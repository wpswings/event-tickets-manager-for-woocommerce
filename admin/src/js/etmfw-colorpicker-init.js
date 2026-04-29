(function($){
    'use strict';

    function normalizeHexColor(value) {
        var color = (value || '').toString().trim();

        if ( /^#([0-9a-f]{3})$/i.test(color) ) {
            color = '#' + color.charAt(1) + color.charAt(1) + color.charAt(2) + color.charAt(2) + color.charAt(3) + color.charAt(3);
        }

        if ( ! /^#([0-9a-f]{6})$/i.test(color) ) {
            return '#000000';
        }

        return color.toUpperCase();
    }

    function getDescriptionNode($input) {
        var $tableHelper = $input.closest('td').find('.wps_etmfw_helper_text').first();

        if ( $tableHelper.length ) {
            return $tableHelper;
        }

        return $input.closest('.wps-etmfw-ui-field').find('.wps-etmfw-ui-field__description').first();
    }

    function ensurePickerStructure($input) {
        var $picker = $input.closest('.wp-picker-container');
        var $result = $picker.find('.wp-color-result').first();
        var $meta;
        var $description;

        if ( ! $picker.length || ! $result.length ) {
            return $picker;
        }

        $picker.addClass('wps-etmfw-picker');

        $meta = $picker.children('.wps-etmfw-picker-meta');
        if ( ! $meta.length ) {
            $meta = $('<div class="wps-etmfw-picker-meta"><div class="wps-etmfw-picker-head"><span class="wps-etmfw-picker-title">COLOR</span><span class="wps-etmfw-picker-hex" data-etmfw-color-hex></span></div><div class="wps-etmfw-picker-description"></div></div>');
            $result.after($meta);
        }

        $description = $meta.find('.wps-etmfw-picker-description');

        if ( ! $description.children().length ) {
            var $descriptionNode = getDescriptionNode($input);

            if ( $descriptionNode.length ) {
                $descriptionNode.addClass('wps-etmfw-picker-description-text').appendTo($description);
            }
        }

        return $picker;
    }

    function syncPickerState($input) {
        var $picker = ensurePickerStructure($input);
        var normalized = normalizeHexColor($input.val());

        if ( ! $picker.length ) {
            return;
        }

        $picker.css('--etmfw-picked-color', normalized);
        $picker.toggleClass('has-color-value', !! ($input.val() || '').toString().trim());
        $picker.find('[data-etmfw-color-hex]').text(normalized);
        $picker.find('.wp-color-result').attr('aria-label', 'Color ' + normalized);
    }

    window.etmfwInitColorPickers = function( context ) {
        context = context || document;

        $( context ).find('.wps_etmfw_colorpicker').each(function(){
            var $input = $(this);

            if ( ! $input.data('etmfw-initialized') && typeof $input.wpColorPicker === 'function' ) {
                $input.wpColorPicker({
                    change: function() {
                        syncPickerState($input);
                    },
                    clear: function() {
                        syncPickerState($input);
                    }
                });
                $input.data('etmfw-initialized', true);
            }

            ensurePickerStructure($input);
            syncPickerState($input);
            $input.off('change.etmfwColor input.etmfwColor').on('change.etmfwColor input.etmfwColor', function(){
                syncPickerState($input);
            });
        });
    };

    $(function(){
        if ( typeof window.etmfwInitColorPickers === 'function' ) {
            window.etmfwInitColorPickers( document );
        }

        if ( typeof MutationObserver !== 'undefined' ) {
            var observer = new MutationObserver(function(mutations){
                mutations.forEach(function(mutation){
                    Array.prototype.forEach.call(mutation.addedNodes, function(node){
                        try {
                            if ( node.querySelector && node.querySelector('.wps_etmfw_colorpicker') ) {
                                window.etmfwInitColorPickers(node);
                            }
                        } catch(e) {}
                    });
                });
            });

            observer.observe(document.body, { childList: true, subtree: true });
        }
    });
})(jQuery);
