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

require_once('std/DBDate.php');

function fc_removeReciboAnterior($iNumpreReciboRemocao) {

  global $conn;

  $sSqlDebitos = "select distinct                                                          \n";
  $sSqlDebitos.= "       arrecad.k00_numpre,                                               \n";
  $sSqlDebitos.= "       arrecad.k00_numpar,                                               \n";
  $sSqlDebitos.= "       arrecad.k00_receit                                                \n";
  $sSqlDebitos.= "  from recibopaga                                                        \n";
  $sSqlDebitos.= "       inner join arrecad  on arrecad.k00_numpre = recibopaga.k00_numpre \n";
  $sSqlDebitos.= "                          and arrecad.k00_numpar = recibopaga.k00_numpar \n";
  $sSqlDebitos.= " where k00_numnov = {$iNumpreReciboRemocao}                              \n";
  $rsDebitos   = db_query($conn, $sSqlDebitos);

  if (!$rsDebitos) {
    throw new Exception("Erro ao Buscar dados do recibo para exclusao.".pg_last_error($conn) );
  }

  /**
   * Caso nao encontre recibo pelo numpre novo, nao ha necessidade de continuar
   */
  if ( pg_num_rows($rsDebitos) == 0 ) {
    return true;
  }


  $aDebitosRecibo = db_utils::getCollectionByRecord($rsDebitos);
 // Percorre os debitos tentanbdo exclui-los do Arrecad

  foreach ( $aDebitosRecibo as $oDebitoRecibo ) {

     $aDadosCancelamento["Numpre"]  = $oDebitoRecibo->k00_numpre;
     $aDadosCancelamento["Numpar"]  = $oDebitoRecibo->k00_numpar;
     $aDadosCancelamento["Receita"] = $oDebitoRecibo->k00_receit;
     $aDebitos[]                    = $aDadosCancelamento;

  }

  try {

    $oCancelaDebito = new cancelamentoDebitos();
    $oCancelaDebito->setArreHistTXT('Cancelamento pelo Sistema WebISS.');
    $oCancelaDebito->setCadAcao(6);
    $oCancelaDebito->setTipoCancelamento(2);
    $oCancelaDebito->setHistoricoProcessamento("Cancelado por Alteração de Valores de Lançamento no Sistema WebISS");
    $oCancelaDebito->geraCancelamento($aDebitos);

  } catch (Exception $eErro) {
    throw new Exception("Erro ao Cancelar Debito: " . $eErro->getMessage() );
  }

  $rsRecibopaga         = db_query($conn, " delete from recibopaga       where k00_numnov   = {$iNumpreReciboRemocao}; ");
  $rsRecibopagaBoleto   = db_query($conn, " delete from recibopagaboleto where k138_numnov  = {$iNumpreReciboRemocao}; ");
  $rsArrebanco          = db_query($conn, " delete from arrebanco        where k00_numpre   = {$iNumpreReciboRemocao}; ");
  $rsDB_ReciboWeb       = db_query($conn, " delete from db_reciboweb     where k99_numpre_n = {$iNumpreReciboRemocao}; ");
  $rsReciboCodbar       = db_query($conn, " delete from recibocodbar     where k00_numpre   = {$iNumpreReciboRemocao}; ");

  return true;
}
function fc_preProcessamentoDocumento($iNumdoc) {

  global $conn;

  $oRetorno = new stdClass();
  $oRetorno->lPermiteProcessamento = true;
  $oRetorno->sMensagem             = "";

  //Buscando dados do arrecad pelo numprenovo

  $sSqlValidaDebito = "select distinct                                                                                             ";
  $sSqlValidaDebito.= "       k00_numnov,                                                                                          ";
  $sSqlValidaDebito.= "       k00_numpre,                                                                                          ";
  $sSqlValidaDebito.= "       k00_numpar,                                                                                          ";
  $sSqlValidaDebito.= "       (select coalesce(count(distinct k00_sequencial), 0)                                                  ";
  $sSqlValidaDebito.= "          from arreckey                                                                                     ";
  $sSqlValidaDebito.= "               inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial  ";
  $sSqlValidaDebito.= "         where arreckey.k00_numpre = recibopaga.k00_numpre                                                  ";
  $sSqlValidaDebito.= "           and arreckey.k00_numpar = recibopaga.k00_numpar                                                  ";
  $sSqlValidaDebito.= "       ) as abatimentos,                                                                                    ";
  $sSqlValidaDebito.= "       (select count(distinct arrepaga.k00_numpre::text||arrepaga.k00_numpar::text)                         ";
  $sSqlValidaDebito.= "          from arrepaga                                                                                     ";
  $sSqlValidaDebito.= "         where arrepaga.k00_numpre = recibopaga.k00_numpre                                                  ";
  $sSqlValidaDebito.= "           and arrepaga.k00_numpar = recibopaga.k00_numpar                                                  ";
  $sSqlValidaDebito.= "       ) as pagamentos                                                                                      ";
  $sSqlValidaDebito.= "  from recibopaga                                                                                           ";
  $sSqlValidaDebito.= " where recibopaga.k00_numnov = {$iNumdoc};                                                                  ";

  $rsValidaDebito   = db_query($conn, $sSqlValidaDebito);

  if ( pg_num_rows($rsValidaDebito) == 0 ) {
    return $oRetorno;
  }

  $aValidacaoDebito = db_utils::getCollectionByRecord($rsValidaDebito);

  foreach ($aValidacaoDebito as $oValidacaoDebito) {

    if ($oValidacaoDebito->abatimentos > 0) {
      $oRetorno->lPermiteProcessamento = false;
      $oRetorno->sMensagem             = "Existe pagamento parcial para o recibo.";
      return $oRetorno;
    }

    if ($oValidacaoDebito->pagamentos > 0) {
      $oRetorno->lPermiteProcessamento = false;
      $oRetorno->sMensagem             = "Existem pagamentos para o recibo.";
      return $oRetorno;
    }

  }
  return $oRetorno;
}

