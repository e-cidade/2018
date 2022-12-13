<?php

use Classes\PostgresMigration;

class M5778 extends PostgresMigration
{

public function up(){
  $sql = <<<'SQL'

drop function if exists conta_dias_afasta (integer,integer,integer,integer);
drop function if exists conta_dias_afasta (integer,integer,integer,integer,integer);

CREATE OR REPLACE FUNCTION conta_dias_afasta (integer,integer,integer,integer,integer)
  RETURNS integer AS $$
DECLARE
  REGISTRO          ALIAS FOR $1 ;
  ANO               ALIAS FOR $2 ;
  MES               ALIAS FOR $3 ;
  NDIAS             ALIAS FOR $4 ;
  INSTIT            ALIAS FOR $5 ;

  RUBMAT            VARCHAR(4);
  RUBSAU            VARCHAR(4);
  RUBACI            VARCHAR(4);

  INICIO            DATE;
  FIM               DATE;
  QTDAFASTADOS      INTEGER := 0;
  QAFASTADOSNOVO    INTEGER := 0;
  TOTAL             INTEGER := 0;

BEGIN


  INICIO := TO_DATE(trim(TO_CHAR(ANO,'9999')
                         ||lpad(MES,2,0)
                         ||'01')
  ,'YYYYMMDD')
  ;
  FIM := TO_DATE(trim(TO_CHAR(ANO,'9999')
                      ||lpad(MES,2,0)
                      || TO_CHAR( NDIAS,'99' ))
  ,'YYYYMMDD' ) ;

  TOTAL := 0;

  SELECT r33_rubmat,
    r33_rubsau,
    r33_rubaci
  FROM rhpessoalmov
    inner join inssirf on r33_anousu = rh02_anousu
                          and r33_mesusu = rh02_mesusu
                          and r33_codtab = rh02_tbprev+2
                          and r33_instit = rh02_instit
  INTO RUBMAT, RUBSAU, RUBACI
  WHERE rh02_regist = registro
  and rh02_anousu = ano
  and rh02_mesusu = mes
  and rh02_instit = instit;

  SELECT
    coalesce(sum( ( CASE WHEN (CASE WHEN R45_SITUAC = 8
      THEN (R45_DTAFAS + 30)
                               ELSE R45_DTAFAS
                               END) < INICIO
      THEN ( ( CASE WHEN R45_DTRETO IS NULL
        THEN FIM
               ELSE R45_DTRETO
               END ) - INICIO + 1 )
                    else ( ( CASE WHEN (R45_SITUAC = 8 AND (R45_DTAFAS + 30) > FIM)
                      THEN (CASE WHEN NDIAS = 31
                        THEN 1
                            ELSE 0
                            END
                      )
                             WHEN (R45_SITUAC = 8 AND (R45_DTAFAS + 30) <= FIM AND (R45_DTAFAS + 30) >= INICIO)
                               THEN (CASE WHEN R45_DTRETO IS NULL or R45_DTRETO > FIM
                                 THEN FIM - (R45_DTAFAS + 30) + 1
                                     ELSE R45_DTRETO - (R45_DTAFAS + 30) + 1
                                     END
                               )
                             WHEN (R45_DTRETO IS NULL or R45_DTRETO > FIM )
                               THEN FIM - R45_DTAFAS + 1
                             ELSE r45_dtreto - R45_DTAFAS+1
                             END ) )
                    END ) ),0) INTO QTDAFASTADOS
  FROM AFASTA
  WHERE R45_anousu = ANO
        AND (
          ( R45_situac  = 5 and (rubmat is null or trim(rubmat) = '' ))
          or ( r45_situac  = 3 and (rubaci is null or trim(rubaci) = ''))
          or ( r45_situac  = 6 and (rubsau is null or trim(rubsau) = '' ))
          or ( r45_situac  = 8 and (rubsau is null or trim(rubsau) = '' ))
          or ( r45_situac  = 2 )
          or ( r45_situac  = 7 )
        )
        AND R45_mesusu = MES
        AND R45_REGIST = REGISTRO
        AND (   ( R45_DTAFAS <=  FIM
                  AND R45_DTRETO IS NULL )
                OR ( R45_DTAFAS >= INICIO
                     AND R45_DTRETO <= FIM )
                OR ( R45_DTRETO > FIM
                     AND R45_DTAFAS >= INICIO
                     AND R45_DTAFAS <= FIM  )
                OR ( R45_DTAFAS < INICIO
                     AND R45_DTRETO >= INICIO )
                OR ( R45_DTRETO >  FIM
                     AND R45_DTAFAS < INICIO ));

  TOTAL := TOTAL + QTDAFASTADOS;
  SELECT
    coalesce(sum( ( CASE WHEN R69_DTAFAST < INICIO
      THEN ( ( CASE WHEN R69_DTRETORNO IS NULL
        THEN FIM
               ELSE R69_DTRETORNO
               END ) - INICIO + 1)
                    else ( ( CASE WHEN ( R69_DTRETORNO IS NULL or R69_DTRETORNO > FIM )
                      THEN FIM - R69_DTAFAST + 1
                             ELSE r69_DTRETORNO - R69_DTAFAST+1
                             END ) )
                    END ) ),0) INTO QAFASTADOSNOVO
  FROM AFASTAMENTO, CAD_AFAST
  WHERE R69_anousu = ANO
        AND R69_mesusu = MES
        AND R69_REGIST = REGISTRO
        AND (   ( R69_DTAFAST <=  FIM
                  AND R69_DTRETORNO IS NULL )
                OR ( R69_DTAFAST >= INICIO
                     AND R69_DTRETORNO <= FIM )
                OR ( R69_DTRETORNO > FIM
                     AND R69_DTAFAST >= INICIO
                     AND R69_DTAFAST <= FIM  )
                OR ( R69_DTAFAST < INICIO
                     AND R69_DTRETORNO >= INICIO )
                OR ( R69_DTRETORNO >  FIM
                     AND R69_DTAFAST < INICIO ))
        AND R68_anousu = ANO
        AND R68_mesusu = MES
        AND R68_CODIGO = R69_CODIGO
        AND R68_VT = 'f';


  TOTAL := TOTAL +QAFASTADOSNOVO;

  RETURN TOTAL;

END;

$$ LANGUAGE 'plpgsql';
    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
