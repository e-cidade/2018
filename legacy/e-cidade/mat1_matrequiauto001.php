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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matrequi_classe.php");
include("classes/db_matrequiitem_classe.php");
include("classes/db_atendrequi_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_almox_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_matestoqueini_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
//if (substr($DB_BASE,0,5) != "ontem") {
//	  die("rotina indisponivel");
//}
$clmatrequi = new cl_matrequi;
$clmatrequiitem = new cl_matrequiitem;
$clatendrequi = new cl_atendrequi;
$cldb_depart = new cl_db_depart;
$cldb_almox = new cl_db_almox;
$cldb_usuarios = new cl_db_usuarios;
$clmatestoqueini = new cl_matestoqueini;
if (!isset($db_opcao)){
  $db_opcao = 1;
}
$opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro=false;

  $coddepto = db_getsession("DB_coddepto");
  $sqlalmox = $cldb_almox->sql_query_file(null, "*", null, "m91_depto=$coddepto");
  $resalmox = $cldb_almox->sql_record($sqlalmox);
  if($cldb_almox->numrows>0) {
    db_fieldsmemory($resalmox, 0);
  } else {
    $sqlerro=true;
    $erro_msg="Departamento $coddepto não é um Almoxarifado!";
  }

  if($sqlerro==false) {
    $clmatrequi->m40_auto  = 't';
    $clmatrequi->m40_depto = $departamento;
    $clmatrequi->m40_login = $login;
  	$clmatrequi->m40_almox = $m91_codigo;
  
    $clmatrequi->incluir($m40_codigo);
    $codigorequi=$clmatrequi->m40_codigo;
    if ($clmatrequi->erro_status==0){
      $sqlerro=true;
      $erro_msg=$clmatrequi->erro_msg;
    }
  }

  if($sqlerro==false) {
    $clatendrequi->m42_login=$login;
    $clatendrequi->m42_depto=$departamento;
    $clatendrequi->m42_data=date('Y-m-d',db_getsession("DB_datausu"));
    $clatendrequi->m42_hora=db_hora();
    $clatendrequi->incluir(null);
    $codigoatend=$clatendrequi->m42_codigo;
    if ($clatendrequi->erro_status==0){
      $sqlerro=true;
      $erro_msg=$clatendrequi->erro_msg;
    }
  }
  /*
  if($sqlerro == false){
   $clmatestoqueini->m80_login          = $login;
    $clmatestoqueini->m80_data           = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatestoqueini->m80_hora           = db_hora();
    $clmatestoqueini->m80_obs            = "$m40_obs";
    $clmatestoqueini->m80_codtipo        = "17";
    $clmatestoqueini->m80_coddepto       = $departamento;
    $clmatestoqueini->incluir(@$m80_codigo);
    if($clmatestoqueini->erro_status==0){
      $sqlerro=true;
      $erro_msg = $clmatestoqueini->erro_msg;
    }
    $m80_codigo = $clmatestoqueini->m80_codigo;
  }*/
  db_fim_transacao($sqlerro);
}else{
  if (isset($m40_codigo)&&$m40_codigo!=""){
    $result_matrequi=$clmatrequi->sql_record($clmatrequi->sql_query($m40_codigo,"m40_data,m40_depto as departamento,db_depart.descrdepto,m40_hora,m40_obs,m40_login as login,nome"));
    if ($clmatrequi->numrows>0){
      db_fieldsmemory($result_matrequi,0);
    }
  }else{
    $m40_data_dia=date('d',db_getsession("DB_datausu"));
    $m40_data_mes=date('m',db_getsession("DB_datausu"));
    $m40_data_ano=date('Y',db_getsession("DB_datausu"));
    /*
    $m40_depto=db_getsession("DB_coddepto");
    $result_depto=$cldb_depart->sql_record($cldb_depart->sql_query_file($m40_depto,'descrdepto'));
    if ($cldb_depart->numrows!=0){
      db_fieldsmemory($result_depto,0);
    }
    $m40_login=db_getsession("DB_id_usuario");
    $result_login=$cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($m40_login,'nome'));
    if ($cldb_usuarios->numrows!=0){
      db_fieldsmemory($result_login,0);
    }*/
    $m40_hora=db_hora();
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
    <center>
	<?
	include("forms/db_frmmatrequiauto.php");
	?>
    </center>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    //$clmatrequi->erro(true,false);
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($clmatrequi->erro_campo!=""){
      echo "<script> document.form1.".$clmatrequi->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatrequi->erro_campo.".focus();</script>";
    };
  }else{
    //$clmatrequi->erro(true,true);
    //db_msgbox($erro_msg);
    echo "<script>
               alert('Inclusão Efetuada com Sucesso!!Requisição:$codigorequi - Atendimento:$codigoatend');
               parent.iframe_g2.location.href='mat1_matrequiitemauto001.php?m40_codigo=".@$codigorequi."&m42_codigo=".@$codigoatend."&m80_codigo=".@$m80_codigo."';\n
               parent.iframe_g1.location.href='mat1_matrequiauto001.php?m40_codigo=".@$codigorequi."&m42_codigo=".@$codigoatend."&db_opcao=3&m80_codigo=".@$m80_codigo."';\n
               parent.mo_camada('g2');
               parent.document.formaba.g2.disabled = false;\n
	 </script>";
  };
};
?>
