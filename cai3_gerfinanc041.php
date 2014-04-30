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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_certid_classe.php");
require("libs/db_sql.php");
$clcertid = new cl_certid;
$clrotulo = new rotulocampo;
$clrotulo->label("v13_certid");
$clrotulo->label("v13_dtemis");
$clrotulo->label("v13_login");
$clrotulo->label("v01_exerc");
$clrotulo->label("v03_descr");
$clrotulo->label("k00_valor");
$clrotulo->label("v01_proced");
$clrotulo->label("v01_numpre");
$clrotulo->label("v01_coddiv");
$clrotulo->label("v01_numcgm");
$clrotulo->label("v01_dtinsc");
$clrotulo->label("v01_exerc");
$clrotulo->label("k00_inscr");
$clrotulo->label("k00_matric");
$clrotulo->label("v01_numpre");
$clrotulo->label("v01_numpar");
$clrotulo->label("v01_proced");
$clrotulo->label("v03_descr");
$clrotulo->label("v01_numtot");
$clrotulo->label("v01_numdig");
$clrotulo->label("v01_vlrhis");
$clrotulo->label("v01_obs");
$clrotulo->label("v01_livro");
$clrotulo->label("v01_folha");
$clrotulo->label("v01_dtvenc");
$clrotulo->label("v01_dtoper");
$clrotulo->label("v01_valor");
$clrotulo->label("z01_nome");

