<?
/*
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

//MODULO: Ambulatorial
$oSauConfig->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("s103_c_lancafaa");
$oRotulo->label("s103_v_msgagenda");
$oRotulo->label("s103_c_agendaproc");
$oRotulo->label("s103_c_emitircomprovante");
$oRotulo->label("s103_i_validaagenda");
$oRotulo->label("s103_c_agendaprog");
?>
<div class="container">
  <form name="form_agendamento" method="post" action="">
    <fieldset>
      <legend>Agendamento</legend>
      <table>
        <tr>
          <td nowrap title='Agendamento'>
            <b>Agendamento</b>
          </td>
          <td nowrap >
            <?php
            $x = array( 'I' => 'Habilitado', 'L' => 'Desabilitado' );
            db_select( 's103_c_lancafaa', $x, true, $db_opcao, "" );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts103_v_msgagenda?>">
            <?=$Ls103_v_msgagenda?>
          </td>
          <td nowrap >
            <?php
            db_input( 's103_v_msgagenda', 90, $Is103_v_msgagenda, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts103_c_agendaproc?>">
            <?=$Ls103_c_agendaproc?>
          </td>
          <td nowrap >
            <?php
            $x = array( 'S' => 'SIM', 'N' => 'NÃO' );
            db_select( 's103_c_agendaproc', $x, true, $db_opcao, "" );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts103_c_emitircomprovante?>">
            <?=$Ls103_c_emitircomprovante?>
          </td>
          <td nowrap >
            <?php
            $x = array( 'S' => 'SIM', 'N' => 'NÃO' );
            db_select( 's103_c_emitircomprovante', $x, true, $db_opcao, "" );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts103_i_validaagenda?>">
            <?=$Ls103_i_validaagenda?>
          </td>
          <td nowrap >
            <?php
            db_input( 's103_i_validaagenda', 10, $Is103_i_validaagenda, true, 'text', $db_opcao, "" );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts103_c_agendaprog?>">
            <?=$Ls103_c_agendaprog?>
          </td>
          <td nowrap >
           <?php
           $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
           db_select( 's103_c_agendaprog', $x, true, $db_opcao, "" );
           ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts103_procsemcbo?>">
            <?=$Ls103_procsemcbo?>
          </td>
          <td nowrap >
           <?php
           $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
           db_select( 's103_procsemcbo', $x, true, $db_opcao, "" );
           ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="submit"
           value="<?=( $db_opcao == 1 ? 'Incluir' : 'Alterar' )?>"
           name="<?=( $db_opcao == 1 ? 'incluir' : 'alterar' )?>"
           style="margin-top: 10px;">
  </form>
</div>
<script>
$('s103_c_lancafaa').className          = 'field-size3';
$('s103_c_agendaproc').className        = 'field-size3';
$('s103_c_emitircomprovante').className = 'field-size3';
$('s103_i_validaagenda').className      = 'field-size3';
$('s103_c_agendaprog').className        = 'field-size3';
$('s103_procsemcbo').className          = 'field-size3';
$('s103_v_msgagenda').className         = 'field-size9';
</script>