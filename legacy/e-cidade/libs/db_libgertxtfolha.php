<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: PESSOAL
class LayoutBBBSFolha {

/***************************************************************************************************/
/***************      TXT - Variável que retorna texto a ser impresso no arquivo     ***************/
/***************************************************************************************************/

    var  $TEXTO = null;

/***************************************************************************************************/
/***************************************************************************************************/
/*********************  VARIÁVEIS USADAS PARA GERAR ARQUIVO DO BANCO BANRISUL **********************/
/***************************************************************************************************/
/*
    CABEÇALHO ARQUIVO
*/
    var  $BSheaderA_001_003 = null;
    var  $BSheaderA_004_007 = null;
    var  $BSheaderA_008_008 = null;
    var  $BSheaderA_009_017 = null;
    var  $BSheaderA_018_018 = null;
    var  $BSheaderA_019_032 = null;
    var  $BSheaderA_033_037 = null;
    var  $BSheaderA_038_052 = null;
    var  $BSheaderA_053_057 = null;
    var  $BSheaderA_058_058 = null;
    var  $BSheaderA_059_061 = null;
    var  $BSheaderA_062_071 = null;
    var  $BSheaderA_072_072 = null;
    var  $BSheaderA_073_102 = null;
    var  $BSheaderA_103_132 = null;
    var  $BSheaderA_133_142 = null;
    var  $BSheaderA_143_143 = null;
    var  $BSheaderA_144_151 = null;
    var  $BSheaderA_152_157 = null;
    var  $BSheaderA_158_163 = null;
    var  $BSheaderA_164_166 = null;
    var  $BSheaderA_167_171 = null;
    var  $BSheaderA_172_191 = null;
    var  $BSheaderA_192_211 = null;
    var  $BSheaderA_212_240 = null;
/*
    CABEÇALHO LOTE
*/
    var  $BSheaderL_001_003 = null;
    var  $BSheaderL_004_007 = null;
    var  $BSheaderL_008_008 = null;
    var  $BSheaderL_009_009 = null;
    var  $BSheaderL_010_011 = null;
    var  $BSheaderL_012_013 = null;
    var  $BSheaderL_014_016 = null;
    var  $BSheaderL_017_017 = null;
    var  $BSheaderL_018_018 = null;
    var  $BSheaderL_019_032 = null;
    var  $BSheaderL_033_037 = null;
    var  $BSheaderL_038_052 = null;
    var  $BSheaderL_053_057 = null;
    var  $BSheaderL_058_061 = null;
    var  $BSheaderL_062_071 = null;
    var  $BSheaderL_072_072 = null;
    var  $BSheaderL_073_102 = null;
    var  $BSheaderL_103_142 = null;
    var  $BSheaderL_143_172 = null;
    var  $BSheaderL_173_177 = null;
    var  $BSheaderL_178_192 = null;
    var  $BSheaderL_193_212 = null;
    var  $BSheaderL_213_220 = null;
    var  $BSheaderL_221_222 = null;
    var  $BSheaderL_223_224 = null;
    var  $BSheaderL_225_240 = null;
/*
    FINAL CABEÇALHOS
*/
/*
    CORPO
*/
    var  $BSregist_001_003 = null;
    var  $BSregist_004_007 = null;
    var  $BSregist_008_008 = null;
    var  $BSregist_009_013 = null;
    var  $BSregist_014_014 = null;
    var  $BSregist_015_015 = null;
    var  $BSregist_016_017 = null;
    var  $BSregist_018_020 = null;
    var  $BSregist_021_023 = null;
    var  $BSregist_024_028 = null;
    var  $BSregist_029_029 = null;
    var  $BSregist_030_042 = null;
    var  $BSregist_043_043 = null;
    var  $BSregist_044_073 = null;
    var  $BSregist_074_088 = null;
    var  $BSregist_089_093 = null;
    var  $BSregist_094_101 = null;
    var  $BSregist_102_104 = null;
    var  $BSregist_105_119 = null;
    var  $BSregist_120_134 = null;
    var  $BSregist_135_154 = null;
    var  $BSregist_155_162 = null;
    var  $BSregist_163_177 = null;
    var  $BSregist_178_182 = null;
    var  $BSregist_183_202 = null;
    var  $BSregist_203_203 = null;
    var  $BSregist_204_217 = null;
    var  $BSregist_218_229 = null;
    var  $BSregist_230_230 = null;
    var  $BSregist_231_240 = null;
  /*
      FINAL CORPO
  */
  /***************************************************************************************************/

  /***************************************************************************************************/
  /***************************************************************************************************/
  /*********************  VARIÁVEIS USADAS PARA GERAR ARQUIVO DO BANCO DO BRASIL *********************/
  /***************************************************************************************************/
  /***************************************************************************************************/
  /*
      CABEÇALHO ARQUIVO
  */
      var  $BBheaderA_001_003 = null;
      var  $BBheaderA_004_007 = null;
      var  $BBheaderA_008_008 = null;
      var  $BBheaderA_009_017 = null;
      var  $BBheaderA_018_018 = null;
      var  $BBheaderA_019_032 = null;
      var  $BBheaderA_033_052 = null;
      var  $BBheaderA_053_057 = null;
      var  $BBheaderA_058_058 = null;
      var  $BBheaderA_059_070 = null;
      var  $BBheaderA_071_071 = null;
      var  $BBheaderA_072_072 = null;
      var  $BBheaderA_073_102 = null;
      var  $BBheaderA_103_132 = null;
      var  $BBheaderA_133_142 = null;
      var  $BBheaderA_143_143 = null;
      var  $BBheaderA_144_151 = null;
      var  $BBheaderA_152_157 = null;
      var  $BBheaderA_158_163 = null;
      var  $BBheaderA_164_166 = null;
      var  $BBheaderA_167_171 = null;
      var  $BBheaderA_172_191 = null;
      var  $BBheaderA_192_211 = null;
      var  $BBheaderA_212_222 = null;
      var  $BBheaderA_223_225 = null;
      var  $BBheaderA_226_228 = null;
      var  $BBheaderA_229_230 = null;
      var  $BBheaderA_231_240 = null;
  /*
      CABEÇALHO LOTE
  */
      var  $BBheaderL_001_003 = null;
      var  $BBheaderL_004_007 = null;
      var  $BBheaderL_008_008 = null;
      var  $BBheaderL_009_009 = null;
      var  $BBheaderL_010_011 = null;
      var  $BBheaderL_012_013 = null;
      var  $BBheaderL_014_016 = null;
      var  $BBheaderL_017_017 = null;
      var  $BBheaderL_018_018 = null;
      var  $BBheaderL_019_032 = null;
      var  $BBheaderL_033_052 = null;
      var  $BBheaderL_053_057 = null;
      var  $BBheaderL_058_058 = null;
      var  $BBheaderL_059_070 = null;
      var  $BBheaderL_071_071 = null;
      var  $BBheaderL_072_072 = null;
      var  $BBheaderL_073_102 = null;
      var  $BBheaderL_103_142 = null;
      var  $BBheaderL_143_172 = null;
      var  $BBheaderL_173_177 = null;
      var  $BBheaderL_178_192 = null;
      var  $BBheaderL_193_212 = null;
      var  $BBheaderL_213_217 = null;
      var  $BBheaderL_218_220 = null;
      var  $BBheaderL_221_222 = null;
      var  $BBheaderL_223_230 = null;
      var  $BBheaderL_231_240 = null;
  /*
      FINAL CBABEÇALHOS
  */
  /*
      CORPO SEGMENTO A
  */
      var  $BBregistA_001_003 = null;
      var  $BBregistA_004_007 = null;
      var  $BBregistA_008_008 = null;
      var  $BBregistA_009_013 = null;
      var  $BBregistA_014_014 = null;
      var  $BBregistA_015_015 = null;
      var  $BBregistA_016_017 = null;
      var  $BBregistA_018_020 = null;
      var  $BBregistA_021_023 = null;
      var  $BBregistA_024_028 = null;
      var  $BBregistA_029_029 = null;
      var  $BBregistA_030_041 = null;
      var  $BBregistA_042_042 = null;
      var  $BBregistA_043_043 = null;
      var  $BBregistA_044_073 = null;
      var  $BBregistA_074_093 = null;
      var  $BBregistA_094_101 = null;
      var  $BBregistA_102_104 = null;
      var  $BBregistA_105_119 = null;
      var  $BBregistA_120_134 = null;
      var  $BBregistA_135_154 = null;
      var  $BBregistA_155_162 = null;
      var  $BBregistA_163_177 = null;
      var  $BBregistA_178_217 = null;
      var  $BBregistA_218_229 = null;
      var  $BBregistA_230_230 = null;
      var  $BBregistA_231_240 = null;
  /*
      CORPO SEGMENTO B
  */
      var  $BBregistB_001_003 = null;
      var  $BBregistB_004_007 = null;
      var  $BBregistB_008_008 = null;
      var  $BBregistB_009_013 = null;
      var  $BBregistB_014_014 = null;
      var  $BBregistB_015_017 = null;
      var  $BBregistB_018_018 = null;
      var  $BBregistB_019_032 = null;
      var  $BBregistB_033_062 = null;
      var  $BBregistB_063_067 = null;
      var  $BBregistB_068_082 = null;
      var  $BBregistB_083_097 = null;
      var  $BBregistB_098_117 = null;
      var  $BBregistB_118_122 = null;
      var  $BBregistB_123_125 = null;
      var  $BBregistB_126_127 = null;
      var  $BBregistB_128_135 = null;
      var  $BBregistB_136_150 = null;
      var  $BBregistB_151_165 = null;
      var  $BBregistB_166_180 = null;
      var  $BBregistB_181_195 = null;
      var  $BBregistB_196_210 = null;
      var  $BBregistB_211_225 = null;
      var  $BBregistB_226_240 = null;
  /*
      FINAL CORPO
  */
  /***************************************************************************************************/

  /***************************************************************************************************/
  /***************************************************************************************************/
  /*****************  VARIÁVEIS USADAS PARA GERAR TRAILLER DO ARQUIVO DOS DOIS BANCOS ****************/
  /***************************************************************************************************/
  /***************************************************************************************************/
  /*
      TRAILLER LOTE
  */
      var  $BBBStraillerL_001_003 = null;
      var  $BBBStraillerL_004_007 = null;
      var  $BBBStraillerL_008_008 = null;
      var  $BBBStraillerL_009_017 = null;
      var  $BBBStraillerL_018_023 = null;
      var  $BBBStraillerL_024_041 = null;
      var  $BBBStraillerL_042_059 = null;
      var  $BBBStraillerL_060_230 = null;
      var  $BBBStraillerL_231_240 = null;
  /*
      TRAILLER ARQUIVO
  */
      var  $BBBStraillerA_001_003 = null;
      var  $BBBStraillerA_004_007 = null;
      var  $BBBStraillerA_008_008 = null;
      var  $BBBStraillerA_009_017 = null;
      var  $BBBStraillerA_018_023 = null;
      var  $BBBStraillerA_024_029 = null;
      var  $BBBStraillerA_230_035 = null;
      var  $BBBStraillerA_236_240 = null;
  /*
	  FINAL TRAILLERS
  */
  /***************************************************************************************************/

