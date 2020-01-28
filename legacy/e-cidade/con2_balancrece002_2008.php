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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("classes/db_orcreceita_classe.php");
require_once("fpdf151/assinatura.php");


$oGet         = db_utils::postMemory($_GET);
// pesquisa a conta mae da receita
$oJson       = new Services_JSON();
$oFiltros    = $oJson->decode(str_replace("\\","",$oGet->filtros)); 
$aUnidades   = $oFiltros->unidade->aUnidades;
class PDFBalanco extends PDF {
  
  
function Header() {

    global $conn;
    global $result;
    global $url;
    global $aUnidades;
    $iOrgao   = null;
    $iUnidade = null;
    if (count($aUnidades) > 0) {
      
      $aDados   = explode("-", $aUnidades[0]);
      $iOrgao   = $aDados[0];
      $iUnidade = $aDados[1];
    }
    if ($iOrgao != null and $iUnidade != null) {
      
      $sSql  = "select * ";
      $sSql .= "  from orcunidade ";
      $sSql .= " where o41_anousu  = ".db_getsession("DB_anousu");
      $sSql .= "   and o41_unidade = {$iUnidade}";
      $sSql .= "   and o41_orgao   = {$iOrgao}";
      
      $rsUnidade     = db_query($sSql);
      $oDadosUnidade = db_utils::fieldsMemory($rsUnidade, 0);
    }
  //Dados da instituição
   
//   echo ("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
//   $dados = db_query("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));

    $sSqlInstit  =  "select nomeinst,";
    $sSqlInstit .=  "       trim(ender)||','||trim(numero::VARCHAR) as ender,";
    $sSqlInstit .=  "       munic, "; 
    $sSqlInstit .=  "       uf,";
    $sSqlInstit .=  "       telef,";
    $sSqlInstit .=  "       email,";
    $sSqlInstit .=  "       url,";
    $sSqlInstit .=  "       cgc,";
    $sSqlInstit .=  "       logo ";
    $sSqlInstit .=  "  from db_config ";
    $sSqlInstit .=  " where codigo = ".db_getsession("DB_instit");
    $dados = db_query($conn, $sSqlInstit);
    $url = @pg_result($dados,0,"url");
    $this->SetXY(1,1);
    $this->Image('imagens/files/'.pg_result($dados,0,"logo"),7,3,20);

  //$this->Cell(100,32,"",1);
    $nome = pg_result($dados,0,"nomeinst");
    global $nomeinst;
    
    $nomeinst = pg_result($dados,0,"nomeinst");
    
    if(strlen($nome) > 42)
      $TamFonteNome = 8;
    else
      $TamFonteNome = 9;  

    $this->SetFont('Arial','BI',$TamFonteNome);
    $sCnpj = pg_result($dados,0,"cgc");
    if (isset($oDadosUnidade) && $oDadosUnidade->o41_descr != "") {
      
      $nome  = $oDadosUnidade->o41_descr;
      $sCnpj =  $oDadosUnidade->o41_cnpj;
    }
    $this->Text(33,9, $nome);
    $this->SetFont('Arial','I',8);
    $this->Text(33,12,trim(pg_result($dados,0,"ender")));
    $this->Text(33,16,"CNPJ: ".trim(db_formatar($sCnpj,"cnpj")));
    $this->Text(33,20,trim(pg_result($dados,0,"munic"))." - ".pg_result($dados,0,"uf"));
    $this->Text(33,24,trim(pg_result($dados,0,"telef")));
    $this->Text(33,28,trim(pg_result($dados,0,"email")));
    $comprim = ($this->w - $this->rMargin - $this->lMargin);
    $this->Text(33,32,$url);  
    $Espaco = $this->w - 80 ;
    $this->SetFont('Arial','',7);
    $margemesquerda = $this->lMargin; 
    $this->setleftmargin($Espaco);
    $this->sety(6);
    $this->setfillcolor(235);
    $this->roundedrect($Espaco - 3,5,75,28,2,'DF','123');
    $this->line(10,33,$comprim,33);
    $this->setfillcolor(255);
    $this->multicell(0,3,@$GLOBALS["head1"],0,1,"J",0);
    $this->multicell(0,3,@$GLOBALS["head2"],0,1,"J",0); 
    $this->multicell(0,3,@$GLOBALS["head3"],0,1,"J",0); 
    $this->multicell(0,3,@$GLOBALS["head4"],0,1,"J",0); 
    $this->multicell(0,3,@$GLOBALS["head5"],0,1,"J",0); 
    $this->multicell(0,3,@$GLOBALS["head6"],0,1,"J",0); 
    $this->multicell(0,3,@$GLOBALS["head7"],0,1,"J",0); 
    $this->multicell(0,3,@$GLOBALS["head8"],0,1,"J",0); 
    $this->multicell(0,3,@$GLOBALS["head9"],0,1,"J",0); 
    $this->setleftmargin($margemesquerda);
    $this->SetY(35);
  }
}
$classinatura = new cl_assinatura;
$clorcreceita = new cl_orcreceita;
$tipo_mesini = 1;
$tipo_mesfim = 1;

