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

require_once('fpdf151/scpdf.php');
require_once('libs/db_utils.php');

$oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');
$oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');
$oDaoDbConfig     = db_utils::getdao('db_config');
$sData            = db_getsession('DB_datausu');
$sDataExtenso     = db_dataextenso($sData);

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

$sCampos  = 'tfd_pedidotfd.*, tfd_agendamentoprestadora.*, cgs_und.*, medicos.sd03_i_crm, ';
$sCampos .= 'db_usuarios.nome, rhcbo.rh70_estrutural, rhcbo.rh70_descr, db_depart.descrdepto, ';
$sCampos .= 'case when medicos.sd03_i_tipo = 1 then cgmmedico.z01_nome else s154_c_nome end as nomemedico, ';
$sCampos .= 'case when medicos.sd03_i_tipo = 1 then cgmdoc.z02_i_cns else s154_c_cns end as cnsmedico, ';
$sCampos .= 'cgm.z01_nome as nomeprest, cgm.z01_munic as municprest, cgm.z01_bairro as bairroprest,  ';
$sCampos .= 'cgm.z01_compl as complprest, cgm.z01_uf as ufprest, cgm.z01_numero as numprest, ';
$sCampos .= 'cgm.z01_ender as enderprest ';

$sSql     = $oDaoTfdPedidoTfd->sql_query_protocolo('', $sCampos, 'tf01_i_codigo', "tf01_i_codigo = $tf01_i_pedidotfd");
$rs       = $oDaoTfdPedidoTfd->sql_record($sSql);

