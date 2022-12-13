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

require_once("libs/exceptions/BusinessException.php");
require_once("libs/exceptions/DBException.php");
/**
 * Nota fiscal
 * 
 * @package ISSQN 
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
class NotaFiscalISSQN {

  /**
   * Grupos de nota 
   */
  const GRUPO_CUPOM               = 1;
  const GRUPO_ELETRONICA          = 2;
  const GRUPO_FATURA              = 3;
  const GRUPO_FORMULARIO_CONTINUO = 4;
  const GRUPO_INGRESSO            = 5;
  const GRUPO_LIVRO               = 6;
  const GRUPO_RPS                 = 7;
  const GRUPO_TALAO               = 8;

  /**
   * Codigo
   * 
   * @var integer
   * @access private
   */
  private $iCodigo;

  /**
   * Tipo de nota
   * 
   * @var string
   * @access private
   */
  private $sTipo;

  /**
   * Descricao da nota
   * 
   * @var string
   * @access private
   */
  private $sDescricao;

  /**
   * Grupo da nota
   * 
   * @var integer
   * @access private
   */
  private $iGrupo;

  /**
   * Construtor da classe
   *
   * @param int $iCodigo
   * @access public
   * @return void
   */
  public function __construct($iCodigo = null) {

    if ( empty($iCodigo) ) {
      return;
    }
    
    $oDaoNota = db_utils::getDao('notasiss');

    $sSqlNota = $oDaoNota->sql_query_file($iCodigo);
    $rsNota   = db_query($sSqlNota); 

    if ( !$rsNota ) {
      throw new Exception("Falha ao buscar Nota: " . $iCodigo . ' - ' . pg_last_error());
    }
    
    if ( pg_num_rows($rsNota) == 0 ) {
    	throw new Exception("Número de nota Fiscal não encontrada: " . $iCodigo);
    }

    $oNota = db_utils::fieldsMemory($rsNota, 0);

    $this->iCodigo    = $iCodigo;
    $this->sTipo      = $oNota->q09_nota;
    $this->sDescricao = $oNota->q09_descr;
    $this->iGrupo     = $oNota->q09_gruponotaiss;
  }

  /**
   * Define o codigo, pk da tabela
   *
   * @param integer $iCodigo
   * @access public
   * @return void
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o codigo, pk da tabela
   *
   * @access public
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o tipo de nota
   *
   * @param string $sTipo
   * @access public
   * @return void
   */
  public function setTipo($sTipo) {
    $this->sTipo = $sTipo;
  }

  /**
   * Retorna o tipo da nota
   *
   * @access public
   * @return string
   */
  public function getTipo() {
    return $this->sTipo;
  }

  /**
   * Define a descricao da nota
   *
   * @param string $sDescricao
   * @access public
   * @return void
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a descricao da nota
   *
   * @access public
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define o codigo do grupo da nota
   *
   * @param integer $iGrupo
   * @access public
   * @return void
   */
  public function setGrupo($iGrupo) {
    $this->iGrupo = $iGrupo;
  }

  /**
   * Retorna o codigo do grupo da nota
   *
   * @access public
   * @return integer
   */
  public function getGrupo() {
    return $this->iGrupo;
  }
  
  /**
   * Salva os dados informados na tabela notasiss
   * @return boolean
   */
  public function salvar() {
  	
  	if (!db_utils::inTransaction()) {
  		throw new DBException('Não existe Transação Ativa');
  	}
  	
  	$oDaoNotaIss                   = db_utils::getDao('notasiss');
  	$oDaoNotaIss->q09_codigo       = $this->iCodigo;
  	$oDaoNotaIss->q09_nota         = $this->sTipo;
  	$oDaoNotaIss->q09_descr        = $this->sDescricao;
  	$oDaoNotaIss->q09_gruponotaiss = $this->iGrupo;
  	
  	/**
  	 * Verifica se já foi indormado algum codigo. Se já foi informada 
  	 * realiza update caso contrário realiza insert
  	 */
    if (empty($this->iCodigo)) {

  		$oDaoNotaIss->incluir(null);
      $this->iCodigo = $oDaoNotaIss->q09_codigo;

  	} else {
  		$oDaoNotaIss->alterar($this->iCodigo);
  	}
  	
  	if ($oDaoNotaIss->erro_status == '0') {
  		throw new DBException($oDaoNotaIss->erro_msg);
  	}
  	
  	return true;
  }

  /**
   * Exclui uma nota Fiscal
   *
   * @exceptions  - Quando NF jÃ¡ tiver aidof lanÃ§ado
   * @access public
   * @return void
   */
  public function excluir() {

    $oDaoAidof      = db_utils::getDao("aidof");
    $sSqlValidacao  = $oDaoAidof->sql_query_file( null,'1', null, "y08_nota = " . $this->iCodigo );
    $rsValidacao    = db_query($sSqlValidacao);

    if ( !$rsValidacao ) {
      throw new DBException("Erro ao validar exclusao de Nota de ISSQN");
    }

    if ( pg_num_rows($rsValidacao) > 0 ) {
      throw new BusinessException("Não foi possivel excluir. Nota com Aidof Liberado.");
    }

    $oDaoNotaIss = db_utils::getDao("notasiss");
    $oDaoNotaIss->excluir($this->iCodigo);

    if ( $oDaoNotaIss->erro_status == "0" ) {
      throw new DBException("Erro ao Excluir Nota.\n\n" . $oDaoNotaIss->erro_msg);
    }

    return true;
  }

}