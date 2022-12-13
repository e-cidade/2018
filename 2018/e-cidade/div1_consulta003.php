<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

	require(modification("libs/db_stdlib.php"));
	require(modification("libs/db_conecta.php"));
	include(modification("libs/db_sessoes.php"));
	include(modification("libs/db_usuariosonline.php"));
	require(modification("classes/db_divida_classe.php"));
	require(modification("classes/db_certdiv_classe.php"));
	require(modification("classes/db_termodiv_classe.php"));
	require(modification("classes/db_termoini_classe.php"));
	require(modification("classes/db_inicial_classe.php"));
	require(modification("classes/db_certter_classe.php"));
	require(modification("classes/db_termo_classe.php"));
	require(modification("classes/db_certid_classe.php"));
	include(modification("dbforms/db_funcoes.php"));
	db_postmemory($HTTP_SERVER_VARS);
	db_postmemory($HTTP_POST_VARS);
	$cldivida = new cl_divida;
	$cltermo = new cl_termo;
	$clcertdiv = new cl_certdiv;
	$cltermodiv = new cl_termodiv;
	$cltermoini = new cl_termoini;
	$clinicial = new cl_inicial;
	$clcertter = new cl_certter;
	$clcertid = new cl_certid;

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
    <td align="center" valign="middle">
