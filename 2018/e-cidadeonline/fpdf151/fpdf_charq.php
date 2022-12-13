<?php
/****************************************************************************
* Software: FPDF                                                            *
* Version:  1.51                                                            *
* Date:     2002/08/03                                                      *
* Author:   Olivier PLATHEY                                                 *
* License:  Freeware                                                        *
*                                                                           *
* You may use and modify this software as you wish.                         *
****************************************************************************/
define('FPDF_VERSION','1.51');

class FPDF
//|00|//FPDF
//|10|//Esta é o construtor da classe. Ele permite que seja definido o formato da página, a orientação e a unidade de medida 
//|10|//usada em todos os métodos (exeto para tamanhos de fonte).
//|15|//$pdf = new FPDF($orientation='P',$unit='mm',$format='A4');
//|20|//orientation  : Orientação padrão da página. Os valores possíveis são (diferenciando maiúsculas e 
//|20|//               minúsculas):  O valor padrão é P.
//|20|//                  - P(retrato)
//|20|//                  - L(paisagem)
//|20|//unit         : Unidade de medida do usuário. Os valores possíveis são:
//|20|//                  - pt: pontos
//|20|//                  - mm: millímetros
//|20|//                  - cm: centímetros
//|20|//                  - in: polegada
//|20|//               Um ponto é igual a 1/72 polegadas, isto é o mesmo que 0.35 mm (uma polegada são 2.54 cm). Ista é 
//|20|//               uma unidade muito comum em tipografia; os tamanhos das fontes são medidos por esta unidade. 
//|20|//format       : O formato usado pelas páginas. Pode ser um dos seguintes valores (diferenciando maiúsculas e minúsculas):
//|20|//                  - A3
//|20|//                  - A4
//|20|//                  - A5
//|20|//	          - Letter
//|20|//	          - Legal 
//|20|//                  - ou um valor personalizado na forma de um array com dois elementos contendo a largura e a altura 
//|20|//                    (expressas na unidade obtida por unit).
{  
//Private properties
var $widths;
var $aligns;
var $page;               //current page number
var $n;                  //current object number
var $offsets;            //array of object offsets
var $buffer;             //buffer holding in-memory PDF
var $pages;              //array containing pages
var $state;              //current document state
var $compress;           //compression flag
var $DefOrientation;     //default orientation
var $CurOrientation;     //current orientation
var $OrientationChanges; //array indicating orientation changes
var $fwPt,$fhPt;         //dimensions of page format in points
var $fw,$fh;             //dimensions of page format in user unit
var $wPt,$hPt;           //current dimensions of page in points
var $k;                  //scale factor (number of points in user unit)
var $w,$h;               //current dimensions of page in user unit
var $lMargin;            //left margin
var $tMargin;            //top margin
var $rMargin;            //right margin
var $bMargin;            //page break margin
var $cMargin;            //cell margin
var $x,$y;               //current position in user unit for cell positionning
var $lasth;              //height of last cell printed
var $LineWidth;          //line width in user unit
var $CoreFonts;          //array of standard font names
var $fonts;              //array of used fonts
var $FontFiles;          //array of font files
var $diffs;              //array of encoding differences
var $images;             //array of used images
var $PageLinks;          //array of links in pages
var $links;              //array of internal links
var $FontFamily;         //current font family
var $FontStyle;          //current font style
var $underline;          //underlining flag
var $CurrentFont;        //current font info
var $FontSizePt;         //current font size in points
var $FontSize;           //current font size in user unit
var $DrawColor;          //commands for drawing color
var $FillColor;          //commands for filling color
var $TextColor;          //commands for text color
var $ColorFlag;          //indicates whether fill and text colors are different
var $ws;                 //word spacing
var $AutoPageBreak;      //automatic page breaking
var $PageBreakTrigger;   //threshold used to trigger page breaks
var $InFooter;           //flag set when processing footer
var $ZoomMode;           //zoom display mode
var $LayoutMode;         //layout display mode
var $title;              //title
var $subject;            //subject
var $author;             //author
var $keywords;           //keywords
var $creator;            //creator
var $AliasNbPages;       //alias for total number of pages

var $legends;		// variavel para os graficos
var $wLegend;		// variavel para os graficos	
var $sum;		// variavel para os graficos
var $NbVal;		// variavel para os graficos

var $descricao;		// string para comparar o tamanho para preenchimento
var $preech;		// string que será preenchida
var $xtam;		// tamanho do campo para preenchimento
var $imprime_rodape;	// Alterado para não imprimir rodape nos relatorios ppa


					
					

/****************************************************************************
*                                                                           *
*                              Public methods                               *
*                                                                           *
****************************************************************************/
function FPDF($orientation='P',$unit='mm',$format='A4')

{
	//Check for PHP locale-related bug
	setlocale(LC_ALL,"bra");
//	$locale = setlocale(LC_NUMERIC,0);
//	setlocale(LC_NUMERIC,"C");
//	setlocale(LC_NUMERIC,$locale);
//	if(1.1==1)
//	  $this->Error('Don\'t alter the locale before including class file');
	//Initialization of properties
	$this->page=0;
	$this->n=2;
	$this->buffer='';
	$this->pages=array();
	$this->OrientationChanges=array();
	$this->state=0;
	$this->fonts=array();
	$this->FontFiles=array();
	$this->diffs=array();
	$this->images=array();
	$this->links=array();
	$this->InFooter=false;
	$this->FontFamily='';
	$this->FontStyle='';
	$this->FontSizePt=12;
	$this->underline=false;
	$this->DrawColor='0 G';
	$this->FillColor='0 g';
	$this->TextColor='0 g';
	$this->ColorFlag=false;
	$this->ws=0;
	//Standard fonts
	$this->CoreFonts['courier']='Courier';
	$this->CoreFonts['courierB']='Courier-Bold';
	$this->CoreFonts['courierI']='Courier-Oblique';
	$this->CoreFonts['courierBI']='Courier-BoldOblique';
	$this->CoreFonts['helvetica']='Helvetica';
	$this->CoreFonts['helveticaB']='Helvetica-Bold';
	$this->CoreFonts['helveticaI']='Helvetica-Oblique';
    $this->CoreFonts['helveticaBI']='Helvetica-BoldOblique';
	$this->CoreFonts['times']='Times-Roman';
	$this->CoreFonts['timesB']='Times-Bold';
	$this->CoreFonts['timesI']='Times-Italic';
	$this->CoreFonts['timesBI']='Times-BoldItalic';
	$this->CoreFonts['symbol']='Symbol';
	$this->CoreFonts['zapfdingbats']='ZapfDingbats';
	//Scale factor
	if($unit=='pt')
		$this->k=1;
	elseif($unit=='mm')
		$this->k=72/25.4;
	elseif($unit=='cm')
		$this->k=72/2.54;
	elseif($unit=='in')
		$this->k=72;
	else
		$this->Error('Incorrect unit: '.$unit);
	//Page format
	if(is_string($format))
	{
		$format=strtolower($format);
		if($format=='a3')
			$format=array(841.89,1190.55);
		elseif($format=='a4')
			$format=array(595.28,841.89);
		elseif($format=='a5')
			$format=array(420.94,595.28);
		elseif($format=='letter')
			$format=array(612,792);
		elseif($format=='legal')
			$format=array(612,1008);
		elseif($format=='carta')
			$format=array(501.736,1190.55);
		else
			$this->Error('Unknown page format: '.$format);
		$this->fwPt=$format[0];
		$this->fhPt=$format[1];
	}
	else
	{
		$this->fwPt=$format[0]*$this->k;
		$this->fhPt=$format[1]*$this->k;
	}
	$this->fw=$this->fwPt/$this->k;
	$this->fh=$this->fhPt/$this->k;
	//Page orientation
	$orientation=strtolower($orientation);
	if($orientation=='p' or $orientation=='portrait')
	{
		$this->DefOrientation='P';
		$this->wPt=$this->fwPt;
		$this->hPt=$this->fhPt;
	}
	elseif($orientation=='l' or $orientation=='landscape')
	{
		$this->DefOrientation='L';
		$this->wPt=$this->fhPt;
		$this->hPt=$this->fwPt;
	}
	else
		$this->Error('Incorrect orientation: '.$orientation);
	$this->CurOrientation=$this->DefOrientation;
	$this->w=$this->wPt/$this->k;
	$this->h=$this->hPt/$this->k;
	//Page margins (1 cm)
	$margin=28.35/$this->k;
	$this->SetMargins($margin,$margin);
	//Interior cell margin (1 mm)
	$this->cMargin=$margin/10;
	//Line width (0.2 mm)
	$this->LineWidth=.567/$this->k;
	//Automatic page break
	$this->SetAutoPageBreak(true,2*$margin);
	//Full width display mode
	$this->SetDisplayMode('fullwidth');
	//Compression
	$this->SetCompression(true);
	$this->imprime_rodape = true;
}

// $descricao          // string para comparar o tamanho para preenchimento
// $preech             // string que será preenchida
// $xtam		// tamnaho da string a ser preenchida
function preenchimento($descricao,$xtam,$preech='.')
{
   $ww    = $this->GetStringWidth($descricao);
   if ($ww < $xtam ){
      $quant   = ($xtam-$ww)/$this->GetStringWidth($preech);
      $xdots = str_repeat($preech,$quant);
   }else{
     $xdots = '';
   }
   return $xdots;
}

function SetMargins($left,$top,$right=-1)
//#00#//setmargins
//#10#//Define as margens esquerda, superior e direita. Por padrão elas são iguais a 1 cm. Chame este método para alerá-las.
//#15#//setmargins($left,$top,$right=-1);
//#20#//left         : Margem esquerda.
//#20#//top          : Margem superior.
//#20#//right        : Margem direita. O valor padrão é o mesmo da esquerda.
{
	//Set left, top and right margins
	$this->lMargin=$left;
	$this->tMargin=$top;
	if($right==-1)
		$right=$left;
	$this->rMargin=$right;
}

function SetLeftMargin($margin)
//#00#//setleftmargin
//#10#//Define a margem esquerda. O método pode ser chamado antes de criar a primeira página.
//#10#//Se a abscissa corrente sair da página, ela é trazida de volta para a margem.
//#15#//setleftmargin(margin)
//#20#//margin       : A margem.
{
	//Set left margin
	$this->lMargin=$margin;
	if($this->page>0 and $this->x<$margin)
		$this->x=$margin;
}

function SetTopMargin($margin)
//#00#//settopmargin 
//#10#//Define a margem superior do documento. Este método pode ser chamado antes de criar a primeira página.
//#15#//SetTopMargin($margin)
//#20#//margin       : A margem.
{
	//Set top margin
	$this->tMargin=$margin;
}

function SetRightMargin($margin)
//#00#//setrightmargin
//#10#//Define a margem direita. O método pode ser chamado antes de criar a primeira página.
//#15#//setrightmargin(margin)
//#20#//margin       : A margem.
{
	//Set right margin
	$this->rMargin=$margin;
}

function SetAutoPageBreak($auto,$margin=0)
{
	//Set auto page break mode and triggering margin
	$this->AutoPageBreak=$auto;
	$this->bMargin=$margin;
	$this->PageBreakTrigger=$this->h-$margin;
}

function SetDisplayMode($zoom,$layout='continuous')
{
	//Set display mode in viewer
	if($zoom=='fullpage' or $zoom=='fullwidth' or $zoom=='real' or $zoom=='default' or !is_string($zoom))
		$this->ZoomMode=$zoom;
	elseif($zoom=='zoom')
		$this->ZoomMode=$layout;
	else
		$this->Error('Incorrect zoom display mode: '.$zoom);
	if($layout=='single' or $layout=='continuous' or $layout=='two' or $layout=='default')
		$this->LayoutMode=$layout;
	elseif($zoom!='zoom')
		$this->Error('Incorrect layout display mode: '.$layout);
}

function SetCompression($compress)
{
	//Set page compression
	if(function_exists('gzcompress'))
		$this->compress=$compress;
	else
		$this->compress=false;
}

function SetTitle($title)
{
	//Title of document
	$this->title=$title;
}

function SetSubject($subject)
{
	//Subject of document
	$this->subject=$subject;
}

function SetAuthor($author)
{
	//Author of document
	$this->author=$author;
}

function SetKeywords($keywords)
{
	//Keywords of document
	$this->keywords=$keywords;
}

function SetCreator($creator)
{
	//Creator of document
	$this->creator=$creator;
}

function AliasNbPages($alias='{nb}')
//#00#//aliasnbpages
//#10#//Define um apelido para o número total de páginas. Ele será substituído quando o documento for fechado.
//#10#//aliasnbpages($alias='{nb}')
//#15#//alias        : O apelido. Valor padrão: {nb}. 
//#99#//No |footer| terá uma linha selhante a linha abaixo que imprime o número da página  corrente e o  total
//#99#//de páginas do documento.
//#99#//  $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');

{
	//Define an alias for total number of pages
	$this->AliasNbPages=$alias;
}

function Error($msg)
{
	//Fatal error
	die('<B>FPDF error: </B>'.$msg);
}

function Open()
//#00#//open
//#10#//Este método inicia a geração de um documento PDF; ele deve ser chamado antes que qualquer comando de escrita. 
//#10#//Nenhua página é criada com este método, para isto é necessário que se chame |addpage()|.
//#15#//open()
{
	//Begin document
	$this->_begindoc();
}

function Close()
{
	//Terminate document
	if($this->page==0)
		$this->AddPage();
	//Page footer
	$this->InFooter=true;
	$this->Footer();
	$this->InFooter=false;
	//Close page
	$this->_endpage();
	//Close document
	$this->_enddoc();
}

function AddPage($orientation='')
//#00#//addpage
//#10#//Adiciona uma página nova ao documento. Se uma página já existir, o método de Footer() é chamado antes para saída 
//#10#//do rodapé. Então a página é adicionada, a posição atual é ajustada ao  canto  superior-esquerdo de acordo com as 
//#10#//margens esquerdas e superiores, e Header() é chamado para montar o cabeçalho.
//#10#//A fonte que foi ajustada antes de chamar é restaurada  automaticamente.  Não há nenhuma necessidade chamar outra 
//#10#//vez |setfont()| se você quiser continuar com a mesma fonte. O mesmo é verdadeiro para cores e largura da linha.
//#10#//A origem do sistema de coordenadas está no de canto superior-esquerdo e as ordenadas cescem para baixo.
//#15#//addpage($orientation='')
//#20#//orientation  : Orientação da página. Os valores possíveis são (diferenciando maiúsculas e minúsculas):
//#20#//                  - P para relrato
//#20#//                  - L para paisagem
//#20#//               O valor padrão é o que foi passado ao construtor. |fpdf|

    
{
	//Start a new page
	$family=$this->FontFamily;
	$style=$this->FontStyle.($this->underline ? 'U' : '');
	$size=$this->FontSizePt;
	$lw=$this->LineWidth;
	$dc=$this->DrawColor;
	$fc=$this->FillColor;
	$tc=$this->TextColor;
	$cf=$this->ColorFlag;
	if($this->page>0)
	{
		//Page footer
		$this->InFooter=true;
		$this->Footer();
		$this->InFooter=false;
		//Close page
		$this->_endpage();
	}
	//Start new page
	$this->_beginpage($orientation);
	//Set line cap style to square
	$this->_out('2 J');
	//Set line width
	$this->LineWidth=$lw;
	$this->_out(sprintf('%.2f w',$lw*$this->k));
	//Set font
	if($family)
		$this->SetFont($family,$style,$size);
	//Set colors
	$this->DrawColor=$dc;
	if($dc!='0 G')
		$this->_out($dc);
	$this->FillColor=$fc;
	if($fc!='0 g')
		$this->_out($fc);
	$this->TextColor=$tc;
	$this->ColorFlag=$cf;
	//Page header
	$this->Header();
	//Restore line width
	if($this->LineWidth!=$lw)
	{
		$this->LineWidth=$lw;
		$this->_out(sprintf('%.2f w',$lw*$this->k));
	}
	//Restore font
	if($family)
		$this->SetFont($family,$style,$size);
	//Restore colors
	if($this->DrawColor!=$dc)
	{
		$this->DrawColor=$dc;
		$this->_out($dc);
	}
	if($this->FillColor!=$fc)
	{
		$this->FillColor=$fc;
		$this->_out($fc);
	}
	$this->TextColor=$tc;
	$this->ColorFlag=$cf;
}

function Header()
{
	//To be implemented in your own inherited class
}

function Footer()
{
	//To be implemented in your own inherited class
}

function PageNo()
{
	//Get current page number
	return $this->page;
}

function SetDrawColor($r,$g=-1,$b=-1)
//#00#//setdrawcolor
//#10#//Define  uma cor  para  ser  usada em todas as operações de desenho  (linhas,  retângulos e  bordas de  células). 
//#10#//Ela pode ser informada como componentes RGB ou tons de cinza.  O método  pode ser  chamado  antes   antes  que a
//#10#//primeira página seja criada e o valor será mantido de uma página para outra.
//#15#//setdrawcolor($r,$g=-1,$b=-1)
//#20#//r            : Se g e b estiverem preenchidos, informa o componente vermelho;  caso  contrário,  indica o tom de
//#20#//               cinza. Valores entre 0 e 255.
//#20#//g            : Componente verde (entre 0 e 255).
//#20#//b            : Componente Azul (entre 0 e 255).
{
	//Set color for all stroking operations
	if(($r==0 and $g==0 and $b==0) or $g==-1)
		$this->DrawColor=sprintf('%.3f G',$r/255);
	else
		$this->DrawColor=sprintf('%.3f %.3f %.3f RG',$r/255,$g/255,$b/255);
	if($this->page>0)
		$this->_out($this->DrawColor);
}

function SetFillColor($r,$g=-1,$b=-1)
//#00#//setfillcolor
//#10#//Define a cor  a ser usada  em todas  as operações de preenchimento (retângulos preenchidos e fundos de células).
//#10#//Ela pode ser informada como componentes RGB ou tons de  cinza.  O método  pode  ser chamado  antes  antes  que a
//#10#//primeira página seja criada e o valor será mantido de uma página para outra.
//#15#//setfillcolor($r,$g=-1,$b=-1)
//#20#//r            : Se g e b estiverem preenchidos, informa o componente vermelho;  caso  contrário,  indica o tom de
//#20#//               cinza. Valores entre 0 e 255.
//#20#//g            : Componente verde (entre 0 e 255).
//#20#//b            : Componente Azul (entre 0 e 255).
{
	//Set color for all filling operations
	if(($r==0 and $g==0 and $b==0) or $g==-1)
		$this->FillColor=sprintf('%.3f g',$r/255);
	else
		$this->FillColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
	$this->ColorFlag=($this->FillColor!=$this->TextColor);
	if($this->page>0)
		$this->_out($this->FillColor);
}

function SetTextColor($r,$g=-1,$b=-1)
//#00#//settextcolor
//#10#//Define a cor usada pelo texto. Ele pode ser informado como componentes RGB ou tons de cinza. O método  pode  ser
//#10#//chamado antes que a primeira página seja criada e o valor será mantido para as páginas seguintes.
//#15#//settextcolor($r,$g=-1,$b=-1)
//#20#//r            : Se g e b estiverem preenchidos, informa o componente vermelho;  caso  contrário,  indica o tom de
//#20#//               cinza. Valores entre 0 e 255.
//#20#//g            : Componente verde (entre 0 e 255).
//#20#//b            : Componente Azul (entre 0 e 255).
{
	//Set color for text
	if(($r==0 and $g==0 and $b==0) or $g==-1)
		$this->TextColor=sprintf('%.3f g',$r/255);
	else
		$this->TextColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
	$this->ColorFlag=($this->FillColor!=$this->TextColor);
}

function GetStringWidth($s)
{
	//Get width of a string in the current font
	$s=(string)$s;
	$cw=&$this->CurrentFont['cw'];
	$w=0;
	$l=strlen($s);
	for($i=0;$i<$l;$i++)
		$w+=$cw[$s{$i}];
	return $w*$this->FontSize/1000;
}

function SetLineWidth($width)
{
	//Set line width
	$this->LineWidth=$width;
	if($this->page>0)
		$this->_out(sprintf('%.2f w',$width*$this->k));
}

function Line($x1,$y1,$x2,$y2)
//#00#//line
//#10#//Desenha uma linha entre dois pontos.
//#15#//line($x1,$y1,$x2,$y2)
//#20#//x1           : Abscissa do primeiro ponto. 
//#20#//y1           : Ordenada do primeiro ponto. 
//#20#//x2           : Abscissa do segundo ponto. 
//#20#//y2           : Ordenada do segundo ponto. 
{
	//Draw a line
	$this->_out(sprintf('%.2f %.2f m %.2f %.2f l S',$x1*$this->k,($this->h-$y1)*$this->k,$x2*$this->k,($this->h-$y2)*$this->k));
}

function Rect($x,$y,$w,$h,$style='')
//#00#//rect
//#10#//Desenha um retângulo. Ele pode ser desenhado com linhas (somente borda), preenchido (sem bordas) ou ambos.
//#15#//rect($x,$y,$w,$h,$style='')
//#20#//x            : Abscissa do canto superior-esquerdo. 
//#20#//y            : Ordenada do canto superior-esquerdo. 
//#20#//w	     : Largura. 
//#20#//h            : Altura. 
//#20#//style        : Estilo do retângulo. Os valores possíveis são:
//#20#//                  - D ou um texto vazio: desenha só a borda. Este é o valor padrão.
//#20#//                  - F: preenche
//#20#//	          - DF ou FD: desenha a borda e preenche 
{
	//Draw a rectangle
	if($style=='F')
		$op='f';
	elseif($style=='FD' or $style=='DF')
		$op='B';
	else
		$op='S';
	$this->_out(sprintf('%.2f %.2f %.2f %.2f re %s',$x*$this->k,($this->h-$y)*$this->k,$w*$this->k,-$h*$this->k,$op));
}

function AddFont($family,$style='',$file='')
//#00#//addfont
//#10#//Importa uma fonte TrueType ou Type1 e a deixa disponível. Antes, é preciso gerar  um  arquivo  de definição   de
//#10#//fonte com a ferramenta makefont.php.
//#10#//O arquivo de definição (e o próprio arquivo de fonte quando   embutido)  deve  estar no diretório atual ou em um
//#10#//indicado por FPDF_FONTPATH se esta constante for definida. Se não for encontrado, o erro "Could not include font
//#10#//definition file" (Não foi possível incluir o arquivo de definição de fonte) é gerado.
//#15#//addfont($family,$style='',$file='')
//#20#//family       : Família da fonte. O nome pode ser escolhido arbitrariamente. Se for um nome de família padrão ele
//#20#//               irá sobrepor a fonte correspondente. 
//#20#//style        : Estilo da fonte. Os valores possíveis são (maiúsculas e minúsculas são diferenciadas):
//#20#//                  - empty string: regular
//#20#//		  - B: bold
//#20#//		  - I: italic
//#20#//	          - BI or IB: bold italic 
//#20#//               O valor padrão é regular. 
//#20#//file         : O arquivo de definição da fonte.
//#20#//               Por padrão, o nome é composto pelo nome da fonte e o estilo, em minúsculas sem espaços. 
{
	//Add a TrueType or Type1 font
	$family=strtolower($family);
	if($family=='arial')
		$family='helvetica';
	$style=strtoupper($style);
	if($style=='IB')
		$style='BI';
	if(isset($this->fonts[$family.$style]))
		$this->Error('Font already added: '.$family.' '.$style);
	if($file=='')
		$file=str_replace(' ','',$family).strtolower($style).'.php';
	if(defined('FPDF_FONTPATH'))
		$file=FPDF_FONTPATH.$file;
	include($file);
	if(!isset($name))
		$this->Error('Could not include font definition file');
	$i=count($this->fonts)+1;
	$this->fonts[$family.$style]=array('i'=>$i,'type'=>$type,'name'=>$name,'desc'=>$desc,'up'=>$up,'ut'=>$ut,'cw'=>$cw,'enc'=>$enc,'file'=>$file);
	if($diff)
	{
		//Search existing encodings
		$d=0;
		$nb=count($this->diffs);
		for($i=1;$i<=$nb;$i++)
			if($this->diffs[$i]==$diff)
			{
				$d=$i;
				break;
			}
		if($d==0)
		{
			$d=$nb+1;
			$this->diffs[$d]=$diff;
		}
		$this->fonts[$family.$style]['diff']=$d;
	}
	if($file)
	{
		if($type=='TrueType')
			$this->FontFiles[$file]=array('length1'=>$originalsize);
		else
			$this->FontFiles[$file]=array('length1'=>$size1,'length2'=>$size2);
	}
}

function SetFont($family,$style='',$size=0)
//#00#//setfont
//#10#//Define a fonte que será usada para imprimir os caracteres de texto. É obrigatória a chamada, ao menos   uma vez,
//#10#//deste método antes de imprimir o texto ou o documento resultante não será válido.
//#10#//A fonte pode ser uma padrão ou uma que foi adicionada  através do   método  |addfont|.  As  fontes  padrão  usam
//#10#//codificação Windows cp1252 (Europa ocidental).
//#10#//O método pode ser chamado antes que a primeira página esteja criada e a fonte será mantida  de uma  página  para
//#10#//outra. Se você quiser mudar o tamanho da fonte atual, é mais simples chamar |setfontsize|. 
//#15#//setfont($family,$style='',$size=0)
//#20#//family       : Família da  fonte. Pode   ser um  tanto  nome definido por AddFont() como uma das famílias padrão
//#20#//               (maiúsculas e minúsculas não são diferenciadas):
//#20#//                  - Courier (largura fixa)
//#20#//                  - Helvetica ou Arial (sinônimos; sans serif)
//#20#//                  - Times (serif)
//#20#//                  - Symbol (símbolos)
//#20#//                  - ZapfDingbats (símbolos)
//#20#//               Também é possível passar um texto vazio. Neste caso, a família corrente é mantida.
//#20#//style        : Estilo da fonte. Os valores possíveis são (maiúsculas e minúsculas são diferenciadas):
//#20#//                  - texto vazio: normal
//#20#//                  - B: negrito
//#20#//	          - I: itálico
//#20#//	          - U: sublinhado 
//#20#//size         : Tamanho da fonte em pontos.
//#20#//               O valor padrão é o tamanho atual. Se nenhum tamanho foi especificado desde o início do documento,
//#20#//               o valor usado é 12. 
//#99#//Nota: para as fontes padrão, os arquivos de definição das fontes devem estar acessíveis. Há três  possibilidades
//#99#//para isto:
//#99#//    - Eles estão no diretório atual (o mesmo aonde o script está rodando)
//#99#//    - Eles estão em um dos diretórios definidos pelo parâmetro include_path
//#99#//    - Eles estão no diretório definido pela constante FPDF_FONTPATH 
//#99#//Exemplo para o último caso (observe a barra no final):
//#99#//define('FPDF_FONTPATH','/home/www/font/');
//#99#//require('fpdf.php');
//#99#//Se o arquivo que  corresponde à  fonte solicitada não existir, o erro "Could not include font metric file"  (não
//#99#//foi possível incluir o arquivo de fonte) é gerado.
{
	//Select a font; size given in points
	global $fpdf_charwidths;

	$family=strtolower($family);
	if($family=='')
		$family=$this->FontFamily;
	if($family=='arial')
		$family='helvetica';
	elseif($family=='symbol' or $family=='zapfdingbats')
		$style='';
	$style=strtoupper($style);
	if(is_int(strpos($style,'U')))
	{
		$this->underline=true;
		$style=str_replace('U','',$style);
	}
	else
		$this->underline=false;
	if($style=='IB')
		$style='BI';
	if($size==0)
		$size=$this->FontSizePt;
	//Test if font is already selected
	if($this->FontFamily==$family and $this->FontStyle==$style and $this->FontSizePt==$size)
		return;
	//Test if used for the first time
	$fontkey=$family.$style;
	if(!isset($this->fonts[$fontkey]))
	{
		//Check if one of the standard fonts
		if(isset($this->CoreFonts[$fontkey]))
		{
			if(!isset($fpdf_charwidths[$fontkey]))
			{
				//Load metric file
				$file=$family;
				if($family=='times' or $family=='helvetica')
					$file.=strtolower($style);
				$file.='.php';
				if(defined('FPDF_FONTPATH'))
					$file=FPDF_FONTPATH.$file;
				include($file);
				if(!isset($fpdf_charwidths[$fontkey]))
					$this->Error('Could not include font metric file');
			}
			$i=count($this->fonts)+1;
			$this->fonts[$fontkey]=array('i'=>$i,'type'=>'core','name'=>$this->CoreFonts[$fontkey],'up'=>-100,'ut'=>50,'cw'=>$fpdf_charwidths[$fontkey]);
		}
		else
			$this->Error('Undefined font: '.$family.' '.$style);
	}
	//Select it
	$this->FontFamily=$family;
	$this->FontStyle=$style;
	$this->FontSizePt=$size;
	$this->FontSize=$size/$this->k;
	$this->CurrentFont=&$this->fonts[$fontkey];
	if($this->page>0)
		$this->_out(sprintf('BT /F%d %.2f Tf ET',$this->CurrentFont['i'],$this->FontSizePt));
}

function SetFontSize($size)
{
	//Set font size in points
	if($this->FontSizePt==$size)
		return;
	$this->FontSizePt=$size;
	$this->FontSize=$size/$this->k;
	if($this->page>0)
		$this->_out(sprintf('BT /F%d %.2f Tf ET',$this->CurrentFont['i'],$this->FontSizePt));
}

function AddLink()
{
	//Create a new internal link
	$n=count($this->links)+1;
	$this->links[$n]=array(0,0);
	return $n;
}

function SetLink($link,$y=0,$page=-1)
{
	//Set destination of internal link
	if($y==-1)
		$y=$this->y;
	if($page==-1)
		$page=$this->page;
	$this->links[$link]=array($page,$y);
}

function Link($x,$y,$w,$h,$link)
{
	//Put a link on the page
	$this->PageLinks[$this->page][]=array($x*$this->k,$this->hPt-$y*$this->k,$w*$this->k,$h*$this->k,$link);
}

function Text($x,$y,$txt)
//#00#//text
//#10#//Imprime um texto. O ponto de origem é a esquerda do primeiro caracter, na linha de  base.  Este  método  permite
//#10#//colocar com precisão um texto na página, mas é normalmente mais fácil usar |cell|, |multicell| ou write| que são
//#10#//os métodos padrões de impressão de texto.
//#15#//text($x,$y,$txt)
//#20#//x            : Abscissa da origem. 
//#20#//y            : Ordenada da origem. 
//#20#//txt          : Texto a ser impresso. 
{
	//Output a string
	$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
	$s=sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$txt);
	if($this->underline and $txt!='')
		$s.=' '.$this->_dounderline($x,$y,$txt);
	if($this->ColorFlag)
		$s='q '.$this->TextColor.' '.$s.' Q';
	$this->_out($s);
}

