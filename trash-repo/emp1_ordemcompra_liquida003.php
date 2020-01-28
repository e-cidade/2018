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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_libcontabilidade.php");

include ("dbforms/db_funcoes.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_matparam_classe.php");

include ("classes/db_matordem_classe.php");
include ("classes/db_matordemitem_classe.php");
include ("classes/db_matordemmail_classe.php");

include ("classes/db_empelemento_classe.php");
include ("classes/db_empempenho_classe.php");
include ("classes/db_empempitem_classe.php");

include ("classes/db_empnota_classe.php");
include ("classes/db_empnotaele_classe.php");
include ("classes/db_empnotaord_classe.php");

include ("classes/db_conlancam_classe.php");
include ("classes/db_conlancamcompl_classe.php");
include ("classes/db_conlancamele_classe.php");
include ("classes/db_conlancamnota_classe.php");
include ("classes/db_conlancamcgm_classe.php");
include ("classes/db_conlancamemp_classe.php");
include ("classes/db_conlancamdoc_classe.php");
include ("classes/db_conlancamdot_classe.php");
include ("classes/db_conlancamval_classe.php");

include("classes/db_pagordem_classe.php");
include("classes/db_pagordemele_classe.php");
include("classes/db_pagordemnota_classe.php");
include("classes/db_pagordemval_classe.php");
include("classes/db_pagordemrec_classe.php");
include("classes/db_pagordemtiporec_classe.php");
include("libs/db_utils.php");
include("libs/db_libdocumento.php");

include ("classes/empenho.php"); // funções para empenhar,liquidar,etc...

$clmatparam = new cl_matparam;
$clmatordem = new cl_matordem;
$clmatordemitem = new cl_matordemitem;
$clempempenho = new cl_empempenho;
$clempempitem = new cl_empempitem;
$clcgm = new cl_cgm;
$clmatordemmail = new cl_matordemmail;
$clempnota = new cl_empnota;
$clempnotaele = new cl_empnotaele;
$clempnotaord = new cl_empnotaord;

$clpagordem = new cl_pagordem;
$clpagordemele = new cl_pagordemele;
$clpagordemnota = new cl_pagordemnota;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$valor_total = 0;
$sqlerro = false;

