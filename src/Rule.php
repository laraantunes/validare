<?php
/**
 * 2019 Validare
 */

namespace Validare;

/**
 * Class Rule
 * @package Validare
 */
class Rule
{
    /**
     * Required Rule
     */
    const REQUIRED = 'required';

    /**
     * Value must be positive
     */
    const POSITIVE = 'positive';

    /**
     * Value must be negative
     */
    const NEGATIVE = 'negative';

    /**
     * Value's length must be equals $compareValue
     */
    const LENGTH = 'length';

    /**
     * Rule: Value has at least $compareValue characters length
     */
    const MIN_LENGTH = 'min_length';
    /**
     * Rule: Value has $compareValue characters length at maximum
     */
    const MAX_LENGTH = 'max_length';

    /**
     * Rule: Value must be more than $compareValue
     */
    const MORE = 'more';
    /**
     * Rule: Value must be less than $compareValue
     */
    const LESS = 'less';
    /**
     * Rule: Value must be more or equals than $compareValue
     */
    const MORE_OR_EQUALS = 'more_or_equals';
    /**
     * Rule: Value must be less or equals than $compareValue
     */
    const LESS_OR_EQUALS = 'less_or_equals';
    /**
     * Rule: Value must be equals $compareValue
     */
    const EQUALS = 'equals';
    /**
     * Rule: Value must be not equals $compareValue
     */
    const NOT_EQUALS = 'not_equals';

    /**
     * Rule: Value must be an array
     */
    const IS_ARRAY = 'is_array';

    /**
     * Rule: Value must be a string
     */
    const IS_STRING = 'is_string';

    /**
     * Rule: Value must be numeric
     */
    const IS_NUMERIC = 'is_numeric';

    /**
     * Rule: Value must be of the Classname passed at $compareValue
     */
    const IS_A = 'is_a';

    /**
     * Rule: Array count must be equals $compareValue
     */
    const COUNT_EQUALS = 'count_equals';

    /**
     * Rule: Array count must be more than $compareValue
     */
    const COUNT_MORE = 'count_more';

    /**
     * Rule: Array count must be less than $compareValue
     */
    const COUNT_LESS = 'count_less';

    /**
     * Rule: Array count must be more or equals than $compareValue
     */
    const COUNT_MORE_EQUALS = 'count_more_equals';

    /**
     * Rule: Array count must be less or equals than $compareValue
     */
    const COUNT_LESS_EQUALS = 'count_less_equals';

    /**
     * Rule: Object has attribute $compareValue defined
     */
    const HAS_ATTRIBUTE = 'has_attribute';

    /**
     * Rule: Object has attribute at $compareValue with the following definition:
     * ['attribute' => 'value'] Ex: ['test' => 99]
     */
    const HAS_ATTRIBUTE_VALUE = 'has_attribute_value';

    /**
     * @var callable|string
     */
    protected $rule;

    /**
     * @var mixed
     */
    protected $compareValue;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $ruleName;

    /**
     * @var callable
     */
    public $callback;

    /**
     * Rule constructor.
     * @param mixed $value
     * @param callable|string $rule
     * @param mixed $compareValue
     * @param string $ruleName
     * @param callable $callback
     */
    public function __construct($value, $rule, $compareValue = null, string $ruleName = null, $callback = null)
    {
        $this->value = $value;
        $this->rule = $rule;
        $this->compareValue = $compareValue;
        $this->ruleName = $ruleName ?? is_string($rule) ? $rule : 'Validation';
        $this->callback = $callback;
    }

    /**
     * Executes a rule
     * @param mixed $value
     * @return bool
     */
    public function __invoke($value) {
        $callRule = $this->rule;
        if (is_string($callRule) && method_exists($this, $callRule)) {
            return $this->$callRule($value);
        }
        return $callRule($value);
    }

    /**
     * Required Rule
     * @return bool
     */
    protected function required($a): bool
    {
        return !empty($a);
    }

    /**
     * Value must be positive
     * @param $a
     * @return bool
     */
    protected function positive($a): bool
    {
        return $a > 0;
    }

    /**
     * Value must be negative
     * @param $a
     * @return bool
     */
    protected function negative($a): bool
    {
        return $a < 0;
    }

    /**
     * Value's length must be equals $compareValue
     * @param $a
     * @return bool
     */
    protected function length($a): bool
    {
        return strlen($a) == $this->compareValue;
    }

