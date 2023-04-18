<?php

/**
 * Plugin Name: AdminPass: Password Bypass & Display for WordPress
 * Plugin URI: https://wp.org/adminpass
 * Description: AdminPass allows administrators to bypass password protection and admins/editors to view passwords for protected pages, posts, and custom post types.
 * Version: 1.0.0
 * Requires at least: 5.7
 * Requires PHP: 7.2
 * Author: Belov Digital Agency
 * Author URI: https://belovdigital.agency
 * License: GPL-2.0-or-later
 * Text Domain: adminpass
 */

function enqueue_adminpass_script() {
  $screen = get_current_screen();
  if ( ( current_user_can( 'manage_options' ) || current_user_can( 'edit_others_posts' ) ) && $screen->is_block_editor ) {
      wp_enqueue_script(
          'adminpass-script',
          plugin_dir_url( __FILE__ ) . 'adminpass.js',
          array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-data', 'wp-i18n' ),
          filemtime( plugin_dir_path( __FILE__ ) . 'adminpass.js' ),
          true
      );
  }
}
add_action( 'admin_enqueue_scripts', 'enqueue_adminpass_script' );

function bypass_password_protection_for_admin( $posts, $wp_query ) {
  if ( ! is_single() || ! is_admin_bar_showing() || ! current_user_can( 'manage_options' ) || empty( $posts ) ) {
      return $posts;
  }

  $post = $posts[0];

  if ( ! post_password_required( $post ) ) {
      return $posts;
  }

  $post->post_password = '';
  return array( $post );
}

add_filter( 'the_posts', 'bypass_password_protection_for_admin', 10, 2 );