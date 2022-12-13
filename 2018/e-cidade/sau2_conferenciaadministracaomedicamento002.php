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


require_once ("fpdf151/pdf.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/exceptions/DBException.php");

$oGet = db_utils::postMemory($_GET);
define('MSG_SAU2_CONFERENCIAADMINISTRACAOMEDICAMENTO002', 'saude.ambulatorial.sau2_conferenciaadministracaomedicamento002.');

// Array com os dados dos medicamentos no período
$aMedicamentos       = array();
$iCodigoDepartamento = db_getsession("DB_coddepto");
$oDtInicio           = null;
$oDtFinal            = null;
/**
 * Busca os dados e agrupa-os calculados no array $aMedicamentos
 */
try {

  if ( empty($oGet->dtInicial) || empty($oGet->dtFinal) ) {
    throw new Exception( _M(MSG_SAU2_CONFERENCIAADMINISTRACAOMEDICAMENTO002 ."filtro_periodo_obrigatorio") );
  }
  $aWhereMedicamentos = array();
  if (!empty($oGet->aMaterial)) {
    $aWhereMedicamentos [] = " m60_codmater in ($oGet->aMaterial) ";
  }

  $sWhere           = implode(" and ", $aWhereMedicamentos);
  $oDaoMedicamentos = new cl_far_matersaude();

  $sSqlMedicamentos       = $oDaoMedicamentos->sql_query_dados(null, "fa01_i_codigo, m60_codmater, m60_descr", "m60_descr", $sWhere);
  $rsCadastroMedicamentos = $oDaoMedicamentos->sql_record($sSqlMedicamentos);
  if (!$rsCadastroMedicamentos) {
    throw new Exception("Erro ao consultar dados dos medicamentos");
  }

  $iTotalMedicamento = pg_num_rows($rsCadastroMedicamentos);
  $oDtInicio = new DBDate($oGet->dtInicial);
  $oDtFinal  = new DBDate($oGet->dtFinal);

  /**
   * Consulta das  requisições realizadas
   */
  $sCampos  = " coalesce(sum(case when m80_data < '{$oDtInicio->getDate()}' then m82_quant else 0 end ), 0)  as entradas_anterior, ";
  $sCampos .= " coalesce(sum(case when m80_data >= '{$oDtInicio->getDate()}' then m82_quant else 0 end ), 0) as entradas_periodo   ";


  $aWhere   = array();
  $aWhere[] = " m81_codtipo = 17 ";
  $aWhere[] = " m70_coddepto = {$iCodigoDepartamento} ";
  $aWhere[] = " m80_data <= $1 ";
  $aWhere[] = " m70_codmatmater = $2";
  $aWhere[] = " fa04_i_codigo is null "; // não pode ser medicamento que teve dispensação na farmácia

  $sWhereSolicitados = implode(" and ", $aWhere);
  $oDaoDescarte      = new cl_descartemedicamento();
  $oDaoAdministracao = new cl_administracaomedicamento();
  $sSqlMedicamentos  = $oDaoAdministracao->medicamentosSolicitados($sCampos, $sWhereSolicitados);
  $rsConsultaEntradas = pg_prepare("consulta_requisicoes", $sSqlMedicamentos);

  /**
   * Variáveis para calculo da administração...
   */
  $sCalculoAdministracao = " (sd105_quantidade / coalesce(sd105_quantidadetotal, 1))";
  $sCamposAdministracao  = " sum( case when sd105_data < '{$oDtInicio->getDate()}'  then {$sCalculoAdministracao} else 0 end) as administracao_anterior, ";
  $sCamposAdministracao .= " sum( case when sd105_data >= '{$oDtInicio->getDate()}' then {$sCalculoAdministracao} else 0 end) as administracao_periodo ";

  $aWhereAdministracao   = array();
  $aWhereAdministracao[] = " sd24_i_unidade = {$iCodigoDepartamento} ";
  $aWhereAdministracao[] = " sd105_data <= '{$oDtFinal->getDate()}' ";

  $sWhereAdministracao     = implode(" and ", $aWhereAdministracao);
  $sWhereAdministracao    .= " and sd105_medicamento = $1";
  $sSqlAdministracao       = $oDaoAdministracao->administracaoMedicamento($sCamposAdministracao, $sWhereAdministracao);
  $rsConsultaAdministracao = pg_prepare("administracao_medicamentos", $sSqlAdministracao);


  /**
   * Pesquisamos todos descartes do medicamento
   */

  $sCalculoDescarte = " (sd107_quantidade / coalesce(sd107_quantidadetotal, 1)) ";
  $sCamposDescarte  = " sum( case when sd107_data < '{$oDtInicio->getDate()}'  then {$sCalculoDescarte} else 0 end) as descarte_anterior, ";
  $sCamposDescarte .= " sum( case when sd107_data >= '{$oDtInicio->getDate()}' then {$sCalculoDescarte} else 0 end) as descarte_periodo ";

  $aWhereDescarte   = array();
  $aWhereDescarte[] = " sd107_db_depart = {$iCodigoDepartamento} ";
  $aWhereDescarte[] = " sd107_data <= '{$oDtFinal->getDate()}' ";

  $sWhereDescarte = implode(" and ", $aWhereDescarte);
  $sWhereDescarte .= " and sd107_medicamento = $1";

  $sSqlDescarte        = $oDaoDescarte->sql_query_file(null, $sCamposDescarte, null, $sWhereDescarte);
  $rsConsultaDescartes = pg_prepare("descarte_medicamentos", $sSqlDescarte);
  for ($iMedicamento = 0; $iMedicamento < $iTotalMedicamento; $iMedicamento++) {

    $oMedicamento = db_utils::fieldsMemory($rsCadastroMedicamentos, $iMedicamento);

    $oMedicamento->nSaldoAnterior         = 0;
    $oMedicamento->nSaldo                 = 0;
    $oMedicamento->administracao_periodo  = 0;
    $oMedicamento->administracao_anterior = 0;
    $oMedicamento->descarte_anterior      = 0;
    $oMedicamento->descarte_periodo       = 0;
    $oMedicamento->nSaldoFinal            = 0;
    $oMedicamento->entradas_anterior      = 0;
    $oMedicamento->entradas_periodo       = 0;

    $rsMedicamentos = pg_execute('consulta_requisicoes', array($oDtFinal->getDate(), $oMedicamento->m60_codmater));
    if (!$rsMedicamentos) {
      throw new Exception(_M(MSG_SAU2_CONFERENCIAADMINISTRACAOMEDICAMENTO002 . "erro_buscar_medicamentos"));
    }

    $iLinhasMedicamentosSolicitados = pg_num_rows($rsMedicamentos);

    $oEntradaMedicamento            = db_utils::fieldsMemory($rsMedicamentos, 0);
    $oMedicamento->entradas_anterior = $oEntradaMedicamento->entradas_anterior;
    $oMedicamento->entradas_periodo  = $oEntradaMedicamento->entradas_periodo;


    /**
     * Pesquisamos todas administrações do medicamento
     */
    $rsAdministracao = pg_execute('administracao_medicamentos', array($oMedicamento->fa01_i_codigo));
    if (!$rsAdministracao) {
      throw new Exception(_M(MSG_SAU2_CONFERENCIAADMINISTRACAOMEDICAMENTO002 . "erro_buscar_administracoes"));
    }
    if ($rsAdministracao && pg_num_rows($rsAdministracao) > 0) {

      $iLinhasAdministracao = pg_num_rows($rsAdministracao);
      for ($iAdministracao = 0; $iAdministracao < $iLinhasAdministracao; $iAdministracao++) {

        $oAdministracao = db_utils::fieldsMemory($rsAdministracao, $iAdministracao);

        $oMedicamento->administracao_anterior += $oAdministracao->administracao_anterior;
        $oMedicamento->administracao_periodo += $oAdministracao->administracao_periodo;
      }
    }


    /**
     * Descartes do medicamento
     */
    $rsDescarte = pg_execute('descarte_medicamentos', array($oMedicamento->fa01_i_codigo));

    if ($rsDescarte && pg_num_rows($rsDescarte) > 0) {

      $iLinhasDescarte = pg_num_rows($rsDescarte);
      for ($iDescarte = 0; $iDescarte < $iLinhasDescarte; $iDescarte++) {

        $oDescarte = db_utils::fieldsMemory($rsDescarte, $iDescarte);

        $oMedicamento->descarte_anterior += $oDescarte->descarte_anterior;
        $oMedicamento->descarte_periodo += $oDescarte->descarte_periodo;
      }
    }

    /**
     * Calculos do saldo
     * Saldo anterior = (entrada_anterior - (administracao_anterior + descarte_anterior))
     * Saldo atual    = entradas_periodo
     * Saldo Final    = (Saldo anterior + Saldo atual - (administracao_periodo + descarte_periodo))
     */
    $sConsumoAnterior = $oMedicamento->administracao_anterior + $oMedicamento->descarte_anterior;
    $sConsumoPeriodo  = $oMedicamento->administracao_periodo  + $oMedicamento->descarte_periodo;

    $oMedicamento->nSaldoAnterior = $oMedicamento->entradas_anterior - $sConsumoAnterior;
    $oMedicamento->nSaldo         = $oMedicamento->entradas_periodo;
    $oMedicamento->nSaldoFinal    = $oMedicamento->nSaldoAnterior + $oMedicamento->entradas_periodo - $sConsumoPeriodo;

    if ($oMedicamento->entradas_periodo == 0 && $oDescarte->descarte_periodo == 0 && $oAdministracao->administracao_periodo == 0) {
      continue;
    }
    $aMedicamentos[] = $oMedicamento;

  }

  if (count($aMedicamentos) == 0) {
  throw new Exception(_M(MSG_SAU2_CONFERENCIAADMINISTRACAOMEDICAMENTO002 . "sem_medicamentos"));
  }
} catch ( Exception $oError ) {

  db_redireciona('db_erros.php?fechar=true&db_erro='.$oError->getMessage());
}


