<?php
/**
 * Copy this file to config.php and fill in the values — or simply run
 * /install/install.php once, which creates config.php for you.
 * config.php is git-ignored and must never be committed.
 */
return [
    // MySQL database created in cPanel → "MySQL® Databases"
    'db_host' => 'localhost',
    'db_name' => 'cpaneluser_mcattorneys',
    'db_user' => 'cpaneluser_mcadmin',
    'db_pass' => 'change-me',

    // Folder the site lives in, WITHOUT trailing slash. '' when installed at the domain root.
    'base_path' => '',

    // Public canonical URL (no trailing slash) used in meta tags / emails.
    'site_url' => 'https://www.example.co.zw',

    // Contact form recipient and sender (sender should be an address on your own domain).
    'mail_to'   => 'admin@mcattorneys.co.zw',
    'mail_from' => 'no-reply@example.co.zw',

    // Blog
    'posts_per_page' => 6,
    'categories' => ['Conveyancing', 'Litigation', 'Estates', 'Labour Law', 'Commercial', 'Mining Law', 'News'],
];
