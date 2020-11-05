<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Url;

/**
 * Provides an interface for defining Footer link entities.
 */
interface FooterLinkInterface extends ConfigEntityInterface {

  /**
   * Get the footer link URL.
   *
   * @return \Drupal\Core\Url
   *   The footer link URL.
   */
  public function getUrl(): Url;

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

  /**
   * Get the footer link weight.
   *
   * @return int
   *   The footer link weight.
   */
  public function getWeight(): int;

}
