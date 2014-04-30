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

//MODULO: fiscal
$clvistoriarec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y70_id_usuario");
$clrotulo->label("k02_descr");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_receitas.location.href='fis1_vistoriarec002.php?chavepesquisa=$y76_codvist&chavepesquisa1=$y76_receita'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_receitas.location.href='fis1_vistoriarec003.php?chavepesquisa=$y76_codvist&chavepesquisa1=$y76_receita'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty76_codvist?>">
       <?
       db_ancora(@$Ly76_codvist,"js_pesquisay76_codvist(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y76_codvist',10,$Iy76_codvist,true,'text',3," onchange='js_pesquisay76_codvist(false);'")
?>
       <?
db_input('y70_id_usuario',5,$Iy70_id_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty76_receita?>">
       <?
       db_ancora(@$Ly76_receita,"js_pesquisay76_receita(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y76_receita',4,$Iy76_receita,true,'text',$db_opcao," onchange='js_pesquisay76_receita(false);'");
if($db_opcao == 2){
  db_input('y76_receita',4,$Iy76_receita,true,'hidden',$db_opcao,"","y76_receita_old");
  echo "<script>document.form1.y76_receita_old.value = '$y76_receita'</script>";
}
?>
       <?
db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty76_valor?>">
       <?=@$Ly76_valor?>
    </td>
    <td> 
<?
db_input('y76_valor',10,$Iy76_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty76_descr?>">
       <?=@$Ly76_descr?>
    </td>
    <td> 
<?
db_input('y76_descr',50,$Iy76_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?
      if(($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33)){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_vistoriarec001.php?y76_codvist=<?=$y76_codvist?>'">
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="top">
   <?
    $chavepri= array("y76_codvist"=>@$y76_codvist,"y76_receita"=>@$y76_receita);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y76_codvist,y76_receita,y76_valor,y76_descr";
    $cliframe_alterar_excluir->sql=$clvistoriarec->sql_query("","","*",""," y76_codvist = $y76_codvist");
    $cliframe_alterar_excluir->legenda="Receitas da Vistoria";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Registro Encontrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
      
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_setatabulacao(){
  js_tabulacaoforms("form1","y76_receita",true,1,"y76_receita",true);
}
function js_pesquisay76_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_mostravistorias1|y70_codvist|y70_id_usuario','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?pesquisa_chave='+document.form1.y76_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
  }
}
function js_mostravistorias(chave,erro){
  document.form1.y70_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y76_codvist.focus(); 
    document.form1.y76_codvist.value = ''; 
  }
}
function js_mostravistorias1(chave1,chave2){
  document.form1.y76_codvist.value = chave1;
  document.form1.y70_id_usuario.value = chave2;
  db_iframe_vistorias.hide();
}
function js_pesquisay76_receita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.y76_receita.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.y76_receita.focus(); 
    document.form1.y76_receita.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.y76_receita.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_vistoriarec','func_vistoriarec.php?funcao_js=parent.js_preenchepesquisa|y76_codvist|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_vistoriarec.hide();
}
</script>
<?
if(isset($y76_codvist) && $y76_codvist != ""){
  echo "<script>js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?pesquisa_chave=$y76_codvist&funcao_js=parent.js_mostravistorias','Pesquisa',false);</script>";
}
?>