<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks;

/**
 * Interface for the footer link manager service.
 */
interface FooterLinkManagerInterface {

  /**
   * Load section entities, sorted by their weight.
   *
   * @return \Drupal\oe_corporate_blocks\Entity\FooterLinkSectionInterface[]
   *   Section entities, sorted by weight.
   */
  public function getSections(): array;

  /**
   * Get links by section, sorted by their weight.
   *
   * @param string $section
   *   Section ID.
   *
   * @return \Drupal\oe_corporate_blocks\Entity\FooterLinkGeneralInterface[]
   *   Link entities, sorted by weight.
   */
  public function getLinksBySection(string $section): array;

  /**
   * Get links without a section, sorted by their weight.
   *
   * @return \Drupal\oe_corporate_blocks\Entity\FooterLinkGeneralInterface[]
   *   Link entities, sorted by weight.
   */
  public function getLinksWithoutSection(): array;

  /**
   * Get the sections suitable for a select "#options" property.
   *
   * @return array
   *   List of section names, keyed by their ID.
   */
  public function getSectionsAsOptions(): array;

  /**
   * The list cache tags associated with this entity type.
   *
   * Enables code listing entities of this type to ensure that newly created
   * entities show up immediately.
   *
   * @return array
   *   The list cache tags.
   */
  public function getListCacheTags(): array;

}
