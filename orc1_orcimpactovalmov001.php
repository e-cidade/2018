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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");

include("classes/db_orcimpactovalmov_classe.php");
include("classes/db_orcimpactovalmovmes_classe.php");
include("classes/db_orcimpactoval_classe.php");
include("classes/db_orcimpactovalmovele_classe.php");
include("classes/db_orcimpactomovtiporec_classe.php");
include("classes/db_orcimpactomov_classe.php");
include("classes/db_orcimpactomovpai_classe.php");
include("classes/db_orcimpactomovimp_classe.php");
include("classes/db_orcimpacto_classe.php");
include("classes/db_orcelemento_classe.php");

include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcimpactovalmov     = new cl_orcimpactovalmov;
$clorcimpactovalmovmes  = new cl_orcimpactovalmovmes;
$clorcimpactoval        = new cl_orcimpactoval;
$clorcimpactovalmovele  = new cl_orcimpactovalmovele;
$clorcimpactomovtiporec = new cl_orcimpactomovtiporec;
$clorcelemento   = new cl_orcelemento;
$clorcimpactomov = new cl_orcimpactomov;
$clorcimpactomovpai = new cl_orcimpactomovpai;
$clorcimpactomovimp = new cl_orcimpactomovimp;
$clorcimpacto = new cl_orcimpacto;
$db_opcao = 22;
$db_botao = false;

