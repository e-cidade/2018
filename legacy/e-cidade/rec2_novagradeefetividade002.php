<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/PDFDocument.php"));
require_once(modification("fpdf151/PDFTable.php"));

ini_set("memory_limit", "-1");

$oGet            = db_utils::postmemory($_GET, 0);
$iAnoCompetencia = $oGet->ano;
$iMesCompetencia = str_pad($oGet->mes, 2, '0', STR_PAD_LEFT);
$sFiltro         = $oGet->filtro;
$sParametros     = $oGet->parametros;
/**
 * Verifica o tempo de inicio e fim configurados em Par�metros >> Efetividade >> Configura��es de Data
 */

$oDaoConfiguracoesDatasEfetividade     = new cl_configuracoesdatasefetividade();
$sCamposConfiguracoesDatasEfetividade  = "  to_date(generate_series(rh186_datainicioefetividade::timestamp, rh186_datafechamentoefetividade::timestamp, '1 day')::varchar, 'YYYY-mm-dd') as data";
$sCamposConfiguracoesDatasEfetividade .= ", rh186_datainicioefetividade as datainicioefetividade";
$sCamposConfiguracoesDatasEfetividade .= ", rh186_datafechamentoefetividade as datafechamentoefetividade";
$sWhereConfiguracoesDatasEfetividade   = "     rh186_exercicio   = {$iAnoCompetencia}";
$sWhereConfiguracoesDatasEfetividade  .= " and rh186_competencia = '{$iMesCompetencia}'";
$sWhereConfiguracoesDatasEfetividade  .= " and rh186_instituicao = " . db_getsession("DB_instit");
$sSqlConfiguracoesDatasEfetividade     = $oDaoConfiguracoesDatasEfetividade->sql_query_file(
  null,
  $sCamposConfiguracoesDatasEfetividade,
  null,
  $sWhereConfiguracoesDatasEfetividade
);
$rsConfiguracoesDatasEfetividade       = db_query($sSqlConfiguracoesDatasEfetividade);

if (pg_num_rows($rsConfiguracoesDatasEfetividade) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Per�odo de Efetividade n�o configurado.');
  exit;
}


for ($iData = 0; $iData < pg_num_rows($rsConfiguracoesDatasEfetividade); $iData++) {

  $oConfiguracaoDataEfetividade = db_utils::fieldsmemory($rsConfiguracoesDatasEfetividade, $iData);
  $dDataInicioEfetividade       = $oConfiguracaoDataEfetividade->datainicioefetividade;
  $dDataFechamentoEfetividade   = $oConfiguracaoDataEfetividade->datafechamentoefetividade;

  $aDatasEfetividade[$oConfiguracaoDataEfetividade->data] = false;

}

$aMatriculas                = array();
$aGradeEfetividade          = array();
$aEscalasUtilizadas         = array();
$aGradesHorariosUtilizadas  = array();
$aLegendas = array();


/**
 * Carrega as matr�culas apartir dos filtros selecionados
 */

$sWhere = "    rh02_anousu = {$iAnoCompetencia}  
           and rh02_mesusu = {$iMesCompetencia} 
           and rh05_recis is null
           and exists (select 1 
                         from db_departrhlocaltrab 
                        where rh185_db_depart   = " . db_getsession('DB_coddepto') . " 
                          and rh185_rhlocaltrab = rh55_codigo) ";

if ($sFiltro == 'lotacao') {
  $sWhere .= " and rh02_lota in ($sParametros) ";
}

if ($sFiltro == 'matricula') {
  $sWhere .= " and rh01_regist in ($sParametros) ";
}

$oDaoRhpessoalmov = new cl_rhpessoalmov;
$sSqlMatriculas   = $oDaoRhpessoalmov->sql_query_baseServidores($iMesCompetencia, $iAnoCompetencia, db_getsession('DB_instit'), 'rh02_regist as matricula, z01_nome as nome, rh02_lota as codigo_lotacao', $sWhere, 'rh02_regist');
$rsMatriculas     = db_query($sSqlMatriculas);

if (pg_num_rows($rsMatriculas) == 0) {

  db_redireciona("db_erros.php?fechar=true&db_erro=Compet�ncia $iMesCompetencia/$iAnoCompetencia da folha  n�o configurado ou servidores n�o vinculados ao local de trabalho ou departamento.");
  exit;

}

