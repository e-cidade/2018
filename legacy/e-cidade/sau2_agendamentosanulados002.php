<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

function novoCabecalho($oPdf) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfillcolor(223);
  $oPdf->setfont('arial', 'B', 8);

  $oPdf->cell(18, $iTam, 'Data Agend.', 1, 0, 'L', $lCor);
  $oPdf->cell(30, $iTam, 'Tipo Ficha', 1, 0, 'L', $lCor);
  $oPdf->cell(15, $iTam, 'Agenda', 1, 0, 'L', $lCor);
  $oPdf->cell(8, $iTam, 'Hora', 1, 0, 'L', $lCor);
  $oPdf->cell(15, $iTam, 'CGS', 1, 0, 'L', $lCor);
  $oPdf->cell(51, $iTam, 'Nome', 1, 0, 'L', $lCor);
  $oPdf->cell(16, $iTam, 'Data Anul.', 1, 0, 'L', $lCor);
  $oPdf->cell(25, $iTam, 'Situação', 1, 0, 'L', $lCor);
  $oPdf->cell(76, $iTam, 'Motivo', 1, 0, 'L', $lCor);
  $oPdf->cell(25, $iTam, 'Usuário', 1, 1, 'L', $lCor);

}

function novaLinha($oPdf, $dAgend, $sTipoFicha, $iCodAgend, $sHora, $iCgs, $sNome, $dAnul, 
                   $sSituacao, $sMotivo, $sLogin) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 8);

  $oPdf->cell(18, $iTam, $dAgend, 1, 0, 'L', $lCor);
  $oPdf->cell(30, $iTam, $sTipoFicha, 1, 0, 'L', $lCor);
  $oPdf->cell(15, $iTam, $iCodAgend, 1, 0, 'L', $lCor);
  $oPdf->cell(8, $iTam, $sHora, 1, 0, 'L', $lCor);
  $oPdf->cell(15, $iTam, $iCgs, 1, 0, 'L', $lCor);
  $oPdf->cell(51, $iTam, $sNome, 1, 0, 'L', $lCor);
  $oPdf->cell(16, $iTam, $dAnul, 1, 0, 'L', $lCor);
  $oPdf->cell(25, $iTam, $sSituacao, 1, 0, 'L', $lCor);
  $oPdf->cell(76, $iTam, $sMotivo, 1, 0, 'L', $lCor);
  $oPdf->cell(25, $iTam, $sLogin, 1, 1, 'L', $lCor);

}

function novoTotal($oPdf, $iTotal) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);

  $oPdf->cell(279, $iTam, 'Total de Registros: '.$iTotal, 1, 1, 'L', $lCor);
  $oPdf->ln(3);

}

function formataData($dData, $iTipo = 1) {
  
  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/', $dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];

    return $dData;
  
  }
 
 $dData = explode('-', $dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];

 return $dData;

}

$oDaoAgendaConsultaAnula = db_utils::getdao('agendaconsultaanula');

$aDatas                  = explode(',', $sDatas);
$aDatas2                 = array(formataData($aDatas[0]), formataData($aDatas[1]));
                         
$sCampos                 = ' agendamentos.*, agendaconsultaanula.*, coddepto, descrdepto,';
$sCampos                .= ' sd23_i_numcgs, z01_v_nome, sd101_c_descr, s102_i_prontuario, login, ';
$sCampos                .= " case when s114_i_situacao = 1 then 'Cancelado' ";
$sCampos                .= "      when s114_i_situacao = 2 then 'Faltou' ";
$sCampos                .= "      else 'Outros' "; 
$sCampos                .= " end as situacao";
$sWhere                  = " s114_d_data between '".$aDatas2[0]."' and '".$aDatas2[1]."'";
$sWhere                 .= " and s114_i_situacao in ($sSituacoes) and sd04_i_unidade in ($sUnidades) ";
$sOrderBy                = ' coddepto, sd23_d_consulta ';
$sSql                    = $oDaoAgendaConsultaAnula->sql_query_anulados(null, $sCampos, $sOrderBy, $sWhere);
$rs                      = $oDaoAgendaConsultaAnula->sql_record($sSql);
$iLinhas                 = $oDaoAgendaConsultaAnula->numrows;

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

$head1 = 'RELATÓRIO DE AGENDAMENTOS ANULADOS';
$head5 = 'PERÍODO: '.$aDatas[0].' a '.$aDatas[1];


$iTotal   = 0;
$iUnidade = -1;
for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);


  if ($oPdf->getY() > $oPdf->h - 30) {

    $oPdf->Addpage('L');
    novoCabecalho($oPdf);

  }

  if ($iUnidade != $oDados->coddepto) {
    
    if ($iCont != 0) {
      novoTotal($oPdf, $iTotal);
    }
    $head3 = 'UNIDADE: '.$oDados->descrdepto;
    $oPdf->Addpage('L');
    novoCabecalho($oPdf);
    $iUnidade = $oDados->coddepto;
    $iTotal   = 0;

  }
  novaLinha($oPdf, formataData($oDados->sd23_d_consulta, 2), substr($oDados->sd101_c_descr, 0, 15), 
            $oDados->sd23_i_codigo, $oDados->sd23_c_hora, $oDados->sd23_i_numcgs, 
            substr($oDados->z01_v_nome, 0, 27), formataData($oDados->s114_d_data, 2),
            substr($oDados->situacao, 0, 20), substr($oDados->s114_v_motivo, 0, 45), $oDados->login
           );
  $iTotal++;

}

if ($iLinhas > 0) {
  novoTotal($oPdf, $iTotal);
}

$oPdf->Output();
?>