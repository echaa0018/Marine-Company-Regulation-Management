<?php

// Forward Vercel requests to Laravel's public/index.php

// Set the current working directory to the Laravel root
chdir(dirname(__DIR__));

// Check if we're in a Vercel environment
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    // Set up Laravel environment for Vercel
    $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../public';
    $_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}

// Include Laravel's public index.php
require __DIR__ . '/../public/index.php';