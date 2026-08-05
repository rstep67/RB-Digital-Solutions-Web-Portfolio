<?php
require_once __DIR__ . '/../models/testimonial_model.php';

$testimonial_id = filter_input(INPUT_POST, 'testimonial_id', FILTER_VALIDATE_INT);
$toggle_field = $_POST['toggle_field'] ?? '';


//whitelist visibility and featured only to be edited 
if ($testimonial_id && in_array($toggle_field, ['is_visible', 'is_featured'], true)) {
    if ($toggle_field === 'is_visible') {
        toggleTestimonialVisible($pdo, $testimonial_id);

    }
    else {
        toggleTestimonialFeatured($pdo,$testimonial_id);
    }
}

header('location: ' . BASE_URL . '/?page=admin_dashboard');
exit;