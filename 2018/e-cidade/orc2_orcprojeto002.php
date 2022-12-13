<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
 $cldbconfig    = new cl_db_config;
 $cldbparagrafo = new cl_db_paragrafo;
 $clorcsuplem    = new cl_orcsuplem;
 $auxiliar = new cl_orcsuplem;
 $aux      = new cl_orcsuplem;
  
 parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
 
 $anousu = db_getsession("DB_anousu");
 $projeto = (isset($o46_codlei)&&!empty($o46_codlei))?$o46_codlei:'null';
 $ano_anterior = ($anousu -1);
 $tem_superavit = false;

///////////////////////////////////////////
// defini a classe abaixo pra poder tirar o timpre conforme o caso 
class PDF_TIMBRE extends pdf1 {	
	function Header() {        
         $this->Ln(45);            		
	}
	function Footer() {        
         $this->Ln(45);            		
	}
}
///////////////////////////////////////////
if ($timbre =='s'){
   $pdf = new PDF1();
} else {
	// a classe abaixo sobrescreve a funcao Header() sem implementacao
   $pdf = new PDF_TIMBRE();
}    
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
  $sql = "select  
                o48_tiposup,
                o46_data,
       		    o39_data
          from orcprojeto
                inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
                inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                               and orcsuplemtipo.o48_coddocsup >  0        				      
          where o39_codproj=$projeto 
	  order by o46_data
	  limit 1
         ";
  $res= $auxiliar->sql_record($sql); 
  db_fieldsmemory($res,0); 
//  db_criatabela($res);exit;
  $xtipo = $o48_tiposup;
  // $xdata = $o46_data;
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
                 o39_data,
                 o39_lei,
    						 o39_leidata,
    						 exists(select 1 
    						          from orcsuplem b
    						              inner join orcsuplemlan on b.o46_codsup = o49_codsup
    						        where b.o46_codlei={$projeto}) as processado,
                 o39_compllei,
                 o45_numlei,      
                 date_part('year',o45_dataini)  as ano_lei		 
           from orcprojeto
                inner join orclei on  o45_codlei   = orcprojeto.o39_codlei
                inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
                left  join orcprojetoorcprojetolei on o39_codproj = o139_orcprojeto
                inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                      and orcsuplemtipo.o48_coddocsup >  0        				      
         where o39_codproj=$projeto
	       group by o139_orcprojeto,o39_numero,o39_data,o39_lei,o39_compllei,o39_leidata,o45_numlei, ano_lei 
         ";
  $res= $auxiliar->sql_record($sql);
  //db_criatabela($res);exit;
   
  if ($auxiliar->numrows > 0 ){
       db_fieldsmemory($res,0,true); 
       global $projeto_tipo,$total_suplementado,$o39_numero,$o39_data,$o39_descr,$o39_lei,$o39_leidata,$o45_numlei;
  } else {
       db_redireciona('db_erros.php?fechar=true&db_erro=(Ln:115) Nenhum registro encontrado.');
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
         
    if ($projeto_tipo == "1"){
          $projeto_tipo_texto ="DECRETO";
          $txt="Abre $tipo_sup na importancia de ".
         "R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true).") e da outras providências. ";
          
    }else if ($projeto_tipo == "2") {
       $projeto_tipo_texto ="PROJETO DE LEI";
       $txt="Autoriza o Poder Executivo Municipal a abrir $tipo_sup na importancia de ".
         "R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true).") e da outras providências. ";
    }else {
    	// tipo 3 = retificador
    	if   (strlen(trim($o39_lei))>0) {
    		  $projeto_tipo_texto ="PROJETO DE LEI";
              $txt="Autoriza o Poder Executivo Municipal a abrir $tipo_sup na importancia de ".
              "R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true).") e da outras providências. ";
    	} else {
    	      $projeto_tipo_texto ="DECRETO ".$o39_numero;
              $txt="Abre $tipo_sup na importancia de ".
              "R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true).") e da outras providências. ";
    	}
    }	
   
    $pdf->setX(20);     
    //$pdf->Cell(170,4,$projeto_tipo_texto." ".($projeto_tipo == 1?$o39_numero."/".substr($o39_data,6,4):''),0,1,"C",'1');  
    $pdf->Cell(170,4,$projeto_tipo_texto." ".($projeto_tipo == 1?$o39_numero:'').strtoupper(" de ".substr($o39_data,0,2)." de ".db_mes(substr($o39_data,3,2))." de ".substr($o39_data,6,4)),0,1,"C",'1');
    $pdf->Ln(7);	
    
  /*
   * 
   * caso este projeto tenha sido reretificado por algum outro , coloca esta informação aqui
   */
    $sql = "select o48_projeto,o48_data,o39_numero,o39_data
                 from orcsuplemretif
                        inner join orcprojeto on o48_projeto =o39_codproj 
                 where o48_retificado = $projeto
                ";
     $res_retif = db_query($sql);
     if (pg_numrows($res_retif)>0){
     	 db_fieldsmemory($res_retif,0,true);   
         $pdf->setX(20);	 
         $pdf->multicell(170,4,"Este projeto foi retificado pelo projeto $o48_projeto em $o48_data referente ao Decreto/Lei $o39_numero de $o39_data",'B','J','0',20);
         $pdf->Ln(4); 
     }
  /*
   * 
   * caso este projeto tenha sido reretificado por algum outro , coloca esta informação aqui
   */
    $sql = "select o48_texto
                 from orcsuplemretif
                      inner join orcprojeto on o48_retificado =o39_codproj 
                 where o48_projeto = $projeto
                ";
     $res_retif = db_query($sql);
     if (pg_numrows($res_retif)>0){
     	 db_fieldsmemory($res_retif,0,true);
     	 if (strlen($o48_texto) >1 ){
              $pdf->setX(20);	 
              $pdf->multicell(170,4,"$o48_texto",'B','J','0',20);
              $pdf->Ln(4); 
     	 }
     }
   
      
    
