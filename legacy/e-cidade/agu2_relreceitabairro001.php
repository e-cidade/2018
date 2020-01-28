<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");

$ComboArqAuxiliar = new cl_arquivo_auxiliar ();

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
	
  var ano = document.form1.ano.value;
  var mes = document.form1.mes.value;
  var queryString      = "";
  var listabairro      = "";
  var listareceita     = "";
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
  if (document.form1.receita.length > 0)
  {
    if(queryString != "") queryString = queryString+"&";
    
    for(x = 0; x < document.form1.receita.length; x++)
    {
        listareceita += vir + document.form1.receita.options[x].value;
        vir               = ",";
    }
    queryString      += "listareceita="+listareceita;
  }  

  if(queryString != '') queryString = queryString+"&";
	  
  if((document.form1.ano.value == '') || (document.form1.mes.value == '')) {
	  alert("Informe o exercício e mês para o relatório.");
	  return false;
  }    

  jan = window.open('agu2_relreceitabairro002.php?'+queryString+'ano='+ano+'&mes='+mes, '', 'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 ');

}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"	marginheight="0" onLoad="a=1" bgcolor="#cccccc">
	<form name="form1">
		<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
			<tr>
				<td width="360" height="18">&nbsp;</td>
				<td width="263">&nbsp;</td>
				<td width="25">&nbsp;</td>
				<td width="140">&nbsp;</td>
			</tr>
		</table>

		<fieldset style="width: 400px; margin: 20px auto 0 auto;">
			<legend>
				<strong>Relat&oacute;rio de receitas por bairros</strong>
			</legend>
			<table align="center">
				<tr>
					<td><table align="left">
							<tr>
								<td><strong>Exerc&iacute;cio:</strong></td>
								<td>
								<select name="ano" >
								<?  $ano = date('y',db_getsession("DB_datausu"));
								    $mes = date('m',db_getsession("DB_datausu"));
								    $mes = ( $mes == 1 ? 12 : $mes -1 );
								    $ano = ( $mes == 12 ? $ano -1 : $ano );
								    $sqlano    = "select min(x22_exerc) as anoini, 
 		                                                 max(x22_exerc) as anofim 
 		                                            from aguacalc;";
								    $resultano = db_query($sqlano) or die($sqlano);
								    $oAnos   = db_utils::fieldsMemory($resultano,0,false,false,false);								  
								    for($i=$oAnos->anofim;$i >= $oAnos->anoini;$i--){
								    	echo "<option value=$i".($ano==$i?" selected=selected":"").">$i</option>\n";
								    }								  
								?>
								</select>
								</td>
								</tr>
								<tr>
								<td><strong>M&ecirc;s:</strong></td>
								<td>
								<?  $meses = array( "1"=>"Janeiro",
										            "2"=>"Feveireiro",
										            "3"=>"Março",
										            "4"=>"Abril",
										            "5"=>"Maio",
										            "6"=>"Junho",
										            "7"=>"Julho",
										            "8"=>"Agosto",
										            "9"=>"Setembro",
										            "10"=>"Outubro",
										            "11"=>"Novembro",
										            "12"=>"Dezembro");								    
								    db_select("mes",$meses,true,$db_opcao,"","","","","");
								?>																
								</td>
							</tr>
						</table></td>
				</tr>

				<tr>
					<td><?
					$ComboArqAuxiliar->cabecalho = '<strong>Bairros</strong>';
					$ComboArqAuxiliar->codigo = 'j13_codi'; // chave de retorno da func
					$ComboArqAuxiliar->descr = 'j13_descr'; // chave de retorno
					$ComboArqAuxiliar->nomeobjeto = 'bairro';
					$ComboArqAuxiliar->funcao_js = 'js_mostra_bairro';
					$ComboArqAuxiliar->funcao_js_hide = 'js_mostra_bairro1';
					$ComboArqAuxiliar->func_arquivo = 'func_bairro.php'; // func a executar
					$ComboArqAuxiliar->nomeiframe = 'db_iframe_bairro';
					$ComboArqAuxiliar->nome_botao = 'db_lanca_bairro';
					$ComboArqAuxiliar->db_opcao = 2;
					$ComboArqAuxiliar->tipo = 2;
					$ComboArqAuxiliar->top = 0;
					$ComboArqAuxiliar->linhas = 4;
					$ComboArqAuxiliar->vwidth = 450;
					$ComboArqAuxiliar->tamanho_campo_descricao = 26;
					$ComboArqAuxiliar->Labelancora = 'Bairro:';
					$ComboArqAuxiliar->funcao_gera_formulario ();
					?></td>
				</tr>

				<tr>
					<td><?
					$ComboArqAuxiliar->cabecalho = '<strong>Receitas</strong>';
					$ComboArqAuxiliar->codigo = 'x25_receit'; // chave de retorno da func
					$ComboArqAuxiliar->descr = 'x25_descr'; // chave de retorno
					$ComboArqAuxiliar->nomeobjeto = 'receita';
					$ComboArqAuxiliar->funcao_js = 'js_mostra_receita';
					$ComboArqAuxiliar->funcao_js_hide = 'js_mostra_receita1';
					$ComboArqAuxiliar->func_arquivo = 'func_aguaconsumotipo.php'; // func a executar
					$ComboArqAuxiliar->nomeiframe = 'db_iframe_aguaconsumotipo';
					$ComboArqAuxiliar->nome_botao = 'db_lanca_receita';
					$ComboArqAuxiliar->db_opcao = 2;
					$ComboArqAuxiliar->tipo = 2;
					$ComboArqAuxiliar->top = 0;
					$ComboArqAuxiliar->linhas = 4;
					$ComboArqAuxiliar->vwidth = 450;
					$ComboArqAuxiliar->tamanho_campo_descricao = 25;
					$ComboArqAuxiliar->Labelancora = 'Receita:';
					$ComboArqAuxiliar->funcao_gera_formulario ();						
					?></td>
				</tr>

				<tr>
					<td align="center"><input type="button"
						name="processar" id="processar" value="Processar"
						onclick="js_emite()" /></td>
				</tr>
			</table>
		</fieldset>
		<?
		db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?></form>
</body>
</html>