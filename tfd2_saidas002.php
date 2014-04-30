<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once('libs/db_stdlibwebseller.php');
/* ==================================================================
 *               ESPECIFICAÇÕES GERAÇÃO RELATÓRIO
 * ==================================================================
 *  $tipo    = 0 -> VIAGENS
 *           = 1 -> COM TOTAIS (apenas registros com ajuda de custo)
 *           = 2 -> SEM TOTAIS (com ou sem ajuda de custo)
 *        
 *  $destino = CIDADE PRESTADORA -> tf03_i_codigo
 * ==================================================================
 */


$oDaoTfdPedidoTfd   = db_utils::getdao('tfd_pedidotfd');
$oDaoCgsUnd         = db_utils::getdao('cgs_und');

function formataData($dData, $iTipo) {
 
  if ($dData == '') {
    return $dData;
  }
  if ($iTipo == 1) {
  
    $aData = explode('/',$dData);
    $dData = @$aData[2].'-'.@$aData[1].'-'.@$aData[0];
    return $dData;
    
  }
  $aData = explode('-',$dData);
  $dData = @$aData[2].'/'.@$aData[1].'/'.@$aData[0];
  return $dData;

}

function novaPagina($oPdf) {
  
  global $iLinhas;
  $oPdf->ln(5);
  $oPdf->addpage('L');
  $iLinhas = 0;

}

/*TOPO DEVE ESTAR PRESENTE APENAS NO RELATÓRIO DO TIPO VIAGENS ($tipo == 0) */
function impTopo($oPdf) {
  
  global $iLinhas;
  $oPdf->cell(20, 4, "MOTORISTA:", 0, 1, "L", 0); 
  $oPdf->Line($oPdf->getX()+ 20, $oPdf->getY() - 1, $oPdf->getX() + 90, $oPdf->getY() - 1);
  $oPdf->cell(90, 4, "VEÍCULO:", 0, 0, "L", 0); 
  $oPdf->Line($oPdf->getX() - 70, $oPdf->getY() + 3, $oPdf->getX(), $oPdf->getY() + 3);
  $oPdf->cell(31, 4, "DATA:", 0, 0, "L", 0); 
  $oPdf->Line($oPdf->getX() - 20, $oPdf->getY() + 3, $oPdf->getX(), $oPdf->getY() + 3);
  $oPdf->cell(31, 4, "HORA:", 0, 0, "L", 0);   
  $oPdf->Line($oPdf->getX() - 20, $oPdf->getY() + 3, $oPdf->getX(), $oPdf->getY() + 3);
  $oPdf->cell(40, 4, "(D)DESISTENTE", 0, 0, "C", 0); 
  $oPdf->cell(40, 4, "(NC)NÃO COMPARECEU", 0, 0, "C", 0); 
  $oPdf->cell(40, 4, "(AC)ACOMPANHANTE", 0, 1, "C", 0); 
  $oPdf->setXY($oPdf->getX(), $oPdf->getY() + 2);
  $iLinhas += 3;

}

function impCabecalho($oPdf, $iTipo) { 

  global $iLinhas;
  $oPdf->setfont('arial', 'b', 8);
  if ($iTipo == 0) {
    
    $oPdf->setfillcolor(235);
    $oPdf->cell(15, 4, "Pedido", 1, 0, "L", 1); 
    $oPdf->cell(15, 4, "CGS", 1, 0, "L", 1); 
    $oPdf->cell(40, 4, "Paciente", 1, 0, "L", 1);
    $oPdf->cell(30, 4, "Destino", 1, 0, "L", 1);
    $oPdf->cell(40, 4, "Prestadora", 1, 0, "L", 1);
    $oPdf->cell(15, 4, "Dt. Saída", 1, 0, "L", 1);
    $oPdf->cell(15, 4, "Hr. Saída", 1, 0, "L", 1);
    $oPdf->cell(30, 4, "Local Saída", 1, 0, "L", 1);
    $oPdf->cell(30, 4, "Telefone", 1, 0, "L", 1);
    $oPdf->cell(25, 4, "Identidade", 1, 0, "L", 1);
    $oPdf->cell(25, 4, "Assinatura", 1, 1, "L", 1);
  
  } else {
    
    $oPdf->setfillcolor(235);
    $oPdf->cell(15, 4, "Pedido", 1, 0, "L", 1); 
    $oPdf->cell(15, 4, "CGS", 1, 0, "L", 1); 
    $oPdf->cell(40, 4, "Paciente", 1, 0, "L", 1);
    $oPdf->cell(20, 4, "Nascimento", 1, 0, "L", 1);
    $oPdf->cell(28, 4, "Cartão SUS", 1, 0, "L", 1);
    $oPdf->cell(70, 4, "Especialidade", 1, 0, "L", 1);
    $oPdf->cell(50, 4, "Destino", 1, 0, "L", 1);
    $oPdf->cell(20, 4, "Valor Gasto", 1, 1, "L", 1);
    
  }
  $oPdf->setfillcolor(255);
  $iLinhas++;
  
}

