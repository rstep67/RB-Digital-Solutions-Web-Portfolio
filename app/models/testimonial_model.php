<?php

//returns only featured testimonial on homepage
function getFeaturedTestimonial($pdo) {
    $stmt = $pdo ->prepare("SELECT t.content, u.full_name FROM testimonials t JOIN users u ON t.user_id=u.id WHERE t.is_visible = 1 AND t.is_featured = 1 LIMIT 1");
    $stmt ->execute();
    return $stmt ->fetch();

}

