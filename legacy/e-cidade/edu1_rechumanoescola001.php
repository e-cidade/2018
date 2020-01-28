<?php
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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clrechumano         = new cl_rechumano;
$clrechumanoativ     = new cl_rechumanoativ;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$clregenciahorario   = new cl_regenciahorario;
$clrelacaotrabalho   = new cl_relacaotrabalho;
$clrechumanoescola   = new cl_rechumanoescola;
$clefetividade       = new cl_efetividade;
$clefetividaderh     = new cl_efetividaderh;
$clescola            = new cl_escola;
$oDaoAgendaAtividade = new cl_agendaatividade;
$db_opcao            = 1;
$db_botao            = true;
$iEscola             = db_getsession( "DB_coddepto" );


/**
 * Verifica se o servidor possuí alguma ausência sem data final
 */
function verificaAusencia($iRecursoHumano, $iEscola, $iTipoServidor) {

  try {
    $oDaoAusencia = new cl_docenteausencia();
    $sWhereAusencia = "ed321_final is null AND ed321_rechumano = {$iRecursoHumano} AND ed321_escola = {$iEscola}";
    $sSqlAusencia = $oDaoAusencia->sql_query_file(null, "*", null, "{$sWhereAusencia}");
    $rsAusencia   = db_query($sSqlAusencia);

    if (!$rsAusencia) {
      throw new \Exception("Não foi possível consultar as ausências do servidor.");
    }

    if (pg_num_rows($rsAusencia) > 0) {
      throw new \Exception("Servidor possuí ausência sem data final. Verifique no menu Procedimentos > Controle de ausências e substituições > Alteração.");
    }

    $oDaoAusente = new cl_rechumanoausente();
    $sWhereAusente = "ed348_final is null AND ed348_rechumano = {$iRecursoHumano} AND ed348_escola = {$iEscola}";
    $sSqlAusente = $oDaoAusente->sql_query_file(null, "*", null, "{$sWhereAusente}");
    $rsAusente   = db_query($sSqlAusente);

    if (!$rsAusente) {
      throw new \Exception("Não foi possível consultar as ausências do servidor.");
    }

    if (pg_num_rows($rsAusente) > 0) {
      throw new \Exception("Servidor possuí ausência sem data final. Verifique no menu Procedimentos > Controle de ausências e substituições > Alteração.");
    }

  } catch (\Exception $exception) {
    db_msgbox($exception->getMessage());

    if (db_utils::inTransaction()) {
      db_fim_transacao(true);
    }

    db_redireciona("edu1_rechumanoescola001.php?ed75_i_rechumano={$iRecursoHumano}&ed20_i_tiposervidor={$iTipoServidor}");
  }
}

/**
 * Validações referentes a alterações na data de saída do registro de um recurso humano
 */
function permiteAlteracaoRegistroEscola() {

  $oPost                  = db_utils::postMemory( $_POST );
  $lAlteracaoPermitida    = true;
  $iEscola                = db_getsession( "DB_coddepto" );
  $oDaoRecHumanoEscola    = new cl_rechumanoescola;
  $sCamposRecHumanoEscola = "ed75_d_ingresso, ed75_i_saidaescola";
  $sWhereRecHumanoEscola  = "     ed75_i_escola = {$iEscola} AND ed75_i_saidaescola is null";
  $sWhereRecHumanoEscola .= " AND ed75_i_codigo <> {$oPost->ed75_i_codigo} AND ed75_i_rechumano = {$oPost->ed75_i_rechumano}";

  $sSqlRecHumanoEscola    = $oDaoRecHumanoEscola->sql_query_file( null, $sCamposRecHumanoEscola, null, $sWhereRecHumanoEscola );
  $rsRecHumanoEscola      = db_query( $sSqlRecHumanoEscola );

  if( $rsRecHumanoEscola && pg_num_rows( $rsRecHumanoEscola ) > 0 ) {

    if( empty( $oPost->ed75_i_saidaescola ) ) {

      $lAlteracaoPermitida = false;
      $sMensagem           = "Alteração não permitida. Recurso humano já possui um registro aberto para a escola atual.";
      db_msgbox( $sMensagem );
    }

    if( $lAlteracaoPermitida ) {

      $oDadosRetorno       = db_utils::fieldsMemory( $rsRecHumanoEscola, 0 );
      $oDataSaidaInformada = new DBDate( $oPost->ed75_i_saidaescola );
      $oDataIngresso       = new DBDate( $oDadosRetorno->ed75_d_ingresso );

      if( DBDate::calculaIntervaloEntreDatas( $oDataSaidaInformada, $oDataIngresso, 'd' ) > 0 ) {

        $lAlteracaoPermitida  = false;
        $sMensagem            = "Alteração não permitida. Data de saída informada, é maior que a data de ingresso do";
        $sMensagem           .= " registro aberto para a escola atual.";
        db_msgbox( $sMensagem );
      }
    }
  }

  return $lAlteracaoPermitida;
}

