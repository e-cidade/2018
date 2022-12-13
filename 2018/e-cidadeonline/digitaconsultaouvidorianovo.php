<?php
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

session_start();
if (isset($outro)) {
	
 setcookie("cookie_codigo_cgm");
 echo"<script>location.href='digitaconsultaouvidorianovo.php'</script>";
}

require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("classes/db_protprocesso_classe.php");

$lRetornoAutomatico = "";
if (isset($_GET['lRetornoAutomatico'])) {
  $lRetornoAutomatico = $_GET['lRetornoAutomatico'];
}

db_logs("","",0,"Digita Consulta da Ouvidoria.");
mens_help();
db_mensagem("ouvidoria_cab", "ouvidoria_rod");
$db_verificaip = db_verifica_ip();
?>
<html>  
<head>
  <title><?php echo $w01_titulo ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <link href="config/estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" src="scripts/db_script.js"></script>
  <script language="JavaScript" src="scripts/strings.js"></script>
  <script language="JavaScript" src="scripts/scripts.js"></script>
  <script language="JavaScript" src="scripts/prototype.js"></script>
  <script language="JavaScript" src="scripts/widgets/datagrid.widget.js"></script>
  
  <style type="text/css">
    <?php db_estilosite();?>
  </style>
  
  <link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>

<body style="height:100%;">
  <form onsubmit="return js_pesquisar();" name="formProcessos" id="formProcessos">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
    <tr>
      <td height="60" align="<?php echo $DB_align1;?>">
        <?php echo $DB_mens1;?>
      </td>
    </tr>
  </table>
  <center>
    <fieldset style="width: 300px;">
    
      <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" class = "texto">
        <legend>Consulta Ouvidoria</legend>
        <tr>
          <td width = "50%" height = "30" align = "right">
            Número do Atendimento:
          </td>
          
          <td width = "50%" height = "30">
            <input type  = "text" 
                   id    = "iAtendimento"
                   name  = "iAtendimento" 
                   class = "digitacgccpf" 
                   size  = "10" 
                   maxlength = "10"
                   onchange = "js_validaNumeroAtendimento()"/>
          </td>
        </tr>
        
        <tr>
          <td width = "50%" height = "30" align = "right">
            Número Processo:
          </td>
          
          <td width = "50%" height = "30">
            <input type  = "text" 
                   id    = "iProcesso"
                   name  = "iProcesso" 
                   class = "digitacgccpf" 
                   size  = "10" 
                   maxlength = "10"
                   />
          </td>
        </tr>
        
        
      
        <tr>
          <td width = "50%" height = "30" align = "right">
            CNPJ:
          </td>
          
          <td width = "50%" height = "30">
            <input type = "text"
                   name = "iCnpj" 
                   id = "iCnpj" 
                   class = "digitacgccpf"
                   onChange='js_teclas(event);'
                   onKeyPress="FormataCNPJ(this,event); return js_teclas(event);" 
                   size = "18"
                   maxlength = "19"/>
          </td>
        </tr>
        
        <tr>
          <td width = "50%" height = "30" align = "right">
            CPF:
          </td>
          
          <td width = "50%" height = "30">
            <input name = "iCpf" 
                  type = "text"
                  class = "digitacgccpf"
                  id = "iCpf" 
                  size = "15" 
                  maxlength = "14"
                  onChange='js_teclas(event);'
                  onKeyPress="FormataCPF(this,event); return js_teclas(event);"
                  />
          </td>
        </tr>
      </table>
    </fieldset>
    <table>
      <tr>
        <td width = "50%" height = "30">&nbsp;</td>
        <td width = "50%" height = "30">
          <input class   = "botao" 
             type    = "button"
             name    = "pesquisa" 
             value   = "Pesquisa" 
             class   = "botaoconfirma"
             onclick = "js_pesquisar();"
               />
        </td>
      </tr>
    </table>
  </center>
  
  <center>
    <fieldset style="width:98%" id='fieldsetGrid'>
    <legend class="title"><b>Atendimentos Encontrados</b></legend>
      <div id="listaResultados" style="width: 100%;"></div>
      <div style='text-align:left;'><b>* Clique sob o número do atendimento para visualizar a consulta</b></div>
    </fieldset>
  </center>
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
    <tr>
      <td height="60" align="<?=$DB_align2?>">
         <?php echo $DB_mens2?>
       </td>
     </tr>
   </table>
   </form>
</body>
</html>
<script>

var iAnoAtual = <?php echo date('Y');?>;
var sUrlRpc = "ouvidoriaDbpref.RPC.php";
var lPesquisaAutomatica = '<?php echo $lRetornoAutomatico; ?>';

