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

//MODULO: pessoal
$clcadferia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");

db_sel_cfpess($r30_anousu,$r30_mesusu);
?>
<style type="text/css">
  fieldset > table > tbody > tr > td:first-child {
    width: 207px;
    text-align: left;
}
/*
fieldset > table {
    width: 100%;
}

fieldset > table >tbody >tr > td> select {
    height: 18px;
}
*/
</style>
<form name="form1" method="post">
  <div class="container">
    <fieldset><legend>Servidor</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?=@$Tr30_regist?>"><?
          db_ancora(@$Lr30_regist,"js_pesquisar30_regist(true);",1);
          ?></td>
          <td nowrap><?
          db_input('r30_regist',6,1,true,'text',1,"onchange='js_pesquisar30_regist(false);'")
          ?> <?
          db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
          db_input('z01_numcgm',10,0,true,'hidden',3,'');
          db_input('r30_anousu',4,0,true,'hidden',3,'');
          db_input('r30_mesusu',2,0,true,'hidden',3,'');
          if(!isset($ponto)){
            $ponto = "S";
          }
          db_input('r30_ponto',2,0,true,'hidden',3,'');
          ?></td>
        </tr>
      </table>
    </fieldset>
    <fieldset><legend><strong>Período Aquisitivo</strong></legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tr30_perai?>"><?
        db_ancora("<b>$RLr30_perai:</b>", "", 3);
        ?></td>
        <td ><?
        if(isset($r30_perai_dia) && trim($r30_perai_dia) != "" && isset($r30_perai_mes) && trim($r30_perai_mes) != "" && isset($r30_perai_ano) && trim($r30_perai_ano) != ""){
          $r30_perai_ant = $r30_perai_ano."-".$r30_perai_mes."-".$r30_perai_dia;
        }
        if(isset($r30_peraf_dia) && trim($r30_peraf_dia) != "" && isset($r30_peraf_mes) && trim($r30_peraf_mes) != "" && isset($r30_peraf_ano) && trim($r30_peraf_ano) != ""){
          $r30_peraf_ant = $r30_peraf_ano."-".$r30_peraf_mes."-".$r30_peraf_dia;
        }
        db_inputdata('r30_perai', @$r30_perai_dia, @$r30_perai_mes, @$r30_perai_ano, true, 'text', ($db_opcao == 3 ? 3 : 1), "onchange='js_verificaaquiini(1, event);'","","","parent.js_verificaaquiini(1, event);");
        db_input('r30_perai', 10, $Ir30_perai, true, 'hidden', 3,'','r30_perai_ant');
        ?> <b>a</b> <?
        db_inputdata('r30_peraf', @$r30_peraf_dia, @$r30_peraf_mes, @$r30_peraf_ano, true, 'text', ($db_opcao == 3 ? 3 : 1), "onchange='js_verificaaquifim();'","","","parent.js_verificaaquifim();");
        db_input('r30_peraf', 10, $Ir30_perai, true, 'hidden', 3,'','r30_peraf_ant');
        ?></td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tr30_faltas?>"><?
          db_ancora(@$Lr30_faltas, "", 3);
        ?>
        </td>
        <td>
        <?
          db_input('r30_faltas', 7, 1, true, 'text', $db_opcao);
        ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tr30_ndias?>">    
        <?
          db_ancora(@$Lr30_ndias, "", 3);
        ?></td>
        <td><?
        if(!isset($r30_ndias)){
          $r30_ndias = 30;
        }
        db_input('r30_ndias', 7, 1, true, 'text', $db_opcao,"onchange='js_montaselect(this.value)'");
        ?></td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tr30_abono?>"><?
        db_ancora(@$Lr30_abono, "", 3);
        ?></td>
        <td><?
        if(!isset($r30_abono) || (isset($r30_abono) && trim($r30_abono) == "")){
          $r30_abono = 0;
        }
        db_input('r30_abono', 7, 1, true, 'text', $db_opcao,"");
        ?></td>
      </tr>
    </table>
    </fieldset>
    <fieldset><legend><strong>Primeiro Período de Férias</strong></legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tr30_proc1?>">
        <?php db_ancora("<b>$RLr30_proc1:</b>", "", 3);?>
        </td>
        <td>
        <?php 
          if( isset($r30_proc1)) {
            $r30_proc1 = implode("/", array_reverse(explode("/", $r30_proc1)));
          }

          db_input('r30_proc1', 7, $Ir30_proc1, true, 'text', $db_opcao, 'onkeypress="js_mascaraCompetencia(this, event);"');
         ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tr30_tip1?>">
        <?php
          db_ancora("<b>$RLr30_tip1:</b>", "", 3);
        ?>
        </td>
        <td>
