<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks;

use Drupal\Core\Config\Entity\DraggableListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\oe_corporate_blocks\Form\FooterLinkSocialSettingsForm;

/**
 * Provides a listing of Footer social link item entities.
 */
class FooterLinkSocialListBuilder extends DraggableListBuilder {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'footer_link_social_overview_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['social_network'] = $this->t('Social Network');
    $header['url'] = $this->t('URL');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['social_network'] = [
      '#markup' => $entity->getSocialNetworkName(),
    ];
    $row['url'] = [
      '#markup' => $entity->getUrl()->toString(),
    ];

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = [];

    $build['configuration_form'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Social media footer links settings'),
      0 => $this->formBuilder->getForm(FooterLinkSocialSettingsForm::class),
    ];

    $build['table_form'] = $this->formBuilder->getForm($this);

    return $build;
  }

}
