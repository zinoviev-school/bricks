<?php

/**
 * Custom global functions.
 * These functions are meant to be accessed from multiple locations.
 * 
 */

// --- Social networks ---

// Used in Customizer and Social Icons element
// social name => icon name (FontAwesome 5)

function sb_socialnetworks() {
    
    $sb_socialnetworks = array(
        'facebook' => 'fa-facebook-f',
        'linkedin' => 'fa-linkedin-in',
        'instagram' => 'fa-instagram',
        'twitter' => 'fa-twitter',
        // 'youtube' => 'fa-youtube',
        // 'pinterest' => 'fa-pinterest-p',
        // 'tripadvisor' => 'fa-tripadvisor',
        // 'telegram' => 'fa-telegram-plane',
        // 'behance' => 'fa-behance',
        // 'dribbble' => 'fa-dribbble',
        // 'flickr' => 'fa-flickr',
        // 'github' => 'fa-github',
        // 'gitlab' => 'fa-gitlab',
    );
    return $sb_socialnetworks;
}


// --- [sb] signature ---

function sb_signature($sigType = 'text') {

    $sigURL         = 'https://www.stefanobartoletti.it';
    $sigLogoFull    = get_template_directory_uri().'/dist/img/sb-logo-full.svg';
    $sigLogoSmall   = get_template_directory_uri().'/dist/img/sb-logo-small.svg';
    $sigLogoAlt     = 'Stefano Bartoletti Web Design';

    switch ($sigType) {

        case 'logo-full':
            echo '<a class="ml-md-auto" href="'.$sigURL.'" target="_blank"><img class="sb-logo" src="'.$sigLogoFull.'" alt="'.$sigLogoAlt.'"></a>';
            break;

        case 'logo-small':
            echo '<a class="ml-md-auto" href="'.$sigURL.'" target="_blank"><img class="sb-logo" src="'.$sigLogoSmall.'" alt="'.$sigLogoAlt.'"></a>';
            break;

        case 'text':
            echo '<span class="navbar-text ml-md-auto">Made by <a class="navbar-text" href="'.$sigURL.'" target="_blank">Stefano Bartoletti</a></span>';
            break;

    }

}

