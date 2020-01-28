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
 // include("dbforms/db_funcoes.php");
 include("classes/db_protprocesso_classe.php");
 $aux    = new cl_protprocesso;

 db_postmemory($HTTP_POST_VARS); 
 parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);


 $sql =" select p51_codigo,p51_descr,k02_codigo,k02_drecei,p52_valor 
         from tipoproc 
              inner join procrec on p51_codigo = p52_codigo 
              inner join tabrec on p52_codrec = k02_codigo 
	where p51_dtlimite is null 
           or p51_dtlimite >= '".date("Y-m-d",db_getsession("DB_datausu"))."'";
  if ($ordem == "p51_codigo")	  
     $sql .="order by p51_codigo "; 
  else
     $sql .="order by p51_descr ";
 
  $res= $aux->sql_record($sql);
  // db_criatabela($res);
  // exit;


 $pdf = new pdf();
 $head3 = "Tipos de processo com receitas"; 
 // $head7 = "Periodo:  $d1/$m1/$a1   à   $d2/$m2/$d2 "; 
 $pdf->open();
 $pdf->setfillcolor(243);

 if ($aux->numrows > 0 ) { 
         $tipo = ""; //codigo do tipo do processo 
         for ($x=0;$x < $aux->numrows ; $x++){      
           db_fieldsmemory($res,$x,true);	 
      
           if ($pdf->gety() > $pdf->h - 40 || $x==0){  //quebra página
               $pdf->addpage("P");
               $pdf->aliasNbPages();
               $pdf->setfont('Arial','',7);
               $pdf->cell(10,4,'CÓDIGO',       'B',0,'L',0);
               $pdf->cell(70,4,'DEPARTAMENTO','B',0,'L',0);
               $pdf->cell(10,4,'RECEITA', 'B',0,'R',0); // <br>
               $pdf->cell(70,4,'DESCRIÇÃO', 'B',0,'L',0); // <br>
               $pdf->cell(20,4,'VALOR', 'B',1,'R',0); // <br>
           }

           if ($tipo != $p51_codigo){
 	       $tipo = $p51_codigo;
               $pdf->ln();
               $pdf->cell(10,4,"$tipo" ,0,0,'L');
              $pdf->cell(70,4,"$p51_descr",0,0,'L');
            }else{
              $pdf->cell(10,4,"" ,0,0,'L');
              $pdf->cell(70,4,"",0,0,'L');
            }
            $pdf->cell(10,4,"$k02_codigo",  0,0,'R');
            $pdf->cell(70,4,"$k02_drecei",0,0,'L');
            $pdf->cell(20,4,"$p52_valor",  0,1,'R');


	    
         } // end for   



 } else {  // if ($aux->numrows > 0 ) 
      db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ! ');   
 }  
 
 $pdf->output();

?>