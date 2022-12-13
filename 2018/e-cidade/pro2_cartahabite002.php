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

include(modification("libs/db_sql.php"));
include(modification("fpdf151/pdf4.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_libdocumento.php"));
include(modification("classes/db_obrashabite_classe.php"));
include(modification("classes/db_obrasender_classe.php"));
include(modification("classes/db_obraspropri_classe.php"));
include(modification("classes/db_obras_classe.php"));
include(modification("classes/db_obrastecnicos_classe.php"));
include(modification("classes/db_obrasalvara_classe.php"));
include(modification("classes/db_obraslote_classe.php"));
include(modification("classes/db_obraslotei_classe.php"));
include(modification("classes/db_obrashabiteprot_classe.php"));

$clobrashabite    = new cl_obrashabite;
$clobrasender     = new cl_obrasender;
$clobraspropri    = new cl_obraspropri;
$clobras          = new cl_obras;
$clobrastecnicos  = new cl_obrastecnicos;
$clobrasalvara    = new cl_obrasalvara;
$clobraslote      = new cl_obraslote;
$clobraslotei     = new cl_obraslotei;
$clobrashabiteprot= new cl_obrashabiteprot;

$oLibDocumento    = new libdocumento(1021);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

if(!isset($codigo) || $codigo==''){
  
  $sMsg = _M('tributario.projetos.pro2_cartahabite002.carta_nao_encontrada');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}else{
  $cod_hab=$codigo;
}
$borda   = 1; 
$bordat  = 1;
$preenc  = 0;
$TPagina = 57;
$xnumpre = '';

// funcao q busca todos os dados da instituicao(da sessao) e cria as variaveis em memoria mais informacoes libs/db_stdlib.php

db_sel_instit();

/***************************************************************************************************************************/


$sCamposHabite  = " obrashabite.*,                                                                                                ";
$sCamposHabite .= " obrasconstr.*,                                                                                                ";
$sCamposHabite .= " trim(ob09_logradcorresp)||','||ob09_numcorresp||','||ob09_compl||','||trim(ob09_bairrocorresp) as endcorresp, ";
$sCamposHabite .= " trim(z01_ender)||','||z01_numero||','||z01_compl||','||trim(z01_bairro)||','||trim(z01_munic)  as endcgm,     ";
$sCamposHabite .= " z01_nome,                                                                                                     ";
$sCamposHabite .= " ob01_codobra,                                                                                                 ";
$sCamposHabite .= " case                                                                                                          ";
$sCamposHabite .= "    when ob19_codproc is not null then ob19_codproc                                                            ";
$sCamposHabite .= "    when ob22_codproc is not null then ob22_codproc                                                            ";
$sCamposHabite .= " end as codproc,                                                                                               ";
$sCamposHabite .= " case                                                                                                          ";
$sCamposHabite .= "    when p58_dtproc is not null then p58_dtproc                                                                ";
$sCamposHabite .= "    when ob22_data  is not null then ob22_data                                                                 ";
$sCamposHabite .= " end as dtproc                                                                                                 ";

$result_obrashabite = $clobrashabite->sql_record($clobrashabite->sql_query($cod_hab,$sCamposHabite));

if($clobrashabite->numrows == 0){
  
  $oParms          = new stdClass();
  $oParms->iCodigo = $codigo;
  $sMsg = _M('tributario.projetos.pro2_cartahabite002.carta_codigo_nao_encontrada', $oParms);
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit; 
}

db_fieldsmemory($result_obrashabite,0);

$result_obrasender=$clobrasender->sql_record($clobrasender->sql_query_constr($ob08_codconstr,"ob07_numero,j13_descr,j14_nome,j31_descr"));
if($clobrasender->numrows>0){
  db_fieldsmemory($result_obrasender,0);
}

//técnico
$rsTecnico = $clobrastecnicos->sql_record($clobrastecnicos->sql_query(null,"z01_nome as tec, ob15_crea as tec_crea","","ob20_codobra = $ob01_codobra"));
if($clobrastecnicos->numrows>0){
  db_fieldsmemory($rsTecnico,0);
}

//eng. prefeitura
$rsEngPrefeitura = $clobrashabite->sql_record($clobrashabite->sql_query_engpref(null,"z01_nome as engpref, ob15_crea as engpref_crea",null," ob15_sequencial =$ob09_engprefeitura"));
if($clobrashabite->numrows > 0){
  db_fieldsmemory($rsEngPrefeitura,0);
}


$result_obraslote=$clobraslote->sql_record($clobraslote->sql_query($ob01_codobra,"j34_lote as lote,j34_quadra as quadra,j34_setor as setor"));
if($clobraslote->numrows>0){
  db_fieldsmemory($result_obraslote,0);
}else{
  $result_obraslotei=$clobraslotei->sql_record($clobraslotei->sql_query($ob01_codobra,"ob06_quadra as quadra,ob06_lote as lote, ob06_setor as setor"));
  if($clobraslotei->numrows>0){
    db_fieldsmemory($result_obraslotei,0);
  }
}
$ob24_iptubase = "";

