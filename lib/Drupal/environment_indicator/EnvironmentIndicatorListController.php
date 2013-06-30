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
   * Overrides Drupal\Core\Entity\EntityListController::getOperations().
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);
    return $operations;
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
   * Overrides Drupal\Core\Entity\EntityListController::buildRow().
   */
  public function buildRow(EntityInterface $entity) {
   // die(print_r($entity));
    $row['name'] = check_plain($entity->human_name);
    $row['description'] = check_plain($entity->description);
    $row['operations']['data'] = $this->buildOperations($entity);
    return $row;
  }

}
