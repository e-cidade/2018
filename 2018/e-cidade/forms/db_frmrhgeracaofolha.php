<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyleft (C) 2014  DBseller Servicos de Informatica
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
include(modification("classes/db_rhlota_classe.php"));
include(modification("dbforms/db_classesgenericas.php"));

$clrhgeracaofolha->rotulo->label();
$clrotulo     = new rotulocampo;
$clrotulo->label('labelValorLiquido');
$clrotulo->label('labelFaixaValorLiquido');
$clrotulo->label('labelPercentualPago');
$clrotulo->label('r90_valor');
$clrotulo->label('r48_semest');
$clrhlota     = new cl_rhlota;
$oTipoResumo  = new cl_formulario_rel_pes;

if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
  $anofolha = db_anofolha();
}
if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
  $mesfolha = db_mesfolha();
}
?>
<style>
form > fieldset > table > TBODY > tr > td:first-child {
  width: 180px;

}
</style>

	<form name="form1" method="get" action="" id="form1" class="container">
		<fieldset>
		<legend><strong>Dados da Folha</strong></legend>
		<table class="form-container">
		  <tr>
		    <td nowrap colspan="2">
		    <?php
				  $oTipoResumo->selecao             = true;                      // Mostra campo seleção
				  $oTipoResumo->strngtipores        = "glom";                    // String Para os Tipos de Resumo
				  $oTipoResumo->manomes             = false;                     // Não Mostra ano e mês de Competência da Folha
				  $oTipoResumo->usalota             = true;                      // Permite Utilizar Lotações
				  $oTipoResumo->usaorga             = true;                      // Permite Utilizar Órgãos
				  $oTipoResumo->usaregi             = true;                      // permite Utitizar Registros
				  $oTipoResumo->lo1nome             = "lotaci";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
				  $oTipoResumo->lo2nome             = "lotacf";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
				  $oTipoResumo->lo3nome             = "sellotac";                // Nome do Objeto para seleção de lotações
				  $oTipoResumo->re1nome             = "registini";               // Nome de Campo Registro Inicial
				  $oTipoResumo->re2nome             = "registfim";               // Nome do campo Registro Final
				  $oTipoResumo->re3nome             = "selregist";               // Nome do objeto de seleção de registros.
				  $oTipoResumo->or1nome             = "orgaoi";                  // NOME DO CAMPO DO ÓRGÃO INICIAL
				  $oTipoResumo->or2nome             = "orgaof";                  // NOME DO CAMPO DO ÓRGÃO FINAL
				  $oTipoResumo->or3nome             = "selorg";                  // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS
				  $oTipoResumo->trenome             = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
				  $oTipoResumo->tfinome             = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO
				  $oTipoResumo->resumopadrao        = "g";                       // TIPO DE RESUMO PADRÃO
				  $oTipoResumo->campo_auxilio_lota  = "faixa_lotac";             // Nome do campo de auxílio das Lotações Selecionadas
				  $oTipoResumo->campo_auxilio_orga  = "faixa_orgao";             // Nome do campo de auxílio dos órgãos selecionados.
				  $oTipoResumo->campo_auxilio_regi  = "faixa_matricula";         // Nome do campo de auxílio das matrículas selecionadas.
				  $oTipoResumo->onchpad             = true;                      // Muda as Opções ao selecionar o Filtro
				  $oTipoResumo->desabam             = false;                     // Desabilita Ano e Mês
				  $oTipoResumo->manomes             = true;                      // Mostrar ano e mês no formulário.
				  $oTipoResumo->gera_form($anofolha,$mesfolha);                  // Motra o Formulário na tela
		    ?>
		  <tr>
		    <td align="left">
		      <?=$Lrh102_descricao?>
		    </td>
		    <td align="left">
          <?php
            db_input("rh102_descricao", 57, 0, true, 'text', 1, "style='width: 100%'; ");
          ?>
        <td>
		  </tr>

       <tr>
        <td align="left"><strong>Tipo de Folha:</strong>
        </td>
        <td align="left">
          <?php
          
          $arr_pontos = Array("0" =>"Salário",
                              "1" =>"Adiantamento",
                              "3" =>"Rescisão",
                              "4" =>"Saldo do 13o",
                              "5" =>"Complementar",
                              "6" =>"Suplementar"
                             );
      try {
          $oCompetencia         = new DBCompetencia($anofolha, $mesfolha);
  
          /**
          * Valida se a variável está ativa no db_conn, estando ativa, valida-se se a folha está aberta, caso esteja aberta, 
          * não é possível fazer a geração para aquela folha, entao retiramos ela do select. E verifica-se também se
          * existe uma folha, caso não exista, ele retira também do select a folha.
          */

         if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    
           if( (FolhaPagamentoSalario::hasFolhaAberta(DBPessoal::getCompetenciaFolha()) && FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO)) 
                || !FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO) ) {
             unset($arr_pontos['0']);
           }
           if ( (FolhaPagamentoComplementar::hasFolhaAberta(DBPessoal::getCompetenciaFolha()) && FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR)) 
                || !FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR) ) {
             unset($arr_pontos['5']);
           }
           if ( (FolhaPagamentoSuplementar::hasFolhaAberta(DBPessoal::getCompetenciaFolha()) && FolhaPagamento::getFolhaCompetenciaTipo( $oCompetencia, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR)) 
                || !FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR) ) {
             unset($arr_pontos['6']);
           }
    
          } else if ( !DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {
             unset($arr_pontos['6']);
          }
       
       } catch (Exception $eException) {
         db_msgbox($eException->getMessage());
       }
            db_select("folhaselecion",$arr_pontos,true,$db_opcao, "onchange='js_combolista(this.value);'");
          ?>
        <td>
      </tr>
		    <?php
			    $arr_pontosgerfs_inicial = Array();
			    $arr_pontosgerfs_final   = Array();

			    if(isset($objeto1)){
			      foreach ($objeto1 as $index) {
			        $arr_pontosgerfs_inicial[$index] = $arr_pontos[$index];
			      }
			    }else{
			      $arr_pontosgerfs_inicial = $arr_pontos;
			    }
			    if(isset($objeto2)){
			      foreach ($objeto2 as $index) {
			        $arr_pontosgerfs_final[$index] = $arr_pontos[$index];
			        unset($arr_pontosgerfs_inicial[$index]);
			      }
			    }
		    ?>
  		  <tr style="display: none;" id="ComboContainer">
          <td align='left' title='Nro. Complementar'>
            <strong>Número:</strong>
          </td>
          <td id="ComboContent">
          </td>
        </tr>
		  </table>
      </fieldset>

		  <fieldset>
		  <legend><strong>Filtros para Pagamento: </strong></legend>

			  <table>
				  <tr>
				    <td><strong> Valor Líquido Total de: </strong></td>
				    <td>
				    <?php
				    	$liquido1 = '0';
					    db_input("labelValorLiquido", 15, $IlabelValorLiquido, true, 'text', 1, '', "liquido1");
					    echo "<strong>até</strong>";
					    $liquido2 = '999999999999';
					    db_input("labelValorLiquido", 15, $IlabelValorLiquido, true, 'text', 1, '', "liquido2");
				    ?>
				    </td>
				  </tr>
				  <tr>
				    <td>
				      <strong>Incluir Pagamento de Saldo:</strong>
				    </td>
				    <td nowrap align="left">
				    <?php
					    $pagtosaldo = "f";
					    $arr_truefalse = array('f'=>'Não','t'=>'Sim');
					    db_select("pagtosaldo",$arr_truefalse,true,1,"onchange='js_verificacampos(this.name);'");
				    ?>
				    </td>
				  </tr>
				  <tr>
				    <td><?=$LlabelPercentualPago?></td>
				    <td>
				    <?php
              db_input("labelPercentualPago", 
              		     3, 
              		     $IlabelPercentualPago, 
              		     true, 
              		     'text', 
              		     1,
              		     "onKeyUp='js_validaporcentagem(this);' 
              		      onchange='js_verificacampos(this.name);' 
              		      onPaste='js_validapaste(this);'", 
              		     "percpago");
             ?>%</td>
				  </tr>
				  <tr>
				    <td><strong>Faixa Líquida a Pagar (até): </strong></td>
				    <td nowrap align="left">
				    <?php
					    db_input("labelFaixaValorLiquido", 
					    		     15, 
					    		     $IlabelFaixaValorLiquido, 
					    		     true, 
					    		     'text', 
					    		     1, 
					    		     "onchange='js_verificacampos(this.name);'", 
					    		     "pagarliq");
					    echo "<strong>ou&nbsp;</strong>";
					    $pagarperc = '100';
					    db_input("labelFaixaValorLiquido", 
					    		     3, 
					    		     $IlabelFaixaValorLiquido, 
					    		     true, 
					    		     'text', 
					    		     1, 
					    		     "onKeyUp='js_validaporcentagem(this);' 
					    		      onchange='js_verificacampos(this.name);' 
					    		      onPaste='js_validapaste(this);'",  
					    		     "pagarperc");
				    ?>
				    <strong>%</strong>
				    </td>
				  </tr>
          <tr>
            <td >
              <strong>Mostrar Servidores:</strong>
            </td>
            <td nowrap align="left">
            <?php
              db_select("bMostraServidores",array('f'=>'Não', 't'=>'Sim'), true, 1, "onchange='js_verificacampos(this.name);'");
            ?>
            </td>
          </tr>
          <input type="hidden" id ="valorInformado" name="valorInformado" value="">
				</table>
		</fieldset>
		<input name="incluir" type="submit" id="db_opcao" value="Processar" onclick="return js_enviardados();"/>
	</form>
