<?php

//returns only featured testimonial on homepage
function getFeaturedTestimonial($pdo) {
    $stmt = $pdo ->prepare("SELECT t.content, u.full_name FROM testimonials t JOIN users u ON t.user_id=u.id WHERE t.is_visible = 1 AND t.is_featured = 1 LIMIT 1");
    $stmt ->execute();
    return $stmt ->fetch();

}

//returns every testimonial for featured and visible management 
function getAllTestimonials($pdo) 
{
    $stmt = $pdo ->prepare("SELECT t.id, t.content, t.is_visible, t.is_featured, u.full_name FROM testimonials t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
    $stmt ->execute();
    return $stmt->fetchAll();

}

//changes is_visible on single testimonial 
function toggleTestimonialVisible($pdo, $testimonial_id) {
    $stmt = $pdo->prepare("UPDATE testimonials SET is_visible = NOT is_visible WHERE id = ?");
    $stmt ->execute([$testimonial_id]);
}

//changes is_featured on single testimonial 

function toggleTestimonialFeatured($pdo,$testimonial_id) {
    $stmt = $pdo->prepare("UPDATE testimonials SET is_featured = NOT is_featured WHERE id = ?");
    $stmt ->execute([$testimonial_id]);
}