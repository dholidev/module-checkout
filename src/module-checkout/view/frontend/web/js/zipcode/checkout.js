define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Dholi_Checkout/js/action/get-address-by-zipcode',
    'Magento_Checkout/js/model/full-screen-loader'
], function($, _, registry, getAddressByZipCode, fullScreenLoader) {
    'use strict';

    let isComplete   = null;
    let data$      = null;
    let checkTimeout = 0;
    let checkDelay   = 2000;

    return function (input) {
        const MAPPED_FIELDS = {
            'street': {'m': 'street.0'},
            'neighborhood': {'m': 'street.3'},
            'city': {'m': 'city'},
            'state': {'m': 'region'}
        };

        fullScreenLoader.startLoader();
        isComplete = $.Deferred();
        data$ = getAddressByZipCode(isComplete, input.val());

        $.when(isComplete).done(function () {
            let address = JSON.parse(data$.responseJSON);

            let step = 'shipping-step.shippingAddress.shipping-address-fieldset';
            _.each(MAPPED_FIELDS, function (field, k) {
                clear(step, field.m);
            });
            _.each(address, function (val, k) {
                let field = _.propertyOf(MAPPED_FIELDS)(k);
                populate(step, field.m, val);
            });
        }.bind(this)).fail(function () {

        }.bind(this)).always(function () {
            fullScreenLoader.stopLoader();
        }.bind(this));

        var clear = function (step, name) {
            try {
                let prefix = 'checkout.steps.'.concat(step.concat('.'));
                let fieldId = registry.get(prefix.concat(name)).uid;
                let input = $('#' + fieldId);
                if (input) {
                    if (input.attr('type') == 'text') {
                        input.val('').trigger('change');
                    }
                }
            } catch (e) {

            }
        };

        var populate = function (step, name, value) {
            let fieldId = null;
            let prefix = 'checkout.steps.'.concat(step.concat('.'));

            if (name == 'region') {
                if (value != '') {
                    if (registry.get(prefix.concat('region_id'))) {
                        fieldId = registry.get(prefix.concat('region_id')).uid;
                        if ($('#' + fieldId)) {
                            $('#' + fieldId + ' option').filter(function () {
                                return $.trim($(this).val()) == value;
                            }).attr('selected', true);
                            $('#' + fieldId).trigger('change');
                        }
                    }
                    if (registry.get(prefix.concat('region_id_input'))) {
                        fieldId = registry.get(prefix.concat('region_id_input')).uid;
                        if ($('#' + fieldId)) {
                            $('#' + fieldId).val(value).trigger('change');
                        }
                    }
                }
            } else {
                fieldId = registry.get(prefix.concat(name)).uid;
                if ($('#' + fieldId)) {
                    $('#' + fieldId).val(value).trigger('change');
                }
            }
        };
    };
});