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
include("libs/db_sql.php");
include("classes/db_arrecad_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;

if(isset($desconto)){
  $desconto = $desconto +0;
  if (!empty($desconto)){
     $clarrecad = new cl_arrecad;
     $record = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"));
     if(pg_numrows($record) != 0){
	     db_fieldsmemory();      
     }
  }  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_calcula(){
  var perce = new Number(document.form1.DBtxt8.value);
  if(perce>100){
    alert('Percentual não poderá ser superior a 100%.');
    document.form1.DBtxt8.value = '100';
  }
  var perce = new Number(document.form1.DBtxt8.value);
  var valor = new Number(document.form1.k00_valor.value);
  valor = valor * (perce/100);
  document.form1.DBtxt9.value = valor.toFixed(2) ;
}
function js_calculavalor(){
  var valor = new Number(document.form1.DBtxt9.value);
  if(valor>document.form1.k00_valor.value){
    alert('Valor maior que o débito.');
    document.form1.DBtxt9.value = document.form1.k00_valor.value;
  }
  var valor1 = new Number(document.form1.k00_valor.value);
  var valor  = new Number(document.form1.DBtxt9.value);
  perce =  (valor*100)/valor1;
  document.form1.DBtxt8.value = perce.toFixed(2) ;
}
function js_verifica(){
  var valor = new Number(document.form1.DBtxt9.value);
  if(valor==0){
    alert('Valor Zerado.');
	document.form1.DBtxt9.focus();
	return false;
  }
  return true;

}
function js_caljuros(){
  
  var valor = new Number(document.form1.tvlrjuros.value);
  var valortot = new Number(document.form1.DBtxt9.value);
  if(document.form1.descontojuros.checked){
    valor = valor + valortot;
    document.form1.DBtxt9.value = valor.toFixed(2);
  }else{
    valor =  valortot - valor ;
    document.form1.DBtxt9.value = valor.toFixed(2);
  }

}
function js_calmulta(){

  var valor = new Number(document.form1.tvlrmulta.value);
  var valortot = new Number(document.form1.DBtxt9.value);
  if(document.form1.descontojuros.checked){
    valor = valor + valortot;
    document.form1.DBtxt9.value = valor.toFixed(2);
  }else{
    valor =  valortot - valor ;
    document.form1.DBtxt9.value = valor.toFixed(2);
  }


}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" action="" method="post" onSubmit="return js_verifica()">
<center>
    <table width="686" height="27" border="0" cellpadding="0" cellspacing="0">
      <?
  if(!isset($k00_numpre)){
    db_redireciona('db_erros.php?db_erro=Acesso não Permitido.');
    exit; 
  }
  if(!isset($k00_numpar)){
     $numpar = 0 ;
  }else{
     $numpar = $k00_numpar;
  }
  if(isset($k00_receit)){
	$receit = $k00_receit;
  }else
    $receit = 0;
	 
  $record = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"));
  if(pg_numrows($record) != 0){
    $matrec=array();
    $matpar["0"]="Todas Parcelas ...";
    $matrec["0"]="Todas Receitas ...";
    $valor = 0;
	$tvlrcor= 0;
	$tvlrjuros= 0;
	$tvlrmulta= 0;
	$tvlrdesconto= 0;
    $ttotal = 0;
    for($i=0;$i<pg_numrows($record);$i++){
	  db_fieldsmemory($record,$i);
      $matpar[$k00_numpar]= "$k01_descr";
	  if($numpar!=0 && $k00_numpar == $numpar){ 
	    $matrec[$k00_receit] ="$k02_descr";
	    if($receit!=0 && $k00_receit == $receit){ 
  		   $valor += $total;
           $tvlrcor+= $vlrcor;
	       $tvlrjuros+= $vlrjuros;
	       $tvlrmulta+= $vlrmulta;
	       $tvlrdesconto+= $vlrdesconto;
	       $ttotal+= $total;
		}else if($receit==0){ 
  		   $valor += $total;
           $tvlrcor+= $vlrcor;
	       $tvlrjuros+= $vlrjuros;
	       $tvlrmulta+= $vlrmulta;
	       $tvlrdesconto+= $vlrdesconto;
	       $ttotal+= $total;
		}
      }else if($numpar==0){
	    $matrec[$k00_receit] ="$k02_descr";
	    if($receit!=0 && $k00_receit == $receit){ 
  		   $valor += $total;
           $tvlrcor+= $vlrcor;
	       $tvlrjuros+= $vlrjuros;
	       $tvlrmulta+= $vlrmulta;
	       $tvlrdesconto+= $vlrdesconto;
	       $ttotal+= $total;
		}else if($receit==0){ 
  		   $valor += $total;
           $tvlrcor+= $vlrcor;
	       $tvlrjuros+= $vlrjuros;
	       $tvlrmulta+= $vlrmulta;
	       $tvlrdesconto+= $vlrdesconto;
	       $ttotal+= $total;
		}
	  }
	}
    $k00_valor = $valor;
    $clarrecad = new cl_arrecad;
	$result = $clarrecad->sql_record($clarrecad->sql_query("","cgm.z01_nome#arretipo.k00_descr",""," arrecad.k00_numpre = $k00_numpre and k00_instit = ".db_getsession('DB_instit') )); 
    db_fieldsmemory($result,0);	
    ?>
      <tr> 
        <td width="110">Nome</td>
        <td width="214"> 
          <?
    $clrotulo->label("z01_nome");
	db_input('z01_nome',40,$Iz01_nome,true,'text',3)
	?>
        </td>
        <td width="104">Valor:</td>
        <td width="258"> 
          <?
    $clrotulo->label("k00_valor");
	db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrcor')
	?>
        </td>
      </tr>
      <tr> 
        <td>TipoD&eacute;bito:</td>
        <td> 
          <?
    $clrotulo->label("k00_descr");
	db_input('k00_descr',40,$Ik00_descr,true,'text',3)
	?>
        </td>
        <td>Juros:</td>
        <td> 
          <?
    $clrotulo->label("k00_valor");
	db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrjuros')
	?>
        </td>
      </tr>
      <tr> 
        <td>C&oacute;digo:</td>
        <td> 
          <?
    $clrotulo->label("k00_numpre");
	db_input('k00_numpre',8,$Ik00_numpre,true,'text',3)
	?>
        </td>
        <td>Multa:</td>
        <td> 
          <?
    $clrotulo->label("k00_valor");
	db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrmulta')
	?>
        </td>
      </tr>
      <tr> 
        <td>Parcela:</td>
        <td> 
          <?
    $clrotulo->label("k00_numpar");
	$k00_numpar = $numpar;
	db_select('k00_numpar',$matpar,true,2," onchange='document.form1.submit();' ");
	?>
        </td>
        <td>Desconto:</td>
        <td> 
          <?
    $clrotulo->label("k00_valor");
	db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrdesconto')
	?>
        </td>
      </tr>
      <tr> 
        <td>Receita:</td>
        <td> 
          <?
    $clrotulo->label("k00_receit");
    $k00_receit = $receit;
	db_select('k00_receit',$matrec,true,2," onchange='document.form1.submit();' ")
	?>
        </td>
        <td>Total:</td>
        <td> 
          <?
    $clrotulo->label("k00_valor");
	db_input('k00_valor',15,$Ik00_valor,true,'text',3,'ttotal')
	?>
        </td>
      </tr>
      <tr> 
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp; </td>
      </tr>
      <tr> 
        <td> <div align="right"></div></td>
        <td>Total LIberado Para desconto:</td>
        <td> 
          <?
    $clrotulo->label("k00_valor");
	db_input('k00_valor',15,$Ik00_valor,true,'text',3,'')
	?>
        </td>
        <td>&nbsp; </td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td>Percentual: </td>
        <td> 
          <?
        $clrotulo->label("DBtxt8");
     	db_input('DBtxt8',15,$IDBtxt8,true,'text',2," onchange='js_calcula()'")
    	?>
        </td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td>Valor: </td>
        <td> 
          <?
        $clrotulo->label("DBtxt9");
     	db_input('DBtxt9',15,$IDBtxt9,true,'text',2," onchange='js_calculavalor()'")
	    ?>
        </td>
        <td>&nbsp; </td>
      </tr>
      <tr align="center">
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr align="center"> 
        <td colspan="4"><input name="desconto" type="submit" id="desconto" value="Lan&ccedil;ar Desconto"></td>
      </tr>
      <tr align="center"> 
        <td colspan="4"></td> </td> </tr>
      <?
  }
  ?>
    </table>
  </center>
</form>
</body>
</html>