<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

/**
 * Carregamos as libs necessárias
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
/**
 * Instância do componente que cria a pesquisa de departamentos
 */
$oComponenteDepartamentos = new cl_arquivo_auxiliar;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>

	<form id="form1" name="form1">

	<fieldset style="margin-top:25px; margin-bottom:10px; width:290px;">
	
		<legend><strong>Filtro para Relatório de Solicitações Liberadas</strong></legend>
		
		<table width="100%">
		
			<tr>
				<td width="15%"><strong>Data: </strong></td>
				<td>
					<?php db_inputdata('data_inicial', "", "", "", true, 'text', 1); ?>
					<strong>Até:</strong>
					<?php db_inputdata('data_final', "", "", "", true, 'text', 1); ?>
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
					<?php 
					  /**
					   * Montamos o componente de seleção de departamentos
					   */
					  $oComponenteDepartamentos->cabecalho      = "<strong>Departamento</strong>";
					  $oComponenteDepartamentos->codigo         = "coddepto";
					  $oComponenteDepartamentos->descr          = "descrdepto";
					  $oComponenteDepartamentos->nomeobjeto     = "departamento";
					  $oComponenteDepartamentos->funcao_js      = "js_mostra_departamento";
					  $oComponenteDepartamentos->funcao_js_hide = "js_mostra_departamento1";
					  $oComponenteDepartamentos->func_arquivo   = "func_db_depart.php";
					  $oComponenteDepartamentos->nomeiframe     = "db_iframe_db_depart";
					  $oComponenteDepartamentos->db_opcao       = 2;
					  $oComponenteDepartamentos->tipo           = 2;
					  $oComponenteDepartamentos->top            = 0;
					  $oComponenteDepartamentos->linhas         = 5;
					  $oComponenteDepartamentos->vwidth         = 400;
					  $oComponenteDepartamentos->nome_botao     = "db_lanca";
					  $oComponenteDepartamentos->fieldset       = false;
					  $oComponenteDepartamentos->funcao_gera_formulario();
					?>
				</td>
			</tr>
			<tr>
				<td><strong>Situação: </strong></td>
				<td>
					<select name="selectSituacao" id="selectSituacao">
						<option value="1">Liberadas</option>
						<option value="2">Não Liberadas</option>
						<option value="3">Todas</option>
					</select>
				</td>
			</tr>
		
		</table>
	
	</fieldset>
	<input type="button" id="btnImprimir" name="btnImprimir" onclick="js_emiteRelatorio()" value="Imprimir">
	</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

/**
 * Função que retorna os departamentos selecionados no componente
 */
function js_recuperaDepartamentosSelecionados() {

  iQuantDepartamentos = $('departamento').lenght;
  if (iQuantDepartamentos > 0) {
    return true;
  }
  return false;
}

/**
 * Retorna os departamentos selecionados no componente
 */
function js_getDepartamentosSelecionados() {

  iDptos = document.getElementById("departamento").length;
  aValoresDpto = new Array();
  for (var i = 0; i < iDptos; i++) {
    aValoresDpto.push(document.getElementById("departamento")[i].value);
  }
  return aValoresDpto;
}

/**
 * Função que valida os dados informados informados no formulário
 */
function js_validaForm() {

  if ($('data_inicial').value.trim() === "") {
    
    alert("Favor informar uma data.");
    return false;
  }

	/**
	 * Validação da data se a mesma for informada
	 */
  if ($('data_inicial').value.trim() !== "") {

    if ($('data_final').value.trim() !== "") {

      if (js_comparadata($F('data_inicial'), $F('data_final'), ">")) {

        alert("O intervalo de datas informado é decrescente. Favor informar um crescente.");
        return false;
      }
    } else {
      $('data_final').value = $('data_inicial').value;
    }
  }
  
  /**
   * Valida se foi adicionado algum filtro
   */
  if ($('data_inicial').value.trim() === "" && js_recuperaDepartamentosSelecionados()){
    if (!confirm("Você solicitou um relatório sem filtros. Este procedimento pode ser lento. Deseja continuar a emissão?")) {
      return false;
    }
  }
  return true;
}

/**
 * Função que envia os dados e exibe o relatório
 */
function js_emiteRelatorio() {

  if (js_validaForm()) {

    var aDepartamentosSelecionados = js_getDepartamentosSelecionados();
    var aDepartamentos = Object.toJSON(aDepartamentosSelecionados);
    var sDataInicial   = $F('data_inicial');
    var sDataFinal     = $F('data_final');
    var iSituacao      = $('selectSituacao').value;
    var sFiltros       = 'sDataInicial='+sDataInicial+'&sDataFinal='+sDataFinal;
    		sFiltros      += '&aDepartamentos='+aDepartamentos+'&iSituacao='+iSituacao;
    var jan  = window.open('pat2_solicitacoesliberadas002.php?'+sFiltros, '',
                           'width='+(screen.availWidth-5)+',height='+
                           (screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
}
</script>