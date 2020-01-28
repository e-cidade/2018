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
include("dbforms/db_funcoes.php");

include("classes/db_empnota_classe.php");
include("classes/db_empnotaele_classe.php");
include("classes/db_empelemento_classe.php");
include("classes/db_empempenho_classe.php");

$clempnota = new cl_empnota;
$clempnotaele = new cl_empnotaele;
$clempelemento = new cl_empelemento;
$clempempenho = new cl_empempenho;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($e69_numemp)){
  $result = $clempempenho->sql_record($clempempenho->sql_query_empnome($e69_numemp,"z01_nome,z01_numcgm")); 
  if($clempempenho->numrows>0){
    db_fieldsmemory($result,0);
  }    
}    

$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
   
  if(empty($dinovo)){ 
    //rotina que verifica se o numero da nota já não está incluido em outro codnota
    $result = $clempnota->sql_record($clempnota->sql_query_emp(null,"e69_numemp as numemp","","e69_numero='$e69_numero' and z01_numcgm=$z01_numcgm"));
    $numrows = $clempnota->numrows;
    if($numrows>0){
      $emps = '';
      $sep  = '';
      $mesmo = false;
      for($i=0; $i<$numrows; $i++){
	db_fieldsmemory($result,$i);
	if($numemp == $e69_numemp){
	  $mesmo =  true;
	}	
	$emps .= $sep.$numemp; 
	$sep   = ',';
      }
      if($mesmo == true){
	if($numrows >1){
	   $duv_msg = "Nota já existe para este empenho e também no(s) empenho(s): $emps. Deseja incluir outra novamente?";
	}else{
	   $duv_msg = "Nota já existe para este empenho. Deseja incluir outra novamente?";
	}
      }else{
	   $duv_msg = "Nota já existe  no(s) empenho(s): $emps. Deseja incluir outra novamente?";
      }
      $sqlerro = true;
    }
    //***********************************************************************************
  }

  
  db_inicio_transacao();
  
  if($sqlerro == false){

     //rotina que irá somar os valores de todas as ordens 
	 $result  = $clempnotaele->sql_record($clempnotaele->sql_query(null,null,"e60_numemp,sum(e70_valor) as tot_valor, sum(e70_vlrliq) as tot_vlrliq, sum(e70_vlranu) as tot_vlranu","","e60_numemp=$e69_numemp group by e60_numemp")); 
	 if($clempnotaele->numrows>0){
	   db_fieldsmemory($result,0);
	 }else{
	     $tot_vlrliq   = '0.00';
	     $tot_vlranu   = '0.00';
	     $tot_valor    = '0.00';
	 }  
     //fim  


    
    $clempnota->e69_dtnota     = date("Y-m-d",db_getsession("DB_datausu"));
    $clempnota->e69_dtservidor = date("Y-m-d",db_getsession("DB_datausu"));
    $clempnota->e69_dtinclusao = date("Y-m-d",db_getsession("DB_datausu"));
    $clempnota->e69_anousu     = db_getsession("DB_anousu");
    if (!isset($e69_tipodocumentosfisca) || $e69_tipodocumentosfisca == "") {  
      $clempnota->e69_tipodocumentosfiscal = 4;
    }
    $clempnota->incluir($e69_codnota);
    $erro_msg = $clempnota->erro_msg;
    if($clempnota->erro_status==0){
      $sqlerro=true;
    }else{
      $e69_codnota = $clempnota->e69_codnota;
    }
  }

  if($sqlerro==false){
    $arr_dados = split("#",$dados);
    $tam = count($arr_dados);
    for($i=0; $i<$tam; $i++){
          $arr_ele = split("-",$arr_dados[$i]);
          $valor = number_format($arr_ele[1],"2",".","");
	  $elemento = $arr_ele[0];

          $result09 = $clempelemento->sql_record($clempelemento->sql_query_file($e69_numemp,$elemento,"e64_vlremp,e64_vlranu,e64_vlrliq"));
          db_fieldsmemory($result09,0);  
   
   //rotina que irá somar os valores de todas as ordens 
       $result  = $clempnotaele->sql_record($clempnotaele->sql_query(null,null,"e60_numemp,sum(e70_valor) as tot_valor, sum(e70_vlrliq) as tot_vlrliq, sum(e70_vlranu) as tot_vlranu","","e60_numemp=$e69_numemp and e70_codele =$elemento group by e60_numemp")); 
       if($clempnotaele->numrows>0){
	 db_fieldsmemory($result,0);
       }else{
	   $tot_vlrliq   = '0.00';
	   $tot_vlranu   = '0.00';
	   $tot_valor    = '0.00';
       }  
   //fim  

	  

          //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	  //rotina que verifica se tem disponivel
	  $liber = ($e64_vlremp-$e64_vlranu) - ($tot_valor-$tot_vlranu);
	  $valor = db_formatar($valor,'p')+0;	  	  
	  $liber = db_formatar($liber,'p')+0;
          if($valor > $liber){
	    $sqlerro=true;
	    $erro_msg = "Valor disponivel para o elemento $elemento é menor do que o disponivel. Verifique!";
	  }

	  if($sqlerro==false && $valor != 0){
	    $clempnotaele->e70_codnota = $e69_codnota;
	    $clempnotaele->e70_codele  = $elemento;//$arr[0] contem o elemento
	    $clempnotaele->e70_valor  = $valor;
	    $clempnotaele->e70_vlranu = '0' ;
	    $clempnotaele->e70_vlrliq = '0' ;
	    $clempnotaele->incluir($e69_codnota,$arr_ele[0]);
	    $erro_msg=$clempnotaele->erro_msg;
	    if($clempnotaele->erro_status==0){
		$sqlerro=true;
		break;
	    }
	  }  
     } 	  
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmempnota.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
  if(isset($duv_msg)){
       echo "<script>\n
	     retorna = confirm('$duv_msg');\n
	     if(retorna == true){\n
	       obj=document.createElement('input');\n
	       obj.setAttribute('name','dinovo');\n
	       obj.setAttribute('type','hidden');\n
	       obj.setAttribute('value','true');\n
	       document.form1.appendChild(obj);\n
	       document.form1.incluir.click();\n
	     }\n   
	     </script>";

  }else{
    db_msgbox($erro_msg);
      if($clempnota->erro_campo!=""){
	echo "<script> document.form1.".$clempnota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	echo "<script> document.form1.".$clempnota->erro_campo.".focus();</script>";
      };
    if($sqlerro==false){
      db_redireciona("emp1_empnota001.php");
    }  
  }  
}
?>