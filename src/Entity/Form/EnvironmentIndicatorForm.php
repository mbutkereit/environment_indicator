<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\Form\EnvironmentIndicatorForm.
 */

namespace Drupal\environment_indicator\Entity\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityForm;

/**
 * Class EnvironmentIndicatorForm.
 *
 * @package Drupal\environment_indicator\Entity\Form
 */
class EnvironmentIndicatorForm extends EntityForm {

  /**
   * This actually builds your form.
   */
  public function form(array $form, FormStateInterface $form_state) {
    $environment_indicator = $this->getEntity();
    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => $environment_indicator->label(),
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#machine_name' => [
        'source' => ['label'],
        'exists' => 'environment_indicator_load',
      ],
      '#default_value' => $environment_indicator->id(),
      '#disabled' => !empty($environment_indicator->id),
    ];
    $form['regexurl'] = [
      '#type' => 'textfield',
      '#title' => t('Hostname'),
      '#description' => t('The hostname you want to detect. You can use a regular expression in this field. This regular expression will be run against the current URL to determine wether the environment is active or not. If you use a regular expression here this environment will <strong>not be availabe</strong> for environment switch.'),
      '#default_value' => $environment_indicator->regexurl,
    ];
    $form['color_picker'] = [
      '#markup' => '<div id="environment-indicator-color-picker"></div>',
    ];
    $form['color'] = [
      '#type' => 'textfield',
      '#title' => t('Color'),
      '#description' => t('Color for the indicator. Ex: #D0D0D0.'),
      '#default_value' => $environment_indicator->color ?: '#D0D0D0',
      '#attached' => [
        // Add Farbtastic color picker.
        'library' => [
          'core/jquery.farbtastic',
        ],
      ],
    ];
    $form['help'] = [
      '#markup' => t("You don't need to care about position and fixed if you are using the toolbar. If you use the toolbar module, then the environment indicator will be integrated."),
    ];
    $form['position'] = [
      '#title' => t('Position'),
      '#descripyion' => t('Wether you want the indicator at the top or at the bottom.'),
      '#type' => 'radios',
      '#options' => [
        'top' => t('Top'),
        'bottom' => t('Bottom'),
      ],
      '#default_value' => $environment_indicator->position,
    ];
    $form['fixed'] = [
      '#title' => t('Fixed'),
      '#descripyion' => t('Check this if you want the indicator to be positioned fixed.'),
      '#type' => 'checkbox',
      '#default_value' => $environment_indicator->fixed,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submit(array $form, FormStateInterface $form_state) {
    // Build the entity object from the submitted values.
    $entity = parent::submit($form, $form_state);

    return $entity;
  }

  /**
   * Save your config entity.
   *
   * There will eventually be default code to rely on here, but it doesn't exist
   * yet.
   */
  public function save(array $form, FormStateInterface $form_state) {
    $environment = $this->entity;
    $environment->save();
    drupal_set_message(t('Saved the %label environment.', [
      '%label' => $environment->label(),
    ]));

    $form_state->setRedirect('environment_indicator.list');
  }

  /**
   * Delete your config entity.
   *
   * There will eventually be default code to rely on here, but it doesn't exist
   * yet.
   */
  public function delete(array $form, FormStateInterface $form_state) {
    $destination = [];
    if (isset($_GET['destination'])) {
      $destination = drupal_get_destination();
      unset($_GET['destination']);
    }

    $entity = $this->getEntity();
    //@todo fix it
    $form_state->setFormstate([
      'admin/config/development/environment-indicator/manage/' . $entity->id() . '/delete',
      ['query' => $destination]
    ]);
  }

}