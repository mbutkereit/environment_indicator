<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\EnvironmentIndicatorListController.
 */

namespace Drupal\environment_indicator\Entity\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a listing of environments.
 */
class EnvironmentIndicatorListController extends ConfigEntityListBuilder implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'environment_indicator_overview_environments';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $row['name'] = t('Environment name');
    $row['regexurl'] = t('Environment url');
    $row['weight'] = t('Weight');
    $row['color'] = t('Color');
    $row += parent::buildHeader();
    unset($row['id']);
    unset($row['label']);
    return $row;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row = [];

    // Override default values to markup elements.
    $row['#attributes']['class'][] = 'draggable';

    $row['name'] = [
      'data' => ['#markup' => $entity->get('label')],
    ];
    $row['regexurl'] = [
      'data' => ['#markup' => $entity->get('regexurl')],
    ];
    $row['#weight'] = $entity->get('weight');

    // Add weight column.
    $row['weight'] = [
      '#type' => 'weight',
      '#title' => t('Weight for @title', ['@title' => $entity->label()]),
      '#title_display' => 'invisible',
      '#default_value' => $entity->get('weight'),
      '#attributes' => ['class' => ['weight']],
    ];

    // Add color column.
    $row['color'] = [
      'data' => [
        '#type' => 'html_tag',
        '#tag' => 'pre',
        '#value' => $entity->get('color'),
        '#attributes' => [
          'class' => ['environment-indicator-color'],
          'style' => 'border: 3px solid ' . $entity->get('color') . ';',
        ],
      ]
    ];
    $row += parent::buildRow($entity);
    unset($row['id']);
    unset($row['label']);
    return $row;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $entities = $this->load();
    $header = $this->buildHeader();
    unset($header['weight']);
    $build = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => [],
      '#empty' => t('No environments available. <a href="@link">Add environment indicator</a>.', ['@link' => \Drupal::url('environment_indicator.add')]),
    ];
    foreach ($entities as $entity) {
      $row = $this->buildRow($entity);
      unset($row['#weight']);
      unset($row['weight']);
      unset($row['#attributes']);
      $build['#rows'][$entity->id()]['data'] = $row;
    }
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['environments'] = [
      '#type' => 'table',
      '#header' => $this->buildHeader(),
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'weight',
        ],
      ],
      '#attributes' => [
        'id' => 'environment',
      ],
      '#empty' => t('No environments available. <a href="@link">Add environment indicator</a>.', ['@link' => url('admin/config/development/environment-indicator/add')]),
    ];

    foreach ($this->load() as $entity) {
      $form['environments'][$entity->id()] = $this->buildRow($entity);
    }

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // No validation.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $environments = $form_state['values']['environments'];

    $controller = \Drupal::entityManager()->getStorage($this->entityTypeId);
    $entities = $controller->loadMultiple(array_keys($environments));
    foreach ($environments as $id => $value) {
      if (isset($entities[$id]) && $value['weight'] != $entities[$id]->get('weight')) {
        // Update changed weight.
        $entities[$id]->set('weight', $value['weight']);
        $entities[$id]->save();
      }
    }

    drupal_set_message(t('The configuration options have been saved.'));
  }

}
