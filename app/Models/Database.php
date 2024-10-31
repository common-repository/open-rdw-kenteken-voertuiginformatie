<?php namespace Tussendoor\Bol\Models;

use App\MainConfig;
use Illuminate\Support\Manager;

class Database
{
    /**
     * The tables we use in our plugin, this array is used in the drop() method
     * The order of the tables is very important, please put all the children above their parent to prevent fatal errors
     * A table is a child when they have a foreign assigned
     *
     * @var array
     */
    public static $tables = [
        'tsd_rdw_settings',
        'tsd_rdw_shipment_transport',
        'tsd_rdw_shipments',
        'tsd_rdw_return_itemmeta',
        'tsd_rdw_return_items',
        'tsd_rdw_returns',
        'tsd_rdw_item_fulfilment',
        'tsd_rdw_order_items',
        'tsd_rdw_ordermeta',
        'tsd_rdw_orders',
    ];

    /**
     * The instances of this class
     *
     * @since 1.0.2
     * @var array
     */
    private static $instances = [];

    /**
     * The initialisation status of the class
     *
     * @since 1.0.2
     * @var bool
     */
    private $initialised = false;

    /**
     * Initialise the class when using this class statically
     * By getting a Singleton instance we ensure one database connection
     *
     * @since 1.0.2
     *
     * @param  string $name
     * @param  array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $instance   = self::getInstance();
        $method     = '__' . $name;
        if (!method_exists($instance, $method)) return;

        $instance->setup();
        return call_user_func_array([$instance, $method], $arguments);
    }

    /**
     * Return the instance of this class
     *
     * @since 1.0.2
     *
     * @return self
     */
    private static function getInstance()
    {
        $cls = static::class;
        if (!isset(static::$instances[$cls])) {
            static::$instances[$cls] = new static();
        }

        return static::$instances[$cls];
    }

    /**
     * Setup the $manager property
     *
     * @since 1.0.2
     */
    private function setup()
    {
        if ($this->initialised) {
            return;
        }

        $settings = MainConfig::get('database.settings');
        $database = new Manager;
        $database->addConnection($settings);
        $database->setAsGlobal();
        $database->bootEloquent();

        $this->initialised = true;
    }

