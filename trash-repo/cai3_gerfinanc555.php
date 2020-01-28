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
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>Descritivo do Parcelamento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 11px;
  	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
th {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 11px;
  	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
-->
</style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
 
 
 
    <?
   if(1==1){
   
    $sql = "select a.k00_numpre,
	                k00_numpar, 
					k00_numtot,
					k00_dtoper,
					k00_dtvenc,
					k00_receit,
					k02_drecei,
					k00_valor,
					k00_hist,
					k01_descr
	        from arrecad a
      				 inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre
                           				  and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
	             left outer join arrematric on arrematric.k00_numpre = a.k00_numpre
	             left outer join arreinscr on arreinscr.k00_numpre = a.k00_numpre
	             ,tabrec inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
		     ,histcalc 
	        where a.k00_numpre = ".$numpre." and
				  k02_codigo   = k00_receit and
				  k01_codigo   = k00_hist
			";
    if($numpar != 0){
       $sql .= " and k00_numpar = $numpar";
    }
    $js_func = "";
    db_lovrot($sql,5,"()","",$js_func);    
 }else{
 ?>
 
 
 
  <table width="100%" border="0" cellspacing="0" cellpadding="3">  
   <tr bgcolor="#FFCC66">
      <th width="10%" nowrap>Numpre</th>
      <th width="5%" nowrap>Par</th>
      <th width="5%" nowrap>Tot</th>
      <th width="9%" nowrap>Dt. Lanc.</th>
      <th width="10%" nowrap>Dt. Venc.</th>
      <th width="8%" nowrap>Hist</th>
      <th width="12%" nowrap>Descri&ccedil;&atilde;o</th>
      <th width="9%" nowrap>Receita</th>
      <th width="17%" nowrap>Descri&ccedil;&atilde;o</th>
      <th width="15%" nowrap>Valor</th>
    </tr>       
    <?
    $sql = "select a.k00_numpre,
	                k00_numpar, 
					k00_numtot,
					k00_dtoper,
					k00_dtvenc,
					k00_hist,
					k00_receit,
					k02_drecei,
					k01_descr,
					k00_valor
	        from arrecad a
               inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre 
							                      and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
	             left outer join arrematric on arrematric.k00_numpre = a.k00_numpre
	             left outer join arreinscr on arreinscr.k00_numpre = a.k00_numpre
	             ,tabrec inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
		     ,histcalc 
	        where a.k00_numpre = ".$numpre." and
				  k02_codigo   = k00_receit and
				  k01_codigo   = k00_hist
			";
    if($numpar != 0){
       $sql .= " and k00_numpar = $numpar";
    }
	$dados = pg_exec($sql);
    $ConfCor1 = "#EFE029";
    $ConfCor2 = "#E4F471";
	$numpre_cor = "";
	$numpre_par = "";
	$qcor= $ConfCor1;
    if(pg_numrows($dados)>0){
      for($x=0;$x<pg_numrows($dados);$x++){
	    db_fieldsmemory($dados,$x,"1");
        if($numpre_cor==""){
		   $numpre_cor = $k00_numpre;
		   $numpre_par = $k00_numpar;
	    }
	  if($numpre_cor != $k00_numpre || $numpre_par != $k00_numpar ){
         $numpre_cor = $k00_numpre;
		 $numpre_par = $k00_numpar;
         if($qcor == $ConfCor1)
		    $qcor = $ConfCor2;
		 else $qcor = $ConfCor1;
	  }
	  ?>	  	  
	   <tr bgcolor="<?=$qcor?>">
         <td width="10%" nowrap align="right" > <?=$k00_numpre?></td>
         <td width="5%" nowrap align="right" ><?=$k00_numpar?></td>
         <td width="5%" nowrap align="right"><?=$k00_numtot?></td>
         <td width="9%" nowrap><?=$k00_dtoper?></td>
         <td width="10%" nowrap><?=$k00_dtvenc?> </td>
         <td width="8%" nowrap align="right"><?=$k00_hist?></td>
         <td width="12%" nowrap><?=$k01_descr?></td>
         <td width="9%" nowrap align="center"> <?=$k00_receit?> </td>
         <td width="17%" nowrap><?=$k02_drecei?></td>
         <td width="15%" nowrap align="right"> <?=db_formatar(db_formatar($k00_valor,"v")*-1,"f")?> </td>
      </tr>	  	      
    <?
  	  }
    }
    ?>
  </table>
 <?
 }
 ?>
</body>
</html>