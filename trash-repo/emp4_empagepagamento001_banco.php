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
include("dbforms/db_funcoes.php");

include("classes/db_empageconfche_classe.php");
include("classes/db_empagemov_classe.php");

$clempageconfche = new cl_empageconfche;
$clempagemov     = new cl_empagemov;

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label('z01_numero');

$dbwhere = '1=1';

if(isset($e50_codord) && $e50_codord != '' && isset($e50_codord02) && $e50_codord02 != '' ){
  $dbwhere .=" and e82_codord >=$e50_codord and e82_codord <= $e50_codord02 ";
}else if(  (empty($e50_codord) || ( isset($e50_codord) && $e50_codord == '')   )  && isset($e50_codord02) && $e50_codord02 != '' ){
  $dbwhere .=" and e82_codord <= $e50_codord02 ";
}else if(isset($e50_codord) && $e50_codord != '' ){
  $dbwhere .=" and e82_codord=$e50_codord ";
}


if(isset($e60_codemp) && $e60_codemp != '' ){
  $dbwhere .=" and e60_codemp = $e60_codemp ";
}

if(isset($e60_numemp) && $e60_numemp != '' ){
  $dbwhere .=" and e60_numemp = $e60_numemp ";
}

if(isset($z01_numcgm) && $z01_numcgm != '' ){
  $dbwhere .=" and z01_numcgm = $z01_numcgm ";
}

if(isset($dtfi) && $dtfi !=''){
 $dtfi =  str_replace("_","-",$dtfi);
 $dbwhere .= " and e86_data = '$dtfi'";
}

if(isset($e83_codtipo) && $e83_codtipo != ''){
  $dbwhere .= " and e85_codtipo = $e83_codtipo"; 
}

if(isset($e80_codage) && $e80_codage != ''){
  $dbwhere .= " and e81_codage =  $e80_codage ";
}

if(isset($e87_codgera) && $e87_codgera != ''){
  $dbwhere .= " and e87_codgera =  $e87_codgera ";
}


//echo $dbwhere;exit;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_calcula(){
  chaves =  js_retorna_chaves();
  if(chaves != ''){
    tot = new Number(0);
    arr = chaves.split("#"); 
    for(i=0; i<arr.length; i++){
      dad = arr[i];
      arr_dad = dad.split("-"); 
      valor = new Number(arr_dad[2]);
      tot = new Number(tot+valor);
    }
  }else{
    tot = '0.00';
  }  tot = new Number(tot);
   parent.document.form1.tot.value = tot.toFixed(2);
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name="form1" method="post" action="">
	      <center>

<?

$dbwhere .= " and e80_instit = " . db_getsession("DB_instit") . " and e90_codmov is null ";

