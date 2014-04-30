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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empempitem_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empempenho_classe.php");
include("libs/db_libdocumento.php");
include("libs/db_utils.php");
include("classes/db_pcparam_classe.php");

$clmatordem     = new cl_matordem;
$clmatordemitem = new cl_matordemitem;
$clempempenho   = new cl_empempenho;
$clempempitem   = new cl_empempitem;
$clcgm          = new cl_cgm;
$clpcparam      = new cl_pcparam;

db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if (isset($valores)&&isset($incluir)){
  db_inicio_transacao();
  $arr_forne=array();
  $valor_total="";
  $sqlerro = false;
  $dados=split("quant_","$valores");
  $valordoitem=split("valor_","$val");
  for ($i=1;$i<sizeof($dados);$i++){
    if ($sqlerro==false){
      $numero=split("_",$dados[$i]);
      $numemp=$numero[0];
      $sequen=$numero[1];
      $quanti=$numero[3];
      $vlsoitem=split("_",$valordoitem[$i]);
      $vl_soma_item=$vlsoitem[1];
      $vl_soma_item = str_replace(",",".","$vl_soma_item"); 
      $vl_soma_item;
      if ($emitir=="F"){
      	$result_forne=$clempempenho->sql_record($clempempenho->sql_query_file($numemp,"e60_numcgm as n"));
      	if ($clempempenho->numrows!=0){
			db_fieldsmemory($result_forne,0);
			if (array_key_exists($n,$arr_forne)){
	  			$arr_forne[$n]+=$vl_soma_item;
			}else{
		  		$arr_forne[$n]=$vl_soma_item;
			}
      	}
      }else{
      	if (array_key_exists($numemp,$arr_forne)){
	  		$arr_forne[$numemp]+=$vl_soma_item;
		}else{
			$arr_forne[$numemp]=$vl_soma_item;
		}
      }
    }
  }
  $m51_data="$m51_data_ano-$m51_data_mes-$m51_data_dia";
  reset($arr_forne);
  $cods="";
  $vir="";
  for ($x=0;$x<count($arr_forne);$x++ ){
  	$cgm=key($arr_forne);
  	if ($emitir=="E"){
  		$result_forne=$clempempenho->sql_record($clempempenho->sql_query_file($cgm,"e60_numcgm as numcgm"));
    	if ($clempempenho->numrows!=0){
			db_fieldsmemory($result_forne,0);
			$clmatordem->m51_numcgm = @$numcgm;
    	}
  	}else{
  	    $clmatordem->m51_numcgm = @$cgm;
  	}
    $clmatordem->m51_data = $m51_data; 
    $clmatordem->m51_depto = $coddepto;    
    $clmatordem->m51_obs = $m51_obs;
    $clmatordem->m51_valortotal = $arr_forne[$cgm];
    $clmatordem->incluir(null);
    $cods.=$vir.$clmatordem->m51_codordem;
    $vir=",";
    if($clmatordem->erro_status==0){
      $sqlerro=true;      
    }
    $erro_msg = $clmatordem->erro_msg;
    $codigo=$clmatordem->m51_codordem;
    for ($i=1;$i<sizeof($dados);$i++){
      	if ($sqlerro==false){

		     $numero=split("_",$dados[$i]);
		     $numemp=$numero[0];
      	 $sequen=$numero[1];
		     $quanti=$numero[3];
         $result_forne=$clempempenho->sql_record($clempempenho->sql_query_file($numemp,"e60_numcgm as n"));
         if ($clempempenho->numrows!=0){
	  		   
					 db_fieldsmemory($result_forne,0);
	  		   if ($emitir=="E"){
	  			   $n=$numemp;
	  		   }
	  		   if ($cgm==$n){
	  		    	
							$result_vlruni=$clempempitem->sql_record($clempempitem->sql_query_file($numemp,$sequen,"e62_vlrun"));
	  			    
							if ($clempempitem->numrows>0){
	  				     db_fieldsmemory($result_vlruni,0);
	  				     $vlr_uni=$e62_vlrun;	  				
	  			    }	  			
	    		    $vlsoitem=split("_",$valordoitem[$i]);
	    		    $vl_soma_item=$vlsoitem[1];
	    		    $vl_soma_item = str_replace(",",".","$vl_soma_item"); 
			        $clmatordemitem->m52_codordem = $codigo;
			        $clmatordemitem->m52_numemp = $numemp;
	    		    $clmatordemitem->m52_sequen = $sequen;
	    		    $clmatordemitem->m52_quant = $quanti; 
	    		    $clmatordemitem->m52_valor = $vl_soma_item;
	    		    $clmatordemitem->m52_vlruni = @$vlr_uni;
	    		    $clmatordemitem->incluir(null);
	    		    if ($clmatordemitem->erro_status==0){
	      			   $erro_msg=$clmatordemitem->erro_msg;	
	      			   $sqlerro=true;	      			
	      			   break;	      			
	    		    }
	  		   }
		    }
     }
   }
   if (isset($manda_mail)&&$manda_mail!=""){
		  
			$sqlCgm   = "select z01_email
			               from cgm 
										      inner join db_usuacgm  c on cgmlogin     = z01_numcgm
													inner join db_usuarios u on c.id_usuario =  u.id_usuario
			              where z01_numcgm = ".$clmatordem->m51_numcgm."
										  and usuext = 1";
			$rsCgm    = pg_query($sqlCgm);
			if (pg_num_rows($rsCgm) > 0 ){
         	
					db_fieldsmemory($rsCgm,0);
					if ($z01_email != ''){
					
					   $headers  = "Content-Type:text/html;";  	  	
		         $objteste = new libdocumento(1750);
		         $corpo    = $objteste->emiteDocHTML();
  	         $mail     = mail($z01_email,"Ordem de Compra Nº $codigo",$corpo,$headers);
					}
			}
	 }
   next($arr_forne);
  }
  /*
  if ($sqlerro==true){
  	db_msgbox("true");
  }
  db_msgbox($erro_msg);  
  exit;
  */
//  $sqlerro = true;
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
    <?
    include("forms/db_frmordemcompra.php");
    ?>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<?
if (isset($incluir)){
  if($sqlerro == true){ 
    db_msgbox($erro_msg);
    if($clmatordem->erro_campo!=""){
      echo "<script> document.form1.".$clmatordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatordem->erro_campo.".focus();</script>";
    } 
   }else{ 
   db_msgbox("Ordens de Compra $cods Incluidas com Sucesso"); 
   echo "        <script>
           if(confirm('Deseja imprimir as ordens de compra?')){
             jan = window.open('emp2_ordemcompra002.php?cods=$cods','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
                jan.moveTo(0,0);

	   }	 
	   location.href='emp4_ordemcompra011.php';
         </script>
   ";
  }

}
?>
</body>
</html>