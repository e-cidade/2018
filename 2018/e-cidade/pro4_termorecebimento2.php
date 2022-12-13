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
 include("fpdf151/scpdf.php");
   $codtran = 5; 
   $sqlpara= "select pd.descrdepto as pdepto,
                     pu.nome as pnome pusu,
                     dd.descrdepto as ddepto,
                     du.nome as dnome dusu,
                     to_char(p62_dttran,'DD/MM/YYYY') as dttran
              from   proctransferproc inner join proctransfer on 
                     p63_codtran = p62_codtran
                     inner join db_depart pd on pd.coddepto =  p62_coddeptorec
                     inner join db_depart dd on dd.coddepto =  p62_coddepto
                     inner join db_usuarios pu on pu.id_usuario = p62_id_usorec
                     inner join db_usuarios du on du.id_usuario = p62_id_usuario
              where p63_codtran = $codtran limit 1";
 /*   $sql = "select p63_codproc,
                  p58_requer,
                  p51_descr,
                  descrdepto,
                  nome,
                  to_char(p58_dtproc,'DD/MM/YYYY') as dtproc,
                  to_char(p58_dtproc,'YYYY') as anoproc
           from   proctransferproc inner join protprocesso on 
                  p63_codproc = p58_codproc
                  inner join tipoproc on p58_codigo = p51_codigo
          where p63_codtran = $codtran";*/
   $rspara = pg_exec($sqlpara);
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->settopmargin(1);
        $pdf->line(2,148.5,208,148.5);
	$xlin = 20;
	$xcol = 4;
        db_fieldsmemory($rspara,0);
       	for ($i = 0;$i < 2;$i++){
		$pdf->setfillcolor(245);
		$pdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
		$pdf->setfillcolor(255,255,255);
//		$pdf->roundedrect(10,07,190,183,2,'DF','1234');
		$pdf->Setfont('Arial','B',11);
		//$pdf->text(150,$xlin-13,'RECIBO VÁLIDO ATÉ: ');
		//$pdf->text(159,$xlin-8,$this->datacalc);
		$pdf->Image('imagens/files/Brasao.png',15,$xlin-17,12);
		$pdf->Setfont('Arial','B',9);
		$pdf->text(40,$xlin-15,'PREFEITURA MUNICIPAL DE SAPIRANGA');
		$pdf->Setfont('Arial','',9);
		$pdf->text(40,$xlin-11,'Av. João Correa,793');
		$pdf->text(40,$xlin-8,'Sapiranga');
		$pdf->text(40,$xlin-5,'(051)5994499');
		$pdf->text(40,$xlin-2,'prefeitura@sapiranga.rs.gov.br');
//		$pdf->setfillcolor(245);
	
		$pdf->Roundedrect($xcol,$xlin+2,$xcol+119,20,2,'DF','1234');
		$pdf->Setfont('Arial','',6);
		$pdf->text($xcol+2,$xlin+4,'Para:');
		$pdf->Setfont('Arial','',8);
	//	$pdf->text($xcol+2,$xlin+7,$this->tipoinscr);
	//	$pdf->text($xcol+17,$xlin+7,$this->nrinscr);
		$pdf->text($xcol+30,$xlin+7,'Nome :');
		$pdf->text($xcol+40,$xlin+7,$pusu);
		$pdf->text($xcol+2,$xlin+11,'Departamento :');
		$pdf->text($xcol+17,$xlin+11,$pdepto);
		//$pdf->text($xcol+2,$xlin+15,'Município :');
	//	$pdf->text($xcol+17,$xlin+15,$this->munic);
	//	$pdf->text($xcol+75,$xlin+15,'CEP :');
	//	$pdf->text($xcol+82,$xlin+15,$this->cep);
		$pdf->text($xcol+2,$xlin+19,'Data :');
		$pdf->text($xcol+17,$xlin+19,date('d/m/Y'));
		$pdf->text($xcol+40,$xlin+19,'Hora: '.date("H:i:s"));
		$pdf->text($xcol+75,$xlin+19,'IP :');
		$pdf->text($xcol+82,$xlin+19,$this->ip);
		$pdf->Setfont('Arial','',6);
	
		$pdf->Roundedrect($xcol+126,$xlin+2,76,20,2,'DF','1234');
		$pdf->text($xcol+128,$xlin+7,$this->tipoinscr);
		$pdf->text($xcol+145,$xlin+7,$this->nrinscr);
		$pdf->text($xcol+128,$xlin+11,'Logradouro :');
		$pdf->text($xcol+145,$xlin+11,$this->nomepri);
		$pdf->text($xcol+128,$xlin+15,'N'.chr(176).'/Compl :');
		$pdf->text($xcol+145,$xlin+15,$this->nrpri."      ".$this->complpri);
		$pdf->text($xcol+128,$xlin+19,'Bairro :');
		$pdf->text($xcol+145,$xlin+19,$this->bairropri);

//		$pdf->setfillcolor(245);
		$pdf->Roundedrect($xcol,$xlin+24,202,45,2,'DF','1234');
	   	$pdf->sety($xlin+28);
                $maiscol = 0;
                $yy = $pdf->gety();
		for($ii = 0;$ii < $this->linhasdadospagto ;$ii++) {
                   if ($ii == 10 ){
                      $maiscol = 100;
                      $pdf->sety($yy);
                   }
	   	   $pdf->setx($xcol+3+$maiscol);
	   	   $pdf->cell(5,3,trim(pg_result($this->recorddadospagto,$ii,$this->receita)),0,0,"R",0);
           	   if ( trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita) ) == ''){
     		      $pdf->cell(70,3,trim(pg_result($this->recorddadospagto,$ii,$this->dreceita)),0,0,"L",0);
           	   }else{ 
	  	      $pdf->cell(70,3,trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita)),0,0,"L",0);
        	   }
 		   $pdf->cell(15,3,db_formatar(pg_result($this->recorddadospagto,$ii,$this->valor),'f'),0,1,"R",0);
		}
		$pdf->Roundedrect($xcol,$xlin+71,202,30,2,'DF','1234');
		$pdf->SetY($xlin+72);
		$pdf->SetX($xcol+3);
		$pdf->multicell(0,4,'HISTÓRICO :   '.$this->historico);
		$pdf->SetX($xcol+3);
		$pdf->multicell(0,4,$this->histparcel);
		$pdf->Setfont('Arial','',6);
		$pdf->setx(15);

		$pdf->Roundedrect(128,$xlin+103,38,10,2,'DF','1234');
		$pdf->Roundedrect(168,$xlin+103,38,10,2,'DF','1234');
		$pdf->Roundedrect(146,$xlin+115,40,10,2,'DF','1234');
		$pdf->text(130,$xlin+105,'Vencimento');
		$pdf->text(170,$xlin+105,'Código de Arrecadação');
		$pdf->text(148,$xlin+118,'Valor a Pagar');
		$pdf->setfont('Arial','',10);
		$pdf->text(135,$xlin+110,$this->dtvenc);
		$pdf->text(170,$xlin+110,$this->numpre);
		$pdf->text(155,$xlin+123,$this->valtotal);

	/*	$pdf->setfillcolor(0,0,0);
		$pdf->SetFont('Arial','',4);
	        $pdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
		$pdf->setfont('Arial','',11);
		$pdf->text(10,$xlin+108,$this->linhadigitavel);
		$pdf->int25(10,$xlin+110,$this->codigobarras,15,0.341);
	        $xlin = 169;
     */
     }
 ?>