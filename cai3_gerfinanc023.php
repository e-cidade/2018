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
include("libs/db_sql.php");
include("classes/db_arreprescr_classe.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$clarreprescr = new cl_arreprescr;
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js" ></script>
<script>
function js_imprime(){
	var tipo        = document.form1.tipo_filtro.value;
	var cod         = document.form1.cod_filtro.value;
	var datainicial = document.form1.datainicial.value;
	var datafinal   = document.form1.datafinal.value;
	jan = window.open('cai3_reljustificado.php?tipo_filtro='+tipo+'&cod_filtro='+cod+'&datainicial='+datainicial+'&datafinal='+datafinal,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<style>
</style>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<form name='form1'>
<input name="tipo_filtro" type="hidden" value="<?=$tipo_filtro?>">
<input name="cod_filtro"  type="hidden" value="<?=$cod_filtro?>" >
<input name="datainicial" type="hidden" value="<?=$datainicial?>">
<input name="datafinal"   type="hidden" value="<?=$datafinal?>"  >

<center>
<?
  $where ="";
	if ($datainicial != "--" and $datafinal != "--"){
       
			  $where .= " and (k27_data,k27_data+k27_dias) overlaps (DATE '$datainicial' - '1 day'::interval, DATE '$datafinal'+ '1 day'::interval)";

	}else{	
    if($datainicial!="--"){
      $where .=" and k27_data >= '$datainicial'";
   }
  
    if($datafinal!="--"){
      $where .=" and k27_data <= '$datafinal'";
   }
  }
  
  
	//die("ccccc".$tipo_filtro);
  if ($tipo_filtro=="CGM"){
		 $numcgm = $cod_filtro;
		 $sqlarrejustreg = "select k28_numpre,k28_numpar,k28_receita,nome,k27_data,k27_hora,k27_dias,k27_obs,k28_receita,
                        case when arrematric.k00_numpre is not null then 'Matricula - '|| k00_matric
                             when arreinscr.k00_numpre is not null  then 'Inscricao - '||k00_inscr 
                             else 'CGM - '||k00_numcgm  
                        end as origem 
                        from arrejustreg 
												inner join arreinstit on arreinstit.k00_numpre = arrejustreg.k28_numpre 
												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
                        inner join arrejust    on k28_arrejust = k27_sequencia 
	                      inner join db_usuarios on k27_usuario  = id_usuario 
                        inner join arrenumcgm  on k28_numpre   = arrenumcgm.k00_numpre
						            left  join arrematric  on k28_numpre   = arrematric.k00_numpre
                        left  join arreinscr   on k28_numpre   = arreinscr.k00_numpre
                        where k00_numcgm = $cod_filtro $where";	
	 
  }else if ($tipo_filtro=="MATRICULA"){
		 $matric = $cod_filtro;
     $sqlarrejustreg = "select k28_numpre,k28_numpar,k28_receita,nome,k27_data,k27_hora,k27_dias,k27_obs,k28_receita, 'Matricula - '||k00_matric  as origem 
		                      from arrejustreg 
												inner join arreinstit on arreinstit.k00_numpre = arrejustreg.k28_numpre 
												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
										 	  inner join arrejust on k28_arrejust=k27_sequencia 
	                      inner join db_usuarios on k27_usuario = id_usuario 
                        inner join arrematric on k28_numpre = arrematric.k00_numpre		
											  where k00_matric =  $cod_filtro $where";
  }else if ($tipo_filtro=="INSCRICAO"){
		 $inscr = $cod_filtro;
     $sqlarrejustreg = "select k28_numpre,k28_numpar,k28_receita,nome,k27_data,k27_hora,k27_dias,k27_obs,k28_receita, 'Inscricao - '||k00_inscr  as origem 
                        from arrejustreg
												inner join arreinstit on arreinstit.k00_numpre = arrejustreg.k28_numpre 
												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
                        inner join arrejust on k28_arrejust=k27_sequencia 
	                      inner join db_usuarios on k27_usuario = id_usuario  
                        inner join arreinscr on k28_numpre = arreinscr.k00_numpre
                        where k00_inscr = $cod_filtro $where";
  }else if ($tipo_filtro=="NUMPRE"){
		 $numpre = $cod_filtro;
     $sqlarrejustreg = "select k28_numpre,k28_numpar,k28_receita,nome,k27_data,k27_hora,k27_dias,k27_obs,k28_receita, 'Numpre - '||k28_numpre  as origem 
                      	  from arrejustreg 	
												inner join arreinstit on arreinstit.k00_numpre = arrejustreg.k28_numpre 
												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
		                    inner join arrejust on k28_arrejust=k27_sequencia 
	                      inner join db_usuarios on k27_usuario = id_usuario 
                        where k28_numpre = $cod_filtro $where";
  }
//  die("$sqlarrejustreg");
  $resultarrejustreg = pg_query($sqlarrejustreg);
	$linhasarrejustreg = pg_num_rows($resultarrejustreg);

  $ConfCor1 = "#EFE029";
  $ConfCor2 = "#E4F471";
	$numpre_cor = "";
	$numpre_par = "";
	$qcor= $ConfCor1;
  if($linhasarrejustreg > 0) {
      echo " 	<table width='98%' border='0' cellspacing='0' cellpadding='3'> ";
      echo "     <tr bgcolor='#ffcc66'>  ";
      echo "       <th  nowrap> Numpre     </th> ";
      echo "       <th  nowrap> Parcela    </th> ";
      echo "       <th  nowrap> Receita    </th> ";
      echo "       <th  nowrap> Data       </th> ";
      echo "       <th  nowrap> Hora       </th> ";
      echo "       <th  nowrap> Dias just. </th> ";
      echo "       <th  nowrap> Usuário    </th> ";
      echo "       <th  nowrap> Observação </th> ";
      echo "       <th  nowrap> Origem     </th> ";
      echo "     </tr> ";
      $total = 0;
      for($x=0;$x<$linhasarrejustreg;$x++){
      	db_fieldsmemory($resultarrejustreg,$x);
        if($numpre_cor==""){
		      $numpre_cor = $k28_numpre;
		      $numpre_par = $k28_numpar;
	      }
	      if($numpre_cor != $k28_numpre || $numpre_par != $k28_numpar ){
          $numpre_cor = $k28_numpre;
		      $numpre_par = $k28_numpar;
          if($qcor == $ConfCor1){
		        $qcor = $ConfCor2;
		      }else{
            $qcor = $ConfCor1;
          }
	      }

       // $histdesc = "$k31_hora $login $k31_obs";
//        db_msgbox($histdesc);

        echo " <tr bgcolor='$qcor'>  ";
        echo "   <td  nowrap align='center'>  ".$k28_numpre."                </td> ";
        echo "   <td  nowrap align='center'>  ".$k28_numpar."                </td> ";
        echo "   <td  nowrap align='center'>  ".$k28_receita."               </td> ";
        echo "   <td  nowrap align='center'>  ".db_formatar($k27_data,'d')." </td> ";
        echo "   <td  nowrap align='center'>  ".$k27_hora."                  </td> ";
        echo "   <td  nowrap align='center'>  ".$k27_dias."                  </td> ";
        echo "   <td  nowrap align='center'>  ".$nome."                      </td> ";
       $obs = substr("$k27_obs", 0, 25)."...";
//$obs="vvvcccccccccc";
        ?>
         
        <td>
          <div id="divlabel" width="9%" nowrap align="right" onmouseover="js_mostradiv('<?=$k27_obs?>',true)" onmouseout="js_mostradiv('',false)">
            <?=$obs?>
          </div> 
        </td>
        <?
        echo "   <td  nowrap align='center'>  ".$origem."                    </td> ";
        echo " </tr> ";
      
      //  $total += $valor;
      }
  }else{
      db_msgbox("Débitos Justificados nao encontrados !");  
			db_redireciona("cai3_gerfinanc024.php?tipo_filtro=$tipo_filtro&cod_filtro=$cod_filtro");
  }

	?> 
    <tr>
      <td  colspan="11" align="center" class="tabs">
			  <input type="button" name="imprimir" value="Imprimir" onclick="js_imprime();">
			  <input type="hidden" name="matric"   value="<?=@$matric?>">
			  <input type="hidden" name="inscr"    value="<?=@$inscr?>">
			  <input type="hidden" name="numcgm"   value="<?=@$numcgm?>">
			  <input type="hidden" name="numpre"   value="<?=@$numpre?>">
			</td>
    </tr>
</table>
</center>
</form>
</body>
</html>
<script>
function js_mostradiv(hist,mostra) {

  if(mostra == true){
  
   var camada = top.corpo.document.createElement("DIV");
   camada.setAttribute("id","info");
   camada.setAttribute("align","center");
   camada.style.backgroundColor = "#FFFF99";
   camada.style.layerBackgroundColor = "#FFFF99";
   camada.style.position = "absolute";
   camada.style.left = "82px";
   camada.style.top = "135px";
   camada.style.zIndex = "1000";
   camada.style.visibility = 'visible';
   camada.style.width = "500px";
   //camada.style.height = "60px";
   camada.innerHTML = '<table><tr><td>'+hist+'</td></tr></table>';
   top.corpo.document.body.appendChild(camada);
  }else{
   if(top.corpo.document.getElementById("info")){
     top.corpo.document.body.removeChild(top.corpo.document.getElementById("info"));
    } 
  }
}
</script>
<?
 //fim do isset($tipo_cert) acima
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