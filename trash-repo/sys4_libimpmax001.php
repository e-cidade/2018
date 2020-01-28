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

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

if ((isset($incluir))||(isset($excluir))) {
	if (isset($depto)){
		sort($depto);
		$separador = " ";
		$listaDepartamentos = "(";
		for ($i=0;$i<sizeof($depto);$i++){
			$listaDepartamentos .= $depto[$i];
			if ($i==(sizeof($depto)-1)){
				$separador = " ";
			}else{
				$separador = ",";
			}
			$listaDepartamentos .= $separador;
		}
		$listaDepartamentos .= ")";
		$sql = "
			select distinct id_usuario
			from db_depusu
			where coddepto in $listaDepartamentos
			order by id_usuario
		";
		$result = pg_exec($sql);
		$num = pg_numrows($result);
		if ($num!=0){
			if (isset($usuarios)){
				sort($usuarios);
			}else{
				$usuarios[0] = pg_result($result,0,"id_usuario");
			}
			for ($i=0;$i<$num;$i++){
				if (!in_array(pg_result($result,$i,"id_usuario"),$usuarios)){
					array_push($usuarios,pg_result($result,$i,"id_usuario"));
				}
			}
		}
	}
	if (isset($usuarios)){
		sort($usuarios);
		pg_exec("begin");
		for ($i=0;$i<sizeof($usuarios);$i++){
			for($a=0;$a<sizeof($impressoras);$a++){
				$sql = "
					select d51_usuario, d51_impres
					from perimp
					where d51_usuario = $usuarios[$i]
					and d51_impres = $impressoras[$a]
				";
				$result = pg_exec($sql);
				$num = pg_numrows($result);
				if ($num == 0){
					if (isset($incluir)){
						$sql2 = "
							insert into perimp values ($usuarios[$i],$impressoras[$a])
						";
						pg_exec($sql2);
					}
				}else if (($num != 0)&&(isset($excluir))){
					$sql2 = "
						delete from perimp where d51_usuario =$usuarios[$i]
						and d51_impres = $impressoras[$a]
					";
					pg_exec($sql2);
				}
			}
		}
		pg_exec("end");
	}else if ((!isset($usuarios))&&(isset($excluir))){
		pg_exec("begin");
		if (sizeof($impressoras)!=0){
			for ($i=0;$i<sizeof($impressoras);$i++){
				$sql2 = "
					delete from perimp where d51_impres = $impressoras[$i]
				";
				pg_exec($sql2);
			}
		}else{
			$sql2 = "
				delete from perimp where d51_impres = $impressoras
			";
			pg_exec($sql2);
		}
		pg_exec("end");
	}
	if (isset($excluir)){
		db_msgbox("Impresssoras por usários excluidas com sucesso.");
	}else{
		db_msgbox("Impresssoras por usários incluidas com sucesso.");
	}
	db_redireciona();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<? 
	include("forms/db_frmlibimpmax001.php"); 
	?>
	</td>
  </tr>
</table>
	<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>

</body>
</html>