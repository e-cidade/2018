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

include("classes/db_orcppaval_classe.php");
include("classes/db_orcppavalele_classe.php");
include("classes/db_orcppatiporec_classe.php");
include("classes/db_orcppa_classe.php");
include("classes/db_orcelemento_classe.php");

include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clorcppaval     = new cl_orcppaval;
$clorcppavalele  = new cl_orcppavalele;
$clorcppatiporec = new cl_orcppatiporec;
$clorcelemento   = new cl_orcelemento;
$clorcppa = new cl_orcppa;
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
      if(substr($chave,0,9)=="o24_valor" && current($vt) != ''){

	if(empty($proces)){
	  $proces = '0';
	}
	
        $ano   =  substr($chave,10);
	$valor = current($vt);
	$quant = $vt["o24_quantmed_$ano"];
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o26_codigo_$ano"];
	
	$clorcppaval->o24_codppa = $o24_codppa;
	$clorcppaval->o24_exercicio = $ano;
	$clorcppaval->o24_valor = $valor;
	$clorcppaval->o24_quantmed = $quant;
	$clorcppaval->o24_proces = $proces;
	$clorcppaval->incluir(null);
	$erro_msg = $clorcppaval->erro_msg;
	if($clorcppaval->erro_status==0){
	  $sqlerro=true;
	      break;
	}else{
	    $codseqppa = $clorcppaval->o24_codseqppa;
          if($primproces==true){
            $primproces = false;

	    $proces = $codseqppa;

	    $clorcppaval->o24_proces = $proces;
	    $clorcppaval->alterar($proces);
	    $erro_msg = $clorcppaval->erro_msg;
       	    if($clorcppaval->erro_status==0){
	      $sqlerro=true;
	      break;
            }
          }
	}
	
	//rotina de inclusão no orcppatiporec
	if($sqlerro == false ){
	    $clorcppatiporec->o26_codseqppa = $codseqppa;
	    $clorcppatiporec->o26_codigo    = $rec;
	    $clorcppatiporec->incluir($codseqppa,$rec);
	    $erro_msg = $clorcppatiporec->erro_msg;
       	    if($clorcppatiporec->erro_status==0){
	      $sqlerro=true;
	      break;
            }
	}
	

        //rotina de inclusao no orcppvalele
        if($sqlerro == false && $ele != '' && $ele != 0){
	  $codele = substr($ele,0,7)."000000";
  	  $result = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,'o56_codele','o56_elemento',"o56_anousu = ".db_getsession("DB_anousu")." and  o56_elemento = '$codele' "));
          if($clorcelemento->numrows > 0 ){
	    db_fieldsmemory($result,0);
	    $clorcppavalele->o25_codseqppa = $codseqppa;
	    $clorcppavalele->o25_codele = $o56_codele;
	    $clorcppavalele->incluir($codseqppa,$o56_codele);
	    $erro_msg = $clorcppavalele->erro_msg;
	    if($clorcppavalele->erro_status==0){
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
      if(substr($chave,0,9)=="o24_valor"){
        
	$ano   =  substr($chave,10);
	$valor = current($vt);
	$quant = $vt["o24_quantmed_$ano"];
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o26_codigo_$ano"];
	$codseqppa = $vt["o24_codseqppa_$ano"];

        if($valor != ""){
	  //orcppaval-----------------------------------------
          $result = $clorcppaval->sql_record($clorcppaval->sql_query_file(null,"o24_codseqppa","","o24_proces=$o24_proces and o24_exercicio=$ano and o24_codppa=$o24_codppa"));
          if( $clorcppaval->numrows>0){
	    $quant = ($quant==''?$quant='0':$quant);
	    $valor = ($valor==''?$valor='0':$valor);

	    $clorcppaval->o24_valor = $valor;
	    $clorcppaval->o24_quantmed = $quant;
	    $clorcppaval->o24_codseqppa = $codseqppa;
	    $clorcppaval->alterar($codseqppa);
	    $erro_msg = $clorcppaval->erro_msg;
	    if($clorcppaval->erro_status==0){
	      $sqlerro=true;
		break;
	    }
	  }else{
	      $clorcppaval->o24_codppa = $o24_codppa;
	      $clorcppaval->o24_exercicio = $ano;
	      $clorcppaval->o24_valor = $valor;
	      $clorcppaval->o24_quantmed = $quant;
	      $clorcppaval->o24_proces = $o24_proces;
	      $clorcppaval->incluir(null);
	      $codseqppa = $clorcppaval->o24_codseqppa;
	      $erro_msg = $clorcppaval->erro_msg;
	      if($clorcppaval->erro_status==0){
		$sqlerro=true;
		break;
	      }
	  }  
	  //final
	  //-------------------------------------------------
	  

	  //rotina exclusão orcppatiporec------------------------------------------------
	  if($sqlerro == false && isset($codseqppa) && $codseqppa != ''){
            $result = $clorcppatiporec->sql_record($clorcppatiporec->sql_query_file($codseqppa));
            if($clorcppatiporec->numrows>0){
	      $clorcppatiporec->o26_codseqppa = $codseqppa;
	      $clorcppatiporec->excluir($codseqppa);
	      $erro_msg = $clorcppatiporec->erro_msg;
	      if($clorcppatiporec->erro_status==0){
		$sqlerro=true;
		break;
	      }
	    }  
	  }
          //final
	  //-----------------------------------------------------------------------------------
	  
	  //rotina de inclusão no orcppatiporec------------------------------------------------
	  if($sqlerro == false ){
	    $clorcppatiporec->o26_codseqppa = $codseqppa;
	    $clorcppatiporec->o26_codigo    = $rec;
	    $clorcppatiporec->incluir($codseqppa,$rec);
	    $erro_msg = $clorcppatiporec->erro_msg;
	    if($clorcppatiporec->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
	  }
          //final
	  //-----------------------------------------------------------------------------------


	  //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	  if($sqlerro == false && isset($codseqppa) && $codseqppa != ''){
	     $result = $clorcppavalele->sql_record($clorcppavalele->sql_query($codseqppa,'',"o56_elemento"));
	     if($clorcppavalele->numrows>0){
		$clorcppavalele->o25_codseqppa = $codseqppa;
		$clorcppavalele->excluir($codseqppa);
		$erro_msg = $clorcppavalele->erro_msg;
		if($clorcppavalele->erro_status==0){
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
	      $clorcppavalele->o25_codele = $o56_codele;
	      $clorcppavalele->o25_codseqppa = $codseqppa;
	      $clorcppavalele->incluir($codseqppa,$o56_codele);
	      $erro_msg = $clorcppavalele->erro_msg;
	      if($clorcppavalele->erro_status==0){
		$sqlerro=true;
		break;
	      }
	    }else{
	      $erro_msg = "Não econtrado o código do elemento $o56_codele";
	      $sqlerro = true;
	      break;
	    } 
	  }
	  //-------------------------------------------------------------------------------------------------------------
	  
	}else{
          $result = $clorcppaval->sql_record($clorcppaval->sql_query_file(null,"o24_codseqppa","","o24_proces=$o24_proces and o24_exercicio=$ano and o24_codppa=$o24_codppa"));
          if( $clorcppaval->numrows>0){
	    db_fieldsmemory($result,0);

	      //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	      if($sqlerro == false && isset($codseqppa) && $codseqppa != ''){
		 $result = $clorcppavalele->sql_record($clorcppavalele->sql_query($codseqppa,'',"o56_elemento"));
		 if($clorcppavalele->numrows>0){
		    $clorcppavalele->o25_codseqppa = $codseqppa;
		    $clorcppavalele->excluir($codseqppa);
		    $erro_msg = $clorcppavalele->erro_msg;
		    if($clorcppavalele->erro_status==0){
		      $sqlerro=true;
		      break;
		    }
		 }
	      }
	      //final 
	      //-------------------------------------------------------------------------------------------------------------

	      //rotina exclusão orcppatiporec------------------------------------------------
	      if($sqlerro == false && isset($codseqppa) && $codseqppa != ''){
		$result = $clorcppatiporec->sql_record($clorcppatiporec->sql_query_file($codseqppa));
		if($clorcppatiporec->numrows>0){
		  $clorcppatiporec->o26_codseqppa = $codseqppa;
		  $clorcppatiporec->excluir($codseqppa);
		  $erro_msg = $clorcppatiporec->erro_msg;
		  if($clorcppatiporec->erro_status==0){
		    $sqlerro=true;
		    break;
		  }
		}  
	      }
	      //final
	      //-----------------------------------------------------------------------------------


	      //rotina de exclusão de orcppaval------------
		$clorcppaval->o24_codseqppa = $codseqppa;
		$clorcppaval->excluir($codseqppa);
		$erro_msg = $clorcppaval->erro_msg;
		if($clorcppaval->erro_status==0){
		  $sqlerro=true;
		    break;
		}
              //---------------------------------------------		
	    
	  }
        }
  
      } 
      next($vt);
   }
    db_fim_transacao($sqlerro);
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
      if(substr($chave,0,9)=="o24_valor"){
        
	$ano   =  substr($chave,10);
	$valor = current($vt);
	$quant = $vt["o24_quantmed_$ano"];
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o26_codigo_$ano"];
	$codseqppa = $vt["o24_codseqppa_$ano"];

	$result = $clorcppaval->sql_record($clorcppaval->sql_query_file(null,"o24_codseqppa","","o24_proces=$o24_proces and o24_exercicio=$ano and o24_codppa=$o24_codppa"));
	if( $clorcppaval->numrows>0){
	  db_fieldsmemory($result,0);

	    //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	    if($sqlerro == false && isset($codseqppa) && $codseqppa != ''){
	       $result = $clorcppavalele->sql_record($clorcppavalele->sql_query($codseqppa,'',"o56_elemento"));
	       if($clorcppavalele->numrows>0){
		  $clorcppavalele->o25_codseqppa = $codseqppa;
		  $clorcppavalele->excluir($codseqppa);
		  $erro_msg = $clorcppavalele->erro_msg;
		  if($clorcppavalele->erro_status==0){
		    $sqlerro=true;
		    break;
		  }
	       }
	    }
	    //final 
	    //-------------------------------------------------------------------------------------------------------------

	    //rotina exclusão orcppatiporec------------------------------------------------
	    if($sqlerro == false && isset($codseqppa) && $codseqppa != ''){
	      $result = $clorcppatiporec->sql_record($clorcppatiporec->sql_query_file($codseqppa));
	      if($clorcppatiporec->numrows>0){
		$clorcppatiporec->o26_codseqppa = $codseqppa;
		$clorcppatiporec->excluir($codseqppa);
		$erro_msg = $clorcppatiporec->erro_msg;
		if($clorcppatiporec->erro_status==0){
		  $sqlerro=true;
		  break;
		}
	      }  
	    }
	    //final
	    //-----------------------------------------------------------------------------------


	    //rotina de exclusão de orcppaval------------
	      $clorcppaval->o24_codseqppa = $codseqppa;
	      $clorcppaval->excluir($codseqppa);
	      $erro_msg = $clorcppaval->erro_msg;
	      if($clorcppaval->erro_status==0){
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
}else if(isset($opcao)){
  //no formulario
}
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
	include("forms/db_frmorcppaval.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$clorcppaval->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcppaval->erro_campo.".focus();</script>";
    }
}
?>