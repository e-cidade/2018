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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_veicdevolucao_classe.php");
require_once("classes/db_veicretirada_classe.php");
require_once("classes/db_veiculos_classe.php");
require_once("classes/db_veictipoabast_classe.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
db_app::import("veiculos.*");
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);

$clveicdevolucao = new cl_veicdevolucao;
$clveicretirada  = new cl_veicretirada;
$clveiculos      = new cl_veiculos;
$clveictipoabast = new cl_veictipoabast;

$db_opcao = 1;
$db_botao = true;
$pesq=false;
$sqlerro = false;

if (isset($incluir)) {


  db_inicio_transacao();

    /*
     * Verificamos se ja foi realizada a devolução para esta retirada
     */
    $sSqlValidaDevolucao = $clveicdevolucao->sql_query_file(null, "ve61_codigo", null, "ve61_veicretirada = {$ve61_veicretirada}");
    $rsDevolucao = $clveicdevolucao->sql_record($sSqlValidaDevolucao);
    if ($clveicdevolucao->numrows > 0) {

    	$iDevolucao                = db_utils::fieldsMemory($rsDevolucao, 0)->ve61_codigo;
    	$sqlerro                   = true;
    	$erro_msg                  = "Encontrada devolução {$iDevolucao} cadastrada para esta retirada!";

    } else {
      if ($sqlerro == false) {

        $oDataAtual = new DBDate(date("Y-m-d",db_getsession("DB_datausu")));
        $sHoraAtual = db_hora();

        if ( ! $sqlerro) {

          $clveicdevolucao->ve61_usuario = db_getsession("DB_id_usuario");
          $clveicdevolucao->ve61_data = $oDataAtual->getDate();
          $clveicdevolucao->ve61_hora = $sHoraAtual;

          $clveicdevolucao->incluir($ve61_codigo);
          if ($clveicdevolucao->erro_status == "0") {
            $sqlerro = true;
            $erro_msg = $clveicdevolucao->erro_msg;
          }
        }
      }
    }
  db_fim_transacao(false);

} else if (isset($retirada)) {
  $campos = "distinct ve61_veicretirada,ve60_codigo,ve60_veiculo,ve01_placa,ve60_datasaida,ve60_horasaida,ve60_medidasaida,ve60_veicmotoristas,ve61_veicmotoristas,z01_nome";
  $result_util = $clveicretirada->sql_record($clveicretirada->sql_query_devol(null,$campos,null,"ve60_codigo=$retirada and ve61_codigo is null"));

  if ($clveicretirada->numrows>0) {
    db_fieldsmemory($result_util,0);
    $ve61_veicmotoristas=$ve60_veicmotoristas;
    $ve61_veicretirada=$ve60_codigo;

    $result = $clveiculos->sql_record($clveiculos->sql_query($ve60_veiculo,"ve01_veictipoabast"));
    db_fieldsmemory($result,0);

    $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast,"ve07_sigla"));
    if ($clveictipoabast->numrows > 0) {
      db_fieldsmemory($result_veictipoabast,0);
    }

  } else {
    echo "<script> ";
    echo "  alert('Este veiculo ja foi devolvido!!'); ";
    echo "  location.href='vei1_veicdevolucao001.php'; ";
    echo "</script>";
    exit;
  }

  $pesq=true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("prototype.js, scripts.js, strings.js, prototype.maskedinput.js");
  db_app::load("estilos.css");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC style='margin-top: 25px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	require_once("forms/db_frmveicdevolucao.php");
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 ?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ve61_veicretirada",true,1,"ve61_veicretirada",true);
</script>
<?
if(isset($incluir)){

  if ($sqlerro && !empty($erro_msg)) {

    $clveicdevolucao->erro_msg = $erro_msg;
    $pesq = true;
  }

	if ($ve61_medidadevol > $ve60_medidasaida && $ve61_datadevol > $ve60_datasaida) {
		if($clveicdevolucao->erro_status=="0"){
			$clveicdevolucao->erro(true,false);
			$db_botao=true;
			echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
			if($clveicdevolucao->erro_campo!=""){
				echo "<script> document.form1.".$clveicdevolucao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
				echo "<script> document.form1.".$clveicdevolucao->erro_campo.".focus();</script>";
			}
		}else{
			$clveicdevolucao->erro(true,true);
		}
	} else {

		$clveicdevolucao->erro_status = "0";
    $medidadevol = str_replace(".","",$ve61_medidadevol);
    $medidasaida = str_replace(".","",$ve60_medidasaida);
		if ($medidadevol < $medidasaida) {
			$clveicdevolucao->erro_msg   = "Medida na devolucao deve ser maior que na retirada!";
      $clveicdevolucao->erro_campo = "ve61_medidadevol";
      $sqlerro = true;
		} elseif ($ve61_datadevol < $ve60_datasaida) {
			$clveicdevolucao->erro_msg   = "Data da devolucao deve ser maior ou igual a da retirada!";
      $clveicdevolucao->erro_campo = "ve61_datadevol";
      $sqlerro = true;
		} else if ($ve61_horadevol < $ve60_horasaida && $ve61_datadevol <= $ve60_datasaida){
  		$clveicdevolucao->erro_msg = $erro_msg;
      $sqlerro = true;
    } else if (isset($iDevolucao) && !empty($iDevolucao)) {
    	$clveicdevolucao->erro_msg = $erro_msg;
		} else {

			if ($sqlerro == true){
			  $clveicdevolucao->erro_msg = "Erro não registrado! Contate suporte!";
			}
      if ($sqlerro && !empty($erro_msg)) {
        $clveicdevolucao->erro_msg = $erro_msg;
      }
		}


    if ($sqlerro == true) {

      $clveicdevolucao->erro(true,false);
	  	$db_botao = true;
      $pesq     = true;
  		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  		if($clveicdevolucao->erro_campo!=""){
  			echo "<script> document.form1.".$clveicdevolucao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
  			echo "<script> document.form1.".$clveicdevolucao->erro_campo.".focus();</script>";
  		}
    } else {
			$clveicdevolucao->erro(true,true);
    }
	}
}
if ($pesq==false){
	echo "<script>js_pesquisaretirada();</script>";
}

?>