<?
        $arr_tip1 = Array();
        db_select(
          "r30_tip1",
          $arr_tip1,
          true,
          $db_opcao,
          'onchange="js_validamtipo(); js_montaPeriodo(document.form1.r30_per1i, document.form1.r30_per1f); js_montaPeriodo(document.form1.r30_per2i, document.form1.r30_per2f);"');
        ?></td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tr30_per1i?>"><?
        db_ancora("$Lr30_per1i", "", 3);
        ?></td>
        <td>
          <?php
          db_inputdata(
            'r30_per1i',
            @$r30_per1i_dia,
            @$r30_per1i_mes,
            @$r30_per1i_ano,
            true,
            'text',
            $db_opcao,
            'onchange="js_montaPeriodo(document.form1.r30_per1i, document.form1.r30_per1f);"',
            '',
            '',
            "parent.js_montaPeriodo(parent.document.form1.r30_per1i, parent.document.form1.r30_per1f);"
          );
          ?>
          <b>a</b>
          <?php
          db_inputdata(
            'r30_per1f',
            @$r30_per1f_dia,
            @$r30_per1f_mes,
            @$r30_per1f_ano,
            true,
            'text',
            $db_opcao,
            "",
            "",
            "",
            "parent.js_setDiasGozados();"
          );
          ?>
        </td>
      </tr>
      <tr id='dias-gozados1' style='display: none;'>
        <td><b>Dias Gozados</b></td>
        <td ><? 
        db_input('r30_diasgozados1', 7, 1, true, 'text', $db_opcao, "readonly='readonly'");
        ?></td>
      </tr>
    </table>
    </fieldset>
    <fieldset><legend><strong>Segundo Período de Férias</strong></legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tr30_proc2?>">
          <?php  db_ancora("<b>$RLr30_proc2:</b>", "", 3); ?>
        </td>
        <td>
        <?
          if(isset($r30_proc2)) {
            $r30_proc2 = implode("/", array_reverse(explode("/", $r30_proc2)));
          }

          db_input('r30_proc2', 7, $Ir30_proc2, true, 'text', $db_opcao, 'onkeypress="js_mascaraCompetencia(this, event);"');
        ?></td>
        <td><?
        $r30_tip2 = "09";
        db_input('r30_tip2', 7, $Ir30_tip2, true, 'hidden', 3);
        db_input('saldo', 7, 0, true, 'hidden', 3);
        ?></td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tr30_per2i?>"><?
        db_ancora("$Lr30_per2i", "", 3);
        ?></td>
        <td >
          <?php
          db_inputdata(
            'r30_per2i',
            @$r30_per2i_dia,
            @$r30_per2i_mes,
            @$r30_per2i_ano,
            true,
            'text',
            $db_opcao,
            'onchange="js_montaPeriodo(document.form1.r30_per2i, document.form1.r30_per2f);"',
            '',
            '',
            'parent.js_montaPeriodo(parent.document.form1.r30_per2i, parent.document.form1.r30_per2f);'
          );
          ?>
          <b>a</b>
          <?php
          db_inputdata(
            'r30_per2f',
            @$r30_per2f_dia,
            @$r30_per2f_mes,
            @$r30_per2f_ano,
            true,
            'text',
            $db_opcao,
            '',
            '',
            '',
            'parent.js_validaTotalDias()'
          );
          ?>
        </td>
      </tr>
      
      <tr id='dias-gozados2' style='display: none;'>
        <td><b>Dias Gozados:</b></td>
        <td ><? 
        db_input('r30_diasgozados2', 7, "", true, 'text', $db_opcao, "readonly='readonly'");
        ?></td>
      </tr>
    </table>
    </fieldset>
<input
  name="<?=$db_opcao == 1 ? 'enviar' : ($db_opcao == 2 ? 'alterar' : 'excluir')?>"
  type="submit" id="db_opcao" value="Processar dados"
  onclick="return js_verificadados();" <?php echo isset($r30_regist) && !empty($r30_regist) ? "" : "disabled"; ?>> 

