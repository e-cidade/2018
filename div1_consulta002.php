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
	require("classes/db_divida_classe.php");
	require("classes/db_certid_classe.php");
	require("classes/db_termodiv_classe.php");
	require("classes/db_termo_classe.php");
	require("classes/db_termoini_classe.php");
	require("classes/db_inicialcert_classe.php");
	require("classes/db_certdiv_classe.php");
	require("classes/db_certter_classe.php");
	include("dbforms/db_funcoes.php");
	db_postmemory($HTTP_SERVER_VARS);
	parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
	db_postmemory($HTTP_POST_VARS);
	$cldivida = new cl_divida;
	$clcertdiv = new cl_certdiv;
	$cltermoini = new cl_termoini;
	$cltermo = new cl_termo;
	$clcertter = new cl_certter;
	$clcertid = new cl_certid;
	$clinicialcert = new cl_inicialcert;
	$clrotulocampo = new rotulocampo;
	$cltermodiv = new cl_termodiv;
	$clrotulocampo->label("z01_nome");
	$clrotulocampo->label("j01_matric");
	$clrotulocampo->label("v01_coddiv");
	$clrotulocampo->label("q02_inscr");


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle"> <form name="form1" method="post" action="">
<?
	if (isset($pesquisa_Matricula)||isset($pesquisa_Inscricao)){
		if (isset($pesquisa_Matricula)){
			echo $Lj01_matric." ";
			db_input("j01_matric",10,$Ij01_matric,true,"text",4);
		}else if(isset($pesquisa_Inscricao)){
			echo $Lq02_inscr." ";
			db_input("q02_inscr",10,$Iq02_inscr,true,"text",4);
		}
?>
        <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
<?
	}else{
?>
        <input name="retornar" type="button" id="retornar" value="Retornar" onclick="parent.js_fechaJanela()">
<?
	}
?>
      </form></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
<?
	if(isset($pesquisar)){
		if(isset($j01_matric)){

			$sql = $cldivida->sql_querymatric($j01_matric,"divida.v01_coddiv,v01_numcgm,v01_dtinsc,v01_exerc","v01_instit = ".db_getsession('DB_instit'));
//		  echo $sql;
			$valorDigitadoParaPesquisa = $j01_matric;

		}else if(isset($q02_inscr)){

			$sql = $cldivida->sql_queryinscricao($q02_inscr,"divida.v01_coddiv,v01_numcgm,v01_dtinsc,v01_exerc","v01_instit = ".db_getsession('DB_instit'));
			$valorDigitadoParaPesquisa = $q02_inscr;

		}
	}else if (isset($pesquisa_CGM)){

		$sql = $cldivida->sql_querynumcgm($pesquisa_CGM,"v01_coddiv,v01_numcgm,v01_dtinsc,v01_exerc","v01_instit = ".db_getsession('DB_instit'));
		$valorDigitadoParaPesquisa = $pesquisa_CGM;

	}else if(isset($pesquisa_Matricula)){

		$sql = $cldivida->sql_querymatric($pesquisa_Matricula,"divida.v01_coddiv,v01_numcgm,v01_dtinsc,v01_exerc","v01_instit = ".db_getsession('DB_instit'));
		$valorDigitadoParaPesquisa = $pesquisa_Matricula;

	}else if(isset($pesquisa_Inscricao)){

		$sql = $cldivida->sql_queryinscricao($pesquisa_Inscricao,"divida.v01_coddiv,v01_numcgm,v01_dtinsc,v01_exerc","v01_instit = ".db_getsession('DB_instit'));
		$valorDigitadoParaPesquisa = $pesquisa_Inscricao;

	}else if(isset($certidNorm)){

		$sql = $clcertdiv->sql_query($certidNorm,"","v14_coddiv, v14_vlrhis, v14_vlrcor, v14_vlrjur, v14_vlrmul");
		$valorDigitadoParaPesquisa = $certidNorm;

	}else if(isset($certidParc)){

		$sql = $clcertter->sql_query($certidParc,"","v14_parcel, v14_vlrhis, v14_vlrcor, v14_vlrjur, v14_vlrmul");
		$valorDigitadoParaPesquisa = $certidParc;

	}else if(isset($textoCert)){

		$sql = $clcertid->sql_query($textoCert,"v13_memo",null,"v13_instit = ".db_getsession('DB_instit'));
		$valorDigitadoParaPesquisa = $textoCert;
		$funcao_js = "";

	}else if(isset($textoTerm)){

		$sql = $cltermo->sql_query($textoTerm,"v07_mtermo",null,"v07_instit = ".db_getsession('DB_instit'));
		$valorDigitadoParaPesquisa = $textoTerm;
		$funcao_js = "";

	}else if(isset($termoNorm)){

		$sql = $cltermodiv->sql_query($termoNorm,"","coddiv, valor, juros, multa, desconto, total, numpreant");
		$valorDigitadoParaPesquisa = $termoNorm;

	}else if(isset($termoInicial)){

		$sql = $cltermoini->sql_query($termoInicial,"","inicial, parcel, valor, juros, desconto, total, multa");
		$valorDigitadoParaPesquisa = $termoInicial;

	}else if(isset($iniCert)){

		$sql = $clinicialcert->sql_query($iniCert,"","inicialcert.v51_certidao, inicialcert.v51_inicial");
		$valorDigitadoParaPesquisa = $iniCert;

	}
//  echo $sql;
	db_lovrot($sql,15,"()","",$funcao_js);
?>
    </td>
  </tr>
</table>
</body>
</html>