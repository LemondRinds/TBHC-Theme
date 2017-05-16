<?php
require_once('functions/base.php');   			# Base theme functions
require_once('functions/feeds.php');			# Where functions related to feed data live
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes
require_once('third-party/truncate-html.php');  # Includes truncateHtml function

//Add theme-specific functions here.

// stuff for cors
add_filter( 'allowed_http_origins', 'add_allowed_origins' );
function add_allowed_origins( $origins ) {
    $origins = array(site_url(null, null, 'http'),site_url(null, null, 'https'),'https://e.issuu.com/issuu-reader3-embed-files/stable/embed.html', site_url(null, null, 'https').'/wp-json/oembed/1.0/embed');
	//print_r($origins);
    return $origins;
}

/**
 * Slider post type customizations
 * Stolen from SmartStart theme
 **/
 
// Custom columns for 'Centerpiece' post type
function edit_centerpiece_columns() {
	$columns = array(
		'cb'          => '<input type="checkbox" />',
		'title'       => 'Name',
		'slide_count' => 'Slide Count'
	);
	return $columns;
}
add_action('manage_edit-centerpiece_columns', 'edit_centerpiece_columns');

// Custom columns content for 'Centerpiece'
function manage_centerpiece_columns( $column, $post_id ) {
	global $post;
	switch ( $column ) {
		case 'slide_count':
			print get_post_meta( $post->ID, 'ss_slider_slidecount', true );
			break;
		default:
			break;
	}
}
add_action('manage_centerpiece_posts_custom_column', 'manage_centerpiece_columns', 10, 2);

// Sortable custom columns for 'Centerpiece'
function sortable_centerpiece_columns( $columns ) {
	$columns['slide_count'] = 'slide_count';
	return $columns;
}
add_action('manage_edit-centerpiece_sortable_columns', 'sortable_centerpiece_columns');

// Change default title for 'Centerpiece'
function change_centerpiece_title( $title ){
	$screen = get_current_screen();
	if ( $screen->post_type == 'centerpiece' )
		$title = __('Enter centerpiece name here');
	return $title;
}
add_filter('enter_title_here', 'change_centerpiece_title');



/**
 * Announcement custom columns
 **/

// Custom columns for 'Announcement' post type
function edit_announcement_columns() {
	$columns = array(
		'cb'           => '<input type="checkbox" />',
		'title'        => 'Name',
		'start_date'   => 'Start Date',
		'end_date'     => 'End Date',
		'publish_date' => 'Publish Date'
	);
	return $columns;
}
add_action('manage_edit-announcement_columns', 'edit_announcement_columns');

// Custom columns content for 'Announcement'
function manage_announcement_columns( $column, $post_id ) {
	global $post;
	switch ( $column ) {
		case 'start_date':
			$start_date = get_post_meta($post->ID, 'announcement_start_date', TRUE) ? date('Y/m/d', strtotime(get_post_meta($post->ID, 'announcement_start_date', TRUE))) : '<span style="font-weight:bold;color:#cc0000;">N/A</span>';
			print $start_date;
			break;
		case 'end_date':
			$end_date = get_post_meta($post->ID, 'announcement_end_date', TRUE) ? date('Y/m/d', strtotime(get_post_meta($post->ID, 'announcement_end_date', TRUE))) : '<span style="font-weight:bold;color:#cc0000;">N/A</span>';
			print $end_date;
			break;
		case 'publish_date':
			if ($post->post_status == 'publish') {
				print get_post_time('Y/m/d', true, $post->ID);
			}
			break;
		default:
			break;
	}
}
add_action('manage_announcement_posts_custom_column', 'manage_announcement_columns', 10, 2);

// Sortable custom columns for 'Announcement'
function sortable_announcement_columns( $columns ) {
	$columns['start_date'] 		= 'start_date';
	$columns['end_date'] 		= 'end_date';
	$columns['publish_date'] 	= 'publish_date';
	return $columns;
}
add_action('manage_edit-announcement_sortable_columns', 'sortable_announcement_columns');

/*
 * Custom grid stuff for spotlights
 * */
// Custom columns for 'spotlight' post type
function edit_spotlight_columns() {
	$columns = array(
	'cb'          => '<input type="checkbox" />',
	'title'       => 'Title',
	'spotlight_start'	=> 'Adv Start Date',
	'spotlight_end'	=> 'Adv End Date',
	'spotlight_category' => 'Category',
	'post' 		  => 'Post',	
	'publish_date'=> 'Date',
	);
	return $columns;
}
add_action('manage_edit-spotlight_columns', 'edit_spotlight_columns');

function sortable_spotlight_columns( $columns ) {
	$columns['publish_date'] = 'publish_date';
	$columns['spotlight_start'] = 'spotlight_start';
	$columns['spotlight_end'] = 'spotlight_end';
	return $columns;
}
add_action('manage_edit-spotlight_sortable_columns', 'sortable_spotlight_columns');

// Custom columns content for 'spotlight'
function manage_spotlight_columns( $column, $post_id ) {
	global $post;
	switch ( $column ) {
		case 'post':
		print get_post_meta( $post->ID, 'spotlight_post_to_home', true );
		break;
		case 'publish_date':
			if ($post->post_status == 'publish') {
				print 'Published'.'<br/>'.get_post_time('Y/m/d', true, $post->ID);
			}
		break;
		case 'spotlight_start':
			if(get_post_meta($post->ID,'spotlight_start',true)){
				print date('Y/m/d', strtotime(get_post_meta($post->ID, 'spotlight_start', TRUE)));
			}
		break;
		case 'spotlight_end':
			if(get_post_meta($post->ID,'spotlight_end',true)){
				print date('Y/m/d', strtotime(get_post_meta($post->ID, 'spotlight_end', TRUE)));
			}
		break;
		case 'spotlight_category':
			print get_post_meta($post->ID, 'spotlight_category', true);
		break;
		default:
		break;
	}
}
add_action('manage_spotlight_posts_custom_column', 'manage_spotlight_columns', 10, 2);

/*
 * Custom grid stuff for opportunities
 * */
// Custom columns for 'opportunity' post type
function edit_opportunity_columns() {
	$columns = array(
	'cb'          => '<input type="checkbox" />',
	'title'       => 'Title',
	'opportunity_start'	=> 'Adv Start Date',
	'opportunity_end'	=> 'Adv End Date',
	'event_groups'	=> 'Event Groups',
	'post' 		  => 'Post',	
	'publish_date'=> 'Date',
	);
	return $columns;
}
add_action('manage_edit-opportunity_columns', 'edit_opportunity_columns');

function sortable_opportunity_columns( $columns ) {
	$columns['orderby'] = 'orderby';
	$columns['publish_date'] = 'publish_date';
	$columns['opportunity_start'] = 'opportunity_start';
	$columns['opportunity_end'] = 'opportunity_end';
	$columns['event_group'] = 'event_groups';	
	return $columns;
}
add_action('manage_edit-opportunity_sortable_columns', 'sortable_opportunity_columns');

function extranet_orderby( $query ) {   
    if( ! $query->is_main_query())
	return;
    $orderby = $query->get( 'orderby'); 
	$type = $query->get( 'post_type' );	
	switch($type){
		case "opportunity":
			switch ( $orderby ) 
			{
				case 'opportunity_start':
					$query->set( 'meta_key', 'opportunity_start' );
					$query->set( 'orderby',  'meta_value_num' );
				break;
				case 'opportunity_end':
					$query->set( 'meta_key', 'opportunity_end' );
					$query->set( 'orderby',  'meta_value_num' );
				break;
				default:
				break;
			}	
		case "spotlight":
		switch ( $orderby ) 
		{
			case 'spotlight_start':
				$query->set( 'meta_key', 'spotlight_start' );
				$query->set( 'orderby',  'meta_value_num' );
			break;
			case 'spotlight_end':
				$query->set( 'meta_key', 'spotlight_end' );
				$query->set( 'orderby',  'meta_value_num' );
			break;
			default:
			break;
		}				
		break;
		default:
		break;
	}
}
is_admin() && add_action( 'pre_get_posts', 'extranet_orderby' );    

// Custom columns content for 'opportunity'
function manage_opportunity_columns( $column, $post_id ) {
	global $post;
	switch ( $column ) {
		case 'post':
		print get_post_meta( $post->ID, 'opportunity_post_to_home', true );
		break;
		case 'publish_date':
		if ($post->post_status == 'publish') {
			print 'Published'.'<br/>'.get_post_time('Y/m/d', true, $post->ID);
		}
		break;
		case 'opportunity_start':
			if(get_post_meta($post->ID,'opportunity_start',true)){
				print date('Y/m/d', strtotime(get_post_meta($post->ID, 'opportunity_start', TRUE)));
			}
		break;
		case 'opportunity_end':
			if(get_post_meta($post->ID,'opportunity_end',true)){
				print date('Y/m/d', strtotime(get_post_meta($post->ID, 'opportunity_end', TRUE)));
			}
		break;
		case 'event_groups':
		$theseTerms = get_the_terms($post, 'event_groups');
		if($theseTerms && (array)$theseTerms === $theseTerms){
			$theseTerms = array_map('manage_columns_terms', $theseTerms);			
			print implode(', ',$theseTerms);
		}
		break;		
		default:
		break;
	}
}
add_action('manage_opportunity_posts_custom_column', 'manage_opportunity_columns', 10, 2);

/*
 * Custom grid stuff for people (persons)
 * */
// Custom columns for 'people (persons)' post type
function edit_people_columns() {
	$columns = array(
	'cb'          => '<input type="checkbox" />',
	'title'       => 'Title',
	'org_group' 		  => 'Organizational Group',	
	'orderby' => 'Sort Name',
	'publish_date'=> 'Date'
	);
	return $columns;
}
add_action('manage_edit-person_columns', 'edit_people_columns');

function manage_columns_terms($term){
	$termLink = get_term_link($term);
	return '<a href="'.$termLink.'">'.$term->name.'</a>';
}

