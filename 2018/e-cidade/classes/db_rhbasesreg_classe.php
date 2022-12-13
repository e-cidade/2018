<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhbasesreg
class cl_rhbasesreg { 
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
   var $rh54_seq = 0; 
   var $rh54_regist = 0; 
   var $rh54_base = null; 
   var $rh54_rubric = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh54_seq = int8 = Sequencial 
                 rh54_regist = int4 = Matrícula 
                 rh54_base = varchar(4) = Base 
                 rh54_rubric = varchar(4) = Rubrica 
                 ";
   //funcao construtor da classe 
   function cl_rhbasesreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhbasesreg"); 
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
       $this->rh54_seq = ($this->rh54_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh54_seq"]:$this->rh54_seq);
       $this->rh54_regist = ($this->rh54_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh54_regist"]:$this->rh54_regist);
       $this->rh54_base = ($this->rh54_base == ""?@$GLOBALS["HTTP_POST_VARS"]["rh54_base"]:$this->rh54_base);
       $this->rh54_rubric = ($this->rh54_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh54_rubric"]:$this->rh54_rubric);
     }else{
       $this->rh54_seq = ($this->rh54_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh54_seq"]:$this->rh54_seq);
     }
   }
   // funcao para inclusao
   function incluir ($rh54_seq){ 
      $this->atualizacampos();
     if($this->rh54_regist == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "rh54_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh54_base == null ){ 
       $this->erro_sql = " Campo Base nao Informado.";
       $this->erro_campo = "rh54_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh54_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica nao Informado.";
       $this->erro_campo = "rh54_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh54_seq == "" || $rh54_seq == null ){
       $result = db_query("select nextval('rhbasesreg_rh54_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhbasesreg_rh54_seq_seq do campo: rh54_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh54_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhbasesreg_rh54_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh54_seq)){
         $this->erro_sql = " Campo rh54_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh54_seq = $rh54_seq; 
       }
     }
     if(($this->rh54_seq == null) || ($this->rh54_seq == "") ){ 
       $this->erro_sql = " Campo rh54_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhbasesreg(
                                       rh54_seq 
                                      ,rh54_regist 
                                      ,rh54_base 
                                      ,rh54_rubric 
                       )
                values (
                                $this->rh54_seq 
                               ,$this->rh54_regist 
                               ,'$this->rh54_base' 
                               ,'$this->rh54_rubric' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Bases por registro ($this->rh54_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Bases por registro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Bases por registro ($this->rh54_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh54_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh54_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9019,'$this->rh54_seq','I')");
       $resac = db_query("insert into db_acount values($acount,1544,9019,'','".AddSlashes(pg_result($resaco,0,'rh54_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1544,9020,'','".AddSlashes(pg_result($resaco,0,'rh54_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1544,9021,'','".AddSlashes(pg_result($resaco,0,'rh54_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1544,9022,'','".AddSlashes(pg_result($resaco,0,'rh54_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh54_seq=null) { 
      $this->atualizacampos();
     $sql = " update rhbasesreg set ";
     $virgula = "";
     if(trim($this->rh54_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh54_seq"])){ 
       $sql  .= $virgula." rh54_seq = $this->rh54_seq ";
       $virgula = ",";
       if(trim($this->rh54_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh54_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh54_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh54_regist"])){ 
       $sql  .= $virgula." rh54_regist = $this->rh54_regist ";
       $virgula = ",";
       if(trim($this->rh54_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "rh54_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh54_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh54_base"])){ 
       $sql  .= $virgula." rh54_base = '$this->rh54_base' ";
       $virgula = ",";
       if(trim($this->rh54_base) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "rh54_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh54_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh54_rubric"])){ 
       $sql  .= $virgula." rh54_rubric = '$this->rh54_rubric' ";
       $virgula = ",";
       if(trim($this->rh54_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "rh54_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh54_seq!=null){
       $sql .= " rh54_seq = $this->rh54_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh54_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9019,'$this->rh54_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh54_seq"]))
           $resac = db_query("insert into db_acount values($acount,1544,9019,'".AddSlashes(pg_result($resaco,$conresaco,'rh54_seq'))."','$this->rh54_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh54_regist"]))
           $resac = db_query("insert into db_acount values($acount,1544,9020,'".AddSlashes(pg_result($resaco,$conresaco,'rh54_regist'))."','$this->rh54_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh54_base"]))
           $resac = db_query("insert into db_acount values($acount,1544,9021,'".AddSlashes(pg_result($resaco,$conresaco,'rh54_base'))."','$this->rh54_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh54_rubric"]))
           $resac = db_query("insert into db_acount values($acount,1544,9022,'".AddSlashes(pg_result($resaco,$conresaco,'rh54_rubric'))."','$this->rh54_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bases por registro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh54_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bases por registro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh54_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh54_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh54_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh54_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9019,'$rh54_seq','E')");
         $resac = db_query("insert into db_acount values($acount,1544,9019,'','".AddSlashes(pg_result($resaco,$iresaco,'rh54_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1544,9020,'','".AddSlashes(pg_result($resaco,$iresaco,'rh54_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1544,9021,'','".AddSlashes(pg_result($resaco,$iresaco,'rh54_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1544,9022,'','".AddSlashes(pg_result($resaco,$iresaco,'rh54_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhbasesreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh54_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh54_seq = $rh54_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bases por registro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh54_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bases por registro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh54_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh54_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhbasesreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh54_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbasesreg ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhbasesreg.rh54_regist";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhbasesreg.rh54_rubric
		                                      and  rhrubricas.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhbases  on  rhbases.rh32_base = rhbasesreg.rh54_base
		                                   and  rhbases.rh32_instit = rhrubricas.rh27_instit ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao
		                                    and  rhfuncao.rh37_instit = rhrubricas.rh27_instit ";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      inner join db_config  as a on   a.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      inner join db_config  as b on   b.codigo = rhbases.rh32_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh54_seq!=null ){
         $sql2 .= " where rhbasesreg.rh54_seq = $rh54_seq "; 
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
   function sql_query_file ( $rh54_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbasesreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh54_seq!=null ){
         $sql2 .= " where rhbasesreg.rh54_seq = $rh54_seq "; 
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
   function sql_query_base ( $rh54_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbasesreg ";
     $sql .= "      inner join bases  on  r08_codigo = rhbasesreg.rh54_base  
                                     and  r08_anousu = ".db_anofolha()."
                                     and  r08_mesusu = ".db_mesfolha()."
		                     and  r08_instit = ".db_getsession("DB_instit")." ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh54_seq!=null ){
         $sql2 .= " where rhbasesreg.rh54_seq = $rh54_seq "; 
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
   function sql_query_dadosprinc ( $rh54_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbasesreg ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhbasesreg.rh54_regist";
     $sql .= "      inner join rhbases  on  rhbases.rh32_base = rhbasesreg.rh54_base
		                                   and  rhbases.rh32_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($rh54_seq!=null ){
         $sql2 .= " where rhbasesreg.rh54_seq = $rh54_seq "; 
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
  
  /**
   * Retorna uma query com a junção de duas tabelas: rhbasesreg e basesr.
   * A união entre estas tabelas, encontra as rubricas da base do servidor
   * 
   * @param Base $oBase
   * @param Servidor $oServidor
   * @return String
   */
  public function sql_query_base_servidor(Base $oBase, Servidor $oServidor) {
    
    $sSqlRubrica  = "(select rh54_rubric ";
    $sSqlRubrica .= "     from rhbasesreg ";
    $sSqlRubrica .= "   where rh54_base = '{$oBase->getCodigo()}') ";      
    $sSqlRubrica .= " union ";
    $sSqlRubrica .= "(select r09_rubric ";
    $sSqlRubrica .= "     from basesr ";
    $sSqlRubrica .= "   where r09_anousu  = {$oBase->getCompetencia()->getAno()} ";
    $sSqlRubrica .= "     and r09_mesusu  = {$oBase->getCompetencia()->getMes()} ";
    $sSqlRubrica .= "     and r09_base    = '{$oBase->getCodigo()}' ";
    $sSqlRubrica .= "     and r09_instit  = {$oBase->getInstituicao()->getCodigo()}) ";  
    
    return $sSqlRubrica;
  }
}