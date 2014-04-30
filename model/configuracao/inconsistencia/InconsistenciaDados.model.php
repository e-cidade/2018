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
 
/**
 * Classe para guardar dados inconsistentes para depois corrigir
 *
 * @require db_utils
 *
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @version $Revision: 1.6 $
 */
class InconsistenciaDados {

  /**
   * Codigo da inconsistencia gerada
   * sequencial da tabela db_registrosinconsistentes
   * 
   * @var int
   * @access private
   */
  private $iCodigo;
 
  /**
   * Tabelas permitidas para manutencao de registros duplos.
   * @var array
   */
  static private $aTabelasPermitidas = array();
  /**
   * Data de cadastro da inconsistencia 
   * 
   * @var date
   * @access private
   */
  private $dData;

  /**
   * Usuario que cadastrou inconsitencia 
   * 
   * @var int
   * @access private
   */
  private $iUsuario;

  /**
   * Tabela com inconsistencia 
   * 
   * @var int
   * @access private
   */
  private $iTabela;

  /**
   * Boolean para saber se inconsistencia ja foi corrigido 
   * 
   * @var Bool
   * @access private
   */
  private $lProcessado;

  /**
   * Codigo do registro correto 
   * 
   * @var int
   * @access private
   */
  private $iRegistroCorreto;

  /**
   * Lista dos registros inconsistentes 
   * 
   * @var Array
   * @access private
   */
  private $aRegistrosInconsistentes;

  /**
   * Construtor da classe 
   * 
   * @param int $iCodigo codigo da inconsistencia 
   * @access public
   * @return void
   */
  public function __construct($iCodigo = 0) {
  
    if ( !empty($iCodigo) ) {

      $oDaoDb_registrosinconsistentesdados = db_utils::getDao('db_registrosinconsistentesdados');
      $sWhereRegistrosInconsistentes       = "db136_sequencial = {$iCodigo}";
      $sSqlRegistrosInconsistentes         = $oDaoDb_registrosinconsistentesdados->sql_query(null, '*', null, $sWhereRegistrosInconsistentes);
      $rsRegistrosInconsistentes           = $oDaoDb_registrosinconsistentesdados->sql_record($sSqlRegistrosInconsistentes);

      if ( $oDaoDb_registrosinconsistentesdados->erro_status == "0" ) {
        throw new Exception($oDaoDb_registrosinconsistentesdados->erro_msg);
      }

      $iRegistrosInconsistentes = $oDaoDb_registrosinconsistentesdados->numrows;

      for ( $iIndice = 0; $iIndice < $iRegistrosInconsistentes; $iIndice++ ) {

        $oRegistroInconsistente = db_utils::fieldsMemory($rsRegistrosInconsistentes, $iIndice);

        if ( empty($this->iCodigo) ) {

          $this->setCodigo($iCodigo); 
          $this->setData($oRegistroInconsistente->db136_data); 
          $this->setUsuario($oRegistroInconsistente->db136_usuario); 
          $this->setTabela($oRegistroInconsistente->db136_tabela);
          $this->setProcessado($oRegistroInconsistente->db136_processado); 
        }

        if ( $oRegistroInconsistente->db137_correto == 't' || $oRegistroInconsistente->db137_correto == 'true' ) {

          $this->setRegistroCorreto($oRegistroInconsistente->db137_chave);
          continue;
        }

        $this->adicionarRegistroInconsistente($oRegistroInconsistente->db137_chave, $oRegistroInconsistente->db137_excluir); 
      }

      return;
    }

    $this->setUsuario(db_getsession('DB_id_usuario'));
    $this->setData(date('Y-m-d', db_getsession('DB_datausu'))); 
  }

  /**
   * Define codigo da inconsistencia 
   * 
   * @param int $iCodigo 
   * @access public
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo; 
  }

  /**
   * Retorna codigo da inconsistencia 
   * 
   * @access public
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo; 
  }

  /**
   * Define a data da geracao das inconsistencias 
   * 
   * @param date $dData 
   * @access public
   */
  public function setData($dData) {
    $this->dData = $dData;
  }
  
  /**
   * Retorna da data de geracao das inconsistencias
   * 
   * @access public
   * @return date
   */
  public function getData() {
    return $this->dData; 
  }

  /**
   * Define o codigo do usuario 
   * 
   * @param int $iUsuario 
   * @access public
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario; 
  }

  /**
   * Retorna o codigo do usuario 
   * 
   * @access public
   * @return int
   */
  public function getUsuario() {
    return $this->iUsuario; 
  }