function AcceptPageBreak()
{
	//Accept automatic page break or not
	return $this->AutoPageBreak;
}

function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='',$preenc='')
//#00#//cell
//#10#//Imprime uma célula (área retangular) com bordas opcionais, cor de fundo e  texto. O  canto  superior-esquerdo da
//#10#//célula corresponde à posição atual. O texto pode ser alinhado ou  centralizado.  Depois de  chamada,   a posição
//#10#//atual se move para a direita ou para a linha seguinte. É possível pôr um link no texto.
//#10#//Se a quebra de página automática está habilitada e a pilha for além do limite,  uma  quebra  de  página é  feita
//#10#//antes da impressão.
//#15#//cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
//#20#//w            : Largura da célula. Se 0, a célula se extende até a margem direita. 
//#20#//h            : Altura da célula. Valor padrão: 0. 
//#20#//txt          : Texto a ser impresso. Valor padrão: texto vazio. 
//#20#//border       : Indica se as bordas devem ser desenhadas em volta da célula. O valor deve ser um número:
//#20#//                  - 0: sem borda
//#20#//                  - 1: com borda 
//#20#//	       ou um texto contendo alguns ou todos os seguintes caracteres (em qualquer ordem):
//#20#//	          - L: esquerda
//#20#//		  - T: acima
//#20#// 	          - R: direita
//#20#//		  - B: abaixo 
//#20#//               Valor padrão: 0. 
//#20#//ln           : Indica onde a posição corrente deve ficar depois que a função for chamada. Os  valores  possíveis
//#20#//               são:
//#20#//                  - 0: a direita
//#20#//                  - 1: no início da próxima linha
//#20#//		  - 2: abaixo 
//#20#//               Usar o valor 1 é equivalente a usar 0 e chamar a função Ln() logo após. Valor padrão: 0. 
//#20#//align        : Permite centralizar ou alinhar o texto. Os valores possíveis são:
//#20#//                  - L ou um texto vazio: alinhado à esquerda (valor padrão)
//#20#//	          - C: centralizado
//#20#//		  - R: alinhado à direita 
//#20#//fill         : Indica se o fundo da célula deve ser preenchido (1) ou transparente (0). Valor padrão: 0. 
//#20#//link         : URL ou identificador retornado por |addlink|. 
//#20#//preenc       : indica se a célula terá preenchimento a esquerda.
//#20#//	       Ex.: $pdf->Cell(20,10,'Title',1,1,'C','','.')
//#20#//               preenche com (.) a direita do 'Title' até alcançar o tamanho da célula (20).
//#99#//Exemplo:
//#99#//  - Escolhe a fonte
//#99#//      $pdf->SetFont('Arial','B',16);
//#99#//  - Move para 8 cm a direita
//#99#//      $pdf->Cell(80);
//#99#//  - Texto centralizado em uma célula de 20*10 mm com borda e quebra de linha
//#99#//  -   $pdf->Cell(20,10,'Title',1,1,'C');
{
	//Output a cell
	$k=$this->k;
	if($this->y+$h>$this->PageBreakTrigger and !$this->InFooter and $this->AcceptPageBreak())
	{
		$x=$this->x;
		$ws=$this->ws;
		if($ws>0)
		{
			$this->ws=0;
			$this->_out('0 Tw');
		}
		$this->AddPage($this->CurOrientation);
		$this->x=$x;
		if($ws>0)
		{
			$this->ws=$ws;
			$this->_out(sprintf('%.3f Tw',$ws*$k));
		}
	}
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$s='';
	if($fill==1 or $border==1)
	{
		if($fill==1)
			$op=($border==1) ? 'B' : 'f';
		else
			$op='S';
		$s=sprintf('%.2f %.2f %.2f %.2f re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
	}
	if(is_string($border))
	{
		$x=$this->x;
		$y=$this->y;
		if(is_int(strpos($border,'L')))
			$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
		if(is_int(strpos($border,'T')))
			$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
		if(is_int(strpos($border,'R')))
			$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		if(is_int(strpos($border,'B')))
			$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
	}
	if($txt!='')
	{
		if($align=='R')
			$dx=$w-$this->cMargin-$this->GetStringWidth($txt);
		elseif($align=='C')
			$dx=($w-$this->GetStringWidth($txt))/2;
		else
			$dx=$this->cMargin;
		$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
		$xdots='';
                if($preenc != ''){
	  
                   $ww    = $this->GetStringWidth($txt);
                   if ($ww < $w ){
                       $quant   = ($w-$ww)/$this->GetStringWidth($preenc);
                       $xdots = str_repeat($preenc,$quant);
                   }else{
                       $xdots = '';
                   }
                 
		}
		$txt = $txt.$xdots;

		if($this->ColorFlag)
			$s.='q '.$this->TextColor.' ';
		$s.=sprintf('BT %.2f %.2f Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt);
		if($this->underline)
			$s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
		if($this->ColorFlag)
			$s.=' Q';
		if($link)
			$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
	}
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

function MultiCell($w,$h,$txt,$border=0,$align='J',$fill=0,$indent=0)
//#00#//multicell
//#10#//Este método permite imprimir um texto com quebras de linha. Podem ser automática (assim que  o  texto  alcança a
//#10#//margem direita da célula) ou explícita (através do caracter \n). Serão  geradas  tantas  células  quantas  forem
//#10#//necessárias, uma abaixo da outra.
//#10#//O texto pode ser alinhado, centralizado ou justificado. O bloco de células podem ter borda e um fundo colorido.
//#15#//multicell($w,$h,$txt,$border=0,$align='J',$fill=0,$indent=0)
//#20#//w            : Largura das células. Se 0, então serão extendidas até a margem direita da página. 
//#20#//h            : Altura das células. 
//#20#//txt          : Texto a ser impresso. 
//#20#//border       : Indica se as bordas devem ser desenhadas ao redor do bloco de células. O valor pode ser um número:
//#20#//                  - 0: sem borda
//#20#//		  - 1: com borda 
//#20#//               ou um texto contendo alguns ou todos os seguintes caracteres (em qualquer ordem):
//#20#//                  - L: esquerda
//#20#//		  - T: acima
//#20#//		  - R: direita
//#20#//                  - B: abaixo 
//#20#//               Valor padrão: 0. 
//#20#//align        : Estabelece o alinhamento do texto. Os valores possíveis são:
//#20#//                  - L: alinhado à esquerda
//#20#//                  - C: centralizado
//#20#//		  - R: alinhado à direita
//#20#//		  - J: justificado (valor padrão) 
//#20#//fill         : Indica se o fundo das células deve ser colorido (1) ou transparente (0). Valor padrão: 0. 
//#20#//indent       : Opção de paragrafo, indicando quantos espaçoes a linha começará a ser impressa a partir da margem. 
{
    //Output text with automatic or explicit line breaks
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;

    $wFirst = $w-$indent;
    $wOther = $w;

    $wmaxFirst=($wFirst-2*$this->cMargin)*1000/$this->FontSize;
    $wmaxOther=($wOther-2*$this->cMargin)*1000/$this->FontSize;

    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $b=0;
    if($border)
    {
        if($border==1)
        {
            $border='LTRB';
            $b='LRT';
            $b2='LR';
        }
        else
        {
            $b2='';
            if(is_int(strpos($border,'L')))
                $b2.='L';
            if(is_int(strpos($border,'R')))
                $b2.='R';
            $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
        }
    }
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $ns=0;
    $nl=1;
    $first=true;
    while($i<$nb)
    {
        //Get next character
        $c=$s[$i];
        if($c=="\n")
        {
            //Explicit line break
            if($this->ws>0)
            {
                $this->ws=0;
                $this->_out('0 Tw');
            }
            //caso nãterminea linhas e tenha uma quebra \n ou \r... bY Iuri
            //andrei Guntchnihh em 14/13/2006
            $SaveX = $this->x;
            if ($first and $indent > 0){
               $this->SetX($this->x + $indent);
               $first=false;
            }
            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
            $this->SetX($SaveX);
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $ns=0;
            $nl++;
            $first = false;
            if($border and $nl==2)
                $b=$b2;
            continue;

        }
        if($c==' ')
        {
            $sep=$i;
            $ls=$l;
            $ns++;
        }
        $l+=$cw[$c];

        if ($first)
        {
            $wmax = $wmaxFirst;
            $w = $wFirst;
        }
        else
        {
            $wmax = $wmaxOther;
            $w = $wOther;
        }

        if($l>$wmax)
        {
            //Automatic line break
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
                if($this->ws>0)
                {
                    $this->ws=0;
                    $this->_out('0 Tw');
                }
                $SaveX = $this->x;
                if ($first and $indent > 0)
                {

                    $this->SetX($this->x + $indent);
                    $first=false;

                }
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                $this->SetX($SaveX);
            }
            else
            {
                if($align=='J')
                {
                    $this->ws=($ns>1) ?
($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                    $this->_out(sprintf('%.3f Tw',$this->ws*$this->k));
                }
                $SaveX = $this->x;
                if ($first and $indent >0)
                {

                    $this->SetX($this->x + $indent);
                    $first=false;

                }
                $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                    $this->SetX($SaveX);
                $i=$sep+1;
            }
            $sep=-1;
            $j=$i;
            $l=0;
            $ns=0;
            $nl++;
            if($border and $nl==2)
                $b=$b2;
        }
        else
            $i++;
    }
    //Last chunk
    if($this->ws>0)
    {
        $this->ws=0;
        $this->_out('0 Tw');
    }
    if($border and is_int(strpos($border,'B')))
        $b.='B';

    $SaveX = $this->x;
    //se nãtem \n ou \r bY Iuri andrei Guntchnihh em 14/13/2006
    if ($first and $indent >0){
       $this->SetX($this->x + $indent);
       $first=false;

    }
    $this->Cell($w,$h,substr($s,$j,$i),$b,2,$align,$fill);
    $this->x=$this->lMargin;

}






















function Write($h,$txt,$link='')
//#00#//write
//#10#//Este método imprime um texto a partir da posição atual. Quando a margem direita é atingida (ou o caracter  \n  é
//#10#//encontrado) uma quebra de linha ocorre e o texto continua a partir da margem esquerda. Quando o método finalizar,
//#10#//a posição atual será imediatamente à esquerda do final do texto.
//#10#//É possível colocar um link no texto.
//#15#//write($h,$txt,$link='')
//#20#//h            : Altura da linha. 
//#20#//txt          : Texto a ser impresso. 
//#20#//link         : URL ou identificador retornado por AddLink(). 
//#99#//Exemplo :
//#20#//  - Iniciando com fonte normal
//#20#//      $pdf->SetFont('Arial','',14);
//#20#//      $pdf->Write(5,'Visit ');
//#20#//  - Colocando um link azul sublinhado
//#20#//      $pdf->SetTextColor(0,0,255);
//#20#//      $pdf->SetFont('','U');
//#20#//      $pdf->Write(5,'www.fpdf.org','http://www.fpdf.org');
{
	//Output text in flowing mode
	$cw=&$this->CurrentFont['cw'];
	$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		//Get next character
		$c=$s{$i};
		if($c=="\n")
		{
			//Explicit line break
			$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			if($nl==1)
			{
				$this->x=$this->lMargin;
				$w=$this->w-$this->rMargin-$this->x;
				$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			}
			$nl++;
			continue;
		}
		if($c==' ')
		{
			$sep=$i;
			$ls=$l;
		}
		$l+=$cw[$c];
		if($l>$wmax)
		{
			//Automatic line break
			if($sep==-1)
			{
				if($this->x>$this->lMargin)
				{
					//Move to next line
					$this->x=$this->lMargin;
					$this->y+=$h;
					$w=$this->w-$this->rMargin-$this->x;
					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
					$i++;
					$nl++;
					continue;
				}
				if($i==$j)
					$i++;
				$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
			}
			else
			{
				$this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
				$i=$sep+1;
			}
			$sep=-1;
			$j=$i;
			$l=0;
			if($nl==1)
			{
				$this->x=$this->lMargin;
				$w=$this->w-$this->rMargin-$this->x;
				$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			}
			$nl++;
		}
		else
			$i++;
	}
	//Last chunk
	if($i!=$j)
		$this->Cell($l/1000*$this->FontSize,$h,substr($s,$j,$i),0,0,'',0,$link);
}

function Image($file,$x,$y,$w,$h=0,$type='',$link='')
//#00#//image
//#10#//Imprime uma imagem na página. O canto superior-esquerdo e pelo menos uma das dimensões devem ser  especificadas;
//#10#//a altura ou a largura podem ser calculadas automaticamente com o objetivo de manter as proporções da imagem.
//#10#//Os formatos suportados são JPEG e PNG.
//#10#//Para JPEG, todos os tipos são permitidos:
//#10#//                  - tons de cinza
//#10#//                  - true colors (24 bits)
//#10#//                  - CMYK (32 bits) 
//#10#//Para PNG, são permitidos:
//#10#//                  - tons de cinza em pelo menos 8 bits (256 níveis)
//#10#//                  - cores indexadas
//#10#//                  - true colors (24 bits) 
//#10#//mas não são suportados:
//#10#//                  - Interlacing
//#10#//                  - Alpha channel 
//#10#//Se uma cor transparente for definida, ela vai ser levada em conta (mas só será  interpretada  pelo  Acrobat  4 e
//#10#//superiores).
//#10#//O formato pode ser especificado explicitamente ou inferido pela extensão do arquivo.
//#10#//É possível colocar um link na imagem.
//#10#//Nota: se uma imagem é usada muitas vezes, só uma cópia será embutida no arquivo.
//#15#//image($file,$x,$y,$w,$h=0,$type='',$link='')
//#20#//file         : Nome do arquivo que contém a imagem. 
//#20#//x            : Abscissa do canto superior-esquerdo. 
//#20#//y            : Ordenada do canto superior-esquerdo. 
//#20#//w            : Largura da imagem na página. Se for igual a zero, ela será automaticamente calculada para  manter
//#20#//               as proporções originais. 
//#20#//h            : Altura da  imagem  na  página.  Se não for especificada ou igual a zero, ela será automaticamente
//#20#//               calculada para manter as proporções originais. 
//#20#//type         : Formato da imagem.  Os valores possíveis são (diferenciando maiúsculas e minúsculas) : JPG, JPEG,
//#20#//               PNG. Se não for informado, o tipo será inferido pela extensão do arquivo. 
//#20#//link         : URL ou identificador retornado por AddLink(). 
{
	//Put an image on the page
	if(!isset($this->images[$file]))
	{
		//First use of image, get info
		if($type=='')
		{
			$pos=strrpos($file,'.');
			if(!$pos)
				$this->Error('Image file has no extension and no type was specified: '.$file);
			$type=substr($file,$pos+1);
		}
		$type=strtolower($type);
		$mqr=get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		if($type=='jpg' or $type=='jpeg')
			$info=$this->_parsejpg($file);
		elseif($type=='png')
			$info=$this->_parsepng($file);
		else
			$this->Error('Unsupported image file type: '.$type);
		set_magic_quotes_runtime($mqr);
		$info['i']=count($this->images)+1;
		$this->images[$file]=$info;
	}
	else
		$info=$this->images[$file];
	//Automatic width or height calculation
	if($w==0)
		$w=$h*$info['w']/$info['h'];
	if($h==0)
		$h=$w*$info['h']/$info['w'];
	$this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
	if($link)
		$this->Link($x,$y,$w,$h,$link);
}

function Ln($h='')
//#00#//ln
//#10#//Faz uma quebra de linha. A abscissa corrente volta para a margem esquerda e a ordenada é somada ao valor passado
//#10#//como parâmetro.
//#15#//ln($h='')
//#20#//h            : A altura da quebra.
//#20#//               Por padrão, o valor é igual a altura da última célula impressa. 
{
	//Line feed; default value is last cell height
	$this->x=$this->lMargin;
	if(is_string($h))
		$this->y+=$this->lasth;
	else
		$this->y+=$h;
}

function GetX()
//#00#//getx
//#10#//Retorna a abscissa da posição corrente.
{
	//Get x position
	return $this->x;
}

function SetX($x)
//#00#//setx
//#10#//Define a abscissa da posição corrente. Se o valor passado for negativo, ele será relativo à  margem  direita  da
//#10#//página. a abscissa da posição corrente.
//#15#//setX($x)
//#20#//x            : O valor da abscissa. 
{
	//Set x position
	if($x>=0)
		$this->x=$x;
	else
		$this->x=$this->w+$x;
}

function GetY()
//#00#//gety
//#10#//Retorna a ordenada da posição corrente.
{
	//Get y position
	return $this->y;
}

function SetY($y)
//#00#//sety
//#10#//Move a abscissa atual de volta para margem esquerda e define a ordenada. Se o valor passado  for  negativo,  ele
//#10#//será relativo a margem inferior da página.
//#15#//sety($x)
//#20#//x            : O valor da ordenada. 
{
	//Set y position and reset x
	$this->x=$this->lMargin;
	if($y>=0)
		$this->y=$y;
	else
		$this->y=$this->h+$y;
}

function SetXY($x,$y)
//#00#//setxy
//#10#//Define a abscissa e a ordenada da posição atual. Se os valores passados forem negativos, eles  serão  relativos,
//#10#//respectivamente, as magens direita e inferior da página.
//#15#//setxy($x,$y)
//#20#//x            : O valor da abscissa. 
//#20#//y            : O valor da ordenada. 
{
	//Set x and y positions
	$this->SetY($y);
	$this->SetX($x);
}

function GeraArquivoTemp(){
  return "tmp/rp".rand(1,10000)."_".time().".pdf";
}

function Output($file='',$download=false,$mostrar=false)
//#00#//output
//#10#//Salva um documento PDF em um arquivo local ou envia-o para o browser. Neste último caso, o  plug-in  será  usado
//#10#//(se instalado) ou um download (caixa de diálogo "Salvar como") será apresentada.
//#10#//O método primeiro chama |close|, se necessário para terminar o documento.
//#15#//output($file='',$download=false)
//#20#//file         : O nome do arquivo. Se vazio ou não informado, o documento será enviado ao browser para que ele  o
//#20#//               use com o plug-in (se instalado). 
//#20#//download     : Se file for informado, indica que ele deve ser salvo localmente  (false) ou  mostrar a  caixa  de
//#20#//               diálogo "Salvar como" no browser. Valor padrão: false.

{
    if($file=='')
      $file = $this->GeraArquivoTemp();
  
	//Output PDF to file or browser
	global $HTTP_ENV_VARS;

	if($this->state<3)
		$this->Close();
	if($file=='')
	{
		//Send to browser
		Header('Content-Type: application/pdf');
                header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");              // Date in the past
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modified
                header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");                                    // HTTP/1.0
                header("Cache-control: private");                          
		

		if(headers_sent())
		    $this->Error('Some data has already been output to browser, can\'t send PDF file');
		Header('Content-Length: '.strlen($this->buffer));
		echo $this->buffer;
		
	}
	else
	{
          if($download)
	  {

           if(isset($HTTP_ENV_VARS['HTTP_USER_AGENT']) and strpos($HTTP_ENV_VARS['HTTP_USER_AGENT'],'MSIE 5.5'))
             Header('Content-Type: application/dummy');
           else
             Header('Content-Type: application/octet-stream');
           if(headers_sent())
             $this->Error('Some data has already been output to browser, can\'t send PDF file');
             Header('Content-Length: '.strlen($this->buffer));
             Header('Content-disposition: attachment; filename='.$file);
			 echo $this->buffer;
	  }
	  else
	  {
	  	
	  	    ////////// NÃO RETIRAR ESSE IF SEM FALAR COM MARLON
	  	    ////////// NECESSÁRIO PARA PROGRAMA DO MÓDULO PESSOAL
	  	    ////////// geração de arquivos BB
	  	    if(1==2 && $mostrar == false){
			  header('Content-Type: application/pdf');
			  header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");              // Date in the past
			  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modified
			  header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
			  header("Cache-Control: post-check=0, pre-check=0", false);
			  header("Pragma: no-cache");                                    // HTTP/1.0
			  header("Cache-control: private");
			  echo $this->buffer;
	  	    }
			
			
			//Save file locally
			$f=fopen($file,'wb');
			if(!$f)
				$this->Error('Unable to create output file: '.$file);
			fwrite($f,$this->buffer,strlen($this->buffer));
			fclose($f);
echo "<script>location.href='$file'</script>";


		}
	}
}

/****************************************************************************
*                                                                           *
*                              Private methods                              *
*                                                                           *
****************************************************************************/
function _begindoc()
{
	//Start document
	$this->state=1;
	$this->_out('%PDF-1.3');
}

function _putpages()
{
	$nb=$this->page;
	if(!empty($this->AliasNbPages))
	{
		//Replace number of pages
		for($n=1;$n<=$nb;$n++)
			$this->pages[$n]=str_replace($this->AliasNbPages,$nb,$this->pages[$n]);
	}
	if($this->DefOrientation=='P')
	{
		$wPt=$this->fwPt;
		$hPt=$this->fhPt;
	}
	else
	{
		$wPt=$this->fhPt;
		$hPt=$this->fwPt;
	}
	$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
	for($n=1;$n<=$nb;$n++)
	{
		//Page
		$this->_newobj();
		$this->_out('<</Type /Page');
		$this->_out('/Parent 1 0 R');
		if(isset($this->OrientationChanges[$n]))
			$this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$hPt,$wPt));
		$this->_out('/Resources 2 0 R');
		if(isset($this->PageLinks[$n]))
		{
			//Links
			$annots='/Annots [';
			foreach($this->PageLinks[$n] as $pl)
			{
				$rect=sprintf('%.2f %.2f %.2f %.2f',$pl[0],$pl[1],$pl[0]+$pl[2],$pl[1]-$pl[3]);
				$annots.='<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
				if(is_string($pl[4]))
					$annots.='/A <</S /URI /URI '.$this->_textstring($pl[4]).'>>>>';
				else
				{
					$l=$this->links[$pl[4]];
					$h=isset($this->OrientationChanges[$l[0]]) ? $wPt : $hPt;
					$annots.=sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]>>',1+2*$l[0],$h-$l[1]*$this->k);
				}
			}
			$this->_out($annots.']');
		}
		$this->_out('/Contents '.($this->n+1).' 0 R>>');
		$this->_out('endobj');
		//Page content
		$p=($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
		$this->_newobj();
		$this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
		$this->_putstream($p);
		$this->_out('endobj');
	}
	//Pages root
	$this->offsets[1]=strlen($this->buffer);
	$this->_out('1 0 obj');
	$this->_out('<</Type /Pages');
	$kids='/Kids [';
	for($i=0;$i<$nb;$i++)
		$kids.=(3+2*$i).' 0 R ';
	$this->_out($kids.']');
	$this->_out('/Count '.$nb);
	$this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$wPt,$hPt));
	$this->_out('>>');
	$this->_out('endobj');
}

