<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

class ConstraintBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $domain = new \Fieg\CSP\Domain(array(1,2,3,4));

        $a = new \Fieg\CSP\Feature('A', $domain);
        $b = new \Fieg\CSP\Feature('B', $domain);
        $c = new \Fieg\CSP\Feature('C', $domain);

        $cb = new \Fieg\CSP\ConstraintBuilder();

        $expr = $cb
            ->lte($a, $b)
            ->lt($b, 3)
            ->lt($b, $c)
            ->notX(
                $cb->orX(
                    $cb->eq($a, 2),
                    $cb->eq($c, 3)
                )
            )
            ->getExpr()
        ;

        $this->assertEquals('(A <= B ^ B < 3 ^ B < C ^ ¬((A = 2 v C = 3)))', (string) $expr);
    }
}
