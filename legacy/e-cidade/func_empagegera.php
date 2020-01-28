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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empagegera_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempagegera = new cl_empagegera;
$clrotulo     = new rotulocampo;
$clempagegera ->rotulo -> label();
$clrotulo     ->label("z01_nome");
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
<td width="4%" align="right" nowrap title="<?=$Te87_codgera?>">
<?=$Le87_codgera?>
</td>
<td width="96%" align="left" nowrap>
<?
db_input("e87_codgera",6,$Ie87_codgera,true,"text",4,"","chave_e87_codgera");
?>
</td>
<td width="100%" align="right" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
<?=$Lz01_nome?>
</td>
<td width="96%" align="left" nowrap>
<?
db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
?>
</td>
</tr>
<tr>
<td width="4%" align="right" nowrap title="<?=$Te87_descgera?>">
<?=$Le87_descgera?>
</td>
<td width="96%" align="left" nowrap>
<?
db_input("e87_descgera",40,$Ie87_descgera,true,"text",4,"","chave_e87_descgera");
?>
</td>
<td width="100%" align="right" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td width="100%" colspan="2" align="right" nowrap title="<?=$Te87_data?>">
<strong>Período de </strong>
<?
db_inputdata('e87_data',@$e87_data_dia,@$e87_data_mes,@$e87_data_ano,true,'text',4,"","e87_dataini");
?>
<b> a </b>
<?
db_inputdata('e87_data',@$e87_data_dia,@$e87_data_mes,@$e87_data_ano,true,'text',4,"","e87_datafim");
?>
</td>
</tr>
<!-- [Inicio plugin GeracaoArquivoOBN - parte1] -->
<!-- [Fim plugin GeracaoArquivoOBN - parte1] -->
<tr>
<td colspan="5" align="center">
<input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
<input name="limpar" type="reset" id="limpar" value="Limpar" >
<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empagegera.hide();">
</td>
</tr>
</form>
</table>
</td>
</tr>
<tr>
<td align="center" valign="top">
<?
$where = " e91_codcheque is null and e80_instit = " . db_getsession("DB_instit");

/* [Inicio plugin GeracaoArquivoOBN - parte2] */
/* [Fim plugin GeracaoArquivoOBN - parte2] */

$sVerificarCancelado = 'false';
if (isset($lCancelado) && $lCancelado == '1') {
 $sVerificarCancelado = 'true';
}
$where  .= " and e90_cancelado is {$sVerificarCancelado} ";

if(isset($processado)) {
  $where .= " and e75_codret is null  ";
}

if (!empty($filtrocnab)) {
  $where .= " and not exists (select * from empagegeraobn where e138_empagegera = e87_codgera) ";
}

if (!empty($lFiltroOBN)) {
  $where .= " and exists (select * from empagegeraobn where e138_empagegera = e87_codgera) ";
}

if (!empty($lFiltroPagFor)) {
  $where .= " and exists (select * from pagfornumeracao where o152_empagegera = e87_codgera) ";
}

if(!isset($pesquisa_chave)){

  if (isset($campos) == false) {
    if (file_exists("funcoes/db_func_empagegera.php") == true) {
      include(modification("funcoes/db_func_empagegera.php"));
    } else {
      $campos = "empagegera.*";
    }
  }

  $campos  = " distinct ".$campos;

  if(isset($chave_e87_codgera) && (trim($chave_e87_codgera)!="") ){
    $sql = $clempagegera->sql_query_inner(null,$campos,"e87_codgera desc"," e87_codgera=$chave_e87_codgera and $where ");
  }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
    $sql = $clempagegera->sql_query_inner(null,$campos,"e87_codgera desc "," z01_nome like '$chave_z01_nome%' and $where ");
  }else if(isset($chave_e87_descgera) && (trim($chave_e87_descgera)!="") ){
    $sql = $clempagegera->sql_query_inner(null,$campos,"e87_codgera desc"," e87_descgera like '$chave_e87_descgera%' and $where ");
  }else if(((isset($e87_dataini_dia) && (trim($e87_dataini_dia)!="")) && (isset($e87_dataini_mes) && (trim($e87_dataini_mes)!="")) && (isset($e87_dataini_ano) && (trim($e87_dataini_ano)!=""))) || ((isset($e87_datafim_dia) && (trim($e87_datafim_dia)!="")) && (isset($e87_datafim_mes) && (trim($e87_datafim_mes)!="")) && (isset($e87_datafim_ano) && (trim($e87_datafim_ano)!="")))){
    $e87_dataini = "null";
    $e87_datafim = "null";
    if((isset($e87_dataini_dia) && (trim($e87_dataini_dia)!="")) && (isset($e87_dataini_mes) && (trim($e87_dataini_mes)!="")) && (isset($e87_dataini_ano) && (trim($e87_dataini_ano)!=""))){
      $e87_dataini = $e87_dataini_ano.'-'.$e87_dataini_mes.'-'.$e87_dataini_dia;
    }
    if((isset($e87_datafim_dia) && (trim($e87_datafim_dia)!="")) && (isset($e87_datafim_mes) && (trim($e87_datafim_mes)!="")) && (isset($e87_datafim_ano) && (trim($e87_datafim_ano)!=""))){
      $e87_datafim = $e87_datafim_ano.'-'.$e87_datafim_mes.'-'.$e87_datafim_dia;
    }
    if($e87_dataini!="null" & $e87_datafim!="null"){
      $where = " e87_data between '$e87_dataini' and '$e87_datafim' and $where ";
    }else if($e87_dataini!="null"){
      $where = " e87_data >= '$e87_dataini' and $where ";
    }else if($e87_datafim!="null"){
      $where = " e87_data <= '$e87_datafim' and $where ";
    }

    if (isset($lRetorno) && $lRetorno == '1') {

      $where .= " and empagedadosret.e75_codgera is not null ";
    }

    $sql = $clempagegera->sql_query_inner(null,$campos,"e87_codgera desc"," $where ");
  }else{

    if (isset($lRetorno) && $lRetorno == '1') {

      $where .= " and empagedadosret.e75_codgera is not null ";
    }
    $sql = $clempagegera->sql_query_inner(null,$campos,"e87_codgera desc"," $where");
  }
  //die($sql);
  db_lovrot($sql,15,"()","",$funcao_js);
} else {
  if ($pesquisa_chave!=null && $pesquisa_chave!="") {

    if (isset($lRetorno) && $lRetorno == '1') {

      $where .= " and empagedadosret.e75_codgera is not null ";
    }
    $result = $clempagegera->sql_record($clempagegera->sql_query_inner(null,"*","","e87_codgera=$pesquisa_chave and $where"));
    if($clempagegera->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$e87_descgera',false);</script>";
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
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
