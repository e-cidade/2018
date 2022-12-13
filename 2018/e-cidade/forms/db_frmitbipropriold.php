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

//MODULO: itbi
include("dbforms/db_classesgenericas.php");
$clitbipropriold->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it01_guia");
$clrotulo->label("z01_nome");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_old.location.href='itb1_itbipropriold002.php?chavepesquisa=$it20_guia&it20_numcgm=$it20_numcgm'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_old.location.href='itb1_itbipropriold003.php?chavepesquisa=$it20_guia&it20_numcgm=$it20_numcgm'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit20_guia?>">
       <?
       db_ancora(@$Lit20_guia,"js_pesquisait20_guia(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('it20_guia',10,$Iit20_guia,true,'text',3," onchange='js_pesquisait20_guia(false);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit20_numcgm?>">
       <?
       db_ancora(@$Lit20_numcgm,"js_pesquisait20_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('it20_numcgm',8,$Iit20_numcgm,true,'text',$db_opcao," onchange='js_pesquisait20_numcgm(false);'");
@$it20_numcgm_old = @$it20_numcgm;
db_input('it20_numcgm',8,$Iit20_numcgm,true,'hidden',$db_opcao," onchange='js_pesquisait20_numcgm(false);'","it20_numcgm_old")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit20_pri?>">
       <?=@$Lit20_pri?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('it20_pri',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("it20_guia"=>@$it20_guia,"it20_numcgm"=>@$it20_numcgm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="it20_guia,it20_numcgm,z01_nome,it20_pri";
    $cliframe_alterar_excluir->sql=$clitbipropriold->sql_query($it20_guia);
    $cliframe_alterar_excluir->legenda="Proprietários";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Proprietário Cadastrado!</font>";
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
function js_pesquisait20_guia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia','Pesquisa',true);
  }else{
     if(document.form1.it20_guia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?pesquisa_chave='+document.form1.it20_guia.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
       document.form1.it01_guia.value = ''; 
     }
  }
}
function js_mostraitbi(chave,erro){
  document.form1.it01_guia.value = chave; 
  if(erro==true){ 
    document.form1.it20_guia.focus(); 
    document.form1.it20_guia.value = ''; 
  }
}
function js_mostraitbi1(chave1,chave2){
  document.form1.it20_guia.value = chave1;
  document.form1.it01_guia.value = chave2;
  db_iframe_itbi.hide();
}
function js_pesquisait20_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.it20_numcgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&pesquisa_chave='+document.form1.it20_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.it20_numcgm.focus(); 
    document.form1.it20_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.it20_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itbipropriold','func_itbipropriold.php?funcao_js=parent.js_preenchepesquisa|it20_guia','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itbipropriold.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>