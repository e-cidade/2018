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
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("dbforms/db_classesgenericas.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_utils.php");
  require_once("classes/db_declaracaoquitacao_classe.php");
  
  $clDeclaracaoQuitacao = new cl_declaracaoquitacao();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load('scripts.js');
      db_app::load('estilos.css');
    ?>
  </head>
  <body bgcolor=#CCCCCC>
    <form name="form1" method="post" style="margin-top: 40px;">
      <fieldset style="width: 500px; margin: 0 auto;">
        <legend>
          <strong>Declarações de Quitação de Débitos</strong>
        </legend>
        <table width="450" style="margin-top: 10px;">
          <tr >
            <td title="Data Inicial" align="right" >
              <strong>Data Inicial:</strong>
            </td>
            <td>
              <?
                db_inputdata('datainicial', null, null, null, true, 'text', 1);
              ?>
            </td>
            <td title="Data Final">
              <strong>Data Final:</strong>
            </td>
            <td>
              <?
                db_inputdata('datafinal', null, null, null, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr>
            <td align="right"  title="Situação das Declarações">
              <strong>Situação:</strong>
              
            </td>
            <td colspan="2">
            <?
                $aStatus = array('0' => 'Todas',
                                 '1' => 'Ativas',
                                 '2' => 'Anuladas',
                                 '3' => 'Anuladas Automaticamente');
                db_select('status', $aStatus, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" title="Origem das Declarações">
              <strong>Origem:</strong>
            </td>
            <td>
              <?
                $aOrigens = array(''           => 'Selecione',
                                  'somentecgm' => 'Somente CGM',
                                  'cgm'        => 'CGM Geral',
                                  'matric'     => 'Matrícula',
                                  'inscr'      => 'Inscrição');
                db_select('origem', $aOrigens, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" title="Exercício das Declarações">
              <strong>Exercício:</strong>
            </td>
            <td>
                          <?
                $sSqlDeclaracaoQuitacao = $clDeclaracaoQuitacao->
                                            sql_query_file(null, "DISTINCT ar30_exercicio", "ar30_exercicio", null);
                $rsDeclaracaoQuitacao   = $clDeclaracaoQuitacao->sql_record($sSqlDeclaracaoQuitacao);
              ?>
              <select name="exercicio" >
                <option value="">Todas</option>
                <?
                  for($i = 0; $i < $clDeclaracaoQuitacao->numrows; $i++) {
                  
                    $oDeclaracaoquitacao = db_utils::fieldsMemory($rsDeclaracaoQuitacao, $i);
                    echo "<option value=\"$oDeclaracaoquitacao->ar30_exercicio\">$oDeclaracaoquitacao->ar30_exercicio</option>";
                  } 
                ?>
              </select>
            </td>
          </tr>
          <tr>
            <td align="Right" title="Tipo">
              <strong>Tipo:</strong>
            </td>
            <td>
            <?
                $aTipo = array(''  => 'Selecione',
                               'A' => 'Analítico',
                               'S' => 'Sintético');
                db_select('tipo', $aTipo, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center"><br/>
              <input type="submit" name="imprimir" value="Imprimir" onclick="return js_imprimir();"/>
            </td>
          </tr>
        </table>
      </fieldset>
    </form>
    <?
      db_menu(db_getsession("DB_id_usuario"),
              db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),
              db_getsession("DB_instit"));
    ?>
  </body>
</html> 

<script type="text/javascript">

  function js_imprimir() {
    
    var dDataInicial = document.form1.datainicial.value;
    var dDataFinal   = document.form1.datafinal.value;
    var sStatus      = document.form1.status.value;
    var sOrigem      = document.form1.origem.value;
    var sExercicio   = document.form1.exercicio.value;
    var sTipo        = document.form1.tipo.value;

    if(sOrigem == '') {
  	  alert('Origem não informada.');
  	  return false;
    }  

    if(sTipo == '') {
    	  alert('Tipo não informado.');
    	  return false;
    } 
          
    jan = window.open('arr2_rellistadqd002.php?datainicial=' + dDataInicial+'&datafinal=' + dDataFinal + '&status='
    	                + sStatus + '&origem=' + sOrigem + '&exercicio=' + sExercicio + '&tipo=' + sTipo,
    	                '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) +
    	                ',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  
  }
</script>