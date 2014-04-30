<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_empage_classe.php");
include("classes/db_empageconf_classe.php");
$clempage  = new cl_empage;
$clempageconf  = new cl_empageconf;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("e82_codord");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e81_valor");
$clrotulo->label("e81_codmov");
$clrotulo->label("e86_cheque");
$clrotulo->label("e76_lote");
$clrotulo->label("e76_movlote");
$clrotulo->label("e80_data");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas{
          border: 1px solid #cccccc;
          border-top-color: <?=$cor?>;
          border-right-color: <?=$cor?>;
          border-left-color: <?=$cor?>;
          border-bottom-color: <?=$cor?>;
          background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
          border: 2px solid #cccccc;
          border-top-color: <?=$cor?>;
          border-right-color: <?=$cor?>;
          border-left-color: <?=$cor?>;
          border-bottom-color: <?=$cor?>;
          background-color: #999999;
}
</style>
<script>
function js_marca(obj){ 
  var OBJ = document.form1;
  soma=new Number();
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
      OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
    }
  }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
      <form name="form1" method="post" action="">
      <center>
      <table border='2'>
      <?
      db_input("codgera",10,'',true,'hidden',3);  
      if(isset($codgera) && trim($codgera)!=""){
	$dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and e90_correto='t' and e87_codgera = ".$codgera;
	
	if (isset($lCancelado) && $lCancelado == '0' ) {
	  
	  $dbwhere .= " and empageconfgera.e90_cancelado is false ";
	}
	
	$result_arq  = $clempage->sql_record($clempage->sql_query_cons(null,"e53_valor,e53_vlranu,e53_vlrpag,e87_codgera,e87_descgera,e87_data,e87_hora,e83_descr,pc63_conta,pc63_conta_dig,pc63_agencia,pc63_agencia_dig,pc63_banco,e81_codmov,e60_codemp,e82_codord,e86_codmov,case when a.z01_numcgm='' or a.z01_numcgm is null then cgm.z01_numcgm else a.z01_numcgm end as z01_numcgm,case when a.z01_nome='' or a.z01_nome is null then cgm.z01_nome else a.z01_nome end as z01_nome,e81_valor,e83_codtipo,e83_descr","e83_codtipo,e82_codord",$dbwhere));
	$numrows_arq = $clempage->numrows;
	$arr_valorcontas = Array();
	$arr_valorproces = Array();
	if($numrows_arq>0){
	  for($i = 0;$i<$numrows_arq;$i++){
	    db_fieldsmemory($result_arq,$i);
	    $valormovs += $e81_valor;
	    if(!isset($arr_valorcontas[$e83_codtipo])){
	      $arr_valorcontas[$e83_codtipo] = 0;
	    }
	    $arr_valorcontas[$e83_codtipo] += $e81_valor;
	  }
	  echo "
	  <thead>
	  <tr>
	    <td class='bordas02' align='left' colspan='7'><b>Conta pagadora</b></td>
	  </tr>
	  <tr>
	      <td class='bordas02' align='center' title='Inverte Marcação'>";
		db_ancora("M",'js_marca(this)',1);
	  echo "
	    </td>
	    <td class='bordas02' align='center'><b>$RLe82_codord</b></td>
	    <td class='bordas02' align='center'><b>$RLe60_codemp</b></td>
	    <td class='bordas02' align='center'><b>$RLz01_nome</b></td>
	    <td class='bordas02' align='center'><b>Banco - agência - conta</b></td>
	    <td class='bordas02' align='center'><b>$RLe80_data</b></td>
	    <td class='bordas02' align='center'><b>Valor movimentos</b></td>
	  </tr>
	  </thead>
	  <tbody style='max-height:27ex;overflow:auto;'>
	  ";
	}else{
	  echo "<tr><td><b>Movimentos já baixados ou cancelados.</b></td></tr>";
	}

	$pagadora = "";
	for($i = 0;$i<$numrows_arq;$i++){
	  db_fieldsmemory($result_arq,$i);

	  $class = "";
	  if($pagadora!=$e83_codtipo){
	    $pagadora = $e83_codtipo;
	    if($i!=0){
	      echo "<tr><td colspan='7' align='left'>&nbsp;</td></tr>";
	    }
	    echo "<tr>
		    <td colspan='6' class='bordas' align='left'>
		      <b>$e83_descr</b>
		    </td>
		    <td colspan='1' class='bordas' align='left'>
		      <b>".db_formatar($arr_valorcontas[$e83_codtipo],"f")."</b>
		    </td>
		  </tr>
	    ";
	  }
	  if(trim($pc63_agencia_dig)!=""){
	    $pc63_agencia_dig = "/".$pc63_agencia_dig;
	  }
	  if(trim($pc63_conta_dig)!=""){
	    $pc63_conta_dig = "/".$pc63_conta_dig;
	  }
	  echo "
	  <tr>
	    <td class='bordas$class'><input value='$e81_codmov' name='CHECK_$e81_codmov' type='checkbox'></td>
	    <td class='bordas$class'><small>$e82_codord</small></td>
	    <td class='bordas$class'><small>$e60_codemp</small></td>
	    <td class='bordas$class'><small>$z01_nome</small></td>
	    <td class='bordas$class'><small>".$pc63_banco." - ".$pc63_agencia.$pc63_agencia_dig." - ".$pc63_conta.$pc63_conta_dig."</small></td>
	    <td class='bordas$class'><small>".db_formatar($e87_data,"d")."</small></td>
	    <td class='bordas$class'><small>".db_formatar($e81_valor,"f")."</small></td>
	  </tr>
	  ";
	}
	if($numrows_arq>0){
	  echo "</tbody>";
	}
      }
      ?>
      </table>
      </center>
      </form>
    </td>
  </tr>
</table>
</body>
</html>
<script>
</script>