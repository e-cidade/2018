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

//MODULO: empenho
$clempautoriza->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e44_tipo");
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("pc50_descr");
$clrotulo->label("e57_codhist");
$clrotulo->label("c58_descr");
$clrotulo->label("e150_numeroprocesso");
if ($db_opcao==1) {
  $ac="emp1_empautoriza004.php";
}else if ($db_opcao==2 || $db_opcao==22) {
  $ac="emp1_empautoriza005.php";
}else if ($db_opcao==3 || $db_opcao==33) {
  $ac="emp1_empautoriza006.php";
}

db_app::load("DBFormCache.js");

?>

<style>

  #e57_codhistdescr,
  #e54_codtipodescr,
  #e54_codcomdescr {
    width: 400px;
  }
  #e57_codhist,
  #e54_codtipo,
  #e54_tipol,
  #e54_codcom {
    width: 50px;
  }
  #e44_tipo{
    width: 453px;
  }
  #e54_tipoldescr{
    width: 200px;
  }
</style>

<form name="form1" method="post" onsubmit="<?php ($db_opcao == 1) ? 'return js_salvaCache();' : ''; ?>" action="<?=$ac?>">
<fieldset>
<legend><strong>Autorização de Empenho </strong></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te54_autori?>">
       <?=@$Le54_autori?>
    </td>
    <td>
      <?
       db_input('e54_autori',10,$Ie54_autori,true,'text',3);
       db_input('o58_codele',10,$Ie54_autori,true,'hidden',3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_numcgm?>">
    <?
       db_ancora(@$Lz01_nome,"js_pesquisae54_numcgm(true);",isset($emprocesso)&&$emprocesso==true?"3":$db_opcao);
     ?>
    </td>
    <td nowrap="nowrap">
      <?
        db_input('e54_numcgm',10,$Ie54_numcgm,true,'text',isset($emprocesso)&&$emprocesso==true?"3":$db_opcao," onchange='js_pesquisae54_numcgm(false);'");
        db_input('z01_nome',48,$Iz01_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_codcom?>">
       <strong>Tipo de Compra:</strong>
    </td>
    <td>
      <?php

        if(isset($e54_codcom) && $e54_codcom==''){
          $pc50_descr='';
        }
        if (empty($e54_codcom)) {

          $somadata = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_tipcom as e54_codcom"));
          if ($clpcparam->numrows>0) {
            db_fieldsmemory($somadata,0);
          } else {
            $e54_codcom = 5;
          }
        }

       /*
        * alterado para liberar o campo tipo de compra para alteracao
        */
        $result = $clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom as e54_codcom,pc50_descr"));
        db_selectrecord("e54_codcom",$result,true,isset($emprocesso)&&$emprocesso==true?"1":$db_opcao,"","","","","js_reload(this.value)");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_tipol?>">
      <strong>Tipo de Licitação:</strong>
    </td>
    <td>
      <?
        if (isset($tipocompra) || isset($e54_codcom)) {

          if (isset($e54_codcom) && empty($tipocompra)) {
            $tipocompra=$e54_codcom;
          }

          $result=$clcflicita->sql_record($clcflicita->sql_query_file(null,"l03_tipo,l03_descr",'',"l03_codcom=$tipocompra and l03_instit = ".db_getsession('DB_instit')));
          if ($clcflicita->numrows > 0) {
            /*
             * alterado para liberar o campo tipo licitacao para alteracao
             */
            db_selectrecord("e54_tipol",$result,true,isset($emprocesso)&&$emprocesso==true?"1":"1","","","");
            $dop = $db_opcao;
          } else {

            $e54_tipol  = '';
            $e54_numerl = '';
            $dop        = '3';
            db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
          }
        } else {

          $dop        = '3';
          $e54_tipol  = '';
          $e54_numerl = '';
          db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
        }
      ?>
      <strong>Número da Licitação:</strong>
      <? db_input('e54_numerl', 7,$Ie54_numerl,true,'text',isset($emprocesso)&&$emprocesso==true?"1":$dop); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_codtipo?>">
       <strong>Tipo de Empenho:</strong>
    </td>
    <td>
      <?

       /*
        * alterado para liberar o campo tipo de empenho para alteracao
        */
        $result = $clemptipo->sql_record($clemptipo->sql_query_file(null,"e41_codtipo,e41_descr"));
        db_selectrecord("e54_codtipo",$result,true,isset($emprocesso)&&$emprocesso==true?"1":$db_opcao);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te57_codhist?>">
       <?=$Le57_codhist?>
    </td>
    <td>
      <?
         // caso empparametro.e30_autimportahist=='t' busca o historico da ultima autorização
         $par =  $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu")));
         if ($clempparametro->numrows>0 && $db_opcao == 1) {

           db_fieldsmemory($par,0);
           if ($e30_autimportahist == 't') {

    	        $hist = $clempauthist->sql_record("select e57_codhist
    	                                             from empauthist
    					                               inner join empautoriza on e54_autori=e57_autori
    					                                    where e54_login=".db_getsession("DB_id_usuario")."
    					                                 order by e57_autori desc limit 1");
      	     if ($clempauthist->numrows>0) {
      	       db_fieldsmemory($hist,0);
      	     }
           }
         }
         $result = $clemphist->sql_record($clemphist->sql_query_file(null,"e40_codhist,e40_descr"));
         db_selectrecord("e57_codhist",$result,true,isset($emprocesso)&&$emprocesso==true?"3":"1","","","","0");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te44_tipo?>">
       <?=$Le44_tipo?>
    </td>
    <td>
      <?
        $result  = $clempprestatip->sql_record($clempprestatip->sql_query_file(null,"e44_tipo as tipo,e44_descr,e44_obriga","e44_obriga "));
        $numrows = $clempprestatip->numrows;
        $arr     = array();
        for ($i = 0; $i < $numrows; $i++) {

          db_fieldsmemory($result,$i);
          if($e44_obriga == 0 && empty($e44_tipo)) {
            $e44_tipo = $tipo;
          }
          $arr[$tipo] = $e44_descr;
        }
        db_select("e44_tipo", $arr, true, 1);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_destin?>">
       <?=@$Le54_destin?>
    </td>
    <td>
      <?
           /*
            * alterado para liberar o campo destino para alteracao
            */
        db_input('e54_destin',61,$Ie54_destin,true,'text',isset($emprocesso)&&$emprocesso==true?"1":$db_opcao,"")
      ?>
    </td>
  </tr>

  <tr title="Número do processo administrativo (PA). Máximo 15 caractéres.">
    <td nowrap="nowrap">
      <strong>Processo Administrativo (PA):</strong>
    </td>
    <td colspan="3">
    <?php
      db_input('e150_numeroprocesso', 61, $Ie150_numeroprocesso, true, 'text', $db_opcao);
    ?>
    </td>
  </tr>
<?
  $anousu = db_getsession("DB_anousu");
  if ($anousu > 2007) {
?>
  <tr>
    <td nowrap title="<?=@$Te54_concarpeculiar?>">
      <?
        db_ancora(@$Le54_concarpeculiar,"js_pesquisae54_concarpeculiar(true);",isset($emprocesso)&&$emprocesso==true?"3":$db_opcao);
      ?>
    </td>
    <td nowrap="nowrap">
      <?
        db_input("e54_concarpeculiar",10,$Ie54_concarpeculiar,true,"text",isset($emprocesso)&&$emprocesso==true?"3":$db_opcao,"onChange='js_pesquisae54_concarpeculiar(false);'");
        db_input("c58_descr", 47, 0, true, "text", 3);
      ?>
    </td>
  </tr>
<?
 } else {
   $e54_concarpeculiar = 0;
   db_input("e54_concarpeculiar",10,0,true,"hidden",3,"");
 }
?>
  <tr>
    <td nowrap title="<?=@$Te54_resumo?>" colspan="2">
      <fieldset>
        <legend>
          <strong><?=@$Le54_resumo?></strong>
        </legend>
       <? db_textarea('e54_resumo',3,84,$Ie54_resumo,true,'text',$db_opcao,"") ?>
      </fieldset>
    </td>
  </tr>
</table>
</fieldset>

<div style="margin-top: 10px;">

  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
         type="submit" id="db_opcao"
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
         <?=($db_botao==false?"disabled":"")?> onclick="<?php ($db_opcao == 1) ? 'return js_salvaCache();' : ''; ?>" >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >


  <?if($db_opcao==2){?>
  <input name="novo" type="button" id="novo" value="Nova autorização" onclick="js_nova();" >

  <?
  $permissao_lancar = db_permissaomenu(db_getsession("DB_anousu"),398,3489);
  if ($permissao_lancar == "true") {
  ?>
  <input name="lancemp" type="button" id="lancemp" value="Lançar Empenho" onclick="js_lanc_empenho();" >
  <?
    }
  }

  if ($db_opcao == 1 ){ ?>
  <input name="importar" type="button" id="importar" value="Importar autorização" onclick="js_importar();" >
  <?}?>

</div>

<?if(isset($emprocesso) && $emprocesso == true){?>
  <br><font color="red"><b>Autorização gerada por solicitação de compras.</b></font>
<?}?>

</form>
<script>

var db_opcao = <?php echo $db_opcao; ?>;

var oDBFormCache = new DBFormCache('oDBFormCache', 'db_frmempautoriza.php');

oDBFormCache.setElements( new Array ( $('e54_codtipo')       ,
                                      $('e54_codtipodescr')  ,
                                      $('e57_codhist')       ,
                                      $('e57_codhistdescr')  ,
                                      $('e54_concarpeculiar'),
                                      $('c58_descr')         ,
                                      $('e44_tipo')
                         ));


if (db_opcao == 1) {
  oDBFormCache.load();
  oDBFormCache.save();
}

function js_salvaCache(){

  oDBFormCache.save();
  return true;
}


function js_pesquisae54_concarpeculiar(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_empautoriza','db_iframe_concarpeculiar',
                        'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|'+
                        'c58_sequencial|c58_descr','Pesquisa',true,'0','1');
  }else{
     if(document.form1.e54_concarpeculiar.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_empautoriza',
                            'db_iframe_concarpeculiar',
                            'func_concarpeculiar.php?pesquisa_chave='+document.form1.e54_concarpeculiar.value+
                            '&funcao_js=parent.js_mostraconcarpeculiar','Pesquisa',false);
     }else{
       document.form1.c58_descr.value = '';
     }
  }
}
function js_mostraconcarpeculiar(chave,erro){
  document.form1.c58_descr.value = chave;
  if(erro==true){
    document.form1.e54_concarpeculiar.focus();
    document.form1.e54_concarpeculiar.value = '';
  }
}
function js_mostraconcarpeculiar1(chave1,chave2){
  document.form1.e54_concarpeculiar.value = chave1;
  document.form1.c58_descr.value          = chave2;
  db_iframe_concarpeculiar.hide();
}
function js_nova(){
  destin = document.form1.e54_destin.value;
  resumo = document.form1.e54_resumo.value;
  numcgm = document.form1.e54_numcgm.value;
  nome   = document.form1.z01_nome.value;
  parent.location.href="emp1_empautoriza001.php?z01_nome="+nome+"&e54_numcgm="+numcgm+"&e54_destin="+destin+"&e54_resumo="+resumo;
}
// lançar empenho
function js_lanc_empenho(){

  autori = document.form1.e54_autori.value;
  var iElemento = $F("o58_codele");

  parent.location.href="<?=$sUrlEmpenho?>?iElemento="+iElemento+"&chavepesquisa="+autori+"&lanc_emp=true";
}