<input name="" type="button" value="Nova Pesquisa" onclick="js_pesquisar30_regist(true);" />

<?
  if(isset($opcao)){
    ?> <input name="novo" type="submit" id="db_opcao" value="Novo"> <?
  }
?>
</div>
<?php 
 if(isset($r30_regist) && trim($r30_regist) != ""){

    echo "<fieldset>";
    echo "<legend>Períodos Lançados</legend>";
    include(modification("dbforms/db_classesgenericas.php"));
    $chavepri = array ("r30_anousu" => @ $r30_anousu, "r30_mesusu" => @ $r30_mesusu, "r30_regist" => @ $r30_regist, "r30_perai" => @ $r30_perai);
    $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
    $cliframe_alterar_excluir->chavepri = $chavepri;
    $dbwhere = " r30_anousu = $r30_anousu and r30_mesusu = $r30_mesusu and r30_regist = $r30_regist ";
    $cliframe_alterar_excluir->sql = $clcadferia->sql_query_file(null,"r30_anousu, r30_mesusu, r30_regist, r30_perai, r30_peraf, r30_per1i,
                                                                     r30_per1f, r30_proc1, r30_per2i, r30_per2f, r30_proc2, r30_ndias, 
                                                                     r30_faltas, r30_abono","r30_per2i desc,r30_per1i desc ", $dbwhere);
    $cliframe_alterar_excluir->campos        = "r30_perai, r30_peraf, r30_ndias, r30_faltas, r30_abono, r30_per1i, r30_per1f, r30_proc1,r30_per2i, r30_per2f, r30_proc2";
    $cliframe_alterar_excluir->legenda       = "";
    $cliframe_alterar_excluir->iframe_width  = "100%";
    $cliframe_alterar_excluir->iframe_height = "100%";
    $cliframe_alterar_excluir->opcoes        = 1;
    $cliframe_alterar_excluir->fieldset      = false;
    $cliframe_alterar_excluir->iframe_alterar_excluir(1);
    echo "</fieldset>";
  }
?>
</form>
<script>
document.getElementById("r30_perai").addEventListener("keyup", function(e){js_verificaaquiini(1, e)}, false);
function js_pesquisar30_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ar&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{

    if(document.form1.r30_regist.value != ''){ 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ar&pesquisa_chave='+document.form1.r30_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{

      document.form1.z01_nome.value = '';
      location.href = 'pes4_implantaferias001.php';
    }
  }
}
function js_mostrapessoal(chave,erro) {

  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.r30_regist.focus(); 
    document.form1.r30_regist.value = ''; 
  } else {
    location.href = 'pes4_implantaferias001.php?r30_regist='+document.form1.r30_regist.value;
  }
}

function js_mostrapessoal1(chave1,chave2) {

  document.form1.r30_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  location.href = 'pes4_implantaferias001.php?r30_regist='+chave1;
}

