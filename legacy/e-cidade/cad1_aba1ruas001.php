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
include("classes/db_ruas_classe.php");
include("classes/db_ruascep_classe.php");
include("classes/db_cfiptu_classe.php");
include("classes/db_ruastipo_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clruas		 = new cl_ruas;
$clruascep	 = new cl_ruascep;
$clcfiptu	 = new cl_cfiptu;
$clruastipo  = new cl_ruastipo;

$db_codopcao = 1;
$j14_codigo2 = "";
$db_opcao		 = 1;
$db_botao		 = true;
$sqlerro		 = false;


if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  
	db_inicio_transacao();
  
	$clruas->j14_lei	 = @$j14_lei;
  $clruas->j14_dtlei = @$j14_dtlei_ano."-".@$j14_dtlei_mes."-".@$j14_dtlei_dia;
  
	if ($clruas->j14_dtlei == "--") {
    $clruas->j14_dtlei = null;
  }
  
	$clruas->incluir($j14_codigo);
  $erro_msg = $clruas->erro_msg;
  
	if ( $clruas->erro_status == 0 ){
		$sqlerro = true;
  }else{
     $j14_codigo = $clruas->j14_codigo;
     $j14_nome	 = $clruas->j14_nome;
  } 
  
  if(isset($j29_cep) and $j29_cep <> ''){
    $clruascep->j29_cep		 = $j29_cep;
    $clruascep->j29_inicio = '0';
    $clruascep->j29_final	 = '0';
    $clruascep->incluir($j14_codigo,'0');
    
		if ($clruascep->erro_status == 0){
      $sqlerro  = true;
			$erro_msg = $clruascep->erro_msg;
    }else{
    	$clicou = "";
    }
  }
  
	db_fim_transacao($sqlerro);

}else{


	// ---------  Verifica parametro do módulo cadastro :  Código Logradouro Automático ------------ //

	$rsResult = $clcfiptu->sql_record($clcfiptu->sql_query_file (DB_getsession("DB_anousu"),"*",null,""));

	if ($clcfiptu->numrows > 0){
		db_fieldsmemory($rsResult,0);
		if ($j18_logradauto == 'f'){
			$db_codopcao = 1;
			$mostrabotao = 't';
		}else{
			$db_codopcao = 3;
			$mostrabotao = 'f';
		 }
	}else{
		db_msgbox("Configure os parametros do módulo cadastro para o Exercício: ".db_getsession("DB_anousu"));
		exit;
	}

	// --------------------------------------------------------------------------------------------- //


	if (isset($db_procproximo) && $db_procproximo != ""){
		
		while (!isset($novocodigo) || $novocodigo == "") {
			
			@$clicou = 't';
			$rsResultcod = $clruas->sql_record($clruas->sql_query_file($j14_codigo,"*","j14_codigo",""));
			$numrows = $clruas->numrows;
			if ( $numrows > 0 ){			
				db_fieldsmemory($rsResultcod,0);
				$j14_codigo++;
			}else{
				$novocodigo = $j14_codigo;
			}
		}
		
		$j14_codigo2 = $novocodigo;
		$j14_nome		 = "";
		
	} else if ( $j18_logradauto == 't' ){
		
		$rsResultcod2 = $clruas->sql_record($clruas->sql_query_file("","max(j14_codigo)+1 as codigo","",""));
		db_fieldsmemory($rsResultcod2,0);
			
			if (isset($codigo) && trim($codigo)!=""){
				$novocodigo = $codigo;
				$j14_nome   = "";
				$j14_codigo = "";
			} else {
				$novocodigo = 1;
			}
	}
	
	if ($j18_logradauto == 'f'){
		 $j14_codigo = $j14_codigo2;
		}else{
		 $j14_codigo = $novocodigo;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
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
	include("forms/db_frmruas.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if(($clruas->erro_status=="0") or ($sqlerro == true)){
//    $clruas->erro(true,false);
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clruas->erro_campo!=""){
      echo "<script> document.form1.".$clruas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clruas->erro_campo.".focus();</script>";
    };
  }else{
    // $clruas->erro(true,true);
    db_msgbox($erro_msg);
    echo "<script>
            parent.iframe_g3.location.href='cad1_ruasbairroalt001.php?j16_lograd=".@$j14_codigo."&j14_nome=".$j14_nome."';\n
            parent.iframe_g2.location.href='cad1_aba2ruas001.php?j14_codigo=".@$j14_codigo."&j14_nome=".$j14_nome."';\n
            parent.iframe_g1.location.href='cad1_aba1ruas002.php?chavepesquisa=".@$j14_codigo."&db_teste=true';\n
            parent.mo_camada('g3');
            parent.document.formaba.g2.disabled = false;\n
            parent.document.formaba.g3.disabled = false;\n
          </script>";
  }
}
?>