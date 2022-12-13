<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_arrecant_classe.php");
include ("classes/db_arrecad_classe.php");
include ("classes/db_arrehist_classe.php");
include ("classes/db_cancdebitos_classe.php");
include ("classes/db_cancdebitosprot_classe.php");
include ("classes/db_cancdebitosreg_classe.php");
include ("classes/db_cancdebitosproc_classe.php");
include ("classes/db_cancdebitosprocreg_classe.php");
include ("classes/db_cancdebitosconcarpeculiar_classe.php");
include ("libs/db_sql.php");

$clarrecad            = new cl_arrecad;
$clarrecant           = new cl_arrecant;
$clarrehist           = new cl_arrehist;
$clcancdebitos        = new cl_cancdebitos;
$clcancdebitosprot    = new cl_cancdebitosprot;
$clcancdebitosreg     = new cl_cancdebitosreg;
$clcancdebitosproc    = new cl_cancdebitosproc;
$clcancdebitosprocreg = new cl_cancdebitosprocreg;
$clcancdebitosconcarpeculiar     = new cl_cancdebitosconcarpeculiar;
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);
$db_opcao = 33;
$db_botao = false;

if (isset($processa) && isset($chaves)) {
  $regs = split("#", $chaves);
//  echo "<br><br><br>";print_r($regs);exit;
  $erro_msg = '';
  $sqlerro  = false; 
  $numrows  = count($regs);
  db_inicio_transacao();
  for ($i=0;$i<$numrows;$i++){
    $numpreparrec =  split("-", $regs[$i]);
//		echo "$numpreparrec[1] -- $numpreparrec[2] <br>";
    $clcancdebitosreg->excluir(null," k21_numpre = ".$numpreparrec[1]." and k21_numpar = ".$numpreparrec[2].((int)$numpreparrec[3] == 0?"":" and k21_receit =".$numpreparrec[3]));
    if($clcancdebitosreg->erro_status=="0"){
			$erromsg = "CANCDEBITOSREG : ".$clcancdebitosreg->erro_msg;
      $sqlerro = true;
    }
	}
  // so exclui se nao tiver mais nada na cancdebitosreg
	$sqlProcurareg = " select * from cancdebitosreg where k21_codigo = $k20_codigo ";
	//echo "$sqlProcurareg <br>";
  $clcancdebitos->sql_record($sqlProcurareg);
	if($clcancdebitos->numrows == 0){
	  if($sqlerro == false){
		  $clcancdebitosconcarpeculiar->excluir(null,"k72_cancdebitos = $k20_codigo");
	      $erromsg = "CANCDEBITOSCONCARPECULIAR : ".$clcancdebitosconcarpeculiar->erro_msg;
	      if($clcancdebitosconcarpeculiar->erro_status == 0){
	        $sqlerro = true;
	      }
	  }
	  if($sqlerro == false){
        $rsprot = $clcancdebitosprot->sql_record($clcancdebitosprot->sql_query_file($k20_codigo,"*",null,"")); 
        if($clcancdebitosprot->numrows > 0){
          $clcancdebitosprot->excluir($k20_codigo);
          $erromsg = "CANCDEBITOSPROT : ".$clcancdebitosprot->erro_msg;
          if($clcancdebitosprot->erro_status == 0){
            $sqlerro = true;
          }
        } 
	  }
	  if($sqlerro == false){
        $clcancdebitos->excluir($k20_codigo);
        if($clcancdebitos->erro_status=="0"){
	  	  $erromsg = "CANCDEBITOS : ".$clcancdebitos->erro_msg;
          $sqlerro = true;
        }
	  }

	}   
  if($sqlerro){
    db_msgbox($erromsg);		
  }else{
    db_msgbox("Operação realizada com sucesso !");	
    echo "<script> location.href = 'cai4_retcancdebitos001.php' </script> ";
  }
  //  $sqlerro = true;
  db_fim_transacao($sqlerro);
  
  /********************************************************************************************************************************/
  
}

if (isset ($chavepesquisa)){
  $db_opcao = 1;
  $db_botao = true;
  $campos   = " cancdebitos.k20_cancdebitostipo,cancdebitos.k20_codigo, cancdebitos.k20_usuario, cancdebitos.k20_data, cancdebitos.k20_hora, nome ";
  $result   = $clcancdebitos->sql_record($clcancdebitos->sql_pendentes($campos, "", "k21_codigo =".$chavepesquisa." and k20_instit = ".db_getsession("DB_instit")));
  @ db_fieldsmemory($result, 0);
    // cancelamentos
  if($k20_cancdebitostipo==1){
  	$cancdebitostipo = "Normal";
	
  }else{
  	$cancdebitostipo= "Renuncia";
	$sqlPeculiar = "select k72_cancdebitos,c58_sequencial as tipo,c58_descr as caracteristica 
	                from cancdebitosconcarpeculiar 
	                inner join concarpeculiar on k72_concarpeculiar = c58_sequencial 
	                where k72_cancdebitos = $k20_codigo";
	$rsPeculiar     = pg_query($sqlPeculiar);
	$linhasPeculiar =  pg_num_rows($rsPeculiar);
	if($linhasPeculiar > 0 ){
	  db_fieldsmemory($rsPeculiar,0);
	  $c58_sequencial = $tipo;
      $c58_descr      = $caracteristica;
	  
	}
  }
	$tipoDebito = $k20_cancdebitostipo;
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" valign="top" bgcolor="#CCCCCC">
<br><br>
<center>
<?
include ("forms/db_frmcanccancdeb.php");
?>
</center>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (!isset ($chavepesquisa)) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>