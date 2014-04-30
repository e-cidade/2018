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
  include("classes/db_issvar_classe.php");
  include("classes/db_issvarnotas_classe.php");
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  $clissvar = new cl_issvar;
  $clissvarnotas = new cl_issvarnotas;
  $clissvar->rotulo->label();
  if(isset($string)){
    $where_data= base64_decode($string);
  }else{
    $where_data="";
  }  
  if($tipo=="cgm"){
    $z01_numcgm=$valor;
    $sql04="select  issvar.*,
										k00_dtpaga,
										arrecad.k00_valor as valdev,
										arrepaga.k00_valor as valpag 
						from arrenumcgm 
						inner join issvar        on issvar.q05_numpre     = arrenumcgm.k00_numpre 
						left join arrecad        on issvar.q05_numpre     = arrecad.k00_numpre
						                        and issvar.q05_numpar     = arrecad.k00_numpar
						left outer join arrepaga on arrenumcgm.k00_numpre = arrepaga.k00_numpre 
						                        and issvar.q05_numpar=arrepaga.k00_numpar
						where arrenumcgm.k00_numcgm=$z01_numcgm
            order by q05_ano desc, q05_mes desc
            ";
    
    
    //$sql04 = $clissvar->sql_query_arrenumcgm("","issvar.*,k00_dtpaga,arrecad.k00_valor as valdev,arrepaga.k00_valor as valpag","","arrenumcgm.k00_numcgm=".$z01_numcgm."  ".$where_data);
   
  }else if($tipo=="inscr"){
    $q02_inscr=$valor;
    // $sql04 = $clissvar->sql_query_arreinscr("","issvar.*,k00_dtpaga,arrecad.k00_valor as valdev,arrepaga.k00_valor as valpag","q05_ano desc","k00_inscr=".$q02_inscr."  ".$where_data);
			
			$sql04 ="
			        select issvar.*,k00_dtpaga,arrecad.k00_valor as valdev,arrepaga.k00_valor as valpag 
							from issvar 
							inner join arreinscr on issvar.q05_numpre    = arreinscr.k00_numpre 
							left  join arrecad   on arreinscr.k00_numpre = arrecad.k00_numpre 
							                    and issvar.q05_numpar    = arrecad.k00_numpar 
							left  join arrepaga  on arreinscr.k00_numpre = arrepaga.k00_numpre 
							                    and issvar.q05_numpar    = arrepaga.k00_numpar
							left join	issplannumpre on q32_numpre	       =  issvar.q05_numpre  						
							where k00_inscr = $q02_inscr and coalesce(q32_status,0) <> 2 
							 $where_data
							order by q05_ano desc, q05_mes desc ";
        // echo "<br>".$sql04;
		 
  }
 
  $result04  = pg_query($sql04);
  $numrows04 = pg_num_rows($result04)
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
        <tr class="cabec">
	  <td><?=$RLq05_codigo?></td>
	  <td><?=$RLq05_valor?></td>
	  <td><?=$RLq05_ano?></td>
	  <td><?=$RLq05_mes?></td>
	  <td><?=$RLq05_histor?></td>
	  <td><?=$RLq05_aliq?></td>
	  <td><?=$RLq05_bruto?></td>
	  <td><?=$RLq05_vlrinf?></td>
	  <td>Pago</td>
	  <td>Data</td>
	  <td>valor</td>
	</tr>
<?
  for($i=0; $i<$numrows04; $i++){
	db_fieldsmemory($result04,$i);
        $result00=$clissvarnotas->sql_record($clissvarnotas->sql_query_file($q05_codigo,"","q06_nota,q06_valor"));
	$numrows00=$clissvarnotas->numrows;
	$notas="";
	$susteni="";
	for($c=0; $c<$numrows00; $c++){
	  db_fieldsmemory($result00,$c);
	  $notas.=$susteni.$q06_nota."-".$q06_valor;
	  $susteni="#";
	  
	}
	  
	echo "<input name='notas_$q05_codigo' id='notas_$q05_codigo' value='$notas' type='hidden' >";
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
         <td><a href="#" onclick="return false;" onMouseOut='parent.js_label(false);' onMouseOver='parent.js_label(true,event,<?=$q05_codigo?>,<?=$q05_numpre?>,<?=$q05_numpar?>);' ><?=$q05_codigo?></a></td>
	  <td>&nbsp;<?=$q05_valor?></td>
	  <td>&nbsp;<?=$q05_ano?></td>
	  <td>&nbsp;<?=ucfirst(db_mes($q05_mes))?></td>
	  <td>&nbsp;<?=$q05_histor?></td>
	  <td>&nbsp;<?=$q05_aliq?></td>
	  <td>&nbsp;<?=$q05_bruto?></td>
	  <td>&nbsp;<?=$q05_vlrinf?></td>
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