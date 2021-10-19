<?php

namespace Drupal\recommender_log\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\recommender_log\RecommenderLogInterface;

/**
 * Defines the recommender log entity class.
 *
 * @ContentEntityType(
 *   id = "recommender_log",
 *   label = @Translation("Recommender Log"),
 *   label_collection = @Translation("Recommender Logs"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\recommender_log\RecommenderLogListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\recommender_log\Form\RecommenderLogForm",
 *       "edit" = "Drupal\recommender_log\Form\RecommenderLogForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "recommender_log",
 *   admin_permission = "administer recommender log",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/recommender-log/add",
 *     "canonical" = "/recommender_log/{recommender_log}",
 *     "edit-form" = "/admin/content/recommender-log/{recommender_log}/edit",
 *     "delete-form" = "/admin/content/recommender-log/{recommender_log}/delete",
 *     "collection" = "/admin/content/recommender-log"
 *   },
 *   field_ui_base_route = "entity.recommender_log.settings"
 * )
 */
class RecommenderLog extends ContentEntityBase implements RecommenderLogInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    return $fields;
  }

}