if (isset ($valores) && isset ($incluir)) {
	if (isset($m51_obs) && $m51_obs!=""){
		 $historico = $m51_obs;
	} else {
	   	$historico = "Sem historico";
	}		
		
	/*
	/// matriz de reteções
    $m_retencoes = array();
    if (strpos($retencoes,"|") || strpos($retencoes,";")   ){
      $m_lista = explode("|",$retencoes);  
      for ($x=0;$x < sizeof($m_lista);$x++){
         $v = explode(";",$m_lista[$x]);
         $m_retencoes[$v[0]] = $v["1"];
      }  
    }  
    ///
	*/
	db_inicio_transacao();
	
	

	$dados = split("quant_", "$valores"); //  [empenho|sequencial| iNA|quantidade ]	
	$valordoitem = split("valor_", "$val"); // [NA|empenho|sequencial|valor_digitado] 

	for ($i = 1; $i < sizeof($dados); $i ++) {
		if ($sqlerro == false) {
			$numero = split("_", $dados[$i]);
			$numemp = $numero[0];
			$sequen = $numero[1];
			$quanti = $numero[3];

			$vlsoitem = split("_", $valordoitem[$i]);
			$vl_soma_item = $vlsoitem[3];
			$vl_soma_item = str_replace(",", ".", "$vl_soma_item");
			//  $valor_total+=$vl_soma_item;
		}
		$result_valuni = $clempempitem->sql_record($clempempitem->sql_query_file($numemp, $sequen));
		if ($clempempitem->numrows != 0) {
			db_fieldsmemory($result_valuni, 0);
			$valuni = $e62_vltot / $e62_quant;
			$valitem = $valuni * $quanti;
			$valor_total += $valitem; // valot total do ítem;
		}
	}
	$m51_data = "$m51_data_ano-$m51_data_mes-$m51_data_dia";

	////// fim notas 
	$clmatordem->m51_data = $m51_data;
	$clmatordem->m51_depto = $coddepto;
	$clmatordem->m51_numcgm = $e60_numcgm;
	$clmatordem->m51_obs = $m51_obs;
	//$clmatordem->m51_valortotal = $valor_total;
	$clmatordem->m51_valortotal = $vl_soma_item;
	$clmatordem->incluir(null);
	if ($clmatordem->erro_status == 0) {
		$sqlerro = true;
		db_msgbox($clmatordem->erro_msg);
	}
	// empnota + empnotaordem
	$data_nota = $e69_dtnota_ano.'-'.$e69_dtnota_mes.'-'.$e69_dtnota_dia;
	if (sizeof($data_nota) < 9) {
		$data_nota = date("Y-m-d", db_getsession("DB_datausu"));
	}
	$clempnota->e69_codnota = null;
	$clempnota->e69_numero = $e69_numero; //  character varying(20) 
	$clempnota->e69_numemp = $numemp; // integer                default 0
	$clempnota->e69_id_usuario = db_getsession("DB_id_usuario");
	$clempnota->e69_dtnota = $data_nota; //| date                  |
	$clempnota->e69_dtrecebe = date("Y-m-d", db_getsession("DB_datausu"));
	$clempnota->incluir($clempnota->e69_codnota);
	if ($clempnota->erro_status == 0) {
		$sqlerro = true;
		db_msgbox($clempnota->erro_msg);
	}
	// empnotaele		
	$clempnotaele->e70_codnota = $clempnota->e69_codnota;
	$clempnotaele->e70_codele = $e62_codele;
	$clempnotaele->e70_valor = $vl_soma_item;
	$clempnotaele->e70_vlranu = '0';
	$clempnotaele->e70_vlrliq = '0'; // o update é feito pela classe empenho
	$clempnotaele->incluir($clempnotaele->e70_codnota, $clempnotaele->e70_codele);
	if ($clempnotaele->erro_status == 0) {
		$sqlerro = true;
		db_msgbox($clempnotaele->erro_msg);
	}
	$clempnotaord->m72_codordem = $clmatordem->m51_codordem;
	$clempnotaord->m72_codnota = $clempnotaele->e70_codnota;
	$clempnotaord->incluir($clempnotaord->m72_codnota, $clempnotaord->m72_codordem);
	if ($clempnotaord->erro_status == 0) {
		$sqlerro = true;
		db_msgbox($clempnotaord->erro_msg);
	}
	////// fim notas 	

	if ($sqlerro == false) {
		if (isset ($manda_mail) && $manda_mail != "") {
			$clmatordemmail->m55_codordem = $clmatordem->m51_codordem;
			$clmatordemmail->m55_email = $z01_email;
			$clmatordemmail->incluir(null);
			if ($clmatordemmail->erro_status == 0) {
				$sqlerro = true;
				$erro_msg = $clmatordemmail->erro_msg;
			}
		}
	}

	for ($i = 1; $i < sizeof($dados); $i ++) {
		if ($sqlerro == false) {
			$numero = split("_", $dados[$i]);
			$numemp = $numero[0];
			$sequen = $numero[1];
			$quanti = $numero[3];
			$vlsoitem = split("_", $valordoitem[$i]);
			$vl_soma_item = $vlsoitem[3];
			$vl_soma_item = str_replace(",", ".", "$vl_soma_item");

			$result_valuni = $clempempitem->sql_record($clempempitem->sql_query_file($numemp, $sequen));
			if ($clempempitem->numrows != 0) {
				db_fieldsmemory($result_valuni, 0);
				$valuni = $e62_vltot / $e62_quant;
				$valitem = $valuni * $quanti;
			}

			$clmatordemitem->m52_codordem = $clmatordem->m51_codordem;
			$clmatordemitem->m52_numemp = $numemp;
			$clmatordemitem->m52_sequen = $sequen;
			$clmatordemitem->m52_quant = $quanti;
			// $clmatordemitem->m52_valor = $valitem;
			$clmatordemitem->m52_valor = $vl_soma_item;
			$clmatordemitem->m52_vlruni = $e62_vlrun;
			$clmatordemitem->incluir(null);
			if ($clmatordemitem->erro_status == 0) {
				$sqlerro = true;
				break;
			}
		}
	}
	// lança ordem de pagamento = nota de liquidação'
	if ($sqlerro == false) {
		$clpagordem->e50_codord = "";
		$clpagordem->e50_numemp = $e60_numemp;
		$clpagordem->e50_data = date("Y-m-d", db_getsession("DB_datausu"));
		$clpagordem->e50_obs = $historico;
		$clpagordem->e50_id_usuario = db_getsession("DB_id_usuario");
		$clpagordem->e50_hora = date("H:m", db_getsession("DB_datausu"));
    $clpagordem->e50_anousu     = db_getsession("DB_anousu");
		$clpagordem->incluir($clpagordem->e50_codord);
		if ($clpagordem->erro_status == 0) {
			$sqlerro = true;
			db_msgbox($clpagordem->erro_msg);
		}
	}

	if ($sqlerro == false) {
		$clpagordemele->e53_codord = $clpagordem->e50_codord;
		$clpagordemele->e53_codele = $e62_codele;
		$clpagordemele->e53_valor = $vl_soma_item;
		$clpagordemele->e53_vlranu = '0.00';
		$clpagordemele->e53_vlrpag = '0.00';
		$clpagordemele->incluir($clpagordemele->e53_codord, $clpagordemele->e53_codele);
		if ($clpagordemele->erro_status == 0) {
			$sqlerro = true;
			db_msgbox($clpagordemele->erro_msg);
		}
	}
	if ($sqlerro == false) {
		$clpagordemnota->e71_codord = $clpagordem->e50_codord;
		$clpagordemnota->e71_codnota = $clempnota->e69_codnota;
		$clpagordemnota->e71_anulado = 'false';
		$clpagordemnota->incluir($clpagordemnota->e71_codord, $clpagordemnota->e71_codnota);
		if ($clpagordemnota->erro_status == 0) {
			$sqlerro = true;
			db_msgbox($clpagordemnota->erro_msg);
		}
	}
    
    /*
	if ($sqlerro == false) {
		foreach ($m_retencoes as $key => $value) {
			$clpagordemrec->e52_codord = $clpagordem->e50_codord;
			$clpagordemrec->e52_receit = $key;
			$clpagordemrec->e52_valor = $value;
			$clpagordemrec->incluir($clpagordem->e50_codord, $key);
			if ($clpagordemrec->erro_status == 0) {
				$sqlerro = true;
				db_msgbox($clpagordemrec->erro_msg);
			}
		} // END FOREACH
	}
    */
	// liquida o empenho
	$clemp = new empenho;
	$teste = $clemp->liquidar($numemp, $e62_codele, $clempnotaele->e70_codnota, $vl_soma_item);
	if ($teste == true) {
		//  echo "<br><Br><Br> res >>  deu certo";
	} else {
		$sqlerro = true;
		db_msgbox($clemp->erro_msg);
	}

	db_fim_transacao($sqlerro);
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
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <?



include ("forms/db_frmmatordemcgm_liquida.php");
?>
    </td>
  </tr>
</table>
<?



if (isset ($incluir)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
		if ($clmatordem->erro_campo != "") {
			echo "<script> document.form1.".$clmatordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clmatordem->erro_campo.".focus();</script>";
		}
	} else {
		echo "
				         <script>
				           if(confirm('Deseja imprimir a ordem de compra?')){
				             jan = window.open('emp2_ordemcompra002.php?cods=".$clmatordem->m51_codordem."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
				             jan.moveTo(0,0);
					      } 
				         </script>
				";
		if (isset ($manda_mail) && $manda_mail != "") {
			
			$headers  = "Content-Type:text/html;";  	  	
		  $objteste = new libdocumento(1750);
		  $corpo    = $objteste->emiteDocHTML();
  	  $mail     = mail($z01_email,"Ordem de Compra Nº $codigo","$corpo",$headers);
			if ($mail==true){
				db_msgbox("E-mail enviado com sucesso!!");  		
			}else{
				db_msgbox("Erro ao enviar e-mail!!E-mail não foi enviado!!");
			}
			
		}
		echo "<script>
					       //   location.href='emp1_ordemcompra001.php?vLiquida=true';
				         </script>
				   ";
	}
}
?>
</body>
</html>