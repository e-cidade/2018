<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_nome");

$db_opcao = 1;

?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("windowAux.widget.js");
      db_app::load("strings.js");
      db_app::load("dbtextField.widget.js");
      db_app::load("dbViewAvaliacoes.classe.js");
      db_app::load("dbmessageBoard.widget.js");
      db_app::load("dbautocomplete.widget.js");
      db_app::load("dbcomboBox.widget.js");
      db_app::load("datagrid.widget.js");
      db_app::load("widgets/DBLookUp.widget.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
  </head>
  <body onload="$('lbl_rh01_regist').click()">
    <form id="formPesquisarEsocial" method="POST" action="eso4_preenchimento001.php" class="container">
      <fieldset>
        <legend>Conferência dos dados informados pelo servidor:</legend>
        <table class="form-container">
          <tr>
            <td nowrap title="<?php echo $Trh01_regist; ?>">
              <a id="lbl_rh01_regist" for="matricula"><?=$Lrh01_regist?></a>
            </td>
            <td>
              <?php db_input('rh01_regist', 10, $Irh01_regist, true, "text", 3); ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Tz01_nome; ?>">
              <label id="lbl_z01_nome" for="z01_nome">Servidor:</label>
            </td>
            <td><?php db_input('z01_nome', 50, $Iz01_nome, true, "text", $db_opcao); ?></td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" />
    </form>
    
    <div id="questionario"></div>
    <?php db_menu(); ?>
  </body>
</html>

<script>
(function() {

  $('pesquisar').observe("click", function pesquisar() {
  
    var iMatricula = $F('rh01_regist');
  
    if(iMatricula.trim() == '' || iMatricula.trim().match(/[^\d]+/g)) {
      
      alert('Informe um número de Matrícula válido para pesquisar.');
      return;
    }
  
    this.form.submit();
  });

  var oLookUpCgm = new DBLookUp ($('lbl_rh01_regist'), $('rh01_regist'), $('z01_nome'), {
    'sArquivo'          : 'func_rhpessoal.php',
    'oObjetoLookUp'     : 'func_nome'
  });
})();
</script>
