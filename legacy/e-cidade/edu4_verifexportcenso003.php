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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label("ed52_i_ano");
$escola   = db_getsession("DB_coddepto");
$db_opcao = 1;
$db_botao = true;
function SiglaUF($uf) {
	
  if ($uf != "") {
  	
    $result = pg_query("SELECT ed260_c_sigla FROM censouf WHERE ed260_i_codigo = $uf");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function Municipio($municipio) {
	
  if ($municipio != "") {
  	
    $result = pg_query("SELECT ed261_c_nome FROM censomunic WHERE ed261_i_codigo = $municipio");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function Distrito($distrito,$municipio) {
	
  if ($distrito != "" && $municipio != "") {
  	
    $result = pg_query("SELECT ed262_c_nome FROM censodistrito 
                        WHERE ed262_i_censomunic = $municipio AND ed262_i_coddistrito = $distrito");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function OrgaoEnsino($orgao,$uf) {

  if ($orgao != "" && $uf != "") { 	 

    $result = pg_query("SELECT ed263_c_nome FROM censoorgreg WHERE ed263_i_censouf = $uf 
                               AND ed263_i_codigocenso = '$orgao'");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function EtapaTurma($etapa) {
	
  if ($etapa != "") {
  	
    $result = pg_query("SELECT ed266_c_descr FROM censoetapa WHERE ed266_i_codigo = $etapa");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function AtivCompl($atividade) {
	
  if ($atividade != "") {
  	
    $result = pg_query("SELECT ed133_c_descr FROM censoativcompl WHERE ed133_i_codigo = $atividade");   
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function NomeAluno($aluno) {
	
  if ($aluno != "") {
  	
    $result = pg_query("SELECT ed47_v_nome FROM aluno WHERE ed47_i_codigo = $aluno");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function NomeDocente($docente) {
	
  if ($docente != "") {
  	
    $result = pg_query("SELECT z01_nome FROM cgm WHERE z01_numcgm = $docente");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function Pais($pais) {
	
  if ($pais != "") {
  	
    $result = pg_query("SELECT ed228_c_descr FROM pais WHERE ed228_i_codigo = $pais");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function OrgaoRG($orgao) {
	
  if ($orgao != "") {
  	
    $result = pg_query("SELECT ed132_c_descr FROM censoorgemissrg WHERE ed132_i_codigo = $orgao");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function CursoSup($curso) {
	
  if ($curso != "") {
  	
    $result = pg_query("SELECT ed94_c_descr FROM cursoformacao WHERE ed94_c_codigocenso = '$curso'");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function InstSup($inst) {
	
  if ($inst != "") {
  	
    $result = pg_query("SELECT ed257_c_nome FROM censoinstsuperior WHERE ed257_i_codigo = $inst");
    return trim(pg_result($result,0,0));
    
  } else {
    return "";
  }
}

function NomeTurma($turma) {
	
  if ($turma != "") {
  	
    $result = pg_query("SELECT ed57_c_descr FROM turma WHERE ed57_i_codigo = $turma");
    if (pg_num_rows($result) > 0) {
    	
      $result1 = pg_query("SELECT ed11_i_codcenso
                                FROM serie
                                     inner join serieregimemat on ed223_i_serie = ed11_i_codigo
                                     inner join turmaserieregimemat on ed220_i_serieregimemat = ed223_i_codigo
                                WHERE ed220_i_turma = $turma");
      return trim(pg_result($result,0,0))." / ".EtapaTurma(trim(pg_result($result1,0,0)));
      
    } else {
    	
      $result1 = pg_query("SELECT ed268_c_descr,ed268_i_tipoatend FROM turmaac WHERE ed268_i_codigo = $turma");
      if (pg_num_rows($result1) > 0) {
        return trim(pg_result($result1,0,0))." / ".(trim(pg_result($result1,0,1))==4?"ATIVIDADE COMPLEMENTAR":"AEE");
      }
    }
    
  } else {
    return "";
  }
}

$array_registro = array("null"=>"","00"=>"Registro 00 - Identifica��o","10"=>"Registro 10 - Autentica��o",
                        "20"=>"Registro 20 - Cadastro de Turma","21"=>"Registro 51 - V�nculo Turma / Docentes",
                        "30"=>"Registro 30 - Dados do Docente","40"=>"Registro 40 - Documentos do Docente",
                        "50"=>"Registro 50 - Dados Vari�veis do Docente","51"=>"Registro 51 - V�nculo Docente / Turmas",
                        "60"=>"Registro 60 - Dados do Aluno","70"=>"Registro 70 - Documentos do Aluno",
                        "80"=>"Registro 80 - V�nculo Aluno / Turmas","81"=>"Registro 80 - V�nculo Turma / Alunos");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td valign="top" bgcolor="#CCCCCC" align="center">
   <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
     <td valign="top" bgcolor="#CCCCCC">
      <br>
      <b><?=$array_registro[$registro]?></b><br><br>
      <?
      if ($registro == "00") {
      	
        $array_situacao       = array("1"=>"EM FUNCIONAMENTO","2"=>"PARALISADA","3"=>"EXTINTA");
        $array_dependencia    = array("1"=>"FEDERAL","2"=>"ESTADUAL","3"=>"MUNICIPAL","4"=>"PRIVADA");
        $array_categprivada   = array(""=>"","1"=>"PARTICULAR","2"=>"COMUNIT�RIA",
                                      "3"=>"CONFESSIONAL","4"=>"FILANTR�PICA");
        $array_credenciamento = array("0"=>"N�O","1"=>"SIM","2"=>"EM TRAMITA��O");
        $array_mantprivada    = array("EMPRESAS / PESSOA F�SICA","SINDICATOS / COOPERATIVAS",
                                      "ONG","INSTITUI��ES SEM FINS LUCRATIVOS");
        $ponteiro = fopen($arquivogerado,"r");
        
        while (!feof($ponteiro)) {
        	
          $linha         = " ".fgets($ponteiro);
          $explode_linha = explode("|",$linha);
          
          if ($explode_linha[0] == "00") {
          	 
            if (trim($explode_linha[27]) != "") {
            	
              $mantprivada     = "";
              $arq_mantprivada = trim($explode_linha[27]);
              
              for ($t = 0; $t < 4; $t++) {
              	
                if (substr($arq_mantprivada,$t,1) == "1") {
                  $mantprivada .= "<br> ->".$array_mantprivada[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[21]) != "") {
            	
              $dep             = "";
              $arq_dependencia = trim($explode_linha[21]);
              
              for ($r = 0; $r < 4; $r++) {
              	
                if (substr($arq_dependencia,$r,1) == "1") {
                  $dep .= "<br> ->".$array_dependencia[$r];
                }
                
              }
            }
            
            if (trim($explode_linha[23]) != "") {
            	
              $categprivada = "";
              $arq_categoriaprivada = trim($explode_linha[23]);
              
              for ($u = 0; $u < 5; $u++) {
              	
                if (substr($arq_categoriaprivada,$u,1) == "1") {
                  $categprivada .= "<br> ->".$array_categprivada[$u];
                }
                
              }
            }
            
            if (trim($explode_linha[33]) != "") {
            	
              $credenciamento     = "";
              $arq_credenciamento = trim($explode_linha[33]);
              
              for ($s = 0; $s < 3; $s++) {
              	
                if (substr($arq_credenciamento,$s,1) == "1") {
                  $credenciamento .= "<br> ->".$array_credenciamento[$s];
                }
                
              }
            }
            $iAnoInicio  = substr(trim($explode_linha[3]),0,2)."/".substr(trim($explode_linha[3]),2,2);
            $iAnoInicio .= "/".substr(trim($explode_linha[3]),4,4);
            $iAnoFim     = substr(trim($explode_linha[4]),0,2)."/".substr(trim($explode_linha[4]),2,2);
            $iAnoFim    .= "/".substr(trim($explode_linha[4]),4,4); 
            $array = array("C�digo INEP                   : <b> ".trim($explode_linha[1])."</b>",
                           "Situa��o de Funcionamento     : <b> ".$array_situacao[trim($explode_linha[2])]."</b>",
                           "In�cio do Ano Letivo          : <b> ".$iAnoInicio."</b>",
                           "T�rmino do Ano Letivo         : <b> ".$iAnoFim."</b>",
                           "Nome da Escola                : <b> ".trim($explode_linha[5])."</b>",
                           "CEP                           : <b> ".trim($explode_linha[6])."</b>",
                           "Endere�o                      : <b> ".trim($explode_linha[7])."</b>",
                           "N�mero                        : <b> ".trim($explode_linha[8])."</b>",
                           "Complemento                   : <b> ".trim($explode_linha[9])."</b>",
                           "Bairro                        : <b> ".trim($explode_linha[10])."</b>",
                           "UF                            : <b> ".SiglaUF(trim($explode_linha[11]))."</b>",
                           "Munic�pio                     : <b> ".Municipio(trim($explode_linha[12]))."</b>",
                           "Distrito                      : <b> ".Distrito($explode_linha[13],$explode_linha[12])."</b>",
                           "DDD                           : <b> ".trim($explode_linha[14])."</b>",
                           "Telefone                      : <b> ".trim($explode_linha[15])."</b>",
                           "Telefone 1                    : <b> ".trim($explode_linha[16])."</b>",
                           "Telefone 2                    : <b> ".trim($explode_linha[17])."</b>",
                           "Telefone 3                    : <b> ".trim($explode_linha[18])."</b>",
                           "Email                         : <b> ".trim($explode_linha[19])."</b>",
                           "�rg�o Regional de Ensino      : <b> ".OrgaoEnsino($explode_linha[20],$explode_linha[11])."</b>",
                           "Depend�ncia Administrativa    : <b> ".$dep."</b>",
                           "Zona                          : <b> ".($explode_linha[22]=="1"?"URBANA":"RURAL")."</b>",
                           "Categoria de Escola Privada   : <b> ".@$categprivada."</b>",
                           "Conveniada com Poder P�blico  : <b> ".$explode_linha[24]=="1"?"ESTADUAL":$explode_linha[24]=="2"?"MUNICIPAL":""."</b>",
                           "N� CNAS                       : <b> ".trim($explode_linha[25])."</b>",
                           "N� CEBAS                      : <b> ".trim($explode_linha[26])."</b>",
                           "Mantenedora da Escola Privada : <b> ".@$mantprivada."</b>", //27 a 30
                           "CNPJ Mantenedora Privada      : <b> ".trim($explode_linha[31])."</b>",
                           "CNPJ Escola Privada           : <b> ".trim($explode_linha[32])."</b>",                          
                           "Credenciamento                : <b> ".$credenciamento."</b>"
                          );
          }
        }
        fclose($ponteiro);
      }
      
      if ($registro == "10") {
      	
        $array_aee_ativ     = array(""=>"","0"=>"N�O OFERECE","1"=>"N�O EXCLUSIVAMENTE","2"=>"EXCLUSIVAMENTE");
        $array_locdifer     = array("0"=>"N�O SE APLICA","1"=>"�REA DE ASSENTAMENTO","2"=>"TERRA IND�GENA",
                                    "3"=>"�REA REMANESCENTE DE QUILOMBOS");
        $array_localizacao  = array("PR�DIO ESCOLAR","TEMPLO/IGREJA","SALAS DE EMPRESA","CASA DO PROFESSOR",
                                    "SALAS EM OUTRA ESCOLA","GALP�O/RANCHO/PAIOL/BARRAC�O",
                                    "UNIDADE DE INTERNA��O/PRISIONAL","OUTROS");
        $array_abastagua    = array("REDE P�BLICA","PO�O ARTESIANO","CACIMBA/CISTERNA/PO�O",
                                    "FONTE/RIO/IGARAP�/RIACHO/C�RREGO","INEXISTENTE");
        $array_abastenergia = array("REDE P�BLICA","GERADOR","OUTROS (ENERGIA ALTERNATIVA)","INEXISTENTE");
        $array_esgoto       = array("REDE P�BLICA","FOSSA","INEXISTENTE");
        $array_lixo         = array("COLETA PERI�DICA","QUEIMA","JOGA EM OUTRA �REA","RECICLA","ENTERRA","OUTROS");
        $array_dependencia  = array("DIRETORIA","SALA DE PROFESSORES","LABORAT�RIO DE INFORM�TICA",
                                    "LABORAT�RIO DE CI�NCIAS","SALA DE RECURSOS MULTIFUNCIONAIS","QUADRA DE ESPORTES",
                                    "COZINHA","BIBLIOTECA","SALA DE LEITURA","PARQUE INFANTIL","BER��RIO",
                                    "SANIT�RIO FORA DO PR�DIO","SANIT�RIO DENTRO DO PR�DIO",
                                    "SANIT�RIO ADEQUADO � EDUCA��O INFANTIL","SANIT�RIO P/ DEFICIENTES",
                                    "DEPEND�NCIA ADEQUADA P/ DEFICIENTES","NENHUMA DEPEND�NCIA");
        $array_equipamento  = array("APARELHO TELEVIS�O","VIDEOCASSETE","DVD","ANTENA PARAB�LICA",
                                    "COPIADORA","RETROPROJETOR","IMPRESSORA");
        $array_modalidade   = array("REGULAR","ESPECIAL","EDUCA��O DE JOVENS E ADULTOS");
        $array_etapa        = array("REGULAR (EDUCA��O INFANTIL - CRECHE)","REGULAR (EDUCA��O INFANTIL - PR�-ESCOLA)",
                                    "REGULAR (ENSINO FUNDAMENTAL - 8 ANOS)","REGULAR (ENSINO FUNDAMENTAL - 9 ANOS)",
                                    "REGULAR (ENSINO M�DIO - M�DIO)","REGULAR (ENSINO M�DIO - INTEGRADO)",
                                    "REGULAR (ENSINO M�DIO - NORMAL/MAGIST�RIO)",
                                    "REGULAR (ENSINO M�DIO - EDUCA��O PROFISSIONAL)",
                                    "ESPECIAL (EDUCA��O INFANTIL - CRECHE)","ESPECIAL (EDUCA��O INFANTIL - PR�-ESCOLA)",
                                    "ESPECIAL (ENSINO FUNDAMENTAL - 8 ANOS)","ESPECIAL (ENSINO FUNDAMENTAL - 9 ANOS)",
                                    "ESPECIAL (ENSINO M�DIO - M�DIO)","ESPECIAL (ENSINO M�DIO - INTEGRADO)",
                                    "ESPECIAL (ENSINO M�DIO - NORMAL/MAGIST�RIO)",
                                    "ESPECIAL (ENSINO M�DIO - EDUCA��O PROFISSIONAL)",
                                    "ESPECIAL (EJA - ENSINO FUNDAMENTAL)","ESPECIAL (EJA - ENSINO M�DIO)",
                                    "EJA (ENSINO FUNDAMENTAL)","EJA (ENSINO M�DIO)");
        $array_mater        = array("N�O UTILIZA","QUILOMBOLA","IND�GENA");
        $array_lingua       = array("L�NGUA IND�GENA","L�NGUA PORTUGUESA");
        $ponteiro           = fopen($arquivogerado,"r");
        
        while (!feof($ponteiro)) {
        	
          $linha         = " ".fgets($ponteiro);
          $explode_linha = explode("|",$linha);
          
          if ($explode_linha[0] == "10") {
          	
            if (trim($explode_linha[6]) != "") {
            	
              $localizacao     = "";
              $arq_localizacao = trim($explode_linha[6]);
              
              for ($t = 0; $t < 8; $t++) {
              	
                if (substr($arq_localizacao,$t,1) == "1") {
                  $localizacao .= "<br> ->".$array_localizacao[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[23]) != "") {
            	
              $abastagua     = "";
              $arq_abastagua = trim($explode_linha[23]);
              
              for ($t = 0; $t < 5; $t++) {
              	
                if (substr($arq_abastagua,$t,1) == "1") {
                  $abastagua .= "<br> ->".$array_abastagua[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[28]) != "") {
            	
              $abastenergia     = "";
              $arq_abastenergia = trim($explode_linha[28]);
              
              for ($t = 0; $t < 4; $t++) {
              	
                if (substr($arq_abastenergia,$t,1) == "1") {
                  $abastenergia .= "<br> ->".$array_abastenergia[$t];
                }
                
              }              
            }
            
            if (trim($explode_linha[32]) != "") {
            	
              $esgoto     = "";
              $arq_esgoto = trim($explode_linha[32]);
              
              for ($t = 0; $t < 3; $t++) {
              	
                if (substr($arq_esgoto,$t,1) == "1") {
                  $esgoto .= "<br> ->".$array_esgoto[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[35]) != "") {
            	
              $lixo     = "";
              $arq_lixo = trim($explode_linha[35]);
              
              for ($t = 0; $t < 6; $t++) {
              	
                if (substr($arq_lixo,$t,1) == "1") {
                  $lixo .= "<br> ->".$array_lixo[$t];
                }
                 
              }
            }
            
            if (trim($explode_linha[41]) != "") {
            	
              $dependencia     = "";
              $arq_dependencia = trim($explode_linha[41]);
              
              for ($t = 0; $t < 18; $t++) {
              	
                if (substr($arq_dependencia,$t,1) == "1") {
                  $dependencia .= "<br> ->".$array_dependencia[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[61]) != "") {
            	
              $equipamento     = "";
              $arq_equipamento = trim($explode_linha[61]);
              
              for ($t = 0; $t < 7; $t++) {
              	
                if (substr($arq_equipamento,$t,1) == "1") {
                  $equipamento .= "<br> ->".$array_equipamento[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[78]) != "") {
            	
              $modalidade     = "";
              $arq_modalidade = trim($explode_linha[78]);
              
              for ($t = 0; $t < 3; $t++) {
              	
                if (substr($arq_modalidade,$t,1) == "1") {
                  $modalidade .= "<br> ->".$array_modalidade[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[81]) != "") {
            	
              $etapa     = "";
              $arq_etapa = trim($explode_linha[81]);
              
              for ($t = 0; $t < 20; $t++) {
              	
                if (substr($arq_etapa,$t,1) == "1") {
                  $etapa .= "<br> ->".$array_etapa[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[103]) != "") {
            	
              $mater     = "";
              $arq_mater = trim($explode_linha[103]);
              
              for ($t = 0; $t < 3; $t++) {
              	
                if (substr($arq_mater,$t,1) == "1") {
                  $mater .= "<br> ->".$array_mater[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[107]) != "") {
            	
              $lingua     = "";
              $arq_lingua = trim($explode_linha[107]);
              
              for ($t = 0; $t < 2; $t++) {
              	
                if (substr($arq_lingua,$t,1) == "1") {
                  $lingua .= "<br> ->".$array_lingua[$t];
                }
                
              } 
            }
            
            $array = array("C�digo INEP                              : <b> ".trim($explode_linha[1])."</b>",
                           "Nome do Diretor / Respons�vel            : <b> ".trim($explode_linha[3])."</b>",
                           "N� do CPF                                : <b> ".trim($explode_linha[2])."</b>",
                           "Cargo do Diretor / Respons�vel           : <b> ".trim($explode_linha[4])."</b>",
                           "Email do Diretor / Respons�vel           : <b> ".trim($explode_linha[5])."</b>
                            <br><br><b>Registro 10 - Caracteriza��o e Infra-estrutura</b><br>",
                           "Local de Funcionamento                   : <b> ".@$localizacao."</b>",
                           "Pr�dio Compartilhado                     : <b> ".
                            (trim($explode_linha[15])=="0"?"N�O":"SIM")."</b>",
                           "Escolas com a qual compartilha           : <b> ".trim($explode_linha[16])."</b>",
                           "�gua Consumida pelos Alunos              : <b> ".
                            (trim($explode_linha[22])=="1"?"N�O FILTRADA":"FILTRADA")."</b>",
                           "Abastecimento de �gua                    : <b> ".@$abastagua."</b>",
                           "Abastecimento de Energia                 : <b> ".@$abastenergia."</b>",
                           "Esgoto Sanit�rio                         : <b> ".@$esgoto."</b>",
                           "Destina��o do Lixo                       : <b> ".@$lixo."</b>",
                           "Depend�ncias Existentes                  : <b> ".@$dependencia."</b>",
                           "N� de Salas de Aulas Existentes          : <b> ".trim($explode_linha[59])."</b>",
                           "N� de Salas Utilizadas como Sala de Aula : <b> ".trim($explode_linha[60])."</b>",
                           "Equipamentos                             : <b> ".@$equipamento."</b>",
                           "Computadores                             : <b> ".
                            (trim($explode_linha[68])=="0"?"N�O POSSUI":"POSSUI")."</b>",
                           "Qtde.Computadores na Escola              : <b> ".trim($explode_linha[69])."</b>",
                           "Qtde.Computadores Uso Administrativo     : <b> ".trim($explode_linha[70])."</b>",
                           "Qtde.Computadores Uso Alunos             : <b> ".trim($explode_linha[71])."</b>",
                           "Acesso � Internet                        : <b> ".
                            (trim($explode_linha[72])=="0"?"N�O":"SIM")."</b>",
                           "Banda Larga                              : <b> ".
                            (trim($explode_linha[73])=="0"?"N�O POSSUI":"POSSUI")."</b>",
                           "Total de Funcion�rios                    : <b> ".trim($explode_linha[74])."</b>",
                           "Alimenta��o Escolar                      : <b> ".
                            (trim($explode_linha[75])=="0"?"N�O OFERECE":"OFERECE")."</b><br>
                            <br><b>Registro 10 - Dados Educacionais</b><br>",
                           "Atend. Educacional Especial              : <b> ".
                            $array_aee_ativ[trim($explode_linha[76])]."</b>",
                           "Atividade Complementar                   : <b> ".
                            $array_aee_ativ[trim($explode_linha[77])]."</b>",
                           "Modalidades                              : <b> ".@$modalidade."</b>",
                           "Etapas                                   : <b> ".@$etapa."</b>",
                           "Ensino Fundamental em ciclos             : <b> ".
                            (trim($explode_linha[101])=="0"?"N�O":"SIM")."</b>",
                           "Localiza��o Diferenciada                 : <b> ".
                            $array_locdifer[trim($explode_linha[102])]."</b>",
                           "Materiais Did�ticos                      : <b> ".@$mater."</b>",
                           "Educa��o Ind�gena                        : <b> ".
                            (trim($explode_linha[106])=="0"?"N�O":"SIM")."</b>",
                           "L�ngua em que o ensino � ministrado      : <b> ".@$lingua."</b>",
                           "C�digo da L�ngua Ind�gena                : <b> ".trim($explode_linha[109])."</b>"
                          );
          }
        }
        fclose($ponteiro);
      }
      if ($registro == "20") {
      	
        $array_tipoaee    = array("SISTEMA BRAILE","ATIVIDADES DA VIDA AUT�NOMA","RECURSOS PARA ALUNOS COM BAIXA VIS�O",
                                  "DESENVOLVIMENTO DE PROCESSOS MENTAIS","ORIENTA��O E MOBILIDADE",
                                  "L�NGUA BRASILEIRA DE SINAIS","COMUNICA��O ALTERNATIVA E AUMENTATIVA",
                                  "ATIVIDADES DE ENRIQUECIMENTO CURRICULAR","SOROBAN","INFORM�TICA ACESS�VEL",
                                  "L�NGUA PORTUGUESA NA MODALIDADE ESCRITA");
        $array_tipoatend  = array("0"=>"N�O SE APLICA","1"=>"CLASSE HOSPITALAR","2"=>"UNIDADE DE INTERNA��O",
                                  "3"=>"UNIDADE PRISIONAL","4"=>"ATIVIDADE COMPLEMENTAR",
                                  "5"=>"ATEND. EDUCACIONAL ESPECIAL");
        $array_modalidade = array(""=>"","1"=>"REGULAR","2"=>"ESPECIAL","3"=>"EDUCA��O DE JOVENS E ADULTOS");
        $array_disciplina = array("QU�MICA","F�SICA","MATEM�TICA","BIOLOGIA","CI�NCIAS",
                                  "L�NGUA / LITERATURA PORTUGUESA","L�NGUA / LITERATURA ESTRANGEIRA - INGL�S",
                                  "L�NGUA / LITERATURA ESTRANGEIRA - ESPANHOL",
                                  "L�NGUA / LITERATURA ESTRANGEIRA - OUTRA",
                                  "ARTES (EDUCA��O ART�STICA, TEATRO, DAN�A, M�SICA, ARTES PL�STICAS E OUTRAS)",
                                  "EDUCA��O F�SICA","HIST�RIA","GEOGRAFIA","FILOSOFIA","ESTUDOS SOCIAIS/SOCIOLOGIA",
                                  "INFORM�TICA/COMPUTA��O","DISCIPLINAS PROFISSIONALIZANTES",
                                  "DISCIPLINAS VOLTADAS AO ATENDIMENTO DE NECESSIDADES ESPECIAIS (DISCIPLINAS PEDAG�GICAS)",
                                  "DISCIPLINAS VOLTADAS � DIVERSIDADE S�CIO-CULTURAL (DISCIPLINAS PEDAG�GICAS)",
                                  "LIBRAS","DISCIPLINAS PEDAG�GICAS","ENSINO RELIGIOSO",
                                  "LINGUA IND�GENA","OUTRAS DISCIPLINAS");
        $ponteiro         = fopen($arquivogerado,"r");
        while (!feof($ponteiro)) {
        	
          $linha         = " ".fgets($ponteiro);
          $explode_linha = explode("|",$linha);
          if ($explode_linha[0] == "20" && $explode_linha[3] == $codigoturma) {  
          	
            if (trim($explode_linha[12]) != "") {
            	
              $tipoaee     = "";
              $arq_tipoaee = trim($explode_linha[12]);
              
              for ($t = 0; $t < 5; $t++) {
              	
                if (substr($arq_tipoaee,$t,1) == "1") {
                  $tipoaee .= "<br> ->".$array_tipoaee[$t];
                }
                 
              }
            }
              $disciplinas     = "";                      
            if (trim($explode_linha[16]) != "") {
            	
              $disciplinas     = "";
              $arq_disciplina = trim($explode_linha[16]);
              
              for ($t = 0; $t < 24; $t++) {
              	
                if (substr($arq_disciplina,$t,1) == "1") {
                  $disciplinas .= "<br> ->".$array_disciplina[$t];
                }
                
              }
            } 

            
//            $disciplinas = "";
//           if (trim($explode_linha[16]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[16])];
//           }
//           if (trim($explode_linha[17]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[17])];
//           }
//           if (trim($explode_linha[18]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[18])];
//           }
//           if (trim($explode_linha[19]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[19])];
//           }
//           if (trim($explode_linha[20]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[20])];
//           }
//           if (trim($explode_linha[21]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[21])];
//           }
//           if (trim($explode_linha[22]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[22])];
//           }
//           if (trim($explode_linha[23]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[23])];
//           }
//           if (trim($explode_linha[24]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[24])];
//           }
//           if (trim($explode_linha[25]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[25])];
//           }
//           if (trim($explode_linha[26]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[26])];
//           }
//           if (trim($explode_linha[27]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[27])];
//           }
//           if (trim($explode_linha[28]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[28])];
//           }
//          if (trim($explode_linha[29]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[29])];
//           }
//          if (trim($explode_linha[30]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[30])];
//           }
//          if (trim($explode_linha[31]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[31])];
//           }
//          if (trim($explode_linha[32]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[32])];
//           }
//          if (trim($explode_linha[33]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[33])];
//           }
//          if (trim($explode_linha[34]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[34])];
//           }
//          if (trim($explode_linha[35]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[35])];
//           }
//          if (trim($explode_linha[36]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[36])];
//           }
//          if (trim($explode_linha[37]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[37])];
//           }
//          if (trim($explode_linha[38]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[38])];
//           }
//          if (trim($explode_linha[39]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[39])];
//           }
            $array = array("C�digo INEP da Escola               : <b> ".trim($explode_linha[1])."</b>",
                           "C�digo INEP da Turma                : <b> ".trim($explode_linha[2])."</b>",
                           "C�digo da Turma na Escola           : <b> ".trim($explode_linha[3])."</b>",
                           "Nome da Turma                       : <b> ".trim($explode_linha[4])."</b>",
                           "Hor�rio da Turma                    : <b> ".
                            trim($explode_linha[5]).":".trim($explode_linha[6]).
                            " �s ".trim($explode_linha[7]).":".trim($explode_linha[8])."</b>",
                           "Tipo de Atendimento                 : <b> ".@$array_tipoatend[trim($explode_linha[9])]."</b>",
                           "Frequ�ncia Semanal da Atividade/AEE : <b> ".
                            trim($explode_linha[10]).(trim($explode_linha[10])!=""?" VEZ".
                            (trim($explode_linha[10])>1?"ES":"")." POR SEMANA":"")."</b>",
                           "C�digo Atividade 1                  : <b> ".
                            trim($explode_linha[11]).(trim($explode_linha[11])!=""?" - ".
                            AtivCompl(trim($explode_linha[11])):"")."</b>",
                           "C�digo Atividade 2                  : <b> ".trim($explode_linha[11])."</b>",
                           "C�digo Atividade 3                  : <b> ".trim($explode_linha[11])."</b>",
                           "C�digo Atividade 4                  : <b> ".trim($explode_linha[11])."</b>",
                           "C�digo Atividade 5                  : <b> ".trim($explode_linha[11])."</b>",
                           "C�digo Atividade 6                  : <b> ".trim($explode_linha[11])."</b>",
                           "Tipo de Atend. Educacional Especial : <b> ".@$tipoaee."</b>",
                           "Modalidade                          : <b> ".$array_modalidade[trim($explode_linha[13])]."</b>",
                           "Etapa                               : <b> ".EtapaTurma(trim($explode_linha[14]))."</b>",
                           "Curso Profiss                       : <b> ".trim($explode_linha[15])."</b>",                        
                           "Disciplinas                         : <b> ".$disciplinas."</b>"
                          );
          }
        }
        fclose($ponteiro);
      }
      
       if($registro=="21"){
       $ponteiro = fopen($arquivogerado,"r");
       $array[] = "";
       while (!feof($ponteiro)){
        $linha = " ".fgets($ponteiro);
        $explode_linha = explode("|",$linha);

        if($codigoturma==trim(@$explode_linha[3])){
         $codigoturma = trim($explode_linha[3]);
         $descrturma = trim($explode_linha[4]);
         $etapaturma = EtapaTurma(trim($explode_linha[25]));
         $array[] = "Turma: <b>".NomeTurma(trim($explode_linha[3]))." / ".$etapaturma."</b><br><br>Docentes:";
        }
        if($explode_linha[0]=="51" && trim($explode_linha[5])==$codigoturma){
         $array[] = "<b>".trim($explode_linha[3])." - ".NomeDocente(trim($explode_linha[3]))."</b>";
        }
       }
       fclose($ponteiro);
      }
      
      if($registro=="81"){
       $ponteiro = fopen($arquivogerado,"r");
       $array[] = "";
       while (!feof($ponteiro)){
        $linha = " ".fgets($ponteiro,500);
       $explode_linha = explode("|",$linha);
        if(trim(@$explode_linha[5])==$codigoturma){
         $codigoturma = trim(@$explode_linha[5]);
         $descrturma = NomeTurma(trim($explode_linha[5]));
        // $etapaturma = EtapaTurma(trim(substr($linha,183,2)));
         $array[] = "Turma: <b>".$descrturma."</b><br><br>Alunos:";
        }
        if($explode_linha[0]=="80" && trim($explode_linha[5])==$codigoturma){
         $array[] = "<b>".trim($explode_linha[3])." - ".NomeAluno(trim($explode_linha[3]))."</b>";
        }
       }
       fclose($ponteiro);
      }
      
      
      
      if ($registro == "30") {
        $array_raca          = array("0"=>"N�O DECLARADO","1"=>"BRANCA","2"=>"PRETA","3"=>"PARDA",
                                     "4"=>"AMARELA","5"=>"IND�GENA");
        $array_nacionalidade = array("1"=>"BRASILEIRA","2"=>"BRASILEIRA NO EXTERIOR","3"=>"ESTRANGEIRA");
        $ponteiro            = fopen($arquivogerado,"r");
        while (!feof($ponteiro)) {
        	
          $linha         = " ".fgets($ponteiro);
          $explode_linha = explode("|",$linha);
          
          if ($explode_linha[0] == "30" && $explode_linha[3] == $codigodocente) {
          	 
            $array = array("C�digo INEP da Escola       : <b> ".trim($explode_linha[1])."</b>",
                           "C�digo INEP do Docente      : <b> ".trim($explode_linha[2])."</b>",
                           "C�digo do Docente na Escola : <b> ".trim($explode_linha[3])."</b>",
                           "Nome                        : <b> ".trim($explode_linha[4])."</b>",
                           "Email                       : <b> ".trim($explode_linha[5])."</b>",
                           "NIS                         : <b> ".trim($explode_linha[6])."</b>",
                           "Data de Nascimento          : <b> ".
                            (substr(trim($explode_linha[7]),0,2)."/".substr(trim($explode_linha[7]),2,2).
                            "/".substr(trim($explode_linha[7]),4,4))."</b>",
                           "Sexo                        : <b> ".(trim($explode_linha[8])=="1"?"MASCULINO":"FEMININO")."</b>",
                           "Ra�a                        : <b> ".$array_raca[trim($explode_linha[9])]."</b>",
                           "Nome da M�e                 : <b> ".trim($explode_linha[10])."</b>",
                           "Nacionalidade               : <b> ".$array_nacionalidade[trim($explode_linha[11])]."</b>",
                           "Pa�s de Origem              : <b> ".Pais(trim($explode_linha[12]))."</b>",
                           "UF de Nascimento            : <b> ".SiglaUF(trim($explode_linha[13]))."</b>",
                           "Munic�pio de Nascimento     : <b> ".Municipio(trim($explode_linha[14]))."</b>"
                          );
          }
        }
        fclose($ponteiro);
      }
      
      if ($registro == "40") {
      	
        $ponteiro = fopen($arquivogerado,"r");
        while (!feof($ponteiro)) {
        	
          $linha         = " ".fgets($ponteiro);
          $explode_linha = explode("|",$linha); 
                 
          if ($explode_linha[0] == "40" && trim($explode_linha[3]) == $codigodocente) {
          	
            $array = array("C�digo INEP da Escola       : <b> ".trim($explode_linha[1])."</b>",
                           "C�digo INEP do Docente      : <b> ".trim($explode_linha[2])."</b>",
                           "C�digo do Docente na Escola : <b> ".trim($explode_linha[3])." - ".$nomedocente."</b>",
                           "N� do CPF                   : <b> ".trim($explode_linha[4])."</b>",
                           "CEP                         : <b> ".trim($explode_linha[5])."</b>",
                           "Endere�o                    : <b> ".trim($explode_linha[6])."</b>",
                           "N� Endere�o                 : <b> ".trim($explode_linha[7])."</b>",
                           "Complemento                 : <b> ".trim($explode_linha[8])."</b>",
                           "Bairro                      : <b> ".trim($explode_linha[9])."</b>",
                           "UF de Endere�o              : <b> ".SiglaUF(trim($explode_linha[10]))."</b>",
                           "Munic�pio de Endere�o       : <b> ".Municipio(trim($explode_linha[11]))."</b>"
                          );
          }
        }
        fclose($ponteiro);
      }

      if ($registro == "50") { 
      	
        $array_escolaridade = array("1"=>"FUNDAMENTAL INCOMPLETO","2"=>"FUNDAMENTAL COMPLETO",
                                    "3"=>"ENSINO M�DIO - NORMAL/MAGIST�RIO","4"=>"ENSINO M�DIO - NORMAL/MAGIST�RIO IND�GENA",
                                    "5"=>"ENSINO M�DIO","6"=>"SUPERIOR COMPLETO");
        $array_posgraduacao = array("ESPECIALIZA��O","MESTRADO","DOUTORADO","NENHUM");
        $array_outroscursos = array("ESPEC�FICO PARA CRECHE","ESPEC�FICO PARA PR�-ESCOLA",
                                    "ESPEC�FICO PARA EDUCA��O ESPECIAL","ESPEC�FICO PARA EDUCA��O IND�GENA",
                                    "INTERCULTURAL/DIVERSIDADE/OUTROS","NENHUM");
        $ponteiro           = fopen($arquivogerado,"r");
        
        while (!feof($ponteiro)) {
        	
          $linha         = " ".fgets($ponteiro);
          $explode_linha = explode("|",$linha);
                 
          if ($explode_linha[0] == "50" && trim($explode_linha[3]) == $codigodocente) {
          	
            $formacao = "";
            if (trim($explode_linha[5]) != "") {
            	
              $formacao .= "<hr>Licenciatura 1: <b>".(trim($explode_linha[10])=="0"?"N�O":"SIM")."</b>";
              $formacao .= "<br> Curso Superior 1: <b>".CursoSup(trim($explode_linha[7]))."</b>";
              $formacao .= "<br> Ano Conclus�o 1: <b>".trim($explode_linha[9])."</b>";
              $formacao .= "<br> Tipo de Institui��o 1: <b>".(trim($explode_linha[3])=="1"?"P�BLICA":"PRIVADA")."</b>";
              $formacao .= "<br> Institui��o 1: <b>".InstSup(trim($explode_linha[11]))."</b><hr>";
              
            }
            
            if (trim($explode_linha[13]) != "") {
            	
              $formacao .= "Licenciatura 2: <b>".(trim($explode_linha[13])=="0"?"N�O":"SIM")."</b>";
              $formacao .= "<br> Curso Superior 2: <b>".CursoSup(trim($explode_linha[14]))."</b>";
              $formacao .= "<br> Ano Conclus�o 2: <b>".trim($explode_linha[16])."</b>";
              $formacao .= "<br> Tipo de Institui��o 2: <b>".(trim($explode_linha[17])=="1"?"P�BLICA":"PRIVADA")."</b>";
              $formacao .= "<br> Institui��o 2: <b>".InstSup(trim($explode_linha[18]))."</b><hr>";
              
            }
            
            if (trim($explode_linha[20]) != "") {
            	
              $formacao .= "Licenciatura 3: <b>".(trim($explode_linha[20])=="0"?"N�O":"SIM")."</b>";
              $formacao .= "<br> Curso Superior 3: <b>".CursoSup(trim($explode_linha[21]))."</b>";
              $formacao .= "<br> Ano Conclus�o 3: <b>".trim(@$explode_linha[23])."</b>";
              $formacao .= "<br> Tipo de Institui��o 3: <b>".(trim(@$explode_linha[24])=="1"?"P�BLICA":"PRIVADA")."</b>";
              $formacao .= "<br> Institui��o 3: <b>".InstSup(trim(@$explode_linha[25]))."</b><hr>";
              
            }
            
            if (trim($explode_linha[6]) != "") {
            	
              $posgraduacao     = "";
              $arq_posgraduacao = trim($explode_linha[6]);
              
              for ($t = 0; $t < 4; $t++) {
              	
                if (substr($arq_posgraduacao,$t,1) == "1") {
                  $posgraduacao .= "<br> ->".$array_posgraduacao[$t];
                }
                
              }
            }
            
            if (trim($explode_linha[7]) != "") {
            	
              $outroscursos     = "";
              $arq_outroscursos = trim($explode_linha[7]);
              
              for ($t = 0; $t < 6; $t++) {
              	
                if (substr($arq_outroscursos,$t,1) == "1") {
                  $outroscursos .= "<br> ->".$array_outroscursos[$t];
                }
                
              }
            }
            
            $array = array("C�digo INEP da Escola       : <b> ".trim($explode_linha[1])."</b>",
                           "C�digo INEP do Docente      : <b> ".trim($explode_linha[2])."</b>",
                           "C�digo do Docente na Escola : <b> ".trim($explode_linha[3])." - ".$nomedocente."</b>",
                           "Escolaridade                : <b> ".$array_escolaridade[trim($explode_linha[4])]."</b>",
                           "".@$formacao."</b>",
                           "P�s-Gradua��o               : <b> ".@$posgraduacao."</b>",
                           "Outros Cursos               : <b> ".@$outroscursos."</b>"
                          );
         }
       }
       fclose($ponteiro);
      }

     if ($registro == "51") {
     	
       $array_funcao     = array("1"=>"DOCENTE","2"=>"AUXILIAR DE EDUCA��O INFANTIL",
                                 "3"=>"MONITOR DE ATIVIDADE COMPLEMENTAR/AEE");
       $array_disciplina = array("QU�MICA","F�SICA","MATEM�TICA","BIOLOGIA","CI�NCIAS",
                                 "L�NGUA / LITERATURA PORTUGUESA","L�NGUA / LITERATURA ESTRANGEIRA - INGL�S",
                                 "L�NGUA / LITERATURA ESTRANGEIRA - ESPANHOL",
                                 "L�NGUA / LITERATURA ESTRANGEIRA - OUTRA",
                                 "ARTES (EDUCA��O ART�STICA, TEATRO, DAN�A, M�SICA, ARTES PL�STICAS E OUTRAS)",
                                 "EDUCA��O F�SICA","HIST�RIA","GEOGRAFIA","FILOSOFIA",
                                 "ESTUDOS SOCIAIS/SOCIOLOGIA","INFORM�TICA/COMPUTA��O",
                                 "DISCIPLINAS PROFISSIONALIZANTES",
                                 "DISCIPLINAS VOLTADAS AO ATENDIMENTO DE NECESSIDADES ESPECIAIS (DISCIPLINAS PEDAG�GICAS)",
                                 "DISCIPLINAS VOLTADAS � DIVERSIDADE S�CIO-CULTURAL (DISCIPLINAS PEDAG�GICAS)",
                                 "LIBRAS","DISCIPLINAS PEDAG�GICAS","ENSINO RELIGIOSO",
                                 "LINGUA IND�GENA","OUTRAS DISCIPLINAS");
       $primeiro         = 0;
       $contavinculo     = 1;
       $ponteiro         = fopen($arquivogerado,"r");
       while (!feof($ponteiro)) {
       	
         $linha         = " ".fgets($ponteiro);
         $explode_linha = explode("|",$linha);
         $nome_docente = $nomedocente;
         $nometurma    = $nometurma;
         if ($explode_linha[0] == "51" && trim($explode_linha[3]) == $codigodocente) {
         	
           if ($primeiro == 0) {
           	
             $array = array("C�digo INEP da Escola:<b> ".trim($explode_linha[1])."</b>",
                         "C�digo INEP do Docente:<b> ".trim($explode_linha[2])."</b>",
                         "C�digo do Docente na Escola:<b> ".trim($explode_linha[3])." - ".$nome_docente."</b><br>");
             
             for ($t = 0; $t < count($array); $t++) {
               echo $array[$t]."<br>";
             }
             $primeiro = 1;
           }
         
//           if (trim($explode_linha[8]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[8])];
//           } 
//           if (trim($explode_linha[9]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[9])];
//           }
//           if (trim($explode_linha[10]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[10])];
//           }
//           if (trim($explode_linha[11]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[11])];
//           }
//           if (trim(@$explode_linha[12]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[12])];
//           }
//           if (trim(@$explode_linha[13]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[13])];
//           }
//           if (trim(@$explode_linha[14]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[14])];
//           }
//           if (trim(@$explode_linha[15]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[15])];
//           }
//           if (trim(@$explode_linha[16]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[16])];
//           }
//           if (trim(@$explode_linha[17]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[17])];
//           }
//           if (trim(@$explode_linha[18]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[18])];
//           }
//           if (trim(@$explode_linha[19]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[19])];
//           }
//           if (trim(@$explode_linha[20]) != "") {
//             $disciplinas .= "<br>->".$array_disciplina[trim($explode_linha[20])];
//           }           

           if (trim($explode_linha[8]) != "") {
            	
              $disciplina     = "";
              $arq_disciplina = trim($explode_linha[8]);
              
              for ($t = 0; $t < 24; $t++) {
              	
                if (substr($arq_disciplina,$t,1) == "1") {
                  $disciplina .= "<br> ->".$array_disciplina[$t];
                }
                
              }
            } 
           if (trim($explode_linha[7]) != "") {
           	 if (trim($explode_linha[7]) == 1 ) {
           	   $situacao = "Concursado efetivo";          	
             } else if (trim($explode_linha[7]) == 2) {
           	   $situacao = "Contrato tempor�rio"; 
             } else {
               $situacao = "Contrato terceirizado";             	
             }
           }
    
           $array = array("<b>".$contavinculo."� Turma :</b><br>C�digo INEP da Turma:<b> ".trim($explode_linha[4])."</b>",
                          "C�digo da Turma na Escola:<b> ".trim($explode_linha[5])." - ".NomeTurma(trim($nometurma))."</b>",
                          "Fun��o na Turma:<b> ".@$array_funcao[trim($explode_linha[6])]."</b>",
                          "Disciplinas:<b> ".@$disciplina."</b>",
                          "Situa��o Funcional:<b> ".@$situacao."</b><br>"
                         );
           for ($t = 0; $t < count($array); $t++) {
             echo $array[$t]."<br>";
           }
           $contavinculo++;
         }
       }
       fclose($ponteiro);
      }


      if ($registro == "60") {
        $array_raca          = array("0"=>"N�O DECLARADO","1"=>"BRANCA","2"=>"PRETA","3"=>"PARDA",
                                     "4"=>"AMARELA","5"=>"IND�GENA");
        $array_nacionalidade = array("1"=>"BRASILEIRA","2"=>"BRASILEIRA NO EXTERIOR","3"=>"ESTRANGEIRA");
        $array_deficiencia   = array("CEGUEIRA","BAIXA VIS�O","SURDEZ","DEFICI�NCIA AUDITIVA","SURDOCEGUEIRA",
                                     "DEFICI�NCIA F�SICA","DEFICI�NCIA MENTAL","DEFICI�NCIA M�LTIPLA",
                                     "AUTISMO CL�SSICO","S�NDROME DE ASPERGER","S�NDROME DE RETT",
                                     "TRANSTORNO DESINTEGRATIVO DA INF�NCIA (PSICOSE INFANTIL)",
                                     "ALTAS HABILIDADES/SUPERDOTA��O","DEFICIENCIA INTELECTUAL","AUTISMO INFANTIL");       
        $ponteiro            = fopen($arquivogerado,"r");
        while (!feof($ponteiro)) {
        	
          $linha         = " ".fgets($ponteiro);
          $explode_linha = explode("|",$linha);
          
          if ($explode_linha[0] == "60" && $codigoaluno == trim($explode_linha[3])) {
          	
            if (trim($explode_linha[17]) != "") {
            	
              $deficiencia     = "";
              $arq_deficiencia = trim($explode_linha[17]);
              
              for ($t = 0; $t < 15; $t++) {
              	
                if (substr($arq_deficiencia,$t,1) == "1") {
                  $deficiencia .= "<br> ->".$array_deficiencia[$t];
                }
                
              }
            }
            $array = array("C�digo INEP da Escola         : <b> ".trim($explode_linha[1])."</b>",
                           "C�digo INEP do Aluno          : <b> ".trim($explode_linha[2])."</b>",
                           "C�digo do Aluno na Escola     : <b> ".trim($explode_linha[3])."</b>",
                           "Nome                          : <b> ".trim($explode_linha[4])."</b>",
                           "NIS                           : <b> ".trim($explode_linha[5])."</b>",
                           "Data de Nascimento            : <b> ".
                            (substr(trim($explode_linha[6]),0,2)."/".substr(trim($explode_linha[6]),2,2).
                            "/".substr(trim($explode_linha[6]),4,4))."</b>",
                           "Sexo                          : <b> ".
                            (trim($explode_linha[7])=="1"?"MASCULINO":"FEMININO")."</b>",
                           "Ra�a                          : <b> ".$array_raca[trim($explode_linha[8])]."</b>",
                           "Filia��o                      : <b> ".
                            (trim($explode_linha[9])=="0"?"N�O DECLARADO/IGNORADO":"PAI E/OU M�E")."</b>",
                           "Nome da M�e                   : <b> ".trim($explode_linha[10])."</b>",
                           "Nome da Pai                   : <b> ".trim($explode_linha[11])."</b>",
                           "Nacionalidade                 : <b> ".$array_nacionalidade[trim($explode_linha[12])]."</b>",
                           "Pa�s de Origem                : <b> ".Pais(trim($explode_linha[13]))."</b>",
                           "UF de Nascimento              : <b> ".SiglaUF(trim($explode_linha[14]))."</b>",
                           "Munic�pio de Nascimento       : <b> ".Municipio(trim($explode_linha[15]))."</b>",
                           "Defici�ncia/Transtorno Global : <b> ".(trim($explode_linha[16])=="0"?"N�O":"SIM")."</b>",
                           "Tipos de Defici�ncia          : <b> ".@$deficiencia."</b><br>"
                          );
          }
        }
        fclose($ponteiro);
      }
      
      if($registro=="70"){
       $ponteiro = fopen($arquivogerado,"r");
       while (!feof($ponteiro)){
        $linha = " ".fgets($ponteiro);
        $explode_linha = explode("|",$linha);
        if(trim(@$explode_linha[10])=="1"){
         $tipocertidao = "NASCIMENTO";
        }elseif(trim(@$explode_linha[10])=="2"){
         $tipocertidao = "CASAMENTO";                 
        }else{
         $tipocertidao = "";        
        }
        
        if(trim(@$explode_linha[9])=="1"){
         $modelocertidao = "MODELO ANTIGO";
        }elseif(trim(@$explode_linha[9])=="2"){
         $modelocertidao = "MODELO NOVO";                 
        }else{
         $modelocertidao = "";        
        }
        if($explode_linha[0]=="70" && $codigoaluno==trim($explode_linha[3])){
         $array = array("C�digo INEP da Escola:<b> ".trim($explode_linha[1])."</b>",
                        "C�digo INEP do Aluno:<b> ".trim($explode_linha[2])."</b>",
                        "C�digo do Aluno na Escola:<b> ".trim($explode_linha[3])."-".$nomealuno."</b>",
                        "N� Identidade:<b> ".trim($explode_linha[4])."</b>",
                        "Complemento Identidade:<b> ".trim($explode_linha[5])."</b>",
                        "�rg�o Emissor:<b> ".OrgaoRG(trim($explode_linha[6]))."</b>",
                        "UF Identidade:<b> ".SiglaUF(trim($explode_linha[7]))."</b>",
                        "Data de Expedi��o:<b> ".(trim($explode_linha[8])==""?"":(substr(trim($explode_linha[8]),0,2)."/".substr(trim($explode_linha[8]),2,2)."/".substr(trim($explode_linha[8]),4,4)))."</b>",
                        "Modelo de Certid�o:"."<b>".$modelocertidao."</b>",
                        "Certid�o Civil:<b> ".$tipocertidao."</b>",
                        "N� Termo:<b> ".trim($explode_linha[11])."</b>",
                        "Folha:<b> ".trim($explode_linha[12])."</b>",
                        "Livro:<b> ".trim($explode_linha[13])."</b>",
                        "Data de Emiss�o:<b> ".(trim($explode_linha[14])==""?"":(substr(trim($explode_linha[13]),0,2)."/".substr(trim($explode_linha[14]),2,2)."/".substr(trim($explode_linha[14]),4,4)))."</b>",
                        "Nome do Cart�rio:<b> ".trim($explode_linha[17])."</b>",
                        "UF Cart�rio:<b> ".SiglaUF(trim($explode_linha[15]))."</b>",
                        "N� do CPF:<b> ".trim($explode_linha[19])."</b>",
                        "Passaporte:<b> ".trim($explode_linha[20])."</b><br><br><b>Registro 70 - Endere�o Residencial</b><br>",
                        "Zona de Resid�ncia:<b> ".(trim($explode_linha[21])=="1"?"URBANA":"RURAL")."</b>",
                        "CEP:<b> ".trim($explode_linha[22])."</b>",
                        "Endere�o:<b> ".trim($explode_linha[23])."</b>",
                        "N� Endere�o:<b> ".trim($explode_linha[24])."</b>",
                        "Complemento:<b> ".trim($explode_linha[25])."</b>",
                        "Bairro:<b> ".trim($explode_linha[26])."</b>",
                        "UF de Endere�o:<b> ".SiglaUF(trim($explode_linha[27]))."</b>",
                        "Munic�pio de Endere�o:<b> ".Municipio(trim($explode_linha[28]))."</b>"
                       );
        }
       }
       fclose($ponteiro);
      }
      
      if ($registro == "80") {
      	
        $array_recebe = array("1"=>"EM HOSPITAL","2"=>"EM DOMIC�LIO","3"=>"N�O RECEBE");
        $primeiro     = 0;
        $contavinculo = 1;        
        $ponteiro     = fopen($arquivogerado,"r");
        
        while (!feof($ponteiro)) {
        	
          $linha         = " ".fgets($ponteiro);
          $explode_linha = explode("|",$linha);
          
          if ($explode_linha[0] == "80" && $codigoaluno == trim($explode_linha[3])) {
          	
            if ($primeiro == 0) {
            	
              $array = array("C�digo INEP da Escola     : <b> ".trim($explode_linha[1])."</b>",
                             "C�digo INEP do Aluno      : <b> ".trim($explode_linha[2])."</b>",
                             "C�digo do Aluno na Escola : <b> ".trim($explode_linha[3])." - ".$nomealuno."</b><br>");
              for ($t = 0; $t < count($array); $t++) {
                echo $array[$t]."<br>";
              }
              $primeiro = 1;
            }
            $array = array("<b>V�nculo $contavinculo:</b><br>
                           C�digo INEP da Turma                       : <b> ".trim($explode_linha[4])."</b>",
                           "C�digo da Turma na Escola                 : <b> ".
                            trim($explode_linha[5])." - ".NomeTurma(trim($explode_linha[5]))."</b>",
                           "Recebe escolariza��o em outro espa�o      : <b> ".$array_recebe[trim($explode_linha[9])]."</b>",
                           "Transporte Escolar P�blico                : <b> ".
                            (trim($explode_linha[10])=="0"?"N�O UTILIZA":"UTILIZA")."</b>",
                           "Poder P�blico Respons�vel pelo Transporte : <b> ".
                            trim($explode_linha[11])=="1"?"ESTADUAL":trim($explode_linha[11])=="2"?"MUNICIPAL":""."</b>",
                          );
            for ($t = 0; $t < count($array); $t++) {
              echo $array[$t]."<br>";
            }
            $contavinculo++;
          }
        }
        fclose($ponteiro);
      }
      
      if ($registro != "51" && $registro != "80" && $registro != "null") {
      	
        for ($t = 0; $t < count($array); $t++) {
          echo $array[$t]."<br>";
        }
        
      }
      ?>
      <br><br>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
</body>
</html>