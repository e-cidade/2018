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
require_once("classes/db_propricemit_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clpropricemit = new cl_propricemit;
$clrotulo      = new rotulocampo;

$clpropricemit->rotulo->label();
$clrotulo->label("z01_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <table>
      <tr>
        <td height="63" align="center" valign="top">
            <table width="35%" border="0" align="center" cellspacing="0">
              <form name="form2" method="post" action="" >
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Tcm28_i_codigo?>">
                  <?=$Lcm28_i_codigo?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?
                     db_input("cm28_i_codigo",10,$Icm28_i_codigo,true,"text",4,"","chave_cm28_i_codigo");
                     ?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Tcm28_i_proprietario?>">
                  <?=$Lcm28_i_proprietario?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?
                     db_input("cm28_i_proprietario",10,$Icm28_i_proprietario,true,"text",4,"","chave_cm28_i_proprietario");
                     db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar" type="reset" id="limpar" value="Limpar" >
                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_propricemit.hide();">
                 </td>
              </tr>
            </form>
            </table>
          </td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?
          if(!isset($pesquisa_chave)){
            if(isset($campos)==false){
               if(file_exists("funcoes/db_func_propricemit.php")==true){
                 include("funcoes/db_func_propricemit.php");
               }else{
               $campos = "propricemit.*";
               }
            }
            if(isset($chave_cm28_i_codigo) && (trim($chave_cm28_i_codigo)!="") ){
                  $sql = $clpropricemit->sql_query($chave_cm28_i_codigo,$campos,"cm28_i_codigo");
            }else if(isset($chave_cm28_i_proprietario) && (trim($chave_cm28_i_proprietario)!="") ){
                  $sql = $clpropricemit->sql_query("",$campos,"cm28_i_codigo"," cm28_i_proprietario =$chave_cm28_i_proprietario ");
            }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
                  $sql = $clpropricemit->sql_query("",$campos,"cm28_i_codigo"," cgmcemit.z01_nome like '$chave_z01_nome%' ");
            }else{
               $sql = $clpropricemit->sql_query("",$campos,"cm28_i_codigo","");
            }

            $repassa = array();
            if(isset($chave_cm28_i_codigo)){
              $repassa = array("chave_cm28_i_codigo"=>$chave_cm28_i_codigo,"chave_cm28_i_proprietario"=>$chave_cm28_i_proprietario);
            }

            db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
          }else{

            if($pesquisa_chave!=null && $pesquisa_chave!=""){

              $result = $clpropricemit->sql_record($clpropricemit->sql_query($pesquisa_chave));
              if($clpropricemit->numrows!=0){

                db_fieldsmemory($result,0);
                echo "<script>".$funcao_js."('$cm28_i_codigo',false,'$z01_nome','$cm28_i_ossoariojazigo');</script>";
              }else{
                  echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            }else{
                echo "<script>".$funcao_js."('',false);</script>";
            }
          }
          ?>
         </td>
       </tr>
    </table>
  </div>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_cm28_i_proprietario",true,1,"chave_cm28_i_proprietario",true);
</script>