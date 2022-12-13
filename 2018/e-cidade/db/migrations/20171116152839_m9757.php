<?php

use Classes\PostgresMigration;

class M9757 extends PostgresMigration
{
   public function down() 
   {
      $sql = "
            ALTER TABLE cadban ALTER COLUMN  k15_codbco type integer USING k15_codbco::integer; 
            ALTER TABLE cadban
            ADD CONSTRAINT cadban_codbco_fk
            FOREIGN KEY (k15_codbco)
            REFERENCES bancos(codbco);  
            
            ALTER TABLE cadban ALTER COLUMN  k15_codbco type integer;     
            
            UPDATE cadban  SET k15_codbco = (SELECT codbco FROM bancos WHERE codbco = k15_codbco); 
      ";  

      $this->execute($sql);
   }

   public function up() 
   {
      $sql = "
          ALTER TABLE cadban DROP CONSTRAINT cadban_codbco_fk;
            
          ALTER TABLE cadban ALTER COLUMN  k15_codbco type varchar(10);    

          UPDATE cadban  SET k15_codbco = (SELECT nomebco  FROM bancos WHERE codbco = k15_codbco::integer); 
      "; 
      
      $this->execute($sql);
   } 

}
