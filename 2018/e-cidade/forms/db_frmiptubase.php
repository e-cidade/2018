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

//MODULO: cadastro
$cliptubase->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j34_setor");
?>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj01_matric?>">
       <?=@$Lj01_matric?>
    </td>
    <td> 
<?
db_input('j01_matric',8,$Ij01_matric,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj01_numcgm?>">
       <?
       db_ancora(@$Lj01_numcgm,"js_pesquisaj01_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j01_numcgm',4,$Ij01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaj01_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj01_baixa?>">
       <?=@$Lj01_baixa?>
    </td>
    <td> 
<?
db_inputdata('j01_baixa',@$j01_baixa_dia,@$j01_baixa_mes,@$j01_baixa_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj01_fracao?>">
       <?=@$Lj01_fracao?>
    </td>
    <td> 
<?
db_input('j01_fracao',15,$Ij01_fracao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
<script>
function js_pesquisaj01_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.j01_numcgm.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j01_numcgm.focus(); 
    document.form1.j01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.j01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisaj01_idbql(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_lote.php?funcao_js=parent.js_mostralote1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_lote.php?pesquisa_chave='+document.form1.j01_idbql.value+'&funcao_js=parent.js_mostralote';
  }
}
function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.j01_idbql.focus(); 
    document.form1.j01_idbql.value = ''; 
  }
}
function js_mostralote1(chave1,chave2){
  document.form1.j01_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
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