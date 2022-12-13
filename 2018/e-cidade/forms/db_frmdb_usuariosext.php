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
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$cldb_usuarios->rotulo->label();
if($db_opcao==1){
  $db_action="con1_db_usuariosext001.php";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="con1_db_usuariosext002.php";
}
?>
<div class="container">
  <form name="form1" method="post" action="<?=$db_action?>">
    <fieldset>
      <legend>Cadastro de usuário externo</legend>

      <table class="form-container">
        <tr>
          <td nowrap title="<?=@$Tid_usuario?>">
            <label for="id_usuario"><?=@$Lid_usuario?></label>
          </td>
          <td>
            <?php db_input('id_usuario',10,$Iid_usuario,true,'text',3,""); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="z01_numcgm">
              <?php db_ancora("Login",' js_cgmlogin(true); ',1); ?>
            </label>
          </td>
          <td>
            <?php
              db_input('z01_numcgm',8,$Iz01_numcgm,true,'text',1,"onchange='js_cgmlogin(false)'");
              db_input('z01_nome',40,0,true,'text',3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tlogin?>">
            <label for="login"><?=@$Llogin?></label>
          </td>
          <td>
            <?php db_input('login',20,$Ilogin,true,'text',3,""); ?>
          </td>
        </tr>
        <tr>
          <td><label for="administrador">Administrador:</label></td>
          <td>
            <?php
              if (!isset($administrador) || $administrador == "") {
                $administrador = 0;
              }

              $ativo = array("1" => "Sim", "0" => "Não");
              db_select("administrador", $ativo, true, $db_opcao, 'onchange="return js_validaAdministrador(); "');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tsenha?>">
            <label for="senha"><?=@$Lsenha?></label>
          </td>
          <td style="font-weight:bold;">
            <?php db_input('senha',20,$Isenha,true,'password',$db_opcao, " onkeyup='return js_forcaDaSenha();'"); ?>
            Força da senha: <span id="forcaSenha"></span>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tsenha?>">
            <label for="verificasenha"><b>Verifica senha:</b></label>
          </td>
          <td>
            <?php db_input('verificasenha',20,$Isenha,true,'password',$db_opcao,""); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tusuarioativo?>">
             <label for="usuarioativo"><b>Situação: </b></label>
          </td>
          <td>
            <?php
              if (!isset($usuarioativo) || $usuarioativo == "") {
                $usuarioativo = 1;
              }

              $aOpcoes = array(
                  "0" => "Inativo",
                  "1" => "Ativo",
                  "3" => "Aguardando Ativação"
                );

              if (!($db_opcao == 2 || $db_opcao == 22)) {
                unset($aOpcoes[3]);
              }

              db_select("usuarioativo", $aOpcoes, true, $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Temail?>">
             <label for="email"><?=@$Lemail?></label>
          </td>
          <td>
            <?php db_input('email',50,$Iemail,true,'text',$db_opcao,""); ?>
          </td>
        </tr>
        <tr>
          <td nowrap>
            <label for="senharandomica"><strong>Criar senha aleatória:</strong></label>
          </td>
          <td nowrap>
            <input name="senharandomica" type="checkbox" value="1" onChange="(this.form.email.value==''?alert('campo e-mail deve ser preenchido'):'');(this.form.email.value==''?this.checked=false:'');js_apagasenha();">
            <input name="enviaemail" type="hidden" value="" size="50" maxlength="50"></td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==1||$db_opcao==2||$db_opcao==22?"Onclick='return js_submit();'":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</div>
<script type="text/javascript">
function js_apagasenha(){
  document.form1.senha.value = '';
  document.form1.verificasenha.value = '';
  if(document.form1.senharandomica.checked == true){
    document.form1.senha.disabled = true;
    document.form1.verificasenha.disabled = true;
  }else{
    document.form1.senha.disabled = false;
    document.form1.verificasenha.disabled = false;
  }
}
function js_submit(){
	if (document.form1.senha.value!=document.form1.verificasenha.value &&
      document.form1.senharandomica.checked == false){
		alert("Senha não confere!!");
		document.form1.verificasenha.value="";
		document.form1.verificasenha.focus();
		return false;
	}else{
      if(document.form1.senharandomica.checked == true){
         	document.form1.enviaemail.value = 'sim';
      }

  		return true;
	}
}
function js_cgmlogin(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?campos=cgm.z01_numcgm,z01_nome,z01_email,trim(z01_cgccpf) as z01_cgccpf&funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  document.form1.login.value      = chave1;
  db_iframe2.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
    document.form1.login.value = "";
  }else{
    document.form1.login.value = document.form1.z01_numcgm.value;
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuariosalt.php?usuext=1&funcao_js=parent.js_preenchepesquisa|id_usuario','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_usuarios.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

/**
 * Verifica força de senha do campo senha
 * @return void
 */
function js_forcaDaSenha() {

  var oCampoForca       = document.getElementById("forcaSenha");
  var oCampoSenha       = document.getElementById("senha");

  var oForte            = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
  var oMedio            = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
  var oPoucosCaracteres = new RegExp("(?=.{6,}).*", "g");

  if (false == oPoucosCaracteres.test( oCampoSenha.value ) ) {

    oCampoForca.innerHTML = "<span style='color:red;'>Poucos Caracteres</span>";
  } else if ( oForte.test( oCampoSenha.value ) ) {

    oCampoForca.innerHTML = "<span style='color:blue;'>Forte</span>";
  } else if ( oMedio.test( oCampoSenha.value ) ) {

    oCampoForca.innerHTML = "<span style='color:orange;'>Média</span>";
  } else {

    oCampoForca.innerHTML = "<span style='color:red;'>Fraca</span>";
  }
}
</script>