$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg      = '';
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

if($origem == "O"){
    $xtipo = "ORÇAMENTO";
}else{
    $xtipo = "BALANÇO";
    if($opcao == 3)
      $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
    else
      $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}
$db_filtro = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';

//echo "<pre>";
//print_r($oFiltros->recurso);
//echo "</pre>";
if (count($oFiltros->recurso->aRecursos) > 0) {
  
  $sListaRecursos = implode(",", $oFiltros->recurso->aRecursos);  
  $sOperador = "in";
  if ($oFiltros->recurso->operador =="notin") {
    $sOperador = "not in";
  }
  $db_filtro .= " and o70_codigo {$sOperador} ($sListaRecursos)";
}
/*
 * Define qual relatorio sera "impresso".
 * caso a variavel esteja setad, imprime o cabecalho do relatorio da receita, 
 * moduilo orcamento
 */
if (!isset($tipo)) {
  
  $head1 = "BALANCETE DA RECEITA ";
  $head3 = "EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;
  
  
  if ($flag_abrev == false){
       if (strlen($descr_inst) > 42){
            $descr_inst = substr($descr_inst,0,100);
       }
  }
  if( $nivel_agrupar  == 0 ){
    $head4 = "Tipo: Analítico";
  }else if( $nivel_agrupar  == 1 ){
    $head4 = "Tipo: Sintético - Não mostra Deduções ";
  }else{
    $head4 = "Tipo: Sintético - Deduções no Grupo ";
  }
  
  $head5 = "INSTITUIÇÕES : ".$descr_inst;
} else {
  
  $head2 = "DEMONSTRATIVO DA RECEITA";
  $head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");
  $head4 = "INSTITUIÇÕES : ".$descr_inst;
}

$pdf = new PDFBalanco(); 
$pdf->Open(); 
$pdf->AliasNbPages();	
$total = 0;
$pdf->setfillcolor(241);
$pdf->setfont('arial','b',8);
$pdf->setleftmargin(3);
$troca = 1;
$alt = 4;

$tm_fonte          =  6;
$tm_valor          = 17;
$tm_descr          = 60;
$tm_concarpeculiar = 5;
$tm_estrut         = 24;
$tm_reduz          = 10;
$fundo=0;
if ($impressao=='paisagem'){
	$pdf->setfont('arial','b',10);
	$tm_fonte =8;
    $tm_valor =24;
    $tm_descr =90;
    $tm_estrut = 33;
    $tm_reduz = 15;    
} 	

//$sql = "select * from work order by elemento";
//$result = db_query($sql);
$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

