<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_conlancamval_classe.php"));
include(modification("classes/db_conlancamdig_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clconlancamval = new cl_conlancamval;
$clconlancamval->rotulo->label("c69_sequen");
$clconlancamval->rotulo->label("c69_codlan");
$clconlancamval->rotulo->label("c69_codhist");
$clconlancamdig = new cl_conlancamdig;
$clconlancamdig->rotulo->label("c78_chave");

$anousu = db_getsession("DB_anousu");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="50%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc69_codlan?>">
              <?=$Lc69_codlan?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?  db_input("c69_codlan",10,$Ic69_codlan,true,"text",4,"","chave_c69_codlan");  ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc69_sequen?>">
              <?=$Lc69_sequen?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?  db_input("c69_sequen",10,$Ic69_sequen,true,"text",4,"","chave_c69_sequen");  ?>
            </td>
          </tr>

      <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc69_codhist?>">
              <?=$Lc69_codhist?>
            </td>
            <td width="96%" align="left" nowrap> 
            <?  db_input("c69_codhist",10,$Ic69_codhist,true,"text",4,"","chave_c69_codhist"); ?>
            </td>
        <td align="right" nowrap ><?=$Lc78_chave?></td>
        <td align=left nowrap>
          <? db_input("c78_chave",20,"",true,1,"");  ?>
        </td>
     </tr>
     <tr>
       <td>
        <b>
         Período :
	 </b>
       </td>
       <td nowrap colspan="4" align="left" >
          <? db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1);  ?>
          à
	  <? db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',1);  ?>

      </td>
     </tr>

       <tr> 
        <td colspan="4" align="center"> 
         <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
         <input name="limpar" type="reset" id="limpar" value="Limpar" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conlancamval.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php

      $iInstituicao = db_getsession('DB_instit');
      $sCampos      = "*";
      $iChave       = null;
      $sOrder       = null;
      $aWhere       = array();

      $aWhere[] = " c02_instit = {$iInstituicao} ";

      $data1 = "";
      $data2 = "";

      if (!empty($data_ini_ano) && !empty($data_ini_mes) && !empty($data_ini_dia)) {
        $data1 = "{$data_ini_ano}-{$data_ini_mes}-{$data_ini_dia}";
      }

      if (!empty($data_fim_ano) && !empty($data_fim_mes) && !empty($data_fim_dia)) {
        $data2 = "{$data_fim_ano}-{$data_fim_mes}-{$data_fim_dia}";
      }

      if (strlen($data1) < 7) {
          $data1 = "";
      }  
      if (strlen($data2) < 7) {
          $data2 = "";
      }

      if (!isset($pesquisa_chave)) {

        $sCampos = "c69_sequen, c69_codlan, c69_codhist as db_c69_codhist, c50_descr, c69_credito, c69_debito, c69_valor, c69_anousu as db_c69_anousu, c69_data";
        if (isset($chave_c69_sequen) && (trim($chave_c69_sequen) != "")) {

          $iChave = $chave_c69_sequen;
          $sOrder = "c69_sequen";
          $aWhere = null;
        } else if (isset($chave_c69_codhist) && (trim($chave_c69_codhist) != "")) {

          $iChave   = null;
          $sOrder   = "c69_codhist";
          $aWhere[] = "c69_anousu = {$anousu} and c69_codhist like '{$chave_c69_codhist}%'";
        } else if (isset($chave_c69_codlan) && (trim($chave_c69_codlan) != "")) {

          $iChave   = null;
          $sOrder   = "c69_sequen";
          $aWhere[] = "c69_anousu = {$anousu} and c69_codlan = {$chave_c69_codlan}";
        } else if (isset($c78_chave) && (trim($c78_chave) != "")) {

          $iChave   = null;
          $sOrder   = "c69_sequen";
          $aWhere[] = "c69_anousu = {$anousu} and  c78_chave like '{$c78_chave}%'";
        } else if ($data1 != '') {

          $iChave = null;
          $sOrder = "c69_sequen";
          if ($data2 != '') {
            $aWhere[] = "c69_anousu = {$anousu} and c69_data between '{$data1}' and '{$data2}'";
          } else {
            $aWhere[] = "c69_anousu = {$anousu} and c69_data = '{$data1}'";
          }
        } else {

          if (isset($pesquisar)) {

            $iChave   = null;
            $sOrder   = "c69_sequen";
            $aWhere[] = "c69_anousu = {$anousu}";
          } else {

            $iChave = null;
            $aWhere = null;
          }
        }

        $sSql = "";
        if (!empty($aWhere) || !empty($iChave)) {

          $sWhere = "";
          if (!empty($aWhere)) {
            $sWhere = implode(" and ", $aWhere);
          }

          if (!empty($iChave)) {
            $sWhere = "c69_sequen = {$iChave} and c02_instit = {$iInstituicao}";
          }
          $sSql = $clconlancamval->sql_query($iChave, $sCampos, $sOrder, $sWhere);
        }
        db_lovrot($sSql, 15, "()", "", $funcao_js);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $aWhere[] = "c69_anousu = {$anousu}";
          $sWhere   = implode("and", $aWhere);

          $result = $clconlancamval->sql_record($clconlancamval->sql_query($pesquisa_chave, null, null, $sWhere));
          if ($clconlancamval->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$c69_codhist',false);</script>";
          } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
          echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
