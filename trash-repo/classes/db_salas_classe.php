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

//MODULO: educação
//CLASSE DA ENTIDADE salas
class cl_salas { 
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
   var $ed08_i_codigo = 0; 
   var $ed08_i_matricula = 0; 
   var $ed08_f_comportamento = 0; 
   var $ed08_f_nota = 0; 
   var $ed08_f_frequencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed08_i_codigo = int8 = Código 
                 ed08_i_matricula = int8 = Matrícula 
                 ed08_f_comportamento = float8 = Comportamento 
                 ed08_f_nota = float8 = Nota 
                 ed08_f_frequencia = float8 = Frequência 
                 ";
   //funcao construtor da classe 
   function cl_salas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("salas"); 
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
       $this->ed08_i_codigo = ($this->ed08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed08_i_codigo"]:$this->ed08_i_codigo);
       $this->ed08_i_matricula = ($this->ed08_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed08_i_matricula"]:$this->ed08_i_matricula);
       $this->ed08_f_comportamento = ($this->ed08_f_comportamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed08_f_comportamento"]:$this->ed08_f_comportamento);
       $this->ed08_f_nota = ($this->ed08_f_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["ed08_f_nota"]:$this->ed08_f_nota);
       $this->ed08_f_frequencia = ($this->ed08_f_frequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed08_f_frequencia"]:$this->ed08_f_frequencia);
     }else{
       $this->ed08_i_codigo = ($this->ed08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed08_i_codigo"]:$this->ed08_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed08_i_codigo){ 
      $this->atualizacampos();
     if($this->ed08_i_matricula == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed08_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed08_f_comportamento == null ){ 
       $this->erro_sql = " Campo Comportamento nao Informado.";
       $this->erro_campo = "ed08_f_comportamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed08_f_nota == null ){ 
       $this->erro_sql = " Campo Nota nao Informado.";
       $this->erro_campo = "ed08_f_nota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed08_f_frequencia == null ){ 
       $this->erro_sql = " Campo Frequência nao Informado.";
       $this->erro_campo = "ed08_f_frequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed08_i_codigo == "" || $ed08_i_codigo == null ){
       $result = @pg_query("select nextval('salas_ed08_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: salas_ed08_i_codigo_seq do campo: ed08_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed08_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from salas_ed08_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed08_i_codigo)){
         $this->erro_sql = " Campo ed08_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed08_i_codigo = $ed08_i_codigo; 
       }
     }
     if($ed08_i_matricula == "" || $ed08_i_matricula == null ){
       $result = @pg_query("select nextval('salas_ed08_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: salas_ed08_i_codigo_seq do campo: ed08_i_matricula"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed08_i_matricula = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from salas_ed08_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed08_i_matricula)){
         $this->erro_sql = " Campo ed08_i_matricula maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed08_i_matricula = $ed08_i_matricula; 
       }
     }
     if(($this->ed08_i_codigo == null) || ($this->ed08_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed08_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into salas(
                                       ed08_i_codigo 
                                      ,ed08_i_matricula 
                                      ,ed08_f_comportamento 
                                      ,ed08_f_nota 
                                      ,ed08_f_frequencia 
                       )
                values (
                                $this->ed08_i_codigo 
                               ,$this->ed08_i_matricula 
                               ,$this->ed08_f_comportamento 
                               ,$this->ed08_f_nota 
                               ,$this->ed08_f_frequencia 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Salas ($this->ed08_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Salas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Salas ($this->ed08_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed08_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed08_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006202,'$this->ed08_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1005008,1006202,'','".AddSlashes(pg_result($resaco,0,'ed08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005008,1005012,'','".AddSlashes(pg_result($resaco,0,'ed08_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005008,1005013,'','".AddSlashes(pg_result($resaco,0,'ed08_f_comportamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005008,1005014,'','".AddSlashes(pg_result($resaco,0,'ed08_f_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005008,1005015,'','".AddSlashes(pg_result($resaco,0,'ed08_f_frequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed08_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update salas set ";
     $virgula = "";
     if(trim($this->ed08_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed08_i_codigo"])){ 
       $sql  .= $virgula." ed08_i_codigo = $this->ed08_i_codigo ";
       $virgula = ",";
       if(trim($this->ed08_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed08_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed08_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed08_i_matricula"])){ 
       $sql  .= $virgula." ed08_i_matricula = $this->ed08_i_matricula ";
       $virgula = ",";
       if(trim($this->ed08_i_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed08_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed08_f_comportamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed08_f_comportamento"])){ 
       $sql  .= $virgula." ed08_f_comportamento = $this->ed08_f_comportamento ";
       $virgula = ",";
       if(trim($this->ed08_f_comportamento) == null ){ 
         $this->erro_sql = " Campo Comportamento nao Informado.";
         $this->erro_campo = "ed08_f_comportamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed08_f_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed08_f_nota"])){ 
       $sql  .= $virgula." ed08_f_nota = $this->ed08_f_nota ";
       $virgula = ",";
       if(trim($this->ed08_f_nota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "ed08_f_nota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed08_f_frequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed08_f_frequencia"])){ 
       $sql  .= $virgula." ed08_f_frequencia = $this->ed08_f_frequencia ";
       $virgula = ",";
       if(trim($this->ed08_f_frequencia) == null ){ 
         $this->erro_sql = " Campo Frequência nao Informado.";
         $this->erro_campo = "ed08_f_frequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed08_i_codigo!=null){
       $sql .= " ed08_i_codigo = $this->ed08_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed08_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006202,'$this->ed08_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed08_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1005008,1006202,'".AddSlashes(pg_result($resaco,$conresaco,'ed08_i_codigo'))."','$this->ed08_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed08_i_matricula"]))
           $resac = pg_query("insert into db_acount values($acount,1005008,1005012,'".AddSlashes(pg_result($resaco,$conresaco,'ed08_i_matricula'))."','$this->ed08_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed08_f_comportamento"]))
           $resac = pg_query("insert into db_acount values($acount,1005008,1005013,'".AddSlashes(pg_result($resaco,$conresaco,'ed08_f_comportamento'))."','$this->ed08_f_comportamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed08_f_nota"]))
           $resac = pg_query("insert into db_acount values($acount,1005008,1005014,'".AddSlashes(pg_result($resaco,$conresaco,'ed08_f_nota'))."','$this->ed08_f_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed08_f_frequencia"]))
           $resac = pg_query("insert into db_acount values($acount,1005008,1005015,'".AddSlashes(pg_result($resaco,$conresaco,'ed08_f_frequencia'))."','$this->ed08_f_frequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Salas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Salas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed08_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed08_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006202,'$ed08_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1005008,1006202,'','".AddSlashes(pg_result($resaco,$iresaco,'ed08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005008,1005012,'','".AddSlashes(pg_result($resaco,$iresaco,'ed08_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005008,1005013,'','".AddSlashes(pg_result($resaco,$iresaco,'ed08_f_comportamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005008,1005014,'','".AddSlashes(pg_result($resaco,$iresaco,'ed08_f_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005008,1005015,'','".AddSlashes(pg_result($resaco,$iresaco,'ed08_f_frequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from salas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed08_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed08_i_codigo = $ed08_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Salas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Salas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed08_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:salas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed08_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from salas ";
     $sql .= "      inner join matriculas  on  matriculas.ed09_i_codigo = salas.ed08_i_codigo";
     $sql .= "      inner join series  on  series.ed03_i_codigo = matriculas.ed09_i_serie";
     $sql .= "      inner join alunos  on  alunos.ed07_i_codigo = matriculas.ed09_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed08_i_codigo!=null ){
         $sql2 .= " where salas.ed08_i_codigo = $ed08_i_codigo "; 
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
   function sql_query_file ( $ed08_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from salas ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed08_i_codigo!=null ){
         $sql2 .= " where salas.ed08_i_codigo = $ed08_i_codigo "; 
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