<script src="scripts/classes/DBViewFormularioFolha/ComboListaFolha.js"></script>
<script>

(function() {
  $('mesfolha').setAttribute("onblur", "js_combolista();");
  $('anofolha').setAttribute("onblur", "js_combolista();");

  document.getElementById('anofolha').addEventListener('change', function() {
    window.location = 'pes4_rhgeracaofolha001.php?anofolha='+this.value;
  });

  document.getElementById('mesfolha').addEventListener('change', function() {
    window.location = 'pes4_rhgeracaofolha001.php?mesfolha='+this.value;
  });
})();

function js_combolista(iTipoFolha) {

  // Remove todo conteudo da DIV
  $('ComboContent').innerHTML = '';
      $('ComboContainer').style.display = 'none';

  // Cria uma objeto ComboListaFolha
  var oComboLista = new DBViewFormularioFolha.ComboListaFolha();

  // Pega o valor dos meses
  var iMes = $F('mesfolha');
  var iAno = $F('anofolha');

  // Se não for passado tipo da folha, força a entrada
  if (iTipoFolha == undefined) {
    iTipoFolha = $F('folhaselecion');
  }

  // Verifica se são as rotinas de complementar ou suplementar
  if (iTipoFolha == 5 || iTipoFolha == 6) {

    // Coloca o código real da folha
    if (iTipoFolha == 5) {
      iTipoFolha = 3;
    }
    var oComboBox = oComboLista.pesquisarFolhas(iTipoFolha, iAno, iMes, false);

    if (oComboBox.aItens.length > 0) {

    //  oComboBox.sStyle = "width: 75px;";
      oComboBox.show($('ComboContent'));
      $('ComboContainer').style.display = '';
    }
  }
}

