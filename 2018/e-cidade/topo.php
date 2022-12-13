<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once('model/configuracao/SkinService.service.php');

$hora = time();

  db_query($conn, "insert into db_usuariosonline 
                       values( ".db_getsession("DB_id_usuario").",
                               ".$hora.",
                              '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."',
                              '".db_getsession("DB_login")."',
                              'Entrou no sistema',            
                              '',
                              ".time().",
                              ' ')") or die("Erro:(27) inserindo arquivo em db_usuariosonline: " . pg_errormessage());

  db_putsession("DB_uol_hora", $hora);
$result = db_query("select nome,login,administrador from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario"));

$oDadosUsuario = db_utils::fieldsMemory($result, 0);

$lPermiteRotinaEspecial = false;
if (db_getsession('DB_login') === "dbseller" && db_getsession("DB_id_usuario") === "1") {
  $lPermiteRotinaEspecial = true;
}

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script>

      function js_scl() {
        if (parent.document.getElementById('corpo').scrolling == 'no') {
          parent.document.getElementById("corpo").scrolling = 'yes';
        } else {
          parent.document.getElementById("corpo").scrolling = 'no';
        }
      }

      function js_abrirSite(sUrl){
        
        var sizeWidth  = screen.availWidth; 
        var sizeHeight = screen.availHeight;
        var jan = window.open(sUrl,'','height='+sizeHeight+',width='+sizeWidth+',scrollbars=0');
    }
    
  </script>
  </head>
  <?php 
    $oSkin = new SkinService();

    include( $oSkin->getPathFile("topo.php") );
  ?>
  <script>

    window.document.captureEvents(Event.KEYDOWN);
    window.document.onkeydown  = function (event) {
      switch (event.which) {     

       case 116: 
       
        return false;
        break;
      };
    }

  function js_montarJanelaMensagens() {

    var iWidthParent  = top.corpo.document.body.clientWidth;
    var iHeightParent = top.corpo.document.body.clientHeight;

    var iWidthJanela  = 900;
    var iHeightJanela = 900;

    if ( iWidthParent < iWidthJanela ) {
      iWidthJanela = iWidthParent;
    }
    
    if ( iHeightParent < iHeightJanela ) {
      iHeightJanela = iHeightParent;
    }

    var iMarginLeft = (iWidthParent - iWidthJanela) / 2;
    var iMarginTop  = 25;
    iHeightJanela  -= iMarginTop;

    var sNomeIframePai       = 'top.corpo';
    var sNomeIframeMensagens = 'db_iframe_mensagens_sistema';
    var sNomeArquivo         = 'con4_mensagens002.php';
    var sTituloJanela        = 'Mensagens';

    js_OpenJanelaIframe(sNomeIframePai, sNomeIframeMensagens, sNomeArquivo, sTituloJanela, true, 
                        iMarginTop, iMarginLeft, iWidthJanela, iHeightJanela);
    top.corpo.document.getElementById('Jandb_iframe_mensagens_sistema').style.zIndex = '999999';
    return false;
  }

    function js_direcionarUsuarioRotinaEspecial() {
      parent.document.getElementById("corpo").src = "con1_acessorapido001.php";
    }
  </script>
</html>