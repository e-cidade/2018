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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
//require("libs/db_conecta.php");
require("libs/db_conn.php");
include("classes/db_eduinfotable_classe.php");


//temporário
//if(!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
if(!($conn = @pg_connect("host='127.0.0.1' dbname='otimizar' port='5432' user='postgres' password=''"))) {
  echo "Contate com Administrador do Sistema! (Conexão Inválida.)   <br>Sessão terminada, feche seu navegador!\n";
  session_destroy();
  exit;
}


$cleduinfotable = new cl_eduinfotable;
$result_table = $cleduinfotable->sql_record( $cleduinfotable->sql_query(null,"*",null,"e200_c_tipo='S'") );

$erro = false;
for ( $x=0; $x<$cleduinfotable->numrows; $x++ ){
     db_fieldsmemory( $result_table, $x );
     $sql = "select * from pg_class where relname = '".$e200_c_tabela."' ";
     $result = pg_query( $sql );
     if( pg_numrows( $result ) == 0 ){
          echo "\nTabela não encontrada em 'pg_class' [$e200_c_tabela]\n";
          $erro = true;
          break;
     }
}

if( $erro == false ){
    //cria arquivo tmp
    $fd = fopen('/tmp/educacao.txt','w');

    for ( $x=0; $x<$cleduinfotable->numrows; $x++ ){
          db_fieldsmemory( $result_table, $x );

          //seleciona tabela e colunas
          fputs( $fd, "\nTABELA|$e200_c_tabela" );
          $sql = "select * from $e200_c_tabela";
          $result = pg_query( $sql );
          
          //pega colunas
          fputs( $fd, "\nCOLUNAS|" );
          $separator="";
          $i = pg_num_fields($result);
          for ($j = 0; $j < $i; $j++) {
               $fieldname = pg_field_name($result, $j);
               fputs( $fd, $separator.$fieldname );
               $separator="|";
          }//fim for colunas

          //pega dados
          $i = pg_num_rows($result);
          for ($j = 0; $j < $i; $j++) {
               fputs( $fd, "\nDADOS|" );
               $separator="";
               $row=pg_fetch_row($result,$j);
               for($k=0; $k < count($row); $k++) {
                    fputs( $fd, $separator.$row[$k] );
                    $separator="|";
               }
          }// fim for dados
    }// fim for table
    fclose($fd);
}

?>