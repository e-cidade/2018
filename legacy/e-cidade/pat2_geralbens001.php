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
include("classes/db_bens_classe.php");
include("libs/db_utils.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_cfpatri_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_departdiv_classe.php");
include("libs/db_app.utils.php");

$clrotulo 			= new rotulocampo;
$cldb_depart		= new cl_db_depart;
$clcfpatric 		= new cl_cfpatri;
$clbens					=	new cl_bens;
$cldepartdiv 		= new cl_departdiv;
$aux_orgao 			= new cl_arquivo_auxiliar;
$aux_unidade 		= new cl_arquivo_auxiliar;
$aux 						= new cl_arquivo_auxiliar;

$clrotulo->label("t04_sequencial");
$clbens->rotulo->label();
$cldb_depart->rotulo->label();

db_postmemory($HTTP_POST_VARS);

//Verifica se utiliza pesquisa por orgão sim ou não
$t06_pesqorgao = "f";
$resPesquisaOrgao	= $clcfpatric->sql_record($clcfpatric->sql_query_file(null,'t06_pesqorgao'));
if($clcfpatric->numrows > 0) {
	db_fieldsmemory($resPesquisaOrgao,0);
}
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
<legend>Relatórios - Geral de Bens</legend>
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
          $aux_orgao->vwhidth = 400;
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
          $aux_unidade->vwhidth = 400;
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
          $aux->vwhidth = 400;
          $aux->nome_botao = 'db_lanca_departamento';
          $aux->funcao_gera_formulario();
        	?>
        </table>
      </fieldset>  
      </td>
   </tr>
   <?} else {?>
   <tr> 
    <td align="right" nowrap title="<?=$Tcoddepto?>"> <? db_ancora(@$Lcoddepto,"js_pesquisa_depart(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("coddepto",10,$Icoddepto,true,"text",4,"onchange='js_pesquisa_depart(false);'"); 
         db_input("descrdepto",50,$Idescrdepto,true,"text",3);  
      ?> 
    </td>
   </tr>
   <?}?>
   
   <?
    if (isset($coddepto)&&$coddepto!=""){
    ?>
  	<tr>
    	<td nowrap align="right" title="Divisão do Depart.">
    	<b> Divisão:</b>   
    	</td>
    	<td>
    	    <select name="t33_divisao" id="t33_divisao">
				<option value='0'>Todas</option>
				<?
				$result=$cldepartdiv->sql_record($cldepartdiv->sql_query_file(null,"t30_codigo,t30_descr",null,"t30_depto=$coddepto"));
				for($y=0;$y<$cldepartdiv->numrows;$y++){
 	  				db_fieldsmemory($result,$y);
 	  			?>
					<option value=<?=@$t30_codigo?>> <?=@$t30_descr?></option>
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
   
  <tr id="datas">
    <td nowrap align="right"><b>Ordem:</b></td>
    <td nowrap>
    <?
    if($t06_pesqorgao == 't'){
       	$matriz = array("depart"=>"Departamento","placa"=>"Placa","bem"=>"Cód. Bem","classi"=>"Classificação",
      "data"=>"Data de aquisição","orgao"=>"Órgão","unidade"=>"Unidade","descricao"=>"Descrição do Bem");
      }else{
      	$matriz = array("depart"=>"Departamento","placa"=>"Placa","bem"=>"Cód. Bem","classi"=>"Classificação",
      "data"=>"Data de aquisição","descricao"=>"Descrição do Bem");
      }
      
      db_select("ordenar",$matriz,true,1,"onChange='js_filtro_ordem(this.value);'");
    ?>
    </td>
  </tr>
  <tr id="datas">
    <td nowrap><b>Imprimir Fornecedor/Observações:</b></td>
    <td nowrap>
    <?
      $imp_forn = array("N"=>"Não","S"=>"Sim");
      db_select("imp_forn",$imp_forn,true,1);
    ?>
    </td>
  </tr>
  <tr id="datas">
    <td nowrap align="right"><b>Imprimir Classificação:</b></td>
    <td nowrap>
    <?
      $imp_classi = array("N"=>"Não","S"=>"Sim");
      db_select("imp_classi",$imp_classi,true,1,'onchange=js_display_quebra_por(this.value)');
    ?>
    </td>
  </tr>
  <tr id="quebrapor" style="display:none;">
    <td nowrap align="right"><b>Quebrar por:</b></td>
    <td nowrap>
    <?
      $quebra_por = array("1"=>"Nenhum","2"=>"Departamento/Divisão","3"=>"Classificação");
      db_select("quebra_por",$quebra_por,true,1);
    ?>
    </td>
  </tr>
  
  <tr id="datas">
      <td nowrap align="right"><b>Quebrar página:</b></td>
      <td nowrap>
      <?
      if($t06_pesqorgao == 't'){
       	$q_pagina = array("N"=>"Não","orgao"=>"Órgão","unidade"=>"Unidade","departamento"=>"Departamento");
      }else{
      	$q_pagina = array("N"=>"Não","S"=>"Sim");
      }
      db_select("q_pagina",$q_pagina,true,1);
      ?>
      </td>
  </tr>
                                    
     <tr id="datas" >
       <td nowrap align="right"><b>Aquisição em:</b>
       <td nowrap>
       <?
          db_inputdata("data_inicial","","","",true,"text",4);
       ?>&nbsp;<b>a</b>&nbsp;
       <?
          db_inputdata("data_final","","","",true,"text",4);
       ?>
       </td>
     </tr>
     <tr >
      <td nowrap align="right"><b>Bens de Convênio:</b></td>
      <td nowrap>
      <?
      	$x = array("T"=>"Todos","N"=>"Nenhum Convênio","S"=>"Com Convênio");
        db_select("bens_convenio",$x,true,1,"onchange='js_selecionaConvenio()'");
      ?>
      </td>
  </tr>
     <tr id="convenio" style="display: none;">
    <td nowrap title="<?="Convênio"?>" align="right">
       <?
       db_ancora("<b>Convênio:</b>","js_pesquisat04_sequencial(true);",1);
       ?>
    </td>
    <td> 
			<?
			db_input('t04_sequencial',8,$It04_sequencial,true,'text',1," onchange='js_pesquisat04_sequencial(false);'")
			?>
	    <?
			db_input('z01_nome_convenio',20,'',true,'text',3,'')
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
function js_selecionaConvenio(){
	if(document.getElementById('bens_convenio').value == "N" || document.getElementById('bens_convenio').value == "T"){
		document.getElementById('convenio').style.display = "none";
	}else{
		document.getElementById('convenio').style.display = "";
	}
}

function js_filtro_ordem(opcao){
   var obj = document.form1;
   if (opcao == "classi"){
        obj.imp_classi.value = "S";
   }

   if (opcao == "data"){
        document.getElementById("datas").style.display = "";          
   } else {
//        document.getElementById("datas").style.display = "none";          
   }
}
function js_emite(){
   var query        = "";
   var data_inicial = "";
   var data_final   = "";

   if (document.getElementById("datas").style.display == ""){
        if (document.form1.data_inicial != undefined && document.form1.data_final != undefined) {
          if (document.form1.data_inicial.value != ""){
               data_inicial = new String(document.form1.data_inicial.value);
          }
          if (document.form1.data_final.value != ""){
               data_final   = new String(document.form1.data_final.value);
          }
        }else{
          if (document.form1.data_inicial_dia.value != ""){
               data_inicial = new String(document.form1.data_inicial_dia.value+"/"+document.form1.data_inicial_mes.value+"/"+document.form1.data_inicial_ano.value);
          }
          if (document.form1.data_final_dia.value != ""){
               data_final   = new String(document.form1.data_final_dia.value+"/"+document.form1.data_final_mes.value+"/"+document.form1.data_final_ano.value);
          }
        }
       
        if (data_inicial != "" || data_final != ""){
             if (data_inicial != ""){
                  var vet_data_inicial = data_inicial.split("/");
                  data_inicial         = vet_data_inicial[2]+"-"+vet_data_inicial[1]+"-"+vet_data_inicial[0];
             }
             if (data_final != ""){
                  var vet_data_final   = data_final.split("/");
                  data_final           = vet_data_final[2]+"-"+vet_data_final[1]+"-"+vet_data_final[0];
             }

             if (data_inicial != "" && data_final != ""){
                  if (data_inicial > data_final){
                       alert(_M("patrimonial.patrimonio.pat2_geralbens001.data_inicial_maior_data_final"));
                       exit;
                  }
             }
        
             query = "data_inicial="+data_inicial+"&data_final="+data_final+"&";
        } 
   }
   
   query += "ordenar="+document.form1.ordenar.value+"&imp_forn="+document.form1.imp_forn.value+"&imp_classi="+document.form1.imp_classi.value+"&q_pagina="+document.form1.q_pagina.value;
   if($('coddepto')==''){
   	query+="&coddepart="+0;
   }else{
   	query+="&coddepart="+document.form1.coddepto.value;
   }
   
   if($('orgaos')){
	    //Le os itens lançados na combo do orgao
			vir="";
		 	listaorgaos="";
		 
		 	for(x=0;x<document.form1.orgaos.length;x++){
		  	listaorgaos+=vir+document.form1.orgaos.options[x].value;
		  	vir=",";
		 	}
			if(listaorgaos!=""){ 	
				query +='&orgaos=('+listaorgaos+')';
			} else {
				query +='&orgaos=';
			}
		}
		
		//Le os itens lançados na combo da unidade
		if($('unidades')){
			vir="";
	 		listaunidades="";
	 
		 	for(x=0;x<document.form1.unidades.length;x++){
		  	listaunidades+=vir+document.form1.unidades.options[x].value;
		  	vir=",";
		 	} 
		 	if(listaunidades!=""){ 	
				query +='&unidades=('+listaunidades+')';
			} else {
				query +='&unidades=';
			}
		 	
		}
		
	 	//Le os itens lançados na combo do orgao
	 	if($('departamentos')){	
			vir="";
		 	listadepartamentos="";
		 
		 	for(x=0;x<document.form1.departamentos.length;x++){
		  	listadepartamentos+=vir+document.form1.departamentos.options[x].value;
		  	vir=",";
		 	} 
		 	if(listadepartamentos!=""){ 	
				query +='&departamentos=('+listadepartamentos+')';
			} else {
				query +='&departamentos=';
			}
		 	
   	}
   
   if($('t04_sequencial').value != ""){
   	query +='&conv='+$('t04_sequencial').value;
   }
   
   if($('imp_classi').value=='N'){
   	query +='&quebra_por=0';
   }else{
   	query +='&quebra_por='+$('quebra_por').value;
   }
 
 		if($('t33_divisao').value != null && $('t33_divisao').value != ""){
 			query +='&divisao='+$('t33_divisao').value;
 		}else{
 			query +='&divisao='
 		} 
 		
 		query +="&bens_convenio="+$('bens_convenio').value;
  
   jan = window.open('pat2_geralbens002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
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
  	js_OpenJanelaIframe('','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostra_uni|o41_unidade|o41_descr'+query,'Pesquisa',true);
  }else{
  	
    js_OpenJanelaIframe('','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o41_unidade.value+'&funcao_js=parent.js_mostra_uni1'+query,'Pesquisa',false);
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
	if (listaorgaos.length > 0)	{
 	  query +='&orgao='+listaorgaos;
	}
 
  document.form1.db_lanca_departamento.onclick = '';
  if(chave){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostra|coddepto|descrdepto'+query,'Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.coddepto.value+'&funcao_js=parent.js_mostra1'+query,'Pesquisa',false);
  }
}

function js_pesquisat04_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_benscadcedente','func_benscadcedente.php?funcao_js=parent.js_mostraconvenio1|t04_sequencial|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.t04_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_benscadcedente','func_benscadcedente.php?pesquisa_chave='+document.form1.t04_sequencial.value+'&funcao_js=parent.js_mostraconvenio','Pesquisa',false);
     }else{
       document.form1.z01_nome_convenio.value = ''; 
     }
  }
}

function js_mostraconvenio(chave,erro){
	//alert(chave);
	//document.getElementById('z01_nome').value = 'teste';
  document.form1.z01_nome_convenio.value = chave; 
  if(erro==true){ 
    document.form1.t04_sequencial.focus(); 
    document.form1.t04_sequencial.value = ''; 
  }
}

function js_mostraconvenio1(chave1,chave2){
	document.form1.t04_sequencial.value = chave1;
  document.form1.z01_nome_convenio.value = chave2;
  db_iframe_benscadcedente.hide();
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

function js_display_quebra_por(valor){
	var valor = valor;
	
	if(valor == 'N'){
		document.getElementById('quebrapor').style.display = 'none';
	}else{
		document.getElementById('quebrapor').style.display = '';
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

$("t04_sequencial").addClassName("field-size2");
$("z01_nome_convenio").addClassName("field-size7");
$("data_inicial").addClassName("field-size2");
$("data_final").addClassName("field-size2");

</script>