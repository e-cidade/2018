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
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_POST_VARS,2);

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
db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Importação');
?>

</center>
</body>
<?
$db_erro = false;

$erro_msg = ImportaArquivo($AArquivo);

//exit;

if(!empty($erro_msg)){
  echo "
  <script>
    parent.js_erro('$erro_msg');
  </script>
  ";
}elseif(empty($erro_msg)){
       db_msgbox('Processamento efetuado com sucesso!!');
       echo "<script>parent.db_iframe_bbconverte.hide();parent.location.href='pes2_guaimportaguiabaprev001.php';</script>";
  
}
db_fim_transacao();
//db_redireciona("pes2_guaimportaguiabaprev002.php");

function ImportaArquivo($AArquivo){

//  include("db_anodata.php");

  //// cria um tabela com o numcgm numerico
  $res_convcgm = pg_query("select * from pg_class where relname = 'w_convcgm' and relkind = 'r'");
  if(pg_numrows($res_convcgm ) > 0 ){
    pg_query("drop table w_convcgm");
  } 
  pg_query("create table w_convcgm as 
                   select z01_numcgm,
                          to_number(case when trim(z01_cgccpf) = '' 
                                         then '0' 
                                        else z01_cgccpf 
                                    end,'9999999999999') as cgccpf,
                          z01_nome 
           from cgm");

  pg_query("create index w_convcgm_in on w_convcgm(cgccpf,z01_nome)");


  $res_ipegp = pg_query("select * from pg_class where relname = 'w_ipegp' and relkind = 'r'");
  if(pg_numrows($res_ipegp ) > 0 ){
    pg_query("drop table w_ipegp");
  } 
  pg_query("create table w_ipegp(
                                 numcgm int,
                                 orgao int,
                                 regist int,
               matipe varchar(13), 
               situacao varchar(2),
               nome varchar(40),
               endereco varchar(40),
               cep varchar(8),
               admissao varchar(8),
               dtsituacao varchar(8),
               dtnasc varchar(8),
               sexo int,
               estciv int,
               rg bigint,
               cpf bigint,
               salario float8
               
               )");

  $arquivo = file($AArquivo);

  $tamanho = sizeof($arquivo);

  db_inicio_transacao();

  $total = 0;
  $enc = 0;
  $nenc = 0;

  for($ii=0;$ii<$tamanho;$ii++){
    
    db_atutermometro($ii,$tamanho,'calculo_folha',1);

    $orgao      = trim(substr($arquivo[$ii],0,3));  //2
    $regist     = trim(substr($arquivo[$ii],3,6));  //8
    $matipe     = trim(substr($arquivo[$ii],9,13));  //21
    $situacao   = trim(substr($arquivo[$ii],22,2));  //24
    $nome       = trim(substr($arquivo[$ii],24,32));  //56
    $endereco   = trim(substr($arquivo[$ii],58,38));  //96
    $cep        = trim(substr($arquivo[$ii],96,8));  //103
    $admissao   = trim(substr($arquivo[$ii],104,8));  //111
    $dtsituacao = trim(substr($arquivo[$ii],112,8));  //119
    $dtnasc     = trim(substr($arquivo[$ii],120,8));  //127
    $sexo       = trim(substr($arquivo[$ii],128,1));  //128
    $estciv     = trim(substr($arquivo[$ii],129,1));  //129
    $rg         = trim(substr($arquivo[$ii],130,10));  //139
    $cpf        = trim(substr($arquivo[$ii],140,11));  //150
    $salario    = trim(substr($arquivo[$ii],151,11));  


    $ver_cgm = pg_query("select * from w_convcgm where cgccpf = ".trim($cpf)." and trim(z01_nome) = '".trim($nome)."'");
    if($ver_cgm == false){
        
       db_msgbox('Erro ao consultar cgm');
       echo "<script>parent.db_iframe_bbconverte.hide();parent.location.href='pes2_guaimportaguiabaprev001.php';</script>";
    }
    $numcgm = 0;
    if(pg_numrows($ver_cgm) > 0){
      $numcgm = pg_result($ver_cgm,0,"z01_numcgm");
    }else{
      $res_valcgm = pg_exec("select nextval('cgm_z01_numcgm_seq') as sequencia");
      $numcgm = pg_result($res_valcgm,0,"sequencia");
       
    
      if($estciv == 1){     
        $xestciv = 1;                         
      }elseif($estciv == 2){ 
        $xestciv = 2;                         
      }elseif($estciv == 5){ 
        $xestciv = 3;                         
      }else{
        $xestciv = 4;
      }
   
      $sql_cgm = "insert into cgm (
                                   z01_numcgm        , 
                                   z01_nome          , 
                                   z01_ender         , 
                                   z01_numero        , 
                                   z01_compl         , 
                                   z01_bairro        , 
                                   z01_munic         , 
                                   z01_uf            , 
                                   z01_cep           , 
                                   z01_cxpostal      , 
                                   z01_cadast        , 
                                   z01_telef         , 
                                   z01_ident         , 
                                   z01_login         , 
                                   z01_incest        , 
                                   z01_telcel        , 
                                   z01_email         , 
                                   z01_endcon        , 
                                   z01_numcon        , 
                                   z01_comcon        , 
                                   z01_baicon        , 
                                   z01_muncon        , 
                                   z01_ufcon         , 
                                   z01_cepcon        , 
                                   z01_cxposcon      , 
                                   z01_telcon        , 
                                   z01_celcon        , 
                                   z01_emailc        , 
                                   z01_nacion        , 
                                   z01_estciv        , 
                                   z01_profis        , 
                                   z01_tipcre        , 
                                   z01_cgccpf        , 
                                   z01_fax           , 
                                   z01_nasc          , 
                                   z01_pai           , 
                                   z01_mae           , 
                                   z01_sexo          , 
                                   z01_ultalt        , 
                                   z01_contato       , 
                                   z01_hora          , 
                                   z01_nomefanta     , 
                                   z01_cnh           , 
                                   z01_categoria     , 
                                   z01_dtemissao     , 
                                   z01_dthabilitacao , 
                                   z01_nomecomple    , 
                                   z01_dtvencimento    
                                  ) values (
                                   $numcgm,
                                   '$nome'      ,
                                   '$endereco'   ,
                                   '0'  ,
                                   ''  ,
                                   '' ,
                                   '' ,
                 'RS',
                                   '92500000'  ,
                 null,
                 '2006-12-02',
                                   ''  ,
                                   '$rg'  ,
                                   1,
                                   null ,
                                   ''  ,
                 null,
                                   null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 1,
                 $xestciv,
                 null,
                 0,
                 '$cpf'  ,
                 null,
                 '".substr($dtnasc,0,4)."-".substr($dtnasc,4,2)."-".substr($dtnasc,6,2)."', 
                 null,
                 null,
                 ".($sexo == 1?"'M'":"'F'").",
                 '2006-12-02',
                 null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 null,
                 null
               )";
      $res_cgm = pg_query($sql_cgm);
      if($res_cgm == false){
       db_msgbox('Erro ao inserir dados');
       echo "<script>parent.db_iframe_bbconverte.hide();parent.location.href='pes2_guaimportaguiabaprev001.php';</script>";
      }
      $total_cgm += 1;		       
    }
    $sql_rhipenumcgm = ("select * from rhipenumcgm where rh63_numcgm = $numcgm");
    $res_rhipenumcgm = pg_query($sql_rhipenumcgm);
    if($res_rhipenumcgm == false){
       return "erro ao pesquisar rhipenumcgm   : $sql_rhipenumcgm";
    }elseif(pg_numrows($res_rhipenumcgm) > 0){
       pg_query("update rhipe set rh14_valor = ".$salario."::float8/100 where rh14_sequencia = ".pg_result($res_rhipenumcgm,0,"rh63_sequencia"));
    }else{
      $res_seq_ipe = pg_query("select nextval('rhipe_rh14_sequencia_seq') as seqipe ");
      $seq_ipe = pg_result($res_seq_ipe,0,"seqipe");
      $sql_rhipe = "insert into rhipe (
                                       rh14_sequencia,
               rh14_matipe,
               rh14_dtvinc,
               rh14_estado,
               rh14_dtalt,
               rh14_contrato,
               rh14_valor,
               rh14_instit
               ) values(
                                       $seq_ipe,
                                       '$matipe',
               ".($admissao != '00000000'?"'".substr($admissao,0,4)."-".substr($admissao,4,2)."-".substr($admissao,6,2)."'":'null').",
                                       '$situacao',
               ".($dtsituacao != '00000000'?"'".substr($dtsituacao,0,4)."-".substr($dtsituacao,4,2)."-".substr($dtsituacao,6,2)."'":'null')   .",
               0,
                                       ".$salario."::float8/100,
               ".db_getsession('DB_instit')."
               )";
     $res_rhipe = pg_query($sql_rhipe);
     if($res_rhipe == false){
       return "erro ao incluir no rhipe  : $sql_rhipe";
     }else{
       pg_query("insert into rhipenumcgm values($seq_ipe,$numcgm)");
     }
               

    $sql_ipe =   "insert into w_ipegp(
                                       numcgm    ,
                                       orgao     , 
                                       regist    ,
                                       matipe    ,
                                       situacao  ,
                                       nome      ,
                                       endereco  ,
                                       cep       ,
                                       admissao  ,
                                       dtsituacao,
                                       dtnasc    ,
                                       sexo      ,
                                       estciv    ,
                                       rg        ,
                                       cpf       ,
                                       salario  
               )
                                       values
               (
               $numcgm    ,
                                       $orgao     , 
                                       $regist    ,
                                       '$matipe'  ,
                                       '$situacao',
                                       '$nome'    ,
                                       '$endereco',
                                       '$cep'     ,
                                       '$admissao',
                                       '$dtsituacao',
                                       '$dtnasc'  ,
                                       '$sexo'    ,
                                       $estciv    ,
                                       '$rg'      ,
                                       '$cpf'     ,
                                       ".$salario."::float8/100   
                                       )";
    $res = pg_query($sql_ipe);
    if($res == false){
      return "erro   ".$sql_ipe;
    }

   }
  }
}
  
?>