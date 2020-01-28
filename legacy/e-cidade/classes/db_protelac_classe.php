<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE protelac
class cl_protelac { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $h19_codigo = 0; 
   var $h19_assent = 0; 
   var $h19_tipo = null; 
   var $h19_dia01 = 0; 
   var $h19_dia02 = 0; 
   var $h19_dia03 = 0; 
   var $h19_dia04 = 0; 
   var $h19_dia05 = 0; 
   var $h19_dia06 = 0; 
   var $h19_dia07 = 0; 
   var $h19_dia08 = 0; 
   var $h19_dia09 = 0; 
   var $h19_dia10 = 0; 
   var $h19_op01 = null; 
   var $h19_op02 = null; 
   var $h19_op03 = null; 
   var $h19_op04 = null; 
   var $h19_op05 = null; 
   var $h19_op06 = null; 
   var $h19_op07 = null; 
   var $h19_op08 = null; 
   var $h19_op09 = null; 
   var $h19_op10 = null; 
   var $h19_per01 = 0; 
   var $h19_per02 = 0; 
   var $h19_per03 = 0; 
   var $h19_per04 = 0; 
   var $h19_per05 = 0; 
   var $h19_per06 = 0; 
   var $h19_per07 = 0; 
   var $h19_per08 = 0; 
   var $h19_per09 = 0; 
   var $h19_per10 = 0; 
   var $h19_tpcalc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h19_codigo = int4 = Código 
                 h19_assent = int4 = Assentamento 
                 h19_tipo = varchar(1) = Tipo 
                 h19_dia01 = int4 = Dias 
                 h19_dia02 = int4 = Dias 
                 h19_dia03 = int4 = Dias 
                 h19_dia04 = int4 = Dias 
                 h19_dia05 = int4 = Dias 
                 h19_dia06 = int4 = Dias 
                 h19_dia07 = int4 = Dias 
                 h19_dia08 = int4 = Dias 
                 h19_dia09 = int4 = Dias 
                 h19_dia10 = int4 = Dias 
                 h19_op01 = varchar(1) = Operador 1 
                 h19_op02 = varchar(1) = Operador 2 
                 h19_op03 = varchar(1) = Operador 3 
                 h19_op04 = varchar(1) = Operador 4 
                 h19_op05 = varchar(1) = Operador 5 
                 h19_op06 = varchar(1) = Operador 6 
                 h19_op07 = varchar(1) = Operador 7 
                 h19_op08 = varchar(1) = Operador 8 
                 h19_op09 = varchar(1) = Operador 9 
                 h19_op10 = varchar(1) = Operador 10 
                 h19_per01 = int4 = Percentual 1 
                 h19_per02 = int4 = Percentual 2 
                 h19_per03 = int4 = Percentual 3 
                 h19_per04 = int4 = Percentual 4 
                 h19_per05 = int4 = Percentual 5 
                 h19_per06 = int4 = Percentual 6 
                 h19_per07 = int4 = Percentual 7 
                 h19_per08 = int4 = Percentual 8 
                 h19_per09 = int4 = Percentual 9 
                 h19_per10 = int4 = Percentual 10 
                 h19_tpcalc = varchar(1) = Tipo de cálculo 
                 ";
   //funcao construtor da classe 
   function cl_protelac() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("protelac"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->h19_codigo = ($this->h19_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_codigo"]:$this->h19_codigo);
       $this->h19_assent = ($this->h19_assent == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_assent"]:$this->h19_assent);
       $this->h19_tipo = ($this->h19_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_tipo"]:$this->h19_tipo);
       $this->h19_dia01 = ($this->h19_dia01 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia01"]:$this->h19_dia01);
       $this->h19_dia02 = ($this->h19_dia02 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia02"]:$this->h19_dia02);
       $this->h19_dia03 = ($this->h19_dia03 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia03"]:$this->h19_dia03);
       $this->h19_dia04 = ($this->h19_dia04 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia04"]:$this->h19_dia04);
       $this->h19_dia05 = ($this->h19_dia05 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia05"]:$this->h19_dia05);
       $this->h19_dia06 = ($this->h19_dia06 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia06"]:$this->h19_dia06);
       $this->h19_dia07 = ($this->h19_dia07 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia07"]:$this->h19_dia07);
       $this->h19_dia08 = ($this->h19_dia08 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia08"]:$this->h19_dia08);
       $this->h19_dia09 = ($this->h19_dia09 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia09"]:$this->h19_dia09);
       $this->h19_dia10 = ($this->h19_dia10 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_dia10"]:$this->h19_dia10);
       $this->h19_op01 = ($this->h19_op01 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op01"]:$this->h19_op01);
       $this->h19_op02 = ($this->h19_op02 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op02"]:$this->h19_op02);
       $this->h19_op03 = ($this->h19_op03 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op03"]:$this->h19_op03);
       $this->h19_op04 = ($this->h19_op04 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op04"]:$this->h19_op04);
       $this->h19_op05 = ($this->h19_op05 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op05"]:$this->h19_op05);
       $this->h19_op06 = ($this->h19_op06 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op06"]:$this->h19_op06);
       $this->h19_op07 = ($this->h19_op07 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op07"]:$this->h19_op07);
       $this->h19_op08 = ($this->h19_op08 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op08"]:$this->h19_op08);
       $this->h19_op09 = ($this->h19_op09 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op09"]:$this->h19_op09);
       $this->h19_op10 = ($this->h19_op10 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_op10"]:$this->h19_op10);
       $this->h19_per01 = ($this->h19_per01 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per01"]:$this->h19_per01);
       $this->h19_per02 = ($this->h19_per02 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per02"]:$this->h19_per02);
       $this->h19_per03 = ($this->h19_per03 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per03"]:$this->h19_per03);
       $this->h19_per04 = ($this->h19_per04 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per04"]:$this->h19_per04);
       $this->h19_per05 = ($this->h19_per05 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per05"]:$this->h19_per05);
       $this->h19_per06 = ($this->h19_per06 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per06"]:$this->h19_per06);
       $this->h19_per07 = ($this->h19_per07 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per07"]:$this->h19_per07);
       $this->h19_per08 = ($this->h19_per08 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per08"]:$this->h19_per08);
       $this->h19_per09 = ($this->h19_per09 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per09"]:$this->h19_per09);
       $this->h19_per10 = ($this->h19_per10 == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_per10"]:$this->h19_per10);
       $this->h19_tpcalc = ($this->h19_tpcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_tpcalc"]:$this->h19_tpcalc);
     }else{
       $this->h19_codigo = ($this->h19_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h19_codigo"]:$this->h19_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($h19_codigo){ 
      $this->atualizacampos();
     if($this->h19_assent == null ){ 
       $this->erro_sql = " Campo Assentamento nao Informado.";
       $this->erro_campo = "h19_assent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h19_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "h19_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h19_dia01 == null ){ 
       $this->erro_sql = " Campo Dias nao Informado.";
       $this->erro_campo = "h19_dia01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h19_dia02 == null ){ 
       $this->h19_dia02 = "0";
     }
     if($this->h19_dia03 == null ){ 
       $this->h19_dia03 = "0";
     }
     if($this->h19_dia04 == null ){ 
       $this->h19_dia04 = "0";
     }
     if($this->h19_dia05 == null ){ 
       $this->h19_dia05 = "0";
     }
     if($this->h19_dia06 == null ){ 
       $this->h19_dia06 = "0";
     }
     if($this->h19_dia07 == null ){ 
       $this->h19_dia07 = "0";
     }
     if($this->h19_dia08 == null ){ 
       $this->h19_dia08 = "0";
     }
     if($this->h19_dia09 == null ){ 
       $this->h19_dia09 = "0";
     }
     if($this->h19_dia10 == null ){ 
       $this->h19_dia10 = "0";
     }
     if($this->h19_op01 == null ){ 
       $this->erro_sql = " Campo Operador 1 nao Informado.";
       $this->erro_campo = "h19_op01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h19_per01 == null ){ 
       $this->erro_sql = " Campo Percentual 1 nao Informado.";
       $this->erro_campo = "h19_per01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h19_per02 == null ){ 
       $this->h19_per02 = "0";
     }
     if($this->h19_per03 == null ){ 
       $this->h19_per03 = "0";
     }
     if($this->h19_per04 == null ){ 
       $this->h19_per04 = "0";
     }
     if($this->h19_per05 == null ){ 
       $this->h19_per05 = "0";
     }
     if($this->h19_per06 == null ){ 
       $this->h19_per06 = "0";
     }
     if($this->h19_per07 == null ){ 
       $this->h19_per07 = "0";
     }
     if($this->h19_per08 == null ){ 
       $this->h19_per08 = "0";
     }
     if($this->h19_per09 == null ){ 
       $this->h19_per09 = "0";
     }
     if($this->h19_per10 == null ){ 
       $this->h19_per10 = "0";
     }
     if($h19_codigo == "" || $h19_codigo == null ){
       $result = db_query("select nextval('protelac_h19_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: protelac_h19_codigo_seq do campo: h19_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h19_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from protelac_h19_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $h19_codigo)){
         $this->erro_sql = " Campo h19_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h19_codigo = $h19_codigo; 
       }
     }
     if(($this->h19_codigo == null) || ($this->h19_codigo == "") ){ 
       $this->erro_sql = " Campo h19_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into protelac(
                                       h19_codigo 
                                      ,h19_assent 
                                      ,h19_tipo 
                                      ,h19_dia01 
                                      ,h19_dia02 
                                      ,h19_dia03 
                                      ,h19_dia04 
                                      ,h19_dia05 
                                      ,h19_dia06 
                                      ,h19_dia07 
                                      ,h19_dia08 
                                      ,h19_dia09 
                                      ,h19_dia10 
                                      ,h19_op01 
                                      ,h19_op02 
                                      ,h19_op03 
                                      ,h19_op04 
                                      ,h19_op05 
                                      ,h19_op06 
                                      ,h19_op07 
                                      ,h19_op08 
                                      ,h19_op09 
                                      ,h19_op10 
                                      ,h19_per01 
                                      ,h19_per02 
                                      ,h19_per03 
                                      ,h19_per04 
                                      ,h19_per05 
                                      ,h19_per06 
                                      ,h19_per07 
                                      ,h19_per08 
                                      ,h19_per09 
                                      ,h19_per10 
                                      ,h19_tpcalc 
                       )
                values (
                                $this->h19_codigo 
                               ,$this->h19_assent 
                               ,'$this->h19_tipo' 
                               ,$this->h19_dia01 
                               ,$this->h19_dia02 
                               ,$this->h19_dia03 
                               ,$this->h19_dia04 
                               ,$this->h19_dia05 
                               ,$this->h19_dia06 
                               ,$this->h19_dia07 
                               ,$this->h19_dia08 
                               ,$this->h19_dia09 
                               ,$this->h19_dia10 
                               ,'$this->h19_op01' 
                               ,'$this->h19_op02' 
                               ,'$this->h19_op03' 
                               ,'$this->h19_op04' 
                               ,'$this->h19_op05' 
                               ,'$this->h19_op06' 
                               ,'$this->h19_op07' 
                               ,'$this->h19_op08' 
                               ,'$this->h19_op09' 
                               ,'$this->h19_op10' 
                               ,$this->h19_per01 
                               ,$this->h19_per02 
                               ,$this->h19_per03 
                               ,$this->h19_per04 
                               ,$this->h19_per05 
                               ,$this->h19_per06 
                               ,$this->h19_per07 
                               ,$this->h19_per08 
                               ,$this->h19_per09 
                               ,$this->h19_per10 
                               ,'$this->h19_tpcalc' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de protelacoes                            ($this->h19_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de protelacoes                            já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de protelacoes                            ($this->h19_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h19_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h19_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9541,'$this->h19_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,584,9541,'','".AddSlashes(pg_result($resaco,0,'h19_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4360,'','".AddSlashes(pg_result($resaco,0,'h19_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4361,'','".AddSlashes(pg_result($resaco,0,'h19_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4362,'','".AddSlashes(pg_result($resaco,0,'h19_dia01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4363,'','".AddSlashes(pg_result($resaco,0,'h19_dia02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4364,'','".AddSlashes(pg_result($resaco,0,'h19_dia03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4365,'','".AddSlashes(pg_result($resaco,0,'h19_dia04'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4366,'','".AddSlashes(pg_result($resaco,0,'h19_dia05'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4367,'','".AddSlashes(pg_result($resaco,0,'h19_dia06'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4368,'','".AddSlashes(pg_result($resaco,0,'h19_dia07'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4369,'','".AddSlashes(pg_result($resaco,0,'h19_dia08'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4370,'','".AddSlashes(pg_result($resaco,0,'h19_dia09'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4371,'','".AddSlashes(pg_result($resaco,0,'h19_dia10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4372,'','".AddSlashes(pg_result($resaco,0,'h19_op01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4373,'','".AddSlashes(pg_result($resaco,0,'h19_op02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4374,'','".AddSlashes(pg_result($resaco,0,'h19_op03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4375,'','".AddSlashes(pg_result($resaco,0,'h19_op04'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4376,'','".AddSlashes(pg_result($resaco,0,'h19_op05'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4377,'','".AddSlashes(pg_result($resaco,0,'h19_op06'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4378,'','".AddSlashes(pg_result($resaco,0,'h19_op07'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4379,'','".AddSlashes(pg_result($resaco,0,'h19_op08'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4380,'','".AddSlashes(pg_result($resaco,0,'h19_op09'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4381,'','".AddSlashes(pg_result($resaco,0,'h19_op10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4382,'','".AddSlashes(pg_result($resaco,0,'h19_per01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4383,'','".AddSlashes(pg_result($resaco,0,'h19_per02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4384,'','".AddSlashes(pg_result($resaco,0,'h19_per03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4385,'','".AddSlashes(pg_result($resaco,0,'h19_per04'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4386,'','".AddSlashes(pg_result($resaco,0,'h19_per05'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4387,'','".AddSlashes(pg_result($resaco,0,'h19_per06'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4388,'','".AddSlashes(pg_result($resaco,0,'h19_per07'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4389,'','".AddSlashes(pg_result($resaco,0,'h19_per08'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4390,'','".AddSlashes(pg_result($resaco,0,'h19_per09'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4391,'','".AddSlashes(pg_result($resaco,0,'h19_per10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,584,4392,'','".AddSlashes(pg_result($resaco,0,'h19_tpcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h19_codigo=null) { 
      $this->atualizacampos();
     $sql = " update protelac set ";
     $virgula = "";
     if(trim($this->h19_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_codigo"])){ 
       $sql  .= $virgula." h19_codigo = $this->h19_codigo ";
       $virgula = ",";
       if(trim($this->h19_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "h19_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h19_assent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_assent"])){ 
       $sql  .= $virgula." h19_assent = $this->h19_assent ";
       $virgula = ",";
       if(trim($this->h19_assent) == null ){ 
         $this->erro_sql = " Campo Assentamento nao Informado.";
         $this->erro_campo = "h19_assent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h19_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_tipo"])){ 
       $sql  .= $virgula." h19_tipo = '$this->h19_tipo' ";
       $virgula = ",";
       if(trim($this->h19_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "h19_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h19_dia01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia01"])){ 
       $sql  .= $virgula." h19_dia01 = $this->h19_dia01 ";
       $virgula = ",";
       if(trim($this->h19_dia01) == null ){ 
         $this->erro_sql = " Campo Dias nao Informado.";
         $this->erro_campo = "h19_dia01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h19_dia02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia02"])){ 
        if(trim($this->h19_dia02)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia02"])){ 
           $this->h19_dia02 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia02 = $this->h19_dia02 ";
       $virgula = ",";
     }
     if(trim($this->h19_dia03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia03"])){ 
        if(trim($this->h19_dia03)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia03"])){ 
           $this->h19_dia03 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia03 = $this->h19_dia03 ";
       $virgula = ",";
     }
     if(trim($this->h19_dia04)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia04"])){ 
        if(trim($this->h19_dia04)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia04"])){ 
           $this->h19_dia04 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia04 = $this->h19_dia04 ";
       $virgula = ",";
     }
     if(trim($this->h19_dia05)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia05"])){ 
        if(trim($this->h19_dia05)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia05"])){ 
           $this->h19_dia05 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia05 = $this->h19_dia05 ";
       $virgula = ",";
     }
     if(trim($this->h19_dia06)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia06"])){ 
        if(trim($this->h19_dia06)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia06"])){ 
           $this->h19_dia06 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia06 = $this->h19_dia06 ";
       $virgula = ",";
     }
     if(trim($this->h19_dia07)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia07"])){ 
        if(trim($this->h19_dia07)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia07"])){ 
           $this->h19_dia07 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia07 = $this->h19_dia07 ";
       $virgula = ",";
     }
     if(trim($this->h19_dia08)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia08"])){ 
        if(trim($this->h19_dia08)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia08"])){ 
           $this->h19_dia08 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia08 = $this->h19_dia08 ";
       $virgula = ",";
     }
     if(trim($this->h19_dia09)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia09"])){ 
        if(trim($this->h19_dia09)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia09"])){ 
           $this->h19_dia09 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia09 = $this->h19_dia09 ";
       $virgula = ",";
     }
     if(trim($this->h19_dia10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_dia10"])){ 
        if(trim($this->h19_dia10)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_dia10"])){ 
           $this->h19_dia10 = "0" ; 
        } 
       $sql  .= $virgula." h19_dia10 = $this->h19_dia10 ";
       $virgula = ",";
     }
     if(trim($this->h19_op01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op01"])){ 
       $sql  .= $virgula." h19_op01 = '$this->h19_op01' ";
       $virgula = ",";
       if(trim($this->h19_op01) == null ){ 
         $this->erro_sql = " Campo Operador 1 nao Informado.";
         $this->erro_campo = "h19_op01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h19_op02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op02"])){ 
       $sql  .= $virgula." h19_op02 = '$this->h19_op02' ";
       $virgula = ",";
     }
     if(trim($this->h19_op03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op03"])){ 
       $sql  .= $virgula." h19_op03 = '$this->h19_op03' ";
       $virgula = ",";
     }
     if(trim($this->h19_op04)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op04"])){ 
       $sql  .= $virgula." h19_op04 = '$this->h19_op04' ";
       $virgula = ",";
     }
     if(trim($this->h19_op05)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op05"])){ 
       $sql  .= $virgula." h19_op05 = '$this->h19_op05' ";
       $virgula = ",";
     }
     if(trim($this->h19_op06)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op06"])){ 
       $sql  .= $virgula." h19_op06 = '$this->h19_op06' ";
       $virgula = ",";
     }
     if(trim($this->h19_op07)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op07"])){ 
       $sql  .= $virgula." h19_op07 = '$this->h19_op07' ";
       $virgula = ",";
     }
     if(trim($this->h19_op08)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op08"])){ 
       $sql  .= $virgula." h19_op08 = '$this->h19_op08' ";
       $virgula = ",";
     }
     if(trim($this->h19_op09)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op09"])){ 
       $sql  .= $virgula." h19_op09 = '$this->h19_op09' ";
       $virgula = ",";
     }
     if(trim($this->h19_op10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_op10"])){ 
       $sql  .= $virgula." h19_op10 = '$this->h19_op10' ";
       $virgula = ",";
     }
     if(trim($this->h19_per01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per01"])){ 
       $sql  .= $virgula." h19_per01 = $this->h19_per01 ";
       $virgula = ",";
       if(trim($this->h19_per01) == null ){ 
         $this->erro_sql = " Campo Percentual 1 nao Informado.";
         $this->erro_campo = "h19_per01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h19_per02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per02"])){ 
        if(trim($this->h19_per02)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per02"])){ 
           $this->h19_per02 = "0" ; 
        } 
       $sql  .= $virgula." h19_per02 = $this->h19_per02 ";
       $virgula = ",";
     }
     if(trim($this->h19_per03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per03"])){ 
        if(trim($this->h19_per03)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per03"])){ 
           $this->h19_per03 = "0" ; 
        } 
       $sql  .= $virgula." h19_per03 = $this->h19_per03 ";
       $virgula = ",";
     }
     if(trim($this->h19_per04)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per04"])){ 
        if(trim($this->h19_per04)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per04"])){ 
           $this->h19_per04 = "0" ; 
        } 
       $sql  .= $virgula." h19_per04 = $this->h19_per04 ";
       $virgula = ",";
     }
     if(trim($this->h19_per05)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per05"])){ 
        if(trim($this->h19_per05)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per05"])){ 
           $this->h19_per05 = "0" ; 
        } 
       $sql  .= $virgula." h19_per05 = $this->h19_per05 ";
       $virgula = ",";
     }
     if(trim($this->h19_per06)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per06"])){ 
        if(trim($this->h19_per06)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per06"])){ 
           $this->h19_per06 = "0" ; 
        } 
       $sql  .= $virgula." h19_per06 = $this->h19_per06 ";
       $virgula = ",";
     }
     if(trim($this->h19_per07)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per07"])){ 
        if(trim($this->h19_per07)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per07"])){ 
           $this->h19_per07 = "0" ; 
        } 
       $sql  .= $virgula." h19_per07 = $this->h19_per07 ";
       $virgula = ",";
     }
     if(trim($this->h19_per08)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per08"])){ 
        if(trim($this->h19_per08)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per08"])){ 
           $this->h19_per08 = "0" ; 
        } 
       $sql  .= $virgula." h19_per08 = $this->h19_per08 ";
       $virgula = ",";
     }
     if(trim($this->h19_per09)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per09"])){ 
        if(trim($this->h19_per09)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per09"])){ 
           $this->h19_per09 = "0" ; 
        } 
       $sql  .= $virgula." h19_per09 = $this->h19_per09 ";
       $virgula = ",";
     }
     if(trim($this->h19_per10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_per10"])){ 
        if(trim($this->h19_per10)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h19_per10"])){ 
           $this->h19_per10 = "0" ; 
        } 
       $sql  .= $virgula." h19_per10 = $this->h19_per10 ";
       $virgula = ",";
     }
     if(trim($this->h19_tpcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h19_tpcalc"])){ 
       $sql  .= $virgula." h19_tpcalc = '$this->h19_tpcalc' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h19_codigo!=null){
       $sql .= " h19_codigo = $this->h19_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h19_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9541,'$this->h19_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_codigo"]))
           $resac = db_query("insert into db_acount values($acount,584,9541,'".AddSlashes(pg_result($resaco,$conresaco,'h19_codigo'))."','$this->h19_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_assent"]))
           $resac = db_query("insert into db_acount values($acount,584,4360,'".AddSlashes(pg_result($resaco,$conresaco,'h19_assent'))."','$this->h19_assent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_tipo"]))
           $resac = db_query("insert into db_acount values($acount,584,4361,'".AddSlashes(pg_result($resaco,$conresaco,'h19_tipo'))."','$this->h19_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia01"]))
           $resac = db_query("insert into db_acount values($acount,584,4362,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia01'))."','$this->h19_dia01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia02"]))
           $resac = db_query("insert into db_acount values($acount,584,4363,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia02'))."','$this->h19_dia02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia03"]))
           $resac = db_query("insert into db_acount values($acount,584,4364,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia03'))."','$this->h19_dia03',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia04"]))
           $resac = db_query("insert into db_acount values($acount,584,4365,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia04'))."','$this->h19_dia04',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia05"]))
           $resac = db_query("insert into db_acount values($acount,584,4366,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia05'))."','$this->h19_dia05',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia06"]))
           $resac = db_query("insert into db_acount values($acount,584,4367,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia06'))."','$this->h19_dia06',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia07"]))
           $resac = db_query("insert into db_acount values($acount,584,4368,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia07'))."','$this->h19_dia07',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia08"]))
           $resac = db_query("insert into db_acount values($acount,584,4369,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia08'))."','$this->h19_dia08',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia09"]))
           $resac = db_query("insert into db_acount values($acount,584,4370,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia09'))."','$this->h19_dia09',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_dia10"]))
           $resac = db_query("insert into db_acount values($acount,584,4371,'".AddSlashes(pg_result($resaco,$conresaco,'h19_dia10'))."','$this->h19_dia10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op01"]))
           $resac = db_query("insert into db_acount values($acount,584,4372,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op01'))."','$this->h19_op01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op02"]))
           $resac = db_query("insert into db_acount values($acount,584,4373,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op02'))."','$this->h19_op02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op03"]))
           $resac = db_query("insert into db_acount values($acount,584,4374,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op03'))."','$this->h19_op03',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op04"]))
           $resac = db_query("insert into db_acount values($acount,584,4375,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op04'))."','$this->h19_op04',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op05"]))
           $resac = db_query("insert into db_acount values($acount,584,4376,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op05'))."','$this->h19_op05',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op06"]))
           $resac = db_query("insert into db_acount values($acount,584,4377,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op06'))."','$this->h19_op06',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op07"]))
           $resac = db_query("insert into db_acount values($acount,584,4378,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op07'))."','$this->h19_op07',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op08"]))
           $resac = db_query("insert into db_acount values($acount,584,4379,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op08'))."','$this->h19_op08',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op09"]))
           $resac = db_query("insert into db_acount values($acount,584,4380,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op09'))."','$this->h19_op09',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_op10"]))
           $resac = db_query("insert into db_acount values($acount,584,4381,'".AddSlashes(pg_result($resaco,$conresaco,'h19_op10'))."','$this->h19_op10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per01"]))
           $resac = db_query("insert into db_acount values($acount,584,4382,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per01'))."','$this->h19_per01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per02"]))
           $resac = db_query("insert into db_acount values($acount,584,4383,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per02'))."','$this->h19_per02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per03"]))
           $resac = db_query("insert into db_acount values($acount,584,4384,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per03'))."','$this->h19_per03',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per04"]))
           $resac = db_query("insert into db_acount values($acount,584,4385,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per04'))."','$this->h19_per04',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per05"]))
           $resac = db_query("insert into db_acount values($acount,584,4386,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per05'))."','$this->h19_per05',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per06"]))
           $resac = db_query("insert into db_acount values($acount,584,4387,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per06'))."','$this->h19_per06',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per07"]))
           $resac = db_query("insert into db_acount values($acount,584,4388,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per07'))."','$this->h19_per07',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per08"]))
           $resac = db_query("insert into db_acount values($acount,584,4389,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per08'))."','$this->h19_per08',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per09"]))
           $resac = db_query("insert into db_acount values($acount,584,4390,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per09'))."','$this->h19_per09',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_per10"]))
           $resac = db_query("insert into db_acount values($acount,584,4391,'".AddSlashes(pg_result($resaco,$conresaco,'h19_per10'))."','$this->h19_per10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h19_tpcalc"]))
           $resac = db_query("insert into db_acount values($acount,584,4392,'".AddSlashes(pg_result($resaco,$conresaco,'h19_tpcalc'))."','$this->h19_tpcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de protelacoes                            nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h19_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de protelacoes                            nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h19_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h19_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9541,'$h19_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,584,9541,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4360,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4361,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4362,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4363,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4364,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4365,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia04'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4366,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia05'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4367,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia06'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4368,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia07'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4369,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia08'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4370,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia09'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4371,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_dia10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4372,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4373,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4374,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4375,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op04'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4376,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op05'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4377,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op06'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4378,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op07'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4379,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op08'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4380,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op09'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4381,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_op10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4382,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4383,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4384,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4385,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per04'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4386,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per05'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4387,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per06'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4388,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per07'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4389,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per08'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4390,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per09'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4391,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_per10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,584,4392,'','".AddSlashes(pg_result($resaco,$iresaco,'h19_tpcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from protelac
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h19_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h19_codigo = $h19_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de protelacoes                            nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h19_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de protelacoes                            nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:protelac";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h19_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from protelac ";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = protelac.h19_assent";
     $sql2 = "";
     if($dbwhere==""){
       if($h19_codigo!=null ){
         $sql2 .= " where protelac.h19_codigo = $h19_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $h19_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from protelac ";
     $sql2 = "";
     if($dbwhere==""){
       if($h19_codigo!=null ){
         $sql2 .= " where protelac.h19_codigo = $h19_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>