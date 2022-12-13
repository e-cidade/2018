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


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($erropdf)){
  $HTTP_POST_VARS["CHECK1"] = $CHECK1;
  @$HTTP_POST_VARS["ver_inscr"] = $ver_inscr;
  @$HTTP_POST_VARS["ver_numcgm"] = $ver_numcgm;
}

require("libs/db_barras.php");
require("libs/db_utils.php");
include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
include("classes/db_db_bancos_classe.php");
include("model/regraEmissao.model.php");
include("model/convenio.model.php");


db_postmemory($HTTP_POST_VARS);

if(isset($carnevariavel)) {

  $sqlvariavel = "select arrecad.k00_numpre, arrecad.k00_numpar from issvar inner join arreinscr on arreinscr.k00_numpre = issvar.q05_numpre inner join arrecad on arrecad.k00_numpre = issvar.q05_numpre and arrecad.k00_numpar = issvar.q05_numpar where issvar.q05_ano = $ano and issvar.q05_mes = $mes and arreinscr.k00_inscr = $inscricao";
  $result = db_query($sqlvariavel);

  if (pg_numrows($result) > 0) {
  	
    db_fieldsmemory($result,0);
  
    $tipo = '3';
    $tipo_debito = '3';
    $ver_matric = '';
    $ver_inscr = $inscricao;
    $ver_numcgm = '';
    $numpre_unica = '';
    $CHECK0 = $k00_numpre . "P" . $k00_numpar;
    
  }

}

$cldb_bancos = new cl_db_bancos();

