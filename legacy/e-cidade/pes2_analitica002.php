<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once 'fpdf151/pdf.php';

require_once 'libs/db_sql.php';
require_once 'libs/db_libpessoal.php';

require_once 'classes/db_selecao_classe.php';
require_once 'classes/db_rhcadregime_classe.php';


$clgerasql = new cl_gera_sql_folha;
$clselecao = new cl_selecao;
$clrhcadregime = new cl_rhcadregime;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clgerasql->inner_rub = false;
$clgerasql->usar_ger = true;
$clgerasql->usar_cgm = true;
$clgerasql->usar_rub = true;
$clgerasql->usar_lot = true;
$clgerasql->usar_fun = true;
$clgerasql->usar_res = true;
$clgerasql->usar_atv = true;
$clgerasql->usar_pad = true;
$clgerasql->usar_car = true;

if($ansin == "a"){
  $impressao = "ANALÍTICO";
}else{
  $impressao = "SINTÉTICO";
}

$head2 = "FOLHA DE PAGAMENTO (".$mes." / ".$ano.") - ".$impressao;
$head4 = "ARQUIVO : ";

//$whereRESC = " rh05_seqpes is null and (r45_regist is null or  r45_regist is not null and (r45_dtreto is null or r45_dtreto > '".$ano."-".$mes."-01'))";
$whereRESC = " rh05_seqpes is null ";
$andwhere = " and  ";
$aWhere   = array();
$aWhere[] = " rh05_seqpes is null ";


$clgerasql->inicio_rh = true;

/**
 * $clgerasql->inner_ger define se realiza inner join com a tabela.
 * A tabela a qual vai ser realizado o inner depende do tipo de folha emitido ($folha)
 */
if($folha == 'r14'){
//  $clgerasql->inicio_rh = true;
  /**
   * Retornado fonte, pois existem afastamentos sem remuneração e servidores nesta condição não apareciam
   * no relatório mesmo selecionando afastados, já que não tinham cálculo de salário
   */
  $clgerasql->inner_ger = false;
  $head4.= 'DE SALÁRIO';
}else if($folha == 'r20'){
  $head4.= 'DE RESCISÃO';
  $whereRESC = "";
  $aWhere   = array();
  $andwhere = "";
}else if($folha == 'r35') {
//  $clgerasql->inicio_rh = true;
  $clgerasql->inner_ger = true;
  $head4     .= 'DE 13o SALÁRIO';
  $whereRESC  = " ";
  $aWhere   = array();

}else if($folha == 'r22'){
  $head4.= 'DE ADIANTAMENTO';
}else if($folha == 'r48'){
  $head4.= 'COMPLEMENTAR';
}else if($folha == 'sup'){
  $head4.= 'SUPLEMENTAR';
}
$head5 = "GERAL";
$orderBY= " z01_nome,rh01_regist,r14_rubric";
$sWhereSup = "";
if(isset($semest) && $semest > 0 ){

  $whereRESC.= $andwhere." r48_semest = ".$semest;
  if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    $sWhereSup = "AND rh141_codigo = {$semest}";
  } else {
    $aWhere[] = "r48_semest = ".$semest;
  }
  $andwhere = " and ";
}

$lotacao = false;

if($folha == 'r20'){
  $camposFiltrar1 = " , r20_tpp ";
  $camposFiltrar  = " , r20_tpp ";
}else{
  $camposFiltrar1 = " ";
  $camposFiltrar  = "";
}


if($tipo == "m"){
  // Se for escolhida alguma matrícula

  $head5 = "MATRÍCULAS";
  $orderBY= " rh01_regist,r14_rubric";

  if(isset($rei) && trim($rei) != "" && isset($ref) && trim($ref) != ""){
    // Se for por intervalos e vier matrícula inicial e final
    $whereRESC.= $andwhere." rh01_regist between ".$rei." and ".$ref;

    $aWhere[] = " rh01_regist between ".$rei." and ".$ref;
    $andwhere = " and ";
    $head5.= " DE ".$rei." A ".$ref;
  }else if(isset($rei) && trim($rei) != ""){
    // Se for por intervalos e vier somente matrícula inicial
    $whereRESC.= $andwhere." rh01_regist >= ".$rei;
    $aWhere[] = " rh01_regist >= ".$rei;
    $andwhere = " and ";
    $head5.= " SUPERIORES A ".$rei;
  }else if(isset($ref) && trim($ref) != ""){
    // Se for por intervalos e vier somente matrícula final
    $whereRESC.= $andwhere." rh01_regist <= ".$ref;
    $aWhere[] = " rh01_regist <= ".$ref;
    $andwhere = " and ";
    $head5.= " INFERIORES A ".$ref;
  }else if(isset($fre) && trim($fre) != ""){
    // Se for por selecionados
    $whereRESC.= $andwhere." rh01_regist in (".$fre.") ";
    $aWhere[] = " rh01_regist in (".$fre.") ";

    $andwhere = " and ";
    $head5.= " SELECIONADAS";
  }

}else if($tipo == "l"){
  // Se for escolhida alguma lotação


  $lotacao = true;

  $head5 = "LOTAÇÕES";
  $orderBY= " r70_estrut,z01_nome,rh01_regist,r14_rubric";
  $camposFiltrar .= ", r70_estrut as codigofiltro, r70_descr as descrifiltro, r70_estrut as estrutfiltro ";

  if(isset($lti) && trim($lti) != "" && isset($ltf) && trim($ltf) != ""){
    // Se for por intervalos e vier lotação inicial e final
    $whereRESC.= $andwhere." r70_estrut between '".$lti."' and '".$ltf."' ";
    $aWhere[] = " r70_estrut between '".$lti."' and '".$ltf."' ";
    $andwhere = " and ";
    $head5.= " DE ".$lti." A ".$ltf;
  }else if(isset($lti) && trim($lti) != ""){
    // Se for por intervalos e vier somente lotação inicial
    $whereRESC.= $andwhere." r70_estrut >= '".$lti."' ";
    $aWhere[] = " r70_estrut >= '".$lti."' ";
    $andwhere = " and ";
    $head5.= " SUPERIORES A ".$lti;
  }else if(isset($ltf) && trim($ltf) != ""){
    // Se for por intervalos e vier somente lotação final
    $whereRESC.= $andwhere." r70_estrut <= '".$ltf."' ";
    $aWhere[] = " r70_estrut <= '".$ltf."' ";
    $andwhere = " and ";
    $head5.= " INFERIORES A ".$ltf;
  }else if(isset($flt) && trim($flt) != ""){
    // Se for por selecionados
    $whereRESC.= $andwhere." r70_estrut in ('".str_replace(",","','",$flt)."') ";
    $aWhere[] = " r70_estrut in ('".str_replace(",","','",$flt)."') ";
    $andwhere = " and ";
    $head5.= " SELECIONADAS";
  }


}else if($tipo == "t"){
  // Se for escolhido algum local de trabalho

  $head5   = "LOCAIS DE TRABALHO";
  $orderBY = " rh55_estrut,z01_nome,rh01_regist,r14_rubric";
  $camposFiltrar  .= ", rh55_estrut as codigofiltro, rh55_descr as descrifiltro, rh55_estrut as estrutfiltro ";
  $camposFiltrar1 .= ", rh55_estrut , rh55_descr ";
  $clgerasql->usar_tra = true;

  if(isset($lci) && trim($lci) != "" && isset($lcf) && trim($lcf) != ""){
    // Se for por intervalos e vier local inicial e final
    $whereRESC .= $andwhere." rh55_estrut between '".$lci."' and '".$lcf."' ";
    $aWhere[]   = " rh55_estrut between '".$lci."' and '".$lcf."' ";
    $andwhere   = " and ";
    $head5     .= " DE ".$lci." A ".$lcf;
  }else if(isset($lci) && trim($lci) != ""){
    // Se for por intervalos e vier somente local inicial
    $whereRESC.= $andwhere." rh55_estrut >= '".$lci."' ";
    $aWhere[] = " rh55_estrut >= '".$lci."' ";
    $andwhere = " and ";
    $head5.= " SUPERIORES A ".$lci;
  }else if(isset($lcf) && trim($lcf) != ""){
    // Se for por intervalos e vier somente local final
    $whereRESC.= $andwhere." rh55_estrut <= '".$lcf."' ";
    $aWhere[] = " rh55_estrut <= '".$lcf."' ";
    $andwhere = " and ";
    $head5.= " INFERIORES A ".$lcf;
  }else if(isset($flc) && trim($flc) != ""){
    // Se for por selecionados
    $whereRESC.= $andwhere." rh55_estrut in ('".str_replace(",","','",$flc)."') ";
    $aWhere[] = " rh55_estrut in ('".str_replace(",","','",$flc)."') ";
    $andwhere = " and ";
    $head5.= " SELECIONADOS";
  }

}else if($tipo == "o"){
  // Se for escolhido algum órgão

  $head5 = "ÓRGÃOS";
  $orderBY= " o40_orgao,z01_nome,rh01_regist,r14_rubric";
  $camposFiltrar .= ", o40_orgao as codigofiltro, o40_descr as descrifiltro, '' as estrutfiltro ";
  $camposFiltrar1 .= ", o40_orgao , o40_descr ";
  $clgerasql->usar_org = true;

  if(isset($ori) && trim($ori) != "" && isset($orf) && trim($orf) != ""){
    // Se for por intervalos e vier órgão inicial e final
    $whereRESC.= $andwhere." o40_orgao between ".$ori." and ".$orf;
    $aWhere[] = " o40_orgao between ".$ori." and ".$orf;
    $andwhere = " and ";
    $head5.= " DE ".$ori." A ".$orf;
  }else if(isset($ori) && trim($ori) != ""){
    // Se for por intervalos e vier somente órgão inicial
    $whereRESC.= $andwhere." o40_orgao >= ".$ori;
    $aWhere[] = " o40_orgao >= ".$ori;
    $andwhere = " and ";
    $head5.= " SUPERIORES A ".$ori;
  }else if(isset($orf) && trim($orf) != ""){
    // Se for por intervalos e vier somente órgão final
    $whereRESC.= $andwhere." o40_orgao <= ".$orf;
    $aWhere[] = " o40_orgao <= ".$orf;
    $andwhere = " and ";
    $head5.= " INFERIORES A ".$orf;
  }else if(isset($for) && trim($for) != ""){
    // Se for por selecionados
    $whereRESC.= $andwhere." o40_orgao in (".$for.") ";
    $aWhere[] = " o40_orgao in (".$for.") ";
    $andwhere = " and ";
    $head5.= " SELECIONADOS";
  }

}

