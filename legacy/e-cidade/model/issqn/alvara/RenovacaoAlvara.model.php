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

require_once("model/issqn/alvara/MovimentacaoAlvara.model.php");

/**
 * @fileoverview - Classe de Modelo para movimentações da Renovação do Alvará
 * @author    Vinicius Silva  - vinicius.silva@dbseller.com.br	
 * @package   ISSQN
 * @revision  $Author: dbjeferson.belmiro $
 * @version   $Revision: 1.1 $
 */
class RenovacaoAlvara extends MovimentacaoAlvara {

  /**
   * salva a renovação do alvará usando o método da classe abstrata
   */
  public function processar() {

    parent::salvar();

  	$oIssAlvara = db_utils::getDao('issalvara');
  	
  	$oIssAlvara->q123_sequencial = $this->getAlvara()->getCodigo();
  	$oIssAlvara->q123_situacao   = Alvara::ATIVO;
  	$oIssAlvara->alterar($this->getAlvara()->getCodigo());
    
  	if ( $oIssAlvara->erro_status == "0" ) {
      throw new DBException($oIssAlvara->erro_msg);
    } 	
  }

  /**
   * valida a quantidade de renovações do alvará
   * @param integer $iQuantRenovacoesRealizadas quantas vezes o alvará já foi modificado
   */
  function validaQuantidadeDeRenovacoesAlvara($iQuantRenovacoesRealizadas) {

    $oDaoIsstipoalvara                          = db_utils::getDao('isstipoalvara');
    $iAlvara                                    = $this->getAlvara()->getCodigo();
    $sWhereBuscaQuantidadePermitidaDeRenovacoes = " q123_sequencial = {$iAlvara} ";
    $sSqlBuscaQuantidadePermitidaDeRenovacoes   = $oDaoIsstipoalvara->sql_query_tipocomalvaravinculado( null, 
                                                                                                        "q98_quantrenovacao", 
                                                                                                        null,
                                                                                                        $sWhereBuscaQuantidadePermitidaDeRenovacoes );
    $rsBuscaQuantidadePermitidaDeRenovacoes     = $oDaoIsstipoalvara->sql_record($sSqlBuscaQuantidadePermitidaDeRenovacoes);
    $iQuantidadeRenovacoesPermitidas            = db_utils::fieldsMemory($rsBuscaQuantidadePermitidaDeRenovacoes, 0)->q98_quantrenovacao;

    if ( $iQuantRenovacoesRealizadas >= $iQuantidadeRenovacoesPermitidas ) {
      throw new BusinessException("O alvará já alcançou o limite de renovações permitido.");
    }
  }
}