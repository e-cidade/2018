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

if(!session_id()){
  session_start();
}
require("libs/db_stdlib.php");
//echo "mostra = $mostrahtml e botao = $botao  arg0= $argv[0]  arg1= $argv[1]";
if(isset($mostrahtml) and $mostrahtml== true){
  if($botao==1){
    //processar os alteradas.
    $processartodos = false;
  }else{
    //processar todos.
    $processartodos = true;
  }
}else{
  if($argv[1] == '1'){
    //processar os alteradas.
    $processartodos = false;
  }else{
    //processar todos
    $processartodos = true;
  }
}
//################## log de inicio ####################
// Desabilita tempo maximo de execucao
set_time_limit(0);

// Hora de Inicio do Script
$sHoraInicio = date( "H:i:s" );
// Timestamp para data/Hora
$sTimeStampInicio = date("Ymd_His");
// Verifica se nao foi setado o nome do script
if(!isset($sNomeScript)) {
  $sNomeScript = basename(__FILE__);
} 


// Seta nome do arquivo de Log, caso jÃ¡ nÃ£o exista
if(!defined("DB_ARQUIVO_LOG")) {
  if($processartodos == true){
    $sArquivoLog = "tmp/PROCESSAR_SISS_".$sTimeStampInicio.".log";
  }else{
    $sArquivoLog = "tmp/ATUALIZACAO_SISS_".$sTimeStampInicio.".log";
  }

  define("DB_ARQUIVO_LOG", $sArquivoLog);
}

// Logs...
db_log("", $sArquivoLog,2);
db_log("*** INICIO Script ".$sNomeScript." ***", $sArquivoLog,2);
db_log("", $sArquivoLog,2);
db_log("Arquivo de Log: $sArquivoLog", $sArquivoLog,2);
db_log("", $sArquivoLog,2);


include("dbforms/db_funcoes.php");

$DB_SERVIDOR_SISS  = "localhost";
$DB_BASE_SISS     = "siss_interface";
$DB_PORTA_SISS    = "5432";
$DB_USUARIO_SISS  = "siss_interface";
$DB_SENHA_SISS    = "siss_interface";

if(!($conn2 = @pg_connect("host=$DB_SERVIDOR_SISS dbname=$DB_BASE_SISS port=$DB_PORTA_SISS user=$DB_USUARIO_SISS password=$DB_SENHA_SISS"))) {
  echo "\n Contate com Administrador do Sistema! (Conexão Inválida na base $DB_BASE_SISS.)   <br>Sessão terminada, feche seu navegador!\n";
  exit;
}else{
  if(!isset($mostrahtml)){
    db_log("CONECTADO A BASE $DB_BASE_SISS ", null, 1, true,true);
    db_log("", $sArquivoLog);
  }
}

//######################################################

if(isset($mostrahtml) and $mostrahtml== true){
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");

}else{

  $DB_SERVIDOR = "192.168.0.2";
  $DB_BASE     = "auto_car_20080720";
  $DB_PORTA    = "5432";
  $DB_USUARIO  = "postgres";
  $DB_SENHA    = "";

  if(!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
    echo "Contate com Administrador do Sistema! (Conexão Inválida na $DB_BASE.)   <br>Sessão terminada, feche seu navegador!\n";
    exit;
  }else{
    db_log("CONECTADO A BASE $DB_BASE do ip $DB_SERVIDOR ", null, 1, true,true);
  }

  $sqlinstit = "select codigo from db_config where prefeitura is true";
  $rsInstit  = pg_query($conn ,$sqlinstit);
  $instit    = pg_result($rsInstit,0,0);

  $sqlSessao  = "SELECT fc_startsession()";
  $rsSessao   = pg_query($conn, $sqlSessao) or die("Problema com a sessão");

  $sqlPut = "SELECT fc_putsession('DB_instit',$instit )";
  $rsPut  = pg_query($conn,$sqlPut) or die("Problema com a sessão" );
}


db_putsession("DB_instit",$instit);
db_putsession("DB_datausu", date("Y-m-d"));
db_putsession("DB_id_usuario",1);
db_putsession("DB_anousu",date("Y"));

if(isset($mostrahtml) and $mostrahtml== true){
  ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <?
    echo "<br><center><b>PROCESSAMENTO GERAL</b></center><br>";
  db_criatermometro('termometro2', 'Concluido...', 'blue', 1);
  echo "<br><br><br>";
  echo "<b><center><span id='titulo'></span></center></b><br>";
  db_criatermometro('termometro', 'Concluido...', 'blue', 1);
  db_atutermometro(0,8, 'termometro2');

}


