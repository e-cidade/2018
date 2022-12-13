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
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body bgcolor="#cccccc">
    <div class="container">
      <form method="post" name="form1">

        <fieldset >
          <legend><?php echo ($db_opcao != 3 ? 'Inclusão' : 'Exclusão') ; ?> de Escala de Férias</legend>
          
          <table>
            <tr>
              <td>
                <?php echo $Lrh109_regist; ?>
              </td>
              <td>
                <?php db_input('rh109_regist', 10, 0, true, 'text', 3); ?>
              </td>
              <td>
                <?php db_input('z01_nome', 40, 0, true, 'text', 3); ?>
              </td>
            </tr>                
          </table>
          
          <fieldset class="separator">
          <legend><strong>Período Aquisitivo</strong></legend>
          
            <?php db_input('rh110_rhferias', 10,0,true,'hidden', 3); ?>
            <?php db_input('rh110_sequencial', 10,0,true,'hidden', 3); ?>

            <?php db_input('ano_folha', 10,0,true,'hidden', 3); ?>
            <?php db_input('mes_folha', 10,0,true,'hidden', 3); ?>

            <table style="border:0;">
              <?php
              if ($db_opcao != 3) {


                ?>
                <tr>
                  <td>
                    <b>Escolha o Período:</b>
                  </td>
                  <td>

                    <?php
                    db_select('periodoaquisitivo', $aPeriodosAquisitivos, true, 1, 'onchange="setarDadosPeriodo()"');
                    ?>
                  </td>
                </tr>
                <?php
              }
              ?>
              <tr>
                <td>
                   <?php echo $Lrh109_periodoaquisitivoinicial; ?>
                </td>
                <td>
                  <?php
                  db_inputdata('rh109_periodoaquisitivoinicial',
                                $rh109_periodoaquisitivoinicial_dia,
                                $rh109_periodoaquisitivoinicial_mes,
                                $rh109_periodoaquisitivoinicial_ano,
                                true, 'text', 3, "");
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                   <?php echo $Lrh109_periodoaquisitivofinal; ?>
                </td>
                <td>
                  <?php
                  db_inputdata('rh109_periodoaquisitivofinal',
                                $rh109_periodoaquisitivofinal_dia,
                                $rh109_periodoaquisitivofinal_mes,
                                $rh109_periodoaquisitivofinal_ano,
                                true, 'text', 3, "");
                  ?>
                </td>
              </tr>
              <?php if ($db_opcao != 3) { ?>
                <tr>
                  <td>
                    <?php echo $Lrh109_diasdireito; ?>
                  </td>
                  <td>
                    <?php db_input('rh109_diasdireito', 10, 0, true, 'text', 3); ?>
                  </td>
                </tr>
              <?php } ?>
            </table>
          </fieldset>     

          <fieldset class="separator">
            <legend>Período de Gozo</legend>

            <table class="form-container">

              <tr>
                <td width="145">
                  <?php  echo $Lrh110_dias; ?>
                </td>
                <td>
                  <?php db_input('rh110_dias', 10, $Irh110_dias, true, 'text', $db_opcao); ?>
                </td>
              </tr>
              
              <tr>
                <td>
                  <?php  echo $Lrh110_diasabono; ?>
                </td>
                <td>
                  <?php db_input('rh110_diasabono', 10, $Irh110_diasabono, true, 'text', $db_opcao); ?>
                </td>
              </tr>

              <tr>
                <td>
                  <?php echo $Lrh110_datainicial; ?>
                </td>
                <td>
                  <?php db_inputdata('rh110_datainicial',
                                     $rh110_datainicial_dia,
                                     $rh110_datainicial_mes,
                                     $rh110_datainicial_ano,
                                     true, 'text', $db_opcao, "onchange='validaDataInicial()'", "", "", "parent.validaDataInicial()");
                  ?>
                </td>
              </tr>

              <tr id="linhaDataFinal">
                <td>
                  <?php echo $Lrh110_datafinal; ?>
                </td>
                <td>
                  <?php db_inputdata('rh110_datafinal',
                                     $rh110_datafinal_dia,
                                     $rh110_datafinal_mes,
                                     $rh110_datafinal_ano,
                                     true, 'text', 3);                          
                  ?>
                </td>
              </tr>   
            </table>
          </fieldset>
          
          <fieldset  class="separator">
            <legend>Observações</legend>
             <?php 
               $rh110_observacoes = utf8_decode($rh110_observacoes);
             ?>

             <?php db_textarea("rh110_observacoes", 5, 60,  "", true, null, $db_opcao);?>
          </fieldset>
        </fieldset>

      
        <?php 
          if ($db_opcao == 3) {
          ?>
          <input name="excluir" type="button" id="db_opcao" value="Excluir" onclick="return js_excluir()" />
          <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="return pesquisaPeriodosquisitivo()" />
          <?php 
          } else {
        ?>
          <input name="processar" type="button" id="db_opcao" value="Processar" onclick="return js_processar(arguments[0])" <?php echo ($db_opcao == 3 ? 'disabled' : '');?> />
        <?php } ?>
        <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_voltar()" />
      
      </form>
    </div>
    <?php 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
                     
  <script>
    var oUrl                  = js_urlToObject();
    var lPermiteSelecaoFerias = <?=($db_opcao == 3 && $lPermiteEscolhaPeriodo) ? 'true' : 'false';?>;
    (function(){

      if ($F('rh110_dias') == 0 && $F('rh110_diasabono') > 0) {
        $('linhaDataFinal').hide();
      }


    })();
 
    var MENSAGEM_SISTEMA  = "recursoshumanos.pessoal.db_frmescalaferias.";

      var oInputDiasGozar             = $('rh110_dias');
      var oInputDiasAbono             = $('rh110_diasabono');
      var oInputDiasDireito           = $('rh109_diasdireito');
      var oInputDataInicial           = $('rh110_datainicial');
      var oInputDataFinal             = $('rh110_datafinal');

      oInputDiasAbono.onchange = function() {

        validaDataInicial();

        if (isNaN(+this.value)) {
          return false;
        }
        return validarDiasAGozar();
      };

      oInputDiasGozar.onchange = function(evt) {

        if ( this.value == 0) {
          $('linhaDataFinal').hide();
        } else { 
          $('linhaDataFinal').show();
        }


        if (evt && oInputDataInicial.value) {
          oInputDataFinal.value = "";
        }

        if ((oInputDataInicial.value && !oInputDataFinal.value) || (!oInputDataInicial.value && oInputDataFinal.value) && oInputDiasGozar.value) {
          validarPeriodoGozo();
        }

      };

      function validaDataInicial() {
        
        var diff = retornaDiferencaEntreDatas(oInputDataInicial.value, $F('rh109_periodoaquisitivoinicial'))
        if (diff > 0) {
          alert(_M( MENSAGEM_SISTEMA + 'data_inicial_menor_que_periodo_aquisitivo' ))
          oInputDataInicial.value = "";
          return;
        }

        validarPeriodoGozo();
      }

      function validarDiasAGozar() {

                

        if ( +oInputDiasGozar.value == 0 && +oInputDiasAbono.value === 0 ) {

          alert(_M( MENSAGEM_SISTEMA + 'periodo_de_gozo_zerado' ));
        //  oInputDiasGozar.value = +$F('rh109_diasdireito') - +$F('rh110_diasabono');
          return false;
        } 
        

        if ( +oInputDiasGozar.value > +oInputDiasDireito.value || +oInputDiasAbono.value > +oInputDiasDireito.value ) {

          alert(_M( MENSAGEM_SISTEMA + 'dias_direito_menor_abono_dias_a_gozar'));
          oInputDiasGozar.value = +oInputDiasDireito.value;
          oInputDiasAbono.value = +oInputDiasDireito.value - oInputDiasGozar.value;
          return false;
        }

        var iTotal = +oInputDiasGozar.value + +oInputDiasAbono.value;

        if (iTotal > +oInputDiasDireito.value) {
        
          alert( _M( MENSAGEM_SISTEMA + 'dias_direito_menor_abono_dias_a_gozar') );
          return false;
        }
        return true;
      }

      /**
       * Valida os Dias de Periodo de Gozo
       */
      function validarPeriodoGozo() {
        
        require_once("scripts/strings.js");
        
        var oDataInicial   = inputDateToObject(oInputDataInicial.value);
        var oDataFinal     = inputDateToObject(oInputDataFinal.value);

        /**
         * Verifica se tem dias a gozar preenchido e a data inicial.
         * A partir disto preenche a data final.
         */
        if ( oInputDataInicial.value != '' && oInputDiasGozar.value != '' ) {

          oNewDateFinal       = new Date();
          var iDiasAdicionais = ( oDataInicial.day + new Number(oInputDiasGozar.value) ) - 1;// Diminuido 1 dia pois o tida de inicio conta como dia gozado
          
          oNewDateFinal.setYear( oDataInicial.year );
          oNewDateFinal.setMonth( oDataInicial.month - 1);
          oNewDateFinal.setDate( iDiasAdicionais );

          oInputDataFinal.value = oNewDateFinal.getFormatedDate(DATA_PTBR);
        }
      }

    /**
    * Retorna em dias a dirença entre duas das data
    * Aceita como parametro um objeto do tipo retornado pela função inputDateToObject
    * Ou uma string de data no formato brasileiro
    */
    function retornaDiferencaEntreDatas(obj1, obj2) {

      var oData1 = (typeof obj1 == "string") ? inputDateToObject(obj1) : obj1,
          oData2 = (typeof obj2 == "string") ? inputDateToObject(obj2) : obj2;

      var oData1 = new Date(oData1.year, oData1.month-1, oData1.day),
          oData2 = new Date(oData2.year, oData2.month-1, oData2.day);

      var iTimestampDiff =  oData2.getTime() - oData1.getTime();
      return (isNaN(iTimestampDiff) ? 0 : Math.round(iTimestampDiff / 86400000));
    }

    /**
    * Tranforma uma string de data no formato brasileiro
    * em um objeto com os atributos day, month e year
    */
    function inputDateToObject(date) {
      var aDate = date.split('/');
                      
      if ( aDate.length == 3 ) {
        return {day   : +aDate[0],
                month : +aDate[1],
                year  : +aDate[2]};
      }
      return {};
    }   
   

    function js_voltar() {
      location.href = 'pes1_escalaferias001.php?db_opcao=<?php echo $db_opcao;?>';
    }

    function js_processar(clickEvent) {

      if ( !validarDiasAGozar() ) {
        return false;
      }

      if (!$F('rh110_dias')) {
    	  alert( _M(MENSAGEM_SISTEMA + 'dias_gozar_nao_informado') );  
        return false;
      }
      
      if (!$F('rh110_diasabono')) {
        
        $('rh110_diasabono').value = '0';
        alert( _M(MENSAGEM_SISTEMA + 'dias_gozar_nao_informado') );  
        return false;
      }

      if (!$F('rh110_datainicial')) {
        alert( _M(MENSAGEM_SISTEMA + 'data_inicial_nao_informada'));
        return false;
      }

      var sUrlRPC = 'pes4_ferias.RPC.php';

      var msgDiv                            = "Processando Dados. \n Aguarde ...",
          oParam                            = {
            sExecucao                 : "cadastrarPeriodoGozo",
            iCodigoFerias             : $F('rh110_rhferias'),
            iMatricula                : $F('rh109_regist'), 
            iDiasGozo                 : $F('rh110_dias'),
            iDiasAbono                : $F('rh110_diasabono'),
            sDataPeriodoInicial       : $F('rh110_datainicial'),
            sDataPeriodoFinal         : $F('rh110_datafinal'),
            sObservacao               : $F('rh110_observacoes').urlEncode(),
          };

      js_divCarregando(msgDiv,'msgBox');

      var oAjax  = new Ajax.Request(sUrlRPC, { method: "post",
                                              parameters:'json='+Object.toJSON(oParam),
                                              onComplete: js_retornoProcessar
      });  

    }

    function js_retornoProcessar(oAjax) {
      js_removeObj('msgBox');
      var oRetorno = eval("("+oAjax.responseText+")");

      alert(oRetorno.sMensagem.urlDecode());

      if (oRetorno.iStatus == "1") {
        js_voltar();
      }
    }

    function js_excluir() {
      var sUrlRPC = 'pes4_ferias.RPC.php';

      var msgDiv                            = "Processando Dados. \n Aguarde ...",
          oParam                            = {
            sExecucao                 : "excluirPeriodoGozo",
            iCodigo                   : $F('rh110_sequencial')
          };

      js_divCarregando(msgDiv,'msgBox');

      var oAjax  = new Ajax.Request(sUrlRPC,{method: "post",
        parameters:'json='+Object.toJSON(oParam),
        onComplete: js_retornoProcessar
      });  
    }

    function js_validaAnoPagamento(obj, event) {
      obj.onkeyup(event);
      if (!obj.value) {
    	  alert(_M(MENSAGEM_SISTEMA + 'ano_pagamento_nao_informado'));
        return false;
      }

      if (+obj.value < +$F('ano_folha')) {
        alert(_M(MENSAGEM_SISTEMA + 'ano_pagamento_menor_folha'))
        return false;
      }

      if (+obj.value > +( inputDateToObject($('rh110_datainicial').value).year ))  {
        alert( _M( MENSAGEM_SISTEMA + 'ano_pagamento_maior_data_incial') );
        return false;
      }

      return true;
    }

    function js_validaMesPagamento(obj, event) {
      obj.onkeyup(event);
      if (!obj.value) {
    	  alert(_M(MENSAGEM_SISTEMA + 'mes_pagamento_nao_informado'));
        return false;
      }

      if (+obj.value > 12) {
        alert( _M(MENSAGEM_SISTEMA + 'mes_invalido') )
        return false;
      }        

      if (+obj.value < +$F('mes_folha')) {
        alert(_M(MENSAGEM_SISTEMA + 'mes_pagamento_menor_folha'))
        return false;
      }

      if (+obj.value > +( inputDateToObject($('rh110_datainicial').value).month ))  {
        alert( _M( MENSAGEM_SISTEMA + 'mes_pagamento_maior_data_incial') );
        return false;
      }

      return true;
    }

    (function(){
      var request = {
        exec      :'getPeriodoEmAbertoDoServidor',
        matricula : $F('rh109_regist'),
      }
      if ($('periodoaquisitivo')) {

        var oAjaxRequest = new AjaxRequest('rh4_periodoaquisitivo.RPC.php', request,
          function (oRetorno, erro) {

            var oCombo = $('periodoaquisitivo')
            oCombo.options.length = 0;
            oRetorno.periodos.each(function (periodo, iSeq) {

              var option = new Option(periodo.inicio + " - " + periodo.fim + " Dias: " + periodo.saldo, periodo.codigo);
              if (iSeq == 0) {
                option.selected = true;
              }
              option.data_periodo = periodo;
              oCombo.add(option);
            });
          }).setMessage('Buscando Periodos Aquisitivos').execute();
      }
      })();


    /**
     * Define os dados pdo peroi
     */
    function setarDadosPeriodo() {

      var oCombo = $('periodoaquisitivo');
      oOption    = oCombo.options[oCombo.selectedIndex];
      if (oOption.data_periodo != '') {

        var oDadosPeriodo = oOption.data_periodo;

        $('rh109_periodoaquisitivoinicial').value = oDadosPeriodo.inicio;
        $('rh109_periodoaquisitivofinal').value   = oDadosPeriodo.fim;
        $('rh109_diasdireito').value              = oDadosPeriodo.saldo;
        $('rh110_dias').value                     = oDadosPeriodo.saldo;
        $('rh110_rhferias').value                 = oDadosPeriodo.codigo;
      }
    }

    function pesquisaPeriodosquisitivo() {

        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_periodo',
                            'func_rhferiasperiodomanutencao.php?funcao_js=parent.carregaperiodo|rh110_sequencial|rh109_sequencial&matricula='+$F('rh109_regist')+"&situacao=0",
                             'Pesquisa',true);
   }
   function carregaperiodo(codigo_periodo, codigo_ferias) {

     db_iframe_periodo.hide();
     var nome_arquivo = '<?=array_shift(explode('?', basename($_SERVER["PHP_SELF"])));?>';
     location.href = nome_arquivo+'?codigo_periodo='+codigo_periodo+'&rh109_regist='+$F('rh109_regist')+'&codigo_ferias='+codigo_ferias;
   }

   if (oUrl.codigo_periodo == null && lPermiteSelecaoFerias) {
     pesquisaPeriodosquisitivo();
   }
  </script>

</html>
