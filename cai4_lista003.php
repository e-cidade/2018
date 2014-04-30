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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_arretipo_classe.php");
require_once ('libs/db_utils.php');
require_once ("libs/db_libpostgres.php");

db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

//se esta no modulo agua, seleciona a funcao js que recupera valores da aba filtro imoveis, se não utiliza a funcao normalmente
//caso seja o modulo agua, a tela principal não mostrará o db_menu pq terá abas
$funcao_js_verifica = $db21_usasisagua == 't' ? 'js_verifica_agua()' : 'js_verifica()';
$monta_menu         = $db21_usasisagua == 't' ? false : true;

$clpostgresqlutils = new PostgreSQLUtils;
$clarretipo        = new cl_arretipo;
$clarretipo->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('k60_descr');
$clrotulo->label('k00_tipo');
$clrotulo->label('k00_descr');
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');

db_postmemory($HTTP_POST_VARS);

$instit = db_getsession("DB_instit");
if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox(_M('tributario.notificacoes.cai4_lista003.problema_indices_debitos'));
  $db_botao = false; 
  $db_opcao = 3;
} else {
  
  $db_botao = true;
  $db_opcao = 1;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>

function js_fimprocessamento(){
 top.corpo.db_iframe_lista002.hide();
 location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>';
}

function js_sobe() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
    var auxValue = F.options[SI].value;
    F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
    F.options[SI + 1] = new Option(auxText,auxValue);
    js_trocacordeselect();  
    F.options[SI].selected = true;
  }
}

function js_desce() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
    var auxValue = F.options[SI].value;
    F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
    F.options[SI - 1] = new Option(auxText,auxValue);
    js_trocacordeselect();
    F.options[SI].selected = true;  
  }
}

function js_excluir() {
  var F = document.getElementById("campos");
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
    js_trocacordeselect();
    if(SI <= (F.length - 1)) 
      F.options[SI].selected = true;  
  }
}

function js_insSelect() {
  var texto=document.form1.k00_descr.value;
  var valor=document.form1.k00_tipo.value;
  if(texto != "" && valor != ""){
    var F = document.getElementById("campos");
    var testa = false;

    for(var x = 0; x < F.length; x++){

      if(F.options[x].value == valor || F.options[x].text == texto){
        testa = true;
        break;
      }  
    } 
    if(testa == false){
      F.options[F.length] = new Option(texto,valor);
      js_trocacordeselect();
    } 
  }  
  texto=document.form1.k00_descr.value="";
  valor=document.form1.k00_tipo.value="";
  document.form1.lanca.onclick = '';
}

