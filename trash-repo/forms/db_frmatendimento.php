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

//MODULO: atendimento
include("classes/db_tipoatend_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_clientes_classe.php");
$cldb_usuarios = new cl_db_usuarios;
$clclientes = new cl_clientes;
$cltecnico = new cl_tecnico;
$cltecnico->rotulo->label();
$cltipoatend = new cl_tipoatend;
$cltipoatend->rotulo->label();
$clatendimento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at01_nomecli");
$clrotulo->label("nome");
$clrotulo->label("at03_codatend");
$clrotulo->label("at03_id_usuario");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat02_codatend?>">
       <?=@$Lat02_codatend?>
    </td>
    <td> 
<?
db_input('at02_codatend',4,$Iat02_codatend,true,'3',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_codcli?>">
       <?
       db_ancora(@$Lat02_codcli,"js_pesquisaat02_codcli(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at02_codcli',4,$Iat02_codcli,true,'text',$db_opcao,"onchange='js_pesquisaat02_codcli(false);'")
?>
<?
db_input('at01_nomecli',40,$Iat01_nomecli,true,'text',3,'')
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat03_id_usuario?>">
       <?=@$Lat03_id_usuario?>
    </td>
    <td> 
<?
db_selectmultiple('at03_id_usuario',($cldb_usuarios->sql_record($cldb_usuarios->sql_query("","id_usuario,nome",""," usuext = 0 and usuarioativo = '1'"))),10,$db_opcao,"","","",($db_opcao==1||$db_opcao==22||$db_opcao==33?0:$cltecnico->sql_record($cltecnico->sql_query($at02_codatend,"","at03_id_usuario,nome","",""))))
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_codtipo?>">
       <?=@$Lat02_codtipo?>
    </td>
    <td> 
<?
db_selectrecord('at02_codtipo',($cltipoatend->sql_record($cltipoatend->sql_query(($db_opcao==2?"":@$at02_codtipo),"*","",""))),true,$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_solicitado?>">
       <?=@$Lat02_solicitado?>
    </td>
    <td> 
<?
db_textarea('at02_solicitado',0,40,$Iat02_solicitado,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_feito?>">
       <?=@$Lat02_feito?>
    </td>
    <td> 
<?
db_textarea('at02_feito',0,40,$Iat02_feito,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_dataini?>">
       <?=@$Lat02_dataini?>
    </td>
    <td> 
<?
if($db_opcao == 1){
  $at02_dataini_dia = date("d",db_getsession("DB_datausu"));
  $at02_dataini_mes = date("m",db_getsession("DB_datausu"));
  $at02_dataini_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('at02_dataini',@$at02_dataini_dia,@$at02_dataini_mes,@$at02_dataini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_datafim?>">
       <?=@$Lat02_datafim?>
    </td>
    <td> 
<?
if($db_opcao == 1){
  $at02_datafim_dia = date("d",db_getsession("DB_datausu"));
  $at02_datafim_mes = date("m",db_getsession("DB_datausu"));
  $at02_datafim_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('at02_datafim',@$at02_datafim_dia,@$at02_datafim_mes,@$at02_datafim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_horaini?>">
       <?=@$Lat02_horaini?>
    </td>
    <td> 
<?
db_input('at02_horaini',5,$Iat02_horaini,true,'text',$db_opcao,"");
if($db_opcao == 1){
  echo "<script>document.form1.at02_horaini.value='".db_hora()."'</script>";
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_horafim?>">
       <?=@$Lat02_horafim?>
    </td>
    <td> 
<?
db_input('at02_horafim',5,$Iat02_horafim,true,'text',$db_opcao,"");
if($db_opcao == 1){
  echo "<script>document.form1.at02_horafim.value='".db_hora()."'</script>";
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_observacao?>">
       <?=@$Lat02_observacao?>
    </td>
    <td> 
<?
db_textarea('at02_observacao',0,40,$Iat02_observacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaat02_codcli(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_clientes.php?funcao_js=parent.js_mostraclientes1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_clientes.php?pesquisa_chave='+document.form1.at02_codcli.value+'&funcao_js=parent.js_mostraclientes';
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.at02_codcli.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe.hide();
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave; 
  if(erro==true){ 
    document.form1.at02_codcli.focus(); 
    document.form1.at02_codcli.value = ''; 
  }
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_atendimento.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'ate1_atendimento002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'ate1_atendimento003.php?abas=1&chavepesquisa='+chave;";
    }
  ?>
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
if($db_opcao == 1){
  echo "<script>
      for(i=0;i<document.getElementById('at03_codatend').length;i++){
	usu = document.form1.at03_codatend.options[i];
        if(usu.value == ".db_getsession("DB_id_usuario")."){
	  usu.selected = true;
	}
      }
      </script>";
}
$result = $clclientes->sql_record($clclientes->sql_query("","*"));
if($clclientes->numrows == 1){
  db_fieldsmemory($result,0);
  echo "<script>db_iframe.jan.location.href = 'func_clientes.php?pesquisa_chave=$at01_codcli&funcao_js=parent.js_mostraclientes';</script>";
  echo "<script>document.form1.at02_codcli.value='$at01_codcli'</script>";
}
?>