// Custom columns content for 'people (persons)'
function manage_people_columns( $column, $post_id ) {
	global $post;
	switch ( $column ) {
		case 'publish_date':
		if ($post->post_status == 'publish') {
			print 'Published'.'<br/>'.get_post_time('Y/m/d', true, $post->ID);
		}
		break;
		case 'orderby':
		print get_post_meta($post->ID, 'person_orderby_name', true);
		break;
		case 'org_group':
			$theseTerms = get_the_terms($post, 'org_groups');
			if($theseTerms && (array)$theseTerms === $theseTerms){
				$theseTerms = array_map('manage_columns_terms', $theseTerms);			
				print implode(', ',$theseTerms);
			}
		break;
		default:
		break;
	}
}
add_action('manage_person_posts_custom_column', 'manage_people_columns', 10, 2);

// Sortable custom columns for 'persons/people'
function sortable_people_columns( $columns ) {
	$columns['orderby'] = 'orderby';
	$columns['publish_date'] = 'publish_date';
	return $columns;
}
add_action('manage_edit-person_sortable_columns', 'sortable_people_columns');

function get_people_from_org_group(){ 
	$p = array(
		($_REQUEST['org_groups'] ? $_REQUEST['org_groups'] : $_REQUEST['dd_org_groups']),
		(!empty($_REQUEST['org_groups2']) ? 'org_groups2=\''.$_REQUEST['org_groups2'].'\' ' : ''),
		(!empty($_REQUEST['dd_org_groups']) ? ' dd_org_groups=\''.$_REQUEST['dd_org_groups'].'\' dropdown=true '.(!empty($_REQUEST['dd2_org_groups']) ? 'dd2_org_groups=\''.$_REQUEST['dd2_org_groups'].'\' dropdown2=true ' : ' ') : ' '),
		'show_org_groups='.$_REQUEST['show_org_groups'],
		(!empty($_REQUEST['join']) ? ' join=\''.$_REQUEST['join'].'\'' : ' '),
		(!empty($_REQUEST['operator']) ? ' operator=\''.$_REQUEST['operator'].'\'' : ' '),
		(!empty($_REQUEST['show_option_all']) ? ' show_option_all=\''.$_REQUEST['show_option_all'].'\' ' : ' '),
		(!empty($_REQUEST['show_option_all2']) ? ' show_option_all2=\''.$_REQUEST['show_option_all2'].'\' ' : ' '),
		'row_size='.$_REQUEST['row_size'],
	);
	$p = array_map(function($a){ return htmlspecialchars($a); }, $p);
	echo do_shortcode('[person-profile-grid org_groups=\''.$p[0].'\''.$p[1].$p[2].$p[3].$p[4].$p[5].$p[6].$p[7].$p[8].']');
	die();
}

add_action( 'wp_ajax_get_people_from_org_group', 'get_people_from_org_group' );
add_action( 'wp_ajax_nopriv_get_people_from_org_group', 'get_people_from_org_group' );

function get_opps_from_event_group(){
	$p = array(
		($_REQUEST['event_groups'] ? $_REQUEST['event_groups'] : $_REQUEST['dd_event_groups']),
		(!empty($_REQUEST['event_groups2']) ? 'event_groups2=\''.($_REQUEST['event_groups2'] ? $_REQUEST['event_groups2'] : $_REQUEST['dd2_event_groups']).'\' ' : ''),
		(!empty($_REQUEST['dd_event_groups']) ? ' dd_event_groups=\''.$_REQUEST['dd_event_groups'].'\' dropdown=true '.(!empty($_REQUEST['dd2_event_groups']) ? 'dd2_event_groups=\''.$_REQUEST['dd2_event_groups'].'\' dropdown2=true ' : ' ') : ' '),
		(!empty($_REQUEST['join']) ? ' join=\''.$_REQUEST['join'].'\'' : ' '),
		(!empty($_REQUEST['operator']) ? ' operator=\''.$_REQUEST['operator'].'\' ' : ' '),
		(!empty($_REQUEST['show_option_all']) ? ' show_option_all=\''.$_REQUEST['show_option_all'].'\' ' : ' '),
		(!empty($_REQUEST['show_option_all2']) ? ' show_option_all2=\''.$_REQUEST['show_option_all2'].'\' ' : ' '),
	);
	$p = array_map(function($a){ return htmlspecialchars($a); }, $p);
	echo do_shortcode('[opportunity-grid event_groups=\''.$p[0].'\' '.$p[1].$p[2].$p[3].$p[4].$p[5].$p[6].' ]');
	die();
}

add_action( 'wp_ajax_get_opps_from_event_group', 'get_opps_from_event_group' );
add_action( 'wp_ajax_nopriv_get_opps_from_event_group', 'get_opps_from_event_group' );

function get_spots_from_event_group(){
	$p = array(
		($_REQUEST['event_groups'] ? $_REQUEST['event_groups'] : $_REQUEST['dd_event_groups']),
		(!empty($_REQUEST['event_groups2']) ? 'event_groups2=\''.($_REQUEST['event_groups2'] ? $_REQUEST['event_groups2'] : $_REQUEST['dd2_event_groups']).'\' ' : ''),
		(!empty($_REQUEST['dd_event_groups']) ? ' dd_event_groups=\''.$_REQUEST['dd_event_groups'].'\' dropdown=true '.(!empty($_REQUEST['dd2_event_groups']) ? 'dd2_event_groups=\''.$_REQUEST['dd2_event_groups'].'\' dropdown2=true ' : ' ') : ' '),
		(!empty($_REQUEST['join']) ? ' join=\''.$_REQUEST['join'].'\'' : ' '),
		(!empty($_REQUEST['operator']) ? ' operator=\''.$_REQUEST['operator'].'\' ' : ' '),
		(!empty($_REQUEST['show_option_all']) ? ' show_option_all=\''.$_REQUEST['show_option_all'].'\' ' : ' '),
		(!empty($_REQUEST['show_option_all2']) ? ' show_option_all2=\''.$_REQUEST['show_option_all2'].'\' ' : ' '),
	);
	$p = array_map(function($a){ return htmlspecialchars($a); }, $p);
	echo do_shortcode('[spotlight-grid event_groups=\''.$p[0].'\' '.$p[1].$p[2].$p[3].$p[4].$p[5].$p[6].' ]');
	die();
}

add_action( 'wp_ajax_get_spots_from_event_group', 'get_spots_from_event_group' );
add_action( 'wp_ajax_nopriv_get_spots_from_event_group', 'get_spots_from_event_group' );

function hex_and_opacity_to_rgba($color, $opacity){
	/*
        Convert HEX Color to RGBA Color, opacity value support.
        Written By: Qassim Hassan
        Website: wp-time.com
	 */
    $color = trim($color, "#");
    $hex = hexdec($color);
    if( strlen($color) == 6 ){
        $r = hexdec( substr($color, 0, 2) );
        $g = hexdec( substr($color, 2, 2) );
        $b = hexdec( substr($color, 4, 2) );
        $a = $opacity;
    }
    else{
        return "Error color code! Please enter correct color code, for example #ffffff";
        return false;
    }
    return $r.", ".$g.", ".$b.", ".$a;
}

/**
 * Allow special tags in post bodies that would get stripped otherwise for most users.
 * Modifies $allowedposttags defined in wp-includes/kses.php
 *
 * http://wordpress.org/support/topic/div-ids-being-stripped-out
 * http://wpquicktips.wordpress.com/2010/03/12/how-to-change-the-allowed-html-tags-for-wordpress/
 **/
$allowedposttags['input'] = array(
	'type' => array(),
	'value' => array(),
	'id' => array(),
	'name' => array(),
	'class' => array()
);
$allowedposttags['select'] = array(
	'id' => array(),
	'name' => array()
);
$allowedposttags['option'] = array(
	'id' => array(),
	'name' => array(),
	'value' => array()
);
$allowedposttags['iframe'] = array(
	'type' => array(),
	'value' => array(),
	'id' => array(),
	'name' => array(),
	'class' => array(),
	'src' => array(),
	'height' => array(),
	'width' => array(),
	'allowfullscreen' => array(),
	'frameborder' => array()
);
$allowedposttags['object'] = array(
	'height' => array(),
	'width' => array()
);

$allowedposttags['param'] = array(
	'name' => array(),
	'value' => array()
);

$allowedposttags['embed'] = array(
	'src' => array(),
	'type' => array(),
	'allowfullscreen' => array(),
	'allowscriptaccess' => array(),
	'height' => array(),
	'width' => array()
);
// Most of these attributes aren't actually valid for some of
// the tags they're assigned to, but whatever:
$allowedposttags['div'] =
$allowedposttags['a'] =
$allowedposttags['button'] = array(
	'id' => array(),
	'class' => array(),
	'style' => array(),
	'width' => array(),
	'height' => array(),
	'align' => array(),
	'aria-hidden' => array(),
	'aria-labelledby' => array(),
	'autofocus' => array(),
	'dir' => array(),
	'disabled' => array(),
	'form' => array(),
	'formaction' => array(),
	'formenctype' => array(),
	'formmethod' => array(),
	'formonvalidate' => array(),
	'formtarget' => array(),
	'hidden' => array(),
	'href' => array(),
	'name' => array(),
	'rel' => array(),
	'rev' => array(),
	'role' => array(),
	'target' => array(),
	'type' => array(),
	'title' => array(),
	'value' => array(),

	// Bootstrap JS stuff:
	'data-dismiss' => array(),
	'data-toggle' => array(),
	'data-target' => array(),
	'data-backdrop' => array(),
	'data-spy' => array(),
	'data-offset' => array(),
	'data-animation' => array(),
	'data-html' => array(),
	'data-placement' => array(),
	'data-selector' => array(),
	'data-title' => array(),
	'data-trigger' => array(),
	'data-delay' => array(),
	'data-content' => array(),
	'data-offset' => array(),
	'data-offset-top' => array(),
	'data-loading-text' => array(),
	'data-complete-text' => array(),
	'autocomplete' => array(),
	'data-parent' => array(),
);

