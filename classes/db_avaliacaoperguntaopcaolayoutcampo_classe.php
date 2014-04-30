<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE avaliacaoperguntaopcaolayoutcampo
class cl_avaliacaoperguntaopcaolayoutcampo { 
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
   var $ed313_sequencial = 0; 
   var $ed313_ano = 0; 
   var $ed313_db_layoutcampo = 0; 
   var $ed313_avaliacaoperguntaopcao = 0; 
   var $ed313_layoutvalorcampo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed313_sequencial = int4 = Código 
                 ed313_ano = int4 = Ano 
                 ed313_db_layoutcampo = int4 = Código do campo 
                 ed313_avaliacaoperguntaopcao = int4 = Código da opção 
                 ed313_layoutvalorcampo = varchar(255) = Valor padrao 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaoperguntaopcaolayoutcampo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoperguntaopcaolayoutcampo"); 
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
       $this->ed313_sequencial = ($this->ed313_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed313_sequencial"]:$this->ed313_sequencial);
       $this->ed313_ano = ($this->ed313_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed313_ano"]:$this->ed313_ano);
       $this->ed313_db_layoutcampo = ($this->ed313_db_layoutcampo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed313_db_layoutcampo"]:$this->ed313_db_layoutcampo);
       $this->ed313_avaliacaoperguntaopcao = ($this->ed313_avaliacaoperguntaopcao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed313_avaliacaoperguntaopcao"]:$this->ed313_avaliacaoperguntaopcao);
       $this->ed313_layoutvalorcampo = ($this->ed313_layoutvalorcampo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed313_layoutvalorcampo"]:$this->ed313_layoutvalorcampo);
     }else{
       $this->ed313_sequencial = ($this->ed313_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed313_sequencial"]:$this->ed313_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed313_sequencial){ 
      $this->atualizacampos();
     if($this->ed313_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "ed313_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed313_db_layoutcampo == null ){ 
       $this->erro_sql = " Campo Código do campo nao Informado.";
       $this->erro_campo = "ed313_db_layoutcampo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed313_avaliacaoperguntaopcao == null ){ 
       $this->erro_sql = " Campo Código da opção nao Informado.";
       $this->erro_campo = "ed313_avaliacaoperguntaopcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed313_sequencial == "" || $ed313_sequencial == null ){
       $result = db_query("select nextval('avaliacaoperguntaopcaolayoutcampo_ed313_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoperguntaopcaolayoutcampo_ed313_sequencial_seq do campo: ed313_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed313_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaoperguntaopcaolayoutcampo_ed313_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed313_sequencial)){
         $this->erro_sql = " Campo ed313_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed313_sequencial = $ed313_sequencial; 
       }
     }
     if(($this->ed313_sequencial == null) || ($this->ed313_sequencial == "") ){ 
       $this->erro_sql = " Campo ed313_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoperguntaopcaolayoutcampo(
                                       ed313_sequencial 
                                      ,ed313_ano 
                                      ,ed313_db_layoutcampo 
                                      ,ed313_avaliacaoperguntaopcao 
                                      ,ed313_layoutvalorcampo 
                       )
                values (
                                $this->ed313_sequencial 
                               ,$this->ed313_ano 
                               ,$this->ed313_db_layoutcampo 
                               ,$this->ed313_avaliacaoperguntaopcao 
                               ,'$this->ed313_layoutvalorcampo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Campo do layout ($this->ed313_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Campo do layout já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Campo do layout ($this->ed313_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed313_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed313_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18921,'$this->ed313_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3364,18921,'','".AddSlashes(pg_result($resaco,0,'ed313_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3364,18922,'','".AddSlashes(pg_result($resaco,0,'ed313_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3364,18923,'','".AddSlashes(pg_result($resaco,0,'ed313_db_layoutcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3364,18924,'','".AddSlashes(pg_result($resaco,0,'ed313_avaliacaoperguntaopcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3364,19111,'','".AddSlashes(pg_result($resaco,0,'ed313_layoutvalorcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed313_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaoperguntaopcaolayoutcampo set ";
     $virgula = "";
     if(trim($this->ed313_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed313_sequencial"])){ 
       $sql  .= $virgula." ed313_sequencial = $this->ed313_sequencial ";
       $virgula = ",";
       if(trim($this->ed313_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed313_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed313_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed313_ano"])){ 
       $sql  .= $virgula." ed313_ano = $this->ed313_ano ";
       $virgula = ",";
       if(trim($this->ed313_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "ed313_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed313_db_layoutcampo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed313_db_layoutcampo"])){ 
       $sql  .= $virgula." ed313_db_layoutcampo = $this->ed313_db_layoutcampo ";
       $virgula = ",";
       if(trim($this->ed313_db_layoutcampo) == null ){ 
         $this->erro_sql = " Campo Código do campo nao Informado.";
         $this->erro_campo = "ed313_db_layoutcampo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed313_avaliacaoperguntaopcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed313_avaliacaoperguntaopcao"])){ 
       $sql  .= $virgula." ed313_avaliacaoperguntaopcao = $this->ed313_avaliacaoperguntaopcao ";
       $virgula = ",";
       if(trim($this->ed313_avaliacaoperguntaopcao) == null ){ 
         $this->erro_sql = " Campo Código da opção nao Informado.";
         $this->erro_campo = "ed313_avaliacaoperguntaopcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed313_layoutvalorcampo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed313_layoutvalorcampo"])){ 
       $sql  .= $virgula." ed313_layoutvalorcampo = '$this->ed313_layoutvalorcampo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed313_sequencial!=null){
       $sql .= " ed313_sequencial = $this->ed313_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed313_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18921,'$this->ed313_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed313_sequencial"]) || $this->ed313_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3364,18921,'".AddSlashes(pg_result($resaco,$conresaco,'ed313_sequencial'))."','$this->ed313_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed313_ano"]) || $this->ed313_ano != "")
           $resac = db_query("insert into db_acount values($acount,3364,18922,'".AddSlashes(pg_result($resaco,$conresaco,'ed313_ano'))."','$this->ed313_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed313_db_layoutcampo"]) || $this->ed313_db_layoutcampo != "")
           $resac = db_query("insert into db_acount values($acount,3364,18923,'".AddSlashes(pg_result($resaco,$conresaco,'ed313_db_layoutcampo'))."','$this->ed313_db_layoutcampo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed313_avaliacaoperguntaopcao"]) || $this->ed313_avaliacaoperguntaopcao != "")
           $resac = db_query("insert into db_acount values($acount,3364,18924,'".AddSlashes(pg_result($resaco,$conresaco,'ed313_avaliacaoperguntaopcao'))."','$this->ed313_avaliacaoperguntaopcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed313_layoutvalorcampo"]) || $this->ed313_layoutvalorcampo != "")
           $resac = db_query("insert into db_acount values($acount,3364,19111,'".AddSlashes(pg_result($resaco,$conresaco,'ed313_layoutvalorcampo'))."','$this->ed313_layoutvalorcampo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Campo do layout nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed313_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Campo do layout nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed313_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed313_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed313_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed313_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18921,'$ed313_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3364,18921,'','".AddSlashes(pg_result($resaco,$iresaco,'ed313_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3364,18922,'','".AddSlashes(pg_result($resaco,$iresaco,'ed313_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3364,18923,'','".AddSlashes(pg_result($resaco,$iresaco,'ed313_db_layoutcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3364,18924,'','".AddSlashes(pg_result($resaco,$iresaco,'ed313_avaliacaoperguntaopcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3364,19111,'','".AddSlashes(pg_result($resaco,$iresaco,'ed313_layoutvalorcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from avaliacaoperguntaopcaolayoutcampo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed313_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed313_sequencial = $ed313_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Campo do layout nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed313_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Campo do layout nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed313_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed313_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoperguntaopcaolayoutcampo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed313_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoperguntaopcaolayoutcampo ";
     $sql .= "      inner join db_layoutcampos  on  db_layoutcampos.db52_codigo = avaliacaoperguntaopcaolayoutcampo.ed313_db_layoutcampo";
     $sql .= "      inner join avaliacaoresposta  on  avaliacaoresposta.db106_sequencial = avaliacaoperguntaopcaolayoutcampo.ed313_avaliacaoperguntaopcao";
     $sql .= "      inner join db_layoutlinha  on  db_layoutlinha.db51_codigo = db_layoutcampos.db52_layoutlinha";
     $sql .= "      inner join db_layoutformat  on  db_layoutformat.db53_codigo = db_layoutcampos.db52_layoutformat";
     $sql .= "      inner join avaliacaoperguntaopcao  on  avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao";
     $sql2 = "";
     if($dbwhere==""){
       if($ed313_sequencial!=null ){
         $sql2 .= " where avaliacaoperguntaopcaolayoutcampo.ed313_sequencial = $ed313_sequencial "; 
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
   function sql_query_file ( $ed313_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoperguntaopcaolayoutcampo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed313_sequencial!=null ){
         $sql2 .= " where avaliacaoperguntaopcaolayoutcampo.ed313_sequencial = $ed313_sequencial "; 
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
   function sql_query_campo ( $ed313_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from avaliacaoperguntaopcaolayoutcampo ";
    $sql .= "      inner join avaliacaoperguntaopcao  on  avaliacaoperguntaopcao.db104_sequencial = avaliacaoperguntaopcaolayoutcampo.ed313_avaliacaoperguntaopcao";
    $sql .= "      inner join db_layoutcampos on  db_layoutcampos.db52_codigo = avaliacaoperguntaopcaolayoutcampo.ed313_db_layoutcampo";
    $sql .= "      inner join db_layoutlinha on  db_layoutlinha.db51_codigo = db_layoutcampos.db52_layoutlinha";
    $sql .= "      inner join db_layouttxt  on  db_layouttxt.db50_codigo = db_layoutlinha.db51_layouttxt";
    $sql2 = "";
    if($dbwhere==""){
      if($ed313_sequencial!=null ){
        $sql2 .= " where avaliacaoperguntaopcaolayoutcampo.ed313_sequencial = $ed313_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
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
   function sql_query_avaliacao ( $ed313_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

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
    $sql .= " from  avaliacaogrupopergunta";
    $sql .= "      inner join avaliacaopergunta                 on db103_avaliacaogrupopergunta   = db102_sequencial";
    $sql .= "      inner join avaliacaoperguntaopcao            on db104_avaliacaopergunta        = db103_sequencial";
    $sql .= "      inner join avaliacaoperguntaopcaolayoutcampo on db104_sequencial              = ed313_avaliacaoperguntaopcao";
    $sql .= "      inner join db_layoutcampos                   on db52_codigo                    =  ed313_db_layoutcampo";
    $sql2 = "";
    if($dbwhere==""){
      if($ed313_sequencial!=null ){
        $sql2 .= " where avaliacaoperguntaopcaolayoutcampo.ed313_sequencial = $ed313_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
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