if ($reg != 0) {

  $whereRESC.= $andwhere." rh30_regime = ".$reg;
  $aWhere[] = " rh30_regime = ".$reg;
  $andwhere = " and ";
  $result_rhcadregime = $clrhcadregime->sql_record($clrhcadregime->sql_query_file($reg," rh52_descr "));
  if($clrhcadregime->numrows > 0){
    db_fieldsmemory($result_rhcadregime, 0);
    $head7 = "REGIME: ".$reg." - ".$rh52_descr;
  }
}

if (trim($sel) != "") {

  $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($sel,db_getsession("DB_instit")," r44_descr, r44_where "));
  if ($clselecao->numrows > 0) {

    db_fieldsmemory($result_selecao, 0);
    $whereRESC.= $andwhere." ".$r44_where;
    $aWhere[] = $r44_where;
    $head8 = "SELEÇÃO: ".$sel." - ".$r44_descr;
  }
}

$camposSQL = "
              rh01_regist,
              rh01_numcgm,
              rh02_codreg ,
              z01_nome,
              z01_nasc,
              #s#_valor  as r14_valor,
              #s#_quant  as r14_quant,
              #s#_rubric as r14_rubric,
              #s#_pd     as r14_pd,
              r70_codigo,
              r70_estrut,
              r70_descr,
              rh27_descr,
              rh37_funcao,
              rh37_descr,
              rh02_hrsmen,
              rh01_admiss,
              rh02_tbprev,
              rh04_descr,
              case when #s#_rubric < 'R950' then 'PD' else 'N' end as provdesc,
              case rh30_vinculo when 'A' then 'Ativo'
                                when 'P' then 'Pensionista'
                                else          'Inativo'
                                end as rh30_vinculo,
              r02_descr
              $camposFiltrar1
             ";


$sNovoWhere = implode(" and ", $aWhere);

$sql_dados = $clgerasql->gerador_sql($folha,$ano,$mes,null,null,$camposSQL,"",$sNovoWhere, null, $sWhereSup);
$sql_dados1 = "select distinct
                      rh01_regist,
                      rh01_numcgm,
                      rh02_codreg as rh01_clas1,
                      z01_nome,
                      z01_nasc,
                      r14_valor,
                      r14_quant,
                      r14_rubric,
                      r14_pd,
                      rh27_descr,
                      rh37_funcao,
                      rh37_descr,
                      r70_codigo,
                      r70_estrut,
                      r70_descr,
                      rh02_hrsmen,
                      rh01_admiss,
                      rh02_tbprev,
                      rh04_descr,
                      provdesc,
                      rh30_vinculo,
                      ( select case
                                 when r45_regist is not null and (max(r45_dtreto) is null or max(r45_dtreto) > '".$ano."-".$mes."-01')
                                   then
                                      case r45_situac
                                        when 2 then 'S/Remuneração'
                                        when 3 then 'Acidente'
                                        when 4 then 'S.Militar'
                                        when 5 then 'Gestante'
                                        when 6 then 'Doença'
                                        when 8 then 'Doença'
                                      else 'S/Venc.' end
                               else 'Normal' end as situacao_funcionario
                          from afasta
                         where r45_anousu = $ano
                           and r45_mesusu = $mes
                           and r45_regist = rh01_regist
                           and (    r45_regist is null
                                 or r45_regist is not null
                                and (r45_dtreto is null or r45_dtreto >= '".$ano."-".$mes."-01')
                               )
                         group by r45_regist, r45_dtreto, r45_situac
                         order by r45_dtreto limit 1
                      ) as situacao_funcionario,
                      r02_descr
                      $camposFiltrar
                 from ($sql_dados) as x ";
if ($afastado == 'n') {
	$sql_dados1 .= " where not exists(select 1
                                     from afasta
                                    where r45_anousu = $ano
                                      and r45_mesusu = $mes
                                      and r45_regist = rh01_regist
                                      and (r45_regist is null
                                           or r45_regist is not null
                                          and (r45_dtreto is null or r45_dtreto > '".$ano."-".$mes."-01')
                                          )
                                     ) ";
}
$sql_dados1 .= " order by $orderBY";

