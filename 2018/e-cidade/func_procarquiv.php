<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_procarquiv_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprocarquiv = new cl_procarquiv;
$clprocarquiv->rotulo->label("p67_codarquiv");
$clprocarquiv->rotulo->label("p67_historico");
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
            <td width="4%" align="right" nowrap title="<?=$Tp67_codarquiv?>">
              <?=$Lp67_codarquiv?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p67_codarquiv",6,$Ip67_codarquiv,true,"text",4,"","chave_p67_codarquiv");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp67_historico?>">
              <?=$Lp67_historico?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p67_historico",0,$Ip67_historico,true,"text",4,"","chave_p67_historico");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php

      $iCodigoDepartamento = db_getsession('DB_coddepto');
      $sWhere              = " p67_coddepto = {$iCodigoDepartamento} ";

      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {
          $campos = "procarquiv.*, p58_requer";
        }

        if (isset($chave_p67_codarquiv) && (trim($chave_p67_codarquiv) != "")) {

          $sWhere .= " and p67_codarquiv = {$chave_p67_codarquiv} ";
          $sql = $clprocarquiv->sql_query(null,$campos, "p67_codarquiv", $sWhere);
        } else if (isset($chave_p67_historico) && (trim($chave_p67_historico) != "")) {

          $sWhere .= " and p67_historico like '$chave_p67_historico%' ";
          $sql = $clprocarquiv->sql_query("", $campos, "p67_historico", $sWhere);
        } else {
          $sql = $clprocarquiv->sql_query("", $campos, "p67_codarquiv", $sWhere);
        }
        db_lovrot($sql, 15, "()", "", $funcao_js);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sWhere .= " and p67_codarquiv = {$pesquisa_chave} ";
          $result = $clprocarquiv->sql_record($clprocarquiv->sql_query(null, "*", null, $sWhere));
          if ($clprocarquiv->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$p67_historico',false);</script>";
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
if(!isset($pesquisa_chave)) {
  ?>
  <script>
document.form2.chave_p67_codarquiv.focus();
document.form2.chave_p67_codarquiv.select();
  </script>
  <?
}
?>