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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_rhlota_classe.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r13_codigo');
$clrotulo->label('r13_descr');
$clrotulo->label('r13_descro');
$clrotulo->label('r70_estrut');


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sWhere = '';
$sTipo = 'Todos';
if($ativos != 'i'){
  $sTipo = 'Ativos';
  $sWhere = " and r70_ativo = '$ativos'";
  if($ativos == 'f'){
    $sTipo = 'Inativos';
  }
}

if($ordem == 'e'){
  $sOrdem = " r70_estrut ";
  $head5  = "Ordem Estrutural";
}elseif($ordem == 'a'){
  $sOrdem = " r70_descr ";
  $head5  = "Ordem Alfabética";
}else{
  $sOrdem = " r70_codigo ";
  $head5  = "Ordem Numérica";
}

$head3 = "CADASTRO DE LOTAÇÕES";
$head4 = "PERÍODO : ".$mes." / ".$ano;
$head6 = "TIPO    : ".$sTipo;

$oRhLota = db_utils::getDao('rhlota');

$sSqlLota = $oRhLota->sql_query_dadosElemento($ano, db_getsession('DB_instit'), $sWhere, $sOrdem);

$rsRhLota = $oRhLota->sql_record($sSqlLota);

if ($oRhLota->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Lotações cadastrados no período de '.$mes.' / '.$ano);

}

$pdf   = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$troca = 1;
$alt   = 4;
$aDados= array();

foreach (db_utils::getCollectionByRecord($rsRhLota) as $oDados) {

	if ( !isset($aDados[$oDados->r70_codigo]) ) {
	  $aDados[$oDados->r70_codigo] = new stdClass();
	} 

	$oDadosLinha = $aDados[$oDados->r70_codigo];
	
	$oDadosLinha->r70_estrut              = $oDados->r70_estrut   ; 
  $oDadosLinha->r70_codigo              = $oDados->r70_codigo   ; 
  $oDadosLinha->r70_descr               = $oDados->r70_descr    ; 
  $oDadosLinha->o40_orgao               = $oDados->o40_orgao    ; 
  $oDadosLinha->o40_descr               = $oDados->o40_descr    ; 
  $oDadosLinha->o41_orgao               = $oDados->o41_orgao    ; 
  $oDadosLinha->o41_unidade             = $oDados->o41_unidade  ; 
  $oDadosLinha->o41_descr               = $oDados->o41_descr    ; 
  $oDadosLinha->o55_projativ            = $oDados->o55_projativ ; 
  $oDadosLinha->o55_descr               = $oDados->o55_descr    ; 
  $oDadosLinha->o15_codigo              = $oDados->o15_codigo   ; 
  $oDadosLinha->o15_descr               = $oDados->o15_descr    ; 
  $oDadosLinha->o52_funcao              = $oDados->o52_funcao   ; 
  $oDadosLinha->o52_descr               = $oDados->o52_descr    ; 
  $oDadosLinha->o53_subfuncao           = $oDados->o53_subfuncao; 
  $oDadosLinha->o53_descr               = $oDados->o53_descr    ; 
  $oDadosLinha->o54_programa            = $oDados->o54_programa ; 
  $oDadosLinha->o54_descr               = $oDados->o54_descr    ;
  $oDadosLinha->c58_descr               = $oDados->c58_descr    ;
                                        
  $oElementoSecundario                  = new stdClass();
  $oElementoSecundario->rh28_codeledef  = $oDados->rh28_codeledef   ;   
  $oElementoSecundario->o56_codele_novo = $oDados->o56_codele_novo  ;
  $oElementoSecundario->o56_descr       = $oDados->o56_descr_novo   ;
  $oElementoSecundario->o15_codigo      = $oDados->o15_codigo_novo  ;
  $oElementoSecundario->o15_descr       = $oDados->o15_descr_novo   ;
  $oElementoSecundario->o55_projativ    = $oDados->o55_projativ_novo;
  $oElementoSecundario->o55_descr       = $oDados->o55_descr_novo   ;
  $oElementoSecundario->o55_anousu      = $oDados->o55_anousu_novo  ;
  
  $oDadosLinha->aElementosSecundarios[$oDados->o56_codele_novo] = $oElementoSecundario;       
}

