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

//MODULO: Configuracoes
$cldocumento->rotulo->label();
      if($db_opcao==1){
 	   $db_action="con1_documento004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="con1_documento005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="con1_documento006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>

<table align=center style="margin-top: 15px;">
<tr><td>

<fieldset>
<legend><strong>Cadastro de Documentos</strong></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb44_sequencial?>">
       <?=@$Ldb44_sequencial?>
    </td>
    <td> 
			<?
			db_input('db44_sequencial',10,$Idb44_sequencial,true,'text',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb44_descricao?>">
       <?=@$Ldb44_descricao?>
    </td>
    <td> 
			<?
			db_input('db44_descricao',50,$Idb44_descricao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  </table>
  
</fieldset>  
</td></tr>
</table>
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_documento','db_iframe_documento','func_documento.php?funcao_js=parent.js_preenchepesquisa|db44_sequencial','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_documento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>