    /**
     * Validation Rule: Value has at least $compareValue characters length
     * @param $a
     * @return bool
     */
    protected function min_length($a): bool
    {
        return strlen($a) >= $this->compareValue;
    }
    /**
     * Validation Rule: Value has $compareValue characters length at maximum
     * @param $a
     * @return bool
     */
    protected function max_length($a): bool
    {
        return strlen($a) <= $this->compareValue;
    }

    /**
     * Validation Rule: Value must be more than $compareValue
     * @param $a
     * @return bool
     */
    protected function more($a): bool
    {
        return $a > $this->compareValue;
    }
    /**
     * Validation Rule: Value must be less than $compareValue
     * @param $a
     * @return bool
     */
    protected function less($a): bool
    {
        return $a < $this->compareValue;
    }
    /**
     * Validation Rule: Value must be more or equals than $compareValue
     * @param $a
     * @return bool
     */
    protected function more_or_equals($a): bool
    {
        return $a >= $this->compareValue;
    }
    /**
     * Validation Rule: Value must be less or equals than $compareValue
     * @param $a
     * @return bool
     */
    protected function less_or_equals($a): bool
    {
        return $a <= $this->compareValue;
    }

    /**
     * Validation Rule: Value must be equals to $compareValue
     * @param $a
     * @return bool
     */
    protected function equals($a): bool
    {
        return $a == $this->compareValue;
    }
    /**
     * Validation Rule: Value must be not equals to $compareValue
     * @param $a
     * @return bool
     */
    protected function not_equals($a): bool
    {
        return $a != $this->compareValue;
    }

    /**
     * Validation Rule: Value must be an array
     * @param $a
     * @return bool
     */
    protected function is_array($a): bool
    {
        return is_array($a);
    }

    /**
     * Validation Rule: Value must be a string
     * @param $a
     * @return bool
     */
    protected function is_string($a): bool
    {
        return is_string($a);
    }

    /**
     * Validation Rule: Value must be numeric
     * @param $a
     * @return bool
     */
    protected function is_numeric($a): bool
    {
        return is_numeric($a);
    }

    /**
     * Validation Rule: Value must be of the Classname passed at compareValue
     * @param $a
     * @return bool
     */
    protected function is_a($a): bool
    {
        return is_a($a, $this->compareValue);
    }

    /**
     * Validation Rule: Array count must be equals compareValue
     * @param $a
     * @return bool
     */
    protected function count_equals($a): bool
    {
        if (!is_array($a)) {
            return false;
        }
        return count($a) == $this->compareValue;
    }

    /**
     * Validation Rule: Array count must be more than compareValue
     * @param $a
     * @return bool
     */
    protected function count_more($a): bool
    {
        if (!is_array($a)) {
            return false;
        }
        return count($a) > $this->compareValue;
    }

    /**
     * Validation Rule: Array count must be less than compareValue
     * @param $a
     * @return bool
     */
    protected function count_less($a): bool
    {
        if (!is_array($a)) {
            return false;
        }
        return count($a) < $this->compareValue;
    }

    /**
     * Validation Rule: Array count must be more or equals than compareValue
     * @param $a
     * @return bool
     */
    protected function count_more_equals($a): bool
    {
        if (!is_array($a)) {
            return false;
        }
        return count($a) >= $this->compareValue;
    }

    /**
     * Validation Rule: Array count must be less or equals than compareValue
     * @param $a
     * @return bool
     */
    protected function count_less_equals($a): bool
    {
        if (!is_array($a)) {
            return false;
        }
        return count($a) <= $this->compareValue;
    }

    /**
     * Validation Rule: Object has attribute compareValue defined
     * @param $a
     * @return bool
     */
    protected function has_attribute($a): bool
    {
        if (!is_string($this->compareValue)) {
            return false;
        }
        return property_exists($a, $this->compareValue);
    }

    /**
     * Validation Rule: Object has attribute value like compareValue
     * @param $a
     * @return bool
     */
    protected function has_attribute_value($a): bool
    {
        if (!is_array($this->compareValue)) {
            return false;
        }
        $attr = array_keys($this->compareValue)[0];
        $value = array_values($this->compareValue)[0];
        if (!property_exists($a, $attr)) {
            return false;
        }
        return $a->$attr == $value;
    }
}