$vir= "";
$whereinscr= "";
$inscricoes="";
$cancelaProcessamento = false;
if($processartodos == false){
  // se for opcao de processar os alteradas...busca dados para atualizar
  $datahoje = date("Y-m-d");

  $sqlinscricao = "
    select q02_inscr from issbase 
    where q02_dtcada = '$datahoje' 
    or q02_ultalt = '$datahoje' 
    or q02_ultalt = '$datahoje';
  ";

  $resultinscricao = pg_query($conn,$sqlinscricao);
  $linhasinscricao = pg_num_rows($resultinscricao);
  if($linhasinscricao > 0){
    for($procalt=0;$procalt< $linhasinscricao;$procalt++){
      db_fieldsmemory($resultinscricao,$procalt);
      $inscricoes .= $vir.$q02_inscr;
      $vir= ",";
    }
    $whereinscr = "where issbase.q02_inscr in($inscricoes)";
  }else{
    if(isset($mostrahtml) and $mostrahtml== true){
      db_msgbox("Nenhum registro incluido ou altereado para esta data.");
      db_log("Nenhum registro incluido ou altereado para esta data. ",$sArquivoLog,2);
      db_log("", $sArquivoLog,2);
      db_log("Final.: " . date( "H:i:s"), $sArquivoLog,2);
      db_log("", $sArquivoLog,2);
      db_log("*** FINAL Script ".$sNomeScript." *** \n\n", $sArquivoLog,2);
      echo "<script>parent.db_iframe_relatorio.hide(); </script>";
      $cancelaProcessamento = true;  //exit;
    }else{
      db_log("Nenhum registro incluido ou altereado para esta data. ",$sArquivoLog);
      db_log("", $sArquivoLog);
      db_log("Final.: " . date( "H:i:s"), $sArquivoLog);
      db_log("", $sArquivoLog);
      db_log("*** FINAL Script ".$sNomeScript." *** \n\n", $sArquivoLog);
      $cancelaProcessamento = true;  //exit;
    }

  }

}

pg_query($conn,'BEGIN');
pg_query($conn2,'BEGIN');
if($cancelaProcessamento == false){


  // ############### PROCESSA CADASTRO DE ATIVIDADES ################
  $sqlAtiv = "
  select distinct q03_ativ ,q03_descr , current_date as dt_exp,
  coalesce ( (select  q81_valexe
              from    tabativ
              left join ativtipo on ativtipo.q80_ativ  = tabativ.q07_ativ
              left join tipcalc  on tipcalc.q81_codigo = ativtipo.q80_tipcal
              left join cadcalc  on cadcalc.q85_codigo = tipcalc.q81_cadcalc
              where cadcalc.q85_var is true 
                and tabativ.q07_ativ = q03_ativ 
              limit 1),0) as aliq
  from ativid
      inner join tabativ  on  tabativ.q07_ativ  = ativid.q03_ativ
      order by q03_ativ 
      ";
  $resultAtiv = pg_query($conn,$sqlAtiv);    
  $linhasAtiv = pg_num_rows($resultAtiv);

  if ($linhasAtiv > 0){
    if(isset($mostrahtml) and $mostrahtml== true){
      db_atutermometro(1,8, 'termometro2');
      echo"<script>document.getElementById('titulo').innerHTML='PROCESSANDO CADASTRO DE ATIVIDADES'; </script>";
      db_log("PROCESSANDO CADASTRO DE ATIVIDADES  total = $linhasAtiv ", $sArquivoLog,2 );
    }else{

      db_log("PROCESSANDO CADASTRO DE ATIVIDADES  total = $linhasAtiv ", $sArquivoLog,0,true,true );
      db_log("", $sArquivoLog);
    }

    
    for ($at=0;$at<$linhasAtiv; $at++) {
      
      if(isset($mostrahtml) and $mostrahtml== true){
        db_atutermometro($at, $linhasAtiv, 'termometro');
      }else{
        $nPercentual = round((($at+1)/$linhasAtiv) * 100 , 2);
        db_log(" {$at} de {$linhasAtiv} .....  \rProcessando {$nPercentual}%...", null, 1, false,false);
      }

      db_fieldsmemory($resultAtiv,$at);

      //verificar se ja existe esta atividade no siss
      $sqlAtiviSiss = "select * from tb_atividades where codativ = $q03_ativ";
      $resultAtivSiss = pg_query($conn2,$sqlAtiviSiss);
      $linhasAtivSiss = pg_num_rows($resultAtivSiss);
      if ($linhasAtivSiss == 0  ){
        $incluiCadAtiv = " Insert into tb_atividades (
                                                     codativ,
                                                     descrativ,
                                                     aliquota ,
                                                     controle ,
                                                     dt_exp)
                                            values   (".dbValida( $q03_ativ,'int').",
                                                      ".dbValida( $q03_descr, 'string').",              
                                                      ".dbValida( $aliq, 'int').",
                                                      false,
                                                      ".dbValida( $dt_exp,'date')."
                                                      )";
 
        $resultCadAtiv = @pg_query($conn2,$incluiCadAtiv) ;
        if($resultCadAtiv==false){
          db_log("Erro: \n sql = $incluiCadAtiv", null, 1);
          pg_query($conn , 'rollback');
          pg_query($conn2, 'rollback');
          exit;
        }else{
          if($processartodos == false){
            db_log("Atualizada Atividade : {$q03_ativ}", $sArquivoLog,2);
          }else{
            db_log("Incluida Atividade : {$q03_ativ}", $sArquivoLog,2);
          }
        }
      }                                              
    }
  }


