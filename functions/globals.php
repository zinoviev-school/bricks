<?php

/**
 * Custom global functions.
 * These functions are meant to be accessed from multiple locations.
 * 
 */

// --- Social networks ---

// Used in Customizer, Social Icons element, Social Share buttons
// https://github.com/bradvin/social-share-urls


function sb_socialnetworks() {
    
    global $post;

    $post_url      = get_the_permalink($post->ID);
    $post_title    = rawurlencode(get_the_title($post->ID).' - '.get_bloginfo('name'));
    $post_thumb    = get_the_post_thumbnail_url($post->ID);
    
    $sb_socialnetworks = array(
        'facebook' => array(
            'social-name'   => 'Facebook',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-facebook-f',
            'has-profile'   => true,
            'has-share'     => true,
            'share-url'     => 'https://www.facebook.com/sharer/sharer.php?u='.$post_url,
        ),
        'twitter' => array(
            'social-name'   => 'Twitter',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-twitter',
            'has-profile'   => true,
            'has-share'     => true,
            'share-url'     => 'https://twitter.com/intent/tweet?url='.$post_url.'&text='.$post_title,
        ),
        'linkedin' => array(
            'social-name'   => 'LinkedIn',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-linkedin-in',
            'has-profile'   => true,
            'has-share'     => true,
            'share-url'     => 'https://www.linkedin.com/shareArticle?mini=true&url='.$post_url.'&title='.$post_title,
        ),
        'instagram' => array(
            'social-name'   => 'Instagram',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-instagram',
            'has-profile'   => true,
            'has-share'     => false,
        ),
        'pinterest' => array(
            'social-name'   => 'Pinterest',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-pinterest-p',
            'has-profile'   => false,
            'has-share'     => true,
            'share-url'     => 'https://pinterest.com/pin/create/button/?url='.$post_url.'&description='.$post_title.'&media='.$post_thumb,
        ),
        'youtube' => array(
            'social-name'   => 'YouTube',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-youtube',
            'has-profile'   => false,
            'has-share'     => false,
        ),
        'tripadvisor' => array(
            'social-name'   => 'TripAdvisor',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-tripadvisor',
            'has-profile'   => false,
            'has-share'     => false,
        ),
        'pocket' => array(
            'social-name'   => 'Pocket',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-get-pocket',
            'has-profile'   => false,
            'has-share'     => true,
            'share-url'     => 'https://getpocket.com/edit?url='.$post_url,
        ),
        'whatsapp' => array(
            'social-name'   => 'WhatsApp',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-whatsapp',
            'has-profile'   => false,
            'has-share'     => true,
            'share-url'     => 'https://api.whatsapp.com/send?text='.$post_title.'%20'.$post_url,
        ),
        'telegram' => array(
            'social-name'   => 'Telegram',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-telegram-plane',
            'has-profile'   => false,
            'has-share'     => true,
            'share-url'     => 'https://t.me/share/url?url='.$post_url.'&text='.$post_title,
        ),
        'github' => array(
            'social-name'   => 'GitHub',
            'icon-style'    => 'fab',
            'icon-name'     => 'fa-github',
            'has-profile'   => false,
            'has-share'     => false,
        ),
        'mail' => array(
            'social-name'   => 'E-Mail',
            'icon-style'    => 'fas',
            'icon-name'     => 'fa-envelope',
            'has-profile'   => false,
            'has-share'     => true,
            'share-url'     => 'mailto:?subject='.$post_title.'&body='.$post_url,
        ),
    );
    return $sb_socialnetworks;
}

// --- Thumbnail alt ---

// Echoes the "alt" value of a post thumbnail as inserted in the media gallery

function sb_thumb_alt() {

    $sb_thumb_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    echo $sb_thumb_alt;

}

// --- Sanitize & inline SVG ---

// Sanitize SVG before inlining;
// "Sanitizer" comes from "Safe SVG" (https://wordpress.org/plugins/safe-svg/) if active.
// If not, "SVG Sanitizer" (https://github.com/darylldoyle/svg-sanitizer) must be installed from Composer

use enshrined\svgSanitize\Sanitizer;

function sb_safe_inline_svg($sourceSVG) {

    $sanitizer = new Sanitizer();
    $dirtySVG = file_get_contents($sourceSVG);
    $cleanSVG = $sanitizer->sanitize($dirtySVG);
    return $cleanSVG;

}

// --- Custom logo SVG ---

// Inlines custom logo if it is in SVG format

function sb_custom_logo_svg() {

    $logourl = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full');
    $logoid = attachment_url_to_postid($logourl);
    $logomime = get_post_mime_type($logoid);

    if ($logomime == 'image/svg+xml') { ?>

        <a href="<?php echo esc_url_raw(home_url()); ?>" class="custom-logo-link" rel="home"><div class="custom-logo"><?php echo sb_safe_inline_svg($logourl) ?></div></a>     
        
    <?php } else {

        return the_custom_logo();

    }   

}

// --- [sb] signature ---

// Used to print signature in the footer

function sb_signature($sigType = 'text') {

    $sigURL         = 'https://www.stefanobartoletti.it';
    $sigLogoFull    = get_template_directory_uri().'/dist/img/sb-logo-full.svg';
    $sigLogoSmall   = get_template_directory_uri().'/dist/img/sb-logo-small.svg';
    $sigLogoAlt     = 'Stefano Bartoletti Web Design';

    switch ($sigType) {

        case 'logo-full':
            echo '<a id="sb-signature" class="ml-md-auto" href="'.$sigURL.'" target="_blank">'.sb_safe_inline_svg($sigLogoFull).'</a>';
            break;

        case 'logo-small':
            echo '<a id="sb-signature" class="ml-md-auto" href="'.$sigURL.'" target="_blank">'.sb_safe_inline_svg($sigLogoSmall).'</a>';
            break;

        case 'text':
            echo '<span id="sb-signature" class="navbar-text ml-md-auto">Made by <a class="text-white-50" " href="'.$sigURL.'" target="_blank">Stefano Bartoletti</a></span>';
            break;

    }

}