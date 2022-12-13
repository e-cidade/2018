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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
$oDaoTipoRecolhimento = new \cl_tiporecolhimento;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>    
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
      fieldset.separator table tr td:FIRST-CHILD {
        width:90px;
        white-space: nowrap;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <fieldset>
        <legend>
          <b>Guias de Recolhimento Pagas</b>
        </legend>
        <table>
          <tr>
            <td>
              <label id='lblUnidadeGestora' for="codigo_unidade">Unidade Gestora:</label>
            </td>
            <td>
              <input type="text" id="codigo_unidade" data='k171_sequencial' onkeyup="js_ValidaCampos(this, 1, 'Unidade Gestora', 0, 1)" class="field-size2">
              <input type="text" id="nome_unidade" data='k171_nome' class="field-size8 readonly" readonly>
            </td>
          </tr>
          <tr>
            <td>
              <label for="tipo_recolhimento" id="lblTipoRecolhimento"><b>Tipo de Recolhimento:</b></label>
            </td>
            <td>
              <input type="text" id="tipo_recolhimento" data='k172_sequencial'onkeyup="js_ValidaCampos(this, 1, 'Tipo de Recolhimento', 0, 1)"  class="field-size2">
              <input type="text" id="nome_recolhimento" data='k172_nome' class="field-size8 readonly" readonly maxlength="100">
            </td>
          </tr>
          <tr>
            <td>
              <label for="especie_ingresso">
                <b>Especie de Ingresso:</b>
              </label>              
            </td>
            <td>
              <select style="width: 100%;" id="especie_ingresso">
                <option value="">Selecione</option>
                <option value="1">Receita</option>
                <option value="2">DDO</option>
                <option value="3">Estorno de Despesa</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="5">
              <fieldset class="separator">
                <legend>
                  Data de Pagamento
                </legend>
                <table>
                  <tr>
                     <td>
                       <label for="data_pagamento_inicial"  class="bold">
                         De:
                       </label>
                     </td>
                     <td>
                       <input type="text" id="data_pagamento_inicial">
                     </td>
                    <td>
                      <label for="data_pagamento_final" class="bold">
                        Até:
                      </label>
                    </td>
                    <td>
                      <input type="text" id="data_pagamento_final">
                    </td>
                  </tr>
                </table>
              </fieldset>              
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="btnEmitir" value="Emitir">
    </div>
  </body>
</html>
<script>
  
  var oUnidadeGestora     = $('codigo_unidade');
  var oNomeUnidadeGestora = $('nome_unidade');
  var oTipoRecolhimento   = $('tipo_recolhimento');
  var oEspecieIngresso    = $('especie_ingresso');
  var oNomeRecolhimento   = $('nome_recolhimento');
  
  var oInputDataInicial = new DBInputDate($('data_pagamento_inicial'));
  var oInputDataFinal   = new DBInputDate($('data_pagamento_final'));
  
  var oLookupTipoRecolhimento = new DBLookUp($('lblTipoRecolhimento'), oTipoRecolhimento, oNomeRecolhimento, {
    "sArquivo"      : "func_tiporecolhimento.php",
    "sObjetoLookUp" : "db_iframe_receita",
    "sLabel"        : "Pesquisar Tipo de Recolhimento"
  });

  var oLookupUnidadeGestora = new DBLookUp($('lblUnidadeGestora'), oUnidadeGestora, oNomeUnidadeGestora, {
    "sArquivo"      : "func_unidadegestora.php",
    "sObjetoLookUp" : "db_iframe_unidade_gestora",
    "sLabel"        : "Pesquisar Unidade Gestora"
  });
  
  $('btnEmitir').observe('click', function(){
    
    if (empty(oUnidadeGestora.value)) {
      
      alert('Informe a Unidade Gestora.');
      return false;      
    }
    var data_inicial = oInputDataInicial.inputElement.value;
    var data_final   = oInputDataFinal.inputElement.value;
    var url = 'cai2_guiaspagasgrm002.php?';
    url += 'unidade_gestora='+oUnidadeGestora.value+'&tipo_recolhimento='+oTipoRecolhimento.value+'&';
    url+= 'data_inicial='+data_inicial+'&data_final='+data_final+'&especie_ingresso='+oEspecieIngresso.value;
    window.open(url, '', 'location=0');
  })
</script>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));