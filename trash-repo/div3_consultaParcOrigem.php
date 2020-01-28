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

$oGet    = db_utils::postmemory($_GET);

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

        $sqlTermoOrigem  = "   select 'divida' as DB_tipo_origem, parcel,'Divida' as tipo, coddiv as dl_codigo,valor,vlrcor,juros,multa,vlrdescjur,vlrdescmul,desconto,total  ";
        $sqlTermoOrigem .= "     from termodiv ";
        $sqlTermoOrigem .= "    where parcel = {$oGet->parcelamento} ";
        $sqlTermoOrigem .= " union ";
        $sqlTermoOrigem .= "   select 'inicial' as DB_tipo_origem, parcel,'Inicial do foro' as tipo, inicial as dl_codigo,valor,vlrcor,juros,multa,vlrdescjur,vlrdescmul,desconto,total  ";
        $sqlTermoOrigem .= "     from termoini ";
        $sqlTermoOrigem .= "    where parcel =  {$oGet->parcelamento} ";
        $sqlTermoOrigem .= " union ";
        $sqlTermoOrigem .= "   select 'contrib' as DB_tipo_origem, parcel,'Contribuição de Melhorias' as tipo, contricalc as dl_codigo,valor,vlrcor,juros,multa,vlrdescjur,vlrdescmul,desconto,total  ";
        $sqlTermoOrigem .= "     from termocontrib ";
        $sqlTermoOrigem .= "    where parcel =  {$oGet->parcelamento} ";
        $sqlTermoOrigem .= " union ";
        $sqlTermoOrigem .= "   select 'diversos' as DB_tipo_origem, dv10_parcel,'Diversos' as tipo, dv10_coddiver as dl_codigo,dv10_valor,dv10_vlrcor,dv10_juros,dv10_multa,dv10_vlrdescjur,dv10_vlrdescmul,dv10_desconto,dv10_total  ";
        $sqlTermoOrigem .= "     from termodiver ";
        $sqlTermoOrigem .= "    where dv10_parcel =  {$oGet->parcelamento} ";
        $sqlTermoOrigem .= " union ";
        $sqlTermoOrigem .= "   select 'reparcelamento' as DB_tipo_origem, v08_parcel,'Reparcelamento' as tipo, v08_parcelorigem as dl_codigo,v07_valor,v07_valor,0,0,0,0,0,v07_valor ";
//      $sqlTermoOrigem .= "   select 'reparcelamento' as DB_tipo_origem, v08_parcel,'Reparcelamento' as tipo, v08_parcelorigem as dl_codigo,v07_valor,vlrcor,juros,multa,vlrdescjur,vlrdescmul,desconto,(v07_valor+coalesce(juros,0)+coalesce(multa,0) ) as v07_valor  ";
        $sqlTermoOrigem .= "     from termoreparc ";
        $sqlTermoOrigem .= "          inner join termo on termo.v07_parcel = termoreparc.v08_parcelorigem ";
