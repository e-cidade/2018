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

include("classes/db_orcimpactorecmov_classe.php");
include("classes/db_orcimpactomov_classe.php");
include("classes/db_orcimpactoperiodo_classe.php");
include("classes/db_orcfontes_classe.php");

include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcimpactorecmov = new cl_orcimpactorecmov;
$clorcimpactomov    = new cl_orcimpactomov;
$clorcimpactoperiodo = new cl_orcimpactoperiodo;
$clorcfontes = new cl_orcfontes;
//$db_opcao = 22;
$db_botao = true;
//echo $opcao;

if(empty($o63_codperiodo)){
  $result=$clorcimpactoperiodo->sql_record($clorcimpactoperiodo->sql_query_file(null,"o96_codperiodo as o69_codperiodo"));
  $numrows = $clorcimpactoperiodo->numrows;
  if($numrows==0){
    db_msgbox("Cadastre o  período para Impacto Orçamentário.");
  }else{
    db_fieldsmemory($result,0);
  }
}

if(isset($incluir)){
  $sqlerro = false;
  db_inicio_transacao();

  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  
  $priproces = true;
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
//    echo "if(".substr($chave,0,9)."==o69_valor && ".current($vt)." != ''){<br>";
    if(substr($chave,0,9)=="o69_valor" && current($vt) != ''){
      $ano   =  substr($chave,10);
      $valor = current($vt);
      $fonte = $vt["o57_fonte_$ano"];
      $obs   = $vt["o69_obs_$ano"];
      $codigo   = $vt["o69_codigo_$ano"];
      $sequen= $vt["o69_sequen_$ano"];
      $perc   = $vt["o69_perc_$ano"];

       
     //------PEGA O CÓDIGO DA FONTE PELO ESTRUTURAL-------------------------------------------------
      $result = $clorcfontes->sql_record($clorcfontes->sql_query_file(null,null,"o57_codfon",'',"o57_fonte = '$fonte' and o57_anousu = ".db_getsession("DB_anousu")));
      if($clorcfontes->numrows==0){
	 $sqlerro  = true;
	 $erro_msg = "Fonte não encontrada...";
	 break;
      }else{
	db_fieldsmemory($result,0);
      }
     //---------------------------------------------------------------------------------------------
       
      //-------PROCESSO DE INLCUÃO-----------------------------------------------------------------------
	if(empty($proces)){
	  $proces = '0';
	}
	if($sqlerro == false){ 
	  $clorcimpactorecmov->o69_proces    = $proces;
	  $clorcimpactorecmov->o69_codperiodo = $o69_codperiodo;
	  $clorcimpactorecmov->o69_exercicio = $ano;
	  $clorcimpactorecmov->o69_codfon    = $o57_codfon;
	  $clorcimpactorecmov->o69_valor     = $valor;
	  $clorcimpactorecmov->o69_obs       = $obs;
	  $clorcimpactorecmov->o69_codigo    = $codigo;
          $clorcimpactorecmov->o69_perc      = $perc;
          $clorcimpactorecmov->o69_codimpger = $o63_codimpger;
	  $clorcimpactorecmov->incluir(null);
	} 	
	$erro_msg = $clorcimpactorecmov->erro_msg;
	if($clorcimpactorecmov->erro_status==0){
	     $sqlerro=true;
	     break;
	}else{
	   $seq  = $clorcimpactorecmov->o69_sequen;
	}  	
     //------------------------------------------------------------------------------------------   
   
     //alterar-----------------------------------------------------

     
     ///parei aki.... 09/05/2005
	if($sqlerro == false && $priproces == true){
	    $priproces = false;
	    $clorcimpactorecmov->o69_proces  = $seq;
	    $clorcimpactorecmov->o69_sequen  = $seq;
	    $clorcimpactorecmov->alterar($seq);
	    $erro_msg = $clorcimpactorecmov->erro_msg;
	    $proces = $seq;
	    if($clorcimpactorecmov->erro_status==0){
	       $sqlerro=true;
	       break;
	    }   
	}
     //---------------------------------------------------------------- 
    }  
    next($vt);
  }
  if($sqlerro == false){
    $opcao = "alterar";
    $tavaincluindo = true;
    unset($incluir);
    $o69_proces = $clorcimpactorecmov->o69_proces;
  }else{
    echo "Processo não foi efetuado com sucesso!";
  }
 db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  $sqlerro = false;
  db_inicio_transacao();

  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  
  $priproces = true;
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,9)=="o69_valor"){
      $ano    =  substr($chave,10);
      $valor  = current($vt);
      $fonte  = $vt["o57_fonte_$ano"];
      $obs    = $vt["o69_obs_$ano"];
      $codigo = $vt["o69_codigo_$ano"];
      $sequen = $vt["o69_sequen_$ano"];
      $perc   = $vt["o69_perc_$ano"];


      if($valor != ''){

       //------PEGA O CÓDIGO DA FONTE PELO ESTRUTURAL-------------------------------------------------
	$result = $clorcfontes->sql_record($clorcfontes->sql_query_file(null,null,"o57_codfon",'',"o57_fonte = '$fonte' and o57_anousu = ".db_getsession("DB_anousu")));
	if($clorcfontes->numrows==0){
	   $sqlerro  = true;
	   $erro_msg = "Fonte não encontrada...";
	   break;
	}else{
	  db_fieldsmemory($result,0);
	}
       //---------------------------------------------------------------------------------------------
	 
	
	//-------PROCESSO DE ALTERAÇÃO---------------------------------------------------------------------
	  if($sqlerro == false){ 
	    $clorcimpactorecmov->o69_proces    = $o69_proces;
	    $clorcimpactorecmov->o69_codperiodo= $o69_codperiodo;
	    $clorcimpactorecmov->o69_exercicio = $ano;
	    $clorcimpactorecmov->o69_codfon    = $o57_codfon;
	    $clorcimpactorecmov->o69_valor     = $valor;
	    $clorcimpactorecmov->o69_obs       = $obs;
	    $clorcimpactorecmov->o69_codigo    = $codigo;
	    $clorcimpactorecmov->o69_perc      = $perc;
            $clorcimpactorecmov->o69_codimpger = $o63_codimpger;
	    if($sequen==""){
   	      $clorcimpactorecmov->incluir(null);
	    }else{
	      $clorcimpactorecmov->o69_sequen  = $sequen;
   	      $clorcimpactorecmov->alterar($sequen);
	    }  
	  } 	
	  $erro_msg = $clorcimpactorecmov->erro_msg;
	  if($clorcimpactorecmov->erro_status==0){
	       $sqlerro=true;
	       break;
	  }  	
       }	  
       //------------------------------------------------------------------------------------------   
    }  
    next($vt);
  }
  if($sqlerro == false){
    unset($o69_proces);
  }
 db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  $sqlerro = false;
  db_inicio_transacao();

  $clorcimpactorecmov->o69_proces    = $o69_proces;
  $clorcimpactorecmov->excluir("","o69_proces=$o69_proces");
  $erro_msg = $clorcimpactorecmov->erro_msg;
  if($clorcimpactorecmov->erro_status==0){
       $sqlerro=true;
  }  	
  unset($o69_proces);
 db_fim_transacao($sqlerro);
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
	include("forms/db_frmorcimpactorecmov.php");
