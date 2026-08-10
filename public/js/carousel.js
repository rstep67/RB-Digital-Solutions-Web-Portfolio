/**wordpress blog post carousel
 * pattern adapted from:
 * Waldron, B. (n.d.) 'Performance first carousels with CSS scroll snap'
 * Available at: https://bwaldron.dev/articles/tutorial-css-scroll-snap
 * 
 * Card width measurement adapted from:
 * Velan, R. (2026) 'A scrollable carousel with buttons'
 * Available at: https://raghavvelan.medium.com/a-scrollable-carousel-with-buttons-40b8de20669c
 * makes carousel card size responsive to viewport size. 
 */

document.addEventListener('DOMContentLoaded', function() {
    var postGrid = document.getElementById('postGrid');
    var prevBtn = document.getElementById('prevPostBtn');
    var nextBtn = document.getElementById('nextPostBtn');

    if(!postGrid || !prevBtn || !nextBtn) {
        return;
    }

    function getScrollAmount() {
        var firstCard = postGrid.querySelector('.post-card');
        if(!firstCard) {
            return postGrid.clientWidth;
        }

        var gap = parseFloat(window.getComputedStyle(postGrid).columnGap) || 0;
        return firstCard.getBoundingClientRect().width+gap;
    }

    prevBtn.addEventListener('click',function() {

    postGrid.scrollBy({left:-getScrollAmount(),behavior:'smooth'});

    });
    nextBtn.addEventListener('click',function(){
        postGrid.scrollBy({left:getScrollAmount(),behavior:'smooth'});
    });



});