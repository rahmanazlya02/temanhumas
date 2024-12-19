<?php

return [

    // Login form
    'login_form' => [

        // Enabled
        'is_enabled' => true

    ],

    // Locales
    'locales' => [

        // Locales list
        'list' => [
            'en' => 'English',
            'id' => 'Indonesian',
        ],

    ],

    // Projects configuration
    'projects' => [

        // Users affectations
        'affectations' => [

            // Users affectations roles
            'roles' => [

                // Default role
                'default' => 'anggota',

                // Role that can manage
                'can_manage' => 'administrator',

                // Role that can complete
                'can_complete' => ['administrator', 'koordinator'],

                // Roles list
                'list' => [
                    'anggota' => 'Anggota',
                    'koordinator' => 'Koordinator Subtim',
                    'administrator' => 'Ketua Tim Humas'
                ],

                // Roles colors
                'colors' => [
                    'primary' => 'staff',
                    'warning' => 'coordinator',
                    'danger' => 'administrator'
                ],

            ],

        ],

    ],

    // Tickets configuration
    'tickets' => [

        // Ticket relations types
        'relations' => [

            // Default type
            'default' => 'related_to',

            // Types list
            'list' => [
                'related_to' => 'Related to',
                'blocked_by' => 'Blocked by',
                'duplicate_of' => 'Duplicate of'
            ],

            // Types colors
            'colors' => [
                'related_to' => 'primary',
                'blocked_by' => 'warning',
                'duplicate_of' => 'danger',
            ],

        ],

    ],

    // System constants
    'max_file_size' => 10240,

];
