<?

$cldb_config = new cl_db_config;

$this->objpdf->sety(42);
$this->objpdf->setfont('Arial','B',18);
$this->objpdf->Multicell(0,8,'',0,"C",0); // tipo de alvara

$this->objpdf->setxy(10,69);
$this->objpdf->SetFont('Arial','',14);
$this->objpdf->multicell(0,7,db_geratexto($this->texto),0,"J",0,40);

$coluna = 15;
$fonte  = 12;
$linha =  $this->objpdf->gety();
$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->Text($coluna,$linha+9,'INSCRIÇÃO:'); // inscricao

if ($this->processo > 0) {
  $this->objpdf->Text($coluna + 97,$linha+9,'PROCESSO:'); // inscricao
}

$this->objpdf->SetFont('Arial','',$fonte);
$this->objpdf->Text($coluna + 60,$linha+9,$this->nrinscr); // inscricao

if ($this->processo > 0) {
  $this->objpdf->Text($coluna + 135,$linha+9,$this->processo); // processo
}

$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->Text($coluna,$linha+15,"NOME/RAZAO SOCIAL: "); // nome
$this->objpdf->SetFont('Arial','',$fonte);
$this->objpdf->Text($coluna + 60,$linha+15,$this->nome); // nome

$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->Text($coluna,$linha+21,"NOME FANTASIA: "); // nome
$this->objpdf->SetFont('Arial','',$fonte);
$this->objpdf->Text($coluna + 60,$linha+21,$this->fantasia); // nome


$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->Text($coluna,$linha+27,"CNPJ/CPF: ");
$this->objpdf->SetFont('Arial','',$fonte);
$this->objpdf->Text($coluna + 60,$linha+27,$this->cnpjcpf);


$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->Text($coluna,$linha+33,"ENDEREÇO: "); // endereco
$this->objpdf->SetFont('Arial','',$fonte);
$this->objpdf->Text($coluna + 60,$linha+33,$this->ender); // endereco

$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->Text($coluna,$linha+39,"NÚMERO: "); // endereco
$this->objpdf->SetFont('Arial','',$fonte);
$this->objpdf->Text($coluna + 60,$linha+39,($this->numero == ""?"":$this->numero));

if ($this->compl != "") {
  $this->objpdf->SetFont('Arial','B',$fonte);
  $this->objpdf->Text($coluna + 97 ,$linha+39,"COMPLEMENTO: "); // endereco
  $this->objpdf->SetFont('Arial','',$fonte);
  $this->objpdf->Text($coluna + 135,$linha+39,($this->compl == ""?"":$this->compl));
}

$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->Text($coluna,$linha+45,"BAIRRO: "); // endereco
$this->objpdf->SetFont('Arial','',$fonte);
$this->objpdf->Text($coluna + 60,$linha+45,$this->bairropri);

$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->Text($coluna,$linha+51,"DATA INICIAL: ");
if ($this->datafim != "") {
  $this->objpdf->Text($coluna + 60,$linha+51,"VALIDADE ATÉ: ");
}
$this->objpdf->SetFont('Arial','',$fonte);
$this->objpdf->Text($coluna + 60,$linha+51,db_formatar($this->datainc,'d'));
if ($this->datafim != "") {
  $this->objpdf->Text($coluna + 105,$linha+51,db_formatar($this->datafim,'d'));
}
$this->objpdf->setx(44);
$this->objpdf->roundedrect($coluna-2,$linha+3,187,51,2,'1234');
$this->objpdf->Ln(1);
$linha = 142;
$linha1 =  $this->objpdf->gety();

