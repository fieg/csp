<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP\Constraint;

use Fieg\CSP\CompoundConstraint;
use Fieg\CSP\Constraint;

class OrX extends CompoundConstraint
{
    /**
     * @inheritdoc
     */
    public function evaluate()
    {
        foreach($this->constraints as $constraint) {
            if (true === $constraint->evaluate()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $constraints = array_map('strval', $this->constraints);

        return '(' . implode(" v ", $constraints) . ')';
    }
}