/**
 * Retrieve a YouTube ID from its URL
 **/
function get_youtube_id($url){
	$shortlink_domain = '/^http\:\/\/(?:www.)?youtu.be/';
	if (preg_match($shortlink_domain, $url)) {
		$parts = parse_url($url);
		return substr($parts['path'], 1, strlen($parts['path']) - 1);
	}
	else {
		$parts = parse_url($url);
		parse_str($parts['query'], $parts);
		return $parts['v'];
	}
}

/**
 * Allow shortcodes in widgets
 **/
add_filter('widget_text', 'do_shortcode');

/**
 * Hide unused admin tools (Links, Comments, etc)
 **/
function hide_admin_links() {
	remove_menu_page('link-manager.php');
	remove_menu_page('edit-comments.php');
}
add_action( 'admin_menu', 'hide_admin_links' );

/**
 * Adds a subheader to a page (if one is set for the page.)
 **/
function get_page_subheader( $post ) {
	ob_start();

	$subheader = get_post_meta( $post->ID, 'page_subheader', true );

	if ( $subheader ) {
		$subheader_post = get_post( $subheader );
		$sub_img = get_post_meta( $subheader, 'subheader_sub_image', true );
		$sub_img_atts = array(
			'class'	=> 'subheader-subimg',
			'alt'   => $post->post_title,
			'title' => $post->post_title,
		);
		$student_name = get_post_meta( $subheader, 'subheader_student_name', true );
		$student_img = get_post_meta( $subheader, 'subheader_student_image', true );
		$student_img_atts = array(
			'class'	=> 'subheader-studentimg',
			'alt'   => get_post_meta( $subheader, 'subheader_student_name', true ),
			'title' => get_post_meta( $subheader, 'subheader_student_name', true ),
		);
		$adjustedColWidth = 8;
		if(!$sub_img || !$student_img){
				$adjustedColWidth = 10;
			if(!$student_img && !$sub_img){
				$adjustedColWidth = 12;
			}
		}
	?>
		<div class="col-md-10 col-sm-10 col-sm-push-2 col-md-push-2">
			<div id="subheader" role="complementary">
				<div class="row">
					<?php if($sub_img){ ?>
						<div class="col-md-2 col-sm-2">
							<?php echo wp_get_attachment_image( $sub_img, 'subpage-subimg', 0, $sub_img_atts ); ?>
						</div>
					<?php } ?>
					<div class="col-md-<?= $adjustedColWidth ?> col-sm-<?= $adjustedColWidth ?>">
						<blockquote class="subheader-quote">
							<?php echo $subheader_post->post_content; ?>
							<p class="subheader-author text-right"><?php echo $student_name; ?></p>
						</blockquote>
					</div>
				</div>
				<?php if($student_img){
				echo wp_get_attachment_image( $student_img, 'subpage-studentimg', 0, $student_img_atts ); 
				}?>
			</div>
		</div>
	<?php
	}

	return ob_get_clean();
}

/**
 * Output Spotlights for front page.
 **/
function frontpage_spotlights() {
	$args = array(
		'post_type' 	=> 'spotlight',
		'post_status'   => 'publish',
		'meta_query'	=> array(
			array(
				'key'	=>	'spotlight_post_to_home',
				'value'	=>	'on',
			),
			array(
				'key'	=>	'spotlight_start',
				'value'	=>	date('Ymd'),
				'compare'	=>	'<=',
			),
			array(
				'key'	=>	'spotlight_end',
				'value'	=>	date('Ymd'),
				'compare'	=>	'>=',
			),
		),
	);
	$spotlights = get_posts($args);

	if(empty($spotlights)){
		$args = array(
			'numberofposts' => 2,
			'post_type' 	=> 'spotlight',
			'post_status'   => 'publish',
			);
		$spotlights = get_posts($args);
	}	
	
	$spotlights = array_splice($spotlights, 0, 2);
	ob_start(); ?>
		<section id="spotlights">
			<div class="spotlights_title_wrap">
				<h2 class="spotlights_title">Spotlights</h2>
				<a href="<?=get_permalink(get_page_by_title('Spotlight Archives', OBJECT, 'page')->ID)?>">
					Check out more stories
					<i class="fa fa-external-link"></i>
				</a>	
			</div>	
			<div class="spotlights_lg_table">
				<? foreach ( $spotlights as $spotlight ){ 
					$link = get_permalink($spotlight->ID);
					$ext_link = get_post_meta($spotlight->ID, 'spotlight_url_redirect', TRUE);
					if($ext_link){
						$link = $ext_link; 
					}
					$cat_term = get_term_by('slug','event-category','event_groups');
					$child_terms = get_term_children($cat_term->term_id, 'event_groups');
					$all_terms   = wp_get_post_terms($spotlight->ID, 'event_groups');
					foreach ( $all_terms as $term ) {
						if( in_array($term->term_id, $child_terms ) ) {
							$term_title = $term->name;
							break;
						}
						if(DEBUG){
							print_r($cat_term);
							print_r($child_terms);
							print_r($all_terms);					
						}					
					}?>
					<div class="spotlight_single_wrap">
						<a class="spotlight_single" href="<?=esc_attr($link)?>" class="ga-event" data-ga-action="Spotlight Link" data-ga-label="<?=esc_attr($spotlight->post_title)?>">
							<div class="spotlight_image_wrap">
								<? $thumb_id = get_post_thumbnail_id($spotlight->ID);
									$thumb_src = wp_get_attachment_image_src( $thumb_id, 'home-thumb' );
									$thumb_src = $thumb_src[0];
									if ($thumb_src) { ?>
										<img class="spotlight_image" src="<?=esc_attr($thumb_src)?>" alt="<?=esc_attr($spotlight->post_title)?>"/>
									<? } ?>
							</div>
							<div class="spotlight_content_wrap">
								<span class="spotlight_type">
									<?=$term_title?>
								</span>	
								<h3 class="spotlight_title">
									<?=$spotlight->post_title?>	
								</h3>
								<p class="spotlight_content">
									<?=get_the_excerpt($spotlight->ID)?>	
								</p>
							</div>
						</a>
					</div>
				<? } ?>
			</div>
		</section>
	<? return ob_get_clean();
}

/**
 * Output Opportunities for front page.
**/
function frontpage_opportunities() {
	$args = array(
		'numberposts' => -1,	
		'post_type' 	=> 'opportunity',
		'post_status'   => 'publish',
		'meta_key'		=> 'opportunity_end',
		'meta_query'	=> array(
			array(
				'key'	=>	'opportunity_start',
				'value'	=>	date('Ymd', mktime(23,59,59)), // this might work? set time as 23:59:59?
				'compare'	=>	'<=',
			),
			array(
				'key'	=>	'opportunity_end',
				'value'	=>	date('Ymd', mktime(0,0,0)),
				'compare'	=>	'>=',
			),
		),
	);
	$opportunities = get_posts($args);
		
	if(empty($opportunities)){
		$args = array(
			'numberposts' => -1,
			'post_type' 	=> 'opportunity',
			'post_status'   => 'publish',
			'meta_key'		=> 'opportunity_end',
		);
		$opportunities = get_posts($args);
	}
	
	usort($opportunities, function($a, $b){
		$a_dt = new DateTime(get_post_meta($a->ID, 'opportunity_end', TRUE));
		$b_dt = new DateTime(get_post_meta($b->ID, 'opportunity_end', TRUE));
		$a_dt = $a_dt->getTimestamp();
		$b_dt = $b_dt->getTimestamp();
		if ($a_dt == $b_dt){
			// If they have the same depth, compare titles
			return strcmp($a->post_title, $b->post_title);
		}
		// If depth_a is smaller than depth_b, return -1; otherwise return 1
		$res = ($a_dt < $b_dt) ? -1 : 1;
		return $res;
	});
	
	$opportunities = array_splice($opportunities, 0, 5);
	
	if(DEBUG){
		print_r($opportunities);
	}
	
	ob_start(); ?>
		<section id="opportunities">
			<div class="opportunities_title_wrap">
				<h2 class="opportunities_title">Opportunities</h2>
				<a href="<?=get_permalink(get_page_by_title('Opportunity Archives', OBJECT, 'page')->ID)?>">Even More Opportunities</a>	
			</div>		
			<div class="opportunities_lg_table">
			<? foreach ( $opportunities as $opportunity ){ 
				$link = get_permalink($opportunity->ID);
				$ext_link = get_post_meta($opportunity->ID, 'opportunity_url_redirect', TRUE);
				if($ext_link){
					$link = $ext_link; 
				} 
				$cat_term = get_term_by('slug','event-category','event_groups');
				$child_terms = get_term_children($cat_term->term_id, 'event_groups');
				$all_terms   = wp_get_post_terms($opportunity->ID, 'event_groups');
				if(DEBUG){
					print_r($cat_term);
					print_r($child_terms);
					print_r($all_terms);					
				}
				foreach ( $all_terms as $term ) {
					if( in_array($term->term_id, $child_terms ) ) {
						$term_title = $term->name;
						break;
					}
				}?>
				<div class="opportunity_single_wrap">
					<a class="opportunity_single" href="<?=esc_attr($link)?>" class="ga-event" data-ga-action="Opportunity Link" data-ga-label="<?=esc_attr($opportunity->post_title)?>">
						<div class="opportunity_content_wrap">
							<h3 class="opportunity_title">
								<?=$opportunity->post_title?>	
							</h3>
							<div class="opportunity_type">
								<?=$term_title?>
							</div>					
						</div>
						<div class="opportunity_icon_wrap">
							<i class="fa fa-2x fa-chevron-right opportunity_icon"></i>
						</div>
					</a>
				</div>
			<? } ?>
			</div>
		</section>
	<? return ob_get_clean();
}


