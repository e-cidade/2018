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
$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e50_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("e82_codord");
$clrotulo->label("e87_descgera");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("cc31_classificacaocredores");
$dados="ordem";
require_once(modification("std/db_stdClass.php"));
$iTipoControleRetencaoMesAnterior = 0;
$lUsaData    = true;
$aParametrosEmpenho = db_stdClass::getParametro("empparametro",array(db_getsession("DB_anousu")));
if (count($aParametrosEmpenho) > 0) {

  $iTipoControleRetencaoMesAnterior = $aParametrosEmpenho[0]->e30_retencaomesanterior;
  $lUsaData = $aParametrosEmpenho[0]->e30_usadataagenda=="t"?true:false;

}
?>
<script>
  function js_mascara(evt){
    var evt = (evt) ? evt : (window.event) ? window.event : "";

    if((evt.charCode >46 && evt.charCode <58) || evt.charCode ==0){//8:backspace|46:delete|190:.
      return true;
    }else{
      return false;
    }
  }
</script>
<style type="text/css">
  input[type=checkbox], input[type=radio] {
    vertical-align: middle;
  }
</style>
<BR><BR>

<form name="form1" method="post" action="">
  <center>
    <table  border =0 style='width:90%'>
      <tr>
        <td>
          <fieldset>
            <legend>
              <a  id='esconderfiltros' style="-moz-user-select: none;cursor: pointer">
                <b>Opções</b>
                <img src='imagens/setabaixo.gif' id='togglefiltros' border='0'>
              </a>
            </legend>
            <table width="100%">
              <tr>
                <td width="35%" valign="top">
                  <fieldset class='filtros'>
                    <legend>
                      <b>Filtros</b>
                    </legend>
                    <table border="0" align="left" >

                      <tr>
                        <td nowrap title="<?=@$Te82_codord?>">
                          <?db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",$db_opcao);  ?>
                        </td>
                        <td nowrap colspan="3">
                          <?
                          db_input('e82_codord',10,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord(false);'");
                          ?>
                          <?
                          db_ancora("<b>até:</b>","js_pesquisae82_codord02(true);",$db_opcao);
                          ?>
                          <?
                          db_input('e82_codord2',10,$Ie82_codord,true,'text',$db_opcao,
                                   "onchange='js_pesquisae82_codord02(false);'","e82_codord02");
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td  nowrap title="<?=$Te60_numemp?>">
                          <?
                          db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);
                          ?>
                        </td>
                        <td nowrap>
                          <input name="e60_codemp" id='e60_codemp'
                                 title='<?=$Te60_codemp?>' size="10" type='text'  onKeyPress="return js_mascara(event);" >
                        </td>
                        <td>
                          <b>Recursos:</b></td>
                        <td align="left">
                          <?
                          if (!isset($recursos)){
                            $recursos = "false";
                          }
                          $ar = array("false"=>"Vinculados","true"=>"Todos");
                          db_select("recursosvinculados",$ar,true,1,"style='width:100%'");
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <b>Data Inicial:</b>
                        </td>
                        <td nowrap>
                          <?
                          db_inputdata("dataordeminicial",null,null,null,true,"text", 1);
                          ?>
                        </td>
                        <td>
                          <b>Data Final:</b>
                        </td>
                        <td nowrap align="">
                          <?
                          db_inputdata("dataordemfinal",null,null,null,true,"text", 1);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap title="<?=@$Tz01_numcgm?>">
                          <?
                          db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
                          ?>
                        </td>
                        <td  colspan='4' nowrap>
                          <?
                          db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
                          db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
                          ?>
                        </td>
                      </tr>
                      <tr nowrap>
                        <td nowrap title="<?=@$To15_codigo?>">
                          <? db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec(true);",$db_opcao); ?>
                        </td>
                        <td colspan=3 nowrap>
                          <?
                          db_input('o15_codigo',10,$Io15_codigo,true,'text',$db_opcao," onchange='js_pesquisac62_codrec(false);'");
                          db_input('o15_descr',40,$Io15_descr,true,'text',3,'');
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap>
                          <b>Conta pagadora padrão:</b>
                        </td>
                        <td colspan=3 nowrap>
                          <?php
                          $sWhere = "";
                          /* [Extensão] - Filtro da Despesa */


                          $sSqlBuscaContaPagadora =
                            $clempagetipo->sql_query(
                              null,
                              "e83_conta, e83_codtipo as codtipo,
                              e83_descr, c61_codigo ",
                              "e83_descr",
                              $sWhere
                            );

                          $result05  = $clempagetipo->sql_record($sSqlBuscaContaPagadora);
                          $numrows05 = $clempagetipo->numrows;
                          $arr['0']="Nenhum";
                          for ($r = 0; $r < $numrows05; $r++) {
                            db_fieldsmemory($result05,$r);
                            $arr[$codtipo] = "{$e83_conta} - {$e83_descr} - {$c61_codigo}";

                          }
                          $e83_codtipo ='0';
                          db_select("e83_codtipo",$arr,true,1,"onchange='js_setContaPadrao(this.value);' style='width:26em'");
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap>
                          <b>Forma de Pagamento padrão:</b></td>
                        <td  nowrap>
                          <?
                          $rsFormaPagamento  = $clempageforma->sql_record($clempageforma->sql_query(null));
                          $iNumRowsPagamento = $clempageforma->numrows;
                          $aFormaPagamento['0']="NDA";
                          for ($r = 0; $r < $iNumRowsPagamento; $r++) {

                            $oFormaPagamento = db_utils::fieldsMemory($rsFormaPagamento, $r);
                            $aFormaPagamento[$oFormaPagamento->e96_codigo] = $oFormaPagamento->e96_descr;

                          }
                          $e96_codigo ='0';
                          db_select("e96_codigo",$aFormaPagamento,true,1,"onchange='js_setFormaPadrao(this.value);' style='width:10em'");
                          ?>
                        </td>

                        <td nowrap="nowrap">
                          <strong>Processo Administrativo:</strong>
                        </td>

                        <td>
                          <?php  db_input('e03_numeroprocesso',10,null,true,'text',1,null,null,null,null,15); ?>
                        </td>

                      </tr>
                      <tr>
                        <td>
                          <b>Data de Pagamento: </b>
                        </td>
                        <td colspan='1'>
                          <?
                          if ($lUsaData) {

                            $data = explode("-",date("d-m-Y",DB_getsession("DB_datausu")));
                            db_inputdata("e42_dtpagamento", $data[0],$data[1],$data[2],true,"text", 1);

                          } else {
                            db_inputdata("e42_dtpagamento",null,null,null,true,"text", 1);
                          }
                          ?>
                        </td>
                        <td>
                          <b>
                            <? db_ancora("<b>OP auxiliar:</b>","js_pesquisae42_sequencial(true);",$db_opcao);  ?>
                          </b>
                        </td>
                        <td>
                          <input type='text' size="10" id='e42_sequencial'
                                 onchange='js_pesquisae42_sequencial(false);' name='e42_sequencial'>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap="nowrap">
                          <label for="codigo_classificacao">
                            <?php db_ancora($Lcc31_classificacaocredores, "oPesquisa.classificacao_credor.busca(true)", 1); ?>
                          </label>
                        </td>
                        <td colspan="3">
                          <?php
                          $Scodigo_classificacao = $Scc31_classificacaocredores;
                          db_input('codigo_classificacao', 10, 1, true, 'text', 1, 'onchange="oPesquisa.classificacao_credor.busca(false)"');
                          db_input('descricao_classificacao', 40, 1, true, 'text', 3);
                          ?>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <label class="bold" for="data_vencimento_inicial">Data de Vencimento:</label>
                        </td>
                        <td colspan="3" nowrap>
                          <?php db_inputdata("data_vencimento_inicial", null, null, null, true, "text", 1); ?>
                          <label class="bold" for="data_vencimento_final">até</label>
                          <?php db_inputdata("data_vencimento_final", null, null, null, true, "text", 1); ?>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <b>Ordens Autorizadas:</b>
                        </td>
                        <td colspan='1' nowrap>
                          <?
                          if (db_permissaomenu(db_getsession("DB_anousu"),39,6956) == "true") {

                            $aAutorizadas = array(
                              1 => "Ambas",
                              2 => "Autorizadas",
                              3 => "Não Autorizadas",
                            );
                          } else {

                            $aAutorizadas = array(
                              2 => "Autorizadas",
                            );

                          }
                          db_select("ordensautorizadas",$aAutorizadas,true,1);
                          ?>
                        </td>
                        <td colspan='2'>
                          <input type='checkbox' id='emitirordemauxiliar'>
                          <label for='emitirordemauxiliar'><b>Emitir Ordem Auxiliar</b></label>
                        </td>
                      </tr>
                      <tr>
                        <td valign="top"><b>Ordenar:</b></td>
                        <td valign="top">
                          <?
                          $x = array("e82_codord"=>"Ordem",
                                     "e60_numemp"=>"Empenho",
                                     "cgm.z01_nome"=>"Credor",
                                     "o15_codigo"=>"Recurso"
                          );
                          db_select("orderby",$x,true,1);
                          ?>
                        </td>
                        <td colspan='2'>
                          <input type='checkbox' id='efetuarpagamento' onclick="js_showAutenticar(this)" />
                          <label for='efetuarpagamento'><b>Efetuar Pagamento</b></label><br>
             <span id='showautenticar' style='visibility:hidden'>
               <input type="checkbox"  id='autenticar' />
               <label for="autenticar"><b>Autenticar</b></label>
             </span>
             <span id='showreemissao' style='visibility:hidden'>
               <input type="checkbox"  id='reemisaoop' onclick="js_reemissaoOP(this);">
               <label for="reemisaoop" ><b>Reemitir OP</b></label>
             </span>
                        </td>
                      </tr>
                    </table>
                  </fieldset>
                </td>

                <td rowspan="1" valign="top" height="100%">
                  <fieldset class='filtros'>
                    <legend><b>Saldos da Conta</b></legend>
                    <table>
                      <tr>
                        <td style='color:blue' id='descrConta' colspan='4'>
                        </td>
                      </tr>
                      <tr>
                        <td valign='top'>
                          <b>Tesouraria:</b>
                        </td>
                        <td style='text-align:right'>
                          <pre>(+)</pre>
                        </td>
                        <td valign='top'>
                          <?
                          db_input("saldotesouraria",15,null,true,"text",3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td valign='top'>
                          <b>Movimentos:</b>
                        </td>
                        <td style='text-align:right'>
                          <pre>(-)</pre>
                        </td>
                        <td valign='top'>
                          <?
                          db_input("totalcheques",15,null,true,"text",3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td valign='top'>
                          <b>Disponível:</b>
                        </td>
                        <td style='text-align:right' valign="">
                          <pre>(=)</pre>
                        </td>
                        <td valign='top'>
                          <?
                          db_input("saldoatual",15,null,true,"text",3);
                          ?>
                        </td>
                      </tr>
                    </table>
                  </fieldset>
                  <fieldset class='filtros'>
                    <legend>
                      <b>Op Auxiliar</b>
                    </legend>
                    <table>
                      <tr>
                        <td nowrap>
                          <b>
                            <? db_ancora("<b>OP auxiliar:</b>","js_pesquisae42_sequencialmanutencao(true);",$db_opcao);  ?>
                          </b>
                        </td>
                        <td>
                          <input type='text' size="10" id='e42_sequencialmanutencao'
                                 onchange='js_pesquisae42_sequencialmanutencao(false);' name='e42_sequencialmanutencao' />
                        </td>
                        <td>
                          <b>Data:</b>
                        </td>
                        <td colspan='1'>
                          <?
                          db_inputdata("e42_dtpagamentomanutencao", null, null,null,true,"text", 3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="4" nowrap>
                          <input type="radio" id="opmanutencaonda" name="opmanutencao" checked="checked" / >
                          <label for="opmanutencaonda" >NDA</label><br>
                          <input type="radio" id="opmanutencaoincluir" name="opmanutencao" />
                          <label for="opmanutencaoincluir">Incluir Movimentos na OP selecionada</label><br>
                          <input type="radio" id="opmanutencaoexcluir" name="opmanutencao" />
                          <label for="opmanutencaoexcluir">Excluir Movimentos na OP selecionada</label>
                        </td>
                      </tr>
                    </table>
                  </fieldset>
                </td>
                <td width='20%'>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan='4' style='text-align: center'>
          <fieldset><legend><b>Ações</b></legend>
            <input name="pesquisar" id='pesquisar' type="button"  value="Pesquisar" onclick='js_pesquisarOrdens();' />
            <input name="atualizar" id='atualizar' type="button"  value="Atualizar" onclick='js_configurar()' />
            <input name="emitecheque" id='emitecheque' type="button"
                   value='Emitir Cheque' onclick='location.href="emp4_empageformache001.php"' />
            <input name="emitetxt" id='emitetxt' type="button"
                   value='Emitir Arquivo Texto' onclick='location.href="emp4_empageconfgera001.php"' />
            <input name='agruparmovimentos' id='agruparmovimentos' value='Agrupar Movimentos' type='button' />
            <input name='relatorioagenda' id='relatorioagenda' value='Relatório' type='button'
                   onclick="js_visualizarRelatorio()" />
            <?php

            /**
             * Podemos remover após o OBN entrar em produção.
             */
            if (db_permissaomenu(db_getsession("DB_anousu"), db_getsession("DB_modulo"), 9755) === "true") {

              echo "<input name='btnConfigurarMovimentos' id='btnConfigurarMovimentos' value='Configurações de Envio' type='button'";
              echo "onclick=\"window.location='emp4_configuracaoarquivoenvio001.php'\" />";
            }
            ?>
          </fieldset>

        </td>
      <tr>
        <td colspan='3'>
          <fieldset>
            <legend><b>Ordens</b></legend>
            <div id='gridNotas' style="width: 100%">
            </div>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan='5' align='left'>
          <b><span >**</span>Conta conferida</b>
          <br />
       <span>
          <fieldset>
            <legend><b>Mostrar</b></legend>
            <input type="checkbox" id='configuradas' onclick='js_showFiltro("configurada",this.checked)' />
            <label for="configuradas" style='padding:1px;border: 1px solid black; background-color:#d1f07c; vertical-align: middle'>
              <b>Atualizados</b>
            </label>
            <input type="checkbox" id='normais' checked onclick='js_showFiltro("normal",this.checked)' />
            <label for="normais" style='padding:1px;border: 1px solid black;background-color:white; vertical-align: middle'>
              <b>Não Atualizados</b>
            </label>
            <input type="checkbox" id='comMovs'  onclick='js_showFiltro("comMov",this.checked)' />
            <label for="comMovs" style='padding:1px;border: 1px solid black;background-color:rgb(222, 184, 135); vertical-align: middle'>
              <b>Com cheque/em Arquivo</b>
            </label>
          </fieldset>
      </span>
        </td>
      </tr>
      <tr>
        <td colspan="5">
          <fieldset>
            <legend>
              <a  id='esconderTotais' style="-moz-user-select: none;cursor: pointer">
                <b>Totais</b>
                <img src='imagens/seta.gif' id='toggletotais' border='0'>
              </a>
            </legend>
            <table cellpadding="0" class='tabelatotais'
                   cellspacing="0"
                   width="50%"
                   style="display: none;border: 2px inset white;">
              <thead>
              <th class='table_header'>
                Tipo
              </th>
              <th class='table_header'>
                Atualizado
              </th>
              <th class='table_header'>
                com Cheque Emitido/Arquivo
              </th>
              <th class='table_header'>
                Não Configurado
              </th>
              </thead>
              <tbody id='totalizadores' style="background-color: white">
              </tbody>
            </table>
          </fieldset>
        </td>
        </td>
      </tr>
    </table>
</form>
</center>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<div id='teste'></div>
<script type="text/javascript">

  var oLiquidacaoPendente;
  var oPesquisa = {
    classificacao_credor : {
      busca : function(lMostrar) {

        var sPath = "func_classificacaocredores.php?funcao_js=";

        if (!lMostrar) {

          if ($F('codigo_classificacao') == "") {
            $('descricao_classificacao').value = '';
            return;
          }

          sPath += "parent.oPesquisa.classificacao_credor.completa&pesquisa_chave=" + $F('codigo_classificacao');

        } else {
          sPath += "parent.oPesquisa.classificacao_credor.preenche|cc30_codigo|cc30_descricao";
        }

        js_OpenJanelaIframe( "CurrentWindow.corpo",
          "db_iframe_classificacaocredores",
          sPath,
          "Pesquisar Lista de Classificaçao de Credores",
          lMostrar);

      },
      preenche : function(iCodigo, sDescricao) {

        $('codigo_classificacao').value = iCodigo;
        $('descricao_classificacao').value = sDescricao;
        db_iframe_classificacaocredores.hide();
      },
      completa : function(sDescricao, lErro) {

        $('descricao_classificacao').value = sDescricao;

        if (lErro) {
          $('codigo_classificacao').value = '';
        }
      }
    }
  }


  sDataDia = "<?=date("d/m/Y",db_getsession("DB_datausu"))?>";
  iTipoControleRetencaoMesAnterior = <?=$iTipoControleRetencaoMesAnterior?>;
  var aAutenticacoesGlobal = new Array();
  function js_reload(){
    document.form1.submit();
  }
  //-----------------------------------------------------------
  //---ordem 01
  function js_pesquisae82_codord(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_pagordem',
        'func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord',
        'Pesquisa Ordens de Pagamento',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30
      );
    }else{
      ord01 = new Number(document.form1.e82_codord.value);
      ord02 = new Number(document.form1.e82_codord02.value);
      if(ord01 > ord02 && ord01 != "" && ord02 != ""){
        alert("Selecione uma ordem menor que a segunda!");
        document.form1.e82_codord.focus();
        document.form1.e82_codord.value = '';
      }
    }
  }
  function js_mostrapagordem1(chave1){
    document.form1.e82_codord.value = chave1;
    db_iframe_pagordem.hide();
  }
  //-----------------------------------------------------------
  //---ordem 02
  function js_pesquisae82_codord02(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_pagordem',
        'func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord',
        'Pesquisa Ordens de Pagamento',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30
      );
    }else{
      ord01 = new Number(document.form1.e82_codord.value);
      ord02 = new Number(document.form1.e82_codord02.value);
      if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
        alert("Selecione uma ordem maior que a primeira");
        document.form1.e82_codord02.focus();
        document.form1.e82_codord02.value = '';
      }
    }
  }
  function js_mostrapagordem102(chave1,chave2){
    document.form1.e82_codord02.value = chave1;
    db_iframe_pagordem.hide();
  }
  function js_pesquisae60_codemp(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_empempenho',
        'func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp|e60_anousu',
        'Pesquisar Empenhos',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30);
    }else{
      // js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
    }
  }
  function js_mostraempempenho2(chave1, iAnoEmepenho){
    document.form1.e60_codemp.value = chave1+'/'+iAnoEmepenho;
    db_iframe_empempenho.hide();
  }

  function js_pesquisaz01_numcgm(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('',
        'func_nome',
        'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
        'Pesquisar CGM',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30);
    }else{
      if(document.form1.z01_numcgm.value != ''){

        js_OpenJanelaIframe('',
          'func_nome',
          'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+
          '&funcao_js=parent.js_mostracgm',
          'Pesquisar CGM',
          false,
          22,
          0,
          document.width-12,
          document.body.scrollHeight-30);
      }else{
        document.form1.z01_nome.value = '';
      }
    }
  }
  function js_mostracgm(erro,chave){
    document.form1.z01_nome.value = chave;
    if(erro==true){
      document.form1.z01_numcgm.focus();
      document.form1.z01_numcgm.value = '';
    }
  }

  function js_mostracgm1(chave1,chave2){

    document.form1.z01_numcgm.value = chave1;
    document.form1.z01_nome.value   = chave2;
    func_nome.hide();

  }
  function js_pesquisae42_sequencial(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('',
        'func_nome',
        'func_empageordem.php?funcao_js=parent.js_mostraordem1|e42_sequencial|e42_dtpagamento',
        'Pesquisar OP Auxiliar ',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight-30
      );
    } else {
      if ($F('e42_sequencial') != "") {
        js_OpenJanelaIframe('',
          'func_nome',
          'func_empageordem.php?pesquisa_chave='+$F('e42_sequencial')+
          '&funcao_js=parent.js_mostraordemagenda',
          'Pesquisar OP Auxiliar',
          false,
          22,
          0,
          document.width-12,
          document.body.scrollHeight-30);
      } else {
        $('e42_sequencial').value = '';
      }
    }
  }

  function js_mostraordem1(chave1,chave2){

    document.form1.e42_sequencial.value = chave1;
    document.form1.e42_dtpagamento.value = js_formatar(chave2,"d");
    func_nome.hide();

  }

  function js_mostraordemagenda(chave,erro){

    if(!erro) {
      document.form1.e42_dtpagamento.value = chave;
    } else {

      document.form1.e42_sequencial.value  = '';
      document.form1.e42_dtpagamento.value = '';

    }
  }

  function js_pesquisae42_sequencialmanutencao(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('',
        'func_nome',
        'func_empageordem.php?funcao_js=parent.js_mostraordem3|e42_sequencial|e42_dtpagamento',
        'Pesquisa OP Auxiliar',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30);
    } else {
      if ($F('e42_sequencialmanutencao') != "") {
        js_OpenJanelaIframe('',
          'func_nome','func_empageordem.php?pesquisa_chave='+$F('e42_sequencialmanutencao')+
          '&funcao_js=parent.js_mostraordemagenda4',
          'Pesquisa OP Auxiliar',
          false,
          22,
          0,
          document.width-12,
          document.body.scrollHeight-30);
      } else {
        $('e42_dtpagamentomanutencao').value = '';
      }
    }
  }

  function js_mostraordem3(chave1,chave2){

    document.form1.e42_sequencialmanutencao.value = chave1;
    document.form1.e42_dtpagamentomanutencao.value = js_formatar(chave2,"d");
    func_nome.hide();

  }

  function js_mostraordemagenda4(chave,erro){

    if(!erro) {
      document.form1.e42_dtpagamentomanutencao.value = chave;
    } else {

      document.form1.e42_sequencialmanutencao.value  = '';
      document.form1.e42_dtpagamentomanutencao.value = '';

    }
  }

  function js_pesquisac62_codrec(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_orctiporec',
        'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
        'Pesquisar Recursos',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30);
    }else{
      if(document.form1.o15_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
          'db_iframe_orctiporec',
          'func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+
          '&funcao_js=parent.js_mostraorctiporec',
          'Pesquisar Recursos',
          false,
          22,
          0,
          document.body.getWidth() - 12,
          document.body.scrollHeight - 30);
      }else{
        document.form1.o15_descr.value = '';
      }
    }
  }
  function js_mostraorctiporec(chave,erro){
    document.form1.o15_descr.value = chave;
    if(erro==true){
      document.form1.o15_codigo.focus();
      document.form1.o15_codigo.value = '';
    }
  }

  function js_mostraorctiporec1(chave1,chave2){
    document.form1.o15_codigo.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
  }
  function js_pesquisarOrdens() {

    if ($F('data_vencimento_inicial') != "" && $F('data_vencimento_final') != "") {

      if (js_comparadata($F('data_vencimento_inicial'), $F('data_vencimento_final'), '>')) {

        alert("A Data de Vencimento inicial deve ser menor que a Data de Vencimento final.");
        return false;
      }
    }

    if ($F('dataordeminicial') != "" && $F('dataordemfinal') != "") {

      if (js_comparadata($F('dataordeminicial'), $F('dataordemfinal'), '>')) {

        alert("A Data Inicial deve ser menor que a Data Final.");
        return false;
      }
    }

    js_divCarregando("Aguarde, pesquisando Movimentos.","msgBox");
    js_liberaBotoes(false);
    js_reset();
    $('normais').checked = true;
    $('TotalForCol14').innerHTML = js_formatar(0,'f');
    $('TotalForCol13').innerHTML = js_formatar(0,'f');
    $('TotalForCol12').innerHTML = js_formatar(0,'f');
    $('TotalForCol11').innerHTML  = js_formatar(0,'f');
    //Criamos um objeto que tera a requisicao

    var oParam = {
      iOrdemIni               : $F('e82_codord'),
      iOrdemFim               : $F('e82_codord02'),
      iCodEmp                 : $F('e60_codemp'),
      dtDataIni               : $F('dataordeminicial'),
      dtDataFim               : $F('dataordemfinal'),
      iNumCgm                 : $F('z01_numcgm'),
      iRecurso                : $F('o15_codigo'),
      sDtAut                  : $F('e42_dtpagamento'),
      iOPauxiliar             : $F('e42_sequencial'),
      iAutorizadas            : $F('ordensautorizadas'),
      iOPManutencao           : $F('e42_sequencialmanutencao'),
      codigo_classificacao    : $F('codigo_classificacao'),
      data_vencimento_inicial : $F('data_vencimento_inicial'),
      data_vencimento_final   : $F('data_vencimento_final'),
      orderBy                 : $F('orderby'),
      lVinculadas             : ($F("recursosvinculados") == "true"),
      e03_numeroprocesso      : encodeURIComponent(tagString($F("e03_numeroprocesso")))
    };



    url = "emp4_manutencaoPagamentoRPC.php";

    var sParam = js_objectToJson(oParam),
      sJson  = '{"exec":"getMovimentos","params":['+sParam+']}',
      oAjax  = new Ajax.Request(url, {
          method    : 'post',
          parameters: 'json='+sJson,
          onComplete: js_retornoConsultaMovimentos
        }
      );

  }

  var oComponente = new DBViewManutencaoEmpenho();
  function manutencaoEmpenho(numEmp) {
    oComponente.show(numEmp, js_pesquisarOrdens);
  }

  function js_retornoConsultaMovimentos(oAjax) {

    js_removeObj("msgBox");
    js_liberaBotoes(true);
    var oResponse = eval("("+oAjax.responseText+")");
    gridNotas.clearAll(true);
    gridNotas.setStatus("");
    var iRowAtiva     = 0;
    var iTotalizador  = 0;
    if (oResponse.status == 1) {

      for (var iNotas = 0; iNotas < oResponse.aNotasLiquidacao.length; iNotas++) {

        with (oResponse.aNotasLiquidacao[iNotas]) {


          var nValor =  e81_valor;
          if (e43_valor > 0 && e43_valor >= e81_valor) {
            nValor = e43_valor;
          }
          nValorTotal  = new Number(nValor - valorretencao).toFixed(2);
          var lDisabled = false;
          var lDisabledContaFornecedor = false;
          var sDisabled = "";
          if (e91_codmov != '' || e90_codmov != '') {

            lDisabled                = true;
            lDisabledContaFornecedor = true;
            sDisabled                = " disabled ";
          }

          if (e97_codforma != 3) {
            lDisabledContaFornecedor = true;
          }

          if (nValor == 0) {
            continue;
          }
          iTotalizador++;
          var aLinha  = new Array();
          aLinha[0]   = e81_codmov;
          aLinha[1]   = "<a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;' href='#'>";
          aLinha[1]  += e60_codemp+"/"+e60_anousu+"</a>";
          aLinha[2]   = o15_codigo;

          /**
           * Cria a expressão regular para efetuar a alteração dos pontos por vazio.
           */

          var sRegExpressao = /\./g;
          var sConCarPeculiar       = e60_concarpeculiar.replace(sRegExpressao, "");
          /**
           *  Caso seja vazio, permite o usuário selecionar um id. Do contrário mostra a concarpeculiar selecionada
           */
          if ( sConCarPeculiar == '' || new Number(sConCarPeculiar) == 0 ) {

            if (e79_concarpeculiar == '') {
              e79_concarpeculiar = 'Selecione';
            }
            aLinha[3]  = "<a href='#' id='ccp_"+e81_codmov+"'";
            aLinha[3] += "onclick='js_lookupConCarPeculiar("+e81_codmov+");' >"+e79_concarpeculiar+"</a>";
          } else {
            aLinha[3] = e60_concarpeculiar;
          }

          aLinha[4]   = e50_codord;
          aLinha[5]   = js_createComboContasPag(e81_codmov, aContasVinculadas, e85_codtipo, lDisabled);
          aLinha[6]   = z01_nome.urlDecode().substring(0,20);
          aLinha[7]   = js_createComboContasForne(aContasFornecedor, e98_contabanco, e81_codmov, z01_numcgm);
          aLinha[8]   = js_createComboForma(e97_codforma,e81_codmov, lDisabled);
          aLinha[9]   = js_formatar(e69_dtvencimento,'d');
          aLinha[10]  = js_formatar( (e53_valor-e53_vlranu),"f");
          aLinha[11]  = "<span id='valoraut" + e81_codmov + "'>" + js_formatar(nValor, "f") + "</span>";

          if (lDisabled) {
            aLinha[12] = "<a id='retencao" + e81_codmov + "'>" + js_formatar(valorretencao, "f") + "</a>";
          } else {

            aLinha[12]  = "<a href='#'  id='retencao" + e81_codmov + "'";
            aLinha[12] += " onclick='js_lancarRetencao(" + e71_codnota + "," + e50_codord + "," + e60_numemp + "," + e81_codmov + ");'>";
            aLinha[12] += js_formatar(valorretencao, "f")+"</a>";
            aLinha[12] += "<span style='display:none' id='validarretencao" + e81_codmov + "'>" + validaretencao + "</span>";
          }
          var sReadOnly = '';
          if (valorretencao > 0) {
            sReadOnly  = ' readonly ';
          }

          nValorTotal = (nValorTotal - e53_vlranu).toFixed(2);

          aLinha[13]  = "<input type = 'text' id='valorrow" + e81_codmov + "' size='9' style='width:100%;height:100%;text-align:right;border:1px inset'";
          aLinha[13] += " class='valores' onchange='js_calculaValor(this," + e81_codmov + ")'" + sReadOnly;
          aLinha[13] += "                 onkeypress='return js_teclas(event,this)'";
          aLinha[13] += "       value = '" + nValorTotal + "' id='valor" + e50_codord + "' " + sDisabled + ">";

          aLinha[14] = "<input type='button' id='manutencao" + e81_codmov + "' value='Manutenção' onclick='manutencaoEmpenho(" + e60_numemp + ")'/>";

          gridNotas.addRow(aLinha, false, lDisabled);

          if (e91_codmov != '' || e90_codmov != '') {

            if (!$('comMovs').checked) {

              iTotalizador--;
              gridNotas.aRows[iRowAtiva].lDisplayed = false;
            }
            gridNotas.aRows[iRowAtiva].aCells[0].lDisabled  = true;
            gridNotas.aRows[iRowAtiva].setClassName('comMov');

          } else if (e86_codmov != '' || e97_codmov != '') {

            if (!$('configuradas').checked) {

              iTotalizador--;
              gridNotas.aRows[iRowAtiva].lDisplayed = false;

            }
            gridNotas.aRows[iRowAtiva].aCells[0].lDisabled  = true;
            gridNotas.aRows[iRowAtiva].setClassName('configurada');

          } else if ($F('e42_sequencialmanutencao') != "" && e42_sequencial == $F('e42_sequencialmanutencao')) {
            gridNotas.aRows[iRowAtiva].setClassName('naOPAuxiliar');
          }
          gridNotas.aRows[iRowAtiva].aCells[7].sEvents  = "onmouseover='js_setAjuda(\""+z01_nome.urlDecode()+"\",true)'";
          gridNotas.aRows[iRowAtiva].aCells[7].sEvents += "onmouseOut='js_setAjuda(null,false)'";
          gridNotas.aRows[iRowAtiva].sValue  = e81_codmov;
          iRowAtiva++;

        }
      }
      gridNotas.renderRows();
      gridNotas.setNumRows(iTotalizador);
      $('gridNotasstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
      if (oResponse.totais.length > 0) {
        var sTotais = "";
        for (var i = 0; i < oResponse.totais.length;i++) {

          with (oResponse.totais[i]) {

            sTotais += "<tr>";
            sTotais += "<td class='linhagrid' style='text-align:laeft'>"+tipo+"</td>";
            var nValor = 0;
            if (tipo != "NDA") {
              nValor  = valor;
            }
            sTotais += "<td class='linhagrid' style='text-align:right'>"+js_formatar(nValor,'f')+"</td>";
            var sValorVinculado = 0;
            if (tipo == "CHE" ) {
              sValorVinculado = cheques;
            }
            if (tipo == "TRA") {
              sValorVinculado = transmissao;
            }
            sTotais += "<td class='linhagrid' style='text-align:right'>"+js_formatar(sValorVinculado,'f')+"</td>";
            var nConfigurado = 0;
            if (tipo == "NDA") {
              nConfigurado = valor;
            }
            sTotais += "<td class='linhagrid' style='text-align:right'>"+js_formatar(nConfigurado,'f')+"</td></tr>";

          }
        }
        $('totalizadores').innerHTML = sTotais;
      }
    } else if (oResponse.status == 2) {
      gridNotas.setStatus("<b>Não foram encontrados movimentos.</b>");
    }
  }

  function js_getContaSaltes(objSelect){
    var iContaSaltes = 0;
    for ( var i = 0; i < objSelect.options.length; i++ ) {
      if ( objSelect.options[i].selected ){
        iContaSaltes = objSelect.options[i].text.split('-')[0].trim();
        break;
      }
    }
    return iContaSaltes;
  }

  function js_init() {

    gridNotas              = new DBGrid("gridNotas");
    gridNotas.nameInstance = "gridNotas";
    gridNotas.selectSingle = function (oCheckbox, sRow, oRow,lVerificaSaldo) {

      if (lVerificaSaldo == null) {
        var lVerificaSaldo = true;
      }

      if (oCheckbox.checked) {

        oRow.isSelected    = true;
        $(sRow).className += 'marcado';
        if (oRow.aCells[8].getValue() != "" && lVerificaSaldo) {
          if ($('ctapag' + oRow.aCells[1].getValue())) {
            js_getSaldos($('ctapag'+oRow.aCells[1].getValue()));
          }
        }
        if (lVerificaSaldo) {

          $('TotalForCol14').innerHTML = js_formatar(gridNotas.sum(14).toFixed(2),'f');
          $('TotalForCol13').innerHTML = js_formatar(gridNotas.sum(13).toFixed(2),'f');
          $('TotalForCol12').innerHTML = js_formatar(gridNotas.sum(12).toFixed(2),'f');
          $('TotalForCol11').innerHTML  = js_formatar(gridNotas.sum(11).toFixed(2),'f');

        }
        $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML)+1;
      } else {

        $(sRow).className = oRow.getClassName();
        oRow.isSelected   = false;
        if (lVerificaSaldo) {

          $('TotalForCol14').innerHTML = js_formatar(gridNotas.sum(14).toFixed(2),'f');
          $('TotalForCol13').innerHTML = js_formatar(gridNotas.sum(13).toFixed(2),'f');
          $('TotalForCol12').innerHTML = js_formatar(gridNotas.sum(12).toFixed(2),'f');
          $('TotalForCol11').innerHTML  = js_formatar(gridNotas.sum(11).toFixed(2),'f');

        }
        $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML)-1;
      }
    }
    gridNotas.selectAll = function(idObjeto, sClasse, sLinha) {

      var obj = document.getElementById(idObjeto);
      if (obj.checked){
        obj.checked = false;
      } else{
        obj.checked = true;
      }

      itens = this.getElementsByClass(sClasse);
      for (var i = 0;i < itens.length ;i++){

        if (itens[i].disabled == false){
          if (obj.checked == true){

            if ($(this.aRows[i].sId).style.display != 'none') {
              if (!itens[i].checked) {

                itens[i].checked=true;
                this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false);

              }

            }
          } else {

            if (itens[i].checked) {

              itens[i].checked=false;
              this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false);
            }
          }
        }
      }

      $('TotalForCol14').innerHTML = js_formatar(gridNotas.sum(14).toFixed(2),'f');
      $('TotalForCol13').innerHTML = js_formatar(gridNotas.sum(13).toFixed(2),'f');
      $('TotalForCol12').innerHTML = js_formatar(gridNotas.sum(12).toFixed(2),'f');
      $('TotalForCol11').innerHTML  = js_formatar(gridNotas.sum(11).toFixed(2),'f');
    }
    gridNotas.setCheckbox(0);
    gridNotas.hasTotalizador = true;
    gridNotas.allowSelectColumns(true);
    gridNotas.setCellWidth(new Array("5%","7%", "5%", "5%","5%","13%","10%", "10%", "10%", "10%", "5%", "5%", "5%", "5%", "10%"));
    gridNotas.setCellAlign(new Array("right", "center","right", "center", "right", "left", "left", "center", "center", "center","right","right","right", "center", "center"));
    gridNotas.setHeader(new Array("Mov.",
      "Empenho",
      "Recurso",
      "CP/CA",
      "OP",
      "Cta. Pag",
      "Nome",
      "Banco/Ag",
      "Forma Pgto",
      "Data Vencimento",
      "Valor OP",
      "Vlr Aut",
      "Retenção",
      "Valor",
      "Lista de Classificação"
      )
    );
    gridNotas.aHeaders[1].lDisplayed = false;
    gridNotas.aHeaders[15].lDisplayed = false;
    gridNotas.show(document.getElementById('gridNotas'));
    $('gridNotasstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
    // Tarefa 24652
    document.form1.e82_codord.focus();
  }

  function js_createComboContasPag(iCodMov, aContas, iContaConfig, lDisabled) {

    var sDisabled = "";
    if (lDisabled == null) {
      lDisabled = false;
    }
    if (lDisabled) {
      sDisabled = " disabled ";
    }
    var sCombo  = "<select id='ctapag"+iCodMov+"' class='ctapag' style='width:100%'";
    sCombo     += " onchange='js_getSaldos(this)' "+sDisabled+">";
    sCombo     += "<option value=''>Selecione</option>";
    if (aContas != null) {

      for (var i = 0; i < aContas.length; i++) {

        var sSelected = "";
        if (iContaConfig == aContas[i].e83_codtipo) {
          sSelected = " selected ";
        }
        var sDescrConta =  aContas[i].e83_conta+" - "+aContas[i].e83_descr.urlDecode()+" - "+aContas[i].c61_codigo;
        sCombo += "<option "+sSelected+" value = "+aContas[i].e83_codtipo+">"+sDescrConta+"</option>";

      }
    }
    sCombo  += "</select>";



    return sCombo;
  }

  function js_showFiltro(sQualFiltro,lMostrar) {

    var aMatched     = gridNotas.getElementsByClass(sQualFiltro);
    aMatched     = aMatched.concat(gridNotas.getElementsByClass(sQualFiltro+"marcado"));
    var iTotalizador = 0;
    for (var i = 0; i < aMatched.length; i++) {
      if (lMostrar) {

        aMatched[i].style.display = '';
        iTotalizador++;

      } else {

        aMatched[i].style.display = 'none';
        iTotalizador--;

      }
    }
    var iTotal  = gridNotas.getNumRows();
    gridNotas.setNumRows(iTotal +iTotalizador);
  }

  function js_createComboForma(iTipoForma, iCodMov, lDisabled) {

    var sDisabled = "";
    if (lDisabled == null) {
      lDisabled = false;
    }
    if (lDisabled) {
      sDisabled = " disabled ";
    }
    /* [Inicio plugin GeracaoArquivoOBN - Efetuar pagamento DEB ou DIN - parte1] */
    var sCombo  = "<select style='width:100%' class='formapag' id='forma"+iCodMov+"' "+sDisabled+">";
    /* [Fim plugin GeracaoArquivoOBN - Efetuar pagamento DEB ou DIN - parte1] */
    sCombo     += "  <option "+(iTipoForma == 0?" selected ":" ")+" value='0'>NDA</option>";
    sCombo     += "  <option "+(iTipoForma == 1?" selected ":" ")+" value='1'>DIN</option>";
    sCombo     += "  <option "+(iTipoForma == 2?" selected ":" ")+" value='2'>CHE</option>";
    sCombo     += "  <option "+(iTipoForma == 3?" selected ":" ")+" value='3'>TRA</option>";
    sCombo     += "  <option "+(iTipoForma == 4?" selected ":" ")+" value='4'>DEB</option>";
    sCombo     += "</select>";
    return sCombo
  }

  /* [Inicio plugin GeracaoArquivoOBN - Efetuar pagamento DEB ou DIN - parte2] */
  /* [Fim plugin GeracaoArquivoOBN - Efetuar pagamento DEB ou DIN - parte2] */

  function js_createComboContasForne(aContasForne, iContaForne, iCodMov, iNumCgm, lDisabled) {

    var sDisabled = "";
    if (lDisabled == null) {
      lDisabled = false;
    }
    if (lDisabled) {
      sDisabled = " disabled ";
    }

    var sRetorno  = "<select id='ctapagfor"+iCodMov+"' "+sDisabled+" class='cgm' style='width:100%'";
    sRetorno     += " onchange='js_novaConta("+iCodMov+", "+iNumCgm+",this.value)'>";
    sRetorno     += "<option value=''>Selecione</option>";
    sRetorno     += "<option value='n'>Nova Conta</option>";
    if (aContasForne != null) {


      aContasForne.each(
        function (oConta, iLinha) {

          var sSelecionado = "";
          if (oConta.pc63_contabanco == oConta.conta_historico_fornecedor && iContaForne == "") {
            sSelecionado = " selected ";
          } else if (iContaForne != "" && iContaForne == oConta.pc63_contabanco) {
            sSelecionado = " selected ";
          } else if (oConta.padrao == "t") {
            sSelecionado = " selected ";
          }

          var sConferido = "";
          var sOption = "<option value='"+oConta.pc63_contabanco+"' "+sSelecionado+">";
          if (oConta.pc63_agencia_dig.trim() != ""){
            oConta.pc63_agencia_dig = "/"+oConta.pc63_agencia_dig;
          }
          if (oConta.pc63_conta_dig.trim() != ""){
            oConta.pc63_conta_dig = "/"+oConta.pc63_conta_dig;
          }

          if (oConta.pc63_dataconf.trim() != "" ){
            sConferido = "** - ";
          }
          sOption += sConferido+oConta.pc63_banco+' - '+oConta.pc63_agencia+""+oConta.pc63_agencia_dig+' - '+oConta.pc63_conta+""+oConta.pc63_conta_dig;
          sOption += "</option>";
          sRetorno += sOption;
        }
      );


    }
    sRetorno += "</select>";
    return sRetorno;
  }

  function js_dbInputData(sName, value, lDisabled){

    var sDisabled = "";
    if (lDisabled == null) {
      lDisabled = false;
    }
    if (lDisabled) {
      sDisabled = " disabled ";
    }
    var sSaida  = '<input readonly name="'+sName+'" type="text" '+sDisabled+' style="height:100%;width:100%"  id="'+sName+'"';
    sSaida += '   value="'+value+'" size="10"  maxlength="10" autocomplete="off"';
    sSaida += '   onBlur="js_validaDbData(this);" onKeyUp="return js_mascaraData(this,event)"';
    sSaida += '   onSelect="return js_bloqueiaSelecionar(this);" onFocus="js_validaEntrada(this);">';
    sSaida += '<input name="'+sName+'_dia" type="hidden" title="" id="'+sName+'_dia" value=""  maxlength="2" >';
    sSaida += '<input name="'+sName+'_mes" type="hidden" title="" id="'+sName+'_mes" value=""  maxlength="2" >';
    sSaida += '<input name="'+sName+'_ano" type="hidden" title="" id="'+sName+'_ano" value=""  maxlength="4" >';

    return sSaida;
  }

  function js_novaConta(Movimento,iNumCgm, sOpcao ){
    erro = 0;
    if(sOpcao == 'n' || sOpcao == 'button'){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcfornecon',
        'com1_pcfornecon001.php?novo=true&reload=true&z01_numcgm='+iNumCgm,
        'Cadastro de Contas de Fornecedores',true);
    }
  }
  function js_setAjuda(sTexto,lShow) {

    if (lShow) {

      el =  $('gridNotas');
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

  function js_configurar() {

    var aMovimentos = gridNotas.getSelection();
    var lEfetuarPagamento = $('efetuarpagamento').checked;
    var lSemErro = true;
    var sAviso = '';

    /*
     * Validamos o movimento configurado, conforme a forma de pagamento escolhido.
     * - cheque, é obrigatorio ter informado a conta pagadora, e o valor;
     * - Transmissao é obrigatorio ter informado a conta pagadora, e a conta do fornecedor
     * - Dinheiro , apenas obrigatorio informar  valor.
     * - NDA, ignoramos o registro.
     */

    if (aMovimentos.length == 0) {

      alert('Não há nenhum movimento selecionado.');
      return false;

    }

    if ($F('e42_dtpagamento') == "") {

      alert('Data de pagamento não informada.');
      return false;

    }
    if (js_comparadata(sDataDia,$F('e42_dtpagamento'),">")) {

      alert("Data Informada Inválida.\nData menor que a data do sistema");
      return false;
    }

    oEnvioMovimento = new Object();
    oEnvioMovimento.exec = "configurarPagamento";
    oEnvioMovimento.lEfetuarPagamento = lEfetuarPagamento;
    oEnvioMovimento.dtPagamento = $F('e42_dtpagamento');
    oEnvioMovimento.aMovimentos = new Array();
    oEnvioMovimento.lEmitirOrdeAuxiliar = false;
    oEnvioMovimento.iOPAuxiliarManutencao = "";
    if ($('emitirordemauxiliar').checked) {
      oEnvioMovimento.lEmitirOrdeAuxiliar = true;
    }
    if ($F('e42_sequencialmanutencao') != "" && !$('opmanutencaonda').checked) {

      if (($('opmanutencaoincluir').checked && !$('opmanutencaoexcluir').checked) && oEnvioMovimento.lEmitirOrdeAuxiliar) {

        alert('Configurações escolhidas estão em conflito: emitir OrdemAuxiliar e incluir movimentos na ordem auxlilar selecionada.');
        return false;

      } else if ($('opmanutencaoincluir').checked && !$('opmanutencaoexcluir').checked) {
        oEnvioMovimento.iOPAuxiliarManutencao = $F('e42_sequencialmanutencao');
      }

      if (($('opmanutencaoexcluir').checked && !$('opmanutencaoincluir').checked) && oEnvioMovimento.lEmitirOrdeAuxiliar) {

        alert('Configurações escolhidas estão em conflito: emitir Ordem Auxiliar e Excluir movimentos na ordem auxlilar selecionada.');
        return false;

      } else if ($('opmanutencaoexcluir').checked && !$('opmanutencaoincluir').checked) {

        oEnvioMovimento.iOPAuxiliarManutencao = $F('e42_sequencialmanutencao');
        oEnvioMovimento.exec = 'cancelaMovimentoOrdemAuxiliar';

      }
    }

    var aFormasSelecionadas     = new Array();
    var lMostraMsgErroRetencao  = false;
    var sMsgRetencaoMesAnterior = "Atenção:\n";
    var sVirgula                = "";
    for (var iMov = 0; iMov < aMovimentos.length; iMov++) {

      var iForma               = aMovimentos[iMov][9];
      var iCodMov              = aMovimentos[iMov][0];
      var nValor               = new Number(aMovimentos[iMov][14]);
      var sConCarPeculiar      = aMovimentos[iMov][4];
      var iNota                = aMovimentos[iMov][5];
      var iContaFornecedor     = aMovimentos[iMov][8];
      var iContaPagadora       = aMovimentos[iMov][6];
      var dtVencimento         = aMovimentos[iMov][10];
      var iContaSaltes         = js_getContaSaltes( $('ctapag'+aMovimentos[iMov][0]) );
      var dtAutoriza           = $F('e42_dtpagamento');
      var nValorRetencao       = js_strToFloat(aMovimentos[iMov][13]);
      var lRetencaoMesAnterior = $('validarretencao'+iCodMov).innerHTML;

      /*
       * Fazemos a verificacao para Cheque;
       */
      aFormasSelecionadas.push(iForma);

      if (iForma != 0) {

        if (iContaPagadora == '') {

          lSemErro = false;
          sAviso   = "Movimento ("+iCodMov+") da Nota "+iNota+" Sem conta pagadora Informada.";

        }
      }

      if (lRetencaoMesAnterior == "true") {

        lMostraMsgErroRetencao   = true;
        sMsgRetencaoMesAnterior += sVirgula+"Movimento "+iCodMov+" da OP "+iNota+" possui retenções ";
        sMsgRetencaoMesAnterior += " configuradas em mês  diferente do mês atual\n";
        sVirgula = ", ";

      }
      if (!lSemErro) {

        alert(sAviso);
        return false;
        break;

      }
      if (sConCarPeculiar == "Selecione") {
        sConCarPeculiar = '';
      }
      oMovimento                   = {};
      oMovimento.iCodForma         = iForma;
      oMovimento.iCodMov           = iCodMov;
      oMovimento.nValor            = nValor.valueOf();
      oMovimento.iContaFornecedor  = iContaFornecedor;
      oMovimento.iContaPagadora    = iContaPagadora;
      oMovimento.iContaSaltes      = iContaSaltes;
      oMovimento.iCodNota          = iNota;
      oMovimento.nValorRetencao    = nValorRetencao.valueOf();
      oMovimento.sConCarPeculiar   = sConCarPeculiar;
      oMovimento.dtVencimento      = dtVencimento;
      oMovimento.sNumeroEmpenho    = aMovimentos[iMov][2];
      if (dtAutoriza == "") {
        dtAutoriza = oEnvioMovimento.dtPagamento;
      }
      oMovimento.dtPagamento = dtAutoriza;
      oEnvioMovimento.aMovimentos.push(oMovimento);
    }

    /*
     * For verificando se todas as formas de pagamento para os movimentos selecionados
     *   sao dinheiro(DIN) ou debito(DEB) caso nao for obrigamos o usuario a corrigir a
     *   forma de pagamento ou desmarcar a opcao de "Efetuar pagamento"
     *
     */
    if (lEfetuarPagamento) {

      for (var iInd = 0; iInd < aFormasSelecionadas.length; iInd++ ) {
        if (aFormasSelecionadas[iInd] != "1" && aFormasSelecionadas[iInd] != "4" ) {
          alert("Para efetuar pagamento automático somente são permitidas as forma de pagamento : Dinheiro (DIN) e Débito (DEB). Verifique.");
          return false;
        }
      }

      /**
       * verificamos o parametro para controle de retencões em meses anteriores.
       * caso seje 0 - não faz nenhuma critica ao usuário. apenas realiza o pagamento.
       *           1 - Avisa ao usuário e pede uma confirmação para realizar o pagamento.
       *           2 - Avisa ao usuário e cancela o pagamento do movimento
       */
      var sMsgConfirmaPagamento = "Deseja realmente efetuar pagamento para os movimentos selecionados?";
      if (iTipoControleRetencaoMesAnterior == 1) {

        if (lMostraMsgErroRetencao) {

          sMsgConfirmaPagamento  =  sMsgRetencaoMesAnterior;
          sMsgConfirmaPagamento += "É Recomendável recalcular as retenções.\n";
          sMsgConfirmaPagamento += "Deseja realmente efetuar pagamento para os movimentos selecionados?";

        }
      } else if (iTipoControleRetencaoMesAnterior == 2) {

        if (lMostraMsgErroRetencao) {

          sMsgConfirmaPagamento    =  sMsgRetencaoMesAnterior;
          sMsgRetencaoMesAnterior += "Recalcule as Retenções do movimento.";
          alert(sMsgRetencaoMesAnterior);
          return false;

        }
      }

      var lConfirmacao          = confirm(sMsgConfirmaPagamento);
      if (!lConfirmacao) {
        return false;
      }
    }

    if (typeof oLiquidacaoPendente != "undefined") {

      if (oLiquidacaoPendente.lWindowAux == true) {
        oLiquidacaoPendente.oWindowAux.destroy();
      }
    }


    oLiquidacaoPendente = new ViewLiquidacoesPendentes('oLiquidacaoPendente', oEnvioMovimento.aMovimentos, efetuarPagamentos);
    oLiquidacaoPendente.show();
  }


  function efetuarPagamentos () {

    js_divCarregando("Aguarde, Configurando Movimentos.","msgBox");
    js_liberaBotoes(false);
    var sJson = js_objectToJson(oEnvioMovimento);
    var oAjax = new Ajax.Request(
      url,
      {
        method    : 'post',
        parameters: 'json='+sJson,
        onComplete: js_retornoConfigurarPagamentos
      }
    );
  }

  function js_retornoConfigurarPagamentos(oAjax) {

    js_removeObj("msgBox");
    js_liberaBotoes(true);
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.iItipoAutent != 3 && oRetorno.status == 1) {

      iCodigoOrdemAuxiliar = oRetorno.iCodigoOrdemAuxiliar;
      if ($('autenticar').checked) {

        aAutenticacoes       = oRetorno.aAutenticacoes;
        iIndice              = 0;
        js_autenticar(oRetorno.aAutenticacoes[0],false);
        if ($('reemisaoop').checked) {
          js_emiteOrdens(oRetorno.aAutenticacoes);
        }
      } else {

        if ($('emitirordemauxiliar').checked || ($('opmanutencaoincluir').checked || $('opmanutencaoexcluir').checked)) {
          js_emitirOrdemAuxiliar($F('e42_dtpagamento'),oRetorno.iCodigoOrdemAuxiliar);
        }

        aAutenticacoesGlobal = oRetorno.aAutenticacoes
        if ($('reemisaoop').checked) {

          var aMovimentosSelecionados = gridNotas.getSelection();
          var aMovimentosImprimir = new Array();
          aMovimentosSelecionados.each(function(aMovimento, iSeq) {
            aMovimentosImprimir.push(aMovimento[0]);
          });
          js_emiteOrdens(aAutenticacoesGlobal, aMovimentosImprimir);
        }
        $('opmanutencaonda').checked = true;
        js_pesquisarOrdens();
      }

      var movimentosTransmissao = [];
      gridNotas.getSelection().each(
        function (dadosMovimento) {

          if (dadosMovimento[9] === '3') {
            movimentosTransmissao.push(dadosMovimento[0]);
          }
        }
      );

      if (movimentosTransmissao.length > 0) {

        var DBViewConfiguracaoEnvio = new DBViewConfiguracaoEnvioTransmissao(movimentosTransmissao, 1);
        DBViewConfiguracaoEnvio.verificarMovimentos();
      } else {
        alert("Movimentos atualizados com sucesso!");
      }

    } else {
      alert(oRetorno.message.urlDecode());
    }
  }

  function js_calculaValor(oTextObj, iCodMov) {

    var nValorAut = js_strToFloat($('valoraut'+iCodMov).innerHTML);
    var nRetencao = js_strToFloat($('retencao'+iCodMov).innerHTML);
    var nValorMaximo = nValorAut  - nRetencao;
    if (new Number(oTextObj.value) > nValorMaximo.toFixed(2) || new Number(oTextObj.value) <= 0) {
      oTextObj.value  = nValorMaximo;
    }
  }

  function js_liberaBotoes(lLiberar) {


    if (lLiberar) {

      $('pesquisar').disabled = false;
      $('atualizar').disabled   = false;

    } else {

      $('pesquisar').disabled = true;
      $('atualizar').disabled   = true;

    }
  }

  function js_getSaldos(objSelect) {

    if (objSelect.value != 0) {

      var dtBase = $F('e42_dtpagamento');
      if ($F('e42_dtpagamento') == '') {
        dtBase = sDataDia;
        $('e42_dtpagamento').focus();
      }
      if ($('descrConta').innerHTML == objSelect.options[objSelect.selectedIndex].innerHTML) {
        return false;
      }
      js_divCarregando("Aguarde, Verificando saldo da conta.","msgBox");
      $('descrConta').innerHTML = objSelect.options[objSelect.selectedIndex].innerHTML;
      var url       = 'emp4_agendaPagamentoRPC.php';
      var sJson = '{"exec":"getSaldos","params":[{"iCodTipo":"'+objSelect.value+'","dtBase":"'+dtBase+'"}]}';
      var oAjax   = new Ajax.Request(
        url,
        {
          method    : 'post',
          parameters: 'json='+sJson,
          onComplete: js_retornoGetSaldos
        }
      );
    }

  }
  function js_retornoGetSaldos(oAjax) {

    js_removeObj("msgBox");
    var oRetorno               = eval("("+oAjax.responseText+")");
    $('saldotesouraria').value = new Number(oRetorno.oSaldoTes.rnvalortesouraria);
    $('totalcheques').value    = new Number(oRetorno.oSaldoTes.rnvalorreservado);
    $('saldoatual').value      = new Number(oRetorno.oSaldoTes.rnsaldofinal).toFixed(2);
  }

  function js_lancarRetencao(iCodNota, iCodOrd, iNumEmp, iCodMov){

    var lSession     = "false";
    var dtPagamento  = $F('e42_dtpagamento');
    var nValor       = new Number($('valorrow'+iCodMov).value);
    var nValorRetido = js_strToFloat($('retencao'+iCodMov).innerHTML);
    if (dtPagamento == '') {

      alert('Antes de recalcular as retencoes, informe a data de pagamento');
      return false;

    }
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_retencao',
      'emp4_lancaretencoes.php?iNumNota='+iCodNota+'&nValorBase='+(nValor+nValorRetido)+
      '&iNumEmp='+iNumEmp+'&iCodOrd='+iCodOrd+"&lSession="+lSession
      +'&dtPagamento='+dtPagamento+'&iCodMov='+iCodMov+'&callback=true',
      'Lancar Retenções',
      true,
      22,
      0,
      document.body.clientWidth,
      document.body.clientHeight);

  }

  function js_atualizaValorRetencao(iCodMov, nValor, iNota, iCodOrdem, lValidar) {

    $('valorrow'+iCodMov).value     = new Number(js_strToFloat($('valoraut'+iCodMov).innerHTML) - new Number(nValor)).toFixed(2);
    $('retencao'+iCodMov).innerHTML = js_formatar(nValor,'f');
    if (new Number(nValor).valueOf() > 0) {
      $('valorrow'+iCodMov).readOnly = true;
    } else {
      $('valorrow'+iCodMov).readOnly = false;
    }
    if (lValidar != null) {
      $('validarretencao'+iCodMov).innerHTML = lValidar;
    }
    db_iframe_retencao.hide();
    $('TotalForCol14').innerHTML = js_formatar(gridNotas.sum(14).toFixed(2),'f');
    $('TotalForCol13').innerHTML = js_formatar(gridNotas.sum(13).toFixed(2),'f');

  }

  function js_setContaPadrao(iCodigoConta) {

    var aItens = gridNotas.getElementsByClass('ctapag');
    var oUltimoSelect = null;

    for (var i = 0; i < aItens.length; i++) {
      if (aItens[i].parentNode.parentNode.childNodes[0].childNodes[0].checked == true) {

        if ($F('e83_codtipo') == "0") {
          aItens[i].value = "";
        }else{
          aItens[i].value = $F('e83_codtipo');
        }

        oUltimoSelect = aItens[i];

      }
    }


    if (aItens.length > 0) {
      js_getSaldos(oUltimoSelect);
    }

  }


  function js_setFormaPadrao(iForma) {


    var aItens = gridNotas.getElementsByClass('formapag');
    for (var i = 0; i < aItens.length; i++) {
      if (aItens[i].parentNode.parentNode.childNodes[0].childNodes[0].checked == true) {
        aItens[i].value = $F('e96_codigo');
      }
    }
  }

  function js_reset() {

    $('descrConta').innerHTML      = '';
    $('saldotesouraria').value     = '';
    $('totalcheques').value        = '';
    $('saldoatual').value          = '';

  }

  function js_autenticar(oAutentica, lReautentica) {

    var sPalavra = 'Autenticar';
    if (lReautentica) {
      var sPalavra = "Autenticar novamente";
    }
    if (confirm(sPalavra+' a Nota '+oAutentica.iNota+'?')) {

      var oRequisicao      = new Object();
      oRequisicao.exec     = "Autenticar";
      oRequisicao.sString  = oAutentica.sAutentica;
      var sJson            = js_objectToJson(oRequisicao);
      var oAjax = new Ajax.Request(
        'emp4_pagarpagamentoRPC.php',
        {
          method    : 'post',
          parameters: 'json='+sJson,
          onComplete: js_retornoAutenticacao
        }
      );

    } else {

      iIndice++;
      if (aAutenticacoes[iIndice]) {
        js_autenticar(aAutenticacoes[iIndice],false);
      } else {


        if ($('emitirordemauxiliar').checked || ($('opmanutencaoincluir').checked || $('opmanutencaoexcluir').checked)) {
          js_emitirOrdemAuxiliar($F('e42_dtpagamento'), iCodigoOrdemAuxiliar);
        }

        $('opmanutencaonda').checked = true;
        js_pesquisarOrdens();

      }
    }
  }

  function js_showAutenticar(obj) {
    if (obj.checked) {

      $('showautenticar').style.visibility = 'visible';
      $('autenticar').checked               = true;
      $('showreemissao').style.visibility = 'visible';

    } else {

      $('showautenticar').style.visibility = 'hidden';
      $('showreemissao').style.visibility  = 'hidden';
      $('autenticar').checked              = false;
      $('reemisaoop').checked              = false;

    }
  }


  function js_reemissaoOP(oObjeto) {

    if (oObjeto.checked) {
      $('autenticar').checked = false;
      $('autenticar').setAttribute("disabled", "disabled");
    } else {
      $('autenticar').removeAttribute("disabled");
    }
  }


  function js_retornoAutenticacao(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {

      js_autenticar(aAutenticacoes[iIndice], true);

    } else {

      if ($('emitirordemauxiliar').checked || ($('opmanutencaoincluir').checked || $('opmanutencaoexcluir').checked)) {
        js_emitirOrdemAuxiliar($F('e42_dtpagamento'), iCodigoOrdemAuxiliar);
      }
      $('opmanutencaonda').checked = true;
      js_pesquisarOrdens();

    }
  }

  function js_emitirOrdemAuxiliar(dtAutoriza, iOrdemAuxiliar) {

    window.open('emp2_ordempagamentoauxiliar002.php?dtAutorizacao=&iAgenda='+iOrdemAuxiliar,'','location=0');
  }

  $('esconderfiltros').onclick = function () {

    var aFiltros = gridNotas.getElementsByClass('filtros');
    aFiltros.each(function (oNode, id) {

      if (oNode.style.display == '') {

        oNode.style.display = 'none';
        $('togglefiltros').src='imagens/seta.gif';

      } else if (oNode.style.display == 'none') {

        oNode.style.display = '';
        $('togglefiltros').src='imagens/setabaixo.gif'

      }
    });
  }
  $('esconderTotais').onclick = function () {

    var aFiltros = gridNotas.getElementsByClass('tabelatotais');
    aFiltros.each(function (oNode, id) {

      if (oNode.style.display == '') {

        oNode.style.display = 'none';
        $('toggletotais').src='imagens/seta.gif';

      } else if (oNode.style.display == 'none') {

        oNode.style.display = '';
        $('toggletotais').src='imagens/setabaixo.gif'

      }
    });
  }

  /**
   * Agrupa as os movimentos selecionados
   */
  function js_agruparMovimentos() {

    /**
     * - O movimento nao pode estar configurado.
     * - Não pode haver retencoes lançadas para o movimento
     */
    var oParam                = new Object();
    oParam.exec               = "agruparMovimentos";
    oParam.aMovimentosAgrupar =  new Array();

    var aMovimentos           = gridNotas.getSelection("object");
    var iOPAnterior = 0;
    for (var i = 0; i < aMovimentos.length; i++) {

      var oMovimento      = new Object();
      var iMovimento      = aMovimentos[i].aCells[1].getValue();
      var iOP             = aMovimentos[i].aCells[5].getValue();
      var nValor          = aMovimentos[i].aCells[14].getValue();
      var sConCarPeculiar = aMovimentos[i].aCells[4].getValue();
      var nValorRetencao = js_strToFloat(aMovimentos[i].aCells[13].getValue()).valueOf();
      if (i > 0 && iOPAnterior !=  iOP ) {

        alert('Foram Selecionados Movimentos de OP diferentes!\nProcedimento Cancelado');
        return false;

      }
      if (aMovimentos[i].getClassName() != "normal") {

        alert('Movimento '+iMovimento+' da OP '+iOP+' Está Configurada.');
        return false;

      }

      if (nValorRetencao != 0) {

        alert('Movimento '+iMovimento+' da OP '+iOP+' possui retenções lancadas.');
        return false;

      }
      iOPAnterior                = iOP;
      oMovimento.e81_codmov      = iMovimento;
      oMovimento.e82_codord      = iOP;
      oMovimento.nValor          = nValor;
      oMovimento.sConCarPeculiar = sConCarPeculiar
      oParam.aMovimentosAgrupar.push(oMovimento);
    }

    var iTotalString =new String(aMovimentos.length).extenso(false).ucFirst();
    if (!confirm('Confirma o agrupamento de '+iTotalString+' movimentos?')){
      return false;
    }
    js_divCarregando("Aguarde, Agrupando Movimentos.","msgBox");
    var oAjax = new Ajax.Request(
      'emp4_manutencaoPagamentoRPC.php',
      {
        method    : 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: js_retornoAgruparMovimentos
      }
    );

  }

  function js_retornoAgruparMovimentos(oResponse) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oResponse.responseText+")");
    if (oRetorno.status == 1) {

      alert(oRetorno.totalagrupados.extenso(false).ucFirst()+' movimentos foram agrupados com sucesso.');
      js_pesquisarOrdens();

    } else {
      alert(oRetorno.message.urlDecode());
    }
  }

  function js_visualizarRelatorio() {

    var oParam           = new Object();
    oParam.iOrdemIni     = $F('e82_codord');
    oParam.iOrdemFim     = $F('e82_codord02');
    oParam.iCodEmp       = $F('e60_codemp');
    oParam.dtDataIni     = $F('dataordeminicial');
    oParam.dtDataFim     = $F('dataordemfinal');
    oParam.iNumCgm       = $F('z01_numcgm');
    oParam.iRecurso      = $F('o15_codigo');
    oParam.sDtAut        = $F('e42_dtpagamento');
    oParam.iOPauxiliar   = $F('e42_sequencial');
    oParam.iAutorizadas  = $F('ordensautorizadas');
    oParam.lAtualizadas  = $('configuradas').checked;
    oParam.lNormais      = $('normais').checked;
    oParam.lChequeArq    = $('comMovs').checked;
    oParam.orderBy       = $F('orderby');
    sUrl = "emp2_manutencaopagamentos002.php?json="+Object.toJSON(oParam);
    window.open(sUrl, '', 'location=0');

  }
  $('agruparmovimentos').observe("click",js_agruparMovimentos);
  js_init();



  /**
   *  Abre lookup para pesquisar na tabela concarpeculiar
   */
  function js_lookupConCarPeculiar(iCodigoMovimento) {
    idLinhaSelecionada = $('ccp_'+iCodigoMovimento);
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_completaConCarPeculiar|c58_sequencial','Pesquisa',true);
  }
  /**
   *  Preenche a linha com o ID da concarpeculiar selecionada
   */
  function js_completaConCarPeculiar(s_c58_concarpeculiar) {
    idLinhaSelecionada.innerHTML = s_c58_concarpeculiar;
    db_iframe_concarpeculiar.hide();
  }

  function js_emiteOrdens(aOrdens, aMovimentos) {

    var sListaOrdem = '';
    var sVirgula    = '';
    aOrdens.each(function (oOrdem, iSeq) {

      sListaOrdem += sVirgula+""+oOrdem.iNota;
      sVirgula   = ",";
    });
    sVirgula        = '';
    sListaMovimento = '';

    aMovimentos.each(function (aMovimento, iSeq) {

      sListaMovimento += sVirgula+""+aMovimento;
      sVirgula         = ",";
    });
    window.open('emp2_emitenotaliqpormovimento002.php?e50_codord='+sListaOrdem+'&e81_codmov='+sListaMovimento,
      '',
      'location=0'
    );
  }

  function verificaCadastroAutenticadora() {

    new AjaxRequest(
      'cai4_autenticadora.RPC.php',
      {exec : 'possuiCadastro'},
      function (oRetorno, lErro) {

        if (!oRetorno.possuiCadastro) {
          alert ("IP "+oRetorno.ip_usuario.urlDecode()+" não cadastrado como autenticadora.");
        }
      }
    ).setMessage('Aguarde, verificando cadastro de autenticadora...').execute();
  }

  verificaCadastroAutenticadora();
  $('col1').style.width = "10px";
</script>
