<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_sql.php");
include("classes/db_parfiscal_classe.php");
include("classes/db_termovist_classe.php");
include("dbforms/db_funcoes.php");
//db_postmemory($HTTP_SERVER_VARS,2);exit;
db_postmemory($HTTP_SERVER_VARS);

$clparfiscal     = new cl_parfiscal;
$cltermovist     = new cl_termovist;
//----------------------- Data atual ----------------------------
$dia = date ('d',db_getsession("DB_datausu"));
$mes = date ('m',db_getsession("DB_datausu"));
$ano = date ('Y',db_getsession("DB_datausu"));
$mes = db_mes($mes);
//----------------------------------------------------------------
//Monta SQL
$int_seq = 0;
$and ="";
$where = " where ";
$sqlerro=false;
$termovist="";
$tipoori="";
$inscrnaoger="";
$naoimprime = false;
if(isset($inscricao) && $inscricao != ""){
  $where .= " $and issbase.q02_inscr = $inscricao ";
  $and = " and ";
}
if((isset($logradouro) && $logradouro != "")){
  $where .= " $and issruas.j14_codigo = $logradouro ";
  $and = " and ";
  $tipoori = "r";
}

if(isset($inscricao) && $inscricao != ""){
  $tipoori = "i";
}

if(isset($classes) && $classes != ""){
  if(isset($tipo) && $tipo == "c"){
    $in = " in ";
  }else{
    $in = " not in ";
  }
  $where .= " $and q82_classe $in ($classes) ";
  $and = " and ";
}
$where .= " $and q02_dtbaix is null 
order by issruas.j14_codigo, issruas.q02_numero ";

$campos  = "           distinct(issbase.q02_numcgm), ";
$campos .= "                      issbase.q02_inscr, ";
$campos .= "                     issruas.j14_codigo, ";
$campos .= "                     issruas.q02_numero, ";
$campos .= "                               z01_nome, ";
$campos .= "                             z01_cgccpf, ";
$campos .= "                              z01_telef, ";
$campos .= "                               q30_area, ";
$campos .= "                              q30_quant, ";
$campos .= "                               q35_zona, ";
$campos .= "                               j50_descr, ";
$campos .= "                              j13_descr, ";
$campos .= "                               j14_nome, ";
$campos .= "                              q02_compl, ";
$campos .= "                               q03_ativ, ";
$campos .= "                               q03_descr ";

$str_sql  = "select $campos from issbase ";
$str_sql .= "left join issruas     on issruas.q02_inscr     = issbase.q02_inscr "; 
$str_sql .= "left join ruas        on ruas.j14_codigo       = issruas.j14_codigo "; 
$str_sql .= "left join issbairro   on issbairro.q13_inscr   = issbase.q02_inscr ";  
$str_sql .= "left join bairro      on issbairro.q13_bairro  = bairro.j13_codi ";  
$str_sql .= "left join cgm         on cgm.z01_numcgm        = issbase.q02_numcgm "; 
$str_sql .= "left join ativprinc   on q88_inscr             = issbase.q02_inscr ";
$str_sql .= "left join tabativ     on q07_inscr             = issbase.q02_inscr 
                                    and             q07_seq = q88_seq ";
$str_sql .= "left join ativid      on q03_ativ              = tabativ.q07_ativ "; 
$str_sql .= "left join clasativ    on q82_ativ              = tabativ.q07_ativ "; 
$str_sql .= "left join issquant    on q30_inscr             = issbase.q02_inscr 
                                    and          q30_anousu = ".db_getsession('DB_anousu')." ";
$str_sql .= "left join isszona     on q35_inscr             = issbase.q02_inscr ";
$str_sql .= "left join zonas       on j50_zona              = q35_zona ";
$str_sql .= $where;

$result = db_query($str_sql);// or 

$int_linhas = pg_num_rows($result);
if($int_linhas == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro para o filtro selecionado!');
  exit;
}

