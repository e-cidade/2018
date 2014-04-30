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
require_once("libs/db_stdlibwebseller.php");
require_once("classes/db_sau_medicosforarede_classe.php");
require_once("classes/db_medicos_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoSauMedicosForaRede = new cl_sau_medicosforarede;
$oDaoMedicos            = new cl_medicos;
$db_opcao               = 1;
$db_botao               = true;
$lIncluir               = true;

if (isset($incluir)) {

  if (!empty($s154_c_cns)) {

    if (!validaCnsDefinitivo($s154_c_cns)) {

      if (!validaCnsProvisorio($s154_c_cns)) {

        db_msgbox(_M('saude.ambulatorial.sau1_sau_medicosforarede001.cns_invalido'));
        $lIncluir = false;
      }
    }
  }

  if ($lIncluir) {

    db_inicio_transacao();

    unset($GLOBALS['sd03_i_cgm']); // Deleto o CGM
    db_query('ALTER TABLE medicos DISABLE TRIGGER ALL');

    $oDaoMedicos->sd03_i_tipo = 2; // Médico fora da rede
    $oDaoMedicos->incluir(null);
    if ($oDaoMedicos->erro_status != '0') {

      $oDaoSauMedicosForaRede->s154_i_medico = $oDaoMedicos->sd03_i_codigo;
      $oDaoSauMedicosForaRede->incluir(null);

      if ($oDaoSauMedicosForaRede->erro_status == '0') {

        $oDaoMedicos->erro_status = '0';
        $oDaoMedicos->erro_msg    = $oDaoSauMedicosForaRede->erro_msg;

      }

    }

    db_fim_transacao($oDaoMedicos->erro_status == '0' ? true : false);

    db_query('ALTER TABLE medicos ENABLE TRIGGER ALL'); // Reabilito as triggers
  }

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
        <fieldset style='width: 75%;'> <legend><b>Profissional Fora da Rede</b></legend>
          <?
          require_once("forms/db_frmsau_medicosforarede.php");
          ?>
        </fieldset>
      </center>
    </td>
  </tr>
</table>
</center>
<?
if (!isset($lBotao) || $lBotao != 'true') {

  db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'),
          db_getsession('DB_anousu'), db_getsession('DB_instit')
         );

}
?>
</body>
</html>
<script>
js_tabulacaoforms("form1", "s154_i_medico", true, 1, "s154_i_medico", true);
</script>
<?
if (isset($incluir) && $lIncluir) {

  if ($oDaoMedicos->erro_status == '0') {

    $oDaoMedicos->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoMedicos->erro_campo != '') {

      echo "<script> document.form1.".$oDaoMedicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoMedicos->erro_campo.".focus();</script>";

    }

  } else {

    $oDaoMedicos->erro(true, false);
    if (isset($lBotao) && $lBotao == 'true') {

      $iUltimo = $oDaoMedicos->sd03_i_codigo;
      $sGet    = "&lBotao=true&chavepesquisa=$iUltimo";
      echo "<script>parent.js_preencheMedicoRecemCadastrado($iUltimo);</script>";
      db_redireciona("sau1_sau_medicosforarede002.php?$sGet");

    } else {

      db_redireciona("sau1_sau_medicosforarede001.php");

    }

  }

}
?>