var oDBGridListaResultados = new DBGrid('gridDBGridListaResultados');
oDBGridListaResultados.nameInstance = 'oDBGridListaResultados';
oDBGridListaResultados.setHeader(new Array('Nº Atendimento',
                                           'Nº Processo',
                                           'Processo',
                                           'CPF/CNPJ',
                                           'Requerente',
                                           'Depto Atual',
                                           'Data de Criação'));
oDBGridListaResultados.setCellWidth(new Array('10%', 
                                           '10%',
                                           '20%', 
                                           '10%', 
                                           '20%', 
                                           '20%',
                                           '10%'));
oDBGridListaResultados.setCellAlign(new Array('center',
                                              'left',
                                              'left',
                                              'left',
                                              'left',
                                              'left',
                                              'right'));
oDBGridListaResultados.show($('listaResultados'));

$('tablegridDBGridListaResultadosheader').style.fontSize = "12px";
$('tablegridDBGridListaResultadosfooter').style.fontSize = "12px";

function js_pesquisar() {


  var iCPF         = $F('iCpf');
  var iCNPJ        = $F('iCnpj');
  var iProcesso    = $F('iProcesso');
  var iAtendimento = $F('iAtendimento');


  if (iCPF == "" && iCNPJ == "" && iProcesso == "" && iAtendimento == "") {
    alert("Informe pelo menos um filtro.");
    return false;
  }

  lPesquisaAutomatica = "";
  js_pesquisaDados();
}

function js_pesquisaDados() {

  var iCPF         = $F('iCpf');
  var iCNPJ        = $F('iCnpj');
  var iProcesso    = $F('iProcesso');
  var iAtendimento = $F('iAtendimento');
	var oParam          = new Object();
	
  oParam.exec               = 'buscaDados';
  oParam.iCPF               =  iCPF;
  oParam.iCNPJ              =  iCNPJ;
  oParam.iProcesso          =  iProcesso;
  oParam.iAtendimento       =  iAtendimento;
  oParam.lRetornoAutomatico = lPesquisaAutomatica;

  js_divCarregando("Aguarde, carregando dados...", "msgBox");
  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
		                           parameters:'json='+Object.toJSON(oParam),
		                           onComplete: js_finalizaBuscaDados});
}

function js_finalizaBuscaDados(oAjax){

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
	
  if(oRetorno.status == 2){
    alert(oRetorno.message.urlDecode());
    return false;
  }

  oDBGridListaResultados.clearAll(true);
  
  var aResultados = oRetorno.aResultados;
  var iNumRows = aResultados.length;
  
  if (iNumRows > 0) {
    
    aResultados.each(
      function (oResultados) {

        var fc_onClick = 'js_detalhesAtendimento('+oResultados.iSeqAtendimento+')';
        var aRow    = new Array();
            aRow[0] = '<a href="#" onclick="'+fc_onClick+'">'+oResultados.iAtendimento+'</a>';
            aRow[1] = "&nbsp;" + oResultados.iProtocolo +"&nbsp;";
            aRow[2] = "&nbsp;" + oResultados.sDescricao.urlDecode() + "&nbsp;";
            aRow[3] = "&nbsp;" +oResultados.iCpfCnpj.urlDecode() + "&nbsp;";
            aRow[4] = "&nbsp;" +oResultados.sRequerente.urlDecode() + "&nbsp;";
            aRow[5] = "&nbsp;" +oResultados.sDepartamento.urlDecode() + "&nbsp;";
            aRow[6] = "&nbsp;" +js_formatar(oResultados.dtDataAtendimento, 'd');
        
        oDBGridListaResultados.addRow(aRow);
      }
    );
  }
  oDBGridListaResultados.renderRows();
}

function js_detalhesAtendimento(iSeqAtendimento) {

  var sUrl = 'consulta_atendimento_ouvidoria.php?iAtendimento='+iSeqAtendimento;
  location.href = sUrl;
}

function js_validaNumeroAtendimento() {

	var sNovoValor         = $('iAtendimento').value;

	if (sNovoValor != "") {
		
  	var aNumeroAtendimento = $('iAtendimento').value.split('/');
  	if (aNumeroAtendimento.length == 1) {
      sNovoValor = $('iAtendimento').value+"/"+iAnoAtual;
  	}
  	$('iAtendimento').value = sNovoValor;
	}
}

function js_pesquisaAutomatica() {

	if (lPesquisaAutomatica != "") {
		js_pesquisaDados();
	}
}
js_pesquisaAutomatica();
$("fieldsetGrid").style.width = '99%';
</script>