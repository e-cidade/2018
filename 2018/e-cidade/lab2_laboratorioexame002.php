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

include("fpdf151/pdf.php");
include("classes/db_lab_laboratorio_classe.php");

$cllab_laboratorio = new cl_lab_laboratorio;
$sCampos     =  " la02_i_codigo, ";
$sCampos    .= "la02_c_descr ";
$sWhere      =  "";
$sOrder      =  "la02_i_codigo";
if (isset($imprimirExames) && $imprimirExames) {
  
  $sCampos .= " ,la08_i_codigo,";
  $sCampos .= " la08_c_sigla,";
  $sCampos .= " la08_c_descr,";
  $sCampos .= " la08_i_ativo,";
  $sCampos .= " la09_i_ativo";
  $sOrder  .= "#la08_i_ativo#la09_i_ativo#la08_c_descr";
  
} else {
  $sCampos = " distinct " . $sCampos;	
}
if (isset($situacaoExames)) {
    
  if($situacaoExames == 1) {
    $sWhere = "la08_i_ativo = 1 AND la09_i_ativo = 1";
  } else if($situacaoExames == 2) {
    $sWhere = "la08_i_ativo = 2 OR la09_i_ativo = 2";
  }
  
} 

$result  =  $cllab_laboratorio->sql_record($cllab_laboratorio->sql_query_labexames(null, $sCampos, $sOrder, $sWhere));
if ($cllab_laboratorio->numrows == 0) {
  
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
$ex = 0;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "Relatório de Laboratórios";
$head3 = "IMPRIMIR EXAMES: ";
$head4 = "SITUAÇÃO EXAMES: ";
if (isset($imprimirExames) && $imprimirExames == "1") {
  $head3 .= ($imprimirExames == 1) ? "SIM" : "NÃO";
} else {
  $head3 .= "NÃO";
}
if(isset($situacaoExames)) {
  
  if ($situacaoExames == 1) {
    $head4 .= "ATIVOS";	
  } else if( $situacaoExames == 2) {
  	$head4 .= "DESATIVADOS";
  } else {
  	$head4 .= "TODOS";
  }    	

}

$iCont     = 38;
$iTotal    = 0;
$iTotallab = 0;
$iCodlab   = -1;
for ($s = 0; $s < $cllab_laboratorio->numrows; $s++) {
  
  db_fieldsmemory($result, $s);
  if ($iCont >= 38 
      || (isset($imprimirExames) && $imprimirExames == 0)
      || $la02_i_codigo != $iCodlab     
     ) {
     	
    if ($la02_i_codigo != $iCodlab && $iCodlab != -1 && isset($imprimirExames) && $imprimirExames == 1) {
      
      $pdf->setfont('arial', 'B', 8);
      $pdf->Cell(280, 4, "TOTAL: ".$iTotallab,  1, 1, "R", 0); 	
      $iTotallab = 0;
      $iCont++;
    
    }
    if ($iCont >= 38) {
  	  
      $pdf->ln(5);
      $pdf->addpage('L');
      $iCont = 0;
      
    }
    if($la02_i_codigo != $iCodlab) {
  	  
      $pdf->setfont('arial','b',10);
  	  $pdf->SetFillColor(235);
      $sLab    = $la02_i_codigo." - ".$la02_c_descr;
      $iCodlab = $la02_i_codigo;	
      $pdf->cell(280, 4, $sLab, 1, 1, "L", 1);
      $pdf->SetFillColor(255);
      if (isset($imprimirExames) && $imprimirExames == 1) {
      
        $pdf->cell(15,  4, "Código", 1, 0, "L", 0);
        $pdf->cell(20,  4, "Sigla",  1, 0, "L", 0);
        $pdf->Cell(225, 4, "Exame",  1, 0, "L", 0);	 	
        $pdf->cell(20,  4, "Situação",  1, 1, "L", 0); 
        $iCont++;
        
      }
      $iCont++;
    
    }
    
  }
  if (isset($imprimirExames) && $imprimirExames == 1) {
   
    $pdf->setfont('arial', '', 8);
    $pdf->cell(15,  4, $la08_i_codigo, 1, 0, "L", 0);
    $pdf->cell(20,  4, $la08_c_sigla,  1, 0, "L", 0);
    $pdf->Cell(225, 4, $la08_c_descr,  1, 0, "L", 0);
    if ($la08_i_ativo == 1 && $la09_i_ativo == 1) {	 	
      $pdf->cell(20,  4, "ATIVO",  1, 1, "L", 0); 
    } else {
      $pdf->cell(20,  4, "DESATIVADO",  1, 1, "L", 0);	
    }
    $iTotal++;
    $iTotallab++;
    $iCont++;
    if ($s + 1 == $cllab_laboratorio->numrows) {
     
      $pdf->setfont('arial', 'B', 8);
      $pdf->Cell(280, 4, "TOTAL: ".$iTotallab,  1, 1, "R", 0);   
      $pdf->setfont('arial', 'B', 10);
      $pdf->Cell(280, 4, "TOTAL: ".$iTotal,  1, 1, "L", 0); 

    }
  
  }

}
$pdf->Output();
?>