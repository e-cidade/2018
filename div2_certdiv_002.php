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

set_time_limit(0);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ( !isset($certid) || $certid == '' ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Certidão Não Encontrada.');
  exit;
}
include("fpdf151/pdf3.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("fpdf151/assinatura.php");
include("classes/db_propri_classe.php");

$classinatura = new cl_assinatura;

$clpropri = new cl_propri;

pg_query("BEGIN");

if (!isset($valormaximo) || $valormaximo == ""){
  $valormaximo=99999999999;
}
if (!isset($valormonimo) || $valorminimo == ""){
  $valorminimo=0;
}

$exercicio = db_getsession("DB_anousu");
$borda = 1;
$bordat = 1;
$preenc = 0;

$TPagina = 57;
$numero = ($certid1 - $certid) + 1;
$count_certid = 0;

///////////////////////////////////////////////////////////////////////
$pdf = new pdf3(); // abre a classe
$pdf->open(); // abre o relatorio
$pdf->aliasnbpages(); // gera alias para as paginas
$pdf->SetAutoPageBreak('on',15);

for($numcertid=0;$numcertid<$numero;$numcertid++){

  $sql="select v14_certid,
  v13_dtemis,
  coalesce(certidmassa.v13_certid) as v13_certidmassa,
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
	  from certdiv
	  inner join divida 		on v01_coddiv = v14_coddiv
	  and v01_instit = ".db_getsession('DB_instit')."
	  inner join cgm 			on z01_numcgm = v01_numcgm
	  left outer join certid 	on certid.v13_certid = certdiv.v14_certid 
	  and certid.v13_instit = ".db_getsession('DB_instit')."
	  left outer join certidmassa  on certidmassa.v13_certid = certid.v13_certid
	  where v14_certid BETWEEN {$certid} AND {$certid1}
	    and v14_certid not in ( {$count_certid} ) 
	  order by {$ordenarpor}
	  ";
	//  echo $sql;exit;
	$result=pg_query($sql);
	if ( pg_numrows($result) == 0 ) {
	  //    db_redireciona('db_erros.php?fechar=true&db_erro=Certidão no. '.$inic. ' Não Encontrado.');
	  //    exit;
	  continue;
	}

	db_fieldsmemory($result,0);

	$count_certid .= ",".$v14_certid;
	
	$ynome = $z01_nome;
	if ($leng == '14' ) {
	  $cpf = db_formatar($z01_cgccpf,'cnpj');
	} else {
	  $cpf = db_formatar($z01_cgccpf,'cpf');
	}

	$anocertid = substr($v13_dtemis,0,4);

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
	/////// TEXTOS E ASSINATURAS
	/*
	   $instit = db_getsession("DB_instit");
	   $sqltexto = "select * from db_textos where id_instit = $instit and ( descrtexto like 'inicial%' or descrtexto like 'ass%')";
	   $resulttexto = pg_exec($sqltexto);
	   for( $xx = 0;$xx < pg_numrows($resulttexto);$xx ++ ){
	   db_fieldsmemory($resulttexto,$xx);
	   $text  = $descrtexto;
	   $$text = db_geratexto($conteudotexto);
	   }*/
	////////
	$sqlparag = "select db02_texto
	  from db_documento 
	  inner join db_docparag on db03_docum = db04_docum
	  inner join db_tipodoc on db08_codigo  = db03_tipodoc
	  inner join db_paragrafo on db04_idparag = db02_idparag 
	  where db03_tipodoc = 1017 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
	$resparag = pg_query($sqlparag);

	if ( pg_numrows($resparag) == 0 ) {
	  $head1 = 'SECRETARIA DE FINANÇAS';
	}else{
	  db_fieldsmemory( $resparag, 0 );
	  $head1 = $db02_texto;
	}



	$sqlparag = "select *
	  from db_documento 
	  inner join db_docparag on db03_docum = db04_docum
	  inner join db_tipodoc on db08_codigo  = db03_tipodoc
	  inner join db_paragrafo on db04_idparag = db02_idparag 
	  where db03_tipodoc = 1008 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
	$resparag = pg_query($sqlparag);
	//echo "////////////sqlparag = ".$sqlparag;
	//  db_criatabela($resparag);exit;
	if ( pg_numrows($resparag) == 0 ) {
	  db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento da CDA com o tipo 1008!');
	  exit;
	}
	$numrows = pg_numrows($resparag);
	//$pdf->inicia     = $db02_inicia;
	$assinaturas_php = "";
	for($i=0;$i<$numrows;$i++){
	  db_fieldsmemory($resparag,$i);

	  if ($db04_ordem == '1'){
	    $fundamento_inicio = $db02_texto;
	    $fundamento = "";
	    
	    $sql="select distinct * from (select db02_texto
	      from certdiv
	      inner join divida      on v01_coddiv = v14_coddiv
                      	      and v01_instit = ".db_getsession('DB_instit')."
	      inner join procedparag on v80_proced = v01_proced
	      inner join db_documento on db03_docum = v80_docum
	      inner join db_docparag on db04_docum = db03_docum
	      inner join db_paragrafo on db04_idparag = db02_idparag 
	      where v14_certid = $v14_certid and db03_instit = " . db_getsession("DB_instit")." 
	      order by db04_ordem ) as x ";
	    //echo "//////////////////sqlordem1 = $sql";
	    $resultp=pg_query($sql);
	    if(pg_numrows($resultp)==0){
	      $fundamento=" ****************** Fundamentação não cadastrada.  ********************";
	    }else{
	      db_fieldsmemory($resultp,0);
	      $fundamento = $db02_texto;
	      /*
//		 tirei a pedido do Ivar
//		 for($qp=0;$qp<pg_numrows($resultp);$qp++){
//		 db_fieldsmemory($resultp,$qp);
//	      //$fundamento .= $db02_texto;
//	      $fundamento = $db02_texto;
//	      }*/
	    }
	  }

	  if ($db02_descr == "ASSINATURAS_CODIGOPHP") {
	    $assinaturas_php = trim($db02_texto);
	  }
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
	//echo "////presente = $presente";
	$sql = "select divida.*,
	certdiv.*,
	lote.*, 
	coalesce(certidmassa.v13_certid) as v13_certidmassa,
	coalesce(arrematric.k00_matric,0) as matric,
	coalesce(arreinscr.k00_inscr,0) as inscr,
	coalesce(divcontr.v01_contr,0) as contr,
	case when a.j01_numcgm is not null
	  then (select z01_nome from cgm where z01_numcgm = a.j01_numcgm)
	  end as nomematric,
	case when q02_numcgm is not null
	  then (select z01_nome from cgm where z01_numcgm = q02_numcgm)
	  end as nomeinscr,
	case when b.j01_numcgm is not null
	  then (select z01_nome from cgm where z01_numcgm = b.j01_numcgm)
	  end as nomecontr,
	v03_descr
	  from certdiv 
	  inner join divida on v14_coddiv = v01_coddiv
	  and v01_instit = ".db_getsession('DB_instit')."
	  left outer join certid      on certid.v13_certid = certdiv.v14_certid 
	  and certid.v13_instit = ".db_getsession('DB_instit')."
	  left outer join certidmassa on certidmassa.v13_certid = certid.v13_certid
	  left outer join arrematric  on arrematric.k00_numpre = divida.v01_numpre
	  left outer join iptubase a  on arrematric.k00_matric = a.j01_matric
	  left outer join lote        on lote.j34_idbql = a.j01_idbql
	  left outer join arreinscr   on arreinscr.k00_numpre  =  divida.v01_numpre
	  left outer join issbase     on arreinscr.k00_inscr = issbase.q02_inscr
	  left outer join divcontr    on divcontr.v01_coddiv  =  divida.v01_coddiv
	  left outer join contrib     on divcontr.v01_contr  =  contrib.d07_contri		      
	  left outer join iptubase b  on b.j01_matric = contrib.d07_matric   
	  left outer join proced      on proced.v03_codigo = divida.v01_proced
	  and proced.v03_instit = ".db_getsession('DB_instit')." 
	  where v14_certid = $v14_certid
	  order by v01_numpre,v01_numpar";
	
	$result3 = pg_exec($sql) or die($sql);
	if ( pg_numrows($result3) == 0 ) {
	  db_redireciona('db_erros.php?fechar=true&db_erro=Certidão no. '.$v14_certid. ' Não encontrada!');
	  exit;
	}


	$flt_total = 0;
	for($ii = 0; $ii < pg_numrows($result3);$ii++){
	  db_fieldsmemory($result3,$ii);

	  $sqlprocura = "select * from arrecad where k00_numpre = $v01_numpre";
	  $resultprocura = pg_exec($sqlprocura) or die($sqlprocura);
	  if (pg_numrows($resultprocura) > 0) {
	    $result2 =     debitos_numpre($v01_numpre,0,0,$dataemis,$anoemis,$v01_numpar);
	  } else {
	    $sqlprocura = "select * from arreold where k00_numpre = $v01_numpre";
	    $resultprocura = pg_exec($sqlprocura) or die($sqlprocura);
	    if (pg_numrows($resultprocura) > 0) {
	      $result2 = debitos_numpre_old($v01_numpre,0,0,$dataemis,$anoemis,$v01_numpar,'','');
	    } else {
	      continue;
	    }
	  }
	  $num = pg_numrows($result2);
	  for($i = 0;$i < $num;$i++) {
	    db_fieldsmemory($result2,$i);
	    $flt_total  += $total;
	  }
	}

	if( ($flt_total < @$valorminimo)  or ($flt_total > @$valormaximo) ){
	  continue;
	}

	$pdf->addpage(); // adiciona uma pagina
	$pdf->settextcolor(0,0,0);
	$pdf->setfillcolor(220);
	$pdf->setfont('arial','',11);
	$pdf->setfont('arial','b',11);

	$pdf->multicell(0,4,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
	$pdf->ln(6);
	$pdf->setfont('arial','',11);

	$aMatric = array();
	$aInscr  = array();
  $aCgm    = array(); 

	for ($xy = 0; $xy < pg_num_rows($result3);$xy++) {

		
		db_fieldsmemory($result3,$xy);
		
		$pdf->setfont('arial','',10);

		$sSqlPardiv	 = " select v04_envolcdaiptu,												  ";
		$sSqlPardiv .= "				v04_envolcdaiss,													";
		$sSqlPardiv .= "				v04_envolprinciptu,										 	  ";
		$sSqlPardiv .= "				v04_imphistcda,  		    									";
        $sSqlPardiv .= "        v04_expfalecimentocda                     ";
		$sSqlPardiv .= "	  from pardiv																		";
		$sSqlPardiv .= "	 where v04_instit = ".db_getsession("DB_instit") ;
		
		$rsPardiv			 = pg_query($sSqlPardiv) or die($sSqlPardiv);
		$iLinhasPardiv = pg_num_rows($rsPardiv);
		
		$oPardiv = db_utils::fieldsMemory($rsPardiv,0);
		if ( $oPardiv->v04_envolcdaiptu == "" || $oPardiv->v04_envolcdaiss == "" || $oPardiv->v04_envolprinciptu == "" ) {
         db_redireciona('db_erros.php?fechar=true&db_erro=Parâmetros da Divida não configurados.');
         exit; 			
		}
		
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
				
			  //--- IMPRIME ENVOLVIDOS DA MATRÍCULA	
        if ($pdf->gety()>$pdf->h -66){
            $pdf->addPage();
            $pdf->SetFont('ARIAL','B',11);
            $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
            $pdf->ln(8);
            $pdf->setfont('','B',9);
        }
            

             //aqui// 
				$pdf->setfont('arial','B',10);
				$pdf->cell(190,7,'DEVEDOR(ES)',0,1,"C",0);
				$pdf->cell(190,0.7,'',"TB",1,"L",0);
				$pdf->Ln(5);
				$pdf->setfont('arial','B',10);
				$pdf->cell(30 ,5,'TIPO'			,"TB",0,"L",0);
				$pdf->cell(130,5,'NOME'			,1	 ,0,"L",0);
				$pdf->cell(30 ,5,'CPF/CNPJ',"TB" ,1,"L",0);
				$pdf->setfont('arial','',10);
				
				if ($iLinhasPardiv > 0) { 
					
					$sqlPossuidor	 = " select j18_textoprom														";
					$sqlPossuidor .= "	 from cfiptu																	";
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
						$sSqlEnvol .= "		from iptubase							     ";
						$sSqlEnvol .= "	 where j01_matric = {$matric}	   "; 
						
						$rsEnvol      = pg_query($sSqlEnvol) or die($sSqlEnvol);
						$iLinhasEnvol = pg_num_rows($rsEnvol);

					}
					
					for ($i = 0; $i < $iLinhasEnvol; $i++){
						
						$oEnvol = db_utils::fieldsMemory($rsEnvol,$i); 
						
						$sSqlDadosEnvol  = " select z01_numcgm,											";
						$sSqlDadosEnvol .= "        z01_nome,												";
						$sSqlDadosEnvol .= "        z01_cgccpf,											";
						$sSqlDadosEnvol .= "        z01_ender,											";
						$sSqlDadosEnvol .= "        z01_numero,											";
						$sSqlDadosEnvol .= "        z01_compl,											";
						$sSqlDadosEnvol .= "        z01_bairro,											";
						$sSqlDadosEnvol .= "        z01_munic,											";
						$sSqlDadosEnvol .= "        z01_cep,												";
						$sSqlDadosEnvol .= "        z01_uf,													";
						$sSqlDadosEnvol .= "        z01_dtfalecimento               ";
						$sSqlDadosEnvol .= "	 from cgm															";
						$sSqlDadosEnvol .= "  where z01_numcgm = {$oEnvol->rinumcgm}";
						
						$rsDadosEnvol			 = pg_query($sSqlDadosEnvol) or die($sSqlDadosEnvol);
						$iLinhasDadosEnvol = pg_num_rows($rsDadosEnvol);

						if ($iLinhasDadosEnvol > 0) {
							
							$oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol,0);
							
							if ( trim($oDadosEnvol->z01_dtfalecimento) != '' && strlen($oDadosEnvol->z01_cgccpf) == 11 && $oDadosEnvol != '00000000000') {
								$sNome = $sExpressaoFalecimento." ".$oDadosEnvol->z01_nome;
							} else {
								$sNome = $oDadosEnvol->z01_nome;
							}
							
							$sEndereco = "";
							$sEndereco = $oDadosEnvol->z01_ender;

							if(trim($oDadosEnvol->z01_numero) !="0" and trim($oDadosEnvol->z01_numero)!=""){
								$sEndereco .= ",{$oDadosEnvol->z01_numero} ";
							}
							if(trim($oDadosEnvol->z01_compl)	!="0" and trim($oDadosEnvol->z01_compl) !=""){
								$sEndereco .= ",{$oDadosEnvol->z01_compl} ";
							}
							if(trim($oDadosEnvol->z01_bairro)	!="0" and trim($oDadosEnvol->z01_bairro)!=""){
								$sEndereco .= ",{$oDadosEnvol->z01_bairro} ";
							}
							if(trim($oDadosEnvol->z01_munic)	!="0" and trim($oDadosEnvol->z01_munic) !=""){
								$sEndereco .= ",{$oDadosEnvol->z01_munic}/{$oDadosEnvol->z01_uf} ";
							}
							if(trim($oDadosEnvol->z01_cep)		!="0" and trim($oDadosEnvol->z01_cep)		!=""){
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
				
			  //--- IMPRIME DADOS DA MATRÍCULA

				$sSqlProprietario  = " select *                    ";
				$sSqlProprietario .= "   from proprietario         ";
				$sSqlProprietario .= "  where j01_matric = $matric ";
				
				$rsProprietario 	 = pg_query($sSqlProprietario) or die($sSqlProprietario);
				$oProprietario  	 = db_utils::fieldsMemory($rsProprietario,0);
      
        if ($pdf->gety()>$pdf->h -45){
            $pdf->addPage();
            $pdf->SetFont('ARIAL','B',11);
            $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
            $pdf->ln(8);
            $pdf->setfont('','B',9);
        }


 
				$pdf->setfont('','',10);
				$pdf->Ln(3);
				$pdf->setfont('arial','B',10);
				$pdf->cell(190,7,'DADOS DO IMÓVEL'  ,0,1,"C",0);
				$pdf->cell(190,0.7,''            ,"TB",1,"L",0);
				$pdf->Ln(3);
				$pdf->setfont('arial','',10);

				if ($endaimp == "o") {
					$pdf->cell(120,5,'ENDEREÇO: '.$oProprietario->nomepri.(isset($oProprietario->j39_numero)?", ".$oProprietario->j39_numero:"").(isset($oProprietario->j39_compl)?", ".$oProprietario->j39_compl:""),0,0,"l",0);
					$pdf->cell(40,5,'BAIRRO : '.$oProprietario->j13_descr,0,1,"l",0);
					
					$sqlcidade = "select munic, uf, cep from db_config where codigo = ".db_getsession('DB_instit');
					$resultcidade = pg_exec($sqlcidade);
					db_fieldsmemory($resultcidade,0);
					$pdf->cell(120,5,'CIDADE : '.$munic.' / '.$uf,0,0,"l",0);
					$pdf->cell(40,5,'CEP : '.$cep,0,1,"l",0);

				} elseif ($endaimp == "c") {
					$pdf->Cell(100,5,"",0,1,"L",0);
					$pdf->cell(20,5,'ENDEREÇO: '.$oProprietario->z01_ender . ($oProprietario->z01_numero != ""?', ' . $oProprietario->z01_numero:"") . ($oProprietario->z01_compl != ""?"/" . $oProprietario->z01_compl:""),0,1,"l",0);
					$pdf->cell(40,5,'BAIRRO : '.$oProprietario->z01_bairro,0,1,"l",0);
					$pdf->cell(110,5,'CIDADE : '.$oProprietario->z01_munic.' / '.$oProprietario->z01_uf,0,0,"l",0);
					$pdf->cell(40,5,'CEP : '.$oProprietario->z01_cep,0,1,"l",0);
				}
					$pdf->cell(40,5,'SETOR  : '.$oProprietario->j34_setor,0,0,"l",0);
					$pdf->cell(40,5,'QUADRA : '.$oProprietario->j34_quadra,0,0,"l",0);
					$pdf->cell(40,5,'LOTE : '.$oProprietario->j34_lote,0,0,"l",0);
					$pdf->cell(40,5,'MATRÍCULA : '.$matric,0,1,"l",0);

				$pdf->Ln(3);
				$pdf->cell(190,0.7,'',"TB",1,"L",0);
				$pdf->Ln(3);
			
				$aMatric[] = $matric;

			}
	  
		}	
		
		
		if ( $inscr > 0 && in_array($inscr,$aInscr)) {
		
			continue;
		
		} else {
		
			if ( $inscr > 0 ){
			  
				//--- IMPRIME ENVOLVIDOS DA INSCRIÇÃO
       if ($pdf->gety()>$pdf->h -66){
            $pdf->addPage();
            $pdf->SetFont('ARIAL','B',11);
            $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
            $pdf->ln(8);
            $pdf->setfont('','B',9);
        }

				
				$pdf->setfont('arial','B',10);
				$pdf->cell(190,7,'DEVEDOR(ES)',0,1,"C",0);
				$pdf->cell(190,0.7,'',"TB",1,"L",0);
				$pdf->Ln(5);
				$pdf->setfont('arial','B',10);
				$pdf->cell(30 ,5,'TIPO'			,"TB",0,"L",0);
				$pdf->cell(130,5,'NOME'			,1	 ,0,"L",0);
				$pdf->cell(30 ,5,'CPF/CNPJ',"TB" ,1,"L",0);
				$pdf->setfont('arial','',10);

				$sSqlEnvol    = " select * from fc_busca_envolvidos({$lRegra},{$oPardiv->v04_envolcdaiss},'I',{$inscr})";
				$rsEnvol      = pg_query($sSqlEnvol) or die($sSqlEnvol);
				$iLinhasEnvol = pg_num_rows($rsEnvol);

				for ($i = 0; $i < $iLinhasEnvol; $i++ ) {

					$oEnvol = db_utils::fieldsMemory($rsEnvol,$i);

					$sSqlDadosEnvol  = " select z01_nome,                       ";
					$sSqlDadosEnvol .= "        z01_cgccpf,                     ";
					$sSqlDadosEnvol .= "        z01_numero,                     ";
					$sSqlDadosEnvol .= "        z01_ender  as ender,            ";
					$sSqlDadosEnvol .= "        z01_numero as numero,           ";
					$sSqlDadosEnvol .= "        z01_compl  as compl,            ";
					$sSqlDadosEnvol .= "        z01_bairro as bairro,           ";
					$sSqlDadosEnvol .= "        z01_munic  as munic,            ";
					$sSqlDadosEnvol .= "        z01_cep    as cep,              ";
					$sSqlDadosEnvol .= "        z01_uf     as uf,               ";
					$sSqlDadosEnvol .= "        z01_dtfalecimento               ";
					$sSqlDadosEnvol .= "   from cgm                             ";
					$sSqlDadosEnvol .= "  where z01_numcgm = {$oEnvol->rinumcgm}";

					$rsDadosEnvol      = pg_query($sSqlDadosEnvol);
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
						if(trim($oDadosEnvol->compl)  !="0" and trim($oDadosEnvol->compl) !=""){
							$sEndereco .= ",{$oDadosEnvol->compl} ";
						}
						if(trim($oDadosEnvol->bairro) !="0" and trim($oDadosEnvol->bairro)!=""){
							$sEndereco .= ",{$oDadosEnvol->bairro} ";
						}
						if(trim($oDadosEnvol->munic)  !="0" and trim($oDadosEnvol->munic) !=""){
							$sEndereco .= ",{$oDadosEnvol->munic}/{$oDadosEnvol->uf}";
						}
						if(trim($oDadosEnvol->cep)    !="0" and trim($oDadosEnvol->cep)   !=""){
							$sEndereco .= "- CEP {$oDadosEnvol->cep} .";
						}

						if ($oEnvol->ritipoenvol == "4") {
						 $sTipo = "EMPRESA";
						}else if ($oEnvol->ritipoenvol == "5") {
						 $sTipo = "SÓCIO";
						}

						$pdf->setfont('arial','',10);
						$pdf->cell(30,5,$sTipo  ,0,0,"L",0);
						$pdf->Cell(130,5,$sNome ,0,0,"L",0);
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

			  //--- IMPRIME DADOS DA INSCRIÇÃO
				
				$sSqlEmpresa  = " select *                  ";
				$sSqlEmpresa .= "   from empresa            ";
				$sSqlEmpresa .= "  where q02_inscr = $inscr ";

				$rsEmpresa    = pg_query($sSqlEmpresa) or die($sSqlEmpresa);
				$oEmpresa     = db_utils::fieldsMemory($rsEmpresa,0);
        if ($pdf->gety()>$pdf->h -68){
            $pdf->addPage();
            $pdf->SetFont('ARIAL','B',11);
            $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
            $pdf->ln(8);
            $pdf->setfont('','B',9);
        }

				$pdf->Ln(3);
				$pdf->setfont('arial','B',10);
				$pdf->cell(190,7,'DADOS DA INSCRIÇÃO',0,1,"C",0);
				$pdf->setfont('arial','',10);
				$pdf->cell(190,0.7,'',"TB",1,"L",0);
				$pdf->Ln(3);
				$pdf->cell(35,5,'INSCRIÇÃO: ',0,0,"L",0);
				$pdf->cell(100,5,$inscr,0,1,"L",0);
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
			
				$aInscr[]  = $inscr;

			}
    }		
	
		  
    if ( in_array($v01_numcgm,$aCgm) ) {
    
      continue;
    
    } else {
			
			if ( $matric == 0  && $inscr == 0 ) {

       if ($pdf->gety()>$pdf->h -66){
            $pdf->addPage();
            $pdf->SetFont('ARIAL','B',11);
            $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
            $pdf->ln(8);
            $pdf->setfont('','B',9);
        }

			
				$pdf->setfont('arial','B',10);
				$pdf->cell(190,7,'DEVEDOR(ES)',0,1,"C",0);
				$pdf->cell(190,0.7,'',"TB",1,"L",0);
				$pdf->Ln(5);
				$pdf->setfont('arial','B',10);
				$pdf->cell(30 ,5,'TIPO'			,"TB",0,"L",0);
				$pdf->cell(130,5,'NOME'			,1	 ,0,"L",0);
				$pdf->cell(30 ,5,'CPF/CNPJ',"TB" ,1,"L",0);
				$pdf->setfont('arial','',10);

				$sSqlCgm  = " select *											  ";
				$sSqlCgm .= "	  from cgm										  ";
				$sSqlCgm .= "  where z01_numcgm = $v01_numcgm ";
		
				$rsCgm = pg_query($sSqlCgm) or die($sSqlCgm);
				$oCgm  = db_utils::fieldsMemory($rsCgm,0);
				
				$sEndereco = $oCgm->z01_ender;

				if(trim($oCgm->z01_numero)!="0" and trim($oCgm->z01_numero)!=""){
					$sEndereco .= ",{$oCgm->z01_numero} ";
				}
				if(trim($oCgm->z01_compl)!="0" and trim($oCgm->z01_compl)!=""){
					$sEndereco .= ",{$oCgm->z01_compl} ";
				}
				if(trim($oCgm->z01_bairro)!="0" and  trim($oCgm->z01_bairro)!=""){
					$sEndereco .= ",{$oCgm->z01_bairro} ";
				}
				if(trim($oCgm->z01_munic) !="0" and trim($oCgm->z01_munic)!=""){
					$sEndereco .= ",{$oCgm->z01_munic}/{$oCgm->z01_uf} ";
				}
				if(trim($oCgm->z01_cep) !="0" and trim($oCgm->z01_cep)!=""){
					$sEndereco .= "- CEP {$oCgm->z01_cep} .";
				}

				$pdf->cell(30,5,"CGM:".$oCgm->z01_numcgm,0,0,"L",0);
				$pdf->Cell(130,5,$oCgm->z01_nome,0,0,"L",0);
				$pdf->Cell(30,5,(strlen($oCgm->z01_cgccpf)== 14?db_formatar($oCgm->z01_cgccpf,'cnpj'):db_formatar($oCgm->z01_cgccpf,'cpf')),0,1,"L",0);
				$pdf->MultiCell(190,5,$sEndereco,0,"L",0);
				$pdf->setfont('','');
				$pdf->cell(190,0.5,'',1,1,"L",0);
				$pdf->Ln(2);
			
				$aCgm[]    = $v01_numcgm; 	
			
			}
		}

	}


	$pdf->setfont('','B',10);
	db_fieldsmemory($result3,0);
	$res_proced = pg_query("select * from proced where v03_codigo = $v01_proced");
	db_fieldsmemory($res_proced,0);



	if(@$v03_tributaria == 't'){
      if ($pdf->gety()>$pdf->h -66){
            $pdf->addPage();
            $pdf->SetFont('ARIAL','B',11);
            $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
            $pdf->ln(8);
            $pdf->setfont('','B',9);
        }

		$pdf->MultiCell(0,5,$fundamento_inicio,0,"L",0);
	  
		if ($fundamento != "") {
			
      if ($pdf->gety()>$pdf->h -66){
        $pdf->addPage();
        $pdf->SetFont('ARIAL','B',11);
        $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
        $pdf->ln(8);
        $pdf->setfont('','B',9);
      }

     for($qp=0;$qp<pg_numrows($resultp);$qp++){
        db_fieldsmemory($resultp,$qp);
		    $pdf->Ln(2);
		    $pdf->MultiCell(0,5,$db02_texto,0,"L",0);
      }
	    $pdf->Ln(3);
	  } else {
	    $pdf->Ln(1);
	  }
	
		if ($pdf->gety()>$pdf->h -35){
	    $pdf->addPage();
	    $pdf->SetFont('ARIAL','B',11);
	    $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
	     $pdf->ln(8);
	    $pdf->setfont('','B',9);
	  }
	  
	  $pdf->MultiCell(0,5,'C R É D I T O    T R I B U T Á R I O ',0,"C",0);
	
	}else{
	  
		$pdf->MultiCell(0,5,$fundamento_inicio,0,"L",0);
	  $pdf->Ln(4);
    for($qp=0;$qp<pg_numrows($resultp);$qp++){
      db_fieldsmemory($resultp,$qp);
      $pdf->Ln(2);
      $pdf->MultiCell(0,5,$db02_texto,0,"L",0);
    }


	if ($pdf->gety()>$pdf->h -35){
    $pdf->addPage();
    $pdf->SetFont('ARIAL','B',11);
    $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
     $pdf->ln(8);
    $pdf->setfont('','B',9);
  }


	  $pdf->MultiCell(0,5,'C R É D I T O   N Ã O   T R I B U T Á R I O ',0,"C",0);
	  // db02_texto
		$sqlparag = "select *
									 from db_documento 
												inner join db_docparag  on db03_docum   = db04_docum
												inner join db_tipodoc   on db08_codigo  = db03_tipodoc
												inner join db_paragrafo on db04_idparag = db02_idparag 
									where db03_tipodoc = 1018 
										and db03_instit  = ".db_getsession("DB_instit")." order by db04_ordem ";
	  
		$resparag = pg_query($sqlparag);
	  
		if ( pg_numrows($resparag) == 0 ) {
	    db_redireciona('db_erros.php?fechar=true&db_=Configure o documento da CDA!');
	    exit;
	  }
	  
		$numrows = pg_numrows($resparag);
	  for($i=0;$i<$numrows;$i++){
	    db_fieldsmemory($resparag,$i);

	    if ($db04_ordem == '2'){
	      @$metodo = $db02_texto;
	    }
	    if ($db04_ordem == '3'){
	      @$presente = $db02_texto;
	    }
	    if ($db04_ordem == '4'){
	      @$asssec = $db02_texto;
	    }
	    if ($db04_ordem == '5'){
	      @$asscoord = $db02_texto;
	    }
	  }
	}

	$pdf->Ln(5);
	$comp       = 0;
	$pagina     = 0;
	$Tvlrhis    = 0;
	$Tvlrcor    = 0;
	$Tvlrmulta  = 0;
	$Tvlrjuros  = 0;
	$Ttotal     = 0;
	$y          = 0;
	$exeant     = 0;
	$Tavlrhis   = 0;
	$Tavlrcor   = 0;
	$Tavlrmulta = 0;
	$Tavlrjuros = 0;
	$Tatotal    = 0;
	$passa      = 't';

	$pdf->SetFont('','B',6);
	$pdf->Cell(10,5,"EXERC.",1,0,"C",1);
	$pdf->Cell(10,5,"PARC",1,0,"C",1);
	$pdf->Cell(10,5,"LIV/FOL",1,0,"C",1);
	$pdf->Cell(15,5,"ORIG.",1,0,"C",1);
	$pdf->Cell(30,5,"PROCEDÊNCIA",1,0,"C",1);
	$pdf->Cell(18,5,"ORIGEM DÉBITO",1,0,"C",1);
	$comp = 18;
	$pdf->Cell(18,5,"DATA INSCR.",1,0,"C",1);
	$pdf->Cell(18,5,"DATA VENC.",1,0,"C",1);
	$pdf->Cell(15,5,"VLR HIST.",1,0,"C",1);
	$pdf->Cell(15,5,"CORRIGIDO",1,0,"C",1);
	$pdf->Cell(10,5,"MULTA",1,0,"C",1);
	$pdf->Cell(10,5,"JUROS",1,0,"C",1);
	$pdf->Cell(15,5,"TOTAL",1,1,"C",1);

	for($ii = 0; $ii < pg_numrows($result3);$ii++){

	  db_fieldsmemory($result3,$ii);
	  $sqlprocura = "select * from arrecad where k00_numpre = $v01_numpre";
	  $resultprocura = pg_exec($sqlprocura) or die($sqlprocura);
	  if (pg_numrows($resultprocura) > 0) {
	    $result2 = debitos_numpre($v01_numpre,0,0,$dataemis,$anoemis,$v01_numpar);
		} else {
	    $sqlprocura = "select * from arreold where k00_numpre = $v01_numpre";
	    $resultprocura = pg_exec($sqlprocura) or die($sqlprocura);
	    if (pg_numrows($resultprocura) > 0) {
	      $result2 = debitos_numpre_old($v01_numpre,0,0,$dataemis,$anoemis,$v01_numpar,'','');
	    }else{

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
    //echo "<b>inserarreold</b>: $sqlInsertArreold<br><br>";
		pg_query($sqlInsertArreold) or die($sqlInsertArreold);
		$result2 = debitos_numpre_old($v01_numpre,0,0,$dataemis,$anoemis,$v01_numpar,'','');
	  //db_criatabela($result2);exit;    
				}else{
		db_redireciona('db_erros.php?fechar=true&db_erro=Débitos pagos ou cancelados, consulte pagamentos!');
		exit;
	      }    
	    }
	  }
//testa se o retorno da função debitos_numpre_old
    if ($result2){ 
	      $num = pg_num_rows($result2);
    }else{
        $num=0;
    }
//
	  for($i = 0;$i < $num;$i++) {
	    db_fieldsmemory($result2,$i);
	    /*=================================================================*/
        //Totaliza por exercício
	    
		if (isset($totexe) && $totexe == 't'){
           
		   //pega o primeiro exercio
           if($ii == 0){
  	        $exeant      = $v01_exerc; 
           }

       //mostra se o exercício for diferente e não por o primeiro registro
	   if ( $v01_exerc != $exeant && $ii > 1) {

		$pdf->SetFont('','B',6);
		$pdf->Cell(111+$comp,5,"TOTAL EXERCICIO - $exeant",1,0,"C",0);
		$pdf->Cell(15,5,db_formatar($Tavlrhis,'f'),1,0,"R",0);
		$pdf->Cell(15,5,db_formatar($Tavlrcor,'f'),1,0,"R",0);
		$pdf->Cell(10,5,db_formatar($Tavlrmulta,'f'),1,0,"R",0);
		$pdf->Cell(10,5,db_formatar($Tavlrjuros,'f'),1,0,"R",0);
		$pdf->Cell(15,5,db_formatar($Tatotal,'f'),1,1,"R",0);
		$pdf->setfont('','B',9);
        $exeant      = $v01_exerc; 
		$Tavlrhis    = 0;
		$Tavlrcor    = 0;
		$Tavlrmulta  = 0;
		$Tavlrjuros  = 0;
		$Tatotal     = 0;
	   }

	   }
	    /*=================================================================*/
	    if ($y > 272){

	      $pdf->AddPage();
        $pdf->SetFont('ARIAL','B',11);
        $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
        $pdf->ln(8);
	      $pdf->SetFont('','B',6);
	      $pdf->Cell(10,5,"EXERC.",1,0,"C",1);
	      $pdf->Cell(10,5,"PARC",1,0,"C",1);
	      $pdf->Cell(10,5,"LIV/FOL",1,0,"C",1);
	      $pdf->Cell(15,5,"ORIG.",1,0,"C",1);
	      $pdf->Cell(30,5,"PROCEDÊNCIA",1,0,"C",1);
	      $pdf->Cell(18,5,"ORIGEM DÉBITO",1,0,"C",1);
	      $comp = 18;
	      $pdf->Cell(18,5,"DATA INSCR.",1,0,"C",1);
	      $pdf->Cell(18,5,"DATA VENC.",1,0,"C",1);
	      $pdf->Cell(15,5,"VLR HIST.",1,0,"C",1);
	      $pdf->Cell(15,5,"CORRIGIDO",1,0,"C",1);
	      $pdf->Cell(10,5,"MULTA",1,0,"C",1);
	      $pdf->Cell(10,5,"JUROS",1,0,"C",1);
	      $pdf->Cell(15,5,"TOTAL",1,1,"C",1);
	      $pagina = $pdf->PageNo();
	    }
	    $pdf->SetFont('','',6);
	    $pdf->Cell(10,5,$v01_exerc,1,0,"C",0);
	    $pdf->Cell(10,5,$v01_numpar,1,0,"C",0);
	    $pdf->Cell(10,5,$v01_livro."/".$v01_folha,1,0,"C",0);
	    if ($matric != '0'){
	      $pdf->Cell(15,5,"Mat/".$matric,1,0,"C",0);
	    }elseif ($inscr != '0'){
	      $pdf->Cell(15,5,"Inscr/".$inscr,1,0,"C",0);
	    }elseif ($contr != '0'){
	      $pdf->Cell(15,5,"Contr/".$contr,1,0,"C",0);
	    }else{
	      $pdf->Cell(15,5,"Cgm/".$z01_numcgm,1,0,"C",0);
	    }
	    $pdf->Cell(30,5,$v03_descr,1,0,"L",0);
	    if (isset($j34_setor) && $j34_setor != "" && isset($j34_quadra) && $j34_quadra != "" && isset($j34_lote) && $j34_lote != ""){
	      $pdf->Cell(18,5,$j34_setor."/".$j34_quadra."/".$j34_lote,1,0,"C",0);
	      $comp = 18;
	    }else{
	      $pdf->Cell(18,5,"".$j34_lote,1,0,"C",0);
	      $comp = 18;
	    }
	    $pdf->Cell(18,5,db_formatar($v01_dtinsc,'d'),1,0,"C",0);
	    $pdf->Cell(18,5,db_formatar($k00_dtvenc,'d'),1,0,"C",0);
	    $pdf->Cell(15,5,db_formatar($vlrhis,'f')    ,1,0,"R",0);
	    $pdf->Cell(15,5,db_formatar($vlrcor,'f')		,1,0,"R",0);
	    if ($v13_certidmassa == 0){
	      $pdf->Cell(10,5,db_formatar($vlrmulta,'f'),1,0,"R",0);
	      $pdf->Cell(10,5,db_formatar($vlrjuros,'f'),1,0,"R",0);
	      $pdf->Cell(15,5,db_formatar($total,'f')		,1,1,"R",0);
	      $Tvlrmulta += $vlrmulta;
	      $Tvlrjuros += $vlrjuros;
	      $Ttotal    += $total;
	    } else {
	      $pdf->Cell(10,5,db_formatar(0,'f')			,1,0,"R",0);
	      $pdf->Cell(10,5,db_formatar(0,'f')			,1,0,"R",0);
	      $pdf->Cell(15,5,db_formatar($vlrcor,'f'),1,1,"R",0);
	      $Ttotal    += $vlrcor;
	    }

	    if ( $oPardiv->v04_imphistcda == "t" && isset($v01_obs)) {
				$pdf->SetFont('','I',5);
				$pdf->setX(10);
				$pdf->Cell(194,4,"Observação: $v01_obs",1,1,"L",0);
				$pdf->SetFont('','',6);
			}
			
	    
			$y = $pdf->GetY();
	    $Tvlrhis   += $vlrhis;
	    $Tvlrcor   += $vlrcor;

	    $Tavlrhis    += $vlrhis  ;
	    $Tavlrcor    += $vlrcor  ;
	    $Tavlrmulta  += $vlrmulta;
	    $Tavlrjuros  += $vlrjuros;
	    $Tatotal     += $total   ;


	  }
	}
	/*=================================================================*/
	//Mostra o total do ultimo exercício.
	if (isset($totexe) && $totexe == 't' && $ii == pg_numrows($result3) ){
	    $pdf->SetFont('','B',6);
	    $pdf->Cell(111+$comp,5,"TOTAL EXERCICIO - $exeant",1,0,"C",0);
	    $pdf->Cell(15,5,db_formatar($Tavlrhis,'f'),1,0,"R",0);
	    $pdf->Cell(15,5,db_formatar($Tavlrcor,'f'),1,0,"R",0);
	    $pdf->Cell(10,5,db_formatar($Tavlrmulta,'f'),1,0,"R",0);
	    $pdf->Cell(10,5,db_formatar($Tavlrjuros,'f'),1,0,"R",0);
	    $pdf->Cell(15,5,db_formatar($Tatotal,'f'),1,1,"R",0);
	    $pdf->setfont('','B',9);
	    $exeant      = $v01_exerc;
	    $Tavlrhis    = 0;
	    $Tavlrcor    = 0;
	    $Tavlrmulta  = 0;
	    $Tavlrjuros  = 0;
	    $Tatotal     = 0;
	}
	/*=================================================================*/
	$pdf->SetFont('','B',6);
	$pdf->Cell(111+$comp,5,"TOTAL",1,0,"C",0);
	$pdf->Cell(15,5,db_formatar($Tvlrhis,'f'),1,0,"R",0);
	$pdf->Cell(15,5,db_formatar($Tvlrcor,'f'),1,0,"R",0);
	$pdf->Cell(10,5,db_formatar($Tvlrmulta,'f'),1,0,"R",0);
	$pdf->Cell(10,5,db_formatar($Tvlrjuros,'f'),1,0,"R",0);
	$pdf->Cell(15,5,db_formatar($Ttotal,'f'),1,1,"R",0);
	$pdf->setfont('','B',9);
	$pdf->Ln(5);

  if ($pdf->gety()>$pdf->h -35){
    $pdf->addPage();
    $pdf->SetFont('ARIAL','B',11);
    $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
     $pdf->ln(8);
    $pdf->setfont('','B',9);
  }

  //aqui buscar todas as metodologias de cálculo conforme certidao emitida
  $sMetCalculo = "select distinct v80_docmetcalculo,
                                  db02_texto 
                             from db_documento 
  														  	inner join procedparag on procedparag.v80_docmetcalculo = db_documento.db03_docum 
																	inner join db_docparag  on db03_docum   = db04_docum
																	inner join db_tipodoc   on db08_codigo  = db03_tipodoc
																	inner join db_paragrafo on db04_idparag = db02_idparag 
													  where db03_tipodoc = 1050 and v80_proced in ( select v01_proced 
													                                                  from divida 
																							                                    inner join certdiv on certdiv.v14_coddiv = divida.v01_coddiv 
															                                              where v14_certid = $v14_certid)";
															
	
	$resMetCalculo = pg_query($sMetCalculo);
	if(pg_num_rows($resMetCalculo)){
		$iNumRows = pg_num_rows($resMetCalculo);
		for($v=0; $v < $iNumRows; $v++){
			db_fieldsmemory($resMetCalculo,$v);
			$pdf->MultiCell(0,5,@$db02_texto,0,"L",0);
			if ($pdf->gety()>$pdf->h -35){
	    	$pdf->addPage();
	      $pdf->SetFont('ARIAL','B',11);
	      $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
	      $pdf->ln(8);
	      $pdf->setfont('','B',9);
	    }	
		}
		
	}
  
	//$pdf->MultiCell(0,5,@$metodo,0,"L",0);

  if ($pdf->gety()>$pdf->h -35){
    $pdf->addPage();
      $pdf->SetFont('ARIAL','B',11);
      $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
      $pdf->ln(8);
      $pdf->setfont('','B',9);
    }

	//parag 1
	$pdf->MultiCell(0,5,@$presente,0,"L",0);
  if ($pdf->gety()>$pdf->h -35){
      $pdf->addPage();
      $pdf->SetFont('ARIAL','B',11);
      $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$v14_certid."/".$anocertid,0,"C",0,0);
       $pdf->ln(8);
      $pdf->setfont('','B',9);
   }


	$pdf->MultiCell(0,4,$munic.', '.$xdia." de ".db_mes( $xmes )." de ".$xano.'.',0,"R",0);
  $pdf->setfont('','',1);
  $pdf->MultiCell(0,2,"",0,"R",0);
	$pdf->setfont('','',10);
	
  if(!empty($asssec))
	  $sec =  "______________________________"."\n".$asssec;
	else
	  $sec =  "";

	if(!empty($asscoord))
	  $coor =  "______________________________"."\n".$asscoord;
	else
	  $coor =  "";

	$pdf->SetFont('','B',10);
	$largura = ( $pdf->w ) / 2;
//	$pdf->ln(1);
	$posy = $pdf->gety();

	if (isset($assinaturas_php)&&$assinaturas_php!=""){
	  eval($assinaturas_php);
	}else{
	  if ($coor != "") {
	    $pdf->multicell($largura-20,4,$coor,0,"C",0,0);
	  } else {
	    $pdf->Cell(1,3,"",0,0,"C",0);
	  }
	  $pdf->sety($posy);
	  if ($sec != "") {
	    $pdf->Cell($largura-10,3,"",0,0,"C",0);
	    $pdf->multicell($largura,4,$sec,0,"C",0,0);
	  }else {
	    $pdf->Cell(100,3,"",0,0,"C",0);
	  }
	}
}

$pdf->Output();

?>