     var $arquivo  = null;
     var $nomearq  = '/tmp/modelo.txt';

  //////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////// MÉTODOS LAYOUT DO BANCO BANRISUL //////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////// Início --- OBS.: Somente HEADER do arquivo, HEADER do lote e REGISTROS ///////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////
     function geraHEADERArqBS(){
	$this->arquivo = fopen($this->nomearq,"w");
	    fputs($this->arquivo,
		    db_formatar(substr(trim($this->BSheaderA_001_003),0,3),"s","0",3,"e",0)
		   ."0000"
		   ."0"
		   .str_repeat(" ",9)
		   ."2"
		   .db_formatar(substr(trim($this->BSheaderA_019_032),0,14),"s","0",14,"e",0)
		   .db_formatar(substr(trim($this->BSheaderA_033_037),0,5),"s","0",5,"e",0)
		   .str_repeat(" ",15)
		   .db_formatar(substr(trim(str_replace('.','',str_replace('-','',$this->BSheaderA_053_057))),0,5),"s","0",5,"e",0)
		   ."0"
		   ."000"
		   .db_formatar(substr(trim(str_replace('.','',str_replace('-','',$this->BSheaderA_062_071))),0,10),"s","0",10,"e",0)
		   ."0"
		   .db_translate(db_formatar(substr(strtoupper($this->BSheaderA_073_102),0,30),'s',' ',30,'d',0))
		   .db_translate(db_formatar(substr(strtoupper($this->BSheaderA_103_132),0,30),'s',' ',30,'d',0))
		   .str_repeat(" ",10)
		   ."1"
		   .str_replace('/','',db_formatar($this->BSheaderA_144_151,"d"))
		   .date("H").date("i").date("s")
		   .db_formatar(substr($this->BSheaderA_158_163,0,6),"s","0",6,"e",0)
		   ."030"
		   .str_repeat("0",5)
		   .str_repeat(" ",20)
		   .db_formatar($this->BSheaderA_192_211,"s","0",20,"e",0)
		   .str_repeat(" ",29)
		   ."\r\n"
	    );
     }
     function geraHEADERLoteBS(){
	  //segundo cabeçalho
	  fputs($this->arquivo,
		    $this->BSheaderL_001_003
		   .db_formatar($this->BSheaderL_004_007,'s','0',4,'e',0)
		   ."1"
		   ."C"
		   .$this->BSheaderL_010_011
		   .$this->BSheaderL_012_013
		   ."020"
		   ." "
		   ."2"
		   .db_formatar($this->BSheaderL_019_032,"s","0",14,"e",0)
		   .db_formatar($this->BSheaderL_033_037,"s","0",5,"e",0)
		   .str_repeat(" ",15)
		   .db_formatar($this->BSheaderL_053_057,"s","0",5,"e",0)
		   .str_repeat('0',4)
		   .db_formatar($this->BSheaderL_062_071,"s","0",10,"e",0)
		   ." "
		   .db_translate(db_formatar(substr(strtoupper($this->BSheaderL_073_102),0,30),'s',' ',30,'d',0))
		   .str_repeat(' ',40)
		   .db_translate(db_formatar(substr(strtoupper($this->BSheaderL_143_172),0,30),'s',' ',30,'d',0))
		   .db_formatar($this->BSheaderL_173_177,"s","0",5,"e",0)
		   .db_translate(db_formatar(substr(strtoupper($this->BSheaderL_178_192),0,15),"s"," ",15,"d",0))
		   .db_translate(db_formatar(substr(strtoupper($this->BSheaderL_193_212),0,20),'s',' ',20,'d',0))
		   .db_formatar(substr(str_replace('.','',str_replace('-','',$this->BSheaderL_213_220)),0,8),'s','0',8,'e',0)
		   .db_translate(db_formatar(substr(strtoupper($this->BSheaderL_221_222),0,2),'s',' ',2,'d',0))
		   .str_repeat(' ',2)
		   .str_repeat(' ',16)
		   ."\r\n"
	    );
      }
      function geraREGISTROSBS(){
	    fputs($this->arquivo,
		    $this->BSregist_001_003
		   .db_formatar($this->BSregist_004_007,'s','0',4,'e',0)
		   ."3"
		   .db_formatar($this->BSregist_009_013,'s','0',5,'e',0)
		   ."A"
		   ."0"
		   ."00"
		   .$this->BSregist_018_020
		   .$this->BSregist_021_023
		   .db_formatar($this->BSregist_024_028,'s','0',5,'e',0)
		   ."0"
		   .db_formatar($this->BSregist_030_042,'s','0',13,'e',0)
		   ." "
		   .db_translate(db_formatar(substr(strtoupper($this->BSregist_044_073),0,30),'s',' ',30,'d',0))
		   .db_formatar($this->BSregist_074_088,'s','0',15,'d',0)
		   ."00005"
		   .str_replace('/','',db_formatar($this->BSregist_094_101,"d"))
		   ."BRL"
		   .str_repeat('0',15)
		   .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->BSregist_120_134,"f")))),'s','0',15,'e',0)
		   .str_repeat(' ',20)
		   .str_repeat(' ',8)
		   .str_repeat(' ',15)
		   .str_repeat(' ',5)
		   .str_repeat(' ',20)
		   .$this->BSregist_203_203
		   .db_formatar(substr(str_replace('.','',str_replace('-','',$this->BSregist_204_217)),0,14),'s','0',14,'e',0)
		   .str_repeat(' ',12)
		   ."0"
		   .str_repeat(' ',10)
		   ."\r\n"
		 );
      }
  //////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////// FINAL MÉTODOS ARQUIVO DO BANCO DO BANRISUL ////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////

  //////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////// MÉTODOS LAYOUT DO BANCO DO BRASIL /////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////// Início --- OBS.: Somente HEADER do arquivo, HEADER do lote e REGISTROS ///////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////

     function geraHEADERArqBB(){
	    $this->arquivo = fopen($this->nomearq,"w");
	    fputs($this->arquivo,
	      $this->BBheaderA_001_003
		  .str_repeat('0',4)
		  ."0"
		  .str_repeat(' ',9)
		  ."2"
		  .db_formatar($this->BBheaderA_019_032,'s','0',14,'e',0)
	      .db_formatar($this->BBheaderA_033_052,'s',' ',20,'d',0)
		  .db_formatar(str_replace('.','',str_replace('-','',$this->BBheaderA_053_057)),'s','0',5,'e',0)
		  .$this->BBheaderA_058_058
		  .db_formatar(str_replace('.','',str_replace('-','',$this->BBheaderA_059_070)),'s','0',12,'e',0)
		  .$this->BBheaderA_071_071
		  .' '
		  .db_translate(db_formatar(substr(strtoupper($this->BBheaderA_073_102),0,30),'s',' ',30,'d',0))
		  .db_translate(db_formatar(substr(strtoupper($this->BBheaderA_103_132),0,30),'s',' ',30,'d',0))
		  .str_repeat(' ',10)
		  ."1"
		  .str_replace('/','',db_formatar($this->BBheaderA_144_151,"d"))
		  .date("H").date("i").date("s")
		  .db_formatar($this->BBheaderA_158_163,'s','0',6,'e',0)
		  ."030"
		  .str_repeat('0',5)
		  .str_repeat(' ',20)
		  .db_formatar($this->BBheaderA_192_211,'s',' ',20,'e',0)
		  .str_repeat(' ',11)
		  .str_repeat(' ',3)
	      .str_repeat('0',3)
	      .str_repeat(' ',2)
	      .str_repeat(' ',10)
		  ."\r\n"
	    );
     }
     function geraHEADERLoteBB(){
	//segundo cabeçalho
	fputs($this->arquivo,
		  $this->BBheaderL_001_003
		  .db_formatar($this->BBheaderL_004_007,'s','0',4,'e',0)
		  ."1"
		  ."C"
		  .$this->BBheaderL_010_011
		  .$this->BBheaderL_012_013
		  ."020"
		  ." "
		  ."2"
		  .db_formatar($this->BBheaderL_019_032,'s','0',14,'e',0)
		  .db_formatar($this->BBheaderL_033_052,'s',' ',20,'d',0)
		  .db_formatar(str_replace('.','',str_replace('-','',$this->BBheaderL_053_057)),'s','0',5,'e',0)
		  .$this->BBheaderL_058_058
		  .db_formatar(str_replace('.','',str_replace('-','',$this->BBheaderL_059_070)),'s','0',12,'e',0)
		  .$this->BBheaderL_071_071
		  ." "
		  .substr(strtoupper($this->BBheaderL_073_102),0,30)
		  .str_repeat(' ',40)
		  .db_translate(db_formatar(strtoupper($this->BBheaderL_143_172),'s',' ',30,'d',0))
		  .db_formatar($this->BBheaderL_173_177,'s',' ',5,'e',0)
		  .str_repeat(' ',15)
		  .db_translate(db_formatar(strtoupper(trim($this->BBheaderL_193_212)),'s',' ',20,'d',0))
		  .db_formatar($this->BBheaderL_213_217,'s','0',5,'e',0)
		  .db_translate(db_formatar($this->BBheaderL_218_220,'s',' ',3,'d',0))
		  .db_translate(db_formatar($this->BBheaderL_221_222,'s',' ',2,'d',0))
		  .str_repeat(' ',8)
	      .str_repeat(' ',10)
		      ."\r\n"
	    );
      }
      function geraREGISTROSBB(){
	    fputs($this->arquivo,
		    $this->BBregistA_001_003
		   .db_formatar($this->BBregistA_004_007,'s','0',4,'e',0)
		   ."3"
		   .db_formatar($this->BBregistA_009_013,'s','0',5,'e',0)
		   ."A"
		   ."0"
		   ."00"
		   .$this->BBregistA_018_020
		   .$this->BBregistA_021_023
		   .db_formatar(str_replace('.','',str_replace('-','',$this->BBregistA_024_028)),'s','0',5,'e',0)
		   .$this->BBregistA_029_029
		   .db_formatar(str_replace('.','',str_replace('-','',$this->BBregistA_030_041)),'s','0',12,'e',0)
		   .$this->BBregistA_042_042
		   .$this->BBregistA_043_043
		   .db_formatar(str_replace('-','',substr($this->BBregistA_044_073,0,30)),'s',' ',30,'d',0)
		   .db_formatar($this->BBregistA_074_093,'s','0',20,'d',0)
		   .str_replace("/",'',db_formatar($this->BBregistA_094_101,"d"))
		   ."BRL"
		   .str_repeat('0',15)
		   .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->BBregistA_120_134,"f")))),'s','0',15,'e',0)
		   .str_repeat(' ',20)
		   .str_repeat(' ',8)
		   .str_repeat(' ',15)
		   .str_repeat(' ',40)
		   .str_repeat(' ',12)
		   ."0"
		   .str_repeat(' ',10)
		   ."\r\n"
		 );
	    fputs($this->arquivo,
		    $this->BBregistB_001_003
		   .db_formatar($this->BBregistB_004_007,'s','0',4,'e',0)
		   ."3"
		   .db_formatar($this->BBregistB_009_013,'s','0',5,'e',0)
		   ."B"
		   .str_repeat(' ',3)
		   .$this->BBregistB_018_018
		   .db_translate(db_formatar($this->BBregistB_019_032,'s','0',14,'e',0))
		   .db_formatar(substr($this->BBregistB_033_062,0,30),'s',' ',30,'d',0)
		   .db_translate(db_formatar(substr($this->BBregistB_063_067,0,5),'s','0',5,'e',0))
		   .db_translate(db_formatar(substr($this->BBregistB_068_082,0,15),'s',' ',15,'d',0))
		   .db_translate(db_formatar(substr($this->BBregistB_083_097,0,15),'s',' ',15,'d',0))
		   .db_translate(db_formatar(substr($this->BBregistB_098_117,0,20),'s',' ',20,'d',0))
		   .db_translate(db_formatar(substr($this->BBregistB_118_122,0,5),'s',' ',5,'d',0))
		   .db_translate(db_formatar(substr($this->BBregistB_123_125,5,3),'s',' ',3,'d',0))
		   .db_translate(db_formatar($this->BBregistB_126_127,'s',' ',2,'d',0))
		   .str_replace("/",'',db_formatar($this->BBregistB_128_135,"d"))
		   .db_formatar(str_replace(',','',str_replace('.','',$this->BBregistB_136_150)),'s','0',15,'e',0)
		   .str_repeat('0',15)
		   .str_repeat('0',15)
		   .str_repeat('0',15)
		   .str_repeat('0',15)
		   .str_repeat('0',15)
		   .str_repeat('0',15)
		   ."\r\n"
		 );
      }

  //////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////// FINAL MÉTODOS ARQUIVO DO BANCO DO BRASIL /////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////

  //////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////// INÍCIO MÉTODOS QUE GERAM TRAILLERS ////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////
  // OBS.: Arquivos do banco do brasil e do banrisul não mudam trailler de arquivo e trailler de lote //
      function geraTRAILLERLote(){
	    fputs($this->arquivo,
		    $this->BBBStraillerL_001_003
		   .db_formatar($this->BBBStraillerL_004_007,'s','0',4,'e',0)
		   ."5"
		   .str_repeat(' ',9)
		   .db_formatar(($this->BBBStraillerL_018_023 + 2),'s','0',6,'e',0)
		   .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->BBBStraillerL_024_041,"f")))),'s','0',18,'e',0)
		   .str_repeat('0',18)
		   .str_repeat(' ',171)
		   .str_repeat(' ',10)
		   ."\r\n"
		 );
      }
      function geraTRAILLERArquivo(){
	    fputs($this->arquivo,
		    $this->BBBStraillerA_001_003
		   .'9999'
		   .'9'
		   .str_repeat(' ',9)
		   .db_formatar($this->BBBStraillerA_018_023,'s','0',6,'e',0)
		   .db_formatar($this->BBBStraillerA_024_029,'s','0',6,'e',0)
		   .str_repeat('0',6)
		   .str_repeat(' ',205)
		   ."\r\n"
		 );
      }
  //////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////// FINAL MÉTODOS QUE GERAM TRAILLERS ////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////

  ////////////////////////////////////////
  //          FECHA O ARQUIVO           //
  ////////////////////////////////////////
      function gera(){
	 fclose($this->arquivo);
      }
  ////////////////////////////////////////
  ////////////////////////////////////////
  //          ABRE O ARQUIVO            //
  ////////////////////////////////////////
      function abre(){
	 $this->arquivo = fopen($this->nomearq,"w");
      }
  ////////////////////////////////////////
  }




  //MODULO: PESSOAL
  class cl_layout_VISA {

  /***************************************************************************************************/
  /***************      TXT - Variável que retorna texto a ser impresso no arquivo     ***************/
  /***************************************************************************************************/

      var  $TEXTO = null;

  /***************************************************************************************************/

  /***************************************************************************************************/
  /***************************************************************************************************/
  /*********************     VARIÁVEIS USADAS PARA GERAR ARQUIVO DO VISA VALE    *********************/
  /***************************************************************************************************/
  /***************************************************************************************************/
  /*
      CABEÇALHO ARQUIVO - HEADER ARQUIVO
  */
      var  $VVheaderA_001_001 = null;
      var  $VVheaderA_002_009 = null;
      var  $VVheaderA_010_013 = null;
      var  $VVheaderA_014_048 = null;
      var  $VVheaderA_049_062 = null;
      var  $VVheaderA_063_073 = null;
      var  $VVheaderA_074_084 = null;
      var  $VVheaderA_085_090 = null;
      var  $VVheaderA_091_098 = null;
      var  $VVheaderA_099_099 = null;
      var  $VVheaderA_100_100 = null;
      var  $VVheaderA_101_106 = null;
      var  $VVheaderA_107_124 = null;
      var  $VVheaderA_125_127 = null;
      var  $VVheaderA_128_394 = null;
      var  $VVheaderA_395_400 = null;
      var  $VVheaderA_401_450 = null;
  /*
      REGISTRO FILIAL OU POSTO DE PESSOA JURÍDICA
  */
      var  $VVregistFL_001_001 = null;
      var  $VVregistFL_002_009 = null;
      var  $VVregistFL_010_013 = null;
      var  $VVregistFL_014_015 = null;
      var  $VVregistFL_016_025 = null;
      var  $VVregistFL_026_060 = null;
      var  $VVregistFL_061_064 = null;
      var  $VVregistFL_065_099 = null;
      var  $VVregistFL_100_139 = null;
      var  $VVregistFL_140_151 = null;
      var  $VVregistFL_152_157 = null;
      var  $VVregistFL_158_192 = null;
      var  $VVregistFL_193_232 = null;
      var  $VVregistFL_233_244 = null;
      var  $VVregistFL_245_250 = null;
      var  $VVregistFL_251_285 = null;
      var  $VVregistFL_286_325 = null;
      var  $VVregistFL_326_337 = null;
      var  $VVregistFL_338_343 = null;
      var  $VVregistFL_344_363 = null;
      var  $VVregistFL_364_394 = null;
      var  $VVregistFL_395_400 = null;
      var  $VVregistFL_401_450 = null;
  /*
      FINAL REGISTROS
  */
  /*
      REGISTRO USUÁRIOS (FUNCIONÁRIOS)
  */
      var  $VVregistFC_001_001 = null;
      var  $VVregistFC_002_012 = null;
      var  $VVregistFC_013_013 = null;
      var  $VVregistFC_014_026 = null;
      var  $VVregistFC_027_080 = null;
      var  $VVregistFC_081_088 = null;
      var  $VVregistFC_089_099 = null;
      var  $VVregistFC_100_100 = null;
      var  $VVregistFC_101_113 = null;
      var  $VVregistFC_114_133 = null;
      var  $VVregistFC_134_139 = null;
      var  $VVregistFC_140_154 = null;
      var  $VVregistFC_155_155 = null;
      var  $VVregistFC_156_156 = null;
      var  $VVregistFC_157_191 = null;
      var  $VVregistFC_192_201 = null;
      var  $VVregistFC_202_206 = null;
      var  $VVregistFC_207_214 = null;
      var  $VVregistFC_215_242 = null;
      var  $VVregistFC_243_272 = null;
      var  $VVregistFC_273_274 = null;
      var  $VVregistFC_275_309 = null;
      var  $VVregistFC_310_310 = null;
      var  $VVregistFC_311_314 = null;
      var  $VVregistFC_315_322 = null;
      var  $VVregistFC_323_326 = null;
      var  $VVregistFC_327_330 = null;
      var  $VVregistFC_331_338 = null;
      var  $VVregistFC_339_339 = null;
      var  $VVregistFC_340_347 = null;
      var  $VVregistFC_348_348 = null;
      var  $VVregistFC_349_388 = null;
      var  $VVregistFC_389_394 = null;
      var  $VVregistFC_395_400 = null;
      var  $VVregistFC_401_450 = null;
  /*
      REGISTRO DE USUÁRIOS (FUNCIONÁRIOS)
  */
  /*
      TRAILLER ARQUIVO VISA VALE
  */
      var  $VVtraillerArq_001_001 = null;
      var  $VVtraillerArq_002_007 = null;
      var  $VVtraillerArq_008_022 = null;
      var  $VVtraillerArq_023_394 = null;
      var  $VVtraillerArq_395_400 = null;
  /*
   *  FINAL DO TRAILLER DE ARQUIVO VISA VALE
  */
  /***************************************************************************************************/

     var $arquivo  = null;
     var $nomearq  = '/tmp/modelo.txt';

  //////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////    MÉTODOS LAYOUT DO VISA VALE    /////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////

     function geraHEADERArqVV(){
	    $this->arquivo = fopen($this->nomearq,"w");
	    fputs($this->arquivo,
			      "0"
			     .str_replace("/",'',db_formatar($this->VVheaderA_002_009,"d"))
			     ."A001"
			     .db_formatar(strtoupper(substr(db_translate($this->VVheaderA_014_048),0,35)),"s"," ",35,"d",0)
			     .db_formatar($this->VVheaderA_049_062,"s","0",14,"e",0)
			     .str_repeat("0",11)
			     .db_formatar($this->VVheaderA_074_084,"s","0",11,"e",0)
			     .str_repeat("0",6)
			     .str_replace("/",'',db_formatar($this->VVheaderA_091_098,"d"))
			   .$this->VVheaderA_099_099
			   .$this->VVheaderA_100_100
			   .str_replace("/",'',$this->VVheaderA_101_106)
			   .str_repeat(" ",18)
			   ."007"
			   .str_repeat(" ",267)
			   .db_formatar("1","s","0",6,"e",0)
			   .str_repeat(" ",50)
		     ."\r\n"
	  );
   }
   function geraRegistVV(){
      //segundo cabeçalho
      fputs($this->arquivo,
				    "1"
				   .db_formatar($this->VVregistFL_002_009,"s","0",14,"e",0)  // CNPJ É QUEBRADO NO ARQUIVO
				   .str_repeat("0",10)                                       // PASSEI SOMENTE UMA VARIÁVEL COM CNPJ DA PREFEITURA
				   .db_formatar(strtoupper(substr(db_translate($this->VVregistFL_026_060),0,35)),"s"," ",35,"d",0)
				   .db_formatar($this->VVregistFL_061_064,"s","0",4,"e",0)
				   .db_formatar(strtoupper(substr(db_translate($this->VVregistFL_065_099),0,35)),"s"," ",35,"d",0)
				   .db_formatar(strtoupper(substr(db_translate($this->VVregistFL_100_139),0,40)),"s"," ",40,"d",0)
				   .db_formatar($this->VVregistFL_140_151,"s","0",12,"e",0)
				   .db_formatar($this->VVregistFL_152_157,"s","0",6,"e",0)
				   .db_formatar(strtoupper(substr(db_translate($this->VVregistFL_158_192),0,35)),"s"," ",35,"d",0)
				   .db_formatar(strtoupper(substr(db_translate($this->VVregistFL_193_232),0,40)),"s"," ",40,"d",0)
				   .db_formatar($this->VVregistFL_233_244,"s","0",12,"e",0)
				   .db_formatar($this->VVregistFL_245_250,"s","0",6,"e",0)
				   .db_formatar(strtoupper(substr(db_translate($this->VVregistFL_251_285),0,35)),"s"," ",35,"d",0)
				   .db_formatar(strtoupper(substr(db_translate($this->VVregistFL_286_325),0,40)),"s"," ",40,"d",0)
				   .db_formatar($this->VVregistFL_326_337,"s","0",12,"e",0)
				   .db_formatar($this->VVregistFL_338_343,"s","0",6,"e",0)
				   .db_formatar($this->VVregistFL_344_363,"s"," ",20,"d",0)
				   .str_repeat(" ",31)
				   .db_formatar("2","s","0",6,"e",0)
				   .str_repeat(" ",50)
		    ."\r\n"
	  );
    }
    function geraREGISTROSVV(){
	  fputs($this->arquivo,
 		      "5"
			   .db_formatar((str_replace('.','',str_replace(',','',trim(db_formatar($this->VVregistFC_002_012,"f"))))),"s","0",11,"e",0)
			   ." "
			   .db_formatar($this->VVregistFC_014_026,"s"," ",13,"d",0)
			   .str_repeat(" ",54)
			   .str_replace("/",'',db_formatar($this->VVregistFC_081_088,"d"))
			   .db_formatar(str_replace('-','',str_replace('.','',$this->VVregistFC_089_099)),"s","0",11,"e",0)
			   ."1"
			   .db_formatar($this->VVregistFC_101_113,"s","0",13,"e",0)
			   .db_formatar(strtoupper(db_translate($this->VVregistFC_114_133)),"s"," ",20,"d",0)
			   .db_formatar(strtoupper(db_translate($this->VVregistFC_134_139)),"s"," ",6,"d",0)
			   .db_formatar($this->VVregistFC_140_154,"s","0",15,"e",0)
			   .strtoupper($this->VVregistFC_155_155)
			   .$this->VVregistFC_156_156
			   .db_formatar(strtoupper(substr(db_translate($this->VVregistFC_157_191),0,35)),"s"," ",35,"d",0)
			   .db_formatar(strtoupper(substr(db_translate($this->VVregistFC_192_201),0,10)),"s"," ",10,"d",0)
			   .db_formatar($this->VVregistFC_202_206,"s","0",5,"e",0)
			   .db_formatar(str_replace('-','',str_replace('.','',$this->VVregistFC_207_214)),"s","0",8,"e",0)
			   .db_formatar(strtoupper(substr(db_translate($this->VVregistFC_215_242),0,28)),"s"," ",28,"d",0)
			   .db_formatar(strtoupper(substr(db_translate($this->VVregistFC_243_272),0,30)),"s"," ",30,"d",0)
			   .db_formatar(strtoupper(substr(db_translate($this->VVregistFC_273_274),0,02)),"s"," ",2,"d",0)
			   .db_formatar(strtoupper(substr(db_translate($this->VVregistFC_275_309),0,35)),"s"," ",35,"d",0)
			   ."R"
			   .db_formatar($this->VVregistFC_311_314,"s","0",4,"e",0)
			   .db_formatar(str_replace('-','',str_replace('.','',$this->VVregistFC_315_322)),"s","0",8,"e",0)
			   .db_formatar($this->VVregistFC_323_326,"s","0",4,"e",0)
			   .db_formatar($this->VVregistFC_327_330,"s","0",4,"e",0)
			   .db_formatar(str_replace('-','',str_replace('.','',$this->VVregistFC_331_338)),"s","0",8,"e",0)
			   .$this->VVregistFC_339_339
			   .str_replace("/",'',db_formatar($this->VVregistFC_340_347,"d"))
			   ." "
			   .db_formatar(strtoupper(substr(db_translate($this->VVregistFC_349_388),0,40)),"s"," ",40,"d",0)
			   .str_repeat(" ",6)
			   .db_formatar($this->VVregistFC_395_400,"s","0",6,"e",0)
			   .str_repeat(" ",50)
				 ."\r\n"
	       );
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////    FINAL MÉTODOS ARQUIVO DO VISA VALE    /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////   INÍCIO MÉTODO QUE GERA TRAILLER  ////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
// OBS.: Arquivos do banco do brasil e do banrisul não mudam trailler de arquivo e trailler de lote //
    function geraTRAILLERArq(){
	  fputs($this->arquivo,
	         "9"
	         .db_formatar($this->VVtraillerArq_002_007,"s","0",6,"e",0)
	         .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->VVtraillerArq_008_022,"f")))),"s","0",15,"e",0)
	         .str_repeat(" ",372)
	         .db_formatar($this->VVtraillerArq_395_400,"s","0",6,"e",0)
		     ."\r\n"
	       );
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////   FINAL MÉTODO QUE GERA TRAILLER  ////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////
//          FECHA O ARQUIVO           //
////////////////////////////////////////
    function gera(){
       fclose($this->arquivo);
    }
