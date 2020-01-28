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
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veicmanut_classe.php");
include("classes/db_veicmanutitem_classe.php");
include("classes/db_veicmanutoficina_classe.php");
include("classes/db_veicmanutretirada_classe.php");
include("classes/db_veicretirada_classe.php");
db_app::import("veiculos.*");
$clveiculos = new cl_veiculos;
$clveicmanut = new cl_veicmanut;
$clveicmanutoficina = new cl_veicmanutoficina;
$clveicmanutretirada = new cl_veicmanutretirada;
$clveicretirada      = new cl_veicretirada;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;

$sqlerro=false;

if (isset($incluir)) {
  
  $sHora = db_hora();
  /*
   -- Codigo Comentado pois foi efetuada toda validacao necessaria na interface (em Javascript)
  $result_ultimamedida = $clveiculos->sql_record($clveiculos->sql_query_ultimamedida($ve62_veiculos, $ve62_dtmanut, $sHora));
  
  if ($clveiculos->numrows>0) {
    $oRetirada = db_utils::fieldsMemory($result_ultimamedida,0);
    if ($oRetirada->ultimamedida > $ve62_medida) {
      $sqlerro  = true;
      $erro_msg = "Medida ($ve62_medida) menor que última medida ($oRetirada->ultimamedida) ";
    }
  }
  
  $result_proximamedida = $clveiculos->sql_record($clveiculos->sql_query_proximamedida(@$ve62_veiculos, @$ve62_dtmanut, $sHora));
  if ($clveiculos->numrows>0) {
    $oRetirada = db_utils::fieldsMemory($result_proximamedida,0);
    if ($oRetirada->proximamedida < $ve62_medida) {
      $sqlerro  = true;
      $erro_msg = "Medida ($ve62_medida) maior que última medida ($oRetirada->proximamedida) ";
    }
  }*/
  
  if ($sqlerro==false) {
    db_inicio_transacao();
    $clveicmanut->ve62_usuario = db_getsession("DB_id_usuario");
    $clveicmanut->ve62_hora    = $sHora;
    $clveicmanut->ve62_data    = date("Y-m-d",db_getsession("DB_datausu"));
    $clveicmanut->incluir(null);
    if ($clveicmanut->erro_status==0) {
      $sqlerro  = true;
      $erro_msg = $clveicmanut->erro_msg;
    }
    $erro_msg = $clveicmanut->erro_msg;
    if ($sqlerro==false) {
      if (isset($ve66_veiccadoficinas)&&$ve66_veiccadoficinas!="") {
        $clveicmanutoficina->ve66_veicmanut=$clveicmanut->ve62_codigo;
        $clveicmanutoficina->incluir(null);
        if ($clveicmanutoficina->erro_status=="0") {
          $erro_msg=$clveicmanutoficina->erro_msg;
          $sqlerro=true;
        }
      }
    }
    if ($sqlerro==false) {
      if (isset($ve65_veicretirada)&&$ve65_veicretirada!="") {
        $clveicmanutretirada->ve65_veicmanut=$clveicmanut->ve62_codigo;
        $clveicmanutretirada->incluir(null);
        if ($clveicmanutretirada->erro_status=="0") {
          $erro_msg=$clveicmanutretirada->erro_msg;
          $sqlerro=true;
        }
      }
    }
    db_fim_transacao($sqlerro);
  }
  $ve62_codigo= $clveicmanut->ve62_codigo;
  $db_opcao = 1;
  $db_botao = true;
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
<body bgcolor="#CCCCCC" style='margin-right: 25px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include("forms/db_frmveicmanut.php");
	?>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clveicmanut->erro_campo!=""){
      echo "<script> document.form1.".$clveicmanut->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicmanut->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("vei1_veicmanut005.php?liberaaba=true&chavepesquisa=$ve62_codigo");
  }
}
?>