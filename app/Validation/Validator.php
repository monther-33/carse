<?php

namespace App\Validation;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator as BaseValidator;

/**
 * Field names in messages: when no label exists for the full key ("form.account_id",
 * "items.0.price", "lines.2.debit"), fall back to the label of the field itself
 * ("account_id", "price", "debit"), so one dictionary in lang/{locale}/validation.php
 * names every form field, however deeply it is nested. List items ("roles.0") use the
 * list's label.
 */
class Validator extends BaseValidator
{
    public function getDisplayableAttribute($attribute)
    {
        $primary = $this->getPrimaryAttribute($attribute);

        foreach (array_unique([$attribute, $primary]) as $name) {
            if ($this->getAttributeFromLocalArray($name) || $this->getAttributeFromTranslations($name)) {
                return parent::getDisplayableAttribute($attribute);
            }
        }

        $segments = explode('.', $attribute);
        while ($segments !== [] && (is_numeric(end($segments)) || end($segments) === '*')) {
            array_pop($segments);
        }

        if ($segments !== []) {
            $field = Str::snake((string) end($segments));
            if ($field !== $attribute && ($label = $this->getAttributeFromTranslations($field))) {
                return $label;
            }
        }

        return parent::getDisplayableAttribute($attribute);
    }
}
