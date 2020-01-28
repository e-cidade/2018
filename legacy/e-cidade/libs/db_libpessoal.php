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


function db_sel_pessal($tabela,$cmp_=null,$where=null){
  global $$tabela;

  $campos = array();

  $campos["R01_INSTIT"] = "RH01_INSTIT";
  $campos["R01_ANOUSU"] = "RH02_ANOUSU";
  $campos["R01_MESUSU"] = "RH02_MESUSU";
  $campos["R01_REGIST"] = "RH01_REGIST"; 
  $campos["R01_NUMCGM"] = "RH01_NUMCGM";
  $campos["R01_FUNCAO"] = "RH01_FUNCAO";
  $campos["R01_ADMISS"] = "RH01_ADMISS";
  $campos["R01_NASC"]   = "RH01_NASC";
  $campos["R01_RACA"]   = "RH01_RACA";
  $campos["R01_NACION"] = "RH01_NACION";
  $campos["R01_ANOCHE"] = "RH01_ANOCHE";
  $campos["R01_INSTRU"] = "RH01_INSTRU";
  $campos["R01_SEXO"]   = "RH01_SEXO";
  $campos["R01_ESTCIV"] = "TRIM(TO_CHAR(RH01_ESTCIV AS''9''))";
  $campos["R01_TIPADM"] = "RH01_TIPADM";
  $campos["R01_NATURA"] = "RH01_NATURA";
  $campos["R01_TRIEN"]  = "RH01_TRIENIO";
  $campos["R01_ANTER"]  = "RH01_PROGRES"; 
  $campos["R01_PROGR"]  = "(CASE WHEN RH01_PROGRES IS NOT NULL THEN 'S' ELSE 'N' END)";
  $campos["R01_CLAS1"]  = "RH01_CLAS1"; 
  $campos["R01_VALE"]   = "RH01_VALE "; 
  $campos["R01_PONTO"]  = "RH01_PONTO"; 
  $campos["R01_CLAS2"]  = "RH01_CLAS2"; 
  $campos["R01_REGIME"] = "RH30_REGIME"; 
  $campos["R01_TPVINC"] = "RH30_VINCULO"; 
  $campos["R01_TIPSAL"] = "RH02_TIPSAL"; 
  $campos["R01_FOLHA"]  = "RH02_FOLHA ";
  $campos["R01_FPAGTO"] = "RH02_FPAGTO";
  $campos["R01_TBPREV"] = "RH02_TBPREV"; 
  $campos["R01_HRSMEN"] = "RH02_HRSMEN"; 
  $campos["R01_HRSSEM"] = "RH02_HRSSEM"; 
  $campos["R01_TPCONT"] = " H13_TPCONT"; 
  $campos["R01_OCORRE"] = "RH02_OCORRE"; 
  $campos["R01_VINCUL"] = "RH02_VINCRAIS";
  $campos["R01_EQUIP"]  = "RH02_EQUIP"; 
  $campos["R01_LOTAC"]  = "TRIM(TO_CHAR(RH02_LOTA,'9999'))"; 
  $campos["R01_SALAR"]  = "RH02_SALARI"; 
  $campos["R01_TITELE"] = "RH16_TITELE";
  $campos["R01_ZONAEL"] = "RH16_ZONAEL";
  $campos["R01_SECAOE"] = "RH16_SECAOE";
  $campos["R01_RESERV"] = "RH16_RESERV";
  $campos["R01_CATRES"] = "RH16_CATRES";
  $campos["R01_CATRES"] = "RH16_CATRES";
  $campos["R01_CTPS"]   = "LPAD(NEW.RH16_CTPS_N,7,0)||LPAD(NEW.RH16_CTPS_S;5;0)";
  $campos["R01_CTPSUF"] = "RH16_CTPS_UF";
  $campos["R01_PIS"]    = "RH16_PIS";
  $campos["R01_CARTH"]  = "RH16_CARTH_N";
  $campos["R01_BANCO"]  = "RH44_CODBAN";
  $campos["R01_AGENC"]  = "SUBSTR(TRIM(RH44_AGENCIA)||TRIM(RH44_DVAGENCIA;1;5)";
  $campos["R01_CONTAC"] = "SUBSTR(TRIM(RH44_CONTA)||TRIM(RH44_DVCONTA;1;15)";
  $campos["R01_FGTS"]   = "RH15_DATA";   
  $campos["R01_BCOFGT"] = "RH15_BANCO";
  $campos["R01_AGFGTS"] = "SUBSTR(RH15_AGENCIA||RH15_AGENCIA_D;1;5)";
  $campos["R01_CCFGTS"] = "SUBSTR(RH15_CONTAC||RH15_CONTAC_D;1;11)";
  $campos["R01_PADRAO"] = "RH03_PADRAO"; 
  $campos["R01_MATIPE"] = "RH14_MATIPE";
  $campos["R01_DTVINC"] = "RH14_DTVINC";
  $campos["R01_ESTADO"] = "RH14_ESTADO";
  $campos["R01_DTALT"]  = "RH14_DTALT";
  $campos["R01_PROP"]   = "RH19_PROPI";
  $campos["R01_BASEFO"] = "RH51_BASEFO";  
  $campos["R01_DESCFO"] = "RH51_DESCFO"; 
  $campos["R01_B13FO"]  = "RH51_B13FO";   
  $campos["R01_D13FO"]  = "RH51_D13FO";   
  $campos["R01_OCORRE"] = "RH51_OCORRE";
  $campos["R01_RECIS"]  = "RH05_RECIS";
  $campos["R01_CAUSA"]  = "RH05_CAUSA"; 
  $campos["R01_CAUB"]   = "RH05_CAUB";  
  $campos["R01_AVISO"]  = "RH05_AVISO";
  $campos["R01_TAVISO"] = "RH05_TAVISO";
  $campos["R01_MREMUN"] = "RH05_MREMUN"; 
  $campos["R01_cc"]     = "RH17_CC"; 
  $campos["R01_RUBRIC"] = "RH65_RUBRIC"; 
  $campos["R01_ARREDN"] = "RH65_VALOR"; 
  $campos["R01_DEPIRF"] = "RH01_DEPIRF"; 
  $campos["R01_DEPSF"]  = "RH01_DEPSF"; 

  $vircula = "";
  if($cmp_ != null || $cmp_ != ""){
     $cmp_ =  strtoupper(split(",",$cmp_));
     for($index=0; $index<$count($cmp_); $index++){
       $cmp .= $vircula." ".$campos[$cmp_[$index]]." AS ".$cmp_[$index];
       $vircula = ",";
     }
  }else {
     reset($campos);
     while (list($key, $val) = each($campos)) {
       $cmp .= $vircula." ".$key." AS ".$val;
       $vircula = ",";
     }
  }      
  $cmp =  str_replace(";",",",$cmp);

  if($where == null || $where == ""){
    $where = "";
  }else{
     while (list($key, $val) = each($campos)) {
       $where =  str_replace($key,$val,$where);
     }
    
  }

  $sql = "select ".$cmp." from rhpessoalmov
                               inner join rhpessoal    on rh01_regist    = rhpessoalmov.rh02_regist";

  if(db_at($cmp, "r70") > 0){
    $sql .= " inner join rhlota       on r70_codigo     = rhpessoalmov.rh02_lota";
    $sql .= "            and rhlota.r70_instit          = rhpessoalmov.rh02_instit";  
  }
  if(db_at($cmp, "z01") > 0){
    $sql .= " inner join cgm          on z01_numcgm     = rhpessoal.rh01_numcgm";
  }
  if(db_at($cmp, "rh05") > 0){
    $sql .= " left join rhpesrescisao on rh05_seqpes    = rhpessoalmov.rh02_seqpes";
  }
  if(db_at($cmp, "rh03") > 0){
    $sql .= " left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes";
  }
  if(db_at($cmp, "rh30") > 0){
    $sql .= " left join rhregime      on rh30_codreg    = rhpessoalmov.rh02_codreg";
  }
  if(db_at($cmp, "rh65") > 0){
    $sql .= " left join rhpesrubcalc  on rh65_seqpes    = rhpessoalmov.rh02_seqpes";
    $sql .= "                   and (rh65_rubric = 'R928' or rh65_rubric = 'R926')";
  }
  if(db_at($cmp, "rh15") > 0){
    $sql .= " left join rhpesfgts     on rh15_regist    = rhpessoalmov.rh02_regist";
  }
  if(db_at($cmp, "h13") > 0){
    $sql .= " left join tpcontra      on h13_codigo     = rhpessoalmov.rh02_tpcont";
  }
  if(db_at($cmp, "rh51") > 0){
    $sql .= " left join rhinssoutros  on rh51_seqpes    = rhpessoalmov.rh02_seqpes";
  }
  if(db_at($cmp, "rh19") > 0){
    $sql .= " left join rhpesprop     on rh19_regist    = rhpessoalmov.rh02_regist";
  }
  if(db_at($cmp, "rh37") > 0){
    $sql .= " left join rhfuncao      on rh37_funcao    = rhpessoal.rh01_funcao";
  }
  if(db_at($cmp, "rh62") > 0){
    $sql .= " left join rhiperegist   on rh62_regist    = rhpessoalmov.rh02_regist";
  }
  if(db_at($cmp, "rh14") > 0){
    $sql .= " left join rhiperegist   on rh62_regist    = rhpessoalmov.rh02_regist";
  }
  if(db_at($cmp, "rh44") > 0){
    $sql .= " left join rhpesbanco    on rh44_seqpes    = rhpessoalmov.rh02_seqpes";
  }
  if(db_at($cmp, "rh16") > 0){
    $sql .= " left join rhpesdoc      on rh16_regist    = rhpessoalmov.rh02_regist";
  }
  if(db_at($cmp, "rh17") > 0){
    $sql .= " left join rhpesccc      on rh17_regist    = rhpessoalmov.rh02_regist";
  }
  $sql .= $where;

  db_selectmax($tabela,$sql);

}

function db_alerta_erro_eval($registro,$formula,$rubrica){

  $error = error_get_last();
  $saida = ob_get_contents();
  ob_flush();
  ob_end_clean();
  if(strpos($saida, "Parse error") > 0 || !empty($error) && $error['type'] === E_PARSE) {
    db_msgbox("Erro na Formula : ".$formula." \\n\\n Matricula : ".$registro." \\n\\n Rubrica : ".$rubrica." \\n\\n Contate o Suporte !!");
    exit;
  }
}

function int($valor){
  return round($valor);

}

