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

//MODULO: escola
//CLASSE DA ENTIDADE regimematdiv
class cl_regimematdiv { 
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
   var $ed219_i_codigo = 0; 
   var $ed219_i_regimemat = 0; 
   var $ed219_c_nome = null; 
   var $ed219_c_abrev = null; 
   var $ed219_i_ordenacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed219_i_codigo = int8 = Código 
                 ed219_i_regimemat = int8 = Regime de Matrícula 
                 ed219_c_nome = char(30) = Descrição 
                 ed219_c_abrev = char(10) = Abreviatura 
                 ed219_i_ordenacao = int4 = Ordenação 
                 ";
   //funcao construtor da classe 
   function cl_regimematdiv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("regimematdiv"); 
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
       $this->ed219_i_codigo = ($this->ed219_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_codigo"]:$this->ed219_i_codigo);
       $this->ed219_i_regimemat = ($this->ed219_i_regimemat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_regimemat"]:$this->ed219_i_regimemat);
       $this->ed219_c_nome = ($this->ed219_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_c_nome"]:$this->ed219_c_nome);
       $this->ed219_c_abrev = ($this->ed219_c_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_c_abrev"]:$this->ed219_c_abrev);
       $this->ed219_i_ordenacao = ($this->ed219_i_ordenacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_ordenacao"]:$this->ed219_i_ordenacao);
     }else{
       $this->ed219_i_codigo = ($this->ed219_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_codigo"]:$this->ed219_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed219_i_codigo){ 
      $this->atualizacampos();
     if($this->ed219_i_regimemat == null ){ 
       $this->erro_sql = " Campo Regime de Matrícula nao Informado.";
       $this->erro_campo = "ed219_i_regimemat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed219_c_nome == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed219_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed219_c_abrev == null ){ 
       $this->erro_sql = " Campo Abreviatura nao Informado.";
       $this->erro_campo = "ed219_c_abrev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed219_i_ordenacao == null ){ 
       $this->erro_sql = " Campo Ordenação nao Informado.";
       $this->erro_campo = "ed219_i_ordenacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed219_i_codigo == "" || $ed219_i_codigo == null ){
       $result = db_query("select nextval('regimematdiv_ed219_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: regimematdiv_ed219_i_codigo_seq do campo: ed219_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed219_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from regimematdiv_ed219_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed219_i_codigo)){
         $this->erro_sql = " Campo ed219_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed219_i_codigo = $ed219_i_codigo; 
       }
     }
     if(($this->ed219_i_codigo == null) || ($this->ed219_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed219_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into regimematdiv(
                                       ed219_i_codigo 
                                      ,ed219_i_regimemat 
                                      ,ed219_c_nome 
                                      ,ed219_c_abrev 
                                      ,ed219_i_ordenacao 
                       )
                values (
                                $this->ed219_i_codigo 
                               ,$this->ed219_i_regimemat 
                               ,'$this->ed219_c_nome' 
                               ,'$this->ed219_c_abrev' 
                               ,$this->ed219_i_ordenacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Divisões do Regime de Matrícula ($this->ed219_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Divisões do Regime de Matrícula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Divisões do Regime de Matrícula ($this->ed219_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed219_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed219_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14918,'$this->ed219_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2626,14918,'','".AddSlashes(pg_result($resaco,0,'ed219_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2626,14921,'','".AddSlashes(pg_result($resaco,0,'ed219_i_regimemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2626,14919,'','".AddSlashes(pg_result($resaco,0,'ed219_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2626,14920,'','".AddSlashes(pg_result($resaco,0,'ed219_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2626,14922,'','".AddSlashes(pg_result($resaco,0,'ed219_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed219_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update regimematdiv set ";
     $virgula = "";
     if(trim($this->ed219_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_codigo"])){ 
       $sql  .= $virgula." ed219_i_codigo = $this->ed219_i_codigo ";
       $virgula = ",";
       if(trim($this->ed219_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed219_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed219_i_regimemat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_regimemat"])){ 
       $sql  .= $virgula." ed219_i_regimemat = $this->ed219_i_regimemat ";
       $virgula = ",";
       if(trim($this->ed219_i_regimemat) == null ){ 
         $this->erro_sql = " Campo Regime de Matrícula nao Informado.";
         $this->erro_campo = "ed219_i_regimemat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed219_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_c_nome"])){ 
       $sql  .= $virgula." ed219_c_nome = '$this->ed219_c_nome' ";
       $virgula = ",";
       if(trim($this->ed219_c_nome) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed219_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed219_c_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_c_abrev"])){ 
       $sql  .= $virgula." ed219_c_abrev = '$this->ed219_c_abrev' ";
       $virgula = ",";
       if(trim($this->ed219_c_abrev) == null ){ 
         $this->erro_sql = " Campo Abreviatura nao Informado.";
         $this->erro_campo = "ed219_c_abrev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed219_i_ordenacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_ordenacao"])){ 
       $sql  .= $virgula." ed219_i_ordenacao = $this->ed219_i_ordenacao ";
       $virgula = ",";
       if(trim($this->ed219_i_ordenacao) == null ){ 
         $this->erro_sql = " Campo Ordenação nao Informado.";
         $this->erro_campo = "ed219_i_ordenacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed219_i_codigo!=null){
       $sql .= " ed219_i_codigo = $this->ed219_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed219_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14918,'$this->ed219_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_codigo"]) || $this->ed219_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2626,14918,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_i_codigo'))."','$this->ed219_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_regimemat"]) || $this->ed219_i_regimemat != "")
           $resac = db_query("insert into db_acount values($acount,2626,14921,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_i_regimemat'))."','$this->ed219_i_regimemat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_c_nome"]) || $this->ed219_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,2626,14919,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_c_nome'))."','$this->ed219_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_c_abrev"]) || $this->ed219_c_abrev != "")
           $resac = db_query("insert into db_acount values($acount,2626,14920,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_c_abrev'))."','$this->ed219_c_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_ordenacao"]) || $this->ed219_i_ordenacao != "")
           $resac = db_query("insert into db_acount values($acount,2626,14922,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_i_ordenacao'))."','$this->ed219_i_ordenacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Divisões do Regime de Matrícula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed219_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Divisões do Regime de Matrícula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed219_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed219_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed219_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed219_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14918,'$ed219_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2626,14918,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2626,14921,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_i_regimemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2626,14919,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2626,14920,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2626,14922,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from regimematdiv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed219_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed219_i_codigo = $ed219_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Divisões do Regime de Matrícula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed219_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Divisões do Regime de Matrícula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed219_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed219_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:regimematdiv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed219_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from regimematdiv ";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = regimematdiv.ed219_i_regimemat";
     $sql2 = "";
     if($dbwhere==""){
       if($ed219_i_codigo!=null ){
         $sql2 .= " where regimematdiv.ed219_i_codigo = $ed219_i_codigo "; 
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
   function sql_query_file ( $ed219_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from regimematdiv ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed219_i_codigo!=null ){
         $sql2 .= " where regimematdiv.ed219_i_codigo = $ed219_i_codigo "; 
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