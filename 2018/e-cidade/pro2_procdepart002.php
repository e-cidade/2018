<?PHP
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
require_once("classes/db_procandam_classe.php");
$aux02   = new cl_procandam;
$aux     = new cl_procandam;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$w         = $lista;
$tx_where  = " 1 = 1 ";
$sql_where = " 1 = 1 ";

//-- processos que estao no departamento
if (isset($ver) && $ver == "com"){
  
  if (isset($w) && !empty($w)) {
    
    $tx_where .= "and p61_coddepto in ($w) ";
  }
} else {
     
  if (isset($w) && !empty($w)) {
    $tx_where .= " and p61_coddepto not in ($w) ";
  }
}  
$tx_where .= " and p61_dtandam >= '$data1' and p61_dtandam <= '$data2' ";

if (isset($lista) && !empty($lista) ){
  
  if (isset($ver) and $ver == "com"){
    
    if (isset($w) && !empty($w)) {
      $sql_where .= " and p58_coddepto in ($w) ";
    }
  } else {
    
    if (isset($w) && !empty($w)) {
      
      $sql_where .= " and p58_coddepto not in ($w)  ";
    }
  }	 
} 

$sql_where .= " and p58_dtproc >= '$data1' and p58_dtproc <= '$data2' "; 
$sql_where .= " and p58_instit = ".db_getsession("DB_instit"); 
$tx_where  .= " and instit     = ".db_getsession("DB_instit"); 
// -----  fim '' prepara dados
// sql - processos iniciados no departamento

