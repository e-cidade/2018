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

$cltermo = new cl_termo();

$oGet    = db_utils::postmemory($_GET);

$rsTermo   = $cltermo->sql_record($cltermo->sql_query_file(null,"v07_numpre",null," v07_parcel = {$oGet->parcelamento}"));
if ( $cltermo->numrows > 0 ) {
  $oTermo  = db_utils::fieldsMemory($rsTermo,0);
}else{
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
        $camposDetalhe = "";
        $funcao_js     = "js_mudaFiltro|DB_parametro";

        if (isset($oGet->tipoFiltro) && $oGet->tipoFiltro == 'a') {
          $camposDetalhe = "v01_coddiv,v01_numpre,v01_numpar, ";
          $funcao_js     = "js_consultaDivida|v01_coddiv";
        }

        $sqlDividas  = "       select  ";
        $sqlDividas .= "              $camposDetalhe                                                               ";
        $sqlDividas .= "              'a' as db_parametro,                                                         ";
        $sqlDividas .= "              v01_exerc,                                                                   ";
        $sqlDividas .= "              v03_descr,                                                                   ";
        $sqlDividas .= "              sum(v01_vlrhis) as v01_vlrhis,                                               ";
        $sqlDividas .= "              sum(v01_valor) as v01_valor                                                  ";
        $sqlDividas .= "         from ( select $camposDetalhe                                                      ";
        $sqlDividas .= "                       'a' as db_parametro,                                                ";
        $sqlDividas .= "                        v01_exerc,                                                         ";
        $sqlDividas .= "                        v03_descr,                                                         ";
        $sqlDividas .= "                        v01_vlrhis,                                                        ";
        $sqlDividas .= "                        v01_valor ,                                                        ";
        $sqlDividas .= "                        rdtlanc                                                            ";
        $sqlDividas .= "                   from fc_origemparcelamento({$oTermo->v07_numpre}) as origemparcelamento ";
        $sqlDividas .= "                        inner join termo    on termo.v07_parcel  = riparcel                ";
        $sqlDividas .= "                        inner join termodiv on termodiv.parcel   = riparcel                ";
        $sqlDividas .= "                        inner join divida   on divida.v01_coddiv = termodiv.coddiv         ";
        $sqlDividas .= "                                           and v01_instit        = ".db_getsession('DB_instit');
        $sqlDividas .= "                        inner join proced   on proced.v03_codigo = divida.v01_proced       ";
        $sqlDividas .= "             union                                                                         ";
        $sqlDividas .= "                select $camposDetalhe                                                      ";
        $sqlDividas .= "                       'a' as db_parametro,                                                ";
        $sqlDividas .= "                        v01_exerc,                                                         ";
        $sqlDividas .= "                        v03_descr,                                                         ";
        $sqlDividas .= "                        v01_vlrhis,                                                        ";
        $sqlDividas .= "                        v01_valor ,                                                        ";
        $sqlDividas .= "                        rdtlanc                                                            ";
        $sqlDividas .= "                   from fc_origemparcelamento({$oTermo->v07_numpre}) as origemparcelamento ";
        $sqlDividas .= "                        inner join termo           on termo.v07_parcel  = riparcel         ";
        $sqlDividas .= "                        inner join termoini        on termoini.parcel   = riparcel         ";
        $sqlDividas .= "                        inner join inicialcert     on inicial           = v51_inicial      ";
        $sqlDividas .= "                        inner join certdiv         on v14_certid        = v51_certidao     ";
        $sqlDividas .= "                        inner join divida          on v01_coddiv        = v14_coddiv       ";
        $sqlDividas .= "                                                  and v01_instit        = ".db_getsession('DB_instit');
        $sqlDividas .= "                        inner join proced          on proced.v03_codigo = divida.v01_proced  ) as x ";      
        $sqlDividas .= "        group by $camposDetalhe                                                            ";
        $sqlDividas .= "                 v01_exerc,                                                                ";
        $sqlDividas .= "                 v03_descr,                                                                ";
        $sqlDividas .= "                 rdtlanc                                                                   ";
        $sqlDividas .= "        order by $camposDetalhe                                                            ";
        $sqlDividas .= "                 v01_exerc,                                                                ";
        $sqlDividas .= "                 v03_descr,                                                                ";
        $sqlDividas .= "                 rdtlanc                                                                   ";

        $arrayTot["v01_vlrhis"] = "v01_vlrhis";
        $arrayTot["v01_valor"]  = "v01_valor";
        $arrayTot["totalgeral"] = "v03_descr";
        
        $array = array("s"=>"Exerc&iacute;cio","a"=>"Numpre e parcela");

        echo "<form name='form1'>";
        echo "<b>Agrupar por : </b>";
        db_select('tipoFiltro',$array,true,"1","onChange='js_mudaFiltro(this.value);'");
        echo "</form>";

        db_lovrot($sqlDividas,15,"()","","$funcao_js","","NoMe", array("parcelamento"=>"{$oGet->parcelamento}"),false, $arrayTot);

      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>

function js_mudaFiltro(valor){

  var url  = 'div3_consultaParcExercicios.php';
  var pars = 'parcelamento=<?=$oGet->parcelamento?>&tipoFiltro='+valor;
  document.location.href = url+'?'+pars;

}

function js_consultaDivida(codigoOrigem){
  /* alert(' inicio -- '+codigoOrigem); return false; */
  var  arquivo    = 'div1_consulta003.php';
  var  parametros = 'codDiv='+codigoOrigem;    
  var  nomeIframe = 'db_iframe_consultadivida';

  js_OpenJanelaIframe('top.corpo',nomeIframe,arquivo+'?'+parametros,'Detalhes da Pesquisa',true);

}




</script>