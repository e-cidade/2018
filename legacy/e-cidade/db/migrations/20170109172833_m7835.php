<?php

use Classes\PostgresMigration;

class M7835 extends PostgresMigration
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
    	$this->execute('update conplano set c60_finali = c60_descr , c60_descr = substr(c60_descr, 1, 50) where LENGTH(c60_descr) > 50  and c60_anousu >= 2017');
    }

    public function down()
    {
    	$this->execute('update conplano set  c60_descr = c60_finali where LENGTH(c60_descr) > 50  and c60_anousu >= 2017');
    }
}
