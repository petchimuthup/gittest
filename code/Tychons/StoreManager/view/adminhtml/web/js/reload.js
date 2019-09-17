/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    "uiRegistry"
], function (registry) {
    'use strict';
    return {
        reloadUIComponent: function (gridName) {
            if (gridName) {
                var params = [];
                var target = registry.get(gridName);
                if (target && typeof target === 'object') {
                    target.set('params.t ', Date.now());
                }
            }
        }
    };
});
