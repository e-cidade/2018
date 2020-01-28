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

require_once("fpdf151/pdf.php");
require_once('libs/db_utils.php');
require_once('libs/db_stdlibwebseller.php');

db_postmemory($HTTP_POST_VARS);

$oDaoFarParametros   = db_utils::getdao("far_parametros");
$oFarControlemed     = db_utils::getdao("far_controlemed");

$dHoje               = date('Y-m-d', db_getsession('DB_datausu'));
$aHoje               = explode('-', $dHoje);
$tHoje               = mktime(0, 0 , 0, $aHoje[1], $aHoje[2], $aHoje[0]);

$sSql                = $oDaoFarParametros->sql_query(null,' fa02_i_tipoperiodocontinuado, '.
                                                     'fa02_i_acumularsaldocontinuado, '.
                                                     'fa02_i_numdiasmedcontinativo '
                                                    );
$rsFarParametros     =  $oDaoFarParametros->sql_record($sSql);
$oDadosFarParametros = db_utils::fieldsmemory($rsFarParametros, 0);

$sCampos             = 'fa01_i_codigo, m60_descr, z01_i_cgsund, z01_v_nome, z01_v_cgccpf, ';
$sCampos            .= "z01_v_ender || ' - ' || z01_i_numero as z01_v_ender, z01_v_telef ";
$sWhere              = " fa10_i_medicamento in ($sMedicamentos) ";

$sSubMovimentacao    = 'select * ';
$sSubMovimentacao   .= '  from far_retiradaitens';
$sSubMovimentacao   .= '    inner join far_retirada on far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada';
$sSubMovimentacao   .= '      where far_retiradaitens.fa06_i_matersaude = fa01_i_codigo';
$sSubMovimentacao   .= '        and far_retirada.fa04_i_cgsund = z01_i_cgsund';

/* Cálculo da data a partir da qual, se não foram ralizadas retiradas, o cadastro de continuados 
  é dito sem movimentaçao (cancelado) */
if ($oDadosFarParametros->fa02_i_numdiasmedcontinativo > 0) {

  $tDataInativo      = $tHoje - ($oDadosFarParametros->fa02_i_numdiasmedcontinativo * 86400); // hoje - N dias
  $dDataInativo      = date('Y-m-d', $tDataInativo);
  $sSubMovimentacao .= " and far_retirada.fa04_d_data > '$dDataInativo'";

}

switch ($iMovimentacao) {
 
  case 1:
    
    $head4            = 'Movimentação: Todos'; 
    $sSubMovimentacao = '';
    break;
 
  case 2:

    $head4            = 'Movimentação: Ativos'; 
    $sSubMovimentacao = ' and exists('.$sSubMovimentacao.') ';
    break;

  default:

    $head4            = 'Movimentação: Inativos'; 
    $sSubMovimentacao = ' and not exists('.$sSubMovimentacao.') ';

    
}
$sWhere .= $sSubMovimentacao;

if (isset($iIdadeIni) && $iIdadeIni != "") {

  $iTimeStamp     = mktime(0, 0, 0, date("m", db_getsession("DB_datausu")),
                           date("d", db_getsession("DB_datausu")),
                           date("Y", db_getsession("DB_datausu"))
                          );
  $iTimeStampIni  = $iTimeStamp - ($iIdadeIni * 29030400);
  $aIni           = explode("/", date("d/m/Y",$iTimeStampIni));
  $iTimeStampFim  = $iTimeStamp - ($iIdadeFim * 29030400);
  $aFim           = explode("/", date("d/m/Y", $iTimeStampFim));
  $sWhere        .= " and z01_d_nasc between '".$aFim[2].'-'.$aFim[1].'-'.$aFim[0];
  $sWhere        .= "' and '".$aIni[2].'-'.$aIni[1].'-'.$aIni[0]."' ";

}

$sWhere             .= ' group by m60_descr, fa01_i_codigo, z01_i_cgsund, z01_v_nome, ';
$sWhere             .= ' z01_v_cgccpf, z01_v_ender, z01_v_telef, z01_i_numero ';
$sWhere             .= ' order by m60_descr, z01_v_nome ';
$sSql                = $oFarControlemed->sql_query(null, $sCampos, '', $sWhere);
$rsMedicamentos      = $oFarControlemed->sql_record($sSql);
$iMedicamentosLinhas = $oFarControlemed->numrows;

