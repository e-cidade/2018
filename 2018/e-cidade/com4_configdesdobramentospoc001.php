<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * @revision $Author: dbluizmarcelo $
 * @version $Revision: 1.1 $
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
require("libs/db_app.utils.php");
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empparametro_classe.php");
include("classes/db_desdobramentosliberadosordemcompra_classe.php");
include("classes/db_orcelemento_classe.php");

$clorcelemento  = new cl_orcelemento;
$clempparametro = new cl_empparametro;
$clrotulo       = new rotulocampo;
$clrotulo->label("o56_elemento");

$dbopcao  = 1;
$sDisable = '';
$bMostrar = true;

$result = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu")));

if($result != false && $clempparametro->numrows > 0){
  $oParam = db_utils::fieldsMemory($result,0);
}

/*
 * Desabilita a pesquisa caso os parametros tiver como nao
 */

if ($oParam->e30_liberaempenho != 't') {
  $dbopcao  = 3;
  $bMostrar = false;
  $sDisable = "disabled";
  $sMesErro = "<b>* Está Instituição não utiliza controle de empenhos *</b> para a ordem de compras.";
}
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_pesquisaElementos();">
<table>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
   <table align="center" border="0">
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="left">&nbsp;<b>Estrutural: </b>
       <?
         db_input('o56_elemento',10,$Io56_elemento,true,'text',$dbopcao,"",""); 
       ?>
         <input  name="pesquisar" id="pesquisar" type="button" value="Pesquisar" onclick='js_pesquisaElementos();' 
                 <?=$sDisable?>>
       </td>
     </tr>
     <tr>
     <td width="700">
	   <fieldset>
	     <legend><b>Elementos Liberados<b></legend>
	       <div id="ctnGridElementosLiberados"><?=$sMesErro?></div>
	   </fieldset>
     </td>
     </tr>
     <tr align="center">
       <td>
         <input  name="processar" id="processar" type="button" value="Processar" onclick="js_liberardesdobramentos();"
                 <?=$sDisable?>>      
       </td>
     </tr>
   </table>
</form>
<table align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:400px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;z-index: 100000' 
            id='ajudaItem'>

</div>
<?
if ($bMostrar) {
?>
<script>
  var oGridElementosLiberados          = new DBGrid('gridElementosLiberados');
  oGridElementosLiberados.nameInstance = "oGridElementosLiberados";
  oGridElementosLiberados.setHeight(300);
  oGridElementosLiberados.setCheckbox(0);
  oGridElementosLiberados.setCellAlign(new Array("center", "center", "left"));
  oGridElementosLiberados.setCellWidth(new Array("15%","45%","40%"));
  oGridElementosLiberados.setHeader(new Array('Código','Elemento','Descrição'));
  oGridElementosLiberados.show($('ctnGridElementosLiberados'));
  oGridElementosLiberados.clearAll(true);

/*
 * Pesquisa os Elementos para a liberacao
*/

function js_pesquisaElementos() {

   var o56_elemento = $F('o56_elemento');
   $('pesquisar').disabled = true;
   $('processar').disabled = true;
   js_divCarregando("Aguarde.. Pesquisando ","msgbox");
   var oParam          = new Object();
   oParam.exec         = "pesquisaElemento";
   oParam.estrutural  = o56_elemento;
   
   // consulta ajax retorna objeto json
   var oAjax        = new Ajax.Request(
                                      "com4_configdesdobramentospo.RPC.php", 
                                       {
                                         method    : 'post', 
                                         parameters: 'json='+js_objectToJson(oParam), 
                                         onComplete: js_retornoPesquisa
                                        }
                                      );
}

/*
 * Processa o retono da pesquisa 
*/

function js_retornoPesquisa(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {
    js_openPesquisaElementosLiberados(oRetorno.aItens,oRetorno.aItens.length);
  }
}

/*
 * Mostra a GRID com os registros retornado da pesquisa
*/

function js_openPesquisaElementosLiberados(aDesdobramento,iRetornoOrcElemento) {

  oGridElementosLiberados.clearAll(true);  
  if (iRetornoOrcElemento == 0) {
    oGridElementosLiberados.setStatus('Não foram encontrados Registros');
  } else {
    for (var i = 0; i < aDesdobramento.length; i++) {
  
      with(aDesdobramento[i]) {
      
        var aLinha        = new Array();
            aLinha[0]     = o56_codele;
            aLinha[1]     = o56_elemento;
            aLinha[2]     = o56_descr.urlDecode();

            var lMarca    = false;
            var lBloquear = false;
            if (pc33_sequencial != "") {
              lMarca = true;
            }

            oGridElementosLiberados.addRow(aLinha, false, lBloquear, lMarca);
            oGridElementosLiberados.aRows[i].aCells[2].sEvents += "onMouseOver='js_setAjuda(\""+o56_elemento.urlDecode()+"\",true)'";
            oGridElementosLiberados.aRows[i].aCells[2].sEvents += "onMouseOut='js_setAjuda(null, false)'";
            oGridElementosLiberados.aRows[i].aCells[3].sEvents += "onMouseOver='js_setAjuda(\""+o56_descr.urlDecode()+"\",true)'";
            oGridElementosLiberados.aRows[i].aCells[3].sEvents += "onMouseOut='js_setAjuda(null, false)'";
            
       }
    }
  }

  oGridElementosLiberados.renderRows();
  $('pesquisar').disabled = false;
  $('processar').disabled = false;
}

/*
 * Libera desdobramentos
*/

function js_liberardesdobramentos() {

   var aItens     = oGridElementosLiberados.aRows;
   
   if (!confirm('Está rotina irá bloquear/Liberar os desdobramentos contidos na lista. Deseja Continuar?')){
     return false;
   }
   
   js_divCarregando("Aguarde.. Processando ","msgbox");
   $('pesquisar').disabled = true;
   $('processar').disabled = true;
   
   var oParam        = new Object();
   oParam.exec       = "processaElementosLiberados";
   oParam.aDesdobramento  = new Array();
   
   for (var i = 0; i < aItens.length; i++) {
       
     var oElementosLiberados          = new Object();
         oElementosLiberados.iNumele  = aItens[i].aCells[1].getValue();
         oElementosLiberados.lLiberar = aItens[i].isSelected;
         oParam.aDesdobramento.push(oElementosLiberados);
       
   }
   var oAjax        = new Ajax.Request(
                                      "com4_configdesdobramentospo.RPC.php", 
                                       {
                                         method    : 'post', 
                                         parameters: 'json='+js_objectToJson(oParam), 
                                         onComplete: js_retornoLiberarDesdobramentos
                                        }
                                      );
}

/*
 * Retorno dos desdobramentos liberados
*/

function js_retornoLiberarDesdobramentos(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {
  
    alert('Processo efetuado com sucesso.');
    js_pesquisaElementos();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

/*
 * Monta div com testo de ajuda
*/

function js_setAjuda(sTexto,lShow) {

  if (lShow) {
   
    var el =  $('gridgridElementosLiberados'); 
    var x  = 0;
    var y  = el.offsetHeight;

    //Walk up the DOM and add up all of the offset positions.
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY')
    {
     // if (el.className != "windowAux12") { 
      
        x += el.offsetLeft;
        y += el.offsetTop;
        
     // }
      el = el.offsetParent;
    }
   x += el.offsetLeft
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+"px";
   $('ajudaItem').style.left    = x+"px";
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
}
</script>
<?
}
?>