?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
    db_msgbox($erro_msg);
    if($sqlerro == false){
      if(isset($incluir)){
	echo "
	   parent.document.formaba.orcimpactorecmov02.disabled=false;
	   top.corpo.iframe_orcimpactorecmov02.location.href='orc1_orcimpactorecmov002.php?o69_codperiodo=".@$o69_codperiodo."';
	";	 
      }else{	
	/*
	echo "<script>";
	echo "
	     parent.document.formaba.orcimpactorecmovmes.disabled=true;
	    ";	 
	echo "</script>";
	*/
      }  	
    }

    
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$clorcimpactomovval->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcimpactomovval->erro_campo.".focus();</script>";
    }
}
if(isset($o69_codperiodo) && empty($opcao) ){
  echo "<script>";
  echo "
       parent.document.formaba.orcimpactorecmov02.disabled=false;
       top.corpo.iframe_orcimpactorecmov02.location.href='orc1_orcimpactorecmov002.php?o69_codperiodo=".@$o69_codperiodo."';
       
      ";	 
  echo "</script>";
}
if(isset($opcao)){
  /*
  echo "<script>";
  echo "
       parent.document.formaba.orcimpactorecmovmes.disabled=false;
       top.corpo.iframe_orcimpactorecmovmes.location.href='orc1_orcimpactorecmovmes001.php?o69_proces=".@$o69_proces."';
      ";	 
      if(isset($tavaincluindo)){
        echo "parent.mo_camada('orcimpactorecmovmes');";
      }
  echo "</script>";
  */
}
?>