<?
include("assinatura.php");

class cl_cabec_rodape extends cl_assinatura {
//|00|//assinatura
//|10|//Esta é o construtor da classe. Ele permite que seja impresso a assinatura do usuario corrente ou  
//|10|//de um tipo de assinatura específica a qual será definida nas tabelas db_paragrafos e db_documentos
//|10|//escolhendo o tipo de assinatura de acordo com a tabela db_tipodoc.
//|15|//$classinatura = new cl_assinatura;
  var $modelo = null;
  
  function cl_cabec_rodape ($modelo){
    $this->modelo = $modelo;
  }


  function rodape($mod){
//#00#//assinatura_usuario
//#10#//Este método é usado gerar a assinatura do usuario que gerou o relatório
//#15#//assinatura_usuario()
/*  if($mod == 1){
      $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
      $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
      $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
      $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');
      
      $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
      $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
      $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
      $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
      $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
      $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');
  
  
      if ($this->assinatura1 != "") {
          $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
      }
  
      if ($this->assinatura2 != "") {
          $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
      }
  
      if ($this->assinatura3 != "") {
          $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);
      }
  
      $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
      $this->objpdf->SetFont('Arial','',6);
      $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
      $this->objpdf->text($xcol+27-(strlen($this->assinatura1)/2),$xlin+213,$this->assinatura1);
      $this->objpdf->text($xcol+27-(strlen($this->assinatura2)/2),$xlin+227,$this->assinatura2);
      $this->objpdf->text($xcol+27-(strlen($this->assinatura3)/2),$xlin+240,$this->assinatura3);
  
      $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
      $this->objpdf->text($xcol+88-(strlen($this->assinaturaprefeito)/2),$xlin+227,$this->assinaturaprefeito);
           																					    
      $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
      $this->objpdf->text($xcol+170,$xlin+207,'DATA');
      $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
      $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
      $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
      $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');
    
      $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
      
      $this->objpdf->SetFont('Arial','',7);
      $this->objpdf->text($xcol+90,$xlin+249,'R E C I B O');
      $this->objpdf->text($xcol+45,$xlin+253,'RECEBI(EMOS) DO MUNICÍPIO DE '.$this->municpref.', A IMPORTÂNCIA ABAIXO ESPECIFICADA, REFERENTE À:');
      $this->objpdf->text($xcol+2,$xlin+257,'(     ) PARTE DO VALOR EMPENHADO');
      $this->objpdf->text($xcol+102,$xlin+257,'(     ) SALDO/TOTAL EMPENHADO');
      $this->objpdf->text($xcol+2,$xlin+261,'R$');
      $this->objpdf->text($xcol+102,$xlin+261,'R$');
      $this->objpdf->text($xcol+2,$xlin+265,'EM ________/________/________',0,0,'C',0);
      $this->objpdf->text($xcol+42,$xlin+265,'_________________________________________',0,0,'C',0);
      $this->objpdf->text($xcol+102,$xlin+265,'EM ________/________/________',0,0,'C',0);
      $this->objpdf->text($xcol+142,$xlin+265,'_________________________________________',0,1,'C',0);
      $this->objpdf->SetFont('Arial','',6);
      $this->objpdf->text($xcol+62,$xlin+269,'CREDOR',0,0,'C',0);
      $this->objpdf->text($xcol+162,$xlin+269,'CREDOR',0,1,'C',0);
    }*/
  }
  return $rodape;
}
?>
