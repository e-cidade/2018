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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_db_depart_classe.php");
include("classes/db_cfpatri_classe.php");
include("classes/db_bens_classe.php");
include("classes/db_clabens_classe.php");
include("classes/db_departdiv_classe.php");

$cldb_depart 		= new cl_db_depart;
$clcfpatri 			= new cl_cfpatri;
$clbens      		= new cl_bens;
$clclabens   		= new cl_clabens;
$cldepartdiv 		= new cl_departdiv;
$cldb_estrut 		= new cl_db_estrut;
$aux_bem	 			= new cl_arquivo_auxiliar;
$aux_conta      = new cl_arquivo_auxiliar;
$aux_situabens  = new cl_arquivo_auxiliar;
$oAuxDpto       = new cl_arquivo_auxiliar;
$oDptoDivisao   = new cl_arquivo_auxiliar;

$cldb_depart->rotulo->label();
$clbens->rotulo->label();
$clclabens->rotulo->label();
db_postmemory($HTTP_POST_VARS);
$result = $clcfpatri->sql_record($clcfpatri->sql_query_file());
db_fieldsmemory($result,0);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript"
	src="scripts/prototype.js"></script>

<script>
function js_abre(){

    if ($('lista_departamento').value == "") {
      if (!confirm(_M("patrimonial.patrimonio.pat2_bensdepart001.nenhum_departamento_selecionado"))) {
        return false;
      }
    }


    var iDivDepart = document.getElementById("divisao").length;
    sDivDepartSelecionado = "";
    
    for ( var dd = 0; dd < iDivDepart ; dd++ ) {
      if ( sDivDepartSelecionado == "" ) {
        sDivDepartSelecionado += document.getElementById("divisao")[dd].value;
      } else {
        sDivDepartSelecionado += ","+document.getElementById("divisao")[dd].value;
      }
    }


    iDptos = document.getElementById("departamento").length;
    sValoresDpto = "";
    
    for (var i = 0; i < iDptos; i++) {
      
      if (sValoresDpto == "") {
        sValoresDpto += document.getElementById("departamento")[i].value;
      } else {
        sValoresDpto += ","+document.getElementById("departamento")[i].value;
      }
      
    }


  	qry = "";    
  	
  	if(document.getElementById('bens')){
	    //Le os itens lançados na combo dos bens
			vir="";
		 	listaBens="";
		 
		 	for(x=0;x<document.form1.bens.length;x++){
		  	listaBens+=vir+document.form1.bens.options[x].value;
		  	vir=",";
		 	}
			if(listaBens!=""){ 	
				qry +='&bens=('+listaBens+')';
			} else {
				qry +='&bens=';
			}
		}
  	
    if (document.getElementById('contas')) {
      //Le os itens lançados na combo dos bens
      var vir="";
      var sListaContas="";
     
      for(var x = 0; x < document.form1.contas.length; x++) {
      
        sListaContas += vir + document.form1.contas.options[x].value;
        vir=",";
        
      }
      if (sListaContas != "") {
        qry +='&contas=('+sListaContas+')';
        
      } else {
        qry +='&contas=';
      }
    }

    //Filtro por situação do bem
    //coleta os dados do select e cria uma string com os sequenciais da tabela situabens
    if (document.getElementById('situabens')) {
      //Le os itens lançados na combo dos situabens
      var vir="";
      var sListaSituaBens="";
     
      for(var x = 0; x < document.form1.situabens.length; x++) {
      
        sListaSituaBens += vir + document.form1.situabens.options[x].value;
        vir=",";
        
      }
      if (sListaSituaBens != "") {
        qry +='&situabens=('+sListaSituaBens+')';
        
      } else {
        qry +='&situabens=';
      }
    }
        
    
  	qry+= '&t52_baixainicio='+$F('t52_baixainicio');
  	qry+= '&t52_baixafim='+$F('t52_baixafim');
    qry+= '&opcao_obs='+document.form1.opcao_obs.value;
    qry+= '&opcao_baixados='+document.form1.opcao_baixados.value;
    qry+= '&ordem='+document.form1.ordem.value;
    qry+= '&quebra='+document.form1.quebra.value;
    qry+= '&descricao='+document.form1.descricao.value;
    qry+= '&dtaquini='+$F('t52_dtaquini');
    qry+= '&dtaquifim='+$F('t52_dtaqufim');
    qry+= '&t52_depart='+sValoresDpto;
    qry+= '&t30_depto='+sDivDepartSelecionado;
    qry+= '&usardivisao='+$('usardivisao').value;
        
    testa = document.form1.t64_class.value;
    for(i=0;i<document.form1.t64_class.value.length;i++){
      testa = testa.replace('.','');       
    }
    var aListaConvenio  = js_campo_recebe_valores();
    var iMostraConvenio = $F('vinculoconvenio');
    qry += "&opcoescedentes="+iMostraConvenio;
    qry += "&listacedentes="+aListaConvenio;
    qry += '&t64_class='+testa;
    qry += "&imp_valor="+document.form1.imp_valor.value;
		jan  = window.open('pat2_bensdepart002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    //document.form1.coddepto.style.backgroundColor='';
}

//Funções para criar o minimizador nas fieldset

//js_escondeFieldset
//Função executada no onload da página
//passa por todos fieldset's pega a tabela que o cl_arquivo_auxiliar gera
//dá um display none e muda a imagem do label

function js_escondeFieldset(){

     var oFields = document.getElementsByTagName("fieldset");

     //Percorre os fieldset da pagina  
     for(var i=0;i < oFields.length;i++){
    
       var oCampo = oFields[i];
       
       //CSS para UI
       oCampo.style.width = '560px';
       oCampo.style.cursor = 'pointer';
       
       //Elementos Filhos
       var oLegend = oCampo.getElementsByTagName("legend");
       var oTable = oCampo.getElementsByTagName("table");   
       oTable[0].style.display  = 'none';  
       oLegend[0].style.background = 'url(imagens/seta.gif) no-repeat right';
       oLegend[0].style.paddingRight  = '10px'; 
       oLegend[0].observe('click', function () {
         js_mostraFieldset(this);
       }) ; 
          
     }

}


//js_mostraFieldset
//Função executada no click do label
//@param: objeto label
//primeiro a funcao pega o seu elemento pai, depois seleciona uma tablea dentro dele
//testa se a tabela está visivel e muda a propriedade display do css e a imagem do label

function js_mostraFieldset(oLegend){

   var oTable  = (oLegend.parentNode).getElementsByTagName("table");   

   if(oTable[0].style.display == 'block'){
   
     oLegend.style.background = 'url(imagens/seta.gif) no-repeat right';
     oTable[0].style.display = 'none';
  
   } else {
  
     oLegend.style.background = 'url(imagens/setabaixo.gif) no-repeat right';
     oTable[0].style.display = 'block';
  
  }
  
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onLoad="js_escondeFieldset();">
<form class="container" name="form1" method="post">
<fieldset>
<legend>Relatórios - Conferência / Bens por Departamento</legend>
<table>
<tr><td></td></tr>
</table>
<table class="form-container">

</table>

<table>
	<tr>
    <td colspan="1">
      <?
        $oAuxDpto->cabecalho      = "<strong>Departamento</strong>";
        $oAuxDpto->codigo         = "coddepto"; //chave de retorno da func
        $oAuxDpto->descr          = "descrdepto";   //chave de retorno
        $oAuxDpto->nomeobjeto     = 'departamento';
        $oAuxDpto->funcao_js      = 'js_mostra_departamento';
        $oAuxDpto->funcao_js_hide = 'js_mostra_departamento1';
        $oAuxDpto->sql_exec       = "";
        $oAuxDpto->func_arquivo   = "func_db_depart.php";  //func a executar
        $oAuxDpto->nomeiframe     = "db_iframe_db_depart";
        $oAuxDpto->localjan       = "";
//        $oAuxDpto->onclick        = "js_liberaDivisaoDpto1();";
        $oAuxDpto->executa_script_apos_incluir = "js_liberaDivisaoDpto(); js_buscaDepartamentoSelecionado();";
        $oAuxDpto->db_opcao       = 2;
        $oAuxDpto->tipo           = 2;
        $oAuxDpto->top            = 0;
        $oAuxDpto->linhas         = 5;
        $oAuxDpto->vwidth         = 400;
        $oAuxDpto->nome_botao     = 'db_lanca';
        $oAuxDpto->fieldset       = false;        
        $oAuxDpto->funcao_gera_formulario();      
      ?>    
    </td>
  </tr>
  
	<tr id='tr_divisao' align='center'>
    <td colspan='4' align='center'>
       <div id='div_divisao' align="center">
          <?        
            db_input('lista_departamento', 10, true, 3, 'hidden', 3);
            db_input('lista_divdepartamento', 10, true, 3, 'hidden', 3);
            
            
            $aTipos = array(0 => 'Sem as divisões selecionadas', 1 => 'Com as divisões selecionadas'); 
            db_select('usardivisao', $aTipos, true, 1);          
            
            $oDptoDivisao->cabecalho      = "<strong>Divisão de Departamento</strong>";
            $oDptoDivisao->codigo         = "t30_codigo"; //chave de retorno da func
            $oDptoDivisao->descr          = "t30_descr";   //chave de retorno
            $oDptoDivisao->nomeobjeto     = 'divisao';
            $oDptoDivisao->funcao_js      = 'js_mostra_divisao';
            $oDptoDivisao->funcao_js_hide = 'js_mostra_divisao1';
            $oDptoDivisao->sql_exec       = "";
            $oDptoDivisao->func_arquivo   = "func_departdiv.php";  //func a executar
            $oDptoDivisao->nomeiframe     = "db_iframe_departdiv";
            $oDptoDivisao->passar_query_string_para_func = "&departamentos='+document.form1.lista_departamento.value+'";
            $oDptoDivisao->executa_script_apos_incluir   = "js_lancaDivisaoDepartamento()";
            $oDptoDivisao->localjan       = "";
            $oDptoDivisao->onclick        = "";
            $oDptoDivisao->db_opcao       = 2;
            $oDptoDivisao->tipo           = 2;
            $oDptoDivisao->top            = 0;
            $oDptoDivisao->linhas         = 5;
            $oDptoDivisao->vwidth         = 400;
            $oDptoDivisao->nome_botao     = 'db_lancadivisao';
            $oDptoDivisao->fieldset       = false; 
   
            $oDptoDivisao->funcao_gera_formulario();      
          ?> 
        </div>
	  </td>	
  </tr>
		<tr>
			<td colspan=2>
		  <?
			// $aux = new cl_arquivo_auxiliar;
			$aux_bem->cabecalho = "<strong>Bens</strong>";
			$aux_bem->codigo = "t52_bem"; //chave de retorno da func
			$aux_bem->descr  = "t52_descr";   //chave de retorno
			$aux_bem->nomeobjeto = 'bens';
			$aux_bem->funcao_js = 'js_mostra_bens';
			$aux_bem->funcao_js_hide = 'js_mostra_bens1';
			$aux_bem->sql_exec  = "";
			$aux_bem->func_arquivo = "func_bens.php";  //func a executar
			$aux_bem->passar_query_string_para_func = "&opcao=todos";
			$aux_bem->nomeiframe = "db_iframe_bens";
			$aux_bem->localjan = "";
			$aux_bem->onclick = "";
			$aux_bem->db_opcao = 2;
			$aux_bem->tipo = 2;
			$aux_bem->top = 0;
			$aux_bem->linhas = 5;
			$aux_bem->vwidth = 400;
			$aux_bem->nome_botao = 'db_lanca_bem';
			$aux_bem->fieldset = false;
			
			$aux_bem->funcao_gera_formulario();
			?>

			</td>
		</tr>
    <tr>
      <td colspan=2><?
      // $aux = new cl_arquivo_auxiliar;
      $aux_conta->cabecalho = "<strong>Estruturais</strong>";
      $aux_conta->codigo = "c60_codcon"; //chave de retorno da func
      $aux_conta->descr  = "c60_descr";  //chave de retorno
      $aux_conta->nomeobjeto = 'contas';
      $aux_conta->funcao_js = 'js_mostra_conta';
      $aux_conta->funcao_js_hide = 'js_mostra_conta1';
      $aux_conta->sql_exec  = "";
      $aux_conta->func_arquivo = "func_clabensconta.php";  //func a executar
      $aux_conta->nomeiframe = "db_iframe_conplano";
      $aux_conta->localjan = "";
      $aux_conta->onclick = "";
      $aux_conta->db_opcao = 2;
      $aux_conta->tipo = 2;
      $aux_conta->top = 0;
      $aux_conta->linhas = 5;
      $aux_conta->vwidth = 400;
      $aux_conta->nome_botao = 'db_lanca_conta';
      $aux_conta->funcao_gera_formulario();
      ?></td>
    </tr>

    <tr>
      <td colspan=2><?
      // $aux = new cl_arquivo_auxiliar;
      //Filtro por situação do bem
      //Controle de seleção de situação de bem
      $aux_situabens->cabecalho = "<strong>Situação do Bem</strong>";
      $aux_situabens->codigo = "t70_situac"; //chave de retorno da func
      $aux_situabens->descr  = "t70_descr";  //chave de retorno
      $aux_situabens->nomeobjeto = 'situabens';
      $aux_situabens->funcao_js = 'js_mostra_situabens';
      $aux_situabens->funcao_js_hide = 'js_mostra_situabens1';
      $aux_situabens->sql_exec  = "";
      $aux_situabens->func_arquivo = "func_situabens.php";  //func a executar
      $aux_situabens->nomeiframe = "db_iframe_situabens";
      $aux_situabens->localjan = "";
      $aux_situabens->onclick = "";
      $aux_situabens->db_opcao = 2;
      $aux_situabens->Labelancora = "Situação do bem";
      $aux_situabens->tipo = 2;
      $aux_situabens->top = 0;
      $aux_situabens->linhas = 5;
      $aux_situabens->vwidth = 400;
      $aux_situabens->nome_botao = 'db_lanca_situabens';
      $aux_situabens->funcao_gera_formulario();
      ?></td>
    </tr>
    	<tr>
		<td nowrap title="<?=@$Tt64_class?>"><?
	 db_ancora(@$Lt64_class,"js_pesquisat64_class(true);",1);
	 ?></td>
		<td><?
		$cldb_estrut->autocompletar = true;
		$cldb_estrut->mascara = false;
		$cldb_estrut->input   = true;
		$cldb_estrut->reload  = false;
		$cldb_estrut->size    = 10;
		$cldb_estrut->funcao_onchange ='js_pesquisat64_class(false);';
		$cldb_estrut->nome    = "t64_class";
		$cldb_estrut->db_opcao= 1;
		$cldb_estrut->db_mascara(@$t06_codcla);
		db_input('t64_descr',30,$It64_descr,true,'text',3,'')
		?></td>
	</tr>
		<tr>
			<td align="right" nowrap><? db_ancora(@$Lt52_dtaqu,"",3);?></td>
			<td align="left" nowrap><?
			db_inputdata('t52_dtaquini',null, null, null, true,'text',1,"");
			echo "&nbsp;<b> A </b>&nbsp;";
			db_inputdata('t52_dtaqufim',null, null, null, true,'text',1,"");
			?></td>
		</tr>
		<tr>
			<td align="right" nowrap><strong>Per&iacute;odo da Baixa</strong></td>
			<td align="left" nowrap><?
			db_inputdata('t52_baixainicio',null, null, null, true,'text',1);
			echo "&nbsp;<b> A </b>&nbsp;";
			db_inputdata('t52_baixafim',null, null, null, true,'text',1);
			?></td>
		</tr>

		<tr>
			<td align="right" nowrap title="Tipo de Agrupamento do Valor"><strong>Convênios
			:&nbsp;&nbsp;</strong></td>
			<td><? 
			$aConvenios = array (1 => "Ambos",
			2 => "Apenas vinculado a convênios",
			3 => "Apenas não vinculado a convênios"
			);
			db_select("vinculoconvenio", $aConvenios, true, 2, "onchange='js_showCedentes()'");
			?></td>
		</tr>
		<tr id='listacedentes' style="display: none;">
			<td colspan='2'>
			<table  class="form-container">
			<?
			$oListaCedente = new cl_arquivo_auxiliar;
			$oListaCedente->cabecalho = "<strong>Convênios</strong>";
			$oListaCedente->codigo = "t04_sequencial"; //chave de retorno da func
			$oListaCedente->descr  = "z01_nome"; //chave de retorno
			$oListaCedente->nomeobjeto = 'cedentes';
			$oListaCedente->funcao_js = 'js_mostra';
			$oListaCedente->funcao_js_hide = 'js_mostra1';
			$oListaCedente->sql_exec  = "";
			$oListaCedente->nome_botao  = "lancacedentes";
			$oListaCedente->func_arquivo = "func_benscadcedente.php";  //func a executar
			$oListaCedente->nomeiframe = "db_iframe_cedentes";
			$oListaCedente->localjan   = "";
			$oListaCedente->onclick    ="";
			$oListaCedente->db_opcao   = 2;
			$oListaCedente->tipo       = 2;
			$oListaCedente->top        = 0;
			$oListaCedente->linhas     = 5;
			$oListaCedente->obrigarselecao = false;
			$oListaCedente->vwhidth    = '100%';
			$oListaCedente->funcao_gera_formulario();
			?>
			</table>
			</td>
		</tr>
		<tr>
			<td align="right" nowrap title="Procurar por descrição"><b>Por
			descrição do bem:</b></td>
			<td nowrap title=""><?

			db_input('descricao',50,0,true,'text',1,"onKeyUp = js_Maiusculo('descricao')");
			?></td>
		</tr>
		<tr>
			<td nowrap align="right" title="Características adicionais do bem"><b>Características
			adicionais do bem:</b></td>
			<td nowrap title=""><?
			$matriz = array("N"=>"NÃO","S"=>"SIM"); 
			db_select("opcao_obs",$matriz,true,1);
			?></td>
		</tr>
		<tr>
			<td nowrap align="right" title="Imprimir valor de aquisição"><b>Imprimir
			valor de aquisição:</b></td>
			<td nowrap title=""><?
			$matriz = array("N"=>"NÃO","S"=>"SIM");
			db_select("imp_valor",$matriz,true,1);
			?></td>
		</tr>
		<tr>
			<td nowrap align="right" title="Bens a serem listados"><b>Listar:</b></td>
			<td nowrap title=""><?
			$matriz_baix = array("t"=>"Todos","n"=>"Não Baixados", "b"=>"Baixados"); 
			db_select("opcao_baixados",$matriz_baix,true,1);
			?></td>
		</tr>
		<tr>
			<td nowrap align="right" title="Ordem"><b>Ordem:</b></td>
			<td nowrap title=""><?
			$matriz_ordem = array(1=>"Placa",2=>"Código", 3=>"Descrição"); 
			db_select("ordem",$matriz_ordem,true,1);
			?></td>
		</tr>
		<tr>
			<td nowrap align="right" title="quebradepagina"><b>Quebra de Página:</b></td>
			<td nowrap title=""><?
			$matriz_quebra = array(1=>"Não",2=>"Departamento / Divisão"); 
			db_select("quebra",$matriz_quebra,true,1);
			?></td>
		</tr>
</table>
</fieldset>
<input name="relatorio" type="button"	onclick='js_abre();' value="Gerar relatório">
</form>

			<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>

//--------------------------------

function js_liberaDivisaoDpto() {

  $('div_divisao').style.display = "";
  $('tr_inicio_divisao').style.display = "";
  
  for (var i = 0; i < document.getElementById("departamento").length; i++) {
        
    var oRegistro = document.getElementById("departamento");
    oRegistro[i].observe('dblclick', function () {
    
      
      if (document.form1.departamento.length == 1) { 
        $('tr_divisao').style.display = "none";
        $('div_divisao').style.display = "none";
        $('tr_inicio_divisao').style.display = "none";
        $('lista_departamento').value = '';
        $('lista_divdepartamento').value = '';
      }
    });   
  }
}


function js_lancaDivisaoDepartamento () {
  var iDivDepart = document.getElementById("divisao").length;
  sDivDepartSelecionado = "";
  
  for ( var dd = 0; dd < iDivDepart ; dd++ ) {
    if ( sDivDepartSelecionado == "" ) {
      sDivDepartSelecionado += document.getElementById("divisao")[dd].value;
    } else {
      sDivDepartSelecionado += ","+document.getElementById("divisao")[dd].value;
    }
  }
  
  $('lista_divdepartamento').value = sDivDepartSelecionado;
}

function js_buscaDepartamentoSelecionado() {

  iDptos = document.getElementById("departamento").length;
  sValoresDpto = "";
    
  for (var i = 0; i < iDptos; i++) {
    
    if (sValoresDpto == "") {
      sValoresDpto += document.getElementById("departamento")[i].value;
    } else {
      sValoresDpto += ","+document.getElementById("departamento")[i].value;
    }
    
  }
  
  $('lista_departamento').value = sValoresDpto;
}

function js_pesquisat64_class(mostra){  
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?funcao_js=parent.js_mostraclabens1|t64_class|t64_descr&analitica=true','Pesquisa',true);  
  }else{
     testa = new String(document.form1.t64_class.value);     
     if(testa != '' && testa != 0){
       i = 0;       
       for(i = 0;i < document.form1.t64_class.value.length;i++){
         testa = testa.replace('.','');       
       }
       js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabens&analitica=true','Pesquisa',false);
     }else{
       document.form1.t64_descr.value = '';
     }
  }
}

function js_mostraclabens(chave,erro){
  document.form1.t64_descr.value = chave;
  if(erro==true){
    document.form1.t64_class.value = '';
    document.form1.t64_class.focus();
  }
}

function js_mostraclabens1(chave1,chave2){
  document.form1.t64_class.value = chave1;
  document.form1.t64_descr.value = chave2;
  db_iframe_clabens.hide();
}

function js_pesquisa_depart(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.coddepto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.coddepto.value+'&funcao_js=parent.js_mostradepart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
       document.form1.submit();
     }
  }
}
function js_mostradepart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.coddepto.focus(); 
    document.form1.coddepto.value = ''; 
  }else{
  	document.form1.submit();
  }
}
function js_mostradepart1(chave1,chave2){
  document.form1.coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
  document.form1.submit();
}
function js_Maiusculo(obj) {
	var valor = document.getElementById(obj).value;
	document.getElementById(obj).value = valor.toUpperCase();
}
function js_showCedentes() {
  
  if ($F('vinculoconvenio') == 2) {
    $('listacedentes').style.display = "";
  } else {
    $('listacedentes').style.display = "none";
  }
}

$('tr_inicio_divisao').style.display = 'none';
$('div_divisao').style.display = 'none';

</script>
</body>
</html>
<script>

$("fieldset_departamento").addClassName("separator");
$("coddepto").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("departamento").style.width = "100%";

$("fieldset_bens").addClassName("separator");
$("t52_bem").addClassName("field-size2");
$("t52_descr").addClassName("field-size7");
$("bens").style.width = "100%";

$("fieldset_contas").addClassName("separator");
$("c60_codcon").addClassName("field-size2");
$("c60_descr").addClassName("field-size7");
$("contas").style.width = "100%";

$("fieldset_situabens").addClassName("separator");
$("t70_situac").addClassName("field-size2");
$("t70_descr").addClassName("field-size7");
$("situabens").style.width = "100%";

//document.getElementsByName("t64_class").className = "field-size2";

$("t64_descr").addClassName("field-size7");
$("t52_dtaquini").addClassName("field-size2");
$("t52_dtaqufim").addClassName("field-size2");
$("t52_baixainicio").addClassName("field-size2");
$("t52_baixafim").addClassName("field-size2");
$("descricao").addClassName("field-size9");

$("fieldset_cedentes").addClassName("separator");
$("t04_sequencial").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("cedentes").style.width = "100%";

</script>