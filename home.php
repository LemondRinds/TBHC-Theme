<?php get_header();?>
<?php $options = get_option(THEME_OPTIONS_NAME);?>
<?php $page    = get_page_by_title('Home');?>
	<div class="row" id="home" data-template="home-nodescription" role="main">
		<?=get_theme_option('home_page_theme') == '2' ? '' : frontpage_events()?>
		<?=get_theme_option('home_page_theme') === '0' ? frontpage_opportunities() : ''?>
		<?=get_theme_option('home_page_theme') == '2' ? '' : frontpage_interests()?>
		<?if(DEBUG){print_r(get_theme_option('home_page_theme'));print_r(gettype(get_theme_option('home_page_theme')));print_r(get_theme_option('home_page_theme')=='2');}?>
 		<?=get_theme_option('home_page_theme') == '2' ? frontpage_scholarship_spotlight() : ''?><?=get_theme_option('home_page_theme') === '0' ? frontpage_spotlights() : ''?>
	</div>
	<!--<div class="container-shadow">
		<span></span>
	</div>-->
</div><!--[container]-->
<?php get_footer();?>
