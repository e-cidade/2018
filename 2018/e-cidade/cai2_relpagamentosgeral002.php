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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));

/**
 * Define tempo limite para o script em 30min
 */
if( !ini_get('safe_mode') ){

  set_time_limit(1800);
  ini_set('max_execution_time', 1800);
}

/**
 * Função para ser chamada quando tempo limite exceder
 */
function tempoLimiteRelatorio() {

  echo '<script>
    location.href = "db_erros.php?fechar=true&db_erro=Tempo de execução do relatório excedido.";
  </script>';
}

/**
 * Define função 'tempoLimitRelatorio' para ser chamada antes do script morrer
 */
register_shutdown_function('tempoLimiteRelatorio');

$oGet = db_utils::postMemory($_GET);
//Processa a forma de ordenação do relatório
$sOrdenar = " order by ";
$sAscDesc = "";

if ($oGet->cboOrdenacaoValor == 1) {
  $sAscDesc .= " asc ";
} else {
  $sAscDesc .= " desc ";
}

$iTipoRelatorio = 1;

$sFiltroData         = "Data: ";
$sFiltroAgrupamento  = "Agrupamento: ";
$sFiltroOrigem       = "Origem: ";
$sFiltroOrdenacao    = "Ordenação: ";
$sFiltroDemonstracao = "Demonstração: ";
$sFiltroTotalizacao  = "Totalização: ";
$sFiltroPeriodo      = "Período: ";
$sFiltroValorInicial = "Valor: ";


if ($oGet->cboTotalizacao == 1) {
  $sFiltroTotalizacao .= " Imprimir Dados e Totalizações ";
} else if ($oGet->cboTotalizacao == 2) {
  $sFiltroTotalizacao .= " Imprimir Somente Totalizações ";
} else if ($oGet->cboTotalizacao == 3) {
  $sFiltroTotalizacao .= " Imprimir Somente Dados ";
}
//Controla se deve fazer ordenação de valor no codigo php
$lOrderValor = false;

if ($oGet->cboAgrupamento == 1) {

  $sFiltroAgrupamento .= " Tipo de Débito ";

  $iTipoRelatorio  = 1;

  switch ($oGet->cboOrdenacao) {

    case 1: //Tipo Débito
      $sFiltroOrdenacao .= " Tipo Débito ";
      $sOrdenar .= " k00_tipo ";
      break;

    case 2: //Valor
      $sFiltroOrdenacao .= " Valor ";
      $sOrdenar .= " k02_codigo, valor_pago ";
      $sOrdenar .= $sAscDesc;
      $lOrderValor = true;
      break;

  }

} else if ($oGet->cboAgrupamento == 2) {

  $sFiltroAgrupamento .= " Receita ";

  $iTipoRelatorio = 2;

  switch ($oGet->cboOrdenacao) {

    case 1: //Receita
      $sFiltroOrdenacao  .= " Receita ";
      $sOrdenar          .= " k02_codigo ";
      break;

    case 2: //Valor
      $sFiltroOrdenacao .= " Valor ";
      $sOrdenar   .= " k02_codigo,valor_pago ";
      $sOrdenar   .= $sAscDesc;
      $lOrderValor = true;
      break;

  }
} else if ($oGet->cboAgrupamento == 3 && $oGet->cboOrigem == 2) {

  $sFiltroAgrupamento .= " Origem ";
  $sFiltroOrigem      .= " Matrícula ";
  $iTipoRelatorio = 3;

  switch ($oGet->cboOrdenacao) {

    case 1: //Matrícula
      $sFiltroOrdenacao .= " Matrícula ";
      $sOrdenar .= " origem ";
      break;

    case 2: //Nome Contribuinte
      $sFiltroOrdenacao .= " Nome Contribuinte ";
      $sOrdenar .= " z01_nome ";
      break;

    case 3: //Tipo Débito
      $sFiltroOrdenacao .= " Tipo Débito ";
      $sOrdenar .= " arretipo.k00_tipo ";
      break;

    case 4: //Receita
      $sFiltroOrdenacao .= " Receita ";
      $sOrdenar .= " k02_codigo ";
      break;

    case 5: //Valor
      $sFiltroOrdenacao .= " Valor ";
      $sOrdenar    .= " origem,valor_pago ";
      $sOrdenar    .= $sAscDesc;
      $lOrderValor  = true;
      break;

  }
} else if ($oGet->cboAgrupamento == 3 && $oGet->cboOrigem == 3){

  $sFiltroAgrupamento .= " Origem ";
  $sFiltroOrigem      .= " Inscrição ";
  $iTipoRelatorio = 4;

  switch ($oGet->cboOrdenacao) {

    case 1: //Inscrição
      $sFiltroOrdenacao .= " Inscrição ";
      $sOrdenar .= " origem ";
      break;

    case 2: //Nome Contribuinte
      $sFiltroOrdenacao .= " Nome Contribuinte ";
      $sOrdenar .= " z01_nome";
      break;

    case 3: //Tipo Débito
      $sFiltroOrdenacao .= " Tipo Débito ";
      $sOrdenar .= " arretipo.k00_tipo ";
      break;

    case 4: //Receita
      $sFiltroOrdenacao .= " Receita ";
      $sOrdenar .= " k02_codigo ";
      break;

    case 5: //Valor
      $sFiltroOrdenacao .= " Valor ";
      $sOrdenar   .= " origem,valor_pago ";
      $sOrdenar   .= $sAscDesc;
      $lOrderValor = true;
      break;

  }
} else if ($oGet->cboAgrupamento == 3 && ($oGet->cboOrigem == 1 || $oGet->cboOrigem == 4 )){

  $sFiltroAgrupamento .= " Origem ";
  $sFiltroOrigem      .= " CGM Geral ";

  $iTipoRelatorio = 5;

  switch ($oGet->cboOrdenacao) {

    case 1: //CGM
      $sFiltroOrdenacao .= " CGM ";
      $sOrdenar .= " z01_numcgm ";
      break;

    case 2: //Tipo Débito
      $sFiltroOrdenacao .= " Tipo Débito ";
      $sOrdenar .= " k00_tipo ";
      break;

    case 3: //Receita
      $sFiltroOrdenacao .= " Receita ";
      $sOrdenar .= " k02_codigo ";
      break;

    case 4: //Valor
      $sFiltroOrdenacao .= " Valor ";
      $sOrdenar   .= " tipo_origem,valor_pago ";
      $sOrdenar   .= $sAscDesc;
      $lOrderValor = true;
      break;
  }
}
//Fim do processamento da ordenação do relatório

//Inicio do Filtro do Relatório
$sWhere  = "";
$sAnd    = "";

$sAnd = $sWhere != "" ? " and " : "";

$sFrom = "arrepaga";

// Filtro Pagamentos Por Compensação Parcial/Total do Débito
$sWhere .= $sAnd;
$sWhere .= "  not exists(select 1                                                                     ";
$sWhere .= "               from abatimentoutilizacaodestino                                           ";
$sWhere .= "                    inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao  ";
$sWhere .= "                    inner join abatimento on abatimento.k125_sequencial = k157_abatimento ";
$sWhere .= "                    inner join abatimentorecibo on k127_abatimento = k125_sequencial      ";
$sWhere .= "              where k170_numpre = arrepaga.k00_numpre                                     ";
$sWhere .= "                and k170_numpar = arrepaga.k00_numpar                                     ";
$sWhere .= "                and k157_tipoutilizacao = '2'                                             ";
$sWhere .= "                and k125_tipoabatimento = 3                                               ";
$sWhere .= "              limit 1)                                                                    ";
$sWhere .= "  and not exists(select 1                                                                           ";
$sWhere .= "                   from abatimentorecibo                                                            ";
$sWhere .= "                        inner join abatimento on abatimento.k125_sequencial = k127_abatimento       ";
$sWhere .= "                        left  join termo on termo.v07_numpre = abatimentorecibo.k127_numpreoriginal ";
$sWhere .= "                                        and termo.v07_situacao = 2                                  ";
$sWhere .= "                  where abatimentorecibo.k127_numpreoriginal = arrepaga.k00_numpre                  ";
$sWhere .= "                    and k125_tipoabatimento = 4                                                     ";
$sWhere .= "                    and termo is null                                                               ";
$sWhere .= "                  limit 1)                                                                          ";

