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

$presente = '';
$coor = '';

$valorminimo=0;
$valormaximo=0;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ( !isset($certid) || $certid == '' ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Certidão Não Encontrada.');
  exit; 
}
include("fpdf151/pdf3.php");
include("libs/db_sql.php");
include_once("libs/db_utils.php");
include("classes/db_termo_classe.php");
$cltermo = new cl_termo;
$exercicio = db_getsession("DB_anousu");
$borda = 1; 
$bordat = 1;
$preenc = 0;
$TPagina = 57;
$numero = ($certid1 - $certid) + 1;

//db_postmemory($HTTP_POST_VARS);
///////////////////////////////////////////////////////////////////////
//$parcel = 50006 ;

$pdf = new pdf3(); // abre a classe
$pdf->open(); // abre o relatorio
$pdf->aliasnbpages(); // gera alias para as paginas

$pdf->SetAutoPageBreak('on',10);

for($numcertid=0;$numcertid<$numero;$numcertid++){
  $inic = $certid + $numcertid;
  $sql="select termo.*,
  coalesce(certidmassa.v13_certid) as v13_certidmassa,
  v14_certid,
  v13_dtemis,
  to_char(length(z01_cgccpf),'99') as leng,
  z01_numcgm, 
  z01_nome,
  case when trim(z01_cgccpf) = ''
    then '000000000'
    else z01_cgccpf
      end as z01_cgccpf, 
    z01_ender, 
    z01_bairro, 
    z01_munic, 
    z01_telef, 
    case when trim(z01_ident) = ''
      then '0000000000'
      else z01_ident
	end as z01_ident,
      z01_email, 
      z01_uf,
      z01_numero,
      case z01_estciv 
	when 1 then 'estado civil solteiro,'
	when 2 then 'estado civil casado,'
	when 3 then 'estado civil viúvo,'
	when 4 then 'estado civil divorciado,'
	else ''
	  end as estciv, 
	z01_cep
	  from termo
	  inner join
	  cgm on z01_numcgm = v07_numcgm
	  left outer join certter
	  on v14_parcel = v07_parcel
	  left outer join certid  on certid.v13_certid = certter.v14_certid 
	  and certid.v13_instit = ".db_getsession('DB_instit')."
	  left outer join certidmassa
	  on certidmassa.v13_certid = certid.v13_certid
	  where v14_certid = $inic and v07_instit = ".db_getsession('DB_instit')." ";
	//  die($sql);
	$result=pg_query($sql);
	if ( pg_numrows($result) == 0 ) {
	  //db_redireciona('db_erros.php?fechar=true&db_erro=Certidão no. '.$certid. ' Não Encontrado.');
	  //exit; 
	  continue;
	}
	db_fieldsmemory($result,0);
	$ynome = $z01_nome;
	if ($leng == '14' ) {
	  $cpf = db_formatar($z01_cgccpf,'cnpj');
	} else {
	  $cpf = db_formatar($z01_cgccpf,'cpf');
	}

	/////// TEXTOS E ASSINATURAS

	$instit = db_getsession("DB_instit");
	$sqltexto = "select * from db_textos where id_instit = $instit and ( descrtexto like 'inicial%' or descrtexto like 'ass%')";
	$resulttexto = pg_exec($sqltexto);
	for( $xx = 0;$xx < pg_numrows($resulttexto);$xx ++ ){
	  db_fieldsmemory($resulttexto,$xx);
	  $text  = $descrtexto;
	  $$text = db_geratexto($conteudotexto);
	}
	////////

	$sqlparag = "select *
	  from db_documento 
	  inner join db_docparag on db03_docum = db04_docum
	  inner join db_tipodoc on db08_codigo  = db03_tipodoc
	  inner join db_paragrafo on db04_idparag = db02_idparag 
	  where db03_tipodoc = 1008 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
	$resparag = pg_query($sqlparag);
	if ( pg_numrows($resparag) == 0 ) {
	  db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento da CDA!');
	  exit; 
	}
	$numrows = pg_numrows($resparag);
	for($i=0;$i<$numrows;$i++){
	  db_fieldsmemory($resparag,$i);

	  if ($db04_ordem == '2'){	
	    $metodo = $db02_texto;
	  }
	  if ($db04_ordem == '3'){
	    $presente = $db02_texto;
	  }
	  if ($db04_ordem == '4'){
	    $asssec = $db02_texto;
	  }
	  if ($db04_ordem == '5'){
	    $asscoord = $db02_texto;
	  }
	}

	$sql = "select termodiv.*,
								 divida.*,
								 coalesce(certidmassa.v13_certid) as v13_certidmassa,
								 v14_certid,
								 v13_dtemis,
								 case 
									 when divmatric.v01_matric is not null then divmatric.v01_matric 
									 else arrematric.k00_matric
								 end as matric,
								 case 
									 when divinscr.v01_inscr is not null then divinscr.v01_inscr
									 else arreinscr.k00_inscr
								 end as inscr,
								 coalesce(divcontr.v01_contr,0) as contr,
								 case 
									 when a.j01_numcgm is not null then ( select z01_nome 
																													from cgm 
																												 where z01_numcgm = a.j01_numcgm)
								 end as nomematric,
								 case 
									 when q02_numcgm is not null then ( select z01_nome 
																											  from cgm 
																											 where z01_numcgm = q02_numcgm)
								 end as nomeinscr,
								 case 
									 when b.j01_numcgm is not null then ( select z01_nome 
																												  from cgm 
																												 where z01_numcgm = b.j01_numcgm)
								 end as nomecontr
						from termodiv 
								 left outer join certter 		 on v14_parcel						 = parcel
								 left outer join certid  	 	 on certid.v13_certid			 = certter.v14_certid 
																					  and certid.v13_certid			 = ".db_getsession('DB_instit') ."
								 inner join  divida  	    on v01_coddiv = coddiv
																				    and v01_instit						 = ".db_getsession('DB_instit')." 
								 left join arrematric        on arrematric.k00_numpre  = divida.v01_numpre
								 left join arreinscr         on arreinscr.k00_numpre   = divida.v01_numpre
								 left outer join divmatric 	 on divmatric.v01_coddiv	 =	 divida.v01_coddiv
								 left outer join iptubase a	 on divmatric.v01_matric	 = a.j01_matric
								 left outer join divinscr 	 on divinscr.v01_coddiv		 =  divida.v01_coddiv
								 left outer join issbase 		 on divinscr.v01_inscr		 = issbase.q02_inscr
								 left outer join divcontr    on divcontr.v01_coddiv    =  divida.v01_coddiv
								 left outer join contrib 		 on divcontr.v01_contr		 =  contrib.d07_contri		      
								 left outer join iptubase b	 on b.j01_matric					 = contrib.d07_matric
								 left outer join certidmassa on certidmassa.v13_certid = certid.v13_certid
					 where v14_certid = $inic ";

	$sql = "select coalesce(certidmassa.v13_certid) as v13_certidmassa,
				   v14_certid,
				   v13_dtemis,
				   arrematric.k00_matric as matric,
				   arreinscr.k00_inscr as inscr,
				   case 
				   	  when a.j01_numcgm is not null then ( select z01_nome 
				   	  										 from cgm 
				   	  										where z01_numcgm = a.j01_numcgm )
	  			   end as nomematric,
				   case 
				   	  when q02_numcgm is not null then ( select z01_nome 
				   	  									   from cgm 
				   	  									  where z01_numcgm = q02_numcgm)
	  			   end as nomeinscr
	  		  from certid
	  			   left join certter 	 on certter.v14_certid     = certid.v13_certid
	  			   left join termo	 	 on certter.v14_parcel 	   = termo.v07_parcel
	  									and v07_instit 		   	   = ".db_getsession('DB_instit')."
	  			   left join arrematric  on arrematric.k00_numpre  = termo.v07_numpre
	  			   left join arreinscr   on arreinscr.k00_numpre   = termo.v07_numpre
	  			   left join iptubase  a on arrematric.k00_matric  = a.j01_matric
	  	  		   left join issbase 	 on arreinscr.k00_inscr    = issbase.q02_inscr
	  			   left join certidmassa on certidmassa.v13_certid = certid.v13_certid
			  where certter.v14_certid = $inic 
			  	and certid.v13_instit  = ".db_getsession('DB_instit') ;

	$result = pg_exec($sql);
	if ( pg_numrows($result) == 0 ) {
	  db_redireciona('db_erros.php?fechar=true&db_erro=Certidão no. '.$inic. ' Não Encontrada.');
	  exit;
	}
	if ( $reemissao == 't'){
	  $dataemis = mktime(0,0,0,substr($v13_dtemis,5,2),substr($v13_dtemis,8,2),substr($v13_dtemis,0,4));
	  $anoemis  = substr($v13_dtemis,0,4);
	  $xmes     = substr($v13_dtemis,5,2);
	  $xdia     = substr($v13_dtemis,8,2);
	  $xano     = substr($v13_dtemis,0,4);
	} else {
	  $dataemis = db_getsession("DB_datausu");
	  $anoemis  = db_getsession("DB_anousu");
	  $xmes = date('m');	
	  $xdia = date('d');
	  $xano = date('Y');
	}

	$flt_total = 0;
	if( $valorminimo > 0 ){
	  db_fieldsmemory($result,0); 
	  $res_debitos = debitos_numpre($v07_numpre,0,0,$dataemis,$anoemis,0);
	  $num = pg_numrows($res_debitos);
	  for($i = 0;$i < $num;$i++) {
	    db_fieldsmemory($res_debitos,$i); 
	    $flt_total  += $total;
	  }
	  if( ($flt_total < $valorminimo)  or ($flt_total > $valormaximo) ){
	    continue;
	  }
	} 

	db_fieldsmemory($result,0);

	$anocertid = substr($v13_dtemis,0,4);
	$sqlparag = "select db02_texto
	  from db_documento 
	  inner join db_docparag on db03_docum = db04_docum
	  inner join db_tipodoc on db08_codigo  = db03_tipodoc
	  inner join db_paragrafo on db04_idparag = db02_idparag 
	  where db03_tipodoc = 1017 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
	$resparag = pg_query($sqlparag);

	if ( pg_numrows($resparag) == 0 ) {
	  $head1 = 'SECRETARIA DE FINANÇAS';
	}
	else {
	  db_fieldsmemory( $resparag, 0 );
	  $head1 = $db02_texto;
	}

	//sql procedencia
	/*
	$sqlProc="
	  select distinct
	  v01_coddiv,
	v01_proced,
	v03_descr,
	v01_dtinsc,
	v01_livro,
	v01_folha,
	v01_exerc,
	case when arrematric.k00_numpre is not null then 'M - '||arrematric.k00_matric when arreinscr.k00_numpre is not null then 'I - '||arreinscr.k00_inscr
	else 'C - '||arrenumcgm.k00_numcgm end as origem from ( select certter.v14_parcel, ( select rinumpre from fc_origemparcelamento(termo.v07_numpre) ) as numpre
	    from certter inner join termo on termo.v07_parcel = certter.v14_parcel where v14_certid = {$inic} ) as cert 
	  inner join ( select certter.v14_parcel, v01_numpre, v01_coddiv, v01_proced, v03_descr, v01_exerc, v01_dtinsc,v01_livro,v01_folha from certter
	      inner join termo on termo.v07_parcel = certter.v14_parcel inner join termodiv on termodiv.parcel = certter.v14_parcel
	      inner join divida on divida.v01_coddiv = termodiv.coddiv and v01_instit =  " . db_getsession("DB_instit")."
	      inner join proced on proced.v03_codigo = divida.v01_proced
	      where certter.v14_certid = {$inic}
	      union  
	      select certter.v14_parcel, v01_numpre, v01_coddiv, v01_proced, v03_descr, v01_exerc, v01_dtinsc,v01_livro,v01_folha from certter
	      inner join termo on termo.v07_parcel = certter.v14_parcel 
	      inner join termoini on termoini.parcel = certter.v14_parcel
	      inner join inicialcert on inicial        = v51_inicial 
	      inner join certdiv on certdiv.v14_certid = v51_certidao 
	      inner join divida  on v01_coddiv         = v14_coddiv and v01_instit =  " . db_getsession("DB_instit")."
	      inner join proced  on proced.v03_codigo  = divida.v01_proced where certter.v14_certid = {$inic} ) as x on x.v14_parcel = cert.v14_parcel
	    inner join arrenumcgm on arrenumcgm.k00_numpre = x.v01_numpre 
	    left  join arrematric on arrematric.k00_numpre = x.v01_numpre
	    left  join arreinscr  on arreinscr.k00_numpre  = x.v01_numpre ";*/
	//
	
	$sqlcert = "select v14_certid,v14_parcel,v07_numpre 
	            from certter 
							inner join termo on termo.v07_parcel = certter.v14_parcel  
							where v14_certid = {$inic}";
	$resultcert = pg_query($sqlcert);						
	$linhascert = pg_num_rows($resultcert);					
	if($linhascert > 0){
		db_fieldsmemory($resultcert,0);
	}

  $campos = "    v01_coddiv,
                 v01_exerc,                                                         
                 v03_descr,                                                         
                 v01_dtinsc,
                 v01_livro,
                 v01_folha ,
                 v01_proced,
                 v01_obs,
                 case when arrematric.k00_numpre is not null then 'M - '||arrematric.k00_matric 
                      when arreinscr.k00_numpre is not null then 'I - '||arreinscr.k00_inscr  
                      else 'C - '||arrenumcgm.k00_numcgm 
                 end as origem   ";
								 
  //esse sql traz as procedencia... busca as origem do parcelameto e reparcelamento.
	$sqlProc = $cltermo->sql_query_origem_divida ( $v07_numpre,$campos);
	
  // die($sqlProc);

	$pdf->addpage(); // adiciona uma pagina
	$pdf->settextcolor(0,0,0);
	$pdf->setfillcolor(220);
	$pdf->setfont('arial','',11);
	$numpar  = $v07_totpar;
	$entrada = $v07_vlrent;
	$vencent = $v07_dtlanc;
	$vlrpar  = $v07_vlrpar;
	$vencpar = $v07_dtvenc;
	$extenso = db_extenso($v07_valor);
	$pdf->setfont('arial','b',11);
	$pdf->multicell(0,4,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
	$pdf->ln(6);
	$pdf->setfont('arial','',11);

  $aMatric = array();
  $aInscr  = array();

  $rsCert  = pg_query($sql);
  $iLinhas = pg_num_rows($rsCert);
	
	for ($xy = 0; $xy < $iLinhas; $xy++) {

		db_fieldsmemory($rsCert,$xy);

		$pdf->setfont('arial','',9);

		
		// Consulta parametrização da pardiv

		$sSqlPardiv  = " select v04_envolcdaiptu,                         ";
		$sSqlPardiv .= "        v04_envolprinciptu,                       ";
		$sSqlPardiv .= "        v04_envolcdaiss,													";
		$sSqlPardiv .= "        v04_imphistcda,                           ";
		$sSqlPardiv .= "        v04_expfalecimentocda                     "; 
		$sSqlPardiv .= "    from pardiv                                   ";
		$sSqlPardiv .= "   where v04_instit = ".db_getsession("DB_instit") ;

		$rsPardiv      = pg_query($sSqlPardiv) or die($sSqlPardiv);
		$iLinhasPardiv = pg_num_rows($rsPardiv);

		$oPardiv = db_utils::fieldsMemory($rsPardiv,0);

    $sExpressaoFalecimento = $oPardiv->v04_expfalecimentocda;	
		
		if ($oPardiv->v04_envolprinciptu == "f") {
			$lRegra = "false";
		}else{
			$lRegra = "true";
		}


    if ($matric > 0 && in_array($matric,$aMatric) ){

      continue;

    } else {

			if ($matric > 0){
				
				if ($iLinhasPardiv > 0) {

					$pdf->setfont('arial','B',10);
					$pdf->cell(190,7,'DEVEDOR(ES)'  ,0,1,"C",0);
					$pdf->cell(190,0.7,'',				"TB",1,"L",0);
					$pdf->Ln(5);
					$pdf->setfont('arial','B',10);
					$pdf->cell(30 ,5,'TIPO'     ,"TB",0,"L",0);
					$pdf->cell(130,5,'NOME'     ,1   ,0,"L",0);
					$pdf->cell(30 ,5,'CPF/CNPJ',"TB" ,1,"L",0);
					$pdf->setfont('arial','',10);

					$sqlPossuidor  = " select j18_textoprom                           ";
					$sqlPossuidor .= "   from cfiptu                                  ";
					$sqlPossuidor .= "  where j18_anousu= ".db_getsession("DB_anousu") ;

					$resultPossuidor = pg_query($sqlPossuidor);
					$linhasPossuidor = pg_num_rows($resultPossuidor);

					if($linhasPossuidor>0){
						db_fieldsmemory($resultPossuidor,0);
						if(trim($j18_textoprom) != ""){
							$possuidor = $j18_textoprom;
						}else{
							$possuidor = "POSSUIDOR";
						}
					}else{
						$possuidor = "POSSUIDOR";
					}

					$sSqlEnvol    = " select * from fc_busca_envolvidos({$lRegra},{$oPardiv->v04_envolcdaiptu},'M',{$matric})";
					$rsEnvol      = pg_query($sSqlEnvol) or die($sSqlEnvol);
					$iLinhasEnvol = pg_num_rows($rsEnvol);

					
					if ($oPardiv->v04_envolcdaiptu == 2 && $iLinhasEnvol == 0 ) {

						$sSqlEnvol  = " select j01_numcgm as rinumcgm,   ";
						$sSqlEnvol .= "        1          as ritipoenvol ";
						$sSqlEnvol .= "   from iptubase                  ";
						$sSqlEnvol .= "  where j01_matric = {$matric}    ";

						$rsEnvol      = pg_query($sSqlEnvol) or die($sSqlEnvol);
						$iLinhasEnvol = pg_num_rows($rsEnvol);

					}

					for ($i = 0; $i < $iLinhasEnvol; $i++){

						$oEnvol = db_utils::fieldsMemory($rsEnvol,$i);

						$sSqlDadosEnvol  = " select z01_numcgm,                     ";
						$sSqlDadosEnvol .= "        z01_nome,                       ";
						$sSqlDadosEnvol .= "        z01_cgccpf,                     ";
						$sSqlDadosEnvol .= "        z01_ender,                      ";
						$sSqlDadosEnvol .= "        z01_numero,                     ";
						$sSqlDadosEnvol .= "        z01_compl,                      ";
						$sSqlDadosEnvol .= "        z01_bairro,                     ";
						$sSqlDadosEnvol .= "        z01_munic,                      ";
						$sSqlDadosEnvol .= "        z01_cep,                        ";
						$sSqlDadosEnvol .= "        z01_uf,                         ";
            $sSqlDadosEnvol .= "        z01_dtfalecimento               ";						
						$sSqlDadosEnvol .= "   from cgm                             ";
						$sSqlDadosEnvol .= "  where z01_numcgm = {$oEnvol->rinumcgm}";

						$rsDadosEnvol      = pg_query($sSqlDadosEnvol) or die($sSqlDadosEnvol);
						$iLinhasDadosEnvol = pg_num_rows($rsDadosEnvol);
						
						if ($iLinhasDadosEnvol > 0) {

							$oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol,0);

              if ( trim($oDadosEnvol->z01_dtfalecimento) != '' && strlen($oDadosEnvol->z01_cgccpf) == 11 && $oDadosEnvol != '00000000000' ) {
                $sNome = $sExpressaoFalecimento." ".$oDadosEnvol->z01_nome;
              } else {
                $sNome = $oDadosEnvol->z01_nome;
              }							
							
							$sEndereco = "";
							$sEndereco = $oDadosEnvol->z01_ender;

							if(trim($oDadosEnvol->z01_numero) !="0" and trim($oDadosEnvol->z01_numero)!=""){
								$sEndereco .= ",{$oDadosEnvol->z01_numero} ";
							}
							if(trim($oDadosEnvol->z01_compl)  !="0" and trim($oDadosEnvol->z01_compl) !=""){
								$sEndereco .= ",{$oDadosEnvol->z01_compl} ";
							}
							if(trim($oDadosEnvol->z01_bairro) !="0" and trim($oDadosEnvol->z01_bairro)!=""){
								$sEndereco .= ",{$oDadosEnvol->z01_bairro} ";
							}
							if(trim($oDadosEnvol->z01_munic)  !="0" and trim($oDadosEnvol->z01_munic) !=""){
								$sEndereco .= ",{$oDadosEnvol->z01_munic}/{$oDadosEnvol->z01_uf} ";
							}
							if(trim($oDadosEnvol->z01_cep)    !="0" and trim($oDadosEnvol->z01_cep)   !=""){
								$sEndereco .= "- CEP {$oDadosEnvol->z01_cep} .";
							}

							if ($oEnvol->ritipoenvol == "1" || $oEnvol->ritipoenvol == "2") {
								$sTipoProp = "PROPRIETÁRIO";
							}else{
								$sTipoProp = $possuidor;
							}

							$pdf->cell(30,5,$sTipoProp ,0,0,"L",0);
							$pdf->Cell(130,5,$sNome    ,0,0,"L",0);
							$tam = strlen($oDadosEnvol->z01_cgccpf);

							if($tam == 14){
								$sCgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cnpj");
							}else{
								$sCgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cpf");
							}

							$pdf->Cell(30,5,$sCgcCpf,0,1,"L",0);
							$pdf->setfont('arial','',8);
							$pdf->MultiCell(190,5,"$sEndereco","B","L",0);
							$pdf->setfont('arial','',10);

						}

					}

				}


				$sSqlProprietario  = " select *										 ";
				$sSqlProprietario .= "	 from proprietario				 ";
				$sSqlProprietario .= "  where j01_matric = $matric ";

				$rsProprietario = pg_query($sSqlProprietario) or die($sSqlProprietario);
				
				$oProprietario  = db_utils::fieldsMemory($rsProprietario,0);
				
				$pdf->setfont('','',10);
				$pdf->Ln(3);
				$pdf->setfont('arial','B',10);
				$pdf->cell(190,7,'DADOS DO IMÓVEL'	,0,1,"C",0);
				$pdf->cell(190,0.7,''						 ,"TB",1,"L",0);
				$pdf->Ln(3);
				$pdf->setfont('arial','',10);
				$pdf->setfont('','B');
				$pdf->cell(25,5,'MATRÍCULA :			 ',0,0,"L",0);
				$pdf->setfont('','');
				$pdf->cell(40,5,$matric					,0,0,"l",0);
				$pdf->setfont('','B');
				$pdf->cell(33,5,'PARCELAMENTO :		 ',0,0,"L",0);
				$pdf->setfont('','');
				$pdf->cell(20,5,$v07_parcel					,0,1,"l",0);
				$pdf->setfont('','',8);
				$pdf->cell(20,4,'ENDEREÇO : '.$oProprietario->j14_tipo.' '.$oProprietario->j14_nome.', '.$oProprietario->j39_numero.'  '.$oProprietario->j39_compl.'   SETOR : '.$oProprietario->j34_setor.'   QUADRA : '.$oProprietario->j34_quadra.'   LOTE : '.$oProprietario->j34_lote,0,1,"l",0);
				$pdf->cell(70,4,'BAIRRO : '.$oProprietario->j13_descr,0,0,"l",0);
				$pdf->cell(20,4,'REFERÊNCIA ANTERIOR : '.$oProprietario->j40_refant,0,1,"l",0);
				$pdf->Ln(3);
				$pdf->Ln(3);
				$pdf->cell(190,0.7,'',"TB",1,"L",0);
				$pdf->Ln(3);
			
			}
	  
		}	

    if ( $inscr > 0 && in_array($inscr,$aInscr)) {

      continue;

    } else {
	
			if ($inscr > 0 ){
					
				$pdf->setfont('arial','B',10);
				$pdf->cell(190,7,'DEVEDOR(ES)'  ,0,1,"C",0);
				$pdf->cell(190,0.7,'',				"TB",1,"L",0);
				$pdf->Ln(5);
				$pdf->setfont('arial','B',10);
				$pdf->cell(30 ,5,'TIPO'     ,"TB",0,"L",0);
				$pdf->cell(130,5,'NOME'     ,1   ,0,"L",0);
				$pdf->cell(30 ,5,'CPF/CNPJ',"TB" ,1,"L",0);
				$pdf->setfont('arial','',10);
			

				$sSqlEnvol    = " select * from fc_busca_envolvidos({$lRegra},{$oPardiv->v04_envolcdaiss},'I',{$inscr})";
				$rsEnvol      = pg_query($sSqlEnvol) or die($sSqlEnvol);
				$iLinhasEnvol = pg_num_rows($rsEnvol);
				
				for ($i = 0; $i < $iLinhasEnvol; $i++ ) {
				
					$oEnvol = db_utils::fieldsMemory($rsEnvol,$i);

					$sSqlDadosEnvol  = " select z01_nome,											  ";
					$sSqlDadosEnvol .= "			  z01_cgccpf,                     ";
					$sSqlDadosEnvol .= "			  z01_numero,                     ";
					$sSqlDadosEnvol .= "			  z01_ender  as ender,            ";
					$sSqlDadosEnvol .= "				z01_numero as numero,           ";
					$sSqlDadosEnvol .= "				z01_compl  as compl,	          ";
					$sSqlDadosEnvol .= "				z01_bairro as bairro,	          ";
					$sSqlDadosEnvol .= "				z01_munic  as munic,	          ";
					$sSqlDadosEnvol .= "				z01_cep    as cep,		          ";
					$sSqlDadosEnvol .= "				z01_uf     as uf,			          ";
          $sSqlDadosEnvol .= "        z01_dtfalecimento               ";					
					$sSqlDadosEnvol .= "	 from cgm										          ";
					$sSqlDadosEnvol .= "	where z01_numcgm = {$oEnvol->rinumcgm}";
					
					$rsDadosEnvol			 = pg_query($sSqlDadosEnvol);
					$iLinhasDadosEnvol = pg_num_rows($rsDadosEnvol);
				
					if ($iLinhasDadosEnvol > 0) {
						$oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol,0);			

            if ( trim($oDadosEnvol->z01_dtfalecimento) != '' && strlen($oDadosEnvol->z01_cgccpf) == 11 && $oDadosEnvol != '00000000000') {
              $sNome = $sExpressaoFalecimento." ".$oDadosEnvol->z01_nome;
            } else {
              $sNome = $oDadosEnvol->z01_nome;
            }       						
						
						$sEndereco = "";
						$sEndereco = $oDadosEnvol->ender;

						if(trim($oDadosEnvol->numero) !="0" and trim($oDadosEnvol->numero)!=""){
							$sEndereco .= ",{$oDadosEnvol->numero} ";
						}
						if(trim($oDadosEnvol->compl)	!="0" and trim($oDadosEnvol->compl)	!=""){
							$sEndereco .= ",{$oDadosEnvol->compl} ";
						}
						if(trim($oDadosEnvol->bairro) !="0" and trim($oDadosEnvol->bairro)!=""){
							$sEndereco .= ",{$oDadosEnvol->bairro} ";
						}
						if(trim($oDadosEnvol->munic)  !="0" and trim($oDadosEnvol->munic) !=""){
							$sEndereco .= ",$munic/$uf";
						}
						if(trim($oDadosEnvol->cep)	  !="0" and trim($oDadosEnvol->cep)		!=""){
							$sEndereco .= "- CEP {$oDadosEnvol->cep} .";
						}
						
						if ($oEnvol->ritipoenvol == "4") {
						 $sTipo = "EMPRESA"; 	
						}else if ($oEnvol->ritipoenvol == "5") {
						 $sTipo = "SÓCIO"; 	
						}
						
						$pdf->setfont('arial','',10);
						$pdf->cell(30,5,$sTipo  ,0,0,"L",0);
						$pdf->Cell(130,5,$sNome	,0,0,"L",0);
						$tam = strlen($oDadosEnvol->z01_cgccpf);
						if($tam == 14){
							$sCgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cnpj");
						}else{
							$sCgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cpf");
						}
						$pdf->Cell(30,5,$sCgcCpf,0,1,"L",0);
						$pdf->setfont('arial','',8);
						$pdf->MultiCell(190,5,"$sEndereco","B","L",0);
						$pdf->setfont('arial','',10);
					
					}
				
				}
			
				$sSqlEmpresa  = " select *									";
				$sSqlEmpresa .= "	  from empresa						";
				$sSqlEmpresa .= "	 where q02_inscr = $inscr ";
				
				$rsEmpresa	  = pg_query($sSqlEmpresa) or die($sSqlEmpresa);
				$oEmpresa     = db_utils::fieldsMemory($rsEmpresa,0);
				$pdf->Ln(3);
				$pdf->setfont('arial','B',10);
				$pdf->cell(190,7,'DADOS DA INSCRIÇÃO',0,1,"C",0);
				$pdf->setfont('arial','',10);
				$pdf->cell(190,0.7,'',"TB",1,"L",0);
				$pdf->Ln(3);
				$pdf->setfont('','B');
				$pdf->cell(35,5,'INSCRIÇÃO: ',0,0,"L",0);
				$pdf->setfont('','');
				$pdf->cell(100,5,$inscr,0,0,"L",0);
				$pdf->setfont('','B');
				$pdf->cell(33,5,'PARCELAMENTO :		 ',0,0,"L",0);
				$pdf->setfont('','');
				$pdf->cell(20,5,$v07_parcel			  ,0,1,"l",0);
				$pdf->cell(35,5,'REF. AO ALVARÁ : ',0,0,"L",0);
				$pdf->cell(100,5,$oEmpresa->j14_tipo.' '.$oEmpresa->z01_ender.', '.$oEmpresa->z01_numero.'  '.$oEmpresa->z01_compl,0,1,"L",0);
				$pdf->cell(35,5,'BAIRRO : ',0,0,"l",0);
				$pdf->cell(100,5,$oEmpresa->z01_bairro,0,1,"l",0);
				$pdf->cell(35,5,'CIDADE : ',0,0,"l",0);
				$pdf->cell(100,5,$oEmpresa->z01_munic.' / '.$oEmpresa->z01_uf,0,0,"l",0);
				$pdf->cell(15,5,'CEP : ',0,0,"l",0);
				$pdf->cell(100,5,$oEmpresa->z01_cep,0,1,"l",0);
				$pdf->cell(190,0.7,'',"TB",1,"L",0);
				$pdf->Ln(3);

			}	
	  }		
		if (trim($matric) == "" && trim($inscr) == "" ){
			
			$pdf->cell(190,0.5,'',1,1,"L",0);
			$pdf->Ln(3);
			$pdf->setfont('','B');
			$pdf->cell(25,5,'DEVEDOR : ',0,0,"L",0);
			$pdf->setfont('','');
			$pdf->Cell(100,5,$z01_nome,0,0,"L",0);
			$pdf->setfont('','B');
			$pdf->cell(20,5,'NUMCGM : ',0,0,"L",0);
			$pdf->setfont('','');
			$pdf->cell(20,5,$z01_numcgm,0,1,"L",0);
			$pdf->Ln(3);
			$pdf->setfont('','B');
			$pdf->cell(25,5,'ENDEREÇO : ',0,0,"L",0);
			$pdf->setfont('','');
			$pdf->cell(100,5,$z01_ender.', '.$z01_numero.'  '.@$z01_compl,0,1,"L",0);

			$pdf->setfont('','B');
			$pdf->cell(25,5,'BAIRRO : ',0,0,"l",0);
			$pdf->setfont('','');
			$pdf->cell(100,5,$z01_bairro,0,1,"l",0);

			$pdf->setfont('','B');
			$pdf->cell(25,5,'CIDADE : ',0,0,"l",0);
			$pdf->setfont('','');
			$pdf->cell(100,5,$z01_munic.' / '.$z01_uf,0,0,"l",0);

			$pdf->setfont('','B');
			$pdf->cell(20,5,'CEP : ',0,0,"l",0);
			$pdf->setfont('','');
			$pdf->cell(100,5,$z01_cep,0,1,"l",0);

			$pdf->Ln(3);
			$pdf->cell(190,0.5,'',1,1,"L",0);
			$pdf->Ln(3);
		
		}
	
		$aMatric[] = $matric;
		$aInscr[]  = $inscr;

	}