switch ($tipo) {
  
  case "1" :
    
    $head3 = "Processos iniciados no departamento";
    $sql   = " select  distinct p58_coddepto,
                            nome_depto,
                            p58_codproc,
                            p58_numero,
                            p58_ano,
                            p58_dtproc,
                            p58_numcgm,
                            z01_nome,
                            andamento,
                            p61_dtandam,
                            descrdepto, 
	  								       ( select nome 
	  								           from procandam 
	  								                inner join db_usuarios on db_usuarios.id_usuario = procandam.p61_id_usuario
	  								          where p61_codandam = (select max(p61_codandam)
	  								                                  from procandam
	  								                                 where procandam.p61_codproc=p58_codproc) ) as nome  
                       from ( select p58_coddepto,
                                     descrdepto as nome_depto,     
                                     p58_codproc,
                                     p58_numero,
                                     p58_ano,
                                     p58_dtproc,
                                     p58_numcgm,
                                     z01_nome,
	  						    	               ( select max(p61_codandam) 
	  							                           from procandam  
	  							                          where procandam.p61_codproc=protprocesso.p58_codproc 
	  							                   ) as andamento
                                from protprocesso
                                     inner join db_depart on coddepto = protprocesso.p58_coddepto
                                     inner join cgm on z01_numcgm = protprocesso.p58_numcgm
                               where $sql_where
                            ) as x	    
                            left outer join procandam on p61_codandam = andamento  
                            left outer join db_depart on coddepto = procandam.p61_coddepto
                  order by p58_coddepto, $ordem ";
    
  break;

  case "2" :  //tipo ==2 = processos que estão no departamento
    
    $head3 = "Processos com ultimo andamento no departamento";
    $sql ="select distinct on (p61_codproc) 
                               p61_codproc as  p58_codproc, 
                               procandam.p61_coddepto as p58_coddepto,
                               p58_numcgm,
	  	                         ( select nome 
	  	                             from procandam 
	  	                                  inner join db_usuarios on db_usuarios.id_usuario = procandam.p61_id_usuario
	  	                            where p61_codandam = (select max(p61_codandam)
	  	                                                    from procandam
	  	                                                   where procandam.p61_codproc=p58_codproc) ) as nome                                                          
                          from procandam
                               inner join protprocesso on p58_codandam = p61_codandam
                               left  join arqproc on p58_codproc = p68_codproc
                         where p68_codproc is null and procandam.p61_codandam in 
                              (
	  										       select max(p61_codandam)  /* , p61_coddepto,p61_codproc */
	  										       from procandam inner join db_depart on p61_coddepto = coddepto
	  										       where $tx_where
	  										       group by p61_coddepto,
	  										       p61_codproc
	  										      )
            order by p58_codproc,p58_coddepto";

  break;  
  
  case "3" :  // sem tramite inicial
    
    $head3 = "Processos sem tramite inicial";
    $sql = "select p58_codproc,
                   p58_numero,
                   p58_ano,
                   p58_coddepto, 
                   p58_numcgm, 
                   p58_dtproc, 
                   z01_nome, 
                   descrdepto,
                  ( select nome 
                      from procandam 
                           inner join db_usuarios on db_usuarios.id_usuario = procandam.p61_id_usuario
                     where p61_codandam = (select max(p61_codandam)
                                             from procandam
                                            where procandam.p61_codproc=p58_codproc) ) as nome 
             from protprocesso 
                  inner join cgm on z01_numcgm = p58_numcgm
                  inner join db_depart on coddepto = p58_coddepto
            where $sql_where 
              and (select count(*) from procandam where p61_codproc = p58_codproc) = 0  ";
  break;
  
  case "4" : //Processos movimentados no departamento
    
    $head3 = "Processos movimentados no departamento";
    
     $sql = "select distinct
                    p58_codproc,                                                                           
                    p58_numero,                                                                            
                    p58_ano,                                                                               
                    p61_coddepto as p58_coddepto,                                                                          
                    p58_numcgm,                                                                            
                    p58_dtproc,                                                                            
                    z01_nome,                                                                              
                    descrdepto,                                                                            
                   ( select nome                                                                           
                       from procandam                                                                      
                            inner join db_usuarios on db_usuarios.id_usuario = procandam.p61_id_usuario    
                      where p61_codandam = (select max(p61_codandam)                                       
                                              from procandam                                               
                                             where procandam.p61_codproc=p58_codproc) ) as nome            
              from protprocesso                                                                            
                left join procandam on protprocesso.p58_codproc = procandam.p61_codproc 
                left join procandamint on procandam.p61_codandam = procandamint.p78_codandam
                inner join cgm on z01_numcgm = p58_numcgm 
                inner join db_depart on coddepto = procandam.p61_coddepto 
             where $tx_where ";                  
    
  break;  
  
}
//die($sql);
$res= $aux->sql_record($sql);
if ($aux->numrows == 0 ) { // ln 40
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado, verifique os dados e tente novamente');   
}


// pdf
$pdf = new pdf();

$sDataInicial = db_formatar($data1, "d");
$sDataFinal   = db_formatar($data2, "d");
$head4 = "Periodo:  {$sDataInicial}   à   {$sDataFinal} "; 

$pdf->open();
$pdf->addpage('L');
$pdf->aliasNbPages();
$pdf->setfillcolor(243);

$quant=0;
$quantgeral=0;

