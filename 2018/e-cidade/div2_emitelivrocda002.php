<?php
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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("std/db_stdClass.php");
include ("classes/db_divida_classe.php");
include ("classes/db_proced_classe.php");
include ("classes/db_arreinscr_classe.php");
include ("classes/db_arrematric_classe.php");
include ("classes/db_db_docparag_classe.php");
include ("classes/db_pardiv_classe.php");
include ("model/cdaLivro.model.php");
include ("libs/db_utils.php");
include ("libs/db_libdocumento.php");

$oGet = db_utils::postMemory($_GET);
try {
  $oLivroCda = new cdaLivro(db_getsession("DB_instit"), $oGet->livro);
} catch (Exception $eErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Livro {$oLivroCda->getNumeroLivro()} nao encontrado");
  exit;
}

if ($oLivroCda->getCodigoLivro() == null) {
  die('Livro nao encontrado!');
}
$oDocumento = new libdocumento(2031);
if ($oDocumento->lErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$oDocumento->sMsgErro}.");
  exit;
}

$oDocumento->getParagrafos();
$parag    = $oDocumento->aParagrafos;
$head1  = "Livro: ". $oLivroCda->getNumeroLivro(); 
$head2  = "Data : ". db_formatar($oLivroCda->getDataEmissao(), "d");
$alt = 5;

$pdf = new pdf("L");
$pdf->Open();
$pdf->SetAutoPageBreak(false,1);
$pdf->AliasNbPages();
$pdf->AddPage(); 
$pdf->SetFont('Arial', 'b', 8);
$pdf->Cell(280, $alt, "INSCRIÇÃO DA DÍVIDA ATIVA", 0, 1, "C", 0);
$pdf->Cell(280, $alt, "LIVRO ".$oLivroCda->getNumeroLivro(), 0, 1, "C", 0);
$pdf->Cell(280, $alt, "EXERCÍCIO ".db_getsession("DB_anousu"), 0, 1, "C", 0);
$pdf->Cell(280, $alt, "TERMO DE ABERTURA", 0, 1, "C", 0);

foreach ( $parag as $chave ) {

  if ($chave->db02_alinha == 1) {
    $alinhamento = "J";
  } elseif ($chave->db02_alinha == 2) {
    $alinhamento = "C";
  } elseif ($chave->db02_alinha == 3) {
    $alinhamento = "R";
  } elseif ($chave->db02_alinha == 4) {
    $alinhamento = "L";
  } else {
    $alinhamento = "J";
  }
  $pdf->MultiCell(280, 6, "        " . $oDocumento->geratexto($chave->db02_texto), 0, $alinhamento);
  $pdf->cell(280, $alt, "", 0, 1, "C", 0);
  
}
$aCdas = $oLivroCda->getCDA();
//echo "<pre>";
//print_r($aCdas);
//echo "</pre>";
//exit;
if (count($aCdas) == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Livro{$oLivroCda->getNumeroLivro()} nao possui CDAs.");
}

$nTotalCGM       = 0;
$nTotalMatric    = 0;
$nTotalInscr     = 0;
$nTotalDivida    = 0;
$nTotalParcel    = 0;
$nTotalHistorico = 0;
$nTotalCorrigido = 0;
$nTotalJuros     = 0;
$nTotalMulta     = 0;
$nTotalDesconto  = 0;
$nTotal          = 0;
$lCabecalho      = true;

