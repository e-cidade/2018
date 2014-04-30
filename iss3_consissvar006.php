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
  include("classes/db_issplanit_classe.php");
  include("classes/db_issplan_classe.php");
  include("classes/db_issplanitinscr_classe.php");
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  $clissplanit = new cl_issplanit;
  $clissplan = new cl_issplan;
  $clissplanitinscr = new cl_issplanitinscr;
  $clissplanit->rotulo->label();
  $clissplanitinscr->rotulo->label();
  $dbwhere="";
  if(isset($q02_inscr)){ 
  //  $sql01=$clissplanit->sql_query("","issplanit.*,q31_inscr, a.z01_numcgm, a.z01_nome",""," q31_inscr=$q02_inscr ");
		$sql01= "
		select issplanit.*,q31_inscr, z01_numcgm, z01_nome
		from issplanit 
		inner join issplanitinscr on q31_issplanit = q21_sequencial
		inner join issplan on issplan.q20_planilha = issplanit.q21_planilha 
		inner join cgm on cgm.z01_numcgm = issplan.q20_numcgm 
		where q31_inscr=$q02_inscr and q21_status = 1 ";

   
  }
  
  if(isset($z01_cgccpf)){
    //$sql02=$clissplanit->sql_query("","issplanit.*,q31_inscr, a.z01_numcgm, a.z01_nome",""," q21_cnpj='$z01_cgccpf' ");
		$sql02="select issplanit.*,q31_inscr, z01_numcgm, z01_nome 
						from issplanit 
						left join issplanitinscr on q31_issplanit = q21_sequencial
						inner join issplan on issplan.q20_planilha = issplanit.q21_planilha 
						inner join cgm on cgm.z01_numcgm = issplan.q20_numcgm 
						where q21_cnpj='".$z01_cgccpf."' and q21_status = 1";
    
  }
  if(isset($sql01) && isset($sql02)){
    $result00 = pg_query($sql01." union  ".$sql02);
  }else if(isset($sql02)){  
    $result00=pg_query($sql02);
  }else{
    $result00=pg_query($sql01);
  }
  $numrows00=pg_num_rows($result00);
  
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
       }
				            
</style>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="iss3_consissvar003.php">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">
<?
  if($numrows00>0){
    
?>
      <table border="1"  >
        <tr class="cabec" bgcolor="<?=$color?>" >
	  <td>&nbsp;<?=$RLq21_planilha?></td>
	  <td>&nbsp;<?=$RLq31_inscr?></td>
	  <td>&nbsp;<?=$RLq21_nome?></td>
	  <td>&nbsp;<?=$RLq21_cnpj?></td>
	  <td>&nbsp;<?=$RLq21_servico?></td>
	  <td>&nbsp;<?=$RLq21_valor?></td>
	  <td>&nbsp;<?=$RLq21_valorser?></td>
	  <td>&nbsp;<?=$RLq21_aliq?></td>
	  <td>&nbsp;<?=$RLq21_nota?></td>
	  <td>&nbsp;<?=$RLq21_serie?></td>
	  <td>&nbsp;CGM do Tomador</td>
	  <td>&nbsp;Nome do Tomador</td>
	</tr>  
<?
        $str=false;
	$plans="";
	$virg="";
	$cont="";
        for($c=0; $c<$numrows00; $c++){
	  db_fieldsmemory($result00,$c);
	  
	  $clissplan->sql_record($clissplan->sql_query_file($q21_planilha));
	  if($str==false && $clissplan->numrows==0){
	    $plans.=$virg.$q21_planilha;
	    $virg=",";
	    $cont++;
	    continue;
	  }else{
            $str=true;
	  }
  	  if($c%2==0){
	    $color="#97B5E6";
	  }else{
	    $color="#E796A4";
	  }
?>
	  
        <tr class="corpo" bgcolor="<?=$color?>" >
	  <td>&nbsp;<?=$q21_planilha?></td>
	  <td>&nbsp;<?=$q31_inscr?></td>
	  <td>&nbsp;<?=$q21_nome?></td>
	  <td>&nbsp;<?=$q21_cnpj?></td>
	  <td>&nbsp;<?=$q21_servico?></td>
	  <td>&nbsp;<?=db_formatar($q21_valor,"f")?></td>
	  <td>&nbsp;<?=db_formatar($q21_valorser,"f")?></td>
	  <td>&nbsp;<?=$q21_aliq?></td>
	  <td>&nbsp;<?=$q21_nota?></td>
	  <td>&nbsp;<?=$q21_serie?></td>
	  <td>&nbsp;<?=$z01_numcgm?></td>
	  <td>&nbsp;<?=$z01_nome?></td>
	</tr>  
<?
	}
        if($plans!=""){
	  if($cont==1){
            echo "<br><b>A planilha $plans não foi encontrada na tabela issplan.</b>";
	  }else{
            echo "<br><b>As planilhas $plans não foram encontradas na tabela issplan.</b>";
	  }  
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