//funcao para criar notificao se estiver utilizando o modulo agua
function js_verifica_agua(){

  var val1 = new Number($F('DBtxt10'));
  var val2 = new Number($F('DBtxt11'));

  var nomelista = $F('k60_descr');

  var oComboSituacao        = parent.iframe_filtroimoveis.document.getElementById('db_situacaocorte');
  var oComboZonaEntrega     = parent.iframe_filtroimoveis.document.getElementById('db_zonaentrega');
  var oComboCaracteristicas = parent.iframe_filtroimoveis.document.getElementById('db_caracteristica');
  var oComboRuas            = parent.iframe_filtroimoveis.document.getElementById('db_ruas');

  var sComSemSituacoes      = parent.iframe_filtroimoveis.document.getElementById('situacaocorte').value;
  var sComSemZonaEntrega    = parent.iframe_filtroimoveis.document.getElementById('zonaentrega').value;
  var sComSemCaracteristica = parent.iframe_filtroimoveis.document.getElementById('caracteristica').value;
  var sComSemRuas           = parent.iframe_filtroimoveis.document.getElementById('logradouro').value;
  var sMatBaixadas          = parent.iframe_filtroimoveis.document.getElementById('matriculasbaixadas').value;
  var sTerrenos             = parent.iframe_filtroimoveis.document.getElementById('terrenos').value;
  
  var lExisteFiltro  = false;

  if (nomelista==""){
    alert(_M('tributario.notificacoes.cai4_lista003.informe_descricao'));
    document.form1.k60_descr.focus();
    return false;
  }

  if(val1.valueOf() >= val2.valueOf()){
    alert(_M('tributario.notificacoes.cai4_lista003.valor_maximo_menor_valor_minimo'));
    document.form1.DBtxt11.focus();
    return false;
  }

  qry  = '?sDescricaoLista='+$F('k60_descr');
  qry += '&dDataDebitos='+$F('data');
  qry += '&iQtdListar='+$F('numerolista22');
  qry += '&nValorIni='+$F('DBtxt10');
  qry += '&nValorFim='+$F('DBtxt11');
  qry += '&sTipoLista='+$F('k60_tipo');
  qry += '&sTipoListaDescr='+$('k60_tipo').options[$('k60_tipo').selectedIndex].text;
  qry += '&dNotifDataLimite='+$F('data1');
  qry += '&iNotifTipo='+$F('notiftipo');
  qry += '&sNotifTipo='+$('notiftipo').options[$('notiftipo').selectedIndex].text;
  qry += '&sMassaFalida='+$F('massa');
  qry += '&sLoteamento='+$F('loteamento');
  qry += '&dDtOperIni='+$F('dtini');
  qry += '&dDtOperFim='+$F('dtfim');
  qry += '&dDtVencIni='+$F('dataini');
  qry += '&dDtVencFim='+$F('datafim');
  qry += '&iExercIni='+$F('exercini');
  qry += '&iExercFim='+$F('exercfim');
  qry += '&iIgnoraExercIni='+$F('desconexercini');
  qry += '&iIgnoraExercFim='+$F('desconexercfim');
  qry += '&iQtdParcAtrasoIni='+$F('parcini');
  qry += '&iQtdParcAtrasoFim='+$F('parcfim');
  qry += '&iNroParcAtrasoIni='+$F('numini');
  qry += '&iNroParcAtrasoFim='+$F('numfim');
  qry += '&sConsideraPosterior='+$F('considerar');
  qry += '&iOpcaoTipoDebito='+$F('opcaofiltro');
  qry += '&sOpcaoTipoDebito='+$('opcaofiltro').options[$('opcaofiltro').selectedIndex].text;
  qry += '&baixadas='+sMatBaixadas;
  qry += '&terrenos='+sTerrenos;

  sTipoDebitos = '';
  sVirgula = '';
  if($('campos').options.length > 0){
    for(var i = 0; i < $('campos').options.length; i++) {
      sTipoDebitos += sVirgula + $('campos').options[i].value;
      sVirgula = ',';
    }
  }
  qry += '&sTiposDebitos='+sTipoDebitos;

  /*caso utilize o modulo agua libera aba para opçoes de filtro de imoveis*/
  var virgula   = '';
  var situacoes = '';
  for(var a = 0; a < oComboSituacao.length; a++) {
    situacoes += virgula+oComboSituacao.options[a].value;
    virgula    = ','; 
    lExisteFiltro  = true;
  }
  qry += '&situacao='+sComSemSituacoes+'&situacoes='+situacoes;

  virgula   = '';
  var zonas = '';
  for(var b = 0; b < oComboZonaEntrega.length; b++) {
    zonas   += virgula+oComboZonaEntrega.options[b].value;
    virgula = ',';
    lExisteFiltro  = true;
  }
  qry += '&zona='+sComSemZonaEntrega+'&zonas='+zonas;

  virgula = '';
  var caracteristicas = '';
  for(var c = 0; c < oComboCaracteristicas.length; c++) {
    caracteristicas += virgula+oComboCaracteristicas.options[c].value;
    virgula = ',';
    lExisteFiltro  = true;
  }
  qry += '&caracteristica='+sComSemCaracteristica+'&caracteristicas='+caracteristicas;  

  virgula = '';
  var ruas = '';
  for(var d = 0; d < oComboRuas.length; d++) {
    ruas += virgula+oComboRuas.options[d].value;
    virgula = ',';
    lExisteFiltro  = true;
  }
  qry += '&rua='+sComSemRuas+'&ruas='+ruas;

  if(lExisteFiltro) {
	  if($F('k60_tipo') != 'M') {
		  alert(_M('tributario.notificacoes.cai4_lista003.filtro_utilizados_caso_diferente_matricula'));
		  document.form1.k60_tipo.focus();
		  return false;
	  }
  }
  
  js_OpenJanelaIframe('','db_iframe_lista002','cai4_lista002.php'+qry,'Processando Lista ...',true);

  return true;

}



