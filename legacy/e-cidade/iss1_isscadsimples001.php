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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_isscadsimples_classe.php"));
require_once(modification("classes/db_issbase_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
$post                 = db_utils::postmemory($_POST);

$clisscadsimples      = new cl_isscadsimples();
$clissbase            = new cl_issbase();
(int)  $db_opcao      = 1;
(int)  $db_opcaoinscr = 1;
(bool) $db_botao      = true;
(bool) $baixado       = false;
(bool) $lSqlErro      = false;

if(isset($incluir)){

  $rsIss = 	$clisscadsimples->sql_record($clisscadsimples->sql_query_baixa(null,'q38_dtinicial,q38_sequencial,q38_inscr,z01_nome',null
	                              ,"q38_inscr = $q38_inscr and q39_isscadsimples is null"));

  if (isset($post->q38_dtinicial)){

      $dataIni  = split("/",$post->q38_dtinicial);
      $dataIni2 = $dataIni[2]."-".$dataIni[1]."-".$dataIni[0];
  }else{
      $dataIni2 = $post->q38_dtinicial_ano."-".$post->q38_dtinicial_mes."-".$post->q38_dtinicial_dia;
  }

	if ($clisscadsimples->numrows > 0){

      $oIss                         = db_utils::fieldsMemory($rsIss,0);
		  $clisscadsimples->erro_status = 0;
			$clisscadsimples->erro_msg    = "Inscricão já cadastrada no simples \\nData Início: ".db_formatar($oIss->q38_dtinicial,"d");
			$clisscadsimples->erro_msg   .= "\\nCódigo Simples : ".$oIss->q38_sequencial;

	}else{

         $rsIss = $clissbase->sql_record($clissbase->sql_query($post->q38_inscr));

		 if ($clissbase->numrows > 0){

             $oIss = db_utils::fieldsMemory($rsIss,0);
			 if (db_strtotime($dataIni2) < db_strtotime($oIss->q02_dtinic)){

			 	/*
			 	 * aqui Sistema está permitindo colocar data de Inicio do Simples apenas a 
			 	 * partir da data de Inicio da Inscrição.
			 	 * 
                 $erro_msg = "Data Inicial menor que a data do início da Atividade.";
				 $lSqlErro = true;
				 $clisscadsimples->erro_msg    = $erro_msg;
				 $clisscadsimples->erro_campo  = "q38_dtinicial";
				 $clisscadsimples->erro_status = 0;
			 	 */
			 }
		 }

     $sSqlBaixa    = $clisscadsimples->sql_query_baixa(null,'q38_dtinicial,q38_sequencial,q38_inscr,z01_nome',null,"q38_inscr = $q38_inscr and q39_dtbaixa > '$dataIni2'::date");
     $rsIssSimples = db_query($sSqlBaixa);

     if( $rsIssSimples ){

       if(pg_num_rows($rsIssSimples) > 0){

          $erro_msg = "Data de Inclusão deve ser maior que a data da Baixa do Simples.";
  				$lSqlErro = true;
  				$clisscadsimples->erro_msg    = $erro_msg;
  				$clisscadsimples->erro_campo  = "q38_dtinicial";
  				$clisscadsimples->erro_status = 0;
       }
     }

		 if (!$lSqlErro){

        db_inicio_transacao();
        $clisscadsimples->incluir($q38_sequencial);
        db_fim_transacao($lSqlErro);
		 }
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
<body class="body-default">
  <div class="container">
	<?php
	  include(modification("forms/db_frmisscadsimples.php"));
	?>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script type="text/javascript">
js_tabulacaoforms("form1","q38_inscr",true,1,"q38_inscr",true);
</script>
<?php

if(isset($incluir)){

  if($clisscadsimples->erro_status=="0"){

    $clisscadsimples->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clisscadsimples->erro_campo!=""){

      echo "<script> document.form1.".$clisscadsimples->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clisscadsimples->erro_campo.".focus();</script>";
    }
  }else{
    $clisscadsimples->erro(true,true);
  }
}
?>
