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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
    ?>
    <script src="scripts/prototype.js"></script>
    <script src="scripts/scripts.js"></script>
    <script src="scripts/strings.js"></script>
    <script src="scripts/classes/DBViewFormularioFolha/PesquisaServidores.classe.js"></script>
  </head>
  <body bgcolor="#CCCCCC">
    <div class="container">
      <fieldset>
      <legend>Consulta de Férias:</legend>
      <table class="form-container">
        <tr>
          <td id="containerAncora"></td>
          <td id="containerInput"></td>
        </tr>
      </table>
      </fieldset>
    <span id='containerBotao'><span>
    </div>
    <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit") ); ?>
  </body>
</html>

<script>
var oPesquisaServidores = new DBViewFormularioFolha.PesquisaServidores(1);
oPesquisaServidores.fixarAncora      ( $('containerAncora') );
oPesquisaServidores.fixarMatricula   ( $('containerInput') );
oPesquisaServidores.fixarNomeServidor( $('containerInput') );
oPesquisaServidores.fixarBotao       ( $('containerBotao'), function() {

  if ( oPesquisaServidores.oInputMatricula.getValue() == '' ) {
    return false;
  }

  if ( oPesquisaServidores.oInputNomeServidor.getValue() == '' ) {
	    return false;
	}
  
  window.location.href = 'pes2_consultaferias002.php?rh01_regist=' + oPesquisaServidores.oInputMatricula.getValue() + '&z01_nome=' + 
                         oPesquisaServidores.oInputNomeServidor.getValue();
});
</script>