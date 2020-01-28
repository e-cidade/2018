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
require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oDaoFarMater = new cl_far_matersaude();

parse_str( $_SERVER['QUERY_STRING'] );

if( $sOrdem == 'n' ) {

  $sOrdem = 'fa01_i_codigo';
  $head5  = "ORDEM : NUMÉRICA";
} else {

	if ($sOrdem == 'a') {

		$sOrdem = 'm60_descr';
		$head5  = "ORDEM : ALFABÉTICA";
	} else {

		if($sOrdem == 'u'){

			$sOrdem = 'm61_descr';
			$head5  = "ORDEM : UNIDADE";
		}
	}
}

$head3 = "RELATORIO DE MEDICAMENTO CLASSIFICADOS ";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);

$pdf->setfont( 'arial', 'b', 8 );
$alt = 4;

if( $sQuebra == 'q' ) {
  $sOrdem = "fa05_c_class , " . $sOrdem;
}

$sCampos  = "fa01_i_codigo, m60_descr, m61_descr, m60_controlavalidade, fa05_c_descr, fa05_c_class, m64_pontopedido";// m64_pontopedido removido
$sSql     = $oDaoFarMater->sql_query_medicamento( "", $sCampos, $sOrdem, "fa01_i_class in ({$sMedicamentos})" );
$rsSql    = $oDaoFarMater->sql_record($sSql);
$iLinhas  = $oDaoFarMater->numrows;

if( $iLinhas == 0 ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}
	 	 
$troca      = 1;
$total      = 0;
$subtotal   = 0;
$iRegistros = 0;

$iColunaClassificacao = 270;
$iColunaCodigo        = 15;
$iColunaMedicamento   = 70;
$iColunaUnidade       = 70;
$iColunaClassif       = 40;
$iColunaValidade      = 55;
$iColunaPedido        = 30;

