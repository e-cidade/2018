
<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_conecta".".php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$db_opcao = 1;

$oDaoCotaPrestadoraExameMensal = new cl_cotaprestadoraexamemensal();
$oDaoPrestadoraVinculos        = new cl_sau_prestadorvinculos();
$oDaoProcedimento              = new cl_sau_procedimento();
$oDaoPrestadorHorarios         = new cl_sau_prestadorhorarios();
$oDaoGrupoExame                = new cl_grupoexame();

$oDaoCotaPrestadoraExameMensal->rotulo->label();
$oDaoPrestadoraVinculos->rotulo->label();
$oDaoProcedimento->rotulo->label();
$oDaoPrestadorHorarios->rotulo->label();
$oDaoGrupoExame->rotulo->label();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/saude/agendamento/PrestadorCotaMensal.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
  <?php
    include("forms/db_frmcotamensal.php");
  ?>
  </body>
  <script>

    var parametrosPesquisa = new Array();
    parametrosPesquisa.push('func_sau_prestadorvinculos.php');
    parametrosPesquisa.push(['sd63_c_procedimento','sd63_c_nome', 's111_i_codigo']);
    parametrosPesquisa.push('lProcedimentosAgendamento=true&chave_s111_i_prestador=' + document.form1.s111_i_prestador.value);

    var oPrestadorCotaMensal = new PrestadorCotaMensal("sau1_prestadorcotamensal.RPC.php", parametrosPesquisa);
    oPrestadorCotaMensal.comportamentoCadastro();
    oPrestadorCotaMensal.montarGrid();
    oPrestadorCotaMensal.carregarGrid();

    function js_pesquisas111_i_exame( mostra ) {

      if( mostra == true ) {

        js_OpenJanelaIframe(
                             '',
                             'db_iframe_sau_prestadorvinculos',
                             'func_sau_prestadorvinculos.php?chave_s111_i_prestador=' + document.form1.s111_i_prestador.value
                                                          +'&funcao_js=parent.js_mostraexame1|sd63_i_codigo|sd63_c_procedimento'
                                                                                           +'|sd63_c_nome|s111_i_codigo'
                                                          +'&lProcedimentosAgendamento',
                             'Pesquisa',
                             true
                           );
      } else {

        if( $('sd63_c_procedimento').value != '' ) {

          js_OpenJanelaIframe(
                               '',
                               'db_iframe_sau_prestadorvinculos',
                               'func_sau_prestadorvinculos.php?chave_s111_i_prestador=' + document.form1.s111_i_prestador.value
                                                            +'&pesquisa_chave=' + $F('sd63_c_procedimento')
                                                            +'&funcao_js=parent.js_mostraexame1'
                                                            +'&lProcedimentosAgendamento',
                               'Pesquisa',
                               false
                             );
        } else {

          $('sd63_i_codigo').value        = '';
          $('sd63_c_nome').value          = '';
          $('sd63_c_procedimento').value  = '';
          $('s112_i_prestadorvinc').value = '';
        }
      }
    }

    function js_mostraexame1() {

      if( arguments[1] !== true && arguments[1] !== false ) {

        $('sd63_i_codigo').value        = arguments[0];
        $('sd63_c_procedimento').value  = arguments[1];
        $('sd63_c_nome').value          = arguments[2];
        $('s112_i_prestadorvinc').value = arguments[3];
      } else if( arguments[1] === false ) {

        $('s112_i_prestadorvinc').value = arguments[0];
        $('sd63_i_codigo').value        = arguments[2];
        $('sd63_c_nome').value          = arguments[3];
      } else if( arguments[1] === true ) {

        $('s112_i_prestadorvinc').value = '';
        $('sd63_i_codigo').value        = '';
        $('sd63_c_procedimento').value  = '';
        $('sd63_c_nome').value          = arguments[0];
      }

      db_iframe_sau_prestadorvinculos.hide();
    }

  </script>
</html>
