<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: orcamento
$clorcsuplementacaoparametro->rotulo->label();
      if($db_opcao==1){
 	   $db_action="orc1_orcsuplementacaoparametro004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="orc1_orcsuplementacaoparametro005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="orc1_orcsuplementacaoparametro006.php";
      }  
?>
<div style="margin-left: 500px;">
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<fieldset style="width: 400px; margin-top: 30px;">
  <legend><b>Parâmetros Principais</b></legend>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$To134_anousu?>">
           <?=@$Lo134_anousu?>
        </td>
        <td> 
    <?
    $o134_anousu = db_getsession('DB_anousu');
    db_input('o134_anousu',10,$Io134_anousu,true,'text',3,"")
    ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$To134_percentuallimiteloa?>">
           <?=@$Lo134_percentuallimiteloa?>
        </td>
        <td> 
    <? db_input('o134_percentuallimiteloa',10,$Io134_percentuallimiteloa,true,'text',$db_opcao,"")?>
        </td>
      </tr>
      </table>
      </center>
</fieldset>     
    <input onclick="return ver_valor_limite();" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" 
     style="margin-top: 20px;" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" style="display: none" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" > 

</form>

</div>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcsuplementacaoparametro','db_iframe_orcsuplementacaoparametro','func_orcsuplementacaoparametro.php?funcao_js=parent.js_preenchepesquisa|o134_anousu','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_orcsuplementacaoparametro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
/*
 * função que validara a entrada do limite LOA
 * apenas permitira valores entre 1 e 100
 */  
function ver_valor_limite() {
  var iValor = document.getElementById('o134_percentuallimiteloa').value;
  if((iValor < 0) ||(iValor > 100) ) {
    alert('Valor Somente entre 0 e 100'); 
    document.getElementById('o134_percentuallimiteloa').value="";
    document.getElementById('o134_percentuallimiteloa').focus();
    return false;
  }else{
    return true;
  }
}
</script>