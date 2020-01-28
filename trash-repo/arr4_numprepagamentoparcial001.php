<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("classes/db_numpref_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

$oDaoNumPref   = new cl_numpref;
$oPost         = db_utils::postMemory($_POST);
$iAnoUsu       = db_getsession('DB_anousu');
$iInstit       = db_getsession('DB_instit');                                     
$sLogin        = db_getsession('DB_login',$iInstit);

$sDisable      = ' disabled ';
$sOpenTagForm  = null;
$sCloseTagForm = null;
$db_opcao      = 3;

$oDaoNumPref->rotulo->label();

$sSqlNumPref = $oDaoNumPref->sql_query_file($iAnoUsu, $iInstit, 'k03_numprepgtoparcial');
$rsNumPref   = $oDaoNumPref->sql_record($sSqlNumPref);

/**
 * Valida se existe registros do parâmetro da Numpref
 */
if ($oDaoNumPref->numrows > 0) { 
  $k03_numprepgtoparcial = db_utils::fieldsMemory($rsNumPref, 0)->k03_numprepgtoparcial;
} 

/**
 * Valida se foi o login dbseller
 */
if ($sLogin == 'dbseller') {
	
	
  if ($k03_numprepgtoparcial == 0) {
  	
  	$sOpenTagForm  = '<form method="post" action="" onSubmit="return js_ValidaFormulario();">';
  	$sCloseTagForm = '</form>';
  	$db_opcao      = 2;
	  $sDisable      = null;
  } 


  if ( isset($oPost->alterar) && $oPost->k03_numprepgtoparcial > 0) {

    try {

      db_inicio_transacao();

      $oDaoNumPref->k03_numprepgtoparcial = $oPost->k03_numprepgtoparcial;
      $oDaoNumPref->k03_anousu            = $iAnoUsu;
      $oDaoNumPref->k03_instit            = $iInstit;
      $oDaoNumPref->alterar($iAnoUsu, $iInstit);

      if ($oDaoNumPref->erro_status == '0') {
        throw new Exception ($oDaoNumPref->erro_msg);
      }

      db_fim_transacao(false);
			db_redireciona("arr4_numprepagamentoparcial001.php");
    } catch(Exception $oErro) {

      db_msgbox( $oErro->getMessage() );
      db_fim_transacao(true);
    }
  }
} 


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="expires" content="0">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">
<br /><br />
<center>
  <?php echo $sOpenTagForm; ?>

    <fieldset id="Numpre Pagamento Parcial" style="width:300px;margin-bottom:5px;">

      <legend><b>Numpre Pagamento Parcial</b></legend>

      <table border="0" style="width:100%;">
        <tr>
          <td nowrap title="<?php $Tk03_numprepgtoparcial; ?>">
            <?php echo $Lk03_numprepgtoparcial; ?>
          </td>
          <td> 
            <?php db_input('k03_numprepgtoparcial',12,$Ik03_numprepgtoparcial,true,'text',$db_opcao,""); ?>
          </td>
        </tr>
      </table>

    </fieldset>

    <input name="alterar" type="submit" id="alterar" value="Alterar" <?php echo $sDisable; ?> />

  <?php echo $sCloseTagForm; ?>
</center>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>   
<script>

/**
 * Valida formulario
 */
function js_ValidaFormulario() {
	
  var iNumpre = document.getElementById("k03_numprepgtoparcial").value;
	if ( parseInt(iNumpre) == 0 || iNumpre == "" ) {

	  alert("Numpre não pode ser 0 ou vazio!");
	  return false;
	}
}

</script>