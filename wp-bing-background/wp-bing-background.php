<?php
/**
 * @package WP_Bing_Background
 * @version 1.1.4
 */
/**
 * Plugin Name: WP Bing Background
 * Description: Change the wordpress's background to the image which provided by <a href="https://www.bing.com/">Bing</a>.
 * Version: 1.1.4
 * Requires at least: 5.0
 * Requires PHP: 5.4
 * Author: Joytou Wu
 * Author URI: http://www.xn--irr040d121a.cn/
 * License: GPL v2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-bing-background
 * Domain Path: /languages
 * 
 * WP Bing Background is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * WP Bing Background is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with WP Bing Background. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

require 'includes/lessc.inc.php';
require 'includes/imgcompress.class.php';

/**
 * Class for WordPress plugin WP Bing Background
 * @access public
 * @author Joytou
 * @version 1.0.0
 * @license GPL v2
 */
class wp_bing_background {
    
    /**
     * Option name for plugin.
     * @var string
     */
    const OPTION_NAME = 'wp_bing_background_options';
    
    /**
     * Option fields in setting page.
     * @var array
     * @example 
     * [
     *      'id' => '{$field_id}',
     *      'name' => '{$field_name}',
     *      'default_value' => '{$field_default_value}',
     *      'type' => '{$field_type}',
     *      'desc' => '{$field_description}',
     *      'required' => {$field_is_required_boolean},
     *      'attrs' => {$field_others_attr_array},
     *  ]
     */
    const OPTIONS_FIELD = [
    	[
    		'id' => 'save_directory',
    		'name' => 'Save Directory',
    		'default_value' => 'bing',
    		'type' => 'text',
    		'desc' => 'About the directory name of the static files that are automatically generated in this plugin to be saved to the upload folder. For example: The value is \'bing\', then the files will be saved in \'/wp-content/uploads/bing/\'',
    		'required' => true,
    		'attrs' => [
    		],
    	],
    	
        [
            'id' => 'interface_domain_name',
            'name' => 'Interface Domain Name',
            'default_value' => 'https://www.bing.com',
            'type' => 'url',
            'desc' => 'Bing domain name interface address. It is not recommended to modify it casually',
            'required' => true,
            'attrs' => [
            ],
        ],
        
        [
            'id' => 'opacity',
            'name' => 'Opacity',
            'default_value' => '1',
            'type' => 'range',
            'desc' => 'Opacity of background, the smaller, the more transparent. The minimum is 0 and the maximum is 1',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '1',
                'step' => '0.01',
                'onfocus' => '',
                'onblur' => '',
            ],
        ],
        
