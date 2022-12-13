<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

$oRotulo = new rotulocampo();
$oRotulo->label('bi15_acervo');
$oRotulo->label('bi15_codigo');
$oRotulo->label('bi06_titulo');

?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend><b>Assuntos do Acervo</b></legend>
      <table class="form-container">
        <tr>
          <td>
            <label for="bi06_titulo"><?=$Lbi15_acervo?></label>
          </td>
          <td>
            <?php
              db_input('bi15_acervo', 10, $Ibi15_acervo, true, 'hidden', 3);
              db_input('bi06_titulo', 50, $Ibi06_titulo, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="bi30_descricao"><a href="#" id='ancoraAssunto'>Assunto:</a></label>
          </td>
          <td>
            <input type='hidden' name='bi30_sequencial' id='bi30_sequencial' />
            <input type='text'   name='bi30_descricao'  id='bi30_descricao'  class='field-size-max ' />
          </td>
        </tr>
      </table>
    </fieldset>
    <input type='button' name='btnSalvar' id='btnSalvarAssunto' value="Salvar" />
  </form>
</div>

<div class="subcontainer" style="width:600px;">
  <fieldset >
    <legend>Assuntos vinculados ao acervo</legend>
    <div id='ctnGrid'></div>
  </fieldset>
</div>

<script type="text/javascript">

  var oLookUp = new DBLookUp( $('ancoraAssunto'), $('bi30_sequencial'), $('bi30_descricao'), {
    sArquivo: 'func_tipoassunto.php',
    sLabel: 'Pesquisa Assuntos Cadastrados',
    sObjetoLookUp: 'db_iframe_rechumano'
  });

var oCollection  = new Collection().setId("codigo");
var oGridAssunto = new DatagridCollection(oCollection).configure({
  order  : false,
  height : 120
});

oGridAssunto.addColumn("descricao", {
  label : "Descrição",
  align : "left",
  width : "85%"
});

oGridAssunto.addAction("Remover", null, function(oEvento, oItem) {

  var sMsgExcluir  = 'Você esta prestes a remover o assunto: ' + oItem.descricao
      sMsgExcluir += ' do acervo. \nDeseja prosseguir e remover?';

  if ( !confirm(sMsgExcluir) ) {
    return;
  }

  var oParametros = {
    "exec"     : "excluirAssuntoAcervo",
    "iAssunto" : oItem.codigo,
    "iAcervo"  : $F('bi15_acervo')
  };

  new AjaxRequest("bib4_acervo.RPC.php", oParametros, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if (lErro) {
      return;
    }
    oCollection.remove( oItem.codigo );
    oGridAssunto.reload();

  }).execute();
});

oGridAssunto.show($("ctnGrid"));


(function() {

  var oParametros = {
    "exec"     : "buscaAssuntoAcervo",
    "iAcervo"  : $F('bi15_acervo')
  };

  new AjaxRequest("bib4_acervo.RPC.php", oParametros, function(oRetorno, lErro) {

    if (lErro) {
      alert(oRetorno.sMessage);
      return;
    }

    for ( var oAssunto of oRetorno.aAssuntos ) {
      oCollection.add(oAssunto);
    }
    oGridAssunto.reload();

  }).setMessage( "Buscando assuntos cadastrados para o acervo, aguarde..." ).execute();
})();

function validarDadosObrigatorios() {

  if ( $F('bi15_acervo') == '') {

    alert('Selecione o Acervo.');
    return false;
  }

  if ( $F('bi30_sequencial') == '') {

    alert('Selecione o Assunto.');
    return false;
  }

  return true;
}

$('btnSalvarAssunto').addEventListener('click', function(){

  if ( !validarDadosObrigatorios() ) {
    return;
  }

  var oParametros = {
    "exec"     : "adicionarAssunto",
    "iAcervo"  : $F('bi15_acervo'),
    "sAssunto" : $F('bi30_descricao')
  };

  new AjaxRequest("bib4_acervo.RPC.php", oParametros, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if (lErro) {
      alert(oRetorno.sMessage);
      return;
    }

    oCollection.add({'codigo': oRetorno.iCodigoAssuntoAdicionado, 'descricao': $F('bi30_descricao')});
    oGridAssunto.reload();
    $('bi30_descricao').value  = '';
    $('bi30_sequencial').value = '';

  }).setMessage( "Adicionando assunto ao acervo, aguarde..." ).execute();
});

</script>