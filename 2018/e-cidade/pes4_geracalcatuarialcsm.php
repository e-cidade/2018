<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_selecao_classe.php"));
require_once(modification("dbforms/db_layouttxt.php"));
include(modification("classes/db_db_layoutcampos_classe.php"));
$oPost = db_utils::postMemory($_POST);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table>
<tr height=25><td>&nbsp;</td></tr>
</table>
<?
db_postmemory($HTTP_POST_VARS);
db_criatermometro('termometro','Concluido...','blue',1);
flush();
$wh               = '';
$clselecao        = null;
$sCaseProfessores = "1=2";
$sWhere           = "rh05_seqpes is null";
$sSelecao         = '';

if ($_POST["r44_selec"] != ''){

 $clselecao = new cl_selecao;
 $rsselec   =  $clselecao->sql_record($clselecao->sql_query($r44_selec, db_getsession("DB_instit")));
 db_fieldsmemory($rsselec,0);
 $sCaseProfessores  = "$r44_where";
 $sSelecao          = " AND $r44_where ";
}

$sSql     = "";
$sAndSoma = "";
$sAtivos  = " rh05_seqpes is null ";
if ($_POST["vinculo"] == "A") {

  $arquivo       = 'tmp/calc_ativoscsm_'.db_getsession("DB_instit").'.txt';
  $arqlayout     = 'tmp/calc_ativoslayoutcsm_'.db_getsession("DB_instit").'.txt';
  $sSqlSoma     = "select round(sum(r14_valor),2)";
  $sSqlSoma     .= "  from gerfsal ";
  $sSqlSoma     .= " where r14_regist = rh01_regist";
  $sSqlSoma     .= "   and r14_anousu = {$oPost->ano}";
  $sSqlSoma     .= "   and r14_mesusu = {$oPost->mes}";
  $sSqlSoma     .= "   and r14_instit  = ".db_getsession("DB_instit");
  $sSqlSoma     .= "   and r14_rubric  = 'R985'";
  $sAtivos      .= "and rh02_tbprev  = {$oPost->tabprev} ";
  $sAtivos      .= "and rh30_vinculo = '{$oPost->vinculo}'";

  $iCodigoLayOut = 69;
  $sSql  = "select z01_nome as nome,";
  $sSql .= "       rh01_regist as MATRICULA,";
  $sSql .= "       case when lower(rh01_sexo) = 'm' then '01' else '02'  END as SEXO,";
  $sSql .= "       TO_CHAR(rh01_admiss,'ddmmyyyy') as DATAADMISSAO,";
  $sSql .= "       TO_CHAR(rh01_nasc,'ddmmyyyy') as DATANASCIMENTO,";
  $sSql .= "       TO_CHAR(conjuge,'ddmmyyyy') as DTNASCCONJUGE,";
  $sSql .= "       TO_CHAR(filho,'ddmmyyyy') as DTNASCFILHONOVO,";
  $sSql .= "       case when lower(prof) = 'o' then '02' else '01' end  as OCUPACAO,";
  $sSql .= "       (select round(sum(h16_dtterm - h16_dtconc)/30) as tempo_contrib";
  $sSql .= "          from assenta ";
  $sSql .= "               inner join tipoasse on h12_codigo = h16_assent";
  $sSql .= "         where h12_reltot > 0 ";
  $sSql .= "           and h16_regist = rh01_regist";
  $sSql .= "        having sum(h16_dtterm - h16_dtconc) <> 0 ) as tempocontrib,";
  $sSql .= "       replace(valor::varchar, '.','') as salario";

} else if ($oPost->vinculo == "I") {

  $arquivo       = 'tmp/calc_inativosativoscsm_'.db_getsession("DB_instit").'.txt';
  $arqlayout     = 'tmp/calc_inativoslayoutcsm_'.db_getsession("DB_instit").'.txt';
  $sAndSoma     .=  "and r14_rubric  = 'R985'";
  $iCodigoLayOut = 70;
  $sSqlSoma      = "select round(sum(r14_valor),2)";
  $sSqlSoma     .= "  from gerfsal ";
  $sSqlSoma     .= " where r14_regist = rh01_regist";
  $sSqlSoma     .= "   and r14_anousu = {$oPost->ano}";
  $sSqlSoma     .= "   and r14_mesusu = {$oPost->mes}";
  $sSqlSoma     .= "   and r14_instit  = ".db_getsession("DB_instit");
  $sSqlSoma     .= "   and r14_pd = 1";
  $sAtivos      .= "and rh30_vinculo = '{$oPost->vinculo}'";
  $sSql  = "select z01_nome as nome,";
  $sSql .= "       rh01_regist as MATRICULA,";
  $sSql .= "       case when lower(rh01_sexo) = 'm' then '01' else '02'  END as SEXO,";
  $sSql .= "       TO_CHAR(rh01_admiss,'ddmmyyyy') as datainiciobeneficio,";
  $sSql .= "       TO_CHAR(rh01_nasc,'ddmmyyyy') as DATANASCIMENTO,";
  $sSql .= "       TO_CHAR(conjuge,'ddmmyyyy') as DTNASCCONJUGE,";
  $sSql .= "       TO_CHAR(filho,'ddmmyyyy') as DTNASCFILHONOVO,";
  $sSql .= "       '01' as tipobeneficio,";
  $sSql .= "       '01' as tipopensao,";
  $sSql .= "       replace(valor::varchar, '.','') as valor";

} else if ($oPost->vinculo == "P") {

  $arquivo       = 'tmp/calc_pensionistasativoscsm_'.db_getsession("DB_instit").'.txt';
  $arqlayout     = 'tmp/calc_pensionistaslayoutcsm_'.db_getsession("DB_instit").'.txt';
  $iCodigoLayOut = 71;
  $sSqlSoma      = "select round(sum(r14_valor),2)";
  $sSqlSoma     .= "  from gerfsal ";
  $sSqlSoma     .= " where r14_regist = rh01_regist";
  $sSqlSoma     .= "   and r14_anousu = {$oPost->ano}";
  $sSqlSoma     .= "   and r14_mesusu = {$oPost->mes}";
  $sSqlSoma     .= "   and r14_instit  = ".db_getsession("DB_instit");
  $sSqlSoma     .= "   and r14_pd = 1";
  $sAtivos      .= "and rh30_vinculo = '{$oPost->vinculo}'";

  $sSql  = "select z01_nome as nome,";
  $sSql .= "       rh01_regist as MATRICULA,";
  $sSql .= "       case when lower(rh01_sexo) = 'm' then '01' else '02'  END as SEXO,";
  $sSql .= "       TO_CHAR(rh01_admiss,'ddmmyyyy') as datainiciobeneficio,";
  $sSql .= "       TO_CHAR(rh01_nasc,'ddmmyyyy') as DATANASCIMENTO,";
  $sSql .= "       TO_CHAR(conjuge,'ddmmyyyy') as DTNASCCONJUGE,";
  $sSql .= "       TO_CHAR(filho,'ddmmyyyy') as DTNASCFILHONOVO,";
  $sSql .= "       '02' as tipobeneficio,";
  $sSql .= "       '01' as tipopensao,";
  $sSql .= "       replace(valor::varchar, '.','') as valor";

} else if ($oPost->vinculo == "E") {

  $arquivo       = 'tmp/calc_exoneradoscsm_'.db_getsession("DB_instit").'.txt';
  $arqlayout     = 'tmp/calc_exoneradoslayoutcsm_'.db_getsession("DB_instit").'.txt';
  $sSqlSoma      = "0::integer";
  $sAtivos       = "rh05_seqpes is not null ";
  $sAtivos      .= "and rh02_tbprev  = {$oPost->tabprev} ";
  $iCodigoLayOut = 72;
  $sSql  = "select z01_nome as nome,\n";
  $sSql .= "       rh01_regist as MATRICULA,\n";
  $sSql .= "       case when lower(rh01_sexo) = 'm' then '01' else '02'  END as SEXO,\n";
  $sSql .= "       TO_CHAR(rh01_admiss,'ddmmyyyy') as DATAADMISSAO,\n";
  $sSql .= "       TO_CHAR(rh01_nasc,'ddmmyyyy') as DATANASCIMENTO,\n";
  $sSql .= "       TO_CHAR(rh05_recis,'ddmmyyyy') as dataexoneracao,\n";
  $sSql .= "       case when lower(prof) = 'o' then '02' else '01' end  as OCUPACAO,\n";
  $sSql .= "       replace(valor::varchar, '.','') as ultimosalario\n";

}

