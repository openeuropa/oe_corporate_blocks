<?php

namespace Drupal\oe_corporate_blocks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Url;

/**
 * Defines the footer general link configuration entity.
 *
 * @ConfigEntityType(
 *   id = "footer_general_link",
 *   label = @Translation("Footer general link"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\oe_corporate_blocks\FooterGeneralLinkListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\oe_corporate_blocks\Form\FooterGeneralLinkForm",
 *       "edit" = "Drupal\oe_corporate_blocks\Form\FooterGeneralLinkForm",
 *       "delete" = "Drupal\oe_corporate_blocks\Form\FooterGeneralLinkDeleteForm"
 *     }
 *   },
 *   config_prefix = "footer_link.general",
 *   admin_permission = "administer footer general link",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "weight" = "weight"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "url",
 *     "weight"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/footer-general-link/add",
 *     "edit-form" = "/admin/structure/footer-general-link/{footer_general_link}/edit",
 *     "delete-form" = "/admin/structure/footer-general-link/{footer_general_link}/delete",
 *     "collection" = "/admin/structure/footer-general-link"
 *   }
 * )
 */
class FooterGeneralLink extends ConfigEntityBase implements FooterLinkInterface {

  /**
   * The footer link URL.
   *
   * @var string
   */
  protected $url;

  /**
   * The footer link weight.
   *
   * @var int
   */
  protected $weight;

  /**
   * {@inheritdoc}
   */
  public function getUrl(): Url {
    $input = trim($this->get('url'));
    if (parse_url($input, PHP_URL_SCHEME) === NULL) {
      $input = 'internal:' . $input;
    }
    return Url::fromUri($input);
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight(): int {
    return $this->get('weight');
  }

}
