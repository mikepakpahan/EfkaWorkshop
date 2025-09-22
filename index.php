<?php
require 'blade_setup.php';
require 'resources/backend/config/database.php';
require 'resources/backend/helpers/layout_helpers.php';
require 'resources/backend/handlers/landing_page_handler.php';

// Minta data dari Layout Helper
$layoutData = getSharedLayoutData($conn);

// Minta data dari Koki Landing Page
$pageData = getLandingPageData($conn);

// Gabungkan semua data
$viewData = array_merge($layoutData, $pageData);

// Render view dengan semua data
echo $blade->make('customer.landing.landing', $viewData)->render();

$conn->close();
