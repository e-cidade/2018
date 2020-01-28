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
require_once(modification("classes/db_cgmnatureza_classe.php"));

$clcgmnatureza = new cl_cgmnatureza();
$clcgmnatureza->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("c05_numcgm");
$clrotulo->label("c05_tipo");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body >
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Cadastro de Órgão Público</legend>
      <table class="form-container">
        <tr>
          <label for="CGM:">
            <td nowrap title="<?=$Tz01_numcgm?>">
              <?php
              db_ancora("CGM:","js_pesquisa_numcgm(true);",1);
              ?>
            </td>
          </label>
          <td>
            <?php
            $Sz01_numcgm = "CGM";
            db_input('z01_numcgm',10,1,true,'text',1," onchange='js_pesquisa_numcgm(false);'");
            ?>
            <?php
            db_input('z01_nome',60,"",true,'text',3);
            ?>
          </td>
        </tr>
        <tr>
          <td><label for="natureza">Natureza:</label></td>
          <td nowrap>
            <select id="natureza" name="c05_tipo" size="">
              <option  value="" selected>Selecione...</option>
              <option value="2">102.3 ÓRGÃO PÚBLICO DO PODER EXECUTIVO ESTADUAL OU DISTRITO FEDERAL</option>
              <option value="3">103.1 ÓRGÃO PÚBLICO DO PODER EXECUTIVO MUNICIPAL</option>
              <option value="4">120.1 FUNDO PÚBLICO</option>
            </select>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id="btnsalvar"    name="salvar" value="Salvar">
  </form>
</div>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

  var inputCGM = $('z01_numcgm');
  /**
   * Botao que verifica se os campos estão
   * preenchidos e depois executa o AjaxRequest
   */
  $('btnsalvar').observe('click', function(event){

    if(inputCGM.value == "") {
      alert("O campo CGM é de preechimento obrigatório.");
      return false;
    }

    /**
     * Vincular o CGM com Natureza
     *
     */
    new AjaxRequest(
      'prot1_cadgeralmunic.RPC.php',
      {exec : 'vincularNatureza', codigo_cgm : inputCGM.value, natureza : $('natureza').value},
      function(oRetorno,erro){
        alert(oRetorno.mensagem);
      }
    ).setMessage('Salvando a Natureza do CGM...').execute();

    /**
     * Limpa os campos após cadastrar
     *
     */
    $('z01_numcgm').value = "";
    $('z01_nome').value   = "";
    $('natureza').value   = "";

  });

  /**
   *
   * Botao de que pesquisa se o CGM
   * está vinculado a uma Natureza
   */

  function buscaNaturezaCGM () {

    $('natureza').value   = "";
    if ($('z01_numcgm').value === '') {
      return false;
    }

    new AjaxRequest(
      'prot1_cadgeralmunic.RPC.php',
      {exec : 'buscaNaturezaCGM', codigo_cgm : $('z01_numcgm').value},
      function(oRetorno,erro){
        $('natureza').value = oRetorno.tipo;
      }
    ).setMessage('Busacando a Natureza do CGM...').execute();
  }

  function js_pesquisa_numcgm(mostra) {

    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','func_nome','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
    }else{
      if(document.form1.z01_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','func_nome','func_cgm.php?lNovoDetalhe=1&pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
      }else{
        document.form1.z01_nome.value = '';
      }
    }
    buscaNaturezaCGM();
  }

  function js_mostracgm(chave,erro){

    document.form1.z01_nome.value = chave;
    if(erro==true){
      document.form1.z01_nome.value = '';
      document.form1.z01_numcgm.focus();
    }
  }

  function js_mostracgm1(chave1,chave2){

    document.form1.z01_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    buscaNaturezaCGM();
    func_nome.hide();
  }

</script>
</body>
</html>
