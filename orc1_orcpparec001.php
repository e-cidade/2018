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

include("classes/db_orcpparec_classe.php");
include("classes/db_orcppa_classe.php");
include("classes/db_orcppalei_classe.php");
include("classes/db_orcfontes_classe.php");
include("classes/db_concarpeculiar_classe.php");

include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcpparec      = new cl_orcpparec;
$clorcppa         = new cl_orcppa;
$clorcppalei      = new cl_orcppalei;
$clorcfontes      = new cl_orcfontes;
$clconcarpeculiar = new cl_concarpeculiar;

//$db_opcao = 22;
$db_botao = true;
//echo $opcao;

if(isset($incluir)){
  $sqlerro = false;
  db_inicio_transacao();

  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);

  $priproces = true;
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
//    echo "if(".substr($chave,0,9)."==o27_valor && ".current($vt)." != ''){<br>";
    if(substr($chave,0,9)=="o27_valor" && current($vt) != ''){
      $ano   =  substr($chave,10);
      $valor = current($vt);
      $fonte = $vt["o57_fonte_$ano"];
      $obs   = $vt["o27_obs_$ano"];
      $sequen= $vt["o27_sequen_$ano"];
      $perc   = $vt["o27_perc_$ano"];
      
      if (trim($vt["o27_concarpeculiar_$ano"]) == ""){
        $o27_concarpeculiar = "0";
      } else {
        $o27_concarpeculiar = $vt["o27_concarpeculiar_$ano"];
      }

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
	  $clorcpparec->o27_proces         = $proces;
	  $clorcpparec->o27_codleippa      = $o27_codleippa;
	  $clorcpparec->o27_exercicio      = $ano;
	  $clorcpparec->o27_codfon         = $o57_codfon;
	  $clorcpparec->o27_valor          = $valor;
	  $clorcpparec->o27_obs            = $obs;
    $clorcpparec->o27_perc           = $perc;
    $clorcpparec->o27_concarpeculiar = $o27_concarpeculiar;

	  $clorcpparec->incluir(null);
	} 	
	$erro_msg = $clorcpparec->erro_msg;
	if($clorcpparec->erro_status==0){
	     $sqlerro=true;
	     break;
	}else{
	   $seq  = $clorcpparec->o27_sequen;
	}  	
     //------------------------------------------------------------------------------------------   
   
     //alterar-----------------------------------------------------
	if($sqlerro == false && $priproces == true){
	    $priproces = false;
	    $clorcpparec->o27_proces  = $seq;
	    $clorcpparec->o27_sequen  = $seq;
	    $clorcpparec->alterar($seq);
	    $erro_msg = $clorcpparec->erro_msg;
	    $proces = $seq;
	    if($clorcpparec->erro_status==0){
	       $sqlerro=true;
	       break;
	    }   
	}
     //---------------------------------------------------------------- 
    }  
    next($vt);
  }
  if($sqlerro == true){
    echo "dd";
  }
