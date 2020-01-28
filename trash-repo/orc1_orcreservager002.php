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
include("classes/db_orcreservager_classe.php");
include("classes/db_orcreserprev_classe.php");
include("classes/db_orcprevdesp_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$erro = false;	

$clorcreserprev = new cl_orcreserprev;
$clorcprevdesp = new cl_orcprevdesp;

if(isset($atualiza)){

  db_inicio_transacao();

  reset($HTTP_POST_VARS);


  for($i=0;$i<count($HTTP_POST_VARS);$i++){
  	if(substr(key($HTTP_POST_VARS),0,9) == 'atividade' ){
      $mat = split("\_",key($HTTP_POST_VARS));
      $clorcreserprev->excluir(db_getsession("DB_anousu"),$mat[1],$mat[2]);
  	}
  	if(substr(key($HTTP_POST_VARS),0,8) == 'previsao' ){
      $mat = split("\_",key($HTTP_POST_VARS));
      $clorcprevdesp->excluir(db_getsession("DB_anousu"),$mat[1],$mat[2]);
  	}
    next($HTTP_POST_VARS);
  }
  reset($HTTP_POST_VARS);
  
  for($i=0;$i<count($HTTP_POST_VARS);$i++){
  	if(substr(key($HTTP_POST_VARS),0,9) == 'atividade' ){

      $mat = split("\_",key($HTTP_POST_VARS));
      $clorcreserprev->o33_anousu   = db_getsession("DB_anousu");
      $clorcreserprev->o33_projativ = $mat[1];
      $clorcreserprev->o33_codigo   = $mat[2];
      $clorcreserprev->o33_mes      = $mat[3];
      $clorcreserprev->o33_perc     = "0".$HTTP_POST_VARS[key($HTTP_POST_VARS)];
      $clorcreserprev->o33_valor    = "0";

      
      $result = $clorcreserprev->incluir(db_getsession("DB_anousu"),$mat[1],$mat[2],$mat[3]);
      if($result == false || $clorcreserprev->erro_status == "0"){
        $erro = true;
        $msg_erro = $clorcreserprev->erro_msg;
        break;
      }
      
  	}
  	if(substr(key($HTTP_POST_VARS),0,8) == 'previsao' ){

      $mat = split("\_",key($HTTP_POST_VARS));
      $clorcprevdesp->o35_anousu   = db_getsession("DB_anousu");
      $clorcprevdesp->o35_projativ = $mat[1];
      $clorcprevdesp->o35_codigo   = $mat[2];
      $clorcprevdesp->o35_mes      = $mat[3];
      $clorcprevdesp->o35_perc     = "0".$HTTP_POST_VARS[key($HTTP_POST_VARS)];
      $clorcprevdesp->o35_valor    = "0";

      
      $result = $clorcprevdesp->incluir(db_getsession("DB_anousu"),$mat[1],$mat[2],$mat[3]);
      if($result == false || $clorcprevdesp->erro_status == "0"){
        $erro = true;
        $msg_erro = $clorcprevdesp->erro_msg;
        break;
      }

  	}
    next($HTTP_POST_VARS);
  }	

  db_fim_transacao($erro);

}

if($erro == false){

  $sql = "select o58_projativ,o55_descr,o58_codigo,o15_descr,sum(o58_valor) as o58_valor,
       sum(substr(fc_dotacaosaldo,107,12)::float8) as atual,
       sum(substr(fc_dotacaosaldo,120,12)::float8) as reservado,
       sum(substr(fc_dotacaosaldo,133,12)::float8) as atual_menos_reservado
  from orcdotacao
  inner join orcprojativ on o58_anousu = o55_anousu and o58_projativ = o55_projativ
  inner join orctiporec on o15_codigo =o58_codigo
  inner join ( select o58_anousu as anousu, o58_coddot as coddot, fc_dotacaosaldo(".db_getsession("DB_anousu").",o58_coddot,5,'".date("Y-m-d",db_getsession("DB_anousu"))."','".db_getsession("DB_anousu")."-12-31') from orcdotacao where o58_anousu = ".db_getsession("DB_anousu")." ) as saldo
       on saldo.anousu = o58_anousu and saldo.coddot = o58_coddot
  where o58_anousu = ".db_getsession("DB_anousu")."
      and o58_instit = ".db_getsession("DB_instit");

  if($atividade>0){
     $sql .= " and o58_projativ = $atividade ";  
  }
  $sql .= " group by o58_projativ,o58_codigo,o55_descr,o15_descr";


  $clorcreservager = new cl_orcreservager;
  $result=$clorcreservager->sql_record($sql);
  //db_criatabela($result);
  if($clorcreservager->numrows==0){
    $msg_erro = "Orçamento não cadastrado.";
    $erro = true;	
  }
}

$clrotulo = new rotulocampo;
$clrotulo->label("o58_projativ");
$clrotulo->label("o55_descr");
$clrotulo->label("o58_codigo");
$clrotulo->label("o15_descr");
$clrotulo->label("o58_valor");
$clrotulo->label("atual");
$clrotulo->label("reservado");
$clrotulo->label("atual_menos_reservado");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1' method='post'>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <?
  if($erro==false){
    echo "<tr><td title='$Lo55_descr' >";
    echo $Lo58_projativ;
    echo "</td><td>";
    echo $Lo58_codigo;
    echo "</td><td>";
    echo $Lo15_descr;
    echo "</td><td>";
    echo $Lo58_valor;
    echo "</td><td>";
    echo "Saldo Atual";
    echo "</td><td>";
    echo "Reservado";
    echo "</td><td>";
    echo "Saldo Real";
    for($x=1;$x<13;$x++){
      echo "</td><td>";
      echo substr(db_mes($x,3),0,3)."(%)";
    }
    echo "</td></tr>";
  	for($i=0;$i<$clorcreservager->numrows;$i++){
  	  db_fieldsmemory($result,$i);
      echo "<tr><td title='$o55_descr'>";
      echo $o58_projativ;
      echo "</td><td>";
      echo $o58_codigo;
      echo "</td><td title='$o15_descr' >";
      echo substr($o15_descr,0,15);
      echo "</td><td align='right'>";
      echo db_formatar($o58_valor,'f');
      echo "</td><td align='right'>";
      echo db_formatar($atual,'f');
      echo "</td><td align='right'>";
      echo db_formatar($reservado,'f');
      echo "</td><td align='right'>";
      echo db_formatar($atual_menos_reservado,'f');
      for($x=1;$x<13;$x++){
        echo "</td><td>";
        $resultprev = $clorcreserprev->sql_record($clorcreserprev->sql_query(db_getsession("DB_anousu"),$o58_projativ,$o58_codigo,$x,'o33_perc'));
        if($clorcreserprev->numrows >0){
        	$atividade = "atividade_".$o58_projativ."_".$o58_codigo."_$x";
        	global $$atividade;
        	db_fieldsmemory($resultprev,0);
        	$$atividade = $o33_perc;
        }
        db_input("atividade_".$o58_projativ."_".$o58_codigo."_$x",5,0,true,"text",($atual_menos_reservado==0?3:2),"onchange='js_mudarprevis(\"".$o58_projativ."_".$o58_codigo."_$x\")'");
      }
      echo "</td></tr>";

      ///////////////////////////////////////////////////////////////////////////////
      /////                 MOSTRAR CAMPOS PARA LANÇAR PREVISÃO                 /////
      ///////////////////////////////////////////////////////////////////////////////
      $tranca_previsao = 1;
      $desabe_previsao = " disabled ";
      $clique_previsao = "";
      $resultprevdespteste = $clorcprevdesp->sql_record($clorcprevdesp->sql_query_file(null,null,null,null,'*',"","o35_anousu = ".db_getsession("DB_anousu")." and o35_projativ = $o58_projativ and o35_codigo = $o58_codigo and o35_perc > 0"));
      if($clorcprevdesp->numrows > 0){
      	$tranca_previsao = 3;
      	$desabe_previsao = "";
      	$clique_previsao = "js_habilita(\"".$o58_projativ."_".$o58_codigo."\")";
      }
    echo "<tr><td align='right' colspan='7'><b><input onclick='".$clique_previsao."' type='checkbox' name='chk_".$o58_projativ."_".$o58_codigo."' $desabe_previsao>Lançar previsão:</b>";
      for($x=1;$x<13;$x++){
        echo "</td><td>";
        $resultprevdesp = $clorcprevdesp->sql_record($clorcprevdesp->sql_query_file(db_getsession("DB_anousu"),$o58_projativ,$o58_codigo,$x,'o35_perc'));
        if($clorcprevdesp->numrows >0){
        	$atividade = "previsao_".$o58_projativ."_".$o58_codigo."_$x";
        	global $$atividade;
        	db_fieldsmemory($resultprevdesp,0);
        	$$atividade = $o35_perc;
        }
        db_input("previsao_".$o58_projativ."_".$o58_codigo."_$x",5,0,true,"text",$tranca_previsao);
      }
      echo "</td></tr>";
      ///////////////////////////////////////////////////////////////////////////////
  	}
  }
  ?>
  <input type='hidden' name='atualiza' value='atualiza'>
</table>
</form>
</body>
</html>
<script>
function js_habilita(prjrec){
	x = eval("document.form1");
	y = eval("document.form1.chk_"+prjrec);
	if(y.checked == true){
		for(i=1; i<13; i++){
		  eval("x.previsao_"+prjrec+"_"+i+".readOnly = false");
		  eval("x.previsao_"+prjrec+"_"+i+".style.backgroundColor = ''");
		}
	}else{
		for(i=1; i<13; i++){
		  eval("x.previsao_"+prjrec+"_"+i+".readOnly = true");
		  eval("x.previsao_"+prjrec+"_"+i+".style.backgroundColor = '#DEB887'");
		}
	}
}
function js_mudarprevis(campo){
  x = eval("document.form1.previsao_"+campo);
  y = eval("document.form1.atividade_"+campo);
  if(x.readOnly == false && (x.value == "" || x.value == 0)){
    x.value = y.value;
  }
}
</script>
<?
if($erro==true)
  db_msgbox($msg_erro);

if(isset($atualiza))
  if($erro==false)
    db_msgbox("Processo concluído.");
  
?>