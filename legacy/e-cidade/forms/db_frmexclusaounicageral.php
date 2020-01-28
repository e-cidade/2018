<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

$oDaoReciboUnicaGeracao = new cl_recibounicageracao;
$oDaoReciboUnicaGeracao->rotulo->label();

$oDaoReciboUnica        = new cl_recibounica;
$oDaoReciboUnica->rotulo->label();
?>
 <form action="" method="post" name="formulario" id="formulario">

   <fieldset>
     <legend>Exclusão de Única Geral</legend>

       <table class="form-container">
         <tr>
           <td><a href="" id="labelReciboGeracaoUnica"><?php echo $Lar40_sequencial; ?></a></td>
           <td>
             <?php

               db_input('ar40_sequencial', 4,  1, true, 'text', 1);
               db_input('ar40_observacao', 40, 1, true, 'text', 3);
             ?>
           </td>
         </tr>
       </table>

   </fieldset>

   <fieldset id="dadosGeracao">
     <legend>Dados da Geração</legend>

     <table class="form-container">
       <tr>
         <td style="width: 205px"><?php echo $Lar40_db_usuarios?> <span id="ar40_db_usuarios"></span></td>
       </tr>

       <tr>
         <td><?php echo $Lar40_dtoperacao; ?> <span id="ar40_dtoperacao"></span></td>
       </tr>

       <tr>
         <td><?php echo $Lar40_dtvencimento; ?> <span id="ar40_dtvencimento"></span></td>
       </tr>

       <tr>
         <td><?php echo $Lar40_percentualdesconto; ?> <span id="ar40_percentualdesconto"></span></td>
       </tr>

       <tr>
         <td><strong>Quantidade de Registros:</strong> <span id="quantidade_recibos" class="atencao"></span></td>
       </tr>

       <tr>
         <td><strong>Quantidade de registros que <i>possuem pagamento</i>:</strong> <span id="quantidade_recibos_pagos" class="atencao"></span></td>
       </tr>
     </table>

   </fieldset>

   <input type="button" value="Excluir" name="excluir" id="excluir" onclick="return js_excluir()" disabled="disabled" />
   <input type="reset"  value="Limpar"  name="limpar"  id="limpar" onclick="return js_limpar()" />

</form>

<style type="text/css">
  fieldset { min-width: 540px; }
  .form-container span { text-align: left; font-weight: normal !important; }
  .atencao { color: #FF0000; font-weight: bold; }
</style>

<script type="text/javascript">

  const MENSAGEM = "tributario.arrecadacao.db_frmexclusaounicageral.";
  const RPC      = "arr4_exclusaounicageral.RPC.php";

  /**
   * Ancora para Recibo Unica Geração
   * @type  {DBLookUp}
   */
  var oReciboUnicaGeracao = new DBLookUp($('labelReciboGeracaoUnica'), $('ar40_sequencial'), $('ar40_observacao'), {
    'sArquivo'              : 'func_recibounicageracao.php',
    'sObjetoLookUp'         : 'db_iframe_recibounicageracao',
    'sLabel'                : 'Pesquisar Geração de Única',
    'fCallBack'            :  js_retornoReciboUnicaGeracao,
    'oBotaoParaDesabilitar' : $('excluir'),
    'aParametrosAdicionais' : ['sTipoGeracao=G&lValidaVencimento=true&lValidaUsuario=true']
  });

  function js_retornoReciboUnicaGeracao(){

    if( js_empty($F(ar40_sequencial)) ) {

      js_limpar();
      return false;
    }

    /**
     * Buscamos os dados da Geração via RPC
     */
    var oParametros = {
        sExecucao      : "getCotaUnicaGeral",
        iCodigoGeracao : $F('ar40_sequencial')
    }

    new AjaxRequest(RPC, oParametros, function(oRetorno, erro) {

      if (erro) {

        alert(oRetorno.sMessage.urlDecode());
        return false;
      }

      var sNomeLogin = oRetorno.aDadosUnica[0].nome.urlDecode() + ' (' + oRetorno.aDadosUnica[0].login.urlDecode() + ')';
      $('ar40_db_usuarios').innerHTML         = sNomeLogin;
      $('ar40_dtoperacao').innerHTML          = oRetorno.aDadosUnica[0].ar40_dtoperacao;
      $('ar40_dtvencimento').innerHTML        = oRetorno.aDadosUnica[0].ar40_dtvencimento;
      $('ar40_percentualdesconto').innerHTML  = oRetorno.aDadosUnica[0].ar40_percentualdesconto + "%";
      $('quantidade_recibos').innerHTML       = oRetorno.aDadosUnica[0].quantidade_recibos;
      $('quantidade_recibos_pagos').innerHTML = oRetorno.aDadosUnica[0].quantidade_recibos_pagos;

      var lHabilitaBotao    = false;
      if(oRetorno.aDadosUnica[0].quantidade_recibos == oRetorno.aDadosUnica[0].quantidade_recibos_pagos){
        lHabilitaBotao      = true;
      }
      $('excluir').disabled = lHabilitaBotao;

      $('dadosGeracao').show();

    }).setMessage( _M( MENSAGEM + 'carregando_dados_geracao' ) ).execute();
  }

  function js_excluir(){

    if( js_empty($F(ar40_sequencial)) ) {

      alert( _M( MENSAGEM + 'codigo_geracao_obrigatorio' ) );
      return false;
    }

    if ( !confirm( _M( MENSAGEM + 'confirma_exclusao' ) ) ) {
      return false;
    }

    /**
     * Buscamos os dados da Geração via RPC
     */
    var oParametros = {
        sExecucao      : "excluirUnicaGeral",
        iCodigoGeracao : $F('ar40_sequencial')
    }

    new AjaxRequest(RPC, oParametros, function(oRetorno, erro) {

      alert(oRetorno.sMessage.urlDecode());
      if (erro) {
        return false;
      }
      $('limpar').click();
    }).setMessage( _M( MENSAGEM + 'excluindo_geracao' ) ).execute();

  }

  function js_limpar(){

    $('dadosGeracao').hide();
    $('ar40_db_usuarios').innerHTML         = null;
    $('ar40_dtoperacao').innerHTML          = null;
    $('ar40_dtvencimento').innerHTML        = null;
    $('ar40_percentualdesconto').innerHTML  = null;
    $('quantidade_recibos').innerHTML       = null;
    $('quantidade_recibos_pagos').innerHTML = null;
    $('excluir').disabled                   = true;
  }

  js_limpar();
</script>