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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
include("classes/db_tipoasse_classe.php");
include("classes/db_portariatipo_classe.php");
include("classes/db_portariaenvolv_classe.php");
include("classes/db_portariatipoato_classe.php");
include("classes/db_portariaproced_classe.php");
include("classes/db_portariatipodocindividual_classe.php");
include("classes/db_portariatipodoccoletiva_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cltipoasse        = new cl_tipoasse;
$clportariatipo    = new cl_portariatipo;
$clportariaenvolv  = new cl_portariaenvolv;
$clportariatipoato = new cl_portariatipoato;
$clportariaproced  = new cl_portariaproced;
$clrotulo          = new rotulocampo;
$clportariatipodocindividual = new cl_portariatipodocindividual;
$clportariatipodoccoletiva	 = new cl_portariatipodoccoletiva;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if(isset($alterar)){
  db_inicio_transacao();

  $db_opcao = 2;
  $cltipoasse->alterar($h12_codigo);

  if ($cltipoasse->erro_status == 0){
       $sqlerro  = true;
  }

  if ($sqlerro == false){
  	
       if (isset($h30_sequencial) && trim($h30_sequencial)!=""){
            $flag_alt = true;
       } else {
            $flag_alt = false;
       }

       if ($flag_alt == false){
            if (isset($h30_portariaenvolv) && trim($h30_portariaenvolv)!=""){
                 $flag_inc = true;
            } else {
                 $flag_inc = false;
            }
       }

       if ($flag_alt == true){ 
            $clportariatipo->h30_sequencial = $h30_sequencial;
       }

       $clportariatipo->h30_tipoasse        = $h12_codigo;
       $clportariatipo->h30_portariaenvolv  = $h30_portariaenvolv;
       $clportariatipo->h30_portariatipoato = $h30_portariatipoato;
       $clportariatipo->h30_portariaproced  = $h30_portariaproced;
       $clportariatipo->h30_amparolegal     = $h30_amparolegal;

       $clportariatipodocindividual->h37_modportariaindividual = $h37_modportariaindividual;
       $clportariatipodocindividual->h37_portariatipo 		     = $clportariatipo->h30_sequencial;
       
       $clportariatipodoccoletiva->h38_modportariacoletiva 	   = $h38_modportariacoletiva;
       $clportariatipodoccoletiva->h38_portariatipo       	   = $clportariatipo->h30_sequencial;
       

       if ($flag_alt == true){ 
         
       	 $rsConsultaModIndividual = $clportariatipodocindividual->sql_record($clportariatipodocindividual->sql_query(null,"h37_sequencial",null," h37_portariatipo = {$h30_sequencial}"));
  	   	 $rsConsultaModColetiva   = $clportariatipodoccoletiva->sql_record($clportariatipodoccoletiva->sql_query(null,"h38_sequencial",null, "h38_portariatipo = {$h30_sequencial} "));       	

  	   	 if($clportariatipodocindividual->numrows > 0){
       	   $lTemModInd   = true;
       	   $oPortariaInd = db_utils::fieldsMemory($rsConsultaModIndividual,0);
  	       $clportariatipodocindividual->h37_sequencial = $oPortariaInd->h37_sequencial;       	   
	  	 } else {
	  	   $lTemModInd = false;
	  	 }

  	     if ($clportariatipodoccoletiva->numrows > 0){
       	   $lTemModCol   = true;  	     
  	       $oPortariaCol = db_utils::fieldsMemory($rsConsultaModColetiva,0);
  	       $clportariatipodoccoletiva->h38_sequencial = $oPortariaCol->h38_sequencial;  	       
  	     } else {
       	   $lTemModCol = false;  	     	
  	     }

  	     
  	   	 if (isset($h30_portariaenvolv) && trim($h30_portariaenvolv)!=""){

  	   	   $clportariatipo->alterar($h30_sequencial);

       	   if($lTemModInd){
       	   	 if(isset($h37_modportariaindividual) && trim($h37_modportariaindividual) != ""){ 
			   $clportariatipodocindividual->alterar($oPortariaInd->h37_sequencial);             
		  	 }else{
			   $clportariatipodocindividual->excluir($oPortariaInd->h37_sequencial);
		  	 }
	  	   } else {
	  	   	 if(isset($h37_modportariaindividual) && trim($h37_modportariaindividual) != ""){
	  	   	   $clportariatipodocindividual->incluir(null);
	  	   	 }	  	   	
	  	   }

  	       if($lTemModCol){
  	         if(isset($h38_modportariacoletiva) && trim($h38_modportariacoletiva) != ""){
  	       	   $clportariatipodoccoletiva->alterar($oPortariaCol->h38_sequencial);
  	         }else{
  	       	   $clportariatipodoccoletiva->excluir($oPortariaCol->h38_sequencial);  	         	
  	         }
  	       } else {
  	       	 if(isset($h38_modportariacoletiva) && trim($h38_modportariacoletiva) != ""){
			   $clportariatipodoccoletiva->incluir(null);
			 }
  	       }
                 
         } else {
           
           if($lTemModInd){
			 $clportariatipodocindividual->excluir($oPortariaInd->h37_sequencial);
	  	   }

  	       if($lTemModCol){
  	       	 $clportariatipodoccoletiva->excluir($oPortariaCol->h38_sequencial);
  	       }	
  	       
           $clportariatipo->excluir($h30_sequencial);
           
         }
       }
	
       if ($flag_alt == false && $flag_inc == true){
       	
	        $clportariatipo->h30_tipoasse        = $h12_codigo;
    	    $clportariatipo->h30_portariaenvolv  = $h30_portariaenvolv;
       		$clportariatipo->h30_portariatipoato = $h30_portariatipoato;
       		$clportariatipo->h30_portariaproced  = $h30_portariaproced;
       		$clportariatipo->h30_amparolegal     = $h30_amparolegal;
            $clportariatipo->incluir(null);
       		if ($clportariatipo->erro_status == 0){
         		$sqlerro = true;
       		}
            
			if(isset($h37_modportariaindividual) && trim($h37_modportariaindividual) != ""){ 
       	      $clportariatipodocindividual->h37_modportariaindividual = $h37_modportariaindividual;
       		  $clportariatipodocindividual->h37_portariatipo 		  = $clportariatipo->h30_sequencial;
              $clportariatipodocindividual->incluir(null);
	          if($clportariatipodocindividual->erro_status == 0){
       	 		$sqlerro = true;
       		  }
			}
  	       	 
			if(isset($h38_modportariacoletiva) && trim($h38_modportariacoletiva) != ""){
       		  $clportariatipodoccoletiva->h38_modportariacoletiva 	  = $h38_modportariacoletiva;
       		  $clportariatipodoccoletiva->h38_portariatipo       	  = $clportariatipo->h30_sequencial;
			  $clportariatipodoccoletiva->incluir(null);
	          if($clportariatipodoccoletiva->erro_status == 0){
       	 		$sqlerro = true;
       		  }
			}
            
            if ($clportariatipo->erro_status != 0){
              $h30_sequencial = $clportariatipo->h30_sequencial;
            }
       }

       
       

       
  }

  
  db_fim_transacao($sqlerro);
  
}else if(isset($chavepesquisa)){
  
  $db_opcao = 2;
  $result = $cltipoasse->sql_record($cltipoasse->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);

  $res_portariatipo = $clportariatipo->sql_record($clportariatipo->sql_query_func(null,"h30_sequencial,h42_descr","h42_sequencial","h30_tipoasse = ".@$h12_codigo));
  if ($clportariatipo->numrows > 0){
       db_fieldsmemory($res_portariatipo,0);

    // Consulta Modelo de Portaria Individual
    $rsConsultaModIndividual = $clportariatipodocindividual->sql_record($clportariatipodocindividual->sql_query(null,"h37_modportariaindividual, db63_nomerelatorio as descrModIndividual",null," h37_portariatipo = {$h30_sequencial}"));
    if($clportariatipodocindividual->numrows > 0){
	    db_fieldsmemory($rsConsultaModIndividual,0);
	    $descrModIndividual = $descrmodindividual;  	
    }
  
  
    // Consulta Modelo de Portaria Coletiva
    $rsConsultaModColetiva   = $clportariatipodoccoletiva->sql_record($clportariatipodoccoletiva->sql_query(null,"h38_modportariacoletiva, db63_nomerelatorio as descrModColetiva",null, "h38_portariatipo = {$h30_sequencial} "));
    if($clportariatipodoccoletiva->numrows > 0){
  	  db_fieldsmemory($rsConsultaModColetiva,0);
  	  $descrModColetiva = $descrmodcoletiva; 
    }
  }
  
  $db_botao = true;
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmtipoasse.php");
      ?>
      </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($cltipoasse->erro_status=="0"){
    $cltipoasse->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltipoasse->erro_campo!=""){
      echo "<script> document.form1.".$cltipoasse->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltipoasse->erro_campo.".focus();</script>";
    }
  }else{
    $cltipoasse->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","h12_assent",true,1,"h12_assent",true);
</script>