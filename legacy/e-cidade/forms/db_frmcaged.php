<?php

/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form action="" method="post" name="form1" class="container">
  <fieldset>
    <legend>Emissão CAGED</legend>
    <table class="form-container">
      <tr>
        <td>
          <label>Data de Referência:</label>
        </td>
        <td>
          <?php
          $sData = date('dmY'); // Now
          if (!isset($dataref_dia)) {
            $dataref_dia = substr($sData, 0, 2); // Day
          }

          if (!isset($dataref_mes)) {
            $dataref_mes = substr($sData, 2, 2); // Month
          }

          if (!isset($dataref_ano)) {
            $dataref_ano = substr($sData, 4, 4); // Year
          }

          db_inputdata('dataref', $dataref_dia, $dataref_mes, $dataref_ano, true, 'text', $db_opcao, 'style="width: 100px;"');
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Autorização:</label>
        </td>
        <td>
          <?php
          $autorizacao = 0;
          db_input('autorizacao', 7, 1, true, 'text', 1, 'style="width: 71px;"');
          db_input('digautoriza', 1, 1, true, 'text', 1, 'style="width: 25px;"');
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Alteração:</label>
        </td>
        <td>
          <?php
          $alteracao     = 1;
          $arr_alteracao = array(
            1 => "Nada a alterar",
            2 => "Alterar dados cadastrais",
            3 => "Encerramento de atividades"
          );

          db_select('alteracao', $arr_alteracao, true, 1, '');
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label>Primeira declaração:</label>
        </td>
        <td>
          <?php
          $primeiradeclaracao     = 2;
          $arr_primeiradeclaracao = array(
            1 => "Primeira declaração",
            2 => "Já informou"
          );

          db_select('primeiradeclaracao', $arr_primeiradeclaracao, true, 1, '');
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset class="separator">
            <legend>Contato</legend>
            <table class="subtable">
              <tr>
                <td>
                  <label>DDD / Telefone:</label>
                </td>
                <td>
                  <?php db_input('ddd',     4, 1, true, 'text', 1); ?>
                  <?php db_input('codarea', 4, 1, true, 'text', 1); ?>
                  -
                  <?php db_input('telefone',4, 1, true, 'text', 1); ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label>Ramal:</label>
                </td>
                <td>
                  <?php db_input('ramal', 4, 1, true, 'text', 1); ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="submit" name="gerar" id="gerar" value="Gerar CAGED" onclick="return js_processa();">
</form>
<script>
  function js_validaDataEmissao() {

    var oNow = new Date();
    var oFrm = new Date(document.form1.dataref_ano.value, document.form1.dataref_mes.value -1, document.form1.dataref_dia.value);

    if (oFrm.getTime() > oNow.getTime()) {

      alert('A data informada não pode ser maior que a atual.');
      return false;
    }

    return true;
  }

  function js_processa() {

    if (js_validaDataEmissao()) {

      js_tabulacaoforms('form1', 'dataref', true, 1, 'dataref', true);
      return true;
    } else {
      return false;
    }
  }
</script>