  /**
   * Define o codigo da tabela 
   * 
   * @param int $iTabela 
   * @access public
   */
  public function setTabela($iTabela) {
    $this->iTabela = $iTabela; 
  }

  /**
   * Retorna a tabela 
   * 
   * @access public
   * @return int
   */
  public function getTabela() {
    return $this->iTabela; 
  }
  
  /**
   * Define se registros ja foram corrigidos 
   * 
   * @param bool $lProcessado 
   * @access public
   */
  public function setProcessado($lProcessado) {
    $this->lProcessado = $lProcessado; 
  }

  /**
   * Retorna se registros ja foram corrigidos 
   * 
   * @access public
   * @return bool
   */
  public function processado() {
    return $this->lProcessado; 
  }
  
  /**
   * Define registro como correto 
   * 
   * @param int $iRegistroCorreto 
   * @access public
   */
  public function setRegistroCorreto($iRegistroCorreto) {
    $this->iRegistroCorreto = $iRegistroCorreto; 
  }

  /**
   * Retorna o registro correto 
   * 
   * @access public
   * @return int
   */
  public function getRegistroCorreto() {
    return $this->iRegistroCorreto; 
  }
  
  /**
   * Adiciona registro inconsistente 
   * @param int $iRegistroInconsitente Codigo da primary key do registro
   * @param boolean $lExcluir se deve ser excluido o registro após o processamento.  
   * @access public
   */
  public function adicionarRegistroInconsistente($iRegistroInconsitente, $lExcluir = true) {
    
    $oRegistroInconsistente                  = new stdClass();
    $oRegistroInconsistente->iCodigoRegistro = $iRegistroInconsitente;
    $oRegistroInconsistente->lExcluir        = $lExcluir;
    $this->aRegistrosInconsistentes[]        = $oRegistroInconsistente; 
  }

  /**
   * Retorna lista de inconsistencia 
   * 
   * @access public
   * @return Array
   */
  public function getRegistrosInconsistentes() {
    return $this->aRegistrosInconsistentes; 
  }

  /**
   * Busca os dados de inconsistencia 
   * campo       - campo a ser atualizado
   * tabela      - tabela a ser atualizada
   * chave       - chave que sera atualizada
   * campo_unico - se campo é unico
   * 
   * @access public
   * @return Array
   */
  public function getDadosInconsistentes() {

    $iTabela              = $this->getTabela(); 
    $iRegistroCorreto     = $this->getRegistroCorreto(); 
    $iCodigoInconsitencia = $this->getCodigo(); 

    $oDaoDb_registrosinconsistentesdados = db_utils::getDao('db_registrosinconsistentesdados');

    $sSqlDependencias = $oDaoDb_registrosinconsistentesdados->sql_query_buscaDependencias($iCodigoInconsitencia);
    $rsDependencias   = $oDaoDb_registrosinconsistentesdados->sql_record($sSqlDependencias);

    if ( $oDaoDb_registrosinconsistentesdados->erro_status == "0" ) {
      throw new Exception($oDaoDb_registrosinconsistentesdados->erro_msg);
    }
  
    return db_utils::getColectionByRecord($rsDependencias);
  }

