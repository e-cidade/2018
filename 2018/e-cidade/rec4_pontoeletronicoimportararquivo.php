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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("rh196_arquivo");

$listaSobrescreverArquivo = array(0=>'Não',1=>'Sim');
?>
<html>
<head>
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load(array(
    "strings.js",
    "scripts.js",
    "dates.js",
    "prototype.js",
    "strings.js",
    "AjaxRequest.js",
    "widgets/DBLookUp.widget.js",
    "widgets/Input/DBInput.widget.js",
    "widgets/Input/DBInputDate.widget.js",
    "estilos.css",
    "grid.style.css",
    "classes/recursoshumanos/Efetividade/PeriodoEfetividade.js"
  ));
  ?>
  <style type="text/css">
  </style>
</head>
<body>
<div class="container">
  <form method="POST" id="importarArquivo" class="form-container">
    <fieldset>
      <legend>Arquivo do Ponto Eletrônico</legend>
      <table class="form-container">
        <tr>
          <td>
            <label for="periodoInicio">Período:</label>
          </td>
          <td id="linhaPeriodo"></td>
        </tr>
        <tr>
          <td nowrap title="Informa se as marcações já importadas irão ser sobrescritas">
            <label id="lbl_sobrescreverArquivo" for="sobrescreverArquivo">Sobrescrever Marcações:</label>
          </td>
          <td>
            <?php db_select('sobrescreverArquivo', $listaSobrescreverArquivo, true, 1) ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Trh196_arquivo; ?>">
            <label id="lbl_rh196_arquivo" for="rh196_arquivo"><?php echo $Lrh196_arquivo; ?></label>
          </td>
          <td>
            <?php db_input('rh196_arquivo', 10, $Irh196_arquivo, true, "file", 1); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value="Importar" onclick="importarArquivo()" />
  </form>
</div>
<script type="text/javascript">

function importarArquivo () {

  if(!oPeriodo.validarPreenchimentoPeriodo()) {
    alert('Informe as datas do período')
    return false;
  }

  if(!!parseInt($F('sobrescreverArquivo'))) {    

    var mensagemAlertaSobreescrita = "Atenção\n\nAo selecionar 'Sobrescrever Marcações' como 'Sim' as alterações manuais serão perdidas.";

    if(!confirm(mensagemAlertaSobreescrita)) {
      return false;
    }
  }

  AjaxRequest.create(
    'rec4_pontoeletronico.RPC.php',
    {
      exec    : 'importarArquivo',
      periodo : {
        dataInicio : oPeriodo.getDataFormatada(oPeriodo.getDataInicio()),
        dataFim    : oPeriodo.getDataFormatada(oPeriodo.getDataFim()),
      },
      sobrescrever: $F('sobrescreverArquivo')
    },
    function (retorno, erro) {
      
      if(retorno.mensagem) {
        alert(retorno.mensagem);
      }

      if(erro) {
        return;
      }
    }
  ).setMessage('Importando arquivo...').addFileInput($('rh196_arquivo')).execute();
}

var oPeriodo = new PeriodoEfetividade();
    oPeriodo.show($('linhaPeriodo'));
</script>
<?php db_menu(); ?>
</body>
</html>
