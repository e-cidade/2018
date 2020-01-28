<?
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

$clsolicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("pc50_descr");
$clrotulo->label("pc54_solicita");
$clrotulo->label("pc12_vlrap");
$clrotulo->label("pc12_tipo");
$clrotulo->label("o74_sequencial");
$clrotulo->label("o74_descricao");
$iBloqueia = 1;
if (isset($oGet->alterar)) {

  $lBtnShowBtnConsulta = true;
  $iBloqueia           = 3;

}
?>
<form name="form1" method="post" action="">
<center>
  <table>
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Dados da Compilação</b>
          </legend>
          <table>
            <tr>
              <td nowrap title="Código da Abertura">
                 <b>Código:</b>
              </td>
              <td>
                <?
                db_input('pc10_numero',10,$Ipc10_numero,true,'text',3)
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tpc10_data?>">
                <b>Data Vigência:</b>
              </td>
              <td>
                <?
                $recebedata = db_getsession("DB_datausu");
                $recebedata = date("Y-m-d",$recebedata);
                if(isset($pc10_data) && trim($pc10_data) != ""){
                  $recebedata = $pc10_data;
                }
                $arr_data = split("-",$recebedata);
                @$pc10_datadia = $arr_data[2];
                @$pc10_datames = $arr_data[1];
                @$pc10_dataano = $arr_data[0];
                db_inputdata('pc54_datainicio',null,null,null,true,'text',1);
                echo "&nbsp;<b>a</b>&nbsp;";
                db_inputdata('pc54_datatermino',null,null,null,true,'text',1);
                ?>
              </td>
            <tr>
              <td nowrap title="<?=@$Tpc10_resumo?>">
                <b>Resumo:</b>
              </td>
              <td>
              <?
               @$pc10_resumo = stripslashes($pc10_resumo);
               db_textarea("pc10_resumo",10,120,"",true,"text",$db_opcao,"","","",735);
              ?>
              </td>
            </tr>
            <tr>
              <td>
                 <?
                 db_ancora("<b>Abertura de Preço:</b>", "js_pesquisaaberturaprecos(true);", $iBloqueia);
                 ?>
              </td>
              <td>
                <?
                 db_input('pc54_solicita', 8, $Ipc54_solicita, true, 'text', 3, "");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                 &nbsp;
              </td>
              <td>

                <input type="checkbox" id='pc54_liberado'>
                <label for="pc54_liberado"><b>Disponibilizar para Utilização:</b></label>

              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;">
        <input type='button' value='Salvar' id='btnSalvar'>
        <input type='button' value='Imprimir' id='btnImprimir'>
        <?
          if ($lBtnShowBtnConsulta) {
           echo "<input type='button' value='Pesquisar' id='btnConsultar'>";
          }
        ?>
      </td>
    </tr>
  </table>
</center>
</form>
<script>
var sUrlRC = 'com4_solicitacaoComprasRegistroPreco.RPC.php';
var lAlteracao = <?=isset($oGet->alterar)?"true":"false";?>;
function js_salvarEstimativa() {

  /**
   * as Datas devem ser preenchidas.
   */
   if ($F('pc54_datainicio') == '') {

    alert('Informe a data de início da vigencia.');
    $('pc54_datainicio').focus();
    return false;

   }

   if ($F('pc54_datatermino') == '') {

    alert('Informe a data de termino da vigencia.');
    $('pc54_datatermino').focus();
    return false;

   }

   /**
    * Valida data de inicio e termino - true quando data de termino é menor ou 'i' quando datas são iguais
    * @var mixed bool | string
    */
   var mDiferenca = js_diferenca_datas(js_formatar($F('pc54_datainicio'), 'd'), js_formatar($F('pc54_datatermino'), 'd'), 3);

   if (mDiferenca && mDiferenca != 'i') {

     alert('Data final da vigência menor que a inicial.');
     $('pc54_datatermino').focus();
     return false;
   }

   if ($F('pc54_solicita') == "") {

     alert('Informe a Abertura de Preços.');
     $('pc54_solicita').focus();
     return false;

   }
   js_divCarregando("Aguarde, salvando abertura Registro de Preço.","msgBox");
   
   var oParam         = new Object();
   oParam.exec        = "salvarCompilacao";
   oParam.tipo        = 6;
   oParam.datainicio  = $F('pc54_datainicio');
   oParam.datatermino = $F('pc54_datatermino');
   oParam.liberado    = $('pc54_liberado').checked;
   oParam.iAbertura   = $F('pc54_solicita');
   var sResumo        = tagString($F('pc10_resumo'));
   oParam.resumo      = encodeURIComponent(sResumo);
   var oAjax          = new Ajax.Request(sUrlRC,
                                      {
                                       method: "post",
                                       parameters:'json='+Object.toJSON(oParam),
                                       onComplete: js_retornoSalvarabertura
                                       });
}