function impPaciente($oPdf, $oPaciente, $iTipo) {
  
  global $iLinhas;
  if ($iTipo == 1 && $oPaciente->ajudacusto == 0) {
    return;
  } 
  $oPdf->setfont('arial', '', 8);  
  $oPdf->cell(15, 4, $oPaciente->tf01_i_codigo, 1, 0, "L", 1); 
  $oPdf->cell(15, 4, $oPaciente->cgs, 1, 0, "L", 1); 
  $oPdf->cell(40, 4, $oPaciente->paciente, 1, 0, "L", 1);    $iLinhas++;
  if ($iTipo == 0) {
    
    $oPdf->cell(30, 4, $oPaciente->tf03_c_descr, 1, 0, "L", 1);
    $oPdf->cell(40, 4, $oPaciente->prestadora, 1, 0, "L", 1);
    $oPdf->cell(15, 4, formataData($oPaciente->tf17_d_datasaida, 2), 1, 0, "C", 1);
    $oPdf->cell(15, 4, $oPaciente->tf17_c_horasaida, 1, 0, "C", 1);
    $oPdf->cell(30, 4, $oPaciente->tf17_c_localsaida, 1, 0, "L", 1);
    $oPdf->cell(30, 4, $oPaciente->telefone, 1, 0, "L", 1);
    $oPdf->cell(25, 4, $oPaciente->identidade, 1, 0, "L", 1);
    $oPdf->cell(25, 4, '', 1, 1, "L", 1);
    
  } else {
    
    $oPdf->cell(20, 4, formataData($oPaciente->nascimento, 2), 1, 0, "C", 1);
    $oPdf->cell(28, 4, $oPaciente->cartaosus, 1, 0, "L", 1);
    $oPdf->cell(70, 4, converteCodificacao($oPaciente->especialidade), 1, 0, "L", 1);
    $oPdf->cell(50, 4, $oPaciente->tf03_c_descr, 1, 0, "L", 1);
    $oPdf->cell(20, 4, number_format($oPaciente->ajudacusto, 2, ',', '.'), 1, 1, "C", 1);
    
  }
  $iLinhas++;
  
}

/*OS ACOMPANHANTES DEVEM APARECER APENAS NO RELATÓRIO DO TIPO VIAGENS ($tipo == 1) */
function impAcompanhantes($oPdf, $oPaciente, $iTipo) {
  
  global $sSqlValor;
  global $oDaoCgsUnd;
  $sCampos    = $sSqlValor;
  $sCampos   .= "as ajudacusto, '+AC - '||z01_v_nome as paciente, z01_i_cgsund as cgs, tf01_i_codigo";
  $sWhere     = "tipo = 2 and tf01_i_codigo = $oPaciente->tf01_i_codigo";
  $sSql       = $oDaoCgsUnd->sql_query_cgs_beneficiadosajudacusto(null, $sCampos, null, $sWhere);
  $rsAcmp     = $oDaoCgsUnd->sql_record($sSql);  
  $nTotalAjud = 0;
  for ($iI = 0; $iI <$oDaoCgsUnd->numrows; $iI++) {
  
    $oAcompanhante = db_utils::fieldsmemory($rsAcmp, $iI);
    $oAcompanhante->tf03_c_descr       = '';
    $oAcompanhante->tf01_i_codigo      = '';
    $oAcompanhante->prestadora         = '';
    $oAcompanhante->tf17_d_datasaida   = '';
    $oAcompanhante->tf17_c_horasaida   = '';
    $oAcompanhante->tf17_c_localsaida  = '';
    $oAcompanhante->especialidade      = '';
    $oAcompanhante->identidade         = '';
    $oAcompanhante->telefone           = '';
    $oAcompanhante->cartaosus          = '';
    $oAcompanhante->nascimento         = '';
    $nTotalAjud                       += $oAcompanhante->ajudacusto;
    if ($iTipo == 0) { 
      impPaciente($oPdf, $oAcompanhante, $iTipo);
    }

  }
  if ($iTipo == 0) {
    return $iI; 
  } else {
    return $nTotalAjud; 
  }
  
}