////////////////////////////////////////
////////////////////////////////////////
//          ABRE O ARQUIVO            //
////////////////////////////////////////
    function abre(){
       $this->arquivo = fopen($this->nomearq,"w");
    }
////////////////////////////////////////
}




//MODULO: PESSOAL
class cl_layout_SEFIP {

/***************************************************************************************************/
/***************      TXT - Variável que retorna texto a ser impresso no arquivo     ***************/
/***************************************************************************************************/

    var  $TEXTO = null;

/***************************************************************************************************/

/***************************************************************************************************/
/***************************************************************************************************/
/*********************       VARIÁVEIS USADAS PARA GERAR ARQUIVO DA SEFIP      *********************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    INFORMAÇÕES DO RESPONSÁVEL - REGISTRO '00'
*/
    var  $SFPRegistro00_001_002 = null;
    var  $SFPRegistro00_003_053 = null;
    var  $SFPRegistro00_054_054 = null;
    var  $SFPRegistro00_055_055 = null;
    var  $SFPRegistro00_056_069 = null;
    var  $SFPRegistro00_070_099 = null;
    var  $SFPRegistro00_100_119 = null;
    var  $SFPRegistro00_120_169 = null;
    var  $SFPRegistro00_170_189 = null;
    var  $SFPRegistro00_190_197 = null;
    var  $SFPRegistro00_198_217 = null;
    var  $SFPRegistro00_218_219 = null;
    var  $SFPRegistro00_220_231 = null;
    var  $SFPRegistro00_232_291 = null;
    var  $SFPRegistro00_292_297 = null;
    var  $SFPRegistro00_298_300 = null;
    var  $SFPRegistro00_301_301 = null;
    var  $SFPRegistro00_302_302 = null;
    var  $SFPRegistro00_303_310 = null;
    var  $SFPRegistro00_311_311 = null;
    var  $SFPRegistro00_312_319 = null;
    var  $SFPRegistro00_320_326 = null;
    var  $SFPRegistro00_327_327 = null;
    var  $SFPRegistro00_328_341 = null;
    var  $SFPRegistro00_342_359 = null;
    var  $SFPRegistro00_360_360 = null;
/*
    INFORMAÇÕES DA EMPRESA - REGISTRO '10'
*/
    var  $SFPRegistro10_001_002 = null;
    var  $SFPRegistro10_003_003 = null;
    var  $SFPRegistro10_004_017 = null;
    var  $SFPRegistro10_018_053 = null;
    var  $SFPRegistro10_054_093 = null;
    var  $SFPRegistro10_094_143 = null;
    var  $SFPRegistro10_144_163 = null;
    var  $SFPRegistro10_164_171 = null;
    var  $SFPRegistro10_172_191 = null;
    var  $SFPRegistro10_192_193 = null;
    var  $SFPRegistro10_194_205 = null;
    var  $SFPRegistro10_206_206 = null;
    var  $SFPRegistro10_207_213 = null;
    var  $SFPRegistro10_214_214 = null;
    var  $SFPRegistro10_215_216 = null;
    var  $SFPRegistro10_217_217 = null;
    var  $SFPRegistro10_218_218 = null;
    var  $SFPRegistro10_219_221 = null;
    var  $SFPRegistro10_222_225 = null;
    var  $SFPRegistro10_226_229 = null;
    var  $SFPRegistro10_230_234 = null;
    var  $SFPRegistro10_235_249 = null;
    var  $SFPRegistro10_250_264 = null;
    var  $SFPRegistro10_265_279 = null;
    var  $SFPRegistro10_280_280 = null;
    var  $SFPRegistro10_281_294 = null;
    var  $SFPRegistro10_295_297 = null;
    var  $SFPRegistro10_298_301 = null;
    var  $SFPRegistro10_302_310 = null;
    var  $SFPRegistro10_311_355 = null;
    var  $SFPRegistro10_356_359 = null;
    var  $SFPRegistro10_360_360 = null;

