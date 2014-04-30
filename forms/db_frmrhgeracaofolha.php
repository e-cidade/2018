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
include("classes/db_rhlota_classe.php");
include("dbforms/db_classesgenericas.php");

$clrhgeracaofolha->rotulo->label();
$clrotulo     = new rotulocampo;
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


<center>
	<form name="form1" method="get" action="" id="form1">
		<fieldset>
		<legend><B>Dados da Folha</B></legend>
		<table border="0">
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
		    <td>
		      <B>Descrição</B>
		    </td>
		    <td align="left">
          <?php 
            db_input("rh102_descricao", 57, 0, true, 'text', 1, "style='width: 100%'; ");
          ?>
        <td>
		  </tr>
		</table>
		</fieldset>
		
		<fieldset>
		<legend><b>Tipo de Folha: </b></legend>
		<table border="0">
		  <tr>
		    <td nowrap colspan="2">
		    <?php
			    db_input("folhaselecion", 3, 0, true, 'hidden', 3);
			
			    $arr_pontosgerfs_inicial = Array();
			    $arr_pontosgerfs_final   = Array();
			    $arr_pontos = Array(
			                        "0" =>"Salário",
			                        "1" =>"Adiantamento",
			                        "2" =>"Férias",
			                        "3" =>"Rescisão",
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
		    <td  title="Número da complementar">
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
		      <font color="red">Sem complementar para este período.</font>
		      <?
			      $complementares = 0;
			      db_input("complementares", 2,0, true, 'hidden', 3);
		      ?>
		    </td>
		  </tr>
		  <?php
		    }
		  }
		  ?>
		  </table>
		  </fieldset>
		  
		  <fieldset>
		  <legend><b>Filtros para Pagamento: </b></legend>
		  
			  <table>
				  <tr>
				    <td>
				      <b>Valor líquido total de:</b>
				    </td>
				    <td>
				    <?php
				    	$liquido1 = '0';
					    db_input("r90_valor", 15, $Ir90_valor, true, 'text', 1, '', "liquido1");
					    echo "<b>Até</b>";
					    $liquido2 = '999999999999';
					    db_input("r90_valor", 15, $Ir90_valor, true, 'text', 1, '', "liquido2");
				    ?>
				    </td>
				  </tr>
				  <tr>
				    <td>
				      <b>Incluir pagamento de saldo:</b>
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
				    <td><b>Percentual pago:</b></td>
				    <td>
				    <?php
              db_input("r90_valor", 3, $Ir90_valor, true, 'text', 1, "onchange='js_verificacampos(this.name);'", "percpago");
             ?>%</td>
				  </tr>
				  <tr>
				    <td >
				      <b>Informar faixa líquida a pagar (até):</b>
				    </td>
				    <td nowrap align="left">
				    <?php
					    db_input("r90_valor", 15, $Ir90_valor, true, 'text', 1, "onchange='js_verificacampos(this.name);'", "pagarliq");
					    echo "<b>ou&nbsp;</b>";
					    $pagarperc = '100';
					    db_input("r90_valor", 3,  $Ir90_valor, true, 'text', 1, "onchange='js_verificacampos(this.name);'",  "pagarperc");
				    ?>
				    <b>%</b>
				    </td>
				  </tr>
          <tr>
            <td >
              <b>Mostra Servidores:</b>
            </td>
            <td nowrap align="left">
            <?php
              db_select("bMostraServidores",array('f'=>'Não', 't'=>'Sim'), true, 1, "onchange='js_verificacampos(this.name);'");
            ?>
            </td>
          </tr>
				</table>
		</fieldset>
		<input name="incluir" type="submit" id="db_opcao" value="Processar" onclick="return js_enviardados();" onblur="js_tabulacaoforms('form1','anofolha',true,1,'anofolha',true);">
	</form>
</center>
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


  if (stringretorno == "") {
  
    alert("Selecione o(s) tipo(s) de folha.");
    return false;
  } else if(document.form1.liquido1.value == "" || document.form1.liquido2.value == "") {
  
    alert("Informe a faixa do valor líquido total.");
    document.form1.liquido1.select();
    document.form1.liquido1.focus();
    return false;
  } else if(document.form1.pagtosaldo.selectedIndex == 1 && document.form1.percpago.value == "") {
  
    alert("Informe o percentual pago.");
    document.form1.percpago.select();
    document.form1.percpago.focus();
    return false;
  } else if(document.form1.pagtosaldo.selectedIndex == 0 && (document.form1.pagarliq.value == "" && document.form1.pagarperc.value == "")) {
  
    alert("Informe o a faixa do valor líquido a pagar.");
    document.form1.pagarliq.select();
    document.form1.pagarliq.focus();
    return false;
  } else if (document.form1.rh102_descricao.value == "") {
  
    alert("Informe a descrição da folha a ser gerada.");
    document.form1.rh102_descricao.select();
    document.form1.rh102_descricao.focus();
    return false;  
  }

  if(document.form1.anofolha.value == ""){
    document.form1.anofolha.value = "<?=db_anofolha()?>";
  }
  
  if(document.form1.mesfolha.value == ""){
    document.form1.mesfolha.value = "<?=db_mesfolha()?>";
  }

  document.form1.folhaselecion.value = stringretorno;
  
  if(document.form1.bMostraServidores.value == 'f'){
    js_geraFolha();
    
    return false;
  } else {

   js_OpenJanelaIframe(  "top.corpo","db_iframe_selecionaservidores",
                          "pes4_rhgeracaofolha.php?"+$('form1').serialize(),
                          "Seleção de Servidores",
                          true
                          
                       );
    return false;
  }

}

function js_geraFolha(aMatriculas){
  if(aMatriculas == null || aMatriculas == ""){
    aMatriculas = new Array();
  }
  var me              = this;
  this.sRPC           = 'pes4_rhgeracaofolha.RPC.php';
  var oParam          = new Object();
  oParam.exec         = 'geraFolha';
  oParam.oDados       = new Object();
  oParam.oDados       = $('form1').serialize(true);
  oParam.aDadosServidores = aMatriculas;

  js_divCarregando('Gerando Folha de Pagamento .....', 'msgBox');
  var oAjax  = new Ajax.Request(me.sRPC, 
                                {method: 'post',
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: function(oAjax) {
                                
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  js_removeObj('msgBox');
                                  if (oRetorno.status== "2") {
                                    alert(oRetorno.message.urlDecode());
                                  } else {
                                    if(oRetorno.message != ""){
                                      alert(oRetorno.message.urlDecode());
                                    }
                                    window.location = "";
                                  }
                                }
                               
                               })   
}





js_verificacampos("");
</script>