<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2016  DBSeller Servicos de Informatica
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

require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_cairetordem_classe.php"));
require_once(modification("classes/db_iptubase_classe.php"));
require_once(modification("classes/db_issbase_classe.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_db_bancos_classe.php"));

require_once(modification("model/regraEmissao.model.php"));
require_once(modification("model/convenio.model.php"));
require_once(modification("model/recibo.model.php"));

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

$historico = "";
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_SERVER);
db_postmemory($_GET);
db_postmemory($_POST);

$instit = db_getsession("DB_instit");
$oDataDia = DBDate::createFromTimestamp(db_getsession("DB_datausu"));
$cldb_bancos = new cl_db_bancos;


$sqluf = "select db21_codcli, db12_uf,db12_extenso from db_config  inner join db_uf on db12_uf=uf  where codigo = $instit";
$resultuf = db_query($sqluf);
db_fieldsmemory($resultuf, 0);

if (isset($mostramenu) && $mostramenu == 't') {
    if (isset($z01_numcgm) && $z01_numcgm != '') {
        $titulo = 'CGM';
        $origem = $z01_numcgm;
    } else {
        if (isset($j01_matric) && $j01_matric != '') {
            $titulo = 'MATRICULA';
            $origem = $j01_matric;
        } else {
            if (isset($q02_inscr) && $q02_inscr != '') {
                $titulo = 'INSCRICAO';
                $origem = $q02_inscr;
            }
        }
    }
}

db_query("begin");
//gera um nuvo numpre. "numnov"
$result = db_query("select k03_reciboprot,k03_reciboprotretencao from numpref where k03_anousu = " . db_getsession("DB_anousu") . " and k03_instit = {$instit}");
db_fieldsmemory($result, 0);

if (isset($arretipo) and $arretipo != "") {
    $k03_reciboprot = $arretipo;
}

if (!isset($lReemissao)) {
    $result = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
    db_fieldsmemory($result, 0);
} else {
    $k03_numpre = $iNumpre;
}

db_query("commit");

