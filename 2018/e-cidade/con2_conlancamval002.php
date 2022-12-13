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

 include_once("fpdf151/pdf.php");
 db_postmemory($HTTP_SERVER_VARS);

 $instituicao = str_replace("-",",",$db_selinstit);

 $w = '';
 if (isset($lista)){
    $w .="(";
    $tamanho= sizeof($lista);
    for ($x=0;$x < sizeof($lista);$x++){
        $w = $w."$lista[$x]";
        if ($x < $tamanho-1) {
            $w= $w.",";
        }
    }
    $w = $w.")";
 }
  $data_ini="";
  $data_fim="";
  @$data_ini="$DBtxt21_ano-$DBtxt21_mes-$DBtxt21_dia";
  @$data_fim="$DBtxt22_ano-$DBtxt22_mes-$DBtxt22_dia";
  if (strlen($data_ini < 9 )|| strlen($data_fim < 9))  {
     $data_ini="";
     $data_fim="";
  }  
  $sql ="select  c69_codlan,
                 c69_data,
                 c69_debito,
                 ca.c60_descr as debito_descr, 
                 c69_credito,
                 cb.c60_descr as credito_descr, 
                 c69_valor,
                 c69_codhist,
                 c50_descr ,
		 c72_complem
         from conlancamval
             inner join conplanoreduz ra on ra.c61_reduz  = c69_debito and 
                                            ra.c61_instit in ($instituicao) and
                                            ra.c61_anousu = ".db_getsession("DB_anousu")."
             inner join conplano ca      on ca.c60_codcon = ra.c61_codcon and ca.c60_anousu=ra.c61_anousu 
             inner join conplanoreduz rb on rb.c61_reduz  = c69_credito and rb.c61_anousu = ".db_getsession("DB_anousu")."
             inner join conplano cb      on cb.c60_codcon = rb.c61_codcon and cb.c60_anousu=rb.c61_anousu
             left outer join conhist  on c50_codhist = c69_codhist
	         left join conlancamcompl on c72_codlan = c69_codlan
	    ";

    if (($data_ini !="") and ($data_fim !="" )) {
       $sql.="where c69_data >= '$data_ini' and c69_data <= '$data_fim' ";     
       if ($w != '')
	 $sql .= " and c50_codhist in $w "; 
    }else{
       if ($w != '')
	 $sql = " where c50_codhist in $w "; 
    }
    $sql.=" order by c69_codlan "; 

   //--
   //echo $sql;exit;
   $result = pg_exec($sql);
   $rows = pg_numrows($result);
   if($result==false){
        db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado, verifique as datas e tente novamente');   
   }
   //-- 
   $dt = date("d/m/Y");
   $head2 = "RELETÓRIO DE LANÇAMENTOS";
   $head3 = "PERÍODO : ".db_formatar($data_ini,'d')." a ".db_formatar($data_fim,'d');
   $head4 = "";
   $head5 = "";
   $head6 = "";
   $head7 = "";
   $head8 = "";
   $head9 = "";
  
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage("L");

  // monta cabecalho do relatório    
  $pdf->SetFillColor(235);
  $pdf->setY(40);
  $pdf->setX(5);

  $imprime = true;
  for ($x=0;$x< $rows;$x++){    
       db_fieldsmemory($result,$x,true);
       if ($pdf->gety() > $pdf->h - 40 ){  //testa quebra pagina
               $pdf->addpage("L");
  	       $imprime=true;
       }
       if ($imprime == true){  // header
          $pdf->setX(10);
          $pdf->Cell(20,4,'COD.LANC',"B",0,"R",0);
          $pdf->Cell(20,4,'DATA',"B",0,"C",0);
	  $pdf->Cell(40,4,'HIST',"B",0,"L",0);
          $pdf->Cell(20,4,'C.DEBITO',"B",0,"R",0);
          $pdf->Cell(55,4,'DESCR',"B",0,"L",0);
          $pdf->Cell(20,4,'C.CREDITO',"B",0,"R",0);
          $pdf->Cell(55,4,'DESCR',"B",0,"L",0);
          $pdf->Cell(30,4,'VALOR',"B",1,"R",0);
	  $imprime = false;
	  $preen = 1;
        } 
        if($preen == 1)
	  $preen = 0;
	else
	  $preen = 1;
        $pdf->SetFont('arial','',7);
        $pdf->setX(10);
        $pdf->Cell(20,4,$c69_codlan,"0",0,"R",$preen);
        $pdf->Cell(20,4,$c69_data,"0",0,"C",$preen);
        $pdf->Cell(40,4,$c50_descr,"0",0,"L",$preen);
        $pdf->Cell(20,4,$c69_debito,"0",0,"R",$preen);
        $pdf->Cell(55,4,substr($debito_descr,0,50),"0",0,"L",$preen);
        $pdf->Cell(20,4,$c69_credito,"0",0,"R",$preen);
        $pdf->Cell(55,4,substr($credito_descr,0,50),"0",0,"L",$preen);
        $pdf->Cell(30,4,$c69_valor,"0",1,"R",$preen);
	if ($c72_complem != ''){
           $pdf->multicell(260,4,"Complemento :  ".$c72_complem,0,"L",$preen);
	}
   }
   //////
 

// $tmpfile=tempnam("tmp","tmp.pdf");

 $pdf->Output();


?>