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

//GA user consent
jQuery(document).ready(function ($) {
    if (Cookies.get('siteseo-user-consent-close') == undefined && Cookies.get('siteseo-user-consent-accept') == undefined) {
        $('.siteseo-user-consent.siteseo-user-message').removeClass('siteseo-user-consent-hide');
        $('.siteseo-user-consent-backdrop').removeClass('siteseo-user-consent-hide');
    }
    $('#siteseo-user-consent-accept').on('click', function () {
        $('.siteseo-user-consent.siteseo-user-message').addClass('siteseo-user-consent-hide');
        $('.siteseo-user-consent-backdrop').addClass('siteseo-user-consent-hide');
        $.ajax({
            method: 'GET',
            url: siteseoAjaxGAUserConsent.siteseo_cookies_user_consent,
            data: {
                action: 'siteseo_cookies_user_consent',
                _ajax_nonce: siteseoAjaxGAUserConsent.siteseo_nonce,
            },
            success: function (data) {
                if (data.data) {
                    $('head').append(data.data.gtag_js);
                    $('head').append(data.data.matomo_js);
                    $('head').append(data.data.clarity_js);
                    $('head').append(data.data.custom);
                    $('head').append(data.data.head_js);
                    $('body').prepend(data.data.body_js);
                    $('body').append(data.data.footer_js);
                }
                Cookies.set('siteseo-user-consent-accept', '1', { expires: Number(siteseoAjaxGAUserConsent.siteseo_cookies_expiration_days) });
            },
        });
    });
    $('#siteseo-user-consent-close').on('click', function () {
        $('.siteseo-user-consent.siteseo-user-message').addClass('siteseo-user-consent-hide');
        $('.siteseo-user-consent-backdrop').addClass('siteseo-user-consent-hide');
        Cookies.set('siteseo-user-consent-close', '1', { expires: Number(siteseoAjaxGAUserConsent.siteseo_cookies_expiration_days) });
        Cookies.remove('siteseo-user-consent-accept');
    });
    $('#siteseo-user-consent-edit').on('click', function () {
        $('.siteseo-user-consent.siteseo-user-message').removeClass('siteseo-user-consent-hide');
        $('.siteseo-user-consent-backdrop').removeClass('siteseo-user-consent-hide');
    });
});
