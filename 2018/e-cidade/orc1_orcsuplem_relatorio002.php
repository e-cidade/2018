<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
 include("classes/db_db_config_classe.php");
 include("classes/db_db_paragrafo_classe.php");
 
 $cldbconfig = new cl_db_config;
 $cldbparagrafo = new cl_db_paragrafo;
 $auxiliar = new cl_orcsuplem;
 $aux = new cl_orcsuplem;


   $head1 = "Relatorio do Projeto de Lei";
   $head3 = "";
   $head4 = "";
   $head5 = "";
   $head6 = "";
   $head7 = "";
   $head8 = "";
   $head9 = ""; 
   $pdf = new PDF();
   $pdf->Open();
   $pdf->AliasNbPages();
   $pdf->AddPage("P");
   // monta cabecalho do relatório    
   $pdf->SetFillColor(235);
   $pdf->SetFont('Courier','B',9);
   $pdf->setY(40);
   $pdf->setX(5);

   //- seleciona projeto
  $o46_codlei = (isset($o46_codlei)&&!empty($o46_codlei))?$o46_codlei:'null';
  $sql=" select  o39_codproj,
                 o39_descr,
	         o38_descr,
		 sum(o47_valor) as valor1
         from orcprojeto
                inner join orctipoproj on o38_tipoproj = o39_tipoproj
                inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
                inner join orcsuplemval on o47_codsup = o46_codsup
         where orcprojeto.o39_codproj=$o46_codlei
	    and orcsuplemval.o47_valor >0
         group by o39_codproj,o39_descr,o38_descr ";
   $res = $auxiliar->sql_record($sql); 
   if ($auxiliar->numrows > 0 ){
         db_fieldsmemory($res,0);
	  $pdf->setX(5);
	  $pdf->Cell(195,4,"$o38_descr",0,0,'C','0');
          $pdf->Ln(14);
   } else {
      echo " manda hp erro !";
      exit;
   }  
   //-----
   $valor = db_formatar($valor1,'f');
   $txt="$o39_descr  na importancia de R$ $valor (".db_extenso($valor1,true).") e da outras providências. ";
   $pdf->setX(100);
   $pdf->multicell(100,4,$txt,'0','J','0',20); 
   $pdf->Ln();

   //------ prefeito
   $res= $cldbconfig->sql_record($cldbconfig->sql_query());
   db_fieldsmemory($res,0);
   $pdf->setX(10);
   $pref = strtoupper($pref);
   $txt="$pref, PREFEITO MUNICIPAL DE $munic, $uf  ";
   $pdf->multicell(180,4,$txt,'0','J','0');
   $pdf->Ln();
   //-- paragrafo "faco saber, id 49 
   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(49,"db02_texto"));
   if ($cldbparagrafo->numrows > 0 ){
         db_fieldsmemory($pr,0);   
         $txt="$db02_texto";
         $pdf->multicell(180,4,$txt,'0','J','0');
         $pdf->Ln();    
   }  else {
         echo "paragrafo 49 faltando";
         exit;
   }  
   //-------- suplementações e artigos 
   // até tipo 1005, paragrafo 1, até tipo 1012 paragrafo2 , depos um paragrafo para cada tipo
   $sql = "select orcsuplem.o46_codsup,
                  orcsuplem.o46_tiposup 
	  from orcsuplem
	      inner join orcsuplemval  on o47_codsup = o46_codsup
	      inner join orcprojeto on o39_codproj = o46_codlei and o39_codproj=$o46_codlei
          where orcsuplemval.o47_valor > 0
	  order by orcsuplem.o46_tiposup ";
   $res= $auxiliar->sql_record($sql); 
   $artigo = array();
   $artigo_count=1;
   for ($x=0;$x< $auxiliar->numrows ;$x++){
       db_fieldsmemory($res,$x);
       if ($o46_tiposup <= 1005){
  	     /* begin */
              if (!in_array("1005",$artigo)){
  	           $pdf->Ln(7);
		   $sql_r="select sum(o47_valor) as valor
		           from orcsuplemval 
                              inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
			      and orcsuplem.o46_codlei = $o46_codlei
			   where o47_valor > 0 ";
	           $pr = $aux->sql_record($sql_r);
		   if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
		   }
                   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(50,"db02_texto"));
                   if ($cldbparagrafo->numrows > 0 ){
                         db_fieldsmemory($pr,0);   
                         $txt="Art $artigo_count. -  $db02_texto $importancia (".db_extenso($valor,true).") sob a seguinte classificacao :";
                         $pdf->multicell(180,4,$txt,'0','J','0',20);
                   } else {
                         echo "paragrafo 50 faltando";
                         exit;
                   }   
   	           $artigo[$artigo_count]=1005;
		   $artigo_count += 1;
	           $pdf->Ln(7);
                   $pdf->setX(20);
                   $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
	           $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	           $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      
	      }
              $sql_r="select  o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      o47_valor 
	              from orcsuplemval
	                  inner join orcsuplem on o46_codsup = o47_codsup and o46_codsup=$o46_codsup
		       where  o47_valor > 0 ";
              $r=$aux->sql_record($sql_r);
              for($y=0; $y < $aux->numrows;$y++){
	          db_fieldsmemory($r,$y,true);		  
                  $pdf->setX(20);
                  // $pdf->Cell(30,4,$o47_coddot,0,0,"C",'0');   
	          // $pdf->Cell(90,4,$o50_estrutdespesa,0,0,"C",'0');	    
		  db_query("BEGIN");
                  $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		  db_query("ROLLBACK");
                  if(pg_numrows($r_dot)>0){
                      db_fieldsmemory($r_dot,0,true);
                      $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                      $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                      $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                      $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	      $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                      // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                  }else{
                        echo "Dotação não cadastrada.";
                        exit;
                  } 
		  $pdf->Ln();   
	      }
	     /* end */
       } else if ($o46_tiposup <= 1010){
  	     /* begin */
              if (!in_array("1010",$artigo)){
  	           $pdf->Ln(7);
		   $sql_r="select sum(o47_valor) as valor
		           from orcsuplemval 
                              inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1006 and o46_tiposup <= 1010
			      and orcsuplem.o46_codlei = $o46_codlei
			   where o47_valor > 0 ";
	           $pr = $aux->sql_record($sql_r);
		   if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
		   }
                   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(51,"db02_texto"));
                   if ($cldbparagrafo->numrows > 0 ){
                         db_fieldsmemory($pr,0);   
                         $txt="Art $artigo_count. -  $db02_texto $importancia (".db_extenso($valor,true).") sob a seguinte classificacao :";
                         $pdf->multicell(180,4,$txt,'0','J','0',20);
                   } else {
                         echo "paragrafo 51 faltando";
                         exit;
                   }   
   	           $artigo[$artigo_count]=1010;
   	           $artigo_count +=1;
	           $pdf->Ln(7);
                   $pdf->setX(20);
                   $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
	           $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	           $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      
	      }
              $sql_r="select  o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      o47_valor 
	              from orcsuplemval
	                  inner join orcsuplem on o46_codsup = o47_codsup and o46_codsup=$o46_codsup
		       where  o47_valor > 0 ";
              $r=$aux->sql_record($sql_r);
              for($y=0; $y < $aux->numrows;$y++){
	          db_fieldsmemory($r,$y,true);		  
                  $pdf->setX(20);
                  // $pdf->Cell(30,4,$o47_coddot,0,0,"C",'0');   
	          // $pdf->Cell(90,4,$o50_estrutdespesa,0,0,"C",'0');	    
		  db_query("BEGIN");
                  $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		  db_query("ROLLBACK");
                  if(pg_numrows($r_dot)>0){
                      db_fieldsmemory($r_dot,0,true);
                      $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                      $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                      $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                      $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	      $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                      // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                  }else{
                        echo "Dotação não cadastrada.";
                        exit;
                  } 
		  $pdf->Ln();   
	      }
	     /* end */
       } else if ($o46_tiposup = 1011 ){
     	     /* begin */
              if (!in_array("1011",$artigo)){
  	           $pdf->Ln(7);
		   $sql_r="select sum(o47_valor) as valor
		           from orcsuplemval 
                              inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1011
			      and orcsuplem.o46_codlei = $o46_codlei
			   where o47_valor > 0 ";
	           $pr = $aux->sql_record($sql_r);
		   if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
		   }
                   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(52,"db02_texto"));
                   if ($cldbparagrafo->numrows > 0 ){
                         db_fieldsmemory($pr,0);   
                         $txt="Art $artigo_count. -  $db02_texto $importancia (".db_extenso($valor,true).") sob a seguinte classificacao :";
                         $pdf->multicell(180,4,$txt,'0','J','0',20);
                   } else {
                         echo "paragrafo 52 faltando";
                         exit;
                   }   
   	           $artigo[$artigo_count]=1011;
 		   $artigo_count +=1;
	           $pdf->Ln(7);
                   $pdf->setX(20);
                   $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
	           $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	           $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      
	      }
              $sql_r="select  o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      o47_valor 
	              from orcsuplemval
	                  inner join orcsuplem on o46_codsup = o47_codsup and o46_codsup=$o46_codsup
		       where  o47_valor > 0 ";
              $r=$aux->sql_record($sql_r);
              for($y=0; $y < $aux->numrows;$y++){
	          db_fieldsmemory($r,$y,true);		  
                  $pdf->setX(20);
                  // $pdf->Cell(30,4,$o47_coddot,0,0,"C",'0');   
	          // $pdf->Cell(90,4,$o50_estrutdespesa,0,0,"C",'0');	    
		  db_query("BEGIN");
                  $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		  db_query("ROLLBACK");
                  if(pg_numrows($r_dot)>0){
                      db_fieldsmemory($r_dot,0,true);
                      $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                      $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                      $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                      $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	      $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                      // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                  }else{
                        echo "Dotação não cadastrada.";
                        exit;
                  } 
		  $pdf->Ln();   
	      }
	     /* end */ 
       } else if ($o46_tiposup = 1012 ){
              /* begin */
              if (!in_array("1012",$artigo)){
  	           $pdf->Ln(7);
		   $sql_r="select sum(o47_valor) as valor
		           from orcsuplemval 
                              inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1012
			      and orcsuplem.o46_codlei = $o46_codlei
			   where o47_valor > 0 ";
	           $pr = $aux->sql_record($sql_r);
		   if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
		   }
                   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(53,"db02_texto"));
                   if ($cldbparagrafo->numrows > 0 ){
                         db_fieldsmemory($pr,0);   
                         $txt="Art $artigo_count. -  $db02_texto $importancia (".db_extenso($valor,true).") sob a seguinte classificacao :";
                         $pdf->multicell(180,4,$txt,'0','J','0',20);
                   } else {
                         echo "paragrafo 53 faltando";
                         exit;
                   }   
		   $artigo[$artigo_count]=1012;
		   $artigo_count +=1;
	           $pdf->Ln(7);
                   $pdf->setX(20);
                   $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
	           $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	           $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      
	      }
              $sql_r="select  o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      o47_valor 
	              from orcsuplemval
	                  inner join orcsuplem on o46_codsup = o47_codsup and o46_codsup=$o46_codsup
		       where  o47_valor > 0 ";
              $r=$aux->sql_record($sql_r);
              for($y=0; $y < $aux->numrows;$y++){
	          db_fieldsmemory($r,$y,true);		  
                  $pdf->setX(20);
                  // $pdf->Cell(30,4,$o47_coddot,0,0,"C",'0');   
	          // $pdf->Cell(90,4,$o50_estrutdespesa,0,0,"C",'0');	    
		  db_query("BEGIN");
                  $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		  db_query("ROLLBACK");
                  if(pg_numrows($r_dot)>0){
                      db_fieldsmemory($r_dot,0,true);
                      $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                      $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                      $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                      $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	      $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                      // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                  }else{
                        echo "Dotação não cadastrada.";
                        exit;
                  } 
		  $pdf->Ln();   
	      }
            /* end */	   
       } else if ($o46_tiposup = 1013 ){
	     /* begin */
              if (!in_array("1013",$artigo)){
  	           $pdf->Ln(7);
		   $sql_r="select sum(o47_valor) as valor
		           from orcsuplemval 
                              inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1013
			      and orcsuplem.o46_codlei = $o46_codlei
			   where o47_valor > 0 ";
	           $pr = $aux->sql_record($sql_r);
		   if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
		   }
                   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(54,"db02_texto"));
                   if ($cldbparagrafo->numrows > 0 ){
                         db_fieldsmemory($pr,0);   
                         $txt="Art $artigo_count. -  $db02_texto $importancia (".db_extenso($valor,true).") sob a seguinte classificacao :";
                         $pdf->multicell(180,4,$txt,'0','J','0',20);
	                   } else {
                         echo "paragrafo 54 faltando";
                         exit;
                   }   
   	           $artigo[$artigo_count]=1013;
		   $artigo_count +=1;
	           $pdf->Ln(7);
                   $pdf->setX(20);
                   $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
	           $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	           $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      
	      }
              $sql_r="select  o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      o47_valor 
	              from orcsuplemval
	                  inner join orcsuplem on o46_codsup = o47_codsup and o46_codsup=$o46_codsup
		       where  o47_valor > 0 ";
              $r=$aux->sql_record($sql_r);
              for($y=0; $y < $aux->numrows;$y++){
	          db_fieldsmemory($r,$y,true);		  
                  $pdf->setX(20);
                  // $pdf->Cell(30,4,$o47_coddot,0,0,"C",'0');   
	          // $pdf->Cell(90,4,$o50_estrutdespesa,0,0,"C",'0');	    
		  db_query("BEGIN");
                  $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		  db_query("ROLLBACK");
                  if(pg_numrows($r_dot)>0){
                      db_fieldsmemory($r_dot,0,true);
                      $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                      $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                      $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                      $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	      $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                      // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                  }else{
                        echo "Dotação não cadastrada.";
                        exit;
                  } 
		  $pdf->Ln();   
	      }
            /* end */	    
       } else if ($o46_tiposup = 1014 ){
	     /* begin */
              if (!in_array("1014",$artigo)){
  	           $pdf->Ln(7);
		   $sql_r="select sum(o47_valor) as valor
		           from orcsuplemval 
                              inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1014
			      and orcsuplem.o46_codlei = $o46_codlei
			   where o47_valor > 0 ";
	           $pr = $aux->sql_record($sql_r);
		   if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
		   }
                   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(55,"db02_texto"));
                   if ($cldbparagrafo->numrows > 0 ){
                         db_fieldsmemory($pr,0);   
                         $txt="Art $artigo_count. -  $db02_texto $importancia (".db_extenso($valor,true).") sob a seguinte classificacao :";
                         $pdf->multicell(180,4,$txt,'0','J','0',20);
                   } else {
                         echo "paragrafo 55 faltando";
                         exit;
                   }   
   	           $artigo[$artigo_count]=1014;
		   $artigo_count +=1;
	           $pdf->Ln(7);
                   $pdf->setX(20);
                   $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
	           $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	           $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      
	      }
              $sql_r="select  o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      o47_valor 
	              from orcsuplemval
	                  inner join orcsuplem on o46_codsup = o47_codsup and o46_codsup=$o46_codsup
		       where  o47_valor > 0 ";
              $r=$aux->sql_record($sql_r);
              for($y=0; $y < $aux->numrows;$y++){
	          db_fieldsmemory($r,$y,true);		  
                  $pdf->setX(20);
                  // $pdf->Cell(30,4,$o47_coddot,0,0,"C",'0');   
	          // $pdf->Cell(90,4,$o50_estrutdespesa,0,0,"C",'0');	    
		  db_query("BEGIN");
                  $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		  db_query("ROLLBACK");
                  if(pg_numrows($r_dot)>0){
                      db_fieldsmemory($r_dot,0,true);
                      $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                      $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                      $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                      $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	      $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                      // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                  }else{
                        echo "Dotação não cadastrada.";
                        exit;
                  } 
		  $pdf->Ln();   
	      }
            /* end */	    
       } else if ($o46_tiposup = 1015 ){
	      /* begin */
              if (!in_array("1015",$artigo)){
  	           $pdf->Ln(7);
		   $sql_r="select sum(o47_valor) as valor
		           from orcsuplemval 
                              inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1015
			      and orcsuplem.o46_codlei = $o46_codlei
			   where o47_valor > 0 ";
	           $pr = $aux->sql_record($sql_r);
		   if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
		   }
                   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(56,"db02_texto"));
                   if ($cldbparagrafo->numrows > 0 ){
                         db_fieldsmemory($pr,0);   
                         $txt="Art $artigo_count. -  $db02_texto $importancia (".db_extenso($valor,true).") sob a seguinte classificacao :";
                         $pdf->multicell(180,4,$txt,'0','J','0',20);
                   } else {
                         echo "paragrafo 56 faltando";
                         exit;
                   }   
   	           $artigo[$artigo_count]=1015;
		   $artigo_count +=1;
	           $pdf->Ln(7);
                   $pdf->setX(20);
                   $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
	           $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	           $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      
	      }
              $sql_r="select  o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      o47_valor 
	              from orcsuplemval
	                  inner join orcsuplem on o46_codsup = o47_codsup and o46_codsup=$o46_codsup
		       where  o47_valor > 0 ";
              $r=$aux->sql_record($sql_r);
              for($y=0; $y < $aux->numrows;$y++){
	          db_fieldsmemory($r,$y,true);		  
                  $pdf->setX(20);
                  // $pdf->Cell(30,4,$o47_coddot,0,0,"C",'0');   
	          // $pdf->Cell(90,4,$o50_estrutdespesa,0,0,"C",'0');	    
		  db_query("BEGIN");
                  $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		  db_query("ROLLBACK");
                  if(pg_numrows($r_dot)>0){
                      db_fieldsmemory($r_dot,0,true);
                      $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                      $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                      $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                      $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	      $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                      // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                  }else{
                        echo "Dotação não cadastrada.";
                        exit;
                  } 
		  $pdf->Ln();   
	      }
            /* end */	    
       } else if ($o46_tiposup = 1016 ){
	      /* begin */
              if (!in_array("1016",$artigo)){
  	           $pdf->Ln(7);
		   $sql_r="select sum(o47_valor) as valor
		           from orcsuplemval 
                              inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1016
			      and orcsuplem.o46_codlei = $o46_codlei
			   where o47_valor > 0 ";
	           $pr = $aux->sql_record($sql_r);
		   if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
		   }
                   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(57,"db02_texto"));
                   if ($cldbparagrafo->numrows > 0 ){
                         db_fieldsmemory($pr,0);   
                         $txt="Art $artigo_count. -  $db02_texto $importancia (".db_extenso($valor,true).") sob a seguinte classificacao :";
                         $pdf->multicell(180,4,$txt,'0','J','0',20);
                   } else {
                         echo "paragrafo 57 faltando";
                         exit;
                   }   
   	           $artigo[$artigo_count]=1016;
		   $artigo_count +=1;
	           $pdf->Ln(7);
                   $pdf->setX(20);
                   $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
	           $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	           $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      
	      }
              $sql_r="select  o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      o47_valor 
	              from orcsuplemval
	                  inner join orcsuplem on o46_codsup = o47_codsup and o46_codsup=$o46_codsup
		       where  o47_valor > 0 ";
              $r=$aux->sql_record($sql_r);
              for($y=0; $y < $aux->numrows;$y++){
	          db_fieldsmemory($r,$y,true);		  
                  $pdf->setX(20);
                  // $pdf->Cell(30,4,$o47_coddot,0,0,"C",'0');   
	          // $pdf->Cell(90,4,$o50_estrutdespesa,0,0,"C",'0');	    
		  db_query("BEGIN");
                  $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		  db_query("ROLLBACK");
                  if(pg_numrows($r_dot)>0){
                      db_fieldsmemory($r_dot,0,true);
                      $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                      $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                      $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                      $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	      $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                      // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                  }else{
                        echo "Dotação não cadastrada.";
                        exit;
                  } 
		  $pdf->Ln();   
	      }
            /* end */	    
       } else {
             echo "tipo de suplementação inválido ! ";
	     exit;
       }; // end chave do if	    
   }; // end sql	   

  
  //--------
  //--------  Reduções  e artigos 
   if (in_array("1005",$artigo)){
               /* begin */
	       /*
               $sql_r="select sum(o47_valor) as valor
	               from orcsuplemval 
                          inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                    and orcsuplem.o46_codlei = $o46_codlei
	               where o47_valor < 0 ";
               $pr = $aux->sql_record($sql_r);
	       if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
	       } else {
                  break;
	       }*/	
               $pdf->Ln(7);
	       $art =  array_search('1005',$artigo);
               $txt="Art $artigo_count. - Para cobertura da(s) Suplementações aberto(as) no artigo número $art";
	       $txt .=" será usado como recurso(s) o(s) íten(s) conforme classificação a seguir : ";
               $pdf->multicell(180,4,$txt,'0','J','0',20);
	       $artigo_count += 1; // reduções
               $sql_r="select o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
                              ( case when (o47_valor < 0) then ( o47_valor *-1)
                                else o47_valor
                                end)  as o47_valor,
			      o48_superavit
	              from orcsuplemval
                            inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                      and orcsuplem.o46_codlei = $o46_codlei
			    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	              where  o47_valor < 0 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln(7);	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     
		    for($y=0; $y < $aux->numrows;$y++){
	                db_fieldsmemory($r,$y,true);		  
                        $pdf->setX(20);
		        db_query("BEGIN");
                        $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		        db_query("ROLLBACK");
                        if(pg_numrows($r_dot)>0){
                             db_fieldsmemory($r_dot,0,true);
                             $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                             $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                             $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                             $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	             $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                             // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                        } else{
                             echo "Dotação não cadastrada.";
                             exit;
                        } 
		        $pdf->Ln();
	            } // end for
                    // begin superavit
		    for($y=0; $y < $aux->numrows;$y++){  //repete o for
	                db_fieldsmemory($r,$y,true);		  
                        if ($o48_superavit =="t"){ // begin if "t"
			    if (!isset($kbd)){ // variavel qualquer
			         $kbd = 1;  //seta com qualquer valor
                                 $pdf->Ln(7);	       
                                 $pdf->setX(20);
                                 $pdf->Cell(30,4,"Codigo",0,0,"L",'0');   
                                 $pdf->Cell(90,4,"Recurso",0,0,"L",'0');	    
	                         $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
			    }  
			    $pdf->setX(20);
		            db_query("BEGIN");
                            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		            db_query("ROLLBACK");
                            if(pg_numrows($r_dot)>0){
                               db_fieldsmemory($r_dot,0,true);
                               $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
	  	               $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
			    }   
                        } // end if "t"
		        $pdf->Ln();
                    }//end for
		    // end superavit
	      }	    
              $sql_r= "select *
	               from orcsuplem
			     inner join orcsuplemrec on o85_codsup = o46_codsup
	                     inner join orcreceita   on o70_codrec = o85_codrec  and o70_anousu =o85_anousu
			     inner join orcfontes    on o57_codfon = orcreceita.o70_codfon and o57_anousu = orcreceita.o70_anousu
		       where orcsuplem.o46_codlei= $o46_codlei
			      and  o46_tiposup >= 1001 and o46_tiposup <= 1005
	               ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln();	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Receita",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Estrutural",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
	            for($y=0; $y < $aux->numrows;$y++){
	                 db_fieldsmemory($r,$y,true);
                         $pdf->setX(20);
                         $pdf->Cell(150,4,"$o85_codrec - $o57_fonte",0,1,"L",'0');  $pdf->setX(20);   
                         $pdf->Cell(120,4,"$o57_descr              ",0,0,"L",'0');     
                         $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  $pdf->setX(20);   
	            }		 
              } // end for
	      // end receitas
   } else if (in_array("1010",$artigo)){
               /* begin */
	       /*
               $sql_r="select sum(o47_valor) as valor
	               from orcsuplemval 
                          inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                    and orcsuplem.o46_codlei = $o46_codlei
	               where o47_valor < 0 ";
               $pr = $aux->sql_record($sql_r);
	       if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
	       } else {
                  break;
	       }*/	
               $pdf->Ln(7);
	       $art =  array_search('1010',$artigo);
               $txt="Art $artigo_count. - Para cobertura do(s) Credito(s) Especial aberto(as) no artigo número $art";
	       $txt .=" será usado como recurso(s) o(s) íten(s) conforme classificação a seguir : ";
               $pdf->multicell(180,4,$txt,'0','J','0',20);
               $artigo_count += 1;
	       // reduções
               $sql_r="select o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
                              ( case when (o47_valor < 0) then ( o47_valor *-1)
                                else o47_valor
                                end)  as o47_valor,
			      o48_superavit
	              from orcsuplemval
                            inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup <= 1010
	                      and orcsuplem.o46_codlei = $o46_codlei
			    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	              where  o47_valor < 0 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln(7);	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     
		    for($y=0; $y < $aux->numrows;$y++){
	                db_fieldsmemory($r,$y,true);		  
                        $pdf->setX(20);
		        db_query("BEGIN");
                        $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		        db_query("ROLLBACK");
                        if(pg_numrows($r_dot)>0){
                             db_fieldsmemory($r_dot,0,true);
                             $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                             $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                             $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                             $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	             $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                             // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                        } else{
                             echo "Dotação não cadastrada.";
                             exit;
                        } 
		        $pdf->Ln();
	            } // end for
                    // begin superavit
		    for($y=0; $y < $aux->numrows;$y++){  //repete o for
	                db_fieldsmemory($r,$y,true);		  
                        if ($o48_superavit =="t"){ // begin if "t"
			    if (!isset($kbd)){ // variavel qualquer
			         $kbd = 1;  //seta com qualquer valor
                                 $pdf->Ln(7);	       
                                 $pdf->setX(20);
                                 $pdf->Cell(30,4,"Codigo",0,0,"L",'0');   
                                 $pdf->Cell(90,4,"Recurso",0,0,"L",'0');	    
	                         $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
			    }  
			    $pdf->setX(20);
		            db_query("BEGIN");
                            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		            db_query("ROLLBACK");
                            if(pg_numrows($r_dot)>0){
                               db_fieldsmemory($r_dot,0,true);
                               $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
	  	               $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
			    }   
                        } // end if "t"
		        $pdf->Ln();
                    }//end for
		    // end superavit
	      }	    
              $sql_r= "select *
	               from orcsuplem
			     inner join orcsuplemrec on o85_codsup = o46_codsup
	                     inner join orcreceita   on o70_codrec = o85_codrec  and o70_anousu =o85_anousu
			     inner join orcfontes    on o57_codfon = orcreceita.o70_codfon and o57_anousu = orcreceita.o70_anousu
		       where orcsuplem.o46_codlei= $o46_codlei
			      and  o46_tiposup <= 1010
	               ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln();	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Receita",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Estrutural",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
	            for($y=0; $y < $aux->numrows;$y++){
	                 db_fieldsmemory($r,$y,true);
                         $pdf->setX(20);
                         $pdf->Cell(150,4,"$o85_codrec - $o57_fonte",0,1,"L",'0');  $pdf->setX(20);   
                         $pdf->Cell(120,4,"$o57_descr              ",0,0,"L",'0');     
                         $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  $pdf->setX(20);   
	            }		 
              } // end for
	      // end receitas
   } else if (in_array("1011",$artigo)){
               /* begin */
	       /*
               $sql_r="select sum(o47_valor) as valor
	               from orcsuplemval 
                          inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                    and orcsuplem.o46_codlei = $o46_codlei
	               where o47_valor < 0 ";
               $pr = $aux->sql_record($sql_r);
	       if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
	       } else {
                  break;
	       }*/	
               $pdf->Ln(7);
	       $art =  array_search('1011',$artigo);
               $txt="Art $artigo_count. - Para cobertura do(s) Credito(s) Extraordinario(s) aberto(as) no artigo número $art";
	       $txt .=" será usado como recurso(s) o(s) íten(s) conforme classificação a seguir : ";
               $pdf->multicell(180,4,$txt,'0','J','0',20);
	       $artigo_count += 1;  
	       // reduções
               $sql_r="select o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
                              ( case when (o47_valor < 0) then ( o47_valor *-1)
                                else o47_valor
                                end)  as o47_valor,
			      o48_superavit
	              from orcsuplemval
                            inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1011
	                      and orcsuplem.o46_codlei = $o46_codlei
			    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	              where  o47_valor < 0 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln(7);	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     
		    for($y=0; $y < $aux->numrows;$y++){
	                db_fieldsmemory($r,$y,true);		  
                        $pdf->setX(20);
		        db_query("BEGIN");
                        $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		        db_query("ROLLBACK");
                        if(pg_numrows($r_dot)>0){
                             db_fieldsmemory($r_dot,0,true);
                             $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                             $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                             $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                             $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	             $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                             // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                        } else{
                             echo "Dotação não cadastrada.";
                             exit;
                        } 
		        $pdf->Ln();
	            } // end for
                    // begin superavit
		    for($y=0; $y < $aux->numrows;$y++){  //repete o for
	                db_fieldsmemory($r,$y,true);		  
                        if ($o48_superavit =="t"){ // begin if "t"
			    if (!isset($kbd)){ // variavel qualquer
			         $kbd = 1;  //seta com qualquer valor
                                 $pdf->Ln(7);	       
                                 $pdf->setX(20);
                                 $pdf->Cell(30,4,"Codigo",0,0,"L",'0');   
                                 $pdf->Cell(90,4,"Recurso",0,0,"L",'0');	    
	                         $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
			    }  
			    $pdf->setX(20);
		            db_query("BEGIN");
                            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		            db_query("ROLLBACK");
                            if(pg_numrows($r_dot)>0){
                               db_fieldsmemory($r_dot,0,true);
                               $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
	  	               $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
			    }   
                        } // end if "t"
		        $pdf->Ln();
                    }//end for
		    // end superavit
	      }	    
              $sql_r= "select *
	               from orcsuplem
			     inner join orcsuplemrec on o85_codsup = o46_codsup
	                     inner join orcreceita   on o70_codrec = o85_codrec  and o70_anousu =o85_anousu
			     inner join orcfontes    on o57_codfon = orcreceita.o70_codfon and o57_anousu = orcreceita.o70_anousu
		       where orcsuplem.o46_codlei= $o46_codlei
			      and  o46_tiposup = 1011 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln();	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Receita",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Estrutural",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
	            for($y=0; $y < $aux->numrows;$y++){
	                 db_fieldsmemory($r,$y,true);
                         $pdf->setX(20);
                         $pdf->Cell(150,4,"$o85_codrec - $o57_fonte",0,1,"L",'0');  $pdf->setX(20);   
                         $pdf->Cell(120,4,"$o57_descr              ",0,0,"L",'0');     
                         $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  $pdf->setX(20);   
	            }		 
              } // end for
	      // end receitas
   } else if (in_array("1012",$artigo)){
               /* begin */
	       /*
               $sql_r="select sum(o47_valor) as valor
	               from orcsuplemval 
                          inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                    and orcsuplem.o46_codlei = $o46_codlei
	               where o47_valor < 0 ";
               $pr = $aux->sql_record($sql_r);
	       if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
	       } else {
                  break;
	       }*/	
               $pdf->Ln(7);
	       $art =  array_search('1012',$artigo);
               $txt="Art $artigo_count. - Para cobertura da(s) Reabertura de Credito(s) Especial aberto(as) no artigo número $art";
	       $txt .=" será usado como recurso(s) o(s) íten(s) conforme classificação a seguir : ";
               $pdf->multicell(180,4,$txt,'0','J','0',20);
	       $artigo_count += 1;
	       // reduções
               $sql_r="select o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
	                      ( case when (o47_valor < 0) then ( o47_valor *-1)
                                else o47_valor
                                end)  as o47_valor,
			      o48_superavit
	              from orcsuplemval
                            inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1012
	                      and orcsuplem.o46_codlei = $o46_codlei
			    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	              where  o47_valor < 0 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln(7);	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     
		    for($y=0; $y < $aux->numrows;$y++){
	                db_fieldsmemory($r,$y,true);		  
                        $pdf->setX(20);
		        db_query("BEGIN");
                        $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		        db_query("ROLLBACK");
                        if(pg_numrows($r_dot)>0){
                             db_fieldsmemory($r_dot,0,true);
                             $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                             $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                             $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                             $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	             $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                             // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                        } else{
                             echo "Dotação não cadastrada.";
                             exit;
                        } 
		        $pdf->Ln();
	            } // end for
                    // begin superavit
		    for($y=0; $y < $aux->numrows;$y++){  //repete o for
	                db_fieldsmemory($r,$y,true);		  
                        if ($o48_superavit =="t"){ // begin if "t"
			    if (!isset($kbd)){ // variavel qualquer
			         $kbd = 1;  //seta com qualquer valor
                                 $pdf->Ln(7);	       
                                 $pdf->setX(20);
                                 $pdf->Cell(30,4,"Codigo",0,0,"L",'0');   
                                 $pdf->Cell(90,4,"Recurso",0,0,"L",'0');	    
	                         $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
			    }  
			    $pdf->setX(20);
		            db_query("BEGIN");
                            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		            db_query("ROLLBACK");
                            if(pg_numrows($r_dot)>0){
                               db_fieldsmemory($r_dot,0,true);
                               $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
	  	               $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
			    }   
                        } // end if "t"
		        $pdf->Ln();
                    }//end for
		    // end superavit
	      }	    
              $sql_r= "select *
	               from orcsuplem
			     inner join orcsuplemrec on o85_codsup = o46_codsup
	                     inner join orcreceita   on o70_codrec = o85_codrec  and o70_anousu =o85_anousu
			     inner join orcfontes    on o57_codfon = orcreceita.o70_codfon and o57_anousu = orcreceita.o70_anousu
		       where orcsuplem.o46_codlei= $o46_codlei
			      and  o46_tiposup = 1012 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln();	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Receita",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Estrutural",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
	            for($y=0; $y < $aux->numrows;$y++){
	                 db_fieldsmemory($r,$y,true);
                         $pdf->setX(20);
                         $pdf->Cell(150,4,"$o85_codrec - $o57_fonte",0,1,"L",'0');  $pdf->setX(20);   
                         $pdf->Cell(120,4,"$o57_descr              ",0,0,"L",'0');     
                         $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  $pdf->setX(20);   
	            }		 
              } // end for
	      // end receitas
   } else if (in_array("1013",$artigo)){
               /* begin */
	       /*
               $sql_r="select sum(o47_valor) as valor
	               from orcsuplemval 
                          inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                    and orcsuplem.o46_codlei = $o46_codlei
	               where o47_valor < 0 ";
               $pr = $aux->sql_record($sql_r);
	       if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
	       } else {
                  break;
	       }*/	
               $pdf->Ln(7);
	       $art =  array_search('1013',$artigo);
               $txt="Art $artigo_count. - Para cobertura da(s) Reabertura de Credito(s) Extraordinario aberto(as) no artigo número $art";
	       $txt .=" será usado como recurso(s) o(s) íten(s) conforme classificação a seguir : ";
               $pdf->multicell(180,4,$txt,'0','J','0',20);
	       $artigo_count += 1;// reduções
               $sql_r="select o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
                              ( case when (o47_valor < 0) then ( o47_valor *-1)
                                else o47_valor
                                end)  as o47_valor,
			      o48_superavit
	              from orcsuplemval
                            inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1013
	                      and orcsuplem.o46_codlei = $o46_codlei
			    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	              where  o47_valor < 0 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln(7);	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     
		    for($y=0; $y < $aux->numrows;$y++){
	                db_fieldsmemory($r,$y,true);		  
                        $pdf->setX(20);
		        db_query("BEGIN");
                        $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		        db_query("ROLLBACK");
                        if(pg_numrows($r_dot)>0){
                             db_fieldsmemory($r_dot,0,true);
                             $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                             $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                             $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                             $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	             $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                             // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                        } else{
                             echo "Dotação não cadastrada.";
                             exit;
                        } 
		        $pdf->Ln();
	            } // end for
                    // begin superavit
		    for($y=0; $y < $aux->numrows;$y++){  //repete o for
	                db_fieldsmemory($r,$y,true);		  
                        if ($o48_superavit =="t"){ // begin if "t"
			    if (!isset($kbd)){ // variavel qualquer
			         $kbd = 1;  //seta com qualquer valor
                                 $pdf->Ln(7);	       
                                 $pdf->setX(20);
                                 $pdf->Cell(30,4,"Codigo",0,0,"L",'0');   
                                 $pdf->Cell(90,4,"Recurso",0,0,"L",'0');	    
	                         $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
			    }  
			    $pdf->setX(20);
		            db_query("BEGIN");
                            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		            db_query("ROLLBACK");
                            if(pg_numrows($r_dot)>0){
                               db_fieldsmemory($r_dot,0,true);
                               $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
	  	               $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
			    }   
                        } // end if "t"
		        $pdf->Ln();
                    }//end for
		    // end superavit
	      }	    
              $sql_r= "select *
	               from orcsuplem
			     inner join orcsuplemrec on o85_codsup = o46_codsup
	                     inner join orcreceita   on o70_codrec = o85_codrec  and o70_anousu =o85_anousu
			     inner join orcfontes    on o57_codfon = orcreceita.o70_codfon and o57_anousu = orcreceita.o70_anousu
		       where orcsuplem.o46_codlei= $o46_codlei
			      and  o46_tiposup = 1013 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln();	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Receita",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Estrutural",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
	            for($y=0; $y < $aux->numrows;$y++){
	                 db_fieldsmemory($r,$y,true);
                         $pdf->setX(20);
                         $pdf->Cell(150,4,"$o85_codrec - $o57_fonte",0,1,"L",'0');  $pdf->setX(20);   
                         $pdf->Cell(120,4,"$o57_descr              ",0,0,"L",'0');     
                         $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  $pdf->setX(20);   
	            }		 
              } // end for
	      // end receitas
   } else if (in_array("1014",$artigo)){
               /* begin */
	       /*
               $sql_r="select sum(o47_valor) as valor
	               from orcsuplemval 
                          inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                    and orcsuplem.o46_codlei = $o46_codlei
	               where o47_valor < 0 ";
               $pr = $aux->sql_record($sql_r);
	       if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
	       } else {
                  break;
	       }*/	
               $pdf->Ln(7);
	       $art =  array_search('1014',$artigo);
               $txt="Art $artigo_count. - Para cobertura da(s) Trasnferência(s) de Recurso  aberto(s) no artigo número $art";
	       $txt .=" será usado como recurso(s) o(s) íten(s) conforme classificação a seguir : ";
               $pdf->multicell(180,4,$txt,'0','J','0',20); 	      
	       $artigo_count += 1;
	       // reduções
               $sql_r="select o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
                              ( case when (o47_valor < 0) then ( o47_valor *-1)
                                else o47_valor
                                end)  as o47_valor,
			      o48_superavit
	              from orcsuplemval
                            inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1014
	                      and orcsuplem.o46_codlei = $o46_codlei
			    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	              where  o47_valor < 0 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln(7);	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     
		    for($y=0; $y < $aux->numrows;$y++){
	                db_fieldsmemory($r,$y,true);		  
                        $pdf->setX(20);
		        db_query("BEGIN");
                        $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		        db_query("ROLLBACK");
                        if(pg_numrows($r_dot)>0){
                             db_fieldsmemory($r_dot,0,true);
                             $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                             $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                             $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                             $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	             $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                             // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                        } else{
                             echo "Dotação não cadastrada.";
                             exit;
                        } 
		        $pdf->Ln();
	            } // end for
                    // begin superavit
		    for($y=0; $y < $aux->numrows;$y++){  //repete o for
	                db_fieldsmemory($r,$y,true);		  
                        if ($o48_superavit =="t"){ // begin if "t"
			    if (!isset($kbd)){ // variavel qualquer
			         $kbd = 1;  //seta com qualquer valor
                                 $pdf->Ln(7);	       
                                 $pdf->setX(20);
                                 $pdf->Cell(30,4,"Codigo",0,0,"L",'0');   
                                 $pdf->Cell(90,4,"Recurso",0,0,"L",'0');	    
	                         $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
			    }  
			    $pdf->setX(20);
		            db_query("BEGIN");
                            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		            db_query("ROLLBACK");
                            if(pg_numrows($r_dot)>0){
                               db_fieldsmemory($r_dot,0,true);
                               $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
	  	               $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
			    }   
                        } // end if "t"
		        $pdf->Ln();
                    }//end for
		    // end superavit
	      }	    
              $sql_r= "select *
	               from orcsuplem
			     inner join orcsuplemrec on o85_codsup = o46_codsup
	                     inner join orcreceita   on o70_codrec = o85_codrec  and o70_anousu =o85_anousu
			     inner join orcfontes    on o57_codfon = orcreceita.o70_codfon and o57_anousu = orcreceita.o70_anousu
		       where orcsuplem.o46_codlei= $o46_codlei
			      and  o46_tiposup = 1014 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln();	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Receita",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Estrutural",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
	            for($y=0; $y < $aux->numrows;$y++){
	                 db_fieldsmemory($r,$y,true);
                         $pdf->setX(20);
                         $pdf->Cell(150,4,"$o85_codrec - $o57_fonte",0,1,"L",'0');  $pdf->setX(20);   
                         $pdf->Cell(120,4,"$o57_descr              ",0,0,"L",'0');     
                         $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  $pdf->setX(20);   
	            }		 
              } // end for
	      // end receitas
   } else if (in_array("1015",$artigo)){
               /* begin */
	       /*
               $sql_r="select sum(o47_valor) as valor
	               from orcsuplemval 
                          inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                    and orcsuplem.o46_codlei = $o46_codlei
	               where o47_valor < 0 ";
               $pr = $aux->sql_record($sql_r);
	       if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
	       } else {
                  break;
	       }*/	
               $pdf->Ln(7);
	       $art =  array_search('1015',$artigo);
               $txt="Art $artigo_count. - Para cobertura do(s) Remanejamento(s) de Recurso aberto(s) no artigo número $art";
	       $txt .=" será usado como recurso(s) o(s) íten(s) conforme classificação a seguir : ";
               $pdf->multicell(180,4,$txt,'0','J','0',20);
	       $artigo_count += 1;
	       // reduções
               $sql_r="select o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
			      ( case when (o47_valor < 0) then ( o47_valor *-1)
                                else o47_valor
                                end)  as o47_valor,
			      o48_superavit
	              from orcsuplemval
                            inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1015
	                      and orcsuplem.o46_codlei = $o46_codlei
			    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	              where  o47_valor < 0 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln(7);	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     
		    for($y=0; $y < $aux->numrows;$y++){
	                db_fieldsmemory($r,$y,true);		  
                        $pdf->setX(20);
		        db_query("BEGIN");
                        $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		        db_query("ROLLBACK");
                        if(pg_numrows($r_dot)>0){
                             db_fieldsmemory($r_dot,0,true);
                             $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                             $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                             $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                             $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	             $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                             // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                        } else{
                             echo "Dotação não cadastrada.";
                             exit;
                        } 
		        $pdf->Ln();
	            } // end for
                    // begin superavit
		    for($y=0; $y < $aux->numrows;$y++){  //repete o for
	                db_fieldsmemory($r,$y,true);		  
                        if ($o48_superavit =="t"){ // begin if "t"
			    if (!isset($kbd)){ // variavel qualquer
			         $kbd = 1;  //seta com qualquer valor
                                 $pdf->Ln(7);	       
                                 $pdf->setX(20);
                                 $pdf->Cell(30,4,"Codigo",0,0,"L",'0');   
                                 $pdf->Cell(90,4,"Recurso",0,0,"L",'0');	    
	                         $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
			    }  
			    $pdf->setX(20);
		            db_query("BEGIN");
                            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		            db_query("ROLLBACK");
                            if(pg_numrows($r_dot)>0){
                               db_fieldsmemory($r_dot,0,true);
                               $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
	  	               $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
			    }   
                        } // end if "t"
		        $pdf->Ln();
                    }//end for
		    // end superavit
	      }	    
              $sql_r= "select *
	               from orcsuplem
			     inner join orcsuplemrec on o85_codsup = o46_codsup
	                     inner join orcreceita   on o70_codrec = o85_codrec  and o70_anousu =o85_anousu
			     inner join orcfontes    on o57_codfon = orcreceita.o70_codfon and o57_anousu = orcreceita.o70_anousu
		       where orcsuplem.o46_codlei= $o46_codlei
			      and  o46_tiposup = 1015 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln();	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Receita",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Estrutural",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
	            for($y=0; $y < $aux->numrows;$y++){
	                 db_fieldsmemory($r,$y,true);
                         $pdf->setX(20);
                         $pdf->Cell(150,4,"$o85_codrec - $o57_fonte",0,1,"L",'0');  $pdf->setX(20);   
                         $pdf->Cell(120,4,"$o57_descr              ",0,0,"L",'0');     
                         $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  $pdf->setX(20);   
	            }		 
              } // end for
	      // end receitas
   } else if (in_array("1016",$artigo)){
               /* begin */
	       /*
               $sql_r="select sum(o47_valor) as valor
	               from orcsuplemval 
                          inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup >= 1001 and o46_tiposup <= 1005
	                    and orcsuplem.o46_codlei = $o46_codlei
	               where o47_valor < 0 ";
               $pr = $aux->sql_record($sql_r);
	       if ($aux->numrows > 0 ){
		       db_fieldsmemory($pr,0);
		       $importancia = db_formatar($valor,'f');
	       } else {
                  break;
	       }*/	
               $pdf->Ln(7);
	       $art =  array_search('1016',$artigo);
               $txt="Art $artigo_count. - Para cobertura da(s) Transposição(ões) de Recursos aberto(s) no artigo número $art";
	       $txt .=" será usado como recurso(s) o(s) íten(s) conforme classificação a seguir : ";
               $pdf->multicell(180,4,$txt,'0','J','0',20);
	       $artigo_count += 1;
	       // reduções
               $sql_r="select o47_coddot,
	                      o47_anousu,
	                      fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
			      ( case when (o47_valor < 0) then ( o47_valor *-1)
                                else o47_valor
                                end)  as o47_valor,
			      o48_superavit
	              from orcsuplemval
                            inner join orcsuplem on o46_codsup=o47_codsup and o46_tiposup = 1016
			         and orcsuplem.o46_codlei = $o46_codlei
			    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	              where  o47_valor < 0 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln(7);	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Reduzido",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Dotação",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     
		    for($y=0; $y < $aux->numrows;$y++){
	                db_fieldsmemory($r,$y,true);		  
                        $pdf->setX(20);
		        db_query("BEGIN");
                        $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		        db_query("ROLLBACK");
                        if(pg_numrows($r_dot)>0){
                             db_fieldsmemory($r_dot,0,true);
                             $pdf->Cell(150,4,"$o58_orgao - $o40_descr",0,1,"L",'0');  $pdf->setX(20);    
                             $pdf->Cell(150,4,"$o58_unidade -  $o41_descr",0,1,"L",'0');  $pdf->setX(20);	    
                             $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  $pdf->setX(20);   
                             $pdf->Cell(120,4,"$o58_elemento - $o56_descr",0,0,"L",'0');     
	  	             $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
                             // $pdf->Cell(150,4,"$o58_codigo - $o15_descr",0,1,"L",'0');  $pdf->setX(20);   
                        } else{
                             echo "Dotação não cadastrada.";
                             exit;
                        } 
		        $pdf->Ln();
	            } // end for
                    // begin superavit
		    for($y=0; $y < $aux->numrows;$y++){  //repete o for
	                db_fieldsmemory($r,$y,true);		  
                        if ($o48_superavit =="t"){ // begin if "t"
			    if (!isset($kbd)){ // variavel qualquer
			         $kbd = 1;  //seta com qualquer valor
                                 $pdf->Ln(7);	       
                                 $pdf->setX(20);
                                 $pdf->Cell(30,4,"Codigo",0,0,"L",'0');   
                                 $pdf->Cell(90,4,"Recurso",0,0,"L",'0');	    
	                         $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
			    }  
			    $pdf->setX(20);
		            db_query("BEGIN");
                            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot");
		            db_query("ROLLBACK");
                            if(pg_numrows($r_dot)>0){
                               db_fieldsmemory($r_dot,0,true);
                               $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
	  	               $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  $pdf->setX(20);         
			    }   
                        } // end if "t"
		        $pdf->Ln();
                    }//end for
		    // end superavit
	      }	    
              $sql_r= "select *
	               from orcsuplem
			     inner join orcsuplemrec on o85_codsup = o46_codsup
	                     inner join orcreceita   on o70_codrec = o85_codrec  and o70_anousu =o85_anousu
			     inner join orcfontes    on o57_codfon = orcreceita.o70_codfon and o57_anousu = orcreceita.o70_anousu
		       where orcsuplem.o46_codlei= $o46_codlei
			      and  o46_tiposup = 1016 ";
              $r=$aux->sql_record($sql_r);
	      if ($aux->numrows > 0 ){
                    $pdf->Ln();	       
                    $pdf->setX(20);
                    $pdf->Cell(30,4,"Receita",0,0,"L",'0');   
                    $pdf->Cell(90,4,"Estrutural",0,0,"L",'0');	    
	            $pdf->Cell(30,4,"VALOR",0,1,"R",'0');   	      	     	
	            for($y=0; $y < $aux->numrows;$y++){
	                 db_fieldsmemory($r,$y,true);
                         $pdf->setX(20);
                         $pdf->Cell(150,4,"$o85_codrec - $o57_fonte",0,1,"L",'0');  $pdf->setX(20);   
                         $pdf->Cell(120,4,"$o57_descr              ",0,0,"L",'0');     
                         $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  $pdf->setX(20);   
	            }		 
              } // end for
	      // end receitas

   } else {
      echo "opa ! tem codigo inválido";
      exit; 
   }  
  //
   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(58,"db02_texto"));
   if ($cldbparagrafo->numrows > 0 ){
         db_fieldsmemory($pr,0);   	 
	 $pdf->Ln(7);
         $txt="Art $artigo_count. - $db02_texto";
         $artigo_count += 1;
	 $pdf->setX(30);
         $pdf->multicell(180,4,$txt,'0','J','0',20);
         $pdf->Ln();    
   }  else {
         echo "paragrafo 49 faltando";
         exit;
   }  
   $pr = $cldbparagrafo->sql_record($cldbparagrafo->sql_query_file(59,"db02_texto"));
   if ($cldbparagrafo->numrows > 0 ){
         db_fieldsmemory($pr,0);   
	 $pdf->Ln(7);
         $txt="Art $artigo_count. - $db02_texto";
         $artigo_count += 1;
	 $pdf->setX(30);
         $pdf->multicell(180,4,$txt,'0','J','0',20);
         $pdf->Ln();    
   }  else {
         echo "paragrafo 49 faltando";
         exit;
   }  
  //
  $pdf->Ln();
  $pdf->Cell(150,2,"","",1,"L",0);
  $pdf->Cell(150,0,"","LRBT",1,"L",0);
  // $tmpfile=tempnam("tmp","tmp.pdf");

  $pdf->Output();

?>