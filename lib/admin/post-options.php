<?php
/**
 * Add the custom meta boxes to all supported post types.
 *
 * @package   SocialWarfare\Functions
 * @copyright Copyright (c) 2017, Warfare Plugins, LLC
 * @license   GPL-3.0+
 * @since     1.0.0
 */

defined( 'WPINC' ) || die;

/**
 * Get the Twitter handle for tweet counts.
 *
 * @param  string $fallback A default value to fall back to.
 * @return string $twitter_handle The twitter handle.
 */
function _swp_get_twitter_handle( $fallback = false ) {
	// Fetch the Twitter handle for the Post Author if it exists.
	if ( isset( $_GET['post'] ) ) {
		$user_id = SWP_User_Profile::get_author( absint( $_GET['post'] ) );
	} else {
		$user_id = get_current_user_id();
	}

	$twitter_handle = get_the_author_meta( 'swp_twitter', $user_id );

	if ( ! $twitter_handle ) {
		$twitter_handle = $fallback;
	}

	return $twitter_handle;
}

/**
 * Output the meta boxes on the posts page if the plugin is registered
 *
 */


/**
 * Build the options fields.
 *
 * @since  3.3.0 | 20 AUG 2018 | Added call to SWP::Utility, else legacy code.
 *
 * @param  array $meta_boxes The existing meta boxes.
 * @return array $meta_boxes The modified meta boxes.
 */
