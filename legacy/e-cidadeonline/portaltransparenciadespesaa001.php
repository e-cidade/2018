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
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("classes/db_issbase_classe.php");
$sSqlAnos  = "select distinct o58_anousu";
$sSqlAnos .= "  from orcdotacao"; 
$sSqlAnos .= " where o58_instit = ".db_getsession("DB_instit"); 
$sSqlAnos .= " order by o58_anousu"; 
$rsAnos    = db_query($sSqlAnos);
$aAnos     = db_utils::getCollectionByRecord($rsAnos);
$aMes      = array(1  => 'Janeiro',
                   2  => 'Fevereiro',
                   3  => 'Março',
                   4  => 'Abril',
                   5  => 'Maio',
                   6  => 'Junho',
                   7  => 'Julho',
                   8  => 'Agosto',
                   9  => 'Setembro',
                   10 => 'Outubro',
                   11 => 'Novembro',
                   12 => 'Dezembro'
                  );

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script language="JavaScript" src="scripts/scripts.js"></script>
<script language="JavaScript" src="scripts/prototype.js"></script>
<script language="JavaScript" src="scripts/strings.js"></script>
<script>
</script>
<style type="text/css">
<?db_estilosite();?>
</style>
</head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" 
        bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
    <?mens_div();?>
    <br>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Opções Disponíveis</b>
            </legend>
            <table>
              <tr>
                <td><b>Anos Disponíveis:</b></td>
                <td>
                  <select name='anosreceita' id='anosreceita' style='width:100px'>
                    <option value=''>Selecione um ano</option>
                    <?
                    foreach ($aAnos as $oAno) {
                      echo "  <option value='{$oAno->o58_anousu}'>{$oAno->o58_anousu}</option>\n";            
                    }
                    ?>
                  </select>
                </td>
                <td><b>Mês:</b></td>
                <td>
                  <select name='mesreceita' id='mesreceita' style='width:100px'>
                    <option value=''>Selecione um Mês</option>
                    <?
                    foreach ($aMes as $iMes => $sDescricao) {
                     echo "  <option value='{$iMes}'>{$sDescricao}</option>\n";            
                    }
                    ?>
                  </select>
                </td>
                <td><b>Orgão:</b></td>
                <td>
                  <select name='orgao' id='orgao' style='width:300px'>
                    <option value=''>Todos...</option>
                  </select>
                </td>
                <td>
                  <input type='button' value='Pesquisar' id='pesquisasrreceitas'>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>       
    </table>
    <table width="100%">
      <tr>
        <td>
          <fieldset style="">
            <legend>
              <b>Despesas Executadas</b>
            </legend>
            <div id='table'>
            </div>
          </fieldset>
        </td>
      </tr>
    </table>
  </body>
</html>  
<script>
function Browser() {

  var ua, s, i;

  this.isIE    = false;  // Internet Explorer
  this.isNS    = false;  // Netscape
  this.version = null;
  this.name    = null;
  this.system  = null;

  ua = navigator.userAgent;

  s = "MSIE";
  if ((i = ua.indexOf(s)) >= 0) {
    this.system = 'Windows';
  }
  s = "Linux";
  if ((i = ua.indexOf(s)) >= 0) {
    this.system = 'Linux';
  }

  s = "MSIE";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isIE = true;
    this.name = 'Internet Explorer';
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  s = "Netscape/";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.name = 'Netscape';
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  // Treat any other "Gecko" browser as NS 6.1.

  s = "Gecko";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.name = 'Netscape';
    this.version = 6.1;
    return;
  }
}

var browser = new Browser();
Number.prototype.toFixed = function(casas){
  if(typeof(casas) =='undefined'){
    casas = 0;    
  }
  var valor    = new Number( this );
  var base     = new Number( Math.pow(10,casas) );
  var valorArr = (Math.round(valor * base) / base );
  return valorArr;
};
function getReceitasByPeriodo() {
  
  var iMes = $F('mesreceita');
  var iAno = $F('anosreceita');
  
  if (iMes == '') {
  
    alert('informe um mes.');
    return false;
  }
  if (iAno == '') {
  
    alert('informe um Ano.');
    return false;
  }
  
  var oParam    =  new Object();
  oParam.exec   = 'getDespesasByPeriodo';
  oParam.iMes   = iMes;
  oParam.iAno   = iAno;
  oParam.iOrgao = $F('orgao');
  js_divCarregando('Carregando...', 'msgbox');
  var oAjax = new Ajax.Request('portaltransparencia.RPC.php',
                               {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoReceitas
                               }
                              ); 
}