// $sqlerro = true;
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
    if(substr($chave,0,9)=="o27_valor"){
      $ano    =  substr($chave,10);
      $valor  = current($vt);
      $fonte  = $vt["o57_fonte_$ano"];
      $obs    = $vt["o27_obs_$ano"];
      $sequen = $vt["o27_sequen_$ano"];
      $perc   = $vt["o27_perc_$ano"];

      if (trim($vt["o27_concarpeculiar_$ano"]) == ""){
        $o27_concarpeculiar = "0";
      } else {
        $o27_concarpeculiar = $vt["o27_concarpeculiar_$ano"];
      }

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
	    $clorcpparec->o27_proces         = $o27_proces;
	    $clorcpparec->o27_codleippa      = $o27_codleippa;
	    $clorcpparec->o27_exercicio      = $ano;
	    $clorcpparec->o27_codfon         = $o57_codfon;
	    $clorcpparec->o27_valor          = $valor;
	    $clorcpparec->o27_obs            = $obs;
	    $clorcpparec->o27_perc           = $perc;
      $clorcpparec->o27_concarpeculiar = $o27_concarpeculiar;

	    if($sequen==""){
   	      $clorcpparec->incluir(null);
	    }else{
	      $clorcpparec->o27_sequen  = $sequen;
   	      $clorcpparec->alterar($sequen);
	    }  
	  } 	
	  $erro_msg = $clorcpparec->erro_msg;
	  if($clorcpparec->erro_status==0){
	       $sqlerro=true;
	       break;
	  }  	
       }	  
       //------------------------------------------------------------------------------------------   
    }  
    next($vt);
  }
 db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  $sqlerro = false;
  db_inicio_transacao();

  $clorcpparec->o27_proces    = $o27_proces;
  $clorcpparec->excluir("","o27_proces=$o27_proces");
  $erro_msg = $clorcpparec->erro_msg;
  if($clorcpparec->erro_status==0){
       $sqlerro=true;
  }  	

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
$libera=false;

if(isset($o27_codleippa) && empty($testado)){	
    $libera  = true;
}

if($libera==true || isset($testado)){

	include("forms/db_frmorcpparec.php");

}else{
$clrotulo = new rotulocampo;
$clrotulo->label("o27_codleippa");
?>
<form name='form1'>
  <table border='0'>
  <?
    if(isset($msg)){
      echo $msg;
     }
  ?>
  <tr>
    <td nowrap title="<?=@$To27_codleippa?>">
       <?=$Lo27_codleippa?>
    </td>
    <td> 
<?
  $result=$clorcppalei->sql_record($clorcppalei->sql_query_file(null,"o21_codleippa,o21_descr||' '||o21_anoini||'-'||o21_anofim","o21_codleippa desc"));
  $numrows = $clorcppalei->numrows;
  db_selectrecord("o27_codleippa",$result,true,1,"","","","0","js_reload()");

?>
    </td>
  </tr>


  
  <tr>
    <td colspan='2' align='center'>
      <input name="pesquisar" type="submit" id="pesquisar" value="Entrar"  >
    </td>  
  </tr>    
  </table>
</form>
<script>
function js_cod(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcpparec','db_iframe_orcppa','func_orcppa.php?funcao_js=parent.js_mostracod1|o23_codppa','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcpparec','db_iframe_orcppa','func_orcppa.php?pesquisa_chave='+document.form1.o24_codppa.value+'&funcao_js=parent.js_mostracod','Pesquisa',false,0);
  }
}
function js_mostracod(chave,erro){
  if(erro==true){ 
    document.form1.o24_codppa.focus(); 
    document.form1.o24_codppa.value = ''; 
  }
}
function js_mostracod1(chave1){
  document.form1.o24_codppa.value = chave1;
  db_iframe_orcppa.hide();
}

  
</script>
<?
}	
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
    if($clorcpparec->erro_campo!=""){
        echo "<script> document.form1.".$clorcppaval->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcppaval->erro_campo.".focus();</script>";
    }

    if ($sqlerro == false){
      echo "<script> 
                    document.location.href = 'orc1_orcpparec001.php?o27_codleippa=$o27_codleippa&o27_codleippadescr=$o27_codleippadescr&testado=$testado';
                    parent.document.formaba.orcpparec02.disabled=false;
                    top.corpo.iframe_orcpparec02.location.href='orc1_orcpparec002.php?o27_codleippa=".@$o27_codleippa."';
            </script>";
    }
}
if( empty($opcao) &&  (($libera==true || isset($testado)) && isset($pesquisar)) || isset($alterar) || isset($inlcuir) || isset($excluir)  ){
  echo "<script>";
  echo "
       parent.document.formaba.orcpparec02.disabled=false;
       top.corpo.iframe_orcpparec02.location.href='orc1_orcpparec002.php?o27_codleippa=".@$o27_codleippa."';
      ";	 
  echo "</script>";
}
?>