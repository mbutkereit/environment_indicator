<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\EnvironmentAccessController.
 */

namespace Drupal\environment_indicator;

use Drupal\Core\Entity\EntityAccessController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines an access controller for the environment entity.
 *
 * @see \Drupal\environment_indicator\Plugin\Core\Entity\Environment.
 */
class EnvironmentAccessController extends EntityAccessController {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    return user_access('administer environment indicator settings', $account);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return user_access('administer environment indicator settings', $account);
  }

}
