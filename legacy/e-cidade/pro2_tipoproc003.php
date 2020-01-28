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
 include("libs/db_utils.php");
 $aux    = new cl_protprocesso;
 $sSqlInsti = "select codigo||' - '||nomeinstabrev as nomeinstabrev 
                 from db_config 
                where codigo = ".db_getsession("DB_instit");
 $objInsti  =  db_utils::fieldsmemory(pg_query($sSqlInsti),0);               

 db_postmemory($HTTP_POST_VARS); 
 parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

 if ($andamento =="com"){
        $sql= "select  p51_codigo,
                       p51_descr,
                       p53_coddepto,
                       descrdepto,
                       p53_dias,
                       p53_ordem
               from tipoproc
                   left outer join andpadrao on p53_codigo = p51_codigo
                          and p53_ordem = ( select min(p53_ordem) from andpadrao
					                                   where p53_codigo=p51_codigo )
                   left outer join db_depart on coddepto = p53_coddepto
               where p51_instit = ".db_getsession("DB_instit");
		            
        if ($ordem =="depart")
            $sql .= "order by  p53_coddepto,p51_descr";	 
        else 
            $sql .= "order by p51_descr";

 } else if ($andamento =="sem"){
        $sql= "select  p51_codigo,
                       p51_descr
               from tipoproc 
	       order by p51_descr "; 

 } else {
    echo "<script> alert ('te decisa ! tchê !' );  window.close();  </script>   ";
 }  

  // echo $sql; 
  $res= $aux->sql_record($sql);
  // db_criatabela($res);
  // exit;


 $pdf = new pdf();
 $head3 = "Tipos de processo"; 
 $head5 = "Instituição: ".$objInsti->nomeinstabrev; 
 $pdf->open();
 $pdf->addpage('P');
 $pdf->aliasNbPages();
 $pdf->setfillcolor(243);
 $pdf->setfont('Arial','','8');

 if ($aux->numrows > 0 ) { 
     //-----------------------------------------
     if ($andamento == "com" && $ordem =="depart"){
        $depto="";
        for ($x=0;$x < $aux->numrows ; $x++){      
            db_fieldsmemory($res,$x,true);	 
            if ($depto != $p53_coddepto){ // imprime departamento 
                $pdf->Ln();
                $pdf->setx(10); 	    
                $pdf->setfont('Arial','B','8');
                $pdf->cell(10,4,"$p53_coddepto",1,0,'R','0');
                $pdf->cell(120,4,"$descrdepto", 1,1,'L','0'); //<br>
                $pdf->setfont('Arial','',7);
               // $pdf->setx(20); 	    
                $pdf->cell(10,4,'COD.TIPO', 1,0,'R',0);
                $pdf->cell(80,4,'DESCRICAO',1,0,'L',0);
                $pdf->cell(60,4,'DIAS',     1,1,'R',0); // <br>
  	        $depto = $p53_coddepto;
	         }  // end if "imprime departamento"
	           $cor=0; 
            $pdf->setx(10); 
            $pdf->cell(20,4,"$p51_codigo",0,0,'R',$cor);
            $pdf->cell(80,4,"$p51_descr ",0,0,'L',$cor);
            $pdf->cell(60,4,"$p53_dias",0,1,'R',$cor); // <br>

            if ($pdf->gety() > $pdf->h - 30 ){  //quebra página
               $pdf->addpage("P");
  	       $depto="";
            }     

        } // end for
     } else if ($andamento == "com" && $ordem =="tipoproc"){
        $header = true;
        for ($x=0;$x < $aux->numrows ; $x++){      
            db_fieldsmemory($res,$x,true);	 
            if ($header == true){ // imprime departamento 
	              $header = false;
                $pdf->Ln();
                $pdf->setx(10); 	    
                $pdf->cell(15,4,'COD.TIPO'  ,1,0,'C',1); 
		            $pdf->cell(80,4,'DESCR',     '1',0,'C',1);
                $pdf->cell(20,4,'COD.DEPTO', '1',0,'C',1);
                $pdf->cell(65,4,'NOME DEPTO','1',0,'C',1);
                $pdf->cell(15,4,'DIAS',      '1',1,'C',1); // <br>
	          }  // end if "imprime departamento"
	          $cor=0; 
            $pdf->setx(10); 
       	    $pdf->cell(15,4,"$p51_codigo",0,0,'C','0');
            $pdf->cell(80,4,"$p51_descr",0,0,'L','0');              
            $pdf->cell(20,4,"$p53_coddepto",0,0,'C',$cor);
            $pdf->cell(65,4,"$descrdepto",0,0,'L',$cor);
            $pdf->cell(15,4,"$p53_dias",0,1,'C',$cor); // <br>

            if ($pdf->gety() > $pdf->h - 30 ){  //quebra página
               $pdf->addpage("P");
  	       $header= true;
            }     

        } // end for
     } 	 
     //-----------------------------------------
     if ($andamento == "sem"){
        $header = true;
        for ($x=0;$x < $aux->numrows ; $x++){      
            db_fieldsmemory($res,$x,true);	 
            if ($header == true){ // imprime departamento 
	        $header = false;
                $pdf->Ln();
                $pdf->setx(20); 	    
                $pdf->cell(20,4,'COD.TIPO'  ,'B',0,'R','0'); 
                $pdf->cell(15,4,'','B',0,'R','0');
		$pdf->cell(80,4,'DESCR',     'B',1,'L',0); // <br>
	    }  // end if "imprime departamento"
	    $cor=0; 
            $pdf->setx(20); 
     	    $pdf->cell(20,4,"$p51_codigo",0,0,'R','0');
            $pdf->cell(15,4,'',0,0,'R','0');
            $pdf->cell(80,4,"$p51_descr" ,0,1,'L','0'); // <br>              

            if ($pdf->gety() > $pdf->h - 40 ){  //quebra página
               $pdf->addpage("P");
  	       $header= true;
            }     

        } // end for
     } 	 
    //-----------------------------------------

 } else {  // if ($aux->numrows > 0 ) 
      db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ! ');   
 }  
 
 $pdf->output();

?>