<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBseller Servicos de Informatica             
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

if ($db_opcao == 1) {
  $db_action = "con1_db_usuarios004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action = "con1_db_usuarios005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action = "con1_db_usuarios006.php";
}

$where = isset($where) ? $where : null;
?>
<script src="scripts/prototype.js"></script>
<div class="container">

  <form name="form1" method="post" action="<?=$db_action?>">

    <fieldset>
      <legend>
        <?php echo ($db_opcao == 1) ? "Incluir" : "Alterar"; ?>
        &nbsp;Usuário
      </legend>

      <table>
        <tr>
          <td style="vertical-align: top;">
            <img src="imagens/none1.jpeg" id="fotocgm" style="border: 1px inset white" height="120" width="95">
          </td>

          <td class="text-center">

            <table border="0">
              <tr>
                <td nowrap title="<?=@$Tid_usuario?>">
                   <?=@$Lid_usuario?>
                </td>
                <td>
                  <?
                    db_input('id_usuario',10,$Iid_usuario,true,'text',3,"")
                  ?>
                </td>
              </tr>

              <tr>
                <td>
                  <?php
                    db_ancora("CGM:", ' js_cgm(true); ', 1);
                  ?>
                </td>
                <td>
                  <?php
                    db_input('z01_numcgm',8,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
                    db_input('z01_nome',40,0,true,'text',3);
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?=@$Tlogin?>">
                  <?=@$Llogin?>
                </td>
                <td>
                  <?php
                    db_input('login',20,$Ilogin,true,'text',$db_opcao,"")
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?=@$Tadministrador?>">
                  <b>Administrador:</b>
                </td>
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

              <?php if (! ($db_opcao == 2 || $db_opcao == 22)) : ?>
                <tr>
                  <td nowrap>
                    <b>Enviar link de ativação:</b>
                  </td>
                  <td>
                    <?php
                      $aOpcoes = array("1" => "Sim", "0" => "Não");
                      db_select("link_ativacao", $aOpcoes, true, $db_opcao, 'onchange="return js_validaAtivacao(); "');
                    ?>
                  </td>
                </tr>
              <?php endif; ?>

              <tr>
                <td nowrap title="<?=@$Tsenha?>">
                   <?=@$Lsenha?>
                </td>
                <td style="font-weight:bold;">
                  <?
                    db_input('senha',20,$Isenha,true,'password',$db_opcao,"onkeyup='return js_forcaDaSenha();'")
                  ?>
                  Força da senha: <span id="forcaSenha"></span>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?=@$Tsenha?>">
                  <b>Verifica senha:</b>
                </td>
                <td>
                  <?
                    db_input('verificasenha',20,$Isenha,true,'password',$db_opcao,"")
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap>
                  <b>Situação:</b>
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
                <td nowrap title="<?php echo $Tdataexpira; ?>">
                  <?php echo $Ldataexpira; ?>
                </td>
                <td nowrap title="<?php echo $Tdataexpira; ?>" >
                  <?php
                    db_inputdata('dataexpira', $dataexpira_dia, $dataexpira_mes, $dataexpira_ano,true,'text', $db_opcao);
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?=@$Temail?>">
                   <?=@$Lemail?>
                </td>
                <td>
                  <?
                    db_input('email',50,$Iemail,true,'text',$db_opcao,"")
                  ?>
                </td>
              </tr>

              <tr>
                <td height="25" valign="top" nowrap><strong>Institui&ccedil;&atilde;o:</strong></td>
                <td height="25" nowrap> <select name="instit[]" size="5" multiple>
                  <?php

              		  if (!empty($id_usuario)) {
              	        $result = db_query("select c.codigo,c.nomeinst,u.id_instit
                                             from db_config c
                                             left outer join db_userinst u
                                             on u.id_instit = c.codigo
              							   and u.id_usuario = $id_usuario $where
              							   ");
              		  } else {
              		    $result = db_query("select codigo,nomeinst from db_config $where");
              		  }

              		  for ($i = 0;$i < pg_numrows($result);$i++) {
              		  	$sel ="";

                      if (!empty($id_usuario) && pg_result($result,$i,"id_instit")==pg_result($result,$i,"codigo")){
                        $sel = "selected";
                      }

              		  	if ($i == 0 && empty($id_usuario)) {
              		  		$sel = "selected";
              		  	}
              		    echo "<option  ".$sel." value=\"".pg_result($result,$i,"codigo")."\" >".pg_result($result,$i,"nomeinst")."</option>\n";
              		  }
          	      ?>
                  </select>
                </td>
              </tr>

            </table>

          </td>
        </tr>
      </table>
    </fieldset>

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==1?"Onclick='return js_submit();'":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</div>
<script type="text/javascript">

  var sRpc = "con1_db_usuarios.RPC.php";

  function js_validaAtivacao() {

    if (!$('link_ativacao')) {
      return true;
    }
    
    if ($F('link_ativacao') == '1') {

      $('senha').up('tr').hide()
      $('verificasenha').up('tr').hide()
      $('usuarioativo').up('tr').hide()
    } else {

      $('senha').up('tr').show()
      $('verificasenha').up('tr').show()
      $('usuarioativo').up('tr').show()
    }
  }

  function js_validaAdministrador() {

    <?php if ($db_opcao == 2 || $db_opcao == 22): ?>

      if ($F('administrador') == '1') {

        if ($F('usuarioativo') == '3') {
          $('usuarioativo').value = '0';
        }

        $$('#usuarioativo option[value="3"]')[0].hide();
      } else {
        $$('#usuarioativo option[value="3"]')[0].show();
      }

    <?php endif; ?>

    if (!$('link_ativacao')) {
      return true;
    }

    if ($F('administrador') == '1') {

      $('link_ativacao').value = '0';
      $('link_ativacao').up('tr').hide()

      js_validaAtivacao()
    } else {

      $('link_ativacao').up('tr').show()
    }
  }

  function js_submit() {

  	if (document.form1.senha.value != document.form1.verificasenha.value){

  		alert("Senha não confere!!");

  		document.form1.verificasenha.value = "";
  		document.form1.verificasenha.focus();

  		return false;
  	} else {

  		return true;
  	}
  }

  function js_cgm(lMostra) {

    var cgm = document.form1.z01_numcgm.value;

    if (lMostra) {
      js_OpenJanelaIframe( '',
                           'db_iframe2',
                           'func_nome.php?funcao_js=parent.js_mostracgm|0|1',
                           'Pesquisa CGM',
                           true );
    } else {
      js_OpenJanelaIframe( '',
                           'db_iframe2',
                           'func_nome.php?pesquisa_chave=' + cgm + '&funcao_js=parent.js_mostracgm1',
                           'Pesquisa CGM',
                           false );
    }
  }

  function js_mostracgm(sChave1, sChave2) {

    document.form1.z01_numcgm.value = sChave1;
    document.form1.z01_nome.value = sChave2;

    var db_opcao = '<?php echo $db_opcao; ?>';

    if (db_opcao != '2'){
      document.form1.login.value = sChave2.split(' ')[0].toLowerCase();
    }

    db_iframe2.hide();

    js_exibeImagemCGM();
  }

  function js_mostracgm1(lErro, sChave){

    document.form1.z01_nome.value = sChave;

    if (lErro) {

      document.form1.z01_numcgm.focus();
      document.form1.z01_numcgm.value = '';
      document.form1.login.value = "";
    } else {

    	var db_opcao = '<?php echo $db_opcao; ?>';

      if (db_opcao != '2'){
        document.form1.login.value = sChave.split(' ')[0].toLowerCase();
      }
    }

    js_exibeImagemCGM();
  }

  /**
   * Busca e exibe a foto do CGM
   */
  function js_exibeImagemCGM() {

    var iCgm = document.form1.z01_numcgm.value;

    if (iCgm) {

      var oParametro = {
            sExecuta : "fotoCgm",
            iCgm : iCgm
          },
          oDados = {
            method : "post",
            parameters : "json=" + Object.toJSON( oParametro ),
            onComplete : function ( oResponse ) {

              var oRetorno = JSON.parse( oResponse.responseText );

              if (oRetorno.iStatus == 2) {
                alert( oRetorno.sMensagem.urlDecode() ); 
              }

              $('fotocgm').src = "imagens/none1.jpeg";

              if (oRetorno.sFoto) {
                $('fotocgm').src = "data:image/jpg;base64," + oRetorno.sFoto;
              }
            }
          };

      new Ajax.Request( sRpc, oDados );
    }
  }

  function js_pesquisa() {

    js_OpenJanelaIframe( '',
                         'db_iframe_db_usuarios',
                         'func_db_usuariosalt.php?funcao_js=parent.js_preenchepesquisa|id_usuario',
                         'Pesquisa',
                         true,
                         '0' );
  }

  function js_preenchepesquisa(sChave) {

    db_iframe_db_usuarios.hide();

    <?php
      if ($db_opcao != 1) {
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+sChave";
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

  js_exibeImagemCGM();
  js_validaAtivacao();
  js_validaAdministrador();
</script>
