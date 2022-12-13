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
require("libs/db_utils.php");
include("classes/db_sepultamentoisencao_classe.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clsepultamentoisencao = new cl_sepultamentoisencao;
$db_opcao = 22;
$db_botao = false;

if(isset($oPost->alterar)){
	
  db_inicio_transacao();
  $db_opcao = 2;
  
  $clsepultamentoisencao->cm33_sequencial   = $oPost->cm33_sequencial;
  $clsepultamentoisencao->cm33_sepultamento = $oPost->cm33_sepultamento;
  $clsepultamentoisencao->cm33_isencao      = $oPost->cm33_isencao;
  $clsepultamentoisencao->cm33_processo     = $oPost->cm33_processo;
  $clsepultamentoisencao->cm33_datalanc     = implode('-',array_reverse(explode("/",$oPost->cm33_datalanc)));
  $clsepultamentoisencao->cm33_obs          = $oPost->cm33_obs;
  $clsepultamentoisencao->cm33_datainicio   = implode('-',array_reverse(explode("/",$oPost->cm33_datainicio)));
  $clsepultamentoisencao->cm33_datafim      = implode('-',array_reverse(explode("/",$oPost->cm33_datafim)));
  $clsepultamentoisencao->cm33_percentual   = $oPost->cm33_percentual;
  
  $clsepultamentoisencao->alterar($oPost->cm33_sequencial);
  
  if ( $clsepultamentoisencao->erro_status == 0 ) {
  	$lErro = true;
  } else {
  	$lErro = false;
  }
  
  db_fim_transacao($lErro);
  
}else if(isset($oGet->chavepesquisa)){
   $db_opcao = 2;
   
   $sCampos  = "sepultamentoisencao.*,        "; 
   $sCampos .= "cgm.z01_nome as z01_nome_prot,";
   $sCampos .= "a.z01_nome,                   ";
   $sCampos .= "cm34_tipo,                    ";
   $sCampos .= "cm34_descricao                ";
   
   $result = $clsepultamentoisencao->sql_record($clsepultamentoisencao->sql_query($oGet->chavepesquisa,$sCampos));
   db_fieldsmemory($result,0);
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
<table style="padding-top:23px;" align="center">
  <tr> 
    <td> 
    <center>
	<?
	include("forms/db_frmsepultamentoisencao.php");
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
if(isset($oPost->alterar)){
  if($clsepultamentoisencao->erro_status=="0"){
    $clsepultamentoisencao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsepultamentoisencao->erro_campo!=""){
      echo "<script> document.form1.".$clsepultamentoisencao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsepultamentoisencao->erro_campo.".focus();</script>";
    }
  }else{
    $clsepultamentoisencao->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","cm33_processo",true,1,"cm33_processo",true);
</script>