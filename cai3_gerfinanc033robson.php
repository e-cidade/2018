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

require ("fpdf151/scpdf.php");
include ("fpdf151/impcarne.php");
include ("libs/db_sql.php");
include ("classes/db_db_config_classe.php");
include ("classes/db_iptubase_classe.php");
include ("classes/db_modcarne_classe.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//die($HTTP_SERVER_VARS['QUERY_STRING']);
$cldb_config = new cl_db_config;
$clmodcarne = new cl_modcarne;
$histinf = "";
$result = pg_exec("select codmodelo,k03_tipo from arretipo where k00_tipo = $tipo_debito");
db_fieldsmemory($result, 0);
pg_free_result($result);

$resul = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit"), "nomeinst as prefeitura, munic, to_char(tx_banc,'99.99') as tx_banc"));
db_fieldsmemory($resul, 0); // pega o dados da prefa
$munic2     = $munic;
$nomeinst2  = $prefeitura;
$taxabancaria = $tx_banc;
$msgvencida = "";
$bql        = "";
$obsdiver   = "";

if ((int) $codmodelo > 0) {
  $impmodelo = (int) $codmodelo;
} else {
  $impmodelo = 1;
}
//=============================================================================================================================
if ($k03_tipo == 1 && $impmodelo <> 1 && $impmodelo <> 30) {
  if (!isset ($matric)) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Para Emissão de Carnê do IPTU Consulte Dados Pela Matrícula.');
    exit;
  }
  $cliptubase = new cl_iptubase;
  $pdf2 = db_criacarne($tipo_debito, db_getsession('DB_ip'), date("Y-m-d", db_getsession("DB_datausu")), db_getsession('DB_instit'), 1); 
  $resultpro = $cliptubase->proprietario_record($cliptubase->proprietario_query($matric));
  db_fieldsmemory($resultpro, 0);

  $pdf2->iptj01_matric = $j01_matric;
  $pdf2->iptz01_cidade = $munic2;
  $pdf2->iptprefeitura = $nomeinst2;
  $pdf2->iptz01_ender  = $z01_ender;
  $pdf2->iptbql        = $j34_setor."/".$j34_quadra."/".$j34_lote;
  $pdf2->iptnomepri    = $nomepri;
  $pdf2->iptcodpri     = $j39_numero;
  $pdf2->iptproprietario = $proprietario;
  $pdf2->iptz01_nome   = $z01_nome;
  $pdf2->iptz01_numcgm = $z01_numcgm;
  $pdf2->iptz01_cgccpf = $z01_cgccpf;
  $pdf2->iptz01_bairro = $z01_bairro;
  $pdf2->iptbairroimo  = $j13_descr;
  $pdf2->iptz01_cidade = $z01_cidade;
  $pdf2->iptz01_munic  = $z01_munic;
  $pdf2->iptz01_cep    = $z01_cep;
  $pdf2->iptj43_cep    = $j43_cep;
  $pdf2->iptdataemis   = date("d/m/Y", db_getsession("DB_datausu"));

  $sql = "select * from arrematric 
                  inner join arrecad on arrecad.k00_numpre = arrematric.k00_numpre 
        where k00_matric = $j01_matric 
          and k00_dtvenc < '".date("Y-m-d", db_getsession("DB_datausu"))."' limit 1";
  $rsResulant = pg_query($sql);
  $numlin = pg_numrows($rsResulant);
  if ($numlin > 0) {
    $pdf2->iptdebant = "Há Débitos Anteriores, favor procurar Setor de Dívida Ativa";
  }

  unset ($resultpro);

  $vt = $HTTP_POST_VARS;
  $tam = sizeof($vt);
  reset($vt);
  $numpres = "";
  for ($i = 0; $i < $tam; $i ++) {
    if (db_indexOf(key($vt), "CHECK") > 0)
      $numpres .= "N".$vt[key($vt)];
    next($vt);
  }

  $numpres = split("N", $numpres);

  $unica = false;
  if (sizeof($numpres) < 2) {
    $numpres = array ("0" => "0", "1" => $numpre_unica.'P000');
    $unica = true;
  } else {
    if (isset ($HTTP_POST_VARS["numpre_unica"])) {
      $unica = true;
    }
  }

  //  pg_exec("BEGIN");
  for ($volta = 1; $volta < sizeof($numpres); $volta ++) {
    $codigos = split("P", $numpres[$volta]);
  }

  $resultunica = pg_exec("select j23_anousu from iptucalc inner join iptunump on j20_anousu = j23_anousu and j20_matric = j23_matric where j20_numpre = $codigos[0]");
  db_fieldsmemory($resultunica, 0);
  $pdf2->iptj23_anousu = $j23_anousu;

  $resultunica = pg_exec("select * from recibounica where k00_numpre = $codigos[0]");
  if (pg_numrows($resultunica) > 0) {
    db_fieldsmemory($resultunica, 0);
    $vencunica = db_formatar($k00_dtvenc, "d");
  }

  if ($unica == 't') {
    $sql = "select *,
               substr(fc_calcula,2,13)::float8 as uvlrhis,
               substr(fc_calcula,15,13)::float8 as uvlrcor,
               substr(fc_calcula,28,13)::float8 as uvlrjuros,
               substr(fc_calcula,41,13)::float8 as uvlrmulta,
               substr(fc_calcula,54,13)::float8 as uvlrdesconto,
               (substr(fc_calcula,15,13)::float8+
               substr(fc_calcula,28,13)::float8+
               substr(fc_calcula,41,13)::float8-
               substr(fc_calcula,54,13)::float8) as utotal
                 from (
                 select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,
                    fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")
               from recibounica r
                 where r.k00_numpre = ".$codigos[0]." and r.k00_dtvenc >= '".date('Y-m-d', db_getsession("DB_datausu"))."'::date 
                 ) as unica order by dtvencunic desc";
    $linha = 220;
    $resultfin = pg_query($sql) or die($sql);

    if ($resultfin != false) {
      db_fieldsmemory($resultfin, 0);
      $pdf2->iptk00_percdes  = $k00_percdes;
      $uvlrcor               = db_formatar($uvlrcor, 'f');
      $pdf2->iptuvlrcor      = $uvlrcor;
      $vlrhis                = db_formatar($uvlrhis, 'f');
      $vlrdesconto           = db_formatar($uvlrdesconto, 'f');
      $pdf2->iptuvlrdesconto = $vlrdesconto;
			$utotal								 += $taxabancaria;
      $vlrtotal              = db_formatar($utotal, 'f');
      $vlrbar                = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
      $pdf2->ipttotal        = $vlrtotal;

      $resultnumbco = pg_exec("select numbanco, segmento, formvencfebraban from db_config where codigo = ".db_getsession("DB_instit"));
      db_fieldsmemory($resultnumbco, 0); // deve ser tirado do db_config

      $numpre = db_numpre($k00_numpre).'000'; //db_formatar(0,'s',3,'e');
      $dtvenc = str_replace("-", "", $dtvencunic);
      $datavencimento = $dtvencunic;

      if ($formvencfebraban == 1) {
        $hj = str_replace("-", "", date('Y-m-d', db_getsession('DB_datausu')));
        $tmpdt = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);
        if ($tmpdt > $datavencimento) {
          $datavencimento = $tmpdt;
        }
        $db_dtvenc = str_replace("-", "", $datavencimento);
        $vencbar = $db_dtvenc.'000000';
      }elseif ($formvencfebraban == 2) {
        $hj = str_replace("-", "", date('Y-m-d', db_getsession('DB_datausu')));
        $tmpdt = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);
        if ($tmpdt > $datavencimento) {
          $datavencimento = $tmpdt;
        }
        $db_dtvenc = str_replace("-", "", $datavencimento);
        $db_dtvenc = substr($db_dtvenc, 6, 2).substr($db_dtvenc, 4, 2).substr($db_dtvenc, 2, 2);
        $vencbar = $db_dtvenc.'00000000';
      }
      if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {
        if (substr($vencbar, 0, 4) > db_getsession('DB_anousu')) {
          continue;
        }
      }
      $inibar = "8".$segmento."6";
      if(isset($k47_tipoconvenio) && $k47_tipoconvenio == 2){
				if (substr($vencbar, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0) {
						$k00_valor = 0;
						$especie   = $ninfla;
						$histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
				}else{
						$especie   = 'R$';
						$histinf   = "";
				}

        if($dtvenc < date('Ymd',db_getsession('DB_datausu'))){
$msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;
				      $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
				}else{
			       $msgvencida = "";					
				}
				$resultcod  = pg_exec("select * from fc_fichacompensacao($k22_cadban,$k00_numpre,$k00_numpar,to_date('".$k00_dtvenc."','dd/mm/yyyy'),$k00_valor)"); 
				db_fieldsmemory($resultcod, 0);
				if(isset($qinfla) && $qinfla != '' && $k00_valor == 0){
							 $k00_valor = $qinfla;				   				
				}
       	if($erro == 'f'){
           $codigo_barras   = $codigobarras;
           $linha_digitavel = $linhadigitavel;
      	}else{
          db_msgbox($mensagem);
          exit;
        }
      }else{
        $resultcod = pg_exec("select fc_febraban('$inibar'||'$vlrbar'||'".$numbanco."'||'".$vencbar."'||'$numpre')");
        db_fieldsmemory($resultcod, 0);
        if ($fc_febraban == "") {
          db_msgbox("Erro ao gerar codigo de barras (1)!");
          exit;
        }
        $dtvencunic = db_formatar($dtvencunic, 'd');
        $pdf2->iptdtvencunic = $dtvencunic;
        $codigo_barras   = substr($fc_febraban, 0, strpos($fc_febraban, ','));
        $linha_digitavel = substr($fc_febraban, strpos($fc_febraban, ',') + 1);
      }
      $pdf2->iptcodigo_barras = $codigo_barras;
      $pdf2->iptlinha_digitavel = $linha_digitavel;
      pg_free_result($resultfin);
  }

  $sql = "select sum(j22_valor) as vlredi
              from iptucale
           where j22_anousu = $j23_anousu 
           and j22_matric = $j01_matric";
  $sqlres = pg_exec($sql);
  if (pg_numrows($sqlres) > 0) {
    db_fieldsmemory($sqlres, 0);
  } else {
    $vlredi = 0;
  }
  $sql = "select j23_vlrter, j23_aliq
              from iptucalc
           where j23_anousu = $j23_anousu 
          and j23_matric = $j01_matric";
  $sqlres = pg_exec($sql);
  if (pg_numrows($sqlres) > 0) {
    db_fieldsmemory($sqlres, 0);
    $pdf2->iptj23_aliq = $j23_aliq;
  } else {
    $j23_vlrter = 0;
    $j23_aliq = 0;
  }
  $j23_vlrter += $vlredi;
  $pdf2->iptj23_vlrter = db_formatar($j23_vlrter, 'f');
}
  $pdf2->imprime();
  $pdf2->objpdf->Output();
  exit;
} else {

  ////////////////////////////////////////////////////////////////////////////////  
  ////////  C O M E Ç O   D A  G E R A Ç Ã O  D O S   C A R N E S   //////////////
  ////////////////////////////////////////////////////////////////////////////////

  /********************* R O T I N A   P A R A   B U S C A R   O   M O D E L O   D E   C A R N E *****************************************************/
  $rstipo = pg_exec("select * from arretipo where k00_tipo = $tipo_debito");
  db_fieldsmemory($rstipo, 0);

  $result = pg_exec("select * from db_config where codigo = ".db_getsession('DB_instit'));
  db_fieldsmemory($result, 0);

  /***************************************************************************************************************************************************/
  // FUNCAO Q RETORNA O PDF ESTANCIADO JA COM O MODELO CERTO TESTANDO AS RESTRIÇÕES
  $pdf1 = db_criacarne($tipo_debito, db_getsession('DB_ip'), date("Y-m-d", db_getsession("DB_datausu")), db_getsession('DB_instit'), 1);

  $pdf1->prefeitura = $nomeinst;

  $sqlparag = "select db02_texto
          from db_documento 
             inner join db_docparag  on db03_docum   = db04_docum
             inner join db_tipodoc   on db08_codigo  = db03_tipodoc
             inner join db_paragrafo on db04_idparag = db02_idparag 
          where db03_tipodoc = 1017 
	    and db03_instit = ".db_getsession("DB_instit")." 
	  order by db04_ordem ";
  $resparag = pg_query($sqlparag);

  if (pg_numrows($resparag) == 0) {
    $pdf1->secretaria = 'SECRETARIA DE FINANÇAS';
  } else {
    db_fieldsmemory($resparag, 0);
    $pdf1->secretaria = $db02_texto;
  }

  $pdf1->tipodebito = $k00_descr;
  $pdf1->pretipodebito = $k00_descr;
  $pdf1->logo = $logo;

  pg_exec("BEGIN");
  db_postmemory($HTTP_POST_VARS);
  $vt = $HTTP_POST_VARS;
  $tam = sizeof($vt);
  reset($vt);
  $numpres = "";

  //print_r($vt);exit;
  for ($i = 0; $i < $tam; $i ++) {
    if (db_indexOf(key($vt), "CHECK") > 0) {
      $numpres .= "N".$vt[key($vt)];
    }
    next($vt);
  }
  $sounica = $numpres;
  $numpres = split("N", $numpres);

  $unica = 2;
  if (sizeof($numpres) < 2) {
    $numpres = array ("0" => "0", "1" => $numpre_unica.'P000');
    $unica = 1;
  } else {
    if (isset ($HTTP_POST_VARS["numpre_unica"])) {
      if ($numpre_unica != '') {
        $unica = 1;
      }
    }
  }
  //sizeof($numpres);
  if (isset ($geracarne) && $geracarne == 'banco') {
    $pagabanco = 't';
  } else {
    $pagabanco = 'f';
  }

/******************************************************   F O R   Q   M O N T A   O S   C A R N E S  ******************************************************/

  for ($volta = 1; $volta < sizeof($numpres); $volta ++) {
		if ($numpres[$volta] == "") {
			continue;
		}
    $k00_numpre = substr($numpres[$volta], 0, strpos($numpres[$volta], 'P'));
    
    $resulttipo = pg_exec("select k00_descr,k00_codbco,k00_codage,k00_txban,k00_rectx,
k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,
k00_hist6,k00_hist7,k00_hist8 
from arretipo 
where k00_tipo = $tipo_debito ");
    db_fieldsmemory($resulttipo, 0);
    if(isset($k47_tipoconvenio) && $k47_tipoconvenio == 2){
       //select na cadban para sobrescrever o codigo doselect acima, no caso
//       die(db_getsession('DB_instit'));
//       die("select k15_codbco as k00_codbco, k15_codage as k00_codage from cadban where k15_codigo = $k22_cadban ");
       $resulttipo = pg_exec("select k15_codbco as k00_codbco, k15_codage as k00_codage from cadban where k15_codigo = $k22_cadban ");
 //      db_fieldsmemory($resulttipo, 0);
//       db_criatabela( $resulttipo);
    } 

     

    //////////////////////  PARCELAMENTO DE DIVERSO ///////////////////////////////////
    if ($k03_tipo == 16) {
      $sql28 = "select b.* 
                     from diversos a 
                          left join  termodiver on  dv10_parcel = dv05_coddiver
                          left outer join procdiver b on a.dv05_procdiver=b.dv09_procdiver 
                     where dv05_numpre = $k00_numpre limit 1";
      $result28 = pg_exec($sql28);
      if (pg_numrows($result28) > 0) {
        db_fieldsmemory($result28, 0);
        $pdf1->tipodebito = 'PARCELAMENTO DE '.$dv09_descr;
        $pdf1->pretipodebito = "PARCELAMENTO DE  $dv09_descr  N- $v10_parcel";
      }
    }

    //////////////  DIVERSO /////////////////////
    if (($tipo_debito == 28) || ($tipo_debito == 25)) {
      ///////// PARCELAMENTO///////////////  
      if ($tipo_debito == 28) {
        $sql25 = "select * 
                          from termo 
                               inner join termodiver on v07_parcel = dv10_parcel 
                               inner join diversos on dv10_coddiver = dv05_coddiver 
                               inner join procdiver on dv05_procdiver=dv09_procdiver
                          where v07_numpre = $k00_numpre";

      ///////////// DIVERSO ///////////////
      } else {
          $sql25 = "select * 
                       from diversos a 
                            left outer join procdiver b on a.dv05_procdiver=b.dv09_procdiver 
                       where dv05_numpre = $k00_numpre 
                       order by a.dv05_coddiver desc limit 1";

      }
      //echo $sql25;exit;
      $result25 = pg_exec($sql25) or die($sql25);

      if (pg_numrows($result25) > 0) {
        db_fieldsmemory($result25, 0);
        @$obs = substr($obs, 0, 20);
        $pdf1->tipodebito = $dv09_descr;
        $pdf1->pretipodebito = $dv09_descr;
        $pdf1->pretipodebito = "PARCELAMENTO DE DIVERSO N- $dv10_parcel";
      } else {
        @$obs = "";
        $rstermo = pg_query(" select v07_parcel from termo where v07_numpre = $k00_numpre ");
        if( pg_numrows($rstermo) > 0 ){
          $codparcel = " N- $v07_parcel ";
        }else{
          $codparcel = "";
        }
        $pdf1->tipodebito = "";
        $pdf1->pretipodebito =$k00_descr.$codparcel;
        $k00_hist1 = "";
        $pdf1->secretaria = "";
        $dv05_procdiver = 0;
      }

      if ($dv05_procdiver == 1284) {
        $pdf1->secretaria = 'FUNDO MUNICIPAL DE HABITAÇÃO';
        $k00_hist1 = 'Convênio SEHAB nº 72/99 - Programa Especial do Funco de Desenvolvimento Social. Aprovação do Conselho Estadual de Habitação em 08/09/1999';
      } else if ($dv05_procdiver == 221) {
          $pdf1->secretaria = 'FUNDO MUNICIPAL DE HABITAÇÃO';
          $k00_hist1 = 'Lei Municipal nº 3049/2002, de 04/12/2002. Aprovação do Conselho Estadual de Habitação em dez/2002';
        }
    }

    $proprietario = '';
    $xender = '';
    $xbairro = '';

    $rstermo = pg_query(" select v07_parcel from termo where v07_numpre = $k00_numpre ");
    if( pg_numrows($rstermo) > 0 ){
      db_fieldsmemory($rstermo,0); 
      $codparcel = " N- $v07_parcel ";
      $pdf1->pretipodebito =$k00_descr.$codparcel;
    }else{
      $codparcel = "";
    }

    /***********************************************************************************************************************/

    $sqlorigem = "select arrecad.k00_numpre,
           arrenumcgm.k00_numcgm as z01_numcgm,  
          case when arrematric.k00_matric is not null
            then arrematric.k00_matric
                when arreinscr.k00_inscr is not null 
                  then arreinscr.k00_inscr
               else 
                  arrenumcgm.k00_numcgm
          end as origem,
          case when arrematric.k00_matric is not null
            then 'Matrícula'
               when arreinscr.k00_inscr is not null
            then 'Inscrição'
                     else
            'CGM'
          end as descr
        from arrecad
inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre
left join arrematric on arrematric.k00_numpre = arrecad.k00_numpre
left join arreinscr  on arreinscr.k00_numpre  = arrecad.k00_numpre
where arrecad.k00_numpre = $k00_numpre";
    $rsOrigem = pg_query($sqlorigem) or die($sqlorigem);
    if (pg_numrows($rsOrigem) > 0) {
      db_fieldsmemory($rsOrigem, 0);
    } else {
      db_msgbox("Nao encontrou registros do numpre: $k00_numpre!");
    }

    if (!empty ($descr) && $descr == 'Matrícula') {
      $Identificacao = pg_exec("select * from proprietario where j01_matric = $origem limit 1");
      db_fieldsmemory($Identificacao, 0);
//db_criatabela($Identificacao);
      $proprietario = $z01_nome;
//die($nomepri);
      $pdf1->bairropri  = $j13_descr;
      $pdf1->prebairropri  = $z01_bairro;
      $pdf1->nomepriimo = $nomepri;
      
      // trocado porque bage pediu
      
      if(isset($k47_tipoconvenio) && $k47_tipoconvenio == 2){
$xender = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
      }else{
$xender = $nomepri.', '.$j39_numero.'  '.$j39_compl;
      }
      // $xender = $nomepri.', '.$j39_numero.'  '.$j39_compl;
//      $xbairro = $j43_bairro;
//      $xbairro = $j43_bairro;
      $bql = '  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
      if (isset ($impmodelo) && $impmodelo == 30) {
        if ($k00_tipo != 6) {
          $numero = $j01_matric;
        } else {
          //$descr = "";
          $numero = "";
          $numero = $j01_matric;
        }
      } else {
        if ($k00_tipo != 6) {
          $numero = $j01_matric.'  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
        } else {
          //         $descr = "";
          $numero = "";
          $numero = $j01_matric.'  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
        }
      }
    } else if (!empty ($descr) && $descr == 'Inscrição') {
        $Identificacao = pg_exec("select * from empresa where q02_inscr = $origem");
        db_fieldsmemory($Identificacao, 0);
        if ($k00_tipo != 6) {
          $numero = $q02_inscr;
          $z01_numcgm = $q02_numcgm;
        } else {
          //    $descr = "";
          $numero = "";
          $numero = $q02_inscr;
          $z01_numcgm = $q02_numcgm;
        }

      } else {
        //  db_msgbox("entrou");
        $Identificacao = pg_exec("select cgm.*, 
                         ''::bpchar as nomepri,
                         ''::bpchar as j39_co
                                            from cgm
                 where z01_numcgm = $origem");
        db_fieldsmemory($Identificacao, 0);
        $numero = $origem;
      }

    /************************************************************************************************************************************/

    // PARCELAMENTO DE DIVIDA  OU  PARCELAMENTO DE CONTR. E MELHORIA OU PARCELAMENTO DE DIVERSO PARCELAMENTO DE INICIAL 
    if (($k03_tipo == 6) || ($k03_tipo == 17) || ($k03_tipo == 16) || ($k03_tipo == 13)) {
      $sqltipodeb = " select  termo.*,z01_nome,z01_ender,z01_numero,z01_compl,z01_bairro,
                        coalesce(k00_matric,0) as matric,
                        coalesce(k00_inscr,0) as inscr
                      from termo
                        left outer join arrematric  on v07_numpre = arrematric.k00_numpre
                        left outer join arreinscr  on v07_numpre = arreinscr.k00_numpre
                        inner join cgm       on v07_numcgm = z01_numcgm
                      where v07_numpre = $k00_numpre ";
      $sqltipodeb = " select z.*, z01_nome,z01_ender,z01_numero,z01_compl,z01_bairro from (
                      select   x.*,
                        case when x.matric <> 0 then case when j41_numcgm is not null then promitente.j41_numcgm else iptubase.j01_numcgm end else case when x.inscr <> 0 then issbase.q02_numcgm else arrecad.k00_numcgm end end as z01_numcgm
                        from (
                        select  termo.*,
                          coalesce(k00_matric,0) as matric,
                          coalesce(k00_inscr,0) as inscr
                        from termo
                          left outer join arrematric  on v07_numpre = arrematric.k00_numpre
                          left outer join arreinscr  on v07_numpre = arreinscr.k00_numpre
                          where v07_numpre = $k00_numpre) as x
                      left join iptubase    on j01_matric = x.matric
                      left outer join promitente      on j01_matric = j41_matric and promitente.j41_tipopro is true
                      left join issbase    on q02_inscr  = x.inscr
                      inner join arrecad on v07_numpre = k00_numpre) as z
                    inner join cgm on z.z01_numcgm = cgm.z01_numcgm ";
      $resulttipodeb = pg_exec($sqltipodeb);
      if (pg_numrows($resulttipodeb) == 0) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento sem termo cadastrado.');
        exit;
      } else {
        db_fieldsmemory($resulttipodeb, 0);
      }
    }
    $exercicio = '';
    //  PARCELAMENTO DE DIVIDA ATIVA
    if ($k03_tipo == 6) {
      $sqldivida = "select distinct v01_exerc 
                         from termodiv 
                              inner join divida on v01_coddiv = coddiv 
                         where parcel = $v07_parcel";
      $resultdivida = pg_exec($sqldivida);
      $traco = '';
      $exercicio = ' - Exerc : ';
      for ($k = 0; $k < pg_numrows($resultdivida); $k ++) {
        $exercicio .= $traco.substr(pg_result($resultdivida, $k, "v01_exerc"), 2, 2);
        $traco = '-';
      }
    }
    /// S E   F O R   U N I C A...
    if ($unica == 1) {
      $sql = "select *,
              substr(fc_calcula,2,13)::float8 as uvlrhis,
              substr(fc_calcula,15,13)::float8 as uvlrcor,
              substr(fc_calcula,28,13)::float8 as uvlrjuros,
              substr(fc_calcula,41,13)::float8 as uvlrmulta,
              substr(fc_calcula,54,13)::float8 as uvlrdesconto,
              (substr(fc_calcula,15,13)::float8+
              substr(fc_calcula,28,13)::float8+
              substr(fc_calcula,41,13)::float8-
              substr(fc_calcula,54,13)::float8) as utotal,
              substr(fc_calcula,77,17)::float8 as qinfla,
              substr(fc_calcula,94,4)::varchar(5) as ninfla
             from ( select r.k00_numpre,
                           r.k00_dtvenc as dtvencunic, 
                     r.k00_dtoper as dtoperunic,
                     r.k00_percdes,
                           fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")
                            from recibounica r
                    where r.k00_numpre = ".$k00_numpre." 
                  and r.k00_dtvenc >= '".date('Y-m-d', db_getsession("DB_datausu"))."'::date ) as unica 
            order by dtvencunic desc";
      $linha = 220;
      $resultfin = pg_query($sql) or die($sql);
      if ($resultfin != false) {
        for ($unicont = 0; $unicont < pg_numrows($resultfin); $unicont ++) {
          db_fieldsmemory($resultfin, $unicont);
          $vlrhis       = db_formatar($uvlrhis, 'f');
          $vlrdesconto  = db_formatar($uvlrdesconto, 'f');
					$utotal				+= $taxabancaria;
          $vlrtotal     = db_formatar($utotal, 'f');
          $vlrbar       = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
          $resultnumbco = pg_exec("select numbanco, segmento, formvencfebraban from db_config where codigo = ".db_getsession("DB_instit"));
          db_fieldsmemory($resultnumbco, 0); // deve ser tirado do db_config

          $sqlvalor = "select k00_impval, k00_tercdigcarneunica from arretipo where k00_tipo = $tipo_debito";
          db_fieldsmemory(pg_exec($sqlvalor), 0);

          // alterei para buscar o terceiro digito pelo tipo de debito da tabela arretipo   
          if (!isset ($k00_tercdigcarneunica) || $k00_tercdigcarneunica == "") {
            db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
          }
          $tipoconvenio = "8".$segmento.$k00_tercdigcarneunica; //"6";
          if ($k00_impval == 't') {
            $k00_valor = $utotal;
            $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
            $ninfla = '';
            if ($utotal == 0) {
              $comvalor = 'f';
              $tipoconvenio = "8".$segmento."7";
              $vlrbar = "00000000000";
            }
          } else {
            $k00_valor = $qinfla;
            $comvalor = 'f';
            $tipoconvenio = "8".$segmento."7";
            $vlrbar = "00000000000";
          }

          $numpre = db_numpre($k00_numpre).'000'; //db_formatar(0,'s',3,'e');
          $dtvenc = str_replace("-", "", $dtvencunic);
          $datavencimento = $dtvencunic;

          if ($formvencfebraban == 1) {
            $hj = str_replace("-", "", date('Y-m-d', db_getsession('DB_datausu')));
            $tmpdt = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);
            if ($tmpdt > $datavencimento) {
              $datavencimento = $tmpdt;
            }
            $db_dtvenc = str_replace("-", "", $datavencimento);

            $vencbar = $db_dtvenc.'000000';
          }  elseif ($formvencfebraban == 2) {
            $hj = str_replace("-", "", date('Y-m-d', db_getsession('DB_datausu')));
             $tmpdt = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);
            if ($tmpdt > $datavencimento) {
              $datavencimento = $tmpdt; 
            }
            $db_dtvenc = str_replace("-", "", $datavencimento);
            $db_dtvenc = substr($db_dtvenc, 6, 2).substr($db_dtvenc, 4, 2).substr($db_dtvenc, 2, 2);
            
            $vencbar = $db_dtvenc.'00000000';
          }
          /**/
          if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {
            if (substr($vencbar, 0, 4) > db_getsession('DB_anousu')) {
              continue;
            }
          }
          /**/

	  if(isset($k47_tipoconvenio) && $k47_tipoconvenio == 2){	
			if (substr($vencbar, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0) {
					$k00_valor = 0;
					$especie   = $ninfla;
					$histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
			}else{
					$especie   = 'R$';
					$histinf   = "";
			}

      if($dtvenc < date('Ymd',db_getsession('DB_datausu'))){
				    $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;					
			      $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
			}else{
			       $msgvencida = "";					
			}

			$resultcod  = pg_exec("select * from fc_fichacompensacao($k22_cadban,$k00_numpre,$k00_numpar,to_date('".$k00_dtvenc."','dd/mm/yyyy'),$k00_valor)"); 
			db_fieldsmemory($resultcod, 0);
			if(isset($qinfla) && $qinfla != '' && $k00_valor == 0){
						 $k00_valor = $qinfla;				   				
			}
  	  if($erro == 'f'){
        $codigo_barras   = $codigobarras;
        $linha_digitavel = $linhadigitavel;
	    }else{
         db_msgbox($mensagem);
         exit;
      }
    }else{
	    $resultcod       = pg_exec("select fc_febraban('$tipoconvenio'||'$vlrbar'||'".$numbanco."'||$vencbar||'$numpre')");
	    db_fieldsmemory($resultcod, 0);
      $dtvencunic      = db_formatar($dtvencunic, 'd');
      $codigo_barras   = substr($fc_febraban, 0, strpos($fc_febraban, ','));
      $linha_digitavel = substr($fc_febraban, strpos($fc_febraban, ',') + 1);
      if ($fc_febraban == ""){
        db_msgbox("Erro ao gerar codigo de barras (2)!");
        exit;
      }
	  }

    

          global $pdf;
          $pdf1->titulo1 = $descr;
          $pdf1->descr1 = $numero;
          $pdf1->descr2 = db_numpre($k00_numpre, 0).'000'; //.db_formatar($k00_numpar,'s',"0",3,"e"); 
          if (isset ($obs)) {
            $pdf1->titulo13 = 'Observação';
            $pdf1->descr13 = $obs;
          }
          /////////////// ISSQN FIXO //////////////////////////////
          if ($k03_tipo == 2) {
            $pdf1->titulo4 = 'Atividade';
            $pdf1->descr4_1 = '- '.$q07_ativ.'-'.$q03_descr;
            $pdf1->titulo13 = 'Atividade';
            $pdf1->descr13 = $q07_ativ;
            ////////////// PARCELAMANTO DE DIVIDA E DE INICIAL ////////////       
          } else if (($k03_tipo == 6) || ($k03_tipo == 13)) {
              $pdf1->titulo4 = 'Parcelamento';
              $pdf1->descr4_1 = '- '.$v07_parcel.$exercicio;
              $pdf1->titulo13 = 'Parcelamento';
              $pdf1->descr13 = $v07_parcel;
            }
          $pdf1->descr5 = 'UNICA';
          $pdf1->descr6 = $dtvencunic;
          $pdf1->predescr6 = $dtvencunic;
          $pdf1->titulo8 = $descr;
          $pdf1->pretitulo8 = $descr;
          $pdf1->descr8 = $numero;
          $pdf1->predescr8 = $numero;
          $pdf1->descr9 = db_numpre($k00_numpre, 0).'000';
          $pdf1->predescr9 = db_numpre($k00_numpre, 0).'000';
          $pdf1->descr10 = 'UNICA';

          if (!empty ($HTTP_POST_VARS["ver_matric"])) {
            $pdf1->descr11_1 = $z01_cgmpri." - ".$proprietario;
            $pdf1->descr11_2 = $xender;
            $pdf1->descr3_1 = $z01_cgmpri." - ".$proprietario;
            $pdf1->predescr3_1 = $z01_cgmpri." - ".$proprietario;
            $pdf1->descr3_2 = $xender;
            $pdf1->predescr3_2 = $xender;
            $pdf1->descr17 = $bql; //variavel q guarda o setor/quadra/lote
          } else {
            $pdf1->descr11_1 = $z01_numcgm." - ".$z01_nome;
            $pdf1->descr11_2 = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
            $pdf1->descr3_1 = $z01_numcgm." - ".$z01_nome;
            $pdf1->predescr3_1 = $z01_numcgm." - ".$z01_nome;
            $pdf1->descr3_2 = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
            $pdf1->predescr3_2 = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
            $pdf1->descr17 = $bql; //variavel q guarda o setor/quadra/lote
          }
          //     die($pdf1->tipodebito."\n".$bql."\n".$obsdiver."\n"); 
          if(isset($k47_tipoconvenio) && $k47_tipoconvenio == 2){
             $pdf1->descr12_1 .= $pdf1->tipodebito."\n".
                                 $pdf1->titulo1." - ".$pdf1->descr1." / ".
                                 $pdf1->titulo4." ".$pdf1->descr4_1." Parcela - ".
                                 $k00_numpar."/".$k00_numtot."\n".
                                 (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
                                 (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
             $pdf1->predescr12_1 .= $pdf1->pretipodebito."\n".
                                 $pdf1->titulo1." - ".$pdf1->descr1." / ".
                                 $pdf1->titulo4." ".$pdf1->descr4_1." Parcela - ".
                                 $k00_numpar."/".$k00_numtot."\n".
                                 (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
                                 (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
          }

          ///////// PEGA A MSG DE PAGAMENTO E AS INSTRUÇÕES DA TABELA NUMPREF

          $rsmsgcarne = pg_query("select k03_msgcarne, k03_msgbanco from numpref where k03_anousu = ".db_getsession("DB_anousu"));
          if (pg_numrows($rsmsgcarne) > 0) {
            db_fieldsmemory($rsmsgcarne, 0);
          }
          if (isset ($k00_msguni2) && $k00_msguni2 != "") {
            $pdf1->descr12_1 = $k00_msguni2; //msg unica, via contribuinte
          } else {
            $pdf1->descr12_1 = $k03_msgbanco." Não aceitar apos vencimento "; //msg unica, via contribuinte
          }
          $pdf1->descr14 = $dtvencunic;
          if ($tipoconvenio == '817') {
            //////////////////// ISSQN VARIAVEL /////////////////////
            if ($k03_tipo == 3) {
              $sqlaliq = "select q05_aliq,q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
              $rsIssvarano = pg_exec($sqlaliq);
              $intNumrows = pg_numrows($rsIssvarano);
              if ($intNumrows == 0) {
                db_redireciona('db_erros.php?fechar=true&db_erro=Ano não encontrado na tabela issvar. Contate o suporte');
                exit;
              }
              db_fieldsmemory($rsIssvarano, 0);
              $pdf1->descr4_1 = $k00_numpar.'a PARCELA   -   Alíquota '.$q05_aliq.'%     EXERCÍCIO : '.$q05_ano;
              //$pdf1->descr4_1   = $k00_numpar.'a PARCELA   -   Alíquota '.pg_result(pg_exec($sqlaliq),"q05_aliq").'%     EXERCÍCIO : '.pg_result(pg_exec($sqlaliq),"q05_ano");
            }
            $pdf1->titulo7 = 'Valor Pago';
            $pdf1->titulo15 = 'Valor Pago';
            $pdf1->titulo13 = 'Valor da Receita Tributável';
          } else {
            $pdf1->descr15 = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor);
            $pdf1->valtotal = $k00_valor;
            $pdf1->descr7 = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor); 
            $pdf1->predescr7 = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor); 
          }
          $pdf1->descr12_2 = '- PARCELA ÚNICA COM '.$k00_percdes.'% DE DESCONTO';
          $pdf1->linha_digitavel = $linha_digitavel;
          $pdf1->codigo_barras = $codigo_barras;
          //    debug($pdf1);exit;
          $pdf1->imprime();
        }
      }
      $unica = 2;
      if ($sounica == '') {
        $pdf1->objpdf->Output();
        exit;
      }
    }
/******************************************************** FIM PARCELA UNICA ************************************************************************/


    //die("select fc_numbco($k00_codbco,'$k00_codage')");
    if ($k00_codbco == "" || $k00_codage == "") {
      $errobco = "Código do banco e ou agência zerado ou nulo!";
      db_redireciona("db_erros.php?fechar=true&db_erro=Verifique cadastro do tipo de débito - $tipo_debito <br> $errobco");
      //.$tipo_debito."\n $errobco");
      exit;
    }
    $result = pg_exec("select fc_numbco($k00_codbco,'$k00_codage')");
    $intnumrows = pg_numrows($result);
    db_fieldsmemory($result, 0);

    $valores = split("P", $numpres[$volta]);
    $k00_numpre = $valores[0];
    $k00_numpar = split("R", $valores[1]);
		$k00_numpar = $k00_numpar[0];
    $k03_anousu = $H_ANOUSU;
//    echo $k00_numpre.'  '.$k00_numpar.'  '.$H_DATAUSU.'  '.$H_ANOUSU;exit;
//    die($k00_numpre." - ".$k00_numpar." - ".date('Y-m-d',$H_DATAUSU)." - ".$H_ANOUSU);

    $DadosPagamento = debitos_numpre_carne($k00_numpre, $k00_numpar, $H_DATAUSU, $H_ANOUSU);

//    db_criatabela($DadosPagamento);exit;
    db_fieldsmemory($DadosPagamento, 0);
		$total += $taxabancaria;

//    db_msgbox(date("Y-m-d",$H_DATAUSU)."<<<>>>".date("Y-m-d",db_getsession('DB_datausu')));

    $sql1 = "select k00_dtvenc as datavencimento,
                    k00_dtvenc,
                    k00_numtot,
             		    k00_dtoper
               from arrecad 
               where k00_numpre = $k00_numpre 
                 and k00_numpar = $k00_numpar 
               limit 1";
    db_fieldsmemory(pg_exec($sql1), 0);
    $k00_dtvenc = db_formatar($k00_dtvenc, 'd');
    $pdf1->data_processamento = db_formatar($k00_dtoper,'d');
    $resultnumbco = pg_exec("select numbanco, segmento, formvencfebraban from db_config where codigo = ".db_getsession("DB_instit"));
    db_fieldsmemory($resultnumbco, 0); // deve ser tirado do db_config

    // alterei para buscar o terceiro digito pelo tipo de debito da tabela arretipo
    $sqlvalor = "select k00_impval,k00_tercdigcarnenormal from arretipo where k00_tipo = $tipo_debito";
    db_fieldsmemory(pg_exec($sqlvalor), 0);

    $comvalor = 't';
    if (!isset ($k00_tercdigcarnenormal) || $k00_tercdigcarnenormal == "") {
      db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
    }
    $tipoconvenio = "8".$segmento.$k00_tercdigcarnenormal; //"6";
    $ss = $ninfla;
    
    if ($k00_impval == 't') {
      if($k03_tipo == 3){
        $rsAnoissvar  = pg_query("select q05_ano from issvar where q05_numpre = $k00_numpre"); 
        $intAnoissvar = pg_numrows($rsAnoissvar);
				db_fieldsmemory($rsAnoissvar,0);
      if($intAnoissvar > 0 && $q05_ano <= date("Y", $H_DATAUSU)){
          $k00_valor = $total;
          $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
          $ninfla = '';
        }
      }else{       
        $k00_valor = $total;
        $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
				$ninfla_ant = $ninfla;
        $ninfla = '';
        if (($total == 0) || (substr($k00_dtvenc, 6, 4) > date("Y", $H_DATAUSU)+1)) {
					if ($ninfla_ant == "REAL") {
            $tipoconvenio = "8".$segmento."6";
						$vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
						$comvalor = 't';
					} else {
            $tipoconvenio = "8".$segmento."7";
						$vlrbar = "00000000000";
						$comvalor = 'f';
						if ($total != 0) {
							$k00_valor = $qinfla;
							$ninfla = $ss;
						}
					}
        }
      }
    } else {
      $k00_valor = $qinfla;
      $comvalor = 'f';
      $tipoconvenio = "8".$segmento."7";
      $vlrbar = "00000000000";
    }

    $numpre = db_numpre($k00_numpre).db_formatar($k00_numpar, 's', "0", 3, "e");
    $dtvenc = substr($k00_dtvenc, 6, 4).substr($k00_dtvenc, 3, 2).substr($k00_dtvenc, 0, 2);
    $datavencimento = $dtvenc;

    if ($formvencfebraban == 1) {
      $hj = str_replace("-", "", date('Y-m-d', db_getsession('DB_datausu')));
      $tmpdt = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);
      if ($tmpdt > $datavencimento) {
           $datavencimento = $tmpdt;
      }
      $db_dtvenc = str_replace("-", "", $datavencimento);
      $vencbar = $db_dtvenc.'000000';
    }  elseif ($formvencfebraban == 2) {
      $hj = str_replace("-", "", date('Y-m-d', db_getsession('DB_datausu')));
      $tmpdt = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);
      if ($tmpdt > $datavencimento) {
        $datavencimento =  $tmpdt;
      }
      $db_dtvenc = str_replace("-", "", $datavencimento);
      $db_dtvenc = substr($db_dtvenc, 6, 2).substr($db_dtvenc, 4, 2).substr($db_dtvenc, 2, 2);
      $vencbar = $db_dtvenc.'00000000';
    }

