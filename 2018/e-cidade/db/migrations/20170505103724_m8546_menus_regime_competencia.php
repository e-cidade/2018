<?php

use Classes\PostgresMigration;

class M8546MenusRegimeCompetencia extends PostgresMigration
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

      $table    = $this->table('db_itensmenu', array('schema' => 'configuracoes'));
      $aColumns = array('id_item', 'descricao' ,'help' ,'funcao' ,'itemativo' ,'manutencao' ,'desctec' ,'libcliente' );
      $aValues  = array(
        array(10417 ,'Regime de Compet�ncia' ,'Rotinas para o regime de compet�ncia' ,'' ,'1' ,'1' ,'Rotinas para o regime de compet�ncia' ,'true' ),
        array(10418 ,'Implanta��o de Contratos em Execu��o' ,'Implanta��o de Contratos em Execu��o' ,'con4_implantacaoregimecompetencia.php' ,'1' ,'1' ,'Implanta��o de Contratos em Execu��o' ,'true')
      );
      
      $table->insert($aColumns, $aValues);
      $table->saveData();

      $table    = $this->table('db_menu', array('schema' => 'configuracoes'));
      $aColumns = array('id_item', 'id_item_filho', 'menusequencia', 'modulo');
      $aValues  = array(
        array(32    ,10417 ,375  ,8251),
        array(10417 ,8580 , 1  ,8251), 
        array(10417 ,10418 ,2 ,8251),
       
      );
      $table->insert($aColumns, $aValues);
      $table->saveData();
      
      $this->execute("update db_itensmenu set descricao = 'Programa��o' , help = 'Programa��o', libcliente = TRUE where id_item = 8580;");
      $this->execute("delete from db_menu where id_item_filho = 8580 and id_item = 32 AND modulo = 8251");
    }
    
    public function down() {
      $this->execute("update db_itensmenu set descricao = 'Programa��o do Regime de Compet�ncia' , help = 'Programa��o do Regime de Competencia' where id_item = 8580;");
      $this->execute("delete from db_menu where id_item_filho = 8580 AND modulo = 8251");
      $this->execute("delete from db_menu where id_item_filho = 10417 AND modulo = 8251");
      $this->execute("delete from db_menu where id_item_filho = 10418 AND modulo = 8251");
      $this->execute("delete from db_itensmenu where id_item in(10417, 10418)");
      
      $table    = $this->table('db_menu', array('schema' => 'configuracoes'));
      $aColumns = array('id_item', 'id_item_filho', 'menusequencia', 'modulo');
      $aValues  = array(                
        array(32 ,8580 , 375 ,8251),     
      );
      $table->insert($aColumns, $aValues);
      $table->saveData();
      
    }
}
