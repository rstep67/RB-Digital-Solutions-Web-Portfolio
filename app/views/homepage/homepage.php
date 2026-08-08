<?php
require_once __DIR__ . '/../../models/portfolio_model.php';
$portfolio_entries = getPortfolioEntries($pdo);
require_once __DIR__ . '/../../models/testimonial_model.php';
$featured_testimonial = getFeaturedTestimonial($pdo);
require_once __DIR__ .'/../../models/site_content_model.php';
$site_content = getSiteContent($pdo);
?>

<body>
    <!--hero-->
    <section class="homepage-hero" role="banner" aria-label="Welcome hero">
    
    
    <div class="homepage-hero-body">
        <h1>RB Digital Solutions</h1>
        <p class="tagline">Professional Web Development Services</p>
        <p class="availability-bubble <?=$site_content['is_available'] ? 'available' : 'unavailable' ?>">
            <?php if ($site_content['is_available']): ?>
                <em>Currently taking new clients, <a href="#contact">enquire now</a></em>
            <?php else:?>
                <em>Not currently taking new clients</em>
                <?php endif;?>
        </p>
       
    </div>

</section>
<section class="two-col-section">
    <div class="Services">
        <h2>Experience</h2>
        <p>
            <?= nl2br(htmlspecialchars($site_content['experience_text'])) ?>
        </p>
    </div>
    <div class="Skills">
        <h2> Skills </h2>
        <p>
            <?= nl2br(htmlspecialchars($site_content['skills_text'])) ?> 
        </p>
    </div>

</section>

<section class="projects-section">
    <div class="container">
        <h2>Projects</h2>

        <?php if (empty($portfolio_entries)): ?>
            <p> No available portfolio entries</p>
            <?php else: ?>
                <div class="projects-list">
                    <?php foreach ($portfolio_entries as $entry): ?>
                        <a class="project-row" href="<?=BASE_URL?>/?page=project&id=<?=(int) $entry['id'] ?>"
                            <?php if (!empty($entry['media_url'])): ?>style="background-image: url('<?=htmlspecialchars($entry['media_url']) ?>');"
                            <?php endif; ?>>
                            <div class="project-row-overlay">

                            </div>
                            <Span class="project-row-title"> <?=htmlspecialchars($entry['title']) ?> </Span>
                            <span class="project-row-chevron" aria-hidden="true">></span>
                        </a>
                    <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                
    </div>
    
</section>

<section id="contact">
<?php include __DIR__ . '/../contact/contact_form.php' ;
?>
</section>

<?php if ($featured_testimonial): ?>
    <section class="testimonial-featured">
        <div class="container">
            <p class="testimonial-quote">"<?= htmlspecialchars($featured_testimonial['content']) ?>"</p>
            <p class="testimonial-name">— <?= htmlspecialchars($featured_testimonial['author_name']) ?></p>
        </div>
    </section>
<?php endif;?>

<!--wordpress blog integration-->

<section class="latest_posts">
    <div class="container">
        <h2>Latest blog posts from willdaywm.co.uk</h2>

        <?php if (!empty($latest_posts)): ?>
            <div class="carousel-wrapper">
                <button type="button" class="carousel-btn" id="prevPostBtn" aria-label="Previous posts">&lt;</button>
                 <div class="post-grid" id="postGrid">
                <?php foreach ($latest_posts as $post): ?>
                    <article class="post-card">
                        <?php
                        $featured_media = $post->_embedded->{'wp:featuredmedia'}[0] ?? null;
                        $image_url = $featured_media->media_details->sizes->full->source_url ?? null;
                        $image_alt = $featured_media->alt_text ?? '';

                        /*fallback to title if no alt test*/
                        if (empty($image_alt)) {
                            $image_alt = $post->title->rendered;
                        }
                        ?>
                        <?php if ($image_url): ?>
                            <img src="<?=htmlspecialchars($image_url) ?>" alt="<?=htmlspecialchars($image_alt)?>">
                            <?php endif; ?>



                        <h3><?= htmlspecialchars($post->title->rendered) ?></h3>
                        <p><?=htmlspecialchars(mb_strimwidth(strip_tags($post->excerpt->rendered), 0, 150, '...'))?></p>
                        <a href="<?=htmlspecialchars($post->link)?>" target="_blank" rel="noopener noreferrer">
                            Read more on willdaywm.co.uk
                        </a>
                    </article>
                    <?php endforeach; ?>
            </div>
            <button type="button" class="carousel-btn" id="nextPostBtn" aria-label="Next posts">&gt;</button>

            </div>




           
            <?php else: ?>
                <p> unable to display latest posts at this time</p>
            <?php endif;?>
    </div>
</section>
</body>