function js_verificadados(){
  x = document.form1;
  retorno = true;
  if((document.form1.r30_perai_dia.value == "" || document.form1.r30_perai_mes.value == "" || document.form1.r30_perai_ano.value == "") &&
     (document.form1.r30_peraf_dia.value == "" || document.form1.r30_peraf_mes.value == "" || document.form1.r30_peraf_ano.value == "")){
    alert("Informe, corretamente, o período aquisitivo.");
    //document.form1.r30_perai_dia.select();
    //document.form1.r30_perai_dia.focus();
    document.form1.r30_perai.select();
    document.form1.r30_perai.focus();
    retorno = false;
  }else if((document.form1.r30_per1i_dia.value == "" || document.form1.r30_per1i_mes.value == "" || document.form1.r30_per1i_ano.value == "") &&
           (document.form1.r30_per2i_dia.value == "" || document.form1.r30_per2i_mes.value == "" || document.form1.r30_per2i_ano.value == "")){
    alert("Informe, corretamente, o período de gozo.");
//    document.form1.r30_per1i_dia.select();
//    document.form1.r30_per1i_dia.focus();
    document.form1.r30_per1i.select();
    document.form1.r30_per1i.focus();
    retorno = false;
  }

  var iAnoCompetenciaPagamentoGozo1 = parseInt(document.form1.r30_proc1.value.replace(/[^\d]/g, '').substr(2, 6));
  var iMesCompetenciaPagamentoGozo1 = parseInt(document.form1.r30_proc1.value.replace(/[^\d]/g, '').substr(0, 2));
  if((document.form1.r30_per1i_dia.value != "" && document.form1.r30_per1i_mes.value != "" && document.form1.r30_per1i_ano.value != "")){
    if((document.form1.r30_per1f_dia.value == "" || document.form1.r30_per1f_mes.value == "" || document.form1.r30_per1f_ano.value == "")){
      alert("Informe, corretamente, o período de gozo inicial.");
//      document.form1.r30_per1i_dia.select();
//      document.form1.r30_per1i_dia.focus();
      document.form1.r30_per1i.select();
      document.form1.r30_per1i.focus();
      retorno = false;
    }
  }

  if(document.form1.r30_proc1.value == "" || (iAnoCompetenciaPagamentoGozo1 < parseInt(document.form1.r30_per1i_ano.value)) || (iMesCompetenciaPagamentoGozo1 > 12 || iMesCompetenciaPagamentoGozo1 < 1)) {
    alert("Informe corretamente a data de pagamento do período de gozo inicial.");
    document.form1.r30_proc1.focus();
    retorno = false;
  }
 
  var iAnoCompetenciaPagamentoGozo2 = parseInt(document.form1.r30_proc2.value.replace(/[^\d]/g, '').substr(2, 6));
  var iMesCompetenciaPagamentoGozo2 = parseInt(document.form1.r30_proc2.value.replace(/[^\d]/g, '').substr(0, 2));
  if(document.form1.r30_per2i != "") {
    if(document.form1.r30_per2i_dia.value != "" && document.form1.r30_per2i_mes.value != "" && document.form1.r30_per2i_ano.value != ""){
      if(document.form1.r30_per2f_dia.value == "" || document.form1.r30_per2f_mes.value == "" || document.form1.r30_per2f_ano.value == ""){
        alert("Informe, corretamente, o período de gozo final.");
  //      document.form1.r30_per2i_dia.select();
  //      document.form1.r30_per2i_dia.focus();
        document.form1.r30_per2i.select();
        document.form1.r30_per2i.focus();
        retorno = false;
      }
    }
  }

  var iTipoPagamento = parseInt(document.form1.r30_tip1.value);
  if(iTipoPagamento != 1 && iTipoPagamento != 5 && iTipoPagamento != 6 && iTipoPagamento != 7 && iTipoPagamento != 8) {
    if(document.form1.r30_proc2.value == "" || (iAnoCompetenciaPagamentoGozo2 < parseInt(document.form1.r30_per2i_ano.value)) || (iMesCompetenciaPagamentoGozo2 > 12 || iMesCompetenciaPagamentoGozo2 < 1)) {
      alert("Informe corretamente a data de pagamento do período de gozo final.");
      document.form1.r30_proc2.focus();
      retorno = false;
    }

    if( document.form1.r30_per2i.value == '' || document.form1.r30_per2f.value == '') {
      alert("Informe, corretamente, o período de gozo final.");
      return false;
    }
  }
  return retorno;
}

/**
 * Cria mascara para competencia.
 */
function js_mascaraCompetencia(oElemento, oEvento) {

  if ( oEvento.keyCode == 8 ) {
    return false;
  }

  var sData = oElemento.value.replace('/', '');

  if (oElemento.value.length == 0){
    return true;
  }

  var iMes  = sData.substring(0, 2),
      iAno  = sData.substring(2, 6);

  /**
   * Verifica se é um numero inteiro.
   */
  var expr = new RegExp("[^0-9]+");

  if (iMes.length > 0 && iMes.match(expr)) {

    oElemento.value = '';
    return false;  
  }

  if (iAno.length > 0 && iAno.match(expr)) { 

    oElemento.value = '';
    return false;
  }
 
  /**
   * Verifica se o mês não é 0.
   */
  if ((iMes.length == 2 && iMes == 0)) {

    oElemento.value = '';
    return false;
  };

  /**
   * Verifica se o Mês não é maior que 12
   */
  if (iMes > 12) {

    oElemento.value = '';
    return false;
  };

  oElemento.value = iMes + '/' + iAno;
}

