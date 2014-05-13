<?php
    function consultaCorrecaoConplano($tabelaOrigem){
        switch($tabelaOrigem){
            case "orcreceita":
                return " select distinct
                                  o70_anousu,
                                  o70_codfon
                             from orcreceita
                            where not exists ( select *
                                                 from conplano
                                               where c60_codcon = o70_codfon
                                                 and c60_anousu = o70_anousu ) ";
            case "orcdotacao":
                return " select distinct
                                  o58_codele,
                                  o58_anousu
                             from orcdotacao
                            where not exists ( select *
                                                 from conplano
                                               where c60_codcon = o58_codele
                                                 and c60_anousu = o58_anousu ) ";
        }
    }
    
    function consultaAuxiliarConplano($tabelaOrigem, $oConplano){
        switch($tabelaOrigem){
            case "orcreceita":
                return " select *
                                   from orcfontes
                                  where o57_codfon  = {$oConplano->o70_codfon}
                                    and o57_anousu >= {$oConplano->o70_anousu}
                               order by o57_anousu asc ";
            case "orcdotacao":
                return " select *
                               from orcelemento
                              where o56_codele  = {$oConplano->o58_codele}
                                and o56_anousu >= {$oConplano->o58_anousu}
                           order by o56_anousu asc ";
        }
    }
    
    function insereConplanoPorFontesOuElemento($tabelaOrigem, $sTabelaPlano, $oOrcFontesOuElemento, $oConplano){
        switch($tabelaOrigem){
            case "orcreceita": //a consulta usará orcfontes
                return "insert into {$sTabelaPlano} ( c60_codcon,
                                                     c60_anousu,
                                                     c60_estrut,
                                                     c60_descr,
                                                     c60_finali,
                                                     c60_codsis,
                                                     c60_codcla
                                                   ) values (
                                                     {$oOrcFontesOuElemento->o57_codfon},
                                                     {$oConplano->o70_anousu},
                                                     '{$oOrcFontesOuElemento->o57_fonte}',
                                                     '{$oOrcFontesOuElemento->o57_descr}',
                                                     '{$oOrcFontesOuElemento->o57_finali}',
                                                     1,
                                                     1
                                                   )";
            case "orcdotacao": //a consulta usará orcelemento
                return $sSqlInsereConplano = " insert into {$sTabelaPlano} ( c60_codcon,
                                                     c60_anousu,
                                                     c60_estrut,
                                                     c60_descr,
                                                     c60_finali,
                                                     c60_codsis,
                                                     c60_codcla
                                                   ) values (
                                                     {$oOrcFontesOuElemento->o56_codele},
                                                     {$oConplano->o58_anousu},
                                                     '{$oOrcFontesOuElemento->o56_elemento}',
                                                     '{$oOrcFontesOuElemento->o56_descr}',
                                                     '{$oOrcFontesOuElemento->o56_finali}',
                                                     1,
                                                     1
                                                   )";
        }
    }
    
    function consultaConplano($tabelaOrigem, $oConplano){
        switch ($tabelaOrigem){
            case "orcreceita":
                return "select *
                         from conplano
                        where c60_codcon = {$oConplano->o70_codfon}";
            case "orcdotacao":
                return "select *
                         from conplano
                        where c60_codcon = {$oConplano->o58_codele}";
        }
        
    }
    
    function insereConplanoPorChaveConplano($tabelaOrigem, $sTabelaPlano, $oConplano, $oConplanoOrigem){
        switch($tabelaOrigem){
            case "orcreceita":
                return " insert into {$sTabelaPlano} ( c60_codcon,
                                                       c60_anousu,
                                                       c60_estrut,
                                                       c60_descr,
                                                       c60_finali,
                                                       c60_codsis,
                                                       c60_codcla
                                                     ) values (
                                                       {$oConplanoOrigem->c60_codcon},
                                                       {$oConplano->o70_anousu},
                                                       '{$oConplanoOrigem->c60_estrut}',
                                                       '{$oConplanoOrigem->c60_descr}',
                                                       '{$oConplanoOrigem->c60_finali}',
                                                       {$oConplanoOrigem->c60_codsis},
                                                       {$oConplanoOrigem->c60_codcla}
                                                     )";
            case "orcdotacao":
                return " insert into {$sTabelaPlano} ( c60_codcon,
                                                       c60_anousu,
                                                       c60_estrut,
                                                       c60_descr,
                                                       c60_finali,
                                                       c60_codsis,
                                                       c60_codcla
                                                     ) values (
                                                       {$oConplanoOrigem->c60_codcon},
                                                       {$oConplano->o58_anousu},
                                                       '{$oConplanoOrigem->c60_estrut}',
                                                       '{$oConplanoOrigem->c60_descr}',
                                                       '{$oConplanoOrigem->c60_finali}',
                                                       {$oConplanoOrigem->c60_codsis},
                                                       {$oConplanoOrigem->c60_codcla}
                                                     )";
        }
    }

?>