<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
$_SESSION["DB_itemmenu_acessado"] = "0";
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php"); 
require_once('model/configuracao/PreferenciaUsuario.model.php');

$oPost = db_utils::postMemory($_POST);

$iInstituicao = db_getsession('DB_instit', false);
$iAnoUsu = db_getsession('DB_anousu', false);

/**
 * Busca os departamentos
 */
$sSqlDepartamentos  = "select distinct d.coddepto, d.descrdepto,u.db17_ordem ";
$sSqlDepartamentos .= "from db_depusu u ";
$sSqlDepartamentos .= "     inner join db_depart d on u.coddepto = d.coddepto ";
$sSqlDepartamentos .= "where instit       = ".db_getsession("DB_instit");
$sSqlDepartamentos .= "  and u.id_usuario = ".db_getsession("DB_id_usuario");
$sSqlDepartamentos .= "  and (d.limite is null or d.limite >= '" . date("Y-m-d",db_getsession("DB_datausu")) . "') ";
$sSqlDepartamentos .= "order by 1 ";

$rsDepartamentos = db_query($sSqlDepartamentos);

$aDadosDepartamentos = db_utils::getCollectionByRecord($rsDepartamentos);
$aDepartamentos = array();

foreach ($aDadosDepartamentos as $oDadosDepartamento) {
  $aDepartamentos[$oDadosDepartamento->coddepto] = $oDadosDepartamento->coddepto.' - '.$oDadosDepartamento->descrdepto;
}

$departamento  = db_getsession('DB_coddepto', false);


/**
 * Identifica qual foi o último menu acessado
 */
$oDaoDBItensMenu = new cl_db_itensmenu();
$sMenuAnterior   = "Nenhum menu acessado até o momento.";
$sDisabled       = "disabled";
$iMenuAcessado   = db_getsession('DB_itemmenu_acessado', false);
if (!empty($iMenuAcessado)) {

  $sSqlMenu = $oDaoDBItensMenu->sql_query_file(db_getsession("DB_itemmenu_acessado", false), "fc_montamenu($iMenuAcessado), funcao");
  $rsMenu   = db_query($sSqlMenu);
  if ( !$rsMenu ) {
    throw new DBException(" Item de menu não pode ser perquisado. Erro: " . pg_last_error() );
  }

  if ( pg_num_rows($rsMenu) == 0 ) {
    throw new BusinessException( "Nenhum item encontrado." );
  }
  $oDadosMenu = db_utils::fieldsMemory($rsMenu, 0);

  $sFuncaoRetornar = $oDadosMenu->funcao;
  $sMenuAnterior   = "$oDadosMenu->fc_montamenu";
  $sDisabled       = "";
}

/**
 * Configuração do cache dos Menus
 */
$oUsuarioSistema     = new UsuarioSistema( db_getsession("DB_id_usuario") );
$oPreferenciaUsuario = $oUsuarioSistema->getPreferenciasUsuario();

if (isset($oPost->cache_salvar)) {

  try {

    $lHabilitaCache = (property_exists($oPost, 'lHabilitaCacheMenu') && $oPost->lHabilitaCacheMenu);

    $oPreferenciaUsuario->setHabilitaCacheMenu( $lHabilitaCache );
    $oPreferenciaUsuario->salvar();

    $sMensagem = "Configuração do cache salva com sucesso.";
  } catch (Exception $e) {
    $sMensagem = $e->getMessage();
  }
}

/**
 * Limpa o cache dos menus do usuário
 */
if (isset($oPost->limpar_cache)) {

  DBMenu::limpaCache( $oUsuarioSistema->getIdUsuario() );
  $sMensagem = "Arquivos de cache removidos com sucesso.";
}


$sStatusBtnPreMenus = 'ativar';
$sValueBtnPreMenus  = 'Ativar PreMenus';

