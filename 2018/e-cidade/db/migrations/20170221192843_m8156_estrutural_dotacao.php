<?php

use Classes\PostgresMigration;

class M8156EstruturalDotacao extends PostgresMigration
{

  public function up() {

    $this->execute(
<<<STRING
create or replace function fc_estruturaldotacao(integer,integer)
returns varchar
as '
DECLARE

  ANOUSU ALIAS FOR $1;
  CODDOT ALIAS FOR $2;
  ESTRUTURAL VARCHAR(200);

BEGIN

  SELECT LPAD(O58_ORGAO,2,0)||''.''||
         LPAD(O58_UNIDADE,2,0)||''.''||
         LPAD(O58_FUNCAO,2,0)||''.''||
         LPAD(O58_SUBFUNCAO,3,0)||''.''||
         LPAD(O58_PROGRAMA,4,0)||''.''||
	 LPAD(O58_PROJATIV,4,0)||''.''||
         LPAD(O56_ELEMENTO,13,0)||''.''||
         LPAD(O58_CODIGO,4,0)
  INTO ESTRUTURAL
  FROM ORCDOTACAO D
       INNER JOIN ORCELEMENTO O ON O.O56_CODELE = D.O58_CODELE
                               AND O.O56_ANOUSU = D.O58_ANOUSU
   WHERE D.O58_ANOUSU = ANOUSU AND D.O58_CODDOT = CODDOT;

  RETURN ESTRUTURAL;

END;
' language 'plpgsql';
STRING
    );
  }


  public function down() {

    $this->execute(
<<<STRING
      create or replace function fc_estruturaldotacao(integer,integer)
      returns varchar
      as '
      DECLARE
      
        ANOUSU ALIAS FOR $1;
        CODDOT ALIAS FOR $2;
        ESTRUTURAL VARCHAR(200);
      
      BEGIN
      
        SELECT LPAD(O58_ORGAO,2,0)||''.''||
               LPAD(O58_UNIDADE,2,0)||''.''||
               LPAD(O58_FUNCAO,2,0)||''.''||
               LPAD(O58_SUBFUNCAO,3,0)||''.''||
               LPAD(O58_PROGRAMA,4,0)||''.''||
         LPAD(O58_PROJATIV,4,0)||''.''||
               LPAD(O56_ELEMENTO,13,0)||''.''||
               LPAD(O58_CODIGO,4,0)
        INTO ESTRUTURAL
        FROM ORCDOTACAO D
             INNER JOIN ORCELEMENTO O ON O.O56_CODELE = D.O58_CODELE
         WHERE D.O58_ANOUSU = ANOUSU AND D.O58_CODDOT = CODDOT;
        RETURN ESTRUTURAL;
      END;
      ' language 'plpgsql';
STRING
    );
  }

}