function js_retornoSalvarabertura(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

     if (!lAlteracao) {

       parent.iframe_itens.js_preencheGrid(oRetorno.itens);
       parent.mo_camada('itens');

     }
     $('pc10_numero').value = oRetorno.iCodigoSolicita;

  } else {
   alert(oRetorno.message.urlDecode());
  }
}

function js_pesquisar() {

  js_OpenJanelaIframe('',
                      'db_iframe_estimativaregistropreco',
                      'func_solicitacompilacao.php?funcao_js=parent.js_completaPesquisa|pc10_numero&departamento=true'+
                      '&anuladas=1&comcompilacao=1',
                      'Compilação de Registro de Preço',
                      true,
                      0);
}

function js_completaPesquisa(iSolicitacao) {

   var oParam          = new Object();
   oParam.exec         = "pesquisarAbertura";
   oParam.iSolicitacao = iSolicitacao;
   oParam.tipo         = 6;
   db_iframe_estimativaregistropreco.hide();
   var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoCompletaPesquisa
                                         });
}

function js_retornoCompletaPesquisa(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    $('pc54_datainicio').value  = oRetorno.datainicio;
    $('pc54_datatermino').value = oRetorno.datatermino;
    $('pc10_resumo').value      = oRetorno.resumo.urlDecode();
    $('pc54_liberado').checked  = oRetorno.liberado;
    $('pc10_numero').value      = oRetorno.solicitacao;
    $('pc54_solicita').value = oRetorno.codigoabertura;
    parent.iframe_itens.js_preencheGrid(oRetorno.itens);

  } else {
    alert(oRetorno.message.urlDecode());
  }

}

function js_pesquisaaberturaprecos(mostra) {

   js_OpenJanelaIframe('',
                      'db_iframe_registropreco',
                      'func_solicitaregistropreco.php?lFiltraInstituicao=true&funcao_js=parent.js_preenche|pc54_solicita'+
                      '&trazsemcompilacao=1&anuladas=1',
                      'Abertura de Registro de Preço',
                      true,
                      0);
}

function js_preenche(solicita,reload) {

  if (reload == null) {
   reload = false;
  }
  $('pc54_solicita').value = solicita;


  /**
   * Validação adicionada para sabermos se a abertura de registro de preço possui estimativa criada
   */
  js_divCarregando("Aguarde, validando estimativas...", "msgBox");

  var oParam         = new Object();
  oParam.exec        = 'consAberturaDetalhes';
  oParam.detalhe     = 'estimativa';
  oParam.pc10_numero = solicita;

  var oAjax = new Ajax.Request('com4_solicitacaoComprasRegistroPreco.RPC.php',
                               {method: 'post',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: function(oAjax){

                                  js_removeObj("msgBox");
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  if (oRetorno.dados.length == 0) {

                                    alert("Abertura do Registro de Preço "+solicita+" não possui estimativa criada.");
                                    $('pc54_solicita').value = '';
                                    $('btnSalvar').disabled = true;
                                    $('btnImprimir').disabled = true;

                                    return false;
                                  } else {

                                    $('btnSalvar').disabled = false;
                                    $('btnImprimir').disabled = false;
                                    db_iframe_registropreco.hide();
                                  }
                                }
                              });
}

function js_imprimir() {

  if ($F('pc10_numero') == "") {

    alert('Abertura nao está salva.\nPara Emitir, salve a abertura.');
    return false;
  }
  var query  = " ini="+$F('pc10_numero');
  query     += "&fim="+$F('pc10_numero');
  query     += "&departamento=<?=db_getsession("DB_coddepto")?>";
  jan = window.open('com2_compilacaoregistro002.php?'+query,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
}
$('btnSalvar').observe("click", js_salvarEstimativa);
$('btnImprimir').observe("click", js_imprimir);
<?
if ($lBtnShowBtnConsulta) {

  echo "\$('btnConsultar').observe('click', js_pesquisar);\n";
  echo "parent.iframe_itens.location.href='com4_solicitacompilacaoitens.php';\n";
  echo "js_pesquisar();\n";

} else {
  echo "js_pesquisaaberturaprecos(true);\n";
}
?>
</script>