$sSqlInscr = $cltermovist->sql_query_file(null,"y91_inscr",null,
                                          " y91_inscr = $inscricao and y91_exerc = '".db_getsession("DB_anousu")."' ");

$rsTipo = $cltermovist->sql_record($sSqlInscr);

$numtipo = $cltermovist->numrows;
if($numtipo == 0){
  $primeiro = 't';
}else{
  $primeiro = 'f';
}

$head2 = "Termo de Fiscalização";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$geratermo = 'f';
$mostrapdf = 'f';
//ss$mostrapdf = 't'; // linha para teste... tirar depois...


$rsTipo  = null;
$numtipo = null;
//db_msgbox($reemis);
for($cont=0;$cont < $int_linhas;$cont++) {
	db_fieldsmemory($result,$cont);  
  if(isset($reemis) && $reemis == 's' ){
    // ############### SE FOR REEIT = SIM #########################
    // verifica se ja tem termo para q possa ser reemitido

    $rsTermo = $cltermovist->sql_record($cltermovist->sql_query_file(null,"*",null," y91_inscr = $q02_inscr and y91_exerc = '".db_getsession("DB_anousu")."' "));
    $numtipo = $cltermovist->numrows;
    
    if($numtipo==0){
      //          continue;
	    $naoimprime = true;
    }else{
      db_fieldsmemory($rsTermo,0);
      $mostrapdf = 't';
      $head3 = "TERMO DE VISTORIA N° ".$y91_termovist;
    }
    
  }else{
    //  ###########  SE FOR REEMITE = NÃO #########################
    $rsTermotipo = $cltermovist->sql_record($cltermovist->sql_query_file(null,"y91_inscr",null," y91_inscr = $q02_inscr and y91_exerc = '".db_getsession("DB_anousu")."' "));
    $numrowstipo = $cltermovist->numrows;
    // SE NÃO TIVER TERMO DE VITORIA PARA  ESTA INSCRIÇÃO NESTE EXERCÍCIO
    if($numrowstipo==0 && $primeiro == 't'){
      $rsTermovist = db_query("select last_value+1 as termovist from termovist_y91_termovist_seq");
      $numrows = pg_num_rows($rsTermovist);
      if ($numrows>0){
        db_fieldsmemory($rsTermovist,0);
      }
      $head3 = "TERMO DE VISTORIA N° ".$termovist;

      // ROTINA DE INCLUSÃO NA TABELA TERMOVIST

      //      $sqlerro=false;
      db_inicio_transacao();
      $cltermovist->y91_inscr     = $q02_inscr;
      $cltermovist->y91_datatermo = date ('Y',db_getsession("DB_datausu"))."-".date ('m',db_getsession("DB_datausu"))."-".date('d',db_getsession("DB_datausu"));
      $cltermovist->y91_exerc     = db_getsession("DB_anousu");
      $cltermovist->y91_codigo    = $j14_codigo;
      $cltermovist->y91_tipo      = $tipoori;
      $cltermovist->incluir(null);
      if ($cltermovist->erro_status=="0"){
        $sqlerro=true;
        $inscrnaoinc = $virgula.$q02_inscr;
        $virgula = ',';
      }else{
        $mostrapdf='t';
      }
      db_fim_transacao($sqlerro);
    }else{
      //die("ja temmmmmmm");
      $naoimprime = true;
    }
  }
  //=============================================================================
  if($naoimprime!=true){
    $pdf->addpage();
    $pdf->setfillcolor(235);
    $pdf->setfont('Arial','',10);
    $col = 28;
    $sqlparag = "select * from db_documento
  inner join db_docparag on db03_docum = db04_docum
  inner join db_tipodoc on db08_codigo  = db03_tipodoc
  inner join db_paragrafo on db04_idparag = db02_idparag 
  where db03_tipodoc = 1013 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
    //die($sqlparag);
    $resparag = db_query($sqlparag);
    if ( pg_numrows($resparag) == 0 ) {
      db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento do Termo de Vistoria!');
      exit;
    }
    $numrows = pg_numrows($resparag);
    //$pdf1->inicia     = $db02_inicia;
    $pdf->setx($col);
    $pdf->setfont('Arial','B',14);
    $pdf->cell(155,10,$head3,0,1,"C",0); //$pdf->cell(155,10,"TERMO DE VISTORIA N° ".$y91_termovist,0,1,"C",0);
    $pdf->setfont('Arial','',10);
    
    for($i=0;$i<$numrows;$i++){
      db_fieldsmemory($resparag,$i);
      if ($i==0){
        $pdf1->inicia     = $db02_inicia;
      }
      if (isset($db02_descr) && $db02_descr == 'TERMO VISTORIA PARAG 1'){
        $pdf->setx($col);
        
        //$pdf->MultiCell(170,4,"          ".db_geratexto($db02_texto),0,"J");
        //Informa??es
        
        /*
         * VARIÁVEIS DO PARAGRAFO 
         */
        if ( trim($z01_telef) != '' ) {
          $telefone = $z01_telef;                
        } else {
          $telefone = "____________";   
        }
                
        if ( trim($q30_area) != '' ) {
          $area = $q30_area;                
        } else {
          $area = "____________";   
        }        
        
        if ( trim($q30_quant) != '' ) {
          $nfuncionarios = $q30_quant;                
        } else {
          $nfuncionarios = "____________";   
        }

        if ( trim($q35_zona) != '' ) {
          $zona = $q35_zona.' - '.$j50_descr;                
        } else {
          $zona = "____________";   
        } 
        
        $pdf->roundedrect($col,$pdf->gety()+2,150+20,75,2,'1234');
        $pdf->setfont('Arial','B',10);
        $pdf->setx($col);
        $pdf->cell(155,5,"",0,1,"L",0);
        $pdf->setx($col);
        $pdf->cell(65,5,"CONTRIBUINTE : ",0,0,"L",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(100,5,$z01_nome,0,1,"L",0);
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"INSCRIÇÃO : ",0,0,"L",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(20,5,$q02_inscr,0,0,"L",0);
	      $pdf->setfont('Arial','B',10);
        $pdf->cell(22,5,"CPF/CNPJ : ",0,0,"L",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(100,5,$z01_cgccpf,0,1,"L",0);
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"LOCALIZAÇÃO LICENCIADA : ",0,0,"L",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(100,5,$j13_descr.','.$j14_nome .' N:'.$q02_numero.'/'.$q02_compl,0,1,"L",0);
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"ATIVIDADE LICENCIADA : ",0,0,"J",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(100,5,$q03_ativ.' - '.$q03_descr,0,1,"J",0);
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"CONDIÇÕES DE FUNCIONAMENTO : ",0,0,"J",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(100,5,"ATIVO(  ) INATIVO(  ) INEXISTENTE(  ) OUTROS(  )",0,1,"J",0);
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"SITUAÇÃO FINANCEIRA : ",0,0,"J",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(100,5,"Posição em ".date( "d/m/Y"),0,1,"J",0);

        $str_sql = "select * from ISSCALC where q01_inscr = $q02_inscr order by q01_anousu desc ";
        $res_isscalc = db_query( $str_sql ) or die( "FALHA $str_sql");
        $int_linhas_isscalc = pg_num_rows( $res_isscalc );
        $str_alvara = "";
        $str_sanitario = "";
        $str_issqn = "";

        for( $y=0; $y<$int_linhas_isscalc; $y++ ){
          db_fieldsmemory( $res_isscalc, $y );
          if( $q01_cadcal == 1  and empty( $str_alvara )){ //6  	ALVAR?
            //  $str_alvara = func_alvara( $q01_numpre );
             
          }
          else if( $q01_cadcal == 6 and empty( $str_sanitario ) ){ //6  	ALVAR? SANIT?RIO
            //    $str_sanitario = func_alvara( $q01_numpre );
          }
          else if( ( $q01_cadcal == 2 or $q01_cadcal == 3 ) and empty( $str_issqn ) ){ //2 ISS FIXO - 3 ISS VAR
            $str_issqn = func_issqn( $q01_numpre, $q01_cadcal );
          }
        }



        //************** TFV... alvara **************
        $sqltfv = "
		select y69_numpre,y71_codvist as visttfv
		from vistorias 
		inner join vistinscr       on y71_codvist = y70_codvist
		inner join vistorianumpre  on y69_codvist = y70_codvist
		where vistinscr.y71_inscr = $q02_inscr 
		  and y70_instit = ".db_getsession('DB_instit')."
		  and not exists (select 1 from vistoriasanu where y28_codvist = y70_codvist)		
		order by y70_data desc limit 1";
        $resulttfv= db_query($sqltfv);
        
        
        $linhastfv = pg_num_rows($resulttfv);
        //  echo "<br>1-$linhastfv";
        if($linhastfv>0){
          db_fieldsmemory( $resulttfv, 0 );
          // busca debitos
          $sqldebtfv="
        select  
        vistorias.y70_data  as datatfv,
        (select k00_numpre from arrecad        where k00_numpre = $y69_numpre limit 1) as arrecad,
        (select k00_numpre from arrecant       where k00_numpre = $y69_numpre limit 1) as arrecant,
        (select k00_numpre from arreold        where k00_numpre = $y69_numpre limit 1) as arreold,
        (select k00_numpre from arrepaga       where k00_numpre = $y69_numpre limit 1) as arrepaga,
        (select k30_numpre from arreprescr     where k30_numpre = $y69_numpre and k30_anulado is false limit 1) as arreprescr,
        (select k21_numpre from cancdebitosreg where k21_numpre = $y69_numpre limit 1) as cancdebitos,
        (select arreprescr.k30_numpre 
           from arreprescr
          inner join divida on divida.v01_numpre = arreprescr.k30_numpre
                                  and divida.v01_numpar = arreprescr.k30_numpar  
          inner join divold on divold.k10_coddiv = divida.v01_coddiv
          where divold.k10_numpre = $y69_numpre and arreprescr.k30_anulado is false limit 1 ) as divprescr,		
        (select arrepaga.k00_numpre 
           from arrepaga
          inner join divida on divida.v01_numpre = arrepaga.k00_numpre
                           and divida.v01_numpar = arrepaga.k00_numpar  
          inner join divold on divold.k10_coddiv = divida.v01_coddiv
          where divold.k10_numpre = $y69_numpre limit 1 ) as divpaga
        from vistorias 
        where vistorias.y70_codvist= $visttfv and  y70_instit = ".db_getsession('DB_instit')  ;
          //echo $sqldebtfv; exit;
          $resultdebtfv= db_query($sqldebtfv);
          $linhasdebtfv = pg_num_rows($resultdebtfv);
          if ($linhasdebtfv>0) {
            db_fieldsmemory( $resultdebtfv, 0 );
            $imprimealvar=1;
            
            $situacaov="";
            if ($arrecad != "") {
              $situacaov= "Pendente";
            } else if($arrepaga != "") {
              $situacaov= "Pago";
            } else if($divpaga != "") {
            	$situacaov= "Divida Paga";
            } else if($arreprescr != ""){
            	$situacaov= "Prescrito"; 			 
            } else if($cancdebitos != ""){
            	$situacaov= "Cancelado"; 			 
			} else if($divprescr != ""){
				$situacaov = "Divida Prescrita";	
            } else if($arreold != "") {
              $situacaov= "Em divida";
            }
          } else {
            $imprimealvar=0;
          }
        } else {
          $imprimealvar=0;
        }


        //********** TFS ... sanitario **************
        $sqltfs="
        select y69_numpre, y70_codvist as visttfs
		from sanitarioinscr 
		inner join vistsanitario   on y74_codsani = y18_codsani
		inner join vistorianumpre  on y69_codvist = y74_codvist
		inner join vistorias       on y74_codvist = y70_codvist
		where y18_inscr = $q02_inscr and y70_instit = ".db_getsession('DB_instit')."
			and not exists (select 1 from vistoriasanu where y28_codvist = y70_codvist)	
		order by y70_data desc limit 1";
        $resulttfs= db_query($sqltfs);
        $linhastfs = pg_num_rows($resulttfs);
        if($linhastfs>0){
          db_fieldsmemory( $resulttfs, 0 );
          // busca debitos
          $sqldebtfs="
        select  
        vistorias.y70_data  as datatfs,
        (select k00_numpre from arrecad        where k00_numpre = $y69_numpre limit 1) as arrecad,
        (select k00_numpre from arrecant       where k00_numpre = $y69_numpre limit 1) as arrecant,
        (select k00_numpre from arreold        where k00_numpre = $y69_numpre limit 1) as arreold,
        (select k00_numpre from arrepaga       where k00_numpre = $y69_numpre limit 1) as arrepaga,
        (select k30_numpre from arreprescr     where k30_numpre = $y69_numpre and k30_anulado is false limit 1) as arreprescr,
        (select k21_numpre from cancdebitosreg where k21_numpre = $y69_numpre limit 1) as cancdebitos,
        (select arreprescr.k30_numpre 
           from arreprescr
          inner join divida on divida.v01_numpre = arreprescr.k30_numpre
                                  and divida.v01_numpar = arreprescr.k30_numpar  
          inner join divold on divold.k10_coddiv = divida.v01_coddiv
          where divold.k10_numpre = $y69_numpre and k30_anulado is false limit 1 ) as divprescr,				
        (select arrepaga.k00_numpre 
           from arrepaga
          inner join divida on divida.v01_numpre = arrepaga.k00_numpre
                           and divida.v01_numpar = arrepaga.k00_numpar  
          inner join divold on divold.k10_coddiv = divida.v01_coddiv
          where divold.k10_numpre = $y69_numpre limit 1 ) as divpaga        
        from vistorias 
        where vistorias.y70_codvist= $visttfs and y70_instit = ".db_getsession('DB_instit')." ";
          $resultdebtfs= db_query($sqldebtfs);
          $linhasdebtfs = pg_num_rows($resultdebtfs);
          if($linhasdebtfs>0){
            db_fieldsmemory( $resultdebtfs, 0 );
            $imprimesani=1;
            if ($arrecad != "") {
              $situacaos= "Pendente";
            } else if($arrepaga != "") {
              $situacaos= "Pago";
            } else if($divpaga != "") {
            	$situacaos= "Divida Paga";
            } else if($arreprescr != ""){
            	$situacaos= "Prescrito"; 			 
            } else if($cancdebitos != ""){
            	$situacaos= "Cancelado"; 			 
			} else if ($divprescr != ""){	
			    $situacaos = "Divida Prescrita";
            } else if($arreold != "") {
              $situacaos= "Em divida";
            }
          }else{
            $imprimesani=0;
          }
        }else{
          $imprimesani=0;
        }

        $str_parcel = func_parcel( $q02_inscr );
        $str_divida = func_divida( $q02_inscr );

        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"Dívida Ativa : ",0,0,"J",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(100,5,$str_divida,0,1,"J",0);
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"Parcelamento : ",0,0,"J",0);
        $pdf->setfont('Arial','',10);
        $pdf->cell(100,5,$str_parcel,0,1,"J",0);
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"Vistoria de Alvará (TFV) : ",0,0,"J",0);
        $pdf->setfont('Arial','',10);
        if($imprimealvar==1){
          $pdf->cell(100,5,$visttfv." - Data: ".db_formatar($datatfv,"d")." - Situação: ".$situacaov,0,1,"J",0);
        }else{
          $pdf->cell(100,5,"sem TFV",0,1,"J",0);
        }
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"Vistoria Sanitário (TFS) : ",0,0,"J",0);
        $pdf->setfont('Arial','',10);
        if($imprimesani==1){
          $pdf->cell(100,5,$visttfs." - Data: ".db_formatar($datatfs,"d")." - Situação: ".$situacaos,0,1,"J",0);
        }else{
          $pdf->cell(100,5,"sem TFS",0,1,"J",0);
        }
        $pdf->setx($col);
        $pdf->setfont('Arial','B',10);
        $pdf->cell(65,5,"Débitos ISSQN : ",0,0,"J",0);        
        $pdf->setfont('Arial','',10);
        $pdf->MultiCell(100,5,$str_issqn ,0,1,"J",0);
        $pdf->setx($col);

        if( eregi("IRR",$str_issqn) ) {
          $pdf->cell(160,2,"",0,1,"L",0);        	
        } else {
        	$pdf->cell(160,5,"",0,1,"L",0);
        }
        
        $pdf->setx($col);
        $pdf->MultiCell(170,4,"Zona: $q35_zona - $j50_descr          Area: $q30_area          N. de funcionarios: $q30_quant          Telefone: $z01_telef" ,0,"J");
        $pdf->setx($col);
        
        $pdf->ln(9);
      }else{
      	
        $pdf->setx($col);
        $pdf->MultiCell(170,4, db_geratexto($db02_texto),0,"J"); //"          ".db_geratexto($db02_texto)
        $pdf->cell(160,3,"",0,1,"L",0);     
        
        
      }
    }
  }
}

