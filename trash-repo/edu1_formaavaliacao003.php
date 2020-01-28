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
$db_botao           = false;
$db_opcao           = 33;
$db_opcao1          = 3;

if (isset($excluir)) {
	
  db_inicio_transacao();
  $db_opcao  = 3;
  $db_opcao1 = 3;
  $oDaoFormaAvaliacao->excluir($ed37_i_codigo);
  db_fim_transacao();
  
} elseif(isset($chavepesquisa)) {
	
  $db_opcao           = 3;
  $sSqlFormaAvaliacao = $oDaoFormaAvaliacao->sql_query($chavepesquisa);
  $rsFormaAvaliacao   = $oDaoFormaAvaliacao->sql_record($sSqlFormaAvaliacao);
  db_fieldsmemory($rsFormaAvaliacao,0);
  $db_botao               = true;
  $ed37_c_minimoaprovconc = $ed37_c_minimoaprov;
  $ed37_c_minimoaprovnota = $ed37_c_minimoaprov;
  
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
     </center>
  <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
            db_getsession("DB_anousu"), db_getsession("DB_instit")
           );
  ?>
 </body>
</html>
<?
if (isset($ed37_c_tipo) && $ed37_c_tipo == "NIVEL") {
  ?><script>document.getElementById("conceito").style.visibility = "visible";</script><?
} elseif (isset($ed37_c_tipo) && $ed37_c_tipo == "PARECER") {
  ?><script>document.getElementById("parecer").style.visibility = "visible";</script><?
} elseif (isset($ed37_c_tipo) && $ed37_c_tipo == "NOTA") {
	
 ?>
  <script>
   document.getElementById("nota").style.visibility = "visible";
   document.form1.ed37_i_menorvalor.value = "<?=number_format($ed37_i_menorvalor,2,'.','.')?>";
   document.form1.ed37_i_maiorvalor.value = "<?=number_format($ed37_i_maiorvalor,2,'.','.')?>";
   document.form1.ed37_i_variacao.value = "<?=number_format($ed37_i_variacao,2,'.','.')?>";
  </script>
 <?
 
}

if (isset($excluir)) {
	
  if ($oDaoFormaAvaliacao->erro_status == "0") {
    $oDaoFormaAvaliacao->erro(true,false);
  } else {
    $oDaoFormaAvaliacao->erro(true,true);
  } 
  
}

if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>