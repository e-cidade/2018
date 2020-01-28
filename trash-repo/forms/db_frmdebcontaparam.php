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

//MODULO: caixa
$cldebcontaparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");

if($db_opcao==2 or $db_opcao==22) {
  $db_opcaobco = 3;
} else {
  $db_opcaobco = $db_opcao;
}

?>
<form name="form1" method="post" action="">
<center>
<table border="0">

    <tr>   
      <td>
      <?
       db_ancora($Ld62_banco,' js_bancos(true); ',$db_opcaobco);
      ?>
       </td>
       <td> 
      <?
       db_input('d62_banco',5,$Id62_banco,true,'text',$db_opcaobco,"onchange='js_bancos(false)'");
       db_input('nomebco',40,"",true,'text',3);
       
      ?>
       </td>
     </tr>

  <tr>
    <td nowrap title="<?=@$Td62_convenio?>">
       <?=@$Ld62_convenio?>
    </td>
    <td> 
<?
db_input('d62_convenio',20,$Id62_convenio,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td62_ultimonsa?>">
       <?=@$Ld62_ultimonsa?>
    </td>
    <td> 
<?
db_input('d62_ultimonsa',10,$Id62_ultimonsa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td62_mascara?>">
       <?=@$Ld62_mascara?>
    </td>
    <td> 
<?
db_input('d62_mascara',25,$Id62_mascara,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisad62_instituicao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.d62_instituicao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.d62_instituicao.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.d62_instituicao.focus(); 
    document.form1.d62_instituicao.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.d62_instituicao.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_debcontaparam','func_debcontaparam.php?funcao_js=parent.js_preenchepesquisa|d62_instituicao|d62_banco','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_debcontaparam.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}

function js_bancos(mostra){
  var bancos=document.form1.d62_banco.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe2','func_bancos.php?funcao_js=parent.js_mostrabancos|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_bancos.php?pesquisa_chave='+bancos+'&funcao_js=parent.js_mostrabancos1','Pesquisa',false);
  }
}
function js_mostrabancos(chave1,chave2){
  document.form1.d62_banco.value = chave1;
  document.form1.nomebco.value = chave2;  
  db_iframe2.hide();
}
function js_mostrabancos1(chave,erro){
  document.form1.nomebco.value = chave;
  if(erro==true){ 
    document.form1.d62_banco.focus(); 
    document.form1.d62_banco.value = ''; 
  }
}



</script>