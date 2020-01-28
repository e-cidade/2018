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
require_once('libs/db_utils.php');

$oDaoSauCotasAgendamento = db_utils::getdao('sau_cotasagendamento');

function novaPrestEspec($oPdf, $iCodUps, $sNomeUps, $sCodEspec, $sNomeEspec) {

  $oPdf->setfillcolor(224);
  $oPdf->setfont('arial', 'B', 9);
  $lCor = true;
  $iTam = 6;
  
  $oPdf->cell(100, $iTam, $iCodUps.' - '.$sNomeUps, 1, 0, 'L', $lCor);
  $oPdf->cell(90, $iTam, $sCodEspec.' - '.$sNomeEspec, 1, 1, 'L', $lCor);

}

function novoCabecalho($oPdf) {

  $oPdf->setfillcolor(244);
  $oPdf->setfont('arial', 'B', 8);
  $lCor = true;
  $iTam = 5;
  
  $oPdf->setX(30);
  $oPdf->cell(17, $iTam, 'Código', 1, 0, 'L', $lCor);
  $oPdf->cell(85, $iTam, 'UPS', 1, 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, 'Distribuído', 1, 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, 'Agendado', 1, 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, 'Realizado', 1, 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, 'Ausente', 1, 1, 'L', $lCor);

}

function novaLinha($oPdf, $iCodUps, $sNomeUps, $iDistribuido, $iAgendado, $iRealizado, $iAusente, $iCodCota, $dIni, 
                                                                                                                $dFim) {

  $oPdf->setfont('arial', '', 8);
  $lCor = false;
  $iTam = 5;
  
  $oPdf->setX(30);
  $oPdf->cell(17, $iTam, $iCodUps, 1, 0, 'L', $lCor);
  $oPdf->cell(85, $iTam, $sNomeUps, 1, 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, $iDistribuido, 1, 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, $iAgendado, 1, 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, $iRealizado, 1, 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, $iAusente, 1, 1, 'L', $lCor);
  //pesquisar se existe profissionais para este lançamento de cotas
  $oDaoSauCotasAgenda = db_utils::getdao('sau_cotasagendamento');
  $dAtual             = date('Y-m-d', db_getsession('DB_datausu'));
  $sHAtual            = date('H:i');
  //Campos
  $sCampos  = "sd27_i_codigo as iCodigo,";
  $sCampos .= "z01_nome      as sNome,";
  $sCampos .= "s164_quantidade as iDistribuido,";
  
  $sSubAg     = 'select count(sd23_i_codigo) ';
  $sSubAg    .= '  from agendamentos as agenda';
  $sSubAg    .= '    inner join undmedhorario on undmedhorario.sd30_i_codigo = agenda.sd23_i_undmedhor ';
  $sSubAg    .= '    inner join especmedico as med on med.sd27_i_codigo = undmedhorario.sd30_i_undmed ';
  $sSubAg    .= '    inner join unidademedicos as undmed on undmed.sd04_i_codigo = med.sd27_i_undmed ';
  $sSubAg    .= '    inner join rhcbo as cbo on cbo.rh70_sequencial = med.sd27_i_rhcbo ';
  $sSubAg    .= '      where undmed.sd04_i_unidade = sau_cotasagendamento.s163_i_upsprestadora ';
  $sSubAg    .= '        and agenda.sd23_i_upssolicitante = sau_cotasagendamento.s163_i_upssolicitante ';
  $sSubAg    .= '        and cbo.rh70_estrutural like rhcbo.rh70_estrutural ';
  $sSubAg    .= '        and cbo.rh70_estrutural like rhcbo.rh70_estrutural ';
  $sSubAg    .= "        and med.sd27_i_codigo = especmedico.sd27_i_codigo";
  $sSubAg    .= "        and agenda.sd23_d_consulta between '$dIni' and '$dFim' ";
  $sSubAg    .= "   and not EXISTS ( select * from agendaconsultaanula where s114_i_agendaconsulta = agenda.sd23_i_codigo )";
  
  $sCampos   .= "($sSubAg) as iAgendado,";
  $sSubWhere  = " and exists(select * from prontagendamento where prontagendamento.s102_i_agendamento = ";
  $sSubWhere .= " agenda.sd23_i_codigo) ";
  $sCampos   .= "(".$sSubAg.$sSubWhere.") as iRealizado,";
  $sSubWhere  = " and (agenda.sd23_d_consulta < '$dAtual'  or  (agenda.sd23_d_consulta = '$dAtual' and agenda.sd23_c_hora < '$sHAtual'))";
  $sSubWhere .= " and not exists(select * from prontagendamento where prontagendamento.s102_i_agendamento = agenda.sd23_i_codigo) ";
  $sCampos   .= "(".$sSubAg.$sSubWhere.") as iAusente";
  $sWhere     = " s164_cotaagendamento = ".$iCodCota;
  $sSql       = $oDaoSauCotasAgenda->sql_query_cotas("",$sCampos,"",$sWhere);
  $rs         = $oDaoSauCotasAgenda->sql_record($sSql);
  //echo "<br><br> SQL: $sSql; <br><br>";
  for ($iX=0; $iX < $oDaoSauCotasAgenda->numrows; $iX++) {

    $oDados = db_utils::fieldsmemory($rs, $iX);
    $oPdf->setX(30);
    $oPdf->cell(17, $iTam, "", 1, 0, 'L', $lCor);
    $oPdf->cell(85, $iTam, $oDados->icodigo."-".$oDados->snome, 1, 0, 'L', $lCor);
    $oPdf->cell(17, $iTam, $oDados->idistribuido, 1, 0, 'L', $lCor);
    $oPdf->cell(17, $iTam, $oDados->iagendado, 1, 0, 'L', $lCor);
    $oPdf->cell(17, $iTam, $oDados->irealizado, 1, 0, 'L', $lCor);
    $oPdf->cell(17, $iTam, $oDados->iausente, 1, 1, 'L', $lCor);

  }

}

