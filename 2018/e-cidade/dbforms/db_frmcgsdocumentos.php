<?php
/**
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
?>
<fieldset class="separator">
  <legend>Documentos</legend>
  <div id="ctnGrid"></div>
</fieldset>

<script>

  var oDBViewCadDocumento = new dbViewCadastroDocumento();
  var oCollection         = new Collection();
      oCollection.setId( 'codigoDocumento' );

  var oGridDocumentos = new DatagridCollection( oCollection, 'gridDocumentosCgs' );
      oGridDocumentos.addColumn( 'documento', { 'width': '85%', 'label': 'Documento' });
      oGridDocumentos.addAction('Lançar', 'Lançar o documento', function(event, itemCollection) {

        if( itemCollection.documentoValor == null ) {

          oDBViewCadDocumento.newDocument( itemCollection.codigoDocumento );
          oDBViewCadDocumento.setSaveCallBackFunction(function(iDocumento){ salvarCgsDocumento( iDocumento ) });
        } else {
          oDBViewCadDocumento.loadDocument( itemCollection.documentoValor );
        }
      });

  oGridDocumentos.show( $('ctnGrid') );

  document.addEventListener("DOMContentLoaded", function(event) {
    buscaDocumentosCgs();
  });

  /**
   * Busca todos os documentos do CGS, com os valores do que já tenham sido respondidos
   */
  function buscaDocumentosCgs() {

    var oParametros = { 'sExecucao': 'documentosCgs', 'iCgs': iCgs };
    var oRequisicao = new AjaxRequest(sRpc, oParametros, function( oRetorno, lErro ) {

      oCollection.add(oRetorno.aDocumentos);
      oGridDocumentos.reload();
    });

    oRequisicao.execute();
  }

  /**
   * Salva o vínculo de um documento com o CGS
   * @param iDocumento
   */
  function salvarCgsDocumento( iDocumento ) {

    var oParametros = { 'sExecucao': 'vinculaCgsDocumento', 'iCgs': iCgs, 'iDocumento': iDocumento };
    var oRequisicao = new AjaxRequest(sRpc, oParametros, function( oRetorno, lErro ) {

      if( lErro ) {

        alert( oRetorno.sMessage );
        return;
      }

      buscaDocumentosCgs();
    });

    oRequisicao.execute();
  }
</script>