<?php
      //// RECIBO
       
        $this->objpdf->AliasNbPages();
        $this->objpdf->AddPage();
        $this->objpdf->settopmargin(1);
        $this->objpdf->line(2,148.5,208,148.5);
        $xlin = 20;
        $xcol = 4;
////////////////////////               for ($i = 0;$i < 2;$i++){
                $this->objpdf->setfillcolor(245);
                $this->objpdf->roundedrect($xcol-2,$xlin-18,206,185,2,'DF','1234');
                $this->objpdf->setfillcolor(255,255,255);
//                $this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
                $this->objpdf->Setfont('Arial','B',11);
                $this->objpdf->text(150,$xlin-13,'RECIBO VÁLIDO ATÉ: ');
                $this->objpdf->text(159,$xlin-8,$this->datacalc);
                
                //Via
                $i = 0;
                if( $i == 0 ){
                  $str_via = 'Contribuinte';
                }else{
                  $str_via = 'Prefeitura';
                }
                $this->objpdf->Setfont('Arial','B',8);
                $this->objpdf->text(178,$xlin-1,($i+1).'ª Via '.$str_via );
        
                $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
                $this->objpdf->Setfont('Arial','B',9);
                $this->objpdf->text(40,$xlin-15,$this->prefeitura);
                $this->objpdf->Setfont('Arial','',9);
                $this->objpdf->text(40,$xlin-11,$this->enderpref);
                $this->objpdf->text(40,$xlin-8,$this->municpref);
                $this->objpdf->text(40,$xlin-5,$this->telefpref);
                $this->objpdf->text($xcol+60,$xlin-5,"CNPJ: ");
                $this->objpdf->text($xcol+70,$xlin-5,db_formatar($this->cgcpref,'cnpj')); 
                $this->objpdf->text(40,$xlin-2,$this->emailpref);
//                $this->objpdf->setfillcolor(245);
        
                $this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+119,20,2,'DF','1234');
                $this->objpdf->Setfont('Arial','',6);
                $this->objpdf->text($xcol+2,$xlin+4,'Identificação:');
                $this->objpdf->Setfont('Arial','',8);
                $this->objpdf->text($xcol+2,$xlin+7,'Nome :');
                $this->objpdf->text($xcol+17,$xlin+7,$this->nome);
                $this->objpdf->text($xcol+2,$xlin+11,'Endereço :');
                $this->objpdf->text($xcol+17,$xlin+11,$this->ender);
                $this->objpdf->text($xcol+2,$xlin+15,'Município :');
                $this->objpdf->text($xcol+17,$xlin+15,$this->munic);
                $this->objpdf->text($xcol+75,$xlin+15,'CEP :');
                $this->objpdf->text($xcol+82,$xlin+15,$this->cep);
                $this->objpdf->text($xcol+2,$xlin+19,'Data :');

                
                $this->objpdf->text($xcol+17,$xlin+19, date("d-m-Y",db_getsession("DB_datausu")));

                $this->objpdf->text($xcol+40,$xlin+19,'Hora: '.date("H:i:s"));

                $this->objpdf->text($xcol+75,$xlin+19,'CNPJ/CPF:');
                $this->objpdf->text($xcol+90,$xlin+19,db_formatar($this->cgccpf,(strlen($this->cgccpf)<12?'cpf':'cnpj')));
                
                //$this->objpdf->text($xcol+75,$xlin+19,'IP :');
                //$this->objpdf->text($xcol+82,$xlin+19,$this->ip);
                $this->objpdf->Setfont('Arial','',6);
        
                $this->objpdf->Roundedrect($xcol+126,$xlin+2,76,20,2,'DF','1234');
                
                $this->objpdf->text($xcol+128,$xlin+4,$this->identifica_dados);
                
                $this->objpdf->text($xcol+128,$xlin+7,$this->tipoinscr);
                $this->objpdf->text($xcol+145,$xlin+7,$this->nrinscr);
                
                //$this->objpdf->text($xcol+128,$xlin+7,$this->tipoinscr1);
                //$this->objpdf->text($xcol+145,$xlin+7,$this->nrinscr1);
                $this->objpdf->text($xcol+128,$xlin+11,$this->tipolograd);
                $this->objpdf->text($xcol+145,$xlin+11,$this->nomepri);
                $this->objpdf->text($xcol+128,$xlin+15,$this->tipocompl);
                $this->objpdf->text($xcol+145,$xlin+15,$this->nrpri."      ".$this->complpri);
                $this->objpdf->text($xcol+128,$xlin+19,$this->tipobairro);
                $this->objpdf->text($xcol+145,$xlin+19,$this->bairropri);