$sAnd = $sWhere != "" ? " and " : "";

// Fim Filtro Pagamentos Por Compensação Parcial/Total do Débito

//Verifica se foi escolhido a opção 1-Processamento
if ($oGet->cboData == 1 && (trim($oGet->dtini) != "" || trim($oGet->dtfim) != "")){

  $sFiltroData .= " Processamento";
  if (trim($oGet->dtini) != "" && trim($oGet->dtfim) != "") {

    $sFiltroPeriodo .= db_formatar($oGet->dtini, "d"). " à " . db_formatar($oGet->dtfim, "d");
    $sWhere .= $sAnd." arrepaga.k00_dtpaga between '".$oGet->dtini."' and '".$oGet->dtfim."' ";
  } else if (trim($oGet->dtini) != "") {

    $sFiltroPeriodo .= " até ".db_formatar($oGet->dtini, "d");
    $sWhere .= $sAnd." arrepaga.k00_dtpaga >= '".$oGet->dtini."'";
  } else if (trim($oGet->dtfim) != "") {

    $sFiltroPeriodo .= " à partir " . db_formatar($oGet->dtfim, "d");
    $sWhere .= $sAnd." arrepaga.k00_dtpaga <= '".$oGet->dtfim."'";
  }
//Verifica se foi escolhido a opção 2-Efetivo Pagamento
} else if ($oGet->cboData == 2 && (trim($oGet->dtini) != "" || trim($oGet->dtfim) != "")){

	$sFrom  = "            disbanco                                                                      ";
	$sFrom .= "            inner join arreidret  on arreidret.idret       = disbanco.idret               ";
	$sFrom .= "            inner join arrepaga   on arrepaga.k00_numpre   = arreidret.k00_numpre         ";
	$sFrom .= "                                 and arrepaga.k00_numpar   = arreidret.k00_numpar         ";

  $sFiltroData .= " Efetivo Pagamento";
  if (trim($oGet->dtini) != "" && trim($oGet->dtfim) != "") {
    $sFiltroPeriodo .= db_formatar($oGet->dtini, "d"). " à " . db_formatar($oGet->dtfim, "d");
//    $sWhere .= $sAnd." disbanco.dtpago between '".$oGet->dtini."' and '".$oGet->dtfim."' ";
    $sWhere .= $sAnd." disbanco.dtpago between '".$oGet->dtini."' and '".$oGet->dtfim."' ";
  } else if (trim($oGet->dtini) != "") {
    $sFiltroPeriodo .= " até ".db_formatar($oGet->dtini, "d");
//    $sWhere .= $sAnd." disbanco.dtpago >= '".$oGet->dtini."'";
    $sWhere .= $sAnd." ( select count(*) from disbanco inner join caixa.arreidret on disbanco.idret = arreidret.idret where arreidret.k00_numpre = arrepaga.k00_numpre and arreidret.k00_numpar = arrepaga.k00_numpar and disbanco.dtpago >= '".$oGet->dtini."' ) > 0 ";
  } else if (trim($oGet->dtfim) != "") {
    $sFiltroPeriodo .= " à partir " . db_formatar($oGet->dtfim, "d");
//    $sWhere .= $sAnd." disbanco.dtpago <= '".$oGet->dtfim."'";
    $sWhere .= $sAnd." ( select count(*) from disbanco inner join caixa.arreidret on disbanco.idret = arreidret.idret where arreidret.k00_numpre = arrepaga.k00_numpre and arreidret.k00_numpar = arrepaga.k00_numpar and disbanco.dtpago <= '".$oGet->dtfim."' ) > 0 ";
  }

}

$sAnd = $sWhere != "" ? " and " : "";

if (trim($oGet->vlIni) != "" && trim($oGet->vlFim) != "") {
  $sFiltroValorInicial .= $oGet->vlIni. " à " .$oGet->vlFim;
  $sWhere .= $sAnd." arrepaga.k00_valor between ".$oGet->vlIni." and ".$oGet->vlFim;
} else if (trim($oGet->vlIni) != "" ) {
  $sFiltroValorInicial .= " até ".$oGet->vlIni;
  $sWhere .= $sAnd." arrepaga.k00_valor >=".$oGet->vlIni;
} else if (trim($oGet->vlFim) != "") {
  $sFiltroValorInicial .= " à partir ".$oGet->vlFim;
  $sWhere .= $sAnd." arrepaga.k00_valor <=".$oGet->vlFim;
}

$sAnd = $sWhere != "" ? " and " : "";

if (trim($oGet->arretipo) != "") {

  $sWhere .= $sAnd." exists (select 1 from arrecant                             ";
  $sWhere .= "                where k00_tipo in {$oGet->arretipo}               ";
  $sWhere .= "                  and arrecant.k00_numpre = arrepaga.k00_numpre   ";
  $sWhere .= "                  and arrecant.k00_numpar = arrepaga.k00_numpar   ";
  $sWhere .= "               union                                              ";
  $sWhere .= "               select 1 from recibo                               ";
  $sWhere .= "                where k00_tipo in {$oGet->arretipo}               ";
  $sWhere .= "                  and recibo.k00_numpre = arrepaga.k00_numpre     ";
  $sWhere .= "                  and recibo.k00_numpar = arrepaga.k00_numpar )   ";

}

$sAnd = $sWhere != "" ? " and " : "";

if (trim($oGet->receita) != "") {

  $sWhere .= $sAnd." tabrec.k02_codigo in".$oGet->receita;
}

$sAnd = $sWhere != "" ? " and " : "";

if (trim($oGet->cboDemonstracao) != "" && $oGet->cboDemonstracao == 2) {
  $sFiltroDemonstracao .= " Imposto/Taxa ";
  $sWhere .= $sAnd." arrepaga.k00_hist in (400,401) ";
} else if (trim($oGet->cboDemonstracao) != "" && $oGet->cboDemonstracao == 3) {
  $sFiltroDemonstracao .= " Juro e Multa ";
  $sWhere .= $sAnd." arrepaga.k00_hist not in (400,401) ";
} else {
  $sFiltroDemonstracao .= " Ambos ";
}

$sAnd = $sWhere != "" ? " and " : "";

if ($oGet->cboAgrupamento == 3) {
  if($oGet->cboOrigem == 2) {
    $sWhere .= $sAnd." arrematric.k00_numpre is not null ";
  } elseif($oGet->cboOrigem == 3) {
    $sWhere .= $sAnd." arreinscr.k00_numpre  is not null ";
  }
}

if ($sWhere != "") {
  $sWhere = " where ". $sWhere;
}

