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

        $this->assertTrue(\Validare\Assert::is_array([1,2,3]));
        $this->assertFalse(\Validare\Assert::is_array('test'));
        $this->assertFalse(\Validare\Assert::is_array(null));
        $this->assertFalse(\Validare\Assert::is_array(1));
        $this->assertFalse(\Validare\Assert::is_array(new \Validare\Assert()));

        $this->assertTrue(\Validare\Assert::is_string('test'));
        $this->assertFalse(\Validare\Assert::is_string(1));
        $this->assertFalse(\Validare\Assert::is_string(null));
        $this->assertFalse(\Validare\Assert::is_string(new \Validare\Assert()));

        $this->assertFalse(\Validare\Assert::is_numeric('test'));
        $this->assertTrue(\Validare\Assert::is_numeric(1));
        $this->assertFalse(\Validare\Assert::is_numeric(null));
        $this->assertFalse(\Validare\Assert::is_numeric(new \Validare\Assert()));

        $this->assertFalse(\Validare\Assert::is_a('test', \Validare\Assert::class));
        $this->assertFalse(\Validare\Assert::is_a([], \Validare\Assert::class));
        $this->assertFalse(\Validare\Assert::is_a(null, \Validare\Assert::class));
        $this->assertTrue(\Validare\Assert::is_a(new \Validare\Assert(), \Validare\Assert::class));

        $this->assertFalse(\Validare\Assert::count_equals('test', 1));
        $this->assertTrue(\Validare\Assert::count_equals([1], 1));
        $this->assertFalse(\Validare\Assert::count_equals([1, 2], 1));

        $this->assertFalse(\Validare\Assert::count_more('test', 1));
        $this->assertTrue(\Validare\Assert::count_more([1, 2], 1));
        $this->assertFalse(\Validare\Assert::count_more([1], 1));

        $this->assertFalse(\Validare\Assert::count_less('test', 1));
        $this->assertTrue(\Validare\Assert::count_less([1], 2));
        $this->assertFalse(\Validare\Assert::count_less([1, 2], 2));

        $this->assertFalse(\Validare\Assert::count_more_equals('test', 1));
        $this->assertTrue(\Validare\Assert::count_more_equals([1], 1));
        $this->assertTrue(\Validare\Assert::count_more_equals([1, 2], 1));
        $this->assertFalse(\Validare\Assert::count_more_equals([1], 2));

        $this->assertFalse(\Validare\Assert::count_less_equals('test', 1));
        $this->assertTrue(\Validare\Assert::count_less_equals([1], 2));
        $this->assertTrue(\Validare\Assert::count_less_equals([1, 2], 2));
        $this->assertFalse(\Validare\Assert::count_less_equals([1, 2, 3], 2));

        $obj = $this->sampleObject();
        $this->assertTrue(\Validare\Assert::has_attribute($obj, 'test'));
        $this->assertFalse(\Validare\Assert::has_attribute($obj, 'field'));

        $obj->test = 'my test';
        $this->assertTrue(\Validare\Assert::has_attribute_value($obj, ['test' => 'my test']));
        $this->assertFalse(\Validare\Assert::has_attribute_value($obj, ['test' => 'wololo']));
        // Field does not exists, returns false
        $this->assertFalse(\Validare\Assert::has_attribute_value($obj, ['field' => 'nope']));
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

    public function testCallbackSuccess()
    {
        $this->expectException(RuntimeException::class);

        $value = 1;
        \Validare\Assert::value(
            $value,
            new \Validare\Rule(
                $value,
                \Validare\Rule::IS_STRING,
                null,
                null,
                function($success){
                    if (!$success) {
                        throw new RuntimeException();
                    }
                }
            )
        );
    }

    public function testCallbackWithUse()
    {
        $value = 1;
        $external = $this;
        \Validare\Assert::value(
            $value,
            new \Validare\Rule(
                $value,
                \Validare\Rule::IS_STRING,
                null,
                null,
                function($success) use ($external) {
                    $external->assertFalse($success);
                }
            )
        );
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

    protected function sampleObject()
    {
        return new class {
            public $test;
        };
    }

}
