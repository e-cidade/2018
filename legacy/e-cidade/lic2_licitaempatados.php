<?php
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_liclicita_classe.php");
include("classes/db_pcorcamdescla_classe.php");
include("classes/db_pcorcamtroca_classe.php");
include("classes/db_liclicitemanu_classe.php");
include("libs/db_utils.php");

$clpcorcam       = new cl_pcorcam;
$clpcorcamforne  = new cl_pcorcamforne;
$clpcorcamitem   = new cl_pcorcamitem;
$clpcorcamval    = new cl_pcorcamval;
$clliclicita     = new cl_liclicita;
$oGet            = db_utils::postMemory($_GET);


$rsLicita = $clpcorcamitem->sql_record(
            $clpcorcamitem->sql_query_pcmaterlic(null,
                                                 "distinct l21_codliclicita,
                                                  pc22_codorc,
                                                  l20_tipojulg,
                                                  l20_numero,
                                                  l20_datacria",
                                                  null,
                                                  "l21_codliclicita={$oGet->l20_codigo}
                                                   and l20_instit = ".db_getsession("DB_instit")));
                                                   
if ($clpcorcamitem->numrows > 0) {
  $oLicita = db_utils::fieldsMemory($rsLicita,0);
} else {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');
}
$head1      = "Itens empatados/MicroEmpresas";
$head3      = "Orçamento: ".$oLicita->pc22_codorc;
$head5      = "Licitacao: {$oLicita->l20_numero}/".substr($oLicita->l20_datacria,0,4);
$iOrcamento = @$oLicita->pc22_codorc;

/*
 * Consultamos os fornecedores da licitação.
 */
$rsFornecedores = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_forneclic(null,"*",null,"pc21_codorc={$iOrcamento}"));
$iNumRowsFornec = $clpcorcamforne->numrows;
if ($iNumRowsFornec == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Fornecedores cadastrados.');
}

$rsItensCabec = $clpcorcamitem->sql_record(
                $clpcorcamitem->sql_query_pcmaterlic(null,
                                                      "distinct l21_ordem,
                                                      pc22_orcamitem,
                                                      pc01_descrmater",
                                                      null,
                                                      "pc22_codorc={$iOrcamento}"));
$iNumRowsItemFornec = $clpcorcamitem->numrows;
/**
 * Percorremos o valor de cada item orçado pelo fornecedor
 * e verificamos quais deles estaocom o mesmo valor
 */
$aItensIguais = array();
for ($iItens = 0; $iItens < $iNumRowsItemFornec; $iItens++) {
  
  $oItem         = db_utils::fieldsMemory($rsItensCabec, $iItens);  
  $sSqlValorItem = $clpcorcamval->sql_query_fornec(null,
	                                             null,
	                                              "pc23_valor,
	                                              z01_numcgm,
	                                              pc23_orcamitem,
	                                              (select min(pc23_valor)
	                                                 from pcorcamval
	                                                where pc23_orcamitem = {$oItem->pc22_orcamitem}
	                                               ) as vlrmin,
	                                               l32_sequencial,
	                                               l32_percentual ",
	                                                
	                                              null,
	                                              " pc23_orcamitem = {$oItem->pc22_orcamitem}"
	                                              );
  $rsValoresIguais =  $clpcorcamval->sql_record($sSqlValorItem);
  $iTotVal         = $clpcorcamval->numrows; 
  
  for ($iVal = 0; $iVal < $iTotVal; $iVal++) {

    $iItemAtual     = $iVal;
    $oValor         = db_utils::fieldsMemory($rsValoresIguais, $iVal);
    $nValorItem     = $oValor->pc23_valor;
    $nValorDeduzido = 0;
    if ($oValor->l32_sequencial != 1 && $oValor->pc23_valor > $oValor->vlrmin) {
          
      if ($oValor->l32_percentual != 0) {
          $nValorDeduzido = round($oValor->pc23_valor - ($oValor->vlrmin *($oValor->l32_percentual/100)));
       }
    }
    if (($nValorItem == $oValor->vlrmin) || ($oValor->l32_sequencial != 1 && ($oValor->vlrmin >= $nValorDeduzido))) {
          
      if (!isset($aItensIguais[$oValor->pc23_orcamitem][$oValor->z01_numcgm])) {
          
        $aItensIguais[$oValor->pc23_orcamitem][$oValor->z01_numcgm]  = $oValor->pc23_valor;
            
       }
    }
  }
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->SetAutoPageBreak(0,1);
$pdf->setfont('arial','b',8);

/**
 * se deve trocar de página
 */
$iTroca    = 1;
$iAlt      = 6;
$iTotal    = 0;

/**
 * se deve preencher a cor de fundo
 */
$ilBgColor = 0; 
$iMaxItens = 0;

/**
 *se já imprimou o máximo de itens 
 */
$lMax      = false;

/**
 * Quantidade de itens a imprimir
 */
$iQuantImp     = 0;
$nTamanhoCorpo = 0;
$iQuantItens   = 0;
$iQuantidadeAnterior = 0; 
/**
 * Percorremos of fornecedores da licitação 
 */
for ($x = 0; $x < $iNumRowsFornec;$x++) {
	  
  $oFornec = db_utils::fieldsmemory($rsFornecedores, $x); 
  $lPaginaAdicionada = false;  
  if (($pdf->gety() > $pdf->h - 30) || $iTroca != 0 ) {

    if (($pdf->gety() > $pdf->h - 30) || !$lMax) {
      
      $pdf->addpage('L');
      if ($iTroca == 0) {
        
        $iQuantidadeAnterior = $iQuantImp;
        $lPaginaAdicionada   = true;
      }
    }
    $ilBgColor = 0;
  	$pdf->setfont('arial','b',9);
  	$pdf->cell(60, $iAlt, "Fornecedor", 1, 0, "C", 1);
  	$pdf->cell(20, $iAlt, "Tipo Emp.", 1, 0, "C", 1);
  	
  	/*
  	 * Montamos o cabecalho com a quantidade de itens, onde cada coluna 
  	 * é a ordem do item no orcamento. 
  	 */
  	if (($iNumRowsItemFornec > $iMaxItens + 11) || ($iNumRowsItemFornec > $iMaxItens && $lPaginaAdicionada)) {
  	  
  	  if ($lPaginaAdicionada && $iQuantImp > 0) {
    	    $lPaginaAdicionada = false;
  	  } else {
  	    $iMaxItens  = $iMaxItens+11;  
  	  }
  	  $lMax       = true;
  	        	
  	} else {
  	  
  	  $lMax      = false;      	
  	  $iMaxItens = $iNumRowsItemFornec;
  	  
  	}
  	$iBordaCelula = 0;
  	for($iItens = $iQuantImp; $iItens < $iMaxItens; $iItens++){
  	  
  	  $oItemCabecalho = db_utils::fieldsMemory($rsItensCabec, $iItens);
  	  if ($iItens == ($iMaxItens - 1)){
  	   $iBordaCelula = 1;
   	  }
      $pdf->cell(18, $iAlt, $oItemCabecalho->l21_ordem, 1, $iBordaCelula, "C", 1);       
  	} 
  	$iTroca = 0;
  }
  
  $iAlt = 4;
  $pdf->setfont('arial','',7);
  $pdf->cell(60, $iAlt, substr($oFornec->z01_nome,0,35),0,0,"L");
  $pdf->cell(20, $iAlt, substr($oFornec->l32_descricao,0,40),0,0,"L");
  $iBordaCelula = 0;
  $iQuantItens  = 0;
  
  /**
   * Percorremos os itens da licitação
   */
  for ($iTotItens = $iQuantImp; $iTotItens < $iMaxItens; $iTotItens++) {

   $oItem = db_utils::fieldsMemory($rsItensCabec, $iTotItens);
	 $pdf->setfont('arial','',7);
   $iBordaCelula = 0;
   if ($iTotItens == ($iMaxItens - 1)) {
  	  
  	  $iBordaCelula  = 1;
  	  $nTamanhoCorpo = $pdf->getx()+10;
  	   
  	}
  	
    if (isset($aItensIguais[$oItem->pc22_orcamitem][$oFornec->z01_numcgm])
        && count($aItensIguais[$oItem->pc22_orcamitem]) > 1) {
           
      $pdf->cell(18,$iAlt,db_formatar($aItensIguais[$oItem->pc22_orcamitem][$oFornec->z01_numcgm],'f'),0,$iBordaCelula,"R");
    } else {
   	  $pdf->cell(18,$iAlt, "-", 0, $iBordaCelula, "C");
    }
  	$iQuantItens++;
  }
  if ($x == $iNumRowsFornec -1 && $lMax== true) {
	  
	//echo $iQuantItens;
	$iQuantImp           += $iQuantItens;
	$x                    = -1;
	$iTroca               = 1;
	//$iTotal     = 0;
	$pdf->setfont('arial','b',8);
	//$pdf->cell(280, $iAlt, '	', "T", 1, "L", 0);
	     	
	}      
	$iTotal++  ; 
}

//$pdf->Line(10, $pdf->GetY(),$nTamanhoCorpo+10, $pdf->GetY());
/**
 * Escrevemos a legenda com a descrição dos itens 
 */
$iTroca = 1;
$pdf->ln();
$pdf->Cell(150,5,"Legenda:",0,1);
for ($iItens = 0; $iItens < $iNumRowsItemFornec; $iItens++) {
  
  $oItemLegenda = db_utils::fieldsmemory($rsItensCabec, $iItens);
  if ($pdf->gety() > $pdf->h - 30 || $iTroca != 0 ) {
    
    if ($pdf->gety() > $pdf->h - 30) {
	  $pdf->addpage('L');
 	}
	$pdf->setfont('arial','b',8);
	$pdf->cell(20,$iAlt,"ITEM",1,0,"C",1);
	$pdf->cell(130,$iAlt,"MATERIAL",1,1,"C",1);
	$ilBgColor = 0;      
	$iTroca    = 0;
	
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$iAlt,$oItemLegenda->l21_ordem,0,0,"C",$ilBgColor);
  $pdf->cell(130,$iAlt,$oItemLegenda->pc01_descrmater,0,1,"L",$ilBgColor);
}
$pdf->Line(10, $pdf->GetY(),160, $pdf->GetY());
$pdf->Output();
?>