<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\Form\EnvironmentIndicatorDeleteForm.
 */

namespace Drupal\environment_indicator\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a deletion confirmation form for environment_indicator environment.
 */
class EnvironmentIndicatorDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'environment_indicator_environment_confirm_delete';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the environment indicator %title?', array('%title' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelPath() {
    return 'admin/config/development/environment-indicator';
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Deleting a environment will make disappear the indicator.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submit(array $form, array &$form_state) {
    $this->entity->delete();
    drupal_set_message(t('Deleted environment %name.', array('%name' => $this->entity->label())));
    watchdog('environment', 'Deleted environment %name.', array('%name' => $this->entity->label()), WATCHDOG_NOTICE);
    $form_state['redirect'] = 'admin/config/development/environment-indicator';
    Cache::invalidateTags(array('content' => TRUE));
  }

}