    /*
     * dados do registro 12
     */
    var  $SFPRegistro12_001_002 = 12;
    var  $SFPRegistro12_003_003 = 1;
    var  $SFPRegistro12_004_017 = null;
    var  $SFPRegistro12_018_053 = null;
    var  $SFPRegistro12_054_068 = null;
    var  $SFPRegistro12_069_083 = " ";
    var  $SFPRegistro12_084_084 = null;
    var  $SFPRegistro12_085_099 = null;
    var  $SFPRegistro12_100_114 = null;
    var  $SFPRegistro12_115_125 = null;
    var  $SFPRegistro12_126_129 = null;
    var  $SFPRegistro12_130_134 = null;
    var  $SFPRegistro12_135_140 = null;
    var  $SFPRegistro12_141_146 = null;
    var  $SFPRegistro12_147_161 = null;
    var  $SFPRegistro12_162_167 = null;
    var  $SFPRegistro12_168_173 = null;
    var  $SFPRegistro12_174_188 = null;
    var  $SFPRegistro12_189_203 = null;
    var  $SFPRegistro12_204_218 = null;
    var  $SFPRegistro12_219_233 = null;
    var  $SFPRegistro12_234_248 = null;
    var  $SFPRegistro12_249_263 = null;
    var  $SFPRegistro12_264_278 = null;
    var  $SFPRegistro12_279_293 = null;
    var  $SFPRegistro12_294_308 = null;
    var  $SFPRegistro12_309_353 = null;
    var  $SFPRegistro12_354_359 = "000000";
    var  $SFPRegistro12_360_360 = null;

/*
    INCLUSÃO / ALTERAÇÃO ENDEREÇO DO TRABALHADOR - REGISTRO '14'
*/
    var  $SFPRegistro14_001_002 = null;
    var  $SFPRegistro14_003_003 = null;
    var  $SFPRegistro14_004_017 = null;
    var  $SFPRegistro14_018_053 = null;
    var  $SFPRegistro14_054_064 = null;
    var  $SFPRegistro14_065_072 = null;
    var  $SFPRegistro14_073_074 = null;
    var  $SFPRegistro14_075_144 = null;
    var  $SFPRegistro14_145_151 = null;
    var  $SFPRegistro14_152_156 = null;
    var  $SFPRegistro14_157_206 = null;
    var  $SFPRegistro14_207_226 = null;
    var  $SFPRegistro14_227_234 = null;
    var  $SFPRegistro14_235_254 = null;
    var  $SFPRegistro14_255_256 = null;
    var  $SFPRegistro14_257_359 = null;
    var  $SFPRegistro14_360_360 = null;
/*
    REGISTRO DO TRABALHADOR - REGISTRO '30'
*/
    var  $SFPRegistro30_001_002 = null;
    var  $SFPRegistro30_003_003 = null;
    var  $SFPRegistro30_004_017 = null;
    var  $SFPRegistro30_018_018 = null;
    var  $SFPRegistro30_019_032 = null;
    var  $SFPRegistro30_033_043 = null;
    var  $SFPRegistro30_044_051 = null;
    var  $SFPRegistro30_052_053 = null;
    var  $SFPRegistro30_054_123 = null;
    var  $SFPRegistro30_124_134 = null;
    var  $SFPRegistro30_135_141 = null;
    var  $SFPRegistro30_142_146 = null;
    var  $SFPRegistro30_147_154 = null;
    var  $SFPRegistro30_155_162 = null;
    var  $SFPRegistro30_163_167 = null;
    var  $SFPRegistro30_168_182 = null;
    var  $SFPRegistro30_183_197 = null;
    var  $SFPRegistro30_198_199 = null;
    var  $SFPRegistro30_200_201 = null;
    var  $SFPRegistro30_202_216 = null;
    var  $SFPRegistro30_217_231 = null;
    var  $SFPRegistro30_232_246 = null;
    var  $SFPRegistro30_247_261 = null;
    var  $SFPRegistro30_262_359 = null;
    var  $SFPRegistro30_360_360 = null;
/*
    MOVIMENTAÇÃO DO TRABALHADOR - REGISTRO '32'
*/
    var  $SFPRegistro32_001_002 = null;
    var  $SFPRegistro32_003_003 = null;
    var  $SFPRegistro32_004_017 = null;
    var  $SFPRegistro32_018_018 = null;
    var  $SFPRegistro32_019_032 = null;
    var  $SFPRegistro32_033_043 = null;
    var  $SFPRegistro32_044_051 = null;
    var  $SFPRegistro32_052_053 = null;
    var  $SFPRegistro32_054_123 = null;
    var  $SFPRegistro32_124_125 = null;
    var  $SFPRegistro32_126_133 = null;
    var  $SFPRegistro32_134_134 = null;
    var  $SFPRegistro32_135_359 = null;
    var  $SFPRegistro32_360_360 = null;
/*
    REGISTRO TOTALIZADOR DO ARQUIVO - REGISTRO '90'
*/
    var  $SFPRegistro90_001_002 = null;
    var  $SFPRegistro90_003_053 = null;
    var  $SFPRegistro90_054_359 = null;
    var  $SFPRegistro90_360_360 = null;
/***************************************************************************************************/

