<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


db_app::import("exceptions.*");

/**
 * Classe Que gencia Envio/Processamento de Remessas a Webservices
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbrafael.nery $
 * @version $Revision: 1.2 $ *
 */
abstract class RemessaWebService {
  
  /**
   * Codigo do Sistema Externo qual a remessa esta ligada
   * @var integer
   */
  private $iSistemaExterno;

  /**
   * Codigo da Remessa(id da tabela)
   * @var integer
   */
  private $iCodigoRemessa;
  
  /**
   * Usuario do Sistema que incluiu a remessa
   * @var UsuarioSistema
   */
  private $oUsuario;
  
  /**
   * Descricao da Remessa
   * @var 
   */
  private $sDescricaoRemessa; 
  
  /**
   * Data da Criacao da Remessa
   * @var string(date)
   */
  private $dDataCriacao; 
  
  /**
   * Data do Processamento da Remessa;
   * @var string(date)
   */
  private $dDataProcessamento;
  
  /**
   * Valida se a Remessa foi processada
   * @var boolean
   */
  private $lProcessada = false;
  
  /**
   * Construtor da Classe
   * @param string  $sTipoRemessa
   * @param integer $iCodigoRemessa
   */
  public function __construct( $iCodigoRemessa = null ) {     
    
    
    if ( !empty($iCodigoRemessa) ) {
      
      db_app::import("configuracao.UsuarioSistema");
      $oDaoDBRemessaWebService = db_utils::getDao("db_remessawebservice");
      $sSqlRemessa             = $oDaoDBRemessaWebService->sql_query_file($iCodigoRemessa);
      $rsRemessa               = $oDaoDBRemessaWebService->sql_record($sSqlRemessa);

      if ( $oDaoDBRemessaWebService->erro_status == "0" ) {
        throw new DBException("[1] - Erro ao Buscar dados da Remessa ");
      }
      
      $oRemessa = db_utils::fieldsMemory($rsRemessa, 0);
      $this->setCodigoRemessa        ( $oRemessa->db127_sequencial );
      $this->setCodigoSistemaExterno ( $oRemessa->db127_sistemaexterno );
      $this->setDataCriacao          ( $oRemessa->db127_datacriacao );
      $this->setDataProcessamento    ( $oRemessa->db127_dataprocessamento );
      $this->setDescricaoRemessa     ( $oRemessa->db127_descricao );
      $this->setProcessada           ( $oRemessa->db127_processada == 't' ? true : false );
      $this->setUsuario              ( new UsuarioSistema($oRemessa->db127_usuario) );
    }
  }
  
  /**
   * Define o Codigo do Sistema Externo Que a Remessa esta vinculada
   * @return 
   */
  public function getCodigoSistemaExterno() {
      return $this->iSistemaExterno;
  }

  /**
   * Define o Codigo do Sistema Externo Que a Remessa esta vinculada
   * @param $iSistemaExterno
   */
  public function setCodigoSistemaExterno($iSistemaExterno) {
      $this->iSistemaExterno = $iSistemaExterno;
  }

  /**
   * Retorna o Codigo da Remessa
   * @return 
   */
  public function getCodigoRemessa() {
      return $this->iCodigoRemessa;
  }

  /**
   * Define o Codigo da Remessa
   * @param $iCodigoRemessa
   */
  public function setCodigoRemessa($iCodigoRemessa) {
      $this->iCodigoRemessa = $iCodigoRemessa;
  }

  /**
   * Retorna o Usuario que incluiu a remessa
   * @return UsuarioSistema 
   */
  public function getUsuario() {
      return $this->oUsuario;
  }
  
  /**
   * Define o Usuario que incluiu a remessa
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario( UsuarioSistema $oUsuario) {
      $this->oUsuario = $oUsuario;
  }

  /**
   * Retorna a Descricao da Remessa
   * @return string
   */
  public function getDescricaoRemessa() {
      return $this->sDescricaoRemessa;
  }

  /**
   * Define a Descricao da Remessa
   * @param $sDescricaoRemessa
   */
  public function setDescricaoRemessa($sDescricaoRemessa) {
      $this->sDescricaoRemessa = $sDescricaoRemessa;
  }

  /**
   * Retorna a Data de Criacao da Remessa
   * @return date
   */
  public function getDataCriacao() {
    return $this->dDataCriacao;
  }

  /**
   * Define a Data de Criacao da Remessa
   * @param $dDataCriacao
   */
  public function setDataCriacao($dDataCriacao) {
    $this->dDataCriacao = $dDataCriacao;
  }

  /**
   * Retorna a Data do Processamento da Remessa
   * @return date
   */
  public function getDataProcessamento() {
    return $this->dDataProcessamento;
  }

  /**
   * Define a Data do Processamento da Remessa
   * @param $dDataProcessamento
   */
  private function setDataProcessamento($dDataProcessamento) {
    $this->dDataProcessamento = $dDataProcessamento;
  }

  /**
   * Retorna o status do Processamento da Remessa
   * @return boolean
   */
  public function isProcessada() {
    return $this->lProcessada;
  }

  /**
   * Define o situacao de Processamento da Remessa
   * @param $lProcessada
   */
  public function setProcessada($lProcessada) {
    $this->lProcessada = $lProcessada;
  }

  /**
   * Processa dados da Remessa
   */
  public function processar() {
    
    self::setDataProcessamento( date( "Y-m-d", db_getsession("DB_datausu") ) );
    $this->setProcessada(true);
    return self::salvar();
  }

  /**
   * Salva Remessa
   * @return Boolean
   */
  public function salvar() {
    
    $oDaoDBRemessaWebService                          = db_utils::getDao("db_remessawebservice");
    $oDaoDBRemessaWebService->db127_sistemaexterno    = $this->getCodigoSistemaExterno();   
    $oDaoDBRemessaWebService->db127_usuario           = $this->oUsuario->getIdUsuario();          
    $oDaoDBRemessaWebService->db127_descricao         = $this->getDescricaoRemessa();     
    $oDaoDBRemessaWebService->db127_datacriacao       = $this->getDataCriacao();
    $oDaoDBRemessaWebService->db127_dataprocessamento = $this->getDataProcessamento();
    $oDaoDBRemessaWebService->db127_processada        = $this->isProcessada() ? 'true' : 'false';
    $iCodigoRemessa                                   = $this->getCodigoRemessa();
    
    if ( !empty( $iCodigoRemessa ) ) {
      
      $oDaoDBRemessaWebService->db127_sequencial      = $this->getCodigoRemessa();
      $oDaoDBRemessaWebService->alterar($this->getCodigoRemessa());
    } else {
      $oDaoDBRemessaWebService->incluir($this->getCodigoRemessa());
    }
    
    if ( $oDaoDBRemessaWebService->erro_status == "0" ) {
      throw new DBException( "Erro ao Salvar dados da Remessa: ".$oDaoDBRemessaWebService->erro_msg );
    }
    $this->setCodigoRemessa($oDaoDBRemessaWebService->db127_sequencial);
    
    return true;
  }
  
}