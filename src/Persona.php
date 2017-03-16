<?php

namespace Persona;

class Persona
{
    /**
     * @var array
     */
    private static $bindList = [];

    /**
     * @var array
     */
    private static $singletonList = [];

    /**
     * @var array
     */
    private static $singletonObject = [];

    /**
     * @param $interface
     * @param $instance
     */
    public static function bind($interface, $instance)
    {
        self::$bindList[$interface] = $instance;
    }

    /**
     * @param $interface
     * @param $instance
     */
    public static function singleton($interface, $instance)
    {
        self::$singletonList[$interface] = $instance;
    }

    /**
     * @return array
     */
    public static function getBindList()
    {
        return self::$bindList;
    }

    /**
     * @return array
     */
    public static function getSingletonList()
    {
        return self::$singletonList;
    }

    /**
     * @param $instance
     * @return object
     */
    public static function make($instance)
    {
        $persona = new self;
        $reflection = $persona->getReflectionClass($instance, array_merge(self::$bindList, self::$singletonList));

        return $persona->newInstance($reflection, array_merge(self::$bindList, self::$singletonList));
    }

    public static function call($method, $instance, $option)
    {
        $obj = self::make($instance);
        $persona = new self;
        return $persona->invoke($method, $obj, array_merge(self::$bindList, self::$singletonList, $option));
    }


    /**
     * @return object
     */
    public function newInstance(\ReflectionClass $reflection, $interfaceList)
    {
        $args = [];
        if ($reflection->hasMethod('__construct')) {
            $parameters = $reflection->getMethod('__construct')->getParameters();
            $args = $this->getArgument($parameters, $interfaceList);
        }

        return $reflection->newInstanceArgs($args);
    }

    /**
     * @param $method
     * @param object $interface
     * @param array $options
     * @return mixed
     */
    public function invoke($method, $interface, array $options)
    {
        $reflection = new \ReflectionClass(get_class($interface));

        if ($reflection->hasMethod($method)) {
            $parameters = $reflection->getMethod($method)->getParameters();
            $args = $this->getArgument($parameters, $options);
            return $reflection->getMethod($method)->invokeArgs($interface, $args);
        }

        return null;
    }


    /**
     * @param \ReflectionParameter[] $parameters
     * @param array $args
     * @return array
     */
    private function getArgument(array $parameters, array $args): array
    {
        return array_reduce($parameters, function ($carry, $parameter) use ($args) {
            /**
             * @var \ReflectionParameter $parameter
             */

            /** @var \ReflectionClass $parameterClass */
            $parameterClass = $parameter->getClass();

            $carry[$parameter->getName()] = ($parameterClass) ? $this->getArgumentObject($parameterClass, $args) : $this->getArgumentValue($parameter, $args);

            return $carry;
        }, []);
    }

    private function getArgumentObject(\ReflectionClass $reflection, array $args)
    {
        return $this->newInstance(
            $this->getReflectionClass($reflection->getName(), $args),
            $args
        );
    }

    private function getArgumentValue(\ReflectionParameter $parameter, array $args)
    {
        $value = null;
        if (isset($args[$parameter->getName()])) {
            $value = $args[$parameter->getName()];
        } else if ($parameter->isDefaultValueAvailable()) {
            $value = $parameter->getDefaultValue();
        }

        return $value;
    }

    /**
     * @param $interface
     * @param array $interfaceList
     * @return \ReflectionClass
     */
    private function getReflectionClass($interface, array $interfaceList)
    {
        if (isset($interfaceList[$interface])) {
            return new \ReflectionClass($interfaceList[$interface]);
        }

        return new \ReflectionClass($interface);
    }
}