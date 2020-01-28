<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

$oGet    = db_utils::postmemory($_GET);
$sFiltro = $oGet->sFiltro; 
$sValor  = $oGet->sValor;

$sParcelamentoCampos  = "distinct (v07_parcel),v07_totpar,z01_nome,z01_numcgm,k00_matric,k00_inscr, v07_dtlanc, v07_dtvenc,  
                         case when (select 1 
                                      from arrecad 
                                     where termo.v07_numpre = k00_numpre 
                                     limit 1) = 1  then 'Ativo'
                         else 
	                         case when (select 1 
	                                      from termoreparc 
	                                     where v08_parcelorigem = v07_parcel 
	                                     limit 1) = 1 then 'Reparcelado' 
                           else 
                             case when (select 1 
                                           from termoanu 
                                          where termoanu.v09_parcel = v07_parcel 
                                          limit 1) = 1 then 'Anulado'
                             else 
                               case when (select 1 
                                            from certter 
                                           inner join inicialcert on v51_certidao = v14_certid
                                           where v14_parcel = v07_parcel 
                                           limit 1) = 1 then 'Ajuizado' 
                               else 
                                 case when (select 1 
                                              from arrepaga 
                                             where k00_numpre = v07_numpre
                                               and not exists (select 1 
                                                                 from arrecad 
                                                                where arrecad.k00_numpre = arrepaga.k00_numpre 
                                                                limit 1)
                                               and not exists (select 1 
                                                                 from termoreparc 
                                                                where termoreparc.v08_parcelorigem = v07_parcel 
                                                                limit 1)                  
                                             limit 1) = 1 then 'Quitado'
                                 end 
                               end 
                             end
                           end 
                         end as v07_situacao";
$sSqlParcelamentos   = "	select {$sParcelamentoCampos} from termo                                  ";
$sSqlParcelamentos  .= "	        inner join cgm        on cgm.z01_numcgm        = termo.v07_numcgm ";
$sSqlParcelamentos  .= "	        inner join db_config  on db_config.codigo      = termo.v07_instit ";
$sSqlParcelamentos  .= "	        inner join arrenumcgm on arrenumcgm.k00_numpre = termo.v07_numpre ";
$sSqlParcelamentos  .= "          left  join arrematric on arrematric.k00_numpre = termo.v07_numpre ";
$sSqlParcelamentos  .= "          left  join arreinscr  on arreinscr.k00_numpre  = termo.v07_numpre ";
$sSqlParcelamentos  .= "	   where {$sFiltro} = {$sValor} ";
//echo $sSqlParcelamentos ; die();
$js_funcao="parent.js_mostraParcelamento|v07_parcel";

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">


</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >

<table style="margin-top: 20px;" border="0" align="center" width="80%">

<tr>
  <td>
    <? db_lovrot($sSqlParcelamentos,20,"()","","$js_funcao"); ?>
  </td>
</tr>

</table>

</body>


</html>