$dAtual   = date('Y-m-d', db_getsession('DB_datausu'));
$sHAtual  = date('H:i');
$dIni     = "$iAno-$iMes-1";
$dFim     = "$iAno-$iMes-";
$dFim    .= date("t", strtotime("$iAno-$iMes-1"));

$sSubAg  = 'select count(sd23_i_codigo) ';
$sSubAg .= '  from agendamentos as agenda';
$sSubAg .= '    inner join undmedhorario on undmedhorario.sd30_i_codigo = agenda.sd23_i_undmedhor ';
$sSubAg .= '    inner join especmedico on especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed ';
$sSubAg .= '    inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ';
$sSubAg .= '    inner join rhcbo as cbo on cbo.rh70_sequencial = especmedico.sd27_i_rhcbo ';
$sSubAg .= '      where unidademedicos.sd04_i_unidade = sau_cotasagendamento.s163_i_upsprestadora ';
$sSubAg .= '        and agenda.sd23_i_upssolicitante = sau_cotasagendamento.s163_i_upssolicitante ';
$sSubAg .= '        and cbo.rh70_estrutural like rhcbo.rh70_estrutural ';
$sSubAg .= "        and agenda.sd23_d_consulta between '$dIni' and '$dFim' ";
$sSubAg .= "   and not EXISTS ( select * from agendaconsultaanula where s114_i_agendaconsulta = agenda.sd23_i_codigo )";
 
$sCampos  = 'distinct db_depart.descrdepto, db_depart.coddepto,';
$sCampos .= " s163_i_quantidade, ($sSubAg) as qtdeagend, ";
$sCampos .= "($sSubAg and exists(select * from prontagendamento ";
$sCampos .= '             where prontagendamento.s102_i_agendamento = agenda.sd23_i_codigo)) as qtdeatend, ';

$sCampos .= "($sSubAg and (agenda.sd23_d_consulta < '$dAtual' ";
$sCampos .= "              or  (agenda.sd23_d_consulta = '$dAtual' and agenda.sd23_c_hora < '$sHAtual'))";
$sCampos .= "  and not exists(select * from prontagendamento ";
$sCampos .= '                 where prontagendamento.s102_i_agendamento = agenda.sd23_i_codigo)) as qtdeaus, ';

$sCampos .= 'unidades.sd02_i_codigo as upsprest, db_depart.descrdepto as nomeprest, ';
$sCampos .= 'upssolic.sd02_i_codigo as upssolic, db_departsolic.descrdepto as nomesolic, ';
$sCampos .= 'rhcbo.rh70_estrutural, rhcbo.rh70_descr, s163_i_codigo';

$sWhere   = " sau_cotasagendamento.s163_i_mescomp = $iMes ";
$sWhere  .= " and sau_cotasagendamento.s163_i_anocomp = $iAno ";

$sOrderBy = ' db_depart.descrdepto, db_depart.coddepto, rhcbo.rh70_descr, db_departsolic.descrdepto ';

$sSql     = $oDaoSauCotasAgendamento->sql_query_cotas(null, $sCampos, $sOrderBy, $sWhere);
$rs       = $oDaoSauCotasAgendamento->sql_record($sSql);
$iLinhas  = $oDaoSauCotasAgendamento->numrows;
//die($sSql);
if ($iLinhas == 0) {
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado.<br>
            <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
  exit;
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$head1 = 'Relatório de Cotas';
$head2 = '';
$head3 = 'Período: '.$sMes.' / '.$iAno;

$oPdf->Addpage('P');

$iUnidade = null;
$sEspec   = '';
for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);

  // Verifico se vieram registros de uma nova prestadora ou especialidade
  if ($iUnidade != $oDados->upsprest || $sEspec != $oDados->rh70_estrutural) {

    novaPrestEspec($oPdf, $oDados->upsprest, $oDados->nomeprest, $oDados->rh70_estrutural, $oDados->rh70_descr);
    novoCabecalho($oPdf);

  }

  novaLinha($oPdf, $oDados->upssolic, $oDados->nomesolic, $oDados->s163_i_quantidade, 
            $oDados->qtdeagend, $oDados->qtdeatend, $oDados->qtdeaus, $oDados->s163_i_codigo, $dIni, $dFim
           );

  if ($oPdf->getY() >$oPdf->h - 30) {

    $oPdf->Addpage('P');
    novoCabecalho($oPdf, $lProfissional);

  }

  $iUnidade = $oDados->upsprest;
  $sEspec   = $oDados->rh70_estrutural;

}

$oPdf->Output();  
?>