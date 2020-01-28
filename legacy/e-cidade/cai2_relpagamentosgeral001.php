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

require(modification("libs/db_app.utils.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));

$aux  = new cl_arquivo_auxiliar;
$aux1	= new cl_arquivo_auxiliar;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js,");
db_app::load("estilos.css");
?>


</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;" bgcolor="#cccccc">
<center>
<form name="form1" method="post">

<table border='0' style="margin-top: 20px;">
<tr>
<td>
<fieldset><legend><b>Relatório de Pagamentos</b></legend>
  <table>
    <tr>
      <td><b>Considerar Data:</b></td>
      <td colspan="3">
	      <?
	       $aSelect = array(1=>"Processamento", 2=>"Efetivo Pagamento");
	       db_select("cboData", $aSelect, true, 1, 'style="width:100%;"');
	      ?>
      </td>
    </tr>
    <tr>
      <td><b>Período:</b></td>
      <td>
      <?
        db_inputdata('dtini',null, null, null, true,'text',1,"");
      ?>
      </td>
      <td align="center"><b>até</b></td>
      <td>
      <?
        db_inputdata('dtfim',null, null, null, true,'text',1,"");
      ?>
      </td>
    </tr>
    <tr>
      <td><b>Valor Inicial:</b></td>
      <td>
      <?
        db_input('vlinicial', 10, "", true, 'text', 1, '', '', '', 'width:100%');
      ?>
      </td>
      <td><b>Valor Final:</b></td>
      <td>
      <?
        db_input('vlfinal', 10, "", true, 'text', 1, '', '', '', 'width:100%');
      ?>
      </td>
    </tr>
    <tr>
      <td><b>Agrupamento:</b></td>
      <td colspan="3">
        <?
         $aSelect = array(1=>"Tipo débito", 2=>"Receita", 3=>"Origem");
         db_select("cboAgrupamento", $aSelect, true, 1,  'onChange="js_validaOrdenacao();" style="width:100%;"');
        ?>
      </td>
    </tr>
    <tr id="trOrigem" style="display: none;">
      <td><b>Origem:</b></td>
      <td colspan="3">
        <?
         $aSelect = array(2=>"Matrícula", 3=>"Inscrição", 4=>"Somente CGM");
         db_select("cboOrigem", $aSelect, true, 1, 'onChange="js_validaOrdenacao();" style="width:100%;"');
        ?>
      </td>
    </tr>
    <tr>
      <td><b>Ordenação:</b></td>
      <td colspan="3">
        <?
         $aSelect = array(1=>"Tipo de Débito", 2=>"Valor");
         db_select("cboOrdenacao", $aSelect, true, 1,  'onChange="js_cboOrdenacao();" style="width:100%;"');
        ?>
        <?
         $aSelect = array(1=>"Crescente", 2=>"Decrescente");
         db_select("cboOrdenacaoValor", $aSelect, true, 1,  'style="width:30%;display:none;"');
        ?>
      </td>
    </tr>
    <tr>
      <td><b>Demonstração:</b></td>
      <td colspan="3">
        <?
         $aSelect = array(1=>"Ambos", 2=>"Imposto/Taxa", 3=>"Juro e Multa");
         db_select("cboDemonstracao", $aSelect, true, 1, 'style="width:100%;"');
        ?>
      </td>
    </tr>
    <tr>
      <td><b>Totalização:</b></td>
      <td colspan="3">
        <?
         $aSelect = array(1=>"Imprimir Dados e Totalizações", 2=>"Imprimir Somente Totalizações",
                          3=>"Imprimir Somente Dados");
         db_select("cboTotalizacao", $aSelect, true, 1, 'style="width:100%;"');
        ?>
      </td>
    </tr>
  </table>
</fieldset>
</td>
</tr>
<tr>
<td>

    <?
    // $aux = new cl_arquivo_auxiliar;
    $aux->cabecalho = "<b>Tipo de débito</b>";
    $aux->codigo = "k00_tipo"; //chave de retorno da func
    $aux->descr  = "k00_descr";   //chave de retorno
    $aux->nomeobjeto = 'arretipo';
    $aux->funcao_js = 'js_mostra_arretipo';
    $aux->funcao_js_hide = 'js_mostra_arretipo1';
    $aux->sql_exec  = "";
    $aux->func_arquivo = "func_arretipo.php";  //func a executar
    $aux->nomeiframe = "db_iframe_arretipo";
    $aux->localjan = "";
    $aux->onclick = "";
    $aux->db_opcao = 2;
    $aux->tipo = 2;
    $aux->top = 0;
    $aux->linhas = 5;
    $aux->vwidth = 430;
    $aux->nome_botao = 'db_lanca_tipo';
    $aux->funcao_gera_formulario();
    ?>

</td>
</tr>
<tr>
<td>

   <?
    // $aux = new cl_arquivo_auxiliar;
    $aux1->cabecalho = "<b>Receita</b>";
    $aux1->codigo = "k02_codigo"; //chave de retorno da func
    $aux1->descr  = "k02_descr";   //chave de retorno
    $aux1->nomeobjeto = 'receita';
    $aux1->funcao_js = 'js_mostra_receita';
    $aux1->funcao_js_hide = 'js_mostra_receita1';
    $aux1->sql_exec  = "";
    $aux1->func_arquivo = "func_tabrec.php";  //func a executar
    $aux1->nomeiframe = "db_iframe_tabrec";
    $aux1->localjan = "";
    $aux1->onclick = "";
    $aux1->db_opcao = 2;
    $aux1->tipo = 2;
    $aux1->top = 0;
    $aux1->linhas = 5;
    $aux1->vwidth = 430;
    $aux1->nome_botao = 'db_lanca_receita';
    $aux1->passar_query_string_para_func = "&lRelatorio=1";
    $aux1->funcao_gera_formulario();
    ?>

</td>
</tr>
</table>

<table>
  <tr>
    <td colspan="2" align="center">
      <input name="relatorio" type="button" onclick='js_abre();'  value="Imprimir">
    </td>
  </tr>
</table>
</form>

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script type="text/javascript">

function js_validaOrdenacao() {

  if ($F('cboAgrupamento') == 3) {
    $('trOrigem').style.display = "";
  } else {
    $('trOrigem').style.display = "none";
  }

  $('cboOrdenacao').style.width        = '100%';
  $('cboOrdenacaoValor').style.display = 'none';

  if ($F('cboAgrupamento') == 1 || $F('cboAgrupamento') == 2) {
    js_selectOrdenacao($F('cboAgrupamento'));
  } else if ($F('cboAgrupamento') == 3 && $F('cboOrigem') == 2) {
    js_selectOrdenacao('3');
  } else if ($F('cboAgrupamento') == 3 && $F('cboOrigem') == 3){
    js_selectOrdenacao('4');
  } else if ($F('cboAgrupamento') == 3 && ($F('cboOrigem') == 1 || $F('cboOrigem') == 4 )){
    js_selectOrdenacao('5');
  }
}

function js_selectOrdenacao(iCodigo){

  switch(iCodigo){

    case '1':

      $('cboOrdenacao').length = 0;
      var oOption              = document.createElement('option');
      oOption.text             = 'Tipo de Débito';
      oOption.value            = 1;
      $('cboOrdenacao').add(oOption,null);
      var oOption              = document.createElement('option');
      oOption.text             = 'Valor';
      oOption.value            = 2;
      $('cboOrdenacao').add(oOption,null);
      break;
    case '2':

      $('cboOrdenacao').length = 0;
      var oOption = document.createElement('option');
      oOption.text  = 'Receita';
      oOption.value = 1;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Valor';
      oOption.value = 2;
      $('cboOrdenacao').add(oOption,null);

      break;
    case '3':
      $('cboOrdenacao').length = 0;
      var oOption = document.createElement('option');
      oOption.text  = 'Matrícula';
      oOption.value = 1;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Nome Contribuinte';
      oOption.value = 2;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Tipo de Débito';
      oOption.value = 3;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Receita';
      oOption.value = 4;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Valor';
      oOption.value = 5;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');

      break;
    case '4':
      $('cboOrdenacao').length = 0;
      var oOption = document.createElement('option');
      oOption.text  = 'Inscrição';
      oOption.value = 1;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Nome Contribuinte';
      oOption.value = 2;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Tipo de Débito';
      oOption.value = 3;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Receita';
      oOption.value = 4;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Valor';
      oOption.value = 5;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');

      break;
    case '5':
      $('cboOrdenacao').length = 0;
      var oOption = document.createElement('option');
      oOption.text  = 'CGM';
      oOption.value = 1;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Tipo de Débito';
      oOption.value = 2;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Receita';
      oOption.value = 3;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      oOption.text  = 'Valor';
      oOption.value = 4;
      $('cboOrdenacao').add(oOption,null);
      var oOption = document.createElement('option');
      break;
  }
}

function js_limpaCbo(sNomeObjeto) {

  var iNumOptions = $(sNomeObjeto).length;
  $(sNomeObjeto).length = 0;
}

function js_cboOrdenacao(){

  if ($('cboOrdenacao').options[$('cboOrdenacao').selectedIndex].innerHTML == 'Valor') {

    $('cboOrdenacao').style.width = '69%';
    $('cboOrdenacaoValor').style.display = 'inline';

  } else {

    $('cboOrdenacao').style.width = '100%';
    $('cboOrdenacaoValor').style.display = 'none';
  }
}

function js_abre(){

    var sQuery = "";
    var sDtIni = "";
    var sDtFim = "";

    sDtIni = $F('dtini') != '' ? js_formatar($F('dtini'),'d') : '';
    sDtFim = $F('dtfim') != '' ? js_formatar($F('dtfim'),'d') : '';

    sQuery += "cboData="+$F('cboData');
    sQuery += "&cboAgrupamento="+$F('cboAgrupamento');
    sQuery += "&cboOrigem="+$F('cboOrigem');
    sQuery += "&cboOrdenacao="+$F('cboOrdenacao');
    sQuery += "&cboDemonstracao="+$F('cboDemonstracao');
    sQuery += "&cboTotalizacao="+$F('cboTotalizacao');
    sQuery += "&vlIni="+$F('vlinicial');
    sQuery += "&vlFim="+$F('vlfinal');
    sQuery += "&dtini="+sDtIni;
    sQuery += "&dtfim="+sDtFim;



    if  ($('cboOrdenacao').options[$('cboOrdenacao').selectedIndex].innerHTML == 'Valor') {
      sQuery += "&cboOrdenacaoValor="+$F('cboOrdenacaoValor');
    } else {
      sQuery += "&cboOrdenacaoValor=";
    }

    if ($('arretipo')) {
      //Le os itens lançados na combo arretipo
      var vir="";
      var sListaTipos="";

      for(var x = 0; x < $('arretipo').length; x++) {

        sListaTipos += vir + $('arretipo').options[x].value;
        vir=",";

      }
      if (sListaTipos != "") {
        sQuery +='&arretipo=('+sListaTipos+')';

      } else {
        sQuery +='&arretipo=';
      }
    }

    if ($('receita')) {
      //Le os itens lançados na combo receita
      var vir="";
      var sListaReceita="";

      for(var x = 0; x < $('receita').length; x++) {

        sListaReceita += vir + $('receita').options[x].value;
        vir=",";

      }
      if (sListaReceita != "") {
        sQuery +='&receita=('+sListaReceita+')';

      } else {
        sQuery +='&receita=';
      }
    }

    jan  = window.open('cai2_relpagamentosgeral002.php?'+sQuery,
                       '',
                       'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

}

</script>
</body>
</html>