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
$clextrato->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk85_sequencial?>">
       <?=@$Lk85_sequencial?>
    </td>
    <td> 
<?
db_input('k85_sequencial',10,$Ik85_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_codbco?>">
		   <?
       db_ancora(@$Lk85_codbco,"js_pesquisak05_codbco(true);",$db_opcao);
			 ?>
    </td>
    <td> 
    <?
      db_input('k85_codbco',10,$Ik85_codbco,true,'text',$db_opcao," onchange='js_pesquisak05_codbco(false);'")
    ?>
    <?
      db_input('nomebco',50,'',true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_convenio?>">
       <?=@$Lk85_convenio?>
    </td>
    <td> 
<?
db_input('k85_convenio',10,$Ik85_convenio,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_pesquisak05_codbco(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_codbco','func_bancos.php?funcao_js=parent.js_mostrabancos1|codbco|nomebco','Pesquisa',true,'0');
  }else{
     if(document.form1.k85_codbco.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_codbco','func_bancos.php?pesquisa_chave='+document.form1.k85_codbco.value+'&funcao_js=parent.js_mostrabancos','Pesquisa',false);
     }else{
       document.form1.k05_codbco.value = ''; 
     }
  }
}

function js_mostrabancos(chave,erro){
  document.form1.nomebco.value = chave; 
  if(erro == true){ 
    document.form1.k85_codbco.focus(); 
    document.form1.k85_codbco.value = ''; 
  }
}
function js_mostrabancos1(chave1,chave2){
//	alert(chave1+' -- '+chave2);
  document.form1.k85_codbco.value = chave1;
  document.form1.nomebco.value    = chave2;
  db_iframe_codbco.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_extrato','func_extrato.php?funcao_js=parent.js_preenchepesquisa|k85_sequencial','Pesquisa',true,'0');
}

function js_preenchepesquisa(chave){
  db_iframe_extrato.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>