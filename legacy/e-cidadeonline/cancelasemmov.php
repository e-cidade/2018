<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_arrecant_classe.php");
include("classes/db_cancdebitos_classe.php");
include("classes/db_cancdebitosreg_classe.php");
include("classes/db_cancdebitosproc_classe.php");
include("classes/db_cancdebitosprocreg_classe.php");
include("classes/db_issvarsemmov_classe.php");
include("classes/db_issvarsemmovreg_classe.php");
db_postmemory($HTTP_POST_VARS);
$clarrecant           = new cl_arrecant;
$clcancdebitos        = new cl_cancdebitos;
$clcancdebitosreg     = new cl_cancdebitosreg;
$clcancdebitosproc    = new cl_cancdebitosproc;
$clcancdebitosprocreg = new cl_cancdebitosprocreg;
$clissvarsemmov       = new cl_issvarsemmov;
$clissvarsemmovreg    = new cl_issvarsemmovreg;
$sqlerro=false;
if(isset($cancelar)){
/* $sqlerro=false;
 db_inicio_transacao();
   $data = date("Y-m-d",db_getsession("DB_datausu"));
   $matriz01=split('#',$numpres);  
   for($q=0; $q<sizeof($matriz01); $q++ ){
     $matriz02=split("P",$matriz01[$q]); 
     echo "1=". $matriz02[0]." 2=".$matriz02[1];
     exit;
     $clarrecant->excluir_arrecant($matriz02[0],$matriz02[1]);
     if($clarrecant->erro_status==0){
       $sqlerro=true;
     } 
     
     //excluir da cancdebitos...cancdebitosreg...cancdebitosproc... cancdebitosprocreg
   }
 db_fim_transacao($sqlerro);
*/

  $sqlerro=false;
  db_inicio_transacao();
  $data = date("Y-m-d",db_getsession("DB_datausu"));
  $matriz01=split('#',$numpres);

  $totcancdebitos="";
  $cancdebitos1="";
  $totcancdebitosproc="";
  $cancdebitosproc1="";
  $issvarsemmov1 = "";
  $totissvarsemmov = "";

  for($q=0; $q<sizeof($matriz01); $q++ ){
  
     $matriz02=split("P",$matriz01[$q]);
    // select cancdebitosreg inner join cancdebitosprocreg para veficar se existe
    //echo "<br> numpre= ".$matriz02[0];
    // echo "<br> numpar= ".$matriz02[1];

    $sqlcanc = "
	     select k21_sequencia as cancdebitosreg,
				k24_sequencia as cancdebitosprocreg, 
				k21_codigo    as cancdebitos ,
				k24_codigo    as cancdebitosproc 
		 from cancdebitosreg 
		 inner join cancdebitosprocreg on k24_cancdebitosreg = k21_sequencia
		 where k21_numpre= ".$matriz02[0]." and k21_numpar =".$matriz02[1]; 
    
    $resultcanc = db_query($sqlcanc);
    $linhascanc = pg_num_rows($resultcanc);

    if($linhascanc>0){
      db_fieldsmemory($resultcanc,0);
      /*
       echo "<br>linhas = $linhascanc
       <br>cancdebitosreg = $cancdebitosreg
       <br>cancdebitosprocreg =$cancdebitosprocreg
       <br>cancdebitos= $cancdebitos
       <br>cancdebitosproc = $cancdebitosproc";
       */
      // e guarda o lote das tabelas cancdebitosproc,cancdebitos
      if($cancdebitos1!=$cancdebitos){
        $cancdebitos1 = $cancdebitos;
        $totcancdebitos .= $cancdebitos."#";
      }
      if($cancdebitosproc1!=$cancdebitosproc){
        $cancdebitosproc1 = $cancdebitosproc;
        $totcancdebitosproc .= $cancdebitosproc."#";
      }
      // excluir da cancdebitosprocreg,cancdebitosreg
      $clcancdebitosprocreg->excluir(null,'k24_cancdebitosreg = '.$cancdebitosreg);
      if($clcancdebitosprocreg->erro_status==0){
        $sqlerro=true;
        $erro = $clcancdebitosprocreg->erro_sql;
       
      }
      $clcancdebitosreg->excluir($cancdebitosreg);
      if($clcancdebitosreg->erro_status==0){
        $sqlerro=true;
        $erro = $clcancdebitosreg->erro_sql;
       
      }

    }
    //
    if($sqlerro==false){
      $sqliss="
			select  q15_issvarsemmov as issvarsemmov, 
					q15_sequencial   as issvarsemmovreg, 
					q05_codigo 
            from issvar 
			inner join issvarsemmovreg on q05_codigo = q15_issvar
			where q05_numpre=".$matriz02[0]." and q05_numpar =".$matriz02[1];
      //echo "<br>$sqliss<br><br>";
      $resultiss = db_query($sqliss);
      $linhasiss = pg_num_rows($resultiss);
      if($linhasiss>0){
        db_fieldsmemory($resultiss,0);
        // guardar issvarsemmov
        if($issvarsemmov1!=$issvarsemmov){
          $issvarsemmov1    = $issvarsemmov;
          $totissvarsemmov .= $issvarsemmov."#";
        }
        // *************** excluir da  issvarsemmovreg
        $clissvarsemmovreg->excluir($issvarsemmovreg);
        if($clissvarsemmovreg->erro_status==0){
          $sqlerro=true;
          $erro = $clissvarsemmovreg->erro_sql;
          
        }
      }

         
      $clarrecant->excluir_arrecant($matriz02[0],$matriz02[1]);
      if($clarrecant->erro_status==0){
        $sqlerro=true;
        $erro = $clarrecant->erro_sql;
        
      }
    }
  }//fecha o for
  /*
  echo "<br> totcancdebitos = $totcancdebitos
  <br> totcancdebitosproc = $totcancdebitosproc
  <br> totissvarsemmov = $totissvarsemmov <br>";*/
  // XXXXXXXXXXXXXXXXXXXXXXXX  ISSVARSEMMOV XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
  // separar os registros da $totissvarsemmov
  if($sqlerro==false){
   
    $issvarsemmov2=split('#',$totissvarsemmov);
    $linhas2 = (sizeof($issvarsemmov2)-1);
    for($x=0; $x<$linhas2; $x++ ){
      $sqlsemmovreg = "select * from  issvarsemmovreg where q15_issvarsemmov= $issvarsemmov2[$x]";
      $resultsemmovreg = db_query($sqlsemmovreg);
      $linhassemmovreg = pg_num_rows($resultsemmovreg);
      if($linhassemmovreg=0){
        // *************** excluir da  issvarsemmov
        $clissvarsemmov->excluir($issvarsemmov2[$x]);
        if($clissvarsemmov->erro_status==0){
          $sqlerro=true;
          $erro = "exclução da issvarsemmov...".$clissvarsemmov->erro_sql;
         
        }
      }
    }
  }
  // XXXXXXXXXXXXXXXXXXXXXXXX  CANCDEBITOSPROC XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
  // separar os registros da $totcancdebitosproc
  if($sqlerro==false){
    $cancdebitosproc2=split('#',$totcancdebitosproc);
    $linhas3 = (sizeof($cancdebitosproc2)-1);
    for($a=0; $a<$linhas3; $a++ ){
      $sqlprocreg = "select * from cancdebitosprocreg  where k24_codigo = $cancdebitosproc2[$a]";
      $resultprocreg = db_query($sqlprocreg);
      $linhasprocreg = pg_num_rows($resultprocreg);
      if($linhasprocreg=0){
        // *************** excluir da  cancdebitosproc
        $clcancdebitosproc->excluir($cancdebitosproc2[$a]);
        if($clcancdebitosproc->erro_status==0){
          $sqlerro=true;
          $erro = "exclução da cancdebitosproc...".$clcancdebitosproc->erro_sql;
          
        }
      }
    }
  }
  // XXXXXXXXXXXXXXXXXXXXXXXX  CANCDEBITOS XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
  // separar os registros da $totcancdebitos
  if($sqlerro==false){
    $cancdebitos2=split('#',$totcancdebitos);
    $linhas4 = (sizeof($cancdebitos2)-1);
    for($b=0; $b<$linhas4; $b++ ){
      $sqldeb = "select * from cancdebitosreg where k21_codigo = $cancdebitos2[$b]";
      $resultdeb = db_query($sqldeb);
      $linhasdeb = pg_num_rows($resultdeb);

      if($linhasdeb=0){
        // *************** excluir da  cancdebitosproc
        $clcancdebitos->excluir($cancdebitos2[$b]);
        if($clcancdebitos->erro_status==0){
          $sqlerro=true;
          $erro = "exclução da cancdebitos...".$clcancdebitos->erro_sql;
          
        }
      }
    }
  }
  // $sqlerro =true;
  db_fim_transacao($sqlerro);

}
if(isset($cancelar)){
  if($sqlerro==true){
	  db_msgbox("erro = $erro");
	}else{
	  db_msgbox("Cancelamento efetuado com sucesso.");
	}  
}


