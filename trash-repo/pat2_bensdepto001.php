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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_db_depart_classe.php");
include("classes/db_departdiv_classe.php");
include("classes/db_bens_classe.php");
include("classes/db_cfpatri_classe.php");
//require_once("classes/db_docume");

db_postmemory($HTTP_POST_VARS);

$cldbdepart     = new cl_db_depart;
$cldepartdiv    = new cl_departdiv;
$clbens         = new cl_bens;
$clcfpatric     = new cl_cfpatri;
$aux_divisao    = new cl_arquivo_auxiliar;
$aux_orgao      = new cl_arquivo_auxiliar;
$aux_unidade    = new cl_arquivo_auxiliar;
$aux            = new cl_arquivo_auxiliar;


$clrotulo = new rotulocampo;
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("nomeresponsavel");
$clrotulo->label("emailresponsavel");
$clrotulo->label("limite");
$cldbdepart->rotulo->label();
$clbens->rotulo->label();

//Verifica se utiliza pesquisa por orgão sim ou não
$t06_pesqorgao = "f";

$resPesquisaOrgao = $clcfpatric->sql_record($clcfpatric->sql_query_file(null,'t06_pesqorgao'));
if($clcfpatric->numrows > 0) {
  $t06_pesqorgao = db_utils::fieldsMemory($resPesquisaOrgao,0)->t06_pesqorgao;
}

?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
<body bgcolor=#CCCCCC >

