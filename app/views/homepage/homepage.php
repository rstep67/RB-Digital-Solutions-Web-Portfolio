<?php
require_once __DIR__ . '/../../models/portfolio_model.php';
$portfolio_entries = get_portfolio_entries($pdo);
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

<?php include __DIR__ . '/../contact/contact_form.php' ;?>

</body>
