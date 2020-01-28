<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

/**
 * 
 * @author I
 * @revision $Author: dbevandro $
 * @version $Revision: 1.3 $
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arrebanco_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clarrecad = new cl_arrecad;
$clarrebanco = new cl_arrebanco;

$clarrecad->rotulo->label();
$clarrebanco->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
td {
  width: 60px;
  white-space: nowrap
}

.barra {
  border:1px;
  border-left:2px groove white;
  margin-left:20px;
}

.display-none {
  display: none;
}

.display-on {
  display: inherit;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
   <table align="center" border="0" width="50%">
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>
         <fieldset>
           <legend><b>Consulta Numpre/Numbanco<b></legend>
            <table align="left" border="0">
                <tr>
                  <td title="<?=@$Tk00_numpre?>" align="left">
                    <?=@$Lk00_numpre?>
                  </td>
                  <td>
                    <?
                      db_input('k00_numpre',12,@$Ik00_numpre,true,'text',1," onchange='js_limpacampos(false);'");
                    ?>
                  </td>
                  <td>&nbsp;</td>
                  <td title="<?=@$Tk00_numpar?>" align="left">
                    <?=@$Lk00_numpar?>
                  </td>
                  <td>
                    <?
                      db_input('k00_numpar',12,@$Ik00_numpar,true,'text',1," onchange='js_limpacampos(false);'");
                    ?>
                  </td>
                  <td>&nbsp;</td>
                  <td class="barra">&nbsp;</td>
                  <td title="<?=@$Tk00_numbco?>" align="left">
                    <?=@$Lk00_numbco?>
                  </td>
                  <td>
                    <?
                      db_input('k00_numbco',12,@$Ik00_numbco,true,'text',1," onchange='js_limpacampos(true);'");
                    ?>
                  </td>
                </tr>
            </table>
         </fieldset>
       </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center">
         <input name="pesquisar" id="pesquisar" type="button" value="Pesquisar" 
                onclick="return js_consultaNumpreNumbanco();">     
       </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>
         <fieldset id="fielsetGrid" class="display-none">
           <div id='cntGridNumpreNumbanco'></div>  
         </fieldset>
       </td>
     </tr>
   </table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
var sUrl = 'cai3_numprenumbanco.RPC.php';

/*
 * DBGrid Numpre/Numbanco
 */
function js_montaGridNumpreNumBanco(sTipoPesquisa) {

  oGridNumpreNumbanco              = new DBGrid("gridNumpreNumbanco");
  oGridNumpreNumbanco.nameInstance = "oGridNumpreNumbanco";
  oGridNumpreNumbanco.allowSelectColumns(true);
  if (sTipoPesquisa == 'Numpre') {
    
    oGridNumpreNumbanco.setCellWidth(new Array("8%", "20%", "20%", "20%", "20%", "50%", "20%", "20%",
                                               "20%", "20%", "10%", "20%", "20%", 
                                               "20%", "20%", "20%","20%"));                    
    oGridNumpreNumbanco.setCellAlign(new Array("center", "center", "center", "center", "center", "left", "center", "center",
                                               "left", "right", "center", "center", "center", 
                                               "left", "center", "left","center"));                                        
    oGridNumpreNumbanco.setHeader(new Array("MI", "Numnov", "Numbco", "Numpre", "Parcela", "Receita", "Cgm", "Dt. Operação", "Histórico", 
                                            "Valor", "Vencimento", "Total Parcelas", "Digito", "Tipo Débito", 
                                            "Movimentação"));
                                            
    oGridNumpreNumbanco.aHeaders[6].lDisplayed  = false;
    oGridNumpreNumbanco.aHeaders[9].lDisplayed  = false;
    oGridNumpreNumbanco.aHeaders[10].lDisplayed = false;
  } else if (sTipoPesquisa == 'Numbanco') {
  
    oGridNumpreNumbanco.setCellWidth(new Array("8%", "10%", "10%", "10%", "10%", 
                                               "20%", "20%", "20%", "20%","20%"));
  
    oGridNumpreNumbanco.setCellAlign(new Array("center", "center", "center", "center", "center", 
                                               "center", "center", "center", "left"));                                        
    oGridNumpreNumbanco.setHeader(new Array("MI", "Numnov", "Numpre", "Parcela", "Código Banco", "Código Agência", 
                                            "Número Banco", "Número Banco Anterior"));
  }
  oGridNumpreNumbanco.show($('cntGridNumpreNumbanco'));
}

/*
 * Pesquisa Numpre/NumBanco
 */ 
