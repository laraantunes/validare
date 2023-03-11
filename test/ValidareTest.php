<?php
/**
 * 2019 Hood Framework
 */

use \PHPUnit\Framework\TestCase;

class ValidareTest extends TestCase {

    /**
     * Tests the magic call for Assert
     */
    public function testAssertMagic() {
        $required = \Validare\Assert::required(null);
        $this->assertFalse($required);

        $equals = \Validare\Assert::equals(5, 5);
        $this->assertTrue($equals);

        $length = \Validare\Assert::length('test', 5);
        $this->assertFalse($length);
    }

    public function testAssertValue() {
        $required = \Validare\Assert::value('Valid', \Validare\Rule::REQUIRED);
        $this->assertTrue($required);

        $length = \Validare\Assert::value('Non-valid', [\Validare\Rule::LENGTH => 4]);
        $this->assertFalse($length);

        $l_or_eq = \Validare\Assert::value(2, [\Validare\Rule::LESS_OR_EQUALS => 5], [\Validare\Rule::MORE => 3]);
        $this->assertFalse($l_or_eq);

        $equals = \Validare\Assert::value(1, new \Validare\Rule(1, \Validare\Rule::EQUALS, 1));
        $this->assertTrue($equals);

    }

    /**
     * Tests the bind trait
     * @dataProvider dataProvider
     * @param object $object
     */
    public function testBindTrait($object, $valid) {
        $object->valid();
        $this->assertEquals($valid, $object->valid());
    }

    /**
     * Data Provider for test
     * @return array
     */
    public function dataProvider() {
        return [
            [$this->mockObject(null, \Validare\Rule::REQUIRED), false],
            [$this->mockObject('', \Validare\Rule::REQUIRED), false],
            [$this->mockObject('Test', \Validare\Rule::REQUIRED), true],
            [$this->mockObject('Test', [\Validare\Rule::LENGTH => 4]), true],
            [$this->mockObject(5, [\Validare\Rule::MORE => 4], [\Validare\Rule::LESS => 7]), true],
            [$this->mockObject(3, \Validare\Rule::POSITIVE, [\Validare\Rule::MORE => 4]), false],
            [$this->mockObject('Test', function($a){$a == 'test';}), false],
            [$this->mockObject(5, [\Validare\Rule::EQUALS => 5]), true],
            [$this->mockObject(5, \Validare\Rule::NEGATIVE), false],
            [$this->mockObject(5, [\Validare\Rule::NOT_EQUALS => 5]), false],
            [$this->mockObject(5, [\Validare\Rule::LESS_OR_EQUALS => 5]), true],
            [$this->mockObject(2, [\Validare\Rule::LESS_OR_EQUALS => 5]), true],
            [$this->mockObject(2, [\Validare\Rule::LESS_OR_EQUALS => 5], [\Validare\Rule::MORE => 3]), false],
            [$this->mockObject('test', [\Validare\Rule::MAX_LENGTH => 5]), true],
            [$this->mockObject('test', [
                'rule' => \Validare\Rule::MAX_LENGTH,
                'compare' => 5,
                'ruleName' => 'My Rule Name',
            ]), true],
            [$this->mockObject('Run Barry, Run!', [\Validare\Rule::MIN_LENGTH => 5]), true],
            [$this->mockObject(1, new \Validare\Rule(1, \Validare\Rule::EQUALS, 1)), true],
        ];
    }

    /**
     * Helper for mocking the Bind trait
     * @param mixed $value
     * @param mixed ...$validations
     * @return object
     */
    protected function mockObject($value,...$validations) {
        return new class($value,...$validations) {
            use \Validare\Bind;

            public $myField;

            protected $validations;

            public function __construct($myField, ...$validations)
            {
                $this->myField = $myField;
                $this->validations = $validations;
            }

            protected function rules()
            {
                $this->validate('myField', ...$this->validations);
            }
        };
    }
}
