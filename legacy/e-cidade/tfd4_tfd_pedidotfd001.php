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

//ABA PEDIDO
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");

db_postmemory($_POST);

$oConfigSaude      = new SaudeConfiguracao();
$lObrigarCNS       = $oConfigSaude->obrigarCns();

$oDaoCgsUnd        = new cl_cgs_und();
$oDaoUnidades      = new cl_unidades();
$oDaoTfdParametros = new cl_tfd_parametros();
$db_opcao          = 1;

$sSqlUnid          = $oDaoUnidades->sql_query_file(db_getsession('DB_coddepto'));
$oDaoUnidades->sql_record($sSqlUnid);

if ($oDaoUnidades->numrows == 0) {
  die('<center><br><br><b><big>Departamento não está cadastrado como UPS no módulo Ambulatorial!</big></b></center>');
}

$rsParametros = $oDaoTfdParametros->sql_record($oDaoTfdParametros->sql_query_file());

if ($oDaoTfdParametros->numrows > 0) {
  $oParametros = db_utils::fieldsmemory($rsParametros, 0);
}
$z01_d_nasc_dia  = isset($z01_d_nasc_dia) ? $z01_d_nasc_dia : "";
$z01_d_nasc_mes  = isset($z01_d_nasc_mes) ? $z01_d_nasc_mes : "";
$z01_d_nasc_ano  = isset($z01_d_nasc_ano) ? $z01_d_nasc_ano : "";
$z01_i_cgsund2   = '';
?>
<html>
<head>
<title>DBSeller Informática Ltda</title>
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load(
    "scripts.js, 
    estilos.css, 
    prototype.js, 
    datagrid.widget.js, 
    strings.js, 
    webseller.js, 
    grid.style.css,
    widgets/datagrid/plugins/DBHint.plugin.js"
  );
?>
</head>
<body class="body-default">

  <?php require_once modification("forms/db_frmtfd_pedidotfdini.php"); ?>
  <script>
    /**
     * Foco no botão inicial
     */
    <?php
    
    switch ($oParametros->tf11_i_campofoco) {
    
      case 1:
        $sCampoFoco = 'tf01_i_cgsund';
        break;
      case 2:
        $sCampoFoco = 's115_c_cartaosus2';
        break;
      case 3:
        $sCampoFoco = 'tf30_i_encaminhamento';
        break;
      case 4:
        $sCampoFoco = 'tf29_i_prontuario';
        break;
       default:
         $sCampoFoco = 'tf01_i_cgsund';
    }
    ?>
    js_tabulacaoforms('form1', '<?=$sCampoFoco?>', true, 1, '<?=$sCampoFoco?>', true);
  </script>
</body>
</html>