/* coloquei $k47_tipoconvenio == 2 nesse if para q naun imprima parcela com quantidade de inflator qndo o tipo de convenio for cobranca*/
    //if (isset ($emiscarneiframe) && $emiscarneiframe == 'n' || $k47_tipoconvenio == 2) {
    if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {
      if (substr($vencbar, 0, 4) > db_getsession('DB_anousu')) {
        continue;
      }
    }

    if(isset($k47_tipoconvenio) && $k47_tipoconvenio == 2){ 
			if (substr($vencbar, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0) {
          $k00_valor = 0;
          $especie   = $ninfla;
					$histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
			}else{
          $especie   = 'R$';
					$histinf   = "";
			}

      if($dtvenc < date('Ymd',db_getsession('DB_datausu'))){
				    $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;					
			      $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
			}else{
				    $msgvencida = "";
			}		
			$resultcod  = pg_exec("select * from fc_fichacompensacao($k22_cadban,$k00_numpre,$k00_numpar,to_date('".$k00_dtvenc."','dd/mm/yyyy'),$k00_valor)"); 
      db_fieldsmemory($resultcod, 0);
			if(isset($qinfla) && $qinfla != '' && $k00_valor == 0){
				     $k00_valor = $qinfla;				   				
			}
      if($erro == 'f'){
        $codigo_barras   = $codigobarras;
        $linha_digitavel = $linhadigitavel;
      }else{
        db_msgbox("Erro na configuração do banco : \n".$mensagem);
        exit;
      }
    }else{
      $resultcod = pg_exec("select fc_febraban('$tipoconvenio'||'$vlrbar'||'$numbanco'||$vencbar||'$numpre')");
      db_fieldsmemory($resultcod, 0);
      if ($fc_febraban == "") {
        db_msgbox("Erro ao gerar codigo de barras (3)!");
        exit;
      }
      $codigo_barras = substr($fc_febraban, 0, strpos($fc_febraban, ','));
      $linha_digitavel = substr($fc_febraban, strpos($fc_febraban, ',') + 1);
    }
    
    $result = pg_exec("select k15_local,
                              k15_aceite,
                              k15_carte,
                  			      k15_codage,
                              k15_espec,
                              k15_ceden1, 
                              k15_seq1,
                              k15_ageced
                         from cadban
                          where k15_codbco = $k00_codbco 
                            and k15_codage = '$k00_codage'");

    if(isset($k47_tipoconvenio) && $k47_tipoconvenio == 2){
       //select na cadban para sobrescrever o codigo do select acima, no caso do $k47_tipoconvenio == 2 q eh cobranca
       $result = pg_exec("select k15_local,
                                 k15_aceite,
                                 k15_carte,
                                 k15_codage,
                                 k15_espec,
                                 k15_ceden1,
                                 k15_seq1,
                                 k15_ageced
                            from cadban 
			  where k15_codigo = $k22_cadban ");
//     db_criatabela($result);exit;
    } 
   

    if (pg_numrows($result) > 0) {
      db_fieldsmemory($result, 0);
      $agen  = substr($k15_codage,0,strlen($k15_codage)-1)."-". substr($k15_codage,strlen($k15_codage)-1,1);
      $conta = substr($k15_ceden1,0,strlen($k15_ceden1)-1)."-". substr($k15_ceden1,strlen($k15_ceden1)-1,1);
      $pdf1->agencia_cedente = $agen." / ".$conta; // agencia / conta
      $pdf1->carteira = $k15_carte."-".str_pad($k15_seq1.db_CalculaDV($k15_seq1, 11),3,"0",'STR_PAD_LEFT');
      $fc_numbco = $fc_numbco;
      $dt_hoje = date('d/m/Y', $H_DATAUSU);
    }
    $numpre = db_sqlformatar($k00_numpre, 8, '0').'000999';
    $numpre = $numpre.db_CalculaDV($numpre, 11);

    $numbanco = $fc_numbco;
    global $pdf;
    $pdf1->descr12_2 = '';
    $pdf1->titulo1 = $descr;
    $pdf1->descr1 = $numero;
    $pdf1->descr2 = db_numpre($k00_numpre, 0).db_formatar($k00_numpar, 's', "0", 3, "e");
    
    $pdf1->tipo_exerc      = "$k00_tipo / ".substr($k00_dtoper,0,4);

    /************  P E G A   A S   R E C E I T A S   C O M   O S   V A L O R E S  *****************/
    $sqlReceitas = "select k00_receit as codreceita,
                           k02_descr  as descrreceita,
                           case when taborc.k02_codigo is not null then k02_codrec
                                when tabplan.k02_codigo is not null then k02_reduz 
                           end  as reduzreceita,
                           k00_valor  as valreceita
                      from arrecad 
                           inner join tabrec on tabrec.k02_codigo = arrecad.k00_receit 
                           left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo
                                             and taborc.k02_anousu   = ".db_getsession('DB_anousu')."
                           left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo
                                             and tabplan.k02_anousu  = ".db_getsession('DB_anousu')."
                    where k00_numpre = $k00_numpre 
                      and k00_numpar = $k00_numpar ";
    //die($sqlReceitas);
    $rsReceitas = pg_query($sqlReceitas);

    $intnumrows = pg_num_rows($rsReceitas);
    for ($x = 0; $x < $intnumrows; $x ++) {
      db_fieldsmemory($rsReceitas, $x);
      $pdf1->arraycodreceitas[$x]   = $codreceita;
      $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
      $pdf1->arraydescrreceitas[$x] = $descrreceita;
      $pdf1->arrayvalreceitas[$x]   = $valreceita;
    }

    if(isset($vlrjuros) && $vlrjuros != "" && $vlrjuros !=0){
//      $x++;
      $pdf1->arraycodreceitas[$x]   = "";
      $pdf1->arrayreduzreceitas[$x] = "";
      $pdf1->arraydescrreceitas[$x] = "Juros : ";
      $pdf1->arrayvalreceitas[$x]   = $vlrjuros;
    }
    if(isset($vlrmulta) && $vlrmulta != "" && $vlrmulta != 0){
      $x++;
      $pdf1->arraycodreceitas[$x]   = "";
      $pdf1->arrayreduzreceitas[$x] = "";
      $pdf1->arraydescrreceitas[$x] = "Multa : ";
      $pdf1->arrayvalreceitas[$x]   = $vlrmulta;
    }



    /***********************************************************************************************/
//    die("bairro ".$z01_numcgm);

    if (!empty ($HTTP_POST_VARS["ver_matric"])) {
      $pdf1->descr11_1  = $z01_cgmpri." - ".$proprietario;
      $pdf1->descr11_2  = $xender;
      $pdf1->descr11_3  = $xbairro;
      $pdf1->munic      = $j43_munic;
      $pdf1->premunic      = $j43_munic;
      $pdf1->uf         = $z01_uf;
      $pdf1->descr3_1   = $z01_cgmpri." - ".$proprietario;
      $pdf1->descr3_2   = $xender;
      $pdf1->predescr3_1   = $z01_cgmpri." - ".$proprietario;
      $pdf1->predescr3_2   = $xender;
      $pdf1->descr3_3   = $xbairro;
      $pdf1->descr17    = $bql; //variavel q guarda o setor/quadra/lote
      $pdf1->tipoinscr  = 'Matricula';
      $pdf1->nrinscr    =  $j01_matric;
      $pdf1->tipolograd = 'Rua ';
      $pdf1->pretipolograd = 'Rua ';
      $pdf1->cep        = $z01_cep;
      $pdf1->precep        = $z01_cep;
      $pdf1->nomepri    = $z01_ender;
      $pdf1->prenomepri    = $z01_ender;
      $pdf1->nrpri      = $j43_numimo;
      $pdf1->complpri   = $j43_comple;
      $pdf1->prenrpri      = $j43_numimo;
      $pdf1->precomplpri   = $j43_comple;
    } else {
      $pdf1->descr11_1  = $z01_numcgm." - ".$z01_nome;
      $pdf1->descr11_2  = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
      $pdf1->descr11_3  = $z01_bairro;
      $pdf1->descr3_1   = $z01_numcgm." - ".$z01_nome;
      $pdf1->descr3_2   = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
      $pdf1->descr3_3   = $z01_bairro;
      $pdf1->cep        = $z01_cep;
      $pdf1->precep        = $z01_cep;
      $pdf1->uf         = $z01_uf;
      $pdf1->tipoinscr  = 'Cgm';
      $pdf1->nrinscr    =  $z01_numcgm;
      $pdf1->munic      = $z01_munic;
      $pdf1->premunic      = $z01_munic;
      $pdf1->tipolograd = 'Rua ';
      $pdf1->pretipolograd = 'Rua ';
      $pdf1->nomepri    = $z01_ender;
      $pdf1->prenomepri    = $z01_ender;
    }

//    die($pdf1->tipoinscr." - ".$pdf1->nrinscr);

    if ($k00_hist1 == '' || $k00_hist2 == '') {
      $pdf1->descr4_1 = $k00_numpar.'a PARCELA';
      $pdf1->historicoparcela = $k00_numpar.'a PARCELA';
      $pdf1->prehistoricoparcela = $k00_numpar.'a PARCELA';
      if ($k03_tipo == 16) {
        $sqldiversos = "select distinct dv05_obs 
                               from termo 
                         inner join termodiver on dv10_parcel = v07_parcel 
                       inner join diversos on dv05_coddiver = dv10_coddiver 
                     where v07_numpre = $k00_numpre";
        //    die($sqldiversos);
        $resultdiversos = pg_exec($sqldiversos);
        if (pg_numrows($resultdiversos) > 0) {
          db_fieldsmemory($resultdiversos, 0, true);
          $pdf1->descr4_2 = substr($dv05_obs, 0, 100);
          $pdf1->predescr4_2 = substr($dv05_obs, 0, 100);
					$obsdiver = substr($dv05_obs, 0, 100);
        }
      } else if ($k03_tipo == 7) {
        $sqldiversos = "select distinct dv05_obs from diversos where dv05_numpre = $k00_numpre";
        $resultdiversos = pg_exec($sqldiversos);
        if (pg_numrows($resultdiversos) > 0) {
          db_fieldsmemory($resultdiversos, 0, true);
          $pdf1->descr4_2 = substr($dv05_obs, 0, 100);
          $pdf1->predescr4_2 = substr($dv05_obs, 0, 100);
					$obsdiver = substr($dv05_obs, 0, 100);
        }
      }
    }else{
      if (isset ($k00_hist1) && $k00_hist1 != "" && $k00_hist1 != ".") {
        $pdf1->descr4_1 = $k00_hist1;
      }
      if (isset ($k00_hist2) && $k00_hist2 != "" && $k00_hist2 != ".") {
        $pdf1->descr4_2 = $k00_hist2;
        $pdf1->predescr4_2 = $k00_hist2;
      }
    }
    /**************  SE FOR CARNE DE VISTORIAS PEGA OS DADOS DA VISTORIA  **************/
    $sqlvistorias = " select y77_descricao from vistorianumpre 
                                 inner join vistorias     on vistorias.y70_codvist  = vistorianumpre.y69_codvist
                                 inner join tipovistorias on vistorias.y70_tipovist = tipovistorias.y77_codtipo
                        where y69_numpre = $k00_numpre";
    $rsvistorias = pg_exec($sqlvistorias);
    /***********************************************************************************/
    if (pg_numrows($rsvistorias) > 0) {
      db_fieldsmemory($rsvistorias, 0);
      $pdf1->tipodebito = $y77_descricao." - ".db_getsession('DB_anousu');
      $pdf1->pretipodebito = $y77_descricao." - ".db_getsession('DB_anousu');
    }

    if (isset ($obs)) {
      $pdf1->titulo13 = 'Observação';
      $pdf1->descr13 = $obs;
    }
    if ($k03_tipo == 2) {
      $pdf1->titulo4 = 'Atividade';
      $pdf1->descr4_1 = '- '.$q07_ativ.'-'.$q03_descr;
      $pdf1->titulo13 = 'Atividade';
      $pdf1->descr13 = $q07_ativ;
    } else if (($k03_tipo == 6) || ($k03_tipo == 13)) {
        $pdf1->titulo4 = 'Parcelamento';
        $pdf1->descr4_1 = '- '.$v07_parcel.$exercicio;
        $pdf1->titulo13 = 'Parcelamento';
        $pdf1->descr13 = $v07_parcel;
    }
    $pdf1->descr5 = $k00_numpar.' / '.$k00_numtot;
    
    $tmpdta    = split("/",$k00_dtvenc);
    $tmpdtvenc = $tmpdta[2]."-".$tmpdta[1]."-".$tmpdta[0];
    if($db_datausu > $tmpdtvenc){
        $pdf1->dtparapag    = db_formatar($db_datausu,'d');
        $pdf1->datacalc    = db_formatar($db_datausu,'d');
        $pdf1->predatacalc    = db_formatar($db_datausu,'d');
        $pdf1->confirmdtpag = 't';
    }else{
        $pdf1->dtparapag    = $k00_dtvenc;
        $pdf1->datacalc     = $k00_dtvenc;
        $pdf1->predatacalc     = $k00_dtvenc;
        $pdf1->confirmdtpag = 't';
    }
    $pdf1->descr6 = $k00_dtvenc;
    $pdf1->predescr6 = $k00_dtvenc;
    
    $pdf1->titulo8 = $descr;
    $pdf1->pretitulo8 = $descr;
    $pdf1->descr8  = $numero;
    $pdf1->predescr8  = $numero;
    $pdf1->descr9  = db_numpre($k00_numpre, 0).db_formatar($k00_numpar, 's', "0", 3, "e");
    $pdf1->predescr9  = db_numpre($k00_numpre, 0).db_formatar($k00_numpar, 's', "0", 3, "e");
//    die($pdf1->descr9);
    $pdf1->descr10 = $k00_numpar.' / '.$k00_numtot;
    $pdf1->descr14 = $k00_dtvenc;
    if ($total == 0) {
      //////////// ISSQN VARIAVEL ///////////  
      if ($k03_tipo == 3) {
        $sqlaliq = "select q05_aliq,q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
        $rsIssvarano = pg_exec($sqlaliq);
        $intNumrows = pg_numrows($rsIssvarano);
        if ($intNumrows == 0) {
          db_redireciona('db_erros.php?fechar=true&db_erro=Ano não encontrado na tabela issvar. Contate o suporte');
          exit;
        }
        db_fieldsmemory($rsIssvarano, 0);
        $pdf1->descr4_1 = $k00_numpar.'a PARCELA   -   Alíquota '.$q05_aliq.'%     EXERCÍCIO : '.$q05_ano;
      }
      $pdf1->titulo7 = 'Valor Pago';
      $pdf1->titulo15 = 'Valor Pago';
      $pdf1->titulo13 = 'Valor da Receita Tributável';
      $pdf1->descr15 = '';
      $pdf1->valtotal='';
      $pdf1->descr7 = '';
      $pdf1->predescr7 = '';
    } else {

      // desativado em sapiranga pois eles precisam que os carnes de valores de anos posteriores em inflator urm e nao real...
      // $pdf1->descr15   = db_formatar($k00_valor,'f');// ($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor);
      // $pdf1->descr7    = db_formatar($k00_valor,'f');// ($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor); 

      $pdf1->descr15 = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
	//	echo($k00_valor);
      $pdf1->valtotal = db_formatar($k00_valor, 'f'); //$k00_valor;
  //	die($pdf1->valtotal);
      $pdf1->descr7 = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
      $pdf1->predescr7 = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);

    }
//  die($pdf1->tipodebito."\n".$bql."\n".$obsdiver."\n".substr($k00_dtoper,0,4).date('Y',$k00_dtoper)); 
    if(isset($k47_tipoconvenio) && $k47_tipoconvenio == 2){
       $pdf1->descr12_1 .= $pdf1->tipodebito."\n".
                           $pdf1->titulo1." - ".$pdf1->descr1." / ".
                           $pdf1->titulo4." ".$pdf1->descr4_1." Parcela - ".
                           $k00_numpar."/".$k00_numtot."\n".
                           (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
                           (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
       $pdf1->predescr12_1 .= $pdf1->pretipodebito."\n".
                           $pdf1->titulo1." - ".$pdf1->descr1." / ".
                           $pdf1->titulo4." ".$pdf1->descr4_1." Parcela - ".
                           $k00_numpar."/".$k00_numtot."\n".
                           (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
                           (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
    }

    $rsmsgcarne = pg_query("select k03_msgcarne, 
                                   k03_msgbanco 
                              from numpref 
                             where k03_anousu = ".db_getsession("DB_anousu"));
    if (pg_numrows($rsmsgcarne) > 0) {
      db_fieldsmemory($rsmsgcarne, 0);
    }
    if ($pagabanco == 't') {
      if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
        if (isset ($k00_msgparcvenc2) && $k00_msgparcvenc2 != "") {
          $pdf1->descr12_1 .= $k00_msgparcvenc2." ".$histinf." ".$msgvencida;
        }
      } else {
        if (isset ($k00_msgparc2) && $k00_msgparc2 != "") {
          $pdf1->descr12_1 .= $k00_msgparc2." ".$histinf." ".$msgvencida;
        } elseif (isset ($k03_msgbanco) && $k03_msgbanco != "") {
          $pdf1->descr12_1 .= $k03_msgbanco." Não aceitar após vencimento.";
        }
      }
    } else {
      if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
        $pdf1->descr12_1 .= $k00_msgparcvenc2." ".$histinf." ".$msgvencida;
      } elseif (isset ($k00_msgparc2) && $k00_msgparc2 != "") {
        $pdf1->descr12_1 .= $k00_msgparc2." ".$histinf." ".$msgvencida;
      } elseif (isset ($k03_msgbanco) && $k03_msgbanco != "") {
        $pdf1->descr12_1 .= $k03_msgbanco." Após o vencimento cobrar juros de 1%a.m e multa de 2%";
      } else {
        $pdf1->descr12_1 .= '- O PAGAMENTO DEVERÁ SER EFETUADO SOMENTE NA PREFEITURA.'." ".$histinf." ".$msgvencida;
      }
    }

    $sqlparag = "select db02_texto 
             from db_documento 
               inner join db_docparag on db03_docum = db04_docum 
               inner join db_paragrafo on db04_idparag = db02_idparag 
             where db03_docum = 27 
               and db02_descr ilike '%MENSAGEM CARNE%' 
             and db03_instit = ".db_getsession("DB_instit");
    $resparag = pg_query($sqlparag);

    if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
      if (isset ($k00_msgparcvenc) && $k00_msgparcvenc != "") {
        if (strlen($k00_msgparcvenc) > 50) {
          $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strrpos(substr($k00_msgparcvenc, 0, 50), ' '));
        } else {
          $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strlen($k00_msgparcvenc));
        }
        if (strlen($k00_msgparcvencvenc) > 100) {
          $part2 = substr(substr($k00_msgparcvenc, strlen($part1), 50), 0, strrpos(substr($k00_msgparcvenc, strlen($part1), strlen($k00_msgparcvenc)), ' '));
        } else {
          $part2 = substr(substr($k00_msgparcvenc, strlen($part1) + 1, 50), 0, strlen($k00_msgparcvenc));
        }
        if (strlen($k00_msgparcvenc) > 105) {
          $part3 = substr(substr($k00_msgparcvenc, strlen($part2), 50), 0, strlen($k00_msgparcvenc));
        }
        $pdf1->descr16_1 = $part1;
        $pdf1->descr16_2 = $part2;
        $pdf1->descr16_3 = $part3;
        $pdf1->predescr16_1 = $part1;
        $pdf1->predescr16_2 = $part2;
        $pdf1->predescr16_3 = $part3;
      }

    } elseif (isset ($k00_msgparc) && $k00_msgparc != "") {
      $pdf1->descr16_1 = substr($k00_msgparc, 0, 50);
      $pdf1->descr16_2 = substr($k00_msgparc, 50, 50);
      $pdf1->descr16_3 = substr($k00_msgparc, 100, 50);
      $pdf1->predescr16_1 = substr($k00_msgparc, 0, 50);
      $pdf1->predescr16_2 = substr($k00_msgparc, 50, 50);
      $pdf1->predescr16_3 = substr($k00_msgparc, 100, 50);
    } else {
      if (isset ($k03_msgcarne) && $k03_msgcarne != "") {
        $pdf1->descr16_1 = substr($k03_msgcarne, 0, 50);
        $pdf1->descr16_2 = substr($k03_msgcarne, 50, 50);
        $pdf1->descr16_3 = substr($k03_msgcarne, 100, 50);
        $pdf1->predescr16_1 = substr($k03_msgcarne, 0, 50);
        $pdf1->predescr16_2 = substr($k03_msgcarne, 50, 50);
        $pdf1->predescr16_3 = substr($k03_msgcarne, 100, 50);
      } else {
        if (pg_numrows($resparag) == 0) {
          $db02_texto = "";
        } else {
          db_fieldsmemory($resparag, 0);
        }
        $pdf1->descr16_1 = "  ";
        $pdf1->descr16_1 = substr($db02_texto, 0, 55);
        $pdf1->descr16_2 = substr($db02_texto, 55, 55);
        $pdf1->descr16_3 = substr($db02_texto, 110, 55);
        $pdf1->predescr16_1 = substr($db02_texto, 0, 55);
        $pdf1->predescr16_2 = substr($db02_texto, 55, 55);
        $pdf1->predescr16_3 = substr($db02_texto, 110, 55);
      }
    }
    $pdf1->texto = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();
		$imprimircodbar=true;
		$sqltermo = "select k40_forma from termo
								inner join cadtipoparc on k40_codigo = v07_desconto
								where v07_numpre = $k00_numpre";
		$resulttermo = pg_exec($sqltermo) or die($sqltermo);
		if (pg_numrows($resulttermo) > 0) {
			db_fieldsmemory($resulttermo, 0);
			if ($k40_forma == 2 and $k00_numpar == $k00_numtot) {
				$imprimircodbar=false;
			}
		}
		if ($imprimircodbar == true) {
      $pdf1->linha_digitavel = $linha_digitavel;
      $pdf1->codigo_barras = $codigo_barras;
		} else {
      $pdf1->linha_digitavel = null;
      $pdf1->codigo_barras = null;
		}
    db_sel_instit();
    $pdf1->enderpref = $ender;
    $pdf1->municpref = $munic;
    $pdf1->telefpref = $email;
    $pdf1->emailpref = $telef;
//	die($z01_munic);
    @$pdf1->especie = @$especie;

//  debug($pdf1);exit;
    $pdf1->imprime();
    $pdf1->descr12_1 = '';
  }
  pg_exec("COMMIT");
  $pdf1->objpdf->Output();
}
// 4 - carne ficha de compensacao
// 33 - pre-impresso bagea
// 1 ou 2 normal
?>