if (!isset($lReemissao)) {
    db_query("begin");
    //
    $dignum = db_sqlformatar($k03_numpre, 8, '0') . "001001";
    $dignum = db_CalculaDV($dignum);

    $rece = split("YY", $codrece);
    $concarpeculiar = split("YY", $codcpca);
    $codtaxa = split("YY", $codtaxa);
    $valor = split("YY", $vlrrece);
    $recurso = split("YY", $codrecu);

    if ($j01_matric != "") {
        $cliptubase = new cl_iptubase;
        $result = $cliptubase->sql_record($cliptubase->sql_query($j01_matric, 'j01_numcgm'));
        if ($cliptubase->numrows > 0) {
            db_fieldsmemory($result, 0);
            $z01_numcgm = $j01_numcgm;
        } else {
            db_redireciona("db_erros.php?fechar=true&db_erro=Matrícula " . $j01_matric . " não cadastrada.");
            $j01_matric = "";
        }
    }
    if ($p58_codproc != "") {
        $clprotprocesso = new cl_protprocesso;
        $result = $clprotprocesso->sql_record($clprotprocesso->sql_query_file($p58_codproc, 'p58_numcgm, p58_numero, p58_ano'));
        if ($clprotprocesso->numrows > 0) {
            db_fieldsmemory($result, 0);
            $z01_numcgm = $p58_numcgm;
        } else {
            db_redireciona("db_erros.php?fechar=true&db_erro=Processo " . $q02_inscr . " não cadastrado.");
            $p58_codproc = "";
        }
    }
    if ($q02_inscr != "") {
        $clissbase = new cl_issbase;

        $result = $clissbase->sql_record($clissbase->sql_query_file($q02_inscr, 'q02_inscr#q02_numcgm'));
        if ($clissbase->numrows > 0) {
            db_fieldsmemory($result, 0);
            $z01_numcgm = $q02_numcgm;
        } else {
            db_redireciona("db_erros.php?fechar=true&db_erro=Inscrição " . $q02_inscr . " não cadastrada.");
            $q02_inscr = "";
        }
    }


    if ($k32_ordpag != "") {
        $clcairetordem = new cl_cairetordem;
        $clcairetordem->k32_numpre = $k03_numpre;
        $clcairetordem->k32_ordpag = $k32_ordpag;
        $clcairetordem->incluir(null);
    }

    for ($i = 0; $i < sizeof($rece); $i++) {
        if (empty($codtaxa[$i])) {
            $xcodtaxa = 0;
        } else {
            $xcodtaxa = $codtaxa[$i];
        }

        if ($valor[$i] == 0) {
            db_redireciona("db_erros.php?fechar=true&db_erro=O Recibo Com a Receita " . $rece[$i] . " Com Valor Zerado.");
        }

        $sql = "insert into recibo (k00_numcgm,
  	              						  k00_dtoper,
  	              						  k00_receit,
  	              						  k00_hist  ,
  	              						  k00_valor ,
  	              						  k00_dtvenc,
  	              						  k00_numpre,
  	              						  k00_numpar,
  	              						  k00_numtot,
  	              						  k00_numdig,
  	              						  k00_tipo  ,
  	              						  k00_numnov,
  	              						  k00_codsubrec)
  	              		  values ($z01_numcgm,
  	              						  '{$oDataDia->getDate()}',
  	              						  $rece[$i],
  	              						  502,
  	              						  $valor[$i],
  	              						  '" . $db_datausu . "',
  	              						  $k03_numpre,
  	              						  1,
  	              						  1,
  	              						  $dignum,
  	              						  $k03_reciboprot,
  	              						  0,
  	              						  $xcodtaxa)";
        $result = db_query($sql);
    }

    for ($iInd = 0; $iInd < sizeof($concarpeculiar); $iInd++) {

        /**
         * verifica se existe a CP cadastrada para o numpre/numpar/receita.
         * o problema apenas ocorre quando o recibo possui mais de uma taxa com a mesma receita.
         */
        $oDaoReciboConCarPeculiar = db_utils::getDao("reciboconcarpeculiar");
        $sWhere = " k130_numpre = {$k03_numpre} and k130_numpar = 1 and  k130_receit = {$rece[$iInd]}";
        $sSqlCaracteristicaPeculiar = $oDaoReciboConCarPeculiar->sql_query_file(null, "*", null, $sWhere);
        $rsCaracteriscaPeculiar = $oDaoReciboConCarPeculiar->sql_record($sSqlCaracteristicaPeculiar);

        if ($oDaoReciboConCarPeculiar->numrows == 0) {
            $oDaoReciboConCarPeculiar->k130_numpre = $k03_numpre;
            $oDaoReciboConCarPeculiar->k130_numpar = 1;
            $oDaoReciboConCarPeculiar->k130_receit = $rece[$iInd];
            $oDaoReciboConCarPeculiar->k130_concarpeculiar = $concarpeculiar[$iInd];
            $oDaoReciboConCarPeculiar->incluir(null);
            if ($oDaoReciboConCarPeculiar->erro_status == 0) {
                db_redireciona("db_erros.php?fechar=true&db_erro=Não foi cadastrar a característica peculiar!");
            }
        }
    }

    // grava o recurso do recibo
    $sql = "insert into reciborecurso( k00_sequen,
  						              	  		 k00_numpre,
  						              	  		 k00_recurso)
  				  	              values ( nextval('reciborecurso_k00_sequen_seq'),
  						                       $k03_numpre,
  							             		     $recurso[0])";
    $result = db_query($sql);

    if ($p58_codproc != "") {
        $sql = "insert into arreproc values ($k03_numpre,$p58_codproc)";
        $result = db_query($sql);
    }

    if ($j01_matric != "") {
        $sql = "insert into arrematric values ($k03_numpre,$j01_matric)";
        $result = db_query($sql);
    }
    if ($q02_inscr != "") {
        $sql = "insert into arreinscr  values ($k03_numpre,$q02_inscr)";
        $result = db_query($sql);
    }

    $sql = "select * from arrenumcgm where k00_numcgm = $z01_numcgm and k00_numpre = $k03_numpre";
    $result = db_query($sql);

    if (pg_numrows($result) == 0) {
        $sql = "insert into arrenumcgm (k00_numcgm, k00_numpre) values ($z01_numcgm,$k03_numpre)";
        $result = db_query($sql);
    }

    $sql = "insert into arrehist ( k00_numpre,
                                 k00_numpar,
				  			                 k00_hist,
				  			                 k00_dtoper,
				  			                 k00_hora,
				  			                 k00_id_usuario,
				  			                 k00_histtxt,
				  			                 k00_limithist,
				  			                 k00_idhist
				  			               ) values (
  		                          			 $k03_numpre,
				  			                 0,
				  			                 502,
				  			                 '" . date("Y-m-d", db_getsession("DB_datausu")) . "',
				  			                 '" . date("H:i") . "',
				  			                 " . db_getsession("DB_id_usuario") . ",
				  			                 '$historico',
				  			                 null,
				  			                 nextval('arrehist_k00_idhist_seq')
				  			               )";
    $result = db_query($sql);

    db_query("commit");
} else {
    $historico = "";
    $sSqlHistorico = "select * from arrehist where k00_numpre = {$k03_numpre}";
    $rsHistorico = db_query($sSqlHistorico);
    if ($rsHistorico) {
        $historico .= pg_result($rsHistorico, 0, "k00_histtxt");
    }

    $oDaoArrepaga = db_utils::getDao("arrepaga");
    $sSqlPagamento = $oDaoArrepaga->sql_query_file(null, "k00_numpre", null, "k00_numpre = {$iNumpre}");
    $rsPagamento = $oDaoArrepaga->sql_record($sSqlPagamento);
    if ($oDaoArrepaga->numrows > 0) {
        $historico .= "\nRecibo já pago. Não Receber";
    }
}

