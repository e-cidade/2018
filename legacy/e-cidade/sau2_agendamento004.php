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

require_once(modification('fpdf151/scpdf.php'));
require_once(modification('libs/db_sql.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_stdlibwebseller.php'));
require_once(modification('classes/db_sau_config_ext_classe.php'));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
set_time_limit(0);

function novoComprovante(FPDF $oPdf, $oAgendamento, $oDbConfig, $oSauConfig, $sIdade, $sDiaSemana) {

  $iXInicial = $oPdf->getX();
  $iYInicial = $oPdf->getY();

  $oPdf->cell(20, 1, '', 0, 1, 'C', 0);

  $oPdf->setfont('times', 'b', 10);
  $oPdf->cell(190, 4, $oDbConfig->nomeinst, 0, 1, 'C', 0);
  $oPdf->cell(190, 4, 'COMPROVANTE DE AGENDAMENTO', 0, 1, 'C', 0);
  $oPdf->cell(20, 4, '', 0, 1, 'C', 0);

  $oPdf->setfont('times', 'b', 10);
  $oPdf->cell(20, 4, 'No', 0, 0, 'L', 0);
  $oPdf->cell(40, 4, 'Data', 0, 0, 'L', 0);
  $oPdf->cell(40, 4, 'Semana', 0, 0, 'L', 0);
  $oPdf->cell(20, 4, 'Hora', 0, 1, 'L', 0);

  $oPdf->setfont('times', '', 10);
  $oPdf->cell(20, 4, $oAgendamento->sd23_i_codigo, 0, 0, 'L', 0);
  $oPdf->cell(40, 4, formataData($oAgendamento->sd23_d_consulta, 2), 0, 0, 'L', 0);
  $oPdf->cell(40, 4, $sDiaSemana , 0, 0, 'L', 0);
  $oPdf->cell(20, 4, $oAgendamento->sd23_c_hora, 0, 1, 'L', 0);

  $oPdf->setfont('times', 'b', 10);
  $oPdf->cell(20, 4, 'Unidade', 0, 1, 'L', 0);
  $oPdf->setfont('times', '', 10);
  $oPdf->cell(20, 4, $oAgendamento->und_coddepto.' - '.$oAgendamento->und_descrdepto , 0, 1, 'L', 0);

  $oPdf->setfont('times', 'b', 10);
  $oPdf->cell(100, 4, 'Paciente', 0, 0, 'L', 0);
  $oPdf->cell(20, 4, 'Data Nasc.', 0, 0, 'L', 0);
  $oPdf->cell(20, 4, 'Idade', 0, 1, 'L', 0);
  $oPdf->setfont('times', '', 10);

  $sPaciente = "{$oAgendamento->z01_i_cgsund} - {$oAgendamento->z01_v_nome}";
  $iYAntes = $oPdf->GetY();
  $iLinhas = $oPdf->NbLines(100, $sPaciente);
  $oPdf->multicell(100, 4, $sPaciente, 0, 'L', 0);
  $oPdf->SetXY($oPdf->lMargin + 100, $iYAntes);
  $oPdf->cell(20, 4, formataData($oAgendamento->z01_d_nasc, 2), 0, 0, 'L', 0);
  $oPdf->cell(20, 4, $sIdade, 0, 1, 'L', 0);

  if ($iLinhas > 1) {
    $oPdf->Sety( $iYAntes + ($iLinhas * 4) );
  }

  $oPdf->setfont('times', 'b', 10);
  $oPdf->cell(100, 4, 'Médico', 0, 0, 'L', 0);
  $oPdf->cell(20, 4, 'Especialidade', 0, 1, 'L', 0);
  $oPdf->setfont('times', '', 10);
  $oPdf->cell(100, 4, $oAgendamento->med_sd03_i_codigo.' - '.$oAgendamento->med_z01_nome, 0, 0, 'L', 0);

  $sEspecialidade       = $oAgendamento->rh70_estrutural.' - '.$oAgendamento->rh70_descr;
  $iPosicaoX            = $oPdf->GetX();
  $iPosicaoY            = $oPdf->GetY();
  $iLinhasEspecialidade = $oPdf->NbLines(88, $sEspecialidade) * 2;

  $oPdf->SetXY($iPosicaoX, $iPosicaoY);
  $oPdf->MultiCell(88, $iLinhasEspecialidade, $sEspecialidade, 0, 'L');

  $oPdf->cell(190, 4, str_repeat('-', 160), 0, 1, 'L', 0);

  $oPdf->setfont('times', 'b', 10);
  $oPdf->cell(20, 4, 'Mensagem: ', 0, 0, 'L', 0);
  $oPdf->cell(170, 4, $oSauConfig->s103_v_msgagenda, 0, 1, 'L', 0);

  $oPdf->setfont('times', 'b', 7);
  $oPdf->cell(100, 4, 'Atendente', 0, 0, 'L', 0);
  $oPdf->cell(40, 4, 'Data Atend.', 0, 0, 'L', 0);
  $oPdf->cell(40, 4, 'Hora Atend.', 0, 1, 'L', 0);
  $oPdf->setfont('times', '', 7);
  $oPdf->cell(100, 3, $oAgendamento->id_usuario.' - '.$oAgendamento->nome, 0, 0, 'L', 0);
  $oPdf->cell(40, 3, formataData($oAgendamento->sd23_d_agendamento, 2), 0, 0, 'L', 0);
  $oPdf->cell(40, 3, substr($oAgendamento->sd23_c_cadastro, 0, 8), 0, 1, 'L', 0);

  $iAlturaPadrao = 70 + $iLinhasEspecialidade;
  $oPdf->rect($iXInicial, $iYInicial, 190, $iAlturaPadrao, 'D');

  $oPdf->ln(5);
}

