<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

$clarreprescr = new cl_arreprescr;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js" ></script>
<script>
function js_imprime() {

  var frm      = document.form1;
  var queryStr = '';
  var matric   = frm.matric.value;
  var inscr    = frm.inscr.value;
  var numcgm   = frm.numcgm.value;
  var numpre   = frm.numpre.value;
  if(matric != ''){
		queryStr = 'tipoorigem=matric&valororigem='+matric;
  }else if(inscr != ''){
		queryStr = 'tipoorigem=inscr&valororigem='+inscr;
  }else if(numcgm != ''){
		queryStr = 'tipoorigem=numcgm&valororigem='+numcgm;
  }else if(numpre != ''){
		queryStr = 'tipoorigem=numpre&valororigem='+numpre;
  }

  window.open('cai3_gerfinanc021.php?'+queryStr,'',
              'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ')
}
function js_OpenRelatorio(xxaondeJanela,xxnomeJanela,xxarquivoJanela,xxtituloJanela,xxmostraJanela){
  alert('1');
	eval('var nomeIframe = '+xxnomeJanela+'_status;');
  js_OpenJanelaIframe('top.corpo',nomeIframe,'janelaStatus.php','Aguarde Processando',true,30,30,100,60);
  alert('2');
  js_OpenJanelaIframe(xxaondeJanela,nomeIframe,xxarquivoJanela,xxtituloJanela,xxmostraJanela);
  alert('3');
}