function frontpage_interests(){
	$itms = get_posts(array(
		"post_type" => "interest",
		"post_status" => "publish",
	));
	if(DEBUG){
		print_r($itms);
	}
	ob_start(); ?>
	<section id="interests">
		<div class="interests_title_wrap">
			<h2 class="interests_title"><span>What Are You Interested In?</span></h2>
		</div>	
		<?php
				
			// orce override?
			if(get_theme_option('home_page_theme') == 1){ ?>
				<style>
					.interests_title_wrap{
						background-color: #fff;
						color: #000;
					}
					.interests_bg_overlay {
						display: none;

						@media (min-width: 770px) {
							display: block;
							background-color: black;
							position: absolute;
							width: 100%;
							height: 100%;
							opacity: .9;
						}
					}
					.interests_false_wrap{
						@media (min-width: 770px) {
							background-image:url('https://testtbhccmsdev.smca.ucf.edu/wp-content/uploads/sites/2/2016/07/DISCOVERY_FINAL-1140x400.png');
							background-size:cover;
							height:100%;
							background-position:50% 50%;
						}
					}
				</style>
				<div class="interests_false_wrap">
					<div class="interests_bg_overlay"></div>
			<?}?>
		<div class="interests_lg_table">
		<? foreach ( $itms as $itm ){ 
			$link = get_permalink($itm->ID);
			$ext_link = get_post_meta($itm->ID, 'interest_url_redirect', TRUE);
			if($ext_link){
				$link = $ext_link; 
			}?>
			<style>
				@media(min-width: 770px){
					.interest_single#interest_<?=$itm->ID?>{
						background-image:url('<?=get_the_post_thumbnail_url($itm->ID)?>');				
					}
				}
			</style>
			<div class="interest_single_wrap" >
				<a class="interest_single" href="<?=$link?>" id="interest_<?=$itm->ID?>">
					<div class="interest_single_overlay"></div>
					<div class="interest_content_wrap">
						<h3 class="interest_title">
							<?=$itm->post_title?>	
						</h3>
						<p class="interest_content">
							<?=$itm->post_content?>	
						</p>
					</div>
					<div class="interest_icon_wrap">
						<i class="fa fa-2x fa-arrow-right interest_icon"></i>
					</div>
				</a>
			</div>					
		<? } ?>
		</div>
		<?php
				
			// orce override?
			if(get_theme_option('home_page_theme') == 1){ ?>
				</div>
			<?}

		?>
	</section>
	<? return ob_get_clean();
} 

function frontpage_events(){
	$events = get_events(0, 5);
	if(DEBUG){
		print_r($events);
	}
	ob_start();?>
	<section id="events">
	<div class="events_bg_overlay"></div>
		<div class="events_title_wrap">
			<h2 class="events_title">Upcoming Events</h2>
		</div>
		<div class="events_lg_table">
			<div class="events_table_group first">
				<? foreach($events as $element){?>					
					<?if (array_search($element, $events) === 0){?>
						<span class="events_type">Up Next</span>
					<?}else if(array_search($element, $events) === 1){?>
						<div class="events_table_group second">	
							<span class="events_type">Looking Ahead</span>
					<?}?>					
					<div class="event_single_wrap">
						<div class="event_single">
							<div class="event_datetime"><?=$element["starts"]?></div>
							<h3 class="event_title"><?=$element["title"]?></h3>
							<?if (array_search($element, $events) === 0){?>
								<div class="event_content"><?=$element["description"]?></div>	
							<?}?>
						</div>
					</div>	
					<?if (array_search($element, $events) === 0){?>
						</div>	
					<?}?>					
				<?}?>
			</div>
		</div>
	</section>
	<? return ob_get_clean();
}


/**
 * Pulls, parses and caches the weather.
 *
 * @return array
 * @author Chris Conover, Jo Greybill
 **/
function get_weather_data() {
	$cache_key = 'weather';
	// Check if cached weather data already exists
	if(($weather = get_transient($cache_key)) !== False) {
		return $weather;
	} else {
		$weather = array('condition' => 'Fair', 'temp' => '80&#186;', 'img' => '34');
		// Set a timeout
		$opts = array('http' => array(
								'method'  => 'GET',
								'timeout' => WEATHER_FETCH_TIMEOUT,
		));
		$context = stream_context_create($opts);
		// Grab the weather feed
		$raw_weather = file_get_contents(WEATHER_URL, false, $context);
		if ($raw_weather) {
			$json = json_decode($raw_weather);
			$weather['condition'] 	= $json->condition;
			$weather['temp']		= $json->temp;
			$weather['img']			= (string)$json->imgCode;
			// The temp, condition and image code should always be set,
			// but in case they're not, we catch them here:
			# Catch missing cid
			if (!isset($weather['img']) or !$weather['img']){
				$weather['img'] = '34';
			}
			# Catch missing condition
			if (!is_string($weather['condition']) or !$weather['condition']){
				$weather['condition'] = 'Fair';
			}
			# Catch missing temp
			if (!isset($weather['temp']) or !$weather['temp']){
				$weather['temp'] = '80&#186;';
			}
		}
		// Cache the new weather data
		set_transient($cache_key, $weather, WEATHER_CACHE_DURATION);
		return $weather;
	}
}

/**
 * Output weather data. Add an optional class for easy Bootstrap styling.
 **/
function output_weather_data($cssclass=null) {
	$cssclass	= is_string($cssclass) ? strip_tags($cssclass) : (string)strip_tags($cssclass);
	$weather 	= get_weather_data();
	$condition 	= $weather['condition'];
	$temp 		= $weather['temp'];
	$img 		= $weather['img'];
	return "<div id='weather_bug' class='".$cssclass."> screen-only' role='complementary'>".
		"<div id='wb_status_txt'><i class='wi wi-yahoo-".$img."'></i><span>".$temp."F, ".$condition."</span></div>".
	"</div>";
}

/**
 * Get and display announcements.
 * Note that, like the old Announcements advanced search, only one
 * search parameter (role, keyword, or time) can be set at a time.
 * Default (no args) returns all roles within the past week
 * (starting from Monday).
 **/
