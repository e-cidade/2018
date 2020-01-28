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
include("libs/db_sql.php");
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("classes/db_conplano_classe.php");
include("classes/db_conplanoreduz_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_conplanosis_classe.php");
include("classes/db_orctiporec_classe.php");
include("classes/db_conplanoconta_classe.php");

$clconplanoconta = new cl_conplanoconta;
$clconplanosis = new cl_conplanosis;
$clconplano = new cl_conplano;
$clorctiporec = new cl_orctiporec;
$cldb_config = new cl_db_config;
$clconplanoreduz = new cl_conplanoreduz;
$clrotulo = new rotulocampo;
$clrotulo->label("o15_descr");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$instit=str_replace("-",",",$instit);

function php_espaco($nivel){
      $espaco="";
      switch($nivel){
	case 1:
		$espaco="";
		break;
	case 2:
		$espaco=" ";
		break;
	case 3:
		$espaco="    ";
		break;
	case 4:
		$espaco="       ";
		break;
	case 5:
		$espaco="           ";
		break;
	case 6:
		$espaco="              ";
		break;
	case 7:
		$espaco="                  ";
		break;
	case 8:
		$espaco="                      ";
		break;
      }
      return $espaco;
}

$head3 = "PLANO DE CONTAS ";
$head4 = "";
$head5 = "EXERCICIO: ".db_getsession("DB_anousu");

