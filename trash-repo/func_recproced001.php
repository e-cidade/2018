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
include("classes/db_arreold_classe.php");
include("classes/db_divida_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cltabrec = new cl_tabrec;
$clarrecad = new cl_arrecad;
$clarreold = new cl_arreold;
$clproced = new cl_proced;
$cldivida = new cl_divida;
$teste=false;
if(isset($cod_k02_codigo) && trim($cod_k02_codigo)!="" && isset($cod_v03_codigo) && trim($cod_v03_codigo)!=""){
  $teste=true;
//die($clarrecad->sql_query_file(null,"*",null," k00_tipo=1 limit 100"));
  $result_pesq_divida = $clarrecad->sql_record($clarrecad->sql_query_file(null,"*",null," k00_tipo=1 limit 100"));
  $numrows = $clarrecad->numrows;
  $codigo_k02 = split(",",$cod_k02_codigo);
  $codigo_v03 = split(",",$cod_v03_codigo);
  $sqlerro=false;
  db_inicio_transacao();
  for($i=0;$i<$numrows;$i++){
    db_fieldsmemory($result_pesq_divida,$i,true);
    for($ii=0;$ii<sizeof($codigo_k02);$ii++){
      $cod_k02_codigo = $codigo_k02[$ii];
      $cod_v03_codigo = $codigo_v03[$ii];
      if($k00_receit==$cod_k02_codigo){	
	$nextval_numpre=pg_exec("select nextval('numpref_k03_numpre_seq') as numpre_novo");
	db_fieldsmemory($nextval_numpre,0); 
	$cldivida->v01_numcgm  =  $k00_numcgm;
	$cldivida->v01_dtinsc  =  date("Y-m-d");
	$cldivida->v01_exerc   =  db_getsession("DB_anousu");
	$cldivida->v01_numpre  =  $numpre_novo;
	$cldivida->v01_numpar  =  $k00_numpar;
	$cldivida->v01_numtot  =  $k00_numtot;
        $k00_numdig = (int) $k00_numdig;
	$cldivida->v01_numdig  =  "$k00_numdig";
	$cldivida->v01_vlrhis  =  $k00_valor;
	$cldivida->v01_proced  =  $cod_v03_codigo;
	$cldivida->v01_obs     =  "";
	$cldivida->v01_livro   =  "";
	$cldivida->v01_folha   =  "";
	$dt_venc=split("/",$k00_dtvenc);
	$dt_venc_data = $dt_venc[2]."-".$dt_venc[1]."-".$dt_venc[0];
	$cldivida->v01_dtvenc  = $dt_venc_data;
	$dt_oper=split("/",$k00_dtoper);
	$dt_oper_data = $dt_oper[2]."-".$dt_oper[1]."-".$dt_oper[0];
	$cldivida->v01_dtoper  = $dt_oper_data;
	$cldivida->v01_valor   = $k00_valor;
	$cldivida->incluir(null);
	$erro_msg = $cldivida->erro_msg."--- Inclusão Divida";
	if($cldivida->erro_status==0){
	  $erro_msg = $cldivida->erro_msg."--- Inclusão Divida";
	  $sqlerro=true;
	  break;
	}
	if($sqlerro==false){
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
	  $clarreold->k00_valor  = $k00_valor ;
	  $clarreold->k00_dtvenc = $dt_venc_data;
	  $clarreold->k00_numtot = $k00_numtot;
	  $clarreold->k00_numdig = "$k00_numdig";
	  $clarreold->k00_tipo   = $k00_tipo  ;
	  $k00_tipojm = (int) $k00_tipojm;
	  $clarreold->k00_tipojm = "$k00_tipojm";
	  $clarreold->incluir();
	  if($clarreold->erro_status==0){	    
	    $sqlerro=true;
	    $erro_msg = $clarreold->erro_msg."--- Inclusão ArreOld";
	    break;
	  }
	  if($sqlerro==false){
	    db_fieldsmemory($result_pes_proced,0);
	    $clarrecad->k00_numpre = $numpre_novo;
	    $clarrecad->k00_numpar = $k00_numpar;
	    $clarrecad->k00_numcgm = $k00_numcgm;
	    $clarrecad->k00_dtoper = $dt_oper_data;
	    $clarrecad->k00_receit = $v03_receit;
	    $clarrecad->k00_hist   = $k00_hist;
	    $clarrecad->k00_valor  = $k00_valor;
	    $clarrecad->k00_dtvenc = $dt_venc_data;
	    $clarrecad->k00_numtot = $k00_numtot;
	    $clarrecad->k00_numdig = "$k00_numdig";
	    $clarrecad->k00_tipo   = 31;
	    $clarrecad->k00_tipojm = "0";
	    $clarrecad->incluir();
	    if($clarrecad->erro_status==0){
	      $sqlerro=true;
	      $erro_msg = $clarrecad->erro_msg."--- Inclusão ArreCad";
	      break;
	    }
	  }
	  if($sqlerro==false){
	    $clarrecad->excluir(null," k00_numpre=$k00_numpre_exc and k00_numpar=$k00_numpar_exc and k00_receit=$k00_receit_exc");
	    if($clarrecad->erro_status==0){	      
	      $sqlerro=true;
	      $erro_msg = $clarrecad->erro_msg."--- Exclusão ArreCad";
	      break;
	    }
	  }
	}
	if($sqlerro==true){
	  echo "ERRO: ".($i+1);
	}
      }
    }
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="100%" height="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
      <center>
      <form name="form1" method="post" action="">
      <table height="100%"  border="0" cellspacing="0" cellpadding="0">
<?
//select 1 que traz k02_codigo e k02_drecei da tabela tabrec
$sql0 = "select tabrec.k02_codigo,tabrec.k02_drecei from (select distinct k00_receit from arrecad where k00_tipo = 1) as x inner join tabrec on k02_codigo = x.k00_receit";
$result0 = $cltabrec->sql_record($sql0);
$numrows0=$cltabrec->numrows;

//select 2 q traz v03_codigo e v03_descr da tabela proced
$sql1 = "select v03_codigo, v03_descr from proced";
$result1 = $clproced->sql_record($sql1);
$numrows1 = $clproced->numrows;

//for para criar tabela com os campos da tabrec e o select da proced
$vir = "";
$cod_k02_codigo="";
$vir1 = "";
$cod_v03_codigo="";
for($i=0;$i<$numrows0;$i++){
  db_fieldsmemory($result0,$i);
  $cod_k02_codigo .= $vir.$k02_codigo;
  $vir = ",";
  echo "
  <tr>
    <td nowrap>";
  //inputs com k02_drecei
  db_input("$k02_drecei",40,"",true,"text",3,"","k02_drecei");
  echo "
      <select name=\"v03_descr\" onchange=\"js_troca();\" id=\"v03_descr\">
        <option value=\"0\" >Escolha uma procedência</option>      
      ";
  //for para colocar os selects
  for($ii=0;$ii<$numrows1;$ii++){
    db_fieldsmemory($result1,$ii);
    echo "
        <option value=\"$v03_codigo\" >$v03_descr</option>";
    if($ii == 0){
      $cod_v03_codigo .= $vir1.$v03_codigo;
      $vir1=",";
    }
  }
  echo "
      </select>
    </td>
  </tr>";
}
db_input("cod_k02_codigo",40,"",true,"text",3,"");
db_input("cod_v03_codigo",40,"",true,"text",3,"");
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
  vir="";
  codigo="";
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type=="select-one"){
      if(document.form1.elements[i].value!=0){	
        codigo += vir + document.form1.elements[i].value;
        vir=",";
      }
    }
  }
  document.form1.cod_v03_codigo.value=codigo;
}
</script>
<?
if($teste==true){
  db_msgbox($erro_msg);
}
?>