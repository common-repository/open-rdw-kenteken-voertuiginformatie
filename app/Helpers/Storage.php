<?php

namespace App\Helpers;

use Adbar\Dot;

class Storage extends Dot
{
    /**
     * Returns the parameter keys.
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($this->items);
    }
    /**
     * Returns the alphabetic characters of the parameter value.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     */
    public function getString($key, $default = '')
    {
        return sanitize_text_field($this->get($key, $default));
    }
    /**
     * Returns the alphabetic characters of the parameter value.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string The filtered value
     */
    public function getTextarea($key, $default = '')
    {
        return sanitize_textarea_field($this->get($key, $default));
    }
    /**
     * Strips out all characters that are not allowable in an email.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string Filtered email address.
     */
    public function getEmail($key, $default = '')
    {
        return sanitize_email($this->get($key, $default));
    }
    /**
     * Sanitizes content for allowed HTML tags for post content.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string Filtered post data
     */
    public function getPost($key, $default = '')
    {
        return wp_kses_post($this->get($key, $default));
    }
    /**
     * Performs esc_url() for database or redirect usage.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string The cleaned URL after esc_url() is run with the 'db' context.
     */
    public function getUrl($key, $default = '')
    {
        return sanitize_url($this->get($key, $default));
    }
    /**
     * Keys are used as internal identifiers. Lowercase alphanumeric characters, dashes, and underscores are allowed.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string The cleaned URL after esc_url() is run with the 'db' context.
     */
    public function getKey($key, $default = '')
    {
        return sanitize_key($this->get($key, $default));
    }
    /**
     * Returns the alphabetic characters of the parameter value.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string The filtered value
     */
    public function getAlpha($key, $default = '')
    {
        return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default));
    }
    /**
     * Returns the alphabetic characters of the parameter value. With spaces
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string The filtered value
     */
    public function getAlphaSpace($key, $default = '')
    {
        return preg_replace('/[^[:alpha:] ]/', '', $this->get($key, $default));
    }
    /**
     * Returns the alphabetic characters and digits of the parameter value.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string The filtered value
     */
    public function getAlnum($key, $default = '')
    {
        return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default));
    }
    /**
     * Returns the digits of the parameter value.
     * @param  string $key     The parameter key
     * @param  string $default The default value if the parameter key does not exist
     * @return string The filtered value
     */
    public function getDigits($key, $default = '')
    {
        // we need to remove - and + because they're allowed in the filter
        return str_replace(['-', '+'], '', $this->filter($key, $default, FILTER_SANITIZE_NUMBER_INT));
    }
    /**
     * Returns the parameter value converted to integer.
     * @param  string $key     The parameter key
     * @param  int    $default The default value if the parameter key does not exist
     * @return int    The filtered value
     */
    public function getInt($key, $default = 0)
    {
        return (int) $this->get($key, $default);
    }
    /**
     * Returns the parameter value converted to float.
     * @param  string $key     The parameter key
     * @param  int    $default The default value if the parameter key does not exist
     * @return int    The filtered value
     */
    public function getFloat($key, $default = 0)
    {
        return (float) $this->get($key, $default);
    }
    /**
     * Returns the parameter value converted to boolean.
     * @param  string $key     The parameter key
     * @param  mixed  $default The default value if the parameter key does not exist
     * @return bool   The filtered value
     */
    public function getBoolean($key, $default = false)
    {
        return $this->filter($key, $default, FILTER_VALIDATE_BOOLEAN);
    }
    /**
     * Returns a boolean if the value is considered not empty.
     * @param  string $key
     * @return bool
     */
    public function isNotEmpty($key)
    {
        return $this->isEmpty($key) === false;
    }
    /**
     * Filter key.
     * @param string $key     Key
     * @param mixed  $default Default = null
     * @param int    $filter  FILTER_* constant
     * @param mixed  $options Filter options
     * @see http://php.net/manual/en/function.filter-var.php
     * @return mixed
     */
    public function filter($key, $default = null, $filter = FILTER_DEFAULT, $options = [])
    {
        $value = $this->get($key, $default);
        // Always turn $options into an array - this allows filter_var option shortcuts.
        if (!\is_array($options) && $options) {
            $options = ['flags' => $options];
        }
        // Add a convenience check for arrays.
        if (\is_array($value) && !isset($options['flags'])) {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }

        return filter_var($value, $filter, $options);
    }
}
