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

//MODULO: agenda
$cldb_contatos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("g02_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tg01_id?>">
       <?=@$Lg01_id?>
    </td>
    <td> 
<?
db_input('g01_id',5,$Ig01_id,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_tipocon?>">
       <?
       db_ancora(@$Lg01_tipocon,"js_pesquisag01_tipocon(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('g01_tipocon',4,$Ig01_tipocon,true,'text',$db_opcao," onchange='js_pesquisag01_tipocon(false);'")
?>
       <?
db_input('g02_descr',40,$Ig02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_organizacao?>">
       <?=@$Lg01_organizacao?>
    </td>
    <td> 
<?
db_input('g01_organizacao',50,$Ig01_organizacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_nome?>">
       <?=@$Lg01_nome?>
    </td>
    <td> 
<?
db_input('g01_nome',40,$Ig01_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_rua?>">
       <?=@$Lg01_rua?>
    </td>
    <td> 
<?
db_input('g01_rua',50,$Ig01_rua,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_bairro?>">
       <?=@$Lg01_bairro?>
    </td>
    <td> 
<?
db_input('g01_bairro',40,$Ig01_bairro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_cidade?>">
       <?=@$Lg01_cidade?>
    </td>
    <td> 
<?
db_input('g01_cidade',50,$Ig01_cidade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_uf?>">
       <?=@$Lg01_uf?>
    </td>
    <td> 
<?
db_input('g01_uf',2,$Ig01_uf,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_cep?>">
       <?=@$Lg01_cep?>
    </td>
    <td> 
<?
db_input('g01_cep',12,$Ig01_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_telef?>">
       <?=@$Lg01_telef?>
    </td>
    <td> 
<?
db_input('g01_telef',12,$Ig01_telef,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_fax?>">
       <?=@$Lg01_fax?>
    </td>
    <td> 
<?
db_input('g01_fax',12,$Ig01_fax,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_celular?>">
       <?=@$Lg01_celular?>
    </td>
    <td> 
<?
db_input('g01_celular',12,$Ig01_celular,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_obs?>">
       <?=@$Lg01_obs?>
    </td>
    <td> 
<?
db_textarea('g01_obs',3,80,$Ig01_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_email?>">
       <?=@$Lg01_email?>
    </td>
    <td> 
<?
db_input('g01_email',40,$Ig01_email,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg01_site?>">
       <?=@$Lg01_site?>
    </td>
    <td> 
<?
db_input('g01_site',40,$Ig01_site,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisag01_tipocon(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_contatostipo','func_db_contatostipo.php?funcao_js=parent.js_mostradb_contatostipo1|g02_tipocon|g02_descr','Pesquisa',true);
  }else{
     if(document.form1.g01_tipocon.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_contatostipo','func_db_contatostipo.php?pesquisa_chave='+document.form1.g01_tipocon.value+'&funcao_js=parent.js_mostradb_contatostipo','Pesquisa',false);
     }else{
       document.form1.g02_descr.value = ''; 
     }
  }
}
function js_mostradb_contatostipo(chave,erro){
  document.form1.g02_descr.value = chave; 
  if(erro==true){ 
    document.form1.g01_tipocon.focus(); 
    document.form1.g01_tipocon.value = ''; 
  }
}
function js_mostradb_contatostipo1(chave1,chave2){
  document.form1.g01_tipocon.value = chave1;
  document.form1.g02_descr.value = chave2;
  db_iframe_db_contatostipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_contatos','func_db_contatos.php?funcao_js=parent.js_preenchepesquisa|g01_id','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_contatos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>