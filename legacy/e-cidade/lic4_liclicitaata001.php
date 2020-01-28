<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_liclicita_classe.php"));
include(modification("classes/db_liclicitaata_classe.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clliclicita    = new cl_liclicita;
$clliclicitaata = new cl_liclicitaata;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if(isset($incluir)){

	if( $sqlerro == false ){

		db_inicio_transacao();
  	try {
      $nomearq = File::cutName($_FILES["arquivoedital"]["name"], 50);
    } catch (Exception $oErro) {

      $sqlerro = true;
      $erro_msg = 'O arquivo selecionado tem uma extensão inválida.';
    }
  	$nometmp = $_FILES["arquivoedital"]["tmp_name"];
		$arquivoedital = $nometmp;
		$localrecebeedital =$arquivoedital;

		if($sqlerro == false && trim($localrecebeedital) != ""){

   		$arquivograva = fopen($localrecebeedital,"rb");
			$dados = fread($arquivograva,filesize($localrecebeedital));
   		fclose($arquivograva);
  		$oidgrava = pg_lo_create();

   		$clliclicitaata->l39_arquivo        = $oidgrava;
   		$clliclicitaata->l39_arqnome        = $nomearq;
   		$clliclicitaata->l39_liclicita      = $l20_codigo;
   		$clliclicitaata->l39_posicaoinicial = 'false';
   		$clliclicitaata->incluir(null);

   		$erro_msg = $clliclicitaata->erro_msg;

   		if($clliclicitaata->erro_status==0){
     		$sqlerro = true;
   		}

			$objeto = pg_lo_open($conn,$oidgrava,"w");

			if($objeto != false){

				$erro = pg_lo_write($objeto,$dados);
  			pg_lo_close($objeto);

			}else{

				$erro_msg("Operação Cancelada!!");
				$sqlerro=true;
			}

   	}

   	db_fim_transacao($sqlerro);

 	}

} else if(isset($alterar)) {

	if($sqlerro==false){

   	db_inicio_transacao();
    $clliclicitaata->excluir($l39_sequencial);
    $erro_msg = $clliclicitaata->erro_msg;

    if ($clliclicitaata->erro_status == 0 ) {

    	$sqlerro = true;

   	} else {

      try {
        $nomearq = File::cutName($_FILES["arquivoedital"]["name"], 50);
      } catch (Exception $oErro) {

        $sqlerro = true;
        $erro_msg = 'O arquivo selecionado tem uma extensão inválida.';
      }
  		$nometmp = $_FILES["arquivoedital"]["tmp_name"];
  		$arquivoedital = "tmp/$nomearq";

  		if(!copy($nometmp,$arquivoedital)){
 				db_msgbox("Erro ao enviar arquivo.");
 			}

			$localrecebeedital = $arquivoedital;

   		if($sqlerro == false && trim($localrecebeedital) != ""){

   			$arquivograva = fopen($localrecebeedital,"rb");
    		$dados = fread($arquivograva,filesize($localrecebeedital));

      	fclose($arquivograva);

      	$oidgrava = pg_lo_create();
      	$clliclicitaata->l39_arquivo        = $oidgrava;
      	$clliclicitaata->l39_arqnome        = $nomearq;
      	$clliclicitaata->l39_liclicita      = $l20_codigo;
      	$clliclicitaata->l39_posicaoinicial = 'false';
      	$clliclicitaata->incluir(null);
      	$erro_msg = $clliclicitaata->erro_msg;

      	if($clliclicitaata->erro_status==0){
        	$sqlerro = true;
      	}

				$objeto = pg_lo_open($conn,$oidgrava,"w");

				if($objeto != false){

					pg_lo_write($objeto,$dados);
					pg_lo_close($objeto);

				} else {
					$sqlerro = true;
				}
   		}
   	}
   	db_fim_transacao($sqlerro);
 	}

} else if(isset($excluir)) {

	if( $sqlerro==false ){

   	db_inicio_transacao();
    $clliclicitaata->excluir($l39_sequencial);
    $erro_msg = $clliclicitaata->erro_msg;

    if($clliclicitaata->erro_status==0){
     	$sqlerro=true;
    }

   	db_fim_transacao($sqlerro);

 	}

} else if (isset($opcao)) {

	$result=$clliclicita->sql_record($clliclicitaata->sql_query($l39_sequencial));

	if ($clliclicita->numrows){
		db_fieldsmemory($result,0);
		$arquivoedital=$l39_arqnome;
	}

  $result = $clliclicita->sql_record($clliclicita->sql_query($l39_liclicita));
  if($clliclicita->numrows>0){
   	db_fieldsmemory($result,0);
   	$db_botao = true;
  }

}

if(isset($chavepesquisa)){

	$db_opcao = 1;
  $result = $clliclicita->sql_record($clliclicita->sql_query($chavepesquisa));

  if($clliclicita->numrows>0){
    db_fieldsmemory($result,0);
    $db_botao = true;
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
<table align="center" style="padding-top:25px;">
  <tr>
    <td>
    <center>
			<?
			  include(modification("forms/db_frmliclicitaata.php"));
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
if(isset($alterar) || isset($excluir) || isset($incluir)){
  db_msgbox($erro_msg);
}

if($db_opcao==22){
  echo "<script>js_pesquisa();</script>";
}
?>
