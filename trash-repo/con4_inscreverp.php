<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include("classes/db_orcorgao_classe.php");
include("dbforms/db_funcoes.php");

$get                     = db_utils::postmemory($_GET);
$post                    = db_utils::postmemory($_POST);
//$clorcorgao              = new cl_orcorgao;
(integer)$db_opcao       = 1;
(bool)$db_botao          = true;
(string)$sWh             = null;
(string)$lblDescr        = null;
(int)$iNumRows           = 0;
(float)$nTotalAliquidar  = 0;
(float)$nTotalLiquidado  = 0;
(float)$nTotalGeral      = 0 ;
$oDaoEncerramento        =  db_utils::getDao("conencerramento");
$sSqlIncritos            = "select c42_sequencial";
$sSqlIncritos           .= "  from conencerramento ";
$sSqlIncritos           .= " where c42_anousu           = ".db_getsession("DB_anousu");
$sSqlIncritos           .= "   and c42_instit           = ".db_getsession("DB_instit");
$sSqlIncritos           .= "   and c42_encerramentotipo = 1";
$rsInscritos             = $oDaoEncerramento->sql_record($sSqlIncritos);
$sMensagens              = "";
$lErro                   = false;

/*
 * Verificamos se o usuário já inscriveu todos os empennhos com saldo a liquidar.
 */
$dtLanc = implode('-', array_reverse(explode("/", $get->dtlanc)));
$sSqlTotalEmpenhos = $oDaoEncerramento->sqlQueryEmpenhosNaoliquidados(
                                                                      db_getsession("DB_instit"),
                                                                      db_getsession("DB_anousu"),
                                                                      $dtLanc 
                                                                     );
