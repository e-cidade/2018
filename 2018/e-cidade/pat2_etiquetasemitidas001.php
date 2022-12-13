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
require("libs/db_sessoes.php");
require("libs/db_usuariosonline.php");
require("dbforms/db_funcoes.php");
require("libs/db_utils.php");
require("dbforms/db_classesgenericas.php");
require("libs/db_app.utils.php");

$aux      			= new cl_arquivo_auxiliar;
$aux1		        = new cl_arquivo_auxiliar;
$aux2         	= new cl_arquivo_auxiliar;
$aux3         	= new cl_arquivo_auxiliar;

db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php 
db_app::load('scripts.js');
db_app::load('prototype.js');
db_app::load('estilos.css');
?>
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" action="">
<fieldset>
<legend>Relatórios - Etiquetas Emitidas</legend>
<table class="form-container">
    <tr>
      <td colspan="2" >
        <fieldset style="border: none;border-top:2px groove white;">
        <a  id='esconderdepartamentos' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeDepartamento('');">
          <legend><b>Filtrar Departamentos</b>
          <img src='imagens/setabaixo.gif' id='toggledepartamentos' border='0'>
          </legend>        
        </a>     
        <table class="form-container" id="tbDepartamentos" style="display: none;">
      		<?
          // $aux = new cl_arquivo_auxiliar;
          $aux->cabecalho = "<strong>Departamentos</strong>";
          $aux->codigo = "coddepto"; //chave de retorno da func
          $aux->descr  = "descrdepto";   //chave de retorno
          $aux->nomeobjeto = 'departamentos';
          $aux->funcao_js = 'js_departamentos';
          $aux->funcao_js_hide = 'js_departamentos1';
          $aux->sql_exec  = "";
          $aux->func_arquivo = "func_db_depart.php";  //func a executar
          $aux->nomeiframe = "db_iframe_db_depart";
          $aux->localjan = "";
          $aux->onclick = "";
          $aux->db_opcao = 2;
          $aux->tipo = 2;
          $aux->top = 0;
          $aux->linhas = 5;
          $aux->vwhidth = 400;
          $aux->nome_botao = 'db_lanca_departamento';
          $aux->funcao_gera_formulario();
        	?>
        </table>
        </fieldset>
       </td>
     </tr>
     <tr>
      <td colspan="2"> 
        <fieldset style="border: none;border-top:2px groove white;">
        <a  id='esconderdivisao' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeDivisao('');">
          <legend><b>Filtrar Divisões</b>
          <img src='imagens/setabaixo.gif' id='toggledivisao' border='0'>
          </legend>        
        </a>     
        <table class="form-container" id="tbDivisao" style="display: none;">
          <?
	        $aux1 = new cl_arquivo_auxiliar;
	        $aux1->cabecalho = "<strong>Divisão</strong>";
	        $aux1->codigo = "t30_codigo"; //chave de retorno da func
	        $aux1->descr  = "t30_descr";   //chave de retorno
	        $aux1->nomeobjeto = 'divisoes';
	        $aux1->funcao_js = 'js_divisoes';
	        $aux1->funcao_js_hide = 'js_divisoes1';
	        $aux1->sql_exec  = "";
	        $aux1->func_arquivo = "func_departdiv.php";  //func a executar
	        $aux1->nomeiframe = "db_iframe_departdiv";
	        $aux1->localjan = "";
	        $aux1->onclick = "";
	        $aux1->db_opcao = 2;
	        $aux1->tipo = 2;
	        $aux1->top = 0;
	        $aux1->linhas = 5;
	        $aux1->vwhidth = 400;
	        $aux1->nome_botao = 'db_lanca_divisao';
	        $aux1->funcao_gera_formulario();
	        ?>
	      </table>
	      </fieldset>
	     </td>
	 </tr>
   <tr>
      <td colspan="2"> 
        <fieldset style="border: none;border-top:2px groove white;">
        <a  id='esconderbens' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeBens('');">
          <legend><b>Filtrar Bens</b>
          <img src='imagens/setabaixo.gif' id='togglebens' border='0'>
          </legend>        
        </a>     
        <table class="form-container" id="tbBens" style="display: none;">
          <?
          $aux2 = new cl_arquivo_auxiliar;
          $aux2->cabecalho = "<strong>Bens</strong>";
          $aux2->codigo = "t52_bem"; //chave de retorno da func
          $aux2->descr  = "t52_descr";   //chave de retorno
          $aux2->nomeobjeto = 'bens';
          $aux2->funcao_js = 'js_bens';
          $aux2->funcao_js_hide = 'js_bens1';
          $aux2->sql_exec  = "";
          $aux2->func_arquivo = "func_bens.php";  //func a executar
          $aux2->nomeiframe = "db_iframe_bens";
          $aux2->localjan = "";
          $aux2->onclick = "";
          $aux2->db_opcao = 2;
          $aux2->tipo = 2;
          $aux2->top = 0;
          $aux2->linhas = 5;
          $aux2->vwhidth = 400;
          $aux2->nome_botao = 'db_lanca_bem';
          $aux2->funcao_gera_formulario();
          ?>
        </table>
        </fieldset>
       </td>
   </tr>
   <tr>
      <td colspan="2"> 
        <fieldset style="border: none;border-top:2px groove white;">
        <a  id='esconderclassificacoes' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeClassificacoes('');">
          <legend><b>Filtrar Classificações</b>
          <img src='imagens/setabaixo.gif' id='toggleclassificacoes' border='0'>
          </legend>        
        </a>     
        <table class="form-container" id="tbClassificacoes" style="display: none;">
          <?
          $aux3 = new cl_arquivo_auxiliar;
          $aux3->cabecalho = "<strong>Classificações</strong>";
          $aux3->codigo = "t64_codcla"; //chave de retorno da func
          $aux3->descr  = "t64_descr";   //chave de retorno
          $aux3->nomeobjeto = 'clabens';
          $aux3->funcao_js = 'js_clabens';
          $aux3->funcao_js_hide = 'js_clabens1';
          $aux3->sql_exec  = "";
          $aux3->func_arquivo = "func_clabens.php";  //func a executar
          $aux3->nomeiframe = "db_iframe_clabens";
          $aux3->localjan = "";
          $aux3->onclick = "";
          $aux3->db_opcao = 2;
          $aux3->tipo = 2;
          $aux3->top = 0;
          $aux3->linhas = 5;
          $aux3->vwhidth = 400;
          $aux3->nome_botao = 'db_lanca_classificacao';
          $aux3->funcao_gera_formulario();
          ?>
        </table>
        </fieldset>
       </td>
   </tr>
  <tr id="datas" >
       <td>Período em:</td>
       <td nowrap>
       <?
          db_inputdata("data_inicial","","","",true,"text",4);
       ?>&nbsp;<b>a</b>&nbsp;
       <?
          db_inputdata("data_final","","","",true,"text",4);
       ?>
       </td>
  </tr>   
  <tr>
    <td>Etiquetas:</td>
    <td>
    <?
      $imp_forn = array("T"=>"Todas","I"=>"Impressas","R"=>"Reimpressas");
      db_select("etiqueta",$imp_forn,true,1);
    ?>
    </td>
  </tr>
  <tr>
    <td>Tipo:</td>
    <td>
    <?
      $imp_forn = array("T"=>"Todas","L"=>"Lote","I"=>"Individual");
      db_select("tipo",$imp_forn,true,1);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right"><b>Ordenar por:</b></td>
    <td nowrap>
    <?
      $ordenar = array("1"=>"Bem","2"=>"Placa","3"=>"Data");
      db_select("ordenar",$ordenar,true,1);
    ?>
    </td>
  </tr>
