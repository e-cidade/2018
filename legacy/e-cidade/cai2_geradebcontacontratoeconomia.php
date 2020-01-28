<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_sql.php"));
require_once(modification("dbforms/db_funcoes.php"));

$iAno = db_getsession("DB_anousu");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
  <script src="scripts/scripts.js" type="text/javascript"></script>
  <script src="scripts/prototype.js" type="text/javascript"></script>
  <script src="scripts/AjaxRequest.js" type="text/javascript"></script>
</head>
<body>
  <div class="container">
    <form>
      <fieldset>
        <legend>Geração do Arquivo de Banco</legend>

        <table class="form-container">
            <tr>
              <td>
                <label for="exercicioVencimento">Exercício do Vencimento:</label>
              </td>
              <td>
                <input type="text" id="exercicioVencimento" name="exercicioVencimento" class="field-size1 readonly" readonly="readonly" value="<?php echo $iAno ?>">
              </td>
            </tr>

            <tr>
              <td>
                <label for="mesVencimento">Mês do Vencimento:</label>
              </td>
              <td>
                <select id="mesVencimento">
                  <option value="">Selecione</option>
                  <option value="1">Janeiro</option>
                  <option value="2">Fevereiro</option>
                  <option value="3">Março</option>
                  <option value="4">Abril</option>
                  <option value="5">Maio</option>
                  <option value="6">Junho</option>
                  <option value="7">Julho</option>
                  <option value="8">Agosto</option>
                  <option value="9">Setembro</option>
                  <option value="10">Outubro</option>
                  <option value="11">Novembro</option>
                  <option value="12">Dezembro</option>
                </select>
              </td>
            </tr>

            <tr>
              <td>
                <label for="tipoDebito">Tipo de Débito:</label>
              </td>
              <td>
                <select id="tipoDebito">
                  <option value="">Selecione</option>
                </select>
              </td>
            </tr>

            <tr>
              <td>
                <label for="banco">Banco:</label>
              </td>
              <td>
                <select id="banco">
                  <option value="">Selecione</option>
                </select>
              </td>
            </tr>
        </table>
      </fieldset>

      <input id="processar" type="button" value="Processar">

    </form>
  </div>

<?php db_menu() ?>

<script>

document.observe("dom:loaded", function(){

    new AjaxRequest(
      'cai2_geradebcontacontratoeconomia.RPC.php',
      {
        'exec': 'opcoesSelect',
      },
      function(oRetorno, lErro){

        var oInputSelectTipoDebito = $('tipoDebito');
        for (oTipoDebito of oRetorno.aOpcoesTipoDebito){
          var oOpcaoSelectTipoDebito       = document.createElement('option');
          oOpcaoSelectTipoDebito.value     = oTipoDebito.d66_arretipo;
          oOpcaoSelectTipoDebito.innerHTML = oTipoDebito.k00_descr;
          oInputSelectTipoDebito.appendChild(oOpcaoSelectTipoDebito);
        }

        var oInputSelectBanco = $('banco');
        for (oBanco of oRetorno.aOpcoesBanco){
          var oOpcaoSelectBanco       = document.createElement('option');
          oOpcaoSelectBanco.value     = oBanco.d62_banco;
          oOpcaoSelectBanco.innerHTML = oBanco.nomebco;
          oInputSelectBanco.appendChild(oOpcaoSelectBanco);
        }

      }

    ).execute();

    function validaCampos(){

      if(empty($F('exercicioVencimento'))){
        alert('Campo Exercício do Vencimento é de preenchimento obrigatório.');
        return false;
      }

      if(empty($F('mesVencimento'))){
        alert('Campo Mês do Vencimento é de preenchimento obrigatório.');
        return false;
      }

      if(empty($F('tipoDebito'))){
        alert('Campo Tipo de Débito é de preenchimento obrigatório.');
        return false;
      }

      if(empty($F('banco'))){
        alert('Campo Banco é de preenchimento obrigatório.');
        return false;
      }

      return true;
    }

  function processarArquivo() {

    if(!validaCampos()){
      return false;
    }

    var aParametrosUrl = [
      'iTipoDebito=' + $F('tipoDebito'),
      'iAno='        + $F('exercicioVencimento'),
      'iMes='        + $F('mesVencimento'),
      'iBanco='      + $F('banco')
    ];

    var urlJanela    = 'cai2_geradebcontacontratoeconomiaarquivo.php?' + aParametrosUrl.join('&');
    var tituloJanela = 'Geração do Arquivo de Banco para Débitos em Conta';

    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe', urlJanela, tituloJanela, true, 20);
  }

  $('processar').observe('click', processarArquivo);

});
</script>
</body>
</html>