<form class="container" name="form1" method="post" action="">
<fieldset>
<legend>Relatórios - Termo de Responsabilidade</legend>
<table class="form-container">
    <?php 
  if($t06_pesqorgao == 't'){?>  
  <tr>
      <td colspan=2 >
      <fieldset style="border: none;border-top:2px groove white;">
      <a  id='esconderorgao' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeOrgao('');">
        <legend>Filtrar Orgãos
        <img src='imagens/setabaixo.gif' id='toggleorgaos' border='0'>
        </legend>        
      </a>     
      <table id="tbOrgaos" style="display: none;">
          <?
          // $aux = new cl_arquivo_auxiliar;
          $aux_orgao->cabecalho = "<strong>Órgãos</strong>";
          $aux_orgao->codigo = "o40_orgao"; //chave de retorno da func
          $aux_orgao->descr  = "o40_descr";   //chave de retorno
          $aux_orgao->nomeobjeto = 'orgaos';
          $aux_orgao->funcao_js = 'js_mostra_org';
          $aux_orgao->funcao_js_hide = 'js_mostra_org1';
          $aux_orgao->sql_exec  = "";
          $aux_orgao->func_arquivo = "func_orcorgao.php";  //func a executar
          $aux_orgao->nomeiframe = "db_iframe_orcorgao";
          $aux_orgao->localjan = "";
          $aux_orgao->onclick = "";
          $aux_orgao->db_opcao = 2;
          $aux_orgao->tipo = 2;
          $aux_orgao->top = 0;
          $aux_orgao->linhas = 5;
          $aux_orgao->vwidth = 440;
          $aux_orgao->nome_botao = 'db_lanca_orgao';
          $aux_orgao->funcao_gera_formulario();
          ?>
       </table>
       </fieldset>
       </td>
   </tr>
   <tr>
      <td colspan=2 >
      <fieldset style="border: none;border-top:2px groove white;">
      <a  id='esconderunidades' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeUnidade('');">
        <legend>Filtrar Unidades
        <img src='imagens/setabaixo.gif' id='toggleunidades' border='0'>
        </legend>        
      </a>     
      <table id="tbUnidades" style="display:none;">
          <?
          // $aux = new cl_arquivo_auxiliar;
          $aux_unidade->cabecalho = "<strong>Unidades</strong>";
          $aux_unidade->codigo = "o41_unidade"; //chave de retorno da func
          $aux_unidade->descr  = "o41_descr";   //chave de retorno
          $aux_unidade->nomeobjeto = 'unidades';
          $aux_unidade->funcao_js = 'js_mostra_uni';
          $aux_unidade->funcao_js_hide = 'js_mostra_uni1';
          $aux_unidade->sql_exec  = "";
          $aux_unidade->func_arquivo = "func_orcunidade.php";  //func a executar
          $aux_unidade->nomeiframe = "db_iframe_orcunidade";
          $aux_unidade->localjan = "";
          $aux_unidade->onclick = "";
          $aux_unidade->db_opcao = 2;
          $aux_unidade->tipo = 2;
          $aux_unidade->top = 0;
          $aux_unidade->linhas = 5;
          $aux_unidade->vwidth = 440;
          $aux_unidade->nome_botao = 'db_lanca_unidade';   
          $aux_unidade->funcao_gera_formulario();
          ?>
       </table>
       </fieldset>
       </td>
   </tr>
   <tr>
      <td colspan=2 >
      <fieldset style="border: none;border-top:2px groove white;">
      <a  id='esconderdepartamentos' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeDepartamento('');">
        <legend><b>Filtrar Departamentos</b>
        <img src='imagens/setabaixo.gif' id='toggledepartamentos' border='0'>
        </legend>        
      </a>     
      <table id="tbDepartamentos" style="display: none;">
          <?
          // $aux = new cl_arquivo_auxiliar;
          $aux->cabecalho = "<strong>Departamentos</strong>";
          $aux->codigo = "coddepto"; //chave de retorno da func
          $aux->descr  = "descrdepto";   //chave de retorno
          $aux->nomeobjeto = 'departamentos';
          $aux->funcao_js = 'js_mostra';
          $aux->funcao_js_hide = 'js_mostra1';
          $aux->sql_exec  = "";
          $aux->func_arquivo = "func_db_depart.php";  //func a executar
          $aux->nomeiframe = "db_iframe_db_depart";
          $aux->localjan = "";
          $aux->onclick = "";
          $aux->db_opcao = 2;
          $aux->tipo = 2;
          $aux->top = 0;
          $aux->linhas = 5;
          $aux->vwidth = 440;
          $aux->nome_botao = 'db_lanca_departamento';
          $aux->funcao_gera_formulario();
          ?>
       </table>
       </td>
   </tr>
   <tr >
      <td colspan=2 >
      <table id="tbDivisao" style="display: none;">
      
          <?
          // $aux = new cl_arquivo_auxiliar;
          $aux_divisao->cabecalho = "<strong>Divisão</strong>";
          $aux_divisao->codigo = "t30_codigo"; //chave de retorno da func
          $aux_divisao->descr  = "t30_descr";   //chave de retorno
          $aux_divisao->nomeobjeto = 'divisoes';
          $aux_divisao->funcao_js = 'js_mostraorgao';
          $aux_divisao->funcao_js_hide = 'js_mostraorgao1';
          $aux_divisao->sql_exec  = "";
          $aux_divisao->func_arquivo = "func_db_departdiv.php";  //func a executar
          $aux_divisao->nomeiframe = "db_iframe_db_departdiv";
          $aux_divisao->localjan = "";
          $aux_divisao->onclick = "";
          $aux_divisao->db_opcao = 2;
          $aux_divisao->tipo = 2;
          $aux_divisao->top = 0;
          $aux_divisao->linhas = 5;
          $aux_divisao->vwidth = 400;
          $aux_divisao->nome_botao = 'db_lanca_divisao';
          $aux_divisao->funcao_gera_formulario();
          ?>
       </table>
       </td>
   </tr>
   <?} else {?>
  <tr> 
    <td nowrap align="right" title="<?=@$descrdepto?>"><?db_ancora(@$Lcoddepto,"js_coddepto(true);",1);?></td>
    <td>
    <?
      db_input('coddepto',6,$Icoddepto,true,'text',1," onchange='js_coddepto(false);'");
      db_input('descrdepto',35,$Idescrdepto,true,'text',3,'');
    ?>
    </td>
  </tr>
  <? } ?>
  <!-- Fechamento do if da pesquisa por orgão -->    	
  <?
  if (isset($coddepto)&&$coddepto!=""){
  ?>
  <tr>
    <td nowrap align="right" title="Divisão do Depart.">
    <b> Divisão:</b>   
    </td>
    <td>
      <select name='t33_divisao' OnChange="js_divisao();">
			 <option value=''>Todas</option>
			 <?
			 $result = $cldepartdiv->sql_record($cldepartdiv->sql_query_file(null,
			                                                                 "t30_codigo,
			                                                                  t30_descr",
			                                                                  null,
			                                                                  "t30_depto=$coddepto"
			                                                                 ));
			 for($y = 0;$y < $cldepartdiv->numrows; $y++){
 	  	   db_fieldsmemory($result, $y);
				 if ($t33_divisao == $t30_codigo) {
				  $selected = "SELECTED";
				 } else {
					$selected = "";
				 }
 	  	 ?>
			 <option value=<?=@$t30_codigo?> <?=$selected?>> <?=@$t30_descr?></option>
   		 <?
   		 }
   		 ?>
     </select> 
   </td>
	</tr>	
  <?
  }else{
    db_input('t33_divisao',10,"",true,'hidden',3,'');
  }
  ?>
  <tr>
    <td nowrap align="right" title="Filtro de bens"><b>Filtro de bens:</b></td>
    <td nowrap title="">
    <?
    $matriz = array("G"=>"Geral","I"=>"Intervalo","S"=>"Selecionados"); 
	  db_select("filtro_bens",$matriz,true,1,"onChange='js_filtro_bens();'");
    ?>
    </td>
  </tr>
  <?
  if (isset($filtro_bens) && $filtro_bens == "I" || $t06_pesqorgao == 't'){
  	
  	$display = '';
  	if ($t06_pesqorgao == 't') {
  		$display = 'none';
  	}
  	
  ?>	  
  <tr  style="display: <?=$display; ?>" id="trIntervaloBens"> 
    <td nowrap align="right" title="Intervalo de bens"><b>Intervalo de bens: </b></td>
    <td nowrap title="<?=$Tt52_bem?>">
        <? db_ancora("Inicial","js_pesquisa_bem_ini(true);",1);?>
    <?
    db_input("t52_bem_ini",8,"",true,"text",1,"onchange='js_pesquisa_bem_ini(false);'"); 
    ?>
	  <b>&nbsp;&nbsp;a&nbsp;&nbsp;<? db_ancora("Final","js_pesquisa_bem_fim(true);",1);?></b> 
    <?
    db_input("t52_bem_fim",8,"",true,"text",1,"onchange='js_pesquisa_bem_fim(false);'"); 
    ?>
    </td>
   </tr>
   <?
	}
   ?>
   <?
  if ($t06_pesqorgao == 't'){
  	
  ?>    
  <!-- tr style="display: none;" id="trIntervaloBens"> 
    <td nowrap align="right" title="Intervalo de bens"><b>Intervalo de bens:&nbsp;&nbsp;<? db_ancora("Inicial","js_pesquisa_bem_ini(true);",1);?></b></td>
    <td nowrap title="<?=$Tt52_bem?>">
    <?
    //db_input("t52_bem_ini1",8,"",true,"text",1,"onchange='js_pesquisa_bem_ini(false);'"); 
    ?>
    <b>&nbsp;&nbsp;a&nbsp;&nbsp;<? db_ancora("Final","js_pesquisa_bem_fim(true);",1);?></b> 
    <?
    //db_input("t52_bem_fim1",8,"",true,"text",1,"onchange='js_pesquisa_bem_fim(false);'"); 
    ?>
    </td>
   </tr-->
   <?
  }
   ?>
   
   <tr>
    <td align="right" nowrap title="Características adicionais do bem"><b>Características adicionais do bem:</b></td>
    <td nowrap title="">
    <?
    $matriz = array("S"=>"SIM","N"=>"NÃO"); 
	  db_select("opcao_obs",$matriz,true,1);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right" title="Posição do parágrafo"><b>Posição do parágrafo:</b></td>
    <td nowrap title="">
    <?
    $matriz = array("A"=>"Acima","B"=>"Abaixo"); 
	  db_select("posicao",$matriz,true,1);
    ?>
    </td>
  </tr>
  <tr>
    <td align="right"><b>Período de aquisição:</b></td>
    <td>
    <?
      db_inputdata('dtini',null, null, null, true,'text',1,"");
    ?>
    &nbsp;à&nbsp;
    <?
     db_inputdata('dtfim',null, null, null, true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right" title="Agrupar pôr"><b>Agrupar por:</b></td>
    <td nowrap title="">
    <?
    $matriz = array(1=>"Nenhum", 4=>"Departamento", 5=>"Departamento/Divisão");
    if ($t06_pesqorgao == 't') {
      $matriz = array(1=>"Nenhum", 2=>"Órgão", 3=>"Órgão/Unidade", 4=>"Departamento", 5=>"Departamento/Divisão");
    } 
    db_select("cboAgrupa", $matriz, true, 1);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right" title="Agrupar pôr"><b>Imprimir Valor:</b></td>
    <td nowrap title="">
    <?
    $matriz = array(1=>"Sim", 2=>"Não");
    db_select("cboValor", $matriz, true, 1);
    ?>
    </td>
  </tr>

  <?
  if (isset($filtro_bens) && $filtro_bens == "S"){
 
  ?>
  </tr>
  <tr>
    <td nowrap colspan="2" align="center">
    <table border="0">
    <?
    $aux = new cl_arquivo_auxiliar;
    $aux->cabecalho = "<strong>BENS</strong>";
    $aux->codigo = "t52_bem";     //chave de retorno da func
    $aux->descr  = "t52_descr";   //chave de retorno
    $aux->nomeobjeto = 'bens_sel';
    $aux->funcao_js = 'js_mostrabem';
    $aux->funcao_js_hide = 'js_mostrabem1';
    $aux->sql_exec  = "";
    $aux->func_arquivo = "func_bens.php";  //func a executar 
    $aux->nomeiframe = "db_iframe_bens";
    $aux->localjan = "";
    $aux->onclick = "";
    $aux->db_opcao = 2;
    $aux->tipo = 2;
    $aux->top = null;
    $aux->linhas = 10;
    $aux->vwidth = 400;
		//$aux->mostrar_botao_lancar = false;
		
    $aux->funcao_gera_formulario();
    ?>
    
    </table>
    </td>
  </tr>
    <?
  }

  if ($t06_pesqorgao == 't'){
 // if (true){
  ?>
  </tr>
  <tr>
    <td nowrap colspan="2" align="center">
    <table border="0" id="tbBens" style="display: none;">
    <?
    $aux = new cl_arquivo_auxiliar;
    $aux->cabecalho  = "<strong>BENS</strong>";
    $aux->codigo     = "t52_bem";     //chave de retorno da func
    $aux->descr      = "t52_descr";   //chave de retorno
    $aux->nomeobjeto = 'bens_sel';
    $aux->funcao_js  = 'js_mostrabem';
    $aux->funcao_js_hide = 'js_mostrabem1';
    $aux->sql_exec   = "";
    $aux->func_arquivo = "func_bens.php";  //func a executar
    $aux->nomeiframe = "db_iframe_bens";
    $aux->localjan   = "";
    $aux->onclick    = "";
    $aux->db_opcao   = 2;
    $aux->tipo       = 2;
    $aux->top        = null;
    $aux->linhas     = 5;
    $aux->vwidth     = 400;
    $aux->nome_botao = 'db_lanca_bens';
    $aux->funcao_gera_formulario();
    ?>
    </table>
    </td>
  <tr>
    <td align="right">
    <b>Modelos Ata:</b>
    </td>
    <td>
    <?
      $oDaoModelos = db_utils::getDao("db_documentotemplate");
      $sSql        = $oDaoModelos->sql_query_file(null,"db82_sequencial,db82_descricao",null,"db82_templatetipo = 7");
      $rsSql       = $oDaoModelos->sql_record($sSql);
      
      db_selectrecord('atamodelo',$rsSql,true,1,'');
    ?>
    </td>               
  </tr> 
  <?
	}
  ?>
  <?if ($t06_pesqorgao == 't') { ?>
    
    <tr>
    <td nowrap align="right" title="Assinatura" colspan="2">
      <fieldset class="separator">
        <legend>Assinatura:</legend>
        <?
          db_textarea('assinatura', 3,30, 0,true, $dbhidden = 'text',1 );
        ?>
      </fieldset>
    </td>
  </tr>
    
  <? } ?>
</table>
</fieldset>
    <input name="consultar" type="button" value="Termo de Responsabilidade" onclick="return js_mandadados(1);" >
    <input name="atainventario" type="button" value="Ata de Inventário" onclick="return js_mandadados(2);" >
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_divisao(){
   if (document.form1.filtro_bens.value == "I"){
        document.form1.t52_bem_ini.value = "";
        document.form1.t52_bem_fim.value = "";
   }
   document.form1.submit();
}
function js_filtro_bens(){
  
		  if ($('departamentos')) {
		    
		    if($F('filtro_bens') == "G") {
		      $('trIntervaloBens').style.display = "none";
		      $('tbBens').style.display = "none";
		    }
		    
		    if(($('departamentos').length == 0 && document.form1.filtro_bens.value != "G")) {
		      
		      alert(_M("patrimonial.patrimonio.pat2_bensdepto001.selecione_departamento"));
		      $('filtro_bens').value = "G";
		      return false;
		    } else if ($F('filtro_bens') == "S") {
		      $('tbBens').style.display = "";
		      $('trIntervaloBens').style.display = "none";
		      return false;
		    } else if ($F('filtro_bens') == "I") {
		      $('tbBens').style.display = "none";
		      $('trIntervaloBens').style.display = "";
		      return false;
		    }
		      
		  } else {
		  
		    if ((document.form1.coddepto.value == "" && document.form1.filtro_bens.value != "G")){
			    alert(_M("patrimonial.patrimonio.pat2_bensdepto001.selecione_departamento"));
			    $('filtro_bens').value = "G";
				  return false;
				} else {
			     document.form1.submit();	
				}
		  }
   
  
}
function js_pesquisa_bem_ini(mostra){
  if(mostra==true){
      
      var deptos = false;
      if(document.form1.departamentos){
        var deptos = true;
      }     
  
      if (document.form1.coddepto.value != "" && !deptos) {
           js_OpenJanelaIframe('top.corpo','db_iframe_bens',
                               'func_bens.php?chave_depto='+document.form1.coddepto.value+
                               '&chave_div='+document.form1.t33_divisao.value+
                               '&funcao_js=parent.js_mostrabem1_ini|t52_bem',
                               'Pesquisa',true);
                               
      }else if (document.form1.departamentos.length > 0) {
      
          var query = "";
          if ($('departamentos')) {
			      vir="";
			      listadepartamentos="";
			     
			      for(x = 0 ; x < document.form1.departamentos.length; x++) {
			        listadepartamentos+=vir+document.form1.departamentos.options[x].value;
			        vir=",";
			      } 
			      if (listadepartamentos!="") {   
			        query +='&departamentos=('+listadepartamentos+')';
			      } else {
			        query +='&departamentos=';
			      }
			    }
			      
		      if ($('divisoes')) { 
			      vir="";
			      listadivisoes="";
			     
			      for(x = 0 ; x < document.form1.divisoes.length; x++) {
			        listadivisoes+=vir+document.form1.divisoes.options[x].value;
			        vir=",";
			      } 
			      if (listadivisoes != "") {   
			        query +='&divisoes=('+listadivisoes+')';
			      } else {
			        query +='&divisoes=';
			      }
			    }
			    
			    js_OpenJanelaIframe('top.corpo','db_iframe_bens',
                               'func_bens.php?chave_depto='+
                               '&chave_div='+query+
                               '&funcao_js=parent.js_mostrabem1_ini|t52_bem',
                               'Pesquisa',true);
           
      } 	   
  }else{
    if (document.form1.t52_bem_ini.value != "") {
         js_OpenJanelaIframe('top.corpo','db_iframe_bens',
                             'func_bens.php?chave_coddepto='+document.form1.coddepto.value+
                             '&chave_div='+document.form1.t33_divisao.value+
                             '&funcao_js=parent.js_mostrabem_ini',
                             'Pesquisa',false);
    }	 
  }
}
function js_pesquisa_bem_fim(mostra){
  if(mostra==true){
  
      var deptos = false;
      if(document.form1.departamentos){
        var deptos = true;
      }     
      
      if (document.form1.coddepto.value != "" && !deptos){
           js_OpenJanelaIframe('top.corpo','db_iframe_bens',
                               'func_bens.php?chave_depto='+document.form1.coddepto.value+
                               '&chave_div='+document.form1.t33_divisao.value+
                               '&funcao_js=parent.js_mostrabem1_fim|t52_bem',
                               'Pesquisa',true);
      }else if (document.form1.departamentos.length > 0) {
      
          var query = "";
          if ($('departamentos')) {
            vir="";
            listadepartamentos="";
           
            for(x = 0 ; x < document.form1.departamentos.length; x++) {
              listadepartamentos+=vir+document.form1.departamentos.options[x].value;
              vir=",";
            } 
            if (listadepartamentos!="") {   
              query +='&departamentos=('+listadepartamentos+')';
            } else {
              query +='&departamentos=';
            }
          }
            
          if ($('divisoes')) { 
            vir="";
            listadivisoes="";
           
            for(x = 0 ; x < document.form1.divisoes.length; x++) {
              listadivisoes+=vir+document.form1.divisoes.options[x].value;
              vir=",";
            } 
            if (listadivisoes != "") {   
              query +='&divisoes=('+listadivisoes+')';
            } else {
              query +='&divisoes=';
            }
          }
          
          js_OpenJanelaIframe('top.corpo','db_iframe_bens',
                               'func_bens.php?chave_depto='+
                               '&chave_div='+query+
                               '&funcao_js=parent.js_mostrabem1_fim|t52_bem',
                               'Pesquisa',true);
           
      }      	   
  }else{
    if (document.form1.t52_bem_fim.value != "") {
         js_OpenJanelaIframe('top.corpo','db_iframe_bens',
                             'func_bens.php?chave_coddepto='+document.form1.coddepto.value+
                             '&chave_div='+document.form1.t33_divisao.value+
                             '&funcao_js=parent.js_mostrabem_fim',
                             'Pesquisa',false);
    }	 
  }
}
function js_mostrabem_ini(chave,erro){
   document.form1.t52_bem_ini.value = chave;

   if(erro==true){ 
       document.form1.t52_bem_ini.focus(); 
       document.form1.t52_bem_ini.value = "";
   }
}
function js_mostrabem1_ini(chave1){
  
   document.form1.t52_bem_ini.value = chave1;
   if (document.form1.t52_bem_ini.value > document.form1.t52_bem_fim.value && document.form1.t52_bem_fim.value != "") {
        alert(_M("patrimonial.patrimonio.pat2_bensdepto001.intervalo_inicial_maior_final"));
        document.form1.t52_bem_ini.focus();
        document.form1.t52_bem_ini.value = "";
   }
   
   db_iframe_bens.hide();
}
function js_mostrabem_fim(chave,erro){
   document.form1.t52_bem_fim.value = chave;

   if(erro==true){ 
       document.form1.t52_bem_fim.focus(); 
       document.form1.t52_bem_fim.value = "";
   }
}
function js_mostrabem1_fim(chave1){
   document.form1.t52_bem_fim.value = chave1;
   if (document.form1.t52_bem_fim.value < document.form1.t52_bem_ini.value && 
       document.form1.t52_bem_ini.value != "") {
        alert(_M("patrimonial.patrimonio.pat2_bensdepto001.intervalo_final_menor_inicial"));
	document.form1.t52_bem_fim.focus();
	document.form1.t52_bem_fim.value = "";
   }
   db_iframe_bens.hide();
}
//-------------------------------------------------------------------------------
function js_limpacampos(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      document.form1.elements[i].value = '';
    }
  }
}
function js_consultasani(){
  var vazio = 0;
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      if(document.form1.elements[i].value == ""){
        vazio = 1;
      }else{
	vazio = 0;
	break;
      }
    }
  }
  if(vazio == 1){
    alert(_M("patrimonial.patrimonio.pat2_bensdepto001.preencha_campos"));
    return false;
  }else{
    jan = window.open('','rel',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
}
function js_abreconsulta(chave){
  js_OpenJanelaIframe('','db_iframe_consulta','fis3_consultavist002.php?y70_codvist='+chave,'Pesquisa',true,15);
}

function js_mandadados(tipo){
 var iTipoRelatorio = tipo;
 var query     = "";
 var listabens = "";
 var vir       = "";
 var i;

 var dtini = "";
 var dtfim = "";
 
  if ($F('dtini').trim() != "") {
	  dtini = js_formatar($F('dtini'),'d','');
	}
	
	if ($F('dtfim').trim() != "") {
    dtfim = js_formatar($F('dtfim'),'d','');
  }	
  
  query += "dtini="+dtini;
  query += "&dtfim="+dtfim;
  query += "&cboAgrupar="+$F('cboAgrupa');
  query += "&cboValor="+$F('cboValor');
 // query += "&cboAta="+$F('cboAta');
  
  <? if ($t06_pesqorgao == 't'){ ?>
  if ($('atamodelo').length == 0 && iTipoRelatorio == 2) {
    
    alert(_M("patrimonial.patrimonio.pat2_bensdepto001.nenhum_documento_emissao_ata"));
    return false;
  
    query += "&atamodelo=";
  } else {
    query += "&atamodelo="+$F('atamodelo');
  }
  <? } ?>
  
  if ($('assinatura')) {
    query += "&ass="+encodeURIComponent(tagString($F('assinatura')));
  }
  
  if ($('orgaos')) {
  
    if ($('orgaos')) {
      //Le os itens lançados na combo do orgao
      vir="";
      listaorgaos="";
     
      for (x = 0; x < document.form1.orgaos.length; x++) { 
        listaorgaos+=vir+document.form1.orgaos.options[x].value;
        vir=",";
      }
      if (listaorgaos!="") {  
        query +='&orgaos=('+listaorgaos+')';
      } else {
        query +='&orgaos=';
      }
    }
    
    //Le os itens lançados na combo da unidade
    if ($('unidades')) {
      vir="";
      listaunidades="";
   
      for (x=0; x < document.form1.unidades.length; x++) {
        listaunidades+=vir+document.form1.unidades.options[x].value;
        vir=",";
      } 
      if (listaunidades!="") {  
        query +='&unidades=('+listaunidades+')';
      } else {
        query +='&unidades=';
      }
      
    }
    
    //Le os itens lançados na combo do orgao
    if ($('departamentos')) { 
      vir="";
      listadepartamentos="";
     
      for(x = 0 ; x < document.form1.departamentos.length; x++) {
        listadepartamentos+=vir+document.form1.departamentos.options[x].value;
        vir=",";
      } 
      if (listadepartamentos!="") {   
        query +='&departamentos=('+listadepartamentos+')';
      } else {
        query +='&departamentos=';
      }
      
    }    
    //Le os itens lançados na combo do orgao
    if ($('divisoes')) { 
      vir="";
      listadivisoes="";
     
      for(x = 0 ; x < document.form1.divisoes.length; x++) {
        listadivisoes+=vir+document.form1.divisoes.options[x].value;
        vir=",";
      } 
      if (listadivisoes != "") {   
        query +='&divisoes=('+listadivisoes+')';
      } else {
        query +='&divisoes=';
      }
      
    }    
    
  }

 if (document.form1.filtro_bens.value == "I"){
   if (document.form1.t52_bem_ini.value == "" ||document.form1.t52_bem_fim.value == ""){
	   alert(_M("patrimonial.patrimonio.pat2_bensdepto001.selecione_intervalo_valido"));
	   return false;
   }
 }
 
 if (document.form1.filtro_bens.value == "S"){
    vir="";
    listabens="";
 
	  for(i=0;i < document.form1.bens_sel.length;i++){
	    listabens += vir + document.form1.bens_sel.options[i].value;
	    vir=",";
	  }
 
	  if (listabens == "") {
		 alert(_M("patrimonial.patrimonio.pat2_bensdepto001.selecione_bens"));
		 return false;
	  }
 }

 query += '&depto='       +document.form1.coddepto.value    +
          '&div='        +document.form1.t33_divisao.value +
	        '&opcao_obs='  +document.form1.opcao_obs.value   +
	        '&posicao='    +document.form1.posicao.value     +
	        '&filtro_bens='+document.form1.filtro_bens.value;
	        
 if (document.form1.filtro_bens.value == "I"){
   query += "&t52_bem_ini="+document.form1.t52_bem_ini.value+"&t52_bem_fim="+document.form1.t52_bem_fim.value;
 }

 if (document.form1.filtro_bens.value == "S"){
   query += "&listabens=" + listabens;
 }

  if (iTipoRelatorio == 1) {
	   
	 jan = window.open('pat2_bensdepto002.php?'+query,'',
	                   'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	 jan.moveTo(0,0);
	} else if (iTipoRelatorio == 2) {
	 
	 jan = window.open('pat2_bensata001.php?'+query,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
	
	}
}

function js_coddepto(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_depart',
                          'func_db_depart.php?funcao_js=parent.js_mostracoddepto1|coddepto|descrdepto',
                          'Pesquisa',true);
    }else{
      coddepto = document.form1.coddepto.value;
      if(coddepto!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart',
                            'func_db_depart.php?pesquisa_chave='+coddepto+'&funcao_js=parent.js_mostracoddepto',
                            'Pesquisa',false);
      }else{ 	
	      document.form1.descrdepto.value='';
      } 	
    }
}
  function js_mostracoddepto1(chave1,chave2){
    document.form1.coddepto.value = chave1;
    document.form1.descrdepto.value = chave2;
    db_iframe_db_depart.hide();
    document.form1.submit();
  }
  function js_mostracoddepto(chave,erro){
    document.form1.descrdepto.value = chave; 
    if(erro==true){ 
      document.form1.coddepto.focus(); 
      document.form1.coddepto.value = ''; 
    }else{
    document.form1.submit();
    }
    
  }

