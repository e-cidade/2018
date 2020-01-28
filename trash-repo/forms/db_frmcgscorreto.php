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
$clcgscorreto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_v_nome");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts127_i_codigo?>">
       <?=@$Ls127_i_codigo?>
    </td>
    <td> 
<?
db_input('s127_i_codigo',10,$Is127_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts127_i_numcgs?>">
       <?
       db_ancora(@$Ls127_i_numcgs,"js_pesquisas127_i_numcgs(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('s127_i_numcgs',8,$Is127_i_numcgs,true,'text',$db_opcao," onchange='js_pesquisas127_i_numcgs(false);'")
?>
       <?
db_input('z01_v_nome',40,$Iz01_v_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts127_d_data?>">
       <?=@$Ls127_d_data?>
    </td>
    <td> 
<?
if(empty($s127_d_data_dia)){
  $s127_d_data_dia = date("d",db_getsession("DB_datausu"));
  $s127_d_data_mes = date("m",db_getsession("DB_datausu"));
  $s127_d_data_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('s127_d_data',@$s127_d_data_dia,@$s127_d_data_mes,@$s127_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts127_c_hora?>">
       <?=@$Ls127_c_hora?>
    </td>
    <td> 
<?
db_input('s127_c_hora',5,$Is127_c_hora,true,'text',$db_opcao,"");
if($db_opcao == 1){
  echo "<script>document.form1.s127_c_hora.value='".db_hora()."'</script>";
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts127_i_login?>">
       <?
       db_ancora(@$Ls127_i_login,"js_pesquisas127_i_login(true);",3);
       ?>
    </td>
    <td> 
<?
if (isset($s127_i_login)&&$s127_i_login!=""){
$sql = "select nome,id_usuario as s127_i_login from db_usuarios where id_usuario = ".@$s127_i_login;
db_fieldsmemory(pg_query($sql),0);
}
db_input('s127_i_login',5,$Is127_i_login,true,'text',3," onchange='js_pesquisas127_i_login(false);'");
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts127_b_proc?>">
       <?=@$Ls127_b_proc?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('s127_b_proc',$x,true,$db_opcao,($db_opcao == 1?"onChange='this.options[0].selected = true'":""));
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisas127_i_numcgs(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.s127_i_numcgs.value+'&funcao_js=parent.js_mostracgs','Pesquisa',false);
  }
}
function js_mostracgs(erro,chave){
  document.form1.z01_v_nome.value = chave; 
  if(erro==true){ 
    document.form1.s127_i_numcgs.focus(); 
    document.form1.s127_i_numcgs.value = ''; 
  }
}
function js_mostracgs1(chave1,chave2){
  document.form1.s127_i_numcgs.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  db_iframe_cgs_und.hide();
}
function js_pesquisas127_i_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.s127_||_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.s127_i_login.focus(); 
    document.form1.s127_i_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.s127_i_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_sau_cgscorreto','func_sau_cgscorreto.php?funcao_js=parent.js_preenchepesquisa|s127_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_cgscorreto.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'sau4_cgscorreto002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'sau4_cgscorreto003.php?abas=1&chavepesquisa='+chave;";
    }
  ?>
}
</script>