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

//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("m51_codordem");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post">
  <center>
    <table>
      <tr>
        <td>
          <fieldset><legend><b>Dados da Ordem</b></legend>
            <table border='0'>
              <tr align = 'left'>
                <td align="left">
                  <table align="center">
                    <td nowrap title="<?=@$Tm51_codordem?>">
                      <b> <?db_ancora("Ordem de Compra:","js_consultaordem(\$F('m51_codordem'));",1);?></b>
                    </td>
                    <td>
                      <?
                      db_input('m51_codordem',5,$Im51_codordem,true,'text',3);
                      db_input('z01_nome',30,$Iz01_nome,true,'text',3,'');
                      ?>
                    </td>
                    <td nowrap title="<?=@$Te69_numero?>">
                      <?=@$Le69_numero?>
                    </td>
                    <td>
                      <?
                      db_input('e69_numero',20,$Ie69_numero,true,'text',3,"")
                      ?>
                    </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?=@$Te69_id_usuario?>">
                        <?=@$Le69_id_usuario?>
                      </td>
                      <td>
                        <?
                        db_input('e69_id_usuario',5,$Ie69_id_usuario,true,'text',3);
                        db_input('nome',30,$Inome,true,'text',3,'');
                        ?>
                      </td>
                      <td nowrap title="<?=@$Te69_dtnota?>">
                        <?=@$Le69_dtnota?>
                      </td>
                      <td>
                        <?
                        db_inputdata('e69_dtnota',@$e69_dtnota_dia,@$e69_dtnota_mes,@$e69_dtnota_ano,true,'text',3,"");
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?=@$Te69_dtrecebe?>">
                        <?=@$Le69_dtrecebe?>
                      </td>
                      <td>
                        <?
                        db_inputdata('e69_dtrecebe',null,null,null,true,'text',3);
                        ?>
                      </td>
                      <td nowrap title="<?=@$e70_valor?>"  >
                        <?=@$Le70_valor ?>
                      </td>
                      <td>
                        <?
                        db_input('e70_valor',20,$Ie70_valor,true,'text',3);
                        ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan='5'>
          <fieldset><legend><b>Itens</b></legend>
            <div style='border:2px inset white'>
              <table border='0' width='100%' cellspacing="0" cellpadding="0">
                <tr>
                  <th class='table_header' align='center'><b>Empenho</b></th>
                  <th class='table_header' align='center'><b>Cód. Item</b></th>
                  <th class='table_header' align='center'style='width:30%'><b>Item</b></th>
                  <th class='table_header' align='center'><b>Quantidade</b></th>
                  <th class='table_header' align='center'><b>Valor</b></th>
                  <th class='table_header' align='center'><b>Qtde Atend</b></th>
                  <th class='table_header' align='center'><b>Saldo Estoque</b></th>
                  <th class='table_header' align='center'style='width:18px'><b>&nbsp;</b></th>
                </tr>
                <tbody id='dados' style='height:150;width:100%;overflow:scroll;overflow-x:hidden;background-color:white'>
                </tbody>
              </table>
            </div>
          </fieldset>
        </td>
      </tr>
    </table>
    <input name="anula" id='anular' type="button"  disabled value="Anular" onclick='if (confirm("Confirma a anulação?" )){js_verificaItensBaixados()}'>
    <input name="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisa_empnota(true)">
    <input name="e69_codnota" type="hidden" id='e69_codnota' value="">
  </center>
