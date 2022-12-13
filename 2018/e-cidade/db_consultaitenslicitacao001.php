<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicitem_classe.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitaitemlog_classe.php");
require_once("model/licitacao.model.php");
require_once("model/licitacao/SituacaoLicitacao.model.php");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body style="margin-top: 5px; background-color: #CCCCCC;">
<center>
  <fieldset>
    <legend><b>Itens da Licitação</b></legend>
    <div id="ctnGridItens"></div>
  </fieldset>
</center>
</body>
</html>

<script>
  var aHeaders = ["Código"
                  ,"Qtd. Solicitada"
                  ,"Vlr. Unitário"
                  ,"Unidade"
                  ,"Material"
                  ,"Fornecedor"
                  ,"Resumo do Item"
                  ,"Observações"];

  var aCellAlign = ["center"
                   ,"center"
                   ,"right"
                   ,"center"
                   ,"left"
                   ,"left"
                   ,"left"
                   ,"left"];

  var aCellWidth = ["5%"
                   ,"8%"
                   ,"8%"
                   ,"8%"
                   ,"24%"
                   ,"22%"
                   ,"10%"
                   ,"10%"];

  var oGridItens = new DBGrid("oGridItens");
  oGridItens.sNameInstance = "oGridItens";
  oGridItens.setHeader(aHeaders);
  oGridItens.setCellAlign(aCellAlign);
  oGridItens.setCellWidth(aCellWidth);
  oGridItens.show($('ctnGridItens'));

  oGridItens.setStatus("Coloque o cursor sob a linha para obter mais informações.");

  var oGet = js_urlToObject();
  var oParametro = {"exec" : "getItensConsultaLicitacao", "iCodigoLicitacao" : oGet.l20_codigo};

  js_divCarregando("Aguarde, carregando itens da licitação", "msgBox");
  new Ajax.Request("lic4_licitacao.RPC.php",
                   {method: 'post',
                    asynchronous: false,
                    parameters: 'json='+Object.toJSON(oParametro),
                    onComplete: completarGrid
                   });

  function completarGrid(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    oGridItens.clearAll(true);
    if (oRetorno.aItens.length == 0) {
      alert("Nenhum item encontrado para a licitação "+oGet.l20_codigo+".");
    }

    /**
     * Adicionamos os itens na grid
     */
    oRetorno.aItens.each(function (oItem, iIndice) {

      var aLinha = [oItem.iCodigo,
                    oItem.iQuantidade,
                    oItem.nValorUnitario,
                    oItem.sUnidadeDeMedida.urlDecode(),
                    oItem.sDescricaoMaterial.urlDecode().substr(0, 35),
                    oItem.sFornecedor.urlDecode().substr(0, 35),
                    oItem.sResumo.urlDecode().substr(0, 35),
                    oItem.sObservacao.urlDecode().substr(0, 35)];
      oGridItens.addRow(aLinha);
    });
    oGridItens.renderRows();

    /**
     * Adicionamos o texto completo quando o usuário passar o mouse por cima da linha
     */
    oRetorno.aItens.each(function (oItem, iIndice) {

      oGridItens.setHint(iIndice, 4, oItem.sDescricaoMaterial.urlDecode());
      oGridItens.setHint(iIndice, 5, oItem.sFornecedor.urlDecode());
      if (oItem.sResumo.trim() != "") {
        oGridItens.setHint(iIndice, 6, oItem.sResumo.urlDecode());
      }
      if (oItem.sObservacao.trim() != "") {
        oGridItens.setHint(iIndice, 7, oItem.sObservacao.urlDecode());
      }

    });
  }
</script>