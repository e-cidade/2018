<?php

use Classes\PostgresMigration;

class M8073 extends PostgresMigration
{
    
    public function up()
    {
        $diasVale = <<<EOT
            drop function "fc_dias_vale" (integer,integer,integer,integer);
            CREATE OR REPLACE FUNCTION "fc_dias_vale" (integer,integer,integer,integer)
            RETURNS integer AS '
            DECLARE
                REGISTRO          ALIAS FOR $1 ;
                ANO               ALIAS FOR $2 ;
                MES               ALIAS FOR $3 ;
                INSTIT            ALIAS FOR $4 ;

                PER2I_NO_MES      DATE;
                PER2F_NO_MES      DATE;
                PERI_NO_MES       DATE;
                PERF_NO_MES       DATE;
                DTAFAS	          DATE;
                DTRETO	          DATE;
                MESANT            INTEGER;
                ANOANT            INTEGER;
                DIAS	            INTEGER;
                TBPREV	          INTEGER;
                SITUAC	          INTEGER;
                VALE  	          VARCHAR(1);
                ANO_C             CHAR(4);
                PAGA_TERCO_FER    BOOLEAN;
                
                INICIO            DATE;
                ADMISS            DATE;
                FIM               DATE;
                QTDAFASTA         INTEGER := 0;
                QTDFERIAS         INTEGER := 0;  
                QTDADMISS         INTEGER := 0; 
                TOTAL             INTEGER := 0;

            BEGIN

            DIAS = 15;

            ANO_C = ANO;

            IF MES = 1 THEN
            MESANT = 12;
            ANOANT = ano - 1;
            ELSE
            MESANT = MES - 1;
            ANOANT = 0;
            END IF;

            SELECT R30_PER1I,
                R30_PER1F,
                R30_PAGA13
            FROM CADFERIA INTO PERI_NO_MES,PERF_NO_MES, PAGA_TERCO_FER
            WHERE R30_ANOUSU = ANO  
            AND R30_MESUSU = MES
            AND fc_anousu_mesusu(extract(year from R30_PER1F)::int, extract(MONTH from R30_PER1F)::int) >= fc_anousu_mesusu(ANO,MES)
            AND (    ( DATE_PART(''MONTH'',R30_PER1I) = MES    AND DATE_PART(''Y'',R30_PER1I) = ANO   )
                    OR ( MES = 1 AND ( DATE_PART(''MONTH'',R30_PER1I) = MESANT AND DATE_PART(''Y'',R30_PER1I) = ANOANT) )
                    OR ( DATE_PART(''MONTH'',R30_PER1I) = MESANT AND DATE_PART(''Y'',R30_PER1I) = ANO   )
                )
            AND R30_REGIST = REGISTRO order by r30_per1i desc limit 1;

            IF DATE_PART(''MONTH'',PERI_NO_MES) = 12 AND DATE_PART(''Y'',PERI_NO_MES) = ANOANT THEN

            IF DATE_PART(''MONTH'',PERI_NO_MES) = MES THEN
            DIAS = 0;
            ELSE IF (DATE_PART(''MONTH'',PERI_NO_MES) <> MES) 
                    AND ( DATE_PART(''MONTH'',PERF_NO_MES) = MES 
                    AND DATE_PART(''D'',PERF_NO_MES) > 14
                    ) THEN 
                    DIAS = 0;
                ELSE IF DATE_PART(''D'',PERF_NO_MES) < 15 THEN
                        DIAS = 15 - DATE_PART(''D'',PERF_NO_MES);
                END IF ;
                END IF;

            
            END IF;

            ELSE

            IF DATE_PART(''MONTH'',PERI_NO_MES) = MES AND DATE_PART(''Y'',PERI_NO_MES) = ANO AND PAGA_TERCO_FER = FALSE THEN
            DIAS = 0;
            ELSE IF (DATE_PART(''MONTH'',PERI_NO_MES) <> MES) 
                    AND ( DATE_PART(''MONTH'',PERF_NO_MES) = MES 
                    AND DATE_PART(''D'',PERF_NO_MES) > 14
                    ) THEN 
                    DIAS = 0;
                ELSE IF DATE_PART(''D'',PERF_NO_MES) < 15 AND DATE_PART(''Y'',PERI_NO_MES) = ANO  THEN
                        DIAS = 15 - DATE_PART(''D'',PERF_NO_MES);
                END IF ;
                END IF;

            
            END IF;


            END IF;


            SELECT R30_PER2I,
                R30_PER2F 
            FROM CADFERIA INTO PER2I_NO_MES,PER2F_NO_MES
            WHERE R30_ANOUSU = ANO  
            AND R30_MESUSU = MES
            AND fc_anousu_mesusu(extract(year from R30_PER2F)::int, extract(MONTH from R30_PER2F)::int) >= fc_anousu_mesusu(ANO,MES)
            AND (    ( DATE_PART(''MONTH'',R30_PER2I) = MES    AND DATE_PART(''Y'',R30_PER2I) = ANO   )
                    OR ( MES = 1 AND ( DATE_PART(''MONTH'',R30_PER2I) = MESANT AND DATE_PART(''Y'',R30_PER2I) = ANOANT) )
                    OR ( DATE_PART(''MONTH'',R30_PER2I) = MESANT AND DATE_PART(''Y'',R30_PER2I) = ANO   )
                )
            AND R30_REGIST = REGISTRO order by r30_per2i desc limit 1;

            IF DATE_PART(''MONTH'',PER2I_NO_MES) = 12 AND DATE_PART(''Y'',PER2I_NO_MES) = ANOANT THEN

            IF DATE_PART(''MONTH'',PER2I_NO_MES) = MES THEN
            DIAS = 0;
            ELSE IF (DATE_PART(''MONTH'',PER2I_NO_MES) <> MES) 
                    AND ( DATE_PART(''MONTH'',PER2F_NO_MES) = MES 
                    AND DATE_PART(''D'',PER2F_NO_MES) > 14
                    ) THEN 
                    DIAS = 0;
                ELSE IF DATE_PART(''D'',PER2F_NO_MES) < 15 THEN
                        DIAS = 15 - DATE_PART(''D'',PER2F_NO_MES);
                END IF ;
                END IF;

            
            END IF;

            ELSE

            IF DATE_PART(''MONTH'',PER2I_NO_MES) = MES AND DATE_PART(''Y'',PER2I_NO_MES) = ANO THEN
            DIAS = 0;
            ELSE IF (DATE_PART(''MONTH'',PER2I_NO_MES) <> MES) 
                    AND ( DATE_PART(''MONTH'',PER2F_NO_MES) = MES 
                    AND DATE_PART(''D'',PER2F_NO_MES) > 14
                    ) THEN 
                    DIAS = 0;
                ELSE IF DATE_PART(''D'',PER2F_NO_MES) < 15 AND DATE_PART(''Y'',PER2I_NO_MES) = ANO  THEN
                        DIAS = 15 - DATE_PART(''D'',PER2F_NO_MES);
                END IF ;
                END IF;

            
            END IF;


            END IF;


            SELECT RH01_ADMISS ,RH02_TBPREV,RH01_VALE
            FROM RHPESSOALMOV
            INNER JOIN RHPESSOAL ON RH01_REGIST = RH02_REGIST
            INTO ADMISS, TBPREV, VALE
            WHERE RH02_REGIST = REGISTRO
            AND RH02_ANOUSU = ANO
            AND RH02_MESUSU = MES
            AND RH02_INSTIT = INSTIT;

            IF DATE_PART(''MONTH'',ADMISS) = MES AND
            DATE_PART(''Y'',ADMISS)     = ANO THEN
            DIAS = 0;
            END IF;

            SELECT R45_DTAFAS,
                R45_DTRETO,
                R45_SITUAC 
            FROM AFASTA
            INTO DTAFAS,DTRETO,SITUAC
            WHERE R45_ANOUSU = ANO 
            AND R45_MESUSU = MES
            AND R45_REGIST = REGISTRO
            ORDER BY R45_DTAFAS DESC;

            IF TBPREV IN (2,4) THEN 
            IF SITUAC IN (2,7) 	AND 
                (DTRETO IS NULL 	OR 
                DTRETO > TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||LPAD(MES,2,0)||''15''),''YYYYMMDD'')) THEN
                DIAS = 0;
            ELSE IF SITUAC NOT IN (2,7) THEN
                    DIAS = DIAS;
                    END IF;
            END IF;
            END IF ;

            IF TBPREV = 1 AND DTAFAS IS NOT NULL THEN
            IF DTRETO IS NULL       OR 
                DTRETO > TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||LPAD(MES,2,0)||''14''),''YYYYMMDD'') THEN
                DIAS = 0;
            ELSE 
                IF DTRETO < TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||LPAD(MES,2,0)||''15''),''YYYYMMDD'') AND 
                    DTRETO >= TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||LPAD(MES,2,0)||''01''),''YYYYMMDD'') THEN
                    DIAS = 15 - DATE_PART(''D'',DTRETO);
                END IF;
            END IF;
            END IF ;

            IF SITUAC = 5 AND ( DTRETO IS NULL 
                            OR DTRETO >= TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||
                                                                LPAD(MES,2,0)||''01'')
                    ,''YYYYMMDD''))  THEN
            DIAS = 15;
            END IF;

            IF VALE = ''N'' THEN
            DIAS = 0;
            END IF;


            TOTAL := DIAS;

            RETURN TOTAL;

            END;

            ' LANGUAGE 'plpgsql';
