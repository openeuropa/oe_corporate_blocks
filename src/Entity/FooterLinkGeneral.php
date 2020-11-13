<?php

namespace Drupal\oe_corporate_blocks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Url;

/**
 * Defines the footer general link configuration entity.
 *
 * @ConfigEntityType(
 *   id = "footer_link_general",
 *   label = @Translation("Footer Link General"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\oe_corporate_blocks\FooterLinkGeneralListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\oe_corporate_blocks\Form\FooterLinkGeneralForm",
 *       "edit" = "Drupal\oe_corporate_blocks\Form\FooterLinkGeneralForm",
 *       "delete" = "Drupal\oe_corporate_blocks\Form\FooterLinkGeneralDeleteForm"
 *     }
 *   },
 *   config_prefix = "footer_link.general",
 *   admin_permission = "administer site specific footer links",
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
 *     "section",
 *     "weight"
 *   },
 *   links = {
 *     "add-form" = "/admin/config/footer_link_general/add",
 *     "edit-form" = "/admin/config/footer_link_general/{footer_link_general}/edit",
 *     "delete-form" = "/admin/config/footer_link_general/{footer_link_general}/delete",
 *     "collection" = "/admin/config/footer_link_general"
 *   }
 * )
 */
class FooterLinkGeneral extends ConfigEntityBase implements FooterLinkGeneralInterface {

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
    if ($input === '<front>') {
      $input = 'internal:/';
    }
    if (parse_url($input, PHP_URL_SCHEME) === NULL) {
      $input = 'internal:' . $input;
    }
    return Url::fromUri($input);
  }

  /**
   * {@inheritdoc}
   */
  public function getSection(): string {
    return $this->get('section');
  }

  /**
   * {@inheritdoc}
   */
  public function setSection(string $section): void {
    $this->set('section', $section);
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight(): int {
    return $this->get('weight');
  }

}
