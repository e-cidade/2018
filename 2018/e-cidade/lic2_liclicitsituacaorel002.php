<?
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
include("classes/db_liclicita_classe.php");
include("classes/db_liclicitasituacao_classe.php");
include("classes/db_liclicitem_classe.php");
include("classes/db_empautitem_classe.php");
include("classes/db_pcorcamjulg_classe.php");
$clliclicita         = new cl_liclicita;
$clliclicitasituacao = new cl_liclicitasituacao;
$clliclicitem        = new cl_liclicitem;
$clempautitem        = new cl_empautitem;
$clpcorcamjulg       = new cl_pcorcamjulg;
$clrotulo = new rotulocampo;
$clrotulo->label('');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
$where  = "";
$and    = "";
$whAnda = '';
if (($data != "--") && ($data1 != "--")) {
	$where  .= $and." l11_data  between '$data' and '$data1' ";
	$whAnda .= " and l11_data  between '$data' and '$data1' ";
	$data    = db_formatar($data, "d");
	$data1   = db_formatar($data1, "d");
	$info    = "De $data até $data1.";
	$and     = " and ";

}else if ($data != "--") {
	$where  .= $and." l11_data >= '$data'  ";
	$whAnda .= " and l11_data >= '$data'  ";
	$data    = db_formatar($data, "d");
  $info    = "Apartir de $data.";
  $and     = " and ";
}else if ($data1 != "--") {
	$where   .= $and." l11_data <= '$data1'   ";
	$whAnda .= " and  l11_data <= '$data1'   ";
	$data1    = db_formatar($data1, "d");
	$info     = "Até $data1.";
	$and      = " and ";
}
if ($l20_codigo!=""){
	$where .= $and." l20_codigo=$l20_codigo ";
	$and = " and ";
}

if ($situac != ''){

 $in = $selec == "S"?" in ":" not in";
 $where .= $and ." l20_licsituacao $in ($situac)";
 $and = " and ";

}
$where .= $and." l20_instit = ".db_getsession("DB_instit");

$result=$clliclicitasituacao->sql_record($clliclicitasituacao->sql_query(null,"distinct liclicita.*,l03_descr,nome"
                             ,"l20_codtipocom,l20_numero",$where));
//die( $clliclicitasituacao->sql_query(null,"distinct liclicita.*,l03_descr,nome"
//                             ,"l20_codtipocom,l20_numero",$where));
$numrows=$clliclicitasituacao->numrows;
if ($numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');
   exit;
}
$head2 = "Situações das Licitações";
$head3 = @$info;
$head4 = @$info1;
$head5 = @$info2;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 0;
$alt = 4;
$total = 0;
$p=0;
$valortot=0;
$muda=0;
for($i=0;$i<$numrows;$i++){

  db_fieldsmemory($result,$i);
 	$pdf->addpage();
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Código Sequencial:',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$l20_codigo,0,1,"L",0);
 
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Edital:',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,$l20_edital,0,0,"L",0);
  
  $pdf->setfont('arial','b',8); 
  $pdf->cell(30,$alt,'Tipo de Compra :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$l20_codtipocom.' - '.$l03_descr,0,0,"L",0);
  
  $pdf->setfont('arial','b',8); 
  $pdf->cell(30,$alt,'Número :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$l20_numero,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Data Publicação :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,db_formatar($l20_dtpublic,'d'),0,0,"L",0); 
 
  $pdf->setfont('arial','b',8); 
  $pdf->cell(30,$alt,'Data Abertura :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,db_formatar($l20_dataaber,'d'),0,0,"L",0);
  
  $pdf->setfont('arial','b',8); 
  $pdf->cell(30,$alt,'Hora Abertura :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$l20_horaaber,0,1,"L",0);
  
  $pdf->setfont('arial','b',8); 
  $pdf->cell(30,$alt,'Usuário :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$l20_id_usucria.' - '.$nome,0,1,"L",0);

  $pdf->setfont('arial','b',8); 
  $pdf->cell(30,$alt,'Objeto :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->multicell(150,$alt,$l20_objeto,0,"L",0);

  $rsAndam = $clliclicitasituacao->sql_record($clliclicitasituacao->sql_query('','*',"l11_data,l11_sequencial"
	                                 ,"l11_liclicita = $l20_codigo $whAnda")); 
	if ($clliclicitasituacao->numrows > 0){

     $pdf->setfont('arial','b',8);
	   $pdf->cell(100,$alt,'Usuário',1,0,"C",1);
	   $pdf->cell(30,$alt,'Situação',1,0,"C",1);
	   $pdf->cell(20,$alt,'Data',1,0,"C",1);
	   $pdf->cell(20,$alt,'Hora',1,1,"C",1);
	   $pdf->cell(170,$alt,'Observações',1,1,"C",1);
	   $pdf->setfont('arial','',8);
	   for ($k = 0;$k < $clliclicitasituacao->numrows;$k++){

	     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	        if ($pdf->gety() > $pdf->h - 30){
	          	$pdf->addpage();
	      }
        $pdf->setfont('arial','b',8);
	      $pdf->cell(100,$alt,'Usuário',1,0,"C",1);
	      $pdf->cell(30,$alt,'Situação',1,0,"C",1);
	      $pdf->cell(20,$alt,'Data',1,0,"C",1);
	      $pdf->cell(20,$alt,'Hora',1,1,"C",1);
	      $pdf->cell(170,$alt,'Observações',1,1,"C",1);
	      $pdf->setfont('arial','',8);
	 		  $troca = 0;
		  }
      db_fieldsmemory($rsAndam,$k);
      $pdf->cell(100,$alt,$nome,'B',0,"L");
      $pdf->cell(30,$alt,$l08_descr,'B',0,"L");
      $pdf->cell(20,$alt,db_formatar($l11_data,'d'),'B',0,"C");
      $pdf->cell(20,$alt,$l11_hora,"B",1,"C");
      $pdf->multicell(170,$alt,$l11_obs,"B",1,"L");
      //$pdf->cell(170,$alt,$l11_obs,"B",1,"L");
    }
		//$pdf->cell(

  }
}
	  
$pdf->Output();
?>