<?php
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

db_app::import('configuracao.TaskManager');

$oPost = db_utils::postMemory($_POST);

if ( isset($oPost->iniciar) ) {
  echo $sRetorno = system("php -q con4_gerenciadortarefas002.php > tmp/log_gerenciador_tarefas.log 2> tmp/erros_gerenciador_tarefas.log &");
}

$lPermiteInicializacao = true;
$oTaskManager          = TaskManager::getInstance();

if ( !$oTaskManager->iniciarServico(true) ) {
  $lPermiteInicializacao = false;
}
if ( isset($oPost->iniciar) && $lPermiteInicializacao ) {
  $sRetorno = system("sh jobs/configuracoes/taskManager/executa.sh&", $teste);
  $lPermiteInicializacao = false;
}


?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("strings.js");
      db_app::load("estilos.css");
    ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <BR>
    <BR>
    <BR>
    <center>
      <form method="post">
        <fieldset style="width:300px;">
          <legend><strong>Gerenciador de Tarefas:</strong></legend>
          
          <table>
            <tr>
              <td><B>Status:</B></td>
              <td><?php echo $lPermiteInicializacao ? "Inativo" : "Ativo"; ?>
              </td>
            </tr>
            
            <tr>
              <td><B>PID do Processo:</B></td>
              <td><?php echo $oTaskManager->getPIDProcesso(); ?></td>
            </tr>
            
            <tr>
              <td><B>Inicio:</B></td>
              <td><?php echo $oTaskManager->getDataInicio() . " - " . $oTaskManager->getHoraInicio(); ?></td>
            </tr>
            
          </table>     
        
        </fieldset>
        <input type="submit" name="iniciar" value="Iniciar Serviço" <?php echo $lPermiteInicializacao ? "" : "disbled"; ?> />
      </form>
    </center>
    <?php
      db_menu(db_getsession("DB_id_usuario"),
              db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),
              db_getsession("DB_instit"));
    ?>
  </body>
</html>