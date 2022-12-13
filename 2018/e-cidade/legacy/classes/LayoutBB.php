<?php
class LayoutBB {

    /*
        CABEÇALHO
    */
    var $cabec01 = '9';
    //tipo do registro - informar :0(zero) 

    var $cabec02 = '9';
    //código da remessa - informar :1

    var $cabec03 = 'xxxxxxx';
    //brancos

    var $cabec04 = '99'; //12
    //tipo de serviço -  informar:03

    var $cabec05 = 'x';
    //indicar cgc - informar:branco

    var $cabec06 = '999-99';
    //valor da tarifa a ser cobrada pelo banco para cada lançamento 
    //efetuado informar "00000"

    var $cabec07 = 'xxxxxxx';
    //brancos

    var $cabec08 = '9999';
    //prefixo da agência do BB onde a empresa mantém a sua conta de depósitos

    var $cabec09 = 'x';
    //dígito  verificador do prefixo da agência (módulo 11 - ver capitulo "Cálculo do digito
    //verificador" )

    var $cabec10 = '999999999';
    //numero de depósitos da empresa

    var $cabec11 = 'x';
    //digito verificador do numero da conta da empresa(modulo 11)

    var $cabec12 = 'xxxxx';
    //brancos

    var $cabec13 = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
    //nome da empresa

    var $cabec14 = '999';
    //código do BB - informar: 001

    var $cabec15 = '999999';
    //numero do convenio. Dado fornecido pelo banco, imprescindivel para
    //o processamento do arquivo 

    var $cabec16 = 'xxx';
    //tipo de retorno desejado. informar brancos. O tipo de estorno disponibilizado
    //deverá ser configurado no cadastramento do convênio junto ao banco.
    //Será identificado no retorno, nas posições 186 a 194

    var $cabec17 = 'xxxxxxxxxx';
    //campo de livre uso do conveniente  

    var $cabec18 = '99';
    //meio fisico de retorno.Informar zeros. O meio de retorno deverá ser 
    //configurado no cadastramento do convenio no sistema do banco . Pode ser por edi ouu mainframe 

    var $cabec19 = '999';
    //de uso do banco, para controle de remessas por meio fisico de retorno edi ou mainframe

    var $cabec20 = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';  //46 char 
    //brancos

    var $cabec21 = 'xxxxxxxxxxxxx';
    //uso exclusivo do sistema

    var $cabec22 = 'xxxx';
    //informar novo para possibilitar o recebimento de arquivo-retorno,
    //conforme campos 3,4 e 5 do detalhe  

    var $cabec23 = 'xxxxxxxxxxx';
    //brancos

    var $cabec24 = 'xxxxxxxxx';
    //No arquivo remessa: informar brancos
    //No arquivo retorno : tipo de retorno que esta sendo disponibilizado 
    //para o cliente, conforme informação do camo 16 no arquivo-remessa. Pode ser
    //RETPREVIA-retorno de prévias
    //RETPROCES-retorno de processamento
    //RETCONSOL-retorno de confirmação de processamento

    var $cabec25 = '999999';
    //sequencial informar: 000001

    /*
          FINAL CABEÇALHO
    */

    /*
            CORPO
    */
    var $corp01 = '9';  //pos: 001 a 001 
    //tipo do registro - informar :1

    var $corp02 = 'x';  //pos: 002 a 002 
    //brancos

    var $corp03 = '9';  //pos: 003 a 003
    //indicador de conferência da agênci, conta e CPF/CGC do favorecido

    var $corp04 = 'xxxxxxxxxxxx'; //pos:004 a 015 
    //CPF, CGC ou PIS/PASEP

    var $corp05 = 'xx'; //pos:016 a 017 
    //digito verificador

    var $corp06 = '9999'; //pos:018 a 021 
    //Arquivo remessa, informar:0
    //Arquivo retorno, informar: prefixo da agencia BB

    var $corp07 = 'x'; //pos:022 a 023
    //Arquivo remessa, informar:0
    //Arquivo retorno, informar: DV do prefixo da agencia BB

    var $corp08 = '999999999'; //pos:023 a 031
    //Arquivo remessa, informar:0
    //Arquivo retorno, informar: numero da conta do banco onde foi efetivado o credito

    var $corp09 = 'x'; //pos:032 a 032
    //Arquivo remessa, informar:0
    //Arquivo retorno, informar: o DV do numero da conta onde foi efetivado o credito

    var $corp10 = 'xxxxxxxxxx'; //pos: 033 a 042
    //livre uso do conveniente

    var $corp11 = 'xxxxxxxx'; //043 a 050 
    //BRANCOS  

    var $corp12 = 'xxxxxx'; //pos: 051 a 056
    //numero identificador -  campo de livre uso do conveniente

