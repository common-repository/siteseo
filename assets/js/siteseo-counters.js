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

//Init tabs
jQuery(document).ready(function(){
    if(jQuery("#siteseo-ca-tabs .wrap-ca-list").length){
        jQuery("#siteseo-ca-tabs .hidden").removeClass("hidden");
        jQuery("#siteseo-ca-tabs").tabs();
    }
});

function pixelTitle(e) {
    inputText = e;
    font = "20px Arial";
	
    canvas = document.createElement("canvas");
    context = canvas.getContext("2d");
    context.font = font;
    width = context.measureText(inputText).width;
    formattedWidth = Math.ceil(width);

    return formattedWidth;
}

function pixelDesc(e) {
    inputText = e;
    font = "14px Arial";
	
    canvas = document.createElement("canvas");
    context = canvas.getContext("2d");
    context.font = font;
    width = context.measureText(inputText).width;
    formattedWidth = Math.ceil(width);

    return formattedWidth;
}

function siteseo_is_valid_url(string) {
    var res = string.match(
        /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g
    );
    return res !== null;
}

function siteseo_social_img(social_slug){
    const $ = jQuery;
	
	let img_input = $('#siteseo_social_'+social_slug+'_img_meta');
	
	// We we input is not there no need to process anything else.
	if(img_input.lenght < 1){
		return;
	}
	
	let img_value = img_input.val();
	
	if(img_value.length < 1 || !siteseo_is_valid_url(img_value)){
		siteseo_show_metabox_input_error(true, img_input, 'Image has invalid URL');
		img_input.val('');
		return;
	}

	// Now lets check if the image exists
	$.get(img_value)
	.done((img_data, textStatus, jqXHR) => {
		let content_length = jqXHR.getResponseHeader('Content-Length'),
		content_type = jqXHR.getResponseHeader('Content-Type'),
		allowed_mime_types = ['image/jpeg', 'image/jpg', 'image/gif', 'image/png', 'image/webp'];

		// Checking mime types
		if(content_type && allowed_mime_types.indexOf(content_type) == -1){
			siteseo_show_metabox_input_error(true, img_input, 'Please upload image of the supported image types only');
			img_input.val('');
			return;
		}

		// Checking if the size of the file is less than 5 MB
		if(!content_length){
			siteseo_show_metabox_input_error(true, img_input, 'Could not retrive the size of the image');
			img_input.val('');
			return;
		}
		
		let max_size = 5;
		if(social_slug == 'fb'){
			max_size = 8;
		}
		
		if((content_length / 1024) > (max_size * 1024)){
			siteseo_show_metabox_input_error(true, img_input, 'Selected image has size '+(content_lenght / 1024)+ 'which is larer than 5MB');
			img_input.val('');
			return;
		}

		// Getting the dimenssions of the image to find if it qualifies the criteria.
		let test_img = new Image();
		test_img.src = img_value;

		$(test_img).one('load', function(){
			img_width = parseInt(test_img.width);
			img_height = parseInt(test_img.height);
			
			if(!img_width || !img_height){
				siteseo_show_metabox_input_error(true, img_input, 'Selected image does not have any width or height');
				img_input.val('');
				return;
			}

			// min size required
			if(social_slug == 'fb'){
				var min_width = 200,
				min_height = 200;
			} else {
				var min_width = 144,
				min_height = 144;
			}
			
			// Checking minimum dimenssions
			if(img_width < min_width || img_height < min_height){
				siteseo_show_metabox_input_error(true, img_input, 'Width or height of this image ('+img_width+'x'+img_height+') is smaller than the minimum requirment');
				img_input.val('');
				return;
			}
			
			let aspect_ratio = (img_width/img_height).toFixed(2);
			// TODO: Show this aspect_ratio to the user.

			test_img = null; // Destroying image
			
			siteseo_show_metabox_input_error(false, img_input); // Removing error if any

			// Updating the image in the preview.
			if(social_slug == 'twitter'){
				$('.siteseo-metabox-x-preview img').attr('src', img_value);
				return;
			}

			$('.siteseo-metabox-'+social_slug+'-preview img').attr('src', img_value);
		});
	}).fail((jqXHR, textStatus, errorThrown) => {
		siteseo_show_metabox_input_error(true, img_input, errorThrown);
		img_input.val('');
	});
}

