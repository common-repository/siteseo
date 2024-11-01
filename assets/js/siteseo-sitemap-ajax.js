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

jQuery(document).ready(function($) {
	$('#siteseo-flush-permalinks,#siteseo-flush-permalinks2').on('click', function() {
		$.ajax({
			method : 'GET',
			url : siteseoAjaxResetPermalinks.siteseo_ajax_permalinks,
			data: {
				action: 'siteseo_flush_permalinks',
				_ajax_nonce: siteseoAjaxResetPermalinks.siteseo_nonce,
			},
			success : function( data ) {
				window.location.reload(true);
			},
		});
	});
	$('#siteseo-flush-permalinks,#siteseo-flush-permalinks2').on('click', function() {
		$(this).attr("disabled", "disabled");
		$( '.spinner' ).css( "visibility", "visible" );
		$( '.spinner' ).css( "float", "none" );
	});
});