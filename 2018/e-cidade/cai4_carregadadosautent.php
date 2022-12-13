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
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/JSON.php"));

include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_corrente_classe.php"));
include(modification("classes/db_concilia_classe.php"));
$clcorrente = new cl_corrente;
$clconcilia = new cl_concilia;
$objJSON    = new Services_JSON();

db_postmemory($_POST);

$sqlMenorDataCaixa  = " select min(menordatacaixa) as menordatacaixa ";
$sqlMenorDataCaixa .= "   from (  select min(k68_data) as menordatacaixa from concilia ";
$sqlMenorDataCaixa .= "	       union ";
$sqlMenorDataCaixa .= "	  	 	    select min(k89_data) as menordatacaixa from conciliapendcorrente ) as x ";
$rsMenorDataCaixa   = $clconcilia->sql_record($sqlMenorDataCaixa);
if($clconcilia->numrows > 0){
  db_fieldsmemory($rsMenorDataCaixa,0);
}else{
  $menordatacaixa = '2006-01-01';
}
$sqlAutentica  = " select caixa,                                  ";
$sqlAutentica .= "        autent,                                 ";
$sqlAutentica .= "        data,                                   ";
$sqlAutentica .= "        sum(valor_debito) as valor_debito,      ";
$sqlAutentica .= "        sum(valor_credito) as valor_credito,    ";
$sqlAutentica .= "        cheque,                                 ";
$sqlAutentica .= "        credor,                                 ";
$sqlAutentica .= "        min(detalhe) as detalhe,                ";
$sqlAutentica .= "        classe,                                 ";
$sqlAutentica .= "        itemconciliacao,                        ";
$sqlAutentica .= "        erro,                                    ";
$sqlAutentica .= "        justificativa                                    ";
$sqlAutentica .= "   from ( select distinct ";
$sqlAutentica .= "                 caixa as caixa, ";
$sqlAutentica .= "                 autent as autent, ";
$sqlAutentica .= "                 data as data, ";
$sqlAutentica .= "                 valor_debito as valor_debito, ";
$sqlAutentica .= "                 valor_credito as valor_credito, ";
$sqlAutentica .= "                 receita as receita, ";
$sqlAutentica .= "                 cheque as cheque, ";
$sqlAutentica .= "                 credor as credor, ";
$sqlAutentica .= "                 detalhe as detalhe, ";
$sqlAutentica .= "                 case ";
$sqlAutentica .= "                   when x.classe = 'conciliado' then 'conciliado' ";
$sqlAutentica .= "                   when ( k86_data is not null and k86_documento is not null ) then 'preselecionado' ";
$sqlAutentica .= "                   else x.classe ";
$sqlAutentica .= "                 end as classe, ";
$sqlAutentica .= "                 itemconciliacao, ";
$sqlAutentica .= "                 justificativa, ";
$sqlAutentica .= "                 erro as erro ";
$sqlAutentica .= "            from ( ";

// pendentes
  $sqlAutentica .= "                   select distinct ";
  $sqlAutentica .= "                          ricaixa           as caixa, ";
  $sqlAutentica .= "                          riautent          as autent, ";
  $sqlAutentica .= "                          ridata            as data, ";
  $sqlAutentica .= "                          rnvalordebito     as valor_debito, ";
  $sqlAutentica .= "                          rivalorcredito    as valor_credito, ";
  $sqlAutentica .= "                          rireceita         as receita, ";
  $sqlAutentica .= "                          richeque          as cheque, ";
  $sqlAutentica .= "                          rtcredor          as credor, ";
  $sqlAutentica .= "                          rtdetalhe         as detalhe, ";
  $sqlAutentica .= "                          k89_justificativa as justificativa, ";
  $sqlAutentica .= "                          'pendente'        as classe,";
  $sqlAutentica .= "                          0                 as itemconciliacao, ";
  $sqlAutentica .= "                          rberro            as erro ";
  $sqlAutentica .= "                     from conciliapendcorrente ";
  $sqlAutentica .= "                          inner join concilia on k68_sequencial = k89_concilia ";
  $sqlAutentica .= "                          inner join ( select * from fc_extratocaixa(" . db_getsession('DB_instit') . ",$conta,'" . $menordatacaixa . "','" . $data . "',false ) ) as x ";
  $sqlAutentica .= "                                                          on ricaixa  = k89_id ";
  $sqlAutentica .= "                                                         and riautent = k89_autent ";
  $sqlAutentica .= "                                                         and ridata   = k89_data ";
  $sqlAutentica .= "                           left join conciliacor          on ricaixa  = k84_id ";
  $sqlAutentica .= "                                                         and riautent = k84_autent ";
  $sqlAutentica .= "                                                         and ridata   = k84_data ";
  $sqlAutentica .= "                           left join conciliaitem         on k83_sequencial = k84_conciliaitem ";
  $sqlAutentica .= "                                                         and k83_concilia = (select k68_sequencial ";
  $sqlAutentica .= "                                                                               from concilia  ";
  $sqlAutentica .= "                                                                              where k68_contabancaria = {$conta} ";
  $sqlAutentica .= "                                                                                and k68_data = '" . $data . "' ) ";
  $sqlAutentica .= "                     where ( k83_sequencial is null ) ";
  $sqlAutentica .= "                       and k68_sequencial = " . $concilia;

  $sqlAutentica .= "                     union all ";

