<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_econsigmovimentoservidor_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cleconsigmovimentoservidor = new cl_econsigmovimentoservidor();
$cleconsigmovimentoservidor->rotulo->label("rh134_sequencial"); 
$cleconsigmovimentoservidor->rotulo->label("rh134_sequencial");

if (isset($chave_rh134_sequencial) && !DBNumber::isInteger($chave_rh134_sequencial)) {
  $chave_rh134_sequencial = '';
}


if (isset($chave_rh134_sequencial) && !DBNumber::isInteger($chave_rh134_sequencial)) {
  $chave_rh134_sequencial = '';
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
      <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Trh134_sequencial; ?>">
              <?php echo $Lrh134_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
		            db_input("rh134_sequencial", 10, $Irh134_sequencial, true, "text", 4, "", "chave_rh134_sequencial");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh134_sequencial; ?>">
              <?php echo $Lrh134_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_input("rh134_sequencial", 10, $Irh134_sequencial, true, "text", 4, "", "chave_rh134_sequencial");
		          ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_econsigmovimentoservidor.hide();">
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

          if (file_exists("funcoes/db_func_econsigmovimentoservidor.php")) {
            include("funcoes/db_func_econsigmovimentoservidor.php");
          } else {
            $campos = "econsigmovimentoservidor.*";
          }
        }

        if (isset($chave_rh134_sequencial) && (trim($chave_rh134_sequencial) != "")) {
	         $sql = $cleconsigmovimentoservidor->sql_query($chave_rh134_sequencial,$campos, "rh134_sequencial");
        } else if (isset($chave_rh134_sequencial) && (trim($chave_rh134_sequencial)!="") ){
	         $sql = $cleconsigmovimentoservidor->sql_query("", $campos, "rh134_sequencial"," rh134_sequencial like '$chave_rh134_sequencial%' ");
        } else {
           $sql = $cleconsigmovimentoservidor->sql_query("", $campos, "rh134_sequencial","");
        }

        $repassa = array();
        if (isset($chave_rh134_sequencial)) {
          $repassa = array("chave_rh134_sequencial" => $chave_rh134_sequencial, "chave_rh134_sequencial" => $chave_rh134_sequencial);
        }

        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $result = $cleconsigmovimentoservidor->sql_record($cleconsigmovimentoservidor->sql_query($pesquisa_chave));
          if ($cleconsigmovimentoservidor->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$rh134_sequencial', false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
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
<?php if (!isset($pesquisa_chave)) { ?>
  <script>
  </script>
<?php } ?>
<script>
  js_tabulacaoforms("form2", "chave_rh134_sequencial", true, 1, "chave_rh134_sequencial", true);
</script>