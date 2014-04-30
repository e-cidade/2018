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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_tipoproc_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);

$cltipoproc   = new cl_tipoproc;
$db_opcao     = 1;
$db_botao     = true;

if(isset($oPost->db_opcao) && $oPost->db_opcao == "Incluir"){

  $lSqlErro = false;
  db_inicio_transacao();
  
  $cltipoproc->p51_instit = db_getsession("DB_instit");
  $cltipoproc->p51_tipoprocgrupo = 2;  
  $cltipoproc->incluir($p51_codigo);
  
   if ( $cltipoproc->erro_status == 0 ) {
     $lSqlErro = true;
     $sMsgErro = $cltipoproc->erro_msg; 
   }   
  
  db_fim_transacao($lSqlErro);
  
   if ( isset($sMsgErro) && $lSqlErro === true) {
     db_msgbox($sMsgErro);
  	} else {
  	 db_msgbox("Administrador: \\n - Inclusão Efetuada com Sucesso!");
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
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr>  
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	  <?
	    include("forms/db_frmouvtipoproc.php");
	  ?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<?
if(isset($oPost->db_opcao) && $oPost->db_opcao == "Incluir"){	
  if($cltipoproc->erro_status == "0"){
    //$cltipoproc->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltipoproc->erro_campo != ""){
      echo "<script> document.form1.".$cltipoproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltipoproc->erro_campo.".focus();</script>";
    }
  } else {
      echo "<script>
              parent.iframe_departamento.location.href='ouv1_aba2depto004.php?p51_codigo=".@$cltipoproc->p51_codigo."&db_opcao=".$db_opcao."';
              parent.iframe_formreclamacao.location.href='ouv1_aba3formrecl004.php?p51_codigo=".@$cltipoproc->p51_codigo."&db_opcao=".$db_opcao."';
              parent.iframe_tipoprocesso.location.href='ouv1_aba1tipoproc005.php?chavepesquisa=".@$cltipoproc->p51_codigo."';
              parent.iframe_andamentopadrao.location.href='pro1_andpadrao001.php?p53_codigo=".@$cltipoproc->p51_codigo."&p51_descr=".@$cltipoproc->p51_descr."&aba=true';
              parent.mo_camada('departamento');
              parent.document.formaba.departamento.disabled    = false;
              parent.document.formaba.formreclamacao.disabled  = false;
              parent.document.formaba.andamentopadrao.disabled = false;              
            </script>";  
  }
}
?>