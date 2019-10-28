<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks;

use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Footer social link item entities.
 */
class FooterSocialLinkListBuilder extends FooterGeneralLinkListBuilder {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'footer_social_links_overview_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    // Injecting of social network column.
    $header = parent::buildHeader();

    $first_element = array_slice($header, 0, 1);
    $header = ['social_network' => $this->t('Social Network')] + $header;
    $header = $first_element + $header;

    return $header;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    // Injecting of social network column.
    $row = parent::buildRow($entity);

    $first_element = array_slice($row, 0, 1);

    $row = [
      'social_network' => [
        '#markup' => $entity->getSocialNetwork(),
      ],
    ] + $row;
    $row = $first_element + $row;

    return $row;
  }

}
