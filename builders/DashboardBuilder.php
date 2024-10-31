<?php

namespace Builders;

use Builders\DashboardItemBuilder;
use Illuminate\Support\Collection;
use Builders\Abstracts\AbstractBuilder;

class DashboardBuilder extends AbstractBuilder
{
    /**
     * The dashboard items
     * Specifically an array and not a Collection for easy handeling in the filters
     * Can be usefull for third parties to add items to the dashboard
     *
     * @var array
     */
    protected $items = [];

    /**
     * The dashboard panels
     * Specifically an array and not a Collection for easy handeling in the filters
     * Can be usefull for third parties to add panels to the dashboard
     *
     * @var array
     */
    protected $panels = [];

    /**
     * Build dashboard items based on the given array
     * Is used by the DashboardRouter to create default dashboard items
     *
     * @param  array $items
     * @return self
     */
    public function buildDashboardItems(array $items)
    {
        $items = $this->hydrateItems($items);
        $builders = $this->getDashboardItemBuilders($items);

        $this->items = $this->createDashboardNavigationItems($builders);
        $this->panels = $this->createDashboardPanels($builders);

        return $this;
    }

    /**
     * Reject all items that do not contain mandatory keys
     *
     * @param  array $items
     * @return Collection
     */
    private function hydrateItems(array $items)
    {
        $items = (new Collection($items))->reject(function ($item) {
            return !isset($item['title']) && !isset($item['default']) && !isset($item['priority']);
        });

        return $items;
    }

    /**
     * Build navigation items based on the given array
     *
     * @param  Collection $items Mandatory key value pairs are title=>string, default=>bool and priority=>int
     * @return Collection
     */
    private function getDashboardItemBuilders(Collection $items)
    {
        $builders = $items->map(function ($item, $resource) {
            $item = $this->createDashboardItemBuilder($resource, $item['title'], $item['default'], $item['priority'], true);
            return $item;
        });

        return $builders;
    }

    /**
     * Create a dashboard item builder with the given data
     *
     * @param  string $resource
     * @param  string $title
     * @param  bool $default
     * @param  int $priority
     * @param  bool $enabled
     * @return DashboardItemBuilder
     */
    private function createDashboardItemBuilder(string $resource, string $title, bool $default, int $priority, bool $enabled)
    {
        $builder = new DashboardItemBuilder();
        $builder->setResource($resource)->setTitle($title)->setPriority($priority)->setEnabled($enabled)->setDefault($default)->createNavigationItem()->createDashboardPanel();
        return $builder;
    }

    /**
     * Create navigation items based on the given collection
     *
     * @param  Collection $items Mandatory key value pairs are title=>string, default=>bool and priority=>int
     * @return array
     */
    private function createDashboardNavigationItems(Collection $builders)
    {
        $items = collect();

        $builders->each(function ($builder) use (&$items) {
            $items->put($builder->getResource(), $builder->getNavigationItemValues());
        });

        return $items->sortBy('priority')->toArray();
    }

    /**
     * Create navigation items based on the given array
     *
     * @param  Collection $items Mandatory key value pairs are title=>string, default=>bool and priority=>int
     * @return array
     */
    private function createDashboardPanels(Collection $builders)
    {
        $panels = collect();

        $builders->each(function ($builder) use (&$panels) {
            $panels->put($builder->getResource(), $builder->getDashboardPanelValue());
        });

        return $panels->toArray();
    }

    /**
     * Get all the the navigation items for the dashboard of this plugin
     *
     * @return array
     */
    public function getDashboardNavigationItems()
    {
        /**
         * Filter can be used to hook into by routers or by third parties
         *
         * @uses apply_filters('tussendoor_bol_dashboard_navigation_items')
         *
         * @return array
         */
        $items = $this->filter('tussendoor_bol_dashboard_navigation_items', $this->items);

        // Sort the navigation items by priority
        $items = collect($items)->map(function ($link) {
            $link['priority'] = !empty($link['priority']) ? $link['priority'] : 9999;
            return $link;
        })->sortBy('priority');

        return $items;
    }

    /**
     * Get the panels to connect the navigation items to content in the plugin dashboard
     *
     * @return array
     */
    public function getDashboardPanels()
    {
        /**
         * Filter can be used to hook into by routers or by third parties
         *
         * @uses apply_filters('tussendoor_bol_dashboard_panels')
         *
         * @return array
         */
        $panels = $this->filter('tussendoor_bol_dashboard_panels', $this->panels);

        return $panels;
    }
}