// conciliados
$sqlAutentica .= "                    select distinct ";
$sqlAutentica .= "                           ricaixa        as caixa, ";
$sqlAutentica .= "                           riautent       as autent, ";
$sqlAutentica .= "                           ridata         as data, ";
$sqlAutentica .= "                           rnvalordebito  as valor_debito, ";
$sqlAutentica .= "                           rivalorcredito as valor_credito, ";
$sqlAutentica .= "                           rireceita      as receita, ";
$sqlAutentica .= "                           richeque       as cheque, ";
$sqlAutentica .= "                           rtcredor       as credor, ";
$sqlAutentica .= "                           rtdetalhe      as detalhe, ";
$sqlAutentica .= "                           ''             as justificativa, ";
$sqlAutentica .= "                           'conciliado'   as classe, ";
$sqlAutentica .= "                           k83_sequencial as itemconciliacao, ";
$sqlAutentica .= "                           rberro         as erro ";
$sqlAutentica .= "                      from conciliacor ";
$sqlAutentica .= "                           inner join conciliaitem on k83_sequencial = k84_conciliaitem ";
$sqlAutentica .= "                           inner join concilia     on k83_concilia   = k68_sequencial ";
$sqlAutentica .= "                           inner join fc_extratocaixa(".db_getsession('DB_instit').",$conta,'".$menordatacaixa."','".$data."',false ) ";
$sqlAutentica .= "                                                   on k84_id     = ricaixa ";
$sqlAutentica .= "                                                  and k84_autent = riautent ";
$sqlAutentica .= "                                                  and k84_data   = ridata ";
$sqlAutentica .= "                     where k68_sequencial = ".$concilia;

$sqlAutentica .= "                     union all ";

// registros normais
$sqlAutentica .= "                    select distinct ";
$sqlAutentica .= "                           ricaixa        as caixa, ";
$sqlAutentica .= "                           riautent       as autent, ";
$sqlAutentica .= "	 		                     ridata         as data, ";
$sqlAutentica .= "  			                   rnvalordebito  as valor_debito, ";
$sqlAutentica .= "  			                   rivalorcredito as valor_credito, ";
$sqlAutentica .= "			                     rireceita      as receita, ";
$sqlAutentica .= "			                     richeque       as cheque, ";
$sqlAutentica .= "			                     rtcredor       as credor, ";
$sqlAutentica .= "			                     rtdetalhe      as detalhe, ";
$sqlAutentica .= "                           ''             as justificativa, ";
$sqlAutentica .= "                           'normal'       as classe, ";
$sqlAutentica .= "                           0              as itemconciliacao, ";
$sqlAutentica .= "			                     rberro         as erro ";
$sqlAutentica .= "                      from fc_extratocaixa(".db_getsession('DB_instit').",$conta,'".$data."','".$data."',false ) ";
$sqlAutentica .= "                           left join conciliacor          on ricaixa    = k84_id       ";
$sqlAutentica .= "                                                         and riautent   = k84_autent   ";
$sqlAutentica .= "                                                         and ridata     = k84_data     ";
$sqlAutentica .= "                           left join conciliaitem         on k83_sequencial = k84_conciliaitem ";
$sqlAutentica .= "                                                         and k83_concilia = (select k68_sequencial ";
$sqlAutentica .= "                                                                               from concilia  ";
$sqlAutentica .= "                                                                              where k68_contabancaria = {$conta} ";
$sqlAutentica .= "                                                                                and k68_data = '".$data."' ) ";
$sqlAutentica .= "                           left join conciliapendcorrente on k89_id     = ricaixa    ";
$sqlAutentica .= "                                                         and k89_autent = riautent   ";
$sqlAutentica .= "			                                                   and k89_data   = ridata     ";
$sqlAutentica .= "                                                         and k89_concilia = (select k68_sequencial ";
$sqlAutentica .= "                                                                               from concilia  ";
$sqlAutentica .= "                                                                              where k68_contabancaria = {$conta} ";
$sqlAutentica .= "                                                                                and k68_data = '".$data."' ) ";
//$sqlAutentica .= "	                                                       and conciliaitem.k83_concilia = conciliapendcorrente.k89_concilia ";
$sqlAutentica .= "                     where ( k89_id is null and k89_autent is null and k89_data is null ) ";
$sqlAutentica .= "                       and ( k83_sequencial is null ) ";