for ($iTotalMatriculas = 0; $iTotalMatriculas < pg_num_rows($rsMatriculas); $iTotalMatriculas++) {

  $oMatricula              = db_utils::fieldsmemory($rsMatriculas, $iTotalMatriculas);
  $aOcorrenciasEfetividade = $aDatasEfetividade;
  $iMatricula              = $oMatricula->matricula;

  /**
   * Para mostrar na grade
   * |_ Afastamento sobrescreve feriado que sobrescreve jornada;
   *
   * Sql que monta a escala realizada pelo servidor apartir do per�odo configurado.
   */

  $sSqlEscalaServidor  = "select gradeshorarios.rh190_sequencial as sequencial, 
                                 gradeshorarios.rh190_descricao as descricao, 
                                 escalasperiodo.data, 
                                 ((escalasperiodo.data - gradeshorarios.rh190_database) % (select max(rh191_ordemhorario) from gradeshorariosjornada where rh191_gradehorarios = gradeshorarios.rh190_sequencial) + 1) as ordem
                            from ( select (select rh192_sequencial from escalaservidor where rh192_regist = {$iMatricula} and rh192_dataescala <= data order by rh192_dataescala desc limit 1) as codigo_escala, 
                                           to_char(data, 'YYYY-mm-dd')::date as data 
                                     from (select generate_series(('$dDataInicioEfetividade'::date)::timestamp, ('$dDataFechamentoEfetividade'::date)::timestamp, '1 day') as data ) as escalas ) as escalasperiodo 
                           inner join escalaservidor on escalaservidor.rh192_sequencial = escalasperiodo.codigo_escala 
                           inner join gradeshorarios on gradeshorarios.rh190_sequencial = escalaservidor.rh192_gradeshorarios 
                           group by gradeshorarios.rh190_sequencial, gradeshorarios.rh190_descricao, escalasperiodo.data, gradeshorarios.rh190_database, escalaservidor.rh192_dataescala 
                           order by escalasperiodo.data";

  $rsEscalaServidor     = db_query($sSqlEscalaServidor);
  $iCodigoEscalaInicial = null;

  for ($iEscalaServidor = 0; $iEscalaServidor < pg_num_rows($rsEscalaServidor); $iEscalaServidor++) {

    $oEscalaServidor = db_utils::fieldsmemory($rsEscalaServidor, $iEscalaServidor);

    /**
     * Jornadas de trabalho do servidor
     */
    $sCamposJornada  = "  gradeshorariosjornada.rh191_ordemhorario as ordemhorario";
    $sCamposJornada .= ", jornada.rh188_sequencial as sequencial";
    $sCamposJornada .= ", jornada.rh188_descricao as descricao";
    $sCamposJornada .= ", array_agg(rh189_hora) as horario";

    $sSqlJornada  = "select {$sCamposJornada}
                       from gradeshorariosjornada 
                            inner join jornada      on jornada.rh188_sequencial   = gradeshorariosjornada.rh191_jornada 
                            left  join jornadahoras on jornadahoras.rh189_jornada = jornada.rh188_sequencial 
                      where rh191_gradehorarios =  {$oEscalaServidor->sequencial}
                        and rh191_ordemhorario  = '{$oEscalaServidor->ordem}'
                      group by gradeshorariosjornada.rh191_ordemhorario, jornada.rh188_sequencial, jornada.rh188_descricao 
                      order by rh191_ordemhorario";
    $rsJornada    = db_query($sSqlJornada);

    $oJornada     = new stdClass();
    if (pg_num_rows($rsJornada) > 0) {

      $oDadosJornada        = db_utils::fieldsmemory($rsJornada, 0);
      $oJornada->iOrdem     = $oDadosJornada->ordemhorario;
      $oJornada->iCodigo    = $oDadosJornada->sequencial;
      $oJornada->sDescricao = $oDadosJornada->descricao;
      $oJornada->sHorario   = $oDadosJornada->horario;

      $aEscalasUtilizadas[$oEscalaServidor->sequencial] = true;

    }

    $oEscala                      = new stdClass();
    $oEscala->iMatricula          = $oMatricula->matricula;
    $oEscala->sNomeServidor       = $oMatricula->nome;
    $oEscala->sTipo               = 'escala';
    $oEscala->iCodigo             = $oEscalaServidor->sequencial;
    $oEscala->sDescricao          = $oEscalaServidor->descricao;
    $oEscala->sDescricaoDetalhada = $oEscalaServidor->descricao;
    $oEscala->oJornada            = $oJornada;

    $aOcorrenciasEfetividade[$oEscalaServidor->data]               = new stdClass();
    $aOcorrenciasEfetividade[$oEscalaServidor->data]->oEscala      = $oEscala;
    $aOcorrenciasEfetividade[$oEscalaServidor->data]->oFeriado     = null;
    $aOcorrenciasEfetividade[$oEscalaServidor->data]->oAfastamento = null;
    $aOcorrenciasEfetividade[$oEscalaServidor->data]->oDiaExtra    = null;

  }

  unset($rsEscalaServidor);

  /**
   * Calend�rio de feriados da lota��o
   */

  $sSqlCalendarioLotacao = "select r62_data 
                              from rhlotacalend 
                                   inner join calendf on calendf.r62_calend = rhlotacalend.rh64_calend 
                             where rhlotacalend.rh64_lota = {$oMatricula->codigo_lotacao}
                               and calendf.r62_data between '$dDataInicioEfetividade' and '$dDataFechamentoEfetividade'";
  $rsCalendarioLotacao   = db_query($sSqlCalendarioLotacao);

  for ($iCalendarioLotacao = 0; $iCalendarioLotacao < pg_num_rows($rsCalendarioLotacao); $iCalendarioLotacao++) {

    $oCalendarioLotacao                       = db_utils::fieldsmemory($rsCalendarioLotacao, $iCalendarioLotacao);

    $oDataFeriadoLotacao                      = new stdClass();
    $oDataFeriadoLotacao->iMatricula          = $oMatricula->matricula;
    $oDataFeriadoLotacao->sNomeServidor       = $oMatricula->nome;
    $oDataFeriadoLotacao->sTipo               = 'feriado';
    $oDataFeriadoLotacao->iCodigo             = 1;
    $oDataFeriadoLotacao->sDescricao          = 'FER';
    $oDataFeriadoLotacao->sDescricaoDetalhada = 'FERIADO LOTA��O';
    $oDataFeriadoLotacao->oJornada            = null;

    $aLegendas[$oDataFeriadoLotacao->sDescricao] = $oDataFeriadoLotacao;

    $aOcorrenciasEfetividade[$oCalendarioLotacao->r62_data]->oFeriado = $oDataFeriadoLotacao;

  }

  unset($rsCalendarioLotacao);

  /**
   * Registros de afastamento do servidor
   */

  $oDaoAssentamento        = new cl_assenta();
  $sCamposAssentamento     = "h12_codigo, ";
  $sCamposAssentamento    .= "h12_descr , ";
  $sCamposAssentamento    .= "h12_assent, ";
  $sCamposAssentamento    .= "h12_natureza, ";
  $sCamposAssentamento    .= "to_date(generate_series((case when h16_dtconc < '$dDataInicioEfetividade' then '$dDataInicioEfetividade' else h16_dtconc end)::timestamp,
                                                      (case when h16_dtterm is null or h16_dtterm > '$dDataFechamentoEfetividade' then '$dDataFechamentoEfetividade' else h16_dtterm end)::timestamp, '1 day')::varchar, 'YYYY-mm-dd') as h16_dtconc                                                                                                       ";
  $sWhereAssentamento      = "    h16_regist = $iMatricula                                                                                                                                                      ";
  $sWhereAssentamento     .= "and (h12_tipo = 'A' or h12_natureza = " . Assentamento::NATUREZA_DIA_EXTRA . ") ";
  $sWhereAssentamento     .= "and ((h16_dtconc::date || ' 00:00:00')::timestamp, case when h16_dtterm is null then '$dDataFechamentoEfetividade 23:59:59'::timestamp else (h16_dtterm::date || ' 23:59:59')::timestamp end) 
                              overlaps 
                                  ('$dDataInicioEfetividade 00:00:00'::timestamp, '$dDataFechamentoEfetividade 23:59:59'::timestamp)  ";

  $sSqlAssentamento        = $oDaoAssentamento->sql_query_tipo(null, $sCamposAssentamento, "h16_dtconc", $sWhereAssentamento);
  $rsAssentamento          = db_query($sSqlAssentamento);

  for($iRegistroAssentamento = 0; $iRegistroAssentamento < pg_num_rows($rsAssentamento); $iRegistroAssentamento++) {

    $oRegistroAssentamento                 = db_utils::fieldsmemory($rsAssentamento, $iRegistroAssentamento);

    $oAssentamento                         = new stdClass();
    $oAssentamento->iMatricula             = $oMatricula->matricula;
    $oAssentamento->sNomeServidor          = $oMatricula->nome;
    $oAssentamento->sTipo                  = 'assentamento';
    $oAssentamento->iCodigo                = $oRegistroAssentamento->h12_codigo;
    $oAssentamento->sDescricao             = $oRegistroAssentamento->h12_assent;
    $oAssentamento->sDescricaoDetalhada    = $oRegistroAssentamento->h12_descr;
    $oAssentamento->oJornada               = null;

    $aLegendas[$oRegistroAssentamento->h12_assent] = $oAssentamento;

    if ($oRegistroAssentamento->h12_natureza == Assentamento::NATUREZA_DIA_EXTRA) {

      $aOcorrenciasEfetividade[$oRegistroAssentamento->h16_dtconc]->oDiaExtra    = $oAssentamento;

    } else {

      $aOcorrenciasEfetividade[$oRegistroAssentamento->h16_dtconc]->oAfastamento = $oAssentamento;

    }

  }

  unset($rsAssentamento);

  $aGradeEfetividade[$iMatricula] = $aOcorrenciasEfetividade;

  unset($aOcorrenciasEfetividade);

}

