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

// Para garantir que nao houve erros em outros itens
if($sqlerro==false) {

  //PERMISSÕES DE EMPENHOS";
  include("classes/db_db_permemp_classe.php");
  include("classes/db_db_usupermemp_classe.php");

  $clpermemp    = new cl_db_permemp;
  $clusupermemp = new cl_db_usupermemp;
  // se tem em 2007
  $sqlorigem = "select db20_codperm from db_permemp where db20_anousu =  $anoorigem ";
  $resultorigem = db_query($sqlorigem);
  $linhasorigem = pg_num_rows($resultorigem);

  if ($linhasorigem > 0) {
    for ($pe=0; $pe<$linhasorigem; $pe++) {
      db_fieldsmemory($resultorigem, $pe);
      db_atutermometro($pe, $linhasorigem, 'termometroitem', 1, $sMensagemTermometroItem);

      // se tem em 2008
      $sqldestino = "select * from db_permemp where db20_anousu = $anodestino and db20_codperm = ".$db20_codperm ;
      $resultdestino = db_query($sqldestino);
      $linhasdestino = pg_num_rows($resultdestino);
      if ($linhasdestino==0) {
        //se não tiver dados em 2008 pode incluir
        
        $sqlpermemp = "
          select db20_orgao,
          db20_unidade,
          db20_funcao,
          db20_subfuncao,
          db20_programa,
          db20_projativ,
          db20_codele,
          db20_codigo,
          db20_tipoperm,
          db21_id_usuario
          from db_permemp
          inner join db_usupermemp on db21_codperm = db20_codperm
          inner join orcorgao on o40_anousu = ".$anodestino." and o40_orgao = db20_orgao
          where db20_anousu = ".$anoorigem." and db20_codperm = ".$db20_codperm." and db20_tipoperm is not null";
        $resultpermemp = db_query($sqlpermemp);
        $linhaspermemp = pg_num_rows($resultpermemp);
        if ($linhaspermemp>0) {
          db_fieldsmemory($resultpermemp,0);
          $clpermemp->db20_anousu    = $anodestino;
          $clpermemp->db20_orgao     = $db20_orgao;
          $clpermemp->db20_unidade   = $db20_unidade;
          $clpermemp->db20_funcao    = $db20_funcao;
          $clpermemp->db20_subfuncao = $db20_subfuncao;
          $clpermemp->db20_programa  = $db20_programa;
          $clpermemp->db20_projativ  = $db20_projativ;
          $clpermemp->db20_codele    = $db20_codele;
          $clpermemp->db20_codigo    = $db20_codigo;
          $clpermemp->db20_tipoperm  = $db20_tipoperm;
          $clpermemp->incluir(null);
          if ($clpermemp->erro_status==0) {
            $sqlerro   = true;
            $erro_msg .= $clpermemp->erro_msg;
            break;
          }

          $clusupermemp->db21_codperm    = $clpermemp->db20_codperm;
          $clusupermemp->db21_id_usuario = $db21_id_usuario;
          $clusupermemp->incluir($clpermemp->db20_codperm,$db21_id_usuario);
          if ($clusupermemp->erro_status==0) {
            $sqlerro   = true;
            $erro_msg .= $clusupermemp->erro_msg;
            break;
          }
        }
        if ($sqlerro==false) {
          //echo "...PROCESSADO COM SUCESSO.";
        } else {
          $sqlerro = true;
          //echo "<br>Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
        }
        
        // akiiiiiiiii
      }
      
    }
  } else {
    // não tem dados em 2007
    $cldb_viradaitemlog->c35_log = "Não existem Permissão de empenho para o exercicio $anoorigem";
    $cldb_viradaitemlog->c35_codarq        = 883;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status==0) {
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }
}

?>