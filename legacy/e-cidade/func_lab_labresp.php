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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("classes/db_lab_labresp_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$cllab_labresp = new cl_lab_labresp;
$cllab_labresp->rotulo->label("la06_i_codigo");
$cllab_labresp->rotulo->label("z01_nome");

$oRotulo = new rotulocampo();
$oRotulo->label("z01_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Tla06_i_codigo?>">
              <?=$Lla06_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("la06_i_codigo",10,$Ila06_i_codigo,true,"text",4,"","chave_la06_i_codigo");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
             <b>Nome:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lab_labresp.hide();">
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
           if(file_exists("funcoes/db_func_lab_labresp.php")==true){
             include(modification("funcoes/db_func_lab_labresp.php"));
           }else{
             $campos = "lab_labresp.*";
           }
           $campos = "lab_labresp.*,z01_nome";
        }
        $where="";
        $sep="";
        if(isset($la24_i_laboratorio)){
           $where=" la06_i_laboratorio=$la24_i_laboratorio ";
           $sep=" and";
        }
        if(isset($chave_la06_i_codigo) && (trim($chave_la06_i_codigo)!="") ){
	         $sql = $cllab_labresp->sql_query($chave_la06_i_codigo,$campos,"la06_i_codigo",$where);
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $cllab_labresp->sql_query("",$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' $sep$where");
        }else{
           $sql = $cllab_labresp->sql_query("",$campos,"la06_i_codigo","$where");
        }
        $repassa = array();
        if(isset($chave_la06_i_codigo)){
          $repassa = array("chave_la06_i_codigo"=>$chave_la06_i_codigo,"chave_z01_nome"=>$chave_z01_nome);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cllab_labresp->sql_record($cllab_labresp->sql_query($pesquisa_chave));
          if($cllab_labresp->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
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
js_tabulacaoforms("form2","chave_la06_i_codigo",true,1,"chave_la06_i_codigo",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