function completaElemento(iElemento) {

  //alert(iElemento);
  $("o58_codele").value = iElemento;
}

function js_reload(valor){
  obj=document.createElement('input');
  obj.setAttribute('name','tipocompra');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',valor);
  document.form1.appendChild(obj);
  document.form1.submit();
}
function js_pesquisae54_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_empautoriza','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'0','1');
  }else{
     if(document.form1.e54_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_empautoriza','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e54_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}

function js_mostracgm(erro,chave){

  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.e54_numcgm.focus();
    document.form1.e54_numcgm.value = '';
  } else {
    js_debitosemaberto();
  }
}

function js_mostracgm1(chave1,chave2){

  document.form1.e54_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();

  js_debitosemaberto();
}

function js_pesquisae54_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0','1');
  }else{
     if(document.form1.e54_login.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.e54_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.e54_login.focus();
    document.form1.e54_login.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.e54_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}

function js_importar(){
  js_OpenJanelaIframe('top.corpo.iframe_empautoriza','db_iframe_empautoriza','func_empautoriza.php?funcao_js=parent.js_importar02|e54_autori','Pesquisa',true,'0','1');
}
function js_importar02(chave){
  db_iframe_empautoriza.hide();
  if(confirm("Deseja realmente importar a autorização "+chave+"?" )){
     <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?autori_importa='+chave";
     ?>
  }
}
function js_pesquisa(){
<?
  if($db_opcao==2 || $db_opcao==22){
    $iframe="selempautoriza";
  }else{
    $iframe="selempautoriza";
  }
?>
  js_OpenJanelaIframe('top.corpo.iframe_empautoriza','db_iframe_<?=$iframe?>','func_<?=$iframe?>.php?funcao_js=parent.js_preenchepesquisa|e54_autori','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_<?=$iframe?>.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

/**
 * Procura se o fornecedor possui débitos em aberto
 */
function js_debitosemaberto() {

  var sUrlRPC = 'com4_notificafornecedor.RPC.php';
  var iCgm    = $('e54_numcgm').value;

  if ($('pesquisar')) {
    $('pesquisar').disabled = true;
  }

  if ($('novo')) {
    $('novo').disabled = true;
  }

  if ($('lancemp')) {
    $('lancemp').disabled = true;
  }

  if ($('importar')) {
    $('importar').disabled = true;
  }

  $('db_opcao').disabled   = true;

  js_divCarregando('Aguarde, verificando débitos em aberto...',"msgBoxDebitosEmAberto");

  var oParam        = new Object();
  oParam.sExecucao  = 'debitosEmAberto';
  oParam.iNumCgm    = iCgm;
  oParam.sLiberacao = "A";

  var oAjax        = new Ajax.Request (sUrlRPC,
                                       {
                                          method: 'post',
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornodebitosemaberto
                                       });
}

/**
 * Retorno com os débitos em aberto e informações de configuração
 */
function js_retornodebitosemaberto(oAjax) {

  js_removeObj("msgBoxDebitosEmAberto");

  var oRetorno                = eval("("+oAjax.responseText+")");
  var iNumCgm                 = new Number(oRetorno.iNumCgm);
  var iParamFornecDeb         = new Number(oRetorno.iParamFornecDeb);
  var iDebitosEmAberto        = new Number(oRetorno.iDebitosEmAberto);
  var lParamGerarNotifDebitos = oRetorno.lParamGerarNotifDebitos;

  if (iParamFornecDeb == 1) {

    if ($('pesquisar')) {
      $('pesquisar').disabled = false;
    }

    if ($('novo')) {
      $('novo').disabled = false;
    }

    if ($('lancemp')) {
      $('lancemp').disabled = false;
    }

    if ($('importar')) {
      $('importar').disabled = false;
    }

    $('db_opcao').disabled   = false;
  } else if (iParamFornecDeb == 2) {

    if (iDebitosEmAberto > 0) {

      var sMensagem  = 'O fornecedor '+ iNumCgm +' possui débitos em aberto.';
          sMensagem += '\n Deseja Notifica-lo?';
      if (confirm(sMensagem)) {
        js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, true);
      } else {
        js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, false);
      }
    } else {

	    if ($('pesquisar')) {
	      $('pesquisar').disabled = false;
	    }

	    if ($('novo')) {
	      $('novo').disabled = false;
	    }

	    if ($('lancemp')) {
	      $('lancemp').disabled = false;
	    }

	    if ($('importar')) {
	      $('importar').disabled = false;
	    }

	    $('db_opcao').disabled = false;
    }
  } else if (iParamFornecDeb == 3) {

    if (iDebitosEmAberto > 0) {

      alert('O fornecedor '+ iNumCgm +' possui débitos em aberto.');

      js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, true);

    } else {
    	if ($('pesquisar')) {
  	    $('pesquisar').disabled = false;
  	  }

  	  if ($('novo')) {
  	    $('novo').disabled = false;
  	  }

  	  if ($('lancemp')) {
  	    $('lancemp').disabled = false;
  	  }

  	  if ($('importar')) {
  	    $('importar').disabled = false;
  	  }

  	  $('db_opcao').disabled = false;
    }
  }
}