function _putfonts()
{
	$nf=$this->n;
	foreach($this->diffs as $diff)
	{
		//Encodings
		$this->_newobj();
		$this->_out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
		$this->_out('endobj');
	}
	$mqr=get_magic_quotes_runtime();
	set_magic_quotes_runtime(0);
	foreach($this->FontFiles as $file=>$info)
	{
		//Font file embedding
		$this->_newobj();
		$this->FontFiles[$file]['n']=$this->n;
		if(defined('FPDF_FONTPATH'))
			$file=FPDF_FONTPATH.$file;
		$size=filesize($file);
		if(!$size)
			$this->Error('Font file not found');
		$this->_out('<</Length '.$size);
		if(substr($file,-2)=='.z')
			$this->_out('/Filter /FlateDecode');
		$this->_out('/Length1 '.$info['length1']);
		if(isset($info['length2']))
			$this->_out('/Length2 '.$info['length2'].' /Length3 0');
		$this->_out('>>');
		$f=fopen($file,'rb');
		$this->_putstream(fread($f,$size));
		fclose($f);
		$this->_out('endobj');
	}
	set_magic_quotes_runtime($mqr);
	foreach($this->fonts as $k=>$font)
	{
		//Font objects
		$this->_newobj();
		$this->fonts[$k]['n']=$this->n;
		$name=$font['name'];
		$this->_out('<</Type /Font');
		$this->_out('/BaseFont /'.$name);
		if($font['type']=='core')
		{
			//Standard font
			$this->_out('/Subtype /Type1');
			if($name!='Symbol' and $name!='ZapfDingbats')
				$this->_out('/Encoding /WinAnsiEncoding');
		}
		else
		{
			//Additional font
			$this->_out('/Subtype /'.$font['type']);
			$this->_out('/FirstChar 32');
			$this->_out('/LastChar 255');
			$this->_out('/Widths '.($this->n+1).' 0 R');
			$this->_out('/FontDescriptor '.($this->n+2).' 0 R');
			if($font['enc'])
			{
				if(isset($font['diff']))
					$this->_out('/Encoding '.($nf+$font['diff']).' 0 R');
				else
					$this->_out('/Encoding /WinAnsiEncoding');
			}
		}
		$this->_out('>>');
		$this->_out('endobj');
		if($font['type']!='core')
		{
			//Widths
			$this->_newobj();
			$cw=&$font['cw'];
			$s='[';
			for($i=32;$i<=255;$i++)
				$s.=$cw[chr($i)].' ';
			$this->_out($s.']');
			$this->_out('endobj');
			//Descriptor
			$this->_newobj();
			$s='<</Type /FontDescriptor /FontName /'.$name;
			foreach($font['desc'] as $k=>$v)
				$s.=' /'.$k.' '.$v;
			$file=$font['file'];
			if($file)
				$s.=' /FontFile'.($font['type']=='Type1' ? '' : '2').' '.$this->FontFiles[$file]['n'].' 0 R';
			$this->_out($s.'>>');
			$this->_out('endobj');
		}
	}
}