function swp_register_meta_boxes( $meta_boxes ) {
	global $swp_user_options;

	$prefix = 'swp_';
	$options = $swp_user_options;

	$twitter_id = isset( $options['twitter_id'] ) ? $options['twitter_id'] : false;

	$twitter_handle = _swp_get_twitter_handle( $twitter_id );

    if ( class_exists( SWP::Utility ) ) :
        $pages = SWP_Utility::get_post_types();
    else:
        //* Legacy code.
        $types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );
        $types = array_merge( $types, array( 'post', 'page' ) );

    	$pages = apply_filters( 'swp_post_types', $types );
    endif;

	// Setup our meta box using an array.
	$meta_boxes[0] = array(
		'id'       => 'socialWarfare',
		'title'    => __( 'Social Warfare Custom Options','social-warfare' ),
		'pages'    => $pages,
		'context'  => 'normal',
		'priority' => apply_filters( 'swp_metabox_priority', 'high' ),
		'fields'   => array(
			// Setup the social media image.
			array(
				'name'  => '<span class="dashicons dashicons-share"></span> ' . __( 'Social Media Image','social-warfare' ),
				'desc'  => __( 'Add an image that is optimized for maximum exposure on Facebook, Google+ and LinkedIn. We recommend 1,200px by 628px.','social-warfare' ),
				'id'    => $prefix . 'og_image',
				'type'  => 'image_advanced',
				'clone' => false,
				'class' => $prefix . 'og_imageWrapper',
				'max_file_uploads' => 1,
			),
			// Setup the social media title.
			array(
				'name'  => '<span class="dashicons dashicons-share"></span> ' . __( 'Social Media Title','social-warfare' ),
				'desc'  => __( 'Add a title that will populate the open graph meta tag which will be used when users share your content onto Facebook, LinkedIn, and Google+. If nothing is provided here, we will use the post title as a backup.','social-warfare' ),
				'id'    => $prefix . 'og_title',
				'type'  => 'textarea',
				'class' => $prefix . 'og_title',
				'clone' => false,
			),
			// Setup the social media description.
			array(
				'name'  => '<span class="dashicons dashicons-share"></span> ' . __( 'Social Media Description','social-warfare' ),
				'desc'  => __( 'Add a description that will populate the open graph meta tag which will be used when users share your content onto Facebook, LinkedIn, and Google Plus.','social-warfare' ),
				'id'    => $prefix . 'og_description',
				'class' => $prefix . 'og_description',
				'type'  => 'textarea',
				'clone' => false,
			),
			// Divider.
			array(
				'name' => 'divider',
				'id'   => 'divider',
				'type' => 'divider',
			),
			// Setup the pinterest optimized image.
			array(
				'name'  => '<i class="sw sw-pinterest"></i> ' . __( 'Pinterest Image','social-warfare' ),
				'desc'  => __( 'Add an image that is optimized for maximum exposure on Pinterest. We recommend using an image that is formatted in a 2:3 aspect ratio like 735x1102.','social-warfare' ),
				'id'    => $prefix . 'pinterest_image',
				'class' => $prefix . 'pinterest_imageWrapper',
				'type'  => 'image_advanced',
				'clone' => false,
				'max_file_uploads' => 1,
			),
			// Setup the Custom Tweet box.
			array(
				'name'  => '<i class="sw swp_twitter_icon"></i> ' . __( 'Custom Tweet','social-warfare' ),
				'desc'  => ( $twitter_id ? sprintf( __( 'If this is left blank your post title will be used. Based on your username (@%1$s), <span class="tweetLinkSection">a link being added,</span> and the current content above, your tweet has %2$s characters remaining.','social-warfare' ),str_replace( '@','',$twitter_handle ),'<span class="counterNumber">140</span>' ) : sprintf( __( 'If this is left blank your post title will be used. <span ="tweetLinkSection">Based on a link being added, and</span> the current content above, your tweet has %s characters remaining.','social-warfare' ),'<span class="counterNumber">140</span>' )),
				'id'    => $prefix . 'custom_tweet',
				'class' => $prefix . 'custom_tweetWrapper',
				'type'  => 'textarea',
				'clone' => false,
			),
			// Setup the pinterest description.
			array(
				'name'  => '<i class="sw sw-pinterest"></i>' . __( 'Pinterest Description','social-warfare' ),
				'desc'  => __( 'Craft a customized description that will be used when this post is shared on Pinterest. Leave this blank to use the title of the post.','social-warfare' ),
				'id'    => $prefix . 'pinterest_description',
				'class' => $prefix . 'pinterest_descriptionWrapper',
				'type'  => 'textarea',
				'clone' => false,
			),
			array(
				'name'    => '<i class="sw sw-pinterest"></i> ' . __( 'Pin Image for Browser Extensions','social-warfare' ),
				'id'      => 'swp_advanced_pinterest_image',
				'class'   => 'swp_advanced_pinterest_imageWrapper',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Default','social-warfare' ),
					'on'      => __( 'On','social-warfare' ),
					'off'     => __( 'Off','social-warfare' ),
				),
				'clone' => false,
				'std'   => 'default',
			),
			array(
				'name'    => '<i class="sw sw-pinterest"></i> ' . __( 'Pin Browser Image Location','social-warfare' ),
				'id'      => 'swp_advanced_pinterest_image_location',
				'class'   => 'swp_advanced_pinterest_image_locationWrapper',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Default','social-warfare' ),
					'hidden'  => __( 'Hidden','social-warfare' ),
					'top'     => __( 'At the Top of the Post','social-warfare' ),
					'bottom'  => __( 'At the Bottom of the Post','social-warfare' ),
				),
				'clone' => false,
				'std'   => 'default',
			),
			// Set up the location on post options.
			array(
				'name'    => '<span class="dashicons dashicons-randomize"></span> ' . __( 'Horizontal Buttons Location','social-warfare' ),
				'id'      => $prefix . 'post_location',
				'class'   => $prefix . 'post_locationWrapper',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Default','social-warfare' ),
					'above'   => __( 'Above the Content','social-warfare' ),
					'below'   => __( 'Below the Content','social-warfare' ),
					'both'    => __( 'Both Above and Below the Content','social-warfare' ),
					'none'    => __( 'None/Manual Placement','social-warfare' ),
				),
				'clone' => false,
				'std'	=> 'default',
			),
			array(
				'name'    => '<span class="dashicons dashicons-randomize"></span> ' . __( 'Floating Buttons','social-warfare' ),
				'id'      => $prefix . 'float_location',
				'class'   => $prefix . 'float_locationWrapper',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Default','social-warfare' ),
					'on'      => __( 'On','social-warfare' ),
					'off'     => __( 'Off','social-warfare' ),
				),
				'clone' => false,
				'std'   => 'default',
			),
			array(
				'name'  => 'divider2',
				'id'    => 'divider2',
				'type'  => 'divider',
			),
			array(
				'name'  => $twitter_handle,
				'id'    => 'twitter_id',
				'class' => 'twitterIDWrapper',
				'type'  => 'hidden',
				'std'   => $twitter_handle,
			),
		),
	);

	return $meta_boxes;
}