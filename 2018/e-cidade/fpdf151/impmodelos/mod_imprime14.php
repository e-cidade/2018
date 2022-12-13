<?php

$this->objpdf->AliasNbPages();
  $this->objpdf->AddPage();
  $this->objpdf->settopmargin(1);
  $this->objpdf->setleftmargin(4);
  $pagina = 1;
  $xlin = 30;
  $xcol = 4;

  
  $this->objpdf->setfillcolor(245);
  $this->objpdf->rect($xcol-2,$xlin-26,206,286,2,'DF','1234');
  $this->objpdf->Setfont('Arial','B',9);
  $this->objpdf->text(130,$xlin-17,'AUTORIZAÇÃO  N'.CHR(176));
  $this->objpdf->text(165,$xlin-17,db_formatar($this->codaidof,'s','0',6,'e'));
  $this->objpdf->text(180,$xlin-17,"/".$this->ano);
  $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-19,12);
  $this->objpdf->Setfont('Arial','B',9);
  $this->objpdf->text(40,$xlin-17,$this->prefeitura);
  $this->objpdf->Setfont('Arial','',9);
  $this->objpdf->text(40,$xlin-14,$this->enderpref);
  $this->objpdf->text(40,$xlin-10,$this->municpref);
  $this->objpdf->text(40,$xlin- 7,$this->telefpref);
  $this->objpdf->text(40,$xlin- 4,$this->emailpref);
  
  $this->objpdf->Setfont('Arial','b',12);
  $this->objpdf->text($xcol+6,$xlin+6,"AUTORIZAÇÃO DE IMPRESSÃO DE DOCUMENTOS FISCAIS DO IMPOSTO SOBRE SERVIÇOS");
  
  $this->objpdf->Setfont('Arial','b',8);

  $this->objpdf->text($xcol+80,$xlin+13,"ESTABELECIMENTO GRÁFICO");
  $this->objpdf->rect($xcol,$xlin+14,$xcol+198,32,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+14,$xcol+198,8,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+22,$xcol+198,8,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+30,$xcol+198,8,2,'','1234');
  $this->objpdf->rect($xcol+68,$xlin+30,$xcol+65,8,2,'','1234');
  $this->objpdf->rect($xcol+68,$xlin+38,$xcol+130,8,2,'','1234');
  $this->objpdf->Setfont('Arial','b',7);

  $this->objpdf->text($xcol+2,$xlin+19,"NOME:");
  $this->objpdf->text($xcol+2,$xlin+27,"ENDEREÇO:");
  $this->objpdf->text($xcol+2,$xlin+35,"INSCRIÇÃO ESTADUAL:");
  $this->objpdf->text($xcol+70,$xlin+35,"INSCRIÇÃO MUNICIPAL:");
  $this->objpdf->text($xcol+139,$xlin+35,"INSCRIÇÃO DO CNPJ/CPF:");
  $this->objpdf->text($xcol+2,$xlin+43,"TELEFONE:");
  $this->objpdf->text($xcol+70,$xlin+43,"CEP:");

  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+12,$xlin+19,$this->nome_graf);
  $this->objpdf->text($xcol+22,$xlin+27,$this->ender_graf);
  $this->objpdf->text($xcol+36,$xlin+35,$this->inscr_est);
  $this->objpdf->text($xcol+106,$xlin+35,$this->inscr_graf);
  $this->objpdf->text($xcol+172,$xlin+35,$this->cnpj_graf);
  $this->objpdf->text($xcol+20,$xlin+43,$this->telef_graf);
  $this->objpdf->text($xcol+79,$xlin+43,$this->cep_graf);

  $this->objpdf->Setfont('Arial','B',7);
  $this->objpdf->text($xcol+81,$xlin+50,"ESTABELECIMENTO USUÁRIO");

  $this->objpdf->rect($xcol,$xlin+52,$xcol+198,32,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+52,$xcol+78, 8,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+52,$xcol+198,8,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+60,$xcol+198,8,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+68,$xcol+198,8,2,'','1234');
  $this->objpdf->rect($xcol+68,$xlin+68,$xcol+65,8,2,'','1234');
  $this->objpdf->rect($xcol+68,$xlin+76,$xcol+130,8,2,'','1234');

  $this->objpdf->text($xcol+2,$xlin+57, "NOME:");
  $this->objpdf->text($xcol+84,$xlin+57, "NOME FANTASIA:");
  $this->objpdf->text($xcol+2,$xlin+65, "ENDEREÇO:");
  $this->objpdf->text($xcol+2,$xlin+73, "INSCRIÇÃO ESTADUAL:");
  $this->objpdf->text($xcol+70,$xlin+73, "INSCRIÇÃO MUNICIPAL:");
  $this->objpdf->text($xcol+139,$xlin+73, "INSCRIÇÃO DO CNPJ/CPF:");
  $this->objpdf->text($xcol+2,$xlin+81, "TELEFONE:");
  $this->objpdf->text($xcol+70,$xlin+81, "CEP:");

  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+12,$xlin+57, $this->nome_usu);
  $this->objpdf->text($xcol+110,$xlin+57, $this->nome_fantasia);
  $this->objpdf->text($xcol+22,$xlin+65, $this->codigo_rua." - ".$this->ender_usu);
  $this->objpdf->text($xcol+36,$xlin+73, $this->cadest_usu);
  $this->objpdf->text($xcol+106,$xlin+73, $this->inscr_usu);
  $this->objpdf->text($xcol+172,$xlin+73, $this->cnpj_usu);
  $this->objpdf->text($xcol+20,$xlin+81, $this->telefone);
  $this->objpdf->text($xcol+79,$xlin+81, $this->cep);

  
  
  
  
  $this->objpdf->Setfont('Arial','B',8);
  $this->objpdf->text($xcol+80,$xlin+88,"DOCUMENTO A SER IMPRESSO");

  $this->objpdf->rect($xcol,$xlin+90,$xcol+198,50,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+90,$xcol+198,10,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+100,$xcol+198,10,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+90,$xcol+65,20,2,'','1234');
  $this->objpdf->rect($xcol+110,$xlin+90,$xcol+88,20,2,'','1234');

  $this->objpdf->text($xcol+25,$xlin+96,"NUMERAÇÃO");
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+20,$xlin+108,$this->notaini."   A   ".$this->notafin);
  $this->objpdf->Setfont('Arial','B',8);
  $this->objpdf->text($xcol+78,$xlin+96,"QUANTIDADE");
  $this->objpdf->text($xcol+150,$xlin+96,"ESPÉCIE");
  $this->objpdf->text($xcol+2,$xlin+115,"OBSERVAÇÕES:");
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+85,$xlin+108,$this->quant);
  $this->objpdf->text($xcol+115,$xlin+108,$this->especie);
  $this->objpdf->SetXY($xcol+2,$xlin+117);
  $this->objpdf->Setfont('Arial','',7);
  $this->objpdf->multicell(198,4,$this->obs,0,"L");   

  $this->objpdf->Setfont('Arial','B',8);
  $this->objpdf->text($xcol+40,$xlin+144,"PEDIDO");
  $this->objpdf->rect($xcol,$xlin+145,$xcol+95,80,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+145,$xcol+95,8,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+153,$xcol+95,16,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+169,$xcol+95,8,2,'','1234');
  $this->objpdf->rect($xcol,$xlin+177,$xcol+95,24,2,'','1234');

        $this->objpdf->text($xcol+140,$xlin+144,"ENTREGA");
    $this->objpdf->rect($xcol+103,$xlin+145,$xcol+95,48,2,'','1234');
    $this->objpdf->rect($xcol+103,$xlin+145,$xcol+95,8,2,'','1234');
    $this->objpdf->rect($xcol+103,$xlin+153,$xcol+95,8,2,'','1234');
    $this->objpdf->rect($xcol+103,$xlin+153,$xcol+47,8,2,'','1234');
    $this->objpdf->rect($xcol+103,$xlin+161,$xcol+95,8,2,'','1234');
    $this->objpdf->rect($xcol+103,$xlin+169,$xcol+95,8,2,'','1234');
    $this->objpdf->rect($xcol+103,$xlin+177,$xcol+95,16,2,'','1234');
  
        $this->objpdf->text($xcol+137,$xlin+197,"REPARTIÇÃO FISCAL");
        $this->objpdf->text($xcol+141,$xlin+203,"AUTORIZAMOS");
  $this->objpdf->rect($xcol+103,$xlin+198,$xcol+95,27,2,'','1234');
  
  $this->objpdf->Setfont('Arial','',7);
        
  $this->objpdf->text($xcol+2,$xlin+151,"DATA:________________DE______________________________DE_____________");
        $this->objpdf->text($xcol+1,$xlin+156,"NOME DO RESPONSÁVEL PELO ESTABELECIMENTO USUÁRIO");
        $this->objpdf->text($xcol+3,$xlin+165,"___________________________________________________________________");
        $this->objpdf->text($xcol+1,$xlin+172,"DOCUMENTO DE IDENTIDADE");
        $this->objpdf->text($xcol+1,$xlin+200,"ASSINATURA DO RESPONSÁVEL PELO ESTABELECIMENTO USUÁRIO");
        $this->objpdf->text($xcol+1,$xlin+224,"ASSINATURA DO RESPONSÁVEL PELO ESTABELECIMENTO GRÁFICO");
      
       
        $this->objpdf->text($xcol+105,$xlin+151,"DATA:________________DE______________________________DE_____________");
        $this->objpdf->text($xcol+104,$xlin+156,"DOC. FISCAL N°.:");
        $this->objpdf->text($xcol+155,$xlin+156,"SÉRIE:");
        $this->objpdf->text($xcol+104,$xlin+164,"RECEBIDO POR");  
        $this->objpdf->text($xcol+104,$xlin+172,"DOCUMENTO DE IDENTIDADE"); 
        $this->objpdf->text($xcol+116,$xlin+192,"ASSINATURA DO RESPONSÁVEL PELO RECEBIMENTO");
        
  $this->objpdf->text($xcol+105,$xlin+211,"EM________________DE______________________________DE_____________");
        $this->objpdf->text($xcol+115,$xlin+224,"ASSINATURA E CARIMBO DA AUTORIDADE COMPETENTE");
  
  $this->objpdf->Setfont('Arial','B',5);
  $this->objpdf->text($xcol+105,$xlin+227,"REGISTRADA NO SISTEMA EM " . $this->autoriza_data . " POR " . $this->autoriza_usuario);


  $this->objpdf->Setfont('Arial','B',8);
  $this->objpdf->text($xcol+40,$xlin+229,"IMPORTANTE");
  $this->objpdf->rect($xcol,$xlin+230,$xcol+198, 28,2,'','1234');
  $this->objpdf->Setfont('Arial','b',7);   
  //$this->objpdf->text($xcol+10,$xlin+236,"NO RODAPÉ DAS NOTAS FISCAIS DEVERÁ CONSTAR OBRIGATORIAMENTE:");


include ("classes/db_db_config_classe.php");
$cldb_config = new cl_db_config;

/*
 * Lib para retornar informação "IMPORTANTE",
 * do documento de paragrafo definido pelo usuário
 */

$oObservacao = new libdocumento(2035);
$aParagrafos = $oObservacao->getDocParagrafos();

$sObservacao = $aParagrafos[1]->oParag->db02_texto;
$this->objpdf->Setfont('Arial','B', 7);
$this->objpdf->setY($xlin+233);
$this->objpdf->setAutoPAgeBreak(false);
$this->objpdf->multicell(200, 3, $sObservacao, 0, "L",0);

$this->objpdf->Setfont('Arial','',5);
$this->objpdf->text($xcol-2,$xlin+262,"Base: ".$this->base);
  
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text($xcol+41,$xlin+265,$this->arquivo."   Emissor: ".$this->emissor."   Exercï¿½cio: ".$this->exercicio."   Data: ".$this->data);

?>
