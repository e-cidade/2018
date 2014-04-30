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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_empempenho_classe.php");
include("classes/db_pagordem_classe.php");
include("classes/db_empparametro_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempempenho = new cl_empempenho;
$clpagordem   = new cl_pagordem;
$clempparametro	  = new cl_empparametro;

$where = "";
$where_sql = "";
$sql1="";
if(isset($newsql) && $newsql=="true"){
  $funcao_js="''";
  $sql1 = "select distinct fc_estruturaldotacao(".db_getsession("DB_anousu").",o58_coddot) as dl_estrutural,o55_descr::text,o56_descr,o58_coddot
           from empempenho 
	        inner join orcdotacao  d on d.o58_anousu = empempenho.e60_anousu and d.o58_coddot = empempenho.e60_coddot
                inner join orcprojativ p on p.o55_anousu = ".db_getsession("DB_anousu")." and p.o55_projativ = d.o58_projativ
                inner join orcelemento e on e.o56_codele = d.o58_codele and e.o56_anousu = d.o58_anousu
	   where orcdotacao.o58_anousu = ".db_getsession("DB_anousu")."
	     and e60_instit = ".db_getsession("DB_instit");
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="1"  align="center" cellspacing="0" bgcolor="#CCCCCC">
   <!--- filtro --->
  <form name=form1  action="" method=POST>
  <tr><td valign=top>
  <table border=0 align=center>
     <tr> 
       <td align="center" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empconsulta002.hide();">
        <!--
        <input name="Imprimir" type="button" id="imp" value="Imprimir esta tela" onClick = "js_emite();">
        -->
        <input name="Todos" type="button" id="imptd" value="Imprimir todos" onClick = "parent.js_consulta002('<?=$HTTP_SERVER_VARS["QUERY_STRING"]?>');">
       </td>
     </tr>  
    <!--    
     <tr>
     <td align="center" nowrap wrap="false"> 
      Período:
      <?  db_inputdata('dt1',@$dia,@$mes,@$ano,true,'text',1,"");   		          
          echo " a ";
          db_inputdata('dt2',@$dia,@$mes,@$ano,true,'text',1,"");
       ?>
     </td>
     </tr> 
     <tr><td align=center><input type=submit value=Filtrar>  </td></tr>
     -->
  </form>
  </table>
  </td>
  </tr>
  <!---  end filtro --->
  <tr> 
    <td align="center" valign="top"> 
      <?//---
        $data1=0;
        $data2=0;
        @$data1= "$dt1_ano-$dt1_mes-$dt1_dia";
        @$data2= "$dt2_ano-$dt2_mes-$dt2_dia";
	if (strlen($data1) < 3){
           unset($data1);
	}  
	if (strlen($data2) < 3){
           unset($data2);
	}  
        //--
	$campos=" distinct e60_numemp,e60_codemp,e60_emiss,e60_vencim,z01_nome,e60_vlremp,e60_vlrliq,e60_vlrpag,e60_vlranu";
	$sql = "";
	if (isset($e60_numemp) and $e60_numemp!=""){  
	   ?><script> parent.js_consulta003('<?=$e60_numemp ?>'); </script> <?
		 flush();
	   exit;
  	}  
    if (isset($e60_codemp) and $e60_codemp != "" ) { 	  
	  $arr = split("/",$e60_codemp);
	  if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
	  	$where_sql .= 'e60_codemp =  '.$arr[0]." and e60_anousu = ".$arr[1]." and ";
	  }else{
	  	$where_sql .= 'e60_codemp =  '.$arr[0]." and e60_anousu = ".db_getsession("DB_anousu")." and ";
	  }	  
	}  

if (isset ($o58_coddot) and $o58_coddot != "") {
	$where .= "o58_coddot=$o58_coddot and";
	$where_sql .= "o58_coddot=$o58_coddot and ";
}
if (isset ($pc01_codmater) and $pc01_codmater != "") {
	$where_sql .= "pc01_codmater = $pc01_codmater and ";
}
if (isset ($z01_numcgm) and $z01_numcgm != "") {
	$where_sql .= "e60_numcgm = $z01_numcgm and ";
}
if (isset($e53_codord) and $e53_codord !=""){
	$where_sql .= "e50_codord = $e53_codord and ";
}
$result02 = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_permconsempger"));
if($clempparametro->numrows>0){
  db_fieldsmemory($result02,0);
} else {
  $e30_permconsempger = 't';
}

$clpermusuario_dotacao =  new cl_permusuario_dotacao( db_getsession('DB_anousu'), db_getsession('DB_id_usuario'), null, null, null);

$sqldot = " 1=1 ";

if ($e30_permconsempger == 'f') {
  if ($clpermusuario_dotacao->sql != "") {
    $sqldot = " e60_coddot in (select distinct o58_coddot from (" . $clpermusuario_dotacao->sql . ") as xxx) ";
  } else {
    $sqldot = " e60_coddot in (0) ";
  }
}

$sql = $clempempenho->sql_query(null, $campos, null, " $where_sql $sqldot and e60_instit = ".db_getsession("DB_instit"));
if ((isset ($dt1) and $dt1 != "") and (isset ($dt2) and $dt2 != "")){
	$sql = $clempempenho->sql_query(null, $campos, null, "$where_sql e60_emiss between '$dt1' and '$dt2' and e60_instit = ".db_getsession("DB_instit"));	
}
if (isset($pc01_codmater) and $pc01_codmater !=""){
   $sql = $clempempenho->sql_query_itemmaterial(null,$campos,null," $where_sql e60_instit = ".db_getsession("DB_instit"));
   if ((isset ($dt1) and $dt1 != "") and (isset ($dt2) and $dt2 != "")){
      $sql = $clempempenho->sql_query_itemmaterial(null, $campos, null, "$where_sql e60_emiss between '$dt1' and '$dt2' and e60_instit = ".db_getsession("DB_instit"));
   }  
}

	if(isset($o50_estrutdespesa) && $o50_estrutdespesa!=""){
	  $matriz=split('\.',$o50_estrutdespesa);
	  for($i=0; $i<count($matriz); $i++){
	    switch($i){
	      case 0://orgao
		    $o40_orgao = $matriz[$i];
		    break;
	      case 1://unidade
		    $o41_unidade = $matriz[$i];
		    break;
	      case 2://funcao
		    $o52_funcao = $matriz[$i];
		    break;
	      case 3://subfuncao	
		    $o53_subfuncao = $matriz[$i];
		    break;
	      case 4://programa
		    $o54_programa = $matriz[$i];
		    break;
	      case 5://projativ
		    $o55_projativ = $matriz[$i];
		    break;
	      case 6://elemento de despesa	
		    $o56_elemento = $matriz[$i];
		    break;
	      case 7://tipo de  recurso
		    $o58_codigo = $matriz[$i];
		    break;
	    } 
	  }
	} 
       if(!empty($o40_orgao)){
	 $where .= " and o58_orgao = $o40_orgao ";
       }
       if(!empty($o41_unidade)){
	 if($where!="")
	 $where .= " and o58_unidade = $o41_unidade ";
       }
       if(!empty($o52_funcao)){
	 $where .= " and o58_funcao = $o52_funcao ";
       }
       if(!empty($o53_subfuncao)){
	 $where .= " and o58_subfuncao = $o53_subfuncao ";
       }
       if(!empty($o54_programa)){
	 $where .= " and o58_programa = $o54_programa ";
       }
       if(!empty($o55_projativ)){
	 $where .= " and o58_projativ = $o55_projativ ";
       }
       if(!empty($o56_elemento)){
	 $where .= " and o58_elemento = $o56_elemento ";
       }
       if(!empty($o58_codigo)){
	 $where .= " and o58_codigo = $o58_codigo ";
       }       
       $sql1 = $sql1.$where;
       if (isset($e53_codord) and $e53_codord !=""){
	 $where_sql .= "e50_codord = $e53_codord and ";
         if (isset($e53_codord) and $e53_codord !=""){
	   $sql = $clpagordem->sql_query(null,$campos,null," e50_codord = $e53_codord and e60_instit = ".db_getsession("DB_instit"));
         }
       }
      ?>
      <script> sql= "<?=$sql?>"; </script>
      <? 
       if(isset($newsql) && $newsql=="true"){
        db_lovrot($sql1,15,"()","","js_abre|o58_coddot");
       }else{
        db_lovrot($sql,15,"()","",$funcao_js);
       }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>
function js_abre(coddot){
  js_OpenJanelaIframe('top.corpo','db_iframe_orgao','func_saldoorcdotacao.php?coddot='+coddot,'pesquisa',true);
}
function js_emite(){
     e60_codemp="<?=$e60_codemp?>";
     e60_numemp ="<?=$e60_numemp?>";
     o58_coddot  ="<?=$o58_coddot?>";
     pc01_codmater ="<?=$pc01_codmater?>";
     e60_numemp ="<?=$e60_numemp?>";
     z01_numcgm ="<?=$z01_numcgm?>";
     dt1 ="<?=$dt1?>";
     dt2 ="<?=$dt2?>";
     e53_codord ="<?=$e53_codord?>";

  jantes = window.open('emp1_empconsulta004.php?dt1='+dt1+ '&dt2='+dt2+ '&o58_coddot='+o58_coddot+  '&e60_codemp='+e60_codemp+'&e60_numemp='+e60_numemp+'&e53_codord='+e53_codord+'&pc01_codmater='+pc01_codmater+'&z01_numcgm='+z01_numcgm,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jantes.moveTo(0,0);

}
</script>