$cldb_layouttxt = new db_layouttxt($iCodigoLayOut, $arquivo, "");
$sSql .= "  from ";
$sSql .= "      (";
$sSql .= "      select z01_nome,";
$sSql .= "             rh01_regist,";
$sSql .= "             rh01_sexo,";
$sSql .= "             case when {$sCaseProfessores} then 'P' else 'O' end ";
$sSql .= "             as prof,";
$sSql .= "             rh01_admiss,";
$sSql .= "             rh01_nasc  ,";
$sSql .= "             (select rh31_dtnasc";
$sSql .= "                from rhdepend";
$sSql .= "               where rh31_gparen = 'C' and rh31_regist = rh01_regist limit 1";
$sSql .= "              ) as conjuge,";
$sSql .= "              (select rh31_dtnasc";
$sSql .= "                 from rhdepend";
$sSql .= "                where rh31_gparen = 'F' and rh31_regist = rh01_regist order by rh31_dtnasc desc limit 1";
$sSql .= "              ) as filho,";
$sSql .= "              rh05_recis," ;
$sSql .= "              rh30_vinculo, ({$sSqlSoma}) as valor";
$sSql .= "              from rhpessoal ";
$sSql .= "                   inner join rhpessoalmov   on rh01_regist = rh02_regist ";
$sSql .= "                                            and rh02_anousu = {$oPost->ano}";
$sSql .= "                                            and rh02_mesusu = {$oPost->mes} ";
$sSql .= "                                            and rh02_instit = ".db_getsession("DB_instit");
$sSql .= "                   inner join rhregime       on rh30_codreg = rh02_codreg ";
$sSql .= "                                            and rh30_instit = rh02_instit ";
$sSql .= "                   left join rhpesrescisao   on rh02_seqpes = rh05_seqpes ";
$sSql .= "                   inner join cgm            on rh01_numcgm = z01_numcgm ";
$sSql .= "                   inner join rhfuncao       on rh37_funcao = rh01_funcao ";
$sSql .= "                                            and rh37_instit = rh02_instit ";
$sSql .= "             where {$sAtivos} {$sSelecao}";
$sSql .= "             order by z01_nome ";
$sSql .= "             ) as x; ";

