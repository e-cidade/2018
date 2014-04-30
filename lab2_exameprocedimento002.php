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
include("classes/db_lab_exame_classe.php");
$cllab_exame = new cl_lab_exame;
$sOrder = "";
if (isset($tipo) && $tipo == 1) {
  $sOrder = " la08_c_descr ";
} else {
  $sOrder = " sd63_c_nome ";
}
$sCampos =  " la08_i_codigo, ";
$sCampos .= "la08_c_sigla, ";
$sCampos .= "la08_c_descr, ";
$sCampos .= "sd63_c_procedimento, ";
$sCampos .= "sd63_c_nome, ";
$sCampos .= "(sd63_f_sp + sd63_f_sa + sd63_f_sh) as valor_sus, ";
$sCampos .= "la53_n_acrescimo ";
$sWhere  =  " la53_i_ativo = 1 ";
$result  =  $cllab_exame->sql_record($cllab_exame->sql_query_procedimento(null, $sCampos, $sOrder, $sWhere));
if ($cllab_exame->numrows == 0) {

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

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELATÓRIO GERAL DE EXAMES";
if (isset($tipo)) {
  $head3  = "ORDEM: ";
  $head3 .= ($tipo == 1) ? "EXAMES" : "PROCEDIMENTOS";
} else {
  $head2 = "ORDEM: ";
}
$iCont = 0;
$pdf->ln(5);
$pdf->addpage('L');
$pdf->setfont('arial','b',10);
$pdf->cell(15,4,"Código ",1,0,"L",0);
$pdf->cell(15,4,"Sigla ",1,0,"L",0);
$pdf->cell(50,4,"Exame ",1,0,"L",0);
$pdf->cell(50,4,"Procedimento ",1,0,"L",0);
$pdf->cell(85,4,"Descrição ",1,0,"L",0);
$pdf->cell(20,4,"Valor SUS ",1,0,"L",0);
$pdf->cell(20,4,"Valor SMS ",1,0,"L",0);
$pdf->cell(25,4,"Valor Total ",1,1,"L",0);

/* Setando as variaveis do Row_multicell */
$iAltura        = 4;
$bBorda         = true;
$iEspaco        = 4; // = altura da linha
$bPreenchimento = false;
$bNaoUsarEspaco = true; //variavel espaco obs: se não usar da problema!!!
$bUsarQuebra    = true;
$iCampoTestar   = null;
$iLaguraFixa    = 0; //false

/* Setando os parametros fixos de Largura e alinhamento das celulas */
$pdf->SetWidths(array(15, 15, 50, 50, 85, 20, 20, 25));
$pdf->SetAligns(array("L","L","L", "L", "L", "R", "R", "R"));

for($s=0; $s < $cllab_exame->numrows; $s++) {
  db_fieldsmemory($result,$s);
  if ($iCont >= 35) {
    
    $pdf->ln(5);
    $pdf->addpage('L');
	$pdf->setfont('arial','b',10);
    $pdf->cell(15,4,"Código ",1,0,"L",0);
    $pdf->cell(15,4,"Sigla ",1,0,"L",0);
    $pdf->cell(50,4,"Exame ",1,0,"L",0);
    $pdf->cell(50,4,"Procedimento ",1,0,"L",0);
    $pdf->cell(85,4,"Descrição ",1,0,"L",0);
    $pdf->cell(20,4,"Valor SUS ",1,0,"L",0);
    $pdf->cell(20,4,"Valor SMS ",1,0,"L",0);
    $pdf->cell(25,4,"Valor Total ",1,1,"L",0);
	$iCont = 0;
	
  }
  $pdf->setfont('arial', '', 8);
  $iAlturaRow = $pdf->h - 30;
  $aData = array($la08_i_codigo,
                $la08_c_sigla,
                $la08_c_descr,
                $sd63_c_procedimento,
                $sd63_c_nome,
                number_format($valor_sus, 2, ',', '.'),
                number_format($la53_n_acrescimo, 2, ',', '.'),
                number_format($valor_sus + $la53_n_acrescimo, 2, ',', '.')
               );
  /* 
  * ====================================================================
  *   Setando a altura do retângulo que corresponde a borda da celula.
  *   $iLines  = Quantidade de linhas ocupadas na celula do pdf.
  *   $iHeight = Valor da altura total da maior celula da linha.
  *   Função que retorna a quantidade de linhas ocupadas na célular.
  *   $pdf->NbLines( array(), array()) 
  * ====================================================================
  */ 
             
  $iLines = 0;
  for ($i = 0; $i < count($aData) && $iCont <= 35; $i++) {
    
  	if ($iLines <  $pdf->NbLines($pdf->widths[$i], $aData[$i])) {
      $iLines =   $pdf->NbLines($pdf->widths[$i], $aData[$i]);
    }
    
  }
  $iHeight = $iLines * $iEspaco;
  $descricaoitemimprime = $pdf->Row_multicell($aData,
                                              $iAltura,
                                              $bBorda,
                                              $iHeight,
                                              $bPreenchimento,
                                              $bNaoUsarEspaco,
                                              $bUsarQuebra,
                                              $iCampoTestar,
                                              $iAlturaRow,
                                              $iLaguraFixa
                                             );
   
  $iCont += $iLines;           
}

$pdf->Output();
?>