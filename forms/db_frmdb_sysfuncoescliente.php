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
$cldb_sysfuncoescliente->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at01_nomecli");
$clrotulo->label("nomefuncao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb41_sysfuncoescliente?>">
       <?=@$Ldb41_sysfuncoescliente?>
    </td>
    <td> 
<?
db_input('db41_sysfuncoescliente',10,$Idb41_sysfuncoescliente,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb41_cliente?>">
       <?
       db_ancora(@$Ldb41_cliente,"js_pesquisadb41_cliente(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db41_cliente',4,$Idb41_cliente,true,'text',$db_opcao," onchange='js_pesquisadb41_cliente(false);'")
?>
       <?
db_input('at01_nomecli',40,$Iat01_nomecli,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb41_funcao?>">
       <?
       db_ancora(@$Ldb41_funcao,"js_pesquisadb41_funcao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db41_funcao',5,$Idb41_funcao,true,'text',$db_opcao," onchange='js_pesquisadb41_funcao(false);'")
?>
       <?
db_input('nomefuncao',100,$Inomefuncao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb41_cliente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_mostraclientes1|at01_codcli|at01_nomecli','Pesquisa',true);
  }else{
     if(document.form1.db41_cliente.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_clientes','func_clientes.php?pesquisa_chave='+document.form1.db41_cliente.value+'&funcao_js=parent.js_mostraclientes','Pesquisa',false);
     }else{
       document.form1.at01_nomecli.value = ''; 
     }
  }
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave; 
  if(erro==true){ 
ssh dbrobson@192.1680..1
drobson@192.1680..1
dlegriontem_dbseller
select * from db_syscfun  cli ;
psql -U postgres -l
ocument.form1.db41_cliente.focus(); 
    document.form1.db41_cliente.value = ''; 
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.db41_cliente.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe_clientes.hide();
}
function js_pesquisadb41_funcao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?funcao_js=parent.js_mostradb_sysfuncoes1|codfuncao|nomefuncao','Pesquisa',true);
  }else{
     if(document.form1.db41_funcao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?pesquisa_chave='+document.form1.db41_funcao.value+'&funcao_js=parent.js_mostradb_sysfuncoes','Pesquisa',false);
     }else{
       document.form1.nomefuncao.value = ''; 
     }
  }
}
function js_mostradb_sysfuncoes(chave,erro){
  document.form1.nomefuncao.value = chave; 
  if(erro==true){ 
    document.form1.db41_funcao.focus(); 
    document.form1.db41_funcao.value = ''; 
  }
}
function js_mostradb_sysfuncoes1(chave1,chave2){
  document.form1.db41_funcao.value = chave1;
  document.form1.nomefuncao.value = chave2;
  db_iframe_db_sysfuncoes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoescliente','func_db_sysfuncoescliente.php?funcao_js=parent.js_preenchepesquisa|db41_sysfuncoescliente','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_sysfuncoescliente.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>