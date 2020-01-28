<?php
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
$l20_dtpublic_dia = empty($l20_dtpublic_dia) ? '' : $l20_dtpublic_dia;
$l20_dtpublic_mes = empty($l20_dtpublic_mes) ? '' : $l20_dtpublic_mes;
$l20_dtpublic_ano = empty($l20_dtpublic_ano) ? '' : $l20_dtpublic_ano;
$l20_dataaber_dia = empty($l20_dataaber_dia) ? '' : $l20_dataaber_dia;
$l20_dataaber_mes = empty($l20_dataaber_mes) ? '' : $l20_dataaber_mes;
$l20_dataaber_ano = empty($l20_dataaber_ano) ? '' : $l20_dataaber_ano;


$clliclicita->rotulo->label();
$clrotulo = new rotulocampo;

$clrotulo->label("pc50_descr");
$clrotulo->label("l34_protprocesso");
$clrotulo->label("nome");
$clrotulo->label("l03_usaregistropreco");
$clrotulo->label("p58_numero");

require_once modification("std/db_stdClass.php");

if ($db_opcao == 1) {

	/*
	 * verifica na tabela licitaparam se deve utilizar processo do sistema
	 */
  $oParamLicicita = db_stdClass::getParametro('licitaparam', array(db_getsession("DB_instit")));

  if(isset($oParamLicicita[0]->l12_escolheprotocolo) && $oParamLicicita[0]->l12_escolheprotocolo == 't') {
  	$lprocsis = 's';
  } else {
  	$lprocsis = 'n';
  }

  /*
   * verifica se existe apenas 1 cl_liclocal
   */
  $oLicLocal = new cl_liclocal();
  $rsLicLocal = $oLicLocal->sql_record($oLicLocal->sql_query_file());
  if( $oLicLocal->numrows == 1 ) {
  	db_fieldsmemory($rsLicLocal,0);
  	$l20_liclocal = $l26_codigo;
  }

  /*
   * verifica se existe apenas 1 cl_liccomissao
   */
  $oLicComissao = new cl_liccomissao();
  $rsLicComissao = db_query($oLicComissao->sql_query_file());
  if( pg_num_rows($rsLicComissao) == 1 ) {
  	db_fieldsmemory($rsLicComissao,0);
  	$l20_liccomissao = $l30_codigo;
  }

}

$lBloqueadoRegistroPreco = (empty($itens_lancados) ? $db_opcao : 3);
?>

<style type="text/css">
  fieldset table tr td:first-child {
  	width: 180px;
  	white-space: nowrap
  }
