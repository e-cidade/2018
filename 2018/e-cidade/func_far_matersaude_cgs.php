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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_far_matersaude_classe.php"));
include(modification("classes/db_far_matersaude_classe_cgs_ext.php"));
include(modification("classes/db_matmater_classe.php"));
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfar_matersaude     = new cl_far_matersaude;
$clfar_matersaude_ext = new cl_far_matersaude_ext;
$clmatmater           = new cl_matmater;
$clfar_matersaude->rotulo->label("fa01_i_codigo");
$clmatmater->rotulo->label("m60_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tfa01_i_codigo?>">
              <?=$Lfa01_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("fa01_i_codigo",5,$Ifa01_i_codigo,true,"text",4,"","chave_fa01_i_codigo");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tm60_descr?>">
              <?=$Lm60_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("m60_descr", 20, $Im60_descr,true,"text",4,"","chave_m60_descr");
		          ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_far_matersaude.hide();">
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

        if(isset($campos)==false) {

           if (file_exists("funcoes/db_func_far_matersaude.php")==true) {
             include(modification("funcoes/db_func_far_matersaude.php"));
           } else {
             $campos = "far_matersaude.*";
           }
        }
        //Campo adicional
        $campos.=",matmater.m60_descr";
        if (isset($ativo)) {

    			$ativo="";
	        $sep="";
  		  } else {

  		    $ativo=" m60_ativo='t' ";
  		  	$sep=" and ";
  		  }

        if(isset($chave_fa01_i_codigo) && (trim($chave_fa01_i_codigo)!="") ){
	        $sql = $clfar_matersaude_ext->sql_query($chave_fa01_i_codigo,$campos,"fa01_i_codmater","fa01_i_codigo=$chave_fa01_i_codigo and fa04_i_cgsund=$cgs $sep$ativo");
        }else if(isset($chave_m60_descr) && (trim($chave_m60_descr)!="") ){
	        $sql = $clfar_matersaude_ext->sql_query("",$campos,"fa01_i_codmater"," m60_descr like '$chave_m60_descr%' and fa04_i_cgsund=$cgs $sep$ativo");
        }else{
          $sql = $clfar_matersaude_ext->sql_query("",$campos,"fa01_i_codmater"," fa04_i_cgsund=$cgs $sep$ativo");
        }
        $repassa = array();
        if(isset($chave_fa01_i_codigo)){
          $repassa = array("chave_fa01_i_codigo"=>$chave_fa01_i_codigo,"chave_m60_descr"=>$chave_m60_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

      	if ($pesquisa_chave!=null && $pesquisa_chave!="") {

      	  $sql    = $clfar_matersaude_ext->sql_query($pesquisa_chave,"*","fa01_i_codmater"," fa01_i_codigo=$pesquisa_chave and fa04_i_cgsund=$cgs $sep$ativo");
      	  $result = db_query($sql);
          $linhas = pg_numrows($result);
          if ($linhas!=0) {

          	db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m60_descr',false);</script>";
          } else {
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
<script>
js_tabulacaoforms("form2","chave_fa01_i_codigo",true,1,"chave_fa01_i_codigo",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