//if(isset($e83_codtipo) && $e83_codtipo != '' ){
//  $dbwhere .=" and e83_codtipo=$e83_codtipo ";
//}
if(isset($e87_codgera) && $e87_codgera != ''){
  $sql    		= $clempagemov->sql_query_gera(null,"e81_codmov,e83_codtipo,e83_descr,e60_emiss,e60_codemp,e82_codord,z01_numcgm,z01_nome,e81_valor,sum(e53_valor) as e53_vlrliq, sum(e53_vlrpag) as e53_vlrpag, sum(e53_valor - e53_vlrpag) as db_disponivel","","$dbwhere group by e81_codmov,e83_codtipo,e83_descr,e60_emiss,e60_codemp,e82_codord,z01_numcgm,z01_nome,e81_valor");
  $sql_disabled    	= $clempagemov->sql_query_gera(null,"e81_codmov,e83_codtipo,e83_descr,e60_emiss,e60_codemp,e82_codord,z01_numcgm,z01_nome,e81_valor, sum(e53_valor) as e53_vlrliq, sum(e53_vlrpag) as e53_vlrpag, sum(e53_valor - e53_vlrpag) as db_disponivel","","$dbwhere group by e81_codmov,e83_codtipo,e83_descr,e60_emiss,e60_codemp,e82_codord,z01_numcgm,z01_nome,e81_valor having e81_valor > sum(e53_valor - e53_vlrpag) ");
} else {
  $sql    		= $clempagemov->sql_query_lay(null,"e81_codmov,e83_codtipo,e83_descr,e60_emiss,e60_codemp,e82_codord,z01_numcgm,z01_nome,e81_valor,(e60_vlrliq - e60_vlrpag) as db_disponivel","","$dbwhere");
  $sql_disabled    	= $clempagemov->sql_query_lay(null,"e81_codmov,e83_codtipo,e83_descr,e60_emiss,e60_codemp,e82_codord,z01_numcgm,z01_nome,e81_valor,(e60_vlrliq - e60_vlrpag) as db_disponivel","","$dbwhere and e81_valor > (e60_vlrliq - e60_vlrpag)");
}
//die($sql);
//die($sql_disabled);

	  $result  = $clempagemov->sql_record($sql);
	  $numrows = $clempagemov->numrows;

          $cliframe_seleciona->textocabec ="black";
	  $cliframe_seleciona->textocorpo ="black";
	  $cliframe_seleciona->fundocabec ="#999999";
	  $cliframe_seleciona->fundocorpo ="#cccccc";
	  $cliframe_seleciona->iframe_height ="300";
	  $cliframe_seleciona->iframe_width ="750";
	  $cliframe_seleciona->iframe_nome ="canc";
	  $cliframe_seleciona->fieldset =false;

	  $cliframe_seleciona->js_marcador = "parent.js_calcula()";
	  $cliframe_seleciona->dbscript = "onclick='parent.js_calcula();'";
	  $cliframe_seleciona->desabilitados = false;
	  $cliframe_seleciona->checked = true;
	  
          $campos  = "e81_codmov,e83_codtipo,e83_descr,e60_emiss,e60_codemp,e82_codord,z01_numcgm,z01_nome,e81_valor,db_disponivel";
	  $cliframe_seleciona->campos = $campos;
	  $cliframe_seleciona->sql = $sql;
	  $cliframe_seleciona->sql_disabled = $sql_disabled;
	  $cliframe_seleciona->chaves ="e81_codmov,e82_codord,e81_valor,e83_codtipo";
	  $cliframe_seleciona->iframe_seleciona(1);

//          if(isset($e87_codgera) && $e87_codgera != ''){
//            $sql    = $clempagemov->sql_query_gera(null,"sum(e81_valor) as tot","","$dbwhere");
//	  } else {
//            $sql    = $clempagemov->sql_query_lay(null,"sum(e81_valor) as tot","","$dbwhere");
//	  }
//	  die($sql);

          $tot	 	= 0;
	  $registros 	= 0;
          for ($soma=0; $soma < $numrows; $soma++) {
	    db_fieldsmemory($result,$soma,true);
	    $tot += $e81_valor;
	    $registros++;
	  }
//	  exit;
//	  die($tot);

//	  $result  = $clempageconfche->sql_record($sql);
//	  db_fieldsmemory($result,0);
          
//          if(isset($e87_codgera) && $e87_codgera != ''){
//            $sql    = $clempagemov->sql_query_gera(null,"count(e81_valor) as registros","","$dbwhere");
//	  } else {
//            $sql    = $clempagemov->sql_query_lay(null,"count(e81_valor) as registros","","$dbwhere");
//	  }
//	  echo $sql;
//	  $result  = $clempagemov->sql_record($sql);
//	  db_fieldsmemory($result,0);
?>	  
      </center>
    </form>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  parent.document.form1.tot.value = '<?=$tot?>';
   parent.document.form1.registros.value = '<?=$registros?>';
   parent.document.form1.tipo.value = 'banco';
</script>
<?
if($numrows>0){
  echo "<script>";
  echo "parent.document.form1.atualizar.disabled=false;";
  echo "</script>";
}
?>