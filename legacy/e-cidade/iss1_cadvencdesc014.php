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
include("classes/db_cadvenc_classe.php");
include("classes/db_cadvencdesc_classe.php");
include("classes/db_cadvencdescban_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clcadvenc = new cl_cadvenc;
$clcadvencdesc = new cl_cadvencdesc;
$clcadvencdescban = new cl_cadvencdescban;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clcadvencdesc->incluir($q92_codigo);
  if($clcadvencdesc->erro_status=='0'){
      $sqlerro=true;
  }
  $q92_codigo=$clcadvencdesc->q92_codigo;
  if($k15_codigo>0){
    $clcadvencdescban->q93_codigo=$q92_codigo;
    $clcadvencdescban->q93_cadban=$k15_codigo;
    $clcadvencdescban->incluir($q92_codigo);
    if($clcadvencdescban->erro_status=='0'){
        $sqlerro=true;
    }
  }
  db_fim_transacao($sqlerro);
}
if(isset($duplica)&&$duplica=='true'&&isset($cod_duplic)&&$cod_duplic!=''){
	$sqlerro=false;
	db_inicio_transacao();
	$result_cadvencdesc = $clcadvencdesc->sql_record($clcadvencdesc->sql_query_ban($cod_duplic));
	$numrows_cadvencdesc = $clcadvencdesc->numrows;
	if ($numrows_cadvencdesc>0){
		db_fieldsmemory($result_cadvencdesc,0);
		if ($sqlerro==false){
      if ($q92_vlrminimo==null || $q92_vlrminimo=''){
         $q92_vlrminimo='0';
      }
			$clcadvencdesc->q92_descr = $q92_descr." Copia";
			$clcadvencdesc->q92_tipo = $q92_tipo;
			$clcadvencdesc->q92_hist = $q92_hist;
			$clcadvencdesc->q92_diasvcto = $q92_diasvcto;
			$clcadvencdesc->q92_vlrminimo = $q92_vlrminimo;
			$clcadvencdesc->q92_formacalcparcvenc = $q92_formacalcparcvenc;
			$clcadvencdesc->incluir(null);			
			$erro_msg = $clcadvencdesc->erro_msg;
			if($clcadvencdesc->erro_status=='0'){
      			$sqlerro = true;      		 
  			}
  			$new_cod=$clcadvencdesc->q92_codigo;
  			$q92_descr=$clcadvencdesc->q92_descr;
		}
		if ($sqlerro==false){
			if ($q93_codigo!=""){
				$clcadvencdescban->q93_codigo = $new_cod;
    			$clcadvencdescban->q93_cadban = $q93_cadban;
    			$clcadvencdescban->incluir($new_cod);
    			if($clcadvencdescban->erro_status=='0'){
        			$sqlerro = true;
        			$erro_msg = $clcadvencdescban->erro_msg;
    			}
			}
		}
		if ($sqlerro==false){
	    	$result_cadvenc = $clcadvenc->sql_record($clcadvenc->sql_query_file($cod_duplic,null,"*","q82_parc"));	    	
	    	$numrows_cadvenc = $clcadvenc->numrows;
	    	for($w=0;$w<$numrows_cadvenc;$w++){
	    		db_fieldsmemory($result_cadvenc,$w);
	    		if ($sqlerro==false){
            if ($q82_calculaparcvenc=='f'){
           $q82_calculaparcvenc='false';
            }else if ($q82_calculaparcvenc=='t'){
            $q82_calculaparcvenc='true';
            }
	    			$clcadvenc->q82_venc = $q82_venc;
 	    			$clcadvenc->q82_desc = $q82_desc;
	    			$clcadvenc->q82_perc = $q82_perc;
	    			$clcadvenc->q82_hist = $q82_hist;
	    			$clcadvenc->q82_parc = $q82_parc;
            $clcadvenc->q82_calculaparcvenc = $q82_calculaparcvenc;
  					$clcadvenc->incluir($new_cod,$q82_parc);
  					if($clcadvenc->erro_status==0){
    					$sqlerro=true; 
              db_msgbox("aqui");
    					$erro_msg = $clcadvenc->erro_msg;
    					break;
  					}
	    		}
	    	}
		}
	}
	db_fim_transacao($sqlerro);
	db_msgbox($erro_msg);
	if ($sqlerro==false){
		echo "<script>
              	function js_xy(){
                	parent.document.formaba.cadvenc.disabled=false;\n
					top.corpo.iframe_cadvenc.location.href='iss1_cadvenc004.php?q82_codigo=$new_cod&q92_descr=$q92_descr';\n                	
              	}
              	js_xy();
              </script>";		
		db_redireciona("iss1_cadvencdesc015.php?tavainclu=true&chavepesquisa=$new_cod");
	}else{
		
	}
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
	include("forms/db_frmcadvencdescalt.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clcadvencdesc->erro_status=="0"){
    $clcadvencdesc->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcadvencdesc->erro_campo!=""){
      echo "<script> document.form1.".$clcadvencdesc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcadvencdesc->erro_campo.".focus();</script>";
    }
  }else{
    $clcadvencdesc->erro(true,false);
     echo "
           <script>
              function js_xy(){
                parent.document.formaba.cadvenc.disabled=false;\n
		top.corpo.iframe_cadvenc.location.href='iss1_cadvenc004.php?q82_codigo=$q92_codigo&q92_descr=$q92_descr';\n
                parent.mo_camada('cadvenc');
              }
              js_xy();
           </script>
         ";
         db_redireciona("iss1_cadvencdesc015.php?tavainclu=true&chavepesquisa=$q92_codigo");
 }  	 
}
?>