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
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("std/label/rotulo.php");
include ("classes/db_pagordem_classe.php");
include ("classes/db_pagordemconta_classe.php");
include ("classes/db_pagordemnota_classe.php");
include ("classes/db_pagordemrec_classe.php");
include ("classes/db_pagordemele_classe.php");
include ("classes/db_empempenho_classe.php");
include ("classes/db_empelemento_classe.php");
include ("classes/db_empnota_classe.php");
include ("classes/db_empord_classe.php");
include ("classes/db_empnotaele_classe.php");
include ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);
//db_postmemory($HTTP_SERVER_VARS,2);

$clpagordem = new cl_pagordem;
$clpagordemconta = new cl_pagordemconta;
$clpagordemnota = new cl_pagordemnota;
$clpagordemrec = new cl_pagordemrec;
$clpagordemele = new cl_pagordemele;
$clempempenho = new cl_empempenho;
$clempelemento = new cl_empelemento;
$clempnota = new cl_empnota;
$clempord = new cl_empord;
$clempnotaele = new cl_empnotaele;

$db_opcao = 22;
$db_botao = false;
$ngera = false;
//alterar_02 é gerado depois que é verificado se o valor da ordem não é maior do que o das receitas
if (isset ($alterar) || isset ($alterarimp)) {
	$sqlerro = false;
	db_inicio_transacao();
	if ($vlrpag != $vlrdis) {
		$ngera = true;
	}
	$sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = ".db_getsession("DB_anousu");
	$res = db_query($sql);

	$clpagordem->e50_codord = $e50_codord;
	$clpagordem->e50_numemp = $e50_numemp;
	$clpagordem->e50_data = date("Y-m-d", db_getsession("DB_datausu"));
	$clpagordem->e50_obs = $e50_obs;
	$clpagordem->alterar($e50_codord);
	if ($clpagordem->erro_status == 0) {
		$sqlerro = true;
		$erro_msg = $clpagordem->erro_msg;
	} else {
		$e50_codord = $clpagordem->e50_codord;
		$ok_msg = $clpagordem->erro_msg;
	}

	//*************altera conta do credor**********************************************/
	if ($sqlerro == false) {
		//rotina que traz os dados do pagordem
		$result = $clpagordemconta->sql_record($clpagordemconta->sql_query($e50_codord, "z01_numcgm as numcgm_of"));
		if ($clpagordemconta->numrows > 0) {
			db_fieldsmemory($result, 0);
			//       echo 'CGM   : '.$z01_numcgm2;
			if ((isset ($z01_numcgm2) && $z01_numcgm2 != '') && $z01_numcgm2 != $numcgm_of) {
				//         echo "\n\n".'CGM1   : '.$z01_numcgm2;
				$clpagordemconta->e49_codord = $e50_codord;
				$clpagordemconta->e49_numcgm = $z01_numcgm2;
				$clpagordemconta->alterar($e50_codord);
				if ($clpagordemconta->erro_status == 0) {
					$sqlerro = true;
					$erro_msg = $clpagordemconta->erro_msg;
				}
			} else if ($z01_numcgm2 == "") {
				$clpagordemconta->e49_codord = $e50_codord;
				$clpagordemconta->excluir($e50_codord);
				if ($clpagordemconta->erro_status == 0) {
					$sqlerro = true;
					$erro_msg = $clpagordemconta->erro_msg;
				}
			}
		} else if (isset ($z01_numcgm2) && $z01_numcgm2 != '') {
			$clpagordemconta->e49_codord = $e50_codord;
			$clpagordemconta->e49_numcgm = $z01_numcgm2;
			$clpagordemconta->incluir($e50_codord);
			if ($clpagordemconta->erro_status == 0) {
				$sqlerro = true;
				$erro_msg = $clpagordemconta->erro_msg;
			}
		}
	}
	//*************altera conta do credor**********************************************/

	if ($vlrpag > 0) {
		if ($sqlerro == false && isset ($apagarec)) {
			$clpagordemrec->e52_codord = $e50_codord;
			$clpagordemrec->excluir($e50_codord);
			$erro_msg = $clpagordemrec->erro_msg;
			if ($clpagordemrec->erro_status == 0) {
				$sqlerro = true;
			}
		}

		//rotina que altera em pagordemele
		if ($sqlerro == false) {
			$arr_dados = split("#", $dados);
			$tam = count($arr_dados);
			for ($i = 0; $i < $tam; $i ++) {
				$arr_ele = split("-", $arr_dados[$i]);
				$elemento = $arr_ele[0];

				if (isset ($chaves) && $chaves != '') {
					$vlrord = '0.00';
					$vlrord_nota = $arr_ele[1];
				} else {
					$vlrord = $arr_ele[1];
					$vlrord_nota = '0.00';
				}

				//===========================
				//rotina que atualiza o valor pago para somar com o que o usuario digitar
				$result = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord, $elemento));
				if ($clpagordemele->numrows > 0) {
					db_fieldsmemory($result, 0);
					$tem = true;
				} else {
					$e53_valor = '0';
					$e53_vlranu = '0';
					$e53_vlrpag = '0';
				}
				//==================

				//==================================================
				//rotina que pega os valores de pagordemele
				$result02 = $clpagordemele->sql_record($clpagordemele->sql_query(null, null, "e60_numemp,sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu", "", "e60_numemp=$e50_numemp and e53_codele=$elemento group by e60_numemp"));
				if ($clpagordemele->numrows > 0) {
					db_fieldsmemory($result02, 0);
				} else {
					$tot_vlrpag = '0.00';
					$tot_vlranu = '0.00';
					$tot_valor = '0.00';
				}
				//==============================================

				//=================
				//dados do empelemento
				$result09 = $clempelemento->sql_record($clempelemento->sql_query_file($e50_numemp, $elemento, "sum(e64_vlrliq) as total_vlrliq ,sum(e64_vlrpag) as total_vlrpag,sum(e64_vlranu) as total_vlranu"));
				db_fieldsmemory($result09, 0);
				//=============

				//==============================================
				//rotina que traz os dados do empnotaele
				$sql = $clempnotaele->sql_query_ordem(null, null, " e70_codele,e71_anulado,sum(e70_valor) as tot_valor_nota, sum(e70_vlrliq) as tot_vlrliq_nota, sum(e70_vlranu) as tot_vlranu_nota", "", "e69_numemp=$e50_numemp and e70_codele=$elemento and e70_vlrliq <> 0 and ((e71_codnota is  null) or (e71_codnota is not null and e71_anulado='t') ) group  by e70_codele,e71_anulado ");
				$result65 = $clempnotaele->sql_record($sql);
				if ($clempnotaele->numrows > 0) {
					db_fieldsmemory($result65, 0);
				} else {
					$tot_valor_nota = '0.00';
					$tot_vlrliq_nota = '0.00';
					$tot_vlranu_nota = '0.00';
				}
				//==================================================================================

				//=======================================================================================>
				//rotina que procura pega o total de ordens com  notas
				$sql = $clempnotaele->sql_query_ordem(null, null, " sum(e70_valor) as tot_valornord, sum(e70_vlrliq) as tot_vlrliqnord, sum(e70_vlranu) as    tot_vlranunord", "", "e71_codord=$e50_codord and  e70_codele=$elemento  and e70_vlrliq <> 0  and ((e71_codnota is  not  null  and e71_anulado='f') ) ");
				$result = $clempnotaele->sql_record($sql);
				db_fieldsmemory($result, 0, true);
				$tot_vlrliqnord = $tot_vlrliqnord - $tot_vlranunord;

				/*
				$notas_vlrliq_semordem  =  $tot_vlrliqnord;
				$notas_vlranu_semordem  =  $tot_vlranunord;
				$notas_vlrliq_comordem  = $tot_valor  - $tot_vlrliqnord;
				$notas_vlrau_comordem   = $tot_vlranu - $tot_vlranunord;
				*/
				//=====================================================>

				//valor disponivel com notas
				$vlrdis_nota = $tot_valor_nota - $tot_vlranu_nota;

				//valor disponivel sem notas
				$vlrdis = ($total_vlrliq - $total_vlrpag) - ($tot_valor - $tot_vlranu - $tot_vlrpag) - ($vlrdis_nota);

				//valores da ordem atualmente
				$saldo_ordem_nota = $tot_valornord - $tot_vlranunord;
				$saldo_ordem = ($e53_valor - $e53_vlranu - $e53_vlrpag) - $saldo_ordem_nota;

				//   os valores que serão alterados
				$val_alt_ord_nota = $vlrord_nota;

				$val_alt_ord = $vlrord;

				//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%5
				//testa valores com notas..
				if ($val_alt_ord_nota > $vlrdis_nota) {
					$sqlerro = true;
					$erro_msg = " Valor da nota  $vlrord_nota do elemento $elemento não está disponivel. Verifique!";
					break;
				}

				//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
				//	verifica os valores sem notas
				if ($val_alt_ord > $vlrdis) {
					$sqlerro = true;
					$erro_msg = " Valor $vlrord do elemento $elemento não está disponivel. Verifique!";
					break;
				}
				//===================

				$valor = number_format($vlrord + $vlrord_nota + $e53_valor, "2", ".", "");

				if ($valor == '' || $valor == 0) {
					$valor = '0';
				}
				$clpagordemele->e53_codord = $e50_codord;
				$clpagordemele->e53_codele = $elemento; //$arr[0] contem o elemento
				$clpagordemele->e53_valor = "$valor";
				$clpagordemele->alterar($e50_codord, $elemento);
				$erro_msg = $clpagordemele->erro_msg;
				if ($clpagordemele->erro_status == 0) {
					$sqlerro = true;
				}
			}
		}
		//rotina pega as notas marcadas para atualizar os valores liquidados da notas
		if ($sqlerro == false && isset ($chaves) && $chaves != '') {
			$arr_notas = split("#", $chaves);
			$tam = count($arr_notas);
			for ($i = 0; $i < $tam; $i ++) {
				$nota = $arr_notas[$i];

				$clpagordemnota->e71_codord = $e50_codord;
				$clpagordemnota->e71_codnota = $nota;
				$clpagordemnota->e71_anulado = "false";

				$clpagordemnota->sql_record($clpagordemnota->sql_query_file($e50_codord, $nota, "e71_codord"));
				if ($clpagordemnota->numrows > 0) {
					$clpagordemnota->alterar($e50_codord, $nota);
				} else {
					$clpagordemnota->incluir($e50_codord, $nota);
				}

				$erro_msg = $clpagordemnota->erro_msg;
				if ($clpagordemnota->erro_status == 0) {
					$sqlerro = true;
					break;
				}
			}
		}
	}
	db_fim_transacao($sqlerro);

	$db_opcao = 2;
	$db_botao = true;
}
if (isset ($chavepesquisa) || isset ($e50_codord)) {
	if (isset ($chavepesquisa)) {
		$e50_codord = $chavepesquisa;
	}

	$db_opcao = 2;
	$db_botao = true;

	//rotina que traz os dados do pagordem
	$result = $clpagordem->sql_record($clpagordem->sql_query($e50_codord));
	db_fieldsmemory($result, 0);

	//rotina que traz os dados do pagordem
	$result = $clpagordemconta->sql_record($clpagordemconta->sql_query($e50_codord, "z01_numcgm as z01_numcgm2,z01_nome as z01_nome2"));
	if ($clpagordemconta->numrows > 0) {
		db_fieldsmemory($result, 0);
	}

	//verifica se ja naum tem agenda
	$res_agenda = $clempord->sql_record($clempord->sql_query(null, $e50_codord, "e81_codage"));
	if ($clempord->numrows > 0) {
    db_fieldsmemory($res_agenda,0);
		//$db_opcao = 3;
		$db_botao = false;
		$agendado = true;
	}
	//-=========================================--------------

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <?if(isset($agendado)){?>
     <tr>
       <td align='center'>
         	<b><font color='red'>
             <?
                $tam = strlen($e81_codage) + 2;
             ?>
             Esta Ordem de Pagamento pertence a Agenda <?=db_formatar($e81_codage,"s","0",$tam,"e",0)?>.<br>
             Você deve removê-la para efetuar a alteração.<br>
             Acesse o menu Procedimentos >> Manutenção de Agenda.
          </font></b>
       </td>
     </tr>
     <tr><td>&nbsp;</td></tr>
  <?}?>
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?


include ("forms/db_frmpagordem.php");
?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?


if (isset ($alterar) || isset ($alterarimp)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
		if ($clpagordem->erro_campo != "") {
			echo "<script> document.form1.".$clpagordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clpagordem->erro_campo.".focus();</script>";
		}
	} else {
		if (isset ($alterarimp)) {
			echo "
			             <script> js_imprimir(); </script>
			      ";
		}
		db_msgbox($ok_msg);
	}
}
if (isset ($e50_codord)) {
	if ($desabilita == true) {
		//    $query = "&db_opcaoal=33";
	} else {
		$query = '';
	}
	echo "
	           <script>
		    function js_bloqueia(){
		        parent.document.formaba.pagordemrec.disabled=false;\n
		        top.corpo.iframe_pagordemrec.location.href='emp1_pagordemrec001.php?e52_codord=".$e50_codord."".@$query."';\n
		    }
		    js_bloqueia();
		 </script>
	       ";

}

if ($db_opcao == 22 || $db_opcao == 33) {
	echo "<script>js_pesquisa_ordem();</script>\n";
}
?>