  /**
   * Inclui header de inconsistencia e registros de inconsistencia 
   * 
   * @access public
   * @return void
   */
  public function salvar() {

    if ( !db_utils::inTransaction() ) {
      throw new Exception('Nenhuma transação com o banco de dados definida.');
    }

    /**
     * require apenas
     */
    db_utils::getDao('db_registrosinconsistentesdados', false);
    db_utils::getDao('db_registrosinconsistentes', false);

    /**
     * incluir header dos registros de inconsistencia 
     */
    $oDaoDb_registrosinconsistentes = new cl_db_registrosinconsistentes();
    $oDaoDb_registrosinconsistentes->db136_data    = $this->getData();
    $oDaoDb_registrosinconsistentes->db136_usuario = $this->getUsuario();
    $oDaoDb_registrosinconsistentes->db136_tabela  = $this->getTabela();
    $oDaoDb_registrosinconsistentes->incluir(null);

    /**
     * Erro ao incluir header dos registros de inconsistencia 
     */
    if ( $oDaoDb_registrosinconsistentes->erro_status == "0" ) {
      throw new Exception("Erro ao incluir registro de inconsistência.\n\n" . $oDaoDb_registrosinconsistentes->erro_msg);
    }

    /**
     * Percorre registros inconsistentes e inclui 
     */
    foreach ( $this->getRegistrosInconsistentes() as $oRegistroInconsitente ) {

      $lExcluir = $oRegistroInconsitente->lExcluir ? "true" : "false";
      $oDaoDb_registrosinconsistentesdados                                   = new cl_db_registrosinconsistentesdados();
      $oDaoDb_registrosinconsistentesdados->db137_db_registrosinconsistentes = $oDaoDb_registrosinconsistentes->db136_sequencial;
      $oDaoDb_registrosinconsistentesdados->db137_chave                      = $oRegistroInconsitente->iCodigoRegistro;
      $oDaoDb_registrosinconsistentesdados->db137_excluir                    = $lExcluir;
      $oDaoDb_registrosinconsistentesdados->incluir(null);
      
      /**
       * erro ao incluir registrosinconsistentes 
       */
      if ( $oDaoDb_registrosinconsistentesdados->erro_status == "0" ) {
        throw new Exception("Erro ao gravar registro de inconsistência.\n\n" . $oDaoDb_registrosinconsistentesdados->erro_msg);
      }
    } 

    /**
     * Gravar registro correto para correcao das inconsistências  
     */
    $oDaoDb_registrosinconsistentesdados = new cl_db_registrosinconsistentesdados();
    $oDaoDb_registrosinconsistentesdados->db137_db_registrosinconsistentes = $oDaoDb_registrosinconsistentes->db136_sequencial;
    $oDaoDb_registrosinconsistentesdados->db137_correto                    = 'true';
    $oDaoDb_registrosinconsistentesdados->db137_chave                      = $this->getRegistroCorreto();
    $oDaoDb_registrosinconsistentesdados->db137_excluir                    = 'false';
    $oDaoDb_registrosinconsistentesdados->incluir(null);

    /**
     * Erro ao gravar registro correto 
     */
    if ( $oDaoDb_registrosinconsistentesdados->erro_status == "0" ) {
      throw new Exception("Erro ao gravar registro correto.\n\n" . $oDaoDb_registrosinconsistentesdados->erro_msg);
    }

  }

  /**
   * Exclui inconsistência 
   * 
   * @access public
   * @return void
   */
  public function excluir() {
 
    if ( !db_utils::inTransaction() ) {
      throw new Exception('Nenhuma transação com o banco de dados definida.');
    }

    /**
     * require apenas
     */
    db_utils::getDao('db_registrosinconsistentesdados', false);
    db_utils::getDao('db_registrosinconsistentes', false);

    $oDaoDb_registrosinconsistentesdados = new cl_db_registrosinconsistentesdados();
    $oDaoDb_registrosinconsistentesdados->excluir(null, "db137_db_registrosinconsistentes = " . $this->getCodigo());

    /**
     * erro ao incluir registrosinconsistentes 
     */
    if ( $oDaoDb_registrosinconsistentesdados->erro_status == "0" ) {
      throw new Exception("Erro ao gravar registro de inconsistência.\n\n" . $oDaoDb_registrosinconsistentesdados->erro_msg);
    }
 
    /**
     * exclui header dos registros de inconsistencia 
     */
    $oDaoDb_registrosinconsistentes = new cl_db_registrosinconsistentes();
    $oDaoDb_registrosinconsistentes->excluir($this->getCodigo());

    /**
     * Erro ao excluir header dos registros de inconsistencia 
     */
    if ( $oDaoDb_registrosinconsistentes->erro_status == "0" ) {
      throw new Exception("Erro ao excluir registro de inconsistência.\n\n" . $oDaoDb_registrosinconsistentes->erro_msg);
    }

  }

  static public function getTabelasPermitidas() {

    $aPermitidos = array (   
      'cadenderruacep'            =>  2846,
      'cadenderpais'              =>  2779,
      'cadendermunicipiosistema'  =>  3292,
      'cadenderestado'            =>  2780,
      'cadendermunicipio'         =>  2781,
      'cadenderruaruastipo'       =>  2843,
      'cadenderbairro'            =>  2782,
      'cadenderrua'               =>  2783,
      'cadenderlocal'             =>  2784,
      'cadenderparam'             =>  2936,
      'cadenderruaruas'           =>  2934,
      'cadenderbairrocadenderrua' =>  2910,
      'db_depart'                 =>  154
    );
    return $aPermitidos;
  }
}