<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Url;

/**
 * Interface for footer link entities.
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
   * Get the footer link weight.
   *
   * @return int
   *   The footer link weight.
   */
  public function getWeight(): int;

}