if (isset($certid)) {
	
  $sql = " select v01_coddiv,
                  v01_numcgm,
                  z01_nome,
                  v01_dtinsc,
                  v01_exerc,
                  v01_numpre,
                  v01_numpar,
                  v01_proced,
                  v03_descr,
                  v01_numtot,
                  v01_numdig,
                  v01_vlrhis,
                  v01_obs   ,
                  v01_livro ,
                  v01_folha ,
                  v01_dtvenc,
                  v01_dtoper,
                  v01_valor, 
                  arrematric.k00_matric, 
                  arreinscr.k00_inscr
	         from certdiv 
         	      inner join divida       on  divida.v01_coddiv    = certdiv.v14_coddiv
		          inner join arrecad      on  divida.v01_numpre    = arrecad.k00_numpre 
		                                 and divida.v01_numpar     = arrecad.k00_numpar
		          inner join arreinstit   on arreinstit.k00_numpre = arrecad.k00_numpre 
                  		                 and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
		           left join arreinscr    on arreinscr.k00_numpre  = arrecad.k00_numpre
		           left join arrematric   on arrematric.k00_numpre = arrecad.k00_numpre
              	  inner join cgm     on  divida.v01_numcgm = cgm.z01_numcgm
		          inner join proced  on  divida.v01_proced = proced.v03_codigo 
            where v14_certid={$certid} 
              and arrecad.k00_hist <> 918 
            order by v01_exerc,v01_dtvenc,v01_coddiv";
            
  $sql02 = " select distinct 
                    v01_exerc,
                    arreinscr.k00_inscr ,
                    arrematric.k00_matric ,
                    v01_proced,v03_descr,
                    v01_numpre
	           from certdiv 
         	        inner join divida     on divida.v01_coddiv     = certdiv.v14_coddiv
		            inner join arrecad    on divida.v01_numpre     = arrecad.k00_numpre 
		                                 and divida.v01_numpar     = arrecad.k00_numpar
		            inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
                  		                 and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
		             left join arreinscr  on arreinscr.k00_numpre  = arrecad.k00_numpre
		             left join arrematric on arrematric.k00_numpre = arrecad.k00_numpre
		            inner join proced     on divida.v01_proced     = proced.v03_codigo 
              where v14_certid={$certid} 
              order by v01_exerc";
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
<center>
<table>
    <tr>
      <td  align='left'><b>RESUMO POR ANO E PROCEDÊNCIA</b></td>
    </tr>
<tr>
  <td>
<?
  
  $result02=db_query($sql02);
  $numrows02=pg_numrows($result02);
  echo " <table border='1';>   
           <tr>
				      <th nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_exerc\" align=\"center\">$Lv01_exerc</b></th>
				      <th nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_proced\" align=\"center\">$Lv01_proced</th>
				      <th nowrap bgcolor=\"#CDCDFF\" title=\"$Tv03_descr\" align=\"center\">$Lv03_descr</u></b></th>
				      <th nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_numpre\" align=\"center\">$Lv01_numpre</th>
				      <th title=\"Valor Histórico\" bgcolor=\"#CDCDFF\"   nowrap>Val His.</th>\n
				      <th title=\"Valor Corrigido\" bgcolor=\"#CDCDFF\"   nowrap>Val Cor.</th>\n
				      <th title=\"Valor Juros\" bgcolor=\"#CDCDFF\"  nowrap>Jur.</th>\n
				      <th title=\"Valor Multa\" bgcolor=\"#CDCDFF\"  nowrap>Mul.</th>\n
				      <th title=\"Valor Desconto\" bgcolor=\"#CDCDFF\"  nowrap>Desc.</th>\n                  
				      <th title=\"Total a Pagar\" bgcolor=\"#CDCDFF\"  nowrap>Tot.</th>\n  
           </tr>";
	for ($i=0; $i<$numrows02; $i++) {
		  	
		$lrhis='';
		$lrcor='';
		$lrjuros='';
		$lrmulta='';
		$lrdesconto='';
		$otal='';
		db_fieldsmemory($result02,$i);
		    

		$result05=debitos_numpre($v01_numpre,0,@$tipo,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,"",""," and y.k00_hist <> 918");
		$numrows05=pg_numrows($result05);	
		for ($d=0; $d<$numrows05; $d++) {
		   db_fieldsmemory($result05,$d);
					
			 $lrhis+=$vlrhis;
			 $lrcor+=$vlrcor;
			 $lrjuros+=$vlrjuros;
			 $lrmulta+=$vlrmulta;
			 $lrdesconto+=$vlrdesconto;
			 $otal+=$total;
		}	 
    
		if ($i%2==0) {
		  $color="#E796A4";
		} else { 
		  $color="#97B5E6";
		}  
    
		echo "<tr>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_exerc\" align=\"center\">$v01_exerc</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_proced\" align=\"center\">$v01_proced</td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv03_descr\" align=\"center\">$v03_descr</u></b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_numpre\" align=\"center\">$v01_numpre</td>
			      <td bgcolor=\"$color\"  title=\"Valor Histórico\" style=\"font-size:11px\" nowrap>$lrhis</td>\n
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
      <td  align='left'><b>DADOS GERAIS</b></td>
    </tr>
<tr>
<tr>
  <td>
<?
  $result=pg_query($sql);
  $numrows=pg_numrows($result);
    echo "
   <table border='1';>   
    <tr>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_coddiv\" align=\"center\">$Lv01_coddiv</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tk00_inscr\" aling=\"center\">$Lk00_inscr</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tk00_matric\" aling=\"center\">$Lk00_matric</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tz01_nome\" align=\"center\">$Lz01_nome</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_dtinsc\" align=\"center\">$Lv01_dtinsc</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_exerc\" align=\"center\">$Lv01_exerc</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_numpre\" align=\"center\">$Lv01_numpre</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_numpar\" align=\"center\">$Lv01_numpar</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_proced\" align=\"center\">$Lv01_proced</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv03_descr\" align=\"center\">$Lv03_descr</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_numtot\" align=\"center\">$Lv01_numtot</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_numdig\" align=\"center\">$Lv01_numdig</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_vlrhis\" align=\"center\">$Lv01_vlrhis</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_obs\" align=\"center\">$Lv01_obs</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_livro\" align=\"center\">$Lv01_livro</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_folha\" align=\"center\">$Lv01_folha</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_dtvenc\" align=\"center\">$Lv01_dtvenc</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_dtoper\" align=\"center\">$Lv01_dtoper</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_valor\" align=\"center\">$Lv01_valor</b></td>
	    <td nowrap bgcolor=\"#CDCDFF\" title=\"$Tv01_numpre\" align=\"center\">$Lv01_numpre</td>
	    <th title=\"Valor Histórico\" bgcolor=\"#CDCDFF\"   nowrap>Val His.</th>\n
	    <th title=\"Valor Corrigido\" bgcolor=\"#CDCDFF\"   nowrap>Val Cor.</th>\n
	    <th title=\"Valor Juros\" bgcolor=\"#CDCDFF\"  nowrap>Jur.</th>\n
	    <th title=\"Valor Multa\" bgcolor=\"#CDCDFF\"  nowrap>Mul.</th>\n
	    <th title=\"Valor Desconto\" bgcolor=\"#CDCDFF\"  nowrap>Desc.</th>\n                  
	    <th title=\"Total a Pagar\" bgcolor=\"#CDCDFF\"  nowrap>Tot.</th>\n  
    </tr>";
    
   for ($i=0; $i<$numrows; $i++) {
     db_fieldsmemory($result,$i);
     $result05=debitos_numpre($v01_numpre,0,$tipo,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$v01_numpar);
     db_fieldsmemory($result05,0);
    
	    if ($i%2==0) {
	      $color="#E796A4";
	    } else { 
	      $color="#97B5E6";
	    }
      
    echo "
			    <tr>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_coddiv\" align=\"center\">$v01_coddiv</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tk00_inscr\" aling=\"center\">$k00_inscr</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tk00_matric\" aling=\"center\">$k00_matric</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tz01_nome\" align=\"center\">$z01_nome</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_dtinsc\" align=\"center\">$v01_dtinsc</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_exerc\" align=\"center\">$v01_exerc</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_numpre\" align=\"center\">$v01_numpre</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_numpar\" align=\"center\">$v01_numpar</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_proced\" align=\"center\">$v01_proced</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv03_descr\" align=\"center\">$v03_descr</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_numtot\" align=\"center\">$v01_numtot</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_numdig\" align=\"center\">$v01_numdig</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_vlrhis\" align=\"center\">$v01_vlrhis</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_obs\" align=\"center\">$v01_obs</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_livro\" align=\"center\">$v01_livro</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_folha\" align=\"center\">$v01_folha</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_dtvenc\" align=\"center\">$v01_dtvenc</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_dtoper\" align=\"center\">$v01_dtoper</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_valor\" align=\"center\">$v01_valor</b></td>
			      <td nowrap bgcolor=\"$color\" title=\"$Tv01_numpre\" align=\"center\">$v01_numpre</td>
			      <td bgcolor=\"$color\"  title=\"Valor Histórico\" style=\"font-size:11px\" nowrap>$vlrhis</td>\n
			      <td bgcolor=\"$color\"  title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>$vlrcor</td>\n
			      <td bgcolor=\"$color\"  title=\"Valor Juros\" style=\"font-size:11px\" nowrap>$vlrjuros</td>\n
			      <td bgcolor=\"$color\"  title=\"Valor Multa\" style=\"font-size:11px\" nowrap>$vlrmulta</td>\n
			      <td bgcolor=\"$color\"  title=\"Valor Desconto\" style=\"font-size:11px\" nowrap>".db_formatar($vlrdesconto,'f')."</td>\n 
			      <td bgcolor=\"$color\"  title=\"Total a Pagar\" style=\"font-size:11px\" nowrap>".db_formatar($total,'f')."</td>\n  
			    </tr>";
   }   
   
  echo "</table>";   
?>
  </td>
</tr>  
</table>
</center>
</body>
</html>
<script>
  function js_certter(certid){
      js_OpenJanelaIframe('top.corpo','db_iframe2','cai3_gerfinanc041.php?certid='+certid,'Pesquisa',true);
  }
  function js_certdiv(certid){
      js_OpenJanelaIframe('top.corpo','db_iframe2','cai3_gerfinanc041.php?certid='+certid,'Pesquisa',true);
  }
</script>