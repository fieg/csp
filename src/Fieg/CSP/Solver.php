<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP;

use Fieg\GraphSearch\Graph;
use Fieg\GraphSearch\Node;
use Fieg\GraphSearch\Strategy\DepthFirstStrategy;

class Solver
{
    /**
     * @var Feature[]
     */
    protected $features = array();

    /**
     * @var Constraint
     */
    protected $constraint;

    /**
     * @param Feature $feature
     * @return $this
     */
    public function addFeature(Feature $feature)
    {
        $this->features[] = $feature;

        return $this;
    }

    /**
     * @param Constraint $constraint
     */
    public function setConstraint(Constraint $constraint)
    {
        $this->constraint = $constraint;
    }

    /**
     * @param Node $parentNode
     * @param Feature[] $features
     * @return Node
     */
    protected function createNodes(Node $parentNode, array $features)
    {
        /** @var Feature $feature */
        $feature = array_shift($features);
        if ($feature) {
            $domain = $feature->getDomain();
            $values = $domain->getValues();

            foreach($values as $value) {
                $node = new Node(array($feature, $value));

                $node = $this->createNodes($node, $features);

                $parentNode->addNode($node);
            }
        }

        return $parentNode;
    }

    /**
     * @return mixed
     */
    public function solve()
    {
        $features = $this->features;

        $rootNode = $this->createNodes(new Node('root'), $features);

        $graph = new Graph(new DepthFirstStrategy());
        $graph->setRoot($rootNode);

        $constraint = $this->constraint;

        $goal = function (Node $n) use ($constraint) {
            $values = array();

            $path = $n->getPath();
            array_shift($path); // shift the root

            foreach($path as $node) {
                $values[] = $node->getValue();
            }

            foreach($values as $v) {
                list($feature, $value) = $v;

                $feature->setValue($value);
            }

            if ($values) {
                $result = $constraint->evaluate();

                if ($result) {
                    return true;
                }
            }

            return false;
        };

        $solutions = $graph->searchAll($goal);

        return $solutions;
    }
}
