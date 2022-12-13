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

$clsolicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("pc50_descr");
$clrotulo->label("pc12_vlrap");
$clrotulo->label("pc12_tipo");
$clrotulo->label("o74_sequencial");
$clrotulo->label("o74_descricao");
$clrotulo->label("pc54_solicita");
$clrotulo->label("pc90_numeroprocesso");
//MODULO: compras
$sArquivoRedireciona = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
$result_tipo = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_seltipo,pc30_tipoemiss"));
if ($clpcparam->numrows>0) {
  db_fieldsmemory($result_tipo,0);
}

$iOpcaoTipoSolicitacao = $db_opcao;
?>
<div>
<form name="form1" method="post" action="">
<fieldset >
<legend><strong>Cadastro de Solicitações</strong></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc10_numero?>">
       <?=@$Lpc10_numero?>
    </td>
    <td>
			<?
			db_input('pc10_numero',10,$Ipc10_numero,true,'text',3)
			?>
    </td>

    <td nowrap title="<?=@$Tpc10_data?>">
       <?=@$Lpc10_data?>
    </td>
    <td>
      <?
        $recebedata = db_getsession("DB_datausu");
        $recebedata = date("Y-m-d",$recebedata);
        if (isset($pc10_data) && trim($pc10_data) != "") {
          $recebedata = $pc10_data;
        }
        $arr_data = split("-",$recebedata);
        @$pc10_datadia = $arr_data[2];
        @$pc10_datames = $arr_data[1];
        @$pc10_dataano = $arr_data[0];
        db_inputdata('pc10_data',$pc10_datadia,$pc10_datames,$pc10_dataano,true,'text',3);
      ?>
    </td>
  </tr>

  <tr title="Número do processo administrativo (PA). Máximo 15 caractéres.">
    <td nowrap="nowrap">
      <strong>Processo Administrativo (PA):</strong>
    </td>
    <td colspan="3">
    <?php
      db_input('pc90_numeroprocesso', 57, $Ipc90_numeroprocesso, true, 'text', $db_opcao);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc10_depto?>">
       <?=@$Lpc10_depto?>
    </td>
    <td colspan="3">
			<?
				db_input("param",10,"",false,"hidden",3);

				$GLOBALS["pc10_depto"] = db_getsession("DB_coddepto");
				$pc10_depto = db_getsession("DB_coddepto");

				db_input('pc10_depto',10,$pc10_depto,true,'text',3);

				$result_depart=$cldb_depart->sql_record($cldb_depart->sql_query_file($pc10_depto,"descrdepto"));
				if ($cldb_depart->numrows > 0) {
				  db_fieldsmemory($result_depart,0);
				}
				db_input('descrdepto',45,$descrdepto,true,'text',3);
			?>
    </td>
  </tr>
	  <?
	   $parampesquisa = true;
	   if (isset($pc30_seltipo) && $pc30_seltipo=="t") {
	  ?>
  <tr>
    <td nowrap title="<?=@$Tpc12_tipo?>">
       <b>Tipo de Compra:</b>
    </td>
    <td colspan="3">
	    <?
		    if (isset($pc12_tipo) && $pc12_tipo=='' || !isset($pc12_tipo)) {

		      $somadata = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_tipcom as pc12_tipo"));
		      if ($clpcparam->numrows>0) {
						db_fieldsmemory($somadata,0);
		      } else if (!isset($chavepesquisa) || !isset($pc10_numero)) {

						db_msgbox("Usuário: \\n\\nParâmetros de solicitação não configurados. \\n\\nAdministrador:");
						$db_opcao=3;
						$db_botao=false;
						$parampesquisa = false;
		      }
		    }
		    $result_tipocompra=$clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom as pc12_tipo,pc50_descr"));
		    db_selectrecord("pc12_tipo",$result_tipocompra,true,$db_opcao);
	    ?>
    </td>
  </tr>
  <?
   }

   if (isset($param) && trim($param) != "") {

     $db_proc = "proc=true";
     if (isset($codliclicita) && trim($codliclicita) != ""){
	     $flag_liclicita = true;
		 } else {
		   $flag_liclicita = false;
		 }

     if (isset($codproc) && trim($codproc) != ""){
	     $dbwhere      = "pc80_codproc = $codproc ";
	     $flag_codproc = true;
		 } else {
	     $dbwhere      = "";
	     $flag_codproc = false;
		 }

     if (isset($chavepesquisa) && trim($chavepesquisa) != "" &&
	     $param == "alterar"   && @$param_ant == ""){
	     $dbwhere = "pc11_numero = $chavepesquisa ";
	   }

		 $clpcproc->rotulo->label();
     if (strlen(trim(@$campo)) > 0) {

        $result_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_inf(null,
	                                                                                  "distinct l21_codliclicita as codliclicita3$campo",
																																				            null,
																																				            "$dbwhere"));
     }
		 if ($flag_liclicita == false) {

	     if ($param == "incluir"){
	       $pc     = 1;
		     $tranca = $pc;

	     } else {
	       $pc     = 3;
		  	 $tranca = $pc + 2;
	     }
  ?>
  <tr >
    <td nowrap title="<?=$Tpc80_codproc?>">
			<?
			   db_ancora($Lpc80_codproc,"js_pesquisapc80_codproc(true);",$tranca);
			?>
	  </td>
	  <td nowrap colspan="3">
      <?
        db_input('codproc',10,$Ipc80_codproc,true,"text",$pc,"OnChange='js_pesquisapc80_codproc(false);'");
      ?>
	  </td>
  </tr>
    <?
     }

     if (isset($codliclicita) && trim($codliclicita) != "") {
	     $dbwhere = "l21_codliclicita = $codliclicita ";
		 } else {
	     $dbwhere = "";
	     $campo   = "";
		 }

     if (isset($chavepesquisa) && trim($chavepesquisa) != "" && $param == "alterar"   && @$param_ant == "") {

	     $dbwhere    = "pc11_numero = $chavepesquisa ";
	     $campo      = ",pc11_numero as codsol";
	     $flag_achou = false;
	   }

     if (strlen(trim(@$campo)) > 0) {
       $result_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_inf(null,
	                                                                                 "distinct l21_codliclicita as codliclicita3$campo",
						                                                                       null,
                                                       								             "$dbwhere"));
     }
  	if ($flag_codproc == false) {
    ?>
    <tr>
      <td nowrap title="Licitação">
  	    <b>
  				<?
  				   db_ancora("Licitação:","js_pesquisal21_codliclicita(true);",$tranca);
  				?>
  	    </b>
  	  </td>
  	  <td nowrap colspan="3">
          <?
             db_input('codliclicita',10,"",true,"text",3,"onChange='js_pesquisal21_codliclicita(false);'");
          ?>
  	  </td>
    </tr>
  <?
  	}
  }
  ?>
  <tr>
    <td>
      <b>Tipo de Solicitacao:</b>
    </td>
    <td colspan="3">
      <?
        $aTiposDisponiveis = array(1 => "Normal",
                                   2 => "Pacto",
                                   5 => "Registro de Preço"
                                  );

        db_select("pc10_solicitacaotipo",$aTiposDisponiveis,true, $iOpcaoTipoSolicitacao, "onchange='js_showCamposRegistro()'");
      ?>
    </td>
  </tr>
