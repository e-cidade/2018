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
 *  Desenvolvido por Matheus Felini
 * 
 *  Rotina criada para configurar a seleção do usuário na rotina:
 *  PROCEDIMENTOS > DIFERENÇAS > PROCESSA DIFERENÇA DE SALÁRIO (pes1_procdifsalario001.php)
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_rhpessoal_classe.php");

// Configurações Seleção Matricula
$oFormRhMatricula                 = new cl_arquivo_auxiliar();
$oFormRhMatricula->cabecalho      = "<strong>Selecionar Matrículas</strong>";
$oFormRhMatricula->codigo         = "rh01_regist"; //chave de retorno da func
$oFormRhMatricula->descr          = "z01_nome";   //chave de retorno
$oFormRhMatricula->nomeobjeto     = 'rhpessoalmatricula';
$oFormRhMatricula->funcao_js      = 'js_mostra_RhMatricula';
$oFormRhMatricula->funcao_js_hide = 'js_mostra_RhMatricula1';
$oFormRhMatricula->sql_exec       = "";
$oFormRhMatricula->func_arquivo   = "func_rhpessoal.php";  //func a executar
$oFormRhMatricula->nomeiframe     = "db_iframe_rhMatricula";
$oFormRhMatricula->localjan       = "";
$oFormRhMatricula->db_opcao       = 2;
$oFormRhMatricula->tipo           = 2;
$oFormRhMatricula->top            = 0;
$oFormRhMatricula->linhas         = 10;
$oFormRhMatricula->vwidth         = 400;
$oFormRhMatricula->nome_botao     = 'db_lancaMatricula';
$oFormRhMatricula->fieldset       = false;

// LOTAÇÃO
$oFormRhLotacao                 = new cl_arquivo_auxiliar();
$oFormRhLotacao->cabecalho      = "<strong>Selecionar Lotação</strong>";
$oFormRhLotacao->codigo         = "r70_codigo"; //chave de retorno da func
$oFormRhLotacao->descr          = "r70_descr";   //chave de retorno
$oFormRhLotacao->nomeobjeto     = 'rhlotacao';
$oFormRhLotacao->funcao_js      = 'js_mostra_RhLotacao';
$oFormRhLotacao->funcao_js_hide = 'js_mostra_RhLotacao1';
$oFormRhLotacao->sql_exec       = "";
$oFormRhLotacao->func_arquivo   = "func_rhlota.php";  //func a executar
$oFormRhLotacao->nomeiframe     = "db_iframe_rhLotacao";
$oFormRhLotacao->localjan       = "";
$oFormRhLotacao->db_opcao       = 2;
$oFormRhLotacao->tipo           = 2;
$oFormRhLotacao->top            = 0;
$oFormRhLotacao->linhas         = 10;
$oFormRhLotacao->vwidth         = 400;
$oFormRhLotacao->nome_botao     = 'db_lancaLotacao';
$oFormRhLotacao->fieldset       = false;

// CARGO
$oFormRhCargo                 = new cl_arquivo_auxiliar();
$oFormRhCargo->cabecalho      = "<strong>Selecionar Cargo</strong>";
$oFormRhCargo->codigo         = "rh37_funcao"; //chave de retorno da func
$oFormRhCargo->descr          = "rh37_descr";   //chave de retorno
$oFormRhCargo->nomeobjeto     = 'rhcargo';
$oFormRhCargo->funcao_js      = 'js_mostra_RhCargo';
$oFormRhCargo->funcao_js_hide = 'js_mostra_RhCargo1';
$oFormRhCargo->sql_exec       = "";
$oFormRhCargo->func_arquivo   = "func_rhfuncao.php";  //func a executar
$oFormRhCargo->nomeiframe     = "db_iframe_rhCargo";
$oFormRhCargo->localjan       = "";
$oFormRhCargo->db_opcao       = 2;
$oFormRhCargo->tipo           = 2;
$oFormRhCargo->top            = 0;
$oFormRhCargo->linhas         = 10;
$oFormRhCargo->vwidth         = 400;
$oFormRhCargo->nome_botao     = 'db_lancaCargo';
$oFormRhCargo->fieldset       = false;


