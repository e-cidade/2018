<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

 include("fpdf151/pdf1.php");
 include("fpdf151/assinatura.php");
 include("libs/db_app.utils.php");
 include("libs/db_utils.php");
 include("classes/db_orcsuplem_classe.php");
 include("libs/db_liborcamento.php");
 include("classes/db_db_config_classe.php");
 include("classes/db_db_paragrafo_classe.php");
 db_app::import("orcamento.suplementacao.*");
 $classinatura = new cl_assinatura;
 $cldbconfig = new cl_db_config;
 $cldbparagrafo = new cl_db_paragrafo;
 $auxiliar = new cl_orcsuplem;
 $aux = new cl_orcsuplem;
 $clorcsuplem = new cl_orcsuplem;
 $anousu = db_getsession("DB_anousu");
 parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
 $projeto = (isset($o46_codlei)&&!empty($o46_codlei))?$o46_codlei:'null';
 $anousu = db_getsession("DB_anousu");
 $tem_superavit = false;

 $pdf = new PDF1();
 $pdf->Open();
 $pdf->AliasNbPages();
 $pdf->AddPage("P");
 // monta cabecalho do relatório    
 $pdf->SetFillColor(235);
 $pdf->SetFont('Arial','',9);
 $pdf->setY(60);
 $pdf->setX(5);
 $artigo = 0;
 

 /**
   * executa select para saber se é suplementação ou crédito especial 
   *
   */
  $sql = "select o48_tiposup,
                 o39_data,
                 o45_numlei
         from orcprojeto
              inner join orclei        on o45_codlei  = orcprojeto.o39_codlei
              inner join orcsuplem     on o46_codlei  = orcprojeto.o39_codproj
              inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup and 
                                          orcsuplemtipo.o48_coddocsup >  0        				      
         where o39_codproj=$projeto limit 1
         ";

  $res= $auxiliar->sql_record($sql); 
  db_fieldsmemory($res,0); 

  $xtipo = $o48_tiposup;
  $xdata = $o39_data;

if($xtipo < 1006 ||  $xtipo > 1014 ){
    $tipo_sup = 'Crédito Suplementar';
  }elseif ($xtipo == 1014){  
    $tipo_sup = 'Crédito de Transferência';
  }else{
    $tipo_sup = 'Crédito Especial';
  }
  

 /**
   * executa select para pegar o total da suplementação 
   *
   */
 $sql = "select sum(0) as total_suplementado,
                case when o139_orcprojeto is null then '1' else '2' end as projeto_tipo,
                o39_numero, 
                o39_compllei,
                o39_data,
                exists(select 1 
                         from orcsuplem b
                              inner join orcsuplemlan on b.o46_codsup = o49_codsup
                        where b.o46_codlei={$projeto}) as processado
           from orcprojeto
                inner join orclei on  o45_codlei   = orcprojeto.o39_codlei
                inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
                left  join orcprojetoorcprojetolei on o39_codproj = o139_orcprojeto
                inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                      and orcsuplemtipo.o48_coddocsup >  0                      
         where o39_codproj=$projeto
         group by o139_orcprojeto,o39_numero,o39_data,o39_compllei 
         ";
         
  $res= $auxiliar->sql_record($sql); 
//  db_criatabela($res);exit;
  if ($auxiliar->numrows > 0 ){
       db_fieldsmemory($res,0,true); 
       global $projeto_tipo,$total_suplementado,$o39_numero,$o39_data;
  } else {
       db_redireciona('db_erros.php?fechar=true&db_erro=(Ln:74) Nenhum registro encontrado.');
  }  
  if ($processado == 't') {
    $projeto_tipo = 1;
  }
  $sSqlSuplementacoes   = $clorcsuplem->sql_query(null,"*","o46_codsup","orcprojeto.o39_codproj= {$projeto}");
  $rsSuplementacoes     = $clorcsuplem->sql_record($sSqlSuplementacoes);
  $aSuplementacao       = db_utils::getCollectionByRecord($rsSuplementacoes);
  $valorutilizado       = 0;
  foreach ($aSuplementacao as $oSuplem) {
      
    $oSuplementacao = new Suplementacao($oSuplem->o46_codsup);
    $total_suplementado += $oSuplementacao->getvalorSuplementacao();  
  }
  unset($oSuplementacao);
 /////////////////////////////////////////////////////////
   ///////
    if ($projeto_tipo == "1")
      $projeto_tipo_texto ="DECRETO";
    else
      $projeto_tipo_texto ="PROJETO DE LEI";
    //
    $pdf->setX(20);     