function _putimages()
{
	$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
	foreach($this->images as $file=>$info)
	{
		$this->_newobj();
		$this->images[$file]['n']=$this->n;
		$this->_out('<</Type /XObject');
		$this->_out('/Subtype /Image');
		$this->_out('/Width '.$info['w']);
		$this->_out('/Height '.$info['h']);
		if($info['cs']=='Indexed')
			$this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
		else
		{
			$this->_out('/ColorSpace /'.$info['cs']);
			if($info['cs']=='DeviceCMYK')
				$this->_out('/Decode [1 0 1 0 1 0 1 0]');
		}
		$this->_out('/BitsPerComponent '.$info['bpc']);
		$this->_out('/Filter /'.$info['f']);
		if(isset($info['parms']))
			$this->_out($info['parms']);
		if(isset($info['trns']) and is_array($info['trns']))
		{
			$trns='';
			for($i=0;$i<count($info['trns']);$i++)
				$trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
			$this->_out('/Mask ['.$trns.']');
		}
		$this->_out('/Length '.strlen($info['data']).'>>');
		$this->_putstream($info['data']);
		$this->_out('endobj');
		//Palette
		if($info['cs']=='Indexed')
		{
			$this->_newobj();
			$pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
			$this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
			$this->_putstream($pal);
			$this->_out('endobj');
		}
	}
}

