<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\EnvironmentIndicatorAccessController.
 */

namespace Drupal\environment_indicator;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityAccessController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines an access controller for the environment entity.
 *
 * @see \Drupal\environment_indicator\Plugin\Core\Entity\EnvironmentIndicator.
 */
class EnvironmentIndicatorAccessController extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account,'administer environment indicator settings');
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account,'administer environment indicator settings');
  }

}