//    $pdf->Cell(170,4,$projeto_tipo_texto." ".($projeto_tipo == 1?$o39_numero."/".substr($o39_data,6,4):''),0,1,"C",'1');  
    $pdf->Cell(170,4,$projeto_tipo_texto." ".($projeto_tipo == 1?$o39_numero:'').strtoupper(" de ".substr($o39_data,0,2)." de ".db_mes(substr($o39_data,3,2))." de ".substr($o39_data,6,4)),0,1,"C",'1');  
    $pdf->Ln(7);	
   /////// 
    $txt="$tipo_sup na importancia de ".
         "R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true).") e da outras providências. ";
    $pdf->setX(100);
    $pdf->multicell(90,4,$txt,'0','J','0',20); 
    $pdf->Ln(7);
   ///////
    if ($projeto_tipo == "1"){ // decreto
       $res= $cldbconfig->sql_record($cldbconfig->sql_query(db_getsession("DB_instit")));
       db_fieldsmemory($res,0);
       $pdf->setX(20);
/*       
       $pref = strtoupper($pref);
       $txt="$pref, PREFEITO MUNICIPAL DE $munic, $uf, no uso de suas atribuições legais";
*/       
       $pref = ucfirst($pref);       
       $txt="$pref, Prefeito Municipal, no uso de suas atribuições legais e de conformidade com a Lei Municipal $o45_numlei";

       if($o39_compllei != ""){
         $txt .= ", $o39_compllei, DECRETA: ";
       }else{
         $txt .= ", DECRETA:";
       }
       $pdf->multicell(170,4,$txt,'0','J','0');
       $pdf->Ln(7);	     
    } else {   // quando for lei 
       $res= $cldbconfig->sql_record($cldbconfig->sql_query(db_getsession("DB_instit")));
       db_fieldsmemory($res,0);
       $pdf->setX(20);
       $pref = strtoupper($pref);
       $txt="$pref, PREFEITO MUNICIPAL DE $munic, $uf.";
       $pdf->multicell(170,4,$txt,'0','J','0');
       $pdf->Ln(7);	     
       $pdf->setX(20);
       $txt="FAÇO SABER, que a Camara Municipal aprovou e eu sanciono a seguinte Lei: ";
       $pdf->multicell(170,4,$txt,'0','J','0');
       $pdf->Ln(7);	     
    }
  ////////// primeiro artigo, das suplementações
     ////////////////////////////////////////////////
    $artigo = $artigo +1;
    $txt="Art $artigo. -  Fica aberto $tipo_sup na importância de R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true)." ) ".
	 "sob a seguinte classificação econônica e programatica ";
    $pdf->setX(20);	 
    $pdf->multicell(170,4,$txt,'0','J','0',20);
    $pdf->Ln(4);


  /////////////////////////////////////////////////////
  // seleciona suplementacoes do projeto
  // executa o mesmo select, só que agora pra listar as suplementações
  /////////////////////////////////////////////////////
  $sql = "select 
              o39_codproj,
              o46_codsup,
              o46_tiposup,
              o48_descr,
              o47_coddot,
              o58_orgao,
              o58_unidade,
	      fc_estruturaldotacao(o47_anousu,o47_coddot) as estrutural,
              o47_anousu,
	      o47_valor
         from orcprojeto
              inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
              inner join orcsuplemval on o47_codsup = orcsuplem.o46_codsup 
                                      and orcsuplemval.o47_valor > 0
	      inner join orcdotacao on o58_coddot = o47_coddot and o58_anousu = ".db_getsession("DB_anousu")."
              inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                      and orcsuplemtipo.o48_coddocsup >  0        				      
         where o39_codproj=$projeto
	
         ";
	 $sSqlDotacaoPPA = "select 
	            o39_codproj,
              o46_codsup,
              o46_tiposup,
              o48_descr,
              0 as o47_coddot,
              o08_orgao,
              o08_unidade,
             fc_estruturaldotacaoppa(o08_ano, o08_sequencial) as estrutural,
             o08_ano,
             o136_valor as o47_valor
           from orcprojeto
                inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
                inner join orcsuplemdespesappa on o136_orcsuplem = orcsuplem.o46_codsup 
                inner join ppaestimativadespesa on o07_sequencial = o136_ppaestimativadespesa 
                inner join ppadotacao  on o07_coddot   = o08_sequencial 
                inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                          and orcsuplemtipo.o48_coddocsup >  0                      
          where o39_codproj=$projeto
         order by o58_orgao,o58_unidade ";
  $res= $auxiliar->sql_record($sql." union all {$sSqlDotacaoPPA}");
 // db_criatabela($res);exit;
  $total = 0;
  if ($auxiliar->numrows > 0 ){
      for ($x=0;$x < $auxiliar->numrows ;$x++){
	  db_fieldsmemory($res,$x);
	  $pdf->setX(40);         
          $pdf->Cell(80,4,$estrutural,0,0,"L",'0');  
	  $pdf->Cell(20,4," ( $o47_coddot ) ",0,0,"L",'0');  
  	  $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  
	  $pdf->setX(20);   
	  $total += $o47_valor;
	  $pdf->Ln();
	     
      }      
      $pdf->Cell(130,4,'',0,0,"L",'0');  
      $pdf->Cell(30,4,db_formatar($total,'f'),"T",1,"R",'0');  
      $pdf->setX(20);   

  }
 /////////////////////////////////////////////////////
 /// inicio das origens das suplementacoes 
 /// recurso,  nome,  valor
 $sql=" select 
              o39_codproj,
              o46_codsup,
              o46_tiposup,
	      o46_obs as text_observacao_superavit,
              o48_descr,
              o47_coddot,
              o47_anousu,
	      o47_valor
         from orcprojeto
              inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
              inner join orcsuplemval on o47_codsup = orcsuplem.o46_codsup 
                                      and orcsuplemval.o47_valor > 0
              inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                      and orcsuplemtipo.o48_superavit ='t'
          where o39_codproj=$projeto
         ";
  $res= $auxiliar->sql_record($sql); 
  if ($auxiliar->numrows > 0 ) {
      $tem_superavit = true;
      ////////// superavit financeiro
           ///////////////////////////

      // pega o texto da ultima informação
      /*
      $r_obs = $clorcsuplem->sql_record("select max(o46_codsup) as mcodsup from orcsuplem where o46_codlei=$projeto"); 	   
      if ($clorcsuplem->numrows > 0){
	 db_fieldsmemory($r_obs,0);
         $rr_obs = $clorcsuplem->sql_record("select o46_obs from orcsuplem where o46_codsup=$mcodsup"); 	   
         if ($clorcsuplem->numrows > 0){
  	    db_fieldsmemory($rr_obs,0);
            $texto_observacao_superavit = $o46_obs;
         };
      };
      */
      $sql = "select o39_texto
              from orcprojeto
	      where o39_codproj=$projeto ";
	      $res= $auxiliar->sql_record($sql);
	      db_fieldsmemory($res,0);
	      $txt= pg_result($res,0,"o39_texto");
			   
      //      $text_observacao_superavit = pg_result($res,0,"text_observacao_superavit");	   
      $pdf->Ln(4);
      $artigo = $artigo +1;
      $txt="$txt";
      $pdf->setX(20);	 
      
      $pdf->multicell(170,4,$txt,'0','J','0',20);
      $pdf->Ln(4);
      ////////////////////
      /*
      for ($x=0;$x < $auxiliar->numrows ;$x++){
	    db_fieldsmemory($res,$x);
            db_query("BEGIN");
            $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot and o58_anousu =$o47_anousu ");
	    db_query("ROLLBACK");
            if(pg_numrows($r_dot)>0){
                db_fieldsmemory($r_dot,0,true);
	        $pdf->setX(20);         
                $pdf->Cell(120,4,"$o58_codigo - $o15_descr",0,0,"L",'0');  
  	        $pdf->Cell(30,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  
	        $pdf->setX(20);   
	        $pdf->Ln();
           } 		          
      }*/      
  }

 /// reducoes
 $sql = "select 
              o39_codproj,
              o46_codsup,
              o46_tiposup,
              o48_descr,
              o47_coddot,
              o47_anousu,
              fc_estruturaldotacao(o47_anousu,o47_coddot) as estrutural,
	      o47_valor
         from orcprojeto
              inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
              inner join orcsuplemval on o47_codsup = orcsuplem.o46_codsup 
                                      and orcsuplemval.o47_valor < 0
              inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                      and orcsuplemtipo.o48_coddocred >  0
          where o39_codproj=$projeto
         ";
 //echo $sql;exit;        
