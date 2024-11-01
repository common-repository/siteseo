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

var siteseo_sidebar_tag;

(function($){
	var siteseo_metabox_search_data = {
		title : '',
	},
	siteseo_metabox_fb_data = {},
	siteseo_metabox_x_data = {};

	let title_preview_count = 0,
	desc_preview_count = 0;
	
	$(document).ready(function(){
		
		// Handling Metabox tabs
		$(document).on('click', '.siteseo-metabox-tab-label', function(){
			let jEle = $(this),
			parent_tab = jEle.closest('.siteseo-metabox-tabs, .siteseo-metabox-subtabs'),
			active_tab = parent_tab.find('.siteseo-metabox-tab-label-active');
			
			if(active_tab.length){
				active_tab.removeClass('siteseo-metabox-tab-label-active');
			}
			
			jEle.addClass('siteseo-metabox-tab-label-active');
			let target = jEle.data('tab');
			
			parent_tab.siblings('.'+target).show();
			parent_tab.siblings('.'+target).siblings('.siteseo-metabox-tab').hide();
		});
		
		// --- Syncing the values in all possible same inputs ---
		
		// Syncing keywords
		$(document).on('change', '[name="siteseo_analysis_target_kw"]', function(){
			$('[name="siteseo_analysis_target_kw"]').val(event.target.value);
		});
	
		// Syncing Robots options
		$(document).on('change', '.siteseo-metabox-robots-options', function(){
			let jEle = $(this);

			if(jEle.is(':disabled')){
				return;
			}

			let target = jEle.attr('name');
			
			if(jEle.is(':checked')){
				$('[name="'+target+'"]').attr('checked', true);
			} else {
				$('[name="'+target+'"]').attr('checked', false);
			}
		});
		
		// Syncing cononical url
		$(document).on('input', '[name="siteseo_robots_canonical"]', function(){
			$('[name="siteseo_robots_canonical"]').val(event.target.value);
		});
		
		// Syncing Primary category
		$(document).on('change', '[name="siteseo_robots_primary_cat"]', function(){
			$('[name="siteseo_robots_primary_cat"]').val(event.target.value);
		});
		
		// Syncing Redirection enabled
		$(document).on('change', '[name="siteseo_redirections_enabled"]', function(){
			$('[name="siteseo_redirections_enabled"]').attr('checked', $(this).is(':checked'));
		});
		
		// Syncing Redirection Logged in status
		$(document).on('change', '[name="siteseo_redirections_logged_status"]', function(){
			$('[name="siteseo_redirections_logged_status"]').val(event.target.value);
		});
		
		// Syncing Redirection type
		$(document).on('change', '[name="siteseo_redirections_type"]', function(){
			$('[name="siteseo_redirections_type"]').val(event.target.value);
		});
		
		// Syncing Redirection value
		$(document).on('input', '[name="siteseo_redirections_value"]', function(){
			$('[name="siteseo_redirections_value"]').val(event.target.value);
		});

		// --- Syncing ends here ---
		
		$(document).on('click', '#siteseo-sidebar-wrapper .siteseo-sidebar-tabs', function(){
			$(this).toggleClass('siteseo-sidebar-tabs-opened');
			$(this).next().slideToggle('fast');
		})
		

		$(document).on('click', '#siteseo-metabox-search-mobile', function(){
			$(this).hide();
			$(this).prev().show();
			$('.siteseo-search-preview-desktop').css('max-width', '414px');
		});
		
		$(document).on('click', '#siteseo-metabox-search-pc', function(){
			$(this).hide();
			$(this).next().show();
			$('.siteseo-search-preview-desktop').css('max-width', '');
		});
		
		// Tags on meta info
		$(document).on('click', '.siteseo-metabox-tag', function(){
			event.preventDefault();

			let jEle = $(event.target),
			tag_value = jEle.attr('data-tag'),
			input = jEle.closest('.siteseo-metabox-tags').next('input, textarea'),
			input_val = input.val();

			if(tag_value){
				let target = input.attr('class');
				
				$('.'+target).val(input_val + ' ' + tag_value);
				input.trigger('input', true);
			}
		});

		// Title event
		$(document).on('input paste', '.siteseo_titles_title_meta', async function(e, wasTriggered){
			// Extracting the value, and resolving the variables if required.
			if(!wasTriggered){
				var title_meta = $(event.target).val();
				
				$('.siteseo_titles_title_meta').val(title_meta);

				// If the meta field is empty then show the post title
				if(title_meta.length < 1){
					title_meta = $(event.target).attr('placeholder');
				}
			} else {
				var title_meta = $('#siteseo_titles_title_meta').val();
				
				$('.siteseo_titles_title_meta').val(title_meta);

				if(title_meta.length < 1){
					title_meta = $('.siteseo_titles_title_meta').attr('placeholder');
				}
			}
			
			if(title_meta.indexOf('%%') != -1){
				siteseo_debounce(() => siteseo_resolve_variable(title_meta), (wasTriggered ? 0 : 300));
			} else{
				siteseo_update_title(title_meta);
			}

		});

		// Meta Description event
		$(document).on('input', '.siteseo_titles_desc_meta', async function(e, wasTriggered){
			// Extract the desc and resolve the variable if required
			if(!wasTriggered){
				var desc_meta = $(event.target).val();
				$('.siteseo_titles_desc_meta').val(desc_meta); // To sync all inputs

			} else {
				var desc_meta = $('.siteseo_titles_desc_meta').val();
				$('.siteseo_titles_desc_meta').val(desc_meta); // To sync all inputs
			}
			
			if(desc_meta.indexOf('%%') != -1){
				siteseo_debounce(() => siteseo_resolve_variable(desc_meta, true), (wasTriggered ? 0 : 300));
			} else {
				siteseo_update_desc(desc_meta);
			}
		});

		// Handling events for Social inputs
		$(document).on('input paste', '#siteseo_social_fb_title_meta', function(){
			let title_ele = $('.siteseo-metabox-fb-preview .siteseo-metabox-fb-title');

			$(document.querySelectorAll('#siteseo_social_fb_title_meta')).val(event.target.value);
			title_ele.html(event.target.value);
		});
		
		$(document).on('input paste', '#siteseo_social_fb_desc_meta', function(){
			let desc_ele = $('.siteseo-metabox-fb-preview .siteseo-metabox-fb-desc');
			$(document.querySelectorAll('#siteseo_social_fb_desc_meta')).val(event.target.value);
			desc_ele.html(event.target.value);
		});
		
		$(document).on('input paste', '#siteseo_social_twitter_title_meta', function(){
			let title_ele = $('.siteseo-metabox-x-preview .siteseo-metabox-x-title');
			$(document.querySelectorAll('#siteseo_social_twitter_title_meta')).val(event.target.value);
			title_ele.html(event.target.value);
		});
		
		$(document).on('input paste', '#siteseo_social_twitter_desc_meta', function(){
			$(document.querySelectorAll('#siteseo_social_twitter_desc_meta')).val(event.target.value);
		});
		
		$(document).on('change paste', '#siteseo_social_twitter_img_meta', function(e, wasTriggered = false){
			if(wasTriggered){
				$('[name="siteseo_social_twitter_img"]').val($('#siteseo_social_twitter_img_meta').val());
				return;
			}

			let img_ele = $('.siteseo-metabox-x-preview .siteseo-metabox-x-image img');
			$('[name="siteseo_social_twitter_img"]').val(event.target.value);
			img_ele.attr('src', event.target.value);
		});
		
		$(document).on('change paste', '#siteseo_social_fb_img_meta', function(e, wasTriggered = false){
			if(wasTriggered){
				$('[name="siteseo_social_fb_img"]').val($('#siteseo_social_fb_img_meta').val());
				return;
			}

			let img_ele = $('.siteseo-metabox-fb-preview .siteseo-metabox-fb-image img');
			$('[name="siteseo_social_fb_img"]').val($('#siteseo_social_fb_img_meta').val());
			img_ele.attr('src', event.target.value);
		});
		
		//All variables
		siteseo_universal_tag_dropdown();
		
		let debounce;
		function siteseo_debounce(func, timeout = 300){
			clearTimeout(debounce);
			debounce = setTimeout(() => {
				func.apply(this, arguments);
			}, timeout);
		}
	});

})(jQuery);
	
