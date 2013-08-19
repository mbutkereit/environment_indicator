<?php
/**
 * @file
 * Contains \Drupal\environment_indicator\Plugin\Core\Entity\Environment.
 */

namespace Drupal\environment_indicator\Plugin\Core\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\Annotation\EntityType;
use Drupal\Core\Annotation\Translation;
use Drupal\environment_indicator\EnvironmentInterface;

/**
 * Defines a Environment configuration entity.
 *
 * @EntityType(
 *   id = "environment_indicator",
 *   label = @Translation("Environment Indicator"),
 *   module = "environment_indicator",
 *   controllers = {
 *     "storage" = "Drupal\Core\Config\Entity\ConfigStorageController",
 *     "access" = "Drupal\environment_indicator\EnvironmentAccessController",
 *     "list" = "Drupal\environment_indicator\EnvironmentListController",
 *     "form" = {
 *       "default" = "Drupal\environment_indicator\EnvironmentFormController",
 *       "delete" = "Drupal\environment_indicator\Form\EnvironmentDeleteForm"
 *     }
 *   },
 *   config_prefix = "environment_indicator.environment",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   }
 * )
 */
class Environment extends ConfigEntityBase implements EnvironmentInterface {

  /**
   * The machine-readable ID for the configurable.
   */
  public $id;

  /**
   * The human-readable label for the configurable.
   */
  public $label;

  /**
   * The universal unique identifier for the configurable.
   */
  public $uuid;

  /**
   * The regular expression to match against the URL.
   */
  public $regexurl;

  /**
   * The color code for the indicator.
   */
  public $color;

  /**
   * Position for the indicator.
   */
  public $position;

  /**
   * Flag that determines if the indicator is fixed or absolute.
   */
  public $fixed;
  
  /**
   * The weight of this environment in relation to other vocabularies.
   *
   * @var integer
   */
  public $weight = 0;

  /**
   * {@inheritdoc}
   */
  public function uri() {
    return array(
      'path' => 'admin/config/development/environment-indicator/' . $this->id(),
      'options' => array(
        'entity_type' => $this->entityType,
        'entity' => $this,
      ),
    );
  }

}