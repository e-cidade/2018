<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("classes/db_tfd_ajudacustopedido_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$oIframeAE = new cl_iframe_alterar_excluir();
$oDaotfd_ajudacustopedido = db_utils::getdao('tfd_ajudacustopedido');
$oDaotfd_beneficiadosajudacusto = db_utils::getdao('tfd_beneficiadosajudacusto');
$oDaotfd_agendamentoprestadora = db_utils::getdao('tfd_agendamentoprestadora');

$db_opcao = 1;
$db_botao = true;
$lBotaoRecibo = false;

// Bloco que verifica se já foi indicada uma prestadora
$sCampos = ' z01_nome, tf10_i_prestadora, tf25_i_destino ';
$sSql = $oDaotfd_agendamentoprestadora->sql_query_destino(null, $sCampos, null,
                                                          " tf16_i_pedidotfd = $tf14_i_pedidotfd");

$rs = $oDaotfd_agendamentoprestadora->sql_record($sSql);
if($oDaotfd_agendamentoprestadora->numrows <= 0) { // não pode lançar ajudas de custo

  echo "<script>alert('Antes de lançar ajuda de custo você deve agendar com a prestadora.');";
  echo "parent.db_iframe_ajuda.hide();</script>";
  exit;

}



if(isset($opcao)) {
  
  if($opcao == 'alterar') {
    $db_opcao = 2;
  } else {
    $db_opcao = 3;
  }

} else {
 
  $sSql = $oDaotfd_ajudacustopedido->sql_query2(null, ' tf14_i_codigo, tf14_i_cgsretirou, tf14_d_datarecebimento, '.
                                                'cgs_und.z01_v_nome as z01_v_nome2', 
                                                null, ' tf14_i_pedidotfd = '.$tf14_i_pedidotfd);
  $rs = $oDaotfd_ajudacustopedido->sql_record($sSql);
  if($oDaotfd_ajudacustopedido->numrows > 0) {

    db_fieldsmemory($rs, 0);
    $lBotaoRecibo = true;

  }

}

if(isset($incluir)) {

  $sSql = $oDaotfd_ajudacustopedido->sql_query2(null, 'tf14_i_codigo', null, ' tf14_i_pedidotfd = '.$tf14_i_pedidotfd);
  $rs = $oDaotfd_ajudacustopedido->sql_record($sSql);

  db_inicio_transacao();
  if($oDaotfd_ajudacustopedido->numrows == 0 && (!isset($tf14_i_codigo) || empty($tf14_i_codigo))) {

    $oDaotfd_ajudacustopedido->tf14_c_horarecebimento = date('H:i', db_getsession('DB_datausu')); 
    $oDaotfd_ajudacustopedido->tf14_i_login = db_getsession('DB_id_usuario');
    $oDaotfd_ajudacustopedido->incluir(null);
    if($oDaotfd_ajudacustopedido->erro_status != 0) {
      $tf14_i_codigo = $oDaotfd_ajudacustopedido->tf14_i_codigo;
    }

  } else {

    if($oDaotfd_ajudacustopedido->numrows > 0) {

      $oDados = db_utils::fieldsmemory($rs, 0);
      $tf14_i_codigo = $oDados->tf14_i_codigo;

    }

  }

  if($oDaotfd_ajudacustopedido->erro_status != '0') {

    $oDaotfd_beneficiadosajudacusto->tf15_i_ajudacustopedido = $tf14_i_codigo;
    $oDaotfd_beneficiadosajudacusto->tf15_d_data = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaotfd_beneficiadosajudacusto->incluir(null);
    if($oDaotfd_beneficiadosajudacusto->erro_status == '0') {

      $oDaotfd_ajudacustopedido->erro_status = '0';
      $oDaotfd_ajudacustopedido->erro_msg = $oDaotfd_beneficiadosajudacusto->erro_msg;

    }

  }
  db_fim_transacao($oDaotfd_ajudacustopedido->erro_status == '0' ? true : false);

}

if(isset($alterar)) {

  $db_opcao = 2;
  $opcao = 'alterar';
  db_inicio_transacao();
  $oDaotfd_beneficiadosajudacusto->alterar($tf15_i_codigo);
  if($oDaotfd_beneficiadosajudacusto->erro_status == '0') {

      $oDaotfd_ajudacustopedido->erro_status = '0';
      $oDaotfd_ajudacustopedido->erro_msg = $oDaotfd_beneficiadosajudacusto->erro_msg;

  }
  db_fim_transacao($oDaotfd_ajudacustopedido->erro_status == '0' ? true : false);

}
if(isset($excluir)) {

  $db_opcao = 3;
  $opcao = 'excluir';
  db_inicio_transacao();
  $oDaotfd_beneficiadosajudacusto->excluir($tf15_i_codigo);
  if($oDaotfd_beneficiadosajudacusto->erro_status == '0') {

      $oDaotfd_ajudacustopedido->erro_status = '0';
      $oDaotfd_ajudacustopedido->erro_msg = $oDaotfd_beneficiadosajudacusto->erro_msg;

  }
  db_fim_transacao($oDaotfd_ajudacustopedido->erro_status == '0' ? true : false);

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <fieldset style='width: 80%;'> <legend><b>Ajuda de Custo</b></legend>
	      <?
	      require_once("forms/db_frmtfd_ajudacustopedido.php");
	      ?>
      </fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","tf14_i_cgsretirou",true,1,"tf14_i_cgsretirou",true);
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)) {
  if($oDaotfd_ajudacustopedido->erro_status == "0") {

    $oDaotfd_ajudacustopedido->erro(true,false);
    $db_botao = true;

  } else {

    $oDaotfd_ajudacustopedido->erro(true, false);
    echo "<script>alert('Operação realizada com suscesso.');</script>";
    db_redireciona('tfd4_tfd_ajudacustopedido001.php?tf14_i_pedidotfd='.
                   $tf14_i_pedidotfd.'&tf01_i_cgsund=\''.
                   '+document.getElementById(\'tf01_i_cgsund\').value+\'&z01_v_nome='.$z01_v_nome);


  }

}
?>