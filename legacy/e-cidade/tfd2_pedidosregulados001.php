<?
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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oDaoTfdPedidotfd = db_utils::getdao('tfd_pedidotfd');

function novoPedido($oPdf, $iPedido, $dPedido, $sUrgencia, $dPref, $iCgs, $sPaciente) {

 $lCor = false;
 $iTam = 5;

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(14, $iTam, 'Pedido:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(22, $iTam, $iPedido, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(30, $iTam, 'Data do Pedido:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(30, $iTam, $dPedido, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(20, $iTam, 'Urgência:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(18, $iTam, $sUrgencia, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(36, $iTam, 'Data de Preferência:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(20, $iTam, $dPref, 'RBT', 1, 'L', $lCor);


 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(10, $iTam, 'CGS:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(26, $iTam, $iCgs, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'Nome:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(142, $iTam, $sPaciente, 'RBT', 1, 'L', $lCor);

 $oPdf->cell(190, $iTam, '', 0, 1, 'L', $lCor);

}

function formataData($dData, $iTipo = 1) {

  if(empty($dData)) {
    return '';
  }

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;

  }

 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}

$dDataIni = substr($dataini, 6, 4).'-'.substr($dataini, 3, 2).'-'.substr($dataini, 0, 2);
$dDataFim = substr($datafim, 6, 4).'-'.substr($datafim, 3, 2).'-'.substr($datafim, 0, 2);
$sWhere   = " tf01_d_datapedido between '$dDataIni' and '$dDataFim'";
$sWhere  .= $iTipo == 1 ? ' and tf34_i_login is not null ' : 'and tf34_i_login is null';

if (isset($codigoespec) && $codigoespec != '') {
  $sWhere .= " and rhcbo.rh70_estrutural = '$codigoespec'";
}

$sCampos  = ' tf01_i_codigo, tf04_c_abreviatura, tf01_d_datapedido, ';
$sCampos .= " case when tf01_i_emergencia = 1 then 'SIM' else 'NÃO' end as emergencia, ";
$sCampos .= " tf01_d_datapreferencia, a.rh70_estrutural, a.rh70_descr, ";
$sCampos .= ' z01_i_cgsund, z01_v_nome, z01_v_ident, z01_v_cgccpf, z01_nome, ';
$sCampos .= ' tf34_i_codigo, tf34_i_especmedico, tf34_i_login ';

$sOrderBy = 'tf01_i_codigo';

$sSql     = $oDaoTfdPedidotfd->sql_query_regulado('', $sCampos, $sOrderBy, $sWhere);
$rs       = $oDaoTfdPedidotfd->sql_record($sSql);
$iLinhas  = $oDaoTfdPedidotfd->numrows;
//echo $sSql;
if ($iLinhas == 0) {
?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
<?
 exit;
}

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = "Relatório dos Pedidos de TFD ".($iTipo == 1 ? 'Regulados' : 'Não Regulados');
$head2 = "Período: $dataini a $datafim";

if (isset($codigoespec) && $codigoespec != '') {
  $head4 = "Especialidade: $codigoespec - $especialidade";
}

$oPdf->addpage('P');

$oPdf->setfillcolor(240);

for($iCont = 0; $iCont < $iLinhas; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);

  novoPedido($oPdf, $oDados->tf01_i_codigo, formataData($oDados->tf01_d_datapedido, 2),
             $oDados->emergencia, formataData($oDados->tf01_d_datapreferencia, 2),
             $oDados->z01_i_cgsund, $oDados->z01_v_nome
            );

  if ($oPdf->getY() > $oPdf->h - 30) {
    $oPdf->addpage('P');
  }

}
$oPdf->Output();
?>