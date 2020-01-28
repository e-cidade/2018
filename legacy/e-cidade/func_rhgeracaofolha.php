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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhgeracaofolha_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhgeracaofolha = new cl_rhgeracaofolha();
$clrhgeracaofolha->rotulo->label("rh102_sequencial"); 
$clrhgeracaofolha->rotulo->label("rh102_descricao");

if ( isset($chave_rh102_sequencial) && !DBNumber::isInteger($chave_rh102_sequencial) ) {
  $chave_rh102_sequencial = '';
}

$chave_rh102_descricao = isset($chave_rh102_descricao) ? stripslashes($chave_rh102_descricao) : '';

$iInstituicao = db_getsession('DB_instit');
$sWhereGeracoesAtivas = '';
if( !empty($ativas) ){
	$sWhereGeracoesAtivas = " rh102_ativo = true and rh102_instit = {$iInstituicao}";
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
            <td width="4%" align="right" nowrap title="<?php echo $Trh102_sequencial; ?>">
              <?php echo $Lrh102_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
                db_input("rh102_sequencial", 10, $Irh102_sequencial, true, "text", 4, "", "chave_rh102_sequencial");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh102_descricao; ?>">
              <?php echo $Lrh102_descricao; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_input("rh102_descricao", 10, $Irh102_descricao, true, "text", 4, "", "chave_rh102_descricao");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhgeracaofolha.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top"> 
      <?php

      $chave_rh102_descricao = addslashes($chave_rh102_descricao);
      
      if (!isset($pesquisa_chave)) {

        if (!isset($campos)) {

          if (file_exists("funcoes/db_func_rhgeracaofolha.php")) {
            include("funcoes/db_func_rhgeracaofolha.php");
          } else {
            $campos = "rhgeracaofolha.*";
          }
        }

        if( isset($chave_rh102_sequencial) ){
          if (  !DBNumber::isInteger($chave_rh102_sequencial) ) {
            $chave_rh102_sequencial = '';
          }
        }

        if (isset($chave_rh102_sequencial) && (trim($chave_rh102_sequencial) != "" && DBNumber::isInteger($chave_rh102_sequencial) ) ){
           $sql = $clrhgeracaofolha->sql_query_rhgeracaofolha($chave_rh102_sequencial,$campos, "rh102_sequencial", $sWhereGeracoesAtivas);
        } else if (isset($chave_rh102_descricao) && (trim($chave_rh102_descricao)!="") ){
          $sql = $clrhgeracaofolha->sql_query_rhgeracaofolha("", $campos, "rh102_descricao"," rh102_descricao like '$chave_rh102_descricao%' and $sWhereGeracoesAtivas ");
        } else {
           $sql = $clrhgeracaofolha->sql_query_rhgeracaofolha("", $campos, "rh102_sequencial", $sWhereGeracoesAtivas);
        }
        
        if( isset($chave_rh102_descricao) ){
          $chave_rh102_descricao = str_replace("\\", "", $chave_rh102_descricao);
        }

        $repassa = array();
        if (isset($chave_rh102_sequencial)) {
          $repassa = array("chave_rh102_sequencial" => $chave_rh102_sequencial, "chave_rh102_descricao" => $chave_rh102_descricao);
        }
        
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {
        	
          $result = $clrhgeracaofolha->sql_record($clrhgeracaofolha->sql_query('','*','rh102_sequencial, rh102_descricao',"rh102_sequencial = $pesquisa_chave and $sWhereGeracoesAtivas"));
          if ($clrhgeracaofolha->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."(false,'$rh102_sequencial', '$rh102_descricao');</script>";
          } else {
            echo "<script>".$funcao_js."(true,'Chave(".$pesquisa_chave.") não Encontrado');</script>";
          }

        } else {
          echo "<script>".$funcao_js."(false,'');</script>";
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
   (function(){
      
      if( document.getElementById('$chave_rh102_sequencial').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('$chave_rh102_sequencial').value ) ) {
          alert('Código Geração em Disco deve ser preenchido somente com números!');
          document.getElementById('$chave_rh102_sequencial').value = '';
          return false;  
        }
      }
      
    })();
  </script>
<?php } ?>
<script>
  js_tabulacaoforms("form2", "chave_rh102_sequencial", true, 1, "chave_rh102_sequencial", true);
</script>
