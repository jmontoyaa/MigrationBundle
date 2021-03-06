<?php

namespace Oro\Bundle\MigrationBundle\Migration\Schema;

use Oro\Bundle\MigrationBundle\Tools\DbIdentifierNameGenerator;

class TableWithNameGenerator extends Table
{
    /**
     * @var DbIdentifierNameGenerator
     */
    protected $nameGenerator;

    /**
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->nameGenerator = $args['nameGenerator'];

        parent::__construct($args);
    }

    /**
     * {@inheritdoc}
     */
    public function addIndex(array $columnNames, $indexName = null, array $flags = array(), array $options = array())
    {
        if (!$indexName) {
            $indexName = $this->nameGenerator->generateIndexName(
                $this->getName(),
                $columnNames
            );
        }

        return parent::addIndex($columnNames, $indexName, $flags, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function addUniqueIndex(array $columnNames, $indexName = null, array $options = array())
    {
        if (!$indexName) {
            $indexName = $this->nameGenerator->generateIndexName(
                $this->getName(),
                $columnNames,
                true
            );
        }

        return parent::addUniqueIndex($columnNames, $indexName, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function addForeignKeyConstraint(
        $foreignTable,
        array $localColumnNames,
        array $foreignColumnNames,
        array $options = array(),
        $constraintName = null
    ) {
        if (!$constraintName) {
            $constraintName = $this->nameGenerator->generateForeignKeyConstraintName(
                $this->getName(),
                $localColumnNames
            );
        }

        return parent::addForeignKeyConstraint(
            $foreignTable,
            $localColumnNames,
            $foreignColumnNames,
            $options,
            $constraintName
        );
    }
}
