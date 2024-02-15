<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Entity;

/**
 * Provides an interface for defining Footer Link entities.
 */
interface FooterLinkSocialInterface extends FooterLinkInterface {

  /**
   * Get the footer link social network.
   *
   * @return string
   *   The footer link item social network.
   */
  public function getSocialNetwork(): string;

  /**
   * Get the footer link social network label.
   *
   * @return string
   *   The footer link item social network.
   */
  public function getSocialNetworkName(): string;

}