pg_exec("begin");


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$matriz01=array();
$matriz02=array();
$cont02=0;
if($origem=="R"){
    //-- classe $conplanosis->sql_vs_planocontas($c64_codpla, campos, ordem , where )
/*    $result = $clconplanosis->sql_record($clconplanosis->sql_vs_planocontas(null,
                     "c64_estrut,
		      c60_estrut,
		      c61_reduz,
		      c64_descr,
		      c62_codrec,
		      o15_descr",
                     'c64_estrut,c60_estrut',"c62_anousu = ".db_getsession("DB_anousu")." and c61_instit in ($instit)"));

*/
    $result = $clconplanosis->sql_record(
         $clconplanosis->sql_vs_planocontas(null,
	        "c64_estrut,
		 c60_estrut,
		 c61_reduz,
		 c60_descr,
		 c62_codrec,
		 o15_descr",'c60_estrut'," c62_anousu = ".db_getsession("DB_anousu")." and c61_instit in ($instit)"));

    if ($conplanosis->numrows == 0){
      db_redireciona('db_erros.php?fechar=true&db_erro=Não existem itens cadastrados.');
    }


    $alt = 4;
    $pagina = 1;
    // for
    //db_criatabela($result);
    //exit;
    for($i=0;$i<pg_numrows($result);$i++){
         db_fieldsmemory($result,$i);
        if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    	   $pagina = 0;
    	   $pdf->addpage();
	   $pdf->setfont('arial','b',7);
	
 	   $pdf->cell(35,$alt,"Estrutural",1,0,"C",0);
	   $pdf->cell(15,$alt,"Reduz",1,0,"C",0);
	   $pdf->cell(80,$alt,"Descrição",1,0,"C",0);
         //$pdf->cell(6,$alt,"Clas",1,0,"C",0);
         //$pdf->cell(6,$alt,"Sist",1,0,"C",0);
	   $pdf->cell(11,$alt,"Recurso",1,0,"C",0);
	   $pdf->cell(30,$alt,$RLo15_descr,1,1,"C",0);
         } 

      
         $matriz01=array();
         $matriz_descr=array();
         $cont=0;
         $nivel = db_le_mae_sistema($c64_estrut,true); // false - conta mae, true= nivel
         $testamae=false;

         // ---
         if($nivel==1){
	     $testamae=false;
         }else{
  	     $back=$cont;//caso não tenha mae, a variavel cont terá o mesmo valor quando entrou
	     $mae   = $c64_estrut;
             while($nivel!=1){

	        $mae   = db_le_mae_sistema($mae,false);//retorna mae !
	        $nivel = db_le_mae_sistema($mae,true); // retorna nivel
	        $result65 = $clconplanosis->sql_record(
	                         $clconplanosis->sql_query_file(null,
		                        "c64_estrut as estrut,
			                 c64_descr as descr",null,"c64_estrut='$mae'"));
                //--
                if($clconplanosis->numrows>0){
	             db_fieldsmemory($result65,0);
	             if($nivel==1){
	                 $testamae=true;
	             }
	      	     $matriz01[$cont]=$estrut;
	             $matriz_descr[$cont]=$descr;
	             $cont++;
	        }else{
	             $nivel=1;
	        }
	     }
        } // --	

        if($testamae==true){
	    for($t=count($matriz01);  $t>0; $t--){
	         $tt=intval($t-1);
	         /*rotina para verificar se a estrutura nao esta se repetindo*/
                 $testa_repete=true; 
	         for($f=0; $f<count($matriz02); $f++){
	            if($matriz02[$f]==$matriz01[$tt]){
                         $testa_repete=false; 
		         break;
                    }
	         }
	         if($testa_repete==false){
	              continue;
	         }	
	         /*fim*/

	     
	         $matriz02[$cont02]=$matriz01[$tt];    
	         $cont02++; 
	   
                 $nivel02 = db_le_mae_sistema($matriz01[$tt],true);
                 $espaco=php_espaco($nivel02);
	
	         $pdf->cell(35,$alt,$matriz01[$tt],0,0,"L",0);
	         $pdf->cell(15,$alt,'',0,0,"L",0);
	         $pdf->cell(80,$alt,$espaco.$matriz_descr[$tt],0,0,"L",0);
	     //  $pdf->cell(6,$alt,'',0,0,"C",0);
	     //  $pdf->cell(6,$alt,'',0,0,"C",0);
	         $pdf->cell(11,$alt,'',0,0,"L",0);
	         $pdf->cell(30,$alt,'',0,1,"L",0);
	     }
      }
      unset($matriz01);
      unset($matriz_descr);

      
	  /*rotina para verificar se a estrutura nao esta se repetindo*/
      $testa_repete=true; 
      for($f=0; $f<count($matriz02); $f++){
	      if($matriz02[$f]==$c60_estrut){
                   $testa_repete=false; 
   		   break;
              }
      }
      if($testa_repete==false){
	      continue;
      }	
      /*rotina para dar espaçamento para as descrições*/	
      $nivel = db_le_mae_sistema($c60_estrut,true);
      $espaco=php_espaco($nivel);
      $pdf->cell(35,$alt,$c60_estrut,0,0,"L",1);
      $pdf->cell(15,$alt,$c61_reduz,0,0,"R",1);
      $pdf->cell(80,$alt,$espaco.$c60_descr,0,0,"L",1);
      // $pdf->cell(6,$alt,substr($c51_descr,0,1),0,0,"L",1);
      // $pdf->cell(6,$alt,$c52_descrred,0,0,"L",1);
      $pdf->cell(11,$alt,$c62_codrec,0,0,"L",1);
      $pdf->cell(30,$alt,$o15_descr,0,1,"L",1);
     
 }
    
        $pdf->Output();

////////////////////////////////////////////////
	
}else if($origem=='C'){

    $clrotulo->label("nomeinst");
    $clrotulo->label("c64_estrut");
    $clrotulo->label("c64_descr");
    $clrotulo->label("c61_codigo");

    $result = $clconplanosis->sql_record($clconplanosis->sql_vs_planocontas(null,
            "c60_estrut,
	     c64_estrut,
	     c64_descr,
	     c61_reduz, 
	     c60_descr,
	     c62_codrec, 
	     c63_banco,
	     c63_agencia,
	     c63_conta,
	     o15_descr",
	     'c60_estrut'," c62_anousu = ".db_getsession("DB_anousu")." and c61_instit in ($instit)"));


    $alt = 4;
    $pagina = 1;
    // for
    for($i=0;$i<pg_numrows($result);$i++){
         db_fieldsmemory($result,$i);
	 // -- cabeçalho das paginas
         if($pdf->gety()>$pdf->h-30 || $pagina ==1){
	     $pagina = 0;
   	     $pdf->addpage("L");
	     $pdf->setfont('arial','b',7);
    	     $pdf->cell(122,$alt,"CONPLANO SISTEMA",1,0,"C",0);
	     $pdf->cell(117,$alt,"CONPLANOREDUZ",1,0,"C",0);
	     $pdf->cell(30,$alt,"CONPLANOCONTA",1,1,"C",0);
   	     $pdf->cell(18,$alt,"Estrutural",1,0,"C",0);
	     $pdf->cell(8,$alt,"Reduz",1,0,"C",0);
	     $pdf->cell(60,$alt,"Descrição",1,0,"C",0);
	     $pdf->cell(6,$alt,"Rec",1,0,"C",0);
	     $pdf->cell(30,$alt,$RLo15_descr,1,0,"C",0);
	     $pdf->cell(30,$alt,$RLnomeinst,1,0,"C",0);
	     $pdf->cell(18,$alt,$RLc64_estrut,1,0,"C",0);
	     $pdf->cell(44,$alt,$RLc64_descr,1,0,"C",0);
   	     $pdf->cell(25,$alt,$RLc61_codigo,1,0,"C",0);
	     $pdf->cell(6,$alt,"Ban",1,0,"C",0);
	     $pdf->cell(6,$alt,"Age",1,0,"C",0);
	     $pdf->cell(18,$alt,"Conta",1,1,"C",0);
	     $pdf->setfont('arial','B',6);
         } 
      
         $matriz01=array();
         $matriz_descr=array();
         $cont=0;
         $nivel = db_le_mae_sistema($c60_estrut,true); // false - conta mae, true= nivel
         $testamae=false;

         // ---
         if($nivel==1){
	     $testamae=false;
         }else{
  	     $back=$cont;//caso não tenha mae, a variavel cont terá o mesmo valor quando entrou
	     $mae   = $c60_estrut;
             while($nivel!=1){

	        $mae   = db_le_mae_sistema($mae,false);//retorna mae !
	        $nivel = db_le_mae_sistema($mae,true); // retorna nivel
	        $result65 = $clconplanosis->sql_record(
	                         $clconplanosis->sql_query_file(null,
		                        "c64_estrut as estrut,
			                 c64_descr as descr",null,"c64_estrut='$mae'"));
                //--
                if($clconplanosis->numrows>0){
	             db_fieldsmemory($result65,0);
	             if($nivel==1){
	                 $testamae=true;
	             }
	      	     $matriz01[$cont]=$estrut;
	             $matriz_descr[$cont]=$descr;
	             $cont++;
	        }else{
	             $nivel=1;
	        }
	     }
        } // --	

        if($testamae==true){
	    for($t=count($matriz01);  $t>0; $t--){
	         $tt=intval($t-1);
	         /*rotina para verificar se a estrutura nao esta se repetindo*/
                 $testa_repete=true; 
	         for($f=0; $f<count($matriz02); $f++){
	            if($matriz02[$f]==$matriz01[$tt]){
                         $testa_repete=false; 
		         break;
                    }
	         }
	         if($testa_repete==false){
	              continue;
	         }	
	         /*fim*/

	     
	         $matriz02[$cont02]=$matriz01[$tt];    
	         $cont02++; 
	         $nivel02 = db_le_mae_sistema($matriz01[$tt],true);
                 $espaco=php_espaco($nivel02);
	         // -- sisteticas
    	         $pdf->cell(18,$alt,$matriz01[$tt],0,0,"L",0);
	         $pdf->cell(8,$alt,'',0,0,"C",0);
	         $pdf->cell(60,$alt,$espaco.$matriz_descr[$tt],0,0,"L",0);
	         $pdf->cell(6,$alt,'',0,0,"L",0);
	         $pdf->cell(30,$alt,'',0,0,"L",0);
	         $pdf->cell(30,$alt,'',0,0,"L",0);
	         $pdf->cell(18,$alt,'',0,0,"L",0);
	         $pdf->cell(44,$alt,'',0,0,"L",0);
	         $pdf->cell(25,$alt,'',0,0,"L",0);
	         $pdf->cell(6,$alt,"",0,0,"L",0);
	         $pdf->cell(6,$alt,"",0,0,"L",0);
	         $pdf->cell(18,$alt,"",0,1,"L",0);
	     }
      }
      unset($matriz01);
      unset($matriz_descr);

      
	  /*rotina para verificar se a estrutura nao esta se repetindo*/
      $testa_repete=true; 
      for($f=0; $f<count($matriz02); $f++){
	      if($matriz02[$f]==$c60_estrut){
                   $testa_repete=false; 
   		   break;
              }
      }
      if($testa_repete==false){
	      continue;
      }	
      // -- outros dados
     
      /*rotina para dar espaçamento para as descrições*/	
      $nivel = db_le_mae_sistema($c60_estrut,true);
      $espaco=php_espaco($nivel);

      $pdf->cell(18,$alt,$c60_estrut,0,0,"L",1);
      $pdf->cell(8,$alt,$c61_reduz,0,0,"R",1);
      $pdf->cell(60,$alt,$espaco.$c60_descr,0,0,"L",1);
      $pdf->cell(6,$alt,$c62_codrec,0,0,"L",1);
      $pdf->cell(30,$alt,$o15_descr,0,0,"L",1);
      $pdf->cell(30,$alt,$nomeinst,0,0,"L",1);
      $pdf->cell(18,$alt,$c64_estrut,0,0,"L",1);
      $pdf->cell(44,$alt,$c64_descr,0,0,"L",1);
      $pdf->cell(25,$alt,$descr,0,0,"L",1);
      $pdf->cell(6,$alt,$c63_banco,0,0,"L",1);
      $pdf->cell(6,$alt,$c63_agencia,0,0,"L",1);
      $pdf->cell(18,$alt,$c63_conta,0,1,"L",1);
   
    }
    
    $pdf->Output();
}

//////////////////////////////////////

pg_exec("commit");

?>
