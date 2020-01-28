<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_matparam_classe.php");
require_once("classes/db_db_departorg_classe.php");
require_once("classes/db_db_almox_classe.php");
require_once("classes/db_db_almoxdepto_classe.php");
require_once("classes/db_matestoque_classe.php");
require_once("classes/db_matestoqueitem_classe.php");
require_once("classes/db_matmater_classe.php");
require_once("classes/materialestoque.model.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");
$clmatparam       = new cl_matparam;
$cldb_departorg   = new  cl_db_departorg;
$cldb_almox       = new cl_db_almox;
$cldb_almoxdepto  = new cl_db_almoxdepto;
$clmatestoque     = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatmater       = new cl_matmater;

$clrotulo = new rotulocampo;
$clrotulo->label("");
$clrotulo->label("");

if (isset($codmater)&&$codmater!=""){
  $result_descr=$clmatmater->sql_record($clmatmater->sql_query_file($codmater,"m60_descr as descrmater"));
  if ($clmatmater->numrows>0){
    db_fieldsmemory($result_descr,0);
  }
}

$res_db_almox = $cldb_almox->sql_record($cldb_almox->sql_query_file(null,"*",null,"m91_depto = ".db_getsession("DB_coddepto")));
if ($cldb_almox->numrows > 0){
  $flag_almox = "true";
} else {
  $flag_almox = "false";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_lancamentos(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lancamentos','mat3_matconsultaiframe002.php?codmater='+document.form1.codmater.value+'&db_where='+document.form1.where.value+'&db_inner='+document.form1.inner.value,'Consulta Lançamentos',true);
}
function js_requisicoes(){
  js_OpenJanelaIframe('top.corpo','db_iframe_requisicao','mat3_matconsultaiframe004.php?codmater='+document.form1.codmater.value+'&db_where='+document.form1.where.value+'&db_inner='+document.form1.inner.value,'Consulta Requisições',true);
}
function js_atendimentos(){
  js_OpenJanelaIframe('top.corpo','db_iframe_atendimento','mat3_matconsultaiframe005.php?codmater='+document.form1.codmater.value+'&db_where='+document.form1.where.value+'&db_inner='+document.form1.inner.value,'Consulta Atendimentos',true);
}
function js_devolucoes(){
  js_OpenJanelaIframe('top.corpo','db_iframe_devolucoes','mat3_matconsultaiframe006.php?codmater='+document.form1.codmater.value+'&db_where='+document.form1.where.value+'&db_inner='+document.form1.inner.value,'Consulta Devoluções',true);
}
function js_pontopedidos(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pontopedidos','mat3_matconsultaiframe008.php?codmater='+document.form1.codmater.value+'&db_where='+document.form1.where.value+'&db_inner='+document.form1.inner.value+"&flag_almox=<?=$flag_almox?>",'Consulta Ponto Pedido',true);
}
function js_lotes(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lote','mat3_matconsultalotes.php?codmater='+document.form1.codmater.value+'&db_where='+document.form1.where.value+'&db_inner='+document.form1.inner.value+"&flag_almox=<?=$flag_almox?>",'Consulta Movimentações lote',true);
}
</script>
<style>
.bordas{
  border: 2px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #999999;
}
.bordas_corp{
  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<fieldset style="width: 700px; margin-top: 50px;">
<legend><b>Consulta de Materiais</b></legend>

<form name='form1'  >
<table align='center'  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr>
<td align=center>
<br>
<table>
<tr>
<td><b>Material: <b></td>
<td>
<?
db_input('codmater',6,'',true,'text',3);
db_input('descrmater',40,'',true,'text',3);
?>
</td>
</tr>
<tr>
<?php
$where="";
$inner="";
$depto_atual = db_getsession("DB_coddepto");
$permissao=db_permissaomenu(db_getsession("DB_anousu"),480,4390);
if ($permissao=="false"){
  $result_param=$clmatparam->sql_record($clmatparam->sql_query_file());
  if ($clmatparam->numrows){
    db_fieldsmemory($result_param,0);
    if ($m90_tipocontrol=='S'){
      $result_orgao=$cldb_departorg->sql_record($cldb_departorg->sql_query_file($depto_atual));
      if ($cldb_departorg->numrows){
        db_fieldsmemory($result_orgao,0);
        $where = "db01_orgao = $db01_orgao";
        $inner .= "      inner join db_departorg  on db01_coddepto = db_depart.coddepto and db01_anousu=".db_getsession("DB_anousu");
        $inner .= "      inner join orcorgao  on orcorgao.o40_orgao = db_departorg.db01_orgao and orcorgao.o40_anousu = db_departorg.db01_anousu";
      }
    }else if ($m90_tipocontrol=='G'){
      $result_almox=$cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null,$depto_atual));
      if ($cldb_almoxdepto->numrows>0){
        db_fieldsmemory($result_almox,0);
        $where = "m91_codigo = $m92_codalmox";
        $inner .= "      inner join db_almoxdepto on m92_depto = db_depart.coddepto ";
        $inner .= "      inner join db_almox on m91_codigo = m92_codalmox";
      }else{
        $where = "1=2";
      }
    }else if ($m90_tipocontrol=='D'){
      $where = "D";
    }else if ($m90_tipocontrol=='F'){
      $result_almox=$cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null,$depto_atual));
      if ($cldb_almoxdepto->numrows>0){
        db_fieldsmemory($result_almox, 0);
        $where = "m91_codigo = $m92_codalmox";
        $inner .= "      inner join db_almoxdepto on m92_depto = db_depart.coddepto ";
        $inner .= "      inner join db_almox on m91_codigo = m92_codalmox";
      }else{
        $where = "1=2";
      }
    }

  }

}

