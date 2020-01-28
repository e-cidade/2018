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

function novoProfissional($oPdf, $sNome) {

  $lCor = false;
  $iTam = 5;
  
  $oPdf->ln(5);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(22, $iTam, 'Profissional: ', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(168, $iTam, $sNome, 0, 1, 'L', $lCor);

}

function novoCabecalho($oPdf) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 9);
  
  $oPdf->cell(190, $iTam, 'SOLICITAÇÕES', 1, 1, 'C', $lCor);
  $oPdf->cell(150, $iTam, 'Especialidade', 1, 0, 'C', $lCor);
  $oPdf->cell(40, $iTam, 'Número de Solicitações', 1, 1, 'C', $lCor);

}



function novaLinha($oPdf, $sEstrutural, $sNomeEspecialidade, $iNumSolicitacoes) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 9);

  $oPdf->cell(150, $iTam, $sEstrutural.' - '.$sNomeEspecialidade, 1, 0, 'L', $lCor);
  $oPdf->cell(40, $iTam, $iNumSolicitacoes, 1, 1, 'C', $lCor);

}

function novoTotal($oPdf, $iTotal) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 9);

  $oPdf->cell(35, $iTam, 'Total de Solicitações: ', 'LTB', 0, 'L', $lCor);
  $oPdf->cell(155, $iTam, $iTotal, 'RTB', 1, 'L', $lCor);

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

$oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');

$sCampos          = 'tf01_i_profissionalsolic, medicos.sd03_i_crm, rhcbo.rh70_estrutural, rhcbo.rh70_descr, ';
$sCampos         .= 'db_depart.descrdepto, count(*) as numsolicitacoes, ';
$sCampos         .= 'case when medicos.sd03_i_tipo = 1 then cgmmedico.z01_nome else s154_c_nome end as nomemedico ';
$sWhere           = "tf01_i_profissionalsolic is not null and tf01_d_datapedido between '$dIni' and '$dFim' ";
$sOrderBy         = 'tf01_i_profissionalsolic, rh70_estrutural ';
$sGroupBy         = 'group by tf01_i_profissionalsolic, medicos.sd03_i_crm, rhcbo.rh70_estrutural, ';
$sGroupBy        .= 'rhcbo.rh70_descr, db_depart.descrdepto, medicos.sd03_i_tipo, cgmmedico.z01_nome, s154_c_nome';
$sSql             = $oDaoTfdPedidoTfd->sql_query_protocolo(null, $sCampos, $sOrderBy, $sWhere.$sGroupBy);
$rs               = $oDaoTfdPedidoTfd->sql_record($sSql);
$iLinhas          = $oDaoTfdPedidoTfd->numrows;
if ($iLinhas <= 0) {
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


$head1 = 'Relatório de Consultas / Exames Solicitados';
$head2 = '';
$head3 = 'Período: '.$dIni.' a '.$dFim;

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->Addpage('P');

$oPdf->setfillcolor(223);
$oPdf->setfont('arial','',11);

$iTotal        = 0;
$iProfissional = -1;
for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);
  if ($iProfissional != $oDados->tf01_i_profissionalsolic) {
    
    if ($iProfissional != -1) {
      novoTotal($oPdf, $iTotal);
    }

    if ($oPdf->getY() >$oPdf->h - 50) {
      $oPdf->Addpage('P');
    }

    novoProfissional($oPdf, $oDados->nomemedico);
    novoCabecalho($oPdf);

    $iProfissional = $oDados->tf01_i_profissionalsolic;
    $iTotal        = 0;

  }
  
  if ($oPdf->getY() >$oPdf->h - 30) {

    $oPdf->Addpage('P');
    novoCabecalho($oPdf);

  }

  novaLinha($oPdf, $oDados->rh70_estrutural, $oDados->rh70_descr, $oDados->numsolicitacoes);
  $iTotal += $oDados->numsolicitacoes;

}

if ($iLinhas > 0) {
  novoTotal($oPdf, $iTotal);
}
$oPdf->Output();

?>