<?php disallow_direct_load('single-person.php');?>
<?php get_header(); the_post();?>

	<div class="row page-content person-profile" id="<?=$post->post_name?>">
		<div class="col-md-15 col-sm-15">
			<div id="page-title">
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<h1><?php the_title(); ?></h1>
					</div>
					<?php esi_include( 'output_weather_data', 'col-md-3 col-sm-3' ); ?>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-3 details">
			<?
				$title = get_post_meta($post->ID, 'person_jobtitle', True);
				$image_url = get_featured_image_url($post->ID, 'person-grid-image');
				$email = get_post_meta($post->ID, 'person_email', True);
				$phones = Person::get_phones($post);
			?>
			<img src="<?=$image_url ? $image_url : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg'?>" />
		</div>
		<div class="col-md-12 col-sm-12">
			<article role="main">
				<h2><?=$post->post_title?><?=($title == '') ?: ' - '.$title ?></h2>
				<span class="contact">
					<? if(count($phones)) { ?>
					<? foreach($phones as $phone) { ?>
						<a href="tel:<?=$phone?>"><?=$phone?></a><?=if($phones[$phone] != count($phones)){?>, <?}?>
					<? } ?>
					<? } ?>
					<? if($email != '') { ?>
						<a class="email" href="mailto:<?=$email?>"><?=$email?></a>
					<? } ?>
				</span>
				<?=$content = str_replace(']]>', ']]>', apply_filters('the_content', $post->post_content))?>
			</article>
		</div>
	</div>
</div>

<?php get_footer();?>
