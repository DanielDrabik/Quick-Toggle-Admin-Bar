jQuery(document).ready(function($) {

    if(ajax_toggle_admin_bar_object.is_hidden === 'true') {
        jQuery('span', this).toggleClass('ab-icon-reverse');
        jQuery('#wpadminbar').toggleClass('hidden-bar');
        jQuery('#wpadminbar ul li:not(#wp-admin-bar-toggle-admin-bar):not(#wp-admin-bar-menu-toggle)').toggle();
    }

    jQuery('#wp-admin-bar-toggle-admin-bar').click(function(){
        jQuery('span', this).toggleClass('ab-icon-reverse');
        jQuery('#wpadminbar').toggleClass('hidden-bar');
        jQuery('#wpadminbar ul li:not(#wp-admin-bar-toggle-admin-bar):not(#wp-admin-bar-menu-toggle)').toggle();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_toggle_admin_bar_object.ajaxurl,
            data: {
                'action': 'toggleadminbar',
                'is_hidden': jQuery('#wpadminbar').hasClass('hidden-bar'),
            },
        });
    })
});
