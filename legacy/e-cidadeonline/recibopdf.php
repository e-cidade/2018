<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

  //include("libs/db_conecta.php");
  include("libs/db_stdlib.php");
  include("libs/db_sql.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_arrecad_classe.php");   
  include("classes/db_arreold_classe.php");  
  include("classes/db_issplannumpre_classe.php");
  include("classes/db_cairetordem_classe.php");  
  include("classes/db_issvar_classe.php");   
  include("classes/db_issplannumpreissplanit_classe.php");     
			
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  postmemory($HTTP_POST_VARS);
  $clquery          = new cl_query;
  $cl_issvar        = new cl_issvar;
  $cl_arreold       = new cl_arreold;
  $cl_issplannumpre = new cl_issplannumpre;
  $cl_arrecad       = new cl_arrecad;
  $cl_cairetordem   = new cl_cairetordem;
  $cl_issplannumpreissplanit = new cl_issplannumpreissplanit;
  $q24_inscr       = 0;	
  $clquery->sql_query("db_confplan"," * ","","");
  $clquery->sql_record($clquery->sql);
  


  if(pg_numrows($clquery->result)==0){
    echo "<script>window.close();window.opener.alert('Não é possivel gerar recibo. Por favor, contate com a prefeitura.');window.opener.location.href='digitaissqn.php'</script>";
    exit;
  }

  db_fieldsmemory($clquery->result,0);
  if(!isset($dados_recibo)) {// ################### SE FOR EMITE RECIBO  #######################################
   // die(" emite ");
		//$mes = 0;
    //$ano = 0;
    $sqlerro = false;
    db_query("begin");
    $clquery->sql_query(""," nextval('numpref_k03_numpre_seq') as q20_numpre");
    $clquery->sql_record($clquery->sql);
    db_fieldsmemory($clquery->result,0);
    $clquery->sql_update("issplan","q20_numpre = $q20_numpre, q20_situacao= 3","q20_planilha = $planilha");
    $clquery->sql_query("issplan left join issplanit on q20_planilha = q21_planilha ","q20_ano,q20_mes,q20_numcgm,sum(q21_valor) ",""," q21_status = 1 and q20_numpre = $q20_numpre group by q20_ano,q20_mes,q20_numcgm");
    $clquery->sql_record($clquery->sql);
	// insere na issvar ......................
    db_fieldsmemory($clquery->result,0); 
    $result = db_query("insert into issvar (q05_codigo,
            q05_numpre,q05_numpar,q05_valor,q05_ano,q05_mes,q05_histor,q05_aliq,q05_bruto,q05_vlrinf)
              values(nextval('issvar_q05_codigo_seq'),$q20_numpre,1,$sum,".$q20_ano.",".$q20_mes.",'issqn retenção na fonte',0,0,0)");
    $q20_mes += 1;
    if($q20_mes > 12){ 
      $q20_mes = 1;
      $q20_ano += 1;
    }
		
    $dtarrecad = date($q20_ano."-".$q20_mes."-".$w10_dia);
    $w10_diaoper='01';
    $dtoperaarrecad = date($q20_ano."-".$q20_mes."-".$w10_diaoper);
    
	  $sql = "insert into arrecad (
            k00_numcgm,k00_dtoper,k00_receit,k00_hist,k00_valor,k00_dtvenc,k00_numpre,k00_numpar,k00_numtot,k00_numdig,k00_tipo,k00_tipojm)
              values(".$q20_numcgm.",'".$dtoperaarrecad."',".$w10_receit.",".$w10_hist.",round(".$sum.",2),'".$dtarrecad."',".$q20_numpre.",1,1,1,".$w10_tipo.",0)";
    $result= db_query($sql) or die ($sql);
   
    $sql1 = "select * from issplaninscr where q24_planilha=$planilha";
    $result1= db_query($sql1) or die ($sql1);
    if (pg_num_rows($result1) > 0) {
      
      db_fieldsmemory($result1,0);
      if($q24_inscr != 0) {
        $result= db_query("insert into arreinscr (k00_numpre,k00_inscr) values(".$q20_numpre.",".$q24_inscr.")");
      }
    }
    
		// inclui issplannumpre e issplannumpreissplan
		
		$cl_issplannumpre->q32_planilha = $planilha;
		$cl_issplannumpre->q32_numpre   = $q20_numpre;
		$cl_issplannumpre->q32_dataop   = date("Y-m-d");
		$cl_issplannumpre->q32_horaop   = date("H:i");
		$cl_issplannumpre->q32_status   = 1 ;
		$cl_issplannumpre->incluir(null);
		if ($cl_issplannumpre->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cl_issplannumpre->erro_msg;
    }
		 
		 
		if($sqlerro == false){
			$sqlnotas = " select q21_sequencial from issplanit where q21_planilha = $planilha  and q21_status = 1";
			$resultnotas = db_query($sqlnotas);
			$linhasnotas = pg_num_rows($resultnotas);
			if($linhasnotas > 0){
				for($n=0 ; $n<$linhasnotas ; $n++ ){
				  db_fieldsmemory($resultnotas,$n);
					
					$cl_issplannumpreissplanit->q77_issplanit     = $q21_sequencial;
					$cl_issplannumpreissplanit->q77_issplannumpre = $cl_issplannumpre-> q32_sequencial;
					$cl_issplannumpreissplanit->incluir(null);
					if ($cl_issplannumpreissplanit->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $cl_issplannumpreissplanit->erro_msg;
          }
				}
			}
		}
		//die('emittttttteeee');
		
			
    // verifica se tem ordem de pagamewnto para esta planilha
    
    $sqlpla = "select q96_pagordem from issplanitop 
		              inner join issplanit on q96_issplanit = q21_sequencial 
		              inner join issplan on q20_planilha = q21_planilha 
		              where q20_planilha = $planilha limit 1";
    
		   $resultpla = db_query($sqlpla);
		   $linhaspla = pg_num_rows($resultpla);
		   if($linhaspla > 0){
		     db_fieldsmemory($resultpla,0);
		     // se tiver ordem grava na cairetordem
             $cl_cairetordem -> k32_numpre = $q20_numpre;
             $cl_cairetordem -> k32_ordpag = $q96_pagordem;
             $cl_cairetordem -> incluir(null);
             if($cl_cairetordem ->erro_status == '0'){
              
               db_msgbox("$cl_cairetordem->erro_msg");
               db_query("rollback");
             }
		   }
		  
    db_query("commit");
    
    
    
