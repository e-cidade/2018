<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cfautent_classe.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="Expires" CONTENT="0" />
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewTipoFiltrosFolha.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor=#CCCCCC>

<form class="container" action="" method="POST" style="width: 425px">
  <fieldset>
    <legend> Servidores Afastados</legend>
    <table class="form-container">
      <tr>
        <td colspan="2">
          <div id="ContainerTipoResumo"></div>
        </td>
      </tr>
      <tr>
        <td>
          <label>Afastados Entre:</label>
        </td>
        <td>
          <?php
            db_inputdata('iAfastadosInicio',null,null,null,true,'text',1);
          ?>
          <span> <label>e</label> </span>
          <?php
            db_inputdata('iAfastadosFim',null,null,null,true,'text',1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Retornados Entre:</label>
        </td>
        <td>
          <?php
            db_inputdata('iRetornadosInicio',null,null,null,true,'text',1);
          ?>
          <span> <label>e</label> </span>
          <?php
            db_inputdata('iRetornadosFim',null,null,null,true,'text',1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Lançados Entre:</label>
        </td>
        <td>
          <?php
            db_inputdata('iLancadosInicio',null,null,null,true,'text',1);
          ?>
          <span> <label>e</label> </span>
          <?php
            db_inputdata('iLancadosFim',null,null,null,true,'text',1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Afastamento:</label>
        </td>
        <td>
        <?php
            $aAfastamentos = array(0 => '0 - Todos',
                                   2 => '2 - Sem Remuneração',
                                   3 => '3 - Acidente de Trabalho',
                                   4 => '4 - Serviço Militar',
                                   5 => '5 - Licença Gestante',
                                   6 => '6 - Doença + de 15 dias',
                                   7 => '7 - Sem Vencimentos, Sem Ônus',
                                   8 => '8 - Doença + de 30 dias',
                                  );
             db_select('iAfastamentos',$aAfastamentos,true,1);
            ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Emitir já retornados: </label>
        </td>
        <td>
          <?php
            $aEmiteRetornados = array(0 => 'Não', 1 => 'Sim');
            db_select('iEmiteRetornados', $aEmiteRetornados, true, 1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Ordem: </label>
        </td>
        <td>
          <?php
            $aOrdem = array('a'=>'Alfabética',
                            'n'=>'Numérica',
                            'f'=>'Afastamento',
                            'r'=>'Retorno','l'=>'Lançamento'
                           );
            db_select('sOrdem', $aOrdem, true, 1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Com quebra: </label>
        </td>
        <td>
          <?php
            $aQuebra = array(0 => 'Não', 1 => 'Sim');
            db_select('iQuebra', $aQuebra, true, 1);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" onclick="return js_valida()" value="Processar">
</form>

  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>

<script>

/**
 * Monta os componentes para o formulario
 */
var oTipoFiltrosFolha            = new DBViewFormularioFolha.DBViewTipoFiltrosFolha(<?=db_getsession("DB_instit")?>);
oTipoFiltrosFolha.lExibeFieldset = false;
oTipoFiltrosFolha.sInstancia     = 'oTipoFiltrosFolha';
oTipoFiltrosFolha.aTipos         = [0,1,2,3,5];

oTipoFiltrosFolha.show($('ContainerTipoResumo'));


function js_relatorio() {

  var oQuery = {};
  oQuery.iTipoRelatorio = $F('oCboTipoRelatorio');
  /**
   * Verifica se o tipo escolhido foi intervalo
   */
  var oTipoFiltro    = $F('oCboTipoFiltro');

  if (oTipoFiltro == 1) {

    oQuery.iIntervaloInicial = $F('InputIntervaloInicial');
    oQuery.iIntervaloFinal   = $F('InputIntervaloFinal');
  }

  /**
   * Verifica se o tipo escolhido foi seleção
   */
  if (oTipoFiltro == 2 ) {

    var aSelecionados = [];
    var oTipoFiltros = oTipoFiltrosFolha.getLancadorAtivo().getRegistros();

    /**
     * Percorre os itens selecionados no lancador
     */
    oTipoFiltros.each (function(oFiltro, iIndice) {
      aSelecionados[iIndice] = oFiltro.sCodigo;
    });

    oQuery.iRegistros = aSelecionados;
  }

  oQuery.iAfastadosInicio  = $F('iAfastadosInicio');
  oQuery.iAfastadosFim     = $F('iAfastadosFim');
  oQuery.iRetornadosInicio = $F('iRetornadosInicio');
  oQuery.iRetornadosFim    = $F('iRetornadosFim');
  oQuery.iLancadosInicio   = $F('iLancadosInicio');
  oQuery.iLancadosFim      = $F('iLancadosFim');
  oQuery.iAfastamentos     = $F('iAfastamentos');
  oQuery.iEmiteRetornados  = $F('iEmiteRetornados');
  oQuery.sOrdem            = $F('sOrdem');
  oQuery.iQuebra           = $F('iQuebra');

  jan = window.open('pes2_afastamentos002.php?json='+Object.toJSON(oQuery),'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');

  jan.moveTo(0,0);

  return false;
}

function js_valida(){

  if ($F("oCboTipoRelatorio") != "0") {

    if ( $F('oCboTipoFiltro') == 1 ){

      if ( $F('InputIntervaloInicial') == '' || $F('InputIntervaloFinal') == '' ) {

        alert('Nenhum intervalo informado.');
        return false;
      }

      if( parseInt( $F('InputIntervaloInicial') ) >  parseInt( $F('InputIntervaloFinal') )  ) {

        alert('Intervalo inválido!');
        return false;
      }
    }

    if ( $F('oCboTipoFiltro') == 2 ){

      var oLancadorSelecionado = oTipoFiltrosFolha.getLancadorAtivo().getRegistros();
      if (oLancadorSelecionado.length == 0) {

        alert('Nenhum registro selecionado.');
        return false
      }
    }
  }
  js_relatorio();

  return true;
}
</script>
</html>