</style>
<div class="container">
  <form name="form1" method="post" action="">

    <input type="hidden" value="<?php echo !empty($modalidadeAnterior) ? $modalidadeAnterior : ''?>" id="modalidadeAnterior" name="modalidadeAnterior" readonly/>

    <fieldset>
      <legend>Licitação</legend>

      <fieldset style="border:0px;">

        <table>
         <tr>
           <td nowrap title="<?=$Tl20_codigo?>">
             <label for="l20_codigo">
                <?=$Ll20_codigo?>
              </label>
           </td>
           <td>
             <?php
               db_input('l20_codigo',10,$Il20_codigo,true,'text',3,"");
               if ($db_opcao == 1 || $db_opcao == 11){
                  $l20_correto = 'f';
               }
               db_input("l20_correto",1,"",true,"hidden",3);
               if ($db_botao == false && !empty($l20_correto) && $l20_correto == 't'){
             ?>
            &nbsp;&nbsp;<font color="#FF0000"><b>Licitação já julgada</b></font>
             <?php
               }
             ?>
           </td>
         </tr>
         <tr>
           <td nowrap title="<?=$Tl20_edital?>">
             <label for="l20_edital">
               <?=$Ll20_edital?>
             </label>
           </td>
           <td>
             <?php
               db_input('l20_edital',10,$Il20_edital,true,'text',3,"");
             ?>
           </td>
         </tr>
         <tr>
            <td nowrap title="<?=$Tl20_codtipocom?>">
              <label for="l20_codtipocom" class="bold">

               <?php db_ancora("Modalidade :","js_pesquisal20_codtipocom(true);",3); ?>
              </label>
            </td>
            <td>
              <?php
                $result_tipo=$clcflicita->sql_record($clcflicita->sql_query_numeracao(null,"l03_codigo,l03_descr", null, "l03_instit = " . db_getsession("DB_instit")));
                if ($clcflicita->numrows==0){
        		      db_msgbox("Nenhuma Modalidade cadastrada!!");
        		      $result_tipo="";
        		      $db_opcao=3;
        		      $db_botao = false;
        		      db_input("l20_codtipocom",10,"",true,"text");
        		      db_input("l20_codtipocom",40,"",true,"text");
                } else {
                  db_selectrecord("l20_codtipocom",$result_tipo,true,$db_opcao,"js_mostraRegistroPreco()");
                  if (isset($l20_codtipocom)&&$l20_codtipocom!=""){
                    echo "<script>document.form1.l20_codtipocom.selected=$l20_codtipocom;</script>";
                  }
                }
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tl20_numero?>">
              <label for="l20_numero"><?=$Ll20_numero?></label>
            </td>
            <td>
                <?php
                  db_input('l20_numero',10,$Il20_numero,true,'text',3,"");
                ?>
           </td>
         </tr>

         <tr>
            <td nowrap title="<?=$Tl20_id_usucria?>">
              <label for="l20_id_usucria">
                <?php db_ancora($Ll20_id_usucria,"js_pesquisal20_id_usucria(true);",3); ?>
              </label>
            </td>
            <td>
              <?php
                $usuario=db_getsession("DB_id_usuario");
                $result_usuario=$cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($usuario));
                if ($cldb_usuarios->numrows>0){
                  	db_fieldsmemory($result_usuario,0);
                }
                $l20_id_usucria=$id_usuario;
                db_input('l20_id_usucria',10,$Il20_id_usucria,true,'text',3," onchange='js_pesquisal20_id_usucria(false);'")
              ?>
              <?
               db_input('nome',45,$Inome,true,'text',3,'')
              ?>
           </td>
         </tr>
        </table>
      </fieldset>

      <fieldset class="separator">
        <legend>Datas</legend>
        <table>
          <tr>
            <td nowrap title="<?=$Tl20_datacria?>">
              <label for="l20_datacria"><?=$Ll20_datacria?></label>
            </td>
            <td>
               <?php
                 if(!isset($l20_datacria)) {
                   $l20_datacria_dia=date('d',db_getsession("DB_datausu"));
                   $l20_datacria_mes=date('m',db_getsession("DB_datausu"));
                   $l20_datacria_ano=date('Y',db_getsession("DB_datausu"));
                 }
                 db_inputdata("l20_datacria",$l20_datacria_dia,$l20_datacria_mes,$l20_datacria_ano,true,'text',$db_opcao);
               ?>
            </td>
            <td>
              <label for="l20_horacria"><?=$Ll20_horacria?></label>
            </td>
            <td>
               <?php
                 if ($db_opcao == 1 || $db_opcao == 11){
                     $l20_horacria=db_hora();
                 }
                 db_input('l20_horacria',5,$Il20_horacria,true,'text',$db_opcao,"");
               ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=$Tl20_dtpublic?>">
              <label for="l20_dtpublic"><?=$Ll20_dtpublic?></label>
            </td>
            <td colspan="2">
              <?php
                db_inputdata('l20_dtpublic',$l20_dtpublic_dia,$l20_dtpublic_mes,$l20_dtpublic_ano,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>

         <tr>
            <td nowrap title="<?=$Tl20_dataaber?>">
              <label for="l20_dataaber"><?=$Ll20_dataaber?></label>
            </td>
            <td>
              <?php
                db_inputdata('l20_dataaber',$l20_dataaber_dia,$l20_dataaber_mes,$l20_dataaber_ano,true,'text', 2,"");
              ?>
            </td>
            <td>
              <label for="l20_horaaber"><?=$Ll20_horaaber?></label>
            </td>
            <td>
              <?php
                db_input('l20_horaaber',5,$Il20_horaaber,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset class="separator">
        <legend>Outras Informações</legend>

        <table>
          <tr>
            <td nowrap title="<?=$Tl20_local?>">
              <label for="l20_local"><?=$Ll20_local?></label>
            </td>
            <td>
              <?php
                db_textarea('l20_local',0,57,$Il20_local,true,'text',$db_opcao," rel='ignore-css' ")
              ?>
            </td>

          <tr>
            <td nowrap title="<?=$Tl20_objeto?>">
              <label for="l20_objeto"><?=$Ll20_objeto?></label>
            </td>
            <td>
              <?php
                db_textarea('l20_objeto',0,57,$Il20_objeto,true,'text',$db_opcao," rel='ignore-css' ")
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=$Tl20_localentrega?>">
              <label for="l20_localentrega"><?=$Ll20_localentrega?></label>
            </td>
            <td>
              <?php
                db_textarea('l20_localentrega',0,57,$Il20_localentrega,true,'text',$db_opcao," rel='ignore-css' ")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tl20_prazoentrega?>">
              <label for="l20_prazoentrega"><?=$Ll20_prazoentrega?></label>
            </td>
            <td>
              <?php
                db_textarea('l20_prazoentrega',0,57,$Il20_prazoentrega,true,'text',$db_opcao," rel='ignore-css' ")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tl20_condicoespag?>">
              <label for="l20_condicoespag"><?=$Ll20_condicoespag?></label>
            </td>
            <td>
              <?php
                db_textarea('l20_condicoespag',0,57,$Il20_condicoespag,true,'text',$db_opcao," rel='ignore-css' ")
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=$Tl20_validadeproposta?>">
              <label for="l20_validadeproposta"><?=$Ll20_validadeproposta?></label>
            </td>
            <td>
              <?php
                db_textarea('l20_validadeproposta',0,57,$Il20_validadeproposta,true,'text',$db_opcao," rel='ignore-css' ")
              ?>
            </td>
          </tr>
          <tr id="trTipoJulgamento">
            <td nowrap title="<?=$Tl20_tipojulg?>">
               <label for="l20_tipojulg"><?=$Ll20_tipojulg?></label>
            </td>
            <td>
              <?php
                $arr_tipo = array("1"=>"Por item","2"=>"Global","3"=>"Por lote");
                db_select("l20_tipojulg",$arr_tipo,true, $lBloqueadoRegistroPreco);
                db_input("tipojulg",1,"",true,"hidden",3,"");
                db_input("confirmado",1,"",true,"hidden",3,"");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=$Tl20_liclocal?>">
              <label for="l20_liclocal">
                <?php db_ancora($Ll20_liclocal,"js_pesquisal20_liclocal(true);",$db_opcao); ?>
              </label>
            </td>
            <td>
              <?php
                db_input('l20_liclocal',10,$Il20_liclocal,true,'text',$db_opcao," onchange='js_pesquisal20_liclocal(false);'")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tl20_liccomissao?>">
              <label for="l20_liccomissao">
                <?php db_ancora($Ll20_liccomissao,"js_pesquisal20_liccomissao(true);",$db_opcao); ?>
              </label>
            </td>
            <td>
              <?php
                db_input('l20_liccomissao',10,$Il20_liccomissao,true,'text',$db_opcao," onchange='js_pesquisal20_liccomissao(false);'")
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold" for="lprocsis">Processo do Sistema:</label>
            </td>
            <td>
              <?php
                 $aProcSistema = array(
                    "s" => "Sim",
                    "n" => "Não"
                  );

                 db_select('lprocsis', $aProcSistema, true, $db_opcao_editavel);
              ?>
            </td>
          </tr>

          <tr id="procAdm" style="display:none">
            <td nowrap title="<?php echo $Tl20_procadmin; ?>">
              <label for="l20_procadmin">
                <?php echo $Ll20_procadmin; ?>
              </label>
            </td>
            <td>
              <?php db_input('l20_procadmin', 30, $Il20_procadmin, true, 'text', $db_opcao_editavel, " placeholder='Número/Ano' "); ?>
            </td>
          </tr>

          <tr id="procSis">
            <td nowrap title="<?php echo $Tl34_protprocesso; ?>">
              <?php db_ancora($Ll34_protprocesso,"js_pesquisal34_protprocesso(true);",$db_opcao_editavel); ?>
            </td>
            <td>
              <?php
                db_input('p58_numero', 15, $Ip58_numero, true, 'text', $db_opcao_editavel,"onChange='js_pesquisal34_protprocesso(false);'");
                db_input('l34_protprocesso', 15, $Il34_protprocesso, true, 'hidden', $db_opcao_editavel);
                db_input('l34_protprocessodescr',40,"",  true,'text',3,"");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=$Tl03_usaregistropreco?>">
              <label for="l03_usaregistropreco"><?=$Ll03_usaregistropreco?></label>
            </td>
            <td>
              <?php
              if (!isset($l20_usaregistropreco)) {
                $l20_usaregistropreco = "f";
              }

              db_select("l20_usaregistropreco",array("t"=>"Sim", "f"=>"Não"),true,$lBloqueadoRegistroPreco, "onchange='mostrarFormaControleRegistroPreco()'");
              ?>
            </td>
          </tr>
          <tr id="formacontraleregistropreco" style="'display:none">
            <td nowrap title="<?=$Tl20_formacontroleregistropreco?>">
               <label for="l20_formacontroleregistropreco"><?=$Ll20_formacontroleregistropreco?></label>
            </td>
            <td>
              <?php
                if (!isset($ll20_formacontroleregistropreco)) {
                  $ll20_formacontroleregistropreco = "1";
                }
                db_select("l20_formacontroleregistropreco", array("1"=>"Por Quantidade", "2" => "Por Valor"), true, $lBloqueadoRegistroPreco, "onchange='verificaTipoJulgamento()'");
              ?>
            </td>
          </tr>

          <tr >
            <td nowrap>
               <label for="l20_tipo"><?=$Ll20_tipo?></label>
            </td>
            <td>
              <?php
                $a = array("1"=>"Gera despesa","2"=>"Não gera despesa");
                db_select("l20_tipo", $a, true, $lBloqueadoRegistroPreco); // Se ja tem itens não pode alterar
              ?>
            </td>
          </tr>

        </table>
      </fieldset>

    </fieldset>

    <input name="<?=($db_opcao_editavel==1?'incluir':($db_opcao_editavel==2||$db_opcao_editavel==22?'alterar':'excluir'))?>" type="submit" id="db_opcao"
           value="<?=($db_opcao_editavel==1?'Incluir':($db_opcao_editavel==2||$db_opcao_editavel==22?'Alterar':'Excluir'))?>"
           <?=($db_botao==false?'disabled':'') ?>  onClick="return js_confirmadatas()">
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</div>
<script type="text/javascript">

  var sUrl = "lic4_licitacao.RPC.php";

  /**
   * Processo Administrativo
   */
  $("l20_procadmin").addEventListener("input", function() {
    this.value = this.value.replace(/[^0-9\/]/g, '').replace(/(\/?)([0-9]*)(\/?)([0-9]{0,4})(.*)(\/?)/, '$2$3$4')
  })

  $("lprocsis").addEventListener("change", function() {

    if ( this.value == 's') {
      $("procSis").show();
      $("procAdm").hide();
    } else {
      $("procSis").hide();
      $("procAdm").show();
    }
  });

  if ($("l34_protprocesso").value != '') {
    $("lprocsis").value = 's';
  }

  var oEvento = new Event("change")
  $("lprocsis").dispatchEvent(oEvento)

var oTipoJulgamento = $('trTipoJulgamento');

function verificaTipoJulgamento() {

  oTipoJulgamento.style.display = '';
  if ($F('l20_formacontroleregistropreco') == "2") {
    $('l20_tipojulg').value = "1";
    oTipoJulgamento.style.display = 'none';
  }
}

$('l20_codtipocom').observe('change', function () {

  js_verificaModalidade();
});
$('l20_codtipocomdescr').observe('change', function () {

  js_verificaModalidade();
});
function js_verificaModalidade() {

  js_divCarregando("Aguarde, pesquisando dados da modalidade.","msgBox");
  var oParam            = new Object();
  oParam.exec           = "verificaModalidade";
  oParam.iModalidade    = $F('l20_codtipocom');

  var oAjax           = new Ajax.Request(sUrl,
                                         {
                                         method: "post",
                                         parameters:'json='+Object.toJSON(oParam),
                                         onComplete: js_retornoVerificaModalidade
                                        });

}
function js_retornoVerificaModalidade(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  $("l20_usaregistropreco").options.length = 0;
  if (oRetorno.l03_usaregistropreco == 't') {
  //true pode por sim nao no campo l20_usaregistropreco

    $("l20_usaregistropreco").options[0] = new Option("Não", "f");
    $("l20_usaregistropreco").options[1] = new Option("Sim", "t");
  } else {
    // false somentenao
    $("l20_usaregistropreco").options[0] = new Option("Não", "f");
  }
}

function js_pesquisal20_codtipocom(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pctipocompra','func_pctipocompra.php?funcao_js=parent.js_mostrapctipocompra1|pc50_codcom|pc50_descr','Pesquisa',true,0);
  }else{
     if(document.form1.l20_codtipocom.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pctipocompra','func_pctipocompra.php?pesquisa_chave='+document.form1.l20_codtipocom.value+'&funcao_js=parent.js_mostrapctipocompra','Pesquisa',false);
     }else{
       document.form1.pc50_descr.value = '';
     }
  }
}
function js_mostrapctipocompra(chave,erro){
  document.form1.pc50_descr.value = chave;
  if(erro==true){
    document.form1.l20_codtipocom.focus();
    document.form1.l20_codtipocom.value = '';
  }
}
function js_mostrapctipocompra1(chave1,chave2){
  document.form1.l20_codtipocom.value = chave1;
  document.form1.pc50_descr.value = chave2;
  db_iframe_pctipocompra.hide();
}
function js_pesquisal20_id_usucria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,0);
  }else{
     if(document.form1.l20_id_usucria.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.l20_id_usucria.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.l20_id_usucria.focus();
    document.form1.l20_id_usucria.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.l20_id_usucria.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  <?php if ($db_opcao_editavel == 2) : ?>
  js_OpenJanelaIframe('','db_iframe_liclicita','func_liclicita.php?funcao_js=parent.js_preenchepesquisa|l20_codigo','Pesquisa',true,"0");
  <?php else : ?>
  js_OpenJanelaIframe('','db_iframe_liclicita','func_liclicita.php?tipo=1&funcao_js=parent.js_preenchepesquisa|l20_codigo','Pesquisa',true,"0");
  <?php endif ?>
}
function js_preenchepesquisa(chave){
  db_iframe_liclicita.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
    ?>
   parent.iframe_liclicitem.location.href='lic1_liclicitemalt001.php?licitacao='+chave;
   <?
  }
  ?>
}
function js_pesquisal20_liclocal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_local','func_liclocal.php?funcao_js=parent.js_mostralocal1|l26_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_liclocal.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_local','func_liclocal.php?pesquisa_chave='+document.form1.l20_liclocal.value+'&funcao_js=parent.js_mostralocal','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostralocal(chave,erro){
  if(erro==true){
    document.form1.l20_liclocal.focus();
    document.form1.l20_liclocal.value = '';
  }
}
function js_mostralocal1(chave1){
  document.form1.l20_liclocal.value = chave1;
  db_iframe_local.hide();
}
function js_pesquisal20_liccomissao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_comissao','func_liccomissao.php?funcao_js=parent.js_mostracomissao1|l30_codigo','Pesquisa',true,"0");
  }else{
     if(document.form1.l20_liccomissao.value != ''){
        js_OpenJanelaIfrasme('(window.CurrentWindow || parent.CurrentWindow).corpo','db_iframe_comissao','func_liccomissao.php?pesquisa_chave='+document.form1.l20_liccomissao.value+'&funcao_js=parent.js_mostracomissao','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostracomissao(chave,erro){
  if(erro==true){
    document.form1.l20_liccomissao.focus();
    document.form1.l20_liccomissao.value = '';
  }
}
function js_mostracomissao1(chave1){
  document.form1.l20_liccomissao.value = chave1;
  db_iframe_comissao.hide();
}

function js_pesquisal34_protprocesso(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso_protocolo.php?funcao_js=parent.js_mostraprocesso1|p58_numero|dl_código_do_processo|dl_nome_ou_razão_social','Pesquisa',true,"0");
  } else {

    if(document.form1.p58_numero.value != ''){
      js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso_protocolo.php?pesquisa_chave='+document.form1.p58_numero.value+'&funcao_js=parent.js_mostraprocesso&sCampoRetorno=p58_codproc','Pesquisa',false);
    } else {
      document.form1.l34_protprocessodescr.value = '';
    }
  }
}

function js_mostraprocesso(iCodigoProcesso, sNome, lErro){

  document.form1.l34_protprocessodescr.value = sNome;

  if ( lErro ){

    document.form1.p58_numero.focus();
    document.form1.p58_numero.value = '';
    document.form1.l34_protprocesso.value = '';
    return false;
  }

  document.form1.l34_protprocesso.value = iCodigoProcesso;

  db_iframe_proc.hide();
}

function js_mostraprocesso1(iNumeroProcesso, iCodigoProcesso, sNome) {

  document.form1.p58_numero.value            = iNumeroProcesso;
  document.form1.l34_protprocesso.value      = iCodigoProcesso;
  document.form1.l34_protprocessodescr.value = sNome;
  db_iframe_proc.hide();
}

var sUrl = "lic4_licitacao.RPC.php";
function js_mostraRegistroPreco() {

  js_divCarregando("Aguarde, pesquisando parametros","msgBox");
  var oParam            = new Object();
  oParam.exec           = "verificaParametros";
  oParam.itipoLicitacao = $F('l20_codtipocom');
  db_iframe_estimativaregistropreco.hide();
  var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                         method: "post",
                                         parameters:'json='+Object.toJSON(oParam),
                                         onComplete: js_retornoRegistroPreco
                                        });

}
function js_retornoRegistroPreco(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
}

