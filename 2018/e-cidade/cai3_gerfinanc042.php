<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_certid_classe.php");
require("libs/db_sql.php");
$clcertid = new cl_certid;
$clrotulo = new rotulocampo;
$clrotulo->label("v07_numpre");
$clrotulo->label("v01_exerc");
$clrotulo->label("v14_certid");
$clrotulo->label("v14_parcel");
$clrotulo->label("v07_numpre");
$clrotulo->label("k00_numpar");
$clrotulo->label("v14_vlrhis");
$clrotulo->label("v14_vlrcor");
$clrotulo->label("v14_vlrjur");
$clrotulo->label("v14_vlrmul");
if(isset($certid)){
  $sql = "
         select certter.v14_certid,v14_parcel,v07_numpre,k00_numpar,v14_vlrhis,v14_vlrcor,v14_vlrjur,v14_vlrmul
	   from certter
         	inner join termo   on  certter.v14_parcel= termo.v07_parcel
		inner join arrecad on termo.v07_numpre=arrecad.k00_numpre
		inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                     		 and arreinstit.k00_instit = ".db_getsession('DB_instit')."
	 	inner join cgm     on  termo.v07_numcgm = cgm.z01_numcgm
              where   certter.v14_certid=$certid
            order by certter.v14_certid,v14_parcel,v07_numpre,k00_numpar
        ";
    $sql02 = "
         select distinct v07_numpre,v14_parcel
	   from certter
         	inner join termo  on   certter.v14_parcel= termo.v07_parcel
          inner join arrecad on termo.v07_numpre=arrecad.k00_numpre
    where v14_certid=$certid and v07_instit =	".db_getsession('DB_instit')." ";
}
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<form name="form1" method="post" action="">
<center>
<table>
</tr>
    <tr>
      <td  align='left'><b>DADOS DO PARCELAMENTO</b></td>
    </tr>
<tr>
<tr>
  <td>
<?

  $result02=db_query($sql02);
  $numrows02=pg_numrows($result02);
    echo "
   <table border='1';>
    <tr>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"Opções\" align=\"center\"><b>O</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"Opções\" align=\"center\"><b>Origem</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv14_parcel\" align=\"center\">$Lv14_parcel</td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv07_numpre\" align=\"center\">$Lv07_numpre</td>
    <th title=\"Valor Corrigido\" bgcolor=\"#CDCDFF\"   nowrap>Val His.</th>\n
    <th title=\"Valor Corrigido\" bgcolor=\"#CDCDFF\"   nowrap>Val Cor.</th>\n
    <th title=\"Valor Juros\" bgcolor=\"#CDCDFF\"  nowrap>Jur.</th>\n
    <th title=\"Valor Multa\" bgcolor=\"#CDCDFF\"  nowrap>Mul.</th>\n
    <th title=\"Valor Desconto\" bgcolor=\"#CDCDFF\"  nowrap>Desc.</th>\n
    <th title=\"Total a Pagar\" bgcolor=\"#CDCDFF\"  nowrap>Tot.</th>\n
    </tr>";
  for($i=0; $i<$numrows02; $i++){
   $lrhis='';
   $lrcor='';
   $lrjuros='';
   $lrmulta='';
   $lrdesconto='';
   $otal='';
    db_fieldsmemory($result02,$i);
    // problema neste resulta
     $result05=debitos_numpre($v07_numpre,0,$tipo,db_getsession("DB_datausu"),db_getsession("DB_anousu"));
     if ($result05==false){
     	//echo "retornou falso";
     }else{
	     $numrows05=pg_numrows($result05);
	     for($d=0; $d<$numrows05; $d++){
			 db_fieldsmemory($result05,$d);
			 $lrhis+=$vlrhis;
			 $lrcor+=$vlrcor;
			 $lrjuros+=$vlrjuros;
			 $lrmulta+=$vlrmulta;
			 $lrdesconto+=$vlrdesconto;
			 $otal+=$total;
	    }
  	}

    if($i%2==0){
      $color="#E796A4";
    }else{
      $color="#97B5E6";
    }
    $sql03="
           select v07_parcel, termodiv.parcel as termodiv, termoini.parcel as termoini
	   	from certter
		  inner join termo on termo.v07_parcel = certter.v14_parcel
		  left join termodiv on termodiv.parcel = termo.v07_parcel
		  left join termoini on termoini.parcel = termo.v07_parcel
		where  termo.v07_parcel=$v14_parcel and v07_instit =".db_getsession('DB_instit') ."
	  ";
    $result03=db_query($sql03);
    db_fieldsmemory($result03,0);

    if($termodiv!=''){
      $tipo02='d';
      $funcao="js_termodiv('$v14_parcel');";
      $origem="Parcelamento de divida normal";
    }else if($termoini!=''){
      $tipo02='i';
      $funcao="js_termoini('$v14_parcel');";
      $origem="Parcelamento de inicial";
    }

echo "
    <tr>
    <td nowrap bgcolor=\"$color\" title=\"Opções\" align=\"center\"><b><a href='#' onclick=\"$funcao return false;\">MI</a></b></td>
    <td nowrap bgcolor=\"$color\" title=\"Origem do parcelamento\" align=\"center\">$origem</td>
    <td nowrap bgcolor=\"$color\" title=\"Origem do parcelamento\" align=\"center\">$v14_parcel</td>
    <td nowrap bgcolor=\"$color\" title=\"$Tv07_numpre\" align=\"center\">$v07_numpre</td>
    <td bgcolor=\"$color\"  title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>$lrhis</td>\n
    <td bgcolor=\"$color\"  title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>$lrcor</td>\n
    <td bgcolor=\"$color\"  title=\"Valor Juros\" style=\"font-size:11px\" nowrap>$lrjuros</td>\n
    <td bgcolor=\"$color\"  title=\"Valor Multa\" style=\"font-size:11px\" nowrap>$lrmulta</td>\n
    <td bgcolor=\"$color\"  title=\"Valor Desconto\" style=\"font-size:11px\" nowrap>".db_formatar($lrdesconto,'f')."</td>\n
    <td bgcolor=\"$color\"  title=\"Total a Pagar\" style=\"font-size:11px\" nowrap>".db_formatar($otal,'f')."</td>\n
    </tr>";
  }
  echo "</table>";
