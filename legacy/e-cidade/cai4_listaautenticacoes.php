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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_GET);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, prototype.js, strings.js ");
    db_app::load("estilos.css, grid.style.css");
    db_app::load("widgets/windowAux.widget.js");
    db_app::load("dbmessageBoard.widget.js");
  ?>
  <link href="conciliacao.css" rel="stylesheet" type="text/css">
  <style></style>
  <script>

    parent.$('salvar').disabled  = true;
    parent.$('desproc').disabled = true;
    parent.$('proximo').disabled = true;
    parent.$("load_autenticacao").value = "false";

    function js_escondeLinha(id){
      document.getElementById("tabLinha"+id).style.display = "none";
    }

    function js_removeLinha(id){
      linha = document.getElementById('tabLinha'+id);
      linha.parentNode.removeChild(linha);
    }

    function js_mostraDetalhes(msg,evt){
      js_msgDetalhes(msg,evt.pageX,evt.pageY);
      return false;
    }

    function js_soma(val,obj,operador){

      if(typeof(obj.value) == 'string' && obj.value == '' ){
        var valantes = 0.00;
      } else {
        var valantes = obj.value;
      }

      var valatual = val.replace(/\./gi,"");
      valatual = valatual.replace(",",".");

      valantes = new Number(valantes);
      valatual = new Number(valatual);
      eval ('obj.value = new String( ( valantes '+operador+' valatual ).toFixed(2) ) ');

    }

    function js_somaAutenticacoes(obj,id){
      linha = document.getElementById('tabLinha'+id);
      var mostraMensagem = obj.checked;
      if (obj.checked){
        linha.className = 'selecionado';
        js_soma( document.getElementById('valdeb'+id).innerHTML, parent.document.getElementById('totalautent'),'-' );
        js_soma( document.getElementById('valcred'+id).innerHTML, parent.document.getElementById('totalautent'),'+' );
      }else{
        eval('var objJSON = '+document.getElementById('objJSON'+id).value);
        linha.className = objJSON.classe;
        js_soma( document.getElementById('valdeb'+id).innerHTML, parent.document.getElementById('totalautent'),'+' );
        js_soma( document.getElementById('valcred'+id).innerHTML, parent.document.getElementById('totalautent'),'-' );
      }

      parent.js_comparaValores(mostraMensagem);
    }

    function js_msgDetalhes(msg,X,Y){

       var expReg = /\\n\\n/gm;
       var camada = document.createElement("DIV");
       var tabela = '';

       camada.setAttribute("id",'msgDetalhe');
       camada.setAttribute("align","center");
       camada.style.backgroundColor      = "#FFFFCC";
       camada.style.layerBackgroundColor = "black";
       camada.style.position             = "absolute";

       camada.style.left = X+'px';
       camada.style.top  = Y+'px';

       camada.style.zIndex = "1000";
       camada.style.visibility = 'visible';
       camada.style.width  = "400px";

       tabela   = ''+ '<table width=100% height=100% class=detalhe>'+
                              '  <tr> '+
                              '    <td colspan=2 style=background-color:#000099> '+
                              '      <center><b><font color=#FFFFFF> Detalhes </font></b></center> '+
                              '    </td> '+
                              '  </tr> ';
       arrLinhas = msg.split('#');
       /* lancando os valores na tabela */
       for (i = 0; i < arrLinhas.length; i++){
         arrColunas = arrLinhas[i].split('-');
         tabela = tabela+' <tr> <td nowrap width=30%> <b> '+arrColunas[0]+ ' : </b> </td> <td width=70%> '+undoTagString(arrColunas[1])+'</td> </tr>';
       }
       tabela = tabela+ ' <tr> '+
                        '   <td colspan=2 align=center > '+
                        '     <input type=button value=Fechar style="border:1px solid" onclick="document.body.removeChild(document.getElementById(\'msgDetalhe\'));"> '+
                        '   </td> '+
                        ' </tr> ';
       camada.innerHTML = tabela;
       document.body.appendChild(camada);
    }


    function js_processaRequest(data,conta){

      js_divCarregando('Aguarde processando...','msgBoxAutent');

      parent.$("load_autenticacao").value = "false";

      var concilia  = parent.document.form1.concilia.value;
      var url       = 'cai4_carregadadosautent.php';
      var parametro = 'data='+data+'&conta='+conta+'&concilia='+concilia;
      var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:js_loadTable});

    }

    function js_loadTable(resposta){

      var retorno = resposta.responseText.split('|||');
      js_removeObj('msgBoxAutent');

      document.getElementById('tabelaAutenticacoes').innerHTML = '';

      eval('var objJ = '+retorno[1]+';');
      if (retorno[0].replace('\n','') != '1' || objJ.length == 0) {

        $('divloading_autenticacao').innerHTML = ' <b> Nenhum registro para a data selecionada.  </b>';

        parent.$("load_autenticacao").value = "true";
        parent.js_habilitaSalvar();

        return false;
      }


      /* cabecalho da tabela */
      tabela = "  <table class=grid width='100%' id='tabelaAutent'> "+
               "    <tr id='cabecalho' >"+
               "      <td class='cabe' align='center' nowrap > <a href='' onClick='return js_marcarTodos();'><b> M </b> </a> </td> "+
               "      <td class='cabe' align='center' nowrap > <b> Caixa        </b></td> "+
               "      <td class='cabe' align='center' nowrap > <b> Detalhes     </b></td> "+
               "      <td class='cabe' align='center' nowrap > <b> Data         </b></td> "+
               "      <td class='cabe' align='center' nowrap > <b> Autent.      </b></td> "+
               "      <td class='cabe' align='center' nowrap > <b> Val. Debito  </b></td> "+
               "      <td class='cabe' align='center' nowrap > <b> Val. Credito </b></td> "+
               "      <td class='cabe' align='center' nowrap > <b> Cheque       </b></td> "+
               "      <td class='cabe' align='center' nowrap > <b> Credor       </b></td> "+
               "      <td class='cabe' align='center' nowrap > <b> J            </b></td> "+
               "    </tr> ";

      for (i = 0; i < objJ.length; i++) {

        var iCaixaLinha = null;
        var iAutenticacao= null;
        var checado      = '';
        var disabled     = '';

        if (objJ[i].classe == 'conciliado' || ( (objJ[i].classe == 'pendente' || objJ[i].classe == 'preselecionado') && parent.$('salvar').className == 'bloqueado' ) ) {

          if (objJ[i].classe != 'pendente' || objJ[i].classe != 'preselecionado') {
            checado       = 'checked';
          }
          disabled = 'disabled';

        } else {

          if ( objJ[i].classe == 'pendente' ) {
            iCaixaLinha  = objJ[i].caixa;
            iAutenticacao = objJ[i].autent;
          }

        }

        /**
         * Data atual é menor que a data da ultima conciliacao
         * - desativa os checkbox
         */
        if ( parent.lDataMenorUltimaConciliacao ) {
          disabled = 'disabled';
        }

        tabela = tabela+
        " <tr id='tabLinha"+i+"' class='"+objJ[i].classe+"' onDblClick='parent.js_desprocessarItens("+objJ[i].itemconciliacao+",\"autent\");'> "+
        "   <td nowrap id='chk"+i+"'     align='center' > <input type='hidden' name='objJSON"+i+"' id='objJSON"+i+"' value='"+JSON.stringify(objJ[i])+"' > " +
        "                                                 <input type='checkbox' name='marcado"+i+"'  id='marcado"+i+"' "+disabled+" "+checado+" onClick='js_somaAutenticacoes(this,"+i+");'> </td> "+
        "   <td nowrap id='caixa"+i+"'   align='center' > "+objJ[i].caixa+"                                                                      </td> "+
        "   <td nowrap id='mi"+i+"'      align='center' > <a href='' onClick='return js_mostraDetalhes(\""+objJ[i].detalhe+"\",event);'> MI </a> </td> "+
        "   <td nowrap id='data"+i+"'    align='center' > "+objJ[i].data+"                                                                       </td> "+
        "   <td nowrap id='autent"+i+"'  align='center' > "+objJ[i].autent+"                                                                     </td> "+
        "   <td nowrap id='valdeb"+i+"'  align='right'  > "+objJ[i].valorDebito+"                                                                </td> "+
        "   <td nowrap id='valcred"+i+"' align='right'  > "+objJ[i].valorCredito+"                                                               </td> "+
        "   <td nowrap id='cheque"+i+"'  align='center' > "+objJ[i].numeroCheque+"                                                               </td> "+
        "   <td nowrap id='credor"+i+"'  align='left'   > "+objJ[i].credor.urlDecode()+"                                                         </td> "+
        "   <td nowrap id='hist"+i+"'    align='left'   >";
        if ( objJ[i].classe == 'pendente' || objJ[i].classe == 'normal' ) {
          tabela+="     <input type='button' title='Justiticativa' name='justificativa' value='J' "+
          "             onclick='js_justificativaAutenticacao(\"tabLinha"+i+"\", \"objJSON"+i+"\", "+ iCaixaLinha +", " + iAutenticacao + ");' /> ";
        }
        tabela+="   </td>"+
        " </tr>  ";
      }

      tabela = tabela+ "</table>";
      document.getElementById('tabelaAutenticacoes').innerHTML = tabela;

      $('divloading_autenticacao').innerHTML = '';

      parent.document.form1.totalautent.value = '0.00';

      parent.$("load_autenticacao").value = "true";
      parent.js_habilitaSalvar();

    }


    /**
     * Abre uma window com um campo textarea para digitar uma justificativa para a autenticacao
     */
    function js_justificativaAutenticacao(sIdLinha, sIdJsonElemento, iCaixaLinha, iAutenticacao ) {

      var oIdJsonElemento = $(sIdJsonElemento);
//      var oJsonEstrutura  = eval('('+oIdJsonElemento.value+')');
      var oJsonEstrutura  = JSON.parse(oIdJsonElemento.value);

      var sConteudoJanela  = '<div id="ctnWindowAux">';
      sConteudoJanela     += '<div id="containerMessageBoard"></div>';
      sConteudoJanela     += '<fieldset><legend><strong>Justificativa</strong></legend>';
      sConteudoJanela     += '<textarea id="ctnrTextArea" rows="5" cols="60" ></textarea>';
      sConteudoJanela     += '</fieldset>';
      sConteudoJanela     += '<center>';
      sConteudoJanela     += '<input type="button" name="salvar" value="Salvar" ';
      sConteudoJanela     += '       onclick="js_salvarJustificativa('+sIdJsonElemento+', \'ctnrTextArea\', ' + iCaixaLinha + ', ' + iAutenticacao + ');" />';
      sConteudoJanela     += '</center>';
      sConteudoJanela     += '</div>';

      /**
       * Verifica se a janela do Window Aux ja esta aberta. Se sim destroy a janela
       */
      if ($('ctnJustificativa')) {
        oWindowAux.destroy();
      }

      oWindowAux = new windowAux('ctnJustificativa',
                                     'Justificativa',
                                     500, 250);
      oWindowAux.setContent(sConteudoJanela);
      oWindowAux.setShutDownFunction(function() {
        oWindowAux.destroy();
      });

      var sConteudoMessageBoardJanela  = 'Escreva uma justificativa para a Autenticacao.';
      var oMessageBoard = new DBMessageBoard('oMessageBoard',
                                             'Justificativa',
                                             sConteudoMessageBoardJanela+'\nDocumento: ' +oJsonEstrutura.autent,
                                             oWindowAux.getContentContainer()
                                            );
      oMessageBoard.show();
      oWindowAux.show(35,50);

      if (oJsonEstrutura.justificativa && oJsonEstrutura.justificativa != "") {

        if (oJsonEstrutura.lendoBanco == 'true') {
          $('ctnrTextArea').innerHTML = undoTagString(oJsonEstrutura.justificativa.urlDecode());
        } else {
          $('ctnrTextArea').innerHTML = undoTagString(decodeURIComponent(oJsonEstrutura.justificativa));
        }
      }

    }

    /**
     * Salva a justificava em uma estrutura Json em um campo hidden na linha da grid
     */
    function js_salvarJustificativa(sIdJsonElemento, sIdJustificativa, iCaixaLinha, iAutenticacao) {

      var oElementoJustivicativa   = $(sIdJustificativa);
      var sJustificativa           = oElementoJustivicativa.getValue();
      var oIdJsonElemento          = $(sIdJsonElemento);
      var oJsonEstrutura           = JSON.parse(oIdJsonElemento.value);
      oJsonEstrutura.justificativa = encodeURIComponent(tagString(sJustificativa));
      oJsonEstrutura.lendoBanco    = 'false';
      oIdJsonElemento.value        = JSON.stringify(oJsonEstrutura);
      oWindowAux.destroy();

      if ( iCaixaLinha == null ) {
        return;
      }
      /**
       *  Envia Chamada RPC para salvar os Dados da Justificativa
       */

       var iCodigoConciliacao  = parent.document.getElementById('concilia').value;

       var oAjax   = new Ajax.Request("cai4_processaconciliacao.php",{
         method       : 'post',
         asynchronous : false,
         parameters   : {

           iCodigoConciliacao  : iCodigoConciliacao,
           iCodigoCaixaLinha   : iCaixaLinha,
           iCodigoAutenticacao : iAutenticacao,
           lJustificativaCaixa : true,
           sJustificativa      : sJustificativa,
           solicitacao         : "gravarJustificativaPendente"
         },
         onComplete : function( oAjax ) {
           var sResposta =  oAjax.responseText;
           if ( sResposta != "1" ) {
             alert( sResposta );
           }
         }
       });
    }

    function js_marcarTodos(){
      var objTableAutent   = document.getElementById('tabelaAutent');
      for (i=1;i < objTableAutent.rows.length; i++ ){
        if(objTableAutent.rows[i].style.display == '' && objTableAutent.rows[i].className != 'conciliado' ){
          eval('var chk = $("marcado'+(i-1)+'");');
          if (chk.checked){
            chk.checked = false;
            js_somaAutenticacoes(chk,(i-1));
          }else{
            chk.checked = true;
            js_somaAutenticacoes(chk,(i-1));
          }
        }
      }
      return false;
    }


  </script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form name='form1'>

    <div id="divloading_autenticacao"> <b>Aguarde, processando...</b> </div>

    <div id="tabelaAutenticacoes">
      <script> js_processaRequest("<?=$data?>","<?=$conta?>"); </script>
    </div>

  </form>
</body>
</html>