</table>
</fieldset>
<input type="button" value="Emitir relatório" onClick="js_emite();">
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_emite(){

   var sQuery       = "";
   
   var data_inicial = $('data_inicial').value;
   var data_final   = $('data_final').value;
   
   if (data_inicial != ""){
    if (data_inicial != ""){
      var vet_data_inicial = data_inicial.split("/");
      data_inicial         = vet_data_inicial[2]+"-"+vet_data_inicial[1]+"-"+vet_data_inicial[0];
    }
    if (data_final != ""){
      var vet_data_final   = data_final.split("/");
      data_final           = vet_data_final[2]+"-"+vet_data_final[1]+"-"+vet_data_final[0];
    }

    if (data_inicial != "" && data_final != ""){
    //Validar as datas
	    var sRetornoDatas = js_diferenca_datas(data_inicial,data_final,3);
	    if(sRetornoDatas == true){
	      alert(_M("patrimonial.patrimonio.pat2_etiquetasemitidas001.data_inicial_menor_data_final"));
	      $('data_inicial').value = '';
	      $('data_inicial').focus();
	      return false;
	    }
	        
	    sQuery += "dtinicial="+data_inicial+"&dtfinal="+data_final;
	     
	   }
   }
         	
	 //Le os itens lançados no departamentos
	 if ($('departamentos')) {	
			vir="";
			listaDepartamentos="";
			 
			for (x=0;x<document.form1.departamentos.length;x++) {
			 	listaDepartamentos+=vir+document.form1.departamentos.options[x].value;
			 	vir=",";
			} 
			if ( listaDepartamentos!="" ) { 	
				sQuery +='&departamentos=('+listaDepartamentos+')';
			} else {
				sQuery +='&departamentos=';
			} 	
   }
   
   //Le os itens lançados na Divisões 
   if ($('divisoes')) {  
	    vir="";
	    listaDivisoes="";
	     
	    for(x=0;x<document.form1.divisoes.length;x++){
	      listaDivisoes+=vir+document.form1.divisoes.options[x].value;
	      vir=",";
	    } 
	    if(listaDivisoes!=""){   
	      sQuery +='&divisoes=('+listaDivisoes+')';
	    } else {
	      sQuery +='&divisoes=';
	    }
   }
   
   //Le os itens lançados na Bens 
   if ($('bens')) {  
	    vir="";
	    listaBens="";
	     
	    for(x=0;x<document.form1.bens.length;x++){
	      listaBens+=vir+document.form1.bens.options[x].value;
	      vir=",";
	    } 
	    if(listaBens!=""){   
	      sQuery +='&bens=('+listaBens+')';
	    } else {
	      sQuery +='&bens=';
	    }
   }
   
   //Le os itens lançados na classificacoes 
   if ($('clabens')) {  
	    vir="";
	    listaClaBens="";
	     
	    for(x=0;x<document.form1.clabens.length;x++){
	      listaClaBens+=vir+document.form1.clabens.options[x].value;
	      vir=",";
	    } 
	    if(listaClaBens!=""){   
	      sQuery +='&clabens=('+listaClaBens+')';
	    } else {
	      sQuery +='&clabens=';
	    }
   }
   
   sQuery += '&etiqueta='+$('etiqueta').value; 
   sQuery += '&tipo='+$('tipo').value; 
   sQuery += '&ordenar='+$('ordenar').value; 
    
   //alert(sQuery);
   //return false; 
     
   jan = window.open('pat2_etiquetasemitidas002.php?'+sQuery,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
   
}
</script>  
<script>

function js_escondeDepartamento(){

    if ($('tbDepartamentos').style.display == 'none') {
      
      $('tbDepartamentos').style.display = '';
      $('toggledepartamentos').src = 'imagens/seta.gif';
      
    } else {
    
      $('tbDepartamentos').style.display = 'none';
      $('toggledepartamentos').src = 'imagens/setabaixo.gif';
      
    } 
}

function js_escondeDivisao(){

    if ($('tbDivisao').style.display == 'none') {
      
      $('tbDivisao').style.display = '';
      $('toggledivisao').src = 'imagens/seta.gif';
      
    } else {
    
      $('tbDivisao').style.display = 'none';
      $('toggledivisao').src = 'imagens/setabaixo.gif';
      
    } 
}

function js_escondeBens(){

    if ($('tbBens').style.display == 'none') {
      
      $('tbBens').style.display = '';
      $('togglebens').src = 'imagens/seta.gif';
      
    } else {
    
      $('tbBens').style.display = 'none';
      $('togglebens').src = 'imagens/setabaixo.gif';
      
    } 
}

function js_escondeClassificacoes(){

    if ($('tbClassificacoes').style.display == 'none') {
      
      $('tbClassificacoes').style.display = '';
      $('toggleclassificacoes').src = 'imagens/seta.gif';
      
    } else {
    
      $('tbClassificacoes').style.display = 'none';
      $('toggleclassificacoes').src = 'imagens/setabaixo.gif';
      
    } 
}

</script>
<script>

$("data_inicial").addClassName("field-size2");
$("data_final").addClassName("field-size2");

$("coddepto").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("departamentos").style.width = "100%";

$("t30_codigo").addClassName("field-size2");
$("t30_descr").addClassName("field-size7");
$("divisoes").style.width = "100%";

$("t52_bem").addClassName("field-size2");
$("t52_descr").addClassName("field-size7");
$("bens").style.width = "100%";

$("t64_codcla").addClassName("field-size2");
$("t64_descr").addClassName("field-size7");
$("clabens").style.width = "100%";

</script>