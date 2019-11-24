<?php

namespace Drupal\oe_corporate_blocks\Entity;

/**
 * Defines the footer link social configuration entity.
 *
 * @ConfigEntityType(
 *   id = "footer_link_social",
 *   label = @Translation("Footer Link Social"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\oe_corporate_blocks\FooterLinkSocialListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\oe_corporate_blocks\Form\FooterLinkSocialForm",
 *       "edit" = "Drupal\oe_corporate_blocks\Form\FooterLinkSocialForm",
 *       "delete" = "Drupal\oe_corporate_blocks\Form\FooterLinkGeneralDeleteForm"
 *     }
 *   },
 *   config_prefix = "footer_link.social",
 *   admin_permission = "administer footer link social",
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
 *     "add-form" = "/admin/config/footer-link-social/add",
 *     "edit-form" = "/admin/config/footer-link-social/{footer_link_social}/edit",
 *     "delete-form" = "/admin/config/footer-link-social/{footer_link_social}/delete",
 *     "collection" = "/admin/config/footer-link-social"
 *   }
 * )
 */
class FooterLinkSocial extends FooterLinkGeneral implements FooterLinkSocialInterface {

  /**
   * The list of allowed social networks.
   *
   * @var array
   */
  static public $allowedSocialNetworks = [
    'twitter' => 'Twitter',
    'facebook' => 'Facebook',
    'instagram' => 'Instagram',
    'linkedin' => 'Linkedin',
  ];

  /**
   * {@inheritdoc}
   */
  public function getSocialNetwork(): string {
    return $this->get('social_network');
  }

  /**
   * {@inheritdoc}
   */
  public function getSocialNetworkName(): string {
    return self::$allowedSocialNetworks[$this->get('social_network')] ?? '';
  }

}
