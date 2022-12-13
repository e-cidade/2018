<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


class tableDataManager { 

  private $iPkLastReg       = 0;
  private $iTamanhoBloco    = 1000;
  private $sTableName       = "";
  private $sCampoPk         = "";
  private $aData            = array();
  private $aCopyRows        = array();
  private $aTableProperties = array();
  private $aLinhaAtual      = array();
  private $pConexao         = null;
  private $lAutoStore       = true;
  private $aCampos          = array();
  private $lUseSequence     = false;
  private $sSequenceName    = '';
  
  /**
   * Adicionado neste array os tipos de dados que não podem entrar string quando os campos destes tipo 
   * possuem um retorno vazio
   * @var array
   */
  private $aTiposQueNaoPodeEntrarString = array('numeric', 'int4', 'date', 'timestamp');
  
  /**
   * Construtor da Classe
   * @param  resource  $pConexao
   * @param  string    $sTableName
   * @param  string    $sCampoPk
   * @param  boolean   $lAutoStore
   * @param  number    $iTamanhoBloco
   * @throws Exception
   */
  function __construct($pConexao, $sTableName, $sCampoPk='', $lAutoStore=true, $iTamanhoBloco=1000, $sSequenceName = null) {

    if(!is_resource($pConexao) || empty($sTableName)) {
      throw new Exception("Conexao invalida [tableDataManager]");
    }

    if (empty($sTableName)) {
      throw new Exception("Nome da tabela nao informado [tableDataManager]");      
    }
    
    $this->sTableName    = $sTableName;
    $this->pConexao      = $pConexao;
    $this->iTamanhoBloco = $iTamanhoBloco;
    $this->sCampoPk      = $sCampoPk;
    $this->lAutoStore    = $lAutoStore;
    
    $this->getTableAtt();

    if ( !empty($sCampoPk) ) {

      $this->setSequence( $sSequenceName );
      // metodo para buscar o ultimo valor do campo PK para setar o atributo $this->iPkLastReg
      $this->iPkLastReg = $this->getMaxValueField($sCampoPk);
    }
  }

  function getMaxValueField($sFieldName){

    $iMaxValue    = 0;
    $sSqlMaxValue = "select max({$sFieldName}) as maior_codigo from {$this->sTableName}";
    if ( $this->lUseSequence ) {
      $sSqlMaxValue = "select last_value as maior_codigo from {$this->sSequenceName};";
    }
    $rsMaxValue   = db_query($this->pConexao,$sSqlMaxValue);
    $oMaxValue    = db_utils::fieldsMemory($rsMaxValue,0);
    if ( ! empty($oMaxValue->maior_codigo) ) {
      $iMaxValue = $oMaxValue->maior_codigo;
    }
    return $iMaxValue;

  }

  function __set($sAttName, $sValor) {
    
    $this->aLinhaAtual[$sAttName] = $sValor;
  }

  /**
   * Define os Valores a partir de um objeto
   * @param  stdClass $oDBUtils
   * @param  bool $lInsertValue
   * @throws Exception
   * @return Ambigous <boolean, number>|boolean
   */
  public  function setByLineOfDBUtils($oDBUtils, $lInsertValue = false){

    foreach ( $this->aTableProperties as $aCampo ) {
      
      if (!isset($oDBUtils->$aCampo[0]) && $aCampo[0] != $this->sCampoPk) {
        throw new Exception("[tableDataManager] Campo ".$aCampo[0].":".$aCampo[1]." não informado!");
      }
      
      if (!isset($oDBUtils->$aCampo[0]) && $aCampo[0] == $this->sCampoPk ) {
        $this->$aCampo[0] = '';
      } else {
        $this->$aCampo[0] = $oDBUtils->$aCampo[0];
      }
    }
    
    if ( $lInsertValue ) {
      return $this->insertValue();
    }    
    return true;
  }

  private function getTableAtt(){

    //
    // Montar um array ordenado pelos campos da tabela 
    //   e guardar o nome do campo e o tipo de dado

    $sSqlTable         = "select * from {$this->sTableName} limit 0";
    $rsTableProperties = db_query($this->pConexao,$sSqlTable) or die($sSqlTable);
    $iContaCampos      = pg_num_fields($rsTableProperties);

    for($iCont=0; $iCont<$iContaCampos; $iCont++) {

      $sNomeCampo  = pg_field_name($rsTableProperties, $iCont);
      $sTipoCampo  = pg_field_type($rsTableProperties, $iCont);

      $this->aTableProperties[$iCont] = array($sNomeCampo,$sTipoCampo);

    }

  }

