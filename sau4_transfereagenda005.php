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
include("libs/db_utils.php");
include("libs/db_jsplibwebseller.php");

include("classes/db_agendamentos_ext_classe.php");
include("classes/db_undmedhorario_ext_classe.php");

include("dbforms/db_funcoes.php");

?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">

<style>
a:hover {
  color:blue;
}
a:visited {
  color: black;
  font-weight: bold;
}
a:active {
  color: black;
  font-weight: bold;
}
.cabec {
  text-align: center;
  font-size: 11;
  color: darkblue;
  background-color:#aacccc ;
  border:1px solid $FFFFFF;
  font-weight: bold;
}
.corpo {
  font-size: 9;
  color: black;
  background-color:#ccddcc;
}

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">

<?
db_postmemory($HTTP_POST_VARS);


if( isset( $limpar )){
	if( session_is_registered("arr_transferidos") ){
		db_destroysession("arr_transferidos");
	}
	exit;
}

$sd02_i_codigo = db_getsession("DB_coddepto");
$ano           = substr( $sd23_d_consulta2, 6, 4 );
$mes           = substr( $sd23_d_consulta2, 3, 2 );
$dia           = substr( $sd23_d_consulta2, 0, 2 );

$clagendamentos  = new cl_agendamentos_ext;

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1"  >

<table border="0" cellspacing="2px" width="100%" cellpadding="1px" bgcolor="#cccccc">
	<tr class='cabec'>
		<td class='cabec' align="center">Ficha</td>
		<td class='cabec' align="center">De Horário</td>
		<td class='cabec' align="center">Para Horário</td>
		<td class='cabec' align="center">Código</td>
		<td class='cabec' align="center">Para Profissional</td>
		<td class='cabec' align="center">Código</td>
		<td class='cabec' align="center">Para Paciente</td>
	</tr>
<?

$res_totalficha = $clagendamentos->sql_record("select fc_totalagendado('$ano/$mes/$dia',$sd30_i_codigo2);");
$obj_totalficha = db_utils::fieldsMemory($res_totalficha, 0 );
$arr_totalficha = explode(",", $obj_totalficha->fc_totalagendado );

if( $arr_totalficha[6] > 0 ){
	db_inicio_transacao();
	$clagendamentos->sd23_i_undmedhor= $sd30_i_codigo2;
	$clagendamentos->sd23_d_consulta = $ano."/".$mes."/".$dia;
	$clagendamentos->sd23_i_ficha    = $sd23_i_ficha2;
    $clagendamentos->sd23_i_codigo   = $sd23_i_codigo;
    $clagendamentos->alterar( $sd23_i_codigo );
	
	db_fim_transacao();
	
	if($clagendamentos->erro_status=="0"){
		$clagendamentos->erro(true,false);
		$db_botao=true;
	}else{
		if( !session_is_registered("arr_transferidos") ){
			session_register("arr_transferidos");
			db_putsession("arr_transferidos", array(array( 6 )) );
		}
			$x = db_getsession("arr_transferidos");
			$x[sizeof($x)-1][0] = $sd23_i_ficha2; 
			$x[sizeof($x)-1][1] = $sd23_c_hora; 
			$x[sizeof($x)-1][2] = $sd23_c_hora2; 
			$x[sizeof($x)-1][3] = $sd03_i_codigo2;
			$x[sizeof($x)-1][4] = $z01_nome2;
			$x[sizeof($x)-1][5] = $sd23_i_numcgs2; 
			$x[sizeof($x)-1][6] = $z01_v_nome2;
			$x[] = array( 6 );
			db_putsession("arr_transferidos", $x);
			for( $xx = 0; $xx < sizeof($x)-1; $xx++){
				?>
				<tr>
					<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$x[$xx][0]?></td>
					<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$x[$xx][1]?></td>
					<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$x[$xx][2]?></td>
					<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$x[$xx][3]?></td>
					<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$x[$xx][4]?></td>
					<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$x[$xx][5]?></td>
					<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$x[$xx][6]?></td>
				</tr>
				<?				
			}
			echo "<script>
				 for (i=0;i<parent.document.form1.elements.length;i++)
				      if(parent.document.form1.elements[i].type == \"text\" || parent.document.form1.elements[i].type == \"hidden\" )
				         parent.document.form1.elements[i].value=\"\"
				 parent.document.form1.sd02_i_codigo.value = ".db_getsession("DB_coddepto")."
				  </script> 
			";
			
	}		
}else{
	db_msgbox("Não existe mais fichas disponíveis para o Profissional: $z01_nome2");
}
?>
</table>
</body>
</html>