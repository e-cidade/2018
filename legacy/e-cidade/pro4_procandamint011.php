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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_procandamint_classe.php"));
require_once(modification("classes/db_procandamintusu_classe.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_proctransferintand_classe.php"));
require_once(modification("classes/db_proctransfer_classe.php"));
require_once(modification("classes/db_protparam_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$clprotparam          = new cl_protparam;
$clprocandamint       = new cl_procandamint;
$clprocandamintusu    = new cl_procandamintusu;
$clprotprocesso       = new cl_protprocesso;
$clproctransferintand = new cl_proctransferintand;
$clproctransfer       = new cl_proctransfer;

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;
 
if ( isset($incluir) ) {

  db_inicio_transacao();

  $result = $clprotprocesso->sql_record($clprotprocesso->sql_query_file($p58_codproc,"p58_codandam"));
  db_fieldsmemory($result,0);

  $sWhereParametrosProtocolo = "p90_instit = " . db_getsession('DB_instit');
  $sSqlParametrosProtocolo = $clprotparam->sql_query_file(null, '*', null, $sWhereParametrosProtocolo);
  $result_protparam = $clprotparam->sql_record($sSqlParametrosProtocolo);

   /**
    * Encontrou parametros para instituicao atual 
    */
   if ( $clprotparam->numrows > 0 ) {

    db_fieldsmemory($result_protparam,0);

    $iDespachoMinimoCaracteres = $p90_minchardesp;
    $lDespachoObrigatorio = $p90_despachoob == "t";
    $iDespachoCaracteres = strlen($p78_despacho);

    /**
     * Parametro com minimo de caracteres 
     */
    if ( $iDespachoMinimoCaracteres > 0 ) {

      /**
       * Despacho obrigatorio 
       * Despacho não obrigatorio e quantidade de caracteres do despacho > 0
       */
      if ( $lDespachoObrigatorio || ( !$lDespachoObrigatorio && $iDespachoCaracteres > 0 )  ) {

        /**
         * Despacho com caracteres menor que o minimo 
         */
        if ( $iDespachoCaracteres < $iDespachoMinimoCaracteres) {			

          $erro_msg = "Mínimo de $iDespachoMinimoCaracteres caracteres para o despacho.";
          $sqlerro  = true;
        }
      }
    }

  }

  if ( $sqlerro == false ) {

  	$clprocandamint->p78_codandam = $p58_codandam;
  	$clprocandamint->p78_data     = date("Y-m-d", db_getsession("DB_datausu"));;
  	$clprocandamint->p78_hora     = db_hora();
	  $clprocandamint->p78_usuario  = db_getsession("DB_id_usuario");
  	$clprocandamint->p78_publico  = $p78_publico;
  	$clprocandamint->p78_transint = 'false';
  	$clprocandamint->p78_tipodespacho = $p78_tipodespacho;
  	$clprocandamint->incluir(null);

  	$erro_msg = $clprocandamint->erro_msg;

  	if ( $clprocandamint->erro_status == '0' ) {
    	$sqlerro = true;
  	} 

  	$codprocandamint = $clprocandamint->p78_sequencial;
  }

  if ( $sqlerro == false ) {
    $db_botao = false;
  }

  db_fim_transacao($sqlerro);

  $_SESSION['protprocesso_codprocandamint'] = $codprocandamint;
 
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
  td {white-space: nowrap;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?php include(modification("forms/db_frmprocandamint.php")); ?>
</body>
</html>
<?php

if ( isset($incluir) ) {

  db_msgbox($erro_msg);

  if( $sqlerro ){

    $clprocandamint->erro(true,false);
    $db_botao=true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($clprocandamint->erro_campo!=""){

      echo "<script> document.form1.".$clprocandamint->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocandamint->erro_campo.".focus();</script>";
    }

  } else {
    
     echo "<script>  parent.document.formaba.g3.disabled = false; </script>";

  }
}