// seleciona todos os processos do departamento informado
if ($tipo =="1") { // processos iniciados no departamento
  
  if ($aux->numrows > 0 ) { // ln 40
    
    $depto = "";  
    for ($x=0;$x <$aux->numrows ; $x++){
      db_fieldsmemory($res,$x,true);
      if ($depto !=$p58_coddepto){ // mudou departamento
        $pdf->setx(10); 
        $pdf->cell(20,4,'','0',0,'R',1);
        $pdf->cell(260,4,'DEPARTAMENTO' ,'0',1,'L',1);  // <br>
        $pdf->setx(10); 
        $pdf->setfont('arial','B','7');
        $pdf->cell(20,4,$p58_coddepto,'0',0,'R',0);
        $pdf->cell(60,4,$nome_depto ,'0',1,'L',0);   //<br>
        $pdf->setfont('arial','','7');
        $depto = $p58_coddepto;
        $pdf->setx(10); 
        $pdf->cell(20,4,'N CONTROLE' ,'0',0,'R',1);
        $pdf->cell(20,4,'PROCESSO' ,'0',0,'R',1);
        $pdf->cell(20,4,'DATA'   ,'0',0,'C',1);
        $pdf->cell(10,4,'CGM'    ,'0',0,'R',1);
        $pdf->cell(40,4,'NOME'   ,'0',0,'L',1);
        $pdf->cell(20,4,'COD.AND','0',0,'R',1);
        $pdf->cell(20,4,'DATA'   ,'0',0,'C',1);
        $pdf->cell(50,4,'DEPART.ATUAL','0',0,'L',1);
        $pdf->cell(90,4,'USUÁRIO ATUAL','0',1,'L',1);
        // cabeçalho dos processos
      }
      
      /**
       * Trata o processo caso o mesmo seja de OUVIDORIA
       */
      $sNumeroProcesso = $p58_numero."/".$p58_ano;
      if ($p58_numero == "") {
        $sNumeroProcesso = "";
      }
      
      $pdf->setx(10); 
      $pdf->cell(20,4,$p58_codproc,'0',0,'R',0);
      $pdf->cell(20,4,$sNumeroProcesso,'0',0,'R',0);
      $pdf->cell(20,4,$p58_dtproc,'0',0,'C',0);
      $pdf->cell(10,4,$p58_numcgm,'0',0,'R',0);
      $pdf->cell(40,4,substr($z01_nome,0,30),'0',0,'L',0);
      $pdf->cell(20,4,$andamento,'0',0,'R',0);
      $pdf->cell(20,4,$p61_dtandam,'0',0,'C',0);
      $pdf->cell(50,4,substr($descrdepto,0,30),'0',0,'L',0);
      $pdf->cell(90,4,$nome,'0',1,'L',0);

      $quant++;
      $quantgeral++;

      if ($x == $aux->numrows-1 or $p58_coddepto != pg_result($res, $x+1, "p58_coddepto")) {
        $pdf->cell(20,4,"QUANTIDADE DE PROCESSOS DESTE DEPARTAMENTO: $quant",'0',0,'L',0);
        $pdf->ln(10);
        $quant=0;
      }

      // quebra pagina
      if ($pdf->gety() > $pdf->h - 40 ){
        $pdf->addpage("L");
        $depto="";
      }

    } // end for
  } // end if       
  
} elseif ($tipo =="2"){ // processos iniciados no departamento
  
  if ($aux->numrows > 0 ) { // ln 40
    
    $depto = "";  
    for ($x=0;$x < $aux->numrows ; $x++) {

      db_fieldsmemory($res,$x,true);
      if ($depto !=$p58_coddepto){ // mudou departamento

        $pdf->setx(10); 
        $pdf->cell(20,4,'','B',0,'R',1);
        $pdf->cell(260,4,'DEPARTAMENTO' ,'B',1,'L',1);  // <br>
        $pdf->setx(10); 
        $pdf->cell(20,4,$p58_coddepto,'0',0,'R',0);
        $rr=$aux02->sql_record("select descrdepto from db_depart where coddepto=$p58_coddepto");
        db_fieldsmemory($rr,0); 
        $pdf->cell(60,4,$descrdepto  ,'0',1,'L',0);   //<br>
        $depto = $p58_coddepto;   // atribui departamento
        $pdf->setx(10); 
        $pdf->cell(20,4,'N CONTROLE' ,'B',0,'R',1);
        $pdf->cell(20,4,'PROCESSO' ,'B',0,'R',1);
        $pdf->cell(20,4,'DATA'   ,'B',0,'C',1);
        $pdf->cell(10,4,'CGM'    ,'B',0,'R',1);
        $pdf->cell(60,4,'NOME'   ,'B',0,'L',1);
        $pdf->cell(60,4,'ENTRADA','B',0,'L',1);
        $pdf->cell(60,4,'USUÁRIO ATUAL','B',1,'L',1);
      }
      
      /**
       * Trata o processo caso o mesmo seja de OUVIDORIA
       */
      $sNumeroProcesso = $p58_numero."/".$p58_ano;
      if ($p58_numero == "") {
        $sNumeroProcesso = "";
      }
      
      $pdf->setx(10); 
      $pdf->cell(20,4,$p58_codproc,'0',0,'R',0);
      $pdf->cell(20,4,$sNumeroProcesso,'0',0,'C',0);
      $sql="select p58_dtproc, p58_numero, p58_ano, p58_numcgm, z01_nome, descrdepto
              from protprocesso 
                   inner join cgm on z01_numcgm = p58_numcgm
                   inner join db_depart on coddepto=p58_coddepto
             where p58_codproc = $p58_codproc "; 
        $rr=$aux02->sql_record($sql);
      db_fieldsmemory($rr,0); 
      $pdf->cell(20,4,$p58_dtproc,'0',0,'C',0);
      $pdf->cell(10,4,$p58_numcgm,'0',0,'R',0);
      $pdf->cell(60,4,substr($z01_nome,0,37),'0',0,'L',0);
      $pdf->cell(60,4,substr($descrdepto,0,37),'0',0,'L',0);	   
      $pdf->cell(60,4,$nome,'0',1,'L',0);

      $quant++;
      $quantgeral++;

      if ($x == $aux->numrows-1 or $p58_coddepto != pg_result($res, $x+1, "p58_coddepto")) {
        $pdf->cell(20,4,"QUANTIDADE DE PROCESSOS DESTE DEPARTAMENTO: $quant",'0',0,'L',0);
        $pdf->ln(10);
        $quant=0;
      }

      // quebra pagina e faz $depto="";
      if ($pdf->gety() > $pdf->h - 40 ){
        $pdf->addpage("L");
        $depto="";
      }
    } // enf for    
  } // end if

} elseif ($tipo == 3) {

  if ($aux->numrows > 0 ) {
    $depto = "";  
    for ($x=0;$x <$aux->numrows ; $x++){
      db_fieldsmemory($res,$x,true);
      if ($depto !=$p58_coddepto){ // mudou departamento
        $pdf->setx(10); 
        $pdf->cell(20,4,'','0',0,'R',1);
        $pdf->cell(260,4,'DEPARTAMENTO' ,'0',1,'L',1);  // <br>
        $pdf->setx(10); 
        $pdf->setfont('arial','B','7');
        $pdf->cell(20,4,$p58_coddepto,'0',0,'R',0);
        $pdf->cell(60,4,$descrdepto ,'0',1,'L',0);   //<br>
        $pdf->setfont('arial','','7');
        $depto = $p58_coddepto;
        $pdf->setx(10); 
        $pdf->cell(20,4,'N CONTROLE' ,'0',0,'R',1);
        $pdf->cell(20,4,'PROCESSO' ,'0',0,'R',1);
        $pdf->cell(20,4,'DATA'   ,'0',0,'C',1);
        $pdf->cell(20,4,'CGM'    ,'0',0,'R',1);
        $pdf->cell(70,4,'NOME'   ,'0',0,'L',1);
        $pdf->cell(60,4,'USUÁRIO ATUAL'   ,'0',0,'L',1);
        $pdf->ln();
        // cabeçalho dos processos
      }
      
      /**
       * Trata o processo caso o mesmo seja de OUVIDORIA
       */
      $sNumeroProcesso = $p58_numero."/".$p58_ano;
      if ($p58_numero == "") {
        $sNumeroProcesso = "";
      }
      $pdf->setx(10); 
      $pdf->cell(20,4,$p58_codproc,'0',0,'R',0);
      $pdf->cell(20,4,$sNumeroProcesso,'0',0,'R',0);
      $pdf->cell(20,4,$p58_dtproc,'0',0,'C',0);
      $pdf->cell(20,4,$p58_numcgm,'0',0,'R',0);
      $pdf->cell(70,4,substr($z01_nome,0,37),'0',0,'L',0);
      $pdf->cell(60,4,$nome,'0',0,'L',0);
      $pdf->ln();

      $quant++;
      $quantgeral++;

      if ($x == $aux->numrows-1 or $p58_coddepto != pg_result($res, $x+1, "p58_coddepto")) {
        $pdf->cell(20,4,"QUANTIDADE DE PROCESSOS DESTE DEPARTAMENTO: $quant",'0',0,'L',0);
        $pdf->ln(10);
        $quant=0;
      }

      // quebra pagina
      if ($pdf->gety() > $pdf->h - 40 ){
        $pdf->addpage("L");
        $depto="";
      }

    } // end for

  } // end if

} elseif($tipo == 4) {
  
  if ($aux->numrows > 0 ) {
    
    $depto = "";
    for ($x=0;$x <$aux->numrows ; $x++){
      
      db_fieldsmemory($res,$x,true);
      
      if ($depto !=$p58_coddepto){ // mudou departamento
        
        $pdf->setx(10);
        $pdf->cell(20,4,'','0',0,'R',1);
        $pdf->cell(260,4,'DEPARTAMENTO' ,'0',1,'L',1);  // <br>
        $pdf->setx(10);
        $pdf->setfont('arial','B','7');
        $pdf->cell(20,4,$p58_coddepto,'0',0,'R',0);
        $pdf->cell(260,4,$descrdepto ,'0',1,'L',0);   //<br>
        $pdf->setfont('arial','','7');
        $depto = $p58_coddepto;
        $pdf->setx(10);
        $pdf->cell(20,4,'N CONTROLE' ,'0',0,'R',1);
        $pdf->cell(20,4,'PROCESSO' ,'0',0,'R',1);
        $pdf->cell(20,4,'DATA'   ,'0',0,'C',1);
        $pdf->cell(20,4,'CGM'    ,'0',0,'R',1);
        $pdf->cell(70,4,'NOME'   ,'0',0,'L',1);
        $pdf->cell(130,4,'USUÁRIO ATUAL'   ,'0',0,'L',1);
        $pdf->ln();
        // cabeçalho dos processos
      }
  
      /**
       * Trata o processo caso o mesmo seja de OUVIDORIA
       */
      $sNumeroProcesso = $p58_numero."/".$p58_ano;
      if ($p58_numero == "") {
        $sNumeroProcesso = "";
      }
      $pdf->setx(10);
      $pdf->cell(20,4,$p58_codproc,'0',0,'R',0);
      $pdf->cell(20,4,$sNumeroProcesso,'0',0,'R',0);
      $pdf->cell(20,4,$p58_dtproc,'0',0,'C',0);
      $pdf->cell(20,4,$p58_numcgm,'0',0,'R',0);
      $pdf->cell(70,4,substr($z01_nome,0,37),'0',0,'L',0);
      $pdf->cell(130,4,$nome,'0',0,'L',0);
      $pdf->ln();
  
      $quant++;
      $quantgeral++;
  
      if ($x == $aux->numrows-1 or $p58_coddepto != pg_result($res, $x+1, "p58_coddepto")) {
        $pdf->cell(20,4,"QUANTIDADE DE PROCESSOS DESTE DEPARTAMENTO: $quant",'0',0,'L',0);
        $pdf->ln(10);
        $quant=0;
      }
  
      // quebra pagina
      if ($pdf->gety() > $pdf->h - 40 ){
        
        $pdf->addpage("L");
        $depto="";
      }
    } // end for
  } // end if  
  
}

if ($quantgeral > 0) {
  $pdf->ln(4);
  $pdf->setfont('arial','B','8');
  $pdf->cell(20,4,"QUANTIDADE GERAL DE PROCESSOS: $quantgeral",'0',0,'L',0);
}


$pdf->output();

?>