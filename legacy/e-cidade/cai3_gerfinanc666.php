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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

?>
<html>
<head>
<title>Descritivo do Parcelamento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 11px;
  	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
th {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 11px;
  	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
-->
</style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
 
//		$sql = "select distinct q21_nota,q21_serie,q21_valorser,q21_valorimposto,q21_retido,q21_cnpj,q21_nome,
//							case when q21_status = 1 then 'Ativo' else 
//							    case when q21_status = 2 then 'Alterado' else 
//							        'Excluido' 
//							    end 
//							end as dl_status
//							from issplan 
//							inner join issplannumpre on q32_numpre = q20_numpre
//							inner join issplannumpreissplanit on q77_issplannumpre =q32_sequencial
//							inner join issplanit on q77_issplanit = q21_sequencial
// 						 where q20_numpre = $numpre";
 						 
 		$sql = " select case when q21_tipolanc = 1 then 'Tomado' 
                                  else 
                             case when q21_tipolanc = 2 then 'Prestado'
                                  end end as dl_tipo_lançamento,
                             q21_nota,
                             q21_serie,
                             q21_datanota,
                             q21_valorser,
                             q21_valorimposto,
                             q21_retido,
                             q21_cnpj,
                             q21_nome, 
                             case when q21_status = 1 then 'Ativo' 
                                  else 
                             case when q21_status = 2 then 'Alterado' 
                                  else 'Excluido' 
                                  end end as dl_status 
                        from issplan 
                             inner join issplannumpre on q32_numpre = q20_numpre 
                             inner join issplannumpreissplanit on q77_issplannumpre =q32_sequencial 
                             inner join issplanit on q77_issplanit = q21_sequencial 
                       where q20_numpre = {$numpre} 
                    order by q21_sequencial ";
	
    $js_func = "";
    //die($sql);
    db_lovrot($sql,5,"()","",$js_func);
  ?>
</body>
</html>