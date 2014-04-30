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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoFarFatorRiscoFarmaciaAmbulatorial  = db_utils::getdao('far_fatorriscofarmaciaambulatorial');
$oDaoFarFatorRiscoFarmaciaAmbulatorial2 = db_utils::getdao('far_fatorriscofarmaciaambulatorial');
$oDaoFarFatorRisco                      = db_utils::getdao('far_fatorrisco');
$oDaoSauFatorDeRisco                    = db_utils::getdao('sau_fatorderisco');

$db_opcao                               = 1;
$db_botao                               = true;

if (isset($confirmar)) {

  db_inicio_transacao();

  $sSql        = $oDaoFarFatorRiscoFarmaciaAmbulatorial->sql_query_file(null, 'fa45_i_codigo');
  $rsLigacoes  = $oDaoFarFatorRiscoFarmaciaAmbulatorial->sql_record($sSql);
  $iResultados = $oDaoFarFatorRiscoFarmaciaAmbulatorial->numrows;

  /* Retiro o status de erro de quando ocorre record vazio */
  $oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_status = null;

  /* Excluo todas as ligações de fatores farmácia - ambulatorial existentes para incluir de novo 
     conforme dados enviados pelo formulário 
  */
  for ($iCont = 0; $iCont < $iResultados; $iCont++) {

    $oDadosLigacoes = db_utils::fieldsmemory($rsLigacoes, $iCont);
    $oDaoFarFatorRiscoFarmaciaAmbulatorial->excluir($oDadosLigacoes->fa45_i_codigo);
    if ($oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_status == '0') {
      break;
    }

  }
  
  if ($oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_status != '0') {

    $iFatores = count($fatoresFarmacia);
    for ($iCont = 0; $iCont < $iFatores; $iCont++) {
    
      /* se não foi selecionado fator de risco do ambulatorial equivalente */
      if ($fatoresAmbulatorial[$iCont] == '-1') {
        continue;
      }
    
      $oDaoFarFatorRiscoFarmaciaAmbulatorial->fa45_i_fatorriscoambulatorial = $fatoresAmbulatorial[$iCont];
      $oDaoFarFatorRiscoFarmaciaAmbulatorial->fa45_i_fatorriscofarmacia     = $fatoresFarmacia[$iCont];
      $oDaoFarFatorRiscoFarmaciaAmbulatorial->incluir(null);
      if ($oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_status == '0') {
        break;
      }
    
    }

  }

  db_fim_transacao($oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_status == '0' ? true : false);

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <fieldset style='width: 75%;'> <legend><b>Fatrores de Risco</b></legend>
          <?
          require_once("forms/db_frmfar_fatorriscofaramb.php");
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
js_tabulacaoforms("form1", "fa45_i_fatorriscoambulatorial", true, 1, "fa45_i_fatorriscoambulatorial", true);
</script>
<?
if (isset($confirmar)) {

  if ($oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_status == '0') {

    $oDaoFarFatorRiscoFarmaciaAmbulatorial->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_campo != '') {

      echo "<script> document.form1.".$oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_campo.
           ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoFarFatorRiscoFarmaciaAmbulatorial->erro_campo.".focus();</script>";

    }

  } else {
    $oDaoFarFatorRiscoFarmaciaAmbulatorial->erro(true, false);
  }

}
?>