   var $arquivo  = null;
   var $nomearq  = '/tmp/SEFIP.RE';

//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////      MÉTODOS LAYOUT DA SEFIP      /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

   function geraRegist00SFP(){
     $this->arquivo = fopen($this->nomearq,"w");
     fputs($this->arquivo,
           "00"
           .str_repeat(" ",51)
           ."1"
           ."1"
           .db_formatar(str_replace('.','',str_replace('-','',str_replace("/",'',$this->SFPRegistro00_056_069))),"s","0",14,"e",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro00_070_099),0,30)),"s"," ",30,"d",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro00_100_119),0,20)),"s"," ",20,"d",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro00_120_169),0,50)),"s"," ",50,"d",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro00_170_189),0,20)),"s"," ",20,"d",0)
           .db_formatar(str_replace('-','',str_replace('.','',substr($this->SFPRegistro00_190_197,0,8))),"s","0",8,"e",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro00_198_217),0,20)),"s"," ",20,"d",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro00_218_219),0,2)),"s"," ",2,"d",0)
           .db_formatar($this->SFPRegistro00_220_231,"s","0",12,"e",0)
           .db_formatar(strtolower(substr(db_translate($this->SFPRegistro00_232_291),0,60)),"s"," ",60,"d",0)
           .db_formatar(strtolower(substr(db_translate(str_replace("/",'',$this->SFPRegistro00_292_297)),0,6)),"s","0",6,"e",0)
           .db_formatar($this->SFPRegistro00_298_300,"s","0",3,"e",0)
           .db_formatar($this->SFPRegistro00_301_301,"s"," ",1,"d",0)
           .db_formatar($this->SFPRegistro00_302_302,"s"," ",1,"d",0)
           .db_formatar(str_replace('-','',str_replace("/",'',$this->SFPRegistro00_303_310)),"s","0",8,"e",0)
           .$this->SFPRegistro00_311_311
           .db_formatar(str_replace('-','',str_replace("/",'',$this->SFPRegistro00_312_319)),"s","0",8,"e",0)
           .db_formatar($this->SFPRegistro00_320_326,"s"," ",7,"e",0)
           ."1"
           .db_formatar(str_replace('.','',str_replace('-','',str_replace("/",'',$this->SFPRegistro00_328_341))),"s","0",14,"e",0)
           .str_repeat(" ",18)
           ."*"
           ."\r\n"
          );
   }
   function geraRegist10SFP(){
     fputs($this->arquivo,
           "10"
           ."1"
           .db_formatar($this->SFPRegistro10_004_017,"s","0",14,"e",0)
           .str_repeat("0",36)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro10_054_093),0,40)),"s"," ",40,"d",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro10_094_143),0,50)),"s"," ",50,"d",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro10_144_163),0,20)),"s"," ",20,"d",0)
           .db_formatar(str_replace('-','',str_replace('.','',substr($this->SFPRegistro10_164_171,0,8))),"s","0",8,"e",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro10_172_191),0,20)),"s"," ",20,"d",0)
           .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro10_192_193),0,2)),"s"," ",2,"d",0)
           .db_formatar($this->SFPRegistro10_194_205,"s","0",12,"e",0)
           .strtoupper($this->SFPRegistro10_206_206)
           .db_formatar($this->SFPRegistro10_207_213,"s","0",7,"e",0)
           .strtoupper($this->SFPRegistro10_214_214)
           .db_formatar($this->SFPRegistro10_215_216,"s","0",2,"d",0)
	   ."0"
	   ."1"
           .db_formatar($this->SFPRegistro10_219_221,"s","0",3,"e",0)
           .db_formatar($this->SFPRegistro10_222_225,"s","0",4,"e",0)
           .db_formatar($this->SFPRegistro10_226_229,"s","0",4,"e",0)
           .str_repeat(" ",5)
           .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->SFPRegistro10_235_249,"f")))),"s","0",15,"e",0)
           .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->SFPRegistro10_250_264,"f")))),"s","0",15,"e",0)
           .str_repeat("0",30)
           .str_repeat(" ",16)
           .str_repeat("0",45)
           .str_repeat(" ",4)
           ."*"
           ."\r\n"
          );
    }

    function geraRegist12SFP(){
      fputs($this->arquivo,
            "12"
            ."1"
            .db_formatar($this->SFPRegistro12_004_017,"s","0",14,"e",0)
            .str_repeat("0", 36)
            .trim(str_pad($this->SFPRegistro12_054_068, 15,"0", STR_PAD_LEFT))
            .str_repeat("0", 15)
            ." "
            .trim(str_pad($this->SFPRegistro12_085_099, 15,"0", STR_PAD_LEFT))
            .str_pad($this->SFPRegistro12_100_114, 15,"0", STR_PAD_LEFT)
            .str_pad($this->SFPRegistro12_115_125 ,11, " ", STR_PAD_LEFT)
            .str_pad($this->SFPRegistro12_126_129 ,4, " ", STR_PAD_LEFT)
            .str_pad($this->SFPRegistro12_130_134 ,5, " ", STR_PAD_LEFT)
            .str_pad($this->SFPRegistro12_135_140 ,6, " ", STR_PAD_LEFT)
            .str_pad($this->SFPRegistro12_141_146 ,6, " ", STR_PAD_LEFT)
            .db_formatar(str_replace('-','',str_replace('.','',substr($this->SFPRegistro12_147_161,0,15))),"s","0",15,"e",0)
            .str_pad($this->SFPRegistro12_162_167 ,6, " ", STR_PAD_LEFT)
            .str_pad($this->SFPRegistro12_168_173 ,6, " ", STR_PAD_LEFT)
            .db_formatar($this->SFPRegistro12_174_188,"s","0",15,"e",0)
            .db_formatar($this->SFPRegistro12_189_203,"s","0",15,"e",0)
            .db_formatar($this->SFPRegistro12_204_218,"s","0",15,"e",0)
            .db_formatar($this->SFPRegistro12_219_233,"s","0",15,"e",0)
            .db_formatar($this->SFPRegistro12_234_248,"s","0",15,"e",0)
            .db_formatar($this->SFPRegistro12_249_263,"s","0",15,"e",0)
            .db_formatar($this->SFPRegistro12_264_278,"s","0",15,"e",0)
            .db_formatar($this->SFPRegistro12_279_293,"s","0",15,"e",0)
            .db_formatar($this->SFPRegistro12_294_308,"s","0",15,"e",0)
            .str_repeat("0", 45)
            .str_repeat(chr(32), 6)
            ."*"
            ."\r\n"
           );
    }
    function geraRegist14SFP(){
      fputs($this->arquivo,
            "14"
            ."1"
            .db_formatar($this->SFPRegistro14_004_017,"s","0",14,"e",0)
            .str_repeat("0",36)
            .db_formatar(str_replace('-','',str_replace("/",'',str_replace('.','',substr($this->SFPRegistro14_054_064,0,11)))),"s","0",11,"e",0)
            .db_formatar(str_replace('-','',str_replace("/",'',$this->SFPRegistro14_065_072)),"s","0",8,"e",0)
            .db_formatar($this->SFPRegistro14_073_074,"s","0",2,"e",0)
            .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro14_075_144),0,70)),"s"," ",70,"d",0)
            .db_formatar((((int)$this->SFPRegistro14_073_074 >= 12)?str_replace('-','',str_replace("/",'',str_replace('.','',substr($this->SFPRegistro14_145_151,0,7)))):str_repeat(" ",7)),"s","0",7,"e",0)
            .db_formatar((((int)$this->SFPRegistro14_073_074 >= 12)?str_replace('-','',str_replace("/",'',str_replace('.','',substr($this->SFPRegistro14_152_156,0,5)))):str_repeat(" ",5)),"s","0",5,"e",0)
            .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro14_157_206),0,50)),"s"," ",50,"d",0)
            .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro14_207_226),0,20)),"s"," ",20,"d",0)
            .db_formatar(str_replace('-','',str_replace('.','',substr($this->SFPRegistro14_227_234,0,8))),"s","0",8,"e",0)
            .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro14_235_254),0,20)),"s"," ",20,"d",0)
            .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro14_255_256),0,2)),"s"," ",2,"d",0)
            .str_repeat(" ",103)
            ."*"
            ."\r\n"
           );
    }
    function geraRegist30SFP(){
      fputs($this->arquivo,
            "30"
            ."1"
            .db_formatar($this->SFPRegistro30_004_017,"s","0",14,"e",0)
            .str_repeat(" ",15)
            .db_formatar(str_replace('-','',str_replace("/",'',str_replace('.','',substr($this->SFPRegistro30_033_043,0,11)))),"s","0",11,"e",0)
            .db_formatar(str_replace('-','',str_replace("/",'',$this->SFPRegistro30_044_051)),"s","0",8,"e",0)
            .db_formatar($this->SFPRegistro30_052_053,"s","0",2,"e",0)
            .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro30_054_123),0,70)),"s"," ",70,"d",0)
            .db_formatar($this->SFPRegistro30_124_134,"s","0",11,"e",0)
            .$this->SFPRegistro30_135_141
            .$this->SFPRegistro30_142_146
            .db_formatar(str_replace('-','',str_replace("/",'',$this->SFPRegistro30_147_154)),"s","0",8,"e",0)
            .db_formatar(str_replace('-','',str_replace("/",'',$this->SFPRegistro30_155_162)),"s","0",8,"e",0)
            .db_formatar(str_replace('-','',str_replace("/",'',str_replace('.','',substr($this->SFPRegistro30_163_167,0,4)))),"s","0",5,"e",0)
            .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->SFPRegistro30_168_182,"f")))),"s","0",15,"e",0)
            .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->SFPRegistro30_183_197,"f")))),"s","0",15,"e",0)
            .str_repeat(" ",2)
            .db_formatar($this->SFPRegistro30_200_201,"s","0",2,"e",0)
            .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->SFPRegistro30_202_216,"f")))),"s","0",15,"e",0)
            .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->SFPRegistro30_217_231,"f")))),"s","0",15,"e",0)
            .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->SFPRegistro30_232_246,"f")))),"s","0",15,"e",0)
            .db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($this->SFPRegistro30_247_261,"f")))),"s","0",15,"e",0)
            .str_repeat(" ",98)
            ."*"
            ."\r\n"
           );
    }
    function geraRegist32SFP(){
      fputs($this->arquivo,
            "32"
            ."1"
            .db_formatar($this->SFPRegistro32_004_017,"s","0",14,"e",0)
            .str_repeat(" ",15)
            .db_formatar(str_replace('-','',str_replace("/",'',str_replace('.','',substr($this->SFPRegistro32_033_043,0,11)))),"s","0",11,"e",0)
            .db_formatar(str_replace('-','',str_replace("/",'',$this->SFPRegistro32_044_051)),"s","0",8,"e",0)
            .db_formatar($this->SFPRegistro32_052_053,"s","0",2,"e",0)
            .db_formatar(strtoupper(substr(db_translate($this->SFPRegistro32_054_123),0,70)),"s"," ",70,"d",0)
            .db_formatar($this->SFPRegistro32_124_125,"s"," ",2,"d",0)
            .db_formatar(str_replace('-','',str_replace("/",'',$this->SFPRegistro32_126_133)),"s","0",8,"e",0)
            .db_formatar($this->SFPRegistro32_134_134,"s"," ",1,"e",0)
            .str_repeat(" ",225)
            ."*"
            ."\r\n"
           );
    }
    function geraRegist90SFP(){
      fputs($this->arquivo,
            "90"
            .str_repeat("9",51)
            .str_repeat(" ",306)
            ."*"
            ."\r\n"
           );
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////      FINAL MÉTODOS ARQUIVO DA SEFIP      /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////
//          FECHA O ARQUIVO           //
////////////////////////////////////////
    function gera(){
       fclose($this->arquivo);
    }
////////////////////////////////////////
}




