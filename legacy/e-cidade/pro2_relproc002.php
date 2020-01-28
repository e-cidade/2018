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
 //include("libs/db_stdlib.php");
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
 
 // $processo = $processo;
 // primeiro - dados do processo e dados do cgm
 // segundo  - dados do andamento
 // terceiro - dados de recibos

 $sql =" select *
         from protprocesso
	     inner join cgm on p58_numcgm = cgm.z01_numcgm
             inner join db_usuarios on p58_id_usuario=id_usuario
	 where protprocesso.p58_codproc=$processo  
       ";
 $res_proc = pg_exec($sql);
 $rows_proc = pg_numrows($res_proc);
 if (pg_numrows($res_proc) == 0){
     echo "zerado";
 }  

 $pdf = new pdf();
 $head3 = "PROCESSO : $processo"; 
 $pdf->open();
 $pdf->addpage();
 $pdf->aliasNbPages();
 $pdf->setfillcolor(243);
 $alt=4;
 $pdf->setX(20);
 // dados do processo e cgm
 for ($i = 0;$i < $rows_proc;$i++){ 
     db_fieldsmemory($res_proc,$i,true);
     if ($pdf->gety() > $pdf->h-30){
         $pdf->addpage();
     }
     // linha
     $pdf->cell(170,$alt,"PROCESSO DE PROTOCOLO $p58_codproc",'B',1,"C",0);
     $pdf->Ln(3);
     $pdf->setX(20);
     $pdf->cell(20,$alt,'PROCESSO',0,0,"L",0);
     $pdf->cell(30,$alt,$p58_codproc,0,0,"L",0);
     $pdf->cell(20,$alt,'NOME',0,0,"L",0);
     $pdf->cell(100,$alt,$z01_nome,0,1,"L",0);
     // linha
     $pdf->setX(20);
     $pdf->cell(20,$alt,'DATA',0,0,"L",0);
     $pdf->cell(30,$alt,$p58_dtproc,0,0,"L",0);
     $pdf->cell(20,$alt,'HORA',0,0,"L",0);
     $pdf->cell(20,$alt,$p58_hora,0,1,"L",0);
     // linha
     $pdf->setX(20);
     $pdf->cell(20,$alt,'TIPO',0,0,"L",0);
     $pdf->cell(30,$alt," ",0,0,"L",0);
     $pdf->cell(20,$alt,'ATENDENTE',0,0,"L",0);
     $pdf->cell(70,$alt,$nome,0,1,"L",0);
     // linha
     $pdf->setX(20);
     $pdf->cell(20,$alt,'REQUERENTE',0,0,"L",0);
     $pdf->cell(120,$alt,$p58_requer,0,1,"L",0);
     // linha
     $pdf->setX(20);
     $pdf->cell(20,$alt,'OBS',0,0,"L",0);
     $pdf->multicell(120,$alt,$p58_obs,0,1,"L",0);
     // linha
     
     /* lista andamentos */

      $sql ="select *
             from procandam
	        inner join db_usuarios on p61_id_usuario=id_usuario
		inner join db_depart on p61_coddepto = coddepto
       	     where p61_codproc=$processo 
	     order by p61_dtandam
            ";
      $res_and = pg_exec($sql);
      if (pg_numrows($res_and)>0){
            $pdf->Ln(4);
            $pdf->setX(20);
            $pdf->cell(170,$alt,'ANDAMENTOS','B',1,"C",0);      
            $pdf->setX(20);
            $pdf->cell(20,$alt,'DATA',0,0,"C",0);
            $pdf->cell(40,$alt,'USUARIO',0,0,"L",0);
            $pdf->cell(70,$alt,'DEPARTAMENTO',0,1,"L",0);
            //$pdf->cell(40,$alt,'SITUACAO',0,1,"L",0);
 
            for ($h=0;$h < (pg_numrows($res_and));$h++){
               db_fieldsmemory($res_and,$h,true);
	       $pdf->setX(20);
	       $pdf->cell(20,$alt,$p61_dtandam,0,0,"C",0);
               $pdf->cell(40,$alt,substr($nome,0,25),0,0,"L",0);
               $pdf->cell(50,$alt,substr($descrdepto,0,40),0,1,"L",0);
	       $pdf->setX(30);
               $pdf->multicell(0,$alt,$p61_despacho,0,'J',1,0);    
	       $pdf->Ln(1);
               // linha         
            }
      }
     /* transferencias */ 

      $sql = "select a.descrdepto as deptoatual,
		     b.descrdepto as deptovai,
 		     p63_codtran 
	      from proctransfer 
	 	   inner join db_depart a on p62_coddepto = a.coddepto 
	 	   inner join db_depart b on p62_coddeptorec = b.coddepto
     	           inner join proctransferproc on p63_codtran = p62_codtran
	       where p63_codproc = $processo 
	          and p63_codtran not in(select p64_codtran
				         from proctransand 
					 order by p64_codtran)";
      $res_and = pg_exec($sql);
      if (pg_numrows($res_and)>0){
           $pdf->Ln(4);
           $pdf->setX(20);
           $pdf->cell(170,$alt,'TRANSFERENCIAS','B',1,"C",0);      
           $pdf->setX(20);
           $pdf->cell(20,$alt,'CODIGO',0,0,"C",0);
           $pdf->cell(75,$alt,'DEPTO ATUAL',0,0,"L",0);
           $pdf->cell(75,$alt,'DEPTO DESTINO',0,1,"L",0);

           for ($h=0;$h < (pg_numrows($res_and));$h++){
              db_fieldsmemory($res_and,$h,true);
    	      $pdf->setX(20);
	      $pdf->cell(20,$alt,$p63_codtran,0,0,"C",0);
              $pdf->cell(75,$alt,$deptoatual,0,0,"L",0);
              $pdf->cell(75,$alt,$deptovai,0,1,"L",0);
              // linha         
           }
       }	   

     /* recibos */ 
    
      $sql = "select r.k00_numpre as recibo,
     	    	     rp.k00_numpre as arrepaga,
		     sum(r.k00_valor) as k00_valor,
		     rp.k00_dtpaga,r.k00_dtvenc,r.k00_dtoper 
	      from arreproc 
	          inner join recibo r on r.k00_numpre = k80_numpre 
		  left join arrepaga rp on rp.k00_numpre = k80_numpre 
	      where k80_codproc = $processo 
	      group by r.k00_numpre, rp.k00_numpre,rp.k00_dtpaga,r.k00_dtvenc,r.k00_dtoper";
      $res_and = pg_exec($sql);
      if(pg_numrows($res_and)>0){
           $pdf->Ln(4);
           $pdf->setX(20);
           $pdf->cell(170,$alt,'RECIBOS','B',1,"C",0);      
           $pdf->setX(20);
           $pdf->cell(30,$alt,'SITUACAO',1,0,"C",0);
           $pdf->cell(20,$alt,'NUMPRE',1,0,"C",0);
           $pdf->cell(30,$alt,'VALOR',1,0,"R",0);
           $pdf->cell(30,$alt,'DT.OPERACAO',1,0,"C",0);
           $pdf->cell(30,$alt,'DT.VENCIMENTO',1,0,"C",0);
           $pdf->cell(30,$alt,'DT.PAGAMENTO',1,1,"C",0);

           for ($h=0;$h < (pg_numrows($res_and));$h++){
              db_fieldsmemory($res_and,$h,true);
	      $pdf->setX(20);
	      $pdf->cell(30,$alt,$arrepaga == ""?"RECIBO À PAGAR":"RECIBO PAGO",1,0,"C",0);
	      $pdf->cell(20,$alt,$arrepaga,1,0,"C",0);
              $pdf->cell(30,$alt,db_formatar($k00_valor,'f') ,1,0,"R",0);
              $pdf->cell(30,$alt,$k00_dtoper ,1,0,"C",0);
	      $pdf->cell(30,$alt,$k00_dtvenc ,1,0,"C",0);
              $pdf->cell(30,$alt,$k00_dtpaga!=""?$k00_dtpaga:"",1,1,"C",0);
              // linha         
	  }   
      }




 }
 
 
 $pdf->output();
?>