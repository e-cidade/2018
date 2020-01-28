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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oDaoTfdPedidotfd = db_utils::getdao('tfd_pedidotfd');
$oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');

function novaEspecialidade($oPdf, $sEstrutural, $sEspecialidade, $sComplemento) {

 $lCor = false;
 $iTam = 6;

 $oPdf->setfont('arial', 'B', 12);
 $oPdf->cell(190, $iTam, $sEstrutural.' - '.$sEspecialidade, 0, 1, 'L', $lCor);
 if ($sComplemento != "") {
   $oPdf->cell(190, $iTam, $sComplemento, 0, 1, 'L', $lCor);
 }

}


function getCns($iCgs) {

  global $oDaoCgsCartaoSus;

  $sSql = $oDaoCgsCartaoSus->sql_query(null, ' s115_c_cartaosus ', ' s115_c_tipo asc ',
                                       ' s115_i_cgs = '.$iCgs
                                      );
  $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);
  if ($oDaoCgsCartaoSus->numrows != 0) { // se o paciente tem um cartao sus

    $oDadosCgsCartaoSus = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $sCartaoSus         = $oDadosCgsCartaoSus->s115_c_cartaosus;

  }  else {
    $sCartaoSus = '';
  }

  return $sCartaoSus;

}


function novoPedido($oPdf, $oPedido, $oPaciente, $oSolicitante, $oPrestadora, $oConExame){

 $lCor = false;
 $iTam = 5;
 
 /* PEDIDO */
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(26, $iTam, 'Departamento:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(70, $iTam, $oPedido->sDepto, "BT", 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(23, $iTam, 'Funcionario: ', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(71, $iTam, $oPedido->sLogin." - ".$oPedido->sNomeLogin, 'RBT', 1, 'L', $lCor);
         
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(14, $iTam, 'Pedido:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(22, $iTam, $oPedido->iPedido, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(30, $iTam, 'Data do Pedido:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(30, $iTam, $oPedido->dData, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(20, $iTam, 'Urgência:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(74, $iTam, $oPedido->sEmergencia, 'RBT', 1, 'L', $lCor);

 
 /* PACIENTE */
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'Nome:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(84, $iTam, $oPaciente->sNome, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(22, $iTam, 'Nascimento:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(72, $iTam, $oPaciente->dNasc, 'RBT', 1, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'CGS:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(84, $iTam, $oPaciente->iCgs, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Cartão SUS:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(69, $iTam, $oPaciente->iSusCard, 'RBT', 1, 'L', $lCor);
 
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'RG:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(84, $iTam, $oPaciente->sRg, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'CPF:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(69, $iTam, $oPaciente->sCpf, 'RBT', 1, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'Mãe:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(84, $iTam, $oPaciente->sMae, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Sexo:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(69, $iTam, $oPaciente->sSexo, 'RBT', 1, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(19, $iTam, 'Endereço:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(77, $iTam, $oPaciente->sEnder.", n° ".$oPaciente->iNumero, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Complemento:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(69, $iTam, $oPaciente->sCompl, 'RBT', 1, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(14, $iTam, 'Bairro:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(82, $iTam, $oPaciente->sBairro, "BT", 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(20, $iTam, 'Município:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(74, $iTam, $oPaciente->sMunic, 'RBT', 1, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(14, $iTam, 'CEP:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(82, $iTam, $oPaciente->sCep, "BT", 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(20, $iTam, 'UF:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(74, $iTam, $oPaciente->sUf, 'RBT', 1, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(18, $iTam, 'Telefone:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(78, $iTam, $oPaciente->sTelef, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(16, $iTam, 'Celular:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(78, $iTam, $oPaciente->sCel, 'RBT', 1, 'L', $lCor);

 /* SOLICITANTE */
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Solicitante:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(71, $iTam, $oSolicitante->sNome, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'CRM:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(35, $iTam, $oSolicitante->iCrm, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'CNS:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(35, $iTam, $oSolicitante->cnsMedico, 'RBT', 1, 'L', $lCor);
 
 
/* PRESTADORA */
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(22, $iTam, 'Prestadora:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(168, $iTam, $oPrestadora->sNome, 'RBT', 1, 'L', $lCor);


 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(19, $iTam, 'Endereço:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(77, $iTam, $oPrestadora->sEnder.", n° ".$oPrestadora->iNumero, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Complemento:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(69, $iTam, $oPrestadora->sCompl, 'RBT', 1, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(14, $iTam, 'Bairro:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(83, $iTam, $oPrestadora->sBairro, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(20, $iTam, 'Município:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(51, $iTam, $oPrestadora->sMunic, 'BT', 0, 'L', $lCor);
 
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(10, $iTam, 'UF:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(12, $iTam, $oPrestadora->sUf, 'RBT', 1, 'L', $lCor);

 /* CONSULTA/EXAME */
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(20, $iTam, 'Con/Exam: ', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(30, $iTam, $oConExame->dDataAgend, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(15, $iTam, 'Hora:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(32, $iTam, $oConExame->sHoraAgend, 'BT', 0, 'L', $lCor);
 
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(15, $iTam, 'Sala:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(30, $iTam, $oConExame->sSala, 'BT', 0, 'L', $lCor);
 
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Sequencia:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(23, $iTam, $oConExame->sSequencia, 'RBT', 1, 'L', $lCor);
 
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Profissional:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(72, $iTam, $oConExame->sProfissional, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'CRM:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(34, $iTam, $oConExame->sCrm, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(12, $iTam, 'CNS:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(35, $iTam, $oConExame->sCns, 'RBT', 1, 'L', $lCor);
 
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Protocolo:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(72, $iTam, $oConExame->sProtocolo, 'BT', 0, 'L', $lCor);

 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(25, $iTam, 'Local Ref.:', 'BT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(68, $iTam, $oConExame->sLocal, 'RBT', 1, 'L', $lCor);
 
 $oPdf->setfont('arial', 'B', 10);
 $oPdf->cell(24, $iTam, 'Observação:', 'LBT', 0, 'L', $lCor);
 $oPdf->setfont('arial', '', 10);
 $oPdf->cell(166, $iTam, "", 'RBT', 1, 'L', $lCor);

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

$dDataIni = substr($dataini,6,4).'-'.substr($dataini,3,2).'-'.substr($dataini,0,2);
$dDataFim = substr($datafim,6,4).'-'.substr($datafim,3,2).'-'.substr($datafim,0,2);
$sWhere   = " tf01_d_datapedido between '$dDataIni' and '$dDataFim'";
  
if (isset($codigoespec) && $codigoespec != '') {
  $sWhere .= " and rh70_estrutural = '$codigoespec'";
}

$sCampos  = 'tfd_pedidotfd.*, tfd_agendamentoprestadora.*, cgs_und.*, medicos.sd03_i_crm, ';
$sCampos .= 'db_usuarios.nome, rhcbo.rh70_estrutural, rhcbo.rh70_descr, db_depart.descrdepto, ';
$sCampos .= 'case when medicos.sd03_i_tipo = 1 then cgmmedico.z01_nome else s154_c_nome end as nomemedico, ';
$sCampos .= 'case when medicos.sd03_i_tipo = 1 then cgmdoc.z02_i_cns else s154_c_cns end as cnsmedico, ';
$sCampos .= 'cgm.z01_nome as nomeprest, cgm.z01_munic as municprest, cgm.z01_bairro as bairroprest,  ';
$sCampos .= 'cgm.z01_compl as complprest, cgm.z01_uf as ufprest, cgm.z01_numero as numprest, ';
$sCampos .= 'cgm.z01_ender as enderprest ';

$sOrderBy = 'rh70_estrutural, tf01_d_datapedido, tf01_i_emergencia, z01_v_nome';

$sSql     = $oDaoTfdPedidotfd->sql_query_protocolo('', $sCampos, $sOrderBy, $sWhere);
$sWhere   = "tf01_i_codigo in (".$Pedidos.") ";
$sSql2    = $oDaoTfdPedidotfd->sql_query_protocolo('', $sCampos, $sOrderBy, $sWhere);
$rs       = $oDaoTfdPedidotfd->sql_record('('.$sSql.') UNION ('.$sSql2.')');
$iLinhas  = $oDaoTfdPedidotfd->numrows;

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
$head1 = "RELATÓRIO AGENDA PRESTADORA SAÍDA";
$head2 = "PERÍODO: $dataini a $datafim";
$head4 = "ESPECIALIDADE: $codigoespec - $especialidade";
  
$oPdf->setfillcolor(240);
 
$sEspec = '';
for($iCont = 0; $iCont < $iLinhas; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);

  if (empty($oDados->z01_v_ender)) {
    $oDados->z01_v_ender = '';
  }
  if (empty($oDados->z01_i_numero)) {
    $oDados->z01_i_numero = '';
  }
  if (empty($oDados->z01_v_bairro)) {
    $oDados->z01_v_bairro = '';
  }
  if (empty($oDados->z01_v_munic)) {
    $oDados->z01_v_munic = '';
  }
  $sSexo = 'FEMININO';
  if ($oDados->z01_v_sexo == 'M') {
    $sSexo = 'MASCULINO';
  } 
  if ($oDados->z01_v_ident == 0) {
    $oDados->z01_v_ident = '';
  }
  if ($oDados->z01_v_cgccpf == 0) {
    $oDados->z01_v_cgccpf = '';
  }
  if ($oDados->tf01_i_emergencia == 1){
    $oDados->tf01_i_emergencia = 'SIM';
  } else {
    $oDados->tf01_i_emergencia = 'NÃO';
  }
  
  if ($sEspec != $oDados->rh70_estrutural) {

    $sEspec = $oDados->rh70_estrutural;
    $oPdf->addpage('P');
    novaEspecialidade($oPdf, $oDados->rh70_estrutural, $oDados->rh70_descr, $oDados->tf01_complespec);

  }
  /* PEDIDO */
  $oPedido->sDepto      = substr($oDados->tf01_i_depto.' - '.$oDados->descrdepto, 0, 35);
  $oPedido->sLogin      = $oDados->tf01_i_login;
  $oPedido->sNomeLogin  = $oDados->nome;
  $oPedido->dData       = db_formatar($oDados->tf01_d_datapedido, 'd');
  $oPedido->iPedido     = $oDados->tf01_i_codigo;
  $oPedido->sEmergencia = $oDados->tf01_i_emergencia;
  
  
  /* PACIENTE */
  $oPaciente->sNome    = $oDados->z01_v_nome;
  $oPaciente->iCgs     = $oDados->z01_i_cgsund;
  $oPaciente->dNasc    = db_formatar($oDados->z01_d_nasc, 'd'); 
  $oPaciente->sCpf     = $oDados->z01_v_cgccpf;
  $oPaciente->sRg      = $oDados->z01_v_ident;
  $oPaciente->iSusCard = getCns($oDados->tf01_i_cgsund);
  $oPaciente->sMae     = substr($oDados->z01_v_mae, 0, 32);
  $oPaciente->sSexo    = $sSexo;
  $oPaciente->sEnder   = substr($oDados->z01_v_ender, 0, 36);
  $oPaciente->iNumero  = $oDados->z01_i_numero;
  $oPaciente->sCompl   = substr($oDados->z01_v_compl, 0, 23);
  $oPaciente->sBairro  = substr($oDados->z01_v_bairro, 0, 23);
  $oPaciente->sMunic   = $oDados->z01_v_munic;
  $oPaciente->sUf      = $oDados->z01_v_uf;
  $oPaciente->sCep     = $oDados->z01_v_cep;
  $oPaciente->sTelef   = $oDados->z01_v_telef;
  $oPaciente->sCel     = $oDados->z01_v_telcel;
  
  /* SOLICITANTE */
  $oSolicitante->sNome     = $oDados->nomemedico;
  $oSolicitante->iCrm      = $oDados->sd03_i_crm;
  $oSolicitante->cnsMedico = $oDados->cnsmedico;
  
  /* PRESTADORA */
  $oPrestadora->sNome   = $oDados->nomeprest;
  $oPrestadora->sEnder  = $oDados->enderprest;
  $oPrestadora->iNumero = $oDados->numprest;
  $oPrestadora->sCompl  = $oDados->complprest;
  $oPrestadora->sBairro = $oDados->bairroprest;
  $oPrestadora->sMunic  = $oDados->municprest;
  $oPrestadora->sUf     = $oDados->ufprest;

  /* CONSULTA/EXAME */
  $oConExame->sEspec        = substr($oDados->rh70_descr, 0, 36);
  $oConExame->sComplEspec   = $oDados->tf01_complespec;
  $oConExame->sProtocolo    = $oDados->tf16_c_protocolo;
  $oConExame->dDataAgend    = db_formatar($oDados->tf16_d_dataagendamento, 'd');
  $oConExame->sHoraAgend    = $oDados->tf16_c_horaagendamento;
  $oConExame->sProfissional = $oDados->tf16_c_medico;
  $oConExame->sCrm          = $oDados->tf16_c_crmmedico;
  $oConExame->sCns          = $oDados->tf16_c_cnsmedico;
  $oConExame->sLocal        = $oDados->tf16_c_local;
  $oConExame->sSala         = $oDados->tf16_sala;
  $oConExame->sSequencia    = $oDados->tf16_sequencia;
  
  /* GERAR NOVO PEDIDO */
  novoPedido($oPdf, $oPedido, $oPaciente, $oSolicitante, $oPrestadora, $oConExame);

  if ($oPdf->getY() > $oPdf->h - 30) {
    $oPdf->addpage('P');
  } 

}
$oPdf->Output();
?>