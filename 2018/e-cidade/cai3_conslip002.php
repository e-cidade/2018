<?PHP
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_orctiporec_classe.php");
//$clslip = new cl_slip;
$get  = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('k17_codigo');
$clrotulo->label('k17_data');
$clrotulo->label('k17_debito');
$clrotulo->label('k17_credito');
$clrotulo->label('k17_valor');
$clrotulo->label('k17_hist');
$clrotulo->label('k17_texto');
$clrotulo->label('k17_dtaut');
$clrotulo->label('k17_autent');
$clrotulo->label('c60_descr');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_GET_VARS,2);exit;

$where  = "";
$where1 = "";
if (($get->data != "--") && ($get->data1 != "--")) {
    $where = " and k17_data  between '$get->data' and '$get->data1'  ";
     }else if ($get->data != "--" ){
       $where   = " and k17_data >= '$get->data'  ";
     }else if ($get->data1!="--"){
       $where = " and  k17_data <= '$get->data1'   ";
    }
if ($get->situacao == "A" ){
  $where1="  ";
  }else{
    $where1=" and k17_situacao = ".$get->situacao;
  }

if(trim($get->codigos) !="" ){
  $where .= " and k17_numcgm ";
  $where .= " in (".$get->codigos.") ";
}

$whereslip = "";

if(isset($get->slip1) && trim($get->slip1)!=""){
  $whereslip = " and slip.k17_codigo >= $get->slip1 ";
}

if(isset($get->slip2) && trim($get->slip2)!=""){
  if(trim($whereslip)!=""){
    $whereslip = " and slip.k17_codigo between ".$get->slip1 ." and ". $get->slip2;
  }else{
    $whereslip = " and slip.k17_codigo <= ".$get->slip2;
  }
}

if(isset($get->recurso) && $get->recurso!='0'){
    $whereslip .= " and ( r1.c61_codigo =".$get->recurso ." or r2.c61_codigo = ".$get->recurso.") ";
}
if(isset($get->hist) && $get->hist != ''){
    $whereslip .= " and slip.k17_hist = {$get->hist}";
}
if(isset($get->k145_numeroprocesso) && $get->k145_numeroprocesso != ''){
  $whereslip .= " and slipprocesso.k145_numeroprocesso ilike '%{$get->k145_numeroprocesso}%' ";
}
$where .= $whereslip;
$sql = "         select slip.k17_codigo,
                        k17_data,
                        r1.c61_reduz||'-'||c1.c60_descr as dl_debito_descr,
                        r2.c61_reduz||'-'||c2.c60_descr as dl_credito_descr,
                        (case when k17_situacao = 1 then 'Não Autenticado'
                              when k17_situacao = 2 then 'Autenticado'
                              when k17_situacao = 3 then 'Estornado'
                              when k17_situacao = 4 then 'Anulado'
                         end
                        ) as k17_situacao,
                        k17_valor,
                        k17_dtaut,
                        z01_nome,
                        k145_numeroprocesso
                   from slip
                   left join conplanoreduz r1 on r1.c61_reduz  = k17_debito
                                             and r1.c61_instit = k17_instit
                                             and r1.c61_anousu =".db_getsession("DB_anousu")."
                   left join conplano      c1 on c1.c60_codcon = r1.c61_codcon
                                             and c1.c60_anousu = r1.c61_anousu

                   left join conplanoreduz r2 on r2.c61_reduz = k17_credito
                                             and r2.c61_instit = k17_instit
                                             and r2.c61_anousu=".db_getsession("DB_anousu")."
                   left join conplano c2 on c2.c60_codcon = r2.c61_codcon      and c2.c60_anousu = r2.c61_anousu

                   left join slipnum on slipnum.k17_codigo = slip.k17_codigo
                   left join cgm on cgm.z01_numcgm = slipnum.k17_numcgm
                   left join slipprocesso on slip.k17_codigo = slipprocesso.k145_slip
                  where k17_instit = " . db_getsession('DB_instit') ."
                        $where $where1
                  order by slip.k17_codigo" ;
          //die($sql);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="360" height="18">&nbsp;</td>
    </tr>
  </table>
  <center>
  <?
  $funcao_js='teste|k17_codigo';
  db_lovrot($sql,15,"()","",$funcao_js);
  ?>
  </center>
</body>
</html>
<script>
function teste(slip){
    js_OpenJanelaIframe('top.corpo','db_iframe_slip2','cai3_conslip003.php?slip='+slip,'Slip nº '+slip,true);

}
</script>