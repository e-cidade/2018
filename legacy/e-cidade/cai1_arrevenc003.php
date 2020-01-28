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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_arrevenc_classe.php"));
require_once(modification("classes/db_arrevenclog_classe.php"));
require_once(modification("classes/db_arreinstit_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_GET);
db_postmemory($_POST);

$clarrevenc     = new cl_arrevenc;
$clarreinstit   = new cl_arreinstit;
$clarrevenclog  = new cl_arrevenclog;
$db_botao       = false;
$db_opcao       = 33;
$botaoiframe    = 3;
$db_opcaonumpre = 3;

if ( isset($excluir) ) {

	$sqlerro = false;
  db_inicio_transacao();

  $clarreinstit->sql_record($clarreinstit->sql_query_file(null,"*",null,"k00_numpre = {$k00_numpre} and k00_instit = ".db_getsession('DB_instit') ) );

  if ($clarreinstit->numrows == 0) {

    db_msgbox("Numpre de outra instituição inclusão abortada");
    $sqlerro = true;
  } else {

  	$clarrevenc->k00_sequencial    = $k00_sequencial;
		$clarrevenc->excluir($k00_sequencial);

		if($clarrevenc->erro_status=="0"){
			$sqlerro = true;
			$msgerro = $clarrevenc->erro_msg;
		}
  }

  db_fim_transacao($sqlerro);

	if($sqlerro == false){
		$msgerro = "Exclusão efetuada com sucesso.";
	}
} else if ( isset($chavepesquisa) ) {

   $db_opcao       = 3;
	 $botaoiframe    = 3;
   $k75_sequencial = $chavepesquisa;
}

if ( isset($k75_sequencial) and $k75_sequencial != "" ){

 	$sqlCarrega = "select k75_usuario,login, k75_data,k75_hora,k00_numpre
	                 from arrevenclog
                        inner join arrevenc    on k00_arrevenclog = k75_sequencial
						            inner join db_usuarios on id_usuario      = k75_usuario
						      where k75_sequencial = {$k75_sequencial}";
  $rsCarrega     = db_query($sqlCarrega);
	$linhasCarrega = pg_num_rows($rsCarrega);

	if ( $linhasCarrega > 0 ) {
	 	db_fieldsmemory($rsCarrega,0);
	}

  $db_opcaonumpre = 3;
}

if(isset($k00_sequencial) && $k00_sequencial!=""){

  $sqlAlt    = "select * from arrevenc where k00_sequencial =$k00_sequencial";
	$rsAlt     = db_query($sqlAlt);
	$linhasAlt = pg_num_rows($rsAlt);

	if($linhasAlt > 0){

	  db_fieldsmemory($rsAlt,0);
	  $db_botao = true;
	}
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0">
    <?php
      db_app::load("scripts.js, strings.js, numbers.js, prototype.js ");
      db_app::load("estilos.css, AjaxRequest.js");
    ?>
  </head>
  <body class="body-default">
    <div class="container">
      <?php
  	    include(modification("forms/db_frmarrevenc.php"));
  	  ?>
    </div>
    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );
    ?>
  </body>
</html>
<?php
if($clarrevenc->erro_status=="0"){
  $clarrevenc->erro(true,false);
}else{
  $clarrevenc->erro(true,true);
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>