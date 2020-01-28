<?
set_time_limit(0);
session_cache_limiter('none');

global $HTTP_POST_VARS;
global $HTTP_SERVER_VARS;

if ( session_id() == null )
   session_start();

if(!defined('DB_BIBLIOT')){

  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("libs/db_usuariosonline.php"));

  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_SERVER_VARS);

  if(!defined('FPDF_FONTPATH')){
    define('FPDF_FONTPATH', 'fpdf151/font/');
  }
  require_once(modification('fpdf151/fpdf.php'));
}

class scpdf extends fpdf {
//|00|//scpdf
//|10|//Esta classe é uma extensão da classe |fpdf|, não possui cabeçalho ou rodapé, é classe utilizada
//|10|//na geração de formularios tais como: carnês de parcelamento, recibos, alvarás, etc
  function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {

    $h = $this->h;
    $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
    $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
  }

   function VCell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false) {
     //Output a cell
     $k=$this->k;
     if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
     {
       //Automatic page break
       $x=$this->x;
       $ws=$this->ws;
       if($ws>0)
       {
         $this->ws=0;
         $this->_out('0 Tw');
       }
       $this->AddPage($this->CurOrientation,$this->CurPageFormat);
       $this->x=$x;
       if($ws>0)
       {
         $this->ws=$ws;
         $this->_out(sprintf('%.3F Tw',$ws*$k));
       }
     }
     if($w==0)
       $w=$this->w-$this->rMargin-$this->x;
     $s='';
     // begin change Cell function
     if($fill || $border>0)
     {
       if($fill)
         $op=($border>0) ? 'B' : 'f';
       else
         $op='S';
       if ($border>1) {
         $s=sprintf('q %.2F w %.2F %.2F %.2F %.2F re %s Q ',$border,
             $this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
       }
       else
         $s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
     }
     if(is_string($border))
     {
       $x=$this->x;
       $y=$this->y;
       if(is_int(strpos($border,'L')))
         $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
       else if(is_int(strpos($border,'l')))
         $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);

       if(is_int(strpos($border,'T')))
         $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
       else if(is_int(strpos($border,'t')))
         $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);

       if(is_int(strpos($border,'R')))
         $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
       else if(is_int(strpos($border,'r')))
         $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);

       if(is_int(strpos($border,'B')))
         $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
       else if(is_int(strpos($border,'b')))
         $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
     }
     if(trim($txt)!='')
     {
       $cr=substr_count($txt,"\n");
       if ($cr>0) { // Multi line
         $txts = explode("\n", $txt);
         $lines = count($txts);
         for($l=0;$l<$lines;$l++) {
           $txt=$txts[$l];
           $w_txt=$this->GetStringWidth($txt);
           if ($align=='U')
             $dy=$this->cMargin+$w_txt;
           elseif($align=='D')
           $dy=$h-$this->cMargin;
           else
             $dy=($h+$w_txt)/2;
           $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
           if($this->ColorFlag)
             $s.='q '.$this->TextColor.' ';
           $s.=sprintf('BT 0 1 -1 0 %.2F %.2F Tm (%s) Tj ET ',
               ($this->x+.5*$w+(.7+$l-$lines/2)*$this->FontSize)*$k,
               ($this->h-($this->y+$dy))*$k,$txt);
           if($this->ColorFlag)
             $s.=' Q ';
         }
       }
       else { // Single line
         $w_txt=$this->GetStringWidth($txt);
         $Tz=100;
         if ($w_txt>$h-2*$this->cMargin) {
           $Tz=($h-2*$this->cMargin)/$w_txt*100;
           $w_txt=$h-2*$this->cMargin;
         }
         if ($align=='U')
           $dy=$this->cMargin+$w_txt;
         elseif($align=='D')
         $dy=$h-$this->cMargin;
         else
           $dy=($h+$w_txt)/2;
         $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
         if($this->ColorFlag)
           $s.='q '.$this->TextColor.' ';
         $s.=sprintf('q BT 0 1 -1 0 %.2F %.2F Tm %.2F Tz (%s) Tj ET Q ',
             ($this->x+.5*$w+.3*$this->FontSize)*$k,
             ($this->h-($this->y+$dy))*$k,$Tz,$txt);
         if($this->ColorFlag)
           $s.=' Q ';
       }
     }
     // end change Cell function
     if($s)
       $this->_out($s);
     $this->lasth=$h;
     if($ln>0)
     {
       //Go to next line
       $this->y+=$h;
       if($ln==1)
         $this->x=$this->lMargin;
     }
     else
       $this->x+=$w;
   }

   function TextWithRotation($x,$y,$txt,$txt_angle,$font_angle=0)
    {
        $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));

        $font_angle+=90+$txt_angle;
        $txt_angle*=M_PI/180;
        $font_angle*=M_PI/180;

        $txt_dx=cos($txt_angle);
        $txt_dy=sin($txt_angle);
        $font_dx=cos($font_angle);
        $font_dy=sin($font_angle);

        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',
                 $txt_dx,$txt_dy,$font_dx,$font_dy,
                 $x*$this->k,($this->h-$y)*$this->k,$txt);
        $this->_out($s);


    }
}

//|XX|//
?>
