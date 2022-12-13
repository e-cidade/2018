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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("dbforms/db_funcoes.php"));

$oParametros = db_utils::postMemory($_GET);
db_inicio_transacao();

$oCompetencia       = new DBCompetencia($oParametros->xano, $oParametros->xmes);
$iTabelaPrevidencia = $prev;

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <br><br><br>
</body>
<?


try {

  $aCamposConfig   = array();
  $aCamposConfig[] = "numero";
  $aCamposConfig[] = "ender";
  $aCamposConfig[] = "cgc";
  $aCamposConfig[] = "nomeinst";
  $aCamposConfig[] = "bairro";
  $aCamposConfig[] = "cep";
  $aCamposConfig[] = "munic";
  $aCamposConfig[] = "uf";
  $aCamposConfig[] = "telef";
  $aCamposConfig[] = "email";
  $aCamposConfig[] = "lower(trim(munic)) as d08_carnes";
  $aCamposConfig[] = "cgc";
  $sCamposConfig = implode(",", $aCamposConfig);

  $sWhereConfig = "codigo = ". db_getsession("DB_instit");

  $sSqlConfig = "SELECT {$sCamposConfig} FROM db_config WHERE {$sWhereConfig}";
  $rsConfig   = db_query($sSqlConfig);

  if(!$rsConfig) {
    throw new Exception("Ocorreu um erro ao consultar os dados da instituição.");
  }

  $oDadosConfig = db_utils::makeFromRecord($rsConfig, function($oConfig){
    return $oConfig;
  }, 0);

  $d08_ender  = $oDadosConfig->ender;
  $d08_cgc    = $oDadosConfig->cgc;
  $d08_nome   = $oDadosConfig->nomeinst;
  $d08_bairro = $oDadosConfig->bairro;
  $d08_cep    = $oDadosConfig->cep; // Esse está sendo usado
  $d08_munic  = $oDadosConfig->munic;
  $d08_uf     = $oDadosConfig->uf; // Esse está sendo usado
  $d08_telef  = $oDadosConfig->telef;
  $d08_email  = $oDadosConfig->email;
  $d08_numero = $oDadosConfig->numero;

  if(trim($oDadosConfig->cgc) == "90940172000138"){
    $d08_carnes = "daeb";
  }else{
    $d08_carnes = $oDadosConfig->d08_carnes;
  }

  $oParametros->d08_cep = $d08_cep;
  $oParametros->d08_uf  = $d08_uf;

  $nomearq = "/tmp/layout_ideal.txt";
  $nomepdf = "/tmp/layout_ideal.pdf";

  emite_layoutideal($nomearq, $nomepdf, $oCompetencia, $iTabelaPrevidencia, $oParametros);
  echo "<script>parent.js_detectaarquivo('$nomearq','$nomepdf');</script>";
  db_redireciona("pes2_layoutideal001.php");
} catch (Exception $e) {
  db_msgbox($e->getMessage());
}

db_fim_transacao();