/**
 * Adiciona um registro de movimentação quando alterada data de saída do professor para alguma data
 */
function incluiMovimentacao () {

  $oPost = db_utils::postMemory( $_POST );

  if( !empty( $oPost->ed75_i_saidaescola ) ) {

    db_inicio_transacao();
    $sResumo = 'Professor teve o vínculo com a escola inativado. A disponibilidade foi excluída.';
    $oDaoRecHumanoMovimentacao                   = new cl_rechumanomovimentacao();
    $oDaoRecHumanoMovimentacao->ed118_sequencial = null;
    $oDaoRecHumanoMovimentacao->ed118_escola     = $oPost->ed75_i_escola;
    $oDaoRecHumanoMovimentacao->ed118_rechumano  = $oPost->ed75_i_rechumano;
    $oDaoRecHumanoMovimentacao->ed118_usuario    = db_getsession('DB_id_usuario');
    $oDaoRecHumanoMovimentacao->ed118_data       = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoRecHumanoMovimentacao->ed118_hora       = db_hora();
    $oDaoRecHumanoMovimentacao->ed118_resumo     = $sResumo;
    $oDaoRecHumanoMovimentacao->incluir(null);

    if ( $oDaoRecHumanoMovimentacao->erro_status == 0 ) {

      db_fim_transacao(true);
      throw new DBException("Não vou possível criar o registro da movimentação\n" . $oDaoRecHumanoMovimentacao->erro_msg);
    }

    db_fim_transacao();
  }
}

if (isset($oPost->opcao) && $oPost->opcao == 'excluir') {

	$sSqlExclusao = $clrechumanoescola->sql_query_file($oPost->ed75_i_codigo);
	$rsExclusao   = $clrechumanoescola->sql_record($sSqlExclusao);
	db_fieldsmemory($rsExclusao, 0);
}

/**
 * Caso tenha sido informada um data de saída, valida se esta data é maior que a data atual
 */
if( !empty( $ed75_i_saidaescola ) ) {

  $oDataAtual = new DBDate( date( "d/m/Y", db_getsession( "DB_datausu" ) ) );
  $oDataSaida = new DBDate( $ed75_i_saidaescola );

  if( DBDate::calculaIntervaloEntreDatas( $oDataSaida, $oDataAtual, 'd' ) > 0 ) {

    $sMensagem  = "Data de saída informada( {$oDataSaida->getDate( DBDate::DATA_PTBR )} ), não pode ser maior que a";
    $sMensagem .= " data atual( {$oDataAtual->getDate( DBDate::DATA_PTBR )} ).";
    db_msgbox( $sMensagem );
    db_redireciona("edu1_rechumanoescola001.php?ed75_i_rechumano={$ed75_i_rechumano}&ed20_i_tiposervidor={$ed20_i_tiposervidor}");
  }
}

