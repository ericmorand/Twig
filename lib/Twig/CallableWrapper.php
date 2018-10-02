<?php
/**
 * Created by PhpStorm.
 * User: ericmorand
 * Date: 02/10/18
 * Time: 01:56
 */


class Twig_CallableWrapper
{
    private $name;
    private $callable;

    /**
     * Creates a template function.
     *
     * @param string        $name     Name of this function
     * @param callable|null $callable A callable implementing the function. If null, you need to overwrite the "node_class" option to customize compilation.
     */
    public function __construct(string $name, $callable = null)
    {
        $this->name = $name;
        $this->callable = $callable;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the callable to execute for this function.
     *
     * @return callable|null
     */
    public function getCallable()
    {
        return $this->callable;
    }

    public function getLocationAwareCallable(int $lineno, Twig_Source $source) {
        return function($args) use ($lineno, $source) {
            try {
                return call_user_func_array($this->callable, $args);
            }
            catch (Twig_Error $e) {
                if ($e->getTemplateLine() === -1) {
                    $e->setTemplateLine($lineno);
                    $e->setSourceContext($source);
                }

                throw $e;
            }
            catch (Exception $e) {
                throw $e;
            }
        };
    }
}