function db_verifica_dias_trabalhados($regist, $ano, $mes, $dias_trab = false){
  $retorno  = false;
  $dias_mes = db_dias_mes($ano, $mes);
  if($dias_trab == false){
    $result_testa_ferias = db_query("select dias_gozo_ferias(".$regist.",".$ano.",".$mes.",".$dias_mes.",".db_getsession("DB_instit").") as dias_ferias");
    if(pg_numrows($result_testa_ferias) > 0){
      db_fieldsmemory($result_testa_ferias, 0);
      global $dias_ferias;
      if($dias_ferias != "" && $dias_ferias > 0){
        $retorno = true;
      }
    }
 
    $result_testa_afasta = db_query("select conta_dias_afasta(".$regist.",".$ano.",".$mes.",".$dias_mes.",".db_getsession("DB_instit").") as  dias_afasta");
    if(pg_numrows($result_testa_afasta) > 0){
      db_fieldsmemory($result_testa_afasta, 0);
      global $dias_afasta;
      if($dias_afasta > 0){
        $retorno = true;
      }
    }
 
    $result_testa_rescis = db_query("select rh05_recis as data_recis
                                    from rhpesrescisao 
                                         inner join rhpessoalmov on rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes 
                                              where rh02_anousu = ".$ano." 
                                                and rh02_mesusu = ".$mes." 
                                                and rh02_regist = ".$regist."
                                                and rh02_instit = ".db_getsession("DB_instit"));
    if(pg_numrows($result_testa_rescis) > 0){
      db_fieldsmemory($result_testa_rescis, 0);
      global $data_recis;
      $retorno = true;
    }
  }else{
    $result_dias_trab = db_query("select fc_dias_trabalhados(".$regist.",".$ano.",".$mes.",true,".db_getsession("DB_instit").") as dias_pagamento");
    if(pg_numrows($result_dias_trab) > 0){
      db_fieldsmemory($result_dias_trab, 0);
      global $dias_pagamento;
      $retorno = true;
    }
  }

  $result_nome_funcionario = db_query(
                                     "select rh01_regist as matric, z01_nome as nomefc
                                      from rhpessoal
                                           inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm
                                      where rh01_regist = " . $regist
                                    );
  if(pg_numrows($result_nome_funcionario) > 0){
    db_fieldsmemory($result_nome_funcionario, 0);
    global $matric, $nomefc;
  }

  return $retorno;
}

function db_alerta_dados_func($opcao, $regist, $ano, $mes){
  $retorno = db_verifica_dias_trabalhados($regist,$ano, $mes);
  $msgRetorno = "";
  $emFerias   = "";
  $emAfasta   = "";
  $emRescis   = "";
  if($retorno == true){
    global $dias_ferias, $dias_afasta, $data_recis, $matric, $nomefc;
    if(isset($dias_ferias) && $dias_ferias > 0 && strpos($opcao,"f") !== false){
      $emFerias = "\\n* Férias cadastradas.";
    }
    if(isset($dias_afasta) && $dias_afasta > 0 && strpos($opcao,"a") !== false){
      $emAfasta = "\\n* Afastamento cadastrado.";
    }
    if(isset($data_recis) && $data_recis != "" && strpos($opcao,"r") !== false){
      $emRescis = "\\n* Rescisão.";
    }
    if($emFerias != "" || $emAfasta != "" || $emRescis != ""){
      $msgRetorno = "ALERTA: \\nFuncionário (".$matric." - ".$nomefc.") possui, neste ano / mês: ".$emFerias.$emAfasta.$emRescis;
    }
  }
  return $msgRetorno;
}

function db_retorno_variaveis($ano, $mes, $registro){
  /*
  global $f001, $f002,   $f003, $f004, $f005, 
         $f006, $f006_c, $f007, $f008, $f009, 
	 $f010, $f011,   $f012, $f013, $f014, 
	 $f015, $f022,   $f024, $f025, $padrao;
  */
  $sqlvar = '
          select 0::VARCHAR||trim(substr(db_fxxx,1,11)) as f001,
                 0::VARCHAR||trim(substr(db_fxxx,12,11)) as f002,
                 substr(db_fxxx,23,11) as f003,
                 0::VARCHAR||trim(substr(db_fxxx,34,11)) as f004,
                 0::VARCHAR||trim(substr(db_fxxx,45,11)) as f005,
                 0::VARCHAR||trim(substr(db_fxxx,56,11)) as f006,
                 0::VARCHAR||trim(substr(db_fxxx,67,11)) as f006_clt,
                 0::VARCHAR||trim(substr(db_fxxx,78,11)) as f007,
                 0::VARCHAR||trim(substr(db_fxxx,89,11)) as f008,
                 0::VARCHAR||trim(substr(db_fxxx,100,11)) as f009,
                 0::VARCHAR||trim(substr(db_fxxx,111,11)) as f010,
                 0::VARCHAR||trim(substr(db_fxxx,122,11)) as f011,
                 0::VARCHAR||trim(substr(db_fxxx,133,11)) as f012,
                 0::VARCHAR||trim(substr(db_fxxx,144,11)) as f013,
                 0::VARCHAR||trim(substr(db_fxxx,155,11)) as f014,
                 0::VARCHAR||trim(substr(db_fxxx,166,11)) as f015,
                 0::VARCHAR||trim(substr(db_fxxx,177,11)) as f022,
                 0::VARCHAR||trim(substr(db_fxxx,188,11)) as f024,
                 0::VARCHAR||trim(substr(db_fxxx,199,11)) as f025,
                 0::VARCHAR||trim(substr(db_fxxx,210,11)) as F030,
                 substr(db_fxxx,221,15) as padrao
            from (
	           select db_fxxx(rh02_regist,rh02_anousu,rh02_mesusu,'.db_getsession("DB_instit").')
                   from   rhpessoalmov
                   where rh02_anousu = '.$ano.'
       		         and rh02_mesusu = '.$mes.'
                   and rh02_regist = '.$registro.'
                   and rh02_instit = '.db_getsession("DB_instit").'
		  ) as x';
//		  echo $sqlvar;
  $resultvar = db_query($sqlvar);

  if($resultvar == false){
    return false;
  }else{
    $num_rows = pg_numrows($resultvar);
    if($num_rows > 0){
      $num_cols = pg_numfields($resultvar);
      for($index=0; $index<$num_cols; $index++){
        $nam_campo = pg_fieldname($resultvar, $index);
        $unam_campo = strtoupper(pg_fieldname($resultvar, $index));
        global $$nam_campo,$$unam_campo;
        $$nam_campo = pg_result($resultvar, 0, $nam_campo);
        $$unam_campo = pg_result($resultvar, 0, $nam_campo);
      }
    }
  }

  return pg_numrows($resultvar);
}

//Cria variaveis globais para o ano e mes passados
//Se ano e mes não forem passados, buscará dados do ano e mes correntes da folha
//Retorna false se tiver problemas na execução do sql e numrows caso sql esteja correto (0 se não encontrar registros e 1 caso encontre)
function db_sel_cfpess($anofolha=null, $mesfolha=null, $campos=" * "){
  if($anofolha == null || trim($anofolha) == ""){
    $anofolha = db_anofolha();
  }
  if($mesfolha == null || trim($mesfolha) == ""){
    $mesfolha = db_mesfolha();
  }
  if(trim($campos) == ""){
    $campos = " * ";
  }
  $record_cfpess = db_query("select ".$campos." from cfpess where r11_anousu = ".$anofolha." and r11_mesusu = ".$mesfolha ." and r11_instit = ".DB_getsession("DB_instit"));
  if($record_cfpess == false){
    return false;
  }else{
    $num_cols = pg_numfields($record_cfpess);
    $num_rows = pg_numrows($record_cfpess);
    for($index=0; $index<$num_cols; $index++){
      $nam_campo = pg_fieldname($record_cfpess, $index);
      global $$nam_campo;
//      echo "<BR> nam_campo --> $nam_campo";
      $$nam_campo = @pg_result($record_cfpess, 0, $nam_campo);
    }
    return $num_rows;
  }
}

function db_foto($numcgm,$db_opcao = 3,$javascript = "",$width="95",$height="120"){

  global $oid;

  if(trim($numcgm) != "" && $numcgm != null){
	  $result_foto = db_query("select rh50_oid as oid from rhfotos where rh50_numcgm = $numcgm");
	  if(pg_numrows($result_foto) > 0){
	 	  db_fieldsmemory($result_foto, 0);
	  }
  }

  $mostrarimagem = "imagens/none1.jpeg";
  if(isset($oid)){
  	$mostrarimagem = "func_mostrarimagem.php?oid=".$oid;
  }
  $href = "<img src='".$mostrarimagem."' border=0 width='".$width."' height='".$height."'>";
  db_ancora("$href","$javascript","$db_opcao");

}

function db_empty($string){

  if($string == '  /  /    ' || $string == '  -  -    '   || 
     $string == '    /  /  ' || $string == '    -  -  ' || $string == '()' ) {
    return true;
  }
  $string = trim($string);
  if(empty($string)){
    return true;
  }else{
    return false;
  }


}
function db_emptydata($string){

  $string = trim($string);
  if(empty($string)){
    return true;
  }else{
    return false;
  }

}
function db_dtos($string=null){
  
  // Obs : mantem o formato banco(Postgres) mas sem os caracters separadores de dia , mes e ano 
  // 2006-12-01 transforma em 20061201
  
  return db_strtran(db_strtran($string,'-',''), '/','');
}

function db_strtran($string=null,$quem_sai=null,$quem_entra=null){
    return str_replace($quem_sai,$quem_entra,$string);
}

function db_dtoc($string=null){
  
  // obs : transforma formato banco (Postgres) em caracter
  // 0123456789                0123456789
  // 2006-12-01 transforma em  01-12-2006
  
  return substr($string,8,2)."-".substr($string,5,2)."-".substr($string,0,4);
}

function db_dow($string=null){
 
 // Representação numérica do dia da semana 
 
  $retorna = date("w",db_mktime($string));
  return ($retorna==0?7:$retorna);
}

function db_ctod($string=null){
  
 // Obs : transforma caracter em formato banco(Postgres) 
 // 0123456789               0123456789
 // 01-12-2006 transforma em 2006-12-01
 
  return substr($string,6,4)."-".substr($string,3,2)."-".substr($string,0,2);
}
function db_mktime($string=null){
  
  // Obs : transforma formato banco (Postgres) em formato time para calculo de data
  // a funcao mktime exige a ordem mm,dd,aaaa
  // 0123456789                       
  // 2006-12-01 
  
  if(trim(substr($string,5,2)) != ""){
    return  db_strtotime($string);
  }else{
    return 0;
  }
}
function db_year($string=null){
  return substr($string,0,4)+0;
}

function db_month($string=null){
  return substr($string,5,2)+0;
}

function db_day($string=null){
  return substr($string,8,2)+0;
}

function db_datedif($pmktime=null,$smktime=null, $tipo='d'){
  if($tipo == 'd'){
    return pg_result(db_query("select '$pmktime'::date - '$smktime'::date as d"),0,'d');
//    return ceil((( mktime(0,0,0,substr($pmktime,5,2),substr($pmktime,8,2),substr($pmktime,0,4)) -
  //                  mktime(0,0,0,substr($smktime,5,2),substr($smktime,8,2),substr($smktime,0,4)))/86400));
  }else if($tipo == 'm'){
    return 0;
  }else if($tipo == 'y'){
    return 0;
  }else{
    return ceil((( mktime(0,0,0,substr($pmktime,5,2),substr($pmktime,8,2),substr($pmktime,0,4)) -
                    mktime(0,0,0,substr($smktime,5,2),substr($smktime,8,2),substr($smktime,0,4)))/86400));
  }

}

function db_val($string=0){
  if(!db_empty($string) && $string != null ){
    return $string+0;
  }else{
    return 0;
  }
}
function db_str($str,$quantidade=0,$digitos=0,$caracter= ' '){
  
 return str_pad($str, $quantidade, $caracter,0);
}

function db_substr($string=null,$posini=null,$quant=null){
  if($posini<0)
    return substr("#".$string,$posini);
  else
    return substr("#".$string,$posini,$quant);
}

function db_at($string_a_pesquisar=null,$string_pesquisa=null){

   if(!db_empty($string_a_pesquisar)){
     return strpos(strtoupper("#".$string_pesquisa),strtoupper($string_a_pesquisar))+0 ;
   }else{
     return 0;
   }
}

function db_sqlformat($variavel=null){
   if((is_string($variavel) && $variavel <> 'null') || trim($variavel) == ''){
       return "'".$variavel."'";
    }else if( is_bool($variavel)){
       if( $variavel == true ) {
           return "'t'";
       }else if( $variavel == false ) {
           return "'f'";
       }
    }else{
       return $variavel;
    }
}

function bb_round($valor=0,$dig=2){
  return round($valor,$dig);
}

function bb_condicaosubpes($prefixo=null, $sNomeTabela = null) {
  
  global $subpes;
  $retorno  = " where ".$prefixo."anousu = ".db_substr($subpes,1,4);
  $retorno .= "  and  ".$prefixo."mesusu = ".db_substr($subpes,6,2);
  
  if(db_at(strtoupper($prefixo),"R45_R61_R30_R51_R05_R12_R03_R27_R57_R42_R25_R28_R41_R52_R63_R17_R60_R64_R65_R37_R66_R67_") == 0 ){    
    $retorno .= "  and  ".$prefixo."instit = ".DB_getsession("DB_instit");
  }
  
  if ($sNomeTabela != null) {
    
    $aSiglasMatricula = array("cv01","h07","h09","h10","h16","h18","h22","h57","h60","h72","r01","r03","r10","r14","r17","r18","r19","r20","r21","r22","r26","r28","r29","r30","r31","r34","r35","r36","r38","r40","r45","r47","r48","r51","r52","r53","r54","r57","r58","r60","r61","r63","r64","r69","r90","r91","r92","r93","r94","rh01","rh02","rh09","rh10","rh101","rh108","rh109","rh11","rh112","rh118","rh126","rh134","rh139","rh143","rh144","rh15","rh16","rh17","rh19","rh21","rh31","rh49","rh54","rh57","rh61","rh62","rh66","rh67","rh77","rh85","rh93","rh96","rh99");
    
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && in_array($prefixo, $aSiglasMatricula)) {
  
      $sWherePontofs = "and exists (select 1                              
                                      from {$sNomeTabela}                        
                                     where r10_regist = {$prefixo}_regist        
                                       and r10_anousu = {$prefixo}_anousu        
                                       and r10_mesusu = {$prefixo}_mesusu ) ";
         
      $retorno .= $sWherePontofs;
    }
  }
  return $retorno;
}