$sqlparag = "select *
from db_documento 
inner join db_docparag on db03_docum = db04_docum
inner join db_tipodoc on db08_codigo  = db03_tipodoc
inner join db_paragrafo on db04_idparag = db02_idparag 
where db03_tipodoc = 1016 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
$resparag = pg_query($sqlparag);
if ( pg_numrows($resparag) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento da fundamentacao legal - tipo 1016!');
  exit; 
}

for($paragrafo=0; $paragrafo < pg_numrows($resparag); $paragrafo++) {
  db_fieldsmemory($resparag,$paragrafo);
  $fundam = $db02_texto;
  $pdf->setfont('','B',10);
  $pdf->MultiCell(0,5,$fundam,0,"L",0);
}

$rsProc=pg_query($sqlProc); 

if ( pg_num_rows($rsProc) == 0 ) {
  //continue;
}
if ( pg_num_rows($rsProc) > 0 ){
  //procedência
  $pdf->SetFont('','',7);
  $pdf->Ln(3);
  $pdf->MultiCell(0,5,'P R O C E D Ê N C I A ',0,"C",0);
  $pdf->Ln(3);
  $pdf->SetFont('','B',7);
  $pdf->Cell(15,5,"DÍVIDA",1,0,"C",1);
  $pdf->Cell(30,5,"CÓD. PROCEDÊNCIA",1,0,"C",1);
  $pdf->Cell(53,5,"PROCEDÊNCIA",1,0,"C",1);
  $pdf->Cell(30,5,"DATA DE INSCRIÇÃO",1,0,"C",1);
  $pdf->Cell(15,5,"ORIGEM",1,0,"C",1);
  $pdf->Cell(15,5,"LIVRO",1,0,"C",1);
  $pdf->Cell(15,5,"FOLHA",1,0,"C",1);
  $pdf->Cell(15,5,"EXERCÍCIO",1,1,"C",1);
  $pdf->SetFont('','',7);

  for ($iProced = 0; $iProced < pg_num_rows($rsProc); $iProced++){
    $oProcedencias = db_utils::fieldsmemory($rsProc,$iProced);
    $pdf->Cell(15,5,$oProcedencias->v01_coddiv,1,0,"C",0);
    $pdf->Cell(30,5,$oProcedencias->v01_proced,1,0,"C",0);
    $pdf->Cell(53,5,$oProcedencias->v03_descr,1,0,"C",0);
    $pdf->Cell(30,5,db_formatar($oProcedencias->v01_dtinsc,'d'),1,0,"C",0);
    $pdf->Cell(15,5,$oProcedencias->origem,1,0,"C",0);
    $pdf->Cell(15,5,$oProcedencias->v01_livro,1,0,"C",0);
    $pdf->Cell(15,5,$oProcedencias->v01_folha,1,0,"C",0);
    $pdf->Cell(15,5,$oProcedencias->v01_exerc,1,1,"C",0);
    
    if ( $oPardiv->v04_imphistcda == "t" && isset($oProcedencias->v01_obs)) {
	  $pdf->SetFont('','I',5);
  	  $pdf->setX(10);
	  $pdf->Cell(188,4,"Observação: $oProcedencias->v01_obs",1,1,"L",0);
	  $pdf->SetFont('','',7);
  	}
  }

}


