<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_rhlota_classe.php");
include("dbforms/db_classesgenericas.php");
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

		  $oGeraFormulario->strngtipores = "glom";              // OP��ES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
		  $oGeraFormulario->manomes = false;                     // PARA N�O MOSTRAR ANO E MES DE COMPET�NCIA DA FOLHA

		  $oGeraFormulario->usalota = true;                      // PERMITIR SELE��O DE LOTA��ES
		  $oGeraFormulario->usaorga = true;                      // PERMITIR SELE��O DE LOTA��ES
		  $oGeraFormulario->usaregi = true;                      // PERMITIR SELE��O DE LOTA��ES

		  $oGeraFormulario->lo1nome = "lotaci";                  // NOME DO CAMPO DA LOTA��O INICIAL
		  $oGeraFormulario->lo2nome = "lotacf";                  // NOME DO CAMPO DA LOTA��O FINAL
		  $oGeraFormulario->lo3nome = "sellotac";

		  $oGeraFormulario->re1nome = "registini";                  // NOME DO CAMPO DA LOTA��O INICIAL
		  $oGeraFormulario->re2nome = "registfim";                  // NOME DO CAMPO DA LOTA��O FINAL
		  $oGeraFormulario->re3nome = "selregist";

		  $oGeraFormulario->or1nome = "orgaoi";                  // NOME DO CAMPO DO �RG�O INICIAL
		  $oGeraFormulario->or2nome = "orgaof";                  // NOME DO CAMPO DO �RG�O FINAL
		  $oGeraFormulario->or3nome = "selorg";                  // NOME DO CAMPO DE SELE��O DE �RG�OS

		  $oGeraFormulario->trenome = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
		  $oGeraFormulario->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO

		//  $oGeraFormulario->filtropadrao = "l";                  // TIPO DE FILTRO PADR�O
		  $oGeraFormulario->resumopadrao = "g";                  // TIPO DE RESUMO PADR�O

		  $oGeraFormulario->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTA��ES SELECIONADAS
		  $oGeraFormulario->campo_auxilio_orga = "faixa_orgao";  // NOME DO DOS �RG�OS SELECIONADOS
		  $oGeraFormulario->campo_auxilio_regi = "faixa_matricula";  // NOME DO DOS �RG�OS SELECIONADOS

		  $oGeraFormulario->onchpad = true;                      // MUDAR AS OP��ES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
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
	    $arr_pontos = Array(
	                        "0" =>"Sal�rio",
	                        "1" =>"Adiantamento",
	                        "3" =>"Rescis�o",
	                        "4" =>"Saldo do 13o",
	                        "5" =>"Complementar"
	                       );
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
	    db_multiploselect("valor","descr", "", "", $arr_pontosgerfs_inicial, $arr_pontosgerfs_final, 6, 250, "", "", true, "js_complementar('c');");
	  ?>
    </td>
  </tr>
  <?php
  if(isset($arr_pontosgerfs_final[5])){
    $result_gerfcom = $clgerfcom->sql_record($clgerfcom->sql_query_file($anofolha,$mesfolha,null,null,"distinct r48_semest as comp1,r48_semest as comp2"));
    if($clgerfcom->numrows > 0){
  ?>
  <tr>
    <td nowrap align="right" title="N�mero da complementar">
      <b>Nro. da complementar:</b>
    </td>
    <td nowrap align="left">
    <?php
	    $arr_todos = array(0=>"0",1=>"Todos ...");
	    $complementares = 0;
	    db_selectrecord("complementares",$result_gerfcom,true,$db_opcao,"","","",$arr_todos,"",1);
    ?>
    </td>
  </tr>
  <?php
    }else{
  ?>
  <tr>
    <td colspan="2" align="center">
      <font color="red">Sem complementar para este per�odo.</font>
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
  <tr>
    <td nowrap align="right">
      <b>Valor l�quido total de:</b>
    </td>
    <td nowrap align="left">
    <?php
	    $liquido1 = '0';
	    db_input("r90_valor", 15, $Ir90_valor, true, 'text', 1, '', "liquido1");
    ?>
    <b>at�</b>
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
	    $arr_truefalse = array('f'=>'N�o','t'=>'Sim');
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
      <b>Informar a faixa l�quida a pagar (at�):</b>
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
  for(i=0; i<x.objeto2.length; i++){
    if(x.objeto2.options[i].value == 5){
      erro ++;
      break;
    }
  }
  if((erro == 0 && x.complementares) || (erro > 0 && !x.complementares) || opcao == 'am'){
    for(i=0; i<x.objeto1.length; i++){
      x.objeto1.options[i].selected = true;
    }
    for(i=0; i<x.objeto2.length; i++){
      x.objeto2.options[i].selected = true;
    }
    x.submit();
  }
}
function js_muda_anomes(){
  x = document.form1;
  erro = 0;
  for(i=0; i<x.objeto2.length; i++){
    if(x.objeto2.options[i].value == 5){
      erro ++;
      break;
    }
  }
  if(erro > 0){
    js_complementar('am');
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_folha','func_folha.php?funcao_js=parent.js_preenchepesquisa|r38_regist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_folha.hide();
  <?
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
        js_tabulacaoforms("form1","pagarperc",true,1,"pagarperc",true);
      }else if(campo == "pagarperc" || campo == "percpago"){
        js_tabulacaoforms("form1","incluir",true,1,"incluir",true);
      }else{
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
    alert("Informe a faixa do valor l�quido total.");
    document.form1.liquido1.select();
    document.form1.liquido1.focus();
    return false;
  }else if(document.form1.pagtosaldo.selectedIndex == 1 && document.form1.percpago.value == ""){
    alert("Informe o percentual pago.");
    document.form1.percpago.select();
    document.form1.percpago.focus();
    return false;
  }else if(document.form1.pagtosaldo.selectedIndex == 0 && (document.form1.pagarliq.value == "" && document.form1.pagarperc.value == "")){
    alert("Informe o a faixa do valor l�quido a pagar.");
    document.form1.pagarliq.select();
    document.form1.pagarliq.focus();
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