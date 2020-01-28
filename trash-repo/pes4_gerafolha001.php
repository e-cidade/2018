<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libpessoal.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<fieldset style="width: 60%; margin-top: 30px;">
<legend><b>Cálculo Financeiro</b></legend>
<table width="60%" border="0" cellspacing="4" cellpadding="0">
  <tr><td colspan="2">&nbsp;</td></tr>
  <!-- <form name="form1" method="post" action = "pes4_gerafolha002.php" target = 'IFdb_calculo'>-->
  <form name="form1" method="post"  >
  
  <input type="hidden" name="db_debug" value="false">
  <tr>
    <td align="right">
      <b>Tipo de folha:</b>
    </td>
    <td>
      <?
      $arr_tipofolha = Array("1"=>"Salário","2"=>"Adiantamento","3"=>"Férias","4"=>"Rescisão","5"=>"13o","8"=>"Complementar","10"=>"Fixo");
      db_select("opcao_geral", $arr_tipofolha, true, 1);
      ?>
    </td>
  </tr>
	<?
  if(!isset($opcao_gml)){
    $opcao_gml = "m";
  }
  if(!isset($opcao_filtro)){
    $opcao_filtro = "s";
  }

  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->manomes = false;                     // PARA NÃO MOSTRAR ANO E MES DE COMPETÊNCIA DA FOLHA

  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
  $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES

  $geraform->re1nome = "r110_regisi";             // NOME DO CAMPO DA MATRÍCULA INICIAL
  $geraform->re2nome = "r110_regisf";             // NOME DO CAMPO DA MATRÍCULA FINAL
	
  $geraform->lo1nome = "r110_lotaci";             // NOME DO CAMPO DA LOTAÇÃO INICIAL
  $geraform->lo2nome = "r110_lotacf";             // NOME DO CAMPO DA LOTAÇÃO FINAL

  $geraform->trenome = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO

  $geraform->filtropadrao = "s";                  // TIPO DE FILTRO PADRÃO
  $geraform->resumopadrao = "m";                  // TIPO DE RESUMO PADRÃO

  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS
  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS

  $geraform->strngtipores = "gml";                // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       m - Matrícula,
                                                  //                                       r - Resumo
  $geraform->onchpad      = true;                 // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form(null,null);
  ?>
  </table>
</fieldset>
  
  <table width="60%" border="0" cellspacing="4" cellpadding="0" style="margin-top: 10px;">
  <tr>
    <td colspan='2' align='center'>
      <input type="button" name="processar" value="Processar" onclick="return js_enviar_dados(1);">
      <? if (db_getsession("DB_login") == "dbseller") {
           echo "<input type=\"button\" value=\"Processar com Debug\" onclick=\"js_enviar_dados(2);\">";
         }  
      ?>   
      <input type="button" value ='Limpar' onclick="location.href='pes4_gerafolha001.php'" id='limpar' />
    </td>
  </tr>
  
  </table>
  
  </form>
</table>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>

var iMatricula = '';

function js_enviar_dados(tp){
  if(document.form1.selregist){
  
    valores = '';
    virgula = '';
    
    
    for(i = 0; i < document.form1.selregist.length; i++){
      valores+= virgula+document.form1.selregist.options[i].value;
      virgula = ',';
      if (i == 0) {
         iMatricula = 1;
      } else {
         iMatricula = 2;
      }
    }
    
    document.form1.faixa_regis.value = valores;
    document.form1.selregist.selected = 0;
    if (valores==""){
      alert('Selecione uma matrícula para processar!!');
      return false;
    }
  }else if(document.form1.sellotac){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.sellotac.length; i++){
      valores+= virgula+"'"+document.form1.sellotac.options[i].value+"'";
      virgula = ',';
    }
    document.form1.faixa_lotac.value = valores;
    document.form1.sellotac.selected = 0;
    if (valores==""){
      alert('Selecione uma lotação para processar!!');
      return false;
    }
  }
  if (document.form1.r110_regisi){
    if (document.form1.r110_regisi.value=="" && document.form1.r110_regisf.value==""){
      alert('Informe uma matrícula para processar!!');
      return false;
    }
  }else if (document.form1.r110_lotaci){
    if (document.form1.r110_lotaci.value=="" && document.form1.r110_lotacf.value==""){
      alert('Informe uma lotação para processar!!');
      return false;
    }
  }
  
  if (tp == 2) {
    document.form1.db_debug.value = "true";
  }
  
  document.form1.action = 'pes4_gerafolha002.php';
  

   js_OpenJanelaIframe(  "","db_calculo",
                          "",
                          "Cálculo Financeiro",
                          true,
                          50,     //top 
                          (document.width  - (document.width))/2,    //left
                          document.width,
                          document.height
                       );
  document.form1.target = 'IFdb_calculo';
  document.form1.submit();

  if (tp != 2 ) {
    if (iMatricula == 1 ){ 
      
      setTimeout("document.location.href='pes3_gerfinanc001.php?lConsulta=1&iMatricula='+$F('faixa_regis')", 2000);
      
    }
    if ($F('r110_regisi') == $F('r110_regisf') ){
    
      setTimeout("document.location.href='pes3_gerfinanc001.php?lConsulta=1&iMatricula='+$F('r110_regisi')", 2000);
    }
  }
}
function js_Limpa(){
}

/**
 * Verifica existência de lançamento de 13º ou provisão de férias
 * Caso exista, não deve deixar realizar novo processamento, sem haver estorno do lançamento
 */
function js_verificaExistenciaLancamento() {
   
  var iOpcao          = $F("opcao_geral");
  var sUrlRPC         = "pes4_rhgeracaofolha.RPC.php";
  
  var oParam              = new Object();
  oParam.exec             = "verificaExistenciaLancamentoFeriasDecimoTerceiro";
  js_divCarregando("Aguarde, pesquisando...", "msgBox");

  var oAjax = new Ajax.Request(sUrlRPC,
                                    {
                                    method:'post',
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete:js_validaExistenciaLancamento
                                });
}

function js_validaExistenciaLancamento(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.status == 2) {

    sStyle = "background-color:#DEB887;text-transform:uppercase;";

    /**
     * Desabilitando os campos da tela no caso de houver
     * lançamento de provisão de qualquer tipo
     */
    $('opcao_geral').setAttribute('disabled','true');
    $('opcao_geral').setAttribute('style',sStyle);

    $('opcao_gml').setAttribute('disabled','true');
    $('opcao_gml').setAttribute('style',sStyle);

    $('opcao_filtro').setAttribute('disabled','true');
    $('opcao_filtro').setAttribute('style',sStyle);
    
    $('rh01_regist').setAttribute('readonly','');
    $('rh01_regist').setAttribute('style',sStyle);

    $('selregist').setAttribute('readonly','');
    $('selregist').setAttribute('style',sStyle + "width:400px");

    /**
     * Pega todos os inputs de type=button
     */
    var aButtons  = $$("input[type=button]");

    /**
     * Pega todos os links da classe dbancora
     */
    var aAncoras  = $$("a.dbancora");

    aAncoras[0].setAttribute("onclick","");

    for(var i = 0; i < aButtons.length; i++) {
      
      aButtons[i].setAttribute("style", "display:none");
    }
    
    alert(oRetorno.message.urlDecode());
  }
}
   

js_verificaExistenciaLancamento();
js_trocacordeselect();
</script>
</html>