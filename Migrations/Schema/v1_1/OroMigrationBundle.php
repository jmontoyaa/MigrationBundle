<?php

namespace Oro\Bundle\MigrationBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroMigrationBundle implements Migration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('migrations_data');
        if (!$table->hasColumn('version')) {
            $table->addColumn('version', 'string', ['notnull' => false, 'length' => 255]);
        }
    }
}