$iNumLinhas = 0;
foreach ($aCdas as $oCda) {

  if ($iNumLinhas == cdaLivro::CDAPORPAGINA || $lCabecalho || $pdf->getY() > $pdf->h - 25) {
            
    $iNumLinhas = 0;
    $pdf->AddPage();
    $pdf->setfont('arial', 'BI', 7);
    $pdf->cell(15, $alt, "CDA", 0, 0, "R", 0);
    $pdf->cell(15, $alt, "Data", 0, 0, "C", 0);
    $pdf->cell(20, $alt, "Tipo", 0, 0, "L", 0);
    $pdf->cell(5,  $alt, "Folha", 0, 0, "R", 0);
    $pdf->cell(80, $alt, "Nome", 0, 0, "L", 0);
    $pdf->cell(24, $alt, "Origem", 0, 0, "L", 0);
    $pdf->cell(24, $alt, "Vlr. Histórico", 0, 0, "R", 0);
    $pdf->cell(20, $alt, "Vlr. Corrigido", 0, 0, "R", 0);
    $pdf->cell(15, $alt, "Juros", 0, 0, "R", 0);
    $pdf->cell(15, $alt, "Multa", 0, 0, "R", 0);
    $pdf->cell(20, $alt, "Desconto", 0, 0, "R", 0);
    $pdf->cell(20, $alt, "Total", 0, 1, "R", 0);
    $lCabecalho = false;
    
  }
  
  $pdf->setfont('arial', '', 7);
  $pdf->cell(15, $alt, $oCda->certidao, 0, 0, "R", 0);
  $pdf->cell(15, $alt, db_formatar($oCda->dtemissao,"d"), 0, 0, "C", 0);
  $pdf->cell(20, $alt, $oCda->origem == 1?'Dívida':'Parcelamento', 0, 0, "L", 0);
  $pdf->cell(5,  $alt, $oCda->numerofolha, 0, 0, "R", 0);
  $pdf->cell(80, $alt, $oCda->nome, 0, 0, "L", 0);
  $pdf->cell(24, $alt, "{$oCda->origemdebito}", 0, 0, "L", 0);
  $pdf->cell(24, $alt, db_formatar($oCda->vlrhis,"f"), 0, 0, "R", 0);
  $pdf->cell(20, $alt, db_formatar($oCda->vlrcor,"f"), 0, 0, "R", 0);
  $pdf->cell(15, $alt, db_formatar($oCda->vlrjur,"f"), 0, 0, "R", 0);
  $pdf->cell(15, $alt, db_formatar($oCda->vlrmul,"f"), 0, 0, "R", 0);
  $pdf->cell(20, $alt, "0,00", 0, 0, "R", 0);
  $pdf->cell(20, $alt, db_formatar($oCda->valortotal,"f"), 0, 1, "R", 0);
  if ($oGet->tipo == 1 && $oGet->imprimirorigem == 1) {
    
    $aItensCDa = $oLivroCda->detalhaCDA($oCda->certidao);
    $lMostraCabecalhoInt = true;
    
    if (count($aItensCDa) > 0) {

      foreach ($aItensCDa as $oItenCDA) {

        if ($iNumLinhas == cdaLivro::CDAPORPAGINA || $lMostraCabecalhoInt) {
          
          if ($iNumLinhas == cdaLivro::CDAPORPAGINA || $pdf->getY() > $pdf->h - 35) {
            
            $pdf->AddPage(); 
            $pdf->setfont('arial', 'BI', 7);
            $pdf->cell(15, $alt, "CDA", 0, 0, "R", 0);
            $pdf->cell(15, $alt, "Data", 0, 0, "C", 0);
            $pdf->cell(20, $alt, "Tipo", 0, 0, "L", 0);
            $pdf->cell(5,  $alt, "Folha", 0, 0, "R", 0);
            $pdf->cell(80, $alt, "Nome", 0, 0, "L", 0);
            $pdf->cell(24, $alt, "Origem", 0, 0, "L", 0);
            $pdf->cell(24, $alt, "Vlr. Histórico", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Vlr. Corrigido", 0, 0, "R", 0);
            $pdf->cell(15, $alt, "Juros", 0, 0, "R", 0);
            $pdf->cell(15, $alt, "Multa", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Desconto", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Total", 0, 1, "R", 0);
            $iNumLinhas = 0;
            
          }
          if ($oCda->origem == 1) {
            
            $pdf->setfont('arial', 'BI', 7);
            $pdf->cell(15, $alt, "Dívida", 0, 0, "C", 0);
            $pdf->cell(15, $alt, "Exercício", 0, 0, "R", 0);
            $pdf->cell(40, $alt, "Procedência", 0, 0, "L", 0);
            $pdf->cell(10, $alt, "Parcela", 0, 0, "C", 0);
            $pdf->cell(20, $alt, "Data Venc.", 0, 0, "C", 0);
            $pdf->cell(20, $alt, "Data Inscr.", 0, 0, "C", 0);
            $pdf->cell(24, $alt, "Vlr. Histórico", 0, 0, "R", 0);
            $pdf->cell(24, $alt, "Vlr. Corrigido", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Juros", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Multa", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Desconto", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Total", 0, 1, "R", 0);
            $lMostraCabecalhoInt = false;
               
          } else {
  
            $pdf->setfont('arial', 'BI', 7);
            $pdf->cell(15, $alt, "Parcel", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Data Parcel.", 0, 0, "C", 0);
            $pdf->cell(10, $alt, "Tot. Parc.", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Data Venc.", 0, 0, "C", 0);
            $pdf->cell(24, $alt, "Vlr. Histórico", 0, 0, "R", 0);
            $pdf->cell(24, $alt, "Vlr. Corrigido", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Juros", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Multa", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Desconto", 0, 0, "R", 0);
            $pdf->cell(20, $alt, "Total", 0, 1, "R", 0);
            $lMostraCabecalhoInt = false;
          }
          
          $iNumLinhas ++;
        }
        
        $pdf->setfont('arial', '', 7);
        $pdf->cell(15, $alt, $oItenCDA->codigo, 0, 0, "R", 0);
        if ($oCda->origem == 1) {
          
          $pdf->cell(15, $alt, $oItenCDA->v01_exerc, 0, 0, "R", 0);
          $pdf->cell(40, $alt, $oItenCDA->v03_descr, 0, 0, "L", 0);
          
        } else {
          $pdf->cell(20, $alt, db_formatar($oItenCDA->v07_dtlanc,"d"), 0, 0, "C", 0);
        }
        $pdf->cell(10,  $alt, $oItenCDA->parcela, 0, 0, "R", 0);
        if ($oCda->origem == 1) {
          $pdf->cell(20, $alt, db_formatar($oItenCDA->v01_dtinsc,"d"), 0, 0, "C", 0);  
        }
        
        $pdf->cell(20, $alt, db_formatar($oItenCDA->dtvenc,"d"), 0, 0, "C", 0);
        $pdf->cell(24, $alt, db_formatar($oItenCDA->vlrhis,"f"), 0, 0, "R", 0);
        $pdf->cell(24, $alt, db_formatar($oItenCDA->vlrcor,"f"), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($oItenCDA->vlrjur,"f"), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($oItenCDA->vlrmul,"f"), 0, 0, "R", 0);
        $pdf->cell(20, $alt, "0,00", 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($oItenCDA->valortotal,"f"), 0, 1, "R", 0);
        $iNumLinhas++;
        if ($oCda->origem == 1) {
         
          if (!isset($aProcedencias[$oItenCDA->v03_descr])) {
            
            $aProcedencias[$oItenCDA->v03_descr]->vlrhis   = $oItenCDA->vlrhis;
            $aProcedencias[$oItenCDA->v03_descr]->vlrcor   = $oItenCDA->vlrcor;
            $aProcedencias[$oItenCDA->v03_descr]->vlrjur   = $oItenCDA->vlrjur;
            $aProcedencias[$oItenCDA->v03_descr]->vlrmul   = $oItenCDA->vlrmul;
            $aProcedencias[$oItenCDA->v03_descr]->vlrtotal = $oItenCDA->valortotal;
            
          } else {
            
            $aProcedencias[$oItenCDA->v03_descr]->vlrhis   += $oItenCDA->vlrhis;
            $aProcedencias[$oItenCDA->v03_descr]->vlrcor   += $oItenCDA->vlrcor;
            $aProcedencias[$oItenCDA->v03_descr]->vlrjur   += $oItenCDA->vlrjur;
            $aProcedencias[$oItenCDA->v03_descr]->vlrmul   += $oItenCDA->vlrmul;
            $aProcedencias[$oItenCDA->v03_descr]->vlrtotal += $oItenCDA->valortotal;
            
          }
        }
      }
    }
  }
  
  /**
   * Calculamos os totalizadores;
   */
  switch ($oCda->tipoorigemdebito)  {
    
    case 1:
      $nTotalCGM++;
      break;

    case 2:

      $nTotalMatric++;
      break;
      
    case 3:

      $nTotalInscr++;
      break;
  }
  
  switch ($oCda->origem) {
  	
    case 1:
  	  $nTotalDivida ++;
  	  break;
  	
  	case 2:
  	  
      $nTotalParcel ++;
      break;
  }
  
  $iNumLinhas++;
  $nTotalHistorico += $oCda->vlrhis;
  $nTotalCorrigido += $oCda->vlrcor;
  $nTotalMulta     += $oCda->vlrmul;
  $nTotalJuros     += $oCda->vlrjur;
  $nTotal          += $oCda->valortotal;
  
}

$pdf->AddPage();
$pdf->setfont('arial', 'B', 7);
$pdf->cell(30,$alt,"TOTALIZADORES",0,1,"C");
$pdf->cell(30,$alt,"CGM'S:", 0,0);
$pdf->cell(30,$alt, "{$nTotalCGM}", 0,1,"R");
$pdf->cell(30,$alt,"MATRÍCULAS:", 0,0);
$pdf->cell(30,$alt, $nTotalMatric ,0,1,"R");
$pdf->cell(30,$alt,"INSCRIÇÕES:", 0,0);
$pdf->cell(30,$alt, $nTotalInscr,0,1,"R");
$pdf->cell(30,$alt,"CDA'S DÍVIDA:", 0,0);
$pdf->cell(30,$alt,$nTotalDivida,0,1,"R");
$pdf->cell(30,$alt,"CDA'S DE PARCELAMENTO:", 0,0);
$pdf->cell(30,$alt,$nTotalParcel,0,1,"R");
$pdf->cell(30,$alt,"CDA'S:", 0,0);
$pdf->cell(30,$alt,$nTotalParcel+$nTotalDivida,0,1,"R");
$pdf->cell(30,$alt,"HISTORICO:", 0,0);
$pdf->cell(30,$alt,db_formatar($nTotalHistorico ,"f"),0,1,"R");
$pdf->cell(30,$alt,"CORRIGIDO:", 0,0);
$pdf->cell(30,$alt,db_formatar($nTotalCorrigido,"f"),0,1,"R");
$pdf->cell(30,$alt,"JUROS:", 0,0);
$pdf->cell(30,$alt,db_formatar($nTotalJuros,"f"),0,1,"R");
$pdf->cell(30,$alt,"DESCONTOS:", 0,0);
$pdf->cell(30,$alt,db_formatar(0,"f"),0,1,"R");
$pdf->cell(30,$alt,"MULTA:", 0,0);
$pdf->cell(30,$alt,db_formatar($nTotalMulta,"f"),0,1,"R");
$pdf->cell(30,$alt,"TOTAL:", 0,0);
$pdf->cell(30,$alt,db_formatar($nTotal,"f"),0,1,"R");

if ($oGet->tipo == 1 && $oGet->imprimirorigem == 1) {
   
  $pdf->AddPage();
  $pdf->SetFillColor(240);
  $pdf->cell(70,$alt,"Procedência",1,0,"C",1); 
  $pdf->cell(30,$alt,"Valor Hist.",1,0,"C",1); 
  $pdf->cell(30,$alt,"Valor Corr.",1,0,"C",1); 
  $pdf->cell(30,$alt,"Juros",1,0,"C",1); 
  $pdf->cell(30,$alt,"Multa",1,0,"C",1); 
  $pdf->cell(30,$alt,"Total",1,1,"C",1);
  foreach ($aProcedencias as $Proced => $oValores) {
   
    $pdf->cell(70,$alt, $Proced,1,0,"L"); 
    $pdf->cell(30,$alt, db_formatar($oValores->vlrhis,"f"),1,0,"R"); 
    $pdf->cell(30,$alt, db_formatar($oValores->vlrcor,"f"),1,0,"R"); 
    $pdf->cell(30,$alt, db_formatar($oValores->vlrjur,"f"),1,0,"R"); 
    $pdf->cell(30,$alt, db_formatar($oValores->vlrmul,"f"),1,0,"R"); 
    $pdf->cell(30,$alt, db_formatar($oValores->vlrtotal,"f"),1,1,"R");
    
  }
}

$pdf->AddPage();
$oDocumento = new libdocumento(2031);
$oDocumento->getParagrafos();
$parag    = $oDocumento->aParagrafos;
$pdf->SetFont('Arial', 'b', 8);
$pdf->Cell(280, $alt, "INSCRIÇÃO DA DÍVIDA ATIVA", 0, 1, "C", 0);
$pdf->Cell(280, $alt, "LIVRO ".$oLivroCda->getNumeroLivro(), 0, 1, "C", 0);
$pdf->Cell(280, $alt, "EXERCÍCIO ".db_getsession("DB_anousu"), 0, 1, "C", 0);
$pdf->Cell(280, $alt, "TERMO DE ENCERRAMENTO", 0, 1, "C", 0);

foreach ( $parag as $chave ) {

  if ($chave->db02_alinha == 1) {
    $alinhamento = "J";
  } elseif ($chave->db02_alinha == 2) {
    $alinhamento = "C";
  } elseif ($chave->db02_alinha == 3) {
    $alinhamento = "R";
  } elseif ($chave->db02_alinha == 4) {
    $alinhamento = "L";
  } else {
    $alinhamento = "J";
  }
  $pdf->MultiCell(280, 6, "        " . $oDocumento->geratexto($chave->db02_texto), 0, $alinhamento);
  $pdf->cell(280, $alt, "", 0, 1, "C", 0);
  
}

$pdf->Output();