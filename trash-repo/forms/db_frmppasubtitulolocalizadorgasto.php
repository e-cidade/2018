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
$clppasubtitulolocalizadorgasto->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<fieldset>
<legend>
  <b>Cadastro de Subt�tulo/ Localizador de Gasto</b>
</legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To11_sequencial?>">
       <?=@$Lo11_sequencial?>
    </td>
    <td> 
<?
db_input('o11_sequencial',10,$Io11_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To11_codigo?>">
       <?=@$Lo11_codigo?>
    </td>
    <td> 
<?
db_input('o11_codigo',10,$Io11_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To11_descricao?>">
       <?=@$Lo11_descricao?>
    </td>
    <td> 
<?
db_input('o11_descricao',50,$Io11_descricao,true,'text',$db_opcao,"")
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
  js_OpenJanelaIframe('top.corpo','db_iframe_ppasubtitulolocalizadorgasto','func_ppasubtitulolocalizadorgasto.php?funcao_js=parent.js_preenchepesquisa|o11_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ppasubtitulolocalizadorgasto.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>