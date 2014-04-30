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

require_once("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
$oDaoTransfEscolaRede = db_utils::getdao('transfescolarede');
$oDaoTransfEscolaFora = db_utils::getdao('transfescolafora');

if ($sTipo == "R") {

  $sCamposRede  = ' ed47_i_codigo, ed47_v_nome, escola.ed18_c_nome as escolaorigem, ';
  $sCamposRede .= ' censomunic.ed261_c_nome as cidadeorigem, censouf.ed260_c_sigla as estadoorigem,' ;
  $sCamposRede .= ' escoladestino.ed18_c_nome as escoladestino, censomunicdestino.ed261_c_nome as cidadedestino,' ;
  $sCamposRede .= ' censoufdestino.ed260_c_sigla as estadodestino, ed103_d_data as data,' ;
  $sCamposRede .= " 'TRANSFERÊNCIA REDE' as tipotransf" ;
  $sWhereRede   = " ed103_i_escolaorigem = $iEscola AND ed103_d_data BETWEEN '$dDataInicio' AND '$dDataFinal'";
  $sSql         = $oDaoTransfEscolaRede->sql_query("", $sCamposRede, "to_ascii(ed47_v_nome)", $sWhereRede);
  $rs           = $oDaoTransfEscolaRede->sql_record($sSql);
  $iLinhas      = $oDaoTransfEscolaRede->numrows;
  $head2        = "Tipo: TRANSFERÊNCIA REDE";
 
} else if ($sTipo == "F") {
  
  $sCamposFora  = ' ed47_i_codigo, ed47_v_nome, escola.ed18_c_nome as escolaorigem,  ';
  $sCamposFora .= ' censomunic.ed261_c_nome as cidadeorigem, censouf.ed260_c_sigla as estadoorigem,  ';
  $sCamposFora .= ' ed82_c_nome as escoladestino, ed82_i_censomunic as cidadedestino, ';
  $sCamposFora .= " ed82_i_censouf as estadodestino, ed104_d_data as data, 'TRANSFERÊNCIA FORA' as tipotransf";
  $sWhereFora   = " ed104_i_escolaorigem = $iEscola AND ed104_d_data BETWEEN '$dDataInicio' AND '$dDataFinal'";
  $sSql         = $oDaoTransfEscolaFora->sql_query("", $sCamposFora, "to_ascii(ed47_v_nome)", $sWhereFora);
  $rs           = $oDaoTransfEscolaFora->sql_record($sSql);
  $iLinhas      = $oDaoTransfEscolaFora->numrows;
  $head2        = "Tipo: TRANSFERÊNCIA FORA";  
  
} else {
	
  $sCamposTransfRede  = ' ed47_i_codigo, ed47_v_nome, escola.ed18_c_nome as escolaorigem, ';
  $sCamposTransfRede .= ' censomunic.ed261_c_nome as cidadeorigem, censouf.ed260_c_sigla as estadoorigem, ';
  $sCamposTransfRede .= ' escoladestino.ed18_c_nome as escoladestino, censomunicdestino.ed261_c_nome as cidadedestino, ';
  $sCamposTransfRede .= ' censoufdestino.ed260_c_sigla as estadodestino, ed103_d_data as data,';
  $sCamposTransfRede .= " 'TRANSFERÊNCIA REDE' as tipotransf ";
  $sWhereTransfRede   = ' ed103_i_escolaorigem = '.$iEscola ;
  $sWhereTransfRede  .= " AND ed103_d_data BETWEEN '$dDataInicio' AND '$dDataFinal' ";
  $sSqlTransfRede     = $oDaoTransfEscolaRede->sql_query_transferido("", $sCamposTransfRede, "",$sWhereTransfRede);
  
  $sCamposTransfFora  = ' ed47_i_codigo, ed47_v_nome, ed18_c_nome as escolaorigem, ';
  $sCamposTransfFora .= ' censomunic.ed261_c_nome as cidadeorigem,'; 
  $sCamposTransfFora .= ' censouf.ed260_c_sigla as estadoorigem, ed82_c_nome as escoladestino,';
  $sCamposTransfFora .= ' ss.ed261_c_nome as cidadedestino,';
  $sCamposTransfFora .= ' dd.ed260_c_sigla as estadodestino, ed104_d_data as data,';
  $sCamposTransfFora .= " 'TRANSFERÊNCIA FORA' as tipotransf ";
  $sWhereTransfFora   = ' ed104_i_escolaorigem ='. $iEscola ;
  $sWhereTransfFora  .= " AND ed104_d_data BETWEEN '$dDataInicio' AND '$dDataFinal' ";
  $sOrderTransfFora   = ' ed47_v_nome ';
  $sSqlTransfFora     = $oDaoTransfEscolaFora->sql_query_transferidofora("", $sCamposTransfFora, $sOrderTransfFora,
                                                                         $sWhereTransfFora
                                                                        );

  $sSqlUnion          = $sSqlTransfRede;
  $sSqlUnion         .= ' UNION ';
  $sSqlUnion         .= $sSqlTransfFora;

  $rs                 = $oDaoTransfEscolaRede->sql_record($sSqlUnion);
  $iLinhas            = $oDaoTransfEscolaRede->numrows;
  $head2              = "Tipo: TODAS";

}

if ($iLinhas == 0) {
	
  echo " <table width='100%'> ";
  echo "  <tr>";
  echo "   <td align='center'>";
  echo "    <font color='#FF0000' face='arial'>";
  echo "     <b>Nenhum registro encontrado.<br>";
  echo "     <input type='button' value='Fechar' onclick='window.close()'></b>";
  echo "    </font>";
  echo "   </td>";
  echo "  </tr>";
  echo " </table>"; 
  exit;
  
}

$head1  = "RELATÓRIO DE SAÍDAS POR TRANSFERÊNCIA";
$head3  = "Período: ".db_formatar($dDataInicio, 'd')." até ".db_formatar($dDataFinal, 'd');
$oPdf   = new Pdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$lTroca = true;
$lCor   = true;
for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
	
  $oDadosTipo = db_utils::fieldsmemory($rs, $iCont);
  
  if ($oPdf->gety() > $oPdf->h - 30 || $lTroca != 0 ) {
  	
    $oPdf->addpage('L');
    $oPdf->setfillcolor(215);
    $oPdf->setfont('arial', 'B', 9);
    $oPdf->cell(10, 5, "Código", 1, 0, "C", 1);
    $oPdf->cell(90, 5, "Aluno", 1, 0, "C", 1);
    $oPdf->cell(120, 5, "Escola Destino", 1, 0, "C", 1);
    $oPdf->cell(30, 5, "Tipo", 1, 0, "C", 1);
    $oPdf->cell(30, 5, "Data", 1, 1, "C", 1);
    $lTroca = false;
    
  }
  
  if ($lCor == false) {
    $lCor = true;
  } else {
    $lCor = false;
  }
  
  $oPdf->setfillcolor(230);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(10, 5, $oDadosTipo->ed47_i_codigo, 0, 0, "C", $lCor);
  $oPdf->cell(90, 5, $oDadosTipo->ed47_v_nome, 0, 0, "L", $lCor);
  $sCidadeDestino = $oDadosTipo->cidadedestino == "" ? "" : " / ".$oDadosTipo->cidadedestino;
  $sEstadoDestino = $oDadosTipo->estadodestino == "" ? "" : "-".$oDadosTipo->estadodestino;
  $oPdf->cell(120, 5, $oDadosTipo->escoladestino.$sCidadeDestino.$sEstadoDestino, 0, 0, "L", $lCor);
  $oPdf->cell(30, 5, $oDadosTipo->tipotransf, 0, 0, "L", $lCor);
  $oPdf->cell(30, 5, db_formatar($oDadosTipo->data, 'd'), 0, 1, "C", $lCor);
  
}
$oPdf->setfillcolor(215);
$oPdf->setfont('arial', 'B', 9);
$oPdf->cell(280, 5, "Quantidade no período de ".db_formatar($dDataInicio, 'd')." até ".db_formatar($dDataFinal, 'd').
            ": ".$iLinhas." saídas por transferência", 1, 1, "C", 1
           );
$oPdf->Output();
?>