function js_montaPeriodo(oInputInicio, oInputFinal) {

  /**
   * Define variáveis.
   */
  
  var oEl  = document.form1.r30_tip1;
      iDia = new Number(oInputInicio.value.substring(0, 2)),
      iMes = new Number(oInputInicio.value.substring(3, 5)),
      iAno = new Number(oInputInicio.value.substring(6, 10)),
      iNum = new Number(oEl.options[oEl.selectedIndex].getAttribute('data-ferias'));

  if (oInputInicio.getAttribute('name') == 'r30_per2i') {

    if (new Number(oEl.value) != 2 &&
        new Number(oEl.value) != 3 &&
        new Number(oEl.value) != 4) {

      return false;
    };
  };

  /**
   * Verifica se a data foi preenchida.
   */
  if (iDia == 0 ||
      iMes == 0 ||
      iAno == 0) {

    oInputFinal.value = '';
    return false
  };

  var oFinal = new Date(iAno, iMes -1, iDia +iNum -1);

  var oDiaFinal = document.getElementById(oInputFinal.getAttribute('id') + '_dia'),
      oMesFinal = document.getElementById(oInputFinal.getAttribute('id') + '_mes'),
      oAnoFinal = document.getElementById(oInputFinal.getAttribute('id') + '_ano');


  oDiaFinal.value = oFinal.getDate();
  oMesFinal.value = oFinal.getMonth()+1;
  oAnoFinal.value = oFinal.getFullYear();


  oInputFinal.value = oFinal.toLocaleDateString('pt-BR').replace("-", "/", 'g');
}

function js_verificaaquiini(iOpcao, e = null) {
  /**
   * Define variáveis.
   */
  var iDia = new Number(document.form1.r30_perai_dia.value),
      iMes = new Number(document.form1.r30_perai_mes.value),
      iAno = new Number(document.form1.r30_perai_ano.value);

  /**
   * Verifica se a função foi chamada através de um keyup
   * e não executa caso não tenha sido teclado um número ou 
   * o campo data de início não esteja completo 
   */
  if ( e != null && e.type == "keyup" ) {
    if ( isNaN(e.key) ) {
      return false;
    } else {
      if( document.form1.r30_perai.value.length < 10 ) {
        return false;
      }
    }
  } else if ( e != null && e.type == "change" ) {
    if ( document.form1.r30_perai.value.indexOf(document.form1.r30_perai_ano.value) > -1 ) {
      return false;
    }
  }

  /**
   * Verifica se a data foi preenchida.
   */
  if (iDia == 0 ||
      iMes == 0 ||
      iAno == 0) {

    document.form1.r30_peraf_dia.value = '';
    document.form1.r30_peraf_mes.value = '';
    document.form1.r30_peraf_ano.value = '';
    document.form1.r30_peraf    .value = '';
    return false
  };

  /**
   * Valida se foi passada uma data válida.
   */
  if (iDia == NaN ||
      iMes == NaN ||
      iAno == NaN) {

    document.form1.r30_peraf_dia.value = '';
    document.form1.r30_peraf_mes.value = '';
    document.form1.r30_peraf_ano.value = '';
    document.form1.r30_peraf    .value = '';
    return false
  };

  /**
   * Valida data.
   */
  if (iDia < 1 || iDia > 31) {
    return false;
  };

  if (iMes < 1 || iMes > 12) {
    return false;
  };

  if (iAno < 1970) {
    return false;
  };

  /**
   * Competencia atual.
   */
  var iAnoFolha    = <?= DBPessoal::getAnoFolha(); ?>,
      iMesFolha    = <?= DBPessoal::getMesFolha(); ?>,
      lMensagem    = true;

  var oInicial     = new Date(iAno,      iMes      -1, iDia),    // Período aquisitivo inicial.
      oFinal       = new Date(iAno   +1, iMes      -1, iDia -1), // Período aquisitivo final.
      oTotal       = new Date(iAno,      iMes      -1, 1),       // Período aquisitivo total.
      oCompetencia = new Date(iAnoFolha, iMesFolha -1, 1);       // Competencia atual da folha.

  <?php if (isset($enviar)) { ?>
    var lMensagem = false;
  <?php } ?>

  if (oTotal.getTime() > oCompetencia.getTime() && lMensagem) {

    /**
     * Para prefeituras que pagam as férias antecipadas.
     */
    if (iOpcao == 1 &&
       (iOpcao == 2 ||
       confirm('O período aquisitivo ainda não venceu.\nContinua geração das Férias?'))) {

      // Não faz nada
    }
  };

  document.form1.r30_peraf_dia.value = oFinal.getDate();
  document.form1.r30_peraf_mes.value = oFinal.getMonth() + 1;
  document.form1.r30_peraf_ano.value = oFinal.getFullYear();
  document.form1.r30_peraf    .value = oFinal.toLocaleDateString().replace("-", "/", 'g');
}