/**
 * Busca Complementares por competência
 */

  function js_validapaste(objeto) {
	  
    return setTimeout(function() {
    	   js_validaporcentagem(objeto);
       }, 5);
  }

 var sUrl = 'pes1_rhempenhofolhaRPC.php';

 function js_retornoPontoComplementar(oAjax){

   js_removeObj("msgBox");

   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');

   if ( aRetorno.lErro ) {

     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   }

   var sLinha          = "";
   var iLinhasSemestre = aRetorno.aSemestre.length;

   if ( iLinhasSemestre > 0 ) {

     sLinha += " <td align='left' title='Nro. Complementar'>           ";
     sLinha += "   <strong>Nro. Complementar:</strong>                 ";
     sLinha += " </td>                                                 ";
     sLinha += " <td>                                                  ";
     sLinha += "   <select id='complementares' name='complementares'>  ";

     for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {
       with( aRetorno.aSemestre[iInd] ){
         sLinha += " <option value = '"+semestre+"'>"+semestre+"</option>";
       }
     }

     sLinha += " </td> ";

   } else {

     sLinha += " <td colspan='2' align='center'>                                ";
     sLinha += "   <font color='red'>Sem complementar para este período.</font> ";
     sLinha += " </td>                                                          ";
   }

   $('linhaComplementar').innerHTML     = sLinha;
   $('linhaComplementar').style.display = '';
 }

