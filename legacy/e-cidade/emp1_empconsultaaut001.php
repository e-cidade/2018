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
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_pcmater_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_empautoriza_classe.php"));

$clempautoriza = new cl_empautoriza;
$clorcdotacao = new cl_orcdotacao;
$clpcmater = new cl_pcmater;
$clcgm = new cl_cgm;

$clrotulo = new rotulocampo;
$clrotulo->label("o40_descr");
$clrotulo->label("e60_emiss");
$clpcmater->rotulo->label();
$clcgm->rotulo->label();

$clempautoriza->rotulo->label();
$clorcdotacao->rotulo->label();
db_postmemory($_POST);

?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

  <script>
    function js_abre() {
      obj = document.form1;
      if((obj.e60_emiss1_dia.value != '')
        && (obj.e60_emiss2_dia.value != '')
        && (obj.e60_emiss1_mes.value != '')
        && (obj.e60_emiss2_mes.value != '')
        && (obj.e60_emiss1_ano.value != '')
        && (obj.e60_emiss1_ano.value != '')) {
        dt1 = obj.e60_emiss1_ano.value + '-' + obj.e60_emiss1_mes.value + '-' + obj.e60_emiss1_dia.value;
        dt2 = obj.e60_emiss2_ano.value + '-' + obj.e60_emiss2_mes.value + '-' + obj.e60_emiss2_dia.value;
      } else {
        dt1 = '';
        dt2 = '';
      }

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_empconsulta003',
        'emp1_empconsulta003.php?e54_autori=' + document.form1.e54_autori.value +
        '&o58_coddot=' + document.form1.o58_coddot.value +
        '&pc01_codmater=' + document.form1.pc01_codmater.value +
        '&z01_numcgm=' + document.form1.z01_numcgm.value +
        '&dt1=' + dt1 +
        '&dt2=' + dt2 +
        '&funcao_js=parent.js_consulta002|e54_autori',
        'Pesquisa',
        true
      );
    }

    function js_consulta002(chave1) {
      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_empempenhoaut001',
        'func_empempenhoaut001.php?e54_autori=' + chave1,
        'Pesquisa',
        true
      );
    }

    function js_limpa() {
      location.href = 'emp1_empconsultaaut001.php';
    }

  </script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
  <form name="form1" method="post">

    <fieldset>
      <legend>Filtros</legend>

      <table class="form-container">

        <tr>
          <td nowrap>
            <label for="e54_autori">
                <?php
                db_ancora($Le54_autori, "js_pesquisa_aut(true);", 1);
                ?>
            </label>
          </td>
          <td nowrap>
              <?php
              db_input("e54_autori", 6, $Ie54_autori, true, "text", 4, "onclick='js_pesquisa_aut(false);'");
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?= $To58_coddot ?>">
            <label for="o58_coddot">
                <?php
                db_ancora($Lo58_coddot, "js_pesquisa_dotacao(true);", 1);
                ?>
            </label>
          </td>
          <td nowrap>
              <?php
              db_input("o58_coddot", 6, $Io58_coddot, true, "text", 4, "onchange='js_pesquisa_dotacao(false);'");
              db_input("o40_descr", 40, "", true, "text", 3);
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?= $Tpc01_codmater ?>">
            <label for="pc01_codmater">
                <?php
                db_ancora($Lpc01_codmater, "js_pesquisa_pcmater(true);", 1);
                ?>
            </label>
          </td>
          <td nowrap>
              <?php
              db_input("pc01_codmater", 6, $Ipc01_codmater, true, "text", 4, "onchange='js_pesquisa_pcmater(false);'");
              db_input("pc01_descrmater", 40, "", true, "text", 3);
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?= $Tz01_numcgm ?>">
            <label for="z01_numcgm">
                <?php
                db_ancora($Lz01_nome, "js_pesquisa_cgm(true);", 1);
                ?>
            </label>
          </td>
          <td nowrap>
              <?php
              db_input("z01_numcgm", 6, $Iz01_numcgm, true, "text", 4, "onchange='js_pesquisa_cgm(false);'");
              db_input("z01_nome2", 40, "", true, "text", 3);
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?= $Te60_emiss ?>">
            <label for="e60_emiss1">
                <?php
                db_ancora($Le60_emiss, "", 1);
                ?>
            </label>
          </td>
          <td nowrap>
              <?php
              $e60_emiss_dia = !empty($e60_emiss_dia) ? $e60_emiss_dia : "";
              $e60_emiss_mes = !empty($e60_emiss_mes) ? $e60_emiss_mes : "";
              $e60_emiss_ano = !empty($e60_emiss_ano) ? $e60_emiss_ano : "";

              db_inputdata('e60_emiss1', $e60_emiss_dia, $e60_emiss_mes, $e60_emiss_ano, true, 'text', 1, "");
              echo " a ";
              db_inputdata('e60_emiss2', $e60_emiss_dia, $e60_emiss_mes, $e60_emiss_ano, true, 'text', 1, "");
              ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <input name="pesquisa" type="button" onclick='js_abre();' value="Pesquisa">
    <input name="limpa" type="button" onclick='js_limpa();' value="Limpar campos">
  </form>

