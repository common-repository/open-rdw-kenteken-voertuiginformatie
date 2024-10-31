<?php

namespace App\Helpers;

class Notice
{
    public static $instance;

    protected $key;
    protected $message;
    protected $state = 'info';
    protected $priority = 10;

    private $transientId = 'tsd_notices';

    /**
     * Make instance accessible via this static method
     *
     * @param string $message The text to display in the notice
     * @param int $state The type op notice, determines the color. 1: Success. 2: Fail. 3: Warning. 4: Info.
     * @return self
     */
    public static function instance(string $message = '', int $state = 0)
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        if (!empty($message)) {
            self::$instance->setMessage($message);
        }

        if ($state != 0) {
            self::$instance->setState($state);
        }

        return self::$instance;
    }

    /**
     * The contents of the notice are considered as a failed action.
     * @param  string $message
     * @return self
     */
    public function failed($message = null)
    {
        if (!empty($message)) {
            $this->setMessage($message);
        }

        return $this->setState(2);
    }

    /**
     * The contents of the notice are considered as a successful action.
     * @param  string $message
     * @return self
     */
    public function successful($message = null)
    {
        if (!empty($message)) {
            $this->setMessage($message);
        }

        return $this->setState(1);
    }

    /**
     * The contents of the notice are considered as a successful action.
     * @param  string $message
     * @return self
     */
    public function warning($message = null)
    {
        if (!empty($message)) {
            $this->setMessage($message);
        }

        return $this->setState(3);
    }

    /**
     * The contents of the notice are considered as a successful action.
     * @param  string $message
     * @return self
     */
    public function info($message = null)
    {
        if (!empty($message)) {
            $this->setMessage($message);
        }

        return $this->setState(4);
    }

    /**
     * Wether the notice should be displayed as an success or as an error.
     * @param bool $success
     */
    public function setState($state)
    {
        switch ($state) {
            case 1:
                $this->state = 'success';
                break;

            case 2:
                $this->state = 'error';
                break;

            case 3:
                $this->state = 'warning';
                break;

            default:
                $this->state = 'info';
                break;
        }

        return $this;
    }

    /**
     * Wether the notice should be displayed as an success or as an error.
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->state = $success;

        return $this;
    }

    /**
     * Set the message that's being displayed to the user.
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set the priority of the notice, which sets the action priority. A higher
     * priority means the notice will be displayed lower.
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = (int) $priority;

        return $this;
    }

    /**
     * Set the key of the notice.
     * Will be used in the array of notices, which makes it easier to hide duplicates.
     * Or only show one notice about the same issue.
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = (string) $key;

        return $this;
    }

    /**
     * Get all notices and unset notices with a specified key
     * @param string $key
     */
    public function removeByKey($key)
    {
        $notices = $this->get();
        unset($notices[$key]);

        return $this->save($notices);
    }

    /**
     * Create the notice. Stores it within the transient table,
     * as the user is redirected after a post has been saved.
     * @return bool
     */
    public function create()
    {
        $notices = $this->get();
        $messageHtml = $this->generateHtml();

        if (!empty($this->key)) {
            $notices[$this->key] = $messageHtml;
        } else {
            array_push($notices, $messageHtml);
        }

        return $this->save($notices);
    }

    /**
     * Display all stored notices. If none are found, this method returns false.
     * This method deletes all stored notices after it has finished running.
     * @return bool
     */
    public function display()
    {
        $notices = $this->get();
        if (empty($notices)) {
            return false;
        }

        foreach ($notices as $notice) {
            $this->setNoticeAction($notice);
        }

        return $this->delete();
    }

    /**
     * Ceate an error code and append it to the end of the message
     *
     * @param  string $code
     * @return self
     */
    public function errorCode(string $code)
    {
        $this->message = $this->message . ' <strong>Error code: ' . $code . ' </strong>';
        return $this;
    }

    /**
     * Generate the notice HTML. Uses WordPress css classes
     * @return string
     */
    protected function generateHtml()
    {
        return sprintf('
            <div class="notice notice-%s is-dismissible d-inline-flex align-items-center gap-2 tussendoor-notice">
                <p class="m-0 p-0">%s</p>
            </div>
        ', $this->state, $this->message);
    }

    /**
     * Set the action within WordPress used for Admin notices
     * @param string $notice
     */
    protected function setNoticeAction($notice)
    {
        add_action('all_admin_notices', function () use ($notice) {
            echo $notice;
        }, $this->priority);
    }

    /**
     * Get all stored notices from the transients table.
     * @return array
     */
    private function get()
    {
        $existing = get_transient($this->transientId);
        return $existing === false ? [] : array_unique($existing);
    }

    /**
     * Save a single notice to a transient. The transient is stored for 60 seconds.
     * @param  mixed $messages
     * @return bool
     */
    private function save($messages)
    {
        delete_transient($this->transientId);
        return set_transient($this->transientId, $messages, 60);
    }

    /**
     * Delete all notice transients, identified by our own unique identifier.
     * @return bool
     */
    private function delete($force = false)
    {
        if ($force) {
            return delete_transient($this->transientId);
        }

        return add_action('all_admin_notices', function () {
            return delete_transient($this->transientId);
        }, $this->priority);
    }
}
