<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));  

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case "getItensEmpenho":

      $sCamposEmpenho  = "distinct riseqitem     as item_empenho";
      $sCamposEmpenho .= "         ,ricodmater   as codigo_material";
      $sCamposEmpenho .= "         ,rsdescr      as descricao_material";
      $sCamposEmpenho .= "         ,e62_descr";
      $sCamposEmpenho .= "         ,rnquantini   as quantidade";
      $sCamposEmpenho .= "         ,rnvalorini   as valor_total";
      $sCamposEmpenho .= "         ,rnvaloruni   as valor_unitario";
      $sCamposEmpenho .= "         ,rnsaldoitem  as saldo";
      $sCamposEmpenho .= "         ,round(rnsaldovalor,2) as saldo_valor";
      $sCamposEmpenho .= "         ,o56_descr";
      $sCamposEmpenho .= "         ,case when pcorcamval.pc23_obs is not null";
      $sCamposEmpenho .= "              then pcorcamval.pc23_obs";
      $sCamposEmpenho .= "              else pcorcamvalpai.pc23_obs";
      $sCamposEmpenho .= "         end as observacao";
      $sWhereEmpenho   = "e60_numemp = {$oParam->iCodigoEmpenho}";

      $oDaoEmpenho      = db_utils::getDao("empempenho");
      $sSqlItensEmpenho = $oDaoEmpenho->sql_query_itens_consulta_empenho($oParam->iCodigoEmpenho, $sCamposEmpenho);
      $rsBuscaEmpenho   = $oDaoEmpenho->sql_record($sSqlItensEmpenho);

      $aItensRetorno    = array();

      for ($iRowItem = 0; $iRowItem < $oDaoEmpenho->numrows; $iRowItem++) {

        $oStdItem = db_utils::fieldsMemory($rsBuscaEmpenho, $iRowItem);
        $oStdItem->descricao_material = urlencode($oStdItem->descricao_material);
        $oStdItem->observacao         = urlencode($oStdItem->observacao);
        $oStdItem->e62_descr          = urlencode($oStdItem->e62_descr);
        $aItensRetorno[] = $oStdItem;
      }
      $oRetorno->aItensEmpenho = $aItensRetorno;
      break;

    case "getItensAnulados":

      $oDaoItemAnulado    = db_utils::getDao("empanuladoitem");
      $sCamposItemAnulado = "pc01_codmater,pc01_descrmater,e37_qtd, e37_vlranu,e94_data";                                              
      $sSqlAnulados       = $oDaoItemAnulado->sql_query(null, $sCamposItemAnulado, "e62_sequen", "e62_numemp = {$oParam->iCodigoEmpenho}");                                                    
      $rsBuscaItemAnulado = $oDaoItemAnulado->sql_record($sSqlAnulados);

      $oRetorno->aItensAnuladoEmpenho = db_utils::getCollectionByRecord($rsBuscaItemAnulado);

      break;
  }
  
  db_fim_transacao(false);
    
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);

?>