$rsTotalEmpenhos = db_query("select coalesce(count(*),0)  as total from ($sSqlTotalEmpenhos) as x"); 
$iTotal          = db_utils::fieldsMemory($rsTotalEmpenhos, 0)->total;
if ($iTotal > 0 ){

  $sSqlTotalEmpenhosInscritos = $oDaoEncerramento->sqlQueryEmpenhosNaoliquidados(
                                                                        db_getsession("DB_instit"),
                                                                        db_getsession("DB_anousu"),
                                                                        $dtLanc,
                                                                        null,
                                                                        " and c75_numemp is not null " 
                                                                     );
  $rsTotalEmpenhosInscritos = db_query("select coalesce(count(*),0)  as totalinscritos from ($sSqlTotalEmpenhosInscritos) as x"); 
  $iTotalInscritos          = db_utils::fieldsMemory($rsTotalEmpenhosInscritos, 0)->totalinscritos;
  
  if ($iTotal != $iTotalInscritos && $iTotalInscritos > 0) {
    
    $sMensagens  = "Ainda há RP não processados a serem inscritos.\\nEmita o relatório para saber quais os recursos que estão por inscrever.";
    $lErro       = true;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cab  {font-weight:bold;text-align:center;
       padding:2px;
			 border-bottom:1px outset black;
			 border-left:1px outset black;           
       background-color:#EEEFF2;          
	
	}
.linhagrid{ border:collapse;
            border-right:1px inset black;
            border-bottom:1px inset black;
            cursor:normal;
 }
.marcado{ background-color:#EFEFEF}
.normal{background-color:#FFFFFF}
</style>
<script>
</script>
</head>
<body bgcolor=#CCCCCC style='margin-top:0px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<div class='cab'>
Inscrição de Restos a Pagar Não Processados
</div>
<center>
<form method='post' name='form1'>
<center>
<fieldset>
<table>
<tr>
  <td><b>Filtros para Pesquisa:</b></td>
  <td><input type='button' value='selecione' onclick='mostraFiltros(<?=db_getsession("DB_instit");?>)'>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td><input type='button' onclick='js_pesquisa()' id='pesquisar' value='Pesquisar' disabled>
  </tr>
  <tr>
  <td><input name='filtros' type='hidden' id='filtros'>
  </tr>
</table>
</center>
</fieldset>
<fieldset>
 <table cellspacing="0" width='100%' style='border: 2px inset white;'>
 <tr>
  <td class='cab' colspan=5>Dados do Empenho</td>
  <td class='cab' colspan=6>Valores</td>
 </tr>
  <tr>
	<td  class="cab">&nbsp;</td>
	<td  class="cab">Credor</td>
	<td  class="cab">Nº Empenho</td>
	<td  class="cab">Dotação</td>
	<td  class="cab" size='15'>Recurso</td>
	<td  class="cab">A Liquidar</td>
	<td  class="cab">Liquidado</td>
	<td  class="cab">Geral</td>
	<td  class="cab">&nbsp;<b>
	<input type='checkbox' style='display:none' id='mtodos' onclick='js_marca()'>
	<a onclick='js_marca()' style='cursor:pointer'>M</a></b></td>
	<td class="cab" colspan="2">&nbsp;</td>
</tr>	
<tbody  id='dados' style='height:320;width:95%;overflow:scroll;border:2px inset black;overflow-x:hidden;background-color:white'>
</form>
</tbody>
<tfoot>
<tr class='linhagrid'>
	<td class='cab' colspan='2' style='text-align:center'><b>Total de Empenhos</b></td>
	<td class='cab' style='text-align:center' id='numrows'><b>&nbsp;</b></td>
	<td class='cab' colspan='2' style='text-align:center'><b>Totalizador Geral</b></td>
	<td class='cab' id='totalaliquidar' style='text-align:right;background-color:#FFCC99'><b><?=$nTotalAliquidar?></b></td>
	<td class='cab' id='totalliquidado' style='text-align:right;background-color:#FFF'><b><?=$nTotalLiquidado?></b></td>
	<td class='cab' id='totalgeral'     style='text-align:right;background-color:#FFF'><b><?=$nTotalGeral?></b></td>
	<td class='cab' colspan='2'style='text-align:right'>&nbsp;</td>
	<td class='cab' colspan='2'style='text-a lign:right'>&nbsp;</td>
</tr>	
</tfoot>
</table>
</fieldset>
<input type='button' id='inscrever' value='Inscrição' disabled name='inscrever' onclick="js_inscreverRPS()"> 
<input type='button' id='relatorio' value='Relatório' name='relatorio' onclick="js_relatorio()"> 
</body>
<script>
function js_mostraEmpenho(chave){

  arq = 'func_empempenho001.php?e60_numemp='+chave 
  js_OpenJanelaIframe('top.corpo','db_iframe_saldos',arq,'Pesquisa',true);
}
function js_marca(){
  
	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'chkimp');
	 for (i = 0;i < itens.length;i++){

        if (obj.checked == true){
					itens[i].checked=true;
          js_marcaLinha(itens[i]);
       }else{
					itens[i].checked=false;
          js_marcaLinha(itens[i]);
			 }
	 }
}

function mostraFiltros(instit){

  url ='func_selorcdotacao_aba.php?instit='+instit+'&db_selinstit='+instit+'&desdobramento=true'
  js_OpenJanelaIframe('top.corpo','db_iframe_filtro',url,'Filtros',true);
  $('pesquisar').disabled=false;
}

function js_pesquisa(){
  
  $('filtros').value = top.corpo.db_iframe_filtro.jan.js_atualiza_variavel_retorno();
  js_consultaEmpenho(); 

}

function js_consultaEmpenho(){
   
   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"method":"consultarRPAjax","pars":"'+$F('filtros')+'","datalanc":"'+parent.$F('datalanc')+'"}';
   $('dados').innerHTML    = '';
   $('pesquisar').disabled = true;
   $('inscrever').disabled = true;
   url     = 'con4_inscreverp002.php';
   oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_saida
                              }
                             );

}

