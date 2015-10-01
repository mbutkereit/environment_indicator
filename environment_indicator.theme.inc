<?php

/**
 * @file
 * Theme implementation file.
 */

/**
 * Theme function for the indicator name.
 */
function theme_environment_indicator_indicator_name($variables) {
  $output = '<div class="environment-indicator-name">' . t($variables['name']) . '</div>';
  $environments = environment_indicator_get_all(TRUE);
  $active_environment = environment_indicator_get_active();
  foreach ($environments as $machine => $environment) {
    if ($machine != $active_environment['machine']) {
      $items[] = [
        'link' => [
          '#type' => 'link',
          '#title' => t('Open in %name', ['%name' => t($environment->label())]),
          '#href' => 'http://' . $environment->get('regexurl') . '/' . \Drupal::request()->getRequestUri(),
          '#options' => [
            'html' => TRUE,
          ],
        ],
        '#wrapper_attributes' => [
          'class' => ['environment-switcher']
        ],
      ];
    }
  }
  if (!empty($items)) {
    $switcher = [
      '#theme' => 'item_list',
      '#items' => $items,
      '#attributes' => [
        'class' => ['environment-switcher-container'],
      ],
    ];
    $output .= drupal_render($switcher);
  }
  return $output;
}

/**
 * Theme function for the indicator bar.
 */
function theme_environment_indicator_indicator_bar($variables) {
  $info = $variables['info'];
  $output = [
    '#type' => 'container',
    '#attributes' => [
      'class' => [
        drupal_html_class('environment-indicator-' . $info['machine']),
        'position-' . $info['position'],
        'fixed-' . ($info['fixed'] ? 'yes' : 'no'),
      ],
      'id' => 'environment-indicator',
      'style' => 'background-color: ' . $info['color'],
    ],
    'name' => [
      '#theme' => 'environment_indicator_indicator_name',
      '#name' => $info,
      '#prefix' => '<div class="gradient-bar">',
      '#suffix' => '</div>',
    ],
  ];
  return drupal_render($output);
}