function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_folha','func_folha.php?funcao_js=parent.js_preenchepesquisa|r38_regist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_folha.hide();
  <?php
	  if($db_opcao!=1){
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	  }
  ?>
}

function js_verificacampos(campo){
  if(document.form1.pagarliq.value == "" && document.form1.pagarperc.value == ""){

    if(document.form1.pagtosaldo.selectedIndex == 1){

      document.form1.pagarliq.value     = "";
      document.form1.pagarperc.value    = "";
      document.form1.pagarliq.readOnly  = true;
      document.form1.pagarperc.readOnly = true;
      document.form1.percpago.readOnly  = false;
      document.form1.pagarliq.style.backgroundColor  = "#DEB887";
      document.form1.pagarperc.style.backgroundColor = "#DEB887";
      document.form1.percpago.style.backgroundColor  = "";
      js_tabulacaoforms("form1","percpago",true,1,"percpago",true);
    }else{
      $('valorInformado').value         = '';
      document.form1.pagarliq.value     = "";
      document.form1.pagarperc.value    = "";
      document.form1.percpago.value     = "";
      document.form1.pagarliq.readOnly  = false;
      document.form1.pagarperc.readOnly = false;
      document.form1.percpago.readOnly  = true;
      document.form1.pagarliq.style.backgroundColor  = "";
      document.form1.pagarperc.style.backgroundColor = "";
      document.form1.percpago.style.backgroundColor  = "#DEB887";
      if(campo == "pagarliq"){
        document.form1.pagarperc.focus();
      }else if(campo == "pagarperc" || campo == "percpago"){
        js_tabulacaoforms("form1","incluir",true,1,"incluir",true);
      }else{
        document.form1.pagarliq.focus();
      }
    }
    document.form1.pagtosaldo.disabled = false;
  } else {

    document.form1.pagtosaldo.options[0].selected   = true;
    document.form1.percpago.value      = "";

    document.form1.percpago.readOnly   = true;
    document.form1.pagtosaldo.disabled = true;

    document.form1.percpago.style.backgroundColor   = "#DEB887";

    if(document.form1.pagarliq.value != ""){

      document.form1.pagarperc.value    = "";
      document.form1.pagarliq.readOnly  = false;
      document.form1.pagarperc.readOnly = true;
      document.form1.pagarliq.style.backgroundColor  = "";
      document.form1.pagarperc.style.backgroundColor = "#DEB887";
      js_tabulacaoforms("form1","incluir",true,1,"incluir",true);

    } else if(document.form1.pagarperc.value != "") {

      document.form1.pagarliq.value    = "";
      document.form1.pagarperc.readOnly = false;
      document.form1.pagarliq.readOnly  = true;
      document.form1.pagarperc.style.backgroundColor = "";
      document.form1.pagarliq.style.backgroundColor  = "#DEB887";
      js_tabulacaoforms("form1","incluir",true,1,"incluir",true);

    }
  }
}