function queryServidores($nomearq, $iTabelaPrevidencia, $oCompetencia, $oParametros) {

  $sf         = $oParametros->sf;
  $lg         = $oParametros->lg;
  $ls         = $oParametros->ls;
  $vinculo    = $oParametros->vinculo;
  $d08_cep    = $oParametros->d08_cep;
  $d08_uf     = $oParametros->d08_uf;
  $xano       = $oCompetencia->getAno();
  $xmes       = $oCompetencia->getMes();
  $sTipoFolha = $oParametros->sal_dec;

  if( $sTipoFolha  == "S" ){

    $arquivo  = "gerfsal" ;
    $sigla    = "r14_" ;
    $arquivoc = "gerfcom" ;
    $siglac   = "r48_" ;
    $arquivor = "gerfres" ;
    $siglar   = "r20_" ;
    $rub_base = "('R985','R986')";

    if($iTabelaPrevidencia == 1){

      $rub_desc        = "('R901', 'R902')";
      $rub_desc_ferias = "('R903')";

    }elseif($iTabelaPrevidencia == 2){

      $rub_desc        = "('R904', 'R905')";
      $rub_desc_ferias = "('R906')";

    }elseif($iTabelaPrevidencia == 3){

      $rub_desc        = "('R907', 'R908')";
      $rub_desc_ferias = "('R909')";

    }elseif($iTabelaPrevidencia == 4){

      $rub_desc        = "('R910', 'R911')";
      $rub_desc_ferias = "('R912')";

    }
    $rub_base_ferias = "R987";

  }else{

    $arquivo  = "gerfs13" ;
    $sigla    = "r35_" ;
    $arquivor = "gerfres" ;
    $siglar   = "r20_" ;
    $rub_base = "('R985','R986')" ;

    if($iTabelaPrevidencia == 1){
      $rub_desc        = "('R901', 'R902')";
    }elseif($iTabelaPrevidencia == 2){
      $rub_desc        = "('R904', 'R905')";
    }elseif($iTabelaPrevidencia == 3){
      $rub_desc        = "('R907', 'R908')";
    }elseif($iTabelaPrevidencia == 4){
      $rub_desc        = "('R910', 'R911')";
    }
  }

  // previdencia 3 + 2 da posicao dentro do arquivo = 5;
  $sql_prev  = "select * from inssirf 
                where r33_anousu = ".db_sqlformat( $xano )."
                  and r33_mesusu = ".db_sqlformat( $xmes )."
                  and r33_codtab = {$iTabelaPrevidencia} + 2 
                limit 1";
  //echo '   VINCULO --> '.$vinculo;exit;
  global $r33_ppatro, $r33_rubsau, $r33_rubmat;
  $res_prev = db_query($sql_prev);

  if (!$res_prev) {
    throw new Exception("Erro ao buscar dados da tabela inssirf.");
  }

  if ( pg_num_rows($res_prev) == 0) {
    throw new Exception("Nenhum dado encontrado na tabela inssirf.");
  }

  db_fieldsmemory($res_prev,0);
  $perc_patronal = $r33_ppatro;
  $rubrica_saude = "('')";
  if(trim($r33_rubsau) != '' && $ls == 's' ){
   $rubrica_saude = "('$r33_rubsau')";
  }

  $rubrica_gestante = "('')";
  if(trim($r33_rubmat) != '' && $lg == 's' ){
   $rubrica_gestante = "('$r33_rubmat')";
  }

  $rubrica_familia = "('')";
  if($sf == 's' ){
    $rubrica_familia = "('R918')";
  }

  //echo "<br><br> rubrica_familia --> $rubrica_familia   rubrica_saude --> $rubrica_saude   rubrica_gestante --> $rubrica_gestante"; exit;;
  ///$rubrica_familia, $rubrica_gestante,$perc_patronal,
  if($vinculo == 'i'){
    $where_vin = " and rh30_vinculo = 'I'";
  }elseif($vinculo == 'p'){
    $where_vin = " and rh30_vinculo = 'P'";
  }elseif($vinculo == 'ip'){
    $where_vin = " and rh30_vinculo <> 'A'";
  }else{
    $where_vin = '';
  }


  $varp = '0';

  //echo "  sTipoFolha ---> $sTipoFolha   perc_patronal --> $perc_patronal   rubrica_gestante --> $rubrica_gestante   rubrica_gestante --> $rubrica_gestante <br><br>";exit;

  $sql  = "select * from (
           select ".$varp."||lpad(rh01_regist,9,'0') as regist,
                   rh01_regist as r01_regist,
                   z01_numcgm as numcgm,
                   substr(z01_nome,1,40) as nome,
                   to_char(rh01_admiss,'ddmmyyyy') as admissao,
                   to_char(rh05_recis,'ddmmyyyy') as rescisao,
                   r70_estrut as lotacao,
                   case rh30_vinculo 
                        when 'A' then '01'
                        when 'I' then '02'
                        when 'P' then '03'
                        else 'ER' 
                   end as situacao,
                   substr(z01_cgccpf,1,11) as cpf,
                   rpad(trim(substr(z01_ender,1,36)),36,' ') as z01_ender,
                   rpad(trim( substr(z01_bairro,1,14)),14,' ') as z01_bairro,
                   rpad(trim(substr(z01_munic,1,20)),20,' ') as z01_munic,
                   case when trim(z01_uf) = '' or z01_uf is null then '$d08_uf' else z01_uf end as z01_uf,
                   to_char(rh01_admiss,'ddmmyyyy') as apos,
                   rh01_admiss as r01_admiss, 
                   z01_sexo as sexo,
                   z01_ident   as identidade,
                   rh16_titele as titulo_eleitor,
                   rh16_zonael as zona_titulo,
                   rh16_secaoe as secao_titulo,
                   'RS'        as uf_zona_titulo,
                   to_char(rh01_admiss,'ddmmyyyy') as data_inicio_plano_carreira,
                   to_char(rh01_admiss,'ddmmyyyy') as data_entrada_cargo_atual,                   
                   rh02_funcao as codigo_cargo,
                   rh02_hrssem as regime_horario,
                   '' as data_entrada_cargo_anterior,
                   '' as data_encerramento_pensao,                   
                   to_char(rh01_admiss,'ddmmyyyy') as data_nomeacao_funcionario,
                   rh44_conta||'-'||rh44_dvconta as conta_bancaria,
                   rh44_codban as codigo_banco, 
                   rh44_agencia||'-'||rh44_dvagencia as codigo_agencia_bancaria,
                   '' as numero_certidao_nascimento,
                   z01_telef  as numero_telefone_comercial,
                   z01_telef  as numero_telefone_residencial,
                   z01_telcel as numero_telefone_celular,
                   z01_fax    as numero_fax,
                   z01_email  as email,
                   (select rh31_nome 
                       from rhdepend
                      where rh31_gparen = 'C'
                        and rh31_regist = rh01_regist 
                      limit 1
                   ) as nome_conjuge,
                   (case z01_nacion when 1 then 'BRASILEIRA' ELSE 'ESTRANGEIRA' end) as nacionalidade, 
                   z01_naturalidade as naturalidade, 
                   z01_escolaridade as grau_instrucao,
                   '' as padrao,
                   z01_identorgao as orgao_expedidor_identidade,
                   '' as uf_expedidor_identidade,
                   to_char(z01_identdtexp,'ddmmyyyy') as data_expedicao_identidade,
                   rh02_deficientefisico as deficiente_fisico,
                   z01_compl as complemento_imovel,
                   z01_numero as numero_imovel,
                   '000' as tipo_logradouro,
                   '0' as cod_apos,
                   rh16_pis as pis_pasep,
                   z01_pai as nome_pai,
                   z01_mae as nome_mae,
                   rh01_sexo as sexo,
                   rh01_estciv as estado_civil,
                   coalesce(to_char(z01_dtfalecimento,'ddmmyyyy'),'00000000') as data_falescimento,
                   ";

  if( $sTipoFolha == "S" ){
     // salario + complementar;
     $sql .= " coalesce(lpad(translate(ltrim(to_char((round(coalesce(prev.".$sigla."valor,0)+coalesce(prevc.".$siglac."valor,0)+coalesce(prevrf.".$siglar."valor,0)+coalesce(prevrs.".$siglar."valor,0),2)),'99999999.99')),',.',''),14,0),'00000000000000') as base_prev,
               coalesce(lpad(translate(ltrim(to_char((round(coalesce(descon.".$sigla."valor,0),2)+round(coalesce(desconc.".$siglac."valor,0),2)+round(coalesce(desconrf.".$siglar."valor,0),2)+round(coalesce(desconrs.".$siglar."valor,0),2)),'99999999.99')),',.',''),14,0),'00000000000000') as desc_prev,
               coalesce(lpad(translate(ltrim(to_char( round(coalesce(familia.".$sigla."valor,0),2),'99999999.99')),',.',''),8,0),'00000000') as salfamilia,
               coalesce(lpad(translate(ltrim(to_char( round(coalesce(gestante.".$sigla."valor,0),2),'99999999.99')),',.',''),8,0),'00000000') as salgestante,
               coalesce(lpad(translate(ltrim(to_char( round(coalesce(saude.".$sigla."valor,0),2),'99999999.99')),',.',''),8,0),'00000000') as salsaude," ;

  }else{
     // 13.salario;
     $sql .= " coalesce(lpad(translate(ltrim(to_char(round(coalesce(prev.".$sigla."valor,0),2)+round(coalesce(prevr.".$siglar."valor,0),2),'99999999.99')),',.',''),14,0),'00000000000000') as base_prev,
               coalesce(lpad(translate(ltrim(to_char(round(coalesce(descon.".$sigla."valor,0),2)+round(coalesce(desconr.".$siglar."valor,0),2),'99999999.99')),',.',''),14,0),'00000000000000') as desc_prev," ;
  }

  if( $sTipoFolha == "S" ){
     // acrescenta a complementar;
     $sql .= " coalesce(lpad(translate(ltrim(to_char(round(desconc.".$siglac."quant,2),'99999999.99')),',.',''),5,0),'00000') as quant_desc_prevc," ;
     // acrescenta a rescisao;
     $sql .= " coalesce(lpad(translate(ltrim(to_char(round(desconrs.".$siglar."quant,2),'99999999.99')),',.',''),5,0),'00000')  as quant_desc_prevr,";
  }else{
     // acrescenta a rescisao;
     $sql .= " coalesce(lpad(translate(ltrim(to_char(round(desconr.".$siglar."quant,2),'99999999.99')),',.',''),5,0),'00000')  as quant_desc_prevr,";
  }

  $sql .= " coalesce(lpad(translate(ltrim(to_char(round(descon.".$sigla."quant,2),'99999999.99')),',.',''),5,0),'00000') as quant_desc_prev, ";

  if( $sTipoFolha == "S" ){
     // salario + complementar;
     $sql .= " coalesce(lpad(translate(ltrim(to_char(round(round(coalesce(prev.".$sigla."valor,0)+coalesce(prevc.".$siglac."valor,0)+coalesce(prevrf.".$siglar."valor,0)+coalesce(prevrs.".$siglar."valor,0),2)/100*".db_str($perc_patronal,2).",2),'999999.99')),',.',''),14,0),'00000000000000') as cont_ent," ;
  }else{
     // 13.salario;
     $sql .= " coalesce(lpad(translate(ltrim(to_char(round( (coalesce(prev.".$sigla."valor,0) + coalesce(prevr.".$siglar."valor,0))  /100*".db_str($perc_patronal,2).",2),'999999.99')),',.',''),14,0),'00000000000000') as cont_ent," ;
  }

  $sql .= "        lpad(case when trim(z01_cep) = '' or z01_cep is null or to_number(z01_cep,'99999999') = 0 then '$d08_cep' else z01_cep end,8,0) as z01_cep  ," ;
  $sql .= "        to_char(rh01_nasc,'ddmmyyyy') as nascimento, " ;
  $sql .= "        {$perc_patronal} as perc_patronal, " ;

  if( $sTipoFolha == "S" ){
     $sql .= "        coalesce(lpad(translate(ltrim(to_char(round(   
                              (select coalesce( sum(".$sigla."valor ),0)   
                                   from ".$arquivo."
                                  where ".$sigla."anousu=".db_str($xano,4)." and ".
                                          $sigla."mesusu=".db_str($xmes,2)." and ".
                                          $sigla."instit=".db_getsession("DB_instit")." and ".
                                          $sigla."pd = 1 and ".
                                          $sigla."regist = rh01_regist   
                              )
                              +  
                              (select coalesce( sum(".$siglac."valor),0 )    
                                   from ".$arquivoc."
                                  where ".$siglac."anousu=".db_str($xano,4)." and ".
                                          $siglac."mesusu=".db_str($xmes,2)." and ".
                                          $siglac."instit=".db_getsession("DB_instit")." and ".
                                          $siglac."pd = 1 and ".
                                          $siglac."regist = rh01_regist  
                              ) 
                        ,2),'99999999.99')),',.',''),14,0),'00000000000000') as proventos";

  }else{

     $sql .= "        coalesce(lpad(translate(ltrim(to_char(round(   
                             (select coalesce( sum(".$sigla."valor ),0)    
                                  from ".$arquivo."
                                 where ".$sigla."anousu=".db_str($xano,4)." and ".
                                         $sigla."mesusu=".db_str($xmes,2)." and ".
                                         $sigla."instit=".db_getsession("DB_instit")." and ".
                                         $sigla."pd = 1 and ".
                                         $sigla."regist = rh01_regist 
                             ) 
                      +
                             (select coalesce( sum(".$siglar."valor),0 ) 
                                  from ".$arquivor."
                                 where ".$siglar."rubric between '4000' and '5999' and ".
                                         $siglar."anousu=".db_str($xano,4)." and ".
                                         $siglar."mesusu=".db_str($xmes,2)." and ".
                                         $siglar."instit=".db_getsession("DB_instit")." and ".
                                         $siglar."pd = 1 and ".
                                         $siglar."regist = rh01_regist   
                             )    
                       ,2),'99999999.99')),',.',''),14,0),'00000000000000') as proventos";

  }

  $sql .= " from   rhpessoal
                   inner join cgm          on rh01_numcgm = z01_numcgm    
                   inner join rhpessoalmov on rh02_anousu = $xano
                                          and rh02_mesusu = $xmes
                                          and rh02_regist = rh01_regist
                                          and rh02_instit = ".db_getsession("DB_instit")."
                   inner join rhlota       on r70_codigo  = rh02_lota
                                          and r70_instit  = rh02_instit
                   left join rhpesrescisao on rh05_seqpes = rh02_seqpes
                   left join rhpesdoc      on rh16_regist = rh01_regist
                   left join rhpesbanco    on rh44_seqpes = rh02_seqpes
                   left join rhregime   on rh30_codreg    = rhpessoalmov.rh02_codreg
                                       and rh30_instit    = ".db_getsession("DB_instit")."
                                       $where_vin
                   left outer join (select ".$sigla."regist,   
                                           sum(".$sigla."valor) as ".$sigla."valor    
                                    from ".$arquivo."  
                                    where ".$sigla."rubric in ".$rub_base." and 
                                          ".$sigla."anousu=".db_str($xano,4)." and 
                                          ".$sigla."mesusu=".db_str($xmes,2)." and 
                                          ".$sigla."instit=".$sigla."instit
            group by ".$sigla."regist) 
                                    as prev
                                    on rh01_regist = prev.".$sigla."regist 
                   left outer join (select ".$sigla."regist,
                                           sum(".$sigla."valor) as ".$sigla."valor,
                                           sum(".$sigla."quant) as ".$sigla."quant 
                                    from ".$arquivo ."
                                    where ".$sigla."rubric in ".$rub_desc." and 
                                          ".$sigla."anousu=".db_str($xano,4)." and 
                                          ".$sigla."mesusu=".db_str($xmes,2)." and 
                                          ".$sigla."instit=".$sigla."instit
            group by ".$sigla."regist) 
                                    as descon 
                                    on rh01_regist = descon.".$sigla."regist" ;

  if( $sTipoFolha == "S" ){
     $sql .= "        left outer join (select ".$siglac."regist,
                                          sum(".$siglac."valor) as ".$siglac."valor    
                                        from ".$arquivoc."
                                        where ".$siglac."rubric in ".$rub_base." and ".
                                               $siglac."anousu=".db_str($xano,4)." and ".
                                               $siglac."mesusu=".db_str($xmes,2)." and ".
                                               $siglac."instit=".db_getsession("DB_instit")."
                group by ".$siglac."regist) 
                                        as prevc 
                                        on rh01_regist=prevc.".$siglac."regist  
                       left outer join (select ".$siglac."regist,
                                   sum(".$siglac."valor) as ".$siglac."valor,
                                               sum(".$siglac."quant) as ".$siglac."quant
                                        from ".$arquivoc."
                                        where ".$siglac."rubric in ".$rub_desc." and ".
                                                $siglac."anousu=".db_str($xano,4)." and ".
                                                $siglac."mesusu=".db_str($xmes,2)." and ".
                                                $siglac."instit=".db_getsession("DB_instit")."
                group by ".$siglac."regist)   
                                        as desconc    
                                        on rh01_regist=desconc.".$siglac."regist    
                       left outer join (select ".$siglar."regist, 
                                               sum(".$siglar."valor) as ".$siglar."valor    
                                        from ".$arquivor."
                                        where ".$siglar."rubric = '".$rub_base_ferias."' and ".
                                               $siglar."anousu=".db_str($xano,4)." and ".
                                               $siglar."mesusu=".db_str($xmes,2)." and ".
                                               $siglar."instit=".db_getsession("DB_instit")."
                group by ".$siglar."regist) 
                                        as prevrf 
                                        on rh01_regist=prevrf.".$siglar."regist  
                       left outer join (select ".$siglar."regist, 
                                               sum(".$siglar."valor) as ".$siglar."valor , 
                                               sum(".$siglar."quant) as ".$siglar."quant
                                        from ".$arquivor."
                                        where ".$siglar."rubric in ".$rub_desc_ferias." and ".
                                                $siglar."anousu=".db_str($xano,4)." and ".
                                                $siglar."mesusu=".db_str($xmes,2)." and ".
                                                $siglar."instit=".db_getsession("DB_instit")."
                group by ".$siglar."regist) 
                                        as desconrf 
                                        on rh01_regist=desconrf.".$siglar."regist   
                       left outer join (select ".$siglar."regist,
                                               sum(".$siglar."valor) as ".$siglar."valor    
                                        from ".$arquivor."
                                        where ".$siglar."rubric in ".$rub_base." and ".
                                               $siglar."anousu=".db_str($xano,4)." and ".
                                               $siglar."mesusu=".db_str($xmes,2)." and ".
                                               $siglar."instit=".db_getsession("DB_instit")."
                group by ".$siglar."regist)   
                                        as prevrs    
                                        on rh01_regist=prevrs.".$siglar."regist     
                       left outer join (select ".$siglar."regist, 
                                               sum(".$siglar."valor) as ".$siglar."valor, 
                                               sum(".$siglar."quant) as ".$siglar."quant     
                                        from ".$arquivor."
                                        where ".$siglar."rubric in ".$rub_desc." and ".
                                                $siglar."anousu=".db_str($xano,4)." and ".
                                                $siglar."mesusu=".db_str($xmes,2)." and ".
                                                $siglar."instit=".db_getsession("DB_instit")."
                group by ".$siglar."regist)   
                                        as desconrs    
                                        on rh01_regist=desconrs.".$siglar."regist    
                       left outer join (select ".$sigla."regist, 
                                               sum(".$sigla."valor) as ".$sigla."valor, 
                                               sum(".$sigla."quant) as ".$sigla."quant     
                                        from ".$arquivo."
                                        where ".$sigla."rubric in ".$rubrica_familia." and ".
                                                $sigla."anousu=".db_str($xano,4)." and ".
                                                $sigla."mesusu=".db_str($xmes,2)." and ".
                                                $sigla."instit=".db_getsession("DB_instit")."
                group by ".$sigla."regist) 
                                        as familia    
                                        on rh01_regist=familia.".$sigla."regist    
                       left outer join (select ".$sigla."regist, 
                                               sum(".$sigla."valor) as ".$sigla."valor, 
                                               sum(".$sigla."quant) as ".$sigla."quant    
                                        from ".$arquivo."
                                        where ".$sigla."rubric in ".$rubrica_gestante." and ".
                                                $sigla."anousu=".db_str($xano,4)." and ".
                                                $sigla."mesusu=".db_str($xmes,2)." and ".
                                                $sigla."instit=".db_getsession("DB_instit")."
                group by ".$sigla."regist)   
                                        as gestante    
                                        on rh01_regist=gestante.".$sigla."regist    
                       left outer join (select ".$sigla."regist, 
                                               sum(".$sigla."valor) as ".$sigla."valor, 
                                               sum(".$sigla."quant) as ".$sigla."quant     
                                        from ".$arquivo."
                                        where ".$sigla."rubric in ".$rubrica_saude." and ".
                                                $sigla."anousu=".db_str($xano,4)." and ".
                                                $sigla."mesusu=".db_str($xmes,2)." and ".
                                                $sigla."instit=".db_getsession("DB_instit")."
                group by ".$sigla."regist)   
                                        as saude    
                                        on rh01_regist=saude.".$sigla."regist " ;

  }else{
     $sql .= "        left outer join (select ".$siglar."regist, 
                                              sum(".$siglar."valor) as ".$siglar."valor    
                                       from ".$arquivor."
                                       where ".$siglar."rubric in ".$rub_base." and ".
                                              $siglar."anousu=".db_str($xano,4)." and ".
                                              $siglar."mesusu=".db_str($xmes,2)." and ".
                                              $siglar."instit=".db_getsession("DB_instit")."
               group by ".$siglar."regist)   
                                       as prevr    
                                       on rh01_regist=prevr.".$siglar."regist     
                      left outer join (select ".$siglar."regist, 
                                              sum(".$siglar."valor) as ".$siglar."valor, 
                                              sum(".$siglar."quant) as ".$siglar."quant     
                                       from ".$arquivor."
                                       where ".$siglar."rubric in ".$rub_desc." and ".
                                               $siglar."anousu=".db_str($xano,4)." and ".
                                               $siglar."mesusu=".db_str($xmes,2)." and ".
                                               $siglar."instit=".db_getsession("DB_instit")."
               group by ".$siglar."regist)    
                                       as desconr    
                                       on rh01_regist=desconr.".$siglar."regist " ;


  }

  $sql .= " where rh02_tbprev = {$iTabelaPrevidencia}
            order by rh01_regist 
            ) as x
      where
            coalesce(base_prev,'0')::float8+ 
            coalesce(desc_prev,'0')::float8+ 
            coalesce(salfamilia,'0')::float8+ 
            coalesce(salgestante,'0')::float8+ 
            coalesce(salsaude,'0')::float8+ 
            coalesce(quant_desc_prevc,'0')::float8+ 
            coalesce(quant_desc_prevr,'0')::float8+ 
            coalesce(quant_desc_prev,'0')::float8+ 
            coalesce(cont_ent,'0')::float8+ 
            coalesce(proventos,'0')::float8 > 0 ";

  $result_princ = db_query($sql);

  if(!$result_princ){
    throw new Exception("Ocorreu um erro ao buscar os dados para geração do arquivo.\nContate o suporte.");
  }

  if(pg_num_rows($result_princ) == 0){
    throw new Exception("Não existem dados para o filtro selecionado.");
  }

  return $result_princ;
}