if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
}  
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();

    $vt=$HTTP_POST_VARS;
    $ta=sizeof($vt);
    reset($vt);
    $primproces = true;
    for($i=0; $i<$ta; $i++){
      $chave=key($vt);
      if(substr($chave,0,9)=="o64_valor" && current($vt) != ''){

	if(empty($proces)){
	  $proces = '0';
	}
	
        $ano   =  substr($chave,10);
	$valor = current($vt);
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o67_codigo_$ano"];
	
	$clorcimpactovalmov->o64_codimpmov = $o64_codimpmov;
	$clorcimpactovalmov->o64_exercicio = $ano;
	$clorcimpactovalmov->o64_valor = $valor;
	$clorcimpactovalmov->o64_proces = $proces;
	$clorcimpactovalmov->incluir(null);
	$erro_msg = $clorcimpactovalmov->erro_msg;
	if($clorcimpactovalmov->erro_status==0){
	  $sqlerro=true;
	      break;
	}else{
	    $codseqimpmov = $clorcimpactovalmov->o64_codseqimpmov;
          if($primproces==true){
            $primproces = false;

	    $proces = $codseqimpmov;

	    $clorcimpactovalmov->o64_proces = $proces;
	    $clorcimpactovalmov->alterar($proces);
	    $erro_msg = $clorcimpactovalmov->erro_msg;
       	    if($clorcimpactovalmov->erro_status==0){
	      $sqlerro=true;
	      break;
            }
          }
	}
	
	//rotina de inclusão no orcimpactomovtiporec
	if($sqlerro == false ){
	    $clorcimpactomovtiporec->o67_codseqimpmov = $codseqimpmov;
	    $clorcimpactomovtiporec->o67_codigo    = $rec;
	    $clorcimpactomovtiporec->incluir($codseqimpmov,$rec);
	    $erro_msg = $clorcimpactomovtiporec->erro_msg;
       	    if($clorcimpactomovtiporec->erro_status==0){
	      $sqlerro=true;
	      break;
            }
	}
	

        //rotina de inclusao no orcppvalele
        if($sqlerro == false && $ele != '' && $ele != 0){
	  $codele = substr($ele,0,7)."000000";
  	  $result = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,'o56_codele','o56_elemento'," o56_anousu = ".db_getsession("DB_anousu")." and  o56_elemento = '$codele' "));
          if($clorcelemento->numrows > 0 ){
	    db_fieldsmemory($result,0);
	    $clorcimpactovalmovele->o66_codseqimpmov = $codseqimpmov;
	    $clorcimpactovalmovele->o66_codele = $o56_codele;
	    $clorcimpactovalmovele->incluir($codseqimpmov,$o56_codele);
	    $erro_msg = $clorcimpactovalmovele->erro_msg;
	    if($clorcimpactovalmovele->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
	  }else{
	    $erro_msg = "Não econtrado o código do elemento $o56_codele";
	    $sqlerro = true;
	  } 
	}
	
      }  
      next($vt);
    }
    db_fim_transacao($sqlerro);

    if($sqlerro == false){
      $tavaincluindo = true;
      $opcao = "alterar";
      $o64_codseqimpmov = $codseqimpmov;
      $o64_proces = $proces;
      unset($incluir);
    }
  }  
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();

    $vt=$HTTP_POST_VARS;
    $ta=sizeof($vt);
    reset($vt);
    $primproces = true;
    for($i=0; $i<$ta; $i++){
      $chave=key($vt);
      if(substr($chave,0,9)=="o64_valor"){
        
	$ano   =  substr($chave,10);
	$valor = current($vt);
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o67_codigo_$ano"];
	$codseqimpmov = $vt["o64_codseqimpmov_$ano"];

        if($valor != ""){
	  //orcimpactovalmov-----------------------------------------
          $result = $clorcimpactovalmov->sql_record($clorcimpactovalmov->sql_query_file(null,"o64_codseqimpmov","","o64_proces=$o64_proces and o64_exercicio=$ano"));
          if( $clorcimpactovalmov->numrows>0){
	    $valor = ($valor==''?$valor='0':$valor);

	    $clorcimpactovalmov->o64_valor = $valor;
	    $clorcimpactovalmov->o64_codseqimpmov = $codseqimpmov;
	    $clorcimpactovalmov->alterar($codseqimpmov);
	    $erro_msg = $clorcimpactovalmov->erro_msg;
	    if($clorcimpactovalmov->erro_status==0){
	      $sqlerro=true;
		break;
	    }
	  }else{
	      $clorcimpactovalmov->o64_codimpmov = $o64_codimp;
	      $clorcimpactovalmov->o64_exercicio = $ano;
	      $clorcimpactovalmov->o64_valor = $valor;
	      $clorcimpactovalmov->o64_proces = $o64_proces;
	      $clorcimpactovalmov->incluir(null);
	      $codseqimpmov = $clorcimpactovalmov->o64_codseqimpmov;
	      $erro_msg = $clorcimpactovalmov->erro_msg;
	      if($clorcimpactovalmov->erro_status==0){
		$sqlerro=true;
		break;
	      }
	  }  
	  //final
	  //-------------------------------------------------
	  

	  //rotina exclusão orcimpactomovtiporec------------------------------------------------
	  if($sqlerro == false && isset($codseqimpmov) && $codseqimpmov != ''){
            $result = $clorcimpactomovtiporec->sql_record($clorcimpactomovtiporec->sql_query_file($codseqimpmov));
            if($clorcimpactomovtiporec->numrows>0){
	      $clorcimpactomovtiporec->o67_codseqimpmov = $codseqimpmov;
	      $clorcimpactomovtiporec->excluir($codseqimpmov);
	      $erro_msg = $clorcimpactomovtiporec->erro_msg;
	      if($clorcimpactomovtiporec->erro_status==0){
		$sqlerro=true;
		break;
	      }
	    }  
	  }
          //final
	  //-----------------------------------------------------------------------------------
	  
	  //rotina de inclusão no orcimpactomovtiporec------------------------------------------------
	  if($sqlerro == false ){
	    $clorcimpactomovtiporec->o67_codseqimpmov = $codseqimpmov;
	    $clorcimpactomovtiporec->o67_codigo    = $rec;
	    $clorcimpactomovtiporec->incluir($codseqimpmov,$rec);
	    $erro_msg = $clorcimpactomovtiporec->erro_msg;
	    if($clorcimpactomovtiporec->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
	  }
          //final
	  //-----------------------------------------------------------------------------------


	  //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	  if($sqlerro == false && isset($codseqimpmov) && $codseqimpmov != ''){
	     $result = $clorcimpactovalmovele->sql_record($clorcimpactovalmovele->sql_query($codseqimpmov,'',"o56_elemento"));
	     if($clorcimpactovalmovele->numrows>0){
		$clorcimpactovalmovele->o66_codseqimpmov = $codseqimpmov;
		$clorcimpactovalmovele->excluir($codseqimpmov);
		$erro_msg = $clorcimpactovalmovele->erro_msg;
		if($clorcimpactovalmovele->erro_status==0){
		  $sqlerro=true;
		  break;
		}
	     }
	  }
	  //final 
	  //-------------------------------------------------------------------------------------------------------------

	  //rotina de inclusao no orcppvalele------------------------------------------------------------------------
	  if($sqlerro == false && $ele != '' && $ele != 0){
	    $codele = substr($ele,0,7)."000000";
	    $result = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,'o56_codele','o56_elemento'," o56_anousu = ".db_getsession("DB_anousu")." and  o56_elemento = '$codele' "));
	    if($clorcelemento->numrows > 0 ){
	      db_fieldsmemory($result,0);
	      $clorcimpactovalmovele->o66_codele = $o56_codele;
	      $clorcimpactovalmovele->o66_codseqimp = $codseqimpmov;
	      $clorcimpactovalmovele->incluir($codseqimpmov,$o56_codele);
	      $erro_msg = $clorcimpactovalmovele->erro_msg;
	      if($clorcimpactovalmovele->erro_status==0){
		$sqlerro=true;
		break;
	      }
	    }else{
	      $erro_msg = "Não econtrado o código do elemento $codele";
	      $sqlerro = true;
	      break;
	    } 
	  }
	  //-------------------------------------------------------------------------------------------------------------
	  
	}else{
          $result = $clorcimpactovalmov->sql_record($clorcimpactovalmov->sql_query_file(null,"o64_codseqimpmov","","o64_proces=$o64_proces and o64_exercicio=$ano"));
          if( $clorcimpactovalmov->numrows>0){
	    db_fieldsmemory($result,0);
           
	   //------------------------------------------------------------------------------------------------------- 
           //exclui do orcimpactovalmovmes
              $clorcimpactovalmovmes->sql_record($clorcimpactovalmovmes->sql_query_file($codseqimpmov));
	      $numrows = $clorcimpactovalmovmes->numrows;
	      if($numrows > 0){
		$clorcimpactovalmovmes->o65_codseqimpmov = $codseqimpmov;
		$clorcimpactovalmovmes->excluir($codseqimpmov);
                $erro_msg = $clorcimpactovalmovmes->erro_msg;
	        if($clorcimpactovalmovmes->erro_status==0){
		    $sqlerro=true;
		    break;
		}
              }
	   //final
	   //------------------------------------------------------------------------------------------------------- 

	      //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	      if($sqlerro == false && isset($codseqimpmov) && $codseqimpmov != ''){
		 $result = $clorcimpactovalmovele->sql_record($clorcimpactovalmovele->sql_query($codseqimpmov,'',"o56_elemento"));
		 if($clorcimpactovalmovele->numrows>0){
		    $clorcimpactovalmovele->o66_codseqimp = $codseqimpmov;
		    $clorcimpactovalmovele->excluir($codseqimpmov);
		    $erro_msg = $clorcimpactovalmovele->erro_msg;
		    if($clorcimpactovalmovele->erro_status==0){
		      $sqlerro=true;
		      break;
		    }
		 }
	      }
	      //final 
	      //-------------------------------------------------------------------------------------------------------------

	      //rotina exclusão orcimpactomovtiporec------------------------------------------------
	      if($sqlerro == false && isset($codseqimpmov) && $codseqimpmov != ''){
		$result = $clorcimpactomovtiporec->sql_record($clorcimpactomovtiporec->sql_query_file($codseqimpmov));
		if($clorcimpactomovtiporec->numrows>0){
		  $clorcimpactomovtiporec->o67_codseqimpmov = $codseqimpmov;
		  $clorcimpactomovtiporec->excluir($codseqimpmov);
		  $erro_msg = $clorcimpactomovtiporec->erro_msg;
		  if($clorcimpactomovtiporec->erro_status==0){
		    $sqlerro=true;
		    break;
		  }
		}  
	      }
	      //final
	      //-----------------------------------------------------------------------------------


	      //rotina de exclusão de orcimpactovalmov------------
		$clorcimpactovalmov->o64_codseqimpmov = $codseqimpmov;
		$clorcimpactovalmov->excluir($codseqimpmov);
		$erro_msg = $clorcimpactovalmov->erro_msg;
		if($clorcimpactovalmov->erro_status==0){
		  $sqlerro=true;
		    break;
		}
              //---------------------------------------------		
	    
	  }
        }
  
      } 
      next($vt);
   }
 //  echo $erro_msg;exit;
    db_fim_transacao($sqlerro);
    if($sqlerro == false){
      $opcao = "alterar";
      $o64_codseqimpmov = $codseqimpmov;
      //$o64_proces = $proces;
      unset($alterar);
    }

  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();

    $vt=$HTTP_POST_VARS;
    $ta=sizeof($vt);
    reset($vt);
    $primproces = true;
    for($i=0; $i<$ta; $i++){
      $chave=key($vt);
      if(substr($chave,0,9)=="o64_valor"){
        
	$ano   =  substr($chave,10);
	$valor = current($vt);
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o67_codigo_$ano"];
	$codseqimpmov = $vt["o64_codseqimpmov_$ano"];



	$result = $clorcimpactovalmov->sql_record($clorcimpactovalmov->sql_query_file(null,"o64_codseqimpmov","","o64_proces=$o64_proces and o64_exercicio=$ano"));
	if( $clorcimpactovalmov->numrows>0){
	  db_fieldsmemory($result,0);

           //------------------------------------------------------------------------------------------------------- 
           //exclui do orcimpactovalmovmes
              $clorcimpactovalmovmes->sql_record($clorcimpactovalmovmes->sql_query_file($codseqimpmov));
	      $numrows = $clorcimpactovalmovmes->numrows;
	      if($numrows > 0){
		$clorcimpactovalmovmes->o65_codseqimpmov = $codseqimpmov;
		$clorcimpactovalmovmes->excluir($codseqimpmov);
                $erro_msg = $clorcimpactovalmovmes->erro_msg;
	        if($clorcimpactovalmovmes->erro_status==0){
		    $sqlerro=true;
		    break;
		}
              }
	   //final
	   //------------------------------------------------------------------------------------------------------- 

       
 
	    //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	    if($sqlerro == false && isset($codseqimpmov) && $codseqimpmov != ''){
	       $result = $clorcimpactovalmovele->sql_record($clorcimpactovalmovele->sql_query_file($codseqimpmov));
	       if($clorcimpactovalmovele->numrows>0){
		  $clorcimpactovalmovele->o66_codseqimpmov = $codseqimpmov;
		  $clorcimpactovalmovele->excluir($codseqimpmov);	
		  $erro_msg = $clorcimpactovalmovele->erro_msg;
		  if($clorcimpactovalmovele->erro_status==0){
		    $sqlerro=true;
		    break;
		  }
	       }
	    }
	    //final 
	    //-------------------------------------------------------------------------------------------------------------

	    //rotina exclusão orcimpactomovtiporec------------------------------------------------
	    if($sqlerro == false && isset($codseqimpmov) && $codseqimpmov != ''){
	      $result = $clorcimpactomovtiporec->sql_record($clorcimpactomovtiporec->sql_query_file($codseqimpmov));
	      if($clorcimpactomovtiporec->numrows>0){
		$clorcimpactomovtiporec->o67_codseqimpmov = $codseqimpmov;
		$clorcimpactomovtiporec->excluir($codseqimpmov);
		$erro_msg = $clorcimpactomovtiporec->erro_msg;
		if($clorcimpactomovtiporec->erro_status==0){
		  $sqlerro=true;
		  break;
		}
	      }  
	    }
	    //final
	    //-----------------------------------------------------------------------------------

	    //rotina de exclusão de orcimpactovalmov------------
	      $clorcimpactovalmov->o64_codseqimpmov = $codseqimpmov;
	      $clorcimpactovalmov->excluir($codseqimpmov);
              $erro_msg = $clorcimpactovalmov->erro_msg;
	      if($clorcimpactovalmov->erro_status==0){
		$sqlerro=true;
		  break;
	      }
	    //---------------------------------------------		
	}
      } 
      next($vt);
   }
    db_fim_transacao($sqlerro);
  }
}

/*
if( (isset($opcao) && $opcao=="alterar") || isset($tavaincluindo) ){
    echo "      
        <script> 
           top.corpo.iframe_orcimpactovalmovmes.location.href='orc1_orcimpactovalmovmes001.php?o64_proces=$o64_proces&o65_codimpmov=".$o64_codimpmov."';
           parent.document.formaba.orcimpactovalmovmes.disabled=false;";
	if(isset($tavaincluindo)){
                echo "\nparent.mo_camada('orcimpactovalmovmes');";
	  
	}  
      echo "
         </script> 
	" ;
}else{
    echo "      
        <script> 
           parent.document.formaba.orcimpactovalmovmes.disabled=true;
        </script> 
	" ;
}
*/
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcimpactovalmov.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$clorcimpactovalmov->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcimpactovalmov->erro_campo.".focus();</script>";
    }
}
?>