if ($iMedicamentosLinhas <= 0) {
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

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$head1 = " Medicamentos Continuados Utilizados ";
if (isset($iIdadeIni) && $iIdadeIni != "") {
  $head2 = "idade: $iIdadeIni à $iIdadeFim ";
}
if ($iDispensacao == 1) {
  $head3 = "Dispensação: Pendente";
} else {
  $head3 = "Dispensação: Não Consta";
}

// Verificação dos parâmetros da PL do saldo dos continuados
if ((int)$oDadosFarParametros->fa02_i_acumularsaldocontinuado == 1) {
  $lAcumularSaldo = 'true';
} else {
  $lAcumularSaldo = 'false';
}
if ((int)$oDadosFarParametros->fa02_i_tipoperiodocontinuado == 1) {
  $sSqlPl  = " select fc_saldocontinuado_periodo_fixo(";
} else {
  $sSqlPl  = 'select fc_saldocontinuado_periodo_dinamico(';
}

$iTotal           = 0;
$iSoma            = 0;
$iMedicamento     = 0;
$aSomaMedicamento = array ();
$oMedicamentos    = db_utils::fieldsmemory($rsMedicamentos, 0);
$iMedicamento     = $oMedicamentos->fa01_i_codigo;
$aMedicamentos    = array();

//Calcula total de pacientes por medicamentos
for ($iCount = 0; $iCount < $iMedicamentosLinhas; $iCount++) {

  $oMedicamentos = db_utils::fieldsmemory($rsMedicamentos, $iCount);
  if ($iDispensacao == 1) { // tem que eliminar os registros de pacientes que não possuem saldo


    $sSqlRetornoFunc  = $sSqlPl.$oMedicamentos->z01_i_cgsund.', '.$oMedicamentos->fa01_i_codigo;
    $sSqlRetornoFunc .= ', '."'$dHoje', $lAcumularSaldo) as saldo ";

    $rsFunc = pg_query($sSqlRetornoFunc);
    $oFunc  = db_utils::fieldsmemory($rsFunc, 0);
    if (substr($oFunc->saldo, 0, 1) != '0') { // se possui saldo, então está pendente

      $iTam                                  = count($aMedicamentos);
      $aMedicamentos[$iTam]["fa01_i_codigo"] = $oMedicamentos->fa01_i_codigo;
      $aMedicamentos[$iTam]["m60_descr"]     = $oMedicamentos->m60_descr;
      $aMedicamentos[$iTam]["z01_i_cgsund"]  = $oMedicamentos->z01_i_cgsund;
      $aMedicamentos[$iTam]["z01_v_nome"]    = $oMedicamentos->z01_v_nome;
      $aMedicamentos[$iTam]["z01_v_cgccpf"]  = $oMedicamentos->z01_v_cgccpf;
      $aMedicamentos[$iTam]["z01_v_ender"]   = $oMedicamentos->z01_v_ender;
      $aMedicamentos[$iTam]["z01_v_telef"]   = $oMedicamentos->z01_v_telef;
      
      if ($oMedicamentos->fa01_i_codigo != $iMedicamento) {
  
        $aSomaMedicamento[$iMedicamento] = $iSoma;
        $iMedicamento                    = $oMedicamentos->fa01_i_codigo;
        $iSoma                           = 1;
  
      } else {
        $iSoma++;
      }

    }

  } else { // não elimina nenhum registro dos que vieram (não filtra)
  
    $iTam                                  = count($aMedicamentos);
    $aMedicamentos[$iTam]["fa01_i_codigo"] = $oMedicamentos->fa01_i_codigo;
    $aMedicamentos[$iTam]["m60_descr"]     = $oMedicamentos->m60_descr;
    $aMedicamentos[$iTam]["z01_i_cgsund"]  = $oMedicamentos->z01_i_cgsund;
    $aMedicamentos[$iTam]["z01_v_nome"]    = $oMedicamentos->z01_v_nome;
    $aMedicamentos[$iTam]["z01_v_cgccpf"]  = $oMedicamentos->z01_v_cgccpf;
    $aMedicamentos[$iTam]["z01_v_ender"]   = $oMedicamentos->z01_v_ender;
    $aMedicamentos[$iTam]["z01_v_telef"]   = $oMedicamentos->z01_v_telef;
    if ($oMedicamentos->fa01_i_codigo != $iMedicamento) {
  
      $aSomaMedicamento[$iMedicamento] = $iSoma;
      $iMedicamento                    = $oMedicamentos->fa01_i_codigo;
      $iSoma                           = 0;
  
    } else {
      $iSoma++;
    }

  }

}
$aSomaMedicamento[$iMedicamento] = $iSoma;
$ifirst                          = 1;
for ($iCount = 0; $iCount < count($aMedicamentos); $iCount++) {

  if (($pdf->gety() > $pdf->h - 30)
      || ($aMedicamentos[$iCount]["fa01_i_codigo"] != $iMedicamento)
      || ($ifirst == 1)) {

    $pdf->ln(5);
    $pdf->addpage('P');
    $pdf->setfillcolor(180);
    $pdf->setfont('arial', '', 11);
    $pdf->cell(140, 5, "Medicamento: ".$aMedicamentos[$iCount]["m60_descr"], "TLB", 0, "L", 1);
    $pdf->cell(50, 5, "Total de Pacientes: ".$aSomaMedicamento[$aMedicamentos[$iCount]["fa01_i_codigo"]], 
               "TRB", 1, "R", 1
              );
    $pdf->SetFillColor(230);
    $pdf->cell(13, 5, "CGS", 1, 0, "L", 1);
    $pdf->cell(65, 5, "Paciente", 1, 0, "L", 1);
    $pdf->cell(20, 5, "CPF", 1, 0, "L", 1);
    $pdf->cell(75, 5, "Endereço", 1, 0, "L", 1);
    $pdf->cell(17, 5, "Telefone", 1, 1, "L", 1);
    $iCountLinhas = 0;
    $ifirst       = 0;
    $iMedicamento = $aMedicamentos[$iCount]["fa01_i_codigo"];

  }
  $pdf->setfont('arial', '', 8);
  $pdf->cell(13, 5, $aMedicamentos[$iCount]["z01_i_cgsund"], 1, 0, "R", 0);
  $pdf->cell(65, 5, substr($aMedicamentos[$iCount]["z01_v_nome"], 0, 35), 1, 0, "L", 0);
  $pdf->cell(20, 5, $aMedicamentos[$iCount]["z01_v_cgccpf"], 1, 0, "L", 0);
  $pdf->cell(75, 5, $aMedicamentos[$iCount]["z01_v_ender"], 1, 0, "L", 0);
  $pdf->cell(17, 5, $aMedicamentos[$iCount]["z01_v_telef"], 1, 1, "L", 0);
  $iCountLinhas++;

}

$pdf->Output();
?>