<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require 'dbforms/db_funcoes.php';
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_frmExportaArquivos();">
<form name='form1'>
	<table width="790" style="padding-top:25px;" align="center">
		<tr>
			<td>
				<fieldset>
					<legend><b>Filtros</b></legend>
					<table>
					<tr>
						<td><?php db_ancora('<b>Código usuário</b>',"js_pesquisat08_sequencial(true);",1); ?></td>
						<td><?php db_input('t08_sequencial',10,"",true,'text',1," onchange='js_pesquisat08_sequencial(false);'"); ?></td>
						<td><?php db_input('nome',40,"",true,'text',3,''); ?></td>
					</tr>
					<tr>
						<td><?php db_ancora('<b>Código Departamento</b>',"js_pesquisacoddepto(true);",1); ?></td>
						<td><?php db_input('coddepto',10,"",true,'text',1," onchange='js_pesquisacoddepto(false);'"); ?></td>
						<td><?php db_input('nomedepto',40,"",true,'text',3,''); ?></td>
					</tr>					
					</table>	
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend><b>Exportar Arquivos</b></legend>
					<div id="frmArquivos">
					</div>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" value="Processar" onclick="js_envExportaArquivos();" >
			</td>
		</tr>
	</table>
</form>
<?
 db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
<script type="text/javascript">
	function js_frmExportaArquivos(){
		oDBGridExportaArquivos = new DBGrid('exporta');
		oDBGridExportaArquivos.nameInstance = 'oDBGridExportaArquivos';
		oDBGridExportaArquivos.setCheckbox(0);
		oDBGridExportaArquivos.setHeader(new Array('Arquivo','Tipo','strNome'));
		oDBGridExportaArquivos.setHeight(330);
		oDBGridExportaArquivos.aHeaders[3].lDisplayed = false;
		//oDBGridExportaArquivos.allowSelectColumns(true);
		//oDBGridExportaArquivos.setCellWidth(new Array(100,100));
		oDBGridExportaArquivos.show($('frmArquivos'));
		
		var aLinha = new Array();
		var Tipo0 = '<select id="sltEmpresa" style="width:30%"><option value="0">Completo</option><option value="1">Atualiza</option></select>';
		var Tipo1 = 'Completo';
		var Tipo2 = 'Completo';
		var Tipo3 = 'Completo';
		var Tipo4 = 'Completo';
		var Tipo5 = 'Completo';
		var Tipo6 = 'Completo';
		var Tipo7 = 'Completo';
		var Tipo8 = 'Completo';
		var Tipo9 = 'Completo';
		var Tipo10 = 'Completo';
		var Tipo11 = 'Completo';
		var Tipo12 = 'Completo';
		var Tipo13 = '<select id="sltBem" style="width:30%"><option value="0">Completo</option><option value="1">Atualiza</option></select>';
		var Tipo14 = '<select id="sltHistorico" style="width:30%"><option value="0">Completo</option><option value="1">Atualiza</option></select>';
		var Tipo15 = 'Completo';
		
		aLinha[0] = new Array('EMPRESA',Tipo0,'empresa');
		aLinha[1] = new Array('CCUSTO',Tipo1,'ccusto');
		aLinha[2] = new Array('ESP&Eacute;CIE',Tipo2,'especie');
		aLinha[3] = new Array('ESTADO',Tipo3,'estado');
		aLinha[4] = new Array('FAM&Iacute;LIA',Tipo4,'familia');
		aLinha[5] = new Array('FORNECEDOR',Tipo5,'fornecedor');
		aLinha[6] = new Array('LOCALIZA&Ccedil;&Atilde;O',Tipo6,'localizacao');
		aLinha[7] = new Array('MARCAS',Tipo7,'marcas');
		aLinha[8] = new Array('MEDIDA',Tipo8,'medida');
		aLinha[9] = new Array('MODELO',Tipo9,'modelo');
		aLinha[10] = new Array('OCORR&Ecirc;NCIA',Tipo10,'ocorrencia');
		aLinha[11] = new Array('SETOR',Tipo11,'setor');
		aLinha[12] = new Array('USU&Aacute;RIO',Tipo12,'usuario');
		aLinha[13] = new Array('BEM',Tipo13,'bem');
		aLinha[14] = new Array('HIST&Oacute;RICO',Tipo14,'historico');
		aLinha[15] = new Array('PAR&Acirc;METROS',Tipo15,'parametros');
		
		
		oDBGridExportaArquivos.clearAll(true);
		for(i=0;i < aLinha.length; i++){
			oDBGridExportaArquivos.addRow(aLinha[i]);
		}
		oDBGridExportaArquivos.renderRows();
	}
	
	function js_envExportaArquivos(){
		if($(t08_sequencial).value == ""){
			alert('Nenhum usuário selecionado !');
			return false;
		}
		var aSelecionados = new Array;
		var aParametros   = new Array;
		aSelecionados =  oDBGridExportaArquivos.getSelection();
		if(aSelecionados.length > 0){
			for(i=0; i < aSelecionados.length; i++){
			
			  var obj   = new Object();
			  obj.nome  = encodeURIComponent(aSelecionados[i][3]);
			  obj.valor = aSelecionados[i][2];
			  aParametros.push(obj);
			  
				// alert(aSelecionados[i][0]+'-'+aSelecionados[i][1]+'-'+aSelecionados[i][2]);
			}
			//obj.nome 	= encodeURIComponent('idusuario');
			var idusuario = $('t08_sequencial').value;
			var coddepto  = $('coddepto').value;
			//aParametros.push(obj);
			
			sQueryString =  "["+aParametros.toSource().substr(1,aParametros.toSource().length-2)+"]";
			//var sQueryString = aParametros.toSource().substr(1,aParametros.toSource().length-2));
			
			//var sQueryString = aParametros.toSource().urlEncode();
			var sUrl         = 'pat4_criteriumexportaarquivos002.php?aParametros='+sQueryString+'&idusuario='+idusuario+'&coddepto='+coddepto;
    	js_OpenJanelaIframe('top.corpo','db_iframe_exporta',sUrl,'Pesquisa',true);

      //location.href = sUrl;

		}else{
			alert('Nenhum arquivo selecionado !');
		}
	}
	
function js_pesquisat08_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_usuariocriterium.php?funcao_js=parent.js_mostradb_usuarios1|t08_sequencial|nome','Pesquisa',true);
  }else{
     if(document.form1.t08_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_usuariocriterium.php?pesquisa_chave='+document.form1.t08_sequencial.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.t08_sequencial.focus(); 
    document.form1.t08_sequencial.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
	
  document.form1.t08_sequencial.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}

function js_pesquisacoddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.coddepto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.nomedepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.nomedepto.value = chave; 
  if(erro==true){ 
    document.form1.coddepto.focus(); 
    document.form1.coddepto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
	
  document.form1.coddepto.value = chave1;
  document.form1.nomedepto.value = chave2;
  db_iframe_db_depart.hide();
}
</script>

</html>