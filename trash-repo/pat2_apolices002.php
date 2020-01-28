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
include("libs/db_sql.php");
include("classes/db_bens_classe.php");
include("classes/db_apolitem_classe.php");
include("classes/db_apolice_classe.php");
include("classes/db_seguradoras_classe.php");
$clbens = new cl_bens;
$clapolice = new cl_apolice;
$clapolitem = new cl_apolitem;
$clseguradoras = new cl_seguradoras;
$clrotulo = new rotulocampo;
$clbens->rotulo->label();
$clapolice->rotulo->label();
$clapolitem->rotulo->label();
$clseguradoras->rotulo->label();
$clrotulo->label("z01_nome"); //classificação
$clrotulo->label("t64_class"); //classificação
$clrotulo->label("descrdepto"); //classificação
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if((isset($t81_codapo) && trim($t81_codapo)!="")){
  $result = $clapolice->sql_record($clapolice->sql_query($t81_codapo));
  //db_criatabela($result);
  if($clapolice->numrows>0){
    db_fieldsmemory($result,0);
  }else{
    
    $oParms = new stdClass();
    $oParms->codigoApolice = $t81_codapo;
    $sMsg = _M('patrimonial.patrimonio.pat2_apolices002.apolice_nao_encontrada', $oParms);
    db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
  }
  $result_apolitem = $clapolitem->sql_record($clapolitem->sql_query(null,null,"*","t82_codbem"," apolitem.t82_codapo=$t81_codapo and apolice.t81_venc >='".date("Y-m-d",db_getsession("DB_datausu"))."'" ));
  $numrows = $clapolitem->numrows;
//  db_criatabela($result_apolitem);
  if($numrows==0){
    
    $oParms = new stdClass();
    $oParms->codigoApolice = $t81_codapo;
    $oParms->sApolice = $t81_apolice;
    $sMsg = _M('patrimonial.patrimonio.pat2_apolices002.sem_bens_cadastrados', $oParms);
    db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
  }
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$troca = 1;
$alt = 4;

$head2 = "VENCIMENTO DA APÓLICE (".db_formatar($t81_venc,"d").")";
$head4 = "CÓDIGO: $t81_codapo";
$head5 = "DESCRIÇÃO: $t81_apolice";
$head7 = "SEGURADORA: ".substr($z01_nome,0,35);
$head8 = "CONTATO: $t80_contato";

$pdf->addpage();
$pdf->ln(1);
//$alt = 30;
  for($x = 0; $x<$numrows; $x++){
    db_fieldsmemory($result_apolitem,$x);
    if($pdf->gety() > $pdf->h - 30 || $troca!=0){
      if($pdf->gety() > $pdf->h - 30){
        $pdf->addpage();
        $troca = 0;
      }
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"Bem"        ,1,0,"C",1);
      $pdf->cell(25,$alt,$RLt52_ident ,1,0,"C",1);
      $pdf->cell(75,$alt,$RLt52_descr ,1,0,"C",1);
      $pdf->cell(20,$alt,$RLt64_class ,1,0,"C",1);
      $pdf->cell(50,$alt,$RLdescrdepto,1,1,"C",1);
      $troca = 0;
    }
    $pdf->setfont('arial','',6);
    $pdf->cell(20,$alt,$t52_bem   ,0,0,"C",0);
    $pdf->cell(25,$alt,$t52_ident ,0,0,"L",0);
    $pdf->cell(75,$alt,substr($t52_descr,0,60),0,0,"L",0);
    $pdf->cell(20,$alt,$t64_class ,0,0,"C",0);
    $pdf->cell(50,$alt,$descrdepto,0,1,"L",0);
    $total++;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(190,$alt,'TOTAL DE BENS NESTA APÓLICE :  '.$total,"T",0,"L",0);
//  $result_apolitem = $clapolitemi
$pdf->Output();
?>