foreach ($aDados as $oDadosLotacao) {
	   
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
     
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(25,$alt,'ESTRUTURAL'  ,1,0,"L",1);
      $pdf->cell(15,$alt,'CÓDIGO'      ,1,0,"L",1);
      $pdf->cell(75,$alt,'DESCRIÇÃO'   ,1,0,"L",1);
      $pdf->cell(85,$alt,'ÓRGAO'       ,1,0,"L",1);
      $pdf->cell(75,$alt,'UNIDADE'     ,1,1,"L",1);
      
      if($completo == 's'){
      	        
        $pdf->cell(80 ,$alt,'PROJETO / ATIVIDADE'     ,1,0,"L",1);
        $pdf->cell(80 ,$alt,'RECURSO'                 ,1,0,"L",1);        
        $pdf->cell(115,$alt,'CARACTERÍSTICA PECULIAR' ,1,1,"L",1);        

        $pdf->cell(80 ,$alt,'FUNÇÃO'    ,1,0,"L",1);
        $pdf->cell(80 ,$alt,'SUBFUNÇÃO' ,1,0,"L",1);  
        $pdf->cell(115,$alt,'PROGRAMA'  ,1,1,"L",1);

        $pdf->setfont('arial','BI',7);
        $pdf->cell(30,$alt,'ELEMENTO PRINCIPAL'              , 1,0,"L",1);
        $pdf->cell(85,$alt,'ELEMENTO NOVO - DESCRIÇÃO'       , 1,0,"L",1);
        $pdf->cell(70,$alt,'RECURSO - DESCRIÇÃO'             , 1,0,"L",1);
        $pdf->cell(75,$alt,'PROJETO / ATIVIDADE - DESCRIÇÃO' , 1,0,"L",1);
        $pdf->cell(15,$alt,'EXERCÍCIO'                       , 1,1,"L",1);
        $pdf->setfont('arial','',7);
      }
      
      $troca = 0;
      $pre = 1;
      
   }
   
   if ($pre == 1) {
      $pre = 0;
   } else {
     $pre = 1;
   }
   
   //$pdf->Cell(275,0,'','T');
   
   $pdf->setfont('arial','B',7);
   $pdf->cell(25,$alt,$oDadosLotacao->r70_estrut,'T',0,"L",$pre);
   $pdf->setfont('arial','',7);
   
   $pdf->cell(15,$alt,$oDadosLotacao->r70_codigo,'T',0,"L",$pre);
   $pdf->cell(75,$alt,$oDadosLotacao->r70_descr ,'T',0,"L",$pre);
   $pdf->cell(85,$alt,db_formatar($oDadosLotacao->o40_orgao,'orgao').' - '.$oDadosLotacao->o40_descr                                                 ,'T',0,"L",$pre);
   $pdf->cell(75,$alt,db_formatar($oDadosLotacao->o41_orgao,'orgao').db_formatar($oDadosLotacao->o41_unidade,'orgao').' - '.$oDadosLotacao->o41_descr,'T',1,"L",$pre);
   
   if ($completo == 's') {     
   	
     $pdf->cell(80,$alt,$oDadosLotacao->o55_projativ.' - '.$oDadosLotacao->o55_descr,0,0,"L",$pre);
     $pdf->cell(80,$alt,$oDadosLotacao->o15_codigo  .' - '.$oDadosLotacao->o15_descr,0,0,"L",$pre);
     $pdf->cell(115,$alt,$oDadosLotacao->c58_descr,0,1,"L",$pre);
     
   
     $pdf->cell(80,$alt,$oDadosLotacao->o52_funcao   .'-'.$oDadosLotacao->o52_descr,0,0,"L",$pre);
     $pdf->cell(80,$alt,$oDadosLotacao->o53_subfuncao.'-'.$oDadosLotacao->o53_descr,0,0,"L",$pre);
     $pdf->cell(115,$alt,$oDadosLotacao->o54_programa .'-'.$oDadosLotacao->o54_descr,0,1,"L",$pre);
     
     $pdf->setfont('arial','I',7);
     if (count($oDadosLotacao->aElementosSecundarios > 0)) {
     	
     	foreach ($oDadosLotacao->aElementosSecundarios as $oElementos) {
     		
		     $pdf->cell(30,$alt,strtoupper($oElementos->rh28_codeledef)                                  ,0,0,"L",$pre);
		     $pdf->cell(85,$alt,strtoupper($oElementos->o56_codele_novo . " - ". $oElementos->o56_descr) ,0,0,"L",$pre);
		     $pdf->cell(70,$alt,strtoupper($oElementos->o15_codigo . " - " . $oElementos->o15_descr)     ,0,0,"L",$pre);
		     $pdf->cell(75,$alt,strtoupper($oElementos->o55_projativ . " - "  . $oElementos->o55_descr)  ,0,0,"L",$pre);
		     $pdf->cell(15,$alt,strtoupper($oElementos->o55_anousu)                                      ,0,1,"L",$pre);
     	}
     	
     }
     $pdf->setfont('arial','',7);
     
   }
   
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.count($aDados),"T",0,"C",0);

$pdf->Output();
   
?>