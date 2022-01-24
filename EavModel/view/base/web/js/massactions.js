define([
    'jquery',
], function ($) {
    'use strict';

    return function (Massactions) {
        return Massactions.extend({
            initialize: function () {
                this._super();
                console.log('Hello from the mixin!');
            },

            /**
             * Applies specified action.
             *
             * @param {String} actionIndex - Actions' identifier.
             * @returns {Massactions} Chainable.
             */
            applyAction: function (actionIndex) {
                var data = this.getSelections(),
                    action,
                    callback;

                if (actionIndex.includes('assign_to_merchant')) {
                    action   = this.getAction(actionIndex);
                    callback = this._getCallback(action, data);

                    action.confirm ?
                        this._confirm(action, callback) :
                        callback();

                    return this;
                } else {
                    return this._super(actionIndex);
                }
            },
        });
    }
});