foreach( $aEscalasUtilizadas as $iCodigoEscala => $sEscala ) {

  $sCamposEscalas  = "  gradeshorariosjornada.rh191_gradehorarios as gradeshorarios";
  $sCamposEscalas .= ", gradeshorariosjornada.rh191_ordemhorario as ordemhorario";
  $sCamposEscalas .= ", jornada.rh188_descricao as descricao";
  $sCamposEscalas .= ", array_agg(rh189_hora)";

  $sSqlEscalas = "select {$sCamposEscalas}
                    from gradeshorariosjornada                  
                         inner join jornada      on jornada.rh188_sequencial   = gradeshorariosjornada.rh191_jornada
                         left  join jornadahoras on jornadahoras.rh189_jornada = gradeshorariosjornada.rh191_jornada                        
                   where gradeshorariosjornada.rh191_gradehorarios = {$iCodigoEscala} 
                     and jornadahoras.rh189_sequencial is not null
                   group by 1,2,3 
                   order by rh191_ordemhorario::numeric ";

  $rsEscalas   = db_query($sSqlEscalas);

}

$aCabecalho   = array('Matr�cula', 'Nome');
$aAlinhamento = array('C', 'L');
$aTamanho     = array('4', '12');

foreach ($aDatasEfetividade as $sData => $iData) {

  $aCabecalho[]   = substr($sData, 8, 2);
  $aAlinhamento[] = 'C';
  $aTamanho[]     = '2.4';

}