<?
   /**
    * Caso esteje ativado a utilização de pactos,
    * trazemos os planos cadastrados ;
    */

   if (isset($aParametrosOrcamento[0]->o50_utilizapacto) && $aParametrosOrcamento[0]->o50_utilizapacto == "t") {
?>
  <tr id='planopacto' style='display: none' >
    <td nowrap >
      <?
        if ($db_opcao != 1) {

          $planosolicita = @$o74_sequencial;
          db_input('planosolicita',10,$Io74_sequencial,true,'hidden',1);
        }
        db_ancora("<b>Plano:</b>","js_pesquisapactoplano(true);",$db_opcao);
      ?>
    </td>
    <td colspan="3">
      <?
        db_input('o74_sequencial',10,$Io74_sequencial,true,'text',$db_opcao," onchange='js_pesquisapactoplano(false);'");
        db_input('o74_descricao',40,$Io74_descricao,true,'text',3,'');
      ?>
    </td>
  </tr>
<?
   }
?>
  <tr id='registropreco' style='display: none'">
    <td>
      <?
       db_ancora("<b>Registro de Preço:</b>", "js_pesquisarcompilacao(true);", $db_opcaoBtnRegistroPreco);
      ?>
    </td>
    <td colspan="3">
      <?
       db_input('pc54_solicita', 8, $Ipc54_solicita, true, 'text', 3, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc10_a?>" colspan="4">
      <fieldset>
        <legend><?=@$Lpc10_resumo?></legend>
			  <?
			    @$pc10_resumo = stripslashes($pc10_resumo);
			    db_textarea("pc10_resumo", 11, 80, "", true, "text", $db_opcao, "", "", "", 735);
			  ?>
      </fieldset>
    </td>
  </tr>
</table>
</fieldset>

<br>

<diV style="margin-left: 150px;">
<input style="float:left;" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?>
       onclick="return js_validaAlteracao(<?=$db_opcao?>)">

<input style="float:left;margin-left: 2px;" name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($parampesquisa==false?"disabled":"")?> >

<?
if(isset($pc10_numero) || isset($chavepesquisa)){
  if(isset($chavepesquisa)){
    $pc10_numero=$chavepesquisa;
  }
  $result_itens = $clsolicitem->sql_record($clsolicitem->sql_query_file(null,"pc11_codigo",""," pc11_numero=$pc10_numero "));
  if($clsolicitem->numrows>0){
    echo "<input name='gera' style='float:left;margin-left: 2px;' type='submit' id='gera' value='Gerar relatório' onclick='js_gerarel();'>";
  }
}
if(isset($departusu) && trim($departusu)!=""){
  echo '<input style="float:left;margin-left: 2px;" name="importar" type="button" id="importar" value="Importar Solicitação" onclick="js_importa();">';
}
db_input('opselec',40,"",true,'hidden',3);
?>
<!--
<input type='button' id = 'btnInconsistencia' value='Relatorio Inconsistencias' onclick='js_exibeRelatorioInconsistencia();' style='display:none; float:left;margin-left: 2px;' />
-->
</diV>

</form>
</div>
<script>

var lUtilizaPacto = '<?=@$lUtilizaPacto?>';


var sUrlRpc    = 'com4_solicitacaoComprasRegistroPreco.RPC.php';
var oParam     = new Object();
 /**
  * função para verificar o saldo de cada item antes da importação
  * @param integer iSolicitacao
  */
 function js_verificaSaldoItemImportar(iSolicitacao) {



  oParam.exec         = "verificaSaldoItemSolicitacao"    ;
  oParam.iSolicitacao = iSolicitacao;

  js_divCarregando('Aguarde...','msgBox');

  var oAjax   = new Ajax.Request (sUrlRpc,{
                                         method     : 'post',
                                         parameters : 'json='+Object.toJSON(oParam),
                                         onComplete : js_retornoVerificaSaldoItemImportar
                                        }
                                    );

}

function js_retornoVerificaSaldoItemImportar(oJson) {

  js_removeObj("msgBox");

  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.iStatus == 2) {

    if (confirm(oRetorno.sMessage.urlDecode())) {
      js_exibeRelatorioInconsistencia();
    }
    return false;

  }
 /*
  if (oRetorno.sCodigoItensSemSaldo != ""){

    var sMsgErro  = "Alguns itens não possuem saldo para importação, portanto não poderão ser importados.";
    sMsgErro     += "Deseja Continuar ? ";


    //$('btnInconsistencia').style.display = 'block';

    var sMsgImprimir = "Deseja imprimir relatório de inconsistência?";

    if (confirm(sMsgImprimir)) {
      js_exibeRelatorioInconsistencia();



    }

    if (!confirm(sMsgErro)) {
      return false;
    }

  }*/

  var sArquivoRedireciona = <?php echo "'{$sArquivoRedireciona}'";?>;

  var iTipoSolicitacao = 1;

  if(oRetorno.iAberturaRegistroPreco != ""){
    iTipoSolicitacao = 5;
  }


  var sParametros  = "?lRegistroPreco=1&importar="+oRetorno.iCodigoSolicitacao+"&sItensNaoImportados="+oRetorno.sCodigoItensSemSaldo;
      sParametros += "&pc10_solicitacaotipo="+iTipoSolicitacao+"&pc54_solicita="+oRetorno.iAberturaRegistroPreco;

  $('pc54_solicita').value = oRetorno.iAberturaRegistroPreco;

  location.href = sArquivoRedireciona+sParametros;
}

