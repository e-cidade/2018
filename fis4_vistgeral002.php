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
include("classes/db_tabrec_classe.php");
include("classes/db_proced_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arrematric_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_arreold_classe.php");
include("classes/db_divida_classe.php");
			
include("classes/db_divimporta_classe.php");
include("classes/db_divimportareg_classe.php");

include("classes/db_divold_classe.php");
include("dbforms/db_funcoes.php");
/*
db_postmemory($HTTP_POST_VARS,2);
db_postmemory($HTTP_SERVER_VARS,2);
exit;
*/
//die("where == ".$txt_where);
//die("inner == ".$txt_inner);

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
/*
Voce selecionou uma parcela, e naun todas as receitas da mesma parcela, Deseja continuar
com a importação?
*/

$cltabrec = new cl_tabrec;
$clarrecad = new cl_arrecad;
$clarrematric = new cl_arrematric;
$clarreinscr = new cl_arreinscr;
$clarreold = new cl_arreold;
$clproced = new cl_proced;
$cldivida = new cl_divida;

$cldivimporta = new cl_divimporta;
$cldivimportareg = new cl_divimportareg;

$cldivold = new cl_divold;
$teste=false;
$where = "";
$where2 = "";
$subselect = "";
$and = "";
$xnumpre = "";
$xnumpre2 = "";
$vir = "";
$hoje = $datavenc;
/*
//die ("select * from reg_a_importar".db_getsession("DB_id_usuario").db_getsession("DB_datausu"));
$sqlWhere = "select * from reg_a_importar".db_getsession("DB_id_usuario").db_getsession("DB_datausu").";";
$rsWhere = pg_query($sqlWhere) or die($sqlWhere);
//db_criatabela($rsWhere);exit;
$intContwhere = pg_numrows($rsWhere);

$whereimporta = "";
$or = " and ( ";*//*
for($cont=0;$cont<$intContwhere;$cont++){
	db_fieldsmemory($rsWhere,$cont);
//	echo " numpre = $numpre | numpar = $numpar | receita = $receita";
	$whereimporta .= " $or (arrecad.k00_numpre=$numpre and arrecad.k00_numpar=$numpar and arrecad.k00_receit=$receita) ";
	$or = "or";
}
$whereimporta .= " ) ";
$txt_where = $whereimporta;*/
//select count(*) from (select distinct k00_numpre,k00_numpar from arrecad where k00_numcgm = 34001 group by k00_numpre,k00_numpar)as zzz;
//
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function termo(qual,total){
  document.getElementById('termometro').innerHTML=' Processando registro... '+qual+' de '+total;
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="100%" height="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" width="100%" align="center" valign="top" bgcolor="#CCCCCC">
      <center>
      <form name="form1" method="post" action="">
      <table height="100%" width="100%"  border="0" cellspacing="5" cellpadding="0">
	    </td>
	    <td align="center">
	      <?if(isset($procreg) && $procreg!=""){?>
	           <input name="termometro" style='background: transparent' id="termometro" type="text" value="" size=50>
	      <?}else{
//		   echo "<script>document.getElementById('filtro').style.visibility='hidden';</script>";
		}?>
	    </td>
      
<?
$wherereceita = "";
if(isset($chave_origem) && trim($chave_origem)!="" && isset($chave_destino) && trim($chave_destino)!="" && $sub == 'f'){
//  db_msgbox("entrou chave");
   if(isset($procreg) && $procreg == 't'){
       $wherereceita = " and k00_receit in ($codreceita) and k00_valor <> 0 ";
   }
   if ($tipoparc == "a"){
     $venc = "";
   } else {
     $venc = " and k00_dtvenc < '".$hoje."'";
   }
   if ($tipoparc == "t") {
     $subsql = "select distinct k00_numpre 
                      from (select k00_numpre, 
		                   max(k00_dtvenc) 
		              from arrecad 
		            where k00_tipo = $chave_origem 
		                             $wherereceita 
		            group by k00_numpre) as xxx 
		where max < '$hoje'";
   } else {
     $subsql = " select distinct k00_numpre 
                       from arrecad 
		 where k00_tipo = $chave_origem 
		                  $venc 
				  $wherereceita ";
   }
// db_msgbox($txt_where);
  $sql0 = " select tabrec.k02_codigo,
                   tabrec.k02_drecei, 
         	       contrec
              from (select k00_receit, count(*) as contrec
	              from ($subsql) as x 
           		inner join arrecad on arrecad.k00_numpre = x.k00_numpre
				$txt_inner	
     		where 1=1 $venc 
			          $wherereceita 
					  ".(isset($txt_where)&&$txt_where!=""?$txt_where:"")."
	    	group by k00_receit) as y 
                 inner join tabrec on k02_codigo = y.k00_receit
	  ";
//  die($sql0);
  $result0 = $cltabrec->sql_record($sql0);
  $numrows0=$cltabrec->numrows;
  if($numrows0==0){
    echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
    echo "<script>
            parent.document.form1.gerar.disabled=true;
			    alert('Nenhum tipo de débito encontrado com este código');
          </script>";
    echo "<script>top.corpo.db_iframe.hide();</script>";
    echo "<script>top.corpo.location.href='div4_importadivida001.php'</script>";
  }
  $sql1 = " select v03_codigo, v03_descr from proced inner join tabrec on k02_codigo = v03_codigo ";
  $result1 = $clproced->sql_record($sql1);
  $numrows1 = $clproced->numrows;
  if(!isset($procreg) && isset($sub)){
	  $vir = "";
	  $cod_k02_codigo="";
	  $vir1 = "";
	  $cod_v03_codigo="";
	  echo "<div id='filtro' style='visibility:visible'>";
	  echo "<tr>
				<td nowrap align='center' valign='top'><b> Receita </b></td>
				<td nowrap align='center' valign='top'><b> Descrição </b></td>
				<td nowrap align='center' valign='top'><b> Procedência </b></td>
				<td nowrap align='center' valign='top'><b> Regist </b></td>
			</tr> ";
	  $totcontrec = 0;
	  for($i=0;$i<$numrows0;$i++){
		db_fieldsmemory($result0,$i);
		$cod_k02_codigo .= $vir.$k02_codigo;
		$vir = ",";
		echo "
		<tr>
		  <td nowrap align='left' valign='top'>";
			 db_input("$k02_codigo","8","",true,"text",3,"","k02_codigo"); 
		echo "</td>
			  <td nowrap> ";
					db_input("$k02_drecei",40,"",true,"text",3,"","k02_drecei");
		echo " </td> ";
		echo " <td>
			   <select name=\"v03_descr\" onchange=\"js_troca();\" id=\"v03_descr\">
		   <option value=\"0\" >Escolha uma procedência</option> 
			 ";
		for($ii=0;$ii<$numrows1;$ii++){
		  db_fieldsmemory($result1,$ii);
		  echo " <option value=\"$v03_codigo\" >$v03_codigo - $v03_descr</option> ";
		  if($ii == 0){
		$cod_v03_codigo .= $vir1.$v03_codigo;
		$vir1=",";
		  }
		}
		echo "
		</select>
		  </td>
		  <td nowrap align='left' valign='top'>";
			 db_input("$contrec","10","",true,"text",3,"","contrec"); 
			 $totcontrec += $contrec;
	  }
      echo "<tr>
		   <td nowrap align='center' valign='top'><b>  </b></td>
		   <td nowrap align='center' valign='top'><b>  </b></td>
		   <td nowrap align='right' valign='top'><b>Total de registros : </b></td>
		   <td nowrap align='left' valign='top'><b>$totcontrec </b></td>
		</tr> ";
	  echo "</div>";
   }
//  db_input("datasvenc",40,"0",true,"text",3);
/**//**//**//**/
}
/**//**//**//**/

/********************************************************************************/
/*   => se marcou para unificar os debitos em um unico registro na divida 
        esse input guarda a data de vencimento q sera gravado na divida  */

  if (isset($uni) && $uni == "p"){
      echo "<b> Data do vencimento das dividas : </b>";
      db_inputdata("dtvencuni","","","",true,'text',1,"js_validadata();"); 
  }
/*********************************************************************************/
  db_input("uni",40,"0",true,"hidden",3);
  db_input("chave_origem",40,"0",true,"hidden",3);
  db_input("chave_destino",40,"0",true,"hidden",3);
  db_input("cod_k02_codigo",40,"0",true,"hidden",3);
  db_input("cod_v03_codigo",40,"0",true,"hidden",3); 
  
  db_input("codreceita",40,"0",true,"hidden",3); 
  db_input("tipodata",40,"0",true,"hidden",3); 
  db_input("sub",40,"0",true,"hidden",3);   
  db_input("txt_where",40,"0",true,"hidden",3);
  db_input("txt_inner",40,"0",true,"hidden",3);
//  db_msgbox($sub);
  if(isset($sub) && $sub == 'f'){
	  db_input("procreg",40,"0",true,"hidden",3); 
  }
  echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
/*echo"<script>
          eval("document.form1.dtvenc_"+i+"_dia.style.visibility='visibled'");
          eval("document.form1.dtvenc_"+i+"_mes.style.visibility='visibled'");
          eval("document.form1.dtvenc_"+i+"_ano.style.visibility='visibled'");
          document.getElementById(eval("parc_"+i)).style.visibility='hidden';
     </script>
     ";*/
//}
?>
      </table>
      </form>
	  
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
function js_troca(){
  vir='';
  pass='f';
  codigo='';
  codreceit='';
  cont=0;
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type=="select-one"){
      if(document.form1.elements[i].value!=0){	
        codigo += vir+document.form1.elements[i].value;
        codreceit += vir+document.form1.elements[i-2].value;
        vir=',';
		pass = 't';
      }else{
		cont++;
      }      
    }
  }
  if(pass=='t'){
    parent.document.form1.gerar.disabled=false;
  }else{
    parent.document.form1.gerar.disabled=true;
  }
  document.form1.cod_v03_codigo.value = codigo;
  document.form1.codreceita.value = codreceit;
}
function js_validadata(){
//   alert("entrou");
   if (document.form1.dtvencuni_dia == '' || document.form1.dtvencuni_mes == '' || document.form1.dtvencuni_ano == '' ){
        alert("preencha a data!!");
   } 
}
</script>
<?

