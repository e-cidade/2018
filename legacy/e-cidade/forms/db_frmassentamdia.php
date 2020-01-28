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

//MODULO: rh
$classenta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("h12_codigo");
$clrotulo->label("h12_assent");
$clrotulo->label("rh131_rhferias");

$meiodia = true;
if(!isset($opcao_dtterm)){
  $meiodia = false;
  $opcao_dtterm = $db_opcao;
}

?>

<style type="text/css">
  #h16_dtconc, #h16_dtterm{
    width: 80px;
  }

  #h12_codigodescr, #z01_nome{
    width: 285px;
  }

</style>
<form name="form1" method="post" action="">
<center>

  <fieldset style="width:570px;margin:10px auto;">

    <legend><strong>Assentamentos:</strong></legend>

    <table border="0">

      <?php db_input('h16_codigo',6,$Ih16_codigo,true,'hidden',3,""); ?>
      <?php db_input('h12_vinculaperiodoaquisitivo', 6, '', true, 'hidden', 3); ?>
      <?php db_input('lBloqueiaPeriodoAquisitivo', 6, '', true, 'hidden', 3); ?>

      <tr>
        <td nowrap title="<?=@$Th16_regist?>">
          <?php db_ancora(@$Lh16_regist,"js_pesquisah16_regist(true);",$db_opcao); ?>
        </td>
        <td colspan="3"> 
          <?php
            db_input('h16_regist',8,$Ih16_regist,true,'text',$db_opcao," onchange='js_pesquisah16_regist(false);'");
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Th16_assent?>">
          <?php  db_ancora(@$Lh16_assent,"js_pesquisah16_assent(true);",$db_opcao); ?>
        </td>
        <td colspan="3"> 
          <?php
            db_input('h16_assent',6,$Ih16_assent,true,'hidden',3,"");
            //db_input('h12_assent',6,$Ih12_assent,true,'text',$db_opcao,"onchange='js_pesquisah16_assent(false);'");
            //db_input('h12_codigo',6,"",true,'text',$db_opcao," onchange='js_pesquisah16_assent(false);'");

            $dbwhere = "";
            if ($db_opcao != 1){

              if ( !empty($h16_assent) ) {

                $dbwhere = "h12_codigo = ".@$h16_assent;
                $result  = $cltipoasse->sql_record($cltipoasse->sql_query_file(null,"h12_assent,h12_descr",null,$dbwhere));
                if ($cltipoasse->numrows > 0){

                  db_fieldsmemory($result,0);
                  $h12_codigo = $h12_assent;
                }
              }
            }

            $result_tipoasse = $cltipoasse->sql_record($cltipoasse->sql_query_file(null,"trim(h12_assent),h12_descr"));

            db_selectrecord("h12_codigo",$result_tipoasse,true,$db_opcao,"","",""," -(Selecione)","js_pesquisah16_assent(false);"); 
            db_input('h12_assent',6,"",true,'hidden',3,"");

            if ($db_opcao == 1 || $db_opcao == 11){
              unset($h12_descr);
            }
          ?>
        </td>
      </tr>

      <tr class="vinculoperiodoaquisitivo">
        <td nowrap title="<?php echo $Trh131_rhferias; ?>">
          <?php db_ancora($Lrh131_rhferias, "js_pesquisaPeriodoAquisitivo(true);", $db_opcao); ?>
        </td>
        <td colspan="3">
          <?php db_input('iPeriodoAquisitivo', 6, '', true, 'text', 3, " onchange=js_pesquisaPeriodoAquisitivo(false);"); ?>
          <?php db_input('dtPeriodoAquisitivoInicio', 8, '', true, 'text', 3); ?>
          &nbsp;
          a
          &nbsp;
          <?php db_input('dtPeriodoAquisitivoFinal', 8, '', true, 'text', 3); ?>
        </td>
      </tr>

      <tr class="vinculoperiodoaquisitivo">
        <td>
          <strong>Saldo de Dias:</strong>
        </td>
        <td>
          <?php db_input('iSaldoDias', 6, '', true, 'text', 3); ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Th16_dtconc?>">
          <?=@$Lh16_dtconc?>
        </td>
        <td> 
          <?php db_inputdata('h16_dtconc',@$h16_dtconc_dia,@$h16_dtconc_mes,@$h16_dtconc_ano,true,'text',$db_opcao,"onchange='js_somar_dias(document.form1.h16_quant.value, 0)'","","","parent.js_somar_dias(parent.document.form1.h16_quant.value, 0)") ?>
        </td>
        <td nowrap title="Somar dias" width="70px">
          <b>Quantidade:</b>
        </td>
        <td> 
          <?php db_input('h16_quant',12,$Ih16_quant,true,'text',$opcao_dtterm,"onchange='js_somar_dias(this.value, 1);'","quantidade") ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Th16_dtterm?>">
          <?=@$Lh16_dtterm?>
        </td>
        <td width="120px"> 
          <?php db_inputdata('h16_dtterm',@$h16_dtterm_dia,@$h16_dtterm_mes,@$h16_dtterm_ano,true,'text',$opcao_dtterm,"onchange='js_somar_dias(0, 3)'","","","parent.js_somar_dias(0, 3)") ?>
        </td>
      </tr>

      <?php
      $opcao_quant = 3;

      if(isset($h12_tipo) && isset($h12_tipefe) && trim($h12_tipo) == "S" && trim($h12_tipefe) == "C") :
        $opcao_quant = 1;
      ?>
        <tr>
          <td nowrap title="Ano">
            <b>Anos:</b>
          </td>
          <td colspan="3">
            <table width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td> 
                  <?
                  db_input('valor_ano',4,1,true,'text',1,"");
                  ?>
                </td>
                <td nowrap title="Mês">
                  <b>Meses:</b>
                </td>
                <td> 
                  <?
                  db_input('valor_mes',4,1,true,'text',1,"");
                  ?>
                </td>
                <td nowrap title="Dia">
                  <b>Dias:</b>
                </td>
                <td> 
                  <?
                  db_input('valor_dia',4,1,true,'text',1,"");
                  ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      <?php endif; ?>

      <tr>
        <td nowrap title="<?=@$Th16_quant?>">
          <?=@$Lh16_quant?>
        </td>
        <td> 
          <?php db_input('h16_quant',8,$Ih16_quant,true,'text',($opcao_dtterm == 3 ? 3 : $opcao_quant),"") ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Th16_nrport?>">
          <?=@$Lh16_nrport?>
        </td>
        <td> 
          <?php db_input('h16_nrport',8,$Ih16_nrport,true,'text',$db_opcao,"") ?>
        </td>
        <td nowrap title="<?=@$Th16_atofic?>">
          <?=@$Lh16_atofic?>
        </td>
        <td> 
          <?php db_input('h16_atofic',12,$Ih16_atofic,true,'text',$db_opcao,"") ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Th16_anoato?>">
          <?=@$Lh16_anoato?>
        </td>
        <td colspan='3'> 
          <?php db_input('h16_anoato',8,$Ih16_anoato,true,'text',$db_opcao,"") ?>
        </td>
      </tr>  

      <tr>
        <td nowrap title="<?=@$Th16_histor?>">
          <?=@$Lh16_histor?>
        </td>
        <td colspan="3"> 
          <?php db_textarea('h16_histor',5,47,$Ih16_histor,true,'text',$db_opcao,"") ?>
        </td>
      </tr>

      <!--
      <tr>
        <td nowrap title="<?=@$Th16_login?>">
          <?=@$Lh16_login?>
        </td>
        <td> 
          <?
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Th16_conver?>">
          <?=@$Lh16_conver?>
        </td>
        <td> 
          <?
          $x = array("f"=>"NAO","t"=>"SIM");
          db_select('h16_conver',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      -->
    </table>

  </fieldset>

</center>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verificacampos();" />
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>

  var sUrlRPC   = 'rec1_assenta.RPC.php';
  var MENSAGENS = 'recursoshumanos.rh.rec1_assenta.';

  js_verificaVinculoPeriodoAquisitivo(false);

  function js_verificacampos(){

    if(document.form1.h16_regist.value == ""){

      alert("Informe a matrícula.");
      document.form1.h16_regist.focus()
      return false;

    } else if(document.form1.h16_dtconc_dia.value == "" || document.form1.h16_dtconc_mes.value == "" || document.form1.h16_dtconc_ano.value == ""){

      alert("Informe a data inicial.");
      document.form1.h16_dtconc.focus();
      document.form1.h16_dtconc.select();
      return false;
    }

    if ( $('h12_vinculaperiodoaquisitivo').value == 't' ) {

      if ($F('h16_dtterm') == ''){

        alert(_M(MENSAGENS + 'data_final_obrigatorio'));
        return false;
      }

      oParametros = {}
      oParametros.sExec                    = 'validaSaldoDiasDireito';
      oParametros.iCodigoPeriodoAquisitivo = $F('iPeriodoAquisitivo');
      oParametros.iTipoAssentamento        = $F('h16_assent');
      oParametros.iDias                    = $F('quantidade');
      oParametros.iSequencialAssentamento  = $F('h16_codigo');

      var oRetorno = {};

      oDadosRequisicao = {}
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function( oAjax ) {

        js_removeObj('oCarregando');

        oRetorno = JSON.parse( oAjax.responseText );

        if (oRetorno.iStatus == "2") {
          alert( oRetorno.sMensagem.urlDecode() );        
          return false;
        }


        /**
         * Valida se não foi adicionado mesmo periodo de gozo no mesmo periodo.
         */
        oParametros.sExec                   = 'validaPeriodoDiasDireito';
        oParametros.iTipoAssentamento       = $F('h16_assent');
        oParametros.sDataInicial            = $F('h16_dtconc');
        oParametros.sDataFinal              = $F('h16_dtterm');
        oParametros.iServidor               = $F('h16_regist');
        oParametros.iSequencialAssentamento = $F('h16_codigo');
        oDadosRequisicao.parameters         = 'json=' + Object.toJSON(oParametros);
        oDadosRequisicao.onComplete         = function( oAjax ) {

          oRetorno = JSON.parse( oAjax.responseText );
          if (oRetorno.iStatus == "2") {
            alert( _M( oRetorno.sMensagem.urlDecode() ) );
            return false;
          }

          return true;
        }

        var oAjax                     = new Ajax.Request( sUrlRPC, oDadosRequisicao );
      }

      js_divCarregando('Aguarde, Carregando Dias de Direito ...', 'oCarregando');

      var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );


      return oRetorno.iStatus == "1";
    }

    return true;
  }

  function js_somar_dias(valor, opcao){
    
    diai = new Number(document.form1.h16_dtconc_dia.value);
    mesi = new Number(document.form1.h16_dtconc_mes.value);
    anoi = new Number(document.form1.h16_dtconc_ano.value);

    diaf = new Number(document.form1.h16_dtterm_dia.value);
    diaf++; 
    mesf = new Number(document.form1.h16_dtterm_mes.value);
    anof = new Number(document.form1.h16_dtterm_ano.value);

    if(diai != 0 && mesi != 0 && anoi != 0 && valor != "" && opcao != 3){
      valor = new Number(valor);
      data  = new Date(anoi , (mesi - 1), (diai + valor - 1));

      dia = data.getDate();
      mes = data.getMonth() + 1;
      ano = data.getFullYear();

      document.form1.h16_quant.value = valor;
      document.form1.h16_dtterm_dia.value = dia < 10 ? "0" + dia : dia;
      document.form1.h16_dtterm_mes.value = mes < 10 ? "0" + mes : mes;
      document.form1.h16_dtterm_ano.value = ano;
      document.form1.h16_dtterm.value = document.form1.h16_dtterm_dia.value+'/'+document.form1.h16_dtterm_mes.value+'/'+document.form1.h16_dtterm_ano.value;

      document.form1.h16_dtterm.value = (dia < 10 ? "0" + dia : dia)+'/'+(mes < 10 ? "0" + mes : mes)+'/'+ano;
    }else if(diai != 0 && mesi != 0 && anoi != 0 && diaf != 0 && mesf != 0 && anof != 0 && opcao == 3){
      datai  = new Date(anoi , (mesi - 1), diai);
      dataf  = new Date(anof , (mesf - 1), diaf);

      datad = (dataf - datai) / 86400000;
      document.form1.h16_quant.value = datad.toFixed();
      document.form1.quantidade.value = datad.toFixed();

      if (datad.toFixed() <= 0){
        alert('A data final nao pode ser menor que a data inicial');      
        document.form1.h16_dtterm_dia.value = '';
        document.form1.h16_dtterm_mes.value = '';
        document.form1.h16_dtterm_ano.value = '';
        document.form1.h16_dtterm.value     = '';
        document.form1.h16_dtterm.focus();
        document.form1.h16_quant.value      = '';
        document.form1.quantidade.value     = '';
        return false;
      }

      ano = datad / 365;
      ano = ano.toFixed();
      mes = (datad - (ano * 365)) / 30;
      mes = mes.toFixed();
      dia = datad - (ano * 365) - (mes * 30);
      dia = dia.toFixed();

      if(document.form1.valor_dia){
        document.form1.valor_dia.value = dia;
        document.form1.valor_mes.value = mes;
        document.form1.valor_ano.value = ano;
        document.form1.valor.value = dia+'/'+mes+'/'+ano;
      }
    }else if(opcao == 2){
      alert("Informe a data inicial!");
      document.form1.h16_dtconc.focus();
      document.form1.h16_dtconc.select();
      document.form1.quantidade.value = "";
    }

    if (document.form1.h16_dtterm.value == '') {
      document.form1.quantidade.value = "0";
      document.form1.h16_quant.value = "0";
          
    }
    
    quant_dias = new Number(document.form1.quantidade.value);
    if(quant_dias == 0){
      document.form1.h16_dtterm_dia.value = '';
      document.form1.h16_dtterm_mes.value = '';
      document.form1.h16_dtterm_ano.value = '';
      document.form1.h16_dtterm.value = '';
    }
  }

  function js_pesquisah16_regist(mostra){
    
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome','Pesquisa',true);
    }else{
      if(document.form1.h16_regist.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.h16_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
      }else{
        document.form1.z01_nome.value = ''; 
      }
    }
  }

  function js_mostrarhpessoal(chave,erro){
    document.form1.z01_nome.value = chave; 
    if(erro==true){ 
      document.form1.h16_regist.focus(); 
      document.form1.h16_regist.value = ''; 
    }

    js_limpaPeriodoAquisitivo();
  }

  function js_mostrarhpessoal1(chave1,chave2){
    document.form1.h16_regist.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_rhpessoal.hide();

    js_limpaPeriodoAquisitivo();
  }

  function js_pesquisah16_assent(lMostra){

    if (lMostra == true) {
      js_OpenJanelaIframe( 'CurrentWindow.corpo',
                           'db_iframe_tipoasse',
                           'func_tipoasse.php?funcao_js=parent.js_mostratipoasse1|h12_codigo|h12_assent|h12_descr|h12_vinculaperiodoaquisitivo',
                           'Pesquisa', true );
    } else {

      if (document.form1.h12_codigo.value != '') { 
         js_OpenJanelaIframe( 'CurrentWindow.corpo',
                              'db_iframe_tipoasse',
                              'func_tipoasse.php?chave_assent='+document.form1.h12_codigo.value+'&funcao_js=parent.js_mostratipoasse', 
                              'Pesquisa', false );
      } else {
        document.form1.h12_assent.value = '';
        document.form1.h16_assent.value = '';
        document.form1.submit();
      }
    }
  }

  function js_mostratipoasse(chave, chave2, erro, chave3, lVinculaPeriodoAquisitivo) {
    var lExclusao = document.form1.h12_codigo.readOnly;

    document.form1.h12_codigo.value = lExclusao ? chave : chave3;
    document.form1.h12_assent.value = chave;

    if (erro == true) { 
      document.form1.h12_codigo.focus(); 
      document.form1.h12_assent.value = ''; 
      document.form1.h16_assent.value = '';
    } else {
      document.form1.h16_assent.value = chave3;
    }

    js_verificaVinculoPeriodoAquisitivo((lVinculaPeriodoAquisitivo == 't'));
  }

  function js_mostratipoasse1(chave1, chave2, chave3, lVinculaPeriodoAquisitivo) {

    document.form1.h16_assent.value = chave1;
    document.form1.h12_assent.value = chave2;
    document.form1.h12_codigo.value = chave1;

    var sel1 = document.form1.elements["h12_codigo"];
    var sel2 = document.form1.elements["h12_codigodescr"];     

    for(var i = 0;i < sel1.options.length;i++) {

      if (sel1.options[i].value == chave2) {

        sel1.options[i].selected = true;
        sel2.options[i].selected = true;
        break;
      }
    }

    db_iframe_tipoasse.hide();
    js_verificaVinculoPeriodoAquisitivo((lVinculaPeriodoAquisitivo == 't'));
  }

  function js_pesquisa(){
    <?php if ($meiodia == true) : ?>
      js_OpenJanelaIframe( 'CurrentWindow.corpo',
                           'db_iframe_assmeio',
                           'func_assmeio.php?funcao_js=parent.js_preenchepesquisa'
                           +'|h22_codigo<?php echo ($db_opcao == 33 || $db_opcao == 3? "" : "&bloqueia_assenta=true"); ?>',
                           'Pesquisa', true);
    <?php else: ?>
      js_OpenJanelaIframe( 'CurrentWindow.corpo',
                           'db_iframe_assenta',
                           'func_assenta.php?funcao_js=parent.js_preenchepesquisa|h16_codigo'
                           + '<?php echo ($db_opcao == 2 || $db_opcao == 22 ? "&bloqueia_reajuste=true" : ""); ?>',
                           'Pesquisa', true);
    <?php endif; ?>
  }

  function js_preenchepesquisa(chave){
    <?if($meiodia == true){?>
    db_iframe_assmeio.hide();
    <?}else{?>
    db_iframe_assenta.hide();
    <?}?>
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
  
  /**
   * Abre a func de pesquisa e busca o período aquisitivo
   *
   * @param Boolean lMostra -- Se deve ou não exibir a janela de pesquisa
   */
  function js_pesquisaPeriodoAquisitivo(lMostra) {
    var iRegist = $('h16_regist').value

    if (iRegist == '') {
      alert( _M( MENSAGENS + "servidor_nao_informado") );
      return;
    }

    if (lMostra) {
      js_OpenJanelaIframe( 'CurrentWindow.corpo',
                           'db_iframe_rhferias',
                           'func_rhferias.php?rh109_regist=' + iRegist
                           + '&funcao_js=parent.js_mostraPeriodoAquisitivo'
                           + '|rh109_sequencial|rh109_periodoaquisitivoinicial|rh109_periodoaquisitivofinal|',
                           'Pesquisa Período Aquisitivo', true );
    } else {

      if ($F('iPeriodoAquisitivo') != '') {
        js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                             'db_iframe_rhferias', 
                             'func_rhferias.php?pesquisa_chave=' + $F('iPeriodoAquisitivo')
                             + '&funcao_js=parent.js_mostraPeriodoAquisitivo1', 
                             'Pesquisa Período Aquisitivo', false );
      } else {
        document.form1.z01_nome.value = ''; 
      }
    }
  }

  /**
   * Exibe os campos do período aquisitivo
   *
   * @param Integer iSequencial
   * @param String dtInicio
   * @param String dtFim
   */
  function js_mostraPeriodoAquisitivo(iSequencial, dtInicio, dtFim) {

    js_limpaPeriodoAquisitivo();
    $('iPeriodoAquisitivo').value        = iSequencial;
    $('dtPeriodoAquisitivoInicio').value = dtInicio.replace(/(\d{4})-(\d{2})-(\d{2})/, '$3/$2/$1');
    $('dtPeriodoAquisitivoFinal').value  = dtFim.replace(/(\d{4})-(\d{2})-(\d{2})/, '$3/$2/$1');

    if (db_iframe_rhferias) {
      db_iframe_rhferias.hide();
    }

    var oParametros      = new Object(),
        oDadosRequisicao = new Object();

    oParametros.sExec                    = 'getSaldoDiasDireito';
    oParametros.iCodigoPeriodoAquisitivo = $F('iPeriodoAquisitivo');
    oParametros.iCodigoAssentamento      = $F('h16_codigo');

    oDadosRequisicao.method       = 'POST';
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);
    oDadosRequisicao.onComplete   = function( oAjax ) {

      js_removeObj('oCarregando');

      var oRetorno = JSON.parse( oAjax.responseText );

      if (oRetorno.iStatus == "2") {
        js_limpaPeriodoAquisitivo();
        alert( oRetorno.sMensagem.urlDecode());

        return;
      }

      $('iSaldoDias').value = oRetorno.iDiasDireito || '0';
    }

    js_divCarregando('Aguarde, Carregando Dias de Direito ...', 'oCarregando');

    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
  }

  /**
   * Função chamada na alteração ou exclusão
   *
   * @param Integer iSequencial
   * @param Boolean lErro
   * @param String dtInicio
   * @param String dtFim
   */
  function js_mostraPeriodoAquisitivo1(iSequencial, lErro, dtInicio, dtFim) {
    if (lErro) {
      js_limpaPeriodoAquisitivo();
      return false;
    }

    js_mostraPeriodoAquisitivo(iSequencial, dtInicio, dtFim);
  }
  
  /**
   * Verifica se deve exibir os campos do periodo aquisitivo e carregar os dados do mesmo
   *
   * @param Boolean lExibe
   */
  function js_verificaVinculoPeriodoAquisitivo(lExibe) {

    $('h12_vinculaperiodoaquisitivo').value = 'f';
    js_limpaPeriodoAquisitivo();
    
    $$('.vinculoperiodoaquisitivo').each(function(oElemento) {
      oElemento.hide();
    });

    if (lExibe && !$F('lBloqueiaPeriodoAquisitivo')) {

      $('h12_vinculaperiodoaquisitivo').value = 't';
      $$('.vinculoperiodoaquisitivo').each(function(oElemento) {
        oElemento.show();
      });

      if ($F('h16_codigo') != '') {
        var oParametros      = new Object(),
            oDadosRequisicao = new Object();

        oParametros.sExec              = 'getPeriodoAquisitivo';
        oParametros.iCodigoAssenta     = $F('h16_codigo');
        oParametros.iMatriculaServidor = $F('h16_regist');

        oDadosRequisicao.method       = 'POST';
        oDadosRequisicao.asynchronous = false;
        oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);
        oDadosRequisicao.onComplete   = function( oAjax ) {

          js_removeObj('oCarregando');

          var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

          if (oRetorno.iStatus == "2") {
            js_limpaPeriodoAquisitivo();
            alert( oRetorno.sMensagem );
            return;
          }

          $('iPeriodoAquisitivo').value = oRetorno.iCodigoPeriodoAquisitivo;
          $('iPeriodoAquisitivo').onchange();
        }

        js_divCarregando('Aguarde, Carregando Período Aquisitivo ...', 'oCarregando');

        var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
      }
    }
  }

  /**
   * Limpa os campos do período aquisitivo
   */
  function js_limpaPeriodoAquisitivo() {

    $('iPeriodoAquisitivo').value        = '';
    $('dtPeriodoAquisitivoInicio').value = '';
    $('dtPeriodoAquisitivoFinal').value  = '';
    $('iSaldoDias').value                = '';
  }

  /**
   * Chamada da função que atualiza o tipoasse setando se vincula o periodo aquisitivo
   */
  if ($F('h12_codigo') != '') {
    js_pesquisah16_assent(false);
  }

</script>
