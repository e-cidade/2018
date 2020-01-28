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

//MODULO: educação
require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_cardapiodata_classe.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_mer_subitem_classe.php");
include("dbforms/db_funcoes.php");

//Classes Materiais
include("classes/db_matrequiitem_classe.php");
include("classes/db_matrequi_classe.php");
include("classes/db_atendrequi_classe.php");
include("classes/db_atendrequiitem_classe.php");
include("classes/db_atendrequiitemmei_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matestoqueinimeiari_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_db_almox_classe.php");
include("classes/db_matmater_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_db_departorg_classe.php");
require("classes/requisicaoMaterial.model.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("classes/materialestoque.model.php");
//fim classes materiais

require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");

$escola = db_getsession("DB_coddepto");
$login = DB_getsession("DB_id_usuario");
db_postmemory($HTTP_POST_VARS);
$clmer_cardapiodata = new cl_mer_cardapiodata;
$clmer_cardapiodia = new cl_mer_cardapiodia;
$clmer_subitem = new cl_mer_subitem;

//Inclusão classes materiais
$clmatparam               = new cl_matparam;
$cldb_departorg           = new  cl_db_departorg;
$clmatparam               = new cl_matparam;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clmatrequiitem           = new cl_matrequiitem;
$clmatrequi               = new cl_matrequi;
$clatendrequiitem         = new cl_atendrequiitem;
$clatendrequiitemmei      = new cl_atendrequiitemmei;
$clatendrequi             = new cl_atendrequi;
$clmatestoque             = new cl_matestoque;
$clmatestoqueini          = new cl_matestoqueini;
$clmatestoqueinimei       = new cl_matestoqueinimei;
$clmatestoqueinimeiari    = new cl_matestoqueinimeiari;
$clmatestoqueitem         = new cl_matestoqueitem;
$cldb_almoxdepto          = new cl_db_almoxdepto;
$cldb_almox               = new cl_db_almox;
$clmatmater               = new cl_matmater;
$clmaterialEstoque        = new materialEstoque;
$oDaoMerCardapioDiaEscola = db_utils::getdao('mer_cardapiodiaescola');
$db_botao                 = true;
$rsParam                  = $clmatparam->sql_record($clmatparam->sql_query_file());
$oParam                   = db_utils::fieldsMemory($rsParam,0);
$tobserva                 = $oParam->m90_modrelsaidamat;
if (isset($m40_codigo)) {

  $sSqlAlmox   = "select m40_depto ";
  $sSqlAlmox  .= "  from matrequi  ";
  $sSqlAlmox  .= " where m40_codigo = {$m40_codigo}";
  $oDeptoRequi = db_utils::fieldsMemory($clmatrequi->sql_record($sSqlAlmox),0);

}
//fim inclusçao material
$db_opcao = 1;
$db_botao = true;
$erro_msg = "Não ouve Erro";

if(isset($incluir)){

 db_inicio_transacao();

 $sqlerro="N";
 $coddepto = db_getsession("DB_coddepto"); // O código da escola é o mesmo código do departamento

 // VERIFICO SE O DEPARTAMENTO É UM ALMOXARIFADO
 $sqlalmox = $cldb_almox->sql_query_file(null, "*", null, "m91_depto=$coddepto");
 $resalmox = $cldb_almox->sql_record($sqlalmox);
 if($cldb_almox->numrows>0) {
  db_fieldsmemory($resalmox, 0);
 }else{
  $sqlerro="S";
  $erro_msg="Departamento $coddepto não é um Almoxarifado!";
 }

 if ($sqlerro == 'N') {

   $data=date("Y-m-d",db_getsession("DB_datausu"));
   $vetcad=explode(",",$lista); // A variável $lista contém os códigos da mer_cardapiodia selecionados
   for ($x=0;$x<count($vetcad);$x++) {

     // Obtenho o código da mer_cardapiodiaescola referente à escola e ao cardápio dia informado
     $sSql = $oDaoMerCardapioDiaEscola->sql_query(null, 'me37_i_codigo', 'me37_i_codigo',
                                                  'me37_i_cardapiodia = '.$vetcad[$x].
                                                  ' and me32_i_escola = '.$coddepto
                                                 );
     $rs   = $oDaoMerCardapioDiaEscola->sql_record($sSql);
     if ($oDaoMerCardapioDiaEscola->numrows <= 0) {

       $sqlerro  = 'S';
       $erro_msg = 'Codigo da mer_cardapiodiaescola nao encontrado. Contate o administrador.';
       break;

     }

     $clmer_cardapiodata->me13_d_data              = $data;
     $clmer_cardapiodata->me13_i_cardapiodiaescola = db_utils::fieldsmemory($rs, 0)->me37_i_codigo;
     $clmer_cardapiodata->incluir(null);
     if ($clmer_cardapiodata->erro_status == '0') {

       $sqlerro  = 'S';
       $erro_msg = $clmer_cardapiodata->erro_msg;
       break;

     }

   }
   $vetitem=explode(",",$item);
   $vetquant=explode(",",$quant);

 }

 if($sqlerro=="N") {
  $clmatrequi->m40_data  = date("Y-m-d",db_getsession("DB_datausu"));
  $clmatrequi->m40_auto  = 't';
  $clmatrequi->m40_depto = $escola;
  $clmatrequi->m40_login = $login;
  $clmatrequi->m40_almox = $m91_codigo;
  $clmatrequi->m40_hora = db_hora();
  $clmatrequi->m40_obs = "";
  $clmatrequi->incluir(null);
  $codigorequi=$clmatrequi->m40_codigo;
  if ($clmatrequi->erro_status==0){
   $sqlerro="S";
   $erro_msg=$clmatrequi->erro_msg;
  }
 }
 if($sqlerro=="N") {
  $clatendrequi->m42_login=$login;
  $clatendrequi->m42_depto=$escola;
  $clatendrequi->m42_data=date('Y-m-d',db_getsession("DB_datausu"));
  $clatendrequi->m42_hora=db_hora();
  $clatendrequi->incluir(null);
  $codigoatend=$clatendrequi->m42_codigo;
  if ($clatendrequi->erro_status==0){
   $sqlerro="S";
   $erro_msg=$clatendrequi->erro_msg;
  }
 }
 if ($sqlerro=="N"){
  for($i=0;$i<count($vetitem);$i++){
   $clmatrequiitem->m41_codunid     = '1';
   $clmatrequiitem->m41_codmatrequi = $clmatrequi->m40_codigo;
   $clmatrequiitem->m41_codmatmater = $vetitem[$i];
   $clmatrequiitem->m41_quant = $vetquant[$i];
   $clmatrequiitem->m41_obs = "";
   $clmatrequiitem->incluir(null);
   if ($clmatrequiitem->erro_status==0) {
    $erro_msg = $clmatrequiitem->erro_msg;
    $sqlerro  = "S";
   }
   $codmater   = $clmatrequiitem->m41_codmatmater;
   $codreqitem = $clmatrequiitem->m41_codigo;
   $tot_quant  = $clmatrequiitem->m41_quant;
   // Gera Array Com Itens do Atendimento
   //(iCodMater iCodItemReq, nQtde ,iCodAlmox)
   $aItens = array();
   $aSubItens[$i]->iCodMater   = $codmater;
   $aSubItens[$i]->iCodItemReq = $codreqitem;
   $aSubItens[$i]->iCodalmox   = $coddepto;
   $aSubItens[$i]->nQtde       = $tot_quant;
   $aItens[] = $aSubItens;
   // Efetua atendimento da requisicao
  }
  try{
   $oRequisicao = new requisicaoMaterial($clmatrequi->m40_codigo);
   $oRequisicao->atenderRequisicao(17, $aSubItens, $coddepto,$clatendrequi->m42_codigo);
  }
  catch (Exception $eErro) {
   $sqlerro  = "S";
   $erro_msg = $eErro->getMessage();
  }
  if ($sqlerro=="S") {

    $sErroMessage  = 'Ocorreu um erro durante a baixa Verifique o estoque ou entre em contato com o suporte.\n';
    $sErroMessage .= str_replace("\n",'\n', $erro_msg);
    db_msgbox($sErroMessage);
    db_fim_transacao(($sqlerro == "S"?true:false));
    echo "<script>location.href='mer4_mer_baixamanual001.php';</scritp>";
   }
 }
 db_fim_transacao(($sqlerro == "S"?true:false));
 $m60_descr       = "";
 $m41_quant       = "";
 if($sqlerro!="S"){
  //db_msgbox("Baixa efetuada com sucesso!");
 }else{
  db_msgbox("Erro ($erro_msg)");
 }
 ?>
 <script>
  parent.js_carrega_iframe();
  parent.db_iframe_calculo.hide();
 </script>
 <?
 exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
.cabec{
 font-size: 12;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.descri{
 font-size: 13;
 font-weight: bold;
}
</style>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<?
$sql0 = "SELECT


        ";
//db_lovrot($sql0,15,"()","","","","NoMe");
$sql1="SELECT m60_codmater,
              substr(m60_descr,0,30) as m60_descr,
              me35_i_codigo,
              substr(me35_c_nomealimento,0,30) as me35_c_nomealimento,

              round(coalesce(
               (select coalesce(sum(mer_cardapioitem.me07_f_quantidade),0)
                       *
                       (
                        coalesce((select count(*) from mer_cardapioaluno where me11_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me40_i_repeticao) from mer_cardapioalunorepet where me40_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me39_i_repeticao+me39_i_quantidade) from mer_cardapioturma where me39_i_cardapiodia = m1.me12_i_codigo),0)
                       )
                from mer_cardapioitem
                 inner join mer_cardapiodia as m1 on m1.me12_i_cardapio = mer_cardapioitem.me07_i_cardapio
                where mer_cardapioitem.me07_i_alimento = mer_alimento.me35_i_codigo
                and m1.me12_d_data = mer_cardapiodia.me12_d_data
                and m1.me12_i_codigo in ($lista)
                group by m1.me12_i_codigo
               )
              ,0),2) as dl_qtde_inicial,

              round(coalesce(
               (select coalesce(sum(mitem1.me07_f_quantidade),0)
                       *
                       (
                        coalesce((select count(*) from mer_cardapioaluno where me11_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me40_i_repeticao) from mer_cardapioalunorepet where me40_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me39_i_repeticao+me39_i_quantidade) from mer_cardapioturma where me39_i_cardapiodia = m1.me12_i_codigo),0)
                       )
                from mer_subitem
                 inner join mer_cardapiodia as m1 on m1.me12_i_cardapio = mer_subitem.me29_i_refeicao
                 inner join mer_cardapioitem as mitem1 on mitem1.me07_i_alimento = mer_subitem.me29_i_alimentoorig AND mitem1.me07_i_cardapio = m1.me12_i_cardapio
                where mer_subitem.me29_i_alimentoorig = mer_alimento.me35_i_codigo
                and m1.me12_d_data = mer_cardapiodia.me12_d_data
                and m1.me12_i_codigo in ($lista)
                and m1.me12_d_data between mer_subitem.me29_d_inicio and mer_subitem.me29_d_fim
                group by m1.me12_i_codigo
               )
              ,0),2) as dl_qtde_substituido,

              round(coalesce(
               (select coalesce(sum(mer_subitem.me29_f_quantidade),0)
                       *
                       (
                        coalesce((select count(*) from mer_cardapioaluno where me11_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me40_i_repeticao) from mer_cardapioalunorepet where me40_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me39_i_repeticao+me39_i_quantidade) from mer_cardapioturma where me39_i_cardapiodia = m1.me12_i_codigo),0)
                       )
                from mer_subitem
                 inner join mer_cardapiodia as m1 on m1.me12_i_cardapio = mer_subitem.me29_i_refeicao
                where mer_subitem.me29_i_alimentonovo = mer_alimento.me35_i_codigo
                and m1.me12_d_data = mer_cardapiodia.me12_d_data
                and m1.me12_i_codigo in ($lista)
                and m1.me12_d_data between mer_subitem.me29_d_inicio and mer_subitem.me29_d_fim
                group by m1.me12_i_codigo
               )
              ,0),2) as dl_qtde_substituto,

              round(coalesce(
               (select coalesce(sum(mer_cardapioitem.me07_f_quantidade),0)
                       *
                       (
                        coalesce((select count(*) from mer_cardapioaluno where me11_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me40_i_repeticao) from mer_cardapioalunorepet where me40_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me39_i_repeticao+me39_i_quantidade) from mer_cardapioturma where me39_i_cardapiodia = m1.me12_i_codigo),0)
                       )
                from mer_cardapioitem
                 inner join mer_cardapiodia as m1 on m1.me12_i_cardapio = mer_cardapioitem.me07_i_cardapio
                where mer_cardapioitem.me07_i_alimento = mer_alimento.me35_i_codigo
                and m1.me12_d_data = mer_cardapiodia.me12_d_data
                and m1.me12_i_codigo in ($lista)
                group by m1.me12_i_codigo
               )
              ,0)
              -
              coalesce(
               (select coalesce(sum(mitem1.me07_f_quantidade),0)
                       *
                       (
                        coalesce((select count(*) from mer_cardapioaluno where me11_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me40_i_repeticao) from mer_cardapioalunorepet where me40_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me39_i_repeticao+me39_i_quantidade) from mer_cardapioturma where me39_i_cardapiodia = m1.me12_i_codigo),0)
                       )
                from mer_subitem
                 inner join mer_cardapiodia as m1 on m1.me12_i_cardapio = mer_subitem.me29_i_refeicao
                 inner join mer_cardapioitem as mitem1 on mitem1.me07_i_alimento = mer_subitem.me29_i_alimentoorig AND mitem1.me07_i_cardapio = m1.me12_i_cardapio
                where mer_subitem.me29_i_alimentoorig = mer_alimento.me35_i_codigo
                and m1.me12_d_data = mer_cardapiodia.me12_d_data
                and m1.me12_i_codigo in ($lista)
                and m1.me12_d_data between mer_subitem.me29_d_inicio and mer_subitem.me29_d_fim
                group by m1.me12_i_codigo
               )
              ,0)
              +
              coalesce(
               (select coalesce(sum(mer_subitem.me29_f_quantidade),0)
                       *
                       (
                        coalesce((select count(*) from mer_cardapioaluno where me11_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me40_i_repeticao) from mer_cardapioalunorepet where me40_i_cardapiodia = m1.me12_i_codigo),0)
                        +
                        coalesce((select sum(me39_i_repeticao+me39_i_quantidade) from mer_cardapioturma where me39_i_cardapiodia = m1.me12_i_codigo),0)
                       )
                from mer_subitem
                 inner join mer_cardapiodia as m1 on m1.me12_i_cardapio = mer_subitem.me29_i_refeicao
                where mer_subitem.me29_i_alimentonovo = mer_alimento.me35_i_codigo
                and m1.me12_d_data = mer_cardapiodia.me12_d_data
                and m1.me12_i_codigo in ($lista)
                and m1.me12_d_data between mer_subitem.me29_d_inicio and mer_subitem.me29_d_fim
                group by m1.me12_i_codigo
               )
              ,0),2) as dl_total_baixar,
              mer_cardapiodia.me12_d_data,
             (select m70_quant from matestoque where m70_codmatmater = m60_codmater and m70_coddepto = $escola) as m70_quant
       FROM matmater
        left join mer_alimentomatmater on mer_alimentomatmater.me36_i_matmater = matmater.m60_codmater
        left join mer_alimento on mer_alimento.me35_i_codigo = mer_alimentomatmater.me36_i_alimento
        left join mer_cardapioitem as mitem on mitem.me07_i_alimento = mer_alimento.me35_i_codigo
        left join mer_subitem as msub on msub.me29_i_alimentonovo = mer_alimento.me35_i_codigo
        left join mer_cardapiodia on mer_cardapiodia.me12_i_cardapio = mitem.me07_i_cardapio
                                  OR (mer_cardapiodia.me12_i_cardapio = msub.me29_i_refeicao AND mer_cardapiodia.me12_d_data between msub.me29_d_inicio and msub.me29_d_fim)
       WHERE mer_cardapiodia.me12_i_codigo in ($lista)
       GROUP BY mer_alimento.me35_i_codigo,mer_alimento.me35_c_nomealimento,matmater.m60_codmater,matmater.m60_descr,mer_cardapiodia.me12_d_data
       ORDER BY matmater.m60_descr,mer_cardapiodia.me12_d_data
       ";
