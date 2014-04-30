<?
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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

$ComboArqAuxiliar = new cl_arquivo_auxiliar();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>

<script><!--
function js_emite(){
	
  var dataIniD = document.form1.datainicio_dia.value;
  var dataIniM = document.form1.datainicio_mes.value;   
  var dataIniA = document.form1.datainicio_ano.value;

  var dataFimD = document.form1.datafim_dia.value;
  var dataFimM = document.form1.datafim_mes.value;
  var dataFimA = document.form1.datafim_ano.value;
  var queryString      = "";
  var listabairro      = "";
  var listazonaentrega = "";
  var vir = "";

  if (document.form1.bairro.length > 0)
  {
		if(queryString != "") queryString = queryString+"&";
	 
		for(x = 0; x < document.form1.bairro.length; x++)
		{
			listabairro += vir + document.form1.bairro.options[x].value;
			vir          = ",";
		}
		queryString += "listabairro="+listabairro;
  }	
  vir = "";
  if (document.form1.zona_entrega.length > 0)
  {
    if(queryString != "") queryString = queryString+"&";
    
    for(x = 0; x < document.form1.zona_entrega.length; x++)
    {
        listazonaentrega += vir + document.form1.zona_entrega.options[x].value;
        vir               = ",";
    }
    queryString      += "listazonaentrega="+listazonaentrega;
  }  

  if(queryString != '') queryString = queryString+"&";
	  
  if((document.form1.datainicio.value == '') || (document.form1.datafim.value == '')) {
	  alert("Informe a data inicial e data final do relatório.");
	  return false;
  }    

  var orderBy  = document.form1.orderBy.value;

  jan = window.open('agu2_matricbaix002.php?'+queryString+'datainicial='+dataIniA+'-'+dataIniM+'-'+dataIniD+'&datafinal='+dataFimA+'-'+dataFimM+'-'+dataFimD+'&orderby='+orderBy, '', 'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 ');

}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1">
<table width="790" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#5786B2">
	<tr>
		<td width="360" height="18">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
</table>

<fieldset style="width: 400px; margin: 20px auto 0 auto;"><legend> <strong>Relat&oacute;rio
de Matr&iacute;culas Baixadas</strong> </legend>
<table align="center">
	<tr>
		<td><strong>Per&iacute;odo:</strong></td>
		<td><?
		db_inputdata("datainicio", null, null, null, true, "text", 1);
		?> at&eacute; <?
		db_inputdata("datafim", null, null, null, true, "text", 1);
		?></td>
	</tr>

	<tr>
		<td><?
		$ComboArqAuxiliar->cabecalho      = '<strong>Bairros</strong>';
		$ComboArqAuxiliar->codigo         = 'j13_codi'; //chave de retorno da func
		$ComboArqAuxiliar->descr          = 'j13_descr';   //chave de retorno
		$ComboArqAuxiliar->nomeobjeto     = 'bairro';
		$ComboArqAuxiliar->funcao_js      = 'js_mostra_bairro';
		$ComboArqAuxiliar->funcao_js_hide = 'js_mostra_bairro1';
		$ComboArqAuxiliar->func_arquivo   = 'func_bairro.php';  //func a executar
		$ComboArqAuxiliar->nomeiframe     = 'db_iframe_bairro';
		$ComboArqAuxiliar->nome_botao     = 'db_lanca_bairro';
		$ComboArqAuxiliar->db_opcao       = 2;
		$ComboArqAuxiliar->tipo           = 2;
		$ComboArqAuxiliar->top            = 0;
		$ComboArqAuxiliar->linhas         = 4;
		$ComboArqAuxiliar->vwidth       = 450;
		$ComboArqAuxiliar->funcao_gera_formulario();
		?></td>
	</tr>

	<tr>
		<td><?
		$ComboArqAuxiliar->cabecalho      = '<strong>Zona de Entrega</strong>';
		$ComboArqAuxiliar->codigo         = 'j85_codigo'; //chave de retorno da func
		$ComboArqAuxiliar->descr          = 'j85_descr';   //chave de retorno
		$ComboArqAuxiliar->nomeobjeto     = 'zona_entrega';
		$ComboArqAuxiliar->funcao_js      = 'js_mostra_zona_ent';
		$ComboArqAuxiliar->funcao_js_hide = 'js_mostra_zona_ent1';
		$ComboArqAuxiliar->func_arquivo   = 'func_iptucadzonaentrega.php';  //func a executar
		$ComboArqAuxiliar->nomeiframe     = 'db_iframe_zona_ent';
		$ComboArqAuxiliar->nome_botao     = 'db_lanca_zona_ent';
		$ComboArqAuxiliar->db_opcao       = 2;
		$ComboArqAuxiliar->tipo           = 2;
		$ComboArqAuxiliar->top            = 0;
		$ComboArqAuxiliar->linhas         = 4;
		$ComboArqAuxiliar->vwidth        = 450;
		$ComboArqAuxiliar->tamanho_campo_descricao = 18;
		$ComboArqAuxiliar->funcao_gera_formulario();
		?></td>
	</tr>

	<tr>
		<td><strong>Ordenar por:</strong></td>
		<td><?
		$orderBy = array("1"=>"Matr&iacute;cula", "2"=>"CGM", "3"=>"Nome", "4"=>"Data Baixa", "5"=>"Logradouro");
		db_select("orderBy", $orderBy, true, 2, "style='width: 200px;'");
		?></td>
	</tr>
	<tr>
		<td align="center" colspan="2"><input type="button" name="processar"
			id="processar" value="Processar" onclick="js_emite()" /></td>
	</tr>
</table>
</fieldset>

		<?
		db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?></form>
</body>
</html>