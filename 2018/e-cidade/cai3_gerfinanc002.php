<?
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


require_once(modification("ext/php/adodb-time.inc.php"));
require_once(modification("libs/db_utils.php"));

set_time_limit(0);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//die($HTTP_SERVER_VARS['QUERY_STRING']);

//print_r($_GET);

//se for submit, ele cria o recibo
if(isset($HTTP_POST_VARS["ver_matric"]) && !isset($HTTP_POST_VARS["calculavalor"])) {
  global $HTTP_SESSION_VARS;
  if(isset($db_datausu) && !empty($db_datausu)){
    if($db_datausu == '--' || !checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))){
      echo "Data para cálculo inválida. <br><br>";
      echo "Data deverá se superior a : ".adodb_date('Y-m-d',$HTTP_SESSION_VARS["DB_datausu"]);
      //	 exit;
    }
    if(adodb_mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) <
    adodb_mktime(0,0,0,adodb_date('m',$HTTP_SESSION_VARS["DB_datausu"]),adodb_date('d',$HTTP_SESSION_VARS["DB_datausu"]),adodb_date('Y',$HTTP_SESSION_VARS["DB_datausu"])) ){
      echo "Data não permitida para cálculo. <br><br>";
      echo "Data deverá se superior a : ".adodb_date('Y-m-d',$HTTP_SESSION_VARS["DB_datausu"]);
      //	 exit;
    }
    $DB_DATACALC = adodb_mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
  }else{
    $DB_DATACALC = $HTTP_SESSION_VARS["DB_datausu"];
  }

if((isset($HTTP_POST_VARS["numpre_unica"]) && $HTTP_POST_VARS["numpre_unica"] != "" ) || ( isset($HTTP_POST_VARS["geracarne"]) && !isset($HTTP_POST_VARS["calculavalor"]))) {

  include(modification("cai3_gerfinanc033.php"));
  exit;
}

  include(modification("fpdf151/scpdf.php"));
  include(modification("cai3_gerfinanc003.php"));
  exit;

} else {
  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("libs/db_sql.php"));
  require_once(modification("dbforms/db_funcoes.php"));

  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_GET_VARS);

  db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");
  $db21_usasisagua = isset($db21_usasisagua) && $db21_usasisagua == 't';

  // variavel de controle para saber se deve ou não mostrar a coluna de contrato e economia do modulo agua
  $aguaColunaContrato = false;

  if ($db21_usasisagua === true) {
    $resultAguaConf = db_query("select x18_arretipo from aguaconf where x18_anousu = " . db_getsession("DB_anousu"));
    $x18_arretipo = $resultAguaConf && pg_num_rows($resultAguaConf) ? db_utils::fieldsMemory($resultAguaConf, 0)->x18_arretipo : null;
    // se o tipo de debito configurado na tabela aguaconf for igual ao tipo de debito selecionado, mostramos a coluna
    // esta validacao vem pelo motivo de que em 2017 mudou o tipo da receita, portanto a tabela da receita antiga
    // estaria mostrando as colunas indevidamente
    $aguaColunaContrato = isset($tipo) ? $tipo == $x18_arretipo : false;
  }

  if(isset($HTTP_POST_VARS["calculavalor"])) {

    $vt = $HTTP_POST_VARS;
    $tam = sizeof($vt);

    reset($vt);
    $j = 0;
    for($i = 0;$i < $tam;$i++) {
      if(db_indexOf(key($vt) ,"VAL_ISS") > 0)
      				//echo "xxx: " . $vt[key($vt)] . "\n";
      $valores[$j++] = $vt[key($vt)];
      next($vt);
    }
    $j = 0;
    reset($vt);
    for($i = 0;$i < $tam;$i++) {
      if(db_indexOf(key($vt),"CHECK") > 0){
        $numpres[$j++] = $vt[key($vt)];
      }
      next($vt);
    }

    if(isset($valores)){

      if(sizeof($valores) != sizeof($numpres)) {
        db_erro("Matriz inválida!",1);
      }

      $tam = sizeof($valores);
      // Removido inicio de trasacao com db query
      db_inicio_transacao();
      for($i = 0;$i < $tam;$i++) {
        $mat = split("P",$numpres[$i]);
        $numpre = $mat[0];
        $numpar = split("R", $mat[1]);
        $numpar = $numpar[0];
        $valores[$i] = $valores[$i] + 0;

        /**
         * Verifica se ja existe registro na issvar, se existir atualiza senão insere
         */
        $sWhere     = "q05_numpre = $numpre and q05_numpar = $numpar";
        $oDaoIssVar = db_utils::getDao('issvar');
        $sSqlIssVar = $oDaoIssVar->sql_query_file(null, 1, null, $sWhere);
        $rsIssVar   = db_query($sSqlIssVar);

        if (pg_num_rows($rsIssVar) > 0) {
          $sql = "update issvar set q05_vlrinf = ".$valores[$i]." where q05_numpre = $numpre and q05_numpar = $numpar";
        } else {
          $sql = "insert into issvar (q05_vlrinf, q05_numpre, q05_numpar) values({$valores[$i]},{$numpre},{$numpar})";
        }

        db_query($sql) or die("Erro(37) atualizando issvar: ".pg_errormessage());
      }
      // Removido fim de trasacao com db query
      db_fim_transacao(false);
    }

    $tipo = 3;
  }

  if(isset($db_datausu) && !empty($db_datausu)){
    if($db_datausu == '--' || !checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))){
      echo "Data para Cálculo Inválida. <br><br>";
      echo "Data deverá se superior a : ".adodb_date('Y-m-d',db_getsession("DB_datausu"));
      //	 exit;
    }
    if(adodb_mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) <
    adodb_mktime(0,0,0,adodb_date('m',db_getsession("DB_datausu")),adodb_date('d',db_getsession("DB_datausu")),adodb_date('Y',db_getsession("DB_datausu"))) ){
      echo "Data não permitida para cálculo. <br><br>";
      echo "Data deverá se superior a : ".adodb_date('Y-m-d',db_getsession("DB_datausu"));
      //	 exit;
    }
    $DB_DATACALC = adodb_mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
  }else{
    $DB_DATACALC = db_getsession("DB_datausu");
  }

