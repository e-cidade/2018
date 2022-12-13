<?
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
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_censoetapa_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcensoetapa = new cl_censoetapa;
$clcensoetapa->rotulo->label("ed266_i_codigo");
$clcensoetapa->rotulo->label("ed266_ano");
$clcensoetapa->rotulo->label("ed266_c_descr");
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
          <td><label><?=$Led266_i_codigo?></label></td>
          <td><? db_input("ed266_i_codigo",10,$Ied266_i_codigo,true,"text",4,"","chave_ed266_i_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Led266_ano?></label></td>
          <td><? db_input("ed266_ano",10,$Ied266_ano,true,"text",4,"","chave_ed266_ano"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Led266_c_descr?></label></td>
          <td><? db_input("ed266_c_descr",40,$Ied266_c_descr,true,"text",4,"","chave_ed266_c_descr");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_censoetapa.hide();">
  </form>
      <?php

        if ($modalidade == "ER") {
          $where = " ed266_c_regular = 'S' ";
        } else if ($modalidade == "ES") {
          $where = " ed266_c_especial = 'S' ";
        } else if ($modalidade == "EJ") {
          $where = " ed266_c_eja = 'S' ";
        } else if ($modalidade == "EP") {
          $where = " ed266_i_codigo in (39, 40, 64) ";
        }

        if(!isset($pesquisa_chave)){
          if(isset($campos)==false){
            if(file_exists("funcoes/db_func_censoetapa.php")==true){
              include("funcoes/db_func_censoetapa.php");
            }else{
            $campos = "censoetapa.*";
          }
        }

        if(isset($chave_ed266_i_codigo) && (trim($chave_ed266_i_codigo)!="") ){
          $sql = $clcensoetapa->sql_query("","", $campos,"ed266_i_codigo",$where." AND ed266_i_codigo not in (3,12,13,22,23,24,51,56,58) AND ed266_i_codigo = $chave_ed266_i_codigo");
        }else if(isset($chave_ed266_c_descr) && (trim($chave_ed266_c_descr)!="") ){
          $sql = $clcensoetapa->sql_query("","", $campos,"ed266_i_codigo",$where." AND ed266_i_codigo not in (3,12,13,22,23,24,51,56,58) AND ed266_c_descr like '$chave_ed266_c_descr%' ");
        }else{
          $sql = $clcensoetapa->sql_query("","", $campos,"ed266_i_codigo",$where." AND ed266_i_codigo not in (3,12,13,22,23,24,51,56,58)");
        }


        $repassa = array();
        if(isset($chave_ed266_i_codigo)){
          $repassa = array("chave_ed266_i_codigo"=>$chave_ed266_i_codigo,"chave_ed266_c_descr"=>$chave_ed266_c_descr);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcensoetapa->sql_record($clcensoetapa->sql_query($pesquisa_chave));
          if($clcensoetapa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed266_c_descr',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
</body>
</html>

<script>
js_tabulacaoforms("form2","chave_ed266_c_descr",true,1,"chave_ed266_c_descr",true);
</script>
