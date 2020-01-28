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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clfisctestem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y30_data");
$clrotulo->label("z01_nome");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_test.location.href='fis1_fisctestem002.php?chavepesquisa=$y23_codnoti&chavepesquisa1=$y23_numcgm'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_test.location.href='fis1_fisctestem003.php?chavepesquisa=$y23_codnoti&chavepesquisa1=$y23_numcgm'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty23_codnoti?>">
       <?
       db_ancora(@$Ly23_codnoti,"js_pesquisay23_codnoti(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y23_codnoti',10,$Iy23_codnoti,true,'text',3," onchange='js_pesquisay23_codnoti(false);'")
?>
       <?
db_input('y30_data',10,$Iy30_data,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty23_numcgm?>">
       <?
       db_ancora(@$Ly23_numcgm,"js_pesquisay23_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y23_numcgm',8,$Iy23_numcgm,true,'text',$db_opcao," onchange='js_pesquisay23_numcgm(false);'");
if($db_opcao == 2){
  db_input('y23_numcgm',8,$Iy23_numcgm,true,'hidden',$db_opcao," ","y23_numcgm_old");
  echo "<script>document.form1.y23_numcgm_old.value='$y23_numcgm'</script>";
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
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_fisctestem001.php?y23_codnoti=<?=$y23_codnoti?>'">
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("y23_codnoti"=>$y23_codnoti,"y23_numcgm"=>@$y23_numcgm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y23_codnoti,y23_numcgm,z01_nome";
    $cliframe_alterar_excluir->sql=$clfisctestem->sql_query("","","*",""," y23_codnoti = $y23_codnoti");
    $cliframe_alterar_excluir->legenda="TESTEMUNHAS DA NOTIFICAÇÃO";
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
function js_setatabulacao(){
  js_tabulacaoforms("form1","y23_numcgm",true,1,"y23_numcgm",true);
}
function js_pesquisay23_codnoti(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_fiscal','func_fiscal.php?funcao_js=parent.js_mostrafiscal1|y30_codnoti|y30_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_fiscal','func_fiscal.php?pesquisa_chave='+document.form1.y23_codnoti.value+'&funcao_js=parent.js_mostrafiscal','Pesquisa',false);
  }
}
function js_mostrafiscal(chave,erro){
  document.form1.y30_data.value = chave; 
  if(erro==true){ 
    document.form1.y23_codnoti.focus(); 
    document.form1.y23_codnoti.value = ''; 
  }
}
function js_mostrafiscal1(chave1,chave2){
  document.form1.y23_codnoti.value = chave1;
  document.form1.y30_data.value = chave2;
  db_iframe_fiscal.hide();
}
function js_pesquisay23_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.y23_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y23_numcgm.focus(); 
    document.form1.y23_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.y23_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
</script>