/**
 * Executa a notificação de débitos ao fornecedor
 */
function js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, aFormaNotificacao, lGerarNotificacaoDebito, lMostrarJanela) {

    var iOrigem       = 3;
    var iCodigoOrigem = $('e54_autori').value;

    oNotificarDebitos = new dbViewNotificaFornecedor(iNumCgm, iOrigem);
    oNotificarDebitos.setCodigoOrigem(iCodigoOrigem);
    oNotificarDebitos.setGerarNotificacaoDebito(lGerarNotificacaoDebito);
    if (lMostrarJanela) {

      oNotificarDebitos.setFormaNotificacao(aFormaNotificacao, true);
      if (aFormaNotificacao.length > 0) {
        oNotificarDebitos.show();
      } else {
        oNotificarDebitos.setFormaNotificacao(aFormaNotificacao, false);
      }
    } else {

      oNotificarDebitos.setGerarNotificacaoDebito(false);
      oNotificarDebitos.setFormaNotificacao(0, false);
    }

    /**
     * Retorno do processo de notificação de debitos
     */
    oNotificarDebitos.setCallBack(function (oRetorno) {

      if (oRetorno.lFormaNotifEmail) {
        alert(oRetorno.sMessage.urlDecode());
      }

      if (oRetorno.lFormaNotifCarta) {
        js_emitircartanotificacao(oRetorno.iCodigoNotificaBloqueioFornecedor);
      }

      if ($('pesquisar')) {
        $('pesquisar').disabled = false;
      }

      if ($('novo')) {
        $('novo').disabled = false;
      }

      if ($('lancemp')) {
        $('lancemp').disabled = false;
      }

      if ($('importar')) {
        $('importar').disabled = false;
      }

	    $('db_opcao').disabled = false;
      if (iParamFornecDeb == 3) {
        $('e54_numcgm').value = '';
        $('z01_nome').value    = '';
      }
    });
}

function js_emitircartanotificacao(iCodigoNotificaBloqueioFornecedor) {

  var jan = window.open('com2_emitircartanotificacao002.php?iCodigoNotificaBloqueioFornecedor='+iCodigoNotificaBloqueioFornecedor,
                        '',
                        'width='+(screen.availWidth-5)+
                        ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}
</script>