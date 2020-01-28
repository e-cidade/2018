<?PHP
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
require_once("classes/db_inventario_classe.php");
require_once("classes/db_inventarioanulado_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
 
$clinventario        = new cl_inventario;
$clinventarioanulado = new cl_inventarioanulado;
$clrotulo            = new rotulocampo;
$db_botao            = true;

$clinventario->rotulo->label();
$clinventarioanulado->rotulo->label();
$clrotulo->label("p58_codproc");
$clrotulo->label("ac08_descricao");

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");
db_app::load("estilos.css");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");

$dbOpcao = 3;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Desprocessamento de Invent�rio</legend>
    <table class="form-container">
      <!-- C�digo do Invent�rio -->
      <tr>
        <td nowrap title="<?php echo $Tt75_sequencial?>">
           <?php
             db_ancora(@$Lt75_sequencial,"js_pesquisa();", 1);
           ?>
        </td>
        <td> 
          <?php
            db_input('t75_sequencial',10,$It75_sequencial,true,'text', 3,"")
          ?>
        </td>
      </tr>
     <!-- Data da Abertura do Invent�rio -->
       <tr>
        <td nowrap title="<?php echo $Tt75_dataabertura ?>">
           <?php echo $Lt75_dataabertura ?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_dataabertura',
                          @$t75_dataabertura_dia,
                          @$t75_dataabertura_mes,
                          @$t75_dataabertura_ano,
                          true,
                          'text', 
                          3,
                          "");
          ?>
        </td>
      </tr>
      <!-- Per�odo Inicial -->
      <tr>
        <td nowrap title="<?php echo $Tt75_periodoinicial?>">
           <?php
             echo $Lt75_periodoinicial
           ?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_periodoinicial',
                          @$t75_periodoinicial_dia,
                          @$t75_periodoinicial_mes,
                          @$t75_periodoinicial_ano,
                          true,
                          'text',
                          $dbOpcao,
                          ""
                        );
          ?>
        </td>
      </tr>
      <!-- Per�odo Final -->
      <tr>
        <td nowrap title="<?php echo @$Tt75_periodofinal?>">
           <?php
             echo @$Lt75_periodofinal
           ?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_periodofinal',
                          @$t75_periodofinal_dia,
                          @$t75_periodofinal_mes,
                          @$t75_periodofinal_ano,
                          true,
                          'text',
                          $dbOpcao,
                          ""
                         );
          ?>
        </td>
      </tr>
      <!-- Exerc�cio-->
      <tr>
        <td nowrap title="<?php echo @$Tt75_exercicio?>">
           <?php 
             echo @$Lt75_exercicio
           ?>
        </td>
        <td> 
          <?
          db_input('t75_exercicio',10,$It75_exercicio,true,'text', 3,"")
          ?>
        </td>
      </tr>
      <!-- Processo -->
      <tr>
        <td nowrap title="<?php echo @$Tt75_processo?>">
           <?php
             echo $Lt75_processo;
           ?>
        </td>
        <td> 
          <?
            db_input('t75_processo',10,$It75_processo,true,'hidden',$dbOpcao);
            db_input('p58_codproc',10,$Ip58_codproc,true,'text',3,'');
          ?>
        </td>
      </tr>
      <!-- Comiss�o -->
      <tr>
        <td nowrap title="<?php echo @$Tt75_acordocomissao?>">
           <?
             echo $Lt75_acordocomissao;
           ?>
        </td>
        <td> 
          <?
            db_input('t75_acordocomissao',10,$It75_acordocomissao,true,'text',$dbOpcao);
            db_input('ac08_descricao',39,$Iac08_descricao,true,'text',3,'');
          ?>
        </td>
      </tr>
      <!-- Observa��o -->
      <tr>
        <td colspan="2">
        <fieldset class="separator">
          <legend>Observa��es</legend>
          <?php
            db_textarea('t75_observacao',2,70,$It75_observacao,true,'text',$dbOpcao,"")
          ?>
        </fieldset> 
        </td>
      </tr> 
    </table>
    <div id='ctnGridBens'>
    </div>
  </fieldset>
  <input 
    onclick = "js_desprocessarInventario();" 
    name    = "excluir" 
    type    = "button" 
    id      = "btnExcluir" 
    value   = "Desprocessar Invent�rio"  
  >
</form>



<?php
  db_menu(
    db_getsession("DB_id_usuario"),
    db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),
    db_getsession("DB_instit")); 
?>
</body>
</html>
<script>

js_pesquisa();
js_gridBens();
var sUrlRPC      = 'pat4_inventario.RPC.php';  
var oParametros  = new Object();



