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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_extrato_classe.php"));
require_once(modification("classes/db_concilia_classe.php"));
require_once(modification("classes/db_extratolinha_classe.php"));

$clextratolinha = new cl_extratolinha;
$clconcilia     = new cl_concilia;
$objJSON         = new Services_JSON();

$sqlExtrato  = "";
$retornoJSON = '';
db_postmemory($_POST);

$sqlMenorDataCaixa  = " select min(menordatacaixa) as menordatacaixa ";
$sqlMenorDataCaixa .= "   from (  select min(k68_data) as menordatacaixa from concilia ";
$sqlMenorDataCaixa .= "        union ";
$sqlMenorDataCaixa .= "           select min(k89_data) as menordatacaixa from conciliapendcorrente ) as x ";
$rsMenorDataCaixa   = $clconcilia->sql_record($sqlMenorDataCaixa);
if ($clconcilia->numrows > 0) {
  db_fieldsmemory($rsMenorDataCaixa,0);
} else {
  $menordatacaixa = '2006-01-01';
}

$sqlExtrato = " select distinct                                                                     ";
$sqlExtrato .= "       k86_sequencial,                                                              ";
$sqlExtrato .= "       k66_bancoshistmovcategoria,                                                  ";
$sqlExtrato .= "       detalhe,                                                                     ";
$sqlExtrato .= "       documento,                                                                   ";
$sqlExtrato .= "       data,                                                                        ";
$sqlExtrato .= "       valor_credito,                                                               ";
$sqlExtrato .= "       valor_debito,                                                                ";
$sqlExtrato .= "       historico,                                                                   ";
$sqlExtrato .= "       itemconciliacao,                                                             ";
$sqlExtrato .= "       case                                                                         ";
$sqlExtrato .= "         when xx.classe = 'conciliado' then 'conciliado'                            ";
$sqlExtrato .= "         when ( ridata is not null and richeque is not null ) then 'preselecionado' ";
$sqlExtrato .= "         else xx.classe                                                             ";
$sqlExtrato .= "       end as classe,                                                               ";
$sqlExtrato .= "       justificativa                                                                ";

$sqlExtrato .= "   from (                                                                           ";

// select trazendo os pendentes
$sqlExtrato .= "   select distinct                                                                  ";
$sqlExtrato .= "          k86_sequencial,                                                           ";
$sqlExtrato .= "          0 as k66_bancoshistmovcategoria,                                          ";
$sqlExtrato .= "          'Seq :'||k86_extrato||' '||k86_observacao as detalhe,                     ";
$sqlExtrato .= "          k86_documento as documento,                                               ";
$sqlExtrato .= "          k86_data as data,                                                         ";
$sqlExtrato .= "          case when k86_tipo = 'C' or k86_tipo = ' '                                ";
$sqlExtrato .= "            then k86_valor                                                          ";
$sqlExtrato .= "            else 0                                                                  ";
$sqlExtrato .= "          end as valor_credito,                                                     ";
$sqlExtrato .= "          case when k86_tipo = 'D'                                                  ";
$sqlExtrato .= "            then k86_valor                                                          ";
$sqlExtrato .= "            else 0                                                                  ";
$sqlExtrato .= "          end as valor_debito,                                                      ";
$sqlExtrato .= "          'pendente' as classe,                                                     ";
$sqlExtrato .= "          k66_descricao as historico,                                               ";
$sqlExtrato .= "          0 as itemconciliacao,                                                     ";
$sqlExtrato .= "          k88_justificativa as justificativa                                        ";
$sqlExtrato .= "     from conciliapendextrato                                                       ";
$sqlExtrato .= "          inner join concilia      on k68_sequencial   = k88_concilia               ";
$sqlExtrato .= "          inner join extratolinha  on k88_extratolinha = k86_sequencial             ";
$sqlExtrato .= "          inner join bancoshistmov on k66_sequencial   = k86_bancohistmov           ";
$sqlExtrato .= "    where k68_sequencial = ".$concilia                                      ;

$sqlExtrato .= " union all                                                                          "; // registros conciliados
$sqlExtrato .= "   select distinct                                                                  ";
$sqlExtrato .= "          k86_sequencial,                                                           ";
$sqlExtrato .= "          0 as k66_bancoshistmovcategoria,                                          ";
$sqlExtrato .= "          'Seq :'||k86_extrato||' '||k86_observacao as detalhe,                     ";
$sqlExtrato .= "          k86_documento as documento,                                               ";
$sqlExtrato .= "          k86_data as data,                                                         ";
$sqlExtrato .= "          case when k86_tipo = 'C' or k86_tipo = ' '                                ";
$sqlExtrato .= "            then k86_valor                                                          ";
$sqlExtrato .= "            else 0                                                                  ";
$sqlExtrato .= "          end as valor_credito,                                                     ";
$sqlExtrato .= "          case when k86_tipo = 'D'                                                  ";
$sqlExtrato .= "            then k86_valor                                                          ";
$sqlExtrato .= "            else 0                                                                  ";
$sqlExtrato .= "          end as valor_debito,                                                      ";
$sqlExtrato .= "          'conciliado' as classe,                                                   ";
$sqlExtrato .= "          k66_descricao as historico,                                               ";
$sqlExtrato .= "          k83_sequencial as itemconciliacao,                                        ";
$sqlExtrato .= "          '' as justificativa                                                       ";
$sqlExtrato .= "     from conciliaextrato                                                           ";
$sqlExtrato .= "          inner join conciliaitem        on k83_sequencial   = k87_conciliaitem     ";
$sqlExtrato .= "          inner join extratolinha        on k87_extratolinha = k86_sequencial       ";
$sqlExtrato .= "          inner join bancoshistmov       on k66_sequencial   = k86_bancohistmov     ";
$sqlExtrato .= "   where k83_concilia = ".$concilia                                          ;