function _putresources()
{
	$this->_putfonts();
	$this->_putimages();
	//Resource dictionary
	$this->offsets[2]=strlen($this->buffer);
	$this->_out('2 0 obj');
	$this->_out('<</ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
	$this->_out('/Font <<');
	foreach($this->fonts as $font)
		$this->_out('/F'.$font['i'].' '.$font['n'].' 0 R');
	$this->_out('>>');
	if(count($this->images))
	{
		$this->_out('/XObject <<');
		foreach($this->images as $image)
			$this->_out('/I'.$image['i'].' '.$image['n'].' 0 R');
		$this->_out('>>');
	}
	$this->_out('>>');
	$this->_out('endobj');
}

function _putinfo()
{
	$this->_out('/Producer '.$this->_textstring('FPDF '.FPDF_VERSION));
	if(!empty($this->title))
		$this->_out('/Title '.$this->_textstring($this->title));
	if(!empty($this->subject))
		$this->_out('/Subject '.$this->_textstring($this->subject));
	if(!empty($this->author))
		$this->_out('/Author '.$this->_textstring($this->author));
	if(!empty($this->keywords))
		$this->_out('/Keywords '.$this->_textstring($this->keywords));
	if(!empty($this->creator))
		$this->_out('/Creator '.$this->_textstring($this->creator));
	$this->_out('/CreationDate '.$this->_textstring('D:'.date('YmdHis')));
}

function _putcatalog()
{
	$this->_out('/Type /Catalog');
	$this->_out('/Pages 1 0 R');
	if($this->ZoomMode=='fullpage')
		$this->_out('/OpenAction [3 0 R /Fit]');
	elseif($this->ZoomMode=='fullwidth')
		$this->_out('/OpenAction [3 0 R /FitH null]');
	elseif($this->ZoomMode=='real')
		$this->_out('/OpenAction [3 0 R /XYZ null null 1]');
	elseif(!is_string($this->ZoomMode))
		$this->_out('/OpenAction [3 0 R /XYZ null null '.($this->ZoomMode/100).']');
	if($this->LayoutMode=='single')
		$this->_out('/PageLayout /SinglePage');
	elseif($this->LayoutMode=='continuous')
		$this->_out('/PageLayout /OneColumn');
	elseif($this->LayoutMode=='two')
		$this->_out('/PageLayout /TwoColumnLeft');
}

function _puttrailer()
{
	$this->_out('/Size '.($this->n+1));
	$this->_out('/Root '.$this->n.' 0 R');
	$this->_out('/Info '.($this->n-1).' 0 R');
}

function _enddoc()
{
	$this->_putpages();
	$this->_putresources();
	//Info
	$this->_newobj();
	$this->_out('<<');
	$this->_putinfo();
	$this->_out('>>');
	$this->_out('endobj');
	//Catalog
	$this->_newobj();
	$this->_out('<<');
	$this->_putcatalog();
	$this->_out('>>');
	$this->_out('endobj');
	//Cross-ref
	$o=strlen($this->buffer);
	$this->_out('xref');
	$this->_out('0 '.($this->n+1));
	$this->_out('0000000000 65535 f ');
	for($i=1;$i<=$this->n;$i++)
		$this->_out(sprintf('%010d 00000 n ',$this->offsets[$i]));
	//Trailer
	$this->_out('trailer');
	$this->_out('<<');
	$this->_puttrailer();
	$this->_out('>>');
	$this->_out('startxref');
	$this->_out($o);
	$this->_out('%%EOF');
	$this->state=3;
}

function _beginpage($orientation)
{
	$this->page++;
	$this->pages[$this->page]='';
	$this->state=2;
	$this->x=$this->lMargin;
	$this->y=$this->tMargin;
	$this->lasth=0;
	$this->FontFamily='';
	//Page orientation
	if(!$orientation)
		$orientation=$this->DefOrientation;
	else
	{
		$orientation=strtoupper($orientation{0});
		if($orientation!=$this->DefOrientation)
			$this->OrientationChanges[$this->page]=true;
	}
	if($orientation!=$this->CurOrientation)
	{
		//Change orientation
		if($orientation=='P')
		{
			$this->wPt=$this->fwPt;
			$this->hPt=$this->fhPt;
			$this->w=$this->fw;
			$this->h=$this->fh;
		}
		else
		{
			$this->wPt=$this->fhPt;
			$this->hPt=$this->fwPt;
			$this->w=$this->fh;
			$this->h=$this->fw;
		}
		$this->PageBreakTrigger=$this->h-$this->bMargin;
		$this->CurOrientation=$orientation;
	}
}

function _endpage()
{
	//End of page contents
	$this->state=1;
}

function _newobj()
{
	//Begin a new object
	$this->n++;
	$this->offsets[$this->n]=strlen($this->buffer);
	$this->_out($this->n.' 0 obj');
}

function _dounderline($x,$y,$txt)
{
	//Underline text
	$up=$this->CurrentFont['up'];
	$ut=$this->CurrentFont['ut'];
	$w=$this->GetStringWidth($txt)+$this->ws*substr_count($txt,' ');
	return sprintf('%.2f %.2f %.2f %.2f re f',$x*$this->k,($this->h-($y-$up/1000*$this->FontSize))*$this->k,$w*$this->k,-$ut/1000*$this->FontSizePt);
}

function _parsejpg($file)
{
	//Extract info from a JPEG file
	$a=GetImageSize($file);
	if(!$a)
		$this->Error('Missing or incorrect image file: '.$file);
	if($a[2]!=2)
		$this->Error('Not a JPEG file: '.$file);
	if(!isset($a['channels']) or $a['channels']==3)
		$colspace='DeviceRGB';
	elseif($a['channels']==4)
		$colspace='DeviceCMYK';
	else
		$colspace='DeviceGray';
	$bpc=isset($a['bits']) ? $a['bits'] : 8;
	//Read whole file
	$f=fopen($file,'rb');
	$data=fread($f,filesize($file));
	fclose($f);
	return array('w'=>$a[0],'h'=>$a[1],'cs'=>$colspace,'bpc'=>$bpc,'f'=>'DCTDecode','data'=>$data);
}

function _parsepng($file)
{
	//Extract info from a PNG file
	$f=fopen($file,'rb');
	if(!$f)
		$this->Error('Can\'t open image file: '.$file);
	//Check signature
	if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
		$this->Error('Not a PNG file: '.$file);
	//Read header chunk
	fread($f,4);
	if(fread($f,4)!='IHDR')
		$this->Error('Incorrect PNG file: '.$file);
	$w=$this->_freadint($f);
	$h=$this->_freadint($f);
	$bpc=ord(fread($f,1));
	if($bpc>8)
		$this->Error('16-bit depth not supported: '.$file);
	$ct=ord(fread($f,1));
	if($ct==0)
		$colspace='DeviceGray';
	elseif($ct==2)
		$colspace='DeviceRGB';
	elseif($ct==3)
		$colspace='Indexed';
	else
		$this->Error('Alpha channel not supported: '.$file);
	if(ord(fread($f,1))!=0)
		$this->Error('Unknown compression method: '.$file);
	if(ord(fread($f,1))!=0)
		$this->Error('Unknown filter method: '.$file);
	if(ord(fread($f,1))!=0)
		$this->Error('Interlacing not supported: '.$file);
	fread($f,4);
	$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
	//Scan chunks looking for palette, transparency and image data
	$pal='';
	$trns='';
	$data='';
	do
	{
		$n=$this->_freadint($f);
		$type=fread($f,4);
		if($type=='PLTE')
		{
			//Read palette
			$pal=fread($f,$n);
			fread($f,4);
		}
		elseif($type=='tRNS')
		{
			//Read transparency info
			$t=fread($f,$n);
			if($ct==0)
				$trns=array(ord(substr($t,1,1)));
			elseif($ct==2)
				$trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
			else
			{
				$pos=strpos($t,chr(0));
				if(is_int($pos))
					$trns=array($pos);
			}
			fread($f,4);
		}
		elseif($type=='IDAT')
		{
			//Read image data block
			$data.=fread($f,$n);
			fread($f,4);
		}
		elseif($type=='IEND')
			break;
		else
			fread($f,$n+4);
	}
	while($n);
	if($colspace=='Indexed' and empty($pal))
		$this->Error('Missing palette in '.$file);
	fclose($f);
	return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
}

function _freadint($f)
{
	//Read a 4-byte integer from file
	$i=ord(fread($f,1))<<24;
	$i+=ord(fread($f,1))<<16;
	$i+=ord(fread($f,1))<<8;
	$i+=ord(fread($f,1));
	return $i;
}

function _textstring($s)
{
	//Format a text string
	return '('.$this->_escape($s).')';
}

function _escape($s)
{
	//Add \ before \, ( and )
	return str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$s)));
}

