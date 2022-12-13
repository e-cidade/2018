<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: configuracoes
$cldb_sysregrasacesso->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
      if($db_opcao==1){
 	   $db_action="con1_db_sysregrasacesso004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="con1_db_sysregrasacesso005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="con1_db_sysregrasacesso006.php";
      }
?>
<form name="form1" method="post" action="<?=$db_action?>">
    <fieldset>
        <legend>Cadastro de Regra de Acesso</legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb46_idacesso?>">
       <?=@$Ldb46_idacesso?>
    </td>
    <td>
<?
db_input('db46_idacesso',6,$Idb46_idacesso,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb46_dtinicio?>">
       <?=@$Ldb46_dtinicio?>
    </td>
    <td>
<?
db_inputdata('db46_dtinicio',@$db46_dtinicio_dia,@$db46_dtinicio_mes,@$db46_dtinicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb46_horaini?>">
       <?=@$Ldb46_horaini?>
    </td>
    <td>
<?
db_input('db46_horaini',5,$Idb46_horaini,true,'text',$db_opcao," onchange='js_verifica_hora_local(this.value,this.name)'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb46_datafinal?>">
       <?=@$Ldb46_datafinal?>
    </td>
    <td>
<?
db_inputdata('db46_datafinal',@$db46_datafinal_dia,@$db46_datafinal_mes,@$db46_datafinal_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb46_horafinal?>">
       <?=@$Ldb46_horafinal?>
    </td>
    <td>
<?
db_input('db46_horafinal',5,$Idb46_horafinal,true,'text',$db_opcao," onchange='js_verifica_hora_local(this.value,this.name)'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb46_id_usuario?>">
       <?
       db_ancora(@$Ldb46_id_usuario,"js_pesquisadb46_id_usuario(true);",3);
       ?>
    </td>
    <td>
<?
$db46_id_usuario = db_getsession('DB_id_usuario');
db_input('db46_id_usuario',10,$Idb46_id_usuario,true,'text',3," onchange='js_pesquisadb46_id_usuario(false);'")
?>
       <?
       global $nome;
$nome = db_getsession('DB_login');
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb46_datacadastd?>">
       <?=@$Ldb46_datacadastd?>
    </td>
    <td>
<?
$db46_datacadastd_dia = date('d',db_getsession('DB_datausu'));
$db46_datacadastd_mes = date('m',db_getsession('DB_datausu'));
$db46_datacadastd_ano = date('Y',db_getsession('DB_datausu'));
db_inputdata('db46_datacadastd',@$db46_datacadastd_dia,@$db46_datacadastd_mes,@$db46_datacadastd_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb46_observ?>">
       <?=@$Ldb46_observ?>
    </td>
    <td>
<?
db_textarea('db46_observ',5,90,$Idb46_observ,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
    <center>

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </center>

</form>
<script>
function js_pesquisadb46_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_db_sysregrasacesso','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.db46_id_usuario.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_db_sysregrasacesso','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.db46_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.db46_id_usuario.focus();
    document.form1.db46_id_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.db46_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_db_sysregrasacesso','db_iframe_db_sysregrasacesso','func_db_sysregrasacesso.php?funcao_js=parent.js_preenchepesquisa|db46_idacesso','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_sysregrasacesso.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = 'con1_db_sysregrasacesso005.php?chavepesquisa='+chave";
  }
  ?>
}
function js_verifica_hora_local(valor,campo){
  erro= 0;
  ms  = "";
  hs  = "";

  tam = "";
  pos = "";
  tam = valor.length;
  pos = valor.indexOf(":");
  if(pos!=-1){
    if(pos==0 || pos>2){
      erro++;
    }else{
      if(pos==1){
	hs = "0"+valor.substr(0,1);
	ms = valor.substr(pos+1,2);
      }else if(pos==2){
        hs = valor.substr(0,2);
        ms = valor.substr(pos+1,2);
      }
      if(ms==""){
	ms = "00";
      }
    }
  }else{
    if(tam>=4){
      hs = valor.substr(0,2);
      ms = valor.substr(2,2);
    }else if(tam==3){
      hs = "0"+valor.substr(0,1);
      ms = valor.substr(1,2);
    }else if(tam==2){
      hs = valor;
      ms = "00";
    }else if(tam==1){
      hs = "0"+valor;
      ms = "00";
    }
  }
  if(ms!="" && hs!=""){
    if(hs>24 || hs<0 || ms>60 || ms<0){
      erro++
    }else{
      if(ms==60){
	ms = "59";
      }
      if(hs==24){
	hs = "00";
      }
      hora = hs;
      minu = ms;
    }
  }

  if(erro>0){
    alert("Informe uma hora válida.");
  }
  if(valor!=""){
    eval("document.form1."+campo+".focus();");
    eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
  }
}
</script>
