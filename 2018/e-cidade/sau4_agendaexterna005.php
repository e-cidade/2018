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
include("libs/db_utils.php");
include("classes/db_sau_agendaexterna_ext_classe.php");
$clsau_agendaexterna  = new cl_sau_agendaexterna_ext;
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
<title>Agendar Prestadoras</title>
</head>
<body>
<?
db_postmemory($HTTP_POST_VARS);
$ano           = substr( $pdia, 6, 4 );
$mes           = substr( $pdia, 3, 2 );
$dia           = substr( $pdia, 0, 2 );
$data = $ano."-".$mes."-".$dia;
$tipoagenda= $s118_c_tipoagenda;
if($tipoagenda=="C"){
 $where = "s118_d_marcada='$data' and s118_c_tipoagenda='C' ";
}else{
 $where = "s118_d_marcada='$data' and s118_c_tipoagenda='E'";
}

   $str_query            = $clsau_agendaexterna->sql_query_ext(null, "*", "","$where");
  $result_agendaexterna= $clsau_agendaexterna->sql_record( $str_query );
 ?>
  <table border="0" cellspacing="2px" width="100%" height="" cellpadding="1px" bgcolor="#cccccc">
			<tr class='cabec'>
				<td class='cabec' align="center">Paciente</td>
				<td class='cabec' align="center">Nome</td>
				<td class='cabec' align="center">RG</td>
				<td class='cabec' align="center">CPF</td>
  			<td class='cabec' align="center">Dia</td>
  			<td class='cabec' align="center">Hora</td>
  			<td class='cabec' align="center">Opções</td>
			</tr>
<?	
	for( $x=0; $x < $clsau_agendaexterna->numrows; $x++ ){
		$obj_agendaexterna  = db_utils::fieldsMemory( $result_agendaexterna, $x );
    	
		?>
			
			<tr>				
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_agendaexterna->z01_i_numcgs ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_agendaexterna->z01_v_nome ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_agendaexterna->z01_v_ident?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_agendaexterna->z01_v_cgccpf?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=db_formatar($obj_agendaexterna->s118_d_marcada,'d')?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_agendaexterna->s118_c_horamarcada?></td>
				<td class='corpo' nowrap>
					<a title='Prestadora conteúdo da linha'  href='#' onclick="js_lancar(<?=$obj_agendaexterna->s118_i_codigo?>);">&nbsp;P&nbsp;</a>
					&nbsp;&nbsp;
					<a title='Veículo conteúdo da linha' href='#' onclick="js_veiculo(<?=$obj_agendaexterna->z01_i_numcgs ?>,'<?=$obj_agendaexterna->z01_v_nome ?>');">&nbsp;V&nbsp;</a>
					&nbsp;&nbsp;					
				</td>
			</tr>
		<?
	}//fim for
?>
</table>  

</body>
</html>
<script>
function js_lancar(s118_i_codigo){
      iTop = ( screen.availHeight-600 ) / 2;
  		iLeft = ( screen.availWidth-800 ) / 2;
  		x  = 'sau4_agendaexterna006.php';
  		x += '?s118_i_codigo='+s118_i_codigo;
	  	js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Indique a Prestadora',true, iTop, iLeft, 800, 400);
}

function js_veiculo(z01_i_numcgs,z01_v_nome){
        iTop = ( screen.availHeight-600 ) / 2;
  		iLeft = ( screen.availWidth-800 ) / 2;
  		x  = 'sau4_agendaexterna004.php';
  		x += '?z01_i_numcgs='+z01_i_numcgs;
  		x += '&z01_v_nome='+z01_v_nome;
  		x += '&s124_i_numcgs='+z01_i_numcgs;
  		x += '&chavepesquisacgs='+z01_i_numcgs;
	  	js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Indique o Veiculo',true, iTop, iLeft, 800, 400);
}


</script>