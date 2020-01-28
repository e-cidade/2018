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

//MODULO: vistal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clvistestem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y70_id_usuario");
$clrotulo->label("z01_nome");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_testem.location.href='fis1_vistestem002.php?chavepesquisa=$y25_codvist&chavepesquisa1=$y25_numcgm'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_testem.location.href='fis1_vistestem003.php?chavepesquisa=$y25_codvist&chavepesquisa1=$y25_numcgm'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty25_codvist?>">
       <?
       db_ancora(@$Ly25_codvist,"js_pesquisay25_codvist(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y25_codvist',10,$Iy25_codvist,true,'text',3," onchange='js_pesquisay25_codvist(false);'")
?>
       <?
db_input('y70_id_usuario',10,$Iy70_id_usuario,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty25_numcgm?>">
       <?
       db_ancora(@$Ly25_numcgm,"js_pesquisay25_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y25_numcgm',8,$Iy25_numcgm,true,'text',$db_opcao," onchange='js_pesquisay25_numcgm(false);'");
if($db_opcao == 2){
  db_input('y25_numcgm',8,$Iy25_numcgm,true,'hidden',$db_opcao," ","y25_numcgm_old");
  echo "<script>document.form1.y25_numcgm_old.value='$y25_numcgm'</script>";
}
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?
      if(($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33)){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_vistestem001.php?y25_codvist=<?=$y25_codvist?>&y39_codandam=<?=$y39_codandam?>'">
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("y25_codvist"=>$y25_codvist,"y25_numcgm"=>@$y25_numcgm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y25_codvist,y25_numcgm,z01_nome";
    $cliframe_alterar_excluir->sql=$clvistestem->sql_query("","","*",""," y25_codvist = $y25_codvist");
    $cliframe_alterar_excluir->legenda="TESTEMUNHAS DA VISTORIA";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum registro encontrado!</font>";
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
function js_pesquisay25_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_vistal','func_vistorias.php?funcao_js=parent.js_mostravistal1|y70_codvist|y70_id_usuario','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_vistal','func_vistorias.php?pesquisa_chave='+document.form1.y25_codvist.value+'&funcao_js=parent.js_mostravistal','Pesquisa',false);
  }
}
function js_mostravistal(chave,erro){
  document.form1.y70_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y25_codvist.focus(); 
    document.form1.y25_codvist.value = ''; 
  }
}
function js_mostravistal1(chave1,chave2){
  document.form1.y25_codvist.value = chave1;
  document.form1.y70_id_usuario.value = chave2;
  db_iframe_vistal.hide();
}
function js_pesquisay25_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&pesquisa_chave='+document.form1.y25_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,0);
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y25_numcgm.focus(); 
    document.form1.y25_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.y25_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
</script>