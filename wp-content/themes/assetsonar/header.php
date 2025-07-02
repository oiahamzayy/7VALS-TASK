<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Primary font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('sam-template'); ?>>
<?php wp_body_open(); ?>

<!-- Custom Navigation for SAM Landing Page -->
<header class="sam-header">
    <nav class="sam-nav">
        <div class="container">
            <div class="sam-nav__content">
                <div class="sam-nav__logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="sam-nav__logo-link">
                        <!-- Replace with your actual logo -->
                        <svg width="140" height="32" viewBox="0 0 140 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="32" height="32" rx="8" fill="#2563eb"/>
                            <path d="M12 8h8v2h-8V8zm0 4h8v2h-8v-2zm0 4h6v2h-6v-2z" fill="white"/>
                            <text x="40" y="20" font-family="Inter, sans-serif" font-size="18" font-weight="700" fill="#0f172a">AssetSonar</text>
                        </svg>
                    </a>
                </div>
                
                <div class="sam-nav__menu">
                    <div class="sam-nav__links">
                        <a href="#features" class="sam-nav__link">Features</a>
                        <a href="/pricing" class="sam-nav__link">Pricing</a>
                        <a href="/resources" class="sam-nav__link">Resources</a>
                        <a href="/contact" class="sam-nav__link">Contact</a>
                    </div>
                    
                    <div class="sam-nav__actions">
                        <a href="/login" class="sam-nav__login">Login</a>
                        <a href="#contact" class="btn btn-primary btn-sm">Get Started</a>
                    </div>
                </div>
                
                <!-- Mobile menu toggle -->
                <button class="sam-nav__toggle" id="sam-mobile-toggle" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
            
            <!-- Mobile menu -->
            <div class="sam-nav__mobile" id="sam-mobile-menu">
                <div class="sam-nav__mobile-links">
                    <a href="#features" class="sam-nav__mobile-link">Features</a>
                    <a href="/pricing" class="sam-nav__mobile-link">Pricing</a>
                    <a href="/resources" class="sam-nav__mobile-link">Resources</a>
                    <a href="/contact" class="sam-nav__mobile-link">Contact</a>
                    <a href="/login" class="sam-nav__mobile-link">Login</a>
                </div>
                <div class="sam-nav__mobile-actions">
                    <a href="#contact" class="btn btn-primary btn-lg">Get Started</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<style>

</style>

<script>
// Mobile menu toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('sam-mobile-toggle');
    const mobileMenu = document.getElementById('sam-mobile-menu');
    
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            mobileToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
        
        // Close mobile menu when clicking on links
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            });
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });
    }
});
</script>