$oMaterialEstoque = new materialEstoque($codmater);
$pr_medio = $oMaterialEstoque->getPrecoMedioMaterial();

$sCamposEstoque  = "coalesce(sum(m70_quant), 0) as quantidadeestoque ";
$sWhereEstoque   = " matestoque.m70_codmatmater = {$codmater}";

$sSQlEstoque             = $clmatestoque->sql_query_almox(null, $sCamposEstoque, null, $sWhereEstoque, "", true);
$quantidadetransferencia = $oMaterialEstoque->getSaldoTransferencia();

$result_matestoque=$clmatestoque->sql_record($sSQlEstoque);
if ($clmatestoque->numrows>0){
  db_fieldsmemory($result_matestoque,0);
}else{
  $where = "1=2";
}
$result_estoque_teste =$clmatestoque->sql_record($clmatestoque->sql_query_almox(null,"distinct m70_coddepto,descrdepto,m70_quant,m70_valor",null,"m70_codmatmater=$codmater","",true));
if ($clmatestoque->numrows==0){
  $where = "1=2";
}
db_input('where',6,'',true,'hidden',3);
db_input('inner',6,'',true,'hidden',3);

?>
<td><b>Valor total em estoque:</b></td>
<td>
<?
$vlrtot = db_formatar(@$pr_medio* ($quantidadeestoque + $quantidadetransferencia), 'f');
$vlrtot = trim($vlrtot);
db_input('vlrtot',15,'',true,'text',3);
?>
</td>
</tr>
<tr>
<td><b>Quantidade total em estoque:</b></td>
<td>
<?php
$quantot = $quantidadeestoque + $quantidadetransferencia;
db_input('quantot',15,'',true,'text',3);
?>
</td>
</tr>
<!--<tr>
<td><b>Valor total em transferência:</b></td>
<td>
<?php
//$valortotaltransferencia = ($pr_medio * $quantidadetransferencia);
//db_input('valortotaltransferencia',15,'',true,'text',3);
?>
</td>
</tr>-->
<tr>
<td><b>Quantidade total reservada:</b></td>
<td>
<?php
db_input('quantidadetransferencia',15,'',true,'text',3);
?>
</td>
</tr>
<tr>
<td><b>Preço Médio Atual :</b></td>
<td>
<?
db_input('pr_medio',15,'',true,'text',3);
?>
</td>
</tr>
<!-- <tr>
<td><b>Preço Médio Atual:</b></td>
<td>
<?php
$preco_medio=db_calculapm($codmater,null);
db_input('preco_medio',15,'',true,'text',3);
?>
</td>
</tr>-->
<tr>
<td colspan=2 align=center>
<br>
<input name="voltar" type="button" value="Voltar" onclick="parent.db_iframe.hide();" >
</td>
</tr>
<tr>
<td colspan=2>
<iframe name="matestoque" id="matestoque" src="mat1_matconsultaiframe001.php?codmater=<?=$codmater?>&where=<?=$where?>&preco_medio=<?=$preco_medio?>" width="720" height="220" marginwidth="0" marginheight="0" frameborder="0">
</iframe>
</td>
</tr>
<tr>
<td colspan=2>
<input name='lancamentos' type='button' value='Consulta Lançamentos' onclick="js_lancamentos();">
<input name='requisicao' type='button' value='Requisições' onclick="js_requisicoes();">
<input name='atendimento' type='button' value='Atendimentos' onclick="js_atendimentos();">
<input name='devolucao' type='button' value='Devoluções' onclick="js_devolucoes();">
<input name='pontopedido' type='button' value='Ponto Pedido' onclick="js_pontopedidos();">
<input name='lotes' type='button' value='Lotes' onclick="js_lotes();">
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>

</fieldset>
</center>
</body>
</html>
<script type="text/javascript">
document.getElementById('vlrtot').style.textAlign                  = 'right';
document.getElementById('quantot').style.textAlign                 = 'right';
document.getElementById('quantidadetransferencia').style.textAlign = 'right';
document.getElementById('valortotaltransferencia').style.textAlign = 'right';Y
</script>
<?

function db_calculapm($codmater,$data=null){
  $clmatestoqueitem = new cl_matestoqueitem;
  if ($codmater!=""){
    if ($data!=null&&$data!=""){
      $where = "and  m71_data<=$data and m71_servico is false";
    }else{
      $where = "m71_servico is false";
    }
    global $valor;
    global $quant;
    $result_precomedio=$clmatestoqueitem->sql_record($clmatestoqueitem->sql_query(null,"sum(m71_valor) as valor,sum(m71_quant) as quant",null,"m70_codmatmater=$codmater $where"));
    if ($clmatestoqueitem->numrows>0){
      db_fieldsmemory($result_precomedio,0,true);
      if ($valor==0||$quant==0){
        $preco_medio=db_formatar(0,'f');
      }else{
        $preco_medio=$valor/$quant;
        $preco_medio=db_formatar($preco_medio,'f');
      }
    }else{
      return false;
    }
  }else{
    return false;
  }
  return $preco_medio;
}

?>