$oPDFDocument = new PDFDocument('L');
$oPDFDocument->SetFillColor(235);
$oPDFDocument->open();
$oPDFDocument->setBold(false);
$oPDFDocument->setFontSize(6);

array_push($aCabecalho, 'DE', 'EXT', 'AF', 'T');
array_push($aAlinhamento, 'C', 'C', 'L', 'C');
array_push($aTamanho, '2', '2', '3', '2');

$oPDFTable = new PDFTable('L');
$oPDFTable->setPercentWidth(true);
$oPDFTable->setHeaders( $aCabecalho );
$oPDFTable->setColumnsAlign($aAlinhamento);
$oPDFTable->setColumnsWidth($aTamanho);
$oPDFTable->addHeaderDescription("Grade de Efetividade");
$oPDFTable->addHeaderDescription("");
$oPDFTable->addHeaderDescription("Compet�ncia: $iMesCompetencia/$iAnoCompetencia");
$oPDFTable->addHeaderDescription("Per�odo: " . implode("/", array_reverse(explode("-", $dDataInicioEfetividade))) . " at� " . implode("/", array_reverse(explode("-", $dDataFechamentoEfetividade))));
$oPDFTable->addHeaderDescription("");
$oPDFTable->addHeaderDescription("(DE)Dias Escala / (EXT)Dias Extras / (AF)Afastamentos / (T)Total");