function bb_condicaosubpesproc($prefixo=null,$subpes_procesamento=null){

//  $retorno  = " where "+$prefixo+ "anousu = "+db_anofolha();
//  $retorno .= "  and  "+$prefixo+ "mesusu = "+db_mesfolha();
  
  $retorno  = " where ".$prefixo."anousu = '".substr($subpes_procesamento,0,4)."'";
  $retorno .= "  and  ".$prefixo."mesusu = '".substr($subpes_procesamento,5,2)."'";
  if(db_at(strtoupper($prefixo),"R45_R61_R30_R51_R05_R12_R03_R27_R57_R42_R25_R28_R41_R52_R63_R17_R60_R64_R65_R37_R66_R67_") == 0 ){
     $retorno .= "  and  ".$prefixo."instit = ".DB_getsession("DB_instit");
  }   
  return $retorno;
  

}

function bb_condicaosubpesatual($prefixo=null){
 $submes = db_mesfolha();
 $subano = db_anofolha();

 $retorno = " where ".$prefixo."anousu = ".$subano." and ".$prefixo."mesusu = ".$submes;
 if(db_at(strtoupper($prefixo),"R45_R61_R30_R51_R05_R12_R03_R27_R57_R42_R25_R28_R41_R52_R63_R17_R60_R64_R65_R37_R66_R67_") == 0 ){
    $retorno .= "  and  ".$prefixo."instit = ".DB_getsession("DB_instit");
 }   
 return $retorno;
}

function bb_condicaosubpesanterior($prefixo=null){
 $submes = db_mesfolha()-1;
 $subano = db_anofolha();

// $submes = 1-1;
// $subano = 2006;


 if($submes < 1){
   $submes = 12;
   $subano -= 1;
 }
 $retorno = " where ".$prefixo."anousu = ".$subano." and ".$prefixo."mesusu = ".$submes;
 if(db_at(strtoupper($prefixo),"R45_R61_R30_R51_R05_R12_R03_R27_R57_R42_R25_R28_R41_R52_R63_R17_R60_R64_R65_R37_R66_R67_") == 0 ){
    $retorno .= "  and  ".$prefixo."instit = ".DB_getsession("DB_instit");
 }   
 return $retorno;
       

}

function db_selectmax($matriz=null,$query=null, $tabela = "", $campos=" * ", $order = "", $where = "", $group = ""){

  //echo "Select $matriz  - $query\n";
// system("echo psql -h 192.168.0.37 -U postgres auto_sap_2605 -c explain \"$query;\" >>/tmp/reis");

  if($query == null || trim($query) == ""){
    if($campos == null || trim($campos) == ""){
      $campos = " * ";
    }
    $query = " select ".$campos." from ".$tabela;
    if($where != null && trim($where) != ""){
      $query .= " where ".$where;
    }
    if($group != null && trim($group) != ""){
      $query .= " group by ".$group;
    }
    if($order != null && trim($order) != ""){
      $query .= " order by $order";
    }

  }
  global $$matriz;
  $aChavesCachear = array('rubr_', 'basesr', 'bases');
  $sNomeChave = $matriz;
  $indice = md5($sNomeChave."#".str_replace(" ", "_", $query));
  if (in_array($sNomeChave, $aChavesCachear)) {

    $chave = DBRegistry::get($indice);
    if (DBRegistry::has($indice)) {
      $$matriz = $chave;
      return true;
    }
  }
  $result = @db_query($query);
  //db_criatabela($result);
  global $$matriz;
  if($result!=false && pg_numrows($result)>0){
    
   // echo $matriz."[0]["";
    $dados   = pg_fetch_all($result);
    $$matriz = $dados;
    if (in_array($sNomeChave, $aChavesCachear)) {
      DBRegistry::add($indice, $dados);
    }

    //print_r($$matriz);
    
    return true;
  }else{
    if($result==false){
      echo "Erro no query:  $query ";
      exit;
    }else{
      $$matriz = null;
      return false;
    }
  }
}

function db_boolean($variavel=null){

  if($variavel=='f')
    return false;
  else
    return true;
  
}

function db_criatemp($nome_tab,$mat_campos,$mat_tipos,$mat_tamanho,$mat_deci, $qual_alias=null,$asSQL=null){

//$private nome_tab,mat_campos,mat_tipos,mat_tamanho,mat_deci, retorno;

   $monta_string = "";
   $string_campos = "";
   $retorno = true;

   $monta_string = 'create temporary table '.$nome_tab;
   if($asSQL==null || trim($asSQL == "")){
     $virgula = "";
     $monta_string.= ' (';
     for( $i = 1;$i <= count($mat_campos);$i++){
       if( $mat_tipos[$i] == "c"){
          $tipo_campo = "char(".$mat_tamanho[$i].")";
       }elseif( $mat_tipos[$i] == "n" && $mat_deci[$i] > 0){
          $tipo_campo = "float8 default 0";
       }elseif( $mat_tipos[$i] == "n" && $mat_deci[$i] == 0){
          $tipo_campo = "integer default 0 ";
       }elseif( $mat_tipos[$i] == "d"){
          $tipo_campo = "date";
       }elseif( $mat_tipos[$i] == "l"){
          $tipo_campo = "boolean";
       }elseif( $mat_tipos[$i] == "m"){
          $tipo_campo = "text";
       }
       $string_campos = $string_campos.$virgula.$mat_campos[$i]." ".$tipo_campo." ";
       
       $virgula = ",";
     }
     $string_campos = $string_campos.")";
//echo "<BR> $monta_string $string_campos";
//exit;
     $retorno = db_query($monta_string.$string_campos);
   }else{
//echo "<BR> $monta_string as $asSQL";
//exit;
     $retorno = db_query($monta_string." as ".$asSQL);
   }
   
   return $retorno;
}

function db_update($tabela,$mat_campos,$mat_valores,$condicao_a){
  $linha1 = "";
  $linha2 = "";

  $linha1 = "update ".$tabela." set ";
  $virgula = "";
  for($i=1;$i <= count($mat_campos);$i++){

     $mat_valores[$i] = db_sqlformat($mat_valores[$i]);
     $linha2 .=  $virgula.$mat_campos[$i]." = ".$mat_valores[$i];
     $virgula = ",";
  }

  if($condicao_a != null && trim($condicao_a) != ""){
    if(strpos($condicao_a,"where") === false){
      $condicao_a = " where ".$condicao_a;
    }
  }

//  echo "<BR><BR><BR>".$linha1." ".$linha2." ".$condicao_a.";<BR><BR>";
  $db_sql = db_query($linha1." ".$linha2." ".$condicao_a);

  if($db_sql == null){
    echo ("erro ".$linha1." ".$linha2." ".$condicao_a);
    exit;
  }
  return $db_sql;
}

function db_delete($tabela=null,$condicao_deleta=null,$sem_condicao=null){

  if( $condicao_deleta == null && $sem_condicao == null ){
     echo ("execucao de exclusao nao permitida. contate cpd!") ;
     exit;
  }

//  echo "<BR><BR><BR>"."delete from ".$tabela." ".$condicao_deleta.";<BR><BR>";

  if(strpos($condicao_deleta, "where") === false){
    $condicao_deleta = " where " . $condicao_deleta;
  }

  $db_sql = db_query("delete from ".$tabela." ".$condicao_deleta);

  if($db_sql == false){
    echo ("delete from ".$tabela." ".$condicao_deleta);
    db_query("rollback");
    exit;
  }
  return $db_sql;
}

function db_insert($tabela,$mat_campos,$mat_valores,$execdie=true){
  $linha2  = "";
  $linha3  = "";
  $linha4  = "";
  
  //if($tabela=="gerfsal")
  //  $tabela="gerfsal_calc";
    
  $linha1  = "insert into ".$tabela."(";
  $virgula = "";
  for( $ii = 1;$ii <= count($mat_campos);$ii++){
     $linha2 .=  $virgula.$mat_campos[$ii];
     $virgula = ",";
  }
  // echo "<BR><BR> $tabela -*- ".$linha2;
  $linha2 .= ")";
  $linha3 = " values (";
  $virgula = "";
  for($ii = 1;$ii <= count($mat_valores);$ii++){
     if( $ii+1 > count($mat_valores)){
       // if (type( $mat_valores[$ii] ) == "c" && strtoupper(db_substr($mat_valores[$ii],1,8)) == "DB_OID: "){
       //    $mat_valores[$ii] = "lo_import(".db_sqlformat(db_substr($mat_valores[$ii],9)).")";
       // }else{
           $mat_valores[$ii] = db_sqlformat($mat_valores[$ii]);
       // }
        $linha4 .=  $mat_valores[$ii].")";
     }else{
       // if ( type( $mat_valores[$ii] ) == "c" && strtoupper(db_substr($mat_valores[$ii],1,8)) == "DB_OID: "){
       //    $mat_valores[$ii] = "lo_import(".db_sqlformat(db_substr($mat_valores[$ii],9)).")";
       // }else{
	 
           $mat_valores[$ii] = db_sqlformat($mat_valores[$ii]);
       // }
        $linha4 .=  $mat_valores[$ii].",";
     }
  }
  
  // echo "<BR><BR><BR>".($linha1.$linha2.$linha3.$linha4).";<BR><BR>";
  $db_sql = db_query($linha1.$linha2.$linha3.$linha4);
  if( $db_sql == false ){
  	if($execdie == true){
      echo ("erro ao tentar gravar em ".$tabela);
      echo "<br>".$linha1.$linha2.$linha3.$linha4;
      exit;
  	}
  }
  return $db_sql;
}

