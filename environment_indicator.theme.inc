<?php

/**
 * @file
 * Theme implementation file.
 */

/**
 * Theme function for the indicator name.
 */
function theme_environment_indicator_indicator_name($variables) {
  $output = '<div class="environment-indicator-name">' . t($variables['human_name']) . '</div>';
  $environments = environment_indicator_get_all(TRUE);
  $active_environment = environment_indicator_get_active();
  foreach ($environments as $name => $environment) {
    if ($name != $active_environment['name']) {
      $items[] = array(
        'link' => array(
          '#type' => 'link',
          '#title' => t('Open in %name', array('%name' => t($environment->get('human_name')))),
          '#href' => 'http://' . $environment->get('regexurl') . '/' . current_path(),
          '#options' => array(
            'html' => TRUE,
          ),
        ),
        '#wrapper_attributes' => array(
          'class' => array('environment-switcher')
        ),
      );
    }
  }
  if (!empty($items)) {
    $switcher = theme('item_list', array(
      'items' => $items,
      'attributes' => array(
        'class' => array('environment-switcher-container'),
      ),
    ));
    $output .= $switcher;
  }
  return $output;
}

/**
 * Theme function for the indicator bar.
 */
function theme_environment_indicator_indicator_bar($variables) {
  $info = $variables['info'];
  $output = array(
    '#type' => 'container',
    '#attributes' => array(
      'class' => array(
        drupal_html_class('environment-indicator-' . $info['name']),
        'position-' . $info['position'],
        'fixed-' . ($info['fixed'] ? 'yes' : 'no'),
      ),
      'id' => 'environment-indicator',
      'style' => 'background-color: ' . $info['color'],
    ),
    'name' => array(
      '#markup' => '<div class="gradient-bar">' . theme('environment_indicator_indicator_name', $info) . '</div>',
    ),
  );
  return drupal_render($output);
}