function js_exibeRelatorioInconsistencia() {

  /**
   * confirma a exibição do relatorio de inconsistencias
     itens com valores zerados
   */
   var sFonte  = "com3_inconsistenciaImportacaoSolicitacao.php";  // relatorio de erros
       jan = window.open(sFonte,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
       jan.moveTo(0,0);

}


function js_gerarel(){
  obj = document.form1;
  query='';
  query += "&ini="+obj.pc10_numero.value;
  query += "&fim="+obj.pc10_numero.value;
  query += "&departamento=<?=db_getsession("DB_coddepto")?>";
  <?
  if(isset($pc30_tipoemiss) && trim($pc30_tipoemiss)!=""){
    if($pc30_tipoemiss=="t"){
      echo "jan = window.open('com2_emitesolicita002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');";
    }else{
      echo "jan = window.open('com2_emitesolicita003.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');";
    }
    echo "jan.moveTo(0,0);";
  }else{
    echo "alert('Usuário:\\n\\nParâmetros do módulo compras não configurados.\\n\\nAdministrador:');";
  }
  ?>
  /*
  ini = document.form1.pc10_numero.value;
  jan = window.open('com2_emitesolicita002.php?ini='+ini+'&fim='+ini,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  */
}
function js_importa(){

  //$('btnInconsistencia').style.display = 'none';


  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicita',
                      'db_iframe_solicita',
                      'func_solicita.php?funcao_js=parent.js_preencheimporta|pc10_numero|pc52_sequencial&nada=true&param_mostra_regprecos=nao',
                      'Pesquisa',true,'0');
}


