<?php

/*
* SiteSEO
* https://siteseo.io/
* (c) SiteSEO Team <support@siteseo.io>
*/

if(!defined('ABSPATH')){
	die('HACKING ATTEMPT!');
}

// TODO: Handle Structured data and Primary category
function siteseo_render_breadcrumbs(){
	global $post;

	$settings = get_option('siteseo_advanced_option_name', []);
	$breadcrumbs = [];

	$seperator = !empty($settings['breadcrumbs_seperator']) ? $settings['breadcrumbs_seperator'] : '/';
	$prefix = !empty($settings['breadcrumb_prefix']) ? $settings['breadcrumb_prefix'] : '';

	if(!empty($prefix)){
		$breadcrumbs[] = ['title' => $prefix, 'link' => ''];
	}

	if(!empty($settings['breadcrumbs_custom_seperator'])){
		$seperator = $settings['breadcrumbs_custom_seperator'];
	}

	if(is_home()){
		$breadcrumbs[] = single_post_title('', false);
	} elseif (empty($settings['breadcrumbs_home'])){
		$breadcrumbs[] = ['title' => __('Home'), 'link' => get_site_url()];
	}

	if(is_search()){
		$breadcrumbs[] = ['title' => __('Search Result for ', 'siteseo') . get_search_query(), 'link' => ''];
	}

	if(is_404()){
		$breadcrumbs[] = ['title' => __('404 Page Not found', 'siteseo'), 'link' => ''];
	}

	if(is_attachment()){
		$breadcrumbs[] = ['title' => $post->post_parent, 'link' => get_permalink($post->post_parent)];
		$breadcrumbs[] = ['title' => get_the_title(), 'link' => get_permalink()];
	}

	if(function_exists('is_shop') && function_exists('is_product') && function_exists('is_product_category') && function_exists('is_product_tag')
		&& (is_shop() || is_product() || is_product_category() || is_product_tag()) && function_exists('wc_get_page_id')){
		$shop_id = wc_get_page_id('shop');
		
		// If shop ID is same as the home id that means, the hope page is being used as the shop page,
		// So adding a Shop page crumb will make it redundent.
		if(isset($shop_id) && get_option('page_on_front') != $shop_id){
			$breadcrumbs[] = ['title' => get_the_title($shop_id), 'link' => get_permalink($shop_id)];
		}

		if(is_product_category()){
			$term = $GLOBALS['wp_query']->get_queried_object();

			if(!empty($term)){
				$term_trail = siteseo_get_term_ansestors($term);
				if(!empty($term_trail)){
					$breadcrumbs = [...$breadcrumbs, ...$term_trail];
				}
			}
		}

		if(is_product_tag()){
			$term = $GLOBALS['wp_query']->get_queried_object();

			if(!empty($term)){
				$breadcrumbs[] = ['title' => $term->name, 'link' => ''];
			}
		}

		if(is_product()){
			// Getting Product category and its ansestors if any.
			$categories = get_the_terms($post->ID, 'product_cat');

			if(!empty($categories)){
				foreach($categories as $category){
					if(!empty($category) && $category instanceof WP_Term){
						$category_trail = siteseo_get_term_ansestors($category);
						if(!empty($category_trail)){
							$breadcrumbs = [...$breadcrumbs, ...$category_trail];
						}

						$breadcrumbs[] = ['title' => $category->name, 'link' => get_term_link($category)];
						break;
					}
				}
			}

			$breadcrumbs[] = ['title' => get_the_title($post), 'link' => ''];
		}
	}

	if(is_singular() && !is_archive() && (!function_exists('is_product') || !is_product())){
		$categories = get_the_category();
		if(!empty($categories)){
			foreach($categories as $category){
				if(!empty($category) && $category instanceof WP_Term){
					$category_trail = siteseo_get_term_ansestors($category);
					if(!empty($category_trail)){
						$breadcrumbs = [...$breadcrumbs, ...$category_trail];
					}

					$breadcrumbs[] = ['title' => $category->name, 'link' => get_term_link($category)];
					break;
				}
			}
		}

		$breadcrumbs[] = ['title' => get_the_title(), 'link' => ''];
	}

	if(is_archive()){
		if(is_category() || is_tax() || is_tag()){
			$term = $GLOBALS['wp_query']->get_queried_object();
			
			if(!is_tag() && !empty($term) && $term instanceof WP_Term){
				// Adding Term ansestor if any
				if(!is_tag()){
					$term_trail = siteseo_get_term_ansestors($term);
					if(!empty($term_trail)){
						$breadcrumbs = [...$breadcrumbs, ...$term_trail];
					}
				}

				$breadcrumbs[] = ['title' => $term->name, 'link' => ''];
			}
		}

		if(is_author()){
			global $author;

			$author_data = get_userdata($author);
			$breadcrumbs[] = ['title' => $author_data->display_name, 'link' => get_author_posts_url($author_data->ID)];
		}
	}

	$html = '<style>.siteseo-breadcrumbs{display:flex;list-style-type:none;margin:0;padding:0}.siteseo-breadcrumbs-seperator{margin:0 5px;padding:0}</style><div class="siteseo-breadcrumbs-wrap">
	<ul class="siteseo-breadcrumbs">';

	foreach($breadcrumbs as $i => $breadcrumb){
		if(empty($breadcrumb['title'])){
			continue;
		}
		
		$html .= '<li>'.(!empty($breadcrumb['link']) ? '<a href="'.esc_url($breadcrumb['link']).'" title="'.esc_attr($breadcrumb['title']).'">'.esc_html($breadcrumb['title']).'</a>' : esc_html($breadcrumb['title'])).'</li>';

		if(count($breadcrumbs) - 1 != $i){
			$html .= '<div class="siteseo-breadcrumbs-seperator"><span>'.esc_html($seperator).'</span></div>';
		}
	}
	$html .= '</ul></div>';
	
	return $html;
}

function siteseo_breadcrumbs_seperator(){
	$settings = get_option('siteseo_advanced_option_name', []);
	$seperator = '/';
	
	if(!empty($settings)){
		$seperator = !empty($settings['breadcrumbs_seperator']) ? $settings['breadcrumbs_seperator'] : '/';

		if(!empty($settings['breadcrumbs_custom_seperator'])){
			$seperator = $settings['breadcrumbs_custom_seperator'];
		}
	}

	return $seperator;
}

function siteseo_get_term_ansestors($term){
	$ansestors = get_ancestors($term->term_id, $term->taxonomy);
	$ansestors = array_reverse($ansestors);
	$ansestors_res = [];
	
	foreach($ansestors as $ansestor){
		$ansestor = get_term($ansestor, $term->taxonomy);
		if(empty($ansestor) || is_wp_error($ansestor) || !is_a($ansestor, 'WP_Term')){
			continue;
		}

		$ansestors_res[] = ['title' => $ansestor->name, 'link' => get_term_link($ansestor)];
	}

	return $ansestors_res;
}