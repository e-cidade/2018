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

/**
 * Model para encapsulamento de um SLIP
 * @author Matheus Felini / Bruno Silva
 * @package caixa
 * @subpackage slip
 * @version $Revision: 1.3 $
 */
class Caucao extends Transferencia {

  public function salvar () {
    
    parent::salvar();
    
    $oDaoTipoOperacaoVinculo = db_utils::getDao('sliptipooperacaovinculo');
    $oDaoTipoOperacaoVinculo->k153_slip             = $this->getCodigoSlip();
    $oDaoTipoOperacaoVinculo->k153_slipoperacaotipo = $this->getTipoOperacao();
    $oDaoTipoOperacaoVinculo->incluir($this->getCodigoSlip());
    
    if ($oDaoTipoOperacaoVinculo->erro_status == 0) {
    
    	$sMensagemErro  = "Não foi possível víncular o tipo de slip ao slip.\n\n";
    	$sMensagemErro .= "Erro Técnico: {$oDaoTipoOperacaoVinculo->erro_msg}";
    	throw new Exception($sMensagemErro);
    }
    
    return true;
    
  }
}
?>