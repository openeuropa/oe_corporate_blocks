<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure the Social media footer links settings.
 */
class FooterLinkSocialSettingsForm extends ConfigFormBase {

  /**
   * The config name.
   *
   * @var string
   */
  const CONFIG_NAME = 'oe_corporate_blocks.social_media_footer_links_settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_media_footer_links_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [self::CONFIG_NAME];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::CONFIG_NAME);

    $form['display_labels'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display labels'),
      '#description' => $this->t("Check this box if you'd like to display the social media links labels."),
      '#default_value' => $config->get('display_labels'),
    ];
    $form['alignment'] = [
      '#type' => 'select',
      '#title' => $this->t('Alignment'),
      '#description' => $this->t('The alignment of the social media links.'),
      '#default_value' => $config->get('alignment'),
      '#options' => [
        'horizontal' => $this->t('Horizontal'),
        'vertical' => $this->t('Vertical'),
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config(self::CONFIG_NAME);
    $config->set('display_labels', $form_state->getValue('display_labels'))
      ->set('alignment', $form_state->getValue('alignment'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
