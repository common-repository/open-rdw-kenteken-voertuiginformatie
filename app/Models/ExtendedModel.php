<?php namespace Tussendoor\Bol\Models;

use Tussendoor\Bol\Helpers\Helper;
use Tussendoor\Bol\Helpers\Listing;
use Tussendoor\Bol\Vendor\Illuminate\Database\Eloquent\Model;

class ExtendedModel extends Model
{    
    /**
     * Get translated attribute value
     * 
     * @example OrderItemFulfilment->getTranslatedMethodAttribute()
     *
     * @param  string $value
     * @return string
     */
    public function translated(string $value)
    {
        if (!isset($this->$value)) return '';

        $AttributeName = Helper::underscoreToUpperCamelCase($value);

        if (!method_exists($this, 'getTranslated'.$AttributeName.'Attribute')) {
            return $this->$value;
        }

        return $this->{'getTranslated'.$AttributeName.'Attribute'}($this->$value);
    }
    
    /**
     * Get value in given format
     * This has the potential for a standalone Formatter class, for now we use the Helper
     *
     * @param  string $value
     * @param  string $formatter
     * @return string
     */
    public function formatted(string $value, string $formatter)
    {
        if (isset($this->$value)) {
            $value = $this->$value;
        }
        
        $formatMethod = 'format'.ucfirst($formatter);
        if (!method_exists(Helper::class, $formatMethod)) {
            return $value;
        }
        
        return Helper::{$formatMethod}($value);
    }
    
    /**
     * Create a listing witg the given values
     *
     * @param  array $fields
     * @param  int $renderType
     * @return string
     */
    public function listing(array $fields, int $renderType = Listing::RENDER_DEFAULT)
    {
        $values = array();
        $labels = array();
    
        foreach ($fields as $field => $data)
        {
            if (is_string($field)){
                $values[$field] = $this->getPropertyValue($field, ($data['translated'] ?? false), ($data['formatter'] ?? ''));
                $labels[$field] = $data;
            } else {
                $values[$data] = $this->getPropertyValue($data, ($data['translated'] ?? false), ($data['formatter'] ?? ''));
            }
        }
    
        return new Listing($values, $labels, $renderType);
    }
    
    /**
     * Get the value of the given property
     *
     * @param  string $field
     * @param  bool $translated
     * @param  string $formatter
     * @return string
     */
    public function getPropertyValue(string $field, bool $translated = false, string $formatter = '')
    {
        $properties = explode('.', $field);
		$model = $this;
        $value = '';

        foreach ($properties as $property) {

            if (is_object($model->{$property})) {
                $model = $model->{$property};
            } else {

                if ($translated) {
                    $value = $model->translated($property);
                    continue;
                }

                if (!empty($formatter)) {
                    $value = $model->formatted($property, $formatter);
                    continue;
                }

                $value = $model->{$property};
            }

        }

        return !is_object($value) ? $value : '';
    }
}