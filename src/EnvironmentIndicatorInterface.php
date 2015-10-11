<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\EnvironmentIndicatorInterface.
 */

namespace Drupal\environment_indicator;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Interface EnvironmentIndicatorInterface.
 *
 * @package Drupal\environment_indicator
 */
interface EnvironmentIndicatorInterface extends ConfigEntityInterface {

  /**
   * The regular expression to match against the URL.
   */
  public function setRegexurl($regex);

  /**
   * Getter for Regexurl.
   *
   * @return string
   *    Return the regex for the url.
   */
  public function getRegexurl();

  /**
   * The color code for the indicator.
   */
  public function getColor();

  public function setColor($hex);

  /**
   * Position for the indicator.
   */
  public function getPosition();

  public function setPosition();

  /**
   * Flag that determines if the indicator is fixed or absolute.
   */
  public function isFixed();

  public function setFixed();

  /**
   * Flag that determines if the indicator is disabled.
   */
  public function isDisabled();

  public function setDisabled($bool);

  /**
   * The weight of this environment in relation to other vocabularies.
   *
   * @var integer
   */
  public function getWeight();

  public function setWeight();
}