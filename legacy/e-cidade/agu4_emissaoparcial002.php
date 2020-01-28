<?php
/*
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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));

require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("libs/db_libdocumento.php"));

require_once(modification("classes/db_aguacoletorexportadados_classe.php"));
require_once(modification("classes/db_arrecad_classe.php"));
require_once(modification("classes/db_db_layoutcampos_classe.php"));
require_once(modification("agu3_conscadastro_002_classe.php"));

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_layouttxt.php"));

require_once(modification("model/recibo.model.php"));
require_once(modification("model/regraEmissao.model.php"));
require_once(modification("model/convenio.model.php"));

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

db_postmemory($HTTP_SERVER_VARS);

$oGET = db_utils::postMemory($_GET);
$lMatriculasSemContrato = isset($oGET->matriculas_sem_contrato) && $oGET->matriculas_sem_contrato === '1' ? true : false;

if( !isset( $qtdreg ) ) { // emissao geral pode informar quantidade de registros

  $qtdreg = '';

}

if( !isset( $matricula ) ) { // emissao parcial informa matricula

  $matricula = '';

}

$claguacoletorexportadados = new cl_aguacoletorexportadados();

if ($tipo_emissao=="txt") {
  ?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.x01_matric.focus();" >

  <br>
  <br>
  <?

  $sQuebraLinha = "\r\n";
  $iCadTipoMod = 7;

} else {

  $iCadTipoMod = 8;
}

/*
 * funcao q percorre o objeto procurando a descricao do paragrafo informado e retorna o texto referente a descricao.
 * $descricao  = descricao do paragrafo. ex.: "MSG1"
 * $paragrafos = array retornado da função getDocParagrafos da classe libdocumento q contem o conteudo dos paragrafos
 * $tamanho    = numero de caracteres da string
 */
function retornaTexto($descricao, $paragrafos, $tamanho) {
  foreach($paragrafos as $oParag) {
    if($oParag->oParag->db02_descr == trim($descricao)) {
      return substr($oParag->oParag->db02_texto,0,$tamanho);
    }
  }
}

function MensagemCarne($exerc, $arretipo, $dtbase, $matric, $arrematric="w_arrematric as arrematric") {

  $mensagem = "";

  // Verifica Debitos Vencidos
  $sql  = "  SELECT arrecad.k00_tipo, ";
  $sql .= "         arretipo.k03_tipo, ";
  $sql .= "         COUNT(distinct arrecad.k00_numpar) AS qtdatraso";
  $sql .= "    FROM arrecad ";
  $sql .= "         INNER JOIN arretipo ON arretipo.k00_tipo = arrecad.k00_tipo ";
  $sql .= "   WHERE arrecad.k00_numpre in (select k00_numpre from {$arrematric}) ";
  $sql .= "     AND arrecad.k00_dtvenc < '{$dtbase}' ";
  $sql .= "GROUP BY arrecad.k00_tipo, ";
  $sql .= "         arretipo.k03_tipo";
  $sql .= " ORDER BY 3 DESC"; // ordena pela qtd parcelas em atraso em ordem descendente
  $resDebitos = db_query($sql);
  $rowsDebitos = pg_numrows($resDebitos);

  for ($iDeb = 0; $iDeb < $rowsDebitos; $iDeb++) {

    $oDebito = db_utils::fieldsmemory($resDebitos, $iDeb);

      if ($oDebito->qtdatraso >= 2) {

        // se tem duas ou mais parcelas em aberto...
        $mensagem = "AVISO DE SUSPENSÃO DO FORNECIMENTO DE ÁGUA: Fica o usuário avisado que a não regularização dos".
                    " débitos do imóvel no prazo de 30 (trinta) dias, a contar do vencimento da segunda parcela em ".
                    "atraso, acarretará na suspensão do fornecimento de água (art. 40, V, §2º da Lei n.º 11.445/07).";
        break;
      } else {

        // Verifica o CADTIPO
        switch ($oDebito->k03_tipo) {
          // Divida Ativa
          case 5:
          case 18:

            if (empty($mensagem)) {

              $mensagem = "Imovel possui Divida Ativa";
            } else {

              $mensagem .= " / Divida Ativa";
            }
            break;

            // Parcelamento
          case 6:
          case 13:

            if (empty($mensagem)) {

              $mensagem = "Imovel possui Parcelamento em Atraso";
            } else {

              $mensagem .= " / Parcelamento em Atraso";
            }
            break;

          // Saneamento Básico (Agua Exercicio)
          case 20:

            if (empty($mensagem)) {

              $mensagem = "Imovel possui Debito no Exercicio";
            } else {

              $mensagem .= " / Debito no Exercicio ";
            }
            break;

          // Outros Débitos
          default:

            if (empty($mensagem)) {

              $mensagem = "Imovel possui Outros Debitos em Atraso";
            } else {

              $mensagem .= " / Outros Debitos em Atraso ";
            }
            break;
        }
      }
    }

    if (empty($mensagem)) {
      // se nao tem nada em aberto
      $mensagem = "Obrigado pela pontualidade!!!";
    }
    //db_query("set enable_bitmapscan to off");

  return $mensagem;
}


//
// Gera SQL para Emissao Parcial ou Geral
//

function SQLEmissao($exerc, $matric = null, $qtdreg = null, $lMatriculasSemContrato = false) {

  $sFiltroMatriculasSemContrato = null;
  if ($lMatriculasSemContrato) {
    $sFiltroMatriculasSemContrato = " and not exists (select x54_sequencial from aguacontrato where x54_aguabase = x01_matric) ";
  }
  $sql = "
        select  x01_matric,
                x01_quadra,
                x01_qtdeconomia,
                trim(proprietario_nome.z01_nome) as x99_nome,
                cgm_pri.z01_cgccpf as x99_cpfcnpj,
                x01_entrega as x99_zona,
                x01_zona as x98_zona,
                case
                  when x32_codcorresp is not null then
                    x02_codrua
                  else
                    x01_codrua
                end as x99_codlogradouro,
                case
                  when x32_codcorresp is not null then
                    ruastipo2.j88_sigla
                  else
                    ruastipo.j88_sigla
                end as x99_tipologradouro,
                case
                  when x32_codcorresp is not null then
                    ruas2.j14_nome
                  else
                    ruas.j14_nome
                end as x99_logradouro,
                case
                  when x32_codcorresp is not null then
                    x02_numero
                  else
                    x01_numero
                end as x99_numero,
                case
                  when x32_codcorresp is not null then
                    case
                      when x02_orientacao is null and x02_orientacao = '' and x02_orientacao = '-' then
                        ''::char(1)
                      else
                        x02_orientacao
                    end
                  else
                    case
                      when x01_orientacao is null and x01_orientacao = '' and x02_orientacao = '-' then
                        ' '::char(1)
                      else
                        x01_orientacao
                    end
                end as x99_orientacao,
                case
                  when x32_codcorresp is not null then
                    bairro2.j13_descr
                  else
                    bairro.j13_descr
                end as x99_bairro,
                case
                  when x32_codcorresp is not null then
                    x02_complemento
                  else
                    x11_complemento
                end as x99_complemento,
                to_char(fc_agua_areaconstr(x01_matric), '999990.00') as x99_areaconstr,
                x01_codrua as x98_codlogradouro,
                ruastipo.j88_sigla as x98_tipologradouro,
                ruas.j14_nome as x98_logradouro,
                aguabase.x01_numero as x98_numero,
                case
                  when aguabase.x01_orientacao is null and aguabase.x01_orientacao = '' and aguabase.x01_orientacao = '-' then
                    ' '::char(1)
                  else
                    aguabase.x01_orientacao
                end as x98_orientacao,
                aguaconstr.x11_complemento as x98_complemento,
                bairro.j13_descr as x98_bairro,
                x32_codcorresp,
                entrega.j85_descr as denominacao,
                entrega.j85_ender as localizacao
           from aguabase
                left join aguaconstr                     on x11_matric           = x01_matric
                                                        and x11_tipo             = 'P'
                left join aguabasecorresp                on x32_matric           = x01_matric
                left join aguacorresp                    on x02_codcorresp       = x32_codcorresp
                left join ruas as ruas2                  on ruas2.j14_codigo     = x02_codrua
                left join ruastipo as ruastipo2          on ruastipo2.j88_codigo = ruas2.j14_tipo
                left join bairro as bairro2              on bairro2.j13_codi     = x02_codbairro
                left join ruas                           on ruas.j14_codigo      = x01_codrua
                left join ruastipo                       on ruastipo.j88_codigo  = ruas.j14_tipo
                left join bairro                         on bairro.j13_codi      = x01_codbairro
                left join iptucadzonaentrega as entrega  on entrega.j85_codigo   = x01_entrega
                left join proprietario_nome              on proprietario_nome.j01_matric = x01_matric
                left join cgm as cgm_pri                 on cgm_pri.z01_numcgm   = proprietario_nome.z01_cgmpri
          where fc_agua_existecaract(x01_matric, 5101) is not null
            {$sFiltroMatriculasSemContrato}
        ";

  if(!empty($matric)) {
    $sql .= " and aguabase.x01_matric = {$matric} ";
  }

  $sql .= " order by x99_zona, x99_codlogradouro, x99_orientacao, x99_numero, x99_complemento, x01_matric";

  if(is_numeric($qtdreg)) {
    //$sql .= " limit {$qtdreg} ";
  }

  return $sql;

}

