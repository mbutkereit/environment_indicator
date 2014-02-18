<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\EnvironmentIndicatorListController.
 */

namespace Drupal\environment_indicator;

use Drupal\Core\Config\Entity\ConfigEntityListController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a listing of environments.
 */
class EnvironmentIndicatorListController extends ConfigEntityListController implements FormInterface {
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
    $row = array();

    // Override default values to markup elements.
    $row['#attributes']['class'][] = 'draggable';

    $row['name'] = array(
      'data' => array('#markup' => $entity->get('name')),
    );
    $row['regexurl'] = array(
      'data' => array('#markup' => $entity->get('regexurl')),
    );
    $row['#weight'] = $entity->get('weight');

    // Add weight column.
    $row['weight'] = array(
      '#type' => 'weight',
      '#title' => t('Weight for @title', array('@title' => $entity->label())),
      '#title_display' => 'invisible',
      '#default_value' => $entity->get('weight'),
      '#attributes' => array('class' => array('weight')),
    );

    // Add color column.
    $row['color'] = array(
      'data' => array(
        '#type' => 'html_tag',
        '#tag' => 'pre',
        '#value' => $entity->get('color'),
        '#attributes' => array(
          'class' => array('environment-indicator-color'),
          'style' => 'border: 3px solid ' . $entity->get('color') . ';',
        ),
      )
    );
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
    if (count($entities) > 1) {
      // Creates a form for manipulating environment weights if more than one
      // environment exists.
      return drupal_get_form($this);
    }
    $header = $this->buildHeader();
    unset($header['weight']);
    $build = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => array(),
      '#empty' => t('No environments available. <a href="@link">Add environment indicator</a>.', array('@link' => url('admin/config/development/environment-indicator/add'))),
    );
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
  public function buildForm(array $form, array &$form_state) {
    $form['environments'] = array(
      '#type' => 'table',
      '#header' => $this->buildHeader(),
      '#tabledrag' => array(
        array(
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'weight',
        ),
      ),
        '#attributes' => array(
        'id' => 'environment',
      ),
      '#empty' => t('No environments available. <a href="@link">Add environment indicator</a>.', array('@link' => url('admin/config/development/environment-indicator/add'))),
    );

    foreach ($this->load() as $entity) {
      $form['environments'][$entity->id()] = $this->buildRow($entity);
    }

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
      '#button_type' => 'primary',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, array &$form_state) {
    // No validation.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $environments = $form_state['values']['environments'];

    $entities = entity_load_multiple($this->entityTypeId, array_keys($environments));
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
