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
include("classes/db_coremp_classe.php");

$clcoremp = new cl_coremp;
$clcoremp->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('e60_codemp');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);


$dbwhere="";
$and="";
if(isset($dtini) && $dtini!=""){
  $dtini=str_replace("X","-",$dtini);
  $dbwhere.="coremp.k12_data = '$dtini'";
}


$result = $clcoremp->sql_record($clcoremp->sql_query_nome(null,null,null,"e60_anousu,coremp.k12_data,e60_codemp,z01_nome,coremp.k12_autent,corrente.k12_valor,corrente.k12_estorn","",$dbwhere));
$numrows = $clcoremp->numrows; 

$alt="5";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "Empenhos autenticados";
$head3 = "Data:".db_formatar($dtini,"d");

$pri=true;

$pago = '0.00';
$estorno = '0.00';
for ($i = 0;$i < $numrows;$i++){
  db_fieldsmemory($result,$i);
  if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
      if($pri==true){ 
        $pri = false;
      }	 
      
      $pdf->addpage("P");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',7);

      
      $pdf->cell(17,4,"Autenticação",1,0,"C",1);
      $pdf->cell(15,4,$RLe60_codemp,1,0,"C",1);
      $pdf->cell(35,4,$RLz01_nome,1,0,"C",1);
      $pdf->cell(15,4,"Pago",1,0,"C",1);
      $pdf->cell(15,4,"Estorno",1,0,"C",1);
      $pdf->ln();
  }  
  $pdf->setfont('arial','',7);
  $pdf->cell(17,4,$k12_autent,1,0,"C",0);
  $pdf->cell(15,4,$e60_codemp,1,0,"C",0);
  $pdf->cell(35,4,$z01_nome,1,0,"C",0);
  if($k12_estorn=='t'){
     $pdf->cell(15,4,'0.00',$k12_valor,1,0,"C",0);
     $pdf->cell(15,4,$k12_valor,1,1,"C",0);
     $estorno += $k12_valor;
  }else{
     $pdf->cell(15,4,$k12_valor,1,0,"C",0);
     $pdf->cell(15,4,'0.00',1,1,"C",0);
     $pago += $k12_valor;
  }
  
}
$pdf->cell(67,4,"Totalização:",1,0,"R",0);
$pdf->cell(15,4,$pago,1,0,"C",0);
$pdf->cell(15,4,$estorno,1,0,"C",0);

$pdf->ln();
$pdf->cell(50,6,"Total de registros:$numrows",0,0,"C",0);

$pdf->Output();
?>