$k00_histtxt = $historico;

$result = db_query("select k00_msgrecibo, k00_codbco,k00_codage,k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,k00_hist6,k00_hist7,k00_hist8 from arretipo where k00_tipo = 11");
db_fieldsmemory($result, 0);

global $tipo, $k03_numpre, $emite_recibo_protocolo, $k00_histtxt;
$tipo = 11;
$emite_recibo_protocolo = true;

if (!isset($lGerarOutput)) {
    include_once(modification("fpdf151/recibopdf.php"));
}
require_once(modification("libs/db_barras.php"));

$matricularecibo = @$j01_matric;
$inscricaorecibo = @$q02_inscr;
$numcgmrecibo = @$z01_numcgm;
$numprot = @$p58_codproc;
$numprotimp = @$p58_numero . "/" . @$p58_ano;
$tipoidentificacao = 0;

if (isset($db_datausu)) {
    if (!checkdate(substr($db_datausu, 5, 2), substr($db_datausu, 8, 2), substr($db_datausu, 0, 4))) {
        echo "Data para Cálculo Inválida. <br><br>";
        echo "Data deverá se superior a : " . date('Y-m-d', db_getsession("DB_datausu"));
        exit;
    }
    if (mktime(0, 0, 0, substr($db_datausu, 5, 2), substr($db_datausu, 8, 2), substr($db_datausu, 0, 4)) <
      mktime(0, 0, 0, date('m', db_getsession("DB_datausu")), date('d', db_getsession("DB_datausu")),
        date('Y', db_getsession("DB_datausu")))
    ) {
        echo "Data não permitida para cálculo. <br><br>";
        echo "Data deverá se superior a : " . date('Y-m-d', db_getsession("DB_datausu"));
        exit;
    }
    $DB_DATACALC = mktime(0, 0, 0, substr($db_datausu, 5, 2), substr($db_datausu, 8, 2), substr($db_datausu, 0, 4));
} else {
    $DB_DATACALC = db_getsession("DB_datausu");
}

/*
 * logica para verificar se o numpre está na empprestarecibo
* que seria um recibo de prestação de conta.
*/
$oDaoEmpPrestaRecibo = db_utils::getDao("empprestarecibo");
$sSqlEmpPrestaRecibo = $oDaoEmpPrestaRecibo->sql_query_fileRecibo(null, "*", null, "e170_numpre = {$k03_numpre}");
$rsEmpPrestaRecibo   = $oDaoEmpPrestaRecibo->sql_record($sSqlEmpPrestaRecibo);

// caso venha registro, verificamos a data de vencimento
if ($oDaoEmpPrestaRecibo->numrows > 0) {
    $oDadosEmpPrestaRecibo = db_utils::fieldsMemory($rsEmpPrestaRecibo, 0);

    $iDataVencimento = strtotime($oDadosEmpPrestaRecibo->k00_dtvenc);
    $iDataAtual = db_getsession("DB_datausu");
    $lDevolucaoAdiantamento = 't';
    $lBarra = 't';
    /*
     * se a data do vencimento for menor que a atual
     * atualizamos a recibo dtvenc para a nova data de vencimento
     */
    if ($iDataVencimento < $iDataAtual) {
        $dtVencimento = date("Y-m-d", $iDataAtual);
        $sSqlAtualizaDataVenc = " Update recibo set k00_dtvenc = '{$dtVencimento}' ";
        $sSqlAtualizaDataVenc .= " where k00_numpre = {$k03_numpre} ";
        $sSqlAtualizaDataVenc .= "   and k00_numpar = {$oDadosEmpPrestaRecibo->k00_numpar} ";

        db_query($sSqlAtualizaDataVenc);
    }
}

$k00_descr = $k00_histtxt . "\n" . $k00_msgrecibo;

$sql = "select r.k00_numcgm,
                r.k00_dtvenc,
                r.k00_receit,
                upper(t.k02_descr) as k02_descr,
                upper(t.k02_drecei) as k02_drecei,
                r.k00_dtoper as k00_dtoper,
                k00_codsubrec,
                coalesce(upper(k07_descr),' ') as k07_descr ,
                sum(r.k00_valor) as valor,
                case
                   when taborc.k02_codigo is null
                     then tabplan.k02_reduz
                   else
                     taborc.k02_codrec
                end as codreduz,
                k00_hist,
                (select (select k02_codigo from tabrec where k02_recjur = k00_receit or k02_recmul = k00_receit limit 1) is not null ) as codtipo
           from recibo r
                inner join tabrec t 		 on t.k02_codigo       = r.k00_receit
                inner join tabrecjm 		 on tabrecjm.k02_codjm = t.k02_codjm
		             left outer join tabdesc on codsubrec          = k00_codsubrec
                                        and k07_instit         = $instit
                 left outer join taborc  on t.k02_codigo       = taborc.k02_codigo
                                        and taborc.k02_anousu  = " . db_getsession("DB_anousu") . "
                 left outer join tabplan on t.k02_codigo       = tabplan.k02_codigo
                                        and tabplan.k02_anousu = " . db_getsession("DB_anousu") . "
           where r.k00_numpre = " . $k03_numpre . "
           group by r.k00_dtoper,r.k00_dtvenc,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_numcgm,k00_codsubrec,k07_descr,codreduz,r.k00_hist";

