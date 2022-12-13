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

require_once(modification("classes/db_db_depusu_classe.php"));
require_once(modification("classes/db_db_almoxdepto_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));
$cldb_depusu     = new cl_db_depusu();
$cldb_almoxdepto = new cl_db_almoxdepto();
$clrotulo        = new rotulocampo();
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
$clrotulo->label("m51_numcgm");
$clrotulo->label("m51_codordem");
$clrotulo->label("e69_numero");
$clrotulo->label("e69_dtnota");
$clrotulo->label("e69_dtrecebe");
$clrotulo->label("e69_localrecebimento");
$clrotulo->label("e70_valor");
$clrotulo->label("m51_valortotal");
$clrotulo->label("m80_obs");
$clrotulo->label("m51_depto");
$clrotulo->label("descrdepto");
$clrotulo->label("m90_liqentoc");
$clrotulo->label("cc08_sequencial");
$clrotulo->label("m78_matfabricante");
$clrotulo->label("m76_nome");
$clrotulo->label("e11_cfop");
$clrotulo->label("e10_cfop");
$clrotulo->label("e11_seriefiscal");
$clrotulo->label("e11_inscricaosubstitutofiscal");
$clrotulo->label("e11_valoricmssubstitutotrib");
$clrotulo->label("e11_basecalculoicmssubstitutotrib");
$clrotulo->label("e11_basecalculoicms");
$clrotulo->label("e11_valoricms");
$clrotulo->label("e12_descricao");
$clrotulo->label("e10_descricao");
$clrotulo->label("cc31_classificacaocredores");
$depto_atual        = db_getsession("DB_coddepto");
$usu_atual          = db_getsession("DB_id_usuario");
$vlrtot             = 0;
$db_opcao           = 1;
$sData              = date("d/m/Y", db_getsession("DB_datausu"));
(integer) $iInfNota = 1;
if (isset($atualiza)) {
  $e70_valor = trim(db_formatar($e7_valor, 'p'));
}
$aParamKeys = array(
  db_getsession("DB_anousu")
);
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);

$iTipoControleCustos = 0;
$iControlaPit        = 0;

if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}
$aParamKeys = array(
  db_getsession("DB_instit")
);
$aParametrosPit   = db_stdClass::getParametro("matparaminstit",$aParamKeys);
if (count($aParametrosPit) > 0) {
  $iControlaPit = $aParametrosPit[0]->m10_controlapit;
}
$aParamKeys = array(
  db_getsession("DB_anousu")
);
$aParametrosEmpenho   = db_stdClass::getParametro("empparametro",$aParamKeys);
$iNumeroCasasDecimas = 2;
if (count($aParametrosEmpenho) > 0) {
  $iNumeroCasasDecimas = $aParametrosEmpenho[0]->e30_numdec;
}

$oPost = db_utils::postMemory($_POST);
?>

