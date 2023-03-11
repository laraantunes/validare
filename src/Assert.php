<?php
/**
 * 2019 Hood Framework
 */

namespace Validare;

/**
 * Class Assert
 * @example Assert::required($value)
 * @example Assert::length($value, $compareValue)
 * @example Assert::length($value, $compareValue)
 * @package Validare
 */
class Assert
{
    /**
     * Magic call handler
     * @param $name
     * @param $arguments
     * @return bool
     */
    public static function __callStatic($name, $arguments): bool
    {
        $obj = new class($arguments[0]){
            use Bind;
            public $field;

            public function __construct($field)
            {
                $this->field = $field;
            }
        };
        $obj->validate('field', [$name => $arguments[1] ?? null]);
        return $obj->valid();
    }

    /**
     * Validates a specific value
     * @param mixed $value
     * @param mixed ...$rules
     * @return bool
     */
    public static function value($value, ...$rules): bool
    {
        $obj = new class($value){
            use Bind;
            public $field;

            public function __construct($field)
            {
                $this->field = $field;
            }
        };
        $obj->validate('field', ...$rules);
        return $obj->valid();
    }
}