<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?="\n".header_()."\n"?>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<?php echo google_tag_manager_dl(); ?>

		<?php if ( CB_UID ): ?>
		<script type="text/javascript">
			var _sf_startpt = (new Date()).getTime();

			var CB_UID      = '<?php echo CB_UID; ?>';
			var CB_DOMAIN   = '<?php echo CB_DOMAIN; ?>';
		</script>
		<?php endif;?>
		<?php
		// Always load webfont css for Degree posts and the 404 template.
		if ( $post->post_type == 'degree' || is_404() ) {
			webfont_stylesheet();
		}

		// Load webfonts if enabled by page.
		if ( is_page() ) {
			page_specific_webfonts( $post->ID );
		}

		// Load page-specific css.
		if ( is_page() || ( is_404() && $post = get_page_by_title( '404' ) ) ) {
			esi_include( 'page_specific_stylesheet', $post->ID ); // Wrap in ESI to prevent caching of .css file
		}
		
		?>
		<script type="text/javascript">
			var PostTypeSearchDataManager = {
				'searches' : [],
				'register' : function(search) {
					this.searches.push(search);
				}
			}
			var PostTypeSearchData = function(column_count, column_width, data) {
				this.column_count = column_count;
				this.column_width = column_width;
				this.data         = data;
			}

			var ALERT_RSS_URL				= '<?php echo get_theme_option('alert_feed_url'); ?>';
			var SITE_DOMAIN					= '<?php echo WP_SITE_DOMAIN; ?>';
			var SITE_PATH					= '<?php echo WP_SITE_PATH; ?>';
			var PRINT_HEADER_IMG			= '<?php echo THEME_IMG_URL.'/ucflogo-print.png'; ?>';

		</script>
				<?
			if(get_theme_option('home_page_theme') == '0'){?>
			<script>
			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			  ga('create', 'UA-99997629-1', 'auto');
			  ga('send', 'pageview');

			</script>
			<?}else if(get_theme_option('home_page_theme') == '1'){?>
				<script>
				  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

				  ga('create', 'UA-99997629-4', 'auto');
				  ga('send', 'pageview');

				</script>
			<?}else{
				wp_mail(array('Michael.Callahan@ucf.edu', 'tbhcweb2@ucf.edu'), 'No google analytics code for site.','Please ensure that all sites have a google analytics tracking code placed in the header.php file.');
			}
		?>
		<script type="text/javascript" src="//malsup.github.io/min/jquery.cycle.all.min.js"></script>
		<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
		<!--[if IE]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>			
		<![endif]-->
		<!--[if lt IE 9]>
			<link rel='stylesheet' id='style-min-css'  href='http://tbhccmsdev.smca.ucf.edu/wp-content/themes/TBHC-Theme/static/css/style-no-mqs.min.css' />
		<![endif]-->
		
		<!-- Cheaty style overrides (cuz, yaknow... cascading styles) -->
		<style>
			#jumbotron-logo{
				padding-top: <?=get_theme_option('header_top_pad_mobile')?>;
				padding-bottom: <?=get_theme_option('header_bottom_pad_mobile')?>;
			}
			#jumbotron-logo img{
				width: <?=get_theme_option('header_width_mobile')?>;        
			}					
			#centerpiece_slider ul>img {
				height: <?=get_theme_option('centerpiece_mobile_height')?>;
			}			
			@media(min-width: 770px){
				#jumbotron-logo{
					padding-top: <?=get_theme_option('header_top_pad_desktop')?>;
					padding-bottom: <?=get_theme_option('header_bottom_pad_desktop')?>;
				}
				#jumbotron-logo img{
					width: <?=get_theme_option('header_width_desktop')?>;        
				}		
				#centerpiece_slider ul>img{
					height: <?=get_theme_option('centerpiece_desktop_height')?>;
				}				
			}
		</style>
		</head>
	<body <?php echo body_class(); ?>>

		<?php echo google_tag_manager(); ?>
		
		<nav id="site-nav-xs" class="visible-xs-block navbar navbar-inverse" style="<?=get_theme_option('navbar_bg_color') ? 'background-color:'.get_theme_option('navbar_bg_color').' !important;' : ''?>">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-menu-xs-collapse" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<span class="navbar-brand">Navigation</span>
			</div>
			<div class="collapse navbar-collapse" id="header-menu-xs-collapse">
				<?php
					wp_nav_menu( array(
					'theme_location' => 'header',
					'container' => false,
					'menu_class' => 'menu nav navbar-nav',
					'menu_id' => 'header-menu-xs',
					'walker' => new Bootstrap_Walker_Nav_Menu(),
					'nav_dropdowns'	=> false
					) );
				?>
			</div>
		</nav>
		
		<div id="jumbotron-logo" style="background-color:<?=get_theme_option('header_bg_color')?>">
			<a href="<?=get_site_url()?>">
				<img id="tbhcLogo" src="<?=get_theme_option('header_logo')?>" alt="The Burnett Honors College"/>
			</a>
		</div>
		<nav id="header-nav-wrap" role="navigation" class="screen-only hidden-xs" data-url="<?=admin_url( 'admin-ajax.php' )?>" style="<?=get_theme_option('navbar_bg_color') ? 'background-color:'.get_theme_option('navbar_bg_color').' !important;' : ''?>">
			<?php 
				wp_nav_menu(array(
					'theme_location' => 'header',
					'container' => 'false',
					'menu_class' => 'menu list-unstyled list-inline text-center '.get_header_styles(),
					'menu_id' => 'header-menu',
					'walker' => new Bootstrap_Walker_Nav_Menu(),
					'before' => '<strong>',
					'after' => '</strong>', // THIS IS OVERRIDDEN IN THE WALKER because they wanted fancy behavior for the nav dropdowns
					'nav_dropdowns'	=> true
					)
				);
				/*
				 *	if desktop && homePage
					<div></div> in li item, left:0, pos: fixed, width: 100%
					container for content (to center properly)
					on li hover display
				 * */
			?>
		</nav>
		<?if(is_home() || is_front_page()){?>
			<div class="col-sm-15" id="cntrPceWrap">
				<?php
					$args = array(
					'numberposts' => 1,
					'post_type' => 'centerpiece',
					);
					$latest_centerpiece = get_posts($args);
					echo do_shortcode('[centerpiece id="'.$latest_centerpiece[0]->ID.'"]');
				?>
			</div>
			<?php if(get_theme_option('home_page_theme') != 1 && get_theme_option('home_page_theme') !== 1){ ?>
			<nav id="section-nav-xs" class="navbar navbar-inverse">
			<div id="nav-xs-center-wrap">
				<div class="navbar-section">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#section-menu-xs-collapse" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="section-menu-xs-collapse">
					<?php
						wp_nav_menu( array(
						'theme_location' => 'homepage-sections',
						'container' => false,
						'menu_class' => 'menu nav navbar-nav',
						'menu_id' => 'section-menu-xs',
						'walker' => new Bootstrap_Walker_Nav_Menu(),
						'nav_dropdowns'	=> false
						) );
					?>
				</div>
				</div>
			</nav>		
			<?}
		}?>
		<div class="container">
			<div class="row status-alert" id="status-alert-template" data-alert-id="">
				<div class="col-md-12 col-sm-12 alert-wrap">
					<div class="alert alert-danger alert-block">
						<div class="row">
							<div class="col-md-2 col-sm-2 alert-icon-wrap">
								<div class="alert-icon general"></div>
							</div>
							<div class="col-md-10 col-sm-10 alert-inner-wrap">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<h2>
									<a href="<?php echo get_theme_option('alert_more_information_url'); ?>">
										<span class="title"></span>
									</a>
								</h2>
								<p class="alert-body">
									<a href="<?php echo get_theme_option('alert_more_information_url'); ?>">
										<span class="content"></span>
									</a>
								</p>
								<p class="alert-action">
									<a class="more-information" href="<?php echo get_theme_option('alert_more_information_url'); ?>"></a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if ( is_front_page() ): ?>
			<div id="header" class="sr-only" role="banner">
				<h1><?php echo bloginfo( 'name' ); ?></h1>
			</div>
			<?php endif; ?>