//MODULO: PESSOAL
class cl_layout_CAGED {

/***************************************************************************************************/
/***************      TXT - Variável que retorna texto a ser impresso no arquivo     ***************/
/***************************************************************************************************/

    var  $TEXTO = null;

/***************************************************************************************************/

/***************************************************************************************************/
/***************************************************************************************************/
/*********************       VARIÁVEIS USADAS PARA GERAR ARQUIVO DA CAGED      *********************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    AUTORIZADO - REGISTRO 'A'
*/
    var  $KGDRegistroA_001_001 = null;
    var  $KGDRegistroA_002_002 = null;
    var  $KGDRegistroA_003_008 = null;
    var  $KGDRegistroA_009_009 = null;
    var  $KGDRegistroA_010_011 = null;
    var  $KGDRegistroA_012_015 = null;
    var  $KGDRegistroA_016_016 = null;
    var  $KGDRegistroA_017_021 = null;
    var  $KGDRegistroA_022_022 = null;
    var  $KGDRegistroA_023_036 = null;
    var  $KGDRegistroA_037_071 = null;
    var  $KGDRegistroA_072_111 = null;
    var  $KGDRegistroA_112_119 = null;
    var  $KGDRegistroA_120_121 = null;
    var  $KGDRegistroA_122_125 = null;
    var  $KGDRegistroA_126_133 = null;
    var  $KGDRegistroA_134_138 = null;
    var  $KGDRegistroA_139_143 = null;
    var  $KGDRegistroA_144_148 = null;
    var  $KGDRegistroA_149_150 = null;
/*
    ESTABELECIMENTO - REGISTRO 'B'
*/
    var  $KGDRegistroB_001_001 = null;
    var  $KGDRegistroB_002_002 = null;
    var  $KGDRegistroB_003_016 = null;
    var  $KGDRegistroB_017_021 = null;
    var  $KGDRegistroB_022_022 = null;
    var  $KGDRegistroB_023_023 = null;
    var  $KGDRegistroB_024_031 = null;
    var  $KGDRegistroB_032_036 = null;
    var  $KGDRegistroB_037_076 = null;
    var  $KGDRegistroB_077_116 = null;
    var  $KGDRegistroB_117_136 = null;
    var  $KGDRegistroB_137_138 = null;
    var  $KGDRegistroB_139_143 = null;
    var  $KGDRegistroB_144_144 = null;
    var  $KGDRegistroB_145_150 = null;
/*
    EMPREGADO - REGISTRO 'C'
*/
    var  $KGDRegistroC_001_001 = null;
    var  $KGDRegistroC_002_002 = null;
    var  $KGDRegistroC_003_016 = null;
    var  $KGDRegistroC_017_021 = null;
    var  $KGDRegistroC_022_032 = null;
    var  $KGDRegistroC_033_033 = null;
    var  $KGDRegistroC_034_041 = null;
    var  $KGDRegistroC_042_042 = null;
    var  $KGDRegistroC_043_047 = null;
    var  $KGDRegistroC_048_055 = null;
    var  $KGDRegistroC_056_057 = null;
    var  $KGDRegistroC_058_065 = null;
    var  $KGDRegistroC_066_067 = null;
    var  $KGDRegistroC_068_069 = null;
    var  $KGDRegistroC_070_109 = null;
    var  $KGDRegistroC_110_117 = null;
    var  $KGDRegistroC_118_121 = null;
    var  $KGDRegistroC_122_128 = null;
    var  $KGDRegistroC_129_129 = null;
    var  $KGDRegistroC_130_130 = null;
    var  $KGDRegistroC_131_136 = null;
    var  $KGDRegistroC_137_137 = null;
    var  $KGDRegistroC_138_139 = null;
    var  $KGDRegistroC_140_150 = null;
/*
    ACERTO - REGISTRO 'X'
*/
    var  $KGDRegistroX_001_001 = null;
    var  $KGDRegistroX_002_002 = null;
    var  $KGDRegistroX_003_016 = null;
    var  $KGDRegistroX_017_021 = null;
    var  $KGDRegistroX_022_032 = null;
    var  $KGDRegistroX_033_033 = null;
    var  $KGDRegistroX_034_041 = null;
    var  $KGDRegistroX_042_042 = null;
    var  $KGDRegistroX_043_047 = null;
    var  $KGDRegistroX_048_055 = null;
    var  $KGDRegistroX_056_057 = null;
    var  $KGDRegistroX_058_065 = null;
    var  $KGDRegistroX_066_067 = null;
    var  $KGDRegistroX_068_069 = null;
    var  $KGDRegistroX_070_109 = null;
    var  $KGDRegistroX_110_117 = null;
    var  $KGDRegistroX_118_121 = null;
    var  $KGDRegistroX_122_122 = null;
    var  $KGDRegistroX_123_124 = null;
    var  $KGDRegistroX_125_128 = null;
    var  $KGDRegistroX_129_129 = null;
    var  $KGDRegistroX_130_130 = null;
    var  $KGDRegistroX_131_136 = null;
    var  $KGDRegistroX_137_137 = null;
    var  $KGDRegistroX_138_139 = null;
    var  $KGDRegistroX_140_150 = null;
/***************************************************************************************************/