function ndias ($pmesano){
  
  global $d08_carnes;

  $mmes = db_val(db_substr($pmesano,1,2));
  $mano = db_val(db_substr($pmesano,4,4));
  //echo "<BR> mmes --> $mmes";
  //echo "<BR> mano --> $mano";
  
  if( $mmes == 1 || $mmes == 3 || $mmes == 5 || $mmes == 7 || $mmes == 8 || $mmes == 10 || $mmes == 12 ){
    $dias = 31;
  //echo "<BR> 1 dias --> $dias";
  }else if( $mmes == 2 ){
    if( $mano%4 == 0){  
       $dias = 29;
  //echo "<BR> 2 dias --> $dias";
    }else{
       $dias = 28;
  //echo "<BR> 3 dias --> $dias";
    }
  }else{
    $dias = 30;
  //echo "<BR> 4 dias --> $dias";
  }
  return $dias;
}

function per_fpagto ($tipo=null){
  global $subpes;
  $retmesano = db_substr($subpes,6,2)."/".db_substr($subpes,1,4);
  return $retmesano;
}

function bb_space($numero){
  return str_pad(" ",$numero);
}

function db_dias_pagto($registro=null,$r45_dtreto,$r45_dtafas){
  
  global $dias_pagamento, $data_afastamento, $dtfim,$subpes,$r45_situac;
  $dias_mes = ndias(db_substr($subpes,-2)."/".db_substr($subpes,1,4));
  $dtini = db_ctod("01/".db_substr($subpes,-2)."/".db_substr($subpes,1,4));
  $dtfim = db_ctod(db_str($dias_mes,2,0,"0")."/".db_substr($subpes,-2)."/".db_substr($subpes,1,4));
//echo "<BR> dias_mes --> $dias_mes dtini --> $dtini dtfim --> $dtfim";  
//echo "<BR> r45_dtafas --> $r45_dtafas r45_dtreto --> $r45_dtreto";
  $dias_pagamento = 30;
  $afastado = 1;
  $data_afastamento = date("Y-m-d",db_getsession("DB_datausu"));
//echo "<BR> 0  fastado--> $afastado";          
  if( db_mktime($r45_dtreto) >= db_mktime($dtini) || db_empty($r45_dtreto)){
     $afastado = $r45_situac;
//echo "<BR> 1  fastado--> $afastado";          
     if( !db_empty($r45_dtreto) ){
        if( db_mktime($r45_dtafas) > db_mktime($dtfim) ){
           $afastado = 1;
//echo "<BR> 2  fastado--> $afastado";          
        }
        if(isset($dtfim) || !db_empty($dtfim)){
          if( db_mktime($r45_dtreto) < db_mktime($dtfim) ){
//             $afastado = 1;
//echo "<BR> 3  fastado--> $afastado";          
          }
        }
     }
     if( $afastado != 1){
//echo "<BR> 0 dias_pagamento --> $dias_pagamento";          
        if(  db_mktime($r45_dtreto)==0 || db_mktime($r45_dtreto) > db_mktime($dtfim) ) { 
          if( db_mktime($r45_dtafas) >= db_mktime($dtini) ){
             $dias_pagamento = db_datedif($r45_dtafas,$dtini);
//echo "<BR> 1 dias_pagamento --> $dias_pagamento";          
          }else{
             $dias_pagamento = 0;
//echo "<BR> 2 dias_pagamento --> $dias_pagamento";          
          }
        }else if( db_mktime($r45_dtreto) <= db_mktime($dtfim) ){ 
           if( db_mktime($r45_dtafas) < db_mktime($dtini) ){ 
              $dias_pagamento = db_datedif($dtfim,$r45_dtreto);
//echo "<BR> 3 dias_pagamento --> $dias_pagamento";          
              if( $dias_pagamento > 0 ){
              	 if( $dias_mes > 30){
     	              $dias_pagamento -= 1;
//echo "<BR> 4 dias_pagamento --> $dias_pagamento";          
               	 }else if( $dias_mes == 29){ 
     	              $dias_pagamento = (30 - db_day($r45_dtreto));
//echo "<BR> 5 dias_pagamento --> $dias_pagamento";          
     	           }
              }
           }else { 
             $dias_pagamento = ceil(((db_mktime($dtfim) - db_mktime($r45_dtreto) + db_mktime($r45_dtafas) - db_mktime($dtini))/86400));
//echo "<BR> 6 dias_pagamento --> $dias_pagamento";          
              if( !db_empty($dias_pagamento)){
             	   if( $dias_mes > 30){
     	             $dias_pagamento -= 1;
//echo "<BR> 7 dias_pagamento --> $dias_pagamento";          
//           	   }else if( $dias_mes < 30){ 
//    	             $dias_pagamento = (30 - $dias_mes);
//echo "<BR> 8 dias_pagamento --> $dias_pagamento";          
     	           } 
              }
           } 
       }
        $data_afastamento = $r45_dtafas;
     }
  }

}

function situacao_funcionario ($registro=null,$datafim=null){
  // variaveis publicas
  
  global $dias_pagamento, $data_afastamento, $dias_pagamento_sf, $dtfim,$subpes,$pessoal,$Ipessoal;
  
  //echo "<BR 1.10 - ndias --> ";
  $dias_mes = ndias(db_substr($subpes,-2)."/".db_substr($subpes,1,4));
  //echo "<BR 1.10 - saiu ndias --> ";
  $dtini = db_ctod("01/".db_substr($subpes,-2)."/".db_substr($subpes,1,4));
  $dtfim = db_ctod(db_str($dias_mes,2,0,"0")."/".db_substr($subpes,-2)."/".db_substr($subpes,1,4));
  $dias_pagamento = 30;
  $dias_pagamento_sf = 30;
  $afastado = 1;
  $data_afastamento = date("Y-m-d",db_getsession("DB_datausu"));
  $condicaoaux = " and r45_regist =". db_sqlformat( $registro )." order by r45_regist, r45_dtafas desc"  ;
  global $afasta;
  if( db_selectmax( "afasta", "select * from afasta ".bb_condicaosubpes( "r45_").$condicaoaux )){

    if( db_mktime($afasta[0]["r45_dtreto"]) >= db_mktime($dtini) || db_empty($afasta[0]["r45_dtreto"])){

      /**
       * Caso Afastamento de doença for do tipo mais de 30 dias considera como afastamento
       * somente o período superior aos 30 para isso adianta a data em 30 dias
       *
       * Ex.: 45 dias de afastamento considera com afastamento apenas 15 dias
       */
      if($afasta[0]["r45_situac"] == Afastamento::AFASTADO_DOENCA_MAIS_30_DIAS) {

        $sDataAfastamento        = new DBDate($afasta[0]["r45_dtafas"]);
        $afasta[0]["r45_dtafas"] = $sDataAfastamento->adiantarPeriodo(30, 'd')->getDate();
      }

      // Caso acha afastamento e data de retorno for maior ou igual da de afastamento ou retornou

       $afastado = $afasta[0]["r45_situac"];
       if( !db_empty($afasta[0]["r45_dtreto"]) ){
	        if( db_mktime($afasta[0]["r45_dtafas"]) > db_mktime($dtfim) ){
	           $afastado = 1;
	        }
	        if(isset($datafim) || !db_empty($datafim)){
	           if( db_mktime($afasta[0]["r45_dtreto"]) < db_mktime($datafim) ){
	              $afastado = 1;
	           }
	        }
	     }
       //echo "<BR> ".$afasta[0]["r45_dtreto"]." > ".$dtfim."  && ".$afasta[0]["r45_dtafas"]." < ".$dtini; 
	     if( $afastado != 1){
	       if( ( db_mktime($afasta[0]["r45_dtreto"])==0 || db_mktime($afasta[0]["r45_dtreto"]) > db_mktime($dtfim)  ) && db_mktime($afasta[0]["r45_dtafas"]) >= db_mktime($dtini) ){
	         $dias_pagamento = db_datedif($afasta[0]["r45_dtafas"],$dtini);
	       }else if( ( db_empty( $afasta[0]["r45_dtreto"]) || db_mktime($afasta[0]["r45_dtreto"]) > db_mktime($dtfim)  ) && db_mktime($afasta[0]["r45_dtafas"]) < db_mktime($dtini) ){ 
 	         $dias_pagamento = 0;
	       }else if( db_mktime($afasta[0]["r45_dtafas"]) < db_mktime($dtini) && db_mktime($afasta[0]["r45_dtreto"]) <= db_mktime($dtfim) ){ 
	         $dias_pagamento = db_datedif($dtfim,$afasta[0]["r45_dtreto"]);
	         if( $dias_pagamento > 0 ){
		         if( $dias_mes > 30){
		           $dias_pagamento -= 1;
		         }else if( $dias_mes == 29){ 
		           $dias_pagamento = (30 - db_day($afasta[0]["r45_dtreto"]));
		         }
	         }
	       }else if( db_mktime($afasta[0]["r45_dtafas"]) >= db_mktime($dtini) && db_mktime($afasta[0]["r45_dtreto"]) <= db_mktime($dtfim)){ 
	         $dias_pagamento = ceil(((db_mktime($dtfim) - db_mktime($afasta[0]["r45_dtreto"]) + db_mktime($afasta[0]["r45_dtafas"]) - db_mktime($dtini))/86400));
	         if( !db_empty($dias_pagamento)){
		         if( $dias_mes > 30){
		            $dias_pagamento -= 1;
		         }else if( $dias_mes < 30){ 
		            $dias_pagamento += (30 - $dias_mes);
		         }
	         }
	       }
	       $data_afastamento = $afasta[0]["r45_dtafas"];
	     }
    }
  }else{
     if( db_year($pessoal[$Ipessoal]["r01_admiss"]) == db_val(db_substr($subpes,1,4)) 
	   && db_month($pessoal[$Ipessoal]["r01_admiss"]) == db_val(db_substr($subpes,-2)) ){
       
       //echo "<BR> Admissao efetuada no ano e mes da folha";

     	 if( $dias_mes > 30){
	       $dias_pagamento_sf = $dias_mes - ( db_day($pessoal[$Ipessoal]["r01_admiss"]));
	       $dias_pagamento = $dias_mes - ( db_day($pessoal[$Ipessoal]["r01_admiss"]));
	     }else if( $dias_mes = 30){ 
	       $dias_pagamento_sf = $dias_mes - ( db_day($pessoal[$Ipessoal]["r01_admiss"]) - 1);
	       $dias_pagamento = $dias_mes - ( db_day($pessoal[$Ipessoal]["r01_admiss"]) - 1);
	     }else{
	       $dias_pagamento_sf = 30 - ( db_day($pessoal[$Ipessoal]["r01_admiss"]) - 1);
	       $dias_pagamento    = 30 - ( db_day($pessoal[$Ipessoal]["r01_admiss"]) - 1);
	     }
     }
     if(!db_empty($pessoal[$Ipessoal]["r01_recis"])){ 

       if( db_year($pessoal[$Ipessoal]["r01_recis"]) == db_val(db_substr($subpes,1,4)) 
	     && db_month($pessoal[$Ipessoal]["r01_recis"]) == db_val(db_substr($subpes,-2)) ){
          
          //echo "<BR> Rescisao efetuada no ano e mes da folha";
       
	        $dias_pagamento_sf = db_day($pessoal[$Ipessoal]["r01_recis"]);
       }elseif( (db_year($pessoal[$Ipessoal]["r01_recis"]) < db_val(db_substr($subpes,1,4))) 
		             || (db_year($pessoal[$Ipessoal]["r01_recis"]) == db_val(db_substr($subpes,1,4))
		                 && db_month($pessoal[$Ipessoal]["r01_recis"]) < db_val(db_substr($subpes,-2))
		    						)
		    			)
		   {
           //echo "<BR> Rescisao efetuada antes do ano e mes da folha --> ".$pessoal[$Ipessoal]["r01_recis"];
       
		       $dias_pagamento_sf = 0;
		   }
     }
  }
//  echo "<br>  dias_pagamento_sf --> $dias_pagamento_sf ";
  return $afastado;

}

