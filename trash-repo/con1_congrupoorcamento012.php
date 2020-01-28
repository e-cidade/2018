<?php
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
 
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oGet              = db_utils::postMemory($_GET);
$oRotuloOrcdotacao = new rotulo("orcdotacao");
$oRotuloOrcdotacao->label();


switch ($oGet->iOpcao) {

  case '1' :

    $iOpcaoCodigo    = 1;
    $iOpcaoDescricao = 1;
    break;

  case '2' :

    $iOpcaoCodigo    = 3;
    $iOpcaoDescricao = 1;
    break;

  case '3' :

    $iOpcaoCodigo    = 3;
    $iOpcaoDescricao = 3;
    break;
}


$aOpcoes = array('in'=>'Contendo','not in'=>'Não Contendo');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    
    <style type="text/css">
      fieldset #principal {
      
        width:500px;
        }
      
      fieldset.body {
      
        border:none;
        border-top:outset 1px;
      }  
        
        
        .divbody {
        
        margin-top:30px;
        }
    </style>
  </head>
  
  <body bgcolor="#CCCCCC" style="margin-top:25px" >
  <center>
  <div class='divbody' style="display:table;">    
    <form id="form1" name="form1">
      <fieldset >  
        <legend><b>Contas do Grupo do Plano Orçamentário</b></legend>
        <fieldset class='body' id='principal'>
          <legend><b>Plano de Contas</b></legend>
          <table border="0">
              <tr>
                <td width='110'><b>Conta :</b></td>
                <td>
                  <?
                  db_input('c60_estrut', 10, @$Ic60_estrut, true, 'text', $iOpcaoCodigo);
                  ?>
                </td>
              </tr>
           </table>
        </fieldset>

        <fieldset class='body' id='toogle'>
          <legend><b> Vínculo com o Orçamento</b></legend>

          <table border="0">
            <tr>
              <td width='110'><b>Orgão :</b></td>
              <td>
                <?php 
                  db_select('orgaovincula',$aOpcoes,true,1,"");
                ?>
              </td>
              
              <td>
                <?php
                db_input('o58_orgao', 30, $Io58_orgao, true, 'text', 1);
                ?>
              </td>
            </tr>
            
            <tr>
              <td><b>Unidade</b></td>
              <td>
                <?php 
                  db_select('unidadevincula',$aOpcoes,true,1,"");
                ?>
              </td>
              <td>
                <?php
                db_input('o58_unidade', 30, $Io58_unidade, true, 'text', 1);
                ?>
              </td>
            </tr>

            <tr>
              <td><b>Função :</b></td>
              <td>
                <?php 
                  db_select('funcaovincula',$aOpcoes,true,1,"");
                ?>
              </td>
              <td>
                <?php
                db_input('o58_funcao', 30, $Io58_funcao, true, 'text', 1);
                ?>
              </td>
            </tr>
            
            <tr>
              <td><b>Subfunção :</b></td>
              <td>
                <?php 
                  db_select('subfuncaovincula',$aOpcoes,true,1,"");
                ?>
              </td>
              <td>
                <?php
                db_input('o58_subfuncao', 30, $Io58_subfuncao, true, 'text', 1);
                ?>
              </td>
            </tr>
            
            <tr>
              <td><b>Programa :</b></td>
              <td>
                <?php 
                  db_select('programavincula',$aOpcoes,true,1,"");
                ?>
              </td>
              <td>
                <?php
                db_input('o58_programa', 30, $Io58_programa, true, 'text', 1);
                ?>
              </td>
            </tr>
            
            <tr>
              <td><b>Projeto/Atividade :</b></td>
              <td>
                <?php 
                  db_select('projetovincula',$aOpcoes,true,1,"");
                ?>
              </td>
              <td>
                <?php
                db_input('o58_projativ', 30, $Io58_projativ, true, 'text', $iOpcaoCodigo);
                ?>
              </td>
            </tr>
            
            
            <tr>
              <td><b>Recurso :</b></td>
              <td>
                <?php 
                  db_select('recursovincula',$aOpcoes,true,1,"");
                ?>
              </td>
              <td>
                <?php
                db_input('o58_codigo', 30, $Io58_codigo, true, 'text', $iOpcaoCodigo);
                ?>
              </td>
            </tr> 
            
            <tr>
              <td><b>CP / CA:</b></td>
              <td>
                <?php 
                  db_select('concarpeculiarvincula',$aOpcoes,true,1,"");
                ?>
              </td>
              <td>
                <?php
                db_input('o58_concarpeculiar', 30, $Io58_concarpeculiar, true, 'text', $iOpcaoCodigo);
                ?>
              </td>
            </tr>  
             
          </table>    
         </fieldset>
        
      </fieldset>
      
      <center>
      <br>
        <input name="btnSalvarSituacao" type="button" id="db_opcao" onclick="js_salvaGrupo()" value="Salvar"/>
      </center>
    </form>
  </div>
  <center>
    <fieldset style="width: 800px;">
      <legend><b>Contas Adicionadas ao Grupo</b></legend>
      <div id='cntDados'>
      </div>
    </fieldset>
    <br>
    <input type='button' onclick='js_excluiContas()' value='Excluir Conta(s)'/>
  </center>
  </center>
  </body>
</html>

<script>
                               
var toogle              = new DBToogle("toogle", false);
var oUrl                = js_urlToObject(window.location.search);
var iCodigoGrupo        = oUrl.c20_sequencial;
if (oUrl.c20_sequencial == undefined) {
  iCodigoGrupo = 0;
}
var sUrlRPC             = 'con4_grupocontaorcamento.RPC.php';

