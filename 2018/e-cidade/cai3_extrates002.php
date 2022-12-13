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

$head8 = "Texto numero 8";
$head9 = "Texto numero 9";
$head10 = "Texto numero 10";
include("fpdf151/pdf.php");
//die ($HTTP_SERVER_VARS["QUERY_STRING"]);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

//////  EXTRATO
$conta = split("Y",$conta);
for($i = 0;$i < sizeof($conta);$i++) {
  $des = pg_exec("select k13_descr
       				          from saltes where k13_reduz = ".$conta[$i]) . " 
                                   inner join conplanoreduz on c62_reduz = k13_reduz and 
                                                 c62_anousu = ".db_getsession("DB_anousu")." and 
                                                 c61_instit = ".db_getsession("DB_instit"); 
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY($pdf->GetY() + 3);  
  $pdf->Cell(80,6,$conta[$i]." - ".pg_result($des,0,0),0,2,"L",0);
  $DATAi = false;
  $DATAf = false;
  if(isset($datai) && trim($datai)!=""){
    $DATAi = true;
  }
  if(isset($dataf) && trim($dataf)!=""){
    $DATAf = true;
  }
  
  $where_dt = " 1=1 ";
  if($DATAi == true && $DATAf==true){
    $where_dt = " c.k12_data ".$datai." between ".$dataf;
  }else if($DATAi == true){
    $where_dt = " c.k12_data >=".$datai;
  }else if($DATAf == true){
    $where_dt = " c.k12_data <=".$dataf;
  }
  $sql ="select distinct to_char(c.k12_data,'DD-MM-YYYY') as data,c.k12_hora,
                         (case when cn.k12_numpre is not null then 'Arrecadação: '||cn.k12_numpre::bpchar else 
                         (case when cl.k12_autent is not null then 'Slip: '||cl.k12_codigo::bpchar else 
                         (case when ce.k12_empen is not null then 'Empenho: '||ce.k12_empen::bpchar end) end) end) as codigo,
                          c.k12_autent,c.k12_conta,
                          cl.k12_conta as conta,c.k12_valor
              from corrente c
                     left outer join cornump cn  on cn.k12_id = c.k12_id
                                                              and cn.k12_data = c.k12_data
                                                              and cn.k12_autent = c.k12_autent
                     left outer join corlanc cl    on cl.k12_id = c.k12_id
										                     and cl.k12_data = c.k12_data
										                     and cl.k12_autent = c.k12_autent
                     left outer join coremp ce on ce.k12_id = c.k12_id
										                     and ce.k12_data = c.k12_data
										                     and ce.k12_autent = c.k12_autent
                     where corrente.k12_instit = " . db_getsession("DB_instit") . " and " . $where_dt."
					                and c.k12_conta = ".$conta[$i]." or cl.k12_conta = ".$conta[$i]."
					 ".($id=="T"?"":"and c.k12_id = $id")."
                     order by c.k12_hora";
//		     die($sql);
  $result = pg_exec($sql);
  $numrows = pg_numrows($result);
  $pdf->SetFont('Arial','B',8);
  $pdf->setX(20);
  $pdf->Cell(16,3,"Data","LRB",0,"C",0);  
  $pdf->Cell(9,3,"Hora","RB",0,"C",0);  
  $pdf->Cell(9,3,"Aut","RB",0,"C",0);
  $pdf->Cell(30,3,"Código","RB",0,"C",0);  	
  $pdf->Cell(15,3,"Débito","RB",0,"C",0); 		
  $pdf->Cell(15,3,"Crédito","RB",1,"C",0); 		
  $pdf->SetFont('Arial','',7);  
  for($j = 0;$j < $numrows;$j++) {
    $pdf->setX(20);
    $pdf->Cell(16,3,pg_result($result,$j,"data"),"LR",0,"L",0);  
    $pdf->Cell(9,3,pg_result($result,$j,"k12_hora"),"R",0,"L",0);  
    $pdf->Cell(9,3,pg_result($result,$j,"k12_autent"),"R",0,"l",0);
	if(pg_result($result,$j,"conta") != "") {
	  if(pg_result($result,$j,"conta") == $conta[$i]) {
        $pdf->Cell(30,3,pg_result($result,$j,"codigo")." Conta: ".pg_result($result,$j,"k12_conta"),"R",0,"L",0);		  
        $pdf->Cell(15,3,"0","R",0,"R",0);
        $pdf->Cell(15,3,number_format(pg_result($result,$j,"k12_valor"),2,".",","),"R",1,"R",0);	    
	  } else {
        $pdf->Cell(30,3,pg_result($result,$j,"codigo")." Conta: ".pg_result($result,$j,"conta"),"R",0,"L",0);		  
        $pdf->Cell(15,3,number_format(pg_result($result,$j,"k12_valor"),2,".",","),"R",0,"R",0);
        $pdf->Cell(15,3,"0","R",1,"R",0);		
	  }
	} else {
      $pdf->Cell(30,3,pg_result($result,$j,"codigo"),"R",0,"L",0);		
      $pdf->Cell(15,3,((float)pg_result($result,$j,"k12_valor") >= 0)?number_format(pg_result($result,$j,"k12_valor"),2,".",","):"","R",0,"R",0);
      $pdf->Cell(15,3,((float)pg_result($result,$j,"k12_valor") < 0)?number_format(pg_result($result,$j,"k12_valor"),2,".",","):"","R",1,"R",0);
    }	  
  }
}
//////
$pdf->Output();
?>