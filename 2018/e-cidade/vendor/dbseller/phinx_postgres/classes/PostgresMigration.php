<?php

namespace Classes;

use Phinx\Migration\AbstractMigration;

class PostgresMigration extends AbstractMigration {

    /**
     * {@inheritdoc}
     */
    public function table($tableName, $options = array())
    {
        return new \Classes\Table($tableName, $options, $this->getAdapter());
    }

    /**
     * Import a file to database
     * @param string path/to/file
     * @return integer OID
     */
    public function importLargeObject($path)
    {
        $pdo = $this->getAdapter()->getConnection();

        $oid = $pdo->pgsqlLOBCreate();
        $stream = $pdo->pgsqlLOBOpen($oid, 'w');
        $local = fopen($path, 'rb');

        stream_copy_to_stream($local, $stream);

        $local = null;
        $stream = null;

        return $oid;
    }
}
