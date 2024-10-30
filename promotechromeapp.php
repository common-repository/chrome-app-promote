<?php
/**
 * @package chrome-app-promote
 * @version 1.0.2
 */
/*
Plugin Name: Chrome App Promoter Widget
Plugin URI: http://omgjquery.com/chrome-app-promoter-wordpress-plug-in.html
Description: Promote your Chrome App on your Wordpress Blog. Do you have an App in the Chrome Web Store? Do you want a quick and easy way to Promote it through-out your wordpress blog? Than you have come to the right plug-in! This Plug-in Lets you add a sidebar widget to blog with the official chrome Web Store button and also adds a J-Query Plug-in that is designed to emulate Chrome's info-bar and let your visits know that you have a chrome app! Simply Install the plug-in and enter your Webstore App ID on the setting page and your done! Open your blog up to the 120 million+ Google Chrome Users! Get this plug-in now! 
Author: Anthony Delgado
Version: 1.0.2
Author URI: https://www.facebook.com/mrfresh86
*/


class chromepromoclass {
    public function __construct() {
        if ( is_admin() ){
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
        }
    }
	
    public function add_plugin_page(){
        // This page will be under "Settings"
        add_options_page( 'Chrome App Settings Admin', 'Chrome App ID Settings', 'manage_options', 'chromepromo-setting-admin', array( $this, 'create_admin_page' ) );
    }

    public function create_admin_page() {
        ?>
	<div class="wrap">
	    <?php screen_icon(); ?>
	    <h2>Chrome App ID Setting</h2>			
	    <form method="post" action="options.php">
	        <?php
                    // This prints out all hidden setting fields
		    settings_fields( 'chromepromo_option_group' );	
		    do_settings_sections( 'chromepromo-setting-admin' );
		?>
	        <?php submit_button(); ?>
	    </form>
  </br>
  </br>
  </br>
	    <h2>Don't Have your App in the Google Webstore yet?</h2>
  </br>Use the <a href="http://omgjquery.com/chrome-web-app-generator.php" >Chrome Web-App Generator!</a>

	</div>
	<?php
    }
	
    public function page_init() {		
        register_setting( 'chromepromo_option_group', 'array_key', array( $this, 'check_ID' ) );
            
            add_settings_section(
            'setting_section_id',
            'Chrome App ID Setting',
            array( $this, 'print_section_info' ),
            'chromepromo-setting-admin'
        );	
            
        add_settings_field(
            'chrome_app_dev_id', 
            'Chrome App ID (From the  Web Store)', 
            array( $this, 'create_an_id_field' ), 
            'chromepromo-setting-admin',
            'setting_section_id'			
        );		
    }
	
    public function check_ID( $input ) {
        if ( isset( $input['chrome_app_dev_id'] ) ) {
            $mid = $input['chrome_app_dev_id'];			
            if ( get_option( 'chrome_app_id' ) === FALSE ) {
                add_option( 'chrome_app_id', $mid );
            } else {
                update_option( 'chrome_app_id', $mid );
            }
        } else {
            $mid = '';
        }
        return $mid;
    }
	
    public function print_section_info(){
        print 'Enter your Chrome Web Store Apps Unique ID below:';
    }
	
    public function create_an_id_field(){
        ?><input type="text" id="chrome_app_unique_id" name="array_key[chrome_app_dev_id]" value="<?php echo get_option( 'chrome_app_id' ); ?>" /><?php
    }
}

$chromepromoclass = new chromepromoclass();



add_action('wp_head', 'chrome_installer_js');


function chrome_installer_js() {
	/** This is the JS to be added to the header */
	$the_header_chrome_installer_js = '<!-- Chrome App Promote WP-Plug-in by Anthony Isonhisgrind -->';
	$the_header_chrome_installer_js .= '<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/';

	$the_header_chrome_installer_js .= get_option( 'chrome_app_id' );


	$the_header_chrome_installer_js .= '"> ';


$the_header_chrome_installer_js .= "<script> var IMAGES_FOLDER = '" . plugins_url( 'img/', __FILE__ ) . "'; </script>";

	//$the_header_chrome_installer_js .= plugins_url( 'img/', __FILE__ );


	$the_header_chrome_installer_js .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 <script src="';

	$the_header_chrome_installer_js .= plugins_url( 'jquery.extinfobar.js', __FILE__ );

	$the_header_chrome_installer_js .= '"></script> ';

	$the_header_chrome_installer_js .= "	<script>
			$(function () {
				$.fn.extInfobar({
					id: '";

	$the_header_chrome_installer_js .= get_option( 'chrome_app_id' );

	$the_header_chrome_installer_js .= "',

				});

				$(";

	$the_header_chrome_installer_js .= '"#clear").click(function () {
					localStorage.clear();
					window.location = window.location;
				});
			});
		</script> ';

	$the_header_chrome_installer_js .= '<!-- END  CHROME APP PROMO PLUG-IN -->';
 

	echo $the_header_chrome_installer_js;

}






























/**
 * Adds chrome_App_Widget widget.
 */
class chrome_App_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'chrome_App_Widget', // Base ID
			'Chrome Web Store Widget', // Name
			array( 'description' => __( 'Chrome Web Store Widget', 'chrome_App_Widget_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		echo __( '<a onclick="chrome.webstore.install()" href="https://chrome.google.com/webstore/detail/' . get_option( 'chrome_app_id' ) . '" target="_blank" ><img src="' . plugins_url( 'img/chrome-button.png', __FILE__ ) . '" alt="Get Our Chrome App" title="Get Our Chrome App" width="300" /></a>', 'text_domain' );
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Get Our Chrome App!', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class chrome_App_Widget


// register chrome_App_Widget widget
function register_chrome_App_Widget() {
    register_widget( 'chrome_App_Widget' );
}
add_action( 'widgets_init', 'register_chrome_App_Widget' );

//shortcode [chromewebstore]
function chromeshortcode_func( $atts ){

echo '<a onclick="chrome.webstore.install()" href="https://chrome.google.com/webstore/detail/' . get_option( 'chrome_app_id' ) . '" target="_blank" ><img src="' . plugins_url( 'img/chrome-button.png', __FILE__ ) . '" alt="Get Our Chrome App" title="Get Our Chrome App" width="300" /></a>';

}

add_shortcode( 'chromewebstore', 'chromeshortcode_func' );





?>
