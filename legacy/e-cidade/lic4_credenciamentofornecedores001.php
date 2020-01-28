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

$oRotulo = new rotulocampo();
$oRotulo->label('l20_codigo');
$oRotulo->label('z01_numcgm');
$oRotulo->label('z01_nome');

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
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>

  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>

  <script type="text/javascript">


    var sRPC      = "lic4_credenciamentofornecedor.RPC.php";
    var sFonteMsg = "patrimonial.licitacao.lic4_credenciamentofornecedores001.";
    var oDados    = {
      iLicitacao            : null,
      iOrcamento            : null,
      oCollectionFornecedor : new Collection().setId("iCgm"),
      oCollectionItens      : new Collection().setId("iLicLicitem")
    };

    /**
     * Carrega os fornecedores da licitação
     */
    function buscarDadosLicitacao() {

      oDados.iLicitacao = $F('l20_codigo');
      var oParametros   = {
        exec       : 'buscarDadosLicitacao',
        iLicitacao : oDados.iLicitacao
      };
      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

        if ( lErro ) {

          alert( oRetorno.sMessage );
          return false;
        }

        oDados.oCollectionFornecedor.clear();
        oDados.oCollectionItens.clear();
        clearFornecedorItem();

        oDados.iOrcamento = oRetorno.iOrcamento;
        for (var oFornecedor of oRetorno.aFornecedores ) {

          oDados.oCollectionFornecedor.add(oFornecedor);
          atualizaFornecedoresItens(oFornecedor.sNome, oFornecedor.iCgm);
        }

        for (var oItem of oRetorno.aItensLicitacao) {
          oDados.oCollectionItens.add(oItem);
        }

        oGridFornecedor.reload();
        oGridItensFornecedor.reload();
      }).setMessage( _M(sFonteMsg + "buscando_fornecedores") ) .execute();
    }

    function atualizaFornecedoresItens(sNome, iCgm) {

      var oOption = new Option(sNome, iCgm);

      if(verificaFornecedorOpcao(iCgm)) {
        $('cboFornecedores').add( oOption );
      }
    }

    function verificaFornecedorOpcao(iCgm){

      for(i = $('cboFornecedores').options.length - 1 ; i >= 0 ; i--) {
        if($('cboFornecedores').options[i].value == iCgm){
          return false;
        }
      }

      return true;
    }

    function clearFornecedorItem() {

      var iOptions =  $('cboFornecedores').options.length -1;
      for (var i = iOptions; i > 0; i-- ) {
        $('cboFornecedores').options.remove(i);
      }
    }


    function removerFornecedorItem(iCgm) {

      var iOptions =  $('cboFornecedores').options.length;
      for (var i = 0; i < iOptions; i++ ) {

        if ( $('cboFornecedores').options[i].value == iCgm ) {
          $('cboFornecedores').options.remove(i);
          break;
        }
      }
    }

  </script>
</head>
<body class='body-default'>
  <?php db_menu(); ?>

  <div id="ctnAbas"></div>

   <div id='abaFornecedores'>
      <?php
        require 'forms/db_frmFornecedoresCredenciados.php';
      ?>
   </div>
   <div id='abaItemFornecedor' >
     <?php
        require 'forms/db_frmFornecedoresCredenciadosItens.php';
      ?>
   </div>

</body>

<script type="text/javascript">

var oAbas = new DBAbas($('ctnAbas'));
var oAba1 = oAbas.adicionarAba('Fornecedores', $('abaFornecedores'));
var oAba2 = oAbas.adicionarAba('Itens', $('abaItemFornecedor'));

</script>
</html>