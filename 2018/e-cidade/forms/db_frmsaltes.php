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
$clsaltes->rotulo->label();
$oRotulo = new rotulocampo();
$oRotulo->label("k103_contrapartida");
$oRotulo->label("k109_contaextra");
$oRotulo->label("coddepto");
?>

<style>
  .fieldsetinterno {

    border:0px;
    border-top:2px groove white;
  }

  .fieldsetinterno table tr TD:FIRST-CHILD {

    white-space: nowrap;
  }
  select {
    width: 100%;
  }
  fieldset.fieldsetinterno table tr TD {
    white-space: nowrap;
  }
  legend {
    font-weight: bold;
  }
</style>


<form name="form1" method="post" action="">
  <center>
    <table border="0">
      <tr>
        <td>
          <fieldset>
            <legend><b>Cadastro de Contas </b></legend>
            <fieldset class="fieldsetinterno" ><legend>&nbsp;Identificação&nbsp;</legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$Tk13_descr?>">
                    <?
                    if ($db_opcao == "2"){
                      db_ancora(@$Lk13_descr,"js_contas();",3 );
                    } else {
                      db_ancora(@$Lk13_descr,"js_contas();",$db_opcao );
                    }
                    ?>
                  </td>
                  <td>
                    <?
                    db_input('k13_reduz',8,"",true,'text',3);
                    db_input('k13_descr',40,$Ik13_descr,true,'text',$db_opcao);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tk13_ident?>"><?=@$Lk13_ident?> </td>
                  <td><? db_input('k13_ident',15,$Ik13_ident,true,'text',$db_opcao,"")?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tk13_limite?>"> <?=@$Lk13_limite?> </td>
                  <td>
                    <?
                    @list($k13_limite_dia,$k13_limite_mes,$k13_limite_ano)= split("/",$k13_limite);
                    db_inputdata('k13_limite',@$k13_limite_dia,@$k13_limite_mes,@$k13_limite_ano,true,'text',$db_opcao,"");
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>

            <fieldset class="fieldsetinterno"><legend>&nbsp;Implantação do saldo&nbsp;</legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$Tk13_dtimplantacao?>"> <?=@$Lk13_dtimplantacao?> </td>
                  <td><?
                    @list($k13_dtimplantacao_dia,$k13_dtimplantacao_mes,$k13_dtimplantacao_ano)= split("/",$k13_dtimplantacao);
                    db_inputdata('k13_dtimplantacao',@$k13_dtimplantacao_dia,@$k13_dtimplantacao_mes,@$k13_dtimplantacao_ano,true,'text',$db_opcao,"");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tk13_saldo?>"><?=@$Lk13_saldo?> </td>
                  <td><?
                    if(isset($k13_saldo)&&$k13_saldo!="") {
                      $k13_saldo = str_replace(",",".",$k13_saldo);
                    }
                    db_input('k13_saldo',15,$Ik13_saldo,true,'text',$db_opcao,"onBlur=this.value=this.value.replace(',','.');")
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>

            <fieldset class="fieldsetinterno"><legend>&nbsp;Saldo Atualizado&nbsp;</legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$Tk13_datvlr?>"> <?=@$Lk13_datvlr?> </td>
                  <td><?
                    @list($k13_datvlr_dia,$k13_datvlr_mes,$k13_datvlr_ano)= split("/",$k13_datvlr);
                    db_inputdata('k13_datvlr',@$k13_datvlr_dia,@$k13_datvlr_mes,@$k13_datvlr_ano,true,'text', 3,"");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tk13_vlratu?>"><?=@$Lk13_vlratu?>
                  </td>
                  <td><? db_input('k13_vlratu',15,$Ik13_vlratu,true,'text',3,"") ?>
                  </td>
                </tr>
              </table>
            </fieldset>

            <fieldset class="fieldsetinterno"><legend>&nbsp;Outros dados&nbsp;</legend>
              <table>
                <tr>
                  <td>
                    <?
                    db_ancora(@$Lk103_contrapartida,"js_saltes(true);",$db_opcao );
                    ?>
                  </td>
                  <td>
                    <?
                    db_input('k103_contrapartida',8,$Ik103_contrapartida,true,'text',$db_opcao,"onchange='js_saltes(false)'");
                    db_input('k103_descr',40,$Ik13_descr,true,'text',3);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?
                    db_ancora(@$Lk109_contaextra,"js_saltes2(true);",$db_opcao );
                    ?>
                  </td>
                  <td>
                    <?
                    db_input('k109_saltesextra',8,$Ik109_contaextra,true,'text',$db_opcao,"onchange='js_saltes2(false)'");
                    db_input('k103_descrextra',40,$Ik13_descr,true,'text',3);
                    ?>
                  </td>
                </tr>

              </table>
            </fieldset>
            <!--[ExtensaoFiltroDespesa] Alteracao 1 -->

        </td>
      </tr>
    </table>
    </fieldset>
  </center>

  <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>

  /** [ExtensaoFiltroDespesa] Alteracao 2 --> */


  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_preenche|k13_conta&sem_filtro_departamento=1','Pesquisa',true);
  }
  function js_preenche(chave){
    db_iframe_saltes.hide();
    <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    ?>
  }
  function js_contas(){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes_contas001.php?funcao_js=parent.js_preenche_conta|c62_reduz|c60_descr','Pesquisa',true);
  }
  function js_preenche_conta(chave1,chave2){
    db_iframe_saltes.hide();
    document.form1.k13_reduz.value=chave1;
    document.form1.k13_descr.value=chave2.substring(0,40);
  }

  function js_saltes(lMostra){

    if (lMostra) {
      js_OpenJanelaIframe('top.corpo','db_iframe_contrapartida',
        'func_saltes.php?funcao_js=parent.js_contrapartida|k13_conta|k13_descr',
        'Pesquisa de Contas',true);
    } else {

      var iContrapartida = document.form1.k103_contrapartida.value;
      if (iContrapartida != '') {

        js_OpenJanelaIframe('top.corpo','db_iframe_contrapartida',
          'func_saltes.php?funcao_js=parent.js_contrapartida2&pesquisa_chave='+iContrapartida
          ,'Pesquisa de contas',false);
      } else {

        document.form1.k103_descr.value = '';
      }
    }
  }

  function js_contrapartida(chave1,chave2){

    db_iframe_contrapartida.hide();
    document.form1.k103_contrapartida.value = chave1;
    document.form1.k103_descr.value         = chave2;
  }

  function js_contrapartida2(sRetorno,lErro){

    db_iframe_contrapartida.hide();
    if (!lErro) {
      document.form1.k103_descr.value = sRetorno;
    } else {

      document.form1.k103_descr.value         = sRetorno;
      document.form1.k103_contrapartida.value = '';
    }
  }

  function js_saltes2(lMostra){

    if (lMostra) {
      js_OpenJanelaIframe('top.corpo','db_iframe_contrapartida',
        'func_saltes.php?funcao_js=parent.js_saltesextra|k13_conta|k13_descr',
        'Pesquisa de Contas',true);
    } else {

      var iContrapartida = document.form1.k109_saltesextra.value;
      if (iContrapartida != '') {

        js_OpenJanelaIframe('top.corpo','db_iframe_contrapartida',
          'func_saltes.php?funcao_js=parent.js_saltesextra2&pesquisa_chave='+iContrapartida
          ,'Pesquisa de contas',false);
      } else {

        document.form1.k103_descrextra.value = '';
      }
    }
  }

  function js_mostradepartamento() {

    var sNomeDepartamento   = arguments[1];
    var iCodigoDepartamento = arguments[0];

    if (typeof(arguments[1]) == 'boolean') {

      iCodigoDepartamento = document.getElementById('departamento').value;

      sNomeDepartamento = arguments[0];
      if (arguments[1]) {
        iCodigoDepartamento = '';
        document.getElementById('departamento').focus();
      }
    }

    document.getElementById('departamento').value = iCodigoDepartamento;
    document.getElementById('descrdepto').value   = sNomeDepartamento;
    db_iframe_db_depart.hide();
  }



  function js_saltesextra(chave1,chave2){

    db_iframe_contrapartida.hide();
    document.form1.k109_saltesextra.value = chave1;
    document.form1.k103_descrextra.value  = chave2;
  }

  function js_saltesextra2(sRetorno,lErro){

    db_iframe_contrapartida.hide();
    if (!lErro) {
      document.form1.k103_descrextra.value = sRetorno;
    } else {

      document.form1.k103_descrextra.value  = sRetorno;
      document.form1.k109_saltesextra.value = '';
    }
  }


  function setLabelWidth() {

    var aRows = $$('.fieldsetinterno table td:first-child');
    var iMaxHeigth = 0;
    aRows.each(function(oLinha, id) {

      if (oLinha.scrollWidth > iMaxHeigth) {
        iMaxHeigth = oLinha.scrollWidth;
      }
    });

    aRows.each(function(oLinha, id) {

      oLinha.style.width = iMaxHeigth;
    });
  }
  setLabelWidth();
</script>