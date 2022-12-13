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
$cltipovistoriasrec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y77_descricao");
$clrotulo->label("k02_descr");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_vistrec.location.href='fis1_tipovistoriasrec002.php?chavepesquisa=$y78_codtipo&chavepesquisa1=$y78_receit'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_vistrec.location.href='fis1_tipovistoriasrec003.php?chavepesquisa=$y78_codtipo&chavepesquisa1=$y78_receit'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty78_codtipo?>">
       <?
       db_ancora(@$Ly78_codtipo,"js_pesquisay78_codtipo(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y78_codtipo',10,$Iy78_codtipo,true,'text',3," onchange='js_pesquisay78_codtipo(false);'");
?>
       <?
db_input('y77_descricao',50,$Iy77_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty78_receit?>">
       <?
       db_ancora(@$Ly78_receit,"js_pesquisay78_receit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y78_receit',4,$Iy78_receit,true,'text',$db_opcao," onchange='js_pesquisay78_receit(false);'");
if($db_opcao == 2){
  db_input('y78_receit',4,$Iy78_receit,true,'hidden',$db_opcao,"","y78_receit_old");
  echo "<script>document.form1.y78_receit_old.value = '$y78_receit'</script>";
}
?>
       <?
db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty78_valor?>">
       <?=@$Ly78_valor?>
    </td>
    <td> 
<?
db_input('y78_valor',10,$Iy78_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty78_descr?>">
       <?=@$Ly78_descr?>
    </td>
    <td> 
<?
db_input('y78_descr',50,$Iy78_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td colspan="2" align="top">
   <?
    $chavepri= array("y78_codtipo"=>@$y78_codtipo,"y78_receit"=>@$y78_receit);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y78_codtipo,y78_receit,y78_valor,y78_descr";
    $cliframe_alterar_excluir->sql=$cltipovistoriasrec->sql_query("","","*",""," y78_codtipo = $y78_codtipo");
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
function js_pesquisay78_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipovistorias','func_tipovistorias.php?funcao_js=parent.js_mostratipovistorias1|y77_codtipo|y77_descricao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipovistorias','func_tipovistorias.php?pesquisa_chave='+document.form1.y78_codtipo.value+'&funcao_js=parent.js_mostratipovistorias','Pesquisa',false);
  }
}
function js_mostratipovistorias(chave,erro){
  document.form1.y77_descricao.value = chave; 
  if(erro==true){ 
    document.form1.y78_codtipo.focus(); 
    document.form1.y78_codtipo.value = ''; 
  }
}
function js_mostratipovistorias1(chave1,chave2){
  document.form1.y78_codtipo.value = chave1;
  document.form1.y77_descricao.value = chave2;
  db_iframe_tipovistorias.hide();
}
function js_pesquisay78_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.y78_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.y78_receit.focus(); 
    document.form1.y78_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.y78_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_tipovistoriasrec','func_tipovistoriasrec.php?funcao_js=parent.js_preenchepesquisa|y78_codtipo|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_tipovistoriasrec.hide();
}
</script>
<?
  if(isset($y78_codtipo) && $y78_codtipo != ""){
    echo "<script>js_OpenJanelaIframe('','db_iframe_tipovistorias1','func_tipovistorias.php?pesquisa_chave=$y78_codtipo&funcao_js=parent.js_mostratipovistorias','Pesquisa',false);</script>";
  }
?>