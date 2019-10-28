<?php

namespace Drupal\oe_corporate_blocks\Entity;

/**
 * Defines the footer social link configuration entity.
 *
 * @ConfigEntityType(
 *   id = "footer_social_link",
 *   label = @Translation("Footer social link"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\oe_corporate_blocks\FooterSocialLinkListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\oe_corporate_blocks\Form\FooterSocialLinkForm",
 *       "edit" = "Drupal\oe_corporate_blocks\Form\FooterSocialLinkForm",
 *       "delete" = "Drupal\oe_corporate_blocks\Form\FooterGeneralLinkDeleteForm"
 *     }
 *   },
 *   config_prefix = "footer_link.social",
 *   admin_permission = "administer footer social link",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "social_network" = "social_network",
 *     "uuid" = "uuid",
 *     "weight" = "weight",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "social_network",
 *     "url",
 *     "weight"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/footer-social-link/add",
 *     "edit-form" = "/admin/structure/footer-social-link/{footer_social_link}/edit",
 *     "delete-form" = "/admin/structure/footer-social-link/{footer_social_link}/delete",
 *     "collection" = "/admin/structure/footer-social-link"
 *   }
 * )
 */
class FooterSocialLink extends FooterGeneralLink implements FooterSocialLinkInterface {

  /**
   * Get the footer link social network name.
   *
   * @return string
   *   The footer link item social network.
   */
  public function getSocialNetwork(): string {
    return $this->get('social_network');
  }

}
