<?php

add_action('after_setup_theme', 'sh_theme_setup');

function sh_theme_setup()
{
	
	global $wp_version;
	if(!defined('SH_VERSION')) define('SH_VERSION', '1.0');
	if( !defined( 'SH_NAME' ) ) define( 'SH_NAME', 'wp_furniture' );
	if( !defined( 'SH_ROOT' ) ) define('SH_ROOT', get_template_directory().'/');
	if( !defined( 'SH_URL' ) ) define('SH_URL', get_template_directory_uri().'/');	
	include_once( 'includes/loader.php' );
	
	load_theme_textdomain('furniture', get_template_directory() . '/languages');
	add_editor_style();
	//ADD THUMBNAIL SUPPORT
	add_theme_support('post-thumbnails');
	//add_theme_support( 'post-formats', array( 'gallery', 'image', 'quote', 'video', 'audio' ) );
	add_theme_support('menus'); //Add menu support
	add_theme_support('automatic-feed-links'); //Enables post and comment RSS feed links to head.
	add_theme_support('widgets'); //Add widgets and sidebar support
	
	/* WooCommerce Theme Support */
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	add_theme_support( "title-tag" );
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
	/** Register wp_nav_menus */
	if(function_exists('register_nav_menu'))
	{
		register_nav_menus(
			array(
				/** Register Main Menu location header */
				'top_left_menu' => __('Top Left Menu', 'furniture'),
				'top_right_menu' => __('Top Right Menu', 'furniture'),
				'main_menu' => __('Main Menu', 'furniture'),
				'main_menu_2' => __('Main Menu 2', 'furniture'),
				'footer_menu' => __('Footer Menu', 'furniture'),
			)
		);
	}
	if ( ! isset( $content_width ) ) $content_width = 960;
	add_image_size( '377x284', 377, 284, true );
	add_image_size( '270x341', 270, 341, true );
	add_image_size( '370x342', 370, 342, true );
	add_image_size( '370x244', 370, 244, true );
	add_image_size( '84x84', 84, 84, true );
	add_image_size( '292x198', 292, 198, true );
	add_image_size( '350x236', 350, 236, true );
	add_image_size( '189x211', 189, 211, true );
	add_image_size( '249x260', 249, 260, true );
	add_image_size( '635x491', 635, 491, true );
	add_image_size( '830x390', 830, 390, true );
	add_image_size( '65x65', 65, 65, true );
	

	if(sh_set(_WSH()->option(), 'compress_js_css') && !class_exists('Minit')){
		include_once 'includes/helpers/minit.php';
	}
}


