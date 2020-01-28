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

include("classes/db_orcimpactoval_classe.php");
include("classes/db_orcimpactovalele_classe.php");
include("classes/db_orcimpactotiporec_classe.php");
include("classes/db_orcimpacto_classe.php");
include("classes/db_orcelemento_classe.php");

include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcimpactoval     = new cl_orcimpactoval;
$clorcimpactovalele  = new cl_orcimpactovalele;
$clorcimpactotiporec = new cl_orcimpactotiporec;
$clorcelemento   = new cl_orcelemento;
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
      if(substr($chave,0,9)=="o91_valor" && current($vt) != ''){

	if(empty($proces)){
	  $proces = '0';
	}
	
        $ano   =  substr($chave,10);
	$valor = current($vt);
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o93_codigo_$ano"];
	
	$clorcimpactoval->o91_codimp = $o91_codimp;
	$clorcimpactoval->o91_exercicio = $ano;
	$clorcimpactoval->o91_valor = $valor;
	$clorcimpactoval->o91_proces = $proces;
	$clorcimpactoval->incluir(null);
	$erro_msg = $clorcimpactoval->erro_msg;
	if($clorcimpactoval->erro_status==0){
	  $sqlerro=true;
	      break;
	}else{
	    $codseqimp = $clorcimpactoval->o91_codseqimp;
          if($primproces==true){
            $primproces = false;

	    $proces = $codseqimp;

	    $clorcimpactoval->o91_proces = $proces;
	    $clorcimpactoval->alterar($proces);
	    $erro_msg = $clorcimpactoval->erro_msg;
       	    if($clorcimpactoval->erro_status==0){
	      $sqlerro=true;
	      break;
            }
          }
	}
	
	//rotina de inclusão no orcimpactotiporec
	if($sqlerro == false ){
	    $clorcimpactotiporec->o93_codseqimp = $codseqimp;
	    $clorcimpactotiporec->o93_codigo    = $rec;
	    $clorcimpactotiporec->incluir($codseqimp,$rec);
	    $erro_msg = $clorcimpactotiporec->erro_msg;
       	    if($clorcimpactotiporec->erro_status==0){
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
	    $clorcimpactovalele->o96_codseqimp = $codseqimp;
	    $clorcimpactovalele->o96_codele = $o56_codele;
	    $clorcimpactovalele->incluir($codseqimp,$o56_codele);
	    $erro_msg = $clorcimpactovalele->erro_msg;
	    if($clorcimpactovalele->erro_status==0){
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
      $o91_codseqimp = $codseqimp;
      $o91_proces = $proces;
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
      if(substr($chave,0,9)=="o91_valor"){
        
	$ano   =  substr($chave,10);
	$valor = current($vt);
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o93_codigo_$ano"];
	$codseqimp = $vt["o91_codseqimp_$ano"];

        if($valor != ""){
	  //orcimpactoval-----------------------------------------
          $result = $clorcimpactoval->sql_record($clorcimpactoval->sql_query_file(null,"o91_codseqimp","","o91_proces=$o91_proces and o91_exercicio=$ano"));
          if( $clorcimpactoval->numrows>0){
	    $valor = ($valor==''?$valor='0':$valor);

	    $clorcimpactoval->o91_valor = $valor;
	    $clorcimpactoval->o91_codseqimp = $codseqimp;
	    $clorcimpactoval->alterar($codseqimp);
	    $erro_msg = $clorcimpactoval->erro_msg;
	    if($clorcimpactoval->erro_status==0){
	      $sqlerro=true;
		break;
	    }
	  }else{
	      $clorcimpactoval->o91_codimp = $o91_codimp;
	      $clorcimpactoval->o91_exercicio = $ano;
	      $clorcimpactoval->o91_valor = $valor;
	      $clorcimpactoval->o91_proces = $o91_proces;
	      $clorcimpactoval->incluir(null);
	      $codseqimp = $clorcimpactoval->o91_codseqimp;
	      $erro_msg = $clorcimpactoval->erro_msg;
	      if($clorcimpactoval->erro_status==0){
		$sqlerro=true;
		break;
	      }
	  }  
	  //final
	  //-------------------------------------------------
	  

	  //rotina exclusão orcimpactotiporec------------------------------------------------
	  if($sqlerro == false && isset($codseqimp) && $codseqimp != ''){
            $result = $clorcimpactotiporec->sql_record($clorcimpactotiporec->sql_query_file($codseqimp));
            if($clorcimpactotiporec->numrows>0){
	      $clorcimpactotiporec->o93_codseqimp = $codseqimp;
	      $clorcimpactotiporec->excluir($codseqimp);
	      $erro_msg = $clorcimpactotiporec->erro_msg;
	      if($clorcimpactotiporec->erro_status==0){
		$sqlerro=true;
		break;
	      }
	    }  
	  }
          //final
	  //-----------------------------------------------------------------------------------
	  
	  //rotina de inclusão no orcimpactotiporec------------------------------------------------
	  if($sqlerro == false ){
	    $clorcimpactotiporec->o93_codseqimp = $codseqimp;
	    $clorcimpactotiporec->o93_codigo    = $rec;
	    $clorcimpactotiporec->incluir($codseqimp,$rec);
	    $erro_msg = $clorcimpactotiporec->erro_msg;
	    if($clorcimpactotiporec->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
	  }
          //final
	  //-----------------------------------------------------------------------------------


	  //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	  if($sqlerro == false && isset($codseqimp) && $codseqimp != ''){
	     $result = $clorcimpactovalele->sql_record($clorcimpactovalele->sql_query($codseqimp,'',"o56_elemento"));
	     if($clorcimpactovalele->numrows>0){
		$clorcimpactovalele->o96_codseqimp = $codseqimp;
		$clorcimpactovalele->excluir($codseqimp);
		$erro_msg = $clorcimpactovalele->erro_msg;
		if($clorcimpactovalele->erro_status==0){
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
	    $result = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,'o56_codele','o56_elemento',"o56_anousu = ".db_getsession("DB_anousu")." and  o56_elemento = '$codele' "));
	    if($clorcelemento->numrows > 0 ){
	      db_fieldsmemory($result,0);
	      $clorcimpactovalele->o96_codele = $o56_codele;
	      $clorcimpactovalele->o96_codseqimp = $codseqimp;
	      $clorcimpactovalele->incluir($codseqimp,$o56_codele);
	      $erro_msg = $clorcimpactovalele->erro_msg;
	      if($clorcimpactovalele->erro_status==0){
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
          $result = $clorcimpactoval->sql_record($clorcimpactoval->sql_query_file(null,"o91_codseqimp","","o91_proces=$o91_proces and o91_exercicio=$ano"));
          if( $clorcimpactoval->numrows>0){
	    db_fieldsmemory($result,0);

	      //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	      if($sqlerro == false && isset($codseqimp) && $codseqimp != ''){
		 $result = $clorcimpactovalele->sql_record($clorcimpactovalele->sql_query($codseqimp,'',"o56_elemento"));
		 if($clorcimpactovalele->numrows>0){
		    $clorcimpactovalele->o96_codseqimp = $codseqimp;
		    $clorcimpactovalele->excluir($codseqimp);
		    $erro_msg = $clorcimpactovalele->erro_msg;
		    if($clorcimpactovalele->erro_status==0){
		      $sqlerro=true;
		      break;
		    }
		 }
	      }
	      //final 
	      //-------------------------------------------------------------------------------------------------------------

	      //rotina exclusão orcimpactotiporec------------------------------------------------
	      if($sqlerro == false && isset($codseqimp) && $codseqimp != ''){
		$result = $clorcimpactotiporec->sql_record($clorcimpactotiporec->sql_query_file($codseqimp));
		if($clorcimpactotiporec->numrows>0){
		  $clorcimpactotiporec->o93_codseqimp = $codseqimp;
		  $clorcimpactotiporec->excluir($codseqimp);
		  $erro_msg = $clorcimpactotiporec->erro_msg;
		  if($clorcimpactotiporec->erro_status==0){
		    $sqlerro=true;
		    break;
		  }
		}  
	      }
	      //final
	      //-----------------------------------------------------------------------------------


	      //rotina de exclusão de orcimpactoval------------
		$clorcimpactoval->o91_codseqimp = $codseqimp;
		$clorcimpactoval->excluir($codseqimp);
		$erro_msg = $clorcimpactoval->erro_msg;
		if($clorcimpactoval->erro_status==0){
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
    if($sqlerro == false){
      $opcao = "alterar";
      $o91_codseqimp = $codseqimp;
      //$o91_proces = $proces;
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
      if(substr($chave,0,9)=="o91_valor"){
        
	$ano   =  substr($chave,10);
	$valor = current($vt);
	$ele   = $vt["o56_elemento_$ano"];
	$rec   = $vt["o93_codigo_$ano"];
	$codseqimp = $vt["o91_codseqimp_$ano"];

	$result = $clorcimpactoval->sql_record($clorcimpactoval->sql_query_file(null,"o91_codseqimp","","o91_proces=$o91_proces and o91_exercicio=$ano"));
	if( $clorcimpactoval->numrows>0){
	  db_fieldsmemory($result,0);

	    //rotina de exclusao no orcppvalele------------------------------------------------------------------------
	    if($sqlerro == false && isset($codseqimp) && $codseqimp != ''){
	       $result = $clorcimpactovalele->sql_record($clorcimpactovalele->sql_query($codseqimp,'',"o56_elemento"));
	       if($clorcimpactovalele->numrows>0){
		  $clorcimpactovalele->o96_codseqimp = $codseqimp;
		  $clorcimpactovalele->excluir($codseqimp);
		  $erro_msg = $clorcimpactovalele->erro_msg;
		  if($clorcimpactovalele->erro_status==0){
		    $sqlerro=true;
		    break;
		  }
	       }
	    }
	    //final 
	    //-------------------------------------------------------------------------------------------------------------

	    //rotina exclusão orcimpactotiporec------------------------------------------------
	    if($sqlerro == false && isset($codseqimp) && $codseqimp != ''){
	      $result = $clorcimpactotiporec->sql_record($clorcimpactotiporec->sql_query_file($codseqimp));
	      if($clorcimpactotiporec->numrows>0){
		$clorcimpactotiporec->o93_codseqimp = $codseqimp;
		$clorcimpactotiporec->excluir($codseqimp);
		$erro_msg = $clorcimpactotiporec->erro_msg;
		if($clorcimpactotiporec->erro_status==0){
		  $sqlerro=true;
		  break;
		}
	      }  
	    }
	    //final
	    //-----------------------------------------------------------------------------------


	    //rotina de exclusão de orcimpactoval------------
	      $clorcimpactoval->o91_codseqimp = $codseqimp;
	      $clorcimpactoval->excluir($codseqimp);
	      $erro_msg = $clorcimpactoval->erro_msg;
	      if($clorcimpactoval->erro_status==0){
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

if(isset($opcao) && $opcao=="alterar"){
    echo "      
        <script> 
           top.corpo.iframe_orcimpactovalmes.location.href='orc1_orcimpactovalmes001.php?o91_proces=$o91_proces&o92_codimp=".$o91_codimp."';
           parent.document.formaba.orcimpactovalmes.disabled=false;";
    if(isset($tavaincluindo)){
      echo " \nparent.mo_camada('orcimpactovalmes');\n";
    }	   
 echo "</script> 
	" ;
}else{
    echo "      
        <script> 
           parent.document.formaba.orcimpactovalmes.disabled=true;

        </script> 
	" ;
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
	include("forms/db_frmorcimpactoval.php");
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
        echo "<script> document.form1.".$clorcimpactoval->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcimpactoval->erro_campo.".focus();</script>";
    }
}
?>