    /**
     * Add database tables when they do not exist yet
     *
     * @since 1.0.2 non-static and private
     */
    private function __migrate()
    {
        /**
         * Setting table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_settings')) {
            Manager::schema()->create('tsd_rdw_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->index();
                $table->text('value');
            });
        }

        /**
         * Order table
         * @since 1.0.0-beta.5 removed fulfilment_method column
         */
        if (!Manager::schema()->hasTable('tsd_rdw_orders')) {
            Manager::schema()->create('tsd_rdw_orders', function (Blueprint $table) {
                $table->increments('id');
                $table->char('order_id', 100)->unique()->index();
                $table->char('status', 25)->index();
                $table->char('order_date', 100)->nullable();
                $table->timestamps();
            });
        }

        /**
         * OrderItem table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_order_items')) {
            Manager::schema()->create('tsd_rdw_order_items', function (Blueprint $table) {
                $table->increments('id');
                $table->char('item_id', 100)->index();
                $table->char('order_id', 100)->index();
                $table->char('shipment_id', 100)->nullable()->index();
                $table->char('offer_id', 100)->index();
                $table->char('offer_reference', 100)->index();
                $table->boolean('cancellation_request');
                $table->char('product_ean', 100)->index();
                $table->char('product_title', 100)->index();
                $table->integer('quantity')->unsigned();
                $table->integer('quantity_shipped')->unsigned();
                $table->integer('quantity_cancelled')->unsigned();
                $table->decimal('unit_price', 8, 2)->unsigned();
                $table->decimal('commission', 8, 2)->unsigned();
                $table->timestamp('latest_change');
                $table->timestamps();

                // When an order is deleted its items will delete as well
                $table->foreign('order_id')->references('order_id')->on('tsd_rdw_orders')->cascadeOnDelete();
            });
        }

        /**
         * OrderMeta table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_ordermeta')) {
            Manager::schema()->create('tsd_rdw_ordermeta', function (Blueprint $table) {
                $table->increments('id');
                $table->char('order_id', 100)->index();
                $table->char('meta_key', 100)->index();
                $table->string('meta_value')->index();
                $table->timestamps();

                // When an order is deleted its meta will delete as well
                $table->foreign('order_id')->references('order_id')->on('tsd_rdw_orders')->cascadeOnDelete();
            });
        }

        /**
         * OrderItemFulfilment table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_item_fulfilment')) {
            Manager::schema()->create('tsd_rdw_item_fulfilment', function (Blueprint $table) {
                $table->increments('id');
                $table->char('item_id', 100)->index();
                $table->char('method', 25)->index();
                $table->char('distribution_party', 100);
                $table->char('latest_delivery_date', 100);
                $table->char('expiry_date', 100)->nullable();
                $table->char('time_frame_type', 100)->nullable();

                // When an order_item is deleted its fulfilment will delete as well
                $table->foreign('item_id')->references('item_id')->on('tsd_rdw_order_items')->cascadeOnDelete();
            });
        }

        /**
         * Shipment table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_shipments')) {
            Manager::schema()->create('tsd_rdw_shipments', function (Blueprint $table) {
                $table->increments('id');
                $table->char('shipment_id', 100)->index();
                $table->char('order_id', 100)->nullable()->index();
                $table->char('shipment_date', 100);
                $table->char('shipment_reference', 100)->index();
                $table->boolean('pickup_point');
                $table->timestamps();

                // When an order_item is deleted its shipment will delete as well
                $table->foreign('order_id')->references('order_id')->on('tsd_rdw_orders')->cascadeOnDelete();
            });
        }

        /**
         * Shipment transport table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_shipment_transport')) {
            Manager::schema()->create('tsd_rdw_shipment_transport', function (Blueprint $table) {
                $table->increments('id');
                $table->char('transport_id', 100)->index();
                $table->char('shipment_id', 100)->index();
                $table->char('transporter_code', 100);
                $table->char('track_and_trace', 100);
                $table->char('shipping_label_id', 100);
                $table->timestamps();

                // When an order is deleted the shipment will live, do not NULL the relation here. Can be usefull info to show.
                // no workie workie - order_id can be of an order that does exist in Bol but not in our site. This breaks.
                $table->foreign('shipment_id')->references('shipment_id')->on('tsd_rdw_shipments')->cascadeOnDelete();
            });
        }


        /**
         * ReturnModel table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_returns')) {
            Manager::schema()->create('tsd_rdw_returns', function (Blueprint $table) {
                $table->increments('id');
                $table->char('return_id', 100)->index();
                $table->char('registration_date', 100);
                $table->char('fulfilment_method', 25)->index();
                $table->boolean('handled');
                $table->timestamps();
            });
        }

        /**
         * ReturnItems table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_return_items')) {
            Manager::schema()->create('tsd_rdw_return_items', function (Blueprint $table) {
                $table->increments('id');
                $table->char('rma_id', 100)->index();
                $table->char('return_id', 100)->index();
                $table->char('order_id', 100)->nullable()->index();
                $table->char('ean', 100);
                $table->char('title', 100);
                $table->integer('expected_quantity');
                $table->string('main_reason');
                $table->string('detailed_reason');
                $table->string('customer_comments');
                $table->char('track_and_trace', 100);
                $table->char('transporter_name', 100);
                $table->boolean('handled');
                $table->timestamps();

                // When an return is deleted its items will delete as well
                $table->foreign('return_id')->references('return_id')->on('tsd_rdw_returns')->cascadeOnDelete();

                // When an order is deleted the return item will live, do not NULL the relation here. Can be usefull info to show.
                // no workie workie - order_id can be of an order that does exist in Bol but not in our site. This breaks.
                // $table->foreign('order_id')->references('order_id')->on('tsd_rdw_orders');
            });
        }

        /**
         * ReturnItems table
         */
        if (!Manager::schema()->hasTable('tsd_rdw_return_itemmeta')) {
            Manager::schema()->create('tsd_rdw_return_itemmeta', function (Blueprint $table) {
                $table->increments('id');
                $table->char('item_id', 100)->index();
                $table->char('meta_key', 100)->index();
                $table->string('meta_value')->index();
                $table->timestamps();

                // When an return_item is deleted its meta will delete as well
                $table->foreign('item_id')->references('rma_id')->on('tsd_rdw_return_items')->cascadeOnDelete();
            });
        }
    }

    /**
     * Update the database tables if needed
     *
     * @since 1.0.2 non-static and private
     */
    private function __update()
    {
        if (Manager::schema()->hasColumn('tsd_rdw_orders', 'fulfilment_method')) {
            Manager::schema()->table('tsd_rdw_orders', function (Blueprint $table) {
                $table->dropColumn('fulfilment_method');
            });
        }
    }

    /**
     * Drop all tables created by the plugin
     *
     * @since 1.0.2 non-static and private
     */
    private function __drop()
    {
        foreach (self::$tables as $name) {
            Manager::schema()->dropIfExists($name);
        }
    }
}