if((isset($HTTP_POST_VARS["numpre_unica"]) && $HTTP_POST_VARS["numpre_unica"] != "" ) || ( isset($HTTP_POST_VARS["geracarne"]) && !isset($HTTP_POST_VARS["calculavalor"]))) {

	include(modification("cai3_gerfinanc033.php"));
  exit;
}


  ?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<script language="JavaScript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
  function js_desabilitaBotoes() {

    parent.document.getElementById("geranotif").disabled  	  = true; //botao Gerar Notificacao
    parent.document.getElementById("btnSuspender").disabled   = true; //botao Suspender
    parent.document.getElementById("btparc").disabled    	    = true; //botao Parcelamento
    parent.document.getElementById("btcda").disabled      	  = true; //botao Certidao
    parent.document.getElementById("btcancela").disabled  	  = true; //botao Cancelamento
    parent.document.getElementById("btnSuspender").disabled   = true; //botao Parcelamento
    parent.document.getElementById("btcarne").disabled    	  = true; //botao emite carne
    parent.document.getElementById("emisscarne").disabled 	  = true; //botao emite carne
    parent.document.getElementById("btimpdiverso").disabled   = true; //botao Imp. Divesos
    parent.document.getElementById("btjust").disabled         = true; //botao justifica
    parent.document.getElementById("btnotifica").disabled 	  = true;

    if ( parent.document.getElementById("enviar").value == 'Recibo'){
      parent.document.getElementById("enviar").disabled     = true; //botao Recibo
    }

  }

  function js_disabilitarFormEmissao() {

    var k00_formemissao = document.getElementById('k00_formemissao').value;

    parent.document.getElementById('impvalores').style.display = 'none';
    parent.document.getElementById('formemissao').value        = '1';
    if (k00_formemissao == 3) {

      parent.document.getElementById('impvalores').style.display = '';
      document.getElementById('k00_formemissao').value = parent.document.getElementById('formemissao').value;
    }
  }

  function js_controlaBotoes(form){

    F = form;
    var abilitabotoesUnica =  false;
    var emrec = '<?=$emrec?>';

    if(emrec=='t') {

      for(i = 0;i < F.elements.length;i++) {
        if(F.elements[i].type == "checkbox"){
          if( F.elements[i].checked == true ) {
            abilitabotoesUnica = true;
          }
        }
      }

      if(parent.document.getElementById("btcarne").disabled == true && abilitabotoesUnica == true){
        parent.document.getElementById("btcarne").disabled = false;
      }else{
        parent.document.getElementById("btcarne").disabled = true;
      }
    }
  }

  function js_emiteunica(numpre,datavenc,obj){

    var emrec = '<?=$emrec?>';

    if(emrec=='t') {
      if(parent.document.getElementById("btcarne").disabled == true && obj.checked == true){
        parent.document.getElementById("btcarne").disabled = false;
      }
    }

    var aDatasUnicasNovas   = Array();
    var aNumpresUnicasNovas = Array();
    var aDadosUnicas 	    = Array();
    var aDatasUnicas        = document.getElementsByTagName('input');



    for (iDatasUnicas = 0; iDatasUnicas < aDatasUnicas.length; iDatasUnicas++) {

      if ( aDatasUnicas[iDatasUnicas].name.substr(0,9) == 'dt_unica_') {
        var iContador = aDatasUnicas[iDatasUnicas].name.substr(9,aDatasUnicas[iDatasUnicas].name.length);
        var oCheck    = document.getElementById('check_id_'+iContador);
        if (oCheck.checked && js_search_in_array(aDatasUnicasNovas,aDatasUnicas[iDatasUnicas].value) == false) {
          aDatasUnicasNovas.push(aDatasUnicas[iDatasUnicas].value);
        }
      }

      if ( aDatasUnicas[iDatasUnicas].name.substr(0,9) == 'np_unica_') {
        var iContador = aDatasUnicas[iDatasUnicas].name.substr(9,aDatasUnicas[iDatasUnicas].name.length);
        var oCheck    = document.getElementById('check_id_'+iContador);
        if (oCheck.checked && js_search_in_array(aNumpresUnicasNovas,aDatasUnicas[iDatasUnicas].value) == false) {
          aNumpresUnicasNovas.push(aDatasUnicas[iDatasUnicas].value);
        }
      }

      if ( aDatasUnicas[iDatasUnicas].name.substr(0,11) == 'unica_np_dt') {
        var iContador = aDatasUnicas[iDatasUnicas].name.substr(11,aDatasUnicas[iDatasUnicas].name.length);
        var oCheck    = document.getElementById('check_id_'+iContador);
        if (oCheck.checked && js_search_in_array(aNumpresUnicasNovas,aDatasUnicas[iDatasUnicas].value) == false) {
          aDadosUnicas.push(aDatasUnicas[iDatasUnicas].value);
        }
      }


    }

    document.form1.numpre_unica.value 				= '';
    document.form1.DadosUnicas.value 			    = '';
    document.form1.txtNumpreUnicaSelecionados.value = '';

    var sNumpres = '';
    var sVir     = '';

    for (var iContDt = 0; iContDt < aNumpresUnicasNovas.length; iContDt++ ) {
      sNumpres += sVir+aNumpresUnicasNovas[iContDt];
      sVir = ',';
    }

    var sDatas = '';
    var sVir   = '';

    for (var iContDt = 0; iContDt < aDatasUnicasNovas.length; iContDt++ ) {
      sDatas += sVir+aDatasUnicasNovas[iContDt];
      sVir = ',';
    }

    var sDados = '';
    var sVir   = '';

    for (var iContDt = 0; iContDt < aDadosUnicas.length; iContDt++ ) {
      sDados += sVir+aDadosUnicas[iContDt];
      sVir = ',';
    }


    document.form1.txtNumpreUnicaSelecionados.value = sDatas;
    document.form1.numpre_unica.value			    = sNumpres;
    document.form1.DadosUnicas.value			    = sDados;

    var F = document.form1;
    var abilitabotoesUnica =  false;
    for(var i = 0;i < F.elements.length;i++) {
      if(F.elements[i].type == "checkbox"){
        if( F.elements[i].checked == true ) {
          abilitabotoesUnica = true;
        }
      }
    }

    if(abilitabotoesUnica == true){
      if(emrec=='t') {
        parent.document.getElementById("btcarne").disabled = false;
      }
    }else{
      parent.document.getElementById("btcarne").disabled = true;
    }
  }

  function js_enviarUnica(){
    var valorUnicas = document.form1.txtNumpreUnicaSelecionados.value;
    if (valorUnicas == '') {
      alert('Nenhum debito com cota unica selecionado !');
      return false;
    }
    jan = window.open('','reciboweb2','width=790,height=530,scrollbars=1,location=0');
    jan.moveTo(0,0);
    document.form1.submit();
    document.form1.numpre_unica.value = "";
  }


  function teclas(event){
    tecla = document.all ? event.keyCode : event.which;

    if (tecla > 47 && tecla < 58){
      return true;
    }else{
      if (tecla != 8 && tecla != 0 && tecla != 46 & tecla != 13){ // backspace
        return false;
      }else{
        return true;
      }
    }
  }

  function js_validatamanho(valor,btn,inputvalor){

    var nValorInteiro = valor.split(".");

    if (nValorInteiro[0].length > 8) {
      alert('Valor inválido. Verifique.');
      document.getElementById(btn).disabled = true;
      if(inputvalor != '') {
        document.getElementById(inputvalor).value = js_getInputValue(inputvalor);
      }
      return false;
    }
    if(inputvalor != '') {
      js_putInputValue(inputvalor, document.getElementById(inputvalor).value);
    }
    document.getElementById(btn).disabled = false;

    return true;
  }

  function js_soma1(obj,linha) {


    linha = ((typeof(linha)=="undefined") || (typeof(linha)=="object")?2:linha);
    var F = obj;
    var emrec = '<?=$emrec?>';
    var tab = document.getElementById('tabdebitos');
    if(emrec == 't'){
      parent.document.getElementById("enviar").disabled = false;//botao emite recibo
      if (document.form1.k03_permparc.value == 't') {
        parent.document.getElementById("btparc").disabled = false;//botao parcelamento
      }
      parent.document.getElementById("btcda").disabled = false;//botao certidao
      parent.document.getElementById("btnSuspender").disabled = false;//botao certidao
      parent.document.getElementById("btcancela").disabled = false;//botao certidao
      parent.document.getElementById("btcarne").disabled = false;//botao emite carne
      parent.document.getElementById("emisscarne").disabled = false;//botao emite carne
      parent.document.getElementById("btjust").disabled = false;//botao justifica
      parent.document.getElementById("btnotifica").disabled = false;


    }
    var indi = js_parse_int(obj.id);

    var valor     = parent.document.getElementById('valor'+linha).innerHTML;
    var valorcorr = parent.document.getElementById('valorcorr'+linha).innerHTML;
    var juros     = parent.document.getElementById('juros'+linha).innerHTML;
    var multa     = parent.document.getElementById('multa'+linha).innerHTML;
    var deconto   = parent.document.getElementById('desconto'+linha).innerHTML;
    var total     = parent.document.getElementById('total'+linha).innerHTML;

    if(obj.checked == true){
      valor     += new Number(document.getElementById('valor'+indi).value.replace(",",""));
      valorcorr += new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
      juros     += new Number(document.getElementById('juros'+indi).value.replace(",",""));
      multa     += new Number(document.getElementById('multa'+indi).value.replace(",",""));
      desconto  += new Number(document.getElementById('desconto'+indi).value.replace(",",""));
      total     += new Number(document.getElementById('total'+indi).value.replace(",",""));
    }else{
      valor     -= new Number(document.getElementById('valor'+indi).value.replace(",",""));
      valorcorr -= new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
      juros     -= new Number(document.getElementById('juros'+indi).value.replace(",",""));
      multa     -= new Number(document.getElementById('multa'+indi).value.replace(",",""));
      desconto  -= new Number(document.getElementById('desconto'+indi).value.replace(",",""));
      total     -= new Number(document.getElementById('total'+indi).value.replace(",",""));
    }

    parent.document.getElementById('valor'+linha).innerHTML = valor.toFixed(2);
    parent.document.getElementById('valorcorr'+linha).innerHTML = valorcorr.toFixed(2);
    parent.document.getElementById('juros'+linha).innerHTML = juros.toFixed(2);
    parent.document.getElementById('multa'+linha).innerHTML = multa.toFixed(2);
    parent.document.getElementById('desconto'+linha).innerHTML = desconto.toFixed(2);
    parent.document.getElementById('total'+linha).innerHTML = total.toFixed(2);
    if(linha == 2) {
      valor     = Number(parent.document.getElementById('valor1').innerHTML) - valor;
      valorcorr = Number(parent.document.getElementById('valorcorr1').innerHTML) - valorcorr;
      juros     = Number(parent.document.getElementById('juros1').innerHTML) - juros;
      multa     = Number(parent.document.getElementById('multa1').innerHTML) - multa;
      desconto  = Number(parent.document.getElementById('desconto1').innerHTML) - desconto;
      total     = Number(parent.document.getElementById('total1').innerHTML) - total;

      parent.document.getElementById('valor3').innerHTML = valor.toFixed(2);
      parent.document.getElementById('valorcorr3').innerHTML = valorcorr.toFixed(2);
      parent.document.getElementById('juros3').innerHTML = juros.toFixed(2);
      parent.document.getElementById('multa3').innerHTML = multa.toFixed(2);
      parent.document.getElementById('desconto3').innerHTML = desconto.toFixed(2);
      parent.document.getElementById('total3').innerHTML = total.toFixed(2);
    }

  }

  function js_soma(linha) {

    parent.$('marcartodas').value     = true;
    $('marcartodas').value            = true;

    linha = ((typeof(linha)=="undefined") || (typeof(linha)=="object")?2:linha);
    var F = document.form1;
    var valor = 0;
    var valorcorr = 0;
    var juros = 0;
    var multa = 0;
    var desconto = 0;
    var total = 0;
    var emrec = '<?=$emrec?>';
    var agnum = '<?=@$agnum?>';
    var agpar = '<?=@$agpar?>';
    var k03_tipo = '<?=$k03_tipo?>';
    var perfil_procuradoria = '<?=$perfil_procuradoria?>';

    var permissao_parcelamento     = <?=db_permissaomenu(db_getsession("DB_anousu"),81,3415)?>;
    var permissao_certidao         = <?=db_permissaomenu(db_getsession("DB_anousu"),81,4125)?>;
    var permissao_cancelar         = <?=db_permissaomenu(db_getsession("DB_anousu"),81,4554)?>;
    var permissao_justif           = <?=db_permissaomenu(db_getsession("DB_anousu"),81,5024)?>;
    var permissao_suspender        = <?=db_permissaomenu(db_getsession("DB_anousu"),81,7653)?>;
    var permissao_importardiversos = <?php echo db_permissaomenu(db_getsession("DB_anousu"),1444,9146) == "false" ? db_permissaomenu(db_getsession("DB_anousu"),1444, 9426) : "true"; ?>;

    var tab = document.getElementById('tabdebitos');

    //alert(parent.document.getElementById('tipo').value);

    if(emrec == 't'){

      if (k03_tipo != '18') {

        parent.document.getElementById("enviar").disabled 	  = false;//botao emite recibo
        parent.document.getElementById("btcarne").disabled 	  = false;//botao carne

        if ( (agpar == 't' || agnum == 't') || (k03_tipo == 3) ) {
          parent.document.getElementById("geranotif").disabled 	  = false;
        }
      }
      parent.document.getElementById("emisscarne").disabled   = false;//select outros exercicios
      parent.document.getElementById("btnotifica").disabled   = false;

      parent.document.getElementById("btparc").disabled		    = true; // botao parcelamento
      parent.document.getElementById("btcda").disabled 	      = true; // botao certidao
      parent.document.getElementById("btcancela").disabled 	  = true; // botao cancela debitos
      parent.document.getElementById("btnSuspender").disabled = true; // botao suspender
      parent.document.getElementById("btjust").disabled 	    = true; // botao justifica


      if (document.form1.k03_permparc.value == 't' && permissao_parcelamento == true && linha > 1 && k03_tipo != '18') {
        parent.document.getElementById("btparc").disabled = false; // botao parcelamento
      }

      if (permissao_certidao == true && (k03_tipo == 5 || k03_tipo == 6) && linha > 1 && k03_tipo != '18') {
        parent.document.getElementById("btcda").disabled = false; // botao certidao
      }

      if (permissao_cancelar == true && linha > 1 && k03_tipo != '18' ) {
        parent.document.getElementById("btcancela").disabled = false; // botao cancela debitos
      }

      if (permissao_suspender == true && linha > 1) {
        parent.document.getElementById("btnSuspender").disabled = false; // botao cancela debitos
      }

      if (permissao_justif == true && linha > 1 && k03_tipo != '18') {
        parent.document.getElementById("btjust").disabled = false; // botao justifique debitos
      }

      if (permissao_certidao == true && linha > 1 && ( k03_tipo == 15 || k03_tipo == 18 )) {

        parent.document.getElementById("enviar").disabled 	    = true;//botao emite recibo
        parent.document.getElementById("btcarne").disabled 	    = true;//botao carne
        parent.document.getElementById("emisscarne").disabled   = true;//select outros exercicios
        parent.document.getElementById("btnotifica").disabled   = true;

        if (  (agpar == 't' || agnum == 't') || (k03_tipo != 3)  ) {
          parent.document.getElementById("geranotif").disabled    = true;
        }

        parent.document.getElementById("btparc").disabled		    = true; // botao parcelamento
        parent.document.getElementById("btcda").disabled 	      = true; // botao certidao
        parent.document.getElementById("btcancela").disabled 	  = true; // botao cancela debitos
        parent.document.getElementById("btjust").disabled 	    = true; // botao justific
      }

      // incluido teste para liberação de importação de diversos ps: somente IPTU/AGUA Exercicio

    }

    for(var i = 0;i < F.length;i++) {

      if((F.elements[i].type == "checkbox" || F.elements[i].type == "submit") && (F.elements[i].checked == true || linha == 1) && (F.elements[i].value.indexOf('unica_') == -1) ) {
        var indi   = js_parse_int(F.elements[i].id);
//        alert(indi);
        if(document.getElementById('valor'+indi)){
          valor     += new Number(document.getElementById('valor'+indi).value.replace(",",""));
          valorcorr += new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
          juros     += new Number(document.getElementById('juros'+indi).value.replace(",",""));
          multa     += new Number(document.getElementById('multa'+indi).value.replace(",",""));
          desconto  += new Number(document.getElementById('desconto'+indi).value.replace(",",""));
          total     += new Number(document.getElementById('total'+indi).value.replace(",",""));
        }
      }
    }

    parent.document.getElementById('valor'+linha).innerHTML = valor.toFixed(2);
    parent.document.getElementById('valorcorr'+linha).innerHTML = valorcorr.toFixed(2);
    parent.document.getElementById('juros'+linha).innerHTML = juros.toFixed(2);
    parent.document.getElementById('multa'+linha).innerHTML = multa.toFixed(2);
    parent.document.getElementById('desconto'+linha).innerHTML = desconto.toFixed(2);
    parent.document.getElementById('total'+linha).innerHTML = total.toFixed(2);

    if(linha == 2) {


      if ( (k03_tipo == 1 || k03_tipo == 20) && permissao_importardiversos && valor > 0) {
        parent.document.getElementById("btimpdiverso").disabled		= false; // botão importação diversos
      }

      valor     = Number(parent.document.getElementById('valor1').innerHTML) - valor;
      valorcorr = Number(parent.document.getElementById('valorcorr1').innerHTML) - valorcorr;
      juros     = Number(parent.document.getElementById('juros1').innerHTML) - juros;
      multa     = Number(parent.document.getElementById('multa1').innerHTML) - multa;
      desconto  = Number(parent.document.getElementById('desconto1').innerHTML) - desconto;
      total     = Number(parent.document.getElementById('total1').innerHTML) - total;
      parent.document.getElementById('valor3').innerHTML = valor.toFixed(2);
      parent.document.getElementById('valorcorr3').innerHTML = valorcorr.toFixed(2);
      parent.document.getElementById('juros3').innerHTML = juros.toFixed(2);
      parent.document.getElementById('multa3').innerHTML = multa.toFixed(2);
      parent.document.getElementById('desconto3').innerHTML = desconto.toFixed(2);
      parent.document.getElementById('total3').innerHTML = total.toFixed(2);
    }

    if(emrec == 't') {
      var aux = 0;
      for(i = 0;i < F.length;i++) {
        if(F.elements[i].type == "checkbox")
        if(F.elements[i].checked == true)
        aux = 1;
      }
      if(aux == 0) {
        parent.document.getElementById("enviar").disabled = true;
        parent.document.getElementById("btparc").disabled = true;
        parent.document.getElementById("btcda").disabled = true;
        parent.document.getElementById("btcancela").disabled = true;
      	parent.document.getElementById("btnSuspender").disabled = true;
        parent.document.getElementById("btjust").disabled = true;
        parent.document.getElementById("btcarne").disabled = true;
        parent.document.getElementById("emisscarne").disabled = true;
        parent.document.getElementById("btnotifica").disabled = true;
        parent.document.getElementById("btimpdiverso").disabled = true;
        document.getElementById('marca').innerHTML = "M";
        parent.document.getElementById('btmarca').value = "Marcar Todas";
        parent.document.getElementById('btmarcavencidas').value = "Marcar Vencidas";
        parent.$('marcartodas').value  = false;
        parent.$('marcarvencidas').value  = false;
        $('marcartodas').value  = false;
        $('marcarvencidas').value  = false;

        parent.document.getElementById("geranotif").disabled = true;

      }
    }

    if ( ( k03_tipo == 13 || k03_tipo == 18 ) && perfil_procuradoria == 0 ) {
	    parent.document.getElementById("enviar").disabled = true;//botao emite recibo
      parent.document.getElementById("btparc").disabled = true;
      parent.document.getElementById("btcarne").disabled = true;
    }


  }

  function js_marca() {


    parent.document.getElementById('btmarcavencidas').value = "Marcar Vencidas";
    parent.$('marcarvencidas').value  = false;
    $('marcarvencidas').value         = false;

    var ID = document.getElementById('marca');
    var BT = parent.document.getElementById('btmarca');
    if(!ID){
      return false;
    }

    var F = document.form1;

    if (parent.$('marcarvencidas').value == 'true' || $('marcarvencidas').value == 'true') {

      document.getElementById('marca').innerHTML = 'M';
      for (i = 0; i < F.elements.length; i++) {

        if (F.elements[i].type == "checkbox") {

          if ( F.elements[i].value.indexOf('unica_') != -1) {
            F.elements[i].click();
          } else if (F.elements[i].style.visibility!="hidden") {
            F.elements[i].checked = false;
          }
        }
      }
    }

    if(ID.innerHTML == 'M') {
      var dis = true;
      ID.innerHTML = 'D';
      BT.value = "Desmarcar";
      parent.$('marcartodas').value  = true;
      $('marcartodas').value         = true;
    } else {
      var dis = false;
      ID.innerHTML = 'M';
      BT.value = "Marcar";
      parent.$('marcartodas').value  = false;
      $('marcartodas').value         = false;
    }

    for(i = 0;i < F.elements.length;i++) {
      if(F.elements[i].type == "checkbox"){
        if( F.elements[i].value.indexOf('unica_') != -1 ) {
          F.elements[i].click();
        } else if(F.elements[i].style.visibility!="hidden"){
          F.elements[i].checked = dis;
        }
      }
    }

    js_soma(this,2);
  }

  function js_marca_vencidas() {

    var aVencidos             = $$('.Vencido');
    var oParam                = new Object();
    var oDocument             = document.form1;
    parent.$('btmarca').value = "Marcar Todas";

    if (parent.$('marcartodas').value == 'true' || $('marcartodas').value == 'true') {

      document.getElementById('marca').innerHTML = 'M';
	    for (i = 0; i < oDocument.elements.length; i++) {

	      if (oDocument.elements[i].type == "checkbox") {

	        if ( oDocument.elements[i].value.indexOf('unica_') != -1) {
	          oDocument.elements[i].click();
	        } else if (oDocument.elements[i].style.visibility!="hidden") {
	          oDocument.elements[i].checked = false;
	        }
	      }
	    }
    }

    oParam.aNumpres = new Array();

    aVencidos.each(
      function (oChk) {
        if (oChk.checked) {

          parent.$('btmarcavencidas').value = "Marcar Vencidas";
          parent.$('marcarvencidas').value  = false;
          $('marcarvencidas').value         = false;
          oChk.checked                      = false;

        } else {

          parent.$('btmarcavencidas').value = "Desmarcar Vencidas";
          parent.$('marcarvencidas').value  = true;
          $('marcarvencidas').value         = true;
          oChk.checked                      = true;

          var sNumpre    = new String(oChk.value);
          var iPosNumpar = sNumpre.indexOf("P");

          if (iPosNumpar != -1) {

            var sNumpreParametro = sNumpre.substr(0,iPosNumpar);
                sNumpreParametro = sNumpreParametro.replace("N","");
          }

          oParam.aNumpres.push(sNumpreParametro);

        }
      }
    );

    js_soma(this,2);

    if ($('marcarvencidas').value == 'true') {
      js_soma_vencidas(oParam.aNumpres);
    }

    parent.$('marcartodas').value = false;
    $('marcartodas').value        = false;

  }

  function js_soma_vencidas(sNumpres) {

    var sUrl          = 'con3_parcelasvencidas.RPC.php';
	  var oParam        = new Object();
	  oParam.exec       = "getSomaParcelasVencidas";
    oParam.numpres    = sNumpres;
	  oParam.tipodebito = $('tipo_debito').value;
	  oParam.data       = '<?=$DB_DATACALC?>';

	  var oAjax   = new Ajax.Request( sUrl, {
	                                          method: 'post',
	                                          parameters: 'json='+js_objectToJson(oParam),
	                                          onComplete: js_retornoDadosVencidas
	                                        }
	                                );
  }

  function js_retornoDadosVencidas(oAjax) {

	  var oRetorno = eval("("+oAjax.responseText+")");

	  if (oRetorno.status == 2) {

	    alert(oRetorno.erro.urlDecode());
	    return false;
	  } else {

      parent.$('valor1').innerHTML     = oRetorno.valorlancado.replace(",",".");
      parent.$('valorcorr1').innerHTML = oRetorno.valorlancadocorr.replace(",",".");
      parent.$('juros1').innerHTML     = oRetorno.valorlancadojuro.replace(",",".");
      parent.$('multa1').innerHTML     = oRetorno.valorlancadomulta.replace(",",".");
      parent.$('desconto1').innerHTML  = oRetorno.valorlancadodesconto.replace(",",".");
      parent.$('total1').innerHTML     = oRetorno.valorlancadototal.replace(",",".");

      parent.$('valor2').innerHTML     = oRetorno.valordebito.replace(",",".");
      parent.$('valorcorr2').innerHTML = oRetorno.valorcor.replace(",",".");
      parent.$('juros2').innerHTML     = oRetorno.valorjuro.replace(",",".");
      parent.$('multa2').innerHTML     = oRetorno.valormulta.replace(",",".");
      parent.$('desconto2').innerHTML  = oRetorno.valordesconto.replace(",",".");
      parent.$('total2').innerHTML     = oRetorno.valortotal.replace(",",".");

      parent.$('valor3').innerHTML     = oRetorno.somavalorlancado.replace(",",".");
      parent.$('valorcorr3').innerHTML = oRetorno.somavalorlancadocorr.replace(",",".");
      parent.$('juros3').innerHTML     = oRetorno.somavalorlancadojuro.replace(",",".");
      parent.$('multa3').innerHTML     = oRetorno.somavalorlancadomulta.replace(",",".");
      parent.$('desconto3').innerHTML  = oRetorno.somavalorlancadodesconto.replace(",",".");
      parent.$('total3').innerHTML     = oRetorno.somavalorlancadototal.replace(",",".");

	    return true;
	  }
  }
