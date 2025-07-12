$(document).ready(function () {
    $('.form-input-select').each(function () {
      const $dropdown = $(this);
      const $display = $dropdown.find('.form-select p');
      const $hiddenInput = $dropdown.find('input[type="hidden"]');
      const $options = $dropdown.find('.dropdown-option');
  
      $options.on('click', function () {
        const $option = $(this);
        const value = $option.data('value');
        const label = $option.text().trim();
  
        // Verwijder vorige 'selected'
        $options.removeClass('selected');
        $option.addClass('selected');
  
        // Update tekst en waarde
        $display.text(label);
        $hiddenInput.val(value);
      });
    });
  });
  