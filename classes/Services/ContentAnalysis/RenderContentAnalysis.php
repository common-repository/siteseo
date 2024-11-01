<?php
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

namespace SiteSEO\Services\ContentAnalysis;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

class RenderContentAnalysis {
	public function render($analyzes, $analysis_data, $echo = true) {
		$impact = array_values(wp_list_pluck($analyzes, 'impact'));
		$impact_count = array_count_values($impact);
		$impact = array_unique($impact);
		
		$acceptable_svg = [
			'svg' => [
				'role' => true,
				'aria-hidden' => true,
				'focusable' => true,
				'width' => true,
				'height' => true,
				'viewbox' => true,
				'version' => true,
				'xmlns' => true,
				'fill' => true,
			],
			'circle' => [
				'id' => true,
				'r' => true,
				'cx' => true,
				'cy' => true,
				'fill' => true,
				'stroke-dasharray' => true,
				'stroke-dashoffset' => true
			],
			'path' => [
				'fill' => true,
				'd' => true,
			]
		];
		
		$html = '<div id="siteseo-analysis-tabs">
			<div id="siteseo-analysis-tabs-1">
				<div class="siteseo-analysis-summary">';
					if(!empty($impact_count)){
						$html .= '<div class="siteseo-analysis-summary-pill">';
						
							if(!empty($impact_count['high'])){
								$html .= '<span><svg fill="#f33" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 22h-24l12-20z"/></svg>'.$impact_count['high']. ' Errors</span>';
							}

							if(!empty($impact_count['medium'])){
								$warning_count = $impact_count['medium'];
								
								if(isset($impact_count['low'])){
									$warning_count += $impact_count['low'];
								}

								$html .= '<span><svg xmlns="http://www.w3.org/2000/svg" fill="#fa3" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 96C0 60.7 28.7 32 64 32H384c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96z"/></svg>'.$warning_count. ' Warnings</span>';
							}
							
							if(!empty($impact_count['good'])){
								$html .= '<span><svg xmlns="http://www.w3.org/2000/svg" fill="#0c6" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"/></svg><span>'.$impact_count['good']. ' Good</span></span>';
							}
					
						$html .= '</div>';
					} else {
						$impact_score = __('Unable to analyze your content, try to refresh analysis', 'siteseo');
					}

				if(empty($impact)){
					$score = false;
				}

				if(!empty($analysis_data) && is_array($analysis_data)){
					//$analysis_data['score'] = $score;
					update_post_meta(get_the_ID(), '_siteseo_analysis_data', $analysis_data);
				}

				$html .= '</div><!-- .analysis-score -->';

				if ( ! empty($analyzes)) {
					$order = [
						'1' => 'high',
						'2' => 'medium',
						'3' => 'low',
						'4' => 'good',
					];

					usort($analyzes, function ($a, $b) use ($order) {
						$pos_a = array_search($a['impact'], $order);
						$pos_b = array_search($b['impact'], $order);

						return $pos_a - $pos_b;
					});
					
					// A cross icon with solid background
					$high_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/></svg>';
					
					// A triangle with exclamation in it.
					$medium_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>';
					
					// A check inside a solid circle
					$good_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>';

					foreach ($analyzes as $key => $value) {
						$html .= '<div class="siteseo-analysis-block">';
							if (isset($value['title'])) {
								$html .= '<div class="siteseo-analysis-block-title">';
									if(isset($value['impact'])){
										$impact_icon = '';
										
										switch($value['impact']){
											case 'good':
												$impact_icon = $good_icon;
												break;
											
											case 'medium':
											case 'low':
												$impact_icon = $medium_icon;
												break;
												
											case 'high':
												$impact_icon = $high_icon;
												break;
										}

										$html .= '<div><span class="impact '.esc_attr($value['impact']).'" aria-hidden="true">'.$impact_icon.'</span>
										<span class="screen-reader-text">'. sprintf(esc_html__('Degree of severity: %s','siteseo'), esc_html($value['impact'])).'</span>';
									}

									$html .= esc_html($value['title']).'</div>
									<span class="siteseo-arrow" aria-hidden="true"></span>
								</div>';
							}
							if(isset($value['desc'])){
								$html .= '<div class="siteseo-analysis-block-content" aria-hidden="true">'.wp_kses_post($value['desc']).'</div>';
							}
						$html .= '</div><!-- .siteseo-analysis-block -->';
					}
				}
				$html .= '</div><!-- #siteseo-analysis-tabs-1 -->
			</div><!-- #siteseo-analysis-tabs -->';
			
			if(!empty($echo)){
				$allowed_html = wp_kses_allowed_html('post');
				$allowed_html = array_merge($allowed_html, $acceptable_svg);

				echo wp_kses($html, $allowed_html);
				return;
			}
			
			return $html;
	}
	
	public function renderReadibility($analyzes, $analysis_data, $echo = true){
		
		$acceptable_svg = [
			'svg' => [
				'role' => true,
				'aria-hidden' => true,
				'focusable' => true,
				'width' => true,
				'height' => true,
				'viewbox' => true,
				'version' => true,
				'xmlns' => true,
				'fill' => true,
			],
			'circle' => [
				'id' => true,
				'r' => true,
				'cx' => true,
				'cy' => true,
				'fill' => true,
				'stroke-dasharray' => true,
				'stroke-dashoffset' => true
			],
			'path' => [
				'fill' => true,
				'd' => true,
			]
		];

		if(!empty($analyzes)){
			$order = [
				'1' => 'high',
				'2' => 'medium',
				'3' => 'low',
				'4' => 'good',
			];

			usort($analyzes, function ($a, $b) use ($order) {
				$pos_a = array_search($a['impact'], $order);
				$pos_b = array_search($b['impact'], $order);

				return $pos_a - $pos_b;
			});
		
			// A cross icon with solid background
			$high_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/></svg>';
			
			// A triangle with exclamation in it.
			$medium_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>';
			
			// A check inside a solid circle
			$good_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>';
			
			$html = '<div id="siteseo-readibility-tabs">
			<div id="siteseo-readibility-tabs-1">';
			foreach ($analyzes as $key => $value) {
				$html .= '<div class="siteseo-analysis-block">';
					if (isset($value['title'])) {
						$html .= '<div class="siteseo-analysis-block-title">';
							if(isset($value['impact'])){
								$impact_icon = '';
								
								switch($value['impact']){
									case 'good':
										$impact_icon = $good_icon;
										break;
									
									case 'medium':
									case 'low':
										$impact_icon = $medium_icon;
										break;
										
									case 'high':
										$impact_icon = $high_icon;
										break;
								}

								$html .= '<div><span class="impact '.esc_attr($value['impact']).'" aria-hidden="true">'.$impact_icon.'</span>
								<span class="screen-reader-text">'. sprintf(esc_html__('Degree of severity: %s','siteseo'), esc_html($value['impact'])).'</span>';
							}

							$html .= esc_html($value['title']).'</div>
							<span class="siteseo-arrow" aria-hidden="true"></span>
						</div>';
					}
					if(isset($value['desc'])){
						$html .= '<div class="siteseo-analysis-block-content" aria-hidden="true">'.wp_kses_post($value['desc']).'</div>';
					}
				$html .= '</div><!-- .siteseo-analysis-block -->';
			}
		}
		
		$html .= '</div></div>';
		
		if(!empty($echo)){
			$allowed_html = wp_kses_allowed_html('post');
			$allowed_html = array_merge($allowed_html, $acceptable_svg);

			echo wp_kses($html, $allowed_html);
			return;
		}
		
		return $html;
	}
}
