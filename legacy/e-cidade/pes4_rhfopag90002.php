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

include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);
//parse_str($HTTP_SERVER_VARS['HTTP_USER_AGENT']);
$e_linux = strpos(strtolower($HTTP_USER_AGENT),'linux') ;

if($e_linux > 0){
  $troca_linha = "\n";
}else{
  $troca_linha = "\r\n";
}
global $cfpess,$subpes, $db21_codcli ;

$subpes = db_anofolha().'/'.db_mesfolha();

db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_")); 

db_inicio_transacao();

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
<center>
<? 
db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Geracao do Arquivo de Remessa PASEP');
?>

</center>
</body>
<?

global $db_config;
db_selectmax("db_config","select cgc,email, db21_codcli , cgc from db_config where codigo = ".db_getsession("DB_instit"));

global $d08_cgc,$d08_email; 
global $fopag_dtpago,
       $fopag_geracao,
       $fopag_convenio,
       $fopag_agencia_controle,
       $fopag_dv_agencia_controle,
       $fopag_agencia_deposito,
       $fopag_dv_agencia_deposito,
       $fopag_cc_deposito,
       $fopag_dv_cc_deposito,
       $fopag_sequen,
       $subpes;

$d08_cgc    = $db_config[0]["cgc"];
$d08_email  = $db_config[0]["email"];


$db_erro = false;
$sqlerro = false;
$nomearq = "/tmp/FPSF900"; 
gera_pasep($nomearq);

//exit;
if($sqlerro == false){
  echo "
  <script>
    parent.js_detectaarquivo('$nomearq');
  </script>
  ";
}else{
  echo "
  <script>
    parent.js_erro('$erro_msg');
  </script>
  ";
}
//echo "<BR> antes do fim db_fim_transacao()";
//flush();
db_fim_transacao();
//flush();
db_redireciona("pes4_rhfopag90001.php");

function gera_pasep($nomearq){

global $d08_cgc,$d08_email; 
global $fopag_dtpago,
       $fopag_geracao,
       $fopag_convenio,
       $fopag_agencia_controle,
       $fopag_dv_agencia_controle,
       $fopag_agencia_deposito,
       $fopag_dv_agencia_deposito,
       $fopag_cc_deposito,
       $fopag_dv_cc_deposito,
       $fopag_sequen,
       $subpes,
       $troca_linha
       ;
       
       $arquivo = fopen($nomearq,"w");

      // **********************************
      // * HEADER
      // **********************************
      $lin  =  "1";                                                  // FIXO
      $lin .=  "FPSF900";                                            // FIXO
      $lin .=  db_strtran( db_dtoc( $fopag_geracao ), "-", "" );     // DATA DA GERACAO
      $lin .=  $d08_cgc;			                                       //- CGC DA ENTIDADE
      $lin .=  db_str($fopag_sequen,6,0,"0");                        //- SEQUENCIA DO ARQUIVO
      $lin .=  db_str($fopag_agencia_controle,4,0,"0");
      $lin .=  db_str($fopag_dv_agencia_controle,1,0,"0");
      $lin .=  db_strtran( db_dtoc( $fopag_dtpago ), "-", "" );      // DATA DO PAGAMEWNTO
      $lin .=  db_str($fopag_convenio,6,0,"0");
      $lin .=  "1";
      $lin .=  db_str($fopag_agencia_deposito,4,0,"0");
      $lin .=  db_str($fopag_dv_agencia_deposito,1,0,"0");
      $lin .=  db_str($fopag_cc_deposito,11,0,"0");
      $lin .=  db_str($fopag_dv_cc_deposito,1,0,"0");
      $lin .=  "00000000000000";
      $lin .=  bb_space(18);
      $lin .=  str_pad( $d08_email, 80 );
      $lin .=  bb_space(43);
      fputs($arquivo,$lin.$troca_linha);


      //  ********************************
      //  * PARTICIPANTE
      //  ********************************
      $total_registros = 0;
      
      
      $campos_pessoal  = "rh02_anousu as r01_anousu, 
                          rh02_anousu as r01_mesusu, 
                          rh01_regist as r01_regist,
                          rh01_numcgm as r01_numcgm, 
                          rh16_pis      as r01_pis,
                          rh05_recis    as r01_recis,
                          rh30_vinculo  as r01_tpvinc,
                          rh30_vinculo ";

                      
      $condicaoaux = " and lower(rh30_vinculo) = 'a' and (rh16_pis is not null and trim(rh16_pis) <> '') and rh05_recis is null order by rh16_pis";
		  $sql = "select cgm.*,".$campos_pessoal." from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes 
                       left join rhpesdoc      on rhpesdoc.rh16_regist        = rhpessoalmov.rh02_regist 
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
											                   and rhregime.rh30_instit = rhpessoalmov.rh02_instit
                       ".bb_condicaosubpes("rh02_").$condicaoaux ;
      $Ipes = 0;
      global $pessoal;
      db_selectmax("pessoal", $sql);
//echo "<BR> ".$sql;exit;      
      while($Ipes < count($pessoal)){
         $pis = $pessoal[$Ipes]["r01_pis"];
         $tem_pis_igual = false;
         while($Ipes < count($pessoal) && $pis == $pessoal[$Ipes]["r01_pis"]){
               
            if(!$tem_pis_igual){
               $total_registros += 1;
               $lin  =  "2";
               $lin .=  substr(str_pad($pessoal[$Ipes]["r01_pis"],11),0,11);
               $lin .=  substr(str_pad(trim($pessoal[$Ipes]["z01_nome"]),50),0,50);
               $lin .=  substr(db_str($pessoal[$Ipes]["r01_regist"],15,0,"0"),0,15);
               $lin .=  substr(str_pad(trim( $pessoal[$Ipes]["z01_ender"]),50),0,50);
               $lin .=  substr(db_str($pessoal[$Ipes]["z01_numero"],5,0,"0"),0,5);
               $lin .=  bb_space(15);
               $lin .=  substr(str_pad(trim( $pessoal[$Ipes]["z01_bairro"]),30),0,30);
               $lin .=  substr(str_pad(trim( $pessoal[$Ipes]["z01_munic"]),30),0,30);
               $lin .=  substr(str_pad($pessoal[$Ipes]["z01_uf"],2),0,2);
               $lin .=  substr(str_pad($pessoal[$Ipes]["z01_cep"],8),0,8);
               $lin .=  bb_space(11);
               fputs($arquivo,$lin.$troca_linha);

               $tem_pis_igual = true;
            }
            $Ipes++;
         }
      }
      $lin  = "9";
      $lin .=  bb_space(221);
      $lin .=  db_str($total_registros,6,0,"0");
      fputs($arquivo,$lin.$troca_linha);
      fclose($arquivo);
      
}

?>