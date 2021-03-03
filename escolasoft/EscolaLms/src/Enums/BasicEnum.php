<?php

namespace EscolaSoft\EscolaLms\Enums;

use BenSampo\Enum\Enum;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class BasicEnum extends Enum
{
    /**
     * getAssoc
     *
     * @return array
     */
    public static function getAssoc(bool $pretty = false): array
    {
        if ($pretty) {
            return parent::asSelectArray();
        } else {
            $array = parent::asArray();
            $array = array_flip($array);
            return array_map(fn ($val) => str_replace('_', ' ', $val), $array);
        }
    }

    public static function getDetails(): Collection
    {
        $array = parent::asArray();
        $result = new Collection();
        foreach ($array as $const => $value) {
            $result->push(static::singleDetail($const, $value));
        }
        return $result;
    }

    protected static function singleDetail(string $const, $value): \stdClass
    {
        $enum = new \stdClass();
        $enum->const = $const;
        $enum->value = $value;
        $enum->label = self::getDescription($value);
        return $enum;
    }

    /**
     * @param string $name
     * @param bool $strict
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function isValidName(string $name, $strict = false): bool
    {
        if ($strict) {
            return parent::hasKey($name);
        }

        $keys = array_map('strtolower', parent::getKeys());
        return in_array(strtolower($name), $keys);
    }

    /**
     * @param $value
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function isValidValue($value): bool
    {
        return parent::hasValue($value);
    }

    /**
     * @param $value
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public static function getName($value, ?callable $filterCallback = null): ?string
    {
        if (self::isValidValue($value)) {
            $search = parent::getKey($value);

            if ($filterCallback) {
                return call_user_func($filterCallback, $search);
            } else {
                return $search;
            }
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public static function getValue(string $name)
    {
        if (self::isValidName($name)) {
            return parent::getValue(Str::upper($name));
        }

        return null;
    }
}
