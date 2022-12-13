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
include("classes/db_ausencias_ext_classe.php");

include("dbforms/db_funcoes.php");

?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js">
</script>
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
  font-size: 10;
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

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >


<?


db_postmemory($HTTP_POST_VARS);

$sd02_i_codigo = db_getsession("DB_coddepto");
$ano           = substr( $sd23_d_consulta, 6, 4 );
$mes           = substr( $sd23_d_consulta, 3, 2 );
$dia           = substr( $sd23_d_consulta, 0, 2 );

$clagendamentos  = new cl_agendamentos_ext;
$clundmedhorario = new cl_undmedhorario_ext;
$clausencias     = new cl_ausencias_ext;

$str_query            = $clundmedhorario->sql_calendario2($ano,$mes,$dia, "sd27_i_rhcbo = $sd27_i_rhcbo", $chave_diasemana,"and sd02_c_centralagenda in('N','S')");
$result_undmedhorario = $clundmedhorario->sql_record( $str_query ); 

if( $clundmedhorario->numrows == 0  ){
	echo "<script>
			alert('Nenhum registro encontrado.');
			parent.document.form1.sd23_d_consulta.value='';
			parent.document.form1.diasemana.value='';
			parent.document.form1.sd23_d_consulta.focus();
		</script>";
	exit;
}


?>
<table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
			<tr class='cabec'>
				<td class='cabec' align="center">Profissionais</td>
				<td class='cabec' align="center">Ficha</td>
				<td class='cabec' align="center">Reser</td>
				<td class='cabec' align="center">Saldo</td>
  			<td class='cabec' align="center">UPS</td>
			</tr>
<?	
	$unidade = 0;
	for( $xHora=0; $xHora < $clundmedhorario->numrows; $xHora++ ){
		$obj_undmedhorario  = db_utils::fieldsMemory( $result_undmedhorario, $xHora );
    
		$data = "'$dia/$mes/$ano'";
		if($obj_undmedhorario->sd30_i_fichas - $obj_undmedhorario->sd30_i_reservas - $obj_undmedhorario->total_agendado > 0){
		?>
			
			<tr>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="left">
					<a href="#" onclick="js_profissional(<?=$obj_undmedhorario->sd27_i_codigo ?>,<?=$chave_diasemana ?>, <?=$data ?>, <?=$obj_undmedhorario->sd03_i_codigo ?>, '<?=$obj_undmedhorario->z01_nome ?>', <?=$obj_undmedhorario->sd02_i_codigo ?>, '<?=$obj_undmedhorario->descrdepto ?>');return false"><?=$obj_undmedhorario->z01_nome ?></a>
				</td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_undmedhorario->sd30_i_fichas ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_undmedhorario->sd30_i_reservas ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_undmedhorario->sd30_i_fichas - $obj_undmedhorario->sd30_i_reservas - $obj_undmedhorario->total_agendado ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo'><?=$obj_undmedhorario->sd02_i_codigo." - ".substr($obj_undmedhorario->descrdepto,0,32) ?></td>

			</tr>
		<?
    }
	}//fim for
?>
</table>
</body>
</html>

<script>
	parent.document.form1.sd27_i_codigo.value = ""; 
	parent.document.form1.sd03_i_codigo.value = ""; 
	parent.document.form1.z01_nome.value      = ""; 
	parent.document.form1.sd02_i_codigo.value = "";
	parent.document.form1.descrdepto.value    = "";

function js_profissional(sd27_i_codigo, diasemana, sd23_d_consulta, sd03_i_codigo, z01_nome, sd02_i_codigo, descrdepto){
	x  = 'sau4_agendamento002.php';
	x += '?sd27_i_codigo='+sd27_i_codigo;
	x += '&chave_diasemana='+diasemana;
  	x += '&sd23_d_consulta='+sd23_d_consulta;

	parent.document.form1.sd27_i_codigo.value = sd27_i_codigo; 
	parent.document.form1.sd03_i_codigo.value = sd03_i_codigo; 
	parent.document.form1.z01_nome.value      = z01_nome; 
	parent.document.form1.sd02_i_codigo.value = sd02_i_codigo;
	parent.document.form1.descrdepto.value    = descrdepto;
  	location.href = x;
}

</script>