function js_verifica(){

  var val1 = new Number($F('DBtxt10'));
  var val2 = new Number($F('DBtxt11'));

  var nomelista = $F('k60_descr');

  if (nomelista==""){
    alert(_M('tributario.notificacoes.cai4_lista003.informe_descricao'));
    document.form1.k60_descr.focus();
    return false;
  }

  if(val1.valueOf() >= val2.valueOf()){
    alert(_M('tributario.notificacoes.cai4_lista003.valor_maximo_menor_valor_minimo'));
    document.form1.DBtxt11.focus();
    return false;
  }
  
  
  
  qry  = '?sDescricaoLista='+$F('k60_descr');
  qry += '&dDataDebitos='+$F('data');
  qry += '&iQtdListar='+$F('numerolista22');
  qry += '&nValorIni='+$F('DBtxt10');
  qry += '&nValorFim='+$F('DBtxt11');
  qry += '&sTipoLista='+$F('k60_tipo');
  qry += '&sTipoListaDescr='+$('k60_tipo').options[$('k60_tipo').selectedIndex].text;
  qry += '&dNotifDataLimite='+$F('data1');
  qry += '&iNotifTipo='+$F('notiftipo');
  qry += '&sNotifTipo='+$('notiftipo').options[$('notiftipo').selectedIndex].text;
  qry += '&sMassaFalida='+$F('massa');
  qry += '&sLoteamento='+$F('loteamento');
  qry += '&dDtOperIni='+$F('dtini');
  qry += '&dDtOperFim='+$F('dtfim');
  qry += '&dDtVencIni='+$F('dataini');
  qry += '&dDtVencFim='+$F('datafim');
  qry += '&iExercIni='+$F('exercini');
  qry += '&iExercFim='+$F('exercfim');
  qry += '&iIgnoraExercIni='+$F('desconexercini');
  qry += '&iIgnoraExercFim='+$F('desconexercfim');
  qry += '&iQtdParcAtrasoIni='+$F('parcini');
  qry += '&iQtdParcAtrasoFim='+$F('parcfim');
  qry += '&iNroParcAtrasoIni='+$F('numini');
  qry += '&iNroParcAtrasoFim='+$F('numfim');
  qry += '&sConsideraPosterior='+$F('considerar');
  qry += '&iOpcaoTipoDebito='+$F('opcaofiltro');
  qry += '&sOpcaoTipoDebito='+$('opcaofiltro').options[$('opcaofiltro').selectedIndex].text;
  qry += '&dtDesconsiderarDebitos='+$F('dtDesconsiderarDebitos');
  qry += parent.iframe_filtros.js_getContribuinte();
  qry += parent.iframe_filtros.js_getRuas();
  qry += parent.iframe_filtros.js_getBairros();
  qry += parent.iframe_filtros.js_getZonas();

  sTipoDebitos = '';
  sVirgula = '';
  if($('campos').options.length > 0){
    for(var i = 0; i < $('campos').options.length; i++) {
      sTipoDebitos += sVirgula + $('campos').options[i].value;
      sVirgula = ',';
    }
  }

  qry += '&sTiposDebitos='+sTipoDebitos;

  js_OpenJanelaIframe('','db_iframe_lista002','cai4_lista002.php'+qry,'Processando Lista ...',true);


  return true;

}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_montaFiltro();a=1" bgcolor="#cccccc">

  <table  border="0" align="center">
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>

<!-- > Inicio fieldset 1 <-->
<tr>
<td colspan="2">
<table width="970" border="0" align="center">
  <tr> 
    <td >&nbsp;</td>
  </tr>
  <tr>
  <td>
  <fieldset>
    <Legend align="left">
    <b>Dados da Lista</b>
    </Legend>
    <table border="0" width="100%"  align="center">
    <tr><td>
    <table border="0"  width="100%" align="left">
     <tr>
        <td width="24%" nowrap title="<?=@$Tk60_descr?>">
          <b>Descrição:</b>
        </td>
        <td >
          <?
          if($db_opcao == 1) {
            $xopcao = 3;
          } else {
            $xopcao = $db_opcao;
          } 
          db_input('k60_descr', 85, $Ik60_descr, true, 'text', $db_opcao, "");
           ?>
        </td>
      </tr>
</table>
</td>
</tr>