var oGridDados          = new DBGrid('gridDados');
oGridDados.nameInstance = 'oGridDados';
oGridDados.setCheckbox(0);
oGridDados.setCellWidth(new Array("10%", "25%","65%"));
oGridDados.setCellAlign(new Array('center', 'center', 'left'));
oGridDados.setHeader(new Array("Conta", "Estrutural","Descrição"));
oGridDados.show($('cntDados'));

function js_salvaGrupo () {


  if($F("c60_estrut") == '') {

    alert("Informe o estrutural da conta que deverá ser adicionado ao grupo.");
    return false;
  }
  
  var oParam                     = new Object();
  oParam.exec                    = 'salvarConta';
  oParam.c20_sequencial          =  oUrl.c20_sequencial;

  var oOrgao                     = new Object();
  oOrgao.nome_campo              = 'o58_orgao';
  oOrgao.regra_compara           = $F('orgaovincula');
  oOrgao.valor                   = $F('o58_orgao');

  var oUnidade                   = new Object();
  oUnidade.nome_campo            = 'o58_unidade';
  oUnidade.regra_compara         = $F('unidadevincula');
  oUnidade.valor                 = $F('o58_unidade');
                                 
  var oFuncao                    = new Object();
  oFuncao.nome_campo             = 'o58_funcao';
  oFuncao.regra_compara          = $F('funcaovincula');
  oFuncao.valor                  = $F('o58_funcao');
                        
  var oSubfuncao                 = new Object();
  oSubfuncao.nome_campo          = 'o58_subfuncao';
  oSubfuncao.regra_compara       = $F('subfuncaovincula');
  oSubfuncao.valor               = $F('o58_subfuncao');

  var oPrograma                  = new Object();
  oPrograma.nome_campo           = 'o58_programa';
  oPrograma.regra_compara        = $F('programavincula');
  oPrograma.valor                = $F('o58_programa');

  var oProjativ                  = new Object();
  oProjativ.nome_campo           = 'o58_projativ';
  oProjativ.regra_compara        = $F('projetovincula');
  oProjativ.valor                = $F('o58_projativ');  
  
  var oRecurso                   = new Object();
  oRecurso.nome_campo            = 'o58_codigo';
  oRecurso.regra_compara         = $F('recursovincula');
  oRecurso.valor                 = $F('o58_codigo'); 
  
  var oConcarpeculiar            = new Object();
  oConcarpeculiar.nome_campo     = 'o58_concarpeculiar';
  oConcarpeculiar.regra_compara  = $F('concarpeculiarvincula');
  oConcarpeculiar.valor          = $F('o58_concarpeculiar');  

  oParam.aVinculosOrcamento = new Array();
  oParam.aVinculosOrcamento.push(oOrgao); 
  oParam.aVinculosOrcamento.push(oUnidade);
  oParam.aVinculosOrcamento.push(oFuncao);
  oParam.aVinculosOrcamento.push(oSubfuncao);
  oParam.aVinculosOrcamento.push(oPrograma);
  oParam.aVinculosOrcamento.push(oProjativ);
  oParam.aVinculosOrcamento.push(oRecurso);
  oParam.aVinculosOrcamento.push(oConcarpeculiar);

  oParam.c60_estrut = $F('c60_estrut');

  js_divCarregando("Aguarde, salvando situação...", "msgBox");  


  var oAjax   = new Ajax.Request(sUrlRPC,
      {
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete: js_finalizaSalvar
      });
}

function js_finalizaSalvar(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
  js_preencheGrid();
  
}



function js_preencheGrid() {

  if (iCodigoGrupo == 0) {
    return false;
  }

  var oParam            = new Object();
  oParam.exec           = 'getContasGrupo';
  oParam.c20_sequencial = iCodigoGrupo;

  js_divCarregando("Aguarde, carregando contas do grupo...", "msgBox");
  var oAjax   = new Ajax.Request(sUrlRPC,
      {
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete: js_finalizaPreencheGrid
      });
}

function js_finalizaPreencheGrid(oAjax){

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  oGridDados.clearAll(true);

  oRetorno.aContas.each( function (oDado, iInd) {

    var aRow    = new Array();  
    aRow[0] = oDado.iCodigoConta ;
    aRow[1] = oDado.estrutural;
    aRow[2] = oDado.descricao.urlDecode();
    oGridDados.addRow(aRow);
  });

  oGridDados.renderRows(); 
  
}

/**
 * retorna as contas selecionadas para exclusao
 */

function js_getSelecionados() {

  
  var aListaCheckbox     = oGridDados.getSelection();
  var aListaSelecionados = new Array();
  
  aListaCheckbox.each(
    function ( aRow ) {
      aListaSelecionados.push(aRow[0]);
   }
  )
  
  return aListaSelecionados;
  
}

function js_excluiContas(){

  if ( !confirm("Deseja excluir as contas selecionadas?") ){

    return false;
  }

  
  var oParam                = new Object();
      oParam.exec           = 'excluirConta';
      oParam.c20_sequencial = iCodigoGrupo;
      oParam.aContasExcluir = js_getSelecionados();

      if (oParam.aContasExcluir == '') {
        
        alert('Selecione alguma conta para exclusão');
        return false;
      }

  js_divCarregando("Aguarde, excluindo conta do grupo...", "msgBox");
  var oAjax   = new Ajax.Request(sUrlRPC,
                                  {
                                    method:'post',
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_finalizaExclusao
                                  });

  
}


function js_finalizaExclusao(oAjax){

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
  js_preencheGrid();
}

js_preencheGrid();
</script>