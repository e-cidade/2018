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

//MODULO: atendimento
$clatendordemcli->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("at81_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat85_respcli?>">
       <?
       db_ancora(@$Lat85_respcli,"",3);
       ?>
    </td>
    <td> 
<?
db_input('at85_respcli',6,$Iat85_respcli,true,'text',3)
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
<?
db_input('at85_seq',6,$Iat85_seq,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat85_cliitem?>">
       <?
       db_ancora(@$Lat85_cliitem,"js_pesquisaat85_cliitem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at85_cliitem',6,$Iat85_cliitem,true,'text',$db_opcao," onchange='js_pesquisaat85_cliitem(false);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat81_descr?>">
       <?
       db_ancora(@$Lat81_descr,"",3);
       ?>
    </td>
    <td> 
       <?
db_textarea('at81_descr',4,49,$Iat81_descr,true,'text',3,"")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat85_prioridade?>">
       <?=@$Lat85_prioridade?>
    </td>
    <td> 
<?
  $x = array("1"=>"Baixa",
             "2"=>"Média",
             "3"=>"Alta"
           );
  db_select("at85_prioridade", $x,true,$db_opcao);
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaat85_respcli(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_atendrespcli','func_atendrespcli.php?funcao_js=parent.js_mostraatendrespcli1|at84_seq|nome','Pesquisa',true);
  }else{
     if(document.form1.at85_respcli.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_atendrespcli','func_atendrespcli.php?pesquisa_chave='+document.form1.at85_respcli.value+'&funcao_js=parent.js_mostraatendrespcli','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostraatendrespcli(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.at85_respcli.focus(); 
    document.form1.at85_respcli.value = ''; 
  }
}
function js_mostraatendrespcli1(chave1,chave2){
  document.form1.at85_respcli.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_atendrespcli.hide();
}
function js_pesquisaat85_cliitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_atendusucliitem','func_atendusucliitem.php?funcao_js=parent.js_mostraatendusucliitem1|at81_seq|at81_descr','Pesquisa',true);
  }else{
     if(document.form1.at85_cliitem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_atendusucliitem','func_atendusucliitem.php?pesquisa_chave='+document.form1.at85_cliitem.value+'&funcao_js=parent.js_mostraatendusucliitem','Pesquisa',false);
     }else{
       document.form1.at81_descr.value = ''; 
     }
  }
}
function js_mostraatendusucliitem(chave,erro){
  document.form1.at81_descr.value = chave; 
  if(erro==true){ 
    document.form1.at85_cliitem.focus(); 
    document.form1.at85_cliitem.value = ''; 
  }
}
function js_mostraatendusucliitem1(chave1,chave2){
  document.form1.at85_cliitem.value = chave1;
  document.form1.at81_descr.value = chave2;
  db_iframe_atendusucliitem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_atendordemcli','func_atendordemcli.php?funcao_js=parent.js_preenchepesquisa|at85_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_atendordemcli.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>