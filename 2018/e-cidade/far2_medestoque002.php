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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oDaoFarMater                  = db_utils::getdao('far_matersaude');
$oLarguraColunas               = new stdClass();
$oLarguraColunas->iCodigo      = 20;
$oLarguraColunas->iMedicamento = 130;
$oLarguraColunas->iUnidade     = 25;
$oLarguraColunas->iDeposito    = 16;
$oLarguraColunas->iDescricao   = 40;
$oLarguraColunas->iQuantidade  = 20;
$oLarguraColunas->iValor       = 25;
$oLarguraColunas->iTotal       = $oLarguraColunas->iCodigo + $oLarguraColunas->iMedicamento;
$oLarguraColunas->iTotal      += $oLarguraColunas->iUnidade + $oLarguraColunas->iDeposito;
$oLarguraColunas->iTotal      += $oLarguraColunas->iDescricao + $oLarguraColunas->iQuantidade;
$oLarguraColunas->iTotal      += $oLarguraColunas->iValor;

function imprimirCabecalho($oPdf, $oLarguraColunas) {

  $oPdf->setfont('arial', 'b', 8);   
  $oPdf->setfillcolor(235);
  $oPdf->cell($oLarguraColunas->iCodigo, 4, "Codigo", 1, 0, "L", 1);
  $oPdf->cell($oLarguraColunas->iMedicamento, 4, "Medicamento", 1, 0,"L", 1);
  $oPdf->cell($oLarguraColunas->iUnidade, 4, "Unidade", 1, 0, "L", 1);       
  $oPdf->cell($oLarguraColunas->iDeposito, 4, "Depósito", 1, 0, "L", 1);
  $oPdf->cell($oLarguraColunas->iDescricao, 4, "Descrição", 1, 0, "L", 1);		  
  $oPdf->cell($oLarguraColunas->iQuantidade, 4,"Quant. Estoq.", 1, 0, "L", 1);
  $oPdf->cell($oLarguraColunas->iValor, 4, "Valor Estoque", 1, 1, "L",  1);
  $oPdf->setfont('arial', '', 8); 
  
}

function imprimirMedicamento($oPdf, $oLarguraColunas, $oMedicamento) {
	
  $oPdf->cell($oLarguraColunas->iCodigo, 4, $oMedicamento->icodigo, 1, 0, "L", 0);
  $oPdf->cell($oLarguraColunas->iMedicamento, 4, $oMedicamento->sdescricao, 1, 0, "L", 0);
  $oPdf->cell($oLarguraColunas->iUnidade, 4, $oMedicamento->sunidade, 1, 0, "L", 0);
  $oPdf->cell($oLarguraColunas->iDeposito, 4, $oMedicamento->icodigodeposito, 1, 0, "L", 0);
  $oPdf->cell($oLarguraColunas->iDescricao, 4, substr($oMedicamento->sdecricaodeposito, 0, 21), 1, 0, "L", 0);
  $oPdf->cell($oLarguraColunas->iQuantidade, 4, $oMedicamento->iquantidade, 1, 0, "R", 0);
  $oPdf->cell($oLarguraColunas->iValor, 4, db_formatar($oMedicamento->nvalor,"f"), 1, 1, "R", 0);  
	
}

function imprimirTotalDeposito($oPdf, $oLarguraColunas, $nTotal) {
	
  $oPdf->setfont('arial', 'b', 9);
  $oPdf->cell($oLarguraColunas->iTotal, 5, str_pad("Valor total para o depósito:  ".db_formatar($nTotal,"f"), 299, " ", STR_PAD_LEFT), 1, 1, "R", 1);
  $oPdf->setfont('arial', '', 8); 
	  
}

function imprimirTotal($oPdf, $oLarguraColunas, $nTotal) {
	
  $oPdf->setfont('arial', 'b', 9);
  $oPdf->cell($oLarguraColunas->iTotal, 5, str_pad("Valor total:  ".db_formatar($nTotal,"f"), 299, " ", STR_PAD_LEFT), 1, 1, "R", 1);
	
}

