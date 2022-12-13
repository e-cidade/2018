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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_cotasagendamentoprofissional
class cl_sau_cotasagendamentoprofissional { 
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
   var $s164_codigo = 0; 
   var $s164_especmedico = 0; 
   var $s164_cotaagendamento = 0; 
   var $s164_quantidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s164_codigo = int4 = Código 
                 s164_especmedico = int4 = Especialidade 
                 s164_cotaagendamento = int4 = Cota 
                 s164_quantidade = int4 = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_sau_cotasagendamentoprofissional() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_cotasagendamentoprofissional"); 
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
       $this->s164_codigo = ($this->s164_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s164_codigo"]:$this->s164_codigo);
       $this->s164_especmedico = ($this->s164_especmedico == ""?@$GLOBALS["HTTP_POST_VARS"]["s164_especmedico"]:$this->s164_especmedico);
       $this->s164_cotaagendamento = ($this->s164_cotaagendamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s164_cotaagendamento"]:$this->s164_cotaagendamento);
       $this->s164_quantidade = ($this->s164_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["s164_quantidade"]:$this->s164_quantidade);
     }else{
       $this->s164_codigo = ($this->s164_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s164_codigo"]:$this->s164_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s164_codigo){ 
      $this->atualizacampos();
     if($this->s164_especmedico == null ){ 
       $this->erro_sql = " Campo Especialidade nao Informado.";
       $this->erro_campo = "s164_especmedico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s164_cotaagendamento == null ){ 
       $this->erro_sql = " Campo Cota nao Informado.";
       $this->erro_campo = "s164_cotaagendamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s164_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "s164_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s164_codigo == "" || $s164_codigo == null ){
       $result = db_query("select nextval('sau_cotasagendamentoprofissional_s164_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_cotasagendamentoprofissional_s164_codigo_seq do campo: s164_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s164_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_cotasagendamentoprofissional_s164_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s164_codigo)){
         $this->erro_sql = " Campo s164_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s164_codigo = $s164_codigo; 
       }
     }
     if(($this->s164_codigo == null) || ($this->s164_codigo == "") ){ 
       $this->erro_sql = " Campo s164_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_cotasagendamentoprofissional(
                                       s164_codigo 
                                      ,s164_especmedico 
                                      ,s164_cotaagendamento 
                                      ,s164_quantidade 
                       )
                values (
                                $this->s164_codigo 
                               ,$this->s164_especmedico 
                               ,$this->s164_cotaagendamento 
                               ,$this->s164_quantidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cotas de agendamento por profissional ($this->s164_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cotas de agendamento por profissional já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cotas de agendamento por profissional ($this->s164_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s164_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s164_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18190,'$this->s164_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3214,18190,'','".AddSlashes(pg_result($resaco,0,'s164_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3214,18191,'','".AddSlashes(pg_result($resaco,0,'s164_especmedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3214,18192,'','".AddSlashes(pg_result($resaco,0,'s164_cotaagendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3214,18193,'','".AddSlashes(pg_result($resaco,0,'s164_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s164_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_cotasagendamentoprofissional set ";
     $virgula = "";
     if(trim($this->s164_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s164_codigo"])){ 
       $sql  .= $virgula." s164_codigo = $this->s164_codigo ";
       $virgula = ",";
       if(trim($this->s164_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s164_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s164_especmedico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s164_especmedico"])){ 
       $sql  .= $virgula." s164_especmedico = $this->s164_especmedico ";
       $virgula = ",";
       if(trim($this->s164_especmedico) == null ){ 
         $this->erro_sql = " Campo Especialidade nao Informado.";
         $this->erro_campo = "s164_especmedico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s164_cotaagendamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s164_cotaagendamento"])){ 
       $sql  .= $virgula." s164_cotaagendamento = $this->s164_cotaagendamento ";
       $virgula = ",";
       if(trim($this->s164_cotaagendamento) == null ){ 
         $this->erro_sql = " Campo Cota nao Informado.";
         $this->erro_campo = "s164_cotaagendamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s164_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s164_quantidade"])){ 
       $sql  .= $virgula." s164_quantidade = $this->s164_quantidade ";
       $virgula = ",";
       if(trim($this->s164_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "s164_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s164_codigo!=null){
       $sql .= " s164_codigo = $this->s164_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s164_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18190,'$this->s164_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s164_codigo"]) || $this->s164_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3214,18190,'".AddSlashes(pg_result($resaco,$conresaco,'s164_codigo'))."','$this->s164_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s164_especmedico"]) || $this->s164_especmedico != "")
           $resac = db_query("insert into db_acount values($acount,3214,18191,'".AddSlashes(pg_result($resaco,$conresaco,'s164_especmedico'))."','$this->s164_especmedico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s164_cotaagendamento"]) || $this->s164_cotaagendamento != "")
           $resac = db_query("insert into db_acount values($acount,3214,18192,'".AddSlashes(pg_result($resaco,$conresaco,'s164_cotaagendamento'))."','$this->s164_cotaagendamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s164_quantidade"]) || $this->s164_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,3214,18193,'".AddSlashes(pg_result($resaco,$conresaco,'s164_quantidade'))."','$this->s164_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cotas de agendamento por profissional nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s164_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cotas de agendamento por profissional nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s164_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s164_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s164_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s164_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18190,'$s164_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3214,18190,'','".AddSlashes(pg_result($resaco,$iresaco,'s164_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3214,18191,'','".AddSlashes(pg_result($resaco,$iresaco,'s164_especmedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3214,18192,'','".AddSlashes(pg_result($resaco,$iresaco,'s164_cotaagendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3214,18193,'','".AddSlashes(pg_result($resaco,$iresaco,'s164_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_cotasagendamentoprofissional
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s164_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s164_codigo = $s164_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cotas de agendamento por profissional nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s164_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cotas de agendamento por profissional nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s164_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s164_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_cotasagendamentoprofissional";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s164_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_cotasagendamentoprofissional ";
     $sql .= "      inner join sau_cotasagendamento  on  sau_cotasagendamento.s163_i_codigo = sau_cotasagendamentoprofissional.s164_cotaagendamento";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = sau_cotasagendamentoprofissional.s164_especmedico";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = sau_cotasagendamento.s163_i_rhcbo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = sau_cotasagendamento.s163_i_upsprestadora and  unidades.sd02_i_codigo = sau_cotasagendamento.s163_i_upssolicitante";
     $sql .= "      inner join rhcbo  as a on   a.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql2 = "";
     if($dbwhere==""){
       if($s164_codigo!=null ){
         $sql2 .= " where sau_cotasagendamentoprofissional.s164_codigo = $s164_codigo "; 
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
   function sql_query_file ( $s164_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_cotasagendamentoprofissional ";
     $sql2 = "";
     if($dbwhere==""){
       if($s164_codigo!=null ){
         $sql2 .= " where sau_cotasagendamentoprofissional.s164_codigo = $s164_codigo "; 
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

  function sql_query_cotas_profissionais ( $s164_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

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
     $sql .= " from sau_cotasagendamentoprofissional ";
     $sql .= "      inner join sau_cotasagendamento  on  sau_cotasagendamento.s163_i_codigo = sau_cotasagendamentoprofissional.s164_cotaagendamento";
     $sql2 = "";

     if($dbwhere==""){
       if($s164_codigo!=null ){
         $sql2 .= " where sau_cotasagendamentoprofissional.s164_codigo = $s164_codigo ";
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