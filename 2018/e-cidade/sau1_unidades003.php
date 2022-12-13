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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
$clunidades          = new cl_unidades;
$clsau_atendprestund = new cl_sau_atendprestund;
$clsau_gestaoativ    = new cl_sau_gestaoativ;
$clsau_vinculosus    = new cl_sau_vinculosus;
$db_botao            = false;
$db_opcao            = 33;
$db_opcao1           = 3;
if (isset($excluir)) {

  $db_opcao = 3;
  db_inicio_transacao();
  $clsau_atendprestund->excluir(""," sd48_i_unidade = $sd02_i_codigo");
  $clsau_gestaoativ->excluir(""," sd47_i_unidade = $sd02_i_codigo");
  $clsau_vinculosus->excluir(""," sd50_i_unidade = $sd02_i_codigo");
  $clunidades->excluir($sd02_i_codigo);
  db_fim_transacao();
} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $sSql   = $clunidades->sql_query($chavepesquisa, "db_depart.*, unidades.*, cgm.*, sau_distritosanitario.*,".
                                  " diretorcgm.z01_nome as diretor, sd42_v_descricao"
                                 );
  $result = $clunidades->sql_record($sSql);
  db_fieldsmemory($result,0);
$db_botao = true;
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body >

  <?php require_once (modification("forms/db_frmunidades.php")); ?>

</body>
</html>
<?php
if(isset($chavepesquisa)){
  ?><script>iframe_dados.location.href = "sau1_unidades004.php?chavepesquisa=<?=$sd02_i_numcgm?>";</script><?
}
if (isset($excluir)) {

  if ($clunidades->erro_status=="0") {
    $clunidades->erro(true,false);
  } else {
    $clunidades->erro(true,true);
  }
}
if ($db_opcao==33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>