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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("classes/db_rhpagatra_classe.php");
include("classes/db_rhpagocor_classe.php");
include("classes/db_rhpesjustica_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clrhpagatra = new cl_rhpagatra;
$clrhpagocor = new cl_rhpagocor;
$clrhpesjustica = new cl_rhpesjustica;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro = false;
  if($rh58_tipoocor != ""){
    $or = "";

    $usar_justica = false;
    $dbwhere = "";
    if($pagar == 1){
      $usar_justica = true;
      $dbwhere = " and registro is null ";
    }

    $dbwhere.= " and ( ";
    for($i=0; $i<count($selecionados); $i++){
      $anoescolha = substr($selecionados[$i],0,4);
      $mesescolha = substr($selecionados[$i],4,2);
      $dbwhere .= $or." (rh57_ano = ".$anoescolha." and rh57_mes = ".$mesescolha.") ";
      $or = " or ";
    }
    $dbwhere.= "     ) ";

    $result_dados = $clrhpagocor->sql_record($clrhpagocor->sql_query_notjustica(null," distinct rh57_saldo, rh57_seq, rh57_ano, rh57_mes "," rh57_ano, rh57_mes "," rh57_saldo > 0 ".$dbwhere,$usar_justica));
    $numrows_dados = $clrhpagocor->numrows;

    db_inicio_transacao();
    for($i=0; $i<$numrows_dados; $i++){
      db_fieldsmemory($result_dados, $i);
      $clrhpagocor->rh58_seq = $rh57_seq;
      $clrhpagocor->rh58_tipoocor = $rh58_tipoocor;
      $clrhpagocor->rh58_valor = $rh57_saldo;
      $clrhpagocor->rh58_obs = $rh58_obs;
      $clrhpagocor->rh58_data = "$rh58_data_ano-$rh58_data_mes-$rh58_data_dia";
      $clrhpagocor->incluir(null);
      if($clrhpagocor->erro_status == 0){
        $erro_msg = $clrhpagocor->erro_msg;
        $sqlerro = true;
        break;
      }

      $clrhpagatra->rh57_seq = $rh57_seq;
      $clrhpagatra->rh57_saldo = "0";
      $clrhpagatra->alterar($rh57_seq);
      if($clrhpagatra->erro_status == 0){
        $erro_msg = $clrhpagatra->erro_msg;
        $sqlerro = true;
        break;
      }
    }
    db_fim_transacao($sqlerro);

    if($sqlerro == false){
      $erro_msg = $i." movimentos pagos.";
    }
  }else{
    $erro_msg = "Informe o tipo de ocorrência referente a pagamentos bancários.";
    $sqlerro = true;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
      include("forms/db_frmrhpagatragerabancos.php");
      ?>
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro == false){
    echo "<script>location.href = 'pes1_rhpagatragerabancos001.php'</script>";
  }
}
?>