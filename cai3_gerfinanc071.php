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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
require("dbforms/db_funcoes.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("classes/db_iptubase_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_propri_classe.php");
include("classes/db_promitente_classe.php");

include("classes/db_termoanu_classe.php");

$cltermoanu = new cl_termoanu;

$objGet  = db_utils::postmemory($_GET);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>

function js_mostraParcelamento(parcelamento){
  js_OpenJanelaIframe('top.corpo','db_iframe_consultaparc'+parcelamento,'div3_consultaParcelamento.php?parcelamento='+parcelamento,'Consulta Pacelamentos anulados',true);
}

</script>
<style type="text/css">
<!--
.borda {
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
-->
</style>
<script>
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {
    if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
      document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; 
    }
  } else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

</script>
</head>
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<form name="form1" method="post">
<tr>&nbsp;&nbsp;</tr>
<table border="1" cellpadding="0" cellspacing="0">
<?
    if ($opcao=="cgm"){
      $where = "and arrenumcgm.k00_numcgm = $codopcao";
    }else if ($opcao=="matric"){
      $where = "and arrematric.k00_matric = $codopcao";
    }else if ($opcao=="inscr"){
      $where = "and arreinscr.k00_inscr = $codopcao";
    }else if ($opcao=="numpre"){
      $where = "and termo.v07_numpre = $codopcao";
    }else if ($opcao=="parcel"){
      $where = "and termoanu.v09_parcel = $codopcao";
    }
    
    $campos     = " distinct v07_parcel, ";
    $campos    .= "          v07_numpre, ";
    $campos    .= "          v07_dtlanc, ";
    $campos    .= "          case ";
    $campos    .= "            when termoini.parcel        is not null then 'Parcelamento de inicial' ";
    $campos    .= "            when termodiv.parcel        is not null then 'Parcelamento de divida' ";
    $campos    .= "            when termodiver.dv10_parcel is not null then 'Parcelamento de diversos' ";
    $campos    .= "            when termocontrib.parcel    is not null then 'Parcelamento de contribuicao de melhoria' ";
    $campos    .= "            when termoreparc.v08_parcel is not null then 'Reparcelamento' ";
    $campos    .= "          end as tipoparcel, ";
    $campos    .= "          v07_valor, ";
    $campos    .= "          nome, ";
    $campos    .= "          v09_data, ";
    $campos    .= "          v09_hora, ";
    $campos    .= "          v09_motivo ";
    
    //                               echo($cltermoanu->sqlQueryTermoOrigem (null,$campos,null,"termo.v07_instit = ".db_getsession('DB_instit')." ".$where));
    $rsTermoAnu = $cltermoanu->sql_record($cltermoanu->sqlQueryTermoOrigem (null,$campos,null,"termo.v07_instit = ".db_getsession('DB_instit')." ".$where));

    if ($cltermoanu->numrows == 0) {
      db_msgbox("Não existem parcelamentos para a origem selecionada.");
      exit;      
    } 

    //
    // Cabecalho
    //
    echo "    <tr bgcolor='#FFCC66'> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Detalhes             </th> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Parcelamento         </th> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Numpre               </th> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Data Parcelamento    </th> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Tipo                 </th> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Valor Total          </th> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Anulado por          </th> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Data anulação        </th> ";
    echo "      <th class='borda' style='font-size:12px' nowrap> Hora anulação        </th> ";
    echo "      <th class='borda' style='font-size:12px' wrap> Motivo               </th> ";
    echo "    </tr> ";

    $cor="#EFE029";

    for($i=0; $i < $cltermoanu->numrows; $i++){

      $objParcelamentos = db_utils::fieldsMemory($rsTermoAnu,$i);

      if($cor=="#EFE029"){
         $cor="#E4F471";
      }else if($cor=="#E4F471"){
	      $cor="#EFE029";
      }

      $funcaojs = " js_mostraParcelamento(".$objParcelamentos->v07_parcel.");return false; ";

      echo "  <tr title='Clique aqui para verificar os dados' style='cursor: hand' onclick='".$funcaojs."'>  ";
      echo "    <td align='center' style='font-size:12px' nowrap bgcolor='$cor'>  <a href=''> MI </a>                               </td> ";
      echo "    <td align='center' style='font-size:12px' nowrap bgcolor='$cor'> ".$objParcelamentos->v07_parcel."                  </td> ";
      echo "    <td align='center' style='font-size:12px' nowrap bgcolor='$cor'> ".$objParcelamentos->v07_numpre."                  </td> ";
      echo "    <td align='center' style='font-size:12px' nowrap bgcolor='$cor'> ".db_formatar($objParcelamentos->v07_dtlanc,'d')." </td> ";
      echo "    <td align='left'   style='font-size:12px' nowrap bgcolor='$cor'> ".$objParcelamentos->tipoparcel."                  </td> ";
      echo "    <td align='right'  style='font-size:12px' nowrap bgcolor='$cor'> ".db_formatar($objParcelamentos->v07_valor,'f')."  </td> ";
      echo "    <td align='left'   style='font-size:12px' nowrap bgcolor='$cor'> ".$objParcelamentos->nome."                        </td> ";
      echo "    <td align='center' style='font-size:12px' nowrap bgcolor='$cor'> ".db_formatar($objParcelamentos->v09_data,'d')."   </td> ";
      echo "    <td align='center' style='font-size:12px' nowrap bgcolor='$cor'> ".$objParcelamentos->v09_hora."                    </td> ";
      echo "    <td align='left'   style='font-size:12px' nowrap bgcolor='$cor'> ".$objParcelamentos->v09_motivo."     </td> ";
      echo "  </tr> ";  

   }

?>

</table>

</form>
</center>
</body>
</html>