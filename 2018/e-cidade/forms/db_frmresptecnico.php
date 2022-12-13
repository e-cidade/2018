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
include("classes/db_sanitario_classe.php");
include("dbforms/db_classesgenericas.php");
$clsanitario = new cl_sanitario;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clresptecnico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y80_numcgm");
$clrotulo->label("z01_nome");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_resptecnico.location.href='fis1_resptecnico002.php?chavepesquisa=$y22_codsani&chavepesquisa1=$y22_numcgm'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_resptecnico.location.href='fis1_resptecnico003.php?chavepesquisa=$y22_codsani&chavepesquisa1=$y22_numcgm'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty22_codsani?>">
       <?=$Ly22_codsani;
      // db_ancora(@$Ly22_codsani,"js_pesquisay22_codsani(true);",3);
       ?>
    </td>
    <td> 
<?
if (isset($y22_codsani)&&$y22_codsani!=""){
    // $xx = $clsanitario->sql_query($y22_codsani,"z01_nome as z01_nomesani");
    // echo "$xx"; 
	$result_nome = $clsanitario->sql_record($clsanitario->sql_query($y22_codsani,"z01_nome as z01_nomesani"));
	if ($clsanitario->numrows>0){
   		db_fieldsmemory($result_nome,0);	
	}
	
}
db_input('y22_codsani',8,$Iy22_codsani,true,'text',3,"");
//echo "<script>js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+document.form1.y22_codsani.value+'&funcao_js=parent.js_mostrasanitario','Pesquisa',false);</script>";
?>
       <?
db_input('z01_nomesani',40,$Iy80_numcgm,true,'text',3,'','z01_nomesani'); 
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty22_numcgm?>">
       <?
       db_ancora(@$Ly22_numcgm,"js_pesquisay22_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y22_numcgm',8,$Iy22_numcgm,true,'text',$db_opcao," onchange='js_pesquisay22_numcgm(false);'");
if($db_opcao == 2){
  db_input('y22_numcgm',8,$Iy22_numcgm,true,'hidden',$db_opcao," ",'y22_numcgm_old');
  echo "<script>document.form1.y22_numcgm_old.value='$y22_numcgm'</script>";
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
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("y22_codsani"=>$y22_codsani,"y22_numcgm"=>@$y22_numcgm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y22_codsani,y22_numcgm,z01_nome";
    $cliframe_alterar_excluir->sql=$clresptecnico->sql_query("","","*",""," y22_codsani = $y22_codsani");
    $cliframe_alterar_excluir->legenda="RESPONSÁVEIS TÉCNICOS";
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

function js_pesquisay22_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.y22_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}


function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y22_numcgm.focus(); 
    document.form1.y22_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.y22_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
</script>