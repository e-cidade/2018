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
$clissnotaavulsatomador->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q51_numnota");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq53_sequencial?>">
       <?=@$Lq53_sequencial?>
    </td>
    <td> 
<?
db_input('q53_sequencial',10,$Iq53_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_issnotaavulsa?>">
       <?
       db_ancora(@$Lq53_issnotaavulsa,"js_pesquisaq53_issnotaavulsa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q53_issnotaavulsa',10,$Iq53_issnotaavulsa,true,'text',$db_opcao," onchange='js_pesquisaq53_issnotaavulsa(false);'")
?>
       <?
db_input('q51_numnota',10,$Iq51_numnota,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_dtservico?>">
       <?=@$Lq53_dtservico?>
    </td>
    <td> 
<?
db_inputdata('q53_dtservico',@$q53_dtservico_dia,@$q53_dtservico_mes,@$q53_dtservico_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_nome?>">
       <?=@$Lq53_nome?>
    </td>
    <td> 
<?
db_input('q53_nome',70,$Iq53_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_cpfpnpj?>">
       <?=@$Lq53_cpfpnpj?>
    </td>
    <td> 
<?
db_input('q53_cpfpnpj',14,$Iq53_cpfpnpj,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_endereco?>">
       <?=@$Lq53_endereco?>
    </td>
    <td> 
<?
db_input('q53_endereco',40,$Iq53_endereco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_numero?>">
       <?=@$Lq53_numero?>
    </td>
    <td> 
<?
db_input('q53_numero',15,$Iq53_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_bairro?>">
       <?=@$Lq53_bairro?>
    </td>
    <td> 
<?
db_input('q53_bairro',50,$Iq53_bairro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_cep?>">
       <?=@$Lq53_cep?>
    </td>
    <td> 
<?
db_input('q53_cep',8,$Iq53_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_uf?>">
       <?=@$Lq53_uf?>
    </td>
    <td> 
<?
db_input('q53_uf',2,$Iq53_uf,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_email?>">
       <?=@$Lq53_email?>
    </td>
    <td> 
<?
db_input('q53_email',25,$Iq53_email,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq53_fone?>">
       <?=@$Lq53_fone?>
    </td>
    <td> 
<?
db_input('q53_fone',15,$Iq53_fone,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq53_issnotaavulsa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsa','func_issnotaavulsa.php?funcao_js=parent.js_mostraissnotaavulsa1|q51_numnota|q51_numnota','Pesquisa',true);
  }else{
     if(document.form1.q53_issnotaavulsa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsa','func_issnotaavulsa.php?pesquisa_chave='+document.form1.q53_issnotaavulsa.value+'&funcao_js=parent.js_mostraissnotaavulsa','Pesquisa',false);
     }else{
       document.form1.q51_numnota.value = ''; 
     }
  }
}
function js_mostraissnotaavulsa(chave,erro){
  document.form1.q51_numnota.value = chave; 
  if(erro==true){ 
    document.form1.q53_issnotaavulsa.focus(); 
    document.form1.q53_issnotaavulsa.value = ''; 
  }
}
function js_mostraissnotaavulsa1(chave1,chave2){
  document.form1.q53_issnotaavulsa.value = chave1;
  document.form1.q51_numnota.value = chave2;
  db_iframe_issnotaavulsa.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsatomador','func_issnotaavulsatomador.php?funcao_js=parent.js_preenchepesquisa|q53_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issnotaavulsatomador.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>