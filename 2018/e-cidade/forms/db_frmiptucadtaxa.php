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

$cliptucadtaxa->rotulo->label();
$sNameBotao  = ($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"));
$sValueBotao = ($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"));
$sDisabled   = ($db_botao==false?"disabled":"");

if ($db_opcao == 1) {
  $db_action="cad1_iptucadtaxa004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action="cad1_iptucadtaxa005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action="cad1_iptucadtaxa006.php";
}
?>
<div class="container">
  <form name="form1" method="post" action="<?=$db_action?>">
    <fieldset>
      <legend>Dados da Taxa</legend>

      <table>
        <tr>
          <td nowrap title="<?=$Tj07_iptucadtaxa?>">
            <label for="j07_iptucadtaxa"><?=$Lj07_iptucadtaxa?></label>
          </td>
          <td> 
            <?php
              db_input('j07_iptucadtaxa',10,$Ij07_iptucadtaxa,true,'text',3,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj07_descr?>">
            <label for="j07_descr"><?=$Lj07_descr?></label>
          </td>
          <td> 
            <?php
              db_input('j07_descr',40,$Ij07_descr,true,'text',$db_opcao,"");
            ?>
         </td>
        </tr>
      </table>
    </fieldset>

    <input name="<?=$sNameBotao?>" type="submit" id="db_opcao" value="<?=$sValueBotao?>" <?=$sDisabled?>>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</div>
<script>
function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxa','db_iframe_iptucadtaxa','func_iptucadtaxa.php?funcao_js=parent.js_preenchepesquisa|j07_iptucadtaxa','Pesquisa',true,'0');
}

function js_preenchepesquisa(chave) {
  
  db_iframe_iptucadtaxa.hide();
  
  <?php
  if($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>