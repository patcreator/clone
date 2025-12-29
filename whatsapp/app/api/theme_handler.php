<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header('Content-Type: application/json');

// Check if theme parameter is provided
if (isset($_GET['theme'])) {
    $theme = $_GET['theme'];
    
    // Validate theme
    if ($theme === 'dark' || $theme === 'light') {
        $_SESSION['theme'] = $theme;
        
        echo json_encode([
            'success' => true,
            'message' => 'Theme updated successfully',
            'theme' => $theme
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid theme value'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No theme specified'
    ]);
}
?>