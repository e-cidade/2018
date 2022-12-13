<?php

//require_once "../libs/db_conn.php";
require_once "../libs/db_utils.php";

$DB_SERVIDOR = 'localhost';
$DB_BASE     = 'folha_nery_demo_v2.3.34';
$DB_PORTA    = '5432';
$DB_USUARIO  = 'ecidade';
$DB_SENHA    = '';

$lProcessaCompleto = true;

system("rm ../dd/tabelas/*");
system("rm ../dd/table_wrappers.dd.xml");

if(!($conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
  echo "Contate com Administrador do Sistema! (Conexão Inválida.)\n";
  exit;
}

$rs = pg_query("select fc_startsession();");

$sSqlTabelas     = "select * from db_sysarquivo";
// die($sSqlTabelas);

$rsTabelas       = pg_query($sSqlTabelas);
$iNumRowsTabelas = pg_num_rows($rsTabelas);
if ($lProcessaCompleto) {

/*
  @TODO - Gerar valores default
*/
  for ($iTabelas=0;$iTabelas < $iNumRowsTabelas; $iTabelas++) {
    
    echo "Processando tabelas ... {$iTabelas} de {$iNumRowsTabelas} \r";

    $oTabela = db_utils::fieldsMemory($rsTabelas,$iTabelas);

    $rsArq = fopen("../dd/tabelas/{$oTabela->nomearq}.dd.xml", "a+");
    $sXml  = "<?xml version=\"1.0\" standalone=\"yes\" ?>\n";
    // $sXml .= "<tabela codarq=\"{$oTabela->codarq}\" nomearq=\"{$oTabela->nomearq}\" descricao=\"".utf8_encode($oTabela->descricao)."\" sigla=\"{$oTabela->sigla}\" dataincl=\"{$oTabela->dataincl}\" rotulo=\"".utf8_encode($oTabela->rotulo)."\" tipotabela=\"{$oTabela->tipotabela}\" naolibclass=\"{$oTabela->naolibclass}\" naolibfunc=\"{$oTabela->naolibfunc}\" naolibprog=\"{$oTabela->naolibprog}\" naolibform=\"{$oTabela->naolibform}\">\n";
    $sXml .= "<table name=\"{$oTabela->nomearq}\" description=\"".tratamentoString($oTabela->descricao)."\" prefix=\"{$oTabela->sigla}\" label=\"".tratamentoString($oTabela->rotulo)."\" type=\"{$oTabela->tipotabela}\">\n";
    
    // select buscando os campos
    $sSql = " select db_syscampo.descricao as descricao_campo,
                     db_syscampo.rotulo    as rotulo_campo,
                     
                     ( select cp.nomecam
                         from db_syscampodep  
                              inner join db_syscampo cp  on cp.codcam = db_syscampodep.codcampai
                        where db_syscampodep.codcam = db_sysarqcamp.codcam ) as nome_campo_pai,
                     exists ( select 1 
                                from db_syssequencia
                               where db_syssequencia.codsequencia = db_sysarqcamp.codsequencia ) as tem_sequencia,
  	                 case 
  	                   when db_sysprikey.codcam is not null then true 
  	                   else false 
  	                 end as chave_primaria,
  	                 
                     ( select nomesequencia 
                         from db_syssequencia
                        where db_syssequencia.codsequencia = db_sysarqcamp.codsequencia ) as nomesequencia,
                     *
                from db_sysarqcamp 
                     inner join db_syscampo     on db_syscampo.codcam   = db_sysarqcamp.codcam
                     left  join  db_sysprikey   on db_sysprikey.codarq  = db_sysarqcamp.codarq
                                               and db_sysprikey.codcam  = db_sysarqcamp.codcam   
               where db_sysarqcamp.codarq = {$oTabela->codarq} 
               order by db_sysprikey.sequen";
//die($sSql);
    $rs = pg_query($sSql);
    $iTotalRegistros = pg_num_rows($rs);

//    $sXml .= "  <campos>\n";
    $sXml .= "  <fields>\n";
    // Processando os campos da tabela
    for ($i=0;$i < $iTotalRegistros; $i++) {

      $o = db_utils::fieldsMemory($rs,$i);

      /**
       * 1 - Gerar campo principal
       * 2 - Gerar valores default
       */
      $sXmlSequencia = "";
      if ($o->codsequencia != "0" && $o->codsequencia != "") {
        // $sXmlSequencia = "\n      <sequencia codsequencia=\"{$o->codsequencia}\" nomesequencia=\"{$o->nomesequencia}\" incrseq=\"{$o->incrseq}\" minvalueseq=\"{$o->minvalueseq}\" maxvalueseq=\"{$o->maxvalueseq}\" startseq=\"{$o->startseq}\" cacheseq=\"{$o->cacheseq}\"></sequencia>\n ";
        // $sXmlSequencia = "\n      <sequence name=\"{$o->nomesequencia}\" incrseq=\"{$o->incrseq}\" minvalueseq=\"{$o->minvalueseq}\" maxvalueseq=\"{$o->maxvalueseq}\" startseq=\"{$o->startseq}\" cacheseq=\"{$o->cacheseq}\"></sequence>\n    ";
        $sXmlSequencia = "\n      <sequence name=\"{$o->nomesequencia}\" ></sequence>\n    ";
      }
      // $sXml .= "    <campo nomecam=\"{$o->nomecam}\" conteudo=\"".utf8_encode($o->conteudo)."\" descricao=\"".utf8_encode($o->descricao_campo)."\" valorinicial=\"{$o->valorinicial}\" rotulo=\"".utf8_encode($o->rotulo_campo)."\" tamanho=\"{$o->tamanho}\" nulo=\"{$o->nulo}\" maiusculo=\"{$o->maiusculo}\" autocompl=\"{$o->autocompl}\" aceitatipo=\"{$o->aceitatipo}\" tipoobj=\"{$o->tipoobj}\" rotulorel=\"".utf8_encode($o->rotulorel)."\" codcam_principal=\"{$o->codcam_pai}\" nomecam_principal=\"{$o->nome_campo_pai}\" chaveprimaria=\"{$o->chave_primaria}\" sequencia=\"{$o->tem_sequencia}\">{$sXmlSequencia}</campo> \n";
      $sXml .= "    <field name=\"{$o->nomecam}\" 
                           conteudo=\"".tratamentoString($o->conteudo)."\" 
                           description=\"".tratamentoString($o->descricao_campo)."\" 
                           inivalue=\"{$o->valorinicial}\" 
                           label=\"".tratamentoString($o->rotulo_campo)."\" 
                           size=\"{$o->tamanho}\" 
                           null=\"{$o->nulo}\" 
                           uppercase=\"{$o->maiusculo}\" 
                           autocompl=\"{$o->autocompl}\" 
                           aceitatipo=\"{$o->aceitatipo}\" 
                           tipoobj=\"{$o->tipoobj}\" 
                           labelrel=\"".tratamentoString($o->rotulorel)."\" 
                           reference=\"{$o->nome_campo_pai}\" 
                           ispk=\"{$o->chave_primaria}\" 
                           hassequence=\"{$o->tem_sequencia}\">
                           {$sXmlSequencia}
                    </field> \n";
    }
    
    $sXml .= "  </fields>\n";
    
    $sSqlChavePrimaria = " select db_sysprikey.*,
                                  db_syscampo.nomecam 
                             from db_sysprikey 
                                  inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam 
                            where codarq = {$oTabela->codarq} ";
    $rsChavePrimaria = pg_query($sSqlChavePrimaria);
    $iTotalRegistros = pg_num_rows($rsChavePrimaria);
    $sXml .= "  <primarykey>\n";
    for ($i=0;$i < $iTotalRegistros; $i++) {
      $o = db_utils::fieldsMemory($rsChavePrimaria,$i);
      $sXml .= "    <fieldpk  name=\"{$o->nomecam}\"></fieldpk> \n";
    }
    $sXml .= "  </primarykey>\n";
/*
    $sSqlChaveEstrangeira = "select db_sysforkey.*,
                                    p.nomecam  as campo_principal,
                                    tr.nomearq as nomearq_ref,
                                    ( select nomecam 
                                        from db_sysprikey 
                                             inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam 
                                       where db_sysprikey.codarq = db_sysforkey.referen
                                         and db_sysprikey.sequen = db_sysforkey.sequen
                                    )          as campo_referente,
                                    not p.nulo as inner
                               from db_sysforkey 
                                    inner join db_syscampo p on p.codcam     = db_sysforkey.codcam
                                    inner join db_syscampo r on r.codcam     = db_sysforkey.referen 
                                    inner join db_sysarquivo tr on tr.codarq = db_sysforkey.referen
                              where db_sysforkey.codarq = {$oTabela->codarq} order by tr.codarq ";
*/
    $sSqlChaveEstrangeira  = " select db_sysforkey.*, ";
		$sSqlChaveEstrangeira .= "        ( select nomecam "; 
		$sSqlChaveEstrangeira .= "            from db_syscampo "; 
		$sSqlChaveEstrangeira .= "           where codcam = db_sysforkey.codcam ) as campo_principal, ";
		$sSqlChaveEstrangeira .= "        tr.nomearq as nomearq_ref, ";
    $sSqlChaveEstrangeira .= "        ( select nomecam ";
    $sSqlChaveEstrangeira .= " 	          from db_sysprikey "; 
    $sSqlChaveEstrangeira .= " 	               inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam "; 
    $sSqlChaveEstrangeira .= "           where db_sysprikey.codarq = db_sysforkey.referen ";
    $sSqlChaveEstrangeira .= " 	           and db_sysprikey.sequen = db_sysforkey.sequen ) as campo_referente, ";
    $sSqlChaveEstrangeira .= "        not ( select nulo ";
    $sSqlChaveEstrangeira .= "                from db_syscampo ";
    $sSqlChaveEstrangeira .= "               where codcam = db_sysforkey.codcam ) as inner ";
    $sSqlChaveEstrangeira .= "   from db_sysforkey ";
    $sSqlChaveEstrangeira .= "        inner join db_sysarquivo tr on tr.codarq = db_sysforkey.referen ";
    $sSqlChaveEstrangeira .= "  where db_sysforkey.codarq = {$oTabela->codarq} order by tr.codarq ";

    $sXml .= "  <foreignkeys>\n";
    $rsChaveEstrangeira = pg_query($sSqlChaveEstrangeira);
    $iTotalRegistros = pg_num_rows($rsChaveEstrangeira);

    $aProcessadas = array();
    $lPrimeiro    = true;
    for ($i=0;$i < $iTotalRegistros; $i++) {
      $o = db_utils::fieldsMemory($rsChaveEstrangeira,$i);
      
      if ($lPrimeiro || !in_array($o->referen,$aProcessadas)){
        if (!$lPrimeiro) {
          $sXml .= "    </foreignkey>\n";
        }
        $sXml .= "    <foreignkey reference=\"$o->nomearq_ref\">\n";

        $aProcessadas[] = $o->referen;
        $lPrimeiro      = false;
      }

      $sXml .= "      <fieldfk name=\"{$o->campo_principal}\" reference=\"{$o->campo_referente}\" inner='{$o->inner}'></fieldfk> \n";
      
    }
    if ($iTotalRegistros > 0){
      $sXml .= "    </foreignkey>\n";
    }

    $sXml .= "  </foreignkeys>\n";
/*    
    $sSqlIndices = " select nomeind,
                            db_syscampo.nomecam 
                       from db_sysindices 
                            inner join db_syscadind on db_syscadind.codind = db_sysindices.codind 
                            inner join db_syscampo on db_syscampo.codcam = db_syscadind.codcam 
                      where codarq = {$oTabela->codarq} ";
    $sXml .= "  <indexes>\n";
    $sXml .= "  </indexes>\n";
*/
    
    $sXml .= "</table>\n";
    fputs($rsArq,utf8_encode($sXml));
    fclose($rsArq);
    

  }
}

$sSqlTabelas        = "select nomearq,sigla from db_sysarquivo order by sigla";
$sSqlTabelas        = "select distinct 
                              nomearq, 
                              split_part(nomecam,'_',1) as sigla 
                         from db_syscampo 
                              inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam 
                              inner join db_sysarquivo on db_sysarquivo.codarq = db_sysarqcamp.codarq
                        order by 2 ";
$rsTabelas          = pg_query($sSqlTabelas);
$iNumRowsTabelas    = pg_num_rows($rsTabelas);
$aSiglasProcessadas = array();
$lPrimeiro          = true;

$rsTabelaSigla      = fopen("../dd/table_wrappers.dd.xml", "a+");
$sXmlTabelaSigla    = "<?xml version=\"1.0\" standalone=\"yes\" ?>\n";  
$sXmlTabelaSigla   .= "  <prefixes>\n";

for ($iTabelas=0;$iTabelas < $iNumRowsTabelas; $iTabelas++) {
  
  echo "Processando tabelas ... {$iTabelas} de {$iNumRowsTabelas} \r";

  $oTabela = db_utils::fieldsMemory($rsTabelas,$iTabelas);
  
  if ( ! in_array($oTabela->sigla,$aSiglasProcessadas) ) {

    if ( ! $lPrimeiro ) {
      $sXmlTabelaSigla .= "    </prefix>\n";
    }
    $sXmlTabelaSigla .= "    <prefix name=\"{$oTabela->sigla}\">\n";
    $aSiglasProcessadas[] = $oTabela->sigla;
    $lPrimeiro        = false;
    
  }

  $sXmlTabelaSigla .= "      <table name=\"{$oTabela->nomearq}\"></table>\n";

}

$sXmlTabelaSigla .= "    </prefix>\n";      
$sXmlTabelaSigla .= "</prefixes>\n";

fputs($rsTabelaSigla,utf8_decode($sXmlTabelaSigla));

fclose($rsTabelaSigla);

function tratamentoString($sString){
	
	$aRetirar = array('<b>','</b>',"\"","<i>","</i>");
	
  foreach ($aRetirar as $sRetirar ) {
  	
  	$sString = str_replace($sRetirar,"",$sString);
  	
  }
  
	return $sString;	
	
}


?>
