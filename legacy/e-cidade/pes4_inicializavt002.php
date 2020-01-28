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


function inicializa_vt()
{
  global $total_vales, $m_semana, $diasdasemana,$subpes;
  global $subpes_origem, $qtdmes, $qtdmes_novo, $qtdmes_ant, $mesano_novo, $mesano_ant;
  global $subpes_ant,$m_vtfdias02, $vtffuncant, $Ivtffuncant;
  
  global $r110_lotaci, $r110_lotacf, $r110_regisi, $r110_regisf,$opcao_gml,$opcao_geral,$faixa_lotac,$faixa_regis;
  global $lotacao_faixa,$opcao_filtro;
  
  if (!isset($r110_lotaci)) {
    $r110_lotaci = '    ';
  }
  
  if (!isset($r110_lotacf)) {
    $r110_lotacf = '    ';
  }
  
  if (!isset($r110_regisi)) {
    $r110_regisi = $faixa_regis;
  }
  
  if (!isset($r110_regisf)) {
    $r110_regisf = $faixa_regis;
  }
  
  if (!isset($opcao_filtro)) {
    $opcao_filtro = "0";
  }
  
  
  if ($faixa_lotac != " ") {
    $lotacao_faixa = $faixa_lotac;
  }
  
  $condicao_deleta = db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
  $r110_lotacf,$faixa_regis,$faixa_lotac);
  $total_vales = 0;
  
  $subpes = db_anofolha().'/'.db_mesfolha();
  
  $m_semana = array();
  
  $diasdasemana = array();
  $diasdasemana[1] = "dom";
  $diasdasemana[2] = "seg";
  $diasdasemana[3] = "ter";
  $diasdasemana[4] = "qua";
  $diasdasemana[5] = "qui";
  $diasdasemana[6] = "sex";
  $diasdasemana[7] = "sab";
  
  
  $subpes_origem = $subpes;
  $mesano_novo = db_substr($subpes,6,2)."/".db_substr($subpes,1,4);
  $qtdmes_novo = ndias(db_substr($subpes,6,2)."/".db_substr($subpes,1,4) );
  $dia_01_subpes = db_ctod("01/".$mesano_novo );
  
  $submes = db_val(db_substr($subpes,-2))-1;
  $subano = db_val(db_substr($subpes,1,4));
  if ($submes < 1) {
    $submes = 12;
    $subano -= 1;
  }
  
  $subpes = db_str($subano,4)."/".db_str($submes,2,0,"0");
  $mesano_ant = db_substr($subpes,6,2)."/".db_substr($subpes,1,4);
  $qtdmes_ant = ndias(db_substr($subpes,6,2)."/".db_substr($subpes,1,4) );
  $qtdmes = $qtdmes_ant;
  // para uso na funcao montaquadro.....() do fpag080;
  
  $subpes_ant = $subpes;
  $subpes = $subpes_origem;
  
  $primeiro_mes = true ;
  
  
  
  $condicaoaux  = " and r63_difere = 'f' ";
  global $vtfdiasant;
  //   die("select * from vtfdias ".bb_condicaosubpesanterior("r63_").$condicaoaux );
  if (db_selectmax("vtfdiasant", "select * from vtfdias ".bb_condicaosubpesanterior("r63_").$condicaoaux )) {
    
    $primeiro_mes = false;
  }
  
  
  // apagar todos os vales dos arquivos vtffunc e vtfdias dos funcionarios da;
  deleta_vale_funcionarios($condicao_deleta );
  
  // die("retornou da função deleta_vale_funcionarios");
  $matriz1 = array();
  $matriz2 = array();
  $matriz1[1] = "r63_regist";
  $matriz1[2] = "r63_vale";
  $matriz1[3] = "r63_dia";
  $matriz1[4] = "r63_quant";
  $matriz1[5] = "r63_obrig";
  $matriz1[6] = "r63_difere";
  $matriz1[7] = "r63_quants";
  $matriz1[8] = "r63_anousu";
  $matriz1[9] = "r63_mesusu";
  
  $matriz3 = array();
  $matriz4 = array();
  $matriz3[1] = "r17_regist";
  $matriz3[2] = "r17_lotac";
  $matriz3[3] = "r17_codigo";
  $matriz3[4] = "r17_difere";
  $matriz3[5] = "r17_situac";
  $matriz3[6] = "r17_tipo";
  $matriz3[7] = "r17_quant";
  $matriz3[8] = "r17_anousu";
  $matriz3[9] = "r17_mesusu";
  
  $ind = 0;
  for ($Ivtffuncant=0; $Ivtffuncant<count($vtffuncant); $Ivtffuncant++) {
    $lotac  = trim(db_str($vtffuncant[$Ivtffuncant]["r01_lotac"],4));
    $matric = $vtffuncant[$Ivtffuncant]["r17_regist"];
    $ind += 1;
    $condicaoaux = " rh64_lota = to_number('" . $lotac . "','9999') and r70_instit = ".db_getsession("DB_instit")." ";
    global $lotacao;
    db_selectmax("lotacao","select rh64_calend as r13_calend from rhlotacalend inner join rhlota on r70_codigo = rh64_lota where ".$condicaoaux );
    
    if ($primeiro_mes) {
      $matriz2[1] = $matric;
      $matriz2[2] = $vtffuncant[$Ivtffuncant]["r17_codigo"];
      $matriz2[3] = $dia_01_subpes;
      $matriz2[4] = $vtffuncant[$Ivtffuncant]["r17_quant"];
      $matriz2[5] = 't';
      $matriz2[6] = $vtffuncant[$Ivtffuncant]["r17_difere"];
      $matriz2[7] = 0;
      $matriz2[8] = db_val(db_substr($subpes,1,4));
      $matriz2[9] = db_val(db_substr($subpes,6,2));
      
      db_insert("vtfdias", $matriz1, $matriz2 );
      
      $matriz4[1] = $matric;
      $matriz4[2] = $lotac;
      $matriz4[3] = $vtffuncant[$Ivtffuncant]["r17_codigo"];
      $matriz4[4] = $vtffuncant[$Ivtffuncant]["r17_difere"];
      $matriz4[5] = $vtffuncant[$Ivtffuncant]["r17_situac"];
      $matriz4[6] = 't' ;
      $matriz4[7] = 0;
      $matriz4[8] = db_val(db_substr($subpes,1,4));
      $matriz4[9] = db_val(db_substr($subpes,6,2));
      
      db_insert("vtffunc", $matriz3, $matriz4 );
      
    } else if (db_boolean($vtffuncant[$Ivtffuncant]["r17_tipo"] ) ) {
      
      $condicaoaux  = " and r63_regist = ".db_sqlformat($vtffuncant[$Ivtffuncant]["r17_regist"]) ;
      $condicaoaux .= " and r63_vale   = ".db_sqlformat($vtffuncant[$Ivtffuncant]["r17_codigo"]) ;
      $condicaoaux .= " and r63_difere = ".db_sqlformat($vtffuncant[$Ivtffuncant]["r17_difere"]) ;
      if (db_selectmax("vtfdiasant", "select * from vtfdias ".bb_condicaosubpesanterior("r63_").$condicaoaux )) {
        $matriz2[1] = $matric;
        $matriz2[2] = $vtffuncant[$Ivtffuncant]["r17_codigo"];
        $matriz2[3] = $dia_01_subpes;
        $matriz2[4] = $vtfdiasant[0]["r63_quant"];
        $matriz2[5] = 't';
        $matriz2[6] = $vtffuncant[$Ivtffuncant]["r17_difere"];
        $matriz2[7] = $vtfdiasant[0]["r63_quants"];
        $matriz2[8] = db_val(db_substr($subpes,1,4));
        $matriz2[9] = db_val(db_substr($subpes,6,2));
        
        db_insert("vtfdias", $matriz1, $matriz2 );
        
        $matriz4[1] = $matric;
        $matriz4[2] = $lotac;
        $matriz4[3] = $vtffuncant[$Ivtffuncant]["r17_codigo"];
        $matriz4[4] = $vtffuncant[$Ivtffuncant]["r17_difere"];
        $matriz4[5] = $vtffuncant[$Ivtffuncant]["r17_situac"];
        $matriz4[6] = 't';
        $matriz4[7] = 0;
        $matriz4[8] = db_val(db_substr($subpes,1,4));
        $matriz4[9] = db_val(db_substr($subpes,6,2));
        
        db_insert("vtffunc", $matriz3, $matriz4 );
        
      }
    } else {
      
      $m_vtfdias01 = array();
      monta_semana_mesant();
      leitura_vtfdias_mesanterior($matric);
      montaquadrosemanaapartirdovtfdias();
      monta_vtfdias_novo();
      if ($total_vales > 0) {
        $matriz4[1] = $matric;
        $matriz4[2] = $lotac;
        $matriz4[3] = $vtffuncant[$Ivtffuncant]["r17_codigo"];
        $matriz4[4] = $vtffuncant[$Ivtffuncant]["r17_difere"];
        $matriz4[5] = $vtffuncant[$Ivtffuncant]["r17_situac"];
        $matriz4[6] = $vtffuncant[$Ivtffuncant]["r17_tipo"];
        $matriz4[7] = 0;
        $matriz4[8] = db_val(db_substr($subpes,1,4));
        $matriz4[9] = db_val(db_substr($subpes,6,2));
        //echo "<BR> matric --> $matric lotac --> $lotac r17_codigo --> ".$vtffuncant[$Ivtffuncant]["r17_codigo"];
        db_insert("vtffunc", $matriz3, $matriz4 );
        
        for ($xi=1; $xi<$qtdmes_novo; $xi++) {
          if ($m_vtfdias02[$xi][1] > 0) {
            $data = db_ctod($m_vtfdias02[$xi][0]);
            $quantidade = $m_vtfdias02[$xi][1];
            $obrigatorio = ($m_vtfdias02[$xi][2]=="s"?'t':'f');
            $quants = $m_vtfdias02[$xi][3];
            $matriz2[1] = $matric;
            $matriz2[2] = $vtffuncant[$Ivtffuncant]["r17_codigo"];
            $matriz2[3] = $data;
            $matriz2[4] = $quantidade;
            $matriz2[5] = $obrigatorio;
            $matriz2[6] = $vtffuncant[$Ivtffuncant]["r17_difere"];
            $matriz2[7] = $quants;
            $matriz2[8] = db_val(db_substr($subpes,1,4));
            $matriz2[9] = db_val(db_substr($subpes,6,2));
            
            //echo "<BR>  r17_codigo --> ".$vtffuncant[$Ivtffuncant]["r17_codigo"]. " data --> $data quantidade --> $quantidade obrigatorio --> $obrigatorio" ;
            db_insert("vtfdias", $matriz1, $matriz2 );
          }
        }
      }
    }
    
  }
  
}


