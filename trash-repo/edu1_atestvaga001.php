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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_jsplibwebseller.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

$ed102_d_data_dia = date("d", db_getsession("DB_datausu"));
$ed102_d_data_mes = date("m", db_getsession("DB_datausu"));
$ed102_d_data_ano = date("Y", db_getsession("DB_datausu"));

db_postmemory($HTTP_POST_VARS);

$oDaoAtestVaga  = db_utils::getdao("atestvaga");
$db_opcao       = 1;
$db_botao       = true;
$ed102_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome    = db_getsession("DB_nomedepto");

if (isset($incluir)) {
	
  db_inicio_transacao();
  $oDaoAtestVaga->ed102_i_usuario = db_getsession("DB_id_usuario");
  $oDaoAtestVaga->incluir($ed102_i_codigo);
  db_fim_transacao();
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>
   
   <?include("forms/db_frmatestvaga.php");?>
   
   <?
     db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
             db_getsession("DB_anousu"), db_getsession("DB_instit")
            );
   ?>
 </body>
</html>
<script>
js_tabulacaoforms("form1", "ed102_i_aluno", true, 1, "ed102_i_aluno", true);
</script>
<?
if (isset($incluir)) {
	
  if ($oDaoAtestVaga->erro_status == "0") {
  	
    $oDaoAtestVaga->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    echo "<script> document.form1.db_opcao.style.visibility='visible';</script>  ";
    
    if ($oDaoAtestVaga->erro_campo != "") {
    	
      echo "<script> document.form1.".$oDaoAtestVaga->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAtestVaga->erro_campo.".focus();</script>";
    }
  } else {
  	
    $oDaoAtestVaga->erro(true, false);   
    ?>
    <script>
     jan = window.open('edu2_atestvaga002.php?alunos=<?=$oDaoAtestVaga->ed102_i_codigo?>', '', 
    	               'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 '
    	              );
     jan.moveTo(0, 0);
    </script>
    <?
    db_redireciona("edu1_atestvaga001.php");
  }
}
?>