function js_verificaaquifim(){
  x = document.form1;

  diai = new Number(x.r30_perai_dia.value);
  mesi = new Number(x.r30_perai_mes.value);
  anoi = new Number(x.r30_perai_ano.value);
  mesi-= 1;

  diaf = new Number(x.r30_peraf_dia.value);
  mesf = new Number(x.r30_peraf_mes.value);
  anof = new Number(x.r30_peraf_ano.value);
  mesf-= 1;

  if(x.r30_perai_dia.value == "" || x.r30_perai_mes.value == "" || x.r30_perai_ano.value == ""){
    alert("Informe o período aquisitivo inicial.");
    x.r30_perai_dia.select();
    x.r30_perai_dia.focus();
  }else if(x.r30_peraf_dia.value != "" && x.r30_peraf_mes.value != "" && x.r30_peraf_ano.value != ""){
    perai = new Date(anoi, mesi, diai);
    peraf = new Date(anof, mesf, diaf);
    if(peraf < perai){
      alert("Período aquisitivo final inválido.");
      x.r30_peraf_dia.value = '';
      x.r30_peraf_mes.value = '';
      x.r30_peraf_ano.value = '';
      x.r30_peraf.value = '';
//      x.r30_peraf_dia.focus();
      x.r30_peraf.focus();
    }else{
      perano = (peraf - perai);
      perano = new Date(perano);
      perano/= 86400000;
      perano = new Number(perano);
      perano = Math.floor(perano);

      if(perano > 365){
        alert("ALERTA: Período aquisitivo maior que 1 (um) ano.");
      }
    }
  }
}
function js_montaselect(ndias){
  ndias = new Number(ndias);
  for(i=0;i<document.form1.r30_tip1.length;i++){
    document.form1.r30_tip1.options[i] = null;
    i = -1;
  }
  if (ndias == 30) {

    document.form1.r30_tip1.options[0] = new Option('01 - 30 dias ferias',                 '01');
    document.form1.r30_tip1.options[1] = new Option('02 - 20 dias ferias',                 '02');
    document.form1.r30_tip1.options[2] = new Option('03 - 15 dias ferias',                 '03');
    document.form1.r30_tip1.options[3] = new Option('04 - 10 dias ferias',                 '04');
    document.form1.r30_tip1.options[4] = new Option('05 - 20 dias ferias + 10 dias abono', '05');
    document.form1.r30_tip1.options[5] = new Option('06 - 15 dias ferias + 15 dias abono', '06');
    document.form1.r30_tip1.options[6] = new Option('07 - 10 dias ferias + 20 dias abono', '07');
    document.form1.r30_tip1.options[7] = new Option('08 - 30 dias abono',                  '08');
    document.form1.r30_tip1.options[8] = new Option('12 - dias livre',                     '12');

    document.form1.r30_tip1.options[0].setAttribute('data-ferias', '30');
    document.form1.r30_tip1.options[1].setAttribute('data-ferias', '20');
    document.form1.r30_tip1.options[2].setAttribute('data-ferias', '15');
    document.form1.r30_tip1.options[3].setAttribute('data-ferias', '10');
    document.form1.r30_tip1.options[4].setAttribute('data-ferias', '20');
    document.form1.r30_tip1.options[5].setAttribute('data-ferias', '15');
    document.form1.r30_tip1.options[6].setAttribute('data-ferias', '10');
  } else {

    document.form1.r30_tip1.options[0] = new Option('01 - '+ndias+' dias férias', '01');
    document.form1.r30_tip1.options[1] = new Option('02 - '+ndias+' dias abono', '02');
  }

  <?
  if(isset($r30_tip1) && trim($r30_tip1) != ""){
    echo "
          for(var i=0; i<document.form1.r30_tip1.length; i++){
            if(document.form1.r30_tip1.options[i].value == '".$r30_tip1."'){
              document.form1.r30_tip1.options[i].selected = true;
              break;
            }
          }
         ";
  }
  ?>

  js_validamtipo();
}

