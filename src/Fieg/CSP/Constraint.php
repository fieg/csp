<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP;

abstract class Constraint
{
    /**
     * Evaluates the constraint
     *
     * @return bool
     */
    abstract public function evaluate();

    /**
     * @param mixed $input
     * @return mixed
     */
    protected function resolve($input)
    {
        if ($input instanceof Feature) {
            return $input->getValue();
        }

        return $input;
    }
}
