<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP\Constraint;

use Fieg\CSP\CompoundConstraint;
use Fieg\CSP\Constraint;

class NotX extends CompoundConstraint
{
    /**
     * @inheritdoc
     */
    public function evaluate()
    {
        foreach($this->constraints as $constraint) {
            if (false !== $constraint->evaluate()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Constraint $constraint
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function addConstraint(Constraint $constraint)
    {
        if (0 !== count($this->constraints)) {
            throw new \LogicException('Can only add a single child of type AndX or OrX');
        }

        if (!$constraint instanceof AndX && !$constraint instanceof OrX) {
            throw new \InvalidArgumentException(sprintf('Only constraints of type AndX or OrX allowed, %s given', get_class($constraint)));
        }

        parent::addConstraint($constraint);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $constraints = array_map('strval', $this->constraints);

        $retval = implode('', $constraints);

        return '¬(' . $retval . ')';
    }
}
