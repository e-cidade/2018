<?php

use Classes\PostgresMigration;

class M9691ProtocoloDocumento extends PostgresMigration
{
   
     //    ALTER TABLE protprocessodocumento 
     // ADD CONSTRAINT protprocessodocumento_usuario_fk 
     // FOREIGN KEY (p01_usuario) 
     // REFERENCES db_usuarios(id_usuario);


     // ALTER TABLE protprocessodocumento 
     // ADD CONSTRAINT protprocessodocumento_procandamint_fk
     // FOREIGN KEY (p01_procandamint) 
     // REFERENCES procandamint(p78_sequencial);
    
  

    public function up()
    {
       $sSql = " 

    ALTER TABLE  protprocessodocumento  ADD COLUMN p01_data date ;
    ALTER TABLE  protprocessodocumento  ADD COLUMN p01_procandamint int;
    ALTER TABLE  protprocessodocumento  ADD COLUMN p01_usuario int   ;
    


    insert into db_syscampo values(1009502,'p01_procandamint','int8','Código do andamento do documento','0', 'procandamint',10,'t','f','f',1,'text','');
    insert into db_syscampo values(1009503,'p01_data','date','Data de cadastro do documento','null', 'Data',10,'t','f','f',1,'text','');
    insert into db_syscampo values(1009504,'p01_usuario','int8','Usuário que adicionou o documento','0', 'Usuário',10,'t','f','f',1,'text','');
    update db_sysarquivo set nomearq = 'protprocessodocumento', descricao = 'ligação entre processos e seus arquivos', sigla = 'p01', dataincl = '2017-11-03', rotulo = 'protprocessodocumento', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 3649;
    delete from db_sysarqarq where codarq = 3649;
    insert into db_sysarqarq values(0,3649);
    delete from db_sysarqcamp where codarq = 3649;
    insert into db_sysarqcamp values(3649,20296,1,1000306);
    insert into db_sysarqcamp values(3649,20297,2,0);
    insert into db_sysarqcamp values(3649,20298,3,0);
    insert into db_sysarqcamp values(3649,20299,4,0);
    insert into db_sysarqcamp values(3649,20302,5,0);
    insert into db_sysarqcamp values(3649,1009504,6,0);
    insert into db_sysarqcamp values(3649,1009503,7,0);
    insert into db_sysarqcamp values(3649,1009502,8,0);
    delete from db_sysforkey where codarq = 3649 and referen = 0;
    insert into db_sysforkey values(3649,1009502,1,1059,0);
    delete from db_sysforkey where codarq = 3649 and referen = 0;
    insert into db_sysforkey values(3649,1009504,1,109,0);
    insert into db_sysindices values(1008230,'protprocessodocumento_procadamint_in',3649,'0');
    insert into db_syscadind values(1008230,1009502,1);
    insert into db_sysindices values(1008231,'protprocessodocumento_usuario_in',3649,'0');
    insert into db_syscadind values(1008231,1009504,1);



    ";    

       $this->execute($sSql); 
    }

    public function down()
    {
       $sSql = "
          
         ALTER TABLE protprocessodocumento 
         DROP CONSTRAINT IF EXISTS protprocessodocumento_usuario_fk;

         ALTER TABLE protprocessodocumento 
         DROP CONSTRAINT IF EXISTS protprocessodocumento_procandamint_fk    ;

         ALTER TABLE  protprocessodocumento  DROP COLUMN p01_data;
         ALTER TABLE  protprocessodocumento  DROP COLUMN p01_procandamint;
         ALTER TABLE  protprocessodocumento  DROP COLUMN p01_usuario;

 

         DELETE FROM db_sysindices where  codind = 1008231 AND nomeind = 'protprocessodocumento_usuario_in';
         DELETE FROM db_sysindices where  codind = 1008230 AND nomeind = 'protprocessodocumento_procadamint_in';

         DELETE FROM  db_syscadind WHERE codind =  1008231 AND  codcam  = 1009504;   
         DELETE FROM  db_syscadind WHERE codind =  1008230 AND  codcam   = 1009502; 

         DELETE FROM db_sysforkey where codarq = 3649 and codcam = 1009502;
         DELETE FROM db_sysforkey where codarq = 3649 and codcam = 1009504;

         DELETE  FROM  db_sysarqcamp WHERE codarq = 3649   AND  codcam =1009502;
         DELETE  FROM  db_sysarqcamp WHERE codarq = 3649   AND  codcam =1009504;
         DELETE  FROM  db_sysarqcamp WHERE codarq = 3649   AND  codcam =1009503;
         

         DELETE  FROM db_syscampo  WHERE   codcam = 1009502;
         DELETE  FROM db_syscampo  WHERE    codcam = 1009503;
         DELETE  FROM db_syscampo  WHERE    codcam = 1009504;
       "; 

       $this->execute($sSql); 

    }

}