// Shows error just above the input element
function siteseo_show_metabox_input_error(showError, inputEle, msg = ''){
	let errorSpan = inputEle.prev('span');

	if(!errorSpan.length){
		return;
	}

	if(showError){
		errorSpan.show();
		errorSpan.text(msg);
		return;
	}

	errorSpan.hide();
}

// Content Analysis - Toggle
function siteseo_ca_toggle() {
    const $ = jQuery;
    var stop = false;
    $(document).off('click', '.siteseo-analysis-block-title').on('click', '.siteseo-analysis-block-title', function (e) {
		if(stop){
			e.stopImmediatePropagation();
			e.preventDefault();
			stop = false;
		}

        $(this).toggleClass("open");
        $(this).attr('aria-expanded', ($(this).attr('aria-expanded') == "false" ? true : false));
        $(this).next(".siteseo-analysis-block-content").toggle();
        $(this).next(".siteseo-analysis-block-content").attr('aria-hidden', ($(this).parent().parent().next(".siteseo-analysis-block-content").attr('aria-hidden') == "true" ? false : true));
    });

    // Show all
    $(document).on('click', '#expand-all', function (e) {
        e.preventDefault();
        $(".siteseo-analysis-block-content").show();
        $(".siteseo-analysis-block-title button").attr('aria-expanded', true);
        $(".siteseo-analysis-block-content").attr('aria-hidden', false);
    });
	
    // Hide all
    $(document).on('click', '#close-all', function (e) {
        e.preventDefault();
        $(".siteseo-analysis-block-content").hide();
        $(".siteseo-analysis-block-title button").attr('aria-expanded', false);
        $(".siteseo-analysis-block-content").attr('aria-hidden', true);
    });
}

//Tagify
var input = document.querySelector(
    'input[name="siteseo_analysis_target_kw"]'
);

var siteseo_metabox_tag = new Tagify(input, {
    originalInputValueFormat: (valuesArr) =>
        valuesArr.map((item) => item.value).join(","),
});

 // Listen for add/remove events on the second Tagify instance
siteseo_metabox_tag.on('change', function() {
	siteseo_sync_kw_tags(siteseo_metabox_tag, siteseo_sidebar_tag);
});

function siteseo_google_suggest(data){
    const $ = jQuery;

    var raw_suggestions = String(data);
    var suggestions_array = raw_suggestions.split(",");

    var i;
    for (i = 0; i < suggestions_array.length; i++) {
        if (
            suggestions_array[i] != null &&
            suggestions_array[i] != undefined &&
            suggestions_array[i] != "" &&
            suggestions_array[i] != "[object Object]"
        ) {
            document.getElementById("siteseo_suggestions").innerHTML +=
                '<li><a href="#" class="siteseo-suggest-btn components-button is-secondary">' +
                suggestions_array[i] +
                "</a></li>";
        }
    }

    $(".siteseo-suggest-btn").click(function (e) {
        e.preventDefault();

        siteseo_metabox_tag.addTags($(this).text());
    });
}

jQuery(document).ready(function($){
	siteseo_analysis_init($);
});