//    $txt="Autoriza o Poder Executivo Municipal a abrir $tipo_sup na importancia de ".
//         "R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true).") e da outras providências. ";
    $pdf->setX(100);
    $pdf->multicell(90,4,$txt,'0','J','0',20); 
    $pdf->Ln(7);
     
      
    if ($projeto_tipo == "1"){ // decreto    
       $res= $cldbconfig->sql_record($cldbconfig->sql_query(db_getsession("DB_instit")));
       db_fieldsmemory($res,0);
       $pdf->setX(20);
       $pref = ucfirst($pref);
      // $pref = 'VIVIAN LITIA FLORES DA SILVA';
       
     //  $txt="$pref, PREFEITA MUNICIPAL EM EXERCÍCIO DE $munic, $uf, no uso de suas atribuições legais e de conformidade com a Lei Municipal $o45_numlei";
       if ( $db21_codcli == 34 ) {
         $txt="$pref, PRESIDENTE DA CAMARA MUNICIPAL DE VEREADORES DE $munic, $uf, no uso de suas atribuições legais e de conformidade com a Lei Municipal n" . chr(186) ." $o45_numlei";
       } else {
         $txt="$pref, PREFEITO MUNICIPAL DE $munic, $uf, no uso de suas atribuições legais e de conformidade com a Lei Municipal $o45_numlei";
       }
       if($o39_compllei != ""){
         $txt .= ", $o39_compllei, DECRETA:";
       }else{
         $txt .= " DECRETA:";
       }
       $pdf->multicell(170,4,$txt,'0','J','0');
       $pdf->Ln(7);	     
       $artigo = $artigo +1;
       $txt="Art $artigo. - Fica aberto $tipo_sup ".
                "na importância de  R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true)." ) ".
	            "sob a seguinte classificação econômica e programática ";
    } else {   // quando for lei
       $res = $cldbconfig->sql_record($cldbconfig->sql_query(db_getsession("DB_instit")));
       db_fieldsmemory($res,0);       
       
       $pdf->setX(20);
       $pref = strtoupper($pref);
       if ( $db21_codcli == 34 ) {
         $txt="$pref, PREFEITO MUNICIPAL DE $munic, $uf.";
       } else {
         $txt="$pref, PRESIDENTE DA CAMARA MUNICIPAL DE VEREADORES DE $munic, $uf.";
       }
       $pdf->multicell(170,4,$txt,'0','J','0');
       $pdf->Ln(7);	     
       $pdf->setX(20);
       $txt="FAÇO SABER, que a Camara Municipal aprovou e eu sanciono a seguinte Lei: ";
       $pdf->multicell(170,4,$txt,'0','J','0');
       $pdf->Ln(7);	     
       $artigo = $artigo +1;
       $txt="Art $artigo. -  Fica o Poder Executivo Municipal autorizado a abrir $tipo_sup ".
            "na importância de  R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true)." ) ".
	    "sob a seguinte classificação econômica e programática ";
    }
    
    
