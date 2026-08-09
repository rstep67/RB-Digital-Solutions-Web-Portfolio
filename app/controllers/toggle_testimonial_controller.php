<?php
require_once __DIR__ . '/../models/testimonial_model.php';

$testimonial_id = filter_input(INPUT_POST, 'testimonial_id', FILTER_VALIDATE_INT);
$toggle_field = $_POST['toggle_field'] ?? '';


//whitelist featured only to be edited
if ($testimonial_id && $toggle_field === 'is_featured') {
    try {
        toggleTestimonialFeatured($pdo, $testimonial_id);
        $_SESSION['manage_testimonials_success'] = 'Testimonial featured status updated successfully';
    }

    catch (PDOException $e){
        $_SESSION['manage_testimonial_errors'] = ['database error: ' . $e->getMessage()];


    }
}

else {
    $_SESSION['manage_testimonials_errors'] = ['Unable to update testimonial, invalid request'];
}

header('location: ' . BASE_URL .'/?page=admin_dashboard');
exit;