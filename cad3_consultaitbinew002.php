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
include("classes/db_itbi_classe.php");

//die("chegou aki");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//echo $HTTP_SERVER_VARS["QUERY_STRING"];exit;
$clitbi = new cl_itbi;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
/*
 matric=1234
 &setor=
 &codrua=
 &quadra=
 &adquirente=
 &transmitente=
 &dtini=
 &dtfim= 
 */
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<?
  $where = "";
  
  if(isset($codguia) && $codguia != ""){
     $where .= " and it01_guia = $codguia ";
  }
  
  if(isset($matric) && $matric != ""){
     $where .= " and it06_matric = $matric";
  }
  if(isset($adquirente) && $adquirente != ""){
     $where .= " and it03_nome ilike '".$adquirente."%' and upper(it03_tipo) = 'C' ";
  }
  if(isset($transmitente) && $transmitente != ""){
     $where .= " and it03_nome ilike '".$transmitente."%' and upper(it03_tipo) = 'T' ";
  }
  if(isset($setor) && $setor != ""){
     $where .= " and j34_setor = '" . str_pad($setor,4,"0",STR_PAD_LEFT)."'";
//   $where .= " and trim(j34_setor) = trim('".$setor."')";
  }
  if(isset($quadra) && $quadra != ""){
     $where .= " and j34_quadra = '" . str_pad($quadra,4,"0",STR_PAD_LEFT)."'";
     //$where .= " and trim(j34_quadra) = trim('".$quadra."')";
  }
  if(isset($lote) && $lote != ""){
     $where .= " and j34_lote = '" . str_pad($lote,4,"0",STR_PAD_LEFT) ."'";
     //$where .= " and trim(j34_quadra) = trim('".$quadra."')";
  }
  
	if(isset($setorloc) || isset($quadraloc) || isset($loteloc)) {
				
		if(isset($setorloc) and $setorloc != '') {
			$where .= " and j05_codigoproprio = '{$setorloc}' ";
		}
		if(isset($quadraloc) and $quadraloc != '') {
			$where .= " and j06_quadraloc = '{$quadraloc}' ";
		}
		if(isset($loteloc) and $loteloc != '') {
			$where .= " and j06_lote = '{$loteloc}' ";
		}
					
	}
  
  if(isset($dtini) && $dtini != ""){
     $where .= " and it01_data >= '".$dtini."'";
  }
  if(isset($dtfim) && $dtfim != ""){
     $where .= " and it01_data <= '".$dtfim."'";
  }
  if(isset($tipo) && $tipo == 'u'){
      $where .= " and itbimatric.it06_guia is not null ";   
  }else{
      $where .= " and itbimatric.it06_guia is null ";
  }
  if(isset($codrua) && $codrua != ""){
     $where .= " and j14_codigo = $codrua ";
  }
  
  $sql  = " select distinct on (it01_guia) 																			";
  $sql .= "       it01_guia, 																										";
  $sql .= "       case when it06_matric = 0 then 'ITBI rural ou sem matrícula' else it06_matric::text end as it06_matric, ";
  $sql .= "       it03_nome, 																										";
  $sql .= "       it01_data, 																										";
  $sql .= "       it04_descr, 																									";
  $sql .= '       it14_valoraval, 																							';
  $sql .= '       case 
                    when exists ( select 1  
				                            from itbicancela 
				                           where it16_guia = it01_guia limit 1 ) then \'Cancelada\' 
				            else \'Ativa\' 
				          end																														';
  $sql .= " from ( 																															";
  $sql .= "    select it01_guia,                                                ";
  $sql .= "           it06_matric,                                              ";
  $sql .= "           it03_princ,                                               ";
  $sql .= "           it03_nome,                                                ";
  $sql .= "           it01_data,                                                ";
  $sql .= "           it04_descr,                                               ";
  $sql .= "           it14_valoraval                                            ";
  $sql .= "      from itbi  																										";
  $sql .= "        inner join itbinome      on it03_guia   = it01_guia          ";
  $sql .= "        inner join itbitransacao on it04_codigo = it01_tipotransacao ";
  $sql .= "        left  join itbimatric    on it01_guia   = it06_guia 	        ";
  $sql .= "        left  join itbiavalia    on it14_guia   = it01_guia 	        ";
  $sql .= "        inner join iptubase      on it06_matric = j01_matric	        ";
  $sql .= "        inner join lote          on j34_idbql   = j01_idbql 	        ";
	$sql .= "        left  join loteloc       on j06_idbql   = j01_idbql 					";
  $sql .= "        left  join setorloc      on j05_codigo  = j06_setorloc				";
  $sql .= "        inner join testpri       on j34_idbql   = j49_idbql 	        ";
  $sql .= "        inner join ruas          on j14_codigo  = j49_codigo         ";
  $sql .= "    where 1=1 $where 																								";
  $sql .= " union all 																													";
  $sql .= "    select it01_guia, 		                                            ";
  $sql .= "           0, 						                                            ";
  $sql .= "           it03_princ, 	                                            ";
  $sql .= "           it03_nome, 		                                            ";
  $sql .= "           it01_data, 		                                            ";
  $sql .= "           it04_descr, 	                                            ";
  $sql .= "           it14_valoraval																						";
  $sql .= "      from itbi 																											";
  $sql .= "        inner join itbinome      on it03_guia   = it01_guia 					";
  $sql .= "        inner join itbitransacao on it04_codigo = it01_tipotransacao ";
  $sql .= "        left  join itbimatric    on it01_guia   = it06_guia 					";
  $sql .= "        left  join itbiavalia    on it14_guia   = it01_guia 					";
  $sql .= "        left  join iptubase      on it06_matric = j01_matric 				";
  $sql .= "        left  join lote          on j34_idbql   = j01_idbql 					";
  $sql .= "        left  join loteloc       on j06_idbql   = j01_idbql 					";
  $sql .= "        left  join setorloc      on j05_codigo  = j06_setorloc				";
  $sql .= "        left  join testpri       on j34_idbql   = j49_idbql 					";
  $sql .= "        left  join ruas          on j14_codigo  = j49_codigo 				";
  $sql .= "    where it06_guia is null $where ";
  $sql .= ") as x ";
  $sql .= ""; 

  $result = $clitbi->sql_record($sql);
  if($clitbi->numrows == 1){
    db_fieldsmemory($result,0);
    $funcao = split("\\|",$funcao_js);
    echo "<script>".$funcao[0]."('$it01_guia');</script>";
    echo "<script>parent.db_iframe_consultaitbi.hide();</script>";
    exit;
  }

?>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align="center" valign="top">
      <? 
        db_lovrot($sql,30,"()","",$funcao_js);
      ?>
     </td>
   </tr>
  <tr> 
    <td align="center" valign="top">
      <input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultaitbi.hide();" > 
    </td>
  </tr>
</table>
</body>
</html>