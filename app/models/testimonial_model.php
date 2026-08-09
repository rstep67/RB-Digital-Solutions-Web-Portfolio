<?php

//returns only featured testimonial on homepage
function getFeaturedTestimonial($pdo) {
    $stmt = $pdo ->prepare("SELECT content, author_name FROM testimonials WHERE is_featured = 1 LIMIT 1");
    $stmt ->execute();
    return $stmt ->fetch();

}

//returns every testimonial for featured and visible management 
function getAllTestimonials($pdo) 
{
    $stmt = $pdo ->prepare("SELECT id, content, author_name, is_featured FROM testimonials ORDER BY created_at DESC");
    $stmt ->execute();
    return $stmt->fetchAll();

}

//developer manually add new testimonial 
function createTestimonial($pdo, $author_name, $content) {
    $stmt = $pdo ->prepare("INSERT INTO testimonials (author_name, content, is_featured) VALUES (?,?,0)");
    $stmt ->Execute([$author_name, $content]);
}


//changes is_featured on single testimonial 

function toggleTestimonialFeatured($pdo,$testimonial_id) {
    $check = $pdo->prepare("SELECT is_featured FROM testimonials WHERE id = ?");
    $check ->execute([$testimonial_id]);
    $current = $check ->fetchColumn();

    $pdo ->beginTransaction();
    try {
        //unset other featured testimonial first
        $pdo ->prepare("UPDATE testimonials SET is_featured = 0 WHERE id !=?")->execute([$testimonial_id]);
        $pdo ->prepare("UPDATE testimonials SET is_featured = ? WHERE id = ?")->execute([$current ? 0 : 1, $testimonial_id]);
        $pdo->commit();
    }
    catch(PDOException $e) {
        $pdo ->rollBack();
        throw $e;
    }
}