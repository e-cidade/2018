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
$clemprestimoacervo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi18_retirada");
$clrotulo->label("bi06_titulo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tbi19_codigo?>">
       <?=@$Lbi19_codigo?>
    </td>
    <td> 
<?
db_input('bi19_codigo',10,$Ibi19_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi19_qtdunidade?>">
       <?=@$Lbi19_qtdunidade?>
    </td>
    <td> 
<?
db_input('bi19_qtdunidade',4,$Ibi19_qtdunidade,true,'text',$db_opcao,"")
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b>Limite:</b>
<?
db_input('bi07_qtdlivros',4,$bi07_qtdlivros,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi19_acervo?>">
       <?
       db_ancora(@$Lbi19_acervo,"js_pesquisabi19_acervo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bi19_acervo',10,$Ibi19_acervo,true,'text',$db_opcao," onchange='js_pesquisabi19_acervo(false);'")
?>
<?
db_input('bi06_titulo',50,$Ibi06_titulo,true,'text',3,'')
?>
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisabi19_emprestimo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emprestimo','func_emprestimo.php?funcao_js=parent.js_mostraemprestimo1|bi18_codigo|bi18_retirada','Pesquisa',true);
  }else{
     if(document.form1.bi19_emprestimo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_emprestimo','func_emprestimo.php?pesquisa_chave='+document.form1.bi19_emprestimo.value+'&funcao_js=parent.js_mostraemprestimo','Pesquisa',false);
     }else{
       document.form1.bi18_retirada.value = ''; 
     }
  }
}
function js_mostraemprestimo(chave,erro){
  document.form1.bi18_retirada.value = chave; 
  if(erro==true){ 
    document.form1.bi19_emprestimo.focus(); 
    document.form1.bi19_emprestimo.value = ''; 
  }
}
function js_mostraemprestimo1(chave1,chave2){
  document.form1.bi19_emprestimo.value = chave1;
  document.form1.bi18_retirada.value = chave2;
  db_iframe_emprestimo.hide();
}
function js_pesquisabi19_acervo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_acervo','func_acervo.php?funcao_js=parent.js_mostraacervo1|bi19_acervo|bi06_titulo','Pesquisa',true);
  }else{
     if(document.form1.bi19_acervo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_acervo','func_acervo.php?pesquisa_chave='+document.form1.bi19_acervo.value+'&funcao_js=parent.js_mostraacervo','Pesquisa',false);
     }else{
       document.form1.bi06_titulo.value = '';
     }
  }
}
function js_mostraacervo(chave,erro){
  document.form1.bi06_titulo.value = chave;
  if(erro==true){ 
    document.form1.bi19_acervo.focus(); 
    document.form1.bi19_acervo.value = ''; 
  }
}
function js_mostraacervo1(chave1,chave2){
  document.form1.bi19_acervo.value = chave1;
  document.form1.bi06_titulo.value = chave2;
  db_iframe_acervo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_emprestimoacervo','func_emprestimoacervo.php?funcao_js=parent.js_preenchepesquisa|bi19_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_emprestimoacervo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>