if (isset($incluir)) {

  $clrechumanoescola->pagina_retorno = " edu1_rechumanoescola001.php?ed75_i_rechumano=$ed75_i_rechumano&ed20_i_tiposervidor={$ed20_i_tiposervidor}";
  $sCamposRechumanoEscola            = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
  $sCamposRechumanoEscola           .= " cgmcgm.z01_nome end as z01_nome,ed18_c_nome";
  $sWhereRechumanoEscola             = " ed75_i_rechumano = $ed75_i_rechumano AND ed75_i_escola = $ed75_i_escola";
  $sWhereRechumanoEscola            .= " and ed75_i_saidaescola is null";
  $sSqlRechumanoEscola               = $clrechumanoescola->sql_query("",$sCamposRechumanoEscola,"",$sWhereRechumanoEscola);
  $sResultRechumanoEscola            = $clrechumanoescola->sql_record($sSqlRechumanoEscola);


  if ($clrechumanoescola->numrows > 0) {

    db_fieldsmemory($sResultRechumanoEscola,0);
    db_msgbox("Recurso Humano $z01_nome já está vinculado a escola $ed18_c_nome");
    echo "<script>location.href='".$clrechumanoescola->pagina_retorno."'</script>";
  } else {

    db_inicio_transacao();

    $clrechumanoescola->ed75_c_simultaneo = 'N';
    if (!empty($ed75_c_simultaneo)) {
    	$clrechumanoescola->ed75_c_simultaneo = 'S';
    }

    $clrechumanoescola->incluir($ed75_i_codigo);
    db_fim_transacao();
  }

}

if (isset($alterar)) {

  if( !permiteAlteracaoRegistroEscola() ) {
    db_redireciona("edu1_rechumanoescola001.php?ed75_i_rechumano={$ed75_i_rechumano}&ed20_i_tiposervidor={$ed20_i_tiposervidor}");
  }

  /**
   * Verifica se o servidor possuí alguma ausência sem data final
   */
  if ( !empty($ed75_i_saidaescola) ) {
    verificaAusencia($ed75_i_rechumano, $iEscola, $ed20_i_tiposervidor);
  }

  $sAtivo                  = empty( $ed75_i_saidaescola ) ? 'true' : 'false';
  $sWhereRecHumanoHoraDisp = "ed33_rechumanoescola = {$ed75_i_codigo}";
  $sSqlRecHumanoHoraDisp   = $clrechumanohoradisp->sql_query_file( null, "ed33_i_codigo", null, $sWhereRecHumanoHoraDisp );
  $rsRecHumanoHoraDisp     = db_query( $sSqlRecHumanoHoraDisp );

  if( $rsRecHumanoHoraDisp && pg_num_rows( $rsRecHumanoHoraDisp ) > 0 ) {

    $iTotalLinhas = pg_num_rows( $rsRecHumanoHoraDisp );
    for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

      $iCodigo                            = db_utils::fieldsMemory( $rsRecHumanoHoraDisp, $iContador )->ed33_i_codigo;
      $clrechumanohoradisp->ed33_ativo    = $sAtivo;
      $clrechumanohoradisp->ed33_i_codigo = $iCodigo;
      $clrechumanohoradisp->alterar( $iCodigo );
    }
  }

	$clrechumanoescola->ed75_c_simultaneo = 'N';
	if (!empty($ed75_c_simultaneo)) {
		$clrechumanoescola->ed75_c_simultaneo = 'S';
	}
  $db_opcao = 1;

  db_inicio_transacao();
  $clrechumanoescola->alterar($ed75_i_codigo);

  $oProfissionalEscola = ProfissionalEscolaRepository::getByCodigo( $ed75_i_codigo );
  $aRelacoesTrabalho   = $oProfissionalEscola->getRelacoesTrabalho();
  $aAtividades         = $oProfissionalEscola->getAtividades();

  $lAtivo = $sAtivo == 'true';

  try {

    foreach ( $aRelacoesTrabalho as $oRelacaoTrabalho ) {

      $oRelacaoTrabalho->setAtivo( $lAtivo );
      $oRelacaoTrabalho->salvar();
    }

    foreach ( $aAtividades as $oAtividade ) {

      $oAtividade->setAtivo( $lAtivo );
      $oAtividade->salvar();
    }
  } catch (Exception $oErro) {

    db_msgbox($oErro->getMessage());
    db_fim_transacao(true);
  }

  db_fim_transacao();

  incluiMovimentacao();
}

