<?php
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

  set_time_limit(0);
  
//************************************************/
  $dbname  = "auto_gua_20070411";
  $dbhost  = "192.168.0.42";
  $dbport  = "5432";
//***********************************************/

  $conn = pg_connect("dbname=$dbname user=postgres port=$dbport host=$dbhost") or die('ERRO AO CONECTAR NA BASE DE DADOS !!');
	
  pg_query("BEGIN;");

  pg_query($conn, " delete * from corempagemov ");

  $sql = "select corrente.k12_id,
                 corrente.k12_data,
                 corrente.k12_autent,
                 empagemov.e81_codmov
          from corrente 
               inner join coremp    on coremp.k12_id     = corrente.k12_id   and
                                       coremp.k12_data   = corrente.k12_data and 
                                       coremp.k12_autent = corrente.k12_autent
               inner join empord    on empord.e82_codord = coremp.k12_codord
               inner join empagemov on empagemov.e81_codmov = empord.e82_codmov
         where round(corrente.k12_valor, 2) = round(empagemov.e81_valor, 2)";

  $resultado = pg_query($conn,$sql);

  if (!$resultado){
       $erro = true;
  } else {
       $erro = false;
  }

  if ($erro==false){
       for($i=0; $i < pg_numrows($resultado); $i++){
            $sql_insert = "insert into corempagemov (k12_sequencial,
                                                     k12_id, 
                                                     k12_data, 
                                                     k12_autent, 
                                                     k12_codmov) 
                                             values (nextval('corempagemov_k12_sequencial_seq'),".
                                                     pg_result($resultado,$i,"k12_id")    .",'".
                                                     pg_result($resultado,$i,"k12_data")  ."',".
                                                     pg_result($resultado,$i,"k12_autent")."," .
                                                     pg_result($resultado,$i,"e81_codmov").")";
            $res_insert = pg_query($conn,$sql_insert);                                                       

            if (!$res_insert){
                 $erro = true;
                 break;
            } 

            $contador = $i;
            $contador++;

            echo "ID     = ".pg_result($resultado,$i,"k12_id")."\n";
            echo "DATA   = ".pg_result($resultado,$i,"k12_data")."\n";
            echo "AUTENT = ".pg_result($resultado,$i,"k12_autent")."\n";
            echo "CODMOV = ".pg_result($resultado,$i,"e81_codmov")."\n\n";

            echo "Registros inseridos ".$contador."\n\n";
       }
  }

  if ($erro==true){
	     pg_query("ROLLBACK;");
       echo "Processamento Cancelado!!\n";
  }else{
	     pg_query("COMMIT;");
       echo "Processamento Efetuado com Sucesso!!\n";
  }
?>