function monta_vtfdias_novo()
{
  
  global $lotacao,$calendf,$subpes_origem,$qtdmes_novo,$total_vales,$m_vtfdias02;
  global $m_vtfdias02,$m_semana;
  
  // a partir da semana gerada cria novo vetor do vtfdias ;
  
  $m_vtfdias02 = array();
  $total_vales = 0;
  for ($xy=1 ; $xy<=$qtdmes_novo; $xy++) {
    $dia = db_str($xy ,2,0,"0")."/".db_substr($subpes_origem,6,2)."/".db_substr($subpes_origem,1,4);
    $feriado = true;
    if($lotacao[0]["r13_calend"] == ''){
      $condicaoaux  = " where r62_calend = 0";
    }else{
      $condicaoaux  = " where r62_calend = ".db_sqlformat($lotacao[0]["r13_calend"]);
    }
    $condicaoaux .= "   and r62_data = ".db_sqlformat(db_ctod($dia) );
    if (db_selectmax("calendf", "select * from calendf ".$condicaoaux )) {
      $feriado = false;
    }
    $indice = db_dow(db_ctod($dia)) + 1;
    $obrigatorio = $m_semana[$indice][2];
    $quants = $m_semana[$indice][1];
    $qtd_dia = $m_semana[$indice][1];
    ;
    if ($feriado || (!$feriado && strtolower($obrigatorio) == "s") ) {
      $total_vales += $qtd_dia;
    }
    if (db_empty($qtd_dia)) {
      $qtd_dia = 0;
    }
    
    //* _01/01/1999 | seg | 123456 | n | _f | 000;
    $m_vtfdias02[$xy][0] = $dia;
    $m_vtfdias02[$xy][1] = $qtd_dia;
    $m_vtfdias02[$xy][2] = $obrigatorio;
    $m_vtfdias02[$xy][3] = $quants;
    
  }
}

