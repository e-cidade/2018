<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veicdevolucao_classe.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veictipoabast_classe.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
db_app::import("veiculos.*");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveicdevolucao = new cl_veicdevolucao;
$clveiculos      = new cl_veiculos;
$clveictipoabast = new cl_veictipoabast;

$db_opcao = 22;
$db_botao = false;
$sqlerro = false;
if (isset($alterar)) {
  db_inicio_transacao();
  $clveicdevolucao->alterar($ve61_codigo);

  db_fim_transacao($sqlerro);
} else if (isset($chavepesquisa)) {
  $db_opcao = 2;
  $result = $clveicdevolucao->sql_record($clveicdevolucao->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
  
  $result = $clveiculos->sql_record($clveiculos->sql_query($ve60_veiculo,"ve01_veictipoabast"));
  db_fieldsmemory($result,0);
  
  $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast,"ve07_sigla"));
  if ($clveictipoabast->numrows > 0) {
    db_fieldsmemory($result_veictipoabast,0);
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
<body bgcolor=#CCCCCC style="margin-top: 25px" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include("forms/db_frmveicdevolucao.php");
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
	if ($ve61_medidadevol > $ve60_medidasaida && $ve61_datadevol > $ve60_datasaida) {
    if($clveicdevolucao->erro_status=="0"){
//      $clveicdevolucao->erro(true,false);
      db_msgbox($clveicdevolucao->erro_msg);
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
    } else {
      if ($sqlerro == true){
  			$clveicdevolucao->erro_msg = "Erro no registrado! Contate suporte!";
      }
		}

    if ($sqlerro == true) {
      db_msgbox($clveicdevolucao->erro_msg);
      $db_botao=true;
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
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","ve61_veicretirada",true,1,"ve61_veicretirada",true);
</script>