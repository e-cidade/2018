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

require_once("model/Taxa.model.php");
db_app::import('exceptions.*');

/**
 * Classe para manipulação de processos do foro
 *
 * @require  {db_utils}, {taxa}, {inicial}
 * @author   Rafael Serpa Nery                 - rafael.nery@dbseller.com.br
 * @author   Jeferson Rodrigo Prudente Belmiro - jeferson.belmiro@dbseller.com.br
 * @package  Jurídico
 * @revision $Author: dbalberto $
 * @version  $Revision: 1.12 $
 *
 */
class ProcessoForo {

  /**
   * Código da instituição do processo do foro
   * @var integer
   */
  private $iCodigoInstituicao;

  /**
   * Estado do processo do foro
   * @var boolean
   */
  private $lAnulado;

  /**
   * Valor inicial do processo
   * @var float
   */
  private $nValorInicial;

  /**
   * Código do cartorio
   * @var integer
   */
  private $iCartorio;

  /**
   * Usuário do sistema que criou processo do foro
   * @var integer
   */
  private $iUsuario;

  /**
   * Obervações do processo do foro
   * @var string
   */
  private $sObservacoes;

  /**
   * Data do processo do foro
   * @var date
   */
  private $dDataProcesso;

  /**
   * Iniciais vinculadas ao processo do foro
   * @var array
   */
  private $aIniciais;

  /**
   * Custas vinculadas ao processo do foro
   * @var array
   */
  private $aCustas;

  /**
   * Número do processo
   * @var integer
   */
  private $iNumeroProcesso;

  /**
   * Código da vara do processo do foro
   * @var integer
   */
  private $iVara;

  /**
   * Código do processo do foro
   * @var integer
   */
  private $iCodigoProcesso;

  /**
   * Construtor da classe
   * @param $iCodigoProcesso
   */
  function __construct($iCodigoProcesso = null) {
  	
    if(!empty($iCodigoProcesso)) {
      /**
       * DAO processo do foro
       */
      $oDaoProcessoForo = db_utils::getDao('processoforo');
      $sSqlProcessoForo = $oDaoProcessoForo->sql_query_file($iCodigoProcesso);
      $rsProcessoForo   = $oDaoProcessoForo->sql_record($sSqlProcessoForo);
      // Erro na consulta
      if($oDaoProcessoForo->erro_status == '0') {
        throw new Exception($oDaoProcessoForo->erro_msg);
      }
      $oProcessoForo = db_utils::fieldsMemory($rsProcessoForo, 0);

      /**
       * DAO partilha custa
       */
      $oDaoProcessoForoPartilhaCusta = db_utils::getDao("processoforopartilhacusta");
      $sCamposCustaProcesso          = 'v77_taxa, v77_valor, v86_numcgm, v77_numnov';
      $sSqlCustasProcesso            = $oDaoProcessoForoPartilhaCusta->sql_query_taxasFavorecidos($iCodigoProcesso, $sCamposCustaProcesso);
      $rsCustasProcesso              = db_query($sSqlCustasProcesso);
      // Erro na consulta
      if( !$rsCustasProcesso )  {
        throw new Exception( "Erro ao Retornar as Custas do Processo do Foro: " . pg_last_error() );
      }
      $aCustasProcesso = db_utils::getCollectionByRecord($rsCustasProcesso);

      foreach($aCustasProcesso as $oCustaProcesso) {

        $oCustas                                  = new stdClass();
        $oCustas->oTaxa                           = new Taxa($oCustaProcesso->v77_taxa);
        $oCustas->nValorTaxa                      = $oCustaProcesso->v77_valor;
        $oCustas->iNumnov                         = $oCustaProcesso->v77_numnov;
        $this->aCustas[$oCustaProcesso->v77_taxa] = $oCustas;
      }

      $oDaoProcessoForoInicial = db_utils::getDao("processoforoinicial");
      $sSqlIniciaisProcesso    = $oDaoProcessoForoInicial->sql_query(null, "v71_inicial", null, "v71_processoforo = {$iCodigoProcesso}");
      $rsIniciaisProcesso      = $oDaoProcessoForoInicial->sql_record($sSqlIniciaisProcesso);

      if($oDaoProcessoForoInicial->erro_status == '0') {
        throw new Exception($oDaoProcessoForoInicial->erro_msg);
      }

      $aIniciaisProcesso       = db_utils::getCollectionByRecord($rsIniciaisProcesso);

      foreach ($aIniciaisProcesso as $oInicialProcesso) {
        $this->adicionarInicial($oInicialProcesso->v71_inicial);
      }

      /**
       * Definindo atributos da classe
       */
      $this->setCodigoProcesso   ($oProcessoForo->v70_sequencial);
      $this->setNumeroProcesso   ($oProcessoForo->v70_codforo);
      $this->setUsuario          ($oProcessoForo->v70_id_usuario);
      $this->setVara             ($oProcessoForo->v70_vara);
      $this->setDataProcesso     ($oProcessoForo->v70_data);
      $this->setValorInicial     ($oProcessoForo->v70_valorinicial);
      $this->setObservacoes      ($oProcessoForo->v70_observacao);
      $this->anulaProcessoForo   ($oProcessoForo->v70_anulado);
      $this->setCodigoInstituicao($oProcessoForo->v70_instit);
      $this->setCartorio         ($oProcessoForo->v70_cartorio);
    }


  }

