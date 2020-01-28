<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: patrimonio
//CLASSE DA ENTIDADE benstipodepreciacao
class cl_benstipodepreciacao { 
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
   var $t46_sequencial = 0; 
   var $t46_descricao = null; 
   var $t46_quantidadeano = 0; 
   var $t46_percentual = 0; 
   var $t46_observacao = null; 
   var $t46_depreciavel = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t46_sequencial = int4 = Código 
                 t46_descricao = varchar(150) = Descrição da Depreciação 
                 t46_quantidadeano = numeric(10) = Vida Útil 
                 t46_percentual = numeric(10) = Percentual Depreciado ao Ano 
                 t46_observacao = text = Observação 
                 t46_depreciavel = bool = Bem Depreciavel 
                 ";
   //funcao construtor da classe 
   function cl_benstipodepreciacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benstipodepreciacao"); 
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
       $this->t46_sequencial = ($this->t46_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t46_sequencial"]:$this->t46_sequencial);
       $this->t46_descricao = ($this->t46_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["t46_descricao"]:$this->t46_descricao);
       $this->t46_quantidadeano = ($this->t46_quantidadeano == ""?@$GLOBALS["HTTP_POST_VARS"]["t46_quantidadeano"]:$this->t46_quantidadeano);
       $this->t46_percentual = ($this->t46_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["t46_percentual"]:$this->t46_percentual);
       $this->t46_observacao = ($this->t46_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t46_observacao"]:$this->t46_observacao);
       $this->t46_depreciavel = ($this->t46_depreciavel == "f"?@$GLOBALS["HTTP_POST_VARS"]["t46_depreciavel"]:$this->t46_depreciavel);
     }else{
       $this->t46_sequencial = ($this->t46_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t46_sequencial"]:$this->t46_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t46_sequencial){ 
      $this->atualizacampos();
     if($this->t46_descricao == null ){ 
       $this->erro_sql = " Campo Descrição da Depreciação nao Informado.";
       $this->erro_campo = "t46_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t46_quantidadeano == null ){ 
       $this->erro_sql = " Campo Vida Útil nao Informado.";
       $this->erro_campo = "t46_quantidadeano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t46_percentual == null ){ 
       $this->erro_sql = " Campo Percentual Depreciado ao Ano nao Informado.";
       $this->erro_campo = "t46_percentual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t46_observacao == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "t46_observacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t46_depreciavel == null ){ 
       $this->erro_sql = " Campo Bem Depreciavel nao Informado.";
       $this->erro_campo = "t46_depreciavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t46_sequencial == "" || $t46_sequencial == null ){
       $result = db_query("select nextval('benstipodepreciacao_t46_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benstipodepreciacao_t46_sequencial_seq do campo: t46_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t46_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from benstipodepreciacao_t46_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t46_sequencial)){
         $this->erro_sql = " Campo t46_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t46_sequencial = $t46_sequencial; 
       }
     }
     if(($this->t46_sequencial == null) || ($this->t46_sequencial == "") ){ 
       $this->erro_sql = " Campo t46_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benstipodepreciacao(
                                       t46_sequencial 
                                      ,t46_descricao 
                                      ,t46_quantidadeano 
                                      ,t46_percentual 
                                      ,t46_observacao 
                                      ,t46_depreciavel 
                       )
                values (
                                $this->t46_sequencial 
                               ,'$this->t46_descricao' 
                               ,$this->t46_quantidadeano 
                               ,$this->t46_percentual 
                               ,'$this->t46_observacao' 
                               ,'$this->t46_depreciavel' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de Depreciação do Ben ($this->t46_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de Depreciação do Ben já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de Depreciação do Ben ($this->t46_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t46_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t46_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18460,'$this->t46_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3265,18460,'','".AddSlashes(pg_result($resaco,0,'t46_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3265,18461,'','".AddSlashes(pg_result($resaco,0,'t46_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3265,18462,'','".AddSlashes(pg_result($resaco,0,'t46_quantidadeano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3265,18463,'','".AddSlashes(pg_result($resaco,0,'t46_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3265,18464,'','".AddSlashes(pg_result($resaco,0,'t46_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3265,19382,'','".AddSlashes(pg_result($resaco,0,'t46_depreciavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t46_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update benstipodepreciacao set ";
     $virgula = "";
     if(trim($this->t46_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t46_sequencial"])){ 
       $sql  .= $virgula." t46_sequencial = $this->t46_sequencial ";
       $virgula = ",";
       if(trim($this->t46_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "t46_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t46_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t46_descricao"])){ 
       $sql  .= $virgula." t46_descricao = '$this->t46_descricao' ";
       $virgula = ",";
       if(trim($this->t46_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição da Depreciação nao Informado.";
         $this->erro_campo = "t46_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t46_quantidadeano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t46_quantidadeano"])){ 
       $sql  .= $virgula." t46_quantidadeano = $this->t46_quantidadeano ";
       $virgula = ",";
       if(trim($this->t46_quantidadeano) == null ){ 
         $this->erro_sql = " Campo Vida Útil nao Informado.";
         $this->erro_campo = "t46_quantidadeano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t46_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t46_percentual"])){ 
       $sql  .= $virgula." t46_percentual = $this->t46_percentual ";
       $virgula = ",";
       if(trim($this->t46_percentual) == null ){ 
         $this->erro_sql = " Campo Percentual Depreciado ao Ano nao Informado.";
         $this->erro_campo = "t46_percentual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t46_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t46_observacao"])){ 
       $sql  .= $virgula." t46_observacao = '$this->t46_observacao' ";
       $virgula = ",";
       if(trim($this->t46_observacao) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "t46_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t46_depreciavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t46_depreciavel"])){ 
       $sql  .= $virgula." t46_depreciavel = '$this->t46_depreciavel' ";
       $virgula = ",";
       if(trim($this->t46_depreciavel) == null ){ 
         $this->erro_sql = " Campo Bem Depreciavel nao Informado.";
         $this->erro_campo = "t46_depreciavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t46_sequencial!=null){
       $sql .= " t46_sequencial = $this->t46_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t46_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18460,'$this->t46_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t46_sequencial"]) || $this->t46_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3265,18460,'".AddSlashes(pg_result($resaco,$conresaco,'t46_sequencial'))."','$this->t46_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t46_descricao"]) || $this->t46_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3265,18461,'".AddSlashes(pg_result($resaco,$conresaco,'t46_descricao'))."','$this->t46_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t46_quantidadeano"]) || $this->t46_quantidadeano != "")
           $resac = db_query("insert into db_acount values($acount,3265,18462,'".AddSlashes(pg_result($resaco,$conresaco,'t46_quantidadeano'))."','$this->t46_quantidadeano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t46_percentual"]) || $this->t46_percentual != "")
           $resac = db_query("insert into db_acount values($acount,3265,18463,'".AddSlashes(pg_result($resaco,$conresaco,'t46_percentual'))."','$this->t46_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t46_observacao"]) || $this->t46_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3265,18464,'".AddSlashes(pg_result($resaco,$conresaco,'t46_observacao'))."','$this->t46_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t46_depreciavel"]) || $this->t46_depreciavel != "")
           $resac = db_query("insert into db_acount values($acount,3265,19382,'".AddSlashes(pg_result($resaco,$conresaco,'t46_depreciavel'))."','$this->t46_depreciavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de Depreciação do Ben nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t46_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de Depreciação do Ben nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t46_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t46_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t46_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t46_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18460,'$t46_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3265,18460,'','".AddSlashes(pg_result($resaco,$iresaco,'t46_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3265,18461,'','".AddSlashes(pg_result($resaco,$iresaco,'t46_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3265,18462,'','".AddSlashes(pg_result($resaco,$iresaco,'t46_quantidadeano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3265,18463,'','".AddSlashes(pg_result($resaco,$iresaco,'t46_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3265,18464,'','".AddSlashes(pg_result($resaco,$iresaco,'t46_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3265,19382,'','".AddSlashes(pg_result($resaco,$iresaco,'t46_depreciavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benstipodepreciacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t46_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t46_sequencial = $t46_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de Depreciação do Ben nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t46_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de Depreciação do Ben nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t46_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t46_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:benstipodepreciacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t46_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstipodepreciacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($t46_sequencial!=null ){
         $sql2 .= " where benstipodepreciacao.t46_sequencial = $t46_sequencial "; 
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
   function sql_query_file ( $t46_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstipodepreciacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($t46_sequencial!=null ){
         $sql2 .= " where benstipodepreciacao.t46_sequencial = $t46_sequencial "; 
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