</form>
</body>
<script>

  var dDataSessao = "<?=date("d/m/Y", db_getsession("DB_datausu"))?>";
  var lEmpenhoMaterialPermanente = false;

  function js_consultaordem(codordem){
    if (codordem != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_ordemcompra002','com3_ordemdecompra002.php?m51_codordem='+codordem,'Consulta Ordem de Compra',true);
    }
  }
  function js_pesquisa_empnota(mostra){
    js_reset();
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empnota','func_empnotaord.php?funcao_js=parent.js_mostraempnota1|e69_codnota|m72_codordem','Pesquisa',true);
    }
    //js_mostraempnota1(38480 , 30);

  }
  function js_mostraempnota1(chave1,chave2){
    js_reset();
    db_iframe_empnota.hide();
    js_getDadosNota(chave2,chave1);
    $('e69_codnota').value = chave1;
  }
  //funcoes ajax.

  function js_getDadosNota(iCodOrdem, iCodNota){

    js_reset();
    sJson = '{"method":"getDados","m51_codordem":"'+iCodOrdem+'","e69_codnota":"'+iCodNota+'"}';
    url   = 'mat4_matordemRPC.php';
    js_divCarregando('Aguarde,Buscando dados da Nota', 'msgBox');
    $('anular').disabled      = true;
    oAjax = new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json='+sJson,
        onComplete: js_retornoGetDados

      }
    );
  }

  function js_retornoGetDados(oAjax){

    js_removeObj('msgBox');
    $('anular').disabled      = false;
    oJson  = eval("("+oAjax.responseText+")");
    if (oJson.status == 2){ //erro na consulta
      alert(js_urldecode(oJson.menssagem));
    }else{//2
      // aki
      $('m51_codordem').value   = oJson.m51_codordem;
      $('z01_nome').value       = (oJson.z01_nome).urlDecode();
      $('nome').value           = (oJson.nome).urlDecode();
      $('e69_id_usuario').value = oJson.id_usuario;
      $('e69_dtrecebe').value   = oJson.e69_dtrecebe;
      $('e69_dtnota').value     = oJson.e69_dtnota;
      $('e69_numero').value     = oJson.e69_numero;
      $('e70_valor').value      = oJson.e70_valor;
      sErroMsg                  = '';
      $('dados').innerHTML      = '';
      lLiberar                  = false;
      sRow                      = '';
      if (oJson.totalItens > 0){//3

        var iCodigoItem = 0;
        var nSaldoItemEstoque = 0;
        for (i = 0; i < oJson.itens.length; i++){

          if (iCodigoItem != oJson.itens[i].m60_codmater) {
            nSaldoItemEstoque = new Number(oJson.itens[i].m71_quant);
          } else {
            nSaldoItemEstoque += new Number(oJson.itens[i].m71_quant);
          }

          sRow += "<tr class='linhagrid' style='height:1em'>";
          sRow += "  <td class='linhagrid'>";
          sRow += "  <a onclick='js_JanelaAutomatica(\"empempenho\","+oJson.itens[i].e60_numemp+");return false;' href='#'>";
          sRow +=     oJson.itens[i].e60_codemp+"/"+oJson.itens[i].e60_anousu+"</a></td>";
          sRow += "  <td class='linhagrid' style='text-align:right'>"+oJson.itens[i].m60_codmater+"</td>";
          sRow += "  <td class='linhagrid'style='text-align:left'>";
          sRow += js_urldecode(oJson.itens[i].m60_descr).substring(0,25)+"</td>";
          sRow += "  <td class='linhagrid'style='text-align:right'>"+oJson.itens[i].m71_quant+"</td>";
          sRow += "  <td class='linhagrid'style='text-align:right'>"+js_formatar(oJson.itens[i].m71_valor,"f")+"</td>";
          sRow += "  <td class='linhagrid'style='text-align:right'>"+oJson.itens[i].m71_quantatend+"</td>";
          sRow += "  <td class='linhagrid'style='text-align:right'>"+oJson.itens[i].m70_quant+"</td>";
          sRow += "</tr>";
          if (new Number(oJson.itens[i].m71_quant) > new Number(oJson.itens[i].m70_quant) && !oJson.lEmpenhoMaterialPermanente){
            sErroMsg = "Erro:Nota Anulada. Sem saldo para anulação";
            lLiberar = true;
          }
          if (oJson.itens[i].m70_quant <  nSaldoItemEstoque && oJson.itens[i].pc01_servico == false && !oJson.lEmpenhoMaterialPermanente ){
            lLiberar = true;
          }
          iCodigoNovo = oJson.itens[i].m60_codmater;
          iCodigoItem = iCodigoNovo;
        }
        /*
         situacoes:
         1 - Liquidada
         2 - Anulada
         3 - Paga
         4 - Normal (nenhuma das acias)
         * validacoes da situacao da nota (Ordem vrtual);
         caso a nota seje virtual, podemos anular a entrada da nota caso ela estiver liquidada ou
         qualquer valor pago,caso a nota estiver anulada nao podera ser mais anulada essa entrada.
         * Ordem Normal.
         para anular a entrada dos itens de uma nota normal, a nota nao podera estar paga, nem liquidada (situacoes 3 e 1) ;
         */

        lEmpenhoMaterialPermanente = oJson.lEmpenhoMaterialPermanente;
        if ( !oJson.lEmpenhoMaterialPermanente) {
          if (oJson.m51_tipo == 2) {
            //verificacoes da nota virtual;
            if (oJson.situacaonota == 2) {

              lLiberar = true;
              sErroMsg = "Erro:Nota Anulada.Não pode ser estornado os itens";
            }
          } else {

            if (oJson.situacaonota == 2) {

              lLiberar = true;
              sErroMsg = "Erro:Nota Anulada. Não pode ser estornado os itens";

            } else if (oJson.situacaonota == 3) {

              lLiberar = true;
              sErroMsg = "Erro:Nota Paga. Não pode ser estornado os itens";
            } else if (oJson.situacaonota == 1) {

              lLiberar = true;
              sErroMsg = "Erro:Nota Liquidada. Não pode ser estornado os itens";
            }
          }
        }
        if (sErroMsg != ''){
          alert(sErroMsg);
        }
        sRow += "<tr style='height:auto'><td>&nbsp;</td></tr>";
        $('dados').innerHTML = sRow;
        $('anular').disabled = lLiberar;

      }else{

        $('dados').innerHTML = "<tr><td colspan='8' style='text-align:center class='linhagrid'><b>Nota sem Itens.</b></td</tr>";
        $('dados').innerHTML += "<tr style='height:auto'><td colspan='8'>&nbsp;</td></tr>";

      }
    }
  } //1

  function js_urldecode(str){

    str = str.replace(/\+/g," ");
    str = unescape(str);
    return str;
  }

  function js_anularEntrada() {

    iCodOrdem = $F('m51_codordem');
    iCodNota  = $F('e69_codnota');
    var sUrlAnulacao = 'anularEntradaOrdem';
    if (lEmpenhoMaterialPermanente) {
      sUrlAnulacao = 'anularEntradaOrdemEmpenhoMaterialPermanente';
    }

    sJson = '{"method":"'+sUrlAnulacao+'","m51_codordem":"'+iCodOrdem+'","e69_codnota":"'+iCodNota+'"}';
    url   = 'mat4_matordemRPC.php';
    js_divCarregando('Aguarde, anulando a entrada da Nota','msgBox');
    $('anular').disabled      = true;
    oAjax = new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json='+sJson,
        onComplete: js_retorno
      }
    );
  }
  function js_retorno(oAjax){

    js_removeObj('msgBox');
    $('anular').disabled      = false;
    oJson  = eval("("+oAjax.responseText+")");
    if (oJson.status == 2) { //erro na consulta

      alert(js_urldecode(oJson.mensagem));
    } else {

      alert(js_urldecode(oJson.mensagem));
      js_pesquisa_empnota(true);
    }
  }
  function js_reset(){

    $('m51_codordem').value   = '';
    $('z01_nome').value       = '';
    $('nome').value           = '';
    $('e69_id_usuario').value = '';
    $('e69_dtrecebe').value   = '';
    $('e69_dtnota').value     = '';
    $('e69_numero').value     = '';
    $('e70_valor').value      = '';
    $('dados').innerHTML      = '';
    $('anular').disabled      = true;

  }

  /**
   * Realiza uma consulta via Json para verificar se há bens referentes a nota fiscal
   * que estão incluso no patrimonio e ainda não foram baixados
   */
  function js_verificaItensBaixados() {

    iCodigoNota  = $F('e69_codnota');
    iCodOrdem = $F('m51_codordem');

    if ( js_comparadata(dDataSessao, $F('e69_dtnota'),"<") ) {
      alert("Data para anulação menor que a data da Entrada da Ordem!\nPara continuar com a operação, a data deverá ser maior ou igual a data da entrada da ordem");
      return false;
    }

    sJson = '{"method":"verificaBensBaixado","iCodigoNota":"'+iCodigoNota+'", "m51_codordem":"'+iCodOrdem+'"}';
    url   = 'mat4_matordemRPC.php';
    js_divCarregando('Aguarde, verificando itens da Nota.','msgBox');
    oAjax = new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json='+sJson,
        onComplete: js_retornoVerificaItensBaixados
      }
    );
  }

  function js_retornoVerificaItensBaixados(oAjax) {

    js_removeObj('msgBox');
    oJson  = eval("("+oAjax.responseText+")");
    if (oJson.status == '3') {

      alert(oJson.sMensagem.urlDecode());
      return false;
    }


    switch (oJson.status) {

      case 2:

        var sMsg  = "Atenção! Você não pode anular uma nota com bens ainda ativo no patrimônio";
        sMsg += "\nDeseja verificar os bens ativos?";
        if (confirm(sMsg)) {

          jan = window.open('mat2_bensativosnota002.php?iCodigoNota='+oJson.iCodigoNota,
            '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
        }
        break;

      case 4:

        var sMsg  = "O material foi dispensado de tombamento no módulo patrimonial.\n\nÉ necessário ";
        sMsg += "estornar esta dispensa para que seja possível anular a entrada da ordem de compra.";
        alert(sMsg);
        break;

      case 5:

        var sMensagem = "Nota de Liquidação "+oJson.iCodigoNota+" possui valor liquidado. \n\nÉ necessário estornar a liquidação para que seja possível anular a entrada da ordem de compra.";
        alert(sMensagem);
        break;

      default:
        js_anularEntrada();

    }
  }


  js_pesquisa_empnota(true);
</script>
</html>