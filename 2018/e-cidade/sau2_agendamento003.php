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

require_once(modification('fpdf151/pdf.php'));
require_once(modification('libs/db_stdlibwebseller.php'));
require_once(modification('libs/db_utils.php'));

parse_str( $_SERVER['QUERY_STRING'] );
set_time_limit(0);

$iAno = substr($sd23_d_consulta, 6, 4);
$iMes = substr($sd23_d_consulta, 3, 2);
$iDia = substr($sd23_d_consulta, 0, 2);

$sDataConsulta = "{$iAno}-{$iMes}-{$iDia}";

$oDaoUndMedHorario = new cl_undmedhorario();

$sCampos  = ' undmedhorario.*, sau_tipoficha.*, coddepto, descrdepto, sd03_i_codigo, z01_nome,';
$sCampos .= ' rh70_estrutural, rh70_descr';
$sWhere   = "     especmedico.sd27_i_codigo = '{$sd27_i_codigo}' and sd30_i_diasemana = {$chave_diasemana}";
$sWhere  .= " and exists( select 1 ";
$sWhere  .= "               from agendamentos ";
$sWhere  .= "              where sd23_i_undmedhor = sd30_i_codigo ";
$sWhere  .= "                and sd23_d_consulta = '{$sDataConsulta}' )";
$sOrderBy = 'sd30_c_horaini';
$sSql     = $oDaoUndMedHorario->sql_query_grade_relatorio(null, $sCampos, $sOrderBy, $sWhere);
$rs       = $oDaoUndMedHorario->sql_record($sSql);

if ($oDaoUndMedHorario->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$aGradesHorario   = db_utils::getCollectionByRecord( $rs );
$oDaoAgendamentos = new cl_agendamentos_ext();

$sWhere          = "    sd23_d_consulta = '{$sDataConsulta}' ";
$sWhere         .= 'and not exists (select * ';
$sWhere         .= '                  from agendaconsultaanula ';
$sWhere         .= '                 where s114_i_agendaconsulta = sd23_i_codigo) ';
$sWhere         .= "and sd27_i_codigo = {$sd27_i_codigo} ";
$sOrderBy        = 'sd30_i_codigo, sd30_c_horaini, sd23_i_ficha';
$sSqlAgendamento = $oDaoAgendamentos->sql_query_ext('', '*', $sOrderBy, $sWhere);
$rsAgendamento   = $oDaoAgendamentos->sql_record($sSqlAgendamento);

$aAgendas = array();
$iLinhas  = pg_num_rows($rsAgendamento);
for ($i = 0; $i < $iLinhas; $i++ ) {

  $oDados = db_utils::fieldsMemory($rsAgendamento, $i);

  if ( !array_key_exists($oDados->sd30_i_codigo, $aAgendas) )  {

    $oAgenda              = new stdClass();
    $oAgenda->iAgenda     = $oDados->sd30_i_codigo;
    $oAgenda->sDescricao  = $oDados->sd101_c_descr;
    $oAgenda->sHorarioIni = $oDados->sd30_c_horaini;
    $oAgenda->sHorarioFim = $oDados->sd30_c_horafim;
    $oAgenda->iFichas     = $oDados->sd30_i_fichas;
    $oAgenda->iReservas   = $oDados->sd30_i_reservas;
    $oAgenda->iTurno      = $oDados->sd30_i_turno;
    $oAgenda->aPacientes  = array();

    $aAgendas[$oDados->sd30_i_codigo] = $oAgenda;
  }

  $aAgendas[$oDados->sd30_i_codigo]->aPacientes[] = $oDados;
}

$oPdf = new PDF('L');
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = 'RELATÓRIO DE AGENDAMENTOS';
$head3 = 'Unidade: '.$aGradesHorario[0]->coddepto.' - '.$aGradesHorario[0]->descrdepto;
$head4 = 'Profissional: '.$aGradesHorario[0]->sd03_i_codigo.' - '.$aGradesHorario[0]->z01_nome;
$head5 = 'Especialidade: '.$aGradesHorario[0]->rh70_estrutural.' - '.$aGradesHorario[0]->rh70_descr;
$head6 = 'Data: '.$sd23_d_consulta.' - '.$diasemana;

$lPrimeiraPagina = true;
foreach ($aAgendas as $oAgenda ) {

  imprimeCabecalho($oPdf, $oAgenda, $lPrimeiraPagina);
  $lPrimeiraPagina = false;

  $lPinta = true;
  foreach ($oAgenda->aPacientes as $oPaciente) {

    if ( $oPdf->GetY() > ($oPdf->h - 20) ) {
      imprimeCabecalho($oPdf, $oAgenda, true);
    }

    $sPresenca = 'NÃO';
    if ($oPaciente->sd23_i_presenca == 1) {
      $sPresenca = 'SIM';
    }

    $lPinta = !$lPinta;
    $oPdf->SetFont('arial', '', 7);

    $oPdf->cell( 7,  4, $oPaciente->sd23_i_ficha,              1, 0, 'C', $lPinta );
    $oPdf->cell( 10,  4, $oPaciente->sd23_c_hora,               1, 0, 'C', $lPinta );
    $oPdf->cell( 15, 4, $oPaciente->sd23_i_numcgs,             1, 0, 'C', $lPinta );
    $oPdf->cell( 101, 4, $oPaciente->z01_v_nome,                1, 0, 'L', $lPinta );
    $oPdf->cell( 20, 4, $oPaciente->z01_v_telef,               1, 0, 'L', $lPinta );
    $oPdf->cell( 20, 4, $oPaciente->z01_v_telcel,              1, 0, 'L', $lPinta );
    $oPdf->cell( 15, 4, $sPresenca,                            1, 0, 'C', $lPinta );
    $oPdf->cell( 91, 4, substr($oPaciente->sd23_t_obs, 0, 80), 1, 1, 'L', $lPinta );
  }

  $oPdf->ln();
}


$oPdf->Output();

function imprimeCabecalho($oPdf, $oDadosAgenda, $lAdicionaPagina = false) {

  if ( $lAdicionaPagina ) {
    $oPdf->AddPage();
  }
  $oPdf->setfillcolor(200);
  $oPdf->SetFont('arial', 'B', 9);
  $oPdf->cell(279, 5, "{$oDadosAgenda->iAgenda} - {$oDadosAgenda->sDescricao}", 1, 1, 'L', 1);
  $oPdf->SetFont('arial', 'B', 7);

  $oPdf->setfillcolor(220);
  $oPdf->cell(7,  4, 'Seq.',       1, 0, 'C', 1);
  $oPdf->cell(10,  4, 'Hora',       1, 0, 'C', 1);
  $oPdf->cell(15, 4, 'CGS',        1, 0, 'C', 1);
  $oPdf->cell(101, 4, 'Nome',       1, 0, 'C', 1);
  $oPdf->cell(20, 4, 'Telefone',   1, 0, 'C', 1);
  $oPdf->cell(20, 4, 'Celular',    1, 0, 'C', 1);
  $oPdf->cell(15, 4, 'Presença',   1, 0, 'C', 1);
  $oPdf->cell(91, 4, 'Observação', 1, 1, 'C', 1);

  $oPdf->setfillcolor(240);
}
