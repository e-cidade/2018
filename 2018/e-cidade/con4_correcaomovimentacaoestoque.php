<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, strings.js, prototype.js");
  ?>
  <script src="scripts/datagrid.widget.js"></script>
  <script src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
if ($_SESSION["DB_id_usuario"] != 1) {

  ECHO "<h1>sem permissão de acesso.</h1>";
  exit;
}
?>
  <div class="container">
    <fieldset style="width: 800px">
      <legend>Itens com movimentação inconsistentes</legend>
      <div id="ctnGridItens">

      </div>
    </fieldset>
    <input type="button" value="Processar" disabled="disabled" id='btnProcessar' onclick="processar()">
  </div>
</body>
</html>

<script>
  oGridItens = new DBGrid("gridItens");
  oGridItens.nameInstance = "oGridItens";
  oGridItens.setCellWidth(["10%", "40%", "10%", "10%", "10%", "10%", "10%"]);
  oGridItens.setCellAlign(["center", "left", "right", "right", "right", "right", "center"]);
  oGridItens.setCheckbox(0);
  oGridItens.setHeader(["Cód. Mater", "Material", "Depto", "Saldo Mov", "Em Transf.", "Estoque", "Serv."]);
  oGridItens.setHeight(400);
  oGridItens.show($('ctnGridItens'));

  /**
   * carrega os itens do
   */
   carregarItens = function () {

     new AjaxRequest('con4_correcaomovimentacaoestoque.RPC.php', {exec:'getItens'}, function(oRetorno, lErro) {

       if (lErro) {

         alert(oRetorno.mensagem.urlDecode());
         return false;
       }

       $('btnProcessar').disabled = false;
       oGridItens.clearAll(true);
       if ( oRetorno.itens.length == 0) {

         alert('Todos os itens estão com suas movimentações corretas');
         $('btnProcessar').disabled = true;
       }
       oRetorno.itens.each(function (oItem) {

         var aLinha = [

           oItem.m70_codmatmater,
           oItem.m60_descr.urlDecode(),
           oItem.m70_coddepto,
           oItem.saldo_movimentacao,
           oItem.total_transferencias,
           oItem.m70_quant,
           oItem.tem_mov_servico == 't' ? 'Sim' : 'Não'
         ];
         oGridItens.addRow(aLinha);
       });
       oGridItens.renderRows();
     }).setMessage('Aguarde, carregando lista de Itens. Este processo pode demorar uns minutos').execute();
  }

  carregarItens();
  function processar () {

    var aItens = oGridItens.getSelection('object');
    var aItensSelecionados = new Array();
    aItens.each(function(oItem) {
      aItensSelecionados.push(oItem.aCells[1].getValue());
    });
    if (aItensSelecionados.lenght == 0) {

      alert('Sem itens para processar!');
      return false;
    }
    //$('btnProcessar').disabled = true;
    new AjaxRequest('con4_correcaomovimentacaoestoque.RPC.php', {exec:'processar', itens:aItensSelecionados}, function(oRetorno, lErro) {

      alert(oRetorno.mensagem.urlDecode());
      carregarItens();
      $('btnProcessar').disabled = false;
    }).setMessage('Aguarde, corrigindo movimentação. Este processo pode demorar uns minutos').execute();
  }
</script>