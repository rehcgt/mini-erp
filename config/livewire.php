<?php

return [
    'manifest_path' => null,
    'app_url' => null,
    'asset_url' => env('LIVEWIRE_ASSET_URL', '/mini-erp'),
    'middleware' => [
        'web',
    ],
    'alias' => 'livewire',
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'components.layouts.app',
    'render_on_redirect' => false,
    'temporary_file_upload' => [
        'disk' => null,
        'rules' => null,
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4', 'mov', 'avi', 'wmv', 'mp3', 'm4a', 'webm', 'ogg', 'oga', 'flac', 'aac', 'opus', 'jpg', 'jpeg', 'webp',
        ],
        'max_upload_time' => 5,
    ],
    'inject_assets' => true,
    'inject_morph_markers' => true,
    'legacy_model_binding' => false,
    'features' => [],
];
