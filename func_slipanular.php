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
include ("classes/db_slip_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clslip   = new cl_slip;
$clrotulo = new rotulocampo;

$clrotulo->label("k17_codigo");
$clrotulo->label("k17_debito");
$clrotulo->label("k17_credito");
$clrotulo->label("k17_data");
$clrotulo->label("k17_valor");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="" >
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align="center" valign="top">
      <table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
	<tr> 
	  <td align="center" valign="top">	  
	    <table width="35%" border="0" align="center" cellspacing="0">
		
		<tr>
		  <td align="left" nowrap title="<?=$Tk17_codigo?>"> <? db_ancora(@$Lk17_codigo,"",3);?>  </td>
		  <td align="left" nowrap><?    db_input("k17_codigo",8,$Ik17_codigo,true,"text",4,"","chave_k17_codigo");	 ?></td>
		</tr>
				
		<tr>
		<td colspan=2>
		<fieldset>
		<table border=0>
		 <tr>
		  <td align="left" nowrap title="<?=$Tk17_data?>"> <? db_ancora(@$Lk17_data,"",3);?>  </td>
		  <td align="left" nowrap><? db_inputdata("k17_data",@$k17_data_dia,@$k17_data_mes,db_getsession("DB_anousu"),true,'text',1);    ?></td>
		</tr>		
		<tr>
		  <td align="left" nowrap title="<?=$Tk17_debito?>"> <? db_ancora(@$Lk17_debito,"",3);?>  </td>
		  <td align="left" nowrap><?    db_input("k17_debito",8,$Ik17_debito,true,"text",4,"","chave_k17_debito");	 ?></td>
		</tr>		
		<tr>
		  <td align="left" nowrap title="<?=$Tk17_credito ?>"> <? db_ancora(@$Lk17_credito,"",3);?>  </td>
		  <td align="left" nowrap><?    db_input("k17_credito",8,$Ik17_credito,true,"text",4,"","chave_k17_credito");	 ?></td>
		</tr>
        <tr>
		  <td align="left" nowrap title="<?=$Tk17_valor ?>"> <? db_ancora(@$Lk17_valor,"",3);?>  </td>
		  <td align="left" nowrap><?    db_input("k17_valor",8,$Ik17_credito,true,"text",4,"onKeyDown=this.value=this.value.replace(',','.')","chave_k17_valor");	 ?></td>
		</tr>
		</table>
		</fieldset>
		</td>
		</tr>
		
	    </table>
	    </td>
	</tr>
	<tr> 
	  <td align="center"> 
	    <BR>
	    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
	    <input name="limpar" type="reset" id="limpar" value="Limpar" >
	    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_slip.hide();">
	    <BR>
	   </td>
	</tr>
  </form>
	<tr> 
	  <td align="center" valign="top"> 
	    <?



if (isset ($campos) == false) {
	if (file_exists("funcoes/db_func_slip.php") == true) {
		include ("funcoes/db_func_slip.php");
	} else {
		$campos = "k17_codigo,k17_data,k17_debito,k17_credito,k17_valor,k17_hist,k17_texto";
	}
}

$wh  = " and k17_situacao  in (1,3)";
$wh2 = " k17_situacao  in (1,3)";

if (isset($chave_k17_codigo)) {
  
    $wh2 .="  and k17_codigo=$chave_k17_codigo";
  }






if (!isset ($pesquisa_chave)) {
	if (isset ($chave_k17_codigo) && trim($chave_k17_codigo) != "") {
  $wh2="$wh2 and k17_codigo=$chave_k17_codigo";
  $sql = $clslip->sql_query($chave_k17_codigo, $campos,"k17_data","$wh2");
	} else {
		/*
		 *  
		 */
		$data = "";
		if (isset ($k17_data_dia) && $k17_data_dia != "") {
			$data = "$k17_data_ano-$k17_data_mes-$k17_data_dia";
		}
		if (isset ($chave_k17_debito) && trim($chave_k17_debito) != "") {
			if ($data == "")
				$sql = $clslip->sql_query(null, $campos, null, " k17_debito = $chave_k17_debito $wh and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."'");
			else
				$sql = $clslip->sql_query(null, $campos, null, " k17_debito = $chave_k17_debito $wh and k17_data='".$data."' and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."'");
		} else
			if (isset ($chave_k17_credito) && trim($chave_k17_credito) != "") {
				if ($data == "")
					$sql = $clslip->sql_query(null, $campos, null, " k17_debito = $chave_k17_credito $wh and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."'");
				else
					$sql = $clslip->sql_query(null, $campos, null, " k17_debito = $chave_k17_credito $wh and k17_data='".$data."' and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."'");
			} else
				if (isset ($chave_k17_valor) && trim($chave_k17_valor) != "") {
					if ($data == "")
						$sql = $clslip->sql_query(null, $campos, null, " k17_valor = $chave_k17_valor $wh  and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."'");
					else
						$sql = $clslip->sql_query(null, $campos, null, " k17_valor = $chave_k17_valor $wh  and k17_data='".$data."' and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."'");
				} else {
					if ($data == "")
						$sql = $clslip->sql_query(null, $campos, null, " to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."' $wh");
					else
						$sql = $clslip->sql_query(null, $campos, null, "  k17_data='".$data."' $wh  and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."'");

				}
	}

//  echo $sql;
	db_lovrot($sql,15,"()","",$funcao_js);

} else {
	if ($pesquisa_chave != null && $pesquisa_chave != "") {
		$result = $clslip->sql_record($clslip->sql_query(null,"*",null,"k17_codigo = $pesquisa_chave $wh  and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."'"));
		if ($clslip->numrows != 0) {
			db_fieldsmemory($result, 0);
			echo "<script>".$funcao_js."('$k17_codigo',false);</script>";
		} else {
			echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
		}
	} else {
		echo "<script>".$funcao_js."('',false);</script>";
	}
}
?>
	   </td>
	 </tr>
      </table>
     </td>
   </tr>
</table>
</body>
</html>
<?



if (!isset ($pesquisa_chave)) {
?>
  <script>
  </script>
  <?



}
?>