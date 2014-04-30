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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_cfpess_classe.php");
require_once("classes/db_rhemitecontracheque_classe.php");

$oPost = db_utils::postMemory($_POST);

$clcfpess              = new cl_cfpess;
$clrhemitecontracheque = new cl_rhemitecontracheque();
$clrotulo              = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clcfpess->rotulo->label();

$iAnoFolha       = db_anofolha();
$iMesFolha       = db_mesfolha();
$iInstit         = db_getsession('DB_instit');

$sDisabled       = "";
$lErro           = false;

$sCampos         = "r11_anousu,r11_mesusu,r11_instit,r11_mensagempadraotxt";
$sSqlCfPess      = $clcfpess->sql_query($iAnoFolha,$iMesFolha,$iInstit,$sCampos,null,"");
$rsCfPess        = $clcfpess->sql_record($sSqlCfPess);

if ($clcfpess->numrows > 0) {
  
  $oCfPess               = db_utils::fieldsMemory($rsCfPess,0);
  $sMensagemPadrao       = $oCfPess->r11_mensagempadraotxt;
  $r11_mensagempadraotxt = $oCfPess->r11_mensagempadraotxt;
}

if (isset($oPost->r11_mensagempadraotxt) && empty($oPost->r11_mensagempadraotxt)) {
  
  if ($clcfpess->numrows == 0 || $sMensagemPadrao == "") {
    
    $sMsgErro   = "Usuário: \\n\\n";
    $sMsgErro  .= " Mensagem padrao não cadastrada! \\n";
    $sMsgErro  .= " Para processar a emissão do contra-cheque, você deve informar uma mensagem padrão,\\n";
    $sMsgErro  .= " no menu de acesso: Pessoal > Procedimentos > Manutenção de Parâmetros > Gerais. \\n";
    $sMsgErro  .= " ou preeencha o campo Expressão padrão contra-cheque gráfica. \\n";
  
    $sDisabled  = "disabled"; 
  }
} else {
  
  if (isset($oPost->r11_mensagempadraotxt)) {
    $sMensagemPadrao = $oPost->r11_mensagempadraotxt;
  }
}

