<?php
 
/**
 * @file
 * Contains \Drupal\environment_indicator\Form\EnvironmentIndicatorSettingsForm.
 */
 
namespace Drupal\environment_indicator\Form;
 
use Drupal\system\SystemConfigFormBase;
use Drupal\Core\Form\FormInterface;
 
class EnvironmentIndicatorSettingsForm extends SystemConfigFormBase implements FormInterface {
 
  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormID().
   */
  public function getFormID() {
    return 'environment_indicator_settings_form';
  }
 
  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */
  public function buildForm(array $form, array &$form_state) {
    $form = parent::buildForm($form, $form_state);
    $paths = \Drupal::config('environment_indicator.options')->get('suppress_pages');
    $form['suppress_pages'] = array(
      '#title' => t('Disallowed pages'),
      '#description' => t('Enter a list of paths separated by new lines where the environment indicator should not appear. You can use usual drupal path wildcards.'),
      '#type' => 'textarea',
      '#default_value' => $paths,
    );
    return $form;
  }
 
  /**
   * Implements \Drupal\Core\Form\FormInterface::validateForm().
   */
  public function validateForm(array &$form, array &$form_state) {
    parent::validateForm($form, $form_state);
    // Validate the form values.
  }
 
  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
   */
  public function submitForm(array &$form, array &$form_state) {
    parent::submitForm($form, $form_state);
    $paths = $form_state['values']['suppress_pages'];
    $config = \Drupal::config('environment_indicator.options')->set('suppress_pages', $paths);
    $config->save();
  }
 
}