$DadosPagamento   = db_query($sql);
$sCampoVencimento = "k00_dtvenc";

//faz um somatorio do valor

$datavencimento = pg_result($DadosPagamento, 0, $sCampoVencimento);
$total_recibo = 0;
for ($i = 0; $i < pg_numrows($DadosPagamento); $i++) {
    db_fieldsmemory($DadosPagamento, $i);
    $total_recibo += $valor;
    $arraycodreceitas[$i] = $k00_receit;
    $arrayreduzreceitas[$i] = $codreduz;
    $arraydescrreceitas[$i] = $k02_descr;
    $arrayvalreceitas[$i] = $valor;
}

//seleciona da tabela db_config, o numero do banco e a taxa bancaria e concatena em variavel
$DadosInstit = db_query("select cgc, nomeinst,ender,munic,email,telef,uf,logo,to_char(tx_banc,'9.99') as tx_banc,numbanco from db_config where codigo = " . db_getsession("DB_instit"));
$sSqlArretipo = "select to_char(k00_txban,'99.99') as tx_banc from arretipo where k00_instit = " . db_getsession("DB_instit") . " and k00_tipo = {$k03_reciboprot} ";
$sqlArretipo_tx_banc = db_query($sSqlArretipo);

//cria codigo de barras e linha digitável
//$taxabancaria = pg_result($DadosInstit,0,"tx_banc");
$taxabancaria = pg_result($sqlArretipo_tx_banc, 0, "tx_banc");
$src = pg_result($DadosInstit, 0, 'logo');
$db_nomeinst = pg_result($DadosInstit, 0, 'nomeinst');
$db_ender = pg_result($DadosInstit, 0, 'ender');
$db_munic = pg_result($DadosInstit, 0, 'munic');
$db_uf = pg_result($DadosInstit, 0, 'uf');
$db_telef = pg_result($DadosInstit, 0, 'telef');
$db_email = pg_result($DadosInstit, 0, 'email');
$cgc = pg_result($DadosInstit, 0, 'cgc');

$total_recibo += $taxabancaria;
$valor_parm = $total_recibo;


//seleciona dados de identificacao. Verifica se é inscr ou matric e da o respectivo select
//essa variavel vem do cai3_gerfinanc002.php, pelo window open, criada por parse_str

