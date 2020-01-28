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
$clnotidebitos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k50_procede");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk53_notifica?>">
       <?
       db_ancora(@$Lk53_notifica,"js_pesquisak53_notifica(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k53_notifica',8,$Ik53_notifica,true,'text',$db_opcao," onchange='js_pesquisak53_notifica(false);'")
?>
       <?
db_input('k50_procede',8,$Ik50_procede,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk53_numpre?>">
       <?=@$Lk53_numpre?>
    </td>
    <td> 
<?
db_input('k53_numpre',8,$Ik53_numpre,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk53_numpar?>">
       <?=@$Lk53_numpar?>
    </td>
    <td> 
<?
db_input('k53_numpar',4,$Ik53_numpar,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak53_notifica(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_notificacao.php?funcao_js=parent.js_mostranotificacao1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_notificacao.php?pesquisa_chave='+document.form1.k53_notifica.value+'&funcao_js=parent.js_mostranotificacao';
  }
}
function js_mostranotificacao(chave,erro){
  document.form1.k50_procede.value = chave; 
  if(erro==true){ 
    document.form1.k53_notifica.focus(); 
    document.form1.k53_notifica.value = ''; 
  }
}
function js_mostranotificacao1(chave1,chave2){
  document.form1.k53_notifica.value = chave1;
  document.form1.k50_procede.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_notidebitos.php?funcao_js=parent.js_preenchepesquisa|0|1|2';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave+"&chavepesquisa1="+chave1+"&chavepesquisa2="+chave2;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>