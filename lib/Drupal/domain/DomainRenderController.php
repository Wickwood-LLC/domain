<?php

/**
 * @file
 * Definition of Drupal\domain\DomainRenderController.
 */

namespace Drupal\domain;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityRenderController;
use Drupal\entity\Plugin\Core\Entity\EntityDisplay;

/**
 * Render controller for domain records.
 */
class DomainRenderController extends EntityRenderController {

  /**
   * Overrides Drupal\Core\Entity\EntityRenderController::buildContent().
   */
  public function buildContent(array $entities, array $displays, $view_mode, $langcode = NULL) {
    // If we can get domain_field_extra_fields() working here, we may not even
    // need this override class and can do everything via formatters.
    parent::buildContent($entities, $displays, $view_mode, $langcode);
    $fields = domain_field_extra_fields();
    $list = array_keys($fields['domain']['domain']['display']);

    foreach ($entities as $entity) {
      // Add the fields.
      // @TODO: get field sort order.
      $display = $displays[$entity->bundle()];
      foreach ($list as $key) {
        if (!empty($entity->{$key}) && $display->getComponent($key)) {
          $class = str_replace('_', '-', $key);
          $entity->content[$key] = array(
            '#markup' => check_plain($entity->{$key}),
            '#prefix' => '<div class="domain-' . $class . '">' . '<strong>' . check_plain($key) . ':</strong><br />',
            '#suffix' => '</div>',
          );
        }
      }
    }
  }

}
