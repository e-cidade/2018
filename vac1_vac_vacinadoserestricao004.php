<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

db_postmemory($HTTP_POST_VARS);

$oDaoVacVacinadoserestricao  = db_utils::getdao('vac_vacinadoserestricao');
$oDaovac_restricao           = db_utils::getdao('vac_restricao');

$db_opcao = 1;
$db_botao = true;

if (isset($confirmar)) {

  db_inicio_transacao();

  $sSql                                     = $oDaoVacVacinadoserestricao->sql_query(null, 'vc08_i_codigo',
                                                                                      null, 'vc08_i_vacinadose = '.
                                                                                      $vc08_i_vacinadose);
  $rs                                       = $oDaoVacVacinadoserestricao->sql_record($sSql);
  $oDaoVacVacinadoserestricao->erro_status  = null; // record vazio altera para'0', por isso set null
  $iLinhas                                  = $oDaoVacVacinadoserestricao->numrows;
  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $oDados                                     = db_utils::fieldsmemory($rs, $iCont);
    $oDaoVacVacinadoserestricao->vc08_i_codigo  = $oDados->vc08_i_codigo;
    $oDaoVacVacinadoserestricao->excluir($oDados->vc08_i_codigo);
    if ($oDaoVacVacinadoserestricao->erro_status == '0') {
      break;
    }
    
  }

  
  if ($oDaoVacVacinadoserestricao->erro_status != '0' && isset($restricoesAdicionadas)) {

    $oDaoVacVacinadoserestricao->vc08_i_vacinadose = $vc08_i_vacinadose;
    for ($iCont = 0; $iCont < count($restricoesAdicionadas); $iCont++) {

      $oDaoVacVacinadoserestricao->vc08_i_restricao = $restricoesAdicionadas[$iCont];
      $oDaoVacVacinadoserestricao->incluir(null);
      if ($oDaoVacVacinadoserestricao->erro_status == '0') {
        break;
      }
    
    }

  }

  db_fim_transacao($oDaoVacVacinadoserestricao->erro_status == '0' ? true : false);

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
	    require_once("forms/db_frmvac_vacinadoserestricao.php");
    	?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1", "vc08_i_restricao", true, 1, "vc08_i_restricao", true);
</script>
<?
if (isset($confirmar)) {
  if ($oDaoVacVacinadoserestricao->erro_status == '0') {
    $oDaoVacVacinadoserestricao->erro(true, false);
  } else {

    $oDaoVacVacinadoserestricao->erro(true, false);
    db_redireciona('vac1_vac_vacinadoserestricao004.php?vc08_i_vacinadose='.$vc08_i_vacinadose.
                   '&vc07_c_nome='.$vc07_c_nome.'&vc06_c_descr='.$vc06_c_descr.'&vc06_i_codigo='.$vc06_i_codigo);

  }

}
?>