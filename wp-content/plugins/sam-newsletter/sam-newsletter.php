<?php
/**
 * Plugin Name: SAM Newsletter
 * Plugin URI: https://example.com/sam-newsletter
 * Description: Professional newsletter subscription management with Gutenberg block for SaaS environments.
 * Version: 1.0.0
 * Author: Your Company
 * License: GPL v2 or later
 * Text Domain: sam-newsletter
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants FIRST - before the class
define('SAM_NEWSLETTER_VERSION', '1.0.0');
define('SAM_NEWSLETTER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SAM_NEWSLETTER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SAM_NEWSLETTER_TABLE_NAME', 'sam_newsletter');

/**
 * Main plugin class - All-in-one version
 */
class SAM_Newsletter_Plugin {
    
    public function __construct() {
        add_action('init', [$this, 'init']);
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        
        // Add debug info early
        add_action('wp_footer', [$this, 'debug_frontend']);
    }
    
    public function debug_frontend() {
        if (current_user_can('manage_options')) {
            echo '<div style="position: fixed; bottom: 10px; right: 10px; background: #000; color: #fff; padding: 10px; z-index: 9999; font-size: 12px; max-width: 300px;">';
            echo '<strong>SAM Newsletter Debug:</strong><br>';
            echo 'Plugin URL: ' . SAM_NEWSLETTER_PLUGIN_URL . '<br>';
            echo 'Block registered: ' . (WP_Block_Type_Registry::get_instance()->is_registered('sam-newsletter/subscription-form') ? 'YES' : 'NO') . '<br>';
            echo 'CSS registered: ' . (wp_style_is('sam-newsletter-frontend', 'registered') ? 'YES' : 'NO') . '<br>';
            echo 'JS registered: ' . (wp_script_is('sam-newsletter-frontend', 'registered') ? 'YES' : 'NO') . '<br>';
            echo 'CSS enqueued: ' . (wp_style_is('sam-newsletter-frontend', 'enqueued') ? 'YES' : 'NO') . '<br>';
            echo 'JS enqueued: ' . (wp_script_is('sam-newsletter-frontend', 'enqueued') ? 'YES' : 'NO') . '<br>';
            
            // Check if files exist
            $css_file = SAM_NEWSLETTER_PLUGIN_DIR . 'assets/css/frontend.css';
            $js_file = SAM_NEWSLETTER_PLUGIN_DIR . 'assets/js/frontend.js';
            echo 'CSS file exists: ' . (file_exists($css_file) ? 'YES' : 'NO') . '<br>';
            echo 'JS file exists: ' . (file_exists($js_file) ? 'YES' : 'NO') . '<br>';
            echo '</div>';
        }
    }
    
