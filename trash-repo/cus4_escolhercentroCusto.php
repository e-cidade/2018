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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$oGet               = db_utils::postMemory($_GET);
$sDescricaoMaterial = "";
$sWhere             = "cc08_instit = ".db_getsession("DB_instit") ." and cc08_ativo is true";
if (isset($oGet->iOrigem) && $oGet->iOrigem != "") {
  
  /*
   * Tipos de Origem 1 => liquidacao sem ordem de compra
   *                   * Consultamos o material do item do empenho.
   *                 2 => saida do estoque
   */
  
  if ($oGet->iOrigem == 1) {
    
    $oDaoEmpEmpitem = db_utils::getDao("empempitem"); 
    $sSqliTem       = $oDaoEmpEmpitem->sql_query(null,null,"*",null,"e62_sequencial = {$oGet->iCodItem}");
    $rsItem         = $oDaoEmpEmpitem->sql_record($sSqliTem);
    if ($oDaoEmpEmpitem->numrows > 0) {
      
       $oMaterial             = db_utils::fieldsMemory($rsItem, 0);
       $oGet->iCodigoMaterial = $oMaterial->e62_item;
       $sDescricaoMaterial    = $oMaterial->pc01_descrmater;  
          
    }
  } else if ($iOrigem == 2 ){

      $oDaoTransMater = db_utils::getDao("transmater");
      $sSqlItem       = $oDaoTransMater->sql_query(null,"*",null,"m63_codmatmater= {$oGet->iCodItem}");
      $rsItem         = $oDaoTransMater->sql_record($sSqlItem);
      if ($oDaoTransMater->numrows > 0) {
      
       $oMaterial             = db_utils::fieldsMemory($rsItem, 0);
       $oGet->iCodigoMaterial = $oMaterial->pc01_codmater;
       $sDescricaoMaterial    = $oMaterial->pc01_descrmater;  
          
    } 
    
  }
}
if (isset($oGet->iCodigoDepto) && $oGet->iCodigoDepto != "") {
  $sWhere .= " and cc08_coddepto = {$oGet->iCodigoDepto}";
}

if (isset($oGet->iCodigoMaterial) && $oGet->iCodigoMaterial != "") {
  
  $sWhere  .= " and ( case when cc08_automatico is false then ( cc10_pcmater = {$oGet->iCodigoMaterial})";
  $sWhere  .= "      when cc08_automatico is true and t52_descr is null then true" ; 
  $sWhere  .= "      when cc08_automatico is true and t52_descr is not null then false" ; 
  //$sWhere  .= "      when cc08_automatico is true then true" ; 
  $sWhere  .= " end ) ";
  
}
if (isset($oGet->iCodigoCriterio) && $oGet->iCodigoCriterio != "") {

  $sWhere .= " and cc08_sequencial = {$oGet->iCodigoCriterio} "; 
}
$oDaoCriterio   = db_utils::getDao("custocriteriorateio");
$sSqlCriterios  = $oDaoCriterio->sql_custocriterios(null,
                                                    "distinct cc08_sequencial, 
                                                     cc08_descricao,
                                                     t52_descr,
                                                     descrdepto,
                                                     case when cc08_automatico is true 
                                                               and t52_descr is null then 'Conta de Custo'
                                                          when cc08_automatico is true  and t52_descr is not null
                                                               then 'Bem Patrimonial'
                                                           when  cc08_automatico is false then 'Criterio de Rateio'
                                                           end  as DL_Tipo      
                                                     ",
                                                    "",
                                                    $sWhere
                                                   ); 
                                                   
if (isset($oGet->iCodigoCriterio) && $oGet->iCodigoCriterio != "") {

  $rsCriterio = db_query($sSqlCriterios);
  if (pg_num_rows($rsCriterio)  > 0) {

    $oCriterio = db_utils::fieldsMemory($rsCriterio, 0);
    echo  "<script>parent.js_completaCustos('{$oGet->iCodigoDaLinha}','{$oCriterio->cc08_sequencial}','$oCriterio->cc08_descricao');</script>";
  } else {
    echo  "<script>parent.js_completaCustos('{$oGet->iCodigoDaLinha}','','Chave ({$oGet->iCodigoCriterio}) não encontrado');</script>";
  }
}                                                   
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC">
  <center>
   <table>
      <?
       db_lovrot($sSqlCriterios, 15,"()","","js_completaCustos|cc08_sequencial|cc08_descricao");
      ?>
   </table>
   <input type='button' onclick='js_limparCustos()' value="Zerar Custos">
   </center>
  </body>
</html>
<script>
function js_completaCustos(iCodigoCriterio, sDescricao) {

    parent.js_completaCustos(<?=$oGet->iCodigoDaLinha ?>,iCodigoCriterio, sDescricao);
}
function  js_limparCustos() {
   parent.js_completaCustos(<?=$oGet->iCodigoDaLinha ?>,"", "Escolher");
}
</script>