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

//MODULO: empenho
//CLASSE DA ENTIDADE empageconfcanc
class cl_empageconfcanc { 
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
   var $e88_codmov = 0; 
   var $e88_data_dia = null; 
   var $e88_data_mes = null; 
   var $e88_data_ano = null; 
   var $e88_data = null; 
   var $e88_cheque = null; 
   var $e88_codgera = 0; 
   var $e88_seqerro = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e88_codmov = int4 = Movimento 
                 e88_data = date = Data 
                 e88_cheque = varchar(20) = Cheque 
                 e88_codgera = int4 = Código 
                 e88_seqerro = int4 = Erro banco 
                 ";
   //funcao construtor da classe 
   function cl_empageconfcanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empageconfcanc"); 
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
       $this->e88_codmov = ($this->e88_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e88_codmov"]:$this->e88_codmov);
       if($this->e88_data == ""){
         $this->e88_data_dia = ($this->e88_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e88_data_dia"]:$this->e88_data_dia);
         $this->e88_data_mes = ($this->e88_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e88_data_mes"]:$this->e88_data_mes);
         $this->e88_data_ano = ($this->e88_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e88_data_ano"]:$this->e88_data_ano);
         if($this->e88_data_dia != ""){
            $this->e88_data = $this->e88_data_ano."-".$this->e88_data_mes."-".$this->e88_data_dia;
         }
       }
       $this->e88_cheque = ($this->e88_cheque == ""?@$GLOBALS["HTTP_POST_VARS"]["e88_cheque"]:$this->e88_cheque);
       $this->e88_codgera = ($this->e88_codgera == ""?@$GLOBALS["HTTP_POST_VARS"]["e88_codgera"]:$this->e88_codgera);
       $this->e88_seqerro = ($this->e88_seqerro == ""?@$GLOBALS["HTTP_POST_VARS"]["e88_seqerro"]:$this->e88_seqerro);
     }else{
       $this->e88_codmov = ($this->e88_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e88_codmov"]:$this->e88_codmov);
     }
   }
   // funcao para inclusao
   function incluir ($e88_codmov){ 
      $this->atualizacampos();
     if($this->e88_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "e88_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e88_cheque == null ){ 
       $this->erro_sql = " Campo Cheque nao Informado.";
       $this->erro_campo = "e88_cheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e88_codgera == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "e88_codgera";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e88_seqerro == null ){ 
       $this->erro_sql = " Campo Erro banco nao Informado.";
       $this->erro_campo = "e88_seqerro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->e88_codmov = $e88_codmov; 
     if(($this->e88_codmov == null) || ($this->e88_codmov == "") ){ 
       $this->erro_sql = " Campo e88_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empageconfcanc(
                                       e88_codmov 
                                      ,e88_data 
                                      ,e88_cheque 
                                      ,e88_codgera 
                                      ,e88_seqerro 
                       )
                values (
                                $this->e88_codmov 
                               ,".($this->e88_data == "null" || $this->e88_data == ""?"null":"'".$this->e88_data."'")." 
                               ,'$this->e88_cheque' 
                               ,$this->e88_codgera 
                               ,$this->e88_seqerro 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cancela cheque ($this->e88_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cancela cheque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cancela cheque ($this->e88_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e88_codmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e88_codmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6201,'$this->e88_codmov','I')");
       $resac = db_query("insert into db_acount values($acount,1003,6201,'','".AddSlashes(pg_result($resaco,0,'e88_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1003,6203,'','".AddSlashes(pg_result($resaco,0,'e88_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1003,6204,'','".AddSlashes(pg_result($resaco,0,'e88_cheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1003,6205,'','".AddSlashes(pg_result($resaco,0,'e88_codgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1003,6256,'','".AddSlashes(pg_result($resaco,0,'e88_seqerro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e88_codmov=null) { 
      $this->atualizacampos();
     $sql = " update empageconfcanc set ";
     $virgula = "";
     if(trim($this->e88_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e88_codmov"])){ 
       $sql  .= $virgula." e88_codmov = $this->e88_codmov ";
       $virgula = ",";
       if(trim($this->e88_codmov) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "e88_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e88_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e88_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e88_data_dia"] !="") ){ 
       $sql  .= $virgula." e88_data = '$this->e88_data' ";
       $virgula = ",";
       if(trim($this->e88_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "e88_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e88_data_dia"])){ 
         $sql  .= $virgula." e88_data = null ";
         $virgula = ",";
         if(trim($this->e88_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "e88_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e88_cheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e88_cheque"])){ 
       $sql  .= $virgula." e88_cheque = '$this->e88_cheque' ";
       $virgula = ",";
       if(trim($this->e88_cheque) == null ){ 
         $this->erro_sql = " Campo Cheque nao Informado.";
         $this->erro_campo = "e88_cheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e88_codgera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e88_codgera"])){ 
       $sql  .= $virgula." e88_codgera = $this->e88_codgera ";
       $virgula = ",";
       if(trim($this->e88_codgera) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "e88_codgera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e88_seqerro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e88_seqerro"])){ 
       $sql  .= $virgula." e88_seqerro = $this->e88_seqerro ";
       $virgula = ",";
       if(trim($this->e88_seqerro) == null ){ 
         $this->erro_sql = " Campo Erro banco nao Informado.";
         $this->erro_campo = "e88_seqerro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e88_codmov!=null){
       $sql .= " e88_codmov = $this->e88_codmov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e88_codmov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6201,'$this->e88_codmov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e88_codmov"]))
           $resac = db_query("insert into db_acount values($acount,1003,6201,'".AddSlashes(pg_result($resaco,$conresaco,'e88_codmov'))."','$this->e88_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e88_data"]))
           $resac = db_query("insert into db_acount values($acount,1003,6203,'".AddSlashes(pg_result($resaco,$conresaco,'e88_data'))."','$this->e88_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e88_cheque"]))
           $resac = db_query("insert into db_acount values($acount,1003,6204,'".AddSlashes(pg_result($resaco,$conresaco,'e88_cheque'))."','$this->e88_cheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e88_codgera"]))
           $resac = db_query("insert into db_acount values($acount,1003,6205,'".AddSlashes(pg_result($resaco,$conresaco,'e88_codgera'))."','$this->e88_codgera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e88_seqerro"]))
           $resac = db_query("insert into db_acount values($acount,1003,6256,'".AddSlashes(pg_result($resaco,$conresaco,'e88_seqerro'))."','$this->e88_seqerro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancela cheque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e88_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancela cheque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e88_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e88_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e88_codmov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e88_codmov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6201,'$e88_codmov','E')");
         $resac = db_query("insert into db_acount values($acount,1003,6201,'','".AddSlashes(pg_result($resaco,$iresaco,'e88_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1003,6203,'','".AddSlashes(pg_result($resaco,$iresaco,'e88_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1003,6204,'','".AddSlashes(pg_result($resaco,$iresaco,'e88_cheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1003,6205,'','".AddSlashes(pg_result($resaco,$iresaco,'e88_codgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1003,6256,'','".AddSlashes(pg_result($resaco,$iresaco,'e88_seqerro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empageconfcanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e88_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e88_codmov = $e88_codmov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancela cheque nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e88_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancela cheque nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e88_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e88_codmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:empageconfcanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e88_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageconfcanc ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfcanc.e88_codmov";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if($dbwhere==""){
       if($e88_codmov!=null ){
         $sql2 .= " where empageconfcanc.e88_codmov = $e88_codmov "; 
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
   function sql_query_banco ( $e88_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageconfcanc ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfcanc.e88_codmov";
     $sql .= "      left join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      left join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left join empord  on  empord.e82_codmov = empagemov.e81_codmov";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      left join errobanco on  e92_sequencia  = e88_seqerro";
     $sql2 = "";
     if($dbwhere==""){
       if($e88_codmov!=null ){
         $sql2 .= " where empageconfcanc.e88_codmov = $e88_codmov "; 
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
   function sql_query_file ( $e88_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageconfcanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($e88_codmov!=null ){
         $sql2 .= " where empageconfcanc.e88_codmov = $e88_codmov "; 
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