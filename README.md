# Validare
A simple to use validation library.

## Installation
`composer require laraantunes/validare`

## Basic Usage
Create a class and use the `\Validare\Bind` trait and add the rules by the validate() method:
```php
class MyClass {
    use \Validare\Bind;
    
    public $myField;
   
   /**
    * Validare includes the rules() method that is called 
    * before the proper validation to bind the field 
    * validations
    */ 
   public function rules() {
       $this->validate('myField', \Validare\Rule::REQUIRED);
   }
}

$myObj = new MyClass();
echo $myObj->valid(); // It'll return false

// It'll return an array with all the validation errors found
var_dump($myObj->validationErrors());
```

### The validate() method
The `valid()` method is an alias for `validate()`, without any parameter. This way, it'll run the validation for the object.
If `validate()` is called with parameters, it'll add a validation for the field passed as the first parameter with the rules passed as the other parameters:
```php
// Adds a REQUIRED validation for myField:
$myObj->validate('myField', \Validare\Rule::REQUIRED);

// You may pass more than one rule when calling validate()!
$myObj->validate('myField',  [\Validare\Rule::LESS_OR_EQUALS => 5], [\Validare\Rule::MORE => 3]);

// When passing a value that must be compared, pass the rule as an array:
$myObj->validate('myField', [\Validare\Rule::LENGTH => 4]);

// You need to use the full array syntax if you need to pass a custom rule name for errors:
$myObj->validate('myField', [
    'rule' => \Validare\Rule::MAX_LENGTH,
    'compare' => 5,
    'ruleName' => 'My Rule Name',
]);

// You may also pass closures as rules!
$myObj->validate('myField', function($value){
    return $value->value == 5;
});

// And even with 'use' clause:
$myObj->validate('myField', function($value) use ($customValue){
    return $value->value == $customValue;
});

// And if you need to handle all the \Validare\Rule object, you may pass a new object:
$myObj->validate('myField', new \Validare\Rule(1, \Validare\Rule::EQUALS, 1));

// if you need to use a callback after validate, use a \Validate\Rule object:
$myObj->validate(
    'myField', 
    new \Validare\Rule(
        $value,
        \Validare\Rule::IS_STRING,
        null,
        null,
        function($success){
            if ($success) {
                // Do something!
            }
        }
    )
);
```
### The default Rules
_Validare_ comes with many default useful rules and more still will come. All them are valid as constants of the `\Validare\Rule` class:
- REQUIRED
- POSITIVE
- NEGATIVE
- LENGTH
- MIN_LENGTH
- MAX_LENGTH
- MORE
- LESS
- MORE_OR_EQUALS
- LESS_OR_EQUALS
- EQUALS
- NOT_EQUALS
- IS_ARRAY
- IS_STRING
- IS_NUMERIC
- IS_A
- COUNT_EQUALS
- COUNT_MORE
- COUNT_LESS
- COUNT_MORE_EQUALS
- COUNT_LESS_EQUALS
- HAS_ATTRIBUTE
- HAS_ATTRIBUTE_VALUE 

### The Assert class
If you need to validate a specific value, you may use the `\Validare\Assert` class. It has 2 basic methods, a magic method that calls the default rules dynamically and a `value()` method that validates something with the same syntax that the `Validare\Bind::validate()` method:
```php
    // Magic Calls for default rules:
    \Validare\Assert::required('my test value'); // Returns true
    \Validare\Assert::length('test', 5); // Returns false
    
    // Default assert (rules may be called as the same as \Validare\Bind::validate() method):
    \Validare\Assert::value('Valid', \Validare\Rule::REQUIRED); // Returns true
    \Validare\Assert::value(
        2,
        [\Validare\Rule::LESS_OR_EQUALS => 5],
        [\Validare\Rule::MORE => 3]
    ); // Returns false
    
    // If you need to check if an object has an attribute defined, use 'has_attribute' rule:
    \Validare\Assert::has_attribute($obj, 'test');
    
    // And if you need to check a value of an object, use 'has_attribute_value' rule, with an 
    // array on compareValue:
    \Validare\Assert::has_attribute_value($obj, ['test' => 'wololo']);
```

## Tests
Tests are under `test/` folder using PHPUnit.

## Contributing
Fell free to create pull requests with new rules, bug fixing and suggestions! <3