try {
   $oRegraEmissao = new regraEmissao($tipo,5,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'));
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}


if(!isset($emite_recibo_protocolo)){

  db_query("BEGIN");
  $result = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
  db_fieldsmemory($result,0);
  //pega os numpres da ca3_gerfinanc002.php, separa e insere em db_reciboweb
  $result = db_query(" select k00_codbco,
                              k00_codage,
                              k00_descr,
                              k00_hist1,
                              k00_hist2,
                              k00_hist3,
                              k00_hist4,
                              k00_hist5,
                              k00_hist6,
                              k00_hist7,
                              k00_hist8 
                         from arretipo 
                        where k00_tipo = $tipo");

  db_fieldsmemory($result,0);
 // $k00_descr = $k00_descr." - ".db_getsession("DB_anousu");
  $k00_descr = "ISSQN RET NA FONTE / ISSQN VARIÁVEL ";
  
  
  $vt = $HTTP_POST_VARS;
  if(!isset($numpre_unica) || $numpre_unica ==""){
    $tam = sizeof($vt);
    reset($vt);
    $numpres = "";
    for($i = 0;$i < $tam;$i++) {
      if(db_indexOf(key($vt) ,"CHECK") > 0)
        $numpres .= "N".$vt[key($vt)];  
        next($vt);
    }
    if (isset($carnevariavel)) {
       $numpres .= "N" . $CHECK0;
    }

    $numpres = split("N",$numpres); 
    for($i = 1;$i < sizeof($numpres);$i++) {
      $valores = split("P",$numpres[$i]);  
     
      $sql = "insert into db_reciboweb values(".$valores[0].",".$valores[1].",$k03_numpre,$k00_codbco,'$k00_codage','{$oRegraEmissao->getCodConvenioCobranca()}')";
      db_query($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage());
      //echo $sql . "<br>";
    }
  }else{
      $sql = "insert into db_reciboweb values(".$numpre_unica.",0,$k03_numpre,$k00_codbco,'$k00_codage','{$oRegraEmissao->getCodConvenioCobranca()}')";
      db_query($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage());
  }
 // echo($sql);
  //roda funcao fc_recibo pra gerar o recibo
	
	
  if(isset($dtpaga)){
    $sql = "select fc_recibo($k03_numpre,'".$dtpaga."','".$dtpaga."',".db_getsession("DB_anousu").")";
  }else{
    $sql = "select fc_recibo($k03_numpre,'".date("Y-m-d",db_getsession("DB_datausu"))."','".db_vencimento()."',".db_getsession("DB_anousu").")";
  }
  
 // die ($sql);
 
  $Recibo = db_query($sql) or die("$sql");
db_query("COMMIT");	

}

if(!isset($emite_recibo_protocolo)){
  $sql = "select r.k00_numcgm,r.k00_receit,t.k02_descr,t.k02_drecei,to_char(r.k00_dtoper,'DD-MM-YYYY') as k00_dtoper,to_char(r.k00_dtvenc,'DD-MM-YYYY') as k00_dtvenc,sum(r.k00_valor) as valor
                   from recibopaga r
                   inner join tabrec t on t.k02_codigo = r.k00_receit 
                   inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
                   where r.k00_numnov = ".$k03_numpre."
                   group by r.k00_dtoper,r.k00_dtvenc,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_numcgm";
}else{
  $sql = "select r.k00_numcgm,r.k00_receit,t.k02_descr,t.k02_drecei,to_char(r.k00_dtoper,'DD-MM-YYYY') as k00_dtoper,to_char(r.k00_dtvenc,'DD-MM-YYYY') as k00_dtvenc,sum(r.k00_valor) as valor
                   from recibo r
                   inner join tabrec t on t.k02_codigo = r.k00_receit 
                   inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
                   where r.k00_numpre = ".$k03_numpre."
                   group by r.k00_dtoper,r.k00_dtvenc,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_numcgm";
}
//echo $sql;exit;
$DadosPagamento = db_query($sql);
$linhasDadosPagamento = pg_num_rows($DadosPagamento);
//echo "$sql linha $linhasDadosPagamento = ";
//faz um somatorio do valor
if ($linhasDadosPagamento == 1) {
  $datavencimento = pg_result($DadosPagamento,0,"k00_dtvenc");
} elseif ($linhasDadosPagamento > 1) {
  $datavencimento = pg_result($DadosPagamento,0,"k00_dtoper");
} else {
//	db_msgbox("teste");
	//exit;
}

$datavcto = substr($datavencimento,6,4).substr($datavencimento,3,2).substr($datavencimento,0,2);
$datasessao = substr(date('d/m/Y',db_getsession("DB_datausu")),6,4).substr(date('d/m/Y',db_getsession("DB_datausu")),3,2).substr(date('d/m/Y',db_getsession("DB_datausu")),0,2);
if ($datavcto <= $datasessao) {
    $datavencimento = date('Y-m-d',db_getsession("DB_datausu"));
   //echo " datavencimento = $datavencimento";
 
}else{
  $datavencimento = substr($datavencimento,6,4)."-".substr($datavencimento,3,2)."-".substr($datavencimento,0,2);
 
}
  
$total_recibo = 0;
for($i = 0;$i < pg_numrows($DadosPagamento);$i++) {
  $total_recibo += pg_result($DadosPagamento,$i,"valor");
}
//seleciona da tabela db_config, o numero do banco e a taxa bancaria e concatena em variavel    //codigo = ".db_getsession("DB_instit")
$DadosInstit = db_query("select nomeinst,ender,munic,email,telef,cgc,uf,logo,to_char(tx_banc,'9.99') as tx_banc,numbanco from db_config where codigo = ".db_getsession('DB_instit'));
//cria codigo de barras e linha digitável
$NumBanco = pg_result($DadosInstit,0,"numbanco");
$db_numbanco = pg_result($DadosInstit,0,"numbanco");
$taxabancaria = pg_result($DadosInstit,0,"tx_banc");
$src = pg_result($DadosInstit,0,'logo');
$db_nomeinst = pg_result($DadosInstit,0,'nomeinst');
$db_ender    = pg_result($DadosInstit,0,'ender');
$db_munic    = pg_result($DadosInstit,0,'munic');
$db_uf       = pg_result($DadosInstit,0,'uf');
$db_telef    = pg_result($DadosInstit,0,'telef');
$db_cgc      = pg_result($DadosInstit,0,'cgc');
$db_email    = pg_result($DadosInstit,0,'email');
$total_recibo += $taxabancaria;
$valor_parm = $total_recibo; 
//seleciona dados de identificacao. Verifica se é inscr ou matric e da o respectivo select
//essa variavel vem do cai3_gerfinanc002.php, pelo window open, criada por parse_str
$tipo_chave = "";
if(!empty($HTTP_POST_VARS["ver_matric"])) {
  $numero = $HTTP_POST_VARS["ver_matric"];
  $Identificacao = db_query("select z01_nome,z01_cgccpf,z01_ender,z01_munic,z01_uf,z01_cep,nomepri,j39_compl,j39_numero,j13_descr,j34_setor||'.'||j34_quadra||'.'||j34_lote as sql
                            from proprietario
                            where j01_matric = $numero limit 1");
  db_fieldsmemory($Identificacao,0);
  $tipo_chave = "Matrícula";
} else if(!empty($HTTP_POST_VARS["ver_inscr"])) {
  $numero = $HTTP_POST_VARS["ver_inscr"];
  $Identificacao = db_query("select z01_nome,
                                   z01_cgccpf,
                                   z01_ender,
                                   z01_munic,
                                   z01_uf,
                                   z01_cep,
                                   z01_ender as nomepri,
                                   z01_compl as j39_compl,
                                   z01_numero as j39_numero,
                                   z01_bairro as j13_descr,
                                   '' as sql
                            from empresa
                            where q02_inscr = $numero");
  db_fieldsmemory($Identificacao,0);
  $tipo_chave = "Inscrição";
}else if(!empty($HTTP_POST_VARS["ver_numcgm"])) {
  $numero = $HTTP_POST_VARS["ver_numcgm"];
  $Identificacao = db_query("select z01_nome,z01_cgccpf,z01_ender,z01_munic,z01_uf,z01_cep,''::bpchar as nomepri,''::bpchar as j39_compl,''::bpchar as j39_numero,z01_bairro as j13_descr, '' as sql
                            from cgm
                            where z01_numcgm = ".$HTTP_POST_VARS["ver_numcgm"]);
  db_fieldsmemory($Identificacao,0);
  $tipo_chave = "Numcgm";
} else {
  if(isset($emite_recibo_protocolo)){
    $Identificacao = db_query("
            select cgm.z01_nome,cgm.z01_ender,cgm.z01_munic,cgm.z01_uf,cgm.z01_cep,' ' as nomepri,' ' as j39_compl, ' ' as j39_numero, ' ' as j13_descr, '' as sql
            from recibo r
                 inner join cgm c on c.z01_numcgm = r.k00_numcgm
                       where r.k00_numpre = ".$k03_numpre."
            limit 1");
    db_fieldsmemory($Identificacao,0);
  }
}

$Observacoes = db_query($conn,"select mens,alinhamento from db_confmensagem where cod in('obsboleto1','obsboleto2','obsboleto3','obsboleto4')");
$db_vlrbar = db_formatar(str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
$db_numpre = db_numpre($k03_numpre).'000';

global $pdf;
$pdf = new scpdf();
$pdf->Open();
global $pdf;


$pdf1 = $oRegraEmissao->getObjPdf();

try {
  $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numpre,0,$total_recibo,$db_vlrbar,$datavencimento,'6');
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit; 
}

$codigobarras   = $oConvenio->getCodigoBarra();
$linhadigitavel = $oConvenio->getLinhaDigitavel();

$pdf1->tipo_convenio = $oConvenio->getTipoConvenio();
$pdf1->nosso_numero  = $oConvenio->getNossoNumero();

if( $oRegraEmissao->isCobranca() ){
	
  $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
  $pdf1->carteira        = $oConvenio->getCarteira();
  $pdf1->especie         = "R$";
}


$numpre = db_sqlformatar($k03_numpre,8,'0').'000999';
$numpre = $numpre . db_CalculaDV($numpre,11);

if(isset($tipo)){

  $resulttipo = db_query("select k03_tipo from arretipo where k00_tipo = $tipo");
  db_fieldsmemory($resulttipo,0);

  if($k03_tipo==5 ){ 
    $histparcela = "Divida: ";
    $sqlhist = "select distinct v01_exerc,v01_numpar
                from db_reciboweb
                             left outer join divida on v01_numpre = k99_numpre and v01_numpar = k99_numpar
                where k99_numpre_n = $k03_numpre 
                        group by v01_exerc,v01_numpar
                        order by v01_exerc,v01_numpar";
    $result = db_query($sqlhist);
        if(pg_numrows($result)!=false){
          $exercv = "0000";
            for($xy=0;$xy<pg_numrows($result);$xy++){
            if( $exercv != pg_result($result,$xy,0)){
               $exercv = pg_result($result,$xy,0);
           $histparcela .= pg_result($result,$xy,0).":";
                }
        $histparcela .= pg_result($result,$xy,1)."-";
          }        
    }
    
  }else if($k03_tipo == 3 || $k03_tipo == 2){
        $histparcela = "Exercicio: ";
        $sqlhist = "select distinct q05_ano,q05_numpar
                     from db_reciboweb
                     left outer join issvar on q05_numpre = k99_numpre and q05_numpar = k99_numpar
                    where k99_numpre_n = $k03_numpre
                    group by q05_ano,q05_numpar
                    order by q05_ano,q05_numpar";
        $result = db_query($sqlhist);
        if(pg_numrows($result)!=false){
            $exercv = "0000";
            for($xy=0;$xy<pg_numrows($result);$xy++){
                if( $exercv != pg_result($result,$xy,0)){
                    $exercv = pg_result($result,$xy,0);
                    $histparcela .= "  ".pg_result($result,$xy,0).": Parc:";
                }
                $histparcela .= "-".pg_result($result,$xy,1);
            }
        }
        ////// ISSQN RETIDO
        //if( $tipo == 31){
            $str_sql = "select q21_cnpj, q21_nome, q21_nota, q21_valor,q21_tipolanc,q20_ano, q20_mes 
                          from issplanit
                         inner join issplan on q20_planilha = q21_planilha
                         inner join db_reciboweb on k99_numpre = q20_numpre
                         where k99_numpre_n = $k03_numpre and q21_valor <> 0 and q21_status = 1";
            $result = db_query($str_sql);
            $str_prestador = "";
            if(pg_numrows($result)!=false){
                $totalvalor_P = 0;
                //$totalnota_P  = 0;
                for($xy=0;$xy<pg_numrows($result);$xy++){
		                $q21_tipolanc = pg_result($result,$xy,4);
				            if($q21_tipolanc==1){
							        //TOMADO
							        $str_prestador .= str_pad(pg_result($result,$xy,0),20," ",STR_PAD_LEFT).";".pg_result($result,$xy,1)."; ".pg_result($result,$xy,2)."; ".db_formatar(pg_result($result,$xy,3),'f').";|";
							      }else{
							      //PRESTADO
                    // $totalnota_P += 1;
                    $valorpag = pg_result($result,$xy,3);
                    $totalvalor_P += $valorpag;
						      }
                }
                $ano = pg_result($result,0,5);
                $mes = pg_result($result,0,6);
                $histparcela = "Competência: $mes/$ano.";
            }
            //$arr_prestador = split("#", $str_prestador );
        //}
  }else if($k03_tipo==6 ){
    $histparcela = '';
    $parcelamento = '';
    $sqlhist = "select v07_parcel,k99_numpar
                from db_reciboweb
                 left outer join termo on v07_numpre = k99_numpre
                where k99_numpre_n = $k03_numpre 
                        order by v07_parcel,k99_numpar";
    $result = db_query($sqlhist);
    if(pg_numrows($result)!=false){
 //         $histparcela = "Parcelamento: ".pg_result($result,0,0)." Parc:";
          for($xy=0;$xy<pg_numrows($result);$xy++){
            if (pg_result($result,$xy,0) != $parcelamento){
               $histparcela .= ' Parc : '.pg_result($result,$xy,0)." - ";
            }
            $histparcela .= pg_result($result,$xy,1)." ";
            $parcelamento = pg_result($result,$xy,0);
        }        
    }
  }else{
    $histparcela = "PARCELAS: ";
    $sqlhist = "select k99_numpar
                from db_reciboweb
                where k99_numpre_n = $k03_numpre order by k99_numpar";
    $result = db_query($sqlhist);
    for($xy=0;$xy<pg_numrows($result);$xy++){
       $histparcela .= pg_result($result,$xy,0)." ";           
    }        
  }
}

$pdf1->logo                  = "logo_boleto.png";
$pdf1->prefeitura            = @$db_nomeinst;
$pdf1->enderpref             = @$db_ender;
$pdf1->municpref             = @$db_munic;
$pdf1->telefpref             = @$db_telef;
$pdf1->cgcpref               = @$db_cgc;
$pdf1->emailpref             = @$db_email;
$pdf1->nome                  = @$z01_nome;
$pdf1->cgccpf                = @$z01_cgccpf;
$pdf1->ender                 = @$z01_ender." ".@$j39_numero." ".@$j39_compl;
$pdf1->munic                 = @$z01_munic;
$pdf1->cep                   = @$z01_cep;
$pdf1->tipoinscr             = @$tipo_chave;
$pdf1->nrinscr               = @$numero;
$pdf1->ip                    = @$_SERVER['REMOTE_ADDR'];
$pdf1->nomepri               = @$nomepri;

$pdf1->nrpri                 = @$j39_numero;
$pdf1->complpri              = @$j39_compl;
$pdf1->bairropri             = @$j13_descr;
$pdf1->datacalc              = db_formatar(@$datavencimento,'d');
$pdf1->taxabanc              = db_formatar($taxabancaria,'f');
$pdf1->recorddadospagto      = @$DadosPagamento;
$pdf1->linhasdadospagto      = pg_numrows($DadosPagamento);
$pdf1->receita               = 'k00_receit';
$pdf1->dreceita              = 'k02_descr';
$pdf1->ddreceita             = 'k02_drecei';
$pdf1->valor                 = 'valor';
$pdf1->historico             = @$k00_descr;
$pdf1->histparcel            = @$histparcela;
$pdf1->prestador             = @$str_prestador;
$pdf1->totalvalor_P          = @$totalvalor_P;

//ficha
$pdf1->descr9                = str_pad($k03_numpre."000",11,0,STR_PAD_LEFT); // cod. de arrecadação
$pdf1->descr11_1             = @$z01_nome;
$pdf1->descr11_2             = @$z01_ender." ".@$j39_numero." ".@$j39_compl;
$pdf1->dtparapag             = db_formatar(@$datavencimento,'d');
$pdf1->descr10               = "1/1";

$pdf1->dtvenc                = db_formatar(@$datavencimento,'d');
$pdf1->quaisbancos           = "LOCAIS DE PAGAMENTO ATÉ O VENCIMENTO:\nAgências do BANCO DO BRASIL, BANRISUL, CAIXA ECONÔMICA FEDERAL e Rede Nacional de LOTÉRICAS.\nEM CASO DE PAGAMENTO VIA HOME-BANKING ESCOLHER A OPÇÃO ARRECADAÇÃO";
$pdf1->numpre                = @$numpre;
$pdf1->valtotal              = db_formatar(@$valor_parm,'f');
$pdf1->linhadigitavel        = @$linhadigitavel;
$pdf1->linha_digitavel       = @$linhadigitavel;
$pdf1->codigobarras          = @$codigobarras;
$pdf1->codigo_barras         = @$codigobarras;
$pdf1->tipo_exerc            = @$tipo."/".@$exercv;
//$pdf1->nosso_numero          = $iNossoNumero;
$pdf1->descr12_1             = @$k00_descr."\n\n".@$histparcela."\n\nCPF/CNPJ:".@$z01_cgccpf;
$pdf1->data_processamento    = date('d/m/Y',db_getsession('DB_datausu'));
$pdf1->desconto_abatimento   = '0,00';
$pdf1->mora_multa            = '0,00';
$pdf1->valor_cobrado         = db_formatar(@$valor_parm,'f');
 
$pdf1->texto                 = '';



if($oRegraEmissao->isCobranca()){
      
  $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
  $oBanco           = db_utils::fieldsMemory($rsConsultaBanco,0);  
  $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
  $pdf1->banco      = $oBanco->db90_abrev;
  
  try{
    $pdf1->imagemlogo = $oConvenio->getImagemBanco();
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
  }
  
}

$pdf1->imprime();
$pdf1->objpdf->output();

?>