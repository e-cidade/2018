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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");

db_postmemory($_POST);
db_postmemory($_GET);

$oDaoTfdAgendaSaida           = new cl_tfd_agendasaida();
$oDaoTfdAgendamentoPrestadora = new cl_tfd_agendamentoprestadora();
$oDaoTfdPedidoRegulado        = new cl_tfd_pedidoregulado();
$oDaoTfdParametros            = new cl_tfd_parametros();
$oDaoCgsUnd                   = new cl_cgs_und();
$oDaoTfdPedidoTfd             = new cl_tfd_pedidotfd();
$oDaoTfdVeiculoDestino        = new cl_tfd_veiculodestino();
$oDaoTfdPassageiroVeiculo     = new cl_tfd_passageiroveiculo();

$db_opcao                     = 1;
$db_botao                     = true;
$db_opcaoNaoMudar             = 1;

$db_indicarVeiculo            = db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8437) == 'true' ? 1 : 2;

$sListaCgs                    = '';


/*
 * ===================================================
 *    VERIFICA SE JA FOI INDICADA UMA PRESTADORA
 * ===================================================
 */
$sCampos       = ' z01_nome, tf10_i_prestadora, tf10_i_centralagend, tf25_i_destino, tf03_c_descr,';
$sCampos      .= ' tf16_d_dataagendamento, tf16_c_horaagendamento ';

$sCampos = " 1 ";
$sSql          = $oDaoTfdAgendamentoPrestadora->sql_query_destino(null, $sCampos, null,
                                                                  " tf16_i_pedidotfd = $tf17_i_pedidotfd"
                                                                 );

$rsPrestadora  = $oDaoTfdAgendamentoPrestadora->sql_record($sSql);
if ($oDaoTfdAgendamentoPrestadora->numrows > 0) {

  db_fieldsmemory($rsPrestadora, 0);

} else {

  echo "<script>alert('Antes de agendar a saída você deve agendar com a prestadora.');";
  echo "parent.db_iframe_saida.hide();</script>";
  exit;

}

/*
 * ==================================================
 *     VERIFICA SE O PEDIDO JA FOI REGULADO
 * ==================================================
 */
$sSql = $oDaoTfdPedidoRegulado->sql_query_file(null, 'tf34_i_codigo', null,
                                               " tf34_i_pedidotfd = $tf17_i_pedidotfd"
                                              );
$oDaoTfdPedidoRegulado->sql_record($sSql);
if ($oDaoTfdPedidoRegulado->numrows == 0) {

  echo "<script>alert('Para você agendar a saída, o pedido deve ser regulado.');";
  echo "parent.db_iframe_saida.hide();</script>";
  exit;

}

/*
 * ==================================================
 *   BUSCA PARÂMETROS DE GERENCIAMENTO DE CAPACIDADE
 * ==================================================
 */
$sSql           = $oDaoTfdParametros->sql_query(null, 'tf11_i_utilizagradehorario');
$rsParametros   = $oDaoTfdParametros->sql_record($sSql);
if ($oDaoTfdParametros->numrows > 0) {

  db_fieldsmemory($rsParametros, 0);

}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?

      $aAssets = array(
        "grid.style.css", 
        "scripts.js", 
        "prototype.js", 
        "estilos.css",
        "strings.js", 
        "datagrid.widget.js", 
        "DBInputHora.widget.js",
      );
      
      db_app::load(implode(', ', $aAssets));
    ?>
     </head>
  <body class="body-default">

    <?php
     //Esse require é alterado pelo plugin SMSTFD
     require_once(modification("forms/db_frm_agendasaida.php"));
    ?>

  </body>
</html>
 <script>
// js_tabulacaoforms("form1", "tf17_i_pedidotfd", true, 1, "tf17_i_pedidotfd", true);
 </script>
<?php

if(isset($incluir) || isset($alterar) || isset($excluir)) {

  if($oDaoTfdAgendaSaida->erro_status == '0') {

    $oDaoTfdAgendaSaida->erro(true, false);
    db_redireciona('tfd4_tfd_agendasaida001.php?tf17_i_pedidotfd='.
                   $tf17_i_pedidotfd.'&tf01_i_cgsund=\''.
                   '+document.getElementById(\'tf01_i_cgsund\').value+\'&z01_v_nome='.$z01_v_nome
                  );

  } else {

    $oDaoTfdAgendaSaida->erro(true, false);
    db_redireciona('tfd4_tfd_agendasaida001.php?tf17_i_pedidotfd='.
                   $tf17_i_pedidotfd.'&tf01_i_cgsund=\''.
                   '+document.getElementById(\'tf01_i_cgsund\').value+\'&z01_v_nome='.$z01_v_nome
                  );


  }
}