//////////////////////////////////
if(isset($inscrnaoinc)&&$inscrnaoinc!=""){
  $erroinscr = "Não foi gerado(s) termo(s) para a(s) seguinte(s) inscrições $inscrnaoinc atualize o cadastro";
  db_msgbox($erroinscr);
  exit;
}

if(isset($mostrapdf) && $mostrapdf == 't'){
  $pdf->Output();
}else{
  db_redireciona('db_erros.php?fechar=true&db_erro=Ja foram gerados termos para todas as inscrições do filtro selecionado nesse exercício! ');
  exit;
}


//************************************************************************************************************************************//

//ISSQN
function func_issqn( $q01_numpre, $q01_cadcal ){
  global $q02_inscr;
  global $anodeb;
  global $anodebvar;
  global $k00_dtvenc;
  global $anoaux;
  $str_issqn = "";
  // verifica se tem issqn fixo
  $str_sql3  = "select distinct anodeb,k00_dtvenc from (";
  $str_sql3 .= " select  extract( year from arrecad.k00_dtvenc ) as anodeb ,k00_dtvenc";
  $str_sql3 .= "   from isscalc ";
  $str_sql3 .= "        inner join arreinscr on arreinscr.k00_inscr = isscalc.q01_inscr ";
  $str_sql3 .= "        inner join arrecad   on arrecad.k00_numpre  = isscalc.q01_numpre ";
  $str_sql3 .= "  where isscalc.q01_inscr  = $q02_inscr and q01_cadcal = 2";
  $str_sql3 .= "    and arrecad.k00_dtvenc < '".date("Y/m/d",db_getsession('DB_datausu'))."' ) as zzz";
  //	die($str_sql3);
  $rsIsscalc  = db_query($str_sql3);
  $intIsscalc = pg_numrows($rsIsscalc);
  if( $intIsscalc > 0 ){
    $virgula = '';
    $vir   = '';
    $anoaux='';
    $str_issqn .= "ISSQN FIXO ";
    $venc = "Vencimento(s):";
    for( $z=0; $z<$intIsscalc; $z++ ){
      db_fieldsmemory($rsIsscalc,$z);
      if($anoaux != $anodeb){
        $str_issqn .= $virgula.$anodeb;
        $virgula = ', ';
        $anoaux = $anodeb;
      }
      $venc .= $vir.db_formatar($k00_dtvenc,"d") ;
      $vir   = ", ";
    }
    $str_issqn .= "\n".$venc;
  }

  // verifica se tem issqn variavel
  $str_sql1  = "select distinct anodebvar from (";
  $str_sql1 .= " select  extract( year from arrecad.k00_dtvenc ) as anodebvar ";
  $str_sql1 .= "   from issvar ";
  $str_sql1 .= "        inner join arrecad   on arrecad.k00_numpre   = issvar.q05_numpre ";
  $str_sql1 .= "                            and arrecad.k00_numpar   = issvar.q05_numpar ";
  $str_sql1 .= "        inner join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre ";
  $str_sql1 .= "  where arreinscr.k00_inscr  = $q02_inscr ";
  $str_sql1 .= "    and arrecad.k00_dtvenc < '".date("Y/m/d",db_getsession('DB_datausu'))."' ) as xxx";
  //	die($str_sql1);
  $rsIssvar  = db_query($str_sql1);
  //db_criatabela($rsIssvar);exit;
  $intIssvar = pg_numrows($rsIssvar);

  if( $intIssvar > 0 ){
    $vir = '';
    $str_issqn .= " ISSQN VARIÁVEL \n Exercício(s): ";
    for( $x=0; $x < $intIssvar; $x++ ){
      db_fieldsmemory($rsIssvar,$x);
      $str_issqn .= $vir.$anodebvar;
      $vir = ',';
    }
  }
  if($str_issqn != ""){
    $str_issqn = "IRREGULAR : ".$str_issqn;
  }else{
    $str_issqn = "REGULAR";
  }

  return @$str_issqn;
}