if( $nivel_agrupar == 0 ){

  $result = db_receitasaldo(11,1,$opcao,true,$db_filtro,$anousu,$dataini,$datafin,false,' * ',true,$nivel_agrupar);

  //db_criatabela($result);exit;
  $total_saldo_inicial              =0;
  $total_saldo_prevadic_acum       =0;
  $total_saldo_arrecadado          =0;
  $total_saldo_arrecadado_acumulado=0;
  $total_saldo_a_arrecadar        = 0;

  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);
  //  if($o57_fonte == '400000000000000' || $o57_fonte == '900000000000000'){
    if (db_conplano_grupo($anousu,$o57_fonte,9004) == true){
      $total_saldo_inicial              += $saldo_inicial;
      $total_saldo_prevadic_acum        += $saldo_prevadic_acum;
      $total_saldo_arrecadado           += $saldo_arrecadado;
      $total_saldo_arrecadado_acumulado += $saldo_arrecadado_acumulado;
      $total_saldo_a_arrecadar          += $saldo_a_arrecadar;
    }
  }  	




} else {

  $sql = "";

  if ( $nivel_agrupar == 1 ){

    $sql .= "select x.o57_anousu,
                   x.o57_fonte,
                   case when orc.o57_descr is null then '******ESTRUTURAL MÃE NAO IDENTIFICADO******' else orc.o57_descr end as o57_descr,
                   x.o70_instit,
                   x.saldo_inicial,
                   x.saldo_prevadic_acum,
                   x.saldo_inicial_prevadic,
                   x.saldo_anterior,
                   x.saldo_arrecadado,
                   x.saldo_a_arrecadar,
                   x.saldo_arrecadado_acumulado,
                   x.saldo_prev_anterior,
                   x.o57_nivel,
                   x.tipo_conta
            from (
  
                   select o57_anousu,
                          o57_fonte,
                          0 as o70_concarpeculiar,
                          o70_instit,
                          sum(saldo_inicial) as saldo_inicial,
                          sum(saldo_prevadic_acum) as saldo_prevadic_acum,
                          sum(saldo_inicial_prevadic) as saldo_inicial_prevadic,
                          sum(saldo_anterior) as saldo_anterior ,
                          sum(saldo_arrecadado) as saldo_arrecadado ,
                          sum(saldo_a_arrecadar) as saldo_a_arrecadar,
                          sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado,
                          sum(saldo_prev_anterior) as saldo_prev_anterior,
                          o57_nivel,
                          tipo_conta
                   from (
           ";
  }
  $sql .= "select o57_anousu,
                 o70_codrec,
       
                 case when o70_codrec is not null then '4'||substr(o57_fonte,2,14) else o57_fonte end as o57_fonte,
                 -- neste caso o sistema troca o codigo da conta de deducao pelo codigo 4 para agrupar com as contas certas de arrecadacao

                 --o57_fonte,
                 -- neste caso o sistema mantem o codigo das contas com a estrutura correta
       
                 o57_descr,
                 o70_codigo,
                 o70_concarpeculiar,
                 o70_instit,
                 saldo_inicial,
                 saldo_prevadic_acum,
                 saldo_inicial_prevadic,
                 saldo_anterior,
                 saldo_arrecadado,
                 saldo_a_arrecadar,
                 saldo_arrecadado_acumulado,
                 saldo_prev_anterior,
                 fc_nivel_plano2005(o57_fonte) as o57_nivel,
                 case when tipo_conta is null then 'S' else 'A' end as tipo_conta
          from orcfontes
           left join
           (
            select  o70_anousu,
                    o70_codrec,
                    o70_codfon,
                    o70_codigo,
                    o70_instit,
                    o70_concarpeculiar,
                    cast(coalesce(nullif(substr(fc_receitasaldo, 3,12),''),'0') as float8) as saldo_inicial,
                    cast(coalesce(nullif(substr(fc_receitasaldo,16,12),''),'0') as float8) as saldo_prevadic_acum,
                    cast(coalesce(nullif(substr(fc_receitasaldo,29,12),''),'0') as float8) as saldo_inicial_prevadic,
                    cast(coalesce(nullif(substr(fc_receitasaldo,42,12),''),'0') as float8) as saldo_anterior,
                    cast(coalesce(nullif(substr(fc_receitasaldo,55,12),''),'0') as float8) as saldo_arrecadado,
                    cast(coalesce(nullif(substr(fc_receitasaldo,68,12),''),'0') as float8) as saldo_a_arrecadar,
                    cast(coalesce(nullif(substr(fc_receitasaldo,81,12),''),'0') as float8) as saldo_arrecadado_acumulado,
                    cast(coalesce(nullif(substr(fc_receitasaldo,94,12),''),'0') as float8) as saldo_prev_anterior,
                    'A' as tipo_conta
                                     
            from (
                   select *,fc_receitasaldo(o70_anousu,o70_codrec,$opcao,'$dataini','$datafin') 
                   from orcreceita 
                   where o70_anousu = ".db_getsession("DB_anousu")." and $db_filtro
                 ) as x 
            ) as x on o57_anousu = o70_anousu and o70_codfon = o57_codfon
              where o57_anousu = ".db_getsession("DB_anousu")."
          ";

  if ( $nivel_agrupar == 1 ){

    $sql .= " ) as x
               group by o57_anousu,
                        o57_fonte,
                        o70_instit,
                        o57_nivel,
                        tipo_conta
            ) as x
                 left join orcfontes orc on orc.o57_anousu = x.o57_anousu and orc.o57_fonte = x.o57_fonte
                 order by o57_fonte
           ";
  }else{
 
    $sql .= " order by o57_fonte,o70_concarpeculiar";
  
  }


  //echo $sql;
  $result = db_query($sql);
  //db_criatabela($result);exit;


  $sql = "select * 
          from db_estrutura 
               inner join db_estruturanivel on db77_codestrut = db78_codestrut
               inner join conparametro      on c90_codestrut  = db77_codestrut
          where db77_codestrut = conparametro.c90_codestrut
          order by db78_nivel";
  $res = db_query($sql);
  if (pg_num_rows($res) == 0) {

    $sErro  = "Não foi encontrado a configuração dos estruturais. Verificar a configuração em ";
    $sErro .= "Contabilidade > Procedimentos > Parâmetros > Parâmetros Globais";
    db_redireciona("db_erros.php?db_erro={$sErro}");
    exit;
  }

  for( $i=0; $i<pg_numrows($result);$i++){

     $fonte = pg_result($result,$i,'o57_fonte');
     $nivel = pg_result($result,$i,'o57_nivel')-1;

     $tamanho = pg_result($res,$nivel,'db78_tamanho');
     $inicio  = pg_result($res,$nivel,'db78_inicio');

     $parte_estrutural = substr($fonte,$inicio,$tamanho);

     $total_saldo_inicial              = 0;
     $total_saldo_prevadic_acum        = 0;
     $total_saldo_inicial_prevadic     = 0;
     $total_saldo_anterior             = 0;
     $total_saldo_arrecadado           = 0;
     $total_saldo_a_arrecadar          = 0;
     $total_saldo_arrecadado_acumulado = 0;
     $total_saldo_prev_anterior        = 0;
 
     $tem_valores_sintetico = false;
     for( $x=$i+1;$x<pg_numrows($result);$x++){
        if( $parte_estrutural == substr(pg_result($result,$x,'o57_fonte'),$inicio,$tamanho) ){
          if( pg_result($result,$x,'tipo_conta') == 'A' ){
            $total_saldo_inicial              += pg_result($result,$x,'saldo_inicial');
            $total_saldo_prevadic_acum        += pg_result($result,$x,'saldo_prevadic_acum');
            $total_saldo_inicial_prevadic     += pg_result($result,$x,'saldo_inicial_prevadic');
            $total_saldo_anterior             += pg_result($result,$x,'saldo_anterior');
            $total_saldo_arrecadado           += pg_result($result,$x,'saldo_arrecadado');
            $total_saldo_a_arrecadar          += pg_result($result,$x,'saldo_a_arrecadar');
            $total_saldo_arrecadado_acumulado += pg_result($result,$x,'saldo_arrecadado_acumulado');
            $total_saldo_prev_anterior        += pg_result($result,$x,'saldo_prev_anterior');
            $tem_valores_sintetico = true;
          }
        }else{
          break;
        }
     }

     if( $tem_valores_sintetico ){
       $matriz_sintetico[$i]['total_saldo_inicial']              = $total_saldo_inicial;
       $matriz_sintetico[$i]['total_saldo_prevadic_acum']        = $total_saldo_prevadic_acum;
       $matriz_sintetico[$i]['total_saldo_inicial_prevadic']     = $total_saldo_inicial_prevadic;
       $matriz_sintetico[$i]['total_saldo_anterior']             = $total_saldo_anterior;
       $matriz_sintetico[$i]['total_saldo_arrecadado']           = $total_saldo_arrecadado;
       $matriz_sintetico[$i]['total_saldo_a_arrecadar']          = $total_saldo_a_arrecadar;
       $matriz_sintetico[$i]['total_saldo_arrecadado_acumulado'] = $total_saldo_arrecadado_acumulado;
       $matriz_sintetico[$i]['total_saldo_prev_anterior']        = $total_saldo_prev_anterior;
     }
     //echo "$fonte - $parte_estrutural-- $nivel -- $total_saldo_inicial <br>";exit;

  }


  //db_criatabela($result);exit;
  $total_saldo_inicial              =0;
  $total_saldo_prevadic_acum       =0;
  $total_saldo_arrecadado          =0;
  $total_saldo_arrecadado_acumulado=0;
  $total_saldo_a_arrecadar        = 0;

  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);
    $total_saldo_inicial              += $saldo_inicial;
    $total_saldo_prevadic_acum        += $saldo_prevadic_acum;
    $total_saldo_arrecadado           += $saldo_arrecadado;
    $total_saldo_arrecadado_acumulado += $saldo_arrecadado_acumulado;
    $total_saldo_a_arrecadar          += $saldo_a_arrecadar;
  }	




}



