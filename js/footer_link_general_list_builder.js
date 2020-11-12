(function ($, Drupal) {
  Drupal.behaviors.footerLinkGeneralListBuilder = {
    attach: function attach(context) {
      // @todo: pass table ID via drupalSettings.
      Drupal.tableDrag['edit-entities'].onDrop = function (row) {
        var section = $(this.oldRowElement).prevAll('[data-link-section-id]').attr('data-link-section-id');
        $(this.oldRowElement).find('select.section').val(section);
      };
    }
  };
})(jQuery, Drupal);
