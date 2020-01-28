<?php
require_once(modification("fpdf151/scpdf.php"));

/**
 * Classe para emissao de documentos PDF, com a correcao do método multicell,
 * para criar as bordas no topo e fim de página, caso a exista borda no multilcell
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 *
 */
class FpdfMultiCellBorder extends scpdf {

  protected $lLines          = false;
  protected $lEnableFooter   = false;
  protected $lShowPageNumber = false;
  protected $lShowMenu       = false;
  protected $lExibeHeader    = false;
  protected $lExibeBrasao    = false;

  protected $sFunctionMulticellBreakPage = '';

  /**
   * Define uma funcao para ser executada apos uma chamada addPage implicito do multicell
   * @param string $sFunction nome da funcao
   */
  public function setMulticellBreakPageFunction($sFunction) {
    $this->sFunctionMulticellBreakPage = $sFunction;
  }
  /**
   * Permite a impressao de texto com quebras de linhas
   *
   * As quebras podem ser automáticas, (quando o texto chega na borda da celula) ou  explicitas (\n).
   * O método sempre cria uma nova linha.
   * O parametro $borderm pode ser 1 - Terá bordas em toda o mnulticell ou a combinacao dos caracteres:
   * 'T' = Borda no topo
   * 'B' = Borda no Fundo
   * 'L' = Borda na esquerda
   * 'R' = Borda na Direita
   * @param integer $w Tamanho da multicell
   * @param integer $h espaçamento entre linhas
   * @param string  $txt texto para ser impresso
   * @param mixed   $border bordas em torno do multicell
   * @param string  $align Alinhamento do Texto 'J', 'C', 'L', 'R'
   * @param integer $fill se o multicell vai possuir preenchimento de fundo
   * @param integer $indent tamanhodo Recuo de 1 linha
   * @see FPDF::MultiCell()
   */
  function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = 0, $indent = 0) {

    $sTopBorder = '';
    $cw         = &$this->CurrentFont['cw'];
    if ($w == 0) {
      $w = $this->w-$this->rMargin-$this->x;
    }
    $wFirst = $w-$indent;
    $wOther = $w;

    $wmaxFirst = ($wFirst - 2 * $this->cMargin) * 1000 / $this->FontSize;
    $wmaxOther = ($wOther - 2 * $this->cMargin) * 1000 / $this->FontSize;

    $s  = str_replace("\r",'',$txt);
    $nb = strlen($s);
    if ($nb>0 and $s[$nb-1]=="\n") {
      $nb--;
    }
    $b = 0;
    if($border) {

      if ($border == 1) {

        $border     = 'LTRB';
        $b          = 'LRT';
        $b2         = 'LR';
        $sTopBorder = 'TB';

      } else {

        if (strpos($border, "B") !== false) {
          $sTopBorder .= 'B';
        }
        if (strpos($border, "T") !== false) {
          $sTopBorder .= 'T';
        }
        $b2 = '';
        if (is_int(strpos($border,'L'))) {
          $b2 .= 'L';
        }
        if (is_int(strpos($border,'R'))) {
          $b2 .= 'R';
        }
        $b = is_int(strpos($border,'T')) ? $b2.'T' : $b2;
      }
    }
    $sep   = -1;
    $i     = 0;
    $j     = 0;
    $l     = 0;
    $ns    = 0;
    $nl    = 1;
    $first = true;
    while ($i < $nb) {

      //Get next character
      $c = $s[$i];
      if ($c == "\n") {
        //Explicit line break
        if ($this->ws > 0) {

          $this->ws = 0;
          $this->_out('0 Tw');
        }

        /**
         * caso nã termine a linha e tenha uma quebra \n ou \r
         */
        $SaveX = $this->x;
        if ($first and $indent > 0) {

          $this->SetX($this->x + $indent);
          $first = false;
        }
        $this->linecell($w, $h, substr($s, $j,$i-$j),$b,2,$align,$fill, $sTopBorder);
        $this->SetX($SaveX);
        $i++;
        $sep = -1;
        $j   = $i;
        $l   = 0;
        $ns  = 0;
        $nl++;
        $first = false;
        if ($border and $nl==2) {
          $b = $b2;
        }
        continue;
      }
      if ($c ==' ') {

        $sep = $i;
        $ls  = $l;
        $ns++;
      }
      $l += $cw[$c];

      if ($first) {

        $wmax = $wmaxFirst;
        $w    = $wFirst;
      } else {

        $wmax = $wmaxOther;
        $w    = $wOther;
      }

      if($l > $wmax) {
        //Automatic line break
        if ($sep == -1) {

          if ($i == $j) {
            $i++;
          }
          if ($this->ws > 0) {

            $this->ws = 0;
            $this->_out('0 Tw');
          }
          $SaveX = $this->x;
          if ($first && $indent > 0) {

            $this->SetX($this->x + $indent);
            $first = false;
          }
          $this->linecell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill, $sTopBorder);
          $this->SetX($SaveX);
        } else {

          if ($align == 'J')  {

            $this->ws = ($ns>1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
            $this->_out(sprintf('%.3f Tw',$this->ws*$this->k));
          }
          $SaveX = $this->x;
          if ($first && $indent > 0) {

            $this->SetX($this->x + $indent);
            $first = false;

          }
          $this->lineCell($w, $h, substr($s, $j, $sep - $j), $b, 2, $align, $fill, $sTopBorder);
          $this->SetX($SaveX);
          $i = $sep + 1;
        }
        $sep = -1;
        $j   = $i;
        $l   = 0;
        $ns  = 0;
        $nl++;
        if ($border && $nl == 2) {
          $b = $b2;
        }
      } else {
        $i++;
      }
    }
    //Last chunk
    if ($this->ws > 0) {

      $this->ws = 0;
      $this->_out('0 Tw');
    }

    if ($border && is_int(strpos($border,'B'))) {
      $b .= 'B';
    }

    $SaveX = $this->x;
    if ($first && $indent > 0) {

      $this->SetX($this->x + $indent);
      $first = false;

    }
    $this->lineCell($w, $h, substr($s, $j, $i), $b, 2, $align, $fill, $sTopBorder);
    $this->x = $this->lMargin;
  }

  /**
   * Escreve uma linha de texto método utilizado para escrever as linhas do multicell
   * @param integer $w Tamanho da linha
   * @param integer $h altura da linha
   * @param string  $txt texto para ser impresso
   * @param mixed   $border bordas em torno do multicell
   * @param integer $ln quebra linha apos escrevcer linha
   * @param string  $align Alinhamento do Texto 'J', 'C', 'L', 'R'
   * @param integer $fill se o multicell vai possuir preenchimento de fundo
   * @param string $sParentBorder controle do impressao de bordas apos quebra de página
   */
  protected function lineCell($w, $h = 0, $txt='',$border=0,$ln=0,$align='',$fill=0, $sParentBorder = '') {

    $k = $this->k;

    /**
     * Última Celula impressa na página
     */
    $lLastCellOfPage = false;

    /**
     * Borda do multicell necessita a impressao na parte de baixo
     */
    $lBottomBorder = strpos($sParentBorder, "B") !== false;

    /**
     * próxima celula deverá estar na página de baixo, marcamos essa celula com a última da página
     */
    if ($this->y + ($h * 2) > $this->PageBreakTrigger) {
      $lLastCellOfPage = true;
    }
    if ($this->y + $h > $this->PageBreakTrigger  && !$this->InFooter && $this->AcceptPageBreak()) {

      $lLastCellOfPage = false;
      $x               = $this->x;
      $ws              = $this->ws;
      if ($ws > 0) {

        $this->ws = 0;
        $this->_out('0 Tw');
      }
      if ($txt == "") {
        return;
      }

      $lTopBorder = strpos($sParentBorder, "T") !== false ? true:false;
      $this->AddPage($this->CurOrientation);
      if ($this->sFunctionMulticellBreakPage != "") {
        call_user_func($this->sFunctionMulticellBreakPage);
      }
      if ($lTopBorder) {
        $border .= "T";
      }
      $this->x = $x;
      if ($ws > 0) {

        $this->ws = $ws;
        $this->_out(sprintf('%.3f Tw',$ws*$k));
      }
    }
    if ($lLastCellOfPage && $lBottomBorder) {
      $border .= "B";
    }
    if ($w == 0) {
      $w = $this->w - $this->rMargin - $this->x;
    }
    $s = '';
    if ($fill == 1 || $border == 1) {

      if ($fill == 1) {
        $op = ($border == 1) ? 'B' : 'f';
      } else {
        $op = 'S';
      }
      $s = sprintf('%.2f %.2f %.2f %.2f re %s ', $this->x * $k, ($this->h -$this->y) * $k, $w * $k,- $h * $k, $op);
    }
    if (is_string($border)){
      $x = $this->x;
      $y = $this->y;
      if (is_int(strpos($border,'L'))) {
        $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h))*$k);
      }
      if(is_int(strpos($border,'T'))) {
        $s .= sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
      }
      if (is_int(strpos($border,'R'))) {
        $s .= sprintf('%.2f %.2f m %.2f %.2f l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
      }
      if(is_int(strpos($border,'B'))) {
        $s .= sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
      }
    }
    if ($txt!='') {
      if ($align=='R') {
        $dx = $w - $this->cMargin - $this->GetStringWidth($txt);
      } else if ($align == 'C') {
        $dx =($w - $this->GetStringWidth($txt)) / 2;
      } else {
        $dx = $this->cMargin;
      }
      $txt = str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));

      if ($this->ColorFlag) {
        $s .= 'q '.$this->TextColor.' ';
      }
      $s .= sprintf('BT %.2f %.2f Td (%s) Tj ET',($this->x+$dx)*$k,
                                                 ($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k, $txt);

      if ($this->underline) {
        $s .=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
      }
      if ($this->ColorFlag) {
        $s .=' Q';
      }
    }
    if ($s) {
      $this->_out($s);
    }
    $this->lasth = $h;
    if ($ln > 0) {

      //Go to next line
      $this->y += $h;
      if($ln == 1) {
        $this->x = $this->lMargin;
      }
    } else {
      $this->x += $w;
    }
  }

   /**
    *
    */
  public function footer() {

    global $conn;
    if (!$this->lEnableFooter) {
      return;
    }
    $this->line(($this->lMargin), $this->h - 10, $this->w - $this->rMargin, $this->h - 10);
    if ($this->lShowMenu) {

      $sSqlMenuAcess = " select trim(modulo.descricao)||'>'||trim(menu.descricao)||'>'||trim(item.descricao) as menu
                       from db_menu
                   	  inner join db_itensmenu as modulo on modulo.id_item = db_menu.modulo
                   	  inner join db_itensmenu as menu on menu.id_item = db_menu.id_item
                   	  inner join db_itensmenu as item on item.id_item = db_menu.id_item_filho
                   	  where id_item_filho = ".db_getsession("DB_itemmenu_acessado")."
                   	    and modulo = ".db_getsession("DB_modulo");

      $rsMenuAcess = db_query($conn,$sSqlMenuAcess);
      $sMenuAcess  = substr(pg_result($rsMenuAcess, 0, "menu"), 0, 50);

      //Position at 1.5 cm from bottom
      $this->SetFont('Arial','',5);
      $this->text(10,$this->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
      $this->SetFont('Arial','I',6);
      $this->SetY(-10);
      $nome           = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
      $nome           = substr($nome,strrpos($nome,"/")+1);
      $sSqlMenu       = "select nome as nomeusu from db_usuarios where id_usuario =".db_getsession("DB_id_usuario");
      $result_nomeusu = db_query($conn, $sSqlMenu);
      if (pg_numrows($result_nomeusu)>0){
        $nomeusu = pg_result($result_nomeusu,0,0);
      }
      if (isset($nomeusu)&&$nomeusu != "") {
         $emissor = $nomeusu;
      } else {
        $emissor = @$GLOBALS["DB_login"];
      }
      $sStringMenu  = $sMenuAcess. "  ". $nome.'   Emissor: '.substr(ucwords(strtolower($emissor)),0,30);
      $sStringMenu .= '  Exerc: '.db_getsession("DB_anousu");
      $sStringMenu .= 'Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s");
      //die($sStringMenu);
      $this->text(($this->lMargin) + 15 , $this->h - 6, $sStringMenu ,0,1,'R');
    }

    $this->showPageNumber();
  }

  public function showPageNumber() {

    if ($this->lShowPageNumber) {
      $sString = 'Pág '.$this->PageNo().'/{nb}';
      $this->text(($this->w-$this->rMargin) - $this->GetStringWidth($sString) + 1 , $this->h - 6, $sString ,0,1,'R');
    }
  }


  /**
   * Mostra o Footer na página
   * @param boolean $lShow
   */
  public function mostrarRodape($lShow) {
    $this->lEnableFooter = $lShow;
  }

  /**
   * Mostra total de páginas
   * @param boolean $lShow
   */
  public function mostrarTotalDePaginas($lShow) {
    $this->lShowPageNumber = $lShow;
  }

  /**
   * Mostra t
   * @param boolean $lShow
   */
  public function mostrarEmissor($lShow) {
    $this->lShowMenu = $lShow;
  }

  /**
   * Mostra o cabeçalho padrão do sistema
   * @param unknown $lShow
   */
  public function exibeHeader($lShow) {

    $this->lExibeHeader = $lShow;
  }

  /**
   * Define se o brasão deve ser exibido ou não
   * @param  boolean $lExibeBrasao
   */
  public function setExibeBrasao( $lExibeBrasao) {
    $this->lExibeBrasao = $lExibeBrasao;
  }

  function Header() {

    if (!$this->lExibeHeader) {
    	return false;
    }
    //#00#//header
    //#10#//Este método é usado gerar o cabeçalho da página. É chamado automaticamente por |addPage| e não
    //#10#//deve ser chamado diretamente pela aplicação. A implementação em FPDF está  vazia,  então  você
    //#10#//precisa criar uma subclasse dele para  sobrepor o  método  se  você  quiser  um  processamento
    //#10#//específico para o cabeçalho.
    //#15#//header()
    //#99#//Exemplo:
    //#99#//class PDF extends FPDF
    //#99#//{
    //#99#//  function Header()
    //#99#//  {
    //#99#//    Seleciona fonte Arial bold 15
    //#99#//      $this->SetFont('Arial','B',15);
    //#99#//    Move para a direita
    //#99#//      $this->Cell(80);
    //#99#//    Titulo dentro de uma caixa
    //#99#//      $this->Cell(30,10,'Title',1,0,'C');
    //#99#//    Quebra de linha
    //#99#//      $this->Ln(20);
    //#99#//  }
    //#99#//}

    global $conn;
    global $result;
    global $url;
    global $iEscola;
    //Dados da instituição

    //   echo ("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
    //   $dados = db_query("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));

    $dados = db_query($conn,"select nomeinst,trim(ender)||','||trim(cast(numero as text)) as ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
    $url = @pg_result($dados,0,"url");
    $this->SetXY(1,1);
    if ( $this->lExibeBrasao ) {
      $this->Image('imagens/files/'.pg_result($dados,0,"logo"),7,3,20);
    }
    if ($_SESSION["DB_modulo"] == 1100747) {
      if (!isset($iEscola)){
        $iEscola = 	db_getsession("DB_coddepto");
      }

      //$this->Cell(100,32,"",1);
      $dados1 = db_query($conn,"select ed18_c_nome,
                                   ed18_codigoreferencia,
                                   j14_nome,
                                   ed18_i_numero,
                                   j13_descr,
                                   ed261_c_nome,
                                   ed260_c_sigla,
                                   ed18_c_email,
                                   ed18_c_logo
                             from escola
                              inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro
                              inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua
                              inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo
                              inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf
                              inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic
                              left join ruascep on ruascep.j29_codigo = ruas.j14_codigo
                              left join logradcep on logradcep.j65_lograd = ruas.j14_codigo
                              left join ceplogradouros on ceplogradouros.cp06_codlogradouro = logradcep.j65_ceplog
                              left join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
                             where ed18_i_codigo = ".$iEscola);
      $nome = pg_result($dados,0,"nomeinst");

      $nomeescola        = pg_result($dados1,0,"ed18_c_nome");
      $iCodigoReferencia = pg_result($dados1,0,"ed18_codigoreferencia");

      if ( $iCodigoReferencia != null ) {
        $nomeescola = "{$iCodigoReferencia} - {$nomeescola}";
      }

      global $nomeinst;
      $nomeinst = pg_result($dados,0,"nomeinst");
      if(strlen($nome) > 42 || strlen($nomeescola) > 42)
        $TamFonteNome = 8;
      else
        $TamFonteNome = 9;
      if(trim(pg_result($dados1,0,"ed18_c_logo"))!=""){

        if ( $this->lExibeBrasao ) {
          $this->Image('imagens/'.trim(pg_result($dados1,0,"ed18_c_logo")), 105, 4, 20);
        }
      }
      $ruaescola = trim(pg_result($dados1,0,"j14_nome"));
      $numescola = trim(pg_result($dados1,0,"ed18_i_numero"));
      $bairroescola = trim(pg_result($dados1,0,"j13_descr"));
      $cidadeescola = trim(pg_result($dados1,0,"ed261_c_nome"));
      $estadoescola = trim(pg_result($dados1,0,"ed260_c_sigla"));
      $emailescola = trim(pg_result($dados1,0,"ed18_c_email"));
      $dados2 = db_query($conn,"select ed26_i_numero from telefoneescola where ed26_i_escola = ".db_getsession("DB_coddepto")." LIMIT 1");
      if(pg_num_rows($dados2)>0){
        $telefoneescola = trim(pg_result($dados2,0,"ed26_i_numero"));
      }else{
        $telefoneescola = "";
      }
      $this->SetFont('Arial','BI',$TamFonteNome);
      $this->Text(33,9,$nome);
      $this->Text(33,14,$nomeescola);
      $this->SetFont('Arial','I',8);
      $this->Text(33,18,$ruaescola.", ".$numescola." - ".$bairroescola);
      $this->Text(33,22,$cidadeescola." - ".$estadoescola);
      $this->Text(33,26,$telefoneescola);
      $comprim = ($this->w - $this->rMargin - $this->lMargin);
      $this->Text(33,30,($emailescola!=""?$emailescola." - ":"").$url);
      $Espaco = $this->w - 80 ;
      $this->SetFont('Arial','',7);
      $margemesquerda = $this->lMargin;
      $this->setleftmargin($Espaco);
      $this->sety(6);
      $this->setfillcolor(235);
      $this->roundedrect($Espaco - 3,5,75,28,2,'DF','123');
      $this->line(10,33,$comprim,33);
      $this->setfillcolor(255);
      $this->multicell(0,3,@$GLOBALS["head1"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head2"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head3"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head4"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head5"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head6"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head7"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head8"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head9"],0,1,"J",0);
      $this->setleftmargin($margemesquerda);
      $this->SetY(35);
    } else {

      $dados = db_query($conn,"select nomeinst,
                                   db21_compl,
                                   trim(ender)||',
                                   '||trim(cast(numero as text)) as ender,
                                   trim(ender) as rua,
                                   munic,
                                   numero,
                                   uf,
                                   cgc,
                                   telef,
                                   email,
                                   url,
                                   logo
                            from db_config where codigo = ".db_getsession("DB_instit"));
      $url = @pg_result($dados,0,"url");
      $this->SetXY(1,1);
      $this->Image('imagens/files/'.pg_result($dados,0,"logo"),7,3,20);

      //$this->Cell(100,32,"",1);
      $nome = pg_result($dados,0,"nomeinst");
      global $nomeinst;
      $nomeinst = pg_result($dados,0,"nomeinst");

      if(strlen($nome) > 42)
        $TamFonteNome = 8;
      else
        $TamFonteNome = 9;

      $this->SetFont('Arial','BI',$TamFonteNome);
      $this->Text(33,9,$nome);
      $this->SetFont('Arial','I',8);
      $sComplento = substr(trim(pg_result($dados,0,"db21_compl") ),0,20 );
      if ($sComplento != '' || $sComplento != null ) {
        $sComplento = ", ".substr(trim(pg_result($dados,0,"db21_compl") ),0,20 );
      }
      $this->Text(33,14,trim(pg_result($dados,0,"rua")).", ".trim(pg_result($dados,0,"numero")).$sComplento );
      $this->Text(33,18,trim(pg_result($dados,0,"munic"))." - ".pg_result($dados,0,"uf"));
      $this->Text(33,22,trim(pg_result($dados,0,"telef"))."   -    CNPJ : ".db_formatar(pg_result($dados,0,"cgc"),"cnpj"));
      $this->Text(33,26,trim(pg_result($dados,0,"email")));
      $comprim = ($this->w - $this->rMargin - $this->lMargin);
      $this->Text(33,30,$url);
      $Espaco = $this->w - 80 ;
      $this->SetFont('Arial','',7);
      $margemesquerda = $this->lMargin;
      $this->setleftmargin($Espaco);
      $this->sety(6);
      $this->setfillcolor(235);
      $this->roundedrect($Espaco - 3,5,75,28,2,'DF','123');
      $this->line(10,33,$comprim,33);
      $this->setfillcolor(255);
      $this->multicell(0,3,@$GLOBALS["head1"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head2"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head3"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head4"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head5"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head6"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head7"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head8"],0,1,"J",0);
      $this->multicell(0,3,@$GLOBALS["head9"],0,1,"J",0);
      $this->setleftmargin($margemesquerda);
      $this->SetY(35);
    }
  }
}