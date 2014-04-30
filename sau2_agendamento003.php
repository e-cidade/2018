<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once('fpdf151/pdf.php');
require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_utils.php');

function novoCabecalho($oPdf) {

  $lCor = true;
  $iTam = 4;
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'B', 7);

  $oPdf->cell(7,  $iTam, 'Seq.',       1, 0, 'C', $lCor);
  $oPdf->cell(33, $iTam, 'Tipo Ficha', 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, 'Agenda',     1, 0, 'C', $lCor);
  $oPdf->cell(8,  $iTam, 'Hora',       1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, 'CGS',        1, 0, 'C', $lCor);
  $oPdf->cell(70, $iTam, 'Nome',       1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Telefone',   1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Celular',    1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, 'Presença',   1, 0, 'C', $lCor);
  $oPdf->cell(76, $iTam, 'Observação', 1, 1, 'C', $lCor);
}

function novaLinha($oPdf, $iSeq, $sTipoFicha, $iAgenda, $sHoraIni, $iCgs, $sNome, $sTel, $sCel, $sPresenca, $sObs) {

  $lCor = false;
  $iTam = 4;
  $oPdf->setfont('arial', '', 7);

  $oPdf->cell( 7,  $iTam, $iSeq,                1, 0, 'C', $lCor );
  $oPdf->cell( 33, $iTam, $sTipoFicha,          1, 0, 'C', $lCor );
  $oPdf->cell( 15, $iTam, $iAgenda,             1, 0, 'C', $lCor );
  $oPdf->cell( 8,  $iTam, $sHoraIni,            1, 0, 'C', $lCor );
  $oPdf->cell( 15, $iTam, $iCgs,                1, 0, 'C', $lCor );
  $oPdf->cell( 70, $iTam, $sNome,               1, 0, 'L', $lCor );
  $oPdf->cell( 20, $iTam, $sTel,                1, 0, 'L', $lCor );
  $oPdf->cell( 20, $iTam, $sCel,                1, 0, 'L', $lCor );
  $oPdf->cell( 15, $iTam, $sPresenca,           1, 0, 'C', $lCor );
  $oPdf->cell( 76, $iTam, substr($sObs, 0, 50), 1, 1, 'L', $lCor );
}

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
set_time_limit(0);

$oDaoUndMedHorario = new cl_undmedhorario();

$sCampos  = ' undmedhorario.*, sau_tipoficha.*, coddepto, descrdepto, sd03_i_codigo, z01_nome,';
$sCampos .= ' rh70_estrutural, rh70_descr';
$sWhere   = '     especmedico.sd27_i_codigo = '.$sd27_i_codigo.' and sd30_i_diasemana = '.$chave_diasemana;
$sOrderBy = 'sd30_c_horaini';
$sSql     = $oDaoUndMedHorario->sql_query_grade_relatorio(null, $sCampos, $sOrderBy, $sWhere);
$rs       = $oDaoUndMedHorario->sql_record($sSql);

if ($oDaoUndMedHorario->numrows == 0) {

  echo "<table width='100%'>
          <tr>
            <td align='center'>".$sSql."
              <font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br>
              <input type='button' value='Fechar' onclick='window.close()'></b></font>
            </td>
          </tr>
        </table>";
  exit;
}

$aGradesHorario = db_utils::getColectionByRecord( $rs );
$aAgendamentos  = new cl_agendamentos_ext();

$iAno           = substr($sd23_d_consulta, 6, 4);
$iMes           = substr($sd23_d_consulta, 3, 2);
$iDia           = substr($sd23_d_consulta, 0, 2);
               
$sWhere         = "    sd23_d_consulta = '$iAno-$iMes-$iDia' ";
$sWhere        .= 'and not exists (select * ';
$sWhere        .= '                  from agendaconsultaanula ';
$sWhere        .= '                 where s114_i_agendaconsulta = sd23_i_codigo) ';
$sWhere        .= "and sd27_i_codigo = $sd27_i_codigo ";

