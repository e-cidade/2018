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
  
  require_once "libs/db_stdlib.php";
  require_once "libs/db_conecta.php";
  require_once "libs/db_sessoes.php";
  require_once "libs/db_usuariosonline.php";
  require_once "dbforms/db_funcoes.php";
  require_once "dbforms/db_classesgenericas.php";
  
  $arqAux = new cl_arquivo_auxiliar;
  
  db_postmemory($HTTP_POST_VARS);
  
  $clrotulo = new rotulocampo;
  $clrotulo->label("x21_exerc");
  $clrotulo->label("x21_mes");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js,arrays.js, prototype.js,datagrid.widget.js");
      db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js");
      db_app::load("estilos.css, grid.style.css");
      
      $dDataInicio = explode('-', date('Y-m-d', time()));
      $dDataFim    = explode('-', date('Y-m-d', time()));
      
      $sDataIniDia = $dDataInicio[2];
      $sDataIniMes = $dDataInicio[1];
      $sDataIniAno = $dDataInicio[0];
      
      $sDataFimDia = $dDataFim[2];
      $sDataFimMes = $dDataFim[1];
      $sDataFimAno = $dDataFim[0];
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">
    <div class='container'>
      <form name="form1" method="post">
        <fieldset>
          <legend class="bold">Alterar Vencimento para os débitos</legend>
          <table align="center">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <strong>Período:</strong>
                <?php db_inputdata('dataini', $sDataIniDia, $sDataIniMes, $sDataIniAno, true, 'text', 1); ?>
                á
                <?php db_inputdata('datafim', $sDataFimDia, $sDataFimMes, $sDataFimAno, true, 'text', 1); ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <br><input type="button" name='verificar' value='Verifica Registros' onclick="js_buscaTiposDebitos()"><br><br>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset style="width: 500px">
                  <legend class="bold">Tipos de débito</legend>
                  <div id="boxDataGrid"></div>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <strong>Novo Vencimento:</strong>
                <?php db_inputdata('novaDataVencimento', '', '', '', true, 'text', 1); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <table align="center">
          <tr>
            <td>
              <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_processaAlteraData();">
            </td>
          </tr>
        </table>
      </form>
      <?php 
        db_menu(db_getsession("DB_id_usuario"),
                db_getsession("DB_modulo"),
                db_getsession("DB_anousu"),
                db_getsession("DB_instit"));
      ?>
    </div>
  </body>
</html>
<script>
  var oGridTiposDebitos;

  (function() {
    
    oGridTiposDebitos              = new DBGrid('gridDebitos')
    oGridTiposDebitos.nameInstance = "oGridDebitos";
    
    oGridTiposDebitos.setCellWidth (new Array('15%'   , '55%', '30%'));
    oGridTiposDebitos.setCellAlign (new Array('center', 'left', 'center'));
    oGridTiposDebitos.setHeader    (new Array('','Descrição', 'Quantidade'));

    oGridTiposDebitos.show($('boxDataGrid'));

    oGridTiposDebitos.clearAll(true);

  })();

  function js_buscaTiposDebitos() {

    js_divCarregando("Aguarde, processando.", "msgBox");
    habilitaCampos(true);
      
    var sDataIni = document.form1.dataini_ano.value + '-' 
                 + document.form1.dataini_mes.value + '-'
                 + document.form1.dataini_dia.value;

    var sDataFim = document.form1.datafim_ano.value + '-' 
                 + document.form1.datafim_mes.value + '-'
                 + document.form1.datafim_dia.value;

    
    var sParametros = '&sDataIni=' + sDataIni + '&sDataFim=' + sDataFim;
    var sUrl        = 'arr4_alteravencimento.RPC.php';
    var sQuery      = 'sMethod=consultaTiposDeDebitos';
    
    var oAjax  = new Ajax.Request(sUrl,
                                  { method     : 'post', 
                                    parameters : sQuery + sParametros, 
                                    onComplete : js_retornoTiposDebito
                                  });
  }

  function js_retornoTiposDebito(oSituacoes) {

    oGridTiposDebitos.clearAll(true);

    js_removeObj("msgBox");
    habilitaCampos(false);
    
    var aRetorno = eval("(" + oSituacoes.responseText + ")");

    if (aRetorno.lErro) {
      alert(aRetorno.sMsg.urlDecode());
      return;
    }
    
    if (aRetorno.aSituacoes.length > 0) {
      
      for (var iIndiceDebitos = 0; iIndiceDebitos < aRetorno.aSituacoes.length; iIndiceDebitos++) {

        iCodigo    = aRetorno.aSituacoes[iIndiceDebitos].codigo;
        sDescricao =  iCodigo + ' - ' + aRetorno.aSituacoes[iIndiceDebitos].descricao;
        
        oGridTiposDebitos.addRow(["<input type='checkbox' size='5' id='debito-" + iCodigo
                                  + "' name='debito-" + iCodigo + "'>",
                                  sDescricao, 
                                  aRetorno.aSituacoes[iIndiceDebitos].contador]
                                );
      }
    }
    oGridTiposDebitos.renderRows();
  }


  function habilitaCampos (fLiberado) {

    var aCampos = document.form1.getElementsByTagName("input");

    for (i = 0; i < aCampos.length; i++) {
      aCampos[i].disabled = fLiberado;
    }
  }


  function js_processaAlteraData() {

    js_divCarregando("Aguarde, alterando data dos registros.", "msgBox");
    habilitaCampos(true);
        
    var sUrl        = 'arr4_alteravencimento.RPC.php';
    var sQuery      = 'sMethod=processaAlteracaoData';

    var oCamposForm = document.form1.getElementsByTagName("input");
    var sParametros = '';
    
    for (var iIndice = 0; iIndice < oCamposForm.length; iIndice++) {
      sParametros += '&' + oCamposForm[iIndice].name + '=' + oCamposForm[iIndice].value;
    }

    var iLinhas  = oGridTiposDebitos.aRows.length;
    sTiposDebito = '&sTiposDebito=';
    sVirgula     = '';
    
    for (var i = 0; i < iLinhas; i++) {

      var oTipoDebito = document.getElementById(oGridTiposDebitos.aRows[i].aCells[0].getId()).firstChild;
      
      if (oTipoDebito.checked) {
        sTiposDebito  += sVirgula + oTipoDebito.name;
        sVirgula = ',';
      }
    }
    
    var oAjax  = new Ajax.Request(sUrl,
                                  { method     : 'post', 
                                    parameters : sQuery + sParametros + sTiposDebito, 
                                    onComplete : js_retornoProcessaAlteraData
                                  });
  }

  function js_retornoProcessaAlteraData(oRetorno) {

    js_removeObj("msgBox");
    habilitaCampos(false);
    
    var aRetorno = eval("(" + oRetorno.responseText + ")");

    alert(aRetorno.sMsg.urlDecode());

    if (aRetorno.lErro == false) {

      oGridTiposDebitos.clearAll(true);
      
      var aCampos = document.form1.getElementsByTagName("input");

      for (i = 0; i < aCampos.length; i++) {

        if (aCampos[i].type == 'text') {
          aCampos[i].value = '';
        }
      }
    }
  }
</script>