<table width="95%" border="1" cellspacing="0" class='tab_cinza'>
      <?
	if (isset($codDiv)){
		$sql    = $cldivida->sql_query($codDiv,"*",null," v01_coddiv = $codDiv and v01_instit = ".db_getsession('DB_instit'));
		$result = $cldivida->sql_record($sql);
		$intNumRows = $cldivida->numrows;
		if($intNumRows > 0){
			$cldivida->rotulo->label();
			db_fieldsmemory($result,0,3);
			//aqui o codigo da divida foi localizada e pode-se trazer os seus dados
			$sqlSituacao = $cldivida->sql_querysituacao($v01_coddiv," v01_instit = ".db_getsession('DB_instit'));
			$resultSituacao = db_query($sqlSituacao);
			$fraseSituacao = " ( ".pg_result($resultSituacao,0,0)." )";
?>
    <tr>
      <th align="center" colspan="4">&nbsp;<b>Dados da dívida<?=@$fraseSituacao?></b> </th>
    </tr>
    <tr>
      <th align="left" width="15%" title="<?=$Tv01_coddiv?>" nowrap>&nbsp;<?=$Lv01_coddiv?>&nbsp; </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_coddiv?>&nbsp; </td>
      <th align="left" width="15%" title="<?=$Tv01_dtinsc?>" nowrap>&nbsp;<?=$Lv01_dtinsc?>&nbsp; </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_dtinsc?>&nbsp; </td>
    </tr>
    <tr>
      <th align="left" width="15%" title="<?=$Tv01_exerc?>" nowrap>&nbsp;<?=$Lv01_exerc?>&nbsp; </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_exerc?>&nbsp;	  </td>
      <th align="left" width="15%" title="<?=$Tv01_numpre?>" nowrap>&nbsp;<?=$Lv01_numpre?>&nbsp; </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_numpre?>&nbsp; </td>
    </tr>
    <tr>
      <th align="left" width="15%" title="<?=$Tv01_vlrhis?>" nowrap>&nbsp;<?=$Lv01_vlrhis?>&nbsp;  </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_vlrhis?>&nbsp;	  </td>
      <th align="left" width="15%" title="<?=$Tv01_proced?>" nowrap>&nbsp;<?=$Lv01_proced?>&nbsp;	  </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_proced?>&nbsp;	  </td>
    </tr>
    <tr>
      <th align="left" width="15%" title="<?=$Tv01_livro?>" nowrap>&nbsp;<?=$Lv01_livro?>&nbsp; </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_livro?>&nbsp; </td>
      <th align="left" width="15%" title="<?=$Tv01_folha?>" nowrap>&nbsp;<?=$Lv01_folha?>&nbsp; </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_folha?>&nbsp; </td>
    </tr>
    <tr>
      <th align="left" width="15%" title="<?=$Tv01_dtvenc?>" nowrap>&nbsp;<?=$Lv01_dtvenc?>&nbsp;  </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_dtvenc?>&nbsp;  </td>
      <th align="left" width="15%" title="<?=$Tv01_dtoper?>" nowrap>&nbsp;<?=$Lv01_dtoper?>&nbsp;  </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_dtoper?>&nbsp;  </td>
    </tr>
    <tr>
      <th align="left" width="15%" title="<?=$Tv01_valor?>" nowrap>&nbsp;<?=$Lv01_valor?>&nbsp;	  </th>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v01_valor?>&nbsp;	  </td>
      <td align="left" width="15%"nowrap>&nbsp;	  </td>
      <td align="left" width="35%" nowrap>&nbsp;	  </td>
    </tr>
    <tr>
      <th align="left" width="15%" title="<?=$Tv01_obs?>">&nbsp;<?=$Lv01_obs?>&nbsp;	  </th>
      <td align="left" colspan="3" >&nbsp;<?=$v01_obs?>&nbsp;	  </td>
    </tr>
<?
		}else{
?>
    <tr>
      <th align="center">&nbsp;Dívida não localizada </th>
    </tr>
<?
		}
	}else if (isset($codCert)){
		$sql = $clcertid->sql_query($codCert);
		$result = $clcertid->sql_record($sql);
		if(pg_numrows($result)!=0){
			$clcertid->rotulo->label();
			db_fieldsmemory($result,0,3);
			//aqui o codigo da divida foi localizada e pode-se trazer os seus dados
?>
    <tr>
      <td align="center" colspan="4">&nbsp;<b>Dados da Certidão</b>
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tv13_certid?>" nowrap>&nbsp;<?=$Lv13_certid?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v13_certid?>&nbsp;
	  </td>
      <td align="left" width="15%" title="<?=$Tv13_dtemis?>" nowrap>&nbsp;<?=$Lv13_dtemis?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v13_dtemis?>&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tv13_login?>" nowrap>&nbsp;<?=$Lv13_login?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v13_login?>&nbsp;
	  </td>
      <td align="left" width="15%" nowrap>&nbsp;<input type="button" value="Texto" name="texto" id="texto" onclick="parent.js_abreJanelaTextoCertidao(<?=$v13_certid?>)">
	  </td>
<?
		$sql = $clcertdiv->sql_query($v13_certid);
		$result = $clcertdiv->sql_record($sql);
			if(pg_numrows($result)!=0){
?>
      <td align="left" width="35%" nowrap>&nbsp;Certidão de dívida normal&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" colspan="4">&nbsp;
	  <iframe name="iframe_lov" width="100%" height="100%" align="center" marginwidth="0" marginheight="0" frameborder="0" src="div1_consulta002.php?certidNorm=<?=$v13_certid?>&funcao_js=parent.CurrentWindow.corpo.js_abreJanelaDadosDivida|0"></iframe>
	  </td>
    </tr>
<?
			}else{
?>
      <td align="left" width="35%" nowrap>&nbsp;Certidão de parcelamento&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" colspan="4">&nbsp;
	  <iframe name="iframe_lov" width="100%" height="100%" align="center" marginwidth="0" marginheight="0" frameborder="0" src="div1_consulta002.php?certidParc=<?=$v13_certid?>&funcao_js=parent.CurrentWindow.corpo.js_abreJanelaDadosTermo|0"></iframe>
	  </td>
    </tr>
<?
			}
		}else{
?>
    <tr>
      <td align="center">&nbsp;Certidão não localizada&nbsp;
	  </td>
    </tr>
<?
		}
	}else if(isset($codTerm)){
		$sql = $cltermo->sql_query($codTerm);
		$result = $cltermo->sql_record($sql);
		if(pg_numrows($result)!=0){
			$cltermo->rotulo->label();
			db_fieldsmemory($result,0,3);
?>
    <tr>
      <td align="center" colspan="4">&nbsp;<b>Dados do Termo</b>
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tv07_parcel?>" nowrap>&nbsp;<?=$Lv07_parcel?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_parcel?>&nbsp;
	  </td>
      <td align="left" width="15%" title="<?=$Tv07_dtlanc?>" nowrap>&nbsp;<?=$Lv07_dtlanc?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_dtlanc?>&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tv07_valor?>" nowrap>&nbsp;<?=$Lv07_valor?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_valor?>&nbsp;
	  </td>
      <td align="left" width="15%" title="<?=$Tv07_numpre?>" nowrap>&nbsp;<?=$Lv07_numpre?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_numpre?>&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tv07_totpar?>" nowrap>&nbsp;<?=$Lv07_totpar?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_totpar?>&nbsp;
	  </td>
      <td align="left" width="15%" title="<?=$Tv07_vlrpar?>" nowrap>&nbsp;<?=$Lv07_vlrpar?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_vlrpar?>&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tv07_dtvenc?>" nowrap>&nbsp;<?=$Lv07_dtvenc?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_dtvenc?>&nbsp;
	  </td>
      <td align="left" width="15%" title="<?=$Tv07_vlrent?>" nowrap>&nbsp;<?=$Lv07_vlrent?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_vlrent?>&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tv07_datpri?>" nowrap>&nbsp;<?=$Lv07_datpri?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_datpri?>&nbsp;
	  </td>
      <td align="left" width="15%" title="<?=$Tv07_vlrmul?>" nowrap>&nbsp;<?=$Lv07_vlrmul?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_vlrmul?>&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tv07_login?>" nowrap>&nbsp;<?=$Lv07_login?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$v07_login?>&nbsp;
	  </td>
      <td align="left" width="15%" nowrap>&nbsp;<input type="button" value="Texto" name="texto" id="texto" onclick="parent.js_abreJanelaTextoTermo(<?=$v07_parcel?>)">
	  </td>
<?
		$sql = $cltermodiv->sql_query($v07_parcel);
		$result = $cltermodiv->sql_record($sql);
			if(pg_numrows($result)!=0){
?>
      <td align="left" width="35%" nowrap>&nbsp;Parcelamento de dívida normal&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" colspan="4">&nbsp;
	  <iframe name="iframe_lov" width="100%" height="100%" align="center" marginwidth="0" marginheight="0" frameborder="0" src="div1_consulta002.php?termoNorm=<?=$v07_parcel?>&funcao_js=parent.CurrentWindow.corpo.js_abreJanelaDadosDivida|0"></iframe>
	  </td>
    </tr>
<?
			}else{
?>
      <td align="left" width="35%" nowrap>&nbsp;Parcelamento de divida inicial&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" colspan="4">&nbsp;
	  <iframe name="iframe_lov" width="100%" height="100%" align="center" marginwidth="0" marginheight="0" frameborder="0" src="div1_consulta002.php?termoInicial=<?=$v07_parcel?>&funcao_js=parent.CurrentWindow.corpo.js_abreJanelaDadosInicial|0"></iframe>
	  </td>
    </tr>
<?
			}
		}else{
?>
    <tr>
      <td align="center">&nbsp;Termo não localizado&nbsp;
	  </td>
    </tr>
<?
		}
?>

<?
	}else if(isset($codInicial)){
		$sql = $clinicial->sql_query($codInicial);
		$result = $clinicial->sql_record($sql);
		if(pg_numrows($result)!=0){
			$clinicial->rotulo->label();
			db_fieldsmemory($result,0,3);
?>
    <tr>
      <td align="center" colspan="4">&nbsp;<b>Parcela Inicial</b>
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tinicial?>" nowrap>&nbsp;<?=$Linicial?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$inicial?>&nbsp;
	  </td>
      <td align="left" width="15%" title="<?=$Tadvog?>" nowrap>&nbsp;<?=$Ladvog?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$advog?>&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" title="<?=$Tdata?>" nowrap>&nbsp;<?=$Ldata?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$data?>&nbsp;
	  </td>
      <td align="left" width="15%" title="<?=$Tid_login?>" nowrap>&nbsp;<?=$Lid_login?>&nbsp;
	  </td>
      <td align="left" width="35%" nowrap>&nbsp;<?=$id_login?>&nbsp;
	  </td>
    </tr>
    <tr>
      <td align="left" width="15%" colspan="4">&nbsp;
	  <iframe name="iframe_lov" width="100%" height="100%" align="center" marginwidth="0" marginheight="0" frameborder="0" src="div1_consulta002.php?iniCert=<?=$inicial?>&funcao_js=parent.CurrentWindow.corpo.js_abreJanelaDadosTermo|0"></iframe>
	  </td>
    </tr>
<?
		}else{
?>
    <tr>
      <td align="center">&nbsp;Parcela Inicial não existente
	  </td>
    </tr>
<?
	}
	}else{
?>
    <tr>
      <td align="center">&nbsp;Página solicitada incorretamente
	  </td>
    </tr>
<?
	}
?>
</table>
    </td>
  </tr>
</table>
</body>
</html>