$iSelecao               = $_GET['iSelecao'];
$sSelecaoIframe         = $_GET["sSelecaoIframe"];
$sTipoFiltroIframe      = $_GET["sTipoFiltroIframe"];
$sConteudoSelecaoIframe = $_GET["sConteudoSelecaoIframe"];
$sGuardaSelecionados    = $_GET["sGuardaSelecionados"];

/**
 * Configurações de Display das Tabelas
 */
$sDispMatricInter        = "none";
$sDispMatricSelec        = "none";
$sDispLotacInter         = "none";
$sDispLotacSelec         = "none";
$sDispCargoSelec         = "none";
$sDispCargoInter         = "none";

if ( $iSelecao == 1) {
  
  /**
   *  Configuração do Filtro MATRICULA
   */
  $sDisplayTabelaMatricula = '';
  
  if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "1" ) {

    $aExplode         = explode("and", $sConteudoSelecaoIframe);
    $iRegistIni       = trim($aExplode[0]);
    $iRegistFim       = trim($aExplode[1]);
    $sDispMatricInter = '';
    $sDispMatricSelec = 'none';
    $sTipoFiltro = $sTipoFiltroIframe;
    
  } else if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "2" ) {
    
    $sTipoFiltro = $sTipoFiltroIframe;
    $sDispMatricSelec = '';
    $sDispMatricInter = 'none';
  } else {
    
    $sDispMatricSelec = 'none';
    $sDispMatricInter = 'none';
  }  
  
} else if ( $iSelecao == 2 ) {
  
  /**
   *  Configuração do Filtro LOTACAO
   */
  $sDisplayTabelaLotacao = ""; 

  if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "1" ) {

    $aExplode        = explode("and", $sConteudoSelecaoIframe);
    $iLotacIni       = trim($aExplode[0]);
    $iLotacFim       = trim($aExplode[1]);
    $sDispLotacInter = '';
    $sDispLotacSelec = 'none';
    $sTipoFiltro     = $sTipoFiltroIframe;

  } else if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "2" ) {

    $sTipoFiltro     = $sTipoFiltroIframe;
    $sDispLotacSelec = '';
    $sDispLotacInter = 'none';
  } else {

    $sDispLotacSelec = 'none';
    $sDispLotacInter = 'none';
  }

} else if ( $iSelecao == 3 ) {
  
  if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "1" ) {

    $aExplode        = explode("and", $sConteudoSelecaoIframe);
    $iCargoIni       = trim($aExplode[0]);
    $iCargoFim       = trim($aExplode[1]);
    $sDispCargoInter = '';
    $sDispCargoSelec = 'none';
    $sTipoFiltro     = $sTipoFiltroIframe;

  } else if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "2" ) {

    $sTipoFiltro     = $sTipoFiltroIframe;
    $sDispCargoSelec = '';
    $sDispCargoInter = 'none';
  } else {

    $sDispCargoSelec = 'none';
    $sDispCargoInter = 'none';
  }
  
}


db_postmemory($HTTP_GET_VARS,0)

?>