function emite_layoutideal($nomearq, $nomepdf, DBCompetencia $oCompetencia, $iTabelaPrevidencia, $oParametros, $sSeparador = ';') {

  $ano        = db_str($oCompetencia->getAno(),4);
  $mes        = db_str($oCompetencia->getMes(),2,0,"0");
  $sTipoFolha = $oParametros->sal_dec;

  $rsDadosServidores = queryServidores($nomearq, $iTabelaPrevidencia, $oCompetencia, $oParametros);

  $pdf = new PDF('L');
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca = 1;
  $alt   = 4;

  global $head2;
  $head2 = "LAYOUT IDEAL SISTEMAS - CONFERENCIA";


  $arquivo = fopen($nomearq,"w");

  // 1 fixo tipo de reg. ;
  $sLinha  = "1";

  // ano ;
  $sLinha .= db_str($oCompetencia->getMes(),2,0,"0").db_str($oCompetencia->getAno(),4);

  if( $sTipoFolha == "S" ){
     $sLinha .= "MENSAL            " ;
  }else{
     $sLinha .= "13 SALARIO        " ;
  }

  $sLinha .= PHP_EOL;
  fputs($arquivo,$sLinha);
  $sLinha = '';
  $total_base     = 0;
  $total_patronal = 0;
  $total_desc     = 0;

  $aDadosServidores = array();
  $aDadosServidores = db_utils::makeCollectionFromRecord($rsDadosServidores, function($oItemServidor){
    return $oItemServidor;
  });

  foreach ($aDadosServidores as $oDadosServidor) {

    $oServidor = ServidorRepository::getInstanciaByCodigo($oDadosServidor->r01_regist);
    $aLinhas   = array();
    $sLinha    = '';

    $nTotalValoresServidor  = 0;
    $nTotalValoresServidor += $oDadosServidor->base_prev;
    $nTotalValoresServidor += $oDadosServidor->desc_prev;
    $nTotalValoresServidor += $oDadosServidor->salfamilia;
    $nTotalValoresServidor += $oDadosServidor->salgestante;
    $nTotalValoresServidor += $oDadosServidor->salsaude;
    $nTotalValoresServidor += $oDadosServidor->quant_desc_prevc;
    $nTotalValoresServidor += $oDadosServidor->quant_desc_prevr;
    $nTotalValoresServidor += $oDadosServidor->quant_desc_prev;

    if($nTotalValoresServidor == 0) {
      continue;
    }

    // tipo de reg. fixo 2 ;
    $aLinhas[] = "2";

    // Matricula do Servidor ;
    $aLinhas[] = str_pad($oDadosServidor->regist, 10, "0", STR_PAD_LEFT);

    // nome ;
    $aLinhas[] = db_formatar($oDadosServidor->nome,'s',' ',40,'d');

    // data de admissao  ;
    $aLinhas[] = $oDadosServidor->admissao;

    // localizacao ;
    $aLinhas[] = db_formatar($oDadosServidor->lotacao,'s',' ',20,'d');

    // situacao ;
    $aLinhas[] = str_pad($oDadosServidor->situacao, 2, "0", STR_PAD_LEFT);

    // cpf ;
    $aLinhas[] = db_formatar($oDadosServidor->cpf,'s','0',11,'e');

    // endereco ;
    $aLinhas[] = db_formatar($oDadosServidor->z01_ender,'s',' ',36,'d');

    // bairro ;
    $aLinhas[] = db_formatar($oDadosServidor->z01_bairro,'s',' ',14,'d');

    // cidade ;
    $aLinhas[] = db_formatar($oDadosServidor->z01_munic,'s',' ',20,'d');

    // estado ;
    $aLinhas[] = db_formatar($oDadosServidor->z01_uf,'s',' ',2,'d');;

    // inicio aposentadoria ;
    if( $oDadosServidor->situacao == '02' ){
      $aLinhas[] = str_pad($oDadosServidor->apos, 8, " ", STR_PAD_LEFT);
    }else{
      $aLinhas[] = "00000000" ;
    }

    // codigo aposentadoria ;
    $aLinhas[] = $oDadosServidor->cod_apos;

    // inicio pensao ;
    if( $oDadosServidor->situacao == '03' ){
      $aLinhas[] = str_pad($oDadosServidor->apos, 8, " ", STR_PAD_LEFT);
    }else{
      $aLinhas[] = "00000000";
    }

    // data inicio contr. fundo ;
    $aLinhas[] = $oDadosServidor->admissao;

    // data demissao ;
    $aLinhas[] = db_formatar($oDadosServidor->rescisao,'s','0',8,'e');

    $bases = $oDadosServidor->base_prev + $oDadosServidor->proventos;

    // inativos e pensionistas
    if( db_at($oDadosServidor->situacao,"02-03") > 0  || $bases <= 0){
      $aLinhas[] = "00000"; // patronal;
    }else{
      $aLinhas[] = db_str( int($oDadosServidor->perc_patronal*100),5,0,"0"); // perc. contribuicao da entidade ;
    }

    // utilizar o percentual do salario e caso vazio o da complementar;
    // perc. contribuicao do func. ;
    if( $sTipoFolha == "S" ){
      if(db_val($oDadosServidor->quant_desc_prev) > 0){
        $aLinhas[] = str_pad($oDadosServidor->quant_desc_prev, 5, "0", STR_PAD_RIGHT);
      }else if(db_val($oDadosServidor->quant_desc_prevc) > 0){
        $aLinhas[] = str_pad($oDadosServidor->quant_desc_prevc, 5, "0", STR_PAD_RIGHT);
      }else{
        $aLinhas[] = str_pad($oDadosServidor->quant_desc_prevr, 5, "0", STR_PAD_RIGHT);
      }
    }else{ // 13.salario;
      if(db_val( $oDadosServidor->quant_desc_prev ) > 0){
        $aLinhas[] = str_pad($oDadosServidor->quant_desc_prev, 5, "0", STR_PAD_RIGHT);
      }else{
        $aLinhas[] = str_pad($oDadosServidor->quant_desc_prevr, 5, "0", STR_PAD_RIGHT);
      }
    }

    // base contribuicao ;
    if( $oDadosServidor->situacao == '01' ){

      $aLinhas[] = str_pad($oDadosServidor->base_prev, 14, "0", STR_PAD_RIGHT);
      $total_base += round(db_val($oDadosServidor->base_prev)/100, 2);

    }else{ // para inativos e pensionistas - soma proventos;

      $aLinhas[] = str_pad($proventos, 14, "0", STR_PAD_RIGHT);
      $total_base += round(db_val($oDadosServidor->proventos)/100,2);
    }

    // fixo zeros - valor liquido ;
    $aLinhas[] = "00000000000000";

    // desc. previdencia ;
    $aLinhas[] = str_pad($oDadosServidor->desc_prev, 14, "0", STR_PAD_RIGHT);
    $total_desc += round(db_val($oDadosServidor->desc_prev)/100,2);

    // contribuicao da entidade ;
    if( $oDadosServidor->situacao == '01' ){
      $aLinhas[]       = str_pad($oDadosServidor->cont_ent, 14, "0", STR_PAD_RIGHT);
      $total_patronal += round(db_val($oDadosServidor->cont_ent)/100,2);
    }else{
      $aLinhas[] = "00000000000000"; // nao lancar contribuicao da entidade para inativos / pensionistas;
    }

    if( $sTipoFolha == "S" ){

      // fixo ;
      $aLinhas[] = "20";

      // fixo - salario familia R918;
      $aLinhas[] =  str_pad($oDadosServidor->salfamilia, 8, "0", STR_PAD_RIGHT);

      // fixo ;
      $aLinhas[] = "21";

      // fixo - licenca gestante - ver tabela inssirf rubrica relacionada;
      $aLinhas[] = str_pad($oDadosServidor->salgestante, 8, "0", STR_PAD_RIGHT);

      // fixo ;
      $aLinhas[] = "22";

      // fixo - licenca saude - ver tabela inssirf;
      $aLinhas[] = str_pad($oDadosServidor->salsaude, 8, "0", STR_PAD_RIGHT);

    }else{

      // fixo ;
      $aLinhas[] = "20";

      // fixo  - salario familia R918;
      $aLinhas[] = "00000000";

      // fixo ;
      $aLinhas[] = "21";

      // fixo - licenca gestante - ver tabela inssirf rubrica relacionada;
      $aLinhas[] = "00000000";

      // fixo  ;
      $aLinhas[] = "22";

      // fixo - licenca saude - ver tabela inssirf;
      $aLinhas[] = "00000000";
    }


    // fixo
    $aLinhas[] = "23";
    // fixo
    $aLinhas[] = "00000000";
    // fixo
    $aLinhas[] = "24";
    // fixo
    $aLinhas[] = "00000000";
    // fixo
    $aLinhas[] = "25";
    // fixo
    $aLinhas[] = "00000000";
    // fixo
    $aLinhas[] = "26";
    // fixo
    $aLinhas[] = "00000000";
    // fixo
    $aLinhas[] = "27";
    // fixo
    $aLinhas[] = "00000000";
    // fixo
    $aLinhas[] = "28";
    // fixo
    $aLinhas[] = "00000000";
    // fixo
    $aLinhas[] = "29";
    // fixo
    $aLinhas[] = "00000000";
    // cep ;
    $aLinhas[] = str_pad($oDadosServidor->z01_cep, 8, "0", STR_PAD_RIGHT);
    // fixo
    $aLinhas[] = "30";
    // fixo
    $aLinhas[] = "00000000000000";
    // fixo
    $aLinhas[] = "00000";
    // fixo
    $aLinhas[] = "00000000000000";
    // fixo
    $aLinhas[] = "00000000000000";
    // data de nascimento ;
    $aLinhas[] = $oDadosServidor->nascimento;
    // Valor total de salário - 50
    $aLinhas[] = str_pad($oDadosServidor->proventos, 14, "0", STR_PAD_LEFT); //@todo verificar se é esse campo
    // Código da pensao - 51
    $aLinhas[] = 1;
    //PIS/PASEP - 52
    $aLinhas[] = str_pad($oDadosServidor->pis_pasep, 20, "0", STR_PAD_LEFT);
    
    /**
     * Campos de 53 a 58.
     */
    /**
     * Retorna o assentamento do tipo aposentadoria (43).
     */
    $aAssentamentoAposentadoria = AssentamentoRepository::getAssentamentosPorServidor($oServidor, 43);
    $oAssentamentoAposentadoria = $aAssentamentoAposentadoria[0];
    $aLinhas = array_merge($aLinhas, getDadosAposentadoria($oServidor,$oAssentamentoAposentadoria));



    //sexo da pessoa - 59
    $aLinhas[] = $oDadosServidor->sexo; 

    //indicador de periculosidade ou insalubridade - 60
    $oCalculoFixo          = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_PONTO_FIXO);
    $aRubricaInsalubridade = $oCalculoFixo->getEventosFinanceiros(null, array('0043', '0044'));
    $aLinhas[]             = count($aRubricaInsalubridade) > 0 ? 'S' : 'N';
    //remuneração base para aposentadoria - 61
    $aLinhas[] = str_pad($oDadosServidor->base_prev, 14, "0", STR_PAD_RIGHT);
    //remuneração de final de carreira - 62
    $aLinhas[] = str_pad($oDadosServidor->base_prev, 14, "0", STR_PAD_RIGHT);
    //reserva de poupança - 63
    $aLinhas[] = '00000000000000';
    //vínculo ao ente - 64
    $aLinhas[] = 1;
    //tipo de servidor - 65
    $aLinhas[] = 1;
    //categoria do servidor - 66
    $aLinhas[] = 1;  //Ainda esta sendo regulamentado pelo governo federal, então por enquanto é sempre 1

    //estado civil - 67
    switch ($oDadosServidor->estado_civil) {
      case 1:
        $sEstadoCivil = 'SOLTEIRO';
        break;
      case 2:
        $sEstadoCivil = 'CASADO';
        break;
      case 3:
        $sEstadoCivil = 'VIUVO';
        break;
      default:
        $sEstadoCivil = 'DIVORCIADO';
        break;
    }

    $aLinhas[] = str_pad($sEstadoCivil, 30, " ", STR_PAD_RIGHT);

    //Tempo total de aposentadoria - 68
    if ($oAssentamentoAposentadoria) {
      $iTotalDiasAposentadoria = (int) DBDAte::calculaIntervaloEntreDatas(new DBDate(date('Y-m-d')), $oAssentamentoAposentadoria->getDataConcessao(), 'd') + 1;
    }

    $aLinhas[] = str_pad((isset($iTotalDiasAposentadoria) ? $iTotalDiasAposentadoria : 0), 10, "0", STR_PAD_LEFT);
    //Data de falescimento - 69
    $aLinhas[] = str_pad($oDadosServidor->data_falescimento, 8, "0", STR_PAD_LEFT);
    //campo 70 - identidade
    $aLinhas[] = str_pad(substr($oDadosServidor->identidade, 0, 10), 10, "0", STR_PAD_LEFT);
    //campo 71 - titulo de eleitor
    $aLinhas[] = str_pad($oDadosServidor->titulo_eleitor, 12, "0", STR_PAD_LEFT);
    //campo 72 - zona
    $aLinhas[] = str_pad($oDadosServidor->zona_titulo, 10, "0", STR_PAD_LEFT);
    //campo 73 - secao
    $aLinhas[] = str_pad($oDadosServidor->secao_titulo, 10, "0", STR_PAD_LEFT);
    //campo 74 - secao
    $aLinhas[] = $oDadosServidor->admissao;
    //campo 75 - data de entrada no cargo atual
    $aLinhas[] = $oDadosServidor->admissao;
    //campo 76 - codigo do cargo
    $aLinhas[] = str_pad($oDadosServidor->codigo_cargo, 10, "0", STR_PAD_LEFT);
    //campo 77 - regime de horario - @todo verificar se é mensal.
    $aLinhas[] = str_pad($oDadosServidor->regime_horario, 10, "0", STR_PAD_LEFT);
    //campo 78 - data de entrada no cargo anterior
    $aLinhas[] = '00000000';
    //campo 79 - regime de horario
    $aLinhas[] = db_formatar($oDadosServidor->rescisao,'s','0',8,'e');
    //campo 80 - data de nomeacao do funcionario
    $aLinhas[] = $oDadosServidor->admissao;
    //campo 81 - numero da conta corrente
    $aLinhas[] = str_pad($oDadosServidor->conta_bancaria, 15, " ", STR_PAD_LEFT);
    //campo 82 - numero da certidao de nascimento
    $aLinhas[] = str_pad($oDadosServidor->numero_certidao_nascimento, 20, " ", STR_PAD_LEFT);;
    //campo 83 - numero do telefone comercial
    $aLinhas[] = str_pad($oDadosServidor->numero_telefone_comercial, 13, " ", STR_PAD_LEFT);
    //campo 84 - numero do telefone residencial
    $aLinhas[] = str_pad($oDadosServidor->numero_telefone_residencial, 13, " ", STR_PAD_LEFT);;
    //campo 85 - numero do fax
    $aLinhas[] = str_pad($oDadosServidor->numero_fax, 13, " ", STR_PAD_LEFT);
    //campo 86 - numero do telefone celular
    $aLinhas[] = str_pad($oDadosServidor->numero_telefone_celular, 13, " ", STR_PAD_LEFT);
    //campo 87 - email
    $aLinhas[] = str_pad($oDadosServidor->email, 50, " ", STR_PAD_RIGHT);
    //campo 88 - nome conjuge
    $aLinhas[] = str_pad($oDadosServidor->nome_conjuge, 50, " ", STR_PAD_RIGHT);;
    //campo 89 - uf zona titulo eleitor
    $aLinhas[] = $oDadosServidor->uf_zona_titulo;
    //campo 90 - nacionalidade
    $aLinhas[] = str_pad($oDadosServidor->nacionalidade, 50, " ", STR_PAD_RIGHT);
    //campo 91 - naturalidade
    $aLinhas[] = str_pad($oDadosServidor->naturalidade, 50, " ", STR_PAD_RIGHT);

    $iGrauInstrucao = 12;
    switch ($oDadosServidor->grau_instrucao) {

      case '0':
        $iGrauInstrucao = 12;
      break;
      case '1' :
        $iGrauInstrucao = '01';
      break;
      case '2' :
        $iGrauInstrucao = '02';
      break;
      case '3' :
        $iGrauInstrucao = '03';
      break;
      case '4' :
        $iGrauInstrucao = '04';
      break;
      case '5' :
        $iGrauInstrucao = '05';
      break;
      case '6' :
        $iGrauInstrucao = '06';
      break;
      case '7' :
        $iGrauInstrucao = '07';
      break;
      case '8':
        $iGrauInstrucao = '09';
      break;
      case '9':
        $iGrauInstrucao = '10';
      break;
    }

    //campo 92 - grau de instrução
    $aLinhas[] = str_pad($iGrauInstrucao, 2, "0", STR_PAD_RIGHT);
    //campo 93 - codigo do banco
    $aLinhas[] = str_pad($oDadosServidor->codigo_banco, 6, "0", STR_PAD_RIGHT);
    //campo 94 - codigo da agencia
    $aLinhas[] = str_pad($oDadosServidor->codigo_agencia_bancaria, 6, "0", STR_PAD_RIGHT);;
    //campo 95 - padrao - campo em branco
    $aLinhas[] = str_repeat('0', 40);
    //campo 96 - orgao expedidor identidade
    $aLinhas[] = str_pad($oDadosServidor->orgao_expedidor_identidade, 15, " ", STR_PAD_LEFT);
    //campo 97 - data expedicao identidade
    $aLinhas[] = str_pad($oDadosServidor->data_expedicao_identidade, 8, "0", STR_PAD_RIGHT);
    //campo 98 - uf orgao expedidor identidade
    $aLinhas[] = str_pad($oDadosServidor->uf_expedidor_identidade, 2, " ", STR_PAD_LEFT);
    //campo 99 - complemento endereco
    $aLinhas[] = str_pad($oDadosServidor->complemento_imovel, 30, " ", STR_PAD_LEFT);
    //campo 100 - numero do imovel
    $aLinhas[] = str_pad($oDadosServidor->numero_imovel, 8, "0", STR_PAD_RIGHT);;
    //campo 101 - tipo do logradouro
    $aLinhas[] = str_pad($oDadosServidor->tipo_logradouro, 3, 0, STR_PAD_RIGHT);
    //campo 102 - deficiente
    $aLinhas[] = $oDadosServidor->deficiente_fisico == 't' ? 'S' : 'N';

    $sLinha   .= implode($sSeparador, $aLinhas) . PHP_EOL;
    fputs($arquivo, $sLinha);

    // Monta a seção de depentendes do servidor
    fputs($arquivo, getDependentesPorServidor($oServidor, $oCompetencia, $iTabelaPrevidencia));

    // Monta seção para afastamentos.
    fputs($arquivo, getAfastamentosDoServidor($oServidor));

    // Monta seção para tempo anterior.
    fputs($arquivo, getAssentamentosTempoAnteriorPorServidor($oServidor));

    // Monta a seção de assentamentos
    fputs($arquivo, getAssentamentosPorServidor($oServidor));

    $troca = montaPDF($pdf, $oDadosServidor, $bases, $troca, $alt, $sTipoFolha);
  }

  $pdf->cell(158,$alt,'',0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar( $total_base,'f'),0,0,"R",0);
  $pdf->cell(48,$alt,db_formatar( $total_patronal,'f'),0,0,"R",0);
  $pdf->cell(48,$alt,db_formatar( $total_desc,'f'),0,0,"R",0);

  $pdf->Output($nomepdf,false,true);
  fclose($arquivo);
}

