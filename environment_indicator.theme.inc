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
  if (!empty($environments)) {
    $destination = drupal_get_destination();
    foreach ($environments as $environment) {
      $items[] = array(
        'data' => l(t('Open in %name', array('%name' => t($environment->name))), 'http://' . $environment->regexurl . '/' . $destination['destination'], array('html' => TRUE)),
        'class' => array('environment-switcher'),
      );
    }
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
        drupal_html_class('environment-indicator-' . $info['machine']),
        'position-' . $info['position'],
        'fixed-' . ($info['fixed'] ? 'yes' : 'no'),
      ),
      'id' => 'environment-indicator',
      'style' => 'background-color: ' . $info['color'],
    ),
    'name' => array(
      '#theme' => 'html_tag',
      '#tag' => 'div',
      '#value' => theme('environment_indicator_indicator_name', $info),
      '#attributes' => array(
        'class' => array('gradient-bar'),
      ),
    ),
  );
  return drupal_render($output);
}
