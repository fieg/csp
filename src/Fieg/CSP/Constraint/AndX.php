<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP\Constraint;

use Fieg\CSP\CompoundConstraint;
use Fieg\CSP\Constraint;

class AndX extends CompoundConstraint
{
    /**
     * @inheritdoc
     */
    public function evaluate()
    {
        foreach($this->constraints as $constraint) {
            if (false === $constraint->evaluate()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $constraints = array_map('strval', $this->constraints);

        return '(' . implode(" ^ ", $constraints) . ')';
    }
}
