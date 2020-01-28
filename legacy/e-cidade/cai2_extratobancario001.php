<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_cfautent_classe.php"));
require_once(modification("classes/db_saltes_classe.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
$aux_conta	 = new cl_arquivo_auxiliar;
$rotulocampo = new rotulocampo;
$rotulocampo->label("k11_id");
$rotulocampo->label("k13_conta");

$k00_dtoper = date('Y-m-d',db_getsession("DB_datausu"));
$k00_dtoper_dia = date('d',db_getsession("DB_datausu"));
$k00_dtoper_mes = date('m',db_getsession("DB_datausu"));
$k00_dtoper_ano = date('Y',db_getsession("DB_datausu"));


$sEstiloComboBox = "style=width:100px;";
?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	<script>
		function js_relatorio2() {
			var F = document.form1;
			var aData = document.getElementById('datai').value.split("/");
			var datai = aData[2]+'-'+aData[1]+'-'+aData[0];
			aData = document.getElementById('dataf').value.split("/");
			var dataf = aData[2]+'-'+aData[1]+'-'+aData[0];

			var query = "";

			if(document.getElementById('contas')){
				//Le os itens lançados na combo da conta
				vir="";
				listacontas="";

				for(x=0;x<document.form1.contas.length;x++){
					listacontas+=vir+document.form1.contas.options[x].value;
					vir=",";
				}
				if(listacontas!=""){
					query +='conta=('+listacontas+')';
				} else {
					query +='conta=';
				}
			}

			query += "&imprime_historico="+F.imprime_historico.value;
			query += "&imprime_analitico="+F.imprime_analitico.value;
			query += "&totalizador_diario="+F.totalizador_diario.value;
			query += "&somente_contas_com_movimento="+F.somente_contas_com_movimento.value
			query += "&datai="+datai
			query += "&dataf="+dataf;
			query += "&agrupapor="+F.agrupapor.value;
			query += "&receitaspor="+F.receitaspor.value;
			query += "&pagempenhos="+F.pagempenhos.value;
			query += "&imprime_pdf="+F.imprime_pdf.value;
			//query += "&conta="+F.k13_conta.value;
			query += "&somente_contas_bancarias="+F.somente_contas_bancarias.value;

			jan = window.open('cai2_extratobancario002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
			jan.moveTo(0,0);
		}
	</script>
	<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
			marginheight="0"
			onLoad="if(document.form1) document.form1.elements[0].focus()">
<table width="790" border="0" cellpadding="0" cellspacing="0"
			 bgcolor="#5786B2">
	<tr>
		<td width="360">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0"
			 cellpadding="0" align=center style="margin-top: 15px;">
	<tr>
		<td height="430" align="center" valign="top" bgcolor="#CCCCCC">
			<center>
				<fieldset style="width: 430px;">
					<legend><b>Extrato Bancário</b></legend>

					<form name="form1" method="post" action="">
						<table border="0" cellspacing="3" cellpadding="0" align=center>

							<tr>
								<td align="right" nowrap><strong>Data inicial:</strong></td>
								<td nowrap>
									<?=db_inputdata("datai",$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano,true,"text",1); ?>
								</td>
							</tr>


							<tr>
								<td align="right" nowrap><strong>Data final:</strong></td>
								<td nowrap>
									<?=db_inputdata("dataf",$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano,true,"text",1); ?>
								</td>
							</tr>
							<tr>
								<td colspan=2 >
									<?
									// $aux = new cl_arquivo_auxiliar;
									$aux_conta->cabecalho = "<strong>Contas</strong>";
									$aux_conta->codigo = "k13_conta"; //chave de retorno da func
									$aux_conta->descr  = "k13_descr";   //chave de retorno
									$aux_conta->nomeobjeto = 'contas';
									$aux_conta->funcao_js = 'js_mostra_contas';
									$aux_conta->funcao_js_hide = 'js_mostra_contas1';
									$aux_conta->sql_exec  = "";
									$aux_conta->func_arquivo = "func_saltes.php";  //func a executar
									$aux_conta->nomeiframe = "db_iframe_saltes";
									$aux_conta->localjan = "";
									$aux_conta->onclick = "";
									$aux_conta->db_opcao = 2;
									$aux_conta->tipo = 2;
									$aux_conta->top = 0;
									$aux_conta->linhas = 5;
									$aux_conta->vwhidth = 400;
									$aux_conta->nome_botao = 'db_lanca_conta';
									$aux_conta->funcao_gera_formulario();
									?>
								</td>
							</tr>
							<tr>
								<td align="right" nowrap title="<?="Agrupamentos das receitas"?>"><b>Agrupamento das receitas:</b></td>
								<td align="left" nowrap><?
									$x = array(1=>"Analítico",2=>"Pela conta de receita",3=>"Pelos códigos de empenho e receita");
									db_select("agrupapor",$x,true,1);
									?></td>
							</tr>
							<tr>
								<td align="right" nowrap title="<?="Receitas por baixa bancária"?>"><b>Receitas por baixa bancária:</b></td>
								<td align="left" nowrap><?
									$x = array(1=>"Não agrupar pela classificação",2=>"Agrupar pela classificação");
									db_select("receitaspor",$x,true,1);
									?></td>
							</tr>
							<tr>
								<td align="right" nowrap title="<?="Pagamentos de empenhos"?>"><b>Pagamentos de empenhos:</b></td>
								<td align="left" nowrap><?
									$x = array(1=>"Detalhar",2=>"Agrupar");
									db_select("pagempenhos",$x,true,1, $sEstiloComboBox);
									?></td>
							</tr>
							<tr>
								<td nowrap align=right><b>Somente contas com movimento:</b></td>
								<td><? $matriz = array("n"=>"Não","s"=>"Sim");
									$somente_contas_com_movimento = "s";
									db_select("somente_contas_com_movimento", $matriz,true,1, $sEstiloComboBox);
									?></td>
							</tr>

							<tr>
								<td nowrap align=right><b>Totalizador diário:</b></td>
								<td><? $matriz = array("s"=>"Sim","n"=>"Não");
									db_select("totalizador_diario", $matriz,true,1, $sEstiloComboBox);
									?></td>
							</tr>
							<tr>
								<td nowrap align=right><b>Imprime histórico:</b></td>
								<td><? $matriz = array("s"=>"Sim","n"=>"Não");
									db_select("imprime_historico", $matriz,true,1, $sEstiloComboBox);
									?></td>
							</tr>
							<tr>
								<td nowrap align=right><b>Tipo Impressão:</b></td>
								<td><? $matriz = array("a"=>"Analítico","s"=>"Sintético");
									db_select("imprime_analitico", $matriz,true,1, $sEstiloComboBox);
									?>
								</td>
							</tr>
							<tr>
							</tr>
							<tr>
								<td nowrap align="right"><b>Somente contas bancárias:</b></td>
								<td><?
									$matriz = array("s"=>"Sim","n"=>"Não");
									db_select("somente_contas_bancarias",$matriz,true,1, $sEstiloComboBox);
									?></td>
							</tr>
							<tr>
								<td nowrap align="right"><b>Formato do relatório:</b></td>
								<td><?
									$matriz = array("p"=>"PDF","t"=>"CSV");
									db_select("imprime_pdf",$matriz,true,1, $sEstiloComboBox);
									?></td>
							</tr>
						</table>
					</form>
				</fieldset>
			</center>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top"><input name="imprimir" type="button"
																					 id="imprimir" onClick="js_relatorio2()" value="Imprimir"></td>
	</tr>
</table>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>