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

require_once("fpdf151/impcarne.php");
require_once("fpdf151/scpdf.php");

require_once("classes/db_cgm_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_rhemitecontracheque_classe.php");
require_once("classes/db_cfpess_classe.php");

require_once("libs/db_utils.php");
require_once("libs/db_libpessoal.php");

db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clrhemitecontracheque = new cl_rhemitecontracheque();
$oDaoCfpess            = new cl_cfpess;

/**
 * Tipo de relatório contracheque
 * Retorna false caso der erro na consulta
 */   
$iTipoRelatorio = $oDaoCfpess->buscaCodigoRelatorio('contracheque', db_anofolha(), db_mesfolha());
if(!$iTipoRelatorio) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Modelo de impressão invalido, verifique parametros.');
}

$wherepes = '';
if($selecao != ''){
  $result_sel = db_query("select r44_where , r44_descr from selecao where r44_selec = {$selecao} and r44_instit = " . db_getsession("DB_instit"));
  if(pg_numrows($result_sel) > 0){
    db_fieldsmemory($result_sel, 0, 1);
    $wherepes .= " and ".$r44_where;
    $head5 = $r44_descr;
    $erroajuda = " ou seleção informada é inválida";
  }
}

$sql = "select * from db_config where codigo = ".db_getsession("DB_instit");
$result = db_query($sql);
db_fieldsmemory($result,0);

$xtipo = "'x'";
$qualarquivo = '';
if ( $opcao == 'salario' ){
  $sigla   = 'r14_';
  $arquivo = 'gerfsal';
  $qualarquivo = 'SALÁRIO';
}elseif ( $opcao == 'ferias' ){
  $sigla   = 'r31_';
  $arquivo = 'gerffer';
  $xtipo   = ' r31_tpp ';
  $qualarquivo = 'FÉRIAS';
}elseif ( $opcao == 'rescisao' ){
  $sigla   = 'r20_';
  $arquivo = 'gerfres';
  $xtipo   = ' r20_tpp ';
  $qualarquivo = 'RESCISÃO';
}elseif ($opcao == 'adiantamento'){
  $sigla   = 'r22_';
  $arquivo = 'gerfadi';
  $qualarquivo = 'ADIANTAMENTO';
}elseif ($opcao == '13salario'){
  $sigla   = 'r35_';
  $arquivo = 'gerfs13';
  $qualarquivo = '13o. SALÁRIO';
}elseif ($opcao == 'complementar'){
  $sigla   = 'r48_';
  $arquivo = 'gerfcom';
  $qualarquivo = 'COMPLEMENTAR';
}elseif ($opcao == 'fixo'){
  $sigla   = 'r53_';
  $arquivo = 'gerffx';
  $qualarquivo = 'FIXO';
}elseif ($opcao == 'previden'){
  $sigla   = 'r60_';
  $arquivo = 'previden';
  $qualarquivo = 'AJUSTE DA PREVIDÊNCIA';
}elseif ($opcao == 'irf'){
  $sigla   = 'r61_';
  $arquivo = 'ajusteir';
  $qualarquivo = 'AJUSTE DO IRRF';
}
$txt_where="1=1";
if (isset($filtro)&&$filtro!='N'){
  if ($filtro=='M'){
    $campo=$sigla."regist";
  }else if ($filtro=='L'){
    $campo=$sigla."lotac::integer";
  }else if ($filtro=='T'){
    $campo= "rh56_localtrab";
  }
  if (isset($dados)&&$dados!=""){
    $txt_where = " $campo in ($dados) ";
  }elseif (isset($codini) && $codini != "" && $codfim != ""){
    $txt_where = " $campo between $codini and $codfim ";
  }
}
if(isset($local) && trim($local) != ""){
  if($txt_where!=""){
     $txt_where.= " and ";
  }
  if($tipo_local == 's'){
    $txt_where.= " rh56_localtrab = ".$local;
  }else{
    $txt_where.= " rh56_localtrab <> ".$local;
  }
}

$wheresemest = "";
if(isset($semest) && trim($semest) != 0){
  $wheresemest = " and r48_semest = ".$semest;
}

