<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\Plugin\Menu\LocalAction\AddEnvironmentIndicatorLocalAction.
 */

namespace Drupal\environment_indicator\Plugin\Menu\LocalAction;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Annotation\Menu\LocalAction;
use Drupal\Core\Menu\LocalActionBase;

/**
 * @LocalAction(
 *   id = "environment_indicator_add_local_action",
 *   route_name = "environment_indicator_add",
 *   title = @Translation("Add environment indicator"),
 *   appears_on = {"environment_indicator_list"}
 * )
 */
class AddEnvironmentIndicatorLocalAction extends LocalActionBase {

}
