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
//CLASSE DA ENTIDADE alunopossib
class cl_alunopossib { 
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
   var $ed79_i_codigo = 0; 
   var $ed79_i_alunocurso = 0; 
   var $ed79_i_serie = 0; 
   var $ed79_i_turno = 0; 
   var $ed79_i_turmaant = 0; 
   var $ed79_c_resulant = null; 
   var $ed79_c_situacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed79_i_codigo = int8 = Código 
                 ed79_i_alunocurso = int8 = Aluno 
                 ed79_i_serie = int8 = Série/Ano 
                 ed79_i_turno = int8 = Turno 
                 ed79_i_turmaant = int8 = Turma Anterior 
                 ed79_c_resulant = char(1) = Resultado Anterior 
                 ed79_c_situacao = char(1) = Situação 
                 ";
   //funcao construtor da classe 
   function cl_alunopossib() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunopossib"); 
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
       $this->ed79_i_codigo = ($this->ed79_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed79_i_codigo"]:$this->ed79_i_codigo);
       $this->ed79_i_alunocurso = ($this->ed79_i_alunocurso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed79_i_alunocurso"]:$this->ed79_i_alunocurso);
       $this->ed79_i_serie = ($this->ed79_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed79_i_serie"]:$this->ed79_i_serie);
       $this->ed79_i_turno = ($this->ed79_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed79_i_turno"]:$this->ed79_i_turno);
       $this->ed79_i_turmaant = ($this->ed79_i_turmaant == ""?@$GLOBALS["HTTP_POST_VARS"]["ed79_i_turmaant"]:$this->ed79_i_turmaant);
       $this->ed79_c_resulant = ($this->ed79_c_resulant == ""?@$GLOBALS["HTTP_POST_VARS"]["ed79_c_resulant"]:$this->ed79_c_resulant);
       $this->ed79_c_situacao = ($this->ed79_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed79_c_situacao"]:$this->ed79_c_situacao);
     }else{
       $this->ed79_i_codigo = ($this->ed79_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed79_i_codigo"]:$this->ed79_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed79_i_codigo){ 
      $this->atualizacampos();
     if($this->ed79_i_alunocurso == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed79_i_alunocurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed79_i_serie == null ){ 
       $this->erro_sql = " Campo Série/Ano nao Informado.";
       $this->erro_campo = "ed79_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed79_i_turno == null ){ 
       $this->erro_sql = " Campo Turno nao Informado.";
       $this->erro_campo = "ed79_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed79_i_turmaant == null ){ 
       $this->ed79_i_turmaant = "null";
     }
     if($ed79_i_codigo == "" || $ed79_i_codigo == null ){
       $result = db_query("select nextval('alunopossib_ed79_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunopossib_ed79_i_codigo_seq do campo: ed79_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed79_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from alunopossib_ed79_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed79_i_codigo)){
         $this->erro_sql = " Campo ed79_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed79_i_codigo = $ed79_i_codigo; 
       }
     }
     if(($this->ed79_i_codigo == null) || ($this->ed79_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed79_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunopossib(
                                       ed79_i_codigo 
                                      ,ed79_i_alunocurso 
                                      ,ed79_i_serie 
                                      ,ed79_i_turno 
                                      ,ed79_i_turmaant 
                                      ,ed79_c_resulant 
                                      ,ed79_c_situacao 
                       )
                values (
                                $this->ed79_i_codigo 
                               ,$this->ed79_i_alunocurso 
                               ,$this->ed79_i_serie 
                               ,$this->ed79_i_turno 
                               ,$this->ed79_i_turmaant 
                               ,'$this->ed79_c_resulant' 
                               ,'$this->ed79_c_situacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alunos com Possibilidade de Matrícula ($this->ed79_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alunos com Possibilidade de Matrícula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alunos com Possibilidade de Matrícula ($this->ed79_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed79_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed79_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008401,'$this->ed79_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010068,1008401,'','".AddSlashes(pg_result($resaco,0,'ed79_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010068,1008402,'','".AddSlashes(pg_result($resaco,0,'ed79_i_alunocurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010068,1008403,'','".AddSlashes(pg_result($resaco,0,'ed79_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010068,1008404,'','".AddSlashes(pg_result($resaco,0,'ed79_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010068,1008406,'','".AddSlashes(pg_result($resaco,0,'ed79_i_turmaant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010068,1008405,'','".AddSlashes(pg_result($resaco,0,'ed79_c_resulant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010068,1008407,'','".AddSlashes(pg_result($resaco,0,'ed79_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed79_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update alunopossib set ";
     $virgula = "";
     if(trim($this->ed79_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_codigo"])){ 
       $sql  .= $virgula." ed79_i_codigo = $this->ed79_i_codigo ";
       $virgula = ",";
       if(trim($this->ed79_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed79_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed79_i_alunocurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_alunocurso"])){ 
       $sql  .= $virgula." ed79_i_alunocurso = $this->ed79_i_alunocurso ";
       $virgula = ",";
       if(trim($this->ed79_i_alunocurso) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed79_i_alunocurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed79_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_serie"])){ 
       $sql  .= $virgula." ed79_i_serie = $this->ed79_i_serie ";
       $virgula = ",";
       if(trim($this->ed79_i_serie) == null ){ 
         $this->erro_sql = " Campo Série/Ano nao Informado.";
         $this->erro_campo = "ed79_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed79_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_turno"])){ 
       $sql  .= $virgula." ed79_i_turno = $this->ed79_i_turno ";
       $virgula = ",";
       if(trim($this->ed79_i_turno) == null ){ 
         $this->erro_sql = " Campo Turno nao Informado.";
         $this->erro_campo = "ed79_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed79_i_turmaant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_turmaant"])){ 
        if(trim($this->ed79_i_turmaant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_turmaant"])){ 
           $this->ed79_i_turmaant = "null" ;
        } 
       $sql  .= $virgula." ed79_i_turmaant = $this->ed79_i_turmaant ";
       $virgula = ",";
     }
     if(trim($this->ed79_c_resulant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed79_c_resulant"])){ 
       $sql  .= $virgula." ed79_c_resulant = '$this->ed79_c_resulant' ";
       $virgula = ",";
     }
     if(trim($this->ed79_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed79_c_situacao"])){ 
       $sql  .= $virgula." ed79_c_situacao = '$this->ed79_c_situacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed79_i_codigo!=null){
       $sql .= " ed79_i_codigo = $this->ed79_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed79_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008401,'$this->ed79_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010068,1008401,'".AddSlashes(pg_result($resaco,$conresaco,'ed79_i_codigo'))."','$this->ed79_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_alunocurso"]))
           $resac = db_query("insert into db_acount values($acount,1010068,1008402,'".AddSlashes(pg_result($resaco,$conresaco,'ed79_i_alunocurso'))."','$this->ed79_i_alunocurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_serie"]))
           $resac = db_query("insert into db_acount values($acount,1010068,1008403,'".AddSlashes(pg_result($resaco,$conresaco,'ed79_i_serie'))."','$this->ed79_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_turno"]))
           $resac = db_query("insert into db_acount values($acount,1010068,1008404,'".AddSlashes(pg_result($resaco,$conresaco,'ed79_i_turno'))."','$this->ed79_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed79_i_turmaant"]))
           $resac = db_query("insert into db_acount values($acount,1010068,1008406,'".AddSlashes(pg_result($resaco,$conresaco,'ed79_i_turmaant'))."','$this->ed79_i_turmaant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed79_c_resulant"]))
           $resac = db_query("insert into db_acount values($acount,1010068,1008405,'".AddSlashes(pg_result($resaco,$conresaco,'ed79_c_resulant'))."','$this->ed79_c_resulant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed79_c_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1010068,1008407,'".AddSlashes(pg_result($resaco,$conresaco,'ed79_c_situacao'))."','$this->ed79_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos com Possibilidade de Matrícula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed79_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alunos com Possibilidade de Matrícula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed79_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed79_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed79_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed79_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008401,'$ed79_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010068,1008401,'','".AddSlashes(pg_result($resaco,$iresaco,'ed79_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010068,1008402,'','".AddSlashes(pg_result($resaco,$iresaco,'ed79_i_alunocurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010068,1008403,'','".AddSlashes(pg_result($resaco,$iresaco,'ed79_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010068,1008404,'','".AddSlashes(pg_result($resaco,$iresaco,'ed79_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010068,1008406,'','".AddSlashes(pg_result($resaco,$iresaco,'ed79_i_turmaant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010068,1008405,'','".AddSlashes(pg_result($resaco,$iresaco,'ed79_c_resulant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010068,1008407,'','".AddSlashes(pg_result($resaco,$iresaco,'ed79_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunopossib
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed79_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed79_i_codigo = $ed79_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos com Possibilidade de Matrícula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed79_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alunos com Possibilidade de Matrícula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed79_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed79_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunopossib";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed79_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunopossib ";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = alunopossib.ed79_i_turno";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = alunopossib.ed79_i_serie";
     $sql .= "      inner join alunocurso  on  alunocurso.ed56_i_codigo = alunopossib.ed79_i_alunocurso";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = alunocurso.ed56_i_escola";
     $sql .= "      inner join aluno  as a on   a.ed47_i_codigo = alunocurso.ed56_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = alunocurso.ed56_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = alunocurso.ed56_i_base";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      left join turma  on  turma.ed57_i_codigo = alunopossib.ed79_i_turmaant";
     $sql2 = "";
     if($dbwhere==""){
       if($ed79_i_codigo!=null ){
         $sql2 .= " where alunopossib.ed79_i_codigo = $ed79_i_codigo "; 
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
   function sql_query_file ( $ed79_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunopossib ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed79_i_codigo!=null ){
         $sql2 .= " where alunopossib.ed79_i_codigo = $ed79_i_codigo "; 
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