//Reescrevendo a função de busca do iframe lança unidades
function js_BuscaDadosArquivounidades(chave){
  
  query="";
  vir="";
  listaorgaos="";
 
  for(x=0;x<document.form1.orgaos.length;x++){
    listaorgaos+=vir+document.form1.orgaos.options[x].value;
    vir=",";
  } 
  if(listaorgaos!=""){
    query +='&orgaos=('+listaorgaos+')';
  }
  
  document.form1.db_lanca_unidade.onclick = '';
  if(chave){
    js_OpenJanelaIframe('','db_iframe_orcunidade',
                        'func_orcunidade.php?funcao_js=parent.js_mostra_uni|o41_unidade|o41_descr'+query,
                        'Pesquisa',true);
  }else{
    
    js_OpenJanelaIframe('','db_iframe_orcunidade',
                        'func_orcunidade.php?pesquisa_chave='+document.form1.o41_unidade.value+
                        '&funcao_js=parent.js_mostra_uni1'+query,
                        'Pesquisa',false);
  }
}
//Reescrevendo a função de busca do iframe lança departamentos
function js_BuscaDadosArquivodepartamentos(chave){
  
  query="";
  vir="";
  listaunidades="";
 
  for(x=0;x<document.form1.unidades.length;x++){
    listaunidades+=vir+document.form1.unidades.options[x].value;
    vir=",";
  } 
  
  vir= "";
  listaorgaos = "";
  for(x=0;x<document.form1.orgaos.length;x++){
    listaorgaos+=vir+document.form1.orgaos.options[x].value;
    vir=",";
  } 
  if (listaunidades.length > 0){ 
    query += '&unidades=('+listaunidades+')';
  }
  if (listaorgaos.length > 0) {
    query +='&orgao='+listaorgaos;
  }
 
  document.form1.db_lanca_departamento.onclick = '';
  if(chave){
    js_OpenJanelaIframe('','db_iframe_db_depart',
                        'func_db_depart.php?funcao_js=parent.js_mostra|coddepto|descrdepto'+query,
                        'Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_depart',
                        'func_db_depart.php?pesquisa_chave='+document.form1.coddepto.value+
                        '&funcao_js=parent.js_mostra1'+query,
                        'Pesquisa',false);
  }
}

