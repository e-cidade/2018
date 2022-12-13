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
$clorcorgao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><legend><b>Cadastro de Orgãos</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To40_anousu?>">
       <?=@$Lo40_anousu?>
    </td>
    <td> 
<?
$o40_anousu = db_getsession('DB_anousu');
db_input('o40_anousu',4,$Io40_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To40_orgao?>">
       <?=@$Lo40_orgao?>
    </td>
    <td> 
<?
db_input('o40_orgao',2,$Io40_orgao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To40_codtri?>">
       <?=@$Lo40_codtri?>
    </td>
    <td> 
<?
db_input('o40_codtri',2,$Io40_codtri,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To40_descr?>">
       <?=@$Lo40_descr?>
    </td>
    <td> 
<?
db_input('o40_descr',50,$Io40_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To40_instit?>">
       <?
       db_ancora(@$Lo40_instit,"js_pesquisao40_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o40_instit',2,$Io40_instit,true,'text',$db_opcao," onchange='js_pesquisao40_instit(false);'")
?>
       <?
db_input('nomeinst',80,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To40_finali?>">
       <?=@$Lo40_finali?>
    </td>
    <td> 
<?
db_textarea('o40_finali',6,70,$Io40_finali,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao40_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.o40_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.o40_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.o40_instit.focus(); 
    document.form1.o40_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.o40_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_preenchepesquisa|o40_anousu|o40_orgao','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_orcorgao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>