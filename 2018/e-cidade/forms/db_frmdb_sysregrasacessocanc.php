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
$cldb_sysregrasacessocanc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db46_observ");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<fieldset>
    <legend>Cadastro de Cancelamento de Regra de Acesso</legend>
    <table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb49_idacesso?>">
       <?
       echo @$Ldb49_idacesso;
       ?>
    </td>
    <td>
<?
db_input('db49_idacesso',6,$Idb49_idacesso,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb49_id_usuario?>">
       <?
       echo @$Ldb49_id_usuario;
       ?>
    </td>
    <td>
<?
$db49_id_usuario = db_getsession("DB_id_usuario");
db_input('db49_id_usuario',10,$Idb49_id_usuario,true,'text',3,"")
?>
       <?
$nome = db_getsession('DB_login');
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb49_dtcancela?>">
       <?=@$Ldb49_dtcancela?>
    </td>
    <td>
<?
$db49_dtcancela_dia = date('d',db_getsession("DB_datausu"));
$db49_dtcancela_mes = date('m',db_getsession("DB_datausu"));
$db49_dtcancela_ano = date('Y',db_getsession("DB_datausu"));

db_inputdata('db49_dtcancela',@$db49_dtcancela_dia,@$db49_dtcancela_mes,@$db49_dtcancela_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb49_observ?>">
       <?=@$Ldb49_observ?>
    </td>
    <td>
<?
db_textarea('db49_observ',6,90,$Idb49_observ,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
    <center>
<input name="incluir" type="submit" id="db_opcaoi" value="Incluir" >
<input name="alterar" type="submit" id="db_opcaoa" value="Alterar" >
<input name="excluir" type="submit" id="db_opcaoe" value="Excluir" >
</center>

</form>
<script>
function js_pesquisadb49_idacesso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_sysregrasacesso','func_db_sysregrasacesso.php?funcao_js=parent.js_mostradb_sysregrasacesso1|db46_idacesso|db46_observ','Pesquisa',true);
  }else{
     if(document.form1.db49_idacesso.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_sysregrasacesso','func_db_sysregrasacesso.php?pesquisa_chave='+document.form1.db49_idacesso.value+'&funcao_js=parent.js_mostradb_sysregrasacesso','Pesquisa',false);
     }else{
       document.form1.db46_observ.value = '';
     }
  }
}
function js_mostradb_sysregrasacesso(chave,erro){
  document.form1.db46_observ.value = chave;
  if(erro==true){
    document.form1.db49_idacesso.focus();
    document.form1.db49_idacesso.value = '';
  }
}
function js_mostradb_sysregrasacesso1(chave1,chave2){
  document.form1.db49_idacesso.value = chave1;
  document.form1.db46_observ.value = chave2;
  db_iframe_db_sysregrasacesso.hide();
}
function js_pesquisadb49_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.db49_id_usuario.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.db49_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.db49_id_usuario.focus();
    document.form1.db49_id_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.db49_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_sysregrasacessocanc','func_db_sysregrasacessocanc.php?funcao_js=parent.js_preenchepesquisa|db49_idacesso','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_sysregrasacessocanc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