// ------------- Relatorio em PDF ---------------- //
function montaPDF($pdf, $oDadosServidor, $bases, $troca, $alt, $sTipoFolha) {

  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){

    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $troca = 0;
    $pdf->cell(25,$alt,"Regist ",0,0,"L",0);
    $pdf->cell(60,$alt,"Nome  ",0,0,"L",0);
    $pdf->cell(20,$alt,"Lot. ",0,0,"L",0);
    $pdf->cell(25,$alt,"Inicio",0,0,"L",0);
    $pdf->cell(10,$alt,"Sit.",0,0,"L",0);
    $pdf->cell(18,$alt,"Cod. ",0,0,"L",0);
    $pdf->cell(20,$alt,"Base/Prov",0,0,"R",0);
    $pdf->cell(48,$alt,"Contrib. Entid %.",0,0,"R",0);
    $pdf->cell(48,$alt,"Contrib. Serv. %",0,1,"R",0);
  }

  $oDataAdmissao = !empty($oDadosServidor->r01_admiss) ? new DBDate($oDadosServidor->r01_admiss) : null;

  $pdf->cell(25,$alt,$oDadosServidor->regist,0,0,"L",0);
  $pdf->cell(60,$alt,db_substr($oDadosServidor->nome,1,30),0,0,"L",0);
  $pdf->cell(20,$alt,$oDadosServidor->lotacao ,0,0,"L",0);

  if(!empty($oDataAdmissao)) {
    $pdf->cell(25,$alt,$oDataAdmissao->getDate(DBDate::DATA_PTBR),0,0,"L",0);
  } else {
    $pdf->cell(25,$alt,"",0,0,"L",0);
  }

  $pdf->cell(10,$alt,$oDadosServidor->situacao,0,0,"L",0);

  if( $oDadosServidor->situacao == '03'){
    $pdf->cell(18,$alt,$oDadosServidor->cod_apos,0,0,"R",0);;
  } else {
    $pdf->cell(18,$alt,"",0,0,"R",0);;
  }

  if( $oDadosServidor->situacao == '01' ){
    $pdf->cell(20,$alt,db_formatar( round(db_val($oDadosServidor->base_prev)/100,2),'f'),0,0,"R",0);
  } else {
    $pdf->cell(20,$alt,db_formatar( round(db_val($oDadosServidor->proventos)/100,2),'f'),0,0,"R",0);
  }


  // inativos e pensionistas
  if(( db_at($oDadosServidor->situacao,"02-03") > 0)  || $bases <= 0){
    $pdf->cell(48, $alt,db_formatar(0,'f') ."%". db_formatar(0,'f'), 0, 0, "R", 0) ;
  } else { // somente ativos tem desconto de previdencia;
    $pdf->cell(48,$alt,db_formatar($oDadosServidor->perc_patronal,'f')."%".db_formatar( round(db_val($oDadosServidor->cont_ent)/100,2),'f'),0,0,"R",0);
  }

  // utilizar o percentual do salario e caso vazio o da complementar;
  if( $sTipoFolha == "S" ){

    if ( db_val( $oDadosServidor->quant_desc_prev ) > 0) {
      $pdf->cell(30,$alt,db_formatar( round(db_val($oDadosServidor->quant_desc_prev)/100,2),'f')."%",0,0,"R",0);
    } else if(db_val( $quant_desc_prevc ) > 0) {
      $pdf->cell(30,$alt,db_formatar( round(db_val($oDadosServidor->quant_desc_prevc)/100,2),'f')."%",0,0,"R",0);
    } else {
      $pdf->cell(30,$alt,db_formatar( round(db_val($oDadosServidor->quant_desc_prevr)/100,2),'f')."%",0,0,"R",0);
    }

  } else { // 13.salario;

    if(db_val( $oDadosServidor->quant_desc_prev ) > 0) {
      $pdf->cell(30,$alt,db_formatar( round(db_val($oDadosServidor->quant_desc_prev)/100,2),'f')."%",0,0,"R",0);
    } else {
      $pdf->cell(30,$alt,db_formatar( round(db_val($oDadosServidor->quant_desc_prevr)/100,2),'f')."%",0,0,"R",0);
    }
  }

  $pdf->cell(18,$alt,db_formatar(round(db_val($oDadosServidor->desc_prev)/100,2),'f'),0,1,"R",0);

  return $troca;
}

