<?php // $file = /home/wushkagm/public_html/wp-content/plugins/fs-grid/modules/element/grid_item/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file),
  'name' => 'fs_grid_item',
  'title' => 'Item',
  'width' => 500,
  'fragment' => true,
  'defaults' => [
    'show_custom_settings' => 'all',
    'show_custom_fields' => 'fieldset_1'
  ],
  'placeholder' => [
    'props' => [
      'title' => 'Title',
      'meta' => '',
      'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
      'image' => '',
      'icon' => ''
    ]
  ],
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file),
    'content' => $filter->apply('path', './templates/content.php', $file)
  ],
  'fields' => [
    'show_custom_settings' => [
      'label' => 'Show',
      'description' => '',
      'type' => 'select',
      'options' => [
        'All Enabled' => 'all',
        'Nested Grids Disabler' => 'grids_disabler',
        'Nested Grids Settings' => 'grids_main',
        'Nested Grids Columns' => 'grids_columns',
        'Nested Grids Sliders' => 'grids_sliders',
        'Nested Grids Images' => 'grids_images',
        'Nested Grids Panels' => 'grids_panels',
        'Field Sets Targets' => 'fieldsets_targets',
        'Field Sets Mixed Width' => 'fieldsets_mixed_width',
        'Item Mixed Width' => 'item_mixed_width',
        'Item Panel' => 'item_panel'
      ],
      'source' => false
    ],
    'show_custom_fields' => [
      'label' => 'Show',
      'description' => '',
      'default' => 'fieldset_1',
      'type' => 'select',
      'options' => [
        'Field Set #1' => 'fieldset_1',
        'Field Set #2' => 'fieldset_2',
        'Field Set #3' => 'fieldset_3',
        'Field Set #4' => 'fieldset_4',
        'Field Set #5' => 'fieldset_5',
        'Field Set #6' => 'fieldset_6',
        'Field Set #7' => 'fieldset_7',
        'Field Set #8' => 'fieldset_8',
        'Field Set #9' => 'fieldset_9',
        'Field Set #10' => 'fieldset_10',
        'Field Set #11' => 'fieldset_11',
        'Field Set #12' => 'fieldset_12',
        'Field Set #13' => 'fieldset_13',
        'Field Set #14' => 'fieldset_14',
        'Field Set #15' => 'fieldset_15',
        'Field Set #16' => 'fieldset_16',
        'Field Set #17' => 'fieldset_17',
        'Field Set #18' => 'fieldset_18',
        'Field Set #19' => 'fieldset_19',
        'Field Set #20' => 'fieldset_20'
      ],
      'source' => false
    ],
    'sublayout' => [
      'label' => 'Sublayouts',
      'type' => 'content-items',
      'item' => 'fragment',
      'title' => 'name'
    ],
    'title' => [
      'label' => 'Title',
      'source' => true
    ],
    'meta' => [
      'label' => 'Meta',
      'source' => true
    ],
    'content' => [
      'label' => 'Content',
      'type' => 'editor',
      'divider' => true,
      'source' => true
    ],
    'image' => [
      'label' => 'Image',
      'type' => 'image',
      'source' => true,
      'altRef' => '%name%_alt',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_image\']'
    ],
    '_fs_focal_point_panel' => [
      'type' => 'button-panel',
      'description' => '<code>IMPORTANT:</code> Requires YOOtheme Pro 4.x <br />Set a focal point to adjust the image focus when cropping.',
      'panel' => 'fs_focal_point_settings',
      'text' => 'Image Focal Point',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_image\'] && image'
    ],
    'image_alt' => [
      'label' => 'Image Alt',
      'description' => '<code>SEO Optimization:</code> Alt text is applied to image tags to provide a text alternative for search engines.',
      'attrs' => [
        'placeholder' => 'Describe your image here'
      ],
      'source' => true,
      'enable' => 'image',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_image\'] && image'
    ],
    'image_title' => [
      'label' => 'Image Title',
      'description' => '<code>LIGHBOX:</code> Image title will be used as aria-label.<br /><code>W3C Optimization:</code> Image title text is an attribute used to provide additional information about the image.',
      'attrs' => [
        'placeholder' => 'Describe your image here'
      ],
      'source' => true,
      'enable' => 'image',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_image\'] && image'
    ],
    'icon' => [
      'label' => 'Icon',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!image',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_image\'] && !image'
    ],
    'image_attrs_tag' => [
      'label' => 'Image Attributes',
      'type' => 'editor',
      'description' => 'Define one or more attributes for the Image element. Separate attribute name and value by <code>=</code> character. One attribute per line. Examples: <code>itemprop=&quot;image&quot;</code> <code>decoding=&quot;async&quot;</code>',
      'editor' => 'code',
      'divider' => true,
      'source' => true,
      'enable' => 'image',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_image\'] && image'
    ],
    'link_panel_custom' => [
      'label' => 'Panel Link',
      'type' => 'link',
      'description' => 'You can use two different links: one for the panel, and a second for the image, title and button. Also a lighbox has a force image mode.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_custom_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'link_panel_custom && !link_panel_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_advanced' => [
      'type' => 'checkbox',
      'text' => 'Advanced link settings',
      'enable' => 'link_panel_custom',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_nofollow' => [
      'type' => 'checkbox',
      'label' => 'Nofollow',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link_panel_custom && link_panel_advanced && !link_panel_toggle',
      'show' => 'link_panel_custom && link_panel_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_sponsored' => [
      'type' => 'checkbox',
      'label' => 'Sponsored',
      'description' => 'Mark links that are advertisements or paid placements (commonly called paid links) with the sponsored value.',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link_panel_custom && link_panel_advanced && !link_panel_toggle',
      'show' => 'link_panel_custom && link_panel_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_ugc' => [
      'type' => 'checkbox',
      'label' => 'UGC (User Generated Content)',
      'description' => 'We recommend marking user-generated content (User Generated Content) links, such as comments and forum posts, with the ugc value.',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link_panel_custom && link_panel_advanced && !link_panel_toggle',
      'show' => 'link_panel_custom && link_panel_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_noopener' => [
      'type' => 'checkbox',
      'label' => 'Noopener',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link_panel_custom && link_panel_advanced && !link_panel_toggle',
      'show' => 'link_panel_custom && link_panel_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_noreferrer' => [
      'type' => 'checkbox',
      'label' => 'Noreferrer',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link_panel_custom && link_panel_advanced && !link_panel_toggle',
      'show' => 'link_panel_custom && link_panel_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_prefetch' => [
      'type' => 'checkbox',
      'label' => 'Prefetch',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link_panel_custom && link_panel_advanced',
      'show' => 'link_panel_custom && link_panel_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_title' => [
      'label' => 'Panel Link Title',
      'description' => '<code>SEO Optimization:</code> Set a panel link title attribute for a link tooltips support.',
      'attrs' => [
        'placeholder' => 'Description for the panel link'
      ],
      'source' => true,
      'enable' => 'link_panel_custom && link_panel_advanced',
      'show' => 'link_panel_custom && link_panel_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link_panel_custom_aria_label' => [
      'label' => 'Panel Link Aria Label',
      'divider' => true,
      'description' => '<code>WCAG Accessibility:</code> The aria-label attribute provides a way to place a descriptive text label on an object, such as a link, when there are no elements visible on the page that describe the object.',
      'attrs' => [
        'placeholder' => 'Descriptive text label for the custom panel link'
      ],
      'source' => true,
      'enable' => 'link_panel_custom && link_panel_advanced',
      'show' => 'link_panel_custom && link_panel_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
    ],
    'link' => [
      'label' => 'Link',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'enable' => '!link_item_toggle || !link_item_toggle_modal_integration',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'link && !link_woo && !link_item_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'show' => '!link_woo && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_toggle_modal_integration' => [
      'type' => 'checkbox',
      'text' => 'Connect the sublayout modal automatically',
      'enable' => '!link_woo',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\'] && link_item_toggle && this.builder.parent(this.node)[\'props\'][\'show_sublayout\'] && this.builder.parent(this.node)[\'props\'][\'sublayout_mode\'] == \'modal\' && this.builder.parent(this.node)[\'props\'][\'sublayout_modal_wrap\'] == \'all\''
    ],
    'link_advanced' => [
      'type' => 'checkbox',
      'text' => 'Advanced link settings',
      'enable' => 'link',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_nofollow' => [
      'type' => 'checkbox',
      'label' => 'Nofollow',
      'description' => 'Will be added automatically when using WooCommerce add to cart link.',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link && link_advanced && !link_woo && !link_item_toggle',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_sponsored' => [
      'type' => 'checkbox',
      'label' => 'Sponsored',
      'description' => 'Mark links that are advertisements or paid placements (commonly called paid links) with the sponsored value.',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link && link_advanced && !link_woo && !link_item_toggle',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_ugc' => [
      'type' => 'checkbox',
      'label' => 'UGC (User Generated Content)',
      'description' => 'We recommend marking user-generated content (User Generated Content) links, such as comments and forum posts, with the ugc value.',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link && link_advanced && !link_woo && !link_item_toggle',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_noopener' => [
      'type' => 'checkbox',
      'label' => 'Noopener',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link && link_advanced && !link_item_toggle',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_noreferrer' => [
      'type' => 'checkbox',
      'label' => 'Noreferrer',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link && link_advanced && !link_item_toggle',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_item_prefetch' => [
      'type' => 'checkbox',
      'label' => 'Prefetch',
      'text' => 'Enable',
      'source' => true,
      'enable' => 'link && link_advanced',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_text' => [
      'label' => 'Link Text',
      'description' => 'Set a different link text for this item.',
      'attrs' => [
        'placeholder' => 'Read More'
      ],
      'source' => true,
      'enable' => 'link',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\'] && link'
    ],
    'link_aria_label' => [
      'label' => 'Link Aria Label',
      'description' => '<code>WCAG Accessibility:</code> The aria-label attribute provides a way to place a descriptive text label on an object, such as a link, when there are no elements visible on the page that describe the object.',
      'attrs' => [
        'placeholder' => 'Descriptive text label for the link'
      ],
      'source' => true,
      'enable' => 'link && link_advanced',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_title' => [
      'label' => 'Link Title',
      'description' => '<code>SEO Optimization:</code> Set a link title attribute for a link tooltips support.',
      'attrs' => [
        'placeholder' => 'Description for the link'
      ],
      'source' => true,
      'enable' => 'link && link_advanced',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_modal_id' => [
      'label' => 'Modal ID',
      'description' => '<code>Sublayouts Modal Connect</code> - The modal ID should be unique for each item. If you are using dynamic content, please map the article <code>ID</code> here. Then, click on the pencil icon and set the prefix <code>modal-</code> by using before the modifier. Similarly, for the item link field, set the prefix <code>#modal-</code> to connect the item link with your modals. It is required for the IDs to start with a letter.',
      'source' => true,
      'attrs' => [
        'placeholder' => 'modal-18'
      ],
      'enable' => '!link_woo',
      'show' => '((this.builder.parent(this.node)[\'props\'][\'show_sublayout\'] && this.builder.parent(this.node)[\'props\'][\'show_link\'] && link_item_toggle) || (this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && (custom_link_1_toggle || custom_link_2_toggle || custom_link_3_toggle || custom_link_4_toggle || custom_link_5_toggle || custom_link_6_toggle || custom_link_7_toggle || custom_link_8_toggle || custom_link_9_toggle || custom_link_10_toggle || custom_link_11_toggle || custom_link_12_toggle || custom_link_13_toggle || custom_link_14_toggle || custom_link_15_toggle || custom_link_16_toggle || custom_link_17_toggle || custom_link_18_toggle || custom_link_19_toggle || custom_link_20_toggle))) && ((this.builder.parent(this.node)[\'props\'][\'sublayout_mode\'] == \'modal\' && ((this.builder.parent(this.node)[\'props\'][\'sublayout_modal_wrap\'] == \'all\' && !link_item_toggle_modal_integration) || this.builder.parent(this.node)[\'props\'][\'sublayout_modal_wrap\'] != \'all\')) || this.builder.parent(this.node)[\'props\'][\'sublayout_mode\'] == \'mixed\')'
    ],
    'link_modal_header_text' => [
      'label' => 'Modal Header Text',
      'description' => '<code>Sublayouts Modal Header Text</code> can be used only when wrapping all sublayouts into one modal window. Header display should be enabled in the Sublayouts/Modal setting section.',
      'source' => true,
      'attrs' => [
        'placeholder' => 'Modal Header Text'
      ],
      'enable' => '!link_woo',
      'show' => '((this.builder.parent(this.node)[\'props\'][\'show_sublayout\'] && this.builder.parent(this.node)[\'props\'][\'show_link\'] && link_item_toggle) || (this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && (custom_link_1_toggle || custom_link_2_toggle || custom_link_3_toggle || custom_link_4_toggle || custom_link_5_toggle || custom_link_6_toggle || custom_link_7_toggle || custom_link_8_toggle || custom_link_9_toggle || custom_link_10_toggle || custom_link_11_toggle || custom_link_12_toggle || custom_link_13_toggle || custom_link_14_toggle || custom_link_15_toggle || custom_link_16_toggle || custom_link_17_toggle || custom_link_18_toggle || custom_link_19_toggle || custom_link_20_toggle))) && (this.builder.parent(this.node)[\'props\'][\'sublayout_mode\'] == \'modal\' && this.builder.parent(this.node)[\'props\'][\'sublayout_modal_wrap\'] == \'all\' && this.builder.parent(this.node)[\'props\'][\'sublayout_modal_header\'])'
    ],
    'link_class' => [
      'label' => 'Link Class',
      'description' => 'Set a custom classes to a link.',
      'source' => true,
      'enable' => 'link && link_advanced',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'link_attrs_tag' => [
      'divider' => true,
      'label' => 'Link Attributes',
      'type' => 'editor',
      'editor' => 'code',
      'description' => 'Define one or more attributes for the link element. Separate attribute name and value by <code>=</code> character. One attribute per line. Examples: <code>uk-toggle</code> <code>uk-modal</code> <code>data-type=iframe</code> <code>download=&quot;&quot;</code>  <code>onclick=&quot;window.print();&quot;</code>. For multiple rel attributes use <code>rel=&quot;nofollow noreferrer&quot;</code>',
      'source' => true,
      'enable' => 'link && link_advanced',
      'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
    ],
    'tags' => [
      'label' => 'Tags',
      'divider' => true,
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.<br /><code>IMPORTANT:</code> Filter will be disabled if the tags field is empty.',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'filter\']'
    ],
    'rating' => [
      'label' => 'Rating',
      'description' => 'Enter rating value from 0 to 5. You can also enter not integer values like: <code>2.5</code>, <code>3.5</code>, <code>4.5</code>',
      'attrs' => [
        'placeholder' => '0'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_rating\']'
    ],
    'custom_1_fields_disabled' => [
      'label' => 'Filed Set #1 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_1\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_1\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_1\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_1\'])'
    ],
    'custom_meta_1' => [
      'label' => 'Meta #1',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_1\']'
    ],
    'custom_text_1' => [
      'label' => 'Text #1',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_1\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_1\'])'
    ],
    'custom_image_1' => [
      'label' => 'Image #1',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_1_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_1\']'
    ],
    'custom_image_1_alt' => [
      'label' => 'Image Alt #1',
      'source' => true,
      'enable' => 'custom_image_1',
      'show' => 'custom_image_1 && !custom_image_1_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_1\']'
    ],
    'custom_image_1_icon' => [
      'label' => 'Icon #1',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_1',
      'show' => '!custom_image_1 && this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_1\']'
    ],
    'custom_link_1' => [
      'label' => 'Link #1',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_1\']'
    ],
    'custom_link_1_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_1 && !custom_link_1_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_1\']'
    ],
    'custom_link_1_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_1',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_1\']'
    ],
    'custom_2_fields_disabled' => [
      'label' => 'Filed Set #2 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_2\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_2\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_2\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_2\'])'
    ],
    'custom_meta_2' => [
      'label' => 'Meta #2',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_2\']'
    ],
    'custom_text_2' => [
      'label' => 'Text #2',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_2\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_2\'])'
    ],
    'custom_image_2' => [
      'label' => 'Image #2',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_2_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_2\']'
    ],
    'custom_image_2_alt' => [
      'label' => 'Image Alt #2',
      'source' => true,
      'enable' => 'custom_image_2',
      'show' => 'custom_image_2 && !custom_image_2_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_2\']'
    ],
    'custom_image_2_icon' => [
      'label' => 'Icon #2',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_2',
      'show' => '!custom_image_2 && this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_2\']'
    ],
    'custom_link_2' => [
      'label' => 'Link #2',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_2\']'
    ],
    'custom_link_2_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_2 && !custom_link_2_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_2\']'
    ],
    'custom_link_2_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_2',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_2\']'
    ],
    'custom_3_fields_disabled' => [
      'label' => 'Filed Set #3 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_3\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_3\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_3\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_3\'])'
    ],
    'custom_meta_3' => [
      'label' => 'Meta #3',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_3\']'
    ],
    'custom_text_3' => [
      'label' => 'Text #3',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_3\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_3\'])'
    ],
    'custom_image_3' => [
      'label' => 'Image #3',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_3_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_3\']'
    ],
    'custom_image_3_alt' => [
      'label' => 'Image Alt #3',
      'source' => true,
      'enable' => 'custom_image_3',
      'show' => 'custom_image_3 && !custom_image_3_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_3\']'
    ],
    'custom_image_3_icon' => [
      'label' => 'Icon #3',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_3',
      'show' => '!custom_image_3 && this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_3\']'
    ],
    'custom_link_3' => [
      'label' => 'Link #3',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_3\']'
    ],
    'custom_link_3_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_3 && !custom_link_3_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_3\']'
    ],
    'custom_link_3_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_3',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_3\']'
    ],
    'custom_4_fields_disabled' => [
      'label' => 'Filed Set #4 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_4\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_4\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_4\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_4\'])'
    ],
    'custom_meta_4' => [
      'label' => 'Meta #4',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_4\']'
    ],
    'custom_text_4' => [
      'label' => 'Text #4',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_4\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_4\'])'
    ],
    'custom_image_4' => [
      'label' => 'Image #4',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_4_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_4\']'
    ],
    'custom_image_4_alt' => [
      'label' => 'Image Alt #4',
      'source' => true,
      'enable' => 'custom_image_4',
      'show' => 'custom_image_4 && !custom_image_4_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_4\']'
    ],
    'custom_image_4_icon' => [
      'label' => 'Icon #4',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_4',
      'show' => '!custom_image_4 && this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_4\']'
    ],
    'custom_link_4' => [
      'label' => 'Link #4',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_4\']'
    ],
    'custom_link_4_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_4 && !custom_link_4_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_4\']'
    ],
    'custom_link_4_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_4',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_4\']'
    ],
    'custom_5_fields_disabled' => [
      'label' => 'Filed Set #5 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_5\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_5\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_5\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_5\'])'
    ],
    'custom_meta_5' => [
      'label' => 'Meta #5',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_5\']'
    ],
    'custom_text_5' => [
      'label' => 'Text #5',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_5\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_5\'])'
    ],
    'custom_image_5' => [
      'label' => 'Image #5',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_5_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_5\']'
    ],
    'custom_image_5_alt' => [
      'label' => 'Image Alt #5',
      'source' => true,
      'enable' => 'custom_image_5',
      'show' => 'custom_image_5 && !custom_image_5_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_5\']'
    ],
    'custom_image_5_icon' => [
      'label' => 'Icon #5',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_5',
      'show' => '!custom_image_5 && this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_5\']'
    ],
    'custom_link_5' => [
      'label' => 'Link #5',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_5\']'
    ],
    'custom_link_5_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_5 && !custom_link_5_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_5\']'
    ],
    'custom_link_5_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_5',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_5\']'
    ],
    'custom_6_fields_disabled' => [
      'label' => 'Filed Set #6 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_6\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_6\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_6\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_6\'])'
    ],
    'custom_meta_6' => [
      'label' => 'Meta #6',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_6\']'
    ],
    'custom_text_6' => [
      'label' => 'Text #6',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_6\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_6\'])'
    ],
    'custom_image_6' => [
      'label' => 'Image #6',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_6_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_6\']'
    ],
    'custom_image_6_alt' => [
      'label' => 'Image Alt #6',
      'source' => true,
      'enable' => 'custom_image_6',
      'show' => 'custom_image_6 && !custom_image_6_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_6\']'
    ],
    'custom_image_6_icon' => [
      'label' => 'Icon #6',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_6',
      'show' => '!custom_image_6 && this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_6\']'
    ],
    'custom_link_6' => [
      'label' => 'Link #6',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_6\']'
    ],
    'custom_link_6_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_6 && !custom_link_6_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_6\']'
    ],
    'custom_link_6_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_6',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_6\']'
    ],
    'custom_7_fields_disabled' => [
      'label' => 'Filed Set #7 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_7\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_7\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_7\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_7\'])'
    ],
    'custom_meta_7' => [
      'label' => 'Meta #7',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_7\']'
    ],
    'custom_text_7' => [
      'label' => 'Text #7',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_7\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_7\'])'
    ],
    'custom_image_7' => [
      'label' => 'Image #7',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_7_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_7\']'
    ],
    'custom_image_7_alt' => [
      'label' => 'Image Alt #7',
      'source' => true,
      'enable' => 'custom_image_7',
      'show' => 'custom_image_7 && !custom_image_7_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_7\']'
    ],
    'custom_image_7_icon' => [
      'label' => 'Icon #7',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_7',
      'show' => '!custom_image_7 && this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_7\']'
    ],
    'custom_link_7' => [
      'label' => 'Link #7',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_7\']'
    ],
    'custom_link_7_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_7 && !custom_link_7_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_7\']'
    ],
    'custom_link_7_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_7',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_7\']'
    ],
    'custom_8_fields_disabled' => [
      'label' => 'Filed Set #8 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_8\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_8\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_8\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_8\'])'
    ],
    'custom_meta_8' => [
      'label' => 'Meta #8',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_8\']'
    ],
    'custom_text_8' => [
      'label' => 'Text #8',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_8\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_8\'])'
    ],
    'custom_image_8' => [
      'label' => 'Image #8',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_8_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_8\']'
    ],
    'custom_image_8_alt' => [
      'label' => 'Image Alt #8',
      'source' => true,
      'enable' => 'custom_image_8',
      'show' => 'custom_image_8 && !custom_image_8_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_8\']'
    ],
    'custom_image_8_icon' => [
      'label' => 'Icon #8',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_8',
      'show' => '!custom_image_8 && this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_8\']'
    ],
    'custom_link_8' => [
      'label' => 'Link #8',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_8\']'
    ],
    'custom_link_8_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_8 && !custom_link_8_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_8\']'
    ],
    'custom_link_8_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_8',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_8\']'
    ],
    'custom_9_fields_disabled' => [
      'label' => 'Filed Set #9 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_9\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_9\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_9\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_9\'])'
    ],
    'custom_meta_9' => [
      'label' => 'Meta #9',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_9\']'
    ],
    'custom_text_9' => [
      'label' => 'Text #9',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_9\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_9\'])'
    ],
    'custom_image_9' => [
      'label' => 'Image #9',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_9_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_9\']'
    ],
    'custom_image_9_alt' => [
      'label' => 'Image Alt #9',
      'source' => true,
      'enable' => 'custom_image_9',
      'show' => 'custom_image_9 && !custom_image_9_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_9\']'
    ],
    'custom_image_9_icon' => [
      'label' => 'Icon #9',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_9',
      'show' => '!custom_image_9 && this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_9\']'
    ],
    'custom_link_9' => [
      'label' => 'Link #9',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_9\']'
    ],
    'custom_link_9_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_9 && !custom_link_9_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_9\']'
    ],
    'custom_link_9_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_9',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_9\']'
    ],
    'custom_10_fields_disabled' => [
      'label' => 'Filed Set #10 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_10\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_10\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_10\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_10\'])'
    ],
    'custom_meta_10' => [
      'label' => 'Meta #10',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_10\']'
    ],
    'custom_text_10' => [
      'label' => 'Text #10',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_10\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_10\'])'
    ],
    'custom_image_10' => [
      'label' => 'Image #10',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_10_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_10\']'
    ],
    'custom_image_10_alt' => [
      'label' => 'Image Alt #10',
      'source' => true,
      'enable' => 'custom_image_10',
      'show' => 'custom_image_10 && !custom_image_10_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_10\']'
    ],
    'custom_image_10_icon' => [
      'label' => 'Icon #10',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_10',
      'show' => '!custom_image_10 && this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_10\']'
    ],
    'custom_link_10' => [
      'label' => 'Link #10',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_10\']'
    ],
    'custom_link_10_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_10 && !custom_link_10_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_10\']'
    ],
    'custom_link_10_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_10',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_10\']'
    ],
    'custom_11_fields_disabled' => [
      'label' => 'Filed Set #11 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_11\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_11\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_11\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_11\'])'
    ],
    'custom_meta_11' => [
      'label' => 'Meta #11',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_11\']'
    ],
    'custom_text_11' => [
      'label' => 'Text #11',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_11\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_11\'])'
    ],
    'custom_image_11' => [
      'label' => 'Image #11',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_11_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_11\']'
    ],
    'custom_image_11_alt' => [
      'label' => 'Image Alt #11',
      'source' => true,
      'enable' => 'custom_image_11',
      'show' => 'custom_image_11 && !custom_image_11_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_11\']'
    ],
    'custom_image_11_icon' => [
      'label' => 'Icon #11',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_11',
      'show' => '!custom_image_11 && this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_11\']'
    ],
    'custom_link_11' => [
      'label' => 'Link #11',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_11\']'
    ],
    'custom_link_11_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_11 && !custom_link_11_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_11\']'
    ],
    'custom_link_11_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_11',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_11\']'
    ],
    'custom_12_fields_disabled' => [
      'label' => 'Filed Set #12 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_12\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_12\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_12\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_12\'])'
    ],
    'custom_meta_12' => [
      'label' => 'Meta #12',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_12\']'
    ],
    'custom_text_12' => [
      'label' => 'Text #12',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_12\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_12\'])'
    ],
    'custom_image_12' => [
      'label' => 'Image #12',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_12_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_12\']'
    ],
    'custom_image_12_alt' => [
      'label' => 'Image Alt #12',
      'source' => true,
      'enable' => 'custom_image_12',
      'show' => 'custom_image_12 && !custom_image_12_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_12\']'
    ],
    'custom_image_12_icon' => [
      'label' => 'Icon #12',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_12',
      'show' => '!custom_image_12 && this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_12\']'
    ],
    'custom_link_12' => [
      'label' => 'Link #12',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_12\']'
    ],
    'custom_link_12_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_12 && !custom_link_12_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_12\']'
    ],
    'custom_link_12_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_12',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_12\']'
    ],
    'custom_13_fields_disabled' => [
      'label' => 'Filed Set #13 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_13\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_13\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_13\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_13\'])'
    ],
    'custom_meta_13' => [
      'label' => 'Meta #13',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_13\']'
    ],
    'custom_text_13' => [
      'label' => 'Text #13',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_13\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_13\'])'
    ],
    'custom_image_13' => [
      'label' => 'Image #13',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_13_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_13\']'
    ],
    'custom_image_13_alt' => [
      'label' => 'Image Alt #13',
      'source' => true,
      'enable' => 'custom_image_13',
      'show' => 'custom_image_13 && !custom_image_13_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_13\']'
    ],
    'custom_image_13_icon' => [
      'label' => 'Icon #13',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_13',
      'show' => '!custom_image_13 && this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_13\']'
    ],
    'custom_link_13' => [
      'label' => 'Link #13',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_13\']'
    ],
    'custom_link_13_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_13 && !custom_link_13_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_13\']'
    ],
    'custom_link_13_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_13',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_13\']'
    ],
    'custom_14_fields_disabled' => [
      'label' => 'Filed Set #14 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_14\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_14\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_14\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_14\'])'
    ],
    'custom_meta_14' => [
      'label' => 'Meta #14',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_14\']'
    ],
    'custom_text_14' => [
      'label' => 'Text #14',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_14\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_14\'])'
    ],
    'custom_image_14' => [
      'label' => 'Image #14',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_14_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_14\']'
    ],
    'custom_image_14_alt' => [
      'label' => 'Image Alt #14',
      'source' => true,
      'enable' => 'custom_image_14',
      'show' => 'custom_image_14 && !custom_image_14_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_14\']'
    ],
    'custom_image_14_icon' => [
      'label' => 'Icon #14',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_14',
      'show' => '!custom_image_14 && this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_14\']'
    ],
    'custom_link_14' => [
      'label' => 'Link #14',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_14\']'
    ],
    'custom_link_14_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_14 && !custom_link_14_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_14\']'
    ],
    'custom_link_14_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_14',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_14\']'
    ],
    'custom_15_fields_disabled' => [
      'label' => 'Filed Set #15 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_15\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_15\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_15\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_15\'])'
    ],
    'custom_meta_15' => [
      'label' => 'Meta #15',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_15\']'
    ],
    'custom_text_15' => [
      'label' => 'Text #15',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_15\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_15\'])'
    ],
    'custom_image_15' => [
      'label' => 'Image #15',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_15_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_15\']'
    ],
    'custom_image_15_alt' => [
      'label' => 'Image Alt #15',
      'source' => true,
      'enable' => 'custom_image_15',
      'show' => 'custom_image_15 && !custom_image_15_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_15\']'
    ],
    'custom_image_15_icon' => [
      'label' => 'Icon #15',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_15',
      'show' => '!custom_image_15 && this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_15\']'
    ],
    'custom_link_15' => [
      'label' => 'Link #15',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_15\']'
    ],
    'custom_link_15_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_15 && !custom_link_15_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_15\']'
    ],
    'custom_link_15_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_15',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_15\']'
    ],
    'custom_16_fields_disabled' => [
      'label' => 'Filed Set #16 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_16\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_16\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_16\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_16\'])'
    ],
    'custom_meta_16' => [
      'label' => 'Meta #16',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_16\']'
    ],
    'custom_text_16' => [
      'label' => 'Text #16',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_16\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_16\'])'
    ],
    'custom_image_16' => [
      'label' => 'Image #16',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_16_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_16\']'
    ],
    'custom_image_16_alt' => [
      'label' => 'Image Alt #16',
      'source' => true,
      'enable' => 'custom_image_16',
      'show' => 'custom_image_16 && !custom_image_16_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_16\']'
    ],
    'custom_image_16_icon' => [
      'label' => 'Icon #16',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_16',
      'show' => '!custom_image_16 && this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_16\']'
    ],
    'custom_link_16' => [
      'label' => 'Link #16',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_16\']'
    ],
    'custom_link_16_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_16 && !custom_link_16_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_16\']'
    ],
    'custom_link_16_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_16',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_16\']'
    ],
    'custom_17_fields_disabled' => [
      'label' => 'Filed Set #17 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_17\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_17\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_17\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_17\'])'
    ],
    'custom_meta_17' => [
      'label' => 'Meta #17',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_17\']'
    ],
    'custom_text_17' => [
      'label' => 'Text #17',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_17\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_17\'])'
    ],
    'custom_image_17' => [
      'label' => 'Image #17',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_17_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_17\']'
    ],
    'custom_image_17_alt' => [
      'label' => 'Image Alt #17',
      'source' => true,
      'enable' => 'custom_image_17',
      'show' => 'custom_image_17 && !custom_image_17_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_17\']'
    ],
    'custom_image_17_icon' => [
      'label' => 'Icon #17',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_17',
      'show' => '!custom_image_17 && this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_17\']'
    ],
    'custom_link_17' => [
      'label' => 'Link #17',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_17\']'
    ],
    'custom_link_17_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_17 && !custom_link_17_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_17\']'
    ],
    'custom_link_17_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_17',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_17\']'
    ],
    'custom_18_fields_disabled' => [
      'label' => 'Filed Set #18 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_18\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_18\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_18\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_18\'])'
    ],
    'custom_meta_18' => [
      'label' => 'Meta #18',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_18\']'
    ],
    'custom_text_18' => [
      'label' => 'Text #18',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_18\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_18\'])'
    ],
    'custom_image_18' => [
      'label' => 'Image #18',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_18_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_18\']'
    ],
    'custom_image_18_alt' => [
      'label' => 'Image Alt #18',
      'source' => true,
      'enable' => 'custom_image_18',
      'show' => 'custom_image_18 && !custom_image_18_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_18\']'
    ],
    'custom_image_18_icon' => [
      'label' => 'Icon #18',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_18',
      'show' => '!custom_image_18 && this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_18\']'
    ],
    'custom_link_18' => [
      'label' => 'Link #18',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_18\']'
    ],
    'custom_link_18_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_18 && !custom_link_18_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_18\']'
    ],
    'custom_link_18_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_18',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_18\']'
    ],
    'custom_19_fields_disabled' => [
      'label' => 'Filed Set #19 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_19\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_19\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_19\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_19\'])'
    ],
    'custom_meta_19' => [
      'label' => 'Meta #19',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_19\']'
    ],
    'custom_text_19' => [
      'label' => 'Text #19',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_19\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_19\'])'
    ],
    'custom_image_19' => [
      'label' => 'Image #19',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_19_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_19\']'
    ],
    'custom_image_19_alt' => [
      'label' => 'Image Alt #19',
      'source' => true,
      'enable' => 'custom_image_19',
      'show' => 'custom_image_19 && !custom_image_19_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_19\']'
    ],
    'custom_image_19_icon' => [
      'label' => 'Icon #19',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_19',
      'show' => '!custom_image_19 && this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_19\']'
    ],
    'custom_link_19' => [
      'label' => 'Link #19',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_19\']'
    ],
    'custom_link_19_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_19 && !custom_link_19_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_19\']'
    ],
    'custom_link_19_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_19',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_19\']'
    ],
    'custom_20_fields_disabled' => [
      'label' => 'Filed Set #20 Fields Are Not Enabled to Display',
      'description' => '<code>NOTE:</code>You can enable custom fields under the element content tab.<br /><code>CUSTOM FILTER GROUPS:</code>Enable related filter group in the filter settings, to display the text input field that can be used to enter tags without displaying the content on the website.',
      'type' => 'info',
      'enable' => false,
      'show' => '!this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_meta_20\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_text_20\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_image_20\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_link_20\'])'
    ],
    'custom_meta_20' => [
      'label' => 'Meta #20',
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_meta_20\']'
    ],
    'custom_text_20' => [
      'label' => 'Text #20',
      'type' => 'editor',
      'description' => 'Enter a comma-separated list of tags, for example: <code>blue, white, black</code>.',
      'source' => true,
      'show' => '(this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_text_20\']) || (this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'filter\'] && this.builder.parent(this.node)[\'props\'][\'filter_groups\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_filter_20\'])'
    ],
    'custom_image_20' => [
      'label' => 'Image #20',
      'type' => 'image',
      'source' => true,
      'enable' => '!custom_image_20_icon',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_20\']'
    ],
    'custom_image_20_alt' => [
      'label' => 'Image Alt #20',
      'source' => true,
      'enable' => 'custom_image_20',
      'show' => 'custom_image_20 && !custom_image_20_icon && this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_20\']'
    ],
    'custom_image_20_icon' => [
      'label' => 'Icon #20',
      'type' => 'icon',
      'description' => 'Pick an icon from the icon library.',
      'source' => true,
      'enable' => '!custom_image_20',
      'show' => '!custom_image_20 && this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_image_20\']'
    ],
    'custom_link_20' => [
      'label' => 'Link #20',
      'type' => 'link',
      'description' => 'Enter or pick a link, an image or a video file.',
      'attrs' => [
        'placeholder' => 'https://'
      ],
      'source' => true,
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_20\']'
    ],
    'custom_link_20_item_target' => [
      'type' => 'checkbox',
      'text' => 'Open the link in a new window',
      'enable' => 'custom_link_20 && !custom_link_20_toggle',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_20\']'
    ],
    'custom_link_20_toggle' => [
      'type' => 'checkbox',
      'text' => 'Interactive toggle (also used for sublayouts modal connect)',
      'enable' => 'custom_link_20',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_link_20\']'
    ],
    'item_disable_custom_grid_1' => [
      'type' => 'checkbox',
      'label' => 'Disable Grid #1',
      'text' => 'Yes',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_1_custom\']'
    ],
    'item_disable_custom_grid_2' => [
      'type' => 'checkbox',
      'label' => 'Disable Grid #2',
      'text' => 'Yes',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_2_custom\']'
    ],
    'item_disable_custom_grid_3' => [
      'type' => 'checkbox',
      'label' => 'Disable Grid #3',
      'text' => 'Yes',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_3_custom\']'
    ],
    'item_disable_custom_grid_4' => [
      'type' => 'checkbox',
      'label' => 'Disable Grid #4',
      'text' => 'Yes',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_4_custom\']'
    ],
    'item_disable_custom_grid_5' => [
      'type' => 'checkbox',
      'label' => 'Disable Grid #5',
      'text' => 'Yes',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_5_custom\']'
    ],
    'item_disable_custom_grid_6' => [
      'description' => 'With this options set, you can add additional grids for custom fields.',
      'type' => 'checkbox',
      'label' => 'Disable Grid #6',
      'text' => 'Yes',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_6_custom\']'
    ],
    'item_width_default' => $config->get('fs_grid.item_width_default'),
    'item_width_small' => $config->get('fs_grid.item_width_small'),
    'item_width_medium' => $config->get('fs_grid.item_width_medium'),
    'item_width_large' => $config->get('fs_grid.item_width_large'),
    'item_width_xlarge' => $config->get('fs_grid.item_width_xlarge'),
    'grid_1_custom_item_position' => $config->get('fs_grid.grid_position_item'),
    'grid_1_custom_item_column_gap' => $config->get('fs_grid.grid_column_gap_item'),
    'grid_1_custom_item_row_gap' => $config->get('fs_grid.grid_row_gap_item'),
    'grid_1_custom_item_divider_top' => $config->get('fs_grid.grid_divider_top'),
    'grid_1_custom_item_divider' => [
      'label' => 'Grid Dividers',
      'description' => 'Show a divider between grid columns.',
      'type' => 'checkbox',
      'text' => 'Show',
      'enable' => 'grid_1_custom_column_gap != \'collapse\' && grid_1_custom_row_gap != \'collapse\''
    ],
    'grid_1_custom_item_column_align' => [
      'label' => 'Alignment',
      'type' => 'checkbox',
      'text' => 'Center columns',
      'enable' => '!grid_1_custom_slider'
    ],
    'grid_1_custom_item_row_align' => $config->get('fs_grid.grid_row_align'),
    'grid_1_custom_item_grid_match' => $config->get('fs_grid.grid_match'),
    'grid_1_custom_item_text_align' => $config->get('fs_grid.text_align_item'),
    'grid_1_custom_item_text_align_breakpoint' => [
      'label' => 'Text Alignment Breakpoint',
      'default' => 'inherit',
      'description' => 'Define the device width from which the alignment will apply.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'enable' => 'grid_1_custom_item_text_align'
    ],
    'grid_1_custom_item_text_align_fallback' => [
      'label' => 'Text Alignment Fallback',
      'description' => 'Define an alignment fallback for device widths below the breakpoint.',
      'type' => 'select',
      'options' => $config->get('fs_grid.text_align_options_item'),
      'enable' => 'grid_1_custom_item_text_align && grid_1_custom_item_text_align_breakpoint != \'inherit\' && grid_1_custom_item_text_align_breakpoint != \'always\''
    ],
    'grid_1_custom_item_margin' => $config->get('fs_grid.grid_margin_item'),
    'grid_1_custom_item_visibility' => $config->get('fs_grid.visibility_item'),
    'grid_1_custom_item_slider' => [
      'label' => 'Grid #1 Slider',
      'description' => 'Enables nested grid slider.',
      'type' => 'select',
      'options' => [
        'Inherit' => '',
        'Enable' => 'enable',
        'Disable' => 'disable'
      ],
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_1_custom\']'
    ],
    'grid_1_custom_panel_style' => $config->get('fs_grid.panel_style_item'),
    'grid_1_custom_panel_card_offset' => [
      'type' => 'checkbox',
      'text' => 'Add clipping offset',
      'enable' => 'grid_1_custom_panel_style'
    ],
    'grid_1_custom_panel_padding' => $config->get('fs_grid.panel_padding_item'),
    'grid_1_custom_item_default' => $config->get('fs_grid.grid_default_item'),
    'grid_1_custom_item_small' => $config->get('fs_grid.grid_small'),
    'grid_1_custom_item_medium' => $config->get('fs_grid.grid_medium'),
    'grid_1_custom_item_large' => $config->get('fs_grid.grid_large'),
    'grid_1_custom_item_xlarge' => $config->get('fs_grid.grid_xlarge'),
    'grid_2_custom_item_position' => $config->get('fs_grid.grid_position_item'),
    'grid_2_custom_item_column_gap' => $config->get('fs_grid.grid_column_gap_item'),
    'grid_2_custom_item_row_gap' => $config->get('fs_grid.grid_row_gap_item'),
    'grid_2_custom_item_divider_top' => $config->get('fs_grid.grid_divider_top'),
    'grid_2_custom_item_divider' => [
      'label' => 'Grid Dividers',
      'description' => 'Show a divider between grid columns.',
      'type' => 'checkbox',
      'text' => 'Show',
      'enable' => 'grid_2_custom_column_gap != \'collapse\' && grid_1_custom_row_gap != \'collapse\''
    ],
    'grid_2_custom_item_column_align' => [
      'label' => 'Alignment',
      'type' => 'checkbox',
      'text' => 'Center columns',
      'enable' => '!grid_2_custom_slider'
    ],
    'grid_2_custom_item_row_align' => $config->get('fs_grid.grid_row_align'),
    'grid_2_custom_item_grid_match' => $config->get('fs_grid.grid_match'),
    'grid_2_custom_item_text_align' => $config->get('fs_grid.text_align_item'),
    'grid_2_custom_item_text_align_breakpoint' => [
      'label' => 'Text Alignment Breakpoint',
      'default' => 'inherit',
      'description' => 'Define the device width from which the alignment will apply.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'enable' => 'grid_2_custom_item_text_align'
    ],
    'grid_2_custom_item_text_align_fallback' => [
      'label' => 'Text Alignment Fallback',
      'description' => 'Define an alignment fallback for device widths below the breakpoint.',
      'type' => 'select',
      'options' => $config->get('fs_grid.text_align_options_item'),
      'enable' => 'grid_2_custom_item_text_align && grid_2_custom_item_text_align_breakpoint != \'inherit\' && grid_2_custom_item_text_align_breakpoint != \'always\''
    ],
    'grid_2_custom_item_margin' => $config->get('fs_grid.grid_margin_item'),
    'grid_2_custom_item_visibility' => $config->get('fs_grid.visibility_item'),
    'grid_2_custom_item_slider' => [
      'label' => 'Grid #2 Slider',
      'description' => 'Enables nested grid slider.',
      'type' => 'select',
      'options' => [
        'Inherit' => '',
        'Enable' => 'enable',
        'Disable' => 'disable'
      ],
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_2_custom\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\']'
    ],
    'grid_2_custom_panel_style' => $config->get('fs_grid.panel_style_item'),
    'grid_2_custom_panel_card_offset' => [
      'type' => 'checkbox',
      'text' => 'Add clipping offset',
      'enable' => 'grid_2_custom_panel_style'
    ],
    'grid_2_custom_panel_padding' => $config->get('fs_grid.panel_padding_item'),
    'grid_2_custom_item_default' => $config->get('fs_grid.grid_default_item'),
    'grid_2_custom_item_small' => $config->get('fs_grid.grid_small'),
    'grid_2_custom_item_medium' => $config->get('fs_grid.grid_medium'),
    'grid_2_custom_item_large' => $config->get('fs_grid.grid_large'),
    'grid_2_custom_item_xlarge' => $config->get('fs_grid.grid_xlarge'),
    'grid_3_custom_item_position' => $config->get('fs_grid.grid_position_item'),
    'grid_3_custom_item_column_gap' => $config->get('fs_grid.grid_column_gap_item'),
    'grid_3_custom_item_row_gap' => $config->get('fs_grid.grid_row_gap_item'),
    'grid_3_custom_item_divider_top' => $config->get('fs_grid.grid_divider_top'),
    'grid_3_custom_item_divider' => [
      'label' => 'Grid Dividers',
      'description' => 'Show a divider between grid columns.',
      'type' => 'checkbox',
      'text' => 'Show',
      'enable' => 'grid_3_custom_column_gap != \'collapse\' && grid_1_custom_row_gap != \'collapse\''
    ],
    'grid_3_custom_item_column_align' => [
      'label' => 'Alignment',
      'type' => 'checkbox',
      'text' => 'Center columns',
      'enable' => '!grid_3_custom_slider'
    ],
    'grid_3_custom_item_row_align' => $config->get('fs_grid.grid_row_align'),
    'grid_3_custom_item_grid_match' => $config->get('fs_grid.grid_match'),
    'grid_3_custom_item_text_align' => $config->get('fs_grid.text_align_item'),
    'grid_3_custom_item_text_align_breakpoint' => [
      'label' => 'Text Alignment Breakpoint',
      'default' => 'inherit',
      'description' => 'Define the device width from which the alignment will apply.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'enable' => 'grid_3_custom_item_text_align'
    ],
    'grid_3_custom_item_text_align_fallback' => [
      'label' => 'Text Alignment Fallback',
      'description' => 'Define an alignment fallback for device widths below the breakpoint.',
      'type' => 'select',
      'options' => $config->get('fs_grid.text_align_options_item'),
      'enable' => 'grid_3_custom_item_text_align && grid_3_custom_item_text_align_breakpoint != \'inherit\' && grid_3_custom_item_text_align_breakpoint != \'always\''
    ],
    'grid_3_custom_item_margin' => $config->get('fs_grid.grid_margin_item'),
    'grid_3_custom_item_visibility' => $config->get('fs_grid.visibility_item'),
    'grid_3_custom_item_slider' => [
      'label' => 'Grid #3 Slider',
      'description' => 'Enables nested grid slider.',
      'type' => 'select',
      'options' => [
        'Inherit' => '',
        'Enable' => 'enable',
        'Disable' => 'disable'
      ],
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_3_custom\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\']'
    ],
    'grid_3_custom_panel_style' => $config->get('fs_grid.panel_style_item'),
    'grid_3_custom_panel_card_offset' => [
      'type' => 'checkbox',
      'text' => 'Add clipping offset',
      'enable' => 'grid_3_custom_panel_style'
    ],
    'grid_3_custom_panel_padding' => $config->get('fs_grid.panel_padding_item'),
    'grid_3_custom_item_default' => $config->get('fs_grid.grid_default_item'),
    'grid_3_custom_item_small' => $config->get('fs_grid.grid_small'),
    'grid_3_custom_item_medium' => $config->get('fs_grid.grid_medium'),
    'grid_3_custom_item_large' => $config->get('fs_grid.grid_large'),
    'grid_3_custom_item_xlarge' => $config->get('fs_grid.grid_xlarge'),
    'grid_4_custom_item_position' => $config->get('fs_grid.grid_position_item'),
    'grid_4_custom_item_column_gap' => $config->get('fs_grid.grid_column_gap_item'),
    'grid_4_custom_item_row_gap' => $config->get('fs_grid.grid_row_gap_item'),
    'grid_4_custom_item_divider_top' => $config->get('fs_grid.grid_divider_top'),
    'grid_4_custom_item_divider' => [
      'label' => 'Grid Dividers',
      'description' => 'Show a divider between grid columns.',
      'type' => 'checkbox',
      'text' => 'Show',
      'enable' => 'grid_4_custom_column_gap != \'collapse\' && grid_4_custom_row_gap != \'collapse\''
    ],
    'grid_4_custom_item_column_align' => [
      'label' => 'Alignment',
      'type' => 'checkbox',
      'text' => 'Center columns',
      'enable' => '!grid_4_custom_slider'
    ],
    'grid_4_custom_item_row_align' => $config->get('fs_grid.grid_row_align'),
    'grid_4_custom_item_grid_match' => $config->get('fs_grid.grid_match'),
    'grid_4_custom_item_text_align' => $config->get('fs_grid.text_align_item'),
    'grid_4_custom_item_text_align_breakpoint' => [
      'label' => 'Text Alignment Breakpoint',
      'default' => 'inherit',
      'description' => 'Define the device width from which the alignment will apply.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'enable' => 'grid_4_custom_item_text_align'
    ],
    'grid_4_custom_item_text_align_fallback' => [
      'label' => 'Text Alignment Fallback',
      'description' => 'Define an alignment fallback for device widths below the breakpoint.',
      'type' => 'select',
      'options' => $config->get('fs_grid.text_align_options_item'),
      'enable' => 'grid_4_custom_item_text_align && grid_4_custom_item_text_align_breakpoint != \'inherit\' && grid_4_custom_item_text_align_breakpoint != \'always\''
    ],
    'grid_4_custom_item_margin' => $config->get('fs_grid.grid_margin_item'),
    'grid_4_custom_item_visibility' => $config->get('fs_grid.visibility_item'),
    'grid_4_custom_item_slider' => [
      'label' => 'Grid #4 Slider',
      'description' => 'Enables nested grid slider.',
      'type' => 'select',
      'options' => [
        'Inherit' => '',
        'Enable' => 'enable',
        'Disable' => 'disable'
      ],
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_4_custom\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\']'
    ],
    'grid_4_custom_panel_style' => $config->get('fs_grid.panel_style_item'),
    'grid_4_custom_panel_card_offset' => [
      'type' => 'checkbox',
      'text' => 'Add clipping offset',
      'enable' => 'grid_4_custom_panel_style'
    ],
    'grid_4_custom_panel_padding' => $config->get('fs_grid.panel_padding_item'),
    'grid_4_custom_item_default' => $config->get('fs_grid.grid_default_item'),
    'grid_4_custom_item_small' => $config->get('fs_grid.grid_small'),
    'grid_4_custom_item_medium' => $config->get('fs_grid.grid_medium'),
    'grid_4_custom_item_large' => $config->get('fs_grid.grid_large'),
    'grid_4_custom_item_xlarge' => $config->get('fs_grid.grid_xlarge'),
    'grid_5_custom_item_position' => $config->get('fs_grid.grid_position_item'),
    'grid_5_custom_item_column_gap' => $config->get('fs_grid.grid_column_gap_item'),
    'grid_5_custom_item_row_gap' => $config->get('fs_grid.grid_row_gap_item'),
    'grid_5_custom_item_divider_top' => $config->get('fs_grid.grid_divider_top'),
    'grid_5_custom_item_divider' => [
      'label' => 'Grid Dividers',
      'description' => 'Show a divider between grid columns.',
      'type' => 'checkbox',
      'text' => 'Show',
      'enable' => 'grid_5_custom_column_gap != \'collapse\' && grid_5_custom_row_gap != \'collapse\''
    ],
    'grid_5_custom_item_column_align' => [
      'label' => 'Alignment',
      'type' => 'checkbox',
      'text' => 'Center columns',
      'enable' => '!grid_5_custom_slider'
    ],
    'grid_5_custom_item_row_align' => $config->get('fs_grid.grid_row_align'),
    'grid_5_custom_item_grid_match' => $config->get('fs_grid.grid_match'),
    'grid_5_custom_item_text_align' => $config->get('fs_grid.text_align_item'),
    'grid_5_custom_item_text_align_breakpoint' => [
      'label' => 'Text Alignment Breakpoint',
      'default' => 'inherit',
      'description' => 'Define the device width from which the alignment will apply.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'enable' => 'grid_5_custom_item_text_align'
    ],
    'grid_5_custom_item_text_align_fallback' => [
      'label' => 'Text Alignment Fallback',
      'description' => 'Define an alignment fallback for device widths below the breakpoint.',
      'type' => 'select',
      'options' => $config->get('fs_grid.text_align_options_item'),
      'enable' => 'grid_5_custom_item_text_align && grid_5_custom_item_text_align_breakpoint != \'inherit\' && grid_5_custom_item_text_align_breakpoint != \'always\''
    ],
    'grid_5_custom_item_margin' => $config->get('fs_grid.grid_margin_item'),
    'grid_5_custom_item_visibility' => $config->get('fs_grid.visibility_item'),
    'grid_5_custom_item_slider' => [
      'label' => 'Grid #5 Slider',
      'description' => 'Enables nested grid slider.',
      'type' => 'select',
      'options' => [
        'Inherit' => '',
        'Enable' => 'enable',
        'Disable' => 'disable'
      ],
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_5_custom\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\']'
    ],
    'grid_5_custom_panel_style' => $config->get('fs_grid.panel_style_item'),
    'grid_5_custom_panel_card_offset' => [
      'type' => 'checkbox',
      'text' => 'Add clipping offset',
      'enable' => 'grid_5_custom_panel_style'
    ],
    'grid_5_custom_panel_padding' => $config->get('fs_grid.panel_padding_item'),
    'grid_5_custom_item_default' => $config->get('fs_grid.grid_default_item'),
    'grid_5_custom_item_small' => $config->get('fs_grid.grid_small'),
    'grid_5_custom_item_medium' => $config->get('fs_grid.grid_medium'),
    'grid_5_custom_item_large' => $config->get('fs_grid.grid_large'),
    'grid_5_custom_item_xlarge' => $config->get('fs_grid.grid_xlarge'),
    'grid_6_custom_item_position' => $config->get('fs_grid.grid_position_item'),
    'grid_6_custom_item_column_gap' => $config->get('fs_grid.grid_column_gap'),
    'grid_6_custom_item_row_gap' => $config->get('fs_grid.grid_row_gap_item'),
    'grid_6_custom_item_divider_top' => $config->get('fs_grid.grid_divider_top'),
    'grid_6_custom_item_divider' => [
      'label' => 'Grid Dividers',
      'description' => 'Show a divider between grid columns.',
      'type' => 'checkbox',
      'text' => 'Show',
      'enable' => 'grid_6_custom_column_gap != \'collapse\' && grid_6_custom_row_gap != \'collapse\''
    ],
    'grid_6_custom_item_column_align' => [
      'label' => 'Alignment',
      'type' => 'checkbox',
      'text' => 'Center columns',
      'enable' => '!grid_6_custom_slider'
    ],
    'grid_6_custom_item_row_align' => $config->get('fs_grid.grid_row_align'),
    'grid_6_custom_item_grid_match' => $config->get('fs_grid.grid_match'),
    'grid_6_custom_item_text_align' => $config->get('fs_grid.text_align_item'),
    'grid_6_custom_item_text_align_breakpoint' => [
      'label' => 'Text Alignment Breakpoint',
      'default' => 'inherit',
      'description' => 'Define the device width from which the alignment will apply.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'enable' => 'grid_6_custom_item_text_align'
    ],
    'grid_6_custom_item_text_align_fallback' => [
      'label' => 'Text Alignment Fallback',
      'description' => 'Define an alignment fallback for device widths below the breakpoint.',
      'type' => 'select',
      'options' => $config->get('fs_grid.text_align_options_item'),
      'enable' => 'grid_6_custom_item_text_align && grid_6_custom_item_text_align_breakpoint != \'inherit\' && grid_6_custom_item_text_align_breakpoint != \'always\''
    ],
    'grid_6_custom_item_margin' => $config->get('fs_grid.grid_margin_item'),
    'grid_6_custom_item_visibility' => $config->get('fs_grid.visibility_item'),
    'grid_6_custom_item_slider' => [
      'label' => 'Grid #6 Slider',
      'description' => 'Enables nested grid slider.',
      'type' => 'select',
      'options' => [
        'Inherit' => '',
        'Enable' => 'enable',
        'Disable' => 'disable'
      ],
      'show' => 'this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_6_custom\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\']'
    ],
    'grid_6_custom_panel_style' => $config->get('fs_grid.panel_style_item'),
    'grid_6_custom_panel_card_offset' => [
      'type' => 'checkbox',
      'text' => 'Add clipping offset',
      'enable' => 'grid_6_custom_panel_style'
    ],
    'grid_6_custom_panel_padding' => $config->get('fs_grid.panel_padding_item'),
    'grid_6_custom_item_default' => $config->get('fs_grid.grid_default_item'),
    'grid_6_custom_item_small' => $config->get('fs_grid.grid_small'),
    'grid_6_custom_item_medium' => $config->get('fs_grid.grid_medium'),
    'grid_6_custom_item_large' => $config->get('fs_grid.grid_large'),
    'grid_6_custom_item_xlarge' => $config->get('fs_grid.grid_xlarge'),
    'custom_1_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_2_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_3_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_4_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_5_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_6_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_7_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_8_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_9_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_10_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_11_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_12_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_13_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_14_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_15_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_16_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_17_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_18_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_19_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_20_grid_item' => $config->get('fs_grid.target_grid_item'),
    'custom_1_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_2_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_3_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_4_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_5_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_6_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_7_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_8_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_9_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_10_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_11_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_12_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_13_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_14_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_15_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_16_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_17_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_18_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_19_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_20_visibility_item' => $config->get('fs_grid.visibility_item'),
    'custom_1_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_1_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_1_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_1_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_1_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_2_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_2_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_2_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_2_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_2_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_3_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_3_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_3_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_3_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_3_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_4_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_4_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_4_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_4_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_4_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_5_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_5_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_5_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_5_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_5_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_6_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_6_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_6_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_6_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_6_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_7_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_7_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_7_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_7_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_7_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_8_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_8_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_8_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_8_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_8_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_9_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_9_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_9_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_9_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_9_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_10_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_10_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_10_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_10_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_10_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_11_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_11_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_11_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_11_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_11_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_12_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_12_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_12_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_12_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_12_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_13_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_13_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_13_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_13_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_13_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_14_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_14_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_14_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_14_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_14_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_15_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_15_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_15_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_15_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_15_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_16_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_16_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_16_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_16_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_16_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_17_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_17_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_17_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_17_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_17_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_18_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_18_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_18_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_18_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_18_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_19_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_19_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_19_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_19_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_19_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'custom_20_mixed_width_item_default' => $config->get('fs_grid.grid_default_item_mixed_width'),
    'custom_20_mixed_width_item_small' => $config->get('fs_grid.grid_small_item_mixed_width'),
    'custom_20_mixed_width_item_medium' => $config->get('fs_grid.grid_medium_item_mixed_width'),
    'custom_20_mixed_width_item_large' => $config->get('fs_grid.grid_large_item_mixed_width'),
    'custom_20_mixed_width_item_xlarge' => $config->get('fs_grid.grid_xlarge_item_mixed_width'),
    'grid_1_custom_image_width' => $config->get('fs_grid.grid_image_width'),
    'grid_1_custom_image_height' => $config->get('fs_grid.grid_image_height'),
    'grid_1_custom_image_border' => $config->get('fs_grid.grid_image_border_item'),
    'grid_1_custom_icon_width' => $config->get('fs_grid.grid_icon_width_item'),
    'grid_1_custom_icon_color' => $config->get('fs_grid.grid_icon_color_item'),
    'grid_1_custom_image_align' => $config->get('fs_grid.grid_image_align_item'),
    'grid_1_custom_image_grid_width' => [
      'label' => 'Grid Width',
      'description' => 'Define the width of the image within the grid. Choose between percent and fixed widths or expand columns to the width of their content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_image_grid_width_options_item'),
      'show' => '(grid_1_custom_image_align == \'left\' || grid_1_custom_image_align == \'right\')'
    ],
    'grid_1_custom_image_grid_column_gap' => [
      'label' => 'Grid Column Gap',
      'description' => 'Set the size of the gap between the image and the content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_1_custom_image_align == \'left\' || grid_1_custom_image_align == \'right\')'
    ],
    'grid_1_custom_image_grid_row_gap' => [
      'label' => 'Grid Row Gap',
      'description' => 'Set the size of the gap if the grid items stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_1_custom_image_align == \'left\' || grid_1_custom_image_align == \'right\')'
    ],
    'grid_1_custom_image_grid_breakpoint' => [
      'label' => 'Grid Breakpoint',
      'description' => 'Set the breakpoint from which grid items will stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'show' => '(grid_1_custom_image_align == \'left\' || grid_1_custom_image_align == \'right\')'
    ],
    'grid_1_custom_image_vertical_align' => [
      'label' => 'Vertical Alignment',
      'description' => 'Vertically center grid items.',
      'type' => 'checkbox',
      'text' => 'Center',
      'show' => '(grid_1_custom_image_align == \'left\' || grid_1_custom_image_align == \'right\')'
    ],
    'grid_1_custom_image_margin_bottom' => [
      'label' => 'Margin Bottom',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_1_custom_image_align == \'top\''
    ],
    'grid_1_custom_image_margin_top' => [
      'label' => 'Margin Top',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_1_custom_image_align == \'bottom\''
    ],
    'grid_1_custom_image_svg_inline' => $config->get('fs_grid.grid_image_svg_inline'),
    'grid_1_custom_image_svg_animate' => [
      'type' => 'checkbox',
      'text' => 'Animate strokes',
      'show' => 'grid_1_custom_image_svg_inline'
    ],
    'grid_1_custom_image_svg_color' => [
      'label' => 'SVG Color',
      'description' => 'Select the SVG color. It will only apply to supported elements defined in the SVG.',
      'type' => 'select',
      'options' => $config->get('fs_grid.color_options_item'),
      'show' => 'grid_1_custom_image_svg_inline'
    ],
    'grid_2_custom_image_width' => $config->get('fs_grid.grid_image_width'),
    'grid_2_custom_image_height' => $config->get('fs_grid.grid_image_height'),
    'grid_2_custom_image_border' => $config->get('fs_grid.grid_image_border_item'),
    'grid_2_custom_icon_width' => $config->get('fs_grid.grid_icon_width_item'),
    'grid_2_custom_icon_color' => $config->get('fs_grid.grid_icon_color_item'),
    'grid_2_custom_image_align' => $config->get('fs_grid.grid_image_align_item'),
    'grid_2_custom_image_grid_width' => [
      'label' => 'Grid Width',
      'description' => 'Define the width of the image within the grid. Choose between percent and fixed widths or expand columns to the width of their content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_image_grid_width_options_item'),
      'show' => '(grid_2_custom_image_align == \'left\' || grid_2_custom_image_align == \'right\')'
    ],
    'grid_2_custom_image_grid_column_gap' => [
      'label' => 'Grid Column Gap',
      'description' => 'Set the size of the gap between the image and the content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_2_custom_image_align == \'left\' || grid_2_custom_image_align == \'right\')'
    ],
    'grid_2_custom_image_grid_row_gap' => [
      'label' => 'Grid Row Gap',
      'description' => 'Set the size of the gap if the grid items stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_2_custom_image_align == \'left\' || grid_2_custom_image_align == \'right\')'
    ],
    'grid_2_custom_image_grid_breakpoint' => [
      'label' => 'Grid Breakpoint',
      'description' => 'Set the breakpoint from which grid items will stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'show' => '(grid_2_custom_image_align == \'left\' || grid_2_custom_image_align == \'right\')'
    ],
    'grid_2_custom_image_vertical_align' => [
      'label' => 'Vertical Alignment',
      'description' => 'Vertically center grid items.',
      'type' => 'checkbox',
      'text' => 'Center',
      'show' => '(grid_2_custom_image_align == \'left\' || grid_2_custom_image_align == \'right\')'
    ],
    'grid_2_custom_image_margin_bottom' => [
      'label' => 'Margin Bottom',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_2_custom_image_align == \'top\''
    ],
    'grid_2_custom_image_margin_top' => [
      'label' => 'Margin Top',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_2_custom_image_align == \'bottom\''
    ],
    'grid_2_custom_image_svg_inline' => $config->get('fs_grid.grid_image_svg_inline'),
    'grid_2_custom_image_svg_animate' => [
      'type' => 'checkbox',
      'text' => 'Animate strokes',
      'show' => 'grid_2_custom_image_svg_inline'
    ],
    'grid_2_custom_image_svg_color' => [
      'label' => 'SVG Color',
      'description' => 'Select the SVG color. It will only apply to supported elements defined in the SVG.',
      'type' => 'select',
      'options' => $config->get('fs_grid.color_options_item'),
      'show' => 'grid_2_custom_image_svg_inline'
    ],
    'grid_3_custom_image_width' => $config->get('fs_grid.grid_image_width'),
    'grid_3_custom_image_height' => $config->get('fs_grid.grid_image_height'),
    'grid_3_custom_image_border' => $config->get('fs_grid.grid_image_border_item'),
    'grid_3_custom_icon_width' => $config->get('fs_grid.grid_icon_width_item'),
    'grid_3_custom_icon_color' => $config->get('fs_grid.grid_icon_color_item'),
    'grid_3_custom_image_align' => $config->get('fs_grid.grid_image_align_item'),
    'grid_3_custom_image_grid_width' => [
      'label' => 'Grid Width',
      'description' => 'Define the width of the image within the grid. Choose between percent and fixed widths or expand columns to the width of their content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_image_grid_width_options_item'),
      'show' => '(grid_3_custom_image_align == \'left\' || grid_3_custom_image_align == \'right\')'
    ],
    'grid_3_custom_image_grid_column_gap' => [
      'label' => 'Grid Column Gap',
      'description' => 'Set the size of the gap between the image and the content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_3_custom_image_align == \'left\' || grid_3_custom_image_align == \'right\')'
    ],
    'grid_3_custom_image_grid_row_gap' => [
      'label' => 'Grid Row Gap',
      'description' => 'Set the size of the gap if the grid items stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_3_custom_image_align == \'left\' || grid_3_custom_image_align == \'right\')'
    ],
    'grid_3_custom_image_grid_breakpoint' => [
      'label' => 'Grid Breakpoint',
      'description' => 'Set the breakpoint from which grid items will stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'show' => '(grid_3_custom_image_align == \'left\' || grid_3_custom_image_align == \'right\')'
    ],
    'grid_3_custom_image_vertical_align' => [
      'label' => 'Vertical Alignment',
      'description' => 'Vertically center grid items.',
      'type' => 'checkbox',
      'text' => 'Center',
      'show' => '(grid_3_custom_image_align == \'left\' || grid_3_custom_image_align == \'right\')'
    ],
    'grid_3_custom_image_margin_bottom' => [
      'label' => 'Margin Bottom',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_3_custom_image_align == \'top\''
    ],
    'grid_3_custom_image_margin_top' => [
      'label' => 'Margin Top',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_3_custom_image_align == \'bottom\''
    ],
    'grid_3_custom_image_svg_inline' => $config->get('fs_grid.grid_image_svg_inline'),
    'grid_3_custom_image_svg_animate' => [
      'type' => 'checkbox',
      'text' => 'Animate strokes',
      'show' => 'grid_3_custom_image_svg_inline'
    ],
    'grid_3_custom_image_svg_color' => [
      'label' => 'SVG Color',
      'description' => 'Select the SVG color. It will only apply to supported elements defined in the SVG.',
      'type' => 'select',
      'options' => $config->get('fs_grid.color_options_item'),
      'show' => 'grid_3_custom_image_svg_inline'
    ],
    'grid_4_custom_image_width' => $config->get('fs_grid.grid_image_width'),
    'grid_4_custom_image_height' => $config->get('fs_grid.grid_image_height'),
    'grid_4_custom_image_border' => $config->get('fs_grid.grid_image_border_item'),
    'grid_4_custom_icon_width' => $config->get('fs_grid.grid_icon_width_item'),
    'grid_4_custom_icon_color' => $config->get('fs_grid.grid_icon_color_item'),
    'grid_4_custom_image_align' => $config->get('fs_grid.grid_image_align_item'),
    'grid_4_custom_image_grid_width' => [
      'label' => 'Grid Width',
      'description' => 'Define the width of the image within the grid. Choose between percent and fixed widths or expand columns to the width of their content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_image_grid_width_options_item'),
      'show' => '(grid_4_custom_image_align == \'left\' || grid_4_custom_image_align == \'right\')'
    ],
    'grid_4_custom_image_grid_column_gap' => [
      'label' => 'Grid Column Gap',
      'description' => 'Set the size of the gap between the image and the content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_4_custom_image_align == \'left\' || grid_4_custom_image_align == \'right\')'
    ],
    'grid_4_custom_image_grid_row_gap' => [
      'label' => 'Grid Row Gap',
      'description' => 'Set the size of the gap if the grid items stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_4_custom_image_align == \'left\' || grid_4_custom_image_align == \'right\')'
    ],
    'grid_4_custom_image_grid_breakpoint' => [
      'label' => 'Grid Breakpoint',
      'description' => 'Set the breakpoint from which grid items will stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'show' => '(grid_4_custom_image_align == \'left\' || grid_4_custom_image_align == \'right\')'
    ],
    'grid_4_custom_image_vertical_align' => [
      'label' => 'Vertical Alignment',
      'description' => 'Vertically center grid items.',
      'type' => 'checkbox',
      'text' => 'Center',
      'show' => '(grid_4_custom_image_align == \'left\' || grid_4_custom_image_align == \'right\')'
    ],
    'grid_4_custom_image_margin_bottom' => [
      'label' => 'Margin Bottom',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_4_custom_image_align == \'top\''
    ],
    'grid_4_custom_image_margin_top' => [
      'label' => 'Margin Top',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_4_custom_image_align == \'bottom\''
    ],
    'grid_4_custom_image_svg_inline' => $config->get('fs_grid.grid_image_svg_inline'),
    'grid_4_custom_image_svg_animate' => [
      'type' => 'checkbox',
      'text' => 'Animate strokes',
      'show' => 'grid_4_custom_image_svg_inline'
    ],
    'grid_4_custom_image_svg_color' => [
      'label' => 'SVG Color',
      'description' => 'Select the SVG color. It will only apply to supported elements defined in the SVG.',
      'type' => 'select',
      'options' => $config->get('fs_grid.color_options_item'),
      'show' => 'grid_4_custom_image_svg_inline'
    ],
    'grid_5_custom_image_width' => $config->get('fs_grid.grid_image_width'),
    'grid_5_custom_image_height' => $config->get('fs_grid.grid_image_height'),
    'grid_5_custom_image_border' => $config->get('fs_grid.grid_image_border_item'),
    'grid_5_custom_icon_width' => $config->get('fs_grid.grid_icon_width_item'),
    'grid_5_custom_icon_color' => $config->get('fs_grid.grid_icon_color_item'),
    'grid_5_custom_image_align' => $config->get('fs_grid.grid_image_align_item'),
    'grid_5_custom_image_grid_width' => [
      'label' => 'Grid Width',
      'description' => 'Define the width of the image within the grid. Choose between percent and fixed widths or expand columns to the width of their content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_image_grid_width_options_item'),
      'show' => '(grid_5_custom_image_align == \'left\' || grid_5_custom_image_align == \'right\')'
    ],
    'grid_5_custom_image_grid_column_gap' => [
      'label' => 'Grid Column Gap',
      'description' => 'Set the size of the gap between the image and the content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_5_custom_image_align == \'left\' || grid_5_custom_image_align == \'right\')'
    ],
    'grid_5_custom_image_grid_row_gap' => [
      'label' => 'Grid Row Gap',
      'description' => 'Set the size of the gap if the grid items stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_5_custom_image_align == \'left\' || grid_5_custom_image_align == \'right\')'
    ],
    'grid_5_custom_image_grid_breakpoint' => [
      'label' => 'Grid Breakpoint',
      'description' => 'Set the breakpoint from which grid items will stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'show' => '(grid_5_custom_image_align == \'left\' || grid_5_custom_image_align == \'right\')'
    ],
    'grid_5_custom_image_vertical_align' => [
      'label' => 'Vertical Alignment',
      'description' => 'Vertically center grid items.',
      'type' => 'checkbox',
      'text' => 'Center',
      'show' => '(grid_5_custom_image_align == \'left\' || grid_5_custom_image_align == \'right\')'
    ],
    'grid_5_custom_image_margin_bottom' => [
      'label' => 'Margin Bottom',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_5_custom_image_align == \'top\''
    ],
    'grid_5_custom_image_margin_top' => [
      'label' => 'Margin Top',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_5_custom_image_align == \'bottom\''
    ],
    'grid_5_custom_image_svg_inline' => $config->get('fs_grid.grid_image_svg_inline'),
    'grid_5_custom_image_svg_animate' => [
      'type' => 'checkbox',
      'text' => 'Animate strokes',
      'show' => 'grid_5_custom_image_svg_inline'
    ],
    'grid_5_custom_image_svg_color' => [
      'label' => 'SVG Color',
      'description' => 'Select the SVG color. It will only apply to supported elements defined in the SVG.',
      'type' => 'select',
      'options' => $config->get('fs_grid.color_options_item'),
      'show' => 'grid_5_custom_image_svg_inline'
    ],
    'grid_6_custom_image_width' => $config->get('fs_grid.grid_image_width'),
    'grid_6_custom_image_height' => $config->get('fs_grid.grid_image_height'),
    'grid_6_custom_image_border' => $config->get('fs_grid.grid_image_border_item'),
    'grid_6_custom_icon_width' => $config->get('fs_grid.grid_icon_width_item'),
    'grid_6_custom_icon_color' => $config->get('fs_grid.grid_icon_color_item'),
    'grid_6_custom_image_align' => $config->get('fs_grid.grid_image_align_item'),
    'grid_6_custom_image_grid_width' => [
      'label' => 'Grid Width',
      'description' => 'Define the width of the image within the grid. Choose between percent and fixed widths or expand columns to the width of their content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_image_grid_width_options_item'),
      'show' => '(grid_6_custom_image_align == \'left\' || grid_6_custom_image_align == \'right\')'
    ],
    'grid_6_custom_image_grid_column_gap' => [
      'label' => 'Grid Column Gap',
      'description' => 'Set the size of the gap between the image and the content.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_6_custom_image_align == \'left\' || grid_6_custom_image_align == \'right\')'
    ],
    'grid_6_custom_image_grid_row_gap' => [
      'label' => 'Grid Row Gap',
      'description' => 'Set the size of the gap if the grid items stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_gap_options_item'),
      'show' => '(grid_6_custom_image_align == \'left\' || grid_6_custom_image_align == \'right\')'
    ],
    'grid_6_custom_image_grid_breakpoint' => [
      'label' => 'Grid Breakpoint',
      'description' => 'Set the breakpoint from which grid items will stack.',
      'type' => 'select',
      'options' => $config->get('fs_grid.grid_breakpoint_options_full_item'),
      'show' => '(grid_6_custom_image_align == \'left\' || grid_6_custom_image_align == \'right\')'
    ],
    'grid_6_custom_image_vertical_align' => [
      'label' => 'Vertical Alignment',
      'description' => 'Vertically center grid items.',
      'type' => 'checkbox',
      'text' => 'Center',
      'show' => '(grid_6_custom_image_align == \'left\' || grid_6_custom_image_align == \'right\')'
    ],
    'grid_6_custom_image_margin_bottom' => [
      'label' => 'Margin Bottom',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_6_custom_image_align == \'top\''
    ],
    'grid_6_custom_image_margin_top' => [
      'label' => 'Margin Top',
      'default' => 'uk-margin-small',
      'description' => 'Set the bottom margin. Note that the margin will only apply if the content field immediately follows another content field.',
      'type' => 'select',
      'options' => $config->get('fs_grid.margin_options_full_item'),
      'show' => 'grid_6_custom_image_align == \'bottom\''
    ],
    'grid_6_custom_image_svg_inline' => $config->get('fs_grid.grid_image_svg_inline'),
    'grid_6_custom_image_svg_animate' => [
      'type' => 'checkbox',
      'text' => 'Animate strokes',
      'show' => 'grid_6_custom_image_svg_inline'
    ],
    'grid_6_custom_image_svg_color' => [
      'label' => 'SVG Color',
      'description' => 'Select the SVG color. It will only apply to supported elements defined in the SVG.',
      'type' => 'select',
      'options' => $config->get('fs_grid.color_options_item'),
      'show' => 'grid_6_custom_image_svg_inline'
    ],
    'panel_style' => [
      'label' => 'Style',
      'description' => 'Select one of the boxed card styles.',
      'type' => 'select',
      'options' => [
        'Inherit' => '',
        'Card Default' => 'card-default',
        'Card Primary' => 'card-primary',
        'Card Secondary' => 'card-secondary',
        'Card Hover' => 'card-hover',
        'Tile Default' => 'tile-default',
        'Tile Muted' => 'tile-muted',
        'Tile Primary' => 'tile-primary',
        'Tile Secondary' => 'tile-secondary'
      ],
      'source' => true
    ],
    'item_element' => $config->get('builder.html_element_item'),
    'item_id' => [
      'label' => 'ID',
      'description' => 'Define a unique identifier for the item.',
      'source' => true,
      'show' => '$match(show_custom_settings, \'(^item_id_class$|^all$)\')'
    ],
    'item_class' => [
      'label' => 'Classes',
      'source' => true,
      'show' => '$match(show_custom_settings, \'(^item_id_class$|^all$)\')'
    ],
    'item_class_tag_in_container' => [
      'type' => 'checkbox',
      'text' => 'Assign classes to container',
      'description' => 'Define one or more class names for the item. Separate multiple classes with spaces.',
      'source' => true,
      'show' => '$match(show_custom_settings, \'(^item_id_class$|^all$)\')'
    ],
    'item_attrs_tag' => [
      'label' => 'Attributes',
      'type' => 'editor',
      'editor' => 'code',
      'source' => true,
      'show' => '$match(show_custom_settings, \'(^item_id_class$|^all$)\')'
    ],
    'item_attrs_tag_in_container' => [
      'type' => 'checkbox',
      'description' => 'Define one or more attributes for the item. Separate attribute name and value by <code>=</code> character. One attribute per line.',
      'text' => 'Assign attributes to container',
      'source' => true,
      'divider' => true,
      'show' => '$match(show_custom_settings, \'(^item_id_class$|^all$)\')'
    ],
    'link_woo' => [
      'label' => 'WooCommerce',
      'type' => 'checkbox',
      'description' => '<code>WooCommerce</code>Integrate WooCommerce AJAX Add to Cart button. <br /><u>Please set the item link first</u>, by choosing Add to Cart Link in the dynamic content sources and don\'t forget to <u>enable ajax add to cart feature</u> in the WooCommerce settings.',
      'text' => 'Add to Cart',
      'enable' => 'link',
      'show' => 'this.builder.parent(this.node)[\'props\'][\'show_link\'] && !Joomla'
    ],
    'link_woo_sku' => [
      'description' => '<code>REQUIRED</code>Please mapp product SKU via dynamic content.',
      'label' => 'Product SKU',
      'attrs' => [
        'placeholder' => 'Product SKU'
      ],
      'source' => true,
      'enable' => 'link && link_woo',
      'show' => 'link && link_woo && this.builder.parent(this.node)[\'props\'][\'show_link\'] && !Joomla'
    ],
    'link_woo_quantity' => [
      'description' => '<code>REQUIRED</code>Sets default product quantity that will be added to the cart.',
      'label' => 'Quantity',
      'attrs' => [
        'placeholder' => '1'
      ],
      'source' => true,
      'enable' => 'link && link_woo',
      'show' => 'link && link_woo && this.builder.parent(this.node)[\'props\'][\'show_link\'] && !Joomla'
    ],
    'name' => [
      'label' => 'Name',
      'description' => 'Define a name to easily identify this element inside the builder.'
    ],
    'status' => $config->get('builder.statusItem'),
    'source' => $config->get('builder.source')
  ],
  'fieldset' => [
    'default' => [
      'type' => 'tabs',
      'fields' => [[
          'title' => 'Content',
          'fields' => ['title','meta','content','image','_fs_focal_point_panel','image_alt','image_title','icon','image_attrs_tag','link_panel_custom','link_panel_custom_item_target','link_panel_toggle','link_panel_advanced',[
              'label' => 'Panel Link Attributes',
              'description' => '<code>IMPORTANT</code> Please learn the implications for SEO and web security, before enabling those attributes.',
              'type' => 'group',
              'fields' => ['link_panel_nofollow','link_panel_sponsored','link_panel_ugc','link_panel_noopener','link_panel_noreferrer','link_panel_prefetch'],
              'enable' => 'link_panel_advanced && link_panel_custom',
              'show' => 'link_panel_advanced && link_panel_custom && this.builder.parent(this.node)[\'props\'][\'show_link\'] && this.builder.parent(this.node)[\'props\'][\'panel_link\']'
            ],'link_panel_title','link_panel_custom_aria_label','link','link_item_target','link_item_toggle','link_item_toggle_modal_integration','link_advanced','link_modal_id','link_modal_header_text',[
              'label' => 'Link Attributes',
              'description' => '<code>IMPORTANT</code> Please learn the implications for SEO and web security, before enabling those attributes.',
              'type' => 'group',
              'fields' => ['link_item_nofollow','link_item_sponsored','link_item_ugc','link_item_noopener','link_item_noreferrer','link_item_prefetch'],
              'enable' => 'link && link_advanced',
              'show' => 'link && link_advanced && this.builder.parent(this.node)[\'props\'][\'show_link\']'
            ],'link_text','link_aria_label','link_title','link_class','link_attrs_tag','tags','rating']
        ],[
          'title' => 'Fields',
          'fields' => [[
              'label' => 'Custom Field Sets Are Not Enabled',
              'type' => 'group',
              'divider' => true,
              'description' => '<code>NOTE:</code>You have not enabled any of the custom field sets. You can enable some at the bottom of the element content tab.',
              'show' => '(!this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_10\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_11\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_12\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_13\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_14\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_15\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_16\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_17\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_18\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_19\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_20\']))'
            ],[
              'label' => 'Custom Fields Visibility',
              'description' => 'With this options, you can show or hide custom fields here for better navigation through the item. This will not take effect on the page.',
              'divider' => true,
              'type' => 'group',
              'fields' => ['show_custom_fields'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_20\'])'
            ],[
              'name' => '_fieldset_1',
              'type' => 'fields',
              'fields' => ['custom_1_fields_disabled','custom_meta_1','custom_text_1','custom_image_1','custom_image_1_alt','custom_image_1_icon','custom_link_1','custom_link_1_item_target','custom_link_1_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_1\''
            ],[
              'name' => '_fieldset_2',
              'type' => 'fields',
              'fields' => ['custom_2_fields_disabled','custom_meta_2','custom_text_2','custom_image_2','custom_image_2_alt','custom_image_2_icon','custom_link_2','custom_link_2_item_target','custom_link_2_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_2\''
            ],[
              'name' => '_fieldset_3',
              'type' => 'fields',
              'fields' => ['custom_3_fields_disabled','custom_meta_3','custom_text_3','custom_image_3','custom_image_3_alt','custom_image_3_icon','custom_link_3','custom_link_3_item_target','custom_link_3_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_3\''
            ],[
              'name' => '_fieldset_4',
              'type' => 'fields',
              'fields' => ['custom_4_fields_disabled','custom_meta_4','custom_text_4','custom_image_4','custom_image_4_alt','custom_image_4_icon','custom_link_4','custom_link_4_item_target','custom_link_4_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_4\''
            ],[
              'name' => '_fieldset_5',
              'type' => 'fields',
              'fields' => ['custom_5_fields_disabled','custom_meta_5','custom_text_5','custom_image_5','custom_image_5_alt','custom_image_5_icon','custom_link_5','custom_link_5_item_target','custom_link_5_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_5\''
            ],[
              'name' => '_fieldset_6',
              'type' => 'fields',
              'fields' => ['custom_6_fields_disabled','custom_meta_6','custom_text_6','custom_image_6','custom_image_6_alt','custom_image_6_icon','custom_link_6','custom_link_6_item_target','custom_link_6_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_6\''
            ],[
              'name' => '_fieldset_7',
              'type' => 'fields',
              'fields' => ['custom_7_fields_disabled','custom_meta_7','custom_text_7','custom_image_7','custom_image_7_alt','custom_image_7_icon','custom_link_7','custom_link_7_item_target','custom_link_7_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_7\''
            ],[
              'name' => '_fieldset_8',
              'type' => 'fields',
              'fields' => ['custom_8_fields_disabled','custom_meta_8','custom_text_8','custom_image_8','custom_image_8_alt','custom_image_8_icon','custom_link_8','custom_link_8_item_target','custom_link_8_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_8\''
            ],[
              'name' => '_fieldset_9',
              'type' => 'fields',
              'fields' => ['custom_9_fields_disabled','custom_meta_9','custom_text_9','custom_image_9','custom_image_9_alt','custom_image_9_icon','custom_link_9','custom_link_9_item_target','custom_link_9_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_9\''
            ],[
              'name' => '_fieldset_10',
              'type' => 'fields',
              'fields' => ['custom_10_fields_disabled','custom_meta_10','custom_text_10','custom_image_10','custom_image_10_alt','custom_image_10_icon','custom_link_10','custom_link_10_item_target','custom_link_10_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_10\''
            ],[
              'name' => '_fieldset_11',
              'type' => 'fields',
              'fields' => ['custom_11_fields_disabled','custom_meta_11','custom_text_11','custom_image_11','custom_image_11_alt','custom_image_11_icon','custom_link_11','custom_link_11_item_target','custom_link_11_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_11\''
            ],[
              'name' => '_fieldset_12',
              'type' => 'fields',
              'fields' => ['custom_12_fields_disabled','custom_meta_12','custom_text_12','custom_image_12','custom_image_12_alt','custom_image_12_icon','custom_link_12','custom_link_12_item_target','custom_link_12_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_12\''
            ],[
              'name' => '_fieldset_13',
              'type' => 'fields',
              'fields' => ['custom_13_fields_disabled','custom_meta_13','custom_text_13','custom_image_13','custom_image_13_alt','custom_image_13_icon','custom_link_13','custom_link_13_item_target','custom_link_13_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_13\''
            ],[
              'name' => '_fieldset_14',
              'type' => 'fields',
              'fields' => ['custom_14_fields_disabled','custom_meta_14','custom_text_14','custom_image_14','custom_image_14_alt','custom_image_14_icon','custom_link_14','custom_link_14_item_target','custom_link_14_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_14\''
            ],[
              'name' => '_fieldset_15',
              'type' => 'fields',
              'fields' => ['custom_15_fields_disabled','custom_meta_15','custom_text_15','custom_image_15','custom_image_15_alt','custom_image_15_icon','custom_link_15','custom_link_15_item_target','custom_link_15_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_15\''
            ],[
              'name' => '_fieldset_16',
              'type' => 'fields',
              'fields' => ['custom_16_fields_disabled','custom_meta_16','custom_text_16','custom_image_16','custom_image_16_alt','custom_image_16_icon','custom_link_16','custom_link_16_item_target','custom_link_16_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_16\''
            ],[
              'name' => '_fieldset_17',
              'type' => 'fields',
              'fields' => ['custom_17_fields_disabled','custom_meta_17','custom_text_17','custom_image_17','custom_image_17_alt','custom_image_17_icon','custom_link_17','custom_link_17_item_target','custom_link_17_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_17\''
            ],[
              'name' => '_fieldset_18',
              'type' => 'fields',
              'fields' => ['custom_18_fields_disabled','custom_meta_18','custom_text_18','custom_image_18','custom_image_18_alt','custom_image_18_icon','custom_link_18','custom_link_18_item_target','custom_link_18_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_18\''
            ],[
              'name' => '_fieldset_19',
              'type' => 'fields',
              'fields' => ['custom_19_fields_disabled','custom_meta_19','custom_text_19','custom_image_19','custom_image_19_alt','custom_image_19_icon','custom_link_19','custom_link_19_item_target','custom_link_19_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_19\''
            ],[
              'name' => '_fieldset_20',
              'type' => 'fields',
              'fields' => ['custom_20_fields_disabled','custom_meta_20','custom_text_20','custom_image_20','custom_image_20_alt','custom_image_20_icon','custom_link_20','custom_link_20_item_target','custom_link_20_toggle'],
              'show' => 'this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && show_custom_fields == \'fieldset_20\''
            ]]
        ],[
          'title' => 'Sublayout',
          'fields' => ['sublayout']
        ],[
          'title' => 'Overrides',
          'fields' => [[
              'label' => 'Settings Visibility',
              'description' => 'With this options, you can show or hide settings listed here for better navigation.',
              'divider' => true,
              'type' => 'group',
              'fields' => ['show_custom_settings']
            ],[
              'label' => 'Custom Grids Are Not Enabled',
              'type' => 'group',
              'divider' => true,
              'description' => '<code>NOTE:</code>You have not enabled any of the custom field sets to display in the custom grids. You can enable some at the bottom of the element content tab.',
              'show' => '$match(show_custom_settings, \'(^grids_disabler$|^grids_main$|^grids_columns$|^grids_sliders$|^grids_images$|^grids_panels$)\') && (!this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_10\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_11\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_12\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_13\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_14\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_15\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_16\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_17\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_18\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_19\']&& !this.builder.parent(this.node)[\'props\'][\'show_custom_20\']))'
            ],[
              'label' => 'Grids Disabler',
              'type' => 'group',
              'divider' => true,
              'fields' => ['item_disable_custom_grid_1','item_disable_custom_grid_2','item_disable_custom_grid_3','item_disable_custom_grid_4','item_disable_custom_grid_5','item_disable_custom_grid_6'],
              'show' => '$match(show_custom_settings, \'(^grids_disabler$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && (this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_1_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_2_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_3_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_4_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_5_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_6_custom\'])'
            ],[
              'label' => 'Grid #1',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_1_custom_item_position','grid_1_custom_item_column_gap','grid_1_custom_item_row_gap','grid_1_custom_item_divider_top','grid_1_custom_item_divider','grid_1_custom_item_column_align','grid_1_custom_item_row_align','grid_1_custom_item_grid_match','grid_1_custom_item_text_align','grid_1_custom_item_text_align_breakpoint','grid_1_custom_item_text_align_fallback','grid_1_custom_item_margin','grid_1_custom_item_visibility'],
              'show' => '$match(show_custom_settings, \'(^grids_main$|^all$)\') && !item_disable_custom_grid_1 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_1_custom\']'
            ],[
              'label' => 'Grid #1 Columns',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_1_custom_item_default','grid_1_custom_item_small','grid_1_custom_item_medium','grid_1_custom_item_large','grid_1_custom_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^grids_columns$|^all$)\') && !item_disable_custom_grid_1 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_1_custom\']'
            ],[
              'label' => 'Grid #1 Panel',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_1_custom_panel_style','grid_1_custom_panel_card_offset','grid_1_custom_panel_padding'],
              'show' => '$match(show_custom_settings, \'(^grids_panels$|^all$)\') && !item_disable_custom_grid_1 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_1_custom\']'
            ],[
              'label' => 'Grid #1 Image',
              'type' => 'group',
              'divider' => true,
              'fields' => [[
                  'label' => 'Width/Height',
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.',
                  'type' => 'grid',
                  'width' => '1-2',
                  'fields' => ['grid_1_custom_image_width','grid_1_custom_image_height']
                ],'grid_1_custom_image_border','grid_1_custom_icon_width','grid_1_custom_icon_color','grid_1_custom_image_align','grid_1_custom_image_grid_width','grid_1_custom_image_grid_column_gap','grid_1_custom_image_grid_row_gap','grid_1_custom_image_grid_breakpoint','grid_1_custom_image_vertical_align','grid_1_custom_image_margin_top','grid_1_custom_image_margin_bottom','grid_1_custom_image_svg_inline','grid_1_custom_image_svg_animate','grid_1_custom_image_svg_color'],
              'show' => '$match(show_custom_settings, \'(^grids_images$|^all$)\') && !item_disable_custom_grid_1 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_1_custom\']'
            ],[
              'label' => 'Grid #2',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_2_custom_item_position','grid_2_custom_item_column_gap','grid_2_custom_item_row_gap','grid_2_custom_item_divider_top','grid_2_custom_item_divider','grid_2_custom_item_column_align','grid_2_custom_item_row_align','grid_2_custom_item_grid_match','grid_2_custom_item_text_align','grid_2_custom_item_text_align_breakpoint','grid_2_custom_item_text_align_fallback','grid_2_custom_item_margin','grid_2_custom_item_visibility'],
              'show' => '$match(show_custom_settings, \'(^grids_main$|^all$)\') && !item_disable_custom_grid_2 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_2_custom\']'
            ],[
              'label' => 'Grid #2 Columns',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_2_custom_item_default','grid_2_custom_item_small','grid_2_custom_item_medium','grid_2_custom_item_large','grid_2_custom_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^grids_columns$|^all$)\') && !item_disable_custom_grid_2 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_2_custom\']'
            ],[
              'label' => 'Grid #2 Panel',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_2_custom_panel_style','grid_2_custom_panel_card_offset','grid_2_custom_panel_padding'],
              'show' => '$match(show_custom_settings, \'(^grids_panels$|^all$)\') && !item_disable_custom_grid_2 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_2_custom\']'
            ],[
              'label' => 'Grid #2 Image',
              'type' => 'group',
              'divider' => true,
              'fields' => [[
                  'label' => 'Width/Height',
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.',
                  'type' => 'grid',
                  'width' => '1-2',
                  'fields' => ['grid_2_custom_image_width','grid_2_custom_image_height']
                ],'grid_2_custom_image_border','grid_2_custom_icon_width','grid_2_custom_icon_color','grid_2_custom_image_align','grid_2_custom_image_grid_width','grid_2_custom_image_grid_column_gap','grid_2_custom_image_grid_row_gap','grid_2_custom_image_grid_breakpoint','grid_2_custom_image_vertical_align','grid_2_custom_image_margin_top','grid_2_custom_image_margin_bottom','grid_2_custom_image_svg_inline','grid_2_custom_image_svg_animate','grid_2_custom_image_svg_color'],
              'show' => '$match(show_custom_settings, \'(^grids_images$|^all$)\') && !item_disable_custom_grid_2 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_2_custom\']'
            ],[
              'label' => 'Grid #3',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_3_custom_item_position','grid_3_custom_item_column_gap','grid_3_custom_item_row_gap','grid_3_custom_item_divider_top','grid_3_custom_item_divider','grid_3_custom_item_column_align','grid_3_custom_item_row_align','grid_3_custom_item_grid_match','grid_3_custom_item_text_align','grid_3_custom_item_text_align_breakpoint','grid_3_custom_item_text_align_fallback','grid_3_custom_item_margin','grid_3_custom_item_visibility'],
              'show' => '$match(show_custom_settings, \'(^grids_main$|^all$)\') && !item_disable_custom_grid_3 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_3_custom\']'
            ],[
              'label' => 'Grid #3 Columns',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_3_custom_item_default','grid_3_custom_item_small','grid_3_custom_item_medium','grid_3_custom_item_large','grid_3_custom_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^grids_columns$|^all$)\') && !item_disable_custom_grid_3 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_3_custom\']'
            ],[
              'label' => 'Grid #3 Panel',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_3_custom_panel_style','grid_3_custom_panel_card_offset','grid_3_custom_panel_padding'],
              'show' => '$match(show_custom_settings, \'(^grids_panels$|^all$)\') && !item_disable_custom_grid_3 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_3_custom\']'
            ],[
              'label' => 'Grid #3 Image',
              'type' => 'group',
              'divider' => true,
              'fields' => [[
                  'label' => 'Width/Height',
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.',
                  'type' => 'grid',
                  'width' => '1-2',
                  'fields' => ['grid_3_custom_image_width','grid_3_custom_image_height']
                ],'grid_3_custom_image_border','grid_3_custom_icon_width','grid_3_custom_icon_color','grid_3_custom_image_align','grid_3_custom_image_grid_width','grid_3_custom_image_grid_column_gap','grid_3_custom_image_grid_row_gap','grid_3_custom_image_grid_breakpoint','grid_3_custom_image_vertical_align','grid_3_custom_image_margin_top','grid_3_custom_image_margin_bottom','grid_3_custom_image_svg_inline','grid_3_custom_image_svg_animate','grid_3_custom_image_svg_color'],
              'show' => '$match(show_custom_settings, \'(^grids_images$|^all$)\') && !item_disable_custom_grid_3 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_3_custom\']'
            ],[
              'label' => 'Grid #4',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_4_custom_item_position','grid_4_custom_item_column_gap','grid_4_custom_item_row_gap','grid_4_custom_item_divider_top','grid_4_custom_item_divider','grid_4_custom_item_column_align','grid_4_custom_item_row_align','grid_4_custom_item_grid_match','grid_4_custom_item_text_align','grid_4_custom_item_text_align_breakpoint','grid_4_custom_item_text_align_fallback','grid_4_custom_item_margin','grid_4_custom_item_visibility'],
              'show' => '$match(show_custom_settings, \'(^grids_main$|^all$)\') && !item_disable_custom_grid_4 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_4_custom\']'
            ],[
              'label' => 'Grid #4 Columns',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_4_custom_item_default','grid_4_custom_item_small','grid_4_custom_item_medium','grid_4_custom_item_large','grid_4_custom_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^grids_columns$|^all$)\') && !item_disable_custom_grid_4 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_4_custom\']'
            ],[
              'label' => 'Grid #4 Panel',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_4_custom_panel_style','grid_4_custom_panel_card_offset','grid_4_custom_panel_padding'],
              'show' => '$match(show_custom_settings, \'(^grids_panels$|^all$)\') && !item_disable_custom_grid_4 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_4_custom\']'
            ],[
              'label' => 'Grid #4 Image',
              'type' => 'group',
              'divider' => true,
              'fields' => [[
                  'label' => 'Width/Height',
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.',
                  'type' => 'grid',
                  'width' => '1-2',
                  'fields' => ['grid_4_custom_image_width','grid_4_custom_image_height']
                ],'grid_4_custom_image_border','grid_4_custom_icon_width','grid_4_custom_icon_color','grid_4_custom_image_align','grid_4_custom_image_grid_width','grid_4_custom_image_grid_column_gap','grid_4_custom_image_grid_row_gap','grid_4_custom_image_grid_breakpoint','grid_4_custom_image_vertical_align','grid_4_custom_image_margin_top','grid_4_custom_image_margin_bottom','grid_4_custom_image_svg_inline','grid_4_custom_image_svg_animate','grid_4_custom_image_svg_color'],
              'show' => '$match(show_custom_settings, \'(^grids_images$|^all$)\') && !item_disable_custom_grid_4 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_4_custom\']'
            ],[
              'label' => 'Grid #5',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_5_custom_item_position','grid_5_custom_item_column_gap','grid_5_custom_item_row_gap','grid_5_custom_item_divider_top','grid_5_custom_item_divider','grid_5_custom_item_column_align','grid_5_custom_item_row_align','grid_5_custom_item_grid_match','grid_5_custom_item_text_align','grid_5_custom_item_text_align_breakpoint','grid_5_custom_item_text_align_fallback','grid_5_custom_item_margin','grid_5_custom_item_visibility'],
              'show' => '$match(show_custom_settings, \'(^grids_main$|^all$)\') && !item_disable_custom_grid_5 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_5_custom\']'
            ],[
              'label' => 'Grid #5 Columns',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_5_custom_item_default','grid_5_custom_item_small','grid_5_custom_item_medium','grid_5_custom_item_large','grid_5_custom_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^grids_columns$|^all$)\') && !item_disable_custom_grid_5 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_5_custom\']'
            ],[
              'label' => 'Grid #5 Panel',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_5_custom_panel_style','grid_5_custom_panel_card_offset','grid_5_custom_panel_padding'],
              'show' => '$match(show_custom_settings, \'(^grids_panels$|^all$)\') && !item_disable_custom_grid_5 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_5_custom\']'
            ],[
              'label' => 'Grid #5 Image',
              'type' => 'group',
              'divider' => true,
              'fields' => [[
                  'label' => 'Width/Height',
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.',
                  'type' => 'grid',
                  'width' => '1-2',
                  'fields' => ['grid_5_custom_image_width','grid_5_custom_image_height']
                ],'grid_5_custom_image_border','grid_5_custom_icon_width','grid_5_custom_icon_color','grid_5_custom_image_align','grid_5_custom_image_grid_width','grid_5_custom_image_grid_column_gap','grid_5_custom_image_grid_row_gap','grid_5_custom_image_grid_breakpoint','grid_5_custom_image_vertical_align','grid_5_custom_image_margin_top','grid_5_custom_image_margin_bottom','grid_5_custom_image_svg_inline','grid_5_custom_image_svg_animate','grid_5_custom_image_svg_color'],
              'show' => '$match(show_custom_settings, \'(^grids_images$|^all$)\') && !item_disable_custom_grid_5 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_5_custom\']'
            ],[
              'label' => 'Grid #6',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_6_custom_item_position','grid_6_custom_item_column_gap','grid_6_custom_item_row_gap','grid_6_custom_item_divider_top','grid_6_custom_item_divider','grid_6_custom_item_column_align','grid_6_custom_item_row_align','grid_6_custom_item_grid_match','grid_6_custom_item_text_align','grid_6_custom_item_text_align_breakpoint','grid_6_custom_item_text_align_fallback','grid_6_custom_item_margin','grid_6_custom_item_visibility'],
              'show' => '$match(show_custom_settings, \'(^grids_main$|^all$)\') && !item_disable_custom_grid_6 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_6_custom\']'
            ],[
              'label' => 'Grid #6 Columns',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_6_custom_item_default','grid_6_custom_item_small','grid_6_custom_item_medium','grid_6_custom_item_large','grid_6_custom_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^grids_columns$|^all$)\') && !item_disable_custom_grid_6 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_6_custom\']'
            ],[
              'label' => 'Grid #6 Panel',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_6_custom_panel_style','grid_6_custom_panel_card_offset','grid_6_custom_panel_padding'],
              'show' => '$match(show_custom_settings, \'(^grids_panels$|^all$)\') && !item_disable_custom_grid_6 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_6_custom\']'
            ],[
              'label' => 'Grid #6 Image',
              'type' => 'group',
              'divider' => true,
              'fields' => [[
                  'label' => 'Width/Height',
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.',
                  'type' => 'grid',
                  'width' => '1-2',
                  'fields' => ['grid_6_custom_image_width','grid_6_custom_image_height']
                ],'grid_6_custom_image_border','grid_6_custom_icon_width','grid_6_custom_icon_color','grid_6_custom_image_align','grid_6_custom_image_grid_width','grid_6_custom_image_grid_column_gap','grid_6_custom_image_grid_row_gap','grid_6_custom_image_grid_breakpoint','grid_6_custom_image_vertical_align','grid_6_custom_image_margin_top','grid_6_custom_image_margin_bottom','grid_6_custom_image_svg_inline','grid_6_custom_image_svg_animate','grid_6_custom_image_svg_color'],
              'show' => '$match(show_custom_settings, \'(^grids_images$|^all$)\') && !item_disable_custom_grid_6 && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_6_custom\']'
            ],[
              'label' => 'Nested Sliders Are Not Allowed',
              'type' => 'group',
              'divider' => true,
              'description' => '<code>NOTE:</code>Disable element slider to use nested grid sliders.',
              'show' => '$match(show_custom_settings, \'(^grids_sliders$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'slider\']'
            ],[
              'label' => 'Nested Grids Sliders',
              'description' => '<code>IMPORTANT:</code> thouse settings will be ignored if the element slider is enabled. Impossible to use a slider inside another slider.',
              'type' => 'group',
              'divider' => true,
              'fields' => ['grid_1_custom_item_slider','grid_2_custom_item_slider','grid_3_custom_item_slider','grid_4_custom_item_slider','grid_5_custom_item_slider','grid_6_custom_item_slider'],
              'show' => '$match(show_custom_settings, \'(^grids_sliders$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && (this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_1_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_2_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_3_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_4_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_5_custom\'] || this.builder.parent(this.node)[\'props\'][\'advanced_enable_grid_6_custom\']) && !this.builder.parent(this.node)[\'props\'][\'slider\']'
            ],[
              'label' => 'Field Sets not Enabled',
              'type' => 'group',
              'divider' => true,
              'description' => '<code>NOTE:</code>To activate target grid overrides please enable some custom field sets and one of their fields under the element content tab.',
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$)\') && (!this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] || (!this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && !this.builder.parent(this.node)[\'props\'][\'show_custom_20\']))'
            ],[
              'label' => 'Field Set #1',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_1_grid_item','custom_1_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_1\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_1\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_1\'])'
            ],[
              'label' => 'Mixed Width Not Enabled',
              'type' => 'group',
              'divider' => true,
              'description' => '<code>NOTE:</code>To activate Mixed Width overrides please enable related options in the custom grids settings tab.',
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$)\') && (!this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] || !this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] || !this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'])'
            ],[
              'label' => 'Field Set #1 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_1_mixed_width_item_default','custom_1_mixed_width_item_small','custom_1_mixed_width_item_medium','custom_1_mixed_width_item_large','custom_1_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_1\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_1\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_1\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_1\'])'
            ],[
              'label' => 'Field Set #2',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_2_grid_item','custom_2_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_2\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_2\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_2\'])'
            ],[
              'label' => 'Field Set #2 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_2_mixed_width_item_default','custom_2_mixed_width_item_small','custom_2_mixed_width_item_medium','custom_2_mixed_width_item_large','custom_2_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_2\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_2\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_2\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_2\'])'
            ],[
              'label' => 'Field Set #3',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_3_grid_item','custom_3_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_3\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_3\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_3\'])'
            ],[
              'label' => 'Field Set #3 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_3_mixed_width_item_default','custom_3_mixed_width_item_small','custom_3_mixed_width_item_medium','custom_3_mixed_width_item_large','custom_3_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_3\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_3\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_3\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_3\'])'
            ],[
              'label' => 'Field Set #4',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_4_grid_item','custom_4_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_4\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_4\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_4\'])'
            ],[
              'label' => 'Field Set #4 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_4_mixed_width_item_default','custom_4_mixed_width_item_small','custom_4_mixed_width_item_medium','custom_4_mixed_width_item_large','custom_4_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_4\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_4\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_4\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_4\'])'
            ],[
              'label' => 'Field Set #5',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_5_grid_item','custom_5_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_5\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_5\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_5\'])'
            ],[
              'label' => 'Field Set #5 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_5_mixed_width_item_default','custom_5_mixed_width_item_small','custom_5_mixed_width_item_medium','custom_5_mixed_width_item_large','custom_5_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_5\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_5\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_5\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_5\'])'
            ],[
              'label' => 'Field Set #6',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_6_grid_item','custom_6_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_6\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_6\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_6\'])'
            ],[
              'label' => 'Field Set #6 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_6_mixed_width_item_default','custom_6_mixed_width_item_small','custom_6_mixed_width_item_medium','custom_6_mixed_width_item_large','custom_6_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_6\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_6\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_6\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_6\'])'
            ],[
              'label' => 'Field Set #7',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_7_grid_item','custom_7_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_7\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_7\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_7\'])'
            ],[
              'label' => 'Field Set #7 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_7_mixed_width_item_default','custom_7_mixed_width_item_small','custom_7_mixed_width_item_medium','custom_7_mixed_width_item_large','custom_7_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_7\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_7\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_7\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_7\'])'
            ],[
              'label' => 'Field Set #8',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_8_grid_item','custom_8_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_8\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_8\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_8\'])'
            ],[
              'label' => 'Field Set #8 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_8_mixed_width_item_default','custom_8_mixed_width_item_small','custom_8_mixed_width_item_medium','custom_8_mixed_width_item_large','custom_8_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_8\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_8\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_8\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_8\'])'
            ],[
              'label' => 'Field Set #9',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_9_grid_item','custom_9_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_9\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_9\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_9\'])'
            ],[
              'label' => 'Field Set #9 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_9_mixed_width_item_default','custom_9_mixed_width_item_small','custom_9_mixed_width_item_medium','custom_9_mixed_width_item_large','custom_9_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_9\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_9\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_9\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_9\'])'
            ],[
              'label' => 'Field Set #10',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_10_grid_item','custom_10_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_10\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_10\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_10\'])'
            ],[
              'label' => 'Field Set #10 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_10_mixed_width_item_default','custom_10_mixed_width_item_small','custom_10_mixed_width_item_medium','custom_10_mixed_width_item_large','custom_10_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_10\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_10\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_10\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_10\'])'
            ],[
              'label' => 'Field Set #11',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_11_grid_item','custom_11_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_11\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_11\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_11\'])'
            ],[
              'label' => 'Field Set #11 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_11_mixed_width_item_default','custom_11_mixed_width_item_small','custom_11_mixed_width_item_medium','custom_11_mixed_width_item_large','custom_11_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_11\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_11\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_11\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_11\'])'
            ],[
              'label' => 'Field Set #12',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_12_grid_item','custom_12_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_12\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_12\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_12\'])'
            ],[
              'label' => 'Field Set #12 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_12_mixed_width_item_default','custom_12_mixed_width_item_small','custom_12_mixed_width_item_medium','custom_12_mixed_width_item_large','custom_12_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_12\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_12\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_12\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_12\'])'
            ],[
              'label' => 'Field Set #13',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_13_grid_item','custom_13_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_13\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_13\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_13\'])'
            ],[
              'label' => 'Field Set #13 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_13_mixed_width_item_default','custom_13_mixed_width_item_small','custom_13_mixed_width_item_medium','custom_13_mixed_width_item_large','custom_13_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_13\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_13\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_13\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_13\'])'
            ],[
              'label' => 'Field Set #14',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_14_grid_item','custom_14_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_14\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_14\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_14\'])'
            ],[
              'label' => 'Field Set #14 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_14_mixed_width_item_default','custom_14_mixed_width_item_small','custom_14_mixed_width_item_medium','custom_14_mixed_width_item_large','custom_14_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_14\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_14\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_14\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_14\'])'
            ],[
              'label' => 'Field Set #15',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_15_grid_item','custom_15_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_15\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_15\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_15\'])'
            ],[
              'label' => 'Field Set #15 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_15_mixed_width_item_default','custom_15_mixed_width_item_small','custom_15_mixed_width_item_medium','custom_15_mixed_width_item_large','custom_15_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_15\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_15\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_15\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_15\'])'
            ],[
              'label' => 'Field Set #16',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_16_grid_item','custom_16_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_16\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_16\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_16\'])'
            ],[
              'label' => 'Field Set #16 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_16_mixed_width_item_default','custom_16_mixed_width_item_small','custom_16_mixed_width_item_medium','custom_16_mixed_width_item_large','custom_16_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_16\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_16\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_16\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_16\'])'
            ],[
              'label' => 'Field Set #17',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_17_grid_item','custom_17_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_17\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_17\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_17\'])'
            ],[
              'label' => 'Field Set #17 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_17_mixed_width_item_default','custom_17_mixed_width_item_small','custom_17_mixed_width_item_medium','custom_17_mixed_width_item_large','custom_17_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_17\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_17\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_17\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_17\'])'
            ],[
              'label' => 'Field Set #18',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_18_grid_item','custom_18_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_18\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_18\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_18\'])'
            ],[
              'label' => 'Field Set #18 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_18_mixed_width_item_default','custom_18_mixed_width_item_small','custom_18_mixed_width_item_medium','custom_18_mixed_width_item_large','custom_18_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_18\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_18\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_18\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_18\'])'
            ],[
              'label' => 'Field Set #19',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_19_grid_item','custom_19_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_19\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_19\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_19\'])'
            ],[
              'label' => 'Field Set #19 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_19_mixed_width_item_default','custom_19_mixed_width_item_small','custom_19_mixed_width_item_medium','custom_19_mixed_width_item_large','custom_19_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_19\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_19\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_19\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_19\'])'
            ],[
              'label' => 'Field Set #20',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_20_grid_item','custom_20_visibility_item'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_targets$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_20\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_20\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_20\'])'
            ],[
              'label' => 'Field Set #20 Mixed Width',
              'type' => 'group',
              'divider' => true,
              'fields' => ['custom_20_mixed_width_item_default','custom_20_mixed_width_item_small','custom_20_mixed_width_item_medium','custom_20_mixed_width_item_large','custom_20_mixed_width_item_xlarge'],
              'show' => '$match(show_custom_settings, \'(^fieldsets_mixed_width$|^all$)\') && this.builder.parent(this.node)[\'props\'][\'use_custom_fields\'] && this.builder.parent(this.node)[\'props\'][\'advanced_grid\'] && this.builder.parent(this.node)[\'props\'][\'advanced_enable_mixed_width\'] && this.builder.parent(this.node)[\'props\'][\'show_custom_20\'] && (this.builder.parent(this.node)[\'props\'][\'show_custom_text_20\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_meta_20\'] || this.builder.parent(this.node)[\'props\'][\'show_custom_image_20\'])'
            ],[
              'label' => 'Item Mixed Width',
              'description' => 'You can combine <code>.uk-child-width-*</code> classes with <code>.uk-width-*</code> classes for individual items. That way it is possible, for example, to create a grid with one item that has a specific width and all other items expanding to fill the remaining space.',
              'type' => 'group',
              'divider' => true,
              'fields' => ['item_width_default','item_width_small','item_width_medium','item_width_large','item_width_xlarge'],
              'show' => '$match(show_custom_settings, \'(^item_mixed_width$|^all$)\')'
            ],[
              'label' => 'Item Panel',
              'type' => 'group',
              'divider' => true,
              'fields' => ['panel_style','item_element'],
              'show' => '$match(show_custom_settings, \'(^item_panel$|^all$)\')'
            ]]
        ],[
          'title' => 'Advanced',
          'fields' => ['name','status','source','item_id','item_class','item_class_tag_in_container','item_attrs_tag','item_attrs_tag_in_container','link_woo','link_woo_sku','link_woo_quantity']
        ]]
    ]
  ],
  'panels' => [
    'fs_focal_point_settings' => [
      'title' => 'Image Focal Point',
      'width' => 500,
      'fields' => [
        'lightbox_image_focal_point' => [
          'label' => 'Lightbox Image Focal Point',
          'description' => '<code>NOTE:</code>Lightbox width and height should be settled.<br />Set a focal point to adjust the image focus when cropping.',
          'type' => 'select',
          'options' => [
            'Top Left' => 'top-left',
            'Top Center' => 'top-center',
            'Top Right' => 'top-right',
            'Center Left' => 'center-left',
            'Center Center' => '',
            'Center Right' => 'center-right',
            'Bottom Left' => 'bottom-left',
            'Bottom Center' => 'bottom-center',
            'Bottom Right' => 'bottom-right'
          ],
          'source' => true,
          'enable' => 'this.builder.parent(this.node)[\'props\'][\'lightbox\'] && image'
        ],
        'image_focal_point' => [
          'label' => 'Image Focal Point',
          'description' => '<code>NOTE:</code>Image width and height should be settled.<br />Set a focal point to adjust the image focus when cropping.',
          'type' => 'select',
          'options' => [
            'Top Left' => 'top-left',
            'Top Center' => 'top-center',
            'Top Right' => 'top-right',
            'Center Left' => 'center-left',
            'Center Center' => '',
            'Center Right' => 'center-right',
            'Bottom Left' => 'bottom-left',
            'Bottom Center' => 'bottom-center',
            'Bottom Right' => 'bottom-right'
          ],
          'source' => true,
          'enable' => 'this.builder.parent(this.node)[\'props\'][\'show_image\'] && image'
        ]
      ],
      'fieldset' => [
        'default' => [
          'fields' => ['lightbox_image_focal_point','image_focal_point']
        ]
      ]
    ]
  ]
];
