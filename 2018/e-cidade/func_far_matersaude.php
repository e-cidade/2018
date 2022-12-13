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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_far_matersaude_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfar_matersaude = new cl_far_matersaude;
$clmatmater       = new cl_matmater ( );
$clfar_matersaude->rotulo->label("fa01_i_codigo");
$clfar_matersaude->rotulo->label("fa01_codigobarras");
$clmatmater->rotulo->label("m60_descr");
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
      <table width="45%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label><?=$Lfa01_i_codigo?></label></td>
          <td><? db_input("fa01_i_codigo",5,$Ifa01_i_codigo,true,"text",4,"","chave_fa01_i_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lm60_descr?></label></td>
          <td width="96%" align="left" nowrap>
            <?php
            db_input ( "m60_descr", 40, @$Im60_descr, true, "text", 4, "", "chave_m60_descr" );
            ?>
          </td>
        </tr>
        <tr>
          <td><label><?=$Lfa01_codigobarras?></label></td>
          <td><? db_input("fa01_codigobarras",20,$Ifa01_codigobarras,true,"text",4,"","chave_fa01_codigobarras");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_far_matersaude.hide();">
  </form>
      <?php

      if (!isset($pesquisa_chave) && !isset($codigo_barras))  {

        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_far_matersaude.php")==true){
             include("funcoes/db_func_far_matersaude.php");
           }else{
           $campos = "far_matersaude.*";
           }
        }

        if(isset($ativo)){
          $ativo="";
          $sep="";
        }else{
            $ativo=" m60_ativo='t' ";
          $sep=" and ";
        }

        if (isset ( $chave_fa01_i_codigo ) && (trim ( $chave_fa01_i_codigo ) != "")) {
          if(isset($prescricao)){
             $sql = $clfar_matersaude->sql_query_tipo (null, $campos, "fa01_i_codigo" , " fa01_i_codigo=$chave_fa01_i_codigo and fa20_i_codigo=$prescricao $sep $ativo");
          }else{
             $sql = $clfar_matersaude->sql_query (null, $campos, "fa01_i_codigo","fa01_i_codigo=$chave_fa01_i_codigo $sep $ativo");
          }
        } else if (isset ( $chave_m60_descr ) && (trim ( $chave_m60_descr ) != "")) {
          if(isset($prescricao)){
             $sql = $clfar_matersaude->sql_query_tipo ( "", $campos, "fa01_i_codigo", " m60_descr like '$chave_m60_descr%' and fa20_i_codigo=$prescricao $sep $ativo" );
          }else{
             $sql = $clfar_matersaude->sql_query ( "", $campos, "fa01_i_codigo", " m60_descr like '$chave_m60_descr%' $sep $ativo " );
          }
        } else if(isset($chave_fa01_codigobarras) && (trim($chave_fa01_codigobarras)!="") ) {
           $sql = $clfar_matersaude->sql_query("",$campos,"fa01_i_codigo"," fa01_codigobarras = '$chave_fa01_codigobarras' ");
        } else {
          if(isset($prescricao)){
              $sql = $clfar_matersaude->sql_query_tipo ( "", $campos, "fa01_i_codigo", " fa20_i_codigo=$prescricao $sep $ativo " );
          }else{
              $sql = $clfar_matersaude->sql_query ( "", $campos, "fa01_i_codigo","$ativo" );
          }
        }

        $repassa = array();
        if(isset($chave_fa01_i_codigo)){
          $repassa = array("chave_fa01_i_codigo"=>$chave_fa01_i_codigo,"chave_fa01_i_codigo"=>$chave_fa01_i_codigo);
        }else if(isset($chave_fa01_codigobarras)) {
          $repassa = array("chave_fa01_codigobarras"=>$chave_fa01_codigobarras,"chave_fa01_codigobarras"=>$chave_fa01_codigobarras);
        }

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';

      } else {

        if ( !empty( $pesquisa_chave ) || !empty( $codigo_barras ) ) {

          $sChaveInvalida = '';
          $sWhere         = "";
          if ( !empty( $pesquisa_chave ) ) {

            $sWhere         = " fa01_i_codigo = {$pesquisa_chave} ";
            $sChaveInvalida = $pesquisa_chave;
          }
          if ( !empty( $codigo_barras ) ) {

            $sChaveInvalida = $codigo_barras;
            $sWhere         = " fa01_codigobarras = '{$codigo_barras}' ";
          }

          $campos = " far_matersaude.fa01_i_codigo, matmater.m60_descr, far_matersaude.fa01_codigobarras ";
          $sql    = $clfar_matersaude->sql_query("", $campos, "fa01_i_codigo", $sWhere);
          $result = $clfar_matersaude->sql_record($sql);
          if($clfar_matersaude->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$fa01_i_codigo', '$m60_descr', false, '$fa01_codigobarras' );</script>";
          }else{
	         echo "<script>".$funcao_js."('','Chave(".$sChaveInvalida.") não Encontrado',true);</script>";
          }
        } else {
         echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
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
<script>
js_tabulacaoforms("form2","chave_fa01_i_codigo",true,1,"chave_fa01_i_codigo",true);
</script>