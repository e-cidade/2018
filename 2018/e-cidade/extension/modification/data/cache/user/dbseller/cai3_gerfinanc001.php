<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

require_once(modification("classes/db_iptubase_classe.php"));
require_once(modification("classes/db_issbase_classe.php"));
require_once(modification("classes/db_arreprescr_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_numpref_classe.php"));
require_once(modification("classes/db_termoanu_classe.php"));
require_once(modification("classes/db_fiscal_classe.php"));
require_once(modification("classes/db_levanta_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));

require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);

if (session_is_registered("DB_tipodebitoparcel")) {
  db_putsession("DB_tipodebitoparcel", "");
}

$clcgm       = new cl_cgm();
$clfiscal    = new cl_fiscal();
$cllevanta   = new cl_levanta();
$cltermoanu  = new cl_termoanu();
$cldb_config = new cl_db_config();
$clcgm->rotulo->label();
$clrotulo    = new rotulocampo();
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('k00_numpre');
$clrotulo->label('v07_parcel');
$clrotulo->label('k50_notifica');

$iInstitSessao = db_getsession('DB_instit');
$result = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
db_fieldsmemory($result, 0);

$clnumpref = new cl_numpref();
$resnumpref = $clnumpref->sql_record($clnumpref->sql_query_file(db_getsession("DB_anousu"), db_getsession('DB_instit'), "k03_certissvar"));
if ($resnumpref == false || $clnumpref->numrows == 0) {
  throw new \ECidade\V3\Extension\Exceptions\ResponseException("Tabela de parâmetro (numpref) não configurada! Verifique com administrador");
  db_redireciona("corpo.php");
  exit();
} else {
  db_fieldsmemory($resnumpref, 0);
}

// Verifica se Sistema de Agua esta em Uso
db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

if (isset($db21_usasisagua) && $db21_usasisagua != '') {
  $db21_usasisagua = ($db21_usasisagua == 't');
  if ($db21_usasisagua == true) {
    $j18_nomefunc = "func_aguabase.php";
  } else {
    $j18_nomefunc = "func_iptubase.php";
  }
} else {
  $db21_usasisagua = false;
  $j18_nomefunc = "func_iptubase.php";
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewImportacaoDiversos.classe.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>

    <style type="text/css">
      <!--
      .tabcols {
        font-size: 11px;
      }

      .tabcols1 {
        text-align: right;
        font-size: 11px;
      }

      .btcols {
        height: 17px;
        font-size: 10px;
      }

      .links {
        font-weight: bold;
        color: #0033FF;
        text-decoration: none;
        font-size: 10px;
        cursor: hand;
      }

      a.links:hover {
        color: black;
        text-decoration: underline;
      }

      .links2 {
        font-weight: bold;
        color: #0587CD;
        text-decoration: none;
        font-size: 10px;
      }

      a.links2:hover {
        color: black;
        text-decoration: underline;
      }

      .nome {
        color: black;
      }

      a.nome:hover {
        color: blue;
      }
      -->
      #janelaRecibo{
        -moz-user-select: none;
      }
    </style>
    <script>

      function js_parc_copia(){
        numpre = "";
        deb = debitos.document.form1;
        nump = "";
        x = 0;
        for(i=0;i<deb.length;i++) {
          if (deb.k03_parcelamento.value == 't') {
            if (deb.elements[i].type == "checkbox") {
              if (deb.elements[i].checked == true) {
                numpre = deb.elements[i].value.split("N");
                numpre = numpre[0].split("P")
                if(nump == ""){
                  nump = numpre[0];
                }else{
                  if(numpre[0] != nump){
                    alert('Você deve reparcelar um parcelamento de cada vez!')
                    x = 1;
                    break;
                  }
                  nump = numpre[0]
                }
              }
            }
          }
        }
        if(x == 0){
          debitos.document.form1.action = 'cai3_gerfinanc062.php?valor='+document.getElementById('total2').innerHTML+'&valorcorr='+document.getElementById('valorcorr2').innerHTML+'&juros='+document.getElementById('juros2').innerHTML+'&multa='+document.getElementById('multa2').innerHTML+'&japarcelou='+document.form1.japarcelou.value+'&numpresaparcelar='+document.form1.numpresaparcelar.value+'&numparaparcelar='+document.form1.numparaparcelar.value;
          debitos.document.form1.target = '_self';
          debitos.document.form1.submit();
        }
      }
      function js_parc(){
        numpre = "";
        deb = debitos.document.form1;
        nump = "";
        x = 0;
        for(i=0;i<deb.length;i++) {
          if (deb.k03_parcelamento.value == 't') {
            if (deb.elements[i].type == "checkbox") {
              if (deb.elements[i].checked == true) {
                numpre = deb.elements[i].value.split("N");
                numpre = numpre[0].split("P")
                if(nump == ""){
                  nump = numpre[0];
                }else{
                  if(numpre[0] != nump){
                    alert('Você deve reparcelar um parcelamento de cada vez!')
                    x = 1;
                    break;
                  }
                  nump = numpre[0]
                }
              }
            }
          }
        }
        if(x == 0){
          debitos.document.form1.action = 'cai3_gerfinanc062.php?valor='+document.getElementById('total2').innerHTML+'&valorcorr='+document.getElementById('valorcorr2').innerHTML+'&juros='+document.getElementById('juros2').innerHTML+'&multa='+document.getElementById('multa2').innerHTML+'&japarcelou='+document.form1.japarcelou.value+'&numpresaparcelar='+document.form1.numpresaparcelar.value+'&numparaparcelar='+document.form1.numparaparcelar.value;
          debitos.document.form1.target = '_self';
          debitos.document.form1.submit();
        }
      }

      function js_MudaLink(nome) {
        document.getElementById('processando').style.visibility = 'visible';
        if(navigator.appName == "Netscape") {
          TIPO = document.getElementById(nome).getElementsByTagName("a")[0].firstChild.nodeValue;
        } else {
          TIPO = document.getElementById(nome).innerText;
          document.getElementById('processando').style.top = 113;
        }

        document.getElementById('processandoTD').innerHTML = '<h3>Aguarde, processando ' + TIPO + '...</h3>';
        for(i = 0;i < document.links.length;i++) {
          var L = document.links[i].id;
          if(L!=""){
            document.getElementById(L).classList.remove('background-yellow');
            document.getElementById(L).hideFocus = true;
          }
        }
        document.getElementById(nome).classList.add('background-yellow');

        if(nome.indexOf("tiposemdeb") != -1) {
          document.getElementById('enviar').disabled          = true;
          document.getElementById('btmarca').disabled         = true;
          document.getElementById('btmarcavencidas').disabled = true;
        } else {
          document.getElementById('btmarca').disabled         = false;
          document.getElementById('btmarcavencidas').disabled = false;
        }

        document.getElementById('valor1').innerHTML     = "0.00";
        document.getElementById('valorcorr1').innerHTML = "0.00";
        document.getElementById('juros1').innerHTML     = "0.00";
        document.getElementById('multa1').innerHTML     = "0.00";
        document.getElementById('desconto1').innerHTML  = "0.00";
        document.getElementById('total1').innerHTML     = "0.00";

        document.getElementById('valor2').innerHTML     = "0.00";
        document.getElementById('valorcorr2').innerHTML = "0.00";
        document.getElementById('juros2').innerHTML     = "0.00";
        document.getElementById('multa2').innerHTML     = "0.00";
        document.getElementById('desconto2').innerHTML  = "0.00";
        document.getElementById('total2').innerHTML     = "0.00";

        document.getElementById('valor3').innerHTML     = "0.00";
        document.getElementById('valorcorr3').innerHTML = "0.00";
        document.getElementById('juros3').innerHTML     = "0.00";
        document.getElementById('multa3').innerHTML     = "0.00";
        document.getElementById('desconto3').innerHTML  = "0.00";
        document.getElementById('total3').innerHTML     = "0.00";

      }

      function js_relatorio() {
        var numcgm = (typeof(debitos.numcgm)=="undefined")?"":debitos.numcgm;
        var matric = (typeof(debitos.matric)=="undefined")?"":debitos.matric;
        var inscr = (typeof(debitos.inscr)=="undefined")?"":debitos.inscr;
        var numpre = (typeof(debitos.numpre)=="undefined")?"":debitos.numpre;
        var tipo = debitos.tipo;
        alert('Utilizar a emissão do relatório pelo total dos débitos');
      }

      function js_emiterecibo() {

        if ($F('k00_dtoper') == "") {

          alert("Campo Data Pagamento é de preenchimento obrigatório!");
          return;

        }

        if(document.getElementById('forcarvencimento')){

          var forcarvencimento = debitos.document.createElement("INPUT");
          var valforcar = '';
          forcarvencimento.setAttribute("type","hidden");
          forcarvencimento.setAttribute("name","forcarvencimento");
          forcarvencimento.setAttribute("id","forcarvencimento");
          debitos.document.getElementById('form1').appendChild(forcarvencimento);
        }
        if(document.getElementById('forcarvencimento').checked){
          valforcar = 'true';
        }else{
          valforcar = 'false';
        }
        //forcarvencimento.setAttribute("value",valforcar);

        // processar descontos processarDescontoRecibo
        if(!document.getElementById('processarDescontoRecibo')){

          var processarDescontoRecibo = debitos.document.createElement("INPUT");
          var valforcar = '';
          processarDescontoRecibo.setAttribute("type","hidden");
          processarDescontoRecibo.setAttribute("name","processarDescontoRecibo");
          processarDescontoRecibo.setAttribute("id","processarDescontoRecibo");

          debitos.document.getElementById('form1').appendChild(processarDescontoRecibo);

        }

        if(document.getElementById('processarDescontoRecibo').checked){
          valforcar1 = 'true';
        }else{
          valforcar1 = 'false';
        }

        if(document.getElementById('enviar').value != 'Agrupar') {

          iConfirm           = 0;
          lEmite             = true;
          oParam             = new Object();
          oParam.exec        = "validaRecibo";
          oParam.oDadosForm  = debitos.document.form1.serialize(true);

          oParam.oDadosForm.processarDescontoRecibo = valforcar1;
          oParam.oDadosForm.forcarvencimento        = valforcar;

          oParam.oDadosForm.k00_dtoper = $F('k00_dtoper');

          var oAjax2 = new Ajax.Request("cai3_emitecarne.RPC.php",
              {method    : 'post',
               parameters: 'json='+Object.toJSON(oParam),
               onComplete:
                 function(oAjax2) {

                   var oRetorno = eval("("+oAjax2.responseText+")");
                   var sMsg     = oRetorno.message.urlDecode().replace("/\\n/g","\n");
                   if (oRetorno.status == 2) {
                     alert(sMsg);
                   } else {

                     if(oRetorno.iConfirm == 1){

                       if(confirm(sMsg)){
                         js_emiteReciboCarne(oParam,true,false);
                       } else {
                         js_emiteReciboCarne(oParam,false,true);
                       }
                     } else {
                       js_emiteReciboCarne(oParam,true);
                     }
                   }
                 }
              });


          function js_emiteReciboCarne(oParam,lNovoRecibo,lForcajanela){

            js_divCarregando('Processando...', 'msgBox');
            oParam.exec          = "geraRecibo_Carne";
            oParam.lNovoRecibo   = lNovoRecibo;
            var oAjax            = new Ajax.Request("cai3_emitecarne.RPC.php",
                                         {method    : 'post',
                                          parameters: 'json='+Object.toJSON(oParam),
                                          onComplete:
                                            function(oAjax) {
                                              js_removeObj('msgBox');
                                              var oRetorno = eval("("+oAjax.responseText+")");
                                              if (oRetorno.status == 2) {
                                                alert(oRetorno.message.urlDecode().replace("/\\n/gm","\n"));
                                              } else {
                                                var lMostra = true;
                                                var sUrl    = 'cai3_emiterecibo.php?json='+Object.toJSON(oRetorno);

                                                if ((oRetorno.recibos_emitidos.length == 1 && oRetorno.aSessoesCarne.length == '0') && !lForcajanela) {

                                                  var lForcarVencimento = $('forcarvencimento').checked;
                                                  sUrl    = 'cai3_gerfinanc003.php';
                                                  sUrl   += debitos.location.search;
                                                  sUrl   += '&sessao=' + oRetorno.aSessoesRecibo[0];
                                                  sUrl   += '&reemite_recibo=true';
                                                  sUrl   += '&forcarvencimento='+lForcarVencimento;
                                                  sUrl   += '&k03_numpre=' + oRetorno.recibos_emitidos[0];
                                                  sUrl   += '&k03_numnov=' + oRetorno.recibos_emitidos[0];
                                                  oJanela = window.open(sUrl,'reciboweb2','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
                                                  sUrl   += '&reemite_recibo=true';
                                                  oJanela.moveTo(0,0);
                                                } else if (((oRetorno.recibos_emitidos.length == 0 || oRetorno.aSessoesRecibo.length == 0) && oRetorno.aSessoesCarne.length == 1)  && !lForcajanela) {

                                                  sUrl    = 'cai3_gerfinanc033.php'+debitos.location.search +'&sessao=' + oRetorno.aSessoesCarne[0];
                                                  oJanela = window.open(sUrl,'reciboweb2','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
                                                  oJanela.moveTo(0,0);
                                                } else if (((oRetorno.recibos_emitidos.length == 0 || oRetorno.aSessoesRecibo.length == 0) && oRetorno.aSessoesCarne.length == 0)) {

                                                } else {
                                                  /**
                                                   * Cria Janela
                                                   */
                                                  var windowEmissao     = new windowAux('janelaRecibo','Emissão de Recibos / Carnês',screen.availWidth-40,550);
                                                      windowEmissao.setContent("<div id='messageRecibo'></div><div id='conteudoRecibo'></div>");
                                                      windowEmissao.setShutDownFunction(function(){
                                                        document.body.removeChild(document.getElementById('janelaRecibo'));
                                                      });
                                                  windowEmissao.show(25,10);

                                                  var oMessageBoard = new DBMessageBoard('msgboard1','Impressão de Recibos/ Carnês','Clique no botão emitir no carnê ou recibo selecionado. ',$('messageRecibo'));
                                                      oMessageBoard.show();
                                                  var oIframeConteudo = document.createElement("iframe");
                                                      oIframeConteudo.src         = sUrl;
                                                      oIframeConteudo.frameBorder = 0;
                                                      oIframeConteudo.id          = 'db_iframe_recibos';
                                                      oIframeConteudo.name        = 'db_iframe_recibos';
                                                      oIframeConteudo.scrolling   = 'auto';
                                                      oIframeConteudo.width       = (screen.availWidth-50)+'px';
                                                      var Altura = $('janelaRecibo').clientHeight - $('msgboard1').clientHeight - 35;
                                                      oIframeConteudo.height      = Altura+'px';

                                                  $('conteudoRecibo').appendChild(oIframeConteudo);
                                               }
                                             }
                                           }
                                         }
                                        ) ;
            if((elem = debitos.document.getElementById("geracarne"))){
               elem.parentNode.removeChild(elem);
            }
          }
        } else {

          var tab = debitos.document.getElementById('tabdebitos');
          for(i = 1;i < tab.rows.length;i++) {
            var num = new Number(tab.rows[i].cells[10].childNodes[1].nodeValue);
            num = Math.abs(num);
          }

          var cor = "";
          for(i = 1;i < tab.rows.length;i++) {
            cor = (cor=="#E4F471")?"#EFE029":"#E4F471";
            tab.rows[i].bgColor = cor;
            if(tab.rows[i].cells[16].childNodes[0].attributes["type"].nodeValue == "submit") {
              var elem = debitos.document.getElementById(tab.rows[i].cells[16].childNodes[0].attributes["id"].nodeValue);
              elem.parentNode.removeChild(elem);
            }
            if(tab.rows[i].cells[16].childNodes[0].attributes["type"].nodeValue == "hidden") {
              var inp = debitos.document.createElement("INPUT");
              inp.setAttribute("type","checkbox");
              inp.setAttribute("name",tab.rows[i].cells[16].childNodes[0].attributes["name"].nodeValue);
              inp.setAttribute("id",tab.rows[i].cells[16].childNodes[0].attributes["id"].nodeValue);
              inp.setAttribute("value",tab.rows[i].cells[16].childNodes[0].attributes["value"].nodeValue);
              if(navigator.appName == "Netscape")
              inp.addEventListener("click",debitos.js_soma,false);
              else
              inp.onclick = debitos.js_soma;
              tab.rows[i].cells[16].appendChild(inp);
              var elem = debitos.document.getElementById(tab.rows[i].cells[16].childNodes[0].attributes["id"].nodeValue);
              elem.parentNode.removeChild(elem);
            }
          }
          document.getElementById("enviar").value = 'Emite Recibo';
          document.getElementById("enviar").disabled = true;
        }
      }

      function limpaparcela(qual) {
        debitos.document.getElementById(qual).checked=false;
        debitos.document.getElementById(qual).style.visibility='hidden';
        document.getElementById("enviar").disabled = true;
      }

      function js_emiteCarneUnica() {
        debitos.js_enviarUnica();
      }

      function js_emitecarne(qualcarne) {

        var chi = debitos.document.createElement("INPUT");
        chi.setAttribute("type","hidden");
        chi.setAttribute("name","geracarne");
        chi.setAttribute("id","geracarne");
        if (qualcarne == true) {
          chi.setAttribute("value","banco");
        } else {
          chi.setAttribute("value","prefeitura");
        }

        debitos.document.getElementById('form1').appendChild(chi);

        if (document.getElementById('emisscarne')) {

          var emiscarneiframe = debitos.document.createElement("INPUT");
          emiscarneiframe.setAttribute("type","hidden");
          emiscarneiframe.setAttribute("name","emiscarneiframe");
          emiscarneiframe.setAttribute("id","emiscarneiframe");
          emiscarneiframe.setAttribute("value",document.getElementById('emisscarne').value);
          debitos.document.getElementById('form1').appendChild(emiscarneiframe);
        }

        js_emiterecibo();
      }

      function js_verifica(opcaolibera){
        var vari = '';
        for(i = 0;i < document.links.length;i++) {
          L = new String(document.links[i].href);
          if(L.lastIndexOf('cai3_gerfinanc001.php') != -1){
            alert(L.valueOf());
          }
        }
      }

      function js_outrasopcoes(chave){
        if(chave==1){
          if(debitos.document != ""){
            debitos.document.form1.target="";
            debitos.document.form1.action="cai3_gerfinanc012.php";
            debitos.document.form1.submit();
          }else{
            alert("Selecione uma Dívida para dar Desconto.");
          }
        }
      }

      function js_geraNotif(){

        var sUrl = 'valor='+document.getElementById('total2').innerHTML+
                   '&valorcorr='+document.getElementById('valorcorr2').innerHTML+
                   '&juros='+document.getElementById('juros2').innerHTML+
                   '&multa='+document.getElementById('multa2').innerHTML+
                   '&japarcelou='+document.form1.japarcelou.value+
                   '&numpresaparcelar='+document.form1.numpresaparcelar.value+
                   '&numparaparcelar='+document.form1.numparaparcelar.value+
                   '&marcartodas='+document.getElementById('marcartodas').value+
                   '&marcarvencidas='+document.getElementById('marcarvencidas').value;

        debitos.document.form1.action = 'cai3_gerfinanc073.php?'+sUrl;
        debitos.document.form1.target = '_self';
        debitos.document.form1.submit();

      }

      function js_suspender(){

        var sUrl = 'valor='+document.getElementById('total2').innerHTML+
                   '&valorcorr='+document.getElementById('valorcorr2').innerHTML+
                   '&juros='+document.getElementById('juros2').innerHTML+
                   '&multa='+document.getElementById('multa2').innerHTML+
                   '&japarcelou='+document.form1.japarcelou.value+
                   '&numpresaparcelar='+document.form1.numpresaparcelar.value+
                   '&numparaparcelar='+document.form1.numparaparcelar.value+
                   '&marcartodas='+document.getElementById('marcartodas').value+
                   '&marcarvencidas='+document.getElementById('marcarvencidas').value;

        debitos.document.form1.action = 'cai3_gerfinanc074.php?'+sUrl;
        debitos.document.form1.target = '_self';
        debitos.document.form1.submit();

      }


      function js_emitenotificacao(){
        var chi = debitos.document.createElement("INPUT");
        chi.setAttribute("type","hidden");
        chi.setAttribute("name","notificacao_tipo");
        chi.setAttribute("id","notificacao_tipo");
        debitos.document.getElementById('form1').appendChild(chi);
        debitos.document.getElementById('form1').action='cai3_gerfinanc060.php';
        debitos.document.getElementById('form1').target='';
        debitos.document.getElementById('form1').submit();
      }

      function js_label(liga,str){
        if(liga=='true'){
          document.getElementById('tab_label').style.visibility='visible';
          document.getElementById('label_numpre').innerHTML=str;
        }else{
          document.getElementById('tab_label').style.visibility='hidden';
        }

      }

    </script>
    <link href="estilos.css"            rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
<body class="body-default">
<div id="DDD"></div>
<div id="processando"
  style="position: absolute; left: 05px; top: 113px; width: 99%; height: 235px; z-index: 1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
<Table width="99%">
  <tr>
    <td align="center" valign="middle" id="processandoTD"
      onclick="document.getElementById('processando').style.visibility='hidden'"></td>
  </tr>
</Table>
</div>

<table  id="tab_label" class="cabec"
  style="position: absolute; z-index: 1; background-color: #cccccc; top: 350; left: 30; ">
  <tr>
    <td><font color="darkblue"> <span id="label_numpre"> </span> </font></td>
  </tr>
</table>

<?php
$mensagem_semdebitos = false;
$com_debitos = true;

//----------Tipo de Filtro usado para consulta e Cod. do mesmo. -----------


$tipo_filtro = "";
$cod_filtro = "";

////////////////////////////////////////////////


if (isset($HTTP_POST_VARS ["pesquisar"]) || isset($matricula) || isset($inscricao)) {

  ?>

  <div style="width: 99%; padding: 0px 2px;">

  <?php

  db_destroysession("conteudoparc");

  //aqui é pra se clicar no link da matricula em cai3_gerfinanc002.php
  if (isset($inscricao) && ! empty($inscricao)) {
    $HTTP_POST_VARS ["q02_inscr"] = $inscricao;
  }
  if (isset($matricula) && ! empty($matricula)) {
    $HTTP_POST_VARS ["j01_matric"] = $matricula;
  }

  if (! empty($HTTP_POST_VARS ["k50_notifica"])) {
    $resultnotifica = db_query("select k57_numcgm,k56_inscr,k55_matric
                                  from notificacao
                                       left join notinumcgm on notificacao.k50_notifica = k57_notifica
                                       left join notiinscr on notiinscr.k56_notifica = notificacao.k50_notifica
                                       left join notimatric on k55_notifica = notificacao.k50_notifica
                                 where notificacao.k50_notifica = " . $HTTP_POST_VARS ["k50_notifica"]);
    if (pg_numrows($resultnotifica) == 0) {
      db_msgbox("Erro(175) não foi encontrada notificação " . $HTTP_POST_VARS ["k50_notifica"]);
      echo "<script>location.href='cai3_gerfinanc001.php';</script>";
      exit();
    } elseif (pg_numrows($resultnotifica) > 0) {
      db_fieldsmemory($resultnotifica, 0);
      if ($k57_numcgm != "") {
        $HTTP_POST_VARS ["z01_numcgm"] = $k57_numcgm;
        $z01_numcgm = $k57_numcgm;
      } else if ($k56_inscr != "") {
        $HTTP_POST_VARS ["q02_inscr"] = $k56_inscr;
        $inscricao = $k56_inscr;
      } else if ($k55_matric != "") {
        $HTTP_POST_VARS ["j01_matric"] = $k55_matric;
        $matricula = $k55_matric;
      }
    }
  }

  $mensagemcorte = "";

  if (! empty($HTTP_POST_VARS ["z01_numcgm"])) {

    //---------- Tipo de Filtro usado para consulta e Cod. do mesmo --------

    $tipo_filtro = "CGM";
    $cod_filtro = $HTTP_POST_VARS ["z01_numcgm"];

    ///////// VERIFICA SE O NUMCGM POSSUI INSCRICOES
    $clsqlinscricoes = new cl_issbase();
    $sqlinscr = $clsqlinscricoes->sqlinscricoes_nome($HTTP_POST_VARS ["z01_numcgm"]);
    $resultinscr = db_query($sqlinscr);
    if (pg_numrows($resultinscr) != 0) {
      $outrasinscricoes = true;
    } else {
      $outrasinscricoes = false;
    }
    //////////////////////////////////////////////////////////////////


    $result = db_query("select z01_numcgm as k00_numcgm, z01_nome from cgm where z01_numcgm = " . $HTTP_POST_VARS ["z01_numcgm"]);
    if (pg_numrows($result) == 0) {
      db_msgbox("Numcgm inexistente");
      db_redireciona();
      exit();
    } else {
      db_fieldsmemory($result, 0);
      $resultaux = $result;
      if (! ($result = debitos_tipos_numcgm($HTTP_POST_VARS ["z01_numcgm"]))) {
        //db_msgbox('Sem débitos a pagar');
        $mensagem_semdebitos = true;
        $result = $resultaux;
        unset($resultaux);
      }
      $arg = "numcgm=" . $HTTP_POST_VARS ["z01_numcgm"];
    }
  } else if (! empty($HTTP_POST_VARS ["z01_numcgm"])) {

    //----------Tipo de Filtro usado para consulta e Cod. do mesmo-----------


    $tipo_filtro = "CGM";
    $cod_filtro = $HTTP_POST_VARS ["z01_numcgm"];

    ////////////////////////////////////////////////////////////////////


    $result = db_query("select z01_numcgm as k00_numcgm, z01_nome from cgm where z01_numcgm = " . $HTTP_POST_VARS ["db_numcgm"]);
    if (pg_numrows($result) == 0) {
      db_msgbox("Numcgm inexistente");
      db_redireciona();
      exit();
    } else {
      db_fieldsmemory($result, 0);
      $resultaux = $result;
      if (! ($result = debitos_tipos_numcgm($HTTP_POST_VARS ["db_numcgm"]))) {
        //db_msgbox('Sem débitos a pagar');
        $mensagem_semdebitos = true;
        $result = $resultaux;
        unset($resultaux);
      }
      $arg = "numcgm=" . $HTTP_POST_VARS ["db_numcgm"];
    }

  } else if (! empty($HTTP_POST_VARS ["j01_matric"])) {
    //----------Tipo de Filtro usado para consulta e Cod. do mesmo-----------


    $tipo_filtro = "MATRICULA";
    $cod_filtro = $HTTP_POST_VARS ["j01_matric"];

    ////////////////////////////////////////////////


    $result = db_query("select j01_matric,j01_numcgm as k00_numcgm
    from iptubase
    where j01_matric = " . $HTTP_POST_VARS ["j01_matric"]);
    if (pg_numrows($result) == 0) {
      db_msgbox("Matrícula inexistente");
      db_redireciona();
      exit();
    } else {
      $resultaux = $result;
      if (! ($result = debitos_tipos_matricula($HTTP_POST_VARS ["j01_matric"]))) {
        //db_msgbox('Sem débitos a pagar');
        $mensagem_semdebitos = true;
        $result = $resultaux;
        unset($resultaux);
      }
      $arg = "matric=" . $HTTP_POST_VARS ["j01_matric"];
    }

    ///////// VERIFICA SE A MATRÍCULA POSSUI OUTROS PROPRIETÁRIOS
    $resultpropri = db_query("select * from propri where j42_matric = " . $HTTP_POST_VARS ["j01_matric"]);
    if (pg_numrows($resultpropri) != 0) {
      $proprietario = true;
    } else {
      $proprietario = false;
    }

    ///////// VERIFICAD SE A MATRÍCULA POSSUI PROMITENTES
    $resultpromi = db_query("select * from promitente
    where j41_matric = " . $HTTP_POST_VARS ["j01_matric"]);
    if (pg_numrows($resultpromi) != 0) {
      $promitente = true;
    } else {
      $promitente = false;
    }
    ///////////////////////////////////////////////////////////


    //$resultprinc = db_query("select z01_cgmpri as z01_numcgm, z01_nome from proprietario_nome
    //where j01_matric = ".$HTTP_POST_VARS["j01_matric"]);
    $sqlenvol = "select rinumcgm as z01_numcgm, rvnome as z01_nome from fc_busca_envolvidos(true, {$db21_regracgmiptu}, 'M', {$HTTP_POST_VARS["j01_matric"]})";
    $resultprinc = db_query($sqlenvol);
    db_fieldsmemory($resultprinc, 0);

    if ($db21_usasisagua == true) {
      require_once(modification("agu3_conscadastro_002_classe.php"));
      $Consulta = new ConsultaAguaBase($HTTP_POST_VARS ["j01_matric"]);
      $sqlcorte = $Consulta->GetAguaCorteMatMovSQL();
      $resultcorte = db_query($sqlcorte) or die($sqlcorte);
      if (pg_numrows($resultcorte) > 0) {
        $mensagemcorte = pg_result($resultcorte, 0, "x43_descr");
      }
    }

  } else if (! empty($HTTP_POST_VARS ["q02_inscr"])) {

    //----------Tipo de Filtro usado para consulta e Cod. do mesmo-----------


    $tipo_filtro = "INSCRICAO";
    $cod_filtro = $HTTP_POST_VARS ["q02_inscr"];

    ////////////////////////////////////////////////
    $result = db_query("select q02_inscr, z01_numcgm, z01_nome from issbase inner join cgm on z01_numcgm = q02_numcgm where q02_inscr = " . $HTTP_POST_VARS ["q02_inscr"]);

    if (pg_numrows($result) == 0) {
      db_msgbox("Inscrição inexistente");
      db_redireciona();
      exit();
    } else {
      $sqlenvol = "select riinscr as q02_inscr, rinumcgm as z01_numcgm, rvnome as z01_nome from fc_busca_envolvidos(true, {$db21_regracgmiss}, 'I', {$HTTP_POST_VARS["q02_inscr"]})";
      $result = db_query($sqlenvol) or die($sqlenvol);

      db_fieldsmemory($result, 0);
      $resultaux = $result;
      if (! ($result = debitos_tipos_inscricao($HTTP_POST_VARS ["q02_inscr"]))) {
        //db_msgbox('Sem débitos a pagar');
        $mensagem_semdebitos = true;
        $result = $resultaux;
        unset($resultaux);
      }
      $arg = "inscr=" . $HTTP_POST_VARS ["q02_inscr"];
    }

  } else if (! empty($HTTP_POST_VARS ["k00_numpre"])) {

    $tipo_filtro = "NUMPRE";
    $cod_filtro = $HTTP_POST_VARS ["k00_numpre"];

    //    echo '<form name="form1" method="post">';
    echo '<table border=1>';

    $sql = "select k00_numcgm, k00_matric, k00_inscr, 'RECIBO DA CGF' as tipo
              from db_reciboweb
                   inner join arrenumcgm on arrenumcgm.k00_numpre = k99_numpre
                   left  join arrematric on arrematric.k00_numpre = k99_numpre
                   left  join arreinscr  on arreinscr.k00_numpre  = k99_numpre
             where k99_numpre_n = " . $HTTP_POST_VARS ["k00_numpre"];
    $sql .= " union ";
    $sql .= "select arrenumcgm.k00_numcgm, k00_matric, k00_inscr, 'RECIBO AVULSO' as tipo
               from recibo
                    inner join arrenumcgm on arrenumcgm.k00_numpre = recibo.k00_numpre
                    left  join arrematric on arrematric.k00_numpre = recibo.k00_numpre
                    left  join arreinscr  on arreinscr.k00_numpre  = recibo.k00_numpre
              where recibo.k00_numpre = " . $HTTP_POST_VARS ["k00_numpre"];
    $result = db_query($sql) or die($sql);
    //db_criatabela($result);exit;


    $matriz = array ();

    for($reg = 0; $reg < pg_numrows($result); $reg ++) {
      db_fieldsmemory($result, $reg);

      if (( int ) $k00_numcgm > 0) {
        $retorno = array_search("C" . $k00_numcgm, $matriz);

        if ($retorno === false) {
          $matriz [] = "C" . $k00_numcgm;
        }
      }

      if (( int ) $k00_matric > 0) {
        $retorno = array_search("M" . $k00_matric, $matriz);

        if ($retorno === false) {
          $matriz [] = "M" . $k00_matric;
        }
      }

      if (( int ) $k00_inscr > 0) {
        $retorno = array_search("I" . $k00_inscr, $matriz);

        if ($retorno === false) {
          $matriz [] = "I" . $k00_inscr;
        }
      }

    }

    // para alegrete, nos casos da emissao geral de iptu gera um único registro na db_reciboweb com parcela 0, e deveria gerar 1 registro para cada parcela do iptu, entao tivemos que modificar para utilizar a recibopaga ao inves de utilizar diretamente a db_reciboweb
    $sql = "select * from ( ";
    $sql .= "select distinct recibopaga.k00_numpre as k99_numpre, recibopaga.k00_numpar as k99_numpar, k00_receit, ";
    $sql .= "      (select arrepaga.k00_dtpaga from arrepaga where arrepaga.k00_numpre = recibopaga.k00_numpre and arrepaga.k00_numpar = recibopaga.k00_numpar limit 1) as k00_dtpaga, ";
    $sql .= "      (select disbanco.dtpago from disbanco inner join arreidret on disbanco.idret = disbanco.idret where arreidret.k00_numpre = recibopaga.k00_numpre and arreidret.k00_numpar = recibopaga.k00_numpar limit 1) as dtpago,";
    $sql .= "coalesce((select v08_parcel from termo inner join termoreparc on v08_parcelorigem = v07_parcel where termo.v07_numpre = recibopaga.k00_numpre and termo.v07_situacao = 3 limit 1),0) as v07_parcel, ";
    $sql .= "(select arrecad.k00_numpre from arrecad where arrecad.k00_numpre = recibopaga.k00_numpre and arrecad.k00_numpar = recibopaga.k00_numpar and arrecad.k00_receit = recibopaga.k00_receit limit 1) as k00_numpre_arrecad, ";
    $sql .= "(select arrecant.k00_numpre from arrecant where arrecant.k00_numpre = recibopaga.k00_numpre and arrecant.k00_numpar = recibopaga.k00_numpar and arrecant.k00_receit = recibopaga.k00_receit limit 1) as k00_numpre_arrecant, ";
    $sql .= "(select divold.k10_coddiv from divold where divold.k10_numpre = recibopaga.k00_numpre and divold.k10_numpar = recibopaga.k00_numpar and divold.k10_receita = recibopaga.k00_receit limit 1) as k10_coddiv, ";
    $sql .= "(select distinct parcel from divida inner join termodiv on v01_coddiv = coddiv where divida.v01_numpre = recibopaga.k00_numpre limit 1) as v07_parcel_parcelado,";
    $sql .= "'RECIBO DA CGF' as tipo
    from db_reciboweb
    inner join recibopaga on db_reciboweb.k99_numpre_n = recibopaga.k00_numnov
    inner join arrenumcgm on arrenumcgm.k00_numpre = k99_numpre
    where k00_hist not in (400,401,918) and k99_numpre_n = " . $HTTP_POST_VARS ["k00_numpre"];

    $sql .= " union ";
    $sql .= "select distinct recibo.k00_numpre as k99_numpre, recibo.k00_numpar as k99_numpar, recibo.k00_receit as k00_receit, ";
    $sql .= "(select arrepaga.k00_dtpaga from arrepaga where arrepaga.k00_numpre = recibo.k00_numpre and arrepaga.k00_numpar = recibo.k00_numpar limit 1) as k00_dtpaga, ";
    $sql .= "(select disbanco.dtpago from disbanco inner join arreidret on disbanco.idret = disbanco.idret where arreidret.k00_numpre = recibo.k00_numpre and arreidret.k00_numpar = recibo.k00_numpar limit 1) as dtpago,";
    $sql .= "0 as v07_parcel, ";
    $sql .= "0 as k00_numpre_arrecad, ";
    $sql .= "0 as k00_numpre_arrecant, ";
    $sql .= "0 as k10_coddiv, ";
    $sql .= "0 as v07_parcel_parcelado, ";
    $sql .= "'RECIBO AVULSO' as tipo ";
    $sql .= " from recibo
    inner join arrenumcgm on arrenumcgm.k00_numpre = recibo.k00_numpre
    where recibo.k00_numpre = " . $HTTP_POST_VARS ["k00_numpre"];

    $sql .= " union ";
    $sql .= "select distinct divida.v01_numpre as k99_numpre, divida.v01_numpar as k00_numpar, proced.v03_receit as k00_receit, ";
    $sql .= "(select arrepaga.k00_dtpaga from arrepaga where arrepaga.k00_numpre = divida.v01_numpre and arrepaga.k00_numpar = divida.v01_numpar limit 1) as k00_dtpaga, ";
    $sql .= "(select disbanco.dtpago from disbanco inner join arreidret on disbanco.idret = disbanco.idret where arreidret.k00_numpre = divida.v01_numpre and arreidret.k00_numpar = divida.v01_numpar limit 1) as dtpago,";
    $sql .= "0 as v07_parcel, ";
    $sql .= "0 as k00_numpre_arrecad, ";
    $sql .= "0 as k00_numpre_arrecant, ";
    $sql .= "0 as k10_coddiv, ";
    $sql .= "(select distinct parcel from termodiv inner join termo on termo.v07_parcel = termodiv.parcel and v07_situacao = 1 where coddiv = divida.v01_coddiv limit 1) as v07_parcel_parcelado,";
    $sql .= "'DIVIDA ATIVA' as tipo ";
    $sql .= " from divida
                   inner join arrenumcgm on arrenumcgm.k00_numpre = divida.v01_numpre
                   inner join proced     on proced.v03_codigo     = divida.v01_proced
             where divida.v01_numpre = ".$HTTP_POST_VARS ["k00_numpre"].") as x
             where not exists (select k00_numpre from arrecad where k00_numpre = ".$HTTP_POST_VARS ["k00_numpre"]." limit 1 )
               and not exists (select k00_numpre from arrecant where k00_numpre = ".$HTTP_POST_VARS ["k00_numpre"]." limit 1 )";

    $result = db_query($sql) or die($sql);

    if (pg_numrows($result) > 0) {

      echo "<tr>
            <b>
            NUMPRE ORIGINAL: " . $HTTP_POST_VARS ["k00_numpre"] . " - RECIBO DOS SEGUINTES NUMPRES:
            </b>
            </tr>";

      echo "<tr>";
      echo "<td><b>NUMPRE</b></td>";
      echo "<td><b>NUMPAR</b></td>";
      echo "<td><b>RECEITA</b></td>";
      echo "<td><b>SITUAÇÃO</b></td>";
      echo "<td><b>TIPO</b></td>";
      echo "<td><b>ORIGEM</b></td>";
      echo "</tr>";

      for($registroavulso = 0; $registroavulso < pg_numrows($result); $registroavulso ++) {
        db_fieldsmemory($result, $registroavulso);

        $sql_origem = "select distinct k00_numcgm, k00_matric, k00_inscr
                         from arrenumcgm
                              inner join arreinstit on arreinstit.k00_numpre = arrenumcgm.k00_numpre
                                                   and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                              left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
                              left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
                        where arrenumcgm.k00_numpre = " . pg_result($result, $registroavulso, 0);
        $result_origem = db_query($sql_origem) or die($sql_origem);

        $matriz_numpre = array ();
        $expr_orig = "";

        for($reg = 0; $reg < pg_numrows($result_origem); $reg ++) {
          db_fieldsmemory($result_origem, $reg);

          if (( int ) $k00_numcgm > 0) {
            $retorno = array_search("C" . $k00_numcgm, $matriz_numpre);

            if ($retorno === false) {
              $matriz_numpre [] = "C" . $k00_numcgm;
            }
          }

          if (( int ) $k00_matric > 0) {
            $retorno = array_search("M" . $k00_matric, $matriz_numpre);

            if ($retorno === false) {
              $matriz_numpre [] = "M" . $k00_matric;
            }
          }

          if (( int ) $k00_inscr > 0) {
            $retorno = array_search("I" . $k00_inscr, $matriz_numpre);

            if ($retorno === false) {
              $matriz_numpre [] = "I" . $k00_inscr;
            }
          }

          for($reg2 = 0; $reg2 < sizeof($matriz_numpre); $reg2 ++) {
            if (substr($matriz_numpre [$reg2], 0, 1) == "C") {
              $expr_orig .= "CGM";
            } elseif (substr($matriz_numpre [$reg2], 0, 1) == "M") {
              $expr_orig .= "MATRÍCULA";
            } elseif (substr($matriz_numpre [$reg2], 0, 1) == "I") {
              $expr_orig .= "INSCRIÇÃO";
            }
            $expr_orig .= ": " . substr($matriz_numpre [$reg2], 1, strlen($matriz_numpre [$reg2])) . " - ";
          }

        }

        $k00_dtpaga = db_formatar($k00_dtpaga, 'd');

        echo "<tr>";
        echo "<td>" . pg_result($result, $registroavulso, "k99_numpre") . "</td>";
        echo "<td>" . pg_result($result, $registroavulso, "k99_numpar") . "</td>";
        echo "<td>" . pg_result($result, $registroavulso, "k00_receit") . "</td>";

        $situacao_numpre = "";

        if (pg_result($result, $registroavulso, "k00_dtpaga") != "") {
          $situacao_numpre = "PAGO EM $k00_dtpaga";
        } elseif (pg_result($result, $registroavulso, "k00_numpre_arrecad") > 0) {
          $situacao_numpre = "ABERTO";
        } elseif (pg_result($result, $registroavulso, "k00_numpre_arrecant") > 0) {
          $situacao_numpre = "CANCELADO";
        } elseif (pg_result($result, $registroavulso, "k10_coddiv") > 0) {
          $sqlcoddiv = "select v01_numpre, v01_numpar from divida where v01_coddiv = $k10_coddiv";
          $resultcoddiv = db_query($sqlcoddiv) or die($sqlcoddiv);
          if (pg_numrows($resultcoddiv) > 0) {
            db_fieldsmemory($resultcoddiv,0);
          }
          $situacao_numpre = "IMPORTADO - CODDIV: $k10_coddiv - NUMPRE NOVO: $v01_numpre - PARCELA: $v01_numpar";
        } elseif (pg_result($result, $registroavulso, "v07_parcel") > 0) {
          $situacao_numpre = "REPARCELAMENTO $v07_parcel";
        } elseif (pg_result($result, $registroavulso, "v07_parcel_parcelado") != 0) {
          $situacao_numpre = "PARCELAMENTO $v07_parcel_parcelado";
        } else {
          $situacao_numpre = "ABERTO";
        }
        echo "<td>" . $situacao_numpre . "</td>";
        echo "<td>" . pg_result($result, $registroavulso, "tipo") . "</td>";
        echo "<td>" . $expr_orig . "</td>";
        echo "</tr>";

      }

      for($reg = 0; $reg < sizeof($matriz); $reg ++) {

        if (substr($matriz [$reg], 0, 1) == "C") {
          $expr_orig = "CGM";
        } elseif (substr($matriz [$reg], 0, 1) == "M") {
          $expr_orig = "MATRÍCULA";
        } elseif (substr($matriz [$reg], 0, 1) == "I") {
          $expr_orig = "INSCRIÇÃO";
        }
      }

      echo "<tr>";
      $uri = $GLOBALS ["PHP_SELF"];
      echo '<td><a href=' . $uri . '>Voltar</a></td>';
      echo "</tr>";
      echo "</table>";
      exit;

    }

    $sql_result = "select k00_numcgm as z01_numcgm, z01_nome
                     from arrenumcgm
                          inner join arreinstit on arreinstit.k00_numpre = arrenumcgm.k00_numpre
                                               and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                          inner join cgm on z01_numcgm = k00_numcgm
                    where arrenumcgm.k00_numpre = " . $HTTP_POST_VARS ["k00_numpre"];
    $sql_result .= " union ";
    $sql_result .= "select recibopaga.k00_numcgm as z01_numcgm, z01_nome
                      from recibopaga
                           inner join arreinstit on arreinstit.k00_numpre = recibopaga.k00_numpre
                                                and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                           inner join cgm on z01_numcgm = recibopaga.k00_numcgm
                     where recibopaga.k00_numnov = " . $HTTP_POST_VARS ["k00_numpre"];
    $result = db_query($sql_result) or die($sql_result);
    if (pg_numrows($result) > 0) {
      db_fieldsmemory($result, 0);
    }

    $result = debitos_tipos_numpre($HTTP_POST_VARS ["k00_numpre"]);

    if ($result == false) {
      $sql = "select case
                 when arrecant.k00_numcgm is not null then arrecant.k00_numcgm
                 when arrepaga.k00_numcgm is not null then arrepaga.k00_numcgm
               end as k00_numcgm
            from arreinstit
               left join arrecant  on arrecant.k00_numpre = arreinstit.k00_numpre
                 left join arrepaga  on arrepaga.k00_numpre = arreinstit.k00_numpre
           where arreinstit.k00_numpre = " . $HTTP_POST_VARS ["k00_numpre"] . "
             and arreinstit.k00_instit = " . db_getsession('DB_instit') . " limit 1 ";

      $result = db_query($sql);

      if (pg_numrows($result) > 0) {
        $com_debitos = false;
      } else {
        $result = false;
      }
    } else {
      $resultaux = 1;
    }

    if ($result == false) {

      $mensagem_semdebitos = true;
      db_msgbox('Sem débitos a pagar ou não localizado!');
      db_redireciona();
      exit;
    }

    $arg = "numpre=" . $HTTP_POST_VARS ["k00_numpre"];

    echo ' </table>';
    echo '</form>';

  } else if (! empty($HTTP_POST_VARS ["v07_parcel"])) {
    $tipo_filtro = "PARCEL";
    $cod_filtro = $HTTP_POST_VARS ["v07_parcel"];

    $sqlNumpreParcelamento = " select v07_numpre ";
    $sqlNumpreParcelamento .= "   from termo      ";
    $sqlNumpreParcelamento .= "  where v07_parcel = " . $HTTP_POST_VARS ["v07_parcel"] . "  ";
    $sqlNumpreParcelamento .= "    and v07_instit = " . db_getsession('DB_instit') . "  ";
    $sqlNumpreParcelamento .= "    and (   exists ( select arrecant.k00_numpre  ";
    $sqlNumpreParcelamento .= "                       from arrecant  ";
    $sqlNumpreParcelamento .= "                      where arrecant.k00_numpre = termo.v07_numpre limit 1 )  ";
    $sqlNumpreParcelamento .= "         or exists ( select arrecad.k00_numpre  ";
    $sqlNumpreParcelamento .= "                       from arrecad  ";
    $sqlNumpreParcelamento .= "                      where arrecad.k00_numpre = termo.v07_numpre limit 1 )  ";
    $sqlNumpreParcelamento .= "         or exists ( select arreold.k00_numpre  ";
    $sqlNumpreParcelamento .= "                       from arreold  ";
    $sqlNumpreParcelamento .= "                      where arreold.k00_numpre = termo.v07_numpre limit 1 ) )";

    $Rec = db_query($sqlNumpreParcelamento) or die($sqlNumpreParcelamento);
    if (pg_numrows($Rec) == 0) {
      db_erro("Erro(175) não foi encontrado numpre pelo codigo do parcelamento " . $HTTP_POST_VARS ["v07_parcel"]);
    }

    db_fieldsmemory($Rec, 0);

    if (! ($result = debitos_tipos_numpre(pg_result($Rec, 0, 0)))) {
      $mensagem_semdebitos = true;
    }

    $k00_numpre = pg_result($Rec, 0, "v07_numpre");
    $resultaux = 1;
    $arg = "Parcelamento=" . $HTTP_POST_VARS ["v07_parcel"];
    $Parcelamento = $HTTP_POST_VARS ["v07_parcel"];
    pg_freeresult($Rec);

    $sqlcgm = "select k00_numcgm as z01_numcgm, z01_nome
                 from arrenumcgm
                      left join arreinstit on arreinstit.k00_numpre = arrenumcgm.k00_numpre
                                           and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                      inner join cgm        on z01_numcgm = k00_numcgm
                where arrenumcgm.k00_numpre = $k00_numpre limit 1";
    $resultcgm = db_query($sqlcgm);
    db_fieldsmemory($resultcgm, 0);
  }
  $dados = db_query("select z01_ender,z01_munic,z01_uf,z01_cgccpf,z01_ident,z01_numero,z01_compl
                      from cgm where z01_numcgm = $z01_numcgm");
  db_fieldsmemory($dados, 0);

  ////////////////    VERIFICA SE O NUMCGM POSSUI MATRÍCULA CADASTRADAS
  $clsqlamatriculas = new cl_iptubase();
  $sqlmatric = $clsqlamatriculas->sqlmatriculas_nome($z01_numcgm);
  $resultmatric = db_query($sqlmatric);
  if (pg_numrows($resultmatric) != 0) {
    $outrasmatriculas = true;
  } else {
    $outrasmatriculas = false;
  }

  ////////////////      VERIFICA SE O NUMCGM POSSUI SOCIOS
  $clsqlinscricoes = new cl_issbase();
  $sqlsocios = $clsqlinscricoes->sqlinscricoes_socios(0, $z01_numcgm, "cgmsocio.z01_nome");
  $resultsocios = db_query($sqlsocios);
  if (pg_numrows($resultsocios) != 0) {
    $socios = true;
  } else {
    $socios = false;
  }

  ////////////////  VERIFICA SE O NUMCGM POSSUI RETENÇÃO COMO PRESTADOR ///////

  $sqlprest  = "select z01_numcgm																	  ";
  $sqlprest .= "  from cgm 																				  ";
  $sqlprest .= "       inner join issplanit on z01_cgccpf = q21_cnpj";
  $sqlprest .= " where z01_numcgm = $z01_numcgm                     ";
  $sqlprest .= "   and q21_status = 1                               ";

  $resultprest = db_query($sqlprest);
  $linhaspret = pg_num_rows($resultprest);
  if ($linhaspret > 0) {
    $prestador = true;
  } else {
    $prestador = false;
  }
  ///////////////////////////////////////////////////////////////////


  /*==================== VERIFICA SE EXISTE DEBITOS PRESCRITOS =========================*/
  $innerjoin = "";
  $where = " where k30_anulado is false ";
  if (isset($HTTP_POST_VARS ["k00_numpre"]) && $HTTP_POST_VARS ["k00_numpre"] != "") {

    $tipo_filtro = "NUMPRE";
    $cod_filtro  = $HTTP_POST_VARS ["k00_numpre"];
    $innerjoin   = " inner join arreinstit on arreinstit.k00_numpre = k30_numpre ";
    $innerjoin  .= "                      and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $where       = "and k30_numpre = " . $HTTP_POST_VARS ["k00_numpre"];
  } else if (isset($HTTP_POST_VARS ["j01_matric"]) && $HTTP_POST_VARS ["j01_matric"] != "") {

    $tipo_filtro = "MATRICULA";
    $cod_filtro  = $HTTP_POST_VARS ["j01_matric"];
    $innerjoin   = " inner join arrematric on k30_numpre = arrematric.k00_numpre";
    $innerjoin  .= " inner join arreinstit on arreinstit.k00_numpre = arrematric.k00_numpre ";
    $innerjoin  .= "                      and arreinstit.k00_instit = " . db_getsession('DB_instit');

    $where = "and k00_matric = " . $HTTP_POST_VARS ["j01_matric"];
  } else if (isset($HTTP_POST_VARS ["q02_inscr"]) && $HTTP_POST_VARS ["q02_inscr"] != "") {

    $tipo_filtro = "INSCRICAO";
    $cod_filtro  = $HTTP_POST_VARS ["q02_inscr"];
    $innerjoin   = " inner join arreinscr on k30_numpre = arreinscr.k00_numpre";
    $innerjoin  .= " inner join arreinstit on arreinstit.k00_numpre = arreinscr.k00_numpre ";
    $innerjoin  .= "                      and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $where = "and k00_inscr = " . $HTTP_POST_VARS ["q02_inscr"];
  } else if (isset($HTTP_POST_VARS ["v07_parcel"]) && $HTTP_POST_VARS ["v07_parcel"] != "") {

    $tipo_filtro = "PARCEL";
    $cod_filtro  = $HTTP_POST_VARS ["v07_parcel"];
    $innerjoin   = " inner join termo on v07_numpre = k30_numpre ";
    $where       = " and v07_parcel = ".$HTTP_POST_VARS ["v07_parcel"];

  } else {

    $tipo_filtro = "CGM";
    $cod_filtro  = $HTTP_POST_VARS ["z01_numcgm"];
    $innerjoin   = " inner join arrenumcgm on k30_numpre = arrenumcgm.k00_numpre";
    $innerjoin  .= " inner join arreinstit on arreinstit.k00_numpre = arrenumcgm.k00_numpre ";
    $innerjoin  .= "                      and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $where = "and k00_numcgm = " . $z01_numcgm;
  }
  $clarreprescr = new cl_arreprescr();
  $sqlprescr  = " select k30_numpre from arreprescr";
  $sqlprescr .= "        $innerjoin ";
  $sqlprescr .= $where." and  k30_anulado is false ";

  $rsPrescr = $clarreprescr->sql_record($sqlprescr);
  if ($clarreprescr->numrows > 0) {
    $temprescr = true;
  } else {
    $temprescr = false;
  }
  /*====================================================================================*/
  ////////////////////////////// VERIFICA SE TEM DEBITO JUSTIFICADO //////////////////////////////////
  if (isset($HTTP_POST_VARS ["k00_numpre"]) && $HTTP_POST_VARS ["k00_numpre"] != "") {

    $tipo_filtro     = "NUMPRE";
    $cod_filtro      = $HTTP_POST_VARS ["k00_numpre"];
    $sqlarrejustreg  = "select *																																							 ";
    $sqlarrejustreg .= "  from arrejustreg																																	   ";
    $sqlarrejustreg .= "         inner join arreinstit on arreinstit.k00_numpre = arrejustreg.k28_numpre       ";
    $sqlarrejustreg .= "                              and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $sqlarrejustreg .= "where k28_numpre = " . $HTTP_POST_VARS ["k00_numpre"];
  } else if (isset($HTTP_POST_VARS ["j01_matric"]) && $HTTP_POST_VARS ["j01_matric"] != "") {

    $tipo_filtro 		 = "MATRICULA";
    $cod_filtro 		 = $HTTP_POST_VARS ["j01_matric"];
    $sqlarrejustreg  = "select *                																															 ";
    $sqlarrejustreg .= "  from arrejustreg																																	   ";
    $sqlarrejustreg .= "       inner join arrematric on k28_numpre = arrematric.k00_numpre    							   ";
    $sqlarrejustreg .= "       inner join arreinstit on arreinstit.k00_numpre = arrematric.k00_numpre          ";
    $sqlarrejustreg .= "                              and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $sqlarrejustreg .= "where k00_matric = " . $HTTP_POST_VARS ["j01_matric"];
  } else if (isset($HTTP_POST_VARS ["q02_inscr"]) && $HTTP_POST_VARS ["q02_inscr"] != "") {

    $tipo_filtro     = "INSCRICAO";
    $cod_filtro      = $HTTP_POST_VARS ["q02_inscr"];
    $sqlarrejustreg  = "select *																																					 ";
    $sqlarrejustreg .= "  from arrejustreg																																 ";
    $sqlarrejustreg .= "       inner join arreinscr on k28_numpre = arreinscr.k00_numpre									 ";
    $sqlarrejustreg .= "       inner join arreinstit on arreinstit.k00_numpre = arreinscr.k00_numpre			 ";
    $sqlarrejustreg .= "                          and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $sqlarrejustreg .= "where k00_inscr = " . $HTTP_POST_VARS ["q02_inscr"];
  } else if (isset($HTTP_POST_VARS ["v07_parcel"]) && $HTTP_POST_VARS ["v07_parcel"] != "") {

    $tipo_filtro     = "PARCEL";
    $cod_filtro      = $HTTP_POST_VARS ["v07_parcel"];
    $sqlarrejustreg  = "select * 																																						 ";
    $sqlarrejustreg .= "  from arrejustreg			                                                             ";
    $sqlarrejustreg .= "       inner join termo      on termo.v07_numpre      = arrejustreg.k28_numpre       ";
    $sqlarrejustreg .= "       inner join arreinstit on arreinstit.k00_numpre = termo.v07_numpre             ";
    $sqlarrejustreg .= "                            and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $sqlarrejustreg .= "where v07_parcel = " . $HTTP_POST_VARS ["v07_parcel"];

  } else {

    $tipo_filtro     = "CGM";
    $cod_filtro      = $HTTP_POST_VARS ["z01_numcgm"];
    $sqlarrejustreg  = "select *																																						 ";
    $sqlarrejustreg .= "from arrejustreg 																																		 ";
    $sqlarrejustreg .= "     inner join arrenumcgm on k28_numpre            = arrenumcgm.k00_numpre          ";
    $sqlarrejustreg .= "     inner join arreinstit on arreinstit.k00_numpre = arrenumcgm.k00_numpre          ";
    $sqlarrejustreg .= "                          and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $sqlarrejustreg .= "where k00_numcgm = " . $z01_numcgm;
  }

  $resultarrejustreg = db_query($sqlarrejustreg);
  $linhasarrejustreg = pg_num_rows($resultarrejustreg);
  if ($linhasarrejustreg > 0) {
    $justificado = true;
  } else {
    $justificado = false;
  }
  //======================================================================================
  ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%">
            <table border="0">
              <tr>
                <td nowrap title="Clique Aqui para ver os dados cadastrais."
                  class="tabcols"><strong style="color:blue"\><a href=''
                  onclick='js_mostracgm();return false;'>CGM:</a></strong></td>
                <td class="tabcols" nowrap
                  title="Clique Aqui para ver os dados cadastrais."><input
                  class="btcols" type="text" name="z01_numcgm"
                  value="<?=@$z01_numcgm?>" size="5" readonly>
  &nbsp;&nbsp;<?php

    if (!DBString::isCNPJ($z01_cgccpf) && !DBString::isCPF($z01_cgccpf)) {
      echo "<span class=\"bold\" style=\"color: red\">CGM Desatualizado. CPF/CNPJ invalido.</span>";
    }

  ?>&nbsp;&nbsp;
  <?php

  $sqlloteador  = "  select *                                                                       ";
  $sqlloteador .= "    from loteam                                                                  ";
  $sqlloteador .= "         inner join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam      ";
  $sqlloteador .= "   where j120_cgm = {$z01_numcgm}                                                ";

  $resultloteador = db_query($sqlloteador) or die($sqlloteador);
  if (pg_numrows($resultloteador) > 0) {
    echo "<b> <font color=red> - LOTEAMENTO - </b>";
  }

  parse_str($arg);
  if (isset($matric)) {

    $sTipoChavePesquisa = "matricula";
    $Label              = "<a href='' onclick='js_mostrabic_matricula();return false;'>Matrícula:</a>";
    $codOrigem          = $matric;
  } else if (isset($inscr)) {

    $sTipoChavePesquisa = "inscricao";
    $Label              = "<a href='' onclick='js_mostrabic_inscricao();return false;'>Inscrição:</a>";
    $codOrigem          = $inscr;
  } else if (isset($numpre)) {

    $sTipoChavePesquisa = "numpre";
    $Label              = "Numpre:";
    $codOrigem          = $numpre;
  } else if (isset($Parcelamento)) {

    $sTipoChavePesquisa = "parcelamento";
    $Label 						  = "Parcelamento:";
    $codOrigem          = $Parcelamento;
  }
  if (isset($Label)) {

    echo "<strong style=\"color:blue\">$Label</strong>
    <input style=\"border: 1px solid blue;font-weight: bold;background-color:#80E6FF\" tipoPesquisa=\"{$sTipoChavePesquisa}\" class=\"btcols\" type=\"text\" id=\"botaoChavePesquisa\" name=\"Label\" value=\"" . @$codOrigem . "\" size=\"10\" readonly>\n";
  }

  if ($mensagemcorte != "") {
    echo "<b> <font color=red><blink> - $mensagemcorte</b>";
  }

  if (isset($HTTP_POST_VARS ["j01_matric"]) && ! empty($HTTP_POST_VARS ["j01_matric"])) {

    if ( !$db21_usasisagua ) {

      $sqlbaixada = "select j01_baixa from iptubase where j01_matric = " . $HTTP_POST_VARS ["j01_matric"] . " and j01_baixa is not null";
      $resultbaixada = db_query($sqlbaixada) or die($sqlbaixada);
    } else {

      $sqlbaixada = "select x08_data as j01_baixa from aguabasebaixa where x08_matric = " . $HTTP_POST_VARS ["j01_matric"] ;
      $resultbaixada = db_query($sqlbaixada) or die($sqlbaixada);
    }
    if (pg_numrows($resultbaixada) > 0) {
      echo "<b><font color=red><blink> - MATRÍCULA BAIXADA EM " . db_formatar(pg_result($resultbaixada, 0, "j01_baixa"), "d") . "</b>";
    }
  }

  if (isset($HTTP_POST_VARS ["q02_inscr"]) && ! empty($HTTP_POST_VARS ["q02_inscr"])) {

    $sqlbaixada = "select q02_dtbaix from issbase where q02_inscr = " . $HTTP_POST_VARS ["q02_inscr"] . " and q02_dtbaix is not null";
    $resultbaixada = db_query($sqlbaixada) or die($sqlbaixada);
    if (pg_numrows($resultbaixada) > 0) {
      echo "<b> <font color=red><blink> - INSCRIÇÃO BAIXADA EM " . db_formatar(pg_result($resultbaixada, 0, "q02_dtbaix"), "d") . "</b>";
    }
  }

  ?>
  </td>
              </tr>
              <tr>
                <td nowrap class="tabcols"><strong>Nome:</strong></td>
                <td nowrap><input class="btcols" type="text" name="z01_nome"
                  value="<?=@$z01_nome?>" size="60" readonly> &nbsp;</td>
              </tr>
              <tr>
                <td nowrap class="tabcols"><strong>Endereço:</strong></td>
                <td nowrap><input class="btcols" type="text" name="z01_ender"
                  value="<?=@$z01_ender . ($z01_numero != "" ? ", " : "") . $z01_numero . ($z01_compl != "" ? "/" : "") . $z01_compl?>"
                  size="60" readonly></td>
              </tr>
              <tr>
                <td nowrap class="tabcols"><strong>Município:</strong></td>
                <td><input class="btcols" type="text" name="z01_munic"
                  value="<?=@$z01_munic?>" size="20" readonly> <strong
                  class="tabcols">UF:</strong> <input class="btcols" type="text"
                  name="z01_uf" value="<?=@$z01_uf?>" size="2" maxlength="2"
                  readonly=""> &nbsp;</td>
              </tr>
              <form name="formatu" action="cai3_gerfinanc001.php" method="post">


              <tr>
                <td height="21" colspan="2" nowrap class="tabcols">
  <?
  if (isset($HTTP_POST_VARS ["j01_matric"]) && ! empty($HTTP_POST_VARS ["j01_matric"])) {

    echo "<input type=\"hidden\" name=\"j01_matric\"  value=\"" . $HTTP_POST_VARS ["j01_matric"] . "\">";
    $innerJoinOutros = " arrematric on arrematric.k00_numpre = arreinstit.k00_numpre ";
    $whereOutras = " arrematric.k00_matric = " . $HTTP_POST_VARS ["j01_matric"];
  }
  if (isset($HTTP_POST_VARS ["q02_inscr"]) && ! empty($HTTP_POST_VARS ["q02_inscr"])) {

    echo "<input type=\"hidden\" name=\"q02_inscr\"  value=\"" . $HTTP_POST_VARS ["q02_inscr"] . "\">";
    $innerJoinOutros = "arreinscr on arreinscr.k00_numpre = arreinstit.k00_numpre ";
    $whereOutras = "arreinscr.k00_inscr = " . $HTTP_POST_VARS ["q02_inscr"];
  }
  if (isset($HTTP_POST_VARS ["z01_numcgm"]) && ! empty($HTTP_POST_VARS ["z01_numcgm"])) {

    echo "<input type=\"hidden\" name=\"z01_numcgm\"  value=\"" . $HTTP_POST_VARS ["z01_numcgm"] . "\">";
    $innerJoinOutros = "arrenumcgm on arrenumcgm.k00_numpre = arreinstit.k00_numpre ";
    $whereOutras = "arrenumcgm.k00_numcgm = " . $HTTP_POST_VARS ["z01_numcgm"];
  }
  if (isset($HTTP_POST_VARS ["v07_parcel"]) && ! empty($HTTP_POST_VARS ["v07_parcel"])) {

    echo "<input type=\"hidden\" name=\"v07_parcel\"  value=\"" . $HTTP_POST_VARS ["v07_parcel"] . "\">";
    $innerJoinOutros = "termo on termo.v07_numpre = arreinstit.k00_numpre ";
    $whereOutras = "termo.v07_parcel = " . $HTTP_POST_VARS ["v07_parcel"];
  }
  if (isset($HTTP_POST_VARS ["k00_numpre"]) && ! empty($HTTP_POST_VARS ["k00_numpre"])) {

    echo "<input type=\"hidden\" name=\"k00_numpre\"  value=\"" . $HTTP_POST_VARS ["k00_numpre"] . "\">";
    $whereOutras = "arreinstit.k00_numpre = " . $HTTP_POST_VARS ["k00_numpre"];
    $innerJoinOutros = "arrenumcgm on arrenumcgm.k00_numpre = arreinstit.k00_numpre ";
  }
  ?>

  &nbsp;
  <input name="retornar" type="button" id="retornar"
                  value="Nova Pesquisa" title="Inicio da Consulta"
                  onclick="location.href='cai3_gerfinanc001.php'"> &nbsp;&nbsp; <input
                  name="pesquisar" type="submit" id="pesquisar"
                  title="Atualiza a Consulta" value="Atualizar"> &nbsp;&nbsp; <input
                  name="voltar" type="button" id="voltar" value="<<" title="
                  Retorna" onclick="debitos.history.back()"> &nbsp;&nbsp; <input
                  name="avanca" type="button" id="avanca" value=">>"
                  title="Avança" onclick="debitos.history.forward()">


  <?

  //este select é pra ver se o cgm esta no ruas e tb tem CPF/CNPJ para deixar preenchido o responsavel pelo parcelamento
  $re_cgm = db_query("select * from cgm c left join db_cgmruas r on r.z01_numcgm = c.z01_numcgm where c.z01_numcgm = $z01_numcgm and trim(c.z01_cgccpf) <> ''");
  if (pg_numrows($re_cgm) > 0) {
    $id_resp_parc = $z01_numcgm;
    $resp_parc    = $z01_nome;
  }
  ?>

  <input name="id_resp_parc" id="id_resp_parc" type="hidden"
                  value="<?=@$id_resp_parc?>"> <!-- este dois inputs guardam o responsável pelo parcelamento para q qdo ele escolha outra divida para parcelar ele traga automaticamento o ultimo nome que foi preenchido -->
                <input name="resp_parc" id="resp_parc" type="hidden"
                  value="<?=@$resp_parc?>"></td>
                </form>

            </table>
            </td>
            <td width="47%" valign="top">
  <?

  $_iInstit = db_getsession('DB_instit');
  $_dData = date('Y-m-d', db_getsession('DB_datausu'));

  $sqlDebitosOutrasinstit = "select a.k00_instit, ";
  $sqlDebitosOutrasinstit .= "       (select date '{$_dData}' - coalesce(min(arrecad.k00_dtvenc), date '{$_dData}') ";
  $sqlDebitosOutrasinstit .= "          from arreinstit ";
  $sqlDebitosOutrasinstit .= "               inner join {$innerJoinOutros} ";
  $sqlDebitosOutrasinstit .= "               inner join arrecad     on arrecad.k00_numpre    = arreinstit.k00_numpre ";
  $sqlDebitosOutrasinstit .= "         where {$whereOutras} ";
  $sqlDebitosOutrasinstit .= "           and arreinstit.k00_instit = a.k00_instit ";
  $sqlDebitosOutrasinstit .= "           and arrecad.k00_dtvenc    < '{$_dData}') as k00_diasvenc ";

  $sqlDebitosOutrasinstit .= "from (select distinct ";
  $sqlDebitosOutrasinstit .= "             arreinstit.k00_instit ";
  $sqlDebitosOutrasinstit .= "        from arreinstit ";
  $sqlDebitosOutrasinstit .= "             inner join {$innerJoinOutros} ";
  $sqlDebitosOutrasinstit .= "       where {$whereOutras} ";
  $sqlDebitosOutrasinstit .= "         and arreinstit.k00_instit <> {$_iInstit} ";
  $sqlDebitosOutrasinstit .= "         and exists (select arrecad.k00_numpre ";
  $sqlDebitosOutrasinstit .= "                       from arrecad ";
  $sqlDebitosOutrasinstit .= "                      where arrecad.k00_numpre = arreinstit.k00_numpre)) as a; ";

  $rsDebitosOutrasInstit = db_query($sqlDebitosOutrasinstit);
  if (pg_numrows($rsDebitosOutrasInstit) > 0) {
    db_fieldsmemory($rsDebitosOutrasInstit, 0);

    if ($k00_diasvenc > 0) {
      $k00_mensagem = "EXISTEM DÉBITOS VENCIDOS HÁ {$k00_diasvenc} DIA(S) EM OUTRAS INSTITUIÇÕES";
    } else {
      $k00_mensagem = "EXISTEM DÉBITOS NÃO VENCIDOS EM OUTRAS INSTITUIÇÕES";
    }

    echo " <table> <tr> <td class=\"links2\" colspan='3' align='center'> {$k00_mensagem} </td> </tr> </table>";

    // Muda posicionamento da DIV
    echo "<script>document.getElementById('processando').style.top = 134;</script>";
  } else {
    echo "<script>document.getElementById('processando').style.top = 113;</script>";
  }



if ($result) {
  $numrows = pg_numrows($result);
} else {
  $numrows = 0;
}

echo "<script>
      function js_envia(chave){
        debitos.location.href=chave+document.form1.k00_dtoper_ano.value+'-'+document.form1.k00_dtoper_mes.value+'-'+document.form1.k00_dtoper_dia.value;
      }
      </script>";

echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">\n<tr class=\"links\">\n<td valign=\"top\" style=\"font-size:11px\"> <form name=\"form2\" method=\"post\" target=\"debitos\">\n";

  $iPerfilProcuradoria = 1;
  if ($db21_codcli == 19985) {

    $sPerfilProcuradoria = "SELECT db_permherda.id_usuario from configuracoes.db_permherda where db_permherda.id_usuario = " . db_getsession('DB_id_usuario') . " and db_permherda.id_perfil in (334, 338) union select db_usuarios.id_usuario from configuracoes.db_usuarios where db_usuarios.id_usuario = " . db_getsession('DB_id_usuario') . " and administrador = 1";
    $rsPerfilProcuradoria = db_query($sPerfilProcuradoria) or die($sPerfilProcuradoria);
    if ( pg_numrows($rsPerfilProcuradoria) > 0 ) {
      $iPerfilProcuradoria = 1;
    } else {
      $iPerfilProcuradoria = 0;
    }
  }

if (isset($resultaux)) {

  for($i = 0; $i < $numrows; $i ++) {

    $sql_k03_tipo = "select k03_tipo
                       from arretipo
                      where k00_instit = " . db_getsession('DB_instit') . "
                        and k00_tipo = " . pg_result($result, $i, "k00_tipo");
    $result_k03_tipo = db_query($sql_k03_tipo);
    db_fieldsmemory($result_k03_tipo, 0);

    $sqlTipoInicial = "select v04_tipoinicial, v04_tipocertidao from pardiv where v04_instit = " . db_getsession('DB_instit');
    $rsTipoInicial = db_query($sqlTipoInicial);
    if (pg_num_rows($rsTipoInicial) > 0) {
      db_fieldsmemory($rsTipoInicial, 0);
    } else {
      db_msgbox("Configure o tipo de debito para certidao");
    }
    if (pg_result($result, $i, "k00_tipo") == $v04_tipoinicial) {
      $nome_arquivo = 'cai3_gerfinanc050.php';
    } else if (pg_result($result, $i, "k00_tipo") == $v04_tipocertidao) {
      $nome_arquivo = 'cai3_gerfinanc040.php';
    } else {
      $nome_arquivo = 'cai3_gerfinanc002.php';
    }
    if (! isset($certidao)) {
      $certidao = "";
    }
    echo "
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
    <td valign=\"center\" class=\"links\" id=\"tipodeb$i\">
      <a title=\"" . pg_result($result, $i, "k00_tipo") . "\" class=\"links\" href=\"\" id=\"tipodeb$i\" onClick=\"js_MudaLink('tipodeb$i');js_envia('$nome_arquivo?" . $arg . "&tipo=" . pg_result($result, $i, "k00_tipo") . "&emrec=" . pg_result($result, $i, "k00_emrec") . "&agnum=" . pg_result($result, $i, "k00_agnum") . "&agpar=" . pg_result($result, $i, "k00_agpar") . "&certidao=$certidao&k03_tipo=$k03_tipo&perfil_procuradoria=$iPerfilProcuradoria&k00_tipo=" . pg_result($result, $i, "k00_tipo") . "&db_datausu=');return false;\" target=\"debitos\">" . pg_result($result, $i, "k00_descr") . "&nbsp;</a>
    </td>
    </tr>
    </table>\n";

    if ($i == 8){
      echo "</td><td style=\"font-size:11px\" valign=\"top\">\n";
    }
  }
}

echo "</td><td style=\"font-size:11px\" valign=\"top\">\n";

if (isset($tipo_filtro) && $tipo_filtro != "" && isset($cod_filtro) && $cod_filtro != "") {
  $where_lev = "";

  if ($tipo_filtro == "CGM") {
    $where_lev = "and levcgm.y93_numcgm = $cod_filtro";
  } else if ($tipo_filtro == "MATRICULA") {
    $where_lev = "and 1=2";
  } else if ($tipo_filtro == "INSCRICAO") {
    $where_lev = "and levinscr.y62_inscr = $cod_filtro";
  } else if ($tipo_filtro == "NUMPRE") {
    $where_lev = "and arreinscr.k00_numpre = $cod_filtro";
  }
  if ($tipo_filtro == "NUMPRE") {
    $sql_lev = $cllevanta->sql_query_inf_numpre(null, "levanta.*", null, " y60_importado is false " . $where_lev);
  } else {
    $sql_lev = $cllevanta->sql_query_inf(null, "levanta.*", null, " y60_importado is false " . $where_lev);
  }
  $dados_lev = db_query($sql_lev);
  if (pg_numrows($dados_lev) > 0) {
    echo "
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
    <td valign=\"top\" class=\"links2\" id=\"tiposemdeb2\">
    <a class=\"links2\"  id=\"tiposemdeb2\"  href=\"cai4_gerfinanc006.php?cod=$cod_filtro&tipo=$tipo_filtro\" target=\"debitos\">LEVANTAMENTO FISCAL</a>
    </td>
    </tr>
    </table>\n";
  }
}

if (@$tipo_pesq [0] != "numpre") { // inicio do tipo de certidao
  $permissao = db_permissaomenu(db_getsession("DB_anousu"), 1985522, 5604);

  //colocado aqui
  $tipo_pesq = split("=", $arg);
  if ($tipo_pesq [0] != "numpre") {
      $tipo = "c";
      $whereissvar = ($k03_certissvar == 't' ? " k00_valor <> 0 " : "");
    if ($tipo_pesq [0] == "matric") {
      $tipo = "m";
    } else if ($tipo_pesq [0] == "inscr") {
      $tipo = "i";
    }

    $iAnoUsu        = db_getsession('DB_anousu');
    $iInst          = db_getsession('DB_instit');
    $sQueryRegraCND = "select k03_regracnd,k03_tipocertidao from numpref where  k03_instit = $iInst and k03_anousu = $iAnoUsu";

    $resQueryRegraCND = db_query($sQueryRegraCND);
    if (pg_num_rows($resQueryRegraCND) > 0) {

      db_fieldsmemory($resQueryRegraCND, 0);
      $sTipo = $k03_regracnd;
    }

    $link    = '';
    $indconj = '';
    if($k03_tipocertidao == '1') {

      $nrovias  = 1;
      $indconj  = 0;
    }elseif($k03_tipocertidao == '2'){

      $nrovias  = 1;
      $indconj  = 1;
    }elseif($k03_tipocertidao == '3'){

      $nrovias  = 2;
      $indconj  = 0;
    }

    for($i=0; $i<$nrovias; $i++){

      if($indconj == 1) {

        $tipoindconj = 2;
        if ($whereissvar <> "") {
          $whereissvar .= " and ";
        }
        $whereissvar .= " arreinstit.k00_instit = " . db_getsession("DB_instit");
        $link         = " INDIVIDUAL";
      }elseif($indconj == 0) {

        $link        = " CONJUNTA";
        $tipoindconj = 1;
      }

      $database = date('Y-m-d', db_getsession('DB_datausu'));
      $sql      = "select * from fc_tipocertidao({$tipo_pesq[1]}, '{$tipo}', '{$database}', '{$whereissvar}', {$sTipo})";

      $dados    = db_query($sql) or die($sql);
      db_fieldsmemory($dados, 0);
      $certidao = $fc_tipocertidao;

      if ($certidao == "positiva") {
        echo "
        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr>
        <td valign=\"top\" class=\"links2\" id=\"tiposemdeb3\">";
        if ($permissao == "false") {
          echo "<b>CERTIDÃO POSITIVA$link</b>";
        }else {
          echo "<a class=\"links2\" onClick=\"js_MudaLink('tiposemdeb3')\" id=\"tiposemdeb3\"  href=\"cai3_gerfinanc006.php?" . base64_encode("indconjunta=".$tipoindconj."&tipo_cert=1&" . $arg) . "\" target=\"debitos\">CERTIDÃO POSITIVA$link</a>";
        }
        echo "
        </td>
        </tr>
        </table>\n";
      }else {
        if ($certidao == "regular") {
          echo "
          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
          <td valign=\"top\" class=\"links2\" id=\"tiposemdeb4\">";
          if ($permissao == "false") {
            echo "<b>CERTIDÃO REGULAR</b>";
          }else {
            echo "<a class=\"links2\" onClick=\"js_MudaLink('tiposemdeb4')\" id=\"tiposemdeb4\"  href=\"cai3_gerfinanc006.php?" . base64_encode("indconjunta=".$tipoindconj."&tipo_cert=0&" . $arg) . "\" target=\"debitos\">CERTIDÃO REGULAR$link</a>";
          }
          echo "
            </td>
            </tr>
            </table>\n";
        }else {
          echo "
          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
          <td valign=\"top\" class=\"links2\" id=\"tiposemdeb5\">";
          if ($permissao == "false") {
            echo "<b>CERTIDÃO NEGATIVA$link</b>";
          }else {
            echo "
            <a class=\"links2\" onClick=\"js_MudaLink('tiposemdeb5')\" id=\"tiposemdeb5\"  href=\"cai3_gerfinanc006.php?" . base64_encode("indconjunta=".$tipoindconj."&tipo_cert=2&" . $arg) . "\" target=\"debitos\">CERTIDÃO NEGATIVA$link</a>";
          }
          echo "
          </td>
          </tr>
          </table>\n";
        }
      }

      $indconj = 1;
    }
  }

  //////

    if (@$outrasmatriculas == true) {
      echo "
      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
      <td valign=\"top\" class=\"links2\" id=\"outrasmatriculas\">
      <a class=\"links2\" onClick=\"js_MudaLink('outrasmatriculas')\" id=\"outrasmatriculas\"  href=\"cai3_gerfinanc018.php?opcao=matricula&numcgm=" . $z01_numcgm . "\" target=\"debitos\">MATRÍCULAS CADASTRADAS</a>
      </td>
      </tr>
      </table>\n";
    }
    if (@$outrasinscricoes == true) {
      echo "
      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
      <td valign=\"top\" class=\"links2\" id=\"outrasinscricoes\">
      <a class=\"links2\" onClick=\"js_MudaLink('outrasinscricoes')\" id=\"outrasinscricoes\"  href=\"cai3_gerfinanc018.php?opcao=inscricao&numcgm=" . $z01_numcgm . "&inscricao=" . @$tipo_pesq [1] . "\" target=\"debitos\">INSCRIÇÕES CADASTRADAS</a>
      </td>
      </tr>
      </table>\n";
    }
    if (@$proprietario == true) {
      echo "
      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
      <td valign=\"top\" class=\"links2\" id=\"proprietarios\">
      <a class=\"links2\" onClick=\"js_MudaLink('proprietarios')\" id=\"proprietarios\"  href=\"cai3_gerfinanc018.php?opcao=proprietario&matricula=" . $tipo_pesq [1] . "\" target=\"debitos\">OUTROS PROPRIETÁRIOS</a>
      </td>
      </tr>
      </table>\n";
    }
    if (@$socios == true) {
      echo "
      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
      <td valign=\"top\" class=\"links2\" id=\"socios\">
      <a class=\"links2\" onClick=\"js_MudaLink('socios')\" id=\"socios\"  href=\"cai3_gerfinanc018.php?opcao=socios&numcgm=$z01_numcgm\" target=\"debitos\">SÓCIOS</a>
      </td>
      </tr>
      </table>\n";
    }
    if (@$promitente == true) {
      echo "
      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
      <td valign=\"top\" class=\"links2\" id=\"promitentes\">
      <a class=\"links2\" onClick=\"js_MudaLink('promitentes')\" id=\"promitentes\"  href=\"cai3_gerfinanc018.php?opcao=promitente&matricula=" . $tipo_pesq [1] . "\" target=\"debitos\">PROMITENTES</a>
      </td>
      </tr>
      </table>\n";
    }
    if (@$prestador == true) {
      echo "
          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
          <td valign=\"top\" class=\"links2\" id=\"prestador\">
          <a class=\"links2\" id=\"prestador\"  href=\"cai3_gerfinanc067.php?cgm=" . $z01_numcgm . "\" target=\"debitos\">RETENÇÃO COMO PRESTADOR</a>
          </td>
          </tr>
          </table>\n";
    }

  // pesquisa pagamentos
} // fim do tipo de certidao

  if ($tipo_filtro == "CGM") {

    $_left = "left  join arrenumcgm  on arrenumcgm.k00_numpre  = termo.v07_numpre     ";
    $where = "and arrenumcgm.k00_numcgm = $cod_filtro";
    $tipo  = 'cgm';
  } else if ($tipo_filtro == "MATRICULA") {

    $_left = "left  join arrematric  on arrematric.k00_numpre  = termo.v07_numpre     ";
    $where = "and arrematric.k00_matric = $cod_filtro";
    $tipo  = 'matric';
  } else if ($tipo_filtro == "INSCRICAO") {

    $_left = "left join arreinscr   on arreinscr.k00_numpre   = termo.v07_numpre     ";
    $where = "and arreinscr.k00_inscr = $cod_filtro";
    $tipo  = 'inscr';
  } else if ($tipo_filtro == "NUMPRE") {

    $_left = "";
    $where = "and termo.v07_numpre = $cod_filtro";
    $tipo  = 'numpre';
  } else if ($tipo_filtro == "PARCEL") {

    $_left = "";
    $where = "and termo.v07_parcel = $cod_filtro";
    $tipo  = 'parcel';
  }

  $sSqlTermoAnu  = "select v09_parcel ";
  $sSqlTermoAnu .= "  from termoanu ";
  $sSqlTermoAnu .= "       inner join termo       on termo.v07_parcel = termoanu.v09_parcel ";
  $sSqlTermoAnu .= "       {$_left} ";
  $sSqlTermoAnu .= "       inner join db_usuarios on db_usuarios.id_usuario = termoanu.v09_usuario ";
  $sSqlTermoAnu .= "       inner join cgm         on cgm.z01_numcgm         = termo.v07_numcgm     ";
  $sSqlTermoAnu .= " where termo.v07_instit = " . db_getsession('DB_instit') . " ";
  $sSqlTermoAnu .= $where;

  $rsTermoAnu = $cltermoanu->sql_record($sSqlTermoAnu);

  if ($cltermoanu->numrows > 0) {
    //
    // Parcelamentos anulados
    //
    $htmlParcAnulados = " <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
    $htmlParcAnulados .= "   <tr>";
    $htmlParcAnulados .= "     <td valign=\"top\" class=\"links2\" id=\"outrasmatriculas\">";
    $htmlParcAnulados .= "       <a class=\"links2\" onClick=\"js_MudaLink('parcelamentosanulados')\" id=\"parcelamentosanulados\" href=\"cai3_gerfinanc071.php?opcao=" . $tipo . "&codopcao=" . $cod_filtro . "\" target=\"debitos\">PARCELAMENTOS ANULADOS</a>";
    $htmlParcAnulados .= "     </td> ";
    $htmlParcAnulados .= "   </tr> ";
    $htmlParcAnulados .= " </table>\n";

    echo $htmlParcAnulados;
  }

  if (@$temprescr == true) {
    echo "
      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
      <td valign=\"top\" class=\"links2\" id=\"debitosprescritos\">
      <a class=\"links2\" onClick=\"js_MudaLink('debitosprescritos')\" id=\"prescritos\"
      href=\"cai3_gerfinanc020.php?tipo_filtro=$tipo_filtro&cod_filtro=$cod_filtro&cgm=" . $z01_numcgm . "\" target=\"debitos\">DÉBITOS PRESCRITOS</a>
      </td>
      </tr>
      </table>\n";
  }
  // debito justificado
  if (@$justificado == true) {
    echo "
      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
      <td valign=\"top\" class=\"links2\" id=\"justificado\">
      <a class=\"links2\" onClick=\"js_MudaLink('justificado')\" id=\"justificado\"
      href=\"cai3_gerfinanc024.php?tipo_filtro=$tipo_filtro&cod_filtro=$cod_filtro.\" target=\"debitos\">DÉBITOS JUSTIFICADOS</a>
      </td>
      </tr>
      </table>\n";
  }

 /////////////////////////////////////////////////////////////////////////////////////////////////////
 if ($tipo_filtro != "NUMPRE") {
   echo "<input name='tipo_filtro' type='hidden' value='$tipo_filtro'>";
   echo "<input name='cod_filtro' type='hidden' value='$cod_filtro'>";
   echo " <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> ";
   echo "   <tr> ";
   echo "     <td valign=\"top\" class=\"links2\" id=\"sit_fiscal\"> ";
   echo "       <a class=\"links2\" onClick=\"js_situacao_fiscal($cod_filtro,'$tipo_filtro');\" id=\"sit_fiscal\" href=#>SITUAÇÃO FISCAL</a> ";
   echo "     </td>  ";
   echo "   </tr>    ";
   echo " </table>\n ";
 }





  //---------------------------------------------------------------------------------------------------
  /////////////////////////////////////////////////////////////////////////////////////////////////////
  $sql = " select arrepaga.k00_numpre from arrepaga ";
  if ($tipo_pesq[0] == "numcgm") {
    $sql = $sql . " inner join arrenumcgm on arrepaga.k00_numpre = arrenumcgm.k00_numpre
                    inner join arreinstit on arreinstit.k00_numpre = arrenumcgm.k00_numpre
                                         and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
    where arrenumcgm.k00_numcgm = " . $tipo_pesq [1];
  } else if ($tipo_pesq[0] == "matric") {

    $sql = $sql . "   inner join arrematric on arrematric.k00_numpre = arrepaga.k00_numpre
                      inner join arreinstit on arreinstit.k00_numpre = arrematric.k00_numpre
                                           and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
    where k00_matric = " . $tipo_pesq [1];
  } else if ($tipo_pesq[0] == "inscr") {

    $sql = $sql . "   inner join arreinscr  on arreinscr.k00_numpre = arrepaga.k00_numpre
                      inner join arreinstit on arreinstit.k00_numpre = arreinscr.k00_numpre
                                           and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
    where k00_inscr = " . $tipo_pesq [1];
  } else if ($tipo_pesq[0] == "Parcelamento") {

  	$sql = $sql . "   inner join termo      on termo.v07_numpre = arrepaga.k00_numpre
  	                  inner join arreinstit on arreinstit.k00_numpre = termo.v07_numpre
  	                                       and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
  	                  where v07_parcel = " . $tipo_pesq [1];

  } else {
    $sql = $sql . " where k00_numpre = " . $tipo_pesq [1];
  }
  $sql = $sql . " limit 1";

  $dados = db_query($sql);

  if (pg_numrows($dados) > 0) {
    echo "
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
    <td valign=\"top\" class=\"links2\" id=\"tiposemdeb6\">
    <a class=\"links2\" onClick=\"js_MudaLink('tiposemdeb6')\" id=\"tiposemdeb6\"  href=\"cai3_gerfinanc008.php?" . base64_encode("tipo_cert=1&" . $arg) . "\" target=\"debitos\">PAGAMENTOS EFETUADOS</a>
    </td>
    </tr>
    </table>\n";

    $sUrlReciboPago = null;

    if($tipo_pesq[0] == "numcgm"){
      $sUrlReciboPago = "numcgm=".$tipo_pesq[1];
    } elseif ($tipo_pesq[0] == "matric"){
      $sUrlReciboPago = "matric=".$tipo_pesq[1];
    }elseif($tipo_pesq[0] == "inscr"){
      $sUrlReciboPago = "inscr=".$tipo_pesq [1];
    }

    /**
     * Pagamentos por Recibos
     */
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
    echo "  <tr>";
    echo "    <td valign=\"top\" class=\"links2\" id=\"pagamentosRecibos\">";
    echo "      <a class=\"links2\" onClick=\"js_MudaLink('pagamentosRecibos')\" id=\"pagamentosRecibos\"  href=\"cai3_gerfinancPagamentosRecibos.php?{$sUrlReciboPago}\" target=\"debitos\">CONSULTA BOLETOS PAGOS</a>";
    echo "    </td>";
    echo "  </tr>";
    echo "</table>\n";
  }

  if ($tipo_pesq[0] == "numcgm") {

    $sInnerCredito     = " inner join arrenumcgm on arrenumcgm.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sInnerCompensacao = " inner join arrenumcgm on arrenumcgm.k00_numpre = abatimentoutilizacaodestino.k170_numpre ";
    $sInnerDevolucao   = " inner join arrenumcgm on arrenumcgm.k00_numpre = arreckey.k00_numpre ";

    $sWhereCredito     = " and arrenumcgm.k00_numcgm = ".$tipo_pesq[1];
    $sWhereCompensacao = $sWhereCredito;
    $sWhereDevolucao   = $sWhereCompensacao;

    $sPesquisaOrigem   = " exists ( select 1 from arrenumcgm where k00_numcgm = {$tipo_pesq[1]} and arrenumcgm.k00_numpre = arreckey.k00_numpre)";
  } else if ($tipo_pesq[0] == "matric") {

    $sInnerCredito     = " inner join arrematric on arrematric.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sInnerCompensacao = " inner join arrematric on arrematric.k00_numpre = abatimentoutilizacaodestino.k170_numpre ";
    $sInnerDevolucao   = " inner join arrematric on arrematric.k00_numpre = arreckey.k00_numpre ";

    $sWhereCredito     = " and arrematric.k00_matric = ".$tipo_pesq[1];
    $sWhereCompensacao = $sWhereCredito;
    $sWhereDevolucao   = $sWhereCompensacao;

    $sPesquisaOrigem   = " exists ( select 1 from arrematric where k00_matric = {$tipo_pesq[1]} and arrematric.k00_numpre = arreckey.k00_numpre)";
  } else if ($tipo_pesq[0] == "inscr") {

    $sInnerCredito     = " inner join arreinscr on arreinscr.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sInnerCompensacao = " inner join arreinscr on arreinscr.k00_numpre = abatimentoutilizacaodestino.k170_numpre ";
    $sInnerDevolucao   = " inner join arreinscr on arreinscr.k00_numpre = arreckey.k00_numpre ";

    $sWhereCredito     = " and arreinscr.k00_inscr = ".$tipo_pesq[1];
    $sWhereCompensacao = $sWhereCredito;
    $sWhereDevolucao   = $sWhereCompensacao;

    $sPesquisaOrigem   = " exists ( select 1 from arreinscr where k00_inscr = {$tipo_pesq[1]} and arreinscr.k00_numpre = arreckey.k00_numpre)";
  } else {

    $sInnerCredito     = "";
    $sInnerCompensacao = "";
    $sInnerDevolucao   = "";

    $sWhereCredito     = " and abatimentorecibo.k127_numprerecibo  = ". $tipo_pesq[1];
    $sWhereCompensacao = " abatimentoutilizacaodestino.k170_numpre = ". $tipo_pesq[1];
    $sWhereDevolucao   = " arreckey.k00_numpre                     = ". $tipo_pesq[1];
  }

  /**
   * @todo mover lógica das compensações para dao
   */
  $sSqlCreditosDisponiveis  = " select *                                                                                      ";
  $sSqlCreditosDisponiveis .= "   from abatimentorecibo                                                                       ";
  $sSqlCreditosDisponiveis .= "        inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento ";
  $sSqlCreditosDisponiveis .= "        {$sInnerCredito}                                                                       ";
  $sSqlCreditosDisponiveis .= "  where abatimento.k125_tipoabatimento = 3 and k125_instit = ".db_getsession('DB_instit');
  $sSqlCreditosDisponiveis .= "        {$sWhereCredito}                                                                       ";
  $sSqlCreditosDisponiveis .= "   limit 1                                                                                     ";


  $rsCreditosDisponiveis    = db_query($sSqlCreditosDisponiveis);

  if ( $rsCreditosDisponiveis && pg_num_rows($rsCreditosDisponiveis) > 0 ) {

    echo " <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td valign=\"top\" class=\"links2\" id=\"tiposemdeb7\">
                  <a class=\"links2\" onClick=\"js_MudaLink('tiposemdeb7')\" id=\"tiposemdeb7\"  href=\"cai3_consultacreditos001.php?".$arg."\" target=\"debitos\">CRÉDITOS</a>
                </td>
              </tr>
           </table> ";
  }

  $sSqlDevolucoesCredito  = " select 'DEVOLUÇÕES DE CREDITO'::varchar                                                                                            \n";
  $sSqlDevolucoesCredito .= "   from abatimentoutilizacao                                                                                                        \n";
  $sSqlDevolucoesCredito .= "        left join abatimentoutilizacaodestino on abatimentoutilizacao.k157_sequencial = abatimentoutilizacaodestino.k170_utilizacao \n";
  $sSqlDevolucoesCredito .= "        inner join abatimento           on abatimento.k125_sequencial = abatimentoutilizacao.k157_abatimento                        \n";
  $sSqlDevolucoesCredito .= "        inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial                            \n";
  $sSqlDevolucoesCredito .= "        inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey                                           \n";
  $sSqlDevolucoesCredito .= "        {$sInnerDevolucao}                                                                                                          \n";
  $sSqlDevolucoesCredito .= "  where ".str_replace('and', '', $sWhereCompensacao)."                                                                              \n";
  $sSqlDevolucoesCredito .= "   and abatimentoutilizacaodestino.k170_utilizacao is null and k125_instit = ".db_getsession('DB_instit')."  limit 1                \n";

  $rsDevolucoesCredito    = db_query($sSqlDevolucoesCredito);

  if ( $rsDevolucoesCredito && pg_num_rows($rsDevolucoesCredito) > 0 ) {

    echo " <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td valign=\"top\" class=\"links2\" id=\"tiposemdeb81\">
                  <a class=\"links2\" onClick=\"js_MudaLink('tiposemdeb81')\" id=\"tiposemdeb81\"  href=\"cai3_gerfinanc052.php?".$arg."&tipo=4&devolucao=1\" target=\"debitos\">DEVOLUÇÕES DE CREDITO</a>
                </td>
              </tr>
           </table> ";
  }

  $sSqlCompensacoesDisponiveis  = "select distinct * from (                                                                                                      \n";
  $sSqlCompensacoesDisponiveis .= " select 'COMPENSACAO'::varchar                                                                                                \n";
  $sSqlCompensacoesDisponiveis .= "   from abatimentorecibo                                                                                                      \n";
  $sSqlCompensacoesDisponiveis .= "        inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento                                \n";
  $sSqlCompensacoesDisponiveis .= "        {$sInnerCredito}                                                                                                      \n";
  $sSqlCompensacoesDisponiveis .= "  where abatimento.k125_tipoabatimento = 4  and k125_instit = ".db_getsession('DB_instit')."                                  \n";
  $sSqlCompensacoesDisponiveis .= "        {$sWhereCredito}                                                                                                      \n";
  $sSqlCompensacoesDisponiveis .= "                                                                                                                              \n";
  $sSqlCompensacoesDisponiveis .= " UNION all                                                                                                                    \n";
  $sSqlCompensacoesDisponiveis .= " select 'COMPENSACAO'::varchar                                                                                                \n";
  $sSqlCompensacoesDisponiveis .= "   from abatimentoutilizacaodestino                                                                                           \n";
  $sSqlCompensacoesDisponiveis .= "        inner join abatimentoutilizacao on abatimentoutilizacao.k157_sequencial = abatimentoutilizacaodestino.k170_utilizacao \n";
  $sSqlCompensacoesDisponiveis .= "        inner join abatimento           on abatimento.k125_sequencial = abatimentoutilizacao.k157_abatimento                  \n";
  $sSqlCompensacoesDisponiveis .= "        {$sInnerCompensacao}                                                                                                  \n";
  $sSqlCompensacoesDisponiveis .= "  where ".str_replace('and', '', $sWhereCompensacao)." and k125_instit = ".db_getsession('DB_instit')."                       \n";
  $sSqlCompensacoesDisponiveis .= "   limit 1                                                                                                                    \n";

  $sSqlCompensacoesDisponiveis .= ")      as x      ";


  if (isset( $sPesquisaOrigem) ) {

    $sSqlCompensacoesDisponiveis .= " union                                                                                                           \n";
    $sSqlCompensacoesDisponiveis .= " select * from (                                                                                                 \n";
    $sSqlCompensacoesDisponiveis .= " select 'DESCONTO'::varchar                                                                                      \n";
    $sSqlCompensacoesDisponiveis .= "   from abatimento                                                                                               \n";
    $sSqlCompensacoesDisponiveis .= "        inner join abatimentoarreckey akey on akey.k128_abatimento    = abatimento.k125_sequencial               \n";
    $sSqlCompensacoesDisponiveis .= "        inner join arreckey                on arreckey.k00_sequencial = akey.k128_arreckey                       \n";
    $sSqlCompensacoesDisponiveis .= "  where abatimento.k125_tipoabatimento = 2 and $sPesquisaOrigem and k125_instit = ".db_getsession('DB_instit')." \n";
    $sSqlCompensacoesDisponiveis .= "  limit 1) as y                                                                                                  \n";
  }

  $rsCompensacoesDisponiveis    = db_query($sSqlCompensacoesDisponiveis);

  if ( $rsCompensacoesDisponiveis && pg_num_rows($rsCompensacoesDisponiveis) > 0 ) {

    echo " <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td valign=\"top\" class=\"links2\" id=\"tiposemdeb8\">
                  <a class=\"links2\" onClick=\"js_MudaLink('tiposemdeb8')\" id=\"tiposemdeb8\"  href=\"cai3_gerfinanc052.php?".$arg."&tipo=4&compensacao=1\" target=\"debitos\">COMPENSAÇÕES UTILIZADAS</a>
                </td>
              </tr>
           </table> ";
  }

  // pesquisa cancelamentos efetuados
  $sql = " select arrecant.k00_numpre ";
  $sql .= "   from arrecant ";
  $sql .= "        inner join arreinstit on arreinstit.k00_numpre = arrecant.k00_numpre ";
  $sql .= "                             and arreinstit.k00_instit = " . db_getsession('DB_instit');
  $sql .= "        left join arrepaga p           on p.k00_numpre              = arrecant.k00_numpre ";
  $sql .= "                                      and p.k00_numpar              = arrecant.k00_numpar ";
  $sql .= "        left join arreprescr           on arrecant.k00_numpre       = arreprescr.k30_numpre ";
  $sql .= "                                      and arrecant.k00_numpar       = arreprescr.k30_numpar ";
  $sql .= "                                      and arrecant.k00_receit       = arreprescr.k30_receit ";
  $sql .= "                                      and arreprescr.k30_anulado is false                    ";
  $sql .= "        inner join cancdebitosreg      on cancdebitosreg.k21_numpre = arrecant.k00_numpre ";
  $sql .= "                                      and cancdebitosreg.k21_numpar = arrecant.k00_numpar ";
  $sql .= "                                      and cancdebitosreg.k21_receit = arrecant.k00_receit ";
  $sql .= "        inner join cancdebitosprocreg  on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia ";

  // Se forma de pesquisa da CGF for por...
  // ... CGM
  if ($tipo_pesq [0] == "numcgm") {
    $sql = "select arrenumcgm.k00_numpre ";
    $sql .= "  from arrenumcgm ";
    $sql .= "       inner join arrecant            on arrecant.k00_numpre        = arrenumcgm.k00_numpre ";
    $sql .= "       inner join arreinstit on arreinstit.k00_numpre = arrecant.k00_numpre ";
    $sql .= "                            and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $sql .= "       inner join cancdebitosreg      on cancdebitosreg.k21_numpre  = arrecant.k00_numpre ";
    $sql .= "                                     and cancdebitosreg.k21_numpar  = arrecant.k00_numpar ";
    $sql .= "                                     and cancdebitosreg.k21_receit  = arrecant.k00_receit ";
    $sql .= "       inner join cancdebitosprocreg  on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia ";
    $sql .= "       left join  arreprescr          on arrecant.k00_numpre        = arreprescr.k30_numpre ";
    $sql .= "                                     and arrecant.k00_numpar        = arreprescr.k30_numpar ";
    $sql .= "                                     and arrecant.k00_receit        = arreprescr.k30_receit ";
    $sql .= "                                     and arreprescr.k30_anulado is false                    ";
    $sql .= "       left outer join arrepaga p     on p.k00_numpre               = arrecant.k00_numpre ";
    $sql .= "                                     and p.k00_numpar               = arrecant.k00_numpar ";
    $sql .= "                                     and p.k00_receit               = arrecant.k00_receit ";
    $sql .= " where arrenumcgm.k00_numcgm = " . $tipo_pesq [1];

  // ... Matricula do Cadastro Imobiliario
  } else if ($tipo_pesq [0] == "matric") {
    $sql = "select arrematric.k00_numpre ";
    $sql .= "  from arrematric ";
    $sql .= "       inner join arrecant            on arrecant.k00_numpre        = arrematric.k00_numpre ";
    $sql .= "       inner join arreinstit          on arreinstit.k00_numpre = arrecant.k00_numpre ";
    $sql .= "                                     and arreinstit.k00_instit = " . db_getsession('DB_instit');
    $sql .= "       inner join cancdebitosreg      on cancdebitosreg.k21_numpre  = arrecant.k00_numpre ";
    $sql .= "                                     and cancdebitosreg.k21_numpar  = arrecant.k00_numpar ";
    $sql .= "                                     and cancdebitosreg.k21_receit  = arrecant.k00_receit ";
    $sql .= "       inner join cancdebitosprocreg  on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia ";
    $sql .= "       left join  arreprescr          on arrecant.k00_numpre        = arreprescr.k30_numpre ";
    $sql .= "                                     and arrecant.k00_numpar        = arreprescr.k30_numpar ";
    $sql .= "                                     and arrecant.k00_receit        = arreprescr.k30_receit ";
    $sql .= "                                     and arreprescr.k30_anulado is false                    ";
    $sql .= "       left join  arrepaga p          on p.k00_numpre               = arrecant.k00_numpre ";
    $sql .= "                                     and p.k00_numpar               = arrecant.k00_numpar ";
    $sql .= "                                     and p.k00_receit               = arrecant.k00_receit ";
    $sql .= "where k00_matric = " . $tipo_pesq [1];

  // ... Inscricao do Cadastro do ISS
  } else if ($tipo_pesq [0] == "inscr") {
    $sql = " select arreinscr.k00_numpre ";
    $sql .= "   from arreinscr ";
    $sql .= "        inner join arrecant            on arrecant.k00_numpre        = arreinscr.k00_numpre ";
    $sql .= "        inner join arreinstit          on arreinstit.k00_numpre      = arrecant.k00_numpre ";
    $sql .= "                                      and arreinstit.k00_instit      = " . db_getsession('DB_instit');
    $sql .= "        inner join cancdebitosreg      on cancdebitosreg.k21_numpre  = arrecant.k00_numpre ";
    $sql .= "                                      and cancdebitosreg.k21_numpar  = arrecant.k00_numpar ";
    $sql .= "                                      and cancdebitosreg.k21_receit  = arrecant.k00_receit ";
    $sql .= "        inner join cancdebitosprocreg  on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia ";
    $sql .= "        left join  arreprescr          on arrecant.k00_numpre        = arreprescr.k30_numpre ";
    $sql .= "                                      and arrecant.k00_numpar        = arreprescr.k30_numpar ";
    $sql .= "                                      and arrecant.k00_receit        = arreprescr.k30_receit ";
    $sql .= "                                      and arreprescr.k30_anulado is false                    ";
    $sql .= "        left join  arrepaga p          on p.k00_numpre               = arrecant.k00_numpre ";
    $sql .= "                                      and p.k00_numpar               = arrecant.k00_numpar ";
    $sql .= "                                      and p.k00_receit               = arrecant.k00_receit ";
    $sql .= "  where k00_inscr = " . $tipo_pesq [1];

  // caso contrario pelo NUMPRE
  } else {
    $sql = $sql . " where arrecant.k00_numpre = " . $tipo_pesq [1];
  }
  $sql = $sql . " and p.k00_numpre is null and arreprescr.k30_numpre is null limit 1";
  //echo $sql;exit;
  $dados = db_query($sql);

  if (pg_numrows($dados) > 0) {
    echo "
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
    <td valign=\"top\" class=\"links2\" id=\"tipodesconto7\">
    <a class=\"links2\" onClick=\"js_MudaLink('tipodesconto7')\" id=\"tipodesconto7\"  href=\"cai3_gerfinanc016.php?" . base64_encode("tipo_cert=1&" . $arg) . "\" target=\"debitos\">CANCEL. EFETUADOS</a>
    </td>
    </tr>
    </table>\n";
  }

  $sql = " select arresusp.k00_numpre
         from arresusp
            inner join suspensao on suspensao.ar18_sequencial = arresusp.k00_suspensao ";

  if ($tipo_pesq [0] == "numcgm") {
    $sql = $sql . " inner join arrenumcgm on arresusp.k00_numpre   = arrenumcgm.k00_numpre
                  inner join arreinstit on arreinstit.k00_numpre = arrenumcgm.k00_numpre
                                     and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
    where arrenumcgm.k00_numcgm = " . $tipo_pesq [1];
  } else if ($tipo_pesq [0] == "matric") {
    $sql = $sql . "   inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre
                  inner join arreinstit on arreinstit.k00_numpre = arrematric.k00_numpre
                                       and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
    where k00_matric = " . $tipo_pesq [1];
  } else if ($tipo_pesq [0] == "inscr") {
    $sql = $sql . "   inner join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre
                  inner join arreinstit on arreinstit.k00_numpre = arreinscr.k00_numpre
                                       and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
    where k00_inscr = " . $tipo_pesq [1];
  } else {
    $sql = $sql . " where ar18_situacao = 1 and k00_numpre = " . $tipo_pesq [1];
  }
  $sql = $sql . " limit 1";

  $dados = db_query($sql);
  if (pg_numrows($dados) > 0) {
    echo "
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
    <td valign=\"top\" class=\"links2\" id=\"tiposemdeb7\">
    <a class=\"links2\" onClick=\"js_MudaLink('tiposemdeb7')\" id=\"tiposemdeb7\"  href=\"cai3_gerfinanc026.php?tipo_cert=1&" . $arg . "\" target=\"debitos\">DÉBITOS SUSPENSOS</a>
    </td>
    </tr>
    </table>\n";
  }

  echo "</td>\n</tr>\n</table>\n";

  if ($mensagem_semdebitos == false and $com_debitos == true) {
    echo "
    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
    <tr>
      <td valign=\"top\" class=\"links2\" id=\"tiposemdebitototal\">
        <a class=\"links\" href=\"\" onClick=\"js_MudaLink('tiposemdebitototal');js_envia('cai3_gerfinanc010.php?" . $arg . "&db_datausu=');return false;\" id=\"tiposemdebitototal\"  target=\"debitos\">TOTAL DE DÉBITOS</a>
      </td> ";
    echo "
    </tr>
    </table>\n";
  }

  ?>
  </td>
          </tr>
          <td height="2">
          </form>

        </table>
        </td>
      </tr>
      <tr>
        <td width="100%" colspan="2" align="center" valign="middle">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
  <?
  db_input("tipo_filtro", 20, '', true, "hidden", 3);
  db_input("cod_filtro", 40, '', true, "hidden", 3);
  ?>
            <iframe id="debitos" height="235" width="100%" name="debitos"
              src="cai3_gerfinanc007.php?<?=$arg?>"></iframe></td>
          </tr>
          <tr>
            <td align="right">
            <table border="1" bordercolor="#000000" cellspacing="0"
              cellpadding="0" width="100%">
              <tr bgcolor="#666666">
                <th style="font-size: 11px">Valor</th>
                <th style="font-size: 11px">Valor Corr.</th>
                <th style="font-size: 11px">Juros</th>
                <th style="font-size: 11px">Multa</th>
                <th style="font-size: 11px">Desconto</th>
                <th style="font-size: 11px">Total</th>
              </tr>
              <tr>
                <td class="tabcols1"><font id="valor1">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="valorcorr1">0.00</font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="juros1">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="multa1">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="desconto1">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="total1">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
              </tr>
              <tr>
                <td class="tabcols1"><font id="valor2">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="valorcorr2">0.00</font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="juros2">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="multa2">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="desconto2">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="total2">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
              </tr>
              <tr>
                <td class="tabcols1"><font id="valor3">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="valorcorr3">0.00</font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="juros3">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="multa3">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="desconto3">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
                <td class="tabcols1"><font id="total3">0.00 </font><img
                  src="imagens/alinha.gif" border="0" width="5"></td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td height="24">
          <input type="button" name="btmarca"         id="btmarca"          value="Marcar"            onClick="debitos.js_marca()">
          <input type="button" name="btmarcavencidas" id="btmarcavencidas"  value="Marcar Vencidas"   onClick="debitos.js_marca_vencidas()" >
          <input type="button" name="geranotif"       id="geranotif"        value="Gerar Notificação" onClick="js_geraNotif()" disabled>

          <input type="button" name="btnSuspender"    id="btnSuspender"     value="Suspender"         onClick="js_suspender()" disabled>
          <input type="button" name="enviar"          id="enviar"           value="Recibo"            onClick="return js_emiterecibo()" disabled>
          <input type="button" name="btcarne"         id="btcarne"          value="Carne Banco"       onClick="js_emitecarne(true)" disabled>

	        <input type="button" name="relatorio"       id="relatorio"        value="" style="display: none">

	        <input type="button" name="btparc"          id="btparc"           value="Parcelamento"          onClick="js_parcelamento()"            disabled />
	        <input type="button" name="btcda"           id="btcda"            value="CDA"                   onClick="js_certidao()"                disabled />
	        <input type="button" name="btcancela"       id="btcancela"        value="Cancela Débito"        onClick="js_cancela()"                 disabled />
	        <input type="button" name="btjust"          id="btjust"           value="Justifica"             onClick="js_justifica()"               disabled />
	        <input type="button" name="btimpdiverso"    id="btimpdiverso"     value="Importar p/ Diversos"  onClick="js_importaDebitos_Diversos()" disabled />
	        <input type="button" name="btnotifica"      id="btnotifica"       value="Notificação"           onClick="js_emitenotificacao()"        disabled style='visibility: hidden' />
	        <input type="hidden" name="marcartodas"     id="marcartodas"      value="false" >
	        <input type="hidden" name="marcarvencidas"  id="marcarvencidas"   value="false" >
        </td>
      </tr>
      <tr>
        <td nowrap align="left">
          <?
          /*************************************************************************************************************************************/
          echo "<b> Parcelas de outros exercicios : </b>";
          $x = array (
            "i" => "Imprimir todas mas com qtd. de Inflator para exercicios posteriores",
            "n" => "Não imprimir parcelas de exercicios posteriores"
          );
          db_select('emisscarne', $x, true, "", " disabled onchange=''");
          /*************************************************************************************************************************************/
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right"></td>
      </tr>
      <script type="text/javascript">

        function js_mostradiv(liga,evt,vlr){
          evt= (evt)?evt:(window.event)?window.event:"";
          if(liga){
            document.getElementById('vlr').innerHTML=vlr;
            document.getElementById('divlabel').style.left=0;
            document.getElementById('divlabel').style.top=0;
            document.getElementById('divlabel').style.visibility='visible';
          }else{
            document.getElementById('divlabel').style.visibility='hidden';
          }
        }

        function js_certidao(){
          if(confirm('Confirma emissão da CDA?')==true){
            deb = debitos.document.form1
            document.getElementById('btcda').disabled = true;
            debitos.document.form1.action = 'cai3_gerfinanc064.php?valor='+document.getElementById('total2').innerHTML;
            debitos.document.form1.target = '_self';
            debitos.document.form1.submit();
          }
        }
        function js_cancela(){
          debitos.document.form1.action = 'cai3_gerfinanc065.php?valor='+document.getElementById('total2').innerHTML;
          debitos.document.form1.target = '_self';
          debitos.document.form1.submit();
        }
        function js_justifica(){
          debitos.document.form1.action = 'cai3_gerfinanc066.php?valor='+document.getElementById('total2').innerHTML;
          debitos.document.form1.target = '_self';
          debitos.document.form1.submit();
        }
      </script>
      <form name="form1" method="post">


      <td align="right" nowrap title="Data para cálculo dos acréscimos no sistema">

        <span id="impvalores" style="display: none;">
	        <strong>Imprimir com Valores : </strong>
	        <?
	          $aFormEmissao = array("1" => "Com valores originais",
	                                "2" => "Com valores atualizados");
	          db_select('formemissao', $aFormEmissao, true, 2, " onchange='js_mudaFormEmissao();' ");
	        ?>
        </span>
        &nbsp;&nbsp;
        <b> Processar descontos recibo : </b>
        <input type='checkbox' name='processarDescontoRecibo' value='processarDescontoRecibo'
               id='processarDescontoRecibo' checked> &nbsp;&nbsp;
        <b> Forçar vencimento </b>
        <input type='checkbox' name='forcarvencimento' value='forcarvencimento'
               id='forcarvencimento'> &nbsp;&nbsp;
        <strong>Data Pagamento:</strong>
  <?
  //
  $k00_dtoper = date('Y-m-d', db_getsession("DB_datausu"));
  $k00_dtoper_dia = date('d', db_getsession("DB_datausu"));
  $k00_dtoper_mes = date('m', db_getsession("DB_datausu"));
  $k00_dtoper_ano = date('Y', db_getsession("DB_datausu"));
  //
  $Ik00_dtoper = '9';
  db_inputdata('k00_dtoper', $k00_dtoper_dia, $k00_dtoper_mes, $k00_dtoper_ano, true, 'text', 4);
  ?>

  <!--<b>AGRUPAR:</b>-->
  <input name="seagrupar" type="hidden" id="seagrupar" value="seagrupar">

  <input name="japarcelou" id="japarcelou" type="hidden" value="0">
  <input name="numpresaparcelar" id="numpresaparcelar" type="hidden" value="">
  <input name="numparaparcelar" id="numparaparcelar" type="hidden" value="">

  </td>

  <div id="divlabel" style="position: absolute; visibility: hidden;">
  <table cellpadding="2">
  <tr nowrap>
  <td align="center" nowrap>
  <span color="#9966cc" id="vlr"></span><br>
  </td>
  </tr>
  </table>
  </div>

  </form>
  </tr>
  </table>
  <?

} else {

  if (!session_is_registered("conteudoparc")) {

    session_register("conteudoparc");
    db_putsession("conteudoparc", "");

    session_register("valoresparc");
    db_putsession("valoresparc", "");
  } else {

    db_putsession("conteudoparc", "");
    db_putsession("valoresparc", "");
  }
  ?>
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
        <legend>Geral Financeira</legend>
        <table>
          <tr>
            <td title="<?php echo $Tz01_nome; ?>">
              <?php db_ancora($Lz01_nome, 'js_mostranomes(true);', 4); ?>
            </td>
            <td>
              <input type="text" name="z01_numcgm" id="z01_numcgm" maxlength="8" size="8" autocomplete="off" onkeyup="js_ValidaCampos(this,1,'Numcgm','t','f',event);" onblur="js_ValidaMaiusculo(this,'f',event);" onchange="js_mostranomes(false);" title="Numero de Identificação do Contribuinte ou Empresa no Cadastro geral do Município Campo:z01_numcgm "/>
              <?php db_input("z01_nome", 40, $Iz01_nome, true, 'text', 5); ?>
            </td>
          </tr>

          <tr>
            <td title="<?php echo $Tj01_matric; ?>">
              <?php db_ancora($Lj01_matric, "js_mostramatricula(true,'$j18_nomefunc');", 2); ?>
            </td>
            <td>
              <input type="text" name="j01_matric" id="j01_matric" maxlength = "8" size="8" autocomplete="off" onkeyup="js_ValidaCampos(this,1,'Matrícula do Imóvel','t','f',event);" onblur="js_ValidaMaiusculo(this,'f',event);" onchange  = "js_mostramatricula(false,'<?=$j18_nomefunc?>')" title     = "Codigo da matrícula do imovel para identificar o proprietário de um determinado lote. Campo:j01_matric "/>
            </td>
          </tr>

          <?php if ($db21_usasisagua == false) { ?>
            <tr>
              <td title="<?php echo $Tq02_inscr; ?>">
                <?php db_ancora($Lq02_inscr, 'js_mostrainscricao(true);', 4); ?>
              </td>
              <td>
                <input type="text" name="q02_inscr" id="q02_inscr" maxlength="8" size="8" autocomplete="off" onkeyup="js_ValidaCampos(this,1,'Inscrição Municipal','t','f',event);" onblur="js_ValidaMaiusculo(this,'f',event);" onchange="js_mostrainscricao(false)" title="Inscricao Municipal no cadastro de alvará Campo:q02_inscr "/>
              </td>
            </tr>
          <?php } else { ?>
            <input type="hidden" name="q02_inscr"  value="">
          <?php } ?>

          <tr>
            <td title="<?php echo $Tk00_numpre; ?>">
              <?php db_ancora($Lk00_numpre, 'js_mostranumpre(true);', 3); ?>
            </td>
            <td>
              <input type="text" name="k00_numpre" id="k00_numpre" size="8" maxlength="8"/>
            </td>
          </tr>

          <tr>
            <td title="<?php echo $Tv07_parcel; ?>">
              <?php db_ancora($Lv07_parcel, 'js_mostraparcel(true);', 3); ?>
            </td>
            <td>
              <input type="text" name="v07_parcel" id="v07_parcel" size="8" maxlength="8" />
            </td>
          </tr>

          <tr>
            <td title="<?php echo $Tk50_notifica; ?>">
              <?php db_ancora($Lk50_notifica, '', 3); ?>
            </td>
            <td>
              <input type="text" name="k50_notifica" id="k50_notifica" size="8" maxlength="8" />
            </td>
          </tr>

        </table>
      </fieldset>
      <input onClick="if((this.form.v07_parcel.value=='' && this.form.z01_numcgm.value=='' && this.form.j01_matric.value=='' && this.form.q02_inscr.value=='' && this.form.k00_numpre.value=='' && this.form.k50_notifica.value=='' )) {   alert('Informe numcgm, matricula, inscrição, parcelamento ou numpre!');return false; }"  type="submit" value="Pesquisar" name="pesquisar">
    </form>
<?php } ?>
<?php
  db_menu( db_getsession("DB_id_usuario"),
           db_getsession("DB_modulo"),
           db_getsession("DB_anousu"),
           db_getsession("DB_instit") );
?>
</div>
</body>
</html>
<script>
// mostra os dados do cgm do contribuinte
function js_mostracgm(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nome','prot3_conscgm002.php?fechar=func_nome&numcgm=<?=@$z01_numcgm?>','Pesquisa',true);
}


// esta funcao é utilizada quando clicar na matricula após pesquisar
// a mesma
function js_mostrabic_matricula(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bicmatric','cad3_conscadastronovo_002.php?cod_matricula=<?=@$matric?>','Pesquisa',true);
}
// esta funcao é utilizada quando clicar na inscricao após pesquisar
// a mesma
function js_mostrabic_inscricao(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bicinscr','iss3_consinscr003.php?numeroDaInscricao=<?=@$inscr?>','Pesquisa',true);
}

function js_mostranomes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nomes','func_nome.php?funcao_js=parent.js_preenche|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nomes','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_preenche1','Pesquisa',false);
  }
}
function js_preenche(chave,chave1){
  document.form1.z01_numcgm.value = chave;
  document.form1.z01_nome.value = chave1;
  db_iframe_nomes.hide();
}
function js_preenche1(chave,chave1){
  document.form1.z01_nome.value = chave1;
  if(chave==true){
    document.form1.z01_numcgm.value = "";
    document.form1.z01_numcgm.focus();
  }
}

function js_mostramatricula(mostra, nome_func){
  document.form1.z01_numcgm.value = "";
  document.form1.q02_inscr.value  = "";
  if(mostra==true){
    if(nome_func != "func_iptubase.php") {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matric',nome_func+'?funcao_js=parent.js_preenchematricula|0|1','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matric',nome_func+'?funcao_js=parent.js_preenchematricula3|0|1|2','Pesquisa',true);
    }
  }else {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matric',nome_func+'?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenchematricula2','Pesquisa',false);
  }
}
function js_preenchematricula3(chave,chave1,chave2){

  document.form1.j01_matric.value = chave;
  document.form1.z01_nome.value   = chave2;
  db_iframe_matric.hide();

}
function js_preenchematricula(chave,chave1){

  document.form1.j01_matric.value = chave;
  document.form1.z01_nome.value   = chave1;
  db_iframe_matric.hide();

}
function js_preenchematricula2(chave,chave1){

  if(chave1 == false) {
    document.form1.z01_nome.value = chave;
    db_iframe_matric.hide();
  }else {
    document.form1.z01_nome.value   = chave;
    document.form1.j01_matric.value = "";
    db_iframe_matric.hide();
  }
  if(document.form1.j01_matric.value == '' && document.form1.z01_nome.value == ''){
    document.form1.z01_nome.value   = '';
  }
}

function js_mostrainscricao(mostra){
  document.form1.j01_matric.value = "";
  document.form1.z01_numcgm.value = "";
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostra|q02_inscr|z01_nome|q02_dtbaix','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
  }
}

function js_mostra(chave1,chave2,baixa){

  if(chave2 != false) {
    document.form1.q02_inscr.value  = chave1;
    document.form1.z01_nome.value   = chave2;
    db_iframe.hide();
  }else {
    document.form1.z01_nome.value   = chave1;
  }

  if(document.form1.q02_inscr.value == '') {
    document.form1.z01_nome.value   = '';
  }

  if( typeof(baixa)=="undefined" && chave2 == true ){
	  document.form1.z01_nome.value   = chave1;
	  document.form1.q02_inscr.value  = '';
	}

  db_iframe.hide();
}

<?
if ($db21_usasisagua==true) {
?>
  document.form1.j01_matric.focus();
<?
} else {
?>
  if(document.form1.z01_numcgm) {
    document.form1.z01_numcgm.focus();
  }
<?
}
?>

<?
if($mensagem_semdebitos == true){
  echo "alert(' 2- Sem débitos a Pagar')";
}
?>

function js_mostradetalhes(chave){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_mostrainscr',chave,'Pesquisa',true, 20, 0, document.body.clientWidth, document.body.clientHeight - 30);
}

//-------------func Situação Fiscal - Por /*Rogerio Baum*/ -----------------------

function js_situacao_fiscal(cod,tipo){

  if ($F('cod_filtro') != cod) {
    cod = $F('cod_filtro');
  }

  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_sitfiscal','cai3_consitfiscal002.php?cod='+cod+'&tipo='+tipo,'Situação Fiscal',true);
}

//--------------------------------------------------------

function js_mudaFormEmissao() {

  var formemissao = document.getElementById('formemissao').value;
  debitos.document.form1.k00_formemissao.value = formemissao;
}


/**
 * Importação de Diversos
 */

 function js_importaDebitos_Diversos() {

   var oFormularioDebitos   = debitos.document.form1;
   var sDebitosSelecionados = "";

   for(i = 0; i < oFormularioDebitos.length; i++) {

     var oCheckBox =oFormularioDebitos.elements[i];
     if (oCheckBox.type == "checkbox" && oCheckBox.checked == true) {

       var sValorCampo = oCheckBox.value;

       if ( oCheckBox.value.indexOf("N") == -1 ) {
         sValorCampo = "N" + sValorCampo;
       }
       sDebitosSelecionados = sDebitosSelecionados + sValorCampo;
     }
   }
   var aSelecaoDebitosNumpre  = sDebitosSelecionados.split("N");
   var aDebitos               = new Array();

   aSelecaoDebitosNumpre.each(
     function( sDebito ) {

       if ( sDebito == "" ) {
         return;
       }
       var aDebito         = sDebito.split("P");
       var aParcelaReceita = aDebito[1].split("R");

       var iNumpre         = aDebito[0];
       var iNumpar         = aParcelaReceita[0];
       var iReceita        = aParcelaReceita[1];

       aDebitos.push({iNumpre  : iNumpre,
                      iNumpar  : iNumpar,
                      iReceita : iReceita});
     }
   );

   /**
    *  Retorna variáveis da $_GET do Frame debitos
    */
   var oGetFrameDebitos   = js_urlToObject(debitos.location.search);
   var aDebitosComponente = new Array();
   js_divCarregando('Pesquisando Débitos.', 'msgAjax');

   var oParam             = new Object();
   var oAjax              = new Object();

   oParam.sExec           = "getDebitos";

   oParam.iTipoPesquisa   = 4; //Array de Debitos;
   oParam.aChavePesquisa  = aDebitos;

   oAjax.method           = 'POST';
   oAjax.parameters       = 'json=' + Object.toJSON(oParam);
   oAjax.asynchronous     = false;
   oAjax.onComplete       = function ( oAjax ) {

   js_removeObj('msgAjax');

   var oRetorno  = eval("("+oAjax.responseText+")");

   if (oRetorno.status == 2) {

     alert(oRetorno.message.urlDecode().replace(/\\n/g, '\n') );
     return;
   }

     for (var iDebito = 0; iDebito < oRetorno.aDebitos.length; iDebito++)  {

       var oDebito = oRetorno.aDebitos[iDebito];

       oImportacao.adicionarDebitos(oDebito.k00_numpre,
                                    oDebito.k00_numpar,
                                    oDebito.k00_descr,
                                    oDebito.k02_codigo,
                                    oDebito.k02_descr,
                                    oDebito.k00_valor );
     }

     for (var iProcedencia = 0; iProcedencia < oRetorno.aProcdiver.length; iProcedencia++)  {

       var oProcedencia = oRetorno.aProcdiver[iProcedencia];

       oImportacao.adicionarProcedencias(oProcedencia.dv09_procdiver,
                                         oProcedencia.dv09_descra,
                                         oProcedencia.dv09_descr,
                                         oProcedencia.dv09_tipo);
     }



   }
   oImportacao = new DBViewImportacaoDiversos('oImportacao', 'importacao');

   if (  $('botaoChavePesquisa') && $('botaoChavePesquisa').getAttribute("tipopesquisa") == "matricula" ) {
     oImportacao.setTipoPesquisa(2);

     oImportacao.setChavePesquisa($('botaoChavePesquisa').getValue())
   }
   oImportacao.setCallBackFunction(js_retornoTestes);


   var oRequest           = new Ajax.Request('dvr3_importacaoiptu.RPC.php', oAjax);
   oImportacao.show();

   return;
}
function js_retornoTestes() {
  $('pesquisar').click();
}

/**
 * Abre Janela para emissao de Certidão
 *
 * @param  CallBack  fCallBack funcao de retorno - recebe parametros(codigoprocesso, observações)
 * @access public
 * @return void
 */
function js_windowCertidao( fCallBack, iTipoCertidao ) {

  if ( $('EmissaoCertidao') ) {
    $('EmissaoCertidao').outerHTML = '';
  }

  sTipoCertidao = "Negativa";

  if( iTipoCertidao == 1 ){
	 	sTipoCertidao = "Positiva";
	}else if( iTipoCertidao == 0 ){
	 	sTipoCertidao = "Regular";
	}

  var sCaminhoMensagem    = 'arrecadacao.cai2_emitecnd.';

  var oElemento           = document.createElement("div");
      oElemento.className = 'container-window-aux';

  var sConteudo  = "<fieldset>                                                               \n";
  sConteudo     += "  <legend>Dados da Emissão</legend>                                      \n";
  sConteudo     += "  <table class='form-container'>                                         \n";
  sConteudo     += "    <tr>                                                                 \n";
  sConteudo     += "      <td style='width: 60px !important'>Processo:</td>                  \n";
  sConteudo     += "      <td><input class='field-size2' id='codigo_processo_certidao'/></td>\n";
  sConteudo     += "    </tr>                                                                \n";
  sConteudo     += "    <tr>                                                                 \n";
  sConteudo     += "      <td colspan='2'>                                                   \n";
  sConteudo     += "        <fieldset class='separator'>                                     \n";
  sConteudo     += "          <legend>Histórico</legend>                                     \n";
  sConteudo     += "          <textarea id='observacao_certidao'></textarea>                 \n";
  sConteudo     += "        </fieldset>  	                                                  \n";
  sConteudo     += "      </td>                                                              \n";
  sConteudo     += "    </tr>                                                                \n";
  sConteudo     += "  </table>                                                               \n";
  sConteudo     += "</fieldset>  	                                                          \n";

  var oInput     = document.createElement('input');
  oInput.type    = 'button';
  oInput.value   = 'Processar';
  oInput.onclick = function() {
		var oProcessoCertidao = $('codigo_processo_certidao');

		if (oProcessoCertidao.value.match(/[^0-9]/)) {
			alert(_M( sCaminhoMensagem + 'campo_processo_numerico'));
			return false;
		}

    fCallBack( $F('codigo_processo_certidao'), $F('observacao_certidao') );
  }

  oElemento.innerHTML = sConteudo;
  oElemento.appendChild(oInput);

  var oWindowAux    =  new windowAux( 'EmissaoCertidao', _M( sCaminhoMensagem + 'titulo_janela_emissao'), 500, 300 );
  oWindowAux.setContent( oElemento );
  oWindowAux.show();
  var oMessageBoard = new DBMessageBoard( null,
                                          _M( sCaminhoMensagem + 'titulo_message_board', { 'sTipoCertidao' : sTipoCertidao } ),
                                          _M( sCaminhoMensagem + 'texto_message_board' ),
                                          oElemento );
  oMessageBoard.show();

  $('codigo_processo_certidao').observe('keyup', function() {
		this.value = this.value.replace(/[^0-9]/, '');
	})
}

function js_parcelamento(){

  x      = 0;
  var vir = "";
  var inicial = "";
  numpre = "";
  nump   = "";
  deb    = debitos.document.form1;
  var qtdeTotalDebitos  = 0;

  for(i = 0; i < deb.length; i++){

    if(deb.elements[i].type == "checkbox"){
      deb.elements[i].value.split("N").each(function(item){
        if(item.trim() != null && item.trim() != '') {
          qtdeTotalDebitos++;
        }
      });
    }

    if(deb.k03_parcelamento.value == 't'){

      if(deb.elements[i].type == "checkbox"){
        if(deb.elements[i].checked == true){

          numpre = deb.elements[i].value.split("N");
          numpre = numpre[0].split("P");

          if(nump == ""){
            nump = numpre[0];
          }else{

            if(numpre[0] != nump){

              alert('Você deve reparcelar um parcelamento de cada vez!');
              x = 1;
              break;
            }

            nump = numpre[0];
          }
        }
      }
    }

    if ( typeof deb.inicial != 'undefined' ) {
      if ( deb.inicial.value == 't' ) {
        if (deb.elements[i].type == "checkbox") {
          if (deb.elements[i].checked == true) {
            inicial += vir + deb.elements[i].value;
            vir = ",";
          }
        }
      }
    }
  }

  if(x == 0){

    var oParans = {
      valor        : document.getElementById('total2').innerHTML,
      valorcorr    : document.getElementById('valorcorr2').innerHTML,
      juros        : document.getElementById('juros2').innerHTML,
      multa        : document.getElementById('multa2').innerHTML,
      totregistros : qtdeTotalDebitos
    }

    if(inicial != ""){
      oParans.inicial = inicial;
    } else {

      oParans.japarcelou       = document.form1.japarcelou.value;
      oParans.numpresaparcelar = document.form1.numpresaparcelar.value;
      oParans.numparaparcelar  = document.form1.numparaparcelar.value;
    }

    var oDBDocumentDebitos = new DBDocumentDebitos('cai3_gerfinanc062.php', '_self', oParans);

    oDBDocumentDebitos.getParameters().submit();

    return false;
  }

}

/**
 * Classe responsavel pela administração do submit de muitos debitos da Geral Financeira
 * @param string sFormPathFile
 * @param string sFormTarget
 * @param object oFormParameters
 */
function DBDocumentDebitos(sFormPathFile, sFormTarget, oFormParameters){

  this.oDocument = CurrentWindow.corpo.debitos.document; //debitos.document;

  this.oForm     =  CurrentWindow.corpo.debitos.document.form1; //debitos.document.form1;

  this.oTable    =  CurrentWindow.corpo.debitos.document.getElementById('tabdebitos'); // debitos.document.getElementById('tabdebitos');

  this.sRpc = "cai3_gerfinanc.RPC.php";

  this.sMensagem = "Aguarde...";

  this.oParameters = {};

  this.sFormPathFile = sFormPathFile;

  this.sFormTarget = sFormTarget;

  this.oFormParameters = oFormParameters;

  this.getParameters = function(){

    this.oParameters.sExecucao = 'geralFinanceiraDebitosRequest';

    var iCheckBox = 0;
    var iValores  = 0;

    // Percorre elementos do form carpurando checkbox de numpres selecionados e soma ao JSON de parametros

    for(i = 0; i < this.oForm.length; i++){

      var oElement = this.oForm.elements[i];

      if(oElement.type == "checkbox"){

        var oCheckbox = this.oDocument.getElementById("CHECK"    + iCheckBox);

        if (!this.oDocument.getElementById("_VALORES" + iValores) && iValores == 0) {
          iValores = 1;
        }

        var oHidden   = this.oDocument.getElementById("_VALORES" + iValores);

        var aDebito = {
          "sNumpres" : oCheckbox.value,
          "sValores" : ((!oHidden) ? ' ' : oHidden.value),
          "lChecked" : oCheckbox.checked
        }

        this.oParameters[iCheckBox] = aDebito;

        iCheckBox++;
        iValores++;
      }
    }

    return this;
  }

  this.submit = function(){

    new AjaxRequest(this.sRpc, this.oParameters, function(oRetorno, lErro){

      if(lErro) {

        alert(oRetorno.sMessage.urlDecode());
        return false;
      }

      var sAction = '';
      var iLenght = 0;
      var iMax    = Object.keys(this.oFormParameters).length;

      // Forma string do action do form
      for(var sIndex in this.oFormParameters){

        sAction += sIndex + '=' + this.oFormParameters[sIndex];

        if(iLenght < iMax){
          sAction += '&';
        }
        iLenght++;
      }

      // Remove todos os elementos da table contidos dentro do form
      this.oTable.innerHTML = '';

      // Adiciona propriedades e submeter form
      this.oForm.action     = this.sFormPathFile + '?' + sAction;
      this.oForm.target     = this.sFormTarget;
      this.oForm.submit();

    }.bind(this)).setMessage(this.sMensagem).execute();

    return this;
  }
}

</script>
