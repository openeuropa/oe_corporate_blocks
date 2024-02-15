<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Entity;

/**
 * Provides an interface for defining Footer link entities.
 */
interface FooterLinkGeneralInterface extends FooterLinkInterface {

  /**
   * Get the footer link section.
   *
   * @return string
   *   The footer link section.
   */
  public function getSection(): string;

  /**
   * Set the footer link section.
   *
   * @param string $section
   *   The section of footer link.
   */
  public function setSection(string $section): void;

}
