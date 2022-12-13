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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$oDaocgs_und = db_utils::getdao('cgs_und');
$clrotulo = new rotulocampo;
$oDaocgs_und->rotulo->label("z01_i_cgsund");
$oDaocgs_und->rotulo->label("z01_v_nome");
$oDaocgs_und->rotulo->label("z01_v_cgccpf");
$oDaocgs_und->rotulo->label("z01_v_ident");


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
    <form name="form2" method="post" action="" >
   <table width="100%" border="0" align="center" cellspacing="0">
    <tr>
     <td>
      <b>CGS:</b>&nbsp;&nbsp; <?db_input('z01_i_cgsund',6,$Iz01_i_cgsund,true,'text',4,"","chave_z01_i_cgsund");?>
      <b>Nasc:</b> <?db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,'text',4,"",'chave_z01_d_nasc');?>
      <b>Identidade:</b>&nbsp;&nbsp; <?db_input('z01_v_ident',15,$Iz01_v_ident,true,'text',1,"","chave_z01_v_ident");?><br>
     </td>
    </tr>
    <tr>
     <td>
      <b>Nome:</b> <?db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',4,"onblur='js_nome(this)'",'chave_z01_v_nome');?>
     </td>
    </tr>
    <tr>
     <td colspan="3" align="center">
      <input name="pesquisar2" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="button"  id="limpar" value="Limpar" onClick="js_limpar();">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_fechar('<?=@$campoFoco?>');">
     </td>
    </tr>
   </table>
    </form>
  </td>
 </tr>
 <tr>
 	<td align="center" valign="top">
	<?
	if(!isset($pesquisa_chave)){

    $campos = 
      " z01_i_cgsund, 
        z01_v_nome,
        z01_d_nasc,   
        z01_v_sexo,
        z01_v_ender,
        z01_i_numero,
        z01_v_bairro,
        z01_v_ident,
        tipo ";

    if(!isset($chave_tf01_i_codigo) || empty($chave_tf01_i_codigo)) { // código do pedido
      $chave_tf01_i_codigo = -1;
    }

    $sPacientesAnulados = ' tf13_i_anulado = 2 ';

	  if(isset($chave_z01_i_cgsund) && (trim($chave_z01_i_cgsund) != '') ) {

	    $sql = $oDaocgs_und->sql_query_cgs_beneficiadosajudacusto(null, $campos, 'z01_i_cgsund',
                                                                "z01_i_cgsund = $chave_z01_i_cgsund and ".
                                                                "tf01_i_codigo = $chave_tf01_i_codigo and ".
                                                                $sPacientesAnulados);

    } else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome) != '') ) {

	  	$sql = $oDaocgs_und->sql_query_cgs_beneficiadosajudacusto(null, $campos, 'z01_v_nome',
                                                                " z01_v_nome like '$chave_z01_v_nome%' and ".
                                                                "tf01_i_codigo = $chave_tf01_i_codigo and".
                                                                $sPacientesAnulados);

  	} else if(isset($chave_z01_v_ident) && (trim($chave_z01_v_ident) != '') ) {

	  	$sql = $oDaocgs_und->sql_query_cgs_beneficiadosajudacusto('', $campos, 'z01_v_nome',
                                                                " z01_v_ident = '$chave_z01_v_ident' and ".
                                                                "tf01_i_codigo = $chave_tf01_i_codigo and ".
                                                                $sPacientesAnulados);

  	} else if(isset($chave_z01_d_nasc) && (trim($chave_z01_d_nasc) != '')) {

	  	$chave_z01_d_nasc = substr($chave_z01_d_nasc,6,4).'-'.substr($chave_z01_d_nasc,3,2).'-'.substr($chave_z01_d_nasc,0,2);
		 	$sql = $oDaocgs_und->sql_query_cgs_beneficiadosajudacusto(null, $campos, 'z01_v_nome',
                                                                " z01_d_nasc = '$chave_z01_d_nasc' and ".
                                                                "tf01_i_codigo = $chave_tf01_i_codigo and ".
                                                                $sPacientesAnulados);

	  } else {

		  $sql = $oDaocgs_und->sql_query_cgs_beneficiadosajudacusto(null, $campos, 'z01_v_nome', 
                                                                " tf01_i_codigo = $chave_tf01_i_codigo and ".
                                                                $sPacientesAnulados);
  	}

		$repassa = array();
		if(isset($chave_z01_i_cgsund)) {

			$repassa = array('chave_z01_i_cgsund'=>@$chave_z01_i_cgsund, 
							         'chave_z01_v_nome'=>@$chave_z01_v_nome,
							         'chave_z01_v_ident'=>@$chave_z01_v_ident);

		}

		db_lovrot( $sql,15,"()","",$funcao_js,"","NoMe",$repassa);

	} else {
		
		if($pesquisa_chave != null && $pesquisa_chave != '') {
      
			$sWhere = " z01_i_cgsund = $pesquisa_chave ";
			if (isset($chave_tf01_i_codigo) && !empty($chave_tf01_i_codigo)) {
				$sWhere .= " and tf01_i_codigo = {$chave_tf01_i_codigo}";
			}
      $sql = $oDaocgs_und->sql_query_cgs_beneficiadosajudacusto(null, 
      		                                                      '*', 
      		                                                      null, 
                                                                $sWhere);
      $rs = $oDaocgs_und->sql_record($sql);

			if($oDaocgs_und->numrows != 0) {

				db_fieldsmemory($rs, 0);
				echo "<script>".$funcao_js."('$z01_v_nome',false, '$tipo');</script>";

			} else {
				echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
			}

		}

  }
?>
	</td>
 </tr>
</table>
</body>
</html>
<script>
function js_nome(nome){
	if( nome != "" ){
		//document.form2.pesquisar.focus();
	}
}
/**
 * Botoão Fechar
 * campoFoco = foco de retorno quando fechar
 */
function js_fechar( campoFoco ){
	if( campoFoco != undefined && campoFoco != '' ){

		eval( "parent.document.getElementById('"+campoFoco+"').focus(); " );
		eval( "parent.document.getElementById('"+campoFoco+"').select(); " );

	}
	parent.db_iframe_cgs_und_beneficiadosajudacusto.hide();
} 

function js_limpar(){
	document.form2.chave_z01_v_nome.value="";
	document.form2.chave_z01_i_cgsund.value="";
	document.form2.chave_z01_v_ident.value="";
}
document.form2.chave_z01_v_nome.focus();

</script>