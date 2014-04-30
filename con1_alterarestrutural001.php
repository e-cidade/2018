<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_conplanoorcamento_classe.php");
require_once("classes/db_orcfontes_classe.php");
require_once("classes/db_orcelemento_classe.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);

$anousu = db_getsession("DB_anousu");

$cliframe_seleciona_conplano = new cl_iframe_seleciona;

$clconplanoOrcamento         = new cl_conplanoorcamento;
$clorcfontes                 = new cl_orcfontes;
$clorcelemento               = new cl_orcelemento;

$clconplanoOrcamento->rotulo->label("c60_codcon");
$clconplanoOrcamento->rotulo->label("c60_descr");
$clconplanoOrcamento->rotulo->label("c60_estrut");

$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/dbtextFieldData.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js, widgets/dbtextField.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>


<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

 
<center>

<form name="form1" action="" method="post">


<fieldset style="margin-top: 50px; width: 700px;">
  <legend>Alterar Estrutural do Plano de Contas Orçamentário</legend>
  <table border="0" align='left' style="width:100%;">
    <tr> 
      <td width="10%" align="left" title="<?=@$Tc60_estrut?>">
        <?=@$Lc60_estrut?>
      </td>
      <td>
        <?PHP
          db_input("c60_estrut",40, $Ic60_estrut,true,"text",4,"","chave_c60_estrut");
          db_input("c60_codcon",15,0,true,"hidden",3);
        ?> 
      </td>
    </tr>
  </table>
</fieldset>

<div style="margin-top:10px;">
  <input type='button' value='Pesquisar' onclick='js_listaContas();' />
</div>     

<fieldset style="margin-top:10px; width: 700px;">
  <legend>Contas Encontradas</legend>
  <div style="margin-top: 10px;" > 
    <label style="float: left; font-weight: bold;">
      Alterar Para: 
     <input size="40" maxlength="15" type='text' id='novoEstrutural' onKeyUp="js_ValidaCampos(this,0,'Estrutural','t','t',event);"  onblur="js_ValidaMaiusculo(this,'t',event);" />
   
    </label>
  </div>
  
  <br>
  <div style="margin-top: 10px; width: 700px;" id='ctnContasEncontradas'></div>
</fieldset>

<div style="margin-top:10px;">
  <input type='button' value='Processar' onclick='js_alterar();' />
</div>  


</form>  

</center>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

var sUrlMensagem = "financeiro.contabilidade.con1_alterarestrutural001.";
var sUrlRPC      = "con1_alterarestrutural.RPC.php";

function js_gridContas() {

  oGridContas = new DBGrid('Contas');
  oGridContas.nameInstance = 'oGridContas';
  oGridContas.setCheckbox(0);
  //oGridContas.allowSelectColumns(true);
  oGridContas.setCellWidth(new Array( '70px' ,
                                      '70px',
                                      '130px',
                                      '330px'
                                     ));
  
  oGridContas.setCellAlign(new Array( 'left'  ,
                                      'left'  ,
                                      'center',
                                      'left'  
                                     ));
  
  
  oGridContas.setHeader(new Array( 'Código',
                                   'Reduzido',
                                   'Estrutural',
                                   'Descrição'
                                  ));
  oGridContas.setHeight(300);
  oGridContas.show($('ctnContasEncontradas'));
  oGridContas.clearAll(true);
}

 
function js_listaContas() {

  var iEstrutural  = $F('chave_c60_estrut'); 
  var msgDiv       = _M(sUrlMensagem +  "pesquisando_contas" );
  var oParametros  = new Object();
  
  if (iEstrutural == '') {

    alert( _M( sUrlMensagem + "selecione_estrutural") );
    return false;
  }
  
  oParametros.exec        = 'getDadosConta';
  oParametros.iEstrutural = iEstrutural;

  js_divCarregando(msgDiv,'msgBox');
  
  new Ajax.Request(sUrlRPC,
                          {method: "post",
                           parameters:'json='+Object.toJSON(oParametros),
                           onComplete: js_retornoCompletaContas
                          });
                         
}
/*
 * funcao para montar a grid com os registros de interessados
 *  retornado do RPC
 *
 */ 
function js_retornoCompletaContas(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.iStatus == 1) {

      oGridContas.clearAll(true);
        
      if ( oRetorno.aDadosRetorno.length == 0 ) {
      
        alert(_M( sUrlMensagem + "nenhumregistroencontrado"));
        return false;
      } 
      
      oRetorno.aDadosRetorno.each(
           
                    function (oDado, iInd) {       

                        var aRow    = new Array();  
                            aRow[0] = oDado.iCodigo;
                            aRow[1] = oDado.iReduzido;
                            aRow[2] = oDado.sEstrutural;
                            aRow[3] = oDado.sDescricao.urlDecode();                            
                            oGridContas.addRow(aRow);
                       });
      oGridContas.renderRows();
    } 
}

function js_alterar() {


  var aListaCheckbox  = oGridContas.getSelection();
  var oParametros     = new Object();
  var aListaContas    = new Array();
  var sEstruturalNovo = $F('novoEstrutural');
  var msgDiv          = _M(sUrlMensagem +  "alterando_contas" );
  
  aListaCheckbox.each(
    function ( aRow ) {

      oDadosConta  = new Object();
      oDadosConta.c60_codcon = aRow[1];
      oDadosConta.c61_reduz  = aRow[2];
      oDadosConta.c60_estrut = aRow[3];
      
      aListaContas.push(oDadosConta);
   }
  );

  if (sEstruturalNovo == '') {

    alert(_M(sUrlMensagem + "estrutural_vazio"));
    return false;
  }
  
  if (aListaContas == '') {

    alert(_M(sUrlMensagem + "contas_vazia"));
    return false;
  }
  oParametros.exec            = 'alterarEstrutural';
  oParametros.sEstruturalNovo = sEstruturalNovo;
  oParametros.aContasAlterar  = aListaContas;

  js_divCarregando(msgDiv,'msgBox');
  
  new Ajax.Request(sUrlRPC,
                          {method: "post",
                           parameters:'json='+Object.toJSON(oParametros),
                           onComplete: js_retornoAlterar
                          });  

  
}
function js_retornoAlterar(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());
  oGridContas.clearAll(true);
}

 js_gridContas();

</script>