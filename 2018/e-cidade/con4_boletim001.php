<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("dbforms/db_funcoes.php");

include("classes/db_boletim_classe.php");
include("classes/db_conlancambol_classe.php");
include("classes/db_saltes_classe.php");
include("classes/db_orcreceita_classe.php");
include("classes/db_orcreceitaval_classe.php");
include("classes/db_orcfontes_classe.php");
include("classes/db_orcfontesdes_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamrec_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamlr_classe.php");
include("classes/db_conlancampag_classe.php");
include("classes/db_conlancamcompl_classe.php");
include("classes/db_contrans_classe.php");
include("classes/db_conplanoreduz_classe.php");
include("classes/db_conlancamcgm_classe.php");
include("classes/db_corgrupocorrente_classe.php");
include("classes/db_conlancamcorgrupocorrente_classe.php");
include("classes/db_conplanoconplanoorcamento_classe.php");


db_postmemory($HTTP_POST_VARS);
$clsaltes = new cl_saltes;
$clorcreceita = new cl_orcreceita;
$clorcreceitaval = new cl_orcreceitaval;
$clorcfontes = new cl_orcfontes;
$clorcfontesdes = new cl_orcfontesdes;
$db_opcao = 1;
$db_botao = false;
$msg_erro = "";
$debug= false; // output

$executar = false;
$mensagem = "";

