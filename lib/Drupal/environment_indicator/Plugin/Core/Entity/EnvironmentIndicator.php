<?php

/**
 * @file
 * Contains Drupal\environment_indicator\Plugin\Core\Entity\EnvironmentIndicator.
 */

namespace Drupal\environment_indicator\Plugin\Core\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\environment_indicator\EnvironmentIndicatorStorageInterface;
use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * Defines the Environment Indicator Environment entity.
 *
 * @Plugin(
 *   id = "environment_indicator",
 *   label = @Translation("Environment Indicator Environment"),
 *   module = "environment_indicator",
 *   controller_class = "Drupal\environment_indicator\EnvironmentIndicatorStorageController",
 *   list_controller_class = "Drupal\environment_indicator\EnvironmentIndicatorListController",
 *   form_controller_class = {
 *     "default" = "Drupal\environment_indicator\EnvironmentIndicatorFormController"
 *   },
 *   uri_callback = "environment_indicator_uri",
 *   config_prefix = "environment_indicator.environment",
 *   fieldable = FALSE,
 *   entity_keys = {
 *     "id" = "name",
 *     "label" = "human_name",
 *     "uuid" = "uuid"
 *   }
 * )
 */
class EnvironmentIndicator extends ConfigEntityBase implements EnvironmentIndicatorStorageInterface {

  /**
   * The name of the environment indicator.
   *
   * @var string
   */
  public $name = NULL;

  /**
   * The description of the environment indicator, only in the interface.
   *
   * @var string
   */
  public $description = '';

  /**
   * The human readable name of the environment indicator.
   *
   * @var string
   */
  public $human_name = '';

  /**
   * The weight of this environment indicator in relation to others.
   *
   * @var integer
   */
  public $weight = 0;

  /**
   * Stores all settings of this environment indicator.
   *
   * An array containing Drupal\views\Plugin\views\display\DisplayPluginBase
   * objects.
   *
   * @var array
   */
  protected $settings;

  /**
   * Returns whether the environment indicator's status is disabled or not.
   *
   * This value is used for exported environment indicators, to provide some
   * default environment indicators which aren't enabled.
   *
   * @var bool
   */
  protected $disabled = FALSE;

  /**
   * The UUID for this entity.
   *
   * @var string
   */
  public $uuid = NULL;

  /**
   * Overrides Drupal\Core\Entity\EntityInterface::uri().
   */
  public function uri() {
    return array(
      'path' => 'admin/config/development/environment-indicator/manage/' . $this->id(),
    );
  }

  /**
   * Overrides Drupal\Core\Entity\EntityInterface::id().
   */
  public function id() {
    return $this->get('name');
  }


  /**
   * Implements Drupal\environment_indicator\EnvironmentIndicatorStorageInterface::enable().
   */
  public function enable() {
    $this->disabled = FALSE;
    $this->save();
  }

  /**
   * Implements Drupal\environment_indicator\EnvironmentIndicatorStorageInterface::disable().
   */
  public function disable() {
    $this->disabled = TRUE;
    $this->save();
  }

  /**
   * Implements Drupal\environment_indicator\EnvironmentIndicatorStorageInterface::isEnabled().
   */
  public function isEnabled() {
    return !$this->disabled;
  }

  /**
   * Return the human readable name for an environment indicator.
   *
   * When a certain environment indicator doesn't have a human readable name
   * return the machine readable name.
   */
  public function getHumanName() {
    if (!$human_name = $this->get('human_name')) {
      $human_name = $this->get('name');
    }
    return $human_name;
  }


  /**
   * Overrides \Drupal\Core\Config\Entity\ConfigEntityBase::getExportProperties();
   */
  public function getExportProperties() {
    $names = array(
      'name',
      'human_name',
      'description',
      'disabled',
      'uuid',
    );
    $properties = array();
    foreach ($names as $name) {
      $properties[$name] = $this->get($name);
    }
    return $properties;
  }

}
