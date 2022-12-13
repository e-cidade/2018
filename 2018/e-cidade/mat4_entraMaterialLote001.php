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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));

$clempempenho = new cl_empempenho;
$clcgm    = new cl_cgm;

$clrotulo = new rotulocampo;
$clcgm->rotulo->label();
$clempempenho->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("m51_codordem");
db_postmemory($_POST);
$oGet = db_utils::postMemory($_GET);

?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script>

    function js_limpa(){
      location.href='mat4_entraMaterialLote001.php';
    }
  </script>

  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="container" onLoad="document.form1.m51_codordem.focus();">
  <form name="form1" method="post" target="" onsubmit=" return js_valida();" action="mat4_entraMaterialLote002.php" >
    <div class="container">
      <fieldset><legend><b>Entrada da Ordem de Compra</b></legend>
        <table border='0'>
          <tr>
            <td  align="left" nowrap title="<?=$Tm51_codordem?>"><b><?db_ancora('Código da Ordem de Compra:',"js_pesquisa_matordem(true);",1);?></b></td>
            <td align="left" nowrap>
              <? db_input("m51_codordem",6,$Im51_codordem,true,"text",4,"onchange='js_pesquisa_matordem(false);'");
              ?></td>
          </tr>
        </table>
      </fieldset>
      <input name="processar" type="submit"  value="Processar"  >
      <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar">
      <?
      db_input("m51_depto",100,0,true,"hidden",3);
      ?>
    </div>
  </form>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>


  var oGet = js_urlToObject();
  console.log(oGet.lAbrirPesquisa);
  if (oGet.lAbrirPesquisa != undefined) {
    js_pesquisa_matordem(true);
  }

  function js_pesquisa_matordem(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordement.php?lExibeAutomatica=false&funcao_js=parent.js_mostramatordem1|m51_codordem|m51_depto','Pesquisa',true);
    }else{
      if(document.form1.m51_codordem.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordement.php?lExibeAutomatica=false&pesquisa_chave='+document.form1.m51_codordem.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
      }else{
        document.form1.m51_codordem.value = '';
      }
    }
  }
  function js_mostramatordem(chave,erro){
    document.form1.m51_codordem.value = chave;
    if(erro==true){
      document.form1.m51_codordem.value = '';
      document.form1.m51_codordem.focus();
    }
  }
  function js_mostramatordem1(chave1,chave2){
    document.form1.m51_codordem.value = chave1;
    document.form1.m51_depto.value = chave2;
    db_iframe_matordem.hide();
  }

  /**
   * Valida formulario
   *
   * @return {Boolean}
   */
  function js_valida() {

    var iOrdem = document.getElementById('m51_codordem').value;

    if (empty(iOrdem)) {

      alert('Selecione uma ordem de compra.');
      return false;
    }

    /**
     * Valida ordem, caso encontre da envia formulario
     */
    js_validarOrdem(iOrdem, function(sErro) {

      if (sErro == null) {
        return document.form1.submit();
      }
      document.getElementById('m51_codordem').value = '';
    });

    return false;
  }

  /**
   * Valida se ordem de compra existe
   * - caso nao encontra passa mensagem de erro para callback
   *
   * @param {Integer} iOrdem
   * @param {Function} callback
   * @retun {Void}
   */
  function js_validarOrdem(iOrdem, callback) {

    /**
     * Parametros invalidos
     */
    if (empty(iOrdem) || typeof callback != 'function') {
      return;
    }

    var sPrograma = 'func_matordement.php?lExibeAutomatica=false&pesquisa_chave='+iOrdem+'&funcao_js=parent.js_validarOrdem.callback';
    js_OpenJanelaIframe('top.corpo', 'db_iframe_matordem', sPrograma,'Pesquisa', false);
    js_validarOrdem.callback = function(mDescricao, lErro) {

      if (lErro) {
        return callback(mDescricao);
      }

      callback(null);
    }
  }
</script>
</body>
</html>
