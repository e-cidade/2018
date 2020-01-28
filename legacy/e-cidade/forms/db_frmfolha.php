<?php
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

//MODULO: pessoal
$clfolha->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('r90_valor');
$clrotulo->label('r48_semest');
include(modification("classes/db_rhlota_classe.php"));
include(modification("dbforms/db_classesgenericas.php"));
$clrhlota = new cl_rhlota;
$oGeraFormulario = new cl_formulario_rel_pes;

if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
  $anofolha = db_anofolha();
}
if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
  $mesfolha = db_mesfolha();
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap colspan="2">
    <?php
		  $oGeraFormulario->selecao = true;

		  $oGeraFormulario->strngtipores = "glom"; // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
		  $oGeraFormulario->manomes = false;       // PARA NÃO MOSTRAR ANO E MES DE COMPETÊNCIA DA FOLHA

		  $oGeraFormulario->usalota = true; // PERMITIR SELEÇÃO DE LOTAÇÕES
		  $oGeraFormulario->usaorga = true; // PERMITIR SELEÇÃO DE LOTAÇÕES
		  $oGeraFormulario->usaregi = true; // PERMITIR SELEÇÃO DE LOTAÇÕES

		  $oGeraFormulario->lo1nome = "lotaci";       // NOME DO CAMPO DA LOTAÇÃO INICIAL
		  $oGeraFormulario->lo2nome = "lotacf";       // NOME DO CAMPO DA LOTAÇÃO FINAL
		  $oGeraFormulario->lo3nome = "sellotac";

		  $oGeraFormulario->re1nome = "registini";    // NOME DO CAMPO DA LOTAÇÃO INICIAL
		  $oGeraFormulario->re2nome = "registfim";    // NOME DO CAMPO DA LOTAÇÃO FINAL
		  $oGeraFormulario->re3nome = "selregist";

		  $oGeraFormulario->or1nome = "orgaoi";       // NOME DO CAMPO DO ÓRGÃO INICIAL
		  $oGeraFormulario->or2nome = "orgaof";       // NOME DO CAMPO DO ÓRGÃO FINAL
		  $oGeraFormulario->or3nome = "selorg";       // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS

		  $oGeraFormulario->trenome = "opcao_gml";    // NOME DO CAMPO TIPO DE RESUMO
		  $oGeraFormulario->tfinome = "opcao_filtro"; // NOME DO CAMPO TIPO DE FILTRO

		//  $oGeraFormulario->filtropadrao = "l";     // TIPO DE FILTRO PADRÃO
		  $oGeraFormulario->resumopadrao = "g";       // TIPO DE RESUMO PADRÃO

		  $oGeraFormulario->campo_auxilio_lota = "faixa_lotac";     // NOME DO DAS LOTAÇÕES SELECIONADAS
		  $oGeraFormulario->campo_auxilio_orga = "faixa_orgao";     // NOME DO DOS ÓRGÃOS SELECIONADOS
		  $oGeraFormulario->campo_auxilio_regi = "faixa_matricula"; // NOME DO DOS ÓRGÃOS SELECIONADOS

		  $oGeraFormulario->onchpad = true;                      // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
		  $oGeraFormulario->desabam = false;
		  $oGeraFormulario->manomes = true;
		  $oGeraFormulario->gera_form($anofolha,$mesfolha);
  	?>
