<?php

use Classes\PostgresMigration;

class M9685RhFerias extends PostgresMigration
{
    public function up()
    {

        $sql = " 
            insert into db_syscampo values(1009478,'rh109_perdeudireitoferias','bool','Flag para verificar se o servidor perdeu o direito a férias','false', 'Perdeu Direito a Férias',1,'f','f','f',5,'text','Perdeu Direito a Férias');

            delete from db_sysarqcamp where codarq = 3373;

            insert into db_sysarqcamp values(3373,18957,1,2267);
            insert into db_sysarqcamp values(3373,18958,2,0);
            insert into db_sysarqcamp values(3373,18959,3,0);
            insert into db_sysarqcamp values(3373,18960,4,0);
            insert into db_sysarqcamp values(3373,18961,5,0);
            insert into db_sysarqcamp values(3373,18966,6,0);
            insert into db_sysarqcamp values(3373,20166,7,0);
            insert into db_sysarqcamp values(3373,1009478,8,0);

            ALTER TABLE  rhferias  ADD COLUMN rh109_perdeudireitoferias boolean ;
            ALTER TABLE  rhferias  ALTER COLUMN rh109_perdeudireitoferias SET DEFAULT false ;
            UPDATE rhferias SET rh109_perdeudireitoferias = 'f'; 

        "; 

        $this->execute($sql); 
    }

    public function down()
    {
        $sql= "DELETE  FROM db_sysarqcamp WHERE  codarq =  3373 AND  codcam = 1009478;
               DELETE  FROM db_syscampo   WHERE   codcam =  1009478;
               ALTER TABLE  rhferias  DROP COLUMN rh109_perdeudireitoferias;
        "; 

        $this->execute($sql);  

    }

}
