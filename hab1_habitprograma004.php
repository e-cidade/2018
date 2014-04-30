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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_habitprograma_classe.php");
require_once("classes/db_habitprogramaconcedente_classe.php");
require_once("classes/db_habitparametro_classe.php");

$clHabitPrograma           = new cl_habitprograma();
$clHabitParametro          = new cl_habitparametro();
$clHabitProgramaConcedente = new cl_habitprogramaconcedente();

db_postmemory($_POST);

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {
	
  $lSqlErro = false;
  
  db_inicio_transacao();
  
  $clHabitPrograma->incluir($ht01_sequencial);
  
  if($clHabitPrograma->erro_status==0){
    $lSqlErro=true;
  } 
  
  $sErroMsg        = $clHabitPrograma->erro_msg;
  $ht01_sequencial = $clHabitPrograma->ht01_sequencial;
  
  if ( trim($ht19_numcgm) != '' ) {
  	
  	$clHabitProgramaConcedente->ht19_habitprograma = $clHabitPrograma->ht01_sequencial;
  	$clHabitProgramaConcedente->ht19_numcgm        = $ht19_numcgm;
  	$clHabitProgramaConcedente->incluir(null);
  	
  	if ( $clHabitProgramaConcedente->erro_status == "0" ) {
  		$lSqlErro = true;
  	}
  	
  	$sErroMsg = $clHabitProgramaConcedente->erro_msg;
  	
  }
  
  db_fim_transacao($lSqlErro);

  
  $db_opcao = 1;
  $db_botao = true;
} else {
	
	$rsParametro = $clHabitParametro->sql_record($clHabitParametro->sql_query(db_getsession('DB_anousu')));
	
	if ( $clHabitParametro->numrows > 0 ) {
		
		$oParametro = db_utils::fieldsMemory($rsParametro,0);
		
		$ht01_diapadraopagamento     = $oParametro->ht16_diaspadraopagamento;
		$ht01_receitapadraopagamento = $oParametro->ht16_receitapadrao;
		$k02_descr                   = $oParametro->k02_descr;
		$ht01_qtdparcpagamento       = $oParametro->ht16_qtdparcelaspagamento;
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
<table align="center">
  <tr> 
    <td> 
			<?
	   		include("forms/db_frmhabitprograma.php");
			?>
	  </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($lSqlErro==true){
    db_msgbox($sErroMsg);
    if($clHabitPrograma->erro_campo!=""){
      echo "<script> document.form1.".$clHabitPrograma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clHabitPrograma->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($sErroMsg);
   db_redireciona("hab1_habitprograma005.php?liberaaba=true&chavepesquisa=$ht01_sequencial");
  }
}
?>