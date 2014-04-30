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

//MODULO: recursoshumanos
$clrhestagiocomissao->rotulo->label();
      if($db_opcao==1){
 	   $db_action="rec1_rhestagiocomissao004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="rec1_rhestagiocomissao005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="rec1_rhestagiocomissao006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table>
<tr>
  <td>
  <table>
  <tr>
  <td>
  <fieldset><legend><b>Dados da Comissão<b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th59_sequencial?>">
       <?=@$Lh59_sequencial?>
    </td>
    <td> 
<?
db_input('h59_sequencial',10,$Ih59_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th59_dtini?>">
       <?=@$Lh59_dtini?>
    </td>
    <td> 
<?
db_inputdata('h59_dtini',@$h59_dtini_dia,@$h59_dtini_mes,@$h59_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th59_dtfim?>">
       <?=@$Lh59_dtfim?>
    </td>
    <td> 
<?
db_inputdata('h59_dtfim',@$h59_dtfim_dia,@$h59_dtfim_mes,@$h59_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th59_descr?>">
       <?=@$Lh59_descr?>
    </td>
    <td> 
<?
db_input('h59_descr',40,$Ih59_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table></fieldset>
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_rhestagiocomissao','db_iframe_rhestagiocomissao','func_rhestagiocomissao.php?funcao_js=parent.js_preenchepesquisa|h59_sequencial','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_rhestagiocomissao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>