function js_enviardados() {

  var MENSAGEM    = 'recursoshumanos/pessoal/db_frmrhgeracaofolha.';
  var oRegex      = /^[0-9]+$/;
  var oRegexFloat = /(\d*[.])?\d+/;

  if (typeof document.form1.selorg !== 'undefined' && document.form1.selorg) {

    valores = '';
    virgula = '';
    for(i=0; i < document.form1.selorg.length; i++){
      valores+= virgula+document.form1.selorg.options[i].value;
      virgula = ',';
    }
    document.form1.faixa_orgao.value = valores;
    document.form1.selorg.selected = 0;
  } else if(typeof document.form1.sellotac !== 'undefined' && document.form1.sellotac) {

    valores = '';
    virgula = '';
    for(i=0; i < document.form1.sellotac.length; i++){
      valores+= virgula+"'"+document.form1.sellotac.options[i].value+"'";
      virgula = ',';
    }
    document.form1.faixa_lotac.value = valores;
    document.form1.sellotac.selected = 0;
  } else  if(typeof document.form1.selregist !== 'undefined' && document.form1.selregist) {

    valores = '';
    virgula = '';
    for(i=0; i < document.form1.selregist.length; i++){
      valores+= virgula+document.form1.selregist.options[i].value;
      virgula = ',';
    }
    document.form1.faixa_matricula.value = valores;
    document.form1.selregist.selected = 0;
  }

  if(document.form1.liquido1.value == "" || document.form1.liquido2.value == "") {

    alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: 'Faixa do Valor Líquido Total'}) );
    document.form1.liquido1.select();
    document.form1.liquido1.focus();
    return false;
  } else if(document.form1.pagtosaldo.selectedIndex == 1 && document.form1.percpago.value == "") {

    alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: 'Percentual Pago'}) );
    document.form1.percpago.select();
    document.form1.percpago.focus();
    return false;
  } else if(document.form1.pagtosaldo.selectedIndex == 0 && (document.form1.pagarliq.value == "" && document.form1.pagarperc.value == "")) {

    alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: 'Faixa do Valor Líquido a Pagar'}) );
    document.form1.pagarliq.focus();
    return false;
  } else if (document.form1.rh102_descricao.value == "") {

    alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: 'Descrição'}) );
    document.form1.rh102_descricao.select();
    document.form1.rh102_descricao.focus();
    return false;
  }

  if( document.getElementById('liquido1').value != '') {

    if ( !oRegexFloat.test( document.getElementById('liquido1').value ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Líquido Total'}) );
      document.getElementById('liquido1').value = '';
      return false;
    }
  }

  if( document.getElementById('liquido2').value != '') {

    if ( !oRegexFloat.test( document.getElementById('liquido2').value ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Líquido Total'}) );
      document.getElementById('liquido2').value = '';
      return false;
    }
  }

  if( document.form1.anofolha.value != '') {

    if ( !oRegex.test( document.form1.anofolha.value ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Ano'}) );
      document.form1.anofolha.value = '';
      return false;
    }
  }

  if( document.form1.mesfolha.value != '') {

    if ( !oRegex.test( document.form1.mesfolha.value ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Mês'}) );
      document.form1.mesfolha.value = '';
      return false;
    }

    if ( document.form1.mesfolha.value > 12 ) {

      alert( _M( MENSAGEM + 'mes_invalido' ) );
      document.form1.mesfolha.value = '';
      return false;
    }
  }

  if( document.form1.selecao.value != '') {

    if ( !oRegex.test( document.form1.selecao.value ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Seleção'}) );
      document.form1.selecao.value = '';
      return false;
    }
  }

  if( $F('liquido1') != '') {

    if ( !oRegexFloat.test( $F('liquido1') ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Valor Líquido Total'}) );
      $('liquido1').value = '';
      $('liquido1').focus();
      return false;
    }
  }

  if( $F('liquido2') != '') {

    if ( !oRegexFloat.test( $F('liquido2') ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Valor Líquido Total'}) );
      $('liquido2').value = '';
      $('liquido2').focus();
      return false;
    }
  }

  if( $F('pagarliq') != '') {

    if ( !oRegexFloat.test( $F('pagarliq') ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Faixa Líquida a Pagar'}) );
      $('pagarliq').value = '';
      $('valorInformado').value = '';
      $('pagarliq').focus();
      return false;
    }
    $('valorInformado').value = 't';
  }

  if( $F('percpago') != '') {

    if ( !oRegex.test( $F('percpago') ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: 'Percentual Pago'}) );
	    $('percpago').value = '';
	    $('percpago').focus();
	    return false;
	  }
  }

  if(document.form1.anofolha.value == ""){
    document.form1.anofolha.value = "<?=db_anofolha()?>";
  }

  if(document.form1.mesfolha.value == ""){
    document.form1.mesfolha.value = "<?=db_mesfolha()?>";
  }

  if(document.form1.bMostraServidores.value == 'f'){

    js_geraFolha();

    return false;
  } else {

	 	js_OpenJanelaIframe(  "CurrentWindow.corpo","db_iframe_selecionaservidores",
                          "pes4_rhgeracaofolha.php",
                          "Seleção de Servidores",
                          true
                       );
    return false;
  }

}

function js_validaporcentagem(objeto) {
	
  var MENSAGEM      = 'recursoshumanos/pessoal/db_frmrhgeracaofolha.';
  var oRegex        = /^[0-9]+$/;
  var sName         = "";
  var sValueDefault = "";
  
 if (objeto.name == "pagarperc") { 
   sName = "Faixa Líquida a Pagar (Percentual)";
   sValueDefault = "100"; 
 } else {
	 sName = "Percentual Pago";
	 sValueDefault = "";
 }
 	 
 if(objeto.value.length == 1 && objeto.value == '0' ){

     alert( _M( MENSAGEM + 'zero_invalido', {sCampo: sName}) );
     objeto.value = sValueDefault;
     objeto.focus();
     return false;
  }

  if( objeto.value != '') {

    if ( !oRegex.test( objeto.value ) ) {

      alert( _M( MENSAGEM + 'somente_numeros', {sCampo: sName}) );
      objeto.value = sValueDefault;
      objeto.focus();
      return false;
    }
  }

  if( objeto.value > 100) {

      alert( _M( MENSAGEM + 'quantia_invalida', {sCampo: sName}) );
      objeto.value = sValueDefault;
      objeto.focus();
      return false;
  }

}

function js_geraFolha(aMatriculas){

  var MENSAGEM = 'recursoshumanos/pessoal/db_frmrhgeracaofolha.';

  if(aMatriculas == null || aMatriculas == ""){
    aMatriculas = new Array();
  }

  var me                  = this;
  this.sRPC               = 'pes4_rhgeracaofolha.RPC.php';
  var oParam              = new Object();
  oParam.exec             = 'geraFolha';
  oParam.oDados           = new Object();
  oParam.oDados           = $('form1').serialize(true);
  oParam.aDadosServidores = aMatriculas;

  js_divCarregando( _M( MENSAGEM + 'carregando' ) , 'msgBox');
  var oAjax  = new Ajax.Request(me.sRPC,
                                {method: 'post',
                                 parameters: 'json='+Object.toJSON(oParam), 
                                 onComplete: function(oAjax) {

                                    var oRetorno = eval("("+oAjax.responseText+")");
                                    js_removeObj('msgBox');
                                    
                                    if (oRetorno.status== "2") {
                                      alert(oRetorno.message.urlDecode());
                                    } else {
                                        
                                      if (oRetorno.message != "") {
                                        alert(oRetorno.message.urlDecode());
                                      }
                                      
                                      if (oRetorno.relatorio_inconsistencias && oRetorno.relatorio_inconsistencias != "") {
                                        if (confirm("Deseja emitir o relatório de inconsistencias?")) {
                                      	  jan = window.open(oRetorno.relatorio_inconsistencias,'','width  =' + (screen.availWidth - 5)+',height =' + (screen.availHeight - 40)+',scrollbars=1,location=0');
                                          jan.moveTo(0, 0);  
                                        }                                                     
                                      }
                                    
                                      location.href = "pes4_rhgeracaofolha001.php";
                                      
                                    }
                                  
                                 }
  
                                })
}

js_verificacampos("");

</script>
