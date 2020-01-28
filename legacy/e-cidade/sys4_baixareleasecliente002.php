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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css"e
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
</body>
</html>

<?
$atu_completa=false;
$aborta=0;
umask(000);
if(!isset($DB_CMD_PSQL)) {
        db_msgbox("É necessário configurar a variável DB_CMD_PSQL no libs/db_conn.php!");
              $aborta=1;
              exit;
}

$base=db_getsession("DB_NBASE");
$ipbase="$DB_SERVIDOR";
$porta="$DB_PORTA";
$psql="$DB_CMD_PSQL";


$sql    = "select db30_codversao,db30_codrelease from db_versao order by db30_codver desc limit 1";
$result = pg_exec($sql);
$numrows= pg_numrows($result);

$db30_codversao = pg_result($result,0,0);
$db30_codrelease= pg_result($result,0,1);

$releaseatual="2.".$db30_codversao.".".$db30_codrelease."";

$db30_codrelease= pg_result($result,0,1)+1;
$release_nova="2.$db30_codversao.$db30_codrelease";
$release="dbportal-2.".$db30_codversao.".".$db30_codrelease."-linux.tar.bz2";

$verifica=getcwd()."/release/";
$verifica=`cd $verifica;find . -name $release`;
 if($verifica==''){
    db_msgbox("Release $release_nova não disponível.") ;
    $aborta=1;
 }


db_criatermometro('termometro','Concluido...','blue',1);
db_atutermometro(2,100,'termometro');
if ($numrows==0){
   #echo "\n Não existe registro na tabela db_versão. \n";
    $aborta=1;
}