function _putstream($s)
{
	$this->_out('stream');
	$this->_out($s);
	$this->_out('endstream');
}

function _out($s)
{
	//Add a line to the document
	if($this->state==2)
		$this->pages[$this->page].=$s."\n";
	else
		$this->buffer.=$s."\n";
}
//End of class
  function int25($xp,$yp,$text,$alt,$larg) 
{
    $xpos = $xp;
    $text = strtoupper($text);
    $barcodeheight = $alt;                               // seta a altura das barras
    $barcodethinwidth = $larg;                             // seta a largura da barra estreita
    $barcodethickwidth = $barcodethinwidth * 2.2;          // seta a relacao barra larga/barra estreita
    // seta os codigos dos caracteres, sendo 0 para estreito e 1 para largo
    $codingmap  =  Array(
    "0"=>  "00110",  "1"=>  "10001",
    "2"=>  "01001",  "3"=>  "11000",
    "4"=>  "00101",  "5"=>  "10100",
    "6"=>  "01100",  "7"=>  "00011",
    "8"=>  "10010",  "9"=>  "01010");
    // se no. de caracteres impar adiciona 0 no comeco
    if(strlen($text)%2)
	  $text = "0".$text;

    $textlen = strlen($text);
    $barcodewidth  = ($textlen)*(3*$barcodethinwidth + 2*$barcodethickwidth)+($textlen)*(2.5)+(7*$barcodethinwidth + $barcodethickwidth)+3;
    // imprime na imagem o codigo de inicio
    $elementwidth = $barcodethinwidth;
    for($i = 0;$i < 2;$i++) {
      //imagefilledrectangle($im, $xpos, 0, $xpos + $elementwidth - 1 , $barcodeheight, $black);
	  $this->Rect($xpos, $yp, $xpos + $elementwidth-$xpos, $barcodeheight,"F");
      $xpos += $elementwidth;
      $xpos += $barcodethinwidth;
	  //$elementwidth = $barcodethickwidth;
      //$xpos ++;
    }
    // imprime na imagem o codigo em si
    for($idx = 0;$idx < $textlen;$idx += 2)  {      // a impressao e feita 2 caracteres por vez
      $charimpar = substr($text,$idx,1);    // pega o caracter impar, que vai ser impresso em preto
      $charpar  =  substr($text,$idx+1,1);    // pega o caracter par, que vai ser impresso em branco
      // interlacamento
      for($baridx = 0;$baridx < 5;$baridx++)  {  // a cada bit do codigo dos caracteres
        // imprime a barra coresspondente ao bit do caractere impar (preto)
        $elementwidth = (substr($codingmap[$charimpar],$baridx,1)) ?  $barcodethickwidth : $barcodethinwidth;
        //imagefilledrectangle($im, $xpos,0, $xpos + $elementwidth - 1,$barcodeheight, $black);
	    $this->Rect($xpos, $yp, $xpos + $elementwidth-$xpos, $barcodeheight,"F");
        $xpos += $elementwidth;
        // deixa o espaco correspondente ao bit do caractere par (branco)
        $elementwidth = (substr($codingmap[$charpar],$baridx,1)) ?  $barcodethickwidth : $barcodethinwidth;
        $xpos += $elementwidth;
        //$xpos ++;
      }
    }
    // imprime o codigo de final
    $elementwidth = $barcodethickwidth;
    $this->Rect($xpos, $yp, $xpos + $elementwidth-$xpos, $barcodeheight,"F");
    $xpos += $elementwidth;
    $xpos += $barcodethinwidth;
    $elementwidth = $barcodethinwidth;
    $this->Rect($xpos, $yp, $xpos + $elementwidth-$xpos, $barcodeheight,"F");
  }

