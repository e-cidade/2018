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
include("classes/db_vistoriasanu_classe.php");
include("classes/db_vistorianumpre_classe.php");
include("classes/db_vistorias_classe.php");
include("classes/db_arrecad_classe.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_sql.php");
require_once("model/cancelamentoDebitos.model.php");

db_postmemory($HTTP_POST_VARS);
$oCancelaDebito   = new cancelamentoDebitos();
$clvistoriasanu   = new cl_vistoriasanu;
$clvistorianumpre = new cl_vistorianumpre;
$clvistorias      = new cl_vistorias;
$clarrecad        = new cl_arrecad;
$db_opcao         = 1;
$db_botao         = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $result_arrecad = $clvistorias->sql_record($clvistorias->sql_query_deb($y28_codvist,"y69_numpre,
                                                                                       arrecad.k00_numpre  as numpre_cad,
                                                                                       arrecant.k00_numpre as numpre_cant,
                                                                                       arrepaga.k00_numpre as numpre_pag"));

  if ($clvistorias->numrows > 0 ) {
  	
  	db_fieldsmemory($result_arrecad,0);

  	if ($y69_numpre != "") {
  		
  		if ($numpre_cad=="") {
  			
  			if ($numpre_pag=="") {
  				
  				$sqlerro = true;
  				$erro_msg = "VISTORIA COM DÉBITO CANCELADO - NÃO PERMITE ANULAÇÃO DA VISTORIA";
  			} else {
  				
  				$sqlerro = true;
  				$erro_msg = "VISTORIA PAGA OU EM PROCESSO DE PAGAMENTO - NÃO PERMITE ANULAÇÃO DA VISTORIA";
  			}
  		} else if ($numpre_cant!="") {
  			
  			$sqlerro = true;
  			$erro_msg = "DÉBITO CANCELADO - NÃO PERMITE ANULAÇÃO DA VISTORIA";
  		}
  	}
  }
  
  if ($sqlerro==false){
  	
	  $clvistoriasanu->y28_data = date("Y-m-d",db_getsession("DB_datausu"));
	  $clvistoriasanu->y28_hora = db_hora();
	  $clvistoriasanu->y28_usuario = db_getsession("DB_id_usuario");
	  $clvistoriasanu->incluir($y28_codigo);
	  
	  if ($clvistoriasanu->erro_status=="0"){
	  	$sqlerro = true;
	  	$erro_msg = $clvistoriasanu->erro_msg; 
	  }
	    
	  if ($sqlerro==false){
	  	$clvistorias->y70_ativo = "0"; 
	  	$clvistorias->y70_codvist = $y28_codvist;
	  	$clvistorias->alterar($y28_codvist);
	  	
	  	if ($clvistorias->erro_status=="0"){
	  		$sqlerro = true;
	  		$erro_msg = $clvistorias->erro_msg; 
	  	}  
	  }

    if ($sqlerro==false){
    	
    	$sSqlDebitosVistoria  = " select k00_numpre,     ";
    	$sSqlDebitosVistoria .= "        k00_numpar,     ";
    	$sSqlDebitosVistoria .= "        k00_receit      "; 
    	$sSqlDebitosVistoria .= "   from vistorianumpre  ";
    	$sSqlDebitosVistoria .= "        inner join arrecad on arrecad.k00_numpre = vistorianumpre.y69_numpre ";
   	  $sSqlDebitosVistoria .= "   where vistorianumpre.y69_codvist = {$y28_codvist} ";

   	  $rsDebitosVistoria   = db_query($sSqlDebitosVistoria);
      $aDebitosVistoria    = db_utils::getColectionByRecord($rsDebitosVistoria); 
   	  
      $aDebitos = array();
      
      
      foreach ($aDebitosVistoria as $oDebitoVistoria) {
      	
        $aDadosDebitos = array();  
        $aDadosDebitos['Numpre']  = $oDebitoVistoria->k00_numpre; 
        $aDadosDebitos['Numpar']  = $oDebitoVistoria->k00_numpar;
        $aDadosDebitos['Receita'] = $oDebitoVistoria->k00_receit;
          
        $aDebitos[] = $aDadosDebitos;      	
      }

    	if ( count($aDebitos) > 0 ) {

    	  try {
    	  	
	        $oCancelaDebito->setArreHistTXT("ANULAÇÃO DE VISTORIAS");
	        $oCancelaDebito->setTipoCancelamento(2);
	        $oCancelaDebito->setCadAcao(2);
	        $oCancelaDebito->geraCancelamento($aDebitos);
	      } catch (Exception $eException) {
	      	
	        $sqlerro  = true;
	        $erro_msg = $eException->getMessage(); 	      	
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
	include("forms/db_frmvistoriasanu.php");
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
<script>
js_tabulacaoforms("form1","y28_codvist",true,1,"y28_codvist",true);
</script>
<?
if(isset($incluir)){  	
  if($clvistoriasanu->erro_status=="0"||$sqlerro==true){
    //$clvistoriasanu->erro(true,false);
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clvistoriasanu->erro_campo!=""){
      echo "<script> document.form1.".$clvistoriasanu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvistoriasanu->erro_campo.".focus();</script>";
    }
  }else{
    $clvistoriasanu->erro(true,true);
  }
}
?>