<form name="form1" method="post">

  <fieldset style='text-align: left'><Legend><b>Dados da Ordem de Compra</b></legend>
    <table>
      <?
      if($iControlaPit == 1){
        ?>
        <tr>
          <td nowrap align="left" title="<?=@$Tm51_codordem?>">
            <?php db_ancora("<b>Ordem de Compra:</b>","js_consultaOrdemDeCompra();", 1); ?>
          </td>
          <td colspan='1'>
            <?
            db_input('m51_codordem', 10, $Im51_codordem, true, 'text', 3);
            ?>
          </td>
          <td><b>Tipo de Documento Fiscal:</b></td>
          <td>
            <?
            $oDaoDocumentoFiscais = db_utils::getDao("tipodocumentosfiscal");
            $rsDocs = $oDaoDocumentoFiscais->sql_record($oDaoDocumentoFiscais->sql_query(null, "*", "e12_sequencial"));
            $aItens[0] = "selecione";
            for($i = 0; $i < $oDaoDocumentoFiscais->numrows; $i ++) {

              $oItens = db_utils::fieldsMemory($rsDocs, $i);
              $aItens [$oItens->e12_sequencial] = $oItens->e12_descricao;

            }
            db_select('e11_tipodocumentofiscal', $aItens, true, 1, "onchange=js_abreNotaExtra()");
            ?>
            <a href='#' onclick='js_abreNotaExtra()' style='display: none'id='dadosnotacomplementar'>Outros Dados</a>
          </td>
        </tr>
        <?
      }else{
        ?>
        <tr>
          <td nowrap align="left" title="<?=@$Tm51_codordem?>">
            <?php db_ancora("<b>Ordem de Compra:</b>","js_consultaOrdemDeCompra();", 1); ?>
          </td>
          <td colspan='1'>
            <?
            db_input('m51_codordem', 10, $Im51_codordem, true, 'text', 3);
            ?>
          </td>
          <td  style='display: <?=$iControlaPit==1?"":"none"?>'>
            <b>Tipo Documento Fiscal: </b>
          </td>
          <td  style='display: <?=$iControlaPit==1?"":"none"?>'><?
            $oDaoDocumentoFiscais = db_utils::getDao("tipodocumentosfiscal");
            $rsDocs = $oDaoDocumentoFiscais->sql_record($oDaoDocumentoFiscais->sql_query(null, "*", "e12_sequencial"));
            $aItens[0] = "selecione";
            for($i = 0; $i < $oDaoDocumentoFiscais->numrows; $i ++) {

              $oItens = db_utils::fieldsMemory($rsDocs, $i);
              $aItens [$oItens->e12_sequencial] = $oItens->e12_descricao;

            }
            db_select('e11_tipodocumentofiscal', $aItens, true, 1, "onchange=js_abreNotaExtra()");
            ?>
            <a href='#' onclick='js_abreNotaExtra()' style='display: none' id='dadosnotacomplementar'>Outros Dados</a> </td>
        </tr>

        <?
      }
      ?>
      <tr>
        <td>
          <?
          echo $Lm51_numcgm;
          ?>
        </td>
        <td>
          <?
          db_input('m51_numcgm', 10, $Im51_codordem, true, 'text', 3);
          db_input('z01_nome', 36, $Iz01_nome, true, 'text', 3);
          ?>
        </td>
        <td><b><?=$Le69_numero;?>
        </td>
        <td>
          <?
          db_input('e69_numero', 15, 1, true, 'text', 1, "onchange='js_verificaNota(this.value);'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="left" title="<?=@$descrdepto?>">
          <?=@$Lm51_depto?>
        </td>
        <td>
          <?
          db_input('m51_depto', 10, $Im51_depto, true, 'text', 3);
          db_input('descrdepto', 36, $Idescrdepto, true, 'text', 3);
          ?>
        </td>
        <td><b>Data da Nota:</b></td>
        <td>
          <?
          db_inputdata('e69_dtnota', null, null, null, true, 'text', 1, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="left" title=""><b>Valor da Ordem de Compra:</b></td>
        <td>
          <?
          db_input('m51_valortotal', 10, $Im51_valortotal, true, 'text', 3)
          ?>
        </td>
        <td><b>Data de Recebimento:</b></td>
        <td>
          <?
          db_inputdata('e69_dtrecebe', '', '', '', true, 'text', 1, "");
          ?>
        </td>
      </tr>
      <tr>


        <td class="bold"><?= $Lcc31_classificacaocredores ?></td>
        <td>
          <?php
          db_input('codigo_lista_credor', 10, 1, true, 'text', 3);
          db_input('descricao_lista_credor' , 36, 4, true, 'text', 3);
          ?>
        </td>
        <td nowrap align="left" title=""><b>Data de Vencimento:</b></td>
        <td>
          <?php
          db_inputdata('e69_dtvencimento', '', '', '', true, 'text', 1, "");
          ?>
        </td>

      </tr>
      <tr>
        <td>
          <strong>Processo Administrativo:</strong>
        </td>
        <td>
          <?php db_input('e04_numeroprocesso', 10, '', true, 'text', $db_opcao); ?>
        </td>
        <td nowrap align="left" title=""><b><?=$Le70_valor;?></b></td>
        <td>
          <?
          db_input('e70_valor', 10, $Ie70_valor, true, 'text', 3, "onblur='js_setValorAlancar()' onKeyUp=\"js_ValidaCampos(this,4,'','','',event)\" ");
          ?>
        </td>
      </tr>
      <tr>
        <td><label class="bold"><?= $Le69_localrecebimento ?></label></td>
        <td colspan="3" align='left'>
          <?php
          $Ne69_localrecebimento = null;
          db_input("e69_localrecebimento", 85, $Ie69_localrecebimento, true, "text", $db_opcao);
          ?>
        </td>
      </tr>

      <tr>
        <td colspan="4" align='left'>
          <fieldset style="border-bottom: none; border-left: none; border-right: none;">
            <legend><b>Observações</b></legend>
            <?php
            db_textarea("m53_obs", "", "90", '', true, 'text', 1);
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <table width="100%" border='0' cellspacing="0" cellpadding="0">
    <tr>
      <td width="50%" valign="top">
        <fieldset style='text-align: left'><legend>Itens da Ordem</legend>
          <table border='0' width='100%' cellspacing="0" cellpadding="0" style='border: 2px inset white;table-layout: fixed;' id='gridItens'>
            <tr>
              <th class='table_header' width='20'><input type='checkbox' checked style='display: none' id='mtodos'
                                                         onclick='js_marca()'> <b><a onclick='js_marca()' style='cursor: pointer'>M</a></b></th>
              <th class='table_header' align='center'><b>Emp.</b></th>
              <th class='table_header' width="30%" align='center'><b>Material</b></th>
              <th class='table_header' width="30%" align='center'><b>Obs</b></th>
              <th class='table_header' align='center'><b>Vl. Unit.</b></th>
              <th class='table_header' align='center'><b>Qtde</b></th>
              <th class='table_header' align='center'><b>Valor</b></th>
              <th class='table_header' align='center' width='25'><b>&nbsp;</b></th>
            </tr>
            <tbody id='dados' style='height: 215px; width: 100%; overflow: scroll; overflow-x: hidden; background-color: white'>
            </tbody>
          </table>
        </fieldset>
      </td>
      <td width="50%" valign="top">
        <fieldset style='text-align: left'><legend>Dados do Lançamento</legend>
          <table>
            <tr>
              <td><b>Quant. Recebida:</b></td>
              <td>
                <?
                db_input('m52_codlanc', 10, null, true, 'hidden', 1);
                db_input('iIndice', 10, null, true, 'hidden', 1);
                db_input('sJson', 10, null, true, 'hidden', 1);
                db_input('qtdeRecebido', 10, null, true, 'text', 1, "onblur='js_calculaQuant();' onkeypress='return js_teclas(event)'");
                ?>
                <b>(<span id='saldoitens'>0</span>)</b>
              </td>
            </tr>
            <tr id="qtdCorrigido" style="display:none">
              <td>
                <b>Quant. Corrigido:</b>
              </td>
              <td>
                <?
                db_input('corrigeQtdeRecebido', 10, null, true, 'text', 1, "onblur='js_calculaQuant();' onkeypress='return js_teclas(event)'");
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Valor. Recebido:</b></td>
              <td>
                <?
                db_input('valorRecebido', 10, null, true, 'text', 1, "onblur='js_calculaValor();' onkeypress='return js_teclas(event)'");
                ?>
                <b>(<span id='saldovalor'>0</span>)</b>
              </td>
            </tr>
            <tr id="valorCorrigido" style="display:none">
              <td>
                <b>Valor. Corrigido :</b>
              </td>
              <td>
                <?
                db_input('corrigeValorRecebido', 10, null, true, 'text', 1, "onblur='js_calculaValor();' onkeypress='return js_teclas(event)'");
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Unidade de Entrada</b></td>
              <td>
                <?
                $oDaoUnid = db_utils::getDao("matunid");
                $rsUnidades = $oDaoUnid->sql_record($oDaoUnid->sql_query_file(null, "*", "m61_descr"));
                $aUnidades = array();
                for($i = 0; $i < $oDaoUnid->numrows; $i ++) {

                  $oItens = db_utils::fieldsMemory($rsUnidades, $i);
                  $aUnidades [$oItens->m61_codmatunid] = $oItens->m61_descr;

                }
                db_select('unidadeentrada', $aUnidades, true, 1, "style='width:200pt'");
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Quant. Unidade:</b></td>
              <td>
                <?
                db_input('quantunid', 10, null, true, 'text', 1,"onkeypress='return js_teclas(event)'");
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Material de Entrada:</b></td>
              <td>
                <?
                $rsInicio = db_query("select ''as codmatmater,'Selecione' as descr");
                db_selectrecord("matmater", $rsInicio, true, 1, "style='width:200pt' disabled='disabled'");
                ?>
            </tr>
            <tr>
              <td><b>Lote:</b></td>
              <td>
                <?
                db_input('m77_lote', 10, null, true, 'text', 1);
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Validade:</b></td>
              <td>
                <?
                db_inputdata('m77_dtvalidade', null, null, null, true, 'text', 1);
                ?>

              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tm78_matfabricante?>">
                <?
                db_ancora(@$Lm78_matfabricante, "js_pesquisam78_matfabricante(true);", 1);
                ?>
              </td>
              <td colspan="3">
                <?
                db_input('m78_matfabricante', 10, $Im78_matfabricante, true, 'text', 1, " onchange='js_pesquisam78_matfabricante(false);'");
                db_input('m76_nome', 25, $Im76_nome, true, 'text', 3, '')?>
              </td>
            </tr>
            <tr id='centrodecusto' style='display:none'>
              <td>
                <?
                db_ancora("<b>Centro de de Custo:</b>",'js_adicionaCentroCusto()', 1,"","centrocusto");
                ?>
              </td>
              <td>
                <?
                db_input('cc08_sequencial',10,$Icc08_sequencial,true,"text", 3);
                db_input('cc08_descricao',40,"",true,"text",3);
                ?>
              </td>
            </tr>
          </table>
          <hr>
          <button type='button' style='border: 1px solid #999999' title='Salvar informações do item' id='salvar'
                  onClick='js_saveMaterial(false)'><u>S</u>alvar</button>
          <? if (db_permissaomenu(db_getsession("DB_anousu"),480,3981) == "true") { ?>
            <button type='button' style='border: 1px solid #999999' title='Cadastradar Novo item de Entrada' id='novoItem'
                    value='' onclick="js_novomatmater()" >Novo</button>
          <? } ?>
          <button type='button' style='border: 1px solid #999999' title='Escolher Item de Entrada'
                  id='escolher' onclick='js_escolherMater();'>Escolher</button>
          <button type='button' style='border: 1px solid #999999' title='Fracionar Entrada do item' id='doFracionar'
                  onclick='js_saveMaterial(true)'><u>F</u>racionar</button>
          <button type='button' style='border: 1px solid #999999' title='Cancela o fracionamento Escolhido'
                  id='excluirFracionar' onclick='js_cancelarFracionamento();' disabled value=''>Cancelar Fracionamento</button>
          <br>
          <button type='button' style='border: 1px solid #999999' title=''
                  id='acertoQtdValor' onclick='js_acertaQtdValor(this.value);' value='acerta' disabled>Acerto Qtd/Valor</button>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td>
        <?
        echo "<b>Lançado:</b>";
        db_input('lancado', 10, null, true, 'text', 3);
        echo "<b>A Lançar:</b>";
        db_input('alancar', 10, null, true, 'text', 3);
        ?>
      </td>

  </table>

  <center><input name="Confirmar" id='confirmar' type="button" value="Confirmar" onclick='js_confirmaEntrada()'> <input
      name="pesquisar" id='pesquisar' type="button" value="Pesquisar" onclick="js_pesquisa_matordem()"></center>
  <div id='divDadosNotaAux' style='display:none; text-align: center;' >
    <table width="100%">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Dados Complementares</b>
            </legend>
            <table>
              <tr>
                <td nowrap title="<?=@$Te11_cfop?>">
                  <?
                  db_ancora("<b>CPOF</b>","js_pesquisae11_cfop(true);",$db_opcao);
                  ?>
                </td>
                <td nowrap>
                  <?
                  db_input('e11_cfop',10,$Ie11_cfop,true,'hidden',3," onchange='js_pesquisae11_cfop(false);'");
                  db_input('e10_cfop',10,$Ie10_cfop,true,'text',$db_opcao," onchange='js_pesquisae11_cfop(false);'");
                  db_input('e10_descricao',40,$Ie10_descricao,true,'text',3,'')
                  ?>
                </td>
              </tr>
              <tr>
                <td  nowrap>
                  <b>Série:</b>
                </td>
                <td  nowrap>
                  <?
                  db_input('e11_seriefiscal',10,$Ie11_seriefiscal,true,'text',1,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td  nowrap>
                  <b>Inscrição Subst.Fiscal:</b>
                </td>
                <td  nowrap>
                  <?
                  db_input('e11_inscricaosubstitutofiscal',10,$Ie11_inscricaosubstitutofiscal,true,'text',1,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td  nowrap>
                  <b>Base Calculo ICMS:</b>
                </td>
                <td  nowrap>
                  <?
                  db_input('e11_basecalculoicms',10,@$Ie11_basecalculoicms,true,'text',1,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td  nowrap>
                  <b>Valor ICMS:</b>
                </td>
                <td  nowrap>
                  <?
                  db_input('e11_valoricms',10,$Ie11_valoricms,true,'text',1,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td  nowrap>
                  <b>Base Calculo ICMS Substituto:</b>
                </td>
                <td  nowrap>
                  <?
                  db_input('e11_basecalculosubstitutotrib',10,@$Ie11_basecalculosubstitutotrib,true,'text',1,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td  nowrap>
                  <b>Valor ICMS Substituto:</b>
                </td>
                <td  nowrap>
                  <?
                  db_input('e11_valoricmssubstitutotrib',10,$Ie11_valoricmssubstitutotrib,true,'text',1,'');
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan="4" style='text-align: center'>
          <input type='button' value='Salvar Informações' onclick='windowAuxiliarNota.hide()'>
        </td>
      </tr>
    </table>
  </div>
</form>
</body>
</html>
<div id='teste'>
</div>
<div style='position: absolute;
            top: 200px;
            left: 15px;
            border: 1px solid black;
            width: 300px;
            text-align: left;
            padding: 3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<script type="text/javascript">

  $('e69_dtrecebe').value = '<?= $sData ;?>';

  function js_consultaOrdemDeCompra() {

    var iOrdemCompra = $F('m51_codordem');
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_consultaOrdemCompraNovo','com3_ordemdecompra002.php?m51_codordem=' + iOrdemCompra,'Consulta Ordem de Compra',true);
  }

  $('m53_obs').style.width = '100%';
  $('e69_localrecebimento').style.width = '100%';


  $('quantunid').observe('change', function () {

    if ($F('quantunid') <= 0) {
      $('quantunid').value = 1;
    }
  });

  $('lancado').value = "";
  <?

  $aUnidades = db_utils::getCollectionByRecord($rsUnidades, false, false, true);
  $oJson = new Services_JSON();
  $sUnidades = $oJson->encode($aUnidades);
  echo "oUnidades = eval('({\"unidades\":{$sUnidades}})')\n";
  echo "iTipoControleCustos = {$iTipoControleCustos};\n";
  echo "iControlaPit        = {$iControlaPit};\n";
  echo "iNumeroDecimais     = {$iNumeroCasasDecimas};\n";

  ?>
  /**
   * Consulta os dados da Ordem.
   */
  function js_consultaOrdem(iOrdem) {

    js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
    js_bloqueiaLiberaBotao(true);
    strJson = '{"method":"getInfoEntrada","m51_codordem":"'+iOrdem+'"}';
    $('dados').innerHTML    = '';
    $('centrodecusto').style.display = "none";
    $('pesquisar').disabled = true;
    url     = 'mat4_matordemRPC.php';
    oAjax   = new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json='+strJson,
        onComplete: js_retornoGetDados
      }
    );

  }
  /*
   * Retorno da Requisição da consulta dos itens da ordem.
   *
   */
  function js_retornoGetDados(oAjax) {

    $('codigo_lista_credor').value    = "";
    $('descricao_lista_credor').value = "";
    $('e69_dtvencimento').value       = "";

    js_removeObj("msgBox");
    js_bloqueiaLiberaBotao(false);
    var oJson  = eval("("+oAjax.responseText+")");
    if (oJson.status == 2) {
      alert(oJson.mensagem.urlDecode());
      return false;
    }
    $('pesquisar').disabled = false;
    $('m51_codordem').value   = oJson.m51_codordem;
    $('m51_depto').value      = oJson.m51_depto;
    $('m51_valortotal').value = oJson.m51_valortotal;
    $('descrdepto').value     = oJson.descrdepto.urlDecode();
    $('m51_numcgm').value     = oJson.m51_numcgm;
    $('z01_nome').value       = oJson.z01_nome.urlDecode();

    if (empty(oJson.sClassificacao)) {
      oJson.sClassificacao = "Empenho não classificado";
    }

    $('codigo_lista_credor').value    = oJson.iClassificacao;
    $('descricao_lista_credor').value = oJson.sClassificacao.urlDecode().toUpperCase();

    if (!empty(oJson.sDataVencimento)) {
      $('e69_dtvencimento').value = oJson.sDataVencimento.urlDecode();
    }

    sErroMsg                  = '';
    $('dados').innerHTML      = '';
    lLiberar                  = false;
    sRow                      = '';
    iLinhasErro               = 0; //quantidade
    $('e69_numero').readOnly           = true;
    $('e69_numero').style.background   = "#DEB887";
    $('e69_dtnota').style.background   = "#DEB887";
    $('e69_dtnota').readOnly           = true;
    $('e69_dtrecebe').style.background = "#DEB887";
    $('e69_dtrecebe').readOnly         = true;
    $('e70_valor').readOnly            = true;
    $('e70_valor').style.background    = "#DEB887";
    document.form1.dtjs_e69_dtnota.style.display   = 'none';
    document.form1.dtjs_e69_dtrecebe.style.display = 'none';
    if (oJson.m51_tipo == 1) {

      $('e69_numero').readOnly           = false;
      $('e69_numero').style.background   = "#FFFFFF";
      $('e69_dtnota').style.background   = "#FFFFFF";
      $('e69_dtnota').readOnly           = false;
      $('e69_dtrecebe').style.background = "#FFFFFF";
      $('e69_dtrecebe').readOnly         = false;
      $('e70_valor').readOnly            = false;
      $('e70_valor').style.background    = "#FFFFFF";
      document.form1.dtjs_e69_dtnota.style.display   = '';
      document.form1.dtjs_e69_dtrecebe.style.display = '';

    } else if (oJson.m51_tipo == 2) {

      $('e69_numero').value   = oJson.e69_numero.urlDecode();
      $('e69_dtnota').value   = oJson.e69_dtnota;
      $('e69_dtrecebe').value = oJson.e69_dtrecebe;
      $('e70_valor').value    = oJson.e70_valor;

    }
    sDiv = '';
    var nTotalLancado = new Number(0);
    if (oJson.itens.length > 0) {

      //percorremos itens da ordem de Compra. e populamos a grid.;
      for (var iItens = 0; iItens < oJson.itens.length; iItens++) {

        with (oJson.itens[iItens]) {

          iCodLinha  =  m52_codlanc+'_'+iIndiceEntrada;
          sClassName = " normal";
          if (m63_codmatmater == '') {
            sClassName = 'semMatmater';
          }
          sDisabled = "";
          sReturn   = "";
          if (new Number(saldovalor).toFixed(3) == 0 || Number(saldoitens).toFixed(2) == 0){

            sClassName = "disabled";
            sDisabled  = "disabled";
            checked    = "";
            sReturn    = " return false; ";

          }
          //Se devemos mostrar o checkbox para o usuário. apenas mostramos se o item não e um item fracionado
          sShowCheckbox = '';
          if (iIndiceEntrada > 0) {
            sShowCheckbox = "none";
          }
          sRow += "<tr style='cursor:default; height:25px; ' onclick=\""+sReturn+" js_send($('chk"+iCodLinha+"'),"+m52_codlanc+","+iIndiceEntrada+")\"";
          sRow += "    class='"+sClassName+"' id='trchk"+iCodLinha+"'>";
          sRow += "  <td class='linhagrid' style='text-align:right'>";
          sRow += "    <input type='checkbox' id='chk"+m52_codlanc+"_"+iIndiceEntrada+"' "+checked+" "+sDisabled;
          sRow += "    onClick='js_send(this,"+m52_codlanc+","+iIndiceEntrada+");js_setValoresLancados(this,"+m52_codlanc+","+iIndiceEntrada+")'";
          sRow += "  value='"+m52_codlanc+"'";
          sRow += "    class='chkmarca' style='height:11px;display:"+sShowCheckbox+";'>";
          sRow += "    <input type='hidden' id='iCodItem"+iCodLinha+"' value='"+e62_sequencial+"'>";
          sRow += "  <td class='linhagrid' id='codemp"+iCodLinha+"'style='text-align:right'>";
          sRow += "  <a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;'";
          sRow += "href='#'>"+e60_codemp+"</a></td>";
          if (!fraciona ) {

            sRow += "  <td class='linhagrid' id='descrmater"+iCodLinha+"' style='text-align:left'>";
            sRow += " <div class='teste' onmouseover='js_setAjuda(this.innerHTML,true)' onmouseOut='js_setAjuda(null,false)'>";
            sRow +=     pc01_codmater+"-"+pc01_descrmater.urlDecode()+"</div></td>";
            sRow += "  <td class='linhagrid' style='text-align:left;width:150px' id='descrmater"+iCodLinha+"'>";
            sRow += " <div class='teste'>";
            sRow += "   <span onmouseover='js_setAjuda(this.innerHTML,true)' onmouseOut='js_setAjuda(null,false)'>"+e62_descr.urlDecode()+"</span>";
            sRow += " </div></td>";

          } else {
            sRow += "  <td class='linhagrid' colspan='2' id='descrmater"+iCodLinha+"' style='text-align:left' nowrap><div style='overflow:hidden'>";
            sRow +=    pc01_descrmater.urlDecode()+"</div></td>";
          }

          sRow += "  <td class='linhagrid' id='vlrUn"+iCodLinha+"' style='text-align:right'>"+js_formatar(e62_vlun,'f', iNumeroDecimais)+"</td>";
          sRow += "  <td class='linhagrid' id='saldoItem"+iCodLinha+"'  style='text-align:right'>"+saldoitens+"</td>";
          sRow += "  <td class='linhagrid' id='saldovalor"+iCodLinha+"'  style='text-align:right;display:'>"+new Number(m52_valor).toFixed(2)+"</td>";
          sRow += "  <td valign='middle'> <img class='selecionado'id='"+iCodLinha+"'  style='display:none' src='imagens/seta_direita.gif'></td>";
          sRow += "</tr>";
          nTotalLancado += new Number (m52_valor).toFixed(2);
        }
      }
      sRow += "<tr style='height:auto; background-color: #FFF;'><td style='background-color:#FFF;' colspan='8'>&nbsp;</td></tr>";
      $('dados').innerHTML = sRow;
      js_marca();
      //$('lancado').value = nTotalLancado.toFixed(2);
      if ($F('e70_valor') != '') {
        $('alancar').value = new Number((new Number($F('e70_valor')) - new Number(nTotalLancado))).toFixed(2);
      }

    }
  }

  function js_send(obj, iCodLanc, iIndice) {

    ///alert(obj);
    setSelecionado(iCodLanc+"_"+iIndice);
    js_setValorAlancar();
    $('matmater').disabled         = true;
    $('doFracionar').disabled      = false;
    $('qtdeRecebido').disabled     = false;
    $('valorRecebido').disabled    = false;
    $('excluirFracionar').disabled = true;
    $('acertoQtdValor').disabled   = true;

    js_acertaQtdValor('cancela');

    if (obj.checked) {

      js_divCarregando("Aguarde, buscando informações","msgBox");
      js_bloqueiaLiberaBotao(true);
      var strJson  = '{"method":"getInfoItem","m51_codordem":"'+$F('m51_codordem')+'",';
      strJson += '"iCodLanc":'+iCodLanc+',"iIndice":'+iIndice+'}';
      url     = 'mat4_matordemRPC.php';
      oAjax   = new Ajax.Request(
        url,
        {

          method: 'post',
          parameters: 'json='+strJson,
          onComplete: js_retornoSend

        }
      );

    } else {

      /*
       * atualiza o item na seçao, e marca ele como desabilitado
       * somente itens pai(o que nao é fracionado);
       */
      var strJson  = '{"method":"desmarcarItem","m51_codordem":"'+$F('m51_codordem')+'",';
      strJson += '"iCodLanc":'+iCodLanc+',"iIndice":'+iIndice+'}';
      url     = 'mat4_matordemRPC.php';
      var oAjax   = new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json='+strJson
        }
      );

    }
  }
  //Atualiza as informações do item escolhido e mantem no escopo global informações sobre o objeto.
  function js_retornoSend(oAjax) {

    var oResposta = eval("(" + oAjax.responseText + ")");
    if (typeof oResposta.status != 'undefined' && oResposta.status == 2) {

      js_removeObj("msgBox");
      alert(oResposta.mensagem.urlDecode());
      return;
    }

    /*
     * verificamos se o usuário fez modificações no item anterior e
     * não gravou as modificações
     */
    js_removeObj("msgBox");
    js_bloqueiaLiberaBotao(false);
    if ($F('sJson') != '' && lSalvo == false) {

      var oItemAnt    = eval("("+$F('sJson')+")");
      var lModificado = false;
      if ($F('qtdeRecebido') != oItemAnt.m52_quant) {
        lModificado = true;
      }
      if ($F('valorRecebido') != oItemAnt.m52_valor) {
        lModificado = true;
      }
      if ($F('quantunid') != oItemAnt.quantunidade) {
        lModificado = true;
      }
      if ($F('unidadeentrada') != oItemAnt.unidade) {
        lModificado = true;
      }
      if ($F('matmater') != oItemAnt.m63_codmatmater) {
        lModificado = true;
      }
      if ($F('m77_lote') != oItemAnt.m77_lote) {
        lModificado = true;
      }
      if ($F('m77_dtvalidade') != oItemAnt.m77_dtvalidade) {
        lModificado = true;
      }
      if (lModificado) {
        if (!confirm('Há Modificações no item Anterior que nao foram salvas.\nContinuar?')) {

          setSelecionado(oItemAnt.m52_codlanc+"_"+oItemAnt.iIndiceEntrada);
          return false;
        }
      }
    }

    //CUIDADO! objeto oItemAtivo em escopo global
    oItemAtivo                        =  eval("("+oAjax.responseText+")");

    $('m77_lote').value               = oItemAtivo.m77_lote;
    $('m77_dtvalidade').value         = oItemAtivo.m77_dtvalidade;
    $('qtdeRecebido').value           = oItemAtivo.m52_quant;
    $('valorRecebido').value          = new Number(oItemAtivo.m52_valor).toFixed(2);
    $('saldovalor').innerHTML         = new Number(oItemAtivo.saldovalor).toFixed(2);
    $('saldoitens').innerHTML         = oItemAtivo.saldoitens;
    $('unidadeentrada').value         = oItemAtivo.unidade;
    $('m52_codlanc').value            = oItemAtivo.m52_codlanc;
    $('iIndice').value                = oItemAtivo.iIndiceEntrada;
    $('sJson').value                  = oAjax.responseText;
    $('quantunid').value              = oItemAtivo.quantunidade;
    $('matmater').options.length      = 1;
    $('matmaterdescr').options.length = 1
    $('m78_matfabricante').value      = oItemAtivo.m78_matfabricante;
    $('m76_nome').value               = oItemAtivo.m76_nome.urlDecode();
    $('cc08_sequencial').value        = oItemAtivo.cc08_sequencial;
    $('cc08_descricao').value         = oItemAtivo.cc08_descricao.urlDecode();
    lSalvo                            = false;

//adicionada condição para habilitar o campo quantidade quando o item for controlado por quantidade
    if (oItemAtivo.pc01_servico == 't' && oItemAtivo.sServicoQuantidade == 'f') {

      if (iTipoControleCustos > 0) {
        $('centrodecusto').style.display  = "";
      }
      $('qtdeRecebido').disabled = true;

    } else {

      $('acertoQtdValor').disabled     = false;
      $('centrodecusto').style.display = "none";
      $('qtdeRecebido').disabled       = false;

    }

    if (oItemAtivo.aMateriaisEstoque.length > 0) {

      //popula o select com as informações dos itens já vinculadas ao material do estoque com o compras.
      for (var iInd = 0; iInd < oItemAtivo.aMateriaisEstoque.length; iInd++) {

        oOptions                 = new Option(oItemAtivo.aMateriaisEstoque[iInd].m63_codmatmater,
          oItemAtivo.aMateriaisEstoque[iInd].m63_codmatmater);
        oOptions.controlaEstoque = oItemAtivo.aMateriaisEstoque[iInd].m60_controlavalidade;
        oOptions.descr           = oItemAtivo.aMateriaisEstoque[iInd].m60_descr; //Propriedade Original da Requisiçaio com URLENCODE

        $('matmater').add(oOptions, null);

        var oOptions  = new Option(oItemAtivo.aMateriaisEstoque[iInd].m60_descr.urlDecode(),
          oItemAtivo.aMateriaisEstoque[iInd].m63_codmatmater);
        $('matmaterdescr').add(oOptions, null);

        if (oItemAtivo.m63_codmatmater == oItemAtivo.aMateriaisEstoque[iInd].m63_codmatmater) {

          $('matmater').value      = oItemAtivo.m63_codmatmater;
          //$('matmaterdescr').value = oItemAtivo.m60_descr.urlDecode();
          js_ProcCod_matmater('matmater','matmaterdescr');
        }

      }
      $('matmater').disabled      = false;
      $('matmaterdescr').disabled = false;
    }
    /*
     * Caso o indice de entrada do item for maior que 0,
     * significa que o item foi originado de um fracionamento.
     */
    if (oItemAtivo.iIndiceEntrada > 0) {

      $('doFracionar').disabled      = true;
      $('excluirFracionar').disabled = false;
      $('qtdeRecebido').disabled     = true;
      $('valorRecebido').disabled    = true;

    }
    $('qtdeRecebido').focus();
  }
  /**
   * grava as informações do material na sessao
   *
   */
  function js_saveMaterial(lFraciona) {

    if ($F('quantunid') == 0 || $F('quantunid') == '0') {
      $('quantunid').value = 1;
    }

    if ($F('sJson') == '') {
      return false;
    }

    if ( $('acertoQtdValor').value == 'cancela') {
      var lAcerta = true;
    } else {
      var lAcerta = false;
    }

    oObject        = $F('sJson');
    var oItemAtivo = eval( "("+oObject+")");

    if ( lAcerta ) {

      if ( !confirm('Este é uma lançamento de acerto, deseja confirmar a entrada?') ) {
        return false;
      }

      var nQtdRecebido = new Number($F('corrigeQtdeRecebido'));
      var nVlrRecebido = new Number($F('corrigeValorRecebido'));

    } else {

      var nQtdRecebido = new Number($F('qtdeRecebido'));
      var nVlrRecebido = new Number($F('valorRecebido'));

      if ( nQtdRecebido > oItemAtivo.saldoitens ) {
        alert('Quantidade Recebida com valor Inválido!');
        return false;
      }

      if ( nVlrRecebido > js_round(oItemAtivo.saldovalor,2) ) {
        alert('Valor Recebido com valor Inválido!');
        return false;
      }

    }

    if ( nQtdRecebido == 0 ) {
      alert('Quantidade Recebida com valor não informado!');
      return false;
    }

    if ( nVlrRecebido == 0 ) {
      alert('Valor Recebido com valor não informado!');
      return false;
    }

    //testamos se o material possui controle de lote/validade
    switch ($('matmater').options[$('matmater').selectedIndex].controlaEstoque) {

      //lote/validade Obrigatorio
      case '1' :

        if ($F('m77_lote') == '' || $F('m77_dtvalidade') == '') {

          alert('Lote e validade do Material devem ser preenchidos.');
          return false;
        }
        break;
      //lote/validade Obrigatorio nao Obrigatorio
      case '2':

        if ($F('m77_lote') == '' || $F('m77_dtvalidade') == '') {

          if (!confirm('Lote e validade do Material não foram preenchidos.\nContinuar assim mesmo?')) {
            return false;
          }
        }
        break;
    }
    /*
     * caso o usuário está fracionando o item, não podemos deixar ele incluir todo o saldo do item,
     * devemos deduzir do item anterior o total fracionado.
     * o usuário deve preencher o item de entrada.
     */
    if ( lFraciona ) {

      if ( nQtdRecebido > oItemAtivo.saldoitens ) {

        alert('Ao Fracionar, escolha uma quantidade menor que o Item Atual.');
        return false;

      }

      if ( nVlrRecebido > oItemAtivo.saldovalor ) {

        alert('Ao Fracionar, escolha um valor menor que o Item Atual.');
        return false;

      }

      if ($F('matmater') == "" ) {

        alert('Ao Fracionar, escolha um item de Entrada.');
        return false;

      }

      oItemAtivo.fraciona          = true;
      oItemAtivo.iIndiceDebitar    = $F('iIndice');
      oItemAtivo.quantidadeDebitar = nQtdRecebido;
      oItemAtivo.valorDebitar      = nVlrRecebido;
      oItemAtivo.saldoitens        = nQtdRecebido;
      oItemAtivo.saldovalor        = nVlrRecebido;
      oItemAtivo.e62_descr         = '';
      oItemAtivo.m60_descr         = '';
      oItemAtivo.iTotalFracionados = 0;
      oItemAtivo.pc01_descrmater   = encodeURIComponent($('matmater').options[$('matmater').selectedIndex].descr);
      oItemAtivo.checked           = " checked ";

    }

    oItemAtivo.m52_quant         = nQtdRecebido;
    oItemAtivo.m52_valor         = nVlrRecebido;
    oItemAtivo.cc08_sequencial   = $F('cc08_sequencial');
    oItemAtivo.cc08_descricao    = encodeURIComponent($F('cc08_descricao'));

    if (!lFraciona) {

      oItemAtivo.iIndiceEntrada  = $F('iIndice');
      oItemAtivo.fraciona        = false;
      oItemAtivo.e62_descr       = encodeURIComponent(oItemAtivo.e62_descr);
      oItemAtivo.pc01_descrmater = encodeURIComponent(oItemAtivo.pc01_descrmater);
      oItemAtivo.m60_descr       = encodeURIComponent(oItemAtivo.m60_descr);

    } else {

      oItemAtivo.iIndiceEntrada =  null;

    }
    oItemAtivo.m63_codmatmater   = $F('matmater');
    oItemAtivo.m78_matfabricante = $F('m78_matfabricante');
    oItemAtivo.m76_nome          = $F('m76_nome');
    oItemAtivo.quantunidade      = $F('quantunid');
    oItemAtivo.unidade           = $F('unidadeentrada');
    oItemAtivo.m77_lote          = $F('m77_lote');
    oItemAtivo.m77_dtvalidade    = $F('m77_dtvalidade');
    oItemAtivo.aMateriaisEstoque = '';

    var oJson = new Object();
    oJson.method       = "saveMaterial";
    oJson.m51_codordem = $F('m51_codordem');
    oJson.teste        = oItemAtivo.e62_descr;
    oJson.oMaterial    = oItemAtivo;
    oJson.iCodLanc     = $F('m52_codlanc');

    strJson = Object.toJSON(oJson);

    js_divCarregando("Aguarde, Salvado informações","msgBox");
    js_bloqueiaLiberaBotao(true);
    url         = 'mat4_matordemRPC.php';
    var oAjax   = new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json='+strJson,
        onComplete: js_retornoSaveMaterial
      }
    );


  }
  /**
   * Retorno da Requisição da pra salvar os itens .
   */

  function js_retornoSaveMaterial(oAjax) {

    js_removeObj("msgBox");
    js_bloqueiaLiberaBotao(false);
    var oRetorno = eval("("+oAjax.responseText+")");
    var iCodLanc = oRetorno.iCodLanc;
    if (oRetorno.status == 1) {

      lSalvo  = true;
      if (oRetorno.lFraciona) {
        js_send($("chk"+iCodLanc+'_0'), iCodLanc, 0);
      }
      js_getDadosEntrada(iCodLanc);

    } else if (oRetorno.status == 2){
      alert(oRetorno.mensagem.urlDecode());
    }

  }
  /**
   * busca as informações da entrada de itens do usuário;
   */
  function js_getDadosEntrada(iCodLanc) {

    if (typeof(iCodLanc) == 'undefined') {
      iCodLanc = '';
    }
    js_divCarregando("Aguarde, Efetuando consulta","msgBox");
    js_bloqueiaLiberaBotao(true);
    var strJson = '{"method":"getDadosEntrada","m51_codordem":"'+$F('m51_codordem')+'","marcar":"'+iCodLanc+'"}';
    $('dados').innerHTML    = '';
    $('pesquisar').disabled = true;
    url         = 'mat4_matordemRPC.php';
    var oAjax   = new Ajax.Request(
      url,
      {
        asynchronous: true,
        method: 'post',
        parameters: 'json='+strJson,
        onComplete: js_retornoGetDadosEntrada
      }
    );

  }

  /**
   * Retorno da Requisição da consulta dos itens da ordem.
   */
  function js_retornoGetDadosEntrada(oAjax) {

    js_removeObj("msgBox");
    js_bloqueiaLiberaBotao(false);
    var oJson            = eval("("+oAjax.responseText+")");
    $('dados').innerHTML = '';
    if (oJson.aItens.length > 0) {

      var sRow          = '';
      var nTotalLancado = new Number(0);
      for (var iItens = 0; iItens < oJson.aItens.length; iItens++) {

        with (oJson.aItens[iItens]) {

          iCodLinha  =  m52_codlanc+'_'+iIndiceEntrada;
          sClassName = " normal";
          if (m63_codmatmater == '') {
            sClassName = 'semMatmater';
          }
          sShowCheckbox = '';
          sShowTree     = "none";
          sClassTD      = "linhagrid";
          sClassCHK     = "";
          sDisabled     = "";
          sReturn       = "";
          if ((new Number(saldovalor).toFixed(3) == 0 || Number(saldoitens).toFixed(2) == 0)
            && iTotalFracionados == 0){

            sClassName = "disabled";
            sDisabled  = "disabled";
            checked    = "";
            sReturn    = " return false; ";

          }

          if (iIndiceEntrada > 0) {

            sShowCheckbox = "none";
            sShowTree     = "";
            sClassTD      = "fracionado";
            sClassCHK     = "";

          }
          if (oJson.aItens[iItens+1] && oJson.aItens[iItens+1].m52_codlanc != m52_codlanc ) {

            sImgSrc = "join";
          } else if (!oJson.aItens[iItens+1]) {
            sImgSrc = "join";
          } else {
            sImgSrc = "joinbottom";
          }
          sRow += "<tr style='cursor:default' ";
          sRow += "    class='"+sClassName+"' onclick=\""+sReturn+"js_send($('chk"+iCodLinha+"'),"+m52_codlanc+","+iIndiceEntrada+")\"";
          sRow += "    id='trchk"+iCodLinha+"'>";
          sRow += "  <td class='"+sClassCHK+"' style='text-align:right'>";
          sRow += "    <input type='checkbox' indice='"+iIndiceEntrada+"'  id='chk"+m52_codlanc+"_"+iIndiceEntrada+"' "+checked+" ";
          sRow += "    onClick='js_send(this,"+m52_codlanc+","+iIndiceEntrada+")'  value='"+m52_codlanc+"' "+sDisabled;
          sRow += "    class='chkmarca' style='height:11px;display:"+sShowCheckbox+";'>";
          sRow += "    <input type='hidden' id='iCodItem"+iCodLinha+"' value='"+e62_sequencial+"'>";
          sRow += "    <img src='imagens/tree/"+sImgSrc+".gif' style='display:"+sShowTree+"'>";
          sRow += "  </td>";

          if (!fraciona) {

            sRow += "   <td class='"+sClassTD+"'><a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;'";
            sRow += " href='#'>"+e60_codemp+"</a></td>";
            sRow += "  <td class='"+sClassTD+"' style='text-align:left' nowrap>";
            sRow += " <div style='overflow:hidden' id='descrmater"+iCodLinha+"'";
            sRow += " onmouseover='js_setAjuda(this.innerHTML,true)' onmouseOut='js_setAjuda(null,false)'>";
            sRow +=    pc01_codmater+"-"+pc01_descrmater.urlDecode()+"</div></td>";
            sRow += "  <td class='"+sClassTD+"' id='descritem"+iCodLinha+"' nowrap style='text-align:left;overflow:hidden'>";
            sRow += " <div style='overflow:hidden' onmouseover='js_setAjuda(this.innerHTML,true)' onmouseOut='js_setAjuda(null,false)'>";
            sRow += e62_descr.urlDecode()+"&nbsp;</div></td>";

          } else {

            sRow += "  <td class='"+sClassTD+"' colspan='3' id='descrmater"+iCodLinha+"'";
            sRow += "      style='text-align:left' nowrap><div style='overflow:hidden'";
            sRow += " onmouseover='js_setAjuda(this.innerHTML,true)' onmouseOut='js_setAjuda(null,false)'>";
            sRow +=    pc01_descrmater.urlDecode()+"</div></td>";

          }
          sRow += "  <td class='"+sClassTD+"' id='vlrUn"+iCodLinha+"' style='text-align:right'>"+js_formatar(e62_vlun,'f', iNumeroDecimais)+"</td>";
          sRow += "  <td class='"+sClassTD+"' id='saldoItem"+iCodLinha+"' style='text-align:right'>"+saldoitens+"</td>";
          sRow += "  <td class='"+sClassTD+"' id='saldovalor"+iCodLinha+"'  style='text-align:right'>"+new Number(m52_valor).toFixed(2)+"</td>";
          sRow += "  <td style='text-align:left'>";
          sRow += "    <img class='selecionado'id='"+iCodLinha+"' style='display:none' src='imagens/seta_direita.gif'>";
          sRow  += "  </td>";
          sRow += "</tr>";
          if (checked.trim() != '') {

            nTotalLancado += new Number(m52_valor).toFixed(2);
          }
        }
      }
      sRow += "<tr style='height:auto'><td>&nbsp;</td></tr>";
      $('lancado').value = new Number(nTotalLancado).toFixed(2);
      if ($F('e70_valor') != '') {
        $('alancar').value = new Number(new Number($F('e70_valor')) - new Number(nTotalLancado)).toFixed(2);
      }
      $('pesquisar').disabled = false;
      $('dados').innerHTML    = sRow;
      setSelecionado(oJson.marcar+"_0");
    }
  }

  function js_acertaQtdValor(sTipo) {

    if ( sTipo == 'acerta') {
      $('qtdCorrigido').style.display   = '';
      $('valorCorrigido').style.display = '';
      $('acertoQtdValor').value         = 'cancela';
      $('acertoQtdValor').innerHTML     = 'Cancela Acerto';
      $('qtdeRecebido').disabled        = true;
      $('valorRecebido').disabled       = true;
      $('doFracionar').disabled         = true;
    } else {
      $('qtdCorrigido').style.display   = 'none';
      $('valorCorrigido').style.display = 'none';
      $('acertoQtdValor').innerHTML     = 'Acerto Qtd/Valor';
      $('acertoQtdValor').value         = 'acerta';
      $('qtdeRecebido').disabled        = false;
      $('valorRecebido').disabled       = false;
      $('doFracionar').disabled         = false;
    }

  }


  /**
   * Desfaz o fracionamendo selecionado.
   */
  function js_cancelarFracionamento() {


    oObject        = $F('sJson');
    js_divCarregando("Aguarde, cancelando fracionamento","msgBox");
    js_bloqueiaLiberaBotao(true);
    var oItemAtivo = eval( "("+oObject+")");
    var strJson  = '{"method":"cancelarFracionamento","m51_codordem":"'+$F('m51_codordem')+'",';
    strJson += '"iCodLanc":'+oItemAtivo.m52_codlanc+',"iIndice":'+oItemAtivo.iIndiceEntrada+'}';
    url     = 'mat4_matordemRPC.php';
    oAjax   = new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json='+strJson,
        onComplete: js_retornoCancelarFracionamento
      }
    );


  }
  /**
   * Retorno da Requisição do cancelamendo do fracionamento dos itens.
   */
  function js_retornoCancelarFracionamento(oAjax) {

    js_removeObj("msgBox");
    js_bloqueiaLiberaBotao(false);
    js_getDadosEntrada();
    js_limpaInfoItem();

  }
  /**
   * Pesquisa a ordem de compra.
   */
  function js_pesquisa_matordem(mostra){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matordem','func_matordement.php?lExibeAutomatica=false&funcao_js=parent.js_mostramatordem|m51_codordem','Pesquisa',true);
  }
  function js_mostramatordem(chave,erro){
    document.form1.m51_codordem.value = chave;
    if(erro==true){
      document.form1.m51_codordem.value = '';
      document.form1.m51_codordem.focus();
    } else {
      js_consultaOrdem(chave);
      db_iframe_matordem.hide();
    }
  }
  /**
   * Abre a lookup para cadastro de um novo item.
   */
  function js_novomatmater(){

    oObject        = $F('sJson');
    var oItemAtivo = eval( "("+oObject+")");
    cod            = oItemAtivo.pc01_codmater;
    numemp         = oItemAtivo.e60_numemp;
    sequen         = oItemAtivo.e62_sequencial;
    js_OpenJanelaIframe('CurrentWindow.corpo','iframe_material',
      'mat1_matmater011.php?m63_codpcmater='+cod+'&numemp='+numemp+'&sequen='+sequen+'&lLotes=1',
      'Incluir Item de Entrada Novo',true);
  }

  /**
   * Marca o item como selecionado.
   */
  function setSelecionado(id) {

    var aItens  = getElementsByClass("selecionado");
    for (i = 0; i < aItens.length; i++) {

      if (aItens[i].id != id) {
        aItens[i].style.display='none';
      } else {
        aItens[i].style.display='';
      }
    }
  }

  function getElementsByClass( searchClass, domNode, tagName) {

    if (domNode == null) domNode = document;

    if (tagName == null) tagName = '*';

    var el = new Array();
    var tags = domNode.getElementsByTagName(tagName);
    var tcl = " "+searchClass+" ";
    for (i=0,j=0; i<tags.length; i++) {
      var test = " " + tags[i].className + " ";
      if (test.indexOf(tcl) != -1)
        el[j++] = tags[i];
    }
    return el;
  }

  function js_calculaQuant() {

    oObject        = $F('sJson');
    var oItemAtivo = eval( "("+oObject+")");
    // adicionada condição para habilitar o calculo quando o item for controlado por quantidade
    if (oItemAtivo.pc01_servico == "f" || oItemAtivo.sServicoQuantidade == 't') {

      nValorTotal              =  new Number(new Number(oItemAtivo.m52_vlruni) * new Number($F('qtdeRecebido')));
      $('valorRecebido').value = nValorTotal.toFixed(2);

    }

  }

  function js_calculaValor() {

    oObject        = $F('sJson');
    var oItemAtivo = eval( "("+oObject+")");

    var nVlrUni    = new Number(oItemAtivo.m52_vlruni);
    var nVlrRec    = new Number($('valorRecebido').value);
    var nQtdRec    = new Number( nVlrRec / nVlrUni) ;

    /**
     Validação se ordem de compra é serviço = t ou quantidade = f

     pc01_servico == "f"  = item normal como caneta trata por quantidade ou valor, calculando de acordo
     pc01_servico == "t" && sServicoQuantidade == "t" = SERVICO que pode ser tratado como item normal por quantidade e valor calculando de acordo
     pc01_servico == "t" && sServicoQuantidade == "f" = significa que a quantidade do serviço nao MEXE, sempre 1 e trata a quantidade recebida pelo VALOR TOTAL
     */

    if ( (oItemAtivo.pc01_servico == "f") || ( oItemAtivo.pc01_servico == "t" && oItemAtivo.sServicoQuantidade == "t" )  ) {

      $('qtdeRecebido').value = nQtdRec.toFixed(2);

    }

  }


  /**
   * Confirma a entrada dos itens no estoque.
   * faz algumas verificações antes de realmente fazer o envio.
   */
  function js_confirmaEntrada() {

    if ( !confirm('Confirma a entrada dos Itens Selecionados no estoque?')) {
      return false;
    }

    //Número da nota, é obrigatorio
    var sNumeroNota = $F('e69_numero').trim();
    //Data da nota deve estar preenchida
    if ($F('e69_dtnota') == '') {

      alert('Campo Data da Nota e de preenchimento obrigatorio.');
      $('e69_dtnota').focus();
      return false;
    }

    /**
     * valor da Nota não pode ser maior que o valor da ordem de compra.
     */
    if ($F('e70_valor') == '' || $F('e70_valor') <= 0
      || new Number($F('e70_valor')) > new Number($F('m51_valortotal'))) {

      alert('Valor da nota Inválido!.\nVerifique');
      $('e70_valor').focus();
      return false;

    }

    /*
     * pegamos as notas selecionadas pelo usuário e enviamos para a classe fazer validações necessárias
     */
    var aItens = js_getElementbyClass(form1, "chkmarca", "checked==true");
    if (aItens.length == 0) {

      alert('Selecione ao menos um item para dar entrada');
      return false;

    }
    var sJsonItens = "";
    sVirgula       = "";
    var iSemPai    = false; //verifica se a itens fracionados sem o item original marcado.
    var aItensSelecionados = [];
    for (var i = 0; i < aItens.length; i++) {

      /*
       * pegamos o indice do item onde  a segunda posicao indica o nivel do item
       * caso 0 , e o nivel mais alto, onde temos a informação do item da ordem de compra
       * todos os indices maiores que 0, sao fracionamentos.
       */

      iIndice     = aItens[i].id.split("_");
      if (iIndice[1] > 0) {

        /*
         * procuramos pelo item nao fracionado, isso é o item original da ordem de compra
         * somente marcamos o item fracionado se o item de origem também estiver marcado;
         */
        if ($(iIndice[0]+"_0").checked == false) {

          iSemPai = true;
          continue ;
        }
      }

      aItensSelecionados.push( { "iCodLanc" : aItens[i].value, 'iIndiceEntrada' : iIndice[1] } );
    }
    if (iSemPai) {
      var sMsg  = "Há Itens que foram fracionados que  nao estão selecionados.\n";
      sMsg     += "Para esses Itens, não sera Efetuada  a entrada no estoque.\nContinuar?";
      if (!confirm(sMsg)) {
        return false;
      }
    }

    /**
     * Verificamos se o controle do pit está ativo
     * Caso o controle do pit está ativo, o tipo do documento fiscal é obrigatorio.
     */
    var iTipoDocumentoFiscal = $F('e11_tipodocumentofiscal');
    var iCfop                = $F('e11_cfop');
    var iInscrSubstituto     = $F('e11_inscricaosubstitutofiscal');
    var nBaseCalculoICMS     = $F('e11_basecalculoicms');
    var nValorICMS           = $F('e11_valoricms');
    var nBaseCalculoSubst    = $F('e11_basecalculosubstitutotrib');
    var nValorICMSSubst      = $F('e11_valoricmssubstitutotrib');
    var sSerieFiscal         = $F('e11_seriefiscal');
    if (iControlaPit == 1) {
      /**
       * Caso o documento fiscal for do tipo 50, devemos obrigar o usuário
       * a selecionar uma cfop
       */
      if (iTipoDocumentoFiscal == '50') {

        if (iCfop == "") {

          alert('Campo CFOP Deve ser preenchido!');
          js_abreNotaExtra();
          return false;

        }
      }
    }

    /*
     * Faz a requisição para a classe realizar as entradas do estoque.
     */

    if ($F('m53_obs').trim() == "") {
      alert("Campo observação é obrigatório.");
      return false
    }
    js_bloqueiaLiberaBotao(true);
    $('confirmar').disabled = true;
    $('pesquisar').disabled  = true;


    var sObservacao             = encodeURIComponent(tagString($F('m53_obs')));
    var sNumero                 = encodeURIComponent(tagString(sNumeroNota));
    var sProcessoAdministrativo = encodeURIComponent(tagString($F('e04_numeroprocesso')));
    var sLocalRecebimento       = encodeURIComponent(tagString($F('e69_localrecebimento')));

    var oParametro = {
      'method':"confirmarEntrada",
      'm51_codordem': $F('m51_codordem'),
      'dtVencimento': $F('e69_dtvencimento'),
      'sLocalRecebimento': sLocalRecebimento,
      'sNumero': sNumero,
      'dtDataNota': $F('e69_dtnota'),
      'e04_numeroprocesso': sProcessoAdministrativo,
      'oInfoNota': {
        'iCfop' : iCfop,
        'iTipoDocumentoFiscal' : iTipoDocumentoFiscal,
        'iInscrSubstituto' : iInscrSubstituto,
        'nBaseCalculoICMS' : nBaseCalculoICMS,
        'nValorICMS' : nValorICMS,
        'nBaseCalculoSubst' : nBaseCalculoSubst,
        'nValorICMSSubst' : nValorICMSSubst,
        'sSerieFiscal' : sSerieFiscal
      },
      'dtRecebeNota': $F('e69_dtrecebe'),
      'sObs': sObservacao,
      'nValorNota': $F('e70_valor'),
      'aItens': aItensSelecionados
    };

    new AjaxRequest('mat4_matordemRPC.php', oParametro, js_retorndoConfirmaEntrada).execute();
  }
  /**
   * Função de retorno da requisicção da confirmação da entrada da ordem de compra
   * no estoque
   */
  function js_retorndoConfirmaEntrada(oRetorno) {

    js_bloqueiaLiberaBotao(false);
    $('confirmar').disabled = false;
    $('pesquisar').disabled = false;
    if (oRetorno.status == 1) {

      alert(oRetorno.mensagem.urlDecode());
      js_reset();
      js_pesquisa_matordem(true);
      location.href='mat4_entraMaterialLote001.php?lAbrirPesquisa=true';

    } else {
      alert(oRetorno.mensagem.urlDecode());
    }
  }

  /**
   * seta o valor a lancar.
   */
  function js_setValorAlancar() {

    if ($F('e70_valor') != '') {

      var nValoraLancar  = new Number($F('e70_valor')) - new Number($F('lancado'));
      if (nValoraLancar < 0) {
        nValoraLancar = '';
      }
      $('alancar').value = nValoraLancar;
    }

  }
  /**
   * Atualiza os valores lancados.
   */
  function js_setValoresLancados(obj, iCodLanc, iIndice) {

    var nValorLancado  = new Number ($F('lancado'));
    var nDeduzir       = new Number ($('saldovalor'+iCodLanc+'_'+iIndice).innerHTML);

    if (obj.checked) {
      $('lancado').value = (nValorLancado + nDeduzir).toFixed(2);
    } else {
      $('lancado').value = (nValorLancado - nDeduzir).toFixed(2);
    }
    js_setValorAlancar();

  }

  $('quantunid').observe("change", function(){

    if ($('quantunid').value == 0) {
      $('quantunid').value = 1;
    }
  });

  function js_reset() {

    $('dados').innerHTML = '';
    document.form1.reset();

  }
  /**
   * cria listeners para teclas de atalho.
   * ALT+S (salva as informações do item ativo)
   * ALT+F (fraciona o item ativo)
   */
  window.document.captureEvents(Event.KEYPRESS);

  testar = function (event) {

    var teclaPress = document.all ? event.keyCode : event.which;
    if (teclaPress == 18) {
      teclaModi = 18;
    }
  }
  testar2 = function (event) {
    teclaAtalho = document.all ? event.keyCode : event.which;
    if ( teclaModi == 18) {
      if (teclaAtalho == 115) {

        js_calculaQuant();
        js_saveMaterial();
        teclaModi = '';
      } else if (teclaAtalho == 102) {

        js_calculaQuant();
        js_saveMaterial(true);
        teclaModi = '';

      }
    }
  }
  window.document.onkeydown  = testar;
  window.document.onkeypress = testar2;

  /**
   * função para marcar os itens ao clicar no "M" do header da grid
   */
  function js_marca(){


    $('lancado').value = "";
    obj = document.getElementById('mtodos');
    if (obj.checked){
      obj.checked = false;
    }else{
      obj.checked = true;
    }
    var nValorLancado = new Number($F('lancado'));
    itens = js_getElementbyClass(form1,'chkmarca');
    for (i = 0;i < itens.length;i++){
      if (itens[i].disabled == false) {

        var sId = itens[i].id.replace('chk','');
        var nValorLinha = new Number($('saldovalor'+sId).innerHTML);
        if (obj.checked == true){

          itens[i].checked=true;
          nValorLancado += nValorLinha;
          js_marcaItensSession(true);

        } else {

          itens[i].checked=false;
          nValorLancado -= nValorLinha;
          js_marcaItensSession(false);

        }
      }
    }
    if (nValorLancado > 0) {
      $('lancado').value = nValorLancado.toFixed(2);
    }
    js_setValorAlancar();
  }

  function js_pesquisam78_matfabricante(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matfabricante','func_matfabricante.php?funcao_js=parent.js_mostramatfabricante1|m76_sequencial|m76_nome','Pesquisa',true);
    }else{
      if(document.form1.m78_matfabricante.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matfabricante','func_matfabricante.php?pesquisa_chave='+document.form1.m78_matfabricante.value+'&funcao_js=parent.js_mostramatfabricante','Pesquisa',false);
      }else{
        document.form1.m76_nome.value = '';
      }
    }
  }
  function js_mostramatfabricante(chave,erro){
    document.form1.m76_nome.value = chave;
    if(erro==true){
      document.form1.m78_matfabricante.focus();
      document.form1.m78_matfabricante.value = '';
    }
  }
  function js_mostramatfabricante1(chave1,chave2){
    document.form1.m78_matfabricante.value = chave1;
    document.form1.m76_nome.value = chave2;
    db_iframe_matfabricante.hide();
  }

  function js_pesquisae11_cfop(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_cfop',
        'func_cfop.php?funcao_js=parent.js_mostracfop1|e10_sequencial|e10_descricao|e10_cfop',
        'Pesquisa CFOP',true);
    }else{
      if($('e10_cfop').value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
          'db_iframe_cfop',
          'func_cfop.php?pesquisa_chave='+$('e10_cfop').value+'&funcao_js=parent.js_mostracfop',
          'Pesquisa CFOP',false);
      }else{
        $('e10_descricao').value = '';
      }
    }
  }
  function js_mostracfop(chave,chave2, erro){

    $('e10_descricao').value = chave;
    $('e11_cfop').value      = chave2;
    if(erro==true){
      $('e10_cfop').focus();
      $('e10_cfop').value = '';
    }
  }
  function js_mostracfop1(chave1,chave2, chave3){

    $('e11_cfop').value = chave1;
    $('e10_descricao').value = chave2;
    $('e10_cfop').value = chave3;
    db_iframe_cfop.hide();

  }
  function js_limpaInfoItem() {

    $('qtdeRecebido').value           = '';
    $('valorRecebido').value          = '';
    $('matmater').options.length      = 0;
    $('matmaterdescr').options.length = 0;
    $('m76_nome').value               = '';
    $('m78_matfabricante').value      = '';;
    $('m77_lote').value               = '';
    $('m77_dtvalidade').value         = '';
    $('doFracionar').disabled         = true;
    $('sJson').value                  = '';
    lSalvo                            = true;
  }

  function js_bloqueiaLiberaBotao(lDisabled) {

    $('doFracionar').disabled = lDisabled;
    $('pesquisar').disabled   = lDisabled;
    $('confirmar').disabled   = lDisabled;
    $('salvar').disabled      = lDisabled;

  }
  function js_escolherMater(){

    if (oItemAtivo) {

      var iCodpcMater = oItemAtivo.pc01_codmater;
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcmatmater',
        'func_matmaterentoc.php?codpcmater='+iCodpcMater+'&funcao_js=js_mostramatmater|m60_codmater&lLotes=1',
        'Escolher Material',true);
    }
  }

  function js_setAjuda(sTexto,lShow) {

    if (lShow) {

      el =  $('gridItens');
      var x = 0;
      var y = el.offsetHeight;
      while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

        x += el.offsetLeft;
        y += el.offsetTop;
        el = el.offsetParent;

      }
      x += el.offsetLeft;
      y += el.offsetTop;
      $('ajudaItem').innerHTML     = sTexto;
      $('ajudaItem').style.display = '';
      $('ajudaItem').style.top     = y+10;
      $('ajudaItem').style.left    = x;

    } else {
      $('ajudaItem').style.display = 'none';
    }
  }

  function js_marcaItensSession(lMarcar) {

    js_divCarregando("Aguarde, selecionando itens.","msgBox");
    js_bloqueiaLiberaBotao(true);
    $('confirmar').disabled = true;
    $('pesquisar').disabled  = true;
    var sJson  = '{"method":"marcarItensSession","m51_codordem":"'+$F('m51_codordem')+'","lMarcar":'+lMarcar+'}';

    url     = 'mat4_matordemRPC.php';
    oAjax   = new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json='+sJson,
        onComplete: js_retornoMarcaItens
      }
    );

  }

  function js_adicionaCentroCusto() {

    var iOrigem  = 2;
    var sUrl     = 'iOrigem='+iOrigem+'&iCodItem='+$F('matmater')+'&iCodigoDaLinha='+$F('matmater');
    sUrl        += '&iCodigoDepto=<?echo db_getsession("DB_coddepto")?>';
    if ($F('matmater')) {

      js_OpenJanelaIframe('',
        'db_iframe_centroCusto',
        'cus4_escolhercentroCusto.php?'+sUrl,
        'Centro de Custos',
        true,
        '25',
        '1',
        (document.body.scrollWidth-10),
        (document.body.scrollHeight-25)
      );
    }


  }

  function js_abreNotaExtra() {

    if ($F('e11_tipodocumentofiscal') == 50) {

      if (!$('wnddadosnota')) {
        js_createJanelaDadosComplentar();
      }

      windowAuxiliarNota.show(100,300);
      $('dadosnotacomplementar').style.display='';
      $('e10_cfop').focus();
    } else {
      if($('wnddadosnota')){
        $('dadosnotacomplementar').style.display='none';
        windowAuxiliarNota.hide();
      }

    }

    if($F('e11_tipodocumentofiscal') == 0){
      $('e69_numero').readOnly          = true;
      $('e69_numero').style.background  = "#DEB887";
      $('e69_numero').value             = "";
    }else{
      $('e69_numero').readOnly           = false;
      $('e69_numero').style.background   = "#FFFFFF";

    }
    js_validarNumeroNota()

  }

  function js_createJanelaDadosComplentar() {

    windowAuxiliarNota = new windowAux('wnddadosnota', 'DadosComplementares', 600, 500);
    windowAuxiliarNota.setObjectForContent($('divDadosNotaAux'));
    $('dadosnotacomplementar').style.display='';

  }

  function js_completaCustos(iCodigo, iCriterio, iDescr) {

    $('cc08_sequencial').value = iCriterio;
    $('cc08_descricao').value  = iDescr;
    db_iframe_centroCusto.hide();

  }
  function js_retornoMarcaItens() {

    js_removeObj("msgBox");
    js_bloqueiaLiberaBotao(false);
  }

  function js_validarNumeroNota() {
    if ($F('e11_tipodocumentofiscal') == 50) {
      $('e69_numero').value = '';
      $('e69_numero').observe("keypress", function (event) {
        var lValidar = js_mask(event,"0-9");
        if (!lValidar) {
          event.stopPropagation();
          event.preventDefault();
          return false;
        } else {
          return true;
        }
      });
    } else {
      $('e69_numero').stopObserving("keypress");
    }
  }


  /**
   * funcao para verificar se o numero da nota digitada, ja possui alguma entrada
   e informar o numero do empenho caso exista.
   */
  function js_verificaNota(sNota){

    url     = 'mat4_matordemRPC.php';

    var oParametros            = new Object();
    var sNota                  =  sNota;
    oParametros.method         = 'verificaNota';
    oParametros.sNota          = encodeURIComponent(tagString(sNota));
    oParametros.iCgmFornecedor = $F('m51_numcgm');
    oParametros.m51_codordem   = "";

    var oAjaxLista  = new Ajax.Request(url,
      {method: "post",
        parameters:'json='+Object.toJSON(oParametros),
        onComplete: js_retornoVerificaNota
      });
  }

  function js_retornoVerificaNota(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {

      alert("A nota fiscal selecionada, já possui o(s) empenho(s): " + oRetorno.sEmpenho + " vinculado(s).");
    }

  }

  --></script>
<?php
echo "<script>js_consultaOrdem({$m51_codordem});
  document.form1.dtjs_e69_dtnota.style.display   = 'none';
  document.form1.dtjs_e69_dtrecebe.style.display = 'none';
  \$('matmater').style.width='80px';
  lSalvo    = true;
  teclaModi = '';
</script>";
?>
