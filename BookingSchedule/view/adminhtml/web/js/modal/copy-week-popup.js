define([
        'jquery',
        'jquery/ui',
        'Magento_Ui/js/modal/modal'
    ],
    function ($) {
        $.widget('custommodel', $.mage.modal, {
            _close: function () {
                /*write your custom code here */
                console.log('hello world');
                /* below function is used for call parent function */
                this._super();
            }
        });
        return $.custommodel;
    }
);

