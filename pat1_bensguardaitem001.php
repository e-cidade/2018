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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_bensguardaitem_classe.php");
require_once("classes/db_bensguarda_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_histbensocorrencia_classe.php");
require_once("libs/db_app.utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clbensguardaitem = new cl_bensguardaitem;
$clbensguarda = new cl_bensguarda;
$clhistbemocorrencia = new cl_histbensocorrencia;
$db_opcao = 22;
$db_botao = false;
if (isset($alterar) || isset($excluir) || isset($incluir)) {
  $sqlerro = false;
  /*
  $clbensguardaitem->t22_codigo = $t22_codigo;
  $clbensguardaitem->t22_bensguarda = $t22_bensguarda;
  $clbensguardaitem->t22_bem = $t22_bem;
  $clbensguardaitem->t22_dtini = $t22_dtini;
  $clbensguardaitem->t22_dtfim = $t22_dtfim;
  $clbensguardaitem->t22_obs = $t22_obs;
  $clbensguardaitem->t22_usuario = $t22_usuario;
   */
}
if (isset($incluir)) {
  if ($sqlerro == false) {
    db_inicio_transacao();
    $clbensguardaitem->t22_usuario = db_getsession("DB_id_usuario");
    $clbensguardaitem->incluir(null);
    $erro_msg = $clbensguardaitem->erro_msg;
    if ($clbensguardaitem->erro_status == 0) {
      $sqlerro = true;
    }
    //Inseri historico de Guarda do Bem na histbensocorrencias
    if ($sqlerro == false) {
      //$t56_codbem	
      //$this->t69_sequencial 			= null; 
      $clhistbemocorrencia->t69_codbem = $t22_bem;
      $clhistbemocorrencia->t69_ocorrenciasbens = 2; // valor vem direto da tabela
      $clhistbemocorrencia->t69_obs = "Movimento de Inclusão de Guarda do Bem";
      $clhistbemocorrencia->t69_dthist = date('Y-m-d', db_getsession('DB_datausu'));
      $clhistbemocorrencia->t69_hora = db_hora();
      $clhistbemocorrencia->incluir(null);
      if ($clhistbemocorrencia->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clhistbemocorrencia->erro_msg;
      }
    }
    db_fim_transacao($sqlerro);
  }
} else if (isset($alterar)) {
  if ($sqlerro == false) {
    db_inicio_transacao();
    $clbensguardaitem->alterar($t22_codigo);
    $erro_msg = $clbensguardaitem->erro_msg;
    if ($clbensguardaitem->erro_status == 0) {
      $sqlerro = true;
    }
    //Inseri historico de Guarda do Bem na histbensocorrencias
    if ($sqlerro == false) {
      //$t56_codbem	
      //$this->t69_sequencial 			= null; 
      $clhistbemocorrencia->t69_codbem = $t22_bem;
      $clhistbemocorrencia->t69_ocorrenciasbens = 2; // valor vem direto da tabela
      $clhistbemocorrencia->t69_obs = "Movimento de Alteração de Guarda do Bem";
      $clhistbemocorrencia->t69_dthist = date('Y-m-d', db_getsession('DB_datausu'));
      $clhistbemocorrencia->t69_hora = db_hora();
      $clhistbemocorrencia->incluir(null);
      if ($clhistbemocorrencia->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clhistbemocorrencia->erro_msg;
      }
    }
    db_fim_transacao($sqlerro);
  }
} else if (isset($excluir)) {
  if ($sqlerro == false) {
    db_inicio_transacao();
    $clbensguardaitem->excluir($t22_codigo);
    $erro_msg = $clbensguardaitem->erro_msg;
    if ($clbensguardaitem->erro_status == 0) {
      $sqlerro = true;
    }
    //Inseri historico de Guarda do Bem na histbensocorrencias
    if ($sqlerro == false) {
      //$t56_codbem	
      //$this->t69_sequencial 			= null; 
      $clhistbemocorrencia->t69_codbem = $t22_bem;
      $clhistbemocorrencia->t69_ocorrenciasbens = 2; // valor vem direto da tabela
      $clhistbemocorrencia->t69_obs = "Movimento de Exclusão de Guarda do Bem";
      $clhistbemocorrencia->t69_dthist = date('Y-m-d', db_getsession('DB_datausu'));
      $clhistbemocorrencia->t69_hora = db_hora();
      $clhistbemocorrencia->incluir(null);
      if ($clhistbemocorrencia->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clhistbemocorrencia->erro_msg;
      }
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($opcao)) {
  $result = $clbensguardaitem->sql_record($clbensguardaitem->sql_query($t22_codigo));
  if ($result != false && $clbensguardaitem->numrows > 0) {
    db_fieldsmemory($result, 0);
  }
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor=#CCCCCC>
    	<?
      include("forms/db_frmbensguardaitem.php");
      ?>
</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {
  db_msgbox($erro_msg);
  if ($clbensguardaitem->erro_campo != "") {
    echo "<script> document.form1." . $clbensguardaitem->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1." . $clbensguardaitem->erro_campo . ".focus();</script>";
  }
}
?>