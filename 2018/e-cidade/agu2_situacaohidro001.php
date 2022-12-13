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
    <script>
    
      function js_emite() {
          
        var sit    = 1;
        var ano    = document.form1.iAno.value;
        var mes    = document.form1.iMes.value;
        var filtro = document.form1.filtro.value;
        
        today     = new Date();
        
        var todayYear    = today.getFullYear();
        var todayMonth   = today.getMonth() + 1;
        var nomeMesInput = retornaMesNome(mes);
        var nomeMes      = retornaMesNome(todayMonth);
      
        sit += "&filtro=" + filtro;
       
        if (todayMonth < 10) { 
          todayMonth = "0" + todayMonth;
        }
        
        listabairro = "";
        sVirgula    = "";
       
        if (document.getElementById('bairro').length > 0) {
          
          for (x = 0; x < document.getElementById('bairro').length; x++) {
              
            listabairro += sVirgula + document.getElementById('bairro').options[x].value;
            sVirgula     = ",";
          }
         
          sit += "&listabairro=" + listabairro;
        }

        sit += "&ordenacao=" + document.form1.ordenacao.value;


        var iLinhas = oGridSituacao.aRows.length;

        aEnviaSituacao = '';
        aSeparador     = '';
        
        for (var i = 0; i < iLinhas; i++) {

          var iQuantidadeMeses = $F("meses" + i);

          if (iQuantidadeMeses > 0) {

            var iCodigoSituacao  = oGridSituacao.aRows[i].aCells[0].getValue();

            aEnviaSituacao += aSeparador + iCodigoSituacao + ',' + iQuantidadeMeses;
            aSeparador      = ':';
          } 
        }

        sit += '&situacaomeses=' + aEnviaSituacao;
        
        if (document.getElementById('anomes').style.visibility == "visible") {

          if (ano != '') {

            if (ano.length == 4) {

              if (mes != '') {
                  
                if (mes.length == 2) {
                    
                  if ((mes >= 1) && (mes <= 12)) {
                    sit += "&ano=" + ano + "&mes=" + mes;
                  } else {
                      
                    alert('Número do mês inválido. De 01(Janeiro) até 12(Dezembro). Ex.: ' +
                           todayMonth + ' (' + nomeMes + ')');
                    document.form1.iMes.focus();
                    return false;
                  }
                } else {
                    
                  alert('Formato do mês inválido. Ex.: 0' + mes + ' (' + nomeMesInput + ')');
                  document.form1.iMes.focus();
                  return false;
                }
              } else {
                  
                alert('O mês deve ser informado.');
                document.form1.iMes.focus();
                return false;
              }
            } else {
                
              alert('Formato do ano inválido. Ex.: ' + todayYear);
              document.form1.iAno.focus();
              return false;
            }
          } else {
            
            alert('O ano deve ser informado.');
            document.form1.iAno.focus();
            return false;
          }
        }
        
        jan = window.open('agu2_situacaohidro002.php?situacao=' + sit, '', 'width=' + (screen.availWidth - 5) + 
                           ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
      }
      
      function retornaMesNome(numero) {

        var numeroMes = parseInt(numero);
        var fNomeMes  = "";
      
        switch(numeroMes) {
        
          case  1 : fNomeMes = 'Janeiro';   break;
          case  2 : fNomeMes = 'Fevereiro'; break;
          case  3 : fNomeMes = 'Março';     break;
          case  4 : fNomeMes = 'Abril';     break;
          case  5 : fNomeMes = 'Maio';      break;
          case  6 : fNomeMes = 'Junho';     break;
          case  7 : fNomeMes = 'Julho';     break;
          case  8 : fNomeMes = 'Agosto';    break;
          case  9 : fNomeMes = 'Setembro';  break;
          case 10 : fNomeMes = 'Outubro';   break; 
          case 11 : fNomeMes = 'Novembro';  break;
          case 12 : fNomeMes = 'Dezembro';  break;
        }  

        return fNomeMes;
      }
      
      function hidden(selectValue) {
          
        if (selectValue == 1) {
            
          document.getElementById('anomes').style.visibility = "hidden";
        } else if (selectValue == 2) {
            
          document.getElementById('anomes').style.visibility = "visible";
        }
      }
    </script>
<?php
  db_app::load("scripts.js, strings.js,arrays.js, prototype.js,datagrid.widget.js");
  db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">
    <div class='container'>
      <form name="form1" method="post" action="">
        <fieldset>
          <legend class="bold">Relatório de Situação de Hidrômetros</legend>
          <table align="center">
            <tr>
              <td align="right">
                <strong>Listar:</strong>
              </td>
              <td>
                <?php
                  $iListar = 1;
                  $x = array("1" => "Última Situação",
                             "2" => "Informar Ano/Mês");
                  db_select("iListar", $x, true, 1, "onchange=\"hidden(this.value)\"");
                ?>
              </td>
            </tr>
            <tr>
              <td align="right">
                <strong>Filtro:</strong>
              </td>
              <td>
                <?php
                  $a = array('1' => 'Todas',
                             '2' => 'Matrículas COM situação de corte',
                             '3' => 'Matrículas SEM situação de corte');
                  db_select('filtro', $a, true, 1); 
                ?>
              </td>
            </tr>
            <tr>
              <td align="right">
                <strong>Ordenação:</strong>
              </td>
              <td>
                <?php
                  $aOrdenacao = array('1' => 'Matricula',
                                      '2' => 'Bairro/Logradouro/Numero',
                                      '3' => 'Ano/Mês',
                                      '4' => 'Leitura');
                  db_select('ordenacao', $aOrdenacao, true, 1); 
                ?>
              </td>
            </tr>
            <tr id="anomes" style="visibility: hidden; height: 1px;">
              <td>
                <strong>Ano/Mês (AAAA/MM)</strong>
              </td>
              <?php
                $iAno = date("Y",db_getsession("DB_datausu"));
                $iMes = date("m",db_getsession("DB_datausu"));
              ?>
              <td>
                <?php db_input("iAno", 5, $Ix21_exerc, true, "text", 1, "", "", "", "", 4); ?> 
                /
                <?php db_input("iMes", 2, $Ix21_mes, true, "text", 1, "", "", "", "", 2); ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend class="bold">Situação dos Hidrometros / Quantidade Meses</legend>
                  <div id="boxDataGrid"></div>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <table>
                  <tr>
                    <td>
                      <?php
                        $arqAux->cabecalho      = '<strong>Bairros</strong>';
                        $arqAux->codigo         = 'j13_codi';
                        $arqAux->descr          = 'j13_descr';
                        $arqAux->nomeobjeto     = 'bairro';
                        $arqAux->funcao_js      = 'js_mostra_bairro';
                        $arqAux->funcao_js_hide = 'js_mostra_bairro1';
                        $arqAux->func_arquivo   = 'func_bairro.php';
                        $arqAux->nomeiframe     = 'db_iframe_bairro';
                        $arqAux->nome_botao     = 'db_lanca_bairro';
                        $arqAux->db_opcao       = 2;
                        $arqAux->tipo           = 2;
                        $arqAux->top            = 0;
                        $arqAux->linhas         = 4;
                        $arqAux->vwidth         = 450;
                        $arqAux->funcao_gera_formulario();
                      ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </fieldset>
        <table align="center">
          <tr>
            <td>
              <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
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
  var oGridSituacao;

  (function() {
    
    oGridSituacao              = new DBGrid('gridSituacao')
    oGridSituacao.nameInstance = "oGridSituacao";
    
    oGridSituacao.setCellWidth(new Array('20%'   , '60%'     , '20%'));
    oGridSituacao.setCellAlign(new Array('left'  , 'left'    , 'center'));
    oGridSituacao.setHeader   (new Array('Código','Descrição', 'Meses'));

    oGridSituacao.show($('boxDataGrid'));

    oGridSituacao.clearAll(true);
    
    js_buscaSituacoes();

  })();

  function js_buscaSituacoes() {
    
    var sUrl   = 'agu4_leituras.RPC.php';
    var sQuery = 'sMethod=consultaSituacoesHidrometro';
    
    var oAjax  = new Ajax.Request(sUrl,
                                  { method     : 'post', 
                                    parameters : sQuery, 
                                    onComplete : js_retornoSituacoes });
    
  }

  function js_retornoSituacoes(oSituacoes) {

    oGridSituacao.clearAll(true);
    
    var aRetorno = eval("(" + oSituacoes.responseText + ")");
    
    if (aRetorno.aSituacoes.length > 0) {
      
      for (var iIndiceSituacao = 0; iIndiceSituacao < aRetorno.aSituacoes.length; iIndiceSituacao++) {

        oGridSituacao.addRow([aRetorno.aSituacoes[iIndiceSituacao].codigo,
                              aRetorno.aSituacoes[iIndiceSituacao].descricao,
                              "<input type='text' size='5' id='meses" + iIndiceSituacao
                                 + "' name='meses" + iIndiceSituacao + "' value='0'>"]);
      }
    }
    oGridSituacao.renderRows();
  }
  
</script>