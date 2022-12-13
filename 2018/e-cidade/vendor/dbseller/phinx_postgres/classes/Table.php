<?php

namespace Classes;

use Phinx\Db\Adapter\AdapterInterface;

class Table extends \Phinx\Db\Table
{

    /**
     * @var string
     */
    private $tableSchema;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $options = array(), AdapterInterface $adapter = null)
    {
        $schema = 'public';

        if(isset($options['schema'])) {
            $schema = $options['schema'];
            unset($options['schema']);
        }

        $this->setTableSchema($schema);
        $this->setTableSchemaAdapter($adapter);

        parent::__construct($name, $options, $adapter);
    }

    /**
     * Sets the schema of table.
     *
     * @param string $schema Schema of Table
     * @return Table
     */
    public function setTableSchema($schema = 'public')
    {
        $this->tableSchema = $schema;
        return $this;
    }

    /**
     * Gets the schema of table.
     *
     * @return string
     */
    public function getTableSchema()
    {
        return $this->tableSchema;
    }

    /**
     * Sets the schema of table on adapter to persist on database.
     *
     * @param AdapterInterface $adapter
     * @return Table
     */
    public function setTableSchemaAdapter(AdapterInterface $adapter)
    {
        $adapterOptions           = $adapter->getOptions();
        $adapterOptions['schema'] = $this->getTableSchema();

        $adapter->setOptions($adapterOptions);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->setTableSchemaAdapter($this->getAdapter());
        parent::save();
    }
}
