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

//MODULO: issqn
$clissarqsimplesregerro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q23_issarqsimples");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq49_sequencial?>">
       <?
       db_ancora(@$Lq49_sequencial,"js_pesquisaq49_sequencial(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q49_sequencial',8,$Iq49_sequencial,true,'text',$db_opcao," onchange='js_pesquisaq49_sequencial(false);'")
?>
       <?
db_input('q23_issarqsimples',8,$Iq23_issarqsimples,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq49_erro?>">
       <?=@$Lq49_erro?>
    </td>
    <td> 
<?
db_input('q49_erro',100,$Iq49_erro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq49_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimplesreg','func_issarqsimplesreg.php?funcao_js=parent.js_mostraissarqsimplesreg1|q23_sequencial|q23_issarqsimples','Pesquisa',true);
  }else{
     if(document.form1.q49_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimplesreg','func_issarqsimplesreg.php?pesquisa_chave='+document.form1.q49_sequencial.value+'&funcao_js=parent.js_mostraissarqsimplesreg','Pesquisa',false);
     }else{
       document.form1.q23_issarqsimples.value = ''; 
     }
  }
}
function js_mostraissarqsimplesreg(chave,erro){
  document.form1.q23_issarqsimples.value = chave; 
  if(erro==true){ 
    document.form1.q49_sequencial.focus(); 
    document.form1.q49_sequencial.value = ''; 
  }
}
function js_mostraissarqsimplesreg1(chave1,chave2){
  document.form1.q49_sequencial.value = chave1;
  document.form1.q23_issarqsimples.value = chave2;
  db_iframe_issarqsimplesreg.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimplesregerro','func_issarqsimplesregerro.php?funcao_js=parent.js_preenchepesquisa|q49_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issarqsimplesregerro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>