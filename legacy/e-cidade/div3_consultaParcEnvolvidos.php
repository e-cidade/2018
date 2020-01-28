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
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");
include("classes/db_termo_classe.php");

$oGet    = db_utils::postmemory($_GET);
$cltermo = new cl_termo();

$rsTermo   = $cltermo->sql_record($cltermo->sql_query_file(null,"v07_numpre",null," v07_parcel = {$oGet->parcelamento}"));
if ($cltermo->numrows > 0 ) {
  $oTermo  = db_utils::fieldsMemory($rsTermo,0);
} else {
  db_msgbox("Parcelamento não encontrado");
  echo " <script> parent.db_iframe_consultaparc".$oGet->parcelamento.".hide(); </script>";
  exit;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align="center" valign="top"> 
    <?    

        $sqlTermoEnvolvidos  = "   select 'CGM'        as dl_tipo_da_origem, ";
        $sqlTermoEnvolvidos .= "           k00_numcgm  as dl_codigo_da_origem, ";
        $sqlTermoEnvolvidos .= "           z01_nome    as z01_nome";
        $sqlTermoEnvolvidos .= "      from arrenumcgm  ";
        $sqlTermoEnvolvidos .= "           inner join cgm on cgm.z01_numcgm = arrenumcgm.k00_numcgm ";
        $sqlTermoEnvolvidos .= "     where k00_numpre = {$oTermo->v07_numpre}";
        $sqlTermoEnvolvidos .= "  union  ";
        $sqlTermoEnvolvidos .= "    select 'INSCR' as tipo, ";
        $sqlTermoEnvolvidos .= "           k00_inscr, ";
        $sqlTermoEnvolvidos .= "           z01_nome  ";
        $sqlTermoEnvolvidos .= "      from arreinscr  ";
        $sqlTermoEnvolvidos .= "           inner join issbase on issbase.q02_inscr = arreinscr.k00_inscr ";
        $sqlTermoEnvolvidos .= "           inner join cgm     on cgm.z01_numcgm    = issbase.q02_numcgm ";
        $sqlTermoEnvolvidos .= "     where k00_numpre = {$oTermo->v07_numpre} ";
        $sqlTermoEnvolvidos .= "  union ";
        $sqlTermoEnvolvidos .= "    select 'MATRIC' as tipo, ";
        $sqlTermoEnvolvidos .= "            k00_matric,      ";
        $sqlTermoEnvolvidos .= "            z01_nome         ";
        $sqlTermoEnvolvidos .= "      from arrematric        ";
        $sqlTermoEnvolvidos .= "           inner join iptubase on iptubase.j01_matric = arrematric.k00_matric  ";
        $sqlTermoEnvolvidos .= "           inner join cgm      on cgm.z01_numcgm      = iptubase.j01_numcgm ";
        $sqlTermoEnvolvidos .= "     where k00_numpre = {$oTermo->v07_numpre} ";

        
        $funcao_js = "js_consultaDetalhes{$oGet->parcelamento}|dl_tipo_da_origem|dl_codigo_da_origem";
        db_lovrot($sqlTermoEnvolvidos,50,"()","","$funcao_js");

      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>

function js_consultaDetalhes<?=$oGet->parcelamento?>(tipoOrigem,codigoOrigem){

  var arquivo    = '';
  var parametros = '';
  var nomeIframe = '';

  //alert(tipoOrigem+' -- '+codigoOrigem) ;

  if (tipoOrigem == 'CGM') {
    arquivo    = 'prot3_conscgm002.php';
    parametros = 'numcgm='+codigoOrigem+'&fechar=db_iframe_consultacgm';    
    nomeIframe = 'db_iframe_consultacgm';
  }else if (tipoOrigem == 'MATRIC') {
    arquivo    = 'cad3_conscadastro_002.php';
    parametros = 'cod_matricula='+codigoOrigem;    
    nomeIframe = 'db_iframe_consultamatricula';
  }else if (tipoOrigem == 'INSCR') {
    arquivo    = 'iss3_consinscr003.php';
    parametros = 'numeroDaInscricao='+codigoOrigem;    
    nomeIframe = 'db_iframe_consultacontrib';
  } 

  if (arquivo != "" && parametros != '') {
    js_OpenJanelaIframe('top.corpo',nomeIframe,arquivo+'?'+parametros,'Detalhes da Pesquisa',true);
  }

}

</script>