if ($aborta<>1){  
db_atutermometro(4,100,'termometro'); 
db_atutermometro(6,100,'termometro');
     $sql    = "select db21_codcli from db_config where prefeitura=true";
     $result = pg_exec($sql);
     $numrows= pg_numrows($result);
     if ($numrows==0){
          #db_msgbox("Verificar registro na tabela db_config.");
          $aborta=2;
           #exit;
        }
     if ($aborta<>2){
           db_atutermometro(10,100,'termometro');
           $db21_codcli = pg_result($result,0,0);
           $sql    = "update db_config set db21_ativo=3 where prefeitura=true and db21_codcli=$db21_codcli";
           $result = pg_exec($sql);

            #constantes
            $diretorio=getcwd()."/release";
            $release="$diretorio/$release";
            $releasenova="$diretorio/dbportal-2.".$db30_codversao.".".$db30_codrelease."";

            $dir_origem=getcwd();
            
           #limpa diretorio tmp do dbportal
            $tmp=getcwd()."/tmp";
            $executa=`cd $tmp;find . -name "rp*.pdf" -exec rm -f {} \;`;       
           
  #echo "\n Atualizando release $release_nova ! \n \n";
    if (file_exists($release)){
        #echo"removendo diretorio dbportal-2.".$db30_codversao.".".$db30_codrelease."";
        $log="$diretorio/logs/log01_rm_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt";
        $dbportal="dbportal-2.".$db30_codversao.".".$db30_codrelease."";
        $executa=`cd $diretorio;rm -rf $dbportal 2>$log`;

     #descompacta atualização
     #echo"\n DESCOMPACTANDO dbportal-2.".$db30_codversao.".".$db30_codrelease.".linux.tar.bz2 \n";
       $compacta="dbportal-2.".$db30_codversao.".".$db30_codrelease."-linux.tar.bz2";
       $log="$diretorio/logs/log02_descompacta_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt";
       $executa=`cd $diretorio;tar jxvf $compacta 2>$log`;
       $executa=`cd $diretorio;chmod -R 777 $dbportal`;
       $erro = file_get_contents("$log");
       if ($erro<>''){
         #  db_msgbox("Erro ao descompactar o arquivo.Atualização abortada!");
          $aborta=3;
        #exit;
        }

#backup do dbportal2
#echo"\n BACKUP DO DIRETÓRIO DBPORTAL2! \n";

if ($aborta<>3){
  db_atutermometro(20,100,'termometro');
  $backup="Backup_anterior_dbportal-2.".$db30_codversao.".".$db30_codrelease.".tar.bz2";
  $log="$diretorio/logs/log03_back_dbportal2_antes_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt";
  $executa=`cd ..;tar jcvf $backup  dbportal2 2>$log`;
  $erro = file_get_contents("$log");
   if ($erro<>''){
      #db_msgbox(" Erro ao compactar o arquivo dbportal2_antes_dbportal-2.".$db30_codversao.".".$db30_codrelease.".tar.bz2.Atualização abortada!");
       $sql    = "update db_config set db21_ativo=1 where prefeitura=true and db21_codcli=$db21_codcli";
       $result = pg_exec($sql);
       $executa=`cd ..;rm -f $backup`;
       $aborta=4;
       #exit;
      }

if ($aborta<>4){
  db_atutermometro(30,100,'termometro');
  #begin;
  #echo"\n GERANDO ARQUIVO TXT. \n";
  $transacao="begin;";

  $fd = fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt","w");
  fputs($fd,$transacao . "\n" );
  fclose($fd);

#descompacta menus
  $log="$diretorio/logs/log04_descompactamenus_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt";
  $executa=`cd $releasenova;bunzip2 menus8.sql.bz2 2>$log`;
  $erro = file_get_contents("$log");
   if ($erro<>''){
       #db_msgbox("Erro ao descompactar o menus.Atualização abortada!");
       $sql    = "update db_config set db21_ativo=1 where prefeitura=true and db21_codcli=$db21_codcli";
       $result = pg_exec($sql);
       $aborta=5;
    # exit;
   }

if ($aborta<>5){  
  db_atutermometro(40,100,'termometro');
 #menus
 $menus=`find $releasenova -name menus8.sql`;
  if ($menus==''){
      #db_msgbox("Não foi encontrado o arquivo menus8.sql.Atualização abortada!");
      $sql    = "update db_config set db21_ativo=1 where prefeitura=true and db21_codcli=db21_codcli";
      $result = pg_exec($sql);
      $aborta=6;
     #exit;
   }

if ($aborta<>6){
  db_atutermometro(50,100,'termometro');
 $fd = fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt","a");
 fputs($fd,$menus );
 fclose($fd);

#dbversaoant
   $dir="$releasenova/sql";
   $versaoant=`find $dir -name dbversaoant.sql` ;

   if ($versaoant==''){
    #dbmsgbox("Não foi encontrado o arquivo db_versaoant.Atualização abortada!");
    $sql    = "update db_config set db21_ativo=1 where prefeitura=true and db21_codcli=$db21_codcli";
    $result = pg_exec($sql);
    $aborta=7;
    # exit;
    }

if ($aborta<>7){
  db_atutermometro(60,100,'termometro');
 $arquivo="$releasenova/sql/dbversaoant.sql";
 $erro = file_get_contents("$arquivo");
  if (trim($erro)==''){
 #  db_msgbox("Não existe registro no arquivo dbversaoant.sql.");
    $sql    = "update db_config set db21_ativo=1 where prefeitura=true and db21_codcli=$db21_codcli";
    $result = pg_exec($sql);
    $aborta=8;
 #  exit;
    }

if ($aborta<>8){  
db_atutermometro(70,100,'termometro');  
 $fd = fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt","a");
 fputs($fd,$versaoant );
 fclose($fd);

#executa funcoes
 $dir="$releasenova/funcoes8/";
 $funcoes=`find $dir -name  *.sql` ;
 $fd = fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt","a");
 fputs($fd,$funcoes);
 fclose($fd);
#monta arquivo txt com dados para gerar o arquivo sql
#echo"\n GERA ARQUIVO SQL a partir do arquivo txt \n";
 $dir="$releasenova/sql/";
 $sql=`find $dir -name  $db21_codcli.sql` ;
 if ($sql==''){
     #  db_msgbox(" Não foi encontrado o arquivo $db21_codcli.sql. Atualização abortada! ");
       $sql    = "update db_config set db21_ativo=1 where prefeitura=true and db21_codcli=$db21_codcli";
       $result = pg_exec($sql);
       $aborta=9;
     #  exit;
      }

if ($aborta<>9){
 db_atutermometro(80,100,'termometro');
 $codcli="$releasenova/sql/$db21_codcli.sql";
 $fd=file_get_contents("$codcli");
 if (trim($fd)<>''){
 #    echo"Existe sql para rodar no banco de dados";
     $fd = fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt","a");
     fputs($fd,$sql );
     fclose($fd);
    }

#transacao
$transacao="commit;";
$fd = fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt","a");
fputs($fd,$transacao );
fclose($fd);
#Prepara arquivo sql para executar no banco de dados
$var="\i ";
$fd = fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".sql","w");
fclose($fd);
$fd = fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt","r");
while (!feof($fd)){
   $buffer=fgets($fd,4096);
 #  echo "SQL:$buffer";
   if (trim($buffer)=="begin;"){
       $fd2= fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".sql","a");
       fputs($fd2,$buffer );
       continue;
      }
   if (trim($buffer)=="commit;"){
       $fd2= fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".sql","a");
       fputs($fd2,$buffer );
       continue;
      }
      $fd2= fopen("$diretorio/logs/atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".sql","a");
      fputs($fd2, $var.$buffer );
}
fclose($fd2);
fclose($fd);

#echo"\n EXECUTA ATUALIZAÇÃO NO BANCO DE DADOS. \n";
$atualiza="atualiza_dbportal-2.".$db30_codversao.".".$db30_codrelease.".sql";
$log="$diretorio/logs/log05_banco_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt";
$executa=`cd $diretorio/logs; $psql $base -Upostgres -h $ipbase -p $porta -f $atualiza 2>$log`;
$erro=file_get_contents("$log");
  if ($erro<>''){
   #db_msgbox(" Problema na atualização do banco de dados,verificar o log $log. Atualização abortada!");
    $sql    = "update db_config set db21_ativo=1 where prefeitura=true and db21_codcli=$db21_codcli";
    $result = pg_exec($sql);
    $aborta=10;
   #  exit;
  }

if ($aborta<>10){  
  db_atutermometro(90,100,'termometro');
 #copia fontes dbportal2 
 #echo"\n ATUALIZA OS FONTES DA RELEASE 2-".$db30_codversao.".".$db30_codrelease." NO DIRETÓRIO DBPORTAL2 \n";
 $dir="$releasenova/dbportal2/";
 $db=getcwd()."/";
 $executa=`cd $releasenova;cp *.pct $dir`;
 $log="$diretorio/logs/log06_fontes_dbportal-2.".$db30_codversao.".".$db30_codrelease.".txt";
 $executa=`cd $dir; cp -rf *  $db 2>$log`;
 $erro = file_get_contents("$log");
 if (trim($erro)<>''){
    db_msgbox("Atenção.Não foi possível atualizar os fontes provavelmente devido a permissões no diretório do dbportal2.") ;

  }

$dbpref=getcwd();
$verificadbpref=`cd $dbpref; cd .. ; find . -name dbpref`;
  if ($verificadbpref==''){
      db_msgbox("Atenção.Não foi possível atualizar os fontes do dbpref, pois não existe o diretório dbpref.") ;
      }


  if ($verificadbpref<>''){

  #copia fontes dbpref
  #echo"\n ATUALIZA OS FONTES DA RELEASE 2-".$db30_codversao.".".$db30_codrelease." NO DIRETÓRIO DBPREF \n";
  $dir="$releasenova/dbpref";
  $executa=`cd $releasenova;cp *.pct $dir`;
  $log="$diretorio/logs/log07_fontes_dbpref-2.".$db30_codversao.".".$db30_codrelease.".txt";
  $executa=`cd $dir;cp -rf *  ../../../../dbpref/ 2>$log`;
  $erro = file_get_contents("$log");
  if (trim($erro)<>''){
       db_msgbox("Atenção.Não foi possível atualizar os fontes no diretório do dbpref.Entre em contato com a DBseller.") ;
     }
                    
}

#echo"\n ALTERANDO ARQUIVO DB_ACESSA \n";

$dolar="$";
$db_versao="db_fonte_codversao";
$db_release="db_fonte_codrelease";
$db_acessa = "
<?
$dolar$db_versao='$db30_codversao'; 
$dolar$db_release='$db30_codrelease'; 
?> ";
$arquivo=fopen("libs/db_acessa.php","w");
fputs($arquivo,$db_acessa);
 fclose($arquivo);
 $sql    = "update db_config set db21_ativo=1 where prefeitura=true and db21_codcli=$db21_codcli";
 $result = pg_exec($sql);
 $atu_completa=true;
db_atutermometro(99,100,'termometro');
}


#erro=1
}
#erro=2
}
#erro=3
}
#erro=4
}
#erro=5
}
#erro=6
}
#erro=7
}
#erro=8
}
#erro=9
}
#erro=10
}

