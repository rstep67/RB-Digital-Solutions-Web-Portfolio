<?php
require_once __DIR__ . '/../../models/portfolio_model.php';
$portfolio_entries = get_portfolio_entries($pdo);
require_once __DIR__ . '/../../models/testimonial_model.php';
$featured_testimonial = getFeaturedTestimonial($pdo);
?>

<body>
    <!--hero-->
    <section class="homepage-hero" role="banner" aria-label="Welcome hero">
    
    
    <div class="homepage-hero-body">
        <h1>RB Digital Solutions</h1>
        <p class="tagline">Professional Web Development Services</p>
       
    </div>

</section>
<section class="two-col-section">
    <div class="Experience">
        <h2>Experience</h2>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Suspendisse tristique quis elit quis pharetra. 
            Integer pharetra volutpat tellus eget iaculis. 
            Nullam id est eget magna ornare fringilla elementum a sapien. 
            Cras convallis, tellus eu fringilla hendrerit, nibh nunc consequat mi, ultricies tincidunt massa dolor ac arcu. 
        </p>
    </div>
    <div class="Skills">
        <h2> Skills </h2>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Suspendisse tristique quis elit quis pharetra. 
            Integer pharetra volutpat tellus eget iaculis. 
            Nullam id est eget magna ornare fringilla elementum a sapien. 
            Cras convallis, tellus eu fringilla hendrerit, nibh nunc consequat mi, ultricies tincidunt massa dolor ac arcu. 
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
            <p class="testimonial-name">— <?= htmlspecialchars($featured_testimonial['full_name']) ?></p>
        </div>
    </section>
<?php endif;?>

<!--wordpress blog integration-->
<section class="latest_posts">
    <div class="container">
        <h2>Latest blog posts from willdaywm.co.uk</h2>

        <?php if (!empty($latest_posts)): ?>
            <div class="post-grid">
                <?php foreach ($latest_posts as $post): ?>
                    <article class="post-card">
                        <h3><?= htmlspecialchars($post->title->rendered) ?></h3>
                        <p><?=htmlspecialchars(mb_strimwidth(strip_tags($post->excerpt->rendered), 0, 150, '...'))?></p>
                        <a href="<?=htmlspecialchars($post->link)?>" target="_blank" rel="noopener noreferrer">
                            Read more on willdaywm.co.uk
                        </a>
                    </article>
                    <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p> unable to display latest posts at this time</p>
            <?php endif;?>
    </div>
</section>
</body>
