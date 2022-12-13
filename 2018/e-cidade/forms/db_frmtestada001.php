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
$cltestada->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j34_setor");
$clrotulo->label("j14_nome");
$db_opcao = 2;
if ($testa!="") {
  $result = $cltestada->sql_record($cltestada->sql_query($testa));
    if($cltestada->numrows!=0){
      db_fieldsmemory($result,0);
    }else{
	  echo "<script>parent.db_iframe.hide();</script>";   
      echo "<script>alert('Não há Testadas Cadastradas para o lote ".$testa."');</script>";
	}
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj36_idbql?>">
       <?
       db_ancora(@$Lj36_idbql,"js_pesquisaj36_idbql(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j36_idbql',4,$Ij36_idbql,true,'text',$db_opcao," onchange='js_pesquisaj36_idbql(false);'")
?>
       <?
db_input('j34_setor',4,$Ij34_setor,true,'text',3,'')
       ?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj36_face?>">
       <?=@$Lj36_face?>
    </td>
    <td> 
<?
db_input('j36_face',4,$Ij36_face,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj36_codigo?>">
       <?
       db_ancora(@$Lj36_codigo,"js_pesquisaj36_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j36_codigo',4,$Ij36_codigo,true,'text',$db_opcao," onchange='js_pesquisaj36_codigo(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj36_testad?>">
       <?=@$Lj36_testad?>
    </td>
    <td> 
<?
db_input('j36_testad',15,$Ij36_testad,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj36_testle?>">
       <?=@$Lj36_testle?>
    </td>
    <td> 
<?
db_input('j36_testle',15,$Ij36_testle,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisaj36_idbql(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_lote.php?funcao_js=parent.js_mostralote1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_lote.php?pesquisa_chave='+document.form1.j36_idbql.value+'&funcao_js=parent.js_mostralote';
  }
}
function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.j36_idbql.focus(); 
    document.form1.j36_idbql.value = ''; 
  }
}
function js_mostralote1(chave1,chave2){
  document.form1.j36_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  db_iframe.hide();
}
function js_pesquisaj36_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j36_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j36_codigo.focus(); 
    document.form1.j36_codigo.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j36_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_testada.php?pesquisa_chave=<?=$testa?>';
  db_iframe.mostraMsg();
//  db_iframe.show();
//  db_iframe.focus();
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