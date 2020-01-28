<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
if ($sqlerro==false) {
  
  // RESTOS A PAGAR
  $sqldelete = "delete from empresto where e91_anousu = $anodestino";
  $resultdelete = db_query($sqldelete);

  $sqlorigem = "select * from empresto where e91_anousu =   $anoorigem limit 1";
  $resultorigem = db_query($sqlorigem);
  $linhasorigem = pg_num_rows($resultorigem);
  
  $sqldestino = "select * from empresto where e91_anousu = {$anodestino} limit 1";
  $resultdestino = db_query($sqldestino);
  $linhasdestino = pg_num_rows($resultdestino);
  
  if (($linhasorigem > 0) && ($linhasdestino == 0 )) {
    include("classes/db_empresto_classe.php");
    $clempresto = new cl_empresto;
    $sqlemp  = "  select distinct on (e60_numemp) ";
    $sqlemp .= "         e60_numemp, ";
    $sqlemp .= "         e64_codele, ";
    $sqlemp .= "         round(vlremp,2)::float8 as e60_vlremp, ";
    $sqlemp .= "         round(coalesce(vlranu,0),2)::float8 as e60_vlranu, ";
    $sqlemp .= "         round(coalesce(vlrliq,0),2)::float8 as e60_vlrliq, ";
    $sqlemp .= "         round(coalesce(vlrpag,0),2)::float8 as e60_vlrpag, ";
    $sqlemp .= "         o58_codigo, ";
    $sqlemp .= "         e60_instit ";
    $sqlemp .= "    from empempenho ";
    $sqlemp .= "         inner join orcdotacao  on o58_anousu = e60_anousu and o58_coddot = e60_coddot ";
    $sqlemp .= "         inner join empelemento on e60_numemp = e64_numemp ";
    $sqlemp .= "         inner join (select c75_numemp, ";
    $sqlemp .= "                            round(sum(case when c53_tipo in (10) then round(c70_valor,2) end ),2)    as vlremp, ";
    $sqlemp .= "                            round(sum(case when c53_tipo in (11) then round(c70_valor,2) end ),2)    as vlranu, ";
    $sqlemp .= "                            round(sum(case when c53_tipo in (20) then round(c70_valor,2) ";
    $sqlemp .= "                                           when c53_tipo in (21) then round(c70_valor,2)*-1 end ),2) as vlrliq, ";
    $sqlemp .= "                            round(sum(case when c53_tipo in (30) then round(c70_valor,2) ";
    $sqlemp .= "                                           when c53_tipo in (31) then round(c70_valor,2)*-1 end ),2)    as vlrpag ";
    $sqlemp .= "                       from conlancamemp ";
    $sqlemp .= "                            inner join conlancam    on c70_codlan = c75_codlan ";
    $sqlemp .= "                            inner join conlancamdoc on c70_codlan = c71_codlan ";
    $sqlemp .= "                            inner join conhistdoc   on c53_coddoc = c71_coddoc ";
    $sqlemp .= "                      where c53_tipo in (10, 11,20, 21, 30, 31) ";
    $sqlemp .= "                        and c70_anousu = {$anoorigem} ";
    $sqlemp .= "                        and c71_data < '{$anodestino}-01-01' ";
    $sqlemp .= "                   group by c75_numemp) as vlremp on vlremp.c75_numemp = e60_numemp ";
    $sqlemp .= "   where round(round(round(vlremp,2) - round(coalesce(vlranu,0),2),2)::float8 - round(coalesce(vlrpag,0),2)::float8,2) > 0 ";
    $sqlemp .= "     and e60_anousu = {$anoorigem} ";
    
    $resultemp = db_query($sqlemp);
    $linhasemp = pg_num_rows($resultemp);
    for ($e=0; $e<$linhasemp; $e++) {
      db_fieldsmemory($resultemp,$e);
      db_atutermometro($e, $linhasemp, 'termometroitem', 1, $sMensagemTermometroItem);
      $sqlemp2  = "select substr(p.c60_estrut,1,7) as c60_estrut, "; 
      $sqlemp2 .= "       p.c60_descr, ";
      $sqlemp2 .= "       conplano.c60_estrut ";
      $sqlemp2 .= "  from contrans ";
      $sqlemp2 .= "       inner join contranslan     on c46_seqtrans    = c45_seqtrans ";
      $sqlemp2 .= "       inner join conhistdoc      on c45_coddoc      = c53_coddoc ";
      $sqlemp2 .= "       inner join contranslr      on c46_seqtranslan = c47_seqtranslan ";
      $sqlemp2 .= "       inner join conplanoreduz   on c61_anousu      = c45_anousu ";
      $sqlemp2 .= "                                 and c61_reduz       = c47_debito ";
      $sqlemp2 .= "                                 and c61_instit      = c45_instit ";
      $sqlemp2 .= "       inner join conplano        on c60_codcon      = c61_codcon ";
      $sqlemp2 .= "                                 and c60_anousu      = c61_anousu ";
      $sqlemp2 .= "       inner join conplanoreduz r on r.c61_anousu    = c45_anousu ";
      $sqlemp2 .= "                                 and r.c61_reduz     = c47_credito ";
      $sqlemp2 .= "                                 and r.c61_instit    = c45_instit ";
      $sqlemp2 .= "       inner join conplano p      on p.c60_codcon    = r.c61_codcon ";
      $sqlemp2 .= "                                 and p.c60_anousu    = r.c61_anousu ";
			if (USE_PCASP) {
			
        $sqlemp2 .= " inner join conplanoconplanoorcamento on c72_conplano = conplano.c60_codcon ";
        $sqlemp2 .= "                                     and c72_anousu   = conplano.c60_anousu";
        $sqlemp2 .= " inner join conplanoorcamento         on c72_conplanoorcamento = conplanoorcamento.c60_codcon ";
        $sqlemp2 .= "                                     and c72_anousu   = conplanoorcamento.c60_anousu";
        $sqlemp2 .= " inner join conplanoorcamentoanalitica on conplanoorcamento.c60_codcon = conplanoorcamentoanalitica.c61_codcon";
        $sqlemp2 .= "                                     and conplanoorcamento.c60_anousu  = conplanoorcamentoanalitica.c61_anousu";
        $sqlemp2 .= "                                     and conplanoorcamentoanalitica.c61_instit = c45_instit";
			
			}
      $sqlemp2 .= " where c45_instit = {$e60_instit} ";
      $sqlemp2 .= "   and c53_tipo in (20) ";
      $sqlemp2 .= "   and c45_anousu = {$anoorigem} ";
			if (USE_PCASP) {
        $sqlemp2 .= "   and conplanoorcamento.c60_codcon = {$e64_codele} ;";
			} else { 
        $sqlemp2 .= "   and conplano.c60_codcon = {$e64_codele} ;";
		  }

      //die($sqlemp2);
      $resultemp2 = db_query($sqlemp2);
      $linhasemp2 = pg_num_rows($resultemp2);
			if ($linhasemp2 == 0) {

        $cldb_viradaitemlog->c35_log           = "Empenho sem tipo correspondente -> {$e60_numemp} Institui��o: {$e60_instit} Codcon: {$e64_codele}";
        $cldb_viradaitemlog->c35_codarq        = 816;
        $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
        $cldb_viradaitemlog->c35_data          = date("Y-m-d");
        $cldb_viradaitemlog->c35_hora          = date("H:i");
        $cldb_viradaitemlog->incluir(null);
        if ($cldb_viradaitemlog->erro_status==0) {
          $sqlerro   = true;
          $erro_msg .= $cldb_viradaitemlog->erro_msg;
          break;
        }
        
      } else {
        db_fieldsmemory($resultemp2,0);
        $sqlemp3 = " select e90_codigo from emprestotipo where e90_estrut like '".$c60_estrut."%'";
        $resultemp3 = db_query($sqlemp3);
        $linhasemp3 = pg_num_rows($resultemp3);
        
        if ($linhasemp3 > 0) {
          db_fieldsmemory($resultemp3,0);
          
          $clempresto->e91_anousu    = $anodestino;
          $clempresto->e91_numemp    = $e60_numemp;
          $clempresto->e91_vlremp    = $e60_vlremp;
          $clempresto->e91_vlranu    = $e60_vlranu;
          $clempresto->e91_vlrliq    = $e60_vlrliq;
          $clempresto->e91_vlrpag    = $e60_vlrpag;
          $clempresto->e91_elemento  = "$c60_estrut";
          $clempresto->e91_recurso   = $o58_codigo;
          $clempresto->e91_codtipo   = $e90_codigo;
          $clempresto->e91_rpcorreto = "false";
          $clempresto->incluir($anodestino,$e60_numemp);
          if ($clempresto->erro_status==0) {
            $sqlerro   = true;
            $erro_msg .= $clempresto->erro_msg;
            break;
          }
          
        } else {
          //echo "<br> Empenho $e60_numemp n�o encontrado no emprestotipo $e64_codele $c60_estrut";
          $cldb_viradaitemlog->c35_log = "Empenho $e60_numemp n�o encontrado no emprestotipo  $e64_codele    $c60_estrut";
          $cldb_viradaitemlog->c35_codarq        = 1010;
          $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
          $cldb_viradaitemlog->c35_data          = date("Y-m-d");
          $cldb_viradaitemlog->c35_hora          = date("H:i");
          $cldb_viradaitemlog->incluir(null);
          if ($cldb_viradaitemlog->erro_status==0) {
            $sqlerro   = true;
            $erro_msg .= $cldb_viradaitemlog->erro_msg;
            break;        
          }
        }
        
      }
      
    }
    
    if($sqlerro==false) {

      //echo("Processando restos anteriores a ".$this->anousu);
      $sqlemp4  = "select e91_numemp,e91_vlremp,e91_vlranu,e91_vlrliq,e91_vlrpag,e91_elemento,e91_recurso,e91_codtipo,e91_rpcorreto ";
      $sqlemp4 .= "from empresto where e91_anousu = ".$anoorigem ;
      $resultemp4 = db_query($sqlemp4);
      $linhasemp4 = pg_num_rows($resultemp4);
      
      for ($m=0; $m<$linhasemp4; $m++) {
        db_fieldsmemory($resultemp4, $m);
        $sqlemp5  = "  select c71_coddoc, ";
        $sqlemp5 .= "         sum(c70_valor) ";
        $sqlemp5 .= "    from empresto ";
        $sqlemp5 .= "         inner join empempenho on e60_numemp = e91_numemp ";
        $sqlemp5 .= "         inner join conlancamemp on c75_numemp = e91_numemp ";
        $sqlemp5 .= "         inner join conlancamdoc on c75_codlan = c71_codlan ";
        $sqlemp5 .= "         inner join conlancam on c70_codlan = c71_codlan ";
        $sqlemp5 .= "   where e91_anousu = {$anoorigem} ";
        $sqlemp5 .= "     and e91_numemp = {$e91_numemp} ";
        $sqlemp5 .= "     and c71_data between '{$anoorigem}-01-01' and '{$anoorigem}-12-31' ";
        $sqlemp5 .= "group by e91_numemp, ";
        $sqlemp5 .= "         c71_coddoc  ";
        
        $resultemp5 = db_query($sqlemp5);
        $linhasemp5 = pg_num_rows($resultemp5);
        
        $vlranu = 0;
        $vlrliq = 0;
        $vlrpag = 0;
        
        for ($v=0; $v<$linhasemp5; $v++) {
          db_fieldsmemory($resultemp5,$v);
          //$rest = pg_fetch_array($this->result,$v);
          if ($c71_coddoc == 31 || $c71_coddoc == 32) {
            $vlranu += $sum;
          }
          if ($c71_coddoc == 33 ) {
            $vlrliq += $sum;
          }
          if ($c71_coddoc == 34 ) {
            $vlrliq -= $sum;
          }
          if ($c71_coddoc == 35 or $c71_coddoc == 37) {
            $vlrpag += $sum;
          }
          if ($c71_coddoc == 36 or $c71_coddoc == 38) {
            $vlrpag -= $sum;
          }
          if ($c71_coddoc == 31 ) {
            $vlrliq -= $sum;
          }
        }
        
        
        if (round(round(round($e91_vlremp,2)-round($e91_vlranu+$vlranu,2),2)-round(($e91_vlrpag+$vlrpag),2),2) > 0 ) {
          $e91_vlranu = ($e91_vlranu+$vlranu);
          $e91_vlrliq = ($e91_vlrliq+$vlrliq);
          $e91_vlrpag = ($e91_vlrpag+$vlrpag);
         
          $e91_rpcorreto = ($e91_rpcorreto=="t")?"true":"false";

          $clempresto->e91_anousu    = $anodestino;
          $clempresto->e91_numemp    = $e91_numemp;
          $clempresto->e91_vlremp    = $e91_vlremp;
          $clempresto->e91_vlranu    = "$e91_vlranu";
          $clempresto->e91_vlrliq    = "$e91_vlrliq";
          $clempresto->e91_vlrpag    = "$e91_vlrpag";
          $clempresto->e91_elemento  = "$e91_elemento";
          $clempresto->e91_recurso   = $e91_recurso;
          $clempresto->e91_codtipo   = $e91_codtipo;
          $clempresto->e91_rpcorreto = $e91_rpcorreto;
          $clempresto->incluir($anodestino,$e91_numemp);
          if ($clempresto->erro_status==0) {
            $sqlerro   = true;
            $erro_msg .= $clempresto->erro_msg;
            break;
          }
        }
      }
      
    }    
  } else {
    
    if ($linhasorigem == 0) {
      $cldb_viradaitemlog->c35_log = "N�o existem dados (empresto) para o exercicio $anoorigem";
    }
    if ($linhasdestino >0) {
      $cldb_viradaitemlog->c35_log = "Ja existem dados  (empresto) para ano de destino $anodestino";
    }
    $cldb_viradaitemlog->c35_codarq        = 1011;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status==0) {
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }
  
  //if ($sqlerro == true) {
  //  echo "<br>Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
  //} 
  //xxxxxxxxxxxxxxxxxxxxxxxxxxxx
}
?>