$sSql = " select *,                                                                            ";
$sSql.= "        case when ob01_regular is true then j34_setor  else ob06_setor  end as setor ,";
$sSql.= "        case when ob01_regular is true then j34_quadra else ob06_quadra end as quadra,";
$sSql.= "        case when ob01_regular is true then j34_lote   else ob06_lote   end as lote  ,";
$sSql.= "        j06_setorloc  as setorloc ,                                                   ";
$sSql.= "        j06_quadraloc as quadraloc,                                                   ";
$sSql.= "        j06_lote      as loteloc                                                      ";
$sSql.= "   from obras                                                                         ";
$sSql.= "        left join obrasiptubase on obrasiptubase.ob24_obras    = obras.ob01_codobra   ";
$sSql.= "        left join iptubase      on obrasiptubase.ob24_iptubase = iptubase.j01_matric  ";
$sSql.= "        left join lote          on lote.j34_idbql              = iptubase.j01_idbql   ";
$sSql.= "        left join obraslotei    on obraslotei.ob06_codobra     = obras.ob01_codobra   ";
$sSql.= "        left join loteloc       on loteloc.j06_idbql           = iptubase.j01_matric  ";
$sSql.= "  where obras.ob01_codobra = {$ob01_codobra} limit 1                                  ";

$rsSql = db_query($sSql);
$ob19_codproc = "";

$rsObrasHabiteProt = $clobrashabiteprot->sql_record($clobrashabiteprot->sql_query(null,"*",null,"ob19_codhab = $cod_hab"));

if($clobrastecnicos->numrows > 0 && $clobrashabiteprot->numrows > 0){
  db_fieldsmemory($rsObrasHabiteProt, 0);
}
db_fieldsmemory($rsSql, 0);

$data = date("Y-m-d",DB_getsession("DB_datausu"));
$dia  = date("d");
$mes  = date("m");
$ano  = date("Y");
$mes_extenso  = array("01"=>"janeiro","02"=>"fevereiro","03"=>"março","04"=>"abril","05"=>"maio","06"=>"junho","07"=>"julho","08"=>"agosto","09"=>"setembro","10"=>"outubro","11"=>"novembro","12"=>"dezembro");
$data_extenso = $munic.", ".$dia." de ".$mes_extenso[$mes]." de ".$ano.".";

/*============================================================  O DOCUMENTO PDF  ==============================================================================================*/ 

$pdf = new PDF4();             // abre a classe
$pdf->Open();                  // abre o relatorio
$pdf->AliasNbPages();          // gera alias para as paginas
$pdf->AddPage();               // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$alt=5;

/////// TEXTOS E ASSINATURAS

$sqlparag = "select *
               from db_documento
               inner join db_docparag  on db03_docum   = db04_docum
               inner join db_tipodoc   on db08_codigo  = db03_tipodoc
               inner join db_paragrafo on db04_idparag = db02_idparag
         where db03_tipodoc = 1021 
           and db03_instit  = " . db_getsession("DB_instit")." order by db04_ordem ";


$resparag = db_query($sqlparag);
$numrows  = pg_numrows($resparag);

if($numrows == 0 ){
  
  $sMsg = _M('tributario.projetos.pro2_cartahabite002.configure_documento');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}else{
  db_fieldsmemory( $resparag,0);
}

$oLibDocumento->codhab                  = @$cod_hab;//igo;
$oLibDocumento->numero                  = @$ob07_numero;
$oLibDocumento->nome                    = ucwords(strtolower($z01_nome));
$oLibDocumento->rua                     = ucwords(strtolower(@$j14_nome));
$oLibDocumento->tipoconstr              = @$j31_descr;
$oLibDocumento->codproc                 = $codproc;
$oLibDocumento->datavist                = db_dataextenso(db_strtotime($ob09_data),"");
$oLibDocumento->areaconstr              = $ob08_area;
$oLibDocumento->codh                    = $ob09_habite;
$oLibDocumento->setor                   = @$setor;
$oLibDocumento->quadra                  = @$quadra;
$oLibDocumento->lote                    = @$lote;
$oLibDocumento->dataproc                = db_formatar($dtproc,'d');
$oLibDocumento->endercgm                = $endcgm;
$oLibDocumento->endercorresp            = $endcorresp;
$oLibDocumento->processo                = $codproc;
$oLibDocumento->dataprot                = $dtproc;
$oLibDocumento->ocupacao                = $ob08_ocupacao;
$oLibDocumento->tipolanc                = $ob08_tipoconstr;
$oLibDocumento->areatotarea             = $ob08_area;
$oLibDocumento->arealiberada            = $ob09_area;
$oLibDocumento->datahabit               = $ob09_data;
$oLibDocumento->obs                     = $ob09_obs;
$oLibDocumento->obsinss                 = $ob09_obsinss;
$oLibDocumento->tipo                    = $ob09_parcial;
$oLibDocumento->tecproj                 = $tec;
$oLibDocumento->creaproj                = $tec_crea;
$oLibDocumento->data_extenso            = $data_extenso;
$oLibDocumento->data_protocolo_extenso  = db_dataextenso(db_strtotime($dtproc)   , "");
$oLibDocumento->data_vistoria_extenso   = db_dataextenso(db_strtotime($ob09_data), "");
$oLibDocumento->nome_eng_pref           = $engpref;
$oLibDocumento->crea_eng_pref           = $engpref_crea;
$oLibDocumento->ano_habite              = $ob09_anousu;
$oLibDocumento->setorloc                = $setorloc;
$oLibDocumento->quadraloc               = $quadraloc;
$oLibDocumento->loteloc                 = $loteloc;
$oLibDocumento->processoHabite          = $ob19_codproc;
$oLibDocumento->observacoesHabite       = $ob09_obs;
$oLibDocumento->matricula               = $ob24_iptubase;  

$aParagrafo = $oLibDocumento->getDocParagrafos();

foreach($aParagrafo as $oParag){
    $oParag->writeText( $pdf );
}


$pdf->Output();
?>