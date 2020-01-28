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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("model/compilacaoRegistroPreco.model.php"));
require_once(modification("std/DBDate.php"));

$oGet             = db_utils::postMemory($_GET);
$oPost            = db_utils::postMemory($_POST);

$data_inicial     = "";
$data_inicial_dia = "";
$data_inicial_mes = "";
$data_inicial_ano = "";
$data_final       = "";
$data_final_dia   = "";
$data_final_mes   = "";
$data_final_ano   = "";

if ( isset($oGet->pc54_solicita) ) {

  $oCompilacao      = new compilacaoRegistroPreco( $oGet->pc54_solicita );
  $oDataInicial     = new DBDate( $oCompilacao->getDataInicio() );
  $oDataFinal       = new DBDate( $oCompilacao->getDataTermino() );

  $data_inicial     = $oDataInicial->getDate(DBDate::DATA_PTBR);
  $data_inicial_dia = $oDataInicial->getDia();
  $data_inicial_mes = $oDataInicial->getMes();
  $data_inicial_ano = $oDataInicial->getAno();
  $data_final       = $oDataFinal->getDate(DBDate::DATA_PTBR);
  $data_final_dia   = $oDataFinal->getDia();
  $data_final_mes   = $oDataFinal->getMes();
  $data_final_ano   = $oDataFinal->getAno();
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
    ?>
  </head>
  <body bgcolor="#cccccc">

    <div class='container'>
      <form name="form1" id="form1" method="post" >

        <fieldset>
          <legend>Alteração da Vigencia de Registro de Preço:</legend>
		      <table class='form-container'>
		        <tr>
		        	<td>
               <b> Compilação Registro de Preço:</b>
              </td>
		        	<td>
		        		<?php
		        			db_input('pc54_solicita',10,'',true,'text',3,"");
		        		?>
		        	</td>
		        </tr>
		        <tr>
		          <td><b>Início Vigência:</b></td>
		          <td>
		            <?
		             db_inputdata('data_inicial', $data_inicial_dia, $data_inicial_mes, $data_inicial_ano, true, 'text', 1);
                ?>
              </td>
		        </tr>
		        <tr>
		          <td><b>Fim da Vigência:</b></td>
              <td>
                <?php
		             db_inputdata('data_final'  , $data_final_dia, $data_final_mes, $data_final_ano, true, 'text', 1);
		            ?>
		          </td>
		        </tr>

		      </table>
        </fieldset>
        <input name="incluir"     type="button"  value="Processsar"  onclick="js_processa();">
        <input name="pesquisar"   type="button"  value="Pesquisar"  onclick="js_pesquisaaberturaprecos();">
      </form>
    </div>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
    <script>

function js_pesquisaaberturaprecos() {


   js_OpenJanelaIframe( 'CurrentWindow.corpo',
                        'db_iframe_registropreco',
                        'func_solicitacompilacao.php?funcao_js=parent.js_preenche|pc10_numero'+
                        '&departamento=true&anuladas=1&comcompilacao=1',
                        'Compilação do Registro de Preço',
                        true );
}

function js_preenche(solicita) {

  window.location.href = 'com4_vigenciaregistropreco001.php?pc54_solicita='+solicita;
  return;
}

function js_processa() {

  if ( $F("pc54_solicita") == "" || $F("data_inicial")=="" || $F("data_final") == "" ) {

    alert("Todos os campos devem ser preenchidas.");
    return true;
  }

  /**
   * Valida data de inicio e termino - true quando data de termino é menor ou 'i' quando datas são iguais
   * @var mixed bool | string
   */
  var mDiferenca = js_diferenca_datas(js_formatar($F('data_inicial'), 'd'), js_formatar($F('data_final'), 'd'), 3);

  if (mDiferenca && mDiferenca != 'i') {

    alert('Data final da vigência menor que a inicial.');
    $('data_final').focus();
    return false;
  }

  document.forms[0].submit();
}


(function(){

  var oGet = js_urlToObject();
  if ( !oGet.pc54_solicita ) {
    js_pesquisaaberturaprecos(true);
  }
})();
    </script>
  </body>
</html>

<?php
try {

  db_inicio_transacao();
  if ( isset($_POST['pc54_solicita'] ) ) {


    $oDataInicio = new DBDate($_POST['data_inicial']);
    $oDataFim    = new DBDate($_POST['data_final']);

    $oCompilacao = new compilacaoRegistroPreco($_POST['pc54_solicita']);
    $oCompilacao->setDataInicio($oDataInicio->getDate());
    $oCompilacao->setDataTermino($oDataFim->getDate());
    $oCompilacao->salvarDadosAbertura();

    db_fim_transacao(false);
    db_redireciona();
    exit();
  }
} catch ( Exception $eErro ) {

  db_fim_transacao(true);
  $sErroMensagem = "Erro ao Alterar Data de Vigência.\\n" . str_replace("\n", '\\n', $eErro->getMessage());
  echo "<script>alert('{$sErroMensagem}')</script>";
}