// ############ PROCESSANDO INSCRIÇÕES ###############

  $inscricaodupla = "";
  $vir= "";
  //echo "<br> inserir dados na tb_i
  //inserir dados na tb_inter_empr
  
  $sqlissbase = "
					select distinct on(issbase.q02_inscr)
					  issbase.q02_inscr  as inscricao,
					  case when db_cgmcgc.z01_cgc is not null then z01_cgc else 
				          case when db_cgmcpf.z01_cpf is not null then z01_cpf else
				          null
				          end
				    end as cpf_cnpj,
					  cgm.z01_nome       as rsocial,
					  ruas.j14_nome      as logradouro,
						issruas.q02_numero as num_imovel,
						issruas.q02_compl  as complemento,
						issruas.z01_cep    as cep,
			      case when issbase.q02_dtinic is null then
                 (select min(q07_datain) from tabativ where q07_inscr = issbase.q02_inscr)
                 else issbase.q02_dtinic
            end as dt_cadastro,
						issbase.q02_dtbaix as dt_baixa,
						cgm.z01_email      as email,
						current_date as dt_exp
          from issbase      
          inner join cgm         on issbase.q02_numcgm    = cgm.z01_numcgm 
          left  join db_cgmcgc   on db_cgmcgc.z01_numcgm  = cgm.z01_numcgm
          left  join db_cgmcpf   on db_cgmcpf.z01_numcgm  = cgm.z01_numcgm
          left  join issruas     on issruas.q02_inscr     = issbase.q02_inscr
          left  join ruas        on issruas.j14_codigo    = ruas.j14_codigo
          $whereinscr
          ";
  
  $resultissbase = pg_query($conn,$sqlissbase);
  $linhasissbase = pg_num_rows($resultissbase);
  if(isset($mostrahtml) and $mostrahtml== true){
    db_atutermometro(1,8, 'termometro2');
    echo"<script>document.getElementById('titulo').innerHTML='PROCESSANDO EMPRESAS'; </script>";
    db_log("PROCESSANDO EMPRESAS  total = $linhasissbase ", $sArquivoLog,2 );
  }else{

    db_log("PROCESSANDO EMPRESAS  total = $linhasissbase ", $sArquivoLog );
    db_log("", $sArquivoLog);
  }

  if($linhasissbase > 0 ){

    for($is=0;$is<$linhasissbase;$is++){
      if(isset($mostrahtml) and $mostrahtml== true){

        db_atutermometro($is, $linhasissbase, 'termometro');
      }else{
        $nPercentual = round((($is+1)/$linhasissbase) * 100 , 2);
        db_log(" {$is} de {$linhasissbase} .....  \rProcessando {$nPercentual}%...", null, 1, false,false);
      }
      db_fieldsmemory($resultissbase, $is);

      //verifica se ja processou hoje...
      $sqlverifica =" select * from tb_contribuintes where ccm = $inscricao and dt_exp = current_date";
      $resultverifica = pg_query($conn2,$sqlverifica);
      $linhasverifica = pg_num_rows($resultverifica);
      if($linhasverifica > 0   ){
        if(isset($mostrahtml) and $mostrahtml== true){
          db_msgbox("Empresa ja incluida para esta data.");
          db_log("Empresa ja incluida para esta data.",$sArquivoLog,2);
          db_log("", $sArquivoLog,2);
          db_log("Final.: " . date( "H:i:s"), $sArquivoLog,2);
          db_log("", $sArquivoLog,2);
          db_log("*** FINAL Script ".$sNomeScript." *** \n\n", $sArquivoLog,2);
          echo "<script>parent.db_iframe_relatorio.hide(); </script>";
          pg_query($conn , 'rollback');
          pg_query($conn2, 'rollback');
          exit;
        }else{
          db_log("Empresa ja incluida para esta data.",$sArquivoLog);
          db_log("", $sArquivoLog);
          db_log("Final.: " . date( "H:i:s"), $sArquivoLog);
          db_log("", $sArquivoLog);
          db_log("*** FINAL Script ".$sNomeScript." *** \n\n", $sArquivoLog);
          pg_query($conn , 'rollback');
          pg_query($conn2, 'rollback');
          exit;
        }

      }
      // busca valor fix
      $val_fixo = "";
      $sqlValfix = "select q01_valor from isscalc
                    inner join varfix    on q33_inscr = q01_inscr
                    inner join varfixval on q33_codigo = q34_codigo
                    where q33_inscr = $inscricao limit 1 ";
      $resultValfix = pg_query($conn,$sqlValfix );
      $linhasValfix = pg_num_rows($resultValfix );
      if($linhasValfix > 0){
        db_fieldsmemory($resultValfix,0);
        $val_fixo = $q01_valor;
      }

      if($cpf_cnpj == ""){
        $cpf_cnpj = "000000000000";
      }
      $rsocial = addslashes($rsocial);
      $endereco = $logradouro.",".$num_imovel;
      $incluiempresa = "
        INSERT INTO tb_contribuintes (
												ccm,        
												cnpj ,      
												rsocial ,   
												endereco ,  
												complemento,
												cep     ,   
												dt_cadastro,
												dt_baixa  , 
												email  ,    
												val_fixo ,  
												controle ,  
												dt_exp  
                                    )
              VALUES
              ( $inscricao,
							  ".dbValida( $cpf_cnpj,'string').",
							  ".dbValida( $rsocial,'string').",
								".dbValida( $endereco,'string').",
								".dbValida( $complemento,'string').",
								".dbValida( $cep,'string').",
								".dbValida( $dt_cadastro,'date').",
								".dbValida( $dt_baixa,'date').",
								".dbValida( $email,'string').",
								".dbValida( $val_fixo,'int').",
								false,	
                ".dbValida( $dt_exp,'date')."
								)";
      // db_log("inclui $is  = $inscricao", null, 1, true,true);
      //die($incluiempresa);
      $resultinclui = @pg_query($conn2,$incluiempresa) ;
      if($resultinclui==false){
        db_log("Erro: \n sql = $incluiempresa", null, 1);
        pg_query($conn , 'rollback');
        pg_query($conn2, 'rollback');
        exit;
      }else{
        if($processartodos == false){
          db_log("Atualizada inscrição : {$inscricao}", $sArquivoLog,2);
        }else{
          db_log("Incluida inscrição : {$inscricao}", $sArquivoLog,2);
        }

      }

     //############## PROCESSANDO INSCRICOES - ATIVIDADES  ##############
     
     $sqlTavativ = " select q07_ativ,q07_datain,q07_datafi from tabativ where q07_inscr = $inscricao ";
     $resultTabativ = pg_query($conn , $sqlTavativ);
     $linhasTabativ = pg_num_rows($resultTabativ);
     if($linhasTabativ > 0){
     
       for($ta=0;$ta<$linhasTabativ;$ta++){
         db_fieldsmemory($resultTabativ, $ta);
         
         //verificar se ja tem esta atividade gravada no SISS
         $sqlAtiv_contrib = " select * from tb_ativ_contrib where ccm = $inscricao  and codativ = $q07_ativ ";
         $resultAtiv_contrib = pg_query($conn2 , $sqlAtiv_contrib );
         $linhasAtiv_contrib = pg_num_rows($resultAtiv_contrib);
         if($linhasAtiv_contrib == 0){
           $incluiAtiv_contrib = "
                                 insert into tb_ativ_contrib ( ccm,     
                                                               codativ ,
                                                               iniativ ,
                                                               fimativ ,
                                                               controle,
                                                               dt_exp 
                                                             )
                                                      values ( $inscricao,
                                                               $q07_ativ,
                                                               ".dbValida( $q07_datain,'date').",
                                                               ".dbValida( $q07_datafi,'date').",
                                                               false,
                                                               ".dbValida( $dt_exp,'date')."
                                                             )";
           $resultIncluiAtiv_contrib = pg_query($conn2, $incluiAtiv_contrib);
           
           if($resultIncluiAtiv_contrib==false){
             db_log("Erro: \n sql = $incluiAtiv_contrib", null, 1);
             pg_query($conn,'rollback');
             pg_query($conn2,'rollback');
             exit;
           }else{
             if($processartodos == false){
               db_log("Atualizada Atividade para inscrição : {$inscricao}, atividade = $q07_ativ", $sArquivoLog,2,true,true);
             }else{
               db_log("Incluida Atividade para inscrição : {$inscricao}, atividade = $q07_ativ", $sArquivoLog,2,true,true);
             }
           }


         }
       }
     }
     // Processa aidof
     
     $sqlAidof = "select  q09_nota, y08_dtlanc,y08_notain,y08_notafi from aidof inner join notasiss on q09_codigo = y08_nota where y08_inscr = $inscricao";
     $resultAidof = pg_query($conn, $sqlAidof);
     $linhasAidof = pg_num_rows($resultAidof);
     if($linhasAidof > 0){
       for($ad=0;$ad<$linhasAidof;$ad++){
         db_fieldsmemory($resultAidof,$ad);
         // verificar se ja existe no SISS
         $sqlVerAidofSiss = "select * from tb_contrib_aidof 
                                      where ccm     = $inscricao 
                                        and num_ini = $y08_notain
                                        and num_fim = $y08_notafi
                                        and serie   =".dbValida( $q09_nota,'string') ." " ; 
         $resultAidofSiss = pg_query($conn2,$sqlVerAidofSiss);
         $linhasAidofSiss = pg_num_rows($resultAidofSiss);
         if($linhasAidofSiss ==0){
           
           $incluiAidof = " Insert into tb_contrib_aidof (ccm,num_ini,num_fim,serie,dt_lib,controle,dt_exp)
                                                 values  ($inscricao,
                                                          ".dbValida( $y08_notain,'int').",
                                                          ".dbValida( $y08_notafi,'int').",
                                                          ".dbValida( $q09_nota,'string').",
                                                          ".dbValida( $y08_dtlanc,'date').",
                                                          false,
                                                          ".dbValida( $dt_exp,'date')."
                                                          )";
           $resultIncAidof = pg_query($conn2,$incluiAidof);
           if($resultIncAidof==false){
             db_log("Erro: \n sql = $incluiAidof", null, 1);
             pg_query($conn,'rollback');
             pg_query($conn2,'rollback');
             exit;
           }else{
             if($processartodos == false){
               db_log("Atualizada Aidof para inscrição : {$inscricao}", $sArquivoLog,2,true,true);
             }else{
               db_log("Incluida Aidof para inscrição : {$inscricao}", $sArquivoLog,2,true,true);
             }
           }
            
 
         }
       }
     }
     // processar o simplesssss
       $sqlSimples = "
                     select z01_cgccpf as cnpj,q23_mesusu,q23_anousu,sum((q23_vlrprinc+q23_vlrmul+q23_vlrjur)) as valor_simples 
                     from arreinscr 
                     inner join issvar                 on k00_numpre = q05_numpre 
                     inner join issarqsimplesregissvar on q68_issvar = q05_codigo 
                     inner join issarqsimplesreg       on q68_issarqsimplesreg = q23_sequencial
                     inner join issbase                on q02_inscr  = k00_inscr
                     inner join cgm                    on issbase.q02_numcgm    = cgm.z01_numcgm
                     where k00_inscr = {$inscricao} and z01_cgccpf is not null
                     group by z01_cgccpf,q23_mesusu,q23_anousu";
       $resultSimples = pg_query($conn , $sqlSimples );
       $linhasSimples = pg_num_rows($resultSimples);
       if($linhasSimples >0){
          for($si=0;$si<$linhasSimples;$si++){
            db_fieldsmemory($resultSimples,$si);
            if($cnpj != "" ){
              // verificar se ja existe no siss
              $sqlVerSimplesSiss = "select * from tb_simples 
                                       where cnpj= '".$cnpj."' 
                                         and mes = $q23_mesusu
                                         and ano = $q23_anousu ";
              $resultSimplesSiss = pg_query($conn2,$sqlVerSimplesSiss);
              $linhasSimplesSiss = pg_num_rows($resultSimplesSiss);
              if($linhasSimplesSiss == 0 ){
                
                $IncluiSimples = "insert into tb_simples (cnpj,mes,ano,valor,controle,dt_exp)
                                              values     (".dbValida( $cnpj,'string').",
                                                          ".dbValida( $q23_mesusu,'int').",
                                                          ".dbValida( $q23_anousu,'int').",
                                                          ".dbValida( $valor_simples,'int').",
                                                          false,
                                                          ".dbValida( $dt_exp,'date')."
                                                         )";
                $resultIncSimples = pg_query($conn2,$IncluiSimples);
                if($resultIncSimples==false){
                  db_log("Erro: \n sql = $IncluiSimples", null, 1);
                  pg_query($conn,'rollback');
                  pg_query($conn2,'rollback');
                  exit;
                }else{
                  if($processartodos == false){
                    db_log("Atualizado Simples para inscrição : {$inscricao} , CPNJ:{$cnpj}", $sArquivoLog,2,true,true);
                  }else{
                    db_log("Incluido Simples para inscrição : {$inscricao} , CPNJ:{$cnpj}", $sArquivoLog,2,true,true);
                  }
                }
              }
            }
          }
       }// fim simples


    }// for issbase
  }// if issbase

  //############ PROCESSANDO ESCRITORIOS ###############
  $sqlEscritorio = " select distinct q10_numcgm,z01_nome,z01_cgccpf,z01_munic,z01_uf,z01_telef,z01_email,current_date as dt_exp 
                     from escrito 
                     inner join cgm on q10_numcgm = z01_numcgm";
  $resultEscritorio = pg_query($conn , $sqlEscritorio);
  $linhasEscritorio = pg_num_rows($resultEscritorio);
  if($linhasEscritorio > 0){


    if(isset($mostrahtml) and $mostrahtml== true){
      db_atutermometro(1,8, 'termometro2');
      echo"<script>document.getElementById('titulo').innerHTML='PROCESSANDO EMPRESAS'; </script>";
      db_log("PROCESSANDO ESCRITÓRIOS  total = $linhasEscritorio ", $sArquivoLog,2 );
    }else{
  
      db_log("PROCESSANDO ESCRITÓRIOS  total = $linhasEscritorio ", $sArquivoLog );
      db_log("", $sArquivoLog);
    }

    for($e=0;$e<$linhasEscritorio;$e++){
      db_fieldsmemory($resultEscritorio,$e);
      if(isset($mostrahtml) and $mostrahtml== true){

        db_atutermometro($e, $linhasEscritorio, 'termometro');
      }else{
        $nPercentual = round((($e+1)/$linhasEscritorio) * 100 , 2);
        db_log(" {$e} de {$linhasEscritorio} .....  \rProcessando {$nPercentual}%...", null, 1, false,false);
      }

      // verifica se ja tem escritorio em SISS
      
      $sqlEscrSiss = "select * from tb_escritorios where codescr = $q10_numcgm ";
      $resultEscrSiss = pg_query($conn2,$sqlEscrSiss);
      $linhasEscrSiss = pg_num_rows($resultEscrSiss);
      if($linhasEscrSiss == 0){
        if( $z01_cgccpf==""  ){
          $z01_cgccpf = "0000000000000";
        }
        $incluirEscritorio = "insert into tb_escritorios ( codescr, 
                                                           rsocial ,
                                                           cnpj    ,
                                                           cidade  ,
                                                           uf      ,
                                                           telefone,
                                                           email   ,
                                                           controle,
                                                           dt_exp  
                                                         )
                                                  values ( $q10_numcgm,
                                                           ".dbValida( $z01_nome,'string').",
                                                           ".dbValida( $z01_cgccpf,'string').",
                                                           ".dbValida( $z01_munic,'string').",
                                                           ".dbValida( $z01_uf,'string').",
                                                           ".dbValida( $z01_telef,'string').",
                                                           ".dbValida( $z01_email,'string').",
                                                           false,
                                                           ".dbValida( $dt_exp,'date')."
                                                         ) ";
         $resultIncEscr = pg_query($conn2,$incluirEscritorio );

         if($resultIncEscr==false){
           db_log("Erro: \n sql = $incluirEscritorio", null, 1);
           pg_query($conn,'rollback');
           pg_query($conn2,'rollback');
           exit;
         }else{
           if($processartodos == false){
             db_log("Atualizado escritório codigo : {$q10_numcgm}", $sArquivoLog,2,true,true);
           }else{
             db_log("Incluido escritório codigo : {$q10_numcgm}", $sArquivoLog,2,true,true);
           }
         }
         // PROCESSAR AS INSCRIÇÕES PARA CADA ESCRITORIO

         $sqlEscrInsc = "select * from escrito where q10_numcgm = $q10_numcgm ";
         $resultEscrInsc = pg_query($conn, $sqlEscrInsc);
         $linhasEscrInsc = pg_num_rows($resultEscrInsc);
         if($linhasEscrInsc > 0){
           for($ei=0; $ei<$linhasEscrInsc; $ei++){
             db_fieldsmemory($resultEscrInsc ,$ei);
             // verificar se ja esta cadastrado no SISS  
             $sqlEscrInscSiss = " select * from tb_escrit_contrib where ccm = $q10_inscr and codescr= $q10_numcgm ";
             $resultEscrInscSiss = pg_query($conn2,$sqlEscrInscSiss);
             $linhasEscrInscSiss = pg_num_rows($resultEscrInscSiss);
             if($linhasEscrInscSiss == 0){
               $incluiEscrInsc = "
                                   insert into tb_escrit_contrib (ccm,codescr,controle,dt_exp )
                                                       values    ( $q10_inscr, 
                                                                   $q10_numcgm,
                                                                   false, 
                                                                   ".dbValida( $dt_exp,'date')."
                                                                 )";
               $resultIncEscrInsc = pg_query($conn2, $incluiEscrInsc);
               if($resultIncEscrInsc==false){
                 db_log("Erro: \n sql = $incluiEscrInsc", null, 1);
                 pg_query($conn,'rollback');
                 pg_query($conn2,'rollback');
                 exit;
               }else{
                 if($processartodos == false){
                   db_log("Atualizado inscricao ({$q10_inscr}) para escritório ({$q10_numcgm})", $sArquivoLog,2,true,true);
                 }else{
                   db_log("Incluido inscricao ({$q10_inscr}) para escritório ({$q10_numcgm})", $sArquivoLog,2,true,true);
                 }
               }
             }
           }
         }// fim if inscricao por  escrito
      }
    }
  }// fim do if dos escritorios
}
// fecha a parte 1