//db_lovrot($sql1,15,"()","","","","NoMe");
$result_dados = pg_query($sql1);
$result_dia = $clmer_cardapiodia->sql_record($clmer_cardapiodia->sql_query("","DISTINCT me12_d_data as data_cabecalho","me12_d_data"," me12_i_codigo in ($lista)"));
$array_dias = array();
for($x=0;$x<$clmer_cardapiodia->numrows;$x++){
 db_fieldsmemory($result_dia,$x);
 $array_dias[] = $data_cabecalho;
}
?>
<table width="100%" cellspacing="0" cellpadding="0" border="1" bgcolor="#f3f3f3">
 <tr>
  <td class="cabec" align="center" colspan="<?=6+$clmer_cardapiodia->numrows?>">ÍTENS UTILZADOS NO PERÍODO</td>
 </tr>
 <tr>
  <td width="5%" class="cabec" align="center">Cód.</td>
  <td width="15%" class="cabec" align="center">Material</td>
  <td width="15%" class="cabec" align="center">Alimento</td>
  <td width="15%" class="cabec" >&nbsp;</td>
  <?
  for($x=0;$x<$clmer_cardapiodia->numrows;$x++){
   db_fieldsmemory($result_dia,$x);
   ?>
   <td align="center" class="cabec"><?=db_formatar($data_cabecalho,'d')?></td>
   <?
  }
  ?>
  <td align="center" class="cabec" width="5%">Total a baixar</td>
  <td align="center" class="cabec" width="5%">Estoque</td>
 </tr>
 <?
 $primeiro = "";
 $sum_ini = 0;
 $sum_subdo = 0;
 $sum_subto = 0;
 $sum_total = 0;
 $listaitem = "";
 $listaquant = "";
 $msg_erro = "";
 $sep1 = "";
 $sep2 = "";
 for($y=0;$y<pg_num_rows($result_dados);$y++){
  db_fieldsmemory($result_dados,$y);
  if($primeiro!=$m60_codmater){
   if($y>0){
    $estoque = pg_result($result_dados,$y-1,'m70_quant');
    $estoque = $estoque==""?0:$estoque;
    ?>
    <td align="center" width="5%">
     <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tr align="center"><td><?=$sum_ini?></td></tr>
      <tr align="center"><td><?=$sum_subdo?></td></tr>
      <tr align="center"><td><?=$sum_subto?></td></tr>
      <tr align="center"><td style="background:<?=($estoque<=0||$sum_total<=0||$estoque==$sum_total)?"#FF9999":"#CCFFCC"?>"><b><?=$sum_total?></b></td></tr>
     </table>
    </td>
    <td align="center" width="5%">
     <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tr align="center"><td>&nbsp;</td></tr>
      <tr align="center"><td>&nbsp;</td></tr>
      <tr align="center"><td>&nbsp;</td></tr>
      <tr align="center"><td style="background:<?=($estoque<=0||$sum_total<=0||$estoque==$sum_total)?"#FF9999":"#CCFFCC"?>"><?=$estoque?></td></tr>
     </table>
    </td>
    </tr>
    <?
    if(($estoque <= 0 || $estoque < $sum_total) && $estoque!=$sum_total){
     $msg_erro .= $sep1.pg_result($result_dados,$y-1,'m60_descr');
     $sep1 = " , ";
    }else{
     if($sum_total > 0){
      $listaitem .= $sep2.pg_result($result_dados,$y-1,'m60_codmater');
      $listaquant .= $sep2.$sum_total;
      $sep2 = ",";
     }
    }
    $sum_ini = 0;
    $sum_subdo = 0;
    $sum_subto = 0;
    $sum_total = 0;
   }
   ?>
   <tr>
    <td width="5%" class="descri" align="center"><?=$m60_codmater?></td>
    <td width="15%" class="descri"><?=$m60_descr?></td>
    <td width="15%" class="descri"><?=$me35_c_nomealimento?></td>
    <td width="15%">
     <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tr><td>&nbsp;&nbsp;Qtde.Inicial</td></tr>
      <tr><td>- Substituído</td></tr>
      <tr><td>+ Substituto</td></tr>
      <tr bgcolor="#DEB887"><td><b>= Total a baixar</b></td></tr>
     </table>
    </td>
    <?
    $primeiro = $m60_codmater;
    for($tt=0;$tt<count($array_dias);$tt++){
     ?>
     <td id="<?=$m60_codmater.$array_dias[$tt]?>">
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
       <tr align="center"><td>0</td></tr>
       <tr align="center"><td>0</td></tr>
       <tr align="center"><td>0</td></tr>
       <tr align="center" bgcolor="#DEB887"><td>0</b></td></tr>
      </table>
     </td>
     <?
    }
  }

    $sum_ini += $dl_qtde_inicial;
    $sum_subdo += $dl_qtde_substituido;
    $sum_subto += $dl_qtde_substituto;
    $sum_total += $dl_total_baixar;
  $texto = '<table width="100%" cellspacing="0" cellpadding="0" border="0">
             <tr align="center"><td>'.$dl_qtde_inicial.'</td></tr>
             <tr align="center"><td>'.$dl_qtde_substituido.'</td></tr>
             <tr align="center"><td>'.$dl_qtde_substituto.'</td></tr>
             <tr align="center" bgcolor="#DEB887"><td><b>'.$dl_total_baixar.'</b></td></tr>
            </table>';
  ?><script>document.getElementById("<?=$m60_codmater.$me12_d_data?>").innerHTML = <?=$texto?></script><?
 }
 $estoque = pg_result($result_dados,$y-1,'m70_quant');
 $estoque = $estoque==""?0:$estoque;
 if(($estoque <= 0 || $estoque < $sum_total) && $estoque!=$sum_total){
  $msg_erro .= $sep1.pg_result($result_dados,$y-1,'m60_descr');
  $sep1 = " , ";
 }else{
  if($sum_total > 0){
   $listaitem .= $sep2.pg_result($result_dados,$y-1,'m60_codmater');
   $listaquant .= $sep2.$sum_total;
   $sep2 = ",";
  }
 }
 ?>
 <td align="center" width="5%">
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
   <tr align="center"><td><?=$sum_ini?></td></tr>
   <tr align="center"><td><?=$sum_subdo?></td></tr>
   <tr align="center"><td><?=$sum_subto?></td></tr>
   <tr align="center"><td style="background:<?=($estoque<=0||$sum_total<=0||$estoque==$sum_total)?"#FF9999":"#CCFFCC"?>"><b><?=$sum_total?></b></td></tr>
  </table>
 </td>
 <td align="center" width="5%">
   <table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr align="center"><td>&nbsp;</td></tr>
    <tr align="center"><td>&nbsp;</td></tr>
    <tr align="center"><td>&nbsp;</td></tr>
    <tr align="center"><td style="background:<?=($estoque<=0||$sum_total<=0||$estoque==$sum_total)?"#FF9999":"#CCFFCC"?>"><?=$estoque?></td></tr>
   </table>
  </td>
 </tr>
</table>
<br>
<input type="button" name="voltar" value="Voltar" onclick="parent.db_iframe_calculo.hide();">
<?if($lista!="" && pg_num_rows($result_dados)>0){?>
 <input type="button" name="Incluir" value="Baixar Estoque" onclick="js_incluir('<?=$listaitem?>','<?=$listaquant?>','<?=$lista?>','<?=$msg_erro?>')">
<?}?>
</center>
</body>
</html>
<script>
function js_incluir(listaitem,listaquant,lista,msg_erro){
 if(msg_erro!=''){
  alert('Estoque insuficiente para o(s) item(s): '+msg_erro+'!');
  return false;
 }
 if(confirm('Tem certeza que deseja dar baixa nesses itens?')){
  location.href = 'mer4_mer_baixamanual002.php?item='+listaitem+'&quant='+listaquant+'&lista='+lista+'&incluir';
 }
}
parent.db_iframe_calculo.liberarJanBTFechar('false');
parent.db_iframe_calculo.liberarJanBTMinimizar('false');
parent.db_iframe_calculo.liberarJanBTMaximizar('false');
</script>