$pdf->Ln(3);
$pdf->MultiCell(0,5,'C R É D I T O    T R I B U T Á R I O ',0,"C",0);
$pdf->Ln(3);


$result_arrecad=pg_exec("select * from arrecad where k00_numpre=$v07_numpre limit 1");
if (pg_numrows($result_arrecad)>0){

  $result2 = debitos_numpre($v07_numpre,0,0,$dataemis,$anoemis,0);
  $num		 = pg_num_rows($result2);

}else{
  $result_arreold=pg_exec("select * from arreold where k00_numpre=$v07_numpre limit 1");
  if(pg_numrows($result_arreold)>0) {
    $result2 = debitos_numpre_old($v07_numpre,0,0,$dataemis,$anoemis,0);
    $num = pg_numrows($result2);
  } else {
    $sqlprocuraarreforo    = "select k00_numpre,
    k00_numpar,
    k00_numcgm,
    k00_dtoper,
    k00_receit,
    k00_hist,  
    k00_valor, 
    k00_dtvenc,
    k00_numtot,
    k00_numdig,
    k00_tipo 
      from arreforo
      where k00_certidao = $v14_certid ";
    $resultprocuraarreforo = pg_query($sqlprocuraarreforo) or die($sqlprocuraarreforo);
    if (pg_numrows($resultprocuraarreforo) > 0) {
      $sqlInsertArreold = "insert into arreold ( k00_numpre,k00_numpar,k00_numcgm,k00_dtoper,k00_receit,k00_hist,k00_valor, k00_dtvenc,k00_numtot,k00_numdig,k00_tipo ) $sqlprocuraarreforo ";
      pg_query($sqlInsertArreold) or die($sqlInsertArreold);
      $result2 = debitos_numpre_old($v07_numpre,0,0,date('Y-m-d',$dataemis),$anoemis,$v01_numpar,'','');
    }else{
      $num=0;
      db_redireciona("db_erros.php?fechar=true&db_erro=Os debitos da origem CDA {$v14_certid} não foram encontrados, provavelmente pagos ou cancelados!!<br>Contate Suporte!!");
      exit;
    }  
  }
}
$pagina = 0;
$Tvlrhis = 0;
$Tvlrcor = 0;
$Tvlrmulta = 0;
$Tvlrjuros = 0;
$Ttotal = 0;
$y = 0;

