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
//CLASSE DA ENTIDADE alunotransfturma
class cl_alunotransfturma { 
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
   var $ed69_i_codigo = 0; 
   var $ed69_i_matricula = 0; 
   var $ed69_i_turmaorigem = 0; 
   var $ed69_i_turmadestino = 0; 
   var $ed69_d_datatransf_dia = null; 
   var $ed69_d_datatransf_mes = null; 
   var $ed69_d_datatransf_ano = null; 
   var $ed69_d_datatransf = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed69_i_codigo = int8 = Código 
                 ed69_i_matricula = int8 = Matrícula 
                 ed69_i_turmaorigem = int8 = Turma de Origem 
                 ed69_i_turmadestino = int8 = Turma de Destino 
                 ed69_d_datatransf = date = Data da Transferência 
                 ";
   //funcao construtor da classe 
   function cl_alunotransfturma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunotransfturma"); 
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
       $this->ed69_i_codigo = ($this->ed69_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed69_i_codigo"]:$this->ed69_i_codigo);
       $this->ed69_i_matricula = ($this->ed69_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed69_i_matricula"]:$this->ed69_i_matricula);
       $this->ed69_i_turmaorigem = ($this->ed69_i_turmaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed69_i_turmaorigem"]:$this->ed69_i_turmaorigem);
       $this->ed69_i_turmadestino = ($this->ed69_i_turmadestino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed69_i_turmadestino"]:$this->ed69_i_turmadestino);
       if($this->ed69_d_datatransf == ""){
         $this->ed69_d_datatransf_dia = ($this->ed69_d_datatransf_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed69_d_datatransf_dia"]:$this->ed69_d_datatransf_dia);
         $this->ed69_d_datatransf_mes = ($this->ed69_d_datatransf_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed69_d_datatransf_mes"]:$this->ed69_d_datatransf_mes);
         $this->ed69_d_datatransf_ano = ($this->ed69_d_datatransf_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed69_d_datatransf_ano"]:$this->ed69_d_datatransf_ano);
         if($this->ed69_d_datatransf_dia != ""){
            $this->ed69_d_datatransf = $this->ed69_d_datatransf_ano."-".$this->ed69_d_datatransf_mes."-".$this->ed69_d_datatransf_dia;
         }
       }
     }else{
       $this->ed69_i_codigo = ($this->ed69_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed69_i_codigo"]:$this->ed69_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed69_i_codigo){ 
      $this->atualizacampos();
     if($this->ed69_i_matricula == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed69_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed69_i_turmaorigem == null ){ 
       $this->erro_sql = " Campo Turma de Origem nao Informado.";
       $this->erro_campo = "ed69_i_turmaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed69_i_turmadestino == null ){ 
       $this->erro_sql = " Campo Turma de Destino nao Informado.";
       $this->erro_campo = "ed69_i_turmadestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed69_d_datatransf == null ){ 
       $this->erro_sql = " Campo Data da Transferência nao Informado.";
       $this->erro_campo = "ed69_d_datatransf_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed69_i_codigo == "" || $ed69_i_codigo == null ){
       $result = db_query("select nextval('alunotransfturma_ed69_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunotransfturma_ed69_i_codigo_seq do campo: ed69_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed69_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from alunotransfturma_ed69_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed69_i_codigo)){
         $this->erro_sql = " Campo ed69_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed69_i_codigo = $ed69_i_codigo; 
       }
     }
     if(($this->ed69_i_codigo == null) || ($this->ed69_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed69_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunotransfturma(
                                       ed69_i_codigo 
                                      ,ed69_i_matricula 
                                      ,ed69_i_turmaorigem 
                                      ,ed69_i_turmadestino 
                                      ,ed69_d_datatransf 
                       )
                values (
                                $this->ed69_i_codigo 
                               ,$this->ed69_i_matricula 
                               ,$this->ed69_i_turmaorigem 
                               ,$this->ed69_i_turmadestino 
                               ,".($this->ed69_d_datatransf == "null" || $this->ed69_d_datatransf == ""?"null":"'".$this->ed69_d_datatransf."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tranferência de alunos entre turmas ($this->ed69_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tranferência de alunos entre turmas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tranferência de alunos entre turmas ($this->ed69_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed69_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed69_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008628,'$this->ed69_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010113,1008628,'','".AddSlashes(pg_result($resaco,0,'ed69_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010113,1008629,'','".AddSlashes(pg_result($resaco,0,'ed69_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010113,1008630,'','".AddSlashes(pg_result($resaco,0,'ed69_i_turmaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010113,1008631,'','".AddSlashes(pg_result($resaco,0,'ed69_i_turmadestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010113,1008632,'','".AddSlashes(pg_result($resaco,0,'ed69_d_datatransf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed69_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update alunotransfturma set ";
     $virgula = "";
     if(trim($this->ed69_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed69_i_codigo"])){ 
       $sql  .= $virgula." ed69_i_codigo = $this->ed69_i_codigo ";
       $virgula = ",";
       if(trim($this->ed69_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed69_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed69_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed69_i_matricula"])){ 
       $sql  .= $virgula." ed69_i_matricula = $this->ed69_i_matricula ";
       $virgula = ",";
       if(trim($this->ed69_i_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed69_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed69_i_turmaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed69_i_turmaorigem"])){ 
       $sql  .= $virgula." ed69_i_turmaorigem = $this->ed69_i_turmaorigem ";
       $virgula = ",";
       if(trim($this->ed69_i_turmaorigem) == null ){ 
         $this->erro_sql = " Campo Turma de Origem nao Informado.";
         $this->erro_campo = "ed69_i_turmaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed69_i_turmadestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed69_i_turmadestino"])){ 
       $sql  .= $virgula." ed69_i_turmadestino = $this->ed69_i_turmadestino ";
       $virgula = ",";
       if(trim($this->ed69_i_turmadestino) == null ){ 
         $this->erro_sql = " Campo Turma de Destino nao Informado.";
         $this->erro_campo = "ed69_i_turmadestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed69_d_datatransf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed69_d_datatransf_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed69_d_datatransf_dia"] !="") ){ 
       $sql  .= $virgula." ed69_d_datatransf = '$this->ed69_d_datatransf' ";
       $virgula = ",";
       if(trim($this->ed69_d_datatransf) == null ){ 
         $this->erro_sql = " Campo Data da Transferência nao Informado.";
         $this->erro_campo = "ed69_d_datatransf_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed69_d_datatransf_dia"])){ 
         $sql  .= $virgula." ed69_d_datatransf = null ";
         $virgula = ",";
         if(trim($this->ed69_d_datatransf) == null ){ 
           $this->erro_sql = " Campo Data da Transferência nao Informado.";
           $this->erro_campo = "ed69_d_datatransf_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ed69_i_codigo!=null){
       $sql .= " ed69_i_codigo = $this->ed69_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed69_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008628,'$this->ed69_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed69_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010113,1008628,'".AddSlashes(pg_result($resaco,$conresaco,'ed69_i_codigo'))."','$this->ed69_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed69_i_matricula"]))
           $resac = db_query("insert into db_acount values($acount,1010113,1008629,'".AddSlashes(pg_result($resaco,$conresaco,'ed69_i_matricula'))."','$this->ed69_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed69_i_turmaorigem"]))
           $resac = db_query("insert into db_acount values($acount,1010113,1008630,'".AddSlashes(pg_result($resaco,$conresaco,'ed69_i_turmaorigem'))."','$this->ed69_i_turmaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed69_i_turmadestino"]))
           $resac = db_query("insert into db_acount values($acount,1010113,1008631,'".AddSlashes(pg_result($resaco,$conresaco,'ed69_i_turmadestino'))."','$this->ed69_i_turmadestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed69_d_datatransf"]))
           $resac = db_query("insert into db_acount values($acount,1010113,1008632,'".AddSlashes(pg_result($resaco,$conresaco,'ed69_d_datatransf'))."','$this->ed69_d_datatransf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tranferência de alunos entre turmas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed69_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tranferência de alunos entre turmas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed69_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed69_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed69_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed69_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008628,'$ed69_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010113,1008628,'','".AddSlashes(pg_result($resaco,$iresaco,'ed69_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010113,1008629,'','".AddSlashes(pg_result($resaco,$iresaco,'ed69_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010113,1008630,'','".AddSlashes(pg_result($resaco,$iresaco,'ed69_i_turmaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010113,1008631,'','".AddSlashes(pg_result($resaco,$iresaco,'ed69_i_turmadestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010113,1008632,'','".AddSlashes(pg_result($resaco,$iresaco,'ed69_d_datatransf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunotransfturma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed69_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed69_i_codigo = $ed69_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tranferência de alunos entre turmas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed69_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tranferência de alunos entre turmas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed69_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed69_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunotransfturma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed69_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunotransfturma ";
     $sql .= "      inner join matricula on matricula.ed60_i_codigo = alunotransfturma.ed69_i_matricula";
     $sql .= "      inner join matriculaserie on matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "      inner join serie on serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
     $sql .= "      inner join aluno on aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem";
     $sql .= "      inner join base as base on base.ed31_i_codigo = turma.ed57_i_base";     
     $sql .= "      inner join cursoedu  on cursoedu.ed29_i_codigo = base.ed31_i_curso";     
     $sql .= "      inner join escola on escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join turma as turmadestino on turmadestino.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino";
     $sql .= "      inner join base as basedestino on basedestino.ed31_i_codigo = turmadestino.ed57_i_base";
     $sql .= "      inner join cursoedu as cursodestino on cursodestino.ed29_i_codigo = basedestino.ed31_i_curso";
     $sql .= "      inner join escola as escoladestino on escoladestino.ed18_i_codigo = turmadestino.ed57_i_escola";
     $sql .= "      inner join calendario as calendariodestino on calendariodestino.ed52_i_codigo = turmadestino.ed57_i_calendario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed69_i_codigo!=null ){
         $sql2 .= " where ed221_c_origem = 'S' AND alunotransfturma.ed69_i_codigo = $ed69_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where ed221_c_origem = 'S' AND $dbwhere";
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
   function sql_query_file ( $ed69_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunotransfturma ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed69_i_codigo!=null ){
         $sql2 .= " where alunotransfturma.ed69_i_codigo = $ed69_i_codigo "; 
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