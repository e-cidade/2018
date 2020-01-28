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
include("classes/db_conplanoexe_classe.php");
include("classes/db_conplano_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clconplanoexe = new cl_conplanoexe;
$clconplano = new cl_conplano;
$clconplanoexe->rotulo->label("c62_anousu");
$clconplanoexe->rotulo->label("c62_reduz");
$clconplanoexe->rotulo->label("c62_codrec");
$clconplano->rotulo->label("c60_descr");
$clconplano->rotulo->label("c60_estrut");

$anousu = db_getsession("DB_anousu");
if (isset($db_instit) && $db_instit!=''){
  $instit = $db_instit;
} else {
  $instit = db_getsession("DB_instit");
}
?>
  <html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form2.chave_c62_reduz.focus();'>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="" >
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Tc62_reduz?>">
                <?=$Lc62_reduz?>
              </td>
              <td width="96%" align="left" nowrap>
                <? db_input("c62_reduz",6,$Ic62_reduz,true,"text",4,"","chave_c62_reduz"); ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Tc60_estrut?>">
                <?=$Lc60_estrut?>
              </td>
              <td width="96%" align="left" nowrap>
                <? db_input("c60_estrut",15,$Ic60_estrut,true,"text",4,"","chave_c60_estrut"); ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Tc60_descr?>">
                <?=$Lc60_descr?>&nbsp;
              </td>
              <td width="96%" align="left" nowrap>
                <?  db_input("c60_descr",40,$Ic60_descr,true,"text",4,"","chave_c60_descr");   ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar" >
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplanoexe.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?
        $campos = "c62_reduz,c60_estrut,c60_descr ";
        if(!isset($pesquisa_chave)){
          if(isset($campos)==false){
            if(file_exists("funcoes/db_func_conplanoexe.php")==true){
              include("funcoes/db_func_conplanoexe.php");
            }else{
              $campos = "conplanoexe.*";
            }
          }
          if(isset($chave_c62_reduz) && (trim($chave_c62_reduz)!="") ){
            $sql = $clconplanoexe->sql_descr("","",$campos,"c62_reduz","c62_anousu =$anousu and c62_reduz=$chave_c62_reduz and c61_instit= $instit ");
          }else if(isset($chave_c60_descr) && (trim($chave_c60_descr)!="") ){
            $sql = $clconplanoexe->sql_descr("","",$campos,"c60_descr","upper(c60_descr) like '$chave_c60_descr%' and c62_anousu=$anousu and c61_instit= $instit ");
          }else if(isset($chave_c60_estrut) && (trim($chave_c60_estrut)!="") ){
            $sql = $clconplanoexe->sql_descr("","",$campos,"c60_estrut","c60_estrut like '$chave_c60_estrut%' and c62_anousu=$anousu and c61_instit=$instit ");
          }else{
            $sql = $clconplanoexe->sql_descr("","",$campos,"c62_reduz","c62_anousu=$anousu  and c61_instit= $instit");
          }
          // echo $sql ;
          db_lovrot($sql,15,"()","",$funcao_js);


        }else{
          if($pesquisa_chave!=null && $pesquisa_chave!=""){

            $sWherePadrao = "c62_reduz=$pesquisa_chave and c62_anousu = $anousu and c61_instit= $instit ";
            if (!empty($lEstrutural) && $lEstrutural == "true") {

              $pesquisa_chave = str_pad($pesquisa_chave, 15, '0', STR_PAD_RIGHT);
              $sWherePadrao = "c60_estrut = '$pesquisa_chave' and c62_anousu = $anousu and c61_instit= $instit";
            }

            $result = $clconplanoexe->sql_record($clconplanoexe->sql_descr("","","c60_descr, c60_estrut, c62_reduz",null,$sWherePadrao));
            if($clconplanoexe->numrows!=0){

              db_fieldsmemory($result,0);
              if (!empty($lEstrutural) && $lEstrutural == "true") {
                echo "<script>".$funcao_js."('$c60_descr',false, {$c62_reduz}, true);</script>";
              } else {
                echo "<script>" . $funcao_js . "('$c60_descr',false, '{$c60_estrut}', false);</script>";
              }

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