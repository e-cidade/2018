<?
##Modelo de estagio probatorio; 
$troca = 1;
$nomePresidente = $this->objEstagio->getPresidenteComissao(); 
for ($i = 0; $i < $this->iTotquesitos; $i++){
  //desenha os quesitos a tela.
     $oQuesito  = db_utils::fieldsMemory($this->rsQuesitos, $i);
     //$troca = 1;
     $this->objEstagio->getQuestoesQuesito($oQuesito->h51_sequencial);
     if ($troca == 0){
        if (($this->objpdf->getY() >= $this->objpdf->h - 75)){
          $troca = 1;
          $this->objpdf->Setfont('Arial',"",9);
        }else{
           $this->objpdf->line(10,$this->objpdf->getY()+2,200,$this->objpdf->getY()+2);
           $this->objpdf->sety($this->objpdf->getY()+2);
           $this->objpdf->Setfont('Arial',"B",10);
           $this->objpdf->cell(70,4,($i+1).") - {$oQuesito->h51_descr}",0,1);
           $this->objpdf->Setfont('Arial',"",9);
        }
     }
     
     for ($j = 0; $j < $this->objEstagio->iTotQuestoes; $j++){
     
       $this->objpdf->ln();
       if (($this->objpdf->getY() >= $this->objpdf->h - 75) or $troca == 1){
          
          $xlin             = 20;
          $xcol             = 4;
          $this->objpdf->AliasNbPages();
          $this->objpdf->AddPage();
          $this->objpdf->settopmargin(1);
          $this->objpdf->setfillcolor(255, 255, 255);
          $this->objpdf->Image('imagens/files/'.$this->logo, 10,10, 15); 
          $this->objpdf->Setfont('Arial', 'B', 12);
          $this->objpdf->text(30,15, $this->prefeitura);
          $this->objpdf->Setfont('Arial', 'b', 10);
          $this->objpdf->text(30,19,"GABINETE DO EXECUTIVO MUNICIPAL");
          $this->objpdf->Setfont('Arial', 'b', 9);
          $this->objpdf->setY(35);
          $this->objpdf->cell(0,5,'A N E X O   I',0,1,"C");
          $this->objpdf->cell(0,5,'AVALIAÇÃO DE DESEMPENHO NO ESTÁGIO PROBÁTORIO',0,1,"C");
          $this->objpdf->Setfont('Arial', '', 9);
          $this->objpdf->ln();
          $this->objpdf->cell(35,5,'Nome do Funcionário:',0,0,"L");
          $this->objpdf->Setfont('Arial', '', 9);
          $this->objpdf->cell(70,5,$this->dadosAvaliacao->z01_nome,0,1,"L");
          $this->objpdf->Setfont('Arial', '', 9);
          $this->objpdf->cell(15,5,'Matrícula:',0,0,"L");
          $this->objpdf->Setfont('Arial', 'b', 9);
          $this->objpdf->cell(20,5,$this->dadosAvaliacao->rh01_regist,0,0,"L");
          $this->objpdf->Setfont('Arial', '', 9);
          $this->objpdf->cell(30,5,'Data da Nomeação:',0,0);
          $this->objpdf->Setfont('Arial', 'b', 9);
          $this->objpdf->cell(40,5,db_formatar($this->dadosAvaliacao->rh01_admiss,"d"),0,0);
          $this->objpdf->Setfont('Arial', '', 9);
          $this->objpdf->cell(45,5,'Data do Início do Exercício:',0,0);
          $this->objpdf->Setfont('Arial', 'b', 9);
          $this->objpdf->cell(25,5,db_formatar($this->dadosAvaliacao->rh01_admiss,"d"),0,1);
          $this->objpdf->Setfont('Arial', '', 9);
          $this->objpdf->cell(35,5,'Cargo:',0,0);
          $this->objpdf->Setfont('Arial', 'b', 9);
          $this->objpdf->cell(70,5,$this->dadosAvaliacao->rh37_descr,0,0);
          $this->objpdf->Setfont('Arial', '', 9);
          $this->objpdf->cell(45,5,"{$this->dadosAvaliacao->h64_seqaval} ª Avaliação",0,1);
          $this->objpdf->line(10,$this->objpdf->getY()+2,200,$this->objpdf->getY()+2);
          $this->objpdf->sety($this->objpdf->getY()+2);
          $this->objpdf->Setfont('Arial',"B",10);
          $this->objpdf->cell(70,4,($i+1).") - {$oQuesito->h51_descr}",0,1);
          $this->objpdf->ln();
          $troca = 0;
       }
       $oQuestoes = db_utils::fieldsmemory($this->objEstagio->rEstagioQuestao,$j);
       $this->objpdf->Setfont('Arial',"",10);
       $this->objpdf->Setx(20);
       $this->objpdf->multicell(190,4,($j+1).") - {$oQuestoes->h53_descr}",0,"L");
       $this->objpdf->ln();
       $this->objEstagio->getQuestaoRespostas($oQuestoes->h53_sequencial);
       $this->objpdf->line(20,$this->objpdf->getY(),200,$this->objpdf->getY());
       $iYquesito = $this->objpdf->getY(); //altura inicial das questoes do quesito
       if ($this->objEstagio->iTotRespostas > 0){
          for ($k = 0; $k < $this->objEstagio->iTotRespostas; $k++){
          
           $oResposta = db_utils::fieldsmemory($this->objEstagio->rEstagioResposta,$k);
           $this->objpdf->Setx(20);
           $iYold = $this->objpdf->getY();
           $this->objpdf->multicell(150,5,($k+1).") - {$oResposta->h54_descr}\n",1,"L");
           $iYLinha = $this->objpdf->getY();
           $this->objpdf->line(20,$this->objpdf->getY(),200,$this->objpdf->getY());
           $this->objpdf->setxy(170,$iYold);
           if ($this->objEstagio->getRespostaQuestao($oQuestoes->h53_sequencial) == $oResposta->h54_sequencial){
              $this->objpdf->Setfont('Arial',"B",10);
              $this->objpdf->cell(30,5,'X',0,1,"C");   
              $this->objpdf->Setfont('Arial',"",10);
           }
           $this->objpdf->setxy(170,$iYLinha);

          // $this->objpdf->line(20,$iYLinha+2,190,$iYLinha+2);
         }
        
         $this->objpdf->line(200,$this->objpdf->getY(),200,$iYquesito);
         $this->objpdf->ln(3);
         if ($this->dadosAvaliacao->h50_confobs == 2 or $this->dadosAvaliacao->h50_confobs == 3){

           $this->objpdf->Setx(20);
           $this->objpdf->Setfont('Arial',"b",10);
           $this->objpdf->cell(180,5,"Observações",1,1,"C");
           $this->objpdf->Setx(20);
           $this->objpdf->Setfont('Arial',"",8);
           $this->objpdf->multicell(180,4,$this->objEstagio->getObsQuestao($oQuestoes->h53_sequencial,"obs"),1,"L");
           $this->objpdf->Setx(20);
           $this->objpdf->Setfont('Arial',"b",10);
           $this->objpdf->cell(180,5,"Recomendações",1,1,"C");
           $this->objpdf->Setfont('Arial',"",8);
           $this->objpdf->Setx(20);
           $this->objpdf->multicell(180,4,$this->objEstagio->getObsQuestao($oQuestoes->h53_sequencial,"rec"),1,"L");
        }
     }

     $this->objpdf->ln(3);
     if ($this->dadosAvaliacao->h50_confobs == 1 or $this->dadosAvaliacao->h50_confobs == 3){

        $this->objpdf->Setx(20);
        $this->objpdf->Setfont('Arial',"b",10);
        $this->objpdf->cell(180,5,"Observações",1,1,"C");
        $this->objpdf->Setx(20);
        $this->objpdf->Setfont('Arial',"",8);
        $this->objpdf->multicell(180,5,$this->objEstagio->getObsQuesito($oQuesito->h51_sequencial,"obs"),1,"L");
        $this->objpdf->Setx(20);
        $this->objpdf->Setfont('Arial',"b",10);
        $this->objpdf->cell(180,5,"Recomendações",1,1,"C");
        $this->objpdf->Setx(20);
        $this->objpdf->Setfont('Arial',"",8);
        $this->objpdf->multicell(180,5,$this->objEstagio->getObsQuesito($oQuesito->h51_sequencial,"rec"),1,"L");
     }
   }
   
 $this->objpdf->ln();
 $this->objpdf->Setfont('Arial',"",8);
 $this->objpdf->text(10,$this->objpdf->h - 10,"Data da Avaliação : ".db_formatar($this->dadosAvaliacao->h64_data,"d"));
 $this->objpdf->text(55,$this->objpdf->h - 10,"Avaliador : ".$this->dadosAvaliacao->nomeavaliador);
 $this->objpdf->text(125,$this->objpdf->h - 10,"Presidente da Comissão : ".$nomePresidente);
}

