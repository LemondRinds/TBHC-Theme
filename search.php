<?php $options = get_option(THEME_OPTIONS_NAME);?>
<?php get_header(); ?>
<?php if ($options['enable_google'] or $options['enable_google'] === null){ ?>
Technically working...
<? }else{ ?>
<?php
	$domain  = $options['search_domain'];
	$limit   = (int)$options['search_per_page'];
	$start   = (is_numeric($_GET['start'])) ? (int)$_GET['start'] : 0;
	$results = get_search_results($_GET['s'], $start, $limit, $domain);
?>
I'm just some stuff on a page!
<? } ?>
<?php get_footer();?>