<?php

namespace App\Concerns;

use App\MainConfig;

trait HasTemplates
{

    /**
     * Method for returning the desired template
     *
     * @param  string $path
     * @param  array $variables
     * @param  string $extension
     * @return string
     */
    public function template(string $path, array $variables = [], string $extension = 'php')
    {
        $pathWithinViewPath = $path . '.' . $extension;
        $filePath = $this->getFilePath($pathWithinViewPath);

        if (empty($filePath)) return '';

        extract($variables);

        ob_start();
        require $filePath;
        echo ob_get_clean();
    }

    /**
     * Helper to check whether or not to activate a tab on load
     *
     * @param  string $tab
     * @param  bool $default
     * @return bool
     */
    public function tabIsActive(string $tab, $default = false): bool
    {
        if (!isset($_REQUEST['tab']) || empty($_REQUEST['tab'])) {
            return $default;
        }

        return $_REQUEST['tab'] === $tab;
    }

    /**
     * Add tab-link HTML for given arguments
     *
     * @param  string $tab
     * @param  string $title
     * @param  bool $default
     * @param  array $extras
     */
    public function link(string $tab, string $title, bool $default, array $extras = [])
    {

        if (!empty($extras['enabled']) && $extras['enabled'] === false) return;

        ob_start(); ?>

        <li class="nav-item p-lg-2 mb-md-0 mb-sm-0 mb-0 <?php echo !empty($extras['href']) ? 'border-0' : 'border-light'; ?>">
            <a id="<?php echo esc_attr($tab); ?>-tab" class="tab-link pl-lg-0 <?php echo $this->tabIsActive($tab . '-tab', $default) ? 'active' : ''; ?> <?php echo !empty($extras['href']) ? 'btn btn-sm btn-primary text-white d-inline-block fw-light' : 'nav-link'; ?>" href="<?php echo !empty($extras['href']) ? $extras['href'] : ''; ?>" <?php if (empty($extras['href'])) : ?> data-bs-toggle="pill" data-bs-target="#<?php echo esc_attr($tab); ?>" <?php endif; ?> role="<?php echo !empty($extras['href']) ? 'link' : 'tab'; ?>" aria-controls="<?php echo esc_attr($tab); ?>" aria-selected="<?php echo $this->tabIsActive($tab . '-tab', $default) ? 'true' : 'false'; ?>">

                <?php if (!empty($extras['badge']) && !empty($extras['badge']['class']) && !empty($extras['badge']['text'])) : ?>
                    <div class="position-relative <?php echo !empty($extras['href']) ? 'pe-3' : ''; ?>">
                        <span class="link-title">
                            <?php echo esc_html($extras['title']); ?>
                        </span>
                        <?php if (!empty($extras['badge']['icon'])) : ?>
                            <i class="<?php echo esc_attr($extras['badge']['icon']); ?> text-<?php echo esc_attr($extras['badge']['class']); ?> ms-2 position-absolute fs-7 <?php echo !empty($extras['href']) ? 'top-0 end-0' : ''; ?>" alt="<?php echo esc_html($extras['badge']['text']); ?>"></i>
                        <?php else : ?>
                            <div class="badge badge-xs-custom text-white text-bg-<?php echo esc_attr($extras['badge']['class']); ?> rounded-pill fw-light ms-2 position-absolute">
                                <?php echo esc_html($extras['badge']['text']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <span class="link-title">
                        <?php echo esc_html($title); ?>
                    </span>
                <?php endif; ?>

            </a>
        </li>

    <?php print(ob_get_clean());
    }

    /**
     * Add tab-panel HTML for given arguments
     *
     * @param  string $tab
     * @param  bool $default
     * @param  array $extras
     */
    public function panel(string $tab, bool $default = false, array $extras = [])
    {
        ob_start(); ?>

        <div class="tab-pane fade <?php echo $this->tabIsActive($tab . '-tab', $default) ? 'active show' : ''; ?>" id="<?php echo esc_attr($tab); ?>" role="tabpanel" aria-labelledby="panel-tab">
            <?php $this->template('dashboard/dashboard.' . $tab, $extras); ?>
        </div>

<?php print(ob_get_clean());
    }

    /**
     * Get the filepath to use for requiring a file
     * The paths to look for can be edited by third-parties or add-ons
     *
     * @param  string $pathWithinViewPath
     * @return string
     */
    protected function getFilePath(string $pathWithinViewPath): string
    {
        /**
         * Filter: tussendoor_bol_view_paths
         * Can be used by third-parties or add-ons to edit the view paths to look for views
         *
         * @return string
         */
        $viewPaths = apply_filters('tussendoor_bol_view_paths', []);

        // lastly look for the view in the main plugin
        array_push($viewPaths, MainConfig::get('plugin.viewpath'));

        foreach ($viewPaths as $path) {
            if (file_exists($path . $pathWithinViewPath)) {
                return $path . $pathWithinViewPath;
            }
        }

        return '';
    }
}
