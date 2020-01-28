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

//MODULO: biblioteca
$cldevolucaoacervo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi19_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tbi21_codigo?>">
       <?=@$Lbi21_codigo?>
    </td>
    <td> 
<?
db_input('bi21_codigo',8,$Ibi21_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi21_emprestimoacervo?>">
       <?
       db_ancora(@$Lbi21_emprestimoacervo,"js_pesquisabi21_emprestimoacervo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bi21_emprestimoacervo',8,$Ibi21_emprestimoacervo,true,'text',$db_opcao," onchange='js_pesquisabi21_emprestimoacervo(false);'")
?>
       <?
db_input('bi19_codigo',10,$Ibi19_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi21_entrega?>">
       <?=@$Lbi21_entrega?>
    </td>
    <td> 
<?
db_inputdata('bi21_entrega',@$bi21_entrega_dia,@$bi21_entrega_mes,@$bi21_entrega_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi21_qtdunidade?>">
       <?=@$Lbi21_qtdunidade?>
    </td>
    <td> 
<?
db_input('bi21_qtdunidade',8,$Ibi21_qtdunidade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisabi21_emprestimoacervo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emprestimoacervo','func_emprestimoacervo.php?funcao_js=parent.js_mostraemprestimoacervo1|bi19_codigo|bi19_codigo','Pesquisa',true);
  }else{
     if(document.form1.bi21_emprestimoacervo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_emprestimoacervo','func_emprestimoacervo.php?pesquisa_chave='+document.form1.bi21_emprestimoacervo.value+'&funcao_js=parent.js_mostraemprestimoacervo','Pesquisa',false);
     }else{
       document.form1.bi19_codigo.value = ''; 
     }
  }
}
function js_mostraemprestimoacervo(chave,erro){
  document.form1.bi19_codigo.value = chave; 
  if(erro==true){ 
    document.form1.bi21_emprestimoacervo.focus(); 
    document.form1.bi21_emprestimoacervo.value = ''; 
  }
}
function js_mostraemprestimoacervo1(chave1,chave2){
  document.form1.bi21_emprestimoacervo.value = chave1;
  document.form1.bi19_codigo.value = chave2;
  db_iframe_emprestimoacervo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_devolucaoacervo','func_devolucaoacervo.php?funcao_js=parent.js_preenchepesquisa|bi21_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_devolucaoacervo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>