function js_BuscaDadosArquivodivisoes(chave){

  query="";
  vir="";
  listadepartamentos="";
 
  for(x=0;x<document.form1.departamentos.length;x++){
    listadepartamentos+=vir+document.form1.departamentos.options[x].value;
    vir=",";
  } 
    
  if (listadepartamentos.length > 0){ 
    query += '&departamentos=('+listadepartamentos+')';
  }
     
  document.form1.db_lanca_divisao.onclick = '';
  if(chave){
    js_OpenJanelaIframe('','db_iframe_departdiv',
                        'func_departdiv.php?funcao_js=parent.js_mostraorgao|t30_codigo|t30_descr'+query,
                        'Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_departdiv',
                        'func_departdiv.php?pesquisa_chave='+document.form1.t30_codigo.value+
                        '&funcao_js=parent.js_mostraorgao1'+query,
                        'Pesquisa',false);
  }
}
function js_mostraorgao(chave,chave1){
  document.form1.t30_codigo.value = chave;
  document.form1.t30_descr.value = chave1;
  db_iframe_departdiv.hide();
  ;
  document.form1.db_lanca_divisao.onclick = js_insSelectdivisoes;
}
function js_mostraorgao1(chave,chave1){
  document.form1.t30_descr.value = chave;
  if(chave1){
    document.form1.t30_codigo.value = '';
    document.form1.t30_codigo.focus();
  }else{
    ;
    document.form1.db_lanca_divisao.onclick = js_insSelectdivisoes;
  }
  db_iframe_departdiv.hide();
}