function js_preencheimporta(chave, lRegistroPreco){

  //alert("registro -> " + lRegistroPreco);
  db_iframe_solicita.hide();

  if(lRegistroPreco == 5) {
    js_verificaSaldoItemImportar(chave);
  } else {

    <?
        if($db_opcao==1){
          echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?importar='+chave";
        }
        ?>

  }


}


function js_pesquisa(){
<?php
	if (isset($param) && $param != ""){
    if (isset($codliclicita) && strlen(trim(@$codproc)) > 0 || strlen(trim(@$codliclicita)) > 0) {
      $parametro = "&param=".$param."&codproc=".$codproc."&codliclicita=".$codliclicita;
    } else {
      $parametro = "&param=".$param;
    }
  } else {
    $parametro = "";
  }

  if(isset($db_proc)) {
?>
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicita','db_iframe_solicita','func_solicitaalt.php?<?=$db_proc?>&funcao_js=parent.js_preenchepesquisa|pc10_numero&departamento=<?=db_getsession("DB_coddepto")?><?=$parametro?>','Pesquisa',true,'0');
<?php
  } else {
?>
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicita','db_iframe_solicita','func_solicitaalt.php?funcao_js=parent.js_preenchepesquisa|pc10_numero&departamento=<?=db_getsession("DB_coddepto")?><?=$parametro?>','Pesquisa',true,'0');
<?php
  }
?>
}
function js_preenchepesquisa(chave){

  db_iframe_solicita.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&liberaaba=false$parametro'";
  }
  ?>
}
<?
   if (isset($param) && trim($param) != ""){
        $parametro2 = "&param=".$param;
   } else {
        $parametro2 = "";
   }
?>
function js_pesquisapc80_codproc(mostra){
   if (mostra == true){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicita','db_iframe_pcproc','func_excautitem.php?funcao_js=parent.js_mostrapcproc1|pc80_codproc<?=$parametro2?>','Processos',true);
   } else {
        if (document.form1.codproc.value != ""){
             js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicita','db_iframe_pcproc','func_excautitem.php?pesquisa_chave='+document.form1.codproc.value+'&funcao_js=parent.js_mostrapcproc<?=$parametro2?>','Processos',false);
	}
   }
}
function js_pesquisal21_codliclicita(mostra){
   if(mostra == true){
       js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicita','db_iframe_liclicita','func_liclicita.php?funcao_js=parent.js_mostraliclicita1|l20_codigo<?=$parametro2?>','Licitações',true);
   } else {
       if(document.form1.codliclicita.value != ''){
           js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicita','db_iframe_liclicita','func_liclicita.php?pesquisa_chave='+document.form1.codliclicita.value+'&funcao_js=parent.js_mostraliclicita<?=$parametro2?>','Licitações',false);
       }
   }
}
function js_mostrapcproc1(chave1){
   document.form1.codproc.value = chave1;
   db_iframe_pcproc.hide();
}
function js_mostrapcproc(chave,erro){
   if(erro==true){
       document.form1.codproc.focus();
       document.form1.codproc.value = '';
       alert("Processo de compras já autorizado a empenho!");
   }
}
function js_mostraliclicita(chave,erro){
  if(erro==true){
      document.form1.codliclicita.value = '';
      document.form1.codliclicita.focus();
      alert("Licitação já autorizada a empenho!");
  }
}
function js_mostraliclicita1(chave1){
   document.form1.codliclicita.value = chave1;
   db_iframe_liclicita.hide();
}
function js_pesquisapactoplano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_pactoplano',
                        'func_pactoplano.php?funcao_js=parent.js_mostrapactoplano1|o74_sequencial|o74_descricao',
                        'Pesquisa de Planos',
                        true);
  }else{
     if(document.form1.o74_sequencial.value != ''){
        js_OpenJanelaIframe('',
                            'db_iframe_pactoplano',
                            'func_pactoplano.php?pesquisa_chave='+document.form1.o74_sequencial.value+
                            '&funcao_js=parent.js_mostrapactoplano','Pesquisa',false);
     }else{
       document.form1.o74_descricao.value = '';
     }
  }
}
function js_mostrapactoplano(chave,erro){
  document.form1.o74_descricao.value = chave;
  if(erro==true){

    document.form1.o74_sequencial.focus();
    document.form1.o74_sequencial.value = '';

  }
}
function js_mostrapactoplano1(chave1,chave2){

  document.form1.o74_sequencial.value = chave1;
  document.form1.o74_descricao.value = chave2;
  db_iframe_pactoplano.hide();

}

