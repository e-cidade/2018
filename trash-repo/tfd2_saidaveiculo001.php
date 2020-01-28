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

$oDaoTfdVeiculoDestino    = db_utils::getdao('tfd_veiculodestino');

function retanguloVeiculo($oPdf, $oVeiculo) {
  
  $oPdf->Rect($oPdf->getX(), $oPdf->getY(), 135.0, 24.0);
  $oPdf->setXY($oPdf->getX(), $oPdf->getY() + 1);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(23, 4, "  Local Destino: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(105, 4, $oVeiculo->LocalDestino, 0, 1, "L", 0);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(23, 4, "  Ve�culo: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(105, 4, $oVeiculo->Veiculo, 0, 1, "L", 0);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(23, 4, "  Motorista: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(105, 4, $oVeiculo->Motorista, 0, 1, "L", 0);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(23, 4, "  Capacidade: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(105, 4, $oVeiculo->Capacidade, 0, 1, "L", 0);
  
}

function retanguloSaida($oPdf, $oSaida, $iY) {
  
  $oPdf->Rect($oPdf->getX() + 136, $iY, 135.0, 24.0);
  $oPdf->setXY($oPdf->getX() + 135, $iY + 1);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(33, 4, "  Data Sa�da: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(37, 4, $oSaida->DataSaida, 0, 0, "L", 0); 
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(20, 4, " Hora Sa�da: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(45, 4, $oSaida->HoraSaida, 0, 1, "L", 0); 
  $oPdf->setXY($oPdf->getX() + 135, $oPdf->getY());
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(33, 4, "  Data Retorno: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(37, 4, $oSaida->DataRetorno, 0, 0, "L", 0); 
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(20, 4, "Hora Retorno: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(45, 4, $oSaida->HoraRetorno, 0, 1, "L", 0);  
  $oPdf->setXY($oPdf->getX() + 135, $oPdf->getY() + 3);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(33, 4, "  Vagas Utilizadas Ida: ", 0, 0, "L", 0);  
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(32, 4, $oSaida->TotalIda, 0, 1, "L", 0);   
  $oPdf->setXY($oPdf->getX() + 135, $oPdf->getY() + 2);  
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(33, 4, "  Vagas Utilizadas Volta: ", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(32, 4, $oSaida->TotalVolta, 0, 1, "L", 0);
  $oPdf->setXY($oPdf->getX(), $oPdf->getY() + 3);
  
}

function impCabecalho($oPdf) { 
  
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(15, 4, "CGS", 1, 0, "L", 1); 
  $oPdf->cell(55, 4, "Paciente", 1, 0, "L", 1);
  $oPdf->cell(20, 4, "Nro. Acomp.", 1, 0, "L", 1);
  $oPdf->cell(46, 4, "Local de Sa�da", 1, 0, "L", 1);
  $oPdf->cell(40, 4, "Municipio Destino", 1, 0, "L", 1);
  $oPdf->cell(40, 4, "Local Destino", 1, 0, "L", 1);
  $oPdf->cell(10, 4, "Hora", 1, 0, "L", 1);
  $oPdf->cell(30, 4, "Telefone", 1, 0, "L", 1);
  $oPdf->cell(15, 4, "Dire��o", 1, 1, "L", 1); 
  $oPdf->setfillcolor(255);
  
}

function impPaciente($oPdf, $oPaciente) {
  
  $oPdf->setfont('arial', '', 8);  
  $oPdf->cell(15, 4, $oPaciente->z01_i_cgsund, 1, 0, "C", 1); 
  $oPdf->cell(55, 4, $oPaciente->z01_v_nome, 1, 0, "L", 1);
  $oPdf->cell(20, 4, $oPaciente->nro_acompanhantes, 1, 0, "C", 1);
  $oPdf->cell(46, 4, $oPaciente->tf18_c_localsaida, 1, 0, "L", 1);
  $oPdf->cell(40, 4, $oPaciente->tf03_c_descr, 1, 0, "L", 1);
  $oPdf->cell(40, 4, $oPaciente->nomeempresa, 1, 0, "L", 1);
  $oPdf->cell(10, 4, $oPaciente->tf16_c_horaagendamento, 1, 0, "L", 1);
  $oPdf->cell(30, 4, $oPaciente->telefone, 1, 0, "L", 1);
  $oPdf->cell(15, 4, $oPaciente->direcao, 1, 1, "L", 1); 
}

function novaPagina($oPdf) {
  
  $oPdf->ln(5);
  $oPdf->addpage('L');

}

function getSaida($rs, $iLinhas) {
  
  $iIda   = 0;
  $iVolta = 0;
  for ($iI = 0; $iI < $iLinhas; $iI++) {
    
    $oDados  = db_utils::fieldsmemory($rs, $iI);
    $iIda   += $oDados->nro_acompanhantes + 1;
    if ($oDados->tf19_i_fica == 2) {
      $iVolta += $oDados->nro_acompanhantes + 1;
    }
    
  }
  $oSaida->DataSaida   = db_formatar($oDados->tf18_d_datasaida, 'd');
  $oSaida->DataRetorno = db_formatar($oDados->tf18_d_dataretorno, 'd');
  $oSaida->HoraSaida   = $oDados->tf18_c_horasaida;
  $oSaida->HoraRetorno = $oDados->tf18_c_horaretorno;
  $oSaida->TotalIda    = $iIda; 
  $oSaida->TotalVolta  = $iVolta;
  return $oSaida;

}

function getVeiculo($rs) {

  $oDados  = db_utils::fieldsmemory($rs, 0);
  $oVeiculo->LocalDestino = $oDados->cidadeempresa;
  $oVeiculo->Veiculo      = $oDados->veiculo;
  $oVeiculo->Motorista    = $oDados->motorista;
  $oVeiculo->Capacidade   = $oDados->ve01_quantcapacidad;
  return $oVeiculo;
  
}

if (isset($iChavePesquisa) && ! empty($iChavePesquisa)) {
  
  $sWhere = " tf18_i_codigo = $iChavePesquisa ";

} else {

  $sWhere = " tf18_i_destino = $coddestino";
  
  if (isset($codveiculo) && $codveiculo != '') {
    $sWhere .= " and tf18_i_veiculo = $codveiculo";
  }

  $dDataSaida = substr($datasaida, 6, 4).'-'.substr($datasaida, 3, 2).'-'.substr($datasaida, 0, 2);
  $sWhere    .= " and tf18_d_datasaida = '$dDataSaida' ";  
  $sWhere    .= " and tf18_c_horasaida = '$hora'";

}
$sWhere .= ' and tf19_i_tipopassageiro = 1';

$sCampos   = 'z01_i_cgsund, z01_v_nome, tf16_c_horaagendamento, a.z01_nome, ';
$sCampos  .= " (case when z01_v_telef <> '' and z01_v_telcel <> '' then  z01_v_telef||' / '||z01_v_telcel";
$sCampos  .= " when z01_v_telcel <> '' then z01_v_telcel when z01_v_telef <> '' then z01_v_telef else '' end)";
$sCampos  .= " as telefone, empresa.z01_munic||' - '||empresa.z01_uf as cidadeempresa, "; 
$sCampos  .= 've01_placa, tf18_d_datasaida, tf18_c_horasaida, tf03_c_descr,  ';
$sCampos  .= 'tf18_i_veiculo, tf18_c_localsaida, tf18_i_codigo, z01_v_cgccpf, ';
$sCampos  .= 'z01_c_certidaonum, tf19_i_fica, empresa.z01_nome as nomeempresa, tf18_d_dataretorno, ';
$sCampos  .= "tf18_c_horaretorno, (case when tf19_i_fica = 2 THEN 'Ida/Volta' else 'Ida' end) as direcao,";
$sCampos  .= "tf18_i_motorista||' - '||cgm.z01_nome as motorista, ve01_quantcapacidad, ";
$sCampos  .= "tf18_i_veiculo||' - '||ve21_descr||' '||ve22_descr||' - '||ve01_placa as veiculo, ";

$sNroAcmp  = '(select count(*) from tfd_acompanhantes where tf13_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo) ';
$sNroAcmp .= "as nro_acompanhantes";
$sCampos  .= $sNroAcmp;

$sSql     = $oDaoTfdVeiculoDestino->sql_query_lista_daer(null, $sCampos, 'z01_v_nome, nomeempresa', $sWhere);
$rs       = $oDaoTfdVeiculoDestino->sql_record($sSql);
$iLinhas  = $oDaoTfdVeiculoDestino->numrows;

if ($iLinhas == 0) {
  
  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado<br>
          <input type='button' value='Fechar' onclick='wicidadeempresandow.close()'><die($sSql);/b>
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
$head1 = "Relat�rio de Sa�da de Ve�culo";
novaPagina($oPdf);
$iY = $oPdf->getY();
retanguloVeiculo($oPdf, getVeiculo($rs));
retanguloSaida($oPdf, getSaida($rs, $iLinhas), $iY);
impCabecalho($oPdf);
for ($iI = 0; $iI < $iLinhas; $iI++) {
  
  $oPaciente = db_utils::fieldsmemory($rs, $iI);
  impPaciente($oPdf, $oPaciente);
  
}
$oPdf->Output();
?>