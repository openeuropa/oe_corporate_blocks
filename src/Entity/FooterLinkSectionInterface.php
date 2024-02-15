<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Footer link section entities.
 */
interface FooterLinkSectionInterface extends ConfigEntityInterface {

  /**
   * Get the footer link section weight.
   *
   * @return int
   *   The footer link section weight.
   */
  public function getWeight(): int;

}
