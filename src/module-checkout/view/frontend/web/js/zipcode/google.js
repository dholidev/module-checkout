define([
    'jquery',
    'uiRegistry',
    'Dholi_GoogleGeolocation/js/model/google-places',
    'underscore'
], function ($, registry, gp, _) {
    'use strict';

    return function (setShippingInformationAction) {
        const DATA_ATTR = 'google-autocomple';
        const MAPPED_FIELDS = {
            'street_number': {'g': 'long_name', 'm': 'street.1'},
            'route': {'g': 'long_name', 'm': 'street.0'},
            'administrative_area_level_2': {'g': 'long_name', 'm': 'city'},
            'administrative_area_level_1': {'g': 'long_name', 'm': 'region'},
            'country': {'g': 'short_name', 'm': 'country_id'},
            'postal_code': {'g': 'short_name', 'm': 'postcode'}
        };

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
                                return $.trim($(this).text()) == value;
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

        let addressAutoFill = function (id) {
            //const TEMPLATE = '<span class="action action-location" data-bind="click: auto-fill, i18n: \'Fill in address\'"></span>';
            //$('#'+id).after(TEMPLATE);
            let address = JSON.parse($.cookie('dholi-address-information'));
            $('#' + id).value(address.postcode);
        };

        registry.async('checkoutProvider')(function (checkoutProvider) {
            checkoutProvider.on('shippingAddress', function (data) {
                    $('#shipping-new-address-form input[name="street[0]"]').each(function (index) {
                        try {
                            if (!$(this).data(DATA_ATTR)) {
                                $(this).data(DATA_ATTR, true);

                                if (window.dholiGoogleGeolocation.isGooglePlacesAutocompleteActived) {
                                    let autocomplete = gp.initAutocomplete($(this).attr('id'));
                                    autocomplete.addListener('place_changed', function () {
                                        let prefix = 'shipping-step.shippingAddress.shipping-address-fieldset';
                                        let place = autocomplete.getPlace();
                                        if (place && _.size(place.address_components) > 0) {
                                            _.each(MAPPED_FIELDS, function (field, k) {
                                                clear(prefix, field.m);
                                            });

                                            _.each(place.address_components, function (val, k) {
                                                    let addressType = val.types[0];
                                                    if (_.has(MAPPED_FIELDS, addressType)) {
                                                        let field = _.propertyOf(MAPPED_FIELDS)(addressType);
                                                        let value = _.propertyOf(val)(field.g);

                                                        populate(prefix, field.m, value);
                                                    }
                                                }
                                            );
                                        }
                                    });
                                }
                            }
                        } catch (e) {

                        }
                    });
                }
            );

            Object.getOwnPropertyNames(checkoutProvider).forEach(function (val, idx, array) {
                if (val.indexOf('billingAddress') > -1) {
                    checkoutProvider.on(val, function (data) {
                        try {
                            let uiIndex = val.replace('billingAddress', '');
                            let streetid = registry.get('checkout.steps.billing-step.payment.payments-list.' + uiIndex + '-form.form-fields.street.0').uid;

                            if (!$('#'.concat(streetid)).data(DATA_ATTR)) {
                                $('#'.concat(streetid)).data(DATA_ATTR, true);
                                let autocomplete = gp.initAutocomplete(streetid);

                                autocomplete.addListener('place_changed', function () {
                                    let prefix = 'billing-step.payment.payments-list.' + uiIndex + '-form.form-fields';
                                    let place = autocomplete.getPlace();
                                    if (place && _.size(place.address_components) > 0) {
                                        _.each(MAPPED_FIELDS, function (field, k) {
                                            clear(prefix, field.m);
                                        });

                                        _.each(place.address_components, function (val, k) {
                                                let addressType = val.types[0];
                                                if (_.has(MAPPED_FIELDS, addressType)) {
                                                    let field = _.propertyOf(MAPPED_FIELDS)(addressType);
                                                    let value = _.propertyOf(val)(field.g);

                                                    populate(prefix, field.m, value);
                                                }
                                            }
                                        );
                                    }
                                });
                            }
                        } catch (e) {

                        }
                    });
                }
            });
        });

        return setShippingInformationAction;
    }
});