   var $arquivo  = null;
   var $nomearq  = '/tmp/CAGED.TXT';

//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////      MÉTODOS LAYOUT DA SEFIP      /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

   function geraRegistAKGD(){
     $this->arquivo = fopen($this->nomearq,"w");
     fputs($this->arquivo,
           "A"
           ."2"
           .db_formatar(substr($this->KGDRegistroA_003_008,0,6),"s","0",6,"e",0)
           .db_formatar(substr($this->KGDRegistroA_009_009,0,1),"s","0",1,"e",0)
           .db_formatar(substr($this->KGDRegistroA_010_011,0,2),"s","0",2,"e",0)
           .db_formatar(substr($this->KGDRegistroA_012_015,0,4),"s","0",4,"e",0)
           .db_formatar(substr($this->KGDRegistroA_016_016,0,1),"s","0",1,"e",0)
           .db_formatar(substr($this->KGDRegistroA_017_021,0,5),"s","0",5,"e",0)
           .db_formatar(substr($this->KGDRegistroA_022_022,0,1),"s","0",1,"e",0)
	   .db_formatar(substr($this->KGDRegistroA_023_036,0,14),"s","0",14,"e",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroA_037_071)),0,35),"s"," ",35,"d",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroA_072_111)),0,40),"s"," ",40,"d",0)
           .db_formatar(substr(str_replace('-','',str_replace('.','',$this->KGDRegistroA_112_119)),0,8),"s","0",8,"e",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroA_120_121)),0,2),"s"," ",2,"d",0)
           .db_formatar(substr($this->KGDRegistroA_122_125,0,4),"s","0",4,"e",0)
           .db_formatar(substr(str_replace('.','',str_replace('-','',str_replace("/",'',$this->KGDRegistroA_126_133))),0,8),"s","0",8,"e",0)
           .db_formatar(substr(str_replace('.','',str_replace('-','',str_replace("/",'',$this->KGDRegistroA_134_138))),0,5),"s","0",5,"e",0)
           .db_formatar(substr($this->KGDRegistroA_139_143,0,5),"s","0",5,"e",0)
           .db_formatar(substr($this->KGDRegistroA_144_148,0,5),"s","0",5,"e",0)
           ."  "
           ."\r\n"
          );
   }
   function geraRegistBKGD(){
     fputs($this->arquivo,
           "B"
           ."1"
           .db_formatar(substr($this->KGDRegistroB_003_016,0,14),"s","0",14,"e",0)
           .db_formatar(substr($this->KGDRegistroB_017_021,0,5),"s","0",5,"e",0)
           .db_formatar(substr($this->KGDRegistroB_022_022,0,1),"s","0",1,"e",0)
           .db_formatar(substr($this->KGDRegistroB_023_023,0,1),"s","0",1,"e",0)
           .db_formatar(substr(str_replace('-','',str_replace('.','',$this->KGDRegistroB_024_031)),0,8),"s","0",8,"e",0)
           .db_formatar(substr($this->KGDRegistroB_032_036,0,5),"s","0",5,"e",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroB_037_076)),0,40),"s"," ",40,"d",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroB_077_116)),0,40),"s"," ",40,"d",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroB_117_136)),0,20),"s"," ",20,"d",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroB_137_138)),0,2),"s"," ",2,"d",0)
           .db_formatar(substr($this->KGDRegistroB_139_143,0,5),"s","0",5,"e",0)
	   ."2"
           .db_formatar(substr($this->KGDRegistroB_145_150,0,6),"s"," ",6,"d",0)
           ."\r\n"
          );
   }
   function geraRegistCKGD(){
     fputs($this->arquivo,
           "C"
           ."1"
           .db_formatar(substr($this->KGDRegistroC_003_016,0,14),"s","0",14,"e",0)
           .db_formatar(substr($this->KGDRegistroC_017_021,0,5),"s","0",5,"e",0)
           .db_formatar(substr(str_replace('.','',str_replace('-','',str_replace("/",'',$this->KGDRegistroC_022_032))),0,11),"s","0",11,"e",0)
           .db_formatar(substr($this->KGDRegistroC_033_033,0,1),"s","0",1,"e",0)
           .str_replace("/",'',db_formatar($this->KGDRegistroC_034_041,"d"))
           .db_formatar(substr($this->KGDRegistroC_042_042,0,1),"s","0",1,"e",0)
           .str_repeat(" ",5)
           .db_formatar(substr(str_replace(',','',str_replace('.','',trim(db_formatar($this->KGDRegistroC_048_055,"f")))),0,8),'s','0',8,'e',0)
           .db_formatar(substr($this->KGDRegistroC_056_057,0,2),"s","0",2,"e",0)
           .str_replace("/",'',db_formatar($this->KGDRegistroC_058_065,"d"))
           .db_formatar(substr($this->KGDRegistroC_066_067,0,2),"s","0",2,"e",0)
           .db_formatar(substr($this->KGDRegistroC_068_069,0,2),"s","0",2,"e",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroC_070_109)),0,40),"s"," ",40,"d",0)
           .db_formatar(substr($this->KGDRegistroC_110_117,0,8),"s","0",8,"e",0)
           .db_formatar(substr($this->KGDRegistroC_118_121,0,4),"s","0",4,"e",0)
           .db_formatar(substr($this->KGDRegistroC_122_128,0,7),"s"," ",7,"d",0)
           .db_formatar(substr($this->KGDRegistroC_129_129,0,1),"s","0",1,"e",0)
           ."2"
           .db_formatar(substr($this->KGDRegistroC_131_136,0,6),"s","0",6,"e",0)
           ."2"
           .db_formatar(substr($this->KGDRegistroC_138_139,0,2),"s"," ",2,"d",0)
           .db_formatar(substr($this->KGDRegistroC_140_150,0,11),"s"," ",11,"d",0)
           ."\r\n"
          );
   }
   function geraRegistXKGD(){
     fputs($this->arquivo,
           "X"
           ."1"
           .db_formatar(substr($this->KGDRegistroX_003_016,0,14),"s","0",14,"e",0)
           .db_formatar(substr($this->KGDRegistroX_017_021,0,6),"s","0",5,"e",0)
           .db_formatar(substr(str_replace('.','',str_replace('-','',str_replace("/",'',$this->KGDRegistroX_022_032))),0,11),"s","0",11,"e",0)
           .db_formatar(substr($this->KGDRegistroX_033_033,0,1),"s","0",1,"e",0)
           .str_replace("/",'',db_formatar($this->KGDRegistroX_034_041,"d"))
           .db_formatar(substr($this->KGDRegistroX_042_042,0,1),"s","0",1,"e",0)
           .str_repeat(" ",5)
           .db_formatar(substr(str_replace(',','',str_replace('.','',trim(db_formatar($this->KGDRegistroX_048_055,"f")))),0,8),'s','0',8,'e',0)
           .db_formatar(substr($this->KGDRegistroX_056_057,0,2),"s","0",2,"e",0)
           .str_replace("/",'',db_formatar($this->KGDRegistroX_058_065,"d"))
           .db_formatar(substr($this->KGDRegistroX_066_067,0,2),"s","0",2,"e",0)
           .db_formatar(substr($this->KGDRegistroX_068_069,0,2),"s","0",2,"e",0)
           .db_formatar(substr(strtoupper(db_translate($this->KGDRegistroX_070_109)),0,40),"s"," ",40,"d",0)
           .db_formatar(substr($this->KGDRegistroX_110_117,0,8),"s","0",8,"e",0)
           .db_formatar(substr($this->KGDRegistroX_118_121,0,4),"s","0",4,"e",0)
           ."2"
           .db_formatar(substr($this->KGDRegistroX_123_124,0,2),"s","0",2,"e",0)
           .db_formatar(substr($this->KGDRegistroX_125_128,0,4),"s","0",4,"e",0)
           .db_formatar(substr($this->KGDRegistroX_129_129,0,1),"s","0",1,"e",0)
           ."2"
           .db_formatar(substr($this->KGDRegistroX_131_136,0,6),"s","0",6,"e",0)
           ."2"
           .db_formatar(substr($this->KGDRegistroX_138_139,0,2),"s"," ",2,"d",0)
           .db_formatar(substr($this->KGDRegistroX_140_150,0,11),"s"," ",11,"d",0)
           ."\r\n"
          );
   }

//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////      FINAL MÉTODOS ARQUIVO DA SEFIP      /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////
//          FECHA O ARQUIVO           //
////////////////////////////////////////
    function gera(){
       fclose($this->arquivo);
    }
////////////////////////////////////////
}




//MODULO: PESSOAL

class cl_layout_IPE {

/***************************************************************************************************/
/***************      TXT - Variável que retorna texto a ser impresso no arquivo     ***************/
/***************************************************************************************************/

    var  $TEXTO = null;

/***************************************************************************************************/

/***************************************************************************************************/
/***************************************************************************************************/
/**********************       VARIÁVEIS USADAS PARA GERAR ARQUIVO DO IPE      **********************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    HEADER DE ARQUIVO
*/
    var  $IPEHeader_001_003 = null;
    var  $IPEHeader_004_011 = null;
    var  $IPEHeader_012_017 = null;
    var  $IPEHeader_018_018 = null;
    var  $IPEHeader_019_250 = null;
/*
    REGISTRO
*/
    var  $IPERegistro_001_003 = null;
    var  $IPERegistro_004_011 = null;
    var  $IPERegistro_012_024 = null;
    var  $IPERegistro_025_026 = null;
    var  $IPERegistro_027_058 = null;
    var  $IPERegistro_059_098 = null;
    var  $IPERegistro_099_106 = null;
    var  $IPERegistro_107_114 = null;
    var  $IPERegistro_115_122 = null;
    var  $IPERegistro_123_130 = null;
    var  $IPERegistro_131_131 = null;
    var  $IPERegistro_132_132 = null;
    var  $IPERegistro_133_142 = null;
    var  $IPERegistro_143_153 = null;
    var  $IPERegistro_154_164 = null;
    var  $IPERegistro_165_250 = null;
/*
    TRAILLER
*/
    var  $IPETrailler_001_003 = null;
    var  $IPETrailler_004_011 = null;
    var  $IPETrailler_012_016 = null;
    var  $IPETrailler_017_033 = null;
    var  $IPETrailler_034_250 = null;
/***************************************************************************************************/

