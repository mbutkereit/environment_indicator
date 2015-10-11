<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\Form\EnvironmentIndicatorSettingsForm.
 */

namespace Drupal\environment_indicator\Entity\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EnvironmentIndicatorSettingsForm.
 *
 * @package Drupal\environment_indicator\Entity\Form
 */
class EnvironmentIndicatorSettingsForm extends ConfigFormBase implements FormInterface {

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormID().
   */
  public function getFormID() {
    return 'environment_indicator_settings_form';
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $paths = \Drupal::config('environment_indicator.options')
      ->get('suppress_pages');
    $form['suppress_pages'] = [
      '#title' => t('Disallowed pages'),
      '#description' => t('Enter a list of paths separated by new lines where the environment indicator should not appear. You can use usual drupal path wildcards.'),
      '#type' => 'textarea',
      '#default_value' => $paths,
    ];
    return $form;
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::validateForm().
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    // Validate the form values.
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $paths = $form_state['values']['suppress_pages'];
    $config = \Drupal::config('environment_indicator.options')
      ->set('suppress_pages', $paths);
    $config->save();
  }

  /**
   * TODO: Implement getEditableConfigNames() method.
   */
  protected function getEditableConfigNames() {
  }

}