function impRodape($oPdf, $oInfo, $iTipo) {
  
  $oPdf->setXY($oPdf->getX(), $oPdf->getY() + 7);
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  if ($iTipo != 0) {
  
    $oPdf->cell(40, 4, "Destino", 1, 0, "L", 1); 
    $oPdf->cell(20, 4, "Valor Gasto", 1, 1, "L", 1); 
    $oPdf->setfillcolor(255);
    $oPdf->setfont('arial', '', 8);
    $nTotal = 0;
    for ($iI = 0; $iI < count($oInfo->aCidades); $iI++) {  

      if ($oInfo->aCidades[$iI]->nValor > 0 || $iTipo != 1) {
      
        $oPdf->cell(40, 4, $oInfo->aCidades[$iI]->sNome, 1, 0, "L", 1);
        $oPdf->cell(20, 4, number_format($oInfo->aCidades[$iI]->nValor, 2, ',', '.'), 1, 1, "C", 1);
        $nTotal += $oInfo->aCidades[$iI]->nValor;
      
      }
      
    }
    $oPdf->setfillcolor(235);
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(40, 4, "Total:", 1, 0, "L", 1);
    $oPdf->cell(20, 4, number_format($nTotal, 2, ',', '.'), 1, 1, "C", 1);
    
  } else {
    
    $oPdf->cell(40, 4, "Total de Passageiros:", 1, 0, "L", 1); 
    $oPdf->cell(15, 4, $oInfo->iNroPassageiros, 1, 1, "C", 1); 
    
  }
  
}

$sSqlValor      = " (select sum(tf15_f_valoremitido) from tfd_ajudacustopedido inner join tfd_beneficiadosajudacusto ";
$sSqlValor     .= " on tf14_i_codigo =  tf15_i_ajudacustopedido where tf15_i_cgsund = z01_i_cgsund ";
$sSqlValor     .= " and tf14_i_pedidotfd = tf01_i_codigo)";

$sSqlCartaoSus  = "(select s115_c_cartaosus from cgs_cartaosus where s115_i_cgs = z01_i_cgsund ";
$sSqlCartaoSus .= "order by s115_c_tipo, s115_i_codigo LIMIT 1)";
$sSqlCartaoSus .= "as cartaosus";

$sCampos        = ' distinct on (tf01_i_codigo) tf01_i_codigo,  ';
$sCampos       .= ' z01_v_nome as paciente, z01_i_cgsund as cgs, z01_d_nasc as nascimento, ';
$sCampos       .= " (case when z01_v_telef <> '' and z01_v_telcel <> '' then  z01_v_telef||' / '||z01_v_telcel";
$sCampos       .= " when z01_v_telcel <> '' then z01_v_telcel when z01_v_telef <> '' then z01_v_telef else '' end)";
$sCampos       .= " as telefone, rh70_descr as especialidade, z01_v_ident as identidade, "; 
$sCampos       .= '  tf17_c_localsaida,  tf17_d_datasaida, tf17_c_horasaida,';
$sCampos       .= ' cgmprest.z01_nome as prestadora, tf03_c_descr, tf03_i_codigo, ';

$sCampos       .= $sSqlValor." as ajudacusto, ".$sSqlCartaoSus;

$sIntevalo      = formataData($dataInicial, 1)."' and '".formataData($dataFinal, 1);

$sWhere         = " tf16_i_pedidotfd is not null ";
$sWhere        .= " and tf17_i_pedidotfd is not null ";
$sWhere        .= " and tf17_d_datasaida between '$sIntevalo' ";

if ($destino != '') {
  $sWhere  .= " and tf03_i_codigo = $destino";
}

