<?php
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
include("libs/db_utils.php");
include("classes/db_declaracaoquitacao_classe.php");

$oGet = db_utils::postMemory($_GET);

$clDeclaracaoQuitacao = new cl_declaracaoquitacao();

$oPDF = new PDF();

$clrotulo = new rotulocampo();
$clrotulo->label('ar30_sequencial');
$clrotulo->label('ar30_exercicio');
$clrotulo->label('ar32_datacancelamento');
$clrotulo->label('z01_nome');

$oPDF->open();
$oPDF->AliasNbPages(); 
$oPDF->setfillcolor(235);
$oPDF->setfont('arial','b',8);

$iTroca = 1;
$iAlt   = 4;
$iTotal = 0;

if ($oGet->origem == 'matric') {
  
  $sOrigem = 'Matricula';
  
} elseif($oGet->origem == 'inscr') {
  
  $sOrigem = 'Inscrição';
  
} elseif($oGet->origem == 'cgm') {

  $sOrigem = 'CGM Geral';

} elseif($oGet->origem == 'somentecgm') {
  
  $sOrigem = 'Somente CGM';
  
}

if($oGet->ordenar == 'datacancelamento') {
  
  $sOrdenar = 'Data de Cancelamento';
  
} elseif($oGet->ordenar == 'declaracao') {

  $sOrdenar = 'Código da Declaração';
    
}

$head1 = '';
$head2 = 'Data Inicial: '. implode('/', array_reverse(explode('-', $oGet->datainicial)));
$head4 = 'Data Final: '. implode('/', array_reverse(explode('-', $oGet->datafinal)));
$head6 = 'Origem: '.$sOrigem;
$head8 = 'Ordenado por: '.$sOrdenar;

$rDeclaracaoQuitacao = $clDeclaracaoQuitacao->sql_record($clDeclaracaoQuitacao->sql_query_declaracoes_canceladas($oGet->origem, $oGet->datainicial, $oGet->datafinal, $oGet->ordenar));

if ($clDeclaracaoQuitacao->numrows > 0) {
  
  for ($i = 0; $i < $clDeclaracaoQuitacao->numrows; $i++) {
    
    $oDeclaracoesCanceladas = db_utils::fieldsMemory($rDeclaracaoQuitacao, $i, true);

    if ($oPDF->gety() > $oPDF->h - 30 || $iTroca != 0 ) {
      
      $oPDF->addpage('L');
      $oPDF->setfont('arial','b',8);
      $oPDF->cell(40, $iAlt, $RLar30_sequencial      , 1, 0, "C", 1);
      $oPDF->cell(40, $iAlt, $RLar30_exercicio       , 1, 0, "C", 1); 
      $oPDF->cell(40, $iAlt, $RLar32_datacancelamento, 1, 0, "C", 1); 
      $oPDF->cell(80, $iAlt, $RLz01_nome             , 1, 0, "C", 1); 
      $oPDF->cell(40, $iAlt, 'Origem'                , 1, 0, "C", 1); 
      $oPDF->cell(40, $iAlt, 'Cod Origem'            , 1, 1, "C", 1); 
      $iTroca = 0;
      $iP     = 0;
      
    }
  
    $oPDF->setfont('arial', '', 7);
    $oPDF->cell(40, $iAlt, $oDeclaracoesCanceladas->ar30_sequencial      , 0, 0, "C", $iP);
    $oPDF->cell(40, $iAlt, $oDeclaracoesCanceladas->ar30_exercicio       , 0, 0, "C", $iP);
    $oPDF->cell(40, $iAlt, $oDeclaracoesCanceladas->ar32_datacancelamento, 0, 0, "C", $iP);
    $oPDF->cell(80, $iAlt, $oDeclaracoesCanceladas->z01_nome             , 0, 0, "L", $iP);
    $oPDF->cell(40, $iAlt, $oDeclaracoesCanceladas->origem               , 0, 0, "C", $iP);
    $oPDF->cell(40, $iAlt, $oDeclaracoesCanceladas->cod_origem           , 0, 1, "C", $iP);
    $oPDF->multicell(0,$iAlt,$oDeclaracoesCanceladas->ar32_obs ,0,"L",$iP);
    
    if ($iP==0) {
      
      $iP = 1;
      
    } else {
       
      $iP=0;
      
    }
      
    $iTotal++;
    
  }
  
}

$oPDF->setfont('arial', 'b', 8);
$oPDF->cell(0, $iAlt, 'TOTAL DE REGISTROS  :  '.$iTotal, "T", 0, "L", 0);
$oPDF->Output();