  function setTableName($sTableName){
    $this->sTableName = $sTableName;
  }
  
  function getTableName(){
    return $this->sTableName;
  }  

  function setTamanhoBloco($iTamanhoBloco){
    $this->sTableName = $iTamanhoBloco;
  }

  function getNextSequence(){
    return ++$this->iPkLastReg;
  }

  function getLastPk(){
    return $this->iPkLastReg;
  }

  function insertValue() { 

    if (!count($this->aLinhaAtual) > 0) {
      return false;
    }

    foreach ($this->aTableProperties as $aTabela) {

      $sTipo = $aTabela[1];

      if ( ! array_key_exists($aTabela[0],$this->aLinhaAtual) ) {
        throw new Exception("[tableDataManager] Erro : Nao definido campo {$this->sTableName}.".$aTabela[0]);
      }

      if (trim($aTabela[0]) == trim($this->sCampoPk)) {
        $sValor      = $this->getNextSequence();
      }else{
        
        if ($this->aLinhaAtual[$aTabela[0]] == '' && in_array($sTipo, $this->aTiposQueNaoPodeEntrarString)) {
          $sValor = '\N';
        } else {
          $sValor = $this->aLinhaAtual[$aTabela[0]];
        }
      }
      
      $aLinha[$aTabela[0]] = $this->formatValue($sValor,$sTipo);
    }

    $this->aData[] = $aLinha;

    if ($this->lAutoStore) {
      
      if (count($this->aData) == $this->iTamanhoBloco) {
        
        try {
          $this->persist();
        } catch (Exception $e){
          throw new Exception($e->getMessage());
        }
      }
    }

    return $this->getLastPk();
  }

  function persist() {      

    $iCont = 0;
    db_query($this->pConexao, "copy {$this->sTableName} from stdin") ;    
    
    foreach ($this->aData as $aLinha) {

      $sLinha  = implode("\t", $aLinha);      
      $sLinha .= "\n";
      
      if(!pg_put_line($this->pConexao, $sLinha)) {
        throw new Exception("[tableDataManager] Erro linha numero : {$iCont} String : {$sLinha}");
      }
      $iCont++;
    }

    pg_put_line($this->pConexao, "\\.\n"); // Finaliza o Copy
    
    if ( !pg_end_copy($this->pConexao)) {
      throw new Exception("[tableDataManager] :".pg_last_error($this->pConexao));
    }

    $this->aData       = array();
    $this->aLinha      = array();
    $this->aLinhaAtual = array();
 
    if ( $this->lUseSequence ) {
      $this->persistSequenceValue();//$rsSetVal   = pg_query("select setval('{$this->sSequenceName}', {$this->getLastPk()});");
    }

    return true;

  }  

  function formatValue($valor, $tipo) {
    
    $sValorRetorno = "";
        
    if (!is_null($valor)) {
      
      if ($tipo == 'int' || $tipo == 'numeric' || $tipo == 'int8' || $tipo == 'int4' || $tipo == 'float8') {
        
        $sValorRetorno = $valor;
      } else if ( $tipo == 'bpchar' || $tipo == 'string' || $tipo == 'varchar' || $tipo == 'text' ) {
        
        $valor = str_replace("\r",'\r',$valor);
        $valor = str_replace("\n",'\n',$valor);
        $valor = str_replace("\t",'\t',$valor);        
        $sValorRetorno  = pg_escape_string($valor);
        $sValorRetorno = str_replace("''","\'",$sValorRetorno);
        
      } else {
        
        $valor = $valor;
        $sValorRetorno = $valor;
      }
      
    } else {
      $sValorRetorno = "\N";
    }

    return $sValorRetorno;
  }
  
  /**
   * Define uma sequence para ser utilizada no getMaxValueField 
   * 
   * @param mixed $sNomeSequence 
   * @access public
   * @return void
   */
  public function setSequence( $sNomeSequence = null ) {
    
    if ( empty($sNomeSequence) ) {
      $this->lUseSequence = false;
      return true;
    }

    $this->lUseSequence  = true;
    $this->sSequenceName = $sNomeSequence;
  }
 
   public function __destruct() {
  
    if ( $this->lUseSequence ) {
      $this->persistSequenceValue();
    }
  }

  public function persistSequenceValue() {
   $rsSetVal   = db_query("select setval('{$this->sSequenceName}', {$this->getLastPk()});");
  }
}

?>