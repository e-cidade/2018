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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

set_time_limit(0);
$user = db_getsession ( "DB_id_usuario" );
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">

    <form name="form1" method="post" action="">
      <fieldset style="width: 50%">
        <legend>Importação do Cartão SUS</legend>

          <table>
            <tr>
              <td width="50%" colspan="2"> <?php echo db_criatermometro ( 'termometro', 'Concluido...', 'blue', 1 ); ?></td>
            </tr>
            <tr>
              <td align="center"><input type="submit" width="25%" name="processar" value="Processar"></td>
              <td align="center"><input type="button" width="25%" name="emitir" value="Emite Conferência" disabled="disabled" onclick="js_relatorio();" id="emitir"></td>
            </tr>
          </table>
          <input name="codigo" id="codigo" value="" style="display: none;">
      </fieldset>
    </form>
    <?php

    db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );

    include ("sau4_importsus002.php");

    //Iniciando processo
    if ( isset ( $processar ) ) {

      atualiza_cadsus ( 1, $conn, null, $DB_SERVIDOR, $DB_BASE, $DB_PORTA, $DB_USUARIO, $DB_SENHA );
      echo "<br/><a href='db_download.php?arquivo={$arq1}'>Arquivo de log : ".$arq1."</a>";
    }
    ?>
  </div>
</body>
</html>
<script type="text/javascript">

function js_relatorio(){

  jan = window.open('sau2_cadsusconferencia002.php?codigo='+document.form1.codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>