for( $x = 0; $x < $iLinhas; $x++ ) {

  $oResult = db_utils::fieldsmemory( $rsSql, $x );

  if( $sQuebra == 'q' ) {

    $troca   = 0;
    $c_class = substr( $oResult->fa05_c_class, 0, ( strlen( $oResult->fa05_c_class ) - 2 ) );
    $sSqlc   = "select fa05_c_descr from far_class where trim(fa05_c_class) = '{$c_class}"."00'";
    $resultc = db_query($sSqlc);

    if( pg_num_rows( $resultc ) > 0 ) {

      db_fieldsmemory( $resultc, 0 );
      $iRegistros++;

      $pdf->addpage("L");
      $pdf->setfont( 'arial', 'b', 8 );

      $pdf->cell( $iColunaClassificacao, $alt, "Classificação " . $oResult->fa05_c_descr, 0, 1, "L", 0 );
      $pdf->cell( $iColunaCodigo,        $alt, "Código",                                  0, 0, "L", 0 );
      $pdf->cell( $iColunaMedicamento,   $alt, "Medicamento",                             0, 0, "L", 0 );
      $pdf->cell( $iColunaUnidade,       $alt, "Unidade",                                 0, 0, "L", 0 );
      $pdf->cell( $iColunaClassif,       $alt, "Classif.",                                0, 0, "L", 0 );
      $pdf->cell( $iColunaValidade,      $alt, "Validade.",                               0, 0, "L", 0 );
      $pdf->cell( $iColunaPedido,        $alt, "Pont. Pedido",                            0, 1, "R", 0 );

      $posx = $pdf->getx();
      $posy = $pdf->gety();
      $pdf->line( $posx, $posy, $posx + 280, $posy );
      $pdf->setXY( $posx, $posy );
      $pdf->setfont( 'arial', '', 8 );

      while( $c_class == substr( $oResult->fa05_c_class, 0, ( strlen( $oResult->fa05_c_class ) - 2 ) ) ) {

        if( $pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

          $sSqlc   = "select fa05_c_descr from far_class where trim(fa05_c_class) = '{$c_class}"."00'";
          $resultc = db_query( $sSqlc );
          if( pg_num_rows( $resultc ) > 0 ) {

            db_fieldsmemory( $resultc, 0 );
            $iRegistros++;

            $pdf->addpage("L");
            $pdf->setfont( 'arial', 'b', 8 );

            $pdf->cell( $iColunaClassificacao, $alt, "Classificação " . $fa05_c_descr, 0, 1, "L", 0 );
            $pdf->cell( $iColunaCodigo,        $alt, "Código",                         0, 0, "L", 0 );
            $pdf->cell( $iColunaMedicamento,   $alt, "Medicamento",                    0, 0, "L", 0 );
            $pdf->cell( $iColunaUnidade,       $alt, "Unidade",                        0, 0, "L", 0 );
            $pdf->cell( $iColunaClassif,       $alt, "Classif.",                       0, 0, "L", 0 );
            $pdf->cell( $iColunaValidade,      $alt, "Validade.",                      0, 0, "L", 0 );
            $pdf->cell( $iColunaPedido,        $alt, "Pont. Pedido",                   0, 1, "R", 0 );

            $posx = $pdf->getx();
            $posy = $pdf->gety();
            $pdf->line( $posx,$posy, $posx + 280, $posy );
            $pdf->setXY( $posx, $posy );
            $pdf->setfont( 'arial', '', 8 );
            $troca = 0;
          }
        }

  	    $pdf->cell( $iColunaCodigo, $alt, $oResult->fa01_i_codigo, 0, 0, "L", 0 );
        $nome = substr( $oResult->m60_descr, 0, 20 );
  	    $pdf->cell( $iColunaMedicamento, $alt, $nome,                  0, 0, "L", 0 );
        $pdf->cell( $iColunaUnidade,     $alt, $oResult->m61_descr,    0, 0, "L", 0 );
        $pdf->cell( $iColunaClassif,     $alt, $oResult->fa05_c_class, 0, 0, "L", 0 );

  	    if( $oResult->m60_controlavalidade == 1 ) {
          $Validade = "Sim/Obrigatório";
        } else {

  	     if( $oResult->m60_controlavalidade == 2 ) {
	          $Validade = "Sim/Opcional";
          } else {
		       $Validade = "Não";
		     }
		    }

		    $pdf->cell( $iColunaValidade, $alt, $Validade,                 0, 0, "L", 0 );
		    $pdf->cell( 20,               $alt, $oResult->m64_pontopedido, 0, 1, "R", 0 );

        $subtotal ++;
        $x++;

        if( $x >= $iLinhas ) {
          break;
        }

        db_fieldsmemory( $result, $x );
		  }

      $x--;
      $pdf->setfont( 'arial', 'b', 8 );
      $pdf->cell( 280, $alt, "SUB-TOTAL DE REGISTRO :  {$subtotal}", 'T', 1, "L", 0 );
      $subtotal = 0;
    }
  } else {

    $iColunaClassif   = 35;
    $iColunaDescricao = 60;
    $iColunaUnidade   = 50;
    $iColunaPedido    = $iColunaPedido + 5;
		$iRegistros++;

	  if( $pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

		  $pdf->addpage( "L" );
	  	$pdf->setfont( 'arial', 'b', 8 );

		  $pdf->cell( $iColunaCodigo,      $alt, "Código",       0, 0, "L", 0 );
      $pdf->cell( $iColunaMedicamento, $alt, "Medicamento",  0, 0, "L", 0 );
      $pdf->cell( $iColunaUnidade,     $alt, "Unidade",      0, 0, "L", 0 );
      $pdf->cell( $iColunaClassif,     $alt, "Classif.",     0, 0, "L", 0 );
		  $pdf->cell( $iColunaDescricao,   $alt, "Descrição",    0, 0, "L", 0 );
		  $pdf->cell( $iColunaValidade,    $alt, "Validade.",    0, 0, "L", 0 );
		  $pdf->cell( $iColunaPedido,      $alt, "Pont. Pedido", 0, 1, "R", 0 );

		  $posx = $pdf->getx();
      $posy = $pdf->gety();

      $pdf->line( $posx, $posy, $posx + 278, $posy );
      $pdf->setXY( $posx, $posy );
      $pdf->setfont( 'arial', '', 8 );
		  $troca = 0;
    }

		$pdf->cell( $iColunaCodigo, $alt, $oResult->fa01_i_codigo, 0, 0, "L", 0 );
    $nome = substr( $oResult->m60_descr, 0, 20 );
		$pdf->cell( $iColunaMedicamento, $alt, $nome,                  0, 0, "L", 0 );
    $pdf->cell( $iColunaUnidade,     $alt, $oResult->m61_descr,    0, 0, "L", 0 );
    $pdf->cell( $iColunaClassif,     $alt, $oResult->fa05_c_class, 0, 0, "L", 0 );
		$pdf->cell( $iColunaDescricao,   $alt, $oResult->fa05_c_descr, 0, 0, "L", 0 );

		if( $oResult->m60_controlavalidade == 1 ) {
		  $Validade = "Sim/Obrigatório";
		} else {

		  if( $oResult->m60_controlavalidade == 2 ) {
		    $Validade = "Sim/Opcional";
		  } else {
		    $Validade = "Não";
		  }
		}

		$pdf->cell( $iColunaValidade, $alt, $Validade,                 0, 0, "L", 0 );
		$pdf->cell( 35,               $alt, $oResult->m64_pontopedido, 0, 1, "R", 0 );
  }

  $total++;
}

$pdf->setfont( 'arial', 'b', 8 );

if( $sQuebra == 'q' ) {
  $pdf->cell( 280, $alt, "TOTAL DE REGISTROS  :  {$total}", 0, 1, "L", 0 );
} else {
  $pdf->cell( 280, $alt, "TOTAL DE REGISTROS  :  {$total}", 'T', 1, "L", 0 );
}

if( $iRegistros == 0 ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

$pdf->output();