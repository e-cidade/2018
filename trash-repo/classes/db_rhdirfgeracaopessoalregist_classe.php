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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhdirfgeracaopessoalregist
class cl_rhdirfgeracaopessoalregist { 
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
   var $rh99_sequencial = 0; 
   var $rh99_rhdirfgeracaodadospessoalvalor = 0; 
   var $rh99_regist = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh99_sequencial = int4 = Código Sequencial 
                 rh99_rhdirfgeracaodadospessoalvalor = int4 = Código do valor 
                 rh99_regist = int4 = Código da Matrícula 
                 ";
   //funcao construtor da classe 
   function cl_rhdirfgeracaopessoalregist() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdirfgeracaopessoalregist"); 
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
       $this->rh99_sequencial = ($this->rh99_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh99_sequencial"]:$this->rh99_sequencial);
       $this->rh99_rhdirfgeracaodadospessoalvalor = ($this->rh99_rhdirfgeracaodadospessoalvalor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh99_rhdirfgeracaodadospessoalvalor"]:$this->rh99_rhdirfgeracaodadospessoalvalor);
       $this->rh99_regist = ($this->rh99_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh99_regist"]:$this->rh99_regist);
     }else{
       $this->rh99_sequencial = ($this->rh99_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh99_sequencial"]:$this->rh99_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh99_sequencial){ 
      $this->atualizacampos();
     if($this->rh99_rhdirfgeracaodadospessoalvalor == null ){ 
       $this->erro_sql = " Campo Código do valor nao Informado.";
       $this->erro_campo = "rh99_rhdirfgeracaodadospessoalvalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh99_regist == null ){ 
       $this->erro_sql = " Campo Código da Matrícula nao Informado.";
       $this->erro_campo = "rh99_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh99_sequencial == "" || $rh99_sequencial == null ){
       $result = db_query("select nextval('rhdirfgeracaopessoalregist_rh99_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdirfgeracaopessoalregist_rh99_sequencial_seq do campo: rh99_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh99_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdirfgeracaopessoalregist_rh99_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh99_sequencial)){
         $this->erro_sql = " Campo rh99_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh99_sequencial = $rh99_sequencial; 
       }
     }
     if(($this->rh99_sequencial == null) || ($this->rh99_sequencial == "") ){ 
       $this->erro_sql = " Campo rh99_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdirfgeracaopessoalregist(
                                       rh99_sequencial 
                                      ,rh99_rhdirfgeracaodadospessoalvalor 
                                      ,rh99_regist 
                       )
                values (
                                $this->rh99_sequencial 
                               ,$this->rh99_rhdirfgeracaodadospessoalvalor 
                               ,$this->rh99_regist 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matriculas da dirf ($this->rh99_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matriculas da dirf já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matriculas da dirf ($this->rh99_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh99_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["ignoreAccount"])) {
       $resaco = $this->sql_record($this->sql_query_file($this->rh99_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17806,'$this->rh99_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3143,17806,'','".AddSlashes(pg_result($resaco,0,'rh99_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3143,17807,'','".AddSlashes(pg_result($resaco,0,'rh99_rhdirfgeracaodadospessoalvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3143,17808,'','".AddSlashes(pg_result($resaco,0,'rh99_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh99_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhdirfgeracaopessoalregist set ";
     $virgula = "";
     if(trim($this->rh99_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh99_sequencial"])){ 
       $sql  .= $virgula." rh99_sequencial = $this->rh99_sequencial ";
       $virgula = ",";
       if(trim($this->rh99_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "rh99_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh99_rhdirfgeracaodadospessoalvalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh99_rhdirfgeracaodadospessoalvalor"])){ 
       $sql  .= $virgula." rh99_rhdirfgeracaodadospessoalvalor = $this->rh99_rhdirfgeracaodadospessoalvalor ";
       $virgula = ",";
       if(trim($this->rh99_rhdirfgeracaodadospessoalvalor) == null ){ 
         $this->erro_sql = " Campo Código do valor nao Informado.";
         $this->erro_campo = "rh99_rhdirfgeracaodadospessoalvalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh99_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh99_regist"])){ 
       $sql  .= $virgula." rh99_regist = $this->rh99_regist ";
       $virgula = ",";
       if(trim($this->rh99_regist) == null ){ 
         $this->erro_sql = " Campo Código da Matrícula nao Informado.";
         $this->erro_campo = "rh99_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh99_sequencial!=null){
       $sql .= " rh99_sequencial = $this->rh99_sequencial";
     }
     if (!isset($_SESSION["ignoreAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->rh99_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17806,'$this->rh99_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh99_sequencial"]) || $this->rh99_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3143,17806,'".AddSlashes(pg_result($resaco,$conresaco,'rh99_sequencial'))."','$this->rh99_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh99_rhdirfgeracaodadospessoalvalor"]) || $this->rh99_rhdirfgeracaodadospessoalvalor != "")
             $resac = db_query("insert into db_acount values($acount,3143,17807,'".AddSlashes(pg_result($resaco,$conresaco,'rh99_rhdirfgeracaodadospessoalvalor'))."','$this->rh99_rhdirfgeracaodadospessoalvalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh99_regist"]) || $this->rh99_regist != "")
             $resac = db_query("insert into db_acount values($acount,3143,17808,'".AddSlashes(pg_result($resaco,$conresaco,'rh99_regist'))."','$this->rh99_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matriculas da dirf nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh99_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matriculas da dirf nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh99_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh99_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh99_sequencial=null,$dbwhere=null) { 
     if (!isset($_SESSION["ignoreAccount"])) {
       
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($rh99_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco!=false)||($this->numrows!=0)) {
         for ($iresaco=0;$iresaco < $this->numrows;$iresaco++) {
           
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17806,'$rh99_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,3143,17806,'','".AddSlashes(pg_result($resaco,$iresaco,'rh99_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3143,17807,'','".AddSlashes(pg_result($resaco,$iresaco,'rh99_rhdirfgeracaodadospessoalvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3143,17808,'','".AddSlashes(pg_result($resaco,$iresaco,'rh99_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhdirfgeracaopessoalregist
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh99_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh99_sequencial = $rh99_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matriculas da dirf nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh99_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matriculas da dirf nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh99_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh99_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhdirfgeracaopessoalregist";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh99_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdirfgeracaopessoalregist ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhdirfgeracaopessoalregist.rh99_regist";
     $sql .= "      inner join rhdirfgeracaodadospessoalvalor  on  rhdirfgeracaodadospessoalvalor.rh98_sequencial = rhdirfgeracaopessoalregist.rh99_rhdirfgeracaodadospessoalvalor";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      inner join rhdirfgeracaodadospessoal  as a on   a.rh96_sequencial = rhdirfgeracaodadospessoalvalor.rh98_rhdirfgeracaodadospessoal";
     $sql .= "      inner join rhdirftipovalor  on  rhdirftipovalor.rh97_sequencial = rhdirfgeracaodadospessoalvalor.rh98_rhdirftipovalor";
     $sql2 = "";
     if($dbwhere==""){
       if($rh99_sequencial!=null ){
         $sql2 .= " where rhdirfgeracaopessoalregist.rh99_sequencial = $rh99_sequencial "; 
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
   function sql_query_file ( $rh99_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdirfgeracaopessoalregist ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh99_sequencial!=null ){
         $sql2 .= " where rhdirfgeracaopessoalregist.rh99_sequencial = $rh99_sequencial "; 
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