$pdf->SetFont('','B',7);
$pdf->Cell(15,5,"NUMPRE",1,0,"C",1);
$pdf->Cell(50,5,"NATUREZA",1,0,"C",1);
$pdf->Cell(8,5,"PARC",1,0,"C",1);
$pdf->Cell(15,5,"VENC",1,0,"C",1);
$pdf->Cell(20,5,"ORIGINAL",1,0,"C",1);
$pdf->Cell(20,5,"CORRIGIDO",1,0,"C",1);
$pdf->Cell(20,5,"MULTA",1,0,"C",1);
$pdf->Cell(20,5,"JUROS",1,0,"C",1);
$pdf->Cell(20,5,"TOTAL",1,1,"C",1);

for($i = 0;$i < $num;$i++) {
  db_fieldsmemory($result2,$i);
  if ($y > 272){
    $pdf->AddPage();
    $pdf->SetFont('','B',7);
    $pdf->Cell(15,5,"NUMPRE",1,0,"C",1);
    $pdf->Cell(50,5,"NATUREZA",1,0,"C",1);
    $pdf->Cell(8,5,"PARC",1,0,"C",1);
    $pdf->Cell(15,5,"VENC",1,0,"C",1);
    $pdf->Cell(20,5,"ORIGINAL",1,0,"C",1);
    $pdf->Cell(20,5,"CORRIGIDO",1,0,"C",1);
    $pdf->Cell(20,5,"MULTA",1,0,"C",1);
    $pdf->Cell(20,5,"JUROS",1,0,"C",1);
    $pdf->Cell(20,5,"TOTAL",1,1,"C",1);
    $pagina = $pdf->PageNo();
  }
  $pdf->SetFont('','',7);
  $pdf->Cell(15,5,$k00_numpre,1,0,"C",0);
  $pdf->Cell(50,5,substr($k02_drecei,0,34),1,0,"L",0);
  $pdf->Cell(8,5,db_formatar($k00_numpar,'s','0',2,'e'),1,0,"C",0);
  $pdf->Cell(15,5,db_formatar($k00_dtvenc,'d'),1,0,"C",0);
  $pdf->Cell(20,5,db_formatar($vlrhis,'f'),1,0,"R",0);
  $pdf->Cell(20,5,db_formatar($vlrcor,'f'),1,0,"R",0);

  if ($v13_certidmassa == 0){
    $pdf->Cell(20,5,db_formatar($vlrmulta,'f'),1,0,"R",0);
    $pdf->Cell(20,5,db_formatar($vlrjuros,'f'),1,0,"R",0);
    $pdf->Cell(20,5,db_formatar($total,'f'),1,1,"R",0);
    $Tvlrmulta += $vlrmulta;
    $Tvlrjuros += $vlrjuros;
    $Ttotal    += $total;
  } else {
    $pdf->Cell(20,5,db_formatar(0,'f'),1,0,"R",0);
    $pdf->Cell(20,5,db_formatar(0,'f'),1,0,"R",0);
    $pdf->Cell(20,5,db_formatar($vlrcor,'f'),1,1,"R",0);
    $Ttotal    += $vlrcor;
  }
  $y = $pdf->GetY();
  $Tvlrhis   += $vlrhis;
  $Tvlrcor   += $vlrcor;
}
$pdf->SetFont('','B',7);
$pdf->Cell(88,5,"TOTAL",1,0,"C",0);
$pdf->Cell(20,5,db_formatar($Tvlrhis,'f'),1,0,"R",0);
$pdf->Cell(20,5,db_formatar($Tvlrcor,'f'),1,0,"R",0);
$pdf->Cell(20,5,db_formatar($Tvlrmulta,'f'),1,0,"R",0);
$pdf->Cell(20,5,db_formatar($Tvlrjuros,'f'),1,0,"R",0);
$pdf->Cell(20,5,db_formatar($Ttotal,'f'),1,1,"R",0);
$pdf->setfont('','B',10);
$pdf->Ln(3);