if ($oDaoTfdPedidoTfd->numrows == 0) {?>
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

$sCamposInstit   = 'nomeinst as nome, ender, munic, uf, telef, email, url, logo';
$sSqlDadosInstit = $oDaoDbConfig->sql_query_file(db_getsession('DB_instit'), $sCamposInstit);
$rsDadosInstit   = $oDaoDbConfig->sql_record($sSqlDadosInstit);
$oDadosInstit    = db_utils::fieldsMemory($rsDadosInstit, 0);

$oPdf            = new FPDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->settopmargin(1);
$oPdf->SetAutoPageBreak(true, 0);
$oPdf->line(2, 148.5, 208, 148.5);
$oPdf->AddPage();

$iVias = 2;
for ($iCont = 0; $iCont < $oDaoTfdPedidoTfd->numrows; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);
  for ($iCont2 = 0; $iCont2 < $iVias; $iCont2++) {

	  if ($iCont2 % 2 == 0) {
      $iY = 169;
    } else {
      $iY = 20;
    }

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

    // Cabeçalho
    $oPdf->setfillcolor(245);
    $oPdf->roundedrect(2, $iY - 18, 206, 142.5, 2, 'DF', '1234');
    $oPdf->setfillcolor(255, 255, 255);
    $oPdf->Setfont('arial', 'B', 8);

    $sSituacaoProtocolo = 'PEDIDO TFD N'.chr(176).' '.$oDados->tf01_i_codigo;
    $iPosicaoEscrita    = 170;
    if ($oDados->tf01_i_situacao == 4) {

      $iPosicaoEscrita    = 155;
      $sSituacaoProtocolo = 'CANCELADO PEDIDO TFD N'.chr(176).' '.$oDados->tf01_i_codigo;
    }

    $oPdf->text($iPosicaoEscrita, $iY - 13, $sSituacaoProtocolo);
    $oPdf->Image('imagens/files/logo_boleto.png', 10, $iY - 16, 12);
    $oPdf->Setfont('arial', 'B', 9);
    $oPdf->text(30, $iY - 13, $oDadosInstit->nome);
    $oPdf->Setfont('arial', '', 9);
    $oPdf->text(30, $iY - 9, $oDadosInstit->ender);
    $oPdf->text(30, $iY - 6, $oDadosInstit->munic);
    $oPdf->text(30, $iY - 3, $oDadosInstit->telef);
    $oPdf->text(30, $iY, $oDadosInstit->email);

    // Retângulo do pedido
    $oPdf->Roundedrect(4, $iY, 202, 10, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 4, 'PEDIDO');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 8, 'Departamento');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 8, ': '.substr($oDados->tf01_i_depto.' - '.$oDados->descrdepto, 0, 35));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(86, $iY + 8, ' Data do Pedido');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 8, ': '.db_formatar($oDados->tf01_d_datapedido, 'd'));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(126, $iY + 8, 'Funcionário');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(144, $iY + 8, ': '.substr($oDados->tf01_i_login.' - '.$oDados->nome, 0, 35));

    // Retângulo do paciente
    $oPdf->Roundedrect(4, $iY + 11, 202, 32, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 16, 'PACIENTE');
    $oPdf->Setfont('arial', 'B', 8);-
    $oPdf->text(13, $iY + 20, 'Paciente');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 20, ': '.$oDados->z01_v_nome);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(101, $iY + 20, 'CGS ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 20, ': '.$oDados->z01_i_cgsund);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(137, $iY + 20, 'Data Nascimento');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 20, ': '.db_formatar($oDados->z01_d_nasc, 'd'));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(20, $iY + 24, 'RG ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 24, ': '.$oDados->z01_v_ident);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(102, $iY + 24, 'CPF ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 24, ': '.$oDados->z01_v_cgccpf);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(144, $iY + 24, 'Cartão SUS ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 24, ': '.getCns($oDados->tf01_i_cgsund));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 28, 'Nome da Mãe');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 28, ': '.substr($oDados->z01_v_mae, 0, 32));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(101, $iY + 28, 'Sexo ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 28, ': '.$sSexo);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(11, $iY + 32, 'Endereço');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 32, ': '.substr($oDados->z01_v_ender.' '.$oDados->z01_v_compl, 0, 36));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(97, $iY + 32, 'Número');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 32, ': '.$oDados->z01_i_numero);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(141, $iY + 32, 'Complemento ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 32, ': '.substr($oDados->z01_v_compl, 0, 23));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(15, $iY + 36, 'Bairro ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 36, ': '.substr($oDados->z01_v_bairro, 0, 23));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(95, $iY + 36, 'Município ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 36, ': '.$oDados->z01_v_munic);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(155, $iY + 36, 'UF ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 36, ': '.$oDados->z01_v_uf);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(17, $iY + 40, 'CEP ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 40, ': '.$oDados->z01_v_cep);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(95, $iY + 40, 'Telefone ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 40, ': '.$oDados->z01_v_telef);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(150, $iY + 40, 'Celular ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 40, ': '.$oDados->z01_v_telcel);

    // Retângulo do solicitante
    $oPdf->Roundedrect(4, $iY + 44, 202, 10, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 48, 'SOLICITANTE ');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(14, $iY + 52, 'Médico ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 52, ': '.$oDados->nomemedico);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(101, $iY + 52, 'CRM ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 52, ': '.$oDados->sd03_i_crm);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(154, $iY + 52, 'CNS ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 52, ': '.$oDados->cnsmedico);

     // Retângulo do prestadora
    $oPdf->Roundedrect(4, $iY + 55, 202, 19, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 59, 'PRESTADORA ');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(10, $iY + 63, 'Prestadora ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 63, ': '.$oDados->nomeprest);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(12, $iY + 67, 'Endereço ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 67, ': '.$oDados->enderprest);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(96, $iY + 67, 'Número');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 67, ': '.$oDados->numprest);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(142, $iY + 67, 'Complemento');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 67, ': '.$oDados->complprest);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(16, $iY + 71, 'Bairro ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 71, ': '.$oDados->bairroprest);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(94, $iY + 71, 'Município');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 71, ': '.$oDados->municprest);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(156, $iY + 71, 'UF');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 71, ': '.$oDados->ufprest);

    // Retângulo consulta/exame
    $oPdf->Roundedrect(4, $iY + 75, 202, 22, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 79, 'CONSULTA/EXAME ');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 83, 'Especialidade ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 83, ': '.substr($oDados->rh70_descr, 0, 36));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(89, $iY + 83, 'Complemento ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 83, ': '.$oDados->tf01_complespec);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(10, $iY + 87, 'Protocolo ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 87, ': '.$oDados->tf16_c_protocolo);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(86, $iY + 87, 'Consulta/Exame');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 87, ': '.db_formatar($oDados->tf16_d_dataagendamento, 'd'));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(154, $iY + 87, 'Hora');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 87, ': '.$oDados->tf16_c_horaagendamento);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(8, $iY + 91, 'Profissional');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 91, ': '.$oDados->tf16_c_medico);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(101, $iY + 91, 'CRM');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 91, ': '.$oDados->tf16_c_crmmedico);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(154, $iY + 91, 'CNS');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 91, ': '.$oDados->tf16_c_cnsmedico);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(10, $iY + 95, 'Ref. Local');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 95, ': '.$oDados->tf16_c_local);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(102, $iY + 95, 'Sala');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 95, ': '.$oDados->tf16_sala);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(147, $iY + 95, 'Sequencia');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 95, ': '.$oDados->tf16_sequencia);

    // Retângulo obs
    $oPdf->Roundedrect(4, $iY + 98, 109, 25, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 103, 'OBSERVAÇÃO:');
    $oPdf->setX(4);
    $oPdf->setY($iY + 105);
    $oPdf->Setfont('arial', '', 8);
    $oPdf->multicell(110, 3.5, $oDados->tf01_t_obs, 0, 'L');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->line(134, $iY + 110, 199, $iY + 110);
    $oPdf->text(156, $iY + 114, 'RECEBEDOR');
    $oPdf->text(138, $iY + 120, $oDadosInstit->munic.', '.strtoupper($sDataExtenso));
    $oPdf->Setfont('arial', '', 8);

  }

}
$oPdf->Output();
?>