/**
 * Imprime dados
 * @var FpdfMultiCellBorder
 */
$oPdf = new PDF('L');
$oPdf->Open();
$oPdf->AliasNbPages();

$oPdf->setFillColor(220);
$oPdf->SetAutoPageBreak(false, 10);
$head1 = 'CONFERÊNCIA DE ADMINISTRAÇÃO DE MEDICAMENTOS';
$head3 = "Período: {$oDtInicio->getDate(DBDate::DATA_PTBR)} até {$oDtFinal->getDate(DBDate::DATA_PTBR)}";

function montaCabecalho( $oPdf ) {

  $oPdf->addPage();
  $oPdf->SetFont('Arial', 'b', 8);
  $oPdf->cell(170, 4, 'Medicamento',    1, 0, 'C', 1);
  $oPdf->cell(22,  4, 'Saldo Anterior', 1, 0, 'C', 1);
  $oPdf->cell(21,  4, 'Entrada',        1, 0, 'C', 1);
  $oPdf->cell(23,  4, 'Administração',  1, 0, 'C', 1);
  $oPdf->cell(21,  4, 'Descarte',       1, 0, 'C', 1);
  $oPdf->cell(21,  4, 'Saldo',          1, 1, 'C', 1);
}

$lPrimeiraPagina = true;
foreach ( $aMedicamentos as $oMedicamento ) {

  if ( $lPrimeiraPagina || $oPdf->GetY() + 15 > $oPdf->h ) {

    montaCabecalho( $oPdf );
    $lPrimeiraPagina = false;
  }

  $oPdf->SetFont('Arial', '', 7);
  $oPdf->cell(170, 4, $oMedicamento->m60_descr, 1, 0, 'L');
  $oPdf->cell(22,  4, number_format($oMedicamento->nSaldoAnterior,        3, ',', ''), 1, 0, 'R');
  $oPdf->cell(21,  4, number_format($oMedicamento->nSaldo,                3, ',', ''), 1, 0, 'R');
  $oPdf->cell(23,  4, number_format($oMedicamento->administracao_periodo, 3, ',', ''), 1, 0, 'R');
  $oPdf->cell(21,  4, number_format($oMedicamento->descarte_periodo,      3, ',', ''), 1, 0, 'R');
  $oPdf->cell(21,  4, number_format($oMedicamento->nSaldoFinal,           3, ',', ''), 1, 1, 'R');

}

$oPdf->Output();