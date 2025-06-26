<?php
// Simple PHP server for the frontend
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If requesting the root, serve index.html
if ($uri == '/') {
    $uri = '/index.html';
}

// Get the requested file path
$requested_file = __DIR__ . $uri;

// If it's a directory or doesn't exist, serve index.html
if (is_dir($requested_file) || !file_exists($requested_file)) {
    $requested_file = __DIR__ . '/index.html';
}

// Only serve files that exist
if (file_exists($requested_file) && is_file($requested_file)) {
    $ext = pathinfo($requested_file, PATHINFO_EXTENSION);
    
    // Set content type based on file extension
    switch ($ext) {
        case 'html':
            header('Content-Type: text/html');
            break;
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'json':
            header('Content-Type: application/json');
            break;
    }
    
    readfile($requested_file);
} else {
    // Return 404 if file not found
    header('HTTP/1.1 404 Not Found');
    echo '404 Not Found';
} 