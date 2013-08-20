<?php
namespace Drupal\environment_indicator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityFormController;

class EnvironmentIndicatorFormController extends EntityFormController {
  /**
   * This actually builds your form.
   */
  public function form(array $form, array &$form_state) {
    $environment_indicator = $this->entity;

    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => $environment_indicator->label(),
    );
    $form['machine'] = array(
      '#type' => 'machine_name',
      '#machine_name' => array(
        'source' => array('name'),
        'exists' => 'environment_indicator_load',
      ),
      '#default_value' => $environment_indicator->id(),
      '#disabled' => !empty($environment_indicator->machine),
    );
    $form['regexurl'] = array(
      '#type' => 'textfield',
      '#title' => t('Hostname'),
      '#description' => t('The hostname you want to detect. You can use a regular expression in this field. This regular expression will be run against the current URL to determine wether the environment is active or not. If you use a regular expression here this environment will <strong>not be availabe</strong> for environment switch.'),
      '#default_value' => $environment_indicator->regexurl,
    );
    $form['color_picker'] = array(
      '#markup' => '<div id="environment-indicator-color-picker"></div>',
    );
    $form['color'] = array(
      '#type' => 'textfield',
      '#title' => t('Color'),
      '#description' => t('Color for the indicator. Ex: #D0D0D0.'),
      '#default_value' => $environment_indicator->color ?: '#D0D0D0',
      '#attached' => array(
        // Add Farbtastic color picker.
        'library' => array(
          array('system', 'farbtastic'),
        ),
      ),
    );
    $form['help'] = array(
      '#markup' => t('You don\'t need to care about position and fixed if you are using the toolbar. If you use the toolbar module, then the environment indicator will be integrated.'),
    );
    $form['position'] = array(
      '#title' => t('Position'),
      '#descripyion' => t('Wether you want the indicator at the top or at the bottom.'),
      '#type' => 'radios',
      '#options' => array(
        'top' => t('Top'),
        'bottom' => t('Bottom'),
      ),
      '#default_value' => $environment_indicator->position,
    );
    $form['fixed'] = array(
      '#title' => t('Fixed'),
      '#descripyion' => t('Check this if you want the indicator to be positioned fixed.'),
      '#type' => 'checkbox',
      '#default_value' => $environment_indicator->fixed,
    );

    return $form;
  }

  /**
   * Save your config entity.
   *
   * There will eventually be default code to rely on here, but it doesn't exist
   * yet.
   */
  public function save(array $form, array &$form_state) {
    $environment = $this->getEntity($form_state);
    $environment->save();
    drupal_set_message(t('Saved the %label environment.', array(
      '%label' => $environment->label(),
    )));

    $form_state['redirect'] = 'admin/config/development/environment-indicator';
  }

  /**
   * Delete your config entity.
   *
   * There will eventually be default code to rely on here, but it doesn't exist
   * yet.
   */
  public function delete(array $form, array &$form_state) {
    $destination = array();
    if (isset($_GET['destination'])) {
      $destination = drupal_get_destination();
      unset($_GET['destination']);
    }

    $entity = $this->getEntity($form_state);
    $form_state['redirect'] = array('admin/config/development/environment-indicator/manage/' . $entity->id() . '/delete', array('query' => $destination));
  }

}