<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\oe_corporate_blocks\FooterLinkManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form class for the Footer Link General configuration entity.
 */
class FooterLinkGeneralForm extends FooterLinkFormBase {

  /**
   * Footer link manager service.
   *
   * @var \Drupal\oe_corporate_blocks\FooterLinkManagerInterface
   */
  protected $linkManager;

  /**
   * FooterLinkGeneralForm constructor.
   *
   * @param \Drupal\oe_corporate_blocks\FooterLinkManagerInterface $link_manager
   *   The footer link manager service.
   */
  public function __construct(FooterLinkManagerInterface $link_manager) {
    $this->linkManager = $link_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('oe_corporate_blocks.footer_link_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface $footer_link_general */
    $footer_link_general = $this->entity;

    $form['section'] = [
      '#type' => 'select',
      '#title' => $this->t('Section'),
      '#options' => $this->linkManager->getSectionsAsOptions(),
      '#default_value' => $footer_link_general->get('section'),
      '#description' => $this->t('The section the link will appear under.'),
      '#weight' => -1,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $footer_link_general = $this->entity;

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('The "%label" general footer link has been created.', [
          '%label' => $footer_link_general->label(),
        ]));
        break;

      default:
        $this->messenger()->addStatus($this->t('The "%label" general footer link has been updated.', [
          '%label' => $footer_link_general->label(),
        ]));
    }
    $form_state->setRedirectUrl($footer_link_general->toUrl('collection'));
  }

}