function getDependentesPorServidor(Servidor $oServidor, DBCompetencia $oCompetencia, $iTabelaPrevidencia, $sSeparador = ";") {

  $camposSqlDepentendes   = array();
  $camposSqlDepentendes[] = "lpad(rh31_regist,10,0) as matricula";
  $camposSqlDepentendes[] = "rh31_nome as nome";
  $camposSqlDepentendes[] = "coalesce(to_char(rh31_dtnasc,'ddmmyyyy'),'00000000') as data_nascimento";
  $camposSqlDepentendes[] = "case rh31_gparen    
                                  when 'F' then '01'    
                                  when 'C' then '02'    
                                  when 'P' then '03'    
                                  when 'M' then '03'    
                                  else '90'    
                             end as grau_parentesco";

  $sqlDependentes  = " SELECT " .implode(",", $camposSqlDepentendes);
  $sqlDependentes .= "   FROM rhdepend
                        INNER JOIN rhpessoal    on  rhpessoal.rh01_regist    = rhdepend.rh31_regist  
                        INNER JOIN rhpessoalmov on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                               and  rhpessoalmov.rh02_anousu = ".$oCompetencia->getAno()."
                                               and  rhpessoalmov.rh02_mesusu = ".$oCompetencia->getMes()."
                                               and  rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")."   
                        INNER JOIN cgm          on  cgm.z01_numcgm = rhpessoal.rh01_numcgm ";

  $aWhereDependentes   = array();
  $aWhereDependentes[] = "rh02_tbprev = ". $iTabelaPrevidencia;
  $aWhereDependentes[] = "rh31_regist = ". $oServidor->getMatricula();
  $sWhereDependentes   = implode(" and ", $aWhereDependentes);

  $sqlDependentes .= " WHERE ". $sWhereDependentes ." order by rh01_regist";
  $rsDependentes   = db_query($sqlDependentes);

  if(!$rsDependentes) {
    throw new Exception("Ocorreu um erro ao buscar os dependentes do servidor: {$oServidor->getMatricula()}.\nContate o suporte.");
  }

  $aDependetes = array();
  $aDependetes = db_utils::makeCollectionFromRecord($rsDependentes, function($oDependente){
    return $oDependente;
  });

  $sLinhas = "";
  foreach ($aDependetes as $oDependenteServidor) {

    $aLinhas   = array();
    $aLinhas[] = "3";

    //Mátricula do servidor
    $aLinhas[] = $oDependenteServidor->matricula;

    //Nome do dependente
    $aLinhas[] = db_formatar($oDependenteServidor->nome,'s',' ',40,'d');

    //Data de nascimento do dependente
    $aLinhas[] = $oDependenteServidor->data_nascimento;

    //Grau de parentesco do dependente
    $aLinhas[] = $oDependenteServidor->grau_parentesco;

    //Campos nao disponiveis;
    $aLinhas[] = "; ; ;";

    $sLinhas  .= implode($sSeparador, $aLinhas) . PHP_EOL;
  }

  return $sLinhas;
}