// ################################# PARTE 2 ###################################

include("classes/db_issvar_classe.php");
include("classes/db_issvarnotas_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_arrecad_classe.php");
$clissvar = new cl_issvar;
$vir = "";
$sqlerro = false;
$inscricaoSemIssbase = "";
$sqlBuscaBoleto = "select * from tb_controle_boletos where controle is false order by ccm,documento ";
$rsBuscaBoleto = pg_query($conn2,$sqlBuscaBoleto); 
$linhasBuscaBoleto = pg_num_rows($rsBuscaBoleto);
if(isset($mostrahtml) and $mostrahtml== true){
    db_atutermometro(8,9, 'termometro2');
      echo"<script>document.getElementById('titulo').innerHTML='PROCESSANDO ... gerar issvar apartir de boletos'; </script>";
}else{
    db_log("PROCESSANDO ... gerar issvar apartir de boletos - total = $linhasBuscaBoleto ", $sArquivoLog,0,true,true);
      db_log("", $sArquivoLog);
}

if($linhasBuscaBoleto > 0){
  // For nos Boletos do GISS
  $ccm_ant   = null;
  // Controla Processamento Inscricao caso nao exista na ISSBASE
  $lProcessaInscricao = true; 
  for($bb=0;$bb<$linhasBuscaBoleto; $bb++){
    db_fieldsmemory($rsBuscaBoleto,$bb);
    if(isset($mostrahtml) and $mostrahtml== true){
      db_atutermometro($bb, $linhasBuscaBoleto, 'termometro');
    }else{
      $nPercentual = round((($bb+1)/$linhasBuscaBoleto) * 100 , 2);
      db_log("{$bb} de {$linhasBuscaBoleto} \rProcessando {$nPercentual}%...", null, 1, false,false);
    }

    if($ccm <> $ccm_ant) {
      // comeca outro processamento

      // Log de Inicio do Processamento da Inscricao
      db_log("", $sArquivoLog, 2, true,true);
      db_log("Processando Inscricao $ccm", $sArquivoLog,2, true,true);
      db_log("", $sArquivoLog, 2, true,true);
      $lProcessaInscricao = true;
      $ccm_ant   = $ccm;
    }

    if($lProcessaInscricao == false) {
      continue;
    }

    //validar se a inscrição esta na issbase
    $sqlValidaIssbase = "select q02_inscr from issbase where q02_inscr = $ccm ";
    $rsValidaIssbase  = pg_query($conn, $sqlValidaIssbase);
    $linhasValidaIssbase = pg_num_rows($rsValidaIssbase);

    if($linhasValidaIssbase == 0) {
      $lProcessaInscricao = false;
      db_log("Inscricao $ccm_ant Nao encontrada no Cadastro Municipal", $sArquivoLog,2, true,true);
      db_log("Inscricao $ccm_ant Nao Processada", $sArquivoLog,2, true,true);
    }
    if($linhasValidaIssbase > 0){

      if($documento < 8000000){
         db_log("Boleto não processada, num_documento deve ser menor que 8000000 (documento = $documento)", $sArquivoLog,2, true,true); 
      }else{


      //busca as inscrições que estão em aberto e não for um levantamento
      $sqlBuscarAbertos = "select distinct q05_codigo as cod_issvar from issvar 
                           inner join arrecad  on q05_numpre  = arrecad.k00_numpre
                                              and q05_numpar  = arrecad.k00_numpar 
                           inner join arreinscr on q05_numpre = arreinscr.k00_numpre
                           left join issvarlev on q18_codigo  = q05_codigo 
                           where q18_codigo is null  
                             and k00_inscr = $ccm
                             and q05_ano = $ano
                             and q05_mes = $mes ";
      $rsBuscarAbertos = pg_query($conn , $sqlBuscarAbertos);
      $linhasBuscarAbertos = pg_num_rows($rsBuscarAbertos);
      $naoProcessaBoletoSimples = false;
      if($linhasBuscarAbertos > 0 ){

        //Exclui os issvar encontrados em aberto
        for($ev=0; $ev<$linhasBuscarAbertos; $ev++){
          db_fieldsmemory($rsBuscarAbertos , $ev);
          // validar se o issvar não est ano simples

          $sqlSimples = "select q17_nomearq,k15_codbco,k15_codage,q23_codbco,q23_codage,q17_nroremessa 
            from issarqsimplesregissvar 
            inner join issarqsimplesreg   on q68_issarqsimplesreg = q23_sequencial 
            inner join issarqsimples      on q23_issarqsimples    = q17_sequencial
            left  join issarqsimplesregdisbanco on q44_issarqsimplesreg = q23_sequencial
            left  join disbanco           on disbanco.idret = q44_disbanco
            where q68_issvar = $cod_issvar ";
          $rsSimples = pg_query($sqlSimples);								
          $linhasSimples = pg_num_rows($rsSimples);
          if($linhasSimples > 0 ){
            db_fieldsmemory($rsSimples , 0);
            db_log("ISS não processado ... Boleto giss documento = $documento importado para simples (issvar = $cod_issvar, Remessa = $q17_nroremessa, Arquivo = $q17_nomearq).", $sArquivoLog, 2, true,true);
            $naoProcessaBoletoSimples = true;
          }else{
            $naoProcessaBoletoSimples = false;
            db_log("Anulando ISS em aberto (issvar = $cod_issvar).", $sArquivoLog, 2, true,true);
            $clissvar->excluir_issvar($cod_issvar,"0");
          }


        }
      }
      // gera issvar complementar para todos
      // setar todas as  variaveis..
      if(	$naoProcessaBoletoSimples == false){

        db_log("Processando Boleto giss. Documento = $documento, Data Vencimento = $dt_venc, Mês/Ano = $mes/$ano, Valor = $valor", $sArquivoLog, 2, true,true);


        $vt= array();
        $clissvar->q05_numpre = $documento;
        $clissvar->q05_numpar = $mes;
        $clissvar->q05_valor  = $valor;
        $clissvar->q05_ano    = $ano;
        $clissvar->q05_mes    = $mes;
        $clissvar->q05_histor = "Importado do Siss";
        $clissvar->q05_aliq   = '0';
        $clissvar->q05_bruto  = '0';
        $clissvar->q05_vlrinf ="null";

        $clissvar-> incluir_issvar_complementar ($vt,$ccm);
        if($clissvar->erro_status=="0"){
          $sqlerro = true;
          db_log("Erro no processamento do boleto", $sArquivoLog, 0, true,true);
          pg_query($conn,'rollback');
          pg_query($conn2,'rollback');
          exit;
        }

        // gerar db_reciboweb
        if($sqlerro == false){
          $sqlIncluiRecibo = "insert into db_reciboweb 
            (k99_numpre,k99_numpar,k99_numpre_n,k99_codbco,k99_codage,k99_numbco,k99_desconto,k99_tipo,k99_origem) 
            values 
            ($documento,$mes,$documento,0,'','',0,10,4)";
          $rsIncluiRecibo = pg_query($sqlIncluiRecibo);															
          if($rsIncluiRecibo==false){
            $sqlerro = true;
            db_log("Erro: \n sql = $sqlIncluiRecibo", null, 0, true,true);
            pg_query($conn,'rollback');
            pg_query($conn2,'rollback');
            exit;
          }

          $sqlIncluiRecibocodbar  = "insert into recibocodbar (k00_numpre,k00_codbar,k00_linhadigitavel) values($documento,'".$codigo_barra."','".$codigo_barra."')";
          $rsIncluiRecibocodbar   = pg_query($sqlIncluiRecibocodbar);
          if($rsIncluiRecibocodbar==false){
            $sqlerro = true;
            db_log("Erro: \n sql = $sqlIncluiRecibocodbar", null, 1, true,true);
            pg_query($conn,'rollback');
            pg_query($conn2,'rollback');
            exit;
          }
        }
        if($sqlerro == false){
          db_log("Boleto processado com sucesso!...(issvar = ".$clissvar->q05_codigo.", numpre = $documento, parcela = $mes)", $sArquivoLog, 2, true,true);

          // guarda  as informações dos registros que foram gerados issvar, para alterar o campo controle no giss
          $arrayGiss[] = array('ccm'    => $ccm,
              'documento'   => $documento,
              'mes' => $mes,
              'ano' => $ano
              );
        }

      }
    }

  }
  }// do for
  if(($linhasValidaIssbase > 0)  and ($documento > 8000000 )){
    foreach ($arrayGiss as $key => $value) {
      // cada array maior
     //  echo " \n\n Chave: $key; Valor: $value";
      $wherecontrole = "";
      $and="";
      foreach ($value as $key2 => $value2) {
        // cada linha do array
       //  echo " \n Chave: $key2  Valor: $value2";
        $wherecontrole .= " " .$and." " .$key2." = ".$value2  ;
        $and = "and";
      }
      $sqlAlteraControle = "update tb_controle_boletos set controle = 't' where $wherecontrole ";
      $rsAlteraControle  = pg_query($conn2,$sqlAlteraControle);
      if($rsAlteraControle ==false){
        db_log("Erro: \n sql = $sqlAlteraControle", null, 1, true,true);
        pg_query($conn,'rollback');
        pg_query($conn2,'rollback');
        exit;
      }


    }
  }

}
echo "\n OK \n";