$pagina = 1;
$tottotal = 0;
$analitica=false;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $elemento = $o57_fonte;
  $descr    = $o57_descr;
    
  if( isset($tipo_conta) && $tipo_conta == 'S' && !isset($matriz_sintetico[$i]) ){ //nivel_agrupar != 0 && abs($saldo_inicial) + abs($saldo_prevadic_acum) + abs($saldo_arrecadado_acumulado) == 0 ) {
     continue;
  }



  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    if ($impressao=='paisagem'){
         $pdf->addpage('L');
    }else {
    	$pdf->addpage();
    } 	
    $pdf->setfont('arial','b',$tm_fonte);
    $pdf->cell($tm_estrut,$alt,"RECEITA",0,0,"L",0);
    $pdf->cell($tm_descr,$alt,"DESCRIÇÃO",0,0,"L",0);
    $pdf->cell($tm_concarpeculiar,$alt,"CP",0,0,"L",0);
    $pdf->cell($tm_reduz,$alt,"REDUZ",0,0,"L",0);
    $pdf->cell($tm_reduz,$alt,"REC",0,0,"L",0);
    if($origem == "O"){
      $pdf->cell($tm_valor,$alt,"PREVISTO",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"PREV.ADIC.",0,0,"R",0);
    }else{
      $pdf->cell($tm_valor,$alt,"PREVISTO",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"PREV.ADIC.",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"ARRECADADO",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"ARREC. ANO",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"DIFERENÇA",0,0,"R",0);
    }
    $pdf->cell(10,$alt,"Perc",0,1,"R",0);
    $pdf->ln(3);
  
  }
  /**
   *  contas analiticas possuem "o70_codrec" e serão apresentadas sombeadas
   */
  if ( isset($o70_codrec) && $o70_codrec != 0 && $impressao=='paisagem'){
  	 $fundo=1;
  }else {
  	 $fundo=0;
  }		
  $pdf->setfont('arial','',$tm_fonte);
  if( isset($o70_concarpeculiar) && $o70_concarpeculiar != 0 && substr( $elemento,0,1) == '4'){
    $pdf->cell($tm_estrut,$alt,db_formatar('9'.substr($elemento,1,14),'receita'),0,0,"L",$fundo);
  }else{
    $pdf->cell($tm_estrut,$alt,db_formatar($elemento,'receita'),0,0,"L",$fundo);
  }
  
  if( $descr == ""){
    $pdf->cell($tm_descr,$alt,"***ESTRUTURAL NÃO EXISTE NA CONTABILIDADE",0,0,"L",$fundo);
  }else{
    $pdf->cell($tm_descr,$alt,substr($descr,0,45),0,0,"L",$fundo);
  }

  if( isset($o70_concarpeculiar) ){
    $res_orcreceita = $clorcreceita->sql_record($clorcreceita->sql_query(null,null,"c58_descr",null,"o70_anousu = $anousu and o57_fonte = '$o57_fonte'"));
    if ($clorcreceita->numrows > 0){
       db_fieldsmemory($res_orcreceita,0);
       $pdf->cell($tm_concarpeculiar,$alt,$o70_concarpeculiar,0,0,"L",$fundo);
    }else{
       $pdf->cell($tm_concarpeculiar,$alt,"",0,0,"L",$fundo);
    }
  }else{
    $pdf->cell($tm_concarpeculiar,$alt,"",0,0,"L",$fundo);
  }

  if( isset($o70_codrec) && $o70_codrec != 0 ){
     $pdf->cell($tm_reduz,$alt,$o70_codrec,0,0,"C",$fundo);
  }else{
     $pdf->cell($tm_reduz,$alt,'',0,0,"C",$fundo);
  }
  if( isset($o70_codigo) && $o70_codigo != 0 ){
     $pdf->cell($tm_reduz,$alt,db_formatar($o70_codigo,'recurso'),0,0,"L",$fundo);
  }else{
     $pdf->cell($tm_reduz,$alt,'',0,0,"L",$fundo);
  }
  if($origem == "O"){
    if( $nivel_agrupar == 0 || ( $nivel_agrupar != 0 and $tipo_conta == 'A' ) ){
      $pdf->cell($tm_valor,$alt,db_formatar($saldo_inicial,'f'),0,0,"R",$fundo);
      $pdf->cell($tm_valor,$alt,db_formatar($saldo_prevadic_acum,'f'),0,0,"R",$fundo);
    }else{
      $pdf->cell($tm_valor,$alt,db_formatar($matriz_sintetico[$i]['total_saldo_inicial'],'f'),0,0,"R",$fundo);           
      $pdf->cell($tm_valor,$alt,db_formatar($matriz_sintetico[$i]['total_saldo_prevadic_acum'],'f'),0,0,"R",$fundo);         
      $saldo_inicial              = $matriz_sintetico[$i]['total_saldo_inicial'];
      $saldo_prevadic_acum        = $matriz_sintetico[$i]['total_saldo_prevadic_acum'];

    }
  }else{

    if( $nivel_agrupar == 0 || ( $nivel_agrupar != 0 and $tipo_conta == 'A' ) ){

      $pdf->cell($tm_valor,$alt,db_formatar($saldo_inicial,'f'),0,0,"R",$fundo);
      $pdf->cell($tm_valor,$alt,db_formatar($saldo_prevadic_acum,'f'),0,0,"R",$fundo);
      $pdf->cell($tm_valor,$alt,db_formatar($saldo_arrecadado,'f'),0,0,"R",$fundo);
      $pdf->cell($tm_valor,$alt,db_formatar($saldo_arrecadado_acumulado,'f'),0,0,"R",$fundo);
      $pdf->cell($tm_valor,$alt,db_formatar($saldo_a_arrecadar,'f'),0,0,"R",$fundo);

    }else{

      $pdf->cell($tm_valor,$alt,db_formatar($matriz_sintetico[$i]['total_saldo_inicial'],'f'),0,0,"R",$fundo);           
      $pdf->cell($tm_valor,$alt,db_formatar($matriz_sintetico[$i]['total_saldo_prevadic_acum'],'f'),0,0,"R",$fundo);         
      $pdf->cell($tm_valor,$alt,db_formatar($matriz_sintetico[$i]['total_saldo_arrecadado'],'f'),0,0,"R",$fundo);            
      $pdf->cell($tm_valor,$alt,db_formatar($matriz_sintetico[$i]['total_saldo_arrecadado_acumulado'],'f'),0,0,"R",$fundo);  
      $pdf->cell($tm_valor,$alt,db_formatar($matriz_sintetico[$i]['total_saldo_a_arrecadar'],'f'),0,0,"R",$fundo);           

      $saldo_inicial              = $matriz_sintetico[$i]['total_saldo_inicial'];
      $saldo_prevadic_acum        = $matriz_sintetico[$i]['total_saldo_prevadic_acum'];
      $saldo_arrecadado_acumulado = $matriz_sintetico[$i]['total_saldo_arrecadado_acumulado'];


    }
  }

  if ($saldo_inicial + $saldo_prevadic_acum != 0) {
    $pdf->cell($tm_reduz, $alt, db_formatar($saldo_arrecadado_acumulado / ($saldo_inicial + $saldo_prevadic_acum ) *100, 'f'), 0, 1, "R", $fundo);
  } else {
    $pdf->cell($tm_reduz, $alt, db_formatar(0, 'f'), 0, 1, "R", $fundo);
  }
     
}
$pdf->setfont('arial','B',$tm_fonte);
$pdf->cell($tm_estrut,$alt,'',0,0,"L",0);
$pdf->cell($tm_descr+($tm_reduz*2),$alt,'TOTAL ',0,0,"L",0);
$pdf->cell($tm_concarpeculiar,$alt,"",0,0,"L",0);
//$pdf->cell(10,$alt,'',0,0,"L",0);
if($origem == "O"){
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_inicial,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_prevadic_acum,'f'),0,1,"R",0);
}else{
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_inicial,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_prevadic_acum,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_arrecadado,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_arrecadado_acumulado,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_a_arrecadar,'f'),0,0,"R",0);

  if ($total_saldo_inicial + $total_saldo_prevadic_acum != 0) {
    $pdf->cell($tm_reduz,$alt,db_formatar( $total_saldo_arrecadado_acumulado / ($total_saldo_inicial + $total_saldo_prevadic_acum ) *100,'f'),0,1,"R",0);
  } else {
    $pdf->cell($tm_reduz,$alt,db_formatar(0,'f'),0,1,"R",0);
  }    
 
}

$tes =  "______________________________"."\n"."Tesoureiro";
$sec =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_tes  = $classinatura->assinatura(1004,$tes);
$ass_cont = $classinatura->assinatura(1005,$cont);

//echo $ass_pref;
$pdf->setfont('arial','B',8);
if( $pdf->gety() > ( $pdf->h - 50 ) ){
    if ($impressao=='paisagem'){
         $pdf->addpage('L');
    }else {
    	$pdf->addpage();
    }
}
if (!isset($tipo)) {

  $largura = ( $pdf->w ) / 2;
  $pdf->ln(10);
  $pos = $pdf->gety();
  $pdf->multicell($largura,4,$ass_pref,0,"C",0,0);
  $pdf->setxy($largura,$pos);
  $pdf->multicell($largura,4,$ass_cont,0,"C",0,0);
  
}

$pdf->Output();

db_query("commit");

?>