if (isset($excluir)) {

  db_inicio_transacao();

  /**
   * Verifica se o servidor possuí uma ausência sem data final
   */
  verificaAusencia($ed75_i_rechumano, $iEscola, $ed20_i_tiposervidor);

  $db_opcao = 3;
  $sSql     = " SELECT ed33_i_codigo ";
  $sSql    .= "   FROM rechumanohoradisp ";
  $sSql    .= "        inner join periodoescola on ed17_i_codigo = ed33_i_periodo ";
  $sSql    .= "  WHERE ed33_rechumanoescola = {$ed75_i_codigo} AND ed17_i_escola = {$ed75_i_escola}";
  $clrechumanohoradisp->excluir(""," ed33_i_codigo in ($sSql)");

  if ($clrechumanohoradisp->erro_status != "0") {

  	$sSqlEfetividade  = " SELECT ed97_i_codigo ";
  	$sSqlEfetividade .= "   FROM efetividade ";
  	$sSqlEfetividade .= "        inner join efetividaderh on ed98_i_codigo = ed97_i_efetividaderh ";
  	$sSqlEfetividade .= "  WHERE ed97_i_rechumano = {$ed75_i_rechumano} AND ed98_i_escola = {$ed75_i_escola}";
  	$sSqlEfetividade .= "  order by ed97_i_codigo desc limit 1";
    $clefetividade->excluir(""," ed97_i_codigo = ($sSqlEfetividade)");

    if ($clefetividade->erro_status == "0") {

      $clrechumanohoradisp->erro_status = "0";
      $clrechumanohoradisp->erro_msg    = $clefetividade->erro_msg;
    }
  }

  if ($clrechumanohoradisp->erro_status != "0") {

    $sSqlWhere = "select ed22_i_codigo from rechumanoativ where ed22_i_rechumanoescola = {$ed75_i_codigo}";
    $sWhere    = " ed129_rechumanoativ in ($sSqlWhere) ";
    $oDaoAgendaAtividade->excluir(null, $sWhere);

    $sWhereRelacao        = " ed03_i_rechumanoativ in ($sSqlWhere) ";
    $oDaoRechumanoRelacao = new cl_rechumanorelacao();
    $oDaoRechumanoRelacao->excluir(null, $sWhereRelacao);

    $clrechumanoativ->excluir(""," ed22_i_rechumanoescola = {$ed75_i_codigo}");
    if ($clrechumanoativ->erro_status == "0") {

      $clrechumanohoradisp->erro_status = "0";
      $clrechumanohoradisp->erro_msg    = $clrechumanoativ->erro_msg;
    }
  }

  if ($clrechumanohoradisp->erro_status != "0") {

    $clrelacaotrabalho->excluir(""," ed23_i_rechumanoescola = {$ed75_i_codigo}");
    if ($clrelacaotrabalho->erro_status == "0") {

      $clrechumanohoradisp->erro_status = "0";
      $clrechumanohoradisp->erro_msg    = $clrelacaotrabalho->erro_msg;
    }
  }

  if ($clrechumanohoradisp->erro_status != "0") {

  	$sWhereExclui = "ed75_i_codigo = {$ed75_i_codigo}";

    $clrechumanoescola->excluir(null, $sWhereExclui);
    if ($clrechumanoescola->erro_status == "0") {

      $clrechumanohoradisp->erro_status = "0";
      $clrechumanohoradisp->erro_msg    = $clrechumanoescola->erro_msg;
    }
  }

  db_fim_transacao($clrechumanohoradisp->erro_status=="0");

  $clrechumanoescola->erro(true, false);

  $sWhereRecHumanoEscola = "ed75_i_escola = {$iEscola} AND ed75_i_rechumano = {$ed75_i_rechumano}";
  $sSqlRecHumanoEscola   = $clrechumanoescola->sql_query_file( null, "ed75_i_codigo", null, $sWhereRecHumanoEscola );
  $rsRecHumanoEscola     = db_query( $sSqlRecHumanoEscola );

  if( $rsRecHumanoEscola && pg_num_rows( $rsRecHumanoEscola ) == 0 ) {

    echo "<script>";
    echo "parent.location.href = 'edu1_rechumanoabas001.php';";
    echo "</script>";
    exit;
  } else {
    db_redireciona( "edu1_rechumanoescola001.php?ed75_i_rechumano={$ed75_i_rechumano}&ed20_i_tiposervidor={$ed20_i_tiposervidor}" );
  }
}
$sCampos          = " case when ed20_i_tiposervidor = 1 ";
$sCampos         .= "      then ed284_i_rhpessoal ";
$sCampos         .= "      else ed285_i_cgm ";
$sCampos         .= "  end as identificacao, ";
$sCampos         .= " case when ed20_i_tiposervidor = 1 ";
$sCampos         .= "      then cgmrh.z01_nome ";
$sCampos         .= "      else cgmcgm.z01_nome ";
$sCampos         .= "  end as z01_nome, ";
$sCampos         .= " ed20_i_tiposervidor ";
$sSqlRechumano    = $clrechumano->sql_query("",$sCampos,""," ed20_i_codigo = $ed75_i_rechumano");
$sResultRechumano = $clrechumano->sql_record($sSqlRechumano);
db_fieldsmemory($sResultRechumano,0);