$rsCalculo = db_query($sSql);
if ($rsCalculo) {
  $iTotalLinha = pg_num_rows($rsCalculo);
  for ($i = 0; $i < $iTotalLinha; $i++) {

    $oLinhaCalculo               = db_utils::fieldsMemory($rsCalculo, $i);
    $cldb_layouttxt->setByLineOfDBUtils($oLinhaCalculo, 3);
    db_atutermometro($i, $iTotalLinha, 'termometro');
  }

    /**
     * LAyout do layout
     */
    $cldb_layouttxt = new db_layouttxt(12, $arqlayout, "");
    $oLayoutCampos = new cl_db_layoutcampos;
    $dbwhere = " db51_layouttxt = {$iCodigoLayOut}";
    $sql = $oLayoutCampos->sql_query(null,"
      db52_nome,
      db52_descr,
      db52_layoutformat,
      db52_posicao as db52_posicao_inicial,
      db52_posicao - 1 + (case when db52_tamanho = 0 then db53_tamanho else db52_tamanho end) as db52_posicao_final",
      "db52_posicao",$dbwhere);
    $result = $oLayoutCampos->sql_record($sql);
    $cldb_layouttxt->setCampoTipoLinha(3); // 3-Registro
    for ($x = 0; $x < $oLayoutCampos->numrows; $x++) {

      db_fieldsmemory($result, $x);
      $cldb_layouttxt->setCampo("posicao_inicial", $db52_posicao_inicial);
      $cldb_layouttxt->setCampo("posicao_final",   $db52_posicao_final);
      $cldb_layouttxt->setCampo("nome_campo",      $db52_nome);
      $cldb_layouttxt->setCampo("descricao",       $db52_descr);
      $cldb_layouttxt->geraDadosLinha();

    }
} else {

  db_msgbox("Erro ao processar os dados.\\nVerifique o cadastro da selecão de professores");

}
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

?>
<form name='form1' id='form1'></form>
<?
if ($rsCalculo) {

  echo "<script>";
  echo "  listagem = '$arquivo#Download arquivo TXT (dados dos calculo)|';";
  echo "  listagem+= '$arqlayout#Download arquivo TXT (layout do arquivo)';";
  echo "  js_montarlista(listagem,'form1', 'js_manda');";
  echo "</script>";
}
  ?>

<script>
  
function js_manda(){
		location.href='pes4_geracalcaturialcsm001.php';
}
</script>
</body>
</html>