if($('orgaos')) {
 var aBotao = $$('input[name=db_lanca_departamento]');
 aBotao[0].observe('click', function (){
                                         if ($F('coddepto').trim() != "") {
                                            $('o40_orgao').disabled = true;
                                            $('o40_orgao').style.backgroundColor = "#DEB887";
                                            $$('input[name=db_lanca_orgao]')[0].disabled = true;
                                            $('orgaos').disabled = true;
                                            $('orgaos').style.backgroundColor = "#DEB887";
                                            
                                            $('o41_unidade').disabled = true;
                                            $('o41_unidade').style.backgroundColor = "#DEB887";
                                            $$('input[name=db_lanca_unidade]')[0].disabled = true;
                                            $('unidades').disabled = true;
                                            $('unidades').style.backgroundColor = "#DEB887";
                                            
                                            $('tbDivisao').style.display = '';
                                         }
                                       }
                  ); 

}

var t06_pesqorgao = "<?=$t06_pesqorgao ?>"; 

if (t06_pesqorgao == 't') {
	function js_BuscaDadosArquivobens_sel(chave){
	
	  query="";
	  vir="";
	  listadepartamentos="";
	 
	  for(x=0;x<document.form1.departamentos.length;x++){
	    listadepartamentos+=vir+document.form1.departamentos.options[x].value;
	    vir=",";
	  } 
	    
	  if (listadepartamentos.length > 0){ 
	    query += '&departamentos=('+listadepartamentos+')';
	  }
	
	  document.form1.db_lanca_bens.onclick = '';
	  if(chave){
	    js_OpenJanelaIframe('','db_iframe_bens',
	                        'func_bens.php?funcao_js=parent.js_mostrabem|t52_bem|t52_descr'+'&chave_depto='+
	                        listadepartamentos+'&chave_div='+$F('t33_divisao')+query,'Pesquisa',true);
	  }else{
	    js_OpenJanelaIframe('','db_iframe_bens',
	                        'func_bens.php?pesquisa_chave='+
	                        document.form1.t52_bem.value+'&funcao_js=parent.js_mostrabem1'+'&chave_deptos='+
	                        listadepartamentos+'&chave_divs='+$F('t33_divisao')+query,'Pesquisa',false);
	  }
	}
	function js_mostrabem(chave,chave1){
	  document.form1.t52_bem.value = chave;
	  document.form1.t52_descr.value = chave1;
	  db_iframe_bens.hide();
	  document.form1.db_lanca_bens.onclick = js_insSelectbens_sel;
	}
	function js_mostrabem1(chave,chave1){
	  document.form1.t52_descr.value = chave;
	  if(chave1){
	    document.form1.t52_bem.value = '';
	    document.form1.t52_bem.focus();
	  }else{
	    document.form1.db_lanca_bens.onclick = js_insSelectbens_sel;
	  }
	  db_iframe_bens.hide();
	}
 
} 

