<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    */

    'backup' => [
        // Path where backups will be stored
        'path' => storage_path('backups'),

        // Retention period in days (backups older than this will be deleted)
        'retention_days' => env('BACKUP_RETENTION_DAYS', 30),

        // Whether to compress backups by default
        'compress' => env('BACKUP_COMPRESS', false),

        // Tables to exclude from backup (optional)
        'exclude_tables' => [
            // 'sessions',
            // 'cache',
        ],
    ],
];