$sql1= "select distinct
       		z01_nome,
       		rhpessoal.*,
       		rhpessoalmov.*,
          rhpesbanco.*,
       		rh37_descr,
       		r70_descr,
          rhregime.*,
      		substr(r70_estrut,1,7) as estrut,
	       	".$sigla."regist as regist,
	        substr(db_fxxx(".$sigla."regist,$ano,$mes,".db_getsession("DB_instit")."),111,11) as f010, 
        	substr(db_fxxx(".$sigla."regist,$ano,$mes,".db_getsession("DB_instit")."),210,8) as padrao
          from (select distinct ".$sigla."regist,
                                ".$sigla."anousu,
                                ".$sigla."mesusu,
                                ".$sigla."lotac
	              from ".$arquivo." ".bb_condicaosubpesproc( $sigla,$ano."/".$mes ).$wheresemest." 
               ) as ".$arquivo."
       	  inner join rhpessoal     on rh01_regist = ".$sigla."regist 
		      inner join rhpessoalmov  on rh02_regist = rh01_regist
                                  and rh02_anousu = $ano 
		  	                          and rh02_mesusu = $mes 
		  	                          and rh02_instit = ".db_getsession("DB_instit")."
          inner join rhregime      on rh02_codreg = rh30_codreg
                                  and rh02_instit = rh30_instit
     		  inner join cgm           on rh01_numcgm  = z01_numcgm
     		  left join rhfuncao       on rh37_funcao = rh02_funcao
		                              and rh37_instit = rh02_instit
     		  left join rhlota         on r70_codigo  = rh02_lota
				                          and r70_instit  = rh02_instit	
          left join rhpescargo     on rh20_seqpes = rh02_seqpes
		                              and rh20_instit = rh02_instit
          left join rhpesbanco     on rh44_seqpes = rh02_seqpes
          left join rhcargo     	 on rh04_codigo = rh20_cargo
		                              and rh04_instit = rh02_instit
          left join rhpeslocaltrab on rh56_seqpes = rh02_seqpes
                                  and rh56_princ  = true

	        where $txt_where $wherepes
	        ";

$sql = "select * from ($sql1) as xxx, generate_series(1,$num_vias) order by ";

if($ordem == "L"){
  $sql .= " estrut,z01_nome, rh01_regist ";
}else if($ordem == "N"){
  $sql .= " z01_nome , rh01_regist";
}else if($ordem == "T"){
  $sql .= " rh56_localtrab , z01_nome , rh01_regist ";
}else{
  $sql .= " rh01_regist ";
}

// ------------- busca url do site do cliente ----------------------
$sqlDbConfig = " select url from db_config where prefeitura = true ";
$rsDbConfig  = db_query($sqlDbConfig);
$iDbConfig   = pg_numrows($rsDbConfig);

if ($iDbConfig > 0) {
	$oDbConfig = db_utils::fieldsMemory($rsDbConfig, 0);
	$sDbConfig = $oDbConfig->url;
} else {
	$sDbConfig = "";
}
//------------------------------------------------------------------
// echo $sql;exit;
$res = db_query($sql);
$num = pg_numrows($res);
if ($num == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Cálculo no período de '.$mes.' / '.$ano);
}
  global $pdf;
  $pdf = new scpdf();
  $pdf->setautopagebreak(false,0.05);
  $pdf->Open();
  $pdf1 = new db_impcarne($pdf, $iTipoRelatorio);
  $pdf1->logo             = $logo;
  $pdf1->prefeitura       = $nomeinst;
  $pdf1->enderpref        = $ender.(isset($numero)?(', '.$numero):"");
  $pdf1->cgcpref          = $cgc;
  $pdf1->municpref        = $munic;
  $pdf1->telefpref        = $telef;
  $pdf1->emailpref        = $email;
  $pdf1->ano        	    = $ano;
  $pdf1->mes          	  = $mes;
  $pdf1->mensagem         = $msg;
  $pdf1->qualarquivo     = $qualarquivo;
 
  $lin = 1;
  
