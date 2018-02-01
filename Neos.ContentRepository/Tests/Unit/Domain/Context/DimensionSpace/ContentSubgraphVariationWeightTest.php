<?php

namespace Neos\ContentRepository\Tests\Unit\Domain\Context\DimensionSpace;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */
use Neos\ContentRepository\Domain\Context\Dimension;
use Neos\ContentRepository\Domain\Context\DimensionSpace;
use Neos\Flow\Tests\UnitTestCase;

/**
 * Unit test cases for content subgraph variation weights
 */
class ContentSubgraphVariationWeightTest extends UnitTestCase
{
    /**
     * @test
     */
    public function canBeComparedToReturnsTrueForEmptyComponents()
    {
        $weight = new DimensionSpace\ContentSubgraphVariationWeight([]);
        $weightToBeCompared = new DimensionSpace\ContentSubgraphVariationWeight([]);

        $this->assertTrue($weight->canBeComparedTo($weightToBeCompared));
    }

    /**
     * @test
     */
    public function canBeComparedToReturnsTrueForSameComponents()
    {
        $weight = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(0),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(1)
        ]);
        $weightToBeCompared = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(1),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(2)
        ]);

        $this->assertTrue($weight->canBeComparedTo($weightToBeCompared));
    }

    /**
     * @test
     */
    public function canBeComparedToReturnsFalseForDifferentComponents()
    {
        $weight = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(0),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(1)
        ]);
        $weightToBeCompared = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(1),
            'dimensionC' => new Dimension\ContentDimensionValueSpecializationDepth(3)
        ]);

        $this->assertFalse($weight->canBeComparedTo($weightToBeCompared));
    }

    /**
     * @test
     * @expectedException \Neos\ContentRepository\Domain\Context\DimensionSpace\Exception\IncomparableContentSubgraphVariationWeightsException
     * @throws DimensionSpace\Exception\IncomparableContentSubgraphVariationWeightsException
     */
    public function decreaseByThrowsExceptionForIncomparableWeights()
    {
        $weight = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(0),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(1)
        ]);
        $weightToDecreaseBy = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(1),
            'dimensionC' => new Dimension\ContentDimensionValueSpecializationDepth(1)
        ]);

        $weight->decreaseBy($weightToDecreaseBy);
    }

    /**
     * @test
     * @expectedException \Neos\ContentRepository\Domain\Context\Dimension\Exception\InvalidContentDimensionValueSpecializationDepthException
     * @throws DimensionSpace\Exception\IncomparableContentSubgraphVariationWeightsException
     */
    public function decreaseByThrowsExceptionForComponentsGreaterThanTheOriginal()
    {
        $weight = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(0),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(1)
        ]);
        $weightToDecreaseBy = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(1),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(2)
        ]);

        $weight->decreaseBy($weightToDecreaseBy);
    }

    /**
     * @test
     * @throws DimensionSpace\Exception\IncomparableContentSubgraphVariationWeightsException
     */
    public function decreaseByCorrectlyDecreasesEachComponent()
    {
        $weight = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(3),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(2),
            'dimensionC' => new Dimension\ContentDimensionValueSpecializationDepth(3),
        ]);
        $weightToDecreaseBy = new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(0),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(1),
            'dimensionC' => new Dimension\ContentDimensionValueSpecializationDepth(3)
        ]);

        $this->assertEquals(new DimensionSpace\ContentSubgraphVariationWeight([
            'dimensionA' => new Dimension\ContentDimensionValueSpecializationDepth(3),
            'dimensionB' => new Dimension\ContentDimensionValueSpecializationDepth(1),
            'dimensionC' => new Dimension\ContentDimensionValueSpecializationDepth(0)
        ]), $weight->decreaseBy($weightToDecreaseBy));
    }
}