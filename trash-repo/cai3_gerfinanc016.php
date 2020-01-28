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
include("libs/db_sql.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
//arreprescr k30_anulado is false
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<!--
th {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
input {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
	color: black;
	height: 17px;
}
a {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
	color: black;
	text-decoration: none;
}
a:hover {
  text-decoration: underline;
}
td {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
.tabs {
  border: none;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<?
if(isset($tipo_cert) && !isset($HTTP_POST_VARS["procurar"])) {
?>
<br><br>
<!--form name="form1" method="post" onSubmit="return js_validar()"-->
<form name="form1" method="post" >
    <table width="36%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="12%" class="tabs" nowrap><strong>Data Inicial:</strong></td>
        <td width="88%" class="tabs"> 
          <?
		  include("dbforms/db_funcoes.php");
    db_inputdata('datainicial',@$datainicial_dia,@$datainicial_mes,@$datainicial_ano,true,'text',4);
	  ?>
        </td>
      </tr>
      <tr> 
        <td class="tabs" nowrap><strong>Data Final:</strong></td>
        <td class="tabs"> 
          <?
					@$datafinal_dia = date('d',db_getsession('DB_datausu') );
					@$datafinal_mes = date('m',db_getsession('DB_datausu') );
					@$datafinal_ano = date('Y',db_getsession('DB_datausu') );
    db_inputdata('datafinal',@$datafinal_dia,@$datafinal_mes,@$datafinal_ano,true,'text',4);
	  ?>
        </td>
      </tr>
      <tr> 
        <td class="tabs" nowrap><strong>Receita:</strong></td>
        <td class="tabs"><input type="text" name="receita"></td>
      </tr>
      <tr> 
        <td class="tabs" nowrap>&nbsp;</td>
        <td height="30" class="tabs"><input name="procurar" type="submit" id="procurar" value="Procurar"></td>
      </tr>
    </table>
</form>
<script>
   
document.form1.datainicial_dia.focus();
function js_validar() {
  var F = document.form1;
  if(F.receita.value == "" && F.conta.value == "" && (F.datainicial_dia.value == "" || F.datainicial_mes.value == "" || F.datainicial_ano.value == "" || F.datafinal_dia.value == "" || F.datafinal_mes.value == "" || F.datafinal_ano.value == "")) {
    alert("Informe algum campo");
	F.datainicial_dia.select();
    return false;
  }

}

</script>
<?
} else {
  
  $tabela = " arrecant ";
 	if(isset($matric)){
    $tabela = "arrematric";
	}else if(isset($inscr)){
    $tabela = "arreinscr";
	}else if(isset($numcgm)) {
		$tabela = "arrenumcgm";
	}

 	$sqlc = "select distinct
                  p.k00_numpre,
	                p.k00_numpar, 
					        p.k00_numtot,
					        p.k00_dtvenc,
					        p.k00_hist,
					        p.k00_receit,
					        k02_drecei,
					        k01_descr,
					        p.k00_valor,
					        0 as k00_conta,
					        null as k00_dtpaga,
					        cancdebitosproc.k23_obs,
					        cancdebitosproc.k23_usuario,
					        cancdebitosproc.k23_hora,
					        cancdebitosproc.k23_data,
					        login
	           from $tabela " . ($tabela == ""?"p":"");
    if ($tabela != "") {
       $sqlc .= " inner join arrecant p on p.k00_numpre = $tabela.k00_numpre ";
    }

       $sqlc .= " inner join arreinstit         on arreinstit.k00_numpre                 = p.k00_numpre
                           			 		           and arreinstit.k00_instit                 = ".db_getsession('DB_instit')." ";     
       $sqlc .= " inner join cancdebitosreg     on cancdebitosreg.k21_numpre             = p.k00_numpre 
                                               and cancdebitosreg.k21_numpar             = p.k00_numpar 
                                               and cancdebitosreg.k21_receit             = p.k00_receit "; 
       $sqlc .= " inner join cancdebitosprocreg on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia ";
       $sqlc .= " inner join cancdebitosproc    on cancdebitosprocreg.k24_codigo         = cancdebitosproc.k23_codigo ";

       // os sem movimento cancelados pelo dbpref não tem usuarios.....
       $sqlc .= "  left join db_usuarios on db_usuarios.id_usuario =  cancdebitosproc.k23_usuario ";
 	  if(isset($matric)){
     //	  $sqlc = $sqlc . "   	inner join arrematric on arrematric.k00_numpre = p.k00_numpre ";
	  } else if(isset($inscr)) {
     //	  $sqlc = $sqlc . "   	inner join arreinscr on arreinscr.k00_numpre = p.k00_numpre ";
	  }

       $sqlc .= "  left outer join arrepaga a on p.k00_numpre = a.k00_numpre 
                                          and p.k00_numpar    = a.k00_numpar
                                          and p.k00_receit    = a.k00_receit
                   left join arreprescr    on p.k00_numpre    = arreprescr.k30_numpre  
                                          and k30_anulado is false ";

    $querystring = "";
    if(isset($numcgm)) {
   	  $querystring = "numcgm=$numcgm";
  	  $sqlc = $sqlc . " inner join tabrec   on p.k00_receit       = k02_codigo
	            	   	    inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm	  
			                  inner join histcalc on p.k00_hist         = k01_codigo 
                        where arrenumcgm.k00_numcgm = ".$numcgm;
	  } else if(isset($matric)) {
   	  $querystring = "matric=$matric";
	    $sqlc = $sqlc . " inner join tabrec on p.k00_receit         = k02_codigo
	                     	inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
			                	inner join histcalc on p.k00_hist         = k01_codigo 
	                      where k00_matric = ".$matric;
	  } else if(isset($inscr)) {
   	  $querystring = "inscr=$inscr";
	    $sqlc = $sqlc . " inner join tabrec   on p.k00_receit       = k02_codigo
	                      inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
			   	              inner join histcalc on p.k00_hist         = k01_codigo 
	                      where k00_inscr = ".$inscr;
	  } else {
      $querystring = "numpre=$numpre";
      $sqlc = $sqlc . " inner join tabrec   on p.k00_receit       = k02_codigo
			                  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
			   	              inner join histcalc on p.k00_hist         = k01_codigo 
                        where p.k00_numpre = ".$numpre;
	  }
	$sqlc = $sqlc . " and a.k00_numpre is null and arreprescr.k30_numpre is null ";
	
	if (!empty($HTTP_POST_VARS["datainicial_dia"]) && !empty($HTTP_POST_VARS["datainicial_mes"]) && !empty($HTTP_POST_VARS["datainicial_ano"])&&!empty($HTTP_POST_VARS["datafinal_dia"]) && !empty($HTTP_POST_VARS["datafinal_mes"]) && !empty($HTTP_POST_VARS["datafinal_ano"])){
		
	    $datainicial = $HTTP_POST_VARS["datainicial_ano"]."-".$HTTP_POST_VARS["datainicial_mes"]."-".$HTTP_POST_VARS["datainicial_dia"];
   	  $querystring .= "&dataini=$datainicial";
  	  $datafinal = $HTTP_POST_VARS["datafinal_ano"]."-".$HTTP_POST_VARS["datafinal_mes"]."-".$HTTP_POST_VARS["datafinal_dia"];
   	  $querystring .= "&datafim=$datafinal";
   	  $sqlc .= " and cancdebitosproc.k23_data between '$datainicial' and '$datafinal' ";
   	  
	} else {
		
    	if (!empty($HTTP_POST_VARS["datafinal_dia"]) && !empty($HTTP_POST_VARS["datafinal_mes"]) && !empty($HTTP_POST_VARS["datafinal_ano"])) {
  	        $datafinal = $HTTP_POST_VARS["datafinal_ano"]."-".$HTTP_POST_VARS["datafinal_mes"]."-".$HTTP_POST_VARS["datafinal_dia"];
		        $querystring .= "&datafim=$datafinal";
            $sqlc .= " and cancdebitosproc.k23_data <= '$datafinal' ";
	    } else if(!empty($HTTP_POST_VARS["datainicial_dia"]) && !empty($HTTP_POST_VARS["datainicial_mes"]) && !empty($HTTP_POST_VARS["datainicial_ano"])) {
            $datainicial = $HTTP_POST_VARS["datainicial_ano"]."-".$HTTP_POST_VARS["datainicial_mes"]."-".$HTTP_POST_VARS["datainicial_dia"];
           	$querystring .= "&dataini=$datainicial";
            $sqlc .= " and cancdebitosproc.k23_data > '$datainicial' ";	
		  }
		  
	}
	if(!empty($HTTP_POST_VARS["receita"])){
 	  $querystring .= "&receita=".$HTTP_POST_VARS["receita"];
	  $sqlc .= " and p.k00_receit = ".$HTTP_POST_VARS["receita"];	  
	}	

    $sqlc .= " order by p.k00_numpre,p.k00_numpar";
    $dados = pg_exec($sqlc);
	
    $ConfCor1 = "#EFE029";
    $ConfCor2 = "#E4F471";
	  $numpre_cor = "";
	  $numpre_par = "";
	  $qcor= $ConfCor1;
	
	
    if(pg_numrows($dados) > 0) {
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr bgcolor="#ffcc66"> 
      <th width="9%" nowrap>numpre</th>
      <th width="4%" nowrap>par</th>
      <th width="4%" nowrap>tot</th>
      <th width="10%" nowrap>Vencimento</th>
      <th width="5%" nowrap>hist</th>
      <th width="11%" nowrap>descri&ccedil;&atilde;o</th>
      <th width="8%" nowrap>rec.</th>
      <th width="12%" nowrap>descri&ccedil;&atilde;o</th>
      <th width="9%" nowrap>valor</th>
    </tr>
	<?
    $totalpago = 0;
    for ($x=0;$x<pg_numrows($dados);$x++) {
	    db_fieldsmemory($dados,$x);
      if ($numpre_cor=="") {
		    $numpre_cor = $k00_numpre;
		    $numpre_par = $k00_numpar;
	    }
	    
	    if ($numpre_cor != $k00_numpre || $numpre_par != $k00_numpar ) {
        $numpre_cor = $k00_numpre;
		    $numpre_par = $k00_numpar;
        if ($qcor == $ConfCor1) {
		      $qcor = $ConfCor2;
        } else {
        	$qcor = $ConfCor1;
        }	
	    }
		 $histdesc = "";
		 $histdesc .= db_formatar($k23_data,"d")." ".$k23_hora." ".$login." ".addslashes($k23_obs);

     $histdesc = str_replace("\n", "", $histdesc);
     $histdesc = str_replace("\'", '\"', $histdesc);
     $histdesc = str_replace("\r", "<br>", $histdesc);
	  ?>
    <tr bgcolor="<?=$qcor?>"> 
      <td><div id="divlabel" width="9%" nowrap align="right" onmouseover='js_mostradiv("<?=$histdesc?>",true, this)' onmouseout="js_mostradiv('',false, this)"> <a href="javascript:parent.document.getElementById('processando').style.visibility = 'visible';history.back()"><?=$k00_numpre?></a></td>
      <td width="4%" nowrap align="right" ><?=$k00_numpar?></td>
      <td width="4%" nowrap align="right"><?=$k00_numtot?></td>
      <td width="10%" nowrap><?=db_formatar($k00_dtvenc,"d")?></td>
      <td width="5%" nowrap align="right"><?=$k00_hist?></td>
      <td width="11%" nowrap><?=$k01_descr?></td>
      <td width="8%" nowrap align="center"><?=$k00_receit?></td>
      <td width="12%" align="center" nowrap><?=$k02_drecei?></td>
      <td width="9%" nowrap align="right"><?=db_formatar(($k00_valor*-1),"f")?>&nbsp;</td>
    </tr>
    <?
    $totalpago += $k00_valor;
  	  }
	?> 

    <tr bgcolor="#ffcc66"> 
      <th width="31%" align="center" colspan="8" nowrap>Total Pago</th>
      <th width="9%" nowrap><?=db_formatar(($totalpago*-1),'f')?></th>
      <!--
      <th width="6%" nowrap></th>
      <th width="22%" nowrap></th>
      -->
    </tr>

    <tr>
      <td nowrap align="center" colspan="9">
        <input type="button" name="impcanc" value="Imprimir cancelamentos" onclick="js_imprime();">
      </td>
    </tr>

    <tr>
        <td height="30" colspan="11" align="center" class="tabs"></td>
    </tr>
</table>
<script>

function js_mostradiv(hist,mostra, object) {
  if(mostra == true){
   var camada = top.corpo.document.createElement("DIV");
   camada.setAttribute("id","info");
   camada.setAttribute("align","center");
   camada.style.backgroundColor = "#FFFF99";
   camada.style.layerBackgroundColor = "#FFFF99";
   camada.style.position = "absolute";
   camada.style.left = object.scrollWidth+"px";
   camada.style.top  = "132px";
   camada.style.zIndex = "1000";
   camada.style.visibility = 'visible';
   //camada.style.width = "500px";
   //camada.style.height = "60px";
   camada.innerHTML = '<table><tr><td>'+hist+'</td></tr></table>';
   top.corpo.document.body.appendChild(camada);
  }else{
    if(top.corpo.document.getElementById("info")){
     top.corpo.document.body.removeChild(top.corpo.document.getElementById("info"));
    } 
  }
}

function js_imprime(){
  jan = window.open('cai2_gerfinanc016.php?<?=($querystring)?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
    <? 
    } else 
	  $DB_ERRO = "Não existe cancelamentos efetuados para este numpre.";
    ?>
</center>
</body>
</html>
<?
} //fim do isset($tipo_cert) acima
if(isset($DB_ERRO)) {
  ?>
  <script>
    alert('<?=$DB_ERRO?>');
    parent.document.getElementById('processando').style.visibility = 'visible';
	history.back();
  </script>
  <?
}
?>