function get_announcements($role='all', $keyword=NULL, $time='thisweek') {
	// Get some dates for meta_query comparisons:
	$today = date('Y-m-d');
	$thismonday = date('Y-m-d', strtotime('monday this week'));
	$thissunday = date('Y-m-d', strtotime($thismonday.' + 6 days'));
	$nextmonday = date('Y-m-d', strtotime('monday next week'));
	$nextsunday = date('Y-m-d', strtotime($nextmonday.' + 6 days'));
	$firstday_thismonth = date('Y-m-d', strtotime('first day of this month'));
	$lastday_thismonth = date('Y-m-d', strtotime('last day of this month'));
	$firstday_nextmonth = date('Y-m-d', strtotime('first day of next month'));
	$lastday_nextmonth = date('Y-m-d', strtotime('last day of next month'));
	// Set up query args based on GET params:
	$args = array(
		'numberposts' => -1,
		'post_type' => 'announcement',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_key' => 'announcement_start_date',
	);
	// Announcement time queries should allow posts to fall within the week, even if
	// their start and end dates do not fall immediately within the given time
	// (allow for ongoing events that span during the given time, and then some).
	// Announcements should, however, be excluded if their end date has already passed.
	if ($role !== 'all') {
		$role_args = array(
			'tax_query' => array(
				array(
					'taxonomy' => 'audienceroles',
					'field' => 'slug',
					'terms' => $role,
				)
			),
			'meta_query' => array(
				array(
					'key' => 'announcement_start_date',
					'value' => $thissunday,
					'compare' => '<='
				),
				array(
					'key' => 'announcement_end_date',
					'value' => $today,
					'compare' => '>='
				),
			),
		);
		$args = array_merge($args, $role_args);
	}
	elseif ($keyword !== NULL) {
		$keyword_args = array(
			's' => $keyword,
			'meta_query' => array(
				array(
					'key' => 'announcement_start_date',
					'value' => $thissunday,
					'compare' => '<='
				),
				array(
					'key' => 'announcement_end_date',
					'value' => $today,
					'compare' => '>='
				),
			),
		);
		$args = array_merge($args, $keyword_args);
	}
	elseif ($time !== 'thisweek') {
		switch ($time) {
			case 'nextweek':
				$time_args = array(
					'meta_query' => array(
						array(
							'key' => 'announcement_start_date',
							'value' => $nextsunday,
							'compare' => '<='
						),
						array(
							'key' => 'announcement_end_date',
							'value' => $nextmonday,
							'compare' => '>='
						),
						array(
							'key' => 'announcement_end_date',
							'value' => $today,
							'compare' => '>='
						),
					),
				);
				$args = array_merge($args, $time_args);
				break;
			case 'thismonth':
				$time_args = array(
					'meta_query' => array(
						array(
							'key' => 'announcement_start_date',
							'value' => $lastday_thismonth,
							'compare' => '<='
						),
						array(
							'key' => 'announcement_end_date',
							'value' => $today,
							'compare' => '>='
						),
					),
				);
				$args = array_merge($args, $time_args);
				break;
			case 'nextmonth':
				$time_args = array(
					'meta_query' => array(
						array(
							'key' => 'announcement_start_date',
							'value' => $lastday_nextmonth,
							'compare' => '<='
						),
						array(
							'key' => 'announcement_end_date',
							'value' => $firstday_nextmonth,
							'compare' => '>='
						),
						array(
							'key' => 'announcement_end_date',
							'value' => $today,
							'compare' => '>='
						),
					),
				);
				$args = array_merge($args, $time_args);
				break;
			case 'thissemester':
				// Compare the current month to predefined month values
				// to pull announcements from the current semester
				// Check for Spring Semester
				if (CURRENT_MONTH >= SPRING_MONTH_START && CURRENT_MONTH <= SPRING_MONTH_END) {
					$time_args = array(
						'meta_query' => array(
							array(
								'key' => 'announcement_start_date',
								'value' => date('Y-m-t', strtotime('May')),
								'compare' => '<='
							),
							array(
								'key' => 'announcement_end_date',
								'value' => $today,
								'compare' => '>='
							),
						),
					);
					$args = array_merge($args, $time_args);
				}
				// Check for Summer Semester
				elseif (CURRENT_MONTH >= SUMMER_MONTH_START && CURRENT_MONTH <= SUMMER_MONTH_END) {
					$time_args = array(
						'meta_query' => array(
							array(
								'key' => 'announcement_start_date',
								'value' => date('Y-m-t', strtotime('July')),
								'compare' => '<='
							),
							array(
								'key' => 'announcement_end_date',
								'value' => $today,
								'compare' => '>='
							),
						),
					);
					$args = array_merge($args, $time_args);
				}
				// else, it's the Fall Semester
				else {
					$time_args = array(
						'meta_query' => array(
							array(
								'key' => 'announcement_start_date',
								'value' => date('Y-m-t', strtotime('December')),
								'compare' => '<='
							),
							array(
								'key' => 'announcement_end_date',
								'value' => $today,
								'compare' => '>='
							),
						),
					);
					$args = array_merge($args, $time_args);
				}
				break;
			case 'all':
				$time_args = array(
					'meta_query' => array(
						array(
							'key' => 'announcement_end_date',
							'value' => $today,
							'compare' => '>='
						),
					),
				);
				$args = array_merge($args, $time_args);
				break;
			default:
				$time_args = array(
					'meta_query' => array(
						array(
							'key' => 'announcement_start_date',
							'value' => $thissunday,
							'compare' => '<='
						),
						array(
							'key' => 'announcement_end_date',
							'value' => $today,
							'compare' => '>='
						),
					),
				);
				$args = array_merge($args, $time_args);
				break;
		}

	}
	else { // default retrieval args
		$fallback_args = array(
			'meta_query' => array(
				array(
					'key' => 'announcement_start_date',
					'value' => $thissunday,
					'compare' => '<='
				),
				array(
					'key' => 'announcement_end_date',
					'value' => $today,
					'compare' => '>='
				),
			),
		);
		$args = array_merge($args, $fallback_args);
	}

	// Fetch all announcements based on args given above:
	$announcements = get_posts($args);

	if (!($announcements)) {
		return NULL;
	}
	else {
		// Add relevant metadata to each post object so they
		// can be more easily accessed:
		foreach ($announcements as $announcement) {
			$announcement->announcementStartDate 	 = get_post_meta($announcement->ID, 'announcement_start_date', TRUE);
			$announcement->announcementEndDate		 = get_post_meta($announcement->ID, 'announcement_end_date', TRUE);
			$announcement->announcementURL			 = get_post_meta($announcement->ID, 'announcement_url', TRUE);
			$announcement->announcementContactPerson = get_post_meta($announcement->ID, 'announcement_contact', TRUE);
			$announcement->announcementPhone		 = get_post_meta($announcement->ID, 'announcement_phone', TRUE);
			$announcement->announcementEmail		 = get_post_meta($announcement->ID, 'announcement_email', TRUE);
			$announcement->announcementPostedBy		 = get_post_meta($announcement->ID, 'announcement_posted_by', TRUE);
			$announcement->announcementRoles		 = wp_get_post_terms($announcement->ID, 'audienceroles', array("fields" => "names"));
			$announcement->announcementKeywords		 = wp_get_post_terms($announcement->ID, 'keywords', array("fields" => "names"));
			$announcement->announcementIsNew		 = ( date('Ymd') - date('Ymd', strtotime($announcement->post_date) ) <= 2 ) ? true : false;

			// Fallback for bad date ranges--force the start date to equal
			// the end date if the start date is later than the end date
			if ( date('Ymd', strtotime($announcement->announcementStartDate)) > date('Ymd', strtotime($announcement->announcementEndDate)) ) {
				$announcement->announcementStartDate = $announcement->announcementEndDate;
			}
		}

		return $announcements;
	}
}

/**
 * Prints a set of announcements, given an announcements array
 * returned from get_announcements().
 **/
function print_announcements($announcements, $liststyle='thumbtacks', $spantype='col-md-4 col-sm-4', $perrow=3) {
	switch ($liststyle) {
		case 'list':
			print '<ul class="announcement_list list-unstyled">';
			// Simple list of announcements; no descriptions.
			// $spantype and $perrow are not used here.
			foreach ($announcements as $announcement) {
				ob_start(); ?>
				<li><h3><a href="<?=get_permalink($announcement->ID)?>"><?=$announcement->post_title?></a></h3></li>
			<?php
				print ob_get_clean();
			}
			print '</ul>';
			break;

		case 'thumbtacks':
			// Grid of thumbtack-styled announcements
			print '<div class="row">';
			$count = 0;
			foreach ($announcements as $announcement) {
				if ($count % $perrow == 0 && $count !== 0) {
					print '</div><div class="row">';
				}
				ob_start();
				?>
				<div class="<?=$spantype?>" id="announcement_<?=$announcement->ID?>">
					<div class="announcement_wrap">
						<div class="thumbtack"></div>
						<?php if ($announcement->announcementIsNew == true) { ?><div class="new">New Announcement</div><?php } ?>
						<h3><a href="<?=get_permalink($announcement->ID)?>"><?=$announcement->post_title?></a></h3>
						<p class="date">
							<?php if ($announcement->announcementStartDate == $announcement->announcementEndDate) {
								print date('M d', strtotime($announcement->announcementEndDate));
							} else {
								print date('M d', strtotime($announcement->announcementStartDate)) .' - '. date('M d', strtotime($announcement->announcementEndDate));
							}
							?>
						</p>
						<p><?=truncateHtml(strip_tags($announcement->post_content, 200))?></p>
						<p class="audience"><strong>Audience:</strong>
						<?php
							if ($announcement->announcementRoles) {
								$rolelist = '';
								foreach ($announcement->announcementRoles as $role) {
									switch ($role) {
										case 'Alumni':
											$link = '?role=alumni';
											break;
										case 'Faculty':
											$link = '?role=faculty';
											break;
										case 'Prospective Students':
											$link = '?role=prospective-students';
											break;
										case 'Public':
											$link = '?role=public';
											break;
										case 'Staff':
											$link = '?role=staff';
											break;
										case 'Students':
											$link = '?role=students';
											break;
										default:
											$link = '';
											break;
									}
									$rolelist .= '<a class="print-noexpand" href="'.get_permalink().$link.'">'.$role.'</a>, ';
								}
								print substr($rolelist, 0, -2);
							}
							else { print 'n/a'; }
						?>
						</p>
						<p class="keywords"><strong>Keywords:</strong>
						<?php
							if ($announcement->announcementKeywords) {
								$keywordlist = '';
								foreach ($announcement->announcementKeywords as $keyword) {
									$keywordlist .= '<a class="print-noexpand" href="'.get_permalink().'?keyword='.$keyword.'">'.$keyword.'</a>, ';
								}
								print substr($keywordlist, 0, -2);
							}
							else { print 'n/a'; }
						?>
						</p>
					</div>
				</div>
			<?php
				print ob_get_clean();
				$count++;
			} // endforeach
			print '</div>';
			break;
		default:
			break;
	}
}

/**
 * Takes an announcements array from get_announcements() and outputs an RSS feed.
 **/
function announcements_to_rss($announcements) {
	header('Content-Type: application/rss+xml; charset=ISO-8859-1');
	print '<?xml version="1.0" encoding="ISO-8859-1"?>';
	print '<rss version="2.0" xmlns:announcement="'.get_site_url().'/announcements/">';
	print '<channel>';
	print '<title>University of Central Florida Announcements</title>';
	print '<link>http://www.ucf.edu/</link>';
	print '<language>en-us</language>';
	print '<copyright>ucf.edu</copyright>';
	print '<ttl>1</ttl>'; // Time to live (in minutes); force a cache refresh after this time
	print '<description>Feed for UCF Announcements.</description>';

	function print_item($announcement) {
		$output = '';
		$output .= '<item>';
			// Generic RSS story elements
			$output .= '<title>'.htmlentities($announcement->post_title, ENT_COMPAT, 'UTF-8', false).'</title>';
			$output .= '<description><![CDATA['.htmlentities(strip_tags($announcement->post_content)).']]></description>';
			$output .= '<link>'.get_permalink($announcement->ID).'</link>';
			$output .= '<guid>'.get_permalink($announcement->ID).'</guid>';
			$output .= '<pubDate>'.date('r', strtotime($announcement->post_date)).'</pubDate>';

			// Announcement-specific stuff
			$output .= '<announcement:id>'.$announcement->ID.'</announcement:id>';
			$output .= '<announcement:postStatus>'.$announcement->post_status.'</announcement:postStatus>';
			$output .= '<announcement:postModified>'.$announcement->post_modified.'</announcement:postModified>';
			$output .= '<announcement:published>'.$announcement->post_date.'</announcement:published>'; // same as <pubDate>
			$output .= '<announcement:permalink>'.get_permalink($announcement->ID).'</announcement:permalink>'; // same as <guid>
			$output .= '<announcement:postName>'.$announcement->post_name.'</announcement:postName>';
			$output .= '<announcement:startDate>'.$announcement->announcementStartDate.'</announcement:startDate>';
			$output .= '<announcement:endDate>'.$announcement->announcementEndDate.'</announcement:endDate>';
			$output .= '<announcement:url>'.htmlentities($announcement->announcementURL).'</announcement:url>';
			$output .= '<announcement:contactPerson>'.htmlentities($announcement->announcementContactPerson).'</announcement:contactPerson>';
			$output .= '<announcement:phone>'.$announcement->announcementPhone.'</announcement:phone>';
			$output .= '<announcement:email>'.htmlentities($announcement->announcementEmail).'</announcement:email>'; // need to account for special chars
			$output .= '<announcement:postedBy>'.htmlentities($announcement->announcementPostedBy).'</announcement:postedBy>';
			$output .= '<announcement:roles>';
				if (!empty($announcement->announcementRoles)) {
					foreach ($announcement->announcementRoles as $role) {
						$roles .= $role.', ';
					}
					$roles = substr($roles, 0, -2);
					$output .= $roles;
				}
			$output .= '</announcement:roles>';
			$output .= '<announcement:keywords>';
				if (!empty($announcement->announcementKeywords)) {
					foreach ($announcement->announcementKeywords as $keyword) {
						$keywords .= htmlentities($keyword, ENT_COMPAT, 'UTF-8', false) .', ';
					}
					$keywords = substr($keywords, 0, -2);
					$output .= $keywords;
				}
			$output .= '</announcement:keywords>';
			$output .= '<announcement:isNew>';
				$announcement->announcementIsNew == true ? $output .= 'true' : $output .= 'false';
			$output .= '</announcement:isNew>';

		$output .= '</item>';

		$roles = '';
		$keywords = '';

		print $output;
	}

	if ($announcements !== NULL) {
		// $announcements will always be an array of objects
		foreach ($announcements as $announcement) {
			print_item($announcement);
		}
	}
	print '</channel></rss>';
}