// All variables
function siteseo_universal_tag_dropdown(){
	
    let alreadyBind = false;
	
    jQuery('.siteseo-tag-dropdown').each(function (item){
        const _self = jQuery(this);

        var handleClickLi = function(current) {
            if (_self.hasClass('tag-title')) {
                jQuery('.siteseo_titles_title_meta').val(
                    siteseo_get_field_length(jQuery('.siteseo_titles_title_meta')) +
                    jQuery(current).attr('data-value')
                );
                jQuery('.siteseo_titles_title_meta').trigger('paste', true);
            }
            if (_self.hasClass('tag-description')) {
                jQuery('.siteseo_titles_desc_meta').val(
                    siteseo_get_field_length(jQuery('.siteseo_titles_desc_meta')) +
                    jQuery(current).attr("data-value")
                );
                jQuery(".siteseo_titles_desc_meta").trigger("paste", true);
            }
        }
		
		function handleSearch(current){
			let search_value = current.value.toLowerCase();
			
			jQuery(current).closest('.siteseo-tag-variables-list').find('li').filter(function(){
				jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(search_value) > - 1);
			});
		}

        jQuery(this).off('click').on("click", function () {
            jQuery(this).next(".siteseo-wrap-tag-variables-list").toggleClass("open");

            jQuery(this).next(".siteseo-wrap-tag-variables-list").find("li").on("click", function(e) {
                    handleClickLi(this);
                    e.stopImmediatePropagation();
                })
                .on("keyup", function (e) {
                    if(e.keyCode === 13){
                        handleClickLi(this);
                        e.stopImmediatePropagation();
                    }
                });
				
			jQuery(this).next('.siteseo-wrap-tag-variables-list').find('.siteseo-tag-search-input').on('click input', function(e){
				handleSearch(this);
				e.stopImmediatePropagation();
			});

            function closeItem(e) {
                if (
                    jQuery(e.target).hasClass("dashicons") ||
                    jQuery(e.target).hasClass("siteseo-tag-single-all")
                ) {
                    return;
                }

                alreadyBind = false;
                jQuery(document).off('click', closeItem);
                jQuery('.siteseo-wrap-tag-variables-list').removeClass('open');
            }

            if (!alreadyBind) {
                alreadyBind = true;
                jQuery(document).on("click", closeItem);
            }
        });
    });
}