function ver_idade($dhoje,$dnasc){

$idade = db_substr($dhoje,7,4)-db_substr($dnasc,7,4);

if(db_substr($dhoje,4,2) < db_substr($dnasc,4,2)){
   $idade -= 1;
}

return $idade;
   
}

function ferias($registro,$cfuncao = ""){

  global $subpes,$cadferia, $F016 ,$F017 ,$F018 ,$F019 , $F020 ,$F021 , $F023,$cfpess,$d08_carnes;
  
  $F019 = 0;   // Numero de dias a pagar de ferias 
  $F020 = 0;   // Numero de dias de abono p/ pagar de ferias
  $F021 = 0;   // Numero de dias p/ calc do FGTS no mes
  $F023 = 0;   // Numero de dias de Adiantamento de ferias

  $anomes = db_substr($subpes,1,4).db_substr($subpes,6,2);

  $condicaoaux  =  " and r30_regist = ".db_sqlformat($registro);
  $condicaoaux .= " order by r30_perai desc limit 1";
  if( db_selectmax( "cadferia", "select * from cadferia ".bb_condicaosubpes("r30_").$condicaoaux )){

      // r30_proc1 --> Funcionário com férias já cadastradas para o proximo ano / mês
      // r30_proc2 --> Funcionário com saldo de férias para o proximo ano / mês

     if( db_substr($cadferia[0]["r30_proc1"],1,4).db_substr($cadferia[0]["r30_proc1"],6,2)  > $anomes || 
	       db_substr($cadferia[0]["r30_proc2"],1,4).db_substr($cadferia[0]["r30_proc2"],6,2)  > $anomes ){
       	return;
     }

     // r30_proc1d --> Funcionário com diferença de férias para este ano / mês.
     if( db_empty($cadferia[0]["r30_proc1d"]) || $cadferia[0]["r30_proc1d"] == $subpes){
      	
        $F019 = $cadferia[0]["r30_dias1"];
       	$F020 = $cadferia[0]["r30_abono"];

        $dias_ = ndias(db_substr(db_dtoc($cadferia[0]["r30_per1i"]),4,7));
       	$maxdiac = db_str($dias_,2,0,"0")         ;
        //echo "<BR> 1.11 - maxdiac --> $maxdiac";
      	if( db_substr(db_dtos($cadferia[0]["r30_per1i"]),1,6) == $anomes && 
    	      db_substr(db_dtos($cadferia[0]["r30_per1f"]),1,6) == $anomes){
            $F021 = $F019;
       	}else if( db_substr(db_dtos($cadferia[0]["r30_per1i"]),1,6) < $anomes && 
	                db_substr(db_dtos($cadferia[0]["r30_per1f"]),1,6) == $anomes){
            $F019 = db_datedif( $cadferia[0]["r30_per1f"] , db_ctod("01/".db_substr(db_dtoc($cadferia[0]["r30_per1f"]),4,7)) ) + 1 ;
            //echo "<BR> 1.12 - F019 --> $F019";
            $F020 = 0 ;
            $F021 = $F019;
	          if( strtolower($cfpess[0]["r11_fersal"]) == 's' && !db_boolean( $cfpess[0]["r11_recalc"] )){
               $F019 = 0;
               $F021 = 0;
	          }
	      }else if( db_substr(db_dtos($cadferia[0]["r30_per1i"]),1,6) == $anomes && 
	                db_substr(db_dtos($cadferia[0]["r30_per1f"]),1,6) > $anomes ){

            $s_data2 = db_substr(db_dtoc($cadferia[0]["r30_per1i"]),7,4).'-'.db_substr(db_dtoc($cadferia[0]["r30_per1i"]),4,2).'-'.$maxdiac;
            $s_data1 = $cadferia[0]["r30_per1i"];
            $F019 = pg_result(db_query("select '$s_data2'::date - '$s_data1'::date as d"),0,'d') + 1;

            //echo "<BR> 1.12 - F019 --> $F019";
	          $F020 = $cadferia[0]["r30_abono"];
	          $F021 = $F019;
	          if( strtolower($cfpess[0]["r11_fersal"]) == 's' 
                && !db_boolean( $cfpess[0]["r11_recalc"] ) 
				        && db_month( $cadferia[0]["r30_per1i"] ) == 2 
                && db_year($cadferia[0]["r30_per1i"]) == db_substr($subpes,1,4)
                && $F019 == ndias($dias_)){
		           $F019 = 30;
           	  $F021 = 30;
	          }
	          $F023 = $cadferia[0]["r30_dias1"] - $F019;
      	}else if(db_substr(db_dtos($cadferia[0]["r30_per1i"]),1,6) > $anomes &&  
	               db_substr(db_dtos($cadferia[0]["r30_per1f"]),1,6) > $anomes){
	         $F023 = $F019;
	         $F019 = 0 ;
	         $F021 = 0  ;
	      }else if( db_substr(db_dtos($cadferia[0]["r30_per1i"]),1,6) < $anomes ){ 
	         $F019 = 0;
	         $F020 = 0;
	         $F021 = 0;
	      }
       if( $cadferia[0]["r30_tip1"] == "11"){
	        $F020 = 0;
	     }
       if(    db_month($cadferia[0]["r30_per1i"]) == 2  
           && db_year($cadferia[0]["r30_per1i"])  == db_substr($subpes,1,4) 
           && $cadferia[0]["r30_tip1"]            == "01" 
           && db_empty($cadferia[0]["r30_proc2d"]) 
           && !db_boolean( $cfpess[0]["r11_recalc"])
         ){
         if( db_substr($subpes,6,2) == 2){
           $F019 = $cadferia[0]["r30_dias1"];
           $F020 = 0;
           $F021 = 0;
           $F023 = 0;
         }elseif( db_substr($subpes,6,2) == 3){
           $F019 = 0;
           $F020 = 0;
           $F021 = 0;
           $F023 = 0;
         }
       }

       // r30_proc2d --> Funcionário com diferença de saldo de férias já cadastradas para este ano / mês

	     if( db_empty($cadferia[0]["r30_proc2d"]) || $cadferia[0]["r30_proc2d"] == $subpes){ 
	        if( $cadferia[0]["r30_tip2"] == "10"){

             // Saldo em Abono 

	           $F019 = 0;
	           $F020 = $cadferia[0]["r30_abono"];
	           $F021 = 0;
	        }else if( $cadferia[0]["r30_tip2"] == "09"){ 

             // saldo de Ferias
             $F019 = $cadferia[0]["r30_dias2"];

              //  echo "<BR> 1.12 - F019 --> $F019   F020 --> $F020   F023 --> $F023 ";
       	      if( db_substr(db_dtos($cadferia[0]["r30_per2i"]),1,6) < $anomes && 
	                      db_substr(db_dtos($cadferia[0]["r30_per2f"]),1,6) == $anomes){
                        $F019 = db_datedif( $cadferia[0]["r30_per2f"] , db_ctod("01/".db_substr(db_dtoc($cadferia[0]["r30_per2f"]),4,7)) ) + 1 ;
                        //echo "<BR> 1.12 - F019 --> $F019";
                        $F020 = 0 ;
                        $F021 = $F019;
	                      if( strtolower($cfpess[0]["r11_fersal"]) == 's' && !db_boolean( $cfpess[0]["r11_recalc"] )){
                           $F019 = 0;
                           $F021 = 0;
	                      }
	            }else if( db_substr(db_dtos($cadferia[0]["r30_per2i"]),1,6) == $anomes && 
	                      db_substr(db_dtos($cadferia[0]["r30_per2f"]),1,6) > $anomes ){
        
                  $dias2_   = ndias(db_substr(db_dtoc($cadferia[0]["r30_per2i"]),4,7));
       	          $maxdiac2 = db_str($dias2_,2,0,"0")         ;
              
                  $s_data2 = db_substr(db_dtoc($cadferia[0]["r30_per2i"]),7,4).'-'.db_substr(db_dtoc($cadferia[0]["r30_per2i"]),4,2).'-'.$maxdiac2;
                  $s_data1 = $cadferia[0]["r30_per2i"];
                  $F019 = pg_result(db_query("select '$s_data2'::date - '$s_data1'::date as d"),0,'d') + 1;
              
                  //echo "<BR> 1.12 - F019 --> $F019";
	                $F020 = $cadferia[0]["r30_abono"];
	                $F021 = $F019;
	                if( strtolower($cfpess[0]["r11_fersal"]) == 's' 
                      && !db_boolean( $cfpess[0]["r11_recalc"] ) 
				              && db_month( $cadferia[0]["r30_per2i"] ) == 2 
                      && db_year($cadferia[0]["r30_per2i"]) == db_substr($subpes,1,4)
                      && $F019 == ndias($dias_)){
		                 $F019 = 30;
                 	  $F021 = 30;
	                }
	                $F023 = $cadferia[0]["r30_dias2"] - $F019;
      	      }else if(db_substr(db_dtos($cadferia[0]["r30_per2i"]),1,6) > $anomes &&  
	                     db_substr(db_dtos($cadferia[0]["r30_per2f"]),1,6) > $anomes){
	               $F023 = $F019;
	               $F019 = 0 ;
	               $F021 = 0  ;
	            }else if( db_substr(db_dtos($cadferia[0]["r30_per2i"]),1,6) < $anomes ){ 
	               $F019 = 0;
	               $F020 = 0;
	               $F021 = 0;
              }





/*

	           if( db_substr(db_dtos($cadferia[0]["r30_per2i"]),1,6) == $anomes && 
		             db_substr(db_dtos($cadferia[0]["r30_per2f"]),1,6) >= $anomes ){
		            $F019 = db_datedif($cadferia[0]["r30_per2f"],$cadferia[0]["r30_per2i"]) + 1;
		            $F020 = 0;
		            $F021 = $F019;
	           }else if( db_substr(db_dtos($cadferia[0]["r30_per2i"]),1,6) < $anomes &&  
		                   db_substr(db_dtos($cadferia[0]["r30_per2f"]),1,6) == $anomes ){
                $F019 = db_datedif($cadferia[0]["r30_per2f"] ,db_ctod("01/".db_substr(db_dtoc($cadferia[0]["r30_per2f"]),4,2)."/".db_substr(db_dtoc($cadferia[0]["r30_per2f"]),7,4)) ) + 1 ;

                //echo "<BR> 1.13 - F019 --> $F019";
		            $F020 = 0;
		            $F021 = $F019;
	           }else if( db_substr(db_dtos($cadferia[0]["r30_per2i"]),1,6) > $anomes ){
	              $F023 = $F019;
		            $F019 = 0 ;
		            $F020 = 0 ;
		            $F021 = 0 ;
             }else{
                $F019 = 0;
                $F020 = 0;
                $F021 = 0;
	           }


*/
             //   echo "<BR> 1.13 - F019 --> $F019   F020 --> $F020   F023 --> $F023 ";

	        }
	        if( db_at($cfuncao , "fecha_folha")>0 && db_empty($cadferia[0]["r30_proc2d"])){
	           if( $F019 != 0 || $F020 != 0){
	              $perai = $cadferia[0]["r30_perai"];
		            $condicaoaux  = " and r30_regist = ".db_sqlformat( $registro );
		            $condicaoaux .= " and r30_perai = ".db_sqlformat( $perai );
		            $matriz1 = array();
		            $matriz2 = array();
		            $matriz1[1] = "r30_proc2d";
		            $matriz2[1] = $subpes;
		            db_update( "cadferia", $matriz1,$matriz2, bb_condicaosubpes( "r30_" ).$condicaoaux )                 ;
	           }
	        }
	     }
    }

  }
  return true;
}

