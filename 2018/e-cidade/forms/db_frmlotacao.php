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

//MODULO: pessoal
$cllotacao->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tr13_anousu?>">
       <?=@$Lr13_anousu?>
    </td>
    <td> 
<?
$r13_anousu = db_getsession('DB_anousu');
db_input('r13_anousu',4,$Ir13_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_mesusu?>">
       <?=@$Lr13_mesusu?>
    </td>
    <td> 
<?
db_input('r13_mesusu',2,$Ir13_mesusu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_codigo?>">
       <?=@$Lr13_codigo?>
    </td>
    <td> 
<?
db_input('r13_codigo',4,$Ir13_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_descr?>">
       <?=@$Lr13_descr?>
    </td>
    <td> 
<?
db_input('r13_descr',40,$Ir13_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_reduz?>">
       <?=@$Lr13_reduz?>
    </td>
    <td> 
<?
db_input('r13_reduz',5,$Ir13_reduz,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_proati?>">
       <?=@$Lr13_proati?>
    </td>
    <td> 
<?
db_input('r13_proati',4,$Ir13_proati,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_painat?>">
       <?=@$Lr13_painat?>
    </td>
    <td> 
<?
db_input('r13_painat',4,$Ir13_painat,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_descro?>">
       <?=@$Lr13_descro?>
    </td>
    <td> 
<?
db_input('r13_descro',40,$Ir13_descro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_descru?>">
       <?=@$Lr13_descru?>
    </td>
    <td> 
<?
db_input('r13_descru',40,$Ir13_descru,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_subele?>">
       <?=@$Lr13_subele?>
    </td>
    <td> 
<?
db_input('r13_subele',6,$Ir13_subele,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_calend?>">
       <?=@$Lr13_calend?>
    </td>
    <td> 
<?
db_input('r13_calend',2,$Ir13_calend,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_rproat?>">
       <?=@$Lr13_rproat?>
    </td>
    <td> 
<?
db_input('r13_rproat',4,$Ir13_rproat,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr13_rpaina?>">
       <?=@$Lr13_rpaina?>
    </td>
    <td> 
<?
db_input('r13_rpaina',4,$Ir13_rpaina,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lotacao','func_lotacao.php?funcao_js=parent.js_preenchepesquisa|r13_anousu|r13_mesusu|r13_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_lotacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>