<?php

use Classes\PostgresMigration;

class M7896 extends PostgresMigration
{

	public function up()
	{
	
		$sSql = "
                   begin;
                   create table w_anoscorrigir as select distinct c61_anousu 
				                                    from conplanoreduz 
				                                   where c61_anousu > 2017 
				                                order by 1;
                   create table w_contascertas as select * from conplanoreduz where c61_anousu = 2017;
                   create table w_contaserradas as select * from conplanoreduz where c61_anousu > 2017;
                   select min(c61_anousu) as minimo, max(c61_anousu) as maximo from  w_anoscorrigir;
                   delete from conplanoreduz where c61_anousu > 2017;  
                   create table w_contascorrigidas as 
                   select c61_codcon,        
                          generate_series( (select min(c61_anousu) from  w_anoscorrigir ) ,  
				                           ( select max(c61_anousu) from  w_anoscorrigir) ) as c61_anousu,        
                          c61_reduz,         
                          c61_instit,        
                          c61_codigo,        
                          c61_contrapartida
                     from w_contascertas
                     order by c61_anousu;
                   insert into  conplanoreduz select * from  w_contascorrigidas;				
				";
		$this->execute($sSql);
	}
	
	public function down()
	{
	
		$sSql = "
					delete from conplanoreduz where c61_anousu > 2017;
					insert into conplanoreduz select * from w_contaserradas;				
				";
		$this->execute($sSql);
	}
	
}
