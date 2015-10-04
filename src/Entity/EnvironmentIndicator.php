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
use Drupal\environment_indicator\EnvironmentIndicatorInterface;
/**
 * Defines a Environment configuration entity.
 *
 * @ConfigEntityType(
 *   id = "environment_indicator",
 *   label = @Translation("Environment Indicator"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Config\Entity\ConfigEntityStorage",
 *     "access" = "Drupal\environment_indicator\EnvironmentIndicatorAccessController",
 *     "list_builder" = "Drupal\environment_indicator\Entity\Controller\EnvironmentIndicatorListController",
 *     "form" = {
 *       "default" = "Drupal\environment_indicator\Entity\Form\EnvironmentIndicatorForm",
 *       "delete" = "Drupal\environment_indicator\Entity\Form\EnvironmentIndicatorDeleteForm"
 *     }
 *   },
 *   admin_permission = "administer environment indicator settings",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "weight" = "weight"
 *   },
 *   links = {
 *     "canonical" = "/environment_indicator/update",
 *     "edit-form" = "/admin/environment_indicator/update",
 *     "delete-form" = "/admin/environment_indicator/delete"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "regexurl",
 *     "position",
 *     "fixed",
 *     "disabled",
 *     "weight",
 *     "color",
 *   }
 * )
 */
class EnvironmentIndicator extends ConfigEntityBase implements EnvironmentIndicatorInterface {

  /**
   * The machine-readable ID for the configurable.
   */
  public $id;

  /**
   * The human-readable label for the configurable.
   */
  public $label;

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
    return $this->get('id');
  }

  /**
   * {@inheritdoc}
   */
  public function label($langcode = NULL) {
    return $this->get('label');
  }

  /**
   * {@inheritdoc}
   */
  public function uri() {
    return [
      'path' => 'admin/config/development/environment-indicator/manage/' . $this->id(),
      'options' => [
        'entity_type' => $this->entityType,
        'entity' => $this,
      ],
    ];
  }



  public function setRegexurl($regex) {
    // TODO: Implement setRegexurl() method.
  }

  public function getRegexurl() {
    // TODO: Implement getRegexurl() method.
  }

  public function getColor() {
    return $this->color;
  }

  public function setColor($hex) {
    // TODO: Implement setColor() method.
  }

  public function getPosition() {
    // TODO: Implement getPosition() method.
  }

  public function setPosition() {
    // TODO: Implement setPosition() method.
  }

  public function isFixed() {
    // TODO: Implement isFixed() method.
  }

  public function setFixed() {
    // TODO: Implement setFixed() method.
  }

  public function isDisabled() {
    return $this->disabled;
  }

  public function setDisabled($bool) {
    $this->disabled = $bool;
  }

  public function getWeight() {
    // TODO: Implement getWeight() method.
  }

  public function setWeight() {
    // TODO: Implement setWeight() method.
  }
}