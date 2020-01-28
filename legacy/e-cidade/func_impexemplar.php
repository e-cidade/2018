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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_impexemplar_classe.php"));
include(modification("classes/db_impexemplaritem_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$climpexemplar = new cl_impexemplar;
$climpexemplaritem = new cl_impexemplaritem;
$clrotulo = new rotulocampo;
$clrotulo->label("bi23_codigo");
$clrotulo->label("bi06_titulo");
$climpexemplar->rotulo->label("bi24_data");
$climpexemplar->rotulo->label("bi24_modelo");
$depto = db_getsession("DB_coddepto");
$sql = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result = db_query($sql);;
$linhas = pg_num_rows($result);
if($linhas!=0){
 db_fieldsmemory($result,0);
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
     <td width="4%" align="right" nowrap title="<?=$Tbi23_codigo?>">
      <?=$Lbi23_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi23_codigo",10,$Ibi23_codigo,true,"text",4,"","chave_bi23_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi06_titulo?>">
      <?=$Lbi06_titulo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi06_titulo",40,$Ibi06_titulo,true,"text",4,"","chave_bi06_titulo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi24_modelo?>">
      <?=$Lbi24_modelo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?
      $x = array(''=>'','M1'=>'MODELO 1','M2'=>'MODELO 2','M3'=>'MODELO 3', 'M4'=>'MODELO 4');
      db_select('bi24_modelo',$x,true,1,"");
      ?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi24_data?>">
      <?=$Lbi24_data?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_inputdata('bi24_data',@$bi24_data_dia,@$bi24_data_mes,@$bi24_data_ano,true,'text',1,"")?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_impexemplar.hide();">
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
     if(file_exists("funcoes/db_func_impexemplar.php")==true){
      include(modification("funcoes/db_func_impexemplar.php"));
     }else{
      $campos = "impexemplar.*";
     }
    }
    if(isset($chave_bi23_codigo) && (trim($chave_bi23_codigo)!="") ){
     $sql = $climpexemplaritem->sql_query("","DISTINCT ".$campos,"bi24_data desc,bi24_hora desc"," bi24_biblioteca = $bi17_codigo AND bi25_exemplar = $chave_bi23_codigo");
    }else if(isset($chave_bi06_titulo) && (trim($chave_bi06_titulo)!="") ){
     $sql = $climpexemplaritem->sql_query("","DISTINCT ".$campos,"bi24_data desc,bi24_hora desc"," bi24_biblioteca = $bi17_codigo AND bi06_titulo like '$chave_bi06_titulo%'");
    }else if(isset($bi24_data) && (trim($bi24_data)!="") ){
     $bi24_data = substr($bi24_data,6,4)."-".substr($bi24_data,3,2)."-".substr($bi24_data,0,2);
     $sql = $climpexemplar->sql_query("","DISTINCT ".$campos,"bi24_data desc,bi24_hora desc"," bi24_biblioteca = $bi17_codigo AND bi24_data = '$bi24_data'");
    }else if(isset($bi24_modelo) && (trim($bi24_modelo)!="") ){
     $sql = $climpexemplar->sql_query("","DISTINCT ".$campos,"bi24_data desc,bi24_hora desc"," bi24_biblioteca = $bi17_codigo AND bi24_modelo = '$bi24_modelo'");
    }else{
     $sql = $climpexemplar->sql_query("","DISTINCT ".$campos,"bi24_data desc,bi24_hora desc"," bi24_biblioteca = $bi17_codigo");
    }
    $repassa = array();
    if(isset($chave_bi23_codigo)){
     $repassa = array("chave_bi23_codigo"=>$chave_bi23_codigo,"chave_bi06_titulo"=>$chave_bi06_titulo,"bi24_modelo"=>$bi24_modelo,"bi24_data"=>$bi24_data);
    }
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $climpexemplar->sql_record($climpexemplar->sql_query("","*",""," bi24_biblioteca = $bi17_codigo AND bi24_codigo = $pesquisa_chave"));
     if($climpexemplar->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$bi24_data','$bi24_hora',false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
     }
    }else{
     echo "<script>".$funcao_js."('','',false);</script>";
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
js_tabulacaoforms("form2","chave_bi24_codigo",true,1,"chave_bi24_codigo",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