$sSql  = "select k02_codigo,                                                                         ";
$sSql .= "        k02_descr,                                                                        ";
$sSql .= "        k00_tipo,                                                                         ";
$sSql .= "        k00_descr,                                                                        ";
$sSql .= "        k00_tipo_recibo,                                                                  ";
$sSql .= "        k00_descr_recibo,                                                                 ";
$sSql .= "        z01_numcgm,                                                                       ";
$sSql .= "        z01_nome,                                                                         ";
$sSql .= "        tipo_origem,                                                                      ";
$sSql .= "        origem,                                                                           ";
$sSql .= "        perc_origem,                                                                      ";
$sSql .= "        sum((k00_valor * perc_origem)/100) as valor_pago                                  ";
$sSql .= "   from (                                                                                 ";
$sSql .= "     select tabrec.k02_codigo,                                                            ";
$sSql .= "            tabrec.k02_descr,                                                             ";
$sSql .= "            ( select k00_tipo                                       ";
$sSql .= "               from arrecant                                                              ";
$sSql .= "              where k00_numpre = arrepaga.k00_numpre and k00_numpar = arrepaga.k00_numpar limit 1 ";
$sSql .= "            ) as k00_tipo,                                                 ";
$sSql .= "            ( select k00_descr                                    ";
$sSql .= "              from arrecant                                                               ";
$sSql .= "              inner join arretipo on arretipo.k00_tipo = arrecant.k00_tipo                ";
$sSql .= "              where k00_numpre = arrepaga.k00_numpre and k00_numpar = arrepaga.k00_numpar limit 1 ";
$sSql .= "              ) as k00_descr,                                                      ";
$sSql .= "            (select k00_tipo                                                              ";
$sSql .= "               from recibo                                                                ";
$sSql .= "              where k00_numpre = arrepaga.k00_numpre limit 1) as k00_tipo_recibo,         ";
$sSql .= "            (select k00_descr                                                             ";
$sSql .= "               from recibo                                                                ";
$sSql .= "              inner join arretipo on arretipo.k00_tipo = recibo.k00_tipo                  ";
$sSql .= "              where k00_numpre = arrepaga.k00_numpre limit 1) as k00_descr_recibo,        ";
$sSql .= "            cgm.z01_numcgm,                                                               ";
$sSql .= "            cgm.z01_nome,                                                                 ";
$sSql .= "            case                                                                          ";
$sSql .= "              when arrematric.k00_numpre is not null then 'M'                             ";
$sSql .= "              when arreinscr.k00_numpre  is not null then 'I'                             ";
$sSql .= "              else 'C'                                                                    ";
$sSql .= "            end as tipo_origem,                                                           ";
$sSql .= "            case                                                                          ";
$sSql .= "              when arrematric.k00_numpre is not null then arrematric.k00_matric           ";
$sSql .= "              when arreinscr.k00_numpre  is not null then arreinscr.k00_inscr             ";
$sSql .= "              else arrepaga.k00_numcgm                                                    ";
$sSql .= "            end as origem,                                                                ";
$sSql .= "            case                                                                          ";
$sSql .= "              when arrematric.k00_numpre is not null then arrematric.k00_perc             ";
$sSql .= "              when arreinscr.k00_numpre  is not null then arreinscr.k00_perc              ";
$sSql .= "              else 100                                                                    ";
$sSql .= "            end as perc_origem,                                                           ";
$sSql .= "            arrepaga.k00_valor                                                            ";
$sSql .= "       from {$sFrom} 																																			";

$sSql .= "            inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre          ";
$sSql .= "                                 and arreinstit.k00_instit = ".  db_getsession("DB_instit");

$sSql .= "            inner join tabrec     on tabrec.k02_codigo     = arrepaga.k00_receit          ";
$sSql .= "            inner join cgm        on cgm.z01_numcgm        = arrepaga.k00_numcgm          ";
$sSql .= "            left join arrematric  on arrematric.k00_numpre = arrepaga.k00_numpre          ";
$sSql .= "            left join arreinscr   on arreinscr.k00_numpre  = arrepaga.k00_numpre          ";

$sGroup  = "        ) as pagamentos                                                                 ";
$sGroup .= "  group                                                                                 ";
$sGroup .= "     by k02_codigo,                                                                     ";
$sGroup .= "        k02_descr,                                                                      ";
$sGroup .= "        k00_tipo,                                                                       ";
$sGroup .= "        k00_descr,                                                                      ";
$sGroup .= "        k00_tipo_recibo,                                                                ";
$sGroup .= "        k00_descr_recibo,                                                               ";
$sGroup .= "        z01_numcgm,                                                                     ";
$sGroup .= "        z01_nome,                                                                       ";
$sGroup .= "        tipo_origem,                                                                    ";
$sGroup .= "        origem,                                                                         ";
$sGroup .= "        perc_origem                                                                     ";

$sSql = $sSql . $sWhere . $sGroup . $sOrdenar;

//Fim do Filtro do Relatório
$oDaoArrepaga = db_utils::getDao("arrepaga");
$rsSql        = $oDaoArrepaga->sql_record($sSql);
if ($oDaoArrepaga->numrows == 0) {
   db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum resultado retornado para os filtros selecionados.");
}

if($oDaoArrepaga->erro_status == '0') {
   db_redireciona("db_erros.php?fechar=true&db_erro=Ocorreu um erro na consulta.");
}

$head1 = "Relatório de Pagamentos ";
$head2 = $sFiltroData;
$head3 = $sFiltroPeriodo;
$head4 = $sFiltroValorInicial;
$head5 = $sFiltroAgrupamento;
$head6 = $sFiltroOrigem;
$head7 = $sFiltroOrdenacao;
$head8 = $sFiltroDemonstracao;
$head9 = $sFiltroTotalizacao;

//$aDados = db_utils::getCollectionByRecord($rsSql);

$aPagamentos = array();
$aTotais     = array();

/**
 * Percorremos os registros retornados do banco
 */
