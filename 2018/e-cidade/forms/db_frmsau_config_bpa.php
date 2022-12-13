<?php
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

//MODULO: Ambulatorial
$oSauConfig->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("s103_c_bpaorgao");
$oRotulo->label("s103_c_bpasigla");
$oRotulo->label("s103_c_bpaibge");
?>
<div class="container">
  <form name="form_bpa" method="post" action="">
    <fieldset>
      <legend>BPA</legend>
      <table>
        <tr>
          <td>
            <label class="bold">Secretaria Destino:</label>
          </td>
          <td>
            <?php
            db_input( 's103_c_bpasecrdestino', 25, @$Is103_c_bpaorgao, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label class="bold">Sigla:</label>
          </td>
          <td>
            <?php
            db_input( 's103_c_bpasigla', 25, @$Is103_c_bpasigla, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label class="bold">Código do IBGE:</label>
          </td>
          <td>
            <?php
            db_input( 's103_c_bpaibge', 25, @$Is103_c_bpaibge, true, 'text', $db_opcao );
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
$('s103_c_bpasecrdestino').className = 'field-size7';
$('s103_c_bpasigla').className       = 'field-size7';
$('s103_c_bpaibge').className        = 'field-size7';
</script>