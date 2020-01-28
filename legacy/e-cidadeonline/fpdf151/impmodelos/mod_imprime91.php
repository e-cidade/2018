<?php
$this->objpdf->AliasNbPages();
  $this->objpdf->settopmargin(1);
        $this->objpdf->line(2,148.5,208,148.5);
       
        if($this->seq == 0){
    $xlin  = 20;
    $this->objpdf->AddPage();
  }else{        
    $xlin = 171;
  }
  
  $xcol  = 4;
  $cinza = 225;
//        for ($i = 0;$i < $this->linhasenvelope;$i++){
    $this->objpdf->setfillcolor(225);
    $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
    $this->objpdf->setfillcolor(255,255,255);
//    $this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
    $this->objpdf->Setfont('Arial','B',11);
    $this->objpdf->text(130,$xlin-13,'RECIBO DE PAGAMENTO');
    $this->objpdf->text(130,$xlin-8,'REF. AO MÊS '.db_formatar($this->mes,'s','0',2,'e',0).'/'.$this->ano);
    $this->objpdf->text(130,$xlin-3,$this->qualarquivo);
    
    $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12); //.$this->logo
    $this->objpdf->Setfont('Arial','B',9);
    $this->objpdf->text(30,$xlin-15,$this->prefeitura);
    $this->objpdf->Setfont('Arial','',7);
    $this->objpdf->text(30,$xlin-12,$this->enderpref);
    $this->objpdf->text(30,$xlin- 9,$this->municpref);
    $this->objpdf->text(30,$xlin- 6,$this->telefpref);
    $this->objpdf->text(30,$xlin- 3,db_formatar($this->cgcpref,'cnpj'));
  
    ///retangulo da assinatura
    $this->objpdf->Roundedrect($xcol+178,$xlin+14,$xcol+20,110,2,'DF','1234');

    //retangulo onde fica no nome do funcionario
    $this->objpdf->Roundedrect($xcol,$xlin,$xcol+198,12,2,'DF','1234');

    $this->objpdf->Roundedrect($xcol,$xlin+14,$xcol+172,82,2,'DF','1234');
    $this->objpdf->Roundedrect($xcol,$xlin+96,$xcol+172,28,2,'DF','1234');
    $this->objpdf->line($xcol,$xlin+22,$xcol+176,$xlin+22);
    $this->objpdf->line($xcol,$xlin+115,$xcol+176,$xlin+115);
    $this->objpdf->line($xcol+130,$xlin+105,$xcol+176,$xlin+105);
    
    $this->objpdf->line($xcol+153,$xlin+14,$xcol+153,$xlin+115);
    $this->objpdf->line($xcol+130,$xlin+14,$xcol+130,$xlin+115);
    $this->objpdf->line($xcol+115,$xlin+14,$xcol+115,$xlin+96);
    $this->objpdf->line($xcol+15,$xlin+14,$xcol+15,$xlin+96);

    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($xcol+2,$xlin+3,'Matrícula:');
    $this->objpdf->Setfont('Arial','B',7);
    $this->objpdf->text($xcol+12,$xlin+3,$this->registro);

    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($xcol+21,$xlin+3,'Nome:');
    $this->objpdf->Setfont('Arial','B',7);
    $this->objpdf->text($xcol+31,$xlin+3,$this->nome);

    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($xcol+100,$xlin+3,'Função:');
    $this->objpdf->Setfont('Arial','B',7);
    $this->objpdf->text($xcol+110,$xlin+3,$this->descr_funcao);

    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($xcol+170,$xlin+3,'Padrão:');
    $this->objpdf->Setfont('Arial','B',7);
    $this->objpdf->text($xcol+180,$xlin+3,$this->padrao);

    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($xcol+2,$xlin+7,'Lotação:');
    $this->objpdf->Setfont('Arial','B',7);
    $this->objpdf->text($xcol+12,$xlin+7,$this->descr_lota);
    
    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($xcol+100,$xlin+7,'Bco/Ag/Cta:');
    $this->objpdf->Setfont('Arial','B',7);
    $this->objpdf->text($xcol+112,$xlin+7,$this->banco.' / '.$this->agencia.' / '.$this->conta);

    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($xcol+170,$xlin+7,'Admissão:');
    $this->objpdf->Setfont('Arial','B',7);
    $this->objpdf->text($xcol+180,$xlin+7,$this->admissao);
        
    $this->objpdf->Setfont('Arial','',8);
    $this->objpdf->text($xcol+ 5 ,$xlin+18,'Cód.');
    $this->objpdf->text($xcol+ 55,$xlin+18,'Descrição');
    $this->objpdf->text($xcol+116,$xlin+18,'Referência');
    $this->objpdf->text($xcol+135,$xlin+18,'Proventos');
    $this->objpdf->text($xcol+157,$xlin+18,'Descontos');
    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($xcol+155,$xlin+98,'Total dos Descontos');
    $this->objpdf->text($xcol+131,$xlin+98,'Total dos Vencimentos');
    $this->objpdf->text($xcol+133,$xlin+111,'Líquido a Receber');
    $this->objpdf->setfillcolor(225);
    $this->objpdf->rect($xcol+153,$xlin+105,23,10,'DF');
    $this->objpdf->setfillcolor(255,255,255);

    $this->objpdf->text($xco+9  ,$xlin+117,'Sal. Base');
    $this->objpdf->text($xcol+30 ,$xlin+117,'Base Previdência');
    $this->objpdf->text($xcol+62 ,$xlin+117,'Base FGTS');
    $this->objpdf->text($xcol+89,$xlin+117,'FGTS do Mês');
    $this->objpdf->text($xcol+117,$xlin+117,'Base IRRF');

    
      $this->objpdf->sety($xlin+24);
                $maiscol = 0;
                $yy = $this->objpdf->gety();
                $provento = 0;
                $margem_deduz  = 0;
                $margem_consignada = 0;
    $desconto     = 0;
    $baseprev     = 0;
    $basefgts     = 0;
    $baseirrf     = 0;
    $valor_margem = 0;

    $this->objpdf->Setfont('Arial','',7);
    for($ii = 0;$ii < $this->linhasenvelope ;$ii++) {
      
               if ( pg_result($this->recordenvelope,$ii,$this->tipo)  == 'P'){
                  $this->objpdf->cell(5,3,trim(pg_result($this->recordenvelope,$ii,$this->rubrica)),0,0,"R",0);
                  $this->objpdf->cell(5,3,"",0,0,"L",0);
                  $this->objpdf->cell(93,3,pg_result($this->recordenvelope,$ii,$this->descr_rub),0,0,"L",0);
                  $this->objpdf->cell(20,3,db_formatar(pg_result($this->recordenvelope,$ii,$this->quantidade),'f'),0,0,"R",0);
                  $this->objpdf->cell(22,3,db_formatar(pg_result($this->recordenvelope,$ii,$this->valor),'f'),0,0,"R",0);
                  $this->objpdf->cell(22,3,'',0,1,"R",0);
                  $provento += pg_result($this->recordenvelope,$ii,$this->valor);
                  $rubrica = trim(pg_result($this->recordenvelope,$ii,$this->rubrica));
                  if(db_getsession("DB_instit") == 1 && strtoupper($this->municpref == 'GUAIBA') && ($rubrica == '0102' || $rubrica == '0109' || $rubrica == '0111' || $rubrica == '0195'  || $rubrica == '0196' || $rubrica == '0197' || $rubrica == '0198' )){
                    $margem_consignada += pg_result($this->recordenvelope,$ii,$this->valor);
                  }elseif(db_getsession("DB_instit") == 1 && strtoupper($this->municpref) == 'ARAPIRACA' && 
                          ($rubrica == '0005' || $rubrica == '0006' || $rubrica == '0007' || $rubrica == '0008' || 
                           $rubrica == '0011' || $rubrica == '0014' || $rubrica == '0017' || $rubrica == '0018' || 
                           $rubrica == '0020' || $rubrica == '0021' || $rubrica == '0023' || $rubrica == '0055' || 
                           $rubrica == '0060' || $rubrica == '0061' || $rubrica == '0062' || $rubrica == '0063' || 
                           $rubrica == '0064' || $rubrica == '0065' || $rubrica == '0098' || $rubrica == '0099' || 
                           $rubrica == '0101' || $rubrica == '0104' || $rubrica == '0105' || $rubrica == '0107' || 
                           $rubrica == '0108' || $rubrica == '0112' || $rubrica == '0116' || $rubrica == '0117' || 
                           $rubrica == '0118' || $rubrica == '0121' || $rubrica == '0122' || $rubrica == '0126' || 
                           $rubrica == '0129' || $rubrica == '0131' || $rubrica == '0132' || $rubrica == '0133' || 
                           $rubrica == '0134' || $rubrica == '0135' || $rubrica == '0136' || $rubrica == '0137' || 
                           $rubrica == '0138' || $rubrica == '0150' || $rubrica == '0151' || $rubrica == '0160' || 
                           $rubrica == '0170' || $rubrica == '0190' 
                           )){
                    $margem_consignada += pg_result($this->recordenvelope,$ii,$this->valor);
                  }
               }elseif( pg_result($this->recordenvelope,$ii,$this->tipo ) == 'D'){ 
                 $this->objpdf->cell(5,3,trim(pg_result($this->recordenvelope,$ii,$this->rubrica)),0,0,"R",0);
                 $this->objpdf->cell(5,3,"",0,0,"L",0);
                 $this->objpdf->cell(93,3,pg_result($this->recordenvelope,$ii,$this->descr_rub),0,0,"L",0);
                 $this->objpdf->cell(20,3,db_formatar(pg_result($this->recordenvelope,$ii,$this->quantidade),'f'),0,0,"R",0);
                 $this->objpdf->cell(22,3,'',0,0,"R",0);
                 $this->objpdf->cell(22,3,db_formatar(pg_result($this->recordenvelope,$ii,$this->valor),'f'),0,1,"R",0);
                 $desconto += pg_result($this->recordenvelope,$ii,$this->valor);
                 $rubrica = trim(pg_result($this->recordenvelope,$ii,$this->rubrica));
                 if(db_getsession("DB_instit") == 1 && strtoupper($this->municpref) == 'ARAPIRACA' ){
                   if($rubrica == 'R901' || $rubrica == 'R904' || $rubrica == 'R913' || $rubrica == '0333' ){
                     $margem_consignada -= pg_result($this->recordenvelope,$ii,$this->valor);
                   }elseif($rubrica == '0330' || 
                           $rubrica == '0334' || 
                           $rubrica == '0335' || 
                           $rubrica == '0336' || 
                           $rubrica == '0337' || 
                           $rubrica == '0338' || 
                           $rubrica == '0340' || 
                           $rubrica == '0341' || 
                           $rubrica == '0342' || 
                           $rubrica == '0343' || 
                           $rubrica == '0344' || 
                           $rubrica == '0345' 
                          ){
                     $margem_deduz += pg_result($this->recordenvelope,$ii,$this->valor);
                   }
                 }
             }else{
         if(pg_result($this->recordenvelope,$ii,$this->rubrica) == 'R981' ||
            pg_result($this->recordenvelope,$ii,$this->rubrica) == 'R982' ){
            $baseirrf += pg_result($this->recordenvelope,$ii,$this->valor);
         }elseif(pg_result($this->recordenvelope,$ii,$this->rubrica) == 'R992'){
            $baseprev += pg_result($this->recordenvelope,$ii,$this->valor);
         }elseif(pg_result($this->recordenvelope,$ii,$this->rubrica) == 'R991'){
            $basefgts += pg_result($this->recordenvelope,$ii,$this->valor);
         }elseif(pg_result($this->recordenvelope,$ii,$this->rubrica) == 'R803'){
            $valor_margem += pg_result($this->recordenvelope,$ii,$this->valor);
         }
          continue;
       }
    }
    
    $this->objpdf->text($xcol+134,$xlin+102,db_formatar($provento,'f'));
    $this->objpdf->text($xcol+157,$xlin+102,db_formatar($desconto,'f'));
    $this->objpdf->Setfont('Arial','B',9);
    $this->objpdf->text($xcol+157,$xlin+111,db_formatar(( $provento - $desconto ),'f'));
    $this->objpdf->Setfont('Arial','',8);

    
    $this->objpdf->SetY($xlin+119);
    $this->objpdf->SetX($xcol-1);
    $this->objpdf->cell(16, 3, db_formatar($this->f010,'f'), 0, 0, 'R');
    $this->objpdf->SetX($xcol+31);
    $this->objpdf->cell(16, 3, db_formatar($baseprev,'f'), 0, 0, 'R');
    $this->objpdf->SetX($xcol+58);
    $this->objpdf->cell(16, 3, db_formatar($basefgts,'f'), 0, 0, 'R');
    $this->objpdf->SetX($xcol+87);
    $this->objpdf->cell(16, 3, db_formatar(($basefgts*8/100),'f'), 0, 0, 'R');
    $this->objpdf->SetX($xcol+112);
    $this->objpdf->cell(16, 3, db_formatar($baseirrf,'f'), 0, 0, 'R');
    
    $this->objpdf->SetY($xlin+97);
    $this->objpdf->SetX($xcol+3);
    $this->objpdf->multicell(125,4,'MENSAGEM :   '.$this->mensagem,0,"J");
    $this->objpdf->SetX($xcol+3);
    $this->objpdf->multicell(0,4,$this->histparcel);
    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->setx(15);
    $this->objpdf->setfillcolor(0);
    $this->objpdf->Setfont('Arial','',5);
    $this->objpdf->TextWithDirection(185,$xlin+120,'DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMIDA NESTE RECIBO.','U'); // texto no canhoto do carne
    $this->objpdf->line($xcol+193,$xlin+25,$xcol+193,$xlin+70);
    $this->objpdf->line($xcol+193,$xlin+75,$xcol+193,$xlin+115);
    $this->objpdf->TextWithDirection(200,$xlin+97,'DATA','U'); // texto no canhoto do carne
    $this->objpdf->TextWithDirection(200,$xlin+60,'ASSINATURA DO FUNCIONÁRIO','U'); // texto no canhoto do carne
    $this->objpdf->TextWithDirection(209.7,$xlin,$this->total.' / '.$this->numero,'U'); // numero do contra-cheque
    $this->objpdf->TextWithDirection(205,$xlin+120,"Para Verificar Autenticidade Acesse: ".$this->url,'U');
    $this->objpdf->TextWithDirection(205,$xlin+60,"Código da Autenticação: ",'U');
    $this->objpdf->Setfont('Arial','B',5); 
    $this->objpdf->TextWithDirection(205,$xlin+40,$this->codautent,'U');
?>
