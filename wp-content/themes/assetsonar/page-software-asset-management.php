<?php
/**
 * Template Name: Software Asset Management Landing Page
 * Description: Landing page template for AssetSonar's Software Asset Management feature
 */

get_header(); ?>

<div class="sam-landing-page">
    <!-- Hero Section -->
    <section class="sam-hero">
        <div class="container">
            <div class="sam-hero__content">
                <div class="sam-hero__text">
                    <h1 class="sam-hero__title">
                        <?php echo get_field('hero_title') ?: 'Take Control of Your Software Assets'; ?>
                    </h1>
                    <p class="sam-hero__subtitle">
                        <?php echo get_field('hero_subtitle') ?: 'Streamline software license management, reduce costs, and ensure compliance with AssetSonar\'s new Software Asset Management feature.'; ?>
                    </p>
                    <div class="sam-hero__cta">
                        <a href="<?php echo get_field('hero_cta_link') ?: '#contact'; ?>" class="btn btn-primary btn-lg">
                            <?php echo get_field('hero_cta_text') ?: 'Book a Demo'; ?>
                        </a>
                        <a href="#features" class="btn btn-outline btn-lg">Learn More</a>
                    </div>
                </div>
                <div class="sam-hero__visual">
                    <?php 
                    $hero_image = get_field('hero_image');
                    if ($hero_image): ?>
                        <img src="<?php echo esc_url($hero_image['url']); ?>" 
                             alt="<?php echo esc_attr($hero_image['alt']); ?>"
                             class="sam-hero__image">
                    <?php else: ?>
                        <div class="sam-hero__placeholder">
                            <img src="/wp-content/uploads/2025/07/home-mockup.webp" alt="dashboard-screenshot">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="sam-features">
        <div class="container">
            <div class="sam-section-header">
                <h2 class="sam-section-title">
                    <?php echo get_field('features_title') ?: 'Powerful Software Asset Management Features'; ?>
                </h2>
                <p class="sam-section-subtitle">
                    <?php echo get_field('features_subtitle') ?: 'Everything you need to manage your software assets effectively'; ?>
                </p>
            </div>
            
            <div class="sam-features__grid">
                <?php 
                $features = get_field('features');
                if ($features):
                    foreach ($features as $feature): ?>
                        <div class="sam-feature-card">
                            <div class="sam-feature-card__icon">
                                <?php if ($feature['icon']): ?>
                                    <img src="<?php echo esc_url($feature['icon']['url']); ?>" 
                                         alt="<?php echo esc_attr($feature['icon']['alt']); ?>">
                                <?php else: ?>
                                    <div class="sam-feature-card__icon-placeholder"></div>
                                <?php endif; ?>
                            </div>
                            <h3 class="sam-feature-card__title"><?php echo esc_html($feature['title']); ?></h3>
                            <p class="sam-feature-card__description"><?php echo esc_html($feature['description']); ?></p>
                        </div>
                    <?php endforeach;
                else: 
                    // Default features if ACF not configured
                    $default_features = [
                        [
                            'title' => 'License Renewal Reminders',
                            'description' => 'Never miss a renewal date with automated alerts and notifications for upcoming software license expirations.'
                        ],
                        [
                            'title' => 'Risk & Compliance Flagging',
                            'description' => 'Identify compliance risks and unauthorized software installations before they become costly problems.'
                        ],
                        [
                            'title' => 'Cost Forecasting',
                            'description' => 'Predict future software costs and optimize your budget with intelligent spending analytics.'
                        ],
                        [
                            'title' => 'Vendor Management',
                            'description' => 'Centralize vendor relationships and track all software agreements in one unified dashboard.'
                        ]
                    ];
                    
                    foreach ($default_features as $feature): ?>
                        <div class="sam-feature-card">
                            <div class="sam-feature-card__icon">
                                <div class="sam-feature-card__icon-placeholder"></div>
                            </div>
                            <h3 class="sam-feature-card__title"><?php echo esc_html($feature['title']); ?></h3>
                            <p class="sam-feature-card__description"><?php echo esc_html($feature['description']); ?></p>
                        </div>
                    <?php endforeach;
                endif; ?>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="sam-testimonial">
        <div class="container">
            <div class="sam-testimonial__content">
                <div class="sam-testimonial__quote">
                    <blockquote>
                        "<?php echo get_field('testimonial_quote') ?: 'AssetSonar\'s Software Asset Management has transformed how we handle our IT infrastructure. We\'ve reduced software costs by 30% and eliminated compliance risks entirely.'; ?>"
                    </blockquote>
                    <div class="sam-testimonial__author">
                        <?php 
                        $testimonial_image = get_field('testimonial_image');
                        if ($testimonial_image): ?>
                            <img src="<?php echo esc_url($testimonial_image['url']); ?>" 
                                 alt="<?php echo esc_attr($testimonial_image['alt']); ?>"
                                 class="sam-testimonial__avatar">
                        <?php else: ?>
                            <div class="sam-testimonial__avatar-placeholder"></div>
                        <?php endif; ?>
                        <div class="sam-testimonial__author-info">
                            <cite class="sam-testimonial__name">
                                <?php echo get_field('testimonial_name') ?: 'Sarah Johnson'; ?>
                            </cite>
                            <p class="sam-testimonial__title">
                                <?php echo get_field('testimonial_title') ?: 'IT Manager, TechCorp Solutions'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contact" class="sam-contact">
        <div class="container">
            <div class="sam-contact__content">
                <div class="sam-contact__info">
                    <h2 class="sam-contact__title">
                        <?php echo get_field('contact_title') ?: 'Ready to Optimize Your Software Assets?'; ?>
                    </h2>
                    <p class="sam-contact__subtitle">
                        <?php echo get_field('contact_subtitle') ?: 'Book a personalized demo and see how AssetSonar can transform your IT asset management.'; ?>
                    </p>
                    <div class="sam-contact__benefits">
                        <div class="sam-contact__benefit">
                            <span class="sam-contact__benefit-icon">✓</span>
                            <span>Free 30-day trial</span>
                        </div>
                        <div class="sam-contact__benefit">
                            <span class="sam-contact__benefit-icon">✓</span>
                            <span>No credit card required</span>
                        </div>
                        <div class="sam-contact__benefit">
                            <span class="sam-contact__benefit-icon">✓</span>
                            <span>Setup in under 5 minutes</span>
                        </div>
                    </div>
                </div>
                <div class="sam-contact__form">
                    <form class="sam-form" id="sam-contact-form" novalidate>
                        <div class="sam-form__row">
                            <div class="sam-form__group">
                                <label for="firstName" class="sam-form__label">First Name *</label>
                                <input type="text" id="firstName" name="firstName" class="sam-form__input" required>
                            </div>
                            <div class="sam-form__group">
                                <label for="lastName" class="sam-form__label">Last Name *</label>
                                <input type="text" id="lastName" name="lastName" class="sam-form__input" required>
                            </div>
                        </div>
                        <div class="sam-form__group">
                            <label for="email" class="sam-form__label">Work Email *</label>
                            <input type="email" id="email" name="email" class="sam-form__input" required>
                        </div>
                        <div class="sam-form__group">
                            <label for="company" class="sam-form__label">Company *</label>
                            <input type="text" id="company" name="company" class="sam-form__input" required>
                        </div>
                        <div class="sam-form__group">
                            <label for="teamSize" class="sam-form__label">Team Size</label>
                            <select id="teamSize" name="teamSize" class="sam-form__select">
                                <option value="">Select team size</option>
                                <option value="1-10">1-10 people</option>
                                <option value="11-50">11-50 people</option>
                                <option value="51-200">51-200 people</option>
                                <option value="200+">200+ people</option>
                            </select>
                        </div>
                        <div class="sam-form__group">
                            <label for="message" class="sam-form__label">Message</label>
                            <textarea id="message" name="message" class="sam-form__textarea" rows="4" 
                                      placeholder="Tell us about your software asset management needs..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg sam-form__submit">
                            Book Your Demo
                        </button>
                        <p class="sam-form__privacy">
                            By submitting this form, you agree to our 
                            <a href="/privacy-policy">Privacy Policy</a> and 
                            <a href="/terms-of-service">Terms of Service</a>.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Form handling
    const form = document.getElementById('sam-contact-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#ef4444';
                    isValid = false;
                } else {
                    field.style.borderColor = '#e2e8f0';
                }
            });
            
            // Email validation
            const emailField = form.querySelector('#email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailField && !emailRegex.test(emailField.value)) {
                emailField.style.borderColor = '#ef4444';
                isValid = false;
            }
            
            if (isValid) {
                // Simulate form submission
                const submitBtn = form.querySelector('.sam-form__submit');
                const originalText = submitBtn.textContent;
                
                submitBtn.textContent = 'Submitting...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    alert('Thank you! We\'ll be in touch soon to schedule your demo.');
                    form.reset();
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }, 1500);
            }
        });
    }
    
    // Add loading animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe feature cards for animation
    document.querySelectorAll('.sam-feature-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>

<?php get_footer(); ?>