//              $this->objpdf->setfillcolor(245);
                $this->objpdf->Roundedrect($xcol,$xlin+24,202,15,2,'DF','1234');
                $this->objpdf->sety($xlin+24);
                $maiscol = 0;
                $yy = $this->objpdf->gety();
                for($ii = 0;$ii < $this->linhasdadospagto ;$ii++) {
                   if ($ii == 14 ){
                      $maiscol = 100;
                      $this->objpdf->sety($yy);
                   }
                   if($ii==0 || $ii == 14){
                     
                        $this->objpdf->setx($xcol+3+$maiscol);
                        $this->objpdf->cell(5,3,"Rec",0,0,"L",0);
                        $this->objpdf->cell(10,3,"Reduz",0,0,"L",0);
                          $this->objpdf->cell(63,3,"Descrição",0,0,"L",0);
                      $this->objpdf->cell(15,3,"Valor",0,1,"R",0);

                   }
                      $this->objpdf->setx($xcol+3+$maiscol);
                      $this->objpdf->cell(5,3,trim(pg_result($this->recorddadospagto,$ii,$this->receita)),0,0,"R",0);
                      $this->objpdf->cell(10,3,"(".trim(pg_result($this->recorddadospagto,$ii,$this->receitared)).")",0,0,"R",0);
                      if ( trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita) ) == ''){
                           $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->dreceita)),0,0,"L",0);
                      }else{ 
                        $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita)),0,0,"L",0);
                   }
                    $this->objpdf->cell(15,3,db_formatar(pg_result($this->recorddadospagto,$ii,$this->valor),'f'),0,1,"R",0);
                }

                $this->objpdf->Roundedrect($xcol,$xlin+40,202,110,2,'DF','1234');
                $this->objpdf->SetY($xlin+41);
                $this->objpdf->SetX($xcol+3);
                $this->objpdf->multicell(0,4,'HISTÓRICO :   '.$this->historico);
                $this->objpdf->SetX($xcol+3);
                $this->objpdf->multicell(0,4,$this->histparcel);
// mostra os dados da nota                
                $coluna = 0;
               // $this->objpdf->SetX($xcol+3);
