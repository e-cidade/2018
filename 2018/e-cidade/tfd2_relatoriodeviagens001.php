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

require_once('fpdf151/pdf.php');
require_once('libs/db_utils.php');

$oDaoTfdPedidoTfd   = db_utils::getdao('tfd_pedidotfd');

function formataData($dData) {
 
  $dData = explode('-',$dData);
  $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
  return $dData;

}

function impCabecalho($oPdf) { 
  
  global $iLinhas;
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(15, 4, "Pedido", 1, 0, "L", 1); 
  $oPdf->cell(25, 4, "CGS", 1, 0, "L", 1); 
  $oPdf->cell(60, 4, "Paciente", 1, 0, "L", 1);
  $oPdf->cell(20, 4, "Passageiros", 1, 0, "L", 1);
  $oPdf->cell(20, 4, "Data Agend.", 1, 0, "L", 1);
  $oPdf->cell(20, 4, "Hora Agend.", 1, 0, "L", 1);
  $oPdf->cell(50, 4, "Destino", 1, 0, "L", 1);
  $oPdf->cell(68, 4, "Prestadora", 1, 1, "L", 1);
  $oPdf->setfillcolor(255);
  $iLinhas++;
  
}

function impPaciente($oPdf, $oPaciente) {
  
  global $iLinhas;
  $oPdf->setfont('arial', '', 8);  
  $oPdf->cell(15, 4, $oPaciente->tf01_i_codigo, 1, 0, "C", 1); 
  $oPdf->cell(25, 4, $oPaciente->cgs, 1, 0, "C", 1); 
  $oPdf->cell(60, 4, $oPaciente->paciente, 1, 0, "L", 1);
  $oPdf->cell(20, 4, ($oPaciente->nro_acompanhantes + 1), 1, 0, "C", 1);
  $oPdf->cell(20, 4, formataData($oPaciente->tf16_d_dataagendamento), 1, 0, "C", 1);
  $oPdf->cell(20, 4, $oPaciente->tf16_c_horaagendamento, 1, 0, "C", 1);
  $oPdf->cell(50, 4, $oPaciente->tf03_c_descr, 1, 0, "L", 1);
  $oPdf->cell(68, 4, $oPaciente->prestadora, 1, 1, "L", 1);
  $iLinhas++;
  
}

function impRodape($oPdf, $aCidades) {
  
  $oPdf->setXY($oPdf->getX(), $oPdf->getY() + 7);
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(40, 4, "Destino", 1, 0, "L", 1); 
  $oPdf->cell(20, 4, "Quantidade", 1, 1, "L", 1); 
  $oPdf->setfillcolor(255);
  $oPdf->setfont('arial', '', 8);
  for ($iI = 0; $iI < count($aCidades); $iI++) {  

    $oPdf->cell(40, 4, $aCidades[$iI]->sNome, 1, 0, "L", 1);
    $oPdf->cell(20, 4, $aCidades[$iI]->iQuantidade, 1, 1, "C", 1);
  
  }
  
}

function novaPagina($oPdf) {
  
  global $iLinhas;
  $oPdf->ln(5);
  $oPdf->addpage('L');
  $iLinhas = 0;

}

$sCampos            = ' distinct tf01_i_codigo,  ';
$sCampos           .= ' z01_v_nome as paciente, z01_i_cgsund as cgs,';
$sCampos           .= '  tf17_c_localsaida,  tf17_d_datasaida, tf17_c_horasaida,';
$sCampos           .= ' tf16_d_dataagendamento, tf16_c_horaagendamento, ';
$sCampos           .= ' cgmprest.z01_nome as prestadora, tf03_c_descr, tf03_i_codigo, ';
$sNroAcmp           = '(select count(*) from tfd_acompanhantes where tf13_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo) ';
$sNroAcmp          .= "as nro_acompanhantes";
$sCampos           .= $sNroAcmp;

$sIntevalo          = $dataInicial."' and '".$dataFinal;

$sWhere             = " tf16_d_dataagendamento between '$sIntevalo' ";
$sWhere            .= " and tf16_i_pedidotfd is not null ";
$sWhere            .= " and tf01_i_situacao = 1 ";
if ($destino != '') {
  $sWhere            .= " and tf03_i_codigo = $destino";
}
if ($saida == 1) {
  $sWhere  .= " and  tf17_i_pedidotfd is not null ";
} else if ($saida == 2) {
  $sWhere  .= " and  tf17_i_pedidotfd is null ";
}

$sSql = $oDaoTfdPedidoTfd->sql_query_pedido('', $sCampos, ' tf17_d_datasaida, tf01_i_codigo, paciente ', $sWhere);
$rs   = $oDaoTfdPedidoTfd->sql_record($sSql);

if ($oDaoTfdPedidoTfd->numrows == 0) {
  
  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado<br>
          <input type='button' value='Fechar' onclick='window.close()'><die($sSql);/b>
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
$iLinhas = 0;
$head1   = "Relatório Diário de Viagens";
$head2   = "Período.: ".$dataInicial." a ".$dataFinal;
if ($saida == 2) {
  $sSaida = "SEM SAÍDA";
} elseif ($saida == 1) {
  $sSaida = "COM SAÍDA";
} else {
  $sSaida = "TODOS";
}
$head3    = "Saída...: ".$sSaida;
$head4    = "Destino.: ".($destino != '' ? $destino : 'GERAL');
$aCidades = array();
novaPagina($oPdf);
impCabecalho($oPdf); 
for ($iI = 0; $iI < $oDaoTfdPedidoTfd->numrows; $iI++) {
  
  if ($iLinhas >= 37) {
    
    novaPagina($oPdf); 
    impCabecalho($oPdf); 
    
  }  
  $oPaciente = db_utils::fieldsmemory($rs, $iI);
  impPaciente($oPdf, $oPaciente);
  $lNovo = true;
  for ($iW = 0; $iW < count($aCidades); $iW++) {

    if ($oPaciente->tf03_i_codigo == $aCidades[$iW]->iCod) {
      
      $aCidades[$iW]->iQuantidade += $oPaciente->nro_acompanhantes + 1; 
      $lNovo                       = false; 
      break;
      
    }
    
  }
  if ($lNovo) {
    
    $aCidades[$iW]->iQuantidade = $oPaciente->nro_acompanhantes + 1; 
    $aCidades[$iW]->iCod        = $oPaciente->tf03_i_codigo;
    $aCidades[$iW]->sNome       = $oPaciente->tf03_c_descr;
    
  }
  
}
impRodape($oPdf, $aCidades);
$oPdf->Output();
?>