<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks;

use Drupal\Core\Config\Entity\DraggableListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Footer general link item entities.
 */
class FooterLinkGeneralListBuilder extends DraggableListBuilder {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'footer_link_generals_overview_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['url'] = $this->t('URL');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['url'] = [
      '#markup' => $entity->getUrl()->toString(),
    ];

    return $row + parent::buildRow($entity);
  }

}
