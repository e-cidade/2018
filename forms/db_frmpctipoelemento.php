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

//MODULO: compras
$clpctipoelemento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc05_descr");
$clrotulo->label("o56_elemento");
?>
<script>
  function js_verifica(){
    if(document.form1.pc06_codtipo.value==''){
      alert('campo vazio');
      return false;
    }else{
      return true;
    }
  }
</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc06_codtipo?>">
       <?
       db_ancora(@$Lpc06_codtipo,"js_pesquisapc06_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc06_codtipo',6,$Ipc06_codtipo,true,'text',$db_opcao," onchange='js_pesquisapc06_codtipo(false);'")
?>
       <?
db_input('pc05_descr',40,$Ipc05_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
<?
   if(isset($pc06_codtipo) && $pc06_codtipo!=""){
?>
  <tr>
    <td nowrap title="<?=@$Tpc06_codele?>">
       <?
       db_ancora(@$Lpc06_codele,"js_pesquisapc06_codele(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc06_codele',6,$Ipc06_codele,true,'text',$db_opcao," onchange='js_pesquisapc06_codele(false);'")
?>
       <?
db_input('o56_elemento',40,$Io56_elemento,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td colspan='2' align ='center'>
<?  
      $chavepri= array("pc06_codtipo"=>$pc06_codtipo,"pc06_codele"=>@$pc06_codele);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      $cliframe_alterar_excluir->sql     = $clpctipoelemento->sql_query(null,null,"pc06_codtipo,pc06_codele,o56_descr","pc06_codele","pc06_codtipo=$pc06_codtipo");
      $cliframe_alterar_excluir->campos  ="pc06_codele,o56_descr";
     // $cliframe_alterar_excluir->legenda="VALORES DE LANÇADOS";
      $cliframe_alterar_excluir->iframe_height ="140";
      $cliframe_alterar_excluir->iframe_width ="700";
      $cliframe_alterar_excluir->opcoes =3;
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?>
    </td>
  </tr>
<?
   }else{
?>
    <tr>
      <td colspan='2' align='center'>
       <input type='submit' value="Entrar" name='entrar' onclick=" return js_verifica();"> 
      </td>
    </tr>  
    
<?
   }
?>
  </table>
  </center>
</form>
<script>
function js_pesquisapc06_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pctipo','func_pctipo.php?funcao_js=parent.js_mostrapctipo1|pc05_codtipo|pc05_descr','Pesquisa',true);
  }else{
     if(document.form1.pc06_codtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pctipo','func_pctipo.php?pesquisa_chave='+document.form1.pc06_codtipo.value+'&funcao_js=parent.js_mostrapctipo','Pesquisa',false);
     }else{
       document.form1.pc05_descr.value = ''; 
     }
  }
}
function js_mostrapctipo(chave,erro){
  document.form1.pc05_descr.value = chave; 
  if(erro==true){ 
    document.form1.pc06_codtipo.focus(); 
    document.form1.pc06_codtipo.value = ''; 
  }
}
function js_mostrapctipo1(chave1,chave2){
  document.form1.pc06_codtipo.value = chave1;
  document.form1.pc05_descr.value = chave2;
  db_iframe_pctipo.hide();
}
function js_pesquisapc06_codele(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento','Pesquisa',true);
  }else{
     if(document.form1.pc06_codele.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.pc06_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_elemento.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.pc06_codele.focus(); 
    document.form1.pc06_codele.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.pc06_codele.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pctipoelemento','func_pctipoelemento.php?funcao_js=parent.js_preenchepesquisa|pc06_codtipo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pctipoelemento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>