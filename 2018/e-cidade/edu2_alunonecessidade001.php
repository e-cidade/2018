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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

db_postmemory($_POST);

$iModulo        = db_getsession("DB_modulo");
$iDepartamento  = db_getsession("DB_coddepto");
$sNomeEscola    = db_getsession("DB_nomedepto");


?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
   <form name="form1" method="post" action="" class="container">
     <fieldset ><legend><b>Relatório de Alunos com Necessidades Especiais  </b></legend>
       <table class='form-container'>
         <tr>
           <td class='bold field-size2'>Escola:</td>
           <td colspan="5">
             <select id='escola' style="width: 100%;" onchange='js_buscaAno();' >
               <option value='0' selected="selected">Todas</option>
             </select>
           </td>
         </tr>
         <tr>
           <td class='bold field-size2'>Ano:</td>
           <td colspan="5">
             <select id='ano' style="width: 100%;" onchange='js_buscaEtapa();' >
               <option value='' selected="selected">Selecione o ano</option>
             </select>
           </td>
         </tr>
         <tr>
           <td class='bold field-size2'>Etapa:</td>
           <td colspan="5">
             <select id='etapa' style="width: 100%;" disabled='disabled'>
               <option value='' selected="selected">Selecione uma etapa</option>
             </select>
           </td>
         </tr>
       </table>
      </fieldset>
    <input type="button" name="imprimir" id="imprimir" value="Imprimir" onclick="js_imprime()" disabled='disabled' />
  </form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"), db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
 </body>
</html>
<script>

var sRPC          = 'edu_educacaobase.RPC.php';
var iModulo       = <?php echo $iModulo;?> 
var iDepartamento = <?php echo $iDepartamento;?>

var oOptionSelecioneEtapa       = document.createElement('option');
oOptionSelecioneEtapa.value     = "";
oOptionSelecioneEtapa.innerHTML = "Selecione uma etapa";

var oOptionSelecioneAno       = document.createElement('option');
oOptionSelecioneAno.value     = "";
oOptionSelecioneAno.innerHTML = "Selecione o ano";

var oOptionTodos       = document.createElement('option');
oOptionTodos.value     = "";
oOptionTodos.innerHTML = "Todos";

/**
 * busca as escolas da rede ou a escola logada se modulo = escola
 */
function js_buscaEscola() {

  var oParamentro           = new Object();
  oParamentro.exec          = 'pesquisaEscola';
  oParamentro.lTodasEscolas = true; 
  if (iModulo == 1100747) {
    oParamentro.lTodasEscolas = false;
  }

  js_divCarregando("Aguarde, buscando escolas...", "msgBox");
  new Ajax.Request(sRPC,
                   {method:     'post',
                    parameters: 'json='+Object.toJSON(oParamentro),
                    onComplete: js_retornoEscola 
                   } 
                  );
}

function js_retornoEscola(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  var iEscola  = null;
  
  oRetorno.dados.each(function (oEscola) {

    var oOption       = document.createElement('option');
    oOption.value     = oEscola.codigo_escola;
    oOption.innerHTML = oEscola.nome_escola.urlDecode();
    
    $('escola').appendChild(oOption);
    iEscola = oEscola.codigo_escola;
  });

  if (oRetorno.dados.length == 1) {
    $('escola').value = iEscola;
  }
}

/**
 * Busca os anos dos calendários da escola selecionada ou de todos calendários
 */
function js_buscaAno() {

  $('imprimir').setAttribute('disabled', 'disabled');

  var oParamentro     = new Object();
  oParamentro.exec    = 'pesquisaAnoLetivoEscola';
  oParamentro.iEscola = $F('escola');

  if (iModulo == 1100747) {
    oParamentro.iEscola = iDepartamento;
  }

  $('ano').options.length = 0;
  $('ano').appendChild(oOptionSelecioneAno);
  $('etapa').options.length = 0;
  $('etapa').appendChild(oOptionSelecioneEtapa);
  $('etapa').setAttribute("disabled","disabled");
  
  js_divCarregando("Aguarde, buscando ano...", "msgBox");
  new Ajax.Request(sRPC,
                   {method:     'post',
                    parameters: 'json='+Object.toJSON(oParamentro),
                    onComplete: js_retornoAno 
                   } 
                  );
}

function js_retornoAno(oAjax) {

  if ($('msgBox')) {
    js_removeObj('msgBox');
  }
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
  
  oRetorno.aAno.each(function (oAno) {

    var oOption       = document.createElement('option');
    oOption.value     = oAno.ed52_i_ano;
    oOption.innerHTML = oAno.ed52_i_ano;
    $('ano').appendChild(oOption);
  });
}

/**
 * Busca as etapas do ano e escola selecionada ou todas as etapas de um ano selecionado
 */
function js_buscaEtapa() {

  if ($F('ano') == '') {

    $('etapa').options.length = 0;
    $('etapa').appendChild(oOptionSelecioneEtapa);
    $('etapa').setAttribute("disabled","disabled");
    $('imprimir').setAttribute("disabled","disabled");
    return false;
  }
  
  var oParamentro     = new Object();
  oParamentro.exec    = 'pesquisaEtapaAno';
  oParamentro.iEscola = $F('escola');
  oParamentro.iAno    = $F('ano');


  js_divCarregando("Aguarde, buscando cursos...", "msgBox");
  new Ajax.Request(sRPC,
                   {method:     'post',
                    parameters: 'json='+Object.toJSON(oParamentro),
                    onComplete: js_retornoEtapa 
                   } 
                  );
}

function js_retornoEtapa(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  $('etapa').options.length = 0;
  
  if (oRetorno.status == 2) {

    $('etapa').appendChild(oOptionSelecioneEtapa);
    $('etapa').setAttribute("disabled","disabled");
    $('imprimir').setAttribute("disabled","disabled");
    alert(oRetorno.message.urlDecode());
    
    return false;
  }

  $('etapa').appendChild(oOptionTodos);
  if (oRetorno.aEtapaAno.length > 0) {
    
    $('etapa').removeAttribute('disabled');
    $('imprimir').removeAttribute('disabled');
  }
  
  oRetorno.aEtapaAno.each(function (oEtapaAno) {

    var oOption       = document.createElement('option');
    oOption.value     = oEtapaAno.ed11_i_codigo;  
    oOption.innerHTML = oEtapaAno.ed11_c_descr.urlDecode();
    $('etapa').appendChild(oOption);
  });
}

js_buscaEscola();
js_buscaAno();

/**
 * Imprime 
 */
function js_imprime() {

  var sUrl  = 'edu2_alunonecessidade002.php?';
      sUrl += 'iEscola='+$F('escola');
      sUrl += '&iAno='+$F('ano');
      sUrl += '&iSerie='+$F('etapa');
  jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}


</script>