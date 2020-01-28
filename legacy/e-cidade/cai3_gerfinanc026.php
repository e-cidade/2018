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

set_time_limit(0);
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
require(modification("libs/db_utils.php"));
require(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_sql.php"));
include(modification("classes/db_iptubase_classe.php"));
include(modification("classes/db_issbase_classe.php"));
include(modification("classes/db_propri_classe.php"));
include(modification("classes/db_promitente_classe.php"));

include(modification("classes/db_termoanu_classe.php"));

$cltermoanu = new cl_termoanu;

$oGet  = db_utils::postmemory($_GET);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.borda {
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
-->
</style>
<script>
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {
    if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
      document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage;
    }
  } else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

</script>
</head>
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<form name="form1" method="post">
<tr>&nbsp;&nbsp;</tr>
<table border="1" cellpadding="0" cellspacing="0">
<?php

  if(isset($oGet->numcgm)) {

    /**
     * Alterado para quando arrenumcgm, buscar o cgm na virtual e naum na origem (arresusp)
     * @var string
     */
   	$querystring 	 = "numcgm=$oGet->numcgm";
   	$sSqlInnerSusp = " inner join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre ";
    $sSqlWhereSusp = " arrenumcgm.k00_numcgm = ".$oGet->numcgm;
  }else if(isset($oGet->matric)){

    $querystring   = "matric=$oGet->matric";
	  $sSqlInnerSusp = " inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre";
	  $sSqlWhereSusp = " arrematric.k00_matric = ".$oGet->matric;
  }else if(isset($oGet->inscr)){

   	$querystring   = "inscr=$oGet->inscr";
	  $sSqlInnerSusp = " inner join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre";
	  $sSqlWhereSusp = " arreinscr.k00_inscr = ".$oGet->inscr;
  } else {

    $querystring   = "numpre=$oGet->numpre";
    $sSqlInnerSusp = "";
    $sSqlWhereSusp = " arresusp.k00_numpre = ".$oGet->numpre;
  }

  $sSqlSusp  = " select distinct 																			                                  ";
  $sSqlSusp .= "	    ar18_sequencial,		    														                              ";
  $sSqlSusp .= "	    ar18_procjur,		    															                                ";
  $sSqlSusp .= "	    ar18_data,		    															 	                                ";
  $sSqlSusp .= "	    ar18_hora,		    																                                ";
  $sSqlSusp .= "	    v66_descr,		    																                                ";
  $sSqlSusp .= "	    case 																				                                      ";
  $sSqlSusp .= " 		  when ar18_situacao = 1 then 'Ativa' else 'Finalizada' 							              ";
  $sSqlSusp .= "	    end as ar18_situacao,		    													                            ";
  $sSqlSusp .= "	  	db_usuarios.login																	                                ";
  $sSqlSusp .= "   from arresusp 																			                                  ";
  $sSqlSusp .= "	    inner join suspensao   on suspensao.ar18_sequencial  = arresusp.k00_suspensao	    ";
  $sSqlSusp .= "	    inner join procjur     on procjur.v62_sequencial     = suspensao.ar18_procjur	    ";
  $sSqlSusp .= "	    inner join procjurtipo on procjurtipo.v66_sequencial = procjur.v62_procjurtipo	  ";
  $sSqlSusp .= "	    inner join db_usuarios on db_usuarios.id_usuario     = suspensao.ar18_usuario     ";
  $sSqlSusp .= "	    inner join arreinstit  on arreinstit.k00_numpre      = arresusp.k00_numpre		    ";
  $sSqlSusp .= "	   					              and arreinstit.k00_instit      = ".db_getsession('DB_instit');
  $sSqlSusp .= "		{$sSqlInnerSusp}																 	                                  ";
  $sSqlSusp .= "  where {$sSqlWhereSusp}																 	                              ";
  $sSqlSusp .= "  order by ar18_data desc,														 	 		                            ";
  $sSqlSusp .= "  		   ar18_hora desc,														 	 		                              ";
  $sSqlSusp .= "  		   ar18_sequencial desc													 	 		                            ";

  $rsDebitosSuspensos = db_query($sSqlSusp);
  $iLinhasDebitosSusp = pg_num_rows($rsDebitosSuspensos);

  echo "    <tr bgcolor='#FFCC66'> ";
  echo "      <th class='borda' style='font-size:12px' nowrap> Detalhes       </th> ";
  echo "      <th class='borda' style='font-size:12px' nowrap> Cód. Suspensão </th> ";
  echo "      <th class='borda' style='font-size:12px' nowrap> Processo       </th> ";
  echo "      <th class='borda' style='font-size:12px' nowrap> Usuário		    </th> ";
  echo "      <th class='borda' style='font-size:12px' nowrap> Data           </th> ";
  echo "      <th class='borda' style='font-size:12px' nowrap> Hora           </th> ";
  echo "      <th class='borda' style='font-size:12px' nowrap> Situação       </th> ";
  echo "    </tr> ";

  $cor="#EFE029";

  for($i=0; $i < $iLinhasDebitosSusp; $i++){

    $oSuspensao = db_utils::fieldsMemory($rsDebitosSuspensos,$i);

    if($cor=="#EFE029"){
      $cor="#E4F471";
    }else if($cor=="#E4F471"){
	  $cor="#EFE029";
    }

    $funcaojs = " js_consultaSuspensao(".$oSuspensao->ar18_sequencial.");return false; ";

    echo "  <tr title='Clique aqui para verificar os dados' style='cursor: hand' onclick='".$funcaojs."'>  ";
    echo "    <td align='center' style='font-size:12px;width:80px;'  nowrap bgcolor='$cor'>  <a href=''> MI </a>             		   </td> ";
    echo "    <td align='center' style='font-size:12px;width:80px;'  nowrap bgcolor='$cor'> {$oSuspensao->ar18_sequencial}			   </td> ";
    echo "    <td align='center' style='font-size:12px;width:200px;' nowrap bgcolor='$cor'> {$oSuspensao->ar18_procjur}-{$oSuspensao->v66_descr} </td> ";
    echo "    <td align='left'   style='font-size:12px;width:80px;'  nowrap bgcolor='$cor'> {$oSuspensao->login} 					   </td> ";
    echo "    <td align='center' style='font-size:12px;width:80px;'  nowrap bgcolor='$cor'> ".db_formatar($oSuspensao->ar18_data,'d')." </td> ";
    echo "    <td align='center' style='font-size:12px;width:80px;'  nowrap bgcolor='$cor'> {$oSuspensao->ar18_hora} 				   </td> ";
    echo "    <td align='left'   style='font-size:12px;width:80px;'  nowrap bgcolor='$cor'> {$oSuspensao->ar18_situacao}  			   </td> ";
    echo "  </tr> ";

  }

?>
</table>
<table>
  <tr align="center">
    <td>
      <input type="button" name="impsusp" value="Imprimir Suspensões" onclick="js_imprime();">
    </td>
  </tr>
</table>
</form>
</center>
</body>
</html>
<script>

function js_consultaSuspensao(iCodSuspensao){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_consultasusp'+iCodSuspensao,'cai3_consultasusp001.php?suspensao='+iCodSuspensao,'Consulta Suspensões',true);
}
function js_imprime(){
  jan = window.open('cai2_gerfinanc026.php?<?=($querystring)?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>