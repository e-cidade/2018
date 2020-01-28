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
  require_once("libs/db_utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_sql.php");
  require_once("libs/db_app.utils.php");
  require_once("dbforms/db_classesgenericas.php");
  
  $iInstit     = db_getsession("DB_instit");
  $sListaTipo  = "5,6,13,15,18";
  $dtDataAtual = date('Y-m-d',db_getsession('DB_datausu'));
?>

<html>
<head>
<?php 
  db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js, dbtextField.widget.js");
?>
</head>
<body bgcolor="#CCCCCC">

<center>

<form>

<fieldset style="margin-top: 50px; width: 550px">
  <legend>
    <strong>Consolidação de Dados:</strong>
  </legend>

  <fieldset>
    <legend>
      <strong>Filtragem de Datas:</strong>
    </legend>
  
    <table align="left" cellspacing="5" cellpadding="2">
      <tr>
        <td>
          <strong>Período inicial:</strong>
        </td>
        <td>
          <?php db_inputdata('dPeriodoInicial', null, null, null, true, 'text', 1); ?>
        </td>    
        <td>
          <strong>Período final:</strong>
        </td>
        <td>
          <?php db_inputdata('dPeriodoFinal', null, null, null, true, 'text', 1); ?>
        </td>
      </tr>    
      <tr>
        <td><strong>Data do cálculo:</strong></td>
        <td>
          <?php
            $aDatas    = array();
            $iNroDatas = 0;
                                                                                                                                                                                                             
            $sSqlDataDebitos  = "select datadebitos as k115_data                                                                                                                                             ";
            $sSqlDataDebitos .= "               from (select (select k22_data                                                                                                                                ";
            $sSqlDataDebitos .= "                               from debitos                                                                                                                                 ";
            $sSqlDataDebitos .= "                                    inner join arretipo on arretipo.k00_tipo = debitos.k22_tipo                                                                             ";
            $sSqlDataDebitos .= "                              where k22_instit = {$iInstit}                                                                                                                 ";
            $sSqlDataDebitos .= "                                and k03_Tipo in ({$sListaTipo})                                                                                                             ";
            $sSqlDataDebitos .= "                                and k22_data = ((select min(k22_data) as k22_data                                                                                           ";
            $sSqlDataDebitos .= "                                                   from debitos                                                                                                             ";
            $sSqlDataDebitos .= "                                                  where k22_instit = {$iInstit}) + x.id)  limit 1) as datadebitos                                                           ";
            $sSqlDataDebitos .= "                       from generate_series(0, cast((extract(year from '{$dtDataAtual}'::date) - extract(year from (select min(k22_data) as k22_data                        ";
            $sSqlDataDebitos .= "                                                                                                                  from debitos                                              ";
            $sSqlDataDebitos .= "                                                                                                                 where k22_instit = {$iInstit} ) ) + 1) * 365 as integer )  ";
            $sSqlDataDebitos .= "                                                                     ) as x (id)                                                                                            ";
            $sSqlDataDebitos .= "                   ) as y                                                                                                                                                   ";
            $sSqlDataDebitos .= "              where datadebitos is not null order by datadebitos desc                                                                                                       ";
  
           $rsDataDebitos    = pg_query($sSqlDataDebitos);
           $iNroDatas        = pg_num_rows($rsDataDebitos);
           
           $aDatas[''] = 'SELECIONE';
           for( $iInd=0; $iInd < $iNroDatas; $iInd++ ){
             $oDataDebitos = db_utils::fieldsMemory($rsDataDebitos, $iInd);
             $aDatas[$oDataDebitos->k115_data] = db_formatar($oDataDebitos->k115_data, 'd');
           }
          
          db_select("dDataCalculo", $aDatas, true, 1, "onChange=''");
          
          ?>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
    </table>
  
  </fieldset>

  <fieldset><legend><strong>Seleção dos Relatórios:</strong></legend>
  
    <div id="ctnGridRelatorios"></div>
    
  </fieldset>
  
