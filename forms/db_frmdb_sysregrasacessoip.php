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

//MODULO: configuracoes
$cldb_sysregrasacessoip->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db46_observ");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb48_idacesso?>">
       <?
       db_ancora(@$Ldb48_idacesso,"",3);
       ?>
    </td>
    <td> 
<?
db_input('db48_idacesso',6,$Idb48_idacesso,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb48_ip?>">
       <?=@$Ldb48_ip?>
    </td>
    <td> 
<?
db_input('db48_ip',40,$Idb48_ip,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="incluir" type="submit" id="db_opcaoi" value="Incluir" >
<input name="alterar" type="submit" id="db_opcaoa" value="Alterar" >
<input name="excluir" type="submit" id="db_opcaoe" value="Excluir" >
</form>
<script>
function js_pesquisadb48_idacesso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_sysregrasacesso','func_db_sysregrasacesso.php?funcao_js=parent.js_mostradb_sysregrasacesso1|db46_idacesso|db46_observ','Pesquisa',true);
  }else{
     if(document.form1.db48_idacesso.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_sysregrasacesso','func_db_sysregrasacesso.php?pesquisa_chave='+document.form1.db48_idacesso.value+'&funcao_js=parent.js_mostradb_sysregrasacesso','Pesquisa',false);
     }else{
       document.form1.db46_observ.value = ''; 
     }
  }
}
function js_mostradb_sysregrasacesso(chave,erro){
  document.form1.db46_observ.value = chave; 
  if(erro==true){ 
    document.form1.db48_idacesso.focus(); 
    document.form1.db48_idacesso.value = ''; 
  }
}
function js_mostradb_sysregrasacesso1(chave1,chave2){
  document.form1.db48_idacesso.value = chave1;
  document.form1.db46_observ.value = chave2;
  db_iframe_db_sysregrasacesso.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_sysregrasacessoip','func_db_sysregrasacessoip.php?funcao_js=parent.js_preenchepesquisa|db48_idacesso','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_sysregrasacessoip.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>