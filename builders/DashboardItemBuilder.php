<?php

namespace Builders;

use Tussendoor\Bol\Vendor\collect;

class DashboardItemBuilder
{
    public $item = [];
    public $panel = [];
    public $badge = [];
    protected $default = false;
    protected $enabled;
    public $priority = 9999;
    protected $resource;
    protected $title;
    protected $url;

    /**
     * Set the resource of the item
     *
     * Resource will determine where the view will be found.
     * @example $resource = 'order'; View searched in viewpath at dashboard/dashboard.orders.php
     *
     * @param string $priority
     * @return self
     */
    public function setResource(string $resource)
    {
        $this->resource = sanitize_text_field($resource);
        return $this;
    }

    /**
     * Set the item as default
     * When default this dashboard item will open on page load
     *
     * @param bool $default
     * @return self
     */
    public function setDefault(bool $default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Set the item enabled
     *
     * @param  bool $enabled
     * @return self
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Set the item priority
     * Will detemrine the order of nav items
     *
     * @param  int $priority
     * @return self
     */
    public function setPriority(int $priority)
    {
        $this->priority = ($priority < 100 ? $priority * 100 : $priority);
        return $this;
    }

    /**
     * Set the item title
     *
     * @param  string $title
     * @return self
     */
    public function setTitle(string $title)
    {
        $this->title = sanitize_text_field($title);
        return $this;
    }

    /**
     * Set the item url
     *
     * @param  string $url
     * @return self
     */
    public function setUrl(string $url)
    {
        $this->url = sanitize_url($url);
        return $this;
    }

    /**
     * Set the item badge
     *
     * @param  array $badge
     * @return self
     */
    public function setBadge(array $badge)
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * Call this method to add a navigation item AND a panel
     * Should be used for tabs
     */
    public function createDashboardTab()
    {
        $this->addItemToDashboardNavigation();
        $this->addPanelToDashboard();
    }

    /**
     * Call this method to only add a navigation item
     * Should be used for buttons with an external link
     */
    public function addItemToDashboardNavigation()
    {
        add_filter('tussendoor_bol_dashboard_navigation_items', [$this, 'createItem']);
    }

    /**
     * Call this method to add a panel to the dashboard
     * Will be connected to a navigation item and will make a tabblad
     * Should not be used, use createDashboardTab() instead.
     */
    private function addPanelToDashboard()
    {
        add_filter('tussendoor_bol_dashboard_panels', [$this, 'createPanel']);
    }

    /**
     * Return the navigation item
     *
     * @return array
     */
    public function getNavigationItem()
    {
        return $this->item;
    }

    /**
     * Return navigation item, but only its values
     *
     * @return array
     */
    public function getNavigationItemValues()
    {
        return $this->item[$this->resource];
    }

    /**
     * Return the dashboard panel
     *
     * @return array
     */
    public function getDashboardPanel()
    {
        return $this->panel;
    }

    /**
     * Return navigation panel, but only its value
     *
     * @return array
     */
    public function getDashboardPanelValue()
    {
        return $this->panel[$this->resource];
    }

    /**
     * Return the resource
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Add item to the navigation bar
     *
     * @param  array $links
     * @return array
     */
    public function createItem(array $links)
    {
        if (!$this->enabled) return $links;

        if (empty($this->item)) {
            $this->createNavigationItem();
        }

        return collect($links)->merge($this->item)->toArray();
    }

    /**
     * Add panel to the dashboard
     *
     * @param  array $panels
     * @return array
     */
    public function createPanel(array $panels)
    {
        if (!$this->enabled) return $panels;

        if (empty($this->panel)) {
            $this->createDashboardPanel();
        }

        return collect($panels)->merge($this->panel)->toArray();
    }


    /**
     * Create a navigation item for in the dahsboard of the Bol plugin
     */
    public function createNavigationItem()
    {
        // Return early if the class is missing a resource, title or priority. Or is not enabled.
        if (empty($this->resource) || empty($this->title) || empty($this->priority) || !$this->enabled) return null;

        $this->item[$this->resource] = [
            'title'     => $this->title,
            'default'   => $this->default,
            'priority'  => $this->priority,
        ];

        if (!empty($this->url)) {
            $this->item[$this->resource]['href'] = esc_url($this->url);
        }

        // Text and class are mandatory for a badge
        if (!empty($this->badge) && !empty($this->badge['text']) && !empty($this->badge['class'])) {
            $this->item[$this->resource]['badge']['text'] = sanitize_text_field($this->badge['text']);
            $this->item[$this->resource]['badge']['class'] = sanitize_text_field($this->badge['class']);

            // An icon is optional but will replace the text
            if (!empty($this->badge['icon'])) {
                $this->item[$this->resource]['badge']['icon'] = sanitize_text_field($this->badge['icon']);
            }
        }

        return $this;
    }

    /**
     * Create the panel with the given data
     *
     * @since 15-02-23 Extras was added later and is not yet implemented in the builder
     *
     * @param $extras
     * @return self
     */
    public function createDashboardPanel(array $extras = [])
    {
        $this->panel[$this->resource] = [
            'default'   => $this->default,
            'extras'    => $extras,
        ];
        return $this;
    }
}