function js_saida(oAjax){

    obj  = eval("("+oAjax.responseText+")");
    saida = '';
    $('numrows').innerHTML = obj.numrows;
    $('dados').innerHTML   = '';
    if (obj.numrows > 0){
      for (i = 0; i < obj.data.length;i++){
        
         cor = i % 2 == 0?'#FFFFFF':'#FFF';
         saida += "<tr class='normal' style='height:1em' id='trchk"+obj.data[i].e60_numemp+"'>";
         credor = obj.data[i].z01_nome.replace(/\+/g," ");
         credor = unescape(credor);
         saida += "<td class='linhagrid' style='text-align:center'><input class='coddot' type='checkbox' name='chkitens[]'"
         saida += " value='"+obj.data[i].e60_numemp+"'checked style='display:none'>";
         saida += "<a href='#' onclick='js_mostraEmpenho("+obj.data[i].e60_numemp+",0)'><b>MI</b></a></td>";
         saida += "<td class='linhagrid' style='text-align:left'>"+credor+"</td>";
         saida += "<td class='linhagrid' style='text-align:center'>"+obj.data[i].e60_codemp+"</td>";
         saida += "<td class='linhagrid' style='text-align:center'>"+obj.data[i].e60_coddot+"</td>";
         saida += "<td class='linhagrid' style='text-align:center'>"+obj.data[i].o58_codigo+"</td>";
         saida += "<td class='linhagrid' id='vlrliq"+obj.data[i].e60_numemp+"'style='background-color:#FFCC99;text-align:right;width:10%'>"+obj.data[i].a_liquidar+"</td>";
         saida += "<td class='linhagrid' style='text-align:right;width:10%'>"+obj.data[i].liquidado+"</td>";
         saida += "<td class='linhagrid' style='text-align:right;width:10%'>"+obj.data[i].geral+"</td>";
         saida += "<td class='linhagrid' style='text-align:center'>";
         saida += "<input type='checkbox' onclick='js_marcaLinha(this)' class='chkimp' name='chk"+obj.data[i].e60_numemp+"' id='chk"+obj.data[i].e60_numemp+"' value='"+obj.data[i].e60_numemp+"'></td></tr>";
     }
    }
    saida += "<tr style='height:auto'><td colspan='9'>&nbsp;</td></tr>";
    $('dados').innerHTML          = saida;
    $('totalaliquidar').innerHTML = obj.totalALiquidar;
    $('totalliquidado').innerHTML = obj.totalLiquidado;
    $('totalgeral').innerHTML     = obj.totalGeral;
    js_removeObj("msgBox");
    $('pesquisar').disabled = false;
    $('inscrever').disabled = false;
}
function js_marcaLinha(obj){
 
  if (obj.checked){
    
    $('tr'+obj.id).className='marcado';
  }else{

   $('tr'+obj.id).className='normal';

  }

}
//Funcao para Inscrever RP's;
function js_inscreverRPS(){
   
   itens = js_getElementbyClass(form1,'chkimp');
   if (itens.length > 1000) {
      
     alert('Procedimento Cancelado. selecione alguns Filtros para pesquisa');
     return false;
     
   }
   empenhos = '';
   sV = '';
   $('pesquisar').disabled = true;
   $('inscrever').disabled = true;
   regex = /\./g;
   for (i = 0;i < itens.length;i++){
     if (itens[i].checked == true){

        valor     = $('vlrliq'+itens[i].value).innerHTML;
        valor     = valor.replace(/ /g,"");
        valor2    = valor.replace(regex,"");
        valor2    = valor2.replace(",","."); 
        empenhos += sV+'{"empenho":"'+itens[i].value+'","valorLiquidar":"'+valor2+'"}';
        sV = ",";
      }
   }

   if (empenhos != ''){
     js_divCarregando("Aguarde, Inscrevendo RPs ","msgRPS");
     strJson = '{"method":"inscreverRPAjax","datalanc":"'+parent.$F('datalanc')+'","pars":['+empenhos+']}';
     url     = 'con4_inscreverp002.php';
     oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_saidaInscricao
                              }
                             );
   }else{

     alert('Selecione ao menos 1 (um) Empenho para Inscrever');
     $('pesquisar').disabled = false;
     $('inscrever').disabled = false;

   }
}
function js_saidaInscricao(oAjax){
 
    js_removeObj("msgRPS");
    $('pesquisar').disabled = false;
    $('inscrever').disabled = false;
    obj      = eval("("+oAjax.responseText+")");
    mensagem = obj.mensagem.replace(/\+/g," ");
    mensagem = unescape(mensagem);
    parent.$('processarec').disabled        = false
    parent.$('processatrans').disabled      = false
    parent.$('processacompensado').disabled = false;
    alert(mensagem);
    if (obj.erro ==1){

    	parent.window.location = 'con4_processaencerramento001.php';
      // $('pesquisar').click();
    }
}
function js_relatorio(){

    window.open('con2_relrpinscritos001.php','','location=0');   


}

</script>
<?
  if ($lErro) {
    db_msgbox("$sMensagens"); 
  }
?>