function db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi=null,$r110_regisf=null,$r110_lotaci=null,
                           $r110_lotacf=null,$faixa_regis=null,$faixa_lotac=null,$sigla2=null){

if($sigla2 == null){
    $sigla2 = $siglag;
}

$condicaoaux = "";
        if( $opcao_filtro == "i"){
           if(       $opcao_gml == "m"){
              $condicaoaux  = " and ".$siglag."regist between ".db_sqlformat($r110_regisi);
              $condicaoaux .= " and ".db_sqlformat($r110_regisf);
           }else if( $opcao_gml == "l"){
              if($siglag == "rh02_"){ 
                $condicaoaux  = " and ".$siglag."lota between ".db_sqlformat($r110_lotaci);
                $condicaoaux .= " and ".db_sqlformat($r110_lotacf);
              }else{
                $condicaoaux  = " and to_number(".$siglag."lotac,'9999') between $r110_lotaci and $r110_lotacf ";
              }
           }
        }else{
           if(     $opcao_gml == "m" && !db_empty($faixa_regis)){
              $condicaoaux  = " and ".$siglag."regist in (".$faixa_regis.")";

           }elseif($opcao_gml == "l" && !db_empty($faixa_lotac)){
              if($siglag == "rh02_"){ 
                 $condicaoaux  = " and ".$siglag."lota in (".$faixa_lotac.")";
              }else{
                 $condicaoaux  = " and to_number(".$siglag."lotac,'9999') in (".$faixa_lotac.")";
              }
           }
        }
 return $condicaoaux;
}

function db_debug($string,$tempo=null){
  global $db_debug;
  if($db_debug==true){
//    echo $string."\n";
 //   if($tempo!=null)
  //   sleep($tempo);
  }
  
}

function db_debug1($string,$tempo=null){
  global $db_debug;
  if($db_debug==true){
    //echo $string."<br>";
    //if($tempo!=null)
    //  sleep($tempo);
  }
  flush();
  
}

function numero_faltas($registro, $data_inicio, $data_final ){
  global $protelac,$Iprotelac,$assenta;
  
 db_debug1("entrou na funcao numero_faltas"); 
  $num_faltas = 0;
  db_selectmax( "protelac", "select * from protelac " );
  for($Iprotelac=0;$Iprotelac<count($protelac);$Iprotelac++){
   if( $protelac[$Iprotelac]["h19_tipo"] == "f"){
      $condicaoaux  = " where h16_assent = ".db_sqlformat( $protelac[$Iprotelac]["h19_assent"] );
      $condicaoaux .= " and h16_regist = ".db_sqlformat( $registro );
      if( db_selectmax( "assenta", "select * from assenta ".$condicaoaux ) ){
         for($Iassenta=0;$Iassenta<count($assenta);$Iassenta++) 
            if( db_mktime($assenta[$Iassenta]["h16_dtterm"]) < db_mktime($data_inicio) ){
               $data_ini = db_ctod("");
               $data_fim = db_ctod("");
            }else if( db_mktime($assenta[$Iassenta]["h16_dtconc"]) > db_mktime($data_final)){ 
               $data_ini = db_ctod("");
               $data_fim = db_ctod("");
            }else if( db_mktime($assenta[$Iassenta]["h16_dtconc"]) <= db_mktime($data_inicio) && db_mktime($assenta[$Iassenta]["h16_dtterm"]) >= db_mktime($data_final)){ 
               $data_ini = $data_inicio;
               $data_fim = $data_final;
            }else if( db_mktime($assenta[$Iassenta]["h16_dtconc"]) >= db_mktime($data_inicio) && db_mktime($assenta[$Iasseta]["h16_dtterm"]) <= db_mktime($data_final)){ 
               $data_ini = $assenta[$Iassenta]["h16_dtconc"];
               $data_fim = $assenta[$Iassenta]["h16_dtterm"];
            }else if( db_mktime($assenta[$Iassenta]["h16_dtconc"]) >= db_mktime($data_inicio) && db_mktime($assenta[$Iassenta]["h16_dtterm"]) >= db_mktime($data_final)){ 
               $data_ini = $assenta[$Iassenta]["h16_dtconc"];
               $data_fim = $data_final;
            }else if( db_mktime($assenta[$Iassenta]["h16_dtconc"]) >= db_mktime($data_inicio) && db_mktime($assenta[$Iassenta]["h16_dtterm"]) >= db_mktime($data_final)){ 
               $data_ini = $assenta[$Iassenta]["h16_dtconc"];
               $data_fim = $data_final;
            }
            $num_faltas += db_datedif($data_fim,$data_ini) - 1;
         }
      }
   }
 return $num_faltas;
}

/**
 * @deprecated
 * @see DBPessoal::getQuantidadeAvos
 * @param unknown $r30_perai
 * @param unknown $r30_peraf_ant
 * @param unknown $r30_peraf
 * @return number
 */
function retorna_avos($r30_perai,$r30_peraf_ant,$r30_peraf){


 if( db_mktime($r30_peraf) < db_mktime($r30_peraf_ant) ){
 
    $navos = 0;
    if( ndia($r30_peraf) == ndia($r30_perai) ) {
       // no mesmo ano so ver a diferenca de meses
       $navos = db_month($r30_peraf) - db_month($r30_perai); 
    }else{
    
       // meses restante do ano anterior mais meses do ano posterior
       // (12 - mes) = quantidade de meses a contar como periodo aquisitivo no ano
       
       $novos = (12 - db_month($r30_perai) )  +db_month($r30_peraf);
    }
    
    if( (ndia($r30_peraf) - ndia($r30_perai)) > 14 ) {
       // a fração superior a 14 dias - 1/12 avo. , conta como um mes a mais
       $navos++;
    }
 }else{
 
    $navos = db_datedif($r30_peraf_ant,$r30_perai) / 30;
    
    if( $navos < 0 ){
       $navos = $navos * (-1);
    }
 
    // observacao : o periodo aquisitivo nao pode ser maior que um ano ou seja 12 meses
    if( $navos > 12){
       $navos = 12;
    }
 }
	 
 return $navos;
}

function dias_gozo($opcao,$mtipo,$r30_ndias=0) {

$nsaldo = 0;

if($opcao == "S") {

   if($mtipo == "09"){
      $nsaldo = $cadferia[0]["r30_ndias"] - $cadferia[0]["r30_dias1"] + $cadferia[0]["r30_dias2"] + $cadferia[0]["r30_abono"];
      
   }else{
      $nsaldo = 0;
   }
   
}else{

   if($mtipo >= "01" && $mtipo <= "04"){
      
      // mtipo de 02, 03 , 04 vai sobrar saldo
      
      if($mtipo == "01"){
         $nsaldo = $r30_ndias;
      }else if($mtipo == "02"){
         $nsaldo = 20;
      }else if($mtipo == "03"){
         $nsaldo = 15;
      }else if($mtipo == "04"){
         $nsaldo = 10;
      }
   }else if(db_at($mtipo,"05 ,06, 07, 08, 99") > 0){  // quando forma de pgto tiver abono
      // 20 dias de ferias e 10 dias de abono
      }else if($mtipo == "05"){
         $nsaldo = 20;
      }else if($mtipo == "06"){
         $nsaldo = 15;
      }else if($mtipo == "07"){
         $nsaldo = 10;
      }else if($mtipo == "08"){
         $nsaldo = 0;
      } 
   }
   
 return $nsaldo; 

}

function tabela_gozo($nfaltas,$navos) { 

   if($nfaltas <= 05) {
        $k = 2.5; 
   }else if( $nfaltas <= 14) {
        $k = 2.0;
   }else if( $nfaltas <= 23) {
        $k = 1.5;
   }else if( $nfaltas <= 32) {
        $k = 1;
   }else{
        $k = 0;
   }

  return ($k * $navos);
}

function verificaseexisteafastamentonoperiodo($registro, $inicio, $fim){

  global $subpes;

  $cond  = "select rh02_regist as r01_regist,r45_regist,r69_regist ";
  $cond .= " from rhpessoalmov ";
  $cond .= " left outer join afastamento ";
  $cond .= "    on r69_anousu = ".db_sqlformat( db_substr($subpes,1,4) )  ;
  $cond .= "   and r69_mesusu = ".db_sqlformat( db_substr($subpes,6,2) )  ;
  $cond .= "   and r69_regist = ".db_sqlformat( $registro );
  $cond .= "   and (   ( r69_dtafast <= ".db_sqlformat( $fim ) ;
  $cond .= "             and r69_dtretorno is null ) ";
  $cond .= "        or ( r69_dtafast >= ".db_sqlformat( $inicio );
  $cond .= "             and r69_dtretorno <= ".db_sqlformat( $fim ).")";
  $cond .= "        or ( r69_dtretorno > ".db_sqlformat( $fim ) ;
  $cond .= "             and r69_dtafast >= ".db_sqlformat ($inicio) ;
  $cond .= "             and r69_dtafast <= ".db_sqlformat(  $fim ) .")";
  $cond .= "        or ( r69_dtafast < ".db_sqlformat($inicio ) ;
  $cond .= "             and r69_dtretorno >= ".db_sqlformat($inicio ).")";
  $cond .= "        or ( r69_dtretorno >  ".db_sqlformat($fim );
  $cond .= "             and r69_dtafast < ".db_sqlformat($inicio )."))";
  $cond .= " left outer join afasta ";
  $cond .= "    on r45_anousu = ".db_sqlformat( db_substr($subpes,1,4) )  ;
  $cond .= "   and r45_mesusu = ".db_sqlformat( db_substr($subpes,6,2) )  ;
  $cond .= "   and r45_regist = ".db_sqlformat( $registro );
  $cond .= "   and (   ( r45_dtafas <= ".db_sqlformat( $fim ) ;
  $cond .= "             and r45_dtreto is null ) ";
  $cond .= "        or ( r45_dtafas >= ".db_sqlformat( $inicio );
  $cond .= "             and r45_dtreto <= ".db_sqlformat( $fim ).")";
  $cond .= "        or ( r45_dtreto > ".db_sqlformat( $fim ) ;
  $cond .= "             and r45_dtafas >= ".db_sqlformat ($inicio) ;
  $cond .= "             and r45_dtafas <= ".db_sqlformat(  $fim ) .")";
  $cond .= "        or ( r45_dtafas < ".db_sqlformat($inicio ) ;
  $cond .= "             and r45_dtreto >= ".db_sqlformat($inicio ).")";
  $cond .= "        or ( r45_dtreto >  ".db_sqlformat($fim );
  $cond .= "             and r45_dtafas < ".db_sqlformat($inicio )."))";
  $cond .= " where rh02_anousu = ".db_sqlformat( db_substr($subpes,1,4) )  ;
  $cond .= "   and rh02_mesusu = ".db_sqlformat( db_substr($subpes,6,2) )  ;
  $cond .= "   and rh02_regist = ".db_sqlformat( $registro );
  $cond .= "   and rh02_instit = ".db_getsession("DB_instit");
  $cond .= "   and ( r45_regist is not null or r69_regist is not null ) ";

  $retornos = false;
  global $afastamentos;
  db_selectmax( "afastamentos" , $cond ) ;
  if( count($afastamentos) > 0){
     $retornos = true ;
  }
  return $retornos;
}

