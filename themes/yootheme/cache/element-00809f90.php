<?php // $file = /home/wushkagm/public_html/wp-content/plugins/fs-toggle/modules/element/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file),
  'name' => 'fs_toggle',
  'title' => 'Toggle',
  'group' => 'Flart Studio',
  'icon' => $filter->apply('url', 'images/icon.svg', $file),
  'iconSmall' => $filter->apply('url', 'images/iconSmall.svg', $file),
  'element' => true,
  'container' => true,
  'fragment' => true,
  'width' => 500,
  'defaults' => [
    'edit_mode' => true,
    'show_link' => true,
    'toggle_state' => 'hidden',
    'toggle_mode' => 'click',
    'toggle_breakpoint' => 'm',
    'toggle_cls' => 'uk-hidden',
    'link_position' => 'top',
    'link_text' => 'Show More',
    'link_text_alt' => 'Show Less',
    'link_style' => 'default',
    'link_icon' => 'eye',
    'link_icon_alt' => 'eye-slash',
    'link_icon_align' => 'left',
    'margin_top' => 'default',
    'margin_bottom' => 'default'
  ],
  'placeholder' => [],
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file),
    'content' => $filter->apply('path', './templates/content.php', $file)
  ],
  'fields' => [
    'edit_mode' => [
      'label' => 'Display',
      'type' => 'checkbox',
      'text' => 'Edit mode'
    ],
    'show_link' => [
      'type' => 'checkbox',
      'text' => 'Show the link',
      'enable' => '!edit_mode && toggle_mode != \'media\''
    ],
    'content' => [
      'type' => 'builder-fragment'
    ],
    'toggle_mode' => [
      'label' => 'Action',
      'description' => 'Define how the toggle is triggered.',
      'type' => 'select',
      'options' => [
        'Click' => 'click',
        'Hover' => 'hover',
        'Click, Hover' => 'click, hover',
        'Media Query' => 'media'
      ]
    ],
    'toggle_rows' => [
      'label' => 'Rows Split',
      'description' => 'Select how many rows should be initially visible or hidden, depending on the toggle state.',
      'type' => 'select',
      'options' => [
        'None' => '',
        'After 1 Row' => '1',
        'After 2 Rows' => '2',
        'After 3 Rows' => '3',
        'After 4 Rows' => '4',
        'After 5 Rows' => '5',
        'After 6 Rows' => '6',
        'After 7 Rows' => '7',
        'After 8 Rows' => '8',
        'After 9 Rows' => '9',
        'After 10 Rows' => '10'
      ]
    ],
    'toggle_state' => [
      'label' => 'Initial State',
      'description' => 'Choose the initial visibility of rows. Based on the selection, rows will either be shown or hidden.',
      'type' => 'select',
      'options' => [
        'Hidden' => 'hidden',
        'Visible' => 'visible'
      ],
      'show' => 'toggle_mode != \'media\''
    ],
    'toggle_cls' => [
      'label' => 'CSS Class to Toggle',
      'description' => 'Specify one or more classes to apply (e.g., uk-hidden, custom-class). Separate multiple classes with spaces.',
      'attrs' => [
        'placeholder' => 'uk-hidden'
      ],
      'source' => true,
      'show' => 'toggle_mode == \'media\''
    ],
    'toggle_breakpoint' => [
      'label' => 'Breakpoint',
      'description' => 'Select the device size at which the toggle class is applied and becomes active.',
      'type' => 'select',
      'options' => [
        'Small (Phone Landscape)' => 's',
        'Medium (Tablet Landscape)' => 'm',
        'Large (Desktop)' => 'l',
        'XLarge (Large Screens)' => 'xl'
      ],
      'show' => 'toggle_mode == \'media\''
    ],
    'toggle_animation' => [
      'label' => 'Animation',
      'description' => 'Apply an animation when the toggle is activated.',
      'type' => 'select',
      'options' => [
        'None' => '',
        'Fade' => 'fade',
        'Scale Up' => 'scale-up',
        'Scale Down' => 'scale-down',
        'Slide Top Small' => 'slide-top-small',
        'Slide Bottom Small' => 'slide-bottom-small',
        'Slide Left Small' => 'slide-left-small',
        'Slide Right Small' => 'slide-right-small',
        'Slide Top Medium' => 'slide-top-medium',
        'Slide Bottom Medium' => 'slide-bottom-medium',
        'Slide Left Medium' => 'slide-left-medium',
        'Slide Right Medium' => 'slide-right-medium',
        'Slide Top 100%' => 'slide-top',
        'Slide Bottom 100%' => 'slide-bottom',
        'Slide Left 100%' => 'slide-left',
        'Slide Right 100%' => 'slide-right'
      ],
      'show' => 'toggle_mode != \'media\''
    ],
    'toggle_margin' => [
      'label' => 'Margin',
      'description' => 'Select the margin size between the toggle button and the content.',
      'type' => 'select',
      'options' => [
        'Small' => 'small',
        'Default' => '',
        'Medium' => 'medium',
        'Large' => 'large',
        'X-Large' => 'xlarge',
        'None' => 'remove'
      ],
      'show' => 'show_link && toggle_mode != \'media\''
    ],
    'toggle_id' => [
      'label' => 'Toggle ID',
      'description' => 'Assign a custom ID for the toggle. A unique one will be generated if left empty.',
      'attrs' => [
        'placeholder' => 'my-toggle-id'
      ],
      'source' => true
    ],
    'toggle_toggle_helper' => [
      'label' => 'Toggle Helper',
      'type' => 'checkbox',
      'description' => 'Automatically adds the required <code>uk-toggle</code> attribute to links using the special link format: <code>#fs-toggle=<i>my-toggle-id</i></code> when manually adding the attribute is not possible.',
      'text' => 'Handle custom toggle links',
      'show' => 'toggle_id'
    ],
    'html_element' => $config->get('builder.html_element_item'),
    'link_position' => [
      'label' => 'Position',
      'description' => 'Select where the toggle button should appear. In \'Dynamic\' mode, the button appears at the top, with an additional button at the bottom if the toggle height exceeds the viewport.',
      'type' => 'select',
      'options' => [
        'Top' => 'top',
        'Bottom' => 'bottom',
        'Dynamic' => 'auto'
      ],
      'enable' => 'show_link && toggle_mode == \'click\''
    ],
    'link_icon' => [
      'label' => 'Icon',
      'description' => 'Choose an icon for the button (optional).',
      'attrs' => [
        'placeholder' => 'eye'
      ],
      'type' => 'icon',
      'source' => true,
      'enable' => 'show_link && toggle_mode != \'media\''
    ],
    'link_icon_alt' => [
      'label' => 'Icon Active',
      'description' => 'Select an icon to display on the button in the active state (optional).',
      'attrs' => [
        'placeholder' => 'eye'
      ],
      'type' => 'icon',
      'source' => true,
      'enable' => 'show_link && link_icon && toggle_mode != \'media\''
    ],
    'link_icon_align' => [
      'label' => 'Icon Alignment',
      'description' => 'Set the position of the icon (if any).',
      'type' => 'select',
      'options' => [
        'Left' => 'left',
        'Right' => 'right'
      ],
      'enable' => 'show_link && link_icon && toggle_mode != \'media\''
    ],
    'link_text' => [
      'label' => 'Text',
      'description' => 'Enter the text for the link.',
      'source' => true,
      'enable' => 'show_link && toggle_mode != \'media\''
    ],
    'link_text_alt' => [
      'label' => 'Text Active',
      'description' => 'Enter the text for the link when it is in the active state (optional).',
      'source' => true,
      'enable' => 'show_link && link_text && toggle_mode != \'media\''
    ],
    'link_title' => [
      'label' => 'Title',
      'description' => 'Enter an optional text for the title attribute of the link, which will appear on hover.',
      'source' => true,
      'enable' => 'show_link && toggle_mode != \'media\''
    ],
    'link_aria_label' => [
      'label' => 'ARIA Label',
      'description' => 'Enter a descriptive text label to make it accessible if the link has no visible text.',
      'source' => true,
      'enable' => 'show_link && !link_text && link_icon && toggle_mode != \'media\''
    ],
    'link_onclick' => [
      'label' => 'Click Action',
      'description' => 'Enter custom JavaScript to run when the link is clicked. The code will be safely wrapped in a try/catch block to avoid breaking core functionality.',
      'attrs' => [
        'placeholder' => 'gtag(\'event\', \'toggle_open\')'
      ],
      'type' => 'text',
      'source' => true,
      'enable' => 'show_link && toggle_mode != \'media\''
    ],
    'link_style' => [
      'label' => 'Style',
      'description' => 'Set the link style.',
      'type' => 'select',
      'options' => [
        'Button Default' => 'default',
        'Button Primary' => 'primary',
        'Button Secondary' => 'secondary',
        'Button Danger' => 'danger',
        'Button Text' => 'text',
        'Link' => '',
        'Link Muted' => 'link-muted',
        'Link Text' => 'link-text'
      ],
      'enable' => 'show_link && toggle_mode != \'media\''
    ],
    'link_size' => [
      'label' => 'Button Size',
      'description' => 'Set the button size.',
      'type' => 'select',
      'options' => [
        'Small' => 'small',
        'Default' => '',
        'Large' => 'large'
      ],
      'enable' => 'show_link && link_style && !$match(link_style, \'link-(muted|text)\') && toggle_mode != \'media\''
    ],
    'link_width' => [
      'label' => 'Width',
      'description' => 'Set the button width.',
      'type' => 'select',
      'options' => [
        'None' => '',
        'Small' => 'small',
        'Medium' => 'medium',
        'Large' => 'large',
        'XLarge' => 'xlarge',
        '2XLarge' => '2xlarge',
        'Full Width' => '1-1'
      ],
      'enable' => 'show_link && link_style && !$match(link_style, \'link-(muted|text)\') && toggle_mode != \'media\''
    ],
    'position' => $config->get('builder.position'),
    'position_left' => $config->get('builder.position_left'),
    'position_right' => $config->get('builder.position_right'),
    'position_top' => $config->get('builder.position_top'),
    'position_bottom' => $config->get('builder.position_bottom'),
    'position_z_index' => $config->get('builder.position_z_index'),
    'blend' => $config->get('builder.blend'),
    'margin_top' => [
      'label' => 'Margin Top',
      'description' => 'Set the top margin.',
      'type' => 'select',
      'options' => [
        'Keep existing' => '',
        'X-Small' => 'xsmall',
        'Small' => 'small',
        'Default' => 'default',
        'Medium' => 'medium',
        'Large' => 'large',
        'X-Large' => 'xlarge',
        'Remove' => 'remove',
        'Auto' => 'auto'
      ],
      'enable' => 'position != \'absolute\''
    ],
    'margin_bottom' => [
      'label' => 'Margin Bottom',
      'description' => 'Set the bottom margin.',
      'type' => 'select',
      'options' => [
        'Keep existing' => '',
        'X-Small' => 'xsmall',
        'Small' => 'small',
        'Default' => 'default',
        'Medium' => 'medium',
        'Large' => 'large',
        'X-Large' => 'xlarge',
        'Remove' => 'remove',
        'Auto' => 'auto'
      ],
      'enable' => 'position != \'absolute\''
    ],
    'maxwidth' => $config->get('builder.maxwidth'),
    'maxwidth_breakpoint' => $config->get('builder.maxwidth_breakpoint'),
    'block_align' => $config->get('builder.block_align'),
    'block_align_breakpoint' => $config->get('builder.block_align_breakpoint'),
    'block_align_fallback' => $config->get('builder.block_align_fallback'),
    'text_align' => $config->get('builder.text_align'),
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'),
    'text_align_fallback' => $config->get('builder.text_align_fallback'),
    'animation' => $config->get('builder.animation'),
    '_parallax_button' => $config->get('builder._parallax_button'),
    'visibility' => $config->get('builder.visibility'),
    'name' => $config->get('builder.name'),
    'status' => $config->get('builder.status'),
    'source' => $config->get('builder.source'),
    'id' => $config->get('builder.id'),
    'class' => $config->get('builder.cls'),
    'attributes' => $config->get('builder.attrs'),
    'css' => [
      'label' => 'CSS',
      'description' => 'The following selectors are automatically prefixed for this element: <code>.el-element</code> (scoped replacement for <code>.fs-toggle</code>), <code>.fs-toggle__container</code>, <code>.fs-toggle__group</code>, <code>.fs-toggle__link</code>, <code>.fs-toggle__link-text</code>, <code>.fs-toggle__link-icon</code>',
      'type' => 'editor',
      'editor' => 'code',
      'mode' => 'css',
      'attrs' => [
        'debounce' => 500,
        'hints' => ['.el-element','.fs-toggle__container','.fs-toggle__group','.fs-toggle__link','.fs-toggle__link-text','.fs-toggle__link-icon']
      ]
    ]
  ],
  'fieldset' => [
    'default' => [
      'type' => 'tabs',
      'fields' => [[
          'title' => 'Content',
          'fields' => ['content','edit_mode','show_link']
        ],[
          'title' => 'Settings',
          'fields' => [[
              'label' => 'Toggle',
              'type' => 'group',
              'divider' => true,
              'fields' => ['toggle_mode','toggle_rows','toggle_state','toggle_cls','toggle_breakpoint','toggle_animation','toggle_margin','toggle_id','toggle_toggle_helper','html_element']
            ],[
              'label' => 'Link',
              'type' => 'group',
              'divider' => true,
              'fields' => ['link_position','link_icon','link_icon_alt','link_icon_align','link_text','link_text_alt','link_title','link_aria_label','link_onclick','link_style','link_size','link_width'],
              'show' => 'show_link'
            ],[
              'label' => 'General',
              'type' => 'group',
              'fields' => ['position','position_left','position_right','position_top','position_bottom','position_z_index','blend','margin_top','margin_bottom','maxwidth','maxwidth_breakpoint','block_align','block_align_breakpoint','block_align_fallback','text_align','text_align_breakpoint','text_align_fallback','animation','_parallax_button','visibility']
            ]]
        ],$config->get('builder.advanced')]
    ]
  ]
];
