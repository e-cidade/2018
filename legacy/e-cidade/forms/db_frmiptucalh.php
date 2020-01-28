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

//MODULO: cadastro
$cliptucalh->rotulo->label();
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Histórico de Cálculo do IPTU</legend>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tj17_codhis?>">
          <label for="j17_codhis"><?=@$Lj17_codhis?></label>
        </td>
        <td> 
          <? db_input('j17_codhis',10,$Ij17_codhis,true,'text',$db_opcao,"") ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj17_descr?>">
          <label for="j17_descr"><?=@$Lj17_descr?></label>
        </td>
        <td> 
          <? db_input('j17_descr',40,$Ij17_descr,true,'text',$db_opcao,"") ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_iptucalh','func_iptucalh.php?funcao_js=parent.js_preenchepesquisa|j17_codhis','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_iptucalh.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>