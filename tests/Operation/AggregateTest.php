<?php

namespace MongoDB\Tests\Operation;

use MongoDB\Operation\Aggregate;

class AggregateTest extends TestCase
{
    /**
     * @expectedException MongoDB\Exception\InvalidArgumentException
     * @expectedExceptionMessage $pipeline is empty
     */
    public function testConstructorPipelineArgumentMustNotBeEmpty()
    {
        new Aggregate($this->getDatabaseName(), $this->getCollectionName(), []);
    }

    /**
     * @expectedException MongoDB\Exception\InvalidArgumentException
     * @expectedExceptionMessage $pipeline is not a list (unexpected index: "1")
     */
    public function testConstructorPipelineArgumentMustBeAList()
    {
        new Aggregate($this->getDatabaseName(), $this->getCollectionName(), [1 => ['$match' => ['x' => 1]]]);
    }

    /**
     * @expectedException MongoDB\Exception\InvalidArgumentTypeException
     * @dataProvider provideInvalidConstructorOptions
     */
    public function testConstructorOptionTypeChecks(array $options)
    {
        new Aggregate($this->getDatabaseName(), $this->getCollectionName(), [['$match' => ['x' => 1]]], $options);
    }

    public function provideInvalidConstructorOptions()
    {
        $options = [];

        foreach ($this->getInvalidBooleanValues() as $value) {
            $options[][] = ['allowDiskUse' => $value];
        }

        foreach ($this->getInvalidIntegerValues() as $value) {
            $options[][] = ['batchSize' => $value];
        }

        foreach ($this->getInvalidBooleanValues() as $value) {
            $options[][] = ['bypassDocumentValidation' => $value];
        }

        foreach ($this->getInvalidIntegerValues() as $value) {
            $options[][] = ['maxTimeMS' => $value];
        }

        foreach ($this->getInvalidReadPreferenceValues() as $value) {
            $options[][] = ['readPreference' => $value];
        }

        foreach ($this->getInvalidBooleanValues() as $value) {
            $options[][] = ['useCursor' => $value];
        }

        return $options;
    }

    /**
     * @expectedException MongoDB\Exception\InvalidArgumentException
     * @expectedExceptionMessage "batchSize" option should not be used if "useCursor" is false
     */
    public function testConstructorBatchSizeOptionRequiresUseCursor()
    {
        new Aggregate(
            $this->getDatabaseName(),
            $this->getCollectionName(),
            [['$match' => ['x' => 1]]],
            ['batchSize' => 100, 'useCursor' => false]
        );
    }
}
