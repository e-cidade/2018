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
require_once("classes/db_rhempenhoelementopcasp_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhempenhoelementopcasp = new cl_rhempenhoelementopcasp();

$oRotulo = new rotulocampo;
$oRotulo->label("rh119_sequencial");
$oRotulo->label("o56_codele");

if (isset($chave_rh119_sequencial) && !DBNumber::isInteger($chave_rh119_sequencial)) {
  $chave_rh119_sequencial = '';
}

if (isset($chave_o56_codele) && !DBNumber::isInteger($chave_o56_codele)) {
  $chave_o56_codele = '';
}


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
      <table width="60%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Trh119_sequencial; ?>">
              <?php echo $Lrh119_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
                db_input("rh119_sequencial", 6, $Irh119_sequencial, true, "text", 4, "", "chave_rh119_sequencial");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Código Elemento Principal">
              <label class="bold">Código Elemento Principal:</label>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
		            db_input("chave_o56_codele", 8, $Io56_codele, true, "text", 4);
		          ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhempenhoelementopcasp.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top"> 
      <?php


      if (!isset($pesquisa_chave)) {

        if (!isset($campos)) {

          if (file_exists("funcoes/db_func_rhempenhoelementopcasp.php")) {
            include("funcoes/db_func_rhempenhoelementopcasp.php");
          } else {
            $campos = "rhempenhoelementopcasp.*";
          }
        }

        if (isset($chave_rh119_sequencial) && !empty($chave_rh119_sequencial)) {
	        $sql = $clrhempenhoelementopcasp->sql_query($chave_rh119_sequencial,$campos, "rh119_sequencial");
        } else if (isset($chave_o56_codele) && !empty($chave_o56_codele)) {
          $sql = $clrhempenhoelementopcasp->sql_query( null, $campos, " ocdef.o56_codele", " ocdef.o56_codele = {$chave_o56_codele}");
        } else {
           $sql = $clrhempenhoelementopcasp->sql_query("", $campos, "rh119_sequencial","");
        }

        $repassa = array();
        if (isset($chave_rh119_sequencial)) {
          $repassa = array("chave_rh119_sequencial" => $chave_rh119_sequencial, "chave_rh119_sequencial" => $chave_rh119_sequencial);
        }

        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $result = $clrhempenhoelementopcasp->sql_record($clrhempenhoelementopcasp->sql_query($pesquisa_chave));
          if ($clrhempenhoelementopcasp->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$rh119_sequencial', false);</script>";
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
<script>
  js_tabulacaoforms("form2", "chave_rh119_sequencial", true, 1, "chave_rh119_sequencial", true);
</script>
</html>