function siteseo_analysis_init($){

    var siteseo_do_real_preview = function(){

        //Post ID
        if(typeof jQuery(".siteseo-metabox-tabs").attr("data_id") !== "undefined") {
            var post_id = jQuery('.siteseo-metabox-tabs').attr("data_id");
        } else if (typeof jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_id") !== "undefined") {
            var post_id = jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_id")
        }

		if(!post_id){
			return;
		}
		
        //Tax origin
        if (typeof jQuery("#siteseo-tabs").attr("data_tax") !== "undefined") {
            var tax_name = jQuery("#siteseo-tabs").attr("data_tax");
        } else if (typeof jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_tax") !== "undefined") {
            var tax_name = jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_tax")
        }

        //Origin
        if (typeof jQuery(".siteseo-metabox-tabs").attr("data_origin") !== "undefined") {
            var origin = jQuery(".siteseo-metabox-tabs").attr("data_origin");
        } else if (typeof jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_origin") !== "undefined") {
            var origin = jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_origin")
        }

        jQuery.ajax({
            method: "GET",
            url: siteseoAjaxRealPreview.siteseo_real_preview,
            data: {
                action: "siteseo_do_real_preview",
                post_id: post_id,
                tax_name: tax_name,
                origin: origin,
                post_type: jQuery("#siteseo_launch_analysis").attr(
                    "data_post_type"
                ),
                siteseo_analysis_target_kw: jQuery(
                    "#siteseo_analysis_target_kw_meta"
                ).val(),
                _ajax_nonce: siteseoAjaxRealPreview.siteseo_nonce,
            },
            beforeSend: function () {
                $('.siteseo-analysis-summary-pill').fadeIn().text('');
				$(".siteseo-analysis-summary-pill").addClass('siteseo-stripes');
            },
            success: function (s) {
				
                typeof s.data.og_title === "undefined"
                    ? (og_title = "")
                    : (og_title = s.data.og_title.values);
                typeof s.data.og_desc === "undefined"
                    ? (og_desc = "")
                    : (og_desc = s.data.og_desc.values);
                typeof s.data.og_img === "undefined"
                    ? (og_img = "")
                    : (og_img = s.data.og_img.values);
                typeof s.data.og_url === "undefined"
                    ? (og_url = "")
                    : (og_url = s.data.og_url.host);
                typeof s.data.og_site_name === "undefined"
                    ? (og_site_name = "")
                    : (og_site_name = s.data.og_site_name.values);
                typeof s.data.tw_title === "undefined"
                    ? (tw_title = "")
                    : (tw_title = s.data.tw_title.values);
                typeof s.data.tw_desc === "undefined"
                    ? (tw_desc = "")
                    : (tw_desc = s.data.tw_desc.values);
                typeof s.data.tw_img === "undefined"
                    ? (tw_img = "")
                    : (tw_img = s.data.tw_img.values);
                typeof s.data.meta_robots === "undefined"
                    ? (meta_robots = "")
                    : (meta_robots = s.data.meta_robots[0]);

                var data_arr = {
                    og_title: og_title,
                    og_desc: og_desc,
                    og_img: og_img,
                    og_url: og_url,
                    og_site_name: og_site_name,
                    tw_title: tw_title,
                    tw_desc: tw_desc,
                    tw_img: tw_img,
                };

                for (var key in data_arr) {
                    if (data_arr.length) {
                        if (data_arr[key].length > 1) {
                            key = data_arr[key].slice(-1)[0];
                        } else {
                            key = data_arr[key][0];
                        }
                    }
                }

                // Meta Robots
                meta_robots = meta_robots.toString();

                jQuery("#siteseo-advanced-alert").empty();

                var if_noindex = new RegExp("noindex");

                if (if_noindex.test(meta_robots)) {
                    jQuery("#siteseo-advanced-alert").append(
                        '<span class="impact high" aria-hidden="true"></span>'
                    );
                }

                // Google Preview
                title = '';
                if (s.data.title) {
                    title = s.data.title.substr(0, 60) + '...';
                }

                jQuery(".siteseo-search-preview-desktop h3").html(title);
				jQuery(".siteseo_titles_title_meta").attr("placeholder", title);

                meta_desc = '';
                if (s.data.meta_desc) {
                    meta_desc = s.data.meta_desc.substr(0, 160) + '...';
                }

				jQuery(".siteseo-search-preview-desktop .siteseo-search-preview-description").html(meta_desc);
				jQuery(".siteseo_titles_desc_meta").attr('placeholder', meta_desc);

                // Facebook Preview
                if (data_arr.og_title) {
                    jQuery("#siteseo_social_fb_title_meta").attr("placeholder", data_arr.og_title[0]);
					jQuery(".siteseo-metabox-fb-preview .siteseo-metabox-fb-title").html(data_arr.og_title[0]);
                }

                if (data_arr.og_desc) {
                    jQuery("#siteseo_social_fb_desc_meta").attr("placeholder", data_arr.og_desc[0]);
					jQuery(".siteseo-metabox-fb-preview .siteseo-metabox-fb-desc").html(data_arr.og_desc[0]);
                }

                if (data_arr.og_img) {
                    jQuery("#siteseo_social_fb_img_meta").attr("placeholder", data_arr.og_img[0]),
					jQuery(".siteseo-metabox-fb-preview .siteseo-metabox-fb-image img").attr('src', data_arr.og_img[0]);
                }

                jQuery(".siteseo-metabox-fb-preview .siteseo-metabox-fb-host").html(data_arr.og_url);

                // Twitter Preview
                if (data_arr.tw_title) {
                    jQuery("#siteseo_social_twitter_title_meta").attr("placeholder", data_arr.tw_title[0]);
					jQuery(".siteseo-metabox-x-preview .siteseo-metabox-x-title").html(data_arr.tw_title[0]);
                }

                if (data_arr.tw_desc) {
                    jQuery("#siteseo_social_twitter_desc_meta").attr("placeholder", data_arr.tw_desc[0]);
                }

                if (data_arr.tw_img) {
                    jQuery("#siteseo_social_twitter_img_meta").attr("placeholder", data_arr.tw_img[0]);
					jQuery(".siteseo-metabox-x-preview .siteseo-metabox-x-image img").attr("src", data_arr.tw_img[0]);
                }

                jQuery("#siteseo_cpt .twitter-snippet-preview .snippet-twitter-url").html(data_arr.og_url);
				
				jQuery("#siteseo_robots_canonical_meta").attr("placeholder", s.data.canonical);
				
				jQuery("#siteseo-analysis-tabs").load(
					" #siteseo-analysis-tabs-1",
					"",
					siteseo_ca_toggle
				);
				
				jQuery("#siteseo-metabox-wrapper #siteseo-analysis-tabs").load(
					" #siteseo-analysis-tabs-1",
					"",
					siteseo_ca_toggle
				);

				jQuery('#siteseo-wrap-notice-target-kw').load(" #siteseo-notice-target-kw", '');
            },
        });
    }
	
    //siteseo_do_real_preview();
	
	$(document).off("click", "#siteseo_launch_analysis", siteseo_do_real_preview);
	$(document).on("click", "#siteseo_launch_analysis", siteseo_do_real_preview);
    
	siteseo_ca_toggle();

    // Inspect URL
    $('#siteseo_inspect_url').on("click", function () {
        $(this).attr("disabled", "disabled");
        $('.spinner').css("visibility", "visible");
        $('.spinner').css("float", "none");

        //Post ID
        if (typeof jQuery("#siteseo-tabs").attr("data_id") !== "undefined") {
            var post_id = jQuery("#siteseo-tabs").attr("data_id");
        } else if (typeof jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_id") !== "undefined") {
            var post_id = jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_id")
        }

        jQuery.ajax({
            method: "POST",
            url: siteseoAjaxInspectUrl.siteseo_inspect_url,
            data: {
                action: "siteseo_inspect_url",
                post_id: post_id,
                _ajax_nonce: siteseoAjaxInspectUrl.siteseo_nonce,
            },
            success: function () {
                $('.spinner').css("visibility", "hidden");
                $('#siteseo_inspect_url').removeAttr("disabled");
                $("#siteseo-ca-tabs-1").load(" #siteseo-ca-tabs-1");
            }
        });
    });

}