/*      $sqlTermoOrigem .= "          inner join ( select parcel, ";
        $sqlTermoOrigem .= "                              sum(valor)      as valor, ";
        $sqlTermoOrigem .= "                              sum(vlrcor)     as vlrcor, ";
        $sqlTermoOrigem .= "                              sum(juros)      as juros, ";
        $sqlTermoOrigem .= "                              sum(multa)      as multa, ";
        $sqlTermoOrigem .= "                              sum(vlrdescjur) as vlrdescjur, ";
        $sqlTermoOrigem .= "                              sum(vlrdescmul) as vlrdescmul, ";
        $sqlTermoOrigem .= "                              sum(desconto)   as desconto, ";
        $sqlTermoOrigem .= "                              sum(total)      as total ";
        $sqlTermoOrigem .= "                         from ( select  parcel,valor,vlrcor,juros,multa,vlrdescjur,vlrdescmul,desconto,total ";
        $sqlTermoOrigem .= "                                  from termodiv ";
        $sqlTermoOrigem .= "                                 where parcel = termo.v07_parcel ";
        $sqlTermoOrigem .= "                              union ";
        $sqlTermoOrigem .= "                                select parcel,valor,vlrcor,juros,multa,vlrdescjur,vlrdescmul,desconto,total  ";
        $sqlTermoOrigem .= "                                  from termoini ";
        $sqlTermoOrigem .= "                                 where parcel = termo.v07_parcel ";
        $sqlTermoOrigem .= "                              union ";
        $sqlTermoOrigem .= "                                select parcel,valor,vlrcor,juros,multa,vlrdescjur,vlrdescmul,desconto,total  ";
        $sqlTermoOrigem .= "                                  from termocontrib ";
        $sqlTermoOrigem .= "                                 where parcel = termo.v07_parcel ";
        $sqlTermoOrigem .= "                              union ";
        $sqlTermoOrigem .= "                                select dv10_parcel,dv10_valor,dv10_vlrcor,dv10_juros,dv10_multa,dv10_vlrdescjur,dv10_vlrdescmul,dv10_desconto,dv10_total  ";
        $sqlTermoOrigem .= "                                  from termodiver ";
        $sqlTermoOrigem .= "                                 where dv10_parcel = termo.v07_parcel ";
        $sqlTermoOrigem .= "      ) as x group by parcel ) as reparcelamento on termo.v07_parcel = reparcelamento.parcel ";*/

        $sqlTermoOrigem .= "    where v08_parcel =  {$oGet->parcelamento} ";

        $arrayTot["valor"]      = "valor";
        $arrayTot["vlrcor"]     = "vlrcor";
        $arrayTot["juros"]      = "juros";
        $arrayTot["multa"]      = "multa";
        $arrayTot["vlrdescjur"] = "vlrdescjur";
        $arrayTot["vlrdescmul"] = "vlrdescmul";
        $arrayTot["desconto"]   = "desconto";
        $arrayTot["total"]      = "total";
        $arrayTot["totalgeral"] = "dl_codigo";
        
        $funcao_js = "js_consultaDetalhes{$oGet->parcelamento}|DB_tipo_origem|dl_codigo";

        db_lovrot($sqlTermoOrigem,50,"()","","$funcao_js","","NoMe", array(),false, $arrayTot);

      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>
function js_mudaFiltro(valor){

  var url  = 'div3_consultaParcOrigem.php';
  var pars = 'parcelamento=<?=$oGet->parcelamento?>&tipoFiltro='+valor;
  document.location.href = url+'?'+pars;

}

function js_consultaDetalhes<?=$oGet->parcelamento?>(tipoOrigem,codigoOrigem){

  var arquivo    = '';
  var parametros = '';
  var nomeIframe = '';
  
  if (tipoOrigem == 'divida') {
    arquivo    = 'div1_consulta003.php';
    parametros = 'codDiv='+codigoOrigem;    
    nomeIframe = 'db_iframe_consultadivida';
  }else if (tipoOrigem == 'inicial') {
    arquivo    = 'func_inicialmovcert.php';
    parametros = 'v50_inicial='+codigoOrigem;    
    nomeIframe = 'db_iframe';
  }else if (tipoOrigem == 'contrib') {
    arquivo    = '';
    parametros = '';    
    nomeIframe = 'db_iframe_consultacontrib';
  }else if (tipoOrigem == 'diversos') {
    arquivo    = 'dvr3_consdiversos004.php';
    parametros = 'dv05_coddiver='+codigoOrigem;  
    nomeIframe = 'db_iframe_consultadiversos';
  }else if (tipoOrigem == 'reparcelamento') {
    arquivo    = 'div3_consultaParcelamento.php';
    parametros = 'parcelamento='+codigoOrigem;    
    nomeIframe = 'db_iframe_consultaparc'+codigoOrigem;
  }  

  if (arquivo != "" && parametros != '') {
    js_OpenJanelaIframe('top.corpo',nomeIframe,arquivo+'?'+parametros,'Detalhes da Pesquisa',true);
  }

}

</script>