?>
  </td>
</tr>
    <tr>
      <td  align='left'><b>PARCELAS DO PARCELAMENTO</b></td>
    </tr>
<tr>
  <td>
<?

  $result=db_query($sql);
  $numrows=pg_numrows($result);
    echo "
   <table border='1';>
    <tr>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv14_certid\" align=\"center\"><b>$RLv14_certid</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv14_parcel\" align=\"center\"><b>$RLv14_parcel</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv07_numpre\" align=\"center\"><b>$RLv07_numpre</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tk00_numpar\" align=\"center\"><b>$RLk00_numpar</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv14_vlrhis\" align=\"center\"><b>$RLv14_vlrhis</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv14_vlrcor\" align=\"center\"><b>$RLv14_vlrcor</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv14_vlrjur\" align=\"center\"><b>$RLv14_vlrjur</b></td>
    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv14_vlrmul\" align=\"center\"><b>$RLv14_vlrmul</b></td>
    <th title=\"Valor Corrigido\" bgcolor=\"#CDCDFF\"   nowrap>Val His.</th>\n
    <th title=\"Valor Corrigido\" bgcolor=\"#CDCDFF\"   nowrap>Val Cor.</th>\n
    <th title=\"Valor Juros\" bgcolor=\"#CDCDFF\"  nowrap>Jur.</th>\n
    <th title=\"Valor Multa\" bgcolor=\"#CDCDFF\"  nowrap>Mul.</th>\n
    <th title=\"Valor Desconto\" bgcolor=\"#CDCDFF\"  nowrap>Desc.</th>\n
    <th title=\"Total a Pagar\" bgcolor=\"#CDCDFF\"  nowrap>Tot.</th>\n
    </tr>";
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);

    // o problema esta neste result
    $result05=debitos_numpre($v07_numpre,0,$tipo,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$k00_numpar);

	if($result05==false){
		//echo "retornou false";
	}else{
	     db_fieldsmemory($result05,0);

	    if($i%2==0){
	      $color="#E796A4";
	    }else{
	      $color="#97B5E6";
	    }
	echo "
	    <tr>
	      <td nowrap bgcolor=\"$color\" title=\"$Tv14_certid\" align=\"center\">$v14_certid</b></td>
	      <td nowrap bgcolor=\"$color\" title=\"$Tv14_parcel\" align=\"center\">$v14_parcel</td>
	      <td nowrap bgcolor=\"$color\" title=\"$Tv07_numpre\" align=\"center\">$v07_numpre</td>
	      <td nowrap bgcolor=\"$color\" title=\"$Tk00_numpar\" align=\"center\">$k00_numpar</td>
	      <td nowrap bgcolor=\"$color\" title=\"$Tv14_vlrhis\" align=\"center\">$v14_vlrhis</td>
	      <td nowrap bgcolor=\"$color\" title=\"$Tv14_vlrcor\" align=\"center\">$v14_vlrcor</td>
	      <td nowrap bgcolor=\"$color\" title=\"$Tv14_vlrjur\" align=\"center\">$v14_vlrjur</td>
	      <td nowrap bgcolor=\"$color\" title=\"$Tv14_vlrmul\" align=\"center\">$v14_vlrmul</td>
	      <td bgcolor=\"$color\"  title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap> ".@$vlrhis."</td>\n
	      <td bgcolor=\"$color\"  title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap> ".@$vlrcor."</td>\n
	      <td bgcolor=\"$color\"  title=\"Valor Juros\" style=\"font-size:11px\" nowrap> ".@$vlrjuros."</td>\n
	      <td bgcolor=\"$color\"  title=\"Valor Multa\" style=\"font-size:11px\" nowrap> ".@$vlrmulta."</td>\n
	      <td bgcolor=\"$color\"  title=\"Valor Desconto\" style=\"font-size:11px\" nowrap>".db_formatar(@$vlrdesconto,'f')."</td>\n
	      <td bgcolor=\"$color\"  title=\"Total a Pagar\" style=\"font-size:11px\" nowrap>".db_formatar(@$total,'f')."</td>\n
	    </tr>";
	  }
  }
  echo "</table>";
?>
  </td>
</tr>
</table>
</center>
</form>
</body>
</html>
<script>
  function js_termodiv(parcel){
      js_OpenJanelaIframe('top.corpo','db_iframe3','cai3_gerfinanc043.php?modo=<?=base64_encode($origem)?>&parcel='+parcel+'&tipo=<?=$tipo?>','Pesquisa',true);
  }
  function js_termoini(parcel){
      js_OpenJanelaIframe('top.corpo','db_iframe3','cai3_gerfinanc044.php?modo=<?=base64_encode($origem)?>&parcel='+parcel+'&tipo=<?=$tipo?>','Pesquisa',true);
  }
</script>