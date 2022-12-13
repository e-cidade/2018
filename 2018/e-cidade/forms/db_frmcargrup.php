<?php
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

$clcargrup->rotulo->label();
$sNomeBotao = ($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"));
$sDisabled  = ($db_botao==false?"disabled":"");

?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Grupo de Características</legend>

      <table>
        <tr>
          <td nowrap title="<?=$Tj32_grupo?>">
            <label for="j32_grupo"><?=$Lj32_grupo?></label>
          </td>
          <td> 
            <?php
              db_input('j32_grupo',4,$Ij32_grupo,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj32_descr?>">
            <label for="j32_descr"><?=$Lj32_descr?></label>
          </td>
          <td> 
            <?php
              db_input('j32_descr',40,$Ij32_descr,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj32_tipo?>">
            <label for="j32_tipo"><?=$Lj32_tipo?></label>
          </td>
          <td> 
            <?php
              $opcoesSelect = array('L'=>'Lote','F'=>'Face','C'=>'Construção','I'=>'Itbi','O'=>'Obras','A'=>'Água');
              db_select('j32_tipo', $opcoesSelect, true, $db_opcao, "");
            ?>
          </td>
        </tr>        
      </table>
    </fieldset>

    <input name="db_opcao" type="submit" id="db_opcao" value="<?=$sNomeBotao?>" <?=$sDisabled?>>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</div>
<script>
function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_preenchepesquisa|j32_grupo','Pesquisa',true);
}

function js_preenchepesquisa(chave) {

  db_iframe_cargrup.hide();

  <?php
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>