for($i=0;$i<$num;$i++){
	
  db_fieldsmemory($res,$i);

  $rsSeqContraCheque = db_query("select nextval('rhemitecontracheque_rh85_sequencial_seq') as sequencial");
  $oSeqContraCheque  = db_utils::fieldsMemory($rsSeqContraCheque,0);
  $iSequencial       = str_pad($oSeqContraCheque->sequencial,6,'0',STR_PAD_LEFT);
  
  $iMes       = str_pad($mes,2,'0',STR_PAD_LEFT);
  $iMatricula = str_pad($regist,6,'0',STR_PAD_LEFT);
  $iMod1      = db_CalculaDV($iMatricula);
  $iMod2      = db_CalculaDV($iMatricula.$iMod1.$iMes.$ano.$iSequencial); 
     
  $iCodAutent = $iMatricula.$iMod1.$iMes.$iMod2.$ano.$iSequencial;
  
  $clrhemitecontracheque->rh85_sequencial  = $iSequencial;
  $clrhemitecontracheque->rh85_regist      = $regist;
  $clrhemitecontracheque->rh85_anousu      = $ano;
  $clrhemitecontracheque->rh85_mesusu      = $mes;
  $clrhemitecontracheque->rh85_sigla       = substr($sigla,0,3);
  $clrhemitecontracheque->rh85_codautent   = $iCodAutent;
  $clrhemitecontracheque->rh85_dataemissao = date('Y-m-d',db_getsession('DB_datausu'));
  $clrhemitecontracheque->rh85_horaemissao = db_hora();
  $clrhemitecontracheque->rh85_ip          = db_getsession('DB_ip');
  $clrhemitecontracheque->rh85_externo     = 'false';

  $clrhemitecontracheque->incluir($iSequencial);
  
  if ( $clrhemitecontracheque->erro_status == 0 ) {
  	db_redireciona('db_erros.php?fechar=true&db_erro='.$clrhemitecontracheque->erro_msg);
  }
  
  if($lin == 1){
    $lin = 0;
    $pdf1->seq = 0;
  }else{
    $lin = 1;
    $pdf1->seq = 1;
  }
  
  $sql = "
  select ".$sigla."rubric as rubrica,
       round(".$sigla."valor,2) as valor,
       round(".$sigla."quant,2) as quant, 
       rh27_descr, 
       ".$xtipo." as tipo , 
       case when rh27_pd = '3' then 'B' 
            when ".$sigla."pd = 1 then 'P' 
	          when ".$sigla."pd = 2 then 'D' 
       end as provdesc
 
  from ".$arquivo." 
     inner join rhrubricas on rh27_rubric = ".$sigla."rubric 
                          and rh27_instit = ".db_getsession('DB_instit')."
  where ".$sigla."regist = $regist
    and ".$sigla."anousu = $ano 
    and ".$sigla."mesusu = $mes
    and ".$sigla."instit = ".db_getsession("DB_instit")."
    $wheresemest
  order by ".$sigla."rubric  ";

  $res_env = db_query($sql);
   
  $pdf1->registro	        = $rh01_regist;
  $pdf1->admissao	        = db_formatar($rh01_admiss,'d');
  $pdf1->nome		          = $z01_nome;
  $pdf1->descr_funcao	    = $rh37_descr;
  $pdf1->descr_lota       = $estrut.'-'.$r70_descr;
  $pdf1->f010          	  = $f010;
  $pdf1->padrao        	  = $padrao;
  $pdf1->banco         	  = $rh44_codban;
  $pdf1->agencia       	  = trim($rh44_agencia).'-'.trim($rh44_dvagencia);
  $pdf1->conta         	  = trim($rh44_conta).'-'.trim($rh44_dvconta);
  $pdf1->lotacao	        = $estrut;
  $pdf1->recordenvelope   = $res_env;
  $pdf1->linhasenvelope	  = pg_numrows($res_env);
  $pdf1->valor		        = 'valor';
  $pdf1->quantidade	      = 'quant';
  $pdf1->tipo		          = 'provdesc';
  $pdf1->rubrica	        = 'rubrica';
  $pdf1->descr_rub	      = 'rh27_descr';
  $pdf1->numero	  	      = $i+1;
  $pdf1->total	  	      = $num;
  $pdf1->codautent        = $iCodAutent;
  $pdf1->url              = $sDbConfig;
  $pdf1->imprime();
  
}
$pdf1->objpdf->output();
?>