function js_escondeOrgao(){

      if ($('tbOrgaos').style.display == 'none') {
        
        $('tbOrgaos').style.display = '';
        $('toggleorgaos').src = 'imagens/seta.gif';
        
      } else {
      
        $('tbOrgaos').style.display = 'none';
        $('toggleorgaos').src = 'imagens/setabaixo.gif';
        
      } 
}

function js_escondeUnidade(){

      if ($('tbUnidades').style.display == 'none') {
        
        $('tbUnidades').style.display = '';
        $('toggleunidades').src = 'imagens/seta.gif';
        
      } else {
      
        $('tbUnidades').style.display = 'none';
        $('toggleunidades').src = 'imagens/setabaixo.gif';
        
      } 
}

function js_escondeDepartamento(){

      if ($('tbDepartamentos').style.display == 'none') {
        
        $('tbDepartamentos').style.display = '';
        $('toggledepartamentos').src = 'imagens/seta.gif';
        
      } else {
      
        $('tbDepartamentos').style.display = 'none';
        $('toggledepartamentos').src = 'imagens/setabaixo.gif';
        
      } 
}

</script>
<script>

$("o40_orgao").addClassName("field-size2");
$("o40_descr").addClassName("field-size7");
$("orgaos").style.width = "100%";

$("o41_unidade").addClassName("field-size2");
$("o41_descr").addClassName("field-size7");
$("unidades").style.width = "100%";

$("coddepto").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("departamentos").style.width = "100%";

$("t30_codigo").addClassName("field-size2");
$("t30_descr").addClassName("field-size7");
$("divisoes").style.width = "100%";

$("t52_bem").addClassName("field-size2");
$("t52_descr").addClassName("field-size7");
$("bens_sel").style.width = "100%";


$("dtini").addClassName("field-size2");
$("dtfim").addClassName("field-size2");
$("t52_bem_ini").addClassName("field-size2");
$("t52_bem_fim").addClassName("field-size2");
$("atamodelo").setAttribute("rel","ignore-css");
$("atamodelo").addClassName("field-size2");
$("atamodelodescr").setAttribute("rel","ignore-css");
$("atamodelodescr").addClassName("field-size9");
</script>