if (!empty($_POST["ver_matric"]) || $matricularecibo > 0) {
    $numero = @$_POST["ver_matric"] + $matricularecibo;
    $tipoidentificacao = "Matricula :";

    $sSqlMatricula = "select z01_cgccpf,
                           z01_nome,
                           z01_ender,
                           z01_bairro,
                           z01_numero,
                           z01_compl,
                           z01_munic,
                           z01_uf,
                           z01_cep,
                           '' as z01_numcgm,
                           '' as z01_bairro,
                           nomepri,
                           z01_ender as nomepriimo,
                           j39_compl,
                           j39_numero,
                           j13_descr,
                           j34_setor||'.'||j34_quadra||'.'||j34_lote as sql
                      from proprietario
                   	 where j01_matric = $numero limit 1";

    $Identificacao = db_query($sSqlMatricula);
    db_fieldsmemory($Identificacao, 0);

    $cgmcerto = $z01_cgccpf;
} else {
    if (!empty($_POST["ver_inscr"]) || $inscricaorecibo > 0) {
        $numero = @$_POST["ver_inscr"] + $inscricaorecibo;
        $tipoidentificacao = "Inscricao :";

        $sSqlInscr = "select z01_nome,
                       z01_ender,
                       z01_numero,
                       z01_compl,
                       z01_munic,
                       z01_uf,
                       z01_cep,
                       z01_cgccpf,
                       z01_ender as nomepri,
                       z01_ender as nomepriimo,
                       z01_compl as j39_compl,
                       z01_numero as j39_numero,
                       z01_bairro as j13_descr,
                       '' as z01_numcgm,
                       '' as z01_bairro,
                       '' as sql
                  from empresa
                 where q02_inscr = $numero";
        $Identificacao = db_query($sSqlInscr);
        db_fieldsmemory($Identificacao, 0);

        $cgmcerto = $z01_cgccpf;
    } else {
        if (!empty($_POST["ver_numcgm"]) || $numcgmrecibo > 0) {
            $numero = @$_POST["ver_numcgm"] + $numcgmrecibo;
            $tipoidentificacao = "Numcgm :";

            if ($numprot > 0) {
                $tipoidentificacao1 = "Protocolo :";
                $res_proc = db_query("select p58_requer,p51_descr from protprocesso inner join tipoproc on p58_codigo = p51_codigo where p58_codproc = $numprot");
                db_fieldsmemory($res_proc, 0);
            }

            $sSqlCgm = "select z01_cgccpf,
                     z01_nome,
                     z01_ender,
                     z01_numero,
                     z01_compl,
                     z01_munic,
                     z01_uf,
                     z01_bairro,
                     z01_cep,
                             z01_ender as nomepri,
                     z01_ender as nomepriimo,
                     z01_compl as j39_compl,
                     z01_numero as j39_numero,
                     z01_bairro as j13_descr,
                     '' as z01_numcgm,
                     '' as sql
                from cgm
                where z01_numcgm = $numero ";
            $Identificacao = db_query($sSqlCgm);
            db_fieldsmemory($Identificacao, 0);
            $cgmcerto = $z01_cgccpf;
        } else {
            $tipoidentificacao = "";
            if (isset($emite_recibo_protocolo)) {
                $Identificacao = db_query("
            select c.z01_cgccpf,
                   c.z01_nome,
                   c.z01_numcgm,
                   c.z01_ender,
                   c.z01_numero,
                   c.z01_compl,
                   c.z01_bairro,
                   c.z01_munic,
                   c.z01_uf,
                   c.z01_cep,
                   c.z01_ender as nomepri,
                   c.z01_compl as j39_compl,
                   c.z01_numero as j39_numero,
                   c.z01_bairro as j13_descr,
                   '' as sql
            from recibo r
                 inner join cgm c on c.z01_numcgm = r.k00_numcgm
   		    where r.k00_numpre = " . $k03_numpre . "
            limit 1");

                db_fieldsmemory($Identificacao, 0);
                $cgmcerto = $z01_cgccpf;
            }
        }
    }
}


if (isset($tipo_debito)) {
    if ($tipo == 5 || $tipo == 17) {
        $histparcela = "Divida: ";
        $sqlhist = "select distinct v01_exerc,v01_numpar
	        from db_reciboweb
			     left outer join divida on v01_numpre = k99_numpre and v01_numpar = k99_numpar
	        where k99_numpre_n = $k03_numpre
			group by v01_exerc,v01_numpar
			order by v01_exerc,v01_numpar";
        $result = db_query($sqlhist);
        if (pg_numrows($result) != false) {
            $exercv = "0000";
            for ($xy = 0; $xy < pg_numrows($result); $xy++) {
                if ($exercv != pg_result($result, $xy, 0)) {
                    $exercv = pg_result($result, $xy, 0);
                    $histparcela .= pg_result($result, $xy, 0) . ":";
                }
                $histparcela .= pg_result($result, $xy, 1) . "-";
            }
        }
    } else {
        if ($tipo_debito == 3 || $tipo_debito == 2) {
            $histparcela = "Exercicio: ";
            $sqlhist = "select distinct q05_ano,q05_numpar
	        from db_reciboweb
			     left outer join issvar on q05_numpre = k99_numpre and q05_numpar = k99_numpar
	        where k99_numpre_n = $k03_numpre
			group by q05_ano,q05_numpar
			order by q05_ano,q05_numpar";
            $result = db_query($sqlhist);
            if (pg_numrows($result) != false) {
                $exercv = "0000";
                for ($xy = 0; $xy < pg_numrows($result); $xy++) {
                    if ($exercv != pg_result($result, $xy, 0)) {
                        $exercv = pg_result($result, $xy, 0);
                        $histparcela .= "  " . pg_result($result, $xy, 0) . ": Parc:";
                    }
                    $histparcela .= "-" . pg_result($result, $xy, 1);
                }
            }
        } else {
            if ($tipo_debito == 6 || $tipo_debito == 1) {
                $histparcela = '';
                $parcelamento = '';
                $sqlhist = "select v07_parcel,k99_numpar
	        from db_reciboweb
			     left outer join termo on v07_numpre = k99_numpre
	        where k99_numpre_n = $k03_numpre
			order by v07_parcel,k99_numpar";
                $result = db_query($sqlhist);
                if (pg_numrows($result) != false) {
                    for ($xy = 0; $xy < pg_numrows($result); $xy++) {
                        if (pg_result($result, $xy, 0) != $parcelamento) {
                            $histparcela .= ' Parc : ' . pg_result($result, $xy, 0) . " - ";
                        }
                        $histparcela .= pg_result($result, $xy, 1) . " ";
                        $parcelamento = pg_result($result, $xy, 0);
                    }
                }
            } else {
                $histparcela = "PARCELAS: ";
                $sqlhist = "select k99_numpar
	        from db_reciboweb
	        where k99_numpre_n = $k03_numpre order by k99_numpar";
                $result = db_query($sqlhist);
                for ($xy = 0; $xy < pg_numrows($result); $xy++) {
                    $histparcela .= pg_result($result, $xy, 0) . " ";
                }
            }
        }
    }
}
/********************************************************************************************************************************************/
if (isset($lReemissao)) {
    $iTipoDebito = $k03_reciboprotretencao;
    $iTipoMod = 15;
} else {
    $iTipoDebito = $k03_reciboprot;
    $iTipoMod = 14;
}

if (isset($lGerarOutput)) {
    /**
     * Variavel é pdf é setada por fora
     */
    $pdf = $pdf;
    $lNovoPdf = false;
} else {
    $pdf = null;
    $lNovoPdf = true;
}

try {
    $oRegraEmissao = new regraEmissao(
      $iTipoDebito,
      $iTipoMod,
      db_getsession('DB_instit'),
      date("Y-m-d", db_getsession("DB_datausu")),
      db_getsession('DB_ip'),
      $lNovoPdf,
      $pdf
    );
} catch (Exception $eExeption) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
}

