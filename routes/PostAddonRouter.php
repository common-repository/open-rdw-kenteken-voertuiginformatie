<?php

namespace Routes;

use Builders\DashboardItemBuilder;
use Routes\Abstracts\AbstractModuleRouter;

class PostAddonRouter extends AbstractModuleRouter
{
    /**
     * The dashboard item builder
     *w
     * @var DashboardItemBuilder
     */
    protected $builder;

    /**
     * Is the history page enabled
     *
     * @var boolean
     */
    protected $enabled = false;

    /**
     * Only show the tab item to fetch history if we haven't fetched it before.
     * Disable this router when we allready did.
     */
    public function beforeRegister()
    {
        if (class_exists('OpenRDWDataExtension')) {
            $this->enabled = true;
        }
    }

    /**
     * Build the dashboard item for the order page
     */
    public function build()
    {
        $this->builder = new DashboardItemBuilder();
        $this->builder->setResource('rdwpost')
            ->setEnabled($this->enabled)
            ->setPriority(250)
            ->setTitle(esc_html__('RDW Postdata', 'tsd-rdw'))
            ->setBadge([
                'text'  => 'Add-on',
                'class' => 'warning',
            ]);

        $this->builder->createDashboardTab();
    }
}
