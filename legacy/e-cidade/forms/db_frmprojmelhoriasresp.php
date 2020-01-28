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

//MODULO: contrib
$clprojmelhoriasresp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("d40_codigo");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Td42_codigo?>">
       <?
       db_ancora(@$Ld42_codigo,"js_pesquisad42_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d42_codigo',10,$Id42_codigo,true,'text',$db_opcao," onchange='js_pesquisad42_codigo(false);'")
?>
       <?
db_input('d40_codigo',10,$Id40_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td42_numcgm?>">
       <?
       db_ancora(@$Ld42_numcgm,"js_pesquisad42_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d42_numcgm',6,$Id42_numcgm,true,'text',$db_opcao," onchange='js_pesquisad42_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisad42_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_projmelhorias.php?funcao_js=parent.js_mostraprojmelhorias1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_projmelhorias.php?pesquisa_chave='+document.form1.d42_codigo.value+'&funcao_js=parent.js_mostraprojmelhorias';
  }
}
function js_mostraprojmelhorias(chave,erro){
  document.form1.d40_codigo.value = chave; 
  if(erro==true){ 
    document.form1.d42_codigo.focus(); 
    document.form1.d42_codigo.value = ''; 
  }
}
function js_mostraprojmelhorias1(chave1,chave2){
  document.form1.d42_codigo.value = chave1;
  document.form1.d40_codigo.value = chave2;
  db_iframe.hide();
}
function js_pesquisad42_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.d42_numcgm.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.d42_numcgm.focus(); 
    document.form1.d42_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.d42_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_projmelhoriasresp.php?funcao_js=parent.js_preenchepesquisa|0';
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