// Resolves %%*%% encoded variables in the template
async function siteseo_resolve_variable(template, isdesc = false){

	let term_id = jQuery(".siteseo-metabox-tabs").data('term-id'),
	home_id = jQuery(".siteseo-metabox-tabs").data('home-id'),
	post_id = jQuery('.siteseo-metabox-tabs').attr('data_id');

	let res = await jQuery.ajax({
		method: 'GET',
		url: siteseoAjaxRealPreview.ajax_url,
		data: {
			action: 'get_preview_meta_title',
			template: template,
			post_id: post_id,
			term_id: term_id.length === 0 ? undefined : term_id,
			home_id: home_id.length === 0 ? undefined : home_id,
			nonce: siteseoAjaxRealPreview.get_preview_meta_title,
		},
	});
	
	if(!res || !res.success || !res.data){
		return template;
	}

	if(res && res.data.length > 0){
		template = res.data;
	}

	if(isdesc){
		siteseo_update_desc(template);
	} else {
		siteseo_update_title(template);
	}
}

function siteseo_get_field_length(e) {
	if(e.val().length > 0){
		meta = e.val() + ' ';
	} else {
		meta = e.val();
	}

	return meta;
}

var siteseo_tags_is_syncing = false;

function siteseo_sync_kw_tags(source, target){
	if(siteseo_tags_is_syncing) return;

	siteseo_tags_is_syncing = true;

	var tags = source.value; // Get tags from source
	target.loadOriginalValues(tags);
	
	siteseo_tags_is_syncing = false;
}

function siteseo_update_desc(desc_meta){
	let desc_place = jQuery('.siteseo-metabox-search-preview .siteseo-search-preview-description'),
	wrapper = jQuery('.siteseo_titles_desc_meta').closest('.siteseo-metabox-input-wrap');

	// Update the preview
	let ch_count = desc_meta.length,
	percentage = Math.ceil((ch_count/160)*100);
	
	if(ch_count > 160) {
		desc_place.text(desc_meta.substring(0, 160) + '...');
	} else {
		desc_place.text(desc_meta);
	}

	// Update the progress
	wrapper.find('.siteseo-metabox-limits-numbers em').text(ch_count);
	if(percentage <= 100){
		wrapper.find('.siteseo-metabox-limits-meter span').css({'width': percentage+'%', 'background-color': '#00308F'});
		wrapper.find('.siteseo-metabox-limits-numbers em').css({'color': 'unset', 'font-weight': 'normal'});
	} else {
		wrapper.find('.siteseo-metabox-limits-meter span').css({'background-color': '#DC143C', 'width': '100%'});
		wrapper.find('.siteseo-metabox-limits-numbers em').css({'color': 'red', 'font-weight': 'bold'});
	}
}

function siteseo_update_title(title_meta){
	let title_place = jQuery('.siteseo-metabox-search-preview h3'),
	wrapper = jQuery('.siteseo_titles_title_meta').closest('.siteseo-metabox-input-wrap');
	
	// Update the preview
	let ch_count = title_meta.length,
	percentage = Math.ceil((ch_count/60)*100);
	
	if(ch_count > 60) {
		title_place.text(title_meta.substring(0, 60) + '...');
	} else {
		title_place.text(title_meta);
	}
	
	// Update the Progress
	wrapper.find('.siteseo-metabox-limits-numbers em').text(ch_count);
	if(percentage <= 100){
		wrapper.find('.siteseo-metabox-limits-meter span').css({'width': percentage+'%', 'background-color': '#00308F'});
		wrapper.find('.siteseo-metabox-limits-numbers em').css({'color': 'unset', 'font-weight': 'normal'});
	} else {
		wrapper.find('.siteseo-metabox-limits-meter span').css({'width': '100%', 'background-color': 'red'});
		wrapper.find('.siteseo-metabox-limits-numbers em').css({'color': 'red', 'font-weight': 'bold'});
	}
}