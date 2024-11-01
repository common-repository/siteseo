/*
* SiteSEO
* https://siteseo.io/
* (c) SiteSEO Team <support@siteseo.io>
*/

/*
Copyright 2016 - 2024 - Benjamin Denis  (email : contact@seopress.org)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Retrieve title / meta-desc from source code
jQuery(document).ready(function ($) {
    const { subscribe, select } = wp.data;
    let hasSaved = false;

    subscribe(() => {
        //var isSavingPost = wp.data.select('core/editor').isSavingPost();
        var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();
        var isSavingMetaBoxes = wp.data.select('core/edit-post').isSavingMetaBoxes();


        if (isSavingMetaBoxes && !isAutosavingPost && !hasSaved) {

            //Post ID
            if (typeof $(".siteseo-metabox-tabs").attr('data_id') !== 'undefined') {
                var post_id = $('.siteseo-metabox-tabs').attr('data_id');
            } else if (typeof $('#siteseo_content_analysis .wrap-siteseo-analysis').attr('data_id') !== 'undefined') {
                var post_id = $('#siteseo_content_analysis .wrap-siteseo-analysis').attr('data_id')
            }

            //Tax origin
            if(typeof $('#siteseo-tabs').attr('data_tax') !== 'undefined'){
                var tax_name = $('#siteseo-tabs').attr('data_tax');
            } else if (typeof $('#siteseo_content_analysis .wrap-siteseo-analysis').attr('data_tax') !== 'undefined') {
                var tax_name = $('#siteseo_content_analysis .wrap-siteseo-analysis').attr('data_tax')
            }

            //Origin
            if (typeof $('.siteseo-metabox-tabs').attr('data_origin') !== 'undefined') {
                var origin = $('.siteseo-metabox-tabs').attr('data_origin');
            } else if (typeof $('#siteseo_content_analysis .wrap-siteseo-analysis').attr('data_origin') !== 'undefined') {
                var origin = $('#siteseo_content_analysis .wrap-siteseo-analysis').attr('data_origin')
            }

            $.ajax({
                method: 'GET',
                url: siteseoAjaxRealPreview.siteseo_real_preview,
                data: {
                    action: 'siteseo_do_real_preview',
                    post_id: post_id,
                    tax_name: tax_name,
                    origin: origin,
                    post_type: $('#siteseo_launch_analysis').attr('data_post_type'),
                    siteseo_analysis_target_kw: $('#siteseo_analysis_target_kw_meta').val(),
                    _ajax_nonce: siteseoAjaxRealPreview.siteseo_nonce,
                },
                beforeSend: function () {
					$('.siteseo-analysis-summary-pill').fadeIn().text('');
					$(".siteseo-analysis-summary-pill").addClass('siteseo-stripes');
                },
                success: function (s) {
                    typeof s.data.og_title === 'undefined' ? og_title = '' : og_title = s.data.og_title.values;
                    typeof s.data.og_desc === 'undefined' ? og_desc = '' : og_desc = s.data.og_desc.values;
                    typeof s.data.og_img === 'undefined' ? og_img = '' : og_img = s.data.og_img.values;
                    typeof s.data.og_url === 'undefined' ? og_url = '' : og_url = s.data.og_url.host;
                    typeof s.data.og_site_name === 'undefined' ? og_site_name = '' : og_site_name = s.data.og_site_name.values;
                    typeof s.data.tw_title === 'undefined' ? tw_title = '' : tw_title = s.data.tw_title.values;
                    typeof s.data.tw_desc === 'undefined' ? tw_desc = '' : tw_desc = s.data.tw_desc.values;
                    typeof s.data.tw_img === 'undefined' ? tw_img = '' : tw_img = s.data.tw_img.values;
                    typeof s.data.meta_robots === 'undefined' ? meta_robots = '' : meta_robots = s.data.meta_robots[0];

                    var data_arr = {
                        og_title: og_title,
                        og_desc: og_desc,
                        og_img: og_img,
                        og_url: og_url,
                        og_site_name: og_site_name,
                        tw_title: tw_title,
                        tw_desc: tw_desc,
                        tw_img: tw_img
                    };

					for(var key in data_arr){
						if(data_arr.length){
							if(data_arr[key].length > 1){
								key = data_arr[key].slice(-1)[0];
							} else {
								key = data_arr[key][0];
							}
						}
					}

                    // Meta Robots
                    meta_robots = meta_robots.toString();

                    $("#siteseo-advanced-alert").empty();

                    var if_noindex = new RegExp('noindex');

                    if(if_noindex.test(meta_robots)){
                        $("#siteseo-advanced-alert").append('<span class="impact high" aria-hidden="true"></span>');
                    }

                    // Google Preview
                    $(".siteseo-search-preview-desktop h3").html(s.data.title);
					$(".siteseo_titles_title_meta").attr("placeholder", s.data.title);
					$(".siteseo-search-preview-desktop .siteseo-search-preview-description").html(s.data.meta_desc);
					$(".siteseo_titles_desc_meta").attr('placeholder', s.data.meta_desc);

                    // Facebook Preview
                    if(data_arr.og_title){
						$("#siteseo_social_fb_title_meta").attr("placeholder", data_arr.og_title[0]);
						$(".siteseo-metabox-fb-preview .siteseo-metabox-fb-title").html(data_arr.og_title[0]);
                    }

					if(data_arr.og_desc){
						$("#siteseo_social_fb_desc_meta").attr("placeholder", data_arr.og_desc[0]);
						$(".siteseo-metabox-fb-preview .siteseo-metabox-fb-desc").html(data_arr.og_desc[0]);
					}

                    if(data_arr.og_img){
						$("#siteseo_social_fb_img_meta").attr("placeholder", data_arr.og_img[0]);
						$(".siteseo-metabox-fb-preview .siteseo-metabox-fb-image img").attr('src', data_arr.og_img[0]);
                    }

                    $(".siteseo-metabox-fb-preview .siteseo-metabox-fb-host").html(data_arr.og_url);

                    // Twitter Preview
					if(data_arr.tw_title){
						$("#siteseo_social_twitter_title_meta").attr("placeholder", data_arr.tw_title[0]);
						$(".siteseo-metabox-x-preview .siteseo-metabox-x-title").html(data_arr.tw_title[0]);
					}

					if(data_arr.tw_desc){
						$("#siteseo_social_twitter_desc_meta").attr("placeholder", data_arr.tw_desc[0]);
					}

					if(data_arr.tw_img){
						$("#siteseo_social_twitter_img_meta").attr("placeholder", data_arr.tw_img[0]);
						$(".siteseo-metabox-x-preview .siteseo-metabox-x-image img").attr("src", data_arr.tw_img[0]);
					}

                    $(".siteseo-metabox-x-host").html('From ' + data_arr.og_url);

					$('#siteseo_robots_canonical_meta').attr('placeholder', s.data.canonical);
					
					$('#siteseo-metabox-content-analysis').load(" #siteseo-metabox-content-analysis", '', siteseo_ca_toggle);
					$('#siteseo-metabox-wrapper #siteseo-metabox-content-analysis').load(" #siteseo-metabox-content-analysis", '', siteseo_ca_toggle);
					$('#siteseo-wrap-notice-target-kw').load(" #siteseo-notice-target-kw", '');
					$('#siteseo-metabox-wrapper #siteseo-wrap-notice-target-kw').load(" #siteseo-notice-target-kw", '');
                },
            });
        }
        hasSaved = !!isSavingMetaBoxes; //isSavingPost != 0;
    });
	
	// Realtime Analysis
	setInterval(siteseo_do_realtime, 20000, $);
});

function siteseo_do_realtime($){

	// We don't want to refresh when user is interacting with the Content analysis, as that will degrade the user experiance as when the update happens it updates the html content.
	if(document.activeElement && $(document.activeElement).closest('#siteseo-ca-tabs').length > 0){
		return;
	}
	
	let post_content = wp.data.select('core/editor').getEditedPostContent(),
	post_id = wp.data.select('core/editor').getCurrentPostId(),
	post_title = wp.data.select('core/editor').getEditedPostAttribute('title'),
	post_type = wp.data.select('core/editor').getCurrentPostType(),
	post_slug = wp.data.select('core/editor').getEditedPostSlug();

	if(!post_id || !post_content){
		return;
	}

	// Collecting the meta data
	let meta = {};
	meta['title'] = $('#siteseo_titles_title_meta')?.val();
	meta['description'] = $('#siteseo_titles_desc_meta')?.val();

	// FB
	meta['og_title'] = $('#siteseo_social_fb_title_meta')?.val();
	meta['og_desc'] = $('#siteseo_social_fb_desc_meta')?.val();
	meta['og_img'] = $('#siteseo_social_fb_img_meta')?.val();

	// Twitter
	meta['tw_title'] = $('#siteseo_social_twitter_title_meta')?.val();
	meta['tw_desc'] = $('#siteseo_social_twitter_desc_meta')?.val();
	meta['tw_img'] = $('#siteseo_social_twitter_img_meta')?.val();

	//Connanical url
	$connanical_url = $('#siteseo_robots_canonical_meta')?.val();

	let tabs = $('.siteseo-metabox-tabs'),
	term_id = tabs.data('term-id'),
	home_id = tabs.data('home-id'),
	post_origin = tabs.attr('data_origin'),
	post_tax = tabs.attr('data_tax'),
	keywords = $('#siteseo_analysis_target_kw_meta')?.val();

	$.ajax({
		url : siteseoAjaxRealPreview.ajax_url,
		method : 'POST',
		data : {
			action : 'siteseo_realtime_analysis',
			post_content : post_content,
			post_title : post_title,
			post_id : post_id,
			post_type : post_type,
			post_origin : post_origin,
			post_tax : post_tax,
			post_slug : post_slug,
			keywords : keywords,
			_ajax_nonce : siteseoAjaxRealPreview.realtime_nonce,
			meta : meta,
		},
		beforeSend: function () {
			$('.siteseo-analysis-summary-pill').fadeIn().text('');
			$(".siteseo-analysis-summary-pill").addClass('siteseo-stripes');
		},
		success:function(res){
			if(res.success){
				$('#siteseo-metabox-content-analysis').load(" #siteseo-metabox-content-analysis", '', siteseo_ca_toggle);
				$('#siteseo-metabox-wrapper #siteseo-metabox-content-analysis').load(" #siteseo-metabox-content-analysis", '', siteseo_ca_toggle);
				$('#siteseo-wrap-notice-target-kw').load(" #siteseo-notice-target-kw", '');
				$('#siteseo-metabox-wrapper #siteseo-wrap-notice-target-kw').load(" #siteseo-notice-target-kw", '');
			}
		}
	})
}