foreach ($aGradeEfetividade as $iMatricula => $aDatas) {

  $sDiasAfastados   = '';
  $iDiasEscala      = 0;
  $iDiasAfastados   = 0;
  $iDiasExtras      = 0;

  $aRegistros       = array();
  $aAssentamentos   = array();
  $aRegistros[]     = $iMatricula;

  $oDaoRhpessoal    = new cl_rhpessoal();
  $sSqlRhpessoal    = $oDaoRhpessoal->sql_query($iMatricula);
  $rsRhpessoal      = db_query($sSqlRhpessoal);

  if (pg_num_rows($rsRhpessoal) > 0) {
    $aRegistros[] = db_utils::fieldsmemory($rsRhpessoal, 0)->z01_nome;
  }

  foreach($aDatas as $dData => $oEfetividade) {


    if (!is_object($oEfetividade)) {
      $aRegistros[] = '';
      continue;
    }

    $sCelula      = "";

    if (isset($oEfetividade->oEscala) and $oEfetividade->oEscala != null) {

      if (isset($oEfetividade->oEscala->oJornada->sHorario) and $oEfetividade->oEscala->oJornada->sHorario == '{NULL}') {

        $sCelula .= trim(substr($oEfetividade->oEscala->oJornada->sDescricao, 0, 3));

      } else {

        if ($oEfetividade->oEscala->sTipo == 'escala') {

          $sCelula .= trim($oEfetividade->oEscala->iCodigo);

          $iDiasEscala++;

        } else {

          $sCelula .= trim($oEfetividade->oEscala->sDescricao);

        }

      }

    }

    if ($oEfetividade->oAfastamento != null) {

      if (isset($oEfetividade->oEscala->oJornada) and !in_array($oEfetividade->oEscala->oJornada->iCodigo, array(1, 2)) and $oEfetividade->oFeriado == null) {

        $iDiasAfastados++;

        if (!isset($aAssentamentos[trim($oEfetividade->oAfastamento->sDescricao)])) {

          $aAssentamentos[trim($oEfetividade->oAfastamento->sDescricao)] = 1;

        } else {

          $aAssentamentos[trim($oEfetividade->oAfastamento->sDescricao)]++;

        }

      }

      if ($sCelula != '') {
        $sCelula .= "\n\r";
      }

      $sCelula .= trim($oEfetividade->oAfastamento->sDescricao);

    }

    if (isset($oEfetividade->oDiaExtra) and $oEfetividade->oDiaExtra != null) {

      $iDiasExtras++;

      if ($sCelula != '') {
        $sCelula .= "\n\r";
      }

      $sCelula .= 'EXT';

    }

    if (isset($oEfetividade->oFeriado) and $oEfetividade->oFeriado != null) {

      if ($sCelula != '') {
        $sCelula .= "\n\r";
      }

      $sCelula .= 'FER';

    }

    $aRegistros[] = $sCelula;

  }

  $sQuebraLinha = "";
  foreach ($aAssentamentos as $sDescricao => $iQuantidadeAssentamentos ) {
    $sDiasAfastados .= "$sQuebraLinha" . "$sDescricao-$iQuantidadeAssentamentos";
    $sQuebraLinha    = "\n";
  }

  $aRegistros[] = $iDiasEscala;
  $aRegistros[] = $iDiasExtras;
  $aRegistros[] = $sDiasAfastados;
  $aRegistros[] = $iDiasEscala + $iDiasExtras - $iDiasAfastados ;

  $oPDFTable->addLineInformation($aRegistros);

}

$iTotalColunas       = count($aRegistros);
$iColunaAfastamentos = $iTotalColunas - 2;

$aColunasMultiCell = array();
for ($iColuna = 0; $iColuna < $iTotalColunas; $iColuna++) {
  $aColunasMultiCell[] = $iColuna;
}

$oPDFTable->setMulticellColumns($aColunasMultiCell);

$oPDFTable->printOut($oPDFDocument, false);

if (count($aLegendas) > 0) {

  $oPDFTableLegendas = new PDFTable('L');
  $oPDFTableLegendas->setPercentWidth(true);
  $oPDFTableLegendas->setMulticellColumns(array(1));
  $oPDFTableLegendas->setHeaders( array('Legendas', '') );
  $oPDFTableLegendas->setColumnsAlign( array( 'L', 'L'));
  $oPDFTableLegendas->setColumnsWidth( array( '20', '80' ));
  $oPDFTableLegendas->addHeaderDescription("Legenda das Siglas");

  foreach($aLegendas as $oLegenda) {
    $oPDFTableLegendas->addLineInformation( array ($oLegenda->sDescricao, $oLegenda->sDescricaoDetalhada));
  }

  $oPDFTableLegendas->printOut($oPDFDocument, false);
}

$oPDFDocument->showPDF();