$oRecibo = new recibo(1);
$oRecibo->setNumnov($k03_numpre);
$lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

if ($lConvenioCobrancaValido && !CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
    CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
}

$pdf1 = $oRegraEmissao->getObjPdf();

/********************************************************************************************************************************************/
//select pras observacoes
$Observacoes = db_query($conn, "select mens,alinhamento from db_confmensagem where cod in('obsboleto1','obsboleto2','obsboleto3','obsboleto4')");
$db_vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($total_recibo, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

try {
    $oConvenio = new convenio($oRegraEmissao->getConvenio(), $k03_numpre, 0, $total_recibo, $db_vlrbar, $datavencimento, 6);
} catch (Exception $eExeption) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
}

/**
 * Faz comunicacão com webservice para validação dos dados do Recibo avulsos
 */
try {
    if ($lConvenioCobrancaValido && CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
        CobrancaRegistrada::registrarReciboWebservice($k03_numpre, $oRegraEmissao->getConvenio(), $total_recibo);
    }
} catch (Exception $oErro) {
    echo $oErro->getMessage();
    exit;
}

$codigobarras = $oConvenio->getCodigoBarra();
$linhadigitavel = $oConvenio->getLinhaDigitavel();
$datavencimento = db_formatar($datavencimento, "d");

if ($oRegraEmissao->isCobranca()) {
    $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
    $pdf1->carteira = $oConvenio->getCarteira();
    $pdf1->nosso_numero = $oConvenio->getNossoNumero();
}

//numpre formatado
$numpre = db_sqlformatar($k03_numpre, 8, '0') . '000999';
$numpre = $numpre . db_CalculaDV($numpre, 11);

//concatena todos os parametros

//***************** V A R I A V E I S   P A R A   O   R E C I B O   N O   R O D A P E   D A S   C E R T I D O E S  *************************//
if (isset($mostramenu) && $mostramenu == "t") {
    db_redireciona("cai2_emitecnd001.php?tipo=$tipocert&k03_numpre=$k03_numpre&codproc=$p58_codproc&titulo=$titulo&origem=$origem&historico=" . trim($historico) . "&codigobarras=$codigobarras&linhadigitavel=$linhadigitavel&dtvenc=$datavencimento&cadrecibo=$mostramenu");
    exit;
}

$oIdentificacao = db_utils::fieldsMemory($Identificacao, 0);

//******************************************************************************************************************************************//

$pdf1->prefeitura = $db_nomeinst;
/**
 * Quando for convênio BDL de qualquer banco, sistema deve listar no boleto de Recibo (92) e Carnê (100)
 * o nome do cedente que constar como nome no cadastro de convênio.
 * Não deve usar o nome da instituição.
 */
if ($oRegraEmissao->getCadTipoConvenio() == 1) {
    $pdf1->prefeitura = $oRegraEmissao->getNomeConvenio();
}


$pdf1->logo = $src;
$pdf1->tipo_convenio = $oConvenio->getTipoConvenio();
$pdf1->uf_config = $db12_uf;
$pdf1->enderpref = $db_ender;
$pdf1->municpref = $db_munic;
$pdf1->telefpref = $db_telef;
$pdf1->emailpref = $db_email;
$pdf1->cgcpref = $cgc;

/*Campos de identificação do proprietário*/
$pdf1->nome = trim(pg_result($Identificacao, 0, "z01_numcgm")) . "-" . trim(pg_result($Identificacao, 0, "z01_nome"));
$pdf1->ender = trim(pg_result($Identificacao, 0, "z01_ender")) . ', ' . pg_result($Identificacao, 0, "z01_numero")
  . ' ' . trim(pg_result($Identificacao, 0, "z01_compl")) . (strlen(trim(pg_result($Identificacao, 0, "z01_bairro"))) > 0 ? "/" : "")
  . trim(pg_result($Identificacao, 0, "z01_bairro"));
