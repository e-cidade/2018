<?
##Modelo de nota Fiscal 
$confNumRows = pg_num_rows($this->rsConfig);
for ($j = 0;$j < $confNumRows;$j++){

    $oConf            = db_utils::fieldsmemory($this->rsConfig ,$j);  
    $xlin             = 20;
    $xcol             = 4;
    $this->fTotaliUni = 0;
    $this->fTotal     = 0;
    $this->fvlrIssqn  = 0;
    $this->objpdf->AliasNbPages();
    $this->objpdf->AddPage();
    $this->objpdf->settopmargin(1);
    $this->objpdf->setfillcolor(245);
    $this->objpdf->setfillcolor(255, 255, 255);
    $this->objpdf->Setfont('Arial', 'B', 10);
    $this->objpdf->Image('imagens/files/'.$this->logo, 15, $xlin -17, 12);
    $this->objpdf->Setfont('Arial', 'B', 9);
    $this->objpdf->text(40, $xlin -15, $this->prefeitura);
    $this->objpdf->Setfont('Arial', '', 8);
    $this->objpdf->text(40, $xlin -11, $this->enderpref);
    $this->objpdf->text(40, $xlin -8, $this->municpref);
    $this->objpdf->text(40, $xlin -5, $this->telefpref);
    $this->objpdf->text(40, $xlin -2, $this->emailpref);
    $this->objpdf->text(40, $xlin +1, db_formatar($this->cgcpref, 'cnpj'));

    $this->objpdf->Setfont('Arial', 'B', 10);
    $this->objpdf->text(40,$xlin+5,"NOTA FISCAL DE SERVICOS AVULSA");
    $this->objpdf->text(160,$xlin+5,"Nº ".$this->dadosPrestador->q51_numnota);
    $this->objpdf->Setfont('Arial', 'B', 8);
    $this->objpdf->sety($xlin+6);
    $this->objpdf->rect(160,3,40,10);
    $this->objpdf->text(162,6,$oConf->q67_via."ª via - ".$oConf->q67_descr);
    //Dados do Prestador
    $this->objpdf->cell(0,4,"PRESTADOR DO SERVIÇO",0,0,"C");
    $this->objpdf->rect(10,$xlin+10,190,20);
    //Nome
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$xlin+12,'NOME/RAZAO SOCIAL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text(14,$xlin+14,$this->dadosPrestador->z01_nome);
    $this->objpdf->line(10,$xlin+15,200,$xlin+15);
    //Endereco
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$xlin+17,'ENDERECO');
    $this->objpdf->Setfont('Arial', '',7 );
    $this->dadosPrestador->z01_ender = trim($this->dadosPrestador->z01_ender);
    $this->objpdf->text(14,$xlin+19,"{$this->dadosPrestador->z01_ender}, {$this->dadosPrestador->z01_numero}" );
    $this->objpdf->line(10,$xlin+20,200,$xlin+20);
    //Municipio
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$xlin+22,'MUNICIPIO');
    $this->objpdf->Setfont('Arial', '',7);
    $this->objpdf->text(14,$xlin+24,$this->dadosPrestador->z01_munic);
    $this->objpdf->line($xcol+50,$xlin+20,$xcol+50,$xlin+25);
    //UF
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol+52,$xlin+22,'UF');
    $this->objpdf->Setfont('Arial', '',7 );
    $this->objpdf->text($xcol+54,$xlin+24,$this->dadosPrestador->z01_uf);
    $this->objpdf->line($xcol+60,$xlin+20,$xcol+60,$xlin+25);
    //CPF/CNPF
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol+62,$xlin+22,'CPF/CNPJ');
    $this->objpdf->Setfont('Arial', '',7 );
    $this->objpdf->text($xcol+64,$xlin+24,$this->dadosPrestador->z01_cgccpf);
    $this->objpdf->line($xcol+100,$xlin+20,$xcol+100,$xlin+25);
    //INSCRICAO
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol+102,$xlin+22,'INSCRICAO MUNICIPAL');
    $this->objpdf->Setfont('Arial', '',7 );
    $this->objpdf->text($xcol+104,$xlin+24,$this->dadosPrestador->q02_inscr);
    $this->objpdf->line($xcol+160,$xlin+20,$xcol+160,$xlin+25);
    //Fone
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol+162,$xlin+22,'TELEFONE');
    $this->objpdf->Setfont('Arial','',7 );
    $this->objpdf->text($xcol+164,$xlin+24,$this->dadosPrestador->z01_telef);
   // $this->objpdf->line($xcol+100,$xlin+20,$xcol+100,$xlin+25);
    
    $this->objpdf->line(10,$xlin+25,200,$xlin+25);
    //Email
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$xlin+27,'EMAIL');
    $this->objpdf->Setfont('Arial', '',7);
    $this->objpdf->text(14,$xlin+29,$this->dadosPrestador->z01_email);
    $this->objpdf->line($xcol+75,$xlin+25,$xcol+75,$xlin+30);
    //CEP
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol+77,$xlin+27,'CEP');
    $this->objpdf->Setfont('Arial', '',7);
    $this->objpdf->text($xcol+79,$xlin+29,$this->dadosPrestador->z01_cep);
    $this->objpdf->line($xcol+120,$xlin+25,$xcol+120,$xlin+30);
    //BAIRRO
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol+122,$xlin+27,'BAIRRO');
    $this->objpdf->Setfont('Arial', '',7);
    $this->objpdf->text($xcol+125,$xlin+29,$this->dadosPrestador->z01_bairro);

    $xlin = 50;
    $this->objpdf->sety($xlin+4);
    //Dados do TOMADOR
    $this->objpdf->Setfont('Arial', 'B', 8);
    $this->objpdf->cell(0,4,"TOMADOR DO SERVIÇO",0,0,"C");
    $this->objpdf->rect(10,$xlin+10,190,25);
    //Nome
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$xlin+12,'NOME/RAZAO SOCIAL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text(14,$xlin+14.2,$this->dadosTomador->z01_nome);
    $this->objpdf->line(10,$xlin+15,200,$xlin+15);
    //Endereco
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$xlin+17,'ENDERECO');
    $this->objpdf->Setfont('Arial', '',7 );
    $this->dadosTomador->z01_ender = trim($this->dadosTomador->z01_ender);
    $this->objpdf->text(14,$xlin+19.2,"{$this->dadosTomador->z01_ender}, {$this->dadosTomador->z01_numero}");
    $this->objpdf->line(10,$xlin+20,200,$xlin+20);
    //Municipio
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$xlin+22,'MUNICIPIO');
    $this->objpdf->Setfont('Arial', '',7);
    $this->objpdf->text(14,$xlin+24.2,$this->dadosTomador->z01_munic);
    $this->objpdf->line($xcol+50,$xlin+20,$xcol+50,$xlin+25);
    //UF
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol+52,$xlin+22,'UF');
    $this->objpdf->Setfont('Arial', '',7 );
    $this->objpdf->text($xcol+54,$xlin+24,$this->dadosTomador->z01_uf);
    $this->objpdf->line($xcol+60,$xlin+20,$xcol+60,$xlin+25);
    //CPF/CNPF
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol+62,$xlin+22,'CPF/CNPJ');
    $this->objpdf->Setfont('Arial', '',7 );
    $this->objpdf->text($xcol+64,$xlin+24,$this->dadosTomador->z01_cgccpf);
    $this->objpdf->line($xcol+100,$xlin+20,$xcol+100,$xlin+25);
    //INSCRICAO
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol+102,$xlin+22,'INSCRICAO MUNICIPAL');
    $this->objpdf->Setfont('Arial', '',7 );
    $this->objpdf->text($xcol+104,$xlin+24,$this->dadosTomador->q02_inscr);
    $this->objpdf->line($xcol+160,$xlin+20,$xcol+160,$xlin+25);
    //Fone
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol+162,$xlin+22,'TELEFONE');
    $this->objpdf->Setfont('Arial','',7 );
    $this->objpdf->text($xcol+164,$xlin+24,$this->dadosTomador->z01_telef);
   // $this->objpdf->line($xcol+100,$xlin+20,$xcol+100,$xlin+25);
    $this->objpdf->line(10,$xlin+25,200,$xlin+25);
    //Fone
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol+8,$xlin+27,'DATA DA PRESTACAO DO SERVIÇO');
    $this->objpdf->Setfont('Arial','',7 );
    $this->objpdf->text($xcol+10,$xlin+29,db_formatar($this->dadosTomador->q53_dtservico,"d"));
    $this->objpdf->line($xcol+50,$xlin+25,$xcol+50,$xlin+30);
    //Atividade
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol+52,$xlin+27,'COD. ATIVIDADE');
    $this->objpdf->Setfont('Arial', '',7 );
  
    $this->objpdf->line(10,$xlin+30,200,$xlin+30);
    //Email
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$xlin+32,'EMAIL');
    $this->objpdf->Setfont('Arial', '',7);
    $this->objpdf->text(14,$xlin+34,$this->dadosTomador->z01_email);
    $this->objpdf->line($xcol+75,$xlin+30.2,$xcol+75,$xlin+35);
    //CEP
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol+77,$xlin+32,'CEP');
    $this->objpdf->Setfont('Arial', '',7);
    $this->objpdf->text($xcol+79,$xlin+34,$this->dadosTomador->z01_cep);
    $this->objpdf->line($xcol+120,$xlin+30,$xcol+120,$xlin+35);
    //BAIRRO
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol+122,$xlin+32,'BAIRRO');
    $this->objpdf->Setfont('Arial', '',7);
    $this->objpdf->text($xcol+125,$xlin+34.2,$this->dadosTomador->z01_bairro);
    $this->objpdf->sety($xlin+38);
    $this->objpdf->cell(15,5,"QUANT",1,0,"C");
    $this->objpdf->cell(100,5,"DISCRIMINACAO DOS SERVICOS",1,0,"C");
    $this->objpdf->cell(30,5,"VALOR UNITARIO",1,0,"C");
    $this->objpdf->cell(15,5,"ALIQ",1,0,"C");
    $this->objpdf->cell(30,5,"VALOR TOTAL",1,1,"C");
    /*
     ** Dados do Servico; 
    */ 
    $iYinicio = $this->objpdf->getY();
    $cellYnew = $this->objpdf->getY();
    $this->objpdf->line(10,$iYinicio,200,$iYinicio);
    $totlinha = 0;
    for ($i = 0; $i < pg_num_rows($this->rsServico); $i++){
 
        $this->objpdf->sety($cellYnew);
        $oItensServico     = db_utils::fieldsmemory($this->rsServico,$i);
        $totalLinha        = ($oItensServico->q62_qtd*$oItensServico->q62_vlruni);  
        $this->fTotaliUni += $totalLinha;
        $this->fTotal     += $this->fTotaliUni;
        $this->fvlrIssqn  += $oItensServico->q62_vlrissqn;
        $this->objpdf->cell(15,3,$oItensServico->q62_qtd,"LR",0,"R");
        $cellYold = $this->objpdf->getY();
        $this->objpdf->multiCell(100,3,$oItensServico->q62_discriminacao,"LR","L");
        //if ($j == 0){
        //}
        $cellYnew = $this->objpdf->getY();
        $this->objpdf->sety($cellYold);
        $this->objpdf->setx(125);
        $vlUniMostra = $oItensServico->q62_vlruni != ''?number_format($oItensServico->q62_vlruni,2,",","."):null;
        $this->objpdf->cell(30,3,$vlUniMostra,"LR",0,"R");
        $this->objpdf->cell(15,3,$oItensServico->q62_aliquota,"LR",0,"R");
        $vlTotalMostra = $totalLinha != null?number_format($totalLinha,2,".",","):null;
        $this->objpdf->cell(30,3,$vlTotalMostra,"LR",1,"R");
//        $this->objpdf->line(10,$cellYnew,200,$cellYnew);
    }
    //echo $totalLetras
    $this->objpdf->sety(220);
    $iYFinal = $this->objpdf->getY();

    $this->objpdf->line(10,$iYinicio,10,$iYFinal);
    $this->objpdf->line(25,$iYinicio,25,$iYFinal);
    $this->objpdf->line(125,$iYinicio,125,$iYFinal);
    $this->objpdf->line(155,$iYinicio,155,$iYFinal);
    $this->objpdf->line(170,$iYinicio,170,$iYFinal);
    $this->objpdf->line(200,$iYinicio,200,$iYFinal);

    //$this->objpdf->line($,$iYinicio,200,$iYinicio);
    //box com o total devido de imposto 
    $this->yOld = $this->objpdf->getY();
    $this->objpdf->rect(10,$this->yOld,115,15);
    $this->objpdf->Setfont('Arial', '', 10);

    $this->objpdf->sety($this->yOld+2);
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12,$this->yOld+2,'OBSERVAÇÕES');
		$this->objpdf->Setfont('Arial', '', 8);
    // aqui tem que colocar a obs
    $this->objpdf->MultiCell(110,3,$this->dadosPrestador->q51_obs,0,"J" );
    $this->objpdf->Setfont('Arial', '', 10);


    //valores totais da nota
    $this->objpdf->sety($this->yOld);
    $this->objpdf->setX(125);
    $this->objpdf->Setfont('Arial', '', 10);
    $this->objpdf->cell(40,5,"Valor dos Servicos",1,0);
    $this->objpdf->Setfont('Arial', '', 10);
    $this->objpdf->cell(35,5,"R$ ".number_format($this->fTotaliUni,2,",","."),1,1,"R");
    $this->objpdf->setX(125);
    $this->objpdf->cell(40,5,"Valor ISSQN",1,0);
    $this->objpdf->cell(35,5,"R$ ".number_format($this->fvlrIssqn,2,",","."),1,1,"R");
    $this->objpdf->setX(125);
    $this->objpdf->cell(40,5,"Valor Total da Nota",1,0);
    $this->objpdf->Setfont('Arial', '', 10);
    $this->objpdf->cell(35,5,"R$ ".number_format($this->fTotaliUni,2,",","."),1,1,"R");
    $this->objpdf->setDash(true,true);
    $this->objpdf->line(10,$this->yOld+35,200,$this->yOld+35);
    $this->objpdf->setDash(false,false); 
    
    //Guia destacavel

    $this->objpdf->rect(10,$this->yOld+45,190,20);
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text(12,$this->yOld+48,"REPARTICAO FISCAL");
    $this->objpdf->line(40,$this->yOld+45,40,$this->yOld+65);
    $this->objpdf->text(42,$this->yOld+48,"ASSINATURA DO EMITENTE");
    $this->objpdf->line(100,$this->yOld+45,100,$this->yOld+65);
    $this->objpdf->text(102,$this->yOld+48,"MATRICULA");
    $this->objpdf->line(140,$this->yOld+45,140,$this->yOld+65);
    $this->objpdf->text(142,$this->yOld+48,"DATA DA EMISSAO");
    $this->objpdf->Setfont('Arial', 'B',8);
    $this->objpdf->text(142,$this->yOld+55,"Nº ".db_formatar($this->dadosPrestador->q51_dtemiss,"d"));
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->line(180,$this->yOld+45,180,$this->yOld+65);
    $this->objpdf->Setfont('Arial', 'B',8);
    $this->objpdf->text(182,$this->yOld+55,"Nº ".$this->dadosPrestador->q51_numnota);
}
?>
