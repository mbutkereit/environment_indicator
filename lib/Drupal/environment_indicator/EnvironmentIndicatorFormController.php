<?php

/**
 * @file
 * Definition of Drupal\environment_indicator\EnvironmentIndicatorFormController.
 */

namespace Drupal\environment_indicator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityFormController;

/**
 * Base form controller for environment edit forms.
 */
class EnvironmentIndicatorFormController extends EntityFormController {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::form().
   */
  public function form(array $form, array &$form_state, EntityInterface $environment) {

    // Check whether we need a deletion confirmation form.
    if (isset($form_state['confirm_delete']) && isset($form_state['values']['name'])) {
      return environment_indicator_confirm_delete($form, $form_state, $form_state['values']['name']);
    }
    $form['human_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => $environment->name,
      '#maxlength' => 255,
      '#required' => TRUE,
    );
    $form['name'] = array(
      '#type' => 'machine_name',
      '#default_value' => $environment->id(),
      '#maxlength' => 255,
      '#machine_name' => array(
        'exists' => 'environment_indicator_load',
        'source' => array('human_name'),
      ),
    );
    $form['description'] = array(
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#default_value' => $environment->description,
    );

    return parent::form($form, $form_state, $environment);
  }

  /**
   * Returns an array of supported actions for the current entity form.
   */
  protected function actions(array $form, array &$form_state) {
    // If we are displaying the delete confirmation skip the regular actions.
    if (empty($form_state['confirm_delete'])) {
      $actions = parent::actions($form, $form_state);
      array_unshift($actions['delete']['#submit'], array($this, 'submit'));
      return $actions;
    }
    else {
      return array();
    }
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::validate().
   */
  public function validate(array $form, array &$form_state) {
    parent::validate($form, $form_state);

    // Make sure that the machine name of the environment is not in the
    // disallowed list (names that conflict with menu items, such as 'list'
    // and 'add').
    // During the deletion there is no 'name' key.
    if (isset($form_state['values']['name'])) {
      // Do not allow machine names to conflict with environment indicator
      // path arguments.
      $name = $form_state['values']['name'];
      $disallowed = array('add', 'list', 'manage');
      if (in_array($name, $disallowed)) {
        form_set_error('name', t('The machine-readable name cannot be "add", "list", or "manage".'));
      }
    }
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::submit().
   */
  public function submit(array $form, array &$form_state) {
    if ($form_state['triggering_element']['#value'] == t('Delete')) {
      // Rebuild the form to confirm environment deletion.
      $form_state['rebuild'] = TRUE;
      $form_state['confirm_delete'] = TRUE;
      return NULL;
    }
    else {
      return parent::submit($form, $form_state);
    }
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, array &$form_state) {
    $environment = $this->getEntity($form_state);

    // Prevent leading and trailing spaces in environment names.
    $environment->name = trim($environment->name);

    switch (environment_indicator_save($environment)) {
      case SAVED_NEW:
        drupal_set_message(t('Created new environment %name.', array('%name' => $environment->name)));
        watchdog('environment_indicator', 'Created new environment %name.', array('%name' => $environment->name), WATCHDOG_NOTICE, l(t('edit'), 'admin/config/development/environment-indicator/manage/' . $environment->id() . '/edit'));
        $form_state['redirect'] = 'admin/config/development/environment-indicator';
        break;

      case SAVED_UPDATED:
        drupal_set_message(t('Updated environment %name.', array('%name' => $environment->name)));
        watchdog('environment_indicator', 'Updated environment %name.', array('%name' => $environment->name), WATCHDOG_NOTICE, l(t('edit'), 'admin/config/development/environment-indicator/manage/' . $environment->id() . '/edit'));
        $form_state['redirect'] = 'admin/config/development/environment-indicator/manage';
        break;
    }

    $form_state['values']['name'] = $environment->id();
    $form_state['name'] = $environment->id();
  }
  
  /**
   * Overrides Drupal\Core\Entity\EntityFormController::delete().
   */
  public function delete(array $form, array &$form_state) {
    $entity = $this->getEntity($form_state);
    $form_state['redirect'] = 'admin/config/development/environment-indicator/manage/' . $entity->id() . '/delete';
  }

}