$pdf1->munic = trim(pg_result($Identificacao, 0, "z01_munic"));
$pdf1->bairrocontri = $j13_descr;

if (!empty($oIdentificacao->z01_bairro)) {
    $pdf1->bairrocontri = $oIdentificacao->z01_bairro;
}

$pdf1->cep = trim(pg_result($Identificacao, 0, "z01_cep"));
$pdf1->cgccpf = $cgmcerto != '' ? $cgmcerto : '';
/*ffm da identificação do proprietario**/

/*Identificação da origem*/
$pdf1->tipoinscr = $tipoidentificacao;
$pdf1->nrinscr = @$numero . ($numprot > 0 ? " - PROCESSO: " . $numprotimp : "");
$pdf1->tipolograd = ($numprot > 0 ? 'Requerente :' : 'Logradouro :');
$pdf1->tipocompl = ($numprot > 0 ? 'Tipo :' : 'N' . chr(176) . '/Compl :');
$pdf1->nrpri = ($numprot > 0 ? $p51_descr : $j39_numero);
$pdf1->tipobairro = 'Bairro :';
$pdf1->ip = db_getsession("DB_ip");
$pdf1->nomepri = ($numprot > 0 ? $p58_requer : $nomepri);
$pdf1->complpri = $j39_compl;
$pdf1->bairropri = $j13_descr;
$pdf1->nomepriimo = $nomepriimo;


$pdf1->nrinscr1 = ($numprot > 0 ? $numprot : @$numero);
$pdf1->tipoinscr1 = ($numprot > 0 ? $tipoidentificacao1 : $tipoidentificacao);
/*fim identificação origem*/


$pdf1->datacalc = date('d-m-Y', $DB_DATACALC);
$pdf1->predatacalc = date('d-m-Y', $DB_DATACALC);
$pdf1->taxabanc = db_formatar($taxabancaria, 'f');
$pdf1->linhasdadospagto = pg_numrows($DadosPagamento);
$pdf1->recorddadospagto = $DadosPagamento;
$pdf1->receita = 'k00_receit';
$pdf1->receitared = 'codreduz';
$pdf1->dreceita = 'k02_drecei';
$pdf1->ddreceita = 'k07_descr';
$pdf1->valor = 'valor';
$pdf1->historico = $k00_descr;
$pdf1->histparcel = @$histparcela;
$pdf1->dtvenc = $datavencimento;
$pdf1->numpre = $numpre;
$pdf1->valtotal = db_formatar(@$valor_parm, 'f');
$pdf1->linhadigitavel = $linhadigitavel;
$pdf1->codigobarras = $codigobarras;

if (isset($lReemissao) && $lReemissao) {
    if (!isset($lBarra)) {
        $pdf1->linhadigitavel = null;
        $pdf1->codigobarras = null;
    }


    $sSqlCodAutenticador = " SELECT k12_codautent
                                   from cornump
                                        inner join corautent on cornump.k12_id = corautent.k12_id
                                               and cornump.k12_data = corautent.k12_data
                                               and corautent.k12_autent = cornump.k12_autent
                                  where k12_numnov = $k03_numpre ";
    $rsCodAutenticador = db_query($sSqlCodAutenticador);
    $iNumrowsCodAutenticador = pg_numrows($rsCodAutenticador);
    if ($iNumrowsCodAutenticador > 0) {
        db_fieldsmemory($rsCodAutenticador, 0);
    } else {
        $k12_codautent = '';
    }

    /*
     *
     * Adicionada logica para buscar os dados da autenticação do recibo quando foi utilizado o reprocessamento dos lançamentos de receita para o PCASP
     *
     */
    if ($k12_codautent == '') {
        include(modification("libs/db_libpostgres.php"));
        if (PostgreSQLUtils::isTableExists("w_bkp_corautent")) {
            $sSqlCodAutenticador = "select k12_codautent from w_bkp_corautent where k12_codautent ilike '%$k03_numpre%$total_recibo%'";
            $rsCodAutenticador = pg_query($sSqlCodAutenticador);
            $iNumrowsCodAutenticador = pg_numrows($rsCodAutenticador);
            if ($iNumrowsCodAutenticador > 0) {
                db_fieldsmemory($rsCodAutenticador, 0);
            }
        }
    }


    $pdf1->k12_codautent = $k12_codautent;
}

$pdf1->texto = db_getsession('DB_login') . ' - ' . date("d-m-Y - H-i") . '   ' . db_base_ativa();

/**********************************************************************************************************/
$pdf1->descr3_1 = trim(pg_result($Identificacao, 0, "z01_nome")); // contribuinte
$pdf1->descr3_2 = trim(pg_result($Identificacao, 0, "z01_ender")) . ', ' . pg_result($Identificacao, 0, "z01_numero")
  . ' ' . trim(pg_result($Identificacao, 0, "z01_compl"));