if ($atu_completa=="true"){
db_msgbox("ATUALIZAÇÃO DA RELEASE dbportal-2.".$db30_codversao.".".$db30_codrelease." EFETUADA.");
db_atutermometro(99,100,'termometro');
}


if ($aborta==2){
db_msgbox("Verificar os registros na tabela db_config.Atualização abortada! Entre em contato com a DBseller.");

}

if ($aborta==3){
db_msgbox("Erro ao descompactar o arquivo.Atualização abortada! Entre em contato com a DBseller");
}

if ($aborta==4){
db_msgbox(" Erro ao compactar o arquivo dbportal2_antes_dbportal-2.".$db30_codversao.".".$db30_codrelease.".tar.bz2.Atualização abortada!");
}

if ($aborta==5){
db_msgbox("Erro ao descompactar o menus.Atualização abortada! Entre em contato com a DBseller.");
}

if ($aborta==6){
db_msgbox("Não foi encontrado o arquivo menus8.sql.Atualização abortada! Entre em contato com a DBseller.");
}

if ($aborta==7){
db_msgbox("Não foi encontrado o arquivo db_versaoant.Atualização abortada! Entre em contato com a DBseller.");
}

if ($aborta==8){
db_msgbox("Não existe registro no arquivo dbversaoant.sql.Atualização abortada! Entre em contato com a DBseller.");
}

if ($aborta==9){
db_msgbox(" Não foi encontrado o arquivo $db21_codcli.sql. Atualização abortada! Entre em contato com a DBseller. ");
}

if ($aborta==10){
 db_msgbox(" Problema na atualização do banco de dados,verificar o log $log.Atualização abortada! Entre em contato com a DBseller");
}

?>