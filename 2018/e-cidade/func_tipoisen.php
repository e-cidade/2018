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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_tipoisen_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if (!isset($chave_j45_tipo)){
  $chave_j45_tipo = '';
}

$chave_j45_descr = isset($chave_j45_descr) ? stripslashes($chave_j45_descr) : '';

if ( isset($chave_j45_tipo) && !DBNumber::isInteger($chave_j45_tipo) ) {
  $chave_j45_tipo = '';
}

$cltipoisen = new cl_tipoisen;
$cltipoisen->rotulo->label("j45_tipo");
$cltipoisen->rotulo->label("j45_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tj45_tipo?>">
              <?=$Lj45_tipo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	       db_input("j45_tipo",4,$Ij45_tipo,true,"text",4,"","chave_j45_tipo");
	       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tj45_descr?>">
              <?=$Lj45_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	       db_input("j45_descr",40,$Ij45_descr,true,"text",4,"","chave_j45_descr");
	       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tipoisen.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $chave_j45_descr = addslashes($chave_j45_descr);

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_tipoisen.php")==true){
             include(modification("funcoes/db_func_tipoisen.php"));
           }else{
           $campos = "tipoisen.*";
           }
        }

        if( isset($chave_j45_tipo) ){
          if (  !DBNumber::isInteger($chave_j45_tipo) ) {
            $chave_j45_tipo = '';
          }
        }

        if(isset($chave_j45_tipo) && (trim($chave_j45_tipo)!="" && DBNumber::isInteger($chave_j45_tipo)) ){
	         $sql = $cltipoisen->sql_query($chave_j45_tipo,$campos,"j45_tipo");
        }else if(isset($chave_j45_descr) && (trim($chave_j45_descr)!="") ){
	         $sql = $cltipoisen->sql_query("",$campos,"j45_descr"," j45_descr like '$chave_j45_descr%' ");
        }else{
           $sql = $cltipoisen->sql_query("",$campos,"j45_tipo","");
        }
        $repassa = array();
        if(isset($chave_j45_descr)){
          $repassa = array("chave_j45_tipo"=>$chave_j45_tipo,"chave_j45_descr"=>$chave_j45_descr);
        }

        if( isset($chave_j45_descr) ){
          $chave_j45_descr = str_replace("\\", "", $chave_j45_descr);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cltipoisen->sql_record($cltipoisen->sql_query($pesquisa_chave));
          if($cltipoisen->numrows!=0){
             db_fieldsmemory($result,0);
             echo "<script>".$funcao_js."('$j45_descr',$j45_tipis,false, '$j45_taxas');</script>";
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
    (function(){

      if( document.getElementById('chave_j45_tipo').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('chave_j45_tipo').value ) ) {
          alert('Campo Código deve ser preenchido somente com números!');
          document.getElementById('chave_j45_tipo').value = '';
          return false;
        }
      }

    })();
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_j45_descr",true,1,"chave_j45_descr",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
