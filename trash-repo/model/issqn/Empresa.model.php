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

require_once("model/CgmFactory.model.php");
require_once("std/DBDate.php");
require_once("model/issqn/Debitos.model.php");

/**
 * Classe que representa uma Empresa (ISSQN/Alvara) no e-Cidade
 * 
 * @package ISSQN
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo  <renan@dbseller.com.br>
 */
class Empresa {
  
  const MENSAGENS = 'tributario.issqn.Empresa.';

  /**
   * Cgm da Empresa
   * @var CgmBase
   */
  protected $oCgmEmpresa;
  
  /**
   * Data de Inscricao da Empresa
   * @var DBDate 
   */
  protected $oDataInicioAtividades;

  /**
   * Inscricao da empresa
   * 
   * @var integer
   * @access protected
   */
  protected $iInscricao;

  /**
   * Situacao da empresa, ativa ou baixada
   * 
   * @var bool
   * @access protected
   */
  protected $lAtiva;


  protected $oDebitos;
  
  /**
   * Construtor da Classe
   * @param integer $iIncricaoMunicipal
   */
  public function __construct( $iIncricaoMunicipal = null ) {
    
    if ( !empty($iIncricaoMunicipal) ) {
      
      $oDaoIssBase    = new cl_issbase();
      $sSql           = $oDaoIssBase->sql_query_file($iIncricaoMunicipal);
      $rsDadosEmpresa = $oDaoIssBase->sql_record($sSql);
      
      /**
       * Não encontrou inscricao
       */
      if ( pg_numrows($rsDadosEmpresa) == 0 ) {
        return null;
      }
      
      if ( $oDaoIssBase->erro_status == "0" ) {
        throw new DBException(_M(self::MENSAGENS . 'erro_buscar_inscricao'));
      }
      
      $oDadosEmpresa               = db_utils::fieldsMemory($rsDadosEmpresa, 0);
      $this->iInscricao            = $oDadosEmpresa->q02_inscr;
      $this->oCgmEmpresa           = CgmFactory::getInstanceByCgm($oDadosEmpresa->q02_numcgm);
      $this->oDataInicioAtividades = new DBDate($oDadosEmpresa->q02_dtinic);
      $this->lAtiva                = empty($oDadosEmpresa->q02_dtbaix); 
      $this->oDebitos              = new Debitos($this->getInscricao());
    }
  }


  /**
   * Retorna a data de inicio das atividades
   * @return DBDate
   */
  public function getDataInicioAtividades() {
    return $this->oDataInicioAtividades;
  }
  
  /**
   * Retorna o CGM da Empresa
   * @return CgmBase - Cgm da Empresa
   */
  public function getCgmEmpresa() {
    return $this->oCgmEmpresa;
  }

  /**
   * Define a inscricao da empresa
   *
   * @param integer $iInscricao
   * @access public
   * @return void
   */
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao;
  }

  /**
   * Define inscricao da empresa
   *
   * @access public
   * @return integer
   */
  public function getInscricao() {
    return $this->iInscricao;
  }

  /**
   * Situacao da empresa, ativa ou baixada
   *
   * @access public
   * @return bool
   */
  public function isAtiva() {
    return $this->lAtiva;
  }

  /**
   * Verifica se empresa esta paralisada
   *
   * @access public
   * @return boolean
   */
  public function isParalisada() {

    /**
     * Inscricao da empresa nao definida 
     */
    if ( empty($this->iInscricao) ) {
      return false;
    }
  
    $oDaoIssbaseparalisacao = db_utils::getDao('issbaseparalisacao');

    $sWhereParalisacao  = " q140_issbase = " . $this->getInscricao();
    $sWhereParalisacao .= " and ( ";

    /**
     * - Data do sistema maior ou igual a data inicial da paralisacao 
     * - Data final da paralisacao não informada
     */
    $sWhereParalisacao .= "     '" . date('Y-m-d', db_getsession('DB_datausu')) . "' >= q140_datainicio and q140_datafim is null";

    /**
     * Data do sistema esta entre a data inicial e final da paralisacao 
     */
    $sWhereParalisacao .= "  or '" . date('Y-m-d', db_getsession('DB_datausu')) . "' between q140_datainicio and q140_datafim ";
    $sWhereParalisacao .= " ) ";

    $sSqlParalisacoes = $oDaoIssbaseparalisacao->sql_query_file(null, "q140_sequencial", null, $sWhereParalisacao);
    $rsParalisacoes   = db_query($sSqlParalisacoes);
  
    /**
     * Erro na query, ao buscar paralisacoes 
     */
    if ( !$rsParalisacoes ) {

      $oErroMensagem = (object) array('sErroBanco' => pg_last_error());
      throw new Exception(_M(self::MENSAGENS . 'erro_buscar_paralisacoes', $oErroMensagem));
    }

    /**
     * Retorna true caso empresa estiver paralisada 
     */
    return pg_num_rows($rsParalisacoes) > 0;
  }

  /**
   * Retorna um objeto do tipo Debitos
   * @return Debitos classe de debitos
   */
  public function getDebitos() {
    return $this->oDebitos;
  }

}