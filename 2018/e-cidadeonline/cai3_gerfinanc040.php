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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
require("libs/db_stdlib.php");
include("classes/db_certid_classe.php");
require("libs/db_sql.php");
$clcertid = new cl_certid;
$clrotulo = new rotulocampo;
$clrotulo->label("v13_certid");
$clrotulo->label("v13_dtemis");
$clrotulo->label("v13_login");
if(isset($matric) and !empty($matric)){
  $tabela='arrematric';
  $campo='k00_matric';
  $valor=$matric;
}else if(isset($inscr) and !empty($inscr)){
  $tabela='arreinscr';
  $campo='k00_inscr';
  $valor=$inscr;
}else if(isset($numcgm) and !empty($numcgm)){
  $tabela='arrenumcgm';
  $campo='arrenumcgm.k00_numcgm';
  $valor=$numcgm;
}
if(isset($inicial)){
  $sql="
	  select  v14_certid as certid, 'd' as modo
		  from inicial
		  inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
		  inner join inicialcert on inicialcert.v51_inicial = inicial.v50_inicial
		  inner join certid on inicialcert.v51_certidao = certid.v13_certid
		  inner join divida on divida.v01_numpre = inicialnumpre.v59_numpre
		  inner join certdiv on  divida.v01_coddiv = certdiv.v14_coddiv
		  where v50_inicial = $inicial
 	  union
	  select  v14_certid as certter, 'p' as tipo 
                  from inicial
		  inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
		  inner join inicialcert on inicialcert.v51_inicial = inicial.v50_inicial
		  inner join certid on inicialcert.v51_certidao = certid.v13_certid
		  inner join termo on termo.v07_numpre = inicialnumpre. v59_numpre
		  inner join certter on certter.v14_parcel = termo.v07_parcel 
		  where v50_inicial = $inicial
  
       ";   
}else{  
   $sql="
	  select  v14_certid as certid, 'd' as modo
		  from $tabela
		  inner join arrecad on $tabela.k00_numpre = arrecad.k00_numpre 
		  inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
		  inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo
		  inner join divida on divida.v01_numpre = arrecad.k00_numpre and divida.v01_numpar = arrecad.k00_numpar 
		  inner join certdiv on  divida.v01_coddiv = certdiv.v14_coddiv
		  where $campo = $valor and cadtipo.k03_tipo = 15
 	  union
	  select  v14_certid as certter, 'p' as tipo 
                  from $tabela
		  inner join arrecad on $tabela.k00_numpre = arrecad.k00_numpre 
		  inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
		  inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo
		  inner join termo on termo.v07_numpre = arrecad.k00_numpre 
		  inner join certter on certter.v14_parcel = termo.v07_parcel 
		  where $campo = $valor and cadtipo.k03_tipo = 15
  ";

}
   $result = db_query($sql);
   $numrows= pg_numrows($result); 
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<style type="text/css">
.borda {
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<form name="form1" method="post" action="">
<table>
<?
  if($numrows>0){
    echo "
          <tr bgcolor=\"#FFCC66\">   \n  	
            <th class=\"borda\" style=\"font-size:11px\" nowrap>O</td>\n  	  
            <th class=\"borda\" style=\"font-size:11px\" nowrap>$Lv13_certid</th>\n  	  
            <th class=\"borda\" style=\"font-size:11px\" nowrap>$Lv13_dtemis</th>\n  	  
            <th class=\"borda\" style=\"font-size:11px\" nowrap>$Lv13_login</th>\n
            <th class=\"borda\" style=\"font-size:11px\" nowrap align='center'><b>Tipo</b></th>\n
	    <th class=\"borda\" title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>Val His.</th>\n
	    <th class=\"borda\" title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>Val Cor.</th>\n
	    <th class=\"borda\" title=\"Valor Juros\" style=\"font-size:11px\" nowrap>Jur.</th>\n
	    <th class=\"borda\" title=\"Valor Multa\" style=\"font-size:11px\" nowrap>Mul.</th>\n
	    <th class=\"borda\" title=\"Valor Desconto\" style=\"font-size:11px\" nowrap>Desc.</th>\n                  
	    <th class=\"borda\" title=\"Total a Pagar\" style=\"font-size:11px\" nowrap>Tot.</th>\n  
	  </tr>  
	    ";	  
    for($i=0; $i<$numrows; $i++){
      if($i%2==0){
	$color='"#E4F471"';
      }else{
	$color='"#EFE029"';
      }
      db_fieldsmemory($result,$i);
      if($modo=="d"){
	$certid = $certid;
	$mododescr='Dívida ativa';
	$funcao="js_certdiv('$certid');";
      }else if($modo=="p"){
	$certid = $certid;
	$mododescr='Parcelamento';
	$funcao="js_certter('$certid');";
      }else{
	  echo 'erro';
      }
       $result02=$clcertid->sql_record($clcertid->sql_query($certid));      
       db_fieldsmemory($result02,0);
//calcular valores da certidão
      if($modo=="d"){
         $sql04 = " 
           select distinct  v01_numpre as numpre
  	     from certdiv 
         	inner join divida  on  divida.v01_coddiv = certdiv.v14_coddiv
             where   v14_certid=$certid 		 
         ";
      }else if($modo=="p"){
         $sql04 = " 
           select distinct  v07_numpre as numpre
  	     from certter 
         	inner join termo  on  termo.v07_parcel = certter.v14_parcel
             where   v14_certid=$certid 		 
         ";
      }
   $result04=db_query($sql04); 	
   $numrows04=pg_numrows($result04);	
   
   $lrhis='';
   $lrcor='';
   $lrjuros='';
   $lrmulta='';
   $lrdesconto='';
   $otal='';
   for($r=0; $r<$numrows04; $r++){
     db_fieldsmemory($result04,$r);
//     echo "numpre: $numpre - $tipo\n<br>";
     $result05=debitos_numpre($numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"));
     if ($result05 != false) {
       $numrows05=pg_numrows($result05);
     } else {
       $numrows05=0;
     }
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
   $sqllogin = "select login from db_usuarios where login = '$v13_login'";
   $resultlogin = db_query($sqllogin);
   if (pg_numrows($resultlogin) > 0) {
     db_fieldsmemory($resultlogin,0);
   } else {
     $login = "";
   }
       echo "
          <tr bgcolor=$color>\n  	
            <td class=\"borda\" style=\"font-size:11px\" nowrap><a href='#' onclick=\"$funcao return false;\">MI</a></td>\n  	  
            <td class=\"borda\" style=\"font-size:11px\" nowrap>$v13_certid</td>\n  	  
            <td class=\"borda\" style=\"font-size:11px\" nowrap>$v13_dtemis</td>\n  	  
            <td class=\"borda\" style=\"font-size:11px\" nowrap>$login</td>\n
            <td class=\"borda\" style=\"font-size:11px\" nowrap>$mododescr</td>\n
	    <td class=\"borda\" title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>$lrhis</td>\n
	    <td class=\"borda\" title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>$lrcor</td>\n
	    <td class=\"borda\" title=\"Valor Juros\" style=\"font-size:11px\" nowrap>$lrjuros</td>\n
	    <td class=\"borda\" title=\"Valor Multa\" style=\"font-size:11px\" nowrap>$lrmulta</td>\n
	    <td class=\"borda\" title=\"Valor Desconto\" style=\"font-size:11px\" nowrap>".db_formatar($lrdesconto,'f')."</td>\n                  
	    <td class=\"borda\" title=\"Total a Pagar\" style=\"font-size:11px\" nowrap>".db_formatar($otal,'f')."</td>\n  
	  </tr>  
	    ";	  

    }
  }else{
?>
  <tr><td><small>Nenhum registro encontrado</small></td></tr> 
<?
  }
?>
</table>
</form>
</center>
</body>
</html>
<script>
  function js_certter(certid){
      js_OpenJanelaIframe('top.corpo','db_iframe2','cai3_gerfinanc042.php?certid='+certid+'&tipo=<?=$tipo?>','Pesquisa',true);
  }
  function js_certdiv(certid){
      js_OpenJanelaIframe('top.corpo','db_iframe2','cai3_gerfinanc041.php?certid='+certid+'&tipo=<?=$tipo?>','Pesquisa',true);
  }
</script>