//========================= ATIVIDADE PRINCIPAL ========================================================================================
$linha = $linha+10;
$this->objpdf->sety($linha);
$pos = $linha;
$alt = 10; 
//	  $this->objpdf->roundedrect($coluna-2,$linha-1,187,20,2,'1234');
$this->objpdf->SetFont('Arial','B',12);
$this->objpdf->Ln(2);
$this->objpdf->setx(15);
$quebradatas = 0;
$quebraobs   = 1;
$quebradescr = 0;
if ($this->impdatas == 't'){
  $quebradescr = 0;
  $quebradatas = 1;
}
if ($this->impobsativ == 'f'){
  $quebraobs   = 0;
  $incremento  = 6;
}
$this->objpdf->Cell(135,5,"ATIVIDADE PRINCIPAL: ",0,0,"L",0) ; // descrição da atividade principal
if ($this->impdatas == 't'){
  $this->objpdf->Cell(24,5,"INICIO",0,0,"C",0);
  if($this->permanente == 'f'){
    $this->objpdf->Cell(24,5,"FINAL",0,1,"C",0);
  }else{
    $this->objpdf->Cell(24,5,"",0,1,"C",0);
  }
}else{
  $this->objpdf->Cell(24,5,"",0,0,"C",0);
  $this->objpdf->Cell(24,5,"",0,1,"C",0);
}
$this->objpdf->SetFont('Arial','',12);
if ($this->impcodativ == 't'){
  $this->objpdf->setx(15);
  $this->objpdf->Cell(20,5,$this->iAtivPrincCnae,0,0,"C",0);
}else{
  $this->objpdf->setx(15);
  $this->objpdf->Cell(20,5,"",0,0,"C",0);
}
$this->objpdf->Cell(120,5,$this->descrativ,0,$quebradescr,"L",0);
if ($this->impdatas == 't'){
  $this->objpdf->Cell(24,5,db_formatar($this->dtiniativ,'d'),0,0,"C",0);
  if ($this->permanente == 'f'){
    $this->objpdf->Cell(24,5,db_formatar($this->dtfimativ,'d'),0,$quebradatas,"C",0);
  }else{
    $this->objpdf->Cell(24,5,"",0,$quebradatas,"C",0);
  }
}else{
  $this->objpdf->Cell(24,5,"",0,0,"C",0);
  $this->objpdf->Cell(24,5,"",0,1,"C",0);
}
if ($this->impobsativ == 't'){
  $alt+= 5;
  if(isset($this->obsativ) && $this->obsativ != ""){
    $this->objpdf->setx(15);
    $obs = $this->obsativ;
    $this->objpdf->Cell(15,4,"",0,0,"C",0);
    $this->objpdf->Cell(164,4,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,1,"L",0);
  }else{
    $this->objpdf->setx(15);
    $this->objpdf->Cell(15,4,"",0,0,"C",0);
    $this->objpdf->Cell(164,4,"OBS: Sem observação ...",0,1,"L",0);
  }
}
$linha += 16;
$yyy = $this->objpdf->gety();
$obs='';
$this->objpdf->roundedrect($coluna-2,$pos+1,187,$alt+4,2,'1234');	  

//========================= ATIVIDADE SECUNDARIAS ========================================================================================	  
$this->objpdf->setx(15);
$yyy = $this->objpdf->gety();
$linha = $this->objpdf->gety() + 5;
$this->objpdf->sety($linha);
$num_outras=count($this->outrasativs);
$x=105;
$y=$linha+1;
//========================================================================================================================================================================
if ($num_outras >0) {
  $x=$x+4;
  reset($this->outrasativs); 
  $this->objpdf->Ln(4);
  $this->objpdf->setx(15);
  $yyy = $this->objpdf->gety() + 7;
  $this->objpdf->SetFont('Arial','B',13);
  $this->objpdf->Cell(135,5,"ATIVIDADE" . ($num_outras > 1?"S":"") . " SECUNDÁRIA" . ($num_outras > 1?"S":"") . ":",0,1,"L",0);
  $this->objpdf->Ln(2);
  $this->objpdf->SetFont('Arial','',10);
  $impdatafim="";
  $linha += 12;
  //========================================================================================================================================================================
  //define em qual celula vai quebrar a linha
  $iNumColuna = 0;
  $iQuebra    = 0;      
  $this->objpdf->setx(15);

  foreach ($this->aCodigosCnae as $iCodCnae ) {

    $this->objpdf->Cell(25,4,$iCodCnae,0,$iQuebra,"C",0);
    $iNumColuna++;

    if( $iQuebra == 1 ) {
      $this->objpdf->setx(15);
    }
    
    if( $iNumColuna == 6 ) {
      $iQuebra    = 1;
      $iNumColuna = 0;
    } else {
      $iQuebra    = 0;      
    }

    $yyyatual = $this->objpdf->gety();
    if  ($yyyatual >= 200){ 
      break;
    }  

  }
  $this->objpdf->SetFont('Arial','B',10);
  //=====================================================================================================================================================	   
  $this->objpdf->roundedrect($coluna-2,$y,187,31,2,'1234'); // descricao da atividade secundaria
  $y = $y + 35;
}
$x=64;