//foreach ($aDados as $oPagamento) {
$iNumRows = pg_num_rows($rsSql);
for ($i = 0; $i < $iNumRows; $i++) {

  $oPagamento = db_utils::fieldsMemory($rsSql, $i);

  $oCgmPagamento                      = new stdClass();
  $oCgmPagamento->tipoOrigem          = $oPagamento->tipo_origem;
  $oCgmPagamento->origem              = $oPagamento->origem;
  $oCgmPagamento->codigoReceita       = $oPagamento->k02_codigo;
  $oCgmPagamento->descricaoReceita    = $oPagamento->k02_descr;
  $oCgmPagamento->descricaoTipoDebito = $oPagamento->k00_descr ;
  $oCgmPagamento->codigoTipoDebito    = $oPagamento->k00_tipo;
  if (trim($oPagamento->k00_tipo) == "") {
    $oCgmPagamento->descricaoTipoDebito = $oPagamento->k00_descr_recibo ;
    $oCgmPagamento->codigoTipoDebito    = $oPagamento->k00_tipo_recibo;
  }

  $oCgmPagamento->valorPago           = $oPagamento->valor_pago;
  $oCgmPagamento->cgm                 = $oPagamento->z01_numcgm;
  $oCgmPagamento->contribuinte        = $oPagamento->z01_nome;
  $oCgmPagamento->matricula           = $oPagamento->origem;
  $oCgmPagamento->inscricao           = $oPagamento->origem;

  switch ($iTipoRelatorio) {

    /**
     * Agrupamento por Tipo de debito
     */
    case 1:

      if (!isset($aPagamentos[$oCgmPagamento->codigoTipoDebito])) {

        $aPagamentos[$oCgmPagamento->codigoTipoDebito] = new stdClass;

        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->totalRegistros  = 0;
        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->totalGeral      = 0;
        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->dados           = array();
      }

      if (isset($aPagamentos[$oCgmPagamento->codigoTipoDebito]->dados[$oCgmPagamento->codigoReceita]) ) {
        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->dados[$oCgmPagamento->codigoReceita]->valorPago += $oCgmPagamento->valorPago;
      } else {

        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->dados[$oCgmPagamento->codigoReceita] = new stdClass;

        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->dados[$oCgmPagamento->codigoReceita]->valorPago        = $oCgmPagamento->valorPago;
        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->dados[$oCgmPagamento->codigoReceita]->codigoReceita    = $oCgmPagamento->codigoReceita;
        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->dados[$oCgmPagamento->codigoReceita]->descricaoReceita = $oCgmPagamento->descricaoReceita;

        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->codigoTipoDebito    = $oCgmPagamento->codigoTipoDebito;
        $aPagamentos[$oCgmPagamento->codigoTipoDebito]->descricaoTipoDebito = $oCgmPagamento->descricaoTipoDebito;
      }

      $aPagamentos[$oCgmPagamento->codigoTipoDebito]->totalRegistros += 1;
      $aPagamentos[$oCgmPagamento->codigoTipoDebito]->totalGeral     += $oCgmPagamento->valorPago;

      break;

    /**
     * Agrupamento por receita
     */
    case 2:

      if (isset($aPagamentos[$oCgmPagamento->codigoReceita])) {

        $aPagamentos[$oCgmPagamento->codigoReceita]->valorPago      += $oCgmPagamento->valorPago;
        $aPagamentos[$oCgmPagamento->codigoReceita]->totalRegistros += 1;
        $aPagamentos[$oCgmPagamento->codigoReceita]->totalGeral     += $oCgmPagamento->valorPago;
      } else {

        $aPagamentos[$oCgmPagamento->codigoReceita] = new stdClass;

        $aPagamentos[$oCgmPagamento->codigoReceita]->valorPago        = $oCgmPagamento->valorPago;
        $aPagamentos[$oCgmPagamento->codigoReceita]->codigoReceita    = $oCgmPagamento->codigoReceita;
        $aPagamentos[$oCgmPagamento->codigoReceita]->descricaoReceita = $oCgmPagamento->descricaoReceita;
        $aPagamentos[$oCgmPagamento->codigoReceita]->totalRegistros   = 1;
        $aPagamentos[$oCgmPagamento->codigoReceita]->totalGeral       = $oCgmPagamento->valorPago;
      }

      break;

    /**
     * Agrupamento por Origem matricula
     */
    case 3:

      if ($oCgmPagamento->tipoOrigem == 'M') {

        if (!isset($aPagamentos[$oCgmPagamento->matricula])) {

          $aPagamentos[$oCgmPagamento->matricula] = new stdClass;
          $aPagamentos[$oCgmPagamento->matricula]->dados = array();
          $aPagamentos[$oCgmPagamento->matricula]->totalReceita = array();
          $aPagamentos[$oCgmPagamento->matricula]->totalDebito = array();
        }

        if (isset($aPagamentos[$oCgmPagamento->matricula]->dados[$oCgmPagamento->codigoReceita])) {
          $aPagamentos[$oCgmPagamento->matricula]->dados[$oCgmPagamento->codigoReceita]->valorPago += $oCgmPagamento->valorPago;
        } else {
          $aPagamentos[$oCgmPagamento->matricula]->dados[$oCgmPagamento->codigoReceita] = new stdClass;

          $aPagamentos[$oCgmPagamento->matricula]->dados[$oCgmPagamento->codigoReceita]->valorPago = $oCgmPagamento->valorPago;
          $aPagamentos[$oCgmPagamento->matricula]->dados[$oCgmPagamento->codigoReceita]->codigoReceita = $oCgmPagamento->codigoReceita;
          $aPagamentos[$oCgmPagamento->matricula]->dados[$oCgmPagamento->codigoReceita]->descricaoReceita = $oCgmPagamento->descricaoReceita;
          $aPagamentos[$oCgmPagamento->matricula]->dados[$oCgmPagamento->codigoReceita]->codigoTipoDebito = $oCgmPagamento->codigoTipoDebito;
          $aPagamentos[$oCgmPagamento->matricula]->dados[$oCgmPagamento->codigoReceita]->descricaoTipoDebito = $oCgmPagamento->descricaoTipoDebito;

          $aPagamentos[$oCgmPagamento->matricula]->contribuinte = $oCgmPagamento->contribuinte;
          $aPagamentos[$oCgmPagamento->matricula]->cgm = $oCgmPagamento->cgm;
          $aPagamentos[$oCgmPagamento->matricula]->matricula = $oCgmPagamento->matricula;
        }

        //Totaliza por receita
        if (isset($aPagamentos[$oCgmPagamento->matricula]->totalReceita[$oCgmPagamento->codigoReceita])) {

          $aPagamentos[$oCgmPagamento->matricula]->totalReceita[$oCgmPagamento->codigoReceita]->totalRegistros += 1;
          $aPagamentos[$oCgmPagamento->matricula]->totalReceita[$oCgmPagamento->codigoReceita]->totalGeral += $oCgmPagamento->valorPago;
        } else {

          $aPagamentos[$oCgmPagamento->matricula]->totalReceita[$oCgmPagamento->codigoReceita] = new stdClass;
          $aPagamentos[$oCgmPagamento->matricula]->totalReceita[$oCgmPagamento->codigoReceita]->totalRegistros = 1;
          $aPagamentos[$oCgmPagamento->matricula]->totalReceita[$oCgmPagamento->codigoReceita]->totalGeral = $oCgmPagamento->valorPago;
          $aPagamentos[$oCgmPagamento->matricula]->totalReceita[$oCgmPagamento->codigoReceita]->codigoReceita = $oCgmPagamento->codigoReceita;
          $aPagamentos[$oCgmPagamento->matricula]->totalReceita[$oCgmPagamento->codigoReceita]->descricaoReceita = $oCgmPagamento->descricaoReceita;
        }

        //Totaliza por tipo de debito
        if (isset($aPagamentos[$oCgmPagamento->matricula]->totalDebito[$oCgmPagamento->codigoTipoDebito])) {

          $aPagamentos[$oCgmPagamento->matricula]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalRegistros += 1;
          $aPagamentos[$oCgmPagamento->matricula]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalGeral += $oCgmPagamento->valorPago;
        } else {

          $aPagamentos[$oCgmPagamento->matricula]->totalDebito[$oCgmPagamento->codigoTipoDebito] = new stdClass;

          $aPagamentos[$oCgmPagamento->matricula]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalRegistros = 1;
          $aPagamentos[$oCgmPagamento->matricula]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalGeral = $oCgmPagamento->valorPago;
          $aPagamentos[$oCgmPagamento->matricula]->totalDebito[$oCgmPagamento->codigoTipoDebito]->codigoTipoDebito = $oCgmPagamento->codigoTipoDebito;
          $aPagamentos[$oCgmPagamento->matricula]->totalDebito[$oCgmPagamento->codigoTipoDebito]->descricaoTipoDebito = $oCgmPagamento->descricaoTipoDebito;
        }

        //Totaliza por matricula
        if (isset($aPagamentos[$oCgmPagamento->matricula]->totalMatricula)) {
          $aPagamentos[$oCgmPagamento->matricula]->totalMatricula += $oCgmPagamento->valorPago;
        } else {
          $aPagamentos[$oCgmPagamento->matricula]->totalMatricula = $oCgmPagamento->valorPago;
        }
      }
      break;

      /**
       * Agrupamento por Origem inscricao
       */
      case 4:

        if ($oCgmPagamento->tipoOrigem == 'I') {

          if (!isset($aPagamentos[$oCgmPagamento->inscricao])) {

            $aPagamentos[$oCgmPagamento->inscricao] = new stdClass;
            $aPagamentos[$oCgmPagamento->inscricao]->dados = array();
            $aPagamentos[$oCgmPagamento->inscricao]->totalReceita = array();
            $aPagamentos[$oCgmPagamento->inscricao]->totalDebito = array();
          }

          if (isset($aPagamentos[$oCgmPagamento->inscricao]->dados[$oCgmPagamento->codigoReceita])) {

            $aPagamentos[$oCgmPagamento->inscricao]->dados[$oCgmPagamento->codigoReceita]->valorPago += $oCgmPagamento->valorPago;
          } else {

            $aPagamentos[$oCgmPagamento->inscricao]->dados[$oCgmPagamento->codigoReceita] = new stdClass;

            $aPagamentos[$oCgmPagamento->inscricao]->dados[$oCgmPagamento->codigoReceita]->valorPago = $oCgmPagamento->valorPago;
            $aPagamentos[$oCgmPagamento->inscricao]->dados[$oCgmPagamento->codigoReceita]->codigoReceita = $oCgmPagamento->codigoReceita;
            $aPagamentos[$oCgmPagamento->inscricao]->dados[$oCgmPagamento->codigoReceita]->descricaoReceita = $oCgmPagamento->descricaoReceita;
            $aPagamentos[$oCgmPagamento->inscricao]->dados[$oCgmPagamento->codigoReceita]->codigoTipoDebito = $oCgmPagamento->codigoTipoDebito;
            $aPagamentos[$oCgmPagamento->inscricao]->dados[$oCgmPagamento->codigoReceita]->descricaoTipoDebito = $oCgmPagamento->descricaoTipoDebito;

            $aPagamentos[$oCgmPagamento->inscricao]->cgm = $oCgmPagamento->cgm;
            $aPagamentos[$oCgmPagamento->inscricao]->inscricao = $oCgmPagamento->inscricao;
            $aPagamentos[$oCgmPagamento->inscricao]->contribuinte = $oCgmPagamento->contribuinte;
          }

          //Totaliza por receita
          if (isset($aPagamentos[$oCgmPagamento->inscricao]->totalReceita[$oCgmPagamento->codigoReceita])) {

            $aPagamentos[$oCgmPagamento->inscricao]->totalReceita[$oCgmPagamento->codigoReceita]->totalRegistros += 1;
            $aPagamentos[$oCgmPagamento->inscricao]->totalReceita[$oCgmPagamento->codigoReceita]->totalGeral += $oCgmPagamento->valorPago;
          } else {

            $aPagamentos[$oCgmPagamento->inscricao]->totalReceita[$oCgmPagamento->codigoReceita] = new stdClass;

            $aPagamentos[$oCgmPagamento->inscricao]->totalReceita[$oCgmPagamento->codigoReceita]->totalRegistros = 1;
            $aPagamentos[$oCgmPagamento->inscricao]->totalReceita[$oCgmPagamento->codigoReceita]->totalGeral = $oCgmPagamento->valorPago;
            $aPagamentos[$oCgmPagamento->inscricao]->totalReceita[$oCgmPagamento->codigoReceita]->codigoReceita = $oCgmPagamento->codigoReceita;
            $aPagamentos[$oCgmPagamento->inscricao]->totalReceita[$oCgmPagamento->codigoReceita]->descricaoReceita = $oCgmPagamento->descricaoReceita;
          }

          //Totaliza por tipo de debito
          if (isset($aPagamentos[$oCgmPagamento->inscricao]->totalDebito[$oCgmPagamento->codigoTipoDebito])) {

            $aPagamentos[$oCgmPagamento->inscricao]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalRegistros += 1;
            $aPagamentos[$oCgmPagamento->inscricao]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalGeral += $oCgmPagamento->valorPago;
          } else {

            $aPagamentos[$oCgmPagamento->inscricao]->totalDebito[$oCgmPagamento->codigoTipoDebito] = new stdClass;

            $aPagamentos[$oCgmPagamento->inscricao]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalRegistros = 1;
            $aPagamentos[$oCgmPagamento->inscricao]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalGeral = $oCgmPagamento->valorPago;
            $aPagamentos[$oCgmPagamento->inscricao]->totalDebito[$oCgmPagamento->codigoTipoDebito]->codigoTipoDebito = $oCgmPagamento->codigoTipoDebito;
            $aPagamentos[$oCgmPagamento->inscricao]->totalDebito[$oCgmPagamento->codigoTipoDebito]->descricaoTipoDebito = $oCgmPagamento->descricaoTipoDebito;
          }

          //Totaliza pôr Inscricao
          if (isset($aPagamentos[$oCgmPagamento->inscricao]->totalInscricao)) {
            $aPagamentos[$oCgmPagamento->inscricao]->totalInscricao += $oCgmPagamento->valorPago;
          } else {
            $aPagamentos[$oCgmPagamento->inscricao]->totalInscricao = $oCgmPagamento->valorPago;
          }
        }

        break;

      /**
       * Agrupamento por Origem CGM
       */
      case 5 :
        if ($oCgmPagamento->tipoOrigem == 'C') {

          if (!isset($aPagamentos[$oCgmPagamento->cgm])) {

            $aPagamentos[$oCgmPagamento->cgm] = new stdClass;
            $aPagamentos[$oCgmPagamento->cgm]->dados = array();
            $aPagamentos[$oCgmPagamento->cgm]->totalReceita = array();
            $aPagamentos[$oCgmPagamento->cgm]->totalDebito = array();
          }

          if (isset($aPagamentos[$oCgmPagamento->cgm]->dados[$oCgmPagamento->codigoReceita])) {
            $aPagamentos[$oCgmPagamento->cgm]->dados[$oCgmPagamento->codigoReceita]->valorPago += $oCgmPagamento->valorPago;
          } else {

            $aPagamentos[$oCgmPagamento->cgm]->dados[$oCgmPagamento->codigoReceita] = new stdClass;

            $aPagamentos[$oCgmPagamento->cgm]->dados[$oCgmPagamento->codigoReceita]->valorPago = $oCgmPagamento->valorPago;
            $aPagamentos[$oCgmPagamento->cgm]->dados[$oCgmPagamento->codigoReceita]->codigoReceita = $oCgmPagamento->codigoReceita;
            $aPagamentos[$oCgmPagamento->cgm]->dados[$oCgmPagamento->codigoReceita]->descricaoReceita = $oCgmPagamento->descricaoReceita;
            $aPagamentos[$oCgmPagamento->cgm]->dados[$oCgmPagamento->codigoReceita]->codigoTipoDebito = $oCgmPagamento->codigoTipoDebito;
            $aPagamentos[$oCgmPagamento->cgm]->dados[$oCgmPagamento->codigoReceita]->descricaoTipoDebito = $oCgmPagamento->descricaoTipoDebito;

            $aPagamentos[$oCgmPagamento->cgm]->cgm = $oCgmPagamento->cgm;
            $aPagamentos[$oCgmPagamento->cgm]->contribuinte = $oCgmPagamento->contribuinte;
          }

          //Totaliza por receita
          if (isset($aPagamentos[$oCgmPagamento->cgm]->totalReceita[$oCgmPagamento->codigoReceita])) {

            $aPagamentos[$oCgmPagamento->cgm]->totalReceita[$oCgmPagamento->codigoReceita]->totalRegistros += 1;
            $aPagamentos[$oCgmPagamento->cgm]->totalReceita[$oCgmPagamento->codigoReceita]->totalGeral += $oCgmPagamento->valorPago;
          } else {

            $aPagamentos[$oCgmPagamento->cgm]->totalReceita[$oCgmPagamento->codigoReceita] = new stdClass;

            $aPagamentos[$oCgmPagamento->cgm]->totalReceita[$oCgmPagamento->codigoReceita]->totalRegistros = 1;
            $aPagamentos[$oCgmPagamento->cgm]->totalReceita[$oCgmPagamento->codigoReceita]->totalGeral = $oCgmPagamento->valorPago;
            $aPagamentos[$oCgmPagamento->cgm]->totalReceita[$oCgmPagamento->codigoReceita]->codigoReceita = $oCgmPagamento->codigoReceita;
            $aPagamentos[$oCgmPagamento->cgm]->totalReceita[$oCgmPagamento->codigoReceita]->descricaoReceita = $oCgmPagamento->descricaoReceita;
          }

          //Totaliza por tipo de debito
          if (isset($aPagamentos[$oCgmPagamento->cgm]->totalDebito[$oCgmPagamento->codigoTipoDebito])) {

            $aPagamentos[$oCgmPagamento->cgm]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalRegistros += 1;
            $aPagamentos[$oCgmPagamento->cgm]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalGeral += $oCgmPagamento->valorPago;
          } else {

            $aPagamentos[$oCgmPagamento->cgm]->totalDebito[$oCgmPagamento->codigoTipoDebito] = new stdClass;

            $aPagamentos[$oCgmPagamento->cgm]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalRegistros = 1;
            $aPagamentos[$oCgmPagamento->cgm]->totalDebito[$oCgmPagamento->codigoTipoDebito]->totalGeral = $oCgmPagamento->valorPago;
            $aPagamentos[$oCgmPagamento->cgm]->totalDebito[$oCgmPagamento->codigoTipoDebito]->codigoTipoDebito = $oCgmPagamento->codigoTipoDebito;
            $aPagamentos[$oCgmPagamento->cgm]->totalDebito[$oCgmPagamento->codigoTipoDebito]->descricaoTipoDebito = $oCgmPagamento->descricaoTipoDebito;
          }

          //Totaliza por CGM
          if (isset($aPagamentos[$oCgmPagamento->cgm]->totalInscricao)) {
            $aPagamentos[$oCgmPagamento->cgm]->totalInscricao += $oCgmPagamento->valorPago;
          } else {
            $aPagamentos[$oCgmPagamento->cgm]->totalInscricao = $oCgmPagamento->valorPago;
          }
        }
    break;

  }

}