</table>
</center>
<center>
<table border="0">
  <tr>
    <td nowrap colspan="2">
    <?php
	    db_input("folhaselecion", 3, 0, true, 'hidden', 3);
  
      $arr_pontosgerfs_inicial = Array();
      $arr_pontosgerfs_final   = Array();
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
       * não é possível fazer a geração para aquela folha, entao retiramos ela do multi-select. E verifica-se também se
       * existe uma folha, caso não exista, ele retira também do multi-select a folha.
       */
      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

        if ( !FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_SALARIO, $oCompetencia, false) ) {
          unset($arr_pontos['0']);
        }

        if ( !FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR, $oCompetencia, false) ) {
          unset($arr_pontos['5']);
        }

        if ( !FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR, $oCompetencia, false) ) {
          unset($arr_pontos['6']);
        }

      } else {
        unset($arr_pontos['6']);
      }

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
	      }
	    }

    } catch (Exception $eException) {
      db_msgbox($eException->getMessage());
    }
    
	    db_multiploselect("valor","descr", "", "", $arr_pontosgerfs_inicial, $arr_pontosgerfs_final, 6, 250, "", "", true, "js_complementar('c');");
	  ?>
    </td>
  </tr>
  <?php
  if(isset($arr_pontosgerfs_final[5])){
     
    $sSqlComplementar = $clgerfcom->sql_query_file($anofolha,$mesfolha,null,null,"distinct r48_semest as comp1,r48_semest as comp2");
    $result_gerfcom   = db_query($sSqlComplementar);

    if(!$result_gerfcom || pg_num_rows($result_gerfcom) == 0) {
      throw new DBException("Ocorreu um erro ao consultar a folha complementar.");
    }

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()){
      
      $oDaoRhFolhaPagamento = new cl_rhfolhapagamento();
      $sWhereFolhaPagamento = "rh141_anousu = {$anofolha} and rh141_mesusu = {$mesfolha} and rh141_tipofolha = 3";
      $sSqlComplementar     = $oDaoRhFolhaPagamento->sql_query_file(null, 'rh141_codigo, rh141_codigo', null, $sWhereFolhaPagamento);
      $result_gerfcom       = db_query($sSqlComplementar);

      if(!$result_gerfcom) {
        throw new DBException("Ocorreu um erro ao consultar a folha complementar.");
      }
    }

    if(pg_num_rows($result_gerfcom) > 0){
  ?>
  <tr>
    <td nowrap align="right" title="Número da complementar">
      <b>Nro. da complementar:</b>
    </td>
    <td nowrap align="left">
    <?php
	    $arr_todos = array(0=>"0",1=>"Todos ...");
	    $complementares = 0;
	    db_selectrecord("complementares", $result_gerfcom, true, $db_opcao,"","","",$arr_todos,"",1);
    ?>
    </td>
  </tr>
  <?php
    }else{
  ?>
  <tr>
    <td colspan="2" align="center">
      <font color="red">Sem complementar para este período.</font>
      <?php
	      $complementares = 0;
	      db_input("complementares", 2,0, true, 'hidden', 3);
      ?>
    </td>
  </tr>
  <?php
    }
  }
  ?>

  <?php
  if (isset($arr_pontosgerfs_final[6]) && DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
  
    $oDaoRhFolhaPagamento = new cl_rhfolhapagamento();
    $sWhereFolhaPagamento = "rh141_anousu = {$anofolha} and rh141_mesusu = {$mesfolha} and rh141_tipofolha = 6";
    $sSqlComplementar     = $oDaoRhFolhaPagamento->sql_query_file(null, 'rh141_codigo, rh141_codigo', null, $sWhereFolhaPagamento);
    $rsFolhaPagamento     = db_query($sSqlComplementar);

    if(!$rsFolhaPagamento)  {
      throw new DBException("Ocorreu um erro ao consultar a folha complementar.");
    }
    
    if(pg_num_rows($rsFolhaPagamento) > 0){
  ?>
  <tr>
    <td nowrap align="right" title="Número da complementar">
      <b>Nro. da suplementar:</b>
    </td>
    <td nowrap align="left">
    <?php
      $arr_todos = array(0=>"0",1=>"Todos ...");
      $complementares = 0;
      db_selectrecord("suplementares", $rsFolhaPagamento, true, $db_opcao,"","","",$arr_todos,"",1);
    ?>
    </td>
  </tr>
  <?php
    }else{
  ?>
  <tr>
    <td colspan="2" align="center">
      <font color="red">Sem suplementar para este período.</font>
      <?php
        $complementares = 0;
        db_input("suplementares", 2,0, true, 'hidden', 3);
      ?>
    </td>
  </tr>
  <?php
    }
  }
  ?>
  <tr>
    <td nowrap align="right">
      <b>Valor líquido total de:</b>
    </td>
    <td nowrap align="left">
    <?php
	    $liquido1 = '0';
	    db_input("r90_valor", 15, $Ir90_valor, true, 'text', 1, '', "liquido1");
    ?>
    <b>até</b>
    <?php
	    $liquido2 = '999999999999';
	    db_input("r90_valor", 15, $Ir90_valor, true, 'text', 1, '', "liquido2");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right">
      <b>Incluir pagamento de saldo:</b>
    </td>
    <td nowrap align="left">
    <?php
	    $pagtosaldo = "f";
	    $arr_truefalse = array('f'=>'Não','t'=>'Sim');
	    db_select("pagtosaldo",$arr_truefalse,true,1,"onchange='js_verificacampos(this.name);'");
	    echo str_repeat("&nbsp;",10);
    ?>
    <b>Percentual pago:</b>
    <?php
    	db_input("r90_valor", 3, $Ir90_valor, true, 'text', 1, "onchange='js_verificacampos(this.name);'", "percpago");
    ?>
    <b>%</b>
    </td>
  </tr>
  <tr>
    <td nowrap align="right">
      <b>Informar a faixa líquida a pagar (até):</b>
    </td>
    <td nowrap align="left">
    <?php
    	db_input("r90_valor", 15, $Ir90_valor, true, 'text', 1, "onchange='js_verificacampos(this.name);'", "pagarliq");
    ?>
    <b>ou&nbsp;</b>
    <?php
	    $pagarperc = '100';
	    db_input("r90_valor", 3, $Ir90_valor, true, 'text', 1, "onchange='js_verificacampos(this.name);'",  "pagarperc");
    ?>
    <b>%</b>
    </td>
  </tr>
