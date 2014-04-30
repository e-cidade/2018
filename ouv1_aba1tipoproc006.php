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
include("libs/db_utils.php");
include("classes/db_tipoproc_classe.php");
include("classes/db_tipoprocdepto_classe.php");
include("classes/db_tipoprocformareclamacao_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cltipoproc       = new cl_tipoproc;
$cltipoprocdepto  = new cl_tipoprocdepto();
$cltipoprocformareclamacao = new cl_tipoprocformareclamacao();
$db_opcao         = 33;
$db_botao         = false;
$sqlerro          = false;

if(isset($oPost->db_opcao) && $oPost->db_opcao == "Excluir"){
   if (isset($oPost->p51_codigo)) {
	  $iCodigo = $oPost->p51_codigo;
   }
   
  $result = $cltipoproc->sql_record(" select p58_codigo 
                                        from protprocesso 
                                       where p58_codigo = {$iCodigo} limit 1 ");

  if($cltipoproc->numrows > 0){
    db_msgbox('Aviso: \\n - Exclusão não permitida! \\n - Este Tipo de Processo possui processos vinculados.');   	
    $sqlerro = true;	
  } else {
    if(isset($sqlerro) && $sqlerro == false){ 

     $lSqlErro = false;
     db_inicio_transacao();
     
     $cltipoprocdepto->excluir(null,"p41_tipoproc = {$iCodigo} ");

      if ( $cltipoprocdepto->erro_status == 0 ) {
        $lSqlErro = true;
        $sMsgErro = $cltipoprocdepto->erro_msg; 
      }
           
     $cltipoprocformareclamacao->excluir(null,"p43_tipoproc = {$iCodigo} ");

      if ( $cltipoprocformareclamacao->erro_status == 0 ) {
        $lSqlErro = true;
        $sMsgErro = $cltipoprocformareclamacao->erro_msg; 
      }
      
     $cltipoproc->excluir(null,"p51_codigo=$iCodigo");
   
      if ( $cltipoproc->erro_status == 0 ) {
        $lSqlErro = true;
        $sMsgErro = $cltipoproc->erro_msg; 
      } 
   
     db_fim_transacao($lSqlErro);
     
      if ( isset($sMsgErro) && $lSqlErro === true) {
	    db_msgbox('Aviso: \\n - Exclusão não permitida! \\n - Este Tipo de Processo possui processos vinculados!');
  	  } else {
  	    db_msgbox("Administrador: \\n - Exclusão Efetuada com Sucesso!");
  	  }     
    }
  }
  
} else if (isset($chavepesquisa)) {
   $result = $cltipoproc->sql_record($cltipoproc->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_opcao = 3;
   $db_botao = true;
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
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
  if ( isset($oPost->excluir) ){
  	if ( isset($lsqlErro) && $lsqlErro == true ) {
	  db_msgbox($sMsgErro);
  	}
  }
?>