<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
   
    <?
      db_app::load("scripts.js, prototype.js, widgets/dbtextField.widget.js");
      db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js, widgets/dbtextField.widget.js");
      db_app::load("widgets/dbcomboBox.widget.js, estilos.css, grid.style.css");
    ?>
  </head>
  
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" >
<br>
<center>
<form name="form1">
  <fieldset style="width: 500px">
    <legend><strong>Configurar Seleção</strong></legend>
    <table width="100%">
    <!-- TABELA MATRICULA -->
      <tr>
        <td width="100"><b>Tipo de Filtro:</b></td>
        <td colspan="2">
          <?
            $aTipoFiltros = array("0" => "Selecione um Filtro", "1" => "Intervalo", "2" => "Selecionados");
            db_select("sTipoFiltro", $aTipoFiltros, true, 1, "style='width: 210px'");
          ?>
        </td>
      </tr>
    </table>
    <table id="tableMatricula" width="100%">
      <!-- MATRICULA POR INTERVALO -->
      <tr id="matriculaIntervalo" style="display:<?=$sDispMatricInter;?>;">
        <td align="center"  width="100">
          <?
            echo "<b>"; 
            db_ancora("Matrícula", "js_buscaMatricula('ini', true)", 1);
            echo "</b>&nbsp;&nbsp;";
          ?>
        </td>
        <td>
          <?
            db_input("iRegistIni", 10, 1, true, "text", 1, "onchange=\"js_buscaMatricula('ini', false);\"");
            echo "</b>&nbsp;&nbsp;<b>";
            db_ancora("à", "js_buscaMatricula('fim', true)", 1);
            echo "</b>&nbsp;&nbsp;";
            db_input("iRegistFim", 10, 1, true, "text", 1, "onchange=\"js_buscaMatricula('fim', false);\"");
          ?>
        </td>
      </tr>
      <!-- MATRICULA POR SELEÇÃO -->
      <tr id="matriculaSelecionadosTR">
        <table id="matriculaSelecionados" style="display:<?=$sDispMatricSelec;?>;" width="100%">
          <?
            $oFormRhMatricula->funcao_gera_formulario();
          ?>
        </table>
      </tr>
    </table>
    <!-- TABELA LOTACAO -->
    <table id="tableLotacao" width="100%">
      <tr id="lotacaoIntervalo" style="display:<?=$sDispLotacInter;?>;">
      <!-- LOTAÇÃO POR INTERVALO -->
        <td align="center" width="100">
          <?
            echo "<b>"; 
            db_ancora("Lotação", "js_buscaLotacao('ini', true)", 1);
            echo "</b>&nbsp;&nbsp;";
          ?>
        </td>
        <td>
          <?
            db_input("iLotacIni", 10, 1, true, "text", 1, "onchange=\"js_buscaLotacao('ini', false);\"");
            echo "</b>&nbsp;&nbsp;<b>";
            db_ancora("à", "js_buscaLotacao('fim', true)", 1);
            echo "</b>&nbsp;&nbsp;";
            db_input("iLotacFim", 10, 1, true, "text", 1, "onchange=\"js_buscaLotacao('fim', false);\"");
          ?>
        </td>
      </tr>
      <!-- LOTAÇÃO POR SELEÇÃO -->
      <tr id="lotacaoSelecionadosTR">
        <table id="lotacaoSelecionados" style="display:<?=$sDispLotacSelec;?>;" width="100%">
          <?
            $oFormRhLotacao->funcao_gera_formulario();
          ?>
        </table>
      </tr>
    </table>
    <!-- TABELA CARGO -->
    <table id="tableCargo" width="100%">
      <tr id="cargoIntervalo" style="display:<?=$sDispCargoInter;?>;">
      <!-- CARGO POR INTERVALO -->
        <td align="center" width="100">
          <?
            echo "<b>"; 
            db_ancora("Cargo", "js_buscaCargo('ini', true)", 1);
            echo "</b>&nbsp;&nbsp;";
          ?>
        </td>
        <td>
          <?
            db_input("iCargoIni", 10, 1, true, "text", 1, "onchange=\"js_buscaCargo('ini', false);\"");
            echo "</b>&nbsp;&nbsp;<b>";
            db_ancora("à", "js_buscaCargo('fim', true)", 1);
            echo "</b>&nbsp;&nbsp;";
            db_input("iCargoFim", 10, 1, true, "text", 1, "onchange=\"js_buscaCargo('fim', false);\"");
          ?>
        </td>
      </tr>
      <!-- CARGO POR SELEÇÃO -->
      <tr id="cargoSelecionadosTR">
        <table id="cargoSelecionados" style="display:<?=$sDispCargoSelec;?>;" width="100%">
          <?
            $oFormRhCargo->funcao_gera_formulario();
          ?>
        </table>
      </tr>
    </table>
  </fieldset>
<p align="center">
  <input type="button" name="btnConfiguraSelecao" id="btnConfiguraSelecao" value="Confirmar" onclick="js_configuraSelecao();">
  &nbsp;&nbsp;&nbsp;
  <input type="button" name="btnConfiguraSelecao" id="btnConfiguraSelecao" value="Fechar" onclick="js_fecharSelecao();">