function getAssentamentosTempoAnteriorPorServidor(Servidor $oServidor, $sSeparador = ";") {

  $sLinhas = "";

  /**
   * Tipos de assentamentos que serão buscados para tempo anterior.
   */
  $aTiposAssentamenos     = array(14,16,15,32,58);
  $aAssentamentosServidor = AssentamentoRepository::getAssentamentosPorServidor($oServidor, $aTiposAssentamenos);


  if(!empty($aAssentamentosServidor)) {

    foreach ($aAssentamentosServidor as $oAssentamento) {

      $aAtributosDinamicos = $oAssentamento->getAtributosDinamicos();
      $sEmpresa            = '';
      $sTipoContribuicao   = '';
      $sTipoEmpresa        = '';

      /**
       * Percorremos os atributos dinâmicos, obtendo os valores par aos campos
       * Nome da EMpresa, Tipo de contribuição e tipo de empresa.
       */
      foreach ($aAtributosDinamicos as $oAtributoDinamico) {

        switch (trim(mb_strtolower($oAtributoDinamico->nomeAtributo))) {

          case 'empresa onde trabalhou':
            $sEmpresa = $oAtributoDinamico->valorAtributo;
            break;
          case 'tipo de contribuição da pessoa':
            $sTipoContribuicao = $oAtributoDinamico->valorAtributo;
            echo "\nTipo: ";
            echo "<Pre>";
            print_r($oAtributoDinamico);
            echo "</Pre>";
            break;
          case 'tipo de empresa':
            $sTipoEmpresa = $oAtributoDinamico->valorAtributo;
            break;
        }
      }

      $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oAssentamento->getTipoAssentamento());
      $aLinha = array();

      $aLinha[] = "5";

      // Código (matrícula) do servidor
      $aLinha[] = str_pad($oServidor->getMatricula(), 10, "0", STR_PAD_LEFT);

      // Data de Admissão
      $aLinha[] = str_replace("/", "", $oAssentamento->getDataConcessao()->getDate(DBDate::DATA_PTBR) );
      $iDias = $oAssentamento->getDataTermino() !== null ? $oAssentamento->getDias() : DBDate::calculaIntervaloEntreDatas(new DBDate(date('Y-m-d')), $oAssentamento->getDataConcessao(), 'd');
      /**
       * Data de Demissão.
       * @todo sverificar se é este o comportamento quando não existe data final no assentamento.
       */
      $oDataFinal = $oAssentamento->getDataTermino();

      if (is_null($oDataFinal)) {
        $oDataFinal = new DBDate(date('Y-m-d'));
      }

      $aLinha[] = str_pad(str_replace("/", "", $oDataFinal->getDate(DBDate::DATA_PTBR)), 8, " ", STR_PAD_RIGHT);

      //Empresa onde trabalhou
      $aLinha[] = str_pad($sEmpresa, 40, " ", STR_PAD_RIGHT);

      // Número de dias do tempo anterior.
      $aLinha[] = str_pad($iDias, 10, "0", STR_PAD_LEFT);

      // Tipo de contribuição da Pessoa.
      $aLinha[] = str_pad($sTipoContribuicao, 1, "0", STR_PAD_LEFT);

      // Tipo de empresa.
      $aLinha[] = str_pad($sTipoEmpresa, 1, "0", STR_PAD_LEFT);

      $sLinhas .= implode($sSeparador, $aLinha);
      $sLinhas .= PHP_EOL;
    }
  }

  return $sLinhas;
}

