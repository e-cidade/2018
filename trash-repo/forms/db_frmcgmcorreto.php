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
$clcgmcorreto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tz10_codigo?>">
       <?=@$Lz10_codigo?>
    </td>
    <td> 
<?
db_input('z10_codigo',10,$Iz10_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz10_numcgm?>">
       <?
       db_ancora(@$Lz10_numcgm,"js_pesquisaz10_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('z10_numcgm',8,$Iz10_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz10_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz10_data?>">
       <?=@$Lz10_data?>
    </td>
    <td> 
<?
if(empty($z10_data_dia)){
  $z10_data_dia = date("d",db_getsession("DB_datausu"));
  $z10_data_mes = date("m",db_getsession("DB_datausu"));
  $z10_data_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('z10_data',@$z10_data_dia,@$z10_data_mes,@$z10_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz10_hora?>">
       <?=@$Lz10_hora?>
    </td>
    <td> 
<?
db_input('z10_hora',5,$Iz10_hora,true,'text',$db_opcao,"");
if($db_opcao == 1){
  echo "<script>document.form1.z10_hora.value='".db_hora()."'</script>";
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz10_login?>">
       <?
       db_ancora(@$Lz10_login,"js_pesquisaz10_login(true);",3);
       ?>
    </td>
    <td> 
<?
if (isset($z10_login)&&$z10_login!=""){
$sql = "select nome,id_usuario as z10_login from db_usuarios where id_usuario = ".@$z10_login;
db_fieldsmemory(pg_query($sql),0);
}
db_input('z10_login',5,$Iz10_login,true,'text',3," onchange='js_pesquisaz10_login(false);'");
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz10_proc?>">
       <?=@$Lz10_proc?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('z10_proc',$x,true,$db_opcao,($db_opcao == 1?"onChange='this.options[0].selected = true'":""));
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaz10_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z10_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z10_numcgm.focus(); 
    document.form1.z10_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z10_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisaz10_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.z10_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.z10_login.focus(); 
    document.form1.z10_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.z10_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_cgmcorreto','func_cgmcorreto.php?funcao_js=parent.js_preenchepesquisa|z10_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cgmcorreto.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'pro1_cgmcorreto002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'pro1_cgmcorreto003.php?abas=1&chavepesquisa='+chave;";
    }
  ?>
}
</script>