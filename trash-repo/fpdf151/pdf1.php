<?
  set_time_limit(0);
  if(!defined('DB_BIBLIOT')){
     session_cache_limiter('none');
     session_start();
     require("libs/db_stdlib.php");
     require("libs/db_conecta.php");
     include("libs/db_sessoes.php");
     include("libs/db_usuariosonline.php");
     db_postmemory($HTTP_POST_VARS);
     db_postmemory($HTTP_SERVER_VARS);
     include('fpdf.php');
  }

     define('FPDF_FONTPATH','fpdf151/font/');
  class pdf1 extends fpdf {
  //|00|//pdf1
  //|10|//Esta classe � uma extens�o da classe |fpdf| e difere da mesma pelo fato de que nesta  classe
  //|10|//foram alterados os m�todos |header| (cabe�alho da p�gina) de  |footer|  (rodap�)  para   que
  //|10|//atendessem as nossas necessidades, da seguinte maneira:
  //|10|//|header|     :    - O logotipo da prefeitura ficou centralizado;
  //|10|//                  - Os dados da prefeitura tais como: estado,nome e departamento ficaram 
  //|10|//                    prefeitura;
  //|10|//               Contem ainda vari�veis livres para o desenvolvedor as quais  ser�o  impressas
  //|10|//               na parte superior direita da tela, s�o elas:
  //|10|//                  - head1
  //|10|//
  //|10|//|footer|     :    - contem dados como:
  //|10|//                      - programa que gerou o relat�rio;
  //|10|//                      - emissor;
  //|10|//                      - exerc�cio;
  //|10|//                      - data e hora da emiss�o;
  //|10|//                      - n�mero da p�gina.


   // ################################# Initialization

      var $wLine; // Maximum width of the line
      var $hLine; // Height of the line
      var $Text; // Text to display
      var $border;
      var $align; // Justification of the text
      var $fill;
      var $Padding;
      var $lPadding;
      var $tPadding;
      var $bPadding;
      var $rPadding;
      var $TagStyle; // Style for each tag
      var $Indent;
      var $Space; // Minimum space between words
      var $PileStyle; 
      var $Line2Print; // Line to display
      var $NextLineBegin; // Buffer between lines 
      var $TagName;
      var $Delta; // Maximum width minus width
      var $StringLength; 
      var $LineLength;
      var $wTextLine; // Width minus paddings
      var $nbSpace; // Number of spaces in the line
      var $Xini; // Initial position
      var $href; // Current URL
      var $TagHref; // URL for a cell

      // ################################# Public Functions




  //Page header
    function Header() {
  //$dados = @db_query("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".@$GLOBALS["DB_instit"]);
  //$url = @pg_result($dados,0,"url");
  //global $nomeinst;
  //$nomeinst = pg_result($dados,0,"nomeinst");
  //global $ender;
  //$ender = pg_result($dados,0,"ender");
  $sql = "select nomeinst,
                 bairro,
                 cgc,
                 trim(ender)||','||trim(cast(numero as text)) as ender,
                 upper(munic) as munic,
                 uf,
                 telef,
                 email,
                 url,
                 logo, 
                 db12_extenso
          from db_config 
                 inner join db_uf on db12_uf = uf
          where codigo = ".db_getsession("DB_instit");
  $result = db_query($sql);
  global $nomeinst;
  global $ender;
  global $munic;
  global $cgc;
  global $bairro;
  global $uf;
  global $db12_extenso;
  global $logo;
	//echo $sql;
  db_fieldsmemory($result,0);
  $db12_extenso = pg_result($result,0,"db12_extenso");
  /// seta a margem esquerda que veio do relatorio
  $S = $this->lMargin;
  $this->SetLeftMargin(10);
  $Letra = 'Times';

  $posini = ($this->w/6)-15;

  //$this->Image("imagens/files/logo_boleto.png",$posini,8,20);
  $this->Image('imagens/files/'.$logo,$posini,8,20);
  $this->Ln(1);
  $this->SetFont($Letra,'',10);
  $this->MultiCell(0,4,$db12_extenso,0,"C",0);
  $this->SetFont($Letra,'B',13);
  $this->MultiCell(0,6,$nomeinst,0,"C",0);
  $this->SetFont($Letra,'B',12);
  $this->MultiCell(0,4,@$GLOBALS["head1"],0,"C",0);
  $this->Ln(10);
  $this->SetLeftMargin($S);
}
//Page footer

function Footer() {
  $S = $this->lMargin;
  $this->SetLeftMargin(10);
  global $conn;
  global $result;
  global $url;
    //Position at 1.5 cm from bottom
    
    $this->SetFont('Arial','',5);
    $this->text(10,289,'Base: '.@$GLOBALS["DB_NBASE"]);
    $this->SetFont('Arial','I',5);
    $this->SetY(-10);
    $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
	$nome = substr($nome,strrpos($nome,"/")+1);
	$result_nomeusu = db_query("select nome as nomeusu from db_usuarios where id_usuario =".db_getsession("DB_id_usuario"));
	if (pg_numrows($result_nomeusu)>0){
	  	$nomeusu = pg_result($result_nomeusu,0,0);
	}
	if (isset($nomeusu)&&$nomeusu!=""){
	   	$emissor = $nomeusu;
	}else{
	   	$emissor = @$GLOBALS["DB_login"];
	}	
	
    /*
    * Modifica��o para exibir o caminho do menu 
    * na base do relat�rio
    */         
      $sSqlMenuAcess = "SELECT fc_montamenu(funcao) as menu from db_itensmenu where id_item =".db_getsession("DB_itemmenu_acessado");
      $rsMenuAcess   = db_query($conn,$sSqlMenuAcess);
      $sMenuAcess    = substr(pg_result($rsMenuAcess,0,"menu"),0,50);	
	
    $this->Cell(0,10,$url. '  '.$sMenuAcess.'  '.$nome.'  Emissor: '.substr(ucwords(strtolower($emissor)),0,30).'  Exerc�cio: '.db_getsession("DB_anousu").
                         '   Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"),"T",0,'L');    

    $this->Cell(0,10,'P�gina '.$this->PageNo().' de {nb}',0,1,'R');
    $this->SetLeftMargin($S);

}


 function WriteTag($w,$h,$txt,$border=0,$align="J",$fill=0,$padding=0)
    {
        $this->wLine=$w;
        $this->hLine=$h;
        $this->Text=trim($txt);
        $this->Text=ereg_replace("\n|\r|\t","",$this->Text);
        $this->border=$border;
        $this->align=$align;
        $this->fill=$fill;
        $this->Padding=$padding;

        $this->Xini=$this->GetX();
        $this->href="";
        $this->PileStyle=array();        
        $this->TagHref=array();
        $this->LastLine=false;

        $this->SetSpace();
        $this->Padding();
        $this->LineLength();
        $this->BorderTop();

        while($this->Text!="")
        {
            $this->MakeLine();
            $this->PrintLine();
        }

        $this->BorderBottom();
    }


    function SetStyle($tag,$family,$style,$size,$color,$indent=-1)
    {
         $tag=trim($tag);
         $this->TagStyle[$tag]['family']=trim($family);
         $this->TagStyle[$tag]['style']=trim($style);
         $this->TagStyle[$tag]['size']=trim($size);
         $this->TagStyle[$tag]['color']=trim($color);
         $this->TagStyle[$tag]['indent']=$indent;
    }


    // ############################ Private Functions

    function SetSpace() // Minimal space between words
    {
        $tag=$this->Parser($this->Text);
        $this->FindStyle($tag[2],0);
        $this->DoStyle(0);
        $this->Space=$this->GetStringWidth(" ");
    }


    function Padding()
    {
        if(ereg("^.+,",$this->Padding)) {
            $tab=explode(",",$this->Padding);
            $this->lPadding=$tab[0];
            $this->tPadding=$tab[1];
            if(isset($tab[2]))
                $this->bPadding=$tab[2];
            else
                $this->bPadding=$this->tPadding;
            if(isset($tab[3]))
                $this->rPadding=$tab[3];
            else
                $this->rPadding=$this->lPadding;
        }
        else
        {
            $this->lPadding=$this->Padding;
            $this->tPadding=$this->Padding;
            $this->bPadding=$this->Padding;
            $this->rPadding=$this->Padding;
        }
        if($this->tPadding<$this->LineWidth)
            $this->tPadding=$this->LineWidth;
    }


    function LineLength()
    {
        if($this->wLine==0)
            $this->wLine=$this->fw - $this->Xini - $this->rMargin;

        $this->wTextLine = $this->wLine - $this->lPadding - $this->rPadding;
    }


    function BorderTop()
    {
        $border=0;
        if($this->border==1)
            $border="TLR";
        $this->Cell($this->wLine,$this->tPadding,"",$border,0,'C',$this->fill);
        $y=$this->GetY()+$this->tPadding;
        $this->SetXY($this->Xini,$y);
    }


    function BorderBottom()
    {
        $border=0;
        if($this->border==1)
            $border="BLR";
        $this->Cell($this->wLine,$this->bPadding,"",$border,0,'C',$this->fill);
    }


    function DoStyle($tag) // Applies a style
    {
        $tag=trim($tag);
        $this->SetFont($this->TagStyle[$tag]['family'],
            $this->TagStyle[$tag]['style'],
            $this->TagStyle[$tag]['size']);

        $tab=explode(",",$this->TagStyle[$tag]['color']);
        if(count($tab)==1)
            $this->SetTextColor($tab[0]);
        else
            $this->SetTextColor($tab[0],$tab[1],$tab[2]);
    }


    function FindStyle($tag,$ind) // Inheritance from parent elements
    {
        $tag=trim($tag);

        // Family
        if($this->TagStyle[$tag]['family']!="")
            $family=$this->TagStyle[$tag]['family'];
        else
        {
            reset($this->PileStyle);
            while(list($k,$val)=each($this->PileStyle))
            {
                $val=trim($val);
                if($this->TagStyle[$val]['family']!="") {
                    $family=$this->TagStyle[$val]['family'];
                    break;
                }
            }
        }

        $style1=strtoupper($this->TagStyle[$tag]['style']);
        if($style1=="N")
            $style="";
        else
        {
            reset($this->PileStyle);
            while(list($k,$val)=each($this->PileStyle))
            {
                $val=trim($val);
                $style1=strtoupper($this->TagStyle[$val]['style']);
                if($style1=="N")
                    break;
                else
                {
                    if(ereg("B",$style1))
                        $style['b']="B";
                    if(ereg("I",$style1))
                        $style['i']="I";
                    if(ereg("U",$style1))
                        $style['u']="U";
                } 
            }
            $style=$style['b'].$style['i'].$style['u'];
        }

        // Size
        if($this->TagStyle[$tag]['size']!=0)
            $size=$this->TagStyle[$tag]['size'];
        else
        {
            reset($this->PileStyle);
            while(list($k,$val)=each($this->PileStyle))
            {
                $val=trim($val);
                if($this->TagStyle[$val]['size']!=0) {
                    $size=$this->TagStyle[$val]['size'];
                    break;
                }
            }
        }

        // Color
        if($this->TagStyle[$tag]['color']!="")
            $color=$this->TagStyle[$tag]['color'];
        else
        {
            reset($this->PileStyle);
            while(list($k,$val)=each($this->PileStyle))
            {
                $val=trim($val);
                if($this->TagStyle[$val]['color']!="") {
                    $color=$this->TagStyle[$val]['color'];
                    break;
                }
            }
        }
         
        // Result
        $this->TagStyle[$ind]['family']=$family;
        $this->TagStyle[$ind]['style']=$style;
        $this->TagStyle[$ind]['size']=$size;
        $this->TagStyle[$ind]['color']=$color;
        $this->TagStyle[$ind]['indent']=$this->TagStyle[$tag]['indent'];
    }


    function Parser($text)
    {
        $tab=array();
        // Closing tag
        if(ereg("^(</([^>]+)>).*",$text,$regs)) {
            $tab[1]="c";
            $tab[2]=trim($regs[2]);
        }
        // Opening tag
        else if(ereg("^(<([^>]+)>).*",$text,$regs)) {
            $regs[2]=ereg_replace("^a","a ",$regs[2]);
            $tab[1]="o";
            $tab[2]=trim($regs[2]);

            // Presence of attributes
            if(ereg("(.+) (.+)='(.+)' *",$regs[2])) {
                $tab1=split(" +",$regs[2]);
                $tab[2]=trim($tab1[0]);
                while(list($i,$couple)=each($tab1))
                {
                    if($i>0) {
                        $tab2=explode("=",$couple);
                        $tab2[0]=trim($tab2[0]);
                        $tab2[1]=trim($tab2[1]);
                        $end=strlen($tab2[1])-2;
                        $tab[$tab2[0]]=substr($tab2[1],1,$end);
                    }
                }
            }
        }
         // Space
         else if(ereg("^( ).*",$text,$regs)) {
            $tab[1]="s";
            $tab[2]=$regs[1];
        }
        // Text
        else if(ereg("^([^< ]+).*",$text,$regs)) {
            $tab[1]="t";
            $tab[2]=trim($regs[1]);
        }
        // Pruning
        $begin=strlen($regs[1]);
        $end=strlen($text);
        $text=substr($text, $begin, $end);
        $tab[0]=$text;

        return $tab;
    }


    function MakeLine() // Makes a line
    {
        $this->Text.=" ";
        $this->LineLength=array();
        $this->TagHref=array();
        $Length=0;
        $this->nbSpace=0;

        $i=$this->BeginLine();
        $this->TagName=array();

        if($i==0) {
            $Length=$this->StringLength[0];
            $this->TagName[0]=1;
            $this->TagHref[0]=$this->href;
        }

        while($Length<$this->wTextLine)
        {
            $tab=$this->Parser($this->Text);
            $this->Text=$tab[0];
            if($this->Text=="") {
                $this->LastLine=true;
                break;
            }

            if($tab[1]=="o") {
                array_unshift($this->PileStyle,$tab[2]);
                $this->FindStyle($this->PileStyle[0],$i+1);

                $this->DoStyle($i+1);
                $this->TagName[$i+1]=1;
                if($this->TagStyle[$tab[2]]['indent']!=-1) {
                    $Length+=$this->TagStyle[$tab[2]]['indent'];
                    $this->Indent=$this->TagStyle[$tab[2]]['indent'];
                }
                if($tab[2]=="a")
                    $this->href=$tab['href'];
            }

            if($tab[1]=="c") {
                array_shift($this->PileStyle);
                $this->FindStyle($this->PileStyle[0],$i+1);
                $this->DoStyle($i+1);
                $this->TagName[$i+1]=1;
                if($this->TagStyle[$tab[2]]['indent']!=-1) {
                    $this->LastLine=true;
                    $this->Text=trim($this->Text);
                    break;
                }
                if($tab[2]=="a")
                    $this->href="";
            }

            if($tab[1]=="s") {
                $i++;
                $Length+=$this->Space;
                $this->Line2Print[$i]="";
                if($this->href!="")
                    $this->TagHref[$i]=$this->href;
            }

            if($tab[1]=="t") {
                $i++;
                $this->StringLength[$i]=$this->GetStringWidth($tab[2]);
                $Length+=$this->StringLength[$i];
                $this->LineLength[$i]=$Length;
                $this->Line2Print[$i]=$tab[2];
                if($this->href!="")
                    $this->TagHref[$i]=$this->href;
             }

        }

        trim($this->Text);
        if($Length>$this->wTextLine || $this->LastLine==true)
            $this->EndLine();
    }


    function BeginLine()
    {
        $this->Line2Print=array();
        $this->StringLength=array();
        $this->FindStyle($this->PileStyle[0],0);
        $this->DoStyle(0);

        if(count($this->NextLineBegin)>0) {
            $this->Line2Print[0]=$this->NextLineBegin['text'];
            $this->StringLength[0]=$this->NextLineBegin['length'];
            $this->NextLineBegin=array();
            $i=0;
        }
        else {
            ereg("^(( *(<([^>]+)>)* *)*)(.*)",$this->Text,$regs);
            $regs[1]=ereg_replace(" ", "", $regs[1]);
            $this->Text=$regs[1].$regs[5];
            $i=-1;
        }

        return $i;
    }


    function EndLine()
    {
        if(end($this->Line2Print)!="" && $this->LastLine==false) {
            $this->NextLineBegin['text']=array_pop($this->Line2Print);
            $this->NextLineBegin['length']=end($this->StringLength);
            array_pop($this->LineLength);
        }

        while(end($this->Line2Print)=="")
            array_pop($this->Line2Print);

        $this->Delta=$this->wTextLine-end($this->LineLength);

        $this->nbSpace=0;
        for($i=0; $i<count($this->Line2Print); $i++) {
            if($this->Line2Print[$i]=="")
                $this->nbSpace++;
        }
    }


    function PrintLine()
    {
        $border=0;
        if($this->border==1)
            $border="LR";
        $this->Cell($this->wLine,$this->hLine,"",$border,0,'C',$this->fill);
        $y=$this->GetY();
        $this->SetXY($this->Xini+$this->lPadding,$y);

        if($this->Indent!=-1) {
            if($this->Indent!=0)
                $this->Cell($this->Indent,$this->hLine,"",0,0,'C',0);
            $this->Indent=-1;
        }

        $space=$this->LineAlign();
        $this->DoStyle(0);
        for($i=0; $i<count($this->Line2Print); $i++)
        {
            if($this->TagName[$i]==1)
                $this->DoStyle($i);
            if($this->Line2Print[$i]=="")
                $this->Cell($space,$this->hLine,"         ",0,0,'C',0,$this->TagHref[$i]);
            else
                $this->Cell($this->StringLength[$i],$this->hLine,$this->Line2Print[$i],0,0,'C',0,$this->TagHref[$i]);
        }

        $this->LineBreak();
        if($this->LastLine && $this->Text!="")
            $this->EndParagraph();
        $this->LastLine=false;
    }


    function LineAlign()
    {
        $space=$this->Space;
        if($this->align=="J") {
            if($this->nbSpace!=0)
                $space=$this->Space + ($this->Delta/$this->nbSpace);
            if($this->LastLine)
                $space=$this->Space;
        }

        if($this->align=="R")
            $this->Cell($this->Delta,$this->hLine,"",0,0,'C',0);

        if($this->align=="C")
            $this->Cell($this->Delta/2,$this->hLine,"",0,0,'C',0);

        return $space;
    }


    function LineBreak()
    {
        $x=$this->Xini;
        $y=$this->GetY()+$this->hLine;
        $this->SetXY($x,$y);
    }


    function EndParagraph() // Interline between paragraphs
    {
        $border=0;
        if($this->border==1)
            $border="LR";
        $this->Cell($this->wLine,$this->hLine/2,"",$border,0,'C',$this->fill);
        $x=$this->Xini;
        $y=$this->GetY()+$this->hLine/2;
        $this->SetXY($x,$y);
    }



function db_extenso($valor=0, $maiusculas=false) {
 
    $rt = '';
    $singular = array("centavo", "real", "mil", "milh�o", "bilh�o", "trilh�o", "quatrilh�o"); 
    $plural = array("centavos", "reais", "mil", "milh�es", "bilh�es", "trilh�es", 
"quatrilh�es"); 

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", 
"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"); 
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", 
"sessenta", "setenta", "oitenta", "noventa"); 
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", 
"dezesseis", "dezesete", "dezoito", "dezenove"); 
    $u = array("", "um", "dois", "tr�s", "quatro", "cinco", "seis", 
"sete", "oito", "nove"); 

    $z=0; 

    $valor = number_format($valor, 2, ".", "."); 
    $inteiro = explode(".", $valor); 
    for($i=0;$i<count($inteiro);$i++) 
        for($ii=strlen($inteiro[$i]);$ii<3;$ii++) 
            $inteiro[$i] = "0".$inteiro[$i]; 

    $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2); 
    for ($i=0;$i<count($inteiro);$i++) { 
        $valor = $inteiro[$i]; 
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]]; 
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]]; 
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : ""; 

        $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && 
$ru) ? " e " : "").$ru; 
        $t = count($inteiro)-1-$i; 
        $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : ""; 
        if ($valor == "000")$z++; elseif ($z > 0) $z--; 
        if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
//        $rt = '';
        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && 
($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r; 
    } 

         if(!$maiusculas){ 
                          return($rt ? $rt : "zero"); 
         } else { /*
	                 Trocando o " E " por " e ", fica muito + apresent�vel! 
                     Rodrigo Cerqueira, rodrigobc@fte.com.br
                    */
			  if ($rt) $rt=ereg_replace(" E "," e ",ucwords($rt));
                          return (($rt) ? ($rt) : "Zero"); 
         } 

} 



}
//|XX|//
?>