function js_consultaNumpreNumbanco() {
  
  var iNumpre   = $F('k00_numpre');
  var iNumpar   = $F('k00_numpar');
  var iNumbanco = $F('k00_numbco');
  
  if (!iNumpre && !iNumbanco) {
  
    var sMsg  = 'Usuário: \n\n';
        sMsg += ' Informe um Numpre ou Numbanco Válido!';
    alert(sMsg);
    return false;
  }
   
  js_divCarregando('Pesquisando aguarde...','msgBoxPesquisa');
   
  var oParam       = new Object();
  oParam.exec      = "pesquisaNumpreNumbanco";
  oParam.numpre    = iNumpre.trim();
  oParam.numpar    = iNumpar.trim();
  oParam.numbanco  = iNumbanco.trim();
    
  var oAjax        = new Ajax.Request( sUrl, {
                                               method: 'post', 
                                               parameters: 'json='+js_objectToJson(oParam), 
                                               onComplete: js_retornoPesquisa
                                             }
                                     );    
}

/*
 * Preenche Grid Numpre/NumBanco
 */ 
function js_retornoPesquisa(oAjax) {

  js_removeObj("msgBoxPesquisa");
  
  $("fielsetGrid").className = 'display-on';
  
  var oRetorno        = eval("("+oAjax.responseText+")");   
  var aNumpreNumbanco = oRetorno.aNumpreNumbanco;
  var iNumrows        = aNumpreNumbanco.length;
  var sTipoPesquisa   = oRetorno.TipoPesquisa.trim();

  if (oRetorno.erro == 1) {
  
    $("fielsetGrid").className = 'display-none';
    return false;
  }
  
  js_montaGridNumpreNumBanco(sTipoPesquisa);
     
  oGridNumpreNumbanco.clearAll(true);
  
  if (sTipoPesquisa == 'Numpre') {

	  aNumpreNumbanco.each(function (oNumpreNumbanco, id) {
	     
	    var aLinha = new Array();
	    
      aLinha[0] = "<a href='#' onclick='js_pesquisaMi("+oNumpreNumbanco.numpre
                                                       +","
                                                       +oNumpreNumbanco.numpar
                                                       +","
                                                       +oNumpreNumbanco.codreceita
                                                       +");'>MI</a>";
	    aLinha[1]  = oNumpreNumbanco.numnov;
	    aLinha[2]  = oNumpreNumbanco.numbco;
	    aLinha[3]  = oNumpreNumbanco.numpre;
	    aLinha[4]  = oNumpreNumbanco.numpar;
	    aLinha[5]  = oNumpreNumbanco.codreceita+'-'+oNumpreNumbanco.descrreceita.urlDecode();
	    aLinha[6]  = oNumpreNumbanco.numcgm;
	    aLinha[7]  = js_formatar(oNumpreNumbanco.dtoper,'d');
	    aLinha[8]  = oNumpreNumbanco.codhist+'-'+oNumpreNumbanco.descrhist.urlDecode();
	    aLinha[9]  = js_formatar(oNumpreNumbanco.valor,'f');
	    aLinha[10] = js_formatar(oNumpreNumbanco.dtvenc,'d');
	    aLinha[11] = oNumpreNumbanco.totparc;
	    aLinha[12] = oNumpreNumbanco.digitp;
	    aLinha[13] = oNumpreNumbanco.codtipo+'-'+oNumpreNumbanco.descrtipo.urlDecode();
	    aLinha[14] = oNumpreNumbanco.movimentacao.urlDecode();
	        
	    oGridNumpreNumbanco.addRow(aLinha, false, false, false);
	  });

  } else if (sTipoPesquisa == 'Numbanco') {
  
    aNumpreNumbanco.each(function (oNumpreNumbanco, id) {
       
      var aLinha = new Array();
      
      aLinha[0] = "<a href='#' onclick='js_pesquisaMi("+oNumpreNumbanco.numpre
                                                       +","
                                                       +oNumpreNumbanco.numpar
                                                       +","
                                                       +oNumpreNumbanco.codreceita
                                                       +");'>MI</a>";
      aLinha[1] = oNumpreNumbanco.numnov;
      aLinha[2] = oNumpreNumbanco.numpre;
      aLinha[3] = oNumpreNumbanco.numpar;
      aLinha[4] = oNumpreNumbanco.codbanco;
      aLinha[5] = oNumpreNumbanco.codagencia;
      aLinha[6] = oNumpreNumbanco.numbanco;
      aLinha[7] = oNumpreNumbanco.numbancoant;
          
      oGridNumpreNumbanco.addRow(aLinha, false, false, false);
    });
  }
  
  if (iNumrows == 0) {
    oGridNumpreNumbanco.setStatus('Nenhum registro encontrado.');
  }
  
  oGridNumpreNumbanco.renderRows();
     
}

/*
 * Limpa campos numpar, numpre e numbanco
 */
function js_limpacampos(lCampo) {
  
  if (lCampo == true) {

    $('k00_numpre').value = '';
    $('k00_numpar').value = '';
  } else {
    $('k00_numbco').value = '';
  }
}

/*
 * Lockup de pesquisa 
 */
function js_pesquisaMi(iNumpre,iNumpar,iReceita) {

  js_OpenJanelaIframe('top.corpo','db_iframe_consultanumprenumbanco',
                      'func_consultanumprenumbanco.php?numpre='+iNumpre+'&numpar='+iNumpar+'&receita='+iReceita,
                      'Pesquisa',true);
}
</script>
</html>