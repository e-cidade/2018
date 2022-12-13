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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_contranslan_classe.php");

$clcontranslan = new cl_contranslan;

$clrotulo = new rotulocampo;
$clrotulo->label('c45_coddoc');
$clrotulo->label('c46_seqtrans');
$clrotulo->label('c47_seqtranslr');
$clrotulo->label('c46_codhist');
$clrotulo->label('c46_obs');
$clrotulo->label('c50_descr');
$clrotulo->label('c47_credito');
$clrotulo->label('c47_debito');
$clrotulo->label('c60_estrut');
$clrotulo->label('c47_credito');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$dbwhere="1=1";
$and="";


if(isset($ano)){
  $dbwhere = " c45_anousu = $ano";
  $and = " and ";
}

if(isset($coddoc) && $coddoc!=''){
  $dbwhere .= $and." c45_coddoc = $coddoc ";
}



$result = $clcontranslan->sql_record($clcontranslan->sql_query(null,"distinct c45_coddoc,c50_descr","","$dbwhere"));
$numrows = $clcontranslan->numrows; 
$alt="5";

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "Transações de lançamentos contábeis";

$pdf->AddPage("L");
$pdf->setfillcolor(235);

if($numrows>0){
  for ($x = 0;$x < $numrows;$x++){
    db_fieldsmemory($result,$x);
    if(isset($ano)){
      $dbwhere = " c45_anousu = $ano";
       $and = " and ";
    }

    $dbwhere .= $and." c45_coddoc = $c45_coddoc ";
    $result_l = $clcontranslan->sql_record($clcontranslan->sql_query_lr(null,"c47_seqtranslr,c46_seqtrans,c46_obs,c50_descr,c47_credito,cre2.c60_estrut as cred_estrut,c47_debito,deb2.c60_estrut as deb_estrut",'c47_seqtranslan,deb2.c60_estrut',"$dbwhere"));
    $numrows_l = $clcontranslan->numrows;


    $pdf->setfont('arial','b',10);
    $pdf->ln();
    $pdf->ln();

    $pdf->cell(80,4,"$RLc45_coddoc: $c45_coddoc-$c50_descr",1,1,"C",1);
    $pdf->cell(15,4,"Código",1,0,"C",1);
    $pdf->cell(20,4,"Obs",1,0,"C",1);
    $pdf->cell(15,4,"Seq",1,0,"C",1);
    $pdf->cell(115,4,$RLc47_debito,1,0,"C",1);
    $pdf->cell(115,4,$RLc47_credito,1,1,"C",1);


    for ($i = 0;$i < $numrows_l;$i++){
      db_fieldsmemory($result_l,$i,true);
      if ($pdf->gety() > $pdf->h -50  ){
			  $pdf->addpage("L");
			  
			  $pdf->setfont('arial','b',10);
			  $pdf->cell(80,4,"$RLc45_coddoc: $c45_coddoc-$c50_descr",1,1,"C",1);
			  $pdf->cell(15,4,"Código",1,0,"C",1);
			  $pdf->cell(20,4,"Obs",1,0,"C",1);
			  $pdf->cell(15,4,"Seq",1,0,"C",1);
			  $pdf->cell(115,4,$RLc47_debito,1,0,"C",1);
			  $pdf->cell(115,4,$RLc47_credito,1,1,"C",1);	  
      }  
      $pdf->setfont('arial','',8);
      $pdf->cell(15,4,$c46_seqtrans,1,0,"C",0);
      $pdf->cell(20,4,substr($c46_obs,0,8),1,0,"L",0);
      $pdf->cell(15,4,$c47_seqtranslr,1,0,"C",0);
      $pdf->cell(40,4,"($c47_debito)  $deb_estrut",1,0,"C",0);
	  $sql="select c60_descr 
	             from conplano 
	                 inner join conplanoreduz on c61_codcon=c60_codcon and c61_anousu=c60_anousu
	             where c60_anousu = ".db_getsession("DB_anousu")." and c61_reduz = $c47_debito ";
      $rc = pg_exec($sql);
	 @db_fieldsmemory($rc,0);
      $pdf->cell(75,4,substr($c60_descr,0,40),1,0,"L",0); 
      $pdf->cell(40,4,"($c47_credito)  $cred_estrut",1,0,"C",0);
	  $sql="select c60_descr 
	             from conplano 
	                 inner join conplanoreduz on c61_codcon=c60_codcon and c61_anousu=c60_anousu
	             where c60_anousu = ".db_getsession("DB_anousu")." and c61_reduz = $c47_credito ";
      $rc = pg_exec($sql);
	  @db_fieldsmemory($rc,0);
      $pdf->cell(75,4,substr($c60_descr,0,40),1,1,"L",0); 
       
    }
  }
}  
$pdf->Output();
?>