</script>
<style>
</style>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<form name='form1'>
<center>
<?
  $where = "";
  $inner = "";

  if ($tipo_filtro=="CGM"){
		 $numcgm = $cod_filtro;
     $inner = " inner join arrenumcgm on arrenumcgm.k00_numpre = arreprescr.k30_numpre   ";
     $where = " where arrenumcgm.k00_numcgm = $cod_filtro";
  }else if ($tipo_filtro=="MATRICULA"){
		 $matric = $cod_filtro;
     $inner = " inner join arrematric on arrematric.k00_numpre = arreprescr.k30_numpre   ";
     $where = " where arrematric.k00_matric = $cod_filtro";
  }else if ($tipo_filtro=="INSCRICAO"){
		 $inscr = $cod_filtro;
     $inner = " inner join arreinscr on arreinscr.k00_numpre = arreprescr.k30_numpre   ";
     $where = " where arreinscr.k00_inscr = $cod_filtro";
  }else if ($tipo_filtro=="NUMPRE"){
		 $numpre = $cod_filtro;
     $where  = " where arreprescr.k30_numpre = $cod_filtro";
  }else if ($tipo_filtro=="PARCEL"){
		 $numpre = $cod_filtro;
     $where  = " where arreprescr.k30_numpre = ( select v07_numpre from termo where v07_parcel = $cod_filtro )";
  }
  $campos         = " login, k31_data, k31_hora, k31_obs, k30_numpre,k30_dtoper, k30_numpar,k30_numtot,k30_dtvenc,k30_hist,k01_descr,k30_receit,k02_drecei,(k30_vlrcorr+k30_vlrjuros+k30_multa-k30_desconto) as valor ";
  $sqlArreprescr  =  " select $campos from arreprescr ";
	$sqlArreprescr .=  "        inner join arreinstit  on arreinstit.k00_numpre =	arreprescr.k30_numpre ";
	$sqlArreprescr .=  "                              and arreinstit.k00_instit =	".db_getsession('DB_instit') ;
  $sqlArreprescr .=  "        inner join prescricao  on k31_codigo  = k30_prescricao";
  $sqlArreprescr .=  "        inner join db_usuarios on k31_usuario = id_usuario ";
  $sqlArreprescr .=  "        inner join tabrec      on k30_receit  = k02_codigo ";
  $sqlArreprescr .=  "        inner join histcalc    on k30_hist    = k01_codigo ";
  $sqlArreprescr .=  $inner;
  $sqlArreprescr .=  $where." and k30_anulado is false ";

  $rsArreprescr  =  $clarreprescr->sql_record($sqlArreprescr);

  $ConfCor1 = "#EFE029";
  $ConfCor2 = "#E4F471";
	$numpre_cor = "";
	$numpre_par = "";
	$qcor= $ConfCor1;
  $intNumrows = $clarreprescr->numrows;
  if($intNumrows > 0) {

      echo " 	<table width='100%' border='0' cellspacing='0' cellpadding='3'> ";
      echo "     <tr bgcolor='#ffcc66'>  ";
      echo "       <th width='11%' nowrap> Numpre    </th> ";
      echo "       <th width='9%'  nowrap> Opera&ccedil;&atilde;o  </th> ";
      echo "       <th width='4%'  nowrap> Par       </th> ";
      echo "       <th width='4%'  nowrap> Tot       </th> ";
      echo "       <th width='10%' nowrap> Venc      </th> ";
      echo "       <th width='5%'  nowrap> Hist      </th> ";
      echo "       <th width='11%' nowrap> Descri&ccedil;&atilde;o</th> ";
      echo "       <th width='8%'  nowrap> Rec.      </th> ";
      echo "       <th width='12%' nowrap> Descri&ccedil;&atilde;o</th> ";
      echo "       <th width='9%'  nowrap> Valor     </th> ";
      echo "     </tr> ";
      $total = 0;
      for($x=0;$x<$intNumrows;$x++){
      	db_fieldsmemory($rsArreprescr,$x);
        if($numpre_cor==""){
		      $numpre_cor = $k30_numpre;
		      $numpre_par = $k30_numpar;
	      }
	      if($numpre_cor != $k30_numpre || $numpre_par != $k30_numpar ){
          $numpre_cor = $k30_numpre;
		      $numpre_par = $k30_numpar;
          if($qcor == $ConfCor1){
		        $qcor = $ConfCor2;
		      }else{
            $qcor = $ConfCor1;
          }
	      }

        $histdesc = db_formatar($k31_data, 'd') . " - $k31_hora - $login - $k31_obs";
        echo " <tr bgcolor='$qcor'>  ";
        ?>
        <td width="9%" nowrap align="center">
         <a OnMouseOut="parent.js_label('false','');"  OnMouseOver="parent.js_label('true','<?=db_jsspecialchars($histdesc)?>');" href="javascript:parent.document.getElementById('processando').style.visibility = 'visible';history.back()"><?=$k30_numpre?></a></td>
        <?
        echo "   <td width='9%'  nowrap align='right'>  ".db_formatar($k30_dtoper,'d')." </td> ";
        echo "   <td width='4%'  nowrap align='right'>  ".$k30_numpar."                  </td> ";
        echo "   <td width='4%'  nowrap align='right'>  ".$k30_numtot."                  </td> ";
        echo "   <td width='10%' nowrap>                ".db_formatar($k30_dtvenc,'d')." </td> ";
        echo "   <td width='5%'  nowrap align='right'>  ".$k30_hist."                    </td> ";
        echo "   <td width='11%' nowrap>                ".str_pad($k01_descr,20)."       </td> ";
        echo "   <td width='8%'  nowrap align='center'> ".$k30_receit."                  </td> ";
        echo "   <td width='12%' align='center' nowrap> ".str_pad($k02_drecei,40)."      </td> ";
        echo "   <td width='9%'  nowrap align='right'>  ".db_formatar($valor,'f')."&nbsp;</td> ";
        echo " </tr> ";

        $total += $valor;
      }
  }else{
      db_msgbox("Débitos prescritos nao encontrados !");
  }
	?>
    <tr bgcolor="#ffcc66">
      <th width="10%" align="center" colspan="9" nowrap>Total Prescrito: </th>
      <th width="8%"  nowrap><?=db_formatar($total,'f')?></th>
    </tr>
    <tr>
      <td  colspan="11" align="center" class="tabs">
			  <input type="button" name="imprimir" value="Imprimir" onclick="js_imprime()">
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
<?php
if(isset($DB_ERRO)) {
  ?>
  <script>
    alert('<?=$DB_ERRO?>');
    parent.document.getElementById('processando').style.visibility = 'visible';
   	history.back();
  </script>
  <?php
}
?>