/**
 * Valida se tem um vicnulo com a escola que seja ativo
 */
$sWhereRecHumanoEscola = "ed75_i_escola = {$iEscola} AND ed75_i_rechumano = {$oGet->ed75_i_rechumano} and ed75_i_saidaescola is null ";
$sSqlValidavinculo     = $clrechumanoescola->sql_query_file( null, "ed75_i_codigo", null, $sWhereRecHumanoEscola );
$rsValidavinculo       = db_query($sSqlValidavinculo);

if (pg_num_rows($rsValidavinculo) > 0 ) {

  $sComplementoUrl  = "&identificacao=$identificacao";
  $sComplementoUrl .= "&z01_nome=" . addslashes( $z01_nome );
  $sComplementoUrl .= "&ed20_i_tiposervidor=$ed20_i_tiposervidor";

  $sNome = base64_encode($z01_nome);

  echo "<script>";
  echo "  parent.document.formaba.a4.disabled = false;";
  echo "  parent.document.formaba.a5.disabled = false;";
  echo "  parent.document.formaba.a6.disabled = false;";
  echo "  parent.document.formaba.a7.disabled = false;";
  echo "  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href   = 'edu1_rechumanoativ001.php?ed75_i_rechumano={$ed75_i_rechumano}{$sComplementoUrl}'; ";
  echo "  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href   = 'edu1_relacaotrabalho001.php?ed75_i_rechumano={$ed75_i_rechumano}&sNome={$sNome}'; ";
  echo "  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a6.location.href   = 'edu1_rechumanohoradisp001.php?ed20_i_codigo=$ed75_i_rechumano';";
  echo "  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a7.location.href   = 'edu1_rechumanohorario001.php?ed20_i_codigo=$ed75_i_rechumano';";
  echo "</script>";

} else {

  echo "<script>";
  echo "  parent.document.formaba.a4.disabled = true;";
  echo "  parent.document.formaba.a5.disabled = true;";
  echo "  parent.document.formaba.a6.disabled = true;";
  echo "  parent.document.formaba.a7.disabled = true;";
  echo "</script>";
}


?>
<html>
  <head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <br>
      <center>
       <fieldset style="width:95%"><legend><b>Escolas em que o Recurso Humano trabalha: </b></legend>
        <?include(modification("forms/db_frmrechumanoescola.php"));?>
       </fieldset>
      </center>
     </td>
    </tr>
   </table>
  </body>
</html>
<script>
  js_tabulacaoforms("form1", "ed75_i_escola", true, 1, "ed75_i_escola", true);
</script>
<?
if( isset( $incluir ) ) {

  if( $clrechumanoescola->erro_status == "0" ) {

    $clrechumanoescola->erro( true, false );
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clrechumanoescola->erro_campo != "" ) {

      echo "<script> document.form1.".$clrechumanoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrechumanoescola->erro_campo.".focus();</script>";
    }
  } else {
    $clrechumanoescola->erro( true, true );
  }
}

if( isset( $alterar ) ) {

	if( $clrechumanoescola->erro_status == "0" ) {

		$clrechumanoescola->erro( true, false );
		$db_botao = true;

		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if( $clrechumanoescola->erro_campo != "" ) {

			echo "<script> document.form1.".$clrechumanoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clrechumanoescola->erro_campo.".focus();</script>";
		}
	} else {
		db_redireciona( "edu1_rechumanoescola001.php?ed75_i_rechumano={$ed75_i_rechumano}&ed20_i_tiposervidor={$ed20_i_tiposervidor}" );
	}
}

if( isset( $cancelar ) ) {

  $clrechumanoescola->pagina_retorno = "edu1_rechumanoescola001.php?ed75_i_rechumano={$ed75_i_rechumano}&ed20_i_tiposervidor={$ed20_i_tiposervidor}";
  echo "<script>location.href='".$clrechumanoescola->pagina_retorno."'</script>";
}