/*
 * Returns a theme option value or NULL if it doesn't exist
 */
function get_theme_option($key) {
	global $theme_options;
	return isset($theme_options[$key]) ? $theme_options[$key] : NULL;
}

/*
 * Wrap a statement in a ESI include tag with a specified duration if the
 * enable_esi theme option is enabled.
 */
function esi_include($statementname, $argset=null) {
	if (!$statementname) { return null; }

	// Get the statement key
	$statementkey = null;
	foreach (Config::$esi_whitelist as $key=>$function) {
		if ($function['name'] == $statementname) { $statementkey = $key;}
	}
	if (!$statementkey) { return null; }

	// Never include ESI over HTTPS
	$enable_esi = get_theme_option('enable_esi');
	if(!is_null($enable_esi) && $enable_esi === '1' && is_ssl() == false) {
		$argset = ($argset !== null) ? $argset = '&args='.urlencode(base64_encode($argset)) : '';
		?>
		<esi:include src="<?php echo ESI_INCLUDE_URL?>?statement=<?=$statementkey?><?=$argset?>" />
		<?php
	} elseif (array_key_exists($statementkey, Config::$esi_whitelist)) {
		$statementname = Config::$esi_whitelist[$statementkey]['name'];
		$statementargs = Config::$esi_whitelist[$statementkey]['safe_args'];
		// If no safe arguments are defined in the whitelist for this statement,
		// run call_user_func(); otherwise check arguments and run call_user_func_array()
		if (!is_array($statementargs) || $argset == null) {
			return call_user_func($statementname);
		}
		else {
			// Convert argset arrays to strings for easy comparison with our whitelist
			$argset = is_array($argset) ? serialize($argset) : $argset;
			if ($argset !== null && in_array($argset, $statementargs)) {
				$argset = (unserialize($argset) !== false) ? unserialize($argset) : array($argset);
				return call_user_func_array($statementname, $argset);
			}
		}
	}
	else {
		return NULL;
	}
}

/**
 * Pull recent Gravity Forms entries from a given form (intended for Feedback form.)
 * If no formid argument is provided, the function will pick the form
 * with ID of 1 by default.
 * Duration is specified in number of days.
 **/
function get_feedback_entries($formid=1, $duration=7, $to=array('webcom@ucf.edu')) {
	// Check that GF is actually installed
	if (is_plugin_inactive('gravityforms/gravityforms.php')) {
		die('Error: Gravity Forms is not activated. Please install/activate Gravity Forms and try again.');
	}
	// Make sure a valid email address to send to is set
	if (empty($to)) {
		die('Error: No email address specified to mail to.');
	}
	if (!is_array($to)) {
		die('Error: $to expects an array value.');
	}
	// Define how far back to search for old entries
	$dur_end_date 	= date('Y-m-d');
	$dur_start_date = date('Y-m-d', strtotime($dur_end_date.' -'.$duration.' days'));
	// WPDB stuff
	global $wpdb;
	global $blog_id;
	$blog_id == 1 ? $gf_table = 'wp_rg_lead' : $gf_table = 'wp_'.$blog_id.'_rg_lead'; # Y U NO USE CONSISTENT NAMING SCHEMA??
	define( 'DIEONDBERROR', true );
	$wpdb->show_errors();
	// Get all entry IDs
	$entry_ids = $wpdb->get_results(
			"
			SELECT          id
			FROM            ".$gf_table."
			WHERE           form_id = ".$formid."
			AND                     date_created >= '".$dur_start_date." 00:00:00'
			AND                     date_created <= '".$dur_end_date." 23:59:59'
			ORDER BY        date_created ASC
			"
	);
	// Begin $output
	$output .= '<h3>Feedback Submissions for '.date('M. j, Y', strtotime($dur_start_date)).' to '.date('M. j, Y', strtotime($dur_end_date)).'</h3><br />';
	if (count($entry_ids) == 0) {
		$output .= 'No submissions found for this time period.';
	}
	else {
		// Get field data for the entry IDs we got
		foreach ($entry_ids as $obj) {
			$entry = RGFormsModel::get_lead($obj->id);
			$output .= '<ul>';
			$entry_output 	= array();
			$about_array 	= array();
			$routes_array 	= array();
			// Only setup email for active entries (not trash/spam)
			if ($entry['status'] == 'active') {
				foreach ($entry as $field=>$val) {
					// Our form fields names are stored as numbers. The naming schema is as follows:
					// 1 			- Name
					// 2 			- E-mail
					// 3.1 to 3.7 	- 'Tell Us About Yourself' values
					// 4.1 to 4.7	- 'Routes to' values
					// 5			- Comment
					// Entry ID
					$entry_output['id'] = $obj->id;
					// Date
					if ($field == 'date_created') {
						// Trim off seconds from date_created
						$val = date('M. j, Y', strtotime($val));
						$entry_output['date'] .= $val;
					}
					// Name
					if ($field == 1) {
						if ($val) {
							$entry_output['name'] .= $val;
						}
					}
					// E-mail
					if ($field == 2) {
						if ($val) {
							$entry_output['email'] .= $val;
						}
					}
					// Tell Us About Yourself
					if ($field >=3 && $field < 4) {
						if ($val) {
							$about_array[] .= $val;
						}
					}
					// Route To
					if ($field >= 4 && $field < 5) {
						if ($val) {
							$routes_array[] .= $val;
						}
					}
					// Comments
					if ($field == 5) {
						if ($val) {
							$entry_output['comment'] .= $val;
						}
					}
				}
				$output .= '<li><strong>Entry: </strong>#'.$entry_output['id'].'</li>';
				$output .= '<li><strong>From: </strong>'.$entry_output['name'].' < '.$entry_output['email'].' ></li>';
				$output .= '<li><strong>Date Submitted: </strong>'.$entry_output['date'].'</li>';
				$output .= '<li><strong>Tell Us About Yourself: </strong><br/><ul>';
				foreach ($about_array as $about) {
					$output .= '<li>'.$about.'</li>';
				}
				$output .= '</ul></li>';
				$output .= '<li><strong>Route To: </strong><br/><ul>';
				foreach ($routes_array as $routes) {
					$output .= '<li>'.$routes.'</li>';
				}
				$output .= '</ul></li>';
				$output .= '<li><strong>Comments: </strong><br/>'.$entry_output['comment'].'</li>';
				$output .= '</ul><hr />';
			}
		}
	}
	// E-mail setup
	$subject = 'UCF Comments and Feedback for '.date('M. j, Y', strtotime($dur_start_date)).' to '.date('M. j, Y', strtotime($dur_end_date));
	$message = $output;
	// Change e-mail content type to HTML
	add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
	// Send e-mail; return success or error
	$results = wp_mail( $to, $subject, $message );
	if ($results == true) {
		return 'Mail successfully sent at '.date('r');
	}
	else {
		return 'wp_mail returned false; mail did not send.';
	}
}

/**
 * Query the search service with specified params
 * @return array
 * @author Chris Conover
 **/
function query_search_service($params) {
	$results = array();
	try {
		$context = stream_context_create(array(
			'http' => array(
				'method'  => 'GET',
				'timeout' => SEARCH_SERVICE_HTTP_TIMEOUT
		)));
		$search_url = implode(array(SEARCH_SERVICE_URL, '?', http_build_query($params)));
		$response   = file_get_contents($search_url, false, $context);
		$json       = json_decode($response);
		if(isset($json->results)) $results = $json->results;
	} catch (Exception $e) {
		# pass
	}
	return $results;
}

/**
 * Query the undergraduate catalog feed
 * @return array
 * @author Jo Dickson
 **/
function query_undergraduate_catalog() {
	$results = array();
	try {
		$context = stream_context_create(array(
			'http' => array(
				'method'  => 'GET',
				'timeout' => UNDERGRADUATE_CATALOG_FEED_HTTP_TIMEOUT
		)));
		$feed_url = UNDERGRADUATE_CATALOG_FEED_URL;
		$response   = file_get_contents($feed_url, false, $context);
		$json       = json_decode($response);
		if(isset($json->programs)) $results = $json->programs;
	} catch (Exception $e) {
		#pass
	}
	return $results;
}

/**
 * Prevent Wordpress from trying to redirect to a "loose match" post when
 * an invalid URL is requested.  WordPress will redirect to 404.php instead.
 *
 * See http://wordpress.stackexchange.com/questions/3326/301-redirect-instead-of-404-when-url-is-a-prefix-of-a-post-or-page-name
 **/
function no_redirect_on_404($redirect_url) {
	if (is_404()) {
		return false;
	}
	return $redirect_url;
}
add_filter('redirect_canonical', 'no_redirect_on_404');

