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
 
require_once 'model/pessoal/Ponto.model.php';

/**
 * DefiniÃµes sobrte ponto Fixo do Servidors
 * 
 * @uses    Ponto
 * @package Pessoal 
 * @author  Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 */
class PontoFixo extends Ponto {

  /**
   * Nome da tabela para ponto fixo 
   */
  const TABELA = Ponto::FIXO;

  /**
   * Sigla da tabela do ponto fixo 
   */
  const SIGLA_TABELA = 'r90';

  /**
   * Construtor da classe
   *
   * @param Servidor $oServidor
   * @access public
   * @return void
   */
  public function __construct( Servidor $oServidor) {

    parent::__construct($oServidor);

    $this->sTabela = self::TABELA;
    $this->sSigla  = self::SIGLA_TABELA;
  }

  /**
   * Função para gerar ponto para o mes selecionado
   */
  public function gerar() {

  }

  /**
   * Funcao para retornar as movimentacoes das rubricas do ponto
   */
  public function getMovimentacoes($iCodigoRubrica = null) {
    
    $oDaoPontofx = db_utils::getDao('pontofx');
    
    $sWhere      = "     r90_anousu = '" . $this->getServidor()->getAnoCompetencia()  . "'";
    $sWhere     .= " and r90_mesusu = '" . $this->getServidor()->getMesCompetencia()  . "'";
    $sWhere     .= " and r90_regist = '" . $this->getServidor()->getMatricula()       . "'";
    
    if ( !empty($iCodigoRubrica) ) {
      $sWhere   .= " and r90_rubric = '" . $iCodigoRubrica                      . "'";
    }
    
    $sSqlPontofx = $oDaoPontofx->sql_query_file(null, null, null, null, '*', null, $sWhere);
    $rsPontofx   = db_query($sSqlPontofx);
    
    if ( !$rsPontofx ) {
      throw new DBException("Erro ao Buscar Movimentações: " . pg_last_error() );
    }
    
    return db_utils::getColectionByRecord($rsPontofx);    
  }

  /**
   * Funcao para retornar as rubricas utilizadas no ponto
   */
  public function getRubricas() {
  }
  
  /**
   * Verifica se o usuário possui ponto fixo no ano e mes da competencia
   *
   * @param Servidor $oServidor
   * @return boolean
   */
  static function validarExistencia(Servidor $oServidor, Rubrica $oRubrica) {
    
    $oPontoFixo     = new PontoFixo($oServidor);
    $aMovimentacoes = $oPontoFixo->getMovimentacoes($oRubrica->getCodigo());
    
    if (count($aMovimentacoes) == 0) {
      return false;
    }
    
    return true;
  }

}