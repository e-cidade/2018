<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pcsugforn_classe.php");
require_once("classes/db_solicitem_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("model/CgmFactory.model.php");
require_once("model/fornecedor.model.php");

db_postmemory($HTTP_POST_VARS);

$clpcsugforn = new cl_pcsugforn;
$clsolicitem = new cl_solicitem;
$clpcparam   = new cl_pcparam;

$db_opcao         = 22;
$db_botao         = false;
$iStatusBloqueio  = 0;
$erro_msg         = "";

if(isset($alterar) || isset($excluir) || isset($incluir) || isset($opcao)){
  $sqlerro = false;
  $clpcsugforn->pc40_solic = $pc40_solic;
  $clpcsugforn->pc40_numcgm = $pc40_numcgm;
  if(isset($incluir)){
    if($sqlerro==false){
      db_inicio_transacao();
      
      try {
        
	      $oFornecedor = new fornecedor($pc40_numcgm);
	      $oFornecedor->verificaBloqueioSolicitacao($pc40_solic);
	      $iStatusBloqueio = $oFornecedor->getStatusBloqueio();      
      } catch (Exception $eException) {
      	$sqlerro  = true;
      	$erro_msg = $eException->getMessage();
      } 
      
      if($iStatusBloqueio == 2){
      	$erro_msg  = "\\nusuário:\\n\\n Fornecedor com débito na prefeitura !\\n\\n\\n\\n";
      }
      if ($sqlerro==false) {
        
        $clpcsugforn->incluir($pc40_solic,$pc40_numcgm);
        $erro_msg = $clpcsugforn->erro_msg;
        
        if($clpcsugforn->erro_status==0){
	        $sqlerro=true;
        }
      }
      db_fim_transacao($sqlerro);
    }
  }else if(isset($excluir)){
    if($sqlerro==false){
      db_inicio_transacao();
      $clpcsugforn->excluir($pc40_solic,$pc40_numcgm);
      $erro_msg = $clpcsugforn->erro_msg;
      if($clpcsugforn->erro_status==0){
	      $sqlerro=true;
      }
      db_fim_transacao($sqlerro);
    }
  }else if(isset($opcao)){
//    die($clpcsugforn->sql_query($pc40_solic,$pc40_numcgm,"pc40_solic,pc40_numcgm,z01_nome"));
    $result = $clpcsugforn->sql_record($clpcsugforn->sql_query($pc40_solic,$pc40_numcgm,"pc40_solic,pc40_numcgm,z01_nome"));
    if($result!=false && $clpcsugforn->numrows>0){
      db_fieldsmemory($result,0);
    }
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js, strings.js, widgets/dbtextField.widget.js,
               dbViewNotificaFornecedor.js, dbmessageBoard.widget.js, dbautocomplete.widget.js,
               dbcomboBox.widget.js,datagrid.widget.js,widgets/dbtextFieldData.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
			<?
			  include("forms/db_frmsugforn.php");
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
  if($sqlerro==true){
  	db_msgbox($erro_msg);
    if($clpcsugforn->erro_campo!=""){
        echo "<script> document.form1.".$clpcsugforn->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clpcsugforn->erro_campo.".focus();</script>";
    }
  }else if($iStatusBloqueio == 2){
  	db_msgbox($erro_msg);
  }
}
?>