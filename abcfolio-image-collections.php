<?php
/*
 * Plugin Name: Image Collections
 * Plugin URI: http://abcfolio.com/help/wordpress-plugin-image-collections/
 * Description: Image Collections for WordPress.
 * Author: abcFolio WordPress Plugins
 * Author URI: http://www.abcfolio.com
 * Version: 1.6.4
 * Text Domain: abcfic-td
 * Domain Path: /languages
 *
 * Image Collections is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Image Collections is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Image Collections. If not, see <http://www.gnu.org/licenses/>.
 *
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Indicates that a clean exit occured. Handled by set_exception_handler
 */
if (!class_exists('E_Clean_Exit')) {
	class E_Clean_Exit extends RuntimeException{}
}

if ( ! class_exists( 'ABCFIC_Image_Collections' ) ) {

/**
 * Main ABCFIC_Image_Collections Class
 */
final class ABCFIC_Image_Collections {

    private static $instance;
    protected $plugin_slug = 'abcfic';


    /**
     * Main PLUGIN Instance
     *
     * Insures that only one instance of plugin exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     */
    public static function instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof ABCFIC_Image_Collections ) ) {
                    self::$instance = new ABCFIC_Image_Collections;
                    self::$instance->setup_constants();
                    self::$instance->table_names();
                    self::$instance->includes();
                    self::$instance->load_textdomain();
            }
            return self::$instance;
    }

    private function __construct ()	{

                set_exception_handler(array(&$this, 'exception_handler'));
                add_action('init', array(&$this, 'handle_upload_request'));
    }

     //Throw error on object clone. We don't want the object to be cloned.
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'abcfic' ), '1.5' );
    }

    //Disable unserializing of the class
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'abcfic' ), '1.5' );
    }

    private function setup_constants() {

        // Plugin version
        if ( ! defined( 'ABCFIC_VERSION' ) ) { define( 'ABCFIC_VERSION', '1.6.4' ); }
        if ( ! defined( 'ABCFIC_FOLDERS' ) ) { define( 'ABCFIC_FOLDERS', serialize (array ('thumbs', 'thumbs2', 'medium', 'originals'))); }
        if ( ! defined( 'ABCFIC_ABSPATH' ) ) {  define('ABCFIC_ABSPATH', ABSPATH); }

        // Plugin Folder QPath
        if( ! defined( 'ABCFIC_PLUGIN_DIR' ) ){ define( 'ABCFIC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); }
        // Plugin Folder URL
        if ( ! defined( 'ABCFIC_PLUGIN_URL' ) ) { define( 'ABCFIC_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); }

        // Plugin folder name
        if( ! defined( 'ABCFIC_PLUGIN_FOLDER' ) ){ define('ABCFIC_PLUGIN_FOLDER', basename( dirname(__FILE__) ) ); }
        // Plugin Root File QPath
        if ( ! defined( 'ABCFIC_PLUGIN_FILE' ) ){ define( 'ABCFIC_PLUGIN_FILE', __FILE__ ); }
     }

    private function table_names() {

        global $wpdb;
        $wpdb->abcficimgs	= $wpdb->prefix . 'abcfic_images';
        $wpdb->abcficcolls      = $wpdb->prefix . 'abcfic_collections';
    }

    //Include required files
    private function includes() {
        if( is_admin() ) {
            require_once ABCFIC_PLUGIN_DIR . 'includes/options.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/class-image-bulk.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/cbos.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/input-builders.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/content-bldrs.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/db.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/lib-css-builders.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/lib-html-builders.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/scripts.php';
            require_once ABCFIC_PLUGIN_DIR . 'includes/modal-forms.php';

            $is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
            if ( $is_ajax ){
                require_once ABCFIC_PLUGIN_DIR . 'includes/class-upload.php';
                require_once ABCFIC_PLUGIN_DIR . 'includes/ajax-handlers.php';
                require_once ABCFIC_PLUGIN_DIR . 'includes/cbos.php';
            }
            else {
                require_once ( ABCFIC_PLUGIN_DIR . 'includes/class-main-menu.php' );
                $this->abcicMainMenu = new ABCFIC_Main_Menu();
            }
        }
        require_once ABCFIC_PLUGIN_DIR . 'includes/db-user.php';
        require_once ABCFIC_PLUGIN_DIR . 'includes/install.php';
    }

    public function get_plugin_slug() {
            return $this->plugin_slug;
    }

     public function load_textdomain() {
        $pslug = $this->plugin_slug;

        // Set filter for plugin's languages directory
        $langDir = ABCFIC_PLUGIN_FOLDER . '/languages/';
        $langDir = apply_filters( 'abcfic_languages_directory', $langDir );

        // Traditional WordPress plugin locale filter
        $locale        = apply_filters( 'plugin_locale',  get_locale(), $pslug );
        $mofile        = sprintf( '%1$s-%2$s.mo', $pslug, $locale );

        // Setup paths to current locale file
        $mofileLocal  = $langDir . $mofile;
        $mofileGlobal = WP_LANG_DIR . '/' . $pslug . '/' . $mofile;

        if ( file_exists( $mofileGlobal ) ) {
                load_textdomain( $pslug, $mofileGlobal );
        } elseif ( file_exists( $mofileLocal ) ) {
                load_textdomain( $pslug, $mofileLocal );
        } else {
                // Load the default language files. ( 'abcfic-td', false, ABCFIC_PLUGIN_DIR . 'languages/' )
                load_plugin_textdomain( $pslug, false, $langDir );
        }
    }

    function handle_upload_request() {
        //abcficupload is set in: tab-uploader.php
        if (isset($_GET['abcficupload'])) {
            require_once ABCFIC_PLUGIN_DIR . 'includes/abcf-async-upload.php';
            throw new E_Clean_Exit();
        }
    }


    /**
    * Handles clean exits gracefully. Re-raises anything else
    * @param Exception $ex
    */
    public function exception_handler($ex) { if (get_class($ex) != 'E_Clean_Exit') throw $ex; }

}
} // End class_exists check


/**
 * The main function responsible for returning the one true ABCFIC_Main instance to functions everywhere.
 * Use this function like you would a global variable, except without needing to declare the global.
 */
function ABCFIC_Main() {
    return ABCFIC_Image_Collections::instance();
}
// Get Image_Collections Running
ABCFIC_Main();
