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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_postmemory( $_POST );
$oDaoMedicos           = new cl_medicos();
$oDaoTfdPedidoRegulado = new cl_tfd_pedidoregulado();
$oPedidoRegulado       = getPedidoRegulado($tf01_i_codigo);

if (isset($incluir) || isset($alterar)) {
  
  /* Setando as variáveis da classe */
  $oDaoTfdPedidoRegulado->erro_status        = null; // tirar o status de erro 
  $oDaoTfdPedidoRegulado->tf34_i_pedidotfd   = $tf01_i_codigo;
  $oDaoTfdPedidoRegulado->tf34_i_especmedico = $tf34_i_especmedico;
  $oDaoTfdPedidoRegulado->tf34_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoTfdPedidoRegulado->tf34_c_horasistema = date('H:i');
  $oDaoTfdPedidoRegulado->tf34_i_login       = db_getsession('DB_id_usuario');
}

if (isset($incluir)) {

  db_inicio_transacao();
  $oDaoTfdPedidoRegulado->incluir(null);
  db_fim_transacao($oDaoTfdPedidoRegulado->erro_status == '0' ? true : false);
} else if (isset($alterar)) {
	
  db_inicio_transacao();
  $oDaoPedidoCadastrado                 = db_utils::fieldsmemory($oPedidoRegulado, 0);
  $oDaoTfdPedidoRegulado->tf34_i_codigo = $oDaoPedidoCadastrado->tf34_i_codigo;
  $oDaoTfdPedidoRegulado->alterar( $oDaoPedidoCadastrado->tf34_i_codigo );
  db_fim_transacao($oDaoTfdPedidoRegulado->erro_status == '0' ? true : false);
}

/*
 * ============================================
 *              FUNÇÃO PARA
 *   VERIFICAR SE O PEDIDO TFD FOI REGULADO 
 *      *Se foi retorna o recordset
 *      *se não retorna false
 * ============================================
 */
function getPedidoRegulado($tf01_i_codigo) {
	
  $oDaoTfdPedidoRegulado = new cl_tfd_pedidoregulado();
  $sWhere                = "tf34_i_pedidotfd = $tf01_i_codigo";
  $sSql                  = $oDaoTfdPedidoRegulado->sql_query2(null, '*', null, $sWhere);
  $rsPedido              = $oDaoTfdPedidoRegulado->sql_record($sSql);

  if ($oDaoTfdPedidoRegulado->numrows == 0) {
    return false;      
  } else {
  	return $rsPedido ;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("webseller.js");
db_app::load("strings.js");
?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<?php
  require_once("forms/db_frmtfd_selecionarregulador.php");
?>
</body>
</html>
<script>

js_tabulacaoforms("form1","tf01_i_cgsund",true,1,"tf01_i_cgsund",true);

function js_fechar() {
  parent.db_iframe_regulador.hide();
}

function js_limpar(){

  document.form1.sd03_i_codigo.value = "";
  document.form1.z01_nome.value      = "";

  for (var iInc = document.form1.tf34_i_especmedico.length - 1; iInc >= 0; iInc--) {
    document.form1.tf34_i_especmedico.options[iInc] = null;
  }

  document.form1.tf34_i_especmedico.selectedIndex = -1;
  document.form1.sd03_i_codigo.focus();
}

</script>
<?php
if (isset($incluir) || isset($alterar)) {

  if ($oDaoTfdPedidoRegulado->erro_status == '0') {
    $oDaoTfdPedidoRegulado->erro(true, false);
  } else {
  	
  	$oDaoTfdPedidoRegulado->erro(true, false);
    db_redireciona("tfd4_tfd_selecionarregulador001.php?tf01_i_codigo=".$oDaoTfdPedidoRegulado->tf34_i_pedidotfd);
  }
}