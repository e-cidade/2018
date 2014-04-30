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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("model/educacao/ArredondamentoNota.model.php");
db_postmemory($HTTP_POST_VARS);
$oDaoFormaAvaliacao = db_utils::getdao("formaavaliacao");
$oDaoConceito       = db_utils::getdao("conceito");
$db_opcao           = 1;
$db_opcao1          = 1;
$db_botao           = true;
if (isset($incluir)) {
	
  db_inicio_transacao();
  
  if ($ed37_c_tipo == "PARECER") {
  	
    $oDaoFormaAvaliacao->ed37_i_menorvalor  = "0";
    $oDaoFormaAvaliacao->ed37_i_maiorvalor  = "0";
    $oDaoFormaAvaliacao->ed37_i_variacao    = "0";
    $oDaoFormaAvaliacao->ed37_c_minimoaprov = "0";
    
  } elseif ($ed37_c_tipo == "NIVEL") {
  	
    $oDaoFormaAvaliacao->ed37_i_menorvalor  = "0";
    $oDaoFormaAvaliacao->ed37_i_maiorvalor  = "0";
    $oDaoFormaAvaliacao->ed37_i_variacao    = "0";
    
    if (@$ed37_c_minimoaprovconc == "") {
      $oDaoFormaAvaliacao->ed37_c_minimoaprov = "0";
    } else {
      $oDaoFormaAvaliacao->ed37_c_minimoaprov = str_replace(", ", ".", $ed37_c_minimoaprovconc);
    }
    
  } else { 
    $oDaoFormaAvaliacao->ed37_c_minimoaprov = str_replace(", ", ".", $ed37_c_minimoaprovnota);
  }
  $oDaoFormaAvaliacao->ed37_i_escola = db_getsession("DB_coddepto");
  $oDaoFormaAvaliacao->incluir($ed37_i_codigo);
  db_fim_transacao();
  $db_botao = false;
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <?
    db_app::load("prototype.js, prototype.maskedinput.js, strings.js, webseller.js");
  ?>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" style="margin-top: 25px">
     <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>
     <br>
     <center>
       <div style="display: table">
         <?include("forms/db_frmformaavaliacao.php");?>
       </div>
 </body>
</html>

<?db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
          db_getsession("DB_anousu"), db_getsession("DB_instit")
         );
         
if (isset($ed37_c_tipo) && $ed37_c_tipo == "NIVEL") {
  ?><script>document.getElementById("conceito").style.visibility = "visible";</script><?
} elseif (isset($ed37_c_tipo) && $ed37_c_tipo == "PARECER") {
  ?><script>document.getElementById("parecer").style.visibility = "visible";</script><?
} elseif (isset($ed37_c_tipo) && $ed37_c_tipo == "NOTA") {
  ?><script>document.getElementById("nota").style.visibility = "visible";</script><?
}

if (isset($incluir)) {
	
  if ($oDaoFormaAvaliacao->erro_status == "0") {
  	
    $oDaoFormaAvaliacao->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoFormaAvaliacao->erro_campo != "") {
    	
      echo "<script> document.form1.".$oDaoFormaAvaliacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoFormaAvaliacao->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $oDaoFormaAvaliacao->erro(true, false);
    ?>
    <script>
     location.href = "edu1_formaavaliacao002.php?chavepesquisa=<?=$oDaoFormaAvaliacao->ed37_i_codigo?>";
    </script>
   <?
   
  }
  
}
?>