$("r30_per1f").observe("change", function(){
  js_setDiasGozados();
});

$("r30_per2i").observe("change", function(){
  js_validaDataInicialSegundoPeriodo();
});

$("r30_per2f").observe("change", function(){
  js_validaTotalDias();
});



/*
 * Esta função realiza um calculo nos valores das datas selecionadas.
 * Se o total de dias for menor do que o intervalo das datas retorna erro
 * Se for menor Preenche os dias gozados do primeiro periodo com os dias entre as datas selecionadas e 
 * se houver, preenche os Diaz gozados do segundo período com os dias restantes.
 */
function js_setDiasGozados(){
  var iTipo        = $("r30_tip1").value;
  var oDataInicial = $("r30_per1i").value;
  var oDataFinal   = $("r30_per1f").value;
  var iTotalDias   = new Number($("r30_ndias").value);
  var sMsg         = "";
  var lErro        = false;
  var dInicial     = implode("-", array_reverse(explode("/", $("r30_per1i").value)));
  var dFinal       = implode("-", array_reverse(explode("/", $("r30_per1f").value)));

  

  if (iTipo.valueOf() == 12){
    
    if (js_diferenca_datas(dInicial, dFinal, 3) || js_diferenca_datas(dInicial, dFinal, 4) == 'i' ){
      sMsg  = "Data Inicial não pode ser menor ou igual a data final.";
      lErro = true;
    } else {
    
      var iDias = js_diferenca_datas(dInicial, dFinal, 'd');
  
      if (iDias > iTotalDias.valueOf()) {
        sMsg  = "Dias Gozados não pode ser maior do que o Total de Dias a Gozar";
        lErro = true;     
      } else if (iDias == iTotalDias.valueOf()){
        $("r30_diasgozados1").value = iDias;
        $("r30_diasgozados2").value = 0;
        //$("r30_per2i").up(1).setAttribute("disabled", "disabled");
      } else if (iDias < iTotalDias.valueOf()){
        $("r30_diasgozados1").value = iDias;
        $("r30_diasgozados2").value = (iTotalDias.valueOf() - iDias);
      }
    }
    if (lErro) {
      alert (sMsg);
      $("r30_diasgozados1").clear();
      $("r30_diasgozados2").clear();
      $("r30_per1i").clear();
      $("r30_per1f").clear();
      $("r30_per1i").focus();
    }
  }
}


function js_validaTotalDias() {
    
  var iTipo        = $("r30_tip1").value;
  var oDataInicial = $("r30_per2i").value;
  var oDataFinal   = $("r30_per2f").value;
  var iTotalDias   = new Number($("r30_ndias").value);
  var sMsg         = "";
  var lErro        = false;
  var dInicial     = implode("-", array_reverse(explode("/", $("r30_per2i").value)));
  var dFinal       = implode("-", array_reverse(explode("/", $("r30_per2f").value)));

  var iTotalDiasPrimeiroPeriodo = $("r30_diasgozados1").value;
  
  if (iTipo.valueOf() == 12) {
    
    if (js_diferenca_datas(dInicial, dFinal, 3) || js_diferenca_datas(dInicial, dFinal, 4) == 'i') {
     
      sMsg  = "Data Inicial não pode ser menor ou igual a data final.";
      lErro = true;
    } else {
    
      var iDias = js_diferenca_datas(dInicial, dFinal, 'd');

      if (iDias > (iTotalDias.valueOf() - iTotalDiasPrimeiroPeriodo)) {
        sMsg  = "O intervalo das datas não podem utrapassar o total de dias gozados para o segundo período.";
        lErro = true;     
      } 
    }
    if (lErro) {
      alert (sMsg);
      $("r30_per2f").clear();
      $("r30_per2i").focus()
      $("r30_per2i").clear();
    }
  }
}