function monta_semana_mesant()
{
  
  global $diasdasemana,$m_semana;
  
  for ($yi=3; $yi<10; $yi++) {
    $m_semana[$yi-1][0] = $diasdasemana[$yi-2];
    $m_semana[$yi-1][1] = 0;
    $m_semana[$yi-1][2] = 'n';
  }
  
}

function leitura_vtfdias_mesanterior($matric)
{
  
  global $vtffuncant,$Ivtffuncant,$subpes_origem,$qtdmes_ant,$mesano_ant,$lotacao,$total_vales,$m_vtfdias01;
  global $subpes,$lotacao, $subpes_ant;
  
  $m_vtfdias01 = array();
  
  // cria vetor todo vazio para depis preencher com os dias com qtd do arquivo;
  $subpes = $subpes_ant;
  for ($y=1; $y<=$qtdmes_ant; $y++) {
    calcula_pela_semana($y);
  }
  $subpes = $subpes_origem;
  
  $dataum = db_ctod("01/".$mesano_ant );
  $dataum_ = date("Y-m-d",db_mktime($dataum)+($qtdmes_ant*86400));
  
  $condicaoaux  = " and r63_regist  = ".db_sqlformat($vtffuncant[$Ivtffuncant]["r17_regist"] ) ;
  $condicaoaux .= " and r63_vale    = ".db_sqlformat($vtffuncant[$Ivtffuncant]["r17_codigo"] ) ;
  $condicaoaux .= " and r63_difere  = ".db_sqlformat($vtffuncant[$Ivtffuncant]["r17_difere"] ) ;
  $condicaoaux .= " and r63_dia    >= ".db_sqlformat($dataum ) ;
  $condicaoaux .= " and r63_dia    < ".db_sqlformat($dataum_ ) ;
  $condicaoaux .= " order by r63_dia ";
  global $vtfdiasant;
  db_selectmax("vtfdiasant", "select * from vtfdias ".bb_condicaosubpesanterior("r63_").$condicaoaux );
  
  $total_vales = 0;
  for ($Ivtfdiasant=0; $Ivtfdiasant< count($vtfdiasant); $Ivtfdiasant++) {
    
    //* montar vetor dias a partir do arquivo ( com todos os dias );
    $indice = db_day($vtfdiasant[$Ivtfdiasant]["r63_dia"] );
    $feriado = true;
    if($lotacao[0]["r13_calend"] == ''){
      $condicaoaux  = " where r62_calend = 0";
    }else{
      $condicaoaux  = " where r62_calend = ".db_sqlformat($lotacao[0]["r13_calend"]);
    }
    $condicaoaux .= "   and r62_data = ".db_sqlformat($vtfdiasant[$Ivtfdiasant]["r63_dia"] );
    global $calendf;
    // 	 die("select * from calendf ".$condicaoaux );
    if (db_selectmax("calendf", "select * from calendf ".$condicaoaux )) {
      $feriado = false;
    }
    $qtd_dia = $vtfdiasant[$Ivtfdiasant]["r63_quant"];
    $obrigatorio = (db_boolean($vtfdiasant[$Ivtfdiasant]["r63_obrig"])? "s": "n");
    $quants = $vtfdiasant[$Ivtfdiasant]["r63_quants"];
    if ($feriado  || ( !$feriado && strtolower($obrigatorio) == "s" )) {
      $total_vales += $vtfdiasant[$Ivtfdiasant]["r63_quant"];
    }
    $m_vtfdias01[$indice][0] = $m_vtfdias01[$indice][0];
    $m_vtfdias01[$indice][1] = $qtd_dia;
    $m_vtfdias01[$indice][2] = $obrigatorio;
    $m_vtfdias01[$indice][3] = $feriado;
    $m_vtfdias01[$indice][4] = $quants;
  }
  
}