function TextWithDirection($x,$y,$txt,$direction='R')
{
    $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
    if ($direction=='R')
        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$txt);
    elseif ($direction=='L')
        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$txt);
    elseif ($direction=='U')
        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$txt);
    elseif ($direction=='D')
        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$txt);
    else
        $s=sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$txt);
    $this->_out($s);
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

    function SetDash($black=false,$white=false)
    {
        if($black and $white)
            $s=sprintf('[%.3f %.3f] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }
        function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
    {
            if($cw){
                    $d = $b;
                    $b = $o - $a;
                    $a = $o - $d;
            }else{
                    $b += $o;
                    $a += $o;
            }
            $a = ($a%360)+360;
            $b = ($b%360)+360;
            if ($a > $b)
                    $b +=360;
            $b = $b/360*2*M_PI;
            $a = $a/360*2*M_PI;
            $d = $b-$a;
            if ($d == 0 )
                    $d =2*M_PI;
            $k = $this->k;
            $hp = $this->h;
            if($style=='F')
                    $op='f';
            elseif($style=='FD' or $style=='DF')
                    $op='b';
            else
                    $op='s';
            if (sin($d/2))
                    $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
            //first put the center
            $this->_out(sprintf('%.2f %.2f m',($xc)*$k,($hp-$yc)*$k));
            //put the first point
            $this->_out(sprintf('%.2f %.2f l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
            //draw the arc
            if ($d < M_PI/2){
                    $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                                            $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                                            $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                                            $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                                            $xc+$r*cos($b),
                                            $yc-$r*sin($b)
                                            );
            }else{
                    $b = $a + $d/4;
                    $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
                    $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                                            $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                                            $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                                            $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                                            $xc+$r*cos($b),
                                            $yc-$r*sin($b)
                                            );
                    $a = $b;
                    $b = $a + $d/4;
                    $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                                            $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                                            $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                                            $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                                            $xc+$r*cos($b),
                                            $yc-$r*sin($b)
                                            );
                    $a = $b;
                    $b = $a + $d/4;
                    $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                                            $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                                            $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                                            $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                                            $xc+$r*cos($b),
                                            $yc-$r*sin($b)
                                            );
                   $a = $b;
                    $b = $a + $d/4;
                    $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                                            $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                                            $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                                            $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                                            $xc+$r*cos($b),
                                            $yc-$r*sin($b)
                                            );
            }
            //terminate drawing
            $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3 )
    {
            $h = $this->h;
            $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
                    $x1*$this->k,
                    ($h-$y1)*$this->k,
                    $x2*$this->k,
                    ($h-$y2)*$this->k,
                    $x3*$this->k,
                    ($h-$y3)*$this->k));
    }

    function PieChart($w, $h, $data, $format, $colors=null)  /// graficos tipo pizza
    {
            $this->SetFont('Courier', '', 10);
            $this->SetLegends($data,$format);

            $XPage = $this->GetX();
            $YPage = $this->GetY();
            $margin = 2;
            $hLegend = 5;
            $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
            $radius = floor($radius / 2);
            $XDiag = $XPage + $margin + $radius;
            $YDiag = $YPage + $margin + $radius;
            if($colors == null) {
                    for($i = 0;$i < $this->NbVal; $i++) {
                            $gray = $i * intval(255 / $this->NbVal);
                            $colors[$i] = array($gray,$gray,$gray);
                    }
            }

            //Sectors
            $this->SetLineWidth(0.2);
            $angleStart = 0;
            $angleEnd = 0;
            $i = 0;
            foreach($data as $val) {
                    $angle = floor(($val * 360) / doubleval($this->sum));
                    if ($angle != 0) {
                            $angleEnd = $angleStart + $angle;
                            $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
                            $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
                            $angleStart += $angle;
                    }
                    $i++;
            }
            if ($angleEnd != 360) {
                    $this->Sector($XDiag, $YDiag, $radius, $angleStart - $angle, 360);
            }

            //Legends
            $this->SetFont('Courier', '', 10);
            $x1 = $XPage + 2 * $radius + 4 * $margin;
            $x2 = $x1 + $hLegend + $margin;
            $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal*($hLegend + $margin)) / 2;
            for($i=0; $i<$this->NbVal; $i++) {
                    $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
                    $this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
                    $this->SetXY($x2,$y1);
                    $this->Cell(0,$hLegend,$this->legends[$i]);
                    $y1+=$hLegend + $margin;
            }
    }

    function BarDiagram($w, $h, $data, $format, $color=null, $maxVal=0, $nbDiv=4)  /// graficos tipo barra
    {
            $this->SetFont('Courier', '', 10);
            $this->SetLegends($data,$format);

            $XPage = $this->GetX();
            $YPage = $this->GetY();
            $margin = 2;
            $YDiag = $YPage + $margin;
            $hDiag = floor($h - $margin * 2);
            $XDiag = $XPage + $margin * 2 + $this->wLegend;
            $lDiag = floor($w - $margin * 3 - $this->wLegend);
            if($color == null)
                    $color=array(155,155,155);
            if ($maxVal == 0) {
                    $maxVal = max($data);
            }
            $valIndRepere = ceil($maxVal / $nbDiv);
            $maxVal = $valIndRepere * $nbDiv;
            $lRepere = floor($lDiag / $nbDiv);
            $lDiag = $lRepere * $nbDiv;
            $unit = $lDiag / $maxVal;
            $hBar = floor($hDiag / ($this->NbVal + 1));
            $hDiag = $hBar * ($this->NbVal + 1);
            $eBaton = floor($hBar * 80 / 100);

            $this->SetLineWidth(0.2);
            $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

            $this->SetFont('Courier', '', 10);
            
            $i=0;
            $xcor = 0;
            
            foreach($data as $val) {
                 if($xcor == 0 || count($color)==3 ){
                 	$xcor = 1;
                    if(count($color)<3){
                      $this->SetFillColor($color[0]);
                    }else{
                      $this->SetFillColor($color[0],$color[1],$color[2]);
                    }
                 }else{
                 	$xcor = 0;
                    if(count($color)<3){
                      $this->SetFillColor($color[1]);
                    }else{
                      $this->SetFillColor($color[3],$color[4],$color[5]);
                    }
                 }
                    //Bar
                    $xval = $XDiag;
                    $lval = (int)($val * $unit);
                    $yval = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
                    $hval = $eBaton;
                    $this->Rect($xval, $yval, $lval, $hval, 'DF');
                    //Legend
                    $this->SetXY(0, $yval);
                    $this->Cell($xval - $margin, $hval, $this->legends[$i],0,0,'R');
                    $i++;
            
            }

            //Scales
            for ($i = 0; $i <= $nbDiv; $i++) {
                    $xpos = $XDiag + $lRepere * $i;
                    $this->Line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
                    $val = $i * $valIndRepere;
                    $xpos = $XDiag + $lRepere * $i - $this->GetStringWidth($val) / 2;
                    $ypos = $YDiag + $hDiag - $margin;
                    $this->Text($xpos, $ypos, $val);
            }
    }

    function SetLegends($data, $format)  // legendas para os graficos
    {
            $this->legends=array();
            $this->wLegend=0;
            $this->sum=array_sum($data);
            $this->NbVal=count($data);
            foreach($data as $l=>$val)
            {
                    $p=sprintf('%.2f',$val/$this->sum*100).'%';
                    $legend=str_replace(array('%l','%v','%p'),array($l,$val,$p),$format);
                    $this->legends[]=$legend;
                    $this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
            }
    }



