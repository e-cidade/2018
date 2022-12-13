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
$sSqlAnos  = "select distinct o70_anousu";
$sSqlAnos .= "  from orcreceita "; 
$sSqlAnos .= " where o70_instit = ".db_getsession("DB_instit"); 
$sSqlAnos .= " order by o70_anousu"; 
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
                      echo "  <option value='{$oAno->o70_anousu}'>{$oAno->o70_anousu}</option>\n";            
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
              <b>Receitas Arrecadadas</b>
            </legend>
             <div id='tablereceitas'>
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
  js_divCarregando('Carregando..', 'msgbox');
  var oParam  = new Object();
  oParam.exec = 'getReceitasByPeriodo';
  oParam.iMes = iMes;
  oParam.iAno = iAno;
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
  js_removeObj('msgbox');
  var oRetorno = eval("("+oResponse.responseText+")");
  $('tablereceitas').innerHTML = '';
  var sTotalPrevisto          = new Number(0);
  var sTotalPrevistoAdicional = new Number(0);
  var sTotalArrecadado        = new Number(0);
  var sTotalArrecadadoAno     = new Number(0);
  if (oRetorno.itens.length > 0) {
    
    var sCorpoReceitas  = "<table id='tabelareceita' border='0' width='100%' class='lov' cellspacing='0' cellpadding='0' style='border-collapse: separate'>";
    sCorpoReceitas     += "   <thead style='background-color:#eeeee2;color:black'>";
    sCorpoReceitas     += "   <tr>";
    sCorpoReceitas     += "     <th  style=';color:black'>";
    sCorpoReceitas     += "       <b>Receita</b>";
    sCorpoReceitas     += "     </th>";
    sCorpoReceitas     += "     <th  style=';color:black'>";
    sCorpoReceitas     += "        <b>Descrição</b>";
    sCorpoReceitas     += "      </th>";
    sCorpoReceitas     += "      <th  style=';color:black'>";
    sCorpoReceitas     += "        <b>Recurso</b>";
    sCorpoReceitas     += "      </th>";
    sCorpoReceitas     += "      <th  style=';color:black'>";
    sCorpoReceitas     += "        <b>Valor Previsto</b>";
    sCorpoReceitas     += "      </th> ";
    sCorpoReceitas     += "      <th  style=';color:black'>";
    sCorpoReceitas     += "        <b>Valor Prev. Adicional</b>";
    sCorpoReceitas     += "      </th>";
    sCorpoReceitas     += "      <th  style=';color:black'>";
    sCorpoReceitas     += "        <b>Valor Arrecadado no Mês</b>";
    sCorpoReceitas     += "      </th> ";
    sCorpoReceitas     += "      <th  style=';color:black'>";
    sCorpoReceitas     += "        <b>Valor Arrecado no Ano</b>";
    sCorpoReceitas     += "      </th>";
    sCorpoReceitas     += "        <th width='17px'>&nbsp;";
    sCorpoReceitas     += "      </th>";
    sCorpoReceitas     += "      </tr>";
    sCorpoReceitas     += "   </thead>";
     if (browser.isIE) {
      sCorpoReceitas += " <tbody id='tbDespesa' style='background-color: white;color:black'> ";
    } else {
      sCorpoReceitas += " <tbody id='tbDespesa' style='height:300px;max-height:300px; background-color: white;overflow:auto;overflow-x:hidden '> ";
    }
    sCorpoReceitas     += "      <tr>";
    sCorpoReceitas     += "        <td></td>";
    sCorpoReceitas     += "      </tr>";
    for (var i = 0; i < oRetorno.itens.length; i++) {
    
      with (oRetorno.itens[i]) {
        
        if (saldo_inicial == 0 && saldo_prevadic_acum == 0 
            && saldo_arrecadado == 0 && saldo_arrecadado_acumulado == 0) {
          continue;    
        }
        var sBold       = 'normal';
        if (o70_codrec == 0) {
         sBold = 'bold';
        }
        sCorpoReceitas += '<tr style="height:1em; font-weight:'+sBold+'">';    
        sCorpoReceitas += '  <td style="border-right:1px solid gray;border-bottom:1px solid gray">';    
        sCorpoReceitas +=     o57_fonte;
        sCorpoReceitas += '  </td>';
        sCorpoReceitas += '  <td style="border-right:1px solid gray;border-bottom:1px solid gray">';    
        sCorpoReceitas +=     o57_descr.urlDecode();
        sCorpoReceitas += '  </td>';
        sCorpoReceitas += '  <td style="border-right:1px solid gray;border-bottom:1px solid gray">';    
        sCorpoReceitas +=     o70_codigo.urlDecode();
        sCorpoReceitas += '  </td>';
        sCorpoReceitas += '  <td style="border-right:1px solid gray;text-align:right;border-bottom:1px solid gray">';    
        sCorpoReceitas +=     saldo_inicial;
        sCorpoReceitas += '  </td>'     
        sCorpoReceitas += '  <td style="border-right:1px solid gray;text-align:right;border-bottom:1px solid gray">';    
        sCorpoReceitas +=     saldo_prevadic_acum;
        sCorpoReceitas += '  </td>' 
        sCorpoReceitas += '  <td style="text-align:right;border-right:1px solid gray;border-bottom:1px solid gray">';    
        sCorpoReceitas +=     saldo_arrecadado;
        sCorpoReceitas += '  </td>'
        sCorpoReceitas += '  <td style="text-align:right;border-bottom:1px solid gray">';    
        sCorpoReceitas +=     saldo_arrecadado_acumulado;
        sCorpoReceitas += '  </td>'     
        sCorpoReceitas += '</tr>';
        if (o70_codrec != 0) {
         
          sTotalPrevisto          += js_strToFloat(saldo_inicial);
          sTotalPrevistoAdicional += js_strToFloat(saldo_prevadic_acum);
          sTotalArrecadado        += js_strToFloat(saldo_arrecadado);
          sTotalArrecadadoAno     += js_strToFloat(saldo_arrecadado_acumulado);
        }    
      } 
    }
    
  }
  sCorpoReceitas     += '<tr style="height:auto"><td>&nbsp;</td></tr>';
  sCorpoReceitas     += "</tbody> ";
  sCorpoReceitas     += "<tfoot style='background-color:#eeeee2;color:black'> ";
  sCorpoReceitas     += "               <tr> ";
  sCorpoReceitas     += "                 <th colspan='3' style='text-align: right;color:black'> ";
  sCorpoReceitas     += "                   <b>Total</b> ";
  sCorpoReceitas     += "                 </th> ";
  sCorpoReceitas     += "                 <th style='text-align:right;color:black'> ";
  sCorpoReceitas     += "                   <span id='totalprevisto'></span> ";
  sCorpoReceitas     += "                 </th>  ";
  sCorpoReceitas     += "                 <th style='text-align:right;color:black'> ";
  sCorpoReceitas     += "                   <span id='totalprevistoadicional'></span> ";
  sCorpoReceitas     += "                 </th> ";
  sCorpoReceitas     += "                 <th style='text-align:right;color:black'> ";
  sCorpoReceitas     += "                   <span id='totalarrecadado'></span> ";
  sCorpoReceitas     += "                 </th>  ";
  sCorpoReceitas     += "                 <th style='text-align:right;color:black'> ";
  sCorpoReceitas     += "                   <span id='totalarrecadadoano'></span> ";
  sCorpoReceitas     += "                 </th> ";
  sCorpoReceitas     += "                 <th width='17px'>&nbsp; ";
  sCorpoReceitas     += "                 </th> ";
  sCorpoReceitas     += "               </tr> ";
  sCorpoReceitas     += "             </tfoot> ";
  sCorpoReceitas     += "          </table> ";
  $('tablereceitas').innerHTML                  = sCorpoReceitas;
  $('totalprevisto').innerHTML          = js_formatar(new String(sTotalPrevisto), 'f', 2);
  $('totalprevistoadicional').innerHTML = js_formatar(new String(sTotalPrevistoAdicional), 'f', 2);
  $('totalarrecadado').innerHTML        = js_formatar(new String(sTotalArrecadado), 'f', 2);
  $('totalarrecadadoano').innerHTML     = js_formatar(new String(sTotalArrecadadoAno), 'f', 2);
  if (browser.isIE) {
    var t = new ScrollableTable(document.getElementById('tabelareceita'), 300);
  }   
}
$('pesquisasrreceitas').observe("click", getReceitasByPeriodo);

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