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

require_once("libs/exceptions/DBException.php");
require_once("model/issqn/alvara/MovimentacaoAlvara.model.php");

class BaixaAlvara extends MovimentacaoAlvara {
  
  /**
   * 1 - Pedido
   * 2 - Oficio
   * 
   * @var integer
   * @access private
   */
  private $iTipoBaixa;

  /**
   * Define o tipo de baixa 
   *
   * @param integer $iTipoBaixa
   * @access public
   * @return void
   */
  public function setTipoBaixa($iTipoBaixa) {
    $this->iTipoBaixa = $iTipoBaixa;
  }

  /**
   * Processar
   *
   * @param integer $iTipo
   * @access public
   * @return void
   */
  public function processar() {
  	
    parent::salvar();

  	$oIssAlvara = db_utils::getDao('issalvara');
  	$oBaixa     = db_utils::getDao('issmovalvarabaixa');
  	
  	$oBaixa->q129_issmovalvara = $this->getCodigo();
  	$oBaixa->q129_tipobaixa    = $this->iTipoBaixa;
  	$oBaixa->incluir(null);

  	if ($oBaixa->erro_status == "0") {
  		throw new DBException($oBaixa->erro_msg);
  	}
  	
  	$oIssAlvara->q123_sequencial = $this->getAlvara()->getCodigo();
  	$oIssAlvara->q123_situacao   = Alvara::INATIVO;
  	$oIssAlvara->alterar($this->getAlvara()->getCodigo());
    
  	if ($oIssAlvara->erro_status == "0") {
      throw new DBException($oIssAlvara->erro_msg);
    } 	

  }
  
}