function js_desprocessarInventario() {

  var iInventario = $F('t75_sequencial');
  
  if (iInventario == '') {

    alert(_M('patrimonial.patrimonio.pat4_inventariodesprocessamento001.selecione_inventario'));
    return false;
  }

  if (!confirm(_M('patrimonial.patrimonio.pat4_inventariodesprocessamento001.deseja_efetuar_procedimento'))) {
    return false;
  }
  
  var msgDiv                  = _M('patrimonial.patrimonio.pat4_inventariodesprocessamento001.desprocessando_intervalo');
  oParametros.exec            = 'desprocessaInventario';  
  oParametros.iInventario     = iInventario;

  js_divCarregando(msgDiv,'msgBox');
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                   {method: "post",
                                    parameters:'json='+Object.toJSON(oParametros),
                                    onComplete: js_retornoDesprocessar}); 
}

function js_retornoDesprocessar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());
  
  if (oRetorno.iStatus == 2) {
    return false;
  } 
  
  oGridBens.clearAll(true);
  form1.reset();
}


/**
 * M�todo para buscar os itens de um Invent�rio
 */
function js_exibirBens() {

  var iInventario             = $F('t75_sequencial');
  var msgDiv                  = _M('patrimonial.patrimonio.pat4_inventariodesprocessamento001.cancelando_inventario');
  oParametros.exec            = 'getBensVinculados';  
  oParametros.iInventario     = iInventario;

  if (iInventario == '') {

    alert(_M('patrimonial.patrimonio.pat4_inventariodesprocessamento001.selecione_inventario'));
    return false;
  }

  js_divCarregando(msgDiv,'msgBox');
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                   {method: "post",
                                    parameters:'json='+Object.toJSON(oParametros),
                                    onComplete: js_retornoExibirBens}); 
}


/**
 * M�todo para exibir os itens de um Invent�rio na Grid
 */
function js_retornoExibirBens(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 2) {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  } 
  oGridBens.clearAll(true);
  oRetorno.aDados.each( 
      function (oDado, iInd) {       

              aRow     = new Array();  
              aRow[0]  = oDado.t52_bem;
              aRow[1]  = oDado.t52_descr.urlDecode();
              aRow[2]  = oDado.t41_placa.urlDecode(); 
              oGridBens.addRow(aRow);
         });
  oGridBens.renderRows();
}  


/*
 * grid para exibir os bens do inventario selecionado
 */

function js_gridBens() {

  oGridBens              = new DBGrid('Interessados');
  oGridBens.nameInstance = 'oGridBens';
  oGridBens.setCellWidth(new Array( '100px' ,
                                    '400px',
                                    '100px'
                                  ));
  
  oGridBens.setCellAlign(new Array( 'left'  ,
                                    'left'  ,
                                    'left'
                                  ));

  oGridBens.setHeader(new Array( 'C�digo',
                                 'Descri��o',
                                 'Placa'
                               ));
  oGridBens.setHeight(300);
  oGridBens.show($('ctnGridBens'));
  oGridBens.clearAll(true);
}


function js_pesquisa(){

  var sQuery  = "func_inventario.php?situacao=3&";
      sQuery += "funcao_js=parent.js_preenchepesquisa";
      sQuery += "|t75_sequencial"    ;
      sQuery += "|t75_dataabertura"  ;
      sQuery += "|t75_periodoinicial";  
      sQuery += "|t75_periodofinal"  ;
      sQuery += "|t75_processo"      ;
      sQuery += "|t75_exercicio"     ;
      sQuery += "|t75_acordocomissao";
      sQuery += "|ac08_descricao"    ;
      sQuery += "|t75_observacao"    ;
      
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_inventario',
                      sQuery,
                      'Pesquisa',
                      true);
}



function js_preenchepesquisa( iSequencial, 
                              dDataAbertura, 
                              dPeriodoInicial, 
                              dPeriodoFinal, 
                              iProcesso, 
                              iExercicio,
                              iComissao,
                              sComissao,
                              sObservacao
                              ){

  dDataAbertura   = js_formatar(dDataAbertura  ,'d','');
  dPeriodoInicial = js_formatar(dPeriodoInicial,'d','');
  dPeriodoFinal   = js_formatar(dPeriodoFinal  ,'d','');
  
  $("t75_sequencial").value     = iSequencial;
  $("t75_dataabertura").value   = dDataAbertura
  $("t75_periodoinicial").value = dPeriodoInicial;
  $("t75_periodofinal").value   = dPeriodoFinal;
  $("p58_codproc").value        = iProcesso;
  $("t75_exercicio").value      = iExercicio;
  $("t75_acordocomissao").value = iComissao;
  $("ac08_descricao").value     = sComissao;
  $("t75_observacao").value     = sObservacao;
  
  js_exibirBens();
  db_iframe_inventario.hide();
}
</script>