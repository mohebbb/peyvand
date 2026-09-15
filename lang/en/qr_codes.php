<?php

return [

    'page' => [
        'label' => 'QR code defaults',
        'title' => 'QR code defaults',
        'save' => 'Save defaults',
        'saved' => 'QR code defaults saved',
    ],

    'actions' => [
        'download' => 'Download QR code',
        'download_tooltip' => 'Download QR code (default settings)',
        'customize' => 'Customize & download QR code',
        'customize_tooltip' => 'Customize QR code download (color, logo, format)',
        'customize_heading' => 'Customize QR code',
        'customize_description' => 'Design a personalized QR code and download it. You can also save the settings as default.',
        'download_submit' => 'Download',
        'copy_link' => 'Copy link',
    ],

    'form' => [
        'preview_url' => 'Preview URL',
        'preview_url_helper' => 'Only used for the preview image above.',
        'preview_alt' => 'QR code preview',
        'preview_failed' => 'The preview could not be rendered.',
        'format' => 'Format',
        'error_correction' => 'Error correction',
        'error_correction_L' => 'L — low (7%)',
        'error_correction_M' => 'M — medium (15%)',
        'error_correction_Q' => 'Q — quartile (25%)',
        'error_correction_H' => 'H — high (30%)',
        'size' => 'Size',
        'size_helper' => 'Width of the image in pixels.',
        'margin' => 'Quiet zone (margin)',
        'margin_helper' => 'Border around the code, in modules.',
        'foreground_color' => 'Foreground color',
        'background_color' => 'Background color',
        'transparent_background' => 'Transparent background',
        'transparent_background_hint' => 'PNG & SVG only',
        'logo_enabled' => 'Logo',
        'logo_path' => 'Logo image',
        'logo_path_helper' => 'Placed at the center on a white plate; keep it small for reliable scanning.',
        'logo_size_percent' => 'Logo size',
        'logo_size_percent_helper' => 'Percentage of the QR code width.',
        'save_as_default' => 'Save as default',
        'save_as_default_helper' => 'Use these settings for the quick download button.',
    ],

];