function js_retornoReceitas(oResponse) {
   
  var sBorder = 'separate'; 
  if (browser.isIE) {
   sBorder = 'collapse';
  }
  var nTotalLiquidado      = new Number(0);
  var nTotalEmpenhado      = new Number(0);
  var nTotalAnulado        = new Number(0);
  var nTotalPago           = new Number(0);
  js_removeObj('msgbox');
  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.itens.length > 0) {
    
    var sCorpoDespesa  = "<table id='tabeladespesa' border='0' width='100%' class='lov' cellspacing='0' cellpadding='0' style='border-collapse:"+sBorder+"'>";
    sCorpoDespesa     += "<thead style='background-color:#eeeee2;color:black'>";
    sCorpoDespesa     += "  <tr> ";
    sCorpoDespesa     += "    <th width='10%' style=';color:black'>";
    sCorpoDespesa     += "      <b>Reduz</b>";
    sCorpoDespesa     += "     </th>";
    sCorpoDespesa     += "     <th  style=';color:black'>";
    sCorpoDespesa     += "       <b>Descrição</b>";
    sCorpoDespesa     += "     </th>";
    sCorpoDespesa     += "     <th  style=';color:black'>";
    sCorpoDespesa     += "       <b>Empenhado</b>";
    sCorpoDespesa     += "     </th>";
    sCorpoDespesa     += "     <th  style=';color:black'>";
    sCorpoDespesa     += "       <b>Liquidado</b>";
    sCorpoDespesa     += "     </th> ";
    sCorpoDespesa     += "       <th  style=';color:black'>";
    sCorpoDespesa     += "         <b>Pago</b>";
    sCorpoDespesa     += "       </th>";
    sCorpoDespesa     += "       <th width='17px'>&nbsp;";
    sCorpoDespesa     += "       </th>";
    sCorpoDespesa     += "    </tr>";
    sCorpoDespesa     += "</thead>";
    if (browser.isIE) {
      sCorpoDespesa     += " <tbody id='tbDespesa' style='background-color: white;color:black'> ";
    } else {
      sCorpoDespesa     += " <tbody id='tbDespesa' style='height:300px;max-height:300px; background-color: white;overflow:auto;overflow-x:hidden '> ";
    }
    var iCodigoOrgaoAnterior = 0;
    for (var i = 0; i < oRetorno.itens.length; i++) {
      
      with (oRetorno.itens[i]) {
        
        if (empenhado == 0 && liquidado == 0 && pago == 0) {
          continue;
        }
        var sBold       = 'normal';
        if (iCodigoOrgaoAnterior != 0 && iCodigoOrgaoAnterior == o58_orgao) {
        
          
          if (iUnidade != o58_unidade && o58_unidade != 0) {
          
            sCorpoDespesa += "<tr style='height:1em;'>";
            sCorpoDespesa += " <td>";
            sCorpoDespesa +=  o58_orgao+"."+o58_unidade
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td colspan='4'>";
            sCorpoDespesa +=   o41_descr.urlDecode();
            sCorpoDespesa += " </td>";  
            sCorpoDespesa += "<tr>";
          }
          
          if (iFuncao != o58_funcao && o58_funcao != 0) {
          
            sCorpoDespesa += "<tr style='height:1em;'>";
            sCorpoDespesa += " <td>";
            sCorpoDespesa +=  o58_orgao+"."+o58_unidade+"."+o58_funcao
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td colspan='4'>";
            sCorpoDespesa +=   o52_descr.urlDecode();
            sCorpoDespesa += " </td>";  
            sCorpoDespesa += "<tr>";
          }
          if (iSubFuncao != o58_subfuncao && o58_subfuncao != 0) {
          
            sCorpoDespesa += "<tr style='height:1em;'>";
            sCorpoDespesa += " <td>";
            sCorpoDespesa +=  o58_orgao+"."+o58_unidade+"."+o58_funcao+"."+o58_subfuncao;
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td colspan='4'>";
            sCorpoDespesa +=   o53_descr.urlDecode();
            sCorpoDespesa += " </td>";
            sCorpoDespesa += "<tr>";
          }
          if (iPrograma != o58_programa && o58_programa != 0) {
          
            sCorpoDespesa += "<tr style='height:1em;'>";
            sCorpoDespesa += " <td>";
            sCorpoDespesa +=  o58_orgao+"."+o58_unidade+"."+o58_funcao+"."+o58_subfuncao+"."+o58_programa;
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td colspan='4'>";
            sCorpoDespesa +=   o54_descr.urlDecode();
            sCorpoDespesa += " </td>";  
            sCorpoDespesa += "<tr>";
          }
          if (iProjativ != o58_projativ && o58_projativ != 0) {
          
            sCorpoDespesa += "<tr style='height:1em;'>";
            sCorpoDespesa += " <td>";
            sCorpoDespesa +=  o58_orgao+"."+o58_unidade+"."+o58_funcao+"."+o58_subfuncao+"."+o58_programa+"."+o58_projativ;
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td colspan='4'  >";
            sCorpoDespesa +=   o55_descr.urlDecode();
            sCorpoDespesa += " </td>";  
            sCorpoDespesa += "<tr>";
          }
          if (iElemento != o58_elemento && o58_elemento != 0) {
          
            sCorpoDespesa += "<tr style='font-weight:bold;height:1em;'>";
            sCorpoDespesa += " <td>";
            sCorpoDespesa +=  o58_elemento
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td>";
            sCorpoDespesa +=   o56_descr.urlDecode();
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td style='text-align:right'>";
            sCorpoDespesa +=   js_formatar((empenhado -anulado),  'f');
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td style='text-align:right'>";
            sCorpoDespesa +=   js_formatar(liquidado, 'f');
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td style='text-align:right'>";
            sCorpoDespesa +=    js_formatar(pago, 'f');
            sCorpoDespesa += " </td>";
            sCorpoDespesa += "<tr>";
            
          } 
          if (o58_codigo != 0) {
          
            sCorpoDespesa += "<tr style='height:1em;'>";
            sCorpoDespesa += " <td style='text-align:right'>";
            sCorpoDespesa +=    o58_coddot
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td>";
            sCorpoDespesa +=   o15_descr.urlDecode();
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td style='text-align:right'>";
            sCorpoDespesa +=   js_formatar((empenhado - anulado),  'f');
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td style='text-align:right'>";
            sCorpoDespesa +=   js_formatar(liquidado, 'f');
            sCorpoDespesa += " </td>";
            sCorpoDespesa += " <td style='text-align:right'>";
            sCorpoDespesa +=    js_formatar(pago, 'f');
            sCorpoDespesa += " </td>";
            sCorpoDespesa += "<tr>";
          }
          iUnidade      = o58_unidade;
          iFuncao       = o58_funcao;
          iSubFuncao    = o58_subfuncao;
          iPrograma     = o58_programa;
          iProjativ     = o58_projativ;
          iElemento     = o58_elemento
        } else {
          
          var iUnidade    = '';
          var iFuncao     = '';
          var iSubFuncao  = '';
          var iPrograma   = '';
          var iProjativ   = '';
          var iElemento   = '';
          sCorpoDespesa += "<tr style='height:1em; font-weight:bold;background-color:#eeeee2'>";
          sCorpoDespesa += " <td>";
          sCorpoDespesa +=  o58_orgao
          sCorpoDespesa += " </td>";
          sCorpoDespesa += " <td >";
          sCorpoDespesa +=   o40_descr.urlDecode();
          sCorpoDespesa += " </td>";  
          sCorpoDespesa += " <td style='text-align:right'>";
          sCorpoDespesa +=   js_formatar((empenhado -anulado),  'f');
          sCorpoDespesa += " </td>";
          sCorpoDespesa += " <td style='text-align:right'>";
          sCorpoDespesa +=   js_formatar(liquidado, 'f');
          sCorpoDespesa += " </td>";
          sCorpoDespesa += " <td style='text-align:right'>";
          sCorpoDespesa +=    js_formatar(pago, 'f');
          sCorpoDespesa += " </td>";
          sCorpoDespesa   += "<tr>";
          nTotalEmpenhado += (new Number(empenhado) - new Number(anulado)); 
          nTotalLiquidado += new Number(liquidado); 
          nTotalPago      += new Number(pago); 
        }
        iCodigoOrgaoAnterior = o58_orgao;  
      }
    }
    
    if (sCorpoDespesa == '') {
    
      sCorpoDespesa  = "<tr><td colspan='5' style='text-align:center'>";
      sCorpoDespesa += "<b>Não existe movimentação no período informado.</b></td></tr>";       
    } else {
      sCorpoDespesa += '<tr style="height:auto"><td>&nbsp;</td></tr>';
    }
    
  } else {
  
    sCorpoDespesa  = "<tr><td colspan='5' style='text-align:center'>";
    sCorpoDespesa += "<b>Não existe movimentação no período informado.</b></td></tr>";
  }
  
  sCorpoDespesa     += " </tbody>";
  sCorpoDespesa     += " <tfoot style='background-color: #eeeee2;color:black'>";
  sCorpoDespesa     += "  <tr>";
  sCorpoDespesa     += "    <th colspan='2' style='text-align: right;color:black'>";
  sCorpoDespesa     += "     <b>Total</b>";
  sCorpoDespesa     += "     </th>";
  sCorpoDespesa     += "     <th style='text-align:right;color:black'>";
  sCorpoDespesa     += "     <span id='totalempenhado'></span>";
  sCorpoDespesa     += "      </th> ";
  sCorpoDespesa     += "     <th style='text-align:right;color:black'>";
  sCorpoDespesa     += "        <span id='totalliquidado'></span>";
  sCorpoDespesa     += "      </th>";
  sCorpoDespesa     += "      <th style='text-align:right;color:black'>";
  sCorpoDespesa     += "        <span id='totalpago'></span>";
  sCorpoDespesa     += "      </th>";
  sCorpoDespesa     += "      <th width='17px'>&nbsp;";
  sCorpoDespesa     += "      </th>";
  sCorpoDespesa     += "    </tr>";
  sCorpoDespesa     += "   </tfoot>";
  sCorpoDespesa     += " </table> ";
  $('table').innerHTML = sCorpoDespesa;
  $('totalempenhado').innerHTML = js_formatar(nTotalEmpenhado, 'f');
  $('totalliquidado').innerHTML = js_formatar(nTotalLiquidado, 'f');
  $('totalpago').innerHTML      = js_formatar(nTotalPago, 'f');
  $('tbDespesa').style.height='300px';
  if (browser.isIE) {
    var t = new ScrollableTable(document.getElementById('tabeladespesa'), 300);
  }
  
}