    var $corp13 = '999'; // pos: 057 a 059
    //código da camara de compensação

    var $corp14 = 'xxx'; //pos:060 a 062
    //código do banco destinatario do crédito(brancos se for BB)

    var $corp15 = 'xxxx'; //pos:063 a 066
    //prefixo da agencia do favorecido

    var $corp16 = 'x'; //pos 067 a 067
    //digito verificador - prefixo da agencia do favorecido 
    //em branco se for prefixo sem DV

    var $corp17 = '999999999999'; //pos 068 a 079 
    //numero da conta de depósito do favorecido

    var $corp18 = 'X'; //pos 080 a 080 
    //digito verificador  da conta do favorecido para creditos no BB

    var $corp19 = 'XX'; //pos 081 a 082
    //brancos

    var $corp20 = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; //pos 083 a 122 ----- 40 chars
    //nome do favorecido

    var $corp21 = '999999'; //pos 123 a 128 
    //data do pagamento ddmmaa

    var $corp22 = '99999999999-99'; //pos 129 a 141
    //valor do credito

    var $corp23 = '999'; //pos 142 a 144
    //codigo do servico

    var $corp24 = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; //pos 145 a 184  --- 40chars
    //mensagem de livre uso da empresa

    var $corp25 = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; //pos 185 a 194  --- 10chars
    //brancos

    var $corp26 = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; //pos 195 a 184  --- 6chars
    // sequencial do registro
    /*
      FINAL CORPO
    */

    var $arquivo = null;
    var $texto = null;

    var $nomearq = '/tmp/modelo.txt';

    function gera_cabecalho() {
        $this->arquivo = fopen($this->nomearq, "w");

        $this->texto .= (
            $this->cabec01
            . $this->cabec02
            . $this->cabec03
            . $this->cabec04
            . $this->cabec05
            . $this->cabec06
            . $this->cabec07
            . $this->cabec08
            . $this->cabec09
            . $this->cabec10
            . $this->cabec11
            . $this->cabec12
            . $this->cabec13
            . $this->cabec14
            . $this->cabec15
            . $this->cabec16
            . $this->cabec17
            . $this->cabec18
            . $this->cabec19
            . $this->cabec20
            . $this->cabec21
            . $this->cabec22
            . $this->cabec23
            . $this->cabec24
            . $this->cabec25
            . "\r\n"

        );

        fputs($this->arquivo,
            $this->cabec01
            . $this->cabec02
            . $this->cabec03
            . $this->cabec04
            . $this->cabec05
            . $this->cabec06
            . $this->cabec07
            . $this->cabec08
            . $this->cabec09
            . $this->cabec10
            . $this->cabec11
            . $this->cabec12
            . $this->cabec13
            . $this->cabec14
            . $this->cabec15
            . $this->cabec16
            . $this->cabec17
            . $this->cabec18
            . $this->cabec19
            . $this->cabec20
            . $this->cabec21
            . $this->cabec22
            . $this->cabec23
            . $this->cabec24
            . $this->cabec25
            . "\r\n"

        );


        //fclose($fd1);  
    }

    function gera_corpo()
    {

        $this->texto .= (
            $this->corp01
            . $this->corp02
            . $this->corp03
            . $this->corp04
            . $this->corp05
            . $this->corp06
            . $this->corp07
            . $this->corp08
            . $this->corp09
            . $this->corp10
            . $this->corp11
            . $this->corp12
            . $this->corp13
            . $this->corp14
            . $this->corp15
            . $this->corp16
            . $this->corp17
            . $this->corp18
            . $this->corp19
            . $this->corp20
            . $this->corp21
            . $this->corp22
            . $this->corp23
            . $this->corp24
            . $this->corp25
            . $this->corp26
            . "\r\n"
        );

        fputs($this->arquivo,
            $this->corp01
            . $this->corp02
            . $this->corp03
            . $this->corp04
            . $this->corp05
            . $this->corp06
            . $this->corp07
            . $this->corp08
            . $this->corp09
            . $this->corp10
            . $this->corp11
            . $this->corp12
            . $this->corp13
            . $this->corp14
            . $this->corp15
            . $this->corp16
            . $this->corp17
            . $this->corp18
            . $this->corp19
            . $this->corp20
            . $this->corp21
            . $this->corp22
            . $this->corp23
            . $this->corp24
            . $this->corp25
            . $this->corp26
            . "\r\n"
        );
    }

    function gera_trailer()
    {

        $this->texto .= (
            $this->rodap01
            . $this->rodap02
            . $this->rodap03
            . "\r\n"
        );

        fputs($this->arquivo,
            $this->rodap01
            . $this->rodap02
            . $this->rodap03
            . "\r\n"
        );
    }

    function gera()
    {
        fclose($this->arquivo);
    }
}
