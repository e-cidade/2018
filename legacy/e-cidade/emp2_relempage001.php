<?php
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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_empage_classe.php");

$clempage = new cl_empage;

$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e60_numemp");
$clrotulo->label("e81_codmov");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_codemp");
$clrotulo->label("e50_codord");
$clrotulo->label("e83_codtipo");


?>
<html>
<head>
  <?php 
  	db_app::load('scripts.js, estilos.css');
  ?>
</head>

<body bgcolor="#CCCCCC">
<form name="form1" id="form1">

	<fieldset style="width: 400px; margin: 25px auto 10px auto">
		<legend><strong>Gerar Relatório</strong></legend>
		
		<table align="center">
			<tr>
				<td>
					<strong>
					<?php 
						db_ancora("Agenda:","js_empage(true);",$db_opcao);  
					?>
					</strong>
				</td>
				
				<td>
					<?php
						db_input('e80_codage',10 ,@$e80_codage, true, 'text', 1, "onchange='js_empage(false);'");
					?>
				</td>
			</tr>
			
			<tr>
				<td>
					<strong>Período:</strong>
				</td>
				
				<td>
					<?php
						db_inputdata('dPeriodoInicial', null, null, null, true, 'text', 1)					
					?>
					até
					<?php
						db_inputdata('dPeriodoFinal', null, null, null, true, 'text', 1)					
					?>
				</td>
			</tr>
			
			<tr>
				<td>
					<strong>Tipo:</strong>
				</td>
				
				<td>
					<?php 
						$aTipos = array("c"=>"Dados da Conta","e"=>"Dados do Empenho");
						db_select('tipo', $aTipos, true, 4, "style='width: 150px'");
					?>
				</td>
			</tr>
			
			<tr>
				<td>
					<strong>Impressão Por:</strong>
				</td>
				<td>
					<?
						$aImpressaoPor = array("t"=>"Conta pagadora","r"=>"Recurso");
						db_select('form', $aImpressaoPor, true, 4, "style='width: 150px'");
					?>
				</td>
			</tr>
			
		</table>
		
	</fieldset>
	
	<div style="text-align: center">
		<input name="consultar" type="button" value="Gerar Relatório" onclick="js_consultar();"> 			
	</div>
	
	<?
		db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	?>
	
<script>
function js_empage(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empage','func_empage.php?funcao_js=parent.js_mostra|e80_codage','Pesquisa',true);
  }else{
    codage =  document.form1.e80_codage.value;  
    if(codage != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_empage','func_empage.php?pesquisa_chave='+codage+'&funcao_js=parent.js_mostra02','Pesquisa',false);
    }
  }    
}
function js_mostra(codage){
  db_iframe_empage.hide();
  document.form1.e80_codage.value =  codage;  
}

function js_mostra02(chave,erro){
  if(erro==true){ 
    document.form1.e80_codage.focus(); 
    document.form1.e80_codage.value = ''; 
  }
}

function js_consultar(){
	
  sQuery = '1=1';
  
  oForm  = document.form1;

  if ((oForm.e80_codage.value == '') && (oForm.dPeriodoInicial.value == '' && oForm.dPeriodoFinal.value == '')) {
		alert('Nenhum filtro informado para geração do relatório.');
		return false;
  }   
  
  
  sQuery += "&iCodigoAgenda="  + oForm.e80_codage.value;
  sQuery += "&tipo="           + oForm.tipo.value;
  sQuery += "&form="           + oForm.form.value;
  sQuery += "&dPeriodoInicial="+ oForm.dPeriodoInicial.value;
  sQuery += "&dPeriodoFinal="  + oForm.dPeriodoFinal.value;
  
  jan    = window.open('emp2_relempage002.php?query='+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  
  jan.moveTo(0,0);
    

}
</script>
</form>
</body>
</html>