</table>
</center>
<input name="incluir" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_enviardados();" onblur="js_tabulacaoforms('form1','anofolha',true,1,'anofolha',true);">
</form>
<script>

function js_complementar(opcao){

  x = document.form1;
  erro = 0;
  for (i=0; i<x.objeto2.length; i++) {

    if(x.objeto2.options[i].value == 5 || x.objeto2.options[i].value == 6){

      erro ++;
      break;
    }
  }

  if ((erro == 0 && x.complementares) || (erro == 0 && x.suplementares) || (erro > 0 && !x.complementares) || (erro > 0 && !x.suplementares) || opcao == 'am') {

    for(i=0; i<x.objeto1.length; i++){
      x.objeto1.options[i].selected = true;
    }

    for(i=0; i<x.objeto2.length; i++){
      x.objeto2.options[i].selected = true;
    }

    x.submit();
  }
}

function js_muda_anomes() {

  x = document.form1;
  erro = 0;
  for (i=0; i<x.objeto2.length; i++) {
    if (x.objeto2.options[i].value == 5) {

      erro ++;
      break;
    }
  }
  if(erro > 0){
    js_complementar('am');
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_folha','func_folha.php?funcao_js=parent.js_preenchepesquisa|r38_regist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_folha.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_verificacampos(campo){
  if(document.form1.pagarliq.value == "" && document.form1.pagarperc.value == ""){
    if (document.form1.pagtosaldo.selectedIndex == 1) {

      document.form1.pagarliq.value     = "";
      document.form1.pagarperc.value    = "";
      document.form1.pagarliq.readOnly  = true;
      document.form1.pagarperc.readOnly = true;
      document.form1.percpago.readOnly  = false;
      document.form1.pagarliq.style.backgroundColor  = "#DEB887";
      document.form1.pagarperc.style.backgroundColor = "#DEB887";
      document.form1.percpago.style.backgroundColor  = "";
      js_tabulacaoforms("form1","percpago",true,1,"percpago",true);
    } else {

      document.form1.pagarliq.value     = "";
      document.form1.pagarperc.value    = "";
      document.form1.percpago.value     = "";
      document.form1.pagarliq.readOnly  = false;
      document.form1.pagarperc.readOnly = false;
      document.form1.percpago.readOnly  = true;
      document.form1.pagarliq.style.backgroundColor  = "";
      document.form1.pagarperc.style.backgroundColor = "";
      document.form1.percpago.style.backgroundColor  = "#DEB887";

      if (campo == "pagarliq") {
        js_tabulacaoforms("form1","pagarperc",true,1,"pagarperc",true);
      } else if(campo == "pagarperc" || campo == "percpago") {
        js_tabulacaoforms("form1","incluir",true,1,"incluir",true);
      } else {
        js_tabulacaoforms("form1","pagarliq",true,1,"pagarliq",true);
      }
    }
    document.form1.pagtosaldo.disabled = false;
  }else{
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
    }else if(document.form1.pagarperc.value != ""){
      document.form1.pagarliq.value    = "";

      document.form1.pagarperc.readOnly = false;
      document.form1.pagarliq.readOnly  = true;

      document.form1.pagarperc.style.backgroundColor = "";
      document.form1.pagarliq.style.backgroundColor  = "#DEB887";
      js_tabulacaoforms("form1","incluir",true,1,"incluir",true);
    }
  }
}
function js_enviardados(){

  if(document.form1.selorg){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.selorg.length; i++){
      valores+= virgula+document.form1.selorg.options[i].value;
      virgula = ',';
    }
    document.form1.faixa_orgao.value = valores;
    document.form1.selorg.selected = 0;
  }else if(document.form1.sellotac){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.sellotac.length; i++){
      valores+= virgula+"'"+document.form1.sellotac.options[i].value+"'";
      virgula = ',';
    }
    document.form1.faixa_lotac.value = valores;
    document.form1.sellotac.selected = 0;
  } else  if(document.form1.selregist){

    valores = '';
    virgula = '';
    for(i=0; i < document.form1.selregist.length; i++){
      valores+= virgula+document.form1.selregist.options[i].value;
      virgula = ',';
    }
    document.form1.faixa_matricula.value = valores;
    document.form1.selregist.selected = 0;
  }
  stringretorno = "";
  virstrretorno = "";

  for(i=0;i<document.form1.objeto2.length;i++){
    stringretorno+= virstrretorno+document.form1.objeto2.options[i].value;
    virstrretorno = ",";
  }


  if(stringretorno == ""){
    alert("Selecione o(s) tipo(s) de folha.");
    return false;
  }else if(document.form1.liquido1.value == "" || document.form1.liquido2.value == ""){
    alert("Informe a faixa do valor líquido total.");
    document.form1.liquido1.select();
    document.form1.liquido1.focus();
    return false;
  }else if(document.form1.pagtosaldo.selectedIndex == 1 && document.form1.percpago.value == ""){
    alert("Informe o percentual pago.");
    document.form1.percpago.select();
    document.form1.percpago.focus();
    return false;
  }else if(document.form1.pagtosaldo.selectedIndex == 0 && (document.form1.pagarliq.value == "" && document.form1.pagarperc.value == "")){
    alert("Informe o a faixa do valor líquido a pagar.");
    document.form1.pagarliq.select();
    document.form1.pagarliq.focus();
    return false;
  }
  
  if(parseInt(document.form1.liquido1.value) < 0 || parseInt(document.form1.liquido2.value) < 0){
    alert('Informe valor maior ou igual a zero na faixa de valor líquido.');
    return false;
  }

  if(document.form1.anofolha.value == ""){
    document.form1.anofolha.value = "<?=db_anofolha()?>";
  }
  if(document.form1.mesfolha.value == ""){
    document.form1.mesfolha.value = "<?=db_mesfolha()?>";
  }

  document.form1.folhaselecion.value = stringretorno;
  return true;
}
js_verificacampos("");
</script>