function js_confirmadatas() {

  var dataCriacao    = $F('l20_datacria');
  var dataPublicacao = $F('l20_dtpublic');
  var dataAbertura   = $F('l20_dataaber');

  if( js_CompararDatas(dataCriacao, dataPublicacao, '<=') ) {
    if( js_CompararDatas(dataPublicacao, dataAbertura, '<=') ) {
      <?
        if($db_opcao==2 || $db_opcao==22) {
        	echo 'return js_confirmar();';
        } else {
        	echo 'return true;';
        }
      ?>
    } else {

      alert("A Data de Abertura deve ser maior ou igual a Data de Publicação.");
      return false;
    }
  } else {

    alert("A Data de Publicação deve ser maior ou igual a Data de Criação.");
    return false;
  }

}

function js_CompararDatas(data1,data2,comparar){

  if (data1.indexOf('/') != -1){
    datepart = data1.split('/');
    pYear    = datepart[2];
    pMonth   = datepart[1];
    pDay     = datepart[0];
  }
    data1 = pYear+pMonth+pDay;

  if (data2.indexOf('/') != -1){
    datepart = data2.split('/');
    pYear    = datepart[2];
    pMonth   = datepart[1];
    pDay     = datepart[0];
  }
    data2 = pYear+pMonth+pDay;
    if (eval(data1+" "+comparar+" "+data2)) {

       return true;

     }else{
      return false;
     }
}

function mostrarFormaControleRegistroPreco() {

  if ($F('l20_usaregistropreco') == 't') {
    $('formacontraleregistropreco').style.display = '';
  } else {

    $('l20_formacontroleregistropreco').value     ='1'
    $('formacontraleregistropreco').style.display = 'none';

  }
  verificaTipoJulgamento();
}
</script>
<?php
if ( empty($l34_liclicita)) {
  echo "<script>
         document.form1.lprocsis.value = 'n';
         $('procSis').style.display = 'none';
         $('procAdm').style.display = '';
        </script>";
}
?>
<script>
mostrarFormaControleRegistroPreco();
function bloquearRegistroPreco() {

  $('l20_usaregistropreco').disabled            = true;
  $('l20_usaregistropreco').className           = 'readonly';
  $('l20_formacontroleregistropreco').disabled  = true;
  $('l20_formacontroleregistropreco').className = 'readonly';
  verificaTipoJulgamento();
}
</script>