function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
 {
   $k = $this->k;
   $hp = $this->h;
   if($style=='F')
      $op='f';
   elseif($style=='FD' or $style=='DF')
      $op='B';
   else
      $op='S';
   $MyArc = 4/3 * (sqrt(2) - 1);
   $this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));

   $xc = $x+$w-$r;
   $yc = $y+$r;
   $this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));
   if (strpos($angle, '2')===false)
       $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k,($hp-$y)*$k ));
   else
       $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
   $xc = $x+$w-$r;
   $yc = $y+$h-$r;
   $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
   if (strpos($angle, '3')===false)
      $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-($y+$h))*$k));
   else
      $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
   $xc = $x+$r;
   $yc = $y+$h-$r;
   $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
   if (strpos($angle, '4')===false)
       $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-($y+$h))*$k));
   else
       $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
   $xc = $x+$r ;
   $yc = $y+$r;
   $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
   if (strpos($angle, '1')===false)
   {
      $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$y)*$k ));
      $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$y)*$k ));
   }else{
      $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
      $this->_out($op);
   }

 }


function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data,$altura=5,$borda=true,$espaco=5,$preenche=0,$naousaespaco=false )
{
 //Calculate the height of the row
  $nb=0;
  for($i=0;$i<count($data);$i++)
     $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
     $h=$espaco*$nb;
     //Issue a page break first if needed
     // Carlos >>  $this->CheckPageBreak($h); 
     //Draw the cells of the row
     $posinicial=$this->GetY();
     $posfinal=0;
     for($i=0;$i<count($data);$i++)
     {
       $w=$this->widths[$i];
       $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
       //Save the current position
       $x=$this->GetX();
       $y=$this->GetY();
       //Draw the border
       if($borda == true)
         $this->Rect($x,$y,$w,$h);
       //Print the text
       $this->MultiCell($w,$altura,$data[$i],0,$a,$preenche);
       //Put the position to the right of the cell
       if ($this->GetY() > $posfinal) {
         $posfinal=$this->GetY();
       }
       $this->SetXY($x+$w,$y);
     }

     // Adicionado novo parâmetro:
     // Parârametro: NAOUSAESPACO
     // Se $naousaespaco não for setado ao chamar a função ROW ou for setado com FALSE, o ln() continuará
     // usando a variável $h para pular para a próxima linha. Caso contrário, o ln() será a posição final
     // do maior multicell menos a posição em que este começou a ser impresso...
     if($naousaespaco==true){
       //Go to the next line
       $this->Ln($posfinal-$posinicial);
     }else{
       //Go to the next line
       $this->Ln($h);
     }
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
 }

//Handle silly IE contype request
}

if (isset($HTTP_ENV_VARS["HTTP_USER_AGENT"]) && $HTTP_ENV_VARS["HTTP_USER_AGENT"] == "contype") {
	Header('Content-Type: application/pdf');
	exit;
 }

//|XX|//
?>
