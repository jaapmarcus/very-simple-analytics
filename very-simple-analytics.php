<?php
/*
Plugin Name: Very Simple Analytics
Plugin URI: eris.nu
Description: Simple impletation of analytics only loads the tag no facy features
Version: 0.1
Author:  Jaap Marcus
Author URI: http://schipbreukleling.nl
Text Domain: -
*/
	
	
	/*<!-- Global site tag (gtag.js) - Google Analytics -->


*/

Class VerySimpleAnalytics {
	private $options;
	
	
	
	function __construct(){
		add_action('wp_head', array($this, 'loadJavascript'));
		add_action('embed_head', array($this, 'loadJavascript'));
		add_action('admin_menu', array($this,'settings_menu'));
		add_action('admin_init', array($this, 'settings_init'));
		//we need this anyways
		$this -> options = get_option('verysimpleanalytics');
	}
	
	function settings_menu(){
		add_options_page(
			'Very Simple Analytics', // page_title
			'Very Simple Analytics', // menu_title
			'manage_options', // capability
			'very-simple-analytics', // menu_slug
			array( $this, 'settings' ) // function
		);
	}
	
	function settings(){
		?>
			<div class="wrap">
				<h2>Very Simple Analytics</h2>
				<?php settings_errors(); ?>
				<form method="post" action="options.php">
				<?php
					settings_fields( 'very-simple-analytics' );
					do_settings_sections( 'very-simple-analytics' );
					submit_button();
				?>
			</form>
		<?php
	}
	
	function settings_init(){
		register_setting(
			'very-simple-analytics', // option_group
			'verysimpleanalytics', // option_name
			array( $this, 'sanitize' ) // sanitize_callback
		);
		add_settings_section(
			'very-simple-analytics', // id
			'Settings', // title
			array( $this, 'settings_info' ), // callback
			'very-simple-analytics' // page
		);
		add_settings_field(
			'code', // id
			'Analytics Code', // title
			array( $this, 'code' ), // callback
			'very-simple-analytics', // page
			'very-simple-analytics' // section
		);
		add_settings_field(
			'code2', // id
			'Analytics Code Embed', // title
			array( $this, 'code2' ), // callback
			'very-simple-analytics', // page
			'very-simple-analytics' // section
		);
		
	}
	function settings_info(){
		echo 'Please provide the Analytics ID';
	}
	
	function code(){
	
		printf(
			'<input class="regular-text" type="text" name="verysimpleanalytics[code]" id="code" value="%s">',
			isset( $this->options['code'] ) ? esc_attr( $this->options['code']) : ''
		);
	}
	
	function code2(){
	
		printf(
			'<input class="regular-text" type="text" name="verysimpleanalytics[embed]" id="code2" value="%s">',
			isset( $this->options['embed'] ) ? esc_attr( $this->options['embed']) : ''
		);
	}
	
		
	
	function sanitize($verysimpleanalytics){
		return $verysimpleanalytics;
	}
	
	function loadJavascript(){
	if(is_embed()){
		$tags = explode(',',$this->options['embed']);	
		
	}else{
		$tags = explode(',',$this->options['code']);
	}


?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $tags[0]; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

<?php foreach($tags as $tag){
	?>
	gtag('config', '<?php echo $tag;?>');	
	<?php
}
?>

</script>
<?php

	}
}

New VerySimpleAnalytics;
	
?>