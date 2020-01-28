<?php

/**
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhfuncao_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhfuncao = new cl_rhfuncao();
$clrhfuncao->rotulo->label("rh37_funcao"); 
$clrhfuncao->rotulo->label("rh37_instit"); 
$clrhfuncao->rotulo->label("rh37_descr");

$iInstit = db_getsession("DB_instit");
if (isset($chave_rh37_funcao) && !DBNumber::isInteger($chave_rh37_funcao)) {
  $chave_rh37_funcao = '';
}

if (isset($chave_rh37_instit) && !DBNumber::isInteger($chave_rh37_instit)) {
  $chave_rh37_instit = '';
}

$chave_rh37_descr = isset($chave_rh37_descr) ? stripslashes($chave_rh37_descr) : '';

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <style>
  #chave_rh37_instit,
  #chave_rh37_funcao {
    width: 59px;
  }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
        <fieldset style="width: 35%">
          <legend>Pesquisa de Cargo</legend>
          <table width="35%" border="0" align="center" cellspacing="0">
            <tr>
              <td width="4%" align="left" nowrap title="<?php echo $Trh37_funcao; ?>">
                <?php echo $Lrh37_funcao; ?>
              </td>
              <td width="96%" align="left" nowrap> 
                <?php
  		            db_input("rh37_funcao", 5, $Irh37_funcao, true, "text", 4, "", "chave_rh37_funcao");
  		          ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?php echo $Trh37_instit; ?>">
                <?php echo $Lrh37_instit; ?>
              </td>
              <td width="96%" align="left" nowrap> 
                <?php
  		            db_input("rh37_instit", 2, $Irh37_instit, true, "text", 4, "", "chave_rh37_instit");
  		          ?>
              </td>
            </tr>
            <tr> 
              <td width="4%" align="left" nowrap title="<?php echo $Trh37_descr; ?>">
                <?php echo $Lrh37_descr; ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?php
                  db_input("rh37_descr", 30, $Irh37_descr, true, "text", 4, "", "chave_rh37_descr");
  		          ?>
              </td>
            </tr>
            <tr> 
              <td width="4%" align="left" nowrap title="">
                <b>Opções:</b>
              </td>
              <td width="96%" align="left" nowrap> 
              <?
                $aOpcao = array("at"=>"Ativos","ds"=>"Desativados" ,"am"=>"Ambos");
                db_select('sOpcao',$aOpcao,true,4,"");
              ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr>
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onclick="return js_valida(arguments[0])"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhfuncao.hide();">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <fieldset>
        <legend>Resultado da Pesquisa</legend>
      <?php

      $dbwhere = "rh37_instit = ".db_getsession("DB_instit");
    
      $chave_rh37_descr = addslashes($chave_rh37_descr);

      if (!isset($pesquisa_chave)) {

        if (!isset($campos)) {

          if (file_exists("funcoes/db_func_rhfuncao.php")) {
            include("funcoes/db_func_rhfuncao.php");
          } else {
            $campos = "rhfuncao.*";
          }
        }

        if (isset($sOpcao) && !empty($sOpcao)) {
          if ($sOpcao == 'at') {
          	$dbwhere .= " and rh37_ativo is true";
          }	else if ($sOpcao == 'ds') {
          	$dbwhere .= " and rh37_ativo is false";
          }
        } else {
          $dbwhere .= " and rh37_ativo is true";
        }

        if (isset($chave_rh37_funcao) && !empty($chave_rh37_funcao)) {
        	$dbwhere .= " and rh37_funcao = {$chave_rh37_funcao}";
        }

        if (isset($chave_rh37_descr) && !empty($chave_rh37_descr)) {
        	$dbwhere .= " and rh37_descr like '$chave_rh37_descr%'";
        }

        if(isset($chave_rh37_funcao) && (trim($chave_rh37_funcao)!="") ){
	         $sql = $clrhfuncao->sql_query($chave_rh37_funcao,$iInstit,$campos,"rh37_funcao","{$dbwhere}");
        }else if(isset($chave_rh37_descr) && (trim($chave_rh37_descr)!="") ){
	         $sql = $clrhfuncao->sql_query($chave_rh37_descr,$iInstit,$campos,"rh37_descr","{$dbwhere}");
        }else{
           $sql = $clrhfuncao->sql_query("",$iInstit,$campos,"rh37_funcao","{$dbwhere}");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clrhfuncao->sql_record($clrhfuncao->sql_query($pesquisa_chave,$iInstit,"rh37_descr",null));
          if ($clrhfuncao->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$rh37_descr', false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }

        } else {
	        echo "<script>".$funcao_js."('',false);</script>";
        }
      }

      ?>
      </fieldset>
    </td>
  </tr>
</table>
</body>
</html>
<?php if (!isset($pesquisa_chave)) { ?>
  <script>

    function js_valida(event) {
      document.getElementById('chave_rh37_funcao').onkeyup = event;
      document.getElementById('chave_rh37_instit').onkeyup = event; 
      return true;
    }

  </script>
<?php } ?>
<script>
  js_tabulacaoforms("form2", "chave_rh37_descr", true, 1, "chave_rh37_descr", true);
</script>