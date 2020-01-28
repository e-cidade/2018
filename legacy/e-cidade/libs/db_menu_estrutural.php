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

/*  classe cl_estrutura
 *  monta <table> com estruturas das contas debito e credito fornecidas
 *  monta duas div, -> div_conplano e div_conplanosis
 *
 */
 include_once("classes/db_conplano_classe.php");
 include_once("classes/db_conplanosis_classe.php");

 class menu_estrutural extends cl_conplano  {

    var $estrut_debito;
    var $estrut_debito_descr;
    var $estrut_credito;
    var $estrut_credito_descr;
    
    var $mostra_conta = null;

    function menu_estrutural(){
     //-- constructor
    }  
    function monta_select(){
     echo"<center>
          <select id=\"tipo_conta\" name=\"tipo_conta\" size=\"1\" onchange=\"js_plano_contas()\">
               <option value=\"plano\">Plano de Contas</option>
               <option value=\"sistema\">Sistema de Contas</option>
          </select>
         <script>
            function js_plano_contas(){
	    var a = document.getElementById(\"div_conplano\");
	    var b = document.getElementById(\"div_conplanosis\");
	    var s = document.getElementById(\"tipo_conta\");
	    if (s.value==\"plano\"){
	        a.style.visibility='visible';
	        b.style.visibility='hidden';
	    }else {
	        a.style.visibility='hidden';
	        b.style.visibility='visible';
	    }  
	} 
        </script>";      
    }

    function show_conta(){

        //----- conplano ---------------------------------------
	 echo "<div id=\"div_conplano\" style=\"visibility:visible;position:absolute\">";
         echo "<table border=0 align=center>
               <tr><td><strong>Estrutural</strong></td><td><strong>Descrição </strong></b></td></tr>     
               <tr><td colspan='2'><hr></td></tr>     
              ";
         $matriz01=array();
	 $matriz02=array();
	 $matriz_descr=array();
         $cont=0;
	 $cont02=0;
	 $f_estrut=$this->estrut_debito;
	 $f_descr=$this->estrut_debito_descr;
         $nivel = db_le_mae_conplano($this->estrut_debito,true); // retorna nivel
         $testamae=false;
         if($nivel==1){
  	      $testamae=false;
         }else{
    	      $back=$cont;//caso não tenha mae, a variavel cont terá o mesmo valor quando entrou
	      $mae   = $this->estrut_debito;
              while($nivel!=1){
	          $mae   = db_le_mae_conplano($mae,false);
	          $nivel = db_le_mae_conplano($mae,true);
	          $result65 = parent::sql_record(parent::sql_query_file(null,"c60_estrut as estrut,c60_descr as descr",null,"c60_estrut='$mae'"));
	          if($this->numrows>0){
	               //db_fieldsmemory($result65,0,true,true);
	               if($nivel==1){
	                    $testamae=true;
	               }
	               $matriz01[$cont]= pg_result($result65,0,"estrut");  //$this->estrut; 
	               $matriz_descr[$cont]= pg_result($result65,0,"descr");      // $this->descr;
	               $cont++;
	          }else{
	               $nivel=1;
	          } 
	      }
         }	
         if($testamae==true){
   	     for($t=count($matriz01); $t>0; $t--){
	          $tt=intval($t-1);  /*rotina para verificar se a estrutura nao esta se repetindo*/
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
	          $matriz02[$cont02]=$matriz01[$tt];    
	          $cont02++;
	          //-- out screen
                  $nivel02 = db_le_mae_conplano($matriz01[$tt],true);
                  $espaco=$this->php_espaco($nivel02);
		  echo "<tr>
		         <td nowrap> <strong>$matriz01[$tt] </strong></td>
			 <td nowrap> <strong>$espaco$matriz_descr[$tt] </strong></td>
			</tr> 
		       ";		  
	     }
         }
         unset($matriz01);
         unset($matriz_descr);
	 $nivel02 = db_le_mae_conplano($f_estrut,true);
	 $espaco=$this->php_espaco($nivel02);
	 echo "<tr> <td nowrap> $f_estrut </td>
	            <td nowrap> $espaco$f_descr </td>
	       </tr>	    	     
	      ";
    }

    
    function show(){

        $this->monta_select(); 
        //----- conplano ---------------------------------------
	 echo "<div id=\"div_conplano\" style=\"visibility:visible;position:absolute\">";
         echo "<table border=0 align=center>
	       <tr><td colspan=2><b>Conta Débito             </td></tr>
               <tr><td>Estrutural</td><td>Descrição </b></td></tr>     
              ";
         $matriz01=array();
	 $matriz02=array();
	 $matriz_descr=array();
         $cont=0;
	 $cont02=0;
	 $f_estrut=$this->estrut_debito;
	 $f_descr=$this->estrut_debito_descr;
         $nivel = db_le_mae_conplano($this->estrut_debito,true); // retorna nivel
         $testamae=false;
         if($nivel==1){
  	      $testamae=false;
         }else{
    	      $back=$cont;//caso não tenha mae, a variavel cont terá o mesmo valor quando entrou
	      $mae   = $this->estrut_debito;
              while($nivel!=1){
	          $mae   = db_le_mae_conplano($mae,false);
	          $nivel = db_le_mae_conplano($mae,true);
	          $result65 = parent::sql_record(parent::sql_query_file(null,"c60_estrut as estrut,c60_descr as descr",null,"c60_estrut='$mae'"));
	          if($this->numrows>0){
	               //db_fieldsmemory($result65,0,true,true);
	               if($nivel==1){
	                    $testamae=true;
	               }
	               $matriz01[$cont]= pg_result($result65,0,"estrut");  //$this->estrut; 
	               $matriz_descr[$cont]= pg_result($result65,0,"descr");      // $this->descr;
	               $cont++;
	          }else{
	               $nivel=1;
	          } 
	      }
         }	
         if($testamae==true){
   	     for($t=count($matriz01); $t>0; $t--){
	          $tt=intval($t-1);  /*rotina para verificar se a estrutura nao esta se repetindo*/
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
	          $matriz02[$cont02]=$matriz01[$tt];    
	          $cont02++;
	          //-- out screen
                  $nivel02 = db_le_mae_conplano($matriz01[$tt],true);
                  $espaco=$this->php_espaco($nivel02);
		  echo "<tr>
		         <td nowrap> $matriz01[$tt] </td>
			 <td nowrap> $espaco.$matriz_descr[$tt] </td>
			</tr> 
		       ";		  
	     }
         }
         unset($matriz01);
         unset($matriz_descr);
	 $nivel02 = db_le_mae_conplano($f_estrut,true);
	 $espaco=$this->php_espaco($nivel02);
	 echo "<tr> <td nowrap> $f_estrut </td>
	            <td nowrap> $espaco.$f_descr </td>
	       </tr>	    	     
	      ";
         //-- credito
	 echo "<tr><td colspan=2><b> Conta Crédito            </td></tr>
               <tr><td>Estrutural</td><td>Descrição </b> </td></tr>     
              ";
         $matriz01=array();
	 $matriz02=array();
	 $matriz_descr=array();
         $cont=0;
	 $cont02=0;
	 $f_estrut=$this->estrut_credito;
	 $f_descr=$this->estrut_credito_descr;
         $nivel = db_le_mae_conplano($this->estrut_credito,true); // retorna nivel
         $testamae=false;
         if($nivel==1){
  	      $testamae=false;
         }else{
    	      $back=$cont;//caso não tenha mae, a variavel cont terá o mesmo valor quando entrou
	      $mae   = $this->estrut_credito;
              while($nivel!=1){
	          $mae   = db_le_mae_conplano($mae,false);
	          $nivel = db_le_mae_conplano($mae,true);
	          $result65 = parent::sql_record(parent::sql_query_file(null,"c60_estrut as estrut,c60_descr as descr",null,"c60_estrut='$mae'"));
	          if($this->numrows>0){
	               //db_fieldsmemory($result65,0,true,true);
	               if($nivel==1){
	                    $testamae=true;
	               }
	               $matriz01[$cont]= pg_result($result65,0,"estrut");  //$this->estrut; 
	               $matriz_descr[$cont]= pg_result($result65,0,"descr");      // $this->descr;
	               $cont++;
	          }else{
	               $nivel=1;
	          } 
	      }
         }	
         if($testamae==true){
   	     for($t=count($matriz01); $t>0; $t--){
	          $tt=intval($t-1);  /*rotina para verificar se a estrutura nao esta se repetindo*/
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
	          $matriz02[$cont02]=$matriz01[$tt];    
	          $cont02++;
	          //-- out screen
                  $nivel02 = db_le_mae_conplano($matriz01[$tt],true);
                  $espaco=$this->php_espaco($nivel02);
		  echo "<tr>
		         <td nowrap> $matriz01[$tt] </td>
			 <td nowrap> $espaco.$matriz_descr[$tt] </td>
			</tr> 
		       ";		  
	     }
         }
         unset($matriz01);
         unset($matriz_descr);
	 $nivel02 = db_le_mae_conplano($f_estrut,true);
	 $espaco=$this->php_espaco($nivel02);
	 echo "<tr> <td nowrap> $f_estrut </td>
	            <td nowrap> $espaco.$f_descr </td>
	       </tr>	    	     
	      ";
 
	 echo "</table>";
	 echo "</div>";
         //------------------------------------------------------------
	 /* conplanosis */
	 echo "<div id=\"div_conplanosis\" style=\"visibility:hidden;position:absolute\">";
         echo "<table border=0 align=center>
	       <tr><td colspan=2><b> Conta Débito             </td></tr>
               <tr><td>Estrutural</td><td>Descrição </b></td></tr>     
              ";
         $matriz01=array();
	 $matriz02=array();
	 $matriz_descr=array();
         $cont=0;
	 $cont02=0;
	 $f_estrut=$this->estrut_debito;
	 $f_descr=$this->estrut_debito_descr;
         $nivel = db_le_mae_sistema($this->estrut_debito,true); // retorna nivel
         $testamae=false;
         if($nivel==1){
  	      $testamae=false;
         }else{
    	      $back=$cont;//caso não tenha mae, a variavel cont terá o mesmo valor quando entrou
	      $mae   = $this->estrut_debito;
              while($nivel!=1){
	          $mae   = db_le_mae_sistema($mae,false);
	          $nivel = db_le_mae_sistema($mae,true);
	          //$result65 = parent::sql_record(parent::sql_query_file(null,"c60_estrut as estrut,c60_descr as descr",null,"c60_estrut='$mae'"));
                  $result65 = parent::sql_record("select c64_estrut as estrut,c64_descr as descr from conplanosis where c64_estrut='$mae'");

	          if($this->numrows>0){
	               //db_fieldsmemory($result65,0,true,true);
	               if($nivel==1){
	                    $testamae=true;
	               }
	               $matriz01[$cont]= pg_result($result65,0,"estrut");  //$this->estrut; 
	               $matriz_descr[$cont]= pg_result($result65,0,"descr");      // $this->descr;
	               $cont++;
	          }else{
	               $nivel=1;
	          } 
	      }
         }	
         if($testamae==true){
   	     for($t=count($matriz01); $t>0; $t--){
	          $tt=intval($t-1);  /*rotina para verificar se a estrutura nao esta se repetindo*/
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
	          $matriz02[$cont02]=$matriz01[$tt];    
	          $cont02++;
	          //-- out screen
                  $nivel02 = db_le_mae_sistema($matriz01[$tt],true);
                  $espaco=$this->php_espaco($nivel02);
		  echo "<tr>
		         <td nowrap> $matriz01[$tt] </td>
			 <td nowrap> $espaco$matriz_descr[$tt] </td>
			</tr> 
		       ";		  
	     }
         }
         unset($matriz01);
         unset($matriz_descr);
	 $nivel02 = db_le_mae_sistema($f_estrut,true);
	 $espaco=$this->php_espaco($nivel02);
	 echo "<tr> <td nowrap> $f_estrut </td>
	            <td nowrap> $espaco$f_descr </td>
	       </tr>	    	     
	      ";
         //-- credito
	 echo "<tr><td colspan=2><b>Conta Crédito            </td></tr>
               <tr><td>Estrutural</td><td>Descrição  </b></td></tr>     
              ";
         $matriz01=array();
	 $matriz02=array();
	 $matriz_descr=array();
         $cont=0;
	 $cont02=0;
	 $f_estrut=$this->estrut_credito;
	 $f_descr=$this->estrut_credito_descr;
         $nivel = db_le_mae_sistema($this->estrut_credito,true); // retorna nivel
         $testamae=false;
         if($nivel==1){
  	      $testamae=false;
         }else{
    	      $back=$cont;//caso não tenha mae, a variavel cont terá o mesmo valor quando entrou
	      $mae   = $this->estrut_credito;
              while($nivel!=1){
	          $mae   = db_le_mae_sistema($mae,false);
	          $nivel = db_le_mae_sistema($mae,true);
	          $result65 = parent::sql_record("select c64_estrut as estrut,c64_descr as descr from conplanosis where c64_estrut='$mae'");
		        //  sql_conplanosis("c60_estrut as estrut,c60_descr as descr",null,"c60_estrut='$mae'"));
		  if($this->numrows>0){
	               if($nivel==1){
	                    $testamae=true;
	               }
	               $matriz01[$cont]= pg_result($result65,0,"estrut");    // $this->estrut; 
	               $matriz_descr[$cont]= pg_result($result65,0,"descr"); // $this->descr;
	               $cont++;
	          }else{
	               $nivel=1;
	          } 
	      }
         }	
         if($testamae==true){
   	     for($t=count($matriz01); $t>0; $t--){
	          $tt=intval($t-1);  /*rotina para verificar se a estrutura nao esta se repetindo*/
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
	          $matriz02[$cont02]=$matriz01[$tt];    
	          $cont02++;
	          //-- out screen
                  $nivel02 = db_le_mae_sistema($matriz01[$tt],true);
                  $espaco=$this->php_espaco($nivel02);
		  echo "<tr>
		         <td nowrap> $matriz01[$tt] </td>
			 <td nowrap> $espaco$matriz_descr[$tt] </td>
			</tr> 
		       ";		  
	     }
         }
         unset($matriz01);
         unset($matriz_descr);
	 $nivel02 = db_le_mae_sistema($f_estrut,true);
	 $espaco=$this->php_espaco($nivel02);
	 echo "<tr> <td nowrap> $f_estrut </td>
	            <td nowrap> $espaco$f_descr </td>
	       </tr>	    	     
	      ";
 
	 echo "</table>";
	 echo "</div>";
  
    }//- end função

   //------------------------
   function php_espaco($nivel){
      $espaco="";
      switch($nivel){
 	 case 1:
		$espaco="";
		break;
	 case 2:
		$espaco="&nbsp ";
		break;
	 case 3:
		$espaco="&nbsp &nbsp ";
		break;
	 case 4:
		$espaco="&nbsp &nbsp &nbsp ";
     		break;
	 case 5:
		$espaco="&nbsp &nbsp &nbsp &nbsp ";       
		break;
	 case 6:
	        $espaco="&nbsp &nbsp &nbsp &nbsp &nbsp ";
		break;
	 case 7:
	        $espaco="&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp ";
		break;
	 case 8:
	        $espaco="&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp ";
		break;
       }
       return $espaco;
   } // end php_espaco  



 }  //- end classe 


?>