function getAssentamentosPorServidor(Servidor $oServidor, $sSeparador = ";") {

  $sLinhas = "";
  $aAssentamentosServidor = AssentamentoRepository::getAssentamentosPorServidor($oServidor, null, null,  'S');

  if(!empty($aAssentamentosServidor)) {

    foreach ($aAssentamentosServidor as $oAssentamento) {

      $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oAssentamento->getTipoAssentamento());
      $iDias              = $oAssentamento->getDataTermino() !== null ? $oAssentamento->getDias() : DBDate::calculaIntervaloEntreDatas(new DBDate(date('Y-m-d')), $oAssentamento->getDataConcessao(), 'd');
      $aLinhas           = array();

      $aLinhas[] = "6";

      // Código (matrícula) do servidor
      $aLinhas[] = str_pad($oServidor->getMatricula(), 10, "0", STR_PAD_LEFT);

      // Data do assentamento
      $aLinhas[] = str_replace("/", "", $oAssentamento->getDataConcessao()->getDate(DBDate::DATA_PTBR) );

      // Quantidade dias do assentamento
      $aLinhas[] = str_pad($iDias, 4, "0", STR_PAD_LEFT);

      // Descrição resumida do assentamento (descrição do tipo de assentamento)
      $aLinhas[] = str_pad(substr($oTipoAssentamento->getDescricao(), 0, 40), 40, " ", STR_PAD_RIGHT);

      // Detalhamento, campo histórico do assentamento
      $aLinhas[] = str_pad(substr(str_replace(array(PHP_EOL, "\r\n", "\n\r", "\n", "\r"), ' ', $oAssentamento->getHistorico()), 0, 500), 500, " ", STR_PAD_RIGHT);
      
      $sLinhas  .= implode($sSeparador, $aLinhas) . PHP_EOL;
    }
  }

  return $sLinhas;
}

