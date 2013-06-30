<?php

/**
 * @file
 * Definition of Drupal\environment_indicator\EnvironmentIndicatorStorageInterface.
 */

namespace Drupal\environment_indicator;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Defines an interface for environment indicator storage classes.
 */
interface EnvironmentIndicatorStorageInterface extends \IteratorAggregate, ConfigEntityInterface {

  /**
   * Sets the configuration entity status to enabled.
   */
  public function enable();

  /**
   * Sets the configuration entity status to disabled.
   */
  public function disable();

  /**
   * Returns whether the configuration entity is enabled.
   *
   * @return bool
   */
  public function isEnabled();

}
