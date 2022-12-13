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
 include("classes/db_orcsuplem_classe.php");
 include("libs/db_liborcamento.php");
 
 $auxiliar = new cl_orcsuplem;
 $anousu = db_getsession("DB_anousu");
 $instit = db_getsession("DB_instit");

 parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

 if(isset($processados)){

   $sql = "select distinct o39_codproj,
                 o39_data,
                 o39_numero,
		 o39_descr,
		 o49_data as data_proc,
		 orcsuplemlan.o49_id_usuario
          from orcprojeto
               inner join orclei on o45_codlei = o39_codlei
	       left outer join orcsuplem on o46_codlei =o39_codproj
               left outer join orcsuplemlan on o49_codsup = o46_codsup
          where 
	      orcprojeto.o39_data between '$dt_ini' and '$dt_fim'
	  ";
       if (isset($codlei) && ($codlei!="")) 
	      $sql.=" and o45_codlei = $codlei   ";
       
       $sql .=" order by data_proc ";


  }else{
    $sql = "select distinct o39_codproj,
                 o39_data,
                 o39_numero,
		 o39_descr,
		 o49_data as data_proc,
		 orcsuplemlan.o49_id_usuario
          from orcprojeto
               inner join orclei on o45_codlei = o39_codlei
	       left outer join orcsuplem on o46_codlei =o39_codproj
               left outer join orcsuplemlan on o49_codsup = o46_codsup
          where 
	      orcsuplemlan.o49_data between '$dt_ini' and '$dt_fim'
	  ";
       if (isset($codlei) && ($codlei!="")) 
	      $sql.=" and o45_codlei = $codlei   ";

       $sql .=" order by data_proc ";


  }
  $res = $auxiliar->sql_record($sql); 
  if ($auxiliar->numrows ==0){
      db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ');       
  }
  //////////////////////////////////


  //////////////////////////////////
  $head4 = "Relatorio de Projetos";
  $perini= split("-",$dt_ini);
  $perfim= split("-",$dt_fim);
  $head5 = "PERIODO : $perini[2]/$perini[1]/$perini[0]  à  $perfim[2]/$perfim[1]$perfim[0]";
   
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->SetFillColor(235);
  $pdf->SetFont('Arial','',9);
  $pdf->setY(40);
  $codigo="";
  $pagina=1;
  for ($x=0; $x< $auxiliar->numrows ; $x++){ // loop nos projetos
        db_fieldsmemory($res,$x,true);
       
        if ($pdf->gety() > $pdf->h - 30 || $pagina == 1 ){
	    $pagina=0;
            $pdf->addpage();
            $pdf->setfont('arial','',9);
	    $pdf->Ln();
            $pdf->setX(10);
	    $pdf->Cell(15,4,"PROJ",'1',0,"L",'1');    
            $pdf->Cell(30,4,"PROCESAMENTO",'1',0,"L",'1');     
            $pdf->Cell(45,4,"DECRETO/LEI",'1',0,"L",'1');  
	    $pdf->Cell(90,4,"DECRIÇÂO",'1',1,"L",'1');  

            $pdf->Ln();
        }
  	
        $pdf->setX(10);
        $pdf->Cell(15,4,"$o39_codproj",'T',0,"R",'0');    
        $pdf->Cell(30,4,"$data_proc",'T',0,"C",'0');    
        $pdf->Cell(45,4,"$o39_numero/$o39_data",'T',0,"L",'0');
        $pdf->Cell(90,4,substr($o39_descr,0,45),'T',1,"L",'0');       
        /////// -----	
        $suplem   =0;  
	$reduz    =0;  
	$total_suplem=0;
	$total_reduz=0;
	$total = 0 ;
        //----	
        $sql_suplem = "
	        select orcsuplem.o46_codlei,o47_coddot as dotsul,o47_valor as suplem 
                from orcsuplemval
		   inner join orcsuplem on o46_codsup =o47_codsup
		          and orcsuplem.o46_codlei = $o39_codproj
	           inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup		  
		                        and o48_coddocsup > 0
                 where o47_valor > 0   
               ";  	 
	 $result_suplem = pg_exec($sql_suplem);
	 
         $sql_reduz = "select orcsuplem.o46_codlei,o47_coddot as dotred,o47_valor as reduz
	        from orcsuplemval
		   inner join orcsuplem on o46_codsup =o47_codsup
		          and orcsuplem.o46_codlei = $o39_codproj
	           inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup		  
		                        and o48_coddocred > 0
                 where o47_valor < 0   
               ";  	 
	 $result_reduz = pg_exec($sql_reduz);
	 
         $sql_super = "select orcsuplem.o46_codlei,o47_coddot, o47_valor 
	        from orcsuplemval
		   inner join orcsuplem on o46_codsup =o47_codsup
		          and orcsuplem.o46_codlei = $o39_codproj
	           inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup		  
		                        and o48_superavit ='t'
               ";  	 
	 $result_superavit = pg_exec($sql_super);


         //---

	 if (pg_numrows($result_suplem)!=0){
	   $pdf->Cell(50,4,"",'0',0,"L",'0'); 
	   $pdf->Cell(130,4,"Suplementações",'B',1,"L",'0'); 
	   for ($yyy=0;$yyy<pg_numrows($result_suplem);$yyy++){
	     db_fieldsmemory($result_suplem,$yyy);	  	     
	     if ($pdf->gety() > $pdf->h - 30 || $pagina == 1 ){
		$pagina=0;
		$pdf->addpage();
		$pdf->setfont('arial','',9);
		$pdf->Ln();
		$pdf->setX(10);
		$pdf->Cell(15,4,"PROJ",'1',0,"L",'1');    
		$pdf->Cell(30,4,"PROCESAMENTO",'1',0,"L",'1');     
		$pdf->Cell(45,4,"DECRETO/LEI",'1',0,"L",'1');  
		$pdf->Cell(90,4,"DECRIÇÂO",'1',1,"L",'1');  

		$pdf->Ln();
	     }
	     $pdf->Cell(50,4,"",'0',0,"L",'0'); 
	     $pdf->Cell(25,4,$dotsul,'0',0,"L",'0');  
	     $pdf->Cell(80,4,"",'0',0,"L",'0'); 
	     $pdf->Cell(25,4,db_formatar($suplem,'f'),'0',1,"R",'0');  
	     $total_suplem += $suplem;
	   }
	   $pdf->Cell(50,4,"",'0',0,"L",'0'); 
	   $pdf->Cell(130,4,"Total:".db_formatar($total_suplem,'f'),'T',1,"R",'0'); 
	 }
	 if (pg_numrows($result_reduz)!=0){
	   $pdf->Cell(50,4,"",'0',0,"L",'0'); 
	   $pdf->Cell(130,4,"Redução",'B',1,"L",'0'); 
	   for ($yy=0;$yy < pg_numrows($result_reduz);$yy++){
	     db_fieldsmemory($result_reduz,$yy);	   
	     if ($pdf->gety() > $pdf->h - 30 || $pagina == 1 ){
		$pagina=0;
		$pdf->addpage();
		$pdf->setfont('arial','',9);
		$pdf->Ln();
		$pdf->setX(10);
		$pdf->Cell(15,4,"PROJ",'1',0,"L",'1');    
		$pdf->Cell(30,4,"PROCESAMENTO",'1',0,"L",'1');     
		$pdf->Cell(45,4,"DECRETO/LEI",'1',0,"L",'1');  
		$pdf->Cell(90,4,"DECRIÇÂO",'1',1,"L",'1');  

		$pdf->Ln();
	     }
	     $pdf->Cell(50,"","",'0',0,"L",'0'); 
	     $pdf->Cell(25,4,$dotred,'0',0,"L",'0');  
	     $pdf->Cell(80,"","",'0',0,"L",'0'); 
	     $pdf->Cell(25,4,db_formatar($reduz,'f'),'0',1,"R",'0');  
	     $total_reduz += $reduz;
	   }
	   $pdf->Cell(50,4,"",'0',0,"L",'0'); 
	   $pdf->Cell(130,4,"Total:".db_formatar($total_reduz,'f'),'T',1,"R",'0'); 
	 }
	 
         if (pg_numrows($result_superavit)!=0){
	    $pdf->Cell(50,4,"",'0',0,"L",'0'); 
	    $pdf->Cell(130,4,"Superávit",'B',1,"L",'0'); 
	    $total = 0; 
	    for ($yy=0;$yy < pg_numrows($result_superavit);$yy++){
	       db_fieldsmemory($result_superavit,$yy);	   
	       if ($pdf->gety() > $pdf->h - 30 || $pagina == 1 ){
	  	  $pagina=0;
		  $pdf->addpage();
		  $pdf->setfont('arial','',9);
		  $pdf->Ln();
		  $pdf->setX(10);
		  $pdf->Cell(15,4,"PROJ",'1',0,"L",'1');    
		  $pdf->Cell(30,4,"PROCESAMENTO",'1',0,"L",'1');     
		  $pdf->Cell(45,4,"DECRETO/LEI",'1',0,"L",'1');  
		  $pdf->Cell(90,4,"DECRIÇÂO",'1',1,"L",'1');  
 	  	  $pdf->Ln();
	       }
	       $pdf->Cell(50,"","",'0',0,"L",'0'); 
	       $pdf->Cell(25,4,$o47_coddot,'0',0,"L",'0');  
	       $pdf->Cell(80,"","",'0',0,"L",'0'); 
	       $pdf->Cell(25,4,db_formatar($o47_valor,'f'),'0',1,"R",'0');  
	       $total += $o47_valor;
	    }
	    $pdf->Cell(50,4,"",'0',0,"L",'0'); 
	    $pdf->Cell(130,4,"Total:".db_formatar($total,'f'),'T',1,"R",'0'); 
	 }


       
        $pdf->Ln();
	
  }
 $pdf->Output();

?>