/**
 * Disable the Yoast SEO meta box on post types that we don't need it on
 * (non-public-facing posts, i.e. Centerpieces, Subheaders...)
 **/
function remove_yoast_meta_boxes() {
	$post_types = array(
		'centerpiece',
		'subheader',
		'azindexlink',
		'video',
		'document',
		'publication',
	);
	foreach ($post_types as $post_type) {
		remove_meta_box('wpseo_meta', $post_type, 'normal');
	}
}
add_action( 'add_meta_boxes', 'remove_yoast_meta_boxes' );

/**
 * Output a page-specific stylesheet, if one exists.
 * Intended for use in header.php (in edge-side include)
 **/
function page_specific_stylesheet($pageid) {
	if(($stylesheet_id = get_post_meta($pageid, 'page_stylesheet', True)) !== False
		&& ($stylesheet_url = wp_get_attachment_url($stylesheet_id)) !== False) {
		print '<link rel="stylesheet" href="'.$stylesheet_url.'" type="text/css" media="all" />';
	}
	else { return NULL; }
}

/**
 * Prints the Cloud.Typography font stylesheet <link> tag.
 **/
function webfont_stylesheet() {
	$css_key = get_theme_option( 'cloud_font_key' );
	if ( $css_key ) {
		echo '<link rel="stylesheet" href="'. $css_key .'" type="text/css" media="all" />';
	}
}

/**
 * Output the CSS key for Cloud.Typography web fonts if a CSS key is set in
 * Theme Options.
 * Is included conditionally per-page to prevent excessive hits on our Cloud.Typography
 * page view limit per month.
 **/
function page_specific_webfonts( $pageid ) {
	if ( get_post_meta( $pageid, 'page_use_webfonts', True ) == 'on' ) {
		webfont_stylesheet();
	}
}

/**
 * Kill attachment, author, and daily archive pages.
 *
 * http://betterwp.net/wordpress-tips/disable-some-wordpress-pages/
 **/
function kill_unused_templates() {
	global $wp_query, $post;

	if (is_author() || is_attachment() || is_day() || is_search()) {
		wp_redirect(home_url());
	}

	if (is_feed()) {
		$author     = get_query_var('author_name');
		$attachment = get_query_var('attachment');
		$attachment = (empty($attachment)) ? get_query_var('attachment_id') : $attachment;
		$day        = get_query_var('day');
		$search     = get_query_var('s');

		if (!empty($author) || !empty($attachment) || !empty($day) || !empty($search)) {
			wp_redirect(home_url());
			$wp_query->is_feed = false;
		}
	}
}
add_action('template_redirect', 'kill_unused_templates');

/**
* Add ID attribute to registered University Header script.
**/
function add_id_to_ucfhb($url) {
	if ( (false !== strpos($url, 'bar/js/university-header.js')) || (false !== strpos($url, 'bar/js/university-header-full.js')) ) {
	  remove_filter('clean_url', 'add_id_to_ucfhb', 10, 3);
	  return "$url' id='ucfhb-script";
	}
	return $url;
}
add_filter('clean_url', 'add_id_to_ucfhb', 10, 3);

/**
 * Returns an array of post groups, grouped by a specified taxonomy's terms.
 * Each key is a taxonomy term ID; each value is an array of post objects.
 *
 * Used by degree-list shortcode (Degree::objectsToHTML)
 **/
function group_posts_by_tax_terms($tax, $posts, $specific_terms=null) {
	$groups = array();

	// Get all taxonomy terms.
	$args = array('fields' => 'ids');
	$terms = get_terms($tax, $args);

	if ($terms) {
		// 'include' get_terms arg is not working. Filter here instead:
		foreach ($terms as $term) {
			$term = intval($term);
			if (is_array($specific_terms)) {
				if (in_array($term, $specific_terms)) {
					$groups[intval($term)] = array();
				}
			}
			else {
				$groups[intval($term)] = array();
			}
		}

		// Loop through each returned post and get its term(s).
		// Group the post in the $groups array.
		if ($posts) {
			foreach ($posts as $post) {
				$post_terms = wp_get_post_terms($post->ID, $tax, array('fields' => 'ids'));
				if ($post_terms) {
					foreach ($post_terms as $t) {
						$t = intval($t);
						if (isset($groups[$t])) {
							array_push($groups[$t], $post);
						}
						else {
							$groups[$t] = array($post);
						}
					}
				}
			}

			// Remove any terms with no posts.
			foreach ($groups as $term=>$posts) {
				if (empty($groups[$term])) { unset($groups[$term]); }
			}

			return $groups;
		}
		else { return null; }
	}
	else { return null; }
}

/**
 * Helper function that returns the first item in a numeric array.
 **/
function get_first_result( $array_result ) {
	if ( is_array( $array_result ) && count( $array_result ) > 0 ) {
		return $array_result[0];
	}
	return $array_result;
}

/**
 * Helper udiff function for returning the difference between two arrays of
 * post objects
 **/
function posts_array_diff( $post_1, $post_2 ) {
	return $post_1->ID - $post_2->ID;
}

/**
 * Return's a term's custom meta value by key name.
 * Assumes that term data are saved as options using the naming schema
 * 'tax_<taxonomy slug>_<term id>'
 **/
function get_term_custom_meta( $term_id, $taxonomy, $key ) {
	if ( empty( $term_id ) || empty( $taxonomy ) || empty( $key ) ) {
		return false;
	}

	$term_meta = get_option( 'tax_' . $taxonomy . '_' . $term_id );
	if ( $term_meta && isset( $term_meta[$key] ) ) {
		$val = $term_meta[$key];
	}
	else {
		$val = false;
	}
	return stripslashes( $val );
}

/**
 * Saves a term's custom meta data.
 *
 * Assumes that term data are saved as options using the naming schema
 * 'tax_<taxonomy slug>_<term id>', and that term data is included in
 * Add New/Update <taxonomy> forms with inputs that have a name and ID
 * of 'term_meta[]'; e.g. '<input name="term_meta[my_custom_meta]" ..>'
 *
 * This function should be called on edited_<taxonomy> and create_<taxonomy>.
 * It saves all metadata following the term_meta[...] naming structure, so it
 * should only be hooked into edited_<taxonomy> and create_<taxonomy> once per
 * taxonomy.
 **/
function save_term_custom_meta( $term_id, $taxonomy ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$option_name = 'tax_' . $taxonomy . '_' . strval( $term_id );
		$term_meta = get_option( $option_name );
		$term_keys = array_keys( $_POST['term_meta'] );
		foreach ( $term_keys as $key ) {
			if ( isset( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = stripslashes( wp_filter_post_kses( addslashes( $_POST['term_meta'][$key] ) ) );
			}
		}
		// Save the option array.
		update_option( $option_name, $term_meta );
	}
}

/**
* Displays social buttons (Facebook, Twitter, G+) for a post.
* Accepts a post URL and title as arguments.
*
* @return string
* @author Jo Dickson
**/
function display_social( $url, $title, $subject_line='', $email_body='' ) {
	if ( !$subject_line ) {
		$subject_line = 'ucf.edu: ' . $title;
	}
	if ( !$email_body ) {
		$email_body = 'Check out this page on ucf.edu.';
	}

	ob_start();
?>
	<aside class="social clearfix">
		<a class="share-facebook" target="_blank" data-button-target="<?php echo $url; ?>" href="http://www.facebook.com/sharer.php?u=<?php echo $url; ?>" title="Like this on Facebook">
			Like "<?php echo $title; ?>" on Facebook
		</a>
		<a class="share-twitter" target="_blank" data-button-target="<?php echo $url; ?>" href="https://twitter.com/intent/tweet?text=<?php echo $subject_line; ?>&url=<?php echo $url; ?>" title="Tweet this">
			Tweet "<?php echo $title; ?>" on Twitter
		</a>
		<a class="share-googleplus" target="_blank" data-button-target="<?php echo $url; ?>" href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Share this on Google+">
			Share "<?php echo $title; ?>" on Google+
		</a>
		<a class="share-linkedin" target="_blank" data-button-target="<?php echo $url; ?>" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $subject_line; ?>" title="Share this on Linkedin">
			Share "<?php echo $title; ?>" on Linkedin
		</a>
		<a class="share-email" target="_blank" data-button-target="<?php echo $url; ?>" href="mailto:?subject=<?php echo $subject_line; ?>&amp;body=<?php echo $email_body; ?>%0A%0A<?php echo $url; ?>" title="Share this in an email">
			Share "<?php echo $title; ?>" in an email
		</a>
	</aside>
<?php
	return ob_get_clean();
}

/**
 * Creates an Announcement when a new Post An Announcement form entry is
 * submitted.
 *
 * This function assumes a form with ID of 4 already exists and has all form
 * fields/values configured already.
 **/
function announcement_post_save( $post_data, $form, $entry ) {
	if ( intval( $form['id'] ) == 4 ) {
		$post_data['post_type'] = 'announcement';
	}

	return $post_data;
}
add_action( 'gform_post_data', 'announcement_post_save', 10, 3 );


/**
 * Adds keywords and audience roles to a newly-created Announcement from the
 * Post an Announcement forn.
 *
 * This function assumes a form with ID of 4 already exists and has all form
 * fields/values configured already, and that desired Audience Role term values
 * have already been created.
 **/
