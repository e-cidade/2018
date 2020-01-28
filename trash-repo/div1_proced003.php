<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_proced_classe.php");
include("classes/db_tipoproced_classe.php");
include("classes/db_procedenciaagrupa_classe.php");
include("classes/db_procedarretipo_classe.php");
include("classes/db_arretipo_classe.php");
include("classes/db_recparproc_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clproced					 = new cl_proced;
$cltipoproced      = new cl_tipoproced;
$clrecparproc			 = new cl_recparproc;
$clprocedarretipo  = new cl_procedarretipo;
$clarretipo        = new cl_arretipo;
$clprocedAgrupa    = new cl_procedenciaagrupa;

$db_botao = false;
$db_opcao = 33;
$db_opcaoagrupa = 33;

if(isset($excluir)){
  db_inicio_transacao();
  $sqlerro=false;
  $db_opcao = 3;
  $db_opcaoagrupa = 3;   
	$rsVerificaArretipo = $rsArretipo = $clprocedarretipo->sql_record($clprocedarretipo->sql_query_file(null,"*",null,"v06_proced = {$v03_codigo}"));
  
	if($clprocedarretipo->numrows > 0){
    $oArretipo = db_utils::fieldsMemory($rsVerificaArretipo,0);
    $clprocedarretipo->excluir(null,"v06_proced = {$oArretipo->v06_proced}");
    if($clprocedarretipo->erro_status == 0){
      $sqlerro  = true;
      $erro_msg = $clprocedarretipo->erro_msg;
    }
  }
	
	$clrecparproc->excluir($v03_codigo);
  if ($clrecparproc->erro_status==0){  
    $sqlerro=true;
    $erro_msg =$clrecparproc->erro_msg; 
  }  
  
  if (!$sqlerro) {
    
    $clprocedAgrupa->excluir(null, "v24_proced = {$v03_codigo}");
    if ($clprocedAgrupa->erro_status == 0) {
      
      $sqlerro  = true;
      $erro_msg = $clprocedAgrupa->erro_msg;
      
    }
  }
  
	if ($sqlerro==false){
    $clproced->excluir($v03_codigo);  
    if ($clproced->erro_status==0){
      $erro_msg =$clproced->erro_msg;
      $sqlerro=true;
    }  
  }
  
	db_fim_transacao($sqlerro);  

}else if(isset($chavepesquisa)){
   
	 $db_opcao = 3;
   $result	 = $clproced->sql_record($clproced->sql_query($chavepesquisa)); 
	 db_fieldsmemory($result,0);
   
	 $result_recparproc = $clrecparproc->sql_record($clrecparproc->sql_query(null,"receita,k02_descr as descr_2",null,"recparproc.v03_codigo=$chavepesquisa")); 
   if ($clrecparproc->numrows!=0){ 
     db_fieldsmemory($result_recparproc,0);
   }
   
   $rsArretipo = $clprocedarretipo->sql_record($clprocedarretipo->sql_query(null,"k00_descr as v06_arretipo",null,"v06_proced = {$chavepesquisa}"));
   if($clprocedarretipo->numrows > 0){
     db_fieldsmemory($rsArretipo,0);
   }
  $sSqlProcedenciaAgrupar = $clprocedAgrupa->sql_query_agrupa(null,
                                                              "v24_procedagrupa, agrupa.v03_descr as v24_procedagrupadescr", 
                                                              null,
                                                              "v24_proced = {$chavepesquisa}"
                                                              );
  $rsProcedenciaAgrupa = $clprocedAgrupa->sql_record($sSqlProcedenciaAgrupar);
  if ($clprocedAgrupa->numrows == 1) {
    db_fieldsmemory($rsProcedenciaAgrupa, 0);
  }
	 $db_botao = true;
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
<body bgcolor=#CCCCCC onLoad="a=1" >

	<?
	include("forms/db_frmproced.php");
	?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($clproced->erro_status=="0"||$sqlerro==true){
    //$clproced->erro(true,false);
    db_msgbox($erro_msg);
  }else{
    $clproced->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>