EOT;

        $this->execute($diasVale);
    }

    public function down()
    {
        $diasVale = <<<EOT
        drop function "fc_dias_vale" (integer,integer,integer);
        drop function "fc_dias_vale" (integer,integer,integer,integer);
        CREATE OR REPLACE FUNCTION "fc_dias_vale" (integer,integer,integer,integer)
        RETURNS integer AS '
        DECLARE
            REGISTRO          ALIAS FOR $1 ;
            ANO               ALIAS FOR $2 ;
            MES               ALIAS FOR $3 ;
            INSTIT            ALIAS FOR $4 ;

            PER2I_NO_MES      DATE;
            PER2F_NO_MES      DATE;
            PERI_NO_MES       DATE;
            PERF_NO_MES       DATE;
            DTAFAS	      DATE;
            DTRETO	      DATE;
            MESANT            INTEGER;
            ANOANT            INTEGER;
            DIAS	      INTEGER;
            TBPREV	      INTEGER;
            SITUAC	      INTEGER;
            VALE  	      VARCHAR(1);
            ANO_C             CHAR(4);
            
            INICIO            DATE;
            ADMISS            DATE;
            FIM               DATE;
            QTDAFASTA         INTEGER := 0;
            QTDFERIAS         INTEGER := 0;  
            QTDADMISS         INTEGER := 0; 
            TOTAL             INTEGER := 0;

        BEGIN

        DIAS = 15;

        ANO_C = ANO;

        IF MES = 1 THEN
        MESANT = 12;
        ANOANT = ano - 1;
        ELSE
        MESANT = MES - 1;
        ANOANT = 0;
        END IF;

        SELECT R30_PER1I,
            R30_PER1F 
        FROM CADFERIA INTO PERI_NO_MES,PERF_NO_MES
        WHERE R30_ANOUSU = ANO  
        AND R30_MESUSU = MES
        AND fc_anousu_mesusu(extract(year from R30_PER1F)::int, extract(MONTH from R30_PER1F)::int) >= fc_anousu_mesusu(ANO,MES)
        AND (    ( DATE_PART(''MONTH'',R30_PER1I) = MES    AND DATE_PART(''Y'',R30_PER1I) = ANO   )
                OR ( MES = 1 AND ( DATE_PART(''MONTH'',R30_PER1I) = MESANT AND DATE_PART(''Y'',R30_PER1I) = ANOANT) )
                OR ( DATE_PART(''MONTH'',R30_PER1I) = MESANT AND DATE_PART(''Y'',R30_PER1I) = ANO   )
            )
        AND R30_REGIST = REGISTRO order by r30_per1i desc limit 1;

        IF DATE_PART(''MONTH'',PERI_NO_MES) = 12 AND DATE_PART(''Y'',PERI_NO_MES) = ANOANT THEN
        -- RAISE NOTICE ''1 %'',DIAS;

        IF DATE_PART(''MONTH'',PERI_NO_MES) = MES THEN
        DIAS = 0;
        -- RAISE NOTICE ''2 %'',DIAS;
        ELSE IF (DATE_PART(''MONTH'',PERI_NO_MES) <> MES) 
                AND ( DATE_PART(''MONTH'',PERF_NO_MES) = MES 
                AND DATE_PART(''D'',PERF_NO_MES) > 14
                ) THEN 
        -- RAISE NOTICE ''3 %'',DIAS;
                DIAS = 0;
            ELSE IF DATE_PART(''D'',PERF_NO_MES) < 15 THEN
        -- RAISE NOTICE ''4 %'',DIAS;
                    DIAS = 15 - DATE_PART(''D'',PERF_NO_MES);
            END IF ;
            END IF;

        
        END IF;

        ELSE

        -- RAISE NOTICE ''5 %'',DIAS;
        -- RAISE NOTICE ''MES INTICIAL %'',DATE_PART(''MONTH'',PERI_NO_MES);
        -- RAISE NOTICE ''MES FINAL %'',DATE_PART(''MONTH'',PERF_NO_MES);
        -- RAISE NOTICE ''DIA FINAL %'',DATE_PART(''D'',PERF_NO_MES);
        IF DATE_PART(''MONTH'',PERI_NO_MES) = MES AND DATE_PART(''Y'',PERI_NO_MES) = ANO THEN
        DIAS = 0;
        ELSE IF (DATE_PART(''MONTH'',PERI_NO_MES) <> MES) 
                AND ( DATE_PART(''MONTH'',PERF_NO_MES) = MES 
                AND DATE_PART(''D'',PERF_NO_MES) > 14
                ) THEN 
        -- RAISE NOTICE ''6 %'',DIAS;
                DIAS = 0;
            ELSE IF DATE_PART(''D'',PERF_NO_MES) < 15 AND DATE_PART(''Y'',PERI_NO_MES) = ANO  THEN
                    DIAS = 15 - DATE_PART(''D'',PERF_NO_MES);
        -- RAISE NOTICE ''7 %'',DIAS;
            END IF ;
            END IF;

        
        END IF;


        END IF;


        SELECT R30_PER2I,
            R30_PER2F 
        FROM CADFERIA INTO PER2I_NO_MES,PER2F_NO_MES
        WHERE R30_ANOUSU = ANO  
        AND R30_MESUSU = MES
        AND fc_anousu_mesusu(extract(year from R30_PER2F)::int, extract(MONTH from R30_PER2F)::int) >= fc_anousu_mesusu(ANO,MES)
        AND (    ( DATE_PART(''MONTH'',R30_PER2I) = MES    AND DATE_PART(''Y'',R30_PER2I) = ANO   )
                OR ( MES = 1 AND ( DATE_PART(''MONTH'',R30_PER2I) = MESANT AND DATE_PART(''Y'',R30_PER2I) = ANOANT) )
                OR ( DATE_PART(''MONTH'',R30_PER2I) = MESANT AND DATE_PART(''Y'',R30_PER2I) = ANO   )
            )
        AND R30_REGIST = REGISTRO order by r30_per2i desc limit 1;

        IF DATE_PART(''MONTH'',PER2I_NO_MES) = 12 AND DATE_PART(''Y'',PER2I_NO_MES) = ANOANT THEN
        -- RAISE NOTICE ''8 %'',DIAS;

        IF DATE_PART(''MONTH'',PER2I_NO_MES) = MES THEN
        DIAS = 0;
        -- RAISE NOTICE ''9 %'',DIAS;
        ELSE IF (DATE_PART(''MONTH'',PER2I_NO_MES) <> MES) 
                AND ( DATE_PART(''MONTH'',PER2F_NO_MES) = MES 
                AND DATE_PART(''D'',PER2F_NO_MES) > 14
                ) THEN 
                DIAS = 0;
        -- RAISE NOTICE ''10 %'',DIAS;
            ELSE IF DATE_PART(''D'',PER2F_NO_MES) < 15 THEN
                    DIAS = 15 - DATE_PART(''D'',PER2F_NO_MES);
            END IF ;
            END IF;

        
        END IF;

        ELSE

        IF DATE_PART(''MONTH'',PER2I_NO_MES) = MES AND DATE_PART(''Y'',PER2I_NO_MES) = ANO THEN
        DIAS = 0;
        ELSE IF (DATE_PART(''MONTH'',PER2I_NO_MES) <> MES) 
                AND ( DATE_PART(''MONTH'',PER2F_NO_MES) = MES 
                AND DATE_PART(''D'',PER2F_NO_MES) > 14
                ) THEN 
                DIAS = 0;
            ELSE IF DATE_PART(''D'',PER2F_NO_MES) < 15 AND DATE_PART(''Y'',PER2I_NO_MES) = ANO  THEN
                    DIAS = 15 - DATE_PART(''D'',PER2F_NO_MES);
            END IF ;
            END IF;

        
        END IF;


        END IF;

        -- RAISE NOTICE ''12 %'',DIAS;

        SELECT RH01_ADMISS ,RH02_TBPREV,RH01_VALE
        FROM RHPESSOALMOV
        INNER JOIN RHPESSOAL ON RH01_REGIST = RH02_REGIST
        INTO ADMISS, TBPREV, VALE
        WHERE RH02_REGIST = REGISTRO
        AND RH02_ANOUSU = ANO
        AND RH02_MESUSU = MES
        AND RH02_INSTIT = INSTIT;

        IF DATE_PART(''MONTH'',ADMISS) = MES AND
        DATE_PART(''Y'',ADMISS)     = ANO THEN
        DIAS = 0;
        END IF;

        SELECT R45_DTAFAS,
            R45_DTRETO,
            R45_SITUAC 
        FROM AFASTA
        INTO DTAFAS,DTRETO,SITUAC
        WHERE R45_ANOUSU = ANO 
        AND R45_MESUSU = MES
        AND R45_REGIST = REGISTRO
        ORDER BY R45_DTAFAS DESC;

        IF TBPREV = 2 THEN 
        IF SITUAC IN (2,7) 	AND 
            (DTRETO IS NULL 	OR 
            DTRETO > TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||LPAD(MES,2,0)||''15''),''YYYYMMDD'')) THEN
            DIAS = 0;
        ELSE IF SITUAC NOT IN (2,7) THEN
                DIAS = DIAS;
                END IF;
        END IF;
        END IF ;

        IF TBPREV = 1 AND DTAFAS IS NOT NULL THEN
        IF DTRETO IS NULL       OR 
            DTRETO > TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||LPAD(MES,2,0)||''14''),''YYYYMMDD'') THEN
            DIAS = 0;
        ELSE 
            IF DTRETO < TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||LPAD(MES,2,0)||''15''),''YYYYMMDD'') AND 
                DTRETO >= TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||LPAD(MES,2,0)||''01''),''YYYYMMDD'') THEN
        --RAISE NOTICE ''%'',DIAS;
                DIAS = 15 - DATE_PART(''D'',DTRETO);
            END IF;
        END IF;
        END IF ;

        IF SITUAC = 5 AND ( DTRETO IS NULL 
                        OR DTRETO >= TO_DATE(TRIM(TO_CHAR(ANO,''9999'')||
                                                            LPAD(MES,2,0)||''01'')
                ,''YYYYMMDD''))  THEN
        DIAS = 15;
        END IF;

        IF VALE = ''N'' THEN
        DIAS = 0;
        END IF;

        -- RAISE NOTICE ''%'',QTDAFASTA;
        -- RAISE NOTICE ''%'',QTDADMISS;

        -- RAISE NOTICE ''1 %'',DIAS;

        TOTAL := DIAS;

        RETURN TOTAL;

        END;

        ' LANGUAGE 'plpgsql';
EOT;

       $this->execute($diasVale);
    }
}
