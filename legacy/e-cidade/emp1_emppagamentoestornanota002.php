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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
db_app::import("exceptions.*");
db_app::import("configuracao.*");
require_once("model/CgmFactory.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmJuridico.model.php");
require_once("model/CgmFisico.model.php");
require_once("model/Dotacao.model.php");
require_once('model/empenho/EmpenhoFinanceiro.model.php');
require_once("libs/db_libcontabilidade.php");

//------------------------------------------------------
//   Arquivos que verificam se o boletim já foi liberado ou naum
require_once("classes/db_boletim_classe.php");
$clverficaboletim = new cl_verificaboletim(new cl_boletim);
//------------------------------------------------------

require_once("libs/db_liborcamento.php");
require_once("classes/db_orcdotacao_classe.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_empelemento_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_pagordem_classe.php");
require_once("classes/db_pagordemele_classe.php");
$clpagordem = new cl_pagordem;
$clpagordemele = new cl_pagordemele;
$clempempenho = new cl_empempenho;
$clempelemento = new cl_empelemento;
$clorcdotacao = new cl_orcdotacao;
$clempparamentro = new cl_empparametro;
require_once("libs/db_utils.php");
require_once("classes/ordemPagamento.model.php");
require_once("model/retencaoNota.model.php");

require_once("classes/db_conlancam_classe.php");
require_once("classes/db_conlancamele_classe.php");
require_once("classes/db_conlancampag_classe.php");
require_once("classes/db_conlancamcgm_classe.php");
require_once("classes/db_conparlancam_classe.php");
require_once("classes/db_conlancamemp_classe.php");
require_once("classes/db_conlancamval_classe.php");
require_once("classes/db_conlancamdot_classe.php");
require_once("classes/db_conlancamdoc_classe.php");
require_once("classes/db_conlancamcompl_classe.php");
require_once("classes/db_saltes_classe.php");
require_once("classes/db_conplanoreduz_classe.php");
require_once("classes/db_conlancamlr_classe.php");
require_once("classes/db_conlancamord_classe.php");
require_once("classes/db_empord_classe.php");
require_once("classes/db_empprestaitem_classe.php");

$clconlancam      = new cl_conlancam;
$clconlancamele   = new cl_conlancamele;
$clconlancampag   = new cl_conlancampag;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamcgm   = new cl_conlancamcgm;
$clconparlancam   = new cl_conparlancam;
$clconlancamemp   = new cl_conlancamemp;
$clconlancamval   = new cl_conlancamval;
$clconlancamdot   = new cl_conlancamdot;
$clconlancamdoc   = new cl_conlancamdoc;
$clsaltes         = new cl_saltes;
$clconplanoreduz  = new cl_conplanoreduz;
$clconlancamord   = new cl_conlancamord;
$clconlancamlr    = new cl_conlancamlr;

require_once("classes/db_cfautent_classe.php");
$clcfautent = new cl_cfautent;

require_once("libs/db_libcaixa.php");
$clautenticar = new cl_autenticar;

require_once("classes/db_empagemov_classe.php");
$clempagemov = new cl_empagemov;

//retorna os arrays de lancamento...
$cltranslan = new cl_translan;

$ip = db_getsession("DB_ip");
$porta = 5001;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao       = 22;
$db_botao       = false;
$sSqlParamentro = $clempparamentro->sql_query(DB_getsession("DB_anousu"));
$rsParametro    = $clempparamentro->sql_record($sSqlParamentro);
$oParametro     = db_utils::fieldsMemory($rsParametro, 0);

/**
 * Verifica se foi passado o código do movimento e pega a ordem de pagamento pelo movimento
 */
if (isset($e81_codmov) && !empty($e81_codmov)) {

  $clempord      = new cl_empord();
  $sSql          = $clempord->sql_query_file($e81_codmov, null, 'e82_codord');
  $rsCodigoOrdem = $clempord->sql_record( $sSql );

  if ($clempord->numrows == 0) {
    unset($pag_ord);
  } else {

    $oCodigoOrdem  = db_utils::fieldsMemory($rsCodigoOrdem, 0);
    $e50_codord    = $oCodigoOrdem->e82_codord;
  }
}