//DIVIDA
function func_divida( $q02_inscr ){
  $str_anos = "";
  $str_retorno = "NÃO";
  $str_venc = "NÃO";
  $mtz_anos = "";

  //Anos
  $str_sql = "select distinct v01_exerc
  from arreinscr
  inner join divida  on divida.v01_numpre = arreinscr.k00_numpre
                  	and v01_instit = ".db_getsession('DB_instit')."
  inner join arrecad on arrecad.k00_numpre = divida.v01_numpre
  where arreinscr.k00_inscr = $q02_inscr";

  $result = db_query( $str_sql ) or die ("FALHA: $str_sql");
  $int_linhas = pg_num_rows( $result );
  if($int_linhas>0){
    for( $z=0; $z<$int_linhas; $z++ ){
      $dados = pg_fetch_array( $result );
      $str_anos .= $dados[0]." ";
       
    }
    $str_retorno = $str_anos;
  }else{
    $str_retorno = "NÃO ";
  }
  return $str_retorno;

}

//PARCELAMENTO
function func_parcel( $q02_inscr ){

  $str_sql1 = "select k00_dtvenc
  from arreinscr
  inner join termo   on termo.v07_numpre   = arreinscr.k00_numpre 
                   	and v07_instit = ".db_getsession('DB_instit')."
  inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre
  where     arreinscr.k00_inscr = $q02_inscr";
  $result1 = db_query( $str_sql1 ) or die ("FALHA: $str_sql1");
  $linhaspar1 = pg_num_rows($result1);
  if( $linhaspar1> 0 ){
    $str_sql = "select k00_dtvenc
	  from arreinscr
	  inner join termo   on termo.v07_numpre   = arreinscr.k00_numpre 
                   	  and v07_instit = ".db_getsession('DB_instit')."
	  inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre
	  where     arreinscr.k00_inscr = $q02_inscr 
	        and arrecad.k00_dtvenc < '".date('Y-m-d',db_getsession('DB_datausu'))."' limit 1";
    //die($str_sql);
    $result = db_query( $str_sql ) or die ("FALHA: $str_sql");
    $linhaspar = pg_num_rows($result);
    if( $linhaspar> 0 ){
      $str_retorno =  "SIM - IRREGULAR";
    }else{
	     $str_retorno = "SIM - REGULAR";
    }
  }else{
    $str_retorno = "NÃO";
  }

  return $str_retorno;
}
?>