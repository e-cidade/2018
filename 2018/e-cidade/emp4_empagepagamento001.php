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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

require_once ("interfaces/ILancamentoAuxiliar.interface.php");
require_once ("interfaces/IRegraLancamentoContabil.interface.php");
require_once ("libs/db_app.utils.php");

require_once ("model/slip.model.php");
require_once ("model/CgmFactory.model.php");
require_once ("model/CgmBase.model.php");
require_once ("model/CgmJuridico.model.php");
require_once ("model/CgmFisico.model.php");
require_once ("model/Dotacao.model.php");


db_app::import("CgmFactory");
db_app::import("MaterialCompras");
db_app::import("configuracao.*");
db_app::import("contabilidade.*");
db_app::import("caixa.*");
db_app::import("financeiro.*");
db_app::import("caixa.slip.Transferencia");
db_app::import("caixa.slip.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("empenho.*");
db_app::import("exceptions.*");

//------------------------------------------------------
//   Arquivos que verificam se o boletim já foi liberado ou naum
require_once ("classes/db_boletim_classe.php");
$clverficaboletim = new cl_verificaboletim(new cl_boletim);
//------------------------------------------------------

require_once ("classes/db_empageconfche_classe.php");
$clempageconfche = new cl_empageconfche;
require_once ("classes/db_empagepag_classe.php");
$clempagepag = new cl_empagepag;
require_once("classes/db_empagetipo_classe.php");
$clempagetipo = new cl_empagetipo;

//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);

$db_opcao = 1;
$db_botao = false;

if (isset ($atualizar)) {
	//-----------------------------------
	//classes do pagamento de empenho
	require_once ("libs/db_libcontabilidade.php");

	require_once ("libs/db_liborcamento.php");
	require_once ("classes/db_orcdotacao_classe.php");
	require_once ("classes/db_empempenho_classe.php");
	require_once ("classes/db_empelemento_classe.php");
	require_once ("classes/db_pagordem_classe.php");
	require_once ("classes/db_pagordemele_classe.php");

	require_once ("classes/db_cfautent_classe.php");
	$clcfautent = new cl_cfautent;

	$clpagordem = new cl_pagordem;
	$clpagordemele = new cl_pagordemele;
	$clempempenho = new cl_empempenho;
	$clempelemento = new cl_empelemento;
	$clorcdotacao = new cl_orcdotacao;

	require_once ("libs/db_libcaixa.php");
	$clautenticar = new cl_autenticar;

	require_once ("classes/db_empagemov_classe.php");
	$clempagemov = new cl_empagemov;

  require_once ("classes/db_empord_classe.php");
	$clempord = new cl_empord;

	require_once ("classes/db_conlancam_classe.php");
	require_once ("classes/db_conlancamele_classe.php");
	require_once ("classes/db_conlancampag_classe.php");
	require_once ("classes/db_conlancamcgm_classe.php");
	require_once ("classes/db_conparlancam_classe.php");
	require_once ("classes/db_conlancamemp_classe.php");
	require_once ("classes/db_conlancamval_classe.php");
	require_once ("classes/db_conlancamdot_classe.php");
	require_once ("classes/db_conlancamdoc_classe.php");
	require_once ("classes/db_conlancamcompl_classe.php");
	require_once ("classes/db_saltes_classe.php");
	require_once ("classes/db_conplanoreduz_classe.php");
	require_once ("classes/db_conlancamord_classe.php");
	require_once ("classes/db_conlancamlr_classe.php");
  require_once("classes/ordemPagamento.model.php");

	$clconlancam = new cl_conlancam;
	$clconlancamele = new cl_conlancamele;
	$clconlancampag = new cl_conlancampag;
	$clconlancamcgm = new cl_conlancamcgm;
	$clconparlancam = new cl_conparlancam;
	$clconlancamemp = new cl_conlancamemp;
	$clconlancamval = new cl_conlancamval;
	$clconlancamdot = new cl_conlancamdot;
	$clconlancamdoc = new cl_conlancamdoc;
	$clconlancamcompl = new cl_conlancamcompl;
	$clconplanoreduz = new cl_conplanoreduz;
	$clsaltes = new cl_saltes;
	$clconlancamord = new cl_conlancamord;
	$clconlancamlr = new cl_conlancamlr;

	$cltranslan = new cl_translan;
	//final das classes de pagamento de empenho
	///////////////////////////////////////////
	db_inicio_transacao();
	$sqlerro = false;

	$arr_chaves = split("#", $chaves);

	for ($d = 0; $d < count($arr_chaves); $d ++) {
		if ($sqlerro == true) {
			break;
		}
		if ($tipo == 'banco') {
			//e81_codmov,e82_codord,e81_valor
			$arr_dad = split("-", $arr_chaves[$d]);
			$codmov = $arr_dad[0];
			$codord = $arr_dad[1];
			$codigomovimento = $codmov;
			$valor_ger = $arr_dad[2];

			$e83_codtipo = $arr_dad[3];

			//retorna codord
			$e50_codord = $codord;

			//retorna o valor a ser pago
			$e91_valor = $valor_ger;
			$vlrpag = $valor_ger;

			///echo "<br><br><br>valor:".$e91_valor;

			//retorna k13_conta
			$result = $clempagetipo->sql_record($clempagetipo->sql_query($e83_codtipo, "e83_conta"));
			db_fieldsmemory($result, 0);
			$k13_conta = $e83_conta;

			//retorna o numemp
			$result01 = $clpagordem->sql_record($clpagordem->sql_query_emp($e50_codord, "e60_numemp,e60_codemp,e60_anousu"));
			db_fieldsmemory($result01, 0);
			//$e60_numemp =  $e60_numemp;

			//retorna a variavel $dados;
			$result03 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_codele,e64_vlrliq,e64_vlrpag,e64_vlranu,e64_vlremp"));
			$numrows03 = $clempelemento->numrows;
			$dados = '';
			$sep = '';
			$liberado = false;
			$totai = 0;
			for ($e = 0; $e < $numrows03; $e ++) {
				db_fieldsmemory($result03, $e);

				$vlrdis = round($e64_vlrliq, 2) - round($e64_vlrpag, 2);
				if (round($e91_valor, 2) <= round($vlrdis, 2)) {
					$valor = round($e91_valor, 2);
					$liberado = true;
				} else {
					$valor = round($vlrdis, 2);
					$e91_valor = round($e91_valor, 2) - round($vlrdis, 2);
				}
				$dados .= $sep.$e64_codele."-".$valor;
				$totai += $valor;
				$sep = '#';
				if ($liberado == false and $e == ($numrows03 -1)) {
					$sqlerro = true;
					$erro_msg = "O valor da agenda não está  disponivel para ser pago no empenho $e60_codemp/$e60_anousu!\\n Valor da agenda: R$ ".db_formatar($e91_valor, "f")." \\Valor disponível: R$ ".db_formatar($vlrdis, "f");
					break;
				}
			}

			if ($sqlerro == false && $totai != $valor_ger) {
				$sqlerro = true;
				$erro_msg = "O valor da agenda não está fechando com valor disponível!";
			}

			//**********************************************************************************/
			//quando o pagamento for por cheque
		} else
			if ($tipo == 'slip_cheque' || $tipo == 'slip') {
				//---ordem das chaves SLIP_CHE
				//--- e91_codcheque,k17_codigo,e89_codmov,e91_valor
				//---SLIP
				//--- k17_codigo,e89_codmov,k17_instit,k17_valor,e91_codcheque

				$arr_dad = split("-", $arr_chaves[$d]);

				if ($tipo == 'slip_cheque') {
					$e91_codcheque = $arr_dad[0];
				        $codigo = $arr_dad[1];
					$codmov = $arr_dad[2];
				} else {
				        $codigo = $arr_dad[0];
					$codmov = '0';
					$e91_codcheque = '0';
				}
                                $codigomovimento = $codmov;
				$data = date("Y-m-d", db_getsession("DB_datausu"));
				$ip = db_getsession("DB_ip");
				$instit = db_getsession("DB_instit");

				$sql = "select fc_auttransf($codigo,'".$data."','".$ip."',true,$e91_codcheque,".$instit.") as verautenticacao";
				$result03 = db_query($sql);
				if (pg_numrows($result03) == 0) {
					$erro_msg = "Erro ao Autenticar SLIP $numslip!";
					break;
				} else {
					db_fieldsmemory($result03,0);
					if(substr($verautenticacao,0,1) != "1"){
						$erro_msg = $verautenticacao;
						$sqlerro = true;
						break;
					}

  				if (USE_PCASP) {

    				try {

      				$oDaocfautent      = db_utils::getDao('cfautent');
    				  $oDaoSlipTipoOperacao  = db_utils::getDao('sliptipooperacaovinculo');
    				  $sSqlBuscaTipoOperacao = $oDaoSlipTipoOperacao->sql_query_file($codigo);
    				  $rsBuscaTipoOperacao   = $oDaoSlipTipoOperacao->sql_record($sSqlBuscaTipoOperacao);
    				  if ($oDaoSlipTipoOperacao->numrows == 0) {
    				  	throw new Exception("Não foi possível localizar o tipo de operação do slip {$codigo}.");
    				  }
    				  $sSqlAutenticadora = $oDaocfautent->sql_query_file(null,
    				                                                     "k11_id, k11_tipautent",
    				                                                     '',
    				                                                     "k11_ipterm    = '{$ip}'
    				                                                     and k11_instit = ".db_getsession("DB_instit"));
    				  $rsAutenticador    = $oDaocfautent->sql_record($sSqlAutenticadora);

    				  if ($oDaocfautent->numrows == '0') {
    				    throw new Exception("Cadastre o ip {$iIp} como um caixa.");
    				  }
    				  $iCodigoTerminal    = db_utils::fieldsMemory($rsAutenticador, 0)->k11_id;
    				  $iTipoOperacao  = db_utils::fieldsMemory($rsBuscaTipoOperacao, 0)->k153_slipoperacaotipo;
    				  $oTransferencia = TransferenciaFactory::getInstance($iTipoOperacao, $codigo);
    				  $oTransferencia->setDataAutenticacao($data);
    				  $oTransferencia->setIDTerminal($iCodigoTerminal);
    				  $oTransferencia->setNumeroAutenticacao(substr($verautenticacao, 1, 7));
    				  $oTransferencia->executarLancamentoContabil(null, false, $e91_codcheque);

    				} catch (Exception $eErro) {

    				  $sqlerro  = true;
    				  $erro_msg = str_replace("\n", "\\n", $eErro->getMessage());
    				}

  				}
					if (!$sqlerro) {
					  $erro_msg = "Processo concluído com sucesso.";
				  	continue;
					}
				}


				//**********************************************************************************/
				//quando o pagamento for por cheque
			} else

				if ($tipo == 'cheque') {
					$arr_dad = split("-", $arr_chaves[$d]);
					$codcheque = $arr_dad[0];
					$codmov = $arr_dad[1];
					$codord = $arr_dad[2];

					//rotina que retorna $e60_numemp
					$result01 = $clpagordem->sql_record($clpagordem->sql_query_emp($codord, "e60_numemp,e60_codemp"));
					db_fieldsmemory($result01, 0);
					$e60_numemp = $e60_numemp;

					//rotina que traz o valor do cheque
					$result = $clempageconfche->sql_record($clempageconfche->sql_query_file($codcheque, "e91_valor,e91_cheque",null," e91_ativo is true"));
					db_fieldsmemory($result, 0);
					$vlrpag = $e91_valor;
					//echo " cheque= $codcheque =$e60_numemp---$vlrpag<br><br>";

					$e50_codord = $codord;
					$e91_codcheque = $codcheque;
					$k12_cheque = $e91_cheque;
					$codigomovimento = $codmov;


					$result = $clempagepag->sql_record($clempagepag->sql_query($codmov, null, "e83_conta"));
					db_fieldsmemory($result, 0);
					$k13_conta = $e83_conta;

					//rotina que pega os valores dos elementos e coloca na variavel $dados
					$result03 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_codele,e64_vlrpag,e64_vlrliq,e64_vlremp"));
					$numrows03 = $clempelemento->numrows;
					$dados = '';
					$sep = '';
					$liberado = false;
					for ($e = 0; $e < $numrows03; $e ++) {
						db_fieldsmemory($result03, $e);

						$vlrdis = $e64_vlrliq - $e64_vlrpag;
						if ($e91_valor <= $vlrdis) {
							$valor = $e91_valor;
							$liberado = true;
						} else {
							$valor = $vlrdis;
							$e91_valor = $e91_valor - $vlrdis;
						}

						$dados .= $sep.$e64_codele."-".$valor;
						$sep = '#';
					}
					if ($liberado == false) {
						$sqlerro = false;
						$erro_msg = "Cheque $e91_cheque possui um valor maior do que o disponivel!";
					}
					//**********************************************************/
					//quando o  pagamento for  por ordem
				} else
					if ($tipo == "ordem") {
						$arr_dad = split("-", $arr_chaves[$d]);
						$e83_codtipo = $arr_dad[2];

						//retorna codord
						$e50_codord = $arr_dad[0];

						//retorna o valor a ser pago
						$e91_valor = $arr_dad[1];
						$vlrpag = $e91_valor;

						//retorna k13_conta
						$result = $clempagetipo->sql_record($clempagetipo->sql_query($e83_codtipo, "e83_conta"));
						db_fieldsmemory($result, 0);
						$k13_conta = $e83_conta;

						//retorna o numemp
						$result01 = $clpagordem->sql_record($clpagordem->sql_query_emp($e50_codord, "e60_numemp,e60_codemp"));
						db_fieldsmemory($result01, 0);
						//$e60_numemp =  $e60_numemp;

						//retorna a variavel $dados;
						$result03 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_codele,e64_vlrpag,e64_vlrliq,e64_vlremp"));
						$numrows03 = $clempelemento->numrows;
						$dados = '';
						$sep = '';
						$liberado = false;

						$result_codigomovimento = $clempord->sql_record($clempord->sql_query_corempagemov(null,null,"e82_codmov as codigomovimento",""," e82_codord = $e50_codord and corempagemov.k12_codmov is null"));
						if($clempord->numrows > 0){
						  db_fieldsmemory($result_codigomovimento,0);
						}else{
						  $codigomovimento = 0;
						}
						for ($e = 0; $e < $numrows03; $e ++) {
							db_fieldsmemory($result03, $e);

							$vlrdis = $e64_vlrliq - $e64_vlrpag;

							if ($e91_valor <= $vlrdis) {
								$valor = $e91_valor;
								$liberado = true;
							} else {
								$valor = $vlrdis;
								$e91_valor = $e91_valor - $vlrdis;
							}

							$dados .= $sep.$e64_codele."-".$valor;
							$sep = '#';
						}
					} else {
						$sqlerro = true;
						$erro_msg = 'Contate suporte.';
					}
		//******************************************************************/

		//--------------++++++++++++++++++++------------------------------------------------------------
		//inicializa processo de pagamento de empenho
		if ($sqlerro == false) {
			//************-*///////////////////////////////////////////////////////////////////////
			//arquivo de pagamento de empenho---------------
			$pagamento_auto = true; //variavel setada para indicar que eh pagamento automatico
			//  echo $dados."<br><br>";
			//      echo $erro_msg;
           try {

             $oOrdemPagamento = new ordemPagamento($e50_codord);
             $oOrdemPagamento->setCheque($e91_cheque);
             $oOrdemPagamento->setChequeAgenda($e91_codcheque);
             $oOrdemPagamento->setConta($k13_conta);
             $oOrdemPagamento->setValorPago($vlrpag);
             $oOrdemPagamento->setMovimentoAgenda($codigomovimento);
             $oOrdemPagamento->pagarOrdem();
             $sqlerro       = false;
             $erro_msg      = "Pagamento efetuado com sucesso.";
             $k11_tipautent = $oOrdemPagamento->oAutentica->k11_tipautent;
             $retorno       = true;
             $c70_codlan    = $oOrdemPagamento->iCodLanc;

         }
         catch (Exception $e) {

           $sqlerro    = true;
           $erro_msg   = str_replace("\n","\\n",$e->getMessage());
           echo $e->getMessage();
           //exit;
         }
			//include ("emp1_emppagamentoarq.php");
	}
		//final
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	db_fim_transacao($sqlerro);
	if ($sqlerro == false) {
		unset ($e50_codord);
		unset ($e60_numemp);
	}
//  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?


$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$oParametroAgenda = (db_stdClass::getParametro("empparametro",array(db_getsession("DB_anousu")),"e30_agendaautomatico"));
if ($oParametroAgenda[0]->e30_agendaautomatico != "t") {
   require_once ("forms/db_frmempagepagamento.php");
} else {
   require_once ('forms/db_frmpagamentoagenda.php');
}
?>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if (isset ($atualizar)) {
	db_msgbox($erro_msg);
}

function getDocumentoPorTipoInclusao($iTipoOperacao) {

	$iCodigoDocumento = 0;
	switch ($iTipoOperacao) {

		/**
		 * Transferencia Financeira
		 */
		case 1:
			$iCodigoDocumento = 120;
			break;
		case 2:
			$iCodigoDocumento = 121;
			break;
		case 3:
			$iCodigoDocumento = 130;
			break;
		case 4:
			$iCodigoDocumento = 131;
			break;

			/**
			 * Transferencia Bancaria
			 */
		case 5:
			$iCodigoDocumento = 140;
			break;
		case 6:
			$iCodigoDocumento = 141;
			break;

			/**
			 * Caução
			 */
		case 7:
			$iCodigoDocumento = 150;
			break;
		case 8:
			$iCodigoDocumento = 152;
			break;
		case 9:
			$iCodigoDocumento = 151;
			break;
		case 10:
			$iCodigoDocumento = 153;
			break;

	  /**
	   * Depósito de Diversas Origens
	   */
		case 11:
			$iCodigoDocumento = 160;
			break;
		case 12:
			$iCodigoDocumento = 162;
			break;
		case 13:
			$iCodigoDocumento = 161;
			break;
		case 14:
			$iCodigoDocumento = 163;
			break;
	}
	return $iCodigoDocumento;
}
?>