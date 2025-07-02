# AssetSonar Software Asset Management Landing Page

A modern, responsive WordPress landing page template built for AssetSonar's Software Asset Management feature. This template includes custom ACF fields, optimized performance, and follows modern SaaS design practices.

## Features

- **Responsive Design**: Mobile-first approach with clean, modern aesthetics
- **ACF Integration**: Custom fields for easy content management without page builders
- **Performance Optimized**: Compressed assets, semantic HTML, and critical CSS
- **SEO Ready**: Meta tags, structured data, and optimized markup
- **Accessibility**: WCAG compliant with proper ARIA labels and semantic structure
- **Interactive Elements**: Smooth scrolling, form validation, and loading animations

## File Structure

```
├── page-software-asset-management.php    # Main page template
├── header.php                        # Custom header with navigation
├── footer.php                        # Custom footer
├── functions.php                         # WordPress functions (additions)
├── acf-fields-software-asset-management.json  # ACF fields configuration
└── README.md                             # This file
```

## Installation Instructions

### 1. Upload Template Files

Copy the following files to your active WordPress theme directory:

```bash
wp-content/themes/assetsonar/
├── page-software-asset-management.php
├── header.php
├── footer.php
```

### 2. Add Functions to WordPress

Add the code from `functions.php` to your theme's `functions.php` file:

```php
// Add the entire content of the functions.php file to your theme's functions.php
```

### 3. Install Advanced Custom Fields (ACF)

1. Install the ACF plugin if not already installed:
   - Download from WordPress.org or install via admin dashboard
   - Activate the plugin

### 4. Import ACF Fields

**Option A: Import JSON (Recommended)**
1. Go to **Custom Fields → Tools** in WordPress admin
2. Click **Import Field Groups**
3. Select the `acf-fields-software-asset-management.json` file
4. Click **Import File Groups**

**Option B: Programmatic (Alternative)**
The fields are also defined programmatically in functions.php and will be automatically created.

### 5. Create the Landing Page

1. Go to **Pages → Add New** in WordPress admin
2. Set the title to "Software Asset Management"
3. In **Page Attributes**, select **Software Asset Management Landing Page** as the template
4. Publish the page

### 6. Configure Content

After creating the page, you'll see custom ACF fields in the editor:

#### Hero Section
- **Hero Title**: Main headline
- **Hero Subtitle**: Supporting text
- **Hero CTA Text**: Button text
- **Hero CTA Link**: Button URL
- **Hero Image**: Screenshot or illustration

#### Features Section
- **Features Title**: Section headline
- **Features Subtitle**: Section description
- **Features**: Repeater field for feature cards
  - Icon (optional)
  - Title
  - Description

#### Testimonial Section
- **Testimonial Quote**: Customer quote
- **Testimonial Name**: Customer name
- **Testimonial Title**: Job title and company
- **Testimonial Image**: Customer photo

#### Contact Section
- **Contact Title**: Section headline
- **Contact Subtitle**: Section description

## Customization

### Design Variables

The template uses CSS custom properties for easy theming:

```css
:root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary-color: #f1f5f9;
    --text-primary: #0f172a;
    --text-secondary: #64748b;
    --border-radius: 8px;
}
```

### Adding Features

To add more feature cards:

1. Go to the page editor
2. Scroll to the **Features** section
3. Click **Add Row** in the Features repeater
4. Fill in the title and description
5. Optionally upload an icon image

### Form Integration

The contact form is currently a demo form. To integrate with your CRM:

1. Modify the `sam_handle_form_submission()` function in functions.php
2. Add your CRM API integration
3. Update the AJAX endpoint if needed

## Performance Optimizations

- **Critical CSS**: Inline critical styles for above-the-fold content
- **Font Loading**: Preconnect to Google Fonts with display=swap
- **Image Optimization**: Custom image sizes for different use cases
- **Reduced HTTP Requests**: Inline styles and scripts where appropriate
- **Semantic HTML**: Proper heading hierarchy and structure

## SEO Features

- **Meta Tags**: Dynamic title, description, and social media tags
- **Structured Data**: JSON-LD schema for better search visibility
- **Open Graph**: Facebook and social media optimization
- **Twitter Cards**: Enhanced Twitter sharing
- **Semantic HTML**: Proper heading structure and landmarks

## Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Accessibility

- **WCAG 2.1 AA** compliant
- **Keyboard Navigation**: Full keyboard accessibility
- **Screen Readers**: Proper ARIA labels and semantic markup
- **Color Contrast**: Meets accessibility contrast requirements
- **Focus Management**: Visible focus indicators

## Development

### Local Development

1. Set up a local WordPress environment
2. Install the template files
3. Configure ACF fields
4. Test responsiveness and functionality

### Staging Deployment

1. Upload files to staging environment
2. Import ACF configuration
3. Test all functionality
4. Optimize images and assets

### Production Deployment

1. Minify CSS and JavaScript if needed
2. Optimize images
3. Test performance with tools like:
   - Google PageSpeed Insights
   - GTmetrix
   - WebPageTest

## Troubleshooting

### Common Issues

**ACF Fields Not Showing**
- Ensure ACF plugin is installed and activated
- Check that the page template is correctly selected
- Verify ACF fields are imported correctly

**Styling Issues**
- Clear any caching plugins
- Check for theme CSS conflicts
- Ensure the template files are in the correct directory

**Form Not Working**
- Check that AJAX is enabled
- Verify the nonce validation
- Test with browser developer tools

### Support

For technical support or customization needs:

1. Check the WordPress error logs
2. Test with a default theme to isolate issues
3. Verify all files are uploaded correctly
4. Ensure proper file permissions

## License

This template is provided as-is for AssetSonar's use. Modify as needed for your specific requirements.

## Credits

- **Fonts**: Inter by Google Fonts
- **Icons**: Custom SVG icons
- **Framework**: Vanilla JavaScript and CSS
- **CMS**: WordPress with Advanced Custom Fields