$result_dados = db_query($sql_dados1);
$numrows_dados = pg_numrows($result_dados);

if($numrows_dados == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem lançamentos no período de '.$mes.' / '.$ano);
}

db_sel_cfpess();

$antigoregistro = "";

// Arrays de auxílio
// Contador de proventos e descontos por REGISTRO
$arr_contadorregD = Array();
$arr_contadorregP = Array();

// Arrays com os dados dos funcionários
$arr_indexfuncion = Array(); // Verificar se o Registro já passou pelo FOR
$arr_funcionarios = Array(); // Registro do funcionário
$arr_nomesfuncion = Array(); // Nome do funcionário
$arr_horasfuncion = Array(); // Horas mês do funcionário
$arr_lotacfuncion = Array(); // Lotação do funcionário
$arr_descrfuncion = Array(); // Função do funcionário
$arr_nascifuncion = Array(); // Data de nascimento do funcionário
$arr_situafuncion = Array(); // Situação do funcionário (Ativo ou Inativo)
$arr_admisfuncion = Array(); // Data de admissão do funcionário
$arr_afastfuncion = Array(); // Situação do funcionário (se afastado)
$arr_dpadrfuncion = Array(); // Descrição do padrão do funcionário

// Arrays com as quebras de página
$arr_quebras_codigo = Array(); // Código da lotação, do local de trabalho ou do órgão
$arr_quebras_descri = Array(); // Descrição da lotação, do local de trabalho ou do órgão
$arr_quebras_estrut = Array(); // Estrutural da lotação, do local de trabalho ou do órgão

$arr_clas1  = Array(); // Data de admissão do funcionário
$arr_cargo  = Array(); // Data de admissão do funcionário
$arr_padrao = Array(); // Data de admissão do funcionário

// Arrays com as rubricas, quantidades e valores pertencentes a um funcionário
// -- PROVENTOS
$arr_rubricascodP = Array(); // Código da rubrica de desconto
$arr_rubricasdesP = Array(); // Descrição da rubrica de desconto
$arr_rubricasqtdP = Array(); // Quantidade da rubrica de desconto
$arr_rubricasvlrP = Array(); // Valor da rubrica de desconto

// -- DESCONTOS
$arr_rubricascodD = Array(); // Código da rubrica de desconto
$arr_rubricasdesD = Array(); // Descrição da rubrica de desconto
$arr_rubricasqtdD = Array(); // Quantidade da rubrica de desconto
$arr_rubricasvlrD = Array(); // Valor da rubrica de desconto

// Arrays com as bases dos funcionários
$arr_salabase = Array(); // Salário base
$arr_baseFGTS = Array(); // Base FGTS
$arr_bmesFGTS = Array(); // FGTS Mês
$arr_baliqIRF = Array(); // Líquido do IRF
$arr_depenIRF = Array(); // Depend IRF
$arr_bdeducao = Array(); // Deduções
$arr_baseINSS = Array(); // Base INSS
$arr_baseBACL = Array(); // B AC L
$arr_basePRE1 = Array(); // Previdência INSS
$arr_baseOUTR = Array(); // B OUTR
$arr_basePRE2 = Array(); // Previdência B OUTR
$arr_bINSSPat = Array(); // Base INSS Patronal
$arr_bOUTRPat = Array(); // B OUTR Patronal

$arr_SintPrev = Array(); // Array para valores da previdência na folha sintética
$arr_SintIrrf = Array(); // Array para valores do IRRF na folha sintética
$arr_SintSalF = Array(); // Array para valores do Salário família na folha sintética

// Quantidade de funcionários e índex, para no segundo FOR, buscar nos Arrays
$index = 0;

// Coloca a vírgula entre os dados das rubricas
$virgP = "";
$virgD = "";

