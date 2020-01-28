<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
$clconarquivospad->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<fieldset><legend><b>Arquivo PAD</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc54_codarq?>">
       <?=@$Lc54_codarq?>
    </td>
    <td> 
<?
db_input('c54_codarq',10,$Ic54_codarq,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc54_nomearq?>">
       <?=@$Lc54_nomearq?>
    </td>
    <td> 
<?
db_input('c54_nomearq',20,$Ic54_nomearq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc54_arquivo?>">
       <?=@$Lc54_arquivo?>
    </td>
    <td> 
<?
db_textarea('c54_arquivo',15,100,$Ic54_arquivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc54_codtrib?>">
       <?=@$Lc54_codtrib?>
    </td>
    <td> 
<?
if (!isset($c54_codtrib) || $c54_codtrib==""){
	$c54_codtrib = db_getsession("DB_instit");	
}
db_input('c54_codtrib',4,$Ic54_codtrib,true,'text',$db_opcao,"")
?>
 <i> Identifica o agrupamento, por exemplo: se a camara é gerada junto com a prefeitura, então não precisa cadastrar
 os arquivos da camara, somente na prefeitura</i>

    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc54_anousu?>">
       <?=@$Lc54_anousu?>
    </td>
    <td> 
<?
$c54_anousu = (db_getsession('DB_anousu')-1);
db_input('c54_anousu',4,$Ic54_anousu,true,'text',3,"")
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
  js_OpenJanelaIframe('top.corpo','db_iframe_conarquivospad','func_conarquivospad.php?funcao_js=parent.js_preenchepesquisa|c54_codarq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conarquivospad.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>