function afas_periodo_aquisitivo ($periodoi, $periodof ){

  global $pessoal, $Ipessoal,$cfpess,$afasta;

  $desconta_dias = 0;
  if( $pessoal[0]["r01_tbprev"] == $cfpess[0]["r11_tbprev"]){
     $desconta_dias = 15;
  }
  $periodo_afastado = 0;
  $condicaoaux = " and r45_regist =". db_sqlformat( $pessoal[0]["r01_regist"] )." order by r45_regist, r45_dtafas desc ";
  if( db_selectmax( "afasta", "select * from afasta ".bb_condicaosubpes( "r45_").$condicaoaux )){
     for($Iafasta=0;$Iafasta< count($afasta);$Iafasta++){
        if( db_at(db_str($afasta[$Iafasta]["r45_situac"],1),"5-2-4-7")>0){
           continue;
        }
        if(  db_at(db_str( $afasta[$Iafasta]["r45_situac"],1 ) , "3-6-8")>0 ){
           if( !db_empty($afasta[$Iafasta]["r45_dtreto"]) && (db_mktime($afasta[$Iafasta]["r45_dtreto"]) < db_mktime($periodoi))){
               continue;
           }
           if( (db_mktime($afasta[$Iafasta]["r45_dtafas"]) - (15*86400)) > db_mktime($periodof)){
              continue;
           }
           if( !db_empty($afasta[$Iafasta]["r45_dtreto"]) && (db_mktime($afasta[$Iafasta]["r45_dtreto"]) > db_mktime($periodoi)) ){
              if( (db_mktime($afasta[$Iafasta]["r45_dtafas"])-db_mktime($desconta_dias)) > db_mktime($periodoi)){
                 if(db_mktime($afasta[$Iafasta]["r45_dtreto"]) > db_mktime($periodof)) {
		    $periodo_afastado += ceil(((db_mktime($periodof) - db_mktime($afasta[$Iafasta]["r45_dtafas"]) - db_mktime($desconta_dias))/86400));
		 }else{
                    $periodo_afastado += ceil(((db_mktime($afasta[$Iafasta]["r45_dtreto"]) - db_mktime($afasta[$Iafasta]["r45_dtafas"]) - db_mktime($desconta_dias))/86400));
		 }
              }else{
                 $periodo_afastado += db_datedif($afasta[$Iafasta]["r45_dtreto"],$periodoi);
              }
              continue;
           }
           if( !db_empty($afasta[$Iafasta]["r45_dtreto"])
              && (db_mktime($afasta[$Iafasta]["r45_dtreto"]) > db_mktime($periodof))
              && (db_mktime($afasta[$Iafasta]["r45_dtafas"])-db_mktime($desconta_dias) ) < db_mktime($periodof) ){
              if( (db_mktime($afasta[$Iafasta]["r45_dtafas"])-db_mktime($desconta_dias)) > db_mktime($periodoi)){
                 $periodo_afastado += ceil(((db_mktime($periodof) - db_mktime($afasta[$Iafasta]["r45_dtafas"]) - db_mktime($desconta_dias))/86400));
              }else{
                 $periodo_afastado += db_datedif($periodof,$periodoi);
              }
           }
           if( db_empty( $afasta[$Iafasta]["r45_dtreto"] ) && (db_mktime($afasta[$Iafasta]["r45_dtafas"]) - db_mktime($desconta_dias) ) < db_mktime($periodof) ) {
              if( (db_mktime($afasta[$Iafasta]["r45_dtafas"])-db_mktime($desconta_dias)) < db_mktime($periodoi)){
                 $periodo_afastado += db_datedif($periodof,$periodoi);
              }else{
                 $periodo_afastado += ceil(((db_mktime($periodof) - db_mktime($afasta[$Iafasta]["r45_dtafas"]) - db_mktime($desconta_dias))/86400));
              }
           }
        }
     }
  }

  return $periodo_afastado;

}

function db_nulldata($string){

  $string = trim($string);
  if(empty($string) || trim($string) == "--"){
    return 'null';
  }else{
    return $string;
  }

}

function db_ascan($the_array,$search_value) {
   if( ($i = array_search($search_value, $the_array)) !== FALSE)  {
       return $i;
   }else{
       return 0;
   }
}

function db_int($value) {
$value = (int)$value;
return $value;
}

/// salario_base ///
function salario_base($pessoal,$Ipessoal,$cfuncao = ""){
  global $subpes,$d08_carnes,$cfpess,$diversos;

  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028, $F030;
 
  global $quais_diversos;
  eval($quais_diversos);

			       
  global $padroes;
  
  $oServidor = ServidorRepository::getInstanciaByCodigo($pessoal[$Ipessoal]["r01_regist"],DBPessoal::getAnoFolha(),DBPessoal::getMesFolha()); 
  $F030      = DBPessoal::getVariaveisCalculo($oServidor)->f030;

  $F007 = 0;
  $F010 = 0;
  $diversominimo = "    ";
  if( !db_empty($pessoal[$Ipessoal]["r01_salari"])){
     $F007 = $pessoal[$Ipessoal]["r01_salari"];
     
     $F010 = $pessoal[$Ipessoal]["r01_salari"];
//echo "<BR> 1 F010 --> $F010";		
 } else {
    $condicaoaux  = " and r02_regime = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regime"] );
    $condicaoaux .= " and r02_codigo = ".db_sqlformat( $pessoal[$Ipessoal]["r01_padrao"]);
    global $padroes;
    $sChavePadrao = 'chavepadrao#'.$pessoal[$Ipessoal]["r01_regime"]."#".$pessoal[$Ipessoal]["r01_padrao"];
    $padroes      = DBRegistry::get($sChavePadrao);
    if (empty($padroes)) {

       $lPadrao = db_selectmax( "padroes", "select * from padroes ".bb_condicaosubpes( "r02_" ).$condicaoaux );
       DBRegistry::add($sChavePadrao, array());
       if ($lPadrao) {
         DBRegistry::add($sChavePadrao, $padroes);
       }
     }
     if (!empty($padroes)) {

	     if (strtolower($padroes[0]["r02_tipo"]) == "h") {
	       $valor_padrao = bb_round($padroes[0]["r02_valor"]*$F008,2);
	     } else {
	       $valor_padrao = $padroes[0]["r02_valor"];
	     }
	     if ( !db_empty($pessoal[$Ipessoal]["r01_hrssem"]) && $padroes[0]["r02_hrssem"] > 0) {

	       $F007 = $valor_padrao/$padroes[0]["r02_hrssem"]*$pessoal[$Ipessoal]["r01_hrssem"] ;
	       $F010 = $valor_padrao/$padroes[0]["r02_hrssem"]*$pessoal[$Ipessoal]["r01_hrssem"] ;

	     } else {
	       $F007 = $valor_padrao;
	       $F010 = $valor_padrao;
  	   }
     	 $diversominimo = $padroes[0]["r02_minimo"];
    } else {

    	$F007 = 0;
    	$F010 = 0;
    }
  }
  if ( strtolower($pessoal[$Ipessoal]["r01_progr"]) == "s"  && db_empty($pessoal[$Ipessoal]["r01_salari"])) {

     $condicaoaux  = " and r24_regime = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regime"] );
     $condicaoaux .= " and r24_padrao = ".db_sqlformat( $pessoal[$Ipessoal]["r01_padrao"] );
     $condicaoaux .= " order by r24_meses ";
     global $progress;
     if( db_selectmax( "progress", "select * from progress ".bb_condicaosubpes( "r24_" ).$condicaoaux )){
	$valor_progress = 0;
	if( $cfuncao == "gerfres" && strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) == "a"){
	    $data_base = $pessoal[$Ipessoal]["r01_recis"];
	}else{
	    $data_base = (strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) == "a" ? $cfpess[0]["r11_dataf"] : $pessoal[$Ipessoal]["r01_admiss"]);
	}
	$data_progr = (db_empty($pessoal[$Ipessoal]["r01_anter"]) ? $pessoal[$Ipessoal]["r01_admiss"] : $pessoal[$Ipessoal]["r01_anter"]);
	if( $cfuncao == "gerfres" && strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) == "a"){
	  $anos = bb_round( ( db_year($data_base) - db_year($data_progr) ) * 12,2);
	}else{
	  $anos = $F024;
	}
	$perc = 0;
	for($Iprogress=0;$Iprogress<count($progress);$Iprogress++){
	  if($progress[$Iprogress]["r24_meses"] > $anos){
	    break;
	  }
	  $perc = $progress[$Iprogress]["r24_perc"];
	  $valor_progress = $progress[$Iprogress]["r24_valor"];
	}
	if( $valor_progress > 0){
	   $F010 = $valor_progress;
//echo "<BR> 2 F010 --> $F010";		
	   if( strtolower($padroes[0]["r02_tipo"]) == "h"){
	      $F010 = $F010 * $F008;
//echo "<BR> 3 F010 --> $F010";		
	   }
	}else{
	   $F010 += bb_round(($F007*bb_round($perc/100,2)),2);
//echo "<BR> 4 F010 --> $F010";		
	}
     }
  }

  if( !db_empty( $diversominimo )){
     $condicaoaux = " and r07_codigo = ".db_sqlformat($diversominimo);
     global $diversos_;
     db_selectmax( "diversos_", "select * from pesdiver ".bb_condicaosubpes( "r07_" ).$condicaoaux );
     $valormin = $diversos_[0]["r07_valor"];
     if( $F010 < $valormin){
	$F010 = $valormin ;
//echo "<BR> 5 F010 --> $F010";		
     }
  }

}

/**
 * retornar todos os funcionários e seus peridos aquisitivos em aberto até a data informada
 *
 * @param string $sDataVencimento data do vencimento da ferias (data no formato 'YYYY-mm-dd')
 * @param string $sWhere cláusula where adicional
 * @return array
 */