function deleta_vale_funcionarios($condicao_deleta)
{
  
  global $subpes, $vtffunc_pessoal;
  $condicaoaux  = " select * from vtffunc ";
  $condicaoaux .= "inner join rhpessoalmov  on rh02_anousu = r17_anousu ";
  $condicaoaux .= "                        and rh02_mesusu = r17_mesusu ";
  $condicaoaux .= "                        and rh02_regist = r17_regist ";
  $condicaoaux .= "where rh02_anousu = ".db_sqlformat(db_substr($subpes,1,4))." ";
  $condicaoaux .= "  and rh02_mesusu = ".db_sqlformat(db_substr($subpes,-2))." ";
  $condicaoaux .= "  and rh02_instit = ".db_getsession("DB_instit")." ";
  $condicaoaux .= $condicao_deleta;

  //die($condicaoaux);

  if (db_selectmax("vtffunc_pessoal", $condicaoaux )) {
    
    for ($Ivtffunc_pessoal=0; $Ivtffunc_pessoal<count($vtffunc_pessoal); $Ivtffunc_pessoal++) {
      
      $registro = $vtffunc_pessoal[$Ivtffunc_pessoal]["r17_regist"];
      $codigo   = $vtffunc_pessoal[$Ivtffunc_pessoal]["r17_codigo"];
      
      $condicaoaux  = " where r63_regist  = ".db_sqlformat($registro );
      $condicaoaux .= "   and r63_vale    = ".db_sqlformat($codigo );
      $condicaoaux .= "   and r63_anousu  = ".db_sqlformat(db_substr($subpes,1,4));
      $condicaoaux .= "   and r63_mesusu  = ".db_sqlformat(db_substr($subpes,-2));
      
      db_delete("vtfdias", $condicaoaux );
      
      $condicaoaux  = " where r17_regist  = ".db_sqlformat($registro ) ;
      $condicaoaux .= "   and r17_codigo  = ".db_sqlformat($codigo );
      $condicaoaux .= "   and r17_anousu  = ".db_sqlformat(db_substr($subpes,1,4));
      $condicaoaux .= "   and r17_mesusu  = ".db_sqlformat(db_substr($subpes,-2));
      db_delete("vtffunc", $condicaoaux );
    }
  }
  
}

