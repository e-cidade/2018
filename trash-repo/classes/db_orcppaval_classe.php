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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcppaval
class cl_orcppaval { 
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
   var $o24_codseqppa = 0; 
   var $o24_codppa = 0; 
   var $o24_exercicio = 0; 
   var $o24_valor = 0; 
   var $o24_quantmed = 0; 
   var $o24_proces = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o24_codseqppa = int8 = Sequencia PPA 
                 o24_codppa = int8 = Código 
                 o24_exercicio = int4 = Exercicío 
                 o24_valor = float8 = Valor 
                 o24_quantmed = float8 = Quant. Medida 
                 o24_proces = int4 = Processo 
                 ";
   //funcao construtor da classe 
   function cl_orcppaval() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcppaval"); 
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
       $this->o24_codseqppa = ($this->o24_codseqppa == ""?@$GLOBALS["HTTP_POST_VARS"]["o24_codseqppa"]:$this->o24_codseqppa);
       $this->o24_codppa = ($this->o24_codppa == ""?@$GLOBALS["HTTP_POST_VARS"]["o24_codppa"]:$this->o24_codppa);
       $this->o24_exercicio = ($this->o24_exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["o24_exercicio"]:$this->o24_exercicio);
       $this->o24_valor = ($this->o24_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o24_valor"]:$this->o24_valor);
       $this->o24_quantmed = ($this->o24_quantmed == ""?@$GLOBALS["HTTP_POST_VARS"]["o24_quantmed"]:$this->o24_quantmed);
       $this->o24_proces = ($this->o24_proces == ""?@$GLOBALS["HTTP_POST_VARS"]["o24_proces"]:$this->o24_proces);
     }else{
       $this->o24_codseqppa = ($this->o24_codseqppa == ""?@$GLOBALS["HTTP_POST_VARS"]["o24_codseqppa"]:$this->o24_codseqppa);
     }
   }
   // funcao para inclusao
   function incluir ($o24_codseqppa){ 
      $this->atualizacampos();
     if($this->o24_codppa == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "o24_codppa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o24_exercicio == null ){ 
       $this->erro_sql = " Campo Exercicío nao Informado.";
       $this->erro_campo = "o24_exercicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o24_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o24_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o24_quantmed == null ){ 
       $this->o24_quantmed = "0";
     }
     if($this->o24_proces == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "o24_proces";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o24_codseqppa == "" || $o24_codseqppa == null ){
       $result = db_query("select nextval('orcppaval_o24_codseqppa_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcppaval_o24_codseqppa_seq do campo: o24_codseqppa"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o24_codseqppa = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcppaval_o24_codseqppa_seq");
       if(($result != false) && (pg_result($result,0,0) < $o24_codseqppa)){
         $this->erro_sql = " Campo o24_codseqppa maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o24_codseqppa = $o24_codseqppa; 
       }
     }
     if(($this->o24_codseqppa == null) || ($this->o24_codseqppa == "") ){ 
       $this->erro_sql = " Campo o24_codseqppa nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcppaval(
                                       o24_codseqppa 
                                      ,o24_codppa 
                                      ,o24_exercicio 
                                      ,o24_valor 
                                      ,o24_quantmed 
                                      ,o24_proces 
                       )
                values (
                                $this->o24_codseqppa 
                               ,$this->o24_codppa 
                               ,$this->o24_exercicio 
                               ,$this->o24_valor 
                               ,$this->o24_quantmed 
                               ,$this->o24_proces 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores PPA ($this->o24_codseqppa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores PPA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores PPA ($this->o24_codseqppa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o24_codseqppa;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o24_codseqppa));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6484,'$this->o24_codseqppa','I')");
       $resac = db_query("insert into db_acount values($acount,1066,6484,'','".AddSlashes(pg_result($resaco,0,'o24_codseqppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1066,6485,'','".AddSlashes(pg_result($resaco,0,'o24_codppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1066,6486,'','".AddSlashes(pg_result($resaco,0,'o24_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1066,6487,'','".AddSlashes(pg_result($resaco,0,'o24_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1066,6488,'','".AddSlashes(pg_result($resaco,0,'o24_quantmed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1066,6518,'','".AddSlashes(pg_result($resaco,0,'o24_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o24_codseqppa=null) { 
      $this->atualizacampos();
     $sql = " update orcppaval set ";
     $virgula = "";
     if(trim($this->o24_codseqppa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o24_codseqppa"])){ 
       $sql  .= $virgula." o24_codseqppa = $this->o24_codseqppa ";
       $virgula = ",";
       if(trim($this->o24_codseqppa) == null ){ 
         $this->erro_sql = " Campo Sequencia PPA nao Informado.";
         $this->erro_campo = "o24_codseqppa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o24_codppa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o24_codppa"])){ 
       $sql  .= $virgula." o24_codppa = $this->o24_codppa ";
       $virgula = ",";
       if(trim($this->o24_codppa) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o24_codppa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o24_exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o24_exercicio"])){ 
       $sql  .= $virgula." o24_exercicio = $this->o24_exercicio ";
       $virgula = ",";
       if(trim($this->o24_exercicio) == null ){ 
         $this->erro_sql = " Campo Exercicío nao Informado.";
         $this->erro_campo = "o24_exercicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o24_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o24_valor"])){ 
       $sql  .= $virgula." o24_valor = $this->o24_valor ";
       $virgula = ",";
       if(trim($this->o24_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o24_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o24_quantmed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o24_quantmed"])){ 
        if(trim($this->o24_quantmed)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o24_quantmed"])){ 
           $this->o24_quantmed = "0" ; 
        } 
       $sql  .= $virgula." o24_quantmed = $this->o24_quantmed ";
       $virgula = ",";
     }
     if(trim($this->o24_proces)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o24_proces"])){ 
       $sql  .= $virgula." o24_proces = $this->o24_proces ";
       $virgula = ",";
       if(trim($this->o24_proces) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "o24_proces";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o24_codseqppa!=null){
       $sql .= " o24_codseqppa = $this->o24_codseqppa";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o24_codseqppa));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6484,'$this->o24_codseqppa','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o24_codseqppa"]))
           $resac = db_query("insert into db_acount values($acount,1066,6484,'".AddSlashes(pg_result($resaco,$conresaco,'o24_codseqppa'))."','$this->o24_codseqppa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o24_codppa"]))
           $resac = db_query("insert into db_acount values($acount,1066,6485,'".AddSlashes(pg_result($resaco,$conresaco,'o24_codppa'))."','$this->o24_codppa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o24_exercicio"]))
           $resac = db_query("insert into db_acount values($acount,1066,6486,'".AddSlashes(pg_result($resaco,$conresaco,'o24_exercicio'))."','$this->o24_exercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o24_valor"]))
           $resac = db_query("insert into db_acount values($acount,1066,6487,'".AddSlashes(pg_result($resaco,$conresaco,'o24_valor'))."','$this->o24_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o24_quantmed"]))
           $resac = db_query("insert into db_acount values($acount,1066,6488,'".AddSlashes(pg_result($resaco,$conresaco,'o24_quantmed'))."','$this->o24_quantmed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o24_proces"]))
           $resac = db_query("insert into db_acount values($acount,1066,6518,'".AddSlashes(pg_result($resaco,$conresaco,'o24_proces'))."','$this->o24_proces',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores PPA nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o24_codseqppa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores PPA nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o24_codseqppa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o24_codseqppa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o24_codseqppa=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o24_codseqppa));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6484,'$o24_codseqppa','E')");
         $resac = db_query("insert into db_acount values($acount,1066,6484,'','".AddSlashes(pg_result($resaco,$iresaco,'o24_codseqppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1066,6485,'','".AddSlashes(pg_result($resaco,$iresaco,'o24_codppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1066,6486,'','".AddSlashes(pg_result($resaco,$iresaco,'o24_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1066,6487,'','".AddSlashes(pg_result($resaco,$iresaco,'o24_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1066,6488,'','".AddSlashes(pg_result($resaco,$iresaco,'o24_quantmed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1066,6518,'','".AddSlashes(pg_result($resaco,$iresaco,'o24_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcppaval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o24_codseqppa != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o24_codseqppa = $o24_codseqppa ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores PPA nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o24_codseqppa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores PPA nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o24_codseqppa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o24_codseqppa;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcppaval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o24_codseqppa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppaval ";
     $sql .= "      inner join orcppa  on  orcppa.o23_codppa = orcppaval.o24_codppa";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcppa.o23_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcppa.o23_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcppa.o23_anoexe and  orcprograma.o54_programa = orcppa.o23_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcppa.o23_anoexe and  orcprojativ.o55_projativ = orcppa.o23_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcppa.o23_anoexe and  orcorgao.o40_orgao = orcppa.o23_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcppa.o23_anoexe and  orcunidade.o41_orgao = orcppa.o23_orgao and  orcunidade.o41_unidade = orcppa.o23_unidade";
     $sql .= "      inner join orcppalei  on  orcppalei.o21_codleippa = orcppa.o23_codleippa";
     $sql .= "      inner join orctiporec  on orctiporec.o15_codigo = o26_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($o24_codseqppa!=null ){
         $sql2 .= " where orcppaval.o24_codseqppa = $o24_codseqppa "; 
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

   function sql_query_dad ( $o24_codseqppa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppa ";
     $sql .= "    inner join orcppaval  on  orcppa.o23_codppa = orcppaval.o24_codppa";
     $sql .= "    left join orcppavalele  on o25_codseqppa = o24_codseqppa";
     $sql .= "    left join orcelemento   on o25_codele    = o56_codele and o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "    left join orcppatiporec on o26_codseqppa = o24_codseqppa";
     $sql .= "    inner join orctiporec    on o26_codigo    = o15_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($o24_codseqppa!=null ){
         $sql2 .= " where orcppaval.o24_codseqppa = $o24_codseqppa "; 
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
   // funcao do sql 
   function sql_query_file ( $o24_codseqppa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppaval ";
     $sql2 = "";
     if($dbwhere==""){
       if($o24_codseqppa!=null ){
         $sql2 .= " where orcppaval.o24_codseqppa = $o24_codseqppa "; 
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
   // funcao do sql 
   function sql_query_completo ( $o24_codseqppa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppa ";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcppa.o23_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcppa.o23_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcppa.o23_anoexe and  orcprograma.o54_programa = orcppa.o23_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcppa.o23_anoexe and  orcprojativ.o55_projativ = orcppa.o23_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcppa.o23_anoexe and  orcorgao.o40_orgao = orcppa.o23_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcppa.o23_anoexe and  orcunidade.o41_orgao = orcppa.o23_orgao and  orcunidade.o41_unidade = orcppa.o23_unidade";
     $sql .= "      inner join db_config  as d on   d.codigo = orcorgao.o40_instit";
     $sql .= "      inner join orcproduto   on   o22_codproduto = o23_produto";
     $sql .= "      inner join orcppalei   on   o21_codleippa = o23_codleippa";
     $sql .= "    inner join orcppaval  on  orcppa.o23_codppa = orcppaval.o24_codppa";
     $sql .= "    left join orcppavalele  on o25_codseqppa = o24_codseqppa";
     $sql .= "    left join orcelemento   on o25_codele    = o56_codele and o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "    left join orcppatiporec on o26_codseqppa = o24_codseqppa";
     $sql .= "    left join orctiporec    on o26_codigo    = o15_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($o24_codseqppa!=null ){
         $sql2 .= " where orcppaval.o24_codseqppa = $o24_codseqppa "; 
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