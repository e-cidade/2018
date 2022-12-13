<?
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

include("libs/db_sql.php");
include("libs/db_utils.php");
require("fpdf151/pdf.php");

db_postmemory($HTTP_SERVER_VARS);

$oGet    = db_utils::postMemory($_GET);

$Dados   = str_replace("XX",",",$Dados);
$sData   = date('Y-m-d');
$sWhere  = "";

if((isset($oGet->situacao))) {

  $iTam         = 90;
  $headSituacao = 'Todos';
  if ($oGet->situacao == 'A') {

    $iTam         = 120;
    $headSituacao = 'Ativas';
    $sWhere      .= " and ( issbase.q02_dtbaix is null ";
    $sWhere      .= "    or issbase.q02_dtbaix >= '{$sData}' ) ";
  } else if ($oGet->situacao == 'B') {

    $headSituacao = 'Baixadas';
    $sWhere      .= " and issbase.q02_dtbaix is not null ";
    $sWhere      .= "    and issbase.q02_dtbaix < '{$sData}' ";
  }
}

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();

$head4   = "Inscrições Por Escritórios Contábeis";
$head5   = "Situação das Inscrições: {$headSituacao}";
$linha   = 60;

$pdf->setfillcolor(235);
$TPagina = 40;

$pdf->setfont('arial','b',8);
$troca   = 1;
$alt     = 4;
$p       = 0;

   //sql dos escritórios
   $sSqlCadEscrito = "select q86_numcgm, 
                             z01_nome 
                        from cadescrito 
	                           inner join cgm on q86_numcgm = z01_numcgm 
       	               where q86_numcgm in ({$Dados}) order by z01_nome";
   $result = db_query($sSqlCadEscrito);
   $numrows = pg_numrows($result);
    
   for($x=0; $x< $numrows; $x++) {
     db_fieldsmemory($result,$x);
     
     //quebra de página
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"Inscrição",1,0,"C",1);
      $pdf->cell(20,$alt,"Cgm",1,0,"C",1);
      $pdf->cell($iTam,$alt,"Nome/Razão Social",1,0,"L",1);
      
      if ($oGet->situacao != 'A') {
        $pdf->cell(30,$alt,"Data de Baixa",1,0,"C",1);
      }
      
      $pdf->cell(30,$alt,"Situação",1,1,"C",1);
      $troca = 0;
      $p     = 0;
     }
     
     //nome do escritório
      $pdf->setfont('arial','b',8);
      $pdf->cell(120,$alt,$q86_numcgm." - ".$z01_nome,0,1,"L",0);
      
     //sql dos clientes
     $sSqlEscrito = " select q10_inscr,
                             z01_numcgm,
                             z01_nome,
                             q02_dtbaix,
					                   case                                                                          
					                     when ( issbase.q02_dtbaix is not null and issbase.q02_dtbaix < '{$sData}' )  
					                       then 'Baixada'                                                             
					                     else 'Ativa'                                                             
					                   end as situacao
                        from escrito 
	                           inner join cadescrito on q86_numcgm = q10_numcgm 
	                           inner join issbase    on q10_inscr  = q02_inscr
	 	                         inner join cgm        on q02_numcgm = z01_numcgm 
                       where q10_numcgm = {$q86_numcgm}
                    {$sWhere} 
                    order by z01_nome ";

     $result2  = db_query($sSqlEscrito);
     $numrows2 = pg_numrows($result2);
     
     //não ha registros
     if ($numrows2 == 0) {
      $pdf->setfont('arial','',8);
      $pdf->cell(10,$alt,"",0,0,"L",1);     
      $pdf->cell(110,$alt,"Nenhum registro cadastrado",0,1,"L",1);
      continue;
     }
     
     //dados 
     if ($soescritorios=="nao") // agora entra no for 
     for($y=0;$y<$numrows2; $y++){
        db_fieldsmemory($result2,$y);
        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
            $pdf->addpage();
            $pdf->setfont('arial','b',8);
            $pdf->cell(20,$alt,"Inscrição",1,0,"C",1);
            $pdf->cell(20,$alt,"Cgm",1,0,"C",1);
            $pdf->cell($iTam,$alt,"Nome/Razão Social",1,0,"L",1);
            
            if ($oGet->situacao != 'A') {
              $pdf->cell(30,$alt,"Data de Baixa",1,0,"C",1);
            }
            
            $pdf->cell(30,$alt,"Situação",1,1,"C",1);
	          $troca = 0;
	          $p     = 0;
        }
        
        // imprime dados da inscricao;
        $pdf->setfont('arial','',8);
        $pdf->cell(20,$alt,$q10_inscr,0,0,"C",$p);
        $pdf->cell(20,$alt,$z01_numcgm,0,0,"C",$p);
        $pdf->cell($iTam,$alt,$z01_nome,0,0,"L",$p);
        
        if ($oGet->situacao != 'A') {
          $pdf->cell(30,$alt,db_formatar($q02_dtbaix,"d"),0,0,"C",$p);
        }
        
        $pdf->cell(30,$alt,$situacao,0,1,"C",$p);
        
	      //cores das linhas
        if($p == 1){
           $p = 0;
        } else{
           $p = 1; 
        }
     }
   }
  
$pdf->Output();
?>