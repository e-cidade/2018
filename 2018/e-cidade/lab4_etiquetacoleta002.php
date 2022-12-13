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

include("fpdf151/scpdf.php");
include("classes/db_lab_requiitem_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cllab_requiitem = new cl_lab_requisicao;
$sCampos         = "la22_i_cgs, la22_i_codigo, la08_i_codigo, la08_c_descr, z01_v_nome, la23_c_descr, la21_i_codigo";
$sWhere          = " la21_i_codigo in ($sLista)" ;
$sSql            = $cllab_requiitem->sql_query_coleta_amostra("", $sCampos ,"", $sWhere);
$result          = $cllab_requiitem->sql_record( $sSql );
$linhas          = $cllab_requiitem->numrows;

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(243);
$pdf->addpage('P');
$pdf->ln(8);

//coordenadas iniciais
$recty       = 5;//retangulo
$rectx       = 10;
$numx        = 29;//numero codigo de barras
$numy        = 25;
$codx        = 21;//codigo de barras
$cody        = 10;
$pacx        = 21;//nome do paciente
$pacy        = 28;
$exax        = 21;//nome do exame
$exay        = 32;
$setorx      = 23;//nome do setor
$setory      = 8;
$datax       = 40;//data e hora
$datay       = 8;
$largura_ret = 64;//largura do retângulo
$altura_ret  = 30;//altura do retângulo
$colunas     = 0;
$cont        = 0;

for ( $i = 0; $i < $linhas; $i++ ) {
	
  db_fieldsmemory($result,$i);
  $cont++;

  $pdf->rect($rectx,$recty,$largura_ret,$altura_ret,'D');//retangulo
  $pdf->setfont('arial','',9);

  $la22_i_codigo = str_pad($la22_i_codigo,6,0, STR_PAD_LEFT);
  $la08_i_codigo = str_pad($la08_i_codigo,6,0, STR_PAD_LEFT);
  $codigo        = $la22_i_codigo.''.$la08_i_codigo;

  $oDaoColetaItem = new cl_lab_coletaitem();
  $sCampos        = "la32_d_data, la32_c_hora";
  $sWhere         = " la32_i_requiitem = {$la21_i_codigo}";
  $sSqlColetaItem = $oDaoColetaItem->sql_query_file ( '', $sCampos, '', $sWhere );
  $rsColetaItem   = $oDaoColetaItem->sql_record( $sSqlColetaItem );

  if ($oDaoColetaItem->numrows > 0) {
    db_fieldsmemory( $rsColetaItem, 0);
  }

  $pdf->text($numx,$numy,$codigo);//numero codigo de barras
  $pdf->setfont('arial','',7);
  $pdf->SetFillColor(000);//fundo codbarras
  $pdf->text($setorx,$setory,substr($la23_c_descr,0,20));//nome setor
  if ( isset( $la32_d_data ) && isset ( $la32_c_hora ) ) {
    $pdf->text($datax,$datay,db_formatar($la32_d_data,'d')."   ".$la32_c_hora);// data e hora da coletaitem
  }
  $pdf->int25($codx,$cody,$codigo,12,0.341);//codbarras
  $pdf->text($pacx,$pacy,$la22_i_cgs." - ".substr($z01_v_nome,0,25));//nome paciente
  $pdf->text($exax,$exay,$la08_i_codigo." - ".substr($la08_c_descr,0,25));//nome exame

  $rectx  += $largura_ret + 1;
  $pacx   += $largura_ret + 1;
  $exax   += $largura_ret + 1;
  $setorx += $largura_ret + 1;
  $datax  += $largura_ret + 1;
  $numx   += $largura_ret + 1;
  $codx   += $largura_ret + 1;

  $colunas++;
 
  if ( $colunas == 3 ) {
  	
   $recty  += $altura_ret + 1;
   $rectx  -= ( $largura_ret + 1 ) * 3;
   $pacy   += $altura_ret + 1;
   $pacx   -= ( $largura_ret + 1 ) * 3;
   $exay   += $altura_ret + 1;
   $exax   -= ( $largura_ret + 1 ) * 3;
   $setory += $altura_ret + 1;
   $setorx -= ( $largura_ret + 1 ) * 3;
   $datay  += $altura_ret + 1;
   $datax  -= ( $largura_ret + 1 ) * 3;
   $numy   += $altura_ret + 1;
   $numx   -= ( $largura_ret + 1 ) * 3;
   $cody   += $altura_ret + 1;
   $codx   -= ( $largura_ret +1 ) * 3;
   $colunas = 0;
  }

  if ( $cont == 30 ) {
  	
   $pdf->addpage('P');
   $pdf->ln(8);

   $recty       = 5;//retangulo
   $rectx       = 10;
   $numx        = 29;//numero codigo de barras
   $numy        = 25;
   $codx        = 21;//codigo de barras
   $cody        = 10;
   $pacx        = 21;//nome do paciente
   $pacy        = 28;
   $exax        = 21;//nome do exame
   $exay        = 32;
   $setorx      = 23;//nome do setor
   $setory      = 8;
   $datax       = 40;//data e hora
   $datay       = 8;
   $largura_ret = 64;//largura do retângulo
   $altura_ret  = 30;//altura do retângulo
   $colunas     = 0;
   $cont        = 0;
  }
}
$pdf->Output();
?>