if (!isset($tipo_emissao)) {
  $tipo_emissao = "pdf";
}

if ($tipo_emissao=="txt") {
  db_criatermometro('termometro', 'Concluido...', 'blue', 1);
  flush();
}

$sqlarretipo = "select fc_agua_confarretipo({$exercicio}) as x18_arretipo";
$resultarretipo = db_query($sqlarretipo);
db_fieldsmemory($resultarretipo, 0);

try{
  $oRegraEmissao = new regraEmissao($x18_arretipo,$iCadTipoMod,db_getsession('DB_instit'),date('Y-m-d',db_getsession('DB_datausu')),db_getsession('DB_ip'));
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

$pdf = $oRegraEmissao->getObj();

$lConvenioValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

$sql = SQLEmissao($exercicio, $matricula, @$qtdreg, $lMatriculasSemContrato);

$DB_DATACALC = date("Y-m-d", db_getsession("DB_datausu"));

$pdf->data_emissao = db_formatar($DB_DATACALC, "d");
$pdf->hora_emissao = db_hora();

$resultCarnes = db_query($sql);
$numrows = pg_num_rows($resultCarnes);

if($numrows == 0) {
  echo "<script>alert('Parcela(s) nao encontrada(s)');</script>";
  return false;
}

$tamanho_lote        = 2000;
$lote                = 1;
$contador            = 0;
$contador_logradouro = 0;
$logradouro          = 0;
$instit              = db_getsession("DB_instit");

// Emissao do Tipo TXT
if ($tipo_emissao=="txt") {
  $data_geracao = date("Ymd");
  $nomearqdados  = "tmp/carnes_agua_dados_$data_geracao.txt";
  $nomearqlayout = "tmp/carnes_agua_layout_$data_geracao.txt";
  $cldb_layouttxt = new db_layouttxt(11, $nomearqdados, "");
  echo "<br>Inicio:".date("H:i:s")."<br>";
}


// Cria Arrematric Temporaria para Otimizar Rotina (T15765)
$sTmpArrematric = "w_tmp_arrematric";

//$sSqlTmpArrematric  = "create temporary table {$sTmpArrematric} (k00_numpre integer, k00_matric integer, k00_perc float8) on commit drop;";
$sSqlTmpArrematric  = "create temporary table {$sTmpArrematric} (k00_numpre integer, k00_matric integer, k00_perc float8) ;";
db_query($sSqlTmpArrematric) or die("Erro criando tabela temporária com os numpres da matricula {$x01_matric}: ".pg_errormessage());;

$sSqlTmpArrematric = "create index {$sTmpArrematric}_in on {$sTmpArrematric}(k00_numpre, k00_matric) ;";

db_query($sSqlTmpArrematric) or die("Erro criando indice na tabela temporária dos numpres da matricula {$x01_matric}: ".pg_errormessage());;

$lProcessa     = false;
$iMatricInicio = 0;

for($indx=0; $indx<$numrows; $indx++) {
  db_fieldsmemory($resultCarnes, $indx);

  $txttermo = "Processando Matricula $x01_matric (" . ($indx+1) . "/$numrows) ...   ";
  db_atutermometro($indx, $numrows, "termometro", 1, $txttermo);

  // Valida Processamento
  if (!$lProcessa) {
    if ($iMatricInicio > 0) {
      if ($iMatricInicio == $x01_matric) {
        $lProcessa = true;
      } else {
        continue;
      }
    } else {
      $lProcessa = true;
    }
  }

  db_query("truncate {$sTmpArrematric}") or die("Erro limpando tabela temporária para processar matricula {$x01_matric}: ".pg_errormessage());

  $sSqlTmpArrematric  = "insert into {$sTmpArrematric} (k00_numpre, k00_matric, k00_perc) ";
  $sSqlTmpArrematric .= "select distinct ";
  $sSqlTmpArrematric .= "       arrematric.k00_numpre, ";
  $sSqlTmpArrematric .= "       arrematric.k00_matric, ";
  $sSqlTmpArrematric .= "       arrematric.k00_perc    ";
  $sSqlTmpArrematric .= "  from arrematric ";
  $sSqlTmpArrematric .= "       inner join arreinstit  on arreinstit.k00_numpre = arrematric.k00_numpre ";
  $sSqlTmpArrematric .= "                             and arreinstit.k00_instit = {$instit}";
  $sSqlTmpArrematric .= " where arrematric.k00_matric = {$x01_matric} ;";

  db_query($sSqlTmpArrematric) or die("Erro inserindo na tabela temporária dos numpres da matricula {$x01_matric}: ".pg_errormessage());

  db_query("analyze {$sTmpArrematric}") or die("Erro executando analyze na tabela temporária dos numpres da matricula {$x01_matric}: ".pg_errormessage());

  // Percorre Parcelas informadas na Interface
  for($parcela=$parcela_ini; $parcela<=$parcela_fim; $parcela++) {

    if(is_numeric($qtdreg)) {
      if($contador>=$qtdreg) {
        $parcela = $parcela_fim + 1;
        $indx    = $numrows     + 1;
        continue;
      }
    }

    if (!$matricula) {
      $sSqlACED = $claguacoletorexportadados->sql_query_dados(
        null, "x50_contaimpressa", "x50_sequencial DESC",
        "x49_anousu = $exercicio and x49_mesusu = $parcela and x49_situacao = 2 and x50_matric = $x01_matric"
      );
      $rSqlACED = $claguacoletorexportadados->sql_record($sSqlACED);

      if ($claguacoletorexportadados->numrows > 0) {

        db_fieldsmemory($rSqlACED, 0);
        if ($x50_contaimpressa == 1) {
          continue;
        }
      }
    }

    // Data de Vencimento
    $sqlvencimento = "select fc_agua_datavencimento({$exercicio}, {$parcela}, {$x01_matric}) as k00_dtvenc";
    //die($sqlvencimento);
    $resultvencimento = db_query($sqlvencimento);
    db_fieldsmemory($resultvencimento, 0);

    //db_query("truncate {$sTmpArrematric}");

    db_query("begin");

    $sArreMatric = "{$sTmpArrematric} as arrematric";

    //
    // Processa ARRECAD
    //
    $anousu   = db_getsession("DB_anousu");
    $dtvenc   = explode("-",$k00_dtvenc);
    $ano_venc = $dtvenc[0];
    $mes_venc = $dtvenc[1];

    $sSqlTaxas = <<<QUERY
      select
        arrecad.k00_receit,
        arrecad.k00_numpre,
        arrecad.k00_numpar,
        arrecad.k00_numtot,
        arrecad.k00_tipo,
        arrecad.k00_dtvenc,
        round(arrecad.k00_valor, 2) as k00_valor,
        tabrec.k02_descr,
        arrehist.k00_histtxt
      from
        {$sArreMatric}
      inner join arrecad  on arrecad.k00_numpre  = arrematric.k00_numpre
      inner join tabrec   on tabrec.k02_codigo   = arrecad.k00_receit
      left join arrehist  on arrehist.k00_numpre = arrecad.k00_numpre
                         and arrehist.k00_numpar = arrecad.k00_numpar
                         and arrecad.k00_hist in (918, 970)
      where
        arrecad.k00_tipo = {$x18_arretipo}
        and arrecad.k00_numpar = {$parcela}
        and extract(year from arrecad.k00_dtvenc) = {$ano_venc}
QUERY;

    $sSqlDescontos = <<<QUERY
      select distinct
        arrecant.k00_receit,
        arrecant.k00_numpre,
        arrecant.k00_numpar,
        arrecant.k00_numtot,
        arrecant.k00_tipo,
        arrecant.k00_dtvenc,
        round(arrecant.k00_valor, 2) as k00_valor,
        tabrec.k02_descr,
        arrehist.k00_histtxt
      from
        {$sArreMatric}
      inner join arrecant on arrecant.k00_numpre  = arrematric.k00_numpre
      inner join arrecad  on arrecant.k00_numpre  = arrecad.k00_numpre
                         and arrecant.k00_numpar  = arrecad.k00_numpar
      inner join tabrec   on tabrec.k02_codigo   = arrecant.k00_receit
      left join arrehist  on arrehist.k00_numpre = arrecant.k00_numpre
                         and arrehist.k00_numpar = arrecant.k00_numpar
                         and arrecant.k00_hist in (970, 918)
      where
        arrecant.k00_tipo = 137
        and arrecant.k00_receit = 401002
        and arrecant.k00_numpar = {$parcela}
        and extract(year from arrecant.k00_dtvenc) = {$ano_venc}
QUERY;

    $sSqlParcelamentoForo = <<<QUERY
      select
        min(arrecad.k00_receit) as k00_receit,
        arrecad.k00_numpre,
        arrecad.k00_numpar,
        arrecad.k00_numtot,
        arrecad.k00_tipo,
        arrecad.k00_dtvenc,
        round(sum(coalesce(arrecad.k00_valor, 0)), 2) as k00_valor,
        'PARCEL FORO TX' as k02_descr,
        arrehist.k00_histtxt
      from
        {$sArreMatric}
      inner join arrecad   on arrecad.k00_numpre  = arrematric.k00_numpre
      inner join arretipo  on arretipo.k00_tipo   = arrecad.k00_tipo
      left join arrehist   on arrehist.k00_numpre = arrecad.k00_numpre
                          and arrehist.k00_numpar = arrecad.k00_numpar
                          and arrecad.k00_hist in (918, 970)
      where
        arretipo.k03_tipo = 13
        and extract(year from arrecad.k00_dtvenc) = {$ano_venc}
        and extract(month from arrecad.k00_dtvenc) = {$mes_venc}
        and not exists (select arrenaoagrupa.k00_numpre
                        from arrenaoagrupa
                        where arrenaoagrupa.k00_numpre = arrecad.k00_numpre)
      group by
        arrecad.k00_numpre,
        arrecad.k00_numpar,
        arrecad.k00_numtot,
        arrecad.k00_tipo,
        arrecad.k00_dtvenc,
        arrehist.k00_histtxt
QUERY;

    $sSqlParcelamentoDivida = <<<QUERY
      select
        min(arrecad.k00_receit) as k00_receit,
        arrecad.k00_numpre,
        arrecad.k00_numpar,
        arrecad.k00_numtot,
        arrecad.k00_tipo,
        arrecad.k00_dtvenc,
        round(sum(coalesce(arrecad.k00_valor, 0)), 2) as k00_valor,
        'PARCELAM DIV TX' as k02_descr,
        arrehist.k00_histtxt
      from
        {$sArreMatric}
      inner join arrecad   on arrecad.k00_numpre  = arrematric.k00_numpre
      inner join arretipo  on arretipo.k00_tipo   = arrecad.k00_tipo
      left join arrehist   on arrehist.k00_numpre = arrecad.k00_numpre
                          and arrehist.k00_numpar = arrecad.k00_numpar
                          and arrecad.k00_hist in (918, 970)
      where
        arretipo.k03_tipo = 6
        and extract(year from arrecad.k00_dtvenc)  = {$ano_venc}
        and extract(month from arrecad.k00_dtvenc) = {$mes_venc}
        and not exists (select arrenaoagrupa.k00_numpre
                        from arrenaoagrupa
                        where arrenaoagrupa.k00_numpre = arrecad.k00_numpre)
      group by
        arrecad.k00_numpre,
        arrecad.k00_numpar,
        arrecad.k00_numtot,
        arrecad.k00_tipo,
        arrecad.k00_dtvenc,
        arrehist.k00_histtxt
QUERY;

    $sSqlOutrasReceitas = <<<QUERY
      select
        arrecad.k00_receit,
        arrecad.k00_numpre,
        arrecad.k00_numpar,
        arrecad.k00_numtot,
        arrecad.k00_tipo,
        arrecad.k00_dtvenc,
        round(arrecad.k00_valor, 2) as k00_valor,
        tabrec.k02_descr,
        arrehist.k00_histtxt
      from
        {$sArreMatric}
      inner join arrecad   on arrecad.k00_numpre  = arrematric.k00_numpre
      inner join arretipo  on arretipo.k00_tipo   = arrecad.k00_tipo
      inner join tabrec    on tabrec.k02_codigo   = arrecad.k00_receit
      left join arrehist   on arrehist.k00_numpre = arrecad.k00_numpre
                          and arrehist.k00_numpar = arrecad.k00_numpar
                          and arrecad.k00_hist in (918, 970)
      where
        (
          arrecad.k00_tipo <> {$x18_arretipo}
          and  arretipo.k03_tipo <> 6
          and  arretipo.k03_tipo <> 13
        )
        and extract(year from arrecad.k00_dtvenc)  = {$ano_venc}
        and extract(month from arrecad.k00_dtvenc) = {$mes_venc}
        and not exists (select arrenaoagrupa.k00_numpre
                        from arrenaoagrupa
                        where arrenaoagrupa.k00_numpre = arrecad.k00_numpre)
QUERY;

    $sSqlUnion = implode(" union ", array(
      $sSqlTaxas,
      $sSqlDescontos,
      $sSqlParcelamentoDivida,
      $sSqlParcelamentoForo,
      $sSqlOutrasReceitas,
    ));
    if ($lMatriculasSemContrato) {

      $sSqlUnion = implode(" union ", array(
        $sSqlParcelamentoDivida,
        $sSqlParcelamentoForo,
      ));
    }
    $sqlarrecad = "
      select
        *
      from ({$sSqlUnion}) as x
      order by k00_numpre,
               k00_receit ";

    $pdf->resultArrecad = db_query($sqlarrecad) or die("Erro selecionando registros da tabela arrecad: ".pg_errormessage());

    $numrowsArrecad = pg_num_rows($pdf->resultArrecad);

    if($numrowsArrecad == 0) {
      if(!empty($matricula)) {
        echo "<script>alert('Parcela nao encontrada');</script>";
        return false;
      } else {
        db_query("rollback");
        continue;
      }
    }

    $nValorTotal = 0;
    for($ii=0; $ii<$numrowsArrecad; $ii++) {
      $nValorTotal += pg_result($pdf->resultArrecad, $ii, "k00_valor");
    }

    if($nValorTotal <= 0) {
      if(!empty($matricula)) {
        echo "<script>alert('Valor zerado para emissão de carnê!');</script>";
        return false;
      } else {
        db_query("rollback");
        continue;
      }
    }

    $contador++;

    if($logradouro <> $x99_codlogradouro) {
      $contador_logradouro = 0;
      $logradouro = $x99_codlogradouro;
    }
    $contador_logradouro++;

    $pdf->contador_logradouro = $contador_logradouro;
    $pdf->contador            = $contador;

    $pdf->mes              = $parcela;
    $pdf->ano              = $exercicio;
    $pdf->municipio        = "Bage";
    $pdf->estado           = "RS";
    $pdf->nome_usuario     = $x99_nome;
    $pdf->cpfcnpj_usuario  = $x99_cpfcnpj;
    if ($x99_orientacao == "0" || $x99_orientacao == "-") {
      $x99_orientacao = "";
    } else {
      $x99_orientacao = $x99_orientacao.",";
    }

    if ($x98_orientacao == "0" || $x98_orientacao == "-") {
      $x98_orientacao = "";
    } else {
      $x98_orientacao = $x98_orientacao;
    }

    $pdf->endereco_entrega = "$x99_tipologradouro $x99_logradouro, Nro $x99_numero $x99_orientacao  $x99_complemento - $x99_bairro / Bage-RS";

    $entrega = "";
    $entrega = trim(trim($denominacao)." / ".trim($localizacao));

    if(!empty($entrega)) {
      $cod = str_pad($x99_zona, 4, "0", STR_PAD_LEFT);
      $pdf->zona_entrega = "ENTREGA: $cod - $entrega";
    }

    // Se existe Endereco de Entrega
    if(!empty($x32_codcorresp)) {
      $pdf->endereco_imovel = "$x98_tipologradouro $x98_logradouro, Nro $x98_numero $x98_orientacao  $x98_complemento - $x98_bairro / Bage-RS";
      $pdf->codlograd       = str_pad($x98_codlogradouro, 6, "0", STR_PAD_LEFT);
      $pdf->bairro          = $x98_bairro;
    } else {
      $pdf->endereco_imovel = "";
      $pdf->codlograd       = str_pad($x99_codlogradouro, 6, "0", STR_PAD_LEFT);
      $pdf->bairro          = $x99_bairro;
    }

    $pdf->inscricao        = "$x01_matric";
    $pdf->zona             = $x98_zona; // Zona Fiscal
    $pdf->natureza         = "AGUA e ESGOTO";
    $pdf->quarteirao       = $x01_quadra;

    $sqlcategoria = "
      select j31_descr from aguaconstr
      inner join aguaconstrcar on x12_codconstr = x11_codconstr
      inner join caracter on j31_codigo = x12_codigo and j31_grupo = 80
      where x11_matric = $x01_matric ";
    $rescategoria = db_query($sqlcategoria);

    if(pg_num_rows($rescategoria)>0) {
      $pdf->categoria = pg_result($rescategoria, 0, "j31_descr");
    } else {
      $pdf->categoria = "Terreno";
    }

    $pdf->economias        = $x01_qtdeconomia;
    $pdf->area             = $x99_areaconstr;

    // Busca Hidrometro ATIVO
    $sqlhidro = "
      select * from aguahidromatric
      left join aguahidrotroca on x28_codhidrometro = x04_codhidrometro
      where x04_matric = {$x01_matric}
      and   x28_codigo is null";

    $reshidro = db_query($sqlhidro);

    if(pg_num_rows($reshidro) > 0) {
      db_fieldsmemory($reshidro, 0);
      $pdf->hidrometro = $x04_nrohidro;
    } else {
      $pdf->hidrometro = "Sem Hidrometro";
    }

    $ano_parc = str_pad($exercicio,4,"0",STR_PAD_LEFT) . str_pad($parcela,2,"0",STR_PAD_LEFT);

    $sqlleitura  = "select x21_exerc, ";
    $sqlleitura .= "       x21_mes, ";
    $sqlleitura .= "       x17_descr, ";
    $sqlleitura .= "       x21_leitura, ";
    //$sqlleitura .= "       (x21_consumo + x21_excesso) as x21_consumo, ";
    //$sqlleitura .= "       x21_excesso, ";

    $sqlleitura .= "       case ";
    $sqlleitura .= "         when x21_excesso >= 0 then x21_consumo + x21_excesso ";
    $sqlleitura .= "         else x21_consumo ";
    $sqlleitura .= "         end as x21_consumo, ";
    $sqlleitura .= "       case ";
    $sqlleitura .= "         when x21_excesso < 0 then 0 ";
    $sqlleitura .= "         else x21_excesso ";
    $sqlleitura .= "       end as x21_excesso, ";
    $sqlleitura .= "       30::integer as x21_dias, ";
    $sqlleitura .= "       x21_dtleitura ";
    $sqlleitura .= "  from agualeitura ";
    $sqlleitura .= "       inner join aguahidromatric on x04_codhidrometro = x21_codhidrometro ";
    $sqlleitura .= "       inner join aguasitleitura on x17_codigo = x21_situacao ";
    $sqlleitura .= " where x04_matric = {$x01_matric} ";
    $sqlleitura .= "   and x21_status = 1 ";
    //$sqlleitura .= "   and trim(to_char(x21_exerc,'0000'))||trim(to_char(x21_mes,'00')) <= '{$ano_parc}' ";

    $sqlleitura .= "   and (x21_exerc, x21_mes) in (select extract(year from data)  as anousu,
                                                           extract(month from data) as mesusu
                                                      from (select cast(date '$exercicio-$parcela-01' - cast(cast(mes as text) ||cast(' month' as text) as interval) as date) as data
                                                              from generate_series(0, 7) as mes) as x)";

    $sqlleitura .= "order by x21_exerc desc, x21_mes desc ";
    $sqlleitura .= " limit 8";
    //die($sqlleitura);

    $pdf->resultLeitura = db_query($sqlleitura);

    $rowsleitura = pg_num_rows($pdf->resultLeitura);

    if($rowsleitura > 0) {
      $pdf->leitura_atual = pg_result($pdf->resultLeitura, 0, "x21_dtleitura");
      if($rowsleitura>1) {
        $pdf->leitura_ant = pg_result($pdf->resultLeitura, 1, "x21_dtleitura");
      } else {
        $pdf->leitura_ant = 0;
      }
      $pdf->consumo = pg_result($pdf->resultLeitura, 0, "x21_consumo");
    } else {
      $pdf->leitura_atual = 0;
      $pdf->leitura_ant   = 0;
      $pdf->consumo       = 0;
    }

    $pdf->campo_ano       = "x21_exerc";
    $pdf->campo_mes       = "x21_mes";
    $pdf->campo_situacao  = "x17_descr";
    $pdf->campo_leitura   = "x21_leitura";
    $pdf->campo_consumo   = "x21_consumo";
    $pdf->campo_excesso   = "x21_excesso";
    $pdf->campo_dias      = "x21_dias";
    $pdf->campo_dtleitura = "x21_dtleitura";


    if($pdf->leitura_atual==0 or $pdf->leitura_ant==0) {
      $pdf->num_dias = 0;
    } else {
      $pdf->num_dias = db_datedif($pdf->leitura_atual, $pdf->leitura_ant);
    }

    if ($pdf->num_dias==0) {
      $pdf->num_dias = 30;
    }

    $pdf->media_dia      = db_formatar($pdf->consumo/$pdf->num_dias,"p") ;

    $pdf->leitura_atual  = db_formatar($pdf->leitura_atual, "d");
    $pdf->leitura_ant    = db_formatar($pdf->leitura_ant, "d");

    $pdf->campo_receit   = "k00_receit";
    $pdf->campo_recdescr = "k02_descr";
    $pdf->campo_numpre   = "k00_numpre";
    $pdf->campo_numpar   = "k00_numpar";
    $pdf->campo_numtot   = "k00_numtot";
    $pdf->campo_valor    = "k00_valor";
    $pdf->campo_historico = "k00_histtxt";

    // Valores
    $pdf->vencimento     = db_formatar($k00_dtvenc, "d");
    $pdf->valor_total    = 0;
    $pdf->acrescimo      = 0;
    $pdf->desconto       = 0;

    $total_carne = 0;

    // Verifica se Devera Gerar Recibo se existirem Numpres Diferentes a serem emitidos
    $incluirecibo = false;

    // Numpre do Primeiro Debito
    $pdf->numpre = pg_result($pdf->resultArrecad, 0, "k00_numpre");

    // Forcar sempre incluir recibopaga
    $incluirecibo = true;

    for($y=0; $y<pg_num_rows($pdf->resultArrecad); $y++) {

      //if($pdf->numpre <> pg_result($pdf->resultArrecad, $y, "k00_numpre")) {
      //  $incluirecibo = true;
      //}

      $total_carne += pg_result($pdf->resultArrecad, $y, "k00_valor");

    }

    $sqlarretipo = "
      select k00_codbco,
             k00_codage,
             k00_descr,
             k00_hist1,
             k00_hist2,
             k00_hist3,
             k00_hist4,
             k00_hist5,
             k00_hist6,
             k00_hist7,
             k00_hist8,
             k03_tipo,
             k00_tipoagrup,
             k00_tercdigrecnormal
      from   arretipo
      where  k00_tipo   = {$x18_arretipo}
        and  k00_instit = {$instit}";
    $resultarretipo = db_query($sqlarretipo) or die($sqlarretipo);

    if(pg_numrows($resultarretipo)==0){
      echo "O código do banco não está cadastrado no arquivo arretipo para este tipo.";
      exit;
    }
    db_fieldsmemory($resultarretipo, 0);

    if(!isset($k00_tercdigrecnormal) || $k00_tercdigrecnormal == ""){
      db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
    }

    // Monta Mensagem do Carne
    //$pdf->msg1 = MensagemCarne($exercicio, $DB_DATACALC, $x01_matric);
    $pdf->msg1 = $k00_hist1;
    $pdf->msg2 = $k00_hist2;
    $pdf->msg3 = $k00_hist3;

    // Se existir parcelamento (gerar recibo se existir Numpres diferentes)
    if($incluirecibo==true) {

      $numpar = "000";
      $numpre = 0;

      try {
        $oRecibo = new recibo(2, null, 29);
      } catch ( Exception $eException ) {
        db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
        exit;
      }

      for($y=0; $y<pg_num_rows($pdf->resultArrecad); $y++) {
        db_fieldsmemory($pdf->resultArrecad, $y);

        if($numpre <> $k00_numpre) {

          $numpre = $k00_numpre;
          try {
            $oRecibo->addNumpre($k00_numpre,$k00_numpar);
          } catch ( Exception $eException ) {
            db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
            exit;
          }
        }
      }

      db_inicio_transacao();

      try {

        $oRecibo->setDataRecibo($DB_DATACALC);
        $oRecibo->setDataVencimentoRecibo($DB_DATACALC);
        $oRecibo->emiteRecibo();

        if ($lConvenioValido) {
          CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
        }

        $k03_numpre = $oRecibo->getNumpreRecibo();

        /**
         * Declaracoes de debitos para a matricula
         */
        $iNumDeclaracoes              = 0;
        $oDeclaracao                  = new stdClass();
        $oDeclaracaoQuitacaoCarneAgua = db_utils::getDao('declaracaoquitacaocarneagua');

        /**
         * query busca declaracao mais antiga para enviar no recibo/carne
         */
        $sSQLDeclaracoesDeDebitos     =  $oDeclaracaoQuitacaoCarneAgua->sql_declaracao_debito_carne( $x01_matric,
                                                                                                    "ar30_sequencial,
                                                                                                     ar30_exercicio",
                                                                                                    "ar30_exercicio asc
                                                                                                     limit 1", "" );

        $rsDeclaracoesDeDebitos       = $oDeclaracaoQuitacaoCarneAgua->sql_record( $sSQLDeclaracoesDeDebitos );

        $iNumDeclaracoes              = $oDeclaracaoQuitacaoCarneAgua->numrows;


        /**
         * se ha declaracao inseri registro de envio de declaracao em carne na tabela declaracaoquitacaocarneagua
         */
        if ( $iNumDeclaracoes > 0 ) {

          $oDeclaracao = db_utils::fieldsMemory( $rsDeclaracoesDeDebitos , 0 );

          $oDeclaracaoQuitacaoCarneAgua->ar41_declaracaoquitacao  = $oDeclaracao->ar30_sequencial;
          $oDeclaracaoQuitacaoCarneAgua->ar41_numpre              = $k03_numpre;
          $oDeclaracaoQuitacaoCarneAgua->ar41_numpar              = 1;
          $oDeclaracaoQuitacaoCarneAgua->ar41_anoemissao          = $exercicio;
          $oDeclaracaoQuitacaoCarneAgua->ar41_mesemissao          = $parcela;
          $oDeclaracaoQuitacaoCarneAgua->incluir( null );
          if ( $oDeclaracaoQuitacaoCarneAgua->erro_status == '0' ) {
            throw new Exception( $oDeclaracaoQuitacaoCarneAgua->erro_msg );
          }

        }

      } catch ( Exception $eException ) {
        db_fim_transacao(true);
        db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
        exit;
      }

      db_fim_transacao();

    } else {
      $k03_numpre = $pdf->numpre;
      // Parcela do Arrecad
      $parc = pg_result($pdf->resultArrecad, 0, "k00_numpar");
      $numpar = str_pad($parc, 3, "0", STR_PAD_LEFT);
    }

    // Monta Codigo de Barras
    $db_vlrbar = db_formatar(str_replace('.','',str_pad(number_format($total_carne,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');

    try {

      $oConvenio = new convenio($oRegraEmissao->getConvenio(), $k03_numpre, $numpar, $total_carne,
                                $db_vlrbar, $k00_dtvenc,$k00_tercdigrecnormal);

    } catch (Exception $eExeption) {

      db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
      exit;
    }

    $pdf->codigo_barras   = $oConvenio->getCodigoBarra();
    $pdf->linha_digitavel = $oConvenio->getLinhaDigitavel();
    $pdf->nosso_numero    = $oConvenio->getNossoNumero();

    $db_numpre = db_numpre($k03_numpre).$numpar; //db_formatar(0,'s',3,'e');
    $pdf->numpre = $db_numpre;

    // Mensagem para Debito em Conta
    $sqldebconta  = "select d63_codigo ";
    $sqldebconta .= "  from debcontapedido ";
    $sqldebconta .= "       inner join debcontapedidotipo   on d66_codigo = d63_codigo ";
    $sqldebconta .= "       inner join debcontapedidomatric on d68_codigo = d63_codigo ";
    $sqldebconta .= " where d68_matric   = {$x01_matric} ";
    $sqldebconta .= "   and d63_status   = 2 "; // Cadastro Debconta Ativo
    $sqldebconta .= "   and d63_instit   = {$instit} ";
    $sqldebconta .= "   and d66_arretipo = {$x18_arretipo} ";
    $sqldebconta .= " limit 1 ";

    $resultDebconta = db_query($sqldebconta);

    if(pg_numrows($resultDebconta)>0) {
      $pdf->msg_debconta01 = $k00_hist7;
      $pdf->msg_debconta02 = $k00_hist8;
    } else {
      $pdf->msg_debconta01 = "";
      $pdf->msg_debconta02 = "";
    }


    if ($tipo_emissao=="pdf") {
      $pdf->imprime();

      if(empty($matricula)) {
        //echo "$contador - $lote <br>";

        // Controla o Lote
        if( (($contador - $lote)+1) == $tamanho_lote ) {
          $data_geracao = date("Ymd");
          $ini = str_pad($lote, 5, "0", STR_PAD_LEFT);
          $fim = str_pad($contador, 5, "0", STR_PAD_LEFT);
          $arq = "tmp/CarneDaeb_".$data_geracao."_".$ini."-".$fim.".pdf" ;
          $pdf->objpdf->Output($arq);

          $lote = $contador+1;

          unset($objpdf);
          unset($pdf);

            $oRegraEmissao = new regraEmissao(null,$iCadTipoMod,db_getsession('DB_instit'),date('Y-m-d',db_getsession('DB_datausu')),db_getsession('DB_ip'));
            $pdf         = $oRegraEmissao->getObj();

          $pdf->data_emissao = db_formatar($DB_DATACALC, "d");
          //echo "Arquivo $arq gerado com sucesso...<br>";

        }
      }
    } else {
      // txt

      $oDocumento = new libdocumento(32);
      if ($oDocumento->lErro) {
        db_redireciona("db_erros.php?fechar=true&db_erro={$oDocumento->sMsgErro}.");
        exit;
      }
      unset($bMensagem);
      for($ii = 0; $ii < $numrowsArrecad; $ii++) {

        $nParcela = pg_result($pdf->resultArrecad, $ii, "k00_numpar");
        $iNumpre  = pg_result($pdf->resultArrecad, $ii, "k00_numpre");

        $sql2  = "  SELECT true as bMensagem";
        $sql2 .= "    FROM arrecad";
        $sql2 .= "         INNER JOIN termo ON termo.v07_numpre  = arrecad.k00_numpre";
        $sql2 .= "   WHERE arrecad.k00_numpre = {$iNumpre}";
        $sql2 .= "     AND termo.v07_desconto     = 22";
        $sql2 .= "     AND (termo.v07_totpar - 1) = {$nParcela}";
        $resMsgParcela  = db_query($sql2);

        if (pg_num_rows($resMsgParcela) > 0) {

          if (pg_result($resMsgParcela, 0, "bmensagem")) {

            $bMensagem = True;
            break;
          } else {

            $bMensagem = False;
          }
        }

      }

      if (@$bMensagem) {

        $mensagem_debito = "Solicitamos seu comparecimento no Setor de Cadatro e Atendimento para reparcelamento de débitos pendentes.";
      } else {

        $mensagem_debito = MensagemCarne($exercicio, $x18_arretipo, $DB_DATACALC, $x01_matric, $sArreMatric, $parcela_ini);
      }

      $oDocumento->msg_debconta01 = $pdf->msg_debconta01;  //aviso1
      $oDocumento->msg_debconta02 = $pdf->msg_debconta02;  //aviso2
      $oDocumento->msg_debitos    = $mensagem_debito;      //aviso6

      /**
       * constroi paragrafo da declaracao
       * parte 1
       */
      $sDeclaracaoQuitacao = "";
      if ( $iNumDeclaracoes > 0 ) {

        $dataInicialDeclaracao  = "01/01/".$oDeclaracao->ar30_exercicio;
        $dataFinalDeclaracao    = "31/12/".$oDeclaracao->ar30_exercicio;

        $oDocumento->coddeclaracao = $oDeclaracao->ar30_sequencial;
        $oDocumento->data_inicial  = $dataInicialDeclaracao;
        $oDocumento->data_final    = $dataFinalDeclaracao;

      }

      $paragrafos = $oDocumento->getDocParagrafos();

      /**
       * constroi paragrafo da declaracao
       * parte 1
       */
      if ( $iNumDeclaracoes > 0 ) {
        $sDeclaracaoQuitacao = retornaTexto("MSG21", $paragrafos, 145);
      }

      $cldb_layouttxt->setCampoTipoLinha(3); // 3-Registro
      $cldb_layouttxt->limpaCampos();
      $cldb_layouttxt->setCampo("vencimento",       $pdf->vencimento);
      $cldb_layouttxt->setCampo("referencia",       db_mes($pdf->mes,2)."/".db_formatar($pdf->ano,'s','0',4,'e'));
      $cldb_layouttxt->setCampo("msg1",             retornaTexto("MSG1", $paragrafos, 70)); // 70
      $cldb_layouttxt->setCampo("msg2",             retornaTexto("MSG2", $paragrafos, 70));    // 70
      $cldb_layouttxt->setCampo("proprietario",     $pdf->nome_usuario);
      $cldb_layouttxt->setCampo("endereco_entrega", $pdf->endereco_entrega);
      $cldb_layouttxt->setCampo("zona_entrega",     $pdf->zona_entrega);
      $cldb_layouttxt->setCampo("matricula",        $pdf->inscricao);
      $cldb_layouttxt->setCampo("logradouro",       $pdf->codlograd);
      $cldb_layouttxt->setCampo("categoria",        substr($pdf->categoria, 0, 8));
      $cldb_layouttxt->setCampo("zona",             $pdf->zona);
      $cldb_layouttxt->setCampo("quadra",           $pdf->quarteirao);
      $cldb_layouttxt->setCampo("economias",        $pdf->economias);
      $cldb_layouttxt->setCampo("bairro",           $pdf->bairro);
      $cldb_layouttxt->setCampo("msg3",             retornaTexto("MSG3", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg4",             retornaTexto("MSG4", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg5",             retornaTexto("MSG5", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg6",             retornaTexto("MSG6", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg7",             retornaTexto("MSG7", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg8",             retornaTexto("MSG8", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg9",             retornaTexto("MSG9", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg10",            retornaTexto("MSG10", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg11",            retornaTexto("MSG11", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg12",            retornaTexto("MSG12", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg13",            retornaTexto("MSG13", $paragrafos, 70));

      /**
       * Validação de CPF/CNPJ para informar sobre a necessidade de atualização cadastral,
       * devido a obrigatoriedade de CPF/CNPJ na cobrança registrada.
       */
      $lDocumentoValido = false;
      if (DBString::isCPF($pdf->cpfcnpj_usuario) || DBString::isCNPJ($pdf->cpfcnpj_usuario)) {
        $lDocumentoValido = true;
      }

      $sMsgAtualizacaoCadastro1 = null;
      $sMsgAtualizacaoCadastro2 = null;
      /**
       * Exibe a mensagem se o documento não é válido.
       */
      if (!$lDocumentoValido) {

        $sMsgAtualizacaoCadastro1 = retornaTexto("MSG15", $paragrafos, 70);
        $sMsgAtualizacaoCadastro2 = retornaTexto("MSG16", $paragrafos, 70);
      }

      $cldb_layouttxt->setCampo("msg14", retornaTexto("MSG14", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg15", $sMsgAtualizacaoCadastro1);
      $cldb_layouttxt->setCampo("msg16", $sMsgAtualizacaoCadastro2);
      $cldb_layouttxt->setCampo("msg17", retornaTexto("MSG17", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg18", retornaTexto("MSG18", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg19", retornaTexto("MSG19", $paragrafos, 70));
      $cldb_layouttxt->setCampo("msg20", retornaTexto("MSG20", $paragrafos, 70));

      $cldb_layouttxt->setCampo("msg21",            $sDeclaracaoQuitacao);

      $cldb_layouttxt->setCampo("dados_usuario_1",  $pdf->nome_usuario);

      if(!empty($pdf->endereco_imovel)) {
        $cldb_layouttxt->setCampo("dados_usuario_2",  $pdf->endereco_imovel);
      } else {
        $cldb_layouttxt->setCampo("dados_usuario_2",  $pdf->endereco_entrega);
      }

      $cldb_layouttxt->setCampo("dados_usuario_3",  "");
      $cldb_layouttxt->setCampo("data_emissao",     $pdf->data_emissao);

      $impcontador  = "L" . str_pad($pdf->contador_logradouro, 5, "0", STR_PAD_LEFT);
      $impcontador .= "/";
      $impcontador .= "G" . str_pad($pdf->contador, 5, "0", STR_PAD_LEFT);

      $cldb_layouttxt->setCampo("contador",         $impcontador);
      $cldb_layouttxt->setCampo("processamento",    $pdf->numpre);
      $cldb_layouttxt->setCampo("natureza",         $pdf->natureza);
      $cldb_layouttxt->setCampo("area_construida",  str_pad($pdf->area, 8, " ", STR_PAD_LEFT));

      //
      // Leituras
      //
      $numrowsLeitura = pg_num_rows($pdf->resultLeitura);
      $nleituras = min(6, $numrowsLeitura);

      for($zz=0; $zz<$nleituras; $zz++) {
        $exerc = pg_result($pdf->resultLeitura, $zz, $pdf->campo_ano);
        $parc  = pg_result($pdf->resultLeitura, $zz, $pdf->campo_mes);

        $linha  = " ".substr(db_mes($parc, 2), 0, 3);
        $linha .= "  ".str_pad(substr(pg_result($pdf->resultLeitura, $zz, $pdf->campo_situacao), 0, 10), 10, " ", STR_PAD_RIGHT);
        $linha .= "  ".str_pad(pg_result($pdf->resultLeitura, $zz, $pdf->campo_leitura), 6, " ", STR_PAD_LEFT);
        $linha .= "  ".str_pad(pg_result($pdf->resultLeitura, $zz, $pdf->campo_consumo), 4, " ", STR_PAD_LEFT);
        $linha .= "  ".str_pad(pg_result($pdf->resultLeitura, $zz, $pdf->campo_excesso), 4, " ", STR_PAD_LEFT);

        if (($zz+1)<$numrowsLeitura) {

          $dtatual    = pg_result($pdf->resultLeitura, $zz,   $pdf->campo_dtleitura);
          $dtanterior = pg_result($pdf->resultLeitura, $zz+1, $pdf->campo_dtleitura);

          if(empty($dtatual) || empty($dtanterior)) {
            $dias = 0;
          } else {
            $dias = db_datedif($dtatual, $dtanterior);
          }

          $linha .= "  ".str_pad($dias, 3, " ", STR_PAD_LEFT);
        } else {
          $linha .= "  ".str_pad(pg_result($pdf->resultLeitura, $zz, $pdf->campo_dias), 3, " ", STR_PAD_LEFT);
        }

        $cldb_layouttxt->setCampo("leitura_".($zz+1), $linha);
      }

      $cldb_layouttxt->setCampo("titulo_receita_1", "Rec   Descricao        Parcela       Valor  Numpre");

      //
      // Receitas
      //
      $nReceitas = min(8, pg_num_rows($pdf->resultArrecad));

      if ($nReceitas>4) {
        $cldb_layouttxt->setCampo("titulo_receita_2", "Rec   Descricao        Parcela       Valor  Numpre");
      } else {
        $cldb_layouttxt->setCampo("titulo_receita_2", "");
      }

      $pdf->valor_total = 0;
      $lGeraAviso7 = false;
      for($zz=0; $zz<$nReceitas; $zz++) {
        if(pg_result($pdf->resultArrecad, $zz, $pdf->campo_receit) == '401002' && pg_result($pdf->resultArrecad, $zz, $pdf->campo_valor) < 0) {
          $lGeraAviso7   = true;
          $sMsgAviso7    = pg_result($pdf->resultArrecad, $zz, $pdf->campo_historico);
          $pdf->desconto += -(pg_result($pdf->resultArrecad, $zz, $pdf->campo_valor));
        }

        $linha  = pg_result($pdf->resultArrecad, $zz, $pdf->campo_receit);
        $linha .= "  ".str_pad(pg_result($pdf->resultArrecad, $zz, $pdf->campo_recdescr), 14, " ", STR_PAD_RIGHT);

        $parc = str_pad(pg_result($pdf->resultArrecad, $zz, $pdf->campo_numpar),3,"0",STR_PAD_LEFT) . "/" .
                str_pad(pg_result($pdf->resultArrecad, $zz, $pdf->campo_numtot),3,"0",STR_PAD_LEFT) ;
        $linha .= "  ".$parc;

        $valor = str_pad(trim(db_formatar(pg_result($pdf->resultArrecad, $zz, $pdf->campo_valor),"f")), 10, "*", STR_PAD_LEFT);
        $linha .= "  ".$valor;

        $pdf->valor_total += pg_result($pdf->resultArrecad, $zz, $pdf->campo_valor);

        $numpre = str_pad(pg_result($pdf->resultArrecad, $zz, $pdf->campo_numpre), 8, "0", STR_PAD_LEFT);
        $linha .= "  ".$numpre;

        $cldb_layouttxt->setCampo("linha_receita_".($zz+1), $linha);
      }

      $pdf->valor_total = str_pad(trim(db_formatar($pdf->valor_total,"f")), 10, "*", STR_PAD_LEFT);
      $pdf->acrescimo   = str_pad(trim(db_formatar($pdf->acrescimo,"f")), 10, "*", STR_PAD_LEFT);
      $pdf->desconto    = str_pad(trim(db_formatar($pdf->desconto,"f")), 10, "*", STR_PAD_LEFT);


      $cldb_layouttxt->setCampo("hidrometro",          $pdf->hidrometro);
      $cldb_layouttxt->setCampo("dt_leitura_atual",    $pdf->leitura_atual);
      $cldb_layouttxt->setCampo("dt_leitura_anterior", $pdf->leitura_ant);
      $cldb_layouttxt->setCampo("consumo",             $pdf->consumo);
      $cldb_layouttxt->setCampo("dias_leitura",        $pdf->num_dias);
      $cldb_layouttxt->setCampo("media_diaria",        $pdf->media_dia);
      $cldb_layouttxt->setCampo("valor_acrescimo",     $pdf->acrescimo);
      $cldb_layouttxt->setCampo("valor_desconto",      $pdf->desconto);
      $cldb_layouttxt->setCampo("valor_total",         $pdf->valor_total);

      $cldb_layouttxt->setCampo("aviso1", retornaTexto("AVISO1", $paragrafos, 70));
      $cldb_layouttxt->setCampo("aviso2", retornaTexto("AVISO2", $paragrafos, 70));
      $cldb_layouttxt->setCampo("aviso3", retornaTexto("AVISO3", $paragrafos, 70));
      $cldb_layouttxt->setCampo("aviso4", retornaTexto("AVISO4", $paragrafos, 70));
      $cldb_layouttxt->setCampo("aviso5", retornaTexto("AVISO5", $paragrafos, 70));
      $cldb_layouttxt->setCampo("aviso6", retornaTexto("AVISO6", $paragrafos, 500));
      $cldb_layouttxt->setCampo("aviso7", ($lGeraAviso7 ? $sMsgAviso7 : ''));

      if (empty($pdf->msg_debconta01)) {
        $cldb_layouttxt->setCampo("linha_digitavel",     $pdf->linha_digitavel);
        $cldb_layouttxt->setCampo("codigo_barras",       $pdf->codigo_barras);
      } else {
        $cldb_layouttxt->setCampo("linha_digitavel",     "");
        $cldb_layouttxt->setCampo("codigo_barras",       "");
      }

      $cldb_layouttxt->setCampo("cpfcnpj_proprietario", $pdf->cpfcnpj_usuario);
      $cldb_layouttxt->setCampo("nosso_numero", $pdf->nosso_numero);

      $cldb_layouttxt->geraDadosLinha();
      $txttermo = "Processando Matricula $x01_matric (" . ($indx+1) . "/$numrows) ...   ";
      db_atutermometro($indx, $numrows, "termometro", 1, $txttermo);
    }

    db_query("commit");
  }
}

if ($tipo_emissao=="pdf") {

  if(!empty($matricula)) {
    $pdf->objpdf->Output("tmp/CarneAgua_{$data_geracao}_matricula_{$matricula}.pdf");
  } else {
    if( (($contador - $lote)+1) <> $tamanho_lote ) {
      $data_geracao = date("Ymd");
      $ini = str_pad($lote, 5, "0", STR_PAD_LEFT);
      $fim = str_pad($contador, 5, "0", STR_PAD_LEFT);
      $arq = "tmp/CarneAgua_".$data_geracao."_".$ini."-".$fim.".pdf" ;
      $pdf->objpdf->Output($arq);
    }
  }
} else {

  unset($cldb_layouttxt);

  $cldb_layouttxt = new db_layouttxt(12, $nomearqlayout, "");

  $cldb_layoutcampos = new cl_db_layoutcampos;

  $dbwhere = " db52_layoutlinha = 56";
  $sql = $cldb_layoutcampos->sql_query(null,"
    db52_nome,
    db52_descr,
    db52_layoutformat,
    db52_posicao as db52_posicao_inicial,
    db52_posicao - 1 + (case when db52_tamanho = 0 then db53_tamanho else db52_tamanho end) as db52_posicao_final",
    "db52_posicao",$dbwhere);

  $result = $cldb_layoutcampos->sql_record($sql);

  $cldb_layouttxt->setCampoTipoLinha(3); // 3-Registro
  for ($x=0; $x<$cldb_layoutcampos->numrows; $x++) {
    db_fieldsmemory($result, $x);
    $cldb_layouttxt->setCampo("posicao_inicial", $db52_posicao_inicial);
    $cldb_layouttxt->setCampo("posicao_final",   $db52_posicao_final);
    $cldb_layouttxt->setCampo("nome_campo",      $db52_nome);
    $cldb_layouttxt->setCampo("descricao",       $db52_descr);
    $cldb_layouttxt->geraDadosLinha();

    db_atutermometro($x, $cldb_layoutcampos->numrows, 'termometro');
  }

  $cldb_layouttxt->quebraLinha(3);
  $cldb_layouttxt->adicionaLinha("**** FILTROS UTILIZADOS ****{$sQuebraLinha}");

  $cldb_layouttxt->adicionaLinha("     Data/Hora de Emissão: {$pdf->data_emissao} - {$pdf->hora_emissao}{$sQuebraLinha}");
  $cldb_layouttxt->adicionaLinha("                Matricula: ".(is_numeric($matricula)?$matricula:"EMISSAO GERAL (todas matriculas habilitadas)")."{$sQuebraLinha}");
  $cldb_layouttxt->adicionaLinha("                      Ano: {$exercicio}{$sQuebraLinha}");
  $cldb_layouttxt->adicionaLinha("                      Mes: {$parcela}{$sQuebraLinha}");
  $cldb_layouttxt->adicionaLinha("          Tipo de Emissão: {$tipo_emissao}{$sQuebraLinha}");
  $cldb_layouttxt->adicionaLinha("Qtd registros a processar: ".(is_numeric($qtdreg)?$qtdreg:"TODOS REGISTROS")."{$sQuebraLinha}");
  $cldb_layouttxt->adicionaLinha("                  Usuário: ".db_getsession("DB_id_usuario")." - ".db_getsession("DB_login")."{$sQuebraLinha}");

  $cldb_layouttxt->fechaArquivo();

  echo "<script>";
  echo "  listagem = '$nomearqdados#Download arquivo TXT (dados dos carnes)|';";
  echo "  listagem+= '$nomearqlayout#Download arquivo TXT (layout dos carnes)';";
  echo "  parent.js_montarlista(listagem,'form1');";
  echo "</script>";

}

if ($tipo_emissao=="pdf") {
  ?>
  </body>
  </html>
  <?
} else {
  echo "<br>Fim:".date("H:i:s")."<br>";
}
