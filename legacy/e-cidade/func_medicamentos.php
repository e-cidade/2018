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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_medicamentos_classe.php");
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmedicamentos = new cl_medicamentos;
$clmedicamentos->rotulo->label("fa58_codigo");
$clmedicamentos->rotulo->label("fa58_descricao");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label for="chave_fa58_codigo"><?=$Lfa58_codigo?></label></td>
          <td>
            <?php db_input("fa58_codigo",10, $Ifa58_codigo, true, "text", 4, "", "chave_fa58_codigo"); ?>
          </td>
        </tr>
        <tr>
          <td><label for="chave_fa58_descricao"><?=$Lfa58_descricao?></label></td>
          <td>
            <?php db_input("fa58_descricao", 30, $Ifa58_descricao, true, "text", 1, "", "chave_fa58_descricao");?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_medicamentos.hide();">
  </form>
      <?php
      $aWhere   = array();
      $aWhere[] = " not exists( select 1 from far_matersaude where fa01_medicamentos = fa58_codigo ) ";
      if (!isset($pesquisa_chave)) {

        if (isset($campos)==false) {
          if(file_exists("funcoes/db_func_medicamentos.php")==true){
            include("funcoes/db_func_medicamentos.php");
          }else{
            $campos = "medicamentos.*";
          }
        }
        if(isset($chave_fa58_codigo) && (trim($chave_fa58_codigo)!="") ) {
          $aWhere[] = "fa58_codigo = {$chave_fa58_codigo} ";
        }else if(isset($chave_fa58_descricao) && (trim($chave_fa58_descricao)!="") ){
          $aWhere[] = " fa58_descricao like '{$chave_fa58_descricao}%' ";
        }

        $sWhere   = implode(" and ", $aWhere);
        $sql      = $clmedicamentos->sql_query("", $campos, "fa58_descricao", $sWhere);
        $repassa  = array();
        if(isset($chave_fa58_descricao)){
          $repassa = array("chave_fa58_codigo"=>$chave_fa58_codigo,"chave_fa58_descricao"=>$chave_fa58_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $aWhere[] = "fa58_codigo = {$pesquisa_chave} ";
          $sWhere   = implode(" and ", $aWhere);
          $result   = $clmedicamentos->sql_record($clmedicamentos->sql_query(null, "*", null, $sWhere));
          if ($clmedicamentos->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$fa58_descricao',false);</script>";
          } else {
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
</body>
</html>