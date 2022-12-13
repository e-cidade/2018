<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rharqbanco_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrharqbanco = new cl_rharqbanco;
$clrotulo = new rotulocampo;
$clrharqbanco->rotulo->label();
$clrotulo->label('rh34_codarq');
$clrotulo->label('rh34_descr');
$clrotulo->label('db90_descr');
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');


if(isset($emite)){
  db_inicio_transacao();
  $sqlerro = false;
  $clrharqbanco->alterar($rh34_codarq);
  $rh34_sequencial += 1;
  db_fim_transacao($sqlerro);
}else if(isset($rh34_codarq)){
  $result = $clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));
  if($clrharqbanco->numrows > 0){ 
    db_fieldsmemory($result,0);
    if($rh34_sequencial > 1){
      $rh34_sequencial += 1;
    }
  }
}


if (isset($emite) || isset($emite)){
  db_postmemory($HTTP_POST_VARS);

  $folha = $arquivo;
  $ano   = $DBtxt23;
  $mes   = $DBtxt25;


  if ($folha == 'r14'){
       $xarquivo = 'salario';
       $arquivo = 'gerfsal';
  }elseif ($folha == 'r35'){
       $xarquivo = '13alario';
       $arquivo = 'gerfs13';
  }elseif ($folha == 'r22'){
       $xarquivo = 'adiantamento';
       $arquivo = 'gerfadi';
  }elseif ($folha == 'r48'){
       $xarquivo = 'complementar';
       $arquivo = 'gerfcom';
  }

  db_sel_instit();

  $wherepes = '';
  if(isset($semest) && $semest != 0){
    $wherepes = " and r48_semest = ".$semest;
    $head6 = $xarquivo ." ($semest)";
  }



  $sql =
  "
  select 
    z01_nome       as nome
  , z01_cgccpf     as cpf
  , rh44_agencia   as agencia 
  , rh44_conta     as contacorrente 
  , r70_estrut     as lotacao 
  , rh37_descr     as funcao 

  ,".$folha."_regist as registro  
  ,".$folha."_rubric as rubrica  
  ,rh27_descr        as descricao  
  ,".$folha."_quant  as quantidade  
  ,".$folha."_pd     as pd  
  ,".$folha."_valor  as valor 

  , (select count( ".$folha."_rubric ) from ".$arquivo."
     where ".$folha."_anousu = ".$ano."
       and ".$folha."_mesusu = ".$mes."
       and ".$folha."_regist = rh01_regist 
       and ".$folha."_pd != 3  )  as linhas

  , (select coalesce(sum( ".$folha."_valor ),0) from ".$arquivo."
     where ".$folha."_anousu = ".$ano."
       and ".$folha."_mesusu = ".$mes."
       and ".$folha."_regist = rh01_regist 
       and ".$folha."_rubric in ( 'R981','R982','R983') )  as IR

  , (select coalesce( ".$folha."_valor,0)  from ".$arquivo."
     where ".$folha."_anousu = ".$ano."
       and ".$folha."_mesusu = ".$mes."
       and ".$folha."_regist = rh01_regist 
       and ".$folha."_rubric in ( 'R992' ) )  as PREVIDENCIA

  , (select coalesce( ".$folha."_valor,0)  from ".$arquivo."
     where ".$folha."_anousu = ".$ano."
       and ".$folha."_mesusu = ".$mes."
       and ".$folha."_regist = rh01_regist 
       and ".$folha."_rubric in ( 'R991' ) )  as FGTS

   from ".$arquivo."

   left outer join rhrubricas 
     on rh27_rubric = ".$folha."_rubric  
    and rh27_instit = ".db_getsession('DB_instit')."

  inner join rhpessoal
     on rh01_regist = ".$folha."_regist

   left outer join rhpessoalmov 
     on rh02_anousu = ".$ano."
    and rh02_mesusu = ".$mes."
    and rh02_regist = ".$folha."_regist  
    and rh02_instit = ".db_getsession('DB_instit')."
  
  inner join rhlota
     on rh02_lota  = r70_codigo
    and r70_instit = rh02_instit 


   left join rhpesbanco
     on rh02_seqpes = rh44_seqpes
   left outer join rhfuncao 
     on rh37_funcao = rh01_funcao  
    and rh37_instit = rh02_instit

   left outer join cgm 
     on rh01_numcgm = z01_numcgm 

   where ".$folha."_anousu = ".$ano."
     and ".$folha."_mesusu = ".$mes."
     and ".$folha."_pd != 3 
     and rh44_codban = '001'
   order by ".$folha."_regist , ".$folha."_rubric ";


  $arq = "/tmp/contracheque".$xarquivo.$ano.$mes.".txt";
  
  $arquivo = fopen($arq,'w');  
  $result = pg_query($sql);

  $imp = '';

  $imp .= str_repeat("0", 20)                              ; // 1.01 - n/20 - preencher com zeros
  $imp .= '00'                                             ; // 1.02 - n/2  - preencher com zeros
  $imp .= '0'                                              ; // 1.03 - n/1  - preencher com zeros
  $imp .= db_formatar($rh34_agencia,'s','0',4,'e',0)       ; // 1.04 - n/4  - prefixo agencia prefeitura sem dv
  $imp .= db_formatar($rh34_conta,'s','0',11,'e',0)        ; // 1.05 - n/11 - conta c.prefeitura sem dv
  $imp .= 'EDO001'                                         ; // 1.06 - a/6  - fixo edo001
  $imp .= db_formatar($rh34_convenio,'s','0',12,'e',0)     ; // 1.07 - n/12 - nr. do contrato
  $imp .= db_formatar($rh34_sequencial,'s','0',6,'e',0)    ; // 1.08 - n/6 - nr.sequencial da remessa 
  $imp .= '00315'                                          ; // 1.09 - n/5 - codigo do produto
  $imp .= '00001'                                          ; // 1.10 - n/5 - codigo da modalidade
  $imp .= db_formatar($ano,'s','0',4,'e',0 )               ; // 1.11 - n/4 - ano de referencia
  $imp .= db_formatar($mes,'s','0',2,'e',0 )               ; // 1.12 - n/2 - mes de referncia 
  $imp .= db_formatar($remessa_extra,'s','0',2,0,"0" )     ; // 1.13 - n/2 - nr sequencial de extraordinariedade
  $imp .= str_replace('/','',$datadeposit)                 ; // 1.14 - n/8 - data do credito
  $imp .= str_repeat(" ", 12)                              ; // 1.15 - a/12  - brancos

  fputs($arquivo,$imp."\r\n");

  $total_proventos = 0;
  $total_descontos = 0;
  $total_liquido   = 0 ;
  $total_prev      = 0;
  $total_fgts      = 0;
  $total_ir        = 0;
  $quant_destinatarios = 0;

  $registroaux = 0;
  for($x = 0;$x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);


  if ($registroaux != $registro){
      $prov = 0;
      $desc = 0;
      $liquido = 0;
      $prev = 0;
      $fgts = 0;
      $ir = 0;

      $registroaux = $registro;

      $imp  = db_formatar($registro,'s','0',20,'e',0)            ; // 2.01 - n/20 - codigo funcional destinatario
      $imp .= '00'                                               ; // 2.02 - n/2  - zeros
      $imp .= '1'                                                ; // 2.03 - n/1  - preencher c/1
      $imp .= db_formatar($agencia,'s',' ',4,'e',0)             ; // 2.04 - n/4  - prefixo agencia
      $imp .= db_formatar($contacorrente,'s','0',11,'e',0)      ; // 2.05 - n/11 - conta bb destinatario
//      ?? str(folha->linhas + 6,2,0,'0')       && 2.06 - n/2  - qtd linhas do texto
      $imp .= db_formatar($linhas + 10,'s','0',2,'e',0)          ; // 2.06 - n/2  - qtd linhas do texto
      $imp .= db_formatar(trim($nome),'s',' ',40,'d',0)          ; // 2.07 - a/40 - nome destinatario
      $imp .= db_formatar(trim($cpf),'s',' ',11,'e',0)           ;  // 2.08 - n/11 - cpf destinatario
      $imp .= str_repeat(" ", 9)                                 ;        // 2.09 - a/9  - brancos

      fputs($arquivo,$imp."\r\n");

      $quant_destinatarios ++;

      // visao 3 - impressao do texto do contracheque.
      //registroaux = folha->registro
      $sequencia = 0;
      $prov = 0;
      $desc = 0;
      $liquido = 0;
      $prev = 0;
      $fgts = 0;
      $ir = 0;
      //

      $texto = str_repeat(' ',4).db_formatar($nomeinst,'s',' ', 44,'d',0);
      //1234 1234567890123456789012 999.99 999999.99  p '

      $sequencia++     ;
      $imp  = db_formatar($registro,'s','0',20,'e',0)  ; // 3.01 n/20 - codigo funcional do destinatario
      $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;        // 3.02 n/2  - sequencial da linha de texto
      $imp .= '2'                                      ;       // 3.03 n/1  - preenche com 2
      $imp .= $texto                                   ;        // 3.04 a/48 - texto do documento
      $imp .= '0'                                      ;       // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
      $imp .= str_repeat('',28)                        ;       // 3.06 a/28 brancos

      fputs($arquivo,$imp."\r\n");
      //
      $cnpj_inst = db_formatar($cgc,'cnpj'); 
      $texto = db_formatar( '           CNPJ '.$cnpj_inst,'s',' ',48,'d',0);
      //1234 1234567890123456789012 999.99 999999.99  p '

      $sequencia++;     
      $imp  = db_formatar( $registro,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
      $imp .= db_formatar($sequencia,'s','0',2,'e',0)    ;       // 3.02 n/2  - sequencial da linha de texto
      $imp .= '2'                                        ;      // 3.03 n/1  - preenche com 2
      $imp .= $texto                                     ;       // 3.04 a/48 - texto do documento
      $imp .= '1'                                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
      $imp .= str_repeat(' ',28)                         ;      // 3.06 a/28 brancos

      fputs($arquivo,$imp."\r\n");

      //
      $texto = db_formatar( 'Servidor:'.$nome,'s',' ',48,'d',0);
      //1234 1234567890123456789012 999.99 999999.99  p '
      $sequencia++;     
      $imp  = db_formatar( $registro,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
      $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
      $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
      $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
      $imp .= '0'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
      $imp .= str_repeat(' ',28)                  ;      // 3.06 a/28 brancos

      fputs($arquivo,$imp."\r\n");
      //
      
      $texto = db_formatar( 'Matric:'.db_formatar($registro,'s',' ',6,'e',0).' Funcao:'.$funcao,'s',' ',48,'d',0);
      //1234 1234567890123456789012 999.99 999999.99  p '

      $sequencia++;     
      $imp  = db_formatar( $registro,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
      $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
      $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
      $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
      $imp .= '1'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
      $imp .= str_repeat(' ',28)          ;      // 3.06 a/28 brancos
 
      fputs($arquivo,$imp."\r\n");
/////ate aqui novo

      //
      $texto = db_formatar( 'Folha de '.strtoupper($xarquivo).str_repeat(' ',4).'Periodo:'.db_formatar($mes,'s','0',2,'e',0).'/'.$ano,'s',' ',48,'d',0);
      //1234 1234567890123456789012 999.99 999999.99  p '

      $sequencia++;     
      $imp  = db_formatar( $registro,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
      $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
      $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
      $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
      $imp .= '1'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
      $imp .= str_repeat(' ',28)          ;      // 3.06 a/28 brancos
     
      fputs($arquivo,$imp."\r\n");

      //
      $texto = 'Rubrica                      Quant     Valor P/D';
      //1234 1234567890123456789012 999.99 999999.99  p '

      $sequencia++;     
      $imp  = db_formatar( $registro,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
      $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
      $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
      $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
      $imp .= '0'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
      $imp .= str_repeat(' ',28)          ;      // 3.06 a/28 brancos
      
      fputs($arquivo,$imp."\r\n");
  }


  $texto = $rubrica.' '.db_formatar(substr($descricao,0,22),'s',' ',22,'d',0).' '.db_formatar(trim(str_replace('.','',db_formatar($quantidade,'f'))),'s',' ',6,'e',0).' '.db_formatar(trim(str_replace('.','',db_formatar($valor,'f'))),'s',' ',9,'e',0);

  if ($pd == 1){                     // PROVENTOS
     $prov  += $valor;
     $texto .= '  P ';
  }else{                                 // DESCONTOS
     $desc  += $valor;
     $texto .= '  D ';
  }

  $sequencia++;     
  $imp  = db_formatar( $registro,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
  $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
  $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
  $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
  $imp .= '0'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
  $imp .= str_repeat(' ',28)          ;      // 3.06 a/28 brancos

  fputs($arquivo,$imp."\r\n");
  // guarda a informaÃ§ao das bases , que aparecem em todas as linhas para 
  // este servidor, para poder imprimir o rodape.
  $prev = $previdencia;
  $fgts = $fgts;
  $ir   = $ir;

  // se acabaram as rubricas deste funcionario precisa imprimir o rodape

  echo "<br> registroaux --> $registroaux    pg_result --> ".pg_result($result,$x+1,'registro');
  if ($registroaux != pg_result($result,$x+1,'registro')){

     // imprime total de proventos 
     //1234 1234567890123456789012 999.99 999999.99  p '

     //texto = space(48)+'Totais '+str(prov,12,2)+'  '+str(desc,12,2)
     $texto = str_repeat(' ',5).'Total Proventos               '.db_formatar(trim(str_replace('.','',db_formatar($prov,'f'))),'s',' ',9,'e',0).str_repeat(' ',4);
     $total_descontos += $desc;
     $total_proventos += $prov;
   
     $sequencia++;     
     $imp  = db_formatar( $registroaux,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
     $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
     $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
     $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
     $imp .= '0'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
     $imp .= str_repeat(' ',28)          ;      // 3.06 a/28 brancos

     fputs($arquivo,$imp."\r\n");


     // imprime total de descontos
     //1234 1234567890123456789012 999.99 999999.99  p '

     //texto = space(48)+'Totais '+str(prov,12,2)+'  '+str(desc,12,2)
     $texto = str_repeat(' ',5).'Total Descontos               '.db_formatar(trim(str_replace('.','',db_formatar($desc,'f'))),'s',' ',9,'e',0).str_repeat(' ',4);
     $total_descontos += $desc;

     $sequencia++;     
     $imp  = db_formatar( $registroaux,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
     $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
     $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
     $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
     $imp .= '0'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
     $imp .= str_repeat(' ',28)          ;      // 3.06 a/28 brancos

     fputs($arquivo,$imp."\r\n");

     // imprime o liquido
     $liquido = $prov-$desc;
     $total_liquido += $liquido;
     $texto = str_repeat(' ',5).'Líquido                       '.db_formatar(trim(str_replace('.','',db_formatar($liquido,'f'))),'s',' ',9,'e',0).str_repeat(' ',4);
     $total_descontos += $desc;

     $sequencia++;     
     $imp  = db_formatar( $registroaux,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
     $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
     $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
     $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
     $imp .= '1'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
     $imp .= str_repeat(' ',28)          ;      // 3.06 a/28 brancos

     fputs($arquivo,$imp."\r\n");

     // imprime bases 
     $texto = 'Prev.:'.db_formatar(trim(str_replace('.','',db_formatar($prev,'f'))),'s',' ',9,'e',0).
              '  Fgts:'.db_formatar(trim(str_replace('.','',db_formatar($fgts,'f'))),'s',' ',9,'e',0).
              '  IRRF:'.db_formatar(trim(str_replace('.','',db_formatar($ir,'f'))),'s',' ',9,'e',0).' ';

     $sequencia++;     
     $imp  = db_formatar( $registroaux,'s','0',20,'e',0)   ;// 3.01 n/20 - codigo funcional do destinatario
     $imp .= db_formatar($sequencia,'s','0',2,'e',0)   ;       // 3.02 n/2  - sequencial da linha de texto
     $imp .= '2'                        ;      // 3.03 n/1  - preenche com 2
     $imp .= $texto                     ;       // 3.04 a/48 - texto do documento
     $imp .= '0'                        ;      // 3.05 a/1  - 1 para saltar linha -  0 para linhas corridas
     $imp .= str_repeat(' ',28)          ;      // 3.06 a/28 brancos

     fputs($arquivo,$imp."\r\n");

     $total_prev += $prev;
     $total_fgts += $fgts;
     $total_ir   += $ir;
  }

  }

$imp  = '99999999999999999999'              ; // 4.01 n/20 - preencher com 9
$imp .= '99'                                ; // 4.02 n/2  - preencher com 9
$imp .= '9'                                 ; // 4.03 n/1  - preencher com 9
$imp .= '999999999999999'                   ; // 4.04 n/15 - preencher com 9
$imp .= db_formatar($quant_destinatarios,'s','0',11,'e',0)  ;  // 4.05 n/11 - quantidade de destinatarios
$imp .= str_repeat(" ", 51)               ; // 4.06 a/50 - brancos (qtd errada no layout)

fputs($arquivo,$imp."\r\n");

fclose($arquivo);
}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  qry = "";
  if(document.form1.rh40_sequencia){
    qry = "&rh40_sequencia="+document.form1.rh40_sequencia.value;
  }
  jan = window.open('pes2_eldcontracheque002.php?folha='+document.form1.arquivo.value+
                                                 '&ano='+document.form1.DBtxt23.value+
                                                 '&mes='+document.form1.DBtxt25.value+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="left" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
  <tr>
    <td><b>Data do Depósito:</b></td>
    <td>
      <?
      if((!isset($datadeposit_dia) || (isset($datadeposit_dia) && trim($datadeposit_dia) == "")) && (!isset($datadeposit_mes) || (isset($datadeposit_mes) && trim($datadeposit_mes) == "")) && (!isset($datadeposit_ano) || (isset($datadeposit_ano) && trim($datadeposit_ano) == ""))){
        $datadeposit_dia = "";
        $datadeposit_mes = "";
        $datadeposit_ano = "";
      }
      db_inputdata('datadeposit',@$datadeposit_dia,@$datadeposit_mes,@$datadeposit_ano,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr> 
    <td align="left" nowrap title="<?=@$Trh34_codarq?>">
      <?db_ancora(@$Lrh34_codarq,"js_pesquisa(true);",1);?>
    </td>
    <td align="left" nowrap colspan="3">
      <?db_input("rh34_codarq",6,@$Irh34_codarq,true,"text",4,"onchange='js_pesquisa(false);'");?>
      <?db_input("rh34_descr",40,@$Irh34_descr,true,"text",3);?>
      <?db_input("rodape",40,0,true,"hidden",3);?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_codban?>">
      <?
      db_ancora(@$Lrh34_codban,"js_pesquisarh34_codban(true);",1);
      ?>
    </td>
    <td colspan="3"> 
      <?
      db_input('rh34_codban',6,$Irh34_codban,true,'text',1," onchange='js_pesquisarh34_codban(false);'")
      ?>
      <?
      db_input('db90_descr',40,$Idb90_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_agencia?>">
      <?=@$Lrh34_agencia?>
    </td>
    <td> 
      <?
      db_input('rh34_agencia',5,$Irh34_agencia,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvagencia?>" align="right">
      <?=@$Lrh34_dvagencia?>
    </td>
    <td> 
      <?
      db_input('rh34_dvagencia',2,$Irh34_dvagencia,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_conta?>">
      <?=@$Lrh34_conta?>
    </td>
    <td> 
      <?
      db_input('rh34_conta',15,$Irh34_conta,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvconta?>" align="right">
      <?=@$Lrh34_dvconta?>
    </td>
    <td> 
      <?
      db_input('rh34_dvconta',2,$Irh34_dvconta,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_convenio?>">
      <?=@$Lrh34_convenio?>
    </td>
    <td> 
      <?
      db_input('rh34_convenio',15,$Irh34_convenio,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_sequencial?>">
      <?=@$Lrh34_sequencial?>
    </td>
    <td> 
      <?
      db_input('rh34_sequencial',15,$Irh34_sequencial,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td><b>Arquivo:</b</td>
    <td>
     <?
       $x = array("r14"=>"Salário","r48"=>"Complementar","r35"=>"13o. Salário","r22"=>"Adiantamento");
       db_select('arquivo',$x,true,4,"onchange='document.form1.submit();'");
     ?>
    </td>
     </tr>
     <?
     if(isset($arquivo) && $arquivo == "r48"){
       $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($DBtxt23,$DBtxt25,null,null,"distinct r48_semest as rh40_sequencia"));
       if($clgerfcom->numrows > 0){
	 echo "
	  <tr>
	    <td align='left' title='Nro. Complementar'><strong>Nro. Complementar:</strong></td>
            <td>
	      <select name='rh40_sequencia'>
		<option value = '0'>Todos
	      ";
	      for($i=0; $i<$clgerfcom->numrows; $i++){
		db_fieldsmemory($result_semest, $i);
		echo "<option value = '$rh40_sequencia'>$rh40_sequencia";
	      }
	 echo "
	    </td>
	  </tr>
	      ";
       }else{
         echo "
               <tr>
                 <td colspan='2' align='center'>
                   <font color='red'>Sem complementar para este período.</font>
                 </td>
               </tr>
              ";
       }
     }
     ?>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite" id="emite" type="submit" value="Processar"  >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  <?
  if(isset($emite)){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>
function js_pesquisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rharqbanco','func_rharqbanco.php?funcao_js=parent.js_mostra1|rh34_codarq|rh34_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codarq.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_rharqbanco','func_rharqbanco.php?pesquisa_chave='+document.form1.rh34_codarq.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }else{
      document.form1.rh34_codarq.value = '';
      document.form1.rh34_descr.value = '';
      location.href = 'pes2_aleemitearqbanco001.php';
    }
  }
}
function js_mostra(chave,erro){
  if(erro==true){
    document.form1.rh34_descr.value = chave;
    document.form1.rh34_codarq.value = '';
    document.form1.rh34_codarq.focus();
    location.href = 'pes2_aleemitearqbanco001.php';
  }else{
    document.form1.submit();
  }
}
function js_mostra1(chave1,chave2){
  document.form1.rh34_codarq.value = chave1;
  document.form1.submit();
  db_iframe_rharqbanco.hide();
}
function js_pesquisarh34_codban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codban.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
    }else{
      document.form1.db90_descr.value = '';
    }
  }
}
function js_mostradb_bancos(chave,erro){
  document.form1.db90_descr.value = chave;
  if(erro==true){
    document.form1.rh34_codban.focus();
    document.form1.rh34_codban.value = '';
  }
}
function js_mostradb_bancos1(chave1,chave2){
  document.form1.rh34_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}
</script>
<?
if(isset($emite2)){
  if($clrharqbanco->erro_status=="0"){
    $clrharqbanco->erro(true,false);
    $db_botao=true;
    if($clrharqbanco->erro_campo!=""){
      echo "<script> document.form1.".$clrharqbanco->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrharqbanco->erro_campo.".focus();</script>";
    };
  }else{
    echo "<script>js_emite();</script>";
  };
}
?>