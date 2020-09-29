define([
        'mage/storage',
        'Dholi_Core/js/model/url-builder'
    ],
    function (storage, urlBuilder) {
        'use strict';

        return function (deferred, zipcode) {
            return storage.post(
                urlBuilder.createUrl('/dholi/getAddressByZipCode', {}),
                JSON.stringify({zipcode: zipcode}),
                false
            ).done(function (response) {
                if (response && response.erro) {
                    deferred.reject();
                } else {
                    deferred.resolve();
                }
            }).fail(function () {
                deferred.reject();
            });
        };
    }
);