<tr><td>
<table border="0" width="100%" >
      <tr>
        <td title="Data da Geração da tabela débitos"><strong>Data Débitos :</strong>&nbsp;&nbsp;
        </td><td>
         <?
            $sql = "select k115_data as k22_data from datadebitos where k115_instit = ".db_getsession("DB_instit")."order by k115_data desc limit 1";
            $result = db_query($sql);
            if (pg_numrows($result) > 0) {
              db_fieldsmemory($result, 0);
              $data_ano = substr($k22_data, 0, 4);
              $data_mes = substr($k22_data, 5, 2);
              $data_dia = substr($k22_data, 8, 2);
            } else {
              $data_ano = '';
              $data_mes = '';
              $data_dia = '';
            }
            db_inputdata('data', $data_dia, $data_mes, $data_ano, true, 'text', $db_opcao)
            ?>
        </td>
        <td title="Quantidade de contribuintes a ser listado, ou zero para todos"><strong>Quantidade a Listar :</strong>&nbsp;&nbsp; 
        </td><td>
          <?
            db_input('numerolista22', 23, '', true, 'text', $db_opcao, "");
          ?>
        </td>
        
      </tr>
      <tr> 
        <td title="Intervalo de valores a serem listados"><strong>Valores :</strong>&nbsp;&nbsp;
        </td><td>
          <?
           db_input('DBtxt10', 10, $IDBtxt10, true, 'text', $db_opcao);
          ?>
          &nbsp;<b> à </b> &nbsp; 
          <?
          db_input('DBtxt11', 10, $IDBtxt11, true, 'text', $db_opcao);
          ?>
        </td>
        <td align="left" >
        <strong>Tipo de Lista :&nbsp;&nbsp;</strong>
        </td><td>
           <?
           $x = array("N"=>"Nome (  CGM Geral  )","C"=>"Somente por CGM","M"=>"Matrícula","I"=>"Inscrição");
           db_select('k60_tipo',$x,true,$db_opcao,"onchange='js_montaFiltro();'");
          ?>
        </td>
      </tr>
      <tr>
        <td title="Não lista os contribuintes notificados após esta data">
    <strong>Não Considerar Notificados Até:</strong>&nbsp;&nbsp;
    </td><td>
         <?


$data1_ano = substr(date('Y'), 0, 4);
$data1_mes = substr(date('m'),0,2); 
$data1_dia =  substr(date('d'),0,2); 
db_inputdata('data1', $data1_dia, $data1_mes, $data1_ano, true, 'text', $db_opcao);
$xx= array ("0" => "Geral", "1" => "Tipo de débito","2"=>"Numpre/Parcela");
    db_select('notiftipo', $xx, true, $db_opcao, "");

?>
        </td>
  <td><strong>Massa Falida :</strong>&nbsp;&nbsp;
        <?
    $x = array ("N" => "NÃO", "S" => "SIM");
    db_select('massa', $x, true, $db_opcao, "");
  ?>
  </td><td>
  <strong>Loteamentos:</strong>&nbsp;&nbsp;
        <?
    $x = array ("N" => "NÃO", "S" => "SIM");
    db_select('loteamento', $x, true, $db_opcao, "");
  ?>
  </td>
     </tr>

</table>
</td></tr>


  </table>
  </fieldset>
  </td>
  </tr>
  </table>
