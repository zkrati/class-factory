<?php
namespace Zkrati\ClassManagement;
/**
 * Class ClassFactory
 * @package Zkrati\ClassManagement
 */
class ClassFactory
{
    /** @var array */
    private $instances = [];

    /** @var bool */
    private $lazyMode = true;

    /**
     * Instantiator constructor.
     *
     * @param bool $lazy
     */
    public function __construct($lazy = true)
    {
        $this->lazyMode = $lazy;
    }

    /**
     * Add array of classes to instantiate
     *
     * @param array $classes
     */
    public function addMultiple(array $classes)
    {
        foreach($classes as $class => $dependencies){
            if(!is_array($dependencies)){
                $dependencies = array($dependencies);
            }

            $this->addClass($class, $dependencies);
        }
    }

    /**
     * Add new class to instantiate
     *
     * @param $classname
     * @param array $dependencies
     */
    public function addClass($classname, array $dependencies = array())
    {
        if(isset($this->instances[$classname]) AND !is_array($this->instances[$classname])) {
            return;
        }

        if($this->lazyMode){
            $this->instances[$classname] = array($classname, $dependencies);
        } else {
            $this->createInstance($classname, $dependencies);
        }
    }

    /**
     * Get instance of classname
     *
     * @param $classname
     * @return mixed
     * @throws UnknownClassnameException
     */
    public function getInstance($classname)
    {
        if(!isset($this->instances[$classname])){
            throw new UnknownClassnameException("No instance available for class " . $classname);
        }

        if($this->lazyMode and is_array($this->instances[$classname])){
            call_user_func_array(array($this, "createInstance"), $this->instances[$classname]);
        }

        return $this->instances[$classname];
    }

    /**
     * Add already created instance
     *
     * @param object $instance
     */
    public function addInstance($instance)
    {
        $this->instances[get_class($instance)] = $instance;
    }

    /**
     * @param $classname
     * @param array $dependencies
     */
    private function createInstance($classname, array $dependencies = array())
    {
        $instantiatedDependencies = [];
        foreach($dependencies as $dependency){
            if($dependency == null){
                break;
            }
            $instantiatedDependencies[] = $this->getInstance($dependency);
        }

        $class = new \ReflectionClass($classname);
        $this->instances[$classname] = $class->newInstanceArgs($instantiatedDependencies);
    }
}
