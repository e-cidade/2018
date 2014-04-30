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

//MODULO: Caixa
$clextratosaldo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k85_nomearq");
$clrotulo->label("db83_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk97_sequencial?>">
       <?=@$Lk97_sequencial?>
    </td>
    <td> 
<?
db_input('k97_sequencial',10,$Ik97_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_dtsaldofinal?>">
       <?=@$Lk97_dtsaldofinal?>
    </td>
    <td> 
<?
db_inputdata('k97_dtsaldofinal',@$k97_dtsaldofinal_dia,@$k97_dtsaldofinal_mes,@$k97_dtsaldofinal_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_contabancaria?>">
       <?
       db_ancora(@$Lk97_contabancaria,"js_pesquisak97_contabancaria(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k97_contabancaria',10,$Ik97_contabancaria,true,'text',$db_opcao," onchange='js_pesquisak97_contabancaria(false);'")
?>
       <?
db_input('db83_descricao',100,$Idb83_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_extrato?>">
       <?
       db_ancora(@$Lk97_extrato,"js_pesquisak97_extrato(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k97_extrato',10,$Ik97_extrato,true,'text',$db_opcao," onchange='js_pesquisak97_extrato(false);'")
?>
       <?
db_input('k85_nomearq',255,$Ik85_nomearq,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_valorcredito?>">
       <?=@$Lk97_valorcredito?>
    </td>
    <td> 
<?
db_input('k97_valorcredito',10,$Ik97_valorcredito,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_valordebito?>">
       <?=@$Lk97_valordebito?>
    </td>
    <td> 
<?
db_input('k97_valordebito',10,$Ik97_valordebito,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_qtdregistros?>">
       <?=@$Lk97_qtdregistros?>
    </td>
    <td> 
<?
db_input('k97_qtdregistros',10,$Ik97_qtdregistros,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_posicao?>">
       <?=@$Lk97_posicao?>
    </td>
    <td> 
<?
db_input('k97_posicao',1,$Ik97_posicao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_situacao?>">
       <?=@$Lk97_situacao?>
    </td>
    <td> 
<?
db_input('k97_situacao',1,$Ik97_situacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_saldobloqueado?>">
       <?=@$Lk97_saldobloqueado?>
    </td>
    <td> 
<?
db_input('k97_saldobloqueado',10,$Ik97_saldobloqueado,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_saldofinal?>">
       <?=@$Lk97_saldofinal?>
    </td>
    <td> 
<?
db_input('k97_saldofinal',10,$Ik97_saldofinal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk97_limite?>">
       <?=@$Lk97_limite?>
    </td>
    <td> 
<?
db_input('k97_limite',10,$Ik97_limite,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak97_extrato(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_extrato','func_extrato.php?funcao_js=parent.js_mostraextrato1|k85_sequencial|k85_nomearq','Pesquisa',true);
  }else{
     if(document.form1.k97_extrato.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_extrato','func_extrato.php?pesquisa_chave='+document.form1.k97_extrato.value+'&funcao_js=parent.js_mostraextrato','Pesquisa',false);
     }else{
       document.form1.k85_nomearq.value = ''; 
     }
  }
}
function js_mostraextrato(chave,erro){
  document.form1.k85_nomearq.value = chave; 
  if(erro==true){ 
    document.form1.k97_extrato.focus(); 
    document.form1.k97_extrato.value = ''; 
  }
}
function js_mostraextrato1(chave1,chave2){
  document.form1.k97_extrato.value = chave1;
  document.form1.k85_nomearq.value = chave2;
  db_iframe_extrato.hide();
}
function js_pesquisak97_contabancaria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_contabancaria','func_contabancaria.php?funcao_js=parent.js_mostracontabancaria1|db83_sequencial|db83_descricao','Pesquisa',true);
  }else{
     if(document.form1.k97_contabancaria.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_contabancaria','func_contabancaria.php?pesquisa_chave='+document.form1.k97_contabancaria.value+'&funcao_js=parent.js_mostracontabancaria','Pesquisa',false);
     }else{
       document.form1.db83_descricao.value = ''; 
     }
  }
}
function js_mostracontabancaria(chave,erro){
  document.form1.db83_descricao.value = chave; 
  if(erro==true){ 
    document.form1.k97_contabancaria.focus(); 
    document.form1.k97_contabancaria.value = ''; 
  }
}
function js_mostracontabancaria1(chave1,chave2){
  document.form1.k97_contabancaria.value = chave1;
  document.form1.db83_descricao.value = chave2;
  db_iframe_contabancaria.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_extratosaldo','func_extratosaldo.php?funcao_js=parent.js_preenchepesquisa|k97_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_extratosaldo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>