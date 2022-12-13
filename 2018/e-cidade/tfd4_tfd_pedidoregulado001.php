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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);

$oDaoDbUsuaCgm         = db_utils::getdao('db_usuacgm');
$oDaoMedicos           = db_utils::getdao('medicos');
$oDaoCgsUnd            = db_utils::getdao('cgs_und');
$oDaoTfdPedidoRegulado = db_utils::getdao('tfd_pedidoregulado');
$db_opcao              = 1;

if (isset($confirmar)) {
  
  db_inicio_transacao();

  if (!empty($sPedidosSelecionados)) {
    $aPedidosSelecionados = explode(',', $sPedidosSelecionados);
  } else {
    $aPedidosSelecionados = array();
  }
  if (!empty($sPedidosExcluidos)) {
    $aPedidosExcluidos = explode(',', $sPedidosExcluidos);
  } else {
    $aPedidosExcluidos = array();
  }
  
  /* busco todos os pedidos que já estavam vinculados ao médico, para poder verificar 
     quais foram incluídos, alterados, etc.
  */
  $sSql          = $oDaoTfdPedidoRegulado->sql_query2(null, 'tf34_i_pedidotfd, tf34_i_codigo',
                                                       " sd03_i_codigo = $sd03_i_codigo "
                                                      );
  $rsPedidos     = $oDaoTfdPedidoRegulado->sql_record($sSql);
  $aListaPedidos = Array();
  for ($iCont = 0; $iCont < $oDaoTfdPedidoRegulado->numrows; $iCont++) {
       
    $oDadosPedido    = db_utils::fieldsmemory($rsPedidos, $iCont);
    $aListaPedidos[] = $oDadosPedido->tf34_i_pedidotfd;
    $aListaCodigo[]  = $oDadosPedido->tf34_i_codigo;

  }
  
  /* Seto as variáveis da classe */
  $oDaoTfdPedidoRegulado->erro_status        = null; // tirar o status de erro se a query retornar record vazio
  $oDaoTfdPedidoRegulado->tf34_i_especmedico = $tf34_i_especmedico;
  $oDaoTfdPedidoRegulado->tf34_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoTfdPedidoRegulado->tf34_c_horasistema = date('H:i');
  $oDaoTfdPedidoRegulado->tf34_i_login       = db_getsession('DB_id_usuario');
  /* for com a verificação de quais pedidos deverão ser incluídos ou alterados */
  for ($iCont = 0;$iCont < count($aPedidosSelecionados); $iCont++) {
      
    $oDaoTfdPedidoRegulado->tf34_i_pedidotfd = $aPedidosSelecionados[$iCont];

    // Se o pedido não estiver no $aListaPedidos (vetor dos Pedidos que já estavam vinculados ao profissional), deve ser incluído
    if (!in_array($aPedidosSelecionados[$iCont], $aListaPedidos)) {
      $oDaoTfdPedidoRegulado->incluir(null);
    }
    /* descomente para realizar a alteração quando o registro para o pedido já existe
    else { // Deverá ser alterado

      $iCod = $aListaCodigo[array_search($aPedidosSelecionados[$iCont], $aListaPedidos)];
      $oDaoTfdPedidoRegulado->tf34_i_codigo = $iCod;
      $oDaoTfdPedidoRegulado->alterar($iCod);

    }*/

    if ($oDaoTfdPedidoRegulado->erro_status == '0') {
      break;
    }

  }

  /* Verifico os pedidos que foram desmarcados para excluir. */
  if ($oDaoTfdPedidoRegulado->erro_status != '0') {

    for ($iCont = 0;$iCont < count($aPedidosExcluidos); $iCont++) {
      
      $oDaoTfdPedidoRegulado->excluir($aListaCodigo[array_search($aPedidosExcluidos[$iCont], $aListaPedidos)]);
      if ($oDaoTfdPedidoRegulado->erro_status == '0') {
        break;
      }
 
    } // fim for
  
  } // fim if
 
  db_fim_transacao($oDaoTfdPedidoRegulado->erro_status == '0' ? true : false);
  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
db_app::load(" grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br><br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
       <?
       $sSql = $oDaoDbUsuaCgm->sql_query(null, 'nome, cgmlogin', '', 
                                         ' db_usuacgm.id_usuario = '.db_getsession('DB_id_usuario')
                                        );
       $rs   = $oDaoDbUsuaCgm->sql_record($sSql);
      
       if ($oDaoDbUsuaCgm->numrows <= 0) {
         die('<br><span style="font-size: 22px; font-weight: bold;">Usuário logado não é um profissional da saúde.</span>');
       }
      
       $oDados     = db_utils::fieldsmemory($rs, 0);
       $z01_nome = $oDados->nome;
      
       $sSql       = $oDaoMedicos->sql_query_file(null, 'sd03_i_codigo', '', 
                                                 ' sd03_i_cgm = '.$oDados->cgmlogin);
       $rs         = $oDaoMedicos->sql_record($sSql);
      
       if ($oDaoMedicos->numrows <= 0) {
         die('<br><span style="font-size: 22px; font-weight: bold;">Usuário logado não é um profissional da saúde.</span>');
       }
       $oDados = db_utils::fieldsmemory($rs, 0);
       $sd03_i_codigo = $oDados->sd03_i_codigo;
       ?>
 
      <fieldset style='width: 92%;'> <legend><b>Regular Pedidos de TFD</b></legend>
    	  <?
	      require_once("forms/db_frmtfd_pedidoregulado.php");
	      ?>
      </fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","tf01_i_cgsund",true,1,"tf01_i_cgsund",true);
</script>
<?
if (isset($confirmar)) {

  if ($oDaoTfdPedidoRegulado->erro_status == '0') {

    $oDaoTfdPedidoRegulado->erro(true, false);
    $db_botao = true;

  } else {
    $oDaoTfdPedidoRegulado->erro(true, true);
  }

}
?>