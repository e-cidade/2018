<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

$oDaoDebContaPedido     = db_utils::getDao("debcontapedido");
$oDaoDebContaPedidoTipo = db_utils::getDao("debcontapedidotipo");

/**
 * Virada Debito em Conta IPTU e AGUA
 */
if ($sqlerro == false) {
	
	/**
	 * Virada Debito em Conta IPTU
	 */
	$sCampos                = "d66_codigo,                                                                    ";
  $sCampos               .= "( select q92_tipo                                                              ";
  $sCampos               .= "    from cfiptu                                                                ";
  $sCampos               .= "         inner join cadvencdesc on q92_codigo = j18_vencim                     ";
  $sCampos               .= "   where j18_anousu = {$anodestino} limit 1 ) as d66_arretipo                  ";
  
	$sWhere                 = "    d63_instit   = ( select codigo                                             ";
  $sWhere                .= "                       from db_config                                          ";
  $sWhere                .= "                      where prefeitura is true limit 1 )                       ";
  $sWhere                .= "and d66_arretipo = ( select q92_tipo                                           ";
  $sWhere                .= "                       from cfiptu                                             ";
  $sWhere                .= "                            inner join cadvencdesc on q92_codigo = j18_vencim  ";
  $sWhere                .= "                      where j18_anousu = {$anoorigem} limit 1 )                ";
  
  $sSqlDebContaPedido     = $oDaoDebContaPedido->sql_query_deb_conta(null, $sCampos, null, $sWhere);
  $rsDebContaPedido       = $oDaoDebContaPedido->sql_record($sSqlDebContaPedido);
  $iNumRowsDebContaPedido = $oDaoDebContaPedido->numrows;
  if ($iNumRowsDebContaPedido > 0) {
  	
  	for ($iInd = 0; $iInd < $iNumRowsDebContaPedido; $iInd++) {
  		
  		db_atutermometro($iInd, $iNumRowsDebContaPedido, 'termometroitem', 1, $sMensagemTermometroItem . " (Passo 1/1)");
  		
  	  $oDebContaPedido = db_utils::fieldsMemory($rsDebContaPedido, $iInd);
  	  
      $oDaoDebContaPedidoTipo->d66_codigo   = $oDebContaPedido->d66_codigo;
      $oDaoDebContaPedidoTipo->d66_arretipo = $oDebContaPedido->d66_arretipo;
      $oDaoDebContaPedidoTipo->incluir(null);
      if ($oDaoDebContaPedidoTipo->erro_status == 0) {
      	
        $sqlerro   = true;
        $erro_msg .= $oDaoDebContaPedidoTipo->erro_msg;
      }
  	}
  }
}
?>