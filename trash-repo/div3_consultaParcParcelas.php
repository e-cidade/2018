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
include("classes/db_termo_classe.php");
require("libs/db_utils.php");

$cltermo   = new cl_termo();

$oGet    = db_utils::postmemory($_GET);

$rsTermo   = $cltermo->sql_record($cltermo->sql_query_file(null,"termo.v07_numpre",null," v07_parcel = {$oGet->parcelamento}"));
if ( $cltermo->numrows > 0 ) {
  $oTermo  = db_utils::fieldsMemory($rsTermo,0);
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
    
        $camposDetalhe      = " 'a' as DB_parametro,";
        $camposDetalheGroup = " DB_parametro,";

        $funcao_js          = "js_mudaFiltro|DB_parametro";

        if (isset($oGet->tipoFiltro) && $oGet->tipoFiltro == 'a') {
          $camposDetalheGroup = " DB_parametro,a.k00_receit,a.k00_descr,a.k02_descr,";
          $camposDetalhe      = "'s' as DB_parametro, a.k00_receit,a.k00_descr,a.k02_descr, ";
        }

        $sqlTermoParcelas  = " select  a.k00_numpre, a.k00_numpar, $camposDetalhe a.status, sum(a.k00_valor) as k00_valor ";
        $sqlTermoParcelas .= "   from ( ";
        $sqlTermoParcelas .= " select  arrecad.k00_numpre,  arrecad.k00_numpar, arrecad.k00_receit, k00_descr, k02_descr, 'Aberto' as status ,arrecad.k00_valor";
        $sqlTermoParcelas .= "    from arrecad   ";
        $sqlTermoParcelas .= "         inner join arretipo on arretipo.k00_tipo   = arrecad.k00_tipo ";
        $sqlTermoParcelas .= "         inner join tabrec   on tabrec.k02_codigo   = arrecad.k00_receit    ";
        $sqlTermoParcelas .= "         left  join arrepaga on arrepaga.k00_numpre = arrecad.k00_numpre ";
        $sqlTermoParcelas .= "                            and arrepaga.k00_numpar = arrecad.k00_numpar ";
        $sqlTermoParcelas .= "                            and arrepaga.k00_receit = arrecad.k00_receit ";
        $sqlTermoParcelas .= "         left join arrecant  on arrecant.k00_numpre = arrecad.k00_numpre ";
        $sqlTermoParcelas .= "                            and arrecant.k00_numpar = arrecad.k00_numpar ";
        $sqlTermoParcelas .= "                            and arrecant.k00_receit = arrecad.k00_receit ";
        $sqlTermoParcelas .= "   where arrepaga.k00_numpre is null and arrecant.k00_numpre is null and arrecad.k00_numpre = {$oTermo->v07_numpre} ";
        $sqlTermoParcelas .= "union  ";
        $sqlTermoParcelas .= "  select arreold.k00_numpre, arreold.k00_numpar, arreold.k00_receit, k00_descr, k02_descr, 'Anulado' as status, arreold.k00_valor ";
        $sqlTermoParcelas .= "    from arreold   ";
        $sqlTermoParcelas .= "         inner join arretipo on arretipo.k00_tipo   = arreold.k00_tipo ";
        $sqlTermoParcelas .= "         inner join tabrec   on tabrec.k02_codigo   = arreold.k00_receit    ";
        $sqlTermoParcelas .= "         left  join arrepaga on arrepaga.k00_numpre = arreold.k00_numpre ";
        $sqlTermoParcelas .= "                            and arrepaga.k00_numpar = arreold.k00_numpar ";
        $sqlTermoParcelas .= "                            and arrepaga.k00_receit = arreold.k00_receit ";
        $sqlTermoParcelas .= "         left join arrecant  on arrecant.k00_numpre = arreold.k00_numpre ";
        $sqlTermoParcelas .= "                            and arrecant.k00_numpar = arreold.k00_numpar ";
        $sqlTermoParcelas .= "                            and arrecant.k00_receit = arreold.k00_receit ";
        $sqlTermoParcelas .= "   where arrepaga.k00_numpre is null and arrecant.k00_numpre is null and arreold.k00_numpre = {$oTermo->v07_numpre}";
        $sqlTermoParcelas .= "union  ";
        $sqlTermoParcelas .= "  select arrecant.k00_numpre, arrecant.k00_numpar, arrecant.k00_receit, k00_descr, k02_descr, ";
        $sqlTermoParcelas .= "         case ";
        $sqlTermoParcelas .= "           when arrepaga.k00_numpre is not null ";
        $sqlTermoParcelas .= "             then 'Pago'  ";
        $sqlTermoParcelas .= "           else           ";
        $sqlTermoParcelas .= "             'Cancelado'  ";
        $sqlTermoParcelas .= "         end as status, arrecant.k00_valor ";
        $sqlTermoParcelas .= "    from arrecant         ";
        $sqlTermoParcelas .= "         inner join arretipo on arretipo.k00_tipo   = arrecant.k00_tipo   ";
        $sqlTermoParcelas .= "         inner join tabrec   on tabrec.k02_codigo   = arrecant.k00_receit ";
        $sqlTermoParcelas .= "         left  join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre ";
        $sqlTermoParcelas .= "                            and arrepaga.k00_numpar = arrecant.k00_numpar ";
        $sqlTermoParcelas .= "                            and arrepaga.k00_receit = arrecant.k00_receit ";
        $sqlTermoParcelas .= "         left join arreold   on arreold.k00_numpre  = arrecant.k00_numpre ";
        $sqlTermoParcelas .= "                            and arreold.k00_numpar  = arrecant.k00_numpar ";
        $sqlTermoParcelas .= "                            and arreold.k00_receit  = arrecant.k00_receit ";
        $sqlTermoParcelas .= "   where arreold.k00_numpre is null and arrecant.k00_numpre = {$oTermo->v07_numpre} ";
        $sqlTermoParcelas .= " ) as a ";
        $sqlTermoParcelas .= " group by a.k00_numpre, a.k00_numpar,$camposDetalheGroup a.status ";
        $sqlTermoParcelas .= " order by a.k00_numpre, a.k00_numpar,$camposDetalheGroup a.status ";

        echo "<form name='form1'>";
        echo "<b>Agrupar por : </b>";
        $array = array("s"=>"Parcela","a"=>"Receita");
        db_select('tipoFiltro',$array,true,"1","onChange='js_mudaFiltro(this.value);'");
        echo "</form>";

        $arrayTot["k00_valor"]  = "k00_valor";
        $arrayTot["totalgeral"] = "status";

        db_lovrot($sqlTermoParcelas,50,"()","","$funcao_js","","NoMe", array(),false, $arrayTot);

      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>

  function js_mudaFiltro(valor){
    var url  = 'div3_consultaParcParcelas.php';
    var pars = 'parcelamento=<?=$oGet->parcelamento?>&tipoFiltro='+valor;
    document.location.href = url+'?'+pars;
  }

</script>