  /**
   * Retorna Custas do processo do foro
   * @return
   */
  public function getCustas() {
    return $this->aCustas;
  }
  
  public function getCustasRecibo($iNumpreRecibo) {
    
    /**
     * DAO partilha custa
     */
    $oDaoProcessoForoPartilhaCusta = db_utils::getDao("processoforopartilhacusta");
    $sCamposCustaProcesso          = 'v77_taxa, v77_valor, v86_numcgm, v77_numnov';
    $sSqlCustasProcesso            = $oDaoProcessoForoPartilhaCusta->sql_query_taxasFavorecidos($this->getCodigoProcesso(), $sCamposCustaProcesso, $iNumpreRecibo);
    $rsCustasProcesso              = db_query($sSqlCustasProcesso);
    // Erro na consulta
    if( !$rsCustasProcesso )  {
      throw new Exception( "Erro ao Retornar as Custas do Processo do Foro: " . pg_last_error() );
    }
    
    $aCustasProcesso = db_utils::getCollectionByRecord($rsCustasProcesso);
    $aCustasRecibo   = array();
    
    foreach($aCustasProcesso as $oCustaProcesso) {
    
      $oCustas                                  = new stdClass();
      $oCustas->oTaxa                           = new Taxa($oCustaProcesso->v77_taxa);
      $oCustas->nValorTaxa                      = $oCustaProcesso->v77_valor;
      $oCustas->iNumnov                         = $oCustaProcesso->v77_numnov;
      
      $aCustasRecibo[$oCustaProcesso->v77_taxa] = $oCustas;
      
    }

    return $aCustasRecibo;
    
  }

  /**
   * Define estado do processo do foro
   * @param $lAnulado - estado do precesso do foro
   */
  public function anulaProcessoForo( $lAnulado )  {
    $this->lAnulado = $lAnulado;
  }

  /**
   * Retorna o estado do processo do foro
   * @return boolean
   */
  public function isAnulado() {
    return $this->lAnulado;
  }

  /**
   * Adiciona iniciais ao processo do foro
   * @param $iCodigoInicial - Inicial do processo do foro
   */
  public function adicionarInicial($iCodigoInicial) {

    db_app::import('inicial');
    $this->aIniciais[$iCodigoInicial] = new inicial($iCodigoInicial);
  }

  /**
   * Retorna Iniciais vinculadas ao Processo do Foro
   * @return
   */
  public function getIniciais() {
    return $this->aIniciais;
  }

  /**
   * Define código da Instituição
   * @param integer $iCodigoInstituicao - Codigo da Instuição
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * Retorna o código da insituição
   * @return integer
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * Define o valor inicial do processo do foro
   * @param $nValorInicial - Valor inicial do processo
   */
  public function setValorInicial($nValorInicial) {
    $this->nValorInicial = $nValorInicial;
  }

  /**
   * Retorna o valor inicial do processo do foro
   * @return float
   */
  public function getValorInicial() {
    return $this->nValorInicial;
  }