        [
            'id' => 'blur',
            'name' => 'Blur',
            'default_value' => '1',
            'type' => 'range',
            'desc' => 'Blur of background. The bigger the blur. The minimum is 0 and the maximum is 200',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '100',
                'step' => '1',
            ],
        ],
        
        [
            'id' => 'brightness',
            'name' => 'Brightness',
            'default_value' => '100',
            'type' => 'range',
            'desc' => 'Brightness of background. The bigger, the brighter. The minimum is 0 and the maximum is 200',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '200',
                'step' => '1',
            ],
        ],
        
        [
            'id' => 'contrast',
            'name' => 'Contrast',
            'default_value' => '100',
            'type' => 'range',
            'desc' => 'Contrastion of background. The minimum is 0 and the maximum is 200',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '200',
                'step' => '1',
            ],
        ],
        
        [
            'id' => 'grayscale',
            'name' => 'Grayscale',
            'default_value' => '0',
            'type' => 'range',
            'desc' => 'Grayscale of background. The larger the value, the more gray it appears. The minimum is 0 and the maximum is 100',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '100',
                'step' => '1',
            ],
        ],
        
        [
            'id' => 'hue_rotate',
            'name' => 'Hue Rotate',
            'default_value' => '0',
            'type' => 'range',
            'desc' => 'Hue rotation to background. The minimum is 0 and the maximum is 360',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '360',
                'step' => '1',
            ],
        ],
        
        [
            'id' => 'invert',
            'name' => 'Invert',
            'default_value' => '0',
            'type' => 'range',
            'desc' => 'Invertion of background. The minimum is 0 and the maximum is 100',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '100',
                'step' => '1',
            ],
        ],
        
        [
            'id' => 'saturate',
            'name' => 'Saturate',
            'default_value' => '100',
            'type' => 'range',
            'desc' => 'Saturation of background. The higher the value, the higher the saturation. The minimum is 0 and the maximum is 200',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '200',
                'step' => '1',
            ],
        ],
        
        [
            'id' => 'sepia',
            'name' => 'Sepia',
            'default_value' => '0',
            'type' => 'range',
            'desc' => 'Sepia of background. The bigger, the darker brown. The minimum is 0 and the maximum is 100',
            'required' => true,
            'attrs' => [
                'min' => '0',
                'max' => '100',
                'step' => '1',
            ],
        ],
        
        [
            'id' => 'compression_proportion',
            'name' => 'Compression Proportion',
            'default_value' => '1',
            'type' => 'range',
            'desc' => 'Compression ratio of background. The smaller the scale, the smaller the storage space of the background picture, and of course the picture will be blurred when displayed. The minimum is 0.1 and the maximum is 1',
            'required' => true,
            'attrs' => [
                'min' => '0.1',
                'max' => '1',
                'step' => '0.1',
            ],
        ],
    ];
    
    /**
     * Initialize the class.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function init() {
        
        //加载i18n翻译
        load_plugin_textdomain( 
            'wp-bing-background', 
            false, 
            dirname( plugin_basename( __FILE__ ) ) . '/languages/' 
        );
        
        //存储配置
        if( isset( $_POST[self::OPTION_NAME] ) ){
            
            $bing_hp_image_archive_plugin_options = array();
                        
            foreach(self::OPTIONS_FIELD as $option){
                switch ( $option['id'] ) {
                    
                    case 'interface_domain_name':
                        $bing_hp_image_archive_plugin_options[$option['id']] = esc_url( $_POST[self::OPTION_NAME][$option['id']] );
                        break;
                        
                    case 'save_directory':
                    	$bing_hp_image_archive_plugin_options[$option['id']] = sanitize_file_name( $_POST[self::OPTION_NAME][$option['id']] );
                        
                    default:
                        $bing_hp_image_archive_plugin_options[$option['id']] = sanitize_text_field( $_POST[self::OPTION_NAME][$option['id']] );
                        break;
                        
                }
            }
                        
            update_option( self::OPTION_NAME, $bing_hp_image_archive_plugin_options );
            
            unset( $bing_hp_image_archive_plugin_options );
            
            $bingDir = wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . get_option( self::OPTION_NAME )['save_directory'];

			//如果不存在要保存静态资源文件的文件夹，则自动创建
            if( !is_dir( $bingDir ) ){
            	mkdir( $bingDir, 0755 );
            }
            
            if( self::complie_less_to_css() ){
                self::add_settings_error(
                    'myUniqueIdentifyer', 
                    esc_attr('settings_updated'), 
                    esc_html__('Setting has updated', 'wp-bing-background'),
                    'updated'
                );
            }
        }
        
        //初始化配置
        foreach(self::OPTIONS_FIELD as $option ) {
            
            //如果不存在配置项，则初始化配置项
            if( !isset( get_option( self::OPTION_NAME )[$option['id']] ) ) {
                $default = get_option( self::OPTION_NAME );
                $default[$option['id']] = $option['default_value'];
                update_option( self::OPTION_NAME, $default );
            }
        }
        
        //每日更新图片
        $bingDir = wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . get_option( self::OPTION_NAME )['save_directory'];
        $today = md5( mktime( 0, 0, 0, date( 'm' ), date( 'd' ), date( 'Y' ) ) );
        // 是否存在今日图片
        if ( !file_exists( $bingDir . DIRECTORY_SEPARATOR . $today . '.jpg' ) ) {
            self::complie_less_to_css();
        }
        
    }
    
    /**
     * Handler for adding settings error.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function add_settings_error( $setting, $code, $message, $type = 'error' ) {
        global $wp_settings_errors;
        
        $wp_settings_errors[] = array(
            'setting' => $setting,
            'code'	=> $code,
            'message' => $message,
            'type'	=> $type
        );
    }
    
    /**
     * Handler for activing the plugin.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function activate() {
        $default = get_option( self::OPTION_NAME );
        if( empty( $default ) ) {
            $default = array();
            foreach ( self::OPTIONS_FIELD as $item ) {
                $default[$item['id']] = $item['default_value'];
            }
            update_option( self::OPTION_NAME, $default );
        }
    }
    
    /**
     * Handler for deactiving the plugin.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function deactivate() {
        $options = get_option( self::OPTION_NAME );
        if ( $options['Delete'] ) {
            delete_option( self::OPTION_NAME );
        }
    }
    
    /**
     * Handler for uninstalling the plugin.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function uninstall() {
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            die;
        }
        
        delete_option( self::OPTION_NAME );
        delete_site_option( self::OPTION_NAME );
    }
    
    /**
     * Get the bing image url.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     * Show the error message if can not rich the bing.
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.1.2
     */
    static function get_image_url(){
    	$response_header = array(
 			'method' => 'GET',
 			'user-agent' => $_SERVER['HTTP_USER_AGENT'],
 			'header' => array(
 				'Content-Type' => 'application/json;charset=UTF-8',
 			),
 		);
        $domain = 'https://www.bing.com';
        $response =  wp_remote_get( $domain . '/HPImageArchive.aspx?format=js&cc=zh&idx=0&n=1' );
        if( wp_remote_retrieve_response_code($response) !== 200 ){
        	foreach($response->get_error_codes() as $k=>$v){
        		self::add_settings_error(
                	'myUniqueIdentifyer',
                	esc_attr( 'settings_error' ),
                	sprintf(
                		'WP Bing Background Error: %s (%s)',
                		$v,
                		$response->get_error_messages()[$k]
                	),
                	'error'
            	);
        	}
            return false;
        }else{
            return $domain. json_decode( wp_remote_retrieve_body($response), true )['images'][0]['url'];
        }
    }
    
    /**
     * Cache the bing image file to local, and rename to md5(today's date).
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     * 
	 * Detect if support some function from GD library, which used in imgcompress Class.
	 * If not, just storage image with original data.
	 * @since 1.1.1
	 * @author Joytou Wu <joytou.wu@qq.com>
	 * Show the error message if get_img_url() return empty, or cannot rich bing.
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.1.2
	 */
    static function cache_image(){
        // 获取 wp 路径
        $imgDir = wp_upload_dir();
        $bingDir = $imgDir['basedir'] . DIRECTORY_SEPARATOR . get_option( self::OPTION_NAME )['save_directory'] . DIRECTORY_SEPARATOR . 'images';
        if ( !file_exists( $bingDir ) ) {
            mkdir( $bingDir, 0755 );
        }
        $today = md5( mktime( 0, 0, 0, date( 'm' ), date( 'd' ), date( 'Y' ) ) );
        // 是否存在今日图片
        if ( !file_exists( $bingDir . DIRECTORY_SEPARATOR . $today . '.jpg' ) ) {
            if ( ($dh = opendir( $bingDir ) ) !== false ){
                while ( ($file = readdir($dh)) !== false ){
                    if ( $file !== '.' && $file !== '..' ){
                        unlink( $bingDir . DIRECTORY_SEPARATOR . $file );
                    }
                }
                closedir( $dh );
            }
            
            $source = self::get_image_url();
            $distance = $bingDir . DIRECTORY_SEPARATOR . $today . '.jpg';
            if( function_exists( 'getimagesize' ) &&
            	function_exists( 'imagedestroy' ) &&
            	function_exists( 'imagecreatefrom' )
            ) {
           		$precent = get_option( self::OPTION_NAME )[ 'compression_proportion' ];
            	$content = ( new imgcompress( $source, $precent ) )->compressImg( $distance );
            } else {
            	$response =  wp_remote_get( $source );
            	if(wp_remote_retrieve_response_code($response) !== 200 ){
        			foreach($response->get_error_codes() as $k=>$v){
        				self::add_settings_error(
                			'myUniqueIdentifyer',
                			esc_attr( 'settings_error' ),
                			sprintf(
                				'WP Bing Background Error: %s (%s)',
                				$v,
                				$response->get_error_messages()[$k]
                			),
                			'error'
            			);
        			}
            		return false;
        		}else{
            		$file_distance = fopen( $distance, 'w' );
        			fwrite( $file_distance, wp_remote_retrieve_body ( $response ) );
        			fclose( $file_distance );
        		}
            }
        }
        $src = $imgDir['baseurl'] . '/' . get_option( self::OPTION_NAME )['save_directory'] . '/images/' . $today . '.jpg';
        return $src;//此处返回url地址，$src不能把'/'更改为DIRECTORY_SEPARATOR
    } 
    
    /**
     * Complier for cascading style sheets.
     * @static
     * @return Boolean Is it can complie the less to css successfully.
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function complie_less_to_css(){
        
        $background_url = self::cache_image();
        $option = get_option( self::OPTION_NAME );
        $blur = $option['blur'];
        $brightness = $option['brightness'];
        $contrast = $option['contrast'];
        $grayscale = $option['grayscale'];
        $hue_rotate = $option['hue_rotate'];
        $invert = $option['invert'];
        $saturate = $option['saturate'];
        $sepia = $option['sepia'];
        $opacity = $option['opacity'];
        
        $less = array(
            "background-url" => "'{$background_url}'",
            "blur" => "{$blur}px",
            "brightness" => "{$brightness}%",
            "contrast" => "{$contrast}%",
            "grayscale" => "{$grayscale}%",
            "hue-rotate" => "{$hue_rotate}deg",
            "invert" => "{$invert}%",
            "saturate" => "{$saturate}%",//less文件中此处必须要加上 ~''，不然会被编译成#000000
            "sepia" => "{$sepia}%",
            "opacity" => "{$opacity}",
        );
        
        $lessc = new lessc();
        $lessc->setVariables( $less );
        
        $bingDir = wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . get_option( self::OPTION_NAME )['save_directory'] . DIRECTORY_SEPARATOR . 'css';
        
        $inputFile = plugin_dir_path( __FILE__ ) . 'css' . DIRECTORY_SEPARATOR . 'style.less';
        $outputFile = $bingDir . DIRECTORY_SEPARATOR .'style.css';
        
        if ( !file_exists( $bingDir ) ) {
            mkdir( $bingDir, 0755 );
        }
        
        $inputString = '';
        
        if( !file_exists( $inputFile ) ) {
            self::add_settings_error(
                'myUniqueIdentifyer',
                esc_attr( 'settings_error' ),
                sprintf(
                    __('File <strong>%s</strong> does not exist', 'wp-bing-background'),
                    $inputFile
                    ),
                'error'
            );
            return false;
        }
        
        $style_less_file = fopen( $inputFile, 'r' );
        
        if( !$style_less_file ){
            self::add_settings_error(
                'myUniqueIdentifyer',
                esc_attr( 'settings_error' ),
                sprintf( 
                    __( 'Unable to open the file <strong>%s</strong>', 'wp-bing-background' ), 
                    $inputFile 
                ),
                'error'
            );
        }
        
        while( !feof( $style_less_file ) ){
            $inputString .= fgetc( $style_less_file );
        }
        fclose( $style_less_file );
        
        $ouputString = $lessc->compile( $inputString );
        
        $style_css_file = fopen( $outputFile, 'w' );
        fwrite( $style_css_file, $ouputString );
        fclose( $style_css_file );
        return true;
    }
    
    /**
     * Handler for load css file.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function load_css(){
        //此处为加载css文件，不能把'/'改为DIRECTORY_SEPARATOR，下同
        wp_enqueue_style( 'wp-bing-background-style', wp_upload_dir()['baseurl'] . '/' . get_option( self::OPTION_NAME )['save_directory'] . '/' . 'css' . '/' . 'style.css' );
    }
    
    /**
     * Handler for load js file.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function load_js(){
        wp_enqueue_script( 'wp-bing-background-style', plugin_dir_url( __FILE__ ) . 'js/myScript.js' );
    }
    
    /**
     * Handler for adding setting page.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function add_setting_page(){
    	add_options_page( 
    	    esc_html__( 'WP Bing Background Setting', 'wp-bing-background' ), 
    	    esc_html__( 'WP Bing Background', 'wp-bing-background' ), 
    	    'manage_options', 
    	    'wp-bing-background', 
    	    array( 'wp_bing_background', 'render_setting_page' ) 
	    );
    }
    
    /**
     * Handler for rendering setting page.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function render_setting_page(){
    	?>
    	<h2><?php esc_html_e( 'WP Bing Background', 'wp-bing-background' );?></h2>
    	<form method="post">
    		<?php
                settings_fields(self::OPTION_NAME);
                do_settings_sections( 'wp_bing_background' );
    		?>
    		<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save', 'wp-bing-background' ); ?>" />
    	</form>
    	<?php 
    }
    
    /**
     * Handler for registing settings and adding settings fields.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function register_settings(){
        
        $required_field_html = '<span class="required">&nbsp;*</span>';
        
        register_setting( 
            'wp_bing_background', 
            self::OPTION_NAME, 
            array( 'wp_bing_background', 'options_validate' ) 
        );
    	
        add_settings_section( 
            'api_settings', 
            esc_html__( 'WP Bing Background Setting', 'wp-bing-background' ), 
            array( 'wp_bing_background', 'setting_section_text' ), 
            'wp_bing_background' 
        );

    	foreach ( self::OPTIONS_FIELD as $item ) {
    	    add_settings_field(
    	        'wp_bing_background_' . $item['id'], 
    	        esc_html__( $item['name'] , 'wp-bing-background' ) . ( $item['required'] ? $required_field_html : '' ),
    	        array('wp_bing_background', 'settings_field' ),
    	        'wp_bing_background',
    	        'api_settings',
    	        $item
	        );
    	}    	
    }
    
    /**
     * Handler for validating post options' data.
     * @static
     * @param Array $input Array for filtering and valitading.
     * @return Array
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function options_validate( $input ){
    	//验证字段
    	$output = array();
    	foreach ( $input as $k => $v ) {
    	    //保护和过滤数据输入
    	    $output[$k] = sanitize_text_field( $v );
    	}
    	
    	return $output;
    }
    
    /**
     * Handler for rendering subtitle for setting page.
     * @static
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function setting_section_text(){
    	//表单二级标题
        esc_html_e( 'WP Bing Background', 'wp-bing-background' );
    }
    
    /**
     * Handler for rendering setting field.
     * @static
     * @param Array $args Array for rendering the setting fields. 
     * @author Joytou Wu <joytou.wu@qq.com>
     * @since 1.0.0
     */
    static function settings_field( $args ) {
    	$key_value_binding = '';
        foreach($args['attrs'] as $k=>$v){
            $key_value_binding .= " " . esc_attr( $k ) . "=\"" . esc_attr( $v ) . "\"";
        }
        
        $options = get_option( self::OPTION_NAME );
        echo "<input id=\"wp_bing_background_". esc_attr( $args['id'] ) ."\" name=\"" . esc_attr( self::OPTION_NAME ) . "[". esc_attr( $args['id'] ) ."]\" type=\"". ( $args['type'] ? esc_attr( $args['type'] ) : 'text' ) ."\" value=\"" . ( isset( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : esc_attr( $args['default_value'] ) ) . "\" aria-describedby=\"" . esc_attr( $args['id'] ) . "-description\"" . $key_value_binding . "/>";
    	
    	if(isset( $args['type'] ) && esc_attr( $args['type'] ) === 'range' ){
    	    echo "<span id=\"wp_bing_background_" . esc_attr( $args['id'] ) ."-value\">" . ( isset( $options[$args['id']] ) ? esc_html( $options[$args['id']] ) : esc_html( $args['default_value'] ) ) . "</span>";
    	}
    	/**
    	 * 此处如果不使用多个echo进行输出，就会无法达到预期效果。
    	 * 预期效果：<p class="description" id="{$id}-description">{$desc}</p>
    	 * 只用一个echo进行输出的实际效果：{$desc}<p class="description" id="{$id}-description"></p>
    	 */
    	echo "<p class=\"description\" id=\"" . esc_attr( $args['id'] ) . "-description\">";
    	echo ( $args['desc'] ? esc_html__( $args['desc'], 'wp-bing-background' ) : '' );
    	echo "</p>";
    }
    
}

register_activation_hook( __FILE__, array( 'wp_bing_background', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'wp_bing_background', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( 'wp_bing_background', 'uninstall' ) );
add_action( 'plugins_loaded', array( 'wp_bing_background', 'init' ) );
add_action( 'wp_head', array( 'wp_bing_background', 'load_css' ) );

if ( is_admin() ) {
    add_action( 'admin_footer', array( 'wp_bing_background', 'load_js' ) );
    add_action( 'admin_menu', array( 'wp_bing_background', 'add_setting_page' ) );
    add_action( 'admin_init', array( 'wp_bing_background', 'register_settings' ) );
}