</p>
</form>
</center>
</body>
</html>

<script>

  
  /**
   *  Seleção escolhida pelo usuário
   *
   *  1 - matricula
   *  2 - lotação
   *  3 - cargo
   */
  iTipoSelecao = <?=$iSelecao;?>;

  function js_fecharSelecao() {
  
    if ( iTipoSelecao == 1 ) { 
      parent.oWinAuxMatricula.hide();
    } else if ( iTipoSelecao == 2 ) {
      parent.oWinAuxLotacao.hide();
    } else if ( iTipoSelecao == 3 ) {
      parent.oWinAuxCargo.hide();
    }
  }

  function js_configuraSelecao () {
  
    var sTipoFiltro = $('sTipoFiltro').value;

    if ( sTipoFiltro == "0" ) {
      alert("Selecione um tipo de filtro!");
      return false;
    }
    
    /**
     *  Inicio Verificação Matricula
     */
    if ( iTipoSelecao == 1 ) {
    
      if ( sTipoFiltro == "1" ) { // FILTRO INTERVALO
    
        if ( $('iRegistIni').value == "" || $('iRegistFim').value == "" ) {
          alert("Informe um matrícula inicial e final");
          return false;
        }
      
        parent.$('sConteudoSelecaoIframe').value =  $('iRegistIni').value+" and "+$('iRegistFim').value;
      } else if ( sTipoFiltro == "2" ) {

          var sMatriculasComponente      = $('rhpessoalmatricula');
          var sMatriculasSelecionadas    = "";
          var sDadosMatriculaSelecionada = "";
          
          if ( sMatriculasComponente.length == 0 ) {
            alert ("Selecione ao minímo uma matrícula");
            return false;
          }

          // Varre o Array e configura a o valor para buscar no banco de dados
          for ( var i = 0; i < sMatriculasComponente.length; i++ ) {
            
            if ( sMatriculasSelecionadas == "" && sDadosMatriculaSelecionada == "" ) {
            
              sMatriculasSelecionadas    += sMatriculasComponente[i].value;
              sDadosMatriculaSelecionada += sMatriculasComponente[i].value+"||"+sMatriculasComponente[i].text;
            } else {
            
              sMatriculasSelecionadas    += ","+sMatriculasComponente[i].value;
              sDadosMatriculaSelecionada += ","+sMatriculasComponente[i].value+"||"+sMatriculasComponente[i].text;
            }
          }
          
          parent.$('sConteudoSelecaoIframe').value = sMatriculasSelecionadas; // UTILIZADA NO IN (SQL)
          parent.$('sGuardaSelecionados').value    = sDadosMatriculaSelecionada;
       }

      parent.$('sSelecaoIframe').value     = iTipoSelecao;
      parent.$('sTipoFiltroIframe').value = sTipoFiltro;    
      alert("Seleção configurada com sucesso.");
      parent.oWinAuxMatricula.hide();
      
    } else if ( iTipoSelecao == 2 ) {
    /**
     *  Inicio Verificação Lotacao
     */
     
      if ( sTipoFiltro == "1" ) { // FILTRO INTERVALO
    
        if ( $('iLotacIni').value == "" || $('iLotacFim').value == "" ) {
          alert("Informe um lotação inicial e final");
          return false;
        }
      
        parent.$('sConteudoSelecaoIframe').value =  $('iLotacIni').value+" and "+$('iLotacFim').value;
        
      } else if ( sTipoFiltro == "2" ) {

          var sLotacaoComponente       = $('rhlotacao');
          var sLotacaoSelecionadas     = "";
          var sDadosLotacaoSelecionada = "";
          
          if ( sLotacaoComponente.length == 0 ) {
            alert ("Selecione ao minímo uma lotação.");
            return false;
          } 
          
          // Varre o Array e configura a o valor para buscar no banco de dados
          for ( var i = 0; i < sLotacaoComponente.length; i++ ) {
            
            if ( sLotacaoSelecionadas == "" && sDadosLotacaoSelecionada == "" ) {
            
              sLotacaoSelecionadas     += sLotacaoComponente[i].value;
              sDadosLotacaoSelecionada += sLotacaoComponente[i].value+"||"+sLotacaoComponente[i].text;
            } else {
            
              sLotacaoSelecionadas     += ","+sLotacaoComponente[i].value;
              sDadosLotacaoSelecionada += ","+sLotacaoComponente[i].value+"||"+sLotacaoComponente[i].text;
            }
          }
          
          parent.$('sConteudoSelecaoIframe').value = sLotacaoSelecionadas; // UTILIZADA NO IN (SQL)
          parent.$('sGuardaSelecionados').value    = sDadosLotacaoSelecionada;
       }

      parent.$('sSelecaoIframe').value     = iTipoSelecao;
      parent.$('sTipoFiltroIframe').value = sTipoFiltro;    
      alert("Seleção configurada com sucesso.");
      parent.oWinAuxLotacao.hide();
      
     } else if ( iTipoSelecao == 3 ) {
       /**
        *  Inicio Verificação Cargo 
        */
       if ( sTipoFiltro == "1" ) { // FILTRO INTERVALO
    
        if ( $('iCargoIni').value == "" || $('iCargoFim').value == "" ) {
          alert("Informe um cargo inicial e final");
          return false;
        }
      
        parent.$('sConteudoSelecaoIframe').value =  $('iCargoIni').value+" and "+$('iCargoFim').value;
        
      } else if ( sTipoFiltro == "2" ) {

          var sCargoComponente       = $('rhcargo');
          var sCargoSelecionadas     = "";
          var sDadosCargoSelecionada = ""; 
          
          if ( sCargoComponente.length == 0 ) {
            alert ("Selecione ao minímo um cargo.");
            return false;
          } 
          
          // Varre o Array e configura a o valor para buscar no banco de dados
          for ( var i = 0; i < sCargoComponente.length; i++ ) {
            
            if ( sCargoSelecionadas == "" && sDadosCargoSelecionada == "" ) {
            
              sCargoSelecionadas     += sCargoComponente[i].value;
              sDadosCargoSelecionada += sCargoComponente[i].value+"||"+sCargoComponente[i].text;
            } else {
            
              sCargoSelecionadas     += ","+sCargoComponente[i].value;
              sDadosCargoSelecionada += ","+sCargoComponente[i].value+"||"+sCargoComponente[i].text;
            }
          }
          
          parent.$('sConteudoSelecaoIframe').value = sCargoSelecionadas; // UTILIZADA NO IN (SQL)
          parent.$('sGuardaSelecionados').value    = sDadosCargoSelecionada;
       }

      parent.$('sSelecaoIframe').value     = iTipoSelecao;
      parent.$('sTipoFiltroIframe').value = sTipoFiltro;    
      alert("Seleção configurada com sucesso.");
      parent.oWinAuxCargo.hide();
     }
  }
  
  /**
   *  Funções LOOKUP JS Intervalo Matricula
   */
  function js_buscaMatricula( sTipo,lMostra ) {
  
    if ( sTipo == "ini" ) {
      iMatricula = $('iRegistIni');
    } else if ( sTipo == "fim" ) {
      iMatricula = $('iRegistFim');
    }
    
    if ( lMostra == true ) {
      js_OpenJanelaIframe('','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_preencheMatricula|rh01_regist&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
    } else {
      if ( iMatricula.value != '' ) { 
        js_OpenJanelaIframe('','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+iMatricula.value+'&funcao_js=parent.js_preencheMatricula1&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
      }else{
        iMatricula.value = '';
      }
    }
  }

  function js_preencheMatricula(rh01_regist) {
    
    var iNumeroRegist  = new Number(rh01_regist);
    var iRegistInicial = new Number($('iRegistIni').value);
    
    if ( iNumeroRegist < iRegistInicial ) {
      alert("Informe uma matrícula superior à "+$('iRegistIni').value);
      return false;
    } else {
      iMatricula.value = rh01_regist;
      db_iframe_rhpessoal.hide();
    }
  }
  
  function js_preencheMatricula1( chave1, lErro ) {
  
    if ( lErro ) {
      iMatricula.value = "";
      iMatricula.focus();
    } else {
    
      var iNumeroRegist  = new Number(iMatricula.value);
      var iRegistInicial = new Number($('iRegistIni').value);
      
      if ( iNumeroRegist < iRegistInicial ) {
        alert("Informe uma matrícula superior à "+$('iRegistIni').value);
      }
    }
  }
  // FIM CONFIGURAÇÂO MATRICULA

  /**
   *  Funções LOOKUP JS Intervalo Lotação
   */
  function js_buscaLotacao( sTipo,lMostra ) {
  
    if ( sTipo == "ini" ) {
      iLotacao = $('iLotacIni');
    } else if ( sTipo == "fim" ) {
      iLotacao = $('iLotacFim');
    }
    
    if ( lMostra == true ) {
      js_OpenJanelaIframe('','db_iframe_rhlotacao','func_rhlota.php?funcao_js=parent.js_preencheLotacao|r70_codigo&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
    } else {
      if ( iLotacao.value != '' ) { 
        js_OpenJanelaIframe('','db_iframe_rhlotacao','func_rhlota.php?pesquisa_chave='+iLotacao.value+'&funcao_js=parent.js_preencheLotacao1&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
      }else{
        iLotacao.value = '';
      }
    }
  }

  function js_preencheLotacao( r70_codigo ) {
    
    var iCodLotacao    = new Number(r70_codigo);
    var iCodLotacaoIni = new Number($('iLotacIni').value);
    
    if ( iCodLotacao < iCodLotacaoIni ) {
      alert("Informe uma lotação superior à "+$('iLotacIni').value);
      return false;
    } else {
      iLotacao.value = r70_codigo;
      db_iframe_rhlotacao.hide();
    }
  }
  
  function js_preencheLotacao1( chave1, lErro ) {
  
    if ( lErro ) {
      iLotacao.value = "";
      iLotacao.focus();
    } else {
    
      var iCodLotacao  = new Number(iLotacao.value);
      var iCodLotacaoIni = new Number($('iLotacIni').value);
      
      if ( iCodLotacao < iCodLotacaoIni ) {
        alert("Informe uma lotação superior à "+$('iLotacIni').value);
      }
    }
  }

  /**
   *  Funções LOOKUP JS Intervalo Cargo
   */

  function js_buscaCargo( sTipo,lMostra ) {
  
    if ( sTipo == "ini" ) {
      iCargo = $('iCargoIni');
    } else if ( sTipo == "fim" ) {
      iCargo = $('iCargoFim');
    }
    
    if ( lMostra == true ) {
      js_OpenJanelaIframe('','db_iframe_rhcargo','func_rhfuncao.php?funcao_js=parent.js_preencheCargo|rh37_funcao&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
    } else {
      if ( iCargo.value != '' ) { 
        js_OpenJanelaIframe('','db_iframe_rhcargo','func_rhfuncao.php?pesquisa_chave='+iCargo.value+'&funcao_js=parent.js_preencheCargo1&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
      }else{
        iCargo.value = '';
      }
    }
  }

  function js_preencheCargo( rh37_funcao ) {
    
    var iCodCargo    = new Number(rh37_funcao);
    var iCodCargoIni = new Number($('iCargoIni').value);
    
    if ( iCodCargo < iCodCargoIni ) {
      alert("Informe um cargo superior à "+$('iCargoIni').value);
      return false;
    } else {
      iCargo.value = rh37_funcao;
      db_iframe_rhcargo.hide();
    }
  }
  
  function js_preencheCargo1( chave1, lErro ) {
  
    if ( lErro ) {
      iCargo.value = "";
      iCargo.focus();
    } else {
    
      var iCodCargo    = new Number(iCargo.value);
      var iCodCargoIni = new Number($('iCargoIni').value);
      
      if ( iCodCargo < iCodCargoIni ) {
        alert("Informe uma lotação superior à "+$('iCargoIni').value);
      }
    }
  }


  /**
   *  Configurações dos observes dentro das windowAux de cada opção (Matricula, Lotacao, Cargo)
   */  
  $('sTipoFiltro').observe('change',
    function (event) {
      
      var sTipoFiltro = $('sTipoFiltro').value;
      
      /**
       *  Filtros Matricula
       */
      if ( iTipoSelecao == 1 && sTipoFiltro == "1" ) { // Filtro Matricula      
        $('matriculaIntervalo').style.display    = '';
        $('matriculaSelecionados').style.display = 'none';
      } else if ( iTipoSelecao == 1 && sTipoFiltro == "2" ) { // Filtro Intervalo      
        $('matriculaIntervalo').style.display    = 'none';
        $('matriculaSelecionados').style.display = '';
      } else {
        $('matriculaIntervalo').style.display    = 'none';
        $('matriculaSelecionados').style.display = 'none';
      }
      
      /**
       *  Filtros Lotacao
       */
      if ( iTipoSelecao == 2 && sTipoFiltro == "1" ) {       
        $('lotacaoIntervalo').style.display    = '';
        $('lotacaoSelecionados').style.display = 'none';
      } else if ( iTipoSelecao == 2 && sTipoFiltro == "2" ) {       
        $('lotacaoIntervalo').style.display    = 'none';
        $('lotacaoSelecionados').style.display = '';
      } else {
        $('lotacaoIntervalo').style.display    = 'none';
        $('lotacaoSelecionados').style.display = 'none';
      }
      
      /**
       *  Filtros Cargo
       */
      if ( iTipoSelecao == 3 && sTipoFiltro == "1" ) {
        $('cargoIntervalo').style.display    = '';
        $('cargoSelecionados').style.display = 'none';
      } else if ( iTipoSelecao == 3 && sTipoFiltro == "2" ) {
        $('cargoIntervalo').style.display    = 'none';
        $('cargoSelecionados').style.display = '';
      } else {
        $('cargoIntervalo').style.display    = 'none';
        $('cargoSelecionados').style.display = 'none';
      }
    }
  );
  
  
  
  /**
   *  Função js_insereDadosSelecionados()
   *  Criada apenas para preencher o componente cl_arquivo_auxiliar com
   *  os dados já preenchidos pelo usuário
   */
  function js_insereDadosSelecionados ( sNomeObjeto, sValue, sDescricao, iContador ) {
    
    var oDocOptions = document.getElementById(sNomeObjeto);
        oDocOptions.options[iContador] = new Option (sDescricao,sValue);
  }
</script>


<?

/**
 * PREENCHE DADOS DO COMPONENTE MONTADO COM A CLASSE cl_arquivoauxiliar()
 */
if ( $iSelecao == 1 ) {
  
  if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "2" ) {

    $aExplodeSelecionados = explode(",", $sGuardaSelecionados);
    $iContador = 0;
    foreach ( $aExplodeSelecionados as $sDadosMatricula ) {
      
      $aExplodeMatricula = explode("||", $sDadosMatricula);
      echo "<script>
              js_insereDadosSelecionados ('rhpessoalmatricula', '{$aExplodeMatricula[0]}', '{$aExplodeMatricula[1]}', {$iContador});
            </script>";
      $iContador++;
    }
  }
} else if ( $iSelecao == 2 ) {

  if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "2" ) {

    $aExplodeSelecionados = explode(",", $sGuardaSelecionados);
    $iContador = 0;
    foreach ( $aExplodeSelecionados as $sDadosMatricula ) {
      
      $aExplodeMatricula = explode("||", $sDadosMatricula);
      echo "<script>
              js_insereDadosSelecionados ('rhlotacao', '{$aExplodeMatricula[0]}', '{$aExplodeMatricula[1]}', {$iContador});
            </script>";
      $iContador++;
    }
  }
} else if ( $iSelecao == 3 ) {
  
  if ( $sTipoFiltroIframe != "" && $sTipoFiltroIframe == "2" ) {

    $aExplodeSelecionados = explode(",", $sGuardaSelecionados);
    $iContador = 0;
    foreach ( $aExplodeSelecionados as $sDadosMatricula ) {
      
      $aExplodeMatricula = explode("||", $sDadosMatricula);
      echo "<script>
              js_insereDadosSelecionados ('rhcargo', '{$aExplodeMatricula[0]}', '{$aExplodeMatricula[1]}', {$iContador});
            </script>";
      $iContador++;
    }
  }
}


?>