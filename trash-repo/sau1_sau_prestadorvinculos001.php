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
include("libs/db_usuariosonline.php");

include("classes/db_sau_prestadorvinculos_classe.php");
include("classes/db_sau_agendaexames_ext_classe.php");

include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clsau_prestadorvinculos = new cl_sau_prestadorvinculos;
$db_opcao = 1;
$db_botao = true;

if(isset($opcao)){
	$db_botao1 = true;
	$db_opcao = $opcao=="alterar"?2:3;	
	$result = $clsau_prestadorvinculos->sql_record($clsau_prestadorvinculos->sql_query($s111_i_codigo));
	if( $clsau_prestadorvinculos->numrows > 0 ){
		db_fieldsmemory($result,0);		
	}
}
if(isset($incluir)){
  db_inicio_transacao();  
  $clsau_prestadorvinculos->incluir(null);
  db_fim_transacao();
}else if(isset($alterar)){
	$db_opcao = 2;
	$booRetorno = true;
	if( $s111_c_situacao == "I"){
		$result_agendaexames = pg_query( cl_sau_agendaexames_ext::sql_query_ext(null,"s111_i_codigo",null,
					"s111_i_codigo = $s111_i_codigo and s113_d_exame >=
					'".date("Y",db_getsession("DB_datausu")).'/'.date("m",db_getsession("DB_datausu")).'/'.date("d",db_getsession("DB_datausu"))."' 
					") );
		if( pg_num_rows($result_agendaexames) > 0 ){
			$clsau_prestadorvinculos->erro_status = "0";
			$clsau_prestadorvinculos->erro_msg    = "Exame possui agendamentos posterior, não podendo alterar situação.";
			$clsau_prestadorvinculos->erro_campo  = "s111_c_situacao";
			$booRetorno = false;
		}
	}
	if( $booRetorno ){
		db_inicio_transacao();
		$clsau_prestadorvinculos->alterar($s111_i_codigo);
		db_fim_transacao();
	}
}else if(isset($excluir)){
	$db_opcao = 3;
	db_inicio_transacao();
	$clsau_prestadorvinculos->excluir($s111_i_codigo);
	db_fim_transacao();
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <fieldset style="width:95%"><legend><b>Inclusão de Vínculos</b></legend>
	<? include("forms/db_frmsau_prestadorvinculos.php");?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","s111_i_exame",true,1,"s111_i_exame",true);
</script>
<?
if(isset($incluir) || isset($alterar)){
  if($clsau_prestadorvinculos->erro_status=="0"){
    $clsau_prestadorvinculos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsau_prestadorvinculos->erro_campo!=""){
      echo "<script> document.form1.".$clsau_prestadorvinculos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsau_prestadorvinculos->erro_campo.".focus();</script>";
    }
  }else{
    $clsau_prestadorvinculos->erro(true,false);
	db_redireciona("sau1_sau_prestadorvinculos001.php?op=$op&z01_nome=$z01_nome&s111_i_prestador=$s111_i_prestador");
  }
}

if(isset($excluir)){
	$clsau_prestadorvinculos->erro(true,false);
	db_redireciona("sau1_sau_prestadorvinculos001.php?op=$op&z01_nome=$z01_nome&s111_i_prestador=$s111_i_prestador");
}
?>