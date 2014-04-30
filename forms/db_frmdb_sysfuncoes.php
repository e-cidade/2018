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
$cldb_sysfuncoes->rotulo->label();

//MODULO: configuracoes
$cldb_sysfuncoescliente->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at01_nomecli");
$clrotulo->label("nomefuncao");


if($db_opcao==1){
   $db_action="con1_db_sysfuncoes004.php";
}else if($db_opcao==2||$db_opcao==22){
   $db_action="con1_db_sysfuncoes005.php";
}else if($db_opcao==3||$db_opcao==33){
   $db_action="con1_db_sysfuncoes006.php";
}  
?>
<form name="form1" enctype="multipart/form-data" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcodfuncao?>">
       <?=@$Lcodfuncao?>
    </td>
    <td> 
<?
db_input('codfuncao',5,$Icodfuncao,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tnomefuncao?>">
       <?=@$Lnomefuncao?>
    </td>
    <td> 
<?
db_input('nomefuncao',50,$Inomefuncao,true,'text',$db_opcao,"")
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
    <td nowrap title="Selecione o Arquivo fonte">
       <b> Arquivo com o codigo fonte : </b>
    </td>
    <td> 
    <?
      db_input('arquivo',43,'',true,'file',$db_opcao,"class='borda'",'','','');
    ?>
    <input name="carregar" type="Submit" id="carregar" value="Carregar" onclick=" return js_carregar();" >
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tnomearquivo?>">
       <?=@$Lnomearquivo?>
    </td>
    <td> 
    <?
      db_input('nomearquivo',50,$Inomearquivo,true,'text',$db_opcao,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttriggerfuncao?>">
       <?=@$Ltriggerfuncao?>
    </td>
    <td> 
<?
$x = array('0'=>'Função','1'=>'Trigger','2'=>'View');
db_select('triggerfuncao',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tobsfuncao?>">
       <?=@$Lobsfuncao?>
    </td>
    <td> 
<?
db_textarea('obsfuncao',5,50,$Iobsfuncao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcorpofuncao?>">
       <?=@$Lcorpofuncao?>
    </td>
    <td> 
<?
db_textarea('corpofuncao',20,100,$Icorpofuncao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?/*
<input name="libedit"   type="button" id="libedit"   value="Liberar para Editar" onclick="js_liberaedit();" >
<input name="funcbase"  type="submit" id="funcbase"  value="Instalar Função no Banco" onclick="" >
*/?>
</form>
<script>
function js_pesquisadb41_cliente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_mostraclientes1|at01_codcli|at01_nomecli','Pesquisa',true,5);
  }else{
     if(document.form1.db41_cliente.value != ''){
        js_OpenJanelaIframe('','db_iframe_clientes','func_clientes.php?pesquisa_chave='+document.form1.db41_cliente.value+'&funcao_js=parent.js_mostraclientes','Pesquisa',false);
     }else{
       document.form1.at01_nomecli.value = '';
     }
  }
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave;
  if(erro==true){
    document.form1.db41_cliente.focus();
    document.form1.db41_cliente.value = '';
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.db41_cliente.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe_clientes.hide();
}




function js_carregar(){
  return true;
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_db_sysfuncoes','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?funcao_js=parent.js_preenchepesquisa|codfuncao','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_db_sysfuncoes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>