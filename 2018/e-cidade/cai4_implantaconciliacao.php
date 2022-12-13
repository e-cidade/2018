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
	require_once("libs/db_conecta.php");
	require_once("libs/db_sessoes.php");
	require_once("libs/db_usuariosonline.php");
	require_once("libs/exceptions/BusinessException.php");
	require_once("libs/exceptions/DBException.php");
	require_once("libs/db_utils.php");
	require_once("dbforms/db_funcoes.php");
	require_once("classes/db_concilia_classe.php");
	require_once("classes/db_conciliacor_classe.php");
	require_once("classes/db_conciliaitem_classe.php");
	require_once("classes/db_corrente_classe.php");
	require_once("classes/db_extratolinha_classe.php");
	require_once("classes/db_conciliaextrato_classe.php");

	$clcorrente             = new cl_corrente;
	$clconcilia             = new cl_concilia;
	$clconciliacor          = new cl_conciliacor;
	$clconciliaitem         = new cl_conciliaitem;
	$oDaoExtratolinha       = new cl_extratolinha;
	$oDaoConciliaExtrato    = new cl_conciliaextrato;

	$sqlerro = false;
	$erromsg = "";

  $iInstituicaoSessao = db_getsession('DB_instit');
	db_postmemory($_POST);
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
	<?

  $sWhereReduz  = " select c61_reduz ";
  $sWhereReduz .= "   from contabancaria ";
  $sWhereReduz .= "        inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial ";
  $sWhereReduz .= "        inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon ";
  $sWhereReduz .= "                                        and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu ";
  $sWhereReduz .= "                                        and conplanoreduz.c61_anousu = ".db_getsession('DB_anousu');
  $sWhereReduz .= "                                        and conplanoreduz.c61_instit = {$iInstituicaoSessao}";
  $sWhereReduz .= "  where contabancaria.db83_sequencial = {$conta} ";

	// select somando o valor total do corrente
	$rsTotalCorrente = $clcorrente->sql_record($clcorrente->sql_query_file(null,null,null,
                                                                         " coalesce(sum(k12_valor),0) as totalcorrente ",
                                                                         null,
                                                                         "   k12_data <= '".$data."'
                                                                         and k12_conta in ($sWhereReduz) "));

	$totalcorrente = db_utils::fieldsMemory($rsTotalCorrente, 0)->totalcorrente;

	db_inicio_transacao();
	$clconcilia->k68_data           = $data;
	$clconcilia->k68_contabancaria  = $conta;
	$clconcilia->k68_saldoextrato   = $totalcorrente;
	$clconcilia->k68_saldocorrente  = $totalcorrente;
	$clconcilia->k68_conciliastatus = 2;
	$clconcilia->incluir(null);
	$erromsg = $clconcilia->erro_msg;
	if ($clconcilia->erro_status == "0") {

    $erromsg = $clconcilia->erro_msg;
    $sqlerro = true;
	}

	$clconciliaitem->k83_conciliatipo = 3;
	$clconciliaitem->k83_concilia     = $clconcilia->k68_sequencial;
	$clconciliaitem->k83_hora         = db_hora();
	$clconciliaitem->k83_usuario      = db_getsession('DB_id_usuario');

  if ( !$sqlerro ) {
	  $clconciliaitem->incluir(null);
  }

	if ($clconciliaitem->erro_status == "0") {

	  $erromsg = $clconciliaitem->erro_msg;
		$sqlerro = true;
	}

	$sCamposTesouraria   = "distinct riCaixa as k12_id , riAutent as k12_autent, riData as k12_data";
	$sSqlBuscaTesouraria = "
    select {$sCamposTesouraria}
      from fc_extratocaixa({$iInstituicaoSessao}, $conta, '1500-01-01', '{$data}', false) as x
           left join conciliacor on k84_id     = ricaixa
                                and k84_data   = ridata
                                and k84_autent = riautent
     where k84_conciliaitem is null
        ";
	$rsCorrente          = db_query($sSqlBuscaTesouraria);
	$intNumrows          = pg_num_rows($rsCorrente);

  db_criatermometro('termometro','Concluido...','blue',1);


	for($i = 0; $i < $intNumrows; $i++ ){

		$oStdDadosCaixa = db_utils::fieldsMemory($rsCorrente, $i);

		db_atutermometro($i, $intNumrows, 'termometro');

		$clconciliacor->k84_conciliaitem   = $clconciliaitem->k83_sequencial ;
		$clconciliacor->k84_id             = $oStdDadosCaixa->k12_id;
		$clconciliacor->k84_data           = $oStdDadosCaixa->k12_data;
		$clconciliacor->k84_autent         = $oStdDadosCaixa->k12_autent;
    $clconciliacor->k84_conciliaorigem = 1;

    if ( !$sqlerro ) {
		  $clconciliacor->incluir(null);
    }

		if ($clconciliacor->erro_status == 0) {

			$erromsg = $clconciliacor->erro_msg;
			$sqlerro = true;
			break;
		}
	}

	/**
	 * após incluir na conciliacor, buscamos os registros da extratolinha
	 *  filtrando pela data <= e pela conta passada
	 *  percorrer os registros retornados da extratolinha e incluir na conciliaextrato
	 *
   * k86_data           <=  $data
   * k86_contabancaria   =  $conta
	 */

	$sWhereExtratoLinha  = "k86_data <= '{$data}' and  k86_contabancaria = {$conta} ";

	$sSqlExtratoLinha   = $oDaoExtratolinha->sql_query(null, "k86_sequencial", null, $sWhereExtratoLinha);
	$rsExtratoLinha     = $oDaoExtratolinha->sql_record($sSqlExtratoLinha);
	$iTotalExtratoLinha = $oDaoExtratolinha->numrows;

	if ($iTotalExtratoLinha > 0) {

  	for ($iExtratoLinha = 0; $iExtratoLinha <  $iTotalExtratoLinha; $iExtratoLinha++) {

  	  $oExtratoLinha = db_utils::fieldsMemory($rsExtratoLinha, $iExtratoLinha);
  	  $oDaoConciliaExtrato->k87_conciliaitem   = $clconciliaitem->k83_sequencial;
  	  $oDaoConciliaExtrato->k87_extratolinha   = $oExtratoLinha->k86_sequencial;
  	  $oDaoConciliaExtrato->k87_conciliaorigem = 1;
      if ( !$sqlerro ) {
  	    $oDaoConciliaExtrato->incluir(null);
      }
  	  if ($oDaoConciliaExtrato->erro_status == "0") {

  	    $erromsg = "ERRO extratolinha : " . $oDaoConciliaExtrato->erro_msg;
  	    $sqlerro = true;
  	    break;
  	  }
  	}
	}

//	$sqlerro = true;
	db_fim_transacao($sqlerro);

  db_msgbox($erromsg);

	echo " <script> parent.db_iframe_implantacao.hide(); </script>";
	echo " <script> parent.document.location.href = 'cai4_implantaconciliacao001.php';</script>";

?>
</body>
</html>