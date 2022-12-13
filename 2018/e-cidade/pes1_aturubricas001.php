<?
/*
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_gerfcom_classe.php");
$db_opcao= 1;

$clgerfcom = new cl_gerfcom;
$clrotulo  = new rotulocampo;

$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');

db_utils::postMemory($_POST);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("datagrid.widget.js");
      db_app::load("estilos.css");
      db_app::load("grid.style.css");
      db_app::load("dbtextField.widget.js");
      db_app::load("dbcomboBox.widget.js");
      db_app::load("arrays.js");
      db_app::load("classes/DBViewTipoFiltrosFolha.js");
    ?>

    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

      <form name="form1" id="form1" class="container" style="width: 500px" action="pes1_aturubricas002.php" method="POST">
        <fieldset>
          <legend>Atualização de Rubricas</legend>

          <table class="form-container">

            <tr>
              <td colspan="2">
                <fieldset class="separator">
                  <legend><strong>Tipo de Ponto: </strong></legend>
                    <div id="containerTipoPonto"></div>

                    <input type="hidden" name="fx" id="fx" value="" />
                    <input type="hidden" name="fs" id="fs" value="" />
                    <input type="hidden" name="fc" id="fc" value="" />
                    <input type="hidden" name="f3" id="f3" value="" />
                    <input type="hidden" name="fa" id="fa" value="" />
                    <input type="hidden" name="valores_campos_rel" id="valores_campos_rel" value="" />
                  </fieldset>
                </td>
              </tr>
            </table>

            <br/>

            <table class="form-container" style="width: 100%">

            <tr title="Seleção">
              <td>
                <?php
                  db_ancora("Seleção:", "js_pesquisaSelecao(true)", 1);
                ?>
              </td>
              <td>
                <?php
                  db_input('r44_selec', 8,  1, true, 'text', "",'class="field-size2" onchange="js_pesquisaSelecao(false)"');
                  db_input('r44_des',   30, "", true, 'text', 3,'class="field-size7"');
                ?>
              </td>
            </tr>

             <tr>
              <td title="<?=$Trh27_rubric?>">
                <?
                db_ancora(@$Lrh27_rubric, "js_pesquisarrubric(true);", 1);
                ?>
              </td>
              <td> <input type="hidden" name="rubrica" id="rubrica" value="" />
                <?
                db_input('rh27_rubric', 8, $Irh27_rubric, true, 'text', 1, 'class="field-size2" onchange="js_pesquisarrubric(false);"');
                db_input('rh27_descr', 30, $Irh27_descr,  true, 'text', 3, 'class="field-size7"');
                ?>
              </td>
            </tr>

          <tr id="operacao" align="left">
            <td ><strong>Operação:</strong>
            </td>
            <td>
              <?
                $aOpcaoOperacao = array(""=>"Selecione",
                                        "i"=>"Inclusão",
                                        "a"=>"Alteração",
                                        "e"=>"Exclusão"
                                       );
                db_select("iae",$aOpcaoOperacao,true,1,'class="field-size5"');
              ?>
            </td>
          </tr>

          </table>

          </fieldset>
        <p align="center">
          <input  name="processar" id="processar" type="button" value="Processar" onclick="js_processar();" >
        </p>
      </form>
    <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
  </html>
  <script src="scripts/classes/DBViewFormularioFolha/ComboRegime.js"      type="text/javascript"></script>
  <script src="scripts/classes/DBViewFormularioFolha/ComboPrevidencia.js" type="text/javascript"></script>
  <script src="scripts/classes/DBViewFormularioFolha/ComboVinculo.js"     type="text/javascript"></script>
  <script>
    <?php if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      echo 'aDadosTipoPonto = [
          {folha: "fx", descricao: "Fixo"},
          {folha: "f3", descricao: "13o. Salário"},
          {folha: "fa", descricao: "Adiantamento"}
        ];';

      if (FolhaPagamentoSalario::hasFolhaAberta()) {
        echo 'aDadosTipoPonto.push({folha: "fs", descricao: "Salário"});';
      } 
      
      if (FolhaPagamentoComplementar::hasFolhaAberta()) {
          echo 'aDadosTipoPonto.push({folha: "fc", descricao: "Complementar"});';
      }
      
      if (FolhaPagamentoSuplementar::hasFolhaAberta()) {
        echo 'aDadosTipoPonto.push({folha: "fs", descricao: "Suplementar"});';
      }

    } else {
      echo 'aDadosTipoPonto = [
          {folha: "fs", descricao: "Salário / Suplementar"},
          {folha: "fc", descricao: "Complementar"},
          {folha: "fx", descricao: "Fixo"},
          {folha: "f3", descricao: "13o. Salário"},
          {folha: "fa", descricao: "Adiantamento"},
        ];';
    }
    ?>
  </script>
  <script>

    (function() {

      $('form1').reset();

      /**
       * Monta a Grid com os Tipos de Folha
       */
      oGridTipoPonto              = new DBGrid('gridTipoFolha');
      oGridTipoPonto.nameInstance = "oGridTipoPonto";
      oGridTipoPonto.setCheckbox(0);
      oGridTipoPonto.setCellWidth(new Array( '0', '100%'));
      oGridTipoPonto.setCellAlign(new Array( 'left', 'left'));
      oGridTipoPonto.setHeader(new Array( 'Folha','Nome' ));
      oGridTipoPonto.aHeaders[1].lDisplayed = false;
      oGridTipoPonto.show($('containerTipoPonto'));
      oGridTipoPonto.clearAll(true);

      aDadosTipoPonto.each (function(oTipoPonto, iIndiceTipoFolha) {
        oGridTipoPonto.addRow([oTipoPonto.folha, oTipoPonto.descricao]);
      });

      oGridTipoPonto.renderRows();
    })();

  function js_processar() {

    var MENSAGEM    = 'recursoshumanos/pessoal/pes1_aturubricas001.';

    if ($F('iae') == '') {

      alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: 'Operação'}) );
      return false;
    }

    if ($F('rh27_rubric') == '' || $F('rh27_descr') == '') {

      alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: 'Código da Rubrica'}) );
      return false;
    }

    /**
     * Valida se tem pelo menos um ponto selecionado
     */
     var aFolhasSelecionadas = oGridTipoPonto.getSelection();
     if (aFolhasSelecionadas.length == 0) {

       alert( _M( MENSAGEM + 'selecao'));
       return false;
     }

    /**
     * Setamos os valores do select
     * @type {Array}
     */
     var aTipoPontos = [];
     var sPontos     = '';
     var iContador   = 1;
     aFolhasSelecionadas.each (function(oTipoFolha, iIndice) {

       $(oTipoFolha[0]).value = 'true';
       var sSeparador         = ',';

       if(aFolhasSelecionadas.length == iContador){
         sSeparador = '';
       }
        sPontos  += oTipoFolha[0] + sSeparador ;
        iContador++;
     })

     $('valores_campos_rel').value = sPontos;
     $('form1').submit();
  }

  function js_pesquisarrubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
     if(document.form1.rh27_rubric.value != ''){
       quantcaracteres = document.form1.rh27_rubric.value.length;
       for(i=quantcaracteres;i<4;i++){
         document.form1.rh27_rubric.value = "0"+document.form1.rh27_rubric.value;
       }
       js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh27_rubric.value+'&funcao_js=parent.js_mostrarubricas','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = '';
     }
  }
}
function js_mostrarubricas(chave,erro){
  document.form1.rubrica.value     = chave;
  document.form1.rh27_descr.value  = chave;
  if(erro==true){
    document.form1.rh27_rubric.value = '';
    document.form1.rh27_rubric.focus();
  }
}
function js_mostrarubricas1(chave1,chave2){
  document.form1.rubrica.value     = chave1;
  document.form1.rh27_rubric.value = chave1;
  document.form1.rh27_descr.value  = chave2;
  db_iframe_rhrubricas.hide();
}

var oTiposFiltrosFolha;

/**
 * Realiza a busca de seleções retornando o código e descrição da rubrica escolhida;
 */
function js_pesquisaSelecao(lMostra) {

  if ( lMostra ) {
    js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_geraform_mostraselecao1|r44_selec|r44_descr&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
  } else {
    if ( $F(r44_selec) != "" ) {
      js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave=' + $F(r44_selec) + '&funcao_js=parent.js_geraform_mostraselecao&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false);
    } else {
      $(r44_des).setValue("");
    }
  }
}

/**
* Trata o retorno da função js_pesquisaSelecao().
*/
function js_geraform_mostraselecao(sDescricao, lErro) {

  if ( lErro ) {

    $(r44_selec).setValue('');
    $(r44_selec).focus();
  }

  $(r44_des).setValue(sDescricao);
}

/**
* Trata o retorno da função js_pesquisaSelecao();
*/
function js_geraform_mostraselecao1(sChave1, sChave2) {

  $(r44_selec).setValue(sChave1);

  if( $(r44_des) ) {
    $(r44_des).setValue(sChave2);
  }

  db_iframe_selecao.hide();
  }
</script>
