<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

//MODULO: recursos humanos
$cltipoasse->rotulo->label();
$clportariatipo->rotulo->label();

$clrotulo->label("h42_descr");
$clrotulo->label("h37_modportariaindividual");
$clrotulo->label("h38_modportariacoletiva");

$h12_natureza = !empty($h12_natureza) ? $h12_natureza : '';
?>
<br />
<style>
  #h12_assent, #h12_dias {
    width: 70px;
  }
  #h12_reltot, #h12_tipo, #h12_efetiv, #h12_regenc, #h12_tiporeajuste {
    width: 190px;
  }
  #h12_relgra, #h12_graefe, #h12_tipefe, #h12_vinculaperiodoaquisitivo, #h30_portariatipoato, #h30_portariaproced, #h12_natureza {
    width: 95px;
  }
  #h12_descr, #h30_amparolegal {
    width: 400px;
  }
  #h30_portariatipoatodescr, #h30_portariaproceddescr {
    width: 300px;
  }
</style>
<form name="form1" method="post" action="">

  <?php db_input('h79_db_cadattdinamico',5,'',true,'hidden',3,"");  ?>
  <center>
    <fieldset style="width:700px;text-align:right;">
      <Legend align="left"><strong>Assentamentos/Afastamentos</strong></Legend>
      <fieldset style="width:700px;">
        <legend align="left"><strong>Dados de assentamento</strong></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Th12_assent?>">
              <label id="lbl_h12_assent" for="h12_assent"><?=@$Lh12_assent?></label>
            </td>
            <td>
              <?
              db_input('h12_codigo',5,$Ih12_codigo,true,'hidden',3,"");
              db_input('h12_assent',5,$Ih12_assent,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_descr?>">
              <label id="lbl_h12_descr" for="h12_descr"><?=@$Lh12_descr?></label>
            </td>
            <td colspan="3">
              <?
              db_input('h12_descr',40,$Ih12_descr,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_dias?>">
              <label id="lbl_h12_dias" for="h12_dias"><?=@$Lh12_dias?></label>
            </td>
            <td>
              <?
              db_input('h12_dias',6,$Ih12_dias,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_reltot?>">
              <label id="lbl_h12_reltot" for="h12_reltot"><?=@$Lh12_reltot?></label>
            </td>
            <td>
              <?
              $x = array(
                0=>"0 - Nao Soma",
                1=>"1 - Tempo Municipal",
                2=>"2 - Tempo Empresa Privada",
                3=>"3 - Tempo Exercito Nacional",
                4=>"4 - Tempo Federal",
                5=>"5 - Tempo Estadual",
                6=>"6 - Tempo Municipal Averbado",
                9=>"9 - Tempo Convertido"
              );
              db_select('h12_reltot',$x,true,$db_opcao,"");
              ?>
            </td>
            <td nowrap title="<?=@$Th12_relgra?>">
              <label id="lbl_h12_relgra" for="h12_relgra"><?=@$Lh12_relgra?></label>
            </td>
            <td>
              <?
              $x = array("f"=>"Não","t"=>"Sim");
              db_select('h12_relgra',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_tipo?>">
              <label id="lbl_h12_tipo" for="h12_tipo"><?=@$Lh12_tipo?></label>
            </td>
            <td>
              <?
              $x = array(
                "A"=>"A - Afastamento",
                "S"=>"S - Assentamento"
              );
              db_select('h12_tipo',$x,true,$db_opcao,"");
              ?>
            </td>
            <td nowrap title="<?=@$Th12_graefe?>">
              <label id="lbl_h12_graefe" for="h12_graefe"><?=@$Lh12_graefe?></label>
            </td>
            <td>
              <?
              $x = array("f"=>"Não","t"=>"Sim");
              db_select('h12_graefe',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_efetiv?>">
              <label id="lbl_h12_efetiv" for="h12_efetiv"><?=@$Lh12_efetiv?></label>
            </td>
            <td>
              <?
              $x = array(
                "I"=>"I - Inicio",
                "F"=>"F - Fim",
                "+"=>"+ - Soma",
                "-"=>"- - Diminui",
                "N"=>"N - Desconsidera",
                "D"=>"D - Tempo Dobrado",
                "S"=>"S - Nao Soma Tempo"
              );
              db_select('h12_efetiv',$x,true,$db_opcao,"");
              ?>
            </td>
            <td nowrap title="<?=@$Th12_tipefe?>">
              <label id="lbl_h12_tipefe" for="h12_tipefe"><?=@$Lh12_tipefe?></label>
            </td>
            <td>
              <?
              $x = array(
                "I"=>"INSS",
                "P"=>"Instituição",
                "C"=>"Convertida"
              );
              db_select('h12_tipefe',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th12_regenc?>">
              <label id="lbl_h12_regenc" for="h12_regenc"><?=@$Lh12_regenc?></label>
            </td>
            <td>
              <?
              $x = array("f"=>"Não","t"=>"Sim");
              db_select('h12_regenc',$x,true,$db_opcao,"");
              ?>
            </td>
            <td nowrap title="<?=@$Th12_vinculaperiodoaquisitivo?>">
              <label id="lbl_h12_vinculaperiodoaquisitivo" for="h12_vinculaperiodoaquisitivo"><?=@$Lh12_vinculaperiodoaquisitivo?></label>
            </td>
            <td>
              <?
              $x = array("f"=>"Não","t"=>"Sim");
              db_select('h12_vinculaperiodoaquisitivo',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Th12_tiporeajuste; ?>">
              <label id="lbl_h12_tiporeajuste" for="h12_tiporeajuste"><?php echo $Lh12_tiporeajuste; ?></label>
            </td>
            <td>
              <?php
              $aOh12_tiporeajuste = array(
                "0" => "Nenhum",
                "1" => "Real",
                "2" => "Paridade"
              );

              db_select("h12_tiporeajuste", $aOh12_tiporeajuste, true, $db_opcao, "");
              ?>
            </td>

            <td nowrap title="<?php echo $Th12_natureza; ?>">
              <label id="lbl_h12_natureza" for="h12_natureza"><?php echo $Lh12_natureza; ?></label>
            </td>
            <td>
              <?php

              $oDaoNaturezaTipoAssentamento = new cl_naturezatipoassentamento();
              $sSqlNaturezaTipoAssentamento = $oDaoNaturezaTipoAssentamento->sql_query(null, "rh159_sequencial, rh159_descricao", null);
              $rsNaturezaTipoAssentamento   = db_query($sSqlNaturezaTipoAssentamento);
              $aNaturezasTipoassentamento   = array();

              if(is_resource($rsNaturezaTipoAssentamento) && pg_num_rows($rsNaturezaTipoAssentamento) > 0) {
                for ($iIndNatureza=0; $iIndNatureza < pg_num_rows($rsNaturezaTipoAssentamento); $iIndNatureza++) {
                  $iSequencialNatureza = db_utils::fieldsMemory($rsNaturezaTipoAssentamento, $iIndNatureza)->rh159_sequencial;
                  $sDescricaoNatureza  = db_utils::fieldsMemory($rsNaturezaTipoAssentamento, $iIndNatureza)->rh159_descricao;
                  $aNaturezasTipoassentamento[$iSequencialNatureza] = $sDescricaoNatureza;
                }
              }

              db_select("h12_natureza", $aNaturezasTipoassentamento, true, $db_opcao);
              ?>
            </td>
            <td style="display: none;">
              <input name="natureza_validacao" type="text" value="<?=$h12_natureza;?>" />
            </td>
          </tr>
        </table>

      </fieldset>

      <?php
      if ( !empty($h12_codigo) ) {

        $res_portariatipo = $clportariatipo->sql_record($clportariatipo->sql_query_file(null,"*","h30_tipoasse","h30_tipoasse = ".@$h12_codigo));

        if ($clportariatipo->numrows > 0){

          db_fieldsmemory($res_portariatipo,0);
          db_input("h30_sequencial",10,@$Ih30_sequencial,true,"hidden",3);
        }
      }

      $res_portariaenvolv = $clportariaenvolv->sql_record($clportariaenvolv->sql_query_file(@$h30_portariaenvolv,"h42_descr"));
      ?>

      <fieldset style="width:700px;text-align:right;">

        <legend align="left"><strong>Dados tipo de portaria</strong></legend>

        <table border="0">
          <tr>
            <td nowrap colspan="3" title="<?=@$Th30_portariaenvolv?>"><strong>
                <?
                db_ancora(@$Lh30_portariaenvolv,"js_pesquisa_h30_portariaenvolv(true)",$db_opcao);
                ?>
              </strong></td>
            <td nowrap>
              <?
              db_input("h30_portariaenvolv",10,@$Ih30_portariaenvolv,true,"text",$db_opcao,"onchange='js_pesquisa_h30_portariaenvolv(false);'");
              db_input("h42_descr",40,@$Ih42_descr,true,"text",3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="3">
              <?
              db_ancora(@$Lh37_modportariaindividual,"js_pesquisaModIndividual(true)",$db_opcao);
              ?>
            </td>
            <td>
              <?
              db_input("h37_modportariaindividual",10,@$Ih37_modportariaindividual,true,"text",$db_opcao,"onchange='js_pesquisaModIndividual(false);'");
              db_input("descrModIndividual",40,"",true,"text",3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="3">
              <?
              db_ancora($Lh38_modportariacoletiva,"js_pesquisaModColetiva(true)",$db_opcao);
              ?>
            </td>
            <td>
              <?
              db_input("h38_modportariacoletiva",10,@$Ih38_modportariacoletiva,true,"text",$db_opcao,"onchange='js_pesquisaModColetiva(false);'");
              db_input("descrModColetiva",40,"",true,"text",3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="3" title="<?=@$Th30_portariatipoato?>"><?=@$Lh30_portariatipoato?></td>
            <td nowrap>
              <?
              $res_portariatipoato = $clportariatipoato->sql_record($clportariatipoato->sql_query_file(null,"h41_sequencial,h41_descr"));
              db_selectrecord("h30_portariatipoato",$res_portariatipoato,true,$db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="3" title="<?=@$Th30_portariaproced?>"><?=@$Lh30_portariaproced?></td>
            <td nowrap>
              <?
              $res_portariaproced = $clportariaproced->sql_record($clportariaproced->sql_query_file(null,"h40_sequencial,h40_descr"));
              db_selectrecord("h30_portariaproced",$res_portariaproced,true,$db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="3" title="<?=@$Th30_amparolegal?>"><?=@$Lh30_amparolegal?></td>
            <td nowrap>
              <?
              db_textarea('h30_amparolegal',5,40,@$Ih30_amparolegal,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

    </fieldset>

  </center>

  <br />
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" onClick="return js_validaCampos();" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

  <?php if ($db_opcao != 1) : ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <?php endif; ?>
  <input name="lancarAtributos" type="button" id="lancarAtributos" value="Adicionar Campos" onclick="js_lancarAtributos();" />


</form>
<script type="text/javascript">

  /**
   * Validação para quando for selecionado a opção "Vincula Periodo aquisitivo" obrigar
   * o preenchimento do procedimento de portaria
   * @return boolean
   */
  function js_validaCampos(){

    if (document.form1.h12_vinculaperiodoaquisitivo.value == 't' && !document.form1.h30_portariaproced.value){

      alert(_M('recursoshumanos.rh.rec1_tipoassenta.procedimento_portaria'));
      return false;
    }

    if (document.form1.h12_vinculaperiodoaquisitivo.value == 't' && !document.form1.h30_portariaenvolv.value){

      alert(_M('recursoshumanos.rh.rec1_tipoassenta.portaria_envolvida'));
      return false;
    }

    if (document.form1.h12_vinculaperiodoaquisitivo.value == 't' && !document.form1.h30_portariatipoato.value){

      alert(_M('recursoshumanos.rh.rec1_tipoassenta.ato_portaria'));
      return false;
    }
    return true;
  }

  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoasse','func_tipoasse.php?funcao_js=parent.js_preenchepesquisa|h12_codigo','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_tipoasse.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($_SERVER["PHP_SELF"])."?chavepesquisa='+chave;";
    }
    ?>
  }
  function js_pesquisa_h30_portariaenvolv(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_portariaenvolv','func_portariaenvolv.php?funcao_js=parent.js_mostrah30_portariaenvolv1|h42_sequencial|h42_descr|h42_amparolegal','Pesquisa',true);
    }else{
      if(document.form1.h30_portariaenvolv.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_portariaenvolv','func_portariaenvolv.php?pesquisa_chave='+document.form1.h30_portariaenvolv.value+'&funcao_js=parent.js_mostrah30_portariaenvolv','Pesquisa',false);
      }else{
        document.form1.h30_portariaenvolv.value = '';
      }
    }
  }
  function js_mostrah30_portariaenvolv(chave1,erro,chave2,chave3){
    if(erro==true){
      document.form1.h30_portariaenvolv.value = '';
      document.form.h30_portariaenvolv.focus();
    } else {
      document.form1.h30_portariaenvolv.value = chave1;
      document.form1.h42_descr.value          = chave2;
      if (document.form1.h30_amparolegal.value == ""){
        document.form1.h30_amparolegal.value = chave3;
      }
    }
  }
  function js_mostrah30_portariaenvolv1(chave1,chave2,chave3){
    document.form1.h30_portariaenvolv.value = chave1;
    document.form1.h42_descr.value          = chave2;
    if (document.form1.h30_amparolegal.value == ""){
      document.form1.h30_amparolegal.value = chave3;
    }
    db_iframe_portariaenvolv.hide();
  }


  function js_pesquisaModIndividual(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?funcao_js=parent.js_mostraModIndividual1|db63_sequencial|db63_nomerelatorio','Pesquisa',true);
    }else{
      if(document.form1.h37_modportariaindividual.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?pesquisa_chave='+document.form1.h37_modportariaindividual.value+'&funcao_js=parent.js_mostraModIndividual','Pesquisa',false);
      }else{
        document.form1.descrModIndividual.value = '';
      }
    }
  }

  function js_mostraModIndividual(chave,erro){
    document.form1.descrModIndividual.value = chave;
    if(erro==true){
      document.form1.h37_modportariaindividual.focus();
      document.form1.h37_modportariaindividual.value = '';
    }
  }

  function js_mostraModIndividual1(chave1,chave2){
    document.form1.h37_modportariaindividual.value = chave1;
    document.form1.descrModIndividual.value    = chave2;
    db_iframe_db_relatorio.hide();
  }

  function js_pesquisaModColetiva(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?funcao_js=parent.js_mostraModColetiva1|db63_sequencial|db63_nomerelatorio','Pesquisa',true);
    }else{
      if(document.form1.h38_modportariacoletiva.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?pesquisa_chave='+document.form1.h38_modportariacoletiva.value+'&funcao_js=parent.js_mostraModColetiva','Pesquisa',false);
      }else{
        document.form1.descrModColetiva.value = '';
      }
    }
  }

  function js_mostraModColetiva(chave,erro){
    document.form1.descrModColetiva.value = chave;
    if(erro==true){
      document.form1.h38_modportariacoletiva.focus();
      document.form1.h38_modportariacoletiva.value = '';
    }
  }

  function js_mostraModColetiva1(chave1,chave2){
    document.form1.h38_modportariacoletiva.value = chave1;
    document.form1.descrModColetiva.value      = chave2;
    db_iframe_db_relatorio.hide();
  }

</script>
<script>

  /**
   * Metodo para lançar atributos para a atividade
   */
  function js_lancarAtributos() {

    //@todo refatorar esta verificação.
    <?php if (isset($lAssentamentoVinculado) && $lAssentamentoVinculado) { ?>
    alert("Este tipo de assentamento já possui assentamento vinculado,\nalterações nos campos dinâmicos podem ocasionar inconsistências\nde informações e erros nas fórmulas vinculadas.");
    <?php } ?>

    require_once("scripts/widgets/dbmessageBoard.widget.js");
    require_once("scripts/datagrid.widget.js");
    require_once("scripts/widgets/dbcomboBox.widget.js");
    require_once("scripts/widgets/dbtextField.widget.js");
    require_once("scripts/widgets/dbtextFieldData.widget.js");
    require_once("scripts/widgets/windowAux.widget.js");
    require_once("scripts/classes/DBViewCadastroAtributoDinamico.js");

    var iCodigoAttDinamico        = $('h79_db_cadattdinamico').value;
    var oCadastroAtributoDinamico = new DBViewCadastroAtributoDinamico();

    if (iCodigoAttDinamico == '') {
      oCadastroAtributoDinamico.newAttribute();
    } else {
      oCadastroAtributoDinamico.loadAttribute(iCodigoAttDinamico);
    }

    oCadastroAtributoDinamico.setSaveCallBackFunction(

      function (iRetornoCodigoAttDinamico) {
        $('h79_db_cadattdinamico').value = iRetornoCodigoAttDinamico;
        js_salvarVinculoAtributoTipoAssentamento(iRetornoCodigoAttDinamico);
      }
    );
  }

  function js_salvarVinculoAtributoTipoAssentamento(iRetornoCodigoAttDinamico) {
    AjaxRequest.create("rec1_tipoasse.RPC.php",
      {
        'exec'              : 'vincularTipoAssentamentoAtributoDinamico',
        'iAtributoDinamico' : iRetornoCodigoAttDinamico,
        'iTipoasse'         : $('h12_codigo').value
      },
      function(response, erro) {
        alert(response.sMessage.urlDecode());
        if(erro) {
          return;
        }
      }
    ).setMessage('Vinculando atributos dinâmicos...').execute();
  }

</script>
