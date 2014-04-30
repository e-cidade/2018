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
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_orcparamrelnota_classe.php");
include("classes/db_empresto_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$orcparamrel     = new cl_orcparamrel;
$clorcparamrelnota = new cl_orcparamrelnota;
$classinatura = new cl_assinatura;
$clempresto   = new cl_empresto;
/*
 *  se ativar debuga, o relatorio ira montar uma tabela html
 *  com os parametros abertos
 */
$debuga = "false"; 

// pesquisa notas explicativas
$res = $clorcparamrelnota->sql_record($clorcparamrelnota->sql_query("3",db_getsession("DB_anousu"),"o42_nota"));
if ($clorcparamrelnota->numrows > 0 ){
    db_fieldsmemory($res,0);
}

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);

    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    } else {
         $descr_inst .= $xvirg.$nomeinst;
    }

    $xvirg = ', ';
}



$head2 = "BALANÇO PATRIMONIAL - ANEXO 14";
$head3 = "EXERCÍCIO ".db_getsession("DB_anousu");

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,150);
     }
}

$head6 = "INSTITUIÇÕES : ".$descr_inst;
$anousu = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu").'-01-01';
$datafin = db_getsession("DB_anousu").'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',db_getsession("DB_anousu")));

$instituicao = str_replace("-",",",$db_selinstit);