    public function init() {
        // Load text domain
        load_plugin_textdomain('sam-newsletter', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Initialize all components
        $this->init_admin();
        $this->init_gutenberg_block();
        $this->init_ajax_handlers();
        
        // Debug
        error_log('SAM Newsletter: Plugin initialized');
    }
    
    public function activate() {
        $this->create_database_table();
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    public function create_database_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . SAM_NEWSLETTER_TABLE_NAME;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY email (email)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Initialize admin interface
     */
    private function init_admin() {
        if (is_admin()) {
            add_action('admin_menu', [$this, 'add_admin_menu']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
            add_action('wp_ajax_sam_newsletter_delete_subscriber', [$this, 'ajax_delete_subscriber']);
        }
    }
    
    /**
     * Initialize Gutenberg block - FIXED VERSION
     */
    private function init_gutenberg_block() {
        // Register block directly in init, not as a separate action
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);
        
        // Register frontend assets early
        add_action('wp_enqueue_scripts', [$this, 'register_frontend_assets']);
        
        // Register the block immediately
        $this->register_block();
    }
    
    /**
     * Initialize AJAX handlers
     */
    private function init_ajax_handlers() {
        add_action('wp_ajax_sam_newsletter_subscribe', [$this, 'handle_subscription']);
        add_action('wp_ajax_nopriv_sam_newsletter_subscribe', [$this, 'handle_subscription']);
    }
    
    // ===== ADMIN FUNCTIONALITY =====
    
    public function add_admin_menu() {
        add_menu_page(
            __('Newsletter Subscribers', 'sam-newsletter'),
            __('Newsletter', 'sam-newsletter'),
            'manage_options',
            'sam-newsletter',
            [$this, 'admin_page'],
            'dashicons-email-alt',
            30
        );
    }
    
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_sam-newsletter') {
            return;
        }
        
        wp_enqueue_script('jquery');
        
        wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                $(".delete-subscriber").on("click", function(e) {
                    e.preventDefault();
                    
                    if (!confirm("Are you sure you want to delete this subscriber?")) {
                        return;
                    }
                    
                    var button = $(this);
                    var subscriberId = button.data("id");
                    var row = button.closest("tr");
                    
                    button.prop("disabled", true).text("Deleting...");
                    
                    $.ajax({
                        url: ajaxurl,
                        type: "POST",
                        data: {
                            action: "sam_newsletter_delete_subscriber",
                            id: subscriberId,
                            nonce: "' . wp_create_nonce('sam_newsletter_admin') . '"
                        },
                        success: function(response) {
                            if (response.success) {
                                row.fadeOut(300, function() {
                                    $(this).remove();
                                    if ($(".subscribers tbody tr").length === 0) {
                                        location.reload();
                                    }
                                });
                            } else {
                                alert("Failed to delete subscriber");
                                button.prop("disabled", false).text("Delete");
                            }
                        },
                        error: function() {
                            alert("An error occurred");
                            button.prop("disabled", false).text("Delete");
                        }
                    });
                });
            });
        ');
        
        wp_add_inline_style('wp-admin', '
            .sam-newsletter-stats {
                display: flex;
                gap: 1.5rem;
                margin: 1.5rem 0;
                flex-wrap: wrap;
            }
            
            .sam-newsletter-stat {
                background: #fff;
                border: 1px solid #c3c4c7;
                border-radius: 4px;
                padding: 1.5rem;
                min-width: 200px;
                text-align: center;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }
            
            .sam-newsletter-stat .count {
                display: block;
                font-size: 2rem;
                font-weight: 600;
                color: #1d2327;
                line-height: 1.2;
            }
            
            .sam-newsletter-stat .label {
                display: block;
                font-size: 0.875rem;
                color: #646970;
                margin-top: 0.5rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .subscribers .delete-subscriber {
                background: #dc3545;
                border-color: #dc3545;
                color: #fff;
                padding: 4px 12px;
                font-size: 12px;
                border-radius: 3px;
                cursor: pointer;
            }
            
            .subscribers .delete-subscriber:hover {
                background: #c82333;
                border-color: #bd2130;
            }
        ');
    }
    
    public function admin_page() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . SAM_NEWSLETTER_TABLE_NAME;
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $per_page = 20;
        $offset = ($current_page - 1) * $per_page;
        
        // Build query
        $where_clause = '';
        $where_values = [];
        
        if (!empty($search)) {
            $where_clause = " WHERE name LIKE %s OR email LIKE %s";
            $where_values = ["%{$search}%", "%{$search}%"];
        }
        
        // Get subscribers
        $query = "SELECT * FROM {$table_name}{$where_clause} ORDER BY created_at DESC LIMIT %d OFFSET %d";
        $where_values[] = $per_page;
        $where_values[] = $offset;
        
        $subscribers = $wpdb->get_results($wpdb->prepare($query, $where_values));
        
        // Get total count
        $count_query = "SELECT COUNT(*) FROM {$table_name}{$where_clause}";
        if (!empty($where_values)) {
            $count_values = array_slice($where_values, 0, -2);
            $total_count = $wpdb->get_var($wpdb->prepare($count_query, $count_values));
        } else {
            $total_count = $wpdb->get_var($count_query);
        }
        
        $total_pages = ceil($total_count / $per_page);
        
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php _e('Newsletter Subscribers', 'sam-newsletter'); ?></h1>
            
            <form method="get" class="sam-newsletter-search">
                <input type="hidden" name="page" value="sam-newsletter">
                <p class="search-box">
                    <label class="screen-reader-text" for="subscriber-search-input">
                        <?php _e('Search Subscribers', 'sam-newsletter'); ?>
                    </label>
                    <input type="search" id="subscriber-search-input" name="s" value="<?php echo esc_attr($search); ?>" style="width: 280px; margin-right: 8px;">
                    <input type="submit" id="search-submit" class="button" value="<?php _e('Search Subscribers', 'sam-newsletter'); ?>">
                </p>
            </form>
            
            <div class="sam-newsletter-stats">
                <div class="sam-newsletter-stat">
                    <span class="count"><?php echo number_format($total_count); ?></span>
                    <span class="label"><?php _e('Total Subscribers', 'sam-newsletter'); ?></span>
                </div>
            </div>
            
            <?php if (empty($subscribers)): ?>
                <div class="notice notice-info">
                    <p><?php _e('No subscribers found.', 'sam-newsletter'); ?></p>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped subscribers">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column column-name"><?php _e('Name', 'sam-newsletter'); ?></th>
                            <th scope="col" class="manage-column column-email"><?php _e('Email', 'sam-newsletter'); ?></th>
                            <th scope="col" class="manage-column column-date"><?php _e('Subscribed', 'sam-newsletter'); ?></th>
                            <th scope="col" class="manage-column column-actions"><?php _e('Actions', 'sam-newsletter'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscribers as $subscriber): ?>
                            <tr data-id="<?php echo esc_attr($subscriber->id); ?>">
                                <td class="column-name">
                                    <strong><?php echo esc_html($subscriber->name); ?></strong>
                                </td>
                                <td class="column-email">
                                    <a href="mailto:<?php echo esc_attr($subscriber->email); ?>">
                                        <?php echo esc_html($subscriber->email); ?>
                                    </a>
                                </td>
                                <td class="column-date">
                                    <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($subscriber->created_at))); ?>
                                </td>
                                <td class="column-actions">
                                    <button type="button" class="button delete-subscriber" data-id="<?php echo esc_attr($subscriber->id); ?>">
                                        <?php _e('Delete', 'sam-newsletter'); ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <?php if ($total_pages > 1): ?>
                    <div class="tablenav bottom">
                        <div class="tablenav-pages">
                            <?php
                            $pagination_args = [
                                'base' => add_query_arg('paged', '%#%'),
                                'format' => '',
                                'current' => $current_page,
                                'total' => $total_pages,
                                'prev_text' => '&laquo;',
                                'next_text' => '&raquo;'
                            ];
                            
                            if (!empty($search)) {
                                $pagination_args['add_args'] = ['s' => $search];
                            }
                            
                            echo paginate_links($pagination_args);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }
    
    public function ajax_delete_subscriber() {
        check_ajax_referer('sam_newsletter_admin', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized', 'sam-newsletter'));
        }
        
        $id = intval($_POST['id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . SAM_NEWSLETTER_TABLE_NAME;
        
        $result = $wpdb->delete(
            $table_name,
            ['id' => $id],
            ['%d']
        );
        
        if ($result !== false) {
            wp_send_json_success(['message' => __('Subscriber deleted successfully', 'sam-newsletter')]);
        } else {
            wp_send_json_error(['message' => __('Failed to delete subscriber', 'sam-newsletter')]);
        }
    }
    
    // ===== GUTENBERG BLOCK FUNCTIONALITY =====
    
    public function register_frontend_assets() {
        // Register assets early but don't enqueue yet
        wp_register_style(
            'sam-newsletter-frontend',
            SAM_NEWSLETTER_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            SAM_NEWSLETTER_VERSION
        );
        
        wp_register_script(
            'sam-newsletter-frontend',
            SAM_NEWSLETTER_PLUGIN_URL . 'assets/js/frontend.js',
            ['jquery'],
            SAM_NEWSLETTER_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'sam-newsletter-frontend',
            'samNewsletter',
            [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sam_newsletter_frontend')
            ]
        );
        
        error_log('SAM Newsletter: Frontend assets registered');
    }
    
    public function register_block() {
        error_log('SAM Newsletter: register_block called');
        
        // Check if Gutenberg is available
        if (!function_exists('register_block_type')) {
            error_log('SAM Newsletter: register_block_type not available, adding to init hook');
            add_action('init', [$this, 'register_block'], 10);
            return;
        }
        
        // Check if already registered
        if (WP_Block_Type_Registry::get_instance()->is_registered('sam-newsletter/subscription-form')) {
            error_log('SAM Newsletter: Block already registered');
            return;
        }
        
        // Register block type with render callback
        $result = register_block_type('sam-newsletter/subscription-form', [
            'render_callback' => [$this, 'render_block'],
            'attributes' => [
                'title' => [
                    'type' => 'string',
                    'default' => __('Subscribe to Our Newsletter', 'sam-newsletter')
                ],
                'description' => [
                    'type' => 'string',
                    'default' => __('Stay updated with our latest news and updates.', 'sam-newsletter')
                ],
                'buttonText' => [
                    'type' => 'string',
                    'default' => __('Subscribe', 'sam-newsletter')
                ],
                'showTitle' => [
                    'type' => 'boolean',
                    'default' => true
                ],
                'showDescription' => [
                    'type' => 'boolean',
                    'default' => true
                ]
            ]
        ]);
        
        if ($result) {
            error_log('SAM Newsletter: Block registered successfully');
        } else {
            error_log('SAM Newsletter: Block registration failed');
        }
    }
    
    public function enqueue_block_editor_assets() {
        error_log('SAM Newsletter: enqueue_block_editor_assets called');
        
        // Register and enqueue the block editor script
        wp_register_script(
            'sam-newsletter-block-editor',
            false, // No external file, we'll use inline script
            ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'],
            SAM_NEWSLETTER_VERSION
        );
        
        // Add inline script for block registration
        wp_add_inline_script('sam-newsletter-block-editor', '
            console.log("SAM Newsletter: Block editor script loading");
            
            (function() {
                const { registerBlockType } = wp.blocks;
                const { createElement: el, Fragment } = wp.element;
                const { RichText, InspectorControls, useBlockProps } = wp.blockEditor;
                const { PanelBody, TextControl, ToggleControl } = wp.components;
                const { __ } = wp.i18n;
                
                console.log("SAM Newsletter: About to register block type");

                registerBlockType("sam-newsletter/subscription-form", {
                    title: __("SAM Newsletter", "sam-newsletter"),
                    description: __("A newsletter subscription form for collecting email addresses", "sam-newsletter"),
                    icon: "email-alt",
                    category: "widgets",
                    keywords: [
                        __("newsletter", "sam-newsletter"),
                        __("subscription", "sam-newsletter"),
                        __("email", "sam-newsletter")
                    ],
                    supports: {
                        align: ["left", "center", "right", "wide", "full"]
                    },
                    attributes: {
                        title: {
                            type: "string",
                            default: __("Subscribe to Our Newsletter", "sam-newsletter")
                        },
                        description: {
                            type: "string",
                            default: __("Stay updated with our latest news and updates.", "sam-newsletter")
                        },
                        buttonText: {
                            type: "string",
                            default: __("Subscribe", "sam-newsletter")
                        },
                        showTitle: {
                            type: "boolean",
                            default: true
                        },
                        showDescription: {
                            type: "boolean",
                            default: true
                        }
                    },

                    edit: function(props) {
                        const { attributes, setAttributes } = props;
                        const { title, description, buttonText, showTitle, showDescription } = attributes;
                        
                        const blockProps = useBlockProps({
                            className: "sam-newsletter-block-editor"
                        });

                        return el(Fragment, {},
                            el(InspectorControls, {},
                                el(PanelBody, {
                                    title: __("Newsletter Settings", "sam-newsletter"),
                                    initialOpen: true
                                },
                                    el(ToggleControl, {
                                        label: __("Show Title", "sam-newsletter"),
                                        checked: showTitle,
                                        onChange: function(value) {
                                            setAttributes({ showTitle: value });
                                        }
                                    }),
                                    el(ToggleControl, {
                                        label: __("Show Description", "sam-newsletter"),
                                        checked: showDescription,
                                        onChange: function(value) {
                                            setAttributes({ showDescription: value });
                                        }
                                    }),
                                    el(TextControl, {
                                        label: __("Button Text", "sam-newsletter"),
                                        value: buttonText,
                                        onChange: function(value) {
                                            setAttributes({ buttonText: value });
                                        }
                                    })
                                )
                            ),

                            el("div", blockProps,
                                el("div", { 
                                    className: "sam-newsletter-form-container",
                                    style: {
                                        background: "#f8f9fa",
                                        border: "1px solid #e9ecef",
                                        borderRadius: "8px",
                                        padding: "2rem",
                                        margin: "0"
                                    }
                                },
                                    showTitle && el(RichText, {
                                        tagName: "h3",
                                        className: "sam-newsletter-title",
                                        value: title,
                                        onChange: function(value) {
                                            setAttributes({ title: value });
                                        },
                                        placeholder: __("Newsletter title...", "sam-newsletter"),
                                        style: {
                                            margin: "0 0 1rem 0",
                                            fontSize: "1.5rem",
                                            fontWeight: "600",
                                            color: "#2c3e50",
                                            textAlign: "center"
                                        }
                                    }),

                                    showDescription && el(RichText, {
                                        tagName: "p",
                                        className: "sam-newsletter-description",
                                        value: description,
                                        onChange: function(value) {
                                            setAttributes({ description: value });
                                        },
                                        placeholder: __("Newsletter description...", "sam-newsletter"),
                                        style: {
                                            margin: "0 0 1.5rem 0",
                                            color: "#6c757d",
                                            textAlign: "center",
                                            lineHeight: "1.6"
                                        }
                                    }),

                                    el("form", { 
                                        className: "sam-newsletter-form sam-newsletter-form-preview",
                                        style: { margin: "0" }
                                    },
                                        el("div", { 
                                            className: "sam-newsletter-fields",
                                            style: {
                                                display: "flex",
                                                flexDirection: "column",
                                                gap: "1rem"
                                            }
                                        },
                                            el("div", { className: "sam-newsletter-field" },
                                                el("input", {
                                                    type: "text",
                                                    placeholder: __("Your Name", "sam-newsletter"),
                                                    className: "sam-newsletter-input",
                                                    disabled: true,
                                                    style: {
                                                        width: "100%",
                                                        padding: "0.75rem 1rem",
                                                        border: "2px solid #e9ecef",
                                                        borderRadius: "6px",
                                                        fontSize: "1rem",
                                                        backgroundColor: "#fff",
                                                        boxSizing: "border-box",
                                                        opacity: "0.7"
                                                    }
                                                })
                                            ),
                                            el("div", { className: "sam-newsletter-field" },
                                                el("input", {
                                                    type: "email",
                                                    placeholder: __("Your Email", "sam-newsletter"),
                                                    className: "sam-newsletter-input",
                                                    disabled: true,
                                                    style: {
                                                        width: "100%",
                                                        padding: "0.75rem 1rem",
                                                        border: "2px solid #e9ecef",
                                                        borderRadius: "6px",
                                                        fontSize: "1rem",
                                                        backgroundColor: "#fff",
                                                        boxSizing: "border-box",
                                                        opacity: "0.7"
                                                    }
                                                })
                                            ),
                                            el("div", { className: "sam-newsletter-submit" },
                                                el("button", {
                                                    type: "button",
                                                    className: "sam-newsletter-button",
                                                    disabled: true,
                                                    style: {
                                                        width: "100%",
                                                        padding: "0.875rem 1.5rem",
                                                        backgroundColor: "#007cba",
                                                        color: "#fff",
                                                        border: "none",
                                                        borderRadius: "6px",
                                                        fontSize: "1rem",
                                                        fontWeight: "600",
                                                        cursor: "default",
                                                        opacity: "0.8"
                                                    }
                                                }, buttonText)
                                            )
                                        )
                                    )
                                )
                            )
                        );
                    },

                    save: function() {
                        // Server-side rendering
                        return null;
                    }
                });
                
                console.log("SAM Newsletter: Block type registered successfully");
            })();
        ');
        
        wp_enqueue_script('sam-newsletter-block-editor');
        error_log('SAM Newsletter: Block editor script enqueued');
    }
    
    public function render_block($attributes) {
        error_log('SAM Newsletter: render_block called');
        error_log('SAM Newsletter: Attributes: ' . print_r($attributes, true));
        
        $title = isset($attributes['title']) ? esc_html($attributes['title']) : 'Subscribe to Our Newsletter';
        $description = isset($attributes['description']) ? esc_html($attributes['description']) : 'Stay updated with our latest news and updates.';
        $button_text = isset($attributes['buttonText']) ? esc_html($attributes['buttonText']) : 'Subscribe';
        $show_title = isset($attributes['showTitle']) ? $attributes['showTitle'] : true;
        $show_description = isset($attributes['showDescription']) ? $attributes['showDescription'] : true;
        
        // Enqueue frontend assets when block is rendered
        wp_enqueue_style('sam-newsletter-frontend');
        wp_enqueue_script('sam-newsletter-frontend');
        
        error_log('SAM Newsletter: Assets enqueued in render_block');
        
        ob_start();
        ?>
        <div class="sam-newsletter-block">
            <div class="sam-newsletter-form-container">
                <?php if ($show_title): ?>
                    <h3 class="sam-newsletter-title"><?php echo $title; ?></h3>
                <?php endif; ?>
                
                <?php if ($show_description): ?>
                    <p class="sam-newsletter-description"><?php echo $description; ?></p>
                <?php endif; ?>
                
                <form class="sam-newsletter-form" method="post" novalidate>
                    <div class="sam-newsletter-fields">
                        <div class="sam-newsletter-field">
                            <input 
                                type="text" 
                                name="name" 
                                placeholder="<?php esc_attr_e('Your Name', 'sam-newsletter'); ?>" 
                                required
                                class="sam-newsletter-input"
                            >
                            <span class="sam-newsletter-error" data-field="name"></span>
                        </div>
                        
                        <div class="sam-newsletter-field">
                            <input 
                                type="email" 
                                name="email" 
                                placeholder="<?php esc_attr_e('Your Email', 'sam-newsletter'); ?>" 
                                required
                                class="sam-newsletter-input"
                            >
                            <span class="sam-newsletter-error" data-field="email"></span>
                        </div>
                        
                        <div class="sam-newsletter-submit">
                            <button type="submit" class="sam-newsletter-button">
                                <span class="button-text"><?php echo $button_text; ?></span>
                                <span class="button-loading" style="display: none;"><?php esc_html_e('Subscribing...', 'sam-newsletter'); ?></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="sam-newsletter-message" style="display: none;"></div>
                    
                    <?php wp_nonce_field('sam_newsletter_frontend', 'sam_newsletter_nonce'); ?>
                </form>
            </div>
        </div>
        <!-- DEBUG: Block rendered at <?php echo date('Y-m-d H:i:s'); ?> -->
        <?php
        $output = ob_get_clean();
        
        error_log('SAM Newsletter: Block HTML generated, length: ' . strlen($output));
        
        return $output;
    }
    
    // ===== AJAX HANDLERS =====
    
    public function handle_subscription() {
        error_log('SAM Newsletter: handle_subscription called');
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['sam_newsletter_nonce'], 'sam_newsletter_frontend')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }
        
        // Get and validate input
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        
        $errors = [];
        
        // Validate name
        if (empty($name)) {
            $errors['name'] = 'Name is required';
        } elseif (strlen($name) < 2) {
            $errors['name'] = 'Name must be at least 2 characters';
        }
        
        // Validate email
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!is_email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        // Return validation errors
        if (!empty($errors)) {
            wp_send_json_error([
                'message' => 'Please correct the errors below',
                'errors' => $errors
            ]);
        }
        
        // Check if email already exists
        global $wpdb;
        $table_name = $wpdb->prefix . SAM_NEWSLETTER_TABLE_NAME;
        
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table_name} WHERE email = %s",
            $email
        ));
        
        if ($existing > 0) {
            wp_send_json_error([
                'message' => 'This email is already subscribed to our newsletter'
            ]);
        }
        
        // Add subscriber to database
        $result = $wpdb->insert(
            $table_name,
            [
                'name' => $name,
                'email' => $email,
                'created_at' => current_time('mysql')
            ],
            ['%s', '%s', '%s']
        );
        
        if ($result !== false) {
            // Success - trigger action hook for integrations
            do_action('sam_newsletter_new_subscriber', $wpdb->insert_id, $name, $email);
            
            wp_send_json_success([
                'message' => 'Thank you for subscribing! You will receive a confirmation email shortly.'
            ]);
        } else {
            wp_send_json_error([
                'message' => 'Failed to save subscription. Please try again.'
            ]);
        }
    }
}

// Initialize the plugin
new SAM_Newsletter_Plugin();