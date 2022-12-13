<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("classes/db_procandamint_classe.php");
require_once("classes/db_procandamintusu_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clprocandamint    = new cl_procandamint;
$clprocandamintusu = new cl_procandamintusu;
$clprotprocesso    = new cl_protprocesso;
$clprotparam       = db_utils::getDao('protparam');

$db_opcao = 2;
$db_botao = true;

if ( isset($alterar) ) {

  db_inicio_transacao();

  $sqlerro = false;
  $result_procandam=$clprotprocesso->sql_record($clprotprocesso->sql_query_file(null,"*",null,"p58_codproc=$p58_codproc and p58_codandam=$p58_codandam"));
  $numrows_proc=$clprotprocesso->numrows;

  if ( $numrows_proc > 0 ) {

    $result_despacho=$clprocandamint->sql_record($clprocandamint->sql_query_file(null,"p78_sequencial","p78_sequencial desc limit 1","p78_codandam=$p58_codandam"));
    $numrows_desp=$clprocandamint->numrows;

    if ( $numrows_desp > 0 ) { 

      $sequencial = $p78_sequencial;

      db_fieldsmemory($result_despacho, 0);

      if ($p78_sequencial == $sequencial) {

        $p78_despacho = trim($p78_despacho);

        $sWhereParametrosProtocolo = "p90_instit = " . db_getsession('DB_instit');
        $sSqlParametrosProtocolo = $clprotparam->sql_query_file(null, '*', null, $sWhereParametrosProtocolo);
        $result_protparam = $clprotparam->sql_record($sSqlParametrosProtocolo);

        /**
         * Encontrou parametros para instituicao atual 
         */
        if ( $clprotparam->numrows > 0 ) {

          db_fieldsmemory($result_protparam, 0);

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

        if ( !$sqlerro ) {

          $clprocandamint->p78_codandam = $p58_codandam;
          $clprocandamint->p78_data     = date("Y-m-d",db_getsession("DB_datausu"));
          $clprocandamint->p78_hora     = db_hora();
          $clprocandamint->p78_usuario  = db_getsession("DB_id_usuario");
          $clprocandamint->p78_publico  = $p78_publico;
          $clprocandamint->p78_transint = 'false';
          $clprocandamint->p78_despacho = $p78_despacho;
          $clprocandamint->p78_tipodespacho = $p78_tipodespacho;
          $clprocandamint->alterar($sequencial);

          $erro_msg = $clprocandamint->erro_msg;

          if ($clprocandamint->erro_status==0){
            $sqlerro=true;
          }
        }

      } else {

        $sqlerro  = true;
        $erro_msg = "Durante a digitação do despacho o processo teve andamento!!";
      }
    }

  } else {

    $sqlerro  = true;
    $erro_msg = "Durante a digitação do despacho o processo teve andamento!!";
  }

  db_fim_transacao($sqlerro);
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
	<?php require_once("forms/db_frmdespachoalt.php"); ?>
</body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

if(isset($alterar)){

  db_msgbox($erro_msg);

  if($sqlerro==true){

    $clprocandamint->erro(true,false);

    if($clprocandamint->erro_campo!=""){

      echo "<script> document.form1.".$clprocandamint->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocandamint->erro_campo.".focus();</script>";
    }
  }

}
?>