function montaquadrosemanaapartirdovtfdias()
{
  global $diasdasemana,$m_semana,$m_vtfdias01,$qtdmes;
  $lersemana = array();
  for ($a=1; $a<=7; $a++) {
    $lersemana[$a][1] = 0;
    // quant;
    $lersemana[$a][2] = "n";
    // obrigatorio;
    $lersemana[$a][3] = false;
    // campo de controle;
  }
  for ($y=1; $y<=$qtdmes; $y++) {
    $dsemana = db_dow(db_ctod($m_vtfdias01[$y][0] ) );
    // data
    if (!$lersemana[$dsemana][3]) {
      if ($m_vtfdias01[$y][4] > 0 && $m_vtfdias01[$y][3] == true) {
        $lersemana[$dsemana][1] = $m_vtfdias01[$y][4];
        // quant
        $lersemana[$dsemana][2] = $m_vtfdias01[$y][2];
        // obrigatorio
        $lersemana[$dsemana][3] = true;
      }
    }
  }
  for ($x=1; $x<=7; $x++) {
    if ($lersemana[$x][3]) {
      $m_semana[$x+1][0] = $m_semana[$x+1][0];
      $m_semana[$x+1][1] = $lersemana[$x][1];
      $m_semana[$x+1][2] = $lersemana[$x][2];
    }
  }
}


function calcula_pela_semana($y)
{
  global $subpes,$lotacao,$diasdasemanam,$m_vtfdias01,$m_semana,$total_vales;
  $dia = db_str($y ,2,0,"0")."/".db_substr($subpes,6,2)."/".db_substr($subpes,1,4);
  
  $feriado = true;
  if($lotacao[0]["r13_calend"] == ''){
    $condicaoaux  = " where r62_calend = 0";
  }else{
    $condicaoaux  = " where r62_calend = ".db_sqlformat($lotacao[0]["r13_calend"] );
  }
  
  $condicaoaux .= "   and r62_data = ".db_sqlformat(db_ctod($dia) );
  if (db_selectmax("calendf", "select * from calendf ".$condicaoaux )) {
    $feriado = false;
  }
  $indice = db_dow(db_ctod($dia)) + 1;
  $quants = $m_semana[$indice][1];
  $qtd_dia = 0;
  $qtd_dia_sem = 0;
  if ($qtd_dia == $qtd_dia_sem) {
    $qtd_dia = $m_semana[$indice][1];
  }
  if (!$feriado ) {
    $obrigatorio = $m_vtfdias01[$y][2];
  } else {
    $obrigatorio = $m_semana[$indice][2];
  }
  if (db_empty($obrigatorio)) {
    $obrigatorio = "n";
  }
  if ($feriado || (!$feriado && strtolower($obrigatorio) == "s")) {
    $total_vales += $qtd_dia;
  }
  if ($qtd_dia > 0 ) {
    $qtd_dia = 0;
  }
  $m_vtfdias01[$y][0] = $dia;
  $m_vtfdias01[$y][1] = $qtd_dia;
  $m_vtfdias01[$y][2] = $obrigatorio;
  $m_vtfdias01[$y][3] = $feriado;
  $m_vtfdias01[$y][4] = $quants;
}


db_inicio_transacao();
inicializa_vt();
db_fim_transacao();

?>