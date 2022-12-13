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
  include("classes/db_editalrua_classe.php");
  include("classes/db_edital_classe.php");
  include("classes/db_editalserv_classe.php");
  include("classes/db_contrib_classe.php");
  include("classes/db_contricalc_classe.php");
  include("classes/db_iptubase_classe.php");
  include("classes/db_contlotv_classe.php");
  include("libs/db_sql.php");
  $cleditalrua = new cl_editalrua;
  $clcontlotv = new cl_contlotv;
  $cliptubase = new cl_iptubase;
  $cledital = new cl_edital;
  $cleditalserv = new cl_editalserv;
  $clcontrib = new cl_contrib;
  $clcontricalc = new cl_contricalc;
  $clrotulo = new rotulocampo;
  $cleditalrua->rotulo->label(); 
  $cledital->rotulo->label(); 
  $cleditalserv->rotulo->label(); 
  $clrotulo->label('d01_codedi');
  $clrotulo->label('d01_numero');
  $clrotulo->label('d02_contri');
  $clrotulo->label('j14_nome');
  $clrotulo->label('k02_descr');
  $clrotulo->label('d07_vlrdes');
  $clrotulo->label('d07_valor');
  $clrotulo->label('k00_numpre');
  $clrotulo->label('j14_nome');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
.db_area {
  font-family : courier; 
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
  db_postmemory($HTTP_GET_VARS);
///////////////////////contribuição//////////////////////////////////////////////////////////////////////////////
if ($solicitacao == "contri") {
    $result01=$cleditalrua->sql_record($cleditalrua->sql_query($contri,"d02_autori,j14_nome,d02_profun"));
    db_fieldsmemory($result01,0);
?>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr> 
    <td align="left" width="15%" nowrap>
        <?=$Ld02_contri?>
    </td>
    <td align="left" nowrap>
        <?=$contri?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
        <?=$Lj14_nome?>
    </td>
    <td align="left" nowrap>
        <?=$j14_nome?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
	<?=$Ld02_autori?>
    </td>
    <td align="left" nowrap>
      <?=($d02_autori=="t"?"SIM":"NÃO")?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
        <?=$Ld02_profun?>
    </td>
    <td align="left" nowrap>
      <?=$d02_profun?> m
    </td>
  </tr>
</table>
<? 
   $result03=$cleditalserv->sql_record($cleditalserv->sql_query($contri,"","d04_tipos,d04_quant,d04_vlrcal,d04_vlrval,d03_descr"));
   $numrows03=$cleditalserv->numrows;
?>   
<table width="95%" border="1" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td align='center'><b>Serviços</b></td>
    <td align='center'><b>Quantidade em metros</b></td>
    <td align='center'><b>Valor em R$</b></td>
  </tr>
  <tr> 
    <?
     for($a=0; $a<$numrows03; $a++){
    	db_fieldsmemory($result03,$a);
       echo "<tr>"; 	
       echo "<td align='center'>$d03_descr</td>";
	 echo "<td align='center'>".db_formatar($d04_quant,'p')."</td>";
       echo "<td align='center'>".db_formatar($d04_vlrcal,'f')."</td>";
       echo "</tr>"; 	
     }  
    ?>
  </tr>
</table>
<?
 /////////////EDITAL///////////////////////////////////////////////////
 }else if ($solicitacao == "edital") {
    $result01=$cleditalrua->sql_record($cleditalrua->sql_query_file($contri,"d02_codedi"));
    db_fieldsmemory($result01,0);
    $result02=$cledital->sql_record($cledital->sql_query($d02_codedi,"d01_numero,d01_data,d01_perc,k02_descr,d01_perunica"));
    db_fieldsmemory($result02,0);
?>   
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr> 
    <td align="left" width="15%" nowrap>
        <?=$Ld01_codedi?>
    </td>
    <td align="left">
        <?=$d02_codedi?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
        <?=$Ld01_numero?>
    </td>
    <td align="left" nowrap>
        <?=$d01_numero?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
	<?=$Lk02_descr?>
    </td>
    <td align="left" nowrap>
      <?=$k02_descr?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
        <?=$Ld01_perc?>
    </td>
    <td align="left" nowrap>
      <?=$d01_perc?>%
    </td>
  </tr>
</table>
<?
 }else if ($solicitacao == "valores") {
    $result04=$clcontrib->sql_record($clcontrib->sql_query($contri,$matric,"d07_vlrdes,d07_valor"));
    if($clcontrib->numrows>0 ){
      db_fieldsmemory($result04,0);
      $result07=$clcontricalc->sql_record($clcontricalc->sql_query(null,"d09_numpre",null,"d09_contr = $contri and d09_matric = $matric"));
      if($clcontricalc->numrows>0 ){
        db_fieldsmemory($result07,0);
        $result09=debitos_numpre($d09_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,"1");
        if($result09!=false && pg_numrows($result09)>0){
            db_fieldsmemory($result09,0);
        }else{
            $total="0,00";
        }
        db_fieldsmemory($result09,0);
        $result08= pg_query("select sum(k00_valor) from arrepaga where k00_numpre = $d09_numpre");
        if(pg_numrows($result08)>0){
          db_fieldsmemory($result08,0);
        }else{
          $sum="0,00"; 	
        }
      }else{
        echo "<b>Não foram encontrados cálculos para esta matrícula</b>";
        exit;
     }   
    }else{
      echo "<b>Não foram encontrados cálculos para esta matrícula</b>";
      exit;
   }   
?>   
<table width="95%" border="1" align="center" cellpadding="0" cellspacing="2">
  <tr> 
    <td align="left" width="15%" nowrap>
	<?=$Ld07_valor?>
    </td>
    <td align="left" nowrap>
      <?=db_formatar($d07_valor,'f')?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
	<?=$Ld07_vlrdes?>
    </td>
    <td align="left" nowrap>
      <?=db_formatar($d07_vlrdes,'f')?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
      <b>Valor pago:</b>
    </td>
    <td align="left" nowrap>
      <?=db_formatar($sum,'f')?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
      <b>Valor devido:</b>
    </td>
    <td align="left" nowrap>
      <?=db_formatar($total,'f')?>
    </td>
  </tr>
  <tr> 
    <td align="left" width="15%" nowrap>
      <b><?=$Lk00_numpre?></b>
    </td>
    <td align="left" nowrap>
      <?=$d09_numpre?>
    </td>
  </tr>
</table>
<?
////////////////////OUTROS contribuições//////////////////////////////////////
 }else if ($solicitacao == "outras") {
      include("classes/db_contlot_classe.php");
      $clcontlot = new cl_contlot;
    $result05=$cliptubase->sql_record($cliptubase->sql_query_file($matric,"j01_idbql"));
    db_fieldsmemory($result05,0);
      $result10=$clcontlot->sql_record($clcontlot->sql_query("",$j01_idbql,"d01_numero,d01_codedi,d01_descr,d05_contri,j14_nome"));
      $numrows10=$clcontlot->numrows;
      if($numrows10>0){
	db_fieldsmemory($result10,0);
      }else{
	echo "<b>Não foram encontrados outras contribuições para esta matrícula</b>";
	exit;
      }  
  ?>   
  <script>
  function js_outra(contri,matric){
   js_OpenJanelaIframe('top.corpo','db_iframe2','con3_conscontri011.php?contri='+contri+'&cod_matricula='+matric,'Pesquisa',true);
  } 
  </script>
  <table width="95%" border="1" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td align='center'><b><?=(str_replace(":","",$Ld02_contri))?></b></td>
      <td align='center'><b><?=(str_replace(":","",$Lj14_nome))?></b></td>
      <td align='center'><b><?=(str_replace(":","",$Ld01_codedi))?></b></td>
      <td align='center'><b><?=(str_replace(":","",$Ld01_numero))?></b></td>
      <td align='center'><b><?=(str_replace(":","",$Ld01_descr))?></b></td>
    </tr>
    <tr> 
      <?
       for($a=0; $a<$numrows10; $a++){
	 db_fieldsmemory($result10,$a);
	 if($contri!=$d05_contri){
   	   echo "<tr  >"; 	
	   echo "<td align='center'><a href='#' onclick=\"js_outra('$d05_contri','$matric');\" >$d05_contri</a></td>";
	   echo "<td align='center'><a href='#' onclick=\"js_outra('$d05_contri','$matric');\" >$j14_nome</a></td>";
	   echo "<td align='center'><a href='#' onclick=\"js_outra('$d05_contri','$matric');\" >$d01_codedi</a></td>";
	   echo "<td align='center'><a href='#' onclick=\"js_outra('$d05_contri','$matric');\" >$d01_numero</a></td>";
	   echo "<td align='center'><a href='#' onclick=\"js_outra('$d05_contri','$matric');\" >$d01_descr</a></td>";
	   echo "</tr>"; 	
	 }  
       }  
      ?>
    </tr>
  </table>
  <?
   }else if ($solicitacao == "lote") {
    $result05=$cliptubase->sql_record($cliptubase->sql_query_file($matric,"j01_idbql"));
    db_fieldsmemory($result05,0);
    $result06=$clcontlotv->sql_record($clcontlotv->sql_query($contri,$j01_idbql,"","d03_descr,d06_fracao,d06_valor"));
    $numrows06=$clcontlotv->numrows;
    if($numrows06>0){
      db_fieldsmemory($result06,0);
    }else{
      echo "<b>Não foram encontrados lotes para esta matrícula</b>";
      exit;
    }  
?>   
<table width="95%" border="1" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td align='center'><b>Serviços</b></td>
    <td align='center'><b>Fração em metros</b></td>
    <td align='center'><b>Valor em R$</b></td>
  </tr>
  <tr> 
    <?
     for($a=0; $a<$numrows06; $a++){
       db_fieldsmemory($result06,$a);
       echo "<tr>"; 	
       echo "<td align='left'>$d03_descr</td>";
       echo "<td align='center'>".db_formatar($d06_fracao,'p')."</td>";
       echo "<td align='center'>".db_formatar($d06_fracao,'f')."</td>";
       echo "</tr>"; 	
     }  
    ?>
  </tr>
</table>
<?
 }
 ?>
</body>
</html>