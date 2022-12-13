<?php

class LayoutBS
{

    /*
        CABEÇALHO
    */
    var $cabec101 = null;
    var $cabec102 = null;
    var $cabec103 = null;
    var $cabec104 = null;
    var $cabec105 = null;
    var $cabec106 = null;
    var $cabec107 = null;
    var $cabec108 = null;
    var $cabec109 = null;
    var $cabec110 = null;
    var $cabec111 = null;
    var $cabec112 = null;
    var $cabec113 = null;
    var $cabec114 = null;
    var $cabec115 = null;
    var $cabec116 = null;
    var $cabec117 = null;
    var $cabec118 = null;
    var $cabec119 = null;
    var $cabec120 = null;
    var $cabec121 = null;
    var $cabec122 = null;
    var $cabec123 = null;
    var $cabec124 = null;
    var $cabec125 = null;
    var $cabec126 = null;
    var $cabec127 = null;

    var $cabec201 = null;
    var $cabec202 = null;
    var $cabec203 = null;
    var $cabec204 = null;
    var $cabec205 = null;
    var $cabec206 = null;
    var $cabec207 = null;
    var $cabec208 = null;
    var $cabec209 = null;
    var $cabec210 = null;
    var $cabec211 = null;
    var $cabec212 = null;
    var $cabec213 = null;
    var $cabec214 = null;
    var $cabec215 = null;
    var $cabec216 = null;
    var $cabec217 = null;
    var $cabec218 = null;
    var $cabec219 = null;
    var $cabec220 = null;
    var $cabec221 = null;
    var $cabec222 = null;
    var $cabec223 = null;
    var $cabec224 = null;
    var $cabec225 = null;
    var $cabec226 = null;
    var $cabec227 = null;

    /*
          FINAL CABEÇALHO
    */


    /*
            CORPO
    */
    var $detalhe01 = null;
    var $detalhe02 = null;
    var $detalhe03 = null;
    var $detalhe04 = null;
    var $detalhe05 = null;
    var $detalhe06 = null;
    var $detalhe07 = null;
    var $detalhe08 = null;
    var $detalhe09 = null;
    var $detalhe10 = null;
    var $detalhe11 = null;
    var $detalhe12 = null;
    var $detalhe13 = null;
    var $detalhe14 = null;
    var $detalhe15 = null;
    var $detalhe16 = null;
    var $detalhe17 = null;
    var $detalhe18 = null;
    var $detalhe19 = null;
    var $detalhe20 = null;
    var $detalhe21 = null;
    var $detalhe22 = null;
    var $detalhe23 = null;
    var $detalhe24 = null;
    var $detalhe25 = null;
    var $detalhe26 = null;
    var $detalhe27 = null;
    var $detalhe28 = null;
    var $detalhe29 = null;
    var $detalhe30 = null;


    /*
            TRAILLER
    */
    var $roda101 = null;
    var $roda102 = null;
    var $roda103 = null;
    var $roda104 = null;
    var $roda105 = null;
    var $roda106 = null;
    var $roda107 = null;
    var $roda108 = null;
    var $roda109 = null;
    var $roda110 = null;
    var $roda111 = null;
    var $roda112 = null;
    var $roda113 = null;
    var $roda114 = null;
    var $roda115 = null;

    var $roda201 = null;
    var $roda202 = null;
    var $roda203 = null;
    var $roda204 = null;
    var $roda205 = null;
    var $roda206 = null;
    var $roda207 = null;
    var $roda208 = null;
    var $roda209 = null;
    var $roda210 = null;
    var $roda211 = null;
    var $roda212 = null;
    var $roda213 = null;
    var $roda214 = null;
    var $roda215 = null;


    /*
      FINAL CORPO
    */


    var $arquivo = null;


    var $nomearq = '/tmp/modelo.txt';

    function gera_cabecalho()
    {
        $this->arquivo = fopen($this->nomearq, "w");
        fputs($this->arquivo,
            $this->cabec101
            . $this->cabec102
            . $this->cabec103
            . $this->cabec104
            . $this->cabec105
            . $this->cabec106
            . $this->cabec107
            . $this->cabec108
            . $this->cabec109
            . $this->cabec110
            . $this->cabec111
            . $this->cabec112
            . $this->cabec113
            . $this->cabec114
            . $this->cabec115
            . $this->cabec116
            . $this->cabec117
            . $this->cabec118
            . $this->cabec119
            . $this->cabec120
            . $this->cabec121
            . $this->cabec122
            . $this->cabec123
            . $this->cabec124
            . $this->cabec125
            . $this->cabec126
            . $this->cabec127
            . "\r\n"
        //.chr(13).chr(10) 

        );
    }

    function gera_cabecalho02()
    {
        //segundo cabeçalho
        fputs($this->arquivo,
            $this->cabec201
            . $this->cabec202
            . $this->cabec203
            . $this->cabec204
            . $this->cabec205
            . $this->cabec206
            . $this->cabec207
            . $this->cabec208
            . $this->cabec209
            . $this->cabec210
            . $this->cabec211
            . $this->cabec212
            . $this->cabec213
            . $this->cabec214
            . $this->cabec215
            . $this->cabec216
            . $this->cabec217
            . $this->cabec218
            . $this->cabec219
            . $this->cabec220
            . $this->cabec221
            . $this->cabec222
            . $this->cabec223
            . $this->cabec224
            . $this->cabec225
            . $this->cabec226
            . $this->cabec227
            . "\r\n"
        // .chr(13).chr(10) 

        );

        //fclose($fd1);  
    }

    function gera_corpo()
    {
        fputs($this->arquivo,
            $this->detalhe01
            . $this->detalhe02
            . $this->detalhe03
            . $this->detalhe04
            . $this->detalhe05
            . $this->detalhe06
            . $this->detalhe07
            . $this->detalhe08
            . $this->detalhe09
            . $this->detalhe10
            . $this->detalhe11
            . $this->detalhe12
            . $this->detalhe13
            . $this->detalhe14
            . $this->detalhe15
            . $this->detalhe16
            . $this->detalhe17
            . $this->detalhe18
            . $this->detalhe19
            . $this->detalhe20
            . $this->detalhe21
            . $this->detalhe22
            . $this->detalhe23
            . $this->detalhe24
            . $this->detalhe25
            . $this->detalhe26
            . $this->detalhe27
            . $this->detalhe28
            . $this->detalhe29
            . $this->detalhe30
            . "\r\n"
        //.chr(13).chr(10) 
        );
    }

    function gera_trailer1()
    {
        fputs($this->arquivo,
            $this->roda101
            . $this->roda102
            . $this->roda103
            . $this->roda104
            . $this->roda105
            . $this->roda106
            . $this->roda107
            . $this->roda108
            . $this->roda109
            . $this->roda110
            . $this->roda111
            . $this->roda112
            . $this->roda113
            . $this->roda114
            . $this->roda115
            //.chr(13).chr(10)
            . "\r\n"
        );
    }

    function gera_trailer2()
    {
        fputs($this->arquivo,
            $this->roda201
            . $this->roda202
            . $this->roda203
            . $this->roda204
            . $this->roda205
            . $this->roda206
            . $this->roda207
            . $this->roda208
            . $this->roda209
            . $this->roda210
            . $this->roda211
            . $this->roda212
            . $this->roda213
            . $this->roda214
            . $this->roda215
            //.chr(13).chr(10)
            . "\r\n"
        );
    }

    function gera()
    {
        fclose($this->arquivo);
    }


}
