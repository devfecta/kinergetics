<?php
add_action('wp_head', 'wpb_add_googleanalytics');
function wpb_add_googleanalytics() { ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-97065704-2', 'auto');
  ga('send', 'pageview');
</script>
<?php } ?>
<?php
function my_theme_enqueue_styles() {
    $parent_style = 'vantage-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version') );

}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
?>