$sqlExtrato .= " union all                                                                          "; //registros normais
$sqlExtrato .= "   select distinct                                                                  ";
$sqlExtrato .= "          k86_sequencial,                                                           ";
$sqlExtrato .= "          k66_bancoshistmovcategoria,                                               ";
$sqlExtrato .= "          'Seq :'||k86_extrato||' '||k86_observacao as detalhe,                     ";
$sqlExtrato .= "          k86_documento as documento,                                               ";
$sqlExtrato .= "          k86_data as data,                                                         ";
$sqlExtrato .= "          case when k86_tipo = 'C' or k86_tipo = ' '                                ";
$sqlExtrato .= "            then k86_valor                                                          ";
$sqlExtrato .= "            else 0                                                                  ";
$sqlExtrato .= "          end as valor_credito,                                                     ";
$sqlExtrato .= "          case when k86_tipo = 'D'                                                  ";
$sqlExtrato .= "            then k86_valor                                                          ";
$sqlExtrato .= "            else 0                                                                  ";
$sqlExtrato .= "          end as valor_debito,                                                      ";
$sqlExtrato .= "          'normal' as classe,                                                       ";
$sqlExtrato .= "          k66_descricao as historico,                                               ";
$sqlExtrato .= "          0 as itemconciliacao,                                                     ";
$sqlExtrato .= "          '' as justificativa                                                       ";
$sqlExtrato .= "     from extratolinha                                                              ";
$sqlExtrato .= "          inner join bancoshistmov       on k66_sequencial   = k86_bancohistmov     ";
$sqlExtrato .= "          left  join conciliaextrato     on k87_extratolinha = k86_sequencial       ";
$sqlExtrato .= "          left  join conciliapendextrato on k88_extratolinha = k86_sequencial       ";
$sqlExtrato .= "   where k88_extratolinha is null                                                   ";
$sqlExtrato .= "     and k87_extratolinha is null                                                   ";
$sqlExtrato .= "     and k86_data = '{$data}'                                               ";
$sqlExtrato .= "     and k86_contabancaria = {$conta}                                       ";
$sqlExtrato .= "  ) as xx                                                                           ";

$sqlExtrato .= "          left  join ( select * from fc_extratocaixa(".db_getsession('DB_instit').",$conta,'".$menordatacaixa."','".$data."',false ) ) as x ";
$sqlExtrato .= "                                         on lpad(trim(richeque::varchar),20,'0') = lpad(trim(xx.documento),20,'0') ";
$sqlExtrato .= "                                        and (ridata = xx.data or ridata <= '".$data."') ";
$sqlExtrato .= "                                        and richeque <> 0 and xx.documento <> '0' ";
$sqlExtrato .= "                                        and k66_bancoshistmovcategoria = 101";
$sqlExtrato .= "  order by data ";

$rsExtrato   = $clextratolinha->sql_record($sqlExtrato);
$intNumrows  = $clextratolinha->numrows;

if ($intNumrows > 0 ) {

  for($i = 0; $i < $intNumrows; $i++ ) {

    db_fieldsmemory($rsExtrato,$i);
    $arrayObj[$i] = new ExtratoLinha($k86_sequencial                            ,
                                     $i                                         ,
                                     urlencode('Concolidado')                   ,
                                     urlencode($documento)                      ,
                                     urlencode($detalhe)                        ,
                                     urlencode(db_formatar($data,'d'))  ,
                                     urlencode(db_formatar($valor_debito,'f'))  ,
                                     urlencode(db_formatar($valor_credito,'f')) ,
                                     urlencode($historico)                      ,
                                     urlencode($classe)                         ,
                                     $itemconciliacao                           ,
                                     urlencode($justificativa));
  }

  echo '1|||'.json_encode($arrayObj);
} else {

  echo '2|||'.json_encode(array());
}
class ExtratoLinha {
  // Propriedades
  var $extratolinha     = '';
  var $id               = '';
  var $status           = '';
  var $numeroDocumento  = '';
  var $detalhe          = '';
  var $data             = '';
  var $valorDebito      = '';
  var $valorCredito     = '';
  var $historico        = '';
  var $classe           = '';
  var $itemconciliacao  = '';

  // Construtor
  function ExtratoLinha ( $pextratolinha    = null,
                          $pid              = null,
                          $pstatus          = null,
                          $pnumerodocumento = null,
                          $pdetalhe         = null,
                          $pdata            = null,
                          $pvalordebito     = null,
                          $pvalorcredito    = null,
                          $phistorico       = null,
                          $pclasse          = null,
                          $pitemconciliacao = null,
                          $justificativa    = null) {

    $this->extratolinha     = $pextratolinha          ;
    $this->id               = $pid                    ;
    $this->status           = $pstatus                ;
    $this->numeroDocumento  = $pnumerodocumento       ;
    $this->detalhe          = utf8_encode(str_replace("\r","",str_replace("\n","",'Observação-'.$pdetalhe)));
    $this->data             = $pdata                  ;
    $this->valorDebito      = $pvalordebito           ;
    $this->valorCredito     = $pvalorcredito          ;
    $this->historico        = $phistorico             ;
    $this->classe           = $pclasse                ;
    $this->itemconciliacao  = $pitemconciliacao       ;
    $this->justificativa    = rawurlencode($justificativa);
    $this->lendoBanco       = 'true';
  }
}

?>