pg_query($conn,'commit');
pg_query($conn2,'commit');

//db_log("Inicio: $sHoraInicio", $sArquivoLog);
db_log("Final.: " . date( "H:i:s"), $sArquivoLog);
db_log("\n *** FINAL Script ".$sNomeScript." *** \n\n", $sArquivoLog);

function dbValida($valor,$tipo){
  $aValorDefault = array('int'    => "0",
  'date'   => "null",
  'string' => "null" );
  if ($valor != '') {
    if($tipo == 'int'){
      return $valor;
    }else{
      $valor = addslashes($valor);
      $valor = "'".$valor."'";
      return $valor;
    }
     
  }else{
    return $aValorDefault[$tipo];
  }
}


function dbValidaNumero($string){
  $tamanho = strlen($string);
  $string2 = "";
  for($tm=0;$tm<$tamanho;$tm++){
    $letra = $string{$tm};
    // echo "<br>letra = $letra";
    if($letra=='0' or $letra=='1' or $letra=='2' or $letra=='3' or $letra=='4' or
    $letra=='5' or $letra=='6' or $letra=='7' or $letra=='8' or $letra=='9' ){
      $string2 .= $letra;
      // echo "<br>palavra =  $string2";
    }
  }
  return $string2;
}
//db_atutermometro(8,8, 'termometro2');

function db_log($sLog="", $sArquivo="", $iTipo=0, $lLogDataHora=true, $lQuebraAntes=true) {
  //
  $aDataHora = getdate();

  $sQuebraAntes = $lQuebraAntes?"\n":"";


  if($lLogDataHora) {
    $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s", $sQuebraAntes,
    $aDataHora["mday"], $aDataHora["mon"], $aDataHora["year"],
    $aDataHora["hours"], $aDataHora["minutes"], $aDataHora["seconds"],
    $sLog);
  } else {
    $sOutputLog = sprintf("%s%s", $sQuebraAntes, $sLog);
  }


  // Se habilitado saida na tela...
  if($iTipo==0 or $iTipo==1) {
    echo $sOutputLog;
  }

  // Se habilitado saida para arquivo...
  if($iTipo==0 or $iTipo==2) {
    if(!empty($sArquivo)) {
      $fd=fopen($sArquivo, "a+");
      if($fd) {
        fwrite($fd, $sOutputLog);
        fclose($fd);
      }
      //system("echo '$sOutputLog' >> $sArquivo");
    }
  }

  return $aDataHora;
}
?>