function announcement_post_tax_save( $entry, $form ) {
	if( isset( $entry['post_id'] ) ) {
		$post = get_post( $entry['post_id'] );

		if ( $post ) {
			$keywords = $audience_roles = $entry_keywords = $entry_audience_roles = array();

			foreach ( $entry as $key => $val ) {
				if ( substr( $key, 0, 1 ) == '8.' && !empty( $val ) ) {
					$entry_audience_roles[] = trim( $val );
				}
				else if ( $key == '9' ) {
					$entry_keywords = explode( ',', $val );
				}
			}

			if ( !empty( $entry_audience_roles ) ) {
				foreach ( $entry_audience_roles as $role ) {
					// Check for an existing term.  If it doesn't already
					// exist, don't create a new one
					$role_term = get_term_by( 'name', $role, 'audienceroles', 'ARRAY_A' );
					if ( is_array( $role_term ) ) { // make sure we get a successful return
						$audience_roles[] = intval( $role_term['term_id'] );
					}
				}
			}
			if ( !empty( $entry_keywords ) ) {
				foreach ( $entry_keywords as $keyword ) {
					// Check for an existing term
					$keyword_term = get_term_by( 'name', $keyword, 'keywords', 'ARRAY_A' );
					if ( !$keyword_term ) {
						$keyword_term = wp_insert_term( $keyword, 'keywords' );
					}
					if ( is_array( $keyword_term ) ) { // make sure we get a successful return (not WP Error obj)
						$keywords[] = intval( $keyword_term['term_id'] );
					}
				}
			}

			if ( !empty( $audience_roles ) ) {
				wp_set_object_terms( $post->ID, $audience_roles, 'audienceroles' );
			}
			if ( !empty( $keywords ) ) {
				wp_set_object_terms( $post->ID, $keywords, 'keywords' );
			}
		}
	}
}
add_action( 'gform_after_submission_4', 'announcement_post_tax_save', 10, 2 );

/**
 * Allow json files to be uploaded to the media library.
 **/
function uploads_allow_json( $mimes ) {
	$mimes['json'] = 'application/json';
	return $mimes;
}
add_filter( 'upload_mimes', 'uploads_allow_json' );

/**
 * Conditional body class modifications.
 **/
function custom_body_classes( $classes ) {
	if ( !is_front_page() ) {
		$classes[] = 'subpage';
	}
	return $classes;
}
add_filter( 'body_class', 'custom_body_classes' );

/**
 * Enqueues page-specific javascript files.
 **/
function enqueue_page_js() {
	global $post;
	if ( $post && $post->post_type == 'page' && $js = get_post_meta( $post->ID, 'page_javascript', true ) ) {
		Config::add_script( wp_get_attachment_url( $js ) );
	}
}
add_action( 'wp_enqueue_scripts', 'enqueue_page_js' );

/**
 * Prints the Google Tag Manager snippet using the GTM ID in Theme Options.
 **/
function google_tag_manager() {
	ob_start();
	$gtm_id = get_theme_option( 'gtm_id' );
	if ( $gtm_id ) :
?>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $gtm_id; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $gtm_id; ?>');</script>
<!-- End Google Tag Manager -->
<?php
	endif;
	return ob_get_clean();
}

/**
 * Prints the Google Tag Manager data layer snippet.
 **/
function google_tag_manager_dl() {
	ob_start();
	$gtm_id = get_theme_option( 'gtm_id' );
	if ( $gtm_id ) :
?>
	<script>
	  dataLayer = [];
	</script>
<?php
	endif;
	return ob_get_clean();
}

/*
 *	Erik made ajax call for the nav panels 
 */
function get_nav_panel(){
	$args = array(
		'posts_per_page'	=>	1,
		'post_type'	=>	'nav_dropdown',
		'meta_query' => array(
			array(
				'key'   => 'nav_dropdown_menu_item',
				'value' => $_REQUEST['id'],
			)
		),
	);
	$items = get_posts($args);
	$htmlOut = "";
	if(is_array($items) && !empty($items)){
		$htmlOut .= apply_filters( 'siteorigin_panels_before_content', '<div class="content-holder">', $panels_data = false, $items[0]->ID );
		$htmlOut .= siteorigin_panels_render($post_id = $items[0]->ID);
		$htmlOut .= apply_filters( 'siteorigin_panels_after_content', '</div>', $panels_data = false, $items[0]->ID );
	}else{
		$htmlOut .= "Please check back later! This panel is under maintenance!";
	}
	$css = siteorigin_panels_generate_css($items[0]->ID);
	wp_send_json(array(
		'html'	=>	$htmlOut,
		/*'css'	=>	$css*/
	));
	die();
}

add_action( 'wp_ajax_get_nav_panel', 'get_nav_panel' );
add_action( 'wp_ajax_nopriv_get_nav_panel', 'get_nav_panel' );

// Breadcrumbs from https://www.thewebtaylor.com/articles/wordpress-creating-breadcrumbs-without-a-plugin, kudos and thnx to https://www.thewebtaylor.com/articles/author/stuart, modified to use spans instead of li
function custom_breadcrumbs() {
    // Settings
    $separator          = '&nbsp;&gt;&nbsp;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    $home_title         = 'Homepage';
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
    // Get the query & post information
    global $post,$wp_query;
    // Do not display on the homepage
    if ( !is_front_page() ) {
        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
        // Home page
        echo '<span class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></span>';
        echo '<span class="separator separator-home"> ' . $separator . ' </span>';
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
            echo '<span class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></span>';
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
            // If post is a custom post type
            $post_type = get_post_type();
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
                echo '<span class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></span>';
                echo '<span class="separator"> ' . $separator . ' </span>';
            }
            $custom_tax_name = get_queried_object()->name;
            echo '<span class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></span>';
        } else if ( is_single() ) {
            // If post is a custom post type
            $post_type = get_post_type();
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
                echo '<span class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></span>';
                echo '<span class="separator"> ' . $separator . ' </span>';
            }
            // Get post category info
            $category = get_the_category();
            if(!empty($category)) {
                // Get last category post is in
                $last_category = end(array_values($category));
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<span class="item-cat">'.$parents.'</span>';
                    $cat_display .= '<span class="separator"> ' . $separator . ' </span>';
                }
            }
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
            }
            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<span class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></span>';
				// Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                echo '<span class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></span>';
                echo '<span class="separator"> ' . $separator . ' </span>';
                echo '<span class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></span>';
            } else {
                echo '<span class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></span>';
            }
        } else if ( is_category() ) {
            // Category page
            echo '<span class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></span>';
        } else if ( is_page() ) {
            // Standard page
            if( $post->post_parent ){
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                // Get parents in the right order
                $anc = array_reverse($anc);
                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<span class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></span>';
                    $parents .= '<span class="separator separator-' . $ancestor . '"> ' . $separator . ' </span>';
                }
                // Display parent pages
                echo $parents;
                // Current page
                echo '<span class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></span>';
            } else {
                // Just display current page if not parents
                echo '<span class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></span>';
            }
        } else if ( is_tag() ) {
            // Tag page
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
            // Display the tag name
            echo '<span class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></span>';
        } elseif ( is_day() ) {
            // Day archive
            // Year link
            echo '<span class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></span>';
            echo '<span class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </span>';
            // Month link
            echo '<span class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></span>';
            echo '<span class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </span>';
            // Day display
            echo '<span class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></span>';
        } else if ( is_month() ) {
            // Month Archive
            // Year link
            echo '<span class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></span>';
            echo '<span class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </span>';
            // Month display
            echo '<span class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></span>';
        } else if ( is_year() ) {	
            // Display year archive
            echo '<span class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></span>';
        } else if ( is_author() ) {
            // Auhor archive
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
            // Display author name
            echo '<span class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></span>';
        } else if ( get_query_var('paged') ) {
            // Paginated archives
            echo '<span class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></span>';
        } else if ( is_search() ) {
            // Search results page
            echo '<span class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></span>';
        } elseif ( is_404() ) {
            // 404 page
            echo '<span>' . 'Error 404' . '</span>';
        }
        echo '</ul>';
    }
}

// hooks and code modified/inspired from https://www.sitepoint.com/customized-wordpress-administration-filters/ By Simon Codrington December 09, 2014
//defining the filter that will be used to select posts by 'post formats'
function add_org_groups_filter_to_post_administration(){
	
    //execute only on the 'post' content type
    global $post_type;
    if($post_type == 'person'){
		
        $post_formats_args = array(
			'show_option_all'   => 'All People',
			'orderby'           => 'NAME',
			'order'             => 'ASC',
			'name'              => 'org_groups_admin_filter',
			'taxonomy'          => 'org_groups',
			'hierarchical'		=> 1,
        );
		
        //if we have a post format already selected, ensure that its value is set to be selected
        if(isset($_GET['org_groups_admin_filter'])){
            $post_formats_args['selected'] = sanitize_text_field($_GET['org_groups_admin_filter']);
        }
		
        wp_dropdown_categories($post_formats_args);
		
    }
}
add_action('restrict_manage_posts','add_org_groups_filter_to_post_administration');
//restrict the posts by an additional author filter
function add_org_groups_filter_to_posts_query($query){

    global $post_type, $pagenow; 

    //if we are currently on the edit screen of the post type listings
    if($pagenow == 'edit.php' && $post_type == 'person'){

        if(isset($_GET['org_groups_admin_filter'])){

            //set the query variable for 'author' to the desired value
            $org_id = sanitize_text_field($_GET['org_groups_admin_filter']);
			
            //if the author is not 0 (meaning all)
            if($org_id != 0){
                $query->query_vars['tax_query'] = array(
					array(
						'taxonomy'  => 'org_groups',
						'field'     => 'ID',
						'terms'     => array($org_id)
					)
				);
            }

        }
    }
	print_r($query_vars);	
}

add_action('pre_get_posts','add_org_groups_filter_to_posts_query');

// inspiration from http://wordpress.stackexchange.com/a/72562
function get_terms_orderby_semester_year($orderby, $args){
	print_r($orderby);
	$orderby = "SUBSTR({$orderby}, (INSTR({$orderby}, ' ') + 1)) DESC, (CASE SUBSTR({$orderby}, 1, (INSTR({$orderby}, ' ') - 1)) WHEN 'Spring' THEN 1 WHEN 'Summer' THEN 2 ELSE 3 END)";
    return $orderby;
}

add_filter( 'storm_social_icons_use_latest', '__return_true' );

?>