function formataData($dData, $iTipo = 1) {

  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  }

  $dData = explode('-',$dData);
  $dData = $dData[2] . '/' . $dData[1] . '/' . $dData[0];

  return $dData;
}

$oDaoAgendamentos = new cl_agendamentos_ext();
$oRotulo          = new rotulocampo;

$oDaoAgendamentos->rotulo->label();
$oRotulo->label('z01_v_nome');
$oRotulo->label('z01_v_telef');

$sCampos  = ' db_depart.coddepto as und_coddepto, ';
$sCampos .= ' db_depart.descrdepto as und_descrdepto, ';
$sCampos .= ' db_departender.compl as und_compl, ';
$sCampos .= ' db_departender.numero as und_numero, ';
$sCampos .= ' ruas.j14_nome as und_rua, ';
$sCampos .= ' ceplogradouros.cp06_cep as und_cep, ';
$sCampos .= ' bairro.j13_descr as und_bairro, ';
$sCampos .= ' unidades.*, ';
$sCampos .= ' cgs_und.*, ';
$sCampos .= ' db_usuarios.login as login_usuario, ';
$sCampos .= ' medicos.sd03_i_codigo as med_sd03_i_codigo, ';
$sCampos .= ' cgm.z01_numcgm as med_z01_numcgm, ';
$sCampos .= ' cgm.z01_nome as med_z01_nome, ';
$sCampos .= ' unidademedicos.sd04_v_registroconselho as crm, ';
$sCampos .= ' agendamentos.*, ';
$sCampos .= ' rhcbo.*, ';
$sCampos .= ' db_usuarios.*, ';
$sCampos .= ' fc_idade(cgs_und.z01_d_nasc, current_date) ';

$sWhere = " sd23_i_codigo in ($sd23_i_codigo) ";

$sSql          = $oDaoAgendamentos->sql_query_ext(null, $sCampos, '', $sWhere);
$rsAgendamento = $oDaoAgendamentos->sql_record($sSql);

if ($oDaoAgendamentos->numrows == 0) {

  echo "<table width='100%'>
          <tr>
            <td align='center'>
              <font color='#FF0000' face='arial'>
              <b>Nenhum Registro para o Relatório<br>
              <input type='button' value='Fechar' onclick='window.close()'></b>
              </font>
            </td>
          </tr>
        </table>";
  exit;
}

$oDaoDbConfig  = new cl_db_config();
$sSql          = $oDaoDbConfig->sql_query_file(null, '*', '', 'codigo = '.db_getsession('DB_instit'));
$rs            = $oDaoDbConfig->sql_record($sSql);
$oDbConfig     = db_utils::fieldsMemory($rs, 0);

$oDaoSauConfig = new cl_sau_config_ext();
$sSql          = $oDaoSauConfig->sql_query_ext();
$rs            = $oDaoSauConfig->sql_record($sSql);
$oSauConfig    = db_utils::fieldsMemory($rs, 0);

$oPdf = new SCPDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->addpage();
$oPdf->setfillcolor(235);

for ($iCont = 0; $iCont < $oDaoAgendamentos->numrows; $iCont++) {

  if ($oPdf->getY()  > $oPdf->h - 45) {
    $oPdf->addpage();
  }

  $oAgendamento = db_utils::fieldsMemory($rsAgendamento, $iCont);

  $sIdade = '';
  if (!empty($oAgendamento->z01_d_nasc)) {

    $iDia = substr($oAgendamento->z01_d_nasc, 8, 2);
    $iMes = substr($oAgendamento->z01_d_nasc, 5, 2);
    $iAno = substr($oAgendamento->z01_d_nasc, 0, 4);

    $sIdade = calcage($iDia, $iMes, $iAno, date('d'), date('m'), date('Y'));
  }

  novoComprovante($oPdf, $oAgendamento, $oDbConfig, $oSauConfig, $sIdade, $diasemana);
}

$oPdf->Output();