/**
 * @param \Servidor $oServidor
 * @param string    $sSeparador
 * @return string
 */
function getAfastamentosDoServidor(Servidor $oServidor, $sSeparador = ";") {

  $aAfastamentos = AssentamentoRepository::getAssentamentosDeAfastamentoPorServidor($oServidor);
  $sLinha        = '';

  foreach ($aAfastamentos as $oAfastamento) {

    $sDatFinal = '';
    if ($oAfastamento->getDataTermino() != '') {
      $sDatFinal = str_replace("/", "", $oAfastamento->getDataTermino()->getDate(DBDate::DATA_PTBR));
    }

    $sTipo     = 'O';
    $sDesconto = "N";
    switch ($oAfastamento->getTipoAssentamento()) {

      case '1':
      case '2':
      case '3':

        $sDesconto = 'N';
        $sTipo     = 'F';
        break;

      case  "6":
      case  "8":
      case  "9":
      case  "10":
      case  "11":
      case  "4":
      case  "5":
      
        $sDesconto = 'N';
        $sTipo     = 'L';

        break;
      case  "22":
      case  "23":
        $sDesconto = 'S';
        $sTipo     = 'L';
      break;

     case "42":
        $sDesconto = 'N';
        $sTipo     = 'S';
      break;
     default:
        $sTipo = 'O';
       break;
    }

    $aLinha   = array();
    $aLinha[] = "4";
    $aLinha[] = str_pad($oServidor->getMatricula(), 10, "0", STR_PAD_LEFT);
    $aLinha[] = str_replace("/", "", $oAfastamento->getDataConcessao()->getDate(DBDate::DATA_PTBR));
    $aLinha[] = str_pad($sDatFinal, 8, " ", STR_PAD_LEFT);
    $aLinha[] = str_pad(substr(str_replace(array(PHP_EOL, "\r\n", "\n\r", "\n", "\r"), ' ', $oAfastamento->getHistorico()), 0, 40), 40, " ", STR_PAD_RIGHT);
    $aLinha[] = $sTipo;
    $aLinha[] = $sDesconto;
    $sLinha .= implode($sSeparador, $aLinha)."\n";
  }
  return $sLinha;
}

function getDadosAposentadoria($oServidor, $oAssentamentoAposentadoria) {

  if ($oAssentamentoAposentadoria) {

    $aAtributosDinamicos     = $oAssentamentoAposentadoria->getAtributosDinamicos();
    $iAtoAposentadoria       = $oAssentamentoAposentadoria->getCodigoPortaria();
    $sDataConcessaoBeneficio = str_replace("/", "", $oAssentamentoAposentadoria->getDataConcessao()->getDate(DBDate::DATA_PTBR) );

    /**
     * Verificamos os atributos dinâmicos.
     */
    foreach ($aAtributosDinamicos as $oAtributoDinamico) {

      switch (trim(mb_strtolower($oAtributoDinamico->nomeAtributo))) {

        case 'valor do benefício':
          $iNumeroHomologacaoTCE = $oAtributoDinamico->valorAtributo;
          break;
        case 'número de homologação tce':
          $sValorBeneficio       = $oAtributoDinamico->valorAtributo;
          break;
      }
    }
  }
     
  //Número do ato da aposentadoria - 53
  $aLinhas[] = str_pad((isset($iAtoAposentadoria) ? $iAtoAposentadoria : null), 25, " ", STR_PAD_LEFT);
  //Número do homologação TCE - 54
  $aLinhas[] = str_pad((isset($iNumeroHomologacaoTCE) ? $iNumeroHomologacaoTCE : null), 25, " ", STR_PAD_LEFT);
  //Nome do Pai - 55
  $aLinhas[] = str_pad($oServidor->getCgm()->getNomePai(), 40, " ", STR_PAD_RIGHT);
  //Nome da Mãe - 56
  $aLinhas[] = str_pad($oServidor->getCgm()->getNomeMae(), 40, " ", STR_PAD_RIGHT);
  //Data de concessão do benefício - 57
  $aLinhas[] = str_pad((isset($sDataConcessaoBeneficio) ? $sDataConcessaoBeneficio : null), 8, "0", STR_PAD_RIGHT);
  //valor do benefício - 58
  $aLinhas[] = str_pad((isset($sValorBeneficio) ? $sValorBeneficio : null), 14, "0", STR_PAD_RIGHT);

  return $aLinhas;
}
