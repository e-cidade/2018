<?php
/*
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

$oDaoParfiscal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k00_descr");
$clrotulo->label("k01_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("v03_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("p51_descr");
$clrotulo->label("db82_descricao");

$aOpcaoLogica = array('f'=>'Não', 't'=>'Sim');
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Parâmetros</legend>

    <table>
      <tr>
        <td nowrap title="<?php echo $Ty32_instit; ?>">
          <?php
            db_ancora($Ly32_instit, "js_pesquisay32_instit(true);", 3);
          ?>
        </td>
        <td>
          <?php
            db_input('y32_instit',10,$Iy32_instit,true,'text',3," onchange='js_pesquisay32_instit(false);'");
            db_input('nomeinst',50,$Inomeinst,true,'text',3,'');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?php echo $Ty32_tipoprocpadrao; ?>">
          <?php
            db_ancora($Ly32_tipoprocpadrao,"js_pesquisay32_tipoprocpadrao(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?php
            db_input('y32_tipoprocpadrao',10,$Iy32_tipoprocpadrao,true,'text',$db_opcao," onchange='js_pesquisay32_tipoprocpadrao(false);'");
            db_input('p51_descr',50,$Ip51_descr,true,'text',3,'');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?php echo $Ty32_modaidof; ?>">
          <label for="y32_modaidof"><?php echo $Ly32_modaidof; ?></label>
        </td>
        <td>
          <?php
            $aOpcoes = array('1'=>'AIDOF Padrão', '2'=>'AIDOF sem Pedido');
            db_select('y32_modaidof',$aOpcoes, true,$db_opcao);
          ?>
        </td>
      </tr>
    </table>

    <fieldset>
      <legend>Auto de Infração</legend>

      <table>
        <tr>
          <td nowrap title="<?php echo $Ty32_tipo; ?>">
            <?php
              db_ancora($Ly32_tipo, "js_pesquisay32_tipo(true);", $db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('y32_tipo', 10, $Iy32_tipo, true, 'text', $db_opcao, " onchange='js_pesquisay32_tipo(false);'");
              db_input('k00_descr',50,$Ik00_descr,true,'text',3,'')
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_hist; ?>">
            <?php
              db_ancora($Ly32_hist,"js_pesquisay32_hist(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('y32_hist',10,$Iy32_hist,true,'text',$db_opcao," onchange='js_pesquisay32_hist(false);'");
              db_input('k01_descr',50,$Ik01_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_procprotbaixaauto; ?>">
            <label for="y32_procprotbaixaauto"><?php echo $Ly32_procprotbaixaauto; ?></label>
          </td>
          <td>
            <?php

              $aProcProtBaixaAuto = array('1'=>'Sim', '2'=>'Não');
              db_select('y32_procprotbaixaauto',$aProcProtBaixaAuto,true,$db_opcao,"");
            ?>
          </td>
        </tr>

       <tr>
        <td nowrap><label for="utilizadocpadrao"><strong>Utiliza Documento Padrão:</strong></label></td>
        <td align="left">
          <?php

            $aOpcoes = array( "0" => "Sim", "1" =>"Não");
            db_select("utilizadocpadrao",$aOpcoes,true,$db_opcao, "onchange='js_templateAutodeInfracao(this.value);'");
          ?>
        <td>
       </tr>
       <tr id="linhaTemplate" style="display:none;">
         <td nowrap="nowrap" title="<? echo $Tp90_db_documentotemplate; ?>">
           <?php
             db_ancora("<strong>Documento Template:</strong>","js_pesquisaDocumentoAutodeInfracao(true);",$db_opcao);
           ?>
         </td>
         <td nowrap="nowrap">
           <?php
             db_input('y32_templateautoinfracao',10,$Iy32_templateautoinfracao,true,'text',$db_opcao,'onchange="js_pesquisaDocumentoAutodeInfracao(false);"');
             db_input('db82_descricaoautodeinfracao',50,$Idb82_descricao,true,'text',3,'','db82_descricaoautodeinfracao');
           ?>
         </td>
      </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>Levantamento Fiscal</legend>

      <table>
        <tr>
          <td nowrap title="<?php echo $Ty32_receit; ?>">
            <?php
              db_ancora($Ly32_receit,"js_pesquisay32_receit(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('y32_receit',10,$Iy32_receit,true,'text',$db_opcao," onchange='js_pesquisay32_receit(false);'");
              db_input('k02_descr',50,$Ik02_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_receitexp; ?>">
            <?php
              db_ancora($Ly32_receitexp,"js_pesquisay32_receitexp(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('y32_receitexp',10,$Iy32_receitexp,true,'text',$db_opcao," onchange='js_pesquisay32_receitexp(false);'");
              db_input('k02_descrexp',50,$Ik02_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_proced; ?>">
            <?php
              db_ancora($Ly32_proced,"js_pesquisay32_proced(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('y32_proced',10,$Iy32_proced,true,'text',$db_opcao," onchange='js_pesquisay32_proced(false);'");
              db_input('v03_descr',50,$Iv03_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_procedexp; ?>">
            <?php
              db_ancora($Ly32_procedexp,"js_pesquisay32_procedexp(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('y32_procedexp',10,$Iy32_procedexp,true,'text',$db_opcao," onchange='js_pesquisay32_procedexp(false);'");
              db_input('v03_descrexp',50,$Iv03_descr,true,'text',3,'');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>Alvará Sanitário</legend>

      <table>
        <tr>
          <td nowrap title="<?php echo $Ty32_impdatas; ?>">
            <label for="y32_impdatas"><?php echo $Ly32_impdatas; ?></label>
          </td>
          <td>
            <?php
              db_select('y32_impdatas', $aOpcaoLogica, true, $db_opcao);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_impcodativ; ?>">
            <label for="y32_impcodativ"><?php echo $Ly32_impcodativ; ?></label>
          </td>
          <td>
            <?php
              db_select('y32_impcodativ', $aOpcaoLogica, true, $db_opcao);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_impobs; ?>">
            <label for="y32_impobs"><?php echo $Ly32_impobs; ?></label>
          </td>
          <td>
            <?php
              db_select('y32_impobs', $aOpcaoLogica,true,$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_modalvara; ?>">
            <label for="y32_modalvara"><?php echo $Ly32_modalvara; ?></label>
          </td>
          <td>
            <?php

              $aOpcoes = array(
                  '1' => 'Metade A4',
                  '2' => 'A4',
                  '3' => 'Documento Template'
                );

              db_select('y32_modalvara', $aOpcoes, true, $db_opcao, "onchange=\"js_verificaTemplate()\"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap="nowrap">
            <?php
              db_ancora($Ly32_templatealvarasanitarioprovisorio, "js_pesquisaTemplateAlvaraSanitarioProvisorio(true);", $db_opcao);
            ?>
          </td>

          <td nowrap="nowrap">
            <?php
              db_input( "y32_templatealvarasanitarioprovisorio",
                        10,
                        $Iy32_templatealvarasanitarioprovisorio,
                        true,
                        "text",
                        $db_opcao,
                        "onchange=\"js_pesquisaTemplateAlvaraSanitarioProvisorio(false);\"" );
              db_input('db82_descricao', 40, $Idb82_descricao, true, 'text', 3);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap="nowrap">
            <?php
              db_ancora($Ly32_templatealvarasanitariopermanente, "js_pesquisaTemplateAlvaraSanitarioPermanente(true);", $db_opcao);
            ?>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input( "y32_templatealvarasanitariopermanente",
                        10,
                        $Iy32_templatealvarasanitariopermanente,
                        true,
                        "text",
                        $db_opcao,
                        "onchange=\"js_pesquisaTemplateAlvaraSanitarioPermanente(false);\"" );
              db_input('db82_descricaoPermanente', 40, $Idb82_descricao, true, 'text', 3);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_impobslanc; ?>">
            <label for="y32_impobslanc"><?php echo $Ly32_impobslanc; ?></label>
          </td>
          <td>
            <?php
              db_select('y32_impobslanc',$aOpcaoLogica,true,$db_opcao);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_sanidepto; ?>">
             <label for="y32_sanidepto"><?php echo $Ly32_sanidepto; ?></label>
          </td>
          <td>
            <?php
              $aOpcoes = array('0'=>'Não', '1'=>'Sim');
              db_select('y32_sanidepto',$aOpcoes,true,$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_sanbaixadiv; ?>">
            <label for="y32_sanbaixadiv"><?php echo $Ly32_sanbaixadiv; ?></label>
          </td>
          <td>
            <?php
              $aOpcoes = array('0'=>'Não', '1'=>'Sim');
              db_select('y32_sanbaixadiv',$aOpcoes,true,$db_opcao,"");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>Vistorias</legend>

      <table>
        <tr>
          <td nowrap title="<?php echo $Ty32_formvist; ?>">
            <label for="y32_formvist"><?php echo $Ly32_formvist; ?></label>
          </td>
          <td>
            <?php
              db_input('y32_formvist',10,$Iy32_formvist,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_calcvistanosanteriores; ?>">
             <label for="y32_calcvistanosanteriores"><?php echo $Ly32_calcvistanosanteriores; ?></label>
          </td>
          <td>
            <?php
              $x = array('f'=>'Não','t'=>'Sim');
              db_select('y32_calcvistanosanteriores',$x,true,$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_utilizacalculoporteatividade; ?>">
            <label for="y32_utilizacalculoporteatividade"><?php echo $Ly32_utilizacalculoporteatividade; ?></label>
          </td>
          <td>
            <?php

              $aUtilizaCalculoPorteAtividade = array('t'=>'Sim', 'f'=>'Não');
              db_select('y32_utilizacalculoporteatividade', $aUtilizaCalculoPorteAtividade, true, $db_opcao, "");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty32_calculavistoriamei; ?>">
            <label for="y32_calculavistoriamei"><?php echo $Ly32_calculavistoriamei; ?></label>
          </td>
          <td>
            <?php

              $aCalculaVistoriaMei = array('t'=>'Sim', 'f'=>'Não');
              db_select('y32_calculavistoriamei', $aCalculaVistoriaMei, true, $db_opcao);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

  </fieldset>

  <input name="<?php echo ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")); ?>"
         type="submit" id="db_opcao"
         value="<?php echo ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")); ?>"
         <?php echo ($db_botao == false ? "disabled" : ""); ?> onclick="return js_validaCampos();" />

  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"/>

</form>
<script type="text/javascript">

  function js_validaCampos(){

    if($F('utilizadocpadrao') == 1){

      if ($F('y32_templateautoinfracao') == ''){

        alert('Campo Template do Auto de Infração é de preenchimento obrigatório!')
        return false;
      }
    }

    return true;
  }

  /**
   * Template Auto de Infrção (hide/show)
   */
  function js_verificaTemplateAuto() {

    if( !empty($F('y32_templateautoinfracao')) ){
      $('utilizadocpadrao').options[1].selected = true;
    }

    var oTrAutodeInfracao = $('y32_templateautoinfracao').up('tr');

    oTrAutodeInfracao.hide();
    if ($F('utilizadocpadrao') == '1') {
      oTrAutodeInfracao.show();
    }
  }

  /**
   * Template Auto de Infraçao
   */
  function js_templateAutodeInfracao(iOpcao) {

    if( typeof(iOpcao) === 'undefined' ) {
      iOpcao = $F('utilizadocpadrao');
    }

    if( iOpcao == 1 ) {
      $('linhaTemplate').style.display = '';
    }else{

      $('linhaTemplate').style.display        = 'none';
      $('y32_templateautoinfracao').value     = '';
      $('db82_descricaoautodeinfracao').value = '';
    }
  }

  /**
   * Pesquisa dados via lookup ou digitação
   * @param object  oElemento Elemento HTML base para pesquisa
   * @param boolean lMostra   Valida se mostra a lookup de pesquisa
   */
  function js_pesquisaDocumentoAutodeInfracao(lMostra) {

   if (lMostra) {
     sArquivoPesquisa = 'func_db_documentotemplate.php?funcao_js=parent.js_mostraDocumentoLookUpAutodeInfracao|db82_sequencial|db82_descricao&tipo=51';
   } else {
     sArquivoPesquisa = 'func_db_documentotemplate.php?pesquisa_chave=' + $F('y32_templateautoinfracao') + '&funcao_js=parent.js_mostraDocumentoDigitacaoAutodeInfracao&tipo=51';
   }

    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_db_documentotemplate', sArquivoPesquisa, 'Pesquisa', lMostra);
  }

  function js_mostraDocumentoDigitacaoAutodeInfracao(sRetorno, lErro){

    $('db82_descricaoautodeinfracao').value = sRetorno;

    if (lErro) {
      $('db82_descricaoautodeinfracao').focus();
      $('db82_descricaoautodeinfracao').value = '';
    }
  }

  function js_mostraDocumentoLookUpAutodeInfracao(iCodigo, sRetorno) {

      $('y32_templateautoinfracao').value     = iCodigo;
      $('db82_descricaoautodeinfracao').value = sRetorno;
      db_iframe_db_documentotemplate.hide();
  }

  /**
   * Template Alvara Sanitario
   */
  function js_verificaTemplate() {

    var oTrProvisorio = $('y32_templatealvarasanitarioprovisorio').up('tr');
    var oTrPermanente = $('y32_templatealvarasanitariopermanente').up('tr');

    oTrProvisorio.hide();
    oTrPermanente.hide();

    if ($F('y32_modalvara') == '3') {

      oTrProvisorio.show();
      oTrPermanente.show();
    }
  }

  /**
   * LookUp Alvara Sanitario Provisorio
   */
  function js_pesquisaTemplateAlvaraSanitarioProvisorio(lMostra) {

    if (lMostra) {
      sArquivoPesquisa = 'func_db_documentotemplate.php?funcao_js=parent.js_mostraLookUpAlvaraSanitarioProvisorio|db82_sequencial|db82_descricao&tipo=47';
    } else {
      sArquivoPesquisa = 'func_db_documentotemplate.php?pesquisa_chave=' + $F('y32_templatealvarasanitarioprovisorio') + '&funcao_js=parent.js_mostraDigitacaoAlvaraSanitarioProvisorio&tipo=47';
    }

    js_OpenJanelaIframe( 'CurrentWindow.corpo',
                         'db_iframe_db_documentotemplate',
                         sArquivoPesquisa,
                         'Pesquisa Documentos Template Alvará Sanitario Provisório',
                         lMostra);
  }

  function js_mostraDigitacaoAlvaraSanitarioProvisorio(sRetorno, lErro){

    $('db82_descricao').value = sRetorno;

    if (lErro) {

      $('y32_templatealvarasanitarioprovisorio').focus();
      $('y32_templatealvarasanitarioprovisorio').value = '';
    }
  }

  function js_mostraLookUpAlvaraSanitarioProvisorio(iCodigo, sRetorno) {

    $('y32_templatealvarasanitarioprovisorio').value = iCodigo;
    $('db82_descricao').value                        = sRetorno;

    db_iframe_db_documentotemplate.hide();
  }

  /**
   * LookUp Alvara Sanitario Permanente
   */
  function js_pesquisaTemplateAlvaraSanitarioPermanente(lMostra) {

    if (lMostra) {
      sArquivoPesquisa = 'func_db_documentotemplate.php?funcao_js=parent.js_mostraLookUpAlvaraSanitarioPermanente|db82_sequencial|db82_descricao&tipo=47';
    } else {
      sArquivoPesquisa = 'func_db_documentotemplate.php?pesquisa_chave=' + $F('y32_templatealvarasanitariopermanente') + '&funcao_js=parent.js_mostraDigitacaoAlvaraSanitarioPermanente&tipo=47';
    }

    js_OpenJanelaIframe( 'CurrentWindow.corpo',
                         'db_iframe_db_documentotemplate',
                         sArquivoPesquisa,
                         'Pesquisa Documentos Template Alvará Sanitario Permanente',
                         lMostra);
  }

  function js_mostraDigitacaoAlvaraSanitarioPermanente(sRetorno, lErro){

    $('db82_descricaoPermanente').value = sRetorno;

    if (lErro) {

      $('y32_templatealvarasanitariopermanente').focus();
      $('y32_templatealvarasanitariopermanente').value = '';
    }
  }

  function js_mostraLookUpAlvaraSanitarioPermanente(iCodigo, sRetorno) {

    $('y32_templatealvarasanitariopermanente').value = iCodigo;
    $('db82_descricaoPermanente').value              = sRetorno;

    db_iframe_db_documentotemplate.hide();
  }

  function js_pesquisay32_tipo(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
    }else{
       if(document.form1.y32_tipo.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.y32_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
       }else{
         document.form1.k00_descr.value = '';
       }
    }
  }
  function js_mostraarretipo(chave,erro){
    document.form1.k00_descr.value = chave;
    if(erro==true){
      document.form1.y32_tipo.focus();
      document.form1.y32_tipo.value = '';
    }
  }
  function js_mostraarretipo1(chave1,chave2){
    document.form1.y32_tipo.value = chave1;
    document.form1.k00_descr.value = chave2;
    db_iframe_arretipo.hide();
  }
  function js_pesquisay32_hist(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true);
    }else{
       if(document.form1.y32_hist.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.y32_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false);
       }else{
         document.form1.k01_descr.value = '';
       }
    }
  }
  function js_mostrahistcalc(chave,erro){
    document.form1.k01_descr.value = chave;
    if(erro==true){
      document.form1.y32_hist.focus();
      document.form1.y32_hist.value = '';
    }
  }
  function js_mostrahistcalc1(chave1,chave2){
    document.form1.y32_hist.value = chave1;
    document.form1.k01_descr.value = chave2;
    db_iframe_histcalc.hide();
  }

  function js_pesquisay32_receit(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
    }else{
       if(document.form1.y32_receit.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.y32_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
       }else{
         document.form1.k02_descr.value = '';
       }
    }
  }
  function js_mostratabrec(chave,erro){
    document.form1.k02_descr.value = chave;
    if(erro==true){
      document.form1.y32_receit.focus();
      document.form1.y32_receit.value = '';
    }
  }
  function js_mostratabrec1(chave1,chave2){
    document.form1.y32_receit.value = chave1;
    document.form1.k02_descr.value = chave2;
    db_iframe_tabrec.hide();
  }
  function js_pesquisay32_proced(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_proced','func_proced.php?funcao_js=parent.js_mostraproced1|v03_codigo|v03_descr','Pesquisa',true);
    }else{
       if(document.form1.y32_proced.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_proced','func_proced.php?pesquisa_chave='+document.form1.y32_proced.value+'&funcao_js=parent.js_mostraproced','Pesquisa',false);
       }else{
         document.form1.v03_descr.value = '';
       }
    }
  }
  function js_mostraproced(chave,erro){
    document.form1.v03_descr.value = chave;
    if(erro==true){
      document.form1.y32_proced.focus();
      document.form1.y32_proced.value = '';
    }
  }
  function js_mostraproced1(chave1,chave2){
    document.form1.y32_proced.value = chave1;
    document.form1.v03_descr.value = chave2;
    db_iframe_proced.hide();
  }
  function js_pesquisay32_instit(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
    }else{
       if(document.form1.y32_instit.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.y32_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
       }else{
         document.form1.nomeinst.value = '';
       }
    }
  }
  function js_mostradb_config(chave,erro){
    document.form1.nomeinst.value = chave;
    if(erro==true){
      document.form1.y32_instit.focus();
      document.form1.y32_instit.value = '';
    }
  }
  function js_mostradb_config1(chave1,chave2){
    document.form1.y32_instit.value = chave1;
    document.form1.nomeinst.value = chave2;
    db_iframe_db_config.hide();
  }
  function js_pesquisay32_tipoprocpadrao(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoproc','func_tipoproc.php?funcao_js=parent.js_mostratipoproc1|p51_codigo|p51_descr','Pesquisa',true);
    }else{
       if(document.form1.y32_tipoprocpadrao.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoproc','func_tipoproc.php?pesquisa_chave='+document.form1.y32_tipoprocpadrao.value+'&funcao_js=parent.js_mostratipoproc','Pesquisa',false);
       }else{
         document.form1.p51_descr.value = '';
       }
    }
  }
  function js_mostratipoproc(chave,erro){
    document.form1.p51_descr.value = chave;
    if(erro==true){
      document.form1.y32_tipoprocpadrao.focus();
      document.form1.y32_tipoprocpadrao.value = '';
    }
  }
  function js_mostratipoproc1(chave1,chave2){
    document.form1.y32_tipoprocpadrao.value = chave1;
    document.form1.p51_descr.value = chave2;
    db_iframe_tipoproc.hide();
  }
  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_parfiscal','func_parfiscal.php?funcao_js=parent.js_preenchepesquisa|y32_instit','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_parfiscal.hide();
    <?php
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
  function js_pesquisay32_receitexp(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrecexp','func_tabrec.php?funcao_js=parent.js_mostratabrec1exp|k02_codigo|k02_descr','Pesquisa',true);
    }else{
       if(document.form1.y32_receit.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrecexp','func_tabrec.php?pesquisa_chave='+document.form1.y32_receitexp.value+'&funcao_js=parent.js_mostratabrecexp','Pesquisa',false);
       }else{
         document.form1.k02_descrexp.value = '';
       }
    }
  }
  function js_mostratabrecexp(chave,erro){
    document.form1.k02_descrexp.value = chave;
    if(erro==true){
      document.form1.y32_receitexp.focus();
      document.form1.y32_receitexp.value = '';
    }
  }
  function js_mostratabrec1exp(chave1,chave2){
    document.form1.y32_receitexp.value = chave1;
    document.form1.k02_descrexp.value = chave2;
    db_iframe_tabrecexp.hide();
  }

  function js_pesquisay32_procedexp(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_procedexp','func_proced.php?funcao_js=parent.js_mostraproced1exp|v03_codigo|v03_descr','Pesquisa',true);
    }else{
       if(document.form1.y32_proced.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_procedexp','func_proced.php?pesquisa_chave='+document.form1.y32_procedexp.value+'&funcao_js=parent.js_mostraprocedexp','Pesquisa',false);
       }else{
         document.form1.v03_descrexp.value = '';
       }
    }
  }
  function js_mostraprocedexp(chave,erro){
    document.form1.v03_descrexp.value = chave;
    if(erro==true){
      document.form1.y32_procedexp.focus();
      document.form1.y32_procedexp.value = '';
    }
  }
  function js_mostraproced1exp(chave1,chave2){
    document.form1.y32_procedexp.value = chave1;
    document.form1.v03_descrexp.value = chave2;
    db_iframe_procedexp.hide();
  }

  js_verificaTemplate();
  js_verificaTemplateAuto();

  if ($F('y32_templateautoinfracao') != '') {
    js_pesquisaDocumentoAutodeInfracao(false);
  }

  if ($F('y32_templatealvarasanitariopermanente') != '') {
    js_pesquisaTemplateAlvaraSanitarioPermanente(false);
  }

  if ($F('y32_receitexp') != '') {
    js_pesquisay32_receitexp(false);
  }

  if ($F('y32_procedexp') != '') {
    js_pesquisay32_procedexp(false);
  }
</script>