// ############# planilha de iss ######################
                $arr_prestador = explode( "|",$this->prestador );
                if( count( $arr_prestador ) > 0 ){
                    $this->objpdf->Setfont('Arial','B',6);
                   // $this->objpdf->cell(7,3,"TIPO",0,0,"L",0);
                    if($this->totalvalor_P>0){
                       $this->objpdf->cell(90,3,"SERVIÇOS PRESTADOS - Valor Total do ISSQN: ".db_formatar($this->totalvalor_P,"f"),0,1,"L",0);
                    }
                    $this->objpdf->ln(2);                   
                    $this->objpdf->cell(90,3,"SERVIÇOS TOMADOS",0,1,"L",0);
                    $this->objpdf->cell(20,3,"CPF/CNPJ",0,0,"L",0);
                    $this->objpdf->cell(42,3,"PRESTADOR DO SERVIÇO",0,0,"L",0);
                    $this->objpdf->cell(10,3,"NOTA",0,0,"R",0);
                    $this->objpdf->cell(15,3,"VALOR",0,0,"R",0);

                    $this->objpdf->cell(2,3,"|",0,0,"C",0);
                   // $this->objpdf->cell(7,3,"TIPO",0,0,"L",0);
                    $this->objpdf->cell(20,3,"CPF/CNPJ",0,0,"L",0);
                    $this->objpdf->cell(42,3,"PRESTADOR DO SERVIÇO",0,0,"L",0);
                    $this->objpdf->cell(10,3,"NOTA",0,0,"R",0);
                    $this->objpdf->cell(15,3,"VALOR",0,1,"R",0);
                    $this->objpdf->Setfont('Arial','',6);
                }
              // echo "xxxxxxxxxxx";
              // print_r($arr_prestador);exit;
                $totalnota_T  = 0;
                //$totalnota_P  = 0;
                (float)$totalvalor_T = 0;
                //$totalvalor_P = 0;
                for( $xp=0; $xp < count( $arr_prestador )-1; $xp++ ){
                    $arr_presador2 = explode( ";", $arr_prestador[ $xp ] );
                    $valor_sem_ponto  = str_replace(".","",$arr_presador2[3]);
                    $valor_sem_vigula = str_replace(",",".",$valor_sem_ponto);
                    //if($arr_presador2[4]=="T"){
                      $totalnota_T  += 1;
                      //$totalvalor_T += $arr_presador2[3];
                      $totalvalor_T += (float)$valor_sem_vigula;
                     
                    //}
                   
                    if( $coluna == 0 ){
                         $this->objpdf->cell(20,3,trim($arr_presador2[0]),0,0,"L",0);
                         $this->objpdf->cell(42,3,substr($arr_presador2[1], 0, 28 ),0,0,"L",0);
                         $this->objpdf->cell(10,3,$arr_presador2[2],0,0,"R",0);
                         $this->objpdf->cell(15,3,$arr_presador2[3],0,0,"R",0);
                         $coluna++;

                    }else{
                         $this->objpdf->cell(2,3,"|",0,0,"C",0);
                         $this->objpdf->cell(20,3,trim($arr_presador2[0]),0,0,"L",0);
                         $this->objpdf->cell(42,3,substr($arr_presador2[1], 0, 28 ),0,0,"L",0);
                         $this->objpdf->cell(10,3,$arr_presador2[2],0,0,"R",0);
                         $this->objpdf->cell(15,3,$arr_presador2[3],0,1,"R",0);
                         $coluna = 0;
                    }
                }
         	      $this->objpdf->ln(5);
         	      $this->objpdf->Setfont('Arial','B',6);
                
                $this->objpdf->cell(100,3,"Serviços Tomados   - ".$totalnota_T." notas - Valor Total do ISSQN: ".db_formatar($totalvalor_T,"f"),0,1,"L",0);
                
                $this->objpdf->Setfont('Arial','',6);
                $this->objpdf->setx(15);
                $xlin = $xlin -25;
                $this->objpdf->Roundedrect(83,$xlin+177,40,7,2,'DF','1234');
                $this->objpdf->Roundedrect(125,$xlin+177,21,7,2,'DF','1234');
                $this->objpdf->Roundedrect(173,$xlin+177,32,7,2,'DF','1234');
                $this->objpdf->Roundedrect(147,$xlin+177,25,7,2,'DF','1234');
                $this->objpdf->text(95,$xlin+179,'Nosso Número');
                $this->objpdf->text(129,$xlin+179,'Vencimento');
                $this->objpdf->text(179,$xlin+179,'Código de Arrecadação');
                $this->objpdf->text(150,$xlin+179,'Valor a Pagar em R$');
                $this->objpdf->setfont('Arial','',10);
//                $this->objpdf->text(85,$xlin+183,str_pad($this->nosso_numero,17,"0",STR_PAD_LEFT));
                $this->objpdf->text(85,$xlin+183,$this->nosso_numero);
                $this->objpdf->text(127,$xlin+183,$this->dtvenc);
                $this->objpdf->text(175,$xlin+183,$this->descr9);
                $this->objpdf->text(150,$xlin+183,$this->valtotal);

                
                $this->objpdf->SetFont('Arial','B',5);
                $this->objpdf->text(150,$xlin+190,"A   U   T   E   N   T   I   C   A   Ç   Ã   O      M   E   C   Â   N   I   C   A");

                $this->objpdf->setfillcolor(0,0,0);
                $this->objpdf->SetFont('Arial','',4);
                $this->objpdf->TextWithDirection(1.5,$xlin+170,$this->texto,'U'); // texto no canhoto do carne
                $this->objpdf->setfont('Arial','',11);
                $this->objpdf->text(10,$xlin+190,$this->linhadigitavel);
                
                $xlin = 235 ;

//////////////////////       }
include("fpdf151/impmodelos/mod_imprime48.php"); 
$this->objpdf->ln(30);
               ?>
