<?php
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
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">
    
    <fieldset style="width: 500px; margin: auto;">
      <legend style="font-weight: bold;">Menu de Acesso Rápido</legend>

      <table cellpadding="10" style="font-size: 14px; margin-left: 8px; font-weight: bold;">
        <tr>
          <td>
            <input type="button" id="btnRetornaDataSistema" value="Retorna Data do Sistema" />
          </td>
        </tr>
        <tr>
          <td>
            <input type="button" id="btnHabilitarTraceLog" value="Trace Log" />
          </td>
        </tr>
        <tr>
          <td>
            <input type="button" id="btnMensagensSistema" value="Mensagens Sistema" />
          </td>
        </tr>
      </table>
    </fieldset>

  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
</html>
<script type="text/javascript">
  
  $("btnRetornaDataSistema").observe("click", function() {
     js_OpenJanelaIframe("", "iframe_retornadatasistema", "con4_trocadata.php?lParametroExibeMenu=false", "Retorna Data do Sistema", true);
  });

  $("btnHabilitarTraceLog").observe("click", function() {
     js_OpenJanelaIframe("", "iframe_tracelog", "con1_ativatrace001.php?lParametroExibeMenu=false", "Habilitar / Desabilitar TraceLog", true);
  });

  $("btnMensagensSistema").observe("click", function() {
     js_OpenJanelaIframe("", "iframe_mensagenssistema", "con4_mensagens001.php?lIframe=false", "Mensagens do Sistema", true);
  });
</script>