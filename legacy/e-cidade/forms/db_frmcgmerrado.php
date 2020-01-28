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

//MODULO: protocolo
include("dbforms/db_classesgenericas.php");
include("classes/db_cgmcorreto_classe.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcgmerrado->rotulo->label();
$clrotulo = new rotulocampo;
$clcgmcorreto = new cl_cgmcorreto;
$clrotulo->label("z10_numcgm");
$clrotulo->label("z01_nome");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_cgmerrado.location.href='pro1_cgmerrado002.php?chavepesquisa=$z11_codigo&chavepesquisa1=$z11_numcgm'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_cgmerrado.location.href='pro1_cgmerrado003.php?chavepesquisa=$z11_codigo&chavepesquisa1=$z11_numcgm'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tz11_codigo?>">
       <?
       db_ancora(@$Lz11_codigo,"js_pesquisaz11_codigo(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('z11_codigo',10,$Iz11_codigo,true,'text',3," onchange='js_pesquisaz11_codigo(false);'");
?>
       <?
db_input('z10_numcgm',8,$Iz10_numcgm,true,'hidden',3,'');
echo "<script>js_OpenJanelaIframe('','db_iframe_cgmcorreto','func_cgmcorreto.php?pesquisa_chave='+document.form1.z11_codigo.value+'&funcao_js=parent.js_mostracgmcorreto','Pesquisa',false);</script>";
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz11_numcgm?>">
       <?
       db_ancora(@$Lz11_numcgm,"js_pesquisaz11_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('z11_numcgm',8,$Iz11_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz11_numcgm(false);'");
if($db_opcao == 2){
  db_input('z11_numcgm',8,$Iz11_numcgm,true,'hidden',3,'','z11_numcgm_old');
  echo "<script>document.form1.z11_numcgm_old.value='$z11_numcgm'</script>";
}
?>
       <?
db_input('z11_nome',40,$Iz11_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?  
   $sql="select * from cgmerrado where z11_codigo=$z11_codigo"; 
   //$sql="$clcgmerrado->sql_query("","","cgmerrado.z11_numcgm,cgm.z01_nome,cgmerrado.z11_codigo",""," cgmerrado.z11_codigo = ".@$z11_codigo."")";
   //die($sql);
//die($clcgmerrado->sql_query("","","cgmerrado.z11_numcgm,cgm.z01_nome,cgmerrado.z11_codigo",""," cgmerrado.z11_codigo = ".@$z11_codigo.""));
    $chavepri= array("z11_codigo"=>@$z11_codigo,"z11_numcgm"=>@$z11_numcgm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="z11_codigo,z11_numcgm,z11_nome";
    $cliframe_alterar_excluir->sql=$sql;
    $cliframe_alterar_excluir->legenda="CGM's";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum CGM Cadastrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir(1);    
   ?>
   </td>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_pesquisaz11_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgmcorreto','func_cgmcorreto.php?funcao_js=parent.js_mostracgmcorreto1|z10_codigo|z10_numcgm','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgmcorreto','func_cgmcorreto.php?pesquisa_chave='+document.form1.z11_codigo.value+'&funcao_js=parent.js_mostracgmcorreto','Pesquisa',false);
  }
}
function js_mostracgmcorreto(erro,chave){
  document.form1.z10_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.z11_codigo.focus(); 
    document.form1.z11_codigo.value = ''; 
  }
}
function js_mostracgmcorreto1(chave1,chave2){
  document.form1.z11_codigo.value = chave1;
  document.form1.z10_numcgm.value = chave2;
  db_iframe_cgmcorreto.hide();
}
function js_pesquisaz11_numcgm(mostra){
  if(document.form1.z11_numcgm.value == document.form1.z10_numcgm.value){
    alert('Você não pode utilizar o mesmo número do CGM correto!');
    document.form1.z11_numcgm.value = '';
    document.form1.z11_numcgm.focus = '';
  }else{
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z11_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z11_nome.value = chave; 
  if(erro==true){ 
    document.form1.z11_numcgm.focus(); 
    document.form1.z11_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z11_numcgm.value = chave1;
  document.form1.z11_nome.value = chave2;
  db_iframe_cgm.hide();
}
</script>