<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_bancohoras_classe.php"));

$clBancoHoras = new cl_bancohoras();
$clBancoHoras->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("z01_nome");
$clrotulo->label("rh126_regist");
$clrotulo->label("rh126_soma");
$clrotulo->label("rh126_data");
$clrotulo->label("rh126_horas");
$clrotulo->label("rh126_observacao");

$db_opcao = 1;
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, numbers.js, prototype.js, estilos.css, grid.style.css");
      db_app::load("widgets/dbtextFieldData.widget.js, datagrid.widget.js");
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">

  <form class="container" action="" method="post" name="form">

    <fieldset style="width: 570px">
      <legend>Banco de Horas</legend>
      <table class="form-container">

        <input type="hidden" id="rh126_sequencial" name="rh126_sequencial" />

        <tr>
          <td nowrap title="<?=@$Trh126_regist?>">
            <?php db_ancora(@$Lrh126_regist,"js_pesquisaServidor(true);",$db_opcao); ?>
          </td>
          <td colspan="3">
            <?php
              db_input('rh126_regist',6,$Irh126_regist,true,'text',$db_opcao," onchange='js_pesquisaServidor(false);'");
              db_input('z01_nome',44,$Iz01_nome,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh126_soma; ?>">
            <label id="lbl_rh126_soma" for="rh126_soma"><?php echo $Lrh126_soma; ?></label>
          </td>
          <td>
            <?php
              $aOpcoes = array("1" => "Soma", "0" => "Diminui");
              db_select('rh126_soma', $aOpcoes,true, $db_opcao);
            ?>
          </td>
        </tr>

        <tr>
          <td>
            <table>
              <tr>
                <td nowrap title="<?php echo $Trh126_data; ?>">
                  <label id="lbl_rh126_data" for="rh126_data"><?php echo $Lrh126_data; ?></label>
                </td>
                <td><?php db_inputdata("rh126_data", "","", "", true, 'text', $db_opcao); ?></td>
              </tr>
            </table>
          </td>

          <td>
            <table>
              <tr>
                <td nowrap title="<?php echo $Trh126_horas; ?>">
                  <label id="lbl_rh126_horas" for="rh126_horas"><?php echo $Lrh126_horas; ?></label>
                  <input type="hidden" id="rh126_horas" name="rh126_horas" />
                </td>
                <td>
                  <input type="text" id="horas" name="horas" size="5" value="00" onchange="js_formataHora(this)" onkeyUp="js_formataHora(this)" onblur="js_validaHorario(this);" maxlength="5"/>
                </td>
                <td>:</td>
                <td>
                  <input type="text" id="minutos" name="minutos" size="5" value="00" onchange="js_formataHora(this)" onkeyUp="js_formataHora(this)" onblur="js_validaHorario(this);" maxlength="5"/>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td colspan="2">
          <fieldset>
            <legend>Observação</legend>
            <textarea id="rh126_observacao" name="rh126_observacao"></textarea>
          </fieldset>
          </td>
        </tr>
      </table>

      <fieldset>
        <legend>Histórico</legend>
        <div id="gridHistorico"></div>
      </fieldset>

    </fieldset>
    <input type="button" value="Salvar" onclick="js_processar();" />
    <input type="reset" value="Limpar" onclick="js_limpar();"/>
  </form>


  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>

  <script type="text/javascript">

    var mensagem = "recursoshumanos.rh.rec4_bancohoras.";
    var sUrl     = "rec4_bancohoras.RPC.php";

    (function(){

      oGridHistorico              = new DBGrid('GridHistorico');
      oGridHistorico.nameInstance = 'oGridHistorico';
      oGridHistorico.setCellWidth( new Array( '15%',
                                              '15%',
                                              '20%',
                                              '30%',
                                              '20%'
                                            ) );

      oGridHistorico.setCellAlign( new Array( 'center',
                                              'center',
                                              'center',
                                              'left',
                                              'center'
                                            ) );

      oGridHistorico.setHeader( new Array( 'Data',
                                           'Horas',
                                           'Tipo',
                                           'Observação',
                                           'Operação'
                                         ) );

      oGridHistorico.show( $('gridHistorico') );
      oGridHistorico.clearAll( true );

      String.prototype.lpad = function(padString, length, lRemoveSinal) {

          lRemoveSinal   = false || lRemoveSinal;
          lAdicionaSinal = false;

          var str = this;

          if( str > 9 ){
            return str;
          }

          if( str < 0 && lRemoveSinal ){

            str = str.substr(1);
          }

          if(str.length == 2 && str < 0){

           str = str.substr(1);
           lAdicionaSinal = true;
          }

          while (str.length < length){
            str = padString + str;
          }

          if(lAdicionaSinal){
            str = '-' + str;
          }

          return str;
      }

      $('rh126_soma').value = 1;
    })();

    function js_formataHora(campo){

      var oHoras   = $(campo);
      var iHoras   = oHoras.value;
      oHoras.value = iHoras;
      iHoras       = iHoras.replace(/\D/g,"");

      oHoras.value = iHoras;
    }

    function js_validaHorario(campo){

      if( $(campo).name == 'minutos' ){

        var oMinutos   = $('minutos');
        if( $F('minutos') > 59 ){
          alert(_M(mensagem+'minutos_invalidos'));
          oMinutos.value = '';
        }
      }

      $(campo).value = $F(campo).lpad(0,2);
    }

    /**
     * Valida consistência dos dados,
     * e se esta tudo corredo envia os dados por RPC.
     * @return {boolean} true = valido, false = inválido.
     */
    function js_processar(){

      if(empty($F('rh126_regist'))){

        alert(_M(mensagem+'informe_servidor'));
        return false;
      }

      if(empty($F('rh126_data'))){

        alert(_M(mensagem+'informe_data'));
        return false;
      }

      if(empty($F('horas')) || empty($F('minutos'))){

        alert(_M(mensagem+'informe_hora'));
        return false;
      }

      if( $F('horas') == '00' && $F('minutos') == '00' ){

        alert(_M(mensagem+'informe_hora'));
        return false;
      }

      var oParametros               = {};
      oParametros.sExecucao         = 'setBancoHoras';
      oParametros.iServidor         = $F('rh126_regist');
      oParametros.iSequencial       = $F('rh126_sequencial');
      oParametros.iTipo             = $F('rh126_soma');
      oParametros.sData             = $F('rh126_data');
      oParametros.iHoras            = $F('horas');
      oParametros.iMinutos          = $F('minutos');
      oParametros.sObservacao       = encodeURIComponent( $F('rh126_observacao') );

      var oDadosRequisicao          = {}
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function(oAjax) {

        var oRetorno = JSON.parse(oAjax.responseText);

        alert(oRetorno.sMensagem.urlDecode());

        if (oRetorno.iStatus == 2) {
          return false;
        }

        /**
         * Inclusao efetuada
         */
        $('rh126_sequencial').value = '';
        $('rh126_data').value       = '';
        $('horas').value            = '00';
        $('minutos').value          = '00';
        $('rh126_soma').value       = 0;
        $('rh126_observacao').value = '';
        js_getHistorico();
        return;
      }

      new Ajax.Request(sUrl, oDadosRequisicao);
    }

    /**
     * Get registro de historico do servidor
     */
    function js_getHistorico (){

      if( $F('rh126_regist') == '' ){
        return;
      }

      $('rh126_sequencial').value   = '';

      js_divCarregando( _M( mensagem + 'buscando_registro_historico' ), 'msgbox');
      oGridHistorico.setStatus('');
      oGridHistorico.clearAll(true);

      var oParametros               = new Object();
      oParametros.sExecucao         = 'getHistorico';
      oParametros.iServidor         = $('rh126_regist').value;

      var oDadosRequisicao          = new Object();
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function(oAjax){

        js_removeObj('msgbox');

        var oRetorno = eval("("+oAjax.responseText+")");
        if (oRetorno.iStatus == "2") {

          alert(oRetorno.sMensagem.urlDecode());
          return;
        }

        oRetorno.oHistorico.each(

           function (oDado, iInd) {
             var aRow            = new Array();
                 aRow[0]         = oDado.rh126_data;
             var aRowHoras       = oDado.rh126_horas.urlDecode().split(':');
                 aRow[1]         = aRowHoras[0].lpad(0, 2) + ':' + aRowHoras[1].lpad(0, 2);
                 aRow[2]         = oDado.rh126_soma;
                 aRow[3]         = oDado.rh126_observacao.urlDecode();

             var sAlteraRegistro = '<a href="#" onclick="js_alterarRegistroBanco(' + oDado.rh126_sequencial +');">A</a>';
             var sExcluiRegistro = '<a href="#" onclick="js_excluirRegistroBanco(' + oDado.rh126_sequencial +');">E</a>';
                 aRow[4] =  sAlteraRegistro + '&nbsp;&nbsp;' + sExcluiRegistro;
                 oGridHistorico.addRow(aRow);
           }
        );

        oGridHistorico.renderRows();

        /**
         * Get Saldo
         */
         var oParametros                  = new Object();
         oParametros.sExecucao            = 'getSaldoHoras';
         oParametros.iServidor            = $('rh126_regist').value;

         var oDadosRequisicaoSaldo              = new Object();
             oDadosRequisicaoSaldo.method       = 'POST';
             oDadosRequisicaoSaldo.asynchronous = false;
             oDadosRequisicaoSaldo.parameters   = 'json='+Object.toJSON(oParametros);
             oDadosRequisicaoSaldo.onComplete   = function(oAjax){

               var oRetorno = eval("("+oAjax.responseText+")");
               if (oRetorno.status == "2") {

                  alert(oRetorno.message.urlDecode());
                  return;
               }

               oGridHistorico.setStatus( 'Saldo: ' + oRetorno.saldoHoras );
             }

         var oAjax2  = new Ajax.Request( sUrl, oDadosRequisicaoSaldo );

    }

    var oAjax  = new Ajax.Request( sUrl, oDadosRequisicao );
  }

  function js_alterarRegistroBanco( iSequencial ){

      var oParametros               = new Object();
      oParametros.sExecucao         = 'getBancoHoras';
      oParametros.iSequencial       = iSequencial;

      var oDadosRequisicao          = new Object();
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function(oAjax){

        var oRetorno = eval("("+oAjax.responseText+")");
        if (oRetorno.iStatus == "2") {

          alert(oRetorno.sMensagem.urlDecode());
          return;
        }

        oRetorno.oBancoHoras.each(

           function (oDado, iInd) {

              $('rh126_sequencial').value = oDado.rh126_sequencial;
              $('rh126_data').value       = oDado.rh126_data;

              var aSaldo         = oDado.rh126_horas.urlDecode().split(':');
              $('horas').value   = aSaldo[0].lpad(0,2);
              $('minutos').value = aSaldo[1].lpad(0,2);

              $('rh126_soma').value       = 0;
              if ( oDado.rh126_soma == 't'){
                $('rh126_soma').value     = 1;
              }

              $('rh126_observacao').value = oDado.rh126_observacao.urlDecode();
           }
        );
    }

    var oAjax  = new Ajax.Request( sUrl, oDadosRequisicao );
  }

  function js_excluirRegistroBanco( iSequencial ){

      if( !confirm( _M ( mensagem + 'confirma_exclusao' ) ) ) {
          return false;
      }

      var oParametros               = new Object();
      oParametros.sExecucao         = 'deleteBancoHoras';
      oParametros.iSequencial       = iSequencial;

      var oDadosRequisicao          = new Object();
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function(oAjax){

        var oRetorno = eval("("+oAjax.responseText+")");
        if (oRetorno.iStatus == "2") {

          alert(oRetorno.sMensagem.urlDecode());
          return;
        }

        js_getHistorico();
      }

    var oAjax  = new Ajax.Request( sUrl, oDadosRequisicao );
  }

  function js_pesquisaServidor(mostra){

    $('rh126_regist').onkeyup = new Event(Event.CHANGE);

    if( mostra == true ){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_mostraServidor1|rh01_regist|z01_nome&sAtivos=A','Pesquisa',true);
    }else{

      if($F('rh126_regist') != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?filtro_lotacao=true&pesquisa_chave='+$F('rh126_regist')+'&funcao_js=parent.js_mostraServidor&sAtivos=A','Pesquisa',false);
      }else{
        $('z01_nome').value = '';
      }
    }
  }
  function js_mostraServidor(sNome,erro){

    document.form.z01_nome.value = sNome;
    if( erro == true ){
      $('rh126_regist').focus();
      $('rh126_regist').value = '';
    }
    js_getHistorico();
  }

  function js_mostraServidor1(sRegist,sNome){

    $('rh126_regist').value = sRegist;
    $('z01_nome').value     = sNome;
    db_iframe_rhpessoal.hide();

    js_getHistorico();
  }

  function js_limpar(){

    $('rh126_sequencial').value = '';
    $('horas').value            = '00';
    $('minutos').value          = '00';
    oGridHistorico.setStatus('');
    oGridHistorico.clearAll( true );
  }
  </script>

  </body>
</html>