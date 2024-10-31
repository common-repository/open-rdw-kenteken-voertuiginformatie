<?php

namespace App\Concerns;

trait HasActions {

    /**
     * The action associated with saving settings
     *
     * @var string
     */
    public static $settingsSave = 'tsd_rdw_save_settings';

    /**
     * The action associated with saving settings for orders
     *
     * @var string
     */
    public static $orderSettingsSave = 'tsd_rdw_save_order_settings';

    /**
     * The action associated with saving settings for returns
     *
     * @var string
     */
    public static $returnSettingsSave = 'tsd_rdw_save_return_settings';

    /**
     * The action associated with filtering orders
     *
     * @var string
     */
    public static $filterOrders = 'tsd_rdw_filter_orders';

    /**
     * The action associated with filtering returns
     *
     * @var string
     */
    public static $filterReturns = 'tsd_rdw_filter_returns';

    /**
     * The action associated with selecting logs
     *
     * @var string
     */
    public static $selectLogs = 'tsd_rdw_select_logs';

    /**
     * The action associated with deleting a specific log
     *
     * @var string
     */
    public static $deleteSpecificLogs = 'tsd_rdw_delete_specific_log';

    /**
     * The action associated with deleting a all logs
     *
     * @var string
     */
    public static $deleteAllLogs = 'tsd_rdw_delete_all_logs';

    /**
     * The action associated with manually starting an action
     *
     * @var string
     */
    public static $startAction = 'tsd_rdw_manually_start_action';

    /**
     * The action associated with registering the plugin
     *
     * @var string
     */
    public static $registerPlugin = 'tsd_rdw_register_plugin';

    /**
     * The action associated with registering the plugin
     *
     * @var string
     */
    public static $refreshTokenAction = 'tsd_rdw_refresh_api_token_action';

    /**
     * The action associated with downloading a shipping label
     *
     * @var string
     */
    public static $downloadShippingLabelAction = 'tsd_rdw_download_shipping_label';

    /**
     * The action associated with fetching the order history after a click by
     * the user of the plugin
     *
     * @var string
     */
    public static $fetchHistoryActionFromAjax = 'tsd_rdw_fetch_history_from_ajax';

    /**
     * The action associated with fetching the order history on automatic
     * recurring actions
     *
     * @var string
     */
    public static $fetchHistoryActionRecurring = 'tsd_rdw_fetch_history_recurring';

    /**
     * The action associated with fetching the shipment history after it failed
     *
     * @var string
     */
    public static $fetchShipmentHistoryActionAfterFailure = 'tsd_rdw_fetch_shipment_history_after_fail';
}
