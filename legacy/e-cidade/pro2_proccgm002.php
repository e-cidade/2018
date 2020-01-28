<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

 /**-
  * relatorio resumido
  *  cgm  - nome 
  *     - processo - data - descrição-  setor atual
  * relatorio completo
  *  cgm - nome
  *    - procedd data - descricao - 
  *       - andamento1 - setor - obs
  *       - andamento2 - setor - obs
  */
 include("fpdf151/pdf.php");
 include("classes/db_protprocesso_classe.php");

 $aux    = new cl_protprocesso;
 $aux02  = new cl_protprocesso;

 db_postmemory($HTTP_POST_VARS); 
 ///////////////////////////////////////////////////////////////////////
 $data1="";
 $data2="";
 @$data1="$data1_ano-$data1_mes-$data1_dia"; 
 @$data2="$data2_ano-$data2_mes-$data2_dia"; 
 if (strlen($data1) < 7){
    $data1= db_getsession("DB_anousu")."-01-31";
 }  
 if (strlen($data2) < 7){
    $data2= db_getsession("DB_anousu")."-12-31";
 }  
 //---------
  //////////////////////////////////////////////////////////////////
  if ($tipo =="resumido"){
      $sql = "select * 
              from protprocesso 
    	             inner join cgm on z01_numcgm         = protprocesso.p58_numcgm
    	             left  join procandam on p61_codandam = protprocesso.p58_codandam
    	             left  join db_depart on p61_coddepto = coddepto
              where ";
  } else {
      $sql = "select * 
              from protprocesso 
    	            inner join cgm on z01_numcgm         = protprocesso.p58_numcgm
    	            left  join procandam on p61_codandam = protprocesso.p58_codandam
    	             left  join db_depart on p61_coddepto = coddepto
              where ";
   
  }
  //////////////////////////////////////////////////////////////////  
  if (isset($lista)){
      $w="("; 
      $tamanho= sizeof($lista);
      for ($x=0;$x < sizeof($lista);$x++){
          $w = $w."$lista[$x]";
          if ($x < $tamanho-1) {
              $w= $w.",";
          }	
      }  
      $w = $w.")";
   }
   if (isset($lista)){
       if (isset($ver) and $ver=="com"){
           $sql.="p58_numcgm in  $w";
       } else {
	   $sql.="p58_numcgm not in  $w";
       }	 
   } 
   $sql .=" and p58_dtproc >='$data1' and p58_dtproc <= '$data2'  ";
   $sql .= "and (p58_instit = ".db_getsession("DB_instit")." or instit=".db_getsession("DB_instit").")"; 
   $sql .=" order by p58_numcgm,p58_codproc";

  // fim sql
  //die($sql);
  $res= $aux->sql_record($sql);
  // db_criatabela($res);
  // exit;


 $pdf = new pdf();
 $head3 = "PROCESSOS POR CGM"; 
 if ($tipo=="resumido"){
   $head4="TIPO : RESUMIDO";
 } else {
   $head4="TIPO : COMPLETO";
   $head5="QUEBRA POR CGM/PROCESSO/ANDAMENTOS";
 }  
 
  list($a1,$m1,$d1) = split("-",$data1);
  list($a2,$m2,$d2) = split("-",$data2);
 $head7 = "Periodo:  $d1/$m1/$a1   à   $d2/$m2/$a2 "; 
 $pdf->open();
 $pdf->addpage('P');
 $pdf->aliasNbPages();
 $pdf->setfillcolor(243);

 // resumido
 if ($tipo == "resumido"){
     if ($aux->numrows > 0 ) { 
         $cgm = ""; 
         for ($x=0;$x < $aux->numrows ; $x++){      
            db_fieldsmemory($res,$x,true);	 
            if ($cgm != $p58_numcgm){
	       $cor=0;
	       $pdf->Ln();
               $pdf->setx(10); 	    
	       $pdf->setfont('Arial','B','8');
               $pdf->cell(20,4,"$p58_numcgm",0,0,'R','0');
               $pdf->cell(120,4,"$z01_nome",  0,1,'L','0'); //<br>
               $pdf->setfont('Arial','',7);
               $pdf->setx(15); 	    
               $pdf->cell(20,4,'PROCESSO','B',0,'R',0);
               $pdf->cell(20,4,'DATA','B',0,'C',0);
               $pdf->cell(60,4,'REQUERENTE','B',0,'L',0);
               $pdf->cell(40,4,'OBS','B',0,'L',0);
               $pdf->cell(30,4,'SETOR.ATUAL','B',1,'L',0); //<br>
  	       $cgm = $p58_numcgm;
            }  
	    $cor=0; 
            $pdf->setx(15); 
            
            /**
             * Trata o numero do processo para quando o mesmo for do tipo OUVIDORIA
             */
            $sNumeroProcesso = $p58_numero."/".$p58_ano;
            if ($p58_numero == "") {
              $sNumeroProcesso = "";
            }
            
            $pdf->cell(20,4,$sNumeroProcesso,0,0,'R',$cor);
            $pdf->cell(20,4,"$p58_dtproc", 0,0,'C',$cor);
            $pdf->cell(60,4,substr($p58_requer,0,40), 0,0,'L',$cor);
            $pdf->cell(40,4,substr($p58_obs,0,32 ),   0,0,'L',$cor);
            // procura o sertor atual do processo
	    $sql = "select descrdepto
                    from procandam
	  	       inner join db_depart on coddepto = p61_coddepto
                    where
                        p61_codandam=(
		                       select max(p61_codandam) 
		                       from procandam 
		                        where p61_codproc = $p58_codproc
		                      ) ";
            $r= $aux02->sql_record($sql);	
  	    if ($aux02->numrows > 0){
    	        db_fieldsmemory($r,0); 
                $pdf->cell(30,4,substr($descrdepto,0,30),0,1,'L',$cor); // <br>
	    } else {
	        $pdf->cell(30,4,'sem andamento',0,1,'L',$cor); // <br>
            }
            // quebra pagina
            if ($pdf->gety() > $pdf->h - 40 ){
                $pdf->addpage("P");
  	        $cgm="";
            }

         } // end for   
     } else {
      // if ($aux->numrows > 0 ) 
      db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ! ');   
     }
 } else { // if ($tipo == resumido )...
     if ($aux->numrows > 0 ) { 
         $cgm = ""; 
         for ($x=0;$x < $aux->numrows ; $x++){      
            db_fieldsmemory($res,$x,true);	 
            if ($cgm != $p58_numcgm){
	       $cor=0;
	       $pdf->Ln();
               $pdf->setx(10); 	    
	       $pdf->setfont('Arial','B','8');
               $pdf->cell(20,4,"$p58_numcgm",0,0,'R','0');
               $pdf->cell(120,4,"$z01_nome",  0,1,'L','0'); //<br>
               $pdf->setfont('Arial','',7);
	       /*
               $pdf->setx(15); 	    
               $pdf->cell(20,4,'PROCESSO','B',0,'R',0);  // top do detalhe
               $pdf->cell(20,4,'DATA','B',0,'C',0);
               $pdf->cell(60,4,'REQUERENTE','B',0,'L',0);
               $pdf->cell(60,4,'OBS','B',1,'L',0);
	       */
  	       $cgm = $p58_numcgm;
            }  
	    $pdf->setx(15); 	    
            $pdf->cell(20,4,'PROCESSO','B',0,'R',0);  // top do detalhe
            $pdf->cell(20,4,'DATA','B',0,'C',0);
            $pdf->cell(70,4,'REQUERENTE','B',0,'L',0);
            $pdf->cell(65,4,'OBS','B',1,'L',0);
	
	    $cor=0; 
            $pdf->setx(15); 

            /**
             * Trata o numero do processo para quando o mesmo for do tipo OUVIDORIA
             */
            $sNumeroProcesso = $p58_numero."/".$p58_ano;
            if ($p58_numero == "") {
              $sNumeroProcesso = "";
            }
            $pdf->cell(20,4,$sNumeroProcesso,0,0,'R',$cor);
            $pdf->cell(20,4,"$p58_dtproc", 0,0,'C',$cor);
            $pdf->cell(70,4,substr($p58_requer,0,50), 0,0,'L',$cor);
            $pdf->cell(65,4,substr($p58_obs,0,40 ),   0,1,'L',$cor);
	    // seleciona todos os andamentos do processo
            $sql = "select *  
                    from procandam
	  	        inner join db_depart on coddepto = p61_coddepto
                    where p61_codproc = $p58_codproc 
		     order by p61_codandam   ";
            $r= $aux02->sql_record($sql);	
  	    if ($aux02->numrows > 0){ // ln (171)
	        // header 
                $pdf->setx(30); 	    
                $pdf->cell(20,4,'COD.AND','B',0,'R',0);  // top do detalhe
                $pdf->cell(20,4,'DATA','B',0,'C',0);
                $pdf->cell(60,4,'DEPARTAMENTO','B',0,'L',0);
                $pdf->cell(60,4,'DESPACHO','B',1,'L',0);
  	        for ($y=0;$y < $aux02->numrows;$y++){ // ln 
     	            db_fieldsmemory($r,$y,true); 
		    $pdf->setx(30); 	        
		    $pdf->cell(20,4,"$p61_codandam",0,0,'R',$cor);
		    $pdf->cell(20,4,"$p61_dtandam",0,0,'C',$cor);
                    $pdf->cell(60,4,substr($descrdepto,0,40), 0,0,'L',$cor);
                    $pdf->cell(60,4,substr($p61_despacho,0,40 ),0,1,'L',$cor);	
                } 
	    }// end if ln(176)    
	    // quebra pagina
            if ($pdf->gety() > $pdf->h - 40 ){
                $pdf->addpage("P");
  	        $cgm="";
            }


         } // end for   
     } else {  // if ($aux->numrows > 0 ) 
      db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ! ');   
     }
 }  
 
 $pdf->output();

?>