</fieldset>
<br><br>
<center>
  <input type="button" value="Processar" onclick="js_processar()" />
</center>

</form>

</center>

<script>

var aCamposGrid = new Array();

js_gridRelatorios();
  
function js_gridRelatorios() {

  oGridRelatorios                   = new DBGrid('datagridRelatorios');
  oGridRelatorios.sName             = 'datagridRelatorios';
  oGridRelatorios.nameInstance      = 'oGridRelatorios';
  oGridRelatorios.setCheckbox       (0);
  
  oGridRelatorios.allowSelectColumns(true);

  oGridRelatorios.setCellWidth      ( new Array('0', '100%') );
  oGridRelatorios.setCellAlign      ( new Array('center', 'left') );
  oGridRelatorios.setHeader         ( new Array('Código', 'Relatório') );
    
  oGridRelatorios.show              ( $('ctnGridRelatorios') );
  oGridRelatorios.clearAll          (true);

  var aLinha = new Array();

  
  aLinha[0]  = ['1', 'Descontos concedidos por regras'];
  aLinha[1]  = ['2', 'Débitos cancelados'];
  aLinha[2]  = ['3', 'Prescrição de dívida'];
  aLinha[3]  = ['4', 'Inscrição em dívida'];
  aLinha[4]  = ['5', 'Pagamentos geral'];
  aLinha[5]  = ['6', 'Descontos concedidos (cota única)'];
  aLinha[6]  = ['7', 'Resumo geral da dívida'];

  for (var i = 0; i < 7; i++) {
    oGridRelatorios.addRow(aLinha[i], true, null, null, null);  
    oGridRelatorios.aRows[i].aCells[1].lDisplayed = false;
  }
  
  oGridRelatorios.renderRows();
  
  for ( var iLinhaGrid = 0; iLinhaGrid < oGridRelatorios.aRows.length; iLinhaGrid++) {

    var oLinhaGrid = oGridRelatorios.aRows[iLinhaGrid];
    var oCheckBox = $(oLinhaGrid.sId).children[0].children[0];

    aCamposGrid.push(oCheckBox);
  }
    
}

sUrl = 'arr2_gerardiscorelatorioconsolidado.RPC.php'; 

function js_processar() {

  
  var oParam                = new Object();
                            
  oParam.sExec              = 'gerarRelatorio';
  oParam.dPeriodoInicial    = $F('dPeriodoInicial');
  oParam.dPeriodoFinal      = $F('dPeriodoFinal');
  oParam.dDataCalculo       = $F('dDataCalculo');
  
  oParam.aSelecionados = new Array();

    
  for (var iIndice = 0; iIndice < oGridRelatorios.getSelection().length; iIndice++) {
    
    oRelatorio = new Object();
    
    oRelatorio.iCodigoRelatorio   = oGridRelatorios.getSelection()[iIndice][0];
    
    oParam.aSelecionados[iIndice] = oRelatorio;
      
  }
  
  if (oGridRelatorios.getSelection().length == 0 ) {
    alert('Nenhum relatório selecionado para geração.');
    return false;      
  }  
  
  if (!confirm('Todos os dados do relatório salvo anteriormente serão perdidos. Deseja continuar??')) {
    return false;
  }  
  
  js_divCarregando('Gerando Relatório em Disco, aguarde.', 'msgbox');

  var oAjax = new Ajax.Request(sUrl,
                              {
                               method    : 'POST',
                               parameters: 'json='+Object.toJSON(oParam), 
                               onComplete: js_confirma
                              });
  
}

function js_confirma(oAjax){

  js_removeObj('msgbox');
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.iStatus == 1) {
    
    sMensagem = "Relatório Gerado em Disco com Sucesso!";

    alert(sMensagem);
    
  } else { 
    
    alert(oRetorno.sMensagem.urlDecode());
    return false;
    
  }
  
}
</script>

<?php
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit"));
?>
</body>
</html>