if( isset($processar) || isset($desprocessar) ){
  
  $data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
  $clboletim = new cl_boletim;
  $result = $clboletim->sql_record($clboletim->sql_query($data,db_getsession("DB_instit")));
  if($clboletim->numrows==0 ){
    db_msgbox('Boletim não gerado para esta data. ('.$c70_data_dia."/".$c70_data_mes."/".$c70_data_ano.')');
    db_redireciona('con4_boletim001.php');
  }else{
    db_fieldsmemory($result,0);
    if($k11_libera == 'f' || $k11_lanca == 't' || db_getsession('DB_anousu') != $c70_data_ano ){
      if($k11_libera == 'f' ){
        db_msgbox('Boletim não liberado para a Contabilidade.');
      }else if ($k11_lanca == 't' ){
        db_msgbox('Boletim já processado pela Contabilidade.');
      }else{
        db_msgbox('Exercício inválido. Permitido: '.db_getsession("DB_anousu"));
      }
      db_redireciona('con4_boletim001.php');
    }else{
      
      $sSqlSaltes = "select k13_conta 
                       from saltes 
                      inner join conplanoreduz on c61_reduz = k13_reduz 
                      where k13_datvlr >= '{$data}' 
                        and k13_vlratu <> 0 
                        and c61_instit = ".db_getsession("DB_instit")."
                        and c61_anousu = ".db_getsession("DB_anousu");
      $rsSaltes   = db_query($sSqlSaltes);
      if (pg_num_rows($rsSaltes) > 0) {
        
        db_msgbox("Saldo das contas da tesouraria já calculado para a data ".db_formatar($data,'d'));
        db_redireciona('con4_boletim001.php');  
        
      }
      $executar = true;
    }
  }
  
  if($executar==true){
    
    $clconlancam    = new cl_conlancam;
    $clconlancambol = new cl_conlancambol;
    $clconlancamrec = new cl_conlancamrec;
    $clconlancamdoc = new cl_conlancamdoc;
    $clconlancamcgm = new cl_conlancamcgm;
    $clconlancamval = new cl_conlancamval;
    $clconlancamlr  = new cl_conlancamlr;
    $clconlancampag = new cl_conlancampag;
    $clconlancamcompl = new cl_conlancamcompl;
    $clcontrans       = new cl_contrans;
    $clconplanoreduz  = new cl_conplanoreduz;
    
    $sql ="       
    select distinct
    cornump.k12_receit as rec_testa,
    taborc.k02_codrec as codrec_testa,
    o70_codrec as orcreceita_testa,
    k12_conta as conta_testa,
    c62_reduz as reduzexe_testa,
    c61_reduz as reduz_testa                        
    from corrente
    
    inner join cornump  on corrente.k12_id     = cornump.k12_id   and     
    corrente.k12_data   = cornump.k12_data     and
    corrente.k12_autent = cornump.k12_autent 
    
    left outer join corhist on corrente.k12_id = corhist.k12_id and      
    corrente.k12_data   = corhist.k12_data    and 
    corrente.k12_autent = corhist.k12_autent 
    
    left join tabrec  on k12_receit = tabrec.k02_codigo 
    
    left join taborc on taborc.k02_codigo = tabrec.k02_codigo and 
    taborc.k02_anousu=".db_getsession("DB_anousu")."
    
    left join orcreceita on o70_codrec  = k02_codrec and 
    o70_anousu = k02_anousu 
    
    left join conplanoexe on k12_conta = c62_reduz and
    c62_anousu = taborc.k02_anousu
    
    left join conplanoreduz on c61_reduz  = c62_reduz and 
    c61_anousu = c62_anousu 
    
    where corrente.k12_data  = '".$data."'";
    
    // Comentado para posterior analise pelo Paulo pois falta testar o TABPLAN (16/03/2007 Fabrizio)
    /*$resultteste = pg_query($sql);
    
    //db_criatabela($resultteste);exit;     
    if( $resultteste == false){
      $executar = false;
    }else{
      for($testa=0;$testa<pg_numrows($resultteste);$testa++){
        db_fieldsmemory($resultteste,$testa);
        if( $rec_testa == ""){
          $mensagem .= "Receita [$rec_testa] não cadastrada no (tabrec)\n";
        }
        if( $codrec_testa == ""){
          $mensagem .= "Receita [$rec_testa] não cadastrada no (taborc)\n";
        }
        if( $orcreceita_testa == ""){
          $mensagem .= "Receita [$rec_testa][$orcreceita_testa] não cadastrada no (conplanoexe)\n";
        }
        if( $reduzexe_testa == ""){
          $mensagem .= "Receita [$conta_testa][$reduzexe_testa] não cadastrada no (conplanoexe)\n";
        }
        if( $reduz_testa == ""){
          $mensagem .= "Receita [$conta_testa][$reduz_testa] não cadastrada no (conplanoreduz)\n";
        }
      }
      if($mensagem != ""){
        $executar = false;
      }
    }*/
    
    if ( $executar == true ){
      
      // select para todas as receitas orçamentarias
      $arrecada_boletim = true;
      
      /* agrupa os registros sem historico lançado  */
      $sql ="       
			select	xxx.*, 
							orcreceita.o70_codigo from (
      select *
      from (
      
      select 
      k12_conta,
      k02_codrec,
      sum(arrecada) as arrecada,
      sum(estorna) as estorna,
      cgm_pago,
      cgm_estornado,
      k12_codcla,
      k12_histcor,
      0 as k12_id,
      0 as k12_autent
      from  (
      select 	         
      k12_conta,
      k02_codrec,
      k12_histcor,
      (select k00_numcgm from arrepaga where k00_numpre= x.k12_numpre and 
      k00_numpar = x.k12_numpar 
      limit 1) as cgm_pago,
      (select k00_numcgm from arrecad  where k00_numpre= x.k12_numpre and 
      k00_numpar= x.k12_numpar 
      limit 1) as cgm_estornado,
      
      (select k12_codcla from corcla   where k12_id    = x.k12_id and 
      k12_data  ='".$data."' and
      k12_autent= x.k12_autent limit 1) as k12_codcla,				       
      arrecada,
      estorna
      
      from (";
      
        $sql .= " select corrente.k12_id,
                         corrente.k12_autent,
                         k12_conta,
                         k02_codrec,
                         round(sum(case when corrente.k12_estorn = false 
                                       then cornump.k12_valor 
                                       else 0::float8 end),2) as arrecada,
                         round(sum(case when corrente.k12_estorn = true 
                                        then cornump.k12_valor else 0::float8 end),2) as estorna,
                         k12_histcor,
                         cornump.k12_numpre,
                         cornump.k12_numpar
                    from corrente
                         inner join cornump  on corrente.k12_id     = cornump.k12_id   and     
                         corrente.k12_data   = cornump.k12_data     and
                         corrente.k12_autent = cornump.k12_autent 
                         left outer join corhist on corrente.k12_id   = corhist.k12_id 
                                                and corrente.k12_data = corhist.k12_data
                                                and corrente.k12_autent = corhist.k12_autent 
                         inner join tabrec  on k12_receit = tabrec.k02_codigo 
                         inner join taborc  on taborc.k02_codigo = tabrec.k02_codigo 
                                           and taborc.k02_anousu=".db_getsession("DB_anousu");
      if (USE_PCASP)  {
          
        $sql .= " inner join orcreceita on orcreceita.o70_codrec = taborc.k02_codrec ";
        $sql .= "                      and orcreceita.o70_anousu = taborc.k02_anousu ";
        $sql .= " inner join orcfontes  on orcfontes.o57_codfon =  orcreceita.o70_codfon ";
        $sql .= "                      and orcfontes.o57_anousu =  orcreceita.o70_anousu ";
        
        $sql .= " inner join conplanoorcamento on conplanoorcamento.c60_codcon = orcfontes.o57_codfon";
        $sql .= "                             and conplanoorcamento.c60_anousu = orcfontes.o57_anousu";
        $sql .= " inner join conplanoconplanoorcamento on conplanoconplanoorcamento.c72_conplanoorcamento = conplanoorcamento.c60_codcon";
        $sql .= "                                     and conplanoconplanoorcamento.c72_anousu = conplanoorcamento.c60_anousu";
        
        $sql .= " inner join conplano on conplano.c60_codcon = conplanoconplanoorcamento.c72_conplano";
        $sql .= "                    and conplano.c60_anousu = conplanoconplanoorcamento.c72_anousu";
        $sql .= " inner join conplanoreduz on conplano.c60_codcon = conplanoreduz.c61_codcon ";
        $sql .= "                         and conplano.c60_anousu = conplanoreduz.c61_anousu ";
        $sql .= " inner join conplanoexe on conplanoreduz.c61_reduz = c62_reduz ";
        $sql .= "                       and c62_anousu   = conplanoreduz.c61_anousu ";
        
      } else {
        
        $sql .= " inner join conplanoexe on k12_conta    = c62_reduz ";
        $sql .= "                       and c62_anousu   = taborc.k02_anousu ";
        $sql .= "  inner join conplanoreduz on c61_reduz  = c62_reduz ";
        $sql .= "                          and c61_anousu = c62_anousu ";
        
      }
      $sql .= "   where corrente.k12_data  = '".$data."' 
                    and taborc.k02_anousu=".db_getsession('DB_anousu')." 
                    and conplanoreduz.c61_instit =  ".db_getsession('DB_instit')." 
                    and corhist.k12_id is null
                  group by corrente.k12_id,
                           corrente.k12_autent,
                           corrente.k12_conta,
                           taborc.k02_codrec,
                           cornump.k12_numpre,
                           cornump.k12_numpar,
                           corhist.k12_histcor
              ) as x
      ) as xx
      group by 
      k12_conta,
      k02_codrec,
      k12_histcor,
      cgm_pago,
      cgm_estornado,
      k12_codcla,
      k12_histcor,
      k12_id,
      k12_autent
      
      
  ) as xxx
      
      union all ";
      
     $sql .= " select 
      k12_conta,
      k02_codrec,
      arrecada as arrecada,
      estorna as estorna,
      cgm_pago,
      cgm_estornado,
      k12_codcla,
      k12_histcor,
      k12_id,
      k12_autent
      from  (
      select  k12_conta,
      k02_codrec,
      k12_histcor,
      (select k00_numcgm from arrepaga where k00_numpre= x.k12_numpre and 
      k00_numpar = x.k12_numpar 
      limit 1) as cgm_pago,
      (select k00_numcgm from arrecad  where k00_numpre= x.k12_numpre and 
      k00_numpar= x.k12_numpar 
      limit 1) as cgm_estornado,
      
      (select k12_codcla from corcla   where k12_id    = x.k12_id and 
      k12_data  ='".$data."' and
      k12_autent= x.k12_autent limit 1) as k12_codcla,				       
      arrecada,
      estorna,
      k12_id,
      k12_autent
      
      from (
      select corrente.k12_id,
      corrente.k12_autent,
      k12_conta,
      k02_codrec,
      round( case when corrente.k12_estorn = false then cornump.k12_valor else 0::float8 end,2) as arrecada,
      round( case when corrente.k12_estorn = true then cornump.k12_valor else 0::float8 end,2) as estorna,
      k12_histcor,
      cornump.k12_numpre,
      cornump.k12_numpar
      from corrente
      
      inner join cornump  on corrente.k12_id     = cornump.k12_id   and     
      corrente.k12_data   = cornump.k12_data     and
      corrente.k12_autent = cornump.k12_autent 
      
      left outer join corhist on corrente.k12_id = corhist.k12_id and      
      corrente.k12_data   = corhist.k12_data    and 
      corrente.k12_autent = corhist.k12_autent 
      
      inner join tabrec  on k12_receit = tabrec.k02_codigo 
      
      inner join taborc on taborc.k02_codigo = tabrec.k02_codigo and 
      taborc.k02_anousu=".db_getsession("DB_anousu");
    if (USE_PCASP)  {
          
        $sql .= " inner join orcreceita on orcreceita.o70_codrec = taborc.k02_codrec ";
        $sql .= "                      and orcreceita.o70_anousu = taborc.k02_anousu ";
        $sql .= " inner join orcfontes  on orcfontes.o57_codfon =  orcreceita.o70_codfon ";
        $sql .= "                      and orcfontes.o57_anousu =  orcreceita.o70_anousu ";
        
        $sql .= " inner join conplanoorcamento on conplanoorcamento.c60_codcon = orcfontes.o57_codfon";
        $sql .= "                             and conplanoorcamento.c60_anousu = orcfontes.o57_anousu";
        $sql .= " inner join conplanoconplanoorcamento on conplanoconplanoorcamento.c72_conplanoorcamento = conplanoorcamento.c60_codcon";
        $sql .= "                                     and conplanoconplanoorcamento.c72_anousu = conplanoorcamento.c60_anousu";
        
        $sql .= " inner join conplano on conplano.c60_codcon = conplanoconplanoorcamento.c72_conplano";
        $sql .= "                    and conplano.c60_anousu = conplanoconplanoorcamento.c72_anousu";
        $sql .= " inner join conplanoreduz on conplano.c60_codcon = conplanoreduz.c61_codcon ";
        $sql .= "                         and conplano.c60_anousu = conplanoreduz.c61_anousu ";
        $sql .= " inner join conplanoexe on conplanoreduz.c61_reduz = c62_reduz ";
        $sql .= "                       and c62_anousu   = conplanoreduz.c61_anousu ";
        
      } else {
        
        $sql .= " inner join conplanoexe on k12_conta    = c62_reduz ";
        $sql .= "                       and c62_anousu   = taborc.k02_anousu ";
        $sql .= "  inner join conplanoreduz on c61_reduz  = c62_reduz ";
        $sql .= "                          and c61_anousu = c62_anousu ";
        
      }
      
      $sql .= "where 
                 corrente.k12_data  = '".$data."' and
      taborc.k02_anousu=".db_getsession('DB_anousu')." and
      conplanoreduz.c61_instit =  ".db_getsession('DB_instit')." and
      corhist.k12_id is not null
      
      ) as x
      ) as xx
			) as xxx
			inner join orcreceita on o70_anousu = " . db_getsession('DB_anousu') . " and o70_codrec = k02_codrec
      ";
      
      $resultorcamentaria = pg_query($sql) or die($sql);
      
      if ($debug==true){
        echo $sql; 
        db_criatabela($resultorcamentaria);
        exit;
      }	
      
      
      include("con4_boletim004.php");
      //if(pg_numrows($resultorcamentaria)!=0){
        // include("con4_boletim004.php");
      //  }else{
        //   $msg_erro = 'Não existe receita arrecadada para esta data.';
      // }
    }
  }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
<center>
<?
if($executar == false && $mensagem != ""){
  echo $mensagem;
}else{
  include("forms/db_frmboletim001.php");
}
?>
</center>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($msg_erro!='' && $debug==false){
  db_msgbox($msg_erro);
  db_redireciona("con4_boletim001.php");
}

?>