</td>
</tr>
<!-- fim primeiro fieldset 1 >  <-->
<tr>
</td>
<table border="0" width="1000" align="center">
<!-- > Inicio fieldset 2 <-->
<tr>
<td>
<table  border="0" align="center">
  <tr>
  <td>
  <fieldset>
    <Legend align="left">
    <b>Filtros</b>
    </Legend>
  <table border="0" align="center">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC" colspan="2">
    <br>
  <center>
    <table  height="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td nowrap title="Data de operação do débito">
          <b>Data de operação: </b>
        </td>
        <td>
        <?
         db_inputdata('dtini', "", "", "", true, 'text', $db_opcao, "")
        ?>
        </td>
        <td>
          <b> à </b>
          <?
           db_inputdata('dtfim', "", "", "", true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
         <td nowrap title="<?=@$Td40_codigo?>">
           <b>Data do Vencimento: </b>
         </td>
         <td>
         <?
           db_inputdata('dataini', "", "", "", true, 'text', $db_opcao, "")
         ?>
         </td>
         <td>
           <b> à </b>
           <?
             db_inputdata('datafim', "", "", "", true, 'text', $db_opcao, "")
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tk22_exerc?>">
           <b>Exercicio:</b>
         </td>
         <td>
           <?
             db_input('exercini', 10, "Exercicio inicial", true, 'text', $db_opcao);
           ?>
         </td>
         <td>
           <b> à </b>
           <?
             db_input('exercfim', 10, "Exercicio final", true, 'text', $db_opcao);
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tk22_exerc?>">
           <b>Desconsidera Exercícios:</b>
         </td>
         <td>
           <?
             db_input('desconexercini', 10, "Exercicio inicial", true, 'text', $db_opcao);
           ?>
         </td>
         <td>
           <b> à </b>
           <?
             db_input('desconexercfim', 10, "Exercicio final", true, 'text', $db_opcao);
           ?>
         </td>
       <tr>
         <td nowrap title="<?=@$Tk22_exerc?>">
           <b>Qtde de Parcelas em Atraso:</b>
         </td>
         <td>
           <?
            db_input('parcini', 10, "Parcela inicial", true, 'text', $db_opcao);
           ?>
         </td>
         <td>
           <b> à </b>
           <?
             db_input('parcfim', 10, "Parcela final", true, 'text', $db_opcao);
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tk22_exerc?>">
           <b>Número das Parcelas em Atraso:</b>
         </td>
         <td>
            <?
             db_input('numini', 10, "Número parcela inicial", true, 'text', $db_opcao);
            ?>
          </td>
          <td>
            <b> à </b>
            <?
              db_input('numfim', 10, "Número parcela final", true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tk22_exerc?>">
            <b>Considerar além dos filtros:</b>
          </td>
          <td colspan="2">
            <?
              $x = array ("N" => "NÃO", "S" => "SIM");
              db_select('considerar', $x, true, $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <b>Desconsiderar Débitos com recibo válido após:</b>
          </td>
          <td colspan="2">
            <?
              db_inputdata('dtDesconsiderarDebitos', "", "", "", true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>
      </table>
  <tr><td>&nbsp;<tr><td>
  </fieldset>
  </td>
  </tr>
  </table>

</td>
</tr>
<!-- fim primeiro fieldset 2 >  <-->
  
 </table>
</td><td>

<!-- > Inicio fieldset 3 <-->
<table border="0" align="center">
   <tr>
    <td nowrap title="Escolha os tipos de débitos a serem listados ou deixe em branco para listar todos" > 
      <fieldset><b><Legend>Tipos de Débito</legend></b>
      <table border="0">
          <tr>
          <td colspan=2 nowrap><b>Opção:&nbsp;</b>
           <?
             $aOpcaoFiltro = array("1" => "COM os selecionados",
                                   "2" => "SEM os selecionados");
             db_select('opcaofiltro',$aOpcaoFiltro,true,$db_opcao,"");
          ?>
        </td> 
          </tr>
         <tr>
           <td nowrap title="<?=@$Tk00_tipo?>" colspan="2">
            <?

 db_ancora(@ $Lk00_tipo, "js_pesquisak00_tipo(true);", $db_opcao);
?>
            <?


db_input('k00_tipo', 8, $Ik00_tipo, true, 'text', $db_opcao, " onchange='js_pesquisak00_tipo(false);'")
?>
            <?

 db_input('k00_descr', 25, $Ik00_descr, true, 'text', 3, '')
?>
      <input name="lanca" type="button" value="Lançar" >
           </td>
   </tr>  
         <tr>   
     <td align="right" colspan="" width="80%">
         
              <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
              <?

 if (isset ($chavepesquisa)) {

  $resulta = $clarretipo->sql_record($clarretipo->sql_query($chavepesquisa, "", "k00_tipo,k00_descr", ""));
  if ($clarretipo->numrows != 0) {
    $numrows = $clarretipo->numrows;
    for ($i = 0; $i < $numrows; $i ++) {
      db_fieldsmemory($resulta, $i);
      echo "<option value=\"$k00_tipo \">$k00_descr</option>";
    }

  }

}
?>  
        
             </select> 
     </td>
            <td align="left" valign="middle" width="20%"> 
      <img style="cursor:hand" onClick="js_sobe();return false" src="imagens/seta_up.gif" width="20" height="20" border="0">
              <br>
              <br>
              <img style="cursor:hand" onClick="js_desce()" src="imagens/seta_down.gif" width="20" height="20" border="0">
              <br>
              <br>
        <img style="cursor:hand" onClick="js_excluir()" src="imagens/bt_excluir.gif" width="20" height="20" border="0"> 
     </td>
         </tr>
      </table>
      </fieldset>
    </td>
  </tr>
<!-- fim primeiro fieldset 3 >  <-->
</td>
</tr>
</table>
      <tr height="40">
         <td align="center" colspan="2">
       <input name="db_opcao" type="button" id="db_opcao" value="Incluir" onClick="<?=$funcao_js_verifica?>" <?=($db_botao ? '' : 'disabled')?>>
   </td>
      </tr>
  </form>
</table>
<?
if($monta_menu) {
  //db_redireciona("cai4_listafiltros003.php?liberaaba=true");
 // db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
}
?>
</body>
</html>
<script>




function js_pesquisak00_tipo(mostra){
  document.form1.lanca.onclick = "";
  //parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k00_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.k00_tipo.focus(); 
    document.form1.k00_tipo.value = ''; 
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }  
  //parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;
  
}

function js_mostraarretipo1(chave1,chave2){
  document.form1.k00_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

function js_montaFiltro(){

  var sTipo   = $F('k60_tipo');
  
      parent.document.formaba.filtros.disabled = false;
      parent.iframe_filtros.location.href      = "cai4_listafiltros003.php?iFiltro="+sTipo+" ";
      
}
</script>