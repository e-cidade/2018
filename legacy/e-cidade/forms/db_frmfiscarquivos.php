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
include("classes/db_fiscaltipo_classe.php");
$clfiscaltipo = new cl_fiscaltipo;
$clfiscarquivos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y30_data");
$clrotulo->label("db02_descr");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_artigos.location.href='fis1_fiscarquivos002.php?chavepesquisa=$y26_codnoti&chavepesquisa1=$y26_idparag'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_artigos.location.href='fis1_fiscarquivos003.php?chavepesquisa=$y26_codnoti&chavepesquisa1=$y26_idparag'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty26_codnoti?>">
       <?
       db_ancora(@$Ly26_codnoti,"js_pesquisay26_codnoti(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y26_codnoti',8,$Iy26_codnoti,true,'text',3," onchange='js_pesquisay26_codnoti(false);'")
?>
       <?
db_input('y30_data',10,$Iy30_data,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty26_idparag?>">
       <?
       db_ancora(@$Ly26_idparag,"js_pesquisay26_idparag(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y26_idparag',8,$Iy26_idparag,true,'text',$db_opcao," onchange='js_pesquisay26_idparag(false);'");
if($db_opcao == 2){
  db_input('y26_idparag',8,$Iy26_idparag,true,'hidden',$db_opcao," ","y26_idparag_old");
  echo "<script>document.form1.y26_idparag_old.value='$y26_idparag'</script>";
}
?>
       <?
db_input('db02_descr',40,$Idb02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?
      if(($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33)){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_fiscarquivos001.php?y26_codnoti=<?=$y26_codnoti?>&abas=1&y39_codandam=10911'">
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("y26_codnoti"=>$y26_codnoti,"y26_idparag"=>@$y26_idparag);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y26_codnoti,y26_idparag,db02_descr";
    $cliframe_alterar_excluir->sql=$clfiscarquivos->sql_query("","","*",""," y26_codnoti = $y26_codnoti");
    $cliframe_alterar_excluir->legenda="ARTIGOS DA NOTIFICAÇÃO";
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
  js_tabulacaoforms("form1","y26_idparag",true,1,"y26_idparag",true);
}
function js_pesquisay26_codnoti(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_fiscal','func_fiscal.php?funcao_js=parent.js_mostrafiscal1|y30_codnoti|y30_data','Pesquisa',true);
  }else{
     if(document.form1.y26_codnoti.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_fiscal','func_fiscal.php?pesquisa_chave='+document.form1.y26_codnoti.value+'&funcao_js=parent.js_mostrafiscal','Pesquisa',false);
     }else{
       document.form1.y30_data.value = ''; 
     }
  }
}
function js_mostrafiscal(chave,erro){
  document.form1.y30_data.value = chave; 
  if(erro==true){ 
    document.form1.y26_codnoti.focus(); 
    document.form1.y26_codnoti.value = ''; 
  }
}
function js_mostrafiscal1(chave1,chave2){
  document.form1.y26_codnoti.value = chave1;
  document.form1.y30_data.value = chave2;
  db_iframe_fiscal.hide();
}
function js_pesquisay26_idparag(mostra){
<?
   $result = $clfiscaltipo->sql_record($clfiscaltipo->sql_query($y26_codnoti)); 
   if($clfiscaltipo->numrows > 0){
     db_fieldsmemory($result,0);
   }
?>
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_paragrafo','func_db_docparag.php?db04_docum=<?=@$y29_docum?>&funcao_js=parent.js_mostradb_paragrafo1|db04_idparag|db02_descr','Pesquisa',true);
  }else{
     if(document.form1.y26_idparag.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_paragrafo','func_db_docparag.php?db04_docum=<?=@$y29_docum?>&pesquisa_chave='+document.form1.y26_idparag.value+'&funcao_js=parent.js_mostradb_paragrafo','Pesquisa',false);
     }else{
       document.form1.db02_descr.value = ''; 
     }
  }
}
function js_mostradb_paragrafo(chave,erro){
  document.form1.db02_descr.value = chave; 
  if(erro==true){ 
    document.form1.y26_idparag.focus(); 
    document.form1.y26_idparag.value = ''; 
  }
}
function js_mostradb_paragrafo1(chave1,chave2){
  document.form1.y26_idparag.value = chave1;
  document.form1.db02_descr.value = chave2;
  db_iframe_db_paragrafo.hide();
}
</script>