if ( db_getsession("DB_premenus", false) != null  ) {
  
  $sStatusBtnPreMenus = 'desativar';
  $sValueBtnPreMenus  = 'Desativar PreMenus';
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <?php db_app::load("scripts.js, strings.js, prototype.js, estilos.css"); ?>
  </head>
  <body style="background-color: #ccc;">
    <div class="container">
    <fieldset style="width: 500px; margin: auto;">
      <legend style="font-weight: bold;">Menu de Acesso Rápido</legend>

      <table class="form-container">
        <tr>
          <td>
            <input type="button" class="field-size-max" id="btnRetornaDataSistema" value="Retorna Data do Sistema" />
          </td>
        </tr>
        <tr>
          <td>
            <input type="button" class="field-size-max" id="btnHabilitarTraceLog" value="Trace Log" />
          </td>
        </tr>
        <tr>
          <td>
            <input type="button" class="field-size-max" id="btnMensagensSistema" value="Mensagens Sistema" />
          </td>
        </tr>
        <tr>
          <td>
            <input type="button" class="field-size-max" id="btnDebug" value="Debug.PHP" />
          </td>
        </tr>    
        
        <tr>
          <td>
          
            <input type="button" class="field-size-max" id="btnPreMenus" value="<?=$sValueBtnPreMenus; ?>" status="<?=$sStatusBtnPreMenus;?>" />
          </td>
        </tr>            
            
        
        <tr>
          <td>
           <?php db_select('departamento', $aDepartamentos, true, 1); ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset style="width: 500px; margin: auto;">
      <legend style="font-weight: bold;">Menu Anterior</legend>
      <table class="form-container">
        <tr>
          <td>
           <?php echo $sMenuAnterior; ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php
              $sFuncaoRetornar = isset( $sFuncaoRetornar ) ? $sFuncaoRetornar : "";
            ?>
            <input type="button"
                   class="field-size-max"
                   funcao="<?=$sFuncaoRetornar;?>"
                   id="btnVoltarMenuAnterior"
                   value="Voltar" <?php echo $sDisabled; ?>>
          </td>
        </tr>
      </table>

    </fieldset>

    <fieldset style="width: 500px; margin: auto;">
      <legend style="font-weight: bold;">Cache dos Menus</legend>

        <form name="form1" method="post" style="margin: 0px ">

          <table class="form-container">
            <tr>
              <td colspan="2">
                <input type="checkbox" id="lHabilitaCacheMenu" name="lHabilitaCacheMenu" 
                       style="margin: 0px; padding: 0px; height: 0px;"
                       <?php echo ($oPreferenciaUsuario->getHabilitaCacheMenu() ? 'checked="checked"' : ''); ?>/>
                <label for="lHabilitaCacheMenu">Ativar Cache dos Menus</label>
              </td>
            </tr>
            <tr>
              <td><input type="submit" class="field-size-max" id="cache_salvar" name="cache_salvar" value="Salvar"></td>
              <td><input type="submit" class="field-size-max" id="limpar_cache" name="limpar_cache" value="Limpar Cache"></td>
            </tr>
          </table>
        </form>
    </fieldset>

  </div>

  <?php db_menu( db_getsession("DB_id_usuario"),
                 db_getsession("DB_modulo"),
                 db_getsession("DB_anousu"),
                 db_getsession("DB_instit") ); ?>
  </body>
</html>
<script type="text/javascript">

  $("btnDebug").observe("click", function() {
    js_OpenJanelaIframe("", "iframe_debug", "debug.php", "Debug.php", true);
  });

    
  $("btnRetornaDataSistema").observe("click", function() {

     js_OpenJanelaIframe( "", 
                          "iframe_retornadatasistema", 
                          "con4_trocadata.php?lParametroExibeMenu=false", 
                          "Retorna Data do Sistema", 
                          true );
  });

  $("btnHabilitarTraceLog").observe("click", function() {

     js_OpenJanelaIframe( "", 
                          "iframe_tracelog", 
                          "con1_ativatrace001.php?lParametroExibeMenu=false", 
                          "Habilitar / Desabilitar TraceLog", 
                          true );
  });

  $("btnMensagensSistema").observe("click", function() {

     js_OpenJanelaIframe( "", 
                          "iframe_mensagenssistema", 
                          "con4_mensagens001.php?lIframe=false", 
                          "Mensagens do Sistema", 
                          true );
  });

  $('departamento').onchange = function () {

      var iDepartamento = this.value;
      var sDescricaoDepartamento = this.options[this.selectedIndex].text;
      var sPrograma = "modulos.php?coddepto=" + iDepartamento + "&retorno=true&nomedepto=" + sDescricaoDepartamento;
      var sNomeModulo = '<?php echo db_getsession("DB_nome_modulo"); ?>';

      js_OpenJanelaIframe("", "iframe_troca_departamento", sPrograma, "Mensagens do Sistema", false);
      parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<b>' + sNomeModulo + ' > ' + sDescricaoDepartamento + '</b>';
  }

  $('btnVoltarMenuAnterior').onclick = function() {
    location.href = this.getAttribute('funcao');
  }


  /**
   * funcao para manipular os premenus
   * cria a variavel de sessao que o db_query ira verificar
   */
  $('btnPreMenus').observe( 'click', function () {

    var oParamentro    = {};
    oParamentro.sExec  = 'ManipularPreMenus';
    oParamentro.status = $('btnPreMenus').getAttribute('status');

    var oRequest = {};
    oRequest.method       = 'post',
    oRequest.asynchronous = false,
    oRequest.parameters   = 'json='+Object.toJSON(oParamentro),
    oRequest.onComplete   = function(oAjax) {
    
      var oRetorno = eval( '(' + oAjax.responseText + ')' );
      
      $('btnPreMenus').value = oRetorno.sValue;
      $('btnPreMenus').setAttribute('status', oRetorno.sStatus);
    }
    
    new Ajax.Request( 'con1_ativatrace.RPC.php' , oRequest);
    
  });
  

  <?php if (isset($sMensagem)): ?>
    alert('<?php echo $sMensagem; ?>');
  <?php endif; ?>
</script>