function fc_getVencimentoByCompetencia($iAno, $iMes) {

  global $connDestino;

  $rsConfiguracoes = db_query($connDestino,"select *
                                              from integra_cad_config_vencimentos
                                             where mes_competencia = {$iMes}
                                               and ano_competencia = {$iAno}     ");
  if (!$rsConfiguracoes) {
    throw new Exception("Erro ao Executar busca dos Parametros: ".pg_last_error($connDestino));
  }

  if ( pg_num_rows($rsConfiguracoes) == 0 ) {

    throw new BusinessException("Data de Vencimento não Configurada para o Mes/Ano: {$iMes}/{$iAno}. \n
                                 Verifique dados na Tabela integra_cad_config_vencimentos ");
  }

  return new DBDate(db_utils::fieldsMemory($rsConfiguracoes,0)->data_vencimento);

}

/************************   Processamento dos Debitos   ************************/
$aCompetencias = array();
db_logTitulo("PROCESSA DEBITOS",$sArquivoLog,$iParamLog);

$sSqlIntegraRecibo  = "    select integra_recibo.sequencial,                                                                                      \n";
$sSqlIntegraRecibo .= "           integra_recibo_detalhe.tipo_lancamento,                                                                         \n";
$sSqlIntegraRecibo .= "           sum(integra_recibo_detalhe.valor) as valor_imposto,                                                             \n";
$sSqlIntegraRecibo .= "           count(integra_recibo_detalhe.integra_recibo_detalhe_origem) as quantidade_origem,                               \n";
$sSqlIntegraRecibo .= "           integra_recibo.tipo_boleto,                                                                                     \n";
$sSqlIntegraRecibo .= "           integra_recibo.numbanco,                                                                                        \n";
$sSqlIntegraRecibo .= "           integra_recibo.numdoc,                                                                                          \n";
$sSqlIntegraRecibo .= "           integra_cad_empresa.inscricao as inscricao,                                                                     \n";
$sSqlIntegraRecibo .= "           integra_cadastro.cpf_cnpj     as cpfcnpj,                                                                       \n";
$sSqlIntegraRecibo .= "           integra_recibo.data_emissao,                                                                                    \n";
$sSqlIntegraRecibo .= "           integra_recibo_detalhe.ano_competencia_origem as ano_competencia,                                               \n";
$sSqlIntegraRecibo .= "           integra_recibo_detalhe.mes_competencia_origem as mes_competencia                                                \n";
$sSqlIntegraRecibo .= "      from integra_recibo                                                                                                  \n";
$sSqlIntegraRecibo .= "           inner join integra_recibo_detalhe on integra_recibo_detalhe.integra_recibo = integra_recibo.sequencial          \n";
$sSqlIntegraRecibo .= "           left  join integra_cad_empresa    on integra_cad_empresa.sequencial        = integra_recibo.integra_cad_empresa \n";
$sSqlIntegraRecibo .= "           left  join integra_cadastro       on integra_cadastro.sequencial           = integra_recibo.integra_cadastro    \n";
$sSqlIntegraRecibo .= "     where integra_recibo.processado is false                                                                              \n";
$sSqlIntegraRecibo .= "  group by integra_recibo.sequencial,                                                                                      \n";
$sSqlIntegraRecibo .= "           integra_recibo_detalhe.tipo_lancamento,                                                                         \n";
$sSqlIntegraRecibo .= "           integra_recibo.tipo_boleto,                                                                                     \n";
$sSqlIntegraRecibo .= "           integra_recibo.numbanco,                                                                                        \n";
$sSqlIntegraRecibo .= "           integra_recibo.numdoc,                                                                                          \n";
$sSqlIntegraRecibo .= "           integra_cad_empresa.inscricao,                                                                                  \n";
$sSqlIntegraRecibo .= "           integra_cadastro.cpf_cnpj    ,                                                                                  \n";
$sSqlIntegraRecibo .= "           integra_recibo.data_emissao,                                                                                    \n";
$sSqlIntegraRecibo .= "           integra_recibo_detalhe.ano_competencia_origem,                                                                  \n";
$sSqlIntegraRecibo .= "           integra_recibo_detalhe.mes_competencia_origem                                                                   \n";
$sSqlIntegraRecibo .= "  order by integra_recibo.numdoc,                                                                                          \n";
$sSqlIntegraRecibo .= "           integra_recibo_detalhe.ano_competencia_origem,                                                                  \n";
$sSqlIntegraRecibo .= "           integra_recibo_detalhe.mes_competencia_origem                                                                   \n";

$rsIntegraRecibo      = db_query($connDestino,$sSqlIntegraRecibo);
$iLinhasIntegraRecibo = pg_num_rows($rsIntegraRecibo);
$iNumdocAnterior      = 0;
$aIssVarSemLancamento = array();
if ( $iLinhasIntegraRecibo > 0 ) {

  db_log("Total de Registros Encontrados : {$iLinhasIntegraRecibo}", $sArquivoLog, $iParamLog);
  db_log("\n",$sArquivoLog,1);

  for ( $iIndRecibo=0; $iIndRecibo < $iLinhasIntegraRecibo; $iIndRecibo++ ) {

   logProcessamento($iIndRecibo,$iLinhasIntegraRecibo,$iParamLog);
   $oIntegraRecibo                  = db_utils::fieldsMemory($rsIntegraRecibo, $iIndRecibo);

   /**
    * Validando se o recibo está vinculado a uma inscrição ou cgm
    */
    if (trim($oIntegraRecibo->inscricao) != '') {

      $sSqlConsultaCgm  = " select q02_numcgm  as cgm                       ";
      $sSqlConsultaCgm .= "   from issbase                                  ";
      $sSqlConsultaCgm .= "  where q02_inscr = {$oIntegraRecibo->inscricao} ";
    } else if (trim($oIntegraRecibo->cpfcnpj) != '') {

      $sSqlConsultaCgm  = " select z01_numcgm  as cgm                       ";
      $sSqlConsultaCgm .= "   from cgm                                      ";
      $sSqlConsultaCgm .= "  where z01_cgccpf = '{$oIntegraRecibo->cpfcnpj}'";
    } else {

      $sMsgLog  = "Recibo: {$oIntegraRecibo->sequencial} Numdoc: {$oIntegraRecibo->numdoc} não processado! ";
      $sMsgLog .= "Inscrição ou CNPJ da Empresa não informada! ";
      db_log($sMsgLog,$sArquivoLog,2);
      continue;
    }

    $rsConsultaCgm = db_query($conn,$sSqlConsultaCgm);

    if ( pg_num_rows($rsConsultaCgm) > 0 ) {
      $iNumCgm = db_utils::fieldsMemory($rsConsultaCgm,0)->cgm;
    } else {
      $sMsgLog  = "Recibo: {$oIntegraRecibo->sequencial} Numdoc: {$oIntegraRecibo->numdoc} não processado!";
      $sMsgLog .= " Empresa não cadastrada! ";
      db_log($sMsgLog,$sArquivoLog,2);
      continue;
    }


    $sTipoBoleto = strtoupper($oIntegraRecibo->tipo_boleto);

    if (trim($sTipoBoleto) == '') {
      $sMsgLog  = "Recibo: {$oIntegraRecibo->sequencial} Numdoc: {$oIntegraRecibo->numdoc} não processado! ";
      $sMsgLog .= "Tipo de Boleto não informado! ";
      db_log($sMsgLog,$sArquivoLog,2);
      continue;
    }

    $sSqlAlteraIntegraRecibo  = " update integra_recibo                             ";
    $sSqlAlteraIntegraRecibo .= "    set processado = true                          ";
    $sSqlAlteraIntegraRecibo .= "  where sequencial = {$oIntegraRecibo->sequencial} ";

    $rsAlteraIntegraRecibo = db_query($connDestino,$sSqlAlteraIntegraRecibo);

    if (!$rsAlteraIntegraRecibo) {
      throw new Exception("ERRO-45: ".pg_last_error($connDestino)." ".$sSqlAlteraIntegraRecibo);
    }

    /**
     * Ignora Geracao de Debitos para tipos de lancamento diferentes de Imposto
     */


    if ( $oIntegraRecibo->tipo_lancamento != 'I' ||
         ($oIntegraRecibo->tipo_lancamento == 'I' && $oIntegraRecibo->quantidade_origem != 0)
       ) {
      continue;
    }

    $oPreProcessamento = fc_preProcessamentoDocumento($oIntegraRecibo->numdoc);

    if ( !$oPreProcessamento->lPermiteProcessamento ) {
      db_log($oPreProcessamento->sMensagem, $sArquivoLog, $iParamLog);
      continue;
    }

    /**
     * Remove recibos e seus debitos do sistema para ser gerado no recibo
     */
    fc_removeReciboAnterior($oIntegraRecibo->numdoc);

    /**
     * Validando anulação
     */

    $sSqlConsultaReciboAnulado = " select *                                              ";
    $sSqlConsultaReciboAnulado.= "   from integra_recibo_anulado                         ";
    $sSqlConsultaReciboAnulado.= "  where integra_recibo = {$oIntegraRecibo->sequencial} ";

    $rsConsultaReciboAnulado   = db_query($connDestino,$sSqlConsultaReciboAnulado);

    if ( pg_num_rows($rsConsultaReciboAnulado) > 0 ) {

      $oReciboAnulado = db_utils::fieldsMemory($rsConsultaReciboAnulado,0);

      $sSqlAlteraIntegraReciboAnulado  = " update integra_recibo_anulado                     ";
      $sSqlAlteraIntegraReciboAnulado .= "    set processado = true                          ";
      $sSqlAlteraIntegraReciboAnulado .= "  where sequencial = {$oReciboAnulado->sequencial} ";

      $rsAlteraIntegraReciboAnulado = db_query($connDestino,$sSqlAlteraIntegraReciboAnulado);

      if (!$rsAlteraIntegraRecibo) {
        throw new Exception("ERRO-49: ".pg_last_error($connDestino)." ".$sSqlAlteraIntegraReciboAnulado);
      }
      continue;
    }

    /**
     *  Desconsidera os registros tipo "T"-(Tomador) com valor zerado
     */
    if (  $oIntegraRecibo->tipo_boleto   == 'T' &&
        ( $oIntegraRecibo->valor_imposto == 0 || trim($oIntegraRecibo->valor_imposto) == '' )
      ) {
      continue;
    }


    if ( $oIntegraRecibo->valor_imposto == 0 || trim($oIntegraRecibo->valor_imposto) == '' ) {

      $sSqlConsultaIssVar  = " select issvar.*                                                            ";
      $sSqlConsultaIssVar .= "   from issvar                                                              ";
      $sSqlConsultaIssVar .= "        inner join arrecad     on arrecad.k00_numpre    = issvar.q05_numpre ";
      $sSqlConsultaIssVar .= "                              and arrecad.k00_numpar    = issvar.q05_numpar ";
      $sSqlConsultaIssVar .= "        inner join arrenumcgm  on arrenumcgm.k00_numpre = issvar.q05_numpre ";
      $sSqlConsultaIssVar .= "        left  join issplan     on issplan.q20_numpre    = issvar.q05_numpre ";
      $sSqlConsultaIssVar .= "  where issplan.q20_numpre is null                                          ";
      $sSqlConsultaIssVar .= "    and arrecad.k00_valor > 0                                               ";
      $sSqlConsultaIssVar .= "    and arrenumcgm.k00_numcgm = {$iNumCgm}                                  ";
      $sSqlConsultaIssVar .= "    and issvar.q05_ano = {$oIntegraRecibo->ano_competencia}                 ";
      $sSqlConsultaIssVar .= "    and issvar.q05_mes = {$oIntegraRecibo->mes_competencia}                 ";

      $rsVerificaIss   =  db_query($conn,$sSqlConsultaIssVar);

      if (pg_num_rows($rsVerificaIss) > 0) {
        continue;
      }
    }

    /**
      * Verifica se já existe algum iss lançado para a competência com valor zerado
      */
    $sSqlConsultaIssVar  = " select issvar.*                                                            ";
    $sSqlConsultaIssVar .= "   from issvar                                                              ";
    $sSqlConsultaIssVar .= "        inner join arrecad     on arrecad.k00_numpre    = issvar.q05_numpre ";
    $sSqlConsultaIssVar .= "                              and arrecad.k00_numpar    = issvar.q05_numpar ";
    $sSqlConsultaIssVar .= "        inner join arrenumcgm  on arrenumcgm.k00_numpre = issvar.q05_numpre ";
    $sSqlConsultaIssVar .= "        left  join issplan     on issplan.q20_numpre    = issvar.q05_numpre ";
    $sSqlConsultaIssVar .= "  where issplan.q20_numpre is null                                          ";
    $sSqlConsultaIssVar .= "    and arrecad.k00_valor = 0                                               ";
    $sSqlConsultaIssVar .= "    and arrenumcgm.k00_numcgm = {$iNumCgm}                                  ";
    $sSqlConsultaIssVar .= "    and issvar.q05_ano = {$oIntegraRecibo->ano_competencia}                 ";
    $sSqlConsultaIssVar .= "    and issvar.q05_mes = {$oIntegraRecibo->mes_competencia}                 ";

    $rsConsultaIssvar    = db_query($conn,$sSqlConsultaIssVar);

    $oDaoIssVar = new cl_issvar();

    if ( pg_num_rows($rsConsultaIssvar) > 0 && $sTipoBoleto == 'P' ) {

      $oDadosIssVar = db_utils::fieldsMemory($rsConsultaIssvar,0);
      $oDaoIssVar->excluir_issvar($oDadosIssVar->q05_codigo);

      if ($oDaoIssVar->erro_status == "0") {
        throw new Exception("ERRO-56: Erro ao excluir issvar , {$oDaoIssVar->erro_msg}");
      }

      $sSqlDeletaVariavelArrecad  = " delete from arrecad  ";
      $sSqlDeletaVariavelArrecad .= "  where arrecad.k00_numpre  = {$oDadosIssVar->q05_numpre} ";
      $sSqlDeletaVariavelArrecad .= "    and arrecad.k00_numpar  = {$oDadosIssVar->q05_numpar} ";

      db_query($conn,$sSqlDeletaVariavelArrecad);

    }

    $rsNumpre      = db_query($conn, "select nextval('numpref_k03_numpre_seq') as k03_numpre");
    $iNumpreGerado = db_utils::fieldsMemory($rsNumpre, 0)->k03_numpre;

    $oDaoIssVar->q05_numpre = $iNumpreGerado;//$oIntegraRecibo->numdoc;
    $oDaoIssVar->q05_numpar = $oIntegraRecibo->mes_competencia;
    $oDaoIssVar->q05_valor  = $oIntegraRecibo->valor_imposto;
    $oDaoIssVar->q05_ano    = $oIntegraRecibo->ano_competencia;
    $oDaoIssVar->q05_mes    = $oIntegraRecibo->mes_competencia;
    $oDaoIssVar->q05_histor = "Importado do WebISS - Tipo de Boleto: {$oIntegraRecibo->tipo_boleto}";
    $oDaoIssVar->q05_aliq   = '0';
    $oDaoIssVar->q05_bruto  = '0';
    $oDaoIssVar->q05_vlrinf = "null";

    /**
     * Gera os Débitos de ISSQN no Arrecad
     */
    $oDataVencimento = fc_getVencimentoByCompetencia($oIntegraRecibo->ano_competencia,  $oIntegraRecibo->mes_competencia);
    $iReceita        = $sTipoBoleto == 'P' ? $iReceitaDebitoPrestador : $iReceitaDebitoTomador;

    if ( trim($oIntegraRecibo->inscricao) != '' ) {
      $oDaoIssVar->gerarIssqnVariavelComplementar($oDataVencimento, $iReceita, null,$oIntegraRecibo->inscricao,null,$sTipoBoleto);
    } else {

      db_log("Incluindo ISSQN complementar para CGM : {$iNumCgm}.",$sArquivoLog,2);
      $oDaoIssVar->gerarIssqnVariavelComplementar($oDataVencimento, $iReceita, null, null, $iNumCgm, $sTipoBoleto);
    }

    if ($oDaoIssVar->erro_status == "0") {
      throw new Exception("ERRO-42: Erro ao gerar isscomplementar, {$oDaoIssVar->erro_msg}");
    }

    $sAlteraIntegraReciboDetalhe = "update integra_recibo_detalhe                                      \n";
    $sAlteraIntegraReciboDetalhe.= "   set numpre = {$iNumpreGerado},                                  \n";
    $sAlteraIntegraReciboDetalhe.= "       numpar = {$oIntegraRecibo->mes_competencia}                 \n";
    $sAlteraIntegraReciboDetalhe.= " where ano_competencia_origem = {$oIntegraRecibo->ano_competencia} \n";
    $sAlteraIntegraReciboDetalhe.= "   and mes_competencia_origem = {$oIntegraRecibo->mes_competencia} \n";
    $sAlteraIntegraReciboDetalhe.= "   and integra_recibo         = {$oIntegraRecibo->sequencial}      \n";
    $sAlteraIntegraReciboDetalhe.= "   and tipo_lancamento        = 'I'                                \n";
    $rsAlteracaoIntegraRecibo    = db_query($connDestino, $sAlteraIntegraReciboDetalhe);

    if ( !$rsAlteracaoIntegraRecibo ) {
      throw new Exception('Erro ao Vincular dados do Arrecad ao Detalhe da Guia: ' . pg_last_error($rsAlteracaoIntegraRecibo));
    }

    if ( $oIntegraRecibo->valor_imposto == 0 || trim($oIntegraRecibo->valor_imposto) == '' ) {

      $sSqlReceitaDebito = " select arrecad.*                                                      \n";
      $sSqlReceitaDebito.= "   from issvar                                                         \n";
      $sSqlReceitaDebito.= "        inner join arrecad  on arrecad.k00_numpre = issvar.q05_numpre  \n";
      $sSqlReceitaDebito.= "                           and arrecad.k00_numpar = issvar.q05_numpar  \n";
      $sSqlReceitaDebito.= "  where issvar.q05_codigo = {$oDaoIssVar->q05_codigo}                  \n";

      $rsReceitaDebito   = db_query($conn,$sSqlReceitaDebito);

      if ( pg_num_rows($rsReceitaDebito) == 0 ) {
        throw new Exception("ERRO-51: Débito não encontrado!");
      } else {
        $oDadosDebito = db_utils::fieldsMemory($rsReceitaDebito,0);
      }

      $aDadosDebitos['Numpre']  = $oDadosDebito->k00_numpre;
      $aDadosDebitos['Numpar']  = $oDadosDebito->k00_numpar;
      $aDadosDebitos['Receita'] = $oDadosDebito->k00_receit;
      $aDebitos                 = array($aDadosDebitos);

      try {
        $oCancelaDebito->setArreHistTXT("Cancelado pela importação WebISS");
        $oCancelaDebito->setCadAcao(6);
        $oCancelaDebito->setTipoCancelamento(2);
        $oCancelaDebito->setHistoricoProcessamento("Cancelado por não haver movimentações na Competência - Lançado pelo WebISS");
        $oCancelaDebito->geraCancelamento($aDebitos);
      } catch (Exception $eException) {
        throw new Exception("ERRO-50: {$eException->getMessage()}");
      }

      $oDaoIssVarSemMov = new cl_issvarsemmov();

      $oDaoIssVarSemMov->q08_usuario  = db_getsession("DB_id_usuario");
      $oDaoIssVarSemMov->q08_data     = date('Y-m-d',db_getsession('DB_datausu'));
      $oDaoIssVarSemMov->q08_hora     = db_hora();
      $oDaoIssVarSemMov->q08_tipolanc = "0";
      $oDaoIssVarSemMov->incluir(null);

      if ($oDaoIssVarSemMov->erro_status == 0) {
        throw new Exception("ERRO-51: {$oDaoIssVarSemMov->erro_sql}");
      }

      $oDaoIssVarSemMovReg = new cl_issvarsemmovreg();

      $oDaoIssVarSemMovReg->q15_issvarsemmov = $oDaoIssVarSemMov->q08_sequencial;
      $oDaoIssVarSemMovReg->q15_issvar       = $oDaoIssVar->q05_codigo;
      $oDaoIssVarSemMovReg->incluir(null);

      if ($oDaoIssVarSemMovReg->erro_status == 0) {
        throw new Exception("ERRO-52: {$oDaoIssVarSemMovReg->erro_sql}");
      }
      unset($aCompetencias[$oIntegraRecibo->numdoc]);
    }
    /**
     * Adicionando Competencias ao Recibo que sera gerado.
     */

    $oDadosRecibo = new stdClass();
    $oDadosRecibo->iInscricao      = $oIntegraRecibo->inscricao;
    $oDadosRecibo->iNumCgm         = $iNumCgm;
    $oDadosRecibo->iAnoCompetencia = $oIntegraRecibo->ano_competencia;
    $oDadosRecibo->iMesCompetencia = $oIntegraRecibo->mes_competencia;
    $oDadosRecibo->dVencimento     = $oIntegraRecibo->data_emissao;
    $oDadosRecibo->iNumbco         = $oIntegraRecibo->numbanco;
    $oDadosRecibo->iSequencial     = $oIntegraRecibo->sequencial;
    $aCompetencias[$oIntegraRecibo->numdoc][$iNumCgm][] = $oDadosRecibo;
  }
} else {
  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
}
/********************************** PROCESSAMENTO RECIBOS ***********************************/
/**
 * Percorre os dados gerado no arrecad para efetuar a geração do recibo.
 */
db_logTitulo(" PROCESSA GERACAO DE RECIBOS ", $sArquivoLog, $iParamLog);
$iProcessamentoRecibo = 0;
$iTotalCompetencias   = count($aCompetencias);

if ($iTotalCompetencias > 0) {

  foreach ( $aCompetencias as $iNumDoc => $aCgm ) {

    foreach ( $aCgm as $iNumCgmEmissao => $aDocumentos ) {

      logProcessamento($iProcessamentoRecibo, $iTotalCompetencias, $iParamLog);
      $iProcessamentoRecibo++;

      /**
       * Instanciando novo recibo para a guia
       */
      db_log("***Iniciando Emissão de Novo Recibo: $iNumDoc, CGM: $iNumCgmEmissao ",$sArquivoLog,0);
      $oRecibo = new recibo( 2, $iNumCgmEmissao, 1 );
      $oRecibo->setNumnov($iNumDoc);

      foreach ($aDocumentos as $oDadosDocumento) {


        if ( !empty($oDadosDocumento->iInscricao) ) {
          $oRecibo->setInscricao($oDadosDocumento->iInscricao);
        }
        /**
         * Busca Numpre e numpar do débito no arrecad através da sua competencia
         */
        $sSqlCompetenciasGuia = SQLBaseIntegracao::sql_query_DadosGuiaCompetencia($oDadosDocumento->iSequencial, $iNumDoc);
        $rsCompetenciasGuias  = db_query($connDestino, $sSqlCompetenciasGuia);

        if ( !$rsCompetenciasGuias || pg_num_rows($rsCompetenciasGuias) == 0 ) {
          throw new Exception("Erro ao Retornar Dados do Documento. ". pg_last_error($connDestino));
        }

        $aCompetenciasIntegraRecibo = db_utils::getCollectionByRecord($rsCompetenciasGuias);

        $aDebitosLancadosGuia  = array();
        $aReciboVinculadosGuia = array();

        foreach ( db_utils::getCollectionByRecord($rsCompetenciasGuias) as $oDadosGuia ) {

          if ( !empty( $oDadosGuia->numpre ) && !empty( $oDadosGuia->numpar ) ) {

            db_log("Adicionando Numpre/Numpar: {$oDadosGuia->numpre}/{$oDadosGuia->numpar} da Propria Guia", $sArquivoLog, 0);
            $oRecibo->addNumpre($oDadosGuia->numpre, $oDadosGuia->numpar);
          }

          if ( !empty($iNumDoc) ) {

            $oReciboOrigem = new recibo(null,null,null, $iNumDoc);

            foreach ( $oReciboOrigem->getDebitosRecibo() as $oDadosRecibo ) {

              db_log("Adicionando Numpre/Numpar: {$oDadosGuia->numpre}/{$oDadosGuia->numpar} do Recibo:{$oReciboOrigem->getNumpreRecibo()}", $sArquivoLog, 0);
              $oRecibo->addNumpre($oDadosRecibo->k00_numpre, $oDadosRecibo->k00_numpar);
            }
          }
        }
      }
      db_log("Definindo Vencimento: {$oDadosDocumento->dVencimento}", $sArquivoLog, 0);

      $oRecibo->setDataVencimentoRecibo( $oDadosDocumento->dVencimento );
      db_log("Emitindo Recibo\n", $sArquivoLog, 0);
      $oRecibo->emiteRecibo();

      if ( trim($oDadosDocumento->iNumbco) != '' ) {

        $sSqlIncluiArrebanco = " insert into                                 ";
        $sSqlIncluiArrebanco.= "   arrebanco ( k00_numpre,                   ";
        $sSqlIncluiArrebanco.= "               k00_numpar,                   ";
        $sSqlIncluiArrebanco.= "               k00_codbco,                   ";
        $sSqlIncluiArrebanco.= "               k00_codage,                   ";
        $sSqlIncluiArrebanco.= "               k00_numbco                    ";
        $sSqlIncluiArrebanco.= "             ) values (                      ";
        $sSqlIncluiArrebanco.= "               {$iNumDoc},    ";
        $sSqlIncluiArrebanco.= "               0,                            ";
        $sSqlIncluiArrebanco.= "               {$oBancoAgencia->banco},      ";
        $sSqlIncluiArrebanco.= "               '{$oBancoAgencia->agencia}',  ";
        $sSqlIncluiArrebanco.= "               '{$oDadosDocumento->iNumbco}' ";
        $sSqlIncluiArrebanco.= "             );                              ";

        if ( !db_query($conn,$sSqlIncluiArrebanco) ) {
          throw new Exception("ERRO: Erro ao gerar arrebanco, {$sSqlIncluiArrebanco} - ".pg_last_error($conn));
        }
      }
    }
  }
}
db_logTitulo(" PROCESSA RECIBOS ANULADOS",$sArquivoLog,$iParamLog);

$sSqlIntegraReciboAnulado    = " select distinct                                                                                ";
$sSqlIntegraReciboAnulado   .= "        integra_recibo_anulado.sequencial as seqanu,                                            ";
$sSqlIntegraReciboAnulado   .= "        integra_recibo.*,                                                                       ";
$sSqlIntegraReciboAnulado   .= "        integra_recibo_detalhe.ano_competencia_origem,                                          ";
$sSqlIntegraReciboAnulado   .= "        integra_recibo_detalhe.mes_competencia_origem                                           ";
$sSqlIntegraReciboAnulado   .= "   from integra_recibo_anulado                                                                  ";
$sSqlIntegraReciboAnulado   .= "        inner join integra_recibo                                                               ";
$sSqlIntegraReciboAnulado   .= "                on integra_recibo.sequencial             = integra_recibo_anulado.integra_recibo";
$sSqlIntegraReciboAnulado   .= "        inner join integra_recibo_detalhe                                                       ";
$sSqlIntegraReciboAnulado   .= "                on integra_recibo_detalhe.integra_recibo = integra_recibo.sequencial            ";
$sSqlIntegraReciboAnulado   .= "  where integra_recibo_anulado.processado is false                                              ";

$rsIntegraReciboAnulado      = db_query($connDestino,$sSqlIntegraReciboAnulado);
$iLinhasIntegraReciboAnulado = pg_num_rows($rsIntegraReciboAnulado);

if ( $iLinhasIntegraReciboAnulado > 0 ) {

  db_log("Total de Registros Encontrados : {$iLinhasIntegraReciboAnulado}",$sArquivoLog,$iParamLog);
  db_log("\n",$sArquivoLog,1);

  for ( $iIndRecibo=0; $iIndRecibo < $iLinhasIntegraReciboAnulado; $iIndRecibo++ ) {

    $oIntegraReciboAnulado = db_utils::fieldsMemory($rsIntegraReciboAnulado,$iIndRecibo);

    logProcessamento($iIndRecibo,$iLinhasIntegraReciboAnulado,$iParamLog);

    $sSqlReceitaDebito  = " select k00_numpre,k00_numpar,k00_receit ";
    $sSqlReceitaDebito .= "   from arrecad    ";
    $sSqlReceitaDebito .= "        inner join issvar on issvar.q05_numpre = arrecad.k00_numpre ";
    $sSqlReceitaDebito .= "                         and issvar.q05_numpar = arrecad.k00_numpar ";
    $sSqlReceitaDebito .= "  where q05_ano = {$oIntegraReciboAnulado->ano_competencia_origem}  ";
    $sSqlReceitaDebito .= "    and q05_mes = {$oIntegraReciboAnulado->mes_competencia_origem}  ";

    $rsReceitaDebito    = db_query($conn,$sSqlReceitaDebito);

    if ( pg_num_rows($rsReceitaDebito) == 0 ) {
      $sMsgLog  = "Anulação de Recibo: {$oIntegraReciboAnulado->seqanu} ";
      $sMsgLog .= "Código Recibo: {$oIntegraReciboAnulado->sequencial} não processado! ";
      $sMsgLog .= "Débito não encontrado! ";
      db_log($sMsgLog,$sArquivoLog,2);
      continue;
    }
    $oDadosCancelarArrecad = db_utils::fieldsMemory($rsReceitaDebito,0);

    $aDadosDebitos['Numpre']  = $oDadosCancelarArrecad->k00_numpre;
    $aDadosDebitos['Numpar']  = $oDadosCancelarArrecad->k00_numpar;
    $aDadosDebitos['Receita'] = $oDadosCancelarArrecad->k00_receit;
    $aDebitos = array($aDadosDebitos);

    try {
      $oCancelaDebito->setArreHistTXT("Cancelado pela importação WebISS");
      $oCancelaDebito->geraCancelamento($aDebitos);
    } catch (Exception $eException) {
      throw new Exception("ERRO-47: {$eException->getMessage()}");
    }

    $sSqlAlteraIntegraReciboAnulado  = " update integra_recibo_anulado                        ";
    $sSqlAlteraIntegraReciboAnulado .= "    set processado = true                             ";
    $sSqlAlteraIntegraReciboAnulado .= "  where sequencial = {$oIntegraReciboAnulado->seqanu} ";

    $rsAlteraIntegraReciboAnulado = db_query($connDestino,$sSqlAlteraIntegraReciboAnulado);

    if (!$rsAlteraIntegraReciboAnulado) {
      throw new Exception("ERRO-48: ".pg_last_error($connDestino)." ".$sSqlAlteraIntegraReciboAnulado);
    }
  }

} else {
  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
}

db_logTitulo(" PROCESSA RECIBOS BAIXADOS",$sArquivoLog,$iParamLog);

$sSqlReciboSemBaixa  = " select integra_recibo.*                                                             ";
$sSqlReciboSemBaixa .= "   from integra_recibo                                                               ";
$sSqlReciboSemBaixa .= "        left join integra_recibo_baixa_detalhe on integra_recibo_baixa_detalhe.integra_recibo = integra_recibo.sequencial";
$sSqlReciboSemBaixa .= "  where integra_recibo_baixa_detalhe.sequencial is null;                             ";

$rsReciboSemBaixa      = db_query($connDestino,$sSqlReciboSemBaixa);
$iLinhasReciboSemBaixa = pg_num_rows($rsReciboSemBaixa);

if ( $iLinhasReciboSemBaixa > 0 ) {

  db_log("Total de Registros Encontrados : {$iLinhasReciboSemBaixa}",$sArquivoLog,$iParamLog);
  db_log("\n",$sArquivoLog,1);

  for ( $iInd=0; $iInd < $iLinhasReciboSemBaixa; $iInd++ ) {

    $oReciboSemBaixa = db_utils::fieldsMemory($rsReciboSemBaixa,$iInd);

    logProcessamento($iInd,$iLinhasReciboSemBaixa,$iParamLog);

    $sSqlSituacaoDebito  = "   select arrepaga.k00_numpre     as numpre,                                                                 ";
    $sSqlSituacaoDebito .= "          sum(arrepaga.k00_valor) as valor,                                                                  ";
    $sSqlSituacaoDebito .= "          case                                                                                               ";
    $sSqlSituacaoDebito .= "            when arreidret.idret is not null then disarq.arqret                                              ";
    $sSqlSituacaoDebito .= "            else null                                                                                        ";
    $sSqlSituacaoDebito .= "          end                 as nome_arquivo,                                                               ";
    $sSqlSituacaoDebito .= "          case                                                                                               ";
    $sSqlSituacaoDebito .= "            when arreidret.idret is not null then disarq.k15_codbco                                          ";
    $sSqlSituacaoDebito .= "            else null                                                                                        ";
    $sSqlSituacaoDebito .= "          end                 as cod_banco,                                                                  ";
    $sSqlSituacaoDebito .= "          case                                                                                               ";
    $sSqlSituacaoDebito .= "            when arreidret.idret is not null then disbanco.dtpago                                            ";
    $sSqlSituacaoDebito .= "            else k00_dtpaga                                                                                  ";
    $sSqlSituacaoDebito .= "          end                 as data_processamento,                                                         ";
    $sSqlSituacaoDebito .= "          case                                                                                               ";
    $sSqlSituacaoDebito .= "            when arreidret.idret is not null then 'B'                                                        ";
    $sSqlSituacaoDebito .= "            else 'C'                                                                                         ";
    $sSqlSituacaoDebito .= "          end                 as local_pagto,                                                                ";
    $sSqlSituacaoDebito .= "          'Q'                 as tipo_baixa                                                                  ";
    $sSqlSituacaoDebito .= "     from arrepaga                                                                                           ";
    $sSqlSituacaoDebito .= "          left join arreidret on arreidret.k00_numpre = arrepaga.k00_numpre                                  ";
    $sSqlSituacaoDebito .= "                             and arreidret.k00_numpar = arrepaga.k00_numpar                                  ";
    $sSqlSituacaoDebito .= "          left join disbanco  on disbanco.idret       = arreidret.idret                                      ";
    $sSqlSituacaoDebito .= "          left join disarq    on disarq.codret        = disbanco.codret                                      ";
    $sSqlSituacaoDebito .= "    where arrepaga.k00_numpre = {$oReciboSemBaixa->numdoc}                                                   ";
    $sSqlSituacaoDebito .= "      and arrepaga.k00_numpar = {$oReciboSemBaixa->mes_competencia}                                          ";
    $sSqlSituacaoDebito .= " group by numpre,                                                                                            ";
    $sSqlSituacaoDebito .= "          nome_arquivo,                                                                                      ";
    $sSqlSituacaoDebito .= "          cod_banco,                                                                                         ";
    $sSqlSituacaoDebito .= "          data_processamento,                                                                                ";
    $sSqlSituacaoDebito .= "          local_pagto,                                                                                       ";
    $sSqlSituacaoDebito .= "          tipo_baixa                                                                                         ";
    $sSqlSituacaoDebito .= "                                                                                                             ";
    $sSqlSituacaoDebito .= "  union all                                                                                                  ";
    $sSqlSituacaoDebito .= "                                                                                                             ";
    $sSqlSituacaoDebito .= "   select arrecant.k00_numpre      as numpre,                                                                ";
    $sSqlSituacaoDebito .= "          arrecant.k00_valor       as valor,                                                                 ";
    $sSqlSituacaoDebito .= "          null                     as nome_arquivo,                                                          ";
    $sSqlSituacaoDebito .= "          null                     as cod_banco,                                                             ";
    $sSqlSituacaoDebito .= "          cancdebitosproc.k23_data as data_processamento,                                                    ";
    $sSqlSituacaoDebito .= "          null                     as local_pagto,                                                           ";
    $sSqlSituacaoDebito .= "          'C'                      as tipo_baixa                                                             ";
    $sSqlSituacaoDebito .= "     from cancdebitosprocreg                                                                                 ";
    $sSqlSituacaoDebito .= "          inner join cancdebitosreg  on cancdebitosreg.k21_sequencia = cancdebitosprocreg.k24_cancdebitosreg ";
    $sSqlSituacaoDebito .= "          inner join cancdebitosproc on cancdebitosproc.k23_codigo   = cancdebitosprocreg.k24_codigo         ";
    $sSqlSituacaoDebito .= "          inner join arrecant        on arrecant.k00_numpre          = cancdebitosreg.k21_numpre             ";
    $sSqlSituacaoDebito .= "                                    and arrecant.k00_numpar          = cancdebitosreg.k21_numpar             ";
    $sSqlSituacaoDebito .= "    where cancdebitosreg.k21_numpre = {$oReciboSemBaixa->numdoc}                                             ";
    $sSqlSituacaoDebito .= "      and cancdebitosreg.k21_numpar = {$oReciboSemBaixa->mes_competencia}                                    ";
    $sSqlSituacaoDebito .= "                                                                                                             ";
    $sSqlSituacaoDebito .= "  union all                                                                                                  ";
    $sSqlSituacaoDebito .= "                                                                                                             ";
    $sSqlSituacaoDebito .= "   select arreold.k00_numpre  as numpre,                                                                     ";
    $sSqlSituacaoDebito .= "          arreold.k00_valor   as valor,                                                                      ";
    $sSqlSituacaoDebito .= "          null                as nome_arquivo,                                                               ";
    $sSqlSituacaoDebito .= "          null                as cod_banco,                                                                  ";
    $sSqlSituacaoDebito .= "          divida.v01_dtinsc   as data_processamento,                                                         ";
    $sSqlSituacaoDebito .= "          null                as local_pagto,                                                                ";
    $sSqlSituacaoDebito .= "          'I'                 as tipo_baixa                                                                  ";
    $sSqlSituacaoDebito .= "     from divold                                                                                             ";
    $sSqlSituacaoDebito .= "          inner join divida  on divida.v01_coddiv  = divold.k10_coddiv                                       ";
    $sSqlSituacaoDebito .= "          inner join arreold on arreold.k00_numpre = divold.k10_numpre                                       ";
    $sSqlSituacaoDebito .= "                            and arreold.k00_numpar = divold.k10_numpar                                       ";
    $sSqlSituacaoDebito .= "                            and arreold.k00_receit = divold.k10_receita                                      ";
    $sSqlSituacaoDebito .= "    where divold.k10_numpre = {$oReciboSemBaixa->numdoc}                                                     ";
    $sSqlSituacaoDebito .= "      and divold.k10_numpar = {$oReciboSemBaixa->mes_competencia}                                            ";

    $rsSituacaoDebito    = db_query($conn,$sSqlSituacaoDebito);

    if ( $rsSituacaoDebito && pg_num_rows($rsSituacaoDebito) > 0 ) {

      $oSituacaoDebito = db_utils::fieldsMemory($rsSituacaoDebito,0);

      $sMsgLog  = "Processado Numpre : {$oSituacaoDebito->numpre} Sequencial Integra : {$oReciboSemBaixa->sequencial} ";
      $sMsgLog .= "Tipo Baixa : {$oSituacaoDebito->tipo_baixa}";
      db_log($sMsgLog,$sArquivoLog,2);

      $oSituacaoDebito->munic_ibge  = $iCodIBGE;
      $oSituacaoDebito->dataimp     = $dtDataHoje;
      $oSituacaoDebito->horaimp     = db_hora();
      $oSituacaoDebito->processado  = "f";

      $oIntegraReciboBaixa->setByLineOfDBUtils($oSituacaoDebito);

      try {
        $iCodReciboBaixa = $oIntegraReciboBaixa->insertValue();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-32: {$eException->getMessage()}");
      }

      try {
        $oIntegraReciboBaixa->persist();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-33: {$eException->getMessage()}");
      }

      $oSituacaoDebito->integra_recibo        = $oReciboSemBaixa->sequencial;
      $oSituacaoDebito->integra_recibo_baixa  = $iCodReciboBaixa;
      $oSituacaoDebito->integra_recibo_numdoc = $oSituacaoDebito->numpre;
      $oSituacaoDebito->data_baixa            = $oSituacaoDebito->data_processamento;
      $oSituacaoDebito->valor_imposto         = $oSituacaoDebito->valor;
      $oSituacaoDebito->valor_pago            = $oSituacaoDebito->valor;
      $oSituacaoDebito->valor_juros           = '0';
      $oSituacaoDebito->valor_multa           = '0';
      $oSituacaoDebito->valor_desconto        = '0';

      $oIntegraReciboBaixaDetalhe->setByLineOfDBUtils($oSituacaoDebito);

      try {
        $oIntegraReciboBaixaDetalhe->insertValue();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-32: {$eException->getMessage()}");
      }

      try {
        $oIntegraReciboBaixaDetalhe->persist();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-33: {$eException->getMessage()}");
      }
    }
  }
} else {
  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
}