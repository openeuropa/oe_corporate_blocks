<?php

namespace Drupal\oe_corporate_blocks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the footer link section configuration entity.
 *
 * @ConfigEntityType(
 *   id = "footer_link_section",
 *   label = @Translation("Footer Link Section"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\oe_corporate_blocks\FooterLinkSectionListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\oe_corporate_blocks\Form\FooterLinkSectionForm",
 *       "edit" = "Drupal\oe_corporate_blocks\Form\FooterLinkSectionForm",
 *       "delete" = "Drupal\oe_corporate_blocks\Form\FooterLinkGeneralDeleteForm"
 *     }
 *   },
 *   config_prefix = "footer_link.section",
 *   admin_permission = "administer site specific footer link sections",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "weight" = "weight"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "weight"
 *   },
 *   links = {
 *     "add-form" = "/admin/config/footer_link_section/add",
 *     "edit-form" = "/admin/config/footer_link_section/{footer_link_section}/edit",
 *     "delete-form" = "/admin/config/footer_link_section/{footer_link_section}/delete",
 *     "collection" = "/admin/config/footer_link_section"
 *   }
 * )
 */
class FooterLinkSection extends ConfigEntityBase implements FooterLinkSectionInterface {

  /**
   * The footer link weight.
   *
   * @var int
   */
  protected $weight;

  /**
   * {@inheritdoc}
   */
  public function getWeight(): int {
    return $this->get('weight');
  }

  /**
   * {@inheritdoc}
   */
  public static function preDelete(EntityStorageInterface $storage, array $entities) {
    parent::preDelete($storage, $entities);

    // Make sure links do not reference to non-existing sections.
    foreach ($entities as $entity) {
      $link_storage = \Drupal::entityTypeManager()->getStorage('footer_link_general');
      $link_ids = $link_storage->getQuery()
        ->condition('section', $entity->id())
        ->execute();
      foreach ($link_storage->loadMultiple($link_ids) as $link) {
        $link->set('section', '');
        $link->save();
      }
    }
  }

}