$m_ativo_caixa         = $orcparamrel->sql_parametro_instit(3,1,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 1 | ATIVO - CAIXA
$m_ativo_movimento     = $orcparamrel->sql_parametro_instit(3,2,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 2 | ATIVO - BANCO movimento.
$m_ativo_aplicacao     = $orcparamrel->sql_parametro_instit(3,3,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 2 | ATIVO - bancos aplicação
$m_ativo_investimentos = $orcparamrel->sql_parametro_instit(3,4,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 2 | ATIVO - investimentos rpps

$m_ativo_realizavel    = $orcparamrel->sql_parametro_instit(3,5,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 5 | ATIVO - REALIZAVEL.
$m_ativo_bens_moveis   = $orcparamrel->sql_parametro_instit(3,6,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 6 | AP - BENS MOVEIS
$m_ativo_bens_imoveis  = $orcparamrel->sql_parametro_instit(3,7,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 7 | AP - BENS IMOVEIS
$m_ativo_bens_nat_industrial    = $orcparamrel->sql_parametro_instit(3,8,"f",$instituicao,db_getsession("DB_anousu"));  // 3 |  8 | AP - BENS NAT. INDUSTRIAL

$m_ap_creditos                  = $orcparamrel->sql_parametro_instit(3,9,"f",$instituicao,db_getsession("DB_anousu"));  // 3 |  9 | AP - CREDITOS
$m_ap_valores                   = $orcparamrel->sql_parametro_instit(3,10,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 10 | AP - VALORES
$m_acomp_valores_poder_terceiro = $orcparamrel->sql_parametro_instit(3,11,"f",$instituicao,db_getsession("DB_anousu")); // 3 | 11 | A.COMP. - VALORES EM PODER DE TERCEIROS
$m_acomp_valores_terceiros = $orcparamrel->sql_parametro_instit(3,12,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 12 | A.COMP. - VALORES DE TERCEIROS
$m_acomp_valores_nominais  = $orcparamrel->sql_parametro_instit(3,13,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 13 | A.COMP. - VALORES NOMINAIS EMITIDOS
$m_acomp_diversos          = $orcparamrel->sql_parametro_instit(3,14,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 14 | A.COMP. - DIVERSOS

$m_passivo_restos    = $orcparamrel->sql_parametro_instit(3,15,"f",$instituicao,db_getsession("DB_anousu"));            // 3 | 15 | PASSIVO - RESTOS A PAGAR PROCESSADOS
$m_passivo_restos_np = $orcparamrel->sql_parametro_instit(3,16,"f",$instituicao,db_getsession("DB_anousu"));            // 3 | 16 | PASSIVO - RESTOS A PAGAR NÂO PROCESSADOS
$m_passivo_divida    = $orcparamrel->sql_parametro_instit(3,17,"f",$instituicao,db_getsession("DB_anousu"));            // 3 | 17 | PASSIVO - SERVIÇOS DA DIVIDA A PAGAR

$m_passivo_depositos = $orcparamrel->sql_parametro_instit(3,18,"f",$instituicao,db_getsession("DB_anousu"));            // 3 | 18 | PASSIVO - DEPOSITOS
$m_passivo_debitos   = $orcparamrel->sql_parametro_instit(3,19,"f",$instituicao,db_getsession("DB_anousu"));            // 3 | 19 | PASSIVO - DEBITOS DE TESOURARIA
$m_passivo_operacoes = $orcparamrel->sql_parametro_instit(3,20,"f",$instituicao,db_getsession("DB_anousu"));            // 3 | 20 | PASSIVO - OUTRAS OPERAÇÕES

$m_pperm_interna_titulos   = $orcparamrel->sql_parametro_instit(3,21,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 21 | P.PERM - DIVIDA FUNDADA INTERNA - EM TITULOS
$m_pperm_interna_contratos = $orcparamrel->sql_parametro_instit(3,22,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 22 | P.PERM - DIVIDA FUNDADA INTERNA - EM CONTRATOS
$m_pperm_externa_titulos   = $orcparamrel->sql_parametro_instit(3,23,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 23 | P.PERM - DIVIDA FUNDADA EXTERNA - EM TITULOS
$m_pperm_externa_contratos = $orcparamrel->sql_parametro_instit(3,24,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 24 | P.PERM - DIVIDA FUNDADA EXTERNA - EM CONTRATOS
$m_pperm_diversos          = $orcparamrel->sql_parametro_instit(3,25,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 25 | P.PERM - DIVERSOS
$m_pcomp_poder_terceiros   = $orcparamrel->sql_parametro_instit(3,26,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 26 | P.COMP.- CONTRAPARTIDA DE VALORES EM PODER DE TERCEIROS
$m_pcomp_terceiros         = $orcparamrel->sql_parametro_instit(3,27,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 27 | P.COMP.- CONTRAPARTIDA DE VALORES DE TERCEIROS
$m_pcomp_nominais          = $orcparamrel->sql_parametro_instit(3,28,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 28 | P.COMP.- CONTRAPARTIDA DE VALORES NOMINAIS EMITIDOS
$m_pcomp_diversos          = $orcparamrel->sql_parametro_instit(3,29,"f",$instituicao,db_getsession("DB_anousu"));      // 3 | 29 | P.COMP.- DIVERSOS

$aOrcParametro = array_merge(
  $m_ativo_caixa                  ,
  $m_ativo_movimento              ,
  $m_ativo_aplicacao              ,
  $m_ativo_investimentos          ,
  $m_ativo_realizavel             ,
  $m_ativo_bens_moveis            ,
  $m_ativo_bens_imoveis           ,
  $m_ativo_bens_nat_industrial    ,
  $m_ap_creditos                  ,
  $m_ap_valores                   ,
  $m_acomp_valores_poder_terceiro ,
  $m_acomp_valores_terceiros      ,
  $m_acomp_valores_nominais       ,
  $m_acomp_diversos               ,
  $m_passivo_restos               ,
  $m_passivo_restos_np            ,
  $m_passivo_divida               ,
  $m_passivo_depositos            ,
  $m_passivo_debitos              ,
  $m_passivo_operacoes            ,
  $m_pperm_interna_titulos        ,
  $m_pperm_interna_contratos      ,
  $m_pperm_externa_titulos        ,  
  $m_pperm_externa_contratos      ,
  $m_pperm_diversos               ,
  $m_pcomp_poder_terceiros        ,
  $m_pcomp_terceiros              ,       
  $m_pcomp_nominais               ,  
  $m_pcomp_diversos
);

$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";
$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where,'','true','true','',$aOrcParametro);

// somadores
$v_ativo_caixa =0; 
$v_ativo_movimento=0;
$v_ativo_aplicacao=0;
$v_ativo_investimentos=0;

$v_ativo_realizavel=0;
$v_ativo_bens_moveis=0;
$v_ativo_bens_imoveis=0;
$v_ativo_bens_nat_industrial=0;
$v_ap_creditos=0;
$v_ap_valores=0;
$v_acomp_valores_poder_terceiro=0;
$v_acomp_valores_terceiros=0; 
$v_acomp_valores_nominais=0;
$v_acomp_diversos=0;
$v_passivo_restos=0;
$v_passivo_restos_np=0;
$v_passivo_divida=0;
$v_passivo_depositos=0;
$v_passivo_debitos=0;
$v_passivo_operacoes=0;
$v_pperm_interna_titulos=0;
$v_pperm_interna_contratos=0;
$v_pperm_externa_titulos=0;
$v_pperm_externa_contratos=0;
$v_pperm_diversos=0; 
$v_pcomp_poder_terceiros=0;
$v_pcomp_terceiros=0;
$v_pcomp_nominais=0;
$v_pcomp_diversos=0;

if ($debuga=="true"){
   echo "<table border=1 align=center>";
} 

//-------- variável 

/* 
 db_criatabela($result);

 echo "<pre>";
 print_r($m_ativo_caixa);
 echo "</pre>";

 exit;
*/

//--------- // ------------- // ------------- // ---------------
for($i=0;$i< pg_numrows($result);$i++) {
   db_fieldsmemory($result,$i);
   
   $v_elementos = array($estrutural,$c61_instit);

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ativo_caixa)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ativo_caixa); $x++){
       if ($estrutural == $m_ativo_caixa[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_ativo_caixa += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
      if ($debuga =="true"){
      	 echo "<tr><td>Ativo Caixa </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   } 

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ativo_movimento)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ativo_movimento); $x++){
       if ($estrutural == $m_ativo_movimento[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_ativo_movimento += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
      if ($debuga =="true"){
      	 echo "<tr><td> movimento </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ativo_aplicacao)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ativo_aplicacao); $x++){
       if ($estrutural == $m_ativo_aplicacao[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_ativo_aplicacao += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
      if ($debuga =="true"){
      	 echo "<tr><td> movimento </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ativo_investimentos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ativo_investimentos); $x++){
       if ($estrutural == $m_ativo_investimentos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_ativo_investimentos += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
     if ($debuga =="true"){
     	 echo "<tr><td> investimentos </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   } 

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ativo_realizavel)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ativo_realizavel); $x++){
       if ($estrutural == $m_ativo_realizavel[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_ativo_realizavel += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
     if ($debuga =="true"){
     	 echo "<tr><td>Ativo Realizavel </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ativo_bens_moveis)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ativo_bens_moveis); $x++){
       if ($estrutural == $m_ativo_bens_moveis[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_ativo_bens_moveis += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
     if ($debuga =="true"){
     	 echo "<tr><td>Ativo_bens_moveis </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ativo_bens_imoveis)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ativo_bens_imoveis); $x++){
       if ($estrutural == $m_ativo_bens_imoveis[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_ativo_bens_imoveis += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
     if ($debuga =="true"){
     	 echo "<tr><td>Ativo bens imoveis </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ativo_bens_nat_industrial)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ativo_bens_nat_industrial); $x++){
       if ($estrutural == $m_ativo_bens_nat_industrial[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_ativo_bens_nat_industrial += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
     if ($debuga =="true"){
     	 echo "<tr><td>Ativo bens imoveis </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ap_creditos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ap_creditos); $x++){
       if ($estrutural == $m_ap_creditos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_ap_creditos += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
      if ($debuga =="true"){
      	 echo "<tr><td>Ativo Creditos  </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_ap_valores)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_ap_valores); $x++){
       if ($estrutural == $m_ap_valores[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_ap_valores += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
     if ($debuga =="true"){
     	 echo "<tr><td>Ativo valores  </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_acomp_valores_poder_terceiro)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_acomp_valores_poder_terceiro); $x++){
       if ($estrutural == $m_acomp_valores_poder_terceiro[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_acomp_valores_poder_terceiro += $saldo_final;
      if ($debuga =="true"){
      	 echo "<tr><td>valores em poder de terceiros  </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_acomp_valores_terceiros)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_acomp_valores_terceiros); $x++){
       if ($estrutural == $m_acomp_valores_terceiros[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_acomp_valores_terceiros += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
      if ($debuga =="true"){
      	 echo "<tr><td>valores de terceiros  </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_acomp_valores_nominais)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_acomp_valores_nominais); $x++){
       if ($estrutural == $m_acomp_valores_nominais[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_acomp_valores_nominais += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
     if ($debuga =="true"){
     	 echo "<tr><td>valores nominais   </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_acomp_diversos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_acomp_diversos); $x++){
       if ($estrutural == $m_acomp_diversos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_acomp_diversos += anexo14_retorna_saldo($saldo_final, $sinal_final, "A");
      if ($debuga =="true"){
      	 echo "<tr><td>ativo compensado - diversos  </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_passivo_restos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_passivo_restos); $x++){
       if ($estrutural == $m_passivo_restos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_passivo_restos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo - Restos a Pagar </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_passivo_restos_np)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_passivo_restos_np); $x++){
       if ($estrutural == $m_passivo_restos_np[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_passivo_restos_np += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
     if ($debuga =="true"){
     	 echo "<tr><td>Passivo - Restos a Pagar NP </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_passivo_divida)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_passivo_divida); $x++){
       if ($estrutural == $m_passivo_divida[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_passivo_divida += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo - Divida </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_passivo_depositos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_passivo_depositos); $x++){
       if ($estrutural == $m_passivo_depositos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_passivo_depositos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
     if ($debuga =="true"){
     	 echo "<tr><td>Passivo - Depósitos </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_passivo_debitos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_passivo_debitos); $x++){
       if ($estrutural == $m_passivo_debitos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_passivo_debitos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
     if ($debuga =="true"){
     	 echo "<tr><td>Passivo - Debitos  </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_passivo_operacoes)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_passivo_operacoes); $x++){
       if ($estrutural == $m_passivo_operacoes[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_passivo_operacoes += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo - Operações </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pperm_interna_titulos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pperm_interna_titulos); $x++){
       if ($estrutural == $m_pperm_interna_titulos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_pperm_interna_titulos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo  Permanente - Interno Títulos </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }
   
   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pperm_interna_contratos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pperm_interna_contratos); $x++){
       if ($estrutural == $m_pperm_interna_contratos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
     $v_pperm_interna_contratos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
     if ($debuga =="true"){
     	 echo "<tr><td>Passivo  Permanente - Interno contratos </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
     }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pperm_externa_titulos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pperm_externa_titulos); $x++){
       if ($estrutural == $m_pperm_externa_titulos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_pperm_externa_titulos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo  Permanente - Externa Titulos </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pperm_externa_contratos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pperm_externa_contratos); $x++){
       if ($estrutural == $m_pperm_externa_contratos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_pperm_externa_contratos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo  Permanente - Externa Contratos </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pperm_diversos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pperm_diversos); $x++){
       if ($estrutural == $m_pperm_diversos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_pperm_diversos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo  Permanente - Diversos  </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pcomp_poder_terceiros)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pcomp_poder_terceiros); $x++){
       if ($estrutural == $m_pcomp_poder_terceiros[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_pcomp_poder_terceiros += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo Compensado - Valores em poder de terceiros </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pcomp_terceiros)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pcomp_terceiros); $x++){
       if ($estrutural == $m_pcomp_terceiros[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_pcomp_terceiros += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo Compensado - Valores de terceiros </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pcomp_nominais)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pcomp_nominais); $x++){
       if ($estrutural == $m_pcomp_nominais[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_pcomp_nominais += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo Compensado - Valores Nominais </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   }

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_pcomp_diversos)){        
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_pcomp_diversos); $x++){
       if ($estrutural == $m_pcomp_diversos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){        
      $v_pcomp_diversos += anexo14_retorna_saldo($saldo_final, $sinal_final, "P");
      if ($debuga =="true"){
      	 echo "<tr><td>Passivo Compensado - Valores Diversos </td><td>$c60_descr</td><td>$saldo_final</td></tr>";
      }	             
   } 
}
// as informações sobre RP processados e não processados, serviços da divida não
// são extraida de parametros
// aqui vão elas, de forma fixa, sobrescrevendo as variáveis acima
// despesas extras e serviços da dívida	
/*
$v_passivo_divida    = 0;
$v_passivo_restos    = 0;
$v_passivo_restos_np = 0;
for($i=0;$i<pg_numrows($result_despesa_rp);$i++){
   db_fieldsmemory($result_despesa_rp,$i);		   
   if (substr($o58_elemento,0,3)=='332'    ||    substr($o58_elemento,0,3)=='346'  ){		     				      
         $v_passivo_divida    += $atual_a_pagar + $atual_a_pagar_liquidado; // certo
   }	else {
	 $v_passivo_restos    += $atual_a_pagar_liquidado ;// processados
	 $v_passivo_restos_np += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado ;// não processados = à liquidar
   }	
}	
*/

if ($debuga=="true"){
   echo "</table>";
   exit;  // finaliza a página
}
//---- /////////////////////////////// --------------------------------------
$somador_ativo     = 0;
$somador_passivo = 0;
// somador do disponível
//$somador_disponivel =$v_ativo_caixa + $v_ativo_movimento + $v_ativo_aplicacao + $v_ativo_investimentos ;
$somador_disponivel =$v_ativo_caixa + $v_ativo_movimento + $v_ativo_aplicacao ;
$somador_ativo_financeiro = $somador_disponivel +$v_ativo_realizavel + $v_ativo_investimentos ;
$somador_passivo_financeiro =$v_passivo_restos+ $v_passivo_restos_np + $v_passivo_divida +$v_passivo_depositos+$v_passivo_debitos+$v_passivo_operacoes; 
// somador do permanente
$somador_ativo_permanente =$v_ativo_bens_moveis+$v_ativo_bens_imoveis+$v_ativo_bens_nat_industrial+$v_ap_creditos+$v_ap_valores;

$somador_divida_interna =$v_pperm_interna_titulos+$v_pperm_interna_contratos;
$somador_divida_externa = $v_pperm_externa_titulos+$v_pperm_externa_contratos;
$somador_passivo_permanente =$somador_divida_interna + $somador_divida_externa + $v_pperm_diversos;
// somador compensado
$somador_ativo_real = $somador_ativo_financeiro+$somador_ativo_permanente;
$somador_passivo_real = $somador_passivo_financeiro+ $somador_passivo_permanente;

$somador_passivo_real_descoberto =0;
if ($somador_ativo_real <$somador_passivo_real)
  $somador_passivo_real_descoberto = $somador_passivo_real - $somador_ativo_real;
$somador_ativo_real_liquido=0;
if ($somador_ativo_real >$somador_passivo_real)
  $somador_ativo_real_liquido = $somador_ativo_real -  $somador_passivo_real;
  
$somador_subtotal_ativo     = $somador_ativo_real + $somador_passivo_real_descoberto;
$somador_subtotal_passivo =$somador_passivo_real+$somador_ativo_real_liquido;
 
$somador_ativo_compensado =$v_acomp_valores_poder_terceiro+$v_acomp_valores_terceiros+$v_acomp_valores_nominais+$v_acomp_diversos;
$somador_passivo_compensado =$v_pcomp_poder_terceiros+$v_pcomp_terceiros+$v_pcomp_nominais+$v_pcomp_diversos;

$somador_ativo_geral     = $somador_subtotal_ativo     +  $somador_ativo_compensado; 
$somador_passivo_geral =$somador_subtotal_passivo + $somador_passivo_compensado; 
  
//-----------
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt        = 4;
$pagina = 1;

$pdf->addpage();
$pdf->setfont('arial','b',8);
$pdf->cell(95,$alt,"A T I V O",0,0,"C",0);
$pdf->cell(95,$alt,"P A S S I V O",0,1,"C",0);
$pdf->ln(5);

$pdf->cell(70,$alt,'ATIVO FINANCEIRO',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($somador_ativo_financeiro,'f'),0,0,"R",0);
$pdf->cell(70,$alt,'PASSIVO FINANCEIRO',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($somador_passivo_financeiro,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt,"Disponivel",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($somador_disponivel,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->setfont('arial','',8);
$pdf->cell(60,$alt,"Restos a Pagar Processados",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_passivo_restos,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(20);
$pdf->cell(60,$alt,"Caixa",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_caixa,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Restos a Pagar Não Processados",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_passivo_restos_np,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(20);
$pdf->cell(60,$alt,"Bancos e Correspondentes",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_movimento,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Serviço da Divida a Pagar",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_passivo_divida,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(20);
$pdf->cell(60,$alt,"Vinculado em c/c Bancárias",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_aplicacao,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0); // espaço
$pdf->cell(60,$alt,"Depósitos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_passivo_depositos,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15); 
$pdf->setfont('arial','b',8);
$pdf->cell(65,$alt,"Investimentos em Regime Próprio de Previdência",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_investimentos,'f'),0,0,"R",0);
$pdf->setfont('arial','',8);
$pdf->cell(10,$alt,"",0,0,"R",0); // espaço
$pdf->cell(60,$alt,"Débitos de Tesouraria",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_passivo_debitos,'f'),0,1,"R",0);
$pdf->ln(2);


$pdf->setx(20);
$pdf->cell(60,$alt,"Investimentos em Regime Próprio de Previdência",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_investimentos,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0); // espaço
$pdf->cell(60,$alt,"Outras Operações",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_passivo_operacoes,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15); 
$pdf->setfont('arial','b',8);
$pdf->cell(65,$alt,"Realizável",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_realizavel,'f'),0,0,"R",0);
$pdf->setfont('arial','',8);
$pdf->cell(10,$alt,"",0,0,"R",0); // espaço
$pdf->cell(60,$alt,"",0,0,"L",0);
$pdf->cell(25,$alt,"",0,1,"R",0);
$pdf->ln(2);

$pdf->setx(20);
$pdf->cell(60,$alt,"Realizável",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_realizavel,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0); // espaço
$pdf->cell(60,$alt,"",0,0,"L",0);
$pdf->cell(25,$alt,"",0,1,"R",0);
$pdf->ln(2);

$pdf->ln(2);
$pdf->setfont('arial','B',8); 
$pdf->cell(70,$alt,'ATIVO PERMANENTE',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($somador_ativo_permanente,'f'),0,0,"R",0);
$pdf->cell(70,$alt,'PASSIVO PERMANENTE',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($somador_passivo_permanente,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setfont('arial','',8); 

$pdf->setx(15);
$pdf->cell(65,$alt,"Bens Móveis",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_bens_moveis,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->setfont('arial','b',8);
$pdf->cell(60,$alt,"Divida Fundada Interna",0,0,"L",0);  // titulo
$pdf->cell(25,$alt,db_formatar($somador_divida_interna,'f'),0,1,"R",0);
$pdf->setfont('arial','',8);
$pdf->ln(2);


$pdf->setx(15);
$pdf->setfont('arial','',8);
$pdf->cell(65,$alt,"Bens Imóveis",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_bens_imoveis,'f'),0,0,"R",0);
$pdf->cell(20,$alt,"",0,0,"R",0);
$pdf->cell(50,$alt,"Em Títulos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pperm_interna_titulos,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt,"Bens de Natureza Industrial",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ativo_bens_nat_industrial,'f'),0,0,"R",0);
$pdf->cell(20,$alt,"",0,0,"R",0);
$pdf->cell(50,$alt,"Por Contratos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pperm_interna_contratos,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt,"Creditos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ap_creditos,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->setfont('arial','b',8);
$pdf->cell(60,$alt,"Divida Fundada Externa",0,0,"L",0);  // Titulo
$pdf->cell(25,$alt,db_formatar($somador_divida_externa,'f'),0,1,"R",0);
$pdf->setfont('arial','',8);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt,"Valores Diversos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_ap_valores,'f'),0,0,"R",0);
$pdf->cell(20,$alt,"",0,0,"R",0);
$pdf->cell(50,$alt,"Em Títulos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pperm_externa_titulos,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt," ",0,0,"L",0);
$pdf->cell(25,$alt," ",0,0,"R",0);
$pdf->cell(20,$alt,"",0,0,"R",0);
$pdf->cell(50,$alt,"Por Contratos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pperm_externa_contratos,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt,"",0,0,"L",0);
$pdf->cell(25,$alt,"",0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Diversos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pperm_diversos,'f'),0,1,"R",0);
$pdf->ln(2);


$pdf->ln(2);
$pdf->setfont('arial','b',8);
$pdf->setx(15);
$pdf->cell(65,$alt,"Soma do Ativo Real",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($somador_ativo_real,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Soma do Passivo Real",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($somador_passivo_real,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->ln(2);
$pdf->cell(70,$alt,'SALDO PATRIMONIAL',0,0,"L",0,'','.');
$pdf->cell(25,$alt,"",0,0,"R",0);
$pdf->cell(70,$alt,'SALDO PATRIMONIAL',0,0,"L",0,'','.');
$pdf->cell(25,$alt,"",0,1,"R",0);
$pdf->ln(2);

$pdf->ln(2);
$pdf->setx(15);
$pdf->cell(65,$alt,"Passivo Real Descoberto",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($somador_passivo_real_descoberto,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Ativo Real Liquido",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($somador_ativo_real_liquido,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(20);
$pdf->cell(60,$alt,"SubTotal",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($somador_subtotal_ativo,'f'),0,0,"R",0);
$pdf->cell(20,$alt,"",0,0,"R",0);
$pdf->cell(50,$alt,"SubTotal",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($somador_subtotal_passivo,'f'),0,1,"R",0);
$pdf->ln(2);


//-- fim Permanente

$pdf->ln(2);
$pdf->cell(70,$alt,'ATIVO COMPENSADO',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($somador_ativo_compensado,'f'),0,0,"R",0);
$pdf->cell(70,$alt,'PASSIVO COMPENSADO',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($somador_passivo_compensado,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setfont('arial','',8);
$pdf->setx(15);
$pdf->cell(65,$alt,"Valores em Poder de Terceiros",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_acomp_valores_poder_terceiro,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Contrapartida de Valores em Poder de Terceiros",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pcomp_poder_terceiros,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt,"Valores de Terceiros",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_acomp_valores_terceiros,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Contrapartida de Valores de Terceiros",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pcomp_terceiros,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt,"Valores Nominais Emitidos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_acomp_valores_nominais,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Contrapartida de Valores Nominais Emitidos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pcomp_nominais,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setx(15);
$pdf->cell(65,$alt,"Diversos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_acomp_diversos,'f'),0,0,"R",0);
$pdf->cell(10,$alt,"",0,0,"R",0);
$pdf->cell(60,$alt,"Diversos",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($v_pcomp_diversos,'f'),0,1,"R",0);
$pdf->ln(2);

$pdf->setfont('arial','b',8);
$pdf->ln(4);
$pdf->setfont('arial','b',8);
$pdf->cell(70,$alt,'TOTAL GERAL',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($somador_ativo_geral,'f'),0,0,"R",0);
$pdf->cell(70,$alt,'TOTAL GERAL',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($somador_passivo_geral,'f'),0,1,"R",0);

$pdf->ln(2);

$periodo = db_retorna_periodo($mes,"B");
notasExplicativas(&$pdf,3,"{$periodo}",190);

$pdf->ln(15);

// assinaturas
assinaturas(&$pdf,&$classinatura,'BG');

function anexo14_retorna_saldo($saldo, $sinal, $grupo) {
  if ($grupo == "A" and $sinal == "C") {
    $saldo = $saldo *-1;
  } elseif ($grupo == "P" and $sinal == "D") {
    $saldo = $saldo *-1;
  }
  return $saldo;
}

$pdf->Output();
   
?>