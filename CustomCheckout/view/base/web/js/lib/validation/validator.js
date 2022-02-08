define([
    'jquery',
    'jquery/ui',
    'mage/validation',
    'mage/translate',
    'domReady!'
], function ($) {
    return function (validator) {
        validator.addRule(
            'validate-telephone-custom',
            function (value) {

                return /^[+()\d-]+$/.test(value);
            },
            $.mage.__('Please enter valid number in this field')
        );
        return validator;
    }
});