  /**
   * Define o código do cartorio do processo do foro
   * @param $iCartorio - Código do cartorio
   */
  public function setCartorio($iCartorio) {
    $this->iCartorio = $iCartorio;
  }

  /**
   * Retorna codigo do cartório
   * @return integer
   */
  public function getCodigoCartorio() {
    return $this->iCartorio;
  }

  /**
   * Usuário do sistema da geração do processo do foro
   * @param $iUsuario - Usuario do sistema
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }

  /**
   * Retorna codigo do usuario
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }

  /**
   * Obervações do processo do foro
   * @param $sObservacoes - Observações do processo do foro
   */
  public function setObservacoes($sObservacoes) {
    $this->sObservacoes = $sObservacoes;
  }

  /**
   * Retorna Observações do Processo do Foro
   * @return string
   */
  public function getObservacoes() {
    return $this->sObservacoes;
  }

  /**
   * Data do precesso do foro
   * @param $dDataProcesso - Data do processo do foro
   */
  public function setDataProcesso($dDataProcesso) {
    $this->dDataProcesso = $dDataProcesso;
  }

  /**
   * Retorna a Data do Processo
   * @return
   */
  public function getDataProcesso() {
    return $this->dDataProcesso;
  }

  /**
   * Define o número do precesso do foro
   * @param $iNumeroProcesso - Número do processo do foro
   */
  public function setNumeroProcesso($iNumeroProcesso) {
    $this->iNumeroProcesso = $iNumeroProcesso;
  }

  /**
   * Retorna o numero do Processo do foro
   * @return integer
   */
  public function getNumeroProcesso() {
    return $this->iNumeroProcesso;
  }

  /**
   * Define a vara do processo
   * @param $iVara - Código da vara do processo
   */
  public function setVara($iVara) {
    $this->iVara = $iVara;
  }

  /**
   * Retorna codigo da vara do processo do Foro
   * @return integer
   */
  public function getVara() {
    return $this->iVara;
  }

  /**
   * Define o código do processo do foro
   * @param $iCodigoProcesso - Código do processo do foro
   */
  public function setCodigoProcesso($iCodigoProcesso) {
    $this->iCodigoProcesso = $iCodigoProcesso;
  }

  /**
   * Retorna código do processo
   * @return integer
   */
  public function getCodigoProcesso() {
    return $this->iCodigoProcesso;
  }

  /**
   * Retorna o valor base que foi utilizado para emissao das Custas 
   * @param recibo $oRecibo
   */
  public function getValorBaseCustasGeradas( recibo $oRecibo ) {
    
    $oDaoProcessoForoPartilhaCusta = db_utils::getDao("processoforopartilhacusta");
    $sSqlValores                   = $oDaoProcessoForoPartilhaCusta->sql_query(null,"distinct v76_valorpartilha", "", "v77_numnov = {$oRecibo->getNumpreRecibo()}");
    $rsValores                     = $oDaoProcessoForoPartilhaCusta->sql_record($sSqlValores);
    if ( $oDaoProcessoForoPartilhaCusta->erro_status == "0" ) {
      throw new DBException($oDaoProcessoForoPartilhaCusta->erro_msg);
    }
    $nValorBaseDebito              = db_utils::fieldsMemory($rsValores,0)->v76_valorpartilha;
    return $nValorBaseDebito;
  } 
  
  /**
   *  
   */
  public static function getInstanceByNumpre( $iNumpre) {
    
    $oDaoArretipo                  = db_utils::getDao("arretipo");
    $oDaoProcessoForoPartilhaCusta = db_utils::getDao("processoforopartilhacusta");
    $rsTipoDebito                  = $oDaoArretipo->sql_query_numpre($iNumpre, " distinct k03_tipo ");
    $iCadTipoDebito                = db_utils::fieldsMemory($rsTipoDebito,0)->k03_tipo; 
		$aProcessoForo                 = $oDaoProcessoForoPartilhaCusta->getProcessoForoByNumprePacelamento($iNumpre, $iCadTipoDebito);

		if ( count($aProcessoForo) > 0 ) {
			return new ProcessoForo( $aProcessoForo[0] );
		}

		return false;
  }
  
}