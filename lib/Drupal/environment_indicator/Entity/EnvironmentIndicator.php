<?php
/**
 * @file
 * Contains \Drupal\environment_indicator\Entity\EnvironmentIndicator.
 */

namespace Drupal\environment_indicator\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\Annotation\EntityType;
use Drupal\Core\Annotation\Translation;

/**
 * Defines a Environment configuration entity.
 *
 * @EntityType(
 *   id = "environment_indicator",
 *   label = @Translation("Environment Indicator"),
 *   module = "environment_indicator",
 *   controllers = {
 *     "storage" = "Drupal\Core\Config\Entity\ConfigStorageController",
 *     "access" = "Drupal\environment_indicator\EnvironmentIndicatorAccessController",
 *     "list" = "Drupal\environment_indicator\EnvironmentIndicatorListController",
 *     "form" = {
 *       "default" = "Drupal\environment_indicator\EnvironmentIndicatorFormController",
 *       "delete" = "Drupal\environment_indicator\Form\EnvironmentIndicatorDeleteForm"
 *     }
 *   },
 *   config_prefix = "environment_indicator.environment",
 *   entity_keys = {
 *     "id" = "machine",
 *     "label" = "human_name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "environment_indicator.update",
 *     "delete-form" = "environment_indicator.delete",
 *     "edit-form" = "environment_indicator.update"
 *   }
 * )
 */
class EnvironmentIndicator extends ConfigEntityBase implements ConfigEntityInterface {

  /**
   * The machine-readable ID for the configurable.
   */
  public $machine;

  /**
   * The human-readable label for the configurable.
   */
  public $name;

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
  public $color = '#D0D0D0';

  /**
   * Position for the indicator.
   */
  public $position = 'top';

  /**
   * Flag that determines if the indicator is fixed or absolute.
   */
  public $fixed = FALSE;
  
  /**
   * Flag that determines if the indicator is disabled.
   */
  public $disabled = FALSE;
  
  /**
   * The weight of this environment in relation to other vocabularies.
   *
   * @var integer
   */
  public $weight = 0;

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->get('machine');
  }

  /**
   * {@inheritdoc}
   */
  public function label($langcode = NULL) {
    return $this->get('name');
  }

  /**
   * {@inheritdoc}
   */
  public function uri() {
    return array(
      'path' => 'admin/config/development/environment-indicator/manage/' . $this->id(),
      'options' => array(
        'entity_type' => $this->entityType,
        'entity' => $this,
      ),
    );
  }

}