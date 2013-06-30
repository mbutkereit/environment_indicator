<?php

/**
 * @file
 * Administrative page callbacks for the environment_indicator module.
 */

use Drupal\environment_indicator\Plugin\Core\Entity\EnvironmentIndicator;

/**
 * Page callback; Lists available EnvironmentIndicator objects.
 */
function environment_indicator_list_page() {
  $controller = entity_list_controller('environment_indicator');
  return $controller->render();
}

/**
 * Page callback: Presents the EnvironmentIndicator creation form.
 *
 * @return array
 *   A form array as expected by drupal_render().
 */
function environment_indicator_add_page() {
  $entity = entity_create('environment_indicator', array());
  return entity_get_form($entity);
}

/**
 * Page callback: Presents the EnvironmentIndicator edit form.
 *
 * @param Drupal\environment_indicator\Plugin\Core\Entity\EnvironmentIndicator $environment_indicator
 *   The EnvironmentIndicator object to edit.
 *
 * @return array
 *   A form array as expected by drupal_render().
 */
function environment_indicator_edit_page(EnvironmentIndicator $environment_indicator) {
  drupal_set_title(format_string('Edit %label', array('%label' => $environment_indicator->label())), PASS_THROUGH);
  return entity_get_form($environment_indicator);
}

/**
 * Form constructor to delete a EnvironmentIndicator object.
 *
 * @param Drupal\environment_indicator\Plugin\Core\Entity\EnvironmentIndicator $environment_indicator
 *   The EnvironmentIndicator object to delete.
 */
function environment_indicator_delete_form($form, &$form_state, EnvironmentIndicator $environment_indicator) {
  $form_state['environment_indicator'] = $environment_indicator;

  $form['id'] = array('#type' => 'value', '#value' => $environment_indicator->id());
  return confirm_form($form,
    format_string('Are you sure you want to delete %label', array('%label' => $environment_indicator->label())),
    'admin/config/development/environment-indicator',
    NULL,
    'Delete'
  );
}

/**
 * Form submission handler for environment_indicator_delete_form().
 */
function environment_indicator_delete_form_submit($form, &$form_state) {
  $form_state['environment_indicator']->delete();
  drupal_set_message(format_string('%label configuration has been deleted.', array('%label' => $form_state['environment_indicator']->label())));
  $form_state['redirect'] = 'admin/config/development/environment-indicator';
}

/**
 * Form builder for the environment indicator delete confirmation form.
 *
 * @ingroup forms
 * @see environment_indicator_confirm_delete_submit()
 */
function environment_indicator_confirm_delete($form, &$form_state, $name) {
  $environment = environment_indicator_load($name);

  // Always provide entity id in the same form key as in the entity edit form.
  $form['name'] = array('#type' => 'value', '#value' => $name);

  $form_state['environment_indicator'] = $environment;
  $form['#id'] = 'environment_indicator_confirm_delete';
  $form['type'] = array('#type' => 'value', '#value' => 'environment_indicator');
  $form['human_name'] = array('#type' => 'value', '#value' => $environment->getHumanName());
  $form['#submit'] = array('environment_indicator_confirm_delete_submit');
  return confirm_form($form,
    t('Are you sure you want to delete the environment indicator %title?',
      array('%title' => $environment->getHumanName())),
    'admin/config/development/environment-indicator/manage',
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel'));
}

/**
 * Submit handler to delete an environment indicator after confirmation.
 *
 * @see environment_indicator_confirm_delete()
 */
function environment_indicator_confirm_delete_submit($form, &$form_state) {
  $status = environment_indicator_delete($form_state['values']['name']);
  drupal_set_message(t('Deleted environment indicator %name.', array('%name' => $form_state['values']['human_name'])));
  watchdog('environment_indicator', 'Deleted environment indicator %name.', array('%name' => $form_state['values']['human_name']), WATCHDOG_NOTICE);
  $form_state['redirect'] = 'admin/config/development/environment-indicator/manage';
  return;
}

/**
 * General settings form.
 */
function environment_indicator_settings() {
  $form['environment_indicator_suppress_pages'] = array(
    '#type' => 'textarea',
    '#title' => t('Turn off Environment Indicator on these pages'),
    '#default_value' => variable_get('environment_indicator_suppress_pages', ""),
    '#description' => t("Enter one page per line as Drupal paths. The '*' character is a wildcard. Example paths are '<em>blog</em>' for the blog page and '<em>blog/*</em>' for every personal blog. '<em>&lt;front&gt;</em>' is the front page."),
  );
  return system_settings_form($form);
}