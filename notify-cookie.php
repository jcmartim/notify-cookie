<?php

/**
 * Plugin Name:       Notify Cookie Jcmartim
 * Plugin URI:        https://jcmartim.com.br/plugins/notify-cookie
 * Description:       Simple notification of cookies to put on your website.
 * Version:           1.0
 * Author:            João Carlos Martimbianco
 * Author URI:        https://jcmartim.com.br
 * Requires at least: 4.7
 * Tested up to:      5.7.1
 * Requires PHP:      7.0
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       notify-cookie
 * Domain Path:       /languages
 **/
//*

//Namespace
namespace JCM_Notify_Cookie;

// Safety!
if( ! defined( 'ABSPATH' ) ) {
  return;
}

if(! function_exists('add_action')){
  echo __('Direct access to this plugin is not allowed!', 'notify-cookie');
  exit;
}

//******** Initialize the translation. ********
if( ! function_exists( 'notify_cookie_init' ) ) {

  function notify_cookie_init() {
    load_plugin_textdomain( 'notify-cookie', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
  }
  add_action( 'plugins_loaded', 'JCM_Notify_Cookie\notify_cookie_init' );

}

//Plugin settings page
if( ! function_exists( 'notify_cookie' ) ) {

  function notify_cookie() {
    register_setting(
      'notify-cookie-settings-group',
      'text_notify_cookie',
      array(
        'type'                => 'string', 
        'default'             => "This website uses cookies to ensure you get the best experience. By using our services, you consent to such monitoring.",
        'sanitize_callback'   => function($value) {
          if($value === '') {
            add_settings_error(
              'text_notify_cookie',
              esc_attr( 'text_notify_cookie_error' ),
              __('Error: Please enter the notification message in the "Notification text" field!', 'notify-cookie'),
              'error'
            );
          }
          return $value;
        },
      )
    );
    register_setting(
      'notify-cookie-settings-group',
      'select_notify_cookie',
      array(
        'sanitize_callback'     => function($value) {
          if($value == 0) {
            add_settings_error(
              'select_notify_cookie',
              esc_attr( 'select_notify_cookie_error' ),
              __('Error: Please select your "Privacy Policy" page in the page selector!', 'notify-cookie'),
              'error'
            );
          }
          return $value;
        }
      )
    );
  }
  add_action( 'admin_init', 'JCM_Notify_Cookie\notify_cookie');

}

//******** Front-end settings page. *******
if( ! function_exists( 'notify_cookie_menu' ) ) {

  function notify_cookie_menu() {
    add_options_page(
      'Notify Cookie Jcmartim',
      'Notify Cookie',
      'manage_options',
      'notify-cookie',
      'JCM_Notify_Cookie\notify_cookie_html'
    );
  }
  add_action( 'admin_menu', 'JCM_Notify_Cookie\notify_cookie_menu' );

}


if( ! function_exists( 'notify_cookie_html' ) ) {

  function notify_cookie_html() {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.', 'notify-cookie' ) );
    }
  ?>
  <div class="wrap">
  <style>
    .notify-cookie {background-color: #fff; padding: 20px; box-shadow: 0 0 4px #c6c6c6; border-radius: 10px}
    .notify-cookie h1 { font-size: 44px; font-weight: bold; margin: 0; margin-bottom: 20px; color: #666}
    .notify-cookie h4 { font-size: 24px; font-weight: bold; margin: 0; margin-bottom: 20px; color: #666}
    .notify-cookie p { font-size: 1rem; margin: 0; color: #444; margin-bottom: 20px}
  </style>
    <div class="notify-cookie">
      <h1><?php echo esc_html( get_admin_page_title() ) ?></h1>
      <p><?php echo esc_html_e("This plugin was originally designed to meet a personal need of mine, while developing a theme for my website. It was then that I decided to make it available to the community.", 'notify-cookie'); ?></p>
      <h4><?php echo esc_html_e("What is this plugin for?", 'notify-cookie'); ?></h4>
      <p><?php echo esc_html_e("The idea is to notify users of the site, in an easy way, that some data is collected, through cookies, so that you guarantee that your website, is in accordance with the laws of the European Union when it is visited by a user from the region . I know that there are several plugins that are much more complex than this one, but as I said, the idea was to do something simple and practical. This will not consume large resources of the site and will do the job!", 'notify-cookie'); ?></p>
      <h4><?php echo esc_html_e("How it works?", 'notify-cookie'); ?></h4>
      <p><?php echo esc_html_e("This simple plugin saves a file in the localStorage of the user's browser, after he clicks the proceed button, thus ensuring that he does not have to click again when returning to the website or when changing pages. Unless the browser is closed and opened again, the user then has to accept by clicking the button again.", 'notify-cookie'); ?></p>
      <h4><?php echo esc_html_e("Did you like this plugin?", 'notify-cookie'); ?></h4>
      <p><?php echo esc_html_e("This plugin is free and will be like this forever. If you liked it, consider donating some small change to help us maintain and perfect this project. Well, the plugin is free, but its development requires a lot of effort and dedication, not counting the costs for its maintenance. Thanks!", 'notify-cookie'); ?></p>
      <form action="https://www.paypal.com/donate" method="post" target="_black">
        <input type="hidden" name="hosted_button_id" value="3E7V4PQ2Y5C84" />
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
      </form>
    </div>
  </div>
  <form class="form-table" method="post" action="options.php">
      <?php 
        settings_fields( 'notify-cookie-settings-group' );
        do_settings_sections( 'notify-cookie-settings-group' );
      ?>
      <h2 class="title"><?php echo esc_html_e('Notification text', 'notify-cookie'); ?></h2>
      <p><label for="notify_text"><?php echo __('Write your personalized notification here. This text will appear in the content of the informational message about cookies. Be free to fully edit this content to suit your needs.','notify-cookie'); ?></label></p>
      <p>
        <textarea class="large-text code" id="notify_text" name="text_notify_cookie" rows="3"><?php echo esc_attr( get_option('text_notify_cookie') ); ?></textarea>
      </p>
  
      <?php
        $has_pages = (bool) get_posts(
          array(
            'post_type'      => 'page',
            'posts_per_page' => 1,
            'post_status'    => array(
              'publish',
              'draft',
            ),
          )
        );
      ?>
    <table class="form-table tools-privacy-policy-page" role="presentation">
      <?php if ( $has_pages ) : ?>
      <tr>
        <th scope="row">
          <label for="page_for_privacy_policy">
            <?php echo esc_html_e( 'Select your Privacy Policy page:', 'notify-cookie' );?>
          </label>
          <p><?php echo esc_html_e("Please, here you must have already created a Privacy Policy page. If you haven't already? Now is the time to create one.", 'notify-cookie') ?></p>
        </th>
        <td>
        <?php
        $selectOption = get_option('select_notify_cookie');
        wp_dropdown_pages(
          array(
            'id'                => 'page_for_privacy_policy',
            'name'              => 'select_notify_cookie',
            'show_option_none'  => __( '&mdash; Select &mdash;', 'notify-cookie' ),
            'option_none_value' => '0',
            'selected'          => esc_attr( $selectOption ),
            'post_status'       => array( 'draft', 'publish' ),
          )
        );
        ?>
        </td>
      </tr>
      <?php endif; ?>
    </table>
      <?php
        submit_button();
      ?>
  </form>
  <?php
  }

}

//***** Front-end *******
if( ! function_exists( 'notify_cookies_footer' ) ) {

  function notify_cookies_footer(){
    //Variable containing the notification phrase passed via the plugin's configuration page.
    $contentCookies = esc_attr( get_option('text_notify_cookie') );
    ?>
    <style>
      .cookies.accept { display: none !important }
      .cookies { bottom: 0; width: auto; margin: 20px; z-index: 998; color: #444; position: fixed; background: #fff; border-radius: 7px; box-shadow: 0 0 5px #333; margin: 10px; display: flex; padding: 20px; align-items: center; }
      .cookies .msg-cookies, .cookies .btn-cookies { color: #777; margin: 0; margin-right: 30px; padding: 0 }
      .cookies .btn-cookies { font-size: 1rem; text-transform: uppercase; font-weight: bold; color: #fff; padding: 5px 18px; border-radius: 7px; margin: 0;}
      @media(max-width: 580px) {
        .cookies { flex-direction: column }
        .cookies .btn-cookies {margin-top: 20px; width: 100% }
      }
    </style>
    <div class="cookies">
      <p class="msg-cookies">
      <?php echo$contentCookies?>
      <?php echo esc_html_e('For further clarification, read our', 'notify-cookie') ?> <a href='<?php echo get_permalink(get_option('select_notify_cookie')); ?>'><?php echo esc_html_e('Privacy Policy', 'notify-cookie'); ?></a>!
      </p>
      <button aria-label="Aceitar cookies" class="btn-cookies"><?php echo esc_html_e('Proceed', 'notify-cookie') ?></button>
    </div>
    <script>
      if (localStorage.cookies) {
        document.getElementsByClassName("cookies")[0].classList.add("accept");
      };
      const acceptCookies = function() {
        document.getElementsByClassName("cookies")[0].classList.add("accept");
        localStorage.setItem("cookies", "accept");
      };
      const btnCookies = document.getElementsByClassName("btn-cookies")[0];
      btnCookies.onclick = acceptCookies;
    </script>
    <?php
    }
    add_action('wp_footer', 'JCM_Notify_Cookie\notify_cookies_footer');

}
