<?php

declare(strict_types=1);
/**
 * refer doc
 * https://github.com/BenSampo/laravel-enum#basic-usage
 * 
 */

namespace App\Enums;

use BenSampo\Enum\Enum;
use Exception;

class BaseEnum extends Enum
{
    /**
     * convert enum to Select component data
     */
    public static function toSelectValue()
    {
        $data = static::asSelectArray();
        $result = collect($data)->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        })->values()->all();

        return $result;
    }

    public static function getSelectValue($enum)
    {
        try {
            $data = static::fromValue($enum);
            return ['id' => $data->value, 'name' => $data->description];
        } catch (Exception $e) {
            return ['id' => null, 'name' => null];
        }
    }
}