function funcionarioferiasvencidas ($sDataVencimento, $sWhere = '') {
  
  $iAno = db_anofolha();
  $iMes = db_mesfolha();
  $sWhere = trim($sWhere);
  if (!empty($sWhere)) {
    $sWhere = " and {$sWhere} "; 
  }
  $sDataParametro    = implode("-", array_reverse(explode("/",$sDataVencimento))); 
  $sSqlFuncionarios  = "select rh02_regist as r01_regist, ";
  $sSqlFuncionarios .= "       z01_nome, ";
  $sSqlFuncionarios .= "       rh02_hrsmen, ";
  $sSqlFuncionarios .= "       rh01_admiss, ";
  $sSqlFuncionarios .= "       rh05_recis, ";
  $sSqlFuncionarios .= "       rh37_descr, ";
  $sSqlFuncionarios .= "       r70_estrut as cod_lota, ";
  $sSqlFuncionarios .= "       r70_descr  as descr_lota ";
  $sSqlFuncionarios .= "  from rhpessoalmov ";
  $sSqlFuncionarios .= "       inner join rhpessoal on rh01_regist = rh02_regist ";
  $sSqlFuncionarios .= "       inner join rhfuncao  on rh02_funcao = rh37_funcao ";
  $sSqlFuncionarios .= "                           and rh02_instit = rh37_instit ";
  $sSqlFuncionarios .= "       inner join rhregime  on rh30_codreg = rh02_codreg "; 
  $sSqlFuncionarios .= "                           and rh30_instit = rh02_instit ";
  $sSqlFuncionarios .= "       left  join rhpesrescisao on rh05_seqpes = rh02_seqpes ";
  $sSqlFuncionarios .= "       inner join cgm       on rh01_numcgm = z01_numcgm  ";
  $sSqlFuncionarios .= "       left  join rhlota    on rh02_lota   = r70_codigo ";
  $sSqlFuncionarios .= "       left  join rhlotaexe on rh26_codigo = rh02_lota ";
  $sSqlFuncionarios .= "                           and rh26_anousu = rh02_anousu ";
  $sSqlFuncionarios .= "       left  join orcorgao  on o40_orgao   = rh26_orgao ";
  $sSqlFuncionarios .= "                           and o40_anousu  = rh26_anousu ";
  $sSqlFuncionarios .= " where rh02_anousu = $iAno  ";
  $sSqlFuncionarios .= "   and rh02_mesusu = $iMes ";
  $sSqlFuncionarios .= "   and rh02_instit = ".db_getsession("DB_instit")." ";
  $sSqlFuncionarios .= "   and rh01_admiss <= ('$iAno-$iMes-'||ndias($iAno,$iMes) )::date ";
  $sSqlFuncionarios .= "   and rh30_vinculo = 'A' ";
  $sSqlFuncionarios .= "   and ( rh05_recis is null or rh05_recis >= to_date('$iAno-$iMes-01', 'YYYY-mm-dd') ) ";
  $sSqlFuncionarios .= "   {$sWhere}";
  $sSqlFuncionarios .= " order by r70_descr, z01_nome ";
  $sSqlFuncionarios .= " ";

  $rsFuncionarios    = db_query($sSqlFuncionarios);
  /**
   * Criamos um array com toads as lotacoes, e seus funcionarios..
   * teremos um array com a seguinte estrutura:
   * Lotacao
   *        |-> Funcionarios
   *                       |-> Dados das Férias abertas
   */
  $aFuncionarios  = array();
  $iTotalFuncionarios = pg_num_rows($rsFuncionarios); 
  for ($i = 0; $i < $iTotalFuncionarios; $i++) {
  
    $oDados = db_utils::fieldsMemory($rsFuncionarios, $i);
    $oFuncionario                       = new stdClass();
    $oFuncionario->matricula            = $oDados->r01_regist;
    $oFuncionario->nome                 = $oDados->z01_nome;
    $oFuncionario->lotacao              = $oDados->descr_lota;
    $oFuncionario->dataadmissao         = db_formatar($oDados->rh01_admiss, "d");
    $oFuncionario->datarescisao         = db_formatar($oDados->rh05_recis, "d");
    $oFuncionario->periodogozadoinicial = '';
    $oFuncionario->periodogozadofinal   = '';
    $oFuncionario->periodoaquisitivoini = '';
    $oFuncionario->periodoaquisitivofim = '';
    $oFuncionario->periodosvencidos     = array();
    
    /**
     * Último período de férias gozados pelo funcionário
     */
    $sSqlUltimoPeriodoGozado  = "SELECT distinct r30_regist,"; 
    $sSqlUltimoPeriodoGozado .= "       r30_perai, ";
    $sSqlUltimoPeriodoGozado .= "       r30_peraf,";
    $sSqlUltimoPeriodoGozado .= "       r30_per1i,";
    $sSqlUltimoPeriodoGozado .= "       r30_per1f,";
    $sSqlUltimoPeriodoGozado .= "       r30_per2i,";
    $sSqlUltimoPeriodoGozado .= "       r30_per2f,";
    $sSqlUltimoPeriodoGozado .= "       r30_dias1,";
    $sSqlUltimoPeriodoGozado .= "       r30_dias2,";
    $sSqlUltimoPeriodoGozado .= "       r30_ndias ";
    $sSqlUltimoPeriodoGozado .= "  from cadferia ";
    $sSqlUltimoPeriodoGozado .= " where r30_anousu = {$iAno}";
    $sSqlUltimoPeriodoGozado .= "   and r30_mesusu = {$iMes} ";
    $sSqlUltimoPeriodoGozado .= "   and r30_regist = {$oDados->r01_regist}";
    $sSqlUltimoPeriodoGozado .= " order by r30_perai desc limit 1";
    $rsULtimoPeriodoGozado    = db_query($sSqlUltimoPeriodoGozado);
    $iTemFerias               = pg_num_rows($rsULtimoPeriodoGozado); 
    if ($iTemFerias > 0) {
      
      $oDadosPeriodoGozado                = db_utils::fieldsMemory($rsULtimoPeriodoGozado, 0);
      $oFuncionario->periodogozadoinicial = db_formatar($oDadosPeriodoGozado->r30_per1i, "d");
      $oFuncionario->periodogozadofinal   = db_formatar($oDadosPeriodoGozado->r30_per1f, "d");
      $oFuncionario->periodoaquisitivoini = $oDadosPeriodoGozado->r30_perai;
      $oFuncionario->periodoaquisitivofim = $oDadosPeriodoGozado->r30_peraf;
      if ($oDadosPeriodoGozado->r30_per2f != "") {
  
         $oFuncionario->periodogozadoinicial = db_formatar($oDadosPeriodoGozado->r30_per2i, "d");
         $oFuncionario->periodogozadofinal   = db_formatar($oDadosPeriodoGozado->r30_per2f, "d");  
      }
    }
    
    
    /**
     * Verificamos quais as ferias que estão em aberto funcionario.
     * caso o usuario não possui ferias com dias em gozo, devemos calcular o primeiro periodo das ferias 
     * acrescentando 1 ano na data de admissao; 
     */
    $sSqlFeriasCadastradas  = "SELECT distinct r30_regist,"; 
    $sSqlFeriasCadastradas .= "       r30_perai, ";
    $sSqlFeriasCadastradas .= "       r30_peraf,";
    $sSqlFeriasCadastradas .= "       r30_per1i,";
    $sSqlFeriasCadastradas .= "       r30_per1f,";
    $sSqlFeriasCadastradas .= "       r30_per2i,";
    $sSqlFeriasCadastradas .= "       r30_per2f,";
    $sSqlFeriasCadastradas .= "       r30_dias1,";
    $sSqlFeriasCadastradas .= "       r30_dias2,";
    $sSqlFeriasCadastradas .= "       r30_ndias, ";
    $sSqlFeriasCadastradas .= "       coalesce(r30_dias1,0)+coalesce(r30_dias2,0) as diasgozados ";
    $sSqlFeriasCadastradas .= "  from cadferia ";
    $sSqlFeriasCadastradas .= " where coalesce(r30_dias1,0)+coalesce(r30_dias2,0) < r30_ndias ";
    $sSqlFeriasCadastradas .= "   and r30_peraf <= '{$sDataParametro}'"; 
    $sSqlFeriasCadastradas .= "   and r30_anousu = {$iAno}";
    $sSqlFeriasCadastradas .= "   and r30_mesusu = {$iMes} ";
    $sSqlFeriasCadastradas .= "   and r30_regist = {$oDados->r01_regist}";
    $sSqlFeriasCadastradas .= " order by r30_perai asc";
    
    $nUltimaData        = '';
    if ($iTemFerias == 0) {
       $sDataInicial       = $oDados->rh01_admiss;
    } else {
      
      /**
       * periodos de ferias ainda nao gozados completamentes. 
       */
      $rsFeriasVencidas     = db_query($sSqlFeriasCadastradas);
      $iTotalFeriasVencidas = pg_num_rows($rsFeriasVencidas); 
      if ($iTotalFeriasVencidas > 0) {
        
        for ($iFerias = 0; $iFerias < $iTotalFeriasVencidas; $iFerias++) {
  
          $oDadosFerias = db_utils::fieldsMemory($rsFeriasVencidas, $iFerias);
          $oPeriodo = new stdClass();
          $oPeriodo->diasgozo    = $oDadosFerias->r30_ndias;
          $oPeriodo->diasgozados = $oDadosFerias->diasgozados;
          $oPeriodo->datainicial = $oDadosFerias->r30_perai; 
          $oPeriodo->datafinal   = $oDadosFerias->r30_peraf; 
          $aDataFinal  = explode("-", $oPeriodo->datafinal);
          $sDataLimite = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1], $aDataFinal[2]-30, $aDataFinal[0]+1)); 
          $oPeriodo->limite      = $sDataLimite; 
          $oFuncionario->periodosvencidos[] = $oPeriodo;
        }
      }
    }
    if ($oFuncionario->periodoaquisitivofim != "") {
  
      $aDataFinal  = explode("-", $oFuncionario->periodoaquisitivofim);
      $sDataLimite = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1], $aDataFinal[2]+1, $aDataFinal[0])); 
      $sDataInicial = $oFuncionario->periodoaquisitivofim;     
    }
    /**
     * Criamos os novos periodos aquisivos...
     */
    $lTemFeriasVencidas = true;
    while ($lTemFeriasVencidas) {
          
      $oPeriodo = new stdClass();
      $oPeriodo->diasgozo    = 30;
      $oPeriodo->diasgozados = '';
      $oPeriodo->datainicial  = $sDataInicial;
      $aDataInicial   = explode("-", $sDataInicial);
      $sUltimaData    = date("Y-m-d", mktime(0, 0, 0, $aDataInicial[1]+12, $aDataInicial[2]-1, $aDataInicial[0]));
      $oPeriodo->datafinal   = $sUltimaData;
      $aDataFinal   = explode("-", $oPeriodo->datafinal);
      $sDataLimite = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1]+12, $aDataFinal[2]-30, $aDataFinal[0]));
      $oPeriodo->limite      = $sDataLimite;
      if (db_strtotime($oPeriodo->datafinal) >= db_strtotime($sDataParametro)) {
        $lTemFeriasVencidas = false;
      } else {
        $oFuncionario->periodosvencidos[] = $oPeriodo;
      }      
      $aDataFinal   = explode("-", $sUltimaData);
      $sDataInicial = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1], $aDataFinal[2]+1, $aDataFinal[0]));;
    }
    $aFuncionarios[] = $oFuncionario;
  }
  return $aFuncionarios;
}

/**
 * Retorna as Competencias da Folha de Pagamento Tendo Base intervalo de duas datas 
 * 
 * @deprecated
 * @see DBPessoal::getCompetenciasIntervalo
 * @param DBDate $oDataInicial 
 * @param DBDate $oDataFinal 
 * @access public
 * @return array
 */
function retornaCompetenciasByPeriodo ( DBDate $oDataInicial, DBDate $oDataFinal ) {
 
  /**
   * Competencia inicial 
   */
  $iMesInicio = (int) $oDataInicial->getMes(); 
  $iAnoInicio = (int) $oDataInicial->getAno(); 

  /**
   * Competencia final 
   */
  $iMesFim = (int) $oDataFinal->getMes(); 
  $iAnoFim = (int) $oDataFinal->getAno(); 

  /**
   * Valida datas, data inicial nao pode ser maior que final 
   */
  if ( $oDataInicial->getTimeStamp() >  $oDataFinal->getTimeStamp() ) {
    throw new Exception('Data inicial não pode ser maior que a final');    
  }
  
  $aRetorno = array();
 
  $iAnoCalculado = $iAnoInicio;
  $iMesCalculado = $iMesInicio;
  
  /**
   * Subrai 1 mes
   * quando:
   * - Mes de inicio é igual mes final
   * - Ano de inicio diferente do ano final
   * - Mes final maior que 1, para nao deixar mes 0
   *
   */
  if ( $iMesInicio == $iMesFim && $iAnoInicio != $iAnoFim && $iMesFim > 1 ) {
    $iMesFim = $iMesFim - 1;
  }

  while ( 1 ) {
    
    $oDadosRetorno = new stdClass();
    $oDadosRetorno->iAnoCompetencia = $iAnoCalculado;
    $oDadosRetorno->iMesCompetencia = $iMesCalculado;

    $aRetorno[] = $oDadosRetorno;

    /**
     * data dos periodos iguais 
     */
    if ( $iAnoInicio == $iAnoFim && $iMesInicio == $iMesFim ) {
      break;
    }

    /**
     * Final do periodo calculado
     * Ano e mes calculado igual o ano e mes da data final 
     */
    if ( $iAnoCalculado == $iAnoFim && $iMesCalculado ==  $iMesFim ) {
      break;
    }
 
    if ( $iMesCalculado == 12 ) {

      $iMesCalculado = 1;
      $iAnoCalculado++;
      continue;
    }
 
    $iMesCalculado++;
    continue;
  }
  
  return $aRetorno;
}