$res= $auxiliar->sql_record($sql);
// db_criatabela($res);

     //////////  artigo + 1 , paragrafo das reduções
          ////////////////////////////////////////////////	  
     $pdf->Ln(4);
     if ($tem_superavit == false){
       $artigo = $artigo +1;
       $txt="Art $artigo. -  Para cobertura do $tipo_sup aberto no artigo primeiro ".
             "será usado como recurso, as seguintes reduções orçamentárias :";
       $sqltxt = "select o39_texto
              from orcprojeto
	      where o39_codproj=$projeto ";
	     $resTxt= db_query($sqltxt);
	     db_fieldsmemory($resTxt,0);
       if ($o39_texto != ''){
  	      $txt= $o39_texto;
       }
       $pdf->setX(20);	 
       $pdf->multicell(170,4,$txt,'0','J','0',20);
       $pdf->Ln(4);
     }
     /////// imprime reduções
          ///////////////////////////////////////////////	 
    if ($auxiliar->numrows>0 ) {
      $total = 0;
      for ($x=0;$x < $auxiliar->numrows ;$x++){
	    db_fieldsmemory($res,$x);
  	    $pdf->setX(50);         
            $pdf->Cell(70,4,$estrutural,0,0,"L",'0');  
      $pdf->Cell(20,4," ( $o47_coddot ) ",0,0,"L",'0');  
      $pdf->Cell(30,4,db_formatar(abs($o47_valor),'f'),0,1,"R",'0');  
      $pdf->setX(20);  
	    $total += abs($o47_valor);
	    $pdf->Ln();
      }      
    }  
 
 //////////////////////////
 /// arrecadacao a maior 
 /////////////////////////
 // arrecadacao de receita

 /// arrecadacao a maior, lista receitas
  $sql = "select 
              o39_codproj,
              o46_codsup,
              o46_tiposup,
              o48_descr,
              o57_descr,
              o85_codrec,
              o85_anousu,
        o85_valor
         from orcprojeto
              inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
              inner join orcsuplemrec on o85_codsup = orcsuplem.o46_codsup 
        inner join orcreceita   on o70_codrec = orcsuplemrec.o85_codrec
                               and o70_anousu = orcsuplemrec.o85_anousu
              inner join orcfontes on o57_codfon  =   orcreceita.o70_codfon and o57_anousu = orcsuplemrec.o85_anousu             
              inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                      and orcsuplemtipo.o48_arrecadmaior >  0
          where o39_codproj=$projeto
         ";
          
   $sSqlPPA = "select 
              o39_codproj,
              o46_codsup,
              o46_tiposup,
              o48_descr,
              o57_descr,
              0 as o85_codrec,
              o06_anousu,
              o137_valor
         from orcprojeto
              inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
              inner join orcsuplemreceitappa  on o137_orcsuplem = orcsuplem.o46_codsup 
              inner join ppaestimativareceita on o137_ppaestimativareceita = o06_sequencial
              inner join orcfontes on o57_codfon  =   o06_codrec and o57_anousu = o06_anousu             
              inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                      and orcsuplemtipo.o48_arrecadmaior >  0
          where o39_codproj=$projeto
         ";

 $res= $auxiliar->sql_record($sql." union all {$sSqlPPA}"); 
 if ($auxiliar->numrows > 0 ) {
      // imripme receita, e valor
      //////////  artigo + 1 , paragrafo das receitas 
          ////////////////////////////////////////////////
      $pdf->Ln(4);
//      $artigo = $artigo +1;
//      $txt="Art $artigo. -  Para cobertura do $tipo_sup aberto no artigo primeiro ".
//           "será usado como recurso, as receitas conforme a seguinte classificação: ";
//      $pdf->setX(20);	 
//      $pdf->multicell(170,4,$txt,'0','J','0',20);
//     $pdf->Ln(4);
      //////// imprime receitas
          ///////////////////////////////////////////////	  
       for ($x=0;$x < $auxiliar->numrows ;$x++){
	     db_fieldsmemory($res,$x);
 	     $pdf->setX(50);
       $pdf->Cell(90,4,"$o57_descr ( $o85_codrec )",0,0,"L",'0');  
       $pdf->Cell(30,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  
       $total += $o85_valor;
	     $pdf->setX(20);   
	     $pdf->Ln();
       }      
  }  
  
  /*
   * Total da receita
   */
  /*
  $pdf->Cell(130,4,'',0,0,"L",'0');  
  $pdf->Cell(30,4,db_formatar(abs($total),'f'),"T",1,"R",'0');  
  $pdf->setX(20);  */ 

  //////////  artigo + 1 , paragrafo das receitas 
        ////////////////////////////////////////////////
   $pdf->Ln(7);	
   $artigo = $artigo +1;
   $txt="Art $artigo. - Revogam-se as disposições em contrário.";
   $pdf->setX(40);	 
   $pdf->multicell(170,4,$txt,'0','J','0',20);
 
   $pdf->Ln(7);	
   $artigo = $artigo +1;
   $txt="Art $artigo. - Est".($projeto_tipo == 1?'e decreto':'a lei')." entrará em vigor na data de sua publicação.";
   $pdf->setX(40);	 
   $pdf->multicell(170,4,$txt,'0','J','0',20);
   $pdf->Ln(5);    
   $txt = "GABINETE DO PREFEITO MUNICIPAL DE ".strtoupper($munic)." AOS ".substr($xdata,8,2)." DIAS DO MÊS DE ".strtoupper(db_mes(substr($xdata,5,2)))." DE ".substr($xdata,0,4).".";
   $pdf->multicell(180,4,$txt,'0','J','0',10);
   $pdf->Ln(3);    
   $pdf->multicell(180,4,"REGISTRE-SE E PUBLIQUE-SE:",'0','L','0',10);
 
/*
   if ($projeto_tipo == "1"){
  // texto de sapiranga
      $pdf->Ln(10);
      $artigo = $artigo +1;
      $txt="Art $artigo. - Este Decreto entrara em vigor na data de sua publicação.";
      $artigo += 1;
      $pdf->setX(40);
      $pdf->multicell(170,4,$txt,'0','J','0',20);

      $pdf->Ln(5);    
      $txt = "GABINETE DO PREFEITO MUNICIPAL DE ".strtoupper($munic)." AOS ".date('d')." DIAS DO MÊS DE ".strtoupper(db_mes(date('m')))." DE ".date('Y').".";
      $pdf->multicell(180,4,$txt,'0','J','0',20);
      $pdf->Ln(10);    
      $pdf->multicell(0,4,$pref."\n"."PREFEITO MUNICIPAL",'0','C','0');
      $pdf->Ln(10);    
      $pdf->multicell(0,4,"Registre-se e cumpra-se",'0','L','0');
      $pdf->multicell(0,4,"\n\n\n"."FERNANDO FERREIRA DA CUNHA"."\n"."Secretario Municipal de Administração",'0','L','0');
   }

*/
 if (($projeto_tipo == "1")&& strtoupper(trim($munic)) == "ELDORADO DO SUL"){

      $faz = "";
      $adm = "";
      $ass_faz = $classinatura->assinatura(1002,$faz);
      $ass_adm = $classinatura->assinatura(1003,$adm);

    //  $pdf->Ln(5);    
    //  $txt = "GABINETE DO PREFEITO MUNICIPAL DE ".strtoupper($munic)." AOS ".substr($xdata,8,2)." DIAS DO MÊS DE ".strtoupper(db_mes(substr($xdata,5,2)))." DE ".substr($xdata,0,4).".";
    //  $pdf->multicell(180,4,$txt,'0','J','0',10);
   //   $pdf->Ln(3);    
      if ($pdf->gety() > $pdf->h - 60 ){
        $pdf->addpage();
      }
    //  $pdf->multicell(180,4,"REGISTRE-SE E PUBLIQUE-SE:",'0','L','0',10);
      $pdf->Ln(10);   
      $pdf->setx(30);       
      $pdf->multicell(160,4,$pref."\n"."Prefeito Municipal",'0','C','0');
      $linha = $pdf->gety();
      $pdf->multicell(100,4,"\n\n".ucfirst($ass_adm),'0','C','0');
      $pdf->sety($linha);       
      $pdf->setx(100);       
      $pdf->multicell(100,4,"\n\n".ucfirst($ass_faz),'0','C','0');
   }


 $pdf->Output();
?>