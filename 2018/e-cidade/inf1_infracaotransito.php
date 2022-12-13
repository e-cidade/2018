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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGET = (object) $_GET;
$iOpcao = !empty($oGET->iOpcao) ? $oGET->iOpcao : null;

$oRotulo = new rotulocampo();
$oRotulo->label("i05_codigo");
$oRotulo->label("i05_descricao");
$oRotulo->label("i05_nivel");
$oRotulo->label("i05_sequencial");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">

    <form id="frmInfracaoTransito" method="post" action="" class="container">

      <center>
        <fieldset>
          <legend class="bold"> Infrações de Trânsito</legend>
          <table>
            <tr>
              <td>
                <label id="li05_sequencial" for="i05_sequencial" class="bold">Sequencial:</label>
              </td>
              <td>
                <input type="text" readonly="readonly" name="i05_sequencial" id="i05_sequencial">
              </td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="i05_codigo">
                <a id="sLabelCodigo">
                  Código da Infração:
                  </a>
               </label>
              </td>
              <td>
                <?php
                  db_input('i05_codigo',10, $Ii05_codigo,true,'text',$iOpcao,"onkeyup= 'js_ValidaCampos(this, 1, \"Código da infração\", 0, 1);'", "", "", "", '5' );
                ?>
                <input type="hidden" name="descricaoCodigo" id="descricaoCodigo" data="i05_descricao">
              </td>
            </tr>
            <tr>
              <td>
                <label id="sLabeldescricao" class="bold" for="i05_descricao"><?php echo $Li05_descricao ?> </label>
              </td>
              <td>
                <?php
                  db_input('i05_descricao',50, $Ii05_descricao,true,'text', $iOpcao, "");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                  <label id="sLabelNivel" class="bold" for="i05_nivel">Nível: </label>
              </td>
              <td >
                <?php
                  $aTipos = array('0'=>'Selecione', '1' => 'Nível 1', '2' => 'Nível 2', '3' => 'Nível 3', '4' => 'Nível 4');
                  db_select("i05_nivel", $aTipos, false, 1);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </center>

      <input id="btnAcao" type="button" value="" />
      <input id="btnPesquisar" type="button" value="Pesquisar" />
    </form>

  </div>
  <?php db_menu(); ?>
  <script>
    var iOpcao  = <?php echo $iOpcao;?>;
    var sRPC = 'inf4_infracaotransito.RPC.php';

    var oLookUpInfracoes = new DBLookUp($('li05_sequencial'), $('i05_sequencial'), $('descricaoCodigo'),  {
      "sArquivo" : "func_infracaotransito.php",
      "sObjetoLookUp" : "db_iframe_infracaotransito",
      "sLabel" : "Pesquisa Infrações de Trânsito",
      "fCallBack" : function (iSequencial) {
        var oParametros = {
            exec : 'pesquisarInfracaoTransito',
            iSequencial: iSequencial,
        };
        new AjaxRequest(sRPC, oParametros, function (oRetorno) {
          preenchecampos(oRetorno.oInfracao);
        }).execute();
      }
    });
    oLookUpInfracoes.desabilitar();

    /**
     * Validamos se a requisicao eh inclusao, alteracao ou exclusao, alterando o valor do botao acao e chamando a pesquisa
     * quando for alteracao e exclusao
     */
    function aplicarRegrasTela(iOpcao) {

      switch(iOpcao) {

        case 1:

          $('btnPesquisar').hide();
          $('btnAcao').value = 'Salvar';
          $('btnAcao').observe("click", function(event) {
            salvar();
          });
          break;

        case 2:

          $('btnAcao').value    = 'Salvar';
          $('btnAcao').disabled = true;
          $('btnAcao').observe("click", function(event) {
            salvar();
          });
          oLookUpInfracoes.abrirJanela(true);
          break;

        case 3:

          $('btnAcao').value    = 'Excluir';
          $('btnAcao').disabled = true;
          $('btnAcao').observe("click", function(event) {
            excluir();
          });
          $('i05_nivel').setAttribute('readonly', 'readonly');
          $('i05_nivel').addClassName('readonly');
          $('i05_nivel').disabled = true;
          oLookUpInfracoes.abrirJanela(true);
          break;
      }
    }

    function excluir() {

      if (confirm('Deseja realmente excluir?')) {

        var oParametros = {
          exec: 'excluirInfracaoTransito',
          sequencial : $F('i05_sequencial'),
          codigo     : $F('i05_codigo'),
        };

        new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro){
          alert(oRetorno.message);
          if (lErro) {
            return false;
          }
          location.reload();

        }).execute();
      }
    }

    function salvar() {

      var oParametros = {
        exec       : 'salvarInfracaoTransito',
        sequencial : $F('i05_sequencial'),
        codigo     : $F('i05_codigo'),
        descricao  : $F('i05_descricao'),
        nivel      : $F('i05_nivel'),
      };

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

        alert(oRetorno.message);
        if (lErro) {
          return false;
        }
        $('i05_sequencial').value = oRetorno.iSequencial;
        if (iOpcao == 1) {
            $('frmInfracaoTransito').reset();
        }
      }).execute();

    }

    $('btnPesquisar').observe("click", function(event) {
      oLookUpInfracoes.abrirJanela(true);
    });

    function preenchecampos(oInfracao) {

      document.getElementById('i05_sequencial').value = oInfracao.i05_sequencial;
      document.getElementById('i05_codigo').value     = oInfracao.i05_codigo;
      document.getElementById('i05_descricao').value  = oInfracao.i05_descricao;
      document.getElementById('i05_nivel').value  = oInfracao.i05_nivel;

      $('btnAcao').disabled = false;
    }

    aplicarRegrasTela(iOpcao);
  </script>
</body>
</html>

