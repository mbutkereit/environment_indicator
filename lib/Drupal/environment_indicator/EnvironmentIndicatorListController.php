<?php

/**
 * @file
 * Definition of Drupal\environment_indicator\EnvironmentIndicatorListController.
 */

namespace Drupal\environment_indicator;

use Drupal\Core\Config\Entity\ConfigEntityListController;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of contact categories.
 */
class EnvironmentIndicatorListController extends ConfigEntityListController {

  /**
   * Overrides Drupal\Core\Entity\EntityListController::load();
   */
  public function load() {
    $entities = array(
      'enabled' => array(),
      'disabled' => array(),
    );
    foreach (parent::load() as $entity) {
      if ($entity->isEnabled()) {
        $entities['enabled'][] = $entity;
      }
      else {
        $entities['disabled'][] = $entity;
      }
    }
    return $entities;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityListController::buildRow();
   */
  public function buildRow(EntityInterface $entity) {
    return array(
      'data' => array(
        'name' => $entity->get('human_name'),
        'description' => $entity->get('description'),
        'operations' => array(
          'data' => $this->buildOperations($entity),
        ),
      ),
      'title' => t('Machine name: ') . $entity->id(),
      'class' => array($entity->isEnabled() ? 'views-ui-list-enabled' : 'views-ui-list-disabled'),
    );
  }


  /**
   * Overrides Drupal\Core\Entity\EntityListController::buildHeader().
   */
  public function buildHeader() {
    $row['name'] = t('Name');
    $row['description'] = t('Description');
    $row['operations'] = t('Operations');
    return $row;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityListController::getOperations().
   */
  public function getOperations(EntityInterface $entity) {
    $uri = $entity->uri();
    $path = $uri['path'];

    $definition['edit'] = array(
      'title' => t('Edit'),
      'href' => "$path/edit",
      'weight' => -5,
    );
    if (!$entity->isEnabled()) {
      $definition['enable'] = array(
        'title' => t('Enable'),
        'ajax' => TRUE,
        'token' => TRUE,
        'href' => "$path/enable",
        'weight' => -10,
      );
    }
    else {
      $definition['disable'] = array(
        'title' => t('Disable'),
        'ajax' => TRUE,
        'token' => TRUE,
        'href' => "$path/disable",
        'weight' => 0,
      );
    }
    // This property doesn't exist yet.
    if (!empty($entity->overridden)) {
      $definition['revert'] = array(
        'title' => t('Revert'),
        'href' => "$path/revert",
        'weight' => 5,
      );
    }
    else {
      $definition['delete'] = array(
        'title' => t('Delete'),
        'href' => "$path/delete",
        'weight' => 10,
      );
    }
    return $definition;
  }


  /**
   * Overrides Drupal\Core\Entity\EntityListController::buildOperations();
   */
  public function buildOperations(EntityInterface $entity) {
    $build = parent::buildOperations($entity);

    // Allow operations to specify that they use AJAX.
    foreach ($build['#links'] as &$operation) {
      if (!empty($operation['ajax'])) {
        $operation['attributes']['class'][] = 'use-ajax';
      }
    }

    // Use the dropbutton #type.
    unset($build['#theme']);
    $build['#type'] = 'dropbutton';

    return $build;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityListController::render();
   */
  public function render() {
    $entities = $this->load();
    $list['#type'] = 'container';
    //$list['#attached']['css'] = ViewFormControllerBase::getAdminCSS();
    $list['#attached']['library'][] = array('system', 'drupal.ajax');
    $list['#attributes']['id'] = 'environment-indicator-entity-list';
    $list['enabled']['heading']['#markup'] = '<h2>' . t('Enabled') . '</h2>';
    $list['disabled']['heading']['#markup'] = '<h2>' . t('Disabled') . '</h2>';
    foreach (array('enabled', 'disabled') as $status) {
      $list[$status]['#type'] = 'container';
      $list[$status]['#attributes'] = array('class' => array('views-list-section', $status));
      $list[$status]['table'] = array(
        '#theme' => 'table',
        '#header' => $this->buildHeader(),
        '#rows' => array(),
      );
      foreach ($entities[$status] as $entity) {
        $list[$status]['table']['#rows'][$entity->id()] = $this->buildRow($entity);
      }
    }
    // @todo Use a placeholder for the entity label if this is abstracted to
    // other entity types.
    $list['enabled']['table']['#empty'] = t('There are no enabled environment indicators.');
    $list['disabled']['table']['#empty'] = t('There are no disabled environment indicators.');

    return $list;
  }
  
  

}
