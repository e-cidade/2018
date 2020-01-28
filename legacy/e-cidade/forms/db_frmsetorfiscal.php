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

//MODULO: cadastro
$clsetorfiscal->rotulo->label();
if($db_opcao==1){
  $db_action="cad1_setorfiscal004.php";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="cad1_setorfiscal005.php";
}else if($db_opcao==3||$db_opcao==33){
  $db_action="cad1_setorfiscal006.php";
}
?>

<form name="form1" method="post" action="<?=$db_action?>">
  <center>
    <fieldset style="width: 500px;">
      <legend class="bold">Setor Fiscal</legend>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tj90_codigo?>">
            <label for="j90_codigo">
            <?=@$Lj90_codigo?>
            </label>
          </td>
          <td>
            <?
            db_input('j90_codigo',10,$Ij90_codigo,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj90_descr?>">
            <label for="j90_descr">
            <?=@$Lj90_descr?>
            </label>
          </td>
          <td>
            <?
            db_input('j90_descr',40,$Ij90_descr,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>

      </table>
    </fieldset>
  </center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_setorfiscal','db_iframe_setorfiscal','func_setorfiscal.php?funcao_js=parent.js_preenchepesquisa|j90_codigo','Pesquisa',true,'0','1','775','390');
  }
  function js_preenchepesquisa(chave){
    db_iframe_setorfiscal.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
</script>