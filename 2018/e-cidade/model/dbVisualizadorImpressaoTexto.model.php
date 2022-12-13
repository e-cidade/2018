<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

/**
 * @fileoverview Classe que cria a interface para visualizacao, download e impressao de arquivos texto.
 * Este arquivo inclui o arquivo de estilos dbVisualizadorImpressaoTexto.style.css e o script 
 * dbVisualizadorImpressaoTexto.js
 * @author Tony Farney Bruck Mendes Ribeiro
 */

  class DBVisualizadorImpressaoTexto {

    /**
     * Array de string com o conte�do de cada arquivo TXT a ser impresso em cada posi��o do vetor.
     * @var array(string) $sArquivoModelo
     */
    private $aArquivos = array();
    
    /**
     * N�mero de linhas que cada folha suporta na impress�o.
     * @var integer $iLinhasPagina
     */
    private $iLinhasPagina = 65;

    /**
     * N�mero de colunas que cada folha suporta na impress�o.
     * @var integer $iColunasPagina
     */
    private $iColunasPagina = 80;

    /**
     * Altura do visualizador (definida pelo estilo css "height").
     * @var string $sAltura
     */
    private $sAltura = '100%';

    /**
     * Largura do visualizador (definida pelo estilo css "width").
     * @var string $sLargura
     */
    private $sLargura = '100%';

    /**
     * ID do elemento on de ser� inserido o visualizador.
     * @var string $sIdElementoPai
     */
    private $sIdElementoPai = '';

    /**
     * Nome do arquivo auxiliar a ser gerado. Setado pelo m�todo gravarArquivoAuxiliar().
     * @var string $sNomeArquivoAuxiliar
     */
    private $sNomeArquivoAuxiliar = '';

   /**
    * M�todo construtor da classe.
    * @construct
    * @param string $sIdElementoPai ID do elemento html onde sera colocado o visualizador. 
    * @param array(string) $aArquivos. Arquivos a serem impressos. Ao t�rmino de um arquivo, 
    * o pr�ximo inicia no come�o da pr�xima p�gina. Se quiser impress�o sequencial, passar tudo como
    * um �nico arquivo.
    */
    function  __construct($sIdElementoPai, $aArquivos = array()) {
      
      if (empty($sIdElementoPai)) {
        throw new Exception('Imposs�vel carregar o visualizador. Elemento pai n�o informado.');
      }
      $this->sIdElementoPai = $sIdElementoPai;
      if (!empty($aArquivos)) {
        
        if (!is_array($aArquivos)) {
          $this->aArquivos = array($aArquivos);
        } else {
          $this->aArquivos = $aArquivos;
        }

      }

    }
    
    /**
     * M�todo que define o n�mero de linhas de colunas (dimens�es) da p�gina para visualiza��o.
     * @param $iLinhas N�mero de linhas (Intervalo v�lido: 1 - 2000)
     * @param $iColunas N�mero de colunas (Intervalo v�lido: 1 - 5000)
     */
    public function setDimensoes($iLinhas, $iColunas) {
      
      if ($iLinhas < 1 || $iLinhas > 2000 || $iColunas < 0 || $iColunas > 5000) {
        throw new Exception("Dimens�es inv�lidas: $iLinhas (linhas) X $iColunas (colunas).");
      }
      $this->iLinhasPagina  = $iLinhas;
      $this->iColunasPagina = $iColunas;

    }

    /**
     * M�todo que seta o tamanho do visualizador.
     * @param string sAltura Altura do visualizador. Pode ser em %, px 
     * ou qualquer outra unidade aceita pelo estilo css "height".
     */
    public function setAltura($sAltura) {
      
      $this->sAltura = $sAltura;
    
    }

    /**
     * M�todo que seta a largura do visualizador.
     * @param string sLargura Largura do visualizador. Pode ser em %, px 
     * ou qualquer outra unidade aceita pelo estilo css "width".
     */
    public function setLargura($sLargura) {
      
      $this->sLargura = $sLargura;
    
    }

    /**
     * M�todo que salva em disco os arquivos, tudo em um �nico .TXT. Para identificar
     * O t�rmino de um arquivo e o in�cio de outro � identificado pelo caracter 28 (base 10)
     * da tabela ASCII (o FS - File Separator), que � gerado pelo comando chr(28). Esta fun��o
     * gera e seta o nome do arquivo auxiliar ($sNomeArquivoAuxiliar)
     */
    private function gravarArquivoAuxiliar() {

      $sNomeArquivo = './tmp/dbvisualizador_'.date('YmdHisu').uniqid();
      
      $pArquivo = fopen($sNomeArquivo, 'w');
      if (!$pArquivo) {
        throw new Exception("N�o foi poss�vel salvar o arquivo auxiliar do visulizador em '$sNomeArquivo'.");
      }
      
      $sSep = '';
      foreach ($this->aArquivos as $sArquivo) {

        $lRetorno = fwrite($pArquivo, $sArquivo.$sSep);
        if ($lRetorno === false) {

          fclose($pArquivo);
          throw new Exception("N�o foi poss�vel salvar o arquivo em '$sDestino'. Erro ao escrever no arquivo.");

        }
        $sSep = chr(28);

      }
      fclose($pArquivo);

      $this->sNomeArquivoAuxiliar = $sNomeArquivo;

    }
    
    /**
     * M�todo que cont�m / gera o c�digo javascript / html do visualizador.
     */
    private function gerarVisualizador() {
?>
      <script language="JavaScript" type="text/javascript" src="./scripts/dbVisualizadorImpressaoTexto.js"></script>
      <link href="./estilos/dbVisualizadorImpressaoTexto.style.css" rel="stylesheet" type="text/css">
      <script>
        oTxtLinhas = document.createElement('span');
        oTxtLinhas.innerHTML = "N�mero de linhas: ";
        oTxtColunas = document.createElement('span');
        oTxtColunas.innerHTML = " N�mero de colunas: ";

        oInpLinhas = document.createElement('input');
        oInpLinhas.setAttribute('type', 'text');
        oInpLinhas.setAttribute('name', 'iLinhas');
        oInpLinhas.setAttribute('id', 'iLinhas');
        oInpLinhas.setAttribute('size', '4');
        oInpLinhas.setAttribute('maxlength', '4');
        oInpLinhas.setAttribute('value', '<?=$this->iLinhasPagina?>');
        oInpLinhas.onchange = function () {

          oVisualizador.renderizarArquivos(oVisualizador.setDimensoes(this.value, 
                                                                      document.getElementById('iColunas').value
                                                                     )
                                         );

        }
        oInpColunas = document.createElement('input');
        oInpColunas.setAttribute('type', 'text');
        oInpColunas.setAttribute('name', 'iColunas');
        oInpColunas.setAttribute('id', 'iColunas');
        oInpColunas.setAttribute('size', '4');
        oInpColunas.setAttribute('maxlength', '4')
        oInpColunas.setAttribute('value', '<?=$this->iColunasPagina?>');
        oInpColunas.onchange = function () {

          oVisualizador.renderizarArquivos(oVisualizador.setDimensoes(document.getElementById('iLinhas').value, 
                                                                      this.value
                                                                     )
                                         );

        }
        
        oElementoPai = document.getElementById("<?=$this->sIdElementoPai?>");
        oElementoPai.appendChild(oTxtLinhas);
        oElementoPai.appendChild(oInpLinhas);
        oElementoPai.appendChild(oTxtColunas);
        oElementoPai.appendChild(oInpColunas);
        
        oVisualizador = new DBVisualizadorImpressaoTexto("<?=$this->sIdElementoPai?>", 
                                                         "<?=$this->sAltura?>", 
                                                         "<?=$this->sLargura?>"
                                                        );
        oVisualizador.setDimensoes(<?=$this->iLinhasPagina?>, <?=$this->iColunasPagina?>);
        
<?
        foreach ($this->aArquivos as $sArquivo) {

          $sArquivo = str_replace(array('"', "\n"), array('\"', '\n'), $sArquivo);
          //remover caracteres de controle de impress�o
          $sArquivo = str_replace(chr(15), '<font size=\"1\">', $sArquivo);
          $sArquivo = str_replace(chr(18), '</font>', $sArquivo);

?>
          oVisualizador.addArquivo("<?=$sArquivo?>");
<?
        } // Fim foreach
?>
        oVisualizador.renderizarArquivos();

        /* Bot�es */

        /* Imprimir */
        oImp = document.createElement('input');
        oImp.setAttribute('type', 'button');
        oImp.setAttribute('name', 'imprimir');
        oImp.setAttribute('id', 'imprimir');
        oImp.setAttribute('value', 'Imprimir');
        oImp.onclick = function () {

          oJan = window.open("db_impressaoMatricial.php?sEndArquivo=<?=$this->sNomeArquivoAuxiliar?>",
                             'width='+(screen.availWidth - 5)+',height='+(screen.availHeight - 40)+
                             ',scrollbars=1,location=0 '
                            );
          oJan.moveTo(0, 0);

        }
        
        /* Download */
        oDown = document.createElement('input');
        oDown.setAttribute('type', 'button');
        oDown.setAttribute('name', 'download');
        oDown.setAttribute('id', 'download');
        oDown.setAttribute('value', 'Download');
        oDown.onclick = function () {
          
          oJan = window.open("db_downloadTexto.php?sEndArquivo=<?=$this->sNomeArquivoAuxiliar?>",
                             'width='+(screen.availWidth - 5)+',height='+(screen.availHeight - 40)+
                             ',scrollbars=1,location=0 '
                            );
          oJan.moveTo(0, 0);

        }

        /* Fechar */
        oFechar = document.createElement('input');
        oFechar.setAttribute('type', 'button');
        oFechar.setAttribute('name', 'fechar');
        oFechar.setAttribute('id', 'fechar');
        oFechar.setAttribute('value', 'Fechar');
        oFechar.onclick = function () {
          
          window.close();

        }
       
        var sEspacos = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        oCenter = document.createElement('center');
        oCenter.appendChild(document.createElement('br'));

        oCenter.appendChild(oImp);

        oS = document.createElement('span');
        oS.innerHTML = sEspacos;
        oCenter.appendChild(oS);

        oCenter.appendChild(oDown);

        oS = document.createElement('span');
        oS.innerHTML = sEspacos;
        oCenter.appendChild(oS);

        oCenter.appendChild(oFechar);

        oElementoPai.appendChild(oCenter);
     </script>
<?
    }
   
    /**
     * M�todo que adiciona uma arquivo no final do vetor de arquivos.
     * @param string $sArquivo Conte�do (texto) do arquivo.
     */
    public function addArquivo($sArquivo) {
      $this->aArquivo[] = $sArquivo;
    }
    

    /**
     * M�todo que gera o visualizador.
     */
    public function visualizar() {
      
      try {

        $this->gravarArquivoAuxiliar();
        $this->gerarVisualizador();

      } catch (Exception $oE) {
        throw $oE;
      }

    }
    
  }