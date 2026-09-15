<?php

return [

    'navigation' => [
        'label' => 'Short links',
    ],

    'model' => [
        'label' => 'Short link',
        'plural' => 'Short links',
    ],

    'fields' => [
        'title' => 'Title',
        'destination_url' => 'Destination URL',
        'destination_url_helper' => 'The full destination URL (e.g. https://example.gov.ir/services/portal)',
        'short_code' => 'Code',
        'destination' => 'Destination',
        'clicks' => 'Clicks',
        'is_active' => 'Active',
        'created_at' => 'Created at',
    ],

    'messages' => [
        'short_url_copied' => 'Short URL copied',
        'link_copied' => 'Link copied',
        'click_to_activate' => 'Click to activate',
        'click_to_deactivate' => 'Click to deactivate',
    ],

];
