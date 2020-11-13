/**
 * Implements Drupal.tableDrag's onDrop() callback on entities overview page.
 *
 * This script assigns a section to a footer link once such link is dropped,
 * after being dragged in the current position. In order to work correctly
 * the section rows must always be printed before the link rows: this is ensured
 * by the build form callback below.
 *
 * @see \Drupal\oe_corporate_blocks\FooterLinkGeneralListBuilder::buildForm()
 **/
(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.footerLinkGeneralListBuilder = {
    attach: function attach(context) {
      Drupal.tableDrag['edit-' + drupalSettings.footerLinkGeneralListBuilder.entitiesKey].onDrop = function () {
        var section = $(this.oldRowElement).prevAll('[data-link-section-id]').attr('data-link-section-id');
        $(this.oldRowElement).find('select.section').val(section);
      };
    }
  };
})(jQuery, Drupal, drupalSettings);
