<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Entity;

/**
 * Provides an interface for defining Footer link entities.
 */
interface FooterSocialLinkInterface extends FooterLinkInterface {

  /**
   * Get the footer link social network name.
   *
   * @return string
   *   The footer link item social network.
   */
  public function getSocialNetwork(): string;

}
