<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\EnvironmentIndicatorInterface.
 */

namespace Drupal\environment_indicator;

use \Drupal\Core\Site\Settings;
use Drupal\environment_indicator\Entity\EnvironmentIndicator;

/**
 * Class EnvironmentManager.
 *
 * @package Drupal\environment_indicator
 */
class EnvironmentManager {

  protected static $regex = '';

  /**
   * Helper function to get the active indicator.
   *
   * @return \Drupal\environment_indicator\Entity\EnvironmentIndicator|null
   *    EnvironmentIndicator entity
   */
  public function getActiveIndicator() {
    $env = $this->getEnvironmentIndicatorOverwritte();
    if ($env === NULL) {
      $environments = $this->getAllEnvironmentIndicators();
      $matches = [];
      foreach ($environments as $machine => $environment) {
        if ($this->environmentIndicatorMatchPath($environment->regexurl)) {
          $matches[] = $environment;
        }
      }
      $env = reset($matches);
    }
    return $env;
  }

  /**
   * Load the current environment_indicator overwrite.
   *
   * @return \Drupal\environment_indicator\Entity\EnvironmentIndicator|null
   *    EnvironmentIndicator entity.
   */
  public function getEnvironmentIndicatorOverwritte() {
    $env = NULL;
    if (Settings::get("environment_indicator_overwrite", FALSE) == TRUE) {
      $env = new EnvironmentIndicator(
        [
          'label' => Settings::get("environment_indicator_overwritten_name", ""),
          'color' => Settings::get("environment_indicator_overwritten_color", ""),
          'position' => Settings::get("environment_indicator_overwritten_position", ""),
          'fixed' => Settings::get("environment_indicator_overwritten_position", "environment_indicator_overwritten_fixed", ''),
        ],
        'EnvironmentIndicator'
      );
    }
    return $env;
  }

  /**
   * Helper function to match the path based on the regular expression.
   *
   * @param string $regexurl
   *   The regular expression to match against.
   *
   * @return int
   *   Indicating if the environment was a match.
   */
  public function environmentIndicatorMatchPath($regexurl) {
    // If the URL includes a non-scaped slash then an error will be thrown.
    $regexurl = preg_replace("/([^\/])\//", "$1\/", $regexurl);
    return preg_match("/$regexurl/", $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  }

  /**
   * Helper function to get all environments.
   *
   * @param bool $fixed
   *   If TRUE it will only return fixed environments. Fixed environments are
   *   those that do not use a regular expression for detection.
   */
  public function getAllEnvironmentIndicators($fixed = FALSE) {
    $controller = \Drupal::entityManager()->getStorage('environment_indicator');
    return $controller->loadMultiple(NULL);
  }

  /**
   * Helper function to check access to show the indicator.
   *
   * @param \Drupal\environment_indicator\Entity\EnvironmentIndicator $environment_info
   *   The environment indicator.
   *
   * @return bool
   *   TRUE if the user can see the indicator.
   */
  public function environmentIndicatorCheckAccess(EnvironmentIndicator $environment_info) {
    // Do not show the indicator on select pages.
    $supress_pages = \Drupal::config('environment_indicator.options')
      ->get('suppress_pages');
    // Compare with the internal and path alias (if any).
    $current_path = \Drupal::request()->getRequestUri();
    $page_match = \Drupal::service('path.matcher')
      ->matchPath($current_path, $supress_pages);
    if ($page_match) {
      return FALSE;
    }

    return \Drupal::currentUser()
      ->hasPermission('access environment indicator') || \Drupal::currentUser()
      ->hasPermission('access environment indicator ' . $environment_info['id']);
  }

  /**
   * Helper function to check if the JS needs to be included.
   *
   * @return bool
   *   TRUE if the extra javascript is needed.
   */
  public function environmentIndicatorNeedsJs() {
    return \Drupal::moduleHandler()
      ->moduleExists('toolbar') && \Drupal::currentUser()
      ->hasPermission('access toolbar');
  }

  /**
   * Filter callback.
   *
   * @param \Drupal\environment_indicator\Entity\EnvironmentIndicator $item
   *    EnvironmentIndicator entity.
   *
   * @return bool
   *    is in list or not.
   */
  protected function environmentIndicatorRegexFilter(EnvironmentIndicator $item) {
    return !preg_match("/[\*\?\[\]\(\)]/", $item->regexurl);
  }

}