if (isset($confirmar)) {
  try {

    db_inicio_transacao();
    $oOrdemPagamento = new ordemPagamento($e50_codord);
    $oOrdemPagamento->setCheque($k12_cheque);
    $oOrdemPagamento->setChequeAgenda($e91_codcheque);
    $oOrdemPagamento->setConta($k13_conta);
    $oOrdemPagamento->setValorPago($vlrpag_estornar);
    $oOrdemPagamento->setHistorico($c72_complem);
    $oOrdemPagamento->estornarOrdem();

    db_fim_transacao(false);
    $sqlerro       = false;
    $erro_msg      = "Pagamento efetuado com sucesso.";
    $k11_tipautent = $oOrdemPagamento->oAutentica->k11_tipautent;
    $retorno       = $oOrdemPagamento->getRetornoautenticacao();
    $c70_codlan    = $oOrdemPagamento->iCodLanc;

  } catch (Exception $e) {

    $sqlerro    = true;
    $erro_msg   = str_replace("\n","\\n",$e->getMessage());
    db_fim_transacao(true);
  }

  //final rotina corrente////////////////////////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

// autentica na impressora
 if( ( isset($confirmar) && $sqlerro==false && $k11_tipautent == 1)  || isset($retorno_imp)) {

  if (isset($retorno_imp)) {
    $retorno = $retorno_imp;
  }

  require_once 'model/impressaoAutenticacao.php';
  $oImpressao = new impressaoAutenticacao($retorno);
  $oModelo = $oImpressao->getModelo();
  $oModelo->imprimir();

  /*
  $fd = @fsockopen(db_getsession('DB_ip'),4444);
  if ($fd) {
    fputs($fd, chr(15)."$retorno".chr(18).chr(10).chr(13));
    fclose($fd);
    $reimpressao = true;
  } else {
    db_msgbox("Autenticadora não conectada ao computador. Verifique");
    $reimpressao = true;
  }
  */
}

function php_erro($msg, $erro = null) {

  global $e60_numemp, $e50_codord;
  $erro = base64_encode("erro=$erro&erro_msg=$msg&e50_codord=$e50_codord&e60_numemp=$e60_numemp");
  db_redireciona("emp1_emppagamentoestorna001.php?$erro");
}

if (isset($pag_emp) && empty($confirmar) ) {
  $db_opcao = 2;
  $db_botao = true;
  //rotina que traz os dados de empempenho
  if (isset($e60_codemp) && $e60_codemp != '') {
    $arr = split("/", $e60_codemp);
    if (count($arr) == 2 && isset($arr[1]) && $arr[1] != '') {
      $dbwhere_ano = " and e60_anousu = ".$arr[1];
    } else {
      $dbwhere_ano = " and e60_anousu =".db_getsession("DB_anousu");
    }

    $sql = $clempempenho->sql_query("", "*", "e60_numemp",
         " e60_codemp =  '".$arr[0]."' $dbwhere_ano and e60_instit = ".db_getsession("DB_instit"));
  } else {
    $sql = $clempempenho->sql_query($e60_numemp);
  }
  $result = $clempempenho->sql_record($sql);

  if ($clempempenho->numrows > 0) {
    db_fieldsmemory($result, 0, true);
  } else {
    php_erro("Empenho inválido!");
    exit;
  }

  // $result01 = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,'(sum(e53_valor)-sum(e53_vlranu)) as saldo  ',"","e50_numemp = $e60_numemp "));

  $result01 = $clpagordem->sql_record($clpagordem->sql_query(null, 'e50_codord as codord ', "", "e50_numemp = $e60_numemp"));
  $numrows01 = $clpagordem->numrows;

  if ($numrows01 > 0) {
    $existe_ordem = true;
    // db_fieldsmemory($result01,0);
    // if($saldo!=0){
    php_erro("Empenho possui ordens de pagamento, acesse pelo numero da OP !",'true');
    exit;
    // }

  }
} else if (isset($pag_ord)) {
  $db_opcao = 2;
  $db_botao = true;
  //rotina que traz os dados de pagordem
  $result = $clpagordem->sql_record($clpagordem->sql_query($e50_codord));
  if ($clpagordem->numrows > 0) {
    db_fieldsmemory($result, 0, true);
    $result01 = $clpagordem->sql_record($clpagordem->sql_query(null, 'e50_codord as codord', "", "e50_numemp = $e50_numemp"));
    db_fieldsmemory($result01, 0, true);
  } else {
    php_erro("Ordem de pagamento inválido!");
  }

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
	<?


if (isset ($confirmar) && $sqlerro == false) {
	$c72_complem = '';
}
if ($oParametro->e30_agendaautomatico == "t") {
  require_once ("forms/db_frmestornamovimento.php");
} else {
  require_once ("forms/db_frmemppagamentoestorna.php");
}
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if ($db_opcao == 22) {
//	echo "<script>document.form1.pesquisar.click();</script>";
}
if (isset ($confirmar)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
	} else {
		echo "
		       <script>
		          if(confirm('Alteração efetuada com sucesso. \\n \\n Deseja imprimir o relatório? ')){
			    jan = window.open('emp2_emiteestornoemp002.php?codord=$e50_codord&codlan=$c70_codlan','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
			    jan.moveTo(0,0);
		          }
		       </script>

		    ";

	}
}

if( (isset($retorno) && $k11_tipautent == 1) ||  (isset($retorno_imp)) ){
   echo "
       <script>
         // função para dispara a autenticação
	 function aut(){
	      retorna = confirm('Autenticar novamente?');
	      if(retorna == true){
	          obj=document.createElement('input');
	          obj.setAttribute('name','retorno_imp');
	          obj.setAttribute('type','hidden');
	          obj.setAttribute('value','$retorno');
	          document.form1.appendChild(obj);
	          obj=document.createElement('input');
	          obj.setAttribute('name','k11_tipautent');
	          obj.setAttribute('type','hidden');
	          obj.setAttribute('value','1');
	          document.form1.appendChild(obj);
	          document.form1.submit();
  	      }
	 }
       </script>
  ";
}

?>