////////// primeiro artigo, das suplementações
//       $artigo = $artigo +1;
//    $txt="Art $artigo. -  Fica o Poder Executivo Municipal autorizado a abrir $tipo_sup ".
//         "na importância de  R$ ".db_formatar($total_suplementado,'f')." (".db_extenso($total_suplementado,true)." ) ".
//	 "sob a seguinte classificação econônica e programatica ";
    $pdf->setX(20);	 
    $pdf->multicell(170,4,$txt,'0','J','0',20);
    $pdf->Ln(4);


  // seleciona suplementacoes do projeto
  // executa o mesmo select, só que agora pra listar as suplementações
  $sql="select  o46_tiposup,
			          o48_descr,
			          o47_coddot,
			          o47_anousu,
				        o58_orgao,
				        o40_descr,
				        o58_unidade,
                o56_elemento,
                o56_descr,
                o58_projativ,
                o55_descr,
                o41_descr,
                o15_codigo,
                o15_descr,
				        sum(o47_valor) as o47_valor
           from orcprojeto
		            inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
		            inner join orcsuplemval on o47_codsup = orcsuplem.o46_codsup 
		                                   and orcsuplemval.o47_valor > 0
			          inner join orcdotacao  on o58_coddot  = o47_coddot and o58_anousu = ".db_getsession("DB_anousu")."
                inner join orcelemento on o58_codele  = o56_codele and o56_anousu = ".db_getsession("DB_anousu")."
                inner join orcorgao    on o58_orgao   = o40_orgao  and o40_anousu = ".db_getsession("DB_anousu")."
                inner join orcunidade  on o58_unidade = o41_unidade and o41_anousu = ".db_getsession("DB_anousu")."
                                      and o41_orgao   = o58_orgao
                inner join orctiporec on o15_codigo   = o58_codigo                                       
                inner join orcprojativ on o58_projativ  = o55_projativ  and o55_anousu = ".db_getsession("DB_anousu")."
		            inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
		                                      and orcsuplemtipo.o48_coddocsup >  0        				      
         	where o39_codproj=$projeto
	     	 group by o47_coddot,
	               o46_tiposup,
							   o48_descr,
							   o40_descr,
							   o47_anousu,
                 o58_projativ,
                 o55_descr,
                 o56_descr,
							   o58_orgao,
							   o58_unidade,
                 o56_elemento,
                 o41_descr,
                o15_codigo,
                o15_descr ";
	  	 	
	  	 $sSqlDotacaoPPA = "select  o46_tiposup,
                o48_descr,
                0 as coddot,
                o08_ano,
                o08_orgao,
                o40_descr,
                o08_unidade,
                o56_elemento,
                o56_descr,
                o08_projativ,
                o55_descr,
                o41_descr,
                o15_codigo,
                o15_descr,
                sum(o136_valor) as o47_valor
           from orcprojeto
                inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
                inner join orcsuplemdespesappa on o136_orcsuplem = orcsuplem.o46_codsup 
                inner join ppaestimativadespesa on o07_sequencial = o136_ppaestimativadespesa 
                inner join ppadotacao  on o07_coddot   = o08_sequencial 
                inner join orcelemento on o08_elemento = o56_codele and o56_anousu = o08_ano
                inner join orcorgao    on o08_orgao    = o40_orgao  and o40_anousu = o08_ano
                inner join orcunidade  on o08_unidade = o41_unidade and o41_anousu = o08_ano
                                      and o41_orgao   = o08_orgao
                inner join orctiporec on o15_codigo   = o08_recurso
                inner join orcprojativ on o08_projativ  = o55_projativ  and o55_anousu = o08_ano
                inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                          and orcsuplemtipo.o48_coddocsup >  0                      
          where o39_codproj=$projeto
         group by 3,
                 o46_tiposup,
                 o48_descr,
                 o40_descr,
                 o56_descr,
                 o08_ano,
                 o08_projativ,
                 o55_descr,
                 o08_orgao,
                 o08_unidade,
                 o56_elemento,
                 o41_descr,
                o15_codigo,
                o15_descr
        order by o58_orgao,o58_unidade,o58_projativ,o56_elemento ";
  $res= $auxiliar->sql_record($sql." union all {$sSqlDotacaoPPA}");  
   // db_criatabela($res);exit;
  $total = 0;
  if ($auxiliar->numrows > 0 ){
      for ($x=0;$x < $auxiliar->numrows ;$x++){

        db_fieldsmemory($res,$x);
		    $pdf->setX(20);
        $pdf->Cell(150,4,db_formatar($o58_orgao,'orgao')."00 - $o40_descr",0,1,"L",'0');  
		    $pdf->setX(20);    
        $pdf->Cell(150,4,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao')." -  $o41_descr",0,1,"L",'0');  
		    $pdf->setX(20);	    
        $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  
		    $pdf->setX(20);   
        $pdf->Cell(150,4,db_formatar($o56_elemento,'elemento')." - ".$o56_descr,0,1,"L",'0');     
		    $pdf->setX(20);         
        $pdf->Cell(120,4,db_formatar($o15_codigo,'recurso')." - ".trim($o15_descr)." ( $o47_coddot ) ",0,0,"L",'0');  
  	    $pdf->Cell(50,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  
		    $pdf->setX(20);   
		    $total += $o47_valor;
		    $pdf->Ln();
     }      
      $pdf->Cell(130,4,'',0,0,"L",'0');  
      $pdf->setX(160);
      $pdf->Cell(30,4,db_formatar($total,'f'),"T",1,"R",'0');  
      $pdf->setX(20);   
  }

 /// reducoes
 /// entram como reduções as reduções, receitas e o texto do projeto quando superávit
 /// 
 //-- texto do artigo 2
 $sql = "select o39_texto
         from orcprojeto
         where o39_codproj=$projeto ";
 $res= $auxiliar->sql_record($sql);
 db_fieldsmemory($res,0); 
   $pdf->Ln(4);
   $txt= pg_result($res,0,"o39_texto");
   $pdf->setX(20);	 
   $pdf->multicell(170,4,$txt,'0','J','0',20);
   $pdf->Ln(4);

 //-------
 $sql = "select 
              o39_codproj,
	      o39_texto,
              o48_descr,
	      o58_orgao,
	      o58_unidade,
	      o58_projativ,
              o47_coddot, 
              o47_anousu,
	      sum(o47_valor) as o47_valor
         from orcprojeto
              inner join orcsuplem on o46_codlei = orcprojeto.o39_codproj
              inner join orcsuplemval on o47_codsup = orcsuplem.o46_codsup 
                                      and orcsuplemval.o47_valor < 0
              inner join orcdotacao on o58_coddot=o47_coddot and 
	                               o58_anousu=o47_anousu
              inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
                                      and orcsuplemtipo.o48_coddocred >  0
         where o39_codproj=$projeto
	 group by o39_codproj,
                  o39_texto,
	          o48_descr,

		  o58_orgao,
		  o58_unidade,
                  o58_projativ,
	          o47_coddot,
	          o47_anousu
         order by o58_orgao,o58_unidade,o58_projativ
         ";
 $res= $auxiliar->sql_record($sql);
 $tem_reduz = 0;
 if ($auxiliar->numrows>0 ) {
     //////////  artigo 2, paragrafo das reduções
          ////////////////////////////////////////////////	    
    /////// imprime reduções  ///////////////////////////////////////////////	 
      $total = 0;
      $tem_reduz = 1;
      for ($x=0;$x < $auxiliar->numrows ;$x++){
	     db_fieldsmemory($res,$x);
             db_query("BEGIN");
             $r_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o47_coddot and o58_anousu =$o47_anousu ");
	     db_query("ROLLBACK");
             if(pg_numrows($r_dot)>0){
                  db_fieldsmemory($r_dot,0,true);
		  $pdf->setX(20);
                  $pdf->Cell(150,4,db_formatar($o58_orgao,'orgao')."00 - $o40_descr",0,1,"L",'0');  
		  $pdf->setX(20);    
                  $pdf->Cell(150,4,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao')." - $o41_descr",0,1,"L",'0');  
		  $pdf->setX(20);	    
                  $pdf->Cell(150,4,"$o58_projativ - $o55_descr",0,1,"L",'0');  
		  $pdf->setX(20);   
                  $pdf->Cell(150,4,db_formatar($o58_elemento,'elemento')." - ".$o56_descr,0,1,"L",'0');     
		  $pdf->setX(20);         
                  $pdf->Cell(120,4,db_formatar($o58_codigo,'recurso')." - ".trim($o15_descr)." ( $o47_coddot ) ",0,0,"L",'0');  
		  $o47_valor =$o47_valor*-1;
  	          $pdf->Cell(50,4,db_formatar($o47_valor,'f'),0,1,"R",'0');  
		  $pdf->setX(20);  
		  $total += $o47_valor;
		  $pdf->Ln();
             } 		          
	     
      }      
 
  }  
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
   // db_criatabela($res);
   if ($auxiliar->numrows > 0 ) {
       ///////////////////////////////////////////////	  
       for ($x=0;$x < $auxiliar->numrows ;$x++){
	       db_fieldsmemory($res,$x);
 	       $pdf->setX(20);
         $pdf->Cell(120,4,"$o85_codrec - $o57_descr (arrecadação à maior)",0,0,"L",'0');  
	       //$pdf->setX(20);    
  	     $pdf->Cell(50,4,db_formatar($o85_valor,'f'),0,1,"R",'0');  
         $total += $o85_valor;
	       $pdf->setX(20);   
	       $pdf->Ln();
       }      
   }
   if($tem_reduz == 1){
      // -- imprime total das reduções
      $pdf->Cell(130,4,'',0,0,"L",'0');  
      $pdf->Cell(50,4,db_formatar($total,'f'),"T",1,"R",'0');  
      $pdf->setX(20);   
   }
   ////////////////////////////////////////////////
   $pdf->Ln(7);	
   $artigo = 2;
   $artigo = $artigo +1;
   $txt="Art $artigo. - Revogam-se as disposições em contrário.";
   $pdf->setX(40);	 
   $pdf->multicell(170,4,$txt,'0','J','0',20);
 
   $pdf->Ln(7);	
   $artigo = $artigo +1;
   $txt="Art $artigo. - Est".($projeto_tipo == 1?'e decreto':'a lei')." entrará em vigor na data de sua publicação.";
   $pdf->setX(40);	 
   $pdf->multicell(170,4,$txt,'0','J','0',20);
 
   if ($projeto_tipo == "1" && strtoupper(trim($munic)) == "SAPIRANGA"){
   // texto de sapiranga
//      $pdf->Ln(10);
//      $artigo = $artigo +1;
//      $txt="Art $artigo. - Este Decreto entrara em vigor na data de sua publicação.";
//      $artigo += 1;
//      $pdf->setX(40);
//      $pdf->multicell(170,4,$txt,'0','J','0',20);

      $sec =  "";
      $ass_sec = $classinatura->assinatura(1006,$sec);

      $pdf->Ln(5);    
      //$txt = "GABINETE DO PREFEITO MUNICIPAL DE ".strtoupper($munic)." AOS ".substr($xdata,8,2)." DIAS DO MÊS DE ".strtoupper(db_mes(substr($xdata,5,2)))." DE ".date('Y').".";
      $txt = "GABINETE DO PREFEITO MUNICIPAL DE ".strtoupper($munic)." AOS ".substr($xdata,8,2)." DIAS DO MÊS DE ".strtoupper(db_mes(substr($xdata,5,2)))." DE ".substr($xdata,0,4).".";
      $pdf->multicell(180,4,$txt,'0','J','0',20);
      $pdf->Ln(10);    
      $pdf->multicell(0,4,$pref."\n"."PREFEITO MUNICIPAL",'0','C','0');
      $pdf->Ln(10);    
      $pdf->multicell(0,4,"Registre-se e cumpra-se",'0','L','0');
  //    $pdf->multicell(0,4,"\n\n\n"."FERNANDO FERREIRA DA CUNHA"."\n"."Secretario Municipal de Administração",'0','L','0');
      $pdf->multicell(0,3,"\n\n\n".strtoupper($ass_sec),'0','L','0');
   }else if ($projeto_tipo == "1" && strtoupper(trim($munic)) == "BAGE"){
   // texto de sapiranga
//      $pdf->Ln(10);
//      $artigo = $artigo +1;
//      $txt="Art $artigo. - Este Decreto entrara em vigor na data de sua publicação.";
//      $artigo += 1;
//      $pdf->setX(40);
//      $pdf->multicell(170,4,$txt,'0','J','0',20);

      $sec =  "";
      $ass_sec = $classinatura->assinatura(1002,$sec);

      $pdf->Ln(5);    
      $txt = "GABINETE DO PREFEITO MUNICIPAL DE ".strtoupper($munic).", ".substr($xdata,8,2)." DE ".strtoupper(db_mes(substr($xdata,5,2)))." DE ".substr($xdata,0,4).".";
      $pdf->cell(30,4,'','0','J','0');
      $pdf->multicell(180,4,$txt,'0','J','0');
      $pdf->Ln(10);    
      $pdf->multicell(0,4,$pref."\n"."PREFEITO MUNICIPAL",'0','C','0');
  //    $pdf->multicell(0,4,"\n\n\n"."FERNANDO FERREIRA DA CUNHA"."\n"."Secretario Municipal de Administração",'0','L','0');
      $pdf->multicell(0,3,"\n\n\n".strtoupper($ass_sec),'0','L','0');
      $pdf->Ln(10);    
      $pdf->multicell(0,4,"Registre-se e cumpra-se",'0','L','0');
   }else if ($projeto_tipo == "1" && strtoupper(trim($munic)) == "ARROIO DO SAL"){

      $sec =  "";
      $ass_sec = $classinatura->assinatura(1002,$sec);

      $pdf->Ln(5);    
      $txt = "GABINETE DO PREFEITO MUNICIPAL DE ".strtoupper($munic).", ".substr($xdata,8,2)." DE ".strtoupper(db_mes(substr($xdata,5,2)))." DE ".substr($xdata,0,4).".";
      $pdf->cell(30,4,'','0','J','0');
      $pdf->multicell(180,4,$txt,'0','J','0');
      $pdf->Ln(10);    
      $pdf->multicell(0,4,$pref."\n"."PREFEITO MUNICIPAL ",'0','C','0');
      $pdf->multicell(0,3,"\n\n\n".strtoupper($ass_sec),'0','L','0');

   }elseif ($projeto_tipo == "1" && strtoupper(trim($munic)) == "ELDORADO DO SUL"){

      $faz = "";
      $adm = "";
      $ass_faz = $classinatura->assinatura(1002,$faz);
      $ass_adm = $classinatura->assinatura(1003,$adm);

      $pdf->Ln(5);    
      if ( $db21_codcli == 34 ) {
        $txt = "GABINETE DO PRESIDENTE DA CAMARA MUNICIPAL DE VEREADORES DE ".strtoupper($munic)." AOS ".substr($xdata,8,2)." DIAS DO MÊS DE ".strtoupper(db_mes(substr($xdata,5,2)))." DE ".substr($xdata,0,4).".";
      } else {
        $txt = "GABINETE DO PREFEITO MUNICIPAL DE ".strtoupper($munic)." AOS ".substr($xdata,8,2)." DIAS DO MÊS DE ".strtoupper(db_mes(substr($xdata,5,2)))." DE ".substr($xdata,0,4).".";
      }
      $pdf->multicell(180,4,$txt,'0','J','0',10);
      $pdf->Ln(5);    
      if ($pdf->gety() > $pdf->h - 60 ){
        $pdf->addpage();
      }
      $pdf->multicell(180,4,"REGISTRE-SE E PUBLIQUE-SE:",'0','L','0',10);
      $pdf->Ln(10);   
      $pdf->setx(30);       
      if ( $db21_codcli == 34 ) {
        $pdf->multicell(0,4,$pref."\n"."Presidente da Camara Municipal de Vereadores",'0','C','0');
      } else {
        $pdf->multicell(160,4,$pref."\n"."Prefeito Municipal ",'0','C','0');
      }
     // $pdf->multicell(160,4,$pref."\n"."Prefeito(a) Municipal em Exercício ",'0','C','0');
      $linha = $pdf->gety();
      $pdf->multicell(100,4,"\n\n".ucfirst($ass_adm),'0','C','0');
      $pdf->sety($linha);       
      $pdf->setx(100);       
      $pdf->multicell(100,4,"\n\n".ucfirst($ass_faz),'0','C','0');
   }

 $pdf->ln();
 
 // assinaturas
 // include("dbforms/db_assinaturas_balancetes.php");

   
 $pdf->Output();

?>