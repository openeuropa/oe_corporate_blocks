<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration settings form for site switcher block.
 */
class SiteSwitcherSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'oe_corporate_blocks_site_switcher_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'oe_corporate_blocks.data.site_switcher',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('oe_corporate_blocks.data.site_switcher');

    $form['active'] = [
      '#type' => 'select',
      '#title' => $this->t('Link'),
      '#options' => [
        'info' => $this->t('Info site'),
        'political' => $this->t('Political site'),
      ],
      '#description' => $this->t('Choose which site switcher link will be marked as active.'),
      '#default_value' => $config->get('active'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable('oe_corporate_blocks.data.site_switcher')
      ->set('active', $form_state->getValue('active'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
