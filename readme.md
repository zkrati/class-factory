# zkrati/class-factory

Simple library to manage class instances. It handles dependencies and supports lazy instantiating.

  - create instances of given classes for you
  - ensures that there is only single instance of one class
  - handles dependencies of the class

### Version
0.1


### Basic usage

```php
// create ClassFactory
$factory = new Zkrati\ClassManagement\ClassFactory();

// add class to ClassFactory, where first argument is a class name and second argument is an array of dependencies
$factory->addClass("Example\\ClassName", array("First\\Dependency", "Second\\One");

// get instance of the class
$factory->getInstance("Example\\ClassName");
```
simple right? You don´t need to bother with instantiating dependencies what so ever. ClassFactory handles that for you. Even dependencies can have it´s own dependencies. No problem for ClassFactory!
 
If you make a complex project it would be uncomfortable adding classes one by one.
```php
// create array like this one. Manualy in the code or parse from config file.
array(4) {
  'Example\ClassName' =>
      array(1) {
        [0] => string(27) "First\Dependency"
        [1] => string(27) "Second\One"
      }
  'Example\ClassNameSecond' =>
      NULL
  'Example\ClassNameThird' =>
      array(1) {
        [0] => string(20) "AnotherDependecy"
      }
  'First\Dependency' =>
      array(1) {
        [0] => string(20) "Namespace\AnotherDependecy"
      }

// give this group of classes to ClassFactory
$factory->addMultiple(array_of_classes);
```

If you need to create your own instance of some class for some reason, you can pass the created instance
```php
$myClass = new Example\ClassName();

// add created instance to ClassFactory
$factory->addInstance($myClass);

// and than you cen get it
$factory->getInstance("Example\\ClassName");
```

### Exceptions

If you want ClassFactory to give you an instance of a a class you didn´t tell ClassFactory about, it will throw UnknownClassnameException. So it is a good idea to catch it.
```php
try{
    // get instance of unknown class
    $factory->getInstance("Unknown\\ClassName");
} catch(UnknownClassnameException $e) {
    $e->getMessage();
}
```

License
----
MIT