$aTotais['totalGeral'] = 0;
if ($cboAgrupamento != 2) {

  foreach ($aPagamentos as $iCgm => $oDados) {

    foreach ($oDados->dados as $iReceita => $oReceita ) {

      //Realiza totalizador por tipo de Débito

      if ( !isset($oReceita->codigoTipoDebito) ) {
        $oReceita->codigoTipoDebito = 0;
        $oReceita->descricaoTipoDebito = "";
      }

      if (isset($aTotais['tipodebito'][$oReceita->codigoTipoDebito])) {
        $aTotais['tipodebito'][$oReceita->codigoTipoDebito]         += $oReceita->valorPago;

      } else {
        $aTotais['tipodebito'][$oReceita->codigoTipoDebito]          = $oReceita->valorPago;
        $aTotais['descricaotipodebito'][$oReceita->codigoTipoDebito] = $oReceita->descricaoTipoDebito;
      }
      //Realiza totalizador por tipo de Receita
      if (isset($aTotais['codigoreceita'][$oReceita->codigoReceita])) {
        $aTotais['codigoreceita'][$oReceita->codigoReceita]   += $oReceita->valorPago;

      } else {

        $aTotais['codigoreceita'][$oReceita->codigoReceita]    = $oReceita->valorPago;
        $aTotais['descricaoreceita'][$oReceita->codigoReceita] = $oReceita->descricaoReceita;
      }

      //Realiza totalizador por tipo de Receita
      ///
      if (isset($aTotais['totalRegistros'])) {
       // echo $oReceita->valorPago;
        $aTotais['totalRegistros']   += 1;
        $aTotais['totalGeral']       += $oReceita->valorPago;
      } else {

        $aTotais['totalGeral']       = $oReceita->valorPago;
        $aTotais['totalRegistros']   = 1;
      }

    }

  }

} else if ($cboAgrupamento == 2) {


   foreach ($aPagamentos as $oReceita) {
      //Realiza totalizador por tipo de Receita
      if (isset($aTotais['codigoreceita'][$oReceita->codigoReceita])) {
        $aTotais['codigoreceita'][$oReceita->codigoReceita]   += $oReceita->valorPago;

      } else {

        $aTotais['codigoreceita'][$oReceita->codigoReceita]    = $oReceita->valorPago;
        $aTotais['descricaoreceita'][$oReceita->codigoReceita] = $oReceita->descricaoReceita;
      }

      //Realiza totalizador por tipo de Receita

      if (isset($aTotais['totalRegistros'])) {
       // echo $oReceita->valorPago;
        $aTotais['totalRegistros']   += 1;
        $aTotais['totalGeral']       += $oReceita->valorPago;
      } else {

        $aTotais['totalGeral']       = $oReceita->valorPago;
        $aTotais['totalRegistros']   = 1;
      }
   }

}

