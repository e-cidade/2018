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
include("classes/db_benslote_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbenslote = new cl_benslote;
$clbenslote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t42_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tt43_codlote?>">
              <?=$Lt43_codlote?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("t43_codlote",8,$It43_codlote,true,"text",4,"","chave_t43_codlote");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tt42_descr?>">
              <?=$Lt42_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("t42_descr",40,$It42_descr,true,"text",4,"","chave_t42_descr");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_benslote.hide();">
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
           if(file_exists("funcoes/db_func_benslote.php")==true){
             include("funcoes/db_func_benslote.php");
           }else{
           $campos = "benslote.*";
           }
        }
        $campos="distinct t42_codigo,t42_descr,t52_codcla,t64_descr,t52_numcgm,z01_nome,t52_valaqu,t52_dtaqu,t52_descr,t52_obs,t52_depart,descrdepto,bens.t52_bem as db_t52_bem";
        if(isset($chave_t43_codlote) && (trim($chave_t43_codlote)!="") ){
	         $sql = $clbenslote->sql_query(null,$campos,"t42_codigo","t43_codlote=$chave_t43_codlote and t52_instit = " . db_getsession("DB_instit"));
        }else if(isset($chave_t42_descr) && (trim($chave_t42_descr)!="") ){
	         $sql = $clbenslote->sql_query("",$campos,"t42_codigo"," t42_descr like '%$chave_t42_descr%' and t52_instit = " . db_getsession("DB_instit"));
        }else{
           $sql = $clbenslote->sql_query("",$campos,"t42_codigo","t52_instit = " . db_getsession("DB_instit"));
        }
        $repassa = array();
        if(isset($chave_t43_codlote)){
          $repassa = array("chave_t43_codlote"=>$chave_t43_codlote,"chave_t42_descr"=>$chave_t42_descr);
        }
		  $repassa = array();
	//echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js,null,"NoMe",$repassa,false);
        //db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clbenslote->sql_record($clbenslote->sql_query($pesquisa_chave));
          if($clbenslote->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$t43_codigo',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_t43_codlote",true,1,"chave_t43_codlote",true);
</script>