<?php

namespace Persona;

class PersonaReflection
{
    /**
     * @param \ReflectionClass $reflection
     * @param $interfaceList
     * @return object
     */
    public function newInstance(\ReflectionClass $reflection, $interfaceList)
    {
        $args = [];
        if ($reflection->hasMethod('__construct')) {
            $parameters = $reflection->getConstructor()->getParameters();
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
     * @param $interface
     * @param array $interfaceList
     * @return \ReflectionClass
     */
    public function getReflectionClass($interface, array $interfaceList)
    {
        if (isset($interfaceList[$interface])) {
            return new \ReflectionClass($interfaceList[$interface]);
        }

        return new \ReflectionClass($interface);
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

    /**
     * @param \ReflectionClass $reflection
     * @param array $args
     * @return object
     */
    private function getArgumentObject(\ReflectionClass $reflection, array $args)
    {
        return $this->newInstance(
            $this->getReflectionClass($reflection->getName(), $args),
            $args
        );
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param array $args
     * @return mixed|null
     */
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
}