function js_validaDataInicialSegundoPeriodo() {

  var iTipo        = $("r30_tip1").value;
  var sMsg         = "";
  var lErro        = false;
  
  var dInicial                  = implode("-", array_reverse(explode("/", $("r30_per2i").value)));
  var dFinalPrimeiroPeriodo     = implode("-", array_reverse(explode("/", $("r30_per1f").value)));
  
  if (iTipo.valueOf() == 12){
    if (js_diferenca_datas(dFinalPrimeiroPeriodo, dInicial, 3)) {
    
      sMsg  = "Data Inicial não pode ser menor ou igual do que a data final do primeiro período.";
      lErro = true;
    } else if (js_diferenca_datas(dFinalPrimeiroPeriodo, dInicial, 4) == 'i') {
      sMsg  = "Data Inicial não pode igual a data final do primeiro período.";
      lErro = true;
    }
    
    if (lErro) {
      alert (sMsg);
      $("r30_per2f").clear();
      $("r30_per2i").focus()
      $("r30_per2i").clear();
    }
  }
}



function js_validamtipo(){
  valmtipo = document.form1.r30_tip1.options[document.form1.r30_tip1.selectedIndex].value;
  valntipo = new Number(document.form1.r30_tip1.options[document.form1.r30_tip1.selectedIndex].value);
  valorndt = new Number(document.form1.r30_ndias.value);
  if(document.form1.r30_tip1.length > 2){
    if(valntipo == 1 || valntipo == 2 || valntipo == 3 || valntipo == 4){
      document.form1.r30_abono.value = 0;
    }else if(valntipo == 5){
      document.form1.r30_abono.value = 10;
    }else if(valntipo == 6){
      document.form1.r30_abono.value = 15;
    }else if(valntipo == 7){
      document.form1.r30_abono.value = 20;
    }else if(valntipo == 8){
      document.form1.r30_abono.value = 30;
    }
  }else{
    if(valntipo == 1){
      document.form1.r30_abono.value = 0;
    }else{
      document.form1.r30_abono.value = valorndt;
    }
  }
  /*
   * Caso seja selecionada a opção 12- dias livres 
   */
  if (valmtipo == 12 ){
    $("dias-gozados1").style.display = "";
    $("dias-gozados2").style.display = "";
    
    if (($("r30_per1i").value != "" ) && ($("r30_per1f").value != "")){
      js_setDiasGozados();
    }
    
  } else {
    $("dias-gozados1").style.display = "none";
    $("dias-gozados2").style.display = "none";
  }
  
  
}


/*
 * Function Explode do phpjs.org
 */
function explode (delimiter, string, limit) {
    // Splits a string on string separator and return array of components. If limit is positive only limit number of components is returned. If limit is negative all components except the last abs(limit) are returned.  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/explode    // +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: kenneth
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: d3x
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
    var emptyArray = {        0: ''
    };
 
    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {        return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;    }
 
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    } 
    if (delimiter === true) {
        delimiter = '1';
    }
     if (!limit) {
        return string.toString().split(delimiter.toString());
    } else {
        // support for limit argument
        var splitted = string.toString().split(delimiter.toString());        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;
    }
}

/*
 * Function Implode do phpjs.org
 */
function implode (glue, pieces) {
    // Joins array elements placing glue string between items and return one string  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/implode    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Waldo Malqui Silva
    // +   improved by: Itsacon (http://www.itsacon.net/)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: implode(' ', ['Kevin', 'van', 'Zonneveld']);    // *     returns 1: 'Kevin van Zonneveld'
    // *     example 2: implode(' ', {first:'Kevin', last: 'van Zonneveld'});
    // *     returns 2: 'Kevin van Zonneveld'
    var i = '',
        retVal = '',        tGlue = '';
    if (arguments.length === 1) {
        pieces = glue;
        glue = '';
    }    if (typeof(pieces) === 'object') {
        if (pieces instanceof Array) {
            return pieces.join(glue);
        } else {
            for (i in pieces) {                retVal += tGlue + pieces[i];
                tGlue = glue;
            }
            return retVal;
        }    } else {
        return pieces;
    }
}

/*
 * Function array_reverse do phpjs.org
 */
function array_reverse (array) {
    
    var aAux     = new Array();
    var itamanho = array.length;
    var iInicio  = 0;
    
    for(var i = (itamanho-1); i >= 0; i--) {
          
      aAux[iInicio] = array[i];
      iInicio +=1;    
    }
       
    return aAux;
}

<?php if (isset($dbopcao) && $dbopcao == true) { ?>
<?php } elseif (isset($dbopcao) && $dbopcao == false) { ?>
  js_verificaaquiini(2, null);
  js_montaselect(document.form1.r30_ndias.value);
<?php } ?>
</script>
