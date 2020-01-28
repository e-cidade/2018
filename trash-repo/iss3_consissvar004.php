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
  include("classes/db_issplan_classe.php");
  include("classes/db_issplanit_classe.php");
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
 $clissplanit = new cl_issplanit;
 $clissplan = new cl_issplan;
  $clissplanit->rotulo->label();
  $clissplan->rotulo->label();
  if(isset($string2)){
    $where_data= base64_decode($string);
  }else{
    $where_data="";
  }  

  $sql04 ="   select issplan.*,k00_dtpaga,arrecad.k00_valor as valdev,arrepaga.k00_valor as valpag 
	            from issplan 
							left join issplaninscr on q20_planilha = q24_planilha 
							inner join arrecad on issplan.q20_numpre = arrecad.k00_numpre 
							left outer join arrepaga on issplan.q20_numpre = arrepaga.k00_numpre 
							where q24_inscr=$q02_inscr and q20_situacao <> 5 $where_data order by q20_ano,q20_mes";

 // $sql04 = $clissplan->sql_query_arrecad("","issplan.*,k00_dtpaga,arrecad.k00_valor as valdev,arrepaga.k00_valor as valpag","q20_ano,q20_mes","q24_inscr=".$q02_inscr."  ".$where_data);
	
  $result04 = pg_query($sql04);
  $numrows04=pg_num_rows($result04);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
.cabec {
       text-align: center;
       font-size: 10px;
       font-weight: bold;
       background-color:#CDCDFF;
       border-color: darkblue;
	 }
  .corpo {
	 text-align: center;
	 font-size: 10px;
	 }
					      
  </style>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form name="form1" method="post" action="iss3_consissvar003.php">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
<?
  if($numrows04>0){
?>
	  <table border="1"  >
	    <tr class="cabec" >
	      <td><?=$RLq20_planilha?></td>
	      <td><?=$RLq20_numpre?></td>
	      <td><?=$RLq20_mes?></td>
	      <td><?=$RLq20_ano?></td>
	      <td><?=$RLq20_nomecontri?></td>
	      <td><?=$RLq20_fonecontri?></td>
	      <td>Recibo</td>
	      <td>Pago</td>
	      <td>Data</td>
	      <td>Valor</td>
	    </tr>
      <?
	  for($i=0; $i<$numrows04; $i++){
	      db_fieldsmemory($result04,$i);
	  if($q20_numpre!=""){
	    $recibo="Emitido";
	  }else{
	    $recibo="";
	  }  
	    
	  if($i%2==0){
	    $color="#97B5E6";
	  }else{
	    $color="#E796A4";
	  }
	  if($k00_dtpaga!=""){
	    $pago="SIM";
	    $data=db_formatar($k00_dtpaga,"d");
	    $k00_valor=$valpag;
	  }else{
	    $pago="NÂO";
	    $data="";
	    $k00_valor=$valdev;
	  }
  ?>
	  <tr class="corpo" bgcolor="<?=$color?>" >
         <td><a href="#" onclick="parent.js_planit(<?=$q20_planilha?>);return false;"><?=$q20_planilha?></a></td>
	  <td>&nbsp;<?=$q20_numpre?></td>
          <td>&nbsp;<?=ucfirst(db_mes($q20_mes))?></td>
	  <td>&nbsp;<?=$q20_ano?></td>
	  <td>&nbsp;<?=ucfirst($q20_nomecontri)?></td>
	  <td>&nbsp;<?=$q20_fonecontri?></td>
	  <td><b>&nbsp;<?=$recibo?></b></td>
	  <td><b>&nbsp;<?=$pago?></b></td>
	  <td>&nbsp;<?=$data?></td>
	  <td>&nbsp;<?=$k00_valor?></td>
	</tr>
<?
      }
?>
      </table>
<?
   }else{
?>
   <br><b>Nenhum registro encontrado.</b>
<?
	}
?>
    </td>
  </tr>
</table>
</form>
</body>
</html>