<?php

use Classes\PostgresMigration;

class M7791 extends PostgresMigration
{
	
	public function up()
	{
		
		$sSql = "

					alter table conplanocontabancaria disable trigger all;
					create table  w_conplanocontabancaria_2016 as select c56_sequencial    ,  
					                                                          c56_contabancaria ,
					                                                          c56_codcon        ,
					                                                          2017 as  c56_anousu
					                                                     from conplanocontabancaria 
					                                                    where c56_anousu = 2016;
				
					create table  w_conplanocontabancaria_2017 as select *
					                                                     from conplanocontabancaria 
					                                                    where c56_anousu = 2017;
				
					delete from   conplanocontabancaria  where c56_anousu = 2017;                                              
					insert into  conplanocontabancaria select  nextval('conplanocontabancaria_c56_sequencial_seq'),
					                                           c56_contabancaria,
					                                           c56_codcon,
					                                           c56_anousu
					                                     from  w_conplanocontabancaria_2016;
					alter table conplanocontabancaria enable trigger all;				
									
									";
		
		
		$this->execute($sSql);
	}
	
	public function down()
	{
		
		
		$sSql = "
					alter table conplanocontabancaria disable trigger all;
					delete from   conplanocontabancaria  where c56_anousu = 2017;
					insert into  conplanocontabancaria select  *
					alter table conplanocontabancaria enable trigger all;
    			";		
		
		$this->execute($sSql);
	}	
	
}