//=============================//=============================//=============================//=============================
$pdf->MultiCell(0,5,@$metodo,0,"L",0);
$pdf->Ln(3);

if ( $pdf->GetY() > 245) {
  $pdf->AddPage();
}

$pdf->MultiCell(0,5,$presente,0,"L",0);
$pdf->Ln(2); 
$pdf->MultiCell(0,8,$munic.', '.$xdia." de ".db_mes($xmes)." de ".$xano.'.',0,"R",0);
$pdf->setfont('','',10);

if(!empty($asscoord)) {
  $coor = "______________________________\n".$asscoord;
}
if(!empty($asssec)) {
  $sec = "______________________________\n".$asssec;
}

$pdf->SetFont('','B',10);
$largura = ( $pdf->w ) / 2;
$pdf->ln(4);

$pos = $pdf->gety();
if ($coor != "") {
  $pdf->multicell($largura-20,4,$coor,0,"C",0,0);
} else {
  $pdf->Cell(1,4,"",0,0,"C",0);
}

if ($sec != "") {
  $pdf->Cell($largura-10,4,"",0,0,"C",0);
  $pdf->multicell($largura,4,$sec,0,"C",0,0);
}else {
  $pdf->Cell(100,4,"",0,0,"C",0);
}

}//fim 1º for

$pdf->Output();
?>