<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\Form\EnvironmentIndicatorDeleteForm.
 */

namespace Drupal\environment_indicator\Entity\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a deletion confirmation form for environment_indicator environment.
 */
class EnvironmentIndicatorDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the environment indicator %title?', ['%title' => $this->entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelRoute() {
    return [
      'route_name' => 'environment_indicator.list',
      'route_parameters' => [],
    ];
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
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();
    drupal_set_message(t('Deleted environment %name.', ['%name' => $this->entity->label()]));
    \Drupal::logger('my_module')->notice(t('Deleted environment %name.', ['%name' => $this->entity->label()]));
    $form_state->setRedirect('environment_indicator.list');
    Cache::invalidateTags(['content' => TRUE]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return t('Deletef');
  }

}