function getOrgaosByAno() {
   
   
  var iAno    = $F('anosreceita');
  var oParam  = new Object();
  oParam.exec = 'getOrgaosByAno';
  oParam.iAno = iAno;
  var oAjax = new Ajax.Request('portaltransparencia.RPC.php',
                               {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoOrgaos
                               }
                              ); 
  
}

function js_retornoOrgaos(oResponse) {

  
  var oRetorno = eval("("+oResponse.responseText+")");
  $('orgao').options.length = 1;
  if (oRetorno.itens.length > 0) {
    for (var i = 0; i < oRetorno.itens.length ; i++) {
      
      with (oRetorno.itens[i]) {
        
        var oOption = new Option(o40_descr.urlDecode(), o40_orgao);
        
        if (browser.isIE) {
          $('orgao').add(oOption, (i+1));
        } else {
          $('orgao').add(oOption, null);
        }
      }
    }
  }
}
$('pesquisasrreceitas').observe("click", getReceitasByPeriodo);
$('anosreceita').observe("change", getOrgaosByAno);

/**
*
*  Scrollable HTML table
*  http://www.webtoolkit.info/
*
**/
 
function ScrollableTable (tableEl, tableHeight, tableWidth) {
 
  this.initIEengine = function () {
 
    this.containerEl.style.overflowY = 'auto';
    if (this.tableEl.parentElement.clientHeight - this.tableEl.offsetHeight < 0) {
      this.tableEl.style.width = this.newWidth - this.scrollWidth +'px';
    } else {
      this.containerEl.style.overflowY = 'hidden';
      this.tableEl.style.width = this.newWidth +'px';
    }
 
    if (this.thead) {
      var trs = this.thead.getElementsByTagName('tr');
      for (x=0; x<trs.length; x++) {
        trs[x].style.position ='relative';
        trs[x].style.setExpression("top",  "this.parentElement.parentElement.parentElement.scrollTop + 'px'");
      }
    }
 
    if (this.tfoot) {
      var trs = this.tfoot.getElementsByTagName('tr');
      for (x=0; x<trs.length; x++) {
        trs[x].style.position ='relative';
        trs[x].style.setExpression("bottom",  "(this.parentElement.parentElement.offsetHeight - this.parentElement.parentElement.parentElement.clientHeight - this.parentElement.parentElement.parentElement.scrollTop) + 'px'");
      }
    }
 
    eval("window.attachEvent('onresize', function () { document.getElementById('" + this.tableEl.id + "').style.visibility = 'hidden'; document.getElementById('" + this.tableEl.id + "').style.visibility = 'visible'; } )");
  };
 
 
  this.initFFengine = function () {
    this.containerEl.style.overflow = 'hidden';
    this.tableEl.style.width = this.newWidth + 'px';
 
    var headHeight = (this.thead) ? this.thead.clientHeight : 0;
    var footHeight = (this.tfoot) ? this.tfoot.clientHeight : 0;
    var bodyHeight = this.tbody.clientHeight;
    var trs = this.tbody.getElementsByTagName('tr');
    if (bodyHeight >= (this.newHeight - (headHeight + footHeight))) {
      this.tbody.style.overflow = '-moz-scrollbars-vertical';
      for (x=0; x<trs.length; x++) {
        var tds = trs[x].getElementsByTagName('td');
        tds[tds.length-1].style.paddingRight += this.scrollWidth + 'px';
      }
    } else {
      this.tbody.style.overflow = '-moz-scrollbars-none';
    }
 
    var cellSpacing = (this.tableEl.offsetHeight - (this.tbody.clientHeight + headHeight + footHeight)) / 4;
    this.tbody.style.height = (this.newHeight - (headHeight + cellSpacing * 2) - (footHeight + cellSpacing * 2)) + 'px';
 
  };
 
  this.tableEl = tableEl;
  this.scrollWidth = 16;
 
  this.originalHeight = this.tableEl.clientHeight;
  this.originalWidth = this.tableEl.clientWidth;
 
  this.newHeight = parseInt(tableHeight);
  this.newWidth = tableWidth ? parseInt(tableWidth) : this.originalWidth;
 
  this.tableEl.style.height = 'auto';
  this.tableEl.removeAttribute('height');
 
  this.containerEl = this.tableEl.parentNode.insertBefore(document.createElement('div'), this.tableEl);
  this.containerEl.appendChild(this.tableEl);
  this.containerEl.style.height = this.newHeight + 'px';
  this.containerEl.style.width = this.newWidth + 'px';
 
 
  var thead = this.tableEl.getElementsByTagName('thead');
  this.thead = (thead[0]) ? thead[0] : null;
 
  var tfoot = this.tableEl.getElementsByTagName('tfoot');
  this.tfoot = (tfoot[0]) ? tfoot[0] : null;
 
  var tbody = this.tableEl.getElementsByTagName('tbody');
  this.tbody = (tbody[0]) ? tbody[0] : null;
 
  if (!this.tbody) return;
 
  if (document.all && document.getElementById && !window.opera) this.initIEengine();
  if (!document.all && document.getElementById && !window.opera) this.initFFengine();
 
 
}
</script>