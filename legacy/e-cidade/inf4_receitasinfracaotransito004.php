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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clrotulo = new rotulocampo;

$clrotulo->label("k81_receita");
$clrotulo->label("k13_conta");
$clrotulo->label("c61_codigo");
$clrotulo->label("k13_descr");
$clrotulo->label("k02_drecei");
$clrotulo->label("i06_receitaprincipal");
$clrotulo->label("i06_receitaduplicidade");
$clrotulo->label("i06_conta");
$db_opcao = 1;



db_postmemory($_SERVER);
db_postmemory($_POST);
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/classes/DBViewContaBancaria.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body style="background-color: #CCCCCC;" onload="pesquisaNiveis()">
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
        <legend class="bold">
            Configuração de Nível das Receitas
        </legend>
        <fieldset style="border:none">
          <table border="0" width="100%">
            <tr>
              <td class='tamanho-primeira-col' nowrap title="<?php echo $Tk13_conta; ?>">
                <label for="k13_conta" class="bold">
                  <a id="lblSaltes">
                    <?php echo $Li06_conta;?>
                  </a>
                </label>
              </td>
              <td colspan='3'>
                <?php
                db_input('k13_conta', 10, $Ik13_conta, true, 'text', 2, 'data="k13_conta"');
                db_input('k13_descr', 50, $Ik13_descr, true, 'text', 3, "class='input-maior' data='k13_descr'");
                ?>
              </td>
            </tr>
          </table>
        <div style="text-align: left" id="semconfiguracao"></div>
        </fieldset>
        <fieldset>
          <legend class="bold">Níveis</legend>
          <table border="0" width="100%">
            <tr>
              <td align="left" nowrap title="Nível" class="tamanho-primeira-col bold">Nível:</td>
              <td colspan='3'>
                <?php
                  $aTipos = array('0'=>'Selecione', '1' => 'Nível 1', '2' => 'Nível 2', '3' => 'Nível 3', '4' => 'Nível 4');
                  db_select("i06_nivel", $aTipos, "", "", "onchange=pesquisaNivel()");
                ?>
              </td>
            </tr>
            <tr>
              <td class='tamanho-primeira-col' nowrap>
                <label for="codreceita_principal" class="bold">
                  <a id="receita_principal"><?php echo $Li06_receitaprincipal;?></a>
                </label>
              </td>
              <td colspan='3'>
                <?php
                db_input('codreceita_principal', 10, $Ik81_receita, true, 'text', 2, 'data="k02_codigo"');
                db_input('drecei_principal'    , 50, $Ik02_drecei,  true, 'text', 3, "class='input-maior' data='k02_drecei'");
                ?>
              </td>
            </tr>
            <tr>
              <td class='tamanho-primeira-col' nowrap>
                <label for="codreceita_duplicidade" class="bold">
                  <a id="receita_duplicidade"><?php echo $Li06_receitaduplicidade;?></a>
                </label>
              </td>
              <td colspan='3'>
                <?php
                db_input('codreceita_duplicidade',10,$Ik81_receita,true,'text',2,' data="k02_codigo"');
                db_input('drecei_duplicidade' ,50,$Ik02_drecei,true,'text',3,"class='input-maior' data='k02_drecei'");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>
      <input name="salvar" type="button" id="confirma" value="Salvar"   onClick="salvarreceita()">
    </form>
  </div>

  <?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
  <script type="text/javascript">

    var sRPC                = 'inf4_infracaotransito.RPC.php';
    var oPrincipal          = $('codreceita_principal');
    var oDuplicidade        = $('codreceita_duplicidade');
    var oSelectNivel        = $('i06_nivel');
    var oReceitaPrincipal   = $('codreceita_principal');
    var oDescRecPrincipal   = $('drecei_principal');
    var oReceitaDuplicidade = $('codreceita_duplicidade');
    var oDescRecDuplicidade = $('drecei_duplicidade');
    var oConta              = $('k13_conta');
    var oDescricaoConta     = $('k13_descr');
    var iExercicio          = '<?php echo db_getsession("DB_anousu");?>'

    var oLookupPrincipal = new DBLookUp($('receita_principal'), oPrincipal, $('drecei_principal'), {
      "sArquivo"      : "func_tabrec_recurso.php",
      "sObjetoLookUp" : "db_iframe_tabrec",
      "sLabel"        : "Pesquisar"
    });

    var oLookupDuplicidade = new DBLookUp($('receita_duplicidade'), oDuplicidade, $('drecei_duplicidade'), {
      "sArquivo"      : "func_tabrec_recurso.php",
      "sObjetoLookUp" : "db_iframe_tabrec",
      "sLabel"        : "Pesquisar"
    });

    var oLookupSaltes = new DBLookUp($('lblSaltes'), oConta, oDescricaoConta, {
      "sArquivo"              : "func_saltesrecurso.php",
      "sObjetoLookUp"         : "db_iframe_saltes",
      "sLabel"                : "Pesquisar Contas",
      "aParametrosAdicionais" : ["recurso=0"]
    });

    oLookupSaltes.setCallBack('onChange', function() {

      var iConta     = arguments[1][0];
      var sDescricao = arguments[1][1];
      var sRecurso   = arguments[1][2];
      var lErro      = arguments[1][3];

      oDescricaoConta.value = sDescricao;
      if (lErro) {
        oConta.value = '';
      }
    });

    function salvarreceita(){

      if(validar() == false){

        return false;
      };

      var oParametros = {

        exec                : 'salvarReceitaInfracao',
        nivel               : $F('i06_nivel'),
        receita_principal   : $F('codreceita_principal'),
        receita_duplicidade : $F('codreceita_duplicidade'),
        conta               : oConta.value
      };

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro){

        alert(oRetorno.message);
        if (lErro) {
          return false;
        }
        location.reload();
      }).execute();
    }

    function validar(){

      if(document.getElementById('i06_nivel').value == 0){

        alert('O campo Nível é de preenchimento obrigatório.');
        return false;
      }

      if(document.getElementById('k13_conta').value == ''){

        alert('O campo Código da Conta é de preenchimento obrigatório.');
        return false;
      }

      if(document.getElementById('codreceita_principal').value == false){

        alert('O campo Código da Receita Principal é de preenchimento obrigatório.');
        return false;
      }

      if(document.getElementById('codreceita_duplicidade').value == false){

        alert('O campo Código da Receita de Pagamentos em Duplicidade é de preenchimento obrigatório.');
        return false;
      }

      return true;
    }

    function resetaCampos() {

      location.href='inf4_receitasinfracaotransito004.php';
    }

    function pesquisaNivel(){

      if(document.getElementById('i06_nivel').value == 0){

        return false;
      }
      var oParametros = {

        exec   : 'pesquisarReceitaInfracao',
        nivel  : $F('i06_nivel'),
        anousu : iExercicio
      };

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro){

        if (lErro) {
          alert(oRetorno.message);
          return false;
        }
        carregaCampos(oRetorno);
      }).execute();
    }

    function carregaCampos(oDados) {

      var aReceitasInfracao   = oDados.receitas_infracao;

      oReceitaPrincipal.value   = '';
      oDescRecPrincipal.value   = '';
      oReceitaDuplicidade.value = '';
      oDescRecDuplicidade.value = '';

      for (var i = 0; i < aReceitasInfracao.length; i++) {

        if(aReceitasInfracao[i].nivel == oSelectNivel.selectedIndex) {

          oReceitaPrincipal.value   = aReceitasInfracao[i].receitaPrincipal.k02_codigo;
          oDescRecPrincipal.value   = aReceitasInfracao[i].receitaPrincipal.k02_drecei;
          oReceitaDuplicidade.value = aReceitasInfracao[i].receitaDuplicidade.k02_codigo;
          oDescRecDuplicidade.value = aReceitasInfracao[i].receitaDuplicidade.k02_drecei;
          oConta.value              = aReceitasInfracao[i].conta;
          oDescricaoConta.value     = aReceitasInfracao[i].conta_descricao;

          break;
        }
      }
    }

    function pesquisaNiveis(){

      var oParametros = {
        exec   : 'verificaNivelReceitaInfracao',
        anousu : iExercicio
      };

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro){

        if (lErro) {
          alert(oRetorno.message);
          return false;
        }
        exibeSemConfiguracao(oRetorno);
      }).execute();
    }

    function exibeSemConfiguracao(oDados){

      document.getElementById('semconfiguracao').innerHTML = "";
      if(oDados.Nivel) {

        if (oDados.Nivel.length > 0) {
          var sMensagem = '* É necessário configurar o(s) seguinte(s) nível(is): ';
          for (var i = 0; i < oDados.Nivel.length; i++) {
            sMensagem += 'Nível ' + oDados.Nivel[i];

            if(i !=  oDados.Nivel.length -1 ){
              sMensagem += ', ';
            } else {
              sMensagem += '.';
            }
          }
          document.getElementById('semconfiguracao').innerHTML = sMensagem;
        }

        if(oDados.Conta) {

          $('k13_conta').value = oDados.Conta;
          $('k13_descr').value = oDados.ContaDescricao;
        }
      }
      return false;
    }

  </script>
</html>
