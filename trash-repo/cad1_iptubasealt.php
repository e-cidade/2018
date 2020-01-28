<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_matricobs_classe.php");
require_once("classes/db_iptuant_classe.php");
require_once("classes/db_iptubaseregimovel_classe.php");
require_once("classes/db_iptubasecondominio_classe.php");
require_once("classes/db_iptubasepredio_classe.php");

db_postmemory($_GET)  ;
db_postmemory($_POST);

$db_botao=1;
$db_opcao=1;
$db_opcao_matric=1; 
$outros=false;

$cliptubasecondominio  = new cl_iptubasecondominio();
$cliptubasepredio      = new cl_iptubasepredio();	
$cliptubase            = new cl_iptubase;
$cliptuant             = new cl_iptuant;
$clmatricobs           = new cl_matricobs;
$cliptubaseregimovel   = new cl_iptubaseregimovel;
$clrotulo              = new rotulocampo;

$cliptubase->rotulo->label();
$cliptubase->rotulo->label();

$clrotulo->label("z01_nome");
$clrotulo->label("j34_setor");
$clrotulo->label("j26_obs");
$clrotulo->label("j40_refant");

$sqlerro  = false;
$sErroMsg = "";

if(isset($incluir)){
    db_inicio_transacao();
    $cliptubase->j01_matric = $j01_matric;
    $cliptubase->j01_numcgm = $j01_numcgm;
    $cliptubase->j01_idbql  = $j01_idbql; 
    $cliptubase->j01_codave = "1";
    $cliptubase->j01_fracao = $j01_fracao;
	
	if($cliptubase->j01_fracao > 100){
	  $sqlerro= true;
      $sErroMsg = "Fração do Lote maior que 100%";
	}
	 
    if($sqlerro == false){
	 $cliptubase->incluir($j01_matric);    
     if($cliptubase->erro_status==0){
       $sqlerro  = true;
       $sErroMsg = $cliptubase->erro_msg;  
     }
    }
    
    $j01_matric = $cliptubase->j01_matric;
    
	
    if ($j26_obs != "" && $sqlerro == false) {
      $clmatricobs->j26_obs = $j26_obs; 
      $clmatricobs->incluir($j01_matric);
      if( $clmatricobs->erro_status==0 ) {
        $sqlerro= true;
        $sErroMsg = $clmatricobs->erro_msg;
      }
    }
    
    if(isset($j40_refant) && $j40_refant != "" && $sqlerro == false){
    	
    	$cliptuant->j40_refant = $j40_refant;      
      $cliptuant->incluir($j01_matric);
      if ($cliptuant->erro_status==0) {
        $sqlerro= true;
        $sErroMsg = $cliptuant->erro_msg;
      }
    }
    
    // INCLUIR NA IPTUBASEREGIMOVEL ... SE TIVER REGISTROS
    if($sqlerro==false){
			if($j04_setorregimovel!=""){
			  $cliptubaseregimovel->j04_setorregimovel = $j04_setorregimovel;
			  $cliptubaseregimovel->j04_matric         = $j01_matric;
			  $cliptubaseregimovel->j04_matricregimo   = $j04_matricregimo;
			  $cliptubaseregimovel->j04_quadraregimo   = $j04_quadraregimo;
			  $cliptubaseregimovel->j04_loteregimo     = $j04_loteregimo;
			  $cliptubaseregimovel->incluir(null);
			  if($cliptubaseregimovel->erro_status==0){
               $sqlerro= true;
               $sErroMsg = $cliptubaseregimovel->erro_msg;
              }
			}
    }
    
		if($sqlerro==false){
    	    	    	
	    if(isset($j107_sequencial) && trim($j107_sequencial) != "" && isset($predios) && $predios != 0){
	    	//insiro na iptubasepredio
	    	$cliptubasepredio->j109_predio = $predios;
	    	$cliptubasepredio->j109_matric = $j01_matric;
	    	$cliptubasepredio->incluir(null);
	    	if($cliptubasepredio->erro_status==0){
	    		$sqlerro = true;
	    		$sErroMsg = $cliptubasepredio->erro_msg;
	    	}
	    	
	    }else if(isset($j107_sequencial) && trim($j107_sequencial) != ""){
	    	
	    	$cliptubasecondominio->j108_condominio = $j107_sequencial;
	    	$cliptubasecondominio->j108_matric		 = $j01_matric;
	    	$cliptubasecondominio->incluir(null);
	    	
	    	if($cliptubasecondominio->erro_status==0){
	    		$sqlerro = true;
	    		//db_msgbox($cliptubasecondominio->erro_msg);
	    		$sErroMsg = $cliptubasecondominio->erro_msg;
	    	}
	    }
    }
    
    db_fim_transacao($sqlerro);
    if($sqlerro==false){
    	$cliptubase->erro_msg;	
    }else{
    	$cliptubase->erro_msg = $sErroMsg;
    }
    
}else if (isset($alterar)) {
	
    db_inicio_transacao();
    $cliptubase->j01_matric = $j01_matric;
    $cliptubase->j01_numcgm = $j01_numcgm;
    $cliptubase->j01_idbql  = $j01_idbql; 
    $cliptubase->j01_codave = "1";
    $cliptubase->j01_fracao = $j01_fracao; 
	
	if($cliptubase->j01_fracao > 100){
	  $sqlerro= true;
      $sErroMsg = "Fração do Lote maior que 100%";
	}
	
	if($sqlerro == false){
     $cliptubase->alterar($j01_matric); 
     $sErroMsg = $cliptubase->erro_msg;
     if ($cliptubase->erro_status == 0){
       $sqlerro= true;
       $sErroMsg = $cliptubase->erro_msg;
     }
	}
    
    if($sqlerro == false){
	 $resultmatricobs = $clmatricobs->sql_record($clmatricobs->sql_query_file($j01_matric,'j26_matric'));
     if ($clmatricobs->numrows > 0){
       $clmatricobs->excluir($j01_matric);
       if( $clmatricobs->erro_status==0 ) {
        $sqlerro= true;
        $sErroMsg = $clmatricobs->erro_msg;
       }
     }
    }
    
    if ($j26_obs != "" && $sqlerro == false) {
      $clmatricobs->j26_obs = $j26_obs; 
      $clmatricobs->incluir($j01_matric);
      if($clmatricobs->erro_status==0){
        $sqlerro  = true;
        $sErroMsg = $clmatricobs->erro_msg;
      } 
    }
       
	if($sqlerro == false){   
     $resultiptuant = $cliptuant->sql_record($cliptuant->sql_query_file($j01_matric,'j40_matric'));
     if ($cliptuant->numrows > 0){
      $cliptuant->excluir($j01_matric);
      if($cliptuant->erro_status==0){
        $sqlerro  = true;
        $sErroMsg = $cliptuant->erro_msg;
      }
     }
	}
	
    if ($j40_refant != "" && $sqlerro == false) {
      $cliptuant->j40_refant=$j40_refant;
      $cliptuant->j40_matric=$j01_matric;
      $cliptuant->incluir($j01_matric);
      if($cliptuant->erro_status==0){
        $sqlerro  = true;
        $sErroMsg = $cliptuant->erro_msg;
      }
    }
    
 // INCLUIR NA IPTUBASEREGIMOVEL ... SE TIVER REGISTROS
    if($sqlerro==false){
      
      $cliptubaseregimovel-> excluir(null,"j04_matric = $j01_matric" );
      
			if($j04_setorregimovel!=""){
			  $cliptubaseregimovel->j04_setorregimovel = $j04_setorregimovel;
			  $cliptubaseregimovel->j04_matric         = $j01_matric;
			  $cliptubaseregimovel->j04_matricregimo   = $j04_matricregimo;
			  $cliptubaseregimovel->j04_quadraregimo   = $j04_quadraregimo;
			  $cliptubaseregimovel->j04_loteregimo     = $j04_loteregimo;
			  $cliptubaseregimovel->incluir(null);
			  if($cliptubaseregimovel->erro_status==0){
               $sqlerro  = true;
               $sErroMsg = $cliptubaseregimovel->erro_msg;
              }
			}
    }
    
    //INCLUI NA IPTUBASEPREDIO OU IPTUBASECONDOMINIO
    if($sqlerro==false){
    	
    	$cliptubasepredio->excluir(null," j109_matric = $j01_matric ");
    	$cliptubasecondominio->excluir(null," j108_matric = $j01_matric ");
    	
	    if(isset($j107_sequencial) && trim($j107_sequencial) != "" && isset($predios) && $predios != 0){
	    	//insiro na iptubasepredio
	    	$cliptubasepredio->j109_predio = $predios;
	    	$cliptubasepredio->j109_matric = $j01_matric;
	    	$cliptubasepredio->incluir(null);
	    	if($cliptubasepredio->erro_status==0){
	    		$sqlerro = true;
	    		$sErroMsg = $cliptubasepredio->erro_msg;
	    	}
	    	
	    }else if(isset($j107_sequencial) && trim($j107_sequencial) != ""){
	    	
	    	$cliptubasecondominio->j108_condominio = $j107_sequencial;
	    	$cliptubasecondominio->j108_matric		 = $j01_matric;
	    	$cliptubasecondominio->incluir(null);
	    	
	    	if($cliptubasecondominio->erro_status==0){
	    		$sqlerro = true;
	    		//db_msgbox($cliptubasecondominio->erro_msg);
	    		$sErroMsg = $cliptubasecondominio->erro_msg;
	    	}
	    }
    }
    
    db_fim_transacao($sqlerro);
    $cliptubase->erro_msg = $sErroMsg;
    $cancela=false; 
    $db_opcao = 2;

}else if(isset($j01_matric) ||isset($alterando)){
  $resultmatricobs = $clmatricobs->sql_record($clmatricobs->sql_query_file($j01_matric));            
  if ($clmatricobs->numrows != 0){
     @db_fieldsmemory($resultmatricobs,0);
  }
  $resultiptuant = $cliptuant->sql_record($cliptuant->sql_query_file($j01_matric));            
  if ($cliptuant->numrows != 0){
     @db_fieldsmemory($resultiptuant,0);
  }
  $result = $cliptubase->sql_record($cliptubase->sql_query($j01_matric,"j01_numcgm#j01_idbql#j01_codave#j01_fracao#j01_baixa#z01_nome",""));            
  @db_fieldsmemory($result,0);
  $db_opcao=2; 
  $db_opcao_matric=3;
   
  $sqlreg = "select * from iptubaseregimovel where j04_matric = $j01_matric";
  $resultreg= pg_query($sqlreg);
  $linhasreg= pg_num_rows($resultreg);
  if($linhasreg>0){
    db_fieldsmemory($resultreg,0);
  }
  
	$sqlCondominio  = "select * from condominio ";
	$sqlCondominio .= "					inner join iptubasecondominio on j107_sequencial = j108_condominio ";
	$sqlCondominio .= " where j108_matric = $j01_matric ";
	//$sqlCondominio .= "         left join iptubasepredio on j107_sequencial =  j111_sequencial ";
	//$sqlCondominio .= "  where j_matric = $j01_matric";
  
	$resultCondominio= pg_query($sqlCondominio);
  $linhasreg= pg_num_rows($resultCondominio);
  if($linhasreg>0){
    db_fieldsmemory($resultCondominio,0);
  }
  if($linhasreg==0){
	  $sqlPredio  = "select condominio.*,predio.* from condominio ";
		$sqlPredio .= "					inner join predio on j107_sequencial = j111_condominio ";
		$sqlPredio .= "					inner join iptubasepredio on j109_predio = j111_sequencial ";
		$sqlPredio .= " where j109_matric = $j01_matric ";
		//$sqlCondominio .= "         left join iptubasepredio on j107_sequencial =  j111_sequencial ";
		//$sqlCondominio .= "  where j_matric = $j01_matric";
	  
		$resultPredio= pg_query($sqlPredio);
	  $linhasreg= pg_num_rows($resultPredio);
	  if($linhasreg>0){
	    db_fieldsmemory($resultPredio,0);
	  }
  }
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">
    <!--
    td {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
    }
    input {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      height: 17px;
      border: 1px solid #999999;
    }
    -->
    </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <form name="form1" method="post" onSubmit="return js_verifica_campos_digitados();" action="">
      <table height="430" align="center" width="790" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center" valign="top" bgcolor="#CCCCCC">
            <center>
            <? include("forms/db_frmiptubasealt.php"); ?>  
            </center>
          </td>
        </tr>
      </table> 
    </form>
  </body>
</html>
<?

if(isset($incluir) || isset($alterar)){
	
  if($cliptubase->erro_status==0 || $sqlerro==true){
  	
    $cliptubase->erro(true,false);
    if($cliptubase->erro_campo!=""){
      echo "<script> document.form1.".$cliptubase->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptubase->erro_campo.".focus();</script>";
    }
    
  }else{
    if(!isset($cancela) || $cancela!=true){
      db_msgbox($cliptubase->erro_msg);
      echo " <script>
                 parent.document.form1.idmatricula.value=".$cliptubase->j01_matric."; \n           
                 parent.document.form1.nomematricula.value='$z01_nome'; \n          
                 parent.js_parentiframe('matricula',true);
             </script> ";
      db_redireciona("cad1_iptubasealt.php?j01_matric=$cliptubase->j01_matric");
    } 
  }
}
?>