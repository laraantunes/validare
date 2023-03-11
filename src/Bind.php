<?php
/**
 * 2019 Hood Framework
 */

namespace Validare;

/**
 * Trait Bind
 * @package Validare
 */
trait Bind
{
    /**
     * @var Rule[]
     */
    protected $_rules = [];

    /**
     * @var array
     */
    protected $_validationErrors = [];

    /**
     * @var bool
     */
    protected $_valid;

    /**
     * Define the validation rules
     */
    protected function rules() {}

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->validate();
    }

    /**
     * If no parameters are passed, validates the object. If a $field is passed,
     * includes $rules on $_rules attribute for $field.
     * @param string|null $field The field name to validate
     * @param mixed ...$rules If $field is passed, must have a callable, a string
     * an array with the rule as key and the value to compare, if necessary or a
     * \Validare\Rule object
     * @return bool
     */
    public function validate(?string $field = null, ...$rules): bool
    {
        if (empty($field)) {
            $this->rules();
            $valid = true;
            foreach ($this->_rules as $field => $rule) {
                $ruleValid = $rule($rule->value);
                if (!$ruleValid) {
                    $this->_validationErrors[] = [
                        'field' => $field,
                        'value' => $rule->value,
                        'rule' => $rule->ruleName,
                    ];
                }
                if (!empty($rule->callback)) {
                    $callback = $rule->callback;
                    $callback($ruleValid);
                }
                $valid = $valid && $ruleValid;
            }
            return $this->_valid = $valid;
        }

        $this->addRulesForField($field, $rules);
        return true;
    }

    /**
     * Adds the rules for a field
     * @param $field
     * @param $rules
     */
    protected function addRulesForField($field, $rules)
    {
        foreach ($rules as $rule) {
            $compareValue = null;
            $ruleName = null;

            if ($rule instanceof Rule) {
                $this->_rules[$field] = $rule;
            } else {
                if (is_array($rule)) {
                    if (!empty($rule['rule'])) {
                        $callableRule = $rule['rule'];
                        $compareValue = $rule['compare'] ?? null;
                        $ruleName = $rule['ruleName'] ?? null;
                    } else {
                        $callableRule = array_keys($rule)[0];
                        $compareValue = $rule[$callableRule];
                    }
                } else {
                    $callableRule = $rule;
                }

                $this->_rules[$field] = new Rule($this->$field, $callableRule, $compareValue, $ruleName);
            }
        }
    }

    /**
     * Gets the validation errors
     * @return array
     */
    public function validationErrors(): array
    {
        return $this->_validationErrors;
    }
}