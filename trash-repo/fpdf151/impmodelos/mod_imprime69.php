<?
$altura = 3.5;
$this->objpdf->AddPage();

for ($i = 0; $i < 2; $i++){
   $this->objpdf->SetFillColor(235);
   $y = $this->objpdf->gety() - 2;
   $this->objpdf->Image('imagens/files/'.$this->logoitbi,10,$y,14);
   $this->objpdf->SetFont('Arial','B',10);
   $this->objpdf->setx(30);
   $this->objpdf->Cell(100,3,$this->nomeinst,0,1,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->setx(30);
   $this->objpdf->Cell(100,3,'Imposto Sobre Transmissão de Bens Imóveis (ITBI)',0,0,"L",0);
   $this->objpdf->SetFont('Arial','B',12);
   $this->objpdf->cell(100,3,'Vencimento : '.db_formatar($this->datavencimento,'d'),0,1,"L",0);
   $this->objpdf->SetFont('Arial','',8); 
   
   $this->objpdf->setx(30);
   $this->objpdf->Cell(100,3,'Tipo de Transmissão : '.$this->it04_descr,0,0,"L",0);
   $this->objpdf->cell(50,3,'Recibo Emitido em: '.db_formatar($this->dataemissao,'d'),0,1,"L",0);   
   
   $this->objpdf->setx(30);
   $this->objpdf->Cell(100,3,'',0,0,"L",0);
   $this->objpdf->cell(50,3,'Código de Arrecadação : '.$this->numpreitbi,0,1,"L",0);
   
   $this->objpdf->setx(30);
   $this->objpdf->SetFont('Arial','B',10);
   $this->objpdf->Cell(100,3,'Guia de Recolhimento N'.chr(176).' SMF/'.db_formatar($this->itbi,'s','0',5).'/'.$this->ano,0,0,"L",0);
   
   if ($this->tipoitbi == "urbano") { 
     $this->objpdf->Cell(50,5,"ITBI URBANO",0,1,"C",0);
   } else {
     $this->objpdf->Cell(50,5,"ITBI RURAL",0,1,"C",0);
   }
   
//   $this->objpdf->ln();
   
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'',1,0,"C",1);
   $this->objpdf->cell(80,$altura,'Identificação do Transmitente',1,0,"C",1);
   $this->objpdf->cell(97,$altura,'Identificação do Adquirente',1,1,"C",1);
   $this->objpdf->cell(20,$altura,'Nome : ',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(80,$altura,$this->z01_nome.$this->outrostransmitentes,1,0,"L",0);    //nome do transmitente

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(97,$altura,$this->nomecompprinc.$this->outroscompradores,1,1,"L",0);   //nome do comprador 
   $this->objpdf->cell(20,$altura,'CNPJ/CPF:',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(30,$altura,$this->z01_cgccpf,1,0,"L",0);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'Fone:',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(30,$altura,$this->fonetransmitente ,1,0,"L",0);
   $this->objpdf->cell(35,$altura,$this->cgccpfcomprador,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(30,$altura,'Fone:',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(32,$altura,$this->fonecomprador   ,1,1,"L",0);   
   
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'Endereço : ',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(80,$altura,substr($this->z01_ender.' - '.$this->z01_bairro,0,46) ,1,0,"L",0);
   $this->objpdf->cell(97,$altura,substr($this->enderecocomprador.','.$this->numerocomprador.' / '.$this->complcomprador,0,50),1,1,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'Município : ',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(80,$altura,$this->z01_munic.'('.$this->z01_uf.') - CEP: '.$this->z01_cep ,1,0,"L",0);
   $this->objpdf->cell(97,$altura,$this->municipiocomprador.'('.$this->ufcomprador.') - CEP: '.$this->cepcomprador . ' - BAIRRO: '.$this->bairrocomprador ,1,1,"L",0);
   $this->objpdf->Ln(2);
   $this->objpdf->SetFont('Arial','B',8);
   
   if ($this->tipoitbi == "urbano") {
     $this->objpdf->cell(88,$altura,'Dados do Imóvel',1,0,"C",1);
   } else {
     $this->objpdf->cell(88,$altura,'Dados da Terra',1,0,"C",1);  
   }
   $this->objpdf->cell(2,$altura,'',0,0,"C",0);
   
   if ($this->tipoitbi == "urbano"){
   $this->objpdf->cell(107,$altura,'Dados das Construções',1,1,"C",1);
   } else {
     $this->objpdf->cell(107,$altura,'Dados das Benfeitorias',1,1,"C",1);
   }
   
   $this->objpdf->SetFont('Arial','',8);
   $y = $this->objpdf->gety();
   
   if ($this->tipoitbi == "urbano"){
    
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(35,$altura,'Matrícula da Prefeitura: ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(13,$altura,@$this->it06_matric,1,0,"L",0);
  
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(30,$altura,'Número do imóvel: ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(10,$altura,@$this->j39_numero,1,1,"L",0);
  
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(15,$altura,'Setor : ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(14,$altura,@$this->j34_setor,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
  
   $this->objpdf->cell(15,$altura,'Quadra : ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(14,$altura,@$this->j34_quadra,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
  
   $this->objpdf->cell(15,$altura,'Lote: ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(15,$altura,(@$this->matriz == ""?$this->j34_lote:@$this->matriz),1,1,"L",0);
   
   
   
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(15,$altura,'Matric RI : ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(14,$altura,$this->it22_matricri,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
  
   $this->objpdf->cell(15,$altura,'Quadra : ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(14,$altura,$this->it22_quadrari,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
  
   $this->objpdf->cell(15,$altura,'Lote: ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(15,$altura,$this->it22_loteri,1,1,"L",0);   
   
   
  
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Bairro: ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(66,$altura,@$this->j13_descr,1,1,"L",0);
   
   $this->objpdf->SetFont('Arial','B',8);  
   $this->objpdf->cell(22,$altura,'Logradouro: ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $endereco = @$this->j14_tipo . " " . @$this->j14_nome;
   if(strlen($endereco) > 32){
     $pontos = "...";
   }else{
     $pontos = "";
   }
   
   $this->objpdf->cell(66,$altura,substr($endereco, 0, 32).$pontos,1,1,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $iGetY = $this->objpdf->getY();
     $this->objpdf->cell(22,$altura,'Situação: ',1,0,"L",1);
     $this->objpdf->SetFont('Arial','',8);
     $this->objpdf->cell(66,$altura,@$this->it07_descr,1,1,"L",0);
     $this->objpdf->SetFont('Arial','B',8);
     $this->objpdf->cell(22,$altura,'Frente: ',1,0,"L",1);
     $this->objpdf->cell(21,$altura,db_formatar($this->it05_frente,'f',' ' ,0,'e',3) . 'm',1,0,"R",0);
     $this->objpdf->cell(22,$altura,'Fundos : ',1,0,"L",1);
     $this->objpdf->cell(23,$altura,db_formatar($this->it05_fundos,'f',' ' ,0,'e',3).'m',1,1,"R",0);
     $this->objpdf->cell(22,$altura,'Lado Esquerdo: ',1,0,"L",1);
     $this->objpdf->cell(21,$altura,db_formatar($this->it05_esquerdo,'f',' ' ,0,'e',3).'m',1,0,"R",0);
     $this->objpdf->cell(22,$altura,'Lado Direito: ',1,0,"L",1);
     $this->objpdf->cell(23,$altura,db_formatar($this->it05_direito,'f',' ' ,0,'e',3).'m',1,1,"R",0);

   $this->objpdf->setXY(100,$iGetY);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(24,$altura,''         ,1,0,"L",1);
   $this->objpdf->cell(42,$altura,'Real'     ,1,0,"C",1);  
   $this->objpdf->cell(41,$altura,'Transmitida',1,1,"C",1);  
   $this->objpdf->setx(100);
   $this->objpdf->cell(24,$altura,'Terreno: '    ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(42,$altura,db_formatar($this->areaterreno+0,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha')          ,1,0,"R",0);  
   $this->objpdf->cell(41,$altura,(count($this->areaterrenomat)==1?db_formatar($this->areatran,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha'):(strlen($this->areaterrenomat[1])>2?$this->areatran:db_formatar($this->areatran,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha'))),1,1,"R",0);
   $this->objpdf->setx(100);   
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(24,$altura,'Construções:',1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(42,$altura,(@$this->areatotal == 0?'':(count(@$this->areaedificadamat)==1?db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2':(strlen(@$this->areaedificadamat[1])>2?db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2':db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2'))),1,0,"R",0);
   $this->objpdf->cell(41,$altura,(@$this->areatotal == 0?'':(count(@$this->areaedificadamat)==1?db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2':(strlen(@$this->areaedificadamat[1])>2?db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2':db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2'))),1,1,"R",0);     
     
   $iPosicaoYObs = $this->objpdf->getY();

   } else {
    
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(33,$altura,'Matríc. Reg. Imóveis : ',1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8); 
   $this->objpdf->cell(10,$altura,@$this->it22_matricri  ,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(15,$altura,'Distante: '       ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(10,$altura,$this->it18_distcidade   ,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'km da Cidade'       ,1,1,"L",1);  

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Logradouro:'                   ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(66,$altura,substr($this->it18_nomelograd,0,40)  ,1,1,"L",0);
   
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Frente: '                        ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(22,$altura,db_formatar($this->it18_frente,'f',' ' ,0,'e',3).'m',1,0,"L",0);   
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Fundos: '                        ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(22,$altura,db_formatar($this->it18_fundos,'f',' ' ,0,'e',3).'m',1,1,"L",0);  
    
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Profundidade:'                   ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(22,$altura,db_formatar($this->it18_prof,'f',' ' ,0,'e',3).'m'  ,1,0,"L",0);   
   
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(28,$altura,'Frente via Pública:',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(16,$altura,$this->lFrenteVia                   ,1,1,"C",0);   
   
   $iGetY = $this->objpdf->getY();
    
   $this->objpdf->setXY(100,($this->objpdf->getY()+$altura));
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(24,$altura,''         ,1,0,"L",1);
   $this->objpdf->cell(42,$altura,'Real'     ,1,0,"C",1);  
   $this->objpdf->cell(41,$altura,'Transmitida',1,1,"C",1);  
   $this->objpdf->setx(100);
   $this->objpdf->cell(24,$altura,'Terra: '    ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(42,$altura,db_formatar($this->areaterreno+0,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha')          ,1,0,"R",0);  
   $this->objpdf->cell(41,$altura,(count($this->areaterrenomat)==1?db_formatar($this->areatran,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha'):(strlen($this->areaterrenomat[1])>2?$this->areatran:db_formatar($this->areatran,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha'))),1,1,"R",0);
   $this->objpdf->setx(100);   
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(24,$altura,'Benfeitorias:',1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(42,$altura,(@$this->areatotal == 0?'':(count(@$this->areaedificadamat)==1?db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2':(strlen(@$this->areaedificadamat[1])>2?db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2':db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2'))),1,0,"R",0);
   $this->objpdf->cell(41,$altura,(@$this->areatotal == 0?'':(count(@$this->areaedificadamat)==1?db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2':(strlen(@$this->areaedificadamat[1])>2?db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2':db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2'))),1,1,"R",0);
   
   $iPosicaoYObs = $this->objpdf->getY();
   
   $this->objpdf->setY($iGetY);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(44,$altura,'Utilização Terra(ha)'  ,1,0,"L",1);
   $this->objpdf->cell(44,$altura,'Distribuição Terra(ha)',1,1,"L",1);      

     $iGetY = $this->objpdf->getY();
     
     $iLimiteLinhasCaract = 2;
   $iLinhasCaract       = 0;     

     if( count($this->aDadosRuralCaractUtil) > 0 ){
       foreach ( $this->aDadosRuralCaractUtil as $iInd => $aDadosUtil ){       
         $this->objpdf->SetFont('Arial','B',6);
         $this->objpdf->cell(30,$altura,$aDadosUtil['Descricao']   ,1,0,"L",1);
         $this->objpdf->SetFont('Arial','',6);
         $this->objpdf->cell(14,$altura,$aDadosUtil['Valor']."%",1,1,"R",0);
         if ( $iInd == $iLimiteLinhasCaract) {
           break;
         }
         $iLinhasCaract++;
       }
     }
     
     if ( $iLinhasCaract < $iLimiteLinhasCaract ) {
       for( $iInd=$iLinhasCaract; $iInd < $iLimiteLinhasCaract; $iInd++){
         $this->objpdf->cell(30,$altura,"",1,0,"L",1);
         $this->objpdf->cell(14,$altura,"",1,1,"R",0);
       }      
     }     

   $iLinhasCaract       = 0;
   
     if( count($this->aDadosRuralCaractDist) > 0 ){
      
       $this->objpdf->setY($iGetY);
       foreach ( $this->aDadosRuralCaractDist as $iInd => $aDadosDist ){
         $this->objpdf->SetFont('Arial','B',6);
         $this->objpdf->setX(54);
         $this->objpdf->cell(30,$altura,$aDadosDist['Descricao'],1,0,"L",1);
         $this->objpdf->SetFont('Arial','',6);
         $this->objpdf->cell(14,$altura,$aDadosDist['Valor']."%",1,1,"R",0);
         if ( $iInd == $iLimiteLinhasCaract) {
          break;
         }
         $iLinhasCaract++;
       }
       $iGetY = $this->objpdf->getY();
     }

     if ( $iLinhasCaract < $iLimiteLinhasCaract ) {
       $this->objpdf->setY($iGetY); 
       for( $iInd=$iLinhasCaract; $iInd < $iLimiteLinhasCaract; $iInd++){
     $this->objpdf->setX(54);
         $this->objpdf->cell(30,$altura,"",1,0,"L",1);
         $this->objpdf->cell(14,$altura,"",1,1,"R",0);
       }      
     }
     
   }
   

   $this->objpdf->SetXY(100,$y);
   $this->objpdf->SetFont('Arial','B',7);
   $this->objpdf->cell(24,$altura,'Descrição',1,0,"C",1);
   $this->objpdf->cell(35,$altura,'Tipo',1,0,"C",1);
   $this->objpdf->cell(20,$altura,'Área m2',1,0,"C",1);
   $this->objpdf->cell(20,$altura,'Área trans m2',1,0,"C",1);
   $this->objpdf->cell(8,$altura,'Ano',1,1,"C",1);
   $this->objpdf->SetFont('Arial','',7);
   $y = $this->objpdf->gety();
   
   for ($ii = 1;$ii <= 4 ; $ii++){
       $this->objpdf->setx(100);
       $this->objpdf->cell(24,$altura,'',1,0,"C");
       $this->objpdf->cell(35,$altura,'',1,0,"C");
       $this->objpdf->cell(20,$altura,'',1,0,"C");
       $this->objpdf->cell(20,$altura,'',1,0,"C");
       $this->objpdf->cell(8, $altura,'',1,1,"C");
   }
   $yy = $this->objpdf->gety();
   $this->objpdf->SetXY(100,$y);
   
   if($this->linhasresultcons > 0){
     for ($n = 0;$n < $this->linhasresultcons ; $n++){

       $this->objpdf->setx(100);
       $this->objpdf->cell(24,$altura,( strlen($this->arrayit09_codigo[$n])>12? substr($this->arrayit09_codigo[$n],0,12)."...":$this->arrayit09_codigo[$n]),0,0,"L",0);
       $this->objpdf->cell(35,$altura,substr($this->arrayit10_codigo[$n],0,20),0,0,"L",0);
       $this->objpdf->cell(20,$altura,db_formatar($this->arrayit08_area[$n],'f',' ',' ',' ',5),0,0,"R",0);
         $this->objpdf->cell(20,$altura,db_formatar($this->arrayit08_areatrans[$n],'f',' ',' ',' ',5),0,0,"R",0);
       $this->objpdf->cell(8,$altura,$this->arrayit08_ano[$n],0,1,"C",0);
       if($n == 3){
         break;
       }
     }
   }

   $this->objpdf->setY($iPosicaoYObs);
   $this->objpdf->ln(2);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(156,$altura,'Observações',1,0,"L",1);
   $this->objpdf->cell(41,$altura,'V I S T O',1,1,"C",1);
   $this->objpdf->SetFont('Arial','',8);
   $y = $this->objpdf->gety();
   $this->objpdf->cell(156,$altura,'',"TLR",0,"L",0);
   $this->objpdf->cell(41, $altura,'',"TLR",1,"L",0);
   $this->objpdf->cell(156,$altura,'',"LBR",0,"l",0);
   $this->objpdf->cell(41, $altura,'',"LR" ,1,"L",0);
   $this->objpdf->cell(156,$altura,'',"LBR",0,"l",0);
   $this->objpdf->cell(41, $altura,'',"LR" ,1,"L",0);
   $this->objpdf->cell(156,$altura,'',"LBR",0,"l",0);
   $this->objpdf->cell(41, $altura,'',"LR" ,1,"L",0);       
   $this->objpdf->cell(156,$altura,'',"LBR",0,"l",0);
   $this->objpdf->cell(41, $altura,'',"LR" ,1,"L",0);
   $this->objpdf->cell(156,$altura,'',"LBR",0,"l",0);
   $this->objpdf->cell(41, $altura,''," LR",1,"L",0);
   
   $this->objpdf->SetFont('Arial','B',6);
   
   $iPosicaoYFormaPgto = $this->objpdf->getY();
   $this->objpdf->cell(20,$altura,"Tipo"        ,1,0,"C",1);
   $this->objpdf->cell(20,$altura,"Informado"     ,1,0,"C",1);
   $this->objpdf->cell(20,$altura,"Avaliado"      ,1,0,"C",1);
   $iPosicaoXFormaPgto = $this->objpdf->getX();
   $this->objpdf->ln();
   
   if ($this->tipoitbi == "urbano") {
     $this->objpdf->cell(20,$altura,"Terreno"                   ,1,0,"L",1);
   } else {
     $this->objpdf->cell(20,$altura,"Terra"                   ,1,0,"L",1);
   }
   
   $this->objpdf->SetFont('Arial','',6);
   $this->objpdf->cell(20,$altura,db_formatar($this->it01_valorterreno,'f')   ,1,0,"R",0);
   $this->objpdf->cell(20,$altura,db_formatar($this->it14_valoravalter,'f')   ,1,1,"R",0);
   $this->objpdf->SetFont('Arial','B',6);
   
   if ($this->tipoitbi == "urbano") {
     $this->objpdf->cell(20,$altura,"Construção"                ,1,0,"L",1);
   } else {
     $this->objpdf->cell(20,$altura,"Benfeitoria"               ,1,0,"L",1);
   }
   
   $this->objpdf->SetFont('Arial','',6);
   $this->objpdf->cell(20,$altura,db_formatar($this->it01_valorconstr,'f')    ,1,0,"R",0);
   $this->objpdf->cell(20,$altura,db_formatar($this->it14_valoravalconstr,'f'),1,1,"R",0);
   $this->objpdf->SetFont('Arial','B',6);      
   $this->objpdf->cell(20,$altura,"Total"                     ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','',6);   
   $this->objpdf->cell(20,$altura,db_formatar($this->it01_valortransacao,'f') ,1,0,"R",0);
   $this->objpdf->cell(20,$altura,db_formatar($this->it14_valoraval,'f')      ,1,1,"R",0);      
   
   $iPosicaoYCodBarra = $this->objpdf->getY();
   $this->objpdf->SetFont('Arial','B',6);   
   $this->objpdf->setXY($iPosicaoXFormaPgto,$iPosicaoYFormaPgto);
   $this->objpdf->cell(36,$altura,"Forma de Pagamento",1,0,"C",1);
   $this->objpdf->cell(20,$altura,"Avaliado"      ,1,0,"C",1);
   $this->objpdf->cell(20,$altura,"Aliquota"      ,1,0,"C",1);
   $this->objpdf->cell(20,$altura,"Imposto"       ,1,0,"C",1);
   $this->objpdf->cell(41, $altura,''         ," LR",1,"L",0);

   $nTotalImposto  = 0;
   $nTotalAvaliado = 0;
   $lExibeMsg      = false;
   
   if( count($this->aDadosFormasPgto) > 0 ){
     foreach ( $this->aDadosFormasPgto as $iInd => $aDadosFormas ){
       if ( $iInd <= 2 ) {
        
         $this->objpdf->setX($iPosicaoXFormaPgto); 
         $this->objpdf->SetFont('Arial','B',6);
         $this->objpdf->cell(36,$altura,$aDadosFormas['Descricao']            ,1,0,"L",1);
         $this->objpdf->SetFont('Arial','',6);
         $this->objpdf->cell(20,$altura,db_formatar($aDadosFormas['Valor'],'f')   ,1,0,"R",0);
         $this->objpdf->cell(20,$altura,$aDadosFormas['Aliquota']."%"         ,1,0,"R",0);
         $this->objpdf->cell(20,$altura,db_formatar($aDadosFormas['Imposto'],'f') ,1,0,"R",0);
         $this->objpdf->cell(41, $altura,''," LR",1,"L",0);
         $nTotalImposto  += $aDadosFormas['Imposto']; 
         $nTotalAvaliado += $aDadosFormas['Valor'];
         
         $iPosicaoYTotalFormaPgto = $this->objpdf->getY();
          
       } else {
         $lExibeMsg = true;
       }
     }
   }   

   if ( $lExibeMsg ) {
     $this->objpdf->SetFont('Arial','B',6);
     $this->objpdf->setY($iPosicaoYCodBarra);
     $this->objpdf->cell(71,$altura,"* Existe mais formas de pagamento para esse ITBI","T",0,"L",0);
     $this->objpdf->setXY($iPosicaoXFormaPgto,$iPosicaoYTotalFormaPgto);     
   } else{
     $this->objpdf->setX($iPosicaoXFormaPgto);
   } 
   $this->objpdf->SetFont('Arial','B',6);
   $this->objpdf->cell(36,$altura,"Total"                       ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','',6);
   $this->objpdf->cell(20,$altura,db_formatar($nTotalAvaliado,'f')      ,1,0,"R",0);
   $this->objpdf->cell(20,$altura,""                        ,1,0,"R",0);
   $this->objpdf->cell(20,$altura,db_formatar($nTotalImposto,'f')       ,1,0,"R",0);    
   $this->objpdf->cell(41, $altura,''                   ,"BLR",1,"L",0);
   
   
   $yy = $this->objpdf->gety();
   $this->objpdf->sety($y);   
   $this->objpdf->multicell(156,$altura,$this->propri.$this->proprietarios.(strlen(trim($this->propri.$this->proprietarios)) > 0?"\n ":"").
                                                                           (strlen(trim($this->it01_obs)) > 0?$this->it01_obs:"").". ".$this->sMsgSituacaoImovel,1,"L",0);
   $this->objpdf->sety($yy);   
   
   $this->objpdf->ln(2);
   $this->objpdf->SetFont('Arial','B',10);
   $this->objpdf->setX(135);
   
   if ($this->it14_valorpaga == 0) {
     $this->objpdf->cell(60,$altura,'Valor a Pagar : I S E N T O',0,1,"C",0);
   } else {
     $this->objpdf->cell(60,$altura,'Valor a Pagar : R$ '.db_formatar(($this->it14_valorpaga + $this->tx_banc),'f'),0,1,"L",0);
   }
   
   $pos = $this->objpdf->gety();
   $this->objpdf->setfillcolor(0,0,0);
   
   if ($this->lLiberado) {
     
     if ($this->it14_valorpaga > 0) {
      
       $this->objpdf->text(14,$pos,$this->linha_digitavel);
       if ( isset($i) && $i != 0 ) {  
         $this->objpdf->int25(10,$pos+1,$this->codigo_barras,15,0.341);
       }
     }
   }
   
   if (!$this->lLiberado) {
       
     $this->objpdf->SetFont('Arial','B',78);
     $this->objpdf->SetFillColor(178);
     $this->objpdf->TextWithRotation(12,$pos+1,"NÃO LIBERADA",20,0);   
     $this->objpdf->SetFillColor(235);
   }
   
   $this->objpdf->ln(14);
}
?>