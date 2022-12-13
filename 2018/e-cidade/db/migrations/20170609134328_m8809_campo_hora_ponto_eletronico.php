<?php

use Classes\PostgresMigration;

class M8809CampoHoraPontoEletronico extends PostgresMigration
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
    
    $this->addDicionarioDados();
    $this->criarTabelas();    
    $this->alterarFormulaPontoHora();
  }
  
  public function down() {
    
    $this->removerDicionarioDados();
    $this->droparDML();
    $this->retornarFormulaPontoHora();
  }
  
  public function addDicionarioDados()
  {
   

    /**
     * Cria campos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
      array(1009327 ,'h16_hora' ,'varchar(5)' ,'Horas' ,'' ,'Horas' ,5 ,'true' ,'true' ,'false' ,0 ,'text' ,'Horas'),     
           

    );
    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
      array( 528 ,1009327 ,16 ,0 ),      
     
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues); 
    $table->saveData();
    
  }
  
  public function criarTabelas()
  {
    $this->execute("ALTER TABLE recursoshumanos.assenta add COLUMN h16_hora varchar");   
  }

  /**
   * Remove dados do dicionario de dados
   */
  private function removerDicionarioDados()  
  {

    $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009327)');
    $this->execute('delete from configuracoes.db_syscampo where codcam in(1009327)');        
  }
  
  private function droparDML()
  {
    $this->execute('ALTER table recursoshumanos.assenta DROP COLUMN h16_hora');  
  }

  private function alterarFormulaPontoHora()
  {
    $this->execute("UPDATE db_formulas SET db148_formula = 'select fc_converte_hora_trabalho_hora_pagamento((select replace(h16_hora, '':'', ''.'') from assenta where h16_codigo = [CODIGO_ASSENTAMENTO])::numeric)' WHERE db148_nome = 'PONTO_HORA'");
  }

  public function retornarFormulaPontoHora()
  {
    $this->execute("UPDATE db_formulas SET db148_formula = 'select h16_perc from assenta where h16_codigo = [CODIGO_ASSENTAMENTO]' WHERE db148_nome = 'PONTO_HORA'");
  }

}