if (isset($oPost->processar)) {

  $sDirArq  = '/tmp/contra_cheque_grafica.txt';
  $pArquivo = fopen($sDirArq,'w');  

  $sWhere   = "";
  $sAnd     = "";
  $sOrder   = "";
  
  $sSigla   = "";
  $sTabela  = "";
  
  if (isset($oPost->tipofol) && !empty($oPost->tipofol)) {
    
    switch ($oPost->tipofol) {

      case "r48":
        
        $sSigla  = "r48";
        $sTabela = "gerfcom";
        break;
      
      case "r20":
        
        $sSigla  = "r20";
        $sTabela = "gerfres";
        break;
      
      case "r35":
        
        $sSigla  = "r35";
        $sTabela = "gerfs13";
        break;
      
      case "r22":
        
        $sSigla  = "r22";
        $sTabela = "gerfadi";
        break;
        
      default :
        
        $sSigla  = "r14";
        $sTabela = "gerfsal";
        break;
    }
    
  }
  
  if (isset($oPost->tiporesumo) && !empty($oPost->tiporesumo)) {
    
    if (isset($oPost->lista)) {
      $sLista  = implode(",",$oPost->lista);
    }
    
    switch ($oPost->tiporesumo) {

      case "l":
      
        $sOrder = "r70_estrut";
        if (isset($oPost->tipofiltro) && $oPost->tipofiltro == 's') {
         
          if (isset($oPost->lista)) {
        
            $sLista  = implode(",",$oPost->lista);
            $sWhere .= " {$sAnd} r70_estrut in ({$sLista})";
            $sAnd    = "and";
          }
        } else {
        
          if (isset($oPost->cod_ini) && isset($oPost->cod_fim)) {
            
            if (!empty($oPost->cod_ini) && !empty($oPost->cod_fim)) {
         
              $sWhere .= " {$sAnd} r70_estrut between {$oPost->cod_ini} and {$oPost->cod_fim}";
              $sAnd    = "and";
            } else if (!empty($oPost->cod_ini)) {
              
              $sWhere .= " {$sAnd} r70_estrut = {$oPost->cod_ini}";
              $sAnd    = "and";           
            } else if (!empty($oPost->cod_fim)) {
              
              $sWhere .= " {$sAnd} r70_estrut = {$oPost->cod_fim}";
              $sAnd    = "and";            
            }
          }
        }
        break;
        
      case "t":
      
        $sOrder = "r55_estrut"; 
        if (isset($oPost->tipofiltro) && $oPost->tipofiltro == 's') {
         
          if (isset($oPost->lista)) {
        
            $sLista  = implode(",",$oPost->lista);
            $sWhere .= " {$sAnd} r55_estrut in ({$sLista})";
            $sAnd    = "and";
          }
        } else {
        
          if (isset($oPost->cod_ini) && isset($oPost->cod_fim)) {
            
            if (!empty($oPost->cod_ini) && !empty($oPost->cod_fim)) {
         
              $sWhere .= " {$sAnd} r55_estrut between {$oPost->cod_ini} and {$oPost->cod_fim}";
              $sAnd    = "and";
            } else if (!empty($oPost->cod_ini)) {
              
              $sWhere .= " {$sAnd} r55_estrut = {$oPost->cod_ini}";
              $sAnd    = "and";            
            } else if (!empty($oPost->cod_fim)) {
              
              $sWhere .= " {$sAnd} r55_estrut = {$oPost->cod_fim}";
              $sAnd    = "and";          
            }
          }
        }
        break;
        
      case "o":
      
        $sOrder = "o40_orgao";
        if (isset($oPost->tipofiltro) && $oPost->tipofiltro == 's') {
         
          if (isset($oPost->lista)) {
        
            $sLista  = implode(",",$oPost->lista);
            $sWhere .= " {$sAnd} o40_orgao in ({$sLista})";
            $sAnd    = "and";
          }
        } else {
        
          if (isset($oPost->cod_ini) && isset($oPost->cod_fim)) {
            
            if (!empty($oPost->cod_ini) && !empty($oPost->cod_fim)) {
         
              $sWhere .= " {$sAnd} o40_orgao between {$oPost->cod_ini} and {$oPost->cod_fim}";
              $sAnd    = "and";
            } else if (!empty($oPost->cod_ini)) {
              
              $sWhere .= " {$sAnd} o40_orgao = {$oPost->cod_ini}";
              $sAnd    = "and";            
            } else if (!empty($oPost->cod_fim)) {
              
              $sWhere .= " {$sAnd} o40_orgao = {$oPost->cod_fim}";
              $sAnd    = "and";            
            }
          }
        }
        break;
        
      default:
        
        $sOrder = "matricula";
        if (isset($oPost->tipofiltro) && $oPost->tipofiltro == 's') {
         
          if (isset($oPost->lista)) {
        
            $sLista  = implode(",",$oPost->lista);
            $sWhere .= " {$sAnd} matricula in ({$sLista})";
            $sAnd    = "and";
          }
        } else {
         
          if (isset($oPost->cod_ini) && isset($oPost->cod_fim)) {
            
            if (!empty($oPost->cod_ini) && !empty($oPost->cod_fim)) {
          
              $sWhere .= " {$sAnd} matricula between {$oPost->cod_ini} and {$oPost->cod_fim}";
              $sAnd    = "and";
            } else if (!empty($oPost->cod_ini)) {
              
              $sWhere .= " {$sAnd} matricula = {$oPost->cod_ini}";
              $sAnd    = "and";             
            } else if (!empty($oPost->cod_fim)) {
              
              $sWhere .= " {$sAnd} matricula = {$oPost->cod_fim}";
              $sAnd    = "and";             
            }
          }
        }
        break;
    }
  }
  
  if (isset($oPost->mostord)) {
    
    switch ($oPost->mostord) {
      
      case "n":
        
        if (empty($sOrder)) {
          $sOrder = "r70_estrut,nome";
        }
        
        $sOrder = "order by {$sOrder}";
        break;
      
      default:
        
        $sOrder = "order by r70_estrut,nome";
        break;
    }
    
  }
  
  if (!empty($sWhere)) {
    $sWhere = "where {$sWhere}";
  }

  fputs($pArquivo,'CP'.db_formatar( strtoupper(db_mes($oPost->mesfolha)),'s',' ',9,'e',0).
                                               '/'.$oPost->anofolha.str_repeat($sMensagemPadrao, 78)."\r\n");
  
  $sSqlRhPessoalMov  = "     select *                                                                                 ";
  $sSqlRhPessoalMov .= "       from ( select distinct                                                                 ";
  $sSqlRhPessoalMov .= "                     r70_estrut,                                                              ";
  $sSqlRhPessoalMov .= "                     rh01_regist as matricula,                                                ";
  $sSqlRhPessoalMov .= "                     lpad(rh01_regist,6,'0') as regist,                                       ";
  $sSqlRhPessoalMov .= "                     rpad(z01_nome,45,' ') as nome,                                           ";
  $sSqlRhPessoalMov .= "                     rpad(case                                                                ";
  $sSqlRhPessoalMov .= "                            when rh04_descr is null                                           "; 
  $sSqlRhPessoalMov .= "                              then rh37_descr                                                 ";
  $sSqlRhPessoalMov .= "                            else rh04_descr end ,40,' ') as cargo,                            ";
  $sSqlRhPessoalMov .= "                     to_char(rh01_admiss,'ddmmYYYY') as admissao,                             ";
  $sSqlRhPessoalMov .= "                     rpad(z01_cgccpf,11,' ') as cpf,                                          ";
  $sSqlRhPessoalMov .= "                     lpad(substr(rh44_codban,1,4),4,'0') as banco,                            ";
  $sSqlRhPessoalMov .= "                     rh44_agencia as agencia,                                                 ";
  $sSqlRhPessoalMov .= "                     rh44_dvagencia as dvagencia,                                             ";
  $sSqlRhPessoalMov .= "                     translate(to_char(to_number((case                                        ";
  $sSqlRhPessoalMov .= "                                                    when trim(rh44_conta) = ''                "; 
  $sSqlRhPessoalMov .= "                                                      then '0'                                ";
  $sSqlRhPessoalMov .= "                                                    else rh44_conta end ),                    ";
  $sSqlRhPessoalMov .= "                                       '99999999999'),'99,999999,9'),',','') as conta,        ";
  $sSqlRhPessoalMov .= "                     rh44_dvconta as dvconta,                                                 ";
  $sSqlRhPessoalMov .= "                     rpad(rh52_descr,16,' ') as regime,                                       ";
  $sSqlRhPessoalMov .= "                     rh52_regime as cod_regime,                                               ";
  $sSqlRhPessoalMov .= "                     rpad(o40_descr,45,' ') as orgao,                                         ";
  $sSqlRhPessoalMov .= "                     rpad(r70_descr,45,' ') as setor,                                         ";
  $sSqlRhPessoalMov .= "                     rpad(z01_ender,45,' ') as ender,                                         ";
  $sSqlRhPessoalMov .= "                     rpad(substr(z01_compl,1,15),15,' ') as compl,                            ";
  $sSqlRhPessoalMov .= "                     to_char(z01_numero,'999999') as numero,                                  ";
  $sSqlRhPessoalMov .= "                     z01_cep as cep,                                                          ";
  $sSqlRhPessoalMov .= "                     rpad(substr(z01_bairro,1,25),25,' ') as bairro,                          ";
  $sSqlRhPessoalMov .= "                     rpad(substr(z01_munic,1,25),25,' ') as munic                             ";
  //$sSqlRhPessoalMov .= "                     {$sSigla}regist as regist                                              ";
  $sSqlRhPessoalMov .= "                from rhpessoalmov                                                             ";
  $sSqlRhPessoalMov .= "                     inner join rhpessoal    on rh01_regist       = rh02_regist               ";
  $sSqlRhPessoalMov .= "                     inner join {$sTabela}   on {$sSigla}_regist  = rh01_regist               ";
  $sSqlRhPessoalMov .= "                                            and {$sSigla}_anousu  = rh02_anousu               ";
  $sSqlRhPessoalMov .= "                                            and {$sSigla}_mesusu  = rh02_mesusu               ";
  $sSqlRhPessoalMov .= "                                            and {$sSigla}_instit  = rh02_instit               ";
  $sSqlRhPessoalMov .= "                     inner join cgm          on z01_numcgm        = rh01_numcgm               ";
  $sSqlRhPessoalMov .= "                     left  join rhpesbanco   on rh44_seqpes       = rh02_seqpes               ";
  $sSqlRhPessoalMov .= "                     inner join rhfuncao     on rh37_funcao       = rh01_funcao               ";
  $sSqlRhPessoalMov .= "                                            and rh37_instit       = rh02_instit               ";
  $sSqlRhPessoalMov .= "                     left join  rhpescargo   on rh20_seqpes       = rh02_seqpes               ";
  $sSqlRhPessoalMov .= "                     left join  rhcargo      on rh04_codigo       = rh20_cargo                ";
  $sSqlRhPessoalMov .= "                                            and rh04_instit       = rh02_instit               ";
  $sSqlRhPessoalMov .= "                     inner join rhregime     on rh30_codreg       = rh02_codreg               ";
  $sSqlRhPessoalMov .= "                                            and rh30_instit       = rh02_instit               ";
  $sSqlRhPessoalMov .= "                     inner join rhcadregime  on rh52_regime       = rh30_regime               ";
  $sSqlRhPessoalMov .= "                                            and rh30_instit       = rh02_instit               ";
  $sSqlRhPessoalMov .= "                     inner join rhlota       on r70_codigo        = rh02_lota                 ";
  $sSqlRhPessoalMov .= "                                            and r70_instit        = rh02_instit               ";
  $sSqlRhPessoalMov .= "                     left join  rhlotaexe    on rh26_codigo       = r70_codigo                ";
  $sSqlRhPessoalMov .= "                                            and rh26_anousu       = rh02_anousu               ";
  $sSqlRhPessoalMov .= "                     left join orcorgao      on o40_orgao         = rh26_orgao                ";
  $sSqlRhPessoalMov .= "                                            and o40_anousu        = rh26_anousu               ";
  $sSqlRhPessoalMov .= "                                            and o40_instit        = rh02_instit               ";     
  $sSqlRhPessoalMov .= "               where rh02_anousu = {$oPost->anofolha}                                         ";
  $sSqlRhPessoalMov .= "                 and rh02_mesusu = {$oPost->mesfolha}                                         ";
  $sSqlRhPessoalMov .= "                 and rh02_instit = ".db_getsession("DB_instit").") as xxx                     ";
  $sSqlRhPessoalMov .= "   {$sWhere}                                                                                  ";
  $sSqlRhPessoalMov .= "   {$sOrder}                                                                                  ";

  $rsSql    = db_query($sSqlRhPessoalMov);
  $iNumRows = pg_numrows($rsSql);
  
  if ($iNumRows == 0) {
    
    $sMsgErro = "Nenhum registro encontrado!";
    $lErro    = true;
  } else {
    
    // ------------- busca url do site do cliente ----------------------
    $sqlDbConfig = " select url from db_config where prefeitura = true ";
    $rsDbConfig  = pg_query($sqlDbConfig);
    $iDbConfig   = pg_numrows($rsDbConfig);
    
    if ($iDbConfig > 0) {
      $oDbConfig = db_utils::fieldsMemory($rsDbConfig, 0);
      $sDbConfig = $oDbConfig->url;
    } else {
      $sDbConfig = "";
    }
    //------------------------------------------------------------------
    
    
    for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
      
      $oRhPessoalMov = db_utils::fieldsMemory($rsSql,$iInd);
      
      fputs($pArquivo,'00'.
            db_formatar($oRhPessoalMov->nome       ,'s',' ',45,'d',0).$oRhPessoalMov->regist.
            db_formatar($oRhPessoalMov->cargo      ,'s',' ',40,'d',0).$oRhPessoalMov->admissao.
            db_formatar($oPost->mesfolha           ,'s',' ',2,'d',0).
            db_formatar($oPost->anofolha           ,'s',' ',2,'d',0).
            db_formatar($oRhPessoalMov->cpf        ,'s',' ',11,'d',0).
            db_formatar($oRhPessoalMov->banco      ,'s',' ',4,'d',0).
            db_formatar($oRhPessoalMov->agencia    ,'s','0',4,'e',0).
            db_formatar($oRhPessoalMov->dvagencia  ,'s','0',2,'e',0).
            db_formatar($oRhPessoalMov->conta      ,'s','0',11,'e',0).''.
            db_formatar($oRhPessoalMov->dvconta    ,'s',' ',2,'e',0).
            db_formatar($oRhPessoalMov->regime     ,'s',' ',16,'d',0).
            db_formatar($oRhPessoalMov->orgao      ,'s',' ',45,'d',0).
            db_formatar(substr($oRhPessoalMov->setor,0,44),'s',' ',44,'d',0).
            db_formatar($iInd+1                    ,'s','0',4,'e',0)."\r\n");
            
      $iMargem = 0;
      if ($oRhPessoalMov->cod_regime != 3 ) {
      
        $sSqlMargem  = "   select sum(r53_valor) as margem                                                            ";
        $sSqlMargem .= "     from gerffx                                                                              ";
        $sSqlMargem .= "    where r53_anousu = {$oPost->anofolha}                                                     ";
        $sSqlMargem .= "      and r53_mesusu = {$oPost->mesfolha}                                                     ";
        $sSqlMargem .= "      and r53_regist = {$oRhPessoalMov->regist}                                               ";
        $sSqlMargem .= "      and r53_rubric in ('0102','0103','0109','0111','0195','0196','0197','0198','0145')      ";
  
        $rsSqlMargem = db_query($sSqlMargem);

        $oMargem     = db_utils::fieldsMemory($rsSqlMargem,0);
        $iMargem     = $oMargem->margem;
      }
        
      $sSqlTipoFolha  = "   select {$sSigla}_rubric as rubica,                                                        ";
      $sSqlTipoFolha .= "          {$sSigla}_quant as quantidade,                                                     ";
      $sSqlTipoFolha .= "          round({$sSigla}_valor,2) as valor,                                                 ";
      $sSqlTipoFolha .= "          {$sSigla}_pd as pd,                                                                ";
      $sSqlTipoFolha .= "          rh27_descr,                                                                        ";
      $sSqlTipoFolha .= "          case                                                                               ";
      $sSqlTipoFolha .= "            when {$sSigla}_rubric < 'R950'                                                   ";
      $sSqlTipoFolha .= "              then 'v'                                                                       ";
      $sSqlTipoFolha .= "            else 'b'                                                                         ";
      $sSqlTipoFolha .= "          end as tipo,                                                                       "; 
      $sSqlTipoFolha .= "          case                                                                               ";
      $sSqlTipoFolha .= "            when rh27_obs like '%PERC%'                                                      "; 
      $sSqlTipoFolha .= "              then 'p'                                                                       ";
      $sSqlTipoFolha .= "            else                                                                             "; 
      $sSqlTipoFolha .= "            case                                                                             ";
      $sSqlTipoFolha .= "              when rh27_obs like '%DIAS%'                                                    ";
      $sSqlTipoFolha .= "                then 'd'                                                                     ";
      $sSqlTipoFolha .= "              else                                                                           ";
      $sSqlTipoFolha .= "              case                                                                           ";
      $sSqlTipoFolha .= "                when rh27_obs like '%UNID%'                                                  "; 
      $sSqlTipoFolha .= "                  then 'u'                                                                   ";
      $sSqlTipoFolha .= "                else ''                                                                      ";
      $sSqlTipoFolha .= "              end                                                                            ";
      $sSqlTipoFolha .= "            end                                                                              ";
      $sSqlTipoFolha .= "          end as perc                                                                        ";
      $sSqlTipoFolha .= "     from {$sTabela}                                                                         ";
      $sSqlTipoFolha .= "          inner join rhrubricas on {$sSigla}_rubric = rh27_rubric                            ";
      $sSqlTipoFolha .= "                               and {$sSigla}_instit = rh27_instit                            ";        
      $sSqlTipoFolha .= "    where {$sSigla}_anousu = {$oPost->anofolha}                                              ";
      $sSqlTipoFolha .= "      and {$sSigla}_mesusu = {$oPost->mesfolha}                                              ";
      $sSqlTipoFolha .= "      and {$sSigla}_regist = {$oRhPessoalMov->regist}                                        ";
      $sSqlTipoFolha .= "      and {$sSigla}_instit = ".db_getsession("DB_instit")."                                  ";
      $sSqlTipoFolha .= " order by {$sSigla}_regist,{$sSigla}_rubric                                                  ";

      $rsSqlTipoFolha    = db_query($sSqlTipoFolha);
      $iNumRowsRubricas  = pg_numrows($rsSqlTipoFolha);
      
      $base_prev      = 0;
      $base_irrf      = 0;
      $base_fgts      = 0;
      $fgts           = 0;
      $bruto          = 0;
      $desc           = 0;
      
      for ($iRubricas = 0; $iRubricas < $iNumRowsRubricas; $iRubricas++) {
      
        $oTipoFolha = db_utils::fieldsMemory($rsSqlTipoFolha, $iRubricas);
        if ($oTipoFolha->tipo == 'v') {
        
          if ($oTipoFolha->perc == 'p') {
          
            $inform = '%';
            $quant  = trim(db_formatar($oTipoFolha->quantidade,'f')).$inform;
          } else if ($oTipoFolha->perc == 'd') {
          
            $inform = 'D';
            $quant  = $oTipoFolha->quantidade.' '.$inform;
          } else if ($oTipoFolha->perc == 'u') {
          
            $inform = 'UN';
            $quant  = $oTipoFolha->quantidade.' '.$inform;
          } else {
          
            $inform = '';
            $quant  = $oTipoFolha->quantidade.' '.$inform;
          }
        
          if ($oTipoFolha->quantidade == 0) {
            $quant = '';
          }
        
          $iValorTipo1 = ($oTipoFolha->pd == 1?$oTipoFolha->valor:0);
          $iValorTipo2 = ($oTipoFolha->pd == 2?$oTipoFolha->valor:0);
          $sTipoFolha  = ' ';
          if ($oTipoFolha->pd == 2) {
            $sTipoFolha  = 'D';   
          } else if ($oTipoFolha->pd == 1) {
            $sTipoFolha  = 'V';
          }
          fputs($pArquivo,'02'.
                $sTipoFolha.
                $oTipoFolha->rubica.
                db_formatar($oTipoFolha->rh27_descr,'s',' ',40,'d',0).
                db_formatar($quant,'s',' ',7,'e',0).
                db_formatar(str_replace(',',''
                                           ,str_replace('.','',trim(db_formatar($iValorTipo1,'f')))),'s','0',9 ,'e',0).
                db_formatar(str_replace(',',''
                                           ,str_replace('.','',trim(db_formatar($iValorTipo2,'f')))),'s','0',9 ,'e',0).
                str_repeat($sMensagemPadrao, 59).'P'."\r\n");
             
          if ($oTipoFolha->pd == 1) {
            $bruto += $oTipoFolha->valor;
          } else if ($oTipoFolha->pd == 2) {
            $desc  += $oTipoFolha->valor;
          }
        } else {
          
          if ($oTipoFolha->rubica == 'R992') {
            $base_prev = $oTipoFolha->valor;
          } else if ($oTipoFolha->rubica == 'R981' || $oTipoFolha->rubica == 'R982' || $oTipoFolha->rubica == 'R983') {
            $base_irrf = $oTipoFolha->valor;
          } else if ($oTipoFolha->rubica == 'R991') {
          
            $base_fgts = $oTipoFolha->valor;
            $fgts      = $oTipoFolha->valor / 100 * 8;
          }
        }
      }
    
      fputs($pArquivo,'MS'.str_pad($mensagem1,64,' ',STR_PAD_RIGHT).str_repeat($sMensagemPadrao,61).'P'."\r\n");
      fputs($pArquivo,'MS'.str_pad($mensagem2,64,' ',STR_PAD_RIGHT).str_repeat($sMensagemPadrao,61).'P'."\r\n");
      fputs($pArquivo,'MS'.str_pad($mensagem3,64,' ',STR_PAD_RIGHT).str_repeat($sMensagemPadrao,61).'P'."\r\n");
      fputs($pArquivo,'MS'.str_pad($mensagem4,64,' ',STR_PAD_RIGHT).str_repeat($sMensagemPadrao,61).'P'."\r\n");
      fputs($pArquivo,'MS'.str_pad($mensagem5,64,' ',STR_PAD_RIGHT).str_repeat($sMensagemPadrao,61).'P'."\r\n");
      
      fputs($pArquivo,'TT'.
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar($bruto,'f')))),'s','0',9 ,'e',0).
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar($desc,'f')))),'s','0',9 ,'e',0).
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar($bruto - $desc,'f')))),'s','0',9 ,'e',0).
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar($base_prev,'f')))),'s','0',9 ,'e',0).
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar($base_fgts,'f')))),'s','0',9 ,'e',0).
            
            db_formatar($oRhPessoalMov->ender,'s',' ',45,'d',0).
            db_formatar($oRhPessoalMov->compl,'s',' ',15,'d',0).
            db_formatar($oRhPessoalMov->numero,'s',' ',6,'e',0).
            db_formatar($oRhPessoalMov->cep,'s',' ',8,'d',0).
            db_formatar($oRhPessoalMov->bairro,'s',' ',25,'d',0).
            db_formatar($oRhPessoalMov->munic,'s',' ',25,'d',0).
            
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar($iMargem/100*30,'f')))),'s','0',11,'e',0).
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar($base_irrf,'f')))),'s','0',11,'e',0).
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar($fgts,'f')))),'s','0',11,'e',0).
            db_formatar(str_replace(',','',str_replace('.','',trim(
            db_formatar(0,'f')))),'s','0',11,'e',0).
            str_repeat($sMensagemPadrao, 11).'PM'."\r\n");
            
        /*Gerar o código de autenticacao e grava na tabela*/
                     
        $rsSeqContraCheque = db_query("select nextval('rhemitecontracheque_rh85_sequencial_seq') as sequencial");
        $oSeqContraCheque  = db_utils::fieldsMemory($rsSeqContraCheque,0);
        $iSequencial       = str_pad($oSeqContraCheque->sequencial,6,'0',STR_PAD_LEFT);
        
        $mes        = $oPost->mesfolha;
        $ano        = $oPost->anofolha;
        $regist     = $oRhPessoalMov->regist;
        
        $iMes       = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $iMatricula = str_pad($regist, 6, '0', STR_PAD_LEFT);
        $iMod1      = db_CalculaDV($iMatricula);
        $iMod2      = db_CalculaDV($iMatricula.$iMod1.$iMes.$ano.$iSequencial); 
           
        $iCodAutent = $iMatricula.$iMod1.$iMes.$iMod2.$ano.$iSequencial;
        
        $clrhemitecontracheque->rh85_sequencial  = $iSequencial;
        $clrhemitecontracheque->rh85_regist      = $regist;
        $clrhemitecontracheque->rh85_anousu      = $ano;
        $clrhemitecontracheque->rh85_mesusu      = $mes;
        $clrhemitecontracheque->rh85_sigla       = substr($sSigla,0,3);
        $clrhemitecontracheque->rh85_codautent   = $iCodAutent;
        $clrhemitecontracheque->rh85_dataemissao = date('Y-m-d',db_getsession('DB_datausu'));
        $clrhemitecontracheque->rh85_horaemissao = db_hora();
        $clrhemitecontracheque->rh85_ip          = db_getsession('DB_ip');
        $clrhemitecontracheque->rh85_externo     = 'false';
      
        $clrhemitecontracheque->incluir($iSequencial);
        
        if ( $clrhemitecontracheque->erro_status == 0 ) {
          
          $sMsgErro = $clrhemitecontracheque->erro_msg; //"Nenhum registro encontrado!";
          $lErro    = true;
        } 
            
            
       fputs($pArquivo,'03'.str_pad("Para Verificar Autenticidade Acesse: ".$sDbConfig,148,' ',STR_PAD_RIGHT).
                            str_pad("Código da Autenticação: ".$iCodAutent, 100, ' ', STR_PAD_LEFT).
             "\r\n");     
      
    }
    
    if (!$lErro) {
      $sMsgErro = "Processo concluido com sucesso!";  
    }
  }
  
  fclose($pArquivo);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script> 
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<form name="form1" method="post" action="">
  <table>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left">
        <table align="center" border="0">
          <tr>
            <td>
              <?

                $clformulariorelpes               = new cl_formulario_rel_pes;

                $clformulariorelpes->js_anomes    = "onchange='js_anomes();'";
                $clformulariorelpes->usalota      = true;                    
                $clformulariorelpes->usaregi      = true;                    
                $clformulariorelpes->usaloca      = true;                   
                $clformulariorelpes->usaorga      = true;                  
              
                $clformulariorelpes->lo1nome      = "cod_ini";             
                $clformulariorelpes->lo2nome      = "cod_fim";             
                $clformulariorelpes->lo3nome      = "lista";                 
              
                $clformulariorelpes->or1nome      = "cod_ini";             
                $clformulariorelpes->or2nome      = "cod_fim";           
                $clformulariorelpes->or3nome      = "lista";               
                $clformulariorelpes->or4nome      = "Orgão";             
              
                $clformulariorelpes->re1nome      = "cod_ini";             
                $clformulariorelpes->re2nome      = "cod_fim";             
                $clformulariorelpes->re3nome      = "lista";             
                
                $clformulariorelpes->tr1nome      = "cod_ini";            
                $clformulariorelpes->tr2nome      = "cod_fim";           
                $clformulariorelpes->tr3nome      = "lista";                 
              
                $clformulariorelpes->tfinome      = "tipofiltro";       
                $clformulariorelpes->strngtipores = "gmlto";               

              
                $clformulariorelpes->tipofol      = true;                
                
                $clformulariorelpes->arr_tipofol  = array("r14"=>"Salário",
                                                          "r48"=>"Complementar",
                                                          "r20"=>"Rescisão",
                                                          "r35"=>"13o. Salário",
                                                          "r22"=>"Adiantamento");

                $clformulariorelpes->complementar = "r48";                   
              
                $clformulariorelpes->trenome      = "tiporesumo";           
                
                $clformulariorelpes->mostord      = true;                
                
                $clformulariorelpes->arr_mostord  = Array("a"=>"Alfabética", "n"=>"Numérica");
              
                $clformulariorelpes->onchpad      = true;          
                $clformulariorelpes->gera_form($iAnoFolha,$iMesFolha);
  
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tr11_mensagempadraotxt?>">
              <?=@$Lr11_mensagempadraotxt?>
            </td>
            <td> 
              <?
                db_input('r11_mensagempadraotxt',4,$Ir11_mensagempadraotxt,true,'text',1,"")
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center">
        <fieldset>
        <legend><b>Mensagem</b></legend>
          <table border="0">
            <tr>
              <td nowrap align="right">
                <b>Linha 1:</b>
              </td>
              <td> 
                <?
                  db_input('mensagem1',64,0,true,'text',1,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap align="right">
                <b>Linha 2:</b>
              </td>
              <td> 
                <?
                  db_input('mensagem2',64,0,true,'text',1,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap align="right">
                <b>Linha 3:</b>
              </td>
              <td> 
                <?
                  db_input('mensagem3',64,0,true,'text',1,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap align="right">
                <b>Linha 4:</b>
              </td>
              <td> 
                <?
                  db_input('mensagem4',64,0,true,'text',1,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap align="right">
                <b>Linha 5:</b>
              </td>
              <td> 
                <?
                  db_input('mensagem5',64,0,true,'text',1,"")
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
        <input  name="processar" id="processar" type="submit" value="Processar" 
                onclick="return js_validadadoscontracheque();" <?=$sDisabled;?>>
      </td>
    </tr>
  </table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?
  if (isset($sMsgErro)) {
    db_msgbox($sMsgErro);
  }
?>
<script>
function js_validadadoscontracheque() {

  var iAnoFolha       = document.form1.anofolha.value;
  var iMesFolha       = document.form1.mesfolha.value;
  var iCodIni         = "";
  var iCodFim         = "";
  var sMensagemPadrao = document.form1.r11_mensagempadraotxt.value;
  
  if (document.form1.cod_ini) {
    var iCodIni = document.form1.cod_ini.value;
  }
  
  if (document.form1.cod_fim) {
    var iCodFim = document.form1.cod_fim.value;
  }
  
  if (iAnoFolha == "") {
    alert("Ano da folha não foi preenchido!");
    return false;
  }

  if (iMesFolha == "") {
    alert("Mes da folha não foi preenchido!");
    return false;
  }
  
  if (iCodIni > iCodFim) {
    alert("Intervalo informado não correspondem!");
    return false;
  }
  
  if (sMensagemPadrao == "") {
  
    var sMsgErro   = "Usuário: \n\n";
        sMsgErro  += " Mensagem padrao não cadastrada! \n";
        sMsgErro  += " Para processar a emissão do contra-cheque, você deve informar uma mensagem padrão,\n";
        sMsgErro  += " no menu de acesso: Pessoal > Procedimentos > Manutenção de Parâmetros > Gerais. \n";
        sMsgErro  += " ou preeencha o campo Expressão padrão contra-cheque gráfica. \n";
        
        alert(sMsgErro);
        return false;
  }
  
  if (document.form1.lista) {
    for (var x = 0; x < document.form1.lista.length; x++) {
      document.form1.lista.options[x].selected = true;
    }
  }
}

function js_anomes() {
  if (document.form1.tipofol.value == "r48") {
    document.form1.submit();
  }
}
<?
  if (!$lErro) {
    if(isset($oPost->processar)){
      echo "js_montarlista('".$sDirArq."#Arquivo gerado em: ".$sDirArq."','form1');";
    }
  } 
?>
</script>
</html>