<?php

use Classes\PostgresMigration;

class M8308ModelosReciboGrm extends PostgresMigration
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
      $this->execute("insert into caixa.cadmodcarne values (94, 'Carne - GRM', null, 0, 0, null, null);");
      $this->execute("insert into caixa.cadmodcarne values (95, 'Recibo Protoloco - GRM', null, 0, 0, null, null);");
      $this->execute("insert into caixa.cadmodcarne values (96, 'Guia Itbi - GRM', null, 0, 0, null, null);");
    }


  public function down()
  {
    $this->execute('delete from caixa.cadmodcarne where k47_sequencial in(94,95,96)');
  }
}
