<?php

use Classes\PostgresMigration;

class M7951AjusteDirf extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
  public function up() 
  {
    $this->execute("delete from db_layoutcampos where db52_codigo = 15485;");
    $this->execute("update db_layoutcampos set db52_tamanho=0 where db52_codigo in(15464, 15442);");
    $this->execute("update db_layoutcampos set db52_layoutformat=15 where db52_codigo in(15526);");
  }
  public function down() {
    
  }
}