$this->objpdf->Ln(0);
$this->objpdf->setxy(14,$y);

$this->objpdf->SetFont('Arial','',14);
$this->objpdf->Multicell(0,6,$this->obs ); // observação

if(isset($this->impobslanc) && $this->impobslanc == 't'){
  if (isset($this->lancobs) && $this->lancobs != '') {;
    $this->objpdf->Ln(2);
    $this->objpdf->setx(15);
    $this->objpdf->SetFont('Arial','',14);
    $this->objpdf->Multicell(0,5,"Obs : ".$this->lancobs);
    $this->objpdf->Ln(2);
  }
}
$data= date("Y-m-d",db_getsession("DB_datausu"));
$dataex = split("-",$data);
$dia= $dataex[2];
$mes= $dataex[1];
$ano= $dataex[0];
$this->objpdf->ln(15);
$this->objpdf->SetFont('Arial','B',14);
$this->objpdf->cell(0,8,$this->municpref . ", ".$dia." DE ".strtoupper(db_mes( $mes))." DE ".$ano . ".",0,1,"R",0); // data

//  global $db02_texto;
$this->objpdf->setfont('arial','',9);

//  $this->objpdf->SetXY($coluna,264);

/******************************************** ASSINATURAS ************************************************************************************/	
//select * from where tipodoc = 1010 and db02_descr = 'ASSINATURAS_CODIGOPHP';
//se achou da eval no texto, senao faz como atualmente...
$sqlass = "
select *
from db_documento 
inner join db_docparag on db03_docum = db04_docum
inner join db_tipodoc on db08_codigo  = db03_tipodoc
inner join db_paragrafo on db04_idparag = db02_idparag 
where db03_tipodoc = 1010 and db03_instit = ".db_getsession("DB_instit")." 
and db02_descr = 'ASSINATURAS_CODIGOPHP' ";
//die($sqlass);
$resultass = pg_query($sqlass);
$linhasass = pg_num_rows($resultass);
if ($linhasass>0){
  //db_fieldsmemory($resultass,0);
  $ass= pg_result($resultass,0,'db02_texto');
  eval($ass);
}else{
  // QUANDO NÃO TIVER "ASSINATURAS_CODIGOPHP" CADASTRADAS NA DB_DOCUMENTOS pegar o modo antigo.
  //  for pegando as assinaturas do alvara
  $sqlparag = "select *
    from db_documento 
    inner join db_docparag on db03_docum = db04_docum
    inner join db_tipodoc on db08_codigo  = db03_tipodoc
    inner join db_paragrafo on db04_idparag = db02_idparag 
    where db03_tipodoc = 1010 and db03_instit = ".db_getsession("DB_instit")." 
    and db02_descr ilike 'assinatura_%' 
    order by db04_ordem ";
  $resparag = pg_query($sqlparag);

  if (pg_numrows($resparag) == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento do alvara!');
    exit;
  }
  $numrows = pg_numrows($resparag);

  $linha  = $this->objpdf->getY()+10;
  $colpri = $coluna;
  global $db02_texto;
  for ($i = 0; $i < $numrows; $i ++){
    db_fieldsmemory($resparag, $i);
    //echo("texto -- ".$db02_texto);
    $ass = $db02_texto;
    if($i % 2 == 0){
      $this->objpdf->SetXY($coluna,$linha);
      $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$ass,0,"C",0);
    }else{
      $this->objpdf->SetXY($coluna+90,$linha);
      $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$ass,0,"C",0);
      $linha += 10;
    }
  }
}	
$this->objpdf->SetAutoPageBreak('on',0);

?>