</div>
<?php
db_menu();
?>
<script>
  //--------------------------------
  function js_pesquisa_cgm(mostra) {

    var sTitulo = 'Pesquisa CGM';

    if(mostra == true) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'func_nome',
        'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
        sTitulo,
        mostra
      );
    } else {
      if(document.form1.z01_numcgm.value != '') {

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'func_nome',
          'func_nome.php?pesquisa_chave=' + document.form1.z01_numcgm.value + '&funcao_js=parent.js_mostracgm',
          sTitulo,
          mostra
        );
      } else {
        document.form1.z01_nome2.value = '';
      }
    }
  }

  function js_mostracgm(erro, chave) {

    document.form1.z01_nome2.value = chave;

    if(erro == true) {
      document.form1.z01_nome2.value = '';
      document.form1.z01_numcgm.focus();
    }
  }

  function js_mostracgm1(chave1, chave2) {

    document.form1.z01_numcgm.value = chave1;
    document.form1.z01_nome2.value = chave2;
    func_nome.hide();
  }

  //--------------------------------
  function js_pesquisa_pcmater(mostra) {

    var sTitulo = 'Pesquisa Material';

    if(mostra == true) {
      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_pcmater',
        'func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater',
        sTitulo,
        mostra
      );
    } else {
      if(document.form1.pc01_codmater.value != '') {
        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_pcmater',
          'func_pcmater.php?pesquisa_chave=' + document.form1.pc01_codmater.value + '&funcao_js=parent.js_mostrapcmater',
          sTitulo,
          mostra
        );
      } else {
        document.form1.pc01_descrmater.value = '';
      }
    }
  }

  function js_mostrapcmater(chave, erro) {
    document.form1.pc01_descrmater.value = chave;

    if(erro == true) {
      document.form1.pc01_codmater.focus();
      document.form1.pc01_descrmater.value = '';
    }
  }

  function js_mostrapcmater1(chave1, chave2) {
    document.form1.pc01_codmater.value = chave1;
    document.form1.pc01_descrmater.value = chave2;
    db_iframe_pcmater.hide();
  }

  //--------------------------------
  function js_pesquisa_dotacao(mostra) {
    var sTitulo = 'Pesquisa Reduzido';

    if(mostra == true) {
      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_orcdotacao',
        'func_orcdotacao.php?funcao_js=parent.js_mostradotacao1|o58_coddot|o56_descr',
        sTitulo,
        mostra
      );
    } else {
      if(document.form1.o58_coddot.value != '') {
        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_orcdotacao',
          'func_orcdotacao.php?pesquisa_chave=' + document.form1.o58_coddot.value + '&funcao_js=parent.js_mostradotacao',
          sTitulo,
          mostra
        );
      } else {
        document.form1.o40_descr.value = '';
      }
    }
  }

  function js_mostradotacao(chave, erro) {
    document.form1.o40_descr.value = chave;

    if(erro == true) {
      document.form1.o58_coddot.focus();
      document.form1.o58_coddot.value = '';
    }
  }

  function js_mostradotacao1(chave1, chave2) {
    document.form1.o58_coddot.value = chave1;
    document.form1.o40_descr.value = chave2;
    db_iframe_orcdotacao.hide();
  }

  //--------------------------------
  function js_pesquisa_aut(mostra) {
    var sTitulo = 'Pesquisa Autorização';

    if(mostra == true) {
      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_empautoriza',
        'func_empautoriza.php?funcao_js=parent.js_mostraautori1|e54_autori',
        sTitulo,
        mostra
      );
    } else {
      if(document.form1.e54_autori.value != '') {
        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_empautoriza',
          'func_empautoriza.php?pesquisa_chave=' + document.form1.e54_autori.value + '&funcao_js=parent.js_mostraautori',
          sTitulo,
          mostra
        );
      }
    }
  }

  function js_mostraautori(erro, chave) {
    if(erro == true) {
      document.form1.e54_autori.focus();
    }
  }

  function js_mostraautori1(chave1) {
    document.form1.e54_autori.value = chave1;
    db_iframe_empautoriza.hide();
  }
  //--------------------------------
</script>
</body>
</html>