</script>
<style type="text/css">
  <!--
  .borda {
    border-right-width: 1px;
    border-right-style: solid;
    border-right-color: #000000;
  }
  -->
  </style>
<script language="JavaScript" type="text/JavaScript">
  <!--
  function MM_reloadPage(init) {  //reloads the window if Nav4 resized
    if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
    else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
  }
  MM_reloadPage(true);
  //-->

  function js_desconto(chave){
    location.href="cai3_gerfinanc012.php?k00_numpre="+chave;
  }

  </script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
	onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'; js_desabilitaBotoes(); js_disabilitarFormEmissao();">
<center><?
//verifica se clicou no link da matricula
if(isset($inscricao) && !empty($inscricao)) {
  $inscr = $inscricao;
  $tipo = $tipo2;
}
if(isset($matricula) && !empty($matricula)) {
  $matric = $matricula;
  $tipo = $tipo2;
}

$sqltiporetido = "select w10_tipo from db_confplan";
$resultretido = db_query($sqltiporetido);
$linhasretido = pg_num_rows($resultretido);
if($linhasretido > 0){
	db_fieldsmemory($resultretido,0);
}else{
	db_msgbox("Deve configurar a a planilha (db_confplan)");
}



//verifica o tipo e da o select dependendo se é numcgm, matric numpre ou inscr
if(isset($tipo)) {

  if($tipo == 3) {

    if(isset($numcgm)) {

      if(($result = debitos_numcgm_var($numcgm,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"",true))) {
        echo "<script> numcgm = '$numcgm'; </script>\n";
      } else {
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
      }
    } else if(isset($inscr)) {

      if(($result = debitos_inscricao_var($inscr,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),true))) {
        echo "<script> inscr = '$inscr'; </script>\n";
      } else {
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
      }
    } else if(isset($numpre)) {

      if(($result = debitos_numpre_var($numpre,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),true))) {
        echo "<script> numpre = '$numpre'; </script>\n";
      }
    } else {
      db_redireciona("cai3_gerfinanc007.php?erro1=1");
    }
  } elseif($tipo== $w10_tipo) {
    if(isset($numcgm) && !empty($numcgm)) {
      if(($result = debitos_numcgm($numcgm,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","",true ))){
        echo "<script> numcgm = '$numcgm'; </script>\n ";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}
    } else if(isset($matric) && !empty($matric)) {
      if(($result = debitos_matricula($matric,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","",true))){
        echo "<script> matric = '$matric'; </script>\n";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}
    } else if(isset($inscr) && !empty($inscr)) {

      if(($result = debitos_inscricao_retido($inscr,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","",true))){
        echo "<script> inscr = '$inscr'; </script>\n";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}
    } else if(isset($numpre) && !empty($numpre)) {
      if(($result = debitos_numpre($numpre,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","","",true))){
      echo "<script> numpre = '$numpre'; </script>\n";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}
    } else if(isset($Parcelamento) && !empty($Parcelamento) ) {
      $result_parcel = db_query("select distinct v07_numpre as numpre from termo where v07_parcel  = {$Parcelamento} ");
      if (pg_numrows($result_parcel) > 0){
        db_fieldsmemory($result_parcel,0);
      }

      if(($result = debitos_numpre($numpre,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","","",true))){
        echo "<script> numpre = '$numpre'; </script>\n";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}

    }

  }else{
  	if(isset($numcgm) && !empty($numcgm)) {
      if(($result = debitos_numcgm($numcgm,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","",true ))){
        echo "<script> numcgm = '$numcgm'; </script>\n ";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}
    } else if(isset($matric) && !empty($matric)) {
      if(($result = debitos_matricula($matric,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","",true))){
        echo "<script> matric = '$matric'; </script>\n";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}
    } else if(isset($inscr) && !empty($inscr)) {

      if(($result = debitos_inscricao($inscr,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","",true))){
        echo "<script> inscr = '$inscr'; </script>\n";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}
    } else if(isset($numpre) && !empty($numpre)) {
      if(($result = debitos_numpre($numpre,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","",""," and y.k00_hist <> 918",true))){
      echo "<script> numpre = '$numpre'; </script>\n";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}
    } else if(isset($Parcelamento) && !empty($Parcelamento) ) {
      $result_parcel = db_query("select distinct v07_numpre as numpre from termo where v07_parcel  = {$Parcelamento} ");
      if (pg_numrows($result_parcel) > 0){
        db_fieldsmemory($result_parcel,0);
      }

      if(($result = debitos_numpre($numpre,0,$tipo,$DB_DATACALC,db_getsession("DB_anousu"),"","","","",true))){
        echo "<script> numpre = '$numpre'; </script>\n";
			}else{
        db_redireciona("cai3_gerfinanc007.php?erro1=1");
			}

    }


  }

  // echo "x: " . pg_numrows($result) . "\n";
  // db_criatabela($result);
  $numrows = pg_numrows($result);

  echo "<form name=\"form1\" id=\"form1\" method=\"post\" target=\"reciboweb2\">\n";
  echo "<input type=\"hidden\" name=\"H_ANOUSU\" value=\"".db_getsession("DB_anousu")."\">\n";
  echo "<input type=\"hidden\" name=\"H_DATAUSU\" value=\"".$DB_DATACALC."\">\n";
  if ( $numrows > 0 ) {
    echo "<input type=\"hidden\" name=\"ver_matric\" value=\"".@pg_result($result,0,"k00_matric")."\">\n";
    echo "<input type=\"hidden\" name=\"ver_inscr\" value=\"".@pg_result($result,0,"k00_inscr")."\">\n";
    echo "<input type=\"hidden\" name=\"ver_numcgm\" value=\"".@pg_result($result,0,"k00_numcgm")."\">\n";
    echo "<input type=\"hidden\" name=\"certidao\" value=\"".@$certidao."\">\n";
  }

//  echo ("select cadtipo.k03_tipo,k03_parcelamento,k03_permparc from arretipo inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo where k00_tipo = $tipo and k00_instit = ".db_getsession('DB_instit') );
  $result_k03_tipo = db_query("select cadtipo.k03_tipo,k03_parcelamento,k03_permparc,k00_formemissao
	                              from arretipo
																     inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo
															 where k00_tipo = $tipo and k00_instit = ".db_getsession('DB_instit') );
  db_fieldsmemory($result_k03_tipo,0);
  echo "<input type=\"hidden\" name=\"tipo_debito\" id=\"tipo_debito\" value=\"".$tipo."\">\n";
  echo "<input type=\"hidden\" name=\"k03_tipo\" value=\"".$k03_tipo."\">\n";
  echo "<input type=\"hidden\" name=\"perfil_procuradoria\" value=\"".$perfil_procuradoria."\">\n";
  echo "<input type=\"hidden\" name=\"k03_parcelamento\" value=\"".$k03_parcelamento."\">\n";
  echo "<input type=\"hidden\" name=\"k03_permparc\" value=\"".$k03_permparc."\">\n";
  echo "<input type=\"hidden\" name=\"k00_formemissao\" id=\"k00_formemissao\" value=\"".$k00_formemissao."\">\n";
  echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" id=\"tabdebitos\">\n";
  //cria o cabeçalho
  echo "<tr bgcolor=\"#FFCC66\">\n";
  echo "<th title=\"Outras Informações\" class=\"borda\" style=\"font-size:12px\" nowrap>O</th>\n";
  echo "<th title=\"Notificações\" class=\"borda\" style=\"font-size:12px\" nowrap>N</th>\n";
  echo "<th title=\"Código de Arrecadação\" class=\"borda\" style=\"font-size:12px\" nowrap>Numpre</th>\n";
  echo "<th title=\"Parcela\" class=\"borda\" style=\"font-size:12px\" nowrap>P</th>\n";
  echo "<th title=\"Total de Parcela\" class=\"borda\" style=\"font-size:12px\" nowrap>T</th>\n";

  //Se for divida ativa mostra o exercicio da divida
  if($k03_tipo==5){
    echo "<th title=\"Exercício\" class=\"borda\" style=\"font-size:12px\" nowrap>Exercício</th>\n";
    echo "<th title=\"Coddiv\" class=\"borda\" style=\"font-size:12px\" nowrap>Coddiv</th>\n";
  }else if($k03_tipo==6 or $k03_tipo==13 or $k03_tipo==16 or $k03_tipo==17){//Se for parcelamento mostra o Nº do parcelamento
    echo "<th title=\"Parcelamento\" class=\"borda\" style=\"font-size:12px\" nowrap>Parcelamento</th>\n";
  } elseif ($aguaColunaContrato === true) {
    echo "<th title=\"Número do Contrato\" class=\"borda\" style=\"font-size:12px\" nowrap>Contrato</th>\n";
    echo "<th title=\"Identificador da Economia\" class=\"borda\" style=\"font-size:12px\" nowrap>Economia</th>\n";
  }

  echo "<th title=\"Data de Lançamento\" class=\"borda\" style=\"font-size:12px\" nowrap>Dt. oper.</th>\n";
  echo "<th title=\"Data de Vencimento\" class=\"borda\" style=\"font-size:12px\" nowrap>Dt. Venc.</th>\n";
  echo "<th title=\"Histórico do Lançamento\" class=\"borda\" style=\"font-size:12px\" nowrap>Histórico</th>\n";

  //Verifica se agrupado por numpre, cria link pra passar pro nivel 2, mostrando todos os numpres
  if(!empty($inscr)){
    $arg = "inscr=".$inscr;
	}else if(!empty($numcgm)){
    $arg = "numcgm=".$numcgm;
  }else if(!empty($matric)){
    $arg = "matric=".$matric;
  }else if(!empty($numpre)){
    $arg = "numpre=".$numpre;
  }

  if(@$agnum == 't') {
    echo "<th title=\"Lista por parcela\" class=\"borda\" style=\"font-size:12px\" nowrap><a href=\"cai3_gerfinanc002.php?".$arg."&tipo=$tipo&verificaagrupar=1&agnump=f&agpar=t&emrec=".$emrec."&db_datausu=".adodb_date("Y-m-d",$DB_DATACALC)."&inscr=".@$inscr."&matric=".@$matric."&k03_tipo=".@$k03_tipo."&perfil_procuradoria=".@$perfil_procuradoria."&numpre=".@$numpre."&numcgm=".@$numcgm."\">Rec</a></th>\n";
  } else {
    echo "<th title=\"Receita\" class=\"borda\" style=\"font-size:12px\" nowrap>Rec</th>\n";
  }

  echo "<th title=\"Descrição Receita\" class=\"borda\" style=\"font-size:12px\" nowrap>Receita</th>\n";
  echo "<th title=\"Valor Lançado\" class=\"borda\" style=\"font-size:12px\" nowrap>Val.</th>\n";
  echo "<th title=\"Valor Corrigido\" class=\"borda\" style=\"font-size:12px\" nowrap>Val Cor.</th>\n";
  echo "<th title=\"Valor Juros\" class=\"borda\" style=\"font-size:12px\" nowrap>Jur.</th>\n";
  echo "<th title=\"Valor Multa\" class=\"borda\" style=\"font-size:12px\" nowrap>Mul.</th>\n";
  echo "<th title=\"Valor Desconto\" class=\"borda\" style=\"font-size:12px\" nowrap>Desc.</th>\n";
  echo "<th title=\"Total a Pagar\" class=\"borda\" style=\"font-size:12px\" nowrap>Tot.</th>\n";
  echo "<th title=\"Marca/Desmarca Todas\" class=\"borda\" style=\"font-size:12px\" nowrap>
          <a id=\"marca\" href=\"\" style=\"color:black\" onclick=\"js_marca();return false\">M</a>
          <input type=\"hidden\" name=\"numpre_unica\">
        </th>\n";
  echo "</tr>\n";

  echo " <input type='hidden' id='txtNumpreUnicaSelecionados' name='txtNumpreUnicaSelecionados'>";
  echo " <input type='hidden' id='DadosUnicas'				  name='DadosUnicas'> ";

  //verifica se foi clicado no link agrupar e recria as variaveis do QUERY_STRING pra atualizar o agnump e agpar
  if(isset($verificaagrupar)) {
    parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  }

  // calcular totregistros...
  $j = 0;
  $elementos2[0] = "";
  for($i = 0;$i < $numrows;$i++) {
    if(!in_array(pg_result($result,$i,"k00_numpre"),$elementos2)) {
      $elementos2[$j++] = pg_result($result,$i,"k00_numpre");
    }
  }

  $totregistros = 0;

  for($i = 0;$i < sizeof($elementos2);$i++) {

    for($j = 0;$j < $numrows;$j++) {
      if($elementos2[$i] == pg_result($result,$j,"k00_numpre")) {
        if(pg_result($result,$j,"k00_numpar") != @pg_result($result,$j+1,"k00_numpar") || ($elementos2[$i] != @pg_result($result,$j+1,"k00_numpre")) ) {
          $totregistros++;
        }
      }
    }

  }

  echo "<input type=\"hidden\" name=\"totregistros\" value=\"".@$totregistros."\">\n";
  echo "<input type=\"hidden\" name=\"marcartodas\"    id=\"marcartodas\"    value=\"false\" >\n";
  echo "<input type=\"hidden\" name=\"marcarvencidas\" id=\"marcarvencidas\" value=\"false\" >\n";

  //if com 3 partes. Primeiro se é pra agrupar por numpre, segundo se é pra agrupar por parcela e terceiro mostra o default
  //agrupar por numpre
  //	echo "xxx: $agnum";
  ////////////////////////////////////// AGRUPAMENTO GERAL ///////////////////////////////
  $separador				= "N";

  if(@$agnum == 't') {
    /******************************************************************************************/
    //cria um array com os elementos não repetidos
    $j = 0;
    $vlrtotal = 0;
    $elementos[0] = "";
    for($i = 0;$i < $numrows;$i++) {
      if(!in_array(pg_result($result,$i,"k00_numpre"),$elementos)) {
        $REGISTRO[$j] = pg_fetch_array($result,$i);
        $elementos[$j++] = pg_result($result,$i,"k00_numpre");
      }
    }

    $iContadorUnica = 0;
    //faz a mao...
    for($i = 0;$i < sizeof($elementos);$i++) {

      $numpres_valores	= "";
      $separadortroca		= "";

      $valor = 0;
      $valorcorr = 0;
      $juros = 0;
      $multa = 0;
      $desconto = 0;
      $total = 0;
      //$separador = "";
      $numpres = "";

      $totalparc = 0;

      $controlatroca = pg_result($result,0,"k00_numpre") . pg_result($result,0,"k00_numpar");

      for($j = 0;$j < $numrows;$j++) {

        if($elementos[$i] == pg_result($result,$j,"k00_numpre")) {

          //        echo "$i - $j - " . sizeof($elementos) . " - numpre: " . pg_result($result,$j,"k00_numpre") . " - numpar: " . pg_result($result,$j,"k00_numpar") . " - " . pg_result($result,$j,"total") . " - " . @pg_result($result,$j+1,"k00_numpre") . " - $controlatroca<br>";

          if(pg_result($result,$j,"k00_numpar") != @pg_result($result,$j+1,"k00_numpar") || ($elementos[$i] != @pg_result($result,$j+1,"k00_numpre")) ) {
            $numpres .= $separador.pg_result($result,$j,"k00_numpre")."P".pg_result($result,$j,"k00_numpar")."R0";
            $separador = "N";
          }
          $valor     += (float)pg_result($result,$j,"vlrhis");
          $valorcorr += (float)pg_result($result,$j,"vlrcor");
          $juros     += (float)pg_result($result,$j,"vlrjuros");
          $multa     += (float)pg_result($result,$j,"vlrmulta");
          $desconto  += (float)pg_result($result,$j,"vlrdesconto");
          $total     += (float)pg_result($result,$j,"total");
          $totalparc += (float)pg_result($result,$j,"total");

          if ((pg_result($result,$j,"k00_numpre") . pg_result($result,$j,"k00_numpar") != @pg_result($result,$j+1,"k00_numpre") . @pg_result($result,$j+1,"k00_numpar")) or ($elementos[$i] != @pg_result($result,$j+1,"k00_numpre"))) {
            $numpres_valores .= $separadortroca.$totalparc;
            //						echo $numpres_valores . "<br>";
            //						echo "somando: $totalparc<br>";
            $totalparc = 0;
            $separadortroca		= "N";
          }

        }
        $controlatroca = pg_result($result,$j,"k00_numpre") . pg_result($result,$j,"k00_numpar");

      }

      /**************************/
      $vlrtotal += $REGISTRO[$i]["total"];
      $dtoper = $REGISTRO[$i]["k00_dtoper"];
      //$dtoper = pg_result($result,$i,"k00_dtoper");
      $dtoper = adodb_mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
      //if($dtoper > time())
      //  $corDtoper = "#FF5151";
      //else
      $corDtoper = "";
      $dtvenc = $REGISTRO[$i]["k00_dtvenc"];
      $dtvenc = adodb_mktime(23,59,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));

      if($dtvenc < $DB_DATACALC ){ //time())

        $corDtvenc  = "red";
        $sClassVenc = "Vencido";

      } else {

      	$sClassVenc  = "";
        if(adodb_date("d/m/Y",$dtvenc) == adodb_date("d/m/Y",$DB_DATACALC) ){ //time())
          $corDtvenc = "blue";
        } else {
          $corDtvenc = "";
        }

      }
      //*****CABEÇALHO  ;border:none

      // unica
      //	  if($elementos_parcelas[$i]==1){
      $sql_resultunica = "select *,
          substr(fc_calcula,2,13)::float8 as uvlrhis,
          substr(fc_calcula,15,13)::float8 as uvlrcor,
          substr(fc_calcula,28,13)::float8 as uvlrjuros,
          substr(fc_calcula,41,13)::float8 as uvlrmulta,
          substr(fc_calcula,54,13)::float8 as uvlrdesconto,
          (substr(fc_calcula,15,13)::float8+
          substr(fc_calcula,28,13)::float8+
          substr(fc_calcula,41,13)::float8-
          substr(fc_calcula,54,13)::float8) as utotal
          from (
          select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,
          fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")
          from recibounica r
          where r.k00_numpre = ".$elementos[$i]." and r.k00_dtvenc >= '".date('Y-m-d', $DB_DATACALC)."'
          ) as unica";

      $resultunica = db_query($sql_resultunica);
      for($unicont=0;$unicont<pg_numrows($resultunica);$unicont++){
        db_fieldsmemory($resultunica,$unicont);
        if($dtvencunic>=adodb_date('Y-m-d',$DB_DATACALC)){
          $dtvencunic = db_formatar($dtvencunic,'d');
          $dtoperunic = db_formatar($dtoperunic,'d');
          $corunica = "#009933";
          $uvlrcorr = 0;

          $histdesc = "";
          $resulthist = db_query("select k00_dtoper as dtlhist,k00_hora, login,substr(k00_histtxt,0,80) as k00_histtxt
              from arrehist
              left outer join db_usuarios on id_usuario = k00_id_usuario
              where k00_numpre = ".$elementos[$i]." and k00_numpar = 0");
          if(pg_numrows($resulthist)>0){
            for($di=0;$di<pg_numrows($resulthist);$di++){
              db_fieldsmemory($resulthist,$di);
              $histdesc .= $dtlhist." ".$k00_hora." ".$login." ".$k00_histtxt."\n";
            }
          }

          $iContadorUnica++;
          echo "<tr bgcolor=\"$corunica\">\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" nowrap></td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" nowrap></td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" nowrap title=\"".$histdesc."\">".$k00_numpre;
          echo "    <input type=\"hidden\" name=\"np_unica_".($iContadorUnica)."\" value=\"".$k00_numpre."\"> ";
          echo "  </td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" nowrap>00</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" nowrap>00</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" nowrap>".$dtoperunic."</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" nowrap>".$dtvencunic;
          echo "    <input type=\"hidden\" name=\"dt_unica_".($iContadorUnica)."\"  value=\"".implode('-',array_reverse(explode('/',$dtvencunic)))."\"> ";
          echo "    <input type=\"hidden\" name=\"unica_np_dt".($iContadorUnica)."\" value=\"".$k00_numpre."_".implode('-',array_reverse(explode('/',$dtvencunic)))."\"> ";
          echo "  </td> \n";
          echo "  <td colspan=\"3\" class=\"borda\" style=\"font-size:11px;color:white\" nowrap>Parcela Única com $k00_percdes% desconto</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrhis,"f")."</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrcor,"f")."</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrjuros,"f")."</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrmulta,"f")."</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrdesconto,"f")."</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($utotal,"f")."</td>\n";
          echo "  <td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap> ";
          echo "    <input class='{$sClassVenc}' type=\"checkbox\" id=\"check_id_".($iContadorUnica)."\" style=\"border:none;background-color:write\" name=\"unica\" onclick=\"js_emiteunica('".$k00_numpre."','".$dtvencunic."',this)\" value=\"unica_".$unicont."\">";
          echo "  </td>\n ";
          echo "</tr>";

        }
      }
      //     }
      //

      $noti_sql = "select k53_numpre
        from notidebitos
        where k53_numpre = ".$REGISTRO[$i]["k00_numpre"]."
        limit 1";
      $noti_result = db_query($noti_sql);
      $temnoti = false;
      if(pg_numrows($noti_result)){
        $temnoti = true;

      }

      echo "<label for=\"CHECK$i\"><tr style=\"cursor: hand\" bgcolor=\"".(@$cor = (@$cor=="#E4F471"?"#EFE029":"#E4F471"))."\">\n";
      echo "<td title=\"Informações Adicionais\" class=\"borda\" style=\"font-size:11px\" nowrap onclick=\"parent.js_mostradetalhes('cai3_gerfinanc005.php?".base64_encode($tipo."#".$REGISTRO[$i]["k00_numpre"]."#"."0")."','','width=600,height=500,scrollbars=1')\"><a href=\"\" onclick=\"return false;\">
        MI</a></td>\n";
      if($temnoti){
        echo "<td title=\"Notificações Informadas\" class=\"borda\" style=\"font-size:11px\" nowrap onclick=\"parent.js_mostradetalhes('cai3_gerfinanc061.php?chave1=numpre&chave=".$REGISTRO[$i]["k00_numpre"]."','','width=700,height=500,scrollbars=1')\"><a href=\"\" onclick=\"return false;\">
          N</a></td>\n";
      }else{
        echo "<td title=\"Sem Notificações Informadas\" class=\"borda\" style=\"font-size:11px\" nowrap >&nbsp</td>\n";

      }

      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap><input style=\"border:none;background-color:$cor\" onclick=\"location.href='cai3_gerfinanc008.php?".base64_encode("numpre=".$REGISTRO[$i]["k00_numpre"])."'\" type=\"button\" value=\"".$REGISTRO[$i]["k00_numpre"]."\"></td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>
        <input type=\"hidden\" id=\"parc$i\" value=\"0#".$REGISTRO[$i]["k00_numtot"]."#".$REGISTRO[$i]["k00_numpre"]."\">0 </td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$REGISTRO[$i]["k00_numtot"]."</td>\n";

      //Se for divida ativa mostra o exercicio Select para buscar o exercicio
      if($k03_tipo==5){
        $result_exerc = db_query("select distinct v01_coddiv, v01_exerc from divida where v01_numpre =".$REGISTRO[$i]["k00_numpre"]." and v01_numpar = ".$REGISTRO[$i]["k00_numpar"]." limit 1");
        db_fieldsmemory($result_exerc,0);
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v01_exerc."&nbsp;</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v01_coddiv."&nbsp;</td>\n";
      }else if($k03_tipo==6 or $k03_tipo==13 or $k03_tipo==16 or $k03_tipo==17){//Se for parcelamento mostra o Nº do parcelamento select para buscar o Nº do parcelamento
        $result_parcel = db_query("select distinct v07_parcel from termo where v07_numpre =".$REGISTRO[$i]["k00_numpre"]);
        if (pg_numrows($result_parcel)==1){
          db_fieldsmemory($result_parcel,0);
        }
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v07_parcel."&nbsp;</td>\n";
      }
      echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtoper==""?"":"bgcolor=$corDtoper")." nowrap>".adodb_date("d-m-Y",$dtoper)."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtvenc==""?"":"bgcolor=$corDtvenc")." nowrap>".adodb_date("d-m-Y",$dtvenc)."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim($REGISTRO[$i]["k01_descr"])==""?"&nbsp":$REGISTRO[$i]["k01_descr"])."</td>\n";
      echo "<td title=\"Lista por parcela\" class=\"borda\" style=\"font-size:11px\" nowrap><a href=\"cai3_gerfinanc002.php?numpre=".$REGISTRO[$i]["k00_numpre"]."&tipo=$tipo&verificaagrupar=1&agnump=f&agpar=t&emrec=".$emrec."&db_datausu=".adodb_date("Y-m-d",$DB_DATACALC)."&inscr=".@$inscr."&matric=".@$matric."&k03_tipo=".@$k03_tipo."&perfil_procuradoria=".@$perfil_procuradoria."&numcgm=".@$numcgm."\">AP</a></td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim($REGISTRO[$i]["k02_descr"])==""?"&nbsp":$REGISTRO[$i]["k02_descr"])."</td>\n";

      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valor$i\" value=\"".$valor."\">".db_formatar($valor,"f")."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valorcorr$i\" value=\"".$valorcorr."\">".db_formatar($valorcorr,"f")."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"juros$i\" value=\"".$juros."\">".db_formatar($juros,"f")."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"multa$i\" value=\"".$multa."\">".db_formatar($multa,"f")."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"desconto$i\" value=\"".$desconto."\">".db_formatar($desconto,"f")."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$i\" value=\"".$total."\">".db_formatar($total,"f")."</td>\n";

      //      if($emrec == "t")
      echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".
      ($tipo==3?	 "<input type=\"submit\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\">":"")
      ."<input class='{$sClassVenc}' style=\"visibility:'visible'\" type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".$numpres."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".((abs($REGISTRO[$i]["k00_valor"])!=0 && $tipo==3)?"disabled":"").">
        <input style=\"visibility:'visible'\" type=\""."hidden"."\" value=\"".$numpres_valores."\" id=\"_VALORES$i\" name=\"_VALORES$i\">
        </td>\n";

      echo "</tr></label>\n";

      /***************************/
    }
    //agrupar por parcela
    ////////////////////////////////////// AGRUPAMENTO POR PARCELA ///////////////////////////////


  } else if($agpar == 't') {

    /**********************************************************************************************/
    //cria um array com os numpres não repetidos
    $j = 0;
    $elementos_numpres[0] = "";
    for($i = 0;$i < $numrows;$i++) {
      if(!in_array(pg_result($result,$i,"k00_numpre"),$elementos_numpres)) {
        $elementos_numpres[$j++] = pg_result($result,$i,"k00_numpre");
      }
    }
    //contador unico para nomear os inputs
    $ContadorUnico = 0;
    $iContadorUnica = 0;
    $bool = 1;
    //faz a mao..
    //$separadortroca		= "";
    for($x = 0;$x < sizeof($elementos_numpres);$x++) {
      //cria um array com as parcelas do numpre não repetidos

      if($bool == 0) {
        $ConfCor1 = "#77EE20";
        $ConfCor2 = "#A9F471";
        $bool = 1;
      } else {
        $ConfCor1 = "#EFE029";
        $ConfCor2 = "#E4F471";
        $bool = 0;
      }
      $f = 0;
      $vlrtotal = 0;
      if(isset($elementos_parcelas))
      unset($elementos_parcelas);
      $elementos_parcelas[0] = "";
      for($r = 0;$r < $numrows;$r++) {
        if($elementos_numpres[$x] == pg_result($result,$r,"k00_numpre")) {
          if(!in_array(pg_result($result,$r,"k00_numpar"),$elementos_parcelas)) {
            $REGISTRO[$f] = pg_fetch_array($result,$r);
            $elementos_parcelas[$f++] = pg_result($result,$r,"k00_numpar");
          }
        }
      }

      for($i = 0;$i < sizeof($elementos_parcelas);$i++) {

        $numpres_valores	= "";
        $separadortroca = "";

        $numpres = "";
        //$separador = "";
        $valor = 0;
        $valorcorr = 0;
        $juros = 0;
        $multa = 0;
        $desconto = 0;
        $total = 0;

        $totalparc				= 0;
        $controlatroca		= pg_result($result,0,"k00_numpre") . pg_result($result,0,"k00_numpar");
        //$separadortroca	= "";

        for($j = 0;$j < $numrows;$j++) {

          if($elementos_parcelas[$i] == pg_result($result,$j,"k00_numpar") && $elementos_numpres[$x] == pg_result($result,$j,"k00_numpre")) {
            if(pg_result($result,$j,"k00_numpar") != @pg_result($result,$j+1,"k00_numpar") || (	$elementos_numpres[$x] != @pg_result($result,$j+1,"k00_numpre")) ) {
              $numpres .= $separador.$elementos_numpres[$x]."P".$elementos_parcelas[$i]."R0";
              $separador = "N";

            }

            $valor     += (float)pg_result($result,$j,"vlrhis");
            $valorcorr += (float)pg_result($result,$j,"vlrcor");
            $juros     += (float)pg_result($result,$j,"vlrjuros");
            $multa     += (float)pg_result($result,$j,"vlrmulta");
            $desconto  += (float)pg_result($result,$j,"vlrdesconto");
            $total     += (float)pg_result($result,$j,"total");
            $totalparc += (float)pg_result($result,$j,"total");

            if ((pg_result($result,$j,"k00_numpre") . pg_result($result,$j,"k00_numpar") != @pg_result($result,$j+1,"k00_numpre") . @pg_result($result,$j+1,"k00_numpar")) or ($elementos_numpres[$x] != @pg_result($result,$j+1,"k00_numpre"))) {
              $numpres_valores .= $separadortroca.$totalparc;
              $totalparc = 0;
              $separadortroca		= "N";
            }

          }
        }
        /**************************/
        $vlrtotal += $REGISTRO[$i]["total"];
        $dtoper = $REGISTRO[$i]["k00_dtoper"];
        $dtoper = adodb_mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
        //if($dtoper > time())
        //  $corDtoper = "#FF5151";
        //else
        $corDtoper = "";
        $dtvenc = $REGISTRO[$i]["k00_dtvenc"];
        $dtvenc = adodb_mktime(23,59,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));
        if ($dtvenc < $DB_DATACALC ) { //time())

        	$corDtvenc  = "red";
        	$sClassVenc = "Vencido";

        } else {

        	$sClassVenc  = "";
          if (adodb_date("d/m/Y",$dtvenc) == adodb_date("d/m/Y",$DB_DATACALC) ) { //time())
            $corDtvenc = "blue";
          } else {
            $corDtvenc = "";
          }
        }

        // unica
        if($elementos_parcelas[$i]==1){
          $sqlunica = "select k00_numpre,dtvencunic,dtoperunic,k00_percdes,
            substr(fc_calcula,2,13)::float8 as uvlrhis,
            substr(fc_calcula,15,13)::float8 as uvlrcor,
            substr(fc_calcula,28,13)::float8 as uvlrjuros,
            substr(fc_calcula,41,13)::float8 as uvlrmulta,
            substr(fc_calcula,54,13)::float8 as uvlrdesconto,
            (substr(fc_calcula,15,13)::float8+
            substr(fc_calcula,28,13)::float8+
            substr(fc_calcula,41,13)::float8-
            substr(fc_calcula,54,13)::float8) as utotal
            from (
            select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,
            fc_calcula(k00_numpre,0,0,k00_dtvenc,k00_dtvenc,".db_getsession("DB_anousu").")
            from recibounica r
            where r.k00_numpre = ".$elementos_numpres[$x]." and r.k00_dtvenc >= '".date('Y-m-d', $DB_DATACALC)."'
            ) as unica";
          $resultunica = db_query($sqlunica);

          for($unicont=0;$unicont<pg_numrows($resultunica);$unicont++){
            db_fieldsmemory($resultunica,$unicont);
            if($dtvencunic>=adodb_date('Y-m-d',$DB_DATACALC)){
              $dtvencunic = db_formatar($dtvencunic,'d');
              $dtoperunic = db_formatar($dtoperunic,'d');
              $corunica = "#009933";
              $uvlrcorr = 0;
              $histdesc = "";
              $resulthist = db_query("select k00_dtoper as dtlhist,k00_hora, login,substr(k00_histtxt,0,80) as k00_histtxt
                from arrehist
                left outer join db_usuarios on id_usuario = k00_id_usuario
                where k00_numpre = ".$elementos_numpres[$x]." and k00_numpar = 0");
              if(pg_numrows($resulthist)>0){
                for($di=0;$di<pg_numrows($resulthist);$di++){
                  db_fieldsmemory($resulthist,$di);
                  $histdesc .= $dtlhist." ".$k00_hora." ".$login." ".$k00_histtxt."\n";
                }
              }

              $iContadorUnica++;
              echo "<tr bgcolor=\"$corunica\">\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap></td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap></td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap title=\"".$histdesc."\">".$k00_numpre;
              echo "   <input type=\"hidden\" name=\"np_unica_".($iContadorUnica)."\" value=\"".$k00_numpre."\"> ";
          	  echo "  </td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>00</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>00</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$dtoperunic."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$dtvencunic;
              echo "    <input type=\"hidden\" name=\"dt_unica_".($iContadorUnica)."\"  value=\"".implode('-',array_reverse(explode('/',$dtvencunic)))."\"> ";
              echo "    <input type=\"hidden\" name=\"unica_np_dt".($iContadorUnica)."\" value=\"".$k00_numpre."_".implode('-',array_reverse(explode('/',$dtvencunic)))."\"> ";
              echo "  </td> \n";
              echo "<td colspan=\"3\" class=\"borda\" style=\"font-size:11px;color:white\" nowrap>Parcela Única com $k00_percdes% desconto</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrhis,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrcorr,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrjuros,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrmulta,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrdesconto,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($utotal,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"center\" nowrap>";
	            echo "   <input class='{$sClassVenc}' type=\"checkbox\" id=\"check_id_".($iContadorUnica)."\" style=\"border:none;background-color:write\" name=\"unica\" onclick=\"js_emiteunica('".$k00_numpre."','".$dtvencunic."',this)\" value=\"unica_".$unicont."\">";
              echo " </td>\n ";
              echo "</tr>";



            }
          }
        }
        //

        $temnoti = false;

        if (sizeof($REGISTRO[$i]) > 0) {
          $noti_sql = "select k53_numpre
            from notidebitos
            where k53_numpre = ".$REGISTRO[$i]["k00_numpre"]." and
            k53_numpar = ".$REGISTRO[$i]["k00_numpar"]."
            limit 1";
          $noti_result = db_query($noti_sql);
          if(pg_numrows($noti_result)){
            $temnoti = true;
          }
        }



        echo "<label for=\"CHECK$ContadorUnico\"><tr style=\"cursor: hand\" bgcolor=\"".($cor = (@$cor==$ConfCor2?$ConfCor1:$ConfCor2))."\">\n";
        echo "<td title=\"Informações Adicionais\" class=\"borda\" style=\"font-size:11px\" nowrap onclick=\"parent.js_mostradetalhes('cai3_gerfinanc005.php?".base64_encode($tipo."#".$REGISTRO[$i]["k00_numpre"]."#".$REGISTRO[$i]["k00_numpar"])."','','width=600,height=500,scrollbars=1')\"><a href=\"\" onclick=\"return false;\">
          MI</a></td>\n";

        if($temnoti){
          echo "<td title=\"Notificações Informadas\" class=\"borda\" style=\"font-size:11px\" nowrap onclick=\"parent.js_mostradetalhes('cai3_gerfinanc061.php?chave1=numpre&chave=".$REGISTRO[$i]["k00_numpre"]."&chave2=".$REGISTRO[$i]["k00_numpar"]."','','width=700,height=500,scrollbars=1')\"><a href=\"\" onclick=\"return false;\">
            N</a></td>\n";
        }else{
          echo "<td title=\"Notificações Informadas\" class=\"borda\" style=\"font-size:11px\" nowrap >
            &nbsp</td>\n";

        }

        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap><input style=\"border:none;background-color:$cor\" onclick=\"location.href='cai3_gerfinanc008.php?".base64_encode("numpre=".$elementos_numpres[$x])."'\" type=\"button\" value=\"".$elementos_numpres[$x]."\"></td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap><input type=\"hidden\" id=\"parc$i\" value=\"".$elementos_parcelas[$i]."#".$REGISTRO[$i]["k00_numtot"]."#".$elementos_numpres[$x]."\">".$elementos_parcelas[$i]."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$REGISTRO[$i]["k00_numtot"]."</td>\n";

        //print_r($REGISTRO);exit;

        //Se for divida ativa mostra o exercicio Select para buscar o exercicio
        if($k03_tipo==5) {
          $result_exerc = db_query("select distinct v01_coddiv, v01_exerc from divida where v01_numpre = " . $elementos_numpres[$x] . " and v01_numpar = " . $elementos_parcelas[$i]);
          if (pg_numrows($result_exerc)>=1){
            db_fieldsmemory($result_exerc,0);
          }
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v01_exerc."&nbsp;</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v01_coddiv."&nbsp;</td>\n";
        }else if($k03_tipo==6 or $k03_tipo==13 or $k03_tipo==16 or $k03_tipo==17){//Se for parcelamento mostra o Nº do parcelamento select para buscar o Nº do parcelamento
          $result_parcel = db_query("select distinct v07_parcel from termo where v07_numpre =".$REGISTRO[$i]["k00_numpre"]);
          //echo pg_num_rows($result_parcel);
          if (pg_numrows($result_parcel)==1){
            db_fieldsmemory($result_parcel,0);
          }
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v07_parcel."&nbsp;</td>\n";
        }
        $datajust = $REGISTRO[$i]["datajust"];
        //$data = date("Y-m-d");
        //echo "parcela = $datajust <br>";
        if(db_strtotime($datajust) > (db_getsession("DB_datausu")) ){
          $corDtvenc = '#99CCFF';
        }
        echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtoper==""?"":"bgcolor=$corDtoper")." nowrap>".adodb_date("d-m-Y",$dtoper)."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtvenc==""?"":"bgcolor=$corDtvenc")." nowrap>".adodb_date("d-m-Y",$dtvenc)."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim($REGISTRO[$i]["k01_descr"])==""?"&nbsp":$REGISTRO[$i]["k01_descr"])."</td>\n";
        echo "<td title=\"Lista por receita\" class=\"borda\" style=\"font-size:11px\" nowrap><a href=\"cai3_gerfinanc002.php?".$arg."&tipo=$tipo&agnump=f&agpar=f&emrec=".$emrec."&db_datausu=".adodb_date("Y-m-d",$DB_DATACALC)."&numcgm=".@$numcgm."&inscr=".@$inscr."&matric=".@$matric."&k03_tipo=".@$k03_tipo."&perfil_procuradoria=".@$perfil_procuradoria."&numpre=".@$numpre."\">DE</a></td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim($REGISTRO[$i]["k02_descr"])==""?"&nbsp":$REGISTRO[$i]["k02_descr"])."</td>\n";

        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valor$ContadorUnico\" value=\"".$valor."\">".db_formatar($valor,"f")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valorcorr$ContadorUnico\" value=\"".$valorcorr."\">".db_formatar($valorcorr,"f")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"juros$ContadorUnico\" value=\"".$juros."\">".db_formatar($juros,"f")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"multa$ContadorUnico\" value=\"".$multa."\">".db_formatar($multa,"f")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"desconto$ContadorUnico\" value=\"".$desconto."\">".db_formatar($desconto,"f")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$ContadorUnico\" value=\"".$total."\">".db_formatar($total,"f")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$ContadorUnico\" nowrap>".($tipo==3?"<input type=\"submit\" name=\"calculavalor\" id=\"calculavalor$ContadorUnico\" value=\"Calcular\">":"")."<input class='{$sClassVenc}' style=\"visibility:'visible'\" type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".$numpres."\" onclick=\"js_soma(2)\" id=\"CHECK$ContadorUnico\" name=\"CHECK".$ContadorUnico."\" ".((abs($REGISTRO[$i]["k00_valor"])!=0 && $tipo==3)?"disabled":"").">
          <input style=\"visibility:'visible'\" type=\"hidden\" value=\"".$numpres_valores."\" id=\"_VALORES$ContadorUnico\" name=\"_VALORES".$ContadorUnico."\">
          </td>\n";
        /*

        echo "<td class=\"borda\" style=\"font-size:11px\" id=\"valor$ContadorUnico\" align=\"right\" nowrap>".number_format($valor,2,".",",")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" id=\"valorcorr$ContadorUnico\" align=\"right\" nowrap>".number_format($valorcorr,2,".",",")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" id=\"juros$ContadorUnico\" align=\"right\" nowrap>".number_format($juros,2,".",",")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" id=\"multa$ContadorUnico\" align=\"right\" nowrap>".number_format($multa,2,".",",")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" id=\"desconto$ContadorUnico\" align=\"right\" nowrap>".number_format($desconto,2,".",",")."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$ContadorUnico\" value=\"".$total."\">".number_format($total,2,".",",")."</td>\n";
        */

        //        if($emrec == "t")
        //        else
        //	      echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$ContadorUnico\" nowrap>&nbsp;</td>\n";
        echo "</tr></label>\n";
        /***************************/
        $ContadorUnico++;
      }
      ////////////////////////////////////// AGRUPAMENTO POR RECEITA ///////////////////////////////
    }
  } else {//NIVEL NORMAL

    /**************************************************************************************************************/
    //cria um array com os numpres não repetidos
    $j 				= 0;
    $iContadorUnica = 0;

    $elementos_numpres[0] = "";
    for($i = 0;$i < $numrows;$i++) {
      if(!in_array(pg_result($result,$i,"k00_numpre"),$elementos_numpres)) {
        $elementos_numpres[$j++] = pg_result($result,$i,"k00_numpre");
      }
    }
    for($i = 0;$i < sizeof($elementos_numpres);$i++) {
      $auxValor = 0;
      $auxValorcorr = 0;
      $auxJuros = 0;
      $auxMulta = 0;
      $auxDesconto = 0;
      $auxTotal = 0;
      for($j = 0;$j < $numrows;$j++) {
        if($elementos_numpres[$i] == pg_result($result,$j,"k00_numpre")) {
          if(pg_result($result,$j,"k00_numpar") == @pg_result($result,($j+1),"k00_numpar") and 1==2) {
            $auxValor += (float)pg_result($result,$j,"vlrhis");
            $auxValorcorr += (float)pg_result($result,$j,"vlrcor");
            $auxJuros += (float)pg_result($result,$j,"vlrjuros");
            $auxMulta += (float)pg_result($result,$j,"vlrmulta");
            $auxDesconto += (float)pg_result($result,$j,"vlrdesconto");
            $auxTotal += (float)pg_result($result,$j,"total");
            $SomaDasParcelasValor[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][0] = $auxValor;
            $SomaDasParcelasValorcorr[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][0] = $auxValorcorr;
            $SomaDasParcelasJuros[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][0] = $auxJuros;
            $SomaDasParcelasMulta[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][0] = $auxMulta;
            $SomaDasParcelasDesconto[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][0] = $auxDesconto;
            $SomaDasParcelasTotal[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][0] = $auxTotal;
            //echo $elementos_numpres[$i]." == ".pg_result($result,$j,"k00_numpar")." == ".@pg_result($result,$j+1,"k00_numpar")." == ".$aux."<br>";
          } else {
            $auxValor = 0;
            $auxValorcorr = 0;
            $auxJuros = 0;
            $auxMulta = 0;
            $auxDesconto = 0;
            $auxTotal = 0;
            /*
             $SomaDasParcelasValor[$elementos_numpres[$i]][@pg_result($result,$j+1,"k00_numpar")] = "0";
             $SomaDasParcelasValorcorr[$elementos_numpres[$i]][@pg_result($result,$j+1,"k00_numpar")] = 0;
             $SomaDasParcelasJuros[$elementos_numpres[$i]][@pg_result($result,$j+1,"k00_numpar")] = 0;
             $SomaDasParcelasMulta[$elementos_numpres[$i]][@pg_result($result,$j+1,"k00_numpar")] = 0;
             $SomaDasParcelasDesconto[$elementos_numpres[$i]][@pg_result($result,$j+1,"k00_numpar")] = 0;
             $SomaDasParcelasTotal[$elementos_numpres[$i]][@pg_result($result,$j+1,"k00_numpar")] = 0;
             */
            @$SomaDasParcelasValor[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][pg_result($result,$j,"k00_receit")] += pg_result($result,$j,"vlrhis");
            @$SomaDasParcelasValorcorr[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][pg_result($result,$j,"k00_receit")] += pg_result($result,$j,"vlrcor");
            @$SomaDasParcelasJuros[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][pg_result($result,$j,"k00_receit")] += pg_result($result,$j,"vlrjuros");
            @$SomaDasParcelasMulta[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][pg_result($result,$j,"k00_receit")] += pg_result($result,$j,"vlrmulta");
            @$SomaDasParcelasDesconto[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][pg_result($result,$j,"k00_receit")] += pg_result($result,$j,"vlrdesconto");
            @$SomaDasParcelasTotal[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")][pg_result($result,$j,"k00_receit")] += pg_result($result,$j,"total");
          }
        } else {
          //unset($SomaDasParcelasValor, $SomaDasParcelasValorcorr, $SomaDasParcelasJuros, $SomaDasParcelasMulta, $SomaDasParcelasDesconto, $SomaDasParcelasTotal);
          continue;
        }
      }
    }
    $vlrtotal = 0;
    $verf_parc = "";
    $cont = 0;
    $cont2 = 0;
    $bool = 0;
    $bool2 = 0;
    $ConfCor1 = "#EFE029";
    $ConfCor2 = "#E4F471";

    $listaunica = true;
    for($i = 0;$i < $numrows;$i++) {
      if($elementos_numpres[$cont] != pg_result($result,$i,"k00_numpre")) {
        $listaunica = true;
        $cont++;
        if($bool == 0) {
          $ConfCor1 = "#77EE20";
          $ConfCor2 = "#A9F471";
          $bool = 1;
        } else {
          $ConfCor1 = "#EFE029";
          $ConfCor2 = "#E4F471";
          $bool = 0;
        }
      }
      $vlrtotal += pg_result($result,$i,"total");
      $dtoper = pg_result($result,$i,"k00_dtoper");
      $dtoper = adodb_mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
      //if($dtoper > time())
      //  $corDtoper = "#FF5151";
      //else
      $corDtoper = "";
      $dtvenc = pg_result($result,$i,"k00_dtvenc");
      $dtvenc = adodb_mktime(23,59,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));

      if ( $dtvenc < $DB_DATACALC ) { //time())

      	$corDtvenc  = "red";
      	$sClassVenc = "Vencido";

      } else {

      	$sClassVenc  = "";
        if (adodb_date("d/m/Y",$dtvenc) == adodb_date("d/m/Y",$DB_DATACALC) ) { //time())
          $corDtvenc = "blue";
        } else {
          $corDtvenc = "";
        }
      }
      /*	  if($dtvenc < time())
       $corDtvenc = "red";
       else
       $corDtvenc = "";
       */
      if(pg_result($result,$i,"k00_numpar") == @$salva_parcela) {
        $cor = $ConfCor1;
      } else {
        $cor = $ConfCor2;
        if(pg_result($result,$i,"k00_numpar") == @pg_result($result,$i+1,"k00_numpar"))
        $salva_parcela = "";
        else
        $salva_parcela = @pg_result($result,$i+1,"k00_numpar");
      }


      // unica
      if($tipo!=3){
        if(($elementos_numpres[$cont] == pg_result($result,$i,"k00_numpre")) && $listaunica) {
          //	    if($verf_parc != pg_result($result,$i,"k00_numpar") && $emrec == "t") {
          $listaunica = false;
          $resultunica = db_query(
          "select *,
              substr(fc_calcula,2,13)::float8 as uvlrhis,
              substr(fc_calcula,15,13)::float8 as uvlrcor,
              substr(fc_calcula,28,13)::float8 as uvlrjuros,
              substr(fc_calcula,41,13)::float8 as uvlrmulta,
              substr(fc_calcula,54,13)::float8 as uvlrdesconto,
              (substr(fc_calcula,15,13)::float8+
              substr(fc_calcula,28,13)::float8+
              substr(fc_calcula,41,13)::float8-
              substr(fc_calcula,54,13)::float8) as utotal
              from (
              select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic, r.k00_percdes,
              fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")
              from recibounica r
              where r.k00_numpre = ".pg_result($result,$i,"k00_numpre")." and r.k00_dtvenc >= '".date('Y-m-d', $DB_DATACALC)."'
              ) as unica");


          for($unicont=0;$unicont<pg_numrows($resultunica);$unicont++){
            db_fieldsmemory($resultunica,$unicont);
            if($dtvencunic>=adodb_date('Y-m-d',$DB_DATACALC)){
              $dtvencunic = db_formatar($dtvencunic,'d');
              $dtoperunic = db_formatar($dtoperunic,'d');
              $corunica = "#009933";
              $uvlrcorr = 0;
              $histdesc = "";
              $resulthist = db_query("select k00_dtoper as dtlhist,k00_hora, login,substr(k00_histtxt,0,80) as k00_histtxt
                  from arrehist
                  left outer join db_usuarios on id_usuario = k00_id_usuario
                  where k00_numpre = ".pg_result($result,$i,"k00_numpre")." and k00_numpar = 0");
              if(pg_numrows($resulthist)>0){
                for($di=0;$di<pg_numrows($resulthist);$di++){
                  db_fieldsmemory($resulthist,$di);
                  $histdesc .= $dtlhist." ".$k00_hora." ".$login." ".$k00_histtxt."\n";
                }
              }

              $iContadorUnica++;
              echo "<tr bgcolor=\"$corunica\">\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap></td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap></td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap title=\"".$histdesc."\">".$k00_numpre;
              echo "   <input type=\"hidden\" name=\"np_unica_".($iContadorUnica)."\" value=\"".$k00_numpre."\"> ";
          	  echo "  </td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>00</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>00</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$dtoperunic."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$dtvencunic;
              echo "    <input type=\"hidden\" name=\"dt_unica_".($iContadorUnica)."\"  value=\"".implode('-',array_reverse(explode('/',$dtvencunic)))."\"> ";
              echo "    <input type=\"hidden\" name=\"unica_np_dt".($iContadorUnica)."\" value=\"".$k00_numpre."_".implode('-',array_reverse(explode('/',$dtvencunic)))."\"> ";
              echo "  </td> \n";
              echo "<td colspan=\"3\" class=\"borda\" style=\"font-size:11px;color:white\" nowrap>Parcela Única com $k00_percdes% desconto</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrhis,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrcorr,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrjuros,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrmulta,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($uvlrdesconto,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".db_formatar($utotal,"f")."</td>\n";
              echo "<td class=\"borda\" style=\"font-size:11px\" align=\"center\" nowrap>";
	          echo "   <input class='{$sClassVenc}' type=\"checkbox\" id=\"check_id_".($iContadorUnica)."\" style=\"border:none;background-color:write\" name=\"unica\" onclick=\"js_emiteunica('".$k00_numpre."','".$dtvencunic."',this)\" value=\"unica_".$unicont."\">";
              echo " </td>\n ";
              echo "</tr>";
            }
          }
        }
      }
      //
      $noti_sql = "select k53_numpre
          from notidebitos
          where k53_numpre = ".pg_result($result,$i,"k00_numpre")." and
          k53_numpar = ".pg_result($result,$i,"k00_numpar")."
          limit 1";
      $noti_result = db_query($noti_sql);
      $temnoti = false;
      if(pg_numrows($noti_result)){
        $temnoti = true;

      }

      echo "<label for=\"CHECK$i\"><tr style=\"cursor: hand\" bgcolor=\"".$cor."\">\n";
      echo "<td title=\"Informações Adicionais\" class=\"borda\" style=\"font-size:11px; cursos:hand\" nowrap onclick=\"parent.js_mostradetalhes('cai3_gerfinanc005.php?".base64_encode($tipo."#".pg_result($result,$i,"k00_numpre")."#".pg_result($result,$i,"k00_numpar"))."','','width=600,height=500,scrollbars=1')\"><a href=\"\" onclick=\"return false;\">
          MI</a></td>\n";

      if($temnoti){
        echo "<td title=\"Notificações Informadas\" class=\"borda\" style=\"font-size:11px\" nowrap onclick=\"parent.js_mostradetalhes('cai3_gerfinanc061.php?chave1=numpre&chave=".pg_result($result,$i,"k00_numpre")."&chave2=".pg_result($result,$i,"k00_numpar")."','','width=700,height=500,scrollbars=1')\"><a href=\"\" onclick=\"return false;\">
            N</a></td>\n";
      }else{
        echo "<td title=\"Notificações Informadas\" class=\"borda\" style=\"font-size:11px\" nowrap >
            &nbsp</td>\n";
      }

      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap><input style=\"border:none;background-color:$cor\" onclick=\"location.href='cai3_gerfinanc008.php?".base64_encode("numpre=".pg_result($result,$i,"k00_numpre"))."'\" type=\"button\" value=\"".pg_result($result,$i,"k00_numpre")."\"></td>\n";

      //      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".pg_result($result,$i,"k00_numpre")."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_numpar"))==""?"&nbsp":pg_result($result,$i,"k00_numpar"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_numtot"))==""?"&nbsp":pg_result($result,$i,"k00_numtot"))."</td>\n";

      //Se for divida ativa mostra o exercicio Select para buscar o exercicio
      if($k03_tipo==5){
        $result_exerc = db_query("select distinct v01_coddiv, v01_exerc from divida where v01_numpre =".pg_result($result,$i,"k00_numpre") . " and v01_numpar = " . trim(pg_result($result,$i,"k00_numpar")));

        if (pg_numrows($result_exerc)>=1){
          db_fieldsmemory($result_exerc,0);
        }
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v01_exerc."&nbsp;</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v01_coddiv."&nbsp;</td>\n";
      }else if($k03_tipo==6 or $k03_tipo==13 or $k03_tipo==16 or $k03_tipo==17){//Se for parcelamento mostra o Nº do parcelamento select para buscar o N do parcelamento
        $result_parcel = db_query("select distinct v07_parcel from termo where v07_numpre =".pg_result($result,$i,"k00_numpre"));
        if (pg_numrows($result_parcel)==1){
          db_fieldsmemory($result_parcel,0);
        }
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"\" value=\"\">".@$v07_parcel."&nbsp;</td>\n";
      } elseif ($aguaColunaContrato === true) {

        // buscamos o numero do contrato e o numero da economia do contrato (caso exista)
        $resultAguaContrato = db_query("select x22_aguacontrato, (case when x22_manual = '1' then x22_aguacontratoeconomia else null end) as x22_aguacontratoeconomia from aguacalc where x22_numpre = " . pg_result($result,$i,"k00_numpre") . " limit 1");

        $x22_aguacontrato = '';
        $x22_aguacontratoeconomia = '';

        if ($resultAguaContrato && pg_num_rows($resultAguaContrato) > 0) {

          $x22_aguacontrato = pg_result($resultAguaContrato, 0, "x22_aguacontrato");
          $x22_aguacontratoeconomia = pg_result($resultAguaContrato, 0, "x22_aguacontratoeconomia");
        }

        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>" . $x22_aguacontrato . "</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>" . $x22_aguacontratoeconomia . "</td>\n";
      }

        $datajust =  pg_result($result,$i,"datajust");
        $data = db_getsession("DB_datausu");
        //echo "receita $i= $datajust <br>";
        if(db_strtotime($datajust) > $data){
          $corDtvenc = '#99CCFF';
        }

      $lPermissao = db_permissaomenu(db_getsession('DB_anousu'),81,8135);

      if ( $lPermissao == 'true' ) {
      	$sDisabled = '';
      } else {
      	$sDisabled = 'disabled';
      }

      echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtoper==""?"":"bgcolor=$corDtoper")." nowrap>".adodb_date("d-m-Y",$dtoper)."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtvenc==""?"":"bgcolor=$corDtvenc")." nowrap>".adodb_date("d-m-Y",$dtvenc)."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k01_descr"))==""?"&nbsp":pg_result($result,$i,"k01_descr"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_receit"))==""?"&nbsp":pg_result($result,$i,"k00_receit"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k02_descr"))==""?"&nbsp":pg_result($result,$i,"k02_descr"))."</td>\n";
      echo "<script>js_putInputValue('VAL_ISS$i', '".$SomaDasParcelasValor[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")][pg_result($result,$i,"k00_receit")]."');</script>";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valor$i\" value=\"".$SomaDasParcelasValor[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")][pg_result($result,$i,"k00_receit")]."\">".(trim(pg_result($result,$i,"vlrhis"))==""?"&nbsp":((abs(pg_result($result,$i,"k00_valor"))==0 && $tipo==3)?"<input style=\"height: 18px; border: 1px solid #999; font-size: 12px; text-align: right\" onfocus=\"if(parent.document.getElementById('enviar').value == 'Agrupar') parent.document.getElementById('enviar').disabled = true;\" type=\"text\" id=\"VAL_ISS$i\" name=\"VAL_ISS".$i."\" value=\"".abs(pg_result($result,$i,"valor_variavel"))."\"maxlength=\"12\" onkeypress=\"return js_teclas(event)\" size=\"6\" onblur=\"return js_validatamanho(this.value,'calculavalor$i','VAL_ISS$i');\" {$sDisabled} >":db_formatar(pg_result($result,$i,"vlrhis"),"f")))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valorcorr$i\" value=\"".$SomaDasParcelasValorcorr[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")][pg_result($result,$i,"k00_receit")]."\">".(trim(pg_result($result,$i,"vlrcor"))==""?"&nbsp":db_formatar(pg_result($result,$i,"vlrcor"),"f"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"juros$i\" value=\"".$SomaDasParcelasJuros[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")][pg_result($result,$i,"k00_receit")]."\">".(trim(pg_result($result,$i,"vlrjuros"))==""?"&nbsp":db_formatar(pg_result($result,$i,"vlrjuros"),"f"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"multa$i\" value=\"".$SomaDasParcelasMulta[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")][pg_result($result,$i,"k00_receit")]."\">".(trim(pg_result($result,$i,"vlrmulta"))==""?"&nbsp":db_formatar(pg_result($result,$i,"vlrmulta"),"f"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"desconto$i\" value=\"".$SomaDasParcelasDesconto[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")][pg_result($result,$i,"k00_receit")]."\">".(trim(pg_result($result,$i,"vlrdesconto"))==""?"&nbsp":db_formatar(pg_result($result,$i,"vlrdesconto"),"f"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$i\" value=\"".$SomaDasParcelasTotal[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")][pg_result($result,$i,"k00_receit")]."\">".(trim(pg_result($result,$i,"total"))==""?"&nbsp":db_formatar(pg_result($result,$i,"total"),"f"))."</td>\n";

						if($elementos_numpres[$cont2] == pg_result($result,$i,"k00_numpre")) {
						  echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3?"<input type=\"submit\" class=\"opa1\"  onclick=\"this.form.target = '';return js_validatamanho(document.getElementById('VAL_ISS$i').value,'calculavalor$i','VAL_ISS$i');\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\" $sDisabled >":"")."<input class='{$sClassVenc}' style=\"visibility:'visible'\" type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."P".pg_result($result,$i,"k00_numpar")."R".pg_result($result,$i,"k00_receit")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".((abs(pg_result($result,$i,"k00_valor"))!=0 && $tipo==3)?"disabled":"").">
                <input style=\"visibility:'visible'\" type=\""."hidden"."\" value=\"".pg_result($result,$i,"total")."\" id=\"_VALORES$i\" name=\"_VALORES$i\">
                </td>\n";
						  $verf_parc = pg_result($result,$i,"k00_numpar");
						} else {
						  $cont2++;
						  $verf_parc = pg_result($result,$i,"k00_numpar");
						  echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3?"<input type=\"submit\" class=\"opa2\" onclick=\"this.form.target = ''\" name=\"calculavalor\"  id=\"calculavalor$i\" value=\"Calcular\" $sDisabled >":"")."<input class='{$sClassVenc}' style=\"visibility:'visible'\" type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."P".pg_result($result,$i,"k00_numpar")."R".pg_result($result,$i,"k00_receit")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".((abs(pg_result($result,$i,"k00_valor"))!=0 && $tipo==3)?"disabled":"")."></td>\n";
						}
						echo "</tr></label>\n";


    }
  }	////////****************************************************************************************/
  echo "</table>\n</form>\n";
  echo "<script>
        parent.document.getElementById('valor1').innerHTML = \"0.00\";
        parent.document.getElementById('valorcorr1').innerHTML = \"0.00\";
        parent.document.getElementById('juros1').innerHTML = \"0.00\";
        parent.document.getElementById('multa1').innerHTML = \"0.00\";
        parent.document.getElementById('desconto1').innerHTML = \"0.00\";
        parent.document.getElementById('total1').innerHTML = \"0.00\";

        parent.document.getElementById('valor2').innerHTML = \"0.00\";
        parent.document.getElementById('valorcorr2').innerHTML = \"0.00\";
        parent.document.getElementById('juros2').innerHTML = \"0.00\";
        parent.document.getElementById('multa2').innerHTML = \"0.00\";
        parent.document.getElementById('desconto2').innerHTML = \"0.00\";
        parent.document.getElementById('total2').innerHTML = \"0.00\";

        parent.document.getElementById('valor3').innerHTML = \"0.00\";
        parent.document.getElementById('valorcorr3').innerHTML = \"0.00\";
        parent.document.getElementById('juros3').innerHTML = \"0.00\";
        parent.document.getElementById('multa3').innerHTML = \"0.00\";
        parent.document.getElementById('desconto3').innerHTML = \"0.00\";
        parent.document.getElementById('total3').innerHTML = \"0.00\";
        parent.document.getElementById('relatorio').disabled = false;
        js_soma(1);
        </script>\n";
}
?></center>
</body>
</html>
<script>
      /*
      parent.document.getElementById('valor').innerText = '&nbsp;';
      parent.document.getElementById('valorcorr').innerText = '&nbsp;';
      parent.document.getElementById('juros').innerText = '&nbsp;';
      parent.document.getElementById('multa').innerText = '&nbsp';
      parent.document.getElementById('desconto').innerText = '&nbsp;';
      parent.document.getElementById('total').innerText = '&nbsp;';
      */
      parent.document.getElementById('btmarca').value = "Marcar Todas";
      parent.document.getElementById('enviar').disabled = true;
      var tipo = <?=(!isset($tipo)?-1:$tipo)?>;
      if(tipo == 3) {
        parent.document.getElementById('enviar').value = 'Agrupar';
        parent.document.getElementById('enviar').disabled = false;
      }
      </script>
<?
}
echo "<script>
          parent.document.js_parc = parent.js_parc_copia;
		      </script>";

?>