function novaPagina($oPdf, $oLarguraColunas) {
	
  $oPdf->Addpage("L"); 
  imprimirCabecalho($oPdf, $oLarguraColunas);

}

function erro($sDescricao) {
  
  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b><?echo $sDescricao;?><br>
          <input type='button' value='Fechar' onclick='window.close()'></b>
        </font>
      </td>
    </tr>
 </table>
 <?
 
}
$sWhereDepositos = "";
$sSeparador      = "";
if ($depositos != "") {  

  $sWhereDepositos = " m70_coddepto in (".str_replace("|", ", ", $depositos).") "; 
  $sSeparador      = " and ";

}
$sWhereEstoque = "";
if ($estoqueZerado != 'S') {
	
  $sWhereEstoque = $sSeparador." m70_quant > 0 "; 

}

$sOrder = "";
if ($ordem == "A") {
	
  $sOrder = " coddepto, m60_descr ";
  $head5  = "Ordem : Alfabética";

} else {
	
  $sOrder =" coddepto, fa01_i_codigo ";
  $head5 = "Ordem : Numérica";

}
$sWhere   = $sWhereDepositos.$sWhereEstoque;
if ($sWhere == "") {
  $sWhere = " 1 = 1 ";
}
$sGroup   = " GROUP BY fa01_i_codigo, m60_descr, m61_descr, coddepto, descrdepto, m70_quant, m70_valor ";
$sCampos  = " m70_quant as iQuantidade, m70_valor as nValor , coddepto as iCodigoDeposito, ";
$sCampos .= " descrdepto as sDecricaoDeposito, fa01_i_codigo as iCodigo, m60_descr as sDescricao, m61_descr as sUnidade"; 
$sWhere  .= $sGroup;
$sSql     = $oDaoFarMater->sql_query_estoque("", $sCampos, $sOrder, $sWhere);
$rsSql    = $oDaoFarMater->sql_record($sSql);
if ($oDaoFarMater->numrows == 0) {

 erro("Nenhum registro encontrado.");
 exit;
 
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = "Relatorio de Quantidade de Medicamentos em Estoque";
$head3 = "Com Departamento ";
$head4 = "Quebra: Nenhuma";
$head6 = "Estoque zerado: Não ";
if ($estoqueZerado == 'S') {
  $head6 = "Estoque zerado: Sim ";
}  
if ($quebra=="D") {
  $head4 = "Quebra: Depósito";
}

$oPdf->setfillcolor(223); 
$iNumRegPagina  = 0;
$iCodDeposito   = 0;
$nTotalDeposito = 0;
$nTotal         = 0;
for ($iI = 0; $iI < $oDaoFarMater->numrows; $iI++) {
	
  $oMedicamento = db_utils::fieldsmemory($rsSql, $iI);
  if ($iCodDeposito != $oMedicamento->icodigodeposito && $iCodDeposito != 0) {
  	
  	imprimirTotalDeposito($oPdf, $oLarguraColunas, $nTotalDeposito);
  	$nTotal        += $nTotalDeposito;
  	$nTotalDeposito = 0;
  	$iCodDeposito   = $oMedicamento->icodigodeposito;
    if ($quebra == 'D') {
    	
      novaPagina($oPdf, $oLarguraColunas);
  	  $iNumRegPagina = 0;
    	
    } else {    	
      $iNumRegPagina++;
    }
  	
  } elseif ($iNumRegPagina >= 35) {
  	
    novaPagina($oPdf, $oLarguraColunas);
  	$iNumRegPagina = 0;
  	
  } elseif ($iCodDeposito == 0) {
  	
    $iCodDeposito = $oMedicamento->icodigodeposito;
    novaPagina($oPdf, $oLarguraColunas);
  	$iNumRegPagina = 0;
    
  } 
  $nTotalDeposito += $oMedicamento->nvalor;
  imprimirMedicamento($oPdf, $oLarguraColunas, $oMedicamento);
  $iNumRegPagina++;
  
}
imprimirTotalDeposito($oPdf, $oLarguraColunas, $nTotalDeposito);
$nTotal += $nTotalDeposito;
imprimirTotal($oPdf, $oLarguraColunas, $nTotal);
$oPdf->Output();
?>