   var $arquivo  = null;
   var $nomearq  = null;

  // Construtor
  function cl_layout_IPE(){
    $this->nomearq = '/tmp/IPE'.date("mY").'.TXT';
  }


//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////      MÉTODOS LAYOUT DA SEFIP      /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

   function geraHeaderIPE(){
     $this->arquivo = fopen($this->nomearq,"w");
     fputs($this->arquivo,
           db_formatar(substr($this->IPEHeader_001_003,0,3),"s","0",3,"e",0)
           .str_repeat("0",8)
           .db_formatar(substr($this->IPEHeader_012_017,0,6),"s","0",6,"e",0)
           .db_formatar(substr($this->IPEHeader_018_018,0,1),"s","0",1,"e",0)
           .str_repeat(" ",232)
           ."\r\n"
          );
   }
   function geraRegistIPE(){
     fputs($this->arquivo,
           db_formatar(substr($this->IPERegistro_001_003,0,3),"s","0",3,"e",0)
           .db_formatar(substr($this->IPERegistro_004_011,0,8),"s","0",8,"e",0)
           .db_formatar(substr($this->IPERegistro_012_024,0,13),"s","0",13,"e",0)
           .db_formatar(substr($this->IPERegistro_025_026,0,2),"s","0",2,"e",0)
           .db_formatar(substr(strtoupper(db_translate($this->IPERegistro_027_058)),0,32),"s"," ",32,"d",0)
           .db_formatar(substr(strtoupper(db_translate($this->IPERegistro_059_098)),0,40),"s"," ",40,"d",0)
           .db_formatar(substr(str_replace("/",'',str_replace('-','',str_replace('.','',$this->IPERegistro_099_106))),0,8),"s","0",8,"e",0)
           .db_formatar(substr(str_replace("/",'',str_replace('-','',str_replace('.','',$this->IPERegistro_107_114))),0,8),"s","0",8,"e",0)
           .db_formatar(substr(str_replace("/",'',str_replace('-','',str_replace('.','',$this->IPERegistro_115_122))),0,8),"s","0",8,"e",0)
           .db_formatar(substr(str_replace("/",'',str_replace('-','',str_replace('.','',$this->IPERegistro_123_130))),0,8),"s","0",8,"e",0)
           .$this->IPERegistro_131_131
           .$this->IPERegistro_132_132
           .db_formatar(substr(str_replace("/",'',str_replace('-','',str_replace('.','',$this->IPERegistro_133_142))),0,10),"s","0",10,"e",0)
           .db_formatar(substr(str_replace("/",'',str_replace('-','',str_replace('.','',$this->IPERegistro_143_153))),0,11),"s","0",11,"e",0)
           .db_formatar(substr(str_replace(',','',str_replace('.','',trim(db_formatar($this->IPERegistro_154_164,"f")))),0,11),"s","0",11,"e",0)
           .str_repeat(" ",86)
           ."\r\n"
          );
   }
   function geraTraillerIPE(){
     fputs($this->arquivo,
           db_formatar(substr($this->IPETrailler_001_003,0,3),"s","0",3,"e",0)
           .str_repeat("9",8)
           .db_formatar(substr($this->IPETrailler_012_016,0,5),"s","0",5,"e",0)
           .db_formatar(substr(str_replace(',','',str_replace('.','',trim(db_formatar($this->IPETrailler_017_033,"f")))),0,17),"s","0",17,"e",0)
           .str_repeat(" ",217)
           ."\r\n"
          );
   }
//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////       FINAL MÉTODOS ARQUIVO DO IPE       /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////
//          FECHA O ARQUIVO           //
////////////////////////////////////////
    function gera(){
       fclose($this->arquivo);
    }
////////////////////////////////////////
}




//MODULO: PESSOAL

class cl_layout_BLV {

/***************************************************************************************************/
/***************      TXT - Variável que retorna texto a ser impresso no arquivo     ***************/
/***************************************************************************************************/

    var  $TEXTO = null;

/***************************************************************************************************/

/***************************************************************************************************/
/***************************************************************************************************/
/*****************       VARIÁVEIS USADAS PARA GERAR ARQUIVO DO BLV BANRISUL       *****************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    HEADER DE ARQUIVO
*/
    var  $BLVHeader_001_006 = null;
    var  $BLVHeader_007_127 = null;
    var  $BLVHeader_128_128 = null;
/*
    REGISTRO
*/
    var  $BLVRegistro_001_005 = null;
    var  $BLVRegistro_006_015 = null;
    var  $BLVRegistro_016_050 = null;
    var  $BLVRegistro_051_075 = null;
    var  $BLVRegistro_076_090 = null;
    var  $BLVRegistro_091_096 = null;
    var  $BLVRegistro_097_127 = null;
    var  $BLVRegistro_128_128 = null;
/*
    TRAILLER
*/
    var  $BLVTrailler_001_006 = null;
    var  $BLVTrailler_007_021 = null;
    var  $BLVTrailler_022_127 = null;
    var  $BLVTrailler_128_128 = null;
/***************************************************************************************************/

   var $arquivo  = null;
   var $nomearq  = null;

  // Construtor
  function cl_layout_BLV(){
    $this->nomearq = '/tmp/BLV'.date("mY").'.TXT';
  }


//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////      MÉTODOS LAYOUT DA SEFIP      /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

   function geraHeaderBLV(){
     $this->arquivo = fopen($this->nomearq,"w");
     fputs($this->arquivo,
            "BLV000"
           .str_repeat(" ",121)
           ."*"
           ."\r\n"
          );
   }
   function geraRegistBLV(){
     fputs($this->arquivo,
            db_formatar(substr($this->BLVRegistro_001_005,0,5),"s","0",5,"e",0)
           .db_formatar(substr($this->BLVRegistro_006_015,0,10),"s","0",10,"e",0)
           .db_formatar(substr(strtoupper(db_translate($this->BLVRegistro_016_050)),0,35),"s"," ",35,"d",0)
           .str_repeat(" ",25)
           .db_formatar(substr(trim(str_replace('.','',str_replace(',','',db_formatar($this->BLVRegistro_076_090,"f")))),0,15),"s","0",15,"e",0)
           .db_formatar(substr($this->BLVRegistro_091_096,0,6),"s","0",6,"e",0)
           .str_repeat(" ",31)
           ."*"
           ."\r\n"
          );
   }
   function geraTraillerBLV(){
     fputs($this->arquivo,
            "BLV999"
           .db_formatar(substr(trim(str_replace('.','',str_replace(',','',db_formatar($this->BLVTrailler_007_021,"f")))),0,15),"s","0",15,"e",0)
           .str_repeat(" ",106)
           ."*"
           ."\r\n"
          );
   }
//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////       FINAL MÉTODOS ARQUIVO DO BLV       /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////
//          FECHA O ARQUIVO           //
////////////////////////////////////////
    function gera(){
       fclose($this->arquivo);
    }
////////////////////////////////////////
}












class cl_layout_PREVID {

/***************************************************************************************************/
/***************      TXT - Variável que retorna texto a ser impresso no arquivo     ***************/
/***************************************************************************************************/

    var  $TEXTO = null;

/***************************************************************************************************/

/***************************************************************************************************/
/***************************************************************************************************/
/*****************     VARIÁVEIS USADAS PARA GERAR ARQUIVO DO PREVID - CAPSEM      *****************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    HEADER DE ARQUIVO
*/
    var  $PVDHeader_001_002 = null;
    var  $PVDHeader_003_009 = null;
    var  $PVDHeader_010_027 = null;
/*
    REGISTRO DETALHE
*/
    var  $PVDRegistro_001_002 = null;
    var  $PVDRegistro_006_015 = null;
    var  $PVDRegistro_016_050 = null;
    var  $PVDRegistro_051_075 = null;
    var  $PVDRegistro_076_090 = null;
    var  $PVDRegistro_091_096 = null;
    var  $PVDRegistro_097_127 = null;
    var  $PVDRegistro_128_128 = null;
/*
    TRAILLER
*/
    var  $PVDTrailler_001_006 = null;
    var  $PVDTrailler_007_021 = null;
    var  $PVDTrailler_022_127 = null;
    var  $PVDTrailler_128_128 = null;
/***************************************************************************************************/

   var $arquivo  = null;
   var $nomearq  = null;

  // Construtor
  function cl_layout_PVD(){
    $this->nomearq = '/tmp/PVD'.date("mY").'.TXT';
  }


//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////      MÉTODOS LAYOUT DA SEFIP      /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

   function geraHeaderPVD(){
     $this->arquivo = fopen($this->nomearq,"w");
     fputs($this->arquivo,
            "PVD000"
           .str_repeat(" ",121)
           ."*"
           ."\r\n"
          );
   }
   function geraRegistPVD(){
     fputs($this->arquivo,
            db_formatar(substr($this->PVDRegistro_001_005,0,5),"s","0",5,"e",0)
           .db_formatar(substr($this->PVDRegistro_006_015,0,10),"s","0",10,"e",0)
           .db_formatar(substr(strtoupper(db_translate($this->PVDRegistro_016_050)),0,35),"s"," ",35,"d",0)
           .str_repeat(" ",25)
           .db_formatar(substr(trim(str_replace('.','',str_replace(',','',db_formatar($this->PVDRegistro_076_090,"f")))),0,15),"s","0",15,"e",0)
           .db_formatar(substr($this->PVDRegistro_091_096,0,6),"s","0",6,"e",0)
           .str_repeat(" ",31)
           ."*"
           ."\r\n"
          );
   }
   function geraTraillerPVD(){
     fputs($this->arquivo,
            "PVD999"
           .db_formatar(substr(trim(str_replace('.','',str_replace(',','',db_formatar($this->PVDTrailler_007_021,"f")))),0,15),"s","0",15,"e",0)
           .str_repeat(" ",106)
           ."*"
           ."\r\n"
          );
   }
//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////       FINAL MÉTODOS ARQUIVO DO PVD       /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////
//          FECHA O ARQUIVO           //
////////////////////////////////////////
    function gera(){
       fclose($this->arquivo);
    }
////////////////////////////////////////
}
