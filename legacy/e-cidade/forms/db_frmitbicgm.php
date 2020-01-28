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

//MODULO: itbI
$clitbicgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("it01_guia");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_comp.location.href='itb1_itbicgm002.php?chavepesquisa=$it02_guia&chavepesquisa1=$it02_numcgm'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_comp.location.href='itb1_itbicgm003.php?chavepesquisa=$it02_guia&chavepesquisa1=$it02_numcgm'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit02_guia?>">
       <?
       db_ancora(@$Lit02_guia,"js_pesquisait02_guia(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('it02_guia',10,$Iit02_guia,true,'text',3," onchange='js_pesquisait02_guia(false);'")
?>
       <?
db_input('it01_guia',10,$Iit01_guia,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit02_numcgm?>">
       <?
       db_ancora(@$Lit02_numcgm,"js_pesquisait02_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('it02_numcgm',8,$Iit02_numcgm,true,'text',3," onchange='js_pesquisait02_numcgm(false);'");
db_input('it02_numcgm',8,$Iit02_numcgm,true,'hidden',$db_opcao,"","it02_numcgm_old");
if($db_opcao == 2){
  echo "<script>document.form1.it02_numcgm_old.value='$it02_numcgm'</script>";
}
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("it02_guia"=>@$it02_guia,"it02_numcgm"=>@$it02_numcgm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="it02_guia,it02_numcgm,z01_nome";
    $cliframe_alterar_excluir->sql=$clitbicgm->sql_query("","","*",""," it02_guia = ".@$it02_guia."");
    $cliframe_alterar_excluir->legenda="Compradores";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Comprador Cadastrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
    
    
    $re = $clitbicgm->sql_record($clitbicgm->sql_query("","","*",""," it02_guia = ".@$it02_guia.""));
    if($clitbicgm->numrows > 0){
      echo "<script>parent.document.formaba.constr.disabled = false</script>";
    }
   ?>
   </td>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_pesquisait02_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_comp','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.it02_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_comp','db_iframe_cgm','func_nome.php?testanome=true&pesquisa_chave='+document.form1.it02_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.it02_numcgm.focus(); 
    document.form1.it02_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.it02_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisait02_guia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia','Pesquisa',true);
  }else{
     if(document.form1.it02_guia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?pesquisa_chave='+document.form1.it02_guia.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
       document.form1.it01_guia.value = ''; 
     }
  }
}
function js_mostraitbi(chave,erro){
  document.form1.it01_guia.value = chave; 
  if(erro==true){ 
    document.form1.it02_guia.focus(); 
    document.form1.it02_guia.value = ''; 
  }
}
function js_mostraitbi1(chave1,chave2){
  document.form1.it02_guia.value = chave1;
  document.form1.it01_guia.value = chave2;
  db_iframe_itbi.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itbicgm','func_itbicgm.php?funcao_js=parent.js_preenchepesquisa|it02_numcgm|it02_guia','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_itbicgm.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>