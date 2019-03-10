<?php
/**
 * 2019 Hood Framework
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
    protected function min_length($a)
    {
        return strlen($a) >= $this->compareValue;
    }
    /**
     * Validation Rule: Value has $compareValue characters length at maximum
     * @param $a
     * @return bool
     */
    protected function max_length($a)
    {
        return strlen($a) <= $this->compareValue;
    }

    /**
     * Validation Rule: Value must be more than $compareValue
     * @param $a
     * @return bool
     */
    protected function more($a)
    {
        return $a > $this->compareValue;
    }
    /**
     * Validation Rule: Value must be less than $compareValue
     * @param $a
     * @return bool
     */
    protected function less($a)
    {
        return $a < $this->compareValue;
    }
    /**
     * Validation Rule: Value must be more or equals than $compareValue
     * @param $a
     * @return bool
     */
    protected function more_or_equals($a)
    {
        return $a >= $this->compareValue;
    }
    /**
     * Validation Rule: Value must be less or equals than $compareValue
     * @param $a
     * @return bool
     */
    protected function less_or_equals($a)
    {
        return $a <= $this->compareValue;
    }

    /**
     * Validation Rule: Value must be equals to $compareValue
     * @param $a
     * @return bool
     */
    protected function equals($a)
    {
        return $a == $this->compareValue;
    }
    /**
     * Validation Rule: Value must be not equals to $compareValue
     * @param $a
     * @return bool
     */
    protected function not_equals($a)
    {
        return $a != $this->compareValue;
    }
}