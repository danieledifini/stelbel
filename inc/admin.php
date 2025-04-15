<?php

function ft_remove_custom_menus () {
    
	//Remove menus by not allowed user IDs
    $allowed_ids = array(1,2);
    if( !in_array(get_current_user_id(), $allowed_ids) ) {
    	
    	remove_menu_page( 'index.php' );
    	remove_menu_page( 'users.php' );
    	remove_menu_page( 'edit.php?post_type=acf-field-group' );
    	remove_menu_page( 'tools.php' );
    	remove_menu_page( 'options-general.php' );
    	remove_menu_page( 'theme-editor.php' );
    	remove_submenu_page( 'themes.php', 'theme-editor.php' );
    	remove_submenu_page( 'themes.php', 'themes.php' );
    	remove_submenu_page( 'plugins.php', 'plugin-install.php' );
    	remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
    	remove_menu_page( 'update-core.php' );
    	remove_menu_page( 'edit-comments.php' );

    }    
}

add_action( 'admin_menu', 'ft_remove_custom_menus', 999 );

function mytheme_admin_bar_render() {
    global $wp_admin_bar;

    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('updates');
    $wp_admin_bar->remove_menu('new-content');

}
// and we hook our function via
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );


function write_log ( $log )  {
	if ( true === WP_DEBUG ) {
		if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
		} else {
				error_log( $log );
		}
	}
}