//ordenar array por valor
if ($lOrderValor && $iTipoRelatorio == 2) {

  if ($oGet->cboOrdenacaoValor == 1) {

   uasort($aPagamentos,'ordenaUp');
  } else {

    uasort($aPagamentos,'ordenaDown');
  }

} else if ($lOrderValor) {
  if ($oGet->cboOrdenacaoValor == 1) {
    foreach ($aPagamentos as $oCgm) {
      uasort($oCgm->dados,'ordenaUp');
    }
    ksort($aPagamentos);
  } else {
    foreach ($aPagamentos as $oCgm) {
      uasort($oCgm->dados,'ordenaDown');
    }
    ksort($aPagamentos);
  }
}

if ($aTotais["totalRegistros"] == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum resultado retornado para os filtros selecionados.");
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$pdf->setfillcolor(235);

$alt = 4;

$lNovaPagina = true;

if ($oGet->cboTotalizacao <> 2) {
  foreach ($aPagamentos as $oCgm) {

    if ($pdf->gety() > $pdf->h - 30 ) {
      $lNovaPagina = true;
    }

    switch ($iTipoRelatorio){

      case 1:
        if ($lNovaPagina) {
           $pdf->AddPage();
          $lNovaPagina = false;
           cabecalhoTipoDebito($pdf, $alt);
           $pdf->ln(3);
        }
        $pdf->setfont('arial','b',7);
        $pdf->cell(25, $alt, $oCgm->codigoTipoDebito    , 0, 0, "R", 0);
        $pdf->cell(60, $alt, $oCgm->descricaoTipoDebito , 0, 0, "L", 0);
        $pdf->setfont('arial','',7);
        $iX = $pdf->GetX();
        $iY = $pdf->GetY() - $alt;

        //Faz a impressao dos dados
        foreach ($oCgm->dados as $oDado) {
          //Verifica a quebra de nova página
          if ($lNovaPagina) {

            $pdf->AddPage();
            $lNovaPagina = false;
            cabecalhoTipoDebito($pdf, $alt);
            $pdf->ln(3);
          }
          //Imprime os dados do relatorio
          $iY = $pdf->GetY() - $alt;
          $pdf->SetXY($iX,$iY+$alt);
          $pdf->setfont('arial','',7);
          $pdf->cell(25, $alt, $oDado->codigoReceita             , 0, 0, "R", 0);
          $pdf->cell(60, $alt, $oDado->descricaoReceita          , 0, 0, "L", 0);
          $pdf->cell(22, $alt, db_formatar($oDado->valorPago,'f'), 0, 1, "R", 0);

          if ($pdf->gety() > $pdf->h - 30 ) {
             $lNovaPagina = true;
          }
         }

         //Imprime os totais parciais
         $pdf->Ln(1);
         $pdf->SetFont("Arial", "b", 7);
         $pdf->cell(110, $alt, ""                               , 0, 0, "R", 1);
         $pdf->cell(25, $alt, "Registros:"                      , 0, 0, "R", 1);
        $pdf->cell(15, $alt, count($oCgm->dados)               , 0, 0, "L", 1);
        $pdf->cell(20, $alt, "Total Tipo de Débito:"           , 0, 0, "R", 1);
        $pdf->cell(22, $alt, db_formatar($oCgm->totalGeral,"f"), 0, 1, "R", 1);
        $pdf->Ln(3);
        $pdf->SetFont("Arial", "", 8);

        break;

      case 2:
        if ($lNovaPagina) {

          $pdf->AddPage();
          $lNovaPagina = false;
          cabecalhoReceita($pdf, $alt);
          $pdf->ln(3);
        }
          //Imprime os dados do relatorio

          $pdf->setfont('arial','',7);
          $pdf->cell(25, $alt, $oCgm->codigoReceita             , 0, 0, "R", 0);
          $pdf->cell(145, $alt, $oCgm->descricaoReceita         , 0, 0, "L", 0);
          $pdf->cell(22, $alt, db_formatar($oCgm->valorPago,'f'), 0, 1, "R", 0);

        if ($pdf->gety() > $pdf->h - 30 ) {
          $lNovaPagina = true;
        }
        //Imprime os totais parciais
        $pdf->Ln(1);
        $pdf->SetFont("Arial", "b", 7);
        $pdf->cell(110, $alt, ""                               , 0, 0, "R", 1);
        $pdf->cell(25, $alt, "Registros:"                      , 0, 0, "R", 1);
        $pdf->cell(15, $alt, $oCgm->totalRegistros             , 0, 0, "L", 1);
        $pdf->cell(20, $alt, "Total Receita:"                  , 0, 0, "R", 1);
        $pdf->cell(22, $alt, db_formatar($oCgm->totalGeral,"f"), 0, 1, "R", 1);
        $pdf->Ln(3);
        $pdf->SetFont("Arial", "", 8);

        break;

      case 3:

        if ($lNovaPagina) {
          $pdf->AddPage();
          $lNovaPagina = false;
          cabecalhoMatricula($pdf, $alt);
          $pdf->ln(3);
        }

        $pdf->setfont('arial','',7);
        $pdf->cell(15, $alt, $oCgm->matricula    , 0, 0, "R", 0);
        $pdf->cell(15, $alt, $oCgm->cgm          , 0, 0, "R", 0);
        $pdf->cell(50, $alt, $oCgm->contribuinte , 0, 0, "L", 0);

        $iX = $pdf->GetX();
        $iY = $pdf->GetY() - $alt;

        //Faz a impressao dos dados
        foreach ($oCgm->dados as $oDado) {
          //Verifica a quebra de nova página
          if ($lNovaPagina) {
            $pdf->AddPage();
            $lNovaPagina = false;
            cabecalhoTipoDebito($pdf, $alt);
            $pdf->ln(3);
          }
          //Imprime os dados do relatorio
          $iY = $pdf->GetY() - $alt;
          $pdf->SetXY($iX,$iY+$alt);
          $pdf->setfont('arial','',7);
          $pdf->cell(22, $alt, db_formatar($oDado->valorPago,'f'), 0, 0, "R", 0);
          $pdf->cell(18, $alt, $oDado->codigoReceita             , 0, 0, "R", 0);
          $pdf->cell(50, $alt, $oDado->descricaoReceita          , 0, 0, "L", 0);
          $pdf->cell(22, $alt, $oDado->codigoTipoDebito          , 0, 1, "R", 0);

          if ($pdf->gety() > $pdf->h - 30 ) {
            $lNovaPagina = true;
          }
        }

        //Imprime os totais parciais
        $pdf->Ln(1);
        $pdf->SetFont("Arial", "b", 7);
        //$pdf->cell(110, $alt, ""                                   , 0, 0, "R", 1);
        $pdf->cell(25, $alt, "Registros:"                          , 0, 0, "R", 1);
        $pdf->cell(15, $alt, count($oCgm->dados)                   , 0, 0, "L", 1);
        $pdf->cell(40, $alt, "Total da Matrícula:"                 , 0, 0, "R", 1);
        $pdf->cell(22, $alt, db_formatar($oCgm->totalMatricula,"f"), 0, 0, "R", 1);
        $pdf->cell(91, $alt, ""                                    , 0, 1, "R", 1);
        $pdf->Ln(3);
        $pdf->SetFont("Arial", "", 8);

        break;

      case 4:

        if ($lNovaPagina) {
          $pdf->AddPage();
          $lNovaPagina = false;
          cabecalhoInscricao($pdf, $alt);
          $pdf->ln(3);
        }

        $pdf->setfont('arial','',7);
        $pdf->cell(15, $alt, $oCgm->inscricao    , 0, 0, "R", 0);
        $pdf->cell(15, $alt, $oCgm->cgm          , 0, 0, "R", 0);
        $pdf->cell(50, $alt, $oCgm->contribuinte , 0, 0, "L", 0);

        $iX = $pdf->GetX();
        $iY = $pdf->GetY() - $alt;

        //Faz a impressao dos dados
        foreach ($oCgm->dados as $oDado) {
          //Verifica a quebra de nova página
          if ($lNovaPagina) {
            $pdf->AddPage();
            $lNovaPagina = false;
            cabecalhoTipoDebito($pdf, $alt);
            $pdf->ln(3);
          }
          //Imprime os dados do relatorio
          $iY = $pdf->GetY() - $alt;
          $pdf->SetXY($iX,$iY+$alt);
          $pdf->setfont('arial','',7);
          $pdf->cell(22, $alt, db_formatar($oDado->valorPago,'f'), 0, 0, "R", 0);
          $pdf->cell(18, $alt, $oDado->codigoReceita             , 0, 0, "R", 0);
          $pdf->cell(50, $alt, $oDado->descricaoReceita          , 0, 0, "L", 0);
          $pdf->cell(22, $alt, $oDado->codigoTipoDebito          , 0, 1, "R", 0);

          if ($pdf->gety() > $pdf->h - 30 ) {
            $lNovaPagina = true;
          }
        }

        //Imprime os totais parciais

        $pdf->Ln(1);
        $pdf->SetFont("Arial", "b", 7);
        //$pdf->cell(, $alt, ""                                   , 0, 0, "R", 1);
        $pdf->cell(25, $alt, "Registros:"                          , 0, 0, "R", 1);
        $pdf->cell(15, $alt, count($oCgm->dados)                   , 0, 0, "L", 1);
        $pdf->cell(40, $alt, "Total da Inscrição:"                 , 0, 0, "R", 1);
        $pdf->cell(22, $alt, db_formatar($oCgm->totalInscricao,"f"), 0, 0, "R", 1);
        $pdf->cell(91, $alt, ""                                    , 0, 1, "R", 1);
        $pdf->Ln(3);
        $pdf->SetFont("Arial", "", 8);
        break;

      case 5:

        if ($lNovaPagina) {
          $pdf->AddPage();
          $lNovaPagina = false;
          cabecalhoCGM($pdf, $alt);
          $pdf->ln(3);
        }

        $pdf->setfont('arial','',7);
        //$pdf->cell(15, $alt, $oCgm->inscricao    , 0, 0, "R", 0);
        $pdf->cell(15, $alt, $oCgm->cgm          , 0, 0, "R", 0);
        $pdf->cell(57, $alt, $oCgm->contribuinte , 0, 0, "L", 0);

        $iX = $pdf->GetX();
        $iY = $pdf->GetY() - $alt;

        //Faz a impressao dos dados
        foreach ($oCgm->dados as $oDado) {
          //Verifica a quebra de nova página
          if ($lNovaPagina) {
            $pdf->AddPage();
            $lNovaPagina = false;
            cabecalhoTipoDebito($pdf, $alt);
            $pdf->ln(3);
          }
          //Imprime os dados do relatorio
          $iY = $pdf->GetY() - $alt;
          $pdf->SetXY($iX,$iY+$alt);
          $pdf->setfont('arial','',7);
          $pdf->cell(22, $alt, db_formatar($oDado->valorPago,'f'), 0, 0, "R", 0);
          $pdf->cell(18, $alt, $oDado->codigoReceita             , 0, 0, "R", 0);
          $pdf->cell(58, $alt, $oDado->descricaoReceita          , 0, 0, "L", 0);
          $pdf->cell(22, $alt, $oDado->codigoTipoDebito          , 0, 1, "R", 0);

          if ($pdf->gety() > $pdf->h - 30 ) {
            $lNovaPagina = true;
          }
        }

        //Imprime os totais parciais
        $pdf->Ln(1);
        $pdf->SetFont("Arial", "b", 7);
        $pdf->cell(32, $alt, "Registros:"                            , 0, 0, "R", 1);
        $pdf->cell(25, $alt, count($oCgm->dados)                     , 0, 0, "L", 1);
        $pdf->cell(15, $alt, "Total da Inscrição:"                   , 0, 0, "R", 1);
        $pdf->cell(22, $alt, db_formatar($oCgm->totalInscricao, "f") , 0, 0, "R", 1);
        $pdf->cell(98, $alt, ""                                     , 0, 1, "R", 1);

        $pdf->ln(4);

        $pdf->Ln(3);
        $pdf->SetFont("Arial", "", 8);
        break;

    }

  }
}

@ksort($aTotais["tipodebito"]);
@ksort($aTotais["codigoreceita"]);

if ($oGet->cboTotalizacao == 2) {

  $iTipoRelatorio = 3;
}

switch ($iTipoRelatorio) {

  case 5:
  case 4:
  case 3:

    if ($oGet->cboTotalizacao != 3 ) {

      $pdf->SetFont("Arial", "b", 7);
      //$pdf->cell(92, $alt, $iTipoRelatorio                        , 0, 0, "L", 1);
      $pdf->cell(25, $alt, "Total de Registros:"                  , 0, 0, "L", 1);
      $pdf->cell(15, $alt, $aTotais['totalRegistros']             , 0, 0, "L", 1);
      $pdf->cell(40, $alt, "Total Geral:"                         , 0, 0, "L", 1);
      $pdf->cell(22, $alt, db_formatar($aTotais['totalGeral'],"f"), 0, 0, "R", 1);
      $pdf->cell(91, $alt, ""                                     , 0, 1, "R", 1);

      $pdf->ln(2);
    }

    if ($pdf->gety() > $pdf->h - 30 || $oGet->cboTotalizacao == 2) {
      $pdf->AddPage();
    }

    if ($oGet->cboTotalizacao != 3) {
      $nTotalDebito = 0;
      $pdf->setfont('arial','b',7);
      $pdf->cell(25, $alt, "Código"           , 0, 0, "R", 1);
      $pdf->cell(50, $alt, "Descrição Débito" , 0, 0, "L", 1);
      $pdf->cell(15, $alt, "Total"            , 0, 1, "C", 1);

      $pdf->setfont('arial','',7);
      foreach ($aTotais['tipodebito'] as $key=>$value) {
        if ($pdf->gety() > $pdf->h - 30 ) {
          $pdf->AddPage();
        }
        $nTotalDebito += $value;
        $pdf->cell(25, $alt, $key                                 , 0, 0, "R", 0);
        $pdf->cell(50, $alt, $aTotais["descricaotipodebito"][$key], 0, 0, "L", 0);
        $pdf->cell(15, $alt, db_formatar($value,"f")              , 0, 1, "R", 0);

      }

      $pdf->setfont('arial','b',7);
      $pdf->cell(25, $alt, ""                            , 0, 0, "R", 1);
      $pdf->cell(50, $alt, "Total Tipo de Débito"        , 0, 0, "L", 1);
      $pdf->cell(15, $alt, db_formatar($nTotalDebito,"f"), 0, 1, "R", 1);

      $pdf->Ln(3);
      if ($pdf->gety() > $pdf->h - 30 ) {
        $pdf->AddPage();
      }
      //Totalizador por receita
      $nTotalReceita = 0;

      $pdf->setfont('arial','b',7);
      $pdf->cell(25, $alt, "Código"            , 0, 0, "R", 1);
      $pdf->cell(50, $alt, "Descrição Receita" , 0, 0, "L", 1);
      $pdf->cell(15, $alt, "Total"             , 0, 1, "C", 1);
      $pdf->setfont('arial','',7);
      foreach ($aTotais['codigoreceita'] as $key=>$value) {

        if ($pdf->gety() > $pdf->h - 30 ) {
          $pdf->AddPage();
        }

        $nTotalReceita += $value;
        $pdf->cell(25, $alt, $key                              , 0, 0, "R", 0);
        $pdf->cell(50, $alt, $aTotais["descricaoreceita"][$key], 0, 0, "L", 0);
        $pdf->cell(15, $alt, db_formatar($value,"f")           , 0, 1, "R", 0);
      }
      $pdf->setfont('arial','b',7);
      $pdf->cell(25, $alt, ""                              , 0, 0, "R", 1);
      $pdf->cell(50, $alt, "Total Receita"                 , 0, 0, "L", 1);
      $pdf->cell(15, $alt, db_formatar($nTotalReceita, "f"), 0, 1, "R", 1);
      break;
    }

    default:

      $pdf->SetFont("Arial", "b", 7);
      $pdf->cell(92, $alt, ""                        , 0, 0, "L", 1);
      $pdf->cell(25, $alt, "Total de Registros:"                  , 0, 0, "L", 1);
      $pdf->cell(25, $alt, $aTotais['totalRegistros']             , 0, 0, "L", 1);
      $pdf->cell(25, $alt, "Total Geral:"                         , 0, 0, "L", 1);
      $pdf->cell(25, $alt, db_formatar($aTotais['totalGeral'],"f"), 0, 1, "R", 1);
      $pdf->ln(2);
      break;
}

$pdf->Output();

function cabecalhoTipoDebito($pdf, $alt) {

  $pdf->setfont('arial','b',8);
  $pdf->cell(25, $alt, "Tipo de Débito"       , 1, 0, "C", 1);
  $pdf->cell(60, $alt, "Descrição Tipo Débito", 1, 0, "C", 1);
  $pdf->cell(25, $alt, "Código Receita"       , 1, 0, "C", 1);
  $pdf->cell(60, $alt, "Descrição Receita"    , 1, 0, "C", 1);
  $pdf->cell(22, $alt, "Valor Pago"           , 1, 1, "C", 1);

}

function cabecalhoReceita($pdf, $alt) {

  $pdf->setfont('arial','b',8);
  $pdf->cell(25 , $alt, "Código Receita"     , 1, 0, "C", 1);
  $pdf->cell(145, $alt, "Descrição Receita"  , 1, 0, "C", 1);
  $pdf->cell(22 , $alt, "Valor Pago"         , 1, 1, "C", 1);

}

function cabecalhoMatricula($pdf, $alt) {

  $pdf->setfont('arial','b',8);
  $pdf->cell(15, $alt, "Matrícula"            , 1, 0, "C", 1);
  $pdf->cell(15, $alt, "CGM"                  , 1, 0, "C", 1);
  $pdf->cell(50, $alt, "Nome do Contribuinte" , 1, 0, "C", 1);
  $pdf->cell(22, $alt, "Valor Pago"           , 1, 0, "C", 1);
  $pdf->cell(18, $alt, "Cód. Receita"       , 1, 0, "C", 1);
  $pdf->cell(50, $alt, "Descrição Receita"    , 1, 0, "C", 1);
  $pdf->cell(22, $alt, "Tipo de Débito"       , 1, 1, "C", 1);

}

function cabecalhoInscricao($pdf, $alt) {

  $pdf->setfont('arial','b',8);
  $pdf->cell(15, $alt, "Inscrição"            , 1, 0, "C", 1);
  $pdf->cell(15, $alt, "CGM"                  , 1, 0, "C", 1);
  $pdf->cell(50, $alt, "Nome do Contribuinte" , 1, 0, "C", 1);
  $pdf->cell(22, $alt, "Valor Pago"           , 1, 0, "C", 1);
  $pdf->cell(18, $alt, "Cód. Receita"         , 1, 0, "C", 1);
  $pdf->cell(50, $alt, "Descrição Receita"    , 1, 0, "C", 1);
  $pdf->cell(22, $alt, "Tipo de Débito"       , 1, 1, "C", 1);

}

function cabecalhoCGM($pdf, $alt) {

  $pdf->setfont('arial','b',8);
  $pdf->cell(15, $alt, "CGM"            , 1, 0, "C", 1);
  $pdf->cell(57, $alt, "Nome do Contribuinte" , 1, 0, "C", 1);
  $pdf->cell(22, $alt, "Valor Pago"           , 1, 0, "C", 1);
  $pdf->cell(18, $alt, "Cód. Receita"         , 1, 0, "C", 1);
  $pdf->cell(58, $alt, "Descrição Receita"    , 1, 0, "C", 1);
  $pdf->cell(22, $alt, "Tipo de Débito"       , 1, 1, "C", 1);

}

/**
 * Função para realizar a ordenação em ordem crescente de valores
 *
 * @param $obj1
 * @param $obj2
 * @return integer
 */
function ordenaUp($obj1,$obj2) {

  if ($obj1->valorPago > $obj2->valorPago) {
    return 1;
  } else {
    return 0;
  }
}
/**
 * Função para realizar a ordenação em ordem decrescente de valores
 *
 * @param $obj1
 * @param $obj2
 * @return integer
 */

function ordenaDown($obj1,$obj2) {

  if ($obj2->valorPago > $obj1->valorPago) {
    return 1;
  } else {
    return 0;
  }
}
