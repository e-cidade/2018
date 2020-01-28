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

//MODULO: caixa
$clcaiparametro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
db_app::load("scripts.js");
db_app::load("prototype.js");

$oParametrosCaixa = new ParametroCaixa();
$tipo_transmissao = $oParametrosCaixa->getTipoTransmissaoPadrao();
$sConvenioBanco   = $oParametrosCaixa->getConvenioBanco();
?>
<form name="form1" method="post" action="">


  <fieldset style="margin-top: 20px; width: 750px;">
    <legend>
      <strong>Parâmetros Financeiro</strong>
    </legend>

    <table border="0" align='left'>

      <tr>
        <td nowrap title="<?=@$Tk29_boletimzerado?>">
          <?=@$Lk29_boletimzerado?>
        </td>
        <td>
          <?
          $x = array("f"=>"NAO","t"=>"SIM");
          db_select('k29_boletimzerado',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tk29_modslipnormal?>">
          <?=@$Lk29_modslipnormal?>
        </td>
        <td>
          <?
          $x = array('36'=>'Normal/2 partes','37'=>'Com assinaturas/1 parte');
          db_select('k29_modslipnormal',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tk29_modsliptransf?>">
          <?=@$Lk29_modsliptransf?>
        </td>
        <td>
          <?
          $x = array('36'=>'Normal/2 partes','37'=>'2 partes/com assinatura');
          db_select('k29_modsliptransf',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tk29_chqemitidonaoautent?>">
          <?=@$Lk29_chqemitidonaoautent?>
        </td>
        <td>
          <?
          db_inputdata('k29_chqemitidonaoautent',@$k29_chqemitidonaoautent_dia,@$k29_chqemitidonaoautent_mes,@$k29_chqemitidonaoautent_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tk29_saldoemitechq?>">
          <?=@$Lk29_saldoemitechq?>
        </td>
        <td>
          <?
          $x = array('1'=>'Sim','2'=>'Não');
          db_select('k29_saldoemitechq',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tk29_datasaldocontasextra?>">
          <?=@$Lk29_datasaldocontasextra?>
        </td>
        <td>
          <?
          db_inputdata('k29_datasaldocontasextra',
                       @$k29_datasaldocontasextra_dia,
                       @$k29_datasaldocontasextra_mes,@$k29_datasaldocontasextra_ano,
                       true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tk29_trazdatacheque?>">
          <?=@$Lk29_trazdatacheque?>
        </td>
        <td>
          <?
          $x = array('f'=>'Não','t'=>'Sim');
          db_select('k29_trazdatacheque',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tk29_contassemmovimento?>">
          <?=@$Lk29_contassemmovimento?>
        </td>
        <td>
          <?
          $x = array('f'=>'Não','t'=>'Sim');
          db_select('k29_contassemmovimento',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <?php
          db_ancora("<b>Recurso:</b>", "js_pesquisaRecurso(true)", 1);
          ?>
        </td>
        <td>
          <?php
          db_input("k29_orctiporecfundeb", 10, null, false, "text", 1, "onchange='js_pesquisaRecurso(false);'");
          db_input("sDescricaoRecurso", 40, null, true, "text", 3);
          ?>
        </td>
      </tr>
    </table>
    <br />
    <fieldset style="width: 97%; border-left: none; border-right: none; border-bottom: none;">
      <legend class="bold">Agenda de Pagamentos</legend>

      <table>
        <tr>
          <td nowrap title="<?=@$Tk29_chqduplicado?>" class="bold">
            Permitir cheques duplicados:
          </td>
          <td>
            <?
            $x = array("f"=>"NAO","t"=>"SIM");
            db_select('k29_chqduplicado',$x,true,$db_opcao,"");
            ?>
          </td>
        </tr>
        <tr>
          <td class="bold">
            Tipo de Transmissão:
          </td>
          <td>
            <?php
            $oDaoTipoTransmissao = new cl_empagetipotransmissao();
            $sSqlBuscaTipos      = $oDaoTipoTransmissao->sql_query_file(null, "*", 'e57_sequencial');
            $rsBuscaTipos        = $oDaoTipoTransmissao->sql_record($sSqlBuscaTipos);
            db_selectrecord("tipo_transmissao", $rsBuscaTipos, true, $db_opcao, "", "", "", "", "", 1);
            ?>
          </td>
        </tr>
        <tr>
          <td><label class="bold" for="convenio_banco">Convênio com Banco:</label></td>
          <td>
            <input size="8" maxlength="8" id="convenio_banco" name="convenio_banco" value="<?= $sConvenioBanco?>" class="field-size3" type="text" />
          </td>
        </tr>

      </table>
    </fieldset>

  </fieldset>

  <div style='margin-top:10px;'>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </div>

</form>
<script>

  $('k29_boletimzerado').style.width = '155px';
  $('k29_saldoemitechq').style.width = '155px';
  $('k29_trazdatacheque').style.width = '155px';
  $('k29_contassemmovimento').style.width = '155px';
  $('k29_chqduplicado').style.width = '130px';
  $('tipo_transmissao').style.width = '130px';

  function js_pesquisaRecurso(lMostraWindow) {

    if (lMostraWindow) {

      var sUrl = 'func_orctiporec.php?funcao_js=parent.js_preencheRecurso|o15_codigo|o15_descr';
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_recurso',sUrl,'Pesquisa',true);
    } else {
      if($("k29_orctiporecfundeb").value != ''){

        var sUrl = 'func_orctiporec.php?pesquisa_chave='+$("k29_orctiporecfundeb").value+'&funcao_js=parent.js_completaRecurso';
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_recurso',sUrl,'Pesquisa',false);
      } else {
        $("sDescricaoRecurso").value = '';
      }
    }
  }
  function js_preencheRecurso(iCodigoRecurso, sDescricaoRecurso) {

    $('k29_orctiporecfundeb').value = iCodigoRecurso;
    $('sDescricaoRecurso').value    = sDescricaoRecurso;
    db_iframe_recurso.hide();
  }

  function js_completaRecurso(sDescricaoRecurso, lErro) {

    if (!lErro) {
      $('sDescricaoRecurso').value = sDescricaoRecurso;
    } else {

      $('k29_orctiporecfundeb').value = '';
      $('sDescricaoRecurso').value    = sDescricaoRecurso;
    }
  }



  function js_pesquisak29_instit(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
    }else{
      if(document.form1.k29_instit.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.k29_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
      }else{
        document.form1.nomeinst.value = '';
      }
    }
  }
  function js_mostradb_config(chave,erro){
    document.form1.nomeinst.value = chave;
    if(erro==true){
      document.form1.k29_instit.focus();
      document.form1.k29_instit.value = '';
    }
  }
  function js_mostradb_config1(chave1,chave2){
    document.form1.k29_instit.value = chave1;
    document.form1.nomeinst.value = chave2;
    db_iframe_db_config.hide();
  }
  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_caiparametro','func_caiparametro.php?funcao_js=parent.js_preenchepesquisa|k29_instit','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_caiparametro.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
</script>