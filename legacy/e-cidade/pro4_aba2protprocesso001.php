<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_processosapensados_classe.php");
include("dbforms/db_funcoes.php");

//db_postmemory($HTTP_SERVER_VARS);
//db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST,0);
$oGet  = db_utils::postMemory($_GET,0);

$clprotprocesso       = new cl_protprocesso;
$clprocessosapensados = new cl_processosapensados;

$db_opcao         = 1;
$sqlerro          = false;
$db_botao         = false;
$sMsg             = "";

if (isset($oPost->opcao) && $oPost->opcao != "") { 
  $sOpcao = $oPost->opcao;
}

if (isset($oPost->opcao) && $oPost->opcao == "Incluir") {
  $p58_codproc      = "null";
  $p30_procapensado = "null";
	
  if (isset($oPost->p58_codproc) && $oPost->p58_codproc != "") {
     $p58_codproc = $oPost->p58_codproc;
  }

  if (isset($oPost->p30_procapensado) && $oPost->p30_procapensado != "") {
     $p30_procapensado = $oPost->p30_procapensado;
  }
  
  $sSqlProcessoApensados = " select p30_procprincipal,
                                    p30_procapensado 
                               from processosapensados 
                              where p30_procprincipal = {$p58_codproc}
                                and p30_procapensado  = {$p30_procapensado}
                              order by p30_procprincipal ";
  $rsProcessoApensados   = db_query($sSqlProcessoApensados);
  $iProcessoApensados    = pg_num_rows($rsProcessoApensados);

  if ($iProcessoApensados == 0) {
     if($sqlerro == false){
       $lSqlErro = false;
       db_inicio_transacao();
      
       if ($lSqlErro == false) {
          $clprocessosapensados->p30_procprincipal = $p58_codproc;
          $clprocessosapensados->p30_procapensado  = $p30_procapensado;
          $clprocessosapensados->incluir(null);
       
          if ( $clprocessosapensados->erro_status == 0 ) {
             $lSqlErro = true;
             $sMsgErro = $clprocessosapensados->erro_msg; 
          }    
       }
     
       db_fim_transacao($lSqlErro);
       
       if ($lSqlErro == false) {
       	  $sMsg = $clprocessosapensados->erro_msg;
       }
     }
  } else {
  	$sMsg   = "Processo {$p30_procapensado} já está apensado ao processo $p58_codproc \\n!";
  	$sMsg  .= "Administrador: 1";
  }
}

if (isset($oPost->opcao) && $oPost->opcao == 'excluir') {                          
  if($sqlerro == false){
   $lSqlErro = false;
   db_inicio_transacao();
   
     if ($lSqlErro == false) {
        $clprocessosapensados->p30_procprincipal = $p58_codproc;
        $clprocessosapensados->p30_procapensado  = $p30_procapensado;
        $p30_sequencial = $clprocessosapensados->p30_sequencial;                
        $clprocessosapensados->excluir($p30_sequencial," p30_procapensado = {$p30_procapensado} ");
       
       if ( $clprocessosapensados->erro_status == 0 ) {
         $lSqlErro = true;
         $sMsgErro = $clprocessosapensados->erro_msg; 
       }    
     }

     db_fim_transacao($lSqlErro);
     
     if ($lSqlErro == false) {
        $sMsg = $clprocessosapensados->erro_msg;
     }
   
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="">
<br /><br />
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
      <?
         include("forms/db_frmprotprocessoapensados.php");
      ?>
    </td>
  </tr>
</table>
</center>
</form>
</body>
<?
if (isset($oPost->opcao) && $oPost->opcao != "") {
   if ( isset($sMsgErro) && $lSqlErro == true) {
      db_msgbox($sMsgErro);
      echo " <script>
                document.form1.p30_procapensado.value = '';
                document.form1.z01_nome.value         = '';
            </script> ";
   } else {
      db_msgbox($sMsg);
      echo " <script>
                document.form1.p30_procapensado.value = '';
                document.form1.z01_nome.value         = '';
            </script> ";
   }
}
?>
</html>