$sSql  = $oDaoTfdPedidoTfd->sql_query_pedido('', $sCampos, 'tf01_i_codigo, tf17_d_datasaida, paciente ', $sWhere);
$rs    = $oDaoTfdPedidoTfd->sql_record($sSql);
$iRows = $oDaoTfdPedidoTfd->numrows;

if ($tipo == 1 && $iRows > 0) {
  
  $sPedidos = '';
  for ($iI = 0; $iI < $oDaoTfdPedidoTfd->numrows; $iI++) {
    
    if ($iI != 0) {
      $sPedidos .= ',';
    }
    $oPaciente = db_utils::fieldsmemory($rs, $iI);
    $sPedidos .= $oPaciente->tf01_i_codigo;
     
  }
  $sSqlValorII  = " (select sum(tf15_f_valoremitido) from tfd_ajudacustopedido inner join tfd_beneficiadosajudacusto ";
  $sSqlValorII .= " on tf14_i_codigo =  tf15_i_ajudacustopedido where ";
  $sSqlValorII .= " tf14_i_pedidotfd = tf01_i_codigo)::real";
  $sSql         = $oDaoCgsUnd->sql_query_cgs_beneficiadosajudacusto(null, '*', null, 
                                                                    "tf01_i_codigo in ($sPedidos) and $sSqlValorII > 0"
                                                                   );
  $rsAjuda      = $oDaoCgsUnd->sql_record($sSql); 
  $iRows        = $oDaoCgsUnd->numrows;
  
}

if ($iRows == 0) {
  
  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado<br>
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
if ($tipo == 0) {
  
  $head1 = "Relatório Diário de Viagens";
  $head2 = "Período.: ".$dataInicial." a ".$dataFinal;
  $head3 = "Tipo....: VIAGENS";  
  $head4 = "Destino.: ".($destino != ''  ? $destino : 'GERAL');  

} else {

  $head1 = "Relatório de Saída";
  $head2 = "Período.: ".$dataInicial." a ".$dataFinal;
  $head3 = "Tipo....: TOTAIS";  
  $head4 = "Destino.: ".($destino != ''  ? $destino : 'GERAL');  

}
$iLinhas = 0;

novaPagina($oPdf);

if ($tipo == 0) {
  impTopo($oPdf);
}

impCabecalho($oPdf, $tipo); 

$iCount   = 0;
$aCidades = array();

for ($iI = 0; $iI < $oDaoTfdPedidoTfd->numrows; $iI++) {
  
  if ($iLinhas >= 65) {
    
    novaPagina($oPdf);
    impCabecalho($oPdf, $tipo); 
    
  }
  $oPaciente = db_utils::fieldsmemory($rs, $iI);
  if ($tipo == 0) {
  
    impPaciente($oPdf, $oPaciente, $tipo);
    $iCount += impAcompanhantes($oPdf, $oPaciente, $tipo);
    $iCount++;
    
  } else {
    
    $nValor = impAcompanhantes($oPdf, $oPaciente, $tipo);
    $lNovo  = true;
    for ($iW = 0; $iW < count($aCidades); $iW++) {

      if ($oPaciente->tf03_i_codigo == $aCidades[$iW]->iCod) {
      
        $aCidades[$iW]->nValor += $oPaciente->ajudacusto + $nValor; 
        $lNovo                  = false; 
        break;
      
      }
    
    }
    if ($lNovo) {
    
      $aCidades[$iW]->nValor = $oPaciente->ajudacusto + $nValor; 
      $aCidades[$iW]->iCod   = $oPaciente->tf03_i_codigo;
      $aCidades[$iW]->sNome  = $oPaciente->tf03_c_descr;
    
    }
    $oPaciente->ajudacusto += $nValor;
    impPaciente($oPdf, $oPaciente, $tipo);
  
  }
  
}
/*
 *==========================================================================
 * SE FOR TIPO VIAGEM OS TOTAIS DO RODAPÉ SÃO EM QUANTIDADE DE PASSAGEIROS
 * OU SE FOR DE OUTRO TIPO O TOTAL É BASEADO POR CIDADE NA AJUDA DE CUSTO
 *==========================================================================
 */
if ($tipo == 0) {
  $oInfo->iNroPassageiros = $iCount;
} else {
  $oInfo->aCidades = $aCidades;
}
impRodape($oPdf, $oInfo, $tipo); 
$oPdf->Output();
?>