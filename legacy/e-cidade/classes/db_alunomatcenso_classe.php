<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: escola
//CLASSE DA ENTIDADE alunomatcenso
class cl_alunomatcenso { 
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
   var $ed280_i_codigo = 0; 
   var $ed280_i_aluno = 0; 
   var $ed280_i_turmacenso = 0; 
   var $ed280_i_ano = 0; 
   var $ed280_i_matcenso = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed280_i_codigo = int4 = Código 
                 ed280_i_aluno = int4 = Aluno 
                 ed280_i_turmacenso = int8 = Código INEP da Turma 
                 ed280_i_ano = int4 = Ano 
                 ed280_i_matcenso = int8 = Matrícula INEP 
                 ";
   //funcao construtor da classe 
   function cl_alunomatcenso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunomatcenso"); 
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
       $this->ed280_i_codigo = ($this->ed280_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed280_i_codigo"]:$this->ed280_i_codigo);
       $this->ed280_i_aluno = ($this->ed280_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed280_i_aluno"]:$this->ed280_i_aluno);
       $this->ed280_i_turmacenso = ($this->ed280_i_turmacenso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed280_i_turmacenso"]:$this->ed280_i_turmacenso);
       $this->ed280_i_ano = ($this->ed280_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed280_i_ano"]:$this->ed280_i_ano);
       $this->ed280_i_matcenso = ($this->ed280_i_matcenso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed280_i_matcenso"]:$this->ed280_i_matcenso);
     }else{
       $this->ed280_i_codigo = ($this->ed280_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed280_i_codigo"]:$this->ed280_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed280_i_codigo){ 
      $this->atualizacampos();
     if($this->ed280_i_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed280_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed280_i_turmacenso == null ){ 
       $this->ed280_i_turmacenso = "0";
     }
     if($this->ed280_i_ano == null ){ 
       $this->ed280_i_ano = "0";
     }
     if($this->ed280_i_matcenso == null ){ 
       $this->ed280_i_matcenso = "0";
     }
     if($ed280_i_codigo == "" || $ed280_i_codigo == null ){
       $result = db_query("select nextval('alunomatcenso_ed280_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunomatcenso_ed280_i_codigo_seq do campo: ed280_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed280_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from alunomatcenso_ed280_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed280_i_codigo)){
         $this->erro_sql = " Campo ed280_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed280_i_codigo = $ed280_i_codigo; 
       }
     }
     if(($this->ed280_i_codigo == null) || ($this->ed280_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed280_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunomatcenso(
                                       ed280_i_codigo 
                                      ,ed280_i_aluno 
                                      ,ed280_i_turmacenso 
                                      ,ed280_i_ano 
                                      ,ed280_i_matcenso 
                       )
                values (
                                $this->ed280_i_codigo 
                               ,$this->ed280_i_aluno 
                               ,$this->ed280_i_turmacenso 
                               ,$this->ed280_i_ano 
                               ,$this->ed280_i_matcenso 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "alunomatcenso ($this->ed280_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "alunomatcenso já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "alunomatcenso ($this->ed280_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed280_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed280_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15571,'$this->ed280_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2731,15571,'','".AddSlashes(pg_result($resaco,0,'ed280_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2731,15572,'','".AddSlashes(pg_result($resaco,0,'ed280_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2731,15573,'','".AddSlashes(pg_result($resaco,0,'ed280_i_turmacenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2731,15574,'','".AddSlashes(pg_result($resaco,0,'ed280_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2731,15575,'','".AddSlashes(pg_result($resaco,0,'ed280_i_matcenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed280_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update alunomatcenso set ";
     $virgula = "";
     if(trim($this->ed280_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_codigo"])){ 
       $sql  .= $virgula." ed280_i_codigo = $this->ed280_i_codigo ";
       $virgula = ",";
       if(trim($this->ed280_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed280_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed280_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_aluno"])){ 
       $sql  .= $virgula." ed280_i_aluno = $this->ed280_i_aluno ";
       $virgula = ",";
       if(trim($this->ed280_i_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed280_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed280_i_turmacenso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_turmacenso"])){ 
        if(trim($this->ed280_i_turmacenso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_turmacenso"])){ 
           $this->ed280_i_turmacenso = "0" ; 
        } 
       $sql  .= $virgula." ed280_i_turmacenso = $this->ed280_i_turmacenso ";
       $virgula = ",";
     }
     if(trim($this->ed280_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_ano"])){ 
        if(trim($this->ed280_i_ano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_ano"])){ 
           $this->ed280_i_ano = "0" ; 
        } 
       $sql  .= $virgula." ed280_i_ano = $this->ed280_i_ano ";
       $virgula = ",";
     }
     if(trim($this->ed280_i_matcenso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_matcenso"])){ 
        if(trim($this->ed280_i_matcenso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_matcenso"])){ 
           $this->ed280_i_matcenso = "0" ; 
        } 
       $sql  .= $virgula." ed280_i_matcenso = $this->ed280_i_matcenso ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed280_i_codigo!=null){
       $sql .= " ed280_i_codigo = $this->ed280_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed280_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15571,'$this->ed280_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_codigo"]) || $this->ed280_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2731,15571,'".AddSlashes(pg_result($resaco,$conresaco,'ed280_i_codigo'))."','$this->ed280_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_aluno"]) || $this->ed280_i_aluno != "")
           $resac = db_query("insert into db_acount values($acount,2731,15572,'".AddSlashes(pg_result($resaco,$conresaco,'ed280_i_aluno'))."','$this->ed280_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_turmacenso"]) || $this->ed280_i_turmacenso != "")
           $resac = db_query("insert into db_acount values($acount,2731,15573,'".AddSlashes(pg_result($resaco,$conresaco,'ed280_i_turmacenso'))."','$this->ed280_i_turmacenso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_ano"]) || $this->ed280_i_ano != "")
           $resac = db_query("insert into db_acount values($acount,2731,15574,'".AddSlashes(pg_result($resaco,$conresaco,'ed280_i_ano'))."','$this->ed280_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed280_i_matcenso"]) || $this->ed280_i_matcenso != "")
           $resac = db_query("insert into db_acount values($acount,2731,15575,'".AddSlashes(pg_result($resaco,$conresaco,'ed280_i_matcenso'))."','$this->ed280_i_matcenso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "alunomatcenso nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed280_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "alunomatcenso nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed280_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed280_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed280_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed280_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15571,'$ed280_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2731,15571,'','".AddSlashes(pg_result($resaco,$iresaco,'ed280_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2731,15572,'','".AddSlashes(pg_result($resaco,$iresaco,'ed280_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2731,15573,'','".AddSlashes(pg_result($resaco,$iresaco,'ed280_i_turmacenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2731,15574,'','".AddSlashes(pg_result($resaco,$iresaco,'ed280_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2731,15575,'','".AddSlashes(pg_result($resaco,$iresaco,'ed280_i_matcenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunomatcenso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed280_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed280_i_codigo = $ed280_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "alunomatcenso nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed280_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "alunomatcenso nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed280_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed280_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunomatcenso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed280_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunomatcenso ";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = alunomatcenso.ed280_i_aluno";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join aluno aluno2 on  aluno2.ed47_i_codigo = aluno.ed47_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed280_i_codigo!=null ){
         $sql2 .= " where alunomatcenso.ed280_i_codigo = $ed280_i_codigo "; 
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
   function sql_query_file ( $ed280_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunomatcenso ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed280_i_codigo!=null ){
         $sql2 .= " where alunomatcenso.ed280_i_codigo = $ed280_i_codigo "; 
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