<?php
require_once __DIR__ . '/../models/testimonial_model.php';

$testimonial_id = filter_input(INPUT_POST, 'testimonial_id', FILTER_VALIDATE_INT);
$toggle_field = $_POST['toggle_field'] ?? '';


//whitelist dweatured only to be edited
if ($testimonial_id && $toggle_field === 'is_featured') {
    toggleTestimonialFeatured($pdo,$testimonial_id);
}

header('location: ' . BASE_URL .'/?page=admin_dashboard');
exit;