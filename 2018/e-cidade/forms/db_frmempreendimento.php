<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

/**
 * Rotulos dos dados do empreendedor
 */
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_nomefanta");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_cep");
$clrotulo->label("z01_munic");

/**
 * Rotulos dos dados do empreendedor
 */
$clrotulo->label("am05_sequencial");
$clrotulo->label("am05_nome");
$clrotulo->label("am05_nomefanta");
$clrotulo->label("am05_numero");
$clrotulo->label("am05_complemento");
$clrotulo->label("am05_cep");
$clrotulo->label("am05_bairro");
$clrotulo->label("am05_ruas");
$clrotulo->label("am05_cnpj");
$clrotulo->label("am05_areatotal");
$clrotulo->label("am05_protprocesso");
$clrotulo->label("j14_nome");
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_descr");
$clrotulo->label("j13_codi");

$clrotulo->label("am03_sequencial");
$clrotulo->label("am03_descricao");
$clrotulo->label("am03_ramo");
$clrotulo->label("am03_potencialpoluidor");
$clrotulo->label("am03_criterioatividadeimpacto");

$clrotulo->label("am04_sequencial");
$clrotulo->label("am06_sequencial");
$clrotulo->label("am06_principal");

?>
<style type="text/css">

  .desabilitado{ background-color:#DEB887 !important; }
  .habilitado { background-color:#FFFFFF !important; }
  .hidden { display:none !important; }

  #aba_empreendedor, #aba_empreendimento,
  #aba_atividade, #aba_responsaveltecnico { width: 700px; text-align: center; }
</style>

<div id="conteudo_abas" >

  <div id="aba_empreendedor">

    <form name="formEmpreendedor" id="formEmpreendedor" method="post" action="">

      <fieldset style="width: 680px;">
          <legend>Empreendedor</legend>

            <table>
              <tr>
                <td>
                 <?php
                  db_ancora($Lz01_nome,' js_cgmEmpreendedor(true); ',1);
                 ?>
                </td>
                <td>
                 <?php
                  db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',$db_opcao,"onchange='js_cgmEmpreendedor(false)'");
                  db_input('z01_nome',65,0,true,'text',3,"",null);
                 ?>
                </td>
              </tr>
            </table>

        </fieldset>

      <input name="prosseguir" type="button" id="prosseguir" value="Prosseguir" onclick="return js_validaEmpreendedor();"/>
    </form>

  </div>

  <div id="aba_empreendimento">

    <form name="formEmpreendimento" method="post">

      <input type="hidden" name="iCodigoEmpreendimento" id="iCodigoEmpreendimento" value="" />

          <fieldset>
            <legend>Empreendedor</legend>

            <table>
              <tr>
                <td><strong>CGM:</strong></td>
                <td colspan="2">
                  <?php
                    db_input('iCgmEmpreendedor',10,$Iz01_numcgm,true,'text',3);
                    db_input('sNomeEmpreendedor',53,$Iz01_nome,true,'text',3,'',null,null,"padding-left:2px;");
                  ?>
                  </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tz01_nomefanta; ?>" >
                  <?php echo $Lz01_nomefanta; ?>
                </td>
                <td colspan="2">
                  <?php
                    db_input('sNomeFantasiaEmpreendedor', 67, $Iz01_nomefanta, true, 'text', 3, '');
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tz01_ender; ?>">
                  <?php echo $Lz01_ender; ?>
                </td>
                <td>
                  <?php
                    db_input('sEnderecoEmpreendedor',30,$Iz01_ender,true,'text',3);
                  ?>
                </td>
                <td align="right">
                  <?php
                    echo $Lz01_cep;
                    db_input('iCepEmpreendedor', 8,$Iz01_cep,true,'text',3, 'onchange="validaApenasNumeros(\'z01_cep\');"', null, null, null, 8);
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tz01_munic; ?>">
                  <?php echo $Lz01_munic; ?>
                </td>
                <td >
                  <?php
                    db_input('sMunicipioEmpreendedor',30,$Iz01_munic,true,'text',3);
                  ?>
                </td>
                <td align="right">
                  <?php
                    echo "<strong>CPF/CNPJ:</strong>";
                    db_input('iCgccpfEmpreendedor',18,$Iz01_cgccpf,true,'text',3);
                  ?>
                </td>
              </tr>

            </table>
          </fieldset>

          <fieldset>
            <legend>Empreendimento</legend>

            <table>
              <tr>
                <td nowrap title="<?php echo $Tam05_cnpj; ?>">
                  <?php echo $Lam05_cnpj; ?>
                </td>
                <td colspan="3">
                  <?php
                    db_input('am05_cnpj',18,1,true,'text', $db_opcao);
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tam05_nome; ?>">
                  <?php echo $Lam05_nome; ?>
                </td>
                <td colspan="3">
                  <?php
                    db_input('am05_nome',65,3,true,'text', $db_opcao);
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tam05_nomefanta; ?>">
                  <?php echo $Lam05_nomefanta; ?>
                </td>
                <td colspan="3">
                  <?php
                    db_input('am05_nomefanta',65,3,true,'text',$db_opcao);
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tj14_codigo; ?>">
                  <?php
                    db_ancora($Lj14_codigo,'js_pesquisaj14_codigo(true); ',$db_opcao);
                  ?>
                </td>
                <td nowrap colspan="3">
                  <?php
                    db_input('j14_codigo',9,$Ij14_codigo,true,'text',$db_opcao," onchange='js_pesquisaj14_codigo(false);'");
                    db_input('j14_nome',52,$Ij14_nome,true,'text',3, '', null, null, "padding-left:2px;");
                  ?>
               </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tam05_numero?>">
                  <?php echo $Lam05_numero; ?>
                </td>

                <td nowrap>
                  <?php
                    db_input('am05_numero',9,$Iam05_numero,true,'text',$db_opcao);
                  ?>
                </td>

                <td nowrap title="<?php echo $Tam05_complemento?>" style="text-align:right" colspan="2">
                  <?php
                    echo $Lam05_complemento;
                    db_input('am05_complemento',18, 3,true,'text',$db_opcao, null, null, null, null,100);
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tj13_codi; ?>">
                  <?php
                    db_ancora($Lj13_codi,'js_bairro(true);',$db_opcao);
                  ?>
                </td>
                <td nowrap colspan="3">
                  <?php
                    db_input('j13_codi',9,$Ij13_codi,true,'text',$db_opcao,"onchange='js_bairro(false);'",null,"#FFFFFF");
                    db_input('j13_descr',52,$Ij13_descr,true,'text',3, '', null, null, "padding-left:2px;");
                  ?>
               </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tam05_cep; ?>">
                  <?php echo $Lam05_cep; ?>
                </td>
                <td colspan="3">
                  <?php
                    db_input('am05_cep',9,1,true,'text',$db_opcao);
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?php echo $Tam05_areatotal; ?>">
                  <?php echo $Lam05_areatotal; ?>
                </td>
                <td colspan="3">
                  <?php
                    db_input('am05_areatotal',9,$Iam05_areatotal,true,'text',$db_opcao);
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?php echo $Tam05_protprocesso; ?>">
                 <?php
                  db_ancora('Código do Processo:',' js_pesquisaProcesso(true); ',1);
                 ?>
                </td>
                <td colspan="3">
                 <?php
                  db_input('am05_protprocesso',9,1,true,'text',$db_opcao,"onchange='js_pesquisaProcesso(false);'",null,"#FFFFFF");
                  db_input('p51_descr',52,0,true,'text',3,"",null,null, "padding-left:2px;");
                 ?>
                </td>
              </tr>
            </table>

          </fieldset>

      <input name="incluirEmpreendimento"   type="button" id="incluirEmpreendimento"   value="Incluir"   onclick="return js_validaEmpreendimento();"/>
      <input name="pesquisarEmpreendimento" type="button" id="pesquisarEmpreendimento" value="Pesquisar" onclick="return js_pesquisarEmpreendimento();"/>
    </form>
  </div>

  <div id="aba_atividade">

     <form name="formAtividade" id="formAtividade" method="post">

      <input type="hidden" name="iCodigoEmpreendimentoAtividade" id="iCodigoEmpreendimentoAtividade" value="" />

        <fieldset style="width:700px">
          <legend>Atividades</legend>

          <table>
            <tr>
              <td nowrap title="<?php echo $Tam03_sequencial; ?>">
                <?php
                  db_ancora($Lam03_sequencial,'js_pesquisaAtividadeImpacto(true); ', $db_opcao, null, 'am03_sequencial_ancora');
                ?>
              </td>
              <td nowrap colspan="3">
                <?php
                  db_input('am03_sequencial',9,$Iam03_sequencial,true,'text',$db_opcao," onchange='js_pesquisaAtividadeImpacto(false);'");
                  db_input('am03_descricao',52,$Iam03_descricao,true,'text',3, '', null, null, "padding-left:2px;");
                ?>
             </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tam03_ramo; ?>" >
                <?php echo $Lam03_ramo; ?>
              </td>
              <td colspan="2">
                <?php
                  db_input('am03_ramo', 20, $Iam03_ramo, true, 'text', 3, '');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tam03_potencialpoluidor; ?>" >
                <?php echo $Lam03_potencialpoluidor; ?>
              </td>
              <td colspan="2">
                <?php
                  db_input('am03_potencialpoluidor', 20, $Iam03_potencialpoluidor, true, 'text', 3, '');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tam03_criterioatividadeimpacto; ?>" >
                <?php echo $Lam03_criterioatividadeimpacto; ?>
              </td>
              <td colspan="2">
                <?php
                  db_input('am03_criterioatividadeimpacto', 20, $Iam03_criterioatividadeimpacto, true, 'text', 3, '');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tam04_sequencial; ?>" >
                <?php echo $Lam04_sequencial; ?>
              </td>
              <td colspan="2">
                <?php
                  $aOpcoes = array('' =>'Selecione');
                  db_select('am04_sequencial', $aOpcoes, true, $db_opcao);
                ?>
              </td>
            </tr>

            <tr id="tipoAtividade">
              <td nowrap title="<?php echo $Tam06_principal; ?>" >
                <?php echo $Lam06_principal; ?>
              </td>
              <td colspan="2">
                <?php
                  $aOpcoesAtividadePrincipal = array('1' => 'Sim', '0' => 'Não');
                  db_select('am06_principal', $aOpcoesAtividadePrincipal, true, $db_opcao);
                ?>
              </td>
            </tr>

          </table>
        </fieldset>

        <input name="lancar" type="button" id="lancar" value="Lançar" onclick="return js_validaFormularioAtividade();" />
        <input name="limpar" type="button" id="limpar" value="Limpar" onclick="return js_limparFormularioAtividade();" />

        <fieldset style="margin-top:15px;">
          <legend>Atividades do Empreendimento</legend>

          <div id="gridAtividades"></div>
        </fieldset>

    </form>
  </div>

  <div id="aba_responsaveltecnico">
    <form name="formResponsavelTecnico" id="formResponsavelTecnico" method="post">
      <fieldset style="width: 700px;">
          <legend>Responsável técnico</legend>

            <table>
              <tr>
                <td>
                 <?php
                  db_ancora($Lz01_nome,' js_cgm_responsavel(true); ',1);
                 ?>
                </td>
                <td>
                 <?php
                  db_input('iCgmResponsavel',10,$Iz01_numcgm,true,'text',1,"onchange='js_cgm_responsavel(false)'");
                  db_input('sNomeResponsavel',60,0,true,'text',3,"");
                 ?>
                </td>
              </tr>
            </table>

        </fieldset>
        <input name="lancarResponsavel" type="button" id="lancarResponsavel" value="Lançar" onclick="return js_lancaResponsavel();"/>
        <input name="limparResponsavel" type="button" id="limparResponsavel" value="Limpar" onclick="js_limparFormularioResponsavel();"/>

        <fieldset style="margin-top:15px;">
          <legend>Responsáveis vinculados ao Empreendimento</legend>
          <div id="gridReponsaveis"></div>
        </fieldset>
    </form>
  </div>

</div>

<script type="text/javascript">

  /**
   * Define a Opção de operação da rotina (Inclusão ou Alteração)
   */
  <?php
    echo "var iOperacao = {$db_opcao};\n";
  ?>

  var sCaminhoMensagens  = "tributario.meioambiente.amb1_empreendimentos.";
  var sRpcEmpreendimento = "amb1_empreendimento.RPC.php";

  /**
   * Cria as Abas
   */
  var oDBAbas = new DBAbas($('conteudo_abas')),
      oAbaEmpreendedor       = oDBAbas.adicionarAba('Empreendedor' ,         $('aba_empreendedor')       ),
      oAbaEmpreendimento     = oDBAbas.adicionarAba('Empreendimento',        $('aba_empreendimento')     ),
      oAbaAtividade          = oDBAbas.adicionarAba('Atividades',            $('aba_atividade')          ),
      oAbaResponsavelTecnico = oDBAbas.adicionarAba('Responsáveis Técnicos', $('aba_responsaveltecnico') );

  oAbaEmpreendimento.bloquear();
  oAbaAtividade.bloquear();
  oAbaResponsavelTecnico.bloquear();

  /**
   * Alteração de Empreendimento
   */
  if(iOperacao == 2){

    $('incluirEmpreendimento').value = 'Alterar';
    $('incluirEmpreendimento').disable();
    oAbaEmpreendedor.bloquear();
    oAbaEmpreendimento.desbloquear();
    oDBAbas.mostraFilho( oAbaEmpreendimento );
    js_pesquisarEmpreendimento();
  }

  if (iOperacao == 1) {
    $('pesquisarEmpreendimento').hide();
  }

  /**
   * Func de Consulta do Empreendimento
   */
  function js_pesquisarEmpreendimento(){
    js_OpenJanelaIframe('top.corpo','db_iframe_empreendimento','func_empreendimento.php?funcao_js=parent.js_preencheEmpreendimento|am05_sequencial|am05_cgm','Pesquisa',true);
  }

  function js_preencheEmpreendimento(chave1, chave2){

    db_iframe_empreendimento.hide();

    $('iCodigoEmpreendimento').value = chave1;
    $('z01_numcgm').value            = chave2;

    /**
     * Busca dados do empreendedor
     */
    js_validaEmpreendedor();

    /**
     * Busca dados do empreendimento
     */
    getEmpreendimento();

  }

  /**
   * Função que busca os registros do empreendimento
   */
  function getEmpreendimento(){

    var oParametros = {
        sExecucao             : 'getEmpreendimento',
        iCodigoEmpreendimento : $F('iCodigoEmpreendimento')
    }

    new AjaxRequest(sRpcEmpreendimento, oParametros, function(oRetorno, erro) {

      if (erro) {

        alert(oRetorno.sMensagem.urlDecode());
        return false;
      }

      /**
       * Popula formulario com os dados do empreendimento
       */
       oRetorno.oEmpreendimento.each(

          function (oDado, iInd) {

            $('am05_cnpj').value         = !empty(oDado.am05_cnpj) ? oDado.am05_cnpj : '';
            $('am05_nome').value         = !empty(oDado.am05_nome) ? oDado.am05_nome.urlDecode() : '';
            $('am05_nomefanta').value    = !empty(oDado.am05_nomefanta) ? oDado.am05_nomefanta.urlDecode() : '';
            $('j14_codigo').value        = oDado.j14_codigo;
            $('j14_nome').value          = oDado.j14_nome.urlDecode();
            $('am05_numero').value       = oDado.am05_numero;
            $('am05_complemento').value  = !empty(oDado.am05_complemento) ? oDado.am05_complemento.urlDecode() : '';
            $('j13_codi').value          = oDado.j13_codi;
            $('j13_descr').value         = oDado.j13_descr.urlDecode();
            $('am05_cep').value          = oDado.am05_cep;
            $('am05_areatotal').value    = oDado.am05_areatotal;
            $('am05_protprocesso').value = oDado.am05_protprocesso;
            $('p51_descr').value         = oDado.p51_descr.urlDecode();
          }
       );

      $('incluirEmpreendimento').enable();
      oAbaAtividade.desbloquear();
      oAbaResponsavelTecnico.desbloquear();

    }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_empreendimento' ) ).execute();
  }

  /**
   * Controles do Empreenddor
   */
  function js_validaEmpreendedor(){

    if( empty( $F('z01_numcgm') ) ){

      alert( _M( sCaminhoMensagens + 'cgm_obrigatorio' ) );
      return false;
    }

    /**
     * Libera a aba do Empreendimento
     * Bloqueia empreendedor
     */
     oAbaEmpreendedor.bloquear();
     oAbaEmpreendimento.desbloquear();

     /**
      * Executa Consulta do Cgm para empreendimento
      */
     var oParametros = {
        sExecucao        : "getDadosEmpreendedor",
        iCgmEmpreendedor :  $F('z01_numcgm')
      }

     new AjaxRequest(sRpcEmpreendimento, oParametros, function(oRetorno, erro) {

        if (erro) {
          alert(oRetorno.sMessage.urlDecode())
          return false;
        }

        /**
         * Populamos o formulario com os dados do Empreendedor
         */
        $('iCgmEmpreendedor').value       = oRetorno.oDadosEmpreendedor.z01_numcgm;
        $('sNomeEmpreendedor').value      = oRetorno.oDadosEmpreendedor.z01_nome.urlDecode();
        $('sEnderecoEmpreendedor').value  = oRetorno.oDadosEmpreendedor.z01_ender.urlDecode();
        $('sMunicipioEmpreendedor').value = oRetorno.oDadosEmpreendedor.z01_munic.urlDecode();
        $('iCepEmpreendedor').value       = oRetorno.oDadosEmpreendedor.z01_cep;
        $('iCgccpfEmpreendedor').value    = oRetorno.oDadosEmpreendedor.z01_cgccpf;

        if ( !oRetorno.oDadosEmpreendedor.isFisico ) {
          $('sNomeFantasiaEmpreendedor').value = oRetorno.oDadosEmpreendedor.z01_nomefanta.urlDecode();
        }

     }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_empreendedor' ) ).execute();

     oDBAbas.mostraFilho( oAbaEmpreendimento );
  }

  function js_cgmEmpreendedor(mostra){

    var numcgm=document.formEmpreendedor.z01_numcgm.value;
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?testanome=true&funcao_js=parent.js_mostraEmpreendedor|z01_numcgm|z01_nome','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?testanome=true&pesquisa_chave='+numcgm+'&funcao_js=parent.js_mostraEmpreendedor1','Pesquisa',false);
    }
  }
  function js_mostraEmpreendedor(chave1,chave2){

    document.formEmpreendedor.z01_numcgm.value = chave1;
    document.formEmpreendedor.z01_nome.value   = chave2;
    db_iframe.hide();
  }

  function js_mostraEmpreendedor1(erro,chave){

    document.formEmpreendedor.z01_nome.value = chave;
    if(erro==true){

      document.formEmpreendedor.z01_numcgm.focus();
      document.formEmpreendedor.z01_numcgm.value = '';
    }
  }

  /**
   * Controle do Empreendimento
   */
  function js_validaEmpreendimento(){

    if ( !validaCNPJ($('am05_cnpj')) && !empty($F('am05_cnpj')) ) {

      alert( _M( sCaminhoMensagens + 'cnpj_invalido' ) );
      return false;
    }

    if( !isNumeric( $F('j14_codigo') ) ){

      alert( _M( sCaminhoMensagens + 'logradouro_obrigatorio' ) );
      return false;
    }

    if( !isNumeric( $F('am05_numero') ) ){

      alert( _M( sCaminhoMensagens + 'numero_obrigatorio' ) );
      return false;
    }

    if( !isNumeric( $F('j13_codi') ) ){

      alert( _M( sCaminhoMensagens + 'bairro_obrigatorio' ) );
      return false;
    }

    if (  $F('am05_cep').length < 8 ) {

      alert( _M( sCaminhoMensagens + 'cep_invalido' ) );
      return false;
    }

    if( !isNumeric( $F('am05_cep') ) ){

      alert( _M( sCaminhoMensagens + 'cep_obrigatorio' ) );
      return false;
    }

    if( !isNumeric( $F('am05_protprocesso') ) ){

      alert( _M( sCaminhoMensagens + 'protocolo_obrigatorio' ) );
      return false;
    }

   /**
    * Executa Consulta do Cgm para empreendimento
    */
   var oParametros = {
       sExecucao         : 'setEmpreendimento',
       iNumcgm           : $('z01_numcgm').value,
       iCnpj             : $('am05_cnpj').value,
       sNome             : encodeURIComponent($F('am05_nome')),
       sNomeFanta        : encodeURIComponent($F('am05_nomefanta')),
       iCodigoLogradouro : $('j14_codigo').value,
       iNumero           : $('am05_numero').value,
       sComplemento      : $('am05_complemento').value,
       iCodigoBairro     : $('j13_codi').value,
       iCep              : $('am05_cep').value,
       nAreaTotal        : $('am05_areatotal').value,
       iProcesso         : $('am05_protprocesso').value
   }

   if ( !empty($F('iCodigoEmpreendimento')) ) {
     oParametros.iCodigoEmpreendimento = $F('iCodigoEmpreendimento');
   }

   new AjaxRequest(sRpcEmpreendimento, oParametros, function(oRetorno, erro) {

      alert(oRetorno.sMensagem.urlDecode());

      if (erro) {
        return false;
      }

      $('iCodigoEmpreendimento').value = oRetorno.iCodigoEmpreendimento;

      /**
       * Libera abas de atividades e responsaveis
       * Bloqueia Incluir Botão Empreendimento
       * Mostro aba Atividades
       */
      $('incluirEmpreendimento').value = 'Alterar';
      oAbaAtividade.desbloquear();
      oAbaResponsavelTecnico.desbloquear();
      $('Atividades').click();

   }).setMessage( _M( sCaminhoMensagens + 'gravando_dados_empreendimento' ) ).execute();

  }

  function js_pesquisaj14_codigo(mostra) {

    if (mostra==true) {
      js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_mostraruas1|0|1','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?rural=1&pesquisa_chave='+document.formEmpreendimento.j14_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false,0);
    }
  }

  function js_mostraruas1(chave1,chave2) {

    document.formEmpreendimento.j14_codigo.value = chave1;
    document.formEmpreendimento.j14_nome.value   = chave2;
    db_iframe_ruas.hide();
  }

  function js_mostraruas(chave,erro) {

    document.formEmpreendimento.j14_nome.value = chave;
    if (erro==true) {
      document.formEmpreendimento.j14_codigo.focus();
      document.formEmpreendimento.j14_codigo.value = '';
    }
  }

  function js_bairro(mostra) {

    if (mostra==true) {
      js_OpenJanelaIframe('top.corpo','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|0|1','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('top.corpo','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.formEmpreendimento.j13_codi.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
    }
  }
  function js_mostrabairro(chave,erro) {

    document.formEmpreendimento.j13_descr.value = chave;
    if (erro==true) {
      document.formEmpreendimento.j13_codi.focus();
      document.formEmpreendimento.j13_codi.value = '';
    }
  }

  function js_mostrabairro1(chave1,chave2) {

    document.formEmpreendimento.j13_codi.value  = chave1;
    document.formEmpreendimento.j13_descr.value = chave2;
    db_iframe_bairro.hide();
  }

  /**
   * Controle de Atividades
   */
  var sRpcEmpreendimentoAtividade = "amb1_empreendimentoatividade.RPC.php";

  $('am03_ramo').addClassName('field-size6');
  $('am03_potencialpoluidor').addClassName('field-size6');
  $('am03_criterioatividadeimpacto').addClassName('field-size6');
  $('am06_principal').addClassName('field-size6');
  $('am04_sequencial').addClassName('field-size6');
  $('am06_principal').options[0].selected = true;

  /**
   * Valida Formulário da Atividade
   */
  function js_validaFormularioAtividade(){

    if( empty( $F('am03_sequencial') ) ){

      alert( _M( sCaminhoMensagens + 'codigo_atividade_obrigatorio' ) );
      return false;
    }

    if( empty( $F('am04_sequencial') ) ){

      alert( _M( sCaminhoMensagens + 'porte_obrigatorio' ) );
      return false;
    }

    var iQuantidadeAtividades = oGridAtividades.getNumRows();

    if( $F('am06_principal') == 0 && iQuantidadeAtividades == 0 ){

      alert( _M( sCaminhoMensagens + 'erro_principal_minimo' ) );
      return false;
    }

    var oParametros = {
        sExecucao              : 'setAtividadeEmpreendimento',
        iCodigoEmpreendimento  : $('iCodigoEmpreendimento').value,
        iCodigoAtividade       : $('am03_sequencial').value,
        iCodigoPorte           : $('am04_sequencial').value,
        lIsPrincipal           : $('am06_principal').value,
        AtualizaTipoSecundaria : false
    }

    if( !empty( $F('iCodigoEmpreendimentoAtividade') ) ) {

      /**
       * Validamos caso o tipo de atividade esteja sendo alterado de secundaria para primaria
       */
      if( oParametros.lIsPrincipal == 1 && $('tipoAtividade').className != 'hidden' ){

        if( !confirm( _M ( sCaminhoMensagens + 'confirma_alteracao_tipoatividade' ) ) ) {
          return false;
        }
        oParametros.AtualizaTipoSecundaria = true;
      }

      oParametros.sExecucao                      = 'alteraAtividadeEmpreendimento';
      oParametros.iCodigoEmpreendimentoAtividade = $('iCodigoEmpreendimentoAtividade').value;
    }

    new AjaxRequest(sRpcEmpreendimentoAtividade, oParametros, function(oRetorno, erro) {

      alert(oRetorno.sMensagem.urlDecode());
      if (erro) {
        return false;
      }

      /**
       * Recarrega grid e limpa formulário
       */
      js_limparFormularioAtividade();
      js_renderizarGridAtividades();

    }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_atividades' ) ).execute();

  }

  /**
   * Função que busca os dados da atividade (porte, ramo, etc)
   */
  function getAtividadeImpacto(){

    if( empty($F('am03_sequencial')) || $F('am03_sequencial') == 0 ){

      alert( _M( sCaminhoMensagens + 'codigo_atividade_obrigatorio' ) );
      return false;
    }

    var oParametros = {
        sExecucao             : 'getAtividadeImpacto',
        iCodigoEmpreendimento : $('iCodigoEmpreendimento').value,
        iCodigoAtividade      : $('am03_sequencial').value
    }

    new AjaxRequest(sRpcEmpreendimentoAtividade, oParametros, function(oRetorno, erro) {

      if (erro) {

        alert(oRetorno.sMensagem.urlDecode());
        return false;
      }

      /**
       * Remove options e adiciona o padrão
       */
      var oSelect       = $('am04_sequencial');
      oSelect.innerHTML = '';

      oRetorno.oAtividadeImpacto.each(

        function (oDado, iInd) {

          /**
           * Combo Porte da Atividade
           */
          if(iInd == 0){

            var oOpcao       = document.createElement("option");
                oOpcao.value = 0;
                oOpcao.text  = 'Selecione';
                oSelect.appendChild(oOpcao);
          }

          $('am03_ramo').value                     = oDado.am03_ramo.urlDecode();
          $('am03_potencialpoluidor').value        = oDado.am03_potencialpoluidor.urlDecode();
          $('am03_criterioatividadeimpacto').value = oDado.am01_descricao.urlDecode();

           var oOpcao            = document.createElement("option");
               oOpcao.value      = oDado.am02_sequencial;
               oOpcao.text       = oDado.am02_descricao.urlDecode();
               oSelect.appendChild(oOpcao);
        }
      );

    }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_atividades' ) ).execute();
  }

  /**
   * Grid de Atividades
   */
   oGridAtividades              = new DBGrid('GridAtividades');
   oGridAtividades.nameInstance = 'oGridAtividades';
   oGridAtividades.setCellWidth( new Array( '5%',
                                           '62%',
                                           '10%',
                                           '13%',
                                           '10%'
                                         ) );

   oGridAtividades.setCellAlign( new Array( 'center',
                                            'left',
                                            'center',
                                            'left',
                                            'center'
                                          ) );

   oGridAtividades.setHeader( new Array( 'Cód.',
                                        'Atividade',
                                        'Porte',
                                        'Tipo',
                                        'Operação'
                                      ) );

   oGridAtividades.show( $('gridAtividades') );
   oGridAtividades.clearAll( true );

/**
 * Refresh da Grid de Atividades
 */
function js_renderizarGridAtividades(){

 /**
  * Buscamos as atividades vinculadas ao empreendimento e populamos a grid
  */
  var oParametros = {
      sExecucao             : 'getAtividadeEmpreendimento',
      iCodigoEmpreendimento : $('iCodigoEmpreendimento').value
  }

  new AjaxRequest(sRpcEmpreendimentoAtividade, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    oGridAtividades.clearAll( true );

    oRetorno.oEmpreendimentoAtividadeImpacto.each(

      function (oDado, iInd) {

        var aRow            = new Array();
            aRow[0]         = oDado.am03_sequencial;
            aRow[1]         = oDado.am03_descricao.urlDecode();
            aRow[2]         = oDado.am02_descricao.urlDecode();
            aRow[3]         = "Principal";
            if(oDado.am06_principal == 'f'){
              aRow[3]       = "Secundária";
            }

        if (aRow[1].length > 57) {
          aRow[1] = aRow[1].substring(0, 57) + "...";
        }

        var sAlteraRegistro = '<a href="#" onclick="js_alterarAtividade(' + oDado.am06_sequencial + ');">A</a>';
        var sExcluiRegistro = '<a href="#" onclick="js_excluirAtividade(' + oDado.am06_sequencial + ');">E</a>';
            aRow[4]         =  sAlteraRegistro + '&nbsp;&nbsp;' + sExcluiRegistro;

        oGridAtividades.addRow(aRow);
      }
  );

  oGridAtividades.renderRows();

  }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_atividades' ) ).execute();
}

/**
 * Alterar Atividade vinculada ao empreendimento
 * @param  int iCodigoEmpreendimentoAtividade
 */
function js_alterarAtividade( iCodigoEmpreendimentoAtividade ){

  var oParametros = {
      sExecucao                      : 'getEmpreendimentoAtividadeImpacto',
      iCodigoEmpreendimentoAtividade : iCodigoEmpreendimentoAtividade
  }

  new AjaxRequest(sRpcEmpreendimentoAtividade, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    /**
     * Populamos o formulário da atividade com os dados para alteração
     */
    var iPorteAtividadeImpacto = null;
    var iAtividadePrincipal    = null;
    oRetorno.oEmpreendimentoAtividadeImpacto.each(

       function (oDado, iInd) {

         $('am03_sequencial').value                = oDado.am03_sequencial;
         $('am03_descricao').value                 = oDado.am03_descricao.urlDecode();
         $('am03_ramo').value                      = oDado.am03_ramo.urlDecode();
         $('am03_potencialpoluidor').value         = oDado.am03_potencialpoluidor.urlDecode();
         $('am03_criterioatividadeimpacto').value  = oDado.am01_descricao.urlDecode();
         $('iCodigoEmpreendimentoAtividade').value = oDado.am06_sequencial;

         iPorteAtividadeImpacto = oDado.am04_porteatividadeimpacto;
         iAtividadePrincipal    = oDado.am06_principal;
       }
    );

    var oSelect       = $('am04_sequencial');
    oSelect.innerHTML = '';

    oRetorno.oPorteAtividades.each(

       function (oDado, iInd) {

         var oOpcao            = document.createElement("option");
             oOpcao.value      = oDado.am02_sequencial;
             oOpcao.text       = oDado.am02_descricao.urlDecode();

             if( iPorteAtividadeImpacto == oDado.am02_sequencial ){
               oOpcao.selected = 'selected';
             }
             oSelect.appendChild(oOpcao);
       }
    );

    /**
     * Atividade Principal
     */
     var oSelect = $('am06_principal');
     for(var i = 0; i < oSelect.length; i++){

       if( iAtividadePrincipal == 't'){

         oSelect[0].selected = 'selected';
         $('tipoAtividade').addClassName('hidden');
       } else {

         oSelect[1].selected = 'selected';
         $('tipoAtividade').removeClassName('hidden');
       }
     }

   /**
    * Desabilita func
    */
    $('am03_sequencial').removeClassName('habilitado');
    $('am03_sequencial').addClassName('desabilitado');
    $('am03_sequencial').setAttribute('onchange','');
    $('am03_sequencial_ancora').setAttribute('onclick','');

    /**
     * Altera label e função do campo lançar e seta o hidden do sequencial
     */
    $('lancar').value = "Alterar";

  }).setMessage( _M( sCaminhoMensagens + 'gravando_dados_atividades' ) ).execute();

}

/**
 * Excluir Atividade vinculada ao empreendimento
 * @param  int iCodigoEmpreendimentoAtividade
 */
function js_excluirAtividade( iCodigoEmpreendimentoAtividade ){

  js_limparFormularioAtividade();
  if( !confirm( _M ( sCaminhoMensagens + 'confirma_exclusao' ) ) ) {
    return false;
  }

  var oParametros = {
      sExecucao                      : 'excluiAtividadeEmpreendimento',
      iCodigoEmpreendimento          : $('iCodigoEmpreendimento').value,
      iCodigoEmpreendimentoAtividade : iCodigoEmpreendimentoAtividade
  }

  new AjaxRequest(sRpcEmpreendimentoAtividade, oParametros, function(oRetorno, erro) {

    alert(oRetorno.sMensagem.urlDecode());

    if (erro) {
      return false;
    }

    /**
     * Recarrega grid
     */
    js_renderizarGridAtividades();
  }).setMessage( _M( sCaminhoMensagens + 'excluindo_dados_atividades' ) ).execute();
}

/**
 * Limpa formulario de atividades
 */
function js_limparFormularioAtividade(){

  $('formAtividade').reset();

  var oSelect       = $('am04_sequencial');
  oSelect.innerHTML = '';
  var oOpcao        = document.createElement("option");
      oOpcao.value  = 0;
      oOpcao.text   = 'Selecione';
      oSelect.appendChild(oOpcao);

  $('am06_principal').options[0].selected   = true;
  $('lancar').value                         = "Lançar";
  $('iCodigoEmpreendimentoAtividade').value = null;

  /**
   * Habilita o select de tipo de atividade
   */
  $('tipoAtividade').removeClassName('hidden');

  /**
   * Habilita func de pesquisa
   */
  $('am03_sequencial').removeClassName('desabilitado');
  $('am03_sequencial').addClassName('habilitado');
  $('am03_sequencial').setAttribute('onchange','js_pesquisaAtividadeImpacto(false);');
  $('am03_sequencial_ancora').setAttribute('onclick','js_pesquisaAtividadeImpacto(true);');
}

/**
 * Func Atividade
 */
function js_pesquisaAtividadeImpacto(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_atividadeimpacto','func_atividadeimpacto.php?funcao_js=parent.js_mostraatividadeimpacto1|0|1','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('top.corpo','db_iframe_atividadeimpacto','func_atividadeimpacto.php?pesquisa_chave='+document.formAtividade.am03_sequencial.value+'&funcao_js=parent.js_mostraatividadeimpacto','Pesquisa',false,0);
  }
}

function js_mostraatividadeimpacto1(chave1,chave2) {

  document.formAtividade.am03_sequencial.value = chave1;
  document.formAtividade.am03_descricao.value  = chave2;
  db_iframe_atividadeimpacto.hide();
  getAtividadeImpacto();
}

function js_mostraatividadeimpacto(chave,erro) {

  document.formAtividade.am03_descricao.value = chave;
  if (erro==true) {

    document.formAtividade.am03_sequencial.focus();
    document.formAtividade.am03_sequencial.value = '';
  }else{
    getAtividadeImpacto();
  }
}


/**
 * Controle dos Responsaveis Técnicos
 */

var sRpcResponsavelTecnico = "amb1_responsaveltecnico.RPC.php";

/**
 * Cria Grid de Responsáveis Técnicos
 */
oGridReponsaveis              = new DBGrid('GridReponsaveis');
oGridReponsaveis.nameInstance = 'oGridReponsaveis';
oGridReponsaveis.setCellWidth( new Array( '10%',
                                          '50%',
                                          '20%',
                                          '10%'
                                     ) );
oGridReponsaveis.setCellAlign( new Array( 'center',
                                          'left',
                                          'left',
                                          'center'
                                     ) );
oGridReponsaveis.setHeader( new Array( 'CGM',
                                       'Nome',
                                       'Titulação',
                                       'Operação'
                                  ) );
oGridReponsaveis.show( $('gridReponsaveis') );
oGridReponsaveis.clearAll( true );

/**
 * Função responsavel pelo refresh da grid
 */
function js_renderizarGridResponsavel() {

  var oParametros = {
      sExecucao       : 'getResponsavelTecnico',
      iEmpreendimento : $('iCodigoEmpreendimento').value
  }

  new AjaxRequest(sRpcResponsavelTecnico, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    oGridReponsaveis.clearAll( true );

    oRetorno.aResponsaveis.each(

      function (oDado, iInd) {

        var aRow            = new Array();
            aRow[0]         = oDado.z01_numcgm;
            aRow[1]         = oDado.z01_nome.urlDecode();
            aRow[2]         = oDado.titulacao;

        var sExcluiRegistro = '<a href="#" onclick="js_excluirResponsavel(' + oDado.am07_sequencial +');">E</a>';
            aRow[3] = sExcluiRegistro;
            oGridReponsaveis.addRow(aRow);
      }
    );
    oGridReponsaveis.renderRows();

  }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_responsaveis' ) ).execute();
}

function js_lancaResponsavel() {

  if( empty( $F('iCgmResponsavel') ) ){

      alert( _M( sCaminhoMensagens + 'cgm_obrigatorio' ) );
      return false;
  }

  var oParametros = {
      sExecucao       : 'setResponsavelTecnico',
      iCgmResponsavel : $('iCgmResponsavel').value,
      iEmpreendimento : $('iCodigoEmpreendimento').value
  }

  new AjaxRequest(sRpcResponsavelTecnico, oParametros, function(oRetorno, erro) {

    alert(oRetorno.sMensagem.urlDecode());

    if (erro) {

      js_limparFormularioResponsavel();
      return false;
    }

    js_renderizarGridResponsavel();
    js_limparFormularioResponsavel();
  }).setMessage( _M( sCaminhoMensagens + 'gravando_dados_responsavel' ) ).execute();

}

function js_excluirResponsavel( iCodigoResponsavel ) {

    if (oGridReponsaveis.getNumRows() == 1) {

      alert( _M( sCaminhoMensagens + 'erro_minimo_responsavel') );
      return false;
    }

    if( !confirm( _M ( sCaminhoMensagens + 'confirma_exclusao_responsavel' ) ) ) {
      return false;
    }

    var oParametros = {
        sExecucao          : 'excluirResponsavelTecnico',
        iCodigoResponsavel : iCodigoResponsavel
    }

    new AjaxRequest(sRpcResponsavelTecnico, oParametros, function(oRetorno, erro) {

      alert(oRetorno.sMensagem.urlDecode());

      if (erro) {
        return false;
      }

      js_renderizarGridResponsavel();

    }).setMessage( _M( sCaminhoMensagens + 'excluindo_dados_responsavel' ) ).execute();
}


function js_cgm_responsavel(mostra){

  var numcgm=document.formResponsavelTecnico.iCgmResponsavel.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?testanome=true&funcao_js=parent.js_mostra_responsavel|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?testanome=true&pesquisa_chave='+numcgm+'&funcao_js=parent.js_mostra_responsavel1','Pesquisa',false);
  }
}

function js_mostra_responsavel(chave1,chave2){

  document.formResponsavelTecnico.iCgmResponsavel.value = chave1;
  document.formResponsavelTecnico.sNomeResponsavel.value   = chave2;
  db_iframe.hide();
}

function js_mostra_responsavel1(erro,chave){

  document.formResponsavelTecnico.sNomeResponsavel.value = chave;

  if(erro==true){

    document.formResponsavelTecnico.iCgmResponsavel.focus();
    document.formResponsavelTecnico.iCgmResponsavel.value = '';
  }
}

/**
 * Func protprocesso
 */
function js_pesquisaProcesso(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p51_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?pesquisa_chave='+$F('am05_protprocesso')+'&rettipoproc=true&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
  }
}

function js_mostraprocesso1(chave1,chave2){

  $('am05_protprocesso').value = chave1;
  $('p51_descr').value         = chave2;
  db_iframe_proc.hide();
}

function js_mostraprocesso(chave, sDescricao, lErro){

  $('p51_descr').value = sDescricao;
  if( lErro==true){

    $('am05_protprocesso').focus();
    $('am05_protprocesso').value = '';
  }
}

function js_limparFormularioResponsavel(){
  $('formResponsavelTecnico').reset();
}

/**
 * Define callback das abas
 */
oAbaAtividade.setCallback( js_renderizarGridAtividades );
oAbaResponsavelTecnico.setCallback( js_renderizarGridResponsavel );

</script>