// Esse FOR passará os valores para os Arrays
for($x = 0;$x < pg_numrows($result_dados);$x++){
  db_fieldsmemory($result_dados,$x);
  // Testa se registro já passou pelo for e se não tiver passado, setará os valores em seu respectivos ARRAYS
  if(!isset($arr_indexfuncion[$rh01_regist])){

    db_retorno_variaveis($ano, $mes, $rh01_regist);

    $arr_indexfuncion["$rh01_regist"] = $rh01_regist;
    $arr_funcionarios["$index"]       = $rh01_regist;
    $arr_nomesfuncion["$index"]       = $z01_nome;
    $arr_horasfuncion["$index"]       = $rh02_hrsmen;
    $arr_lotacfuncion["$index"]       = $r70_estrut;
    $arr_lotdescrfuncion["$index"]    = $r70_descr;
    $arr_descrfuncion["$index"]       = $rh37_descr;
    $arr_nascifuncion["$index"]       = db_formatar($z01_nasc,"d");
    $arr_situafuncion["$index"]       = $rh30_vinculo;
    $arr_admisfuncion["$index"]       = db_formatar($rh01_admiss,"d");
    $arr_afastfuncion["$index"]       = $situacao_funcionario;
    $arr_dpadrfuncion["$index"]       = $r02_descr;

    if($tipo != "g" && $tipo != "m"){
        $arr_quebras_codigo["$rh01_regist"] = $codigofiltro;
        $arr_quebras_descri["$rh01_regist"] = $descrifiltro;
        $arr_quebras_estrut["$rh01_regist"] = $estrutfiltro;
    }

    $arr_f010["$index"]   = $f010;
    $arr_padrao["$index"] = $padrao;
    $arr_clas1["$index"]  = $rh01_clas1;
    $arr_cargo["$index"]  = $rh04_descr;

    $index ++;
    $virgP = "|";
    $virgD = "|";

  }

  // Testa se já existe alguma rubrica para o registro corrente (PROVENTO)
  if(!isset($arr_rubricascodP["$rh01_regist"])){
    $arr_rubricascodP["$rh01_regist"] = "";
  }

  // Testa se já existe alguma descrição rubrica para o registro corrente (PROVENTO)
  if(!isset($arr_rubricasdesP["$rh01_regist"])){
    $arr_rubricasdesP["$rh01_regist"] = "";
  }

  // Testa se já existe alguma quantidade da rubrica para o registro corrente (PROVENTO)
  if(!isset($arr_rubricasqtdP["$rh01_regist"])){
    $arr_rubricasqtdP["$rh01_regist"] = "";
  }

  // Testa se já existe algum valor da rubrica para o registro corrente (PROVENTO)
  if(!isset($arr_rubricasvlrP["$rh01_regist"])){
    $arr_rubricasvlrP["$rh01_regist"] = "";
  }

  // Testa se já existe alguma rubrica para o registro corrente (DESCONTO)
  if(!isset($arr_contadorregP["$rh01_regist"])){
    $arr_contadorregP["$rh01_regist"] = 0;
  }

  // Testa se já existe alguma descrição rubrica para o registro corrente (DESCONTO)
  if(!isset($arr_rubricascodD["$rh01_regist"])){
    $arr_rubricascodD["$rh01_regist"] = "";
  }

  // Testa se já existe alguma quantidade da rubrica para o registro corrente (DESCONTO)
  if(!isset($arr_rubricasdesD["$rh01_regist"])){
    $arr_rubricasdesD["$rh01_regist"] = "";
  }

  // Testa se já existe algum valor da rubrica para o registro corrente (DESCONTO)
  if(!isset($arr_rubricasqtdD["$rh01_regist"])){
    $arr_rubricasqtdD["$rh01_regist"] = "";
  }

  // Testa se já existe algum valor da rubrica para o registro corrente (DESCONTO)
  if(!isset($arr_rubricasvlrD["$rh01_regist"])){
    $arr_rubricasvlrD["$rh01_regist"] = "";
  }

  // Testa se já existem o contador de proventos da rubrica
  if(!isset($arr_contadorregP["$rh01_regist"])){
    $arr_contadorregP["$rh01_regist"] = 0;
  }

  // Testa se já existem o contador de descontos da rubrica
  if(!isset($arr_contadorregD["$rh01_regist"])){
    $arr_contadorregD["$rh01_regist"] = 0;
  }

  // Testa se é provento ou desconto
  // ONDE: "PD" É provento ou desconto;
  //       "N"  Não é nenhum dos dois.
  if($provdesc == "PD"){

    // Se for provento ou desconto, testa se a rubrica esta cadastrada como provento ou desconto
    // ONDE: 1  É Provento
    //       2  É desconto
    if($r14_pd==1){
      $arr_contadorregP["$rh01_regist"]++;
      $arr_rubricascodP["$rh01_regist"] .= $virgP.$r14_rubric;
      $arr_rubricasdesP["$rh01_regist"] .= $virgP.$rh27_descr;
      $arr_rubricasqtdP["$rh01_regist"] .= $virgP.$r14_quant;
      $arr_rubricasvlrP["$rh01_regist"] .= $virgP.$r14_valor;
      /**
       * Anteriormente o valor desta variável era um ','(virgula)
       * Mas por, posteriormente, dar um split por virgula nestes valores do array acima,
       * Havia algumas descricoes de rubrica com virgula no nome, daí o problema.
       */
      $virgP = "|";
    } else if ($r14_pd == 2) {

      $arr_contadorregD["$rh01_regist"]++;
      $arr_rubricascodD["$rh01_regist"] .= $virgD.$r14_rubric;
      $arr_rubricasdesD["$rh01_regist"] .= $virgD.$rh27_descr;
      $arr_rubricasqtdD["$rh01_regist"] .= $virgD.$r14_quant;
      $arr_rubricasvlrD["$rh01_regist"] .= $virgD.$r14_valor;
      /**
       * Anteriormente o valor desta variável era um ','(virgula)
       * Mas por, posteriormente, dar um split por virgula nestes valores do array acima,
       * Havia algumas descricoes de rubrica com virgula no nome, daí o problema.
       */
      $virgD = "|";
    }
  }

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *   Cálculo dos valores do salário base   * %%%*/
  // Testa se já existem os valores do salário base
  if(!isset($arr_salabase["$rh01_regist"])){
    $arr_salabase["$rh01_regist"] = 0;
  }
  if(isset($arr_salabase["$rh01_regist"])){
    $arr_salabase["$rh01_regist"] = $f010;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *  Cálculo dos valores do líquido do IRF  * %%%*/
  // Testa se já existem os valores do líquido do IRF
  if(!isset($arr_baliqIRF["$rh01_regist"])){
    $arr_baliqIRF["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R981"){
    $arr_baliqIRF["$rh01_regist"] += $r14_valor;
  }

  if($r14_rubric == "R982"){
    $arr_baliqIRF["$rh01_regist"] += $r14_valor;
  }

  if($r14_rubric == "R983"){
    $arr_baliqIRF["$rh01_regist"] += $r14_valor;
  }

  if($r14_rubric == "R984"){
    $arr_baliqIRF["$rh01_regist"] -= $r14_valor;
  }

  if($r14_rubric == "R988"){
    $arr_baliqIRF["$rh01_regist"] -= $r14_valor;
  }

  if($r14_rubric == "R989"){
    $arr_baliqIRF["$rh01_regist"] -= $r14_valor;
  }

  if($r14_rubric == "R997"){
    $arr_baliqIRF["$rh01_regist"] -= $r14_valor;
  }

  if($r14_rubric == "R999"){
    $arr_baliqIRF["$rh01_regist"] -= $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *   Cálculo dos valores da base do INSS   * %%%*/
  // Testa se já existem os valores da base INSS
  if(!isset($arr_baseINSS["$rh01_regist"])){
    $arr_baseINSS["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R992" && $rh02_tbprev == $r11_tbprev){
    $arr_baseINSS["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% * Cálculo dos valores da base do INSS Pat * %%%*/
  // Testa se já existem os valores da base INSS Patronal
  if(!isset($arr_bINSSPat["$rh01_regist"])){
    $arr_bINSSPat["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R990" && $rh02_tbprev == $r11_tbprev){
    $arr_bINSSPat["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *    Cálculo dos valores do Depend IRF    * %%%*/
  // Testa se já existem os valores do Depend IRF
  if(!isset($arr_depenIRF["$rh01_regist"])){
    $arr_depenIRF["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R984"){
    $arr_depenIRF["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *   Cálculo dos valores de Prev do INSS   * %%%*/
  // Testa se já existem os valores de Previdência INSS
  if(!isset($arr_basePRE1["$rh01_regist"])){
    $arr_basePRE1["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R993" && $rh02_tbprev == $r11_tbprev){
    $arr_basePRE1["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *   Cálculo dos valores da base do FGTS   * %%%*/
  // Testa se já existem os valores da base de FGTS
  if(!isset($arr_baseFGTS["$rh01_regist"])){
    $arr_baseFGTS["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R991"){
    $arr_baseFGTS["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *     Cálculo dos valores de deduções     * %%%*/
  // Testa se já existem os valores de Deduções
  if(!isset($arr_bdeducao["$rh01_regist"])){
    $arr_bdeducao["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R984"){
    $arr_bdeducao["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *     Cálculo dos valores de B OUTR       * %%%*/
  // Testa se já existem os valores de B OUTR
  if(!isset($arr_baseOUTR["$rh01_regist"])){
    $arr_baseOUTR["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R992" && $rh02_tbprev != $r11_tbprev){
    $arr_baseOUTR["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *    Cálculo dos valores de B OUTR Pat    * %%%*/
  // Testa se já existem os valores da B OUTR Patronal
  if(!isset($arr_bOUTRPat["$rh01_regist"])){
    $arr_bOUTRPat["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R990" && $rh02_tbprev != $r11_tbprev){
    $arr_bOUTRPat["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% * Cálculo dos valores da base do FGTS Mês * %%%*/
  // Testa se já existem os valores do FGTS Mês
  if(!isset($arr_bmesFGTS["$rh01_regist"])){
    $arr_bmesFGTS["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R991"){
    $arr_bmesFGTS["$rh01_regist"] += ($r14_valor*(8/100));
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *Cálculo dos valores de Previdência B OUTR* %%%*/
  // Testa se já existem os valores de Previdência B OUTR
  if(!isset($arr_basePRE2["$rh01_regist"])){
    $arr_basePRE2["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R993" && $rh02_tbprev != $r11_tbprev){
    $arr_basePRE2["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  // Testa se já existem os valores de B AC L
  if(!isset($arr_baseBACL["$rh01_regist"])){
    $arr_baseBACL["$rh01_regist"] = 0;
  }

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /* *Cálculo dos valores de Previdência / F Sintética* */
  // Testa se já existem os valores de previdência na folha sintética
  if(!isset($arr_SintPrev["$rh01_regist"])){
    $arr_SintPrev["$rh01_regist"] = 0;
  }

  if(
     $r14_rubric == "R901" || $r14_rubric == "R902" || $r14_rubric == "R903" || $r14_rubric == "R904" ||
     $r14_rubric == "R905" || $r14_rubric == "R906" || $r14_rubric == "R907" || $r14_rubric == "R908" ||
     $r14_rubric == "R909" || $r14_rubric == "R910" || $r14_rubric == "R911" || $r14_rubric == "R912"
    ){
    $arr_SintPrev["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*%%%% *Cálculo dos valores de IRRF / F Sintética* %%%*/
  // Testa se já existem os valores de IRRF na folha sintética
  if(!isset($arr_SintIrrf["$rh01_regist"])){
    $arr_SintIrrf["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R913" || $r14_rubric == "R914" || $r14_rubric == "R915"){
    $arr_SintIrrf["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
  /*% *Cálculo dos valores de Sal. Fam. / F Sintética* %*/
  // Testa se já existem os valores de salário família na folha sintética
  if(!isset($arr_SintSalF["$rh01_regist"])){
    $arr_SintSalF["$rh01_regist"] = 0;
  }

  if($r14_rubric == "R918" || $r14_rubric == "R919" || $r14_rubric == "R920"){
    $arr_SintSalF["$rh01_regist"] += $r14_valor;
  }
  /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

}


// Variável que controla a quebra de página ao mudar de lotação, local de trabalho ou órgão
$quebrarpagina = "";

$codigoquebra = "";

$imprime_cabecalho_analitico = true;

$total_sintetica_funcionario = 0;
$total_sintetica_IRRF        = 0;
$total_sintetica_previdencia = 0;
$total_sintetica_salfamilia  = 0;
$total_sintetica_proventos   = 0;
$total_sintetica_descontos   = 0;
$total_sintetica_liquido     = 0;

$total_quebra_sintetica_funcionario = 0;
$total_quebra_sintetica_IRRF        = 0;
$total_quebra_sintetica_previdencia = 0;
$total_quebra_sintetica_salfamilia  = 0;
$total_quebra_sintetica_proventos   = 0;
$total_quebra_sintetica_descontos   = 0;
$total_quebra_sintetica_liquido     = 0;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$alt   = 4;
$troca1= 1;

/// Setar altura onde passará para a próxima página
$pdf->SetAutoPageBreak('on',0);
$pdf->line(2,148.5,208,148.5);
$troca = 0;
if($ansin == "a"){
  $troca = 1;
}

$arrCamposImprime = Array(
                         "registro",
                         "nomeregi",
                         "horasmes",
                         "lotacrec",
                         "lotadescrcrec",
                         "funcarec",
                         "nascirec",
                         "situarec",
                         "admisrec",
                         "cargorec",
                         "clas1rec",
                         "padraorec",
                         "descpadr",
                         "f010rec",
                         "afastame"
                         );

// Inicia da geracao do relatorio

for($ireg=0; $ireg<$index; $ireg++){

  $registro = $arr_funcionarios["$ireg"];

  if($tipo != "g" && $tipo != "m"){

     $codigoquebra = $arr_quebras_codigo["$registro"]; // Código da lotação, do local de trabalho ou do órgão
     $descriquebra = $arr_quebras_descri["$registro"]; // Descrição da lotação, do local de trabalho ou do órgão
     $estrutquebra = $arr_quebras_estrut["$registro"]; // Estrutural da lotação, do local de trabalho ou do órgão
     $pdf->addpage();
     $troca = 0;
     $pdf->setfont('arial','b',7);
     $pdf->cell(20,$alt,$codigoquebra,"LTB",0,"C",1);
     $widthQUEBRA = 160;
     if($ansin == "a"){
       $widthQUEBRA = 172;
     }
     $pdf->cell($widthQUEBRA,$alt,$descriquebra." (".$estrutquebra.")","RTB",1,"L",1);
     $imprime_cabecalho_analitico = true;
     $quebrarpagina = $codigoquebra;
  }

  while($ireg < $index && $quebrarpagina == $codigoquebra ){

     $registro = $arr_funcionarios["$ireg"]; // Registro do funcionário

     if($tipo != "g" && $tipo != "m"){

       $codigoquebra = $arr_quebras_codigo["$registro"]; // Código da lotação, do local de trabalho ou do órgão
       if($quebrarpagina != $codigoquebra ){

         $ireg--;
         break;
       }
     }

     $nomeregi = $arr_nomesfuncion["$ireg"]; // Nome do funcionário
     $horasmes = $arr_horasfuncion["$ireg"]; // Horas mês do funcionário
     $lotacrec = $arr_lotacfuncion["$ireg"]; // Lotação do funcionário
     $lotadescrcrec = $arr_lotdescrfuncion["$ireg"]; // descrição  da lotação do funcionário
     $funcarec = $arr_descrfuncion["$ireg"]; // Função do funcionário
     $nascirec = $arr_nascifuncion["$ireg"]; // Data de nasc. do funcionário
     $situarec = $arr_situafuncion["$ireg"]; // Situação do funcionário (Ativo ou Inativo)
     $admisrec = $arr_admisfuncion["$ireg"]; // Data de admissão do funcionário
     $afastame = $arr_afastfuncion["$ireg"]; // Situação do funcionário (se afastado)
     $descpadr = $arr_dpadrfuncion["$ireg"]; // Descrição do padrão

     $clas1rec = $arr_clas1["$ireg"]; // Data de admissão do funcionário
     $cargorec = $arr_cargo["$ireg"]; // Data de admissão do funcionário
     $padraorec= $arr_padrao["$ireg"]; // Data de admissão do funcionário
     $f010rec  = $arr_f010["$ireg"]; // Data de admissão do funcionário


     /**
      * Anteriormente o caracter do explode era uma virgula, passada fixa por parametro.
      * Agora esta passando o respectivo caracter que foi concatenado os dados referentes ao regist.
      */
     $arr_auxPR = explode($virgP,$arr_rubricascodP["$registro"]); // Arrays com código das rubricas (PROVENTOS) do funcionário separadas por ","
     $arr_auxPD = explode($virgP,$arr_rubricasdesP["$registro"]); // Arrays com descrição das rubricas (PROVENTOS) do funcionário separadas por ","
     $arr_auxPQ = explode($virgP,$arr_rubricasqtdP["$registro"]); // Arrays com quantidade das rubricas (PROVENTOS) do funcionário separadas por ","
     $arr_auxPV = explode($virgP,$arr_rubricasvlrP["$registro"]); // Arrays com valor das rubricas (PROVENTOS) do funcionário separadas por ","

     $arr_auxDR = explode($virgD,$arr_rubricascodD["$registro"]); // Arrays com código das rubricas (DESCONTOS) do funcionário separadas por ","
     $arr_auxDD = explode($virgD,$arr_rubricasdesD["$registro"]); // Arrays com descrição das rubricas (DESCONTOS) do funcionário separadas por ","
     $arr_auxDQ = explode($virgD,$arr_rubricasqtdD["$registro"]); // Arrays com quantidade das rubricas (DESCONTOS) do funcionário separadas por ","
     $arr_auxDV = explode($virgD,$arr_rubricasvlrD["$registro"]); // Arrays com valor das rubricas (DESCONTOS) do funcionário separadas por ","

     $contador = 0;

     // Verifica se deve fazer quebra de página ou não
     if($ansin == "a"){
       // Verifica se tem mais PROVENTOS ou DESCONTOS para quando
       //  imprimir colocar os proventos e descontos nos locais corretos

       $contador = max(count($arr_auxPR),count($arr_auxDR));

       // Variável que testa a quantidade de linhas que os dados relativos ao funcionário corrente ocupará
       $altura_total_menos_tamanho_do_texto = $contador*$alt;

       // 9 linhas fixas que são as bases e a totalização de proventos e descontoos e os dados do funcionário + 1 que é
       // o espaço entre a totalização de proventos e descontos e a totalização das bases
       $altura_total_menos_tamanho_do_texto+= (9 * 5) + 1;

       // Soma a altura atual na página
       $altura_total_menos_tamanho_do_texto+= $pdf->gety();

       // Se o espaço ocupado mais a altura atual for maior que o tamanho da folha menos 20 mm, adicionará uma nova
       // página e voltará o FOR
       if($altura_total_menos_tamanho_do_texto > $pdf->h - 20){
         $pdf->addpage();
         continue;
       }

       if($troca == 1 || $pdf->gety() > $pdf->h - 20 ){
         //echo "<BR> 23 passou aqui !!";
         $troca = 0;
         $pdf->addpage();

       }

       // Chama função que imprime os dados do funcionário
       imprimefuncionario($r11_modanalitica, $arrCamposImprime);

       $valoresP = 0;
       $valoresD = 0;
       $valores_P = 0;
       $valores_D = 0;
       $totalliquido = 0;

       // For dos Arrays de PROVENTOS e DESCONTOS
       for($i2=0; $i2<$contador; $i2++){

         $OPC = "BP";
         if(isset($arr_auxPR["$i2"]) && trim($arr_auxPR["$i2"]) != ""){
           $OPC = "PD1";
           $valoresP += $arr_auxPV["$i2"];
         }else{
           $arr_auxPR["$i2"] = "";
           $arr_auxPQ["$i2"] = "";
           $arr_auxPV["$i2"] = "";
           $arr_auxPD["$i2"] = "";
         }

         // Chama função que imprime os dados da rubrica corrente SETANDO BP, se for em BRANCO ou PD1 se estiver setado
         imprimerubricas($OPC, $arr_auxPR["$i2"], $arr_auxPQ["$i2"], $arr_auxPV["$i2"], $arr_auxPD["$i2"]);

         $OPC = "BD";
         if(isset($arr_auxDR["$i2"]) && trim($arr_auxDR["$i2"]) != ""){
           $OPC = "PD2";
           $valoresD += $arr_auxDV["$i2"];
         }else{
           $arr_auxDR["$i2"] = "";
           $arr_auxDQ["$i2"] = "";
           $arr_auxDV["$i2"] = "";
           $arr_auxDD["$i2"] = "";
         }

         $totalliquido = $valoresP - $valoresD;
         $valores_P    = $valoresP;
         $valores_D    = $valoresD;

         // Chama função que imprime os dados da rubrica corrente SETANDO BD, se for em BRANCO ou PD2 se estiver setado
         imprimerubricas($OPC, $arr_auxDR["$i2"], $arr_auxDQ["$i2"], $arr_auxDV["$i2"], $arr_auxDD["$i2"]);

         if($pdf->gety() > $pdf->h - 30){
           $pdf->addpage();
           imprimefuncionario($r11_modanalitica, $arrCamposImprime);

         }

       }

       // Chama a função que imprime os totais de PROVENTOS, DESCONTOS e SALÁRIO LÍQUIDO
       rodapetotais($valoresP,$valoresD);

       // Chama a função que imprime as bases do funcionário corrente
       rodapebases( $arr_salabase["$registro"], $arr_baseFGTS["$registro"], $arr_bmesFGTS["$registro"], $arr_baliqIRF["$registro"],
                    $arr_depenIRF["$registro"], $arr_bdeducao["$registro"], $arr_baseINSS["$registro"], $arr_baseBACL["$registro"],
                    $arr_basePRE1["$registro"], $arr_baseOUTR["$registro"], $arr_basePRE2["$registro"], $arr_bINSSPat["$registro"],
                    $arr_bOUTRPat["$registro"], $ano, $mes, $registro );

     }else{

       // Verifica se tem mais PROVENTOS ou DESCONTOS
       $contador = max(count($arr_auxPR),count($arr_auxDR));

       $valores_P = 0;
       $valores_D = 0;

       // For dos Arrays de PROVENTOS e DESCONTOS
       for($i2=0; $i2<$contador; $i2++){
         if(isset($arr_auxPR["$i2"]) && trim($arr_auxPR["$i2"]) != ""){
           $valores_P += $arr_auxPV["$i2"];
         }

         if(isset($arr_auxDR["$i2"]) && trim($arr_auxDR["$i2"]) != ""){
           $valores_D += $arr_auxDV["$i2"];
         }

       }

       $totalliquido = $valores_P - $valores_D;

       if($pdf->gety() > $pdf->h - 30 || $imprime_cabecalho_analitico == true){
        // echo "<BR> 1 passou aqui ! if($quebrarpagina == $codigoquebra){";
         if($tipo != "g" && $tipo != "m"){
            if($quebrarpagina == $codigoquebra && $imprime_cabecalho_analitico == false){
              // echo "<BR> 2 passou aqui !";
              $pdf->addpage();
            }
         }else{
              $pdf->addpage();
         }
         $pdf->setfont('arial','b',7);
         $pdf->cell(15,$alt,"Matrícula",1,0,"C",1);
         $pdf->cell(75,$alt,"Nome",1,0,"C",1);
         $pdf->cell(15,$alt,"Previdência",1,0,"C",1);
         $pdf->cell(15,$alt,"I.R.R.F",1,0,"C",1);
         $pdf->cell(15,$alt,"Sal. Fam.",1,0,"C",1);
         $pdf->cell(15,$alt,"Proventos",1,0,"C",1);
         $pdf->cell(15,$alt,"Descontos",1,0,"C",1);
         $pdf->cell(15,$alt,"Líquido",1,1,"C",1);
         $imprime_cabecalho_analitico = false;
         $cor = 1;
       }

       $cor = ($cor == 1 ? 0 : 1);

       $pdf->setfont('arial','',7);
       $pdf->cell(15,$alt,$registro,0,0,"C",$cor);
       $pdf->cell(75,$alt,$nomeregi,0,0,"L",$cor);
       $pdf->cell(15,$alt,db_formatar($arr_SintPrev["$registro"],"f"),0,0,"R",$cor);
       $pdf->cell(15,$alt,db_formatar($arr_SintIrrf["$registro"],"f"),0,0,"R",$cor);
       $pdf->cell(15,$alt,db_formatar($arr_SintSalF["$registro"],"f"),0,0,"R",$cor);
       $pdf->cell(15,$alt,db_formatar($valores_P,"f"),0,0,"R",$cor);
       $pdf->cell(15,$alt,db_formatar($valores_D,"f"),0,0,"R",$cor);
       $pdf->cell(15,$alt,db_formatar($totalliquido,"f"),0,1,"R",$cor);
     }

     $total_sintetica_funcionario ++;
     $total_sintetica_IRRF        += $arr_SintPrev["$registro"];
     $total_sintetica_previdencia += $arr_SintIrrf["$registro"];
     $total_sintetica_salfamilia  += $arr_SintSalF["$registro"];
     $total_sintetica_proventos   += $valores_P;
     $total_sintetica_descontos   += $valores_D;
     $total_sintetica_liquido     += $totalliquido;

     $total_quebra_sintetica_funcionario ++;
     $total_quebra_sintetica_IRRF        += $arr_SintPrev["$registro"];
     $total_quebra_sintetica_previdencia += $arr_SintIrrf["$registro"];
     $total_quebra_sintetica_salfamilia  += $arr_SintSalF["$registro"];
     $total_quebra_sintetica_proventos   += $valores_P;
     $total_quebra_sintetica_descontos   += $valores_D;
     $total_quebra_sintetica_liquido     += $totalliquido;

     $ireg++;
  }
  if($tipo != "g" && $tipo != "m"){
     if($ansin == "a"){
       $pdf->ln(1);
       $pdf->setfont('arial','b',7);
       $pdf->cell(90,$alt," ",0,0,"R",0);
       $pdf->cell(15,$alt,"Previdência",0,0,"R",0);
       $pdf->cell(15,$alt,"I.R.R.F",0,0,"C",0);
       $pdf->cell(15,$alt,"Sal. Fam.",0,0,"C",0);
       $pdf->cell(15,$alt,"Proventos",0,0,"C",0);
       $pdf->cell(15,$alt,"Descontos",0,0,"C",0);
       $pdf->cell(15,$alt,"Líquido",0,1,"C",0);
     }
    $pdf->ln(1);
    $pdf->cell(90,$alt,"SUB-TOTAL LOTAÇÃO $quebrarpagina ".$total_quebra_sintetica_funcionario." FUNCIONÁRIOS","LTB",0,"L",1);
    $pdf->cell(15,$alt,db_formatar($total_quebra_sintetica_IRRF,"f"),"TB",0,"R",1);
    $pdf->cell(15,$alt,db_formatar($total_quebra_sintetica_previdencia,"f"),"TB",0,"R",1);
    $pdf->cell(15,$alt,db_formatar($total_quebra_sintetica_salfamilia,"f"),"TB",0,"R",1);
    $pdf->cell(15,$alt,db_formatar($total_quebra_sintetica_proventos,"f"),"TB",0,"R",1);
    $pdf->cell(15,$alt,db_formatar($total_quebra_sintetica_descontos,"f"),"TB",0,"R",1);
    $pdf->cell(15,$alt,db_formatar($total_quebra_sintetica_liquido,"f"),"RTB",1,"R",1);
    $total_quebra_sintetica_funcionario = 0;
    $total_quebra_sintetica_IRRF        = 0;
    $total_quebra_sintetica_previdencia = 0;
    $total_quebra_sintetica_salfamilia  = 0;
    $total_quebra_sintetica_proventos   = 0;
    $total_quebra_sintetica_descontos   = 0;
    $total_quebra_sintetica_liquido     = 0;

  }


}

if($ireg > 0 ){
  $pdf->ln(2);
  $pdf->cell(90,$alt,"TOTAL GERAL: ".$total_sintetica_funcionario." FUNCIONÁRIOS","LTB",0,"L",1);
  $pdf->cell(15,$alt,db_formatar($total_sintetica_IRRF,"f"),"TB",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($total_sintetica_previdencia,"f"),"TB",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($total_sintetica_salfamilia,"f"),"TB",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($total_sintetica_proventos,"f"),"TB",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($total_sintetica_descontos,"f"),"TB",0,"R",1);
  $pdf->cell(15,$alt,db_formatar(abs($total_sintetica_liquido),"f"),"RTB",1,"R",1);
}

$pdf->Output();
// fim do relatorio

// Função para imprimir os dados do funcionário
function imprimefuncionario($modelo, $arr_campos){

  global $alt,$i;
  global $pdf;

  foreach($arr_campos as $index => $campo){

    global $$campo;
  }

  $pdf->setfont('arial','b',7);
  if($modelo != null && $modelo > 0 && file_exists("fpdf151/impmodelos/mod_imprime_folha$modelo.php")==true){

    include("fpdf151/impmodelos/mod_imprime_folha$modelo.php");
  }else{

    $pdf->cell(12,$alt,$registro. " - " .db_CalculaDV($registro),0,0,"C",0);
    $pdf->cell(80,$alt,$nomeregi,0,0,"L",0);
    $pdf->cell(7,$alt,"HM: ",0,0,"L",0);
    $pdf->cell(10,$alt,$horasmes,0,0,"L",0);
    $pdf->cell(12,$alt,"Admiss.: ",0,0,"L",0);
    $pdf->cell(15,$alt,$admisrec,0,0,"L",0);
    $pdf->cell(42,$alt,"CodReg: ",0,0,"R",0);
    $pdf->cell(15,$alt,$clas1rec,0,1,"L",0);

    $pdf->cell(10,$alt,"Padrão: ",0,0,"L",0);
    $pdf->cell(30,$alt,$padraorec,0,1,"L",0);

    if($cargorec != ""){
      $pdf->cell(12,$alt,"Funcao: ",0,0,"L","0");
      $pdf->cell(80,$alt,$cargorec,0,0,"L",0);
    }else{
      $pdf->cell(12,$alt,"Cargo: ",0,0,"L","0");
      $pdf->cell(80,$alt,$funcarec,0,0,"L",0);
    }
    $pdf->cell(10,$alt,"Lot: ",0,0,"L","0");
    $pdf->cell(20,$alt,$lotacrec.' - '.$lotadescrcrec,0,1,"L",0);
  }

  $pdf->cell(95,$alt,"P R O V E N T O S",0,0,"C",0);
  $pdf->cell(2,$alt,"",0,0,"C",0);
  $pdf->cell(95,$alt,"D E S C O N T O S",0,1,"C",0);

  return true;
}

// Função para imprimir a rubrica corrente do FOR q a chamou
function imprimerubricas($OPC,$rubrica,$quantid,$valores,$descric){
  global $alt;
  global $pdf;

  $pdf->setfont('arial','',6);

  // Se PROVENTO for em BRANCO
  if($OPC == "BP"){

    $pdf->cell(15,$alt,"",0,0,"C",0);
    $pdf->cell(15,$alt,"",0,0,"C",0);
    $pdf->cell(50,$alt,"",0,0,"C",0);
    $pdf->cell(15,$alt,"",0,0,"C",0);
    $pdf->cell( 2,$alt,"",0,0,"C",0);
  }

  // Se DESCONTO for em BRANCO
  if($OPC == "BD"){

    $pdf->cell( 2,$alt,"",0,0,"C",0);
    $pdf->cell(15,$alt,"",0,0,"C",0);
    $pdf->cell(15,$alt,"",0,0,"C",0);
    $pdf->cell(50,$alt,"",0,0,"C",0);
    $pdf->cell(15,$alt,"",0,1,"C",0);
  }

  // Se nem provento ou nem desconto for em branco
  if($OPC == "PD1" || $OPC == "PD2"){

    $a = 0;
    if($OPC == "PD2"){
      $a = 1;
    }

    $pdf->cell(15,$alt, $rubrica,0,0,"C",0);
    $pdf->cell(15,$alt,db_formatar($quantid,"f"),0,0,"R",0);
    $pdf->cell(50,$alt,$descric,0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($valores,"f"),0,$a,"R",0);

    if($OPC == "PD1"){
      $pdf->cell( 2,$alt,"",0,0,"C",0);
    }
  }

  return true;
}

// Função que imprime os valores totais de PROVENTOS, DESCONTOS e o SALÁRIO LÍQUIDO
function rodapetotais($valorP,$valorD){

  global $alt;
  global $pdf;

  $toplinha = "T";
  $bailinha = "B";
  $corlinha = "1";

  $pdf->setfont('arial','b',6);

  $pdf->cell(80,$alt,"TOTAL DOS PROVENTOS     ..............................................................................",$toplinha,0,"R",$corlinha);
  $pdf->cell(15,$alt,db_formatar($valorP,'f'),$toplinha,0,"R",$corlinha);
  $pdf->cell(2,$alt,"",$toplinha,0,"C",$corlinha);
  $pdf->cell(80,$alt,"TOTAL DOS DESCONTOS     ..............................................................................",$toplinha,0,"R",$corlinha);
  $pdf->cell(15,$alt,db_formatar($valorD,"f"),$toplinha,1,"R",$corlinha);
  $pdf->cell(177,$alt,"SALÁRIO LÍQUIDO     ..............................................................................",$bailinha,0,"R",$corlinha);
  $pdf->cell(15,$alt,db_formatar(abs($valorP-$valorD),'f'),$bailinha,1,"R",$corlinha);

  $pdf->ln(1);

  return true;

}

// Imprime as bases
function rodapebases($salabase, $baseFGTS, $bmesFGTS, $baliqIRF, $depenIRF, $bdeducao, $baseINSS, $baseBACL, $basePRE1, $baseOUTR, $basePRE2, $bINSSPat, $bOUTRPat, $iAno = null, $iMes = null, $iRegistro = null ){

  global $alt;
  global $pdf;

  $pdf->setfont('arial','b',6);

  $pdf->cell(80,$alt,"SALÁRIO BASE       ...............................................................................","T",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($salabase,'f'),"T",0,"R",1);
  $pdf->cell(2,$alt,"","T",0,"C",1);
  $pdf->cell(32,$alt,"BASE FGTS     ..............","T",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($baseFGTS,"f"),"T",0,"R",1);
  $pdf->cell(32,$alt,"MÊS FGTS  ........................","T",0,"R",1);
  $pdf->cell(16,$alt,db_formatar($bmesFGTS,"f"),"T",1,"R",1);

  $pdf->cell(32,$alt,"BASE LIQ IRF     ",0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar(($baliqIRF<=0?0:$baliqIRF),"f"),0,0,"R",1);
  $pdf->cell(33.4,$alt,"DEPEND IRF ................... ",0,0,"R",1);
  $pdf->cell(14.6,$alt,db_formatar($depenIRF,"f"),0,0,"R",1);
  $pdf->cell(2,$alt,"",0,0,"C",1);
  $pdf->cell(32,$alt,"DEDUÇÕES     ..............",0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar($bdeducao,"f"),0,0,"R",1);
  $pdf->cell(48,$alt,"",0,1,"C",1);

  $pdf->cell(32,$alt,"BASE INSS     ",0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar($baseINSS,"f"),0,0,"R",1);
  $pdf->cell(33,$alt,"B AC L .............................",0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar($baseBACL,"f"),0,0,"R",1);
  $pdf->cell(2,$alt,"",0,0,"C",1);
  $pdf->cell(32,$alt,"PREVIDÊNCIA  ..................",0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar($basePRE1,"f"),0,0,"R",1);
  $pdf->cell(48,$alt,"",0,1,"C",1);

  $pdf->cell(32,$alt,"BASE OUTR     ","B",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($baseOUTR,"f"),"B",0,"R",1);
  $pdf->cell(33,$alt,"PREVIDÊNCIA ................","B",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($basePRE2,"f"),"B",0,"R",1);
  $pdf->cell(2,$alt,"","B",0,"C",1);
  $pdf->cell(32,$alt,"INSS Patronal  ..................","B",0,"R",1);
  $pdf->cell(15,$alt,db_formatar($bINSSPat,"f"),"B",0,"R",1);
  $pdf->cell(32,$alt,"OUTRAS PREV. Pat.  .......","B",0,"R",1);
  $pdf->cell(16,$alt,db_formatar($bOUTRPat,"f"),"B",1,"R",1);

  /**
   * Mostra afastamentos
   */
  if( $iAno <> '' && $iMes <> '' ){

    $iLinhas = 0;

    $toplinha = "T";
    $bailinha = "B";
    $corlinha = "1";

    $sSql    = "   select r45_dtafas,                                                                                  ";
    $sSql   .= "          r45_dtreto,                                                                                  ";
    $sSql   .= "          ( select r66_descr                                                                           ";
    $sSql   .= "              from codmovsefip                                                                         ";
    $sSql   .= "             where r66_anousu = r45_anousu                                                             ";
    $sSql   .= "               and r66_mesusu = r45_mesusu                                                             ";
    $sSql   .= "               and r66_codigo = r45_codafa ) as afastamento                                            ";
    $sSql   .= "     from afasta                                                                                       ";
    $sSql   .= "    where r45_anousu = $iAno                                                                           ";
    $sSql   .= "      and r45_mesusu = $iMes                                                                           ";
    $sSql   .= "      and r45_regist = $iRegistro                                                                      ";
    $sSql   .= "      and ( ( extract ( month from r45_dtafas ) = $iAno and extract ( year  from r45_dtafas ) = $iAno )";
    $sSql   .= "           or ( r45_dtreto is null or r45_dtreto >= ' $iAno-$iMes-01' )                                ";
    $sSql   .= "          )                                                                                            ";
    $sSql   .= "order by r45_dtafas desc                                                                               ";

    $rsAfastamentos = db_query( $sSql );
    $iLinhas        = pg_num_rows( $rsAfastamentos );

    if( $iLinhas > 0 ){

      for( $iLinha = 0; $iLinha < $iLinhas; $iLinha++ ){

        if( $iLinha == 0 ){

          $pdf->ln(1);
          $pdf->cell(148,$alt,"AFASTAMENTO",$toplinha,0,"L",$corlinha);
          $pdf->cell(22,$alt,"DATA INICIAL",$toplinha,0,"L",$corlinha);
          $pdf->cell(22,$alt,"DATA FINAL",$toplinha,0,"L",$corlinha);
          $pdf->ln();
        }

        $oAfastamentos = db_utils::fieldsMemory( $rsAfastamentos, $iLinha );

        $pdf->setfont('arial','b',6);
        $pdf->cell(148,$alt,$oAfastamentos->afastamento,'',0,"L",$corlinha);
        $pdf->cell(22,$alt,db_formatar($oAfastamentos->r45_dtafas,'d'),'',0,"L",$corlinha);
        $pdf->cell(22,$alt,db_formatar($oAfastamentos->r45_dtreto,'d'),'',0,"L",$corlinha);
        $pdf->ln();
      }
    }
  }
  $pdf->cell(192,0,'',1,0,"L",$corlinha);


  $pdf->ln(4);

  return true;
}