$pdf1->ufcgm = trim(pg_result($Identificacao, 0, "z01_uf"));
$pdf1->premunic = trim(pg_result($Identificacao, 0, "z01_munic"));    // bairro


$pdf1->predescr3_1 = trim(pg_result($Identificacao, 0, "z01_nome")); // contribuinte
$pdf1->predescr3_2 = trim(pg_result($Identificacao, 0, "z01_ender")) . ', ' . pg_result($Identificacao, 0, "z01_numero")
  . ' ' . trim(pg_result($Identificacao, 0, "z01_compl"));
$pdf1->prebairropri = @$z01_bairro;    // municipio
$pdf1->premunic = trim(pg_result($Identificacao, 0, "z01_munic"));    // bairro
$pdf1->precep = trim(pg_result($Identificacao, 0, "z01_cep"));
$pdf1->precgccpf = trim(@pg_result($Identificacao, 0, "z01_cgccpf"));

$pdf1->titulo5 = "";                 // titulo parcela
$pdf1->descr5 = "";                 // descr parcela
$pdf1->titulo8 = $tipoidentificacao;  // tipo de identificacao;
$pdf1->descr8 = @$numero;            //descr matricula ou inscricao

$pdf1->pretitulo5 = "";                 // titulo parcela
$pdf1->predescr5 = "";                 // descr parcela
$pdf1->pretitulo8 = $tipoidentificacao;  // tipo de identificacao;
$pdf1->predescr8 = @$numero;            //descr matricula ou inscricao

$pdf1->arraycodreceitas = $arraycodreceitas;
$pdf1->arrayreduzreceitas = $arrayreduzreceitas;
$pdf1->arraydescrreceitas = $arraydescrreceitas;
$pdf1->arrayvalreceitas = $arrayvalreceitas;

//$pdf1->descr4_1  = $historico;
$pdf1->predescr4_2 = $pdf1->historico;
//$pdf1->predescr4_2  = $k00_descr." ".@$histparcela." ".$historico;
//$pdf1->descr4_2  = ""; // historico - linha 1

$pdf1->descr16_1 = "";
$pdf1->descr16_2 = "";
$pdf1->descr16_3 = ""; //

$pdf1->predescr16_1 = "";
$pdf1->predescr16_2 = "";
$pdf1->predescr16_3 = ""; //

$pdf1->descr12_1 = "";
$pdf1->descr12_2 = ""; //

$pdf1->linha_digitavel = $linhadigitavel;
$pdf1->codigo_barras = $codigobarras;

$pdf1->descr6 = $datavencimento;  // Data de Vencimento
$pdf1->descr7 = db_formatar(@$valor_parm, 'f');  // qtd de URM ou valor
$pdf1->descr9 = db_numpre($k03_numpre, 0) . "000"; //$numpre; // cod. de arrecadação

$pdf1->predescr6 = $datavencimento;  // Data de Vencimento
$pdf1->predescr7 = db_formatar(@$valor_parm, 'f');  // qtd de URM ou valor
$pdf1->predescr9 = db_numpre($k03_numpre, 0) . "000"; //$numpre; // cod. de arrecadação
/*************************************************************************************/
$pdf1->descr11_1 = trim(pg_result($Identificacao, 0, "z01_nome"));
$pdf1->descr11_2 = trim(pg_result($Identificacao, 0, "z01_ender") . ', ' . pg_result($Identificacao, 0, "z01_numero"));
$pdf1->descr11_3 = trim(pg_result($Identificacao, 0, "z01_munic"));
$pdf1->descr12_1 = $historico . "\n \n" . $k00_msgrecibo;
$pdf1->descr14 = $datavencimento; // vencimento
$pdf1->descr10 = "1 / 1";
$pdf1->data_processamento = date('d/m/Y', db_getsession('DB_datausu'));
$pdf1->tipo_exerc = "11 / " . date('Y', db_getsession('DB_datausu'));
$pdf1->especie = "R$";
$pdf1->dtparapag = $datavencimento; //date('d/m/Y',db_getsession('DB_datausu'));
/*************************************************************************************/

// ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
//verifica se é ficha e busca o codigo do banco

if ($oRegraEmissao->isCobranca()) {
    $rsConsultaBanco = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
    $oBanco = db_utils::fieldsMemory($rsConsultaBanco, 0);
    $pdf1->numbanco = $oBanco->db90_codban . "-" . $oBanco->db90_digban;
    $pdf1->banco = $oBanco->db90_abrev;

    try {
        $pdf1->imagemlogo = $oConvenio->getImagemBanco();
    } catch (Exception $eExeption) {
        db_redireciona("db_erros.php?fechar=true&db_erro=" . $eExeption->getMessage());
    }
}

//#############################################################

if ($db21_codcli == 4 && $pdf1->impmodelo == 2) {
    $pdf1->lUtilizaModeloDefault = false;
}

$pdf1->imprime();
if (!isset($lGerarOutput)) {
    $pdf1->objpdf->output();
}
