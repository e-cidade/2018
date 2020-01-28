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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_placaixa_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clplacaixa = new cl_placaixa;
$clplacaixa->rotulo->label("k80_codpla");
$clplacaixa->rotulo->label("k80_data");
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
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk80_codpla ?>">
              <?=$Lk80_codpla ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("k80_codpla", 6, $Ik80_codpla, true, "text", 4, "", "chave_k80_codpla");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_placaixa.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhere  = "     k80_instit = " . db_getsession("DB_instit");
      $sWhere .= " and to_char(k80_data,'YYYY') = '". db_getsession("DB_anousu") . "'";


      /**
       * Filtro para planilhas NAO AUTENTICADAS
       */
      if (isset($lAutenticada) && $lAutenticada == "false") {
        $sWhere .= " and k80_dtaut is null ";
      }

      /**
       * Filtro para planilhas AUTENTICADAS
       */
      if (isset($lAutenticada) && $lAutenticada == "true") {
        $sWhere .= " and k80_dtaut is not null ";
      }

      if (!empty($lPlanilhasSemSlip)) {

        $sWhere .= " and not exists (select 1
                                       from placaixarec
                                            inner join placaixarecslip on placaixarecslip.k110_placaixarec = placaixarec.k81_seqpla
                                      where placaixarec.k81_codpla = placaixa.k80_codpla) ";
      }

      $campos = " distinct placaixa.* ";

      if (!isset($pesquisa_chave)) {


        $sql = $clplacaixa->sql_query(null, $campos, "k80_codpla"," {$sWhere} ");
        if (isset($chave_k80_codpla) && (trim($chave_k80_codpla) != "")) {
          $sql = $clplacaixa->sql_query(null, $campos, "k80_codpla"," {$sWhere} and k80_codpla = $chave_k80_codpla");
        } else if (isset($chave_k80_data) && (trim($chave_k80_data) != "")) {
          $sql = $clplacaixa->sql_query("", $campos, "k80_data", "  {$sWhere}   and k80_data like '$chave_k80_data%' ");
        }

        db_lovrot($sql, 15, "()", "", $funcao_js);
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sWhere  .= " and k80_codpla = {$pesquisa_chave} ";

          $result = $clplacaixa->sql_record($clplacaixa->sql_query(null, "*", null, $sWhere));
          if ($clplacaixa->numrows != 0) {
            db_fieldsmemory($result, 0);
            echo "<script>" . $funcao_js . "('$k80_data', false, '$k80_codpla');</script>";
          } else {
            echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
          }
        } else {
          echo "<script>" . $funcao_js . "('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>