function sh_widget_init()
{
	global $wp_registered_sidebars;
	$theme_options = _WSH()->option();
	if( class_exists( 'SH_Recent_Posts' ) )register_widget( 'SH_Recent_Posts' );
	if( class_exists( 'SH_Flickr' ) )register_widget( 'SH_Flickr' );
	if( class_exists( 'SH_Foot_Features' ) )register_widget( 'SH_Foot_Features' );
	if( class_exists( 'SH_About_Us' ) )register_widget( 'SH_About_Us' );
	if( class_exists( 'SH_feedburner' ) )register_widget( 'SH_feedburner' );
    
	
	if( class_exists( 'SH_Call_Out' ) )register_widget( 'SH_Call_Out' );
	register_sidebar(array(
	  'name' => __( 'Default Sidebar', 'furniture' ),
	  'id' => 'default-sidebar',
	  'description' => __( 'Widgets in this area will be shown on the right-hand side.', 'furniture' ),
	  'class'=>'',
	  'before_widget'=>'<div id="%1$s" class="widget  row m0 clearfix %2$s">',
	  'after_widget'=>'</div>',
	  'before_title' => '<h4 class="heading">',
	  'after_title' => '</h4>'
	));
	register_sidebar(array(
	  'name' => __( 'Footer Top Sidebar', 'furniture' ),
	  'id' => 'footer-top-sidebar',
	  'description' => __( 'Widgets in this area will be shown in Footer Area.', 'furniture' ),
	  'class'=>'',
	  'before_widget'=>'<div id="%1$s"  class="col-sm-4 footFeature %2$s">',
	  'after_widget'=>'</div>',
	  'before_title' => '',
	  'after_title' => ''
	));
	
	register_sidebar(array(
	  'name' => __( 'Footer Sidebar', 'furniture' ),
	  'id' => 'footer-sidebar',
	  'description' => __( 'Widgets in this area will be shown in Footer Area.', 'furniture' ),
	  'class'=>'',
	  'before_widget'=>'<div id="%1$s"  class="col-sm-3 widget %2$s">',
	  'after_widget'=>'</div>',
	  'before_title' => '<h4>',
	  'after_title' => '</h4>'
	));
	register_sidebar(array(
	  'name' => __( 'Blog Listing', 'furniture' ),
	  'id' => 'blog-sidebar',
	  'description' => __( 'Widgets in this area will be shown on the right-hand side.', 'furniture' ),
	  'class'=>'',
	  'before_widget' => '<div class="widget row m0 ">',
	  'after_widget' => "</div>",
	  'before_title' => '<h4 class="heading">',
	  'after_title' => '</h4>',
	));
	if( !is_object( _WSH() )  )  return;
	$sidebars = sh_set(sh_set( $theme_options, 'dynamic_sidebar' ) , 'dynamic_sidebar' ); 
	foreach( array_filter((array)$sidebars) as $sidebar)
	{
		if(sh_set($sidebar , 'topcopy')) continue ;
		
		$name = sh_set( $sidebar, 'sidebar_name' );
		
		if( ! $name ) continue;
		$slug = sh_slug( $name ) ;
		
		register_sidebar( array(
			'name' => $name,
			'id' => sanitize_title( $slug ),
		    'before_widget' => '<div class="widget">',
	        'after_widget' => "</div>",
	        'before_title' => '<div class="widget-title"><h3><span class="divider"></span>',
	        'after_title' => '</h3></div>',
		) );		
	}
	
	update_option('wp_registered_sidebars' , $wp_registered_sidebars) ;
}
add_action( 'widgets_init', 'sh_widget_init' );
// Update items in cart via AJAX
add_filter('add_to_cart_fragments', 'sh_woo_add_to_cart_ajax');
function sh_woo_add_to_cart_ajax( $fragments ) {
    
	global $woocommerce;
    
	
	ob_start(); ?>
	
    <a class="cart-contents" title="<?php esc_html_e('View Cart', 'furniture'); ?>" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>">    
        <div class="fleft cartCount">                        
            <div class="cartCountInner row m0">
                <span class="badge"><?php echo balanceTags( $woocommerce->cart->cart_contents_count ); ?></span>
            </div>
        </div>
    </a>
	
	<?php $fragments['li.cartbutton'] = ob_get_clean();	
	
	ob_start();?>
    
    <li class="cart-item">
	
	<?php include('includes/modules/wc_cart.php' ); ?>
	
	</li>
	
	<?php $fragments['li.cart-item'] = ob_get_clean(); ?>
    

	<?php ob_start(); ?>

	<a class="cart-contents" title="<?php esc_html_e('View Cart', 'furniture'); ?>" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>">    
		<div class="fleft cartCount">                        
			<div class="cartCountInner row m0">
				<span class="badge"><?php echo balanceTags( $woocommerce->cart->cart_contents_count ); ?></span>
			</div>
		</div>
	</a>
<?php $fragments['a.cart-contents'] = ob_get_clean(); 

	return $fragments;
}

// add_filter( 'woocommerce_enqueue_styles', '__return_false' );

add_action( 'vc_before_init', '_sh_prefix_vcSetAsTheme' );

function _sh_prefix_vcSetAsTheme() {
    vc_manager()->disableUpdater();
    vc_set_as_theme(true);
}

