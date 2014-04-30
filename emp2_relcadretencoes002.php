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
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("classes/db_retencaotiporec_classe.php");
require("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$oClretencaotiporec = new cl_retencaotiporec();

$head2 = "Relatório Cadastro de Retenções";
$head3 = @$info;
$head4 = @$info1;
$head5 = @$info2;

$oPdf  = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);

$lTroca    = true;
$iAlt      = 4;

  $sWhere  = " e21_instit = " . db_getsession("DB_instit");
  
  if ( isset($oGet->e21_retencaotipocalc) && $oGet->e21_retencaotipocalc != 0 ){
    $sWhere .= " and e21_retencaotipocalc = ". $oGet->e21_retencaotipocalc;
  }
  
  if ( isset($oGet->e21_retencaotiporecgrupo) && $oGet->e21_retencaotiporecgrupo != 0 ){
    $sWhere .= " and e21_retencaotiporecgrupo = ". $oGet->e21_retencaotiporecgrupo;
  }
  
  $sOrder  = "e21_sequencial ";
  $sCampos = "e01_sequencial, e01_descricao, e32_sequencial, e32_descricao, e21_sequencial, e21_descricao, e21_receita";
  $sSql    = $oClretencaotiporec->sql_query("",$sCampos, $sOrder, $sWhere);
  $rsResultRetencaotiporec  = $oClretencaotiporec->sql_record($sSql);
  $iNumrows = $oClretencaotiporec->numrows;
	
  
  $aGrupo = array();
  
  for ($iInd = 0; $iInd < $iNumrows; $iInd++) {
    
    $oDados = db_utils::fieldsMemory($rsResultRetencaotiporec, $iInd);
    
    $oRetencao = new stdClass();
    $oRetencao->iSequencial = $oDados->e21_sequencial;
    $oRetencao->sDescricao  = $oDados->e21_descricao;
    $oRetencao->iReceita    = $oDados->e21_receita;
    
    $oTipo = new stdClass();
    $oTipo->iCodigo                            = $oDados->e32_sequencial;
    $oTipo->sDescricao                         = $oDados->e32_descricao;
    $oTipo->aRetencao[$oDados->e21_sequencial] = $oRetencao;
    
    $oGrupo = new stdClass();
    $oGrupo->iCodigo                        = $oDados->e01_sequencial;
    $oGrupo->sDescricao                     = $oDados->e01_descricao;
    $oGrupo->aTipo[$oDados->e32_sequencial] = $oTipo;   
    
    /**
     * Agrupa os dados por grupo, tipo de calculo, codigo/descrição/receita
     */
    if (isset($aGrupo[$oDados->e01_sequencial]) ) {
    	
    	if (isset($aGrupo[$oDados->e01_sequencial]->aTipo[$oDados->e32_sequencial])) {
    		
    		if (!isset($aGrupo[$oDados->e01_sequencial]->aTipo[$oDados->e32_sequencial]->aRetencao[$oDados->e21_sequencial])) {
    			$aGrupo[$oDados->e01_sequencial]->aTipo[$oDados->e32_sequencial]->aRetencao[$oDados->e21_sequencial] = $oRetencao;
    		}    			
    	} else {
    		$aGrupo[$oDados->e01_sequencial]->aTipo[$oDados->e32_sequencial] = $oTipo;
    	}
    } else {
    	$aGrupo[$oDados->e01_sequencial] = $oGrupo;
    }
	  
  }  

  foreach ($aGrupo as $oGrupoRetencao) {
  	
    $oPdf->AddPage();
    $oPdf->setfont('arial','b',8);
    $oPdf->cell(30,$iAlt,'Grupo:',0,0,"R",0);
    $oPdf->setfont('arial','',7);
    $oPdf->cell(60,$iAlt, $oGrupoRetencao->sDescricao, 0,1,"L",0);
  	
    foreach ($oGrupoRetencao->aTipo as $oTipoCalculo){
      /*
       * cabecalho dos dados
       */
   	  $oPdf->setfont('arial','b',8);
      $oPdf->cell(30,$iAlt,'Tipo de Cálculo:',0,0,"R",0);
      $oPdf->setfont('arial','',7);
      $oPdf->cell(30,$iAlt, $oTipoCalculo->sDescricao,0,1,"L",0);
      
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(20 ,$iAlt, 'Código'  ,1,0,"C",1);
      $oPdf->cell(150,$iAlt, 'Decrição',1,0,"C",1);
      $oPdf->cell(20 ,$iAlt, 'Receita' ,1,1,"C",1); 

      
      $iCor=1;
      foreach ($oTipoCalculo->aRetencao as $oRetencaoDados){
        /*
         * seta a cor da linha 0 sem com 1 com cor
         */ 
      	if($iCor == 0){
      		$iCor = 1;
      	}else{
      		$iCor = 0;
      	}
      	
        if ($oPdf->gety() > $oPdf->h - 30 || $lTroca != false ){
        	
            if ($oPdf->gety() > $oPdf->h - 30){
                $oPdf->addpage();
            /*
             * insere novo cabecalho de dados na quebra de pagina
             */    
            $oPdf->setfont('arial','b',8);
			      $oPdf->cell(30,$iAlt,'Tipo de Cálculo:',0,0,"R",0);
			      $oPdf->setfont('arial','',7);
			      $oPdf->cell(30,$iAlt, $oTipoCalculo->sDescricao,0,1,"L",0);
			      
			      $oPdf->setfont('arial','b',8);
			      $oPdf->cell(20 ,$iAlt, 'Código'  ,1,0,"C",1);
			      $oPdf->cell(150,$iAlt, 'Decrição',1,0,"C",1);
			      $oPdf->cell(20 ,$iAlt, 'Receita' ,1,1,"C",1);     
			      
			      $iCor=0;
			                
         }
         /*
          * bloco de dados
          */
         $oPdf->setfont('arial','',7);
         $oPdf->cell(20 ,$iAlt,  $oRetencaoDados->iSequencial,'B',0,"C",$iCor);
         $oPdf->cell(150,$iAlt,  $oRetencaoDados->sDescricao , 1, 0,"L",$iCor);
         $oPdf->cell(20 ,$iAlt,  $oRetencaoDados->iReceita   ,"B",1,"C",$iCor);
         $lTroca = true;
        }
      
      }
      
      $oPdf->Ln();      
    }
  }
    
$oPdf->Output();
?>