/*
echo"issplan ";
db_criatabela(db_query("select * from issplan where q20_planilha = $planilha"));
echo"arrecad ";
db_criatabela(db_query("select * from arrecad where k00_numpre =$q20_numpre"));
echo"issvar ";
db_criatabela(db_query("select * from issvar where q05_numpre =$q20_numpre"));
echo"arreinscr";
db_criatabela(db_query("select * from arreinscr where k00_numpre =$q20_numpre"));

exit;*/

    $HTTP_POST_VARS["CHECK1"] = $q20_numpre."P1";
    $HTTP_POST_VARS["pdf"] = $q20_numpre;
    $HTTP_POST_VARS["ver_inscr"] = $q24_inscr;
    $HTTP_POST_VARS["ver_numcgm"] = $q20_numcgm;
    $tipo = $w10_tipo;

    /*if($q24_inscr==0) {
      echo "<script>location.href='recibo.php?erropdf=true&pdf=$q20_numpre&CHECK1=".$q20_numpre."P1&ver_numcgm=".$q20_numcgm."&tipo=".$tipo."&dtpaga=".$dtpaga."';</script>";
    } else {
      echo "<script>location.href='recibo.php?erropdf=true&pdf=$q20_numpre&CHECK1=".$q20_numpre."P1&ver_inscr=".$q24_inscr."&tipo=".$tipo."&dtpaga=".$dtpaga."';</script>";
    }
*/
  }else{// ################################ SE FOR REEMITE RECIBO ###################################################
    //die("reemite recibo");
		
		$dtatual= date("Y-m-d");
	  $hora = date("H:i");
  
    $clquery->sql_query("issplan"," * ","","q20_planilha =$planilha");
    $clquery->sql_record($clquery->sql);
    db_fieldsmemory($clquery->result,0);
    //echo("situação = $q20_situacao");
    if ($q20_situacao==2){ //  se foi alterado os dados depois de emitido o recibo #################################
    	    	
    	// select no arrecad pelo numpre para trazer os dados para gravar no arreold....................
    	
    	$clquery->sql_query("arrecad"," * ","","k00_numpre =$q20_numpre");
   		$clquery->sql_record($clquery->sql);
    	db_fieldsmemory($clquery->result,0);
    	
    	// incluir os dados do arrecad no arreold..................................................
    	
    	db_inicio_transacao();
    	
    	$sqlerro = false;
    	$cl_arreold-> k00_numpre = $k00_numpre;
    	$cl_arreold-> k00_numpar = $k00_numpar;
    	$cl_arreold-> k00_numcgm = $k00_numcgm;
    	$cl_arreold-> k00_dtoper = $k00_dtoper;
    	$cl_arreold-> k00_receit = $k00_receit;
    	$cl_arreold-> k00_hist   = $k00_hist;
    	$cl_arreold-> k00_valor  = $k00_valor;
    	$cl_arreold-> k00_dtvenc = $k00_dtvenc;
    	$cl_arreold-> k00_numtot = $k00_numtot;
    	$cl_arreold-> k00_numdig = $k00_numdig;
    	$cl_arreold-> k00_tipo   = $k00_tipo;
    	$cl_arreold-> k00_tipojm = $k00_tipojm;
	    $cl_arreold-> incluir();
    	if ($cl_arreold->erro_status == 0){
				$sqlerro = true;
				//echo"entrei no erro do arreold.....";
				//die($cl_arreold->erro_sql);
				$erro_msg = $cl_arreold->erro_msg;
		}
		if ($sqlerro==false){
		  // altera o numpre velho para desativado
			
			$sqlseq=  "select q32_sequencial from issplannumpre where q32_planilha = $planilha and q32_numpre=$k00_numpre";
			$rsseq = db_query($sqlseq);
			$linhasseq = pg_num_rows($rsseq);
			if($linhasseq > 0){
				db_fieldsmemory($rsseq,0);
				$cl_issplannumpre->q32_sequencial=$q32_sequencial;
			  $cl_issplannumpre-> q32_status  = 2;
			  $cl_issplannumpre->alterar($q32_sequencial);
		
			  if ($cl_issplannumpre->erro_status == 0){
				  $sqlerro = true;
				  $erro_msg = $cl_issplannumpre->erro_msg;
			  }
			}
				
		}
		//die("!akiiiiiiiiiiii");
		if ($sqlerro==false){
		
			// deletar os dados do arrecad................................................
    		$cl_arrecad-> excluir(null,"k00_numpre = $q20_numpre");
    		
    		if ($cl_arrecad->erro_status == 0){
				$sqlerro = true;
				//echo"entrei no erro do exclui arrecad.....";
				//die($cl_arrecad->erro_sql);
				$erro_msg = $cl_arrecad->erro_msg;
			}  	
		}
						
		/*if ($sqlerro==false){
			
			$sql = $cl_issvar-> sql_query_file (null,"*","","q05_numpre= $q20_numpre");
			$res = db_query($sql); 	
			db_fieldsmemory($res,0);
			
			// deletar os dados da issvar .........................................
    		$cl_issvar-> excluir($q05_codigo);
    		
    		if ($cl_issvar->erro_status == 0){
				$sqlerro = true;
				echo"entrei no erro do exclui issvar.....";
				die($cl_issvar->erro_sql);
				$erro_msg = $cl_issvar->erro_msg;
			}  	
		}*/
			
			
		if ($sqlerro==false){ // gerar um novo numpre ok
	  	
			//$mes = 0;
			//$ano = 0;
    
    		db_query("begin");
    		// gerar um novo registro no arrecad ok
    		
		    $clquery->sql_query(""," nextval('numpref_k03_numpre_seq') as q20_numpre");
		    $clquery->sql_record($clquery->sql);
		    db_fieldsmemory($clquery->result,0);
		    
				// inclui issplannumpre e issplannumpreissplan
		
				$cl_issplannumpre->q32_planilha = $planilha;
				$cl_issplannumpre->q32_numpre   = $q20_numpre;
				$cl_issplannumpre->q32_dataop   = date("Y-m-d");
				$cl_issplannumpre->q32_horaop   = date("H:i");
				$cl_issplannumpre->q32_status   = 1 ;
				$cl_issplannumpre->incluir(null);
				if ($cl_issplannumpre->erro_status == 0) {
		      $sqlerro = true;
		      $erro_msg = $cl_issplannumpre->erro_msg;
		    }
				 
				 
				if($sqlerro == false){
					$sqlnotas = " select q21_sequencial from issplanit where q21_planilha = $planilha and q21_status = 1 ";
         
					$resultnotas = db_query($sqlnotas);
					$linhasnotas = pg_num_rows($resultnotas);
					if($linhasnotas > 0){
						for($n=0 ; $n<$linhasnotas ; $n++ ){
						  db_fieldsmemory($resultnotas,$n);
							
							$cl_issplannumpreissplanit->q77_issplanit     = $q21_sequencial;
							$cl_issplannumpreissplanit->q77_issplannumpre = $cl_issplannumpre-> q32_sequencial;
							$cl_issplannumpreissplanit->incluir(null);
							if ($cl_issplannumpreissplanit->erro_status == 0) {
		            $sqlerro = true;
		            $erro_msg = $cl_issplannumpreissplanit->erro_msg;
		          }
						}
					}
				}
				//************
				
				
				
		    // alterar o numpre e a  situação da issplan para reemitido.(4) ok
		    $clquery->sql_update("issplan","q20_numpre = $q20_numpre, q20_situacao= 4","q20_planilha = $planilha");
		    $clquery->sql_query("issplan left join issplanit on q20_planilha = q21_planilha ","q20_ano,q20_mes,q20_numcgm,sum(q21_valor) ",""," q20_numpre = $q20_numpre and q21_status = 1 group by q20_ano,q20_mes,q20_numcgm");
		   // die($clquery->sql_query("issplan left join issplanit on q20_planilha = q21_planilha ","q20_ano,q20_mes,q20_numcgm,sum(q21_valor) ",""," q20_numpre = $q20_numpre group by q20_ano,q20_mes,q20_numcgm"));
		    $clquery->sql_record($clquery->sql);
			// insere na issvar ......................
		    db_fieldsmemory($clquery->result,0); 
		    
		    // gerar um novo registro na issvar ok
		    $result = db_query("insert into issvar (q05_codigo,
		            q05_numpre,q05_numpar,q05_valor,q05_ano,q05_mes,q05_histor,q05_aliq,q05_bruto,q05_vlrinf)
		              values(nextval('issvar_q05_codigo_seq'),$q20_numpre,1,$sum,".$q20_ano.",".$q20_mes.",'issqn retenção na fonte',0,0,0)");
		    $q20_mes += 1;
		    if($q20_mes > 12){ 
		      $q20_mes = 1;
		      $q20_ano += 1;
		    }
		    // gerar um novo registro no arrecad ok
		    
		   // $sqlvalor="select sum(q21_valor) from issplan inner join issplanit on q20_planilha=q21_planilha where q20_numpre = $q20_numpre";
		  
		   // $resultvalor= db_query($sqlvalor);
		   // db_fieldsmemory($resultvalor,0);
		    
		      
		    $dtarrecad = date($q20_ano."-".$q20_mes."-".$w10_dia);

        $w10_diaoper='01';
        $dtoperaarrecad = date($q20_ano."-".$q20_mes."-".$w10_diaoper);


			  $sql = "insert into arrecad (
		            k00_numcgm,k00_dtoper,k00_receit,k00_hist,k00_valor,k00_dtvenc,k00_numpre,k00_numpar,k00_numtot,k00_numdig,k00_tipo,k00_tipojm)
		              values(".$q20_numcgm.",'".$dtoperaarrecad."',".$w10_receit.",".$w10_hist.",round(".$sum.",2),'".$dtarrecad."',".$q20_numpre.",1,1,1,".$w10_tipo.",0)";
		    $result= db_query($sql) or die ($sql);
		    
		    $sql1 = "select * from issplaninscr where q24_planilha=$planilha";
		    $result1= db_query($sql1) or die ($sql1);
		    if (pg_num_rows($result1) > 0 ) {
		      
		      db_fieldsmemory($result1,0);
		      if($q24_inscr!=0){
		       // gerar um novo registro na arreinscr ok
		        $result= db_query("insert into arreinscr (k00_numpre,k00_inscr) values(".$q20_numpre.",".$q24_inscr.")");
		      }
		    }  
		    db_query("commit");
		
		
		    $HTTP_POST_VARS["CHECK1"] = $q20_numpre."P1";
		    $HTTP_POST_VARS["pdf"] = $q20_numpre;
		    $HTTP_POST_VARS["ver_inscr"] = $q24_inscr;
		    $HTTP_POST_VARS["ver_numcgm"] = $q20_numcgm;
		    $tipo = $w10_tipo;
		
		    /*if($q24_inscr==0){
		    	echo "<script>location.href='recibo.php?erropdf=true&pdf=$q20_numpre&CHECK1=".$q20_numpre."P1&ver_numcgm=".$q20_numcgm."&tipo=".$tipo."&dtpaga=".$dtpaga."';</script>";
		    }else{
		    	echo "<script>location.href='recibo.php?erropdf=true&pdf=$q20_numpre&CHECK1=".$q20_numpre."P1&ver_inscr=".$q24_inscr."&tipo=".$tipo."&dtpaga=".$dtpaga."';</script>";
			  }
			*/
		}
		
    // verifica se tem ordem de pagamewnto para esta planilha
        $sqlpla = "select q96_pagordem from issplanitop 
		              inner join issplanit on q96_issplanit = q21_sequencial 
		              inner join issplan on q20_planilha = q21_planilha 
		              where q20_planilha = $planilha limit 1";
        
		   $resultpla = db_query($sqlpla);
		   $linhaspla = pg_num_rows($resultpla);
		   if($linhaspla > 0){
		     db_fieldsmemory($resultpla,0);
		     // se tiver ordem grava na cairetordem
             $cl_cairetordem -> k32_numpre = $q20_numpre;
             $cl_cairetordem -> k32_ordpag = $q96_pagordem;
             $cl_cairetordem -> incluir(null);
		     if($cl_cairetordem ->erro_status == '0'){
		       db_msgbox("$cl_cairetordem->erro_msg");
               db_query("rollback");
             }
		   }
		
		db_fim_transacao($sqlerro);
    	   		
    
    }else{ // se não foi alterado os dados da planilha depois de emitir o recibo
	    // alterar a situação para 4 .........................................................
	    $clquery->sql_update("issplan","q20_situacao= 4","q20_planilha = $planilha");
	    $clquery->sql_query("issvar left join arreinscr on q05_numpre = k00_numpre inner join arrecad on arrecad.k00_numpre = issvar.q05_numpre"," q05_ano,q05_mes,k00_dtvenc ",""," q05_numpre = ".$q20_numpre);
		$clquery->sql_record($clquery->sql);
	
	    if($clquery->numrows==0){
	      echo "<script>window.close();window.opener.alert(' Não é possivel gerar recibo. Por favor, contate com a prefeitura.');window.opener.location.href='digitaissqn.php'</script>";
	      exit;
	    }
	    db_fieldsmemory($clquery->result,0);
	 
	    if($k00_dtvenc > $dtpaga){
	      $dtpaga = $k00_dtvenc;
	    }
	  	 
        
	    
	 /* $sql1 = "select * from issplaninscr where q24_planilha=$planilha";
	  $result1= db_query($sql1) or die ($sql1);
	  db_fieldsmemory($result1,0);*/
	 
	  $HTTP_POST_VARS["CHECK1"] = $q20_numpre."P1";
	  $HTTP_POST_VARS["ver_inscr"] = $q24_inscr;
	  $HTTP_POST_VARS["ver_numcgm"] = $q20_numcgm;
	  $HTTP_POST_VARS["pdf"] = $q20_numpre;
	  $tipo = $w10_tipo;
	  $ver_numcgm = $HTTP_GET_VARS["q20_numcgm"];
    }
  }

  include("recibo.php");
   
?>