$sOrderBy       = 'sd30_c_horaini, sd23_i_ficha';
                
$sSql           = $aAgendamentos->sql_query_ext('', '*', $sOrderBy, $sWhere);

$rs             = $aAgendamentos->sql_record($sSql);
$aAgendamentos  = db_utils::getColectionByRecord($rs);

$oPdf           = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = 'RELATÓRIO DE AGENDAMENTOS';
$head3 = 'Unidade: '.$aGradesHorario[0]->coddepto.' - '.$aGradesHorario[0]->descrdepto;
$head4 = 'Profissional: '.$aGradesHorario[0]->sd03_i_codigo.' - '.$aGradesHorario[0]->z01_nome;
$head5 = 'Especialidade: '.$aGradesHorario[0]->rh70_estrutural.' - '.$aGradesHorario[0]->rh70_descr;
$head6 = 'Data: '.$sd23_d_consulta.' - '.$diasemana;

$iSeq       = 1;
$oPdf->addpage('L');
novoCabecalho($oPdf);

for ($iCont = 0; $iCont < $oDaoUndMedHorario->numrows; $iCont++) {
  
  $sHoraIni    = $aGradesHorario[$iCont]->sd30_c_horaini;
  $sHoraImp    = $sHoraIni;
  $sHoraFim    = $aGradesHorario[$iCont]->sd30_c_horafim;
  $iMinTrab    = diferencaEmMinutos($sHoraIni, $sHoraFim);
  $iNumFichas  = $aGradesHorario[$iCont]->sd30_i_fichas + $aGradesHorario[$iCont]->sd30_i_reservas;

  $iIntervalo  = 0;
  $iIncremento = 0;
  
  if ($aGradesHorario[$iCont]->sd30_c_tipograde == 'I') {
    
    $iIntervalo  = $iMinTrab;
    
    if ( $iNumFichas > 0 ) {
      $iIntervalo  = number_format(($iMinTrab / $iNumFichas), 2, '.', '');
    }
    $iIncremento = 1;
  }

  $iIdFicha = 1;
  while ($iIdFicha <= $iNumFichas) {
   
    $iIdAgendamento = verificaAgendamentoHorarioByArray($aGradesHorario[$iCont]->sd30_i_codigo,
                                                        $aGradesHorario[$iCont]->sd30_c_tipograde,
                                                        $iIdFicha,
                                                        $aAgendamentos
                                                       );

    if ($iIdAgendamento != -1) {

      if ($aAgendamentos[$iIdAgendamento]->sd23_i_presenca == 1) {
        $sPresenca = 'SIM';
      } else {
        $sPresenca = 'NÃO';
      }
      
      novaLinha($oPdf, $iSeq, $aAgendamentos[$iIdAgendamento]->sd101_c_descr, 
                $aAgendamentos[$iIdAgendamento]->sd23_i_codigo, substr($sHoraImp, 0, 5), 
                $aAgendamentos[$iIdAgendamento]->z01_i_cgsund, $aAgendamentos[$iIdAgendamento]->z01_v_nome, 
                $aAgendamentos[$iIdAgendamento]->z01_v_telef, $aAgendamentos[$iIdAgendamento]->z01_v_telcel, 
                $sPresenca, $aAgendamentos[$iIdAgendamento]->sd23_t_obs
               );
      unset($aAgendamentos[$iIdAgendamento]);
    
      $iSeq++;
    }

    $sHoraImp = somaMinutosHoraAgendamento($sHoraIni, $iIntervalo + $iIncremento);
    $sHoraIni = somaMinutosHoraAgendamento($sHoraIni, $iIntervalo);

    if ($oPdf->gety() > $oPdf->h - 30) {
    
      $oPdf->addpage('L');
      novoCabecalho($oPdf);
    }

    $iIdFicha++;
  }
}

$oPdf->Output();
?>