$sqlAutentica .= "                       and not exists (select 1  ";
$sqlAutentica .= "                                         from conciliacor ";
$sqlAutentica .= "                                         inner join conciliaitem  on k83_sequencial    = k84_conciliaitem ";
$sqlAutentica .= "                                         inner join concilia      on k68_sequencial    = k83_concilia ";
$sqlAutentica .= "                                                                and k68_contabancaria = {$conta} ";
$sqlAutentica .= "                                                                and k68_data          = '".$data."' ";
$sqlAutentica .= "                                                              where k84_id     = ricaixa ";
$sqlAutentica .= "                                                                and k84_autent = riautent ";
$sqlAutentica .= "                                                                and k84_data   = ridata ) ";

$sqlAutentica .= "                 ) as x ";
$sqlAutentica .= "                 left join extratolinha         on lpad(trim(x.cheque::varchar),20,'0') = lpad(trim(k86_documento),20,'0') ";
$sqlAutentica .= "                                               and k86_contabancaria = $conta ";
$sqlAutentica .= "                                               and (k86_data = x.data or k86_data <= '".$data."') ";
$sqlAutentica .= "                                               and x.cheque <> 0 ";
$sqlAutentica .= "                                               and k86_documento <> '0' ";
$sqlAutentica .= "        ) as x ";
$sqlAutentica .= " where not exists (select 1
                                       from corgrupocorrente
                                      where k105_autent = autent
                                        and k105_id     = caixa
                                        and k105_data   = data
                                        and k105_corgrupotipo in (2,3,5,6)
                                        and extract(year from k105_data) <= 2012 )  ";
$sqlAutentica .= "  group by caixa, autent, data, cheque, credor, classe, itemconciliacao, erro, justificativa ";
$sqlAutentica .= "  order by data, autent";
$rsAutentica   = $clcorrente->sql_record($sqlAutentica);

$intNumrows    = $clcorrente->numrows;

if ($intNumrows > 0){

  $arrayObj = array();
	for($i = 0; $i < $intNumrows; $i++ ) {

    db_fieldsmemory($rsAutentica,$i);
		$arrayObj[] = new Autenticacoes($i,
                                      'Concolidado',
                                      $cheque,
				                              str_replace("'","",$detalhe),
                                      $caixa,
                                      $autent,
                                      db_formatar($data,'d'),
                                      db_formatar($valor_debito,'f'),
                                      db_formatar($valor_credito,'f'),
                                      str_replace("'","",$credor),
                                      $classe,
                                      $itemconciliacao,
                                      $justificativa);
	}

	// Vai mostrar o codigo em JSON
  $retornoJSON = $objJSON->encode($arrayObj);
	echo '1|||'.$objJSON->encode($arrayObj);//$retornoJSON;
}else{
  echo '2|||'.$objJSON->encode(array());
}

class Autenticacoes {

  // Propriedades
  var $id               = '';
  var $status           = '';
  var $numeroCheque     = '';
  var $detalhe          = '';
  var $caixa            = '';
  var $autent           = '';
  var $data             = '';
  var $valorDebito      = '';
  var $valorCredito     = '';
  var $credor           = '';
  var $classe           = '';
  var $itemconciliacao  = '';
  var $justificativa    = '';

  // Construtor
  function Autenticacoes ($pid=null,$pstatus=null,$pnumeroCheque=null,$pdetalhe=null,$pcaixa=null,$pautent=null,$pdata=null,$pvalorDebito=null,$pvalorCredito=null,$pcredor=null,$pclasse='normal',$pitemconciliacao=null, $sJustificativa = ''){

  	$this->id               = $pid;
    $this->status           = $pstatus;
    $this->numeroCheque     = urlencode($pnumeroCheque);
    $this->detalhe          = utf8_encode(str_replace("\r","",str_replace("\n","",$pdetalhe)));
    $this->caixa            = $pcaixa;
    $this->autent           = $pautent;
    $this->data             = $pdata;
    $this->valorDebito      = $pvalorDebito;
    $this->valorCredito     = $pvalorCredito;
    $this->credor           = urlencode($pcredor);
    $this->classe           = $pclasse;
    $this->itemconciliacao  = $pitemconciliacao;
    $this->justificativa    = rawurlencode($sJustificativa);
  }
}

?>