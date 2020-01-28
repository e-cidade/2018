<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBSeller Servicos de Informatica             
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
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_nome");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
      db_app::load('scripts.js, prototype.js, dbcomboBox.widget.js, dbtextField.widget.js, strings.js, DBHint.widget.js, DBLookUp.widget.js');
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <form id="form1" name="form1" action="pes4_contratosemergenciais002.php" method="POST" onsubmit="return js_validacampos()" class="container">  
      <fieldset>
        <legend>Servidor</legend>
        <table class="container-form">
          <tr>
            <td nowrap="" title="<?php echo $Trh01_regist?>">
              <a id="procurarMatricula"><?php echo $Lrh01_regist; ?></a>
            </td>
            <td>
              <?
                db_input('rh01_regist', 10, '', true, 'text', 1, "");
              ?>
            </td>
            <td title="<?php echo $Tz01_nome?>">
              <?
                db_input('z01_nome', 50, '', true, 'text', 3, "");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="submit" id="pesquisar" name="pesquisar" value="Pesquisar" />
    </form>
    <script type="text/javascript">

      var oLookupServidor = new DBLookUp($("procurarMatricula"), $("rh01_regist"), $("z01_nome"), {
        "sArquivo"              : "func_rhpessoal.php",
        "sObjetoLookUp"         : "db_iframe_rhpessoal",
        "aParametrosAdicionais" : ["testarescisao=true&contratosEmergenciais=1"]
      });

      function js_validacampos() {

        if(document.form1.rh01_regist.value == "") {
          alert("Informe uma matrícula!");
          return false;
        }
      }

    </script>
    <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
  </body>
</html>
