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

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <style type="text/css">

  .form-label  {
    display: inline-block;
    min-width: 83px !important;
    text-align: left;
  }
  </style>
</head>
<body class='body-default'>
  <div class='container'>
    <form name="form1" method="post" action="" class="form-inline">
      <fieldset>
        <legend>Reemissão da Guia de Transferência de Alunos Encerrados</legend>

        <fieldset class="separator" >
          <legend>Informe o Aluno</legend>
          <div class="form-label">
            <label for="ed60_i_codigo" class="sss bold" >
              <a style="width: 83px;" href="#" id='ancoraAluno'>Aluno:</a>
            </label>
          </div>
          <input type='hidden' name='ed60_i_codigo'    id='db_ed60_i_codigo' />
          <input type='hidden' name='ed137_sequencial' id='iCodigoTransferencia' />
          <input type='text'   name='ed47_i_codigo'    id='ed47_i_codigo' class="field-size2"  />
          <input type='text'   name='ed47_v_nome'      id='ed47_v_nome'   class='field-size6 readonly ' />
        </fieldset>
        <fieldset class="separator">

          <legend>Informação para a Guia de Transferência</legend>
          <div class="form-label">
            <label for="cboEmissor" class="sss bold" >Emissor:</label>
          </div>
          <select id="cboEmissor" class='field-size8 '>
            <option value="">Selecione...</option>
          </select>
        </fieldset>

      </fieldset>
      <input type="button" name="imprimir" id="btnImprimir" value="Imprimir" />
    </form>
  </div>
<?php db_menu(); ?>
</body>
<script type="text/javascript">

var oLookUp = new DBLookUp( $('ancoraAluno'), $('ed47_i_codigo'), $('ed47_v_nome'), {
  sArquivo: 'func_transferiralunoencerrado.php',
  sLabel: 'Pesquisa Alunos Transferidos',
  sObjetoLookUp: 'db_iframe_transferiralunoencerrado',
  aCamposAdicionais: ['db_transferencia', 'db_ed60_i_codigo']
});

oLookUp.setCallBack('onClick', function(aCampos) {

  $('iCodigoTransferencia').value = aCampos[2];
  $('db_ed60_i_codigo').value     = aCampos[3];
});

oLookUp.setCallBack('onChange', function(lErro, aCampos) {

  limparCampos();
  if ( !lErro ) {

    $('ed47_v_nome').value          = aCampos[0];
    $('ed47_i_codigo').value        = aCampos[2];
    $('db_ed60_i_codigo').value     = aCampos[3];
    $('iCodigoTransferencia').value = aCampos[4];
  }
});

(function(){

  new AjaxRequest( 'edu4_transferiralunosencerrados.RPC.php', {'exec' : 'buscarEmissor'}, function(oRetorno, lErro) {

    if (lErro) {
      alert(oRetorno.sMessage);
      return;
    }

    aEmissores = oRetorno.aEmissores;

    aEmissores.forEach(function (oEmissor, iIndex) {

      var sOption = oEmissor.funcao + ' - ' + oEmissor.nome;

      if ( !empty(oEmissor.atolegal) ) {
        sOption += ' (' + oEmissor.atolegal + ')';
      }

      $('cboEmissor').add(new Option(sOption, iIndex));
    });

  }).setMessage( "Buscando emissor, aguarde..." ).execute();
})();


$('btnImprimir').addEventListener('click', function () {

  if ( $F('ed47_i_codigo') == '' ) {

    alert('Selecione o aluno.');
    return;
  }

  var sUrl  = 'edu2_guiatransferenciaencerrados002.php';
      sUrl += '?iTransferencia='+$F('iCodigoTransferencia');
      sUrl += '&iMatricula='+$F('db_ed60_i_codigo');

  if ( $F('cboEmissor') != '') {

    var oEmissor          = aEmissores[$F('cboEmissor')];
    sUrl += "&sEmissor="  + btoa(oEmissor.nome);
    sUrl += "&sFuncao="   + btoa(oEmissor.funcao);
    sUrl += "&sAtoLegal=" + btoa(oEmissor.atolegal);
  }

  window.open( sUrl, '', 'scrollbars=1,location=0');
});


function limparCampos() {

  $$('input[type="hidden"]').each( function(oElement) {
    oElement.value = '';
  });
}

</script>
</html>
