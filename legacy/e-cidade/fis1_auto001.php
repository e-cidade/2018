<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require("libs/db_utils.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){

  echo "<script>location.href='fis1_auto005.php'</script>";
  exit;
}

db_postmemory($HTTP_POST_VARS);

$clauto            = new cl_auto;
$clautolocal       = new cl_autolocal;
$clautoexec        = new cl_autoexec;
$clautoinscr       = new cl_autoinscr;
$clautomatric      = new cl_automatric;
$clautocgm         = new cl_autocgm;
$clautofiscal      = new cl_autofiscal;
$clautosanitario   = new cl_autosanitario;
$clprocfiscalauto  = new cl_procfiscalauto;
$clfiscalcgm       = new cl_fiscalcgm;
$clfiscalmatric    = new cl_fiscalmatric;
$clfiscalinscr     = new cl_fiscalinscr;
$clfiscalsanitario = new cl_fiscalsanitario;

$db_opcao = 1;
$db_botao = true;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  db_inicio_transacao();
  $sqlerro=false;
  $clauto->y50_instit = db_getsession('DB_instit') ;
  $clauto->incluir($y50_codauto);
  $erro=$clauto->erro_msg;
  if ($clauto->erro_status==0){
    $sqlerro=true;
  }
  $y50_codauto = $clauto->y50_codauto;
	if ($sqlerro==false){

		if($procfiscal !=""){

			$clprocfiscalauto->y111_procfiscal = $procfiscal;
			$clprocfiscalauto->y111_auto       = $y50_codauto;
			$clprocfiscalauto->incluir(null);
			if ($clprocfiscalauto->erro_status==0){
	      $sqlerro=true;
				$erro=$clprocfiscalauto->erro_msg;
	    }
		}
	}
  if ($sqlerro==false){

    $clautolocal->y14_codauto=$y50_codauto;
    $clautolocal->y14_codigo=$y14_codigo;
    $clautolocal->y14_codi=@$y14_codi;
    $clautolocal->y14_numero=$y14_numero;
    $clautolocal->y14_compl=$y14_compl;
    $clautolocal->incluir($y50_codauto);
    if ($clautolocal->erro_status==0){

      $sqlerro=true;
      $erro=$clautolocal->erro_msg;
    }
  }

  if ($sqlerro==false){

    $clautoexec->y15_codauto=$y50_codauto;
    $clautoexec->y15_codigo=$y15_codigo;
    $clautoexec->y15_codi=@$y15_codi;
    $clautoexec->y15_numero=$y15_numero;
    $clautoexec->y15_compl=$y15_compl;
    $clautoexec->incluir($clauto->y50_codauto);
    if ($clautoexec->erro_status==0){

      $sqlerro=true;
      $erro=$clautoexec->erro_msg;
    }
  }
  if ($sqlerro==false){

    if(isset($z01_numcgm) && $z01_numcgm != ""){

      $clautocgm->y54_numcgm=$z01_numcgm;
      $clautocgm->incluir($y50_codauto);
      if($clautocgm->erro_status==0){

        $erro=$clautocgm->erro_msg;
		    $sqlerro = true;
      }
    }elseif(isset($j01_matric) && $j01_matric != ""){

      $clautomatric->y53_matric=$j01_matric;
      $clautomatric->incluir($y50_codauto);
      if($clautomatric->erro_status==0){

    		$erro=$clautomatric->erro_msg;
    		$sqlerro = true;
      }
    }elseif(isset($q02_inscr)  && $q02_inscr  != ""){

      $clautoinscr->y52_inscr=$q02_inscr;
      $clautoinscr->incluir($y50_codauto);
      if($clautoinscr->erro_status==0){

    	   $erro=$clautoinscr->erro_msg;
	       $sqlerro = true;
      }
    }elseif(isset($y80_codsani)  && $y80_codsani  != ""){

      $clautosanitario->y55_codsani=$y80_codsani;
      $clautosanitario->incluir($y50_codauto);
      if($clautosanitario->erro_status==0){

		    $sqlerro = true;
        $erro=$clautosanitario->erro_msg;
      }
    }elseif(isset($y30_codnoti)  && $y30_codnoti  != ""){

      $clautofiscal->y51_codnoti=$y30_codnoti;
      $clautofiscal->incluir($y50_codauto);
      if($clautofiscal->erro_status==0){

        $erro=$clautofiscal->erro_msg;
		    $sqlerro = true;
      }else{

        /**
    	   * Verifica a origem da notificação
    	   */
    		//matricula
    		$rsMatric = $clfiscalmatric->sql_record($clfiscalmatric->sql_query($y30_codnoti));
    		if ( $clfiscalmatric->numrows > 0 ) {

    		  $oFiscalMatric = db_utils::fieldsmemory($rsMatric,0);
    		  $clautomatric->y53_matric=$oFiscalMatric->y35_matric;
          $clautomatric->incluir($y50_codauto);
          if ( $clautomatric->erro_status==0 ) {

            $erro=$clautomatric->erro_msg;
            $sqlerro = true;
          }
    		}

    		//inscrição
    		$rsInscr = $clfiscalinscr->sql_record($clfiscalinscr->sql_query($y30_codnoti));
    		if ( $clfiscalinscr->numrows > 0 ) {

    	    $oFiscalInscr = db_utils::fieldsmemory($rsInscr,0);
     	    $clautoinscr->y52_inscr=$oFiscalInscr->q02_inscr;
          $clautoinscr->incluir($y50_codauto);
          if ( $clautoinscr->erro_status==0 ) {
            $erro=$clautoinscr->erro_msg;
            $sqlerro = true;
          }
    		}

    		//sanitario
    		$rsSanitario = $clfiscalsanitario->sql_record($clfiscalsanitario->sql_query($y30_codnoti));
    		if ( $clfiscalsanitario->numrows > 0 ) {

          $oFiscalSanitario = db_utils::fieldsmemory($rsSanitario,0);
          $clautosanitario->y55_codsani=$oFiscalSanitario->y80_codsani;
          $clautosanitario->incluir($y50_codauto);
          if ( $clautosanitario->erro_status==0 ) {
            $sqlerro = true;
            $erro=$clautosanitario->erro_msg;
          }
    		}

    		//cgm
    		$rsCgm = $clfiscalcgm->sql_record($clfiscalcgm->sql_query($y30_codnoti));
    		if ( $clfiscalcgm->numrows > 0 ) {

          $oFiscalCgm = db_utils::fieldsmemory($rsCgm,0);
          $clautocgm->y54_numcgm = $oFiscalCgm->y36_numcgm;
          $clautocgm->incluir($y50_codauto);
          if ( $clautocgm->erro_status==0 ) {
            $erro=$clautocgm->erro_msg;
            $sqlerro = true;
          }
    		}

      }
    }
  }
  db_fim_transacao($sqlerro);
}
if(!isset($pri)){

  include("fis1_auto004.php");
  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
  	<?php
  	 include("forms/db_frmauto.php");
  	?>
  </div>
</body>
</html>
<script type="text/javascript">
js_setatabulacao();
</script>
<?php

if(isset($rnum) && $rnum != ""){

    if(isset($rorigem) && $rorigem == "cgm"){
      $rsResult = $clautocgm->sql_record($clautocgm->sql_query(null,"*",null," y54_numcgm = $rnum"));
  	  $numrows  = $clautocgm->numrows;
  	  if($numrows > 0){

  		 db_msgbox("Existem autos anteriores para este CGM");
  		 echo "<script>
  		          js_pesquisare($rnum,'cgm');
  		       </script>";
      }
    }elseif(isset($rorigem) && $rorigem == "matric"){

      $rsResult = $clautomatric->sql_record($clautomatric->sql_query(null,"*",null," y54_numcgm = $rnum"));
	    $numrows  = $clautomatric->numrows;
  	  if($numrows > 0){

  		 db_msgbox("Existem autos anteriores para esta MATRICULA");
  		 echo "<script>
  		          js_pesquisare($rnum,'matric');
  		       </script>";
      }
    }elseif(isset($rorigem) && $rorigem == "inscr"){

  	  $rsResult = $clautoinscr->sql_record($clautoinscr->sql_query(null,"*",null," y52_inscr = $rnum"));
  	  $numrows  = $clautoinscr->numrows;
  	  if($numrows > 0){

  		 db_msgbox("Existem autos anteriores para esta INSCRIÇÂO");
  		 echo "<script>
  		             js_pesquisare($rnum,'inscr');
  			    </script>";
      }
    }elseif(isset($rorigem) && $rorigem == "sani"){

  	  $rsResult = $clautosanitario->sql_record($clautosanitario->sql_query(null,"*",null," y52_inscr = $q02_inscr"));
  	  $numrows  = $clautosanitario->numrows;
  	  if($numrows > 0){
  		 db_msgbox("Existem autos anteriores para este SANITARIO");
  		 echo "<script>
  		           js_pesquisare($rnum,'sani');
  		       </script>";
      }
    }
}

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  db_msgbox($erro);
  if($sqlerro==true){

    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clauto->erro_campo!=""){

      echo "<script> document.form1.".$clauto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clauto->erro_campo.".focus();</script>";
    }elseif($clautoexec->erro_campo!=""){

      echo "<script> document.form1.".$clautoexec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautoexec->erro_campo.".focus();</script>";
    }elseif($clautolocal->erro_campo!=""){

      echo "<script> document.form1.".$clautolocal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautolocal->erro_campo.".focus();</script>";
    }
  }else{

    echo "
         <script>
         function js_src(){
           parent.iframe_auto.location.href        = 'fis1_auto002.php?chavepesquisa=".$clauto->y50_codauto."&abas=1';\n
           parent.iframe_autolevanta.location.href = 'fis1_autolevanta001.php?y50_codauto=".$clauto->y50_codauto."&abas=1';\n
           parent.iframe_autotipo.location.href    = 'fis1_autotipo001.php?y59_codauto=".$clauto->y50_codauto."&abas=1';\n
           parent.iframe_receitas.location.href    = 'fis1_autorec001.php?y59_codauto=".$clauto->y50_codauto."&abas=1';\n
           parent.iframe_fiscais.location.href     = 'fis1_autousu001.php?y56_codauto=".$clauto->y50_codauto."&abas=1';\n
           parent.iframe_testem.location.href      = 'fis1_autotestem001.php?y24_codauto=".$clauto->y50_codauto."&abas=1';\n
           parent.iframe_calculo.location.href     = 'fis1_autocalc001.php?y50_codauto=".$clauto->y50_codauto."&abas=1';\n

           parent.mo_camada('autolevanta');

           parent.document.formaba.autolevanta.disabled = false;
    		   parent.document.formaba.autotipo.disabled    = false;
    		   parent.document.formaba.receitas.disabled    = false;
    		   parent.document.formaba.fiscais.disabled     = false;
    		   parent.document.formaba.testem.disabled      = false;
    		   parent.document.formaba.calculo.disabled     = false;
         }
         js_src();
         </script>
       ";
  }
}
?>