/*************************************************************************************************************************/
/*************************   A Q U I   I M P O R T A   O S   R E G I S T R O S   *****************************************/
/*************************************************************************************************************************/

//   db_msgbox("chegou");
if(isset($procreg) && $procreg == 't'){
  if(isset($cod_k02_codigo) && trim($cod_k02_codigo)!="" && isset($cod_v03_codigo) && trim($cod_v03_codigo)!=""){
    $teste=true;
//  testa se esta setada a variavel que define se devem ou naun ser
//  unificadas as parcelas em um unico registro na divida
//  db_msgbox($uni);
    if (isset($uni) && $uni == "p"){
//      db_msgbox("uni == p");
        $ek00_numpar = "";  
        $andnumpar  = "";  
        $excnumpar  = "";
    	$dtvencunidiv = $dtvencuni_ano."-".$dtvencuni_mes."-".$dtvencuni_dia;
    }else{
    	$ek00_numpar = " k00_numpar, "; 
        $andnumpar  = ' and k00_numpar = $k00_numpar ';
        $excnumpar  = ' and k00_numpar = $k00_numpar_exc '; 
    }                                                      
                                                           
/******************************************************************/

//  db_msgbox($dtvencunidiv);
//  db_msgbox(eval($excnumpar));
    $sql_pesq = "
				  select x.k00_numpre,
						 $ek00_numpar
	                	 k00_receit,
					     sum(k00_valor) as val
				     from ($subsql) as x
					  inner join arrecad on arrecad.k00_numpre = x.k00_numpre
				      $txt_inner	
				  where 1=1 $venc $wherereceita ".(isset($txt_where)&&$txt_where!=""?$txt_where:"")."
				  group by x.k00_numpre,
				           $ek00_numpar
						   k00_receit
				  order by x.k00_numpre,
				     	   $ek00_numpar
					       k00_receit ";
//  die($sql_pesq);
    $result_pesq_divida = pg_query($sql_pesq);
    $numrows = pg_numrows($result_pesq_divida);
    if (isset($numrows) && $numrows == 0 ){
	 db_msgbox("Nenhum registro para o filtro selecionado !");	
    }
    $codigo_k02 = split(",",$codreceita);
    $codigo_v03 = split(",",$cod_v03_codigo);
    $sqlerro=false;
   
    db_inicio_transacao();
    $numpre_par_rec = "";  
    echo "<script>document.getElementById('filtro').style.visibility='hidden';</script>";
    $perc100 = $numrows / 100;
    $percatual = 0;
    $perc = $perc100;
	
//////////////////// GRAVA O LOTE DE IMPORTAÇÃO DE DIVIDA  (TABELA divimporta) ////////////////////////    
   $cldivimporta->v02_usuario    =  db_getsession("DB_id_usuario");
   $cldivimporta->v02_data       =  date("Y-m-d",db_getsession("DB_datausu")); 
   $cldivimporta->v02_hora       =  db_hora(); 
   $cldivimporta->incluir(null); 
   if($cldivimporta->erro_status=='0'){
	  $erro_msg = $cldivimporta->erro_msg."--- Inclusão divimporta";
      $sqlerro=true;
   }
/////////////////////////////////////////////////////////////////////////////////////   
    for($i=0;$i<$numrows;$i++){
//    echo "Processando registro ".($i+1)." de $numrows<BR>";
      db_fieldsmemory($result_pesq_divida,$i,true);
      $rsArrecad = pg_query(" select * from arrecad 
                                 where k00_numpre = $k00_numpre 
				   ".(isset($$andnumpar)&&$$andnumpar!=""?$$andnumpar:"")."
				   and k00_receit = $k00_receit 
				   and k00_valor > 0 limit 1 ");
      db_fieldsmemory($rsArrecad,0,true);
//    db_criatabela($rsArrecad);
//    exit;
      
      if ($perc++ >= $perc100) {
		  echo "<script>termo($percatual, 100);</script>";
		  flush();
		  $percatual++;
		  $perc = 0;
      }
       
  for($ii=0;$ii<sizeof($codigo_v03);$ii++){
	$cod_k02_codigo = $codigo_k02[$ii];
	$cod_v03_codigo = $codigo_v03[$ii];

	if($k00_receit == $cod_k02_codigo && $sqlerro==false){
	  if ($numpre_par_rec <> str_pad($k00_numpre,10,"0",STR_PAD_LEFT) . str_pad($k00_receit,5,"0",STR_PAD_LEFT)){
	    $nextval_numpre=pg_exec("select nextval('numpref_k03_numpre_seq') as numpre_novo");
	    db_fieldsmemory($nextval_numpre,0);
	    $numpre_par_rec = str_pad($k00_numpre,10,"0",STR_PAD_LEFT) . str_pad($k00_receit,5,"0",STR_PAD_LEFT);
	    $result_arrematric=$clarrematric->sql_record($clarrematric->sql_query_file($k00_numpre,0,"k00_matric"));
	    if($clarrematric->numrows>0){
	      db_fieldsmemory($result_arrematric,0);
	      $clarrematric->k00_numpre=$numpre_novo;
	      $clarrematric->k00_matric=$k00_matric;
  	      $clarrematric->incluir($numpre_novo,$k00_matric);
  	      if($clarrematric->erro_status=='0'){
//           db_msgbox("arrematric");
  	         $sqlerro=true;
  	         break;
  	       }
	     }
	    $result_arreinscr=$clarreinscr->sql_record($clarreinscr->sql_query_file($k00_numpre,0,"k00_inscr"));
	    if($clarreinscr->numrows>0){
	      db_fieldsmemory($result_arreinscr,0);
	      $clarreinscr->k00_numpre=$numpre_novo;
	      $clarreinscr->k00_inscr=$k00_inscr;
  	      $clarreinscr->incluir($numpre_novo,$k00_inscr);
  	      if($clarreinscr->erro_status=='0'){
//      	db_msgbox("arreinscr");
  	        $sqlerro=true;
  	        break;
  	      }
	    }
	  }

	  $cldivida->v01_numcgm  =  $k00_numcgm;
	  $cldivida->v01_dtinsc  =  date("Y-m-d");
//	  die($cldivida->v01_dtinsc);
	  $cldivida->v01_exerc   =  substr($k00_dtoper,6,4);
	  $cldivida->v01_numpre  =  $numpre_novo;
	  if (isset($uni) && $uni == 'p'){
	     $cldivida->v01_numpar  =  1;
	     $cldivida->v01_numtot  =  1;
	  }else{
	     $cldivida->v01_numpar  =  $k00_numpar;
	     $cldivida->v01_numtot  =  $k00_numtot;
	  }
	  $k00_numdig = 1;
	  $cldivida->v01_numdig  =  $k00_numdig;
	  $cldivida->v01_vlrhis  =  $val;
	  $cldivida->v01_proced  =  $cod_v03_codigo;
	  $cldivida->v01_obs     =  "";
	  $cldivida->v01_livro   =  "";
	  $cldivida->v01_folha   =  "";

	  $dt_venc=split("/",$k00_dtvenc);
	  $dt_venc_data = $dt_venc[2]."-".$dt_venc[1]."-".$dt_venc[0];
//	  $cldivida->v01_dtvenc  = $dt_venc_data;
	  if (isset($uni) && $uni == 'p'){
	     $cldivida->v01_dtvenc  = $dtvencunidiv;
	  }else{
	     $cldivida->v01_dtvenc  = $dt_venc_data;
	  }
//	  die($cldivida->v01_dtvenc);
	  $dt_oper=split("/",$k00_dtoper);
	  $dt_oper_data = $dt_oper[2]."-".$dt_oper[1]."-".$dt_oper[0];
	  $cldivida->v01_dtoper  = $dt_oper_data;
	  $cldivida->v01_valor   = $val;
	  
	  $sqlcoddiv = "select nextval('divida_v01_coddiv_seq') as v01_coddiv"; 
	  $resultcoddiv = pg_exec($sqlcoddiv) or die($sqlcoddiv);
	  db_fieldsmemory($resultcoddiv,0);
      $cldivida->incluir(null);
  	  $erro_msg = $cldivida->erro_msg."--- Inclusão Divida";
  	  if($cldivida->erro_status==0){
  	    $erro_msg = $cldivida->erro_msg."--- Inclusão Divida";
  	    $sqlerro=true;
  	    break;
  }
  
/*****  G R A V A   O S   R E G I S T R O S   Q   F O R A M   I M P O R T A D O S,   N A   D I V I M P O R T A R E G  *******/

	 if($sqlerro==false){
       $cldivimportareg->v04_divimporta = $cldivimporta->v02_divimporta;
	   $cldivimportareg->v04_coddiv     = $cldivida->v01_coddiv;
	   $cldivimportareg->incluir();  
	   if($cldivimportareg->erro_status=='0'){
          $erro_msg = $cldivimportaerg->erro_msg."--- Inclusão divimportareg";
	      $sqlerro=true;
  	      break;
	   }
	 }

/**************************************************************************************************************************/
	   
	  if($sqlerro==false){
	    if($sqlerro==false){
	      $cldivold->k10_coddiv=$cldivida->v01_coddiv;
	      $cldivold->k10_numpre=$k00_numpre;
	      $cldivold->k10_numpar=$k00_numpar;
	      $cldivold->k10_receita=$k00_receit;
  	      $cldivold->incluir(null);
  	      if($cldivold->erro_status==0){	    
  	        $sqlerro=true;
  	        $erro_msg = $cldivold->erro_msg."--- Inclusão DIVOLD";
  	        break;
  	      }
	    }
		
//*****************************   A Q U I   F A Z   A   M A O   D O    A R R E C A D  **************************************// 

	    if($sqlerro==false){
	      $k00_hist_arrecad = $k00_hist;
	      $result_pes_proced = $clproced->sql_record($clproced->sql_query_file(null,"v03_receit,k00_hist",null," v03_codigo=$cod_v03_codigo"));
	      db_fieldsmemory($result_pes_proced,0);
//	      db_criatabela($result_pes_proced);exit;
	      $v03_hist = $k00_hist;
	      $k00_hist = $k00_hist_arrecad;
	      
	      $clarrecad->k00_numpre = $numpre_novo;
	      if (isset($uni) && $uni == 'p'){
			  $clarrecad->k00_numpar = 1;
	          $clarrecad->k00_numtot = 1;
	          $clarrecad->k00_dtvenc = $dtvencunidiv;
	      }else{ 
			  $clarrecad->k00_numpar = $k00_numpar;
              $clarrecad->k00_numtot = $k00_numtot;
	          $clarrecad->k00_dtvenc = $dt_venc_data;
          }		  
	      $clarrecad->k00_numcgm = $k00_numcgm;
	      $clarrecad->k00_dtoper = $dt_oper_data;
	      $clarrecad->k00_receit = $v03_receit;
	      $clarrecad->k00_hist   = $v03_hist;
	      $clarrecad->k00_valor  = $val;
	      $clarrecad->k00_numdig = $k00_numdig;
	      $clarrecad->k00_tipo   = $chave_destino;
	      $clarrecad->k00_tipojm = "0";
  	      $clarrecad->incluir();
  	      if($clarrecad->erro_status==0){
  	        $sqlerro=true;
  	        $erro_msg = $clarrecad->erro_msg."--- Inclusão Arrecad";
  	        break;
  	      }
	    }
	    if($sqlerro==false){
//           se agrupar por numpre,  receita
//                          die("select * from arrecad where k00_numpre = $k00_numpre ".(isset($$andnumpar)&& $$andnumpar!=""?$$andnumpar:"")." and k00_receit = $k00_receit");
          $rsArreold = pg_query("select * from arrecad where k00_numpre = $k00_numpre ".(isset($$andnumpar)&& $$andnumpar!=""?$$andnumpar:"")." and k00_receit = $k00_receit");
	      $numrowsArreold = pg_numrows($rsArreold);
          for ($iarreold=0;$iarreold<$numrowsArreold;$iarreold++){
				db_fieldsmemory($rsArreold,$iarreold);
//	                                                   die($clproced->sql_query_file(null,"v03_receit,k00_hist",null," v03_codigo=$cod_v03_codigo"));
				$result_pes_proced = $clproced->sql_record($clproced->sql_query_file(null,"v03_receit,k00_hist",null," v03_codigo=$cod_v03_codigo"));
				$clarreold->k00_numpre = $k00_numpre;
				$k00_numpre_exc=$k00_numpre;
				$clarreold->k00_numpar = $k00_numpar;
				$k00_numpar_exc=$k00_numpar;
				$clarreold->k00_numcgm = $k00_numcgm;
				$clarreold->k00_dtoper = $dt_oper_data;
				$clarreold->k00_receit = $k00_receit;
				$k00_receit_exc=$k00_receit;
				$clarreold->k00_hist   = $k00_hist  ;
				$clarreold->k00_valor  = $val ;
				$clarreold->k00_dtvenc = $dt_venc_data;
				$clarreold->k00_numtot = $k00_numtot;
				$clarreold->k00_numdig = $k00_numdig;
				$clarreold->k00_tipo   = $k00_tipo  ;
				$k00_tipojm = (int) $k00_tipojm;
				$clarreold->k00_tipojm = "$k00_tipojm";
				$clarreold->incluir();
				if($clarreold->erro_status==0){	    
				  $sqlerro=true;
				  $erro_msg = $clarreold->erro_msg."--- Inclusão Arreold";
				  break;
				}
				$clarrecad->excluir(null," k00_numpre=$k00_numpre_exc ".(isset($$excnumpar)&&$$excnumpar!=""?$$excnumpar:"")." and k00_receit=$k00_receit_exc");
				if($clarrecad->erro_status==0){	      
				  $sqlerro=true;	      
				  $erro_msg = $clarrecad->erro_msg."--- Exclusão Arrecad";
				  break;
				}
              }
	        }
	      }
	    }
      }
    }
    $chave_origem  = "";
    $chave_destino = "";
/*    
  if($sqlerro==true){
    db_msgbox("deu pau - i=".$ii);  
  }else{
    db_msgbox("naun deu pau - i=".$ii);  
  }
  exit;
*/
    db_fim_transacao($sqlerro);
  }
}
    if ($sub == 't') {
	  echo "<script>
				document.form1.sub.value='f';
				document.form1.txt_where.value=parent.document.form1.txt_where.value;
				document.form1.submit();
	        </script>";
	  $sub = 'f';
	}
	
//    pg_query(" drop table reg_a_importar".db_getsession("DB_id_usuario").db_getsession("DB_datausu").";");
	if($teste==true){
	  if($erro_msg!=""){
		echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
		db_msgbox($erro_msg);
	//  db_msgbox('Processo concluído com sucesso!');
		echo "<script>top.corpo.db_iframe.hide();</script>";
		echo "<script>top.corpo.location.href='div4_importadivida001.php'</script>";
	  }
	}
?>