$sql = "SELECT ISSVAR.*, ARREINSCR.* FROM ARREINSCR
	      INNER JOIN ISSVAR ON ISSVAR.Q05_NUMPRE = ARREINSCR.K00_NUMPRE 
		    INNER JOIN ARRECANT ON ARRECANT.K00_NUMPRE = Q05_NUMPRE AND K00_NUMPAR = ISSVAR.Q05_NUMPAR 
		    WHERE ARRECANT.K00_VALOR='0' AND K00_INSCR = $inscr AND Q05_VALOR = 0 ORDER BY Q05_ANO, Q05_MES";
$result= db_query($sql);
$linhas = pg_num_rows($result);

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
<script>

function js_marca(){
	var obj = document.form1;
	sus = '';
	obj.numpres.value = '';
	for(i=0; i < obj.length; i++){
      if(obj[i].type == 'checkbox'){
        if(obj[i].checked==true){
           obj.numpres.value += sus+obj[i].value;
           sus = '#';           
        }
      }
  	}
	//alert("np="+obj.numpres.value);
}
</script>
<form name="form1" action="">
<input name='numpres' type='hidden' value=''>
<input name='inscr' type='hidden' value='<?=@$inscr?>'>
<table width="100%" border="0" cellpadding="1" cellspacing="0" align = "center">
	<tr>
		<td align = "center"><br><font class = "titulo3"  >Cancelamento de lançamentos sem movimentos</font><br><br></td>
	</tr>
	<tr>
		<td>
			<table width="90%" border="1" cellpadding="1" cellspacing="0" align = "center">
			
			<?
		if ($linhas>0){
			?>
			
			<tr class ="titulo" bgcolor="#CCCCCC">
				<td>&nbsp;</td>
				<td align = "center">Numpre</td>
				<td align = "center">Parcela</td>
				<td align = "center">Mês</td>
				<td align = "center">Ano</td>
				<td align = "center">Valor</td>
				<td align = "center">Histórico</td>
				
			</tr>
			
		<?
		
			for($i = 0;$i < $linhas;$i++) {
				db_fieldsmemory($result,$i);	
				echo "<tr class ='texto'>";
					echo "<td align = 'center'>
                              <input name='checa' type='checkbox' value=".$q05_numpre."P".$q05_numpar." onclick ='js_marca()' ></td>";
					echo "<td align = 'center'>$q05_numpre</td>";
					echo "<td align = 'center'>$q05_numpar</td>";
					echo "<td align = 'center'>$q05_mes</td>";
					echo "<td align = 'center'>$q05_ano</td>";
					echo "<td align = 'center'>$q05_valor</td>";
					echo "<td align = 'center'>$q05_histor</td>";
					
				echo "</tr>";
		  	}
		
		echo"</table>";
		echo"</td>";
	echo"</tr>";
echo "<tr>";
echo "<td align ='center'><br> <input name='cancelar' type='submit' value='Cancelar'> </td>";
echo "</tr>";


}else{
	echo"<tr>";
		echo "<td align = 'center'>Não existe lançamentos sem movimento</td>";
	echo"</tr>";
}
  

  
  	
?>
</table>
</form>
</body>