function js_validaAlteracao(situacao) {

  if (lUtilizaPacto != '') {

    if (situacao == 3 && (document.form1.o74_sequencial.value != "")) {

      if (!confirm('Excluindo a solicitação, todos as vinculações com o pacto serao excluídas.\nContinuar?')) {
        return false;
      }
    } else if (situacao == 2 && (document.form1.o74_sequencial.value != document.form1.planosolicita.value)) {
      var sMsg  = 'A Solicitação possuia vinculo com o plano ('+document.form1.planosolicita.value+').\n';
      sMsg     += 'Serão EXCLUIDAS todas  as movimentacoes do plano vinculados a a solcitação.\nContinuar?';
      if (!confirm(sMsg)) {
        return false;
      }
    }
  }

  if (situacao == 1) {

    if ($F('pc10_solicitacaotipo') == 5 && $F('pc54_solicita') == "") {
      alert("Informe o registro de preço."); return false;
    }
  }
  return true;
}

function js_showCamposRegistro() {

  var iTipoSolicitacao = $F('pc10_solicitacaotipo');

  if ( lUtilizaPacto != '' ) {
	  if ( iTipoSolicitacao == 2 ) {
	    $('planopacto').style.display = '';
	  } else {
	    $('planopacto').style.display = 'none';
	  }
  }

  if ( iTipoSolicitacao == 5 ) {

    $('registropreco').style.display = '';

    <?php if ( $pc30_sugforn == 't') : ?>
      parent.document.formaba.sugforn.disabled=true;
    <?php endif; ?>

  } else {

    $('registropreco').style.display = 'none';

    <?php if ( $pc30_sugforn == 't') : ?>
      parent.document.formaba.sugforn.disabled=false;
    <?php endif; ?>

    if(empty($F('pc10_numero'))) {

      parent.document.formaba.sugforn.disabled = true;
      parent.document.formaba.solicitem.disabled = true;
    }
  }

}

function js_pesquisarcompilacao (lMostrarRegistro) {

  if (lMostrarRegistro==true) {

    js_OpenJanelaIframe('',
                      'db_iframe_estimativaregistropreco',
                      'func_solicitacompilacao.php?funcao_js=parent.js_mostracompilacao|pc10_numero'+
                      '&anuladas=1&trazapenascomlicitacao=1&estimativadepto=1&apenaslicitacaojulgada=1&lDesabilitaFiltroInstituicaoCompilacao=true&validavigencia=1',
                      'Registros de Preço',
                       true
                      );
  } else {

     if ($F('pc54_solicita') != '') {
     js_OpenJanelaIframe('',
                      'db_iframe_estimativaregistropreco',
                      'func_solicitacompilacao.php?pesquisa_chave='+$F('pc54_solicita')+
                      '&funcao_js=parent.js_mostracompilacao1&anuladas=1&apenaslicitacaojulgada=1&trazapenascomlicitacao=1&estimativadepto=1&lDesabilitaFiltroInstituicaoCompilacao=true',
                      'Registros de Preço',
                      false
                      );
     } else {
       $('pc